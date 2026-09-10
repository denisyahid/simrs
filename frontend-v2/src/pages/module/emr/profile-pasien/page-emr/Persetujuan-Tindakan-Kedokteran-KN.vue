<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Persetujuan Tindakan Kedokteran KN</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :isHideCetak="true" isHideST>
            </ButtonEmr>
          </div>
        </div>
      </div>

      <!-- form baru -->

      <VModal :open="showModalRiwayat" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalRiwayat = false">
        <template #content>
          <form class="modal-form">
            <div class="column is-12 pt-0 pb-0">
              <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
              <div style="overflow-y:auto;" class="mt-1">
                <table style="border: 1px solid black;" v-if="listTemplate.length > 0">
                  <thead>
                    <tr>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="5%">#</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="10%">Tanggal
                        Input</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="10%">Tanggal
                        Registrasi</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">Nama
                        Ruangan</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">No
                        Registrasi</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid black;" width="20%">Pegawai</td>
                    </tr>
                  </thead>
                  <tbody v-for="resep in listTemplate">
                    <tr>
                      <td
                        style="width:5%;text-align:center;vertical-align: middle;border: 1px solid black;padding: 5px;">
                        <VIconButton type="button" raised circle icon="fas fa-eye" @click="addRiwayat(resep)"
                          color="info" v-tooltip-prime.top="'Pilih'">
                        </VIconButton>
                      </td>
                      <td style="width:10%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.created_at }}</span><br>
                      </td>
                      <td style="width:10%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                      </td>
                      <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                      </td>
                      <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                      </td>
                      <td style="width:20%;text-align:center;vertical-align: middle;border: 1px solid black;">
                        <span class="mb-2">{{ resep.user_input.namalengkap }}</span><br>
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
        <div class="column buttons mb-0 mt-0 p-1" style="margin:10px;vertical-align:middle">
          <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :loading="isLoading"
            @click="pilihTemplate()"> Pilih Riwayat
          </VButton>
          <VButton type="button" rounded outlined color="warning" raised icon="feather:file-text" :loading="isLoading"
            @click="buatBaru()"> Buat Baru
          </VButton>
        </div>

        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

        <div class="column">
          <div class="column pt-0 pb-1" style="text-align: center;">
            <h1 style="font-weight: bold;">PEMBERIAN INFORMASI (INFORMATION)</h1>
          </div>
          <table class="table is-bordered m-0" style="width: 100% !important;">
            <tr>
              <th style="width: 30%;">
                Dokter Pelaksana Tindakan<br>
                <i>Doctor in charge / operator</i>
              </th>
              <th style="width: 70%;">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th style="width: 30%;">
                Pemberi Informasi<br>
                <i>Informer</i>
              </th>
              <th style="width: 70%;">
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.DDPemberiInformasi" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th style="width: 30%;">
                Peneriman Informasi/Pemberi Persetujuan<br>
                <i>Recipient / Approver*</i>
              </th>
              <th style="width: 70%;">
                <VControl>
                  <VInput type="text" class="input" v-model="input.TBPemberiPersetujuan" />
                </VControl>
              </th>
            </tr>
          </table>
          <table class="table is-bordered  m-0" style="width: 100% !important;">
            <thead>
              <tr>
                <th style="text-align: center;">JENIS INFORMASI<br><i>Type of information</i></th>
                <th style="text-align: center;">ISI INFORMASI<br><i>Description of the information</i></th>
                <th style="text-align: center;">TANDA (✓)<br><i>Checklist (✓)</i></th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th>1. Diagnosis (WD Dan DD)<br><i>Diagnosis</i></th>
                <th>
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextDiagnosisWD" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;">
                  <VCheckbox v-model="input.pemeriksaanDiagnosis" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  2. Dasar Diagnosis<br>
                  <i>Underlying Diagnosis</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.Diagnosistextdasar" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-cumn">
                  <VCheckbox v-model="input.diagnosisTextDasar" true-value="Ya" color="primary" />
                </th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th>
                  3. Tindakan Kedokteran<br>
                  <i>Medical Action</i>
                </th>
                <th>
                  <VField>
                    <VControl raw subcontrol class="mr-4">
                      <VCheckbox class="p-0" color="primary" square true-value="Diagnostik" label="Diagnostik"
                        v-model="input.pemeriksaantextTindakanKedokteran" />
                      <VTextarea v-model="input.pemeriksaantextTindakanKedokteranT" true-value="a" color="primary"
                        rows="1" />
                    </VControl>
                    <VControl raw subcontrol class="mr-4">
                      <VCheckbox class="p-0" color="primary" square true-value="Terapi" label="Terapi"
                        v-model="input.terapi" />
                    </VControl>
                    <VControl raw subcontrol class="mr-4">
                      <VCheckbox class="p-0" color="primary" square true-value="I-131" label="I-131"
                        v-model="input.iAja" />
                    </VControl>
                    <VControl raw subcontrol class="mr-4">
                      <VCheckbox class="p-0" color="primary" square true-value="Samarium 153" label="Samarium 153"
                        v-model="input.samarium" />
                    </VControl>
                    <VControl raw subcontrol class="mr-4">
                      <VCheckbox class="p-0" color="primary" square true-value="Phospor 32" label="Phospor 32"
                        v-model="input.phospor" />
                    </VControl>
                    <VControl raw subcontrol class="mr-4">
                      <VTextarea v-model="input.bebas" true-value="a" color="primary" rows="1" />
                    </VControl>
                    <!-- <VCheckbox class="p-0" style="color:red !important" square true-value="Gawat Darurat" label="Pasien Gawat Darurat" v-model="input.pemeriksaantextTindakanKedokteran" /> -->
                  </VField>
                </th>
                <th style="text-align: center;">
                  <VCheckbox v-model="input.TindakanKedokteranCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  4. Indikasi Tindakan<br>
                  <i>Indication of Action</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextIndikasiTindakan" true-value="a" color="primary"
                      rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.pemeriksaantextIndikasiTindakanCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  5. Tata Cara<br>
                  <i>Procedures</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VControl raw subcontrol class="mr-4">
                      <VCheckbox class="p-0" color="primary" square true-value="Oral (diminum)" label="Oral (diminum)"
                        v-model="input.Oral" />
                    </VControl>
                    <VControl raw subcontrol class="mr-4">
                      <VCheckbox class="p-0" color="primary" square
                        true-value="Intra vena (melalui pembuluh darah vena)"
                        label="Intra vena (melalui pembuluh darah vena)" v-model="input.IntraVena" />
                    </VControl>
                    <VControl raw subcontrol class="mr-4">
                      <VTextarea v-model="input.pemeriksaantextTataCara" true-value="a" color="primary" rows="2" />
                    </VControl>
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.pemeriksaantextTataCaraCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <!-- <tr>
                <th>
                  6. Tujuan<br>
                  <i>Purpose</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextTujuan" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.tujuanCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr> -->
              <tr>
                <th>
                  6. Risiko<br>
                  <i>Risk</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextRisiko" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.pemeriksaantextRisikoCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
              <tr>
                <th>
                  7. Komplikasi<br>
                  <i>Complication</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextKomplikasi" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;" class="yes-column">
                  <VCheckbox v-model="input.pemeriksaantextKomplikasiCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th>
                  8. Prognosis<br>
                  <i>Prognosis</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextPrognosis" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;">
                  <VCheckbox v-model="input.pemeriksaantextPrognosisCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
            </thead>
            <tr>
              <th>
                9. Alternatif Dan Risko<br>
                <i>Alternative & Risk</i>
              </th>
              <th class="yes-column">
                <VField>
                  <VControl raw subcontrol class="mr-4">
                    <VCheckbox class="p-0" color="primary" square true-value="Tidak sedang hamil (Not pregnant)"
                      label="Tidak sedang hamil (Not pregnant)" v-model="input.tidakSedangHamil" />
                  </VControl>
                  <VControl raw subcontrol class="mr-4">
                    <VCheckbox class="p-0" color="primary" square true-value="Tidak sedang menyusui (Not breastfeeding)"
                      label="Tidak sedang menyusui (Not breastfeeding)" v-model="input.tidakSedangMenyusui" />
                  </VControl>
                  <VControl raw subcontrol class="mr-4">
                    <VCheckbox class="p-0" color="primary" square
                      true-value="Tidak boleh hamil selama 6 bulan pasca terapi (Do not pregnant 6 month after therapy)"
                      label="Tidak boleh hamil selama 6 bulan pasca terapi (Do not pregnant 6 month after therapy)"
                      v-model="input.tidakBolehHamil" />
                  </VControl>
                  <VControl raw subcontrol class="mr-4">
                    <VCheckbox class="p-0" color="primary" square
                      true-value="Tidak boleh detak anak < 12 th dan wanita hamil selama 3 hari (Do not contact with children < 12 years and pregnant woman for three days)"
                      label="Tidak boleh detak anak < 12 th dan wanita hamil selama 3 hari (Do not contact with children < 12 years and pregnant woman for three days)"
                      v-model="input.tidakBolehDetakAnak" />
                  </VControl>
                  <VControl raw subcontrol class="mr-4">
                    <VCheckbox class="p-0" color="primary" square
                      true-value="Bila sudah hipotiroid, minum hormon tiroid secara teratur seumur hidup (On hypothyroid state, take thyroid hormone regularly for a life time)"
                      label="Bila sudah hipotiroid, minum hormon tiroid secara teratur seumur hidup (On hypothyroid state, take thyroid hormone regularly for a life time)"
                      v-model="input.sudahHipotiroid" />
                  </VControl>
                  <VControl raw subcontrol class="mr-4">
                    <VCheckbox class="p-0" color="primary" square
                      true-value="Pemeriksaan berkala 3 sampai 6 bulan (Control regularly every 3 until 6 months)"
                      label="Pemeriksaan berkala 3 sampai 6 bulan (Control regularly every 3 until 6 months)"
                      v-model="input.pemeriksaanBerkala" />
                  </VControl>
                  <!-- <VControl raw subcontrol class="mr-4">
                    <VTextarea v-model="input.pemeriksaantextTataCara" true-value="a" color="primary" rows="2" />
                  </VControl> -->
                  <!-- <VTextarea v-model="input.pemeriksaantextAlternatif" true-value="a" color="primary" rows="2" /> -->
                </VField>
              </th>
              <th style="text-align: center;" class="yes-column">
                <VCheckbox v-model="input.pemeriksaantextAlternatifCheckbox" true-value="Ya" color="primary" />
              </th>
            </tr>
            <thead>
              <tr>
                <th>
                  10. Lain-lain<br>
                  <i>Other</i>
                </th>
                <th class="yes-column">
                  <VField>
                    <VTextarea v-model="input.pemeriksaantextLainLain" true-value="a" color="primary" rows="2" />
                  </VField>
                </th>
                <th style="text-align: center;">
                  <VCheckbox v-model="input.pemeriksaantextLainLainCheckbox" true-value="Ya" color="primary" />
                </th>
              </tr>
            </thead>

          </table>
          <table class="table is-bordered " style="width: 100% !important;">
            <tr>
              <th style="width: 70%;">
                Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas dan
                memberikan kesempatan untuk bertanya dan/atau berdiskusi<br>
                <i>I have explained the information correctly, clearly and provide opportunity to ask and or discuss</i>
              </th>
              <th style="width: 30%;text-align:center">
                <span>Tanda Tangan Dokter</span><br>
                <TandaTangan :elemenID="'TTDmenyatakanMenerangkanInformasi'" :width="'150'" :height="'150'"
                  class="dek" />
              </th>
            </tr>
            <tr>
              <th style="width: 70%;">
                Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri
                tanda/paraf di kolom kanannya, dan telah memahaminya<br>
                <i>I have received the information as I have given signature in the right column and have understood</i>
              </th>
              <th style="width: 30%;text-align:center">
                <span>Tanda Tangan (Pasien/Keluarga)</span><br>
                <TandaTangan :elemenID="'TTDmenyatakanMenerimaInformasi'" :width="'150'" :height="'150'" class="dek" />
              </th>
            </tr>
            <tr>
              <th colspan="2">
                *Bila Pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah Wali atau
                Keluarga terdekat<br>
                If the patient is incompetent or do not want to receive the information, then information are given to
                his or
                her parents, spouse, next of kin or the guardian
              </th>
            </tr>
          </table>

          <div class="is-12">
            <h1 style="font-weight: bold;">PERSETUJUAN TINDAKAN KEDOKTERAN KN (AGREEMENT FOR MEDICAL ACTION)</h1>
          </div>
          <div class="is-12">
            <h1 style="font-weight: bold;">Yang bertandatangan di bawah ini, Saya<br><i>The undersigned below, I</i>
            </h1>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Nama :<br><i>Name</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.namaPasien" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Umur :<br><i>Age</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.umurPasien" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Jenis Kelamin :<br><i>Gender</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.jeniskelamin" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alamat :<br><i>Address</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.alamat" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-5">
              <h1 style="font-weight: bold">dengan ini menyatakan Persetujuan untuk dilakukannya tindakan : </h1>
            </div>
            <div class="column is-7">
              <VField>
                <VControl>
                  <VInput v-model="input.persetujuan" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="is-12">
            <h1 style="font-weight: bold;">Terhadap, saya</h1>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Nama :<br><i>Name</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.namaPasienTerhadap" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Umur :<br><i>Age</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.umurPasienTerhadap" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Jenis Kelamin :<br><i>Age</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.jeniskelaminTerhadap" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="p-0 is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">Alamat :<br><i>Address</i></h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.alamatTerhadap" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>
          <p>Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada
            saya,
            termasuk risiko dan
            komplikasi yang mungkin timbul.</p>
          <p>Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan
            kedokteran
            bukanlah
            keniscayaan, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.</p>
          <p>II Have Fully Understand the need and the benefits of these action which has been explained to me,
            including the
            risks and any
            complication that might be occur. <br>
            I understand that the practice of medicine is not an exact science, hence the success of the medical action
            is not
            an absolute thing but it is
            very dependent on the permission of God Almighty.</p>
        </div>
        <div class="column columns">
          <div class="column is-4">
            <h1>Garut</h1>
            <VField addons>
              <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
              <VControl class="field-addon-body">
                <VButton static>WIB</VButton>
              </VControl>
            </VField>
          </div>

          <div class="column is-4">
            <h1>Saksi : </h1>
            <VControl>
              <VInput v-model="input.TBSaksi" class="input" type="text" />
            </VControl>
          </div>
        </div>

        <div class="columns">
          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1 style="font-weight: bold;">Yang Menyatakan*</h1>
              <TandaTangan :elemenID="'TTDmenyatakan'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <VInput v-model="input.namaPasienmenyatakan" class="input mt-2" type="text" />
              </VControl>
            </div>
          </div>

          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1 style="font-weight: bold;">Pihak Keluarga</h1>
              <TandaTangan :elemenID="'TTDpihakkeluarga'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <VInput v-model="input.pihakKeluarga" class="input mt-2" type="text" />
              </VControl>
            </div>
          </div>

          <div class="column is-4">
            <div class="column" style="text-align:center;">
              <h1 style="font-weight: bold;">Pihak Rumah Sakit</h1>
              <TandaTangan :elemenID="'TTDpihak'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.DDDPihakRumahSakit" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
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

