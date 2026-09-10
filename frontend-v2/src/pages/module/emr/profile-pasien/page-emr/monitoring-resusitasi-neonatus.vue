<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Monitoring Resusitasi Neonatus</h3>
            </div>
            <div class="right is-flex">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate()" @kembaliKeun="kembaliKeun" :isHideCetak="true">
              </ButtonEmr>
            </div>
          </div>
          <!-- <VButton type="button" rounded outlined color="info" raised icon="feather:save" @click="pilihTemplate()" :loading="isLoading"> Riwayat</VButton> -->
        </div>
        <div class="column is-12 mb-0">
          <div class="columns is-mobile is-centered">
            <div class="column is-8">
              <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
                  membuat
                  template</span></h1>
              <VField>
                <VControl>
                  <VInput v-model="input.namatemplate">
                  </VInput>
                </VControl>
              </VField>
            </div>
            <div class="column is-auto mt-5" style="display: flex; gap: 5px;"> <!-- Flexbox with gap -->
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
                @click="pilihTemplateFix(index)">
                Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :loading="isLoading"
                @click="pilihTemplate(index)">
                Pilih Riwayat
              </VButton>
            </div>
          </div>
        </div>
        <!-- <pre>{{ props.pasien }}</pre> -->

        <div class="column is-12 pb-0">
          <h1 style="font-weight: bold;">I. Identitas Pasien</h1>
        </div>
        <hr style="border-top:dashed red;background-color:white; margin: 0.5rem;" class="mb-1 mt-0 pt-0">

        <div class="column is-12 is-multiline columns pt-0">
          <div class="column is-2">
            <h1 class="mb-3" style="font-weight: bold;">Nama Pasien:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" disabled class="input" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12 is-multiline columns pt-0">
          <div class="column is-2">
            <h1 class="mb-3" style="font-weight: bold;">No. RM:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.norm" disabled class="input" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12 is-multiline columns pt-0">
          <div class="column is-2">
            <h1 class="mb-3" style="font-weight: bold;">Tanggal Lahir:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.tanggalLahir" disabled class="input" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12 is-multiline columns pt-0">
          <div class="column is-2">
            <h1 class="mb-3" style="font-weight: bold;">Jenis Kelamin:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.jenisKelamin" disabled class="input" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12 is-multiline columns pt-0">
          <div class="column is-2">
            <h1 class="mb-3" style="font-weight: bold;">Nama Ibu:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaIbu" class="input" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12 is-multiline columns pt-0">
          <div class="column is-2">
            <h1 class="mb-3" style="font-weight: bold;">Jam Lahir Bayi:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.jamLahirBayi" color="green" mode="time" is24hr>
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
        </div>
        <div class="column is-12 is-multiline columns">
          <div class="column is-2">
            <h1 class="mb-3" style="font-weight: bold;">Keadaan bayi awal:</h1>
          </div>
          <div class="column is-10 is-multiline columns">
            <div v-for="(item, index) in keadaanBayi" :key="index">
              <VField>
                <VControl>
                  <VCheckbox v-model="input[item.model]" :true-value="item.value" :label="item.label" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 is-multiline columns">
          <div class="column is-2">
            <h1 class="mb-3" style="font-weight: bold;">A-S saat lahir:</h1>
          </div>
          <div class="column is-10 is-multiline">
            <VField>
              <VControl>
                <VTextarea v-model="input.saatlahir" rows="3" class="input" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="colum is-12 is-multiline">
          <div class="column">
            <table class="table is-bordered is-fullwidth">
              <tr>
                <th class="th-pri has-text-centered">Keterangan</th>
                <th class="th-pri has-text-centered">0</th>
                <th class="th-pri has-text-centered">1</th>
                <th class="th-pri has-text-centered">2</th>
                <th rowspan="6" class="th-pri has-text-centered">
                  <p class="mt-6">
                    Total skor :
                  </p>

                  <VControl>
                      <VInput style="width: 50%;" type="text" disabled class="input" v-model="input.totalSkor" />
                  </VControl>
                  <br>
                  <p>
                    Skor &lt; 4 : Distres nafas ringan <br>
                    Skor 4-5 : Distres nafas moderat <br>
                    Skor &gt; 5 : Distres nafas berat
                  </p>
                </th>
              </tr>
              <tr>
                <td class="td-pri has-text-centered">
                  Frekuensi napas
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="0"
                          label="< 60 x/menit"
                          v-model="input.frekuensiNapas0"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="1"
                          label="60 - 80 x/menit"
                          v-model="input.frekuensiNapas1"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="2"
                          label="> 80 x/menit"
                          v-model="input.frekuensiNapas2"
                      />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td class="td-pri has-text-centered">
                  Retraksi
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="0"
                          label="Tidak ada retraksi"
                          v-model="input.retraksi0"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="1"
                          label="Retraksi ringan"
                          v-model="input.retraksi1"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="2"
                          label="Retraksi berat"
                          v-model="input.retraksi2"
                      />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td class="td-pri has-text-centered">
                  Sianosis
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="0"
                          label="Tidak sianosis"
                          v-model="input.sianosis0"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="1"
                          label="Sianosis hilang dengan O₂"
                          v-model="input.sianosis1"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="2"
                          label="Sianosis hilang dengan O₂"
                          v-model="input.sianosis2"
                      />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td class="td-pri has-text-centered">
                  Air entry
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="0"
                          label="Udara masuk"
                          v-model="input.airEntry0"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="1"
                          label="Penurunan ringan udara masuk"
                          v-model="input.airEntry1"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="2"
                          label="Tidak ada udara masuk"
                          v-model="input.airEntry2"
                      />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td class="td-pri has-text-centered">
                  Merintih
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="0"
                          label="Tidak merintih"
                          v-model="input.merintih0"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="1"
                          label="Dapat didengar dengan stetoskop"
                          v-model="input.merintih1"
                      />
                  </VControl>
                </td>
                <td class="td-pri has-text-centered">
                  <VControl raw subcontrol>
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          circle
                          true-value="2"
                          label="Tidak dapat didengar dengan stetoskop"
                          v-model="input.merintih2"
                      />
                  </VControl>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 class="mb-3" style="font-weight: bold;">Riwayat resusitasi</h1>
              <ul class="ml-6">
                <li style="list-style:lower-alpha;">
                  Tindakan ventilasi 60 detik pertama
                  <div class="columns is-multiline mt-1">
                    <div class="column is-3">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Rangsang taktil"
                              label="Rangsang taktil"
                              v-model="input.rangsangTaktil"
                          />
                      </VControl>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Oksigenasi"
                              label="Oksigenasi :"
                              v-model="input.oksigenasi"
                          />
                      </VControl>
                      <VField addons class="mt-2">
                          <VControl>
                              <VInput type="text" class="input" v-model="input.oksigenasiText" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>lt/mnt</VButton>
                          </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="CPAP1"
                              label="CPAP :"
                              v-model="input.cpap1"
                          />
                      </VControl>
                      <VField addons class="mt-2">
                        <VControl class="field-addon-body">
                            <VButton static>PEEP :</VButton>
                        </VControl>
                          <VControl>
                              <VInput type="text" class="input" v-model="input.cpap1peepText" />
                          </VControl>
                      </VField>
                      <VField addons>
                        <VControl class="field-addon-body">
                            <VButton static>Fi0₂ :</VButton>
                        </VControl>
                          <VControl>
                              <VInput type="text" class="input" v-model="input.cpap1fio2Text" />
                          </VControl>
                      </VField>
                      <VField addons>
                        <VControl class="field-addon-body">
                            <VButton static>Flow :</VButton>
                        </VControl>
                          <VControl>
                              <VInput type="text" class="input" v-model="input.cpap1flowText" />
                          </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VControl raw subcontrol>
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="CPAP2"
                              label="CPAP :"
                              v-model="input.cpap2"
                          />
                      </VControl>
                      <VField addons class="mt-2">
                        <VControl class="field-addon-body">
                            <VButton static>PEEP :</VButton>
                        </VControl>
                          <VControl>
                              <VInput type="text" class="input" v-model="input.cpap2peepText" />
                          </VControl>
                      </VField>
                      <VField addons>
                        <VControl class="field-addon-body">
                            <VButton static>Fi0₂ :</VButton>
                        </VControl>
                          <VControl>
                              <VInput type="text" class="input" v-model="input.cpap2fio2Text" />
                          </VControl>
                      </VField>
                      <VField addons>
                        <VControl class="field-addon-body">
                            <VButton static>Flow :</VButton>
                        </VControl>
                          <VControl>
                              <VInput type="text" class="input" v-model="input.cpap2flowText" />
                          </VControl>
                      </VField>
                      <VField addons>
                        <VControl class="field-addon-body">
                            <VButton static>PIP :</VButton>
                        </VControl>
                          <VControl>
                              <VInput type="text" class="input" v-model="input.cpap2pipText" />
                          </VControl>
                      </VField>
                    </div>
                  </div>
                </li>
                <li class="is-flex">
                  Suction :
                  <VControl raw subcontrol class="ml-6">
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Ya"
                          label="Ya"
                          v-model="input.suctionYa"
                      />
                  </VControl>
                  <VControl raw subcontrol class="ml-6">
                      <VCheckbox
                          class="p-0"
                          color="primary"
                          square
                          true-value="Tidak"
                          label="Tidak"
                          v-model="input.suctionTidak"
                      />
                  </VControl>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 class="mb-3" style="font-weight: bold;">Riwayat medikasi</h1>
              <table class="table is-bordered is-fullwidth">
                <tr>
                  <th class="th-pri has-text-centered">
                    Nama Cairan
                  </th>
                  <th class="th-pri has-text-centered">
                    Jam Pemberian
                  </th>
                  <th class="th-pri has-text-centered">
                    Nama Obat-obatan
                  </th>
                  <th class="th-pri has-text-centered">
                    Dosis
                  </th>
                  <th class="th-pri has-text-centered">
                    Jam Pemberian
                  </th>
                </tr>
                <tr>
                  <td class="td-pri">
                    <div class="is-flex">
                      <VControl raw subcontrol class="mt-3">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Na cl 0,9% :"
                              label="Na cl 0,9% :"
                              v-model="input.nacl09"
                          />
                      </VControl>
                      <VField addons class="ml-2">
                          <VControl>
                              <VInput type="text" class="input" v-model="input.nacl09Text" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>ml</VButton>
                          </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian1" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                  <td class="td-pri">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Vitamin K"
                            label="Vitamin K"
                            v-model="input.vitaminK"
                        />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dosis1" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian2" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri">
                    <div class="is-flex">
                      <VControl raw subcontrol class="mt-3">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Dextrose 10% :"
                              label="Dextrose 10% :"
                              v-model="input.dextrose10"
                          />
                      </VControl>
                      <VField addons class="ml-2">
                          <VControl>
                              <VInput type="text" class="input" v-model="input.dextrose10Text" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>ml</VButton>
                          </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian3" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                  <td class="td-pri">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Adrenalin"
                            label="Adrenalin"
                            v-model="input.adrenalin"
                        />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dosis2" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian4" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri">
                    <div class="is-flex">
                      <VControl raw subcontrol class="mt-3">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Lain-lain :"
                              label="Lain-lain :"
                              v-model="input.lainLain"
                          />
                      </VControl>
                      <VField class="ml-2">
                          <VControl>
                              <VInput type="text" class="input" v-model="input.lainLainText" />
                          </VControl>
                      </VField>
                    </div>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian5" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                  <td class="td-pri">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Morfin"
                            label="Morfin"
                            v-model="input.morfin"
                        />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dosis3" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian6" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text is-flex" class="input" v-model="input.namaCairanFreeText1" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian7" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                  <td class="td-pri">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Sulfat atropin"
                            label="Sulfat atropin"
                            v-model="input.sulfatAtropin"
                        />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dosis4" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian8" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text is-flex" class="input" v-model="input.namaCairanFreeText2" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian9" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                  <td class="td-pri">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Natrium bicarbonat"
                            label="Natrium bicarbonat"
                            v-model="input.natriumBicarbonat"
                        />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dosis5" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian10" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text is-flex" class="input" v-model="input.namaCairanFreeText3" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian11" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                  <td class="td-pri">
                    <div class="is-flex">
                      <VControl raw subcontrol class="mt-3">
                          <VCheckbox
                              class="p-0"
                              color="primary"
                              square
                              true-value="Lain-lain :"
                              label="Lain-lain :"
                              v-model="input.lainLain2"
                          />
                      </VControl>
                      <VControl class="ml-2">
                          <VInput type="text" class="input" v-model="input.lainLain2Text" />
                      </VControl>
                    </div>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dosis6" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian12" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text is-flex" class="input" v-model="input.namaCairanFreeText4" />
                      </VControl>
                    </td>
                    <td class="td-pri">
                      <VDatePicker v-model="input.jamPemberian13" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text is-flex" class="input" v-model="input.namaObatObatanFreeText1" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dosis7" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian14" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text is-flex" class="input" v-model="input.namaCairanFreeText5" />
                      </VControl>
                    </td>
                    <td class="td-pri">
                      <VDatePicker v-model="input.jamPemberian15" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text is-flex" class="input" v-model="input.namaObatObatanFreeText2" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.dosis8" />
                    </VControl>
                  </td>
                  <td class="td-pri">
                    <VDatePicker v-model="input.jamPemberian16" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <h1 class="mb-3" style="font-weight: bold;">Transportasi</h1>
          <ul style="list-style: lower-alpha;" class="ml-6">
            <li>
              Ventilasi
              <div class="mt-2">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Room Air"
                            label="Room Air"
                            v-model="input.roomAir"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Oksigen"
                            label="Oksigen"
                            v-model="input.oksigen"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Single nasal prong"
                            label="Single nasal prong"
                            v-model="input.singleNasalProng"
                        />
                    </VControl>
                  </div>
                  <div class="column is-4 is-flex">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Lain-lain :"
                            label="Lain-lain :"
                            v-model="input.lainLain3"
                        />
                    </VControl>
                    <VControl class="ml-2" style="margin-top: -10px;">
                        <VInput type="text" class="input" v-model="input.lainLain3Text" />
                    </VControl>
                  </div>
                </div>
              </div>
            </li>
            <li>
              Alat transfer yang digunakan
              <div class="mt-2">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Inkubator"
                            label="Inkubator"
                            v-model="input.inkubator"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            true-value="Kangguru mother care"
                            label="Kangguru mother care"
                            v-model="input.kangguruMotherCare"
                        />
                    </VControl>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-8">
            </div>
            <div class="column is-4 has-text-centered">
              <span>Perawat resusitasi,</span>
              <VControl class="prime-auto">
                  <AutoComplete v-model="input.perawatResusitasi" :suggestions="d_Pegawai"
                      @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      class="mt-2" />
              </VControl>
              <TandaTangan :elemenID="'TTDPerawatResusitasi'" :width="'150'" :height="'150'" class="dek mt-4" />
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <Dialog :header="'Riwayat Catatan'" v-model:visible="modalRiwayat" :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '65vw' }" :maximizable="true" :modal="true">
    <div class="columns is-multiline">
      <div class="column ">
        <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
          <div class="columns is-multiline">
            <div class="column is-12">
              <table class="tg">
                <tbody v-for="(itemski, index2) in riwayat_Load" :key="index2">
                  <thead class="tg">
                    <tr>
                      <th style="text-align: center;">{{ H.formatDateIndoSimple(itemski.tanggalJam) }}</th>
                    </tr>
                  </thead>
                  <tr style="background-color: var(--danger--light-color);">
                    <td colspan="12">
                      <div style="margin-top: 30px;">
                        <div class="columns is-multiline">
                          <div class="column is-6">
                            <h1 class="mb-3" style="font-weight: bold;">Hubungan Dengan Pasien</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                              <VControl>
                                <Multiselect v-model="itemski.hubpasien" :attrs="{ value }" placeholder="--Pilih--"
                                  label="label" :options="d_hubungan" :searchable="true" track-by="label" mode="single"
                                  disabled autocomplete="off" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <h1 class="mb-3" style="font-weight: bold;">Nama</h1>
                            <VField>
                              <VControl>
                                <VInput v-model="itemski.nama" disabled class="input" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <h1 class="mb-3" style="font-weight: bold;">Durasi Edukasi</h1>
                            <VField>
                              <VControl>
                                <input v-model="itemski.durasiedukasi" disabled class="input" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <h1 class="mb-3" style="font-weight: bold;">Metode Edukasi</h1>
                            <VField>
                              <VControl>
                                <input v-model="itemski.metodeedukasi" disabled class="input" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <h1 class="mb-3 emr" style="font-weight: bold;">Materi Informasi dan Edukasi</h1>
                            <VField>
                              <VControl>
                                <VTextarea v-model="itemski.mie" disabled rows="3"></VTextarea>
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <h1 class="mb-3" style="font-weight: bold;">Respon</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                              <VControl>
                                <Multiselect v-model="itemski.respon" disabled :attrs="{ value }"
                                  placeholder="--Pilih--" label="label" :options="d_respon" :searchable="true"
                                  track-by="label" mode="single" autocomplete="off" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <hr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Dialog>

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
    @close="isAlltemplate = false; showModalTemplateFix = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="8"
        :loading="isLoading" paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
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

  <Dialog v-model:visible="isDialogPreview" modal header="Preview" :style="{ width: '90vw' }">
    <EdukasiPreview :edukasi="riwayatCatatanEdukasi" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isDialogPreview = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick, onMounted, watchEffect } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import * as EMR2 from '../page-emr-plugins/formulir-informasi-edukasi-pasien'
