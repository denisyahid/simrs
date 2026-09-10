<template>
  <div class="form-layout">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" @simpanTemplate="simpanTemplate"></ButtonEmr>
          </div>
        </div>
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
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Input</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Registrasi</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">No Registrasi</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">No EMR</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="20%">Dokter</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Section</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="5%">#</td>
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
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
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
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
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
              <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)"
                v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Pilih'">
              </VIconButton>
              <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
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

  <div class="columns is-multiline p-2">

    <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
      <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
        @click="pilihTemplateFix(index)"> Pilih Template
      </VButton>
      <!-- <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
        @click="pilihTemplate(index)"> Pilih Riwayat
      </VButton> -->
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
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-4">
            <h1 style="font-weight: bold">Nama Pasien</h1>
            <VControl>
              <VInput v-model="input.namaPasien" class="input" type="text" disabled />
            </VControl>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Tanggal Lahir Pasien</h1>
            <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4">
            <h1 style="font-weight: bold">Jenis Kelamin</h1>
            <div class="is-flex">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                    :label="items.label" color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">No. Rekam Medis</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
            </VControl>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">Tanggal dan Jam</h1>
            <VDatePicker v-model="input.tanggalKunjunganPasien" mode="dateTime" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal dan Jam" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-4 pt-0">
            <h1 style="font-weight: bold">Ruangan</h1>
            <VControl>
              <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Ruangan" />
            </VControl>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <h1 style="font-weight: bold">Sumber Data</h1>
              </div>
              <VField>
                <VControl>
                  <VCheckbox v-model="input.Pasien" true-value="Pasien" label="Pasien" color="primary" square />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VCheckbox v-model="input.Keluarga" true-value="Keluarga" label="Keluarga" color="primary" square />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VCheckbox v-model="input.Lainnya" true-value="Lainnya" label="Lainnya" color="primary" square />
                </VControl>
              </VField>
              <Vfield class="column is-6">
                <VControl>
                  <VInput type="text" v-model="input.LainnyaDetail" placeholder="Detail Lainnya" />
                </VControl>
              </Vfield>
            </div>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="1. Pengkajian">
              <div class="column is-12 pb-0 pt-0">
                <h1 style="font-weight: bold;font-size: large;">Kajian :</h1>
              </div>
              <div class="columns is-multiline column is-12">
                <div class="column is-3" v-for="(data, i) in vitalSign">
                  <div class="columns is-multiline">
                    <div class="column is-12" style="margin-top: 0.5rem">
                      <span> {{ data.label }} : </span>
                    </div>
                    <div class="column is-12 pt-0">
                      <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                      <VField addons v-else>
                        <VControl>
                          <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ data.addon }} </VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12 pt-0">
                <div class="columns is-multiline">
                  <div class="column is-12 pb-0 pt-0">
                    <h1 style="font-weight: bold">Hasil Pemeriksaan Penunjang / PA:</h1>
                  </div>
                  <div class="column is-6">
                    <VControl>
                      <VInput v-model="input.HasilSatu" class="input" type="text" placeholder="1." />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl>
                      <VInput v-model="input.HasilDua" class="input" type="text" placeholder="2." />
                    </VControl>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline column is-12">
                <div class="column is-4" v-for="(data, i) in kajianSubTiga">
                  <h1 style="font-weight: bold">{{ data.label }} :</h1>
                  <VControl>
                    <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                  </VControl>
                </div>
              </div>

              <div class="columns is-multiline column is-12">
                <div class="columns is-multiline column pt-0">
                  <div class="column is-12 pt-0 pb-0">
                    <h1 style="font-weight: bold">Protokol Kemoterapi:</h1>
                  </div>
                  <div class="column is-6">
                    <VControl>
                      <VInput v-model="input.obat1" class="input" type="text" placeholder="1." />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl>
                      <VInput v-model="input.obat2" class="input" type="text" placeholder="2." />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl>
                      <VInput v-model="input.obat3" class="input" type="text" placeholder="3." />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl>
                      <VInput v-model="input.obat4" class="input" type="text" placeholder="4." />
                    </VControl>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline pt-0 column is-12">
                <div class="column is-6 pt-0" v-for="(data, i) in kajianSubKeluhan">
                  <h1 style="font-weight: bold">{{ data.label }} :</h1>
                  <VControl>
                    <VTextarea type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                  </VControl>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="2. Pemeriksaan Fisik">
              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-4" style="margin-top: 0.5rem">
                      <h1 style="font-weight: bold">Keadaan Umum</h1>
                    </div>
                    <div class="column is-8">
                      <Multiselect v-model="input.keadaanumum" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline p-3">
                <div class="column is-3" v-for="(data, i) in vitalSign2">
                  <div class="columns is-multiline">
                    <div class="column is-12" style="margin-top: 0.5rem">
                      <span> {{ data.label }} : </span>
                    </div>
                    <div class="column is-12">
                      <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                      <VField addons v-else>
                        <VControl>
                          <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ data.addon }} </VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline p-3">
                  <div class="columns is-multiline is-12 pl-1 pr-5 pt-5 pb-0">
                    <div class="column is-3">
                      <h1 style="font-weight: bold">PENILAIAN NYERI:</h1>
                    </div>
                    <div class="column is-9">
                      <h1 style="font-weight: bold">
                        Wong Backer (WBS) dan Numeric Pain Scale (NPS)
                      </h1>
                      <div class="columns is-mulitiline is-12 pt-3">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.benarNyeri" class="pt-1 pb-1" value="Benar Nyeri" label="Ya"
                              color="primary" square />
                          </VControl>
                        </VField>
                        <VField>
                          <VControl>
                            <VCheckbox v-model="input.tidakNyeri" class="pt-1 pb-1" value="Tidak Nyeri" label="Tidak"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="columns is-multiline is-12 pl-1 pr-5 pt-5 pb-0">
                    <div class="column is-7">
                      <h1 style="font-weight: bold">BERAPAKAH SKALA NYERI ANDA ?</h1>
                      <div class="columns pt-4">
                        <div class="column" style="text-align: center" v-for="(image, i) in listImageNyeri.detail">
                          <VAvatar size="medium" :picture="image.img" style="cursor: pointer !important"
                            :class="isAktive == i ? 'active' : ''" @click="skor(image, i)" />
                          <p>{{ image.descNilai }}</p>
                          <p>{{ image.nama }}</p>
                        </div>
                      </div>
                    </div>
                    <div class="column is-5">
                      <h1 style="font-weight: bold">Score</h1>
                      <div class="pt-4">
                        <VField v-for="skor in listSkoringNyeri.detail">
                          <VControl raw subcontrol class="p-0">
                            <VCheckbox class="pt-0" v-model="input.skoringNyeri" :true-value="skor.descNilai"
                              :label="skor.nama" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" style="margin-top: 0.5rem">
                      <h1 style="font-weight: bold">Lokasi Nyeri:</h1>
                    </div>
                    <div class="column is-10">
                      <VField>
                        <VControl>
                          <VInput v-model="input.lokasiNyeri" class="input" type="text" placeholder="Lokasi Nyeri..." />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <h1 style="font-weight: bold;">Frekuensi nyeri :</h1>
                    </div>
                    <div class="column is-2">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Jarang" label="Jarang"
                          v-model="input.frekuensiNyeri" />
                      </VControl>
                    </div>
                    <div class="column is-2">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Hilang timbul" label="Hilang timbul"
                          v-model="input.frekuensiNyeri" />
                      </VControl>
                    </div>
                    <div class="column is-2">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Terus-menerus" label="Terus-menerus"
                          v-model="input.frekuensiNyeri" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-2 mt-2">
                      <h1 style="font-weight: bold;">Lama nyeri :</h1>
                    </div>
                    <div class="column is-10">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.lamaNyeri" />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Menjalar:</h1>
                  </div>

                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.menjalar" true-value="tidakMenjalar" label="Tidak Menjalar"
                        color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.menjalar" true-value="menjalar" label="Menjalar, ke" color="primary"
                        square />
                    </VControl>
                  </VField>
                  <Vfield class="column is-6" v-if="input.menjalar == 'menjalar'">
                    <VControl>
                      <VInput type="text" v-model="input.detailMenjalar" placeholder="Detail Menjalar" />
                    </VControl>
                  </Vfield>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Kualitas Nyeri:</h1>
                  </div>

                  <div class="column is-10" style="display: flex">
                    <VField v-for="items in kualitasNyeri" :key="items.value">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.model" class="pt-1 pb-1" :true-value="items.label"
                          :label="items.label" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField class="column is-4 pt-1 pb-1">
                      <VControl>
                        <VInput type="text" v-model="input.detailKualitasNyerilainnya" placeholder="Lainnya" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <div class="columns is-multiline" v-for="items in keadaanFisik" :key="items.value">
                    <div class="column is-4" style="margin-top: 0.5rem">
                      <h1 style="font-weight: bold">{{ items.label }}</h1>
                    </div>
                    <div class="column is-8">
                      <VField>
                        <VControl raw subcontrol>
                          <VInput v-model="input[items.model]" class="input" type="text" :placeholder="items.label" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-12" style="margin-top: 0.5rem">
                      <h1 style="font-weight: bold">Mulut</h1>
                    </div>
                    <div class="column is-2">
                      <h1 style="font-weight: bold">Oral mucositis :</h1>
                    </div>
                    <div class="column is-3" style="display: flex">
                      <VField v-for="items in oralMucositis" :key="items.value">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.oralMucositis" class="pt-1 pb-1" :true-value="items.label"
                            :label="items.label" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <VField class="column is-4">
                      <VControl>
                        <VInput type="text" v-model="input.detailOralMucositis" placeholder="Jelaskan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <div class="columns is-multiline is-12">
                    <div class="column is-12" style="margin-top: 0.5rem">
                      <h1 style="font-weight: bold">Leher</h1>
                    </div>
                    <div class="column is-2">
                      <h1>Bentuk :</h1>
                    </div>
                    <div class="column is-10" style="display: flex">
                      <VField v-for="items in bentukLeher" :key="items.value">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[items.model]" class="pt-1 pb-1" :true-value="items.label"
                            :label="items.label" color="primary" square />
                        </VControl>
                      </VField>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="input.detailbentukLeher" placeholder="Jelaskan" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <div class="columns is-multiline is-12">
                    <div class="column is-12" style="margin-top: 0.5rem">
                      <h1 style="font-weight: bold">Dada</h1>
                    </div>
                    <div class="column is-2">
                      <h1>Bentuk :</h1>
                    </div>
                    <div class="column is-10" style="display: flex">
                      <VField v-for="items in bentukDada" :key="items.value">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[items.model]" class="pt-1 pb-1" :true-value="items.label"
                            :label="items.label" color="primary" square />
                        </VControl>
                      </VField>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="input.detailbentukDada" placeholder="Jelaskan" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-2">
                      <h1>Irama Nasfas :</h1>
                    </div>
                    <div class="column is-10" style="display: flex">
                      <VField class="is-flex">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.iramaReguler" true-value="reguler" label="reguler" color="primary"
                            square class="pt-1 pb-1" />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.iramaIrreguler" true-value="irreguler" label="irreguler"
                            color="primary" square class="pt-1 pb-1" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-2">
                      <h1>Sekret :</h1>
                    </div>
                    <div class="column is-10 is-flex">
                      <VField class="is-flex">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.sekretAda" true-value="Ada" label="Ada, warna/jumlah"
                            color="primary" square class="pt-1 pb-1" />
                        </VControl>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.sekretTidak" true-value="Tidak" label="Tidak Ada" color="primary"
                            square class="pt-1 pb-1" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VInput type="text" v-model="input.keteranganTerdapatSekret"
                            true-value="keteranganTerdapatSekret" placeholder="Keterangan" color="primary" square
                            class="pt-1 pb-1" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Suara nafas:</h1>
                  </div>
                  <div class="column is-10 is-flex pt-1 pb-1">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.suaraNafasNormal" true-value="normal" label="normal" color="primary"
                          square />
                      </VControl>
                    </VField>
                    <h1 style="font-weight: bold" class="pt-3 pb-1">Wheezing</h1>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.suaraNafasW" true-value="wheezingYa" label="Ya" color="primary"
                          square />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.suaraNafasW" true-value="wheezingTidak" label="Tidak" color="primary"
                          square />
                      </VControl>
                    </VField>
                    <h1 style="font-weight: bold" class="pt-3 pb-1">Batuk</h1>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.batuk" true-value="batukYa" label="Ya" color="primary" square />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.batuk" true-value="batukTidak" label="Tidak" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2"></div>
                  <div class="column is-10 is-flex">
                    <h1 style="font-weight: bold" class="pt-3 pb-1">Retraksi</h1>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.retraksi" true-value="retraksiYa" label="Ya" color="primary" square />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.retraksi" true-value="retraksiTidak" label="Tidak" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <div class="columns is-multiline is-12">
                    <div class="column is-12" style="margin-top: 0.5rem">
                      <h1 style="font-weight: bold">Abdomen</h1>
                    </div>
                    <div class="column is-2">
                      <h1>Kembung :</h1>
                    </div>
                    <div class="column is-10" style="display: flex">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kembung" true-value="ya" label="ya" color="primary" square
                            class="pt-1 pb-1" />
                        </VControl>
                      </VField>
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.kembung" true-value="tidak" label="tidak" color="primary" square
                            class="pt-1 pb-1" />
                        </VControl>
                      </VField>
                    </div>

                    <div class="column is-2">
                      <h1>Bising usus :</h1>
                    </div>
                    <div class="column is-10" style="display: flex">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.Bisingusus" true-value="BisingususNormal" label="Normal"
                            color="primary" square class="pt-1 pb-1" />
                        </VControl>
                      </VField>
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.Bisingusus" true-value="BisingususUbnormal" label="Abnormal"
                            color="primary" square class="pt-1 pb-1" />
                        </VControl>
                      </VField>
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="input.keteranganTerdapatBisingusus"
                            true-value="keteranganTerdapatBisingusus" placeholder="Keterangan" color="primary" square
                            class="pt-1 pb-1" />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="columns is-multiline is-12">
                    <div class="column is-12" style="margin-top: 0.5rem">
                      <h1 style="font-weight: bold">Ekstremitas</h1>
                    </div>

                    <div class="is-12 is-flex pb-1 pt-1">
                      <div class="is-flex">
                        <div class="column is-3">
                          <h1>Akral:</h1>
                        </div>
                        <div class="column is-10" style="display: flex">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.akral" true-value="Hangat" label="Hangat" color="primary" square
                                class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.akral" true-value="Dingin" label="Dingin" color="primary" square
                                class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>

                    <div class="is-12 is-flex pb-1 pt-1">
                      <div class="is-flex">
                        <div class="column is-3">
                          <h1>Pergerakan:</h1>
                        </div>
                        <div class="column is-10" style="display: flex">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.Pergerakan" true-value="Aktif" label="Aktif" color="primary"
                                square class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.Pergerakan" true-value="Pasif" label="Pasif" color="primary"
                                square class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>

                    <div class="is-12 is-flex pb-1 pt-1">
                      <div class="is-flex">
                        <div class="column is-5">
                          <h1>Kekuatan otot:</h1>
                        </div>
                        <div class="column is-10" style="display: flex">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kekuatanOtot" true-value="Kuat" label="Kuat" color="primary"
                                square class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kekuatanOtot" true-value="Lemah" label="Lemah" color="primary"
                                square class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>

                    <div class="is-12 is-flex pb-1 pt-1">
                      <div class="is-flex">
                        <div class="column is-2">
                          <h1>Kelainan:</h1>
                        </div>
                        <div class="column is-12" style="display: flex">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kelainan" true-value="Tidak" label="Tidak" color="primary"
                                square class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.kelainan" true-value="Ya" label="Ya, Jelaskan:" color="primary"
                                square class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                          <VField v-if="input.kelainan == 'Ya'">
                            <VControl raw subcontrol>
                              <VInput v-model="input.kelainanKeterangan" placeholder="Detail Kelainan" color="primary"
                                class="pt-1 pb-1" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <h1 style="font-weight: bold">Peripheral Neuropathy :</h1>
                    </div>
                    <div class="column is-8">
                      <VField v-for="items in PeripheralNeropathy" :key="items.value">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.PeripheralNeropathy" class="pt-1 pb-1" :true-value="items.model"
                            :label="items.label" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="columns is-12 is-multiline is-flex">
                    <div class="column is-12">
                      <h1 style="font-weight: bold">{{ kulitFields.nama }}:</h1>
                    </div>

                    <div v-for="(field, index) in kulitFields.detail" :key="index" class="column is-12 is-flex">
                      <h1>{{ field.nama }}:</h1>
                      <VField class="is-flex">
                        <VControl v-for="(option, optionIndex) in field.options" :key="optionIndex" raw subcontrol>
                          <VCheckbox v-model="input[field.nama.replace(/\s+/g, '').toLowerCase()]"
                            :true-value="option.value" :label="option.label" color="primary" square class="pt-1 pb-1" />
                        </VControl>

                        <VControl v-if="field.nama === 'Luka'" raw subcontrol>
                          <VInput type="text" v-model="input.keteranganTerdapatLuka" placeholder="Detail"
                            color="primary" class="pt-1 pb-1 is-12" />
                        </VControl>
                        <VControl v-if="field.nama === 'Masalah integritas kulit'" raw subcontrol>
                          <VInput type="text" v-model="input.keteranganMasalahIntegritasKulit" placeholder="Detail"
                            color="primary" class="pt-1 pb-1 is-12" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="3. Skala Aktivitas">
              <div class="column is-multiline is-12 is-flex">
                <h1 style="font-weight: bold">ECOG SCORE</h1>
                <div>
                  <VField v-for="skor in ecogScore" :key="skor.descNilai">
                    <VControl raw subcontrol class="p-0">
                      <VCheckbox class="pt-0" v-model="input.ecogScore" :true-value="skor.descNilai" :label="skor.label"
                        color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset :toggleable="true" legend="4. PENGKAJIAN SIMPTOM (EDMONTON SYMPTOM ASSESMENT SCALE = ESAS)">
              <div class="column is-multiline is-12">
                <div class="column is-12">
                  <div class="columns is-multiline is-12">
                    <div v-for="(kategori, index) in skalaPenilaian.skala" :key="index" class="column is-12 is-flex">
                      <p>{{ kategori.deskripsi }}</p>
                      <div class="checkbox-group is-flex">
                        <VField v-for="detail in kategori.detail" :key="detail.descNilai" class="is-flex">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[kategori.nama]" :true-value="detail.descNilai"
                              :label="detail.nama" color="primary" square class="pt-1 pb-1" />
                          </VControl>
                        </VField>
                      </div>
                      <p>{{ kategori.deskripsiAkhir }}</p>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold">Keterangan : Ringan ≤ 3 Sedang : 4 - 6 Berat ≥ 7</h1>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="5. Resiko Jatuh" :toggleable="true">
              <div class="column is-12">
                <div class="columns is-multiline">

                  <div class="column is-12">
                    <h1 style="font-weight: bold;">1.&nbsp Perhatikan cara
                      berjalan pasien saat akan
                      duduk
                      dikursi, apakah pasien tampak
                      seimbang(sempoyongan/limbung) ?</h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-2" v-for="(data) in Pilihan">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[data.model]" :true-value="data.title" :label="data.title"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12">

                    <h1 style="font-weight: bold;">2.&nbsp Apakah pasien
                      memegang pinggiran kursi atau
                      meja
                      atau benda lain sebagai penopang saat akan duduk ?
                    </h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-2" v-for="(data) in pegangKursisaatDuduk">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[data.model]" :true-value="data.title" :label="data.title"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12">

                    <h1 style="font-weight: bold;">3.&nbsp Hasil
                    </h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-4" v-for="(data) in hasilResikoJatuh">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[data.model]" :true-value="data.title" :label="data.title"
                              color="primary" square disabled />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                  </div>

                  <div class="column is-12">

                    <h1 style="font-weight: bold;">4.&nbsp Tindakan
                    </h1>
                    <div class="columns is-mulitiline">
                      <div class="column is-4" v-for="(data) in tindakanResikoJatuh">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[data.model]" :true-value="data.title" :label="data.title"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="6. EVALUASI AKSES KEMOTERAPI" :toggleable="true">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12 is-flex">
                    <div class=" pt-3">
                      <h1 style="font-weight: bold;">
                        Akses Kemoterapi:
                      </h1>
                    </div>
                    <VField v-for="(kategori) in aksesKemoterapi" class="is-flex">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[kategori.model]" :true-value="kategori.title" :label="kategori.title"
                          color="primary" square />
                      </VControl>
                      <VControl v-if="kategori.title === 'Lain-lainnya'" raw subcontrol class="pt-3">
                        <VInput type="text" v-model="input.keteranganAksesKemoterapi" placeholder="Detail"
                          color="primary" class="pt-1 pb-1 is-12" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12 is-flex">
                    <div class=" pt-3">
                      <h1 style="font-weight: bold;">
                        Kondisi akses kemoterapi saat ini :
                      </h1>
                    </div>
                    <VField v-for="(kategori) in aksesKemoterapiKeterangan" class="is-flex">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[kategori.model]" :true-value="kategori.title" :label="kategori.title"
                          color="primary" square />
                      </VControl>

                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="7. DIAGNOSA KEPERAWATAN" :toggleable="true">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <VField v-for="(item) in diagnosaKeperawatan" :key="item.value">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[item.model]" class="pt-1 pb-1 " :true-value="item.model"
                        :label="item.deskripsi" color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>

            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="8. RENCANA TINDAKAN KEPERAWATAN" :toggleable="true">
              <div class="column is-12">
                <div class="column is-12">
                  <VField v-for="(item) in rencanaTindakanKeperawatan" :key="item.value" class="is-flex">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[item.model]" class="" :true-value="item.model" :label="item.deskripsi"
                        color="primary" />
                    </VControl>

                    <VControl v-if="item.model === 'lainnya'" raw subcontrol class="column is-10">
                      <VTextarea type="text" v-model="input.keteranganLainnya" placeholder="Detail" color="primary"
                        row="1" />
                    </VControl>
                  </VField>
                </div>
              </div>

            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="9. PEMBERIAN PREMEDIKASI / THERAPI INTRAKEMOTERAPI"
              :toggleable="true">
              <div class="column is-12">
                <div class="column is-12" style="overflow-y: auto;">
                  <table width="250%" class="table-pri" style="width:200% !important">
                    <thead>
                      <tr class="tr-pri">
                        <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;" width="2%">#
                        </th>
                        <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Nama Obat</th>
                        <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Dosis</th>
                        <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Cara Pemberian
                        </th>
                        <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Waktu</th>
                        <th class="th-pri" colspan="4" style="text-align: center;">Nama Petugas</th>
                      </tr>
                      <tr class="tr-pri">
                        <th class="th-pri" style="text-align: center;">Perawat 1</th>
                        <th class="th-pri" style="text-align: center;">Paraf</th>
                        <th class="th-pri" style="text-align: center;">Perawat 2</th>
                        <th class="th-pri" style="text-align: center;">Paraf</th>
                      </tr>
                    </thead>
                    <tbody v-for="(items, index) in input.detailObatResep" :key="index">
                      <tr class="tr-pri">
                        <td class="td-pri" style="vertical-align: inherit;">
                          <div class="column">
                            <VButtons style="justify-content: space-around;">
                              <VIconButton type="button" raised circle icon="feather:plus"
                                v-tooltip-prime.bottom="'Tambah'" @click="addNewObat(items)" outlined color="info">
                              </VIconButton>
                              <VIconButton type="button" raised circle v-if="index > 0" v-tooltip-prime.bottom="'Hapus'"
                                outlined icon="feather:trash" @click="removeObat(items)" color="danger">
                              </VIconButton>
                            </VButtons>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center;">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <AutoComplete v-model="items.obat" :suggestions="d_ObatRS" @complete="fetchObat($event)"
                                  :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="ketik untuk mencari..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center;">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <VControl>
                                  <VInput type="text" v-model="items.dosis" placeholder="Dosis..." />
                                </VControl>
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center;">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <VInput type="text" v-model="items.caraPemberian" placeholder="Cara Pemberian..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center;">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <VInput type="text" v-model="items.waktu" placeholder="Waktu..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center;">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <AutoComplete v-model="items.perawat" :suggestions="d_Perawat"
                                  @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                  :field="'label'" placeholder="ketik untuk mencari..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center;">
                          <TandaTangan :elemenID="`TTDperawat1-${index}`" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td class="td-pri" style="text-align: center;">
                          <div class="column pt-3 pb-0">
                            <VField>
                              <VControl>
                                <AutoComplete v-model="items.perawat2" :suggestions="d_Perawat"
                                  @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                  :field="'label'" placeholder="ketik untuk mencari..." />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-pri" style="text-align: center;">
                          <TandaTangan :elemenID="`TTDperawat2-${index}`" :width="'150'" :height="'150'" class="dek" />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>

            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="columns p-fieldsets" legend="10. TIME OUT KEMOTERAPI" :toggleable="true">
              <div>
                <div class="columns is-12" style="overflow-y: auto; width: 100%;">
                  <table>
                    <thead>
                      <tr class="tr-pri">
                        <th class="th-pri" rowspan="2" style="vertical-align:inherit;text-align: center;">Kriteria</th>
                        <th v-for="n in 5" :key="`obat-${n}`" class="th-pri" rowspan="2"
                          style="vertical-align:inherit;text-align: center;">
                          <div class="column">
                            <h1 style="font-weight: bold">{{ `Obat ${n}` }}</h1>
                            <VField class="pt-3">
                              <VControl>
                                <!-- <AutoComplete v-model="input[`obat${n}`]" :suggestions="d_ObatRS"
                                  @complete="fetchObat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                  placeholder="ketik untuk mencari..." /> -->
                                <VInput type="text" v-model="input[`obat${n}`]" placeholder="Obat..." />
                              </VControl>
                            </VField>
                          </div>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in kriteria.kriteria" :key="index">
                        <td class="td-pri">{{ item.nama }}</td>
                        <td v-for="n in 5" :key="n" class="td-pri">
                          <div v-if="item.detail.length > 0" class="is-flex">
                            <div v-for="(detail, i) in item.detail" :key="i" style="text-align: center;">
                              <VField class="is-flex">
                                <VControl>
                                  <VCheckbox v-if="detail.type === 'checkBox'" :label="detail.deskripsi"
                                    v-model="input[`${detail.model}-${n}`]" color="primary"
                                    :true-value="`${detail.deskripsi}-${n}`" />
                                </VControl>
                              </VField>

                              <VField class="is-flex">
                                <VControl>
                                  <VInput v-if="detail.type === 'input'" type="text" :label="detail.deskripsi"
                                    v-model="input[`${detail.model}-${n}`]" color="primary" />
                                </VControl>
                              </VField>

                              <VField v-if="detail.model === 'tandaTanganPerawat'">
                                <VControl>
                                  <!-- <TandaTangan :elemenID="`${detail.model}-${n}`" :width="'150'" :height="'150'"
                                    class="dek" /> -->
                                  <AutoComplete v-model="input[`namaPerawat-${n}`]" :suggestions="d_Perawat"
                                    @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" class="" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                    :field="'label'" placeholder="ketik untuk mencari..." />
                                  <AutoComplete v-model="input[`namaPerawat2-${n}`]" :suggestions="d_Perawat"
                                    @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" class="is-input mt-3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari..." />
                                </VControl>
                              </VField>

                              <VField class="is-flex" v-if="detail.model === 'tandaTanganPasienKeluarga'">
                                <VControl>
                                  <TandaTangan :elemenID="`${detail.model}-${n}`" :width="'150'" :height="'150'"
                                    class="dek" />
                                  <VInput v-model="input[`keterangan-${detail.model}-${n}`]" color="primary"
                                    placeholder="Nama..." class="mt-3" />
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
            </Fieldset>
          </div>

          <div class="column is-12" style="overflow-y: auto;">
            <Fieldset class="p-fieldsets column is-12" legend="11. PELAKSANAAN DAN MONITORING PEMBERIAN KEMOTERAPI"
              :toggleable="true">
              <div class="columns is-12">
                <div class="column is-12">
                  <table class="is-12 table-pri" style="width: 200% !important;">
                    <thead>
                      <tr class="tr-pri">
                        <th class="th-pri" width="2%" rowspan="2" style="vertical-align:inherit;text-align: center;">#
                        </th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">Jam
                        </th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Suhu</th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Nadi</th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Tensi</th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Respirasi</th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Nyeri</th>
                        <th class="th-pri" width="10%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Obat dan Cairan Yang Diberikan</th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Volume (CC)</th>
                        <th class="th-pri" width="8%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Blood Return</th>
                        <th class="th-pri" width="8%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Reaksi Hiper sensitifitas</th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Makan(PORSI)</th>
                        <th class="th-pri" width="5%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Minum (CC)</th>
                        <th class="th-pri" width="2%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          BAB/BAK</th>
                        <th class="th-pri" width="10%" rowspan="2" style="vertical-align:inherit;text-align: center;">
                          Masalah</th>
                      </tr>
                    </thead>
                    <tbody v-for="(items, index) in input.pelaksanaanDanMonitoring" :key="index">
                      <tr class="tr-pri">
                        <td class="td-pri" style="vertical-align: inherit;">
                          <div class="column">
                            <VButtons style="justify-content: space-around;">
                              <VIconButton type="button" raised circle icon="feather:plus"
                                v-tooltip-prime.bottom="'Tambah'" @click="addMonitoring(items)" outlined color="info">
                              </VIconButton>
                              <VIconButton type="button" raised circle v-if="index > 0" v-tooltip-prime.bottom="'Hapus'"
                                outlined icon="feather:trash" @click="removeMonitoring(items)" color="danger">
                              </VIconButton>
                            </VButtons>
                          </div>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VDatePicker v-model="items.jamPelaksanaanDanMonitoring" mode="time" trim-weeks is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                  <VInput :value="inputValue" placeholder="Jam" v-on="inputEvents" />
                                </VControl>
                              </template>
                            </VDatePicker>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="°C" v-model="items.suhuMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="X/Mnt" v-model="items.nadiMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="mmHg" v-model="items.tensiMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="X/Mnt"
                                v-model="items.respirasiMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Nyeri" v-model="items.nyeriMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VInput type="text" class="input" v-model="items.obatCairan" />
                            <!-- <VControl>
                              <AutoComplete v-model="items.obatMonitoring" :suggestions="d_ObatRS"
                                @complete="fetchObat($event)" optionLabel="label" :dropdown="true" :minLength="3"
                                class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="ketik untuk mencari..." />
                            </VControl> -->
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="Number" class="input" placeholder="Volume"
                                v-model="items.VolumeMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="is-flex column" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VCheckbox v-model="items.bloodReturn" label="Ada" true-value="ada" />
                            </VControl>
                          </VField>

                          <VField>
                            <VControl>
                              <VCheckbox v-model="items.bloodReturn" label="Tidak Ada" true-value="tidakAda" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <div class="is-flex pt-1 pb-1">
                            <VField>
                              <VControl>
                                <VCheckbox v-model="items.reaksiHiper" label="Ada" true-value="ada" />
                              </VControl>
                            </VField>

                            <VField>
                              <VControl>
                                <VCheckbox v-model="items.reaksiHiper" label="Tidak Ada" true-value="tidakAda" />
                              </VControl>
                            </VField>
                          </div>

                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Makan" v-model="items.MakanMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="number" class="input" placeholder="Minum (cc)"
                                v-model="items.MinumCCMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="number" class="input" placeholder="Buang Kotoran"
                                v-model="items.BuangKotoranMonitoring" />
                            </VControl>
                          </VField>
                        </td>

                        <td class="td-pri" style="vertical-align: inherit;">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Masalah"
                                v-model="items.MasalahMonitoring" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="8" class="td-pri" style="vertical-align: inherit;">
                          <h1 style="font-weight: bold;"> Balance Cairan</h1>
                        </td>
                        <td colspan="4.5" class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold;" class="p-3">Cairan Masuk:</h1>
                            <VField class="p-3 column is-10">
                              <VControl>
                                <VInput type="number" class="input" placeholder="Total Cairan Masuk"
                                  v-model="input.rataRataCairanMasuk" disabled />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td colspan="4.5" class="td-pri">
                          <div class="is-flex">
                            <h1 style="font-weight: bold;" class="p-3">Cairan Keluar:</h1>
                            <VField class="p-3 column is-10">
                              <VControl>
                                <VInput type="number" class="input" placeholder="Total Cairan Keluar"
                                  v-model="input.rataRataCairanKeluar" disabled />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="12. EVALUASI" :toggleable="true">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Keluhan Pasien:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput v-model="input.keluhanPasien" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="columns is-multiline">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Keadaan Umum:</h1>
                  </div>
                  <div class="column is-10 is-flex pt-1 pb-1">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.keadaanUmum" label="Baik" color="primary"
                          true-value="keadaanUmumBaik" />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.keadaanUmum" label="Lemah" color="primary"
                          true-value="keadaanUmumLemah" />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.keadaanUmum" label="Sangat Lemah" color="primary"
                          true-value="keadaanUmumSangatLemah" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="columns is-multiline">
                  <div class="column is-3" v-for="(data, i) in vitalSign3">
                    <div class="columns is-multiline">
                      <div class="column is-12" style="margin-top: 0.5rem">
                        <h1 style="font-weight: bold"> {{ data.label }} : </h1>
                      </div>
                      <div class="column is-12">
                        <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                        <VField addons v-else>
                          <VControl>
                            <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>{{ data.addon }} </VButton>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="columns is-multiline">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Kondisi Akses Kemoterapi:</h1>
                  </div>
                  <div class="column is-10 is-flex pt-1 pb-1">
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.Kondisi" label="Baik" color="primary" true-value="KondisiBaik" />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.Kondisi" label="Bengkak" color="primary"
                          true-value="KondisiBengkak" />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VCheckbox v-model="input.Kondisi" label="Lainnya" color="primary"
                          true-value="KondisiLainnya" />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl>
                        <VInput v-model="input.kondisiLainnya" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="12. INTRUKSI KEPULANGAN PASIEN" :toggleable="true">
              <div class="column is-12">
                <div class="column is-12" style="overflow-y: auto;">
                  <table width="100%" class="table-pri" style="width: 100% !important;">
                    <thead>
                      <tr class="tr-pri">
                        <th class="th-pri" colspan="2">
                          <p>Obat di rumah:</p>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="n in 5" :key="`obat-${n}`">
                        <td class="td-pri" width="50%">
                          <VField class="pt-3">
                            <VControl>
                              <AutoComplete v-model="input[`obatRumah${2 * n - 1}`]" :suggestions="d_ObatRS"
                                @complete="fetchObat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                :placeholder="`${2 * n - 1}`" />
                            </VControl>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Instruksi Obat..."
                                v-model="input[`obatRumahInstruksi${2 * n - 1}`]" />
                            </VControl>
                          </VField>
                        </td>
                        <td class="td-pri" width="50%">
                          <VField class="pt-3">
                            <VControl>
                              <AutoComplete v-model="input[`obatRumah${2 * n}`]" :suggestions="d_ObatRS"
                                @complete="fetchObat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                :placeholder="`${2 * n}`" />
                            </VControl>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Instruksi Obat..."
                                v-model="input[`obatRumahInstruksi${2 * n}`]" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>

                    </tbody>
                    <tfoot>
                      <td class="td-pri">
                        <tr>
                          <VField class="pt-3">
                            <VControl>
                              <VDatePicker v-model="input.tanggalkontrolKembali" mode="date" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                  <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" placeholder="Tanggal Kontrol Kembali"
                                        v-on="inputEvents" />
                                    </VControl>
                                  </VField>
                                </template>
                              </VDatePicker>
                            </VControl>
                          </VField>
                        </tr>
                      </td>
                      <td class="td-pri">
                        <tr>
                          <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                            <VControl icon="feather:search">
                              <AutoComplete v-model="input.poliTujuan" :suggestions="d_Ruangan"
                                @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Ketik nama ruangan..." />
                            </VControl>
                          </VField>
                        </tr>
                      </td>
                    </tfoot>
                  </table>
                  <table width="100%" class="table-pri" style="width: 100% !important;">
                    <thead>
                      <tr class="tr-pri">
                        <th class="th-pri" colspan="2">
                          <p>Evaluasi diagnosa keperawatan</p>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="n in 2" :key="`evaluasi-${n}`">
                        <td class="td-pri" width="50%">
                          <VField class="pt-3">
                            <VControl>
                              <VTextarea type="text" class="input" :placeholder="`evaluasi ${2 * n - 1}`"
                                v-model="input[`evaluasi${2 * n - 1}`]" />
                            </VControl>
                          </VField>
                        </td>
                        <td class="td-pri" width="50%">
                          <VField class="pt-3">
                            <VControl>
                              <VTextarea type="text" class="input" :placeholder="`evaluasi ${2 * n}`"
                                v-model="input[`evaluasi${2 * n}`]" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <td>
                        <tr></tr>
                      </td>
                      <td>
                        <tr style=" vertical-align: inherit; text-align: center;">
                          <p class="p-3">Nama dan Tanda Tangan Perawat,</p>
                          <VField class="p-3">
                            <VControl>
                              <TandaTangan elemenID="evaluasiDiagnosaTTDPerawat" :width="'150'" :height="'150'"
                                class="dek" />
                              <AutoComplete v-model="input.evaluasiDiagnosaNamaTTDPerawat" :suggestions="d_Perawat"
                                @complete="fetchPerawat($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                class="is-input mt-3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="ketik untuk mencari..." />
                            </VControl>
                          </VField>
                        </tr>
                      </td>
                    </tfoot>
                  </table>
                </div>
              </div>
            </Fieldset>
          </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted, watchEffect } from "vue";