useHead({ title: 'Persetujuan Tindakan Kedokteran KN - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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

const route = useRoute()
const pasien: any = ref({})
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const dataTTD: any = ref([])
const item: any = reactive({})
const COLLECTION: any = ref('PersetujuanTindakanKedokteranKN') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  DTttd: new Date()
})
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const listTemplate: any = ref([])
const showModalRiwayat: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      listTemplate.value = responselast //set ke inputan
      showModalRiwayat.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}
function calculateAge(birthdate) {
  const today = new Date();
  const birthDate = new Date(birthdate);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  return age;
}
const loadRiwayat = async () => {
  isLoading.value = true
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      isLoading.value = false
      H.tandaTangan().set('TTDmenyatakanMenerangkanInformasi', dataTTD.value.TTDmenyatakanMenerangkanInformasi)
      H.tandaTangan().set('TTDmenyatakanMenerimaInformasi', dataTTD.value.TTDmenyatakanMenerimaInformasi)
      H.tandaTangan().set('TTDmenyatakan', dataTTD.value.TTDmenyatakan)
      H.tandaTangan().set('TTDpihakkeluarga', dataTTD.value.TTDpihakkeluarga)
      H.tandaTangan().set('TTDpihak', dataTTD.value.TTDpihak)
    } else {
      isLoading.value = false
      let d = input.value
      d.namaPasien = props.pasien.namapasien
      d.umurPasien = calculateAge(props.pasien.tgllahir)
      d.jeniskelamin = props.pasien.jeniskelamin
      d.alamat = props.pasien.alamatlengkap
      H.alert('info', 'Data berhasil dimuat')
    }
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  object['TTDmenyatakanMenerangkanInformasi'] = H.tandaTangan().get('TTDmenyatakanMenerangkanInformasi')
  object['TTDmenyatakanMenerimaInformasi'] = H.tandaTangan().get('TTDmenyatakanMenerimaInformasi')
  object['TTDmenyatakan'] = H.tandaTangan().get('TTDmenyatakan')
  object['TTDpihakkeluarga'] = H.tandaTangan().get('TTDpihakkeluarga')
  object['TTDpihak'] = H.tandaTangan().get('TTDpihak')
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
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
    d_Pegawai.value = response
  })
}

