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
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
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
  <div class="columns is-multiline p-2">
    <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
      <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
        @click="pilihTemplateFix(index)"> Pilih Template
      </VButton>
      <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
        :loading="isLoading" @click="pilihTemplate(index)"> Pilih Riwayat
      </VButton>
    </div>

    <hr class="m-0">

    <div class="column is-12">
      <h1>Nama Template&emsp;&emsp;
        <span style="color:red">**Hanya diisi jika ingin membuat template</span>
      </h1>
      <VField>
        <VControl>
          <VTextarea v-model="input.namatemplate" rows="1">
          </VTextarea>
        </VControl>
      </VField>
    </div>

    <hr class="m-0">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="columns is-multiline p-3">
              <div class="column is-3">
                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal dan Jam</h1>
                <VField>
                  <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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
              <div class="column is-3">
                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Dokter Pelaksana Tindakan</h1>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.dokterTindakan" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Pemberi Informasi</h1>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.pemberiInformasi" :suggestions="d_Pegawai"
                      @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Penerima informasi</h1>
                <VField>
                  <VControl>
                    <VInput type="text" v-model="input.bahasaPasien" placeholder="Penerima Informasi" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="PEMBERIAN INFORMASI" :toggleable="true">
              <div class="columns is-multiline">
                <table class="triase" style="width: 90%; margin-top: 2rem; text-align: scenter">
                  <tr>
                    <th colspan="4" style="text-align : center"> PEMBERIAN INFORMASI </th>
                  </tr>
                  <tr>
                    <th style="text-align : center"> No </th>
                    <th style="text-align : center"> Jenis Informasi </th>
                    <th style="text-align: center"> Isi Informasi </th>
                    <th style="text-align: center"> Checklist </th>
                  </tr>
                  <tr v-for="(datas) in DaftarInformasi">
                    <td>{{ datas.no }}</td>
                    <td>{{ datas.Kriteria }}</td>
                    <td>
                      <div class="columns is 12">
                        <div class="column" v-for="(dot) in datas.keterangan">
                          <VField v-if="dot.type == 'checkBox'">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[dot.model]" :true-value="dot.title" :label="dot.title"
                                color="primary" square />
                            </VControl>
                          </VField>

                          <VField v-else="dot.type == 'textArea'">
                            <VControl>
                              <VTextarea rows="2" v-model="input[dot.model]" placeholder="Isi Informasi..." />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <VField v-for="(check) in datas.pilihan">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" v-model="input[check.model]" :true-value="check.label"
                                :label="check.label" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </td>

                  </tr>
                </table>

              </div>

            </Fieldset>
          </div>
          <div class="column is-12 pb-1">
            <h1 style="font-weight: bold;"> Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas
              secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/ atau berdiskusi</h1>
            <div class="columns is-multiline" style="margin-top: 1rem;">
              <div class="column is-12">
                <div class="columns is-12" style="text-align: center;">
                  <div class="column is-4">
                  </div>
                  <div class="column is-4" style="text-align: center;">
                    <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                    <VField class="is-autocomplete-select mt-3" v-slot="{ id }">

                      <VControl icon="feather:search">
                        <AutoComplete v-model="input.namaPegawai" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Cari nama Pegawai" />
                      </VControl>
                    </VField>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column is-12 pb-1">
            <h1 style="font-weight: bold;"> Dengan ini menyatakan bahwa saya telah menerima informasi
              sebagaimana di atas yang saya beri tanda paraf di kolom kanannya dan telah memahaminya</h1>
            <div class="columns is-multiline" style="margin-top: 1rem;">
              <div class="column is-12">
                <div class="columns is-12" style="text-align: center;">
                  <div class="column is-4">
                  </div>
                  <div class="column is-4" style="text-align: center;">
                    <TandaTangan :elemenID="'signature_2'" :width="'150'" :height="'150'"></TandaTangan>
                    <VField class="mt-3">
                      <VControl>
                        <VInput type="text" v-model="input.namaTTD" placeholder="Nama Lengkap" />
                      </VControl>
                    </VField>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column is-12 pb-1">
            <h1 style="font-weight: bold;"> **Bila pasien tidak kompeten atau tidak mau menerima informasi, maka
              penerima informasi adalah wali atau keluarga terdekat</h1>
            <div class="columns is-multiline" style="margin-top: 1rem;">
              <div class="column is-12">
                <div class="columns is-12" style="text-align: center;">
                  <div class="column is-4">
                  </div>
                  <div class="column is-4" style="text-align: center;">
                    <TandaTangan :elemenID="'signature_3'" :width="'150'" :height="'150'"></TandaTangan>
                    <VField class="mt-3">
                      <VControl>
                        <VInput type="text" v-model="input.namaWali" placeholder="Nama Lengkap" />
                      </VControl>
                    </VField>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="PERSETUJUAN TINDAKAN MEDIS" :toggleable="true">
              <div class="column is-12">
                <h1 style="font-weight: bold;">Yang bertanda tangan dibawah ini :
                </h1>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:0.5rem">
                      <h1 style="font-weight: bold;"> Nama Lengkap : </h1>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Nama Lengkap" v-model="input.nama" />
                        </VControl>

                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2 mt-4" style="margin-top:1rem">
                      <h1 style="font-weight: bold;"> Tanggal Lahir : </h1>
                    </div>
                    <div class="column is-6" style=" margin-top: 0.5rem">
                      <VField>
                        <VDatePicker v-model="input.tanggalLahirWali" mode="date" style="width: 100%" trim-weeks
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

                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:0.5rem">
                      <h1 style="font-weight: bold;"> Jenis Kelamin : </h1>
                    </div>
                    <div class="columns is-6" style=" margin-top: 1rem">
                      <VField v-for="items in JenisKelamin" :key="items.value" style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.jenisKelamin" class="pt-1 pb-1 " :true-value="items.label"
                            :label="items.label" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 p-0">

                  <div class="column is-12 p-0">
                    <div class="is-flex">
                      <div class="column is-2" style="margin-top:0.5rem">
                        <h1 style="font-weight: bold;"> Alamat : </h1>
                      </div>
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="Alamat" v-model="input.alamat" />
                          </VControl>

                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="is-flex">
                    <h1 class="is-flex mt-2 mr-2" style="font-weight: bold">
                      dengan ini menyatakan menyetujui untuk dilakukannya tindakan
                    </h1>
                    <VControl style="width: 30%;">
                      <VInput type="text" class="input" v-model="input.tindakan" placeholder="Tindakan" />
                  </VControl>
                  <h1 style="font-weight: bold;" class="ml-2 mt-2">
                    terhadap
                  </h1>
                    <div class="column is-3" style="margin-top:-1rem;">
                      <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="fa:users" fullwidth class="prime-auto ">
                          <AutoComplete v-model="input.hubunganPenjamin" :suggestions="d_Hubungan"
                            @complete="fetchHubungan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik untuk mencari..." />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:0.5rem">
                      <h1 style="font-weight: bold;"> saya, Yang bernama : </h1>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Nama Lengkap" v-model="input.namaPasien" />
                        </VControl>

                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:0.5rem">
                      <h1 style="font-weight: bold;"> Jenis Kelamin : </h1>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Nama Lengkap"
                            v-model="input.jenisKelaminPasien" />
                        </VControl>

                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:0.5rem">
                      <h1 style="font-weight: bold;"> No. Rekam Medis : </h1>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm"
                            disabled />
                        </VControl>

                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:1rem">
                      <h1 style="font-weight: bold;"> Tanggal Lahir : </h1>
                    </div>
                    <div class="column is-6" style=" margin-top: 0.5rem">
                      <VField>
                        <VDatePicker v-model="input.tanggalLahirPasien" mode="date" style="width: 100%" trim-weeks
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

                  </div>
                </div>
                <div class="column is-12 p-0">
                  <div class="is-flex">
                    <div class="column is-2" style="margin-top:0.5rem">
                      <h1 style="font-weight: bold;"> Alamat : </h1>
                    </div>
                    <div class="column is-6">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Alamat" v-model="input.alamatPasien" />
                        </VControl>

                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold">
                    Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan
                    seperti di atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul.
                    Saya bertanggungjawab secara penuh atas segala akibat yang mungkin timbul sebagai
                    akibat tidak dilakukannya pengobatan tersebut.
                    Demikianlah pernyataan ini saya buat dengan penuh kesadaran, dan bertanggung jawab
                    atas risiko yang mungkin terjadi sehubungan dengan pernyataan ini
                  </h1>
                </div>


              </div>
            </Fieldset>
          </div>
        </div>
        <div class="column is-12 pb-1">

          <div class="columns is-multiline" style="margin-top: 1rem;">
            <div class="column is-12">
              <div class="columns is-12">
                <div class="column is-3" style="text-align: center;">
                  <br>
                  <!-- <TandaTangan :elemenID="'signature_7'" :width="'180'" :height="'180'"></TandaTangan> -->
                  <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                    <h1 style="font-weight: bold;"> Dokter DJP :</h1>
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.dokterRawat" :suggestions="d_Pegawai"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                    </VControl>
                  </VField>

                </div>
                <div class="column is-3" style="text-align: center;">
                  <h1 style="font-weight: bold;"> Saksi 1 :</h1>
                  <!-- <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan> -->
                  <VField class="is-autocomplete-select mt-3">
                    <h1 style="font-weight: bold;"> Perawat :</h1>

                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.namaPerawat" :suggestions="d_Pegawai"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Cari nama Pegawai" />
                    </VControl>

                    <!-- <VControl>
                      <VInput type="text" v-model="input.namaPerawat" placeholder="Nama Lengkap" />
                    </VControl> -->
                  </VField>
                </div>
                <div class="column is-3" style="text-align: center;">
                  <h1 style="font-weight: bold;"> Saksi 2 :</h1>
                  <TandaTangan :elemenID="'signature_5'" :width="'150'" :height="'150'"></TandaTangan>
                  <VField class="mt-3">
                    <h1 style="font-weight: bold;"> Keluarga :</h1>
                    <VControl>
                      <VInput type="text" v-model="input.namaSaksi2" placeholder="Nama Lengkap" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" style="text-align: center;">
                  <h1 style="font-weight: bold;"> Yang Menyatakan :</h1>
                  <TandaTangan :elemenID="'signature_6'" :width="'150'" :height="'150'"></TandaTangan>
                  <VField class="mt-3">
                    <h1 style="font-weight: bold;"> Pasien :</h1>
                    <VControl>
                      <VInput type="text" v-model="input.namaPasien" placeholder="Nama Lengkap" />
                    </VControl>
                  </VField>
                </div>

              </div>

            </div>
          </div>
        </div>
        <div class="column is-12">
          <Fieldset legend="PERSETUJUAN TINDAKAN HEMODIALISIS" :toggleable="true">
            <p>Saya memahami perlunya dan manfaat tindakan hemodialisis sebagaimana telah dijelaskan seperti di atas
              kepada
              saya, termasuk risiko dan komplikasi yang mungkin timbul.</p>
            <p>Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan
              kedokteran
              bukanlah keniscayaan, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa</p>
            <p>Dengan ini saya menyatakan setuju untuk dilakukannya tindakan Hemodialisis</p>

            <table class="table-pri">
              <thead>
                <tr style="">
                  <th class="th-pri" style="vertical-align: inherit; text-align: center" rowspan="3">
                    Tanggal dan Jam
                  </th>
                  <th class="th-pri" style="vertical-align: inherit; text-align: center" rowspan="2">Yang Menyatakan
                    Persetujuan
                  </th>
                  <th class="th-pri" style="vertical-align: inherit; text-align: center" colspan="2">Tanda Tangan</th>
                  <th class="th-pri" style="vertical-align: inherit; text-align: center" colspan="2">#</th>
                </tr>
                <tr>
                  <th class="th-pri" style="vertical-align: inherit; text-align: center">Saksi 1</th>
                  <th class="th-pri" style="vertical-align: inherit; text-align: center">Saksi 2</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in input.formSaksi" :key="index">
                  <td class="td-pri" style="vertical-align: inherit; text-align: center">
                    <VField>
                      <VDatePicker v-model="item.tanggalSaksi" mode="dateTime" style="width: 100%"
                        trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </td>
                  <td class="td-pri" style="vertical-align: inherit; text-align: center">
                    <VField>
                      <VControl>
                        <VInput type="text" v-model="item.saksi1Persetujuan1" placeholder="Yang Menyatakan" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-pri">
                    <TandaTangan :elemenID="`ttdSaksi1PersetujuanTTD-${index}`" width="150" height="150">
                    </TandaTangan>
                  </td>
                  <td class="td-pri" style="vertical-align: inherit; text-align: center">
                    <VField class="is-autocomplete-select mt-3">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.saksi2Persetujuan" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Cari nama Pegawai" />
                      </VControl>
                    </VField>
                  </td>
                  <td class="td-pri" style="vertical-align: inherit; text-align: center; width: 100px">
                    <VButtons>
                      <VIconButton icon="fas fa-plus" color="info" v-tooltip-prime.right="'Tambah Data'"
                        @click="addNewSaksi()">
                      </VIconButton>
                      <VIconButton
                        icon="fas fa-trash"
                        color="danger"
                        v-if="index > 0 && input.formSaksi.length > 1"
                        v-tooltip-prime.right="'Delete Data'"
                        @click="removeFormSaksi(index)">
                      </VIconButton>
                    </VButtons>
                  </td>
                </tr>
              </tbody>
            </table>
          </Fieldset>
        </div>
      </VCard>
    </div>
  </div>
  <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="medium" actions="right"
    @close="showModalTemplateFix = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Template</span>
          <div style="overflow-y:auto;" class="mt-1">
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
                  <td style="width:15%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
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
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>
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
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Fieldset from 'primevue/fieldset';
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from '../page-emr-plugins/informed-tindakan-medis'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import moment from 'moment'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let DaftarInformasi = ref(EMR.DaftarInformasi())

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
const confirm = useConfirm();
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Hubungan: any = ref([])
const pasien: any = ref({})
const isLoading: any = ref(false)
const dataTTD = ref([])
const dataDetailTTD = ref([])
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('InformedConsentTindakanHemodialisa') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
  formSaksi: [
    {
      no: 1
    }
  ]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = () => {
  useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      dataDetailTTD.value = response[0].formSaksi
      // for (let i = 0; i < input.value.formSaksi.length; i++) {
      //   await nextTick()
      //   const fieldName = `ttdSaksi1Persetujuan${i}`
      //   H.tandaTangan().set(fieldName, dataTTD.value[fieldName])
      // }
      await nextTick(() => {
        dataDetailTTD.value.forEach((item2, index2) => {
        if (!item2.ttdSaksi1Persetujuan) {
          H.tandaTangan().set(`ttdSaksi1Persetujuan-${index2}`, item2.ttdSaksi1Persetujuan);
        } else {
          H.tandaTangan().set(`ttdSaksi1PersetujuanTTD-${index2}`, item2.ttdSaksi1Persetujuan);
        }
      });
    });
      H.tandaTangan().set("signature_1", dataTTD.value.tandaTanganPegawai)
      H.tandaTangan().set("signature_2", dataTTD.value.tandaTanganWali1)
      H.tandaTangan().set("signature_3", dataTTD.value.tanganTanganWali2)
      H.tandaTangan().set("signature_4", dataTTD.value.tandaTanganSaksi1)
      H.tandaTangan().set("signature_5", dataTTD.value.tandaTanganSaksi2)
      H.tandaTangan().set("signature_6", dataTTD.value.tandaTanganDokter)

    } else {
      isLoading.value = true
      const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
      const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
      isLoading.value = false
      if (responseTglRuangan.length && responseHistori.length) {
        console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
        var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
        var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
        var tgl_Sekarang = moment();
        isLoading.value = false
        const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
        if (responseTglRuangan[0].registrasi.namaruangan.trim() === props.registrasi.namaruangan.trim() && calculateDays < 90) {
          confirm.require({
            message: 'Persetujuan Tindakan Hemodialisa sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
            group: 'templating',
            header: 'Informasi Persetujuan Tindakan Hemodialisa',
            icon: 'pi pi-exclamation-circle',
            accept: () => {
              if (responseHistori.length) {
                input.value = responseHistori[0] //set ke inputan
                input.value.namatemplate = ''
                dataTTD.value = responseHistori[0]

                H.tandaTangan().set("signature_1", dataTTD.value.tandaTanganPegawai)
                H.tandaTangan().set("signature_2", dataTTD.value.tandaTanganWali1)
                H.tandaTangan().set("signature_3", dataTTD.value.tanganTanganWali2)
                H.tandaTangan().set("signature_4", dataTTD.value.tandaTanganSaksi1)
                H.tandaTangan().set("signature_5", dataTTD.value.tandaTanganSaksi2)
                H.tandaTangan().set("signature_6", dataTTD.value.tandaTanganDokter)

                nextTick(() => {
                  dataDetailTTD.value.forEach((item2, index2) => {
                    if (!item2.ttdSaksi1Persetujuan) {
                      H.tandaTangan().set(`ttdSaksi1Persetujuan-${index2}`, item2.ttdSaksi1Persetujuan);
                    } else {
                      H.tandaTangan().set(`ttdSaksi1PersetujuanTTD-${index2}`, item2.ttdSaksi1Persetujuan);
                    }
                  });
                })
                dataDetailTTD.value = responseHistori[0].formSaksi ?? []
                // dataDetailTTD.value.forEach((item, index) => {
                //   H.tandaTangan().set(`ttdSaksi1Persetujuan-${index}`, item.ttdSaksi1Persetujuan || "");
                // });
                isLoading.value = false // Set isLoading to false after loading history data
              } else {
                H.alert('warning', 'Data tidak ada')
                isLoading.value = false
              }
            },
            reject: () => {
              isLoading.value = false // Ensure isLoading is set to false if rejected
            }
          })
        }
      } else {
        // console.log('Data EMR sebelumnya tidak ada!')
        H.alert('warning', 'Data EMR sebelumnya tidak ada!');
        isLoading.value = false // Set isLoading to false when no previous data is found
      }
      // console.log("Ruangan pasien sekarang : " + H.setObjectRegistrasi(pasien.value.registrasi).namaruangan)
      H.alert('info', 'Ruangan Pasien saat ini: ' + props.registrasi.namaruangan);
    }
  })
}
const JenisKelamin: any = ref([
  { label: 'Laki - Laki', value: 'Laki - Laki' },
  { label: 'Perempuan', value: 'Perempuan' }
])
const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
// const addTemplate = (response: any) => {
//   console.log(response)
//   input.value = response //set ke inputan
//   input.value.namatemplate = null
//   delete input.value.namatemplate;
//   delete input.value['_id'];
//   input.value['id'] = ''
//   showModalTemplateFix.value = false
// }
const addTemplate = (response: any) => {
  console.log(response);

  // Salin semua nilai dari response ke input, kecuali yang dikecualikan
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
  ];

  input.value = Object.keys(response).reduce((acc, key) => {
    if (!excludedFields.includes(key)) {
      acc[key] = response[key];
    }
    return acc;
  }, {});

  input.value['id'] = '';
  showModalTemplateFix.value = false;
  showModalTemplate.value = false;
  H.alert('success', 'Berhasil ditambahkan');
  setAutoFill();
};


