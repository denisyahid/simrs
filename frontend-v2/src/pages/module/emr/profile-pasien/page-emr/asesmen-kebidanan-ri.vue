<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideCetak="true">
            </ButtonEmr>
          </div>
        </div>
      </div>

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
        <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
            template</span></h1>
        <VField>
          <VControl>
            <VTextarea v-model="input.namatemplate" rows="1">
            </VTextarea>
          </VControl>
        </VField>
      </div>

      <div class="column is-12">
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
          <div class="column is-4">
            <h1>Rujukan</h1>
            <Multiselect v-model="input.SRujukan" :attrs="{ value }" placeholder="--Pilih--" label="label"
              :options="d_rujukan" :searchable="true" track-by="label" mode="single" autocomplete="off">
            </Multiselect>
          </div>
          <div class="column is-4" v-if="input.SRujukan == 1">
            <h1>Dari :</h1>
            <Multiselect v-model="input.STempatRujukan" :attrs="{ value }" placeholder="--Pilih--" label="label"
              :options="d_tempatRujukan" :searchable="true" track-by="label" mode="single" autocomplete="off">
            </Multiselect>
          </div>
          <div class="column is-4" v-if="input.SRujukan == 4">
            <h1>&nbsp;</h1>
            <VControl>
              <VInput type="text" class="input" v-model="input.TBDiantar" placeholder="Diantar oleh..." />
            </VControl>
          </div>
          <div class="column is-4" v-if="input.STempatRujukan == 1 && input.SRujukan == 1">
            <h1>Rumah Sakit</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="Rumah sakit..." v-model="input.TBRujuk_RS" />
            </VControl>
          </div>
          <div class="column is-4" v-if="input.STempatRujukan == 2 && input.SRujukan == 1">
            <h1>Puskesmas</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="Puskesmas..." v-model="input.TBRujuk_Puskesmas" />
            </VControl>
          </div>
          <div class="column is-4" v-if="input.STempatRujukan == 3 && input.SRujukan == 1">
            <h1>dr.</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="dr..." v-model="input.TBRujuk_dr" />
            </VControl>
          </div>
          <div class="column is-4" v-if="input.STempatRujukan == 4 && input.SRujukan == 1">
            <h1>Lainnya</h1>
            <VControl>
              <VInput type="text" class="input" placeholder="Lainnya..." v-model="input.TBRujuk_Lainnya" />
            </VControl>
          </div>
          <div class="column is-4">
            <h1>Dx.rujukan</h1>
            <VControl>
              <VInput type="text" class="input" v-model="input.TBDx_rujukan" />
            </VControl>
          </div>
          <div class="column is-4">
            <h1>Alloanamnesis</h1>
            <VField class="is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:search">
                <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </VControl>
            </VField>
          </div>
          <div class="column is-4" v-if="input.kebpilihanallo == 4">
            <h1>Lainnya</h1>
            <VControl>
              <VInput type="text" class="input" v-model="input.TBLainnya_Allo" placeholder="Lainnya..." />
            </VControl>
          </div>
        </div>
        <hr>
        <div class="columns is-multiline column">
          <div class="column is-12 pt-0 pb-0"
            style="text-align: center;font-size: large;background-color: lightgray !important;">
            <h1>ANAMNESIS</h1>
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
          <div class="column is-6">
            <h1>Riwayat Penyakit Dahulu :</h1>
            <VField>
              <VTextarea rows="2" v-model="input.TARiwayatPenyakitDahulu"></VTextarea>
            </VField>
          </div>
          <div class="column is-6">
            <h1>Riwayat Pengobatan :</h1>
            <VField>
              <VTextarea rows="2" v-model="input.TARiwayatPengobatan"></VTextarea>
            </VField>
          </div>
        </div>
        <hr>
        <div class="column is-12 pt-1" style="text-align: center;">
          <h1>Riwayat Kehamilan dan Persalinan Yang Lalu</h1>
          <div style="overflow: auto;" class="mt-3">
            <table class="table-rpo">
              <thead>
                <tr>
                  <th class="th-rpo" rowspan="3">No</th>
                  <th class="th-rpo" rowspan="3">Tgl Partus</th>
                  <th class="th-rpo" colspan="3">Umur Hamil</th>
                  <th class="th-rpo" rowspan="3">Jenis Partus</th>
                  <th class="th-rpo" colspan="2">Penolong</th>
                  <th class="th-rpo" colspan="3">Anak</th>
                  <th class="th-rpo" colspan="3">Keadaan Anak Sekarang</th>
                  <th class="th-rpo" rowspan="3">Keterangan / Komplikasi</th>
                  <th class="th-rpo" rowspan="3">#</th>
                </tr>
                <tr>
                  <th class="th-rpo" rowspan="2">Abortus</th>
                  <th class="th-rpo" rowspan="2">Prematur</th>
                  <th class="th-rpo" rowspan="2">Aterm</th>
                  <th class="th-rpo" rowspan="2">Nakes</th>
                  <th class="th-rpo" rowspan="2">Non Nakes</th>
                  <th class="th-rpo" colspan="2">JK</th>
                  <th class="th-rpo" rowspan="2">BBL</th>
                  <th class="th-rpo" colspan="2">Hidup</th>
                  <th class="th-rpo" rowspan="2">Meninggal</th>
                </tr>
                <tr>
                  <th class="th-rpo">L</th>
                  <th class="th-rpo">P</th>
                  <th class="th-rpo">Normal</th>
                  <th class="th-rpo">Cacat</th>
                </tr>
              </thead>
              <tbody v-for="(item, index) in input.detailRK" :key="index">
                <tr>
                  <td class="td-rpo">
                    {{ index + 1 }}
                  </td>
                  <td class="td-rpo">
                    <VDatePicker v-model="item.tanggalPartus" mode="date" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.abortus" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.prematur" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.aterm" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.jenisPartus" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.nakes" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.nonKelas" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.L" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.P" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.BBL" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.Hidup_Normal" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.Hidup_Cacat" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.meninggal" />
                    </VControl>
                  </td>
                  <td class="td-rpo">
                    <VControl>
                      <VInput type="text" class="input" v-model="item.keterangan" />
                    </VControl>
                  </td>
                  <td class="td-rpo p-0">
                    <div class="column">
                      <VButtons style="justify-content:space-around">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                          v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </VButtons>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <hr>
        <div class="column is-12">
          <div class="columns">
            <div class="column is-4">
              <h1>Riwayat Pemakaian Kontrasepsi</h1>
              <Multiselect v-model="input.SRiwayatPK" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_riwayatPK" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4" v-if="input.SRiwayatPK == 2">
              <h1>Jenis</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBJenisPK" placeholder="Jenis..." />
              </VControl>
            </div>
            <div class="column is-4" v-if="input.SRiwayatPK == 2">
              <h1>Lama Pemakaian</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBLamaPemakaianPK" placeholder="Lama Pemakaian..." />
              </VControl>
            </div>
          </div>
        </div>
        <hr>
        <div class="column">
          <h1>Riwayat Persalinan Sekarang</h1>
          <div class="columns is-multiline m-0">
            <div class="column is-3 pl-0">
              <h1>Tanggal dan jam persalinan</h1>
              <VDatePicker v-model="input.DTPersalinan_RPS" mode="datetime" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-3 pl-0">
              <h1>Tempat Persalinan</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBTempatPersalinan" />
              </VControl>
            </div>
            <div class="column is-3 pl-0">
              <h1>Komplikasi pada Kala I</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBKomplikasi_Kala_I" />
              </VControl>
            </div>
            <div class="column is-3 pl-0">
              <h1>Komplikasi pada Kala II</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBKomplikasi_Kala_II" />
              </VControl>
            </div>
            <div class="column is-3 pl-0">
              <h1>Komplikasi pada Kala III</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBKomplikasi_Kala_III" />
              </VControl>
            </div>
            <div class="column is-3 pl-0">
              <h1>Komplikasi pada Kala IV</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBKomplikasi_Kala_IV" />
              </VControl>
            </div>
          </div>
        </div>
        <hr>
        <div class="column">
          <h1>Riwayat Penyakit Keluarga</h1>
          <div class="columns is-multiline m-0">
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Hipertensi" label="Hipertensi"
                  v-model="input.CBHipertensi_RPK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Kencing Manis" label="Kencing Manis"
                  v-model="input.CBKencingManis_RPK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="HIV" label="HIV" v-model="input.CBHIV_RPK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Jantung" label="Jantung"
                  v-model="input.CBJantung_RPK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Varises" label="Varises"
                  v-model="input.CBVarises_RPK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Hepatitis" label="Hepatitis"
                  v-model="input.CBHepatitis_RPK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Asthma" label="Asthma"
                  v-model="input.CBAsthma_RPK" />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Tumor" label="Tumor"
                  v-model="input.CBTumor_RPK" />
              </VControl>
            </div>
            <div class="column is-3" v-if="input.CBTumor_RPK == 'Tumor'">
              <VControl>
                <VInput type="text" class="input" v-model="input.TBTumor_RPK" placeholder="Tumor..." />
              </VControl>
            </div>
            <div class="column is-3">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Lainnya" label="Lainnya"
                  v-model="input.CBLainnya_RPK" />
              </VControl>
            </div>
            <div class="column is-3" v-if="input.CBLainnya_RPK == 'Lainnya'">
              <VControl>
                <VInput type="text" class="input" v-model="input.TBLainnya_RPK" placeholder="Lainnya..." />
              </VControl>
            </div>
          </div>
        </div>
        <hr>
        <div class="column">
          <div class="columns">
            <div class="column is-4">
              <h1>Riwayat Operasi</h1>
              <Multiselect v-model="input.SRiwayatOperasi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_riwayatO" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-8" v-if="input.SRiwayatOperasi == 2">
              <h1>Jelaskan</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRiwayatOperasi" />
              </VControl>
            </div>
          </div>
        </div>
        <div class="column">
          <div class="columns">
            <div class="column is-4">
              <h1>Riwayat Transfusi Darah</h1>
              <Multiselect v-model="input.SRiwayatTD" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_riwayatTD" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-8" v-if="input.SRiwayatTD == 2">
              <h1>Jelaskan</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRiwayatTD" />
              </VControl>
            </div>
          </div>
        </div>
        <div class="column">
          <div class="columns">
            <div class="column is-4">
              <h1>Riwayat Ginekologi</h1>
              <Multiselect v-model="input.SRiwayatGinekologi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_riwayatG" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-8" v-if="input.SRiwayatGinekologi == 2">
              <h1>Jelaskan</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRiwayatGinekologi" />
              </VControl>
            </div>
          </div>
        </div>
        <div class="column">
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
                    <VInput type="text" class="input" v-model="input.TBAlergiMakanan" placeholder="Alergi makanan..." />
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
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>STATUS FISIK</h1>
        </div>
        <div class="column columns is-multiline pb-0">
          <div class="column is-3">
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
        </div>
        <div class="column pt-0 pb-0">
          <h1>Tanda-tanda vital</h1>
        </div>
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-2">
              <h1>Suhu</h1>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBcelciusTTV" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>°C</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1>Respirasi</h1>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBPernafasanTTV" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/mnt</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1>Nadi</h1>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBnadiTTV" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/mnt</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1>Tekanan Darah</h1>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBtekananDarahTTV" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mmHg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1>SaO2</h1>
              <VField addons>
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBnspo2TTV" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>%</VButton>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <hr>
        <div class="column pb-0">
          <h1>Pemeriksaan fisik</h1>
        </div>
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-3">
              <h1>1. Wajah</h1>
              <div class="column pt-0 pb-0">
                <Multiselect v-model="input.SWajah_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_wajah_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
            </div>
            <div class="column is-12">
              <h1>2. Mata</h1>
              <div class="columns m-0 column is-6 p-0">
                <div class="column is-6 pl-3 pt-0 pb-0">
                  <h1>Konjungtiva</h1>
                  <Multiselect v-model="input.SMata_K_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_mata_K_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-6 pl-3 pt-0 pb-0">
                  <h1>Sklera</h1>
                  <Multiselect v-model="input.SMata_S_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_mata_S_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <h1>3. Bibir</h1>
              <div class="column pt-0 pb-0">
                <h1>Mulut</h1>
                <Multiselect v-model="input.SBibir_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_mulut_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </div>
            </div>
            <div class="column is-12">
              <h1>4. Leher</h1>
              <div class="columns m-0 column is-9 p-0">
                <div class="column is-4 pl-3 pt-0 pb-0">
                  <h1>a) Kelenjar limfe</h1>
                  <Multiselect v-model="input.SLeher_A_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_leher_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-4 pl-3 pt-0 pb-0">
                  <h1>b) Kelenjar tiroid</h1>
                  <Multiselect v-model="input.SLeher_B_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_leher_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-4 pl-3 pt-0 pb-0">
                  <h1>c) Vena jugularis</h1>
                  <Multiselect v-model="input.SLeher_C_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_leher_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1>5. Payudara</h1>
              <div class="columns is-multiline m-0">
                <div class="column is-3 pl-3 pt-0 pb-0">
                  <h1>a) Bentuk</h1>
                  <Multiselect v-model="input.SPayudara_A_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_payudara_A_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pl-3 pt-0 pb-0">
                  <h1>b) Puting</h1>
                  <Multiselect v-model="input.SPayudara_B_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_payudara_B_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pl-3 pt-0 pb-0">
                  <h1>c) Lecet pada puting susu</h1>
                  <Multiselect v-model="input.SPayudara_C_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_payudara_C_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pl-3 pt-0 pb-0">
                  <h1>d) Pengeluaran</h1>
                  <Multiselect v-model="input.SPayudara_D_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_payudara_D_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pl-3 pb-0">
                  <h1>e) Kebersihan</h1>
                  <Multiselect v-model="input.SPayudara_E_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_payudara_E_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pl-3 pb-0">
                  <h1>f) Bengkak</h1>
                  <Multiselect v-model="input.SPayudara_F_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_payudara_F_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1>6. Dada</h1>
              <div class="columns m-0 column is-6 p-0">
                <div class="column is-6 pl-3 pt-0 pb-0">
                  <h1>a) Bentuk</h1>
                  <Multiselect v-model="input.SDada_A_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_dada_A_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-6 pt-0 pl-3 pb-0">
                  <h1>b) Retraksi</h1>
                  <Multiselect v-model="input.SDada_B_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_dada_B_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1>7. Perut</h1>
              <div class="columns m-0 is-multiline column is-12 p-0">
                <div class="column is-12 pt-1">
                  <h1>Tinggi fundus uteri</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBTinggiFundusUteri" />
                  </VControl>
                </div>
                <div class="column is-6 pl-3 pt-0 pb-0">
                  <h1>a. Inspeksi</h1>
                  <div class="columns">
                    <div class="column is-6">
                      <h1>Bekas luka operasi</h1>
                      <Multiselect v-model="input.SPerut_AA_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_perut_AA_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </div>
                    <div class="column is-6">
                      <h1>Kandung kemih</h1>
                      <Multiselect v-model="input.SPerut_AB_PF" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_perut_AB_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </div>
                  </div>
                </div>
                <div class="column is-6 pt-0 pb-0">
                  <h1>b. Palpasi</h1>
                  <div class="columns">
                    <div class="column is-6">
                      <h1>Kontraksi uterus</h1>
                      <Multiselect v-model="input.SPerut_B_KU_PF" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="d_perut_B_PF" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </div>
                    <div class="column is-6">
                      <h1>Nyeri tekan</h1>
                      <Multiselect v-model="input.SPerut_B_NT_PF" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="d_perut_B_PF" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1>8. Ekstremitas bawah</h1>
              <div class="columns m-0 is-multiline column is-12 p-0">
                <div class="column is-3 pt-1">
                  <h1>Tungkai</h1>
                  <Multiselect v-model="input.STungkai" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_ekstremitas_A_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>Oedema</h1>
                  <Multiselect v-model="input.SOedema" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_ekstremitas_B_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>Varises</h1>
                  <Multiselect v-model="input.SVarises" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_ekstremitas_B_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>Tanda homan</h1>
                  <Multiselect v-model="input.STandaHoman" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_ekstremitas_B_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>

            </div>
            <div class="column is-12 pt-0">
              <h1>Inspeksi genitalia</h1>
              <div class="columns m-0 is-multiline column is-12 p-0">
                <div class="column is-3 pt-1">
                  <h1>a) Kebersihan</h1>
                  <Multiselect v-model="input.SKebersihan_IG" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_IG_A_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>b) Pengeluaran Lokhea</h1>
                  <Multiselect v-model="input.SPengeluaranLokhea_IG" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_IG_B_PF" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>c) Hematoma</h1>
                  <Multiselect v-model="input.SHematoma_IG" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_IG_C_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>d) Jahitan perineum</h1>
                  <Multiselect v-model="input.SJahitanPerineum_IG" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_IG_D_PF" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1" v-if="input.SJahitanPerineum_IG == 1">
                  <h1>Keadaan Jahitan</h1>
                  <Multiselect v-model="input.SKeadaanJahitan_IG" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_IG_D2_PF" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>e) Penyembuhan luka perineum</h1>
                  <Multiselect v-model="input.SPenyembuhanLukaPerineum_IG" :attrs="{ value }" placeholder="--Pilih--"
                    label="label" :options="d_IG_E_PF" :searchable="true" track-by="label" mode="single"
                    autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>f) Tanda infeksi</h1>
                  <Multiselect v-model="input.STandaInfeksi_IG" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_IG_F_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
                <div class="column is-3 pt-1">
                  <h1>Kondisi anus</h1>
                  <Multiselect v-model="input.SKondisiAnus_IG" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_IG_KA_PF" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </div>
              </div>

            </div>
          </div>
        </div>
        <hr>
        <div class="column pb-0 pt-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>ASESMEN NYERI</h1>
        </div>
        <div class="column">
          <div class="columns is-multiline">
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
        <hr>
        <div class="column pb-0 pt-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL</h1>
        </div>
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1>Kondisi Psikologis</h1>
              <Multiselect v-model="input.SKondisiPsikologis" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_kondisiPsikologis" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>Kemandirian</h1>
              <Multiselect v-model="input.SKemandirian" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_kemandirian" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>Fase adaptasi psikologi</h1>
              <Multiselect v-model="input.SFaseAP" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_FaseAP" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-12 pb-0 pt-0">
                  <h1>Penilaian Bonding Score</h1>
                </div>
                <div class="column is-4">
                  <h1>Melihat</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBMelihat_PBS" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <h1>Meraba</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBMeraba_PBS" />
                  </VControl>
                </div>
                <div class="column is-4">
                  <h1>Menyapa atau suara</h1>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.TBMenyapa_PBS" />
                  </VControl>
                </div>
              </div>
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
            <div class="column is-4" v-else></div>
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
              <Multiselect v-model="input.SPembiayaanKesehatan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_pembiayaanKesehatan" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4" v-if="input.SPembiayaanKesehatan == 2">
              <h1>&nbsp;</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBAsuransiLainnya" placeholder="Asuransi..." />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Rencana Menyusui Secara Ekslusif</h1>
              <Multiselect v-model="input.SRencanaMenyusuiSE" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_rencanaMenyusuiSE" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>Rencana Pengasuhan Bayi</h1>
              <Multiselect v-model="input.SRencanaPB" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_rencanaPB" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4" v-if="input.SRencanaPB == 2">
              <h1>Yaitu</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBDibantuOlehOrangLain" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Rencana Pemakaian Alat Kontrasepsi</h1>
              <Multiselect v-model="input.SRencanaPAK" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_rencanaPAK" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4" v-if="input.SRencanaPAK == 7">
              <h1>Rencana mulai pemakaian</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRencanaMulaiPemakaianImplant" />
              </VControl>
            </div>
          </div>
          <hr>
          <div class="columns is-multiline">
            <div class="column is-12 pb-0">
              Pengetahuan ibu tentang :
            </div>
            <div class="column is-4">
              <h1>1. Bahaya masa nifas</h1>
              <Multiselect v-model="input.SBahayaMasaNifas" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-8">
              <h1>2. Cara memeriksa kontraksi dan masase fundus uteri</h1>
              <Multiselect v-model="input.SBCaraMemeriksaKontraksi" :attrs="{ value }" placeholder="--Pilih--"
                label="label" :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single"
                autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>3. Cara menyusui yang benar</h1>
              <Multiselect v-model="input.SCaraMenyusuiYangBenar" :attrs="{ value }" placeholder="--Pilih--"
                label="label" :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single"
                autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>4. ASI ekslusif</h1>
              <Multiselect v-model="input.SASIEkslusif" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>5. Alat Kontrasepsi</h1>
              <Multiselect v-model="input.SAlatKontrasepsi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>6. Cara memerah dan menampung ASI</h1>
              <Multiselect v-model="input.SCaraMMA" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>7. Cara memperbanyak produksi ASI</h1>
              <Multiselect v-model="input.SCaraMPA" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>8. Cara merawat luka jahitan parineum</h1>
              <Multiselect v-model="input.SCaraMerawatLJP" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-4">
              <h1>9. Senam kegel dan senam nifas</h1>
              <Multiselect v-model="input.SSenamKegel" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_tahuTidakTahu" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
          </div>
          <hr>
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1>Kebiasaan adat istiadat yang mempengaruhi kesehatan</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBKebiasaanAdatIstiadat" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Dukungan sosial dari</h1>
              <Multiselect v-model="input.SDukunganSosialDari" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_dukunganSosial" :searchable="true" track-by="label" mode="single" autocomplete="off">
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
                <VDatePicker v-model="input.TTJ_PMakan" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:clock" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
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
                <VDatePicker v-model="input.TTJ_PMinum" mode="time" is24hr>
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
                  <VInput type="text" class="input" v-model="input.TBKesulitanMakan" placeholder="Kesulitan makan..." />
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
                  <VInput type="text" class="input" v-model="input.TBPantanganMakan" placeholder="Pantangan makan..." />
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
                <Multiselect v-model="input.SMasalahPerkemihan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_tidakAda" :searchable="true" track-by="label" mode="single" autocomplete="off">
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
                <VDatePicker v-model="input.TTJ_Frekuensi" mode="time" is24hr>
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
                  <VInput type="text" class="input" v-model="input.TBMasalahDefekasiLainnya" placeholder="Lainnya..." />
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
                <VDatePicker v-model="input.TTJ_Frekuensi_WF" mode="time" is24hr>
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
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>ASESMEN KEBUTUHAN INFORMASI DAN EDUKASI</h1>
        </div>
        <div class="column">
          Lihat pada form kebutuhan informasi dan edukasi
        </div>
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>SKRINNING NUTRISI</h1>
        </div>
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1>Penurunan BB 6 bulan terakhir?</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.penurunanbb" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_penurunanbb" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1>Ya, bila ya berapa penurunan berat badan</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.penurunanbbYa" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_penurunanbbYa" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1>Terjadi penurunan nafsu makan?</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.penurunannafsu" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_penurunannafsu" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-8"></div>
            <div class="column is-4 pt-0">
              <h1>Nilai</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaiSkrining" disabled />
                </VControl>
              </VField>
            </div>
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
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>STATUS FUNGSIONAL</h1>
        </div>
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-3">
              <h1>Mengontrol BAB</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.mengontrolbab" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_mengontrolbab" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Mengontrol BAK</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.mengontrolbak" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_mengontrolbak" :searchable="true" track-by="label" mode="single" autocomplete="off">
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
                  <Multiselect v-model="input.berpindahtt" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_berpindahtt" :searchable="true" track-by="label" mode="single" autocomplete="off">
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
                  <VInput type="text" class="heightinput input" placeholder="" v-model="input.nilaimandi" disabled />
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
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>ASESMEN RESIKO JATUH</h1>
        </div>
        <div class="column">
          <h1>Morse Fall Scale</h1>
          <div class="columns is-multiline">
            <div class="column is-4">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Resiko Rendah" label="Resiko Rendah : 0-7"
                  v-model="input.CBResikoRendah" />
              </VControl>
            </div>
            <div class="column is-4">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Resiko Sedang" label="Resiko Sedang : 8-13"
                  v-model="input.CBResikoSedang" />
              </VControl>
            </div>
            <div class="column is-4">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Resiko Tinggi" label="Resiko Tinggi : > 14"
                  v-model="input.CBResikoTinggi" />
              </VControl>
            </div>
          </div>
        </div>
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>RIWAYAT PENGUNAAN OBAT</h1>
        </div>
        <div class="column">
          <VField>
            <VTextarea rows="2" v-model="input.TARiwayatPenggunaanObat"></VTextarea>
          </VField>
        </div>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>RENCANA PEMULANGAN PASIEN</h1>
        </div>
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-3">
              <Multiselect v-model="input.SRencanaPP" :attrs="{ value }" placeholder="--Pilih--" label="label"
                :options="d_perluTidakPerlu" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </div>
            <div class="column is-12 pt-0 pb-0"></div>
            <div class="column is-6">
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRPP_1" placeholder="1..." />
              </VControl>
            </div>
            <div class="column is-6">
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRPP_2" placeholder="2..." />
              </VControl>
            </div>
            <div class="column is-6">
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRPP_3" placeholder="3..." />
              </VControl>
            </div>
            <div class="column is-6">
              <VControl>
                <VInput type="text" class="input" v-model="input.TBRPP_4" placeholder="4..." />
              </VControl>
            </div>
          </div>
        </div>
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>DIAGNOSA KEBIDANAN</h1>
        </div>
        <div class="column">
          <VField>
            <VTextarea rows="2" v-model="input.TADiagnosaKebidanan"></VTextarea>
          </VField>
        </div>
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>RENCANA KEBIDANAN</h1>
        </div>
        <div class="column">
          <VField>
            <VTextarea rows="2" v-model="input.TARencanaKebidanan"></VTextarea>
          </VField>
        </div>
        <hr>
        <div class="column pt-0 pb-0"
          style="text-align: center;font-size: large;background-color: lightgray !important;">
          <h1>PROSEDUR INVASIF</h1>
        </div>
        <div class="column">
          <div class="columns">
            <div class="column is-4">
              <VControl raw subcontrol>
                <VCheckbox class="p-0" color="primary" square true-value="Infus Intra Vena" label="Infus Intra Vena"
                  v-model="input.CBInfusIntraVena" />
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
                <VCheckbox class="p-0" color="primary" square true-value="Central Line (CVC)" label="Central Line (CVC)"
                  v-model="input.CBCentralLine" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Dipasang di</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBCentralLine" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Tanggal</h1>
              <VDatePicker v-model="input.DCentralLine" mode="date" trim-weeks :max-date="new Date()">
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
                <VCheckbox class="p-0" color="primary" square true-value="Selang NGT" label="Selang NGT"
                  v-model="input.CBSelangNGT" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Dipasang di</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBSelangNGT" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Tanggal</h1>
              <VDatePicker v-model="input.DSelangNGT" mode="date" trim-weeks :max-date="new Date()">
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
                <VCheckbox class="p-0" color="primary" square true-value="Trakeostomy" label="Trakeostomy"
                  v-model="input.CBTrakeostomy" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Dipasang di</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBTrakeostomy" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Tanggal</h1>
              <VDatePicker v-model="input.DTrakeostomy" mode="date" trim-weeks :max-date="new Date()">
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
                <VCheckbox class="p-0" color="primary" square true-value="EET/Ventilator" label="EET/Ventilator"
                  v-model="input.CBVentilator" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Dipasang di</h1>
              <VControl>
                <VInput type="text" class="input" v-model="input.TBVentilator" />
              </VControl>
            </div>
            <div class="column is-4">
              <h1>Tanggal</h1>
              <VDatePicker v-model="input.DVentilator" mode="date" trim-weeks :max-date="new Date()">
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
        <hr>
        <div class="column">
          <div class="columns">
            <div class="column is-8"></div>
            <div class="column is-4">
              <VField label="Garut">
                <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
              <div class="column" style="text-align:center;">
                <h1>Nama dan tanda tangan</h1>
                <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.CBBidan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                </VControl>
              </div>
            </div>
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
                    width="20%">Halaman</td>
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
                    <span class="mb-2">{{ resep.index_tabs }}</span><br>
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
    @close="showModalTemplateFix = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Template</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
              <thead>
                <tr>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="5%">No</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">Tanggal Dibuat</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="20%">Nama Ruangan</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="25%">Nama Template</td>
                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                    width="15%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplateFix">
                <tr>
                  <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.no }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                    <span class="mb-2">{{ resep.namatemplate }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
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
</template>

<script setup lang="ts">
//======== Referensi Asesmen Awal Kebidanan RJ ========
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as H from '/@src/utils/appHelper'
import Fieldset from 'primevue/fieldset';
import { useConfirm } from "primevue/useconfirm"
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment'

useHead({ title: 'Asesmen Kebidanan Rawat Inap - ' + import.meta.env.VITE_PROJECT })

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const user = useUserSession().getUser().pegawai;
const pasien: any = ref({})
const isLoadingPasien: any = ref(false)
const modalConfirm: any = ref(false)
const confirm = useConfirm();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const COLLECTION: any = ref('AsesmenKebidananRI') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_ko: any = ref('')
const d_Dokter: any = ref([])
const dataSourceICD9: any = ref([])
const dataSourceICD10: any = ref([])
const isPemeriksaanFisik: any = ref(true)
const { y } = useWindowScroll()
const isLoading = ref(false)
const listTemplate: any = ref([])
const d_Pegawai: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isStuck = computed(() => {
  return y.value > 30
})


// ==================== Array Input ==================
const d_penurunanbb: any = ref([{ value: 0, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 0, label: 'Tidak' }])
const d_penurunanbbYa: any = ref([
  { value: 1, label: '1-5 kg' },
  { value: 2, label: '6-10 kg' },
  { value: 3, label: '11-15 kg' },
  { value: 4, label: '>15 kg' }
])
const d_rujukan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }, { value: 3, label: 'Datang Sendiri' }, { value: 4, label: 'Diantar' }])
const d_tempatRujukan: any = ref([{ value: 1, label: 'RS' }, { value: 2, label: 'Puskesmas' }, { value: 3, label: 'dr.' }, { value: 4, label: 'Lainnya' }])
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Lainnya' }])
const d_riwayatPK: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ya' }])
const d_riwayatO: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_riwayatTD: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_riwayatG: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_riwayatAlergi: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Ya' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Lemah' }, { value: 4, label: 'Jelek' }])
const d_wajah_PF: any = ref([{ value: 1, label: 'Oedema' }, { value: 2, label: 'Pucat' }, { value: 3, label: 'Normal' }])
const d_mata_K_PF: any = ref([{ value: 1, label: 'Merah muda' }, { value: 2, label: 'Pucat' }, { value: 3, label: 'Merah' }])
const d_mata_S_PF: any = ref([{ value: 1, label: 'Putih' }, { value: 2, label: 'Ikterus' }])
const d_mulut_PF: any = ref([{ value: 1, label: 'Pucat' }, { value: 2, label: 'Merah muda' }, { value: 3, label: 'Kebiruan' }])
const d_leher_PF: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Ada pembesaran' }])
const d_payudara_A_PF: any = ref([{ value: 1, label: 'Simetris' }, { value: 2, label: 'Tidak ada simetris' }])
const d_payudara_B_PF: any = ref([{ value: 1, label: 'Menonjol' }, { value: 2, label: 'Datar' }, { value: 3, label: 'Masuk' }])
const d_payudara_C_PF: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_payudara_D_PF: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Colostrum' }, { value: 3, label: 'ASI' }])
const d_payudara_E_PF: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Cukup' }, { value: 3, label: 'Kurang' }])
const d_payudara_F_PF: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_dada_A_PF: any = ref([{ value: 1, label: 'Simetris' }, { value: 2, label: 'Tidak simetris' }])
const d_dada_B_PF: any = ref([{ value: 1, label: 'Ada' }, { value: 2, label: 'Tidak ada' }])
const d_perut_AA_PF: any = ref([{ value: 1, label: 'Ada' }, { value: 2, label: 'Tidak ada' }])
const d_perut_AB_PF: any = ref([{ value: 1, label: 'Penuh' }, { value: 2, label: 'Tidak penuh' }])
const d_perut_B_PF: any = ref([{ value: 1, label: 'Ada' }, { value: 2, label: 'Tidak ada' }])
const d_ekstremitas_A_PF: any = ref([{ value: 1, label: 'Simetris' }, { value: 2, label: 'Tidak simetris' }])
const d_ekstremitas_B_PF: any = ref([{ value: 1, label: 'Ada' }, { value: 2, label: 'Tidak ada' }])
const d_IG_A_PF: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Cukup' }, { value: 3, label: 'Kurang' }])
const d_IG_B_PF: any = ref([{ value: 1, label: 'Rubra' }, { value: 2, label: 'Sanguinolenta' }, { value: 3, label: 'Serosa' }, { value: 4, label: 'Alba' }, { value: 5, label: 'Pendarahan aktif' }, { value: 6, label: 'Nanah' }])
const d_IG_C_PF: any = ref([{ value: 1, label: 'Ada' }, { value: 2, label: 'Tidak ada' }])
const d_IG_D_PF: any = ref([{ value: 1, label: 'Ada' }, { value: 2, label: 'Tidak ada' }])
const d_IG_D2_PF: any = ref([{ value: 1, label: 'Utuh' }, { value: 2, label: 'Terlepas' }])
const d_IG_E_PF: any = ref([{ value: 1, label: 'Redness' }, { value: 2, label: 'Edema' }, { value: 3, label: 'Ecchymosis' }, { value: 4, label: 'Discharge' }, { value: 5, label: 'Approximation' }])
const d_IG_F_PF: any = ref([{ value: 1, label: 'Ada' }, { value: 2, label: 'Tidak ada' }])
const d_IG_KA_PF: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Hemoroid' }])
const d_frekuensiNyeri_AN: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang timbul' }, { value: 3, label: 'Terus menerus' }])
const d_kualitasNyeri_AN: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_kondisiPsikologis: any = ref([{ value: 1, label: 'Gelisah' }, { value: 2, label: 'Takut' }, { value: 3, label: 'Sedih' }, { value: 4, label: 'Rendah diri' }, { value: 5, label: 'Acuh tak acuh' }, { value: 6, label: 'Mudah tersinggung' }, { value: 7, label: 'Menarik diri' }])
const d_kemandirian: any = ref([{ value: 1, label: 'Sangat memerlukan bantuan' }, { value: 2, label: 'Memerlukan sedikit bantuan' }, { value: 3, label: 'Sudah mampu mandiri' }])
const d_FaseAP: any = ref([{ value: 1, label: 'Taking in' }, { value: 2, label: 'Taking hold' }, { value: 3, label: 'Letting go' }])
const d_masalahPernikahan: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_statusPernikahan: any = ref([{ value: 1, label: 'Singel' }, { value: 2, label: 'Menikah' }, { value: 3, label: 'Bercerai' }])
const d_mengalamiKekerasanFisik: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaanKesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi lainnya' }])
const d_rencanaMenyusuiSE: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_rencanaPB: any = ref([{ value: 1, label: 'Sendiri' }, { value: 2, label: 'Dibantu oleh orang lain' }])
const d_rencanaPAK: any = ref([{ value: 1, label: 'Tidak menggunakan' }, { value: 2, label: 'Pil' }, { value: 3, label: 'Suntik 1 bulan' }, { value: 4, label: 'Suntik 3 bulan' }, { value: 5, label: 'Kondom' }, { value: 6, label: 'IUD' }, { value: 7, label: 'Implant' }])
const d_tahuTidakTahu: any = ref([{ value: 1, label: 'Tahu' }, { value: 2, label: 'Tidak tahu' }])
const d_yaTidak: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_dukunganSosial: any = ref([{ value: 1, label: 'Suami' }, { value: 2, label: 'Orang tua' }, { value: 3, label: 'Keluarga' }, { value: 4, label: 'Lainnya' }])
const d_kebiasaanIbu: any = ref([{ value: 1, label: 'Merokok' }, { value: 2, label: 'Minum alkohol' }, { value: 3, label: 'Lainnya' }])
const d_tidakYa: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Ya' }])
const d_tidakAda: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Ada' }])
const d_masalahPerkemihan: any = ref([{ value: 1, label: 'Retensi Urine' }, { value: 2, label: 'Inkontinensia Urine' }, { value: 3, label: 'Dialysis' }, { value: 4, label: 'Lainnya' }])
const d_warnaUrine: any = ref([{ value: 1, label: 'Kuning Jernih' }, { value: 2, label: 'Keruh' }, { value: 3, label: 'Kemerahan' }])
const d_masalahDefekasi: any = ref([{ value: 1, label: 'Stoma' }, { value: 2, label: 'Atresia ani' }, { value: 3, label: 'Konstipasi' }, { value: 4, label: 'Diare' }, { value: 5, label: 'Inkontinensia alvi' }, { value: 6, label: 'Lainnya' }])
const d_warnaFaeces: any = ref([{ value: 1, label: 'Kuning' }, { value: 2, label: 'Kecoklatan' }, { value: 3, label: 'Kehitaman' }, { value: 4, label: 'Perdarahan' }])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 0, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 1, label: 'Kadang inkontinen (1xseminggu)' }, { value: 2, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 0, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 1, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 2, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 0, label: 'Butuh pertolongan orang lain' }, { value: 1, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 2, label: 'Mandiri' }, { value: 0, label: 'Tergantung pertolongan orang lain' }, { value: 1, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }])
const d_makan: any = ref([{ value: 0, label: 'Tidak mampu' }, { value: 1, label: 'Perlu seseorang menolong memotong makanan' }, { value: 2, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 2, label: 'Bantuan 1 orang' }, { value: 3, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Dengan kursi roda' }, { value: 2, label: 'Bantuan 1 orang' }, { value: 3, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 0, label: 'Tergantung orang lain' }, { value: 1, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 2, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Butuh Pertolongan' }, { value: 2, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 0, label: 'Teragantung orang lain' }, { value: 1, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const d_perluTidakPerlu: any = ref([{ value: 1, label: 'Perlu' }, { value: 2, label: 'Tidak Perlu' }])
const dataTTD: any = ref([])
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
const input: any = ref({
  detailRK: [{ no: 1, }],
  TBMelihat_PBS: "1/      2/      3/      4",
  TBMeraba_PBS: "1/      2/      3/      4",
  TBMenyapa_PBS: "1/      2/      3/      4",
  TTJ_PMinum: new Date().setHours(0, 0, 0, 0),
  TTJ_PMakan: new Date().setHours(0, 0, 0, 0),
  TTJ_Frekuensi: new Date().setHours(0, 0, 0, 0),
  TTJ_Frekuensi_WF: new Date().setHours(0, 0, 0, 0),
  nilaiSkrining: 0,
  penurunanbb: 0,
  penurunannafsu: 0,
  penurunanbbYa: 0,
  nilai: "RISIKO RENDAH (MST 0-1)",

  // mengontrolbab: 0,
  // mengontrolbak: 0,
  // bersihdiri: 0,
  // toilet: 0,
  // makan: 0,
  // berpindahtt: 0,
  // mobilisasi: 0,
  // berpakaian: 0,
  // tangga: 0,
  // mandi: 0,
  nilaimandi: 0,
  CBKetergantunganTotal: "Ketergantungan total (0-4)",
  DTanggalAsesmenAwal: new Date(),
})

// ==================== Function ==================
const addNewItem = () => {
  input.value.detailRK.push({
    no: input.value.detailRK[input.value.detailRK.length - 1].no + 1,
  });
}

const removeItem = (index: any) => {
  input.value.detailRK.splice(index, 1)
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const loadRiwayat = async () => {
  isLoading.value = true
  let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  isLoading.value = false
  if (responsex.length) {
    input.value = responsex[0];
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
    }
    dataTTD.value = responsex[0]
    H.tandaTangan().set("TTDBidan", dataTTD.value.TTDBidan)
  } else {
    input.value.CBBidan = { label: user.namaLengkap, value: user.id }
  }
}

const setAutoFill = async () => {
  try {
    const response_kandungan = await useApi().get(
      `emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalKebidananDanKandungan&field=TAKeluhanUtama,TARiwayatPenyakitSekarang,TARiwayatPenyakitDahulu,TARiwayatPengobatan,TBRiwayatKehamilan`
    );

    if (response_kandungan) {
      input.value.TAKeluhanUtama = response_kandungan.TAKeluhanUtama || "";
      input.value.TARiwayatPenyakitSekarang = response_kandungan.TARiwayatPenyakitSekarang || "";
      input.value.TARiwayatPenyakitDahulu = response_kandungan.TARiwayatPenyakitDahulu || "";
      input.value.TARiwayatPengobatan = response_kandungan.TARiwayatPengobatan || "";

      if (response_kandungan.TBRiwayatKehamilan && Array.isArray(response_kandungan.TBRiwayatKehamilan)) {
        input.value.detailRK = [];

        input.value.detailRK = response_kandungan.TBRiwayatKehamilan.map((item) => ({
          abortus: item.DAbortus || "",
          prematur: item.DPrematur || "",
          aterm: item.DAterm || "",
          jenisPartus: item.DJenispartus || "",
          nakes: item.DNakes || "",
          nonKelas: item.DNon || "",
          L: item.DJKelamin === "Laki-laki" ? "V" : "",
          P: item.DJKelamin === "Perempuan" ? "V" : "",
          BBL: item.DBBL || "",
          Hidup_Normal: item.DHidup === "Normal" ? "Normal" : "",
          Hidup_Cacat: item.DHidup === "Cacat" ? "Cacat" : "",
          meninggal: item.DHidup === "Meninggal" ? "Meninggal" : "",
          keterangan: item.Keterangan || "",
        }));
      }
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  object.nocm = pasien.value.nocm
  object['TTDBidan'] = H.tandaTangan().get("TTDBidan");
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

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
    }).catch((e: any) => {
      isLoading.value = false
    })
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
const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

onMounted(async () => {
  modalConfirm.value = true
})

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
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
  if (totalNilaiStatusFungsional >= 20) {
    input.value.nilaimandi = 20
  } else {
    input.value.nilaimandi = totalNilaiStatusFungsional
  }

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

setAutoFill();
</script>


<style lang="scss">
.fontcheckbox {
  padding: 5px
}

h1 {
  font-weight: bold;
}

hr {
  margin-top: 10px;
  margin-bottom: 10px;
}

.table-rpo {
  width: 180% !important;
  border: 1px solid;
  border-collapse: collapse;
}

.th-rpo,
.td-rpo {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
  width: auto;
}

.th-rpo {
  text-align: center !important;
}
</style>
