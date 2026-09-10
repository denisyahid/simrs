<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
          <div class="form-header-inner">
            <div class="left">
              <h3>Asesmen Awal Kebidanan dan Kandungan</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun" @simpanTemplate="simpanTemplate" :isHideCetak="true">
              </ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

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
                        <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                        <td class="tg-0lax text-center" width="15%">Tanggal Registrasi</td>
                        <td class="tg-0lax text-center" width="15%">No Registrasi</td>
                        <td class="tg-0lax text-center" width="15%">No EMR</td>
                        <td class="tg-0lax text-center" width="20%">Dokter</td>
                        <td class="tg-0lax text-center" width="15%">Section</td>
                        <td class="tg-0lax text-center" width="5%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplate">
                      <tr>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:5%;text-align:center">
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
          @close="showModalTemplateFix = false">
          <template #content>
            <form class="modal-form">
              <div class="column is-12 pt-0 pb-0">
                <span style="font-size:9pt;font-weight:bold">List Template</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center" width="5%">No</td>
                        <td class="tg-0lax text-center" width="15%">Tanggal Dibuat</td>
                        <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                        <td class="tg-0lax text-center" width="25%">Nama Template</td>
                        <td class="tg-0lax text-center" width="15%">#</td>
                      </tr>
                    </thead>
                    <tbody v-for="resep in listTemplateFix">
                      <tr>
                        <td style="width:5%;text-align:center">
                          <span class="mb-2">{{ resep.no }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <span class="mb-2">{{ resep.created_at }}</span><br>
                        </td>
                        <td style="width:20%;text-align:center">
                          <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                        </td>
                        <td style="width:25%;text-align:center">
                          <span class="mb-2">{{ resep.namatemplate }}</span><br>
                        </td>
                        <td style="width:15%;text-align:center">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)"
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

        <div class="column">
          <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
              @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
          </div>

          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

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

          <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">

          <div class="column columns pb-0">
            <div class="column is-6">
              <h1>Tanggal Masuk</h1>
              <VDatePicker v-model="input.DTanggalMasuk" mode="dateTime" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal masuk..." v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-6">
              <h1>Tanggal Asesmen Awal</h1>
              <VDatePicker v-model="input.DTanggalAsesmenAwal" mode="dateTime" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal asesmen awal..." v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>

          <div class="columns is-multiline column pt-0 pb-0">
            <div class="column is-4 pt-1">
              <h1>Rujukan</h1>
              <Multiselect v-model="input.SRujukan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_rujukan" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4 pt-1" v-if="input.SRujukan == 1">
              <h1>Dari :</h1>
              <Multiselect v-model="input.STempatRujukan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tempatRujukan" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4 pt-1" v-if="input.SRujukan == 4">
              <h1>&nbsp;</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDiantar" placeholder="Diantar oleh..." />
              </VControl>
            </div>
            <div class="column is-4 pt-1" v-if="input.STempatRujukan == 1 && input.SRujukan == 1">
              <h1>Rumah Sakit</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="Rumah sakit..." v-model="input.TBRujuk_RS" />
              </VControl>
            </div>
            <div class="column is-4 pt-1" v-if="input.STempatRujukan == 2 && input.SRujukan == 1">
              <h1>Puskesmas</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="Puskesmas..." v-model="input.TBRujuk_Puskesmas" />
              </VControl>
            </div>
            <div class="column is-4 pt-1" v-if="input.STempatRujukan == 3 && input.SRujukan == 1">
              <h1>dr.</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="dr..." v-model="input.TBRujuk_dr" />
              </VControl>
            </div>
            <div class="column is-4 pt-1" v-if="input.STempatRujukan == 4 && input.SRujukan == 1">
              <h1>Lainnya</h1>
              <VControl>
                <VInput type="text" class="input" placeholder="Lainnya..." v-model="input.TBRujuk_Lainnya" />
              </VControl>
            </div>
            <div class="column is-4 pt-1">
              <h1>Dx.rujukan</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDx_rujukan" />
              </VControl>
            </div>
            <div class="column is-4 pt-1">
              <h1>Alloanamnesis</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:search">
                  <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4 pt-1" v-if="input.kebpilihanallo == 4">
              <h1>Lainnya</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBLainnya_Allo" placeholder="Lainnya..." />
              </VControl>
            </div>
          </div>

          <div class="columns is-multiline column pb-0">
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-12">
              <h1>ANAMNESIS</h1>
            </div>
            <div class="column is-12">
              <VButton color="primary" @click="getTriageGinekologiObstetri">Data Triage Obstetri Gynekologi</VButton>
            </div>
            <div class="column is-6">
              <h1>Keluhan Utama :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TAKeluhanUtama"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h1>Riwayat Penyakit Sekarang :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TARiwayatPenyakitSekarang"></VTextarea>
              </VField>
            </div>
            <div class="column is-6 pt-1">
              <h1>Riwayat Penyakit Dahulu :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TARiwayatPenyakitDahulu"></VTextarea>
              </VField>
            </div>
            <div class="column is-6 pt-1">
              <h1>Riwayat Pengobatan :</h1>
              <VField>
                <VTextarea rows="2" v-model="input.TARiwayatPengobatan"></VTextarea>
              </VField>
            </div>
          </div>

          <div class="column pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="columns column is-multiline">
            <div class="column is-12">
              <h1>Riwayat Menstruasi : </h1>
            </div>
            <div class="columns is-multiline column">
              <div class="column is-3">
                <h1>Menarche Umur</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Umur..." v-model="input.TBUmur" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Tahun</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Volume</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Volume..." v-model="input.TBVolume" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>cc</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Siklus</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Siklus..." v-model="input.TBSiklus" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Hari</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Teratur/Tidak Teratur</h1>
                <Multiselect v-model="input.STeraturSiklus" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_teraturSiklus" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-3">
                <h1>Keluhan Saat Haid</h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Keluhan Saat Haid..."
                      v-model="input.TBKeluhanSaatHaid" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3"></div>
              <div class="column is-3">
                <h1>Lama</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Lama..." v-model="input.TBLama" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Hari</VButton>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>

          <div>
            <div class="column">
              <h1 style="font-weight: bold; text-align: center">Riwayat kehamilan, persalinan dan nifas
                yang lalu</h1>
            </div>
            <div class="column" style="overflow: auto;">
              <table class="tg">
                <thead>
                  <tr>
                    <th style="text-align: center;vertical-align: middle;" rowspan="3">#
                    </th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="3">
                      Tanggal
                      Partus</th>
                    <th style="text-align: center;vertical-align: middle;" colspan="3">Umur
                      Hamil</th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="3">Jenis
                      Partus</th>
                    <th style="text-align: center;vertical-align: middle;" colspan="2">
                      Penolong
                    </th>
                    <th style="text-align: center;vertical-align: middle;" colspan="3">Anak
                    </th>
                    <th style="text-align: center;vertical-align: middle;" colspan="3">
                      Keadaan
                      Anak Sekarang</th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="3">
                      Keterangan/Komplikasi</th>
                  </tr>
                  <tr>
                    <th style="text-align: center;vertical-align: middle;" rowspan="2">
                      Abortus
                    </th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="2">
                      Prematur
                    </th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Aterm
                    </th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Nakes
                    </th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="2">Non
                    </th>
                    <th style="text-align: center;vertical-align: middle;" colspan="2">JK
                    </th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="2">BBL
                    </th>
                    <th style="text-align: center;vertical-align: middle;" colspan="2">Hidup
                    </th>
                    <th style="text-align: center;vertical-align: middle;" rowspan="2">
                      Meninggal
                    </th>
                  </tr>
                  <tr>
                    <th style="text-align: center;vertical-align: middle;color:blue; width: 100px;">Laki-laki</th>
                    <th style="text-align: center;vertical-align: middle;color:salmon">Perempuan
                    </th>
                    <th style="text-align: center;vertical-align: middle;">Normal</th>
                    <th style="text-align: center;vertical-align: middle;">Cacat</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in input.TBRiwayatKehamilan" :key="index">
                    <td>
                      <VButtons style="justify-content:space-around">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                          v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </VButtons>
                    </td>
                    <td>
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" v-model="item.TanggalPartus" placeholder="Tanggal Partus" />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.DAbortus" />
                      </VControl>
                    </td>
                    <td>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.DPrematur" />
                      </VControl>
                    </td>
                    <td>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.DAterm" />
                      </VControl>
                    </td>
                    <td>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.DJenispartus" />
                      </VControl>
                    </td>
                    <td>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.DNakes" />
                      </VControl>
                    </td>
                    <td>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.DNon" />
                      </VControl>
                    </td>
                    <td style="text-align: center;">
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="item.DJKelamin" true-value="Laki-laki" />
                      </VControl>
                    </td>
                    <td style="text-align: center;">
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="item.DJKelamin" true-value="Perempuan" />
                      </VControl>
                    </td>
                    <td>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.DBBL" />
                      </VControl>
                    </td>
                    <td style="text-align: center;">
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="item.DHidup" true-value="Normal" />
                      </VControl>
                    </td>
                    <td style="text-align: center;">
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="item.DHidup" true-value="Cacat" />
                      </VControl>
                    </td>
                    <td style="text-align: center;">
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="item.DHidup" true-value="Meninggal" />
                      </VControl>
                    </td>
                    <td>
                      <VControl>
                        <VInput type="text" class="input" v-model="item.Keterangan" />
                      </VControl>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="column is-12">
              <div class="columns">
                <div class="column is-4">
                  <h1>Riwayat Pemakaian Kontrasepsi</h1>
                  <Multiselect v-model="input.SRiwayatPK" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_riwayatPK" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-4" v-if="input.SRiwayatPK == 'Ya'">
                  <h1>Jenis</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBJenisPK" placeholder="Jenis..." />
                  </VControl>
                </div>
                <div class="column is-4" v-if="input.SRiwayatPK == 'Ya'">
                  <h1>Lama Pemakaian</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBLamaPemakaianPK"
                      placeholder="Lama Pemakaian..." />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1>Riwayat Hamil Ini : </h1>
              <div class="columns is-multiline pt-2">
                <div class="column is-6">
                  <VField label="Hari pertama haid terakhir : ">
                    <VControl>
                      <VInput type="text" class="input" placeholder="Hari pertama haid terakhir..."
                        v-model="input.TBhphtRPAK" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tafsiran partus : ">
                    <VControl>
                      <VInput type="text" class="input" placeholder="Tafsiran partus..."
                        v-model="input.TBtafsiranPartusRPAK" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 columns is-multiline pb-0 pt-0">
              <div class="column is-4">
                <h1>Ante Natal Care : </h1>
                <Multiselect v-model="input.SAnteNatalCare" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_tidakYa" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SAnteNatalCare == 2">
                <Multiselect v-model="input.SAnteNatalCareYa" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_ANC" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SAnteNatalCareYa == 4">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLainnya_ANC" />
                </VControl>
              </div>
            </div>
            <div class="column is-12 pt-0 pb-0 columns is-multiline">
              <div class="column is-4">
                <h1>Frekuensi : </h1>
                <Multiselect v-model="input.SFrekuensi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_frekuensi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4">
                <h1>Imunisasi TT : </h1>
                <Multiselect v-model="input.SImunisasiTT" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_tidakYa" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SImunisasiTT == 2">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLainnya_ImunisasiTT" />
                </VControl>
              </div>
            </div>
            <div class="column is-12 pt-0 pb-0 columns">
              <div class="is-3">
                <h1>Keluhan Saat Hamil : </h1>
              </div>
              <div class="is-8 is-flex">
                <template v-for="(item, index) in d_KSH" :key="index">
                  <VField>
                    <VControl>
                      <VCheckbox class="p-0 mr-2" color="primary" square :true-value="item.value" :label="item.label"
                        v-model="input[item.label]" />
                    </VControl>
                  </VField>
                  <VField v-if="item.label == 'Lainnya'">
                    <VControl>
                      <VInput type="text" class="input" v-model="input.lainnyaKeluhan" />
                    </VControl>
                  </VField>
                </template>
              </div>
            </div>
            <div class="column is-12 pt-0 pb-0 columns is-multiline">
              <div class="column is-12">
                <h1>Riwayat penyakit keluarga</h1>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Hipertensi" label="Hipertensi"
                    v-model="input.Hipertensi" />
                </VControl>
                <VControl v-if="input.Hipertensi == 'Hipertensi'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetHipertensi" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Kencing Manis" label="Kencing Manis"
                    v-model="input.KencingManis" />
                </VControl>
                <VControl v-if="input.KencingManis == 'Kencing Manis'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetKencingManis" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Jiwa" label="Jiwa" v-model="input.Jiwa" />
                </VControl>
                <VControl v-if="input.Jiwa == 'Jiwa'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetJiwa" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Lain-Lain" label="Lain-Lain"
                    v-model="input.LainLain_RPK" />
                </VControl>
                <VControl v-if="input.LainLain_RPK == 'Lain-Lain'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetLainLain_RPK" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="HIV" label="HIV" v-model="input.HIV" />
                </VControl>
                <VControl v-if="input.HIV == 'HIV'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetHIV" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Jantung" label="Jantung"
                    v-model="input.Jantung" />
                </VControl>
                <VControl v-if="input.Jantung == 'Jantung'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetJantung" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Varises" label="Varises"
                    v-model="input.Varises" />
                </VControl>
                <VControl v-if="input.Varises == 'Varises'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetVarises" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Hepatitis" label="Hepatitis"
                    v-model="input.Hepatitis" />
                </VControl>
                <VControl v-if="input.Hepatitis == 'Hepatitis'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetHepatitis" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Asma" label="Asma" v-model="input.Asma" />
                </VControl>
                <VControl v-if="input.Asma == 'Asma'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetAsma" />
                </VControl>
              </div>
              <div class="column is-3">
                <VControl raw subcontrol>
                  <VCheckbox class="p-0" color="primary" square true-value="Tumor di" label="Tumor di.."
                    v-model="input.Tumordi" />
                </VControl>
                <VControl v-if="input.Tumordi == 'Tumor di'">
                  <VInput type="text" class="input" placeholder="" v-model="input.KetTumor" />
                </VControl>
              </div>
            </div>
            <div class="column is-12 columns is-multiline pt-0 pb-0">
              <div class="column is-3">
                <h1>Riwayat Operasi</h1>
                <Multiselect v-model="input.SRiwayatOperasi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_tidakAda_ada" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-3" v-if="input.SRiwayatOperasi == 2">
                <h1>Jelaskan</h1>
                <VControl>
                  <VInput type="text" class="input" placeholder="Operasi..." v-model="input.KetOperasi" />
                </VControl>
              </div>
              <div class="column is-3">
                <h1>Riwayat Transfusi Darah</h1>
                <Multiselect v-model="input.SRiwayatTransfusiDarah" :attrs="{ value }" placeholder="--Pilih--"
                  label="label" :options="d_tidakAda_ada" :searchable="true" track-by="label" mode="single"
                  autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-3" v-if="input.SRiwayatTransfusiDarah == 2">
                <h1>Jelaskan</h1>
                <VControl>
                  <VInput type="text" class="input" placeholder="Transfusi darah..."
                    v-model="input.KetTransfusiDarah" />
                </VControl>
              </div>
              <div class="column is-3">
                <h1>Riwayat Ginekologi</h1>
                <Multiselect v-model="input.SRiwayatGinekologi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_tidakAda_ada" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-3" v-if="input.SRiwayatGinekologi == 2">
                <h1>Jelaskan</h1>
                <VControl>
                  <VInput type="text" class="input" placeholder="Ginekologi..." v-model="input.KetGinekologi" />
                </VControl>
              </div>
            </div>
            <div class="column pt-0 pb-0">
              <div class="columns">
                <div class="column is-4">
                  <h1>Riwayat Alergi</h1>
                  <Multiselect v-model="input.SRiwayatAlergi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_riwayatG" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-8" v-if="input.SRiwayatAlergi == 2">
                  <div class="columns">
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Obat" label="Obat"
                          v-model="input.CBAlergiObat" />
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBAlergiObat" placeholder="Alergi obat..." />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Makanan" label="Makanan"
                          v-model="input.CBAlergiMakanan" />
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBAlergiMakanan"
                          placeholder="Alergi makanan..." />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                          v-model="input.CBAlergiLainnya" />
                      </VControl>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBAlergiLainnya" placeholder="Alergi..." />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
          </div>

          <div class="column columns is-multiline">
            <div class="columns is-multiline m-0">
              <div class="column is-12 pb-0">
                <h1>Status Fisik</h1>
              </div>
              <div class="column is-3 pb-0">
                <h1>Keadaan Umum</h1>
                <Multiselect v-model="input.SKeadaanUmum" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-5">
                <h1>GCS</h1>
                <div class="columns">
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>E</VButton>
                      </VControl>
                      <VControl expanded>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.GCSe" maxLength="1" />
                        </VControl>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>V</VButton>
                      </VControl>
                      <VControl expanded>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.GCSv" maxLength="1" />
                        </VControl>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>M</VButton>
                      </VControl>
                      <VControl expanded>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.GCSm" maxLength="1" />
                        </VControl>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4"></div>
              <div class="column is-3">
                <h1>Tekanan Darah</h1>
                <VField addons>
                  <VControl style="width: 100%;">
                    <VInput type="text" class="input" v-model="input.TBStekananDarah" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>mmHg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Nadi</h1>
                <VField addons>
                  <VControl style="width: 100%;">
                    <VInput type="text" class="input" v-model="input.TBSnadi" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/mnt</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Respirasi</h1>
                <VField addons>
                  <VControl style="width: 100%;">
                    <VInput type="text" class="input" v-model="input.TBSrespirasi" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>x/mnt</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Suhu</h1>
                <VField addons>
                  <VControl style="width: 100%;">
                    <VInput type="text" class="input" v-model="input.TBSsuhu" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>°C</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>SaO<sub>2</sub></h1>
                <VField addons>
                  <VControl style="width: 100%;">
                    <VInput type="text" class="input" v-model="input.TBSSaO2" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>%</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Berat Badan</h1>
                <VField addons>
                  <VControl style="width: 100%;">
                    <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Kg</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Tinggi Badan</h1>
                <VField addons>
                  <VControl style="width: 100%;">
                    <VInput type="text" class="input" v-model="input.TBStinggiBadan" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>cm</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1>Skor EWS</h1>
                <VField addons>
                  <VControl style="width: 100%;">
                    <VInput type="text" class="input" v-model="input.TBSSkorEWS" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static></VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pb-1">
                <h1>Status Obstetri / Gynekologi</h1>
                <VField>
                  <VTextarea rows="2" v-model="input.TBSStatusObstetri"></VTextarea>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>ASESMEN NYERI</h1>
              </div>
              <div class="column is-4">
                <h1>Skala nyeri (NRS/WBS/FLACC)</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBSkalaNyeri_AN" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Lokasi</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLokasi_AN" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Frekuensi nyeri</h1>
                <Multiselect v-model="input.SFrekuensiNyeri_AN" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_frekuensiNyeri_AN" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4">
                <h1>Lama nyeri</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLamaNyeri_AN" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Kualitas nyeri</h1>
                <Multiselect v-model="input.SKualitasNyeri_AN" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_kualitasNyeri_AN" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SKualitasNyeri_AN == 4">
                <h1>&nbsp;</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLamaNyeriLainLain_AN" placeholder="Lain-lain..." />
                </VControl>
              </div>
              <div class="column is-6">
                <h1>Faktor yang memperberat</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBFaktorYangMemperberat_AN" />
                </VControl>
              </div>
              <div class="column is-6">
                <h1>Faktor yang meringankan nyeri</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBFaktorYangMeringankan_AN" />
                </VControl>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL</h1>
              </div>
              <div class="column is-4">
                <h1>Gangguan Psikologis</h1>
                <Multiselect v-model="input.SKondisiPsikologis" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_kondisiPsikologis" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4">
                <h1>Masalah Pernikahan</h1>
                <Multiselect v-model="input.SMasalahPernikahan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_masalahPernikahan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SMasalahPernikahan == 2">
                <h1>Jelaskan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBMasalahPernikahan" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Status Pernikahan</h1>
                <Multiselect v-model="input.SStatusPernikahan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_statusPernikahan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SStatusPernikahan == 2">
                <h1>&nbsp;</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBMenikahBerapaKali" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Kali</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <h1>Umur pertama kali menikah</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBUmurPertamaKaliMenikah" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Tahun</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <h1>Kawin dengan suami 1</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBKawinDenganSuami1" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Tahun</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <h1>Ke 2,3</h1>
                <VField addons>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBSuami2_3" />
                  </VControl>
                  <VControl class="field-addon-body">
                    <VButton static>Tahun</VButton>
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <h1>Mengalami kekerasan fisik</h1>
                <Multiselect v-model="input.SMengalamiKekerasanFisik" :attrs="{ value }" placeholder="--Pilih--"
                  label="label" :options="d_mengalamiKekerasanFisik" :searchable="true" track-by="label" mode="single"
                  autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SMengalamiKekerasanFisik == 2">
                <h1>Jelaskan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBMengalamiKekerasanFisik" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Keyakinan dan nilai pribadi</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBKeyakinanDanNilaiPribadi" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Pembiayaan Kesehatan</h1>
                <Multiselect v-model="input.SPembiayaanKesehatan" :attrs="{ value }" placeholder="--Pilih--"
                  label="label" :options="d_pembiayaanKesehatan" :searchable="true" track-by="label" mode="single"
                  autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SPembiayaanKesehatan == 2">
                <h1>&nbsp;</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBAsuransiLainnya" placeholder="Asuransi..." />
                </VControl>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12">
                <h1>Kebiasaan adat istiadat yang mempengaruhi kesehatan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBKebiasaanAdatIstiadat" />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Dukungan sosial dari</h1>
                <Multiselect v-model="input.SDukunganSosialDari" :attrs="{ value }" placeholder="--Pilih--"
                  label="label" :options="d_dukunganSosial" :searchable="true" track-by="label" mode="single"
                  autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-4" v-if="input.SDukunganSosialDari == 4">
                <h1>&nbsp;</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLainnya_DSD" placeholder="Dari..." />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Kebiasaan ibu</h1>
                <Multiselect v-model="input.SKebiasaanIbu" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_kebiasaanIbu" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
                <VControl v-if="input.SKebiasaanIbu == 1">
                  <VInput type="text" class="input" v-model="input.TBKI_Merokok" placeholder="Catatan..." />
                </VControl>
                <VControl v-else-if="input.SKebiasaanIbu == 2">
                  <VInput type="text" class="input" v-model="input.TBKI_Alkohol" placeholder="Catatan..." />
                </VControl>
                <VControl v-else-if="input.SKebiasaanIbu == 3">
                  <VInput type="text" class="input" v-model="input.TBKI_Lainnya" placeholder="Catatan..." />
                </VControl>
              </div>
              <div class="column is-4" v-if="input.SKebiasaanIbu == 3">
                <h1>&nbsp;</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLainnya_KI" placeholder="Lainnya..." />
                </VControl>
              </div>
              <div class="column is-4">
                <h1>Perlu rohaniawan</h1>
                <Multiselect v-model="input.SPerluRohaniawan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_yaTidak" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
              <div class="column is-12">
                <h1>Larangan dalam agama/adat</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBLaranganDalamAgamaAdat" />
                </VControl>
              </div>
              <div class="column is-12">
                <h1>Pendamping yang diinginkan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBPendampingYangDiinginkan" />
                </VControl>
              </div>
            </div>
            <hr>
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                Makan dan minum
              </div>
              <div class="columns column is-12 is-multiline">
                <div class="column is-3">
                  <h1>Pola makan</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBPolaMakan" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1>Terakhir jam</h1>
                  <VField>
                    <VDatePicker v-model="input.TTJ_PMakan" mode="datetime" trim-weeks :max-date="new Date()">
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
                  <h1>Pola minum</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBPolaMinum" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1>Terakhir jam</h1>
                  <VDatePicker v-model="input.TTJ_PMinum" mode="datetime" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:clock" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-3">
                  <h1>Kesulitan makan</h1>
                  <Multiselect v-model="input.SKesulitanMakan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_tidakYa" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3" v-if="input.SKesulitanMakan == 2">
                  <h1>&nbsp;</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBKesulitanMakan"
                      placeholder="Kesulitan makan..." />
                  </VControl>
                </div>
                <div class="column is-3">
                  <h1>Pantangan makan</h1>
                  <Multiselect v-model="input.SPantanganMakan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_tidakAda" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3" v-if="input.SPantanganMakan == 2">
                  <h1>&nbsp;</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBPantanganMakan"
                      placeholder="Pantangan makan..." />
                  </VControl>
                </div>
                <div class="column is-3">
                  <h1>Keluhan mual</h1>
                  <Multiselect v-model="input.SKeluhanMual" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_tidakAda" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>
              <div class="column is-12 pb-0 pt-0">
                Eliminasi
              </div>
              <div class="columns column is-12 is-multiline">
                <div class="column is-3">
                  <h1>Masalah Perkemihan</h1>
                  <Multiselect v-model="input.SMasalahPerkemihan" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_tidakAda" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3" v-if="input.SMasalahPerkemihan == 2">
                  <h1>Ada</h1>
                  <Multiselect v-model="input.SMasalahPerkemihan_Ada" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_masalahPerkemihan" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3" v-if="input.SMasalahPerkemihan_Ada == 4">
                  <h1>&nbsp;</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBMasalahPerkemihanLainnya"
                      placeholder="Lainnya..." />
                  </VControl>
                </div>
                <div class="column is-12 pt-0 pb-0"></div>
                <div class="column is-3">
                  <h1>Warna Urine</h1>
                  <Multiselect v-model="input.SWarnaUrine" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_warnaUrine" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3">
                  <h1>Frekuensi</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBFrekuensi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1>Terakhir Jam</h1>
                  <VDatePicker v-model="input.TTJ_Frekuensi" mode="datetime" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:clock" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-12 pt-0 pb-0"></div>
                <div class="column is-3">
                  <h1>Masalah Defekasi</h1>
                  <Multiselect v-model="input.SMasalahDefekasi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_tidakAda" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3" v-if="input.SMasalahDefekasi == 2">
                  <h1>Ada</h1>
                  <Multiselect v-model="input.SMasalahDefekasi_Ada" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_masalahDefekasi" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3" v-if="input.SMasalahDefekasi_Ada == 6">
                  <h1>&nbsp;</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBMasalahDefekasiLainnya"
                      placeholder="Lainnya..." />
                  </VControl>
                </div>
                <div class="column is-12 pt-0 pb-0"></div>
                <div class="column is-3">
                  <h1>Warna Faeces</h1>
                  <Multiselect v-model="input.SWarnaFaeces" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_warnaFaeces" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3" v-if="input.SWarnaFaeces == 4">
                  <h1>&nbsp;</h1>
                  <Multiselect v-model="input.SPerdarahan_WF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_tidakYa" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3">
                  <h1>Frekuensi</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBFrekuensi_WF" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1>Terakhir Jam</h1>
                  <VDatePicker v-model="input.TTJ_Frekuensi_WF" mode="datetime" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:clock" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
              </div>
              <div class="column is-12 pb-0 pt-0">
                Istirahat tidur
              </div>
              <div class="column is-12 columns is-multiline">
                <div class="column is-3">
                  <h1>Lama tidur</h1>
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLamaTidur" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>jam/hari</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1>Kesulitan tidur</h1>
                  <Multiselect v-model="input.SKesulitanTidur" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_tidakYa" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>ASESMEN KEBUTUHAN INFORMASI DAN EDUKASI</h1>
              </div>
              <div class="column">
                Lihat pada form kebutuhan informasi dan edukasi
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>SKRINNING NUTRISI</h1>
              </div>
              <div class="column">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1>Penurunan BB 6 bulan terakhir?</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.penurunanbb" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_penurunanbb" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="input.penurunanbb == 'Ya'">
                    <h1>Ya, bila ya berapa penurunan berat badan</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.penurunanbbYa" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_penurunanbbYa" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1>Terjadi penurunan nafsu makan?</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.penurunannafsu" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_penurunannafsu" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <h1>Nilai</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaiSkrining"
                          disabled />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8"></div>
                  <!-- <div class="column is-4 pt-0">
                    <h1>Nilai</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaiSkrining"
                          disabled />
                      </VControl>
                    </VField>
                  </div> -->
                  <div class="column is-12 pt-0 pb-0">
                    <div class="column is-12 pt-0 pb-0">
                      <h1>Pasien dengan diagnosa khusus?</h1>
                    </div>
                    <div class="column is-4 columns pt-0">
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diagnosakhusus" true-value="YA"
                              label="Ya" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diagnosakhusus" true-value="TIDAK"
                              label="Tidak" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12 pt-0">
                    <div class="column is-12 pt-0">
                      <h1>Nilai</h1>
                    </div>
                    <div class="column is-12 pt-0 columns is-multiline">
                      <div class="column is-4 p-0">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.nilai" true-value="RISIKO RENDAH (MST 0-1)"
                              label="Risiko rendah (MST 0-1)" color="primary" circle disabled />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4 p-0">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.nilai" true-value="RISIKO SEDANG (MST 2-3)"
                              label="Risiko sedang (MST 2-3)" color="primary" circle disabled />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4 p-0">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.nilai" true-value="RISIKO TINGGI (MST 4-5)"
                              label="Risiko tinggi (MST 4-5)" color="primary" circle disabled />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>STATUS FUNGSIONAL</h1>
              </div>
              <div class="column">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <h1>Mengontrol BAB</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.mengontrolbab" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_mengontrolbab" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>Mengontrol BAK</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.mengontrolbak" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_mengontrolbak" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>Membersihkan diri</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.bersihdiri" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_bersihdiri" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <h1>Penggunaan toilet</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.toilet" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_toilet" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>Makan</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.makan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_makan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>Berpindah dari tempat tidur</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.berpindahtt" :attrs="{ value }" placeholder="--Pilih--"
                          label="label" :options="d_berpindahtt" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>Mobilisai / Berjalan</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.mobilisasi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_mobilisasi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>Berpakaian</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.berpakaian" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_berpakaian" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>Naik turun tangga</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.tangga" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_tangga" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>Mandi</h1>
                    <VField class="is-autocomplete-select">
                      <VControl>
                        <Multiselect v-model="input.mandi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_mandi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <h1>Nilai</h1>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaimandi"
                          disabled />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns is-multiline column is-12">
                    <div class="column is-12 pb-0">
                      <h1>Keterangan</h1>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan total (0-4)"
                          label="Ketergantungan total (0-4)" v-model="input.CBStatusFungsional" disabled />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan berat (5-8)"
                          label="Ketergantungan berat (5-8)" v-model="input.CBStatusFungsional" disabled />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan sedang (9-11)"
                          label="Ketergantungan sedang (9-11)" v-model="input.CBStatusFungsional" disabled />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Ketergantungan ringan(12-19)"
                          label="Ketergantungan ringan(12-19)" v-model="input.CBStatusFungsional" disabled />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Mandiri (20)" label="Mandiri (20)"
                          v-model="input.CBStatusFungsional" disabled />
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>ASESMEN RISIKO JATUH</h1>
              </div>
              <div class="column">
                <h1>Morse Fall Scale</h1>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Resiko Rendah"
                        label="Resiko Rendah : 0-7" v-model="input.CBResikoRendah" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Resiko Sedang"
                        label="Resiko Sedang : 8-13" v-model="input.CBResikoSedang" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Resiko Tinggi"
                        label="Resiko Tinggi : > 14" v-model="input.CBResikoTinggi" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>RIWAYAT PENGGUNAAN OBAT</h1>
              </div>
              <div class="column">
                <VField>
                  <VTextarea rows="2" v-model="input.TARiwayatPenggunaanObat"></VTextarea>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>RENCANA PEMULANGAN PASIEN</h1>
              </div>
              <div class="column">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <Multiselect v-model="input.SRPP" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_tidakYa" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>DIAGNOSA KEBIDANAN</h1>
              </div>
              <div class="column">
                <VField>
                  <VTextarea rows="2" v-model="input.TADiagnosaKebidanan"></VTextarea>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>RENCANA KEBIDANAN</h1>
              </div>
              <div class="column">
                <VField>
                  <VTextarea rows="2" v-model="input.TARencanaKebidanan"></VTextarea>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12 pb-0">
                <h1>PROSEDUR INVASIF</h1>
              </div>
              <div class="column">
                <div class="columns">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Infus Intra Vena"
                        label="Infus Intra Vena" v-model="input.CBInfusIntraVena" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Dipasang di</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBInfusIntraVena" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Tanggal</h1>
                    <VDatePicker v-model="input.DInfusIntraVena" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </div>
                </div>
                <div class="columns">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Dower chateter" label="Dower chateter"
                        v-model="input.CBDowerChateter" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Dipasang di</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBDowerChateter" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Tanggal</h1>
                    <VDatePicker v-model="input.DDowerChateter" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </div>
                </div>
                <div class="columns">
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Lain-lain" label="Lain-lain"
                        v-model="input.CBLainlain_PI" />
                    </VControl>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainlain_PI" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Dipasang di</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBLainlain_dipasang_PI" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <h1>Tanggal</h1>
                    <VDatePicker v-model="input.DLainlain_PI" mode="date" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12 pt-0">
            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
          </div>

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
                <h1>Tanda Tangan Bidan</h1>
                <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.CBBidan" :suggestions="d_Bidan" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                </VControl>
              </div>
            </div>
          </div>
        </div>

        <!-- form baru -->
      </div>
    </div>

  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/asesmen-awal-kebidanan-rj'