const fetchHubungan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=${filter.query}&limit=10`)
  d_Hubungan.value = response
}

const updateTandaTanganKeys = () => {
  input.value.formSaksi.forEach((item, index) => {
    item.ttdSaksi1Persetujuan = `ttdSaksi1Persetujuan-${index}`;
  });
};

const addNewSaksi = async () => {
  let newItem = {
    no: input.value.formSaksi.length + 1,
  };

  input.value.formSaksi.push(newItem);
  updateTandaTanganKeys();
  await nextTick(() => {
    input.value.formSaksi.forEach((item, index) => {
      H.tandaTangan().set(`ttdSaksi1Persetujuan-${index}`, item.ttdSaksi1Persetujuan || "");
    });
  });
};

const removeFormSaksi = async (index) => {
  input.value.formSaksi.splice(index, 1);
  // delete input.value['ttdSaksi2Persetujuan-' + index]
  // delete input.value['saksi1Persetujuan1-' + index]
  // delete input.value['ttdSaksi1Persetujuan' + index]
  // document.getElementById("ttdSaksi1Persetujuan-" + index).remove();
  updateTandaTanganKeys();
  await nextTick();
  input.value.formSaksi.forEach((item, index) => {
    H.tandaTangan().set(`ttdSaksi1Persetujuan-${index}`, item.ttdSaksi1Persetujuan || "");
  });
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganPegawai = H.tandaTangan().get("signature_1")
  object.tandaTanganWali1 = H.tandaTangan().get("signature_2")
  object.tanganTanganWali2 = H.tandaTangan().get("signature_3")
  object.tandaTanganSaksi1 = H.tandaTangan().get("signature_4")
  object.tandaTanganSaksi2 = H.tandaTangan().get("signature_5")
  object.tandaTanganPernyataan = H.tandaTangan().get("signature_6")
  object.tandaTanganDokter = H.tandaTangan().get("signature_6")
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  // for (let i = 1; i <= 10; i++) {
  //   const ttdPerawat1 = `ttdSaksi1Persetujuan-${i}`
  //   object[ttdPerawat1] = H.tandaTangan().get(ttdPerawat1)
  // }
  let pushData: any = []
  if (object.formSaksi.length >= 0) {
    object.formSaksi.forEach((element: any, i: any) => {
      const ttdSaksi1Persetujuan = H.tandaTangan().get(`ttdSaksi1PersetujuanTTD-${i}`);
      element.ttdSaksi1Persetujuan = ttdSaksi1Persetujuan
    })
  }
  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
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
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jenisKelaminPasien = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.dokterRawat = props.registrasi.dokter
  input.value.tglPembuatan = new Date()
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPegawai = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
  if (response != null) {
    H.tandaTangan().set("signature_7", response.ttd2)
    input.value.tandaTanganDokter = response.ttd2
  } else {
    H.tandaTangan().set("signature_7", '')
  }
}

const pilihTemplate = async (index: any) => {
  isLoading.value = true;
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    .then((responselast: any) => {
      isLoading.value = false;
      if (responselast.length) {
        listTemplate.value = responselast;
        showModalTemplate.value = true;
      } else {
        H.alert('warning', 'Data tidak ada');
      }
    });
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
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
const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('error', 'Nama Template harus diisi untuk menyimpan.');
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

setView()
setAutoFill()
loadRiwayat()
fetchPasien()
</script>
<style lang="scss">
#signature {
  border: double 3px transparent;
  border-radius: 5px;
  background-image: linear-gradient(white, white),
    radial-gradient(circle at top left, #4bc5e8, #9f6274);
  background-origin: border-box;
  background-clip: content-box, border-box;
}

.container {
  width: "100%";
  padding: 8px 16px;
}

.p-fieldsets .p-fieldset-content {
  background: #ffffff;
}

table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
.triase th,
.triase td {
  border: 0.5px solid grey;
}


.triase th,
.triase td {
  padding: 8px;
  vertical-align: middle !important;
}
</style>
