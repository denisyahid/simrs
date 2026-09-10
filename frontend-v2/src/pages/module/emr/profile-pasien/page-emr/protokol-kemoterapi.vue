<template>
  <div class="form-layout is-stacked-2">
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
        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-1 mb-1">
        <div style="text-align: center;font-size: large;font-weight: bold;">
          <VTag :class="isSave ? 'has-background-success' : 'has-background-danger'" style="color:white;width: 100%;font-size: large;">
            {{ isSave ? 'Form Sudah Tersimpan / Data Sudah Ada' : 'Form Belum Tersimpan' }}
          </VTag>
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

      <div class="column isprotokol-kemo">
        <div class="columns is-multiline">
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

        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-3">

        <div class="form-header-inner pt-3">
          <div class="left">
            <Dialog v-model:visible="showSuratObatKhususKemo" maximizable modal header="Surat Permintaan Penggunaan Obat Khusus Kemoterapi" :style="{ width: '70vw' }">
                    <div class="column is-12">
                      <div class="column is-flex p-0">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">Nama Pasien :</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VControl>
                              <VInput v-model="suratkemo.namaPasien" class="input" disabled />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-flex p-0">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">No RM :</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VControl>
                              <VInput v-model="suratkemo.norm" class="input" disabled />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-flex p-0">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">Tanggal Lahir Pasien :</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VDatePicker v-model="suratkemo.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" disabled />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-flex p-0">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">Jenis Kelamin : </h1>
                        </div>
                        <div class="column is-10" style="display: flex">
                          <VField v-for="items in JenisKelamin" :key="items.value">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="suratkemo.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                                :label="items.label" color="primary" disabled circle />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class=" is-flex">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">Cara Bayar :</h1>
                        </div>
                        <div class="is-10 is-flex">
                          <VField>
                            <VControl>
                              <VCheckbox color="primary" label="JKN" v-model="suratkemo.caraBayar" true-value="JKN" />
                            </VControl>
                          </VField>

                          <VField>
                            <VControl>
                              <VCheckbox color="primary" label="IKS" v-model="suratkemo.caraBayar" true-value="IKS" />
                            </VControl>
                          </VField>

                          <VField>
                            <VControl>
                              <VCheckbox color="primary" label="Umum" v-model="suratkemo.caraBayar" true-value="Umum" />
                            </VControl>
                          </VField>

                          <VField>
                            <VControl>
                              <VCheckbox color="primary" label="WNA" v-model="suratkemo.caraBayar" true-value="WNA" />
                            </VControl>
                          </VField>

                          <VField>
                            <VControl>
                              <VCheckbox color="primary" label="Lainnya" v-model="suratkemo.caraBayar" true-value="Lainnya" />
                            </VControl>
                          </VField>

                          <VField>
                            <VControl>
                              <VInput placeholder="Lainnya" v-model="suratkemo.caraBayarLainnya" />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-flex p-0">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">Diagnosis :</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VControl>
                              <VTextarea v-model="suratkemo.diagnosis" class="input" />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-12">
                        <div class="columns is-multiline">
                          <div class="column is-12 pb-0">
                            <h1 class="bold" style="font-size:larger;">
                              PEMERIKSAAN FISIK :
                            </h1>
                          </div>
                          <div class="column is-3">
                            <h1>Tekanan Darah</h1>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="suratkemo.tekananDarahObgyn" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <h1>Nadi</h1>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model="suratkemo.nadiObgyn" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <h1>RR</h1>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model="suratkemo.rr" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static></VButton>
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-3">
                            <h1>Suhu</h1>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model="suratkemo.celciusObgyn" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>°C </VButton>
                              </VControl>
                            </VField>
                          </div>

                          <div class="column is-3">
                            <h1>Perfomance Status</h1>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model="suratkemo.perfomanceStatus" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>Ket</VButton>
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>

                      <Fieldset legend="Data Penunjang" :toggleable="true">
                        <div class="columns is-12">
                          <div class="column is-12 pb-0">
                            <h1 class="bold" style="font-size:larger;">
                              - Hasil PA :
                            </h1>
                            <div class="column is-flex p-0">
                              <div class="column is-2">
                                <h1 style="font-weight: bold">- Biopsi PA:</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VTextarea v-model="suratkemo.biopsi" class="input" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>

                            <div class="column is-flex p-0">
                              <div class="column is-2">
                                <h1 style="font-weight: bold">- IHC dan Hormonal:</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VTextarea v-model="suratkemo.ihc" class="input" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>

                            - Hasil Lboratorium (dilampirkan)
                            <div class="column is-flex p-0">
                              <div class="column is-2">
                                <h1 style="font-weight: bold">- DL</h1>
                              </div>
                              <div class="column is-10"></div>
                            </div>

                            <div class="column is-flex p-0">
                              <div class="column is-12">
                                <h1 style="font-weight: bold">- Kimia Darah (SGOT, SGPT, Albumin, Globulin, Bilirubin Total,
                                  Bilirubin
                                  Direk,
                                  BUN, Serum
                                  Creatinin, Uric Acid, LDH, UL, Elektrolit</h1>
                              </div>
                            </div>

                            <div class="column is-flex p-0">
                              <div class="column is-12">
                                <h1 style="font-weight: bold">- HbS Ag, Anti HCV, Anti HIV</h1>
                              </div>
                            </div>

                            <div class="column is-flex p-0">
                              <div class="column is-12">
                                <h1 style="font-weight: bold">- Foto Rontgen / CT Scans / USG</h1>
                              </div>
                            </div>
                            <div class="column is-12">


                              <div class="columns is-multiline mt-5" v-if="isLoading">
                                <VPlaceloadText :lines="1" class="p-2" />
                                <div class="column is-12" v-for="key in 2" :key="key">
                                  <VPlaceloadWrap>
                                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                  </VPlaceloadWrap>
                                </div>
                              </div>
                              <div class="columns is-multiline" v-else>
                                <div class="column is-12" v-if="dataSource.length">
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <BerkasPasienView :data="dataSource" @edit="edit" @hapus="hapus" @lihat="lihat" :hide="false">
                                      </BerkasPasienView>
                                    </div>
                                  </div>
                                </div>
                                <div class="column is-12" v-else>
                                  <div class="page-placeholder">
                                    <div class="placeholder-content">
                                      <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                        alt="" />
                                      <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                        alt="" />
                                      <h3>{{ H.assets().notFound }}</h3>
                                      <p class="is-larger">
                                        {{ H.assets().notFoundSubtitle }}
                                      </p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="column is-flex p-0">
                              <div class="column is-2">
                                <h1 style="font-weight: bold">- Lain - lain</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput v-model="suratkemo.lainDataPenunjang" class="input" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </div>
                      </Fieldset>

                      <Fieldset legend="Obat Khusus Yang Akan Diberikan" :toggleable="true">
                        <div class="column is-flex p-0">
                          <div class="column is-2">
                            <h1 style="font-weight: bold">Nama Obat Keras</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VInput v-model="suratkemo.namaobatOral" class="input" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-flex p-0">
                          <div class="column is-2">
                            <h1 style="font-weight: bold">Dosis</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VInput v-model="suratkemo.dosisObat" class="input" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-flex p-0">
                          <div class="column is-2">
                            <h1 style="font-weight: bold">Lama Pemberian</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VInput v-model="suratkemo.lamaPemberian" class="input" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-flex p-0">
                          <div class="column is-12">
                            <h1 style="font-weight: bold">Obat terapi sistemik intravena, subcutan di tulis di halaman berikutnya.
                            </h1>
                          </div>
                        </div>

                        <div class="column is-flex p-0">
                          <div class="column is-2">
                            <h1 style="font-weight: bold">Alasan Pemberian</h1>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VControl>
                                <VTextarea v-model="suratkemo.alasanPemberian" class="input" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </Fieldset>

                      <div class="columns is-multiline" style="margin-top: 1rem;">
                        <div class="column is-12">
                          <div class="column is-3 is-flex">
                            <h1 style="font-weight: bold" class="mr-2">Tanggal, </h1>
                            <VField>
                              <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                  <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                                    </VControl>
                                  </VField>
                                </template>
                              </VDatePicker>
                            </VField>
                          </div>
                          <div class="columns is-12" style="justify-content: space-between;">
                            <div class="column is-3" style="text-align: center;">
                              <h1 style="font-weight: bold;">Dokter Penanggung Jawab Pasien, </h1>
                              <TandaTangan :elemenID="'dpjpPasien'" :width="'180'" :height="'180'"></TandaTangan>
                              <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                                <VControl icon="feather:search">
                                  <AutoComplete v-model="suratkemo.dokterRawat" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                                </VControl>
                              </VField>
                            </div>

                            <div class="column is-3" style="text-align: center;">
                              <h1 style="font-weight: bold;"> Konsultan Hemato Onkologi Medik,</h1>
                              <TandaTangan :elemenID="'konsultanHemato'" :width="'180'" :height="'180'"></TandaTangan>
                              <VField class="mt-3">
                                <VControl>
                                  <AutoComplete v-model="suratkemo.konsultan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                                </VControl>
                              </VField>
                            </div>

                          </div>

                        </div>
                      </div>

                      <div class="columns is-multiline" style="margin-top: 1rem;">
                        <div class="column is-12">
                          <div class="columns is-12" style="justify-content: center;">
                            <div class="column is-3" style="text-align: center;">
                              <h1 style="font-weight: bold;">Mengetahui, <br>Wakil Direktur Pelayanan <br>RSUD Bali Mandara</h1>
                              <TandaTangan :elemenID="'wadir'" :width="'180'" :height="'180'"></TandaTangan>
                              <VField class="is-autocomplete-select pt-3">
                                <VControl>
                                  <AutoComplete v-model="suratkemo.wakilDirektur" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <template #footer>
                      <VButton icon="feather:save" class="rem-100 mr-4" color="info" @click="updateSuratKemo()" :loading="isLoading">
                        Simpan
                      </VButton>
                      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showSuratObatKhususKemo = false">
                        Tutup
                      </VButton>
                      <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                        :loading="isLoading" @click="simpanReal"> Simpan
                      </VButton> -->
                    </template>
                  </Dialog>
                  <Dialog v-model:visible="showSediaanKemo" maximizable modal header="Pencampuran Sediaan Kemo" :style="{ width: '70vw' }">
                    <div class="column is-12">
                      <div class="column is-12 is-flex">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">NAMA PASIEN:</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VControl>
                              <VInput v-model="sediaankemo.namaPasien" class="input" type="text" disabled />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-12 is-flex">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VDatePicker v-model="sediaankemo.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal" disabled />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-12 is-flex">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
                        </div>
                        <div class="column is-10" style="display: flex">
                          <VField v-for="items in JenisKelamin" :key="items.value">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="sediaankemo.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                                :label="items.label" color="primary" disabled circle />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                      <div class="column is-12 is-flex">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">No. Rekam Medis:</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="sediaankemo.norm" disabled />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-12 is-flex">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">Ruangan:</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="sediaankemo.ruangan" :suggestions="d_Ruangan" :optionLabel="'label'"
                                @complete="fetchRuangan($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..." class="mt-2" />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="column is-12 is-flex">
                        <div class="column is-2">
                          <h1 style="font-weight: bold">CARA BAYAR:</h1>
                        </div>
                        <div class="column is-10">
                          <VField>
                            <VControl>
                              <VInput type="text" class="input" placeholder="Cara Bayar" v-model="sediaankemo.caraBayar" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                      <div class="column is-12 is-flex">
                      <div class="column is-2">
                        <h1 style="font-weight: bold">ALERGI:</h1>
                      </div>
                      <div class="column is-10">
                        <VField>
                          <VControl>
                            <VTextarea type="text" class="input" placeholder="Alergi" v-model="sediaankemo.alergi" />
                          </VControl>
                        </VField>
                      </div>
                      </div>

                      <div class="column is-12">
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <h1 style="font-weight: bold;">Berat Badan</h1>
                                <VField addons>
                                  <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Berat Badan" v-model="sediaankemo.beratbadanObgyn" />
                                  </VControl>
                                  <VControl class="field-addon-body">
                                    <VButton static>kg</VButton>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4">
                                <h1 style="font-weight: bold;">Tinggi Badan</h1>
                                <VField addons>
                                  <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="sediaankemo.tinggibadanObgyn" />
                                  </VControl>
                                  <VControl class="field-addon-body">
                                    <VButton static>cm</VButton>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4">
                                <h1 style="font-weight: bold;">BSA</h1>
                                <VField addons>
                                  <VControl expanded>
                                    <VInput type="text" class="input" placeholder="BSA" v-model="sediaankemo.bsa" />
                                  </VControl>
                                  <VControl class="field-addon-body">
                                    <VButton static>m<sup>2</sup></VButton>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4">
                                <h1 style="font-weight: bold;">Diagnosa</h1>
                                <VField>
                                  <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Diagnosa" v-model="sediaankemo.Diagnosa" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4">
                                <h1 style="font-weight: bold;">Protokol</h1>
                                <VField>
                                  <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Protokol" v-model="sediaankemo.Protokol" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4">
                                <h1 style="font-weight: bold;">Siklus</h1>
                                <VField>
                                  <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Siklus" v-model="sediaankemo.Siklus" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4">
                                <h1 style="font-weight: bold;">Recana Kemoterapi berikutnya</h1>
                                <VField>
                                  <VDatePicker v-model="sediaankemo.tanggalRencanaKemoterapi" mode="date" trim-weeks
                                    :max-date="new Date()">
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
                                <h1 style="font-weight: bold;">Dokter DPJP</h1>
                                <AutoComplete v-model="sediaankemo.dokterPemeriksa" :suggestions="d_Dokter"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="column is-12" style="overflow-x: auto;">
                        <table style="width: 300%; text-align: center;">
                          <thead>
                            <tr>
                              <th class="th-pri" colspan="5">DIISI OLEH DOKTER</th>
                              <th class="th-pri" colspan="4">DIISI OLEH FARMASI</th>
                              <th class="th-pri" colspan="8">DIISI OLEH DOKTER</th>
                              <th class="th-pri" rowspan="2">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Nama Obat <br> (dosis/kg atau dosis/m²)
                              </td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Dosis</td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Cara pemberian</td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Jenis & vol cairan infus</td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Paraf dokter</td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Pelarut</td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Vol obat yg diambil</td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Volume total</td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">Stabilitas</td>
                              <td class="td-pri" colspan="8">Tanggal/bulan Permintaan</td>
                              <td style="vertical-align: middle;" class="td-pri" rowspan="2">

                              </td>
                            </tr>

                            <tr>
                              <template v-for="(i, index) in 8" :key="index">
                                <td class="td-pri">
                                  <VField>
                                    <VControl>
                                      <VDatePicker v-model="input[`tanggalPermintaan${i}`]" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                          <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                          </VField>
                                        </template>
                                      </VDatePicker>
                                    </VControl>
                                  </VField>
                                </td>
                              </template>
                            </tr>
                            <tr v-for="(data, index) in sediaankemo.details" :key="index">
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <AutoComplete v-model="data['obat']" :suggestions="d_ObatRS" @complete="fetchObat($event)"
                                      :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Dosis" v-model="data['dosis']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Cara pemberian"
                                      v-model="data['caraPemberian']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Jenis & vol cairan infus"
                                      v-model="data['jenisVolCairanInfus']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <AutoComplete v-model="data['parafDokter']" :suggestions="d_Dokter"
                                      @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Pelarut" v-model="data['pelarut']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Vol obat yg diambil"
                                      v-model="data['volObatDiambil']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Volume total" v-model="data['volumeTotal']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Stabilitas" v-model="data['stabilitas']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Tanggal/bulan permintaan"
                                      v-model="data['tanggalBulanPermintaan1']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Tanggal/bulan permintaan"
                                      v-model="data['tanggalBulanPermintaan2']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Tanggal/bulan permintaan"
                                      v-model="data['tanggalBulanPermintaan3']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Tanggal/bulan permintaan"
                                      v-model="data['tanggalBulanPermintaan4']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Tanggal/bulan permintaan"
                                      v-model="data['tanggalBulanPermintaan5']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Tanggal/bulan permintaan"
                                      v-model="data['tanggalBulanPermintaan6']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Tanggal/bulan permintaan"
                                      v-model="data['tanggalBulanPermintaan7']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" placeholder="Tanggal/bulan permintaan"
                                      v-model="data['tanggalBulanPermintaan8']" />
                                  </VControl>
                                </VField>
                              </td>
                              <td class="td-pri">
                                <VButtons style="justify-content:space-around">
                                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                                    v-tooltip.bubble="'Tambah '">
                                  </VIconButton>
                                  <VIconButton class="mt-1" v-if="sediaankemo.details.length > 1" type="button" raised circle
                                    icon="feather:trash" @click="removeItem(index)" color="danger">
                                  </VIconButton>
                                </VButtons>
                              </td>
                            </tr>
                          </tbody>
                          <tr>
                            <td colspan="3" class="td-pri">Catatan dokter:</td>
                            <td colspan="4" class="td-pri">Catatan farmasi:</td>
                            <td style="vertical-align: middle;" colspan="2" class="td-pri">Direview oleh</td>
                            <template v-for="(item, index) in 8" :key="index">
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input[`review-${item}`]" />
                                  </VControl>
                                </VField>
                              </td>
                            </template>
                            <td class="td-pri"></td>
                          </tr>
                          <tr>
                            <td class="td-pri" colspan="3" rowspan="3">
                              <VControl>
                                <VTextarea class="textarea" v-model="sediaankemo.catatanDokter" placeholder="Catatan Dokter"></VTextarea>
                              </VControl>
                            </td>
                            <td class="td-pri" colspan="4" rowspan="3">
                              <VControl>
                                <VTextarea class="textarea" v-model="sediaankemo.catatanFarmasi" placeholder="Catatan Farmasi">
                                </VTextarea>
                              </VControl>
                            </td>
                            <td colspan="2" class="td-pri">Dikerjakan oleh</td>
                            <template v-for="(item, index) in 8" :key="index">
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input[`kerjakan-${item}`]" />
                                  </VControl>
                                </VField>
                              </td>
                            </template>
                            <td class="td-pri"></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="td-pri">Diserahkan oleh</td>
                            <template v-for="(item, index) in 8" :key="index">
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input[`serahkan-${item}`]" />
                                  </VControl>
                                </VField>
                              </td>
                            </template>
                            <td class="td-pri"></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="td-pri">Diterima oleh</td>
                            <template v-for="(item, index) in 8" :key="index">
                              <td class="td-pri">
                                <VField>
                                  <VControl>
                                    <VInput type="text" class="input" v-model="input[`terima-${item}`]" />
                                  </VControl>
                                </VField>
                              </td>
                            </template>
                            <td class="td-pri"></td>
                          </tr>
                        </table>
                      </div>

                <div class="column is-12 p-0">
                  <hr class="m-0">
                </div>
                    </div>
                    <template #footer>
                      <VButton icon="feather:save" class="rem-100 mr-4" color="info" @click="updateSediaanKemo()" :loading="isLoading">
                        Simpan
                      </VButton>
                      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="showSediaanKemo = false">
                        Tutup
                      </VButton>
                      <!-- <VButton type="button" rounded outlined color="primary" class="ml-2" raised icon="feather:save"
                        :loading="isLoading" @click="simpanReal"> Simpan
                      </VButton> -->
                    </template>
                  </Dialog>

                  <!-- <div class="right">
                    <div class="buttons">
                      <VButton type="button" rounded outlined color="info" @click="setSuratKemo()" icon="lucide:file-text">
                        Surat Permintaan Penggunaan Obat Khusus Kemoterapi</VButton>
                      <VButton type="button" rounded outlined color="info" @click="setSediaanKemo()"
                      icon="lucide:file-text">
                      Pencampuran Sediaan Kemoterapi</VButton>
                    </div>
                  </div> -->
          </div>
        </div>


          <div class="column is-12 pt-0 pb-0" style="margin-top: 10px;">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>

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
          <div class="column is-2 pt-0">
            <VControl raw subcontrol style="font-weight: bold;">
              <VCheckbox class="p-0" color="primary" square true-value="MRS" label="MRS" v-model="input.MRS_ODC" />
            </VControl>
            <VDatePicker v-model="input.tglMRS" mode="dateTime" trim-weeks v-if="input.MRS_ODC == 'MRS'">
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" v-on="inputEvents" />
                </VControl>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-2 pt-0">
            <VControl raw subcontrol style="font-weight: bold;">
              <VCheckbox class="p-0" color="primary" square true-value="ODC" label="ODC" v-model="input.MRS_ODC" />
            </VControl>
            <VDatePicker v-model="input.tglODC" mode="dateTime" trim-weeks v-if="input.MRS_ODC == 'ODC'">
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" v-on="inputEvents" />
                </VControl>
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
        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column p-0">
          <div class="columns is-multiline m-0">
            <div class="column is-4">
              <span>TB</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.tb" />
              </VControl>
            </div>
            <div class="column is-4">
              <span>BB</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.bb" />
              </VControl>
            </div>
            <div class="column is-4">
              <span>BSA</span>
              <VControl>
                <VInput type="text" class="input" v-model="input.bsa" />
              </VControl>
            </div>
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
             <div class="columns is-multiline">
            <div class="column is-12">
              <span>Diagnosis</span>
              <VField>
                <VTextarea v-model="input.diagnosis" class="input" rows="2" />
              </VField>
            </div>
            <div class="column is-12">
              <span>Regimen</span>
              <VField>
                <VTextarea v-model="input.regimen" class="input" rows="2" />
              </VField>
            </div>
            <div class="column is-12">
              <span>Premedikasi</span>
              <VField>
                <VTextarea v-model="input.premedikasi" class="input" rows="2" />
              </VField>
            </div>
            <div class="column is-12">
              <span>Hidrasi Sebelum</span>
              <VField>
                <VTextarea v-model="input.hidrasiSebelum" class="input" rows="2" />
              </VField>
            </div>
            <div class="column is-12">
              <span>Kemoterapi</span>
              <VField>
                <VTextarea v-model="input.kemoterapi" class="input" rows="2" />
              </VField>
            </div>
            <div class="column is-12">
              <span>Hidrasi Sesudah</span>
              <VField>
                <VTextarea v-model="input.hidrasiSesudah" class="input" rows="2" />
              </VField>
            </div>
            <div class="column is-12">
              <span>Keterangan</span>
              <VField>
                <VTextarea v-model="input.keterangan" class="input" rows="2" />
              </VField>
            </div>
            
          </div>

            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-3" style="margin-left: auto;">
              <h2 style="font-weight: bold" class="mt-1">DPJP</h2>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" />
              </VControl>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from "@vueuse/head";
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
import MultiSelect from "primevue/multiselect";
import * as EMR from "../page-emr-plugins/kriteria-masuk-hcu";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";
import Fieldset from "primevue/fieldset";
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import BerkasPasienView from './berkas-pasien-preview.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Dialog from 'primevue/dialog';

useHead({ title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT, });
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);
const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
let JenisKelamin = ref(EMR.JenisKelamin());
let kriteria: any = ref(EMR.kriteria());
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const showSuratObatKhususKemo: any = ref(false)
const showSediaanKemo: any = ref(false)

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
const RiwayatPsikososial: any = ref([
  { label: "Baik", value: "Baik" },
  { label: "Tidak Baik", value: "Tidak Baik" },
]);

