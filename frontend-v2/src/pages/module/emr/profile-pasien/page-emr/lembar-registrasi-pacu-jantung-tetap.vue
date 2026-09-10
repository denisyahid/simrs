<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
            ></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div
      class="column is-12 buttons mb-0 mt-0"
      style="margin: 10px; vertical-align: middle"
    >
      <VButton
        type="button"
        rounded
        outlined
        color="primary"
        raised
        icon="feather:folder"
        isLoading="false"
        @click="pilihTemplateFix(index)"
      >
        Pilih Template
      </VButton>
      <VButton
        type="button"
        rounded
        outlined
        color="info"
        raised
        icon="feather:file-text"
        :loading="isLoading"
        @click="pilihTemplate(index)"
      >
        Pilih Riwayat
      </VButton>
    </div>

    <hr class="m-0" />

    <div class="column is-12">
      <h1>
        Nama Template&emsp;&emsp;
        <span style="color: red">**Hanya diisi jika ingin membuat template</span>
      </h1>
      <VField>
        <VControl>
          <VTextarea v-model="input.namatemplate" rows="1"> </VTextarea>
        </VControl>
      </VField>
    </div>

    <VModal
      :open="showModalTemplateFix"
      title="Template"
      :noclose="true"
      size="medium"
      actions="right"
      @close="showModalTemplateFix = false"
    >
      <template #content>
        <form class="modal-form">
          <div class="column is-12 pt-0 pb-0">
            <span style="font-size: 9pt; font-weight: bold">List Template</span>
            <div style="overflow-y: auto" class="mt-1">
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
                    <td style="width: 15%; text-align: center">
                      <VIconButton
                        type="button"
                        raised
                        circle
                        icon="fas fa-plus"
                        @click="addTemplate(resep)"
                        color="info"
                        v-tooltip-prime.top="'Pilih'"
                      >
                      </VIconButton>
                    </td>
                    <td style="width: 15%; text-align: center">
                      <span class="mb-2">{{ resep.no }}</span
                      ><br />
                    </td>
                    <td style="width: 20%; text-align: center">
                      <span class="mb-2">{{ resep.created_at }}</span
                      ><br />
                    </td>
                    <td style="width: 20%; text-align: center">
                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span
                      ><br />
                    </td>
                    <td style="width: 50%; text-align: center">
                      <span class="mb-2">{{ resep.namatemplate }}</span
                      ><br />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </form>
      </template>
    </VModal>
    <VModal
      :open="showModalTemplate"
      title="Riwayat"
      :noclose="true"
      size="large"
      actions="right"
      @close="showModalTemplate = false"
    >
      <template #content>
        <form class="modal-form">
          <div class="column is-12 pt-0 pb-0">
            <span style="font-size: 9pt; font-weight: bold">List Riwayat</span>
            <div style="overflow-y: auto" class="mt-1">
              <table class="tg table-tg" v-if="listTemplate.length > 0">
                <thead>
                  <tr>
                    <td class="tg-0lax text-center" width="5%">#</td>
                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                    <td class="tg-0lax text-center" width="20%">Section</td>
                  </tr>
                </thead>
                <tbody v-for="resep in listTemplate">
                  <tr>
                    <td style="width: 5%; text-align: center">
                      <VIconButton
                        type="button"
                        raised
                        circle
                        icon="fas fa-plus"
                        @click="addTemplate(resep)"
                        color="info"
                        v-tooltip-prime.top="'Pilih'"
                      >
                      </VIconButton>
                    </td>
                    <td style="width: 25%; text-align: center">
                      <span class="mb-2">{{ resep.created_at }}</span
                      ><br />
                    </td>
                    <td style="width: 25%; text-align: center">
                      <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span
                      ><br />
                    </td>
                    <td style="width: 25%; text-align: center">
                      <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span
                      ><br />
                    </td>
                    <td style="width: 20%; text-align: center">
                      <span class="mb-2">{{ resep.pasien.nocm }}</span
                      ><br />
                    </td>
                    <td style="width: 20%; text-align: center">
                      <span class="mb-2">{{ resep.dpjpUtama }}</span
                      ><br />
                    </td>
                    <td style="width: 25%; text-align: center">
                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span
                      ><br />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </form>
      </template>
    </VModal>

    <div class="column is-12">
      <VCard>
        <div class="column p-0">
          <h1 style="font-weight: bold">Indikasi Klinis</h1>
        </div>

        <hr
          style="border-top: 1px dashed red; background-color: white"
          class="mt-0 mb-1"
        />
        <div class="column p-0">
          <h1 style="font-weight: bold">Gejala</h1>
        </div>
        <div class="colum is-multiline columns p-0">
          <div class="column is-6 p-0" v-for="(gejala, index) in Gejala" :key="index">
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input[gejala.model]"
                  :label="gejala.label"
                  :true-value="gejala.value"
                />
              </VControl>
            </VField>
            <VField v-if="gejala.label == 'Lainnya'">
              <VControl>
                <VTextarea type="text" class="input" v-model="input.gejala_lainnya" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column p-0">
          <h1 style="font-weight: bold">EKG</h1>
        </div>
        <div class="colum is-multiline columns p-0">
          <div class="column is-6 p-0" v-for="(ekg, index) in EKG" :key="index">
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input[ekg.model]"
                  :label="ekg.label"
                  :true-value="ekg.value"
                />
              </VControl>
            </VField>
            <VField v-if="ekg.label == 'Lainnya'">
              <VControl>
                <VTextarea type="text" class="input" v-model="input.ekg_lainnya" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column p-0">
          <h1 style="font-weight: bold">Etiologi</h1>
        </div>
        <div class="colum is-multiline columns p-0">
          <div class="column is-6 p-0" v-for="(etiologi, index) in Etiologi" :key="index">
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input[etiologi.model]"
                  :label="etiologi.label"
                  :true-value="etiologi.value"
                />
              </VControl>
            </VField>
            <VField v-if="etiologi.label == 'Lainnya'">
              <VControl>
                <VTextarea type="text" class="input" v-model="input.etiologi_lainnya" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column p-0 columns is-multiline">
          <div class="is-3 column">
            <h1 style="font-weight: bold">Obat-obatan yang sedang diberikan :</h1>
          </div>
          <div class="is-9 column">
            <VField>
              <VControl>
                <VTextarea type="text" class="input" v-model="input.obat" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column p-0 columns is-multiline">
          <div class="is-6 column">
            <h1 style="font-weight: bold">
              Penyakit lain yang tidak berhubungan langsung dengan kelainan hantaran :
            </h1>
          </div>
          <div class="is-6 column">
            <VField>
              <VControl>
                <VTextarea type="text" class="input" v-model="input.penyakitLain" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column p-0 columns is-multiline">
          <div class="is-4 column">
            <h1 style="font-weight: bold">GENERATOR YANG DIPASANG :</h1>
          </div>
          <div class="is-8 column is-multiline columns">
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.UNIPOLAR_YANG_DIPASANG"
                  label="UNIPOLAR"
                  true-value="UNIPOLAR"
                />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.BIPOLAR_YANG_DIPASANG"
                  label="BIPOLAR"
                  true-value="BIPOLAR"
                />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.lainLainnya_yang_dipasang"
                  label="LAIN-LAIN"
                  true-value="LAIN-LAIN"
                />
              </VControl>
            </VField>
            <VField v-if="input.lainLainnya_yang_dipasang == 'LAIN-LAIN'">
              <VControl>
                <VTextarea
                  type="text"
                  class="input"
                  v-model="input.generator_lainnya_yang_dipasang"
                />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column columns is-multiline">
          <div class="is-6 column is-flex">
            <div class="">
              <h1 style="font-weight: bold">Pabrik :</h1>
            </div>
            <div class="column pt-0">
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.pabrik" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="is-6 column is-flex">
            <div class="">
              <h1 style="font-weight: bold">Model 1:</h1>
            </div>
            <div class="column pt-0 is-multiline columns">
              <VCheckbox v-model="input.model_1" label="1.A" true-value="1.A" />
              <VCheckbox v-model="input.model_1" label="2.V" true-value="2.V" />
              <VCheckbox
                v-model="input.model_1"
                label="3.Dual Chamber"
                true-value="Dual Chamber"
              />
            </div>
          </div>

          <div class="is-6 column is-flex">
            <div class="">
              <h1 style="font-weight: bold">Model :</h1>
            </div>
            <div class="column pt-0">
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.Model" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="is-6 column is-flex">
            <div class="">
              <h1 style="font-weight: bold">Model 2:</h1>
            </div>
            <div class="column pt-0 is-multiline columns">
              <VCheckbox v-model="input.model_2" label="1.VVI" true-value="1.VVI" />
              <VCheckbox v-model="input.model_2" label="2. AAI" true-value="2. AAI" />
              <VCheckbox v-model="input.model_2" label="3. DDD" true-value="3. DDD" />
            </div>
          </div>

          <div class="is-6 column is-flex">
            <div class="">
              <h1 style="font-weight: bold">SN :</h1>
            </div>
            <div class="column pt-0">
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.SN" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="is-6 column is-flex">
            <div class="">
              <h1 style="font-weight: bold">Rate responsive:</h1>
            </div>
            <div class="column pt-0 is-multiline columns">
              <VCheckbox v-model="input.model_3" label="YA" true-value="YA" />
              <VCheckbox v-model="input.model_3" label="TIDAK" true-value="TIDAK" />
            </div>
          </div>
        </div>

        <div class="column p-0 columns is-multiline">
          <div class="is-4 column">
            <h1 style="font-weight: bold">GENERATOR YANG DIPROGRAM :</h1>
          </div>
          <div class="is-8 column is-multiline columns">
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.UNIPOLAR_YANG_DIPROGRAM"
                  label="UNIPOLAR"
                  true-value="UNIPOLAR"
                />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.BIPOLAR_YANG_DIPROGRAM"
                  label="BIPOLAR"
                  true-value="BIPOLAR"
                />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.lainLainnya_yang_DIPROGRAM"
                  label="LAIN-LAIN"
                  true-value="LAIN-LAIN"
                />
              </VControl>
            </VField>
            <VField v-if="input.lainLainnya_yang_DIPROGRAM == 'LAIN-LAIN'">
              <VControl>
                <VTextarea
                  type="text"
                  class="input"
                  v-model="input.generator_lainnya_yang_dipasang"
                />
              </VControl>
            </VField>
          </div>
        </div>

        <hr
          style="border-top: 1px dashed red; background-color: white"
          class="mt-0 mb-1"
        />
        <div class="column p-0 is-12 is-flex">
          <div class="column is-6 m-0 p-1">
            <h1 style="font-weight: bold; font-style: italic">Single</h1>
            <div
              class="column p-0 columns is-multiline"
              v-for="(single, index) in Single"
              :key="index"
            >
              <div class="column is-3">
                <h1 style="font-weight: bold">{{ single.label }} :</h1>
              </div>
              <div class="column">
                <VControl>
                  <VTextarea type="text" class="input" v-model="input[single.model]" />
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-6 m-0 p-1">
            <h1 style="font-weight: bold; font-style: italic">Double</h1>
            <div
              class="column p-0 columns is-multiline"
              v-for="(double, index) in Double"
              :key="index"
            >
              <div class="column is-3">
                <h1 style="font-weight: bold">{{ double.label }} :</h1>
              </div>
              <div class="column">
                <VControl>
                  <VTextarea type="text" class="input" v-model="input[double.model]" />
                </VControl>
              </div>
            </div>
          </div>
        </div>

        <div class="column p-0 is-flex">
          <div class="is-1 column">
            <h1 style="font-weight: bold">LEAD :</h1>
          </div>
          <div class="is-6 column is-multiline columns">
            <VField>
              <VControl>
                <VCheckbox v-model="input.LEAD" label="Dipasang" true-value="Dipasang" />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.LEAD"
                  label="Terpasang"
                  true-value="Terpasang"
                />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column p-0 is-multiline columns">
          <div class="column is-6" style="border: 1px solid black">
            <h1 style="font-weight: bold">Lead Antrium</h1>
            <div
              v-for="(leadAntrium, index) in LeadAntrium"
              :key="index"
              class="column p-0 columns is-multiline"
            >
              <div class="is-3 column">
                <h1 style="font-weight: bold">{{ leadAntrium.label }} :</h1>
              </div>
              <div class="column" v-if="leadAntrium.type == 'textbox'">
                <VField>
                  <VControl>
                    <VTextarea
                      type="text"
                      class="input"
                      v-model="input[leadAntrium.model]"
                    />
                  </VControl>
                </VField>
              </div>
              <div
                class="column is-flex p-0"
                v-if="
                  leadAntrium.type == 'checkbox' &&
                  leadAntrium.model == 'jenis_lead_antrium'
                "
              >
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.jenis_lead_antrium"
                      label="UNIPOLAR"
                      true-value="UNIPOLAR"
                    />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.jenis_lead_antrium"
                      label="Bipolar"
                      true-value="Bipolar"
                    />
                  </VControl>
                </VField>
              </div>

              <div
                class="column is-flex p-0"
                v-if="
                  leadAntrium.type == 'checkbox' &&
                  leadAntrium.model == 'steroid_eluting_lead_antrium'
                "
              >
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.steroid_eluting_lead_antrium"
                      label="YA"
                      true-value="YA"
                    />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.steroid_eluting_lead_antrium"
                      label="Tidak"
                      true-value="Tidak"
                    />
                  </VControl>
                </VField>
              </div>

              <div
                class="column is-flex p-0"
                v-if="
                  leadAntrium.type == 'checkbox' &&
                  leadAntrium.model == 'screw_in_lead_antrium'
                "
              >
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.screw_in_lead_antrium"
                      label="YA"
                      true-value="YA"
                    />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.screw_in_lead_antrium"
                      label="Tidak"
                      true-value="Tidak"
                    />
                  </VControl>
                </VField>
              </div>

              <div
                class="column is-flex p-0"
                v-if="
                  leadAntrium.type == 'checkbox' &&
                  leadAntrium.model == 'ambang_sensing_lead_antrium'
                "
              >
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.ambang_sensing_lead_antrium"
                      label="Diukur"
                      true-value="Diukur"
                    />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.ambang_sensing_lead_antrium"
                      label="Tidak Diukur"
                      true-value="Tidak Diukur"
                    />
                  </VControl>
                </VField>
              </div>

              <div
                class="column is-flex p-0"
                v-if="
                  leadAntrium.type == 'addons' &&
                  leadAntrium.model == 'sensitivity_lead_antrium'
                "
              >
                <VField addons>
                  <VControl>
                    <VInput
                      type="text"
                      class="input"
                      v-model="input.sensitivity_lead_antrium"
                    />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mv</VButton>
                  </VControl>
                </VField>
              </div>

              <div
                class="column p-0"
                v-if="
                  leadAntrium.type == 'multi_textbox' &&
                  leadAntrium.model == 'ambang_pacu_lead_antrium'
                "
              >
                <div class="column is-flex">
                  <h1 style="font-weight: bold">Output:</h1>
                  <VField addons>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.ambang_pacu_lead_antrium_output"
                      />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>v</VButton>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-flex">
                  <h1 style="font-weight: bold">Current:</h1>
                  <VField addons>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.ambang_pacu_lead_antrium_current"
                      />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mA</VButton>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-flex">
                  <h1 style="font-weight: bold">Resistance:</h1>
                  <VField addons>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.ambang_pacu_lead_antrium_Resistance"
                      />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>ohm</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" style="border: 1px solid black">
            <h1 style="font-weight: bold">Lead Verinteken</h1>
            <div
              v-for="(LeadVerinteken, index) in LeadVerinteken"
              :key="index"
              class="column p-0 columns is-multiline"
            >
              <div class="is-3 column">
                <h1 style="font-weight: bold">{{ LeadVerinteken.label }} :</h1>
              </div>
              <div class="column" v-if="LeadVerinteken.type == 'textbox'">
                <VField>
                  <VControl>
                    <VTextarea
                      type="text"
                      class="input"
                      v-model="input[LeadVerinteken.model]"
                    />
                  </VControl>
                </VField>
              </div>
              <div
                class="column is-flex p-0"
                v-if="
                  LeadVerinteken.type == 'checkbox' &&
                  LeadVerinteken.model == 'jenis_lead_antrium'
                "
              >
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.jenis_lead_antrium"
                      label="UNIPOLAR"
                      true-value="UNIPOLAR"
                    />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.jenis_lead_antrium"
                      label="Bipolar"
                      true-value="Bipolar"
                    />
                  </VControl>
                </VField>
              </div>

              <div
                class="column is-flex p-0"
                v-if="
                  LeadVerinteken.type == 'checkbox' &&
                  LeadVerinteken.model == 'steroid_eluting_lead_antrium'
                "
              >
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.steroid_eluting_lead_antrium"
                      label="YA"
                      true-value="YA"
                    />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.steroid_eluting_lead_antrium"
                      label="Tidak"
                      true-value="Tidak"
                    />
                  </VControl>
                </VField>
              </div>

              <div
                class="column is-flex p-0"
                v-if="
                  LeadVerinteken.type == 'checkbox' &&
                  LeadVerinteken.model == 'screw_in_lead_antrium'
                "
              >
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.screw_in_lead_antrium"
                      label="YA"
                      true-value="YA"
                    />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.screw_in_lead_antrium"
                      label="Tidak"
                      true-value="Tidak"
                    />
                  </VControl>
                </VField>
              </div>

              <div
                class="column is-flex p-0"
                v-if="
                  LeadVerinteken.type == 'checkbox' &&
                  LeadVerinteken.model == 'ambang_sensing_lead_antrium'
                "
              >
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.ambang_sensing_lead_antrium"
                      label="Diukur"
                      true-value="Diukur"
                    />
                  </VControl>
                </VField>
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="input.ambang_sensing_lead_antrium"
                      label="Tidak Diukur"
                      true-value="Tidak Diukur"
                    />
                  </VControl>
                </VField>
              </div>

              <div
                class="column is-flex p-0"
                v-if="
                  LeadVerinteken.type == 'addons' &&
                  LeadVerinteken.model == 'sensitivity_lead_antrium'
                "
              >
                <VField addons>
                  <VControl>
                    <VInput
                      type="text"
                      class="input"
                      v-model="input.sensitivity_lead_antrium"
                    />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mv</VButton>
                  </VControl>
                </VField>
              </div>

              <div
                class="column p-0"
                v-if="
                  LeadVerinteken.type == 'multi_textbox' &&
                  LeadVerinteken.model == 'ambang_pacu_lead_antrium'
                "
              >
                <div class="column is-flex">
                  <h1 style="font-weight: bold">Output:</h1>
                  <VField addons>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.ambang_pacu_lead_verintekel_output"
                      />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>v</VButton>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-flex">
                  <h1 style="font-weight: bold">Current:</h1>
                  <VField addons>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.ambang_pacu_lead_verintekel_current"
                      />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mA</VButton>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-flex">
                  <h1 style="font-weight: bold">Resistance:</h1>
                  <VField addons>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        v-model="input.ambang_pacu_lead_verintekel_Resistance"
                      />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>ohm</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="column p-0 is-flex">
          <div class="column is-8">
            <h1 style="font-weight: bold">
              Jika pemasangan ulangan/eksplantasi : Data tentang generator/lead :
            </h1>
          </div>
          <div class="column is-multiline columns">
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.pemasangan_ulangan"
                  label="ADA"
                  true-value="ADA"
                />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VCheckbox
                  v-model="input.pemasangan_ulangan"
                  label="TIDAK ADA"
                  true-value="TIDAK ADA"
                />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column p-0">
          <h1>Jika ada jelaskan di bawah ini :</h1>
        </div>
        <div class="column p-0">
          <h1>Prosedur pemasangan :</h1>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Pacu jantung sementara:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VCheckbox
                  v-model="input.prosedur_pemasangan_pacu_jantung"
                  label="YA"
                  true-value="YA"
                />
              </VControl>
              <VControl class="p-0">
                <VCheckbox
                  v-model="input.prosedur_pemasangan_pacu_jantung"
                  label="TIDAK"
                  true-value="TIDAK"
                />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Dilakukan EPSLsebelumnya:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VCheckbox v-model="input.EPSLsebelumnya" label="YA" true-value="YA" />
              </VControl>
              <VControl class="p-0">
                <VCheckbox
                  v-model="input.EPSLsebelumnya"
                  label="TIDAK"
                  true-value="TIDAK"
                />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Anastesi/premediksi:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.anastesi_premediksi" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Vena subclavia:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VCheckbox
                  v-model="input.venaSubclavia"
                  label="KANAN"
                  true-value="KANAN"
                />
              </VControl>
              <VControl class="p-0">
                <VCheckbox v-model="input.venaSubclavia" label="KIRI" true-value="KIRI" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Insisi:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VCheckbox v-model="input.Insisi" label="KANAN" true-value="KANAN" />
              </VControl>
              <VControl class="p-0">
                <VCheckbox v-model="input.Insisi" label="KIRI" true-value="KIRI" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Posisi Elektroda:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.posisi_elektroda" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Fikasi Lead:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.fikasi_lead" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Jahitan Subkutan:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.jahitan_subkutan" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 p-0 column">
              <h1>- Jahitan Kulit:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.jahitan_kulit" />
              </VControl>
            </div>
          </div>
          <div class="column p-0">
            <h1>- Antibiotika</h1>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 column">
              <h1>- Jenis:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.jenis_antibiotika" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 column">
              <h1>- Dosis:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.Dosis_antibiotika" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 column">
              <h1>- Cara Pemberian:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VCheckbox
                  v-model="input.caraPemberian"
                  label="FLUSH"
                  true-value="FLUSH"
                />
              </VControl>
              <VControl class="p-0">
                <VCheckbox
                  v-model="input.caraPemberian"
                  label="PARENTRAL"
                  true-value="PARENTRAL"
                />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 column">
              <h1>- Penyulit:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.Penyulit_antibiotika" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 column">
              <h1>Alasan untuk eksplantasi:</h1>
            </div>
            <div class="column p-0">
              <VControl class="p-0">
                <VInput type="text" class="input" v-model="input.alasan_eksplantasi" />
              </VControl>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 column">
              <h1>Generator dipasang pada tanggal:</h1>
            </div>
            <div class="column p-0">
              <VField>
                <VDatePicker
                  v-model="input.tanggal_generator_dipasang"
                  mode="dateTime"
                  style="width: 100%"
                  trim-weeks
                >
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput
                          :value="inputValue"
                          placeholder="Tanggal"
                          v-on="inputEvents"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="is-3 column">
              <h1>Lead dipasang pada tanggal:</h1>
            </div>
            <div class="column p-0">
              <VField>
                <VDatePicker
                  v-model="input.tanggal_Lead_dipasang"
                  mode="dateTime"
                  style="width: 100%"
                  trim-weeks
                >
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput
                          :value="inputValue"
                          placeholder="Tanggal"
                          v-on="inputEvents"
                        />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-multiline columns">
            <div class="column" style="border: 1px solid black">
              <div class="column">
                <h1>Indikasi Penggantian Generator</h1>
                <div v-for="(item, index) in indikasiPenggantian" :key="index">
                  <VControl>
                    <VCheckbox v-model="input[item.model]" :label="item.label" :true-value="item.value" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column" style="border: 1px solid black">
              <div class="column">
                <h1>Indikasi Penggantian Lead</h1>
                <div v-for="(item, index) in indikasiPenggantianLead" :key="index">
                  <VControl>
                    <VCheckbox v-model="input[item.model]" :label="item.label" :true-value="item.value" />
                  </VControl>
                </div>
              </div>
              <div class="column">
                <h1>Indikasi Penggantian Catatan</h1>
                <div v-for="(item, index) in indikasiPenggantianCatatan" :key="index">
                  <VControl>
                    <VCheckbox v-model="input[item.model]" :label="item.label" :true-value="item.value" />
                  </VControl>
                </div>
              </div>
            </div>
          </div>
          <div class="column p-0 is-multiline columns" v-for="(item, index) in keteranganlainnya" :key="index">
              <div class="column is-2">
                <h1>{{ item.label }} :</h1>
              </div>
              <div class="column p-0">
                <VControl class="p-0">
                  <VInput type="text" class="input" v-model="input[item.model]" />
                </VControl>
              </div>
            </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
  h,
  reactive,
  ref,
  computed,
  watch,
  onBeforeMount,
  onMounted,
  watchEffect,
} from 'vue'
import { useRoute, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import * as EMR from '../page-emr-plugins/lembar-registrasi-pacu-jantung'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset'
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let Gejala = EMR.Gejala()
let EKG = EMR.EKG()
let Etiologi = EMR.Etiologi()
let Single = EMR.Single()
let Double = EMR.Double()
let LeadAntrium = EMR.LeadAntrium()
let LeadVerinteken = EMR.LeadVerintekel()
let indikasiPenggantian = EMR.IndikasiPenggantian()
let indikasiPenggantianLead = EMR.indikasiPengngantianLead()
let indikasiPenggantianCatatan = EMR.IndikasiPenggantianCatatan()
let keteranganlainnya = EMR.KeteranganLainnya()

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
const isAktive = ref()

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const route = useRoute()
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Perawat: any = ref([])
const d_Ruangan: any = ref([])
const d_produk = ref([])
const d_ObatRS = ref([])
const d_Dokter = ref([])
const d_Petugas = ref([])
const dataTTD: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const COLLECTION: any = ref('LembarRegistrasiPacuJantungTetap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = async () => {
  let response = await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
  }
}

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response
    })
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const print = async () => {
  H.printBlade(
    `emr/formulir-catatan-pemberian-obat-kemoterapi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tanggalKunjunganPasien = new Date()
  input.value.ruangan = props.registrasi.namaruangan
}

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const fetchPegawai = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Petugas.value = response
    })
}

const addTemplate = (response: any) => {
  console.log(response)

  const excludedFields = [
    'namatemplate',
    '_id',
    'norm',
    'nama',
    'jenisKelamin',
    'alamat',
    'namaPasien',
    'jenisKelaminPasien',
    'norm',
    'tanggalLahirPasien',
    'alamatPasien',
  ]

  input.value = Object.keys(response).reduce((acc, key) => {
    if (!excludedFields.includes(key)) {
      acc[key] = response[key]
    }
    return acc
  }, {})

  input.value['id'] = ''
  showModalTemplateFix.value = false
  showModalTemplate.value = false
  H.alert('success', 'Berhasil ditambahkan')
  setAutoFill()
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi()
    .get(
      `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
    )
    .then((responselast: any) => {
      isLoading.value = false
      if (responselast.length) {
        listTemplate.value = responselast
        showModalTemplate.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi()
    .get(`/emr/get-emr-template?collection=${COLLECTION.value}`)
    .then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

onBeforeMount(async () => {
  try {
    await setView()
    await loadRiwayat()
    await setAutoFill()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error)
  }
})
onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error)
  }
  next()
})
</script>