// Loopingan
let detailRiwayatKehamilan = ref(EMR.detailRiwayatKehamilan())
let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let detailRencanaKebidanan = ref(EMR.detailRencanaKebidanan())
let statusFungsional: any = ref(EMR.statusFungsional())

// Judul
useHead({ title: 'Asesmen Awal Kebidanan & Kandungan - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const user = useUserSession().getUser().pegawai;
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const { y } = useWindowScroll()
const dataTTD: any = ref([])
const isLoading = ref(false)
const isAktive = ref()
const route = useRoute()
const pasien: any = ref({})
const d_Bidan: any = ref([])
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const loadData: any = ref(true)
const COLLECTION: any = ref('AsesmenAwalKebidananDanKandungan') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  DTttd: new Date(),
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  TTJ_PMinum: new Date().setHours(0, 0, 0, 0),
  TTJ_PMakan: new Date().setHours(0, 0, 0, 0),
  TTJ_Frekuensi: new Date().setHours(0, 0, 0, 0),
  TTJ_Frekuensi_WF: new Date().setHours(0, 0, 0, 0),
  CBBidan: { label: user.namaLengkap, value: user.id },
  TBRiwayatKehamilan: [{
    no: 1,
  }]
})
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
  filter: '',
  airway: [],
  disability: []
})
const isStuck = computed(() => {
  return y.value > 30
})
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
const addNewItem = () => {
  input.value.TBRiwayatKehamilan.push({
    no: input.value.TBRiwayatKehamilan[input.value.TBRiwayatKehamilan.length - 1].no + 1,
  });
}