import { useRoute, onBeforeRouteLeave } from "vue-router";
import { useHead } from "@vueuse/head";
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
// import MultiSelect from "primevue/multiselect";
import * as EMR from "../page-emr-plugins/asuhan-dan-observasi-keperawatan-pasien-kemoterapi-rawat-jalan";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";
import Fieldset from "primevue/fieldset";
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
const user = useUserSession().getUser().pegawai;
let vitalSign = ref(EMR.vitalSign());
let kajianSubTiga = ref(EMR.kajianSubTiga());
let JenisKelamin = ref(EMR.JenisKelamin());
let kajianSubKeluhan = ref(EMR.kajianSubKeluhan());
let vitalSign2 = ref(EMR.vitalSign2());
let listImageNyeri: any = ref(EMR.imgNyeri());
let listSkoringNyeri: any = ref(EMR.skoringNyeri());
let kualitasNyeri = ref(EMR.kualitasNyeri());
let keadaanFisik = ref(EMR.keadaanFisik());
let oralMucositis = ref(EMR.oralMucositis());
let bentukLeher = ref(EMR.bentukLeher());
let bentukDada = ref(EMR.bentukDada());
let ekstimitasFields: any = ref(EMR.ekstimitasFields());
let PeripheralNeropathy = ref(EMR.PeripheralNeropathy());
let kulitFields: any = ref(EMR.kulitFields());
let ecogScore = ref(EMR.ecogScore());
let skalaPenilaian = ref(EMR.skalaPenilaian());
let hasilResikoJatuh = ref(EMR.hasilResikoJatuh())
let pegangKursisaatDuduk = ref(EMR.pegangKursisaatDuduk())
let Pilihan = ref(EMR.Pilihan())
let tindakanResikoJatuh = ref(EMR.tindakanResikoJatuh())
let aksesKemoterapi = ref(EMR.aksesKemoterapi())
let aksesKemoterapiKeterangan = ref(EMR.aksesKemoterapiKeterangan())
let diagnosaKeperawatan = ref(EMR.diagnosaKeperawatan())
let rencanaTindakanKeperawatan = ref(EMR.rencanaTindakanKeperawatan())
let kriteria: any = ref(EMR.timeOutKemoterapi())
let vitalSign3 = ref(EMR.vitalSign3())
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const checkTemplate: any = ref(false)
const idTemplate: any = ref('');
const route = useRoute()
const props = withDefaults(
  defineProps<{
    pasien?: any;
    registrasi?: any;
    FORM_NAME?: string;
    FORM_URL?: string;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: "",
    FORM_URL: "",
  }
);
const isAktive = ref();

