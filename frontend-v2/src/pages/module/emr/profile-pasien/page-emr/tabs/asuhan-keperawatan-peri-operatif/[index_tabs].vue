<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}<br>Halaman ke-{{ route.params.index_tabs }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" @simpanTemplate="simpanTemplate"></ButtonEmr>
          </div>
        </div>
        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-1 mb-1">
        <div style="text-align: center;font-size: large;font-weight: bold;">
          <VTag :class="isSave ? 'has-background-success' : 'has-background-danger'"
            style="color:white;width: 100%;font-size: large;">
            {{ isSave ? 'Form Sudah Tersimpan / Data Sudah Ada' : 'Form Belum Tersimpan' }}
          </VTag>
        </div>
      </div>

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
            <template #empty> No template found. </template>
            <template #loading>
              <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
              <p style="color:white">Loading data, please wait...</p>
            </template>
            <Column headerStyle="width: 8rem">
              <template #body="slotProps">
                <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                  color="info" v-tooltip-prime.top="'Pilih'">
                </VIconButton>
              </template>
            </Column>
            <Column field="namatemplate" header="Nama" :sortable="true"></Column>
            <Column field="created_at" header="Tanggal" :sortable="true">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
              </template>
            </Column>
          </DataTable>
        </template>
      </VModal>

      <VModal :open="modalBerkasPreview" title="Lihat Foto" :noclose="true" size="large" actions="right"
        @close="modalBerkasPreview = false">
        <template #content>
            <BerkasPasienView :data="dataSource" @edit="edit" @hapus="hapus" @lihat="lihat" :hide="false">
            </BerkasPasienView>
        </template>
      </VModal>

      <VModal :open="modalInput" title="Upload Foto" :noclose="true" size="medium" actions="right"
                @close="modalInput = false">
                <template #content>
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField>
                                <VLabel class="required-field">Author</VLabel>
                                <VControl>
                                    <VInput type="text" class="input" v-model="item.author" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField class="is-rounded-select is-autocomplete-select mt-0 pt-0" v-slot="{ id }">
                                <VLabel class="required-field">File</VLabel>
                                <VControl icon="fas fa-sticky-note" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.namafile" :options="d_Berkas" :optionLabel="'label'"
                                        class="is-rounded" placeholder="File" style="width: 100%;" :filter="true" showClear />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel class="required-field">Nama</VLabel>
                                <VControl icon="feather:bookmark">
                                    <input v-model="item.nama" type="text" class="input is-rounded" placeholder="Nama " />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel>Keterangan</VLabel>
                                <VControl>
                                    <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <FileUpload v-model="filePasien" mode="basic" name="demo" accept="image/jpeg,image/png"
                                @upload="onUpload" outlined
                                style=" background-color: transparent; color: var(--danger); border: 1px solid;"
                                :chooseLabel="filePasien ? filePasien.name : 'Unggah'" @select="onSelect($event)"
                                class="is-rounded w-100" />
                        </div>
                    </div>
                </template>
                <template #action>
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                        @click="simpanFile()"> Simpan
                    </VButton>
                </template>
            </VModal>


      <div class="columns is-multiline p-2">
        <div class="column is-12 buttons pb-0 mb-0 mt-0" style="margin:10px;vertical-align:middle">
          <VButton type="button" rounded outlined color="info" raised icon="feather:folder" :loading="isLoading"
            @click="pilihTemplateFix(index)"> Pilih Template
          </VButton>
        </div>

        <div class="column is-12 p-0">
          <hr class="m-0">
        </div>

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

        <div class="column is-12 p-0">
          <hr class="m-0">
        </div>

        <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                                <VButton rounded icon="feather:plus" raised bold
                                @click="addUpload()" color="warning" outlined
                                :loading="isLoading" class="mr-2">Upload</VButton>
                                <VButton rounded icon="feather:eye" raised bold @click="previewBerkas()" color="purple"
                                    outlined :loading="isLoading" class="mr-2">Lihat Foto</VButton>
                            </div>
                        </div>
                    </div>

        <div class="column is-12 ">
          <Fieldset :toggleable="true" legend="PRA OPERATIF">
            <div class="column is-12">
              <Fieldset :toggleable="true" legend="DATA UMUM">
                <div class="column is-12" v-for="(datas) in Pengkajian">
                  <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                  <div class="columns is-multiline">
                    <div class="column is-3" v-for="(data) in datas.value">
                      <VField v-if="data.type == 'checkBox'">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[data.model]" :true-value="data.subTitle" :label="data.subTitle"
                            class="p-0" color="primary" square />
                        </VControl>
                      </VField>
                      <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" style="margin-bottom: 0.5rem;">
                        <VControl raw subcontrol>
                          <input v-model="input[data.model]" class="input p-0" />
                        </VControl>
                      </VField>
                      <VField :label="data.subTitle" v-else-if="data.type == 'datePicker'"
                        style="margin-bottom: 0.5rem;">
                        <VDatePicker v-model="input[data.model]" mode="date" trim-weeks :max-date="new Date()">
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </VField>
                      <VField :label="data.subTitle" v-else-if="data.type == 'timePicker'"
                        style="margin-bottom: 0.5rem;">
                        <VDatePicker v-model="input[data.model]" mode="time" is24hr>
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </VField>
                      <VField :label="data.subTitle" v-else-if="data.type == 'textArea'" style="margin-bottom: 0.5rem;">
                        <VTextarea rows="2" v-model="input[data.model]"></VTextarea>
                      </VField>
                      <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                            :label="data.subTitle" v-model="input[data.model]" />
                        </VControl>
                      </VField>
                      <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem;">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                            :label="data.subTitle" v-model="input[data.model]" />
                        </VControl>
                        <VControl raw subcontrol v-if="data.model2 == 'jenisAnestesiGAText_'">
                          <VCheckbox class="p-0" color="primary" square true-value="GA-OTT" label="GA-OTT"
                            v-model="input.GAOTT" />
                        </VControl>
                        <VControl raw subcontrol v-if="data.model2 == 'jenisAnestesiGAText_'">
                          <VCheckbox class="p-0" color="primary" square true-value="GA-NTT" label="GA-NTT"
                            v-model="input.GANTT" />
                        </VControl>
                        <VControl raw subcontrol v-if="data.model2 == 'jenisAnestesiGAText_'">
                          <VCheckbox class="p-0" color="primary" square true-value="GA-LMA" label="GA-LMA"
                            v-model="input.GALMA" />
                        </VControl>
                        <VControl raw subcontrol v-if="data.model2 == 'jenisAnestesiGAText_'">
                          <VCheckbox class="p-0" color="primary" square true-value="GA-TIVA" label="GA-TIVA"
                            v-model="input.GATIVA" />
                        </VControl>
                        <VControl raw subcontrol v-if="data.model2 == 'jenisAnestesiRAText_'">
                          <VCheckbox class="p-0" color="primary" square true-value="RA-BSA" label="RA-BSA"
                            v-model="input.RABSA" />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                </div>
                <div class="column is-12" v-for="(data) in TimOperasi">
                  <h1 style="font-weight : bold; margin-bottom: 0.5rem; text-align:center;">{{
                    data.title }}</h1>
                  <div class="columns is-multiline">
                    <div class="column is-4" v-for="(detail) in data.value">
                      <VField :label="detail.subTitle" v-if="detail.type == 'comboBoxPetugas'">
                        <VControl class="prime-auto">
                          <AutoComplete v-model="input[detail.model]" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                        </VControl>
                      </VField>
                      <VField :label="detail.subTitle" v-if="detail.type == 'comboBoxDokter'">
                        <VControl class="prime-auto">
                          <AutoComplete v-model="input[detail.model]" :suggestions="d_Dokter"
                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-12">
              <Fieldset :toggleable="true" legend="PENGKAJIAN (DATA FOKUS)">
                <div class="column is-12">
                  <div class="column is-12" v-for="(datas) in DataFokus">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in datas.value">
                        <VField :label="data.subTitle" v-if="data.type == 'text'">
                        </VField>
                        <VField :label="data.subTitle" v-if="data.type == 'textChoice'">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" v-model="input[data.model]" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>{{ data.nama }}</VButton>
                            </VControl>
                          </VField>
                        </VField>
                        <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                          style="margin-bottom: 0.5rem;">
                          <VControl raw subcontrol>
                            <input v-model="input[data.model]" class="input p-0" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem;">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                          </VControl>
                          <VControl raw subcontrol>
                            <VTextarea rows="2" v-model="input[data.model2]"></VTextarea>
                          </VControl>
                        </VField>
                      </div>
                    </div>

                  </div>

                </div>

                <div class="columns is-multiline">
                  <div class="column is-4" v-for="(datas) in DataObyektif">
                    <h1 style="font-weight: bold;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                      <div class="column is-6" v-for="(data) in datas.value">
                        <VField :label="data.subTitle" v-if="data.type == 'text'">
                        </VField>
                        <VField v-else-if="data.type == 'checkboxBebas'"
                          style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                          <VControl raw subcontrol style="display: flex; align-items: center;">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                            <VInput type="text" class="input" v-model="input[data.model2]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'checkboxGCS'" style="margin-bottom: 0.5rem;">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                          </VControl>
                          <VField addons>
                            <VControl class="field-addon-body">
                              <VButton static>{{ data.nama }}</VButton>
                            </VControl>
                            <VControl>
                              <VInput type="text" class="input" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                          <VField addons>
                            <VControl class="field-addon-body">
                              <VButton static>{{ data.nama2 }}</VButton>
                            </VControl>
                            <VControl>
                              <VInput type="text" class="input" v-model="input[data.model3]" />
                            </VControl>
                          </VField>
                          <VField addons>
                            <VControl class="field-addon-body">
                              <VButton static>{{ data.nama3 }}</VButton>
                            </VControl>
                            <VControl>
                              <VInput type="text" class="input" v-model="input[data.model4]" />
                            </VControl>
                          </VField>
                        </VField>
                      </div>
                    </div>

                  </div>
                  <div class="column is-4">
                    <VIconButton class="mr-2" raised circle icon="fas fa-notes-medical" @click="getLabo()"
                      :loading="isLoading" color="success" v-tooltip-prime.top="'Input Laboratorium'">
                    </VIconButton>
                    <VField label="Data penunjang laboratorium">
                      <VTextarea rows="2" v-model="input.dataPenunjangLaboratorium_"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VIconButton raised circle icon="fas fa-user-nurse" @click="getRadio()" :loading="isLoading"
                      color="success" v-tooltip-prime.top="'Input Radiologi'">
                    </VIconButton>
                    <VField label="Radiologi">
                      <VTextarea rows="2" v-model="input.radiologi_"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VIconButton class="mr-2" raised circle icon="fas fa-file-medical-alt" @click="setPenunjang()"
                      :loading="isLoading" color="success" v-tooltip-prime.top="'Set Penunjang Khusus'"></VIconButton>
                    <VField label="Data penunjang lainnya">
                      <VTextarea rows="2" v-model="input.dataPenunjangLainnya_"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField label="Keterangan lain">
                      <VTextarea rows="2" v-model="input.keteranganLain_"></VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="true" label="Select All"
                      v-model="input.selectAll1" />
                  </VControl>
                </div>

                <table class="table is-fullwidth is-bordered">
                  <thead>
                    <tr>
                      <th style="text-align: center; width: 50%;">Diagnosa Keperawatan</th>
                      <th style="text-align: center; width: 50%;">Rencana Keperawatan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, index) in diagnosaKeperawatan" :key="'diagnosa-' + index">
                      <td>
                        <div v-for="(data) in datas.value" :key="data.id">
                          <VField v-if="data.type == 'checkboxBebas'"
                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                            <VControl raw subcontrol style="display: flex; align-items: center;">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                              <VTextarea rows="4" v-model="input[data.model2]">
                              </VTextarea>
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                            <span>- &nbsp;&nbsp;</span>
                            <VField>
                              <VTextarea rows="2" v-model="input[data.model]"></VTextarea>
                            </VField>
                          </VField>
                          <VField v-else-if="data.type == 'text'" style="display: flex;">
                            <span>- {{ data.subTitle }}</span>
                          </VField>
                          <VField v-else-if="data.type == 'textarea'">
                            <VField :label="data.subTitle">
                              <VTextarea rows="2" v-model="input[data.model]"></VTextarea>
                            </VField>
                          </VField>
                        </div>
                      </td>
                      <td v-if="rencanaKeperawatan[index]">
                        <div v-for="(data) in rencanaKeperawatan[index].value" :key="data.id">
                          <VField v-if="data.type == 'checkboxBebas'"
                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                            <VControl raw subcontrol style="display: flex; align-items: center;">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                              <VInput type="text" class="input" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                            <span>-</span>
                            <VControl>
                              <VInput type="text" class="input" v-model="input[data.model]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'text'" style="display: flex;">
                            <span>- {{ data.subTitle }}</span>
                          </VField>
                          <VField v-else-if="data.type == 'textarea'" style="margin-bottom: 0.5rem;">
                            <VField :label="data.subTitle">
                              <VTextarea rows="2" v-model="input[data.model]"></VTextarea>
                            </VField>
                          </VField>
                        </div>
                      </td>
                      <td v-else></td>
                    </tr>
                  </tbody>
                </table>

                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="true" label="Select All"
                      v-model="input.selectAllTK1" />
                  </VControl>
                </div>

                <table class="table is-fullwidth is-bordered">
                  <thead>
                    <tr>
                      <th style="text-align: center; width: 50%;">Tindakan Keperawatan</th>
                      <th style="text-align: center; width: 50%;" colspan="2">Evaluasi Keperawatan
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, index) in tindakanKeperawatan" :key="'tindakan-' + index">
                      <!-- Tindakan Keperawatan -->
                      <td>
                        <div v-for="(data) in datas.value" :key="data.id">
                          <VField v-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem; display:flex;">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]"
                                style="margin-top: 12px; padding-right: 4px;" />
                            </VControl>
                            <VControl>
                              <VInput type="text" class="input" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkboxLancar'" style="margin-bottom: 0.5rem;">
                            <!-- Checkbox field with subTitle as label -->
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]"
                                style="margin-top: 12px; padding-right: 4px;" />
                            </VControl>

                            <!-- Text Input fields for Lokasi, Ukuran, and Nama Pemasang with appropriate labels -->
                            <div style="">
                              <VField label="Lokasi :" style="margin-top: 12px;">
                              </VField>
                              <VControl raw subcontrol>
                                <VInput type="text" class="input" v-model="input[data.model2]" />
                              </VControl>
                            </div>

                            <div style="">
                              <VField label="Ukuran :" style="margin-top: 12px;">
                              </VField>
                              <VControl raw subcontrol>
                                <VInput type="text" class="input" v-model="input[data.model3]" />
                              </VControl>
                            </div>

                            <VField label="Nama Pemasang :">
                              <VControl raw subcontrol>
                                <VInput type="text" class="input" v-model="input[data.model4]" />
                              </VControl>
                            </VField>
                          </VField>

                        </div>
                      </td>

                      <!-- Evaluasi Keperawatan Part 1 -->
                      <td v-if="evaluasiKeperawatan[index]" style="width: 22%;">
                        <div v-for="(data, dataIndex) in evaluasiKeperawatan[index].value"
                          :key="'eval-part1-' + data.id">
                          <div v-if="data.column == 1">
                            <VField v-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                              <VControl raw subcontrol>
                                <VInput type="text" class="input" v-model="input[data.model2]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkboxText2'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]"
                                  style="margin-top: 12px; padding-right: 4px;" />
                              </VControl>
                              <VControl raw subcontrol>
                                <VInput type="text" class="input" v-model="input[data.model2]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkboxText3'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]"
                                  style="margin-top: 12px; padding-right: 4px;" />
                              </VControl>
                              <VField label="Jenis :">
                                <VControl raw subcontrol>
                                  <VInput type="text" class="input" v-model="input[data.model2]" />
                                </VControl>
                              </VField>
                              <VField label="Jam :">
                                <VControl raw subcontrol>
                                  <VDatePicker v-model="input[data.model3]" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                      <VControl icon="feather:clock" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                      </VControl>
                                    </template>
                                  </VDatePicker>
                                </VControl>
                              </VField>
                            </VField>
                            <VField v-else-if="data.type == 'checkboxLancar'" style="margin-bottom: 0.5rem;">
                              <!-- Checkbox field with subTitle as label -->
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]"
                                  style="margin-top: 12px; padding-right: 4px;" />
                              </VControl>

                              <!-- Text Input fields for Lokasi, Ukuran, and Nama Pemasang with appropriate labels -->
                              <div style="">
                                <VField label="Lokasi :" style="margin-top: 12px;">
                                </VField>
                                <VControl raw subcontrol>
                                  <VInput type="text" class="input" v-model="input[data.model2]" />
                                </VControl>
                              </div>

                              <div style="">
                                <VField label="Ukuran :" style="margin-top: 12px;">
                                </VField>
                                <VControl raw subcontrol>
                                  <VInput type="text" class="input" v-model="input[data.model3]" />
                                </VControl>
                              </div>

                              <VField label="Nama Pemasang :">
                                <VControl raw subcontrol>
                                  <VInput type="text" class="input" v-model="input[data.model4]" />
                                </VControl>
                              </VField>
                            </VField>
                          </div>
                        </div>
                      </td>

                      <!-- Evaluasi Keperawatan Part 2 -->
                      <td v-if="evaluasiKeperawatan[index]">
                        <div v-for="(data, dataIndex) in evaluasiKeperawatan[index].value"
                          :key="'eval-part2-' + data.id">
                          <div v-if="data.column == 2">
                            <VField v-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkboxText'"
                              style="margin-bottom: 0.5rem; display:flex;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0 mt-3" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                              <VControl raw subcontrol>
                                <VInput type="text" style="width: 90%;" class="input ml-4"
                                  v-model="input[data.model2]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkboxLancar'" style="margin-bottom: 0.5rem;">
                              <!-- Checkbox field with subTitle as label -->
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]"
                                  style="margin-top: 12px; padding-right: 4px;" />
                              </VControl>

                              <!-- Text Input fields for Lokasi, Ukuran, and Nama Pemasang with appropriate labels -->
                              <div style="">
                                <VField label="Lokasi :" style="margin-top: 12px;">
                                </VField>
                                <VControl raw subcontrol>
                                  <VInput type="text" class="input" v-model="input[data.model2]" />
                                </VControl>
                              </div>

                              <div style="">
                                <VField label="Ukuran :" style="margin-top: 12px;">
                                </VField>
                                <VControl raw subcontrol>
                                  <VInput type="text" class="input" v-model="input[data.model3]" />
                                </VControl>
                              </div>

                              <VField label="Nama Pemasang :">
                                <VControl raw subcontrol>
                                  <VInput type="text" class="input" v-model="input[data.model4]" />
                                </VControl>
                              </VField>
                            </VField>
                          </div>
                        </div>
                      </td>

                      <!-- Empty Cells for Evaluasi Keperawatan -->
                    </tr>
                    <tr>
                      <td colspan="3">
                        <VField label="Keterangan Lain">
                          <VTextarea rows="2" v-model="input.keteranganKeperawatan">
                          </VTextarea>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="1">
                        <VField label="Nama Perawat">
                          <VControl class="prime-auto">
                            <AutoComplete v-model="input.perawatKeperawatan" :suggestions="d_Petugas"
                              @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                          </VControl>
                        </VField>
                      </td>
                      <td colspan="2">
                        <VField label="Tanda tangan">
                          <TandaTangan :elemenID="'signature_1'" :width="'150'" :height="'150'" class="dek" />
                        </VField>
                      </td>
                    </tr>
                  </tbody>
                </table>

              </Fieldset>
            </div>
          </Fieldset>
        </div>

        <div class="column is-12">
          <Fieldset :toggleable="true" legend="INTRA OPERATIF">
            <div class="column is-12">
              <Fieldset :toggleable="true" legend="PENGKAJIAN (DATA DASAR)">

                <div class="columns is-multiline">
                  <div class="column is-half">
                    <h1 style="font-weight: bold;">Data subyektif :</h1>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.dataSubyektif_" rows="2" placeholder="Data Subyektif"
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                    <h1 style="font-weight: bold;">Data obyektif :</h1>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.dataObyektif_" rows="2" placeholder="Data Obyektif"
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <label style="padding-right: 4px;">Suhu OK :</label>
                      </VControl>
                    </VField>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.dOSuhu_" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>°C</VButton>
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <label style="padding-right: 4px;">Kelembaban OK :</label>
                      </VControl>
                    </VField>
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.dOKelembabanOK_" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-half">
                    <div class="column is-12" v-for="(datas) in kondisiPasien">
                      <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                      <div class="columns is-multiline">
                        <div class="column is-4" v-for="(data) in datas.value">
                          <VField addons :label="data.subTitle" v-if="data.type == 'textBoxChoice'">
                            <VControl>
                              <VInput type="text" class="input" v-model="input[data.model]" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>{{ data.nama }}</VButton>
                            </VControl>
                          </VField>
                          <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                            style="margin-bottom: 0.5rem;">
                            <VControl raw subcontrol>
                              <input v-model="input[data.model]" class="input p-0" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="columns is-multiline">
                  <div class="column is-half">
                    <div class="column is-12" v-for="(datas) in setInstrumen">
                      <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                      <div class="columns is-multiline">
                        <div class="column is-6" v-for="(data) in datas.value">
                          <VField v-if="data.type == 'checkboxBebas'"
                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                            <VControl raw subcontrol style="display: flex; align-items: center;">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                              <VInput type="text" class="input" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem; display: flex;">
                            <VControl raw subcontrol style="width: 50%;">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                            <VControl raw subcontrol style="width: 50%;">
                              <VInput type="text" class="input" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-half">
                    <div class="columns is-half">
                      <div class="column is-6" v-for="(datas) in alatLain">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}
                        </h1>
                        <div class="columns is-multiline">
                          <div class="column is-12" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkboxBebas'"
                              style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                              <VControl raw subcontrol style="display: flex; align-items: center;">
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                                <VInput type="text" class="input" v-model="input[data.model2]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkboxText'"
                              style="margin-bottom: 0.5rem; display: flex;">
                              <VControl raw subcontrol style="width: 50%;">
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                              <VControl raw subcontrol style="width: 50%;">
                                <VInput type="text" class="input" v-model="input[data.model2]" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                      <div class="column is-6" v-for="(datas) in jenisAnestesi">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}
                        </h1>
                        <div class="columns is-multiline">
                          <div class="column is-12" v-for="(data) in datas.value">
                            <VField v-if="data.type == 'checkboxBebas'"
                              style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                              <VControl raw subcontrol style="display: flex; align-items: center;">
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                                <VInput type="text" class="input" v-model="input[data.model2]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol style="width: 100%; margin-bottom: 0.5rem;">
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>

                              <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <VControl raw subcontrol v-if="data.model == 'jAGA_'">
                                  <VCheckbox class="p-0" color="primary" square true-value="GA-OTT" label="GA-OTT"
                                    v-model="input.GAOTT1" />
                                </VControl>
                                <VControl raw subcontrol v-if="data.model == 'jAGA_'">
                                  <VCheckbox class="p-0" color="primary" square true-value="GA-NTT" label="GA-NTT"
                                    v-model="input.GANTT1" />
                                </VControl>
                                <VControl raw subcontrol v-if="data.model == 'jAGA_'">
                                  <VCheckbox class="p-0" color="primary" square true-value="GA-LMA" label="GA-LMA"
                                    v-model="input.GALMA1" />
                                </VControl>
                                <VControl raw subcontrol v-if="data.model == 'jAGA_'">
                                  <VCheckbox class="p-0" color="primary" square true-value="GA-TIVA" label="GA-TIVA"
                                    v-model="input.GATIVA1" />
                                </VControl>
                                <VControl raw subcontrol v-if="data.model == 'jARA_'">
                                  <VCheckbox class="p-0" color="primary" square true-value="RA-BSA" label="RA-BSA"
                                    v-model="input.RABSA" />
                                </VControl>
                              </div>
                            </VField>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(datas) in iOBreath">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                      <div class="column is-12" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'checkboxBebas'"
                          style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                          <VControl raw subcontrol style="display: flex; align-items: center;">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                            <VInput type="text" class="input" v-model="input[data.model2]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'DBcheckboxBebas'"
                          style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                          <VControl raw subcontrol style="width:50%">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                          </VControl>
                          <VControl raw subcontrol style="display: flex; align-items: center; width:50%">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle2"
                              :label="data.subTitle2" v-model="input[data.model2]" />
                            <VInput type="text" class="input" v-model="input[data.model3]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'checkboxBebas'"
                          style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                          <VControl raw subcontrol style="display: flex; align-items: center;">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                            <VInput type="text" class="input" v-model="input[data.model2]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'DBcheckbox'" style="margin-bottom: 0.5rem; display: flex">
                          <VControl raw subcontrol style="width:50%">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                          </VControl>
                          <VControl raw subcontrol style="width:50%">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle2"
                              :label="data.subTitle2" v-model="input[data.model2]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem; display: flex;">
                          <VControl raw subcontrol style="width: 50%;">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                          </VControl>
                          <VControl raw subcontrol style="width: 50%;">
                            <VInput type="text" class="input" v-model="input[data.model2]" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="columns is-multiline">
                  <div class="column is-half">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pemasanganEvaluasiKateterUrin_"
                          :true-value="'Pemasangan/evaluasi kateter urin'" label="Pemasangan/evaluasi kateter urin"
                          class="p-0" color="primary" square />
                      </VControl>
                      <VControl style="display: flex;">
                        <VField label="Ukuran :" style="padding-right: 6px; padding-top: 6px;">
                          <VControl>
                            <VInput type="text" class="input" v-model="input.ukuranPemasanganEvaluasiKateterUrin_" />
                          </VControl>
                        </VField>
                        <VField label="Nama pemasang :">
                          <VControl class="prime-auto">
                            <AutoComplete v-model="input.namaPemasanganEvaluasiKateterUrin" :suggestions="d_Petugas"
                              @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                          </VControl>
                        </VField>
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.ngt_" :true-value="'NGT'" label="NGT" class="p-0" color="primary"
                          square />
                      </VControl>
                      <VControl style="display: flex;">
                        <VField label="No :" style="padding-right: 6px; padding-top: 6px;">
                          <VControl>
                            <VInput type="text" class="input" v-model="input.noNGT_" />
                          </VControl>
                        </VField>
                        <VField label="Nama pemasang :">
                          <VControl class="prime-auto">
                            <AutoComplete v-model="input.namaNGT" :suggestions="d_Petugas"
                              @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                          </VControl>
                        </VField>
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.packingTenggorokan_"
                          :true-value="'Packing Tenggorokan. Jam dikeluarkan :'"
                          label="Packing Tenggorokan. Jam dikeluarkan :" class="p-0" color="primary" square />
                        <VDatePicker v-model="input.jamPackingTenggorokan_" mode="time" is24hr>
                          <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                          </template>
                        </VDatePicker>
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.penggunaanTorniquet_" :true-value="'Penggunaan torniquet'"
                          label="Penggunaan torniquet" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                    <div class="columns is-multiline">
                      <div class="column is-half">
                        <VField label="Lokasi :">
                          <VControl>
                            <VInput type="text" class="input" v-model="input.torniquetLokasi" />
                          </VControl>
                        </VField>
                        <VField label="Tekanan :">
                          <VControl>
                            <VInput type="text" class="input" v-model="input.torniquetTekanan" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-half">
                        <VField label="Jam mulai :">
                          <VDatePicker v-model="input.torniquetJamMulai" mode="time" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                              <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                              </VControl>
                            </template>
                          </VDatePicker>
                        </VField>
                        <VField label="Jam selesai :">
                          <VDatePicker v-model="input.torniquetJamSelesai" mode="time" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                              <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                              </VControl>
                            </template>
                          </VDatePicker>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-half">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Diatermy</h1>
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <VField style="display: flex;">
                          <VControl raw subcontrol style="width: 50%;">
                            <VCheckbox class="p-0" color="primary" square :true-value="Bipolar" label="Bipolar"
                              v-model="input.diatermyBipolar_" />
                          </VControl>
                          <VControl style="width: 50%;">
                            <VCheckbox class="p-0" color="primary" square :true-value="Monopolar" label="Monopolar"
                              v-model="input.diatermyMonopolar_" />
                          </VControl>
                        </VField>
                        <VField>
                          <span>Tempat pemasangan arde plat</span>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <ImgDraw elemenID="Gambar1" height="250" width="250"
                          imageSrc="/images/simrs/outline-only-body.jpg" />
                      </div>
                      <div class="column is-12">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">Alat bantu posisi
                          pasien</h1>
                        <VField style="display: flex;">
                          <VControl raw subcontrol style="width: 50%;">
                            <VCheckbox class="p-0" color="primary" square :true-value="LateralSupport"
                              label="Lateral Support" v-model="input.alatBantuPosisiPasienLateralSupport_" />
                          </VControl>
                          <VControl style="width: 50%;">
                            <VCheckbox class="p-0" color="primary" square :true-value="HeadRing" label="Head ring"
                              v-model="input.alatBantuPosisiPasienHeadRing_" />
                          </VControl>
                        </VField>
                        <VField style="display: flex;">
                          <VControl raw subcontrol style="width: 50%;">
                            <VCheckbox class="p-0" color="primary" square :true-value="Bantal" label="Bantal"
                              v-model="input.alatBantuPosisiPasienBantal_" />
                          </VControl>
                          <VControl style="width: 50%;">
                            <VCheckbox class="p-0" color="primary" square :true-value="Stirups" label="Stirups"
                              v-model="input.alatBantuPosisiPasienStirups_" />
                          </VControl>
                        </VField>
                        <VField style="display: flex;">
                          <VControl raw subcontrol style="width: 50%;">
                            <VCheckbox class="p-0" color="primary" square :true-value="ArmBoard" label="Arm board"
                              v-model="input.alatBantuPosisiPasienArmBoard_" />
                          </VControl>
                          <VControl style="width: 50%; display:flex;">
                            <VCheckbox class="p-0 mt-3" color="primary" square :true-value="Bebas" label=""
                              v-model="input.alatBantuPosisiPasienBebas_" />
                            <VControl>
                              <VInput type="text" class="input" v-model="input.alatBantuPosisiPasienBebasText_" />
                            </VControl>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="true" label="Select All"
                        v-model="input.selectAll2" />
                    </VControl>
                  </div>

                  <div class="column is-12">
                    <table class="table is-fullwidth is-bordered">
                      <thead>
                        <tr>
                          <th style="text-align: center; width: 50%;">Diagnosa Keperawatan
                          </th>
                          <th style="text-align: center; width: 50%;">Rencana Keperawatan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(datas, index) in diagnosaKeperawatan2" :key="'diagnosa-' + index">
                          <td>
                            <div v-for="(data) in datas.value" :key="data.id">
                              <VField v-if="data.type == 'checkboxBebas'"
                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                <VControl raw subcontrol style="display: flex; align-items: center;">
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                  <VTextarea rows="4" v-model="input[data.model2]">
                                  </VTextarea>
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                                <VControl raw subcontrol>
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                                <span>- &nbsp;&nbsp;</span>
                                <VField>
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'textBox2'" style="display: flex;">
                                <span>- &nbsp;&nbsp;</span>
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input[data.model]" />
                                  </VControl>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'text'" style="display: flex;">
                                <span>- {{ data.subTitle }}</span>
                              </VField>
                              <VField :label="data.subTitle" v-else-if="data.type == 'combo'">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="input[data.model]" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'textarea'">
                                <VField :label="data.subTitle">
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                            </div>
                          </td>
                          <td v-if="rencanaKeperawatan2[index]">
                            <div v-for="(data) in rencanaKeperawatan2[index].value" :key="data.id">
                              <VField v-if="data.type == 'checkboxBebas'"
                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                <VControl raw subcontrol style="display: flex; align-items: center;">
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                  <VInput type="text" class="input" v-model="input[data.model2]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                                <VControl raw subcontrol>
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'ttd'">
                                <VField>
                                  <span>{{ data.subTitle }}</span>
                                </VField>
                                <VField>
                                  <TandaTangan v-model="data.model" :elemenID="data.model" :width="'150'"
                                    :height="'150'" class="dek" />
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                                <span>-</span>
                                <VControl>
                                  <VInput type="text" class="input" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'text'" style="display: flex;">
                                <span>- {{ data.subTitle }}</span>
                              </VField>
                              <VField v-else-if="data.type == 'textarea'" style="margin-bottom: 0.5rem;">
                                <VField :label="data.subTitle">
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                            </div>
                          </td>
                          <td v-else></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="column is-12">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="true" label="Select All"
                        v-model="input.selectAllTK2" />
                    </VControl>
                  </div>

                  <div class="column is-12">
                    <table class="table is-fullwidth is-bordered">
                      <thead>
                        <tr>
                          <th style="text-align: center; width: 50%;">Tindakan Keperawatan
                          </th>
                          <th style="text-align: center; width: 50%;">Evaluasi Keperawatan
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(datas, index) in tindakanKeperawatan2" :key="'diagnosa-' + index">
                          <td>
                            <div v-for="(data) in datas.value" :key="data.id">
                              <VField v-if="data.type == 'checkboxBebas'"
                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                <VControl raw subcontrol style="display: flex; align-items: center;">
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                  <VTextarea rows="4" v-model="input[data.model2]">
                                  </VTextarea>
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                                <VControl raw subcontrol>
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                                <span>- &nbsp;&nbsp;</span>
                                <VField>
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'textBox2'" style="display: flex;">
                                <span>- &nbsp;&nbsp;</span>
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input[data.model]" />
                                  </VControl>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'text'" style="display: flex;">
                                <span>- {{ data.subTitle }}</span>
                              </VField>
                              <VField v-else-if="data.type == 'manual1'" style="display: flex;">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VField style="margin-bottom: 0.5rem;">
                                        <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square
                                            true-value="KolaborasiPencucianLuka" label="Kolaborasi Pencucian Luka"
                                            v-model="input.tK2KolaborasiPencucianLuka" />
                                        </VControl>
                                      </VField>
                                    </td>
                                    <td>
                                      <VField style="margin-bottom: 0.5rem;">
                                        <span>Jenis cairan yang digunakan</span>
                                      </VField>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VField style="margin-bottom: 0.5rem;">
                                      </VField>
                                    </td>
                                    <td>
                                      <VField>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.tK2JenisCairanText1" />
                                        </VControl>
                                      </VField>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VField style="margin-bottom: 0.5rem;">
                                      </VField>
                                    </td>
                                    <td>
                                      <VField>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.tK2JenisCairanText2" />
                                        </VControl>
                                      </VField>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual3'">
                                <div class="columns is-multiline">
                                  <div class="column is-12">
                                    <VField>
                                      <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square
                                            true-value="MelakukanPerawatanLuka" label="Melakukan perawatan luka"
                                            v-model="input.tK2MelakukanPenutupanLuka_" />
                                        </VControl>
                                    </VField>
                                  </div>
                                  <div class="column is-12">
                                    <table class="table is-fullwidth is-bordered">
                                      <thead>
                                        <tr>
                                          <th style="width: 30%;">
                                            <span style="text-align: center;">Lokasi
                                              Luka</span>
                                          </th>
                                          <th style="width: 40%;" colspan="2">
                                            <span style="text-align: center;">Tipe
                                              dressing</span>
                                          </th>
                                          <!-- <th style="width: 30%;">&nbsp;</th> -->
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td style="width: 30%;">
                                            <VControl raw subcontrol style="display: flex;">
                                              <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                                label="" v-model="input.eK2LokasiLukaBebas1" />
                                              <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText1" />
                                            </VControl>
                                          </td>
                                          <td style="width: 35%;">
                                            <VControl raw subcontrol>
                                              <VCheckbox class="p-0" color="primary" square true-value="TulleGrass"
                                                label="Tulle grass" v-model="input.eK2TulleGrass1" />
                                            </VControl>
                                          </td>
                                          <td style="width: 35%;">
                                            <VControl raw subcontrol style="display: flex;">
                                              <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                                label="" v-model="input.eK2LokasiLukaBebas2" />
                                              <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText2" />
                                            </VControl>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td style="width: 30%;">
                                            <VControl raw subcontrol style="display: flex;">
                                              <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                                label="" v-model="input.eK2LokasiLukaBebas3" />
                                              <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText3" />
                                            </VControl>
                                          </td>
                                          <td style="width: 35%;">
                                            <VControl raw subcontrol>
                                              <VCheckbox class="p-0" color="primary" square true-value="TulleGrass"
                                                label="Tulle grass" v-model="input.eK2TulleGrass2" />
                                            </VControl>
                                          </td>
                                          <td style="width: 35%;">
                                            <VControl raw subcontrol style="display: flex;">
                                              <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                                label="" v-model="input.eK2LokasiLukaBebas4" />
                                              <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText4" />
                                            </VControl>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td style="width: 30%;">
                                            <VControl raw subcontrol style="display: flex;">
                                              <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                                label="" v-model="input.eK2LokasiLukaBebas5" />
                                              <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText5" />
                                            </VControl>
                                          </td>
                                          <td style="width: 35%;">
                                            <VControl raw subcontrol style="display: flex;">
                                              <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                                label="" v-model="input.eK2LokasiLukaBebas6" />
                                              <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText6" />
                                            </VControl>
                                          </td>
                                          <td style="width: 35%;">
                                            <VControl raw subcontrol style="display: flex;">
                                              <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                                label="" v-model="input.eK2LokasiLukaBebas7" />
                                              <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText7" />
                                            </VControl>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <!-- <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                          true-value="MelakukanPerawatanLuka" label="Melakukan perawatan luka"
                                          v-model="input.test1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Melakukan perawatan drain"
                                          label="Melakukan perawatan drain" v-model="input.test2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table> -->
                              </VField>
                              <VField v-else-if="data.type == 'manual2'">
                                <div class="columns is-multiline">
                                  <div class="column is-12">
                                    <VField>
                                      <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square
                                            true-value="MelakukanPerawatanDrain" label="Melakukan perawatan drain"
                                            v-model="input.tK2MelakukanPerawatanDrain_" />
                                        </VControl>
                                    </VField>
                                  </div>
                                  <div class="column is-12">
                                    <VField label="Ukuran drain no :">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2UkuranDrainNoText" />
                                      </VControl>
                                    </VField>
                                    <VField label="Lokasi drain :">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2LokasiDrainText" />
                                      </VControl>
                                    </VField>
                                  </div>
                                  <div class="column is-6">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Vakum" label="Vakum"
                                          v-model="input.eK2Vakum" />
                                      </VControl>
                                    </VField>
                                  </div>
                                  <div class="column is-6">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Non Vakum"
                                          label="Non Vakum" v-model="input.eK2Vakum" />
                                      </VControl>
                                    </VField>
                                  </div>
                                  <div class="column is-6">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Utuh" label="Utuh"
                                          v-model="input.eK2Utuh" />
                                      </VControl>
                                    </VField>
                                  </div>
                                  <div class="column is-6">
                                    <VField>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                          label="Tidak, jelaskan" v-model="input.eK2TidakJelaskan" />
                                        <VInput type="text" class="input" v-model="input.eK2TidakJelaskanText" />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </VField>
                              <VField :label="data.subTitle" v-else-if="data.type == 'combo'">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="input[data.model]" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'textarea'">
                                <VField :label="data.subTitle">
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'text2'" style="display: flex;">
                                <span style="text-align: center;">{{ data.subTitle
                                }}</span>
                              </VField>
                            </div>
                          </td>
                          <td v-if="evaluasiKeperawatan2[index]">
                            <div v-for="(data) in evaluasiKeperawatan2[index].value" :key="data.id">
                              <VField v-if="data.type == 'checkboxBebas'"
                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                <VControl raw subcontrol style="display: flex; align-items: center;">
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                  <VInput type="text" class="input" v-model="input[data.model2]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                                <VControl raw subcontrol>
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'ttd'">
                                <VField>
                                  <span>{{ data.subTitle }}</span>
                                </VField>
                                <VField>
                                  <TandaTangan v-model="data.model" :elemenID="data.model" :width="'150'"
                                    :height="'150'" class="dek" />
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                                <span>-</span>
                                <VControl>
                                  <VInput type="text" class="input" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'text'" style="display: flex;">
                                <span>- {{ data.subTitle }}</span>
                              </VField>
                              <VField v-else-if="data.type == 'text2'" style="display: flex;">
                                <span style="text-align: center;">{{ data.subTitle
                                }}</span>
                              </VField>
                              <VField v-else-if="data.type == 'manual1'">
                                <VField>
                                  <span>Preparation solution yang digunakan :</span>
                                </VField>
                                <VField style="display: flex;">
                                  <VControl raw subcontrol style="width: 50%;">
                                    <VCheckbox class="p-0" color="primary" square true-value="PovidoneIodine"
                                      label="Povidone iodine" v-model="input.eK2PovidoneIodine" />
                                  </VControl>
                                  <VControl raw subcontrol style="width: 50%;">
                                    <VCheckbox class="p-0" color="primary" square true-value="Alkohol" label="Alkohol"
                                      v-model="input.eK2Alkohol" />
                                  </VControl>
                                </VField>
                                <VField style="display: flex;">
                                  <VControl raw subcontrol style="width: 50%;">
                                    <VCheckbox class="p-0" color="primary" square true-value="ChlorhexidineAlcohol"
                                      label="Chlorhexidine alcohol" v-model="input.eK2ChlorhexidineAlcohol" />
                                  </VControl>
                                  <VControl raw subcontrol style="width: 50%;">
                                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                                      v-model="input.eK2Lainnya" />
                                  </VControl>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'manual2'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="DiatermiBerfungsiBaik"
                                          label="Diatermi Berfungsi Baik" v-model="input.eK2DiatermiBerfungsiBaik" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TidakDiperlukan"
                                          label="Tidak Diperlukan" v-model="input.eK2TidakDiperlukan1" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual3'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                          true-value="TerpasangDanTermonitor" label="Terpasang Dan Termonitor"
                                          v-model="input.eK2TerpasangDanTermonitor" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TidakDiperlukan"
                                          label="Tidak Diperlukan" v-model="input.eK2TidakDiperlukan2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual4'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <span style="text-align: center;">Nama
                                        obat</span>
                                    </td>
                                    <td>
                                      <span style="text-align: center;">Lokasi</span>
                                    </td>
                                    <td>
                                      <span style="text-align: center;">Total
                                        dosis</span>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaObat1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2Lokasi1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TotalDosis1" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaObat2" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2Lokasi2" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TotalDosis2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual5'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td colspan="2">
                                      <span style="text-align: center;">Kondisi
                                        hangat</span>
                                    </td>
                                    <td>
                                      <span style="text-align: center;">Total
                                        volume</span>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="Ya" label="Ya"
                                          v-model="input.tK2KondisiHangatYa1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="Tidak" label="Tidak"
                                          v-model="input.tK2KondisiHangatTidak1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VField addons>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.tK2KondisiHangatText1" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>ml</VButton>
                                        </VControl>
                                      </VField>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="Ya" label="Ya"
                                          v-model="input.tK2KondisiHangatYa2" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="Tidak" label="Tidak"
                                          v-model="input.tK2KondisiHangatTidak2" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VField addons>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.tK2KondisiHangatText2" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>ml</VButton>
                                        </VControl>
                                      </VField>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual6'">
                                <!-- <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td style="width: 30%;">
                                      <span style="text-align: center;">Lokasi
                                        Luka</span>
                                    </td>
                                    <td style="width: 70%;">
                                      <span style="text-align: center;">Tipe
                                        dressing</span>
                                    </td>
                                  </tr>
                                </table> -->
                              </VField>
                              <VField v-else-if="data.type == 'manual7'">
                                <!-- <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td style="width: 30%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas1" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText1" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TulleGrass"
                                          label="Tulle grass" v-model="input.eK2TulleGrass1" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas2" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 30%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas3" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText3" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TulleGrass"
                                          label="Tulle grass" v-model="input.eK2TulleGrass2" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas4" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText4" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 30%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas5" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText5" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas6" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText6" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas7" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText7" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table> -->
                              </VField>
                              <VField v-else-if="data.type == 'manual8'">
                                <!-- <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Utuh" label="Utuh"
                                      v-model="input.eK2Utuh" />
                                  </VControl>
                                </VField>
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                      label="Tidak, jelaskan" v-model="input.eK2TidakJelaskan" />
                                    <VInput type="text" class="input" v-model="input.eK2TidakJelaskanText" />
                                  </VControl>
                                </VField> -->
                              </VField>
                              <VField v-else-if="data.type == 'manual9'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <th style="width: 10%;">NO</th>
                                    <th style="width: 40%;">NAMA BAHAN</th>
                                    <th style="width: 50%;">TIPE FIKSASI</th>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahan1" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaBahan1" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TipeFiksasi1" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahan2" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaBahan2" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TipeFiksasi2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahan3" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaBahan3" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TipeFiksasi3" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual100'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <th style="width: 10%;">NO</th>
                                    <th style="width: 40%;">NAMA BAHAN</th>
                                    <th style="width: 50%;">LOKASI PENGAMBILAN</th>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahanKultur" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNamaBahanKultur" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKLokasiBahanKultur" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahanKultur2" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNamaBahanKultur2" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKLokasiBahanKultur2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahanKultur3" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNamaBahanKultur3" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKLokasiBahanKultur3" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual10'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td style="width: 50%;">
                                      <VField label="KU :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2KUText" />
                                        </VControl>
                                      </VField>
                                      <VField label="TD :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2TDText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Nadi :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2NadiText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Jumlah cairan infus :">
                                        <VField addons>
                                          <VControl>
                                            <VInput type="text" class="input"
                                              v-model="input.eK2JumlahCairanInfusText" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>cc</VButton>
                                          </VControl>
                                        </VField>
                                      </VField>
                                      <VField label="Jumlah transfusi :">
                                        <VField addons>
                                          <VControl>
                                            <VInput type="text" class="input" v-model="input.eK2JumlahTransfusiText" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>cc</VButton>
                                          </VControl>
                                        </VField>
                                      </VField>
                                    </td>
                                    <td style="width: 50%;">
                                      <VField label="Respirasi :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2RespirasiText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Suhu :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2SuhuText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Saturasi :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2SaturasiText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Jumlah perdarahan :">
                                        <VField addons>
                                          <VControl>
                                            <VInput type="text" class="input" v-model="input.eK2JumlahPerdarahanText" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>cc</VButton>
                                          </VControl>
                                        </VField>
                                      </VField>
                                      <VField label="Jumlah urine :">
                                        <VField addons>
                                          <VControl>
                                            <VInput type="text" class="input" v-model="input.eK2JumlahUrineText" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>cc</VButton>
                                          </VControl>
                                        </VField>
                                      </VField>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'textarea'" style="margin-bottom: 0.5rem;">
                                <VField :label="data.subTitle">
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                            </div>
                          </td>
                          <td v-else></td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <span>KETERANGAN TAMBAHAN</span>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <VField>
                              <VTextarea rows="2" v-model="input.keteranganTambahanText3">
                              </VTextarea>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <table class="table is-fullwidth is-bordered">
                              <tr>
                                <td>
                                  <span style="text-align: center;">PERAWAT
                                    ANESTESI</span>
                                </td>
                                <td>
                                  <span style="text-align: center;">PERAWAT
                                    INSTRUMEN</span>
                                </td>
                                <td>
                                  <span style="text-align: center;">PERAWAT
                                    SIRKULER</span>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <VField label="Nama :">
                                    <VControl>
                                      <VInput type="text" class="input" v-model="input.namaPerawatAnestesiText" />
                                    </VControl>
                                  </VField>
                                </td>
                                <td>
                                  <VField label="Nama :">
                                    <VControl>
                                      <VInput type="text" class="input" v-model="input.namaPerawatInstrumenText" />
                                    </VControl>
                                  </VField>
                                </td>
                                <td>
                                  <VField label="Nama :">
                                    <VControl>
                                      <VInput type="text" class="input" v-model="input.namaPerawatSirkulerText" />
                                    </VControl>
                                  </VField>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <VField label="Tanda Tangan :">
                                    <TandaTangan v-model="input.TTDperawatAnestesi" :elemenID="'TTDperawatAnestesi'"
                                      :width="'150'" :height="'150'" class="dek" />
                                  </VField>
                                </td>
                                <td>
                                  <VField label="Tanda Tangan :">
                                    <TandaTangan v-model="input.TTDperawatInstrumen" :elemenID="'TTDperawatInstrumen'"
                                      :width="'150'" :height="'150'" class="dek" />
                                  </VField>
                                </td>
                                <td>
                                  <VField label="Tanda Tangan :">
                                    <TandaTangan v-model="input.TTDperawatSirkuler" :elemenID="'TTDperawatSirkuler'"
                                      :width="'150'" :height="'150'" class="dek" />
                                  </VField>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <VField label="Jam :">
                                    <VDatePicker v-model="input.jamPerawatAnestesi" mode="time" is24hr>
                                      <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:clock" fullwidth>
                                          <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                      </template>
                                    </VDatePicker>
                                  </VField>
                                </td>
                                <td>
                                  <VField label="Jam :">
                                    <VDatePicker v-model="input.jamPerawatInstrumen" mode="time" is24hr>
                                      <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:clock" fullwidth>
                                          <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                      </template>
                                    </VDatePicker>
                                  </VField>
                                </td>
                                <td>
                                  <VField label="Jam :">
                                    <VDatePicker v-model="input.jamPerawatSirkuler" mode="time" is24hr>
                                      <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:clock" fullwidth>
                                          <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                      </template>
                                    </VDatePicker>
                                  </VField>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </Fieldset>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12 is-multiline">
          <h1 style="font-weight: bold;">PENGHITUNGAN INTRA OPERATIF</h1>
          <div class="columns">
            <!-- Table Section -->
            <div class="column is-4 pl-0" style="overflow: auto;">
              <table class="table is-bordered">
                <thead>
                  <tr>
                    <td style="text-align: center; min-width: 100px;">#</td>
                    <td style="text-align: center; min-width: 140px;">Jenis/Nama Item Yang Dihitung
                    </td>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in input.details" :key="index">
                    <td style="vertical-align: inherit">
                      <div class="column">
                        <VButtons style="justify-content:space-around">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeItem(index)" color="danger">
                          </VIconButton>
                        </VButtons>
                      </div>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOJenisNamaItem"></VTextarea>
                      </VField>
                      <VField class="is-autocomplete-select" v-slot="{ id }"
                        v-if="item.iOJenisNamaItem == 'MESS'">
                        <VControl>
                          <Multiselect v-model="item.pilihanKlem" :attrs="{ value }" placeholder="Pilih" label="label"
                            :options="d_klem" :searchable="true" track-by="label" mode="single" autocomplete="off">
                          </Multiselect>
                        </VControl>
                      </VField>
                      <VField v-if="item.pilihanKlem == 5">
                        <VTextarea rows="2" v-model="item.textPilihanKlem" placeholder="Lainnya"></VTextarea>
                      </VField>
                    </td>
                  </tr>
                </tbody>
                <tr>
                  <td colspan="14">
                    Instrumen Set / Single
                  </td>
                </tr>
                <tr v-for="(item, index) in input.details2" :key="index">
                  <td style="vertical-align: inherit">
                    <div class="column">
                      <VButtons style="justify-content:space-around">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem2()" color="info"
                          v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem2(index)" color="danger">
                        </VIconButton>
                      </VButtons>
                    </div>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOJenisNamaItem2"></VTextarea>
                    </VField>
                  </td>
                </tr>
              </table>
            </div>
            <div class="column is-8 pl-0" style="overflow: auto;">
              <table class="table is-bordered">
                <thead>
                  <tr>
                    <td style="text-align: center; min-width: 150px;">Penghitungan Awal</td>
                    <td colspan="4" style="text-align: center; min-width: 400px;">Penambahan Item
                    </td>
                    <td style="text-align: center; min-width: 150px;">Total Tambahan</td>
                    <td style="text-align: center; min-width: 180px;">Penghitungan Pertama</td>
                    <td colspan="4" style="text-align: center; min-width: 400px;">Penambahan Kedua
                    </td>
                    <td style="text-align: center; min-width: 150px;">Total Tambahan</td>
                    <td style="text-align: center; min-width: 150px;">Penghitungan Akhir</td>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in input.details" :key="index">
                    <td>
                      <VField v-if="item.iOJenisNamaItem == 'Arteri klem' && item.pilihanKlem != 5">
                        <VTextarea rows="4" v-model="item.iOPenghitunganAwal"></VTextarea>
                      </VField>
                      <VField v-else-if="item.iOJenisNamaItem == 'Arteri klem' && item.pilihanKlem == 5">
                        <VTextarea rows="8" v-model="item.iOPenghitunganAwal"></VTextarea>
                      </VField>
                      <VField v-else>
                        <VTextarea rows="2" v-model="item.iOPenghitunganAwal"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenambahanItem1"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenambahanItem2"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenambahanItem3"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenambahanItem4"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOTotalTambahan"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenghitunganPertama"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenambahanKedua1"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenambahanKedua2"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenambahanKedua3"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenambahanKedua4"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOTotalTambahandua"></VTextarea>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VTextarea rows="2" v-model="item.iOPenghitunganAkhir"></VTextarea>
                      </VField>
                    </td>
                  </tr>
                </tbody>
                <tr>
                  <td colspan="14">
                    Instrumen Set / Single
                  </td>
                </tr>
                <tr v-for="(item, index) in input.details2" :key="index">
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenghitunganAwal2"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenambahanItem5"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenambahanItem6"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenambahanItem7"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenambahanItem8"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOTotalTambahan2"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenghitunganPertam2"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenambahanKedua5"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenambahanKedua6"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenambahanKedua7"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenambahanKedua8"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOTotalTambahandua2"></VTextarea>
                    </VField>
                  </td>
                  <td>
                    <VField>
                      <VTextarea rows="2" v-model="item.iOPenghitunganAkhir2"></VTextarea>
                    </VField>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div class="column is-12">
            <table class="table is-bordered is-fullwidth">
              <tr>
                <td></td>
                <td>
                  Penghitungan Awal
                </td>
                <td>
                  Penghitungan Pertama
                </td>
                <td>
                  Penghitungan Akhir
                </td>
                <td>
                  Keterangan
                </td>
              </tr>
              <tr>
                <td>
                  Nama dan tanda tangan Perawat instrumen
                </td>
                <td>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pIOPerawatInstrumen1" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                  <VField>
                    <TandaTangan v-model="input.TTDpIOPerawatInstrumen1" :elemenID="'TTDpIOPerawatInstrumen1'"
                      :width="'150'" :height="'150'" class="dek" />
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pIOPerawatInstrumen2" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                  <VField>
                    <TandaTangan v-model="input.TTDpIOPerawatInstrumen1" :elemenID="'TTDpIOPerawatInstrumen2'"
                      :width="'150'" :height="'150'" class="dek" />
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pIOPerawatInstrumen3" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                  <VField>
                    <TandaTangan v-model="input.TTDpIOPerawatInstrumen3" :elemenID="'TTDpIOPerawatInstrumen3'"
                      :width="'150'" :height="'150'" class="dek" />
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pIOPerawatInstrumen4" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                  <VField>
                    <TandaTangan v-model="input.TTDpIOPerawatInstrumen4" :elemenID="'TTDpIOPerawatInstrumen4'"
                      :width="'150'" :height="'150'" class="dek" />
                  </VField>
                </td>
              </tr>
              <tr>
                <td>
                  Nama dan tanda tangan Perawat sirkuler
                </td>
                <td>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pIOPerawatSirkuler1" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                  <VField>
                    <TandaTangan v-model="input.TTDpIOPerawatSirkuler1" :elemenID="'TTDpIOPerawatSirkuler1'"
                      :width="'150'" :height="'150'" class="dek" />
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pIOPerawatSirkuler2" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                  <VField>
                    <TandaTangan v-model="input.TTDpIOPerawatSirkuler2" :elemenID="'TTDpIOPerawatSirkuler2'"
                      :width="'150'" :height="'150'" class="dek" />
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pIOPerawatSirkuler3" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                  <VField>
                    <TandaTangan v-model="input.TTDpIOPerawatSirkuler3" :elemenID="'TTDpIOPerawatSirkuler3'"
                      :width="'150'" :height="'150'" class="dek" />
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.pIOPerawatSirkuler4" :suggestions="d_Petugas"
                        @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </VField>
                  <VField>
                    <TandaTangan v-model="input.TTDpIOPerawatSirkuler4" :elemenID="'TTDpIOPerawatSirkuler4'"
                      :width="'150'" :height="'150'" class="dek" />
                  </VField>
                </td>
              </tr>
              <tr>
                <td>Benar penghitungan</td>
                <td colspan="4">
                  <div class="flex-container" style="display: flex; flex-wrap: wrap;">
                    <div class="flex-item" style="flex: 1 1 33%; padding: 10px;">
                      <VField style="display: flex; justify-content: space-evenly;">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.pIOBenarPenghitunganYa" />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak, Jika tidak,"
                            v-model="input.pIOBenarPenghitunganTidak" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="flex-item" style="flex: 1 1 33%; padding: 10px;">
                      <VField>
                        <span>sepengetahuan dokter</span>
                      </VField>
                      <VField>
                        <span>Dilakukan X-ray</span>
                      </VField>
                    </div>
                    <div class="flex-item" style="flex: 1 1 33%; padding: 10px;">
                      <VField style="display: flex; justify-content: space-evenly;">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.pIOBenarPenghitunganYa2" />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.pIOBenarPenghitunganTidak2" />
                        </VControl>
                      </VField>
                      <VField style="display: flex; justify-content: space-evenly;">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.pIOBenarPenghitunganYa3" />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.pIOBenarPenghitunganTidak3" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <div class="column is-12 mt-3-min">
          <Fieldset :toggleable="true" legend="POST OPERATIF">
            <div class="columns is-multiline p-3">
              <div class="column is-12">
                <Fieldset :toggleable="true" legend="PENGKAJIAN (DATA FOKUS)">
                  <h1 style="font-weight: bold;">DATA SUBYEKTIF :</h1>
                  <div class="column is-12" v-for="(datas) in pasienMengeluh">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                      <div class="column is-2" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'checkboxBebas'"
                          style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                          <VControl raw subcontrol style="display: flex; align-items: center;">
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                            <VInput type="text" class="input" v-model="input[data.model2]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'checkbox'">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                              :label="data.subTitle" v-model="input[data.model]" />
                          </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'checkbox2'">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol style="display: flex; align-items: center;">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle2"
                                :label="data.subTitle2" v-model="input[data.model2]" />
                              <VInput type="text" class="input" v-model="input[data.model3]" />
                            </VControl>
                          </VField>
                        </VField>
                        <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                          style="margin-bottom: 0.5rem;">
                          <VControl raw subcontrol>
                            <input v-model="input[data.model]" class="input p-0" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" v-for="(datas) in pOVitalSign">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                      <div class="column is-4" v-for="(data) in datas.value">
                        <VField addons :label="data.subTitle" v-if="data.type == 'textBoxChoice'">
                          <VControl>
                            <VInput type="text" class="input" v-model="input[data.model]" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>{{ data.nama }}</VButton>
                          </VControl>
                        </VField>
                        <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                          style="margin-bottom: 0.5rem;">
                          <VControl raw subcontrol>
                            <input v-model="input[data.model]" class="input p-0" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>

                  <div class="columns is-multiline">
                    <div class="column is-4" v-for="(datas) in pOBreath">
                      <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                      <div class="columns is-multiline">
                        <div class="column is-6" v-for="(data) in datas.value">
                          <VField v-if="data.type == 'checkboxBebas'"
                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                            <VControl raw subcontrol style="display: flex; align-items: center;">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                              <VInput type="text" class="input" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'DBcheckboxBebas'"
                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                            <VControl raw subcontrol style="width:50%">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                            <VControl raw subcontrol style="display: flex; align-items: center; width:50%">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle2"
                                :label="data.subTitle2" v-model="input[data.model2]" />
                              <VInput type="text" class="input" v-model="input[data.model3]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkboxBebas'"
                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                            <VControl raw subcontrol style="display: flex; align-items: center;">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                              <VInput type="text" class="input" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'DBcheckbox'" style="margin-bottom: 0.5rem; display: flex">
                            <VControl raw subcontrol style="width:50%">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                            <VControl raw subcontrol style="width:50%">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle2"
                                :label="data.subTitle2" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                          <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem; display: flex;">
                            <VControl raw subcontrol style="width: 50%;">
                              <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                :label="data.subTitle" v-model="input[data.model]" />
                            </VControl>
                            <VControl raw subcontrol style="width: 50%;">
                              <VInput type="text" class="input" v-model="input[data.model2]" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4" v-for="(datas) in pOKeterangan">
                      <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                      <div class="columns is-multiline">
                        <div class="column is-12" v-for="(data) in datas.value">
                          <VField v-if="data.type == 'textarea'" style="margin-bottom: 0.5rem;">
                            <VTextarea class="textarea" rows="2" :placeholder="data.subTitle"
                              v-model="input[data.model]">
                            </VTextarea>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-12">
                      <h1 style="font-weight: bold; margin-bottom: 1rem;">KETERANGAN LAIN</h1>
                      <VField>
                        <VTextarea rows="2" placeholder="Keterangan Lain" class="textarea"
                          v-model="input.pOKeteranganLainText"></VTextarea>
                      </VField>
                    </div>
                  </div>

                    <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="true" label="Select All"
                            v-model="input.selectAll3" />
                        </VControl>
                    </div>

                  <table class="table is-fullwidth is-bordered">
                    <thead>
                      <tr>
                        <th style="text-align: center; width: 50%;">Diagnosa Keperawatan</th>
                        <th style="text-align: center; width: 50%;">Rencana Keperawatan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(datas, index) in diagnosaKeperawatan3" :key="'diagnosa-' + index">
                        <td>
                          <div v-for="(data) in datas.value" :key="data.id">
                            <VField v-if="data.type == 'checkboxBebas'"
                              style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                              <VControl raw subcontrol style="display: flex; align-items: center;">
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                                <VTextarea rows="4" v-model="input[data.model2]">
                                </VTextarea>
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                              <span>- &nbsp;&nbsp;</span>
                              <VField>
                                <VTextarea rows="2" v-model="input[data.model]">
                                </VTextarea>
                              </VField>
                            </VField>
                            <VField v-else-if="data.type == 'text'" style="display: flex;">
                              <span>- {{ data.subTitle }}</span>
                            </VField>
                            <VField v-else-if="data.type == 'textarea'">
                              <VField :label="data.subTitle">
                                <VTextarea class="textarea" rows="2" v-model="input[data.model]">
                                </VTextarea>
                              </VField>
                            </VField>
                          </div>
                        </td>
                        <td v-if="rencanaKeperawatan3[index]">
                          <div v-for="(data) in rencanaKeperawatan3[index].value" :key="data.id">
                            <VField v-if="data.type == 'checkboxBebas'"
                              style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                              <VControl raw subcontrol style="display: flex; align-items: center;">
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                                <VInput type="text" class="input" v-model="input[data.model2]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                  :label="data.subTitle" v-model="input[data.model]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                              <span>-</span>
                              <VControl>
                                <VInput type="text" class="input" v-model="input[data.model]" />
                              </VControl>
                            </VField>
                            <VField v-else-if="data.type == 'text'" style="display: flex;">
                              <span>- {{ data.subTitle }}</span>
                            </VField>
                            <VField v-else-if="data.type == 'textarea'" style="margin-bottom: 0.5rem;">
                              <VField :label="data.subTitle">
                                <VTextarea class="textarea" rows="2" v-model="input[data.model]">
                                </VTextarea>
                              </VField>
                            </VField>
                          </div>
                        </td>
                        <td v-else></td>
                      </tr>
                    </tbody>
                  </table>

                  <div class="column is-12">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="true" label="Select All"
                            v-model="input.selectAll4" />
                        </VControl>
                    </div>

                  <div class="column is-12">
                    <table class="table is-fullwidth is-bordered">
                      <thead>
                        <tr>
                          <th style="text-align: center; width: 50%;">Tindakan Keperawatan
                          </th>
                          <th style="text-align: center; width: 50%;">Evaluasi Keperawatan
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(datas, index) in tindakanKeperawatan3" :key="'diagnosa-' + index">
                          <td>
                            <div v-for="(data) in datas.value" :key="data.id">
                              <VField v-if="data.type == 'checkboxBebas'"
                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                <VControl raw subcontrol style="display: flex; align-items: center;">
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                  <VTextarea rows="4" v-model="input[data.model2]">
                                  </VTextarea>
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                                <VControl raw subcontrol>
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                                <span>- &nbsp;&nbsp;</span>
                                <VField>
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'textBox2'" style="display: flex;">
                                <span>- &nbsp;&nbsp;</span>
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input[data.model]" />
                                  </VControl>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'text'" style="display: flex;">
                                <span>- {{ data.subTitle }}</span>
                              </VField>
                              <VField v-else-if="data.type == 'manual1'" style="display: flex;">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VField style="margin-bottom: 0.5rem;">
                                        <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square
                                            true-value="KolaborasiPencucianLuka" label="Kolaborasi Pencucian Luka"
                                            v-model="input.tK2KolaborasiPencucianLuka" />
                                        </VControl>
                                      </VField>
                                    </td>
                                    <td>
                                      <VField style="margin-bottom: 0.5rem;">
                                        <span>Jenis cairan yang digunakan</span>
                                      </VField>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VField style="margin-bottom: 0.5rem;">
                                      </VField>
                                    </td>
                                    <td>
                                      <VField>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.tK2JenisCairanText1" />
                                        </VControl>
                                      </VField>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VField style="margin-bottom: 0.5rem;">
                                      </VField>
                                    </td>
                                    <td>
                                      <VField>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.tK2JenisCairanText2" />
                                        </VControl>
                                      </VField>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual2'">
                                <VField label="Ukuran drain no :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eK2UkuranDrainNoText" />
                                  </VControl>
                                </VField>
                                <VField label="Lokasi drain :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eK2LokasiDrainText" />
                                  </VControl>
                                </VField>
                              </VField>
                              <VField :label="data.subTitle" v-else-if="data.type == 'combo'">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="input[data.model]" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'textarea'">
                                <VField :label="data.subTitle">
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'text2'" style="display: flex;">
                                <span style="text-align: center;">{{ data.subTitle
                                }}</span>
                              </VField>
                            </div>
                          </td>
                          <td v-if="evaluasiKeperawatan3[index]">
                            <div v-for="(data) in evaluasiKeperawatan3[index].value" :key="data.id">
                              <VField v-if="data.type == 'checkboxBebas'" style="margin-bottom: 0.5rem; display:flex;">
                                <VControl raw subcontrol style="display: flex; ">
                                  <VCheckbox class="p-0 mt-3" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                </VControl>
                                <VInput style="width: 80%" type="text" class="ml-3 input"
                                  v-model="input[data.model2]" />
                              </VField>
                              <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                                <VControl raw subcontrol>
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'checkbox2'" style="margin-bottom: 0.5rem;">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                          :label="data.subTitle" v-model="input[data.model]" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle2"
                                          :label="data.subTitle2" v-model="input[data.model2]" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'checkbox3'" style="margin-bottom: 0.5rem;">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VField>
                                        <VControl raw subcontrol>
                                          <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                            :label="data.subTitle" v-model="input[data.model]" />
                                        </VControl>
                                      </VField>
                                      <VField>
                                        <VControl raw subcontrol style="display: flex;">
                                          <VCheckbox class="p-0 mt-2" color="primary" square
                                            :true-value="data.subTitle3" :label="data.subTitle3"
                                            v-model="input[data.model3]" />
                                          <VInput type="text" class="input" v-model="input[data.model4]" />
                                        </VControl>
                                      </VField>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle2"
                                          :label="data.subTitle2" v-model="input[data.model2]" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'checkboxText'" style="margin-bottom: 0.5rem;">
                                <VControl raw subcontrol>
                                  <VCheckbox class="p-0" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                  <VField>
                                    <VTextarea rows="2" v-model="input[data.model2]">
                                    </VTextarea>
                                  </VField>
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'checkboxText2'" style="margin-bottom: 0.5rem;">
                                <VControl raw subcontrol style="display: flex;">
                                  <VCheckbox class="p-0 mt-4" color="primary" square :true-value="data.subTitle"
                                    :label="data.subTitle" v-model="input[data.model]" />
                                  <VField>
                                    <VControl>
                                      <VInput type="text" class="input mx-1" style="width: 90%"
                                        v-model="input[data.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="mt-4">
                                    <span>,</span>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="text" class="input mx-4" v-model="input[data.model3]" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>{{ data.nama }}</VButton>
                                    </VControl>
                                  </VField>
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'ttd'">
                                <VField>
                                  <span>{{ data.subTitle }}</span>
                                </VField>
                                <VField>
                                  <TandaTangan v-model="data.model" :elemenID="data.model" :width="'150'"
                                    :height="'150'" class="dek" />
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                                <span>-</span>
                                <VControl>
                                  <VInput type="text" class="input" v-model="input[data.model]" />
                                </VControl>
                              </VField>
                              <VField v-else-if="data.type == 'text'" style="display: flex;">
                                <span>- {{ data.subTitle }}</span>
                              </VField>
                              <VField v-else-if="data.type == 'text2'" style="display: flex;">
                                <span style="text-align: center;">{{ data.subTitle
                                }}</span>
                              </VField>
                              <VField v-else-if="data.type == 'manual1'">
                                <VField>
                                  <span>Preparation solution yang digunakan :</span>
                                </VField>
                                <VField style="display: flex;">
                                  <VControl raw subcontrol style="width: 50%;">
                                    <VCheckbox class="p-0" color="primary" square true-value="PovidoneIodine"
                                      label="Povidone iodine" v-model="input.eK2PovidoneIodine" />
                                  </VControl>
                                  <VControl raw subcontrol style="width: 50%;">
                                    <VCheckbox class="p-0" color="primary" square true-value="Alkohol" label="Alkohol"
                                      v-model="input.eK2Alkohol" />
                                  </VControl>
                                </VField>
                                <VField style="display: flex;">
                                  <VControl raw subcontrol style="width: 50%;">
                                    <VCheckbox class="p-0" color="primary" square true-value="ChlorhexidineAlcohol"
                                      label="Chlorhexidine alcohol" v-model="input.eK2ChlorhexidineAlcohol" />
                                  </VControl>
                                  <VControl raw subcontrol style="width: 50%;">
                                    <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                                      v-model="input.eK2Lainnya" />
                                  </VControl>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'manual2'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="DiatermiBerfungsiBaik"
                                          label="Diatermi Berfungsi Baik" v-model="input.eK2DiatermiBerfungsiBaik" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TidakDiperlukan"
                                          label="Tidak Diperlukan" v-model="input.eK2TidakDiperlukan1" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual3'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                          true-value="TerpasangDanTermonitor" label="Terpasang Dan Termonitor"
                                          v-model="input.eK2TerpasangDanTermonitor" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TidakDiperlukan"
                                          label="Tidak Diperlukan" v-model="input.eK2TidakDiperlukan2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual4'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td>
                                      <span style="text-align: center;">Nama
                                        obat</span>
                                    </td>
                                    <td>
                                      <span style="text-align: center;">Lokasi</span>
                                    </td>
                                    <td>
                                      <span style="text-align: center;">Total
                                        dosis</span>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaObat1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2Lokasi1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TotalDosis1" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaObat2" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2Lokasi2" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TotalDosis2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual5'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td colspan="2">
                                      <span style="text-align: center;">Kondisi
                                        hangat</span>
                                    </td>
                                    <td>
                                      <span style="text-align: center;">Total
                                        volume</span>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="Ya" label="Ya"
                                          v-model="input.tK2KondisiHangatYa1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="Tidak" label="Tidak"
                                          v-model="input.tK2KondisiHangatTidak1" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VField addons>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.tK2KondisiHangatText1" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>ml</VButton>
                                        </VControl>
                                      </VField>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="Ya" label="Ya"
                                          v-model="input.tK2KondisiHangatYa2" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :true-value="Tidak" label="Tidak"
                                          v-model="input.tK2KondisiHangatTidak2" />
                                      </VControl>
                                    </td>
                                    <td>
                                      <VField addons>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.tK2KondisiHangatText2" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>ml</VButton>
                                        </VControl>
                                      </VField>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual6'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td style="width: 30%;">
                                      <span style="text-align: center;">Lokasi
                                        Luka</span>
                                    </td>
                                    <td style="width: 70%;">
                                      <span style="text-align: center;">Tipe
                                        dressing</span>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual7'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td style="width: 30%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas1" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText1" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TulleGrass"
                                          label="Tulle grass" v-model="input.eK2TulleGrass1" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas2" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 30%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas3" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText3" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="TulleGrass"
                                          label="Tulle grass" v-model="input.eK2TulleGrass2" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas4" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText4" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 30%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas5" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText5" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas6" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText6" />
                                      </VControl>
                                    </td>
                                    <td style="width: 35%;">
                                      <VControl raw subcontrol style="display: flex;">
                                        <VCheckbox class="p-0 mt-3" color="primary" square true-value="LokasiLukaBebas"
                                          label="" v-model="input.eK2LokasiLukaBebas7" />
                                        <VInput type="text" class="input" v-model="input.eK2LokasiLukaBebasText7" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual8'">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Utuh" label="Utuh"
                                      v-model="input.eK2Utuh" />
                                  </VControl>
                                </VField>
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                      label="Tidak, jelaskan" v-model="input.eK2TidakJelaskan" />
                                    <VInput type="text" class="input" v-model="input.eK2TidakJelaskanText" />
                                  </VControl>
                                </VField>
                              </VField>
                              <VField v-else-if="data.type == 'manual9'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <th style="width: 10%;">NO</th>
                                    <th style="width: 40%;">NAMA BAHAN</th>
                                    <th style="width: 50%;">TIPE FIKSASI</th>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahan1" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaBahan1" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TipeFiksasi1" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahan2" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaBahan2" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TipeFiksasi2" />
                                      </VControl>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 10%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eKNomorBahan3" />
                                      </VControl>
                                    </td>
                                    <td style="width: 40%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2NamaBahan3" />
                                      </VControl>
                                    </td>
                                    <td style="width: 50%;">
                                      <VControl>
                                        <VInput type="text" class="input" v-model="input.eK2TipeFiksasi3" />
                                      </VControl>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'manual10'">
                                <table class="table is-fullwidth is-bordered">
                                  <tr>
                                    <td style="width: 50%;">
                                      <VField label="KU :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2KUText" />
                                        </VControl>
                                      </VField>
                                      <VField label="TD :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2TDText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Nadi :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2NadiText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Jumlah cairan infus :">
                                        <VField addons>
                                          <VControl>
                                            <VInput type="text" class="input"
                                              v-model="input.eK2JumlahCairanInfusText" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>cc</VButton>
                                          </VControl>
                                        </VField>
                                      </VField>
                                      <VField label="Jumlah transfusi :">
                                        <VField addons>
                                          <VControl>
                                            <VInput type="text" class="input" v-model="input.eK2JumlahTransfusiText" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>cc</VButton>
                                          </VControl>
                                        </VField>
                                      </VField>
                                    </td>
                                    <td style="width: 50%;">
                                      <VField label="Respirasi :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2RespirasiText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Suhu :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2SuhuText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Saturasi :">
                                        <VControl>
                                          <VInput type="text" class="input" v-model="input.eK2SaturasiText" />
                                        </VControl>
                                      </VField>
                                      <VField label="Jumlah perdarahan :">
                                        <VField addons>
                                          <VControl>
                                            <VInput type="text" class="input" v-model="input.eK2JumlahPerdarahanText" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>cc</VButton>
                                          </VControl>
                                        </VField>
                                      </VField>
                                      <VField label="Jumlah urine :">
                                        <VField addons>
                                          <VControl>
                                            <VInput type="text" class="input" v-model="input.eK2JumlahUrineText" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>cc</VButton>
                                          </VControl>
                                        </VField>
                                      </VField>
                                    </td>
                                  </tr>
                                </table>
                              </VField>
                              <VField v-else-if="data.type == 'textarea'" style="margin-bottom: 0.5rem;">
                                <VField :label="data.subTitle">
                                  <VTextarea rows="2" v-model="input[data.model]">
                                  </VTextarea>
                                </VField>
                              </VField>
                            </div>
                          </td>
                          <td v-else></td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <div class="column is-12">
                              <VField>
                                <VControl raw subcontrol>
                                  <VCheckbox class="p-0" color="primary" square
                                    true-value="EvaluasiKondisiPasienSebelumPindahKeRuangPerawatanPulangKeRumah"
                                    label="Evaluasi kondisi pasien sebelum pindah ke ruang perawatan/pulang ke rumah"
                                    v-model="input.evaluasiKondisiPasien" />
                                </VControl>
                              </VField>
                            </div>

                            <div class="columns is-multiline">
                              <div class="column is-3">
                                <VField label="Kesadaran :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPKesadaran" />
                                  </VControl>
                                </VField>
                                <VField label="TD :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPTD" />
                                  </VControl>
                                </VField>
                                <VField label="RR :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPRR" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-3">
                                <VField label="Nadi :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPNadi" />
                                  </VControl>
                                </VField>
                                <VField label="Suhu :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPSuhu" />
                                  </VControl>
                                </VField>
                                <VField label="Keluhan lain :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPKeluhanLain" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-3">
                                <VField label="Saturasi :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPSaturasi" />
                                  </VControl>
                                </VField>
                                <VField label="Bromage score :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPBromageScore" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-3">
                                <VField label="Skala nyeri :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPSkalaNyeri" />
                                  </VControl>
                                </VField>
                                <VField label="Aldrete score :">
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input.eVPAldreteScore" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="HandoverDenganPetugasRuangan"
                                  label="Handover dengan petugas ruangan"
                                  v-model="input.tK3HandoverDenganPetugasRuangan" />
                              </VControl>
                            </VField>
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <VField label="KETERANGAN LAIN">
                              <VTextarea rows="2" class="textarea" v-model="input.keteranganLainText">
                              </VTextarea>
                            </VField>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <span style="text-align: center;">Nama Perawat :</span>
                            <VField>
                              <VControl class="prime-auto">
                                <AutoComplete v-model="input.namaPerawatPostOperatif" :suggestions="d_Petugas"
                                  @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  class="mt-2" />
                              </VControl>
                            </VField>
                          </td>
                          <td>
                            <VField label="Tanda Tangan :">
                              <TandaTangan v-model="input.TTDperawatPostOperatif" :elemenID="'TTDperawatPostOperatif'"
                                :width="'150'" :height="'150'" class="dek" />
                            </VField>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </Fieldset>

                <Fieldset :toggleable="true" legend="KETERANGAN BAYI LAHIR" class="mt-3">
                  <div class="column columns is-multiline">
                    <div class="column is-12 pl-0 pb-0" style="text-align: center;">
                      <table class="table-pri">
                        <thead>
                          <tr>
                            <th class="th-pri">No</th>
                            <th class="th-pri">Lahir (Tanggal & Jam)</th>
                            <th class="th-pri">Jenis Kelamin</th>
                            <th class="th-pri">BB</th>
                            <th class="th-pri">PB</th>
                            <th class="th-pri">LK</th>
                            <th class="th-pri">Apgar Scroe</th>
                            <th class="th-pri">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(item, index) in input.bayiLahir" :key="index">
                            <td class="td-pri">{{ index + 1 }}</td>
                            <td class="td-pri">
                              <VDatePicker class="pt-3" v-model="item.lahirBayi" color="green" trim-weeks
                                mode="datetime" :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }" class="pb-0">
                                  <VField>
                                    <VControl icon="feather:calendar">
                                      <VInput type="text" placeholder="Select a date" :value="inputValue"
                                        v-on="inputEvents" class="is-rounded_Z" />
                                    </VControl>
                                  </VField>
                                </template>
                              </VDatePicker>
                            </td>
                            <td class="td-pri">
                              <div class="column">
                                <VField>
                                  <VControl>
                                    <VCheckbox class="p-0" color="primary" square true-value="Laki-Laki"
                                      label="Laki-Laki" v-model="item.jenisKelamin" />
                                  </VControl>
                                </VField>
                                <VField>
                                  <VControl>
                                    <VCheckbox class="p-0" color="primary" square true-value="Perempuan"
                                      label="Perempuan" v-model="item.jenisKelamin" />
                                  </VControl>
                                </VField>
                              </div>
                            </td>
                            <td class="td-pri">
                              <VField addons>
                                <VControl>
                                  <VInput type="text" v-model="item.bayiLahirBB" />
                                </VControl>
                                <VControl class="field-addon-body">
                                  <VButton static>gr</VButton>
                                </VControl>
                              </VField>
                            </td>
                            <td class="td-pri">
                              <VField addons>
                                <VControl>
                                  <VInput type="text" v-model="item.bayiLahirPB" />
                                </VControl>
                                <VControl class="field-addon-body">
                                  <VButton static>cm</VButton>
                                </VControl>
                              </VField>
                            </td>
                            <td class="td-pri">
                              <VField addons>
                                <VControl>
                                  <VInput type="text" v-model="item.bayiLahirLK" />
                                </VControl>
                                <VControl class="field-addon-body">
                                  <VButton static>cm</VButton>
                                </VControl>
                              </VField>
                            </td>
                            <td class="td-pri">
                              <VField>
                                <VControl>
                                  <VInput type="text" v-model="item.bayiLahirApgar" />
                                </VControl>
                              </VField>
                            </td>
                            <td class="td-pri">
                              <VButtons style="justify-content:space-around">
                                <VIconButton type="button" raised circle icon="feather:plus"
                                  @click="addNewBayiLahir(index)" color="info" v-tooltip.bubble="'Tambah '">
                                </VIconButton>
                                <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                  icon="feather:trash" @click="deleteNewBayiLahir(index)" color="danger">
                                </VIconButton>
                              </VButtons>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </Fieldset>
              </div>
              <div class="column is-12">
              </div>
              <div class="column is-12">
              </div>
            </div>
          </Fieldset>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core';
import { useApi } from '/@src/composable/useApi';
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue';
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router';
import { useHead } from '@vueuse/head';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useThemeColors } from '/@src/composable/useThemeColors';
import { useUserSession } from '/@src/stores/userSession';
import * as H from '/@src/utils/appHelper';
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue';
import ImgDraw from '../../../page-emr-plugins/img-draw.vue';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import * as EMR from '../../../page-emr-plugins/asuhan-keperawatan-peri-operatif';
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import BerkasPasienView from '../../berkas-pasien-preview.vue'

const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
useHead({ title: 'Asuhan Keperawatan Peri-Operatif' + import.meta.env.VITE_PROJECT })
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let Pengkajian = ref(EMR.Pengkajian())
let TimOperasi = ref(EMR.TimOperasi())
let DataFokus = ref(EMR.DataFokus())
let DataObyektif = ref(EMR.DataObyektif())
let pOVitalSign = ref(EMR.pOVitalSign())
let diagnosaKeperawatan = ref(EMR.diagnosaKeperawatan())
let diagnosaKeperawatan2 = ref(EMR.diagnosaKeperawatan2())
let diagnosaKeperawatan3 = ref(EMR.diagnosaKeperawatan3())
let rencanaKeperawatan = ref(EMR.rencanaKeperawatan())
let rencanaKeperawatan2 = ref(EMR.rencanaKeperawatan2())
let rencanaKeperawatan3 = ref(EMR.rencanaKeperawatan3())
let tindakanKeperawatan = ref(EMR.tindakanKeperawatan())
let evaluasiKeperawatan = ref(EMR.evaluasiKeperawatan())
let tindakanKeperawatan2 = ref(EMR.tindakanKeperawatan2())
let evaluasiKeperawatan2 = ref(EMR.evaluasiKeperawatan2())
let tindakanKeperawatan3 = ref(EMR.tindakanKeperawatan3())
let evaluasiKeperawatan3 = ref(EMR.evaluasiKeperawatan3())
let kondisiPasien = ref(EMR.kondisiPasien())
let pasienMengeluh = ref(EMR.pasienMengeluh())
let setInstrumen = ref(EMR.setInstrumen())
let alatLain = ref(EMR.alatLain())
let jenisAnestesi = ref(EMR.jenisAnestesi())
let iOBreath = ref(EMR.iOBreath())
let pOBreath = ref(EMR.pOBreath())
let pOKeterangan = ref(EMR.pOKeterangan())


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
    COLLECTION: 'AsuhanKeperawatanPeriOperatif',
  }
)
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const dataTTD: any = ref([])
const listTemplateFix: any = ref([])
const modalInput: any = ref(false)
const modalBerkasPreview: any = ref(false)
const d_Berkas: any = ref([])
const dataSource: any = ref([])
const filePasien: any = ref()
const showModalTemplateFix: any = ref(false)
const user = useUserSession().getUser().pegawai;
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const d_Petugas: any = ref([])
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}
const d_Dokter: any = ref([])
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_klem: any = ref([{ value: 1, label: '10' }, { value: 2, label: '11' }, { value: 3, label: '15' }, { value: 4, label: '20' }, { value: 5, label: 'Lainnya' }])
const MARKINGSITE: any = ref('')
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}
const addNewItem = (index = null) => {
  const predefinedValues = ["Kasa kecil", "Kasa besar", "Kasa reyteg", "Deepers", "Needles atraumatic", "Needles ordinary", "Syringe needle", "Arteri klem", "MESS"];
  const currentIndex = input.value.details.length;

  const newItem = {
    no: currentIndex + 1,
    iOJenisNamaItem: currentIndex < predefinedValues.length ? predefinedValues[currentIndex] : "MESS",
    iOPenghitunganAwal: "",
    iOPenambahanItem1: "",
    iOPenambahanItem2: "",
    iOTotalTambahan: "",
    iOPenghitunganAkhir: "",
  };
  input.value.details.push(newItem);
};

