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
              @simpanTemplate="simpanTemplate"
            ></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
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
                        <!-- <td class="tg-0lax text-center" width="20%">Nama Ruangan</td> -->
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
                          <VIconButton
                            type="button"
                            raised
                            circle
                            icon="fas fa-trash"
                            @click="deleteTemplate(resep.id)"
                            color="danger"
                            v-tooltip-prime.top="'Hapus'"
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
                        <!-- <td style="width: 20%; text-align: center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span
                          ><br />
                        </td> -->
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
                        <td class="tg-0lax text-center" width="25%">
                          Tanggal Registrasi
                        </td>
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
        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold">Nama Pasien</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" type="text" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">Tanggal Lahir Pasien</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker
                v-model="input.tanggalLahirPasien"
                mode="date"
                trim-weeks
                :max-date="new Date()"
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
          <div class="column is-2">
            <h1 style="font-weight: bold">Jenis Kelamin</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox
                  v-model="input.jeniskelamin"
                  class="pt-1 pb-1"
                  :true-value="items.label"
                  :label="items.label"
                  color="primary"
                  circle
                />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold">No. Rekam Medis</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput
                  type="text"
                  class="input"
                  placeholder="No. Rekam Medis"
                  v-model="input.norm"
                />
              </VControl>
            </VField>
          </div>

          <div class="column is-2">
            <h1 style="font-weight: bold">Tanggal dan Jam</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker
                v-model="input.tanggalKunjunganPasien"
                mode="dateTime"
                style="width: 100%"
                trim-weeks
                :max-date="new Date()"
              >
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput
                        :value="inputValue"
                        placeholder="Tanggal dan Jam"
                        v-on="inputEvents"
                      />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>

          <div class="columns is-multiline p-3">
            <div class="column is-3" v-for="(data, i) in vitalSign">
              <div class="columns is-multiline">
                <div class="column is-12 pb-0" style="margin-top: 0.5rem">
                  <span> {{ data.label }} : </span>
                </div>
                <div class="column is-12 pt-0">
                  <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                  <VField addons v-else>
                    <VControl>
                      <VInput
                        type="text"
                        class="input"
                        :placeholder="data.label"
                        v-model="input[data.model]"
                      />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>{{ data.addon }} </VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-flex is-12">
            <div class="column is-2">
              <h1 style="font-weight: bold">Dokter DPJP :</h1>
            </div>
            <div class="column is-10">
              <VField class="is-autocomplete-select">
                <VControl icon="fa:user-md" class="prime-auto-cus">
                  <AutoComplete
                    v-model="input.dokterDPJP"
                    :suggestions="d_Dokter"
                    :optionLabel="'label'"
                    @complete="fetchDokter($event)"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="Dokter..."
                    class="mt-2"
                  />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-flex is-12 pt-0 pb-0">
            <div class="column is-2">
              <h1 style="font-weight: bold">Diagnosis :</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VTextarea rows="2" v-model="input.TA_Diagnosis"></VTextarea>
              </VField>
            </div>
          </div>

          <div class="column is-flex is-12 pb-0">
            <div class="column is-2">
              <h1 style="font-weight: bold">Regimen:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput
                    v-model="input.regimen"
                    class="input primary"
                    type="text"
                    placeholder="Regimen"
                  />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-flex is-12">
            <div class="column is-2">
              <h1 style="font-weight: bold">Seri:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput
                    v-model="input.seri"
                    class="input primary"
                    type="text"
                    placeholder="Seri"
                  />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12">
            <div class="column is-12">
              <div class="column is-12" style="overflow-y: auto">
                <table width="250%" class="table-pri" style="width: 250% !important">
                  <thead>
                    <tr>
                      <th class="th-pri" colspan="5">
                        <div class="column is-3">
                          <h1 style="font-weight: bold">RUANGAN:</h1>
                          <VField class="is-autocomplete-select">
                            <VControl class="prime-auto-cus" icon="feather:home">
                              <AutoComplete
                                v-model="input.ruangan"
                                :suggestions="d_Ruangan"
                                :optionLabel="'label'"
                                @complete="fetchRuangan($event)"
                                :dropdown="true"
                                :minLength="3"
                                :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'"
                                :field="'label'"
                                placeholder="ruangan..."
                              />
                            </VControl>
                          </VField>
                        </div>
                      </th>
                      <th
                        class="th-pri"
                        colspan="19"
                        style="
                          vertical-align: inherit;
                          text-align: center;
                          font-size: large;
                        "
                      >
                        Tanggal Pemberian Obat
                      </th>
                    </tr>
                    <tr class="tr-pri">
                      <th
                        class="th-pri"
                        colspan="5"
                        style="vertical-align: inherit; text-align: center"
                      >
                        PREMEDIKASI
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKunjunganPasien1"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKunjunganPasien2"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKunjunganPasien3"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKunjunganPasien4"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKunjunganPasien5"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                    </tr>
                    <tr class="tr-pri">
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Nama Obat
                      </th>
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Dosis
                      </th>
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Pelarut
                      </th>
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Rute
                      </th>
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Lama Pemberian
                      </th>
                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>

                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>

                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>

                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>

                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="tr-pri" v-for="i in 6" :key="i">

                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <AutoComplete
                                v-model="input[`obat-${i}`]"
                                :suggestions="d_ObatRS"
                                @complete="fetchObat($event)"
                                :optionLabel="'label'"
                                :dropdown="true"
                                :minLength="3"
                                class="is-input"
                                :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'"
                                :field="'label'"
                                placeholder="ketik untuk mencari..."
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`jumlahObat-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Dosis"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`pelarut-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Pelarut"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`rute-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Rute"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`lamaPemberian-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Lama Pemberian"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri" style="text-align: center; vertical-align: middle">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`jam-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Jam"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat1_1-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat2_1-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri" style="text-align: center; vertical-align: middle">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`jam2-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Jam"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat1_2-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat2_2-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri" style="text-align: center; vertical-align: middle">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`jam3-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Jam"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat1_3-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat2_3-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri" style="text-align: center; vertical-align: middle">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`jam4-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Jam"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat1_4-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat2_4-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri" style="text-align: center; vertical-align: middle">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`jam5-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Jam"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat1_5-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat2_5-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-pri" colspan="2">
                        <h1 style="font-weight: bold">Nama Dokter:</h1>
                        <VField class="is-autocomplete-select">
                          <VControl icon="fa:user-md" class="prime-auto-cus">
                            <AutoComplete
                              v-model="input.dokterDPJPPremadikasi"
                              :suggestions="d_Dokter"
                              :optionLabel="'label'"
                              @complete="fetchDokter($event)"
                              :dropdown="true"
                              :minLength="3"
                              :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'"
                              :field="'label'"
                              placeholder="Dokter..."
                              class="mt-2"
                            />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" colspan="3" style="text-align: center">
                        <h1 style="font-weight: bold">Tanda Tangan</h1>
                        <TandaTangan
                          :elemenID="'dokterPremadikasiTTD'"
                          :width="'150'"
                          :height="'150'"
                          class="dek"
                        />
                      </td>
                      <td class="td-pri" colspan="10">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Catatan :</h1>
                          <VField>
                            <VTextarea
                              rows="2"
                              v-model="input.catatanPremadikasi"
                              placeholder="Catatan..."
                            ></VTextarea>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <div class="column is-12">
              <div class="column is-12" style="overflow-y: auto">
                <table width="250%" class="table-pri" style="width: 250% !important">
                  <thead>
                    <tr class="tr-pri">
                      <th
                        class="th-pri"
                        colspan="5"
                        style="vertical-align: inherit; text-align: center"
                      >
                        KEMOTERAPI
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKemoterapi1"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKemoterapi2"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKemoterapi3"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKemoterapi4"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                      <th class="th-pri" colspan="3" style="vertical-align: inherit">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Tanggal & Jam:</h1>
                          <VField>
                            <VDatePicker
                              v-model="input.tanggalKemoterapi5"
                              mode="dateTime"
                              style="width: 100%"
                              trim-weeks
                              :max-date="new Date()"
                            >
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput
                                    :value="inputValue"
                                    placeholder="Tanggal dan Jam"
                                    v-on="inputEvents"
                                  />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </th>
                    </tr>
                    <tr class="tr-pri">
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Nama Obat
                      </th>
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Dosis
                      </th>
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Pelarut
                      </th>
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Rute
                      </th>
                      <th
                        class="th-pri"
                        rowspan="2"
                        style="vertical-align: inherit; text-align: center"
                      >
                        Lama Pemberian
                      </th>
                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>

                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>

                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>

                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>

                      <th class="th-pri">Jam</th>
                      <th class="th-pri">P1</th>
                      <th class="th-pri">P2</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="tr-pri" v-for="i in 6" :key="i">
                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <AutoComplete
                                v-model="input[`obatKemoterapi-${i}`]"
                                :suggestions="d_ObatRS"
                                @complete="fetchObat($event)"
                                :optionLabel="'label'"
                                :dropdown="true"
                                :minLength="3"
                                class="is-input"
                                :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'"
                                :field="'label'"
                                placeholder="ketik untuk mencari..."
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`jumlahObatKemoterapi-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Jumlah"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`pelarutKemoterapi-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Pelarut"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`ruteKemoterapi-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Rute"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <div class="column p-3">
                          <VField>
                            <VControl>
                              <VInput
                                v-model="input[`lamaPemberianKemoterapi-${i}`]"
                                type="text"
                                class="input"
                                placeholder="Lama Pemberian"
                              />
                            </VControl>
                          </VField>
                        </div>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <VInput v-model="input[`jam1LamaKemo-${i}`]" type="text" class="input" placeholder="Jam" />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_1_1-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_2_1-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <VInput v-model="input[`jam2LamaKemo-${i}`]" type="text" class="input" placeholder="Jam" />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_1_2-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_2_2-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <VInput v-model="input[`jam3LamaKemo-${i}`]" type="text" class="input" placeholder="Jam" />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_1_3-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_2_3-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <VInput v-model="input[`jam4LamaKemo-${i}`]" type="text" class="input" placeholder="Jam" />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_1_4-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_2_4-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <VInput v-model="input[`jam5LamaKemo-${i}`]" type="text" class="input" placeholder="Jam" />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_1_5-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>

                      <td class="td-pri">
                        <VControl class="prime-auto">
                          <AutoComplete
                            v-model="input[`perawat_kemoterapi_2_5-${i}`]"
                            :suggestions="d_Perawat"
                            @complete="fetchPerawat($event)"
                            :optionLabel="'label'"
                            :dropdown="true"
                            :minLength="3"
                            :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'"
                            :field="'label'"
                            class="mt-2"
                          />
                        </VControl>
                      </td>
                    </tr>
                    <tr>
                      <td class="td-pri" colspan="2">
                        <h1 style="font-weight: bold">Nama Dokter:</h1>
                        <VField class="is-autocomplete-select">
                          <VControl icon="fa:user-md" class="prime-auto-cus">
                            <AutoComplete
                              v-model="input.dokterDPJPKemoterapi"
                              :suggestions="d_Dokter"
                              :optionLabel="'label'"
                              @complete="fetchDokter($event)"
                              :dropdown="true"
                              :minLength="3"
                              :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'"
                              :field="'label'"
                              placeholder="Dokter..."
                              class="mt-2"
                            />
                          </VControl>
                        </VField>
                      </td>
                      <td class="td-pri" colspan="3" style="text-align: center">
                        <h1 style="font-weight: bold">Tanda Tangan</h1>
                        <TandaTangan
                          :elemenID="'dokterKemoterapiTTD'"
                          :width="'150'"
                          :height="'150'"
                          class="dek"
                        />
                      </td>
                      <td class="td-pri" colspan="10">
                        <div class="column is-12">
                          <h1 style="font-weight: bold">Catatan :</h1>
                          <VField>
                            <VTextarea
                              rows="2"
                              v-model="input.catatanKemoterapi"
                              placeholder="Catatan..."
                            ></VTextarea>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
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
import { h, reactive, ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import * as EMR from '../page-emr-plugins/catatan-pemberian-obat-kemoterapi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let vitalSign = EMR.vitalSign()
let JenisKelamin = EMR.JenisKelamin()

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

//template constant
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)