const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const isLoading: any = ref(false);
const isDisabled: any = ref(false);
const isLoadingVitalSign: any = ref(false);
const d_Perawat: any = ref([]);
const d_Ruangan: any = ref([]);
const d_produk = ref([])
const d_ObatRS = ref([]);
const dataTTD: any = ref([]);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("FormulirAsuhanDanKeperawatanPasienKemoterapiRawatJalan"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input = ref({
  detailObatResep: [{ no: 1, signatures: { TTDperawat1: '', TTDperawat2: '' } }],
  pelaksanaanDanMonitoring: [
    {
      VolumeMonitoringMonitoring: 0,
      MinumCCMonitoringCCMonitoring: 0,
      jamPelaksanaanDanMonitoring: new Date()
    }
  ],
  tanggalKunjunganPasien: new Date()

});
const setView = () => {
  useHead({
    title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT,
  });
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
  useViewWrapper().setFullWidth(true);
};

const skor = (e: any, i: any) => {
  let listSkor = listSkoringNyeri.value.detail;

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skor = e.descNilai;
    }
  });
  isAktive.value = i;
};
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const loadData: any = ref(true);
const loadTTD = () => {
  input.value.detailObatResep.forEach((item, index) => {
    const ttd1Key = `TTDperawat1-${index}`;
    const ttd2Key = `TTDperawat2-${index}`;
    if (item.signatures.TTDperawat1) {
      H.tandaTangan().set(ttd1Key, item.signatures.TTDperawat1);
    }
    if (item.signatures.TTDperawat2) {
      H.tandaTangan().set(ttd2Key, item.signatures.TTDperawat2);
    }
  });
}
const loadRiwayat = async () => {
  loadData.value = true
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0];
    // console.log(response[0]['tandaTanganPerawat'])
    H.tandaTangan().set('evaluasiDiagnosaTTDPerawat', dataTTD.value.evaluasiDiagnosaTTDPerawat);
    for (let i = 0; i < 5; i++) {
      let a = i;
      H.tandaTangan().set(`tandaTanganPerawat-${i + 1}`, response[0]['tandaTanganPerawat'][a]);
      H.tandaTangan().set(`tandaTanganPasienKeluarga-${i + 1}`, response[0]['tandaTanganPasienKeluarga'][a]);
    }
  } else {
    const dataNS = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn")
    input.value.namaruangan = props.registrasi.namaruangan
    input.value.evaluasiDiagnosaNamaTTDPerawat = { label: user.namaLengkap, value: user.id }
    if (dataNS != null) {
      input.value.tinggiBadan = response_NS.tinggibadanObgyn
      input.value.beratBadan = response_NS.beratbadanObgyn
      input.value.tekananDarah = response_NS.tekananDarahObgyn
      input.value.nadi = response_NS.nadiObgyn
      input.value.respirasi = response_NS.nafasObgyn
      input.value.suhu = response_NS.celciusObgyn
      input.value.keluhanUtama = response_NS.keluhanutama
      input.value.keadaanumum = response_NS.keluhanutama
    }
  }

  loadData.value = false
}

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response;
    });
};

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`);
  d_Ruangan.value = response;
};

const fetchObat = async (filter: any) => {
  const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  response.map((element: any) => {
    element.label = element.productname,
      element.value = element.id
  })
  d_ObatRS.value = response
}

const simpan = () => {
  if (checkTemplate.value == true) {
    H.alert('warning', 'Simpan template ya, bukan simpan data :)')
    return;
  }

  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  object.detailObatResep.forEach((item: any, index: number) => {
    const ttdPerawat1 = H.tandaTangan().get(`TTDperawat1-${index}`);
    const ttdPerawat2 = H.tandaTangan().get(`TTDperawat2-${index}`);

    if (ttdPerawat1) {
      item.signatures.TTDperawat1 = ttdPerawat1;
    }
    if (ttdPerawat2) {
      item.signatures.TTDperawat2 = ttdPerawat2;
    }
  });

  const tandaTanganPerawat = [];
  const tandaTanganPasienKeluarga = [];

  for (let i = 1; i <= 5; i++) {
    const ttdPerawat = H.tandaTangan().get(`tandaTanganPerawat-${i}`);
    const ttdPasienKeluarga = H.tandaTangan().get(`tandaTanganPasienKeluarga-${i}`);

    if (ttdPerawat) {
      tandaTanganPerawat.push(ttdPerawat);
    }

    if (ttdPasienKeluarga) {
      tandaTanganPasienKeluarga.push(ttdPasienKeluarga);
    }
  }

  object['tandaTanganPerawat'] = tandaTanganPerawat;
  object['tandaTanganPasienKeluarga'] = tandaTanganPasienKeluarga;
  object['evaluasiDiagnosaTTDPerawat'] = H.tandaTangan().get(`evaluasiDiagnosaTTDPerawat`);

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: "asesmen_medis",
    data: object,
  };

  isLoading.value = true;
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false;
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false;
  });
};

const print = async () => {
  H.printBlade(
    `emr/formulir-asuhan-dan-obeservasi-keperawatan-pasien-kemoterapi-rawat-jalan?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
};

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.tglPembuatan = new Date();
};