import * as EMR3 from '../page-emr-plugins/monitoring-resusitasi-neonatus'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import EdukasiPreview from './edukasi-preview.vue'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
useHead({
  title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let metodeEdukasi: any = ref(EMR2.metodeEdukasi())
let keadaanBayi: any = ref(EMR3.keadaanBayi())

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
const d_Pegawai: any = ref([])
const d_Dokter = ref([])
const riwayat_Load: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  date: {
    tanggal: new Date,
    jam: new Date
  },
})

const COLLECTION: any = ref('MonitoringResusitasiNeonatus') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const createMidnightDate = () => {
  const date = new Date();
  date.setHours(0, 0, 0, 0);
  return date;
};
const input: any = ref({
  jamLahirBayi: new Date(),
  jamPemberian1: createMidnightDate(),
  jamPemberian2: createMidnightDate(),
  jamPemberian3: createMidnightDate(),
  jamPemberian4: createMidnightDate(),
  jamPemberian5: createMidnightDate(),
  jamPemberian6: createMidnightDate(),
  jamPemberian7: createMidnightDate(),
  jamPemberian8: createMidnightDate(),
  jamPemberian9: createMidnightDate(),
  jamPemberian10: createMidnightDate(),
  jamPemberian11: createMidnightDate(),
  jamPemberian12: createMidnightDate(),
  jamPemberian13: createMidnightDate(),
  jamPemberian14: createMidnightDate(),
  jamPemberian15: createMidnightDate(),
  jamPemberian16: createMidnightDate(),
});

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const modalRiwayat = ref(false)
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const isAlltemplate: any = ref(false);
const filterMenu: any = ref('')
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