const isLoading: any = ref(false)
const i: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const d_Perawat: any = ref([])
const d_Ruangan: any = ref([])
const d_produk = ref([])
const d_ObatRS = ref([])
const d_Dokter = ref([])
const dataTTD: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('FormulirCatataPemberianObatKemoterapi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalKunjunganPasien: new Date(),
  pasien: {},
  registrasi: {},
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadData: any = ref(true)

const loadRiwayat = async () => {
  loadData.value = true
  let response = await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set('dokterKemoterapiTTD', dataTTD.value.dokterKemoterapiTTD)
    H.tandaTangan().set('dokterPremadikasiTTD', dataTTD.value.dokterPremadikasiTTD)
  } else {
    input.value.ruangan = props.registrasi.namaruangan
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

const getDataExist = async () => {
  await useApi()
    .get(
      `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response) => {
      // input.value.beratBadan = response.beratBadan
      // input.value.tinggiBadan = response.tinggiBadan
      // input.value.IMT = response.IMT
      console.log()
    })
}

const fetchObat = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`
  )
  response.map((element: any) => {
    ;(element.label = element.productname), (element.value = element.id)
  })
  d_ObatRS.value = response
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['dokterKemoterapiTTD'] = H.tandaTangan().get('dokterKemoterapiTTD')
  object['dokterPremadikasiTTD'] = H.tandaTangan().get('dokterPremadikasiTTD')

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
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const print = async () => {
  H.printBlade(
    H.printBlade(
      `emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`
    )
  )
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.norm = props.pasien.nocm
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.dokterDPJP = {
    value: props.registrasi.iddokter,
    label: props.registrasi.dokter,
  }
  input.value.dokterDPJPKemoterapi = {
    value: props.registrasi.iddokter,
    label: props.registrasi.dokter,
  }
  input.value.dokterDPJPPremadikasi = {
    value: props.registrasi.iddokter,
    label: props.registrasi.dokter,
  }
  input.value.tglPembuatan = new Date()
}

const show = () => {
  showData.value = true
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

//template function
const addTemplate = (response: any) => {
  console.log(response)

  const excludedFields = [
    'namatemplate',
    'id',
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

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('error', 'Nama Template harus diisi untuk menyimpan.');
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}
  object = input.value
  // object.pasien = H.setObjectPasien(pasien.value)
  // object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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
          // responselast[x].id = ''
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
  // console.log(idTemplate)
  // return
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

setView()
getDataExist()
loadRiwayat()
setAutoFill()
fetchPerawat({ query: '' })
fetchRuangan({ query: '' })
fetchObat({ query: '' })
fetchDokter({ query: '' })
</script>
