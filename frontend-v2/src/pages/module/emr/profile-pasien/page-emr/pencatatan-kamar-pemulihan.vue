<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <VCard radius="small">
    <h3 class="title is-5 mb-2">Pencatatan Kamar Pulih</h3>
    <div class="columns is-multiline">
      <div class="column is-3 mt-3">
        Masuk kamar pulih
      </div>
      <div class="column is-2">
        <VField class="pt-3">
          <VControl>
            <VInput v-model="input.jamSupine" type="time" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-3">
        Kesadaran
      </div>
      <div class="column is-3">
        <VControl raw subcontrol>
          <VCheckbox v-model="input.sadarbetul" label="Sadar Betul" color="primary" />
        </VControl>
      </div>
      <div class="column is-3">
        <VControl raw subcontrol>
          <VCheckbox v-model="input.belumSadar" label="Belum Sadar" color="primary" />
        </VControl>
      </div>
      <div class="column is-3">
        <VControl raw subcontrol>
          <VCheckbox v-model="input.tidurMalam" label="Tidur Dalam" color="primary" />
        </VControl>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-3">
        Pernafasan
      </div>
      <div class="column is-3">
        <VControl raw subcontrol>
          <VCheckbox v-model="input.spontan" label="Spontan" color="primary" />
        </VControl>
      </div>
      <div class="column is-3">
        <VControl raw subcontrol>
          <VCheckbox v-model="input.dibantu" label="Dibantu" color="primary" />
        </VControl>
      </div>
      <div class="column is-3">
        <VControl raw subcontrol>
          <VCheckbox v-model="input.vas" label="VAS" color="primary" />
        </VControl>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-3">
      </div>
      <div class="column is-9">
        <VField>
          <VControl>
            <VInput v-model="input.pernafasanText" type="text" placeholder="" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-3">
        Penyulit Intra Operatif
      </div>
      <div class="column is-9">
        <VField>
          <VControl>
            <VInput v-model="input.penyulitIntraOperatif" type="text" placeholder="" />
          </VControl>
        </VField>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-3">
        Intruksi Tambahan
      </div>
      <div class="column is-9">
        <VField>
          <VControl>
            <VInput v-model="input.intruksiTambahan" type="text" placeholder="" />
          </VControl>
        </VField>
      </div>
    </div>
  </VCard>

  <div class="column">
    <VCard>
      <h1 style="font-weight: bold;">Tanda Vital</h1>
      <div class="column">
        <div class="column is-12">
          <VCard style="border-radius: 16px;">
            <highcharts :options="chartOptions2"></highcharts>
            <!-- <Chart type="line" :data="chartData" :options="chartOptions" :height="100" class="h-30rem" /> -->
          </VCard>
        </div>
      </div>
      <div class="column">
        <span>Tanda Vital</span>
        <div class="column p-3" style="overflow: auto;">
          <table class="tg">
            <thead>
              <tr>
                <th width="10%" class="col-stuck">Waktu</th>
                <th v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['waktuPemerks_' + index]" type="time" placeholder="Pick an hour" />
                    </VControl>
                  </VField>

                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="col-stuck bg-colatas">
                  <span>Suhu</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['suhu_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Pernapasan</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['pernapasan_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Nadi</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['nadi_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Tekanan Darah</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['tekananDarah_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>SaturasiO2</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['saturasiO2_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </VCard>
  </div>

  <div class="column">
    <vCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <table class="table is-hoverable is-fullwidth">
            <thead style="text-align:center">
              <tr>
                <th style="width:5%">No</th>
                <th style="text-align:center; width:50%" colspan="2">
                  Kriteria Pemulihan Pasca Anastesi / Sedasi <br>
                  (Modifikasi Aldrette Recovery Score)
                </th>
                <th style="text-align:center">Masuk</th>
                <th colspan="4" style="text-align:center">
              <tr>
                <td style="text-align:center" colspan="4">
                  Menit
                </td>
              </tr>
              <tr>
                <td width="100">15</td>
                <td width="100">30</td>
                <td width="100">60</td>
                <td width="100">90</td>
              </tr>
              </th>
              <th>
                Keluar
              </th>
              </tr>
            </thead>
            <tbody>
              <tr style="height:10px; font-size:1rem;">
                <td>1</td>
                <td>
                  Dapat menggerakan ke-4 anggota badan sendri/dengan Perintah =2<br>
                  Dapat menggerakan ke-2 anggota badan sendri/dengan Perintah =1<br>
                  Dapat menggerakan ke-0 anggota badan sendri/dengan Perintah =0<br>
                </td>
                <td>Aktivitas</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars1Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars1Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars1Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars1Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars1Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.mars1Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td>2</td>
                <td>
                  Dapat nafas dalam dan batuk bebas = 2<br>
                  Dyspone atau nafas terbatas = 1<br>
                  Apnoe = 0<br>
                </td>
                <td>Respirasi</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars2Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars2Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars2Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars2Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars2Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.mars2Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td>3</td>
                <td>
                  Tekanan darah * 20% dari level pra-anestesia = 2<br>
                  Tekanan darah * 20-49% dari level pra-anestesia =1<br>
                  Tekanan darah * 50% dari level pra-anestesia =0<br>
                </td>
                <td>Sirkulasi</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars3Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars3Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars3Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars3Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars3Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.mars3Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td>4</td>
                <td>
                  Sadar penuh =2<br>
                  Dapat dibangunkan bila dipanggil =1<br>
                  Tidak bereaksii =0<br>
                </td>
                <td>Kesadaran</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars4Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars4Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars4Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars4Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars4Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.mars4Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td>5</td>
                <td>
                  SpO2 >=94% =2<br>
                  SpO2 90-94% =1<br>
                  SpO2 &lt;=90%=0<br>
                </td>
                <td>Oxygen</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars5Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars5Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars5Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars5Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.mars5Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.mars5Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td colspan="3">
                  Jumlah
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.marsJumlahMasuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.marsJumlahMenit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.marsJumlahMenit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.marsJumlahMenit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.marsJumlahMenit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.marsJumlahKeluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td colspan="2">
                  >=8 pindah ruangan
                </td>
                <td colspan="8">
                  <div class="columns is-multline">
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.marsRuangan" label="Ruangan" color="primary" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.marsPicuNicu" label="PICU / NICU" color="primary" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.marsIcuHcu" label="ICU / HCU" color="primary" />
                      </VControl>
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.marsMeninggal" label="Meninggal" color="primary" />
                      </VControl>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </vCard>

    <vCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <table class="table is-hoverable is-fullwidth">
            <thead>
              <tr>
                <th style="width:5%; " rowspan="2">No</th>
                <th style="text-align:center; width:50%; " colspan="2" rowspan="2">
                  Kriteria Pemulihan Pasca Anastesi / Sedasi Khusus Pasien<br>
                  ODS / Ambulatory<br>
                  (Postanesthesia Discharge Scoring System)
                </th>
                <th style="text-align:center; " rowspan="2">Masuk</th>
                <th colspan="4" style="">
              <tr>
                <td colspan="4" style="text-align:center; ">
                  Menit
                </td>
              </tr>
              <tr>
                <td width="100">15</td>
                <td width="100">30</td>
                <td width="100">60</td>
                <td width="100">90</td>
              </tr>
              </th>
              <th rowspan="2" style="">
                Keluar
              </th>
              </tr>
            </thead>
            <tbody>
              <tr style="height:10px; font-size:1rem;">
                <td>1</td>
                <td width="40%">
                  Tanda vital +/-20% dari basis preoperatif =2<br>
                  Tanda vital +/-20 - 40 dari basis preoperatif =1<br>
                  Tanda vital > 40 dari bisnis preoperatif =0<br>
                </td>
                <td>Tanda Vital</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss1Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss1Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss1Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss1Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss1Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.pdss1Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td>2</td>
                <td>
                  Dapat Bberjalan tegak, tidak pusing = 2<br>
                  Butuh bantuan asisten = 1<br>
                  Tidak mampu berjalan = 0<br>
                </td>
                <td>Aktivitas</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss2Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss2Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss2Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss2Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss2Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.pdss2Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td>3</td>
                <td>
                  Minimal, hilang dengan terapi oral = 2<br>
                  Sedang, hilang dengan tetap parenteral =1<br>
                  Continues, sesudah pemberian terapi berulang =0<br>
                </td>
                <td>Nausea dan Muntah</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss3Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss3Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss3Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss3Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss3Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.pdss3Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td>4</td>
                <td>
                  Ya =2<br>
                  Tidak =1<br>
                  Tidak =0<br>
                </td>
                <td>Nyeri minimal, dapat toleransi</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss4Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss4Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss4Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss4Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss4Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.pdss4Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td>5</td>
                <td>
                  minimal (tidak dibutuhkan penggantian kassa) =2<br>
                  Sedan (dibutuhkan 2x pergantian) =1<br>
                  Berat (3x atau lebih penggantian)<br>
                </td>
                <td>Pendarahan</td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss5Masuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss5Menit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss5Menit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss5Menit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdss5Menit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.pdss5Keluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td colspan="3">
                  Jumlah
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdssJumlahMasuk" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td colspan="4">
              <tr>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdssJumlahMenit15" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdssJumlahMenit30" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdssJumlahMenit60" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.pdssJumlahMenit90" rows="1"></VTextarea>
                    </VControl>
                  </VField>
                </td>
              </tr>
              </td>
              <td>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.pdssJumlahKeluar" rows="1"></VTextarea>
                  </VControl>
                </VField>
              </td>
              </tr>
              <tr style="height:10px; font-size:1rem;">
                <td colspan="2">
                  >=8 pindah ruangan
                </td>
                <td colspan="8">
                  <div class="columns is-multline">
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pdssRuangan" label="Ruangan" color="primary" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pdssIcuHcu" label="ICU / HCU" color="primary" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pdssMeninggal" label="Meninggal" color="primary" />
                      </VControl>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </vCard>
    <vCard>
      <div class="columns is-multiline">
        <div class="column is-4">
          Jam keluar kamar pulih
        </div>
        <div class="column is-2">
          <VField>
            <VDatePicker v-model="input.jamKeluarKamarPulih" mode="time" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:clock" fullwidth>
                    <VInput :value="inputValue" placeholder="Jam" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4" style="text-align: center;">
          <P>
            Perawat Ruangan
          </P>
          <TandaTangan :elemenID="'signaturePerawatRuangan'" :width="'180'" :height="'180'" class="dek" />
          <div class="column">
            <span class="label-apas">Nama jelas & tanda tangan</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawatRuangan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="setTandaTanganPerawatRuangan($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <P>
            Mengetahui
          </P>
          <TandaTangan :elemenID="'signatureMengetahui'" :width="'180'" :height="'180'" class="dek" />
          <div class="column">
            <span class="label-apas">Nama jelas & tanda tangan</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.mengetahui" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="setTandaTanganMengetahui($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <P>
            Perawat Kamar Bedah
          </P>
          <TandaTangan :elemenID="'signaturePerawatKamarBedah'" :width="'180'" :height="'180'" class="dek" />
          <div class="column">
            <span class="label-apas">Nama jelas & tanda tangan</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawatKamarBedah" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="setTandaTanganPerawatKamarBedah($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          Jam Pasien Pulang
        </div>
        <div class="column is-2">
          <VField>
            <VDatePicker v-model="input.jamPasienPulang" mode="time" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:clock" fullwidth>
                    <VInput :value="inputValue" placeholder="Jam" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
        <div class="column is-4">
          (Khusus pasien ODS)
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4" style="text-align: center;">
          <P>
            Perawat Ruangan
          </P>
          <TandaTangan :elemenID="'signaturePerawatRuanganJP'" :width="'180'" :height="'180'" class="dek" />
          <div class="column">
            <span class="label-apas">Nama jelas & tanda tangan</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawatRuanganJP" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="setTandaTanganPerawatRuanganJP($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <P>
            Mengetahui
          </P>
          <TandaTangan :elemenID="'signatureMengetahuiJP'" :width="'180'" :height="'180'" class="dek" />
          <div class="column">
            <span class="label-apas">Nama jelas & tanda tangan</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.mengetahuiJP" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="setTandaTanganMengetahuiJP($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <P>
            Perawat Kamar Bedah
          </P>
          <TandaTangan :elemenID="'signaturePerawatKamarBedahJP'" :width="'180'" :height="'180'" class="dek" />
          <div class="column">
            <span class="label-apas">Nama jelas & tanda tangan</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawatKamarBedahJP" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" @item-select="setTandaTanganPerawatKamarBedahJP($event)"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </vCard>
  </div>

  <div class="column">
    <!-- form emr -->
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Chart from 'primevue/chart';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

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

let chartOptions2 = reactive({
  chart: {
    type: 'spline',
  },
  title: {
    text: ''
  },
  credits: {
    enabled: false
  },
  xAxis: {
    categories: []
  },
  yAxis: {
    title: {
      text: 'Jumlah'
    }
  },
  legend: { enabled: true },
  plotOptions: {
    line: {
      dataLabels: {
        enabled: true
      },
      enableMouseTracking: false
    },
    spline: {
      dataLabels: {
        enabled: true,
        // style: {
        //   fontSize: '20px'
        // },
      },
      enableMouseTracking: false
    }
  },
  series: []
});

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const jumlahIndex = ref(15)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const chartData = ref();
const chartOptions = ref();
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].ttdPR) {
          H.tandaTangan().set("signaturePerawatRuangan", response[0].ttdPR)
        }
        if (response[0].ttdM) {
          H.tandaTangan().set("signatureMengetahui", response[0].ttdM)
        }
        if (response[0].ttdPKB) {
          H.tandaTangan().set("signaturePerawatKamarBedah", response[0].ttdPKB)
        }
        if (response[0].ttdPRJP) {
          H.tandaTangan().set("signaturePerawatRuanganJP", response[0].ttdPRJP)
        }
        if (response[0].ttdMJP) {
          H.tandaTangan().set("signatureMengetahuiJP", response[0].ttdMJP)
        }
        if (response[0].ttdPKBJP) {
          H.tandaTangan().set("signaturePerawatKamarBedahJP", response[0].ttdPKBJP)
        }
        chartHigh(response[0])
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }

      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.ttdPR = H.tandaTangan().get("signaturePerawatRuangan")
  object.ttdM = H.tandaTangan().get("signatureMengetahui")
  object.ttdPKB = H.tandaTangan().get("signaturePerawatKamarBedah")
  object.ttdPRJP = H.tandaTangan().get("signaturePerawatRuanganJP")
  object.ttdMJP = H.tandaTangan().get("signatureMengetahuiJP")
  object.ttdPKBJP = H.tandaTangan().get("signaturePerawatKamarBedahJP")
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
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const setTandaTanganPerawatRuangan = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePerawatRuangan", element.ttd)
    } else {
      H.tandaTangan().set("signaturePerawatRuangan", '')
    }
  })
}