const removeItem2 = (index: any) => {
  input.value.details2.splice(index, 1)
}
const addNewItem2 = () => {
  let newItem: any = {}
  newItem = { no: input.value.details2[input.value.details2.length - 1].no + 1 }
  input.value.details2.push(newItem);
}

const onSelect = async (filez: any) => {
    const file = filez.files[0];
    filePasien.value = file
}

const input: any = ref({
  tanggal: new Date(),
  waktuOperasi1_: new Date(),
  waktuOperasi2_: new Date(),
  waktuOperasi3_: new Date(),
  waktuOperasi4_: new Date(),
  waktuOperasi5_: new Date(),
  waktuOperasi6_: new Date(),
  waktuOperasi7_: new Date(),
  waktuOperasi8_: new Date(),
  waktuOperasi9_: new Date(),
  jamPackingTenggorokan_: new Date(),
  torniquetJamMulai: new Date(),
  torniquetJamSelesai: new Date(),
  jamPerawatAnestesi: new Date(),
  jamPerawatInstrumen: new Date(),
  jamPerawatSirkuler: new Date(),
  eKJam_: new Date(),
  details4: [],
  details: [{
    no: 1,
    iOJenisNamaItem: "Kasa kecil",
  },

  {
    no: 2,
    iOJenisNamaItem: "Kasa besar",
  },
  {
    no: 3,
    iOJenisNamaItem: "Kasa reyteg",
  },
  {
    no: 4,
    iOJenisNamaItem: "Deepers",
  },
  {
    no: 5,
    iOJenisNamaItem: "Needles atraumatic",
  },
  {
    no: 6,
    iOJenisNamaItem: "Needles ordinary",
  },
  {
    no: 7,
    iOJenisNamaItem: "Syringe needle",
  },
  {
    no: 8,
    iOJenisNamaItem: "Arteri klem",
  }],
  details2: [{
    no: 1,
  }],
  bayiLahir: [{
    no: 1
  }]
})