const addNewObat = () => {
  input.value.detailObatResep.push({
    no: input.value.detailObatResep[input.value.detailObatResep.length - 1].no + 1,
    signatures: { TTDperawat1: '', TTDperawat2: '' },
  });
}

const removeObat = (index: any) => {
  input.value.detailObatResep.splice(index, 1)
}

const addMonitoring = () => {
  input.value.pelaksanaanDanMonitoring.push({
    no: input.value.pelaksanaanDanMonitoring[input.value.pelaksanaanDanMonitoring.length - 1].no + 1,
    jamPelaksanaanDanMonitoring: new Date()
  });
};

const removeMonitoring = (index: any) => {
  input.value.pelaksanaanDanMonitoring.splice(index, 1)
}

const show = () => {
  showData.value = true;
}

const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  input.value = dt;
  delete input.value['_id'];
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  checkTemplate.value = true
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
    checkTemplate.value = false
    input.value.namatemplate = null
    idTemplate.value = ''
    delete object.namatemplate;
    delete object['_id'];
    delete object.nocm;
    delete object.pasien;
    delete object.registrasi;
    object.id = '';
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
    checkTemplate.value = false
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
  const skipKeys = ['id', '_id', 'namatemplate', 'namaPasien', 'tanggalLahirPasien', 'JenisKelamin', 'norm', 'tanggalKunjunganPasien', 'namaruangan', 'Pasien', 'Keluarga', 'Lainnya', 'LainnyaDetail', 'beratBadan', 'tinggiBadan', 'BSA', 'diagnosaMedis', 'HasilSatu', 'HasilDua']; // Keys to be skipped

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

