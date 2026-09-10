<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Asesmen Awal Keperawatan Intensif</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideCetak></ButtonEmr>
            </div>
          </div>
        </div>
        <ConfirmDialog group="templating">
          <template #message="slotProps">
            <div style="width:500px;height:300px;">
              <table style="width:100%;height:100%;border-collapse: collapse">
                <tr>
                  <td style="text-align:center;vertical-align:middle">
                    <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
                  </td>
                </tr>
                <tr>
                  <td style="padding:7px;text-align:center">
                    <p style="font-size:large">{{ slotProps.message.message }}</p>
                  </td>
                </tr>
              </table>
            </div>
          </template>
        </ConfirmDialog>

        <Dialog v-model:visible="modalRencanaPP" maximizable modal header="" :style="{ width: '70vw' }">
          <RencanaPP :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
          :pasien="props.pasien" :registrasi="props.registrasi"/>
          <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalRencanaPP = false; isLoading = false">
              Tutup
            </VButton>
          </template>
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

        <!-- form baru -->

        <div class="column is-12" style="margin-top: 30px;">
          <div class="columns is-multiline">

            <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
                @click="pilihTemplateFix(index)"> Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
              @click="pilihTemplate(index)"> Pilih Riwayat
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
                  <h1 class="mb-3" style="font-weight: bold;">Tanggal Kedatangan</h1>
                  <VField>
                    <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks :max-date="new Date()">
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
                <div class="column is-4">
                  <h1 class="mb-3" style="font-weight: bold;">Jam Kedatangan</h1>
                  <VField>
                    <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr>
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
                <div class="column is-4">
                  <h1 class="mb-3" style="font-weight: bold;">Jam Asesmen Awal</h1>
                  <VField>
                    <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr>
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

            </div>
          </div>
        </div>

        <div class="column is-12" style="margin-top: -10px;">
          <div class="column is-12 pl-0 pr-0">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <div class="columns is-multiline">
                    <div class="column is-4 mt-auto mb-auto pl-0">
                      <h1 style="font-weight: bold;">Rujukan
                      </h1>
                    </div>
                    <div class="column is-4 mt-auto mb-auto">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="YA"
                            label="Ya" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4 mt-auto mb-auto">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="TIDAK"
                            label="Tidak" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12" v-if="input.kebrujukan == 'YA'">
                  <div class="columns is-multiline">
                    <div class="column is-2 pl-0">
                      <h3 style="font-weight: bold;">
                        Dari
                      </h3>
                    </div>
                    <div class="column is-10">
                      <VField>
                        <VControl>
                          <VInput type="text" class="heightinput input" placeholder="Ket Rujukan"
                            v-model.number="input.kebketrujukan" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-6" v-else-if="input.kebrujukan == 'TIDAK'">
                  <div class="columns is-multiline">
                    <div class="column is-4 mt-auto mb-auto">
                      <h1 style="font-weight: bold;">Kedatangan
                      </h1>
                    </div>
                    <div class="column is-4 mt-auto mb-auto">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan" @change=""
                            true-value="SENDIRI" label="Sendiri" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4 mt-auto mb-auto">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan"
                            true-value="DIANTARA" label="Diantar" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12" v-if="input.kebrujukan == 'TIDAK' && input.kebrujuklanjutan == 'DIANTAR'">
                  <div class="columns is-multiline">
                    <div class="column is-2 mt-auto mb-auto pl-0">
                      <h3 style="font-weight: bold;">
                        Diantar Oleh
                      </h3>
                    </div>
                    <div class="column is-10">
                      <VField>
                        <VControl>
                          <VInput type="text" class="heightinput input" placeholder="Diantar Oleh"
                            v-model.number="input.kebketrujukan" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <br>
        <hr><br>

        <div class="column is-12" style="margin-top: -20px;">
          <div class="column is-12 pl-0 pr-0">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6" fullwidth>
                  <h1 class="mb-3 emr">ALLOANAMNESIS</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="d_allo" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField v-if="input.kebpilihanallo == 'Lainnya'">
                    <VControl>
                      <VTextarea v-model="input.keballoanamnesis" placeholder="Ketik Alloanamnesis Lainnya" rows="3">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr><br>

        <div class="column is-12" style="margin-top: -20px;">
          <div class="column is-12 pl-0 pr-0">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12 mt-auto">
                  <h1 class="mb-5 emr mt-auto">ANAMNESIS</h1>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h1 class="mb-3 emr">Keluhan Utama</h1>
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.keluhanutama" rows="3">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 class="mb-3 emr">Riwayat penyakit sekarang</h1>
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.riwayatpenyakit" rows="3">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 class="mb-3 emr">Riwayat penyakit terdahulu</h1>
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.riwayatpenyakitdahulu" rows="3">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 class="mb-3 emr">Riwayat pengobatan</h1>
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.riwayatpengobatan" rows="3">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <h1 class="mb-3 emr">Riwayat penyakit keluarga</h1>
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="3">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </div>
                    <!-- <div class="column is-12">
                                            <h1 class="mb-3 emr">Riwayat alergi</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.riwayatalergi" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div> -->
                    <div class="column is-12">
                      <h1 class="mb-3 emr">Riwayat alergi</h1>
                      <VField vertical>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="YA" label="Ya"
                            color="primary" circle />
                        </VControl>
                      </VField>
                      <VField vertical>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi" true-value="TIDAK"
                            label="Tidak" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12" v-if="input.isalergi == 'YA'">
                      <h1 class="mb-3 emr">Jenis alergi</h1>
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.riwayatalergi" placeholder="Jelaskan..." rows="3">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr><br>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 class="mb-3 emr">PEMERIKSAAN FISIK:</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-12 emr">Keadaan Umum</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="d_keadaanumum" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <div class="column is-12" style="margin-top: -10px;">
                    <h1 style="font-weight: bold;">Tekanan Darah</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" placeholder="Tekanan Darah"
                          v-model="input.tekananDarahObgyn" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>mmHg</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Nadi</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="" v-model="input.nadiObgyn" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Respirasi</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="" v-model="input.nafasObgyn" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Suhu</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="" v-model="input.celciusObgyn" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>°C </VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">SaO2</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="" v-model="input.sao2Obgyn" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>%</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Berat Badan</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratbadanObgyn" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>kg</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Tinggi Badan</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.tinggibadanObgyn" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cm</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-8" style="margin-top: -10px;">
                  <h1 class="mb-3 emr">GCS</h1>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <VField addons>
                        <VControl class="field-addon-body">
                          <VButton static>E</VButton>
                        </VControl>
                        <VControl expanded>
                          <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label"
                            :options="d_gcse" :searchable="true" track-by="label" mode="single" autocomplete="off">
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField addons>
                        <VControl class="field-addon-body">
                          <VButton static>V</VButton>
                        </VControl>
                        <VControl expanded>
                          <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label"
                            :options="d_gcsv" :searchable="true" track-by="label" mode="single" autocomplete="off">
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-4">
                      <VField addons>
                        <VControl class="field-addon-body">
                          <VButton static>M</VButton>
                        </VControl>
                        <VControl expanded>
                          <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label"
                            :options="d_gcsm" :searchable="true" track-by="label" mode="single" autocomplete="off">
                          </Multiselect>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <br>
        <hr><br>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 class="mb-3 emr">ASSESMEN NYERI:</h1>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <h1 class="mb-3 emr">Skala nyeri</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="heightinput input" placeholder="" v-model="input.skalanyeri" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 class="mb-3 emr">Lokasi</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="heightinput input" placeholder="" v-model="input.lokasi" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-6">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <h1 class="mb-3 emr">Frekuensi Nyeri</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.frekuensinyeri" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="d_freknyeri" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 class="mb-3 emr">Lama Nyeri</h1>
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="heightinput input" placeholder="" v-model="input.lamanyeri" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 class="mb-3 emr">Kualitas Nyeri</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.kualitasnyeri" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="d_kualitasnyeri" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-6">
              <h1 class="mb-3 emr">Faktor yang memperberat</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="heightinput input" placeholder="" v-model="input.memperberat" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h1 class="mb-3 emr">Faktor yang meringankan nyeri</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="heightinput input" placeholder="" v-model="input.meringankan" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <br>
        <hr><br>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 class="mb-3 emr">KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL</h1>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1 class="mb-12 emr">Gangguan Psikologis</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.gangguanpsikologis" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_gangguanpsikologis" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="display: none !important">
              <h1 class="mb-12 emr">Status Pernikahan</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.statuspernikahan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_statusnikah" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="display: none !important">
              <h1 style="font-weight: bold;">Menikah</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="" v-model="input.menikah" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>kali</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="display: none !important">
              <h1 style="font-weight: bold;">Umur pertama kali menikah</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="" v-model="input.pertamamenikah" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Tahun</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="display: none !important">
              <h1 style="font-weight: bold;">Kawin dengan suami 1</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="" v-model="input.suami1" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Tahun</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="display: none !important">
              <h1 style="font-weight: bold;">ke 2,3</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="" v-model="input.suami2" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Tahun</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 class="mb-12 emr">Masalah perkawinan</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.masalahperkawinan" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_masalahkawin" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 class="emr">Jelaskan</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="heightinput input" placeholder="" v-model="input.keyakinanpribadi" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 class="mb-12 emr">Mengalami kekerasan fisik</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.kekerasanfisik" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_kekerasan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 class="emr">Jelaskan</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="heightinput input" placeholder="" v-model="input.ketkekerasanfisik" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 class="emr">Keyakinan dan nilai pribadi</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="heightinput input" placeholder="" v-model="input.keyakinanpribadi" />
                </VControl>
              </VField>
            </div>
            <!-- <div class="column is-3">
                            <h1 class="mb-12 emr">Pembiayaan kesehatan</h1>
                            <VField class="is-autocomplete-select">
                                <VControl >
                                    <Multiselect v-model="input.pembiayaankesehatan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                        :options="d_pembiayaankesehatan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 class="emr">Kebiasaan adat istiadat yang memengaruhi kesehatan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="heightinput input" placeholder=""
                                        v-model="input.adatistiadat" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3" style="display: none !important">
                            <h1 class="mb-12 emr">Dukungan sosial dari</h1>
                            <VField class="is-autocomplete-select">
                                <VControl >
                                    <Multiselect v-model="input.dukungansosial" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                        :options="d_dukungansosial" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" style="display: none !important">
                            <h1 class="mb-12 emr">Kebiasaan ibu</h1>
                            <VField class="is-autocomplete-select">
                                <VControl >
                                    <Multiselect v-model="input.kebiasaanibu" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                        :options="d_kebiasaanibu" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 class="mb-12 emr">Perlu rohaniawan</h1>
                            <VField class="is-autocomplete-select">
                                <VControl >
                                    <Multiselect v-model="input.rohaniawan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                        :options="d_rohaniawan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div> -->
          </div>
        </div>

        <br>
        <hr><br>

        <Fieldset :toggleable="true" legend="F. SKRINNING NUTRISI">
          <div class="column is-12" style="overflow: auto;">
            <table class="tg2">
              <thead>
                <tr>
                  <th></th>
                  <th style="text-align: center;vertical-align: middle;">Skor</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, rowIndex) in detailSkriningNutrisi" :key="rowIndex">
                  <td width="200px" v-for="(item, itemIndex) in row.child" :key="itemIndex" :colspan="item.colspan"
                    :rowspan="item.rowspan" :style="item.style">
                    <VField style="padding: 0px 10px;" v-if="item.type === 'textbox'">
                      <VControl raw subcontrol>
                        <input v-model="input.jumlahNilaiSN" class="input" disabled />
                      </VControl>
                    </VField>
                    <VField style="padding: 0px 10px;" v-if="item.type === 'nilai'">
                      <h1>{{ item.caption }}</h1>
                    </VField>
                    <VField style="padding: 0px 10px;" v-if="item.type === 'text'">
                      <span>{{ item.caption }}</span>
                    </VField>
                    <VControl raw subcontrol v-if="item.type === 'checkbox'">
                      <VCheckbox class="p-0" color="primary" square :true-value="item.caption" :label="item.caption"
                        v-model="input['checkboxSN_' + rowIndex + '_' + itemIndex]" />
                    </VControl>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="column is-12 columns is-multiline">
            <div class="column is-12">
              <span>Pasien dengan diagnosa khusus : </span>
            </div>
            <div class="column is-6">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya" v-model="input.CByaPDDK" />
              </VControl>
            </div>
            <div class="column is-6">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                  v-model="input.CBtidakPDDK" />
              </VControl>
            </div>
            <div class="column is-12">
              <span>Nilai : </span>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Risiko rendah (MST 0-1)"
                  label="Risiko rendah (MST 0-1)" v-model="input.CBrisikoRendahPDDK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Risiko sedang (MST 2-3)"
                  label="Risiko sedang (MST 2-3)" v-model="input.CBrisikoSedangPDDK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Risiko tinggi (MST 4-5)"
                  label="Risiko tinggi (MST 4-5)" v-model="input.CBrisikoTinggiPDDK" />
              </VControl>
            </div>
          </div>
        </Fieldset>

        <br>
        <hr><br>

        <Fieldset :toggleable="true" legend="G. STATUS FUNGSIONAL">
          <div class="column" style="overflow: auto;">
            <table class="tg">
              <thead>
                <tr>
                  <th style="text-align: center;vertical-align: middle;" rowspan="2">No</th>
                  <th style="text-align: center;vertical-align: middle;" rowspan="2">Fungsi</th>
                  <th style="text-align: center;vertical-align: middle;" colspan="4">Skor</th>
                  <th style="text-align: center;vertical-align: middle;" rowspan="2">Skor</th>
                </tr>
                <tr>
                  <th style="text-align: center;vertical-align: middle;">0</th>
                  <th style="text-align: center;vertical-align: middle;">1</th>
                  <th style="text-align: center;vertical-align: middle;">2</th>
                  <th style="text-align: center;vertical-align: middle;">3</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in statusFungsional.statusFungsional" :key="index">
                  <td>
                    <h1 style="font-weight: bold;">{{ index + 1 }}<br></h1>
                  </td>
                  <td>
                    <h1 style="font-weight: bold;">{{ item.fungsi }}<br></h1>
                  </td>
                  <td v-for="(detail, indexDetail) in item.detail" :key="indexDetail">
                    <VField v-if="detail.type == 'checkbox'">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :label="detail.caption"
                          :true-value="detail.caption" v-model="input[`checkBox_${item.fungsi}_${indexDetail}`]" />
                      </VControl>
                    </VField>
                    <VField v-if="detail.type == 'textbox'">
                      <VControl raw subcontrol>
                        <VInput v-model="input[`textbox_${item.fungsi}_${indexDetail}`]" class="input"
                          :placeholder="`${detail.caption} ${item.fungsi}`" />
                      </VControl>
                    </VField>
                    <VField v-if="detail.type == 'skor'">
                      <VControl raw subcontrol>
                        <VInput v-model="input[`textbox_${item.fungsi}_skor`]" class="input"
                          :placeholder="detail.caption" disabled />
                      </VControl>
                    </VField>

                  </td>
                </tr>
                <tr>
                  <td colspan="5">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <h1>Keterangan</h1>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan total (0-4)"
                            label="Ketergantungan total (0-4)" v-model="input.CBKetergantunganTotal" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan berat (5-8)"
                            label="Ketergantungan berat (5-8)" v-model="input.CBKetergantunganBerat" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan sedang (9-11)"
                            label="Ketergantungan sedang (9-11)" v-model="input.CBKetergantunganSedang" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan ringan(12-19)"
                            label="Ketergantungan ringan(12-19)" v-model="input.CBKetergantunganRingan" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Mandiri (20)" label="Mandiri (20)"
                            v-model="input.CBKMandiriK" />
                        </VControl>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="column" style="text-align:center;">
                      <span>Total</span>
                    </div>
                  </td>
                  <td>
                    <div class="column">&nbsp;</div>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBtotal2SF" disabled />
                    </VControl>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Fieldset>

        <br>
        <hr><br>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 class="mb-3 emr">ASESMEN RISIKO JATUH</h1>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <h1 class="mb-12 emr">Morse Fall Scale :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.morserendah"
                        true-value="Risiko Rendah: 0 - 7" label="Risiko Rendah: 0 - 7" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.morsesedang"
                        true-value="Risiko Sedang: 8 - 13" label="Risiko Sedang: 8 - 13" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.morsetinggi"
                        true-value="Risiko Tinggi: >14" label="Risiko Tinggi: >14" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-12 emr">Humty Dumpty :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.humtyrendah"
                        true-value="Risiko Rendah: 7 - 11" label="Risiko Rendah: 7 - 11" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.humtytinggi"
                        true-value="Risiko Tinggi: >= 12" label="Risiko Tinggi: >= 12" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3"></div>
              </div>
            </div>
          </div>
        </div>

        <br>
        <hr><br>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1 class="mb-3 emr">RIWAYAT PENGGUNAAN OBAT</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatobat" rows="3">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <br>
        <hr><br>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 class="mb-3 emr">RENCANA PEMULANGAN PASIEN</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.perlurencana" true-value="Perlu"
                        label="Perlu" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakperlurencana"
                        true-value="Tidak Perlu" label="Tidak Perlu" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.perlu1" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.perlu2" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4"></div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.perlu3" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.perlu4" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4"></div>

              </div>
            </div>
          </div>
        </div>

        <br>
        <hr><br>

        <!-- <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 class="mb-3 emr">DIAGNOSA KEPERAWATAN</h1>
                  <VField>
                    <input type="text" v-model="filterMenu" class="input" placeholder="Search..." />
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.nyeriakut" true-value="Nyeri akut b/d kondisi fisik"
                    label="Nyeri akut b/d kondisi fisik" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.bersihan"
                    true-value="Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan"
                    label="Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.risikojantung"
                    true-value="Risiko /Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel"
                    label="Risiko /Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.risikokekurangan"
                    true-value="Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif"
                    label="Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif" color="primary"
                    circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.kurangpengetahuan"
                    true-value="Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpajannya informasi"
                    label="Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpajannya informasi"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.ansietas"
                    true-value="Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi"
                    label="Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi" color="primary" circle />
                  <br>
                  <VCheckbox class="fontcheckbox" v-model="input.integritas"
                    true-value="Risiko gangguan integritas kulit" label="Risiko gangguan integritas kulit"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.kelebihanvolume"
                    true-value="Kelebihan volume cairan b/d asupan cairan berlebihan"
                    label="Kelebihan volume cairan b/d asupan cairan berlebihan" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.kesiapan"
                    true-value="Kesiapan meningkatkan status kesehatan" label="Kesiapan meningkatkan status kesehatan"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.ketidakefektifan"
                    true-value="Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif"
                    label="Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif" color="primary" circle />
                  <br>
                  <VCheckbox class="fontcheckbox" v-model="input.hambatan"
                    true-value="Hambatan mobilitas fisik b/d intoleran aktivitas"
                    label="Hambatan mobilitas fisik b/d intoleran aktivitas" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.diareakut"
                    true-value="Diare akut b/d mal absorbsi, peningkatan motilitas usus"
                    label="Diare akut b/d mal absorbsi, peningkatan motilitas usus" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.nausea"
                    true-value="Nausea b/d biofisik, psikologis, pemberian kemotherapi, pemberian steroid"
                    label="Nausea b/d biofisik, psikologis, pemberian kemotherapi, pemberian steroid" color="primary"
                    circle />
                  <br>
                  <VCheckbox class="fontcheckbox" v-model="input.kadarglukosa"
                    true-value="Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang menejemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat"
                    label="Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang menejemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.hipertermia"
                    true-value="Hipertermia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi"
                    label="Hipertermia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi" color="primary"
                    circle />
                  <br>
                  <VCheckbox class="fontcheckbox" v-model="input.fungsigigi" true-value="Gangguan fungsi gigi"
                    label="Gangguan fungsi gigi" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.jaringankeras"
                    true-value="Gangguan jaringan keras gigi" label="Gangguan jaringan keras gigi" color="primary"
                    circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.jaringanlunak"
                    true-value="Gangguan jaringan lunak dan pendukung gigi"
                    label="Gangguan jaringan lunak dan pendukung gigi" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.estetika" true-value="Gangguan estetika"
                    label="Gangguan estetika" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.sensori" true-value="Gangguan persepsi sensori"
                    label="Gangguan persepsi sensori" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.riwayatjatuh"
                    true-value="Risiko jatuh b/d riwayat terjatuh/usia lebih dari 65th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan"
                    label="Risiko jatuh b/d riwayat terjatuh/usia lebih dari 65th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.polaasi"
                    true-value="Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan"
                    label="Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan" color="primary"
                    circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.lainnya1" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textLainnya1" />
                    </VControl>
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.lainnya2" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textLainnya2" />
                    </VControl>
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.lainnya3" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textLainnya3" />
                    </VControl>
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.lainnya4" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textLainnya4" />
                    </VControl>
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.lainnya5" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textLainnya5" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <div class="column is-12 forCB">
            <h1 style="font-size: larger; font-weight: bold">DIAGNOSA KEPERAWATAN</h1>
            <!-- <div class="control">
              <input type="text" v-model="filterMenu" class="input" placeholder="Search..." />
            </div> -->

            <MultiSelect v-model="item.diagnosaSelected" :options="diagnosesOptions" optionLabel="text" 
            placeholder="Pilih Diagnosa" display="chip" class="w-full" filter 
            @change="updateDiagnosaInput($event)">
              <!-- <template #option="slotProps">
                  <div class="flex align-items-center">
                      <div>{{ slotProps.option.name }}</div>
                  </div>
              </template> -->
              <template #footer>
                  <div class="py-2 px-3">
                      <b>{{ item.diagnosaSelected ? item.diagnosaSelected.length : 0 }}</b> item{{ (item.diagnosaSelected ? item.diagnosaSelected.length : 0) > 1 ? 's' : '' }} selected.
                  </div>
              </template>
            </MultiSelect>
            <div class="control" style="margin-left: 10px;margin-top:10px;" v-for="(diagselected, indexDiag) in item.diagnosaSelected" :key="indexDiag">
              <span v-if="diagselected.isShow && diagselected.value != 'lainnya1'"> {{ indexDiag + 1 + '. ' + diagselected.text }} </span>
              <div class="is-flex" v-if="diagselected.isShow && diagselected.value == 'lainnya1'">
                <span class="mr-1">{{ indexDiag + 1 + '.' }}</span>
                <textarea v-model="input.textLainnya1" class="textarea" style="width: 10%;"
                placeholder="Tuliskan diagnosis lainnya..."></textarea>
              </div>
            </div>
            <!-- <div v-for="diagnosis in filteredDiagnoses" :key="diagnosis.value" class="checkbox-container">
              <VCheckbox class="fontcheckbox" v-model="input[diagnosis.value]" :true-value="diagnosis.text"
                color="primary" circle />
              <span v-html="highlightMatch(diagnosis.text)" class="highlighted-label"></span><br />

              <div v-if="diagnosis.value === 'lainnya1' && input.lainnya1">
                <textarea v-model="input.textLainnya1" class="textarea"
                  placeholder="Tuliskan diagnosis lainnya..."></textarea>
              </div>
            </div> -->
          </div>
          <hr>

        <br>
        <hr><br>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 class="mb-3 emr">RENCANA KEPERAWATAN</h1>
                  <VCheckbox class="fontcheckbox" v-model="input.istirahatkan"
                    true-value="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
                    label="Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.berikaninfo"
                    true-value="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
                    label="Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.bantupasien"
                    true-value="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
                    label="Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.observasi" true-value="Observasi tanda-tanda vital"
                    label="Observasi tanda-tanda vital" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.ajarkan"
                    true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung"
                    label="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.monitor"
                    true-value="Monitor Frekuensi nafas pasien/ status oksigen pasien"
                    label="Monitor Frekuensi nafas pasien/ status oksigen pasien" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.posisikan"
                    true-value="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)"
                    label="Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)" color="primary"
                    circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.latihanbatuk" true-value="Latihan teknik batuk efektif"
                    label="Latihan teknik batuk efektif" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.chest"
                    true-value="Lakukan chest fisioterapi sesuai indikasi/bila perlu"
                    label="Lakukan chest fisioterapi sesuai indikasi/bila perlu" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.berikie"
                    true-value="Beri KIE tentang tanda-tanda penurunan curah jantung"
                    label="Beri KIE tentang tanda-tanda penurunan curah jantung" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.latihrentang"
                    true-value="Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot"
                    label="Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.edukasi"
                    true-value="Edukasi untuk memberikan kompres dengan air biasa/ hangat"
                    label="Edukasi untuk memberikan kompres dengan air biasa/ hangat" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.kaji"
                    true-value="Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi"
                    label="Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.sarankan"
                    true-value="Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein"
                    label="Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.imunisasi"
                    true-value="Lakukan manajemen imunisasi/vaksinasi" label="Lakukan manajemen imunisasi/vaksinasi"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.dukungan"
                    true-value="Beri dudkungan dalam mengambil keputusan"
                    label="Beri dudkungan dalam mengambil keputusan" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.kontrol"
                    true-value="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
                    label="Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.kaji" true-value="Kaji integritas kulit"
                    label="Kaji integritas kulit" color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.ajarkanteknik"
                    true-value="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung"
                    label="Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progresif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.identifikasi"
                    true-value="Identifikasi level cemas pada pasien" label="Identifikasi level cemas pada pasien"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.cemas"
                    true-value="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
                    label="Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.prosedur"
                    true-value="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur"
                    label="Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur" color="primary"
                    circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.dekatipasien"
                    true-value="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut"
                    label="Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut" color="primary" circle />
                  <br>
                  <VCheckbox class="fontcheckbox" v-model="input.dengarkan"
                    true-value="Dengarkan pasien dengan penuh perhatian" label="Dengarkan pasien dengan penuh perhatian"
                    color="primary" circle /><br>
                  <VCheckbox class="fontcheckbox" v-model="input.rencanalainnya1" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textrencanaLainnya1" />
                    </VControl>
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.rencanalainnya2" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textrencanaLainnya2" />
                    </VControl>
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.rencanalainnya3" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textrencanaLainnya3" />
                    </VControl>
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.rencanalainnya4" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textrencanaLainnya4" />
                    </VControl>
                  </VField>
                  <VCheckbox class="fontcheckbox" v-model="input.rencanalainnya5" color="primary" circle /><br>
                  <VField>
                    <VControl>
                      <VInput type="text" class="heightinput input" v-model.number="input.textrencanaLainnya5" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <br>
        <hr><br>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h1 class="mb-3 emr">PROSEDUR INVASIF</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.intravena" true-value="Infus intra vena"
                        label="Infus intra vena" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">dipasang di :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pasangintravena" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">tanggal :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VDatePicker v-model="input.tglintravena" mode="date" trim-weeks :max-date="new Date()">
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

                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.cvc" true-value="Central Line (CVC)"
                        label="Central Line (CVC)" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">dipasang di :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pasangcvc" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">tanggal :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VDatePicker v-model="input.tglcvc" mode="date" trim-weeks :max-date="new Date()">
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

                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.cath" true-value="Dower Chateter"
                        label="Dower Chateter" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">dipasang di :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pasangcath" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">tanggal :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VDatePicker v-model="input.tglcath" mode="date" trim-weeks :max-date="new Date()">
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

                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.selangngt" true-value="Selang NGT"
                        label="Selang NGT" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">dipasang di :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pasangngt" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">tanggal :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VDatePicker v-model="input.tglngt" mode="date" trim-weeks :max-date="new Date()">
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

                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.trakeostomy" true-value="Trakeostomy"
                        label="Trakeostomy" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">dipasang di :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pasangtrakeostomy" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">tanggal :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VDatePicker v-model="input.tgltrakeostomy" mode="date" trim-weeks :max-date="new Date()">
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

                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.ett" true-value="ETT/Ventilator"
                        label="ETT/Ventilator" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">dipasang di :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pasangett" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">tanggal :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VDatePicker v-model="input.tglett" mode="date" trim-weeks :max-date="new Date()">
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

                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.lainlainbawahranap"
                        true-value="Lain-lain" label="Lain-lain" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.ketLainlainbawahranap" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">dipasang di :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pasanglainlain" placeholder="" rows="1">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">tanggal :</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VDatePicker v-model="input.tgllainlain" mode="date" trim-weeks :max-date="new Date()">
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
            </div>
          </div>
        </div>

        <br>
        <hr><br>
        <div class="columns">
          <div class="column is-8"></div>
          <div class="column is-4">
            <VField label="Garut">
              <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </VField>
            <div class="column" style="text-align:center;">
              <h1>Tanda Tangan</h1>
              <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.CBBidan" :suggestions="d_pegawai" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
              </VControl>
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
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import * as EMR2 from '../page-emr-plugins/asesmen-awal-keperawatan-igd'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import Dialog from 'primevue/dialog';
import RencanaPP from "../page-emr/perencanaan-pulang-ranap.vue";
import ConfirmDialog from 'primevue/confirmdialog'
import moment from 'moment'
import { useConfirm } from "primevue/useconfirm"
import MultiSelect from 'primevue/multiselect';