const simpanFile = async () => {
    if (!item.namafile) {
        H.alert('error', 'Jenis File harus di isi')
        return
    }
    if (!item.nama) {
        H.alert('error', 'Nama harus di isi')
        return
    }
    if (!filePasien.value) {
        H.alert('error', 'File harus di unggah')
        return
    }
    const formData = new FormData()
    formData.append('filePasien', filePasien.value)
    formData.append('norec', item.norec ? item.norec : '')
    formData.append('noregistrasi', props.registrasi.noregistrasi)
    formData.append('nocm', props.pasien.nocm)
    formData.append('norec_apd', props.registrasi.norec_apd)
    formData.append('namafile', item.namafile.label)
    formData.append('keterangan', item.keterangan ? item.keterangan : null)
    formData.append('objectberkaspasien', item.namafile.value)
    formData.append('nama', item.nama)
    formData.append('author', item.author)
    // formData.append('halaman', parseInt(route.params.index_tabs))
    isLoading.value = true
    await useApi().post('/emr/simpan-berkas-pasien-old', formData).then((r) => {
        isLoading.value = false
        loadRiwayatBerkas()
        modalInput.value = false
    }).catch((e: any) => {
        isLoading.value = false
    })
}


const addNewBayiLahir = () => {
  let newItem: any = {}
  newItem = { no: input.value.bayiLahir[input.value.bayiLahir.length - 1].no + 1 }
  input.value.bayiLahir.push(newItem);
}

const deleteNewBayiLahir = (index: any) => {
  input.value.bayiLahir.splice(index, 1)
}

const activeValue: any = ref(0)
const idTemplate: any = ref('');
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const ttdIds = ["signature_1", "rK2TTDPerawat_", "TTDperawatAnestesi", "TTDperawatInstrumen", "TTDperawatSirkuler", "TTDpIOPerawatInstrumen1", "TTDpIOPerawatInstrumen2", "TTDpIOPerawatInstrumen3", "TTDpIOPerawatInstrumen4", "TTDpIOPerawatSirkuler1", "TTDpIOPerawatSirkuler2", "TTDpIOPerawatSirkuler3", "TTDpIOPerawatSirkuler4", "TTDperawatPostOperatif", "Gambar1"];

const loadRiwayat = async () => {
  let tabs = route.params.index_tabs < 1 ? route.params.index_tabs - 1 : 1
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`)
  let check = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${tabs}&check_first_tab=true`)
  if (response.length && check.length != 0) {
    isSave.value = true
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    if (!input.value.details || input.value.details.length === 0) {
      input.value.details = [{
        no: 1,
        iOJenisNamaItem: "Kasa kecil",
      }];
    }
    if (!input.value.details2 || input.value.details2.length === 0) {
      input.value.details2 = [{
        no: 1,
      }];
    }
    if (!input.value.bayiLahir || input.value.bayiLahir.length === 0) {
      input.value.bayiLahir = [{
        no: 1,
      }];
    }
    setTandaTanganOrGambar(response);
  } else {
    if (check.length == 0 && route.params.index_tabs != 1) {
      H.alert('warning', 'Halaman sebelumnnya belum disimpan!');
    }
    isSave.value = false
    setAutoFill()
  }
}

const lihat = async (e: any) => {
    H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile);
}

// Function to set Tanda Tangan or Gambar to dataTTD.value
const setTandaTanganOrGambar = (response) => {
  ttdIds.forEach((id) => {
    // Check if the response contains the id
    if (response[0][id]) {
      // If the id contains 'gambar' (case insensitive), use loadGambar
      if (id.toLowerCase().includes("gambar")) {
        loadGambar(id, response[0][id]); // Call loadGambar for 'gambar' ids
        dataTTD.value[id] = response[0][id]; // Set the gambar value into dataTTD.value
      } else {
        // Otherwise, set directly to dataTTD and use tandaTangan().set
        dataTTD.value[id] = response[0][id]; // Set the value into dataTTD
        H.tandaTangan().set(id, response[0][id]); // Set it using tandaTangan()
      }

    }
  });
};

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById(element_id);
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = value
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, 250, 250);
    }
  }
}

const clearCanvas = (canvas: any) => {

  var sigCanvas: any = document.getElementById(canvas);
  var context = sigCanvas.getContext("2d");
  context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

}

const addSignaturesToObject = (object: any, ids: string[], tandaTangan: any) => {
  ids.forEach((id) => {
    const key = id; // Sanitize ID to create a valid key
    object[key] = tandaTangan().get(id);
  });
};

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  addSignaturesToObject(object, ttdIds, H.tandaTangan);
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  if (route.params.index_tabs) {
    object.index_tabs = parseInt(route.params.index_tabs)
  }
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
      loadRiwayat();
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchJenisFile = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/berkaspasien_m?select=id,nama`)
    d_Berkas.value = response.filter(item => item.label.toLowerCase().includes('blanko')).map(item => ({
        value: item.value,
        label: item.label
    }));
}

const edit = async (e: any) => {
    item.author = e.author
    item.norec = e.norec
    item.keterangan = e.deskripsi
    item.nama = e.nama
    d_Berkas.value.forEach((element: any) => {
        if (e.objectberkaspasien == element.id) {
            item.namafile = element
        }
    });
    let path = 'berkaspasien/' + e.nocm + '/' + e.namafile
    let file = await H.getFileBE(path);

    filePasien.value = file
    filePasien.value.name = e.namafile
    modalInput.value = true
}
const hapus = async (e: any) => {
    e.loadingHapus = true
    await useApi().post(`/emr/hapus-berkas-pasien`, { 'norec': e.norec, }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayatBerkas()
    })
}

// Load Index
watch(
  () => route.params.index_tabs,
  (newValue, oldValue) => {
    input.value = {}
    input.value.DTttd = new Date()
    NOREC_EMRPASIEN.value = '';
    loadRiwayat()
    let rouutename = route.name + '-' + route.params.index_tabs
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
    if (cache) {
      input.value = cache
    }
  })
watch(
  () => input.value,
  (newValue, oldValue) => {
    let rouutename = route.name + '-' + route.params.index_tabs
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    let timeout = null;
    if (timeout) {
      clearTimeout(timeout);
    }
    timeout = setTimeout(() => {
      H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue);
    }, 500);
  }, { deep: true }
)

const addUpload = () => {
    modalInput.value = true
    item.author = user.namaLengkap
}
const previewBerkas = async () => {
    isLoading.value = true
    await loadRiwayatBerkas()
    isLoading.value = false
    modalBerkasPreview.value = true;
}

const loadRiwayatBerkas = async () => {
    isLoading.value = true
    let param = `nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}&dokumen=${52}`;
    await useApi().get(`/emr/berkas-pasien?${param}`).then((response: any) => {
        isLoading.value = false
        dataSource.value = response.data
    })
}

const simpanTemplate = () => {
  if (input.value.namatemplate == null) {
    H.alert('warning', 'Isi nama template terlebih dahulu!')
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
  let object: any = {}

  object = input.value
  object.nocm = props.pasien.nocm
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
    checkTemplate.value = false
    input.value.namatemplate = null
    delete object.namatemplate;
    delete object['_id'];
    delete object.nocm;
    delete object.pasien;
    delete object.regisstrasi;
    object.id = '';
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const checkTemplate: any = ref(false)
const isSave: any = ref(false)
const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
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

const getLabo = () => {
  isLoading.value = true
  // let stringLabo = 'Labora';
  let uri = `laboratorium/riwayat-order?nocmfk=${props.pasien.nocmfk}&norec_pd=${item.NOREC_PD}`

  useApi().get(uri).then((res) => {
    let hasilLab = '';
    if (res.length > 0) {
      for (let index = 0; index < res.length; index++) {
        const element = res[index];
        if (element.details && element.details.length > 0) {
          for (let i = 0; i < element.details.length; i++) {
            const detail = element.details[i];
            hasilLab += '# ' + detail.namaproduk + ' ';
          }
        }
      }
      if (input.value.dataPenunjangLaboratorium_ != undefined || input.value.dataPenunjangLaboratorium_ != null) {
        input.value.dataPenunjangLaboratorium_ += '\nLaboratorium : ' + hasilLab;
      } else {
        input.value.dataPenunjangLaboratorium_ = 'Laboratorium : ' + hasilLab;
      }
      H.alert('success', 'Berhasil ditambahkan')
    } else {
      H.alert('warning', 'Belum ada riwayat')
    }
    isLoading.value = false
  })
}

const getRadio = () => {
  isLoading.value = true
  // let stringLabo = 'Labora';
  let uri = `radiologi/layanan-radiologi?norec_pd=${item.NOREC_PD}`;

  useApi().get(uri).then((res) => {
    let hasilRadio = '';
    if (res && res.detail.length > 0) {
      for (let index = 0; index < res.detail.length; index++) {
        const group = res.detail[index];
        if (group.details.length > 0) {
          for (let i = 0; i < group.details.length; i++) {
            const detail = group.details[i];
            if (detail.hasil != null) {
              hasilRadio += '# ' + detail.hasil + ' ';
            }
          }
        }
      }
      if (input.value.radiologi_ != undefined || input.value.radiologi_ != null) {
        input.value.radiologi_ += '\nRadiologi : ' + hasilRadio;
      } else {
        input.value.radiologi_ = 'Radiologi : ' + hasilRadio;
      }
      H.alert('success', 'Berhasil ditambahkan')
    } else {
      H.alert('warning', 'Belum ada riwayat')
    }
    isLoading.value = false
  })
}

function setPenunjang() {
  isLoading.value = true;
  let str = ''
  let gcol = `PemeriksaanKardiotokografi,PemeriksaanObstetri,PemeriksaanGynekologi,PemeriksaanFetal`;
  useApi().get(`emr/get-penunjang-khusus?tables=${gcol}&norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((dt) => {
    // console.log("res", dt);
    isLoading.value = false;
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
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          // str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
        }
      }

      let detail = input.value;

      if (str != '') {
        if (detail.hasilpemeriksaanpenunjang == undefined) {
          detail.hasilpemeriksaanpenunjang = ''
          detail.hasilpemeriksaanpenunjang += str;
        } else {
          detail.hasilpemeriksaanpenunjang += '\n' + str
        }

        H.alert('success', 'Berhasil ambil data')
      } else {
        H.alert('warning', 'Penunjang Khusus belum ada')
      }
    }
  });

}

const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplateFix.value = false
}