const dataTTD: any = ref([]);
const user = useUserSession().getUser().kelompokUser.kelompokUser
const createdBy = useUserSession().getUser().pegawai.namaLengkap
const riwayatCatatanEdukasi: any = ref([]);
const isloadingLAMPAU = ref(false)
const userLogin = useUserSession().getUser().pegawai.namaLengkap
const disabledForm: any = ref(false)
let isNewItemAdded = false;
const previewEdukasi: any = ref([])
const isDialogPreview: any = ref(false)


const loadRiwayat = async () => {
  const response = await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );

  if (response.length) {
    input.value = response[0];
    dataTTD.value = response[0]
    await nextTick(() => {
        H.tandaTangan().set("TTDPerawatResusitasi", dataTTD.value.TTDPerawatResusitasi);
      });
  } else {
    H.alert("info", "Data tidak ditemukan");
  }
};

const copy = (indexRiwayat, indexDetail) => {
  let selectedRiwayat = riwayatCatatanEdukasi.value[indexRiwayat];

  if (!selectedRiwayat) {
    console.error("Error: Riwayat tidak ditemukan untuk index yang diberikan.");
    return;
  }

  let selectedDetail = selectedRiwayat.details[indexDetail];

  if (!selectedDetail) {
    console.error("Error: Detail tidak ditemukan untuk index yang diberikan.");
    return;
  }

  console.log("Data yang disalin:", selectedDetail);

  let newItem = {
    tenagaMedis: selectedDetail.tenagaMedis,
    hubpasien: selectedDetail.hubpasien,
    nama: selectedDetail.nama,
    tanggalJam: selectedDetail.tanggalJam,
    durasiedukasi: selectedDetail.durasiedukasi,
    metodeedukasi: selectedDetail.metodeedukasi,
    metodeedukasilain: selectedDetail.metodeedukasilain,
    mie: selectedDetail.mie,
    tenagaMedis: userLogin,
    respon: selectedDetail.respon,
    disabledForm: false,
    ttd: selectedDetail.ttd,
  };


  Object.assign(input.value.details[0], newItem);
  console.log("Data yang disalin ditambahkan ke item baru:", input.value.details[0]);
  const ttdKey = `TTDPenerimaEdukasi-${0}`;
  H.tandaTangan().set(ttdKey, selectedDetail.ttd || null);
  H.alert("info", "Data berhasil disalin pada lembaran baru");
};

const formatDate = (date: any) => {
  const options: Intl.DateTimeFormatOptions = {
    timeZone: 'Asia/Jakarta',
    weekday: 'long',
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  };

  // Format tanggal dan waktu menggunakan Intl.DateTimeFormat
  return new Intl.DateTimeFormat('id-ID', options).format(new Date(date));
};

const simpan = () => {
  let ID = input.value.id ? input.value.id : '';

  let object: any = {};
  object = input.value;
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate;
  }
  object.nocm = pasien.value.nocm;

  object.pasien = H.setObjectPasien(pasien.value);
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi);

  object['TTDPerawatResusitasi'] = H.tandaTangan().get("TTDPerawatResusitasi");

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  };

  isLoading.value = true;
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false;
    })
    .catch((e: any) => {
      isLoading.value = false;
    });
};

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
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_pegawai.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
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

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahir = props.pasien.tgllahir
  input.value.jenisKelamin = props.pasien.jeniskelamin
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

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}`).then((responselast: any) => {
      isLoading.value = false
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

watchEffect(() => {
    let total = 0;

    // Separate variables for each input value
    const frekuensiNapas0 = parseFloat(input.value.frekuensiNapas0) || 0;
    const frekuensiNapas1 = parseFloat(input.value.frekuensiNapas1) || 0;
    const frekuensiNapas2 = parseFloat(input.value.frekuensiNapas2) || 0;
    const retraksi0 = parseFloat(input.value.retraksi0) || 0;
    const retraksi1 = parseFloat(input.value.retraksi1) || 0;
    const retraksi2 = parseFloat(input.value.retraksi2) || 0;
    const sianosis0 = parseFloat(input.value.sianosis0) || 0;
    const sianosis1 = parseFloat(input.value.sianosis1) || 0;
    const sianosis2 = parseFloat(input.value.sianosis2) || 0;
    const airEntry0 = parseFloat(input.value.airEntry0) || 0;
    const airEntry1 = parseFloat(input.value.airEntry1) || 0;
    const airEntry2 = parseFloat(input.value.airEntry2) || 0;
    const merintih0 = parseFloat(input.value.merintih0) || 0;
    const merintih1 = parseFloat(input.value.merintih1) || 0;
    const merintih2 = parseFloat(input.value.merintih2) || 0;

    // Calculate the total score
    total = frekuensiNapas0 + frekuensiNapas1 + frekuensiNapas2 +
            retraksi0 + retraksi1 + retraksi2 +
            sianosis0 + sianosis1 + sianosis2 +
            airEntry0 + airEntry1 + airEntry2 +
            merintih0 + merintih1 + merintih2;

    // Update the total score
    if (!isNaN(total)) {
        input.value.totalSkor = total;
    } else {
        input.value.totalSkor = 0;
    }
});


const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate','namaPasien','tanggalLahir','jenisKelamin','norm','namaIbu','jamLahirBayi']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false;
  showModalTemplate.value = false;
  H.alert('info', 'Template berhasil ditambahkan')
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(
    `/emr/hapus-template`, json).then((response: any) => {
      if (response.status !== 500) {
        isLoading.value = false
        isAlltemplate.value = false;
        H.alert('sucess', response.message);
        showModalTemplate.value = false;
        showModalTemplateFix.value = false;
      } else {
        H.alert('danger', response.message);
      }
    }).catch((e: any) => {
      isLoading.value = false
      H.alert('danger', e);
    })
}

const preview = () => {
  previewEdukasi.value = [];
  riwayatCatatanEdukasi.value.forEach((riwayat) => {
    if (riwayat.details) {
      previewEdukasi.value.push(...riwayat.details);
    }
  });
  console.log(riwayatCatatanEdukasi.value);
  isDialogPreview.value = true;
};

onMounted(() => {
  fetchPasien()
  setAutoFill()
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


.table.is-borderless {
  border: none !important;
  background-color: transparent;
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