const addRiwayat = (response: any) => {
  input.value = response
  delete input.value.namatemplate;
  delete input.value['_id'];
  H.tandaTangan().set('TTDmenyatakanMenerangkanInformasi', input.value.TTDmenyatakanMenerangkanInformasi)
  H.tandaTangan().set('TTDmenyatakanMenerimaInformasi', input.value.TTDmenyatakanMenerimaInformasi)
  H.tandaTangan().set('TTDmenyatakan', input.value.TTDmenyatakan)
  H.tandaTangan().set('TTDpihakkeluarga', input.value.TTDpihakkeluarga)
  H.tandaTangan().set('TTDpihak', input.value.TTDpihak)
  showModalRiwayat.value = false
}

function buatBaru() {
  Object.keys(input.value).forEach(key => {
    input.value[key] = null;
  });
  H.tandaTangan().set(`TTDmenyatakanMenerangkanInformasi`, null);
  H.tandaTangan().set(`TTDmenyatakanMenerimaInformasi`, null);
  H.tandaTangan().set(`TTDmenyatakan`, null);
  H.tandaTangan().set(`TTDpihakkeluarga`, null);
  H.tandaTangan().set(`TTDpihak`, null);
  let d = input.value
  d.namaPasien = props.pasien.namapasien
  d.umurPasien = calculateAge(props.pasien.tgllahir)
  d.jeniskelamin = props.pasien.jeniskelamin
  d.alamat = props.pasien.alamatlengkap
  H.alert('info', 'Silahkan dibuat baru...')
}

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
</script>
<style>
table {
  width: 100% !important;
}
</style>