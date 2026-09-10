<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="column">
    <div class="columns is-multiline p-5">
      <div class="column is-12">
        <VCard>
          <Fieldset>
            <div class="columns is-multiline">
              <div class="column is-8" style="overflow-x: scroll; width: 850px;">
                <table border="1" class="triase">
                  <thead>
                    <tr style="text-align: center;">
                      <th style="width: 100px;">Jam</th>
                      <th style="width: 100px;">TD (mmHg)</th>
                      <th style="width: 100px;">Nadi (x/mnt)</th>
                      <th style="width: 130px;">Ritme</th>
                      <th style="width: 100px;">Resep (x/mnt)</th>
                      <th style="width: 130px;">Suhu (°C)</th>
                      <th style="width: 130px;">Sat O<sub>2</sub></th>
                      <th style="width: 130px;">GCS (Ex Vx Mx)</th>
                      <th style="width: 100px;">Ukuran Pupil (mm)</th>
                      <th style="width: 100px;">Reaksi Pupil</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.tekananDarah" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.R" placeholder="R..." />
                          </VControl>
                        </VField>

                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.IRR" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.Resep" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhu" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.Sat" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.GCS" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.Ka1212" placeholder="Ka..." />
                          </VControl>
                        </VField>

                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.Ki1212" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.Ka" placeholder="Ka..." />
                          </VControl>
                        </VField>

                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.Ki" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a1" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a2" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a3" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a4" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a5" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a7" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a6" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a8" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a9" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a10" placeholder="Ka..." />
                          </VControl>
                        </VField>

                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a11" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a12" placeholder="Ka..." />
                          </VControl>
                        </VField>

                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a13" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a14" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a15" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a16" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a17" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a18" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a20" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a19" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a21" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a22" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a23" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a24" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a25" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a26" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a27" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a28" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a29" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a30" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a31" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a33" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a32" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a34" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a35" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a36" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a37" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a38" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a39" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a40" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a41" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a42" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a43" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a44" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a46" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a45" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a47" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a48" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a49" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a50" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a51" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a52" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a53" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a54" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a55" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a56" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a57" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a59" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a58" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a60" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a61" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a62" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a63" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a64" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a65" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a66" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a67" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a68" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a69" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a70" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a72" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a71" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a73" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a74" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a75" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a76" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a77" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a78" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a79" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a80" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a81" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a82" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a83" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a85" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a84" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a86" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a87" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a88" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a89" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a90" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a91" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a92" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a93" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a94" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a95" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a96" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a98" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a97" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a99" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a100" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a101" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a102" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a103" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a104" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <VField style="width: 140px">
                          <VDatePicker v-model="input.a105" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="TD" v-model="input.a106" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHG</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi" v-model="input.a107" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="R :">
                          <VControl>
                            <VInput type="text" v-model="input.a108" placeholder="R..." />
                          </VControl>
                        </VField>
                        <VField label="IRR :">
                          <VControl>
                            <VInput type="text" v-model="input.a109" placeholder="IRR..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Resep" v-model="input.a111" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu" v-model="input.a110" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td>
                        <VField label="Sat O2">
                          <VControl>
                            <VInput type="text" v-model="input.a112" placeholder="Sat..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField addons style="width: 140px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="GCS" v-model="input.a113" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Ex Vx Mx</VButton>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a114" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a115" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField label="Kanan :">
                          <VControl>
                            <VInput type="text" v-model="input.a116" placeholder="Ka..." />
                          </VControl>
                        </VField>
                        <VField label="Kiri :">
                          <VControl>
                            <VInput type="text" v-model="input.a117" placeholder="Ki..." />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <VField label="Catatan :" style="margin-top: 20px; margin-bottom: 20px;">
                  <VControl>
                    <VTextarea v-model="input.catatan" rows="6">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>
        </VCard>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

useHead({
  title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const pasien: any = ref({})
const d_dokter: any = ref({})
const isLoadingPasien: any = ref(false)
const items = ref([]);
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

const COLLECTION: any = ref('MonitoringPasienDalamAmbulance') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  waktu: new Date,
})
const value = ref("");
const d_Diagnosa: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)


// ==================== End List Data ==================

// const fetchDokter = async (filter: any) => {
//   const response = await useApi().get(
//       `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
//   d_dokter.value = response
// }

const loadRiwayat = async () => {

  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan 
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const simpan = () => {

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
  // console.log(json)

  // // isLoading.value = true
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
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const dokterDPJP = async () => {
  await useApi().get(`emr/get-dokter-dpjp?nocmfk=${ID_PASIEN}`).then((response) => {
    input.value.dokter = response.dokter
  })
}
const diagnosa = async () => {
  await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
    input.value.namadiagnosa = response[0].namadiagnosa
  })
}

diagnosa()
dokterDPJP()
fetchPasien()
loadRiwayat()

watch(() => [
  input.value.kesadaranE,
  input.value.kesadaranM,
  input.value.kesadaranV,
  input.value.totalKesadaran,
  // input.value.point2
], () => {
  let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
  let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
  let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0

  const jumlahNilai = poin1 + poin2 + poin3
  input.value.totalKesadaran = jumlahNilai
})

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

.checkbox.is-outlined {
  padding: unset !important;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

h1.emr {
  font-weight: bold;
}

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
td {
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
</style>
