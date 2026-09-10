<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun" isHideCetak isHideST></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="columns is-multiline">

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-5 mt-4">
                    Assesmen awal / ulang tanggal :
                  </div>
                  <div class="column is-5 mt-2">
                    <VDatePicker v-model="input.tanggalAssesmen" mode="datetime" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-2 mt-4">
                    WIB
                  </div>
                </div>
              </div>
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-2 mt-4">
                    Ruangan :
                  </div>
                  <div class="column is-6">
                    <VControl class="prime-auto">
                        <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan"
                            @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            class="mt-2" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="1. Gejala seperti mau muntah dan kesulitan bernafas" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table1">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField addons>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="2. Faktor yang meningkatkan dan membangkitkan gejala fisik" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table2">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="3. Manajemen gejala saat ini dan respon pasien" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table3">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="4. Orientasi spiritual pasien dan keluarga " :toggleable="true">
              <div class="column is-12" v-for="(datas) in table4">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="5. Urusan dan kebutuhan spiritual pasien dan keluarga seperti putus asa, penderitaan, rasa bersalah, atau pengampunan" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table5">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="6. Status psikososial pasien dan keluarga" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table6">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBoxTTD'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column3}`">
                              <TandaTangan :elemenID="data.ttd" :width="'150'" :height="'150'" class="dek" />
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="7. Kebutuhan dukungan atau kelonggaran pelayanan bagi pasien, keluarga, dan pemberi pelayanan lain" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table7">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBoxTTD'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column3}`">
                              <TandaTangan :elemenID="data.ttd" :width="'150'" :height="'150'" class="dek" />
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="8. Apakah ada kebutuhan akan alternatif atau tingkat pelayanan lain" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table8">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBoxTTD'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column3}`">
                              <TandaTangan :elemenID="data.ttd" :width="'150'" :height="'150'" class="dek" />
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="9. Faktor resiko bagi keluarga yang ditinggalkan" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table9">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBoxTTD'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column3}`">
                              <TandaTangan :elemenID="data.ttd" :width="'150'" :height="'150'" class="dek" />
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="Masalah keperawatan" :toggleable="true">
              <div class="column is-12" v-for="(datas) in table10">
                <h1 class="emr">{{ datas.title }}</h1>
                <div class="columns is-multiline">
                    <div class="column" :class="`is-${data.column}`" v-for="(data) in datas.value">
                        <VField v-if="data.type == 'textarea'">
                            <VControl>
                                <VTextarea v-model="input[data.model]" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                        <VField v-if="data.type == 'checkbox'">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                    :label="data.subTitle" class="pb-0" color="primary" square />
                            </VControl>
                        </VField>
                        <VField v-else-if="data.type == 'label'">
                          <div v-html="data.label" class="mt-2">
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'kosong'">
                        </VField>
                        <VField v-else-if="data.type == 'dateTime'">
                          <VDatePicker v-model="input[data.model]" mode="datetime" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                              </template>
                          </VDatePicker>
                        </VField>
                        <VField v-if="data.type == 'checkboxText'">
                          <div class="columns is-multiline">
                            <div class="column" :class="`is-${data.column1}`">
                              <VControl raw subcontrol>
                                  <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                      :label="data.subTitle" class="pb-0" color="primary" square />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VField>
                                  <VControl>
                                      <VInput type="text" class="input" v-model="input[data.model2]" />
                                  </VControl>
                              </VField>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBox'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                          </div>
                        </VField>
                        <VField v-else-if="data.type == 'textBoxTTD'">
                          <div class="columns is-multiline">
                            <div class="column mt-2" :class="`is-${data.column1}`">
                              {{ data.subTitle }}
                            </div>
                            <div class="column" :class="`is-${data.column2}`">
                              <VControl raw subcontrol>
                                  <input v-model="input[data.model]" class="input p-0" />
                              </VControl>
                            </div>
                            <div class="column" :class="`is-${data.column3}`">
                              <TandaTangan :elemenID="data.ttd" :width="'150'" :height="'150'" class="dek" />
                            </div>
                          </div>
                        </VField>
                    </div>
                </div>    
              </div>

              <div class="column is-12">
                <hr>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    Rencana Kerja Dokter
                  </div>
                  
                  <div class="column is-12">
                    <div class="column is-12">
                      <div style="overflow-y:auto;" class="mt-1">
                        <table class="table is-fullwidth is-bordered" border="1" style="width: 100%;">
                          <thead>
                            <tr>
                              <th width="5%" style="vertical-align: inherit;text-align:center">
                                No.
                              </th>
                              <th width="20%" style="vertical-align: inherit;text-align:center">
                                Masalah
                              </th>
                              <th width="25%" style="vertical-align: inherit;text-align:center">
                                Rencana Intervensi
                              </th>
                              <th width="35%" style="vertical-align: inherit;text-align:center">
                                Target (Waktu dan Kondisi Yang Diharapkan)
                              </th>
                              <th width="15%" style="vertical-align: inherit;text-align:center">
                                Persetujuan Keluarga
                              </th>
                              <th style="vertical-align: inherit;text-align:center;" width="5%">
                                #
                              </th>
                            </tr>
                          </thead>
                          <tbody v-for="(input, index) in input.details" :key="index">
                            <tr>
                              <td class="td-po">
                                <div class="pb-0">
                                  {{ index + 1 }}
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <VTextarea rows="2" v-model="input.daftarMasalah" placeholder="Masalah"
                                        :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <!-- <VControl icon="feather:bookmark"> -->
                                      <!-- <VInput type="text" v-model="input.rencanaIntervensi" placeholder="Rencana Intervensi" /> -->
                                      <VTextarea rows="2" v-model="input.rencanaIntervensi" placeholder="Rencana Intervensi"
                                        :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <!-- <VControl icon="feather:bookmark"> -->
                                      <!-- <VInput type="text" v-model="input.target" placeholder="Target" /> -->
                                      <VTextarea rows="2" v-model="input.target" placeholder="Target" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-po">
                                <div class="pb-0">
                                  <VField>
                                    <VControl>
                                      <!-- <VControl icon="feather:bookmark"> -->
                                      <!-- <VInput type="text" v-model="input.target" placeholder="Target" /> -->
                                      <VTextarea rows="2" v-model="input.persetujuanKeluarga" placeholder="Persetujuan Keluarga" :disabled="paramRiwayat">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>
                              </td>
                              <td class="td-rpo" style="vertical-align: inherit">
                                <VButtons style="justify-content:space-around">
                                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                    color="info" v-tooltip.bubble="'Tambah '">
                                  </VIconButton>
                                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                                    @click="removeItem(index)" color="danger">
                                  </VIconButton>
                                </VButtons>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div class="column is-12">
                <hr>
              </div>
            </Fieldset>
          </div>
          
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-12 has-text-centered">
                    Tanda tangan dan nama keluarga,
                  </div>
                  <div class="column is-12 has-text-centered">
                    <TandaTangan :elemenID="'KeluargaTTD'" :width="'150'" :height="'150'" class="dek" />
                  </div>
                  <div class="column is-12 has-text-centered">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.keluargaText" />
                    </VControl>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-12 has-text-centered">
                    Tanda tangan dan perawat pengkaji,
                  </div>
                  <div class="column is-12 has-text-centered">
                    <TandaTangan :elemenID="'perawatPengkajiTTD'" :width="'150'" :height="'150'" class="dek" />
                  </div>
                  <div class="column is-12 has-text-centered">
                    <VControl class="prime-auto">
                        <AutoComplete v-model="input.perawatPengkaji" :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            class="mt-2" />
                    </VControl>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-12 has-text-centered">
                    Tanda tangan dan nama dokter pengkaji <br> (DPJP),
                  </div>
                  <div class="column is-12 has-text-centered pt-0">
                    <TandaTangan :elemenID="'dokterDPJPTTD'" :width="'150'" :height="'150'" class="dek" />
                  </div>
                  <div class="column is-12 has-text-centered pt-0">
                    <VControl class="prime-auto">
                        <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Dokter"
                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            class="mt-2" />
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


  

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-pasien-tahap-terminal'