watch(
  () => input.value.selectAllTK2,
  (newValue, oldValue) => {
    if (newValue != false) {
      input.value.tK2MendampingiDanMengantarPasienPindahKeMejaOperasi_ = 'Mendampingi dan mengantar pasien pindah ke meja operasi'
      input.value.tK2MemberiDukunganPsikologis_ = 'Memberi dukungan psikologis, mengkomunikasikan setiap tindakan yang akan dilakukan dan menjaga privacy pasien'
      input.value.tK2MemasangBedSideMonitor_ = 'Memasang bed side monitor & melakukan observasi vital sign'
      input.value.tK2KolaborasiDalamPemberianAnestesi_ = 'Kolaborasi dalam pemberian anestesi'
      input.value.tK2MengaturPosisiPasienPembedahanMencegahCideraAkibatPosisiPembedahan_ = 'Mengatur posisi pasien untuk pembedahan serta mencegah terjadinya cidera akibat posisi pembedahan'
      input.value.tK2MelakukanPencegahanKerusakanIntegritasKulit_ = 'Melakukan pencegahan kerusakan integritas kulit yang tertekan dengan memberi alas lembut, mengikat dengan baik dan mengobservasi keutuhan kulit yang tertekan'
      input.value.tK2MencukurDaerahOperasi_ = 'Mencukur daerah operasi'
      input.value.tK2MelakukanProsedurSeptikAseptikPembedahan_ = 'Melakukan prosedur septik aseptik pembedahan (scrubing, gowning, gloving, penataan instrumen bedah)'
      input.value.tK2MelakukanPerhitunganIntraOperatif_ = 'Melakukan penghitungan intra operatif (sesuai ceklist alat)'
      input.value.tK2MelakukanMemfasilitasiTindakanSkinPreparationDraping_ = 'Melakukan/memfasilitasi tindakan skin preparation dan draping'
      input.value.tK2MelakukanMemfasilitasiTindakanMengunakanESU_ = 'Melakukan/memfasilitasi tindakan menggunakan ESU (diatermi)'
      input.value.tK2MemasangDanMemonitorPenggunaanTorniquet_ = 'Memasang dan memonitor penggunaan torniquet'
      input.value.tK2MelakukanTimeOut_ = 'Melakukan time out'
      input.value.tK3Observasiperdarahanpervagina_ = 'Observasi perdarahan pervagina'
      input.value.tK3Observasikontraksiuterus_ = 'Observasi kontraksi uterus'
      input.value.tK2MemfasilitasiPenggunaanAnestesiLokal_ = 'Memfasilitasi penggunaan anestesi lokal'
      input.value.tK2MelakukanInstrumentalTeknikDanKolaborasiPembedahan_ = 'Melakukan instrumentasi teknik dan kolaborasi pembedahan'
      input.value.tK2MelakukanPenutupanLukaDanPerawatanDrain_ = 'Melakukan penutupan luka dan perawatan drain'
      input.value.tK2MengobservasiKeutuhanKulitDaerahPemasanganPlateDiatermi_ = 'Mengobservasi keutuhan kulit daerah pemasangan plate diatermi'
      input.value.tK2MenyiapkanBahanPemeriksaanJaringanPatologiAnatomi_ = 'Menyiapkan bahan pemeriksaan jaringan patologi anatomi'
      input.value.tK2MelakukanSignOut_ = 'Melakukan sign out'
      input.value.tK2KolaborasiDalamPengakhiranAnestesi_ = 'Kolaborasi dalam pengakhiran anestesi'
      input.value.tK2EvaluasiKondisiPasienSebelumMeninggalkanKamarOperasi_ = 'Evaluasi kondisi pasien sebelum meninggalkan kamar operasi'
      input.value.tK2MengantarPasienPindahKeRR_ = 'Mengantar pasien pindah ke RR'
      input.value.eK2PasienDiMejaOperasiDidampingiTimOperasi_ = 'Pasien di meja operasi didampingi tim operasi'
      input.value.eK2PasienMenyatakanSiapMenjalaniOperasi_ = 'Pasien menyatakan siap menjalani operasi'
      input.value.eK2PasienMasihCemas_ = 'Pasien masih cemas'
      input.value.eK2BedSiteMonitorTerpasangDanBerfungsiBaik_ = 'Bed side monitor terpasang dan berfungsi baik'
      input.value.eK2PemberianAnestesiBerjalanLancar_ = 'Pemberian anestesi berjalan lancar'
      input.value.eK2PosisiDiaturCederaTidakTerjadi_ = 'Posisi diatur, cedera tidak terjadi'
      input.value.eK2KerusakanIntegritasKulitTidakTerjadi_ = 'Kerusakan integritas kulit tidak terjadi'
      input.value.eK2DaerahOperasiBersih_ = 'Daerah operasi bersih'
      input.value.eK2ProsedurTerlaksanaSesuaiStandarYangBerlaku_ = 'Prosedur terlaksana sesuai standar yang berlaku'
      input.value.eK2PenghitunganBenarSesuaiCeklist_ = 'Penghitungan benar sesuai ceklist'
      input.value.eK2TerlaksanaDenganBaikSesuaiCeklist_ = 'Terlaksana dengan baik sesuai ceklist'
      input.value.eK3PervaginaTidakAdaTandaPerdarahanAktif_ = 'Tidak ada tanda perdarahan aktif'
      input.value.eK2InstrumentalBerjalanLancar_ = 'Instrumentasi berjalan lancar'
      input.value.eK2TerlaksanaDenganBaikSesuaiCeklistTimeOut_ = 'Terlaksana dengan baik sesuai ceklist time out'
      input.value.eK2PengakhiranAnestesiBerjalanBaik_ = 'Pengakhiran anestesi berjalan baik'
      input.value.eK2PasienSudahDiRRHandover_ = 'Pasien sudah di RR, dilakukan handover dengan petugas RR'
    } else {
      input.value.tK2MendampingiDanMengantarPasienPindahKeMejaOperasi_ = ''
      input.value.tK2MemberiDukunganPsikologis_ = ''
      input.value.tK2MemasangBedSideMonitor_ = ''
      input.value.tK2KolaborasiDalamPemberianAnestesi_ = ''
      input.value.tK2MengaturPosisiPasienPembedahanMencegahCideraAkibatPosisiPembedahan_ = ''
      input.value.tK2MelakukanPencegahanKerusakanIntegritasKulit_ = ''
      input.value.tK2MencukurDaerahOperasi_ = ''
      input.value.tK2MelakukanProsedurSeptikAseptikPembedahan_ = ''
      input.value.tK2MelakukanPerhitunganIntraOperatif_ = ''
      input.value.tK2MelakukanMemfasilitasiTindakanSkinPreparationDraping_ = ''
      input.value.tK2MelakukanMemfasilitasiTindakanMengunakanESU_ = ''
      input.value.tK2MemasangDanMemonitorPenggunaanTorniquet_ = ''
      input.value.tK2MelakukanTimeOut_ = ''
      input.value.tK3Observasiperdarahanpervagina_ = ''
      input.value.tK3Observasikontraksiuterus_ = ''
      input.value.tK2MemfasilitasiPenggunaanAnestesiLokal_ = ''
      input.value.tK2MelakukanInstrumentalTeknikDanKolaborasiPembedahan_ = ''
      input.value.tK2MelakukanPenutupanLukaDanPerawatanDrain_ = ''
      input.value.tK2MengobservasiKeutuhanKulitDaerahPemasanganPlateDiatermi_ = ''
      input.value.tK2MenyiapkanBahanPemeriksaanJaringanPatologiAnatomi_ = ''
      input.value.tK2MelakukanSignOut_ = ''
      input.value.tK2KolaborasiDalamPengakhiranAnestesi_ = ''
      input.value.tK2EvaluasiKondisiPasienSebelumMeninggalkanKamarOperasi_ = ''
      input.value.tK2MengantarPasienPindahKeRR_ = ''
      input.value.eK2PasienDiMejaOperasiDidampingiTimOperasi_ = ''
      input.value.eK2PasienMenyatakanSiapMenjalaniOperasi_ = ''
      input.value.eK2PasienMasihCemas_ = ''
      input.value.eK2BedSiteMonitorTerpasangDanBerfungsiBaik_ = ''
      input.value.eK2PemberianAnestesiBerjalanLancar_ = ''
      input.value.eK2PosisiDiaturCederaTidakTerjadi_ = ''
      input.value.eK2KerusakanIntegritasKulitTidakTerjadi_ = ''
      input.value.eK2DaerahOperasiBersih_ = ''
      input.value.eK2ProsedurTerlaksanaSesuaiStandarYangBerlaku_ = ''
      input.value.eK2PenghitunganBenarSesuaiCeklist_ = ''
      input.value.eK2TerlaksanaDenganBaikSesuaiCeklist_ = ''
      input.value.eK3PervaginaTidakAdaTandaPerdarahanAktif_ = ''
      input.value.eK2InstrumentalBerjalanLancar_ = ''
      input.value.eK2TerlaksanaDenganBaikSesuaiCeklistTimeOut_ = ''
      input.value.eK2PengakhiranAnestesiBerjalanBaik_ = ''
      input.value.eK2PasienSudahDiRRHandover_ = ''
    }
  }
)

watch(
  () => input.value.selectAllTK1,
  (newValue, oldValue) => {
    if (newValue != false) {
      input.value.tKMelakukanHandover_ = 'Melakukan handover dan mengevaluasi kelengkapan dokumen pre operasi'
      input.value.tKMemperkenalkanDiri_ = 'Memperkenalkan diri petugas kamar operasi pada pasien'
      input.value.tKMemberikanOrientasi_ = 'Memberikan orientasi dan informasi lingkungan'
      input.value.tKMemberikanHE_ = 'Memberikan H.E tentang prosedur operasi'
      input.value.tKMengobservasiVitalSign = 'Mengobservasi vital sign (hasil ada pada catatan anestesi)'
      input.value.tKMemasangIntraVena_ = 'Memasang/evaluasi akses intra vena'
      input.value.tKMengaturPosisiPasien_ = 'Mengatur posisi pasien sesuai dengan kebutuhan'
      input.value.tKMenyiapkanMesinAnestesi_ = 'Menyiapkan mesin anestesi'
      input.value.tKMenyiapkanAlatObatAnestesi_ = 'Menyiapkan alat dan obat anestesi'
      input.value.tKMembantuPemberianPremedikasi_ = 'Membantu pemberian premedikasi'
      input.value.tKMemonitorEfekPremedikasi_ = 'Memonitor efek pemberian premedikasi'
      input.value.tKMenyiapkanAlatObatPembedahan_ = 'Menyiapkan alat dan obat sesuai pembedahan'
      input.value.tKMenyiapkanLingkunganKamarOperasi_ = 'Menyiapkan lingkungan kamar operasi'
      input.value.tKMelakukanSign_ = 'Melakukan sign in'
      input.value.tKMemberikanAntibiotika_ = 'Memberikan antibiotika sesuai instruksi dokter'
      input.value.tK3Observasiperdarahanpervagina2_ = 'Observasi perdarahan pervagina'
      input.value.tK3Observasikontraksiuterus2_ = 'Observasi kontraksi uterus'
      input.value.eKLengkap_ = 'Lengkap'
      input.value.eKYa1_ = 'Ya'
      input.value.eKPasienMengerti1_ = 'Pasien mengerti'
      input.value.eKPasienMengerti2_ = 'Pasien mengerti'
      input.value.eKYa2_ = 'Ya'
      input.value.eKLancar_ = 'Lancar'
      input.value.eKSiap1_ = 'Siap'
      input.value.eKSiap2_ = 'Siap'
      input.value.eKYa3_ = 'Ya'
      input.value.eKYa4_ = 'Ya'
      input.value.eKSiap3_ = 'Siap'
      input.value.eKSiap4_ = 'Siap'
      input.value.eKYa5_ = 'Ya'
      input.value.eKYa6_ = 'Ya'

    } else {
      input.value.tKMelakukanHandover_ = ''
      input.value.tKMemperkenalkanDiri_ = ''
      input.value.tKMemberikanOrientasi_ = ''
      input.value.tKMemberikanHE_ = ''
      input.value.tKMengobservasiVitalSign = ''
      input.value.tKMemasangIntraVena_ = ''
      input.value.tKMengaturPosisiPasien_ = ''
      input.value.tKMenyiapkanMesinAnestesi_ = ''
      input.value.tKMenyiapkanAlatObatAnestesi_ = ''
      input.value.tKMembantuPemberianPremedikasi_ = ''
      input.value.tKMemonitorEfekPremedikasi_ = ''
      input.value.tKMenyiapkanAlatObatPembedahan_ = ''
      input.value.tKMenyiapkanLingkunganKamarOperasi_ = ''
      input.value.tKMelakukanSign_ = ''
      input.value.tKMemberikanAntibiotika_ = ''
      input.value.tK3Observasiperdarahanpervagina2_ = ''
      input.value.tK3Observasikontraksiuterus2_ = ''
      input.value.eKLengkap_ = ''
      input.value.eKYa1_ = ''
      input.value.eKPasienMengerti1_ = ''
      input.value.eKPasienMengerti2_ = ''
      input.value.eKYa2_ = ''
      input.value.eKLancar_ = ''
      input.value.eKSiap1_ = ''
      input.value.eKSiap2_ = ''
      input.value.eKYa3_ = ''
      input.value.eKYa4_ = ''
      input.value.eKSiap3_ = ''
      input.value.eKSiap4_ = ''
      input.value.eKYa5_ = ''
      input.value.eKYa6_ = ''
    }
  })

watch(
  () => input.value.dKCemas_,
  (newValue, oldValue) => {
    if(newValue != false) {
      input.value.rKLaksanakanProtap_ = 'Laksanakan protap interaksi sosial'
      input.value.rKLaksanakanOrientasi_ = 'Laksanakan orientasi pre operasi'
      input.value.rKHEProsedur_ = 'H.E prosedur operasi'
      input.value.rKKolaborasiPemberian_ = 'Kolaborasi pemberian premedikasi'
      input.value.rKMonitorEfek_ = 'Monitor efek pemberian premedikasi'
    }else {
      input.value.rKLaksanakanProtap_ = undefined
      input.value.rKLaksanakanOrientasi_ = undefined
      input.value.rKHEProsedur_ = undefined
      input.value.rKKolaborasiPemberian_ = undefined
      input.value.rKMonitorEfek_ = undefined
    }
  }
)

watch(
  () => input.value.dKNyeri_,
  (newValue, oldValue) => {
    if(newValue != false) {
      input.value.rKKajiSkala_ = 'Kaji skala nyeri'
      input.value.rKMemberikanPosisi_ = 'Memberikan posisi yang nyaman'
      input.value.rKAjarkanTeknik_ = 'Ajarkan teknik relaksasi dan distraksi'
      input.value.rKKolaborasiDokter_ = 'Kolaborasi dokter untuk pemberian obat analgetika'
    }else {
      input.value.rKKajiSkala_ = undefined
      input.value.rKMemberikanPosisi_ = undefined
      input.value.rKAjarkanTeknik_ = undefined
      input.value.rKKolaborasiDokter_ = undefined
    }
  }
)

watch(
  () => input.value.dKRisikoCedera_,
  (newValue, oldValue) => {
    if(newValue != false) {
      input.value.rKCekKelengkapan_ = 'Cek kelengkapan dokumen pre operasi'
      input.value.rKMenyiapkanMesin_ = 'Menyiapkan mesin anestesi'
      input.value.rKMenyiapkanAlatAnestesi_ = 'Menyiapkan alat dan obat anestesi'
      input.value.rKMenyiapkanAlatPembedahan_ = 'Menyiapkan alat dan obat sesuai pembedahan'
      input.value.rKMelakukanSignIn_ = 'Melakukan sign in'
    }else {
      input.value.rKCekKelengkapan_ = undefined
      input.value.rKMenyiapkanMesin_ = undefined
      input.value.rKMenyiapkanAlatAnestesi_ = undefined
      input.value.rKMenyiapkanAlatPembedahan_ = undefined
      input.value.rKMelakukanSignIn_ = undefined
    }
  }
)

watch(
  () => input.value.dKRisikoGangguan_,
  (newValue, oldValue) => {
    if(newValue != false) {
      input.value.rKObservasiVitalSign_ = 'Observasi vital sign dan keadaan umum pasien'
      input.value.rKKolaborasiPemasangan_ = 'Kolaborasi pemasangan cairan intra vena'
      input.value.rKObservasiIntakeOutput_ = 'Observasi intake out put'
    }else {
      input.value.rKObservasiVitalSign_ = undefined
      input.value.rKKolaborasiPemasangan_ = undefined
      input.value.rKObservasiIntakeOutput_ = undefined
    }
  }
)

watch(
  () => input.value.selectAll4,
  (newValue, oldValue) => {
    if (newValue != false) {
        input.value.tK3MelakukanHandoverPasien_ = 'Melakukan handover pasien'
        input.value.tK3MengaturPosisiPasienSesuaiDenganKebutuhan_ = 'Mengatur posisi pasien sesuai dengan kebutuhan'
        input.value.tK3MemberikanTerapiOksigen_ = 'Memberikan therapi oksigen'
        input.value.tK3MengobservasiVitalSign_ = 'Mengobservasi vital sign'
        input.value.tK3MengobservasiIntakeDanOutput_ = 'Mengobservasi intake dan out put'
        input.value.tK3MengobservasiKondisiLukaOperasiDanDrain_ = 'Mengobservasi kondisi luka operasi dan drain'
        input.value.tK3MelakukanKolaborasiDalamPemberianAnalgetik_ = 'Melakukan kolaborasi dalam pemberian analgetik'
        input.value.tK3MelakukanKolaborasiManajemenMualMuntah_ = 'Melakukan kolaborasi manajemen mual muntah'
        input.value.tK3MelakukanPencegahanPenangananPasienHipothermi_ = 'Melakukan pencegahan/penanganan pasien hipothermi/mengigil'
        input.value.tK3MelakukanPenilaianBromageScore_ = 'Melakukan penilaian Bromage Score'
        input.value.tK3MelakukanPenilaianAldreteScore_ = 'Melakukan penilaian Aldrete Score'
        input.value.tK3MemfasilitasiPemenuhanKebutuhanADLSelamaProsesRecovery_ = 'Memfasilitasi pemenuhan kebutuhan ADLselama proses recovery'
        input.value.eK3TerlaksanaDenganBaik_ = 'Terlaksana dengan baik'
        input.value.eK3PosisiPasien_ = 'Posisi pasien'
        input.value.eK3Ya_ = 'Ya'
        input.value.eK3PasienTerobservasi_ = 'Pasien terobservasi (terdokumentasi pada catatan anestesi)'
        input.value.eK3Ya1_ = 'Ya'
        input.value.eK3TidakAdaTandaPerdarahanAktif_ = 'Tidak ada tanda perdarahan aktif'
        input.value.eK3DitemukanTandaPerdarahanAktif_ = 'Ditemukan tanda perdarahan aktif'
        input.value.eK3PervaginaTidakAdaTandaPerdarahanAktif_ = 'Tidak ada tanda perdarahan aktif'
        input.value.eK3PervaginaDitemukanTandaPerdarahanAktif_ = 'Ditemukan tanda perdarahan aktif'
        input.value.eK3Kontraksiada_ = 'Kontraksi ada dan kuat'
        input.value.eK3Kontraksitidakada_ = 'Kontraksi lemah atau tidak ada'
        input.value.eK3Ya2_ = 'Ya'
        input.value.eK3Ya3_ = 'Ya'
        input.value.eK3Ya4_ = 'Ya'
        input.value.eK3Nilai1_ = 'Nilai :'
        input.value.eK3Nilai2_ = 'Nilai :'
        input.value.eK3Ya5_ = 'Ya'
    } else{
        input.value.tK3MelakukanHandoverPasien_ = ''
        input.value.tK3MengaturPosisiPasienSesuaiDenganKebutuhan_ = ''
        input.value.tK3MemberikanTerapiOksigen_ = ''
        input.value.tK3MengobservasiVitalSign_ = ''
        input.value.tK3MengobservasiIntakeDanOutput_ = ''
        input.value.tK3MengobservasiKondisiLukaOperasiDanDrain_ = ''
        input.value.tK3MelakukanKolaborasiDalamPemberianAnalgetik_ = ''
        input.value.tK3MelakukanKolaborasiManajemenMualMuntah_ = ''
        input.value.tK3MelakukanPencegahanPenangananPasienHipothermi_ = ''
        input.value.tK3MelakukanPenilaianBromageScore_ = ''
        input.value.tK3MelakukanPenilaianAldreteScore_ = ''
        input.value.tK3MemfasilitasiPemenuhanKebutuhanADLSelamaProsesRecovery_ = ''
        input.value.eK3TerlaksanaDenganBaik_ = ''
        input.value.eK3PosisiPasien_ = ''
        input.value.eK3Ya_ = ''
        input.value.eK3PasienTerobservasi_ = ''
        input.value.eK3Ya1_ = ''
        input.value.eK3TidakAdaTandaPerdarahanAktif_ = ''
        input.value.eK3DitemukanTandaPerdarahanAktif_ = ''
        input.value.eK3PervaginaTidakAdaTandaPerdarahanAktif_ = ''
        input.value.eK3PervaginaDitemukanTandaPerdarahanAktif_ = ''
        input.value.eK3Kontraksiada_ = ''
        input.value.eK3Kontraksitidakada_ = ''
        input.value.eK3Ya2_ = ''
        input.value.eK3Ya3_ = ''
        input.value.eK3Ya4_ = ''
        input.value.eK3Nilai1_ = ''
        input.value.eK3Nilai2_ = ''
        input.value.eK3Ya5_ = ''
    }
  }
)