const setTandaTanganMengetahui = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signatureMengetahui", element.ttd)
    } else {
      H.tandaTangan().set("signatureMengetahui", '')
    }
  })
}

const setTandaTanganPerawatKamarBedah = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePerawatKamarBedah", element.ttd)
    } else {
      H.tandaTangan().set("signaturePerawatKamarBedah", '')
    }
  })
}
const setTandaTanganPerawatRuanganJP = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePerawatRuanganJP", element.ttd)
    } else {
      H.tandaTangan().set("signaturePerawatRuanganJP", '')
    }
  })
}

const setTandaTanganMengetahuiJP = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signatureMengetahuiJP", element.ttd)
    } else {
      H.tandaTangan().set("signatureMengetahuiJP", '')
    }
  })
}

const setTandaTanganPerawatKamarBedahJP = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePerawatKamarBedahJP", element.ttd)
    } else {
      H.tandaTangan().set("signaturePerawatKamarBedahJP", '')
    }
  })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const chartHigh = (e: any) => {

  let labels = []
  let seriesNadi = []
  let seriesSuhu = []
  let seriesPernafasan = []
  let seriesSaturasiO2 = []
  for (let x = 0; x < jumlahIndex.value; x++) {
    if (e['suhu_' + x.toString()] != undefined) {
      seriesSuhu.push(parseFloat(e['suhu_' + x.toString()]))
    }
    if (e['nadi_' + x.toString()] != undefined) {
      seriesNadi.push(parseFloat(e['nadi_' + x.toString()]))
    }
    if (e['pernapasan_' + x.toString()] != undefined) {
      seriesPernafasan.push(parseFloat(e['pernapasan_' + x.toString()]))
    }
    if (e['saturasiO2_' + x.toString()] != undefined) {
      seriesSaturasiO2.push(parseFloat(e['saturasiO2_' + x.toString()]))
    }
    if (e['waktuPemerks_' + x.toString()] != undefined) {
      labels.push(e['waktuPemerks_' + x.toString()])
    }
  }
  chartOptions2.xAxis.categories = labels
  chartOptions2.series =
    [{
      name: 'Suhu',
      color: 'red',
      lineWidth: 4,
      marker: {
        radius: 4
      },
      data: seriesSuhu
    }, {
      name: 'Nadi',
      color: 'blue',
      data: seriesNadi
    }, {
      name: 'Saturasi O2',
      color: 'green',
      data: seriesSaturasiO2
    }, {
      name: 'Pernafasan',
      data: seriesPernafasan
    }],
    console.log(chartOptions2)

}
const setChartData = (e: any) => {

  let labels = []
  let seriesNadi = []
  let seriesSuhu = []
  let seriesPernafasan = []
  let seriesSaturasiO2 = []
  for (let x = 0; x < jumlahIndex.value; x++) {
    if (e['suhu_' + x.toString()] != undefined) {
      seriesSuhu.push(parseFloat(e['suhu_' + x.toString()]))
    }
    if (e['waktuPemerks_' + x.toString()] != undefined) {
      labels.push(e['waktuPemerks_' + x.toString()])
    }
  }

  const documentStyle = getComputedStyle(document.documentElement);

  // for (var i = data.length - 1; i >= 0; i--) {
  //   const element = data[i]
  //   labels.push(H.formatDate(element.tanggal, 'lll'))
  //   seriesNadi.push((element.nadi ? parseFloat(element.nadi) : 0))
  //   seriesSuhu.push((element.suhu ? parseFloat(element.suhu) : 0))
  //   seriesPernafasan.push((element.pernapasan ? parseFloat(element.pernapasan) : 0))
  //   seriesPernafasan.push((element.saturasi02 ? parseFloat(element.saturasi02) : 0))
  // }
  return {
    labels: labels,
    datasets: [
      {
        label: 'Nadi',
        data: seriesNadi,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--blue-500')
      },
      {
        label: 'Suhu',
        data: seriesSuhu,
        fill: false,
        borderDash: [5, 5],
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--teal-500')
      },
      {
        label: 'Pernafasan',
        data: seriesPernafasan,
        fill: true,
        borderColor: documentStyle.getPropertyValue('--orange-500'),
        tension: 0.4,
        backgroundColor: 'rgba(255,167,38,0.2)'
      },
      {
        label: 'Saturasi O2',
        data: seriesPernafasan,
        fill: true,
        borderColor: documentStyle.getPropertyValue('rgb(65, 171, 242)'),
        tension: 0.4,
        backgroundColor: 'rgba(255,167,38,0.2)'
      }
    ]
  };
};

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue('--text-color');
  const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
  const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

  return {
    maintainAspectRatio: false,
    aspectRatio: 0.6,
    plugins: {
      legend: {
        labels: {
          color: textColor
        }
      }
    },
    scales: {
      x: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      },
      y: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      }
    }
  };
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()
</script>

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
</style>