useHead({
  title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const NAMA_RUANGAN: any = ref();
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
const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
const checkTemplate: any = ref(false)
const pasien: any = ref({})
const d_pegawai: any = ref([])
const loadData: any = ref(true)
NAMA_RUANGAN.value = route.query.nama_ruangan as string ?? props.registrasi.namaruangan;
const idTemplate: any = ref('');
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

const COLLECTION: any = ref('AsesmenAwalKeperawatanIntensif') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  kebtanggalKedatangan: new Date(),
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }, { value: 3, label: '1-5 kg' }, { value: 4, label: '6-10 kg' }, { value: 5, label: '11-15 kg' }, { value: 6, label: '>15 kg' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const modalRencanaPP: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isDisabled:any  = ref(false)



let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])
let detailSkriningNutrisi = ref(EMR2.detailSkriningNutrisi())
let statusFungsional: any = ref(EMR2.statusFungsional())

// const loadRiwayat = async () => {
//   let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&namaruangan=${NAMA_RUANGAN.value}`)
//   if (response.length) {
//     input.value = response[0] //set ke inputan
//     if (NOREC_EMRPASIEN.value == '') {
//       NOREC_EMRPASIEN.value = response[0].emrpasienfk
//     }
//   }
// }
const filterMenu: any = ref('')
const diagnosesOptions = [
  { text: "Nyeri akut b/d kondisi fisik", value: "nyeriakut", isShow: false },
  { text: "Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan", value: "Bersihan", isShow: false },
  { text: "Risiko / Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel", value: "Risiko", isShow: false },
  { text: "Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif", value: "Risiko Cairan", isShow: false },
  { text: "Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpanjannya informasi", value: "Kurang pengetahuan", isShow: false },
  { text: "Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi", value: "Ansietas b/d", isShow: false },
  { text: "Risiko gangguan integritas kulit", value: "kulit", isShow: false },
  { text: "Kelebihan volume cairan b/d asupan cairan berlebihan", value: "berlebihan", isShow: false },
  { text: "Kesiapan meningkatkan status kesehatan", value: "kesiapan status", isShow: false },
  { text: "Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif", value: "kognitif", isShow: false },
  { text: "Hambatan mobilitas fisik b/d intoleran aktivitas", value: "aktivitas", isShow: false },
  { text: "Diare akut b/d mal absorpsi, peningkatan motilitas usus", value: "usus", isShow: false },
  { text: "Nausea b/d biofisik, psikologis, pemberian kemoterapi, pemberian steroid", value: "steroid", isShow: false },
  { text: "Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang manajemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat", value: "Pemantauan glukosa darah tidak adekuat", isShow: false },
  { text: "Hipertemia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi", value: "termoregulasi", isShow: false },
  { text: "Gangguan fungsi gigi", value: "gangguan fungsi", isShow: false },
  { text: "Gangguan jaringan keras gigi", value: "gangguan keras", isShow: false },
  { text: "Gangguan jaringan lunak dan pendukung gigi", value: "gangguan lunak", isShow: false },
  { text: "Gangguan estetika", value: "gangguan", isShow: false },
  { text: "Gangguan persepsi sensori ", value: "gangguan persepsi", isShow: false },
  { text: "Risiko jatuh b/d riwayat terjatuh / usia lebih dari 65 th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan", value: "sulit penglihatan", isShow: false },
  { text: "Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan", value: "kesehatan", isShow: false },
  { text: "Lainnya", value: "lainnya1", isShow: false }
];