watch(
  () => input.value.selectAll3,
  (newValue, oldValue) => {
    if (newValue != false) {
        input.value.dK3KebersihanJalanNafasTidakEfektif_ = 'Kebersihan jalan nafas tidak efektif berhubungan dengan'
        input.value.dK3NyeriAkutKronis_ = 'Nyeri akut/kronis berhubungan dengan'
        input.value.dK3RisikoCedera_ = 'Risiko cedera/kecelakaan berhubungan dengan'
        input.value.dK3RisikoGangguan_ = 'Risiko gangguan keseimbangan cairan elektrolit berhubungan dengan'
        input.value.rK3SiapkanPeralatanResusitasi_ = 'Siapkan peralatan resusitasi'
        input.value.rK3BebaskanJalanNapas_ = 'Bebaskan jalan napas'
        input.value.rK3BerikanOxygenSesuaiKebutuhan_ = 'Berikan oxygen sesuai kebutuhan'
        input.value.rK3BersihkanSekretPadaJalanNapas_ = 'Bersihkan sekret pada jalan napas'
        input.value.rK3KajiSkala_ = 'Kaji skala nyeri'
        input.value.rK3MemberikanPosisi_ = 'Memberikan posisi yang nyaman'
        input.value.rK3AjarkanTeknik_ = 'Ajarkan teknik relaksasi dan distraksi'
        input.value.rK3KolaborasiDenganDokter_ = 'Kolaborasi dengan dokter'
        input.value.rK3KajiResikoJatuh_ = 'Kaji resiko jatuh'
        input.value.rK3LaksakananProtapResikoJatuh_ = 'Laksanakan protap resiko jatuh'
        input.value.rK3PantauEfekPenggunaanObatAnestesi_ = 'Pantau efek penggunaan obat anestesi'
        input.value.rK3ObservasiVitalSign_ = 'Observasi vital sign dan keadaan umum pasien'
        input.value.rK3KolaborasiPemberian_ = 'Kolaborasi pemberian cairan intra vena'
        input.value.rK3ObservasiIntakeOutput_ = 'Observasi intake out put'
        input.value.rK3ObservasiTandaPerdarahan_ = 'Observasi tanda-tanda perdarahan'
    } else{
        input.value.dK3KebersihanJalanNafasTidakEfektif_ = ''
        input.value.dK3NyeriAkutKronis_ = ''
        input.value.dK3RisikoCedera_ = ''
        input.value.dK3RisikoGangguan_ = ''
        input.value.rK3SiapkanPeralatanResusitasi_ = ''
        input.value.rK3BebaskanJalanNapas_ = ''
        input.value.rK3BerikanOxygenSesuaiKebutuhan_ = ''
        input.value.rK3BersihkanSekretPadaJalanNapas_ = ''
        input.value.rK3KajiSkala_ = ''
        input.value.rK3MemberikanPosisi_ = ''
        input.value.rK3AjarkanTeknik_ = ''
        input.value.rK3KolaborasiDenganDokter_ = ''
        input.value.rK3KajiResikoJatuh_ = ''
        input.value.rK3LaksakananProtapResikoJatuh_ = ''
        input.value.rK3PantauEfekPenggunaanObatAnestesi_ = ''
        input.value.rK3ObservasiVitalSign_ = ''
        input.value.rK3KolaborasiPemberian_ = ''
        input.value.rK3ObservasiIntakeOutput_ = ''
        input.value.rK3ObservasiTandaPerdarahan_ = ''
    }
  }
)


watch(
  () => input.value.selectAll1,
  (newValue, oldValue) => {
    if (newValue != false) {
      input.value.rKLaksanakanProtap_ = 'Laksanakan protap interaksi sosial'
      input.value.rKLaksanakanOrientasi_ = 'Laksanakan orientasi pre operasi'
      input.value.rKHEProsedur_ = 'H.E prosedur operasi'
      input.value.rKKolaborasiPemberian_ = 'Kolaborasi pemberian premedikasi'
      input.value.rKMonitorEfek_ = 'Monitor efek pemberian premedikasi'
      input.value.rKKajiSkala_ = 'Kaji skala nyeri'
      input.value.rKMemberikanPosisi_ = 'Memberikan posisi yang nyaman'
      input.value.rKAjarkanTeknik_ = 'Ajarkan teknik relaksasi dan distraksi'
      input.value.rKKolaborasiDokter_ = 'Kolaborasi dokter untuk pemberian obat analgetika'
      input.value.rKCekKelengkapan_ = 'Cek kelengkapan dokumen pre operasi'
      input.value.rKMenyiapkanMesin_ = 'Menyiapkan mesin anestesi'
      input.value.rKMenyiapkanAlatAnestesi_ = 'Menyiapkan alat dan obat anestesi'
      input.value.rKMenyiapkanAlatPembedahan_ = 'Menyiapkan alat dan obat sesuai pembedahan'
      input.value.rKMelakukanSignIn_ = 'Melakukan sign in'
      input.value.rKObservasiVitalSign_ = 'Observasi vital sign dan keadaan umum pasien'
      input.value.rKKolaborasiPemasangan_ = 'Kolaborasi pemasangan cairan intra vena'
      input.value.rKObservasiIntakeOutput_ = 'Observasi intake out put'
    } else {
      input.value.rKLaksanakanProtap_ = undefined
      input.value.rKLaksanakanOrientasi_ = undefined
      input.value.rKHEProsedur_ = undefined
      input.value.rKKolaborasiPemberian_ = undefined
      input.value.rKMonitorEfek_ = undefined
      input.value.rKKajiSkala_ = undefined
      input.value.rKMemberikanPosisi_ = undefined
      input.value.rKAjarkanTeknik_ = undefined
      input.value.rKKolaborasiDokter_ = undefined
      input.value.rKCekKelengkapan_ = undefined
      input.value.rKMenyiapkanMesin_ = undefined
      input.value.rKMenyiapkanAlatAnestesi_ = undefined
      input.value.rKMenyiapkanAlatPembedahan_ = undefined
      input.value.rKMelakukanSignIn_ = undefined
      input.value.rKObservasiVitalSign_ = undefined
      input.value.rKKolaborasiPemasangan_ = undefined
      input.value.rKObservasiIntakeOutput_ = undefined
    }
  }
)

watch(
  () => input.value.selectAll2,
  (newValue, oldValue) => {
    if (newValue != false) {
      input.value.dK2KebersihanJalanNapasTidakEfektifBerhubunganDengan_ = 'Kebersihan jalan napas tidak efektif berhubungan dengan :'
      input.value.dK2RisikoHypothermy_ = 'Risiko hypothermy berhubungan dengan :'
      input.value.dK2RisikoGangguan_ = 'Risiko gangguan integritas kulit berhubungan dengan :'
      input.value.dK2RisikoInjury_ = 'Risiko injury berhubungan dengan :'
      input.value.dK2RisikoKurang_ = 'Risiko kurang volume cairan berhubungan dengan :'
      input.value.dK2RisikoInfeksi_ = 'Risiko infeksi berhubungan :'
      input.value.rK2SiapkanPeralatan_ = 'Siapkan peralatan resusitasi'
      input.value.rK2BebaskanJalanNapas_ = 'Bebaskan jalan napas'
      input.value.rK2BerikanOxygen_ = 'Berikan oxygen sesuai kebutuhan'
      input.value.rK2ObservasiPemasangan_ = 'Observasi pemasangan packing tenggorokan'
      input.value.rK2SesuaikanSuhuKamar_ = 'Sesuaikan suhu kamar operasi dengan kondisi pasien'
      input.value.rK2BerikanSelimutHangat_ = 'Berikan selimut hangat pada pasien'
      input.value.rK2ObservasiVitalSign_ = 'Observasi vital sign'
      input.value.rK2GunakanPencucianLuka_ = 'Gunakan pencucian luka dengan cairan hangat'
      input.value.rK2PosisikanPasienPembedahan_ = 'Posisikan pasien dengan tepat sesuai kebutuhan pembedahan'
      input.value.rK2PasangPengelasLembut_ = 'Pasang pengalas lembut didaerah kulit yang tertekan'
      input.value.rK2LakukanPengikatan_ = 'Lakukan pengikatan, perhatikan risiko kerusakan kulit & saraf'
      input.value.rK2MonitorKeutuhanKulit_ = 'Monitor keutuhan kulit yang tertekan'
      input.value.rK2PeriksaKesiapanDiatermiPlat_ = 'Periksa kesiapan diatermi plat'
      input.value.rK2TempatkanPlatDiatermi_ = 'Tempatkan plat diatermi di tempat yang berotot dan kering'
      input.value.rK2EvaluasiTempatPlatDiatermiPascaOperasi_ = 'Evaluasi tempat plat diatermi pasca operasi'
      input.value.rK2LakukanPerhitunganIntraOperatif_ = 'Lakukan penghitungan intra-operatif'
      input.value.rK2LakukanTimeOutSignOut_ = 'Lakukan time out - sign out'
      input.value.rK2MonitorPemasanganTorniquet_ = 'Monitor pemasangan torniquet'
      input.value.rK2ObservasiIntakeOutput_ = 'Observasi intake dan output'
      input.value.rK2CatatJumlahPerdarahan_ = 'Catat jumlah perdarahan'
      input.value.rK2LakukanGeneralPrecaution_ = 'Lakukan general precaution'
      input.value.rK2SiapkanAlatOperasiSteril_ = 'Siapkan alat operasi secara steril'
      input.value.rK2LakukanDesinfeksiAreaOperasi_ = 'Lakukan desinfeksi area operasi'
      input.value.rK2KolaborasiPemberianAntibiotik_ = 'Kolaborasi pemberian antibiotik'
      input.value.rK2LakukanPenutupanLapanganOperasiSteril_ = 'Lakukan penutupan lapangan operasi dengan steril'
    } else {
      input.value.dK2KebersihanJalanNapasTidakEfektifBerhubunganDengan_ = ''
      input.value.dK2RisikoHypothermy_ = ''
      input.value.dK2RisikoGangguan_ = ''
      input.value.dK2RisikoInjury_ = ''
      input.value.dK2RisikoKurang_ = ''
      input.value.dK2RisikoInfeksi_ = ''
      input.value.rK2SiapkanPeralatan_ = ''
      input.value.rK2BebaskanJalanNapas_ = ''
      input.value.rK2BerikanOxygen_ = ''
      input.value.rK2ObservasiPemasangan_ = ''
      input.value.rK2SesuaikanSuhuKamar_ = ''
      input.value.rK2BerikanSelimutHangat_ = ''
      input.value.rK2ObservasiVitalSign_ = ''
      input.value.rK2GunakanPencucianLuka_ = ''
      input.value.rK2PosisikanPasienPembedahan_ = ''
      input.value.rK2PasangPengelasLembut_ = ''
      input.value.rK2LakukanPengikatan_ = ''
      input.value.rK2MonitorKeutuhanKulit_ = ''
      input.value.rK2PeriksaKesiapanDiatermiPlat_ = ''
      input.value.rK2TempatkanPlatDiatermi_ = ''
      input.value.rK2EvaluasiTempatPlatDiatermiPascaOperasi_ = ''
      input.value.rK2LakukanPerhitunganIntraOperatif_ = ''
      input.value.rK2LakukanTimeOutSignOut_ = ''
      input.value.rK2MonitorPemasanganTorniquet_ = ''
      input.value.rK2ObservasiIntakeOutput_ = ''
      input.value.rK2CatatJumlahPerdarahan_ = ''
      input.value.rK2LakukanGeneralPrecaution_ = ''
      input.value.rK2SiapkanAlatOperasiSteril_ = ''
      input.value.rK2LakukanDesinfeksiAreaOperasi_ = ''
      input.value.rK2KolaborasiPemberianAntibiotik_ = ''
      input.value.rK2LakukanPenutupanLapanganOperasiSteril_ = ''
    }
  }
)

watch(
  () => input.value.rKLaksanakanProtap_,
  (newValue, oldValue) => {
    console.log('CHECKBOX 1', newValue)
    if (newValue == true) {

    }
  }
)

watch(
  input.value.details,
  (newValue, oldValue) => {
    for (let index = 0; index < input.value.details.length; index++) {
      const element = input.value.details[index];
      console.log("erlement", element)

      let totalTambah = 0;
      let totalTambah2 = 0;
      let totalTambahAkhir = 0
      let awal = element.iOPenghitunganAwal != undefined ? parseInt(element.iOPenghitunganAwal) : undefined
      let pertama = element.iOPenambahanItem1 != undefined ? parseInt(element.iOPenambahanItem1) : undefined
      let kedua = element.iOPenambahanItem2 != undefined ? parseInt(element.iOPenambahanItem2) : undefined
      let ketiga = element.iOPenambahanItem3 != undefined ? parseInt(element.iOPenambahanItem3) : undefined
      let keempat = element.iOPenambahanItem4 != undefined ? parseInt(element.iOPenambahanItem4) : undefined

      let pertama2 = element.iOPenambahanKedua1 != undefined ? parseInt(element.iOPenambahanKedua1) : undefined
      let kedua2 = element.iOPenambahanKedua2 != undefined ? parseInt(element.iOPenambahanKedua2) : undefined
      let ketiga2 = element.iOPenambahanKedua3 != undefined ? parseInt(element.iOPenambahanKedua3) : undefined
      let keempat2 = element.iOPenambahanKedua4 != undefined ? parseInt(element.iOPenambahanKedua4) : undefined
//       iOPenambahanKedua1
// : 
// "5"
// iOPenambahanKedua2
// : 
// "5"
// iOPenambahanKedua3
// : 
// "5"
// iOPenambahanKedua4
// : 
// "5"

// iOTotalTambahandua
// : 
// "5"
      if(pertama != undefined && kedua != undefined & ketiga != undefined && keempat != undefined) {
        totalTambah = totalTambah + pertama + kedua + ketiga + keempat
        input.value.details[index].iOTotalTambahan = totalTambah
        if(awal != undefined) {
          input.value.details[index].iOPenghitunganPertama = (totalTambah) + awal;
        }
      }
      if(pertama2 != undefined && kedua2 != undefined & ketiga2 != undefined && keempat2 != undefined) {
        totalTambah2 = totalTambah2 + pertama2 + kedua2 + ketiga2 + keempat2
        input.value.details[index].iOTotalTambahandua = totalTambah
      }

      if(input.value.details[index].iOPenghitunganPertama != undefined && input.value.details[index].iOTotalTambahandua != undefined) {
        input.value.details[index].iOPenghitunganAkhir = parseInt(input.value.details[index].iOPenghitunganPertama) + parseInt(input.value.details[index].iOTotalTambahandua);
      }
    }
    console.log("ubah input value details", newValue, oldValue)
  }
)



const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.dokterRawat = props.registrasi.dokter
}
setView()
setAutoFill()
fetchJenisFile()

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name;
    let indexTabs = route.params.index_tabs;
    let cacheKey = `TAB~${props.registrasi.noregistrasi}~${rouutename}~${indexTabs}`;

    if (to.name !== 'profile-pasien') {
      H.cacheEMR().set(cacheKey, input.value);
      console.log(`Cache disimpan untuk ${cacheKey}`);
    }

    if (to.name === 'profile-pasien') {
      H.cacheEMR().remove(cacheKey);
      console.log(`Cache dihapus karena berpindah ke profile-pasien: ${cacheKey}`);
    }

  } catch (error) {
    console.error('Error saat menyimpan/menghapus cache:', error);
  }
  next();
});
</script>

<style lang="scss"></style>