watch(() => [input.value.pilihan, input.value.pegangKursisaatDuduk], ([newValueS1_ARJ, newValueS2_ARJ]) => {
  //? Mencegah value checbox dari undefined
  newValueS1_ARJ = newValueS1_ARJ ?? 0;
  newValueS2_ARJ = newValueS2_ARJ ?? 0;

  if (newValueS1_ARJ == 'Tidak' && newValueS2_ARJ == 'Tidak') {
    input.value.hasilResikoJatuh = 'Tidak berisiko (tidak ditemukan a dan b)';
  } else if ((newValueS1_ARJ == 'Ya' && newValueS2_ARJ == 'Tidak') || newValueS1_ARJ == 'Tidak' && newValueS2_ARJ == 'Ya') {
    input.value.hasilResikoJatuh = 'Risiko rendah sedang (ditemukan a atau b)';
  } else if (newValueS1_ARJ == 'Ya' && newValueS2_ARJ == 'Ya') {
    input.value.hasilResikoJatuh = 'Resiko Tinggi (ditemukan a dan b)';
  }
});
watchEffect(() => {
  let total = 0;
  let total2 = 0

  input.value.pelaksanaanDanMonitoring.forEach((monitoring) => {
    total += parseFloat((monitoring.VolumeMonitoring || 0)) + parseFloat((monitoring.MinumCCMonitoring || 0));
    total2 += parseFloat((monitoring.BuangKotoranMonitoring || 0))
  });

  input.value.rataRataCairanKeluar = total2;
  input.value.rataRataCairanMasuk = total;
});
onBeforeMount(async () => {
  try {
    await setView()
    await loadRiwayat()
    await setAutoFill()
    await loadTTD()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
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
</script>
