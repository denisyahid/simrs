<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Kriteria Masuk Ruangan Perinatologi Nicu</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" isHideCetak></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

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

                <div class="column is-12" style="margin-top: 30px;">
                    <div class="columns is-multiline">

                      <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                        <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
                          @click="pilihTemplateFix(index)"> Pilih Template
                        </VButton>
                      </div>

                        <div class="column is-12">
                            <h1 class="mb-3 emr">Nama Template&emsp;&emsp;**Hanya diisi jika ingin membuat template</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.namatemplate" rows="1">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1 class="mb-3" style="font-weight: bold;">Tanggal Lahir</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 class="mb-3" style="font-weight: bold;">Jam </h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebjamAsesmenAwal" mode="time" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Jam"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <br>
                <hr><br>
                  <div class="container">
                  <table class="table is-bordered is-fullwidth">
                      <thead>
                          <tr>
                              <th>KRITERIA (CRITERIAS)</th>
                              <th>YA (✓)</th>
                              <th>TIDAK (✓)</th>
                          </tr>
                      </thead>

                      <thead>
                         <tr>
                           <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">1. Tanda Vital (Vital Sign)
                        </th>
                      </tr>
                      <tr>
                      <th>a. Denyut jantung 120 -> 140x/mnt<br><i>Heart rate 120 -> 140 beats/minute</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitalA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitalA" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>b. Nadi arteri radialis teraba lembutatau tidak teraba <br><i>Inadequate or unpalpable arteri radialis pulse</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitalB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitalB" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                      <th>c. Waktu pengisian kapiler > 3 detik <br><i>Capillary refill time > 3 second</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitalC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitalC" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                    <th>d. Suhu axilla 36,5 °C -> 37,5 °C<br><i>Axillaries temperature 36,5 °C -> 37,5 °C</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitalD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitalD" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                    <tr>
                    <th>e. Laju respirasi 40 0> 60x/mnt<br><i>respiration rate 40 -> 60 beats/minute</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.vitalE" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.vitalE" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                      </thead>
                      <thead>
                        <tr>
                           <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">2. Pemeriksaan Fisik (Physical Examinations)
                            </th>
                        </tr>
                      <tr>
                      <th>a. Neonatus dengan aktivitas, tonus, reflex, dan tangis lemah<br><i>Inadequate activities, tonus, reflexes, and cries</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fisikA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fisikA" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>b. Pupil anisokor <br><i>Anisocor pupils</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fisikB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fisikB" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>c. Kejang <br><i>Seizures</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fisikC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fisikC" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>d. Obstruksi saluran nafas<br><i>Airway abstruction</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fisikD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fisikD" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>e. Sianosis<br><i>Cyanotic</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fisikE" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fisikE" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>f. Gagal Jantung<br><i>Cardiac failure</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fisikF" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fisikF" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>g. Anuria<br><i>Anuria</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fisikG" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fisikG" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>h. kelainan kongenital mayor<br><i>Major congenital anomaly</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.fisikH" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.fisikH" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>

                      <thead>
                        <tr>
                           <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">3. Nilai Laboratorium (Laboratory Findings)
                            </th>
                        </tr>
                      <tr>
                      <th>a. Kadar hemoglobin &lt; 6g/DI<br><i>Hemoglobin level &lt; 6g/DI</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labA" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>b. Kadar hematokrit > 65%<br><i>Hematocrit level > 65%</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labB" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>c. Kadar leukosit > 50.000/ml<br><i>Leucocyte > 50.00/ml</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labC" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>d. pH &lt; 7,2 atau > 7,7<br><i>pH &lt; 7,2 or > 7,7</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labD" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>e. PaO2 &lt; 80mmHg<br><i>PaO2 &lt;80mmHg</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labE" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labE" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>f. PaCO2 > 50mmHg<br><i>PaCO2 > 50mmHg</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labF" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labF" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>g. HCO3 &lt; 15<br><i>HCO3 &lt; 15</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labG" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labG" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>h. Saturasi oksigen perifer (SpO2) &lt;80%<br><i>Peripheral oxygen saturation (SpO2) &lt;80%</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labH" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labH" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>i. Kadar glukosa serum &lt;45mg/dL atau > 150mg/dL<br><i>Serum glucose &lt;45mg/dL or 150mg/dL</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labI" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labI" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>j. Kadar natrium serum &lt;110 mEq/L atau > 160 mEq/L<br><i>Levels of serum sodium &lt;110 mEq/L or > 160 mEq/L</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labJ" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labJ" true-value="Tidak" color="primary" />
                      </th>
                    </tr>


                    <tr>
                    <th>k. Kadar kalium serum &lt;2,0 mEq/L atau > 6,0 mEq/L<br><i>Levels of serum Potassium &lt;2,0 mEq/L or > 6,0 mEq/L</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labK" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labK" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>l. Kadar kalsium serum &lt;6 mg/dL atau > 15 mg/dL<br><i>Serum calcium &lt;6 mg/dL or > 15mg/dL</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.labL" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.labL" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>
                      <thead>
                        <tr>
                           <th style="background-color: #0000CD; color: #FFFFFF" colspan="3">4. Pemeriksaan Radiologi (Radiology Findings)
                            </th>
                        </tr>
                      <tr>
                      <th>a. Pendarahan intracerebral intraventrikular atau pendarahan subarachnoid<br><i>Cerebral hemorrhage, intraventricular or subarachnoid hemorrhage</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.radA" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.radA" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>b. Hypoxic ischemic encephalophaty (HIE)<br><i>Hypoxic ischemic encephalophaty (HIE)</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.radB" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.radB" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                      <th>c. Pneumonia <br><i>Pneumonia</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.radC" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.radC" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>d. Penyakit membran hyalin<br><i>Hyaline membrane disease</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.radD" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.radD" true-value="Tidak" color="primary" />
                      </th>
                    </tr>

                    <tr>
                    <th>e. Fraktur ostia<br><i>Bone fracture</i></th>
                      <th class="yes-column">
                        <VCheckbox v-model="input.radE" true-value="Ya" color="primary" />
                      </th>
                      <th class="no-column">
                        <VCheckbox v-model="input.radE" true-value="Tidak" color="primary" />
                      </th>
                    </tr>
                      </thead>

                  </table>
                   <table class="table is-bordered is-fullwidth mt-4">
                      <thead>
                        <tr>
                          <th class="grey-background" style="font-size: 19px">Kesimpulan</th>
                          <th class="yes-column grey-background"></th>
                        </tr>
                      </thead>
                      <tr>
                        <td colspan="2">
                          <VField>
                             <b style="font-size: 17px">Berdasarkan kondisi diatas, maka pasien ini memenuhi indikasi untuk dirawat di ruang perawatan perinatologi level. <br>
                            <i>Under the condition above, this patient indicated to treat in the perinatology ward level : </i></b>

                          </VField>
                        </td>
                      </tr>
                      <tr>
                  <th>Alat transportasi yang digunakan :<br> <i>Transport equipments needed </i> </th>
                </tr>
                <tr>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.incubator" true-value="Ya" color="primary" />
                      <span>Inkubator /<i> Incubator </i></span>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.boxbayi" true-value="Tidak" color="primary" />
                      <span>Box bayi / <i>Baby box</i></span>
                    </div>
                  </th>
                </tr>

                <tr>
                  <th>Pendamping selama transfer : <br> <i>Transfer Escort </i></th>
                </tr>
                <tr>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.docter" true-value="Ya" color="primary" />
                      <span>Dokter / <i>Doctor</i></span>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.paramedic" true-value="Tidak" color="primary" />
                      <span>Paramedis / <i>Paramedic </i></span>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.caregiver" true-value="Tidak" color="primary" />
                      <span>Care Giver/POS </span>
                    </div>
                  </th>
                </tr>

               <tr>
                  <th>Alat medis yang dibawa selama transfer : <br> <i>Medical equipments needed while transfer</i> </th>
                </tr>
                <tr>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.hooh" true-value="Ya" color="primary" />
                      <span>YA,</span>
                      <VField>
                        <VTextarea v-model="input.textAreaValue" placeholder="Sebutkan" color="primary" />
                      </VField>
                    </div>
                  </th>
                  <th>
                    <div class="checkbox-container">
                      <VCheckbox v-model="input.ora" true-value="Tidak" color="primary" />
                      <span>TIDAK</span>
                    </div>
                  </th>
                </tr>

                    </table>
              </div>

                <br>
                <hr><br>
                <div class="columns">
                    <div class="column is-8"></div>
                    <div class="column is-4">
                        <VField label="Garut">
                            <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                        <div class="column" style="text-align:center;">
                            <h1>Tanda Tangan</h1>
                            <!-- <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" /> -->
                            <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (input.CBBidan ? input.CBBidan.label : '-')"><br>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.CBBidan" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="20%">Section</td>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
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
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal> -->

    <!-- <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="showModalTemplateFix = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Template</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="15%">No</td>
                                    <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                                    <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                                    <td class="tg-0lax text-center" width="50%">Nama Template</td>
                                    <td class="tg-0lax text-center" width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
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
                                    <td style="width:15%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal> -->
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
  import Fieldset from 'primevue/fieldset';
  import { FilterMatchMode } from 'primevue/api';
  import InputText from 'primevue/inputtext';
  import Column from 'primevue/column'
  import DataTable from 'primevue/datatable'
  import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'

  const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREC_PD = useRoute().query.norec_pd as string
  let norec_emr = useRoute().query.norec_emr as string
  const listTemplateFix: any = ref([])
  const showModalTemplateFix: any = ref(false)

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
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const d_Obat: any = ref([])
  const d_Pegawai: any = ref([])
  const d_Dokter: any = ref([])
  const dataTTD: any = ref([])
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
  const input: any = ref({
    kebjamAsesmenAwal: new Date(),
    DTttd: new Date(),
  })
  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }
  const setAutoFill = async () => {
  input.value.kebtanggalKedatangan = props.pasien.tgllahir
  input.value.kebjamAsesmenAwal = props.pasien.tgllahir
}
  const loadRiwayat = () => {
    isLoading.value = true
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          input.value = response[0] //set ke inputan
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
          isLoading.value = false
          dataTTD.value = response[0];
          H.tandaTangan().set("TTDBidan", dataTTD.value.TTDBidan);
        } else {
          isLoading.value = false
          setAutoFill();
        }
      })
  }

  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    delete input.value.namatemplate
    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDBidan'] = H.tandaTangan().get("TTDBidan");
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

  const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=true&nocmfk=${ID_PASIEN}`).then((responselast: any) => {
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

  const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate','kebtanggalKedatangan','kebjamAsesmenAwal']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
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
  setView()
  loadRiwayat()
  </script>

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
  </style>