const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const route = useRoute()
const isLoading: any = ref(false);
const isDisabled: any = ref(false);
const checkTemplate: any = ref(false)
const isLoadingVitalSign: any = ref(false);
const isSave: any = ref(false)
const idTemplate: any = ref('');
const dataTTD: any = ref([]);
const d_Perawat: any = ref([]);
const d_Dokter: any = ref([]);
const d_Ruangan: any = ref([]);
const dataSource: any = ref([]);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("ProtokolKemoterapi"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggal: new Date(),
});
const suratkemo: any = ref({
  tanggal: new Date(),
});
const sediaankemo: any = ref({
  details: [{
    no: 1,
  }],
  tanggal: new Date(),
});

const loadRiwayat = async () => {
  isLoading.value = true
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length) {
      isSave.value = true
      isLoading.value = false
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }

      // dataTTD.value = response[0]
      // H.tandaTangan().set("dpjpPasien", dataTTD.value.dpjpPasien)
      // H.tandaTangan().set("konsultanHemato", dataTTD.value.konsultanHemato)
      // H.tandaTangan().set("wadir", dataTTD.value.wadir)
    } else {
      await setAutoFill();
      isLoading.value = false
      // setAutoFill2();
      // setAutoFill3();
    }
  })
}

const onTabSurat_Kemo = () => {
  COLLECTION.value = 'SuratPermintaanPenggunaanObatKhususKemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-surat-permintaan-penggunaan-obat-khusus-kemoterapi`
  TAB_ACTIVE.value = 'Surat Permintaan Penggunaan Obat Khusus Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-surat-permintaan-penggunaan-obat-khusus-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function updateSuratKemo() {
  let ID = suratkemo.value.id ? suratkemo.value.id : ''
  let object: any = {}
  object = suratkemo.value
  object.nocm = props.pasien.nocm
  // object['canvasmata'] = H.tandaTangan().get("canvasmata");

  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': 'SuratPermintaanPenggunaanObatKhususKemoterapi',
    'url_form': props.FORM_URL,
    'name_form': 'Surat Permintaan Penggunaan Obat Khusus Kemoterapi',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true

  useApi().post(
    `/emr/simpan-emr`, json).then(async (response: any) => {

      saveKlaimSEP();
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      suratkemo.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const onTabSediaan_kemo = () => {
  COLLECTION.value = 'PencampuranSediaanKemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-pencampuran-sediaan-kemoterapi`
  TAB_ACTIVE.value = 'Pencampuran Sediaan Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-pencampuran-sediaan-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function updateSediaanKemo() {
  let ID = sediaankemo.value.id ? sediaankemo.value.id : ''
  let object: any = {}
  object = sediaankemo.value
  object.nocm = props.pasien.nocm
  // object['canvasmata'] = H.tandaTangan().get("canvasmata");

  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': 'PencampuranSediaanKemoterapi',
    'url_form': props.FORM_URL,
    'name_form': 'Pencampuran Sediaan Kemoterapi',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true

  useApi().post(
    `/emr/simpan-emr`, json).then(async (response: any) => {

      saveKlaimSEP();
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      sediaankemo.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const simpan = () => {
  if (checkTemplate.value == true) {
      H.alert('warning', 'Simpan template ya, bukan simpan data :)')
      return;
  }

  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  delete object.namatemplate
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  // object['dpjpPasien'] = H.tandaTangan().get("dpjpPasien");
  // object['konsultanHemato'] = H.tandaTangan().get("konsultanHemato");
  // object['wadir'] = H.tandaTangan().get("wadir");
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
    checkTemplate.value = false
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false;
  });
}


const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk };
  input.value.tglMRS = new Date();
  input.value.tglODC = new Date();
  input.value.namaruangan = { value: props.registrasi.objectruanganfk, label: props.registrasi.namaruangan }

  const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir-v2?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
  if (responseHistori && responseHistori != '') {
    input.value = responseHistori
    let d = input.value
    delete d.id
  }
};

const setAutoFill2 = async () => {
  suratkemo.value.namaPasien = props.pasien.namapasien;
  suratkemo.value.jeniskelamin = props.pasien.jeniskelamin;
  suratkemo.value.norm = props.pasien.nocm;
  suratkemo.value.tanggalLahirPasien = props.pasien.tgllahir;
  suratkemo.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  suratkemo.value.dokterRawat = props.registrasi.dokter;
  suratkemo.value.tglPembuatan = new Date();
}

const setAutoFill3 = async () => {
  sediaankemo.value.namaPasien = props.pasien.namapasien;
  sediaankemo.value.jeniskelamin = props.pasien.jeniskelamin;
  sediaankemo.value.norm = props.pasien.nocm;
  sediaankemo.value.tanggalLahirPasien = props.pasien.tgllahir;
  sediaankemo.value.alamatLengkap = props.pasien.alamatlengkap;
  sediaankemo.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi;
  sediaankemo.value.dokterPemeriksa = props.registrasi.dokter;
  sediaankemo.value.tglPembuatan = new Date();
  sediaankemo.value.ruangan = props.registrasi.namaruangan;
  sediaankemo.value.caraBayar = props.registrasi.kelompokpasien;
  sediaankemo.value.Siklus = 'Ke:'
};

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
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
  const skipKeys = ['id', '_id', 'namatemplate','namaPasien','tanggalLahirPasien','jeniskelamin','norm','MRS_ODC','tglMRS','tglODC','namaruangan','tb','bb','bsa']; // Keys to be skipped

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

function setSuratKemo () {
  const tableAsmed = 'SuratPermintaanPenggunaanObatKhususKemoterapi';
  showSuratObatKhususKemo.value = true;
  isLoading.value = true;
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${tableAsmed}`).then(async (dt) => {
    isLoading.value = false;
    if (dt.length > 0) {
      suratkemo.value = dt[0]
      await loadGambar('GambarTubuh', dt[0].GambarTubuh);
    }
  })
}
function setSediaanKemo () {
  const tableAsmed = 'PencampuranSediaanKemoterapi';
  showSediaanKemo.value = true;
  isLoading.value = true;
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${tableAsmed}`).then(async (dt) => {
    isLoading.value = false;
    if (dt.length > 0) {
      sediaankemo.value = dt[0]
      await loadGambar('GambarTubuh', dt[0].GambarTubuh);
    }
  })
}

const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: sediaankemo.value.details[sediaankemo.value.details.length - 1].no + 1
  }
  sediaankemo.value.details.unshift(newItem);
}
const removeItem = (index: any) => {
  let urut = sediaankemo.value.details.length - 1
  sediaankemo.value.details.splice(urut, 1)
}

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
      let rouutename = from?.name
      H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

onMounted(() => {
  let q = useRoute().query;
  if(q?.isfromKemo == "true" && q?.paramKemo != "hematologi") {
    let inputan = document.querySelectorAll('.isprotokol-kemo input, .isprotokol-kemo textarea, .isprotokol-kemo select, .isprotokol-kemo button');
    for (var i=0; i < inputan.length; i++) {
        inputan[i].setAttribute("disabled", "true");
    }
  }
})
</script>