const filteredDiagnoses = computed(() => {
  const term = filterMenu.value.toLowerCase();
  return diagnosesOptions.filter(diagnosis =>
    diagnosis.text.toLowerCase().includes(term)
  );
});
function highlightMatch(text) {
  if (!filterMenu.value) return text;

  const term = new RegExp(`(${filterMenu.value})`, 'gi');
  return text.replace(term, '<span style="background-color: yellow;">$1</span>');
}
const loadRiwayat = async () => {
  try {

    const response = await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&namaruangan=${NAMA_RUANGAN.value}`
    );

    if (response.length > 0) {
      isDisabled.value = false;
      input.value = response[0]; // Set to input
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      item.diagnosaSelected = [];
      for (let iDiag = 0; iDiag < diagnosesOptions.length; iDiag++) {
        const elDiag = diagnosesOptions[iDiag];
        if(input.value[elDiag.value] != undefined && input.value[elDiag.value] != null) {
          elDiag.isShow = true;
          item.diagnosaSelected.push(elDiag);
        }
      }
      // dataTTD.value = response[0];
      // console.log('DATA TTD 1', dataTTD.value);

      // console.log('DATA TTD 2', dataTTD.value.ttdPerawatKanulasiAkses);
      // H.tandaTangan().set("ttdPerawatKanulasiAkses", dataTTD.value.ttdPerawatKanulasiAkses);
      // H.tandaTangan().set("ttdPerawatTerminasi", dataTTD.value.ttdPerawatTerminasi);
      // H.tandaTangan().set("ttdPerawatPenanggungJawab", dataTTD.value.ttdPerawatPenanggungJawab);
    } else {
      isLoading.value = true;

      const responseTglRuangan = await useApi().get(
        `/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
      );

      const responseHistori = await useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`
      );

      isLoading.value = false;

      if (responseTglRuangan.length && responseHistori.length) {
        console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan);

        const tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
        const convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
        const tgl_Sekarang = moment();
        const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
        // console.log('Response Tgl Ruangan:', responseTglRuangan);
        // console.log('Response Histori:', responseHistori);
        console.log('Calculate Days:', calculateDays);


        H.alert(
          'info',
          'Asuhan dan Observasi Hemodialisis sudah di input ' + calculateDays + ' hari dari tanggal registrasi pasien ini.'
        );
        console.log("Ruangan pasien sekarang uhuy: " + props.registrasi.namaruangan.trim());

        if (
          responseTglRuangan[0].registrasi.namaruangan.trim() === props.registrasi.namaruangan.trim() &&
          // responseTglRuangan[0].registrasi.namaruangan.trim() === props.registrasi.namaruangan &&
          // responseTglRuangan[0].registrasi.namaruangan.trim() === H.setObjectRegistrasi(props.registrasi.apd.norec_apd).namaruangan &&
          // responseTglRuangan[0].registrasi.namaruangan.trim() === H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() &&
          calculateDays < 90
        ) {
          // console.log('hai sauynag')
          confirm.require({
            message: `Asuhan dan Observasi Hemodialisis sudah di input ${calculateDays} hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya?`,
            group: 'templating',
            header: 'Asesmen Awal Medis Hemodialisa',
            icon: 'pi pi-exclamation-circle',
            accept: () => {
              if (responseHistori.length) {
                input.value = responseHistori[0];
                item.diagnosaSelected = [];
                for (let iDiag = 0; iDiag < diagnosesOptions.length; iDiag++) {
                  const elDiag = diagnosesOptions[iDiag];
                  if(input.value[elDiag.value] != undefined && input.value[elDiag.value] != null) {
                    elDiag.isShow = true;
                    item.diagnosaSelected.push(elDiag);
                  }
                }
                input.value.namatemplate = '';
                isLoading.value = false;
              } else {
                H.alert('warning', 'Data tidak ada');
                isLoading.value = false;
              }
            },
            reject: () => {
              isLoading.value = false;
            }
          });
        }
      } else {
        H.alert('warning', 'Data EMR sebelumnya tidak ada!');
        isLoading.value = false;
      }

      H.alert('info', 'Ruangan Pasien saat ini: ' + props.registrasi.namaruangan);
    }
  } catch (error) {
    console.error('Error loading riwayat:', error);
    isLoading.value = false;
    H.alert('error', 'Terjadi kesalahan saat memuat data.');
  }
}

const simpan = () => {

  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  if (route.query.nama_ruangan) {
      object.registrasi = H.setObjectRegistrasi(input.value.registrasi)
      object.pasien = H.setObjectPasien(input.value.pasien)
  } else {
      object.registrasi = H.setObjectRegistrasi(props.registrasi)
      object.pasien = H.setObjectPasien(pasien.value)
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
      if(input.value.perlurencana == "Perlu"){
          modalRencanaPP.value = true
          isLoading.value = false
        }
      NOREC_EMRPASIEN.value = response.norec_emr
      // NOREC_EMRPASIEN.value = response.norec_emr
      // isLoading.value = false

      // if(input.value.perlurencana == "Perlu") {
      //   modalRencanaPP.value = true
      // }
    }).catch((e: any) => {
      isLoading.value = false
    })

  // console.log(resultValue)
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
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

const kembaliKeun = () => {
  window.history.back()
}

const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate','namaPasien','tanggalLahirPasien','jeniskelamin','norm','MRS_ODC','tglMRS','tglODC','namaruangan','tb','bb','bsa']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
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

const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}

const getDataExist = async () => {
  await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {

    if (response != null || response != undefined) {
      input.value.beratbadanObgyn = response.beratBadan
      input.value.tinggibadanObgyn = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.nadiObgyn = response.nadi
      input.value.celciusObgyn = response.suhu
      input.value.tekananDarahObgyn = response.tekananDarah
      input.value.nafasObgyn = response.pernapasan
      input.value.sao2Obgyn = response.SPO2
    }
  })
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
    if (!route.query.nama_ruangan) {
      let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
      if (cache) input.value = cache
    }
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    if (!route.query.nama_ruangan) {
      H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    }
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});


getDataExist()
fetchPasien()

watch(() => [
  input.value.penurunanBB,
  input.value.penurunanNafsuMakan,
], () => {

  let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
  let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

  const total = poin1 + poin2
  input.value.totalNilaiMST = total

})

watch(
  () => Object.keys(input.value).filter(key => key.startsWith('checkboxSN_')).map(key => input.value[key]),
  (newValues) => {
    let sum = 0
    newValues.forEach((checkboxValue, index) => {
      const [_, rowIndex, itemIndex] = Object.keys(input.value).filter(key => key.startsWith('checkboxSN_'))[index].split('_')
      const nilaiRow = detailSkriningNutrisi.value[parseInt(rowIndex)]?.child?.[parseInt(itemIndex) + 1]
      if (checkboxValue && nilaiRow && !isNaN(parseInt(nilaiRow.caption))) {
        sum += parseInt(nilaiRow.caption)
      }
    })
    input.value.jumlahNilaiSN = sum
  },
  { deep: true }
)

watch(
  () => input.value.jumlahNilaiSN,
  (newValue) => {
    if (newValue >= 0 && newValue <= 1) {
      input.value.CBrisikoRendahPDDK = "Risiko rendah (MST 0-1)";
      input.value.CBrisikoSedangPDDK = null;
      input.value.CBrisikoTinggiPDDK = null;
    } else if (newValue >= 2 && newValue <= 3) {
      input.value.CBrisikoRendahPDDK = null;
      input.value.CBrisikoSedangPDDK = "Risiko sedang (MST 2-3)";
      input.value.CBrisikoTinggiPDDK = null;
    } else if (newValue >= 4) {
      input.value.CBrisikoRendahPDDK = null;
      input.value.CBrisikoSedangPDDK = null;
      input.value.CBrisikoTinggiPDDK = "Risiko tinggi (MST 4-5)";
    }
  }
);

watch(
  () => input.value,
  (newValue) => {
    let totalAllSkor = 0;

    statusFungsional.value.statusFungsional.forEach((item, indexFungsi) => {
      let totalSkor = 0;

      item.detail.forEach((detail, indexDetail) => {
        if (detail.type === "checkbox" && input.value[`checkBox_${item.fungsi}_${indexDetail}`]) {
          const value = item.detail.find(d => d.caption === input.value[`checkBox_${item.fungsi}_${indexDetail}`])?.value;
          totalSkor += parseInt(detail.value, 10);
        }

        if (detail.type === "textbox" && input.value[`textbox_${item.fungsi}_${indexDetail}`]) {
          const value = item.detail.find(d => d.caption === "Ket")?.value;
          totalSkor += parseInt(detail.value, 10);
        }
      });

      const skorDetail = item.detail.find(d => d.type === "skor");
      if (skorDetail) {
        input.value[`textbox_${item.fungsi}_skor`] = totalSkor;
        totalAllSkor += totalSkor;
      }
    });

    input.value.TBtotal2SF = totalAllSkor;
  },
  { deep: true }
);

watch(
  () => input.value.TBtotal2SF,
  (newValue) => {

    if (newValue >= 0 && newValue <= 4) {
      input.value.CBKetergantunganTotal = "Ketergantungan total (0-4)";
      input.value.CBKetergantunganBerat = false;
      input.value.CBKetergantunganSedang = false;
      input.value.CBKetergantunganRingan = false;
      input.value.CBKMandiriK = false;
    } else if (newValue >= 5 && newValue <= 8) {
      input.value.CBKetergantunganTotal = false;
      input.value.CBKetergantunganBerat = "Ketergantungan berat (5-8)";
      input.value.CBKetergantunganSedang = false;
      input.value.CBKetergantunganRingan = false;
      input.value.CBKMandiriK = false;
    } else if (newValue >= 9 && newValue <= 11) {
      input.value.CBKetergantunganTotal = false;
      input.value.CBKetergantunganBerat = false;
      input.value.CBKetergantunganSedang = "Ketergantungan sedang (9-11)";
      input.value.CBKetergantunganRingan = false;
      input.value.CBKMandiriK = false;
    } else if (newValue >= 12 && newValue <= 19) {
      input.value.CBKetergantunganTotal = false;
      input.value.CBKetergantunganBerat = false;
      input.value.CBKetergantunganSedang = false;
      input.value.CBKetergantunganRingan = "Ketergantungan ringan (12-19)";
      input.value.CBKMandiriK = false;
    } else if (newValue >= 20) {
      input.value.CBKetergantunganTotal = false;
      input.value.CBKetergantunganBerat = false;
      input.value.CBKetergantunganSedang = false;
      input.value.CBKetergantunganRingan = false;
      input.value.CBKMandiriK = "Ketergantungan mandiri (20 keatas)";
    }
  }
);
const setAutoFill = async () => {
  const fieldsVitalSign = "tinggiBadan,IMT,lingkarPerut,tekananDarah,keadaanumumobgyn,keadaanumum,pernapasan,suhu,nadi,beratBadan,SPO2";
  const fieldsAsesmen = "tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn";

  const fetchData = async (collection, fields) => {
    return await useApi().get(`emr/auto-fill?norec_pd=${props.registrasi.norec_pd}&collection=${collection}&field=${fields}`);
  };

  const parseResponse = (response) => {
    if (!response) return "";

    const fieldsMap = {
      celciusObgyn: "Suhu : ",
      nadiObgyn: "Nadi : ",
      nafasObgyn: "Pernafasan : ",
      tekananDarahObgyn: "Tekanan Darah : ",
      tinggibadanObgyn: "Tinggi Badan : ",
      beratbadanObgyn: "Berat Badan : ",
      sao2Obgyn: "SPO2 : ",
      IMT: "IMT : ",
      kebpilihanallo: "Kebutuhan Pilihan Allo : ",
      keluhanutama: "Keluhan Utama : ",
      riwayatpenyakit: "Riwayat Penyakit : ",
      riwayatpenyakitdahulu: "Riwayat Penyakit Dahulu : ",
      riwayatpengobatan: "Riwayat Pengobatan : ",
      riwayatpenyakitkeluarga: "Riwayat Penyakit Keluarga : ",
      riwayatalergi: "Riwayat Alergi : "
    };

    let data = "";
    Object.entries(fieldsMap).forEach(([key, label]) => {
      if (response[key]) data += `     ${label}${response[key]}\n`;
    });

    return data;
  };

  const setValues = (response) => {
    if (!response) return;

    input.value = {
      ...input.value,
      tekananDarahObgyn: response.tekananDarahObgyn || response.tekananDarah,
      nadiObgyn: response.nadiObgyn || response.nadi,
      nafasObgyn: response.nafasObgyn || response.pernapasan,
      celciusObgyn: response.celciusObgyn || response.suhu,
      sao2Obgyn: response.sao2Obgyn || response.SPO2,
      gcse: response.gcse,
      gcsv: response.gcsv,
      gcsm: response.gcsm,
      kebpilihanallo: response.kebpilihanallo,
      keadaanumum: response.keadaanumumobgyn || response.keadaanumum,
      beratbadanObgyn: response.beratbadanObgyn || response.beratBadan,
      tinggibadanObgyn: response.tinggibadanObgyn || response.tinggiBadan,
      anamnesis: parseResponse(response),
    };
  };

  let response = await fetchData("VitalSign", fieldsVitalSign);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKebidananRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalan", fieldsAsesmen);

  setValues(response);
}

const updateDiagnosaInput = (event) => {
  console.log('diagnosa item', item.diagnosaSelected);
  for (let index = 0; index < diagnosesOptions.length; index++) {
    const element = diagnosesOptions[index];
    let findDiagnosaSelected = event.value.find((dtf) => {
      return dtf.value === element.value;
    })

    if(findDiagnosaSelected) {
      element.isShow = true;
      input.value[element.value] = element.text;
    }else {
      element.isShow = false;
      input.value[element.value] = undefined;
    }

  }
}

setAutoFill();
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

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
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

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}


.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg2 td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
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
  vertical-align: middle
}
</style>