const d_Hubungan: any = ref([])


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let table1 = ref(EMR.table1())
let table2 = ref(EMR.table2())
let table3 = ref(EMR.table3())
let table4 = ref(EMR.table4())
let table5 = ref(EMR.table5())
let table6 = ref(EMR.table6())
let table7 = ref(EMR.table7())
let table8 = ref(EMR.table8())
let table9 = ref(EMR.table9())
let table10 = ref(EMR.table10())

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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_Perawat: any = ref([])
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('AsesmenPasienTahapTerminal') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tglKontrol: new Date(),
  details: [{
    no: 1,
  }],
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length > 0) {
    input.value = response[0] //set ke inputan
    if (response[0].KeluargaTTD) {
      H.tandaTangan().set("KeluargaTTD", response[0].KeluargaTTD)
    }
    if (response[0].perawatPengkajiTTD) {
      H.tandaTangan().set("perawatPengkajiTTD", response[0].perawatPengkajiTTD)
    }
    if (response[0].dokterDPJPTTD) {
      H.tandaTangan().set("dokterDPJPTTD", response[0].dokterDPJPTTD)
    }
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    } else { }
  }
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.KeluargaTTD = H.tandaTangan().get("KeluargaTTD")
  object.perawatPengkajiTTD = H.tandaTangan().get("perawatPengkajiTTD")
  object.dokterDPJPTTD = H.tandaTangan().get("dokterDPJPTTD")
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
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}


const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`);
  d_Ruangan.value = response;
};

const fetchPenangungJawab = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Hubungan.value = response
  })
}

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const setTandaTangan = async (e: any, idTTD: any) => {
  let response = await useApi().get(`/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set(idTTD, response.ttd)
  } else {
    H.tandaTangan().set(idTTD, '')
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

const setAutoFill = async () => {
  input.value.ruangan = props.registrasi.namaruangan 
  input.value.tanggalAssesmen = new Date() 
}

setAutoFill()
setView()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

h1 {
  font-weight: bold;
}

.none {
  display: none;
}

// .canvaCust {
//   position: relative;
//   left: -10px;
//   top: -5px;
// }
</style>