const removeItem = (index: any) => {
  input.value.TBRiwayatKehamilan.splice(index, 1)
}
const d_riwayatPK: any = ref([{ value: 'Tidak', label: 'Tidak ada' }, { value: 'Ya', label: 'Ya' }])
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Bidan.value = response
  })
}

const loadRiwayat = async () => {
  try {
    // Use await directly instead of .then()
    const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`);
    
    if (response.length) {
      input.value = response[0]; // Set ke inputan
      if (NOREC_EMRPASIEN.value === '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk;
      }
      dataTTD.value = response[0];
      H.tandaTangan().set("TTDBidan", dataTTD.value.TTDBidan);
    } else {
      // Await the second API call directly
      const response_IGD = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=TriagePasienObstetriGinekologiIGD&field=TBt_TTV,TBn_TTV,TBr_TTV,TBSat02_TTV,TBSuhuAxila_TTV,TBproduksiUrine_TTV,TBeGCS,TBvGCS,TBmGCS`);
      
      if (response_IGD != null) {
        input.value.GCSe = response_IGD.TBeGCS;
        input.value.GCSv = response_IGD.TBvGCS;
        input.value.GCSm = response_IGD.TBmGCS;
        input.value.TBStekananDarah = response_IGD.TBt_TTV;
        input.value.TBSnadi = response_IGD.TBn_TTV;
        input.value.TBSrespirasi = response_IGD.TBr_TTV;
        input.value.TBSsuhu = response_IGD.TBSuhuAxila_TTV;
        input.value.TBSSaO2 = response_IGD.TBSat02_TTV;
      }
    }
  } catch (error) {
    console.error('Error loading data:', error);
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object['TTDBidan'] = H.tandaTangan().get("TTDBidan");
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const simpanTemplate = () => {
  if(!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
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
  const skipKeys = ['id', '_id', 'namatemplate','DTanggalMasuk','DTanggalAsesmenAwal','SRujukan','STempatRujukan','TBDx_rujukan','kebpilihanallo','TBLainnya_Allo']; // Keys to be skipped

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
  H.alert('info', 'Riwayat berhasil ditambahkan')
}
const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
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

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
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

watch(() => [input.value.penurunanbb, input.value.penurunannafsu, input.value.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
  let totalNilaiSkriningKalkulasi
  //? Mencegah value checbox dari undefined
  newValuePenurunanBB = newValuePenurunanBB ?? 0;
  newValuePenurunanBBYa = newValuePenurunanBBYa ?? 0;
  newValuePenurunanNafsu = newValuePenurunanNafsu ?? 0;

  //? Calculate total Skrining Nutrisi
  totalNilaiSkriningKalkulasi = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
  input.value.nilaiSkrining = totalNilaiSkriningKalkulasi

  if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
    input.value.nilai = "RISIKO RENDAH (MST 0-1)";
  } else if (totalNilaiSkriningKalkulasi >= 2 && totalNilaiSkriningKalkulasi <= 3) {
    input.value.nilai = "RISIKO SEDANG (MST 2-3)";
  } else if (totalNilaiSkriningKalkulasi >= 4) {
    input.value.nilai = "RISIKO TINGGI (MST 4-5)";
  }
});

watch(() => [
  input.value.mengontrolbab,
  input.value.mengontrolbak,
  input.value.bersihdiri,
  input.value.toilet,
  input.value.makan,
  input.value.berpindahtt,
  input.value.mobilisasi,
  input.value.berpakaian,
  input.value.tangga,
  input.value.mandi,
], ([
  newValueMengontrolBab,
  newValueMengontrolBak,
  newValueBersihDiri,
  newValueToilet,
  newValueMakan,
  newValueBerpindahTT,
  newValueMobilisasi,
  newValueBerpakaian,
  newValueTangga,
  newValueMandi,
]) => {
  let totalNilaiStatusFungsional;
  //? Mencegah dari undefined
  newValueMengontrolBab = newValueMengontrolBab ?? 0;
  newValueMengontrolBak = newValueMengontrolBak ?? 0;
  newValueBersihDiri = newValueBersihDiri ?? 0;
  newValueToilet = newValueToilet ?? 0;
  newValueMakan = newValueMakan ?? 0;
  newValueBerpindahTT = newValueBerpindahTT ?? 0;
  newValueMobilisasi = newValueMobilisasi ?? 0;
  newValueBerpakaian = newValueBerpakaian ?? 0;
  newValueTangga = newValueTangga ?? 0;
  newValueMandi = newValueMandi ?? 0;

  //? Calculate Status Fungsional
  totalNilaiStatusFungsional = newValueMengontrolBab + newValueMengontrolBak + newValueBersihDiri + newValueToilet + newValueMakan + newValueBerpindahTT + newValueMobilisasi + newValueBerpakaian + newValueTangga + newValueMandi
  if (totalNilaiStatusFungsional > 20) {
    totalNilaiStatusFungsional = 20;
  }
  input.value.nilaimandi = totalNilaiStatusFungsional

  if (totalNilaiStatusFungsional >= 0 && totalNilaiStatusFungsional <= 4) {
    input.value.CBStatusFungsional = "Ketergantungan total (0-4)"
  } else if (totalNilaiStatusFungsional >= 5 && totalNilaiStatusFungsional <= 8) {
    input.value.CBStatusFungsional = "Ketergantungan berat (5-8)"
  } else if (totalNilaiStatusFungsional >= 9 && totalNilaiStatusFungsional <= 11) {
    input.value.CBStatusFungsional = "Ketergantungan sedang (9-11)"
  } else if (totalNilaiStatusFungsional >= 12 && totalNilaiStatusFungsional <= 19) {
    input.value.CBStatusFungsional = "Ketergantungan ringan(12-19)"
  } else if (totalNilaiStatusFungsional >= 20) {
    input.value.CBStatusFungsional = "Mandiri (20)"
  }
});

// ===== Array =====
const d_tidakAda: any = ref([
  { value: 1, label: 'Tidak' },
  { value: 2, label: 'Ada' }
])
const d_tidakYa: any = ref([
  { value: 1, label: 'Tidak' },
  { value: 2, label: 'Ya' }
])
const d_yaTidak: any = ref([
  { value: 1, label: 'Ya' },
  { value: 2, label: 'Tidak' }
])
const d_tidakAda_ada: any = ref([
  { value: 1, label: 'Tidak' },
  { value: 2, label: 'Ya' }
])
const d_penurunanbb: any = ref([
  { value: 0, label: 'Tidak' },
  { value: 2, label: 'Tidak Yakin' },
  { value: 1, label: '1-5 kg' },
  { value: 2, label: '6-10 kg' },
  { value: 3, label: '11-15 kg' },
  { value: 4, label: '>15 kg' }
]);
const d_penurunannafsu: any = ref([
  { value: 1, label: 'Ya' },
  { value: 0, label: 'Tidak' }
]);
const d_penurunanbbYa: any = ref([
  { value: 1, label: '1-5 kg' },
  { value: 2, label: '6-10 kg' },
  { value: 3, label: '11-15 kg' },
  { value: 4, label: '>15 kg' }
]);
const d_mengontrolbab: any = ref([
  { value: 0, label: 'Inkontinen/tidak teratur (perlu enema)' },
  { value: 1, label: 'Kadang inkontinen (1xseminggu)' },
  { value: 2, label: 'Kontinen teratur' }
]);

const d_mengontrolbak: any = ref([
  { value: 0, label: 'Inkontinen/pakai kateter dan tidak terkontrol' },
  { value: 1, label: 'Kadang inkontinen (max 1x24 jam)' },
  { value: 2, label: 'Mandiri' }
]);
const d_bersihdiri: any = ref([
  { value: 0, label: 'Butuh pertolongan orang lain' },
  { value: 1, label: 'Mandiri' }
]);
const d_toilet: any = ref([
  { value: 0, label: 'Tergantung pertolongan orang lain' },
  { value: 1, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' },
  { value: 2, label: 'Mandiri' }
]);
const d_makan: any = ref([
  { value: 0, label: 'Tidak mampu' },
  { value: 1, label: 'Perlu seseorang menolong memotong makanan' },
  { value: 2, label: 'Mandiri' }
]);
const d_berpindahtt: any = ref([
  { value: 0, label: 'Tidak Mampu' },
  { value: 1, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' },
  { value: 2, label: 'Bantuan 1 orang' },
  { value: 3, label: 'Mandiri' }
]);
const d_mobilisasi: any = ref([
  { value: 0, label: 'Tidak Mampu' },
  { value: 1, label: 'Dengan kursi roda' },
  { value: 2, label: 'Bantuan 1 orang' },
  { value: 3, label: 'Mandiri' }
]);
const d_berpakaian: any = ref([
  { value: 0, label: 'Tergantung orang lain' },
  { value: 1, label: 'Sebagian dibantu (misal mengancing baju)' },
  { value: 2, label: 'Mandiri' }
]);
const d_tangga: any = ref([
  { value: 0, label: 'Tidak Mampu' },
  { value: 1, label: 'Butuh Pertolongan' },
  { value: 2, label: 'Mandiri' }
]);
const d_tempatRujukan: any = ref([{ value: 1, label: 'RS' }, { value: 2, label: 'Puskesmas' }, { value: 3, label: 'dr.' }, { value: 4, label: 'Lainnya' }])
const d_mandi: any = ref([
  { value: 0, label: 'Teragantung orang lain' },
  { value: 1, label: 'Mandiri' }
]);
const d_keadaanumum: any = ref([
  { value: 1, label: 'Baik' },
  { value: 2, label: 'Sedang' },
  { value: 3, label: 'Lemah' },
  { value: 4, label: 'Jelek' }
])
const d_rujukan: any = ref([
  { value: 1, label: 'Ya' },
  { value: 2, label: 'Tidak' },
  { value: 3, label: 'Datang Sendiri' },
  { value: 4, label: 'Diantar' }
])
const d_allo: any = ref([
  { value: 1, label: 'Suami/Istri' },
  { value: 2, label: 'Orang Tua' },
  { value: 3, label: 'Anak' },
  { value: 4, label: 'Pasien' },
  { value: 5, label: 'Lainnya' }
])
const d_kualitasNyeri_AN: any = ref([
  { value: 1, label: 'Tumpul' },
  { value: 2, label: 'Tajam' },
  { value: 3, label: 'Panas/terbakar' },
  { value: 4, label: 'Lain-lain' }
])
const d_frekuensiNyeri_AN: any = ref([
  { value: 1, label: 'Jarang' },
  { value: 2, label: 'Hilang timbul' },
  { value: 3, label: 'Terus menerus' }
])
const d_kondisiPsikologis: any = ref([
  { value: 1, label: 'Gelisah' },
  { value: 2, label: 'Takut' },
  { value: 3, label: 'Sedih' },
  { value: 4, label: 'Rendah diri' },
  { value: 5, label: 'Acuh tak acuh' },
  { value: 6, label: 'Mudah tersinggung' },
  { value: 7, label: 'Menarik diri' }
])
const d_statusPernikahan: any = ref([
  { value: 1, label: 'Singel' },
  { value: 2, label: 'Menikah' },
  { value: 3, label: 'Bercerai' }
])
const d_pembiayaanKesehatan: any = ref([
  { value: 1, label: 'Biaya sendiri/keluarga' },
  { value: 2, label: 'Asuransi lainnya' }
])
const d_dukunganSosial: any = ref([
  { value: 1, label: 'Suami' },
  { value: 2, label: 'Orang tua' },
  { value: 3, label: 'Keluarga' },
  { value: 4, label: 'Lainnya' }
])
const d_kebiasaanIbu: any = ref([
  { value: 1, label: 'Merokok' },
  { value: 2, label: 'Minum alkohol' },
  { value: 3, label: 'Lainnya' }
])
const d_riwayatG: any = ref([
  { value: 1, label: 'Tidak ada' },
  { value: 2, label: 'Ada' }
])
const d_rpp = ref([
  { value: 1, label: 'Perlu' },
  { value: 2, label: 'Tidak Perlu' }
]);

// === Bikin Sendiri ===
const d_teraturSiklus: any = ref([
  { value: 1, label: 'Teratur' },
  { value: 2, label: 'Tidak Teratur' }
])
const d_ANC: any = ref([
  { value: 1, label: 'Dokter Kandungan' },
  { value: 2, label: 'Dokter Umum' },
  { value: 3, label: 'Bidan' },
  { value: 4, label: 'Lainnya' }
])
const d_frekuensi: any = ref([
  { value: 1, label: '1x' },
  { value: 2, label: '2x' },
  { value: 3, label: '3x' },
  { value: 4, label: '4x' }
])
const d_KSH: any = ref([
  { value: 1, label: 'Mual' },
  { value: 2, label: 'Muntah' },
  { value: 3, label: 'Perdarahan' },
  { value: 4, label: 'Pusing' },
  { value: 5, label: 'Sakit Kepala' },
  { value: 6, label: 'Lainnya' }
])
const d_RPK: any = ref([
  { value: 1, label: 'Hipertensi' },
  { value: 2, label: 'HIV' },
  { value: 3, label: 'Kencing Manis' },
  { value: 4, label: 'Jantung' },
  { value: 5, label: 'Jiwa' },
  { value: 6, label: 'Varises' },
  { value: 7, label: 'Lain-lain' },
])
const d_masalahPerkemihan: any = ref([
  { value: 1, label: 'Retensi Urine' },
  { value: 2, label: 'Inkontinensia Urine' },
  { value: 3, label: 'Dialysis' },
  { value: 4, label: 'Lainnya' }
])
const d_warnaUrine: any = ref([
  { value: 1, label: 'Kuning Jernih' },
  { value: 2, label: 'Keruh' },
  { value: 3, label: 'Kemerahan' }
]);
const d_masalahDefekasi: any = ref([
  { value: 1, label: 'Stoma' },
  { value: 2, label: 'Atresia ani' },
  { value: 3, label: 'Konstipasi' },
  { value: 4, label: 'Diare' },
  { value: 5, label: 'Inkontinensia alvi' },
  { value: 6, label: 'Lainnya' }
]);
const d_warnaFaeces: any = ref([
  { value: 1, label: 'Kuning' },
  { value: 2, label: 'Kecoklatan' },
  { value: 3, label: 'Kehitaman' },
  { value: 4, label: 'Perdarahan' }
]);
const d_masalahPernikahan: any = ref([
  { value: 1, label: 'Tidak ada' },
  { value: 2, label: 'Ada' }
])
const d_mengalamiKekerasanFisik: any = ref([
  { value: 1, label: 'Tidak ada' },
  { value: 2, label: 'Ada' }
])

const getTriageGinekologiObstetri = async () => {
  try {
    isLoading.value = true;
    const response = await useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=TriagePasienObstetriGinekologiIGD`
    );

    if (response.length > 0) {
      //TAKeluhanUtama,TARiwayatPengobatan,TARiwayatAlergi
      input.value.TAKeluhanUtama = response[0].TAKeluhanUtama;
      input.value.TARiwayatPengobatan = response[0].TARiwayatPengobatan;
      H.alert('info', 'Data berhasil dimuat');
    } else {
      isLoading.value = false;
      H.alert('warning', 'Data tidak ada');
    }
    isLoading.value = false;
  } catch (error) {
    console.error('Error loading riwayat:', error);
    isLoading.value = false;
    H.alert('error', 'Terjadi kesalahan saat memuat data.');
  }
};


</script>
<style lang="scss">
.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150% !important;
  ;
}

.tg td {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  background-color: rgb(193, 193, 193);
}

h1 {
  font-weight: bold !important;
}
</style>
