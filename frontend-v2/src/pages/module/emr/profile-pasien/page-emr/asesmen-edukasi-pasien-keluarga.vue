<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> Asesmen Kebutuhan Informasi & Edukasi </h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @simpanTemplate="simpanTemplate()" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
      <div class="column is-12 mb-0">
        <div class="columns is-mobile is-centered">
          <div class="column is-8">
            <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
                membuat
                template</span></h1>
            <VField>
              <VControl>
                <VInput v-model="input.namatemplate" rows="1">
                </VInput>
              </VControl>
            </VField>
          </div>
          <div class="column is-auto" style="display: flex; gap: 5px;align-items: center;"> <!-- Flexbox with gap -->
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
              @click="pilihTemplateFix(index)">
              Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :loading="isLoading"
              @click="pilihTemplate(index)">
              Pilih Riwayat
            </VButton>
          </div>
        </div>
      </div>

      <hr style="border-top: 1px dashed lightgray;background-color:white" class="m-0">

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

      <div v-for="(item, index) in input.details" :key="index">
        <div class="column pb-0">
          <VButtons style="justify-content:end">
            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
              v-tooltip.bubble="'Tambah '">
            </VIconButton>
            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
              @click="removeItem(index)" color="danger">
            </VIconButton>
          </VButtons>
        </div>

        <div class="column is-12 pt-0">
          <div class="column is-multiline pt-0 pb-0">
            <div class="is-flex">
              <div class="column is-6">
                <h1 style="font-weight: bold;"> Hubungan dengan Pasien </h1>
                <VField horizontal>
                  <VControl fullwidth>
                    <VSelect v-model="item.hubpasien" class="">
                      <VOption v-for="(value, k) in dropdownHub" :value="value">
                        {{ value }}
                      </VOption>
                    </VSelect>
                  </VControl>
                </VField>
                <VControl v-if="item.hubpasien == 'Lainnya'">
                  <VInput type="text" class="input" placeholder="Lainnya..." v-model="item.hubpasien_lainnya" />
                </VControl>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold;"> Tanggal & Jam </h1>
                <VField>
                  <VDatePicker v-model="item.tglKedatangan" mode="datetime" trim-weeks :max-date="new Date()">
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
            <div class="column is-12 pb-0 pt-0" style="font-size:large;">
              <h1><b>Identitas Penerima Edukasi</b></h1>
            </div>
            <div class="is-flex">
              <div class="column is-6 pb-0">
                <h1 style="font-weight: bold;"> Nama </h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama Lengkap" v-model="item.namaPasien" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6 pb-0">
                <h1 style="font-weight: bold;"> Agama </h1>
                <VField horizontal>
                  <VControl fullwidth>
                    <VSelect v-model="item.agamapasien" class="">
                      <VOption v-for="(value, k) in dropdownAgama" :value="value">
                        {{ value }}
                      </VOption>
                    </VSelect>
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-6 pb-0">
                <h1 style="font-weight: bold;"> Umur </h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Umur" v-model="item.umurPasien" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6 pb-0">
                <h1 style="font-weight: bold;"> Tingkat Pendidikan </h1>
                <VField horizontal>
                  <VControl fullwidth>
                    <VSelect v-model="item.pendidikanpasien" class="">
                      <VOption v-for="(value, k) in dropdownPendidikan" :value="value">
                        {{ value }}
                      </VOption>
                    </VSelect>
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-6">
                <h1 style="font-weight: bold;"> Alamat </h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Alamat" v-model="item.Alamat" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 style="font-weight: bold;"> Alasan </h1>
                <VField vertical>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.PenurunanKesadaran"
                      true-value="Penurunan Kesadaran" label="Penurunan Kesadaran" color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <h1 style="font-weight: bold;"> &nbsp; </h1>
                <VField vertical>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.AlasanLainnya" true-value="Lainnya"
                      label="Lainnya" color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <VCard>
            <div class="column is-multiline">
              <div class="is-flex">
                <div class="column is-2">
                  <h1 style="font-weight: bold; margin-bottom: 20px;"> Bicara </h1>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.BicaraNormal" true-value="Normal"
                        label="Normal" color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.GangguanBicara"
                        true-value="Serangan awal gangguan bicara" label="Serangan awal gangguan bicara"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;"> Bahasa Sehari-hari </h1>
                  <div class="is-flex">
                    <div class="column is-3">
                      <VField vertical>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.English" true-value="English"
                            label="English" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField horizontal>
                        <VControl fullwidth>
                          <VSelect v-model="item.bahasaEnglish" class="">
                            <VOption v-for="(value, k) in dropdownBahasa" :value="value">
                              {{ value }}
                            </VOption>
                          </VSelect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField vertical>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.BahasaLainnya" true-value="Lainnya"
                            label="Lainnya" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="" v-model="item.ketBahasaLainnya" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="is-flex">
                    <div class="column is-3">
                      <VField vertical>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Indonesia" true-value="Indonesia"
                            label="Indonesia" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField horizontal>
                        <VControl fullwidth>
                          <VSelect v-model="item.bahasaIndonesia" class="">
                            <VOption v-for="(value, k) in dropdownBahasa" :value="value">
                              {{ value }}
                            </VOption>
                          </VSelect>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField vertical>
                        <VControl>
                          <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Daerah" true-value="Daerah"
                            label="Daerah" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField>
                        <VControl>
                          <VInput type="text" class="input" placeholder="" v-model="item.bahasaDaerah" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold; margin-bottom: 20px;"> Perlu Penerjemah </h1>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.PenerjemahYa" true-value="Ya" label="Ya"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.PenerjemahTidak" true-value="Tidak"
                        label="Tidak" color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;"> Bahasa Isyarat </h1>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.IsyaratYa" true-value="Ya" label="Ya"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.IsyaratTidak" true-value="Tidak"
                        label="Tidak" color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="is-flex">
                <div class="column is-12">
                  <h1 style="font-weight: bold;"> Hambatan Belajar </h1>
                </div>
              </div>
              <div class="is-flex">
                <div class="column is-3">
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.tidakHambatan"
                        true-value="Tidak ditemukan hambatan" label="Tidak ditemukan hambatan" color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.kesulitanMembaca"
                        true-value="Kesulitan membaca" label="Kesulitan membaca" color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Pendengaran" true-value="Pendengaran"
                        label="Pendengaran" color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Kognitif" true-value="Kognitif"
                        label="Kognitif" color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.hilangMemori" true-value="Hilang memori"
                        label="Hilang memori" color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.KesulitanBicara"
                        true-value="Kesulitan Bicara" label="Kesulitan Bicara" color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Bahasa" true-value="Bahasa" label="Bahasa"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Cemas" true-value="Cemas" label="Cemas"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.MasalahPenglihatan"
                        true-value="Masalah Penglihatan" label="Masalah Penglihatan" color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.MotivasiBuruk" true-value="Motivasi Buruk"
                        label="Motivasi Buruk" color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Emosi" true-value="Emosi" label="Emosi"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.HambatanFisik" true-value="Hambatan Fisik"
                        label="Hambatan Fisik" color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.caregiver"
                        true-value="Tidak ada partisipasi dari care giver" label="Tidak ada partisipasi dari care giver"
                        color="primary" />
                    </VControl>
                  </VField>
                  <VField vertical>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Secarafisiologi"
                        true-value="Secara fisiologi tidak mampu belajar" label="Secara fisiologi tidak mampu belajar"
                        color="primary" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="is-flex">
                <div class="column is-6">
                  <h1 style="font-weight: bold;"> Cara Belajar </h1>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;"> Kesediaan Pasien / Keluarga menerima informasi & edukasi </h1>
                </div>
              </div>
              <div class="is-flex">
                <div class="column is-3">
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Menulis" true-value="Menulis"
                      label="Menulis" color="primary" />
                  </VControl>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Mendengar" true-value="Mendengar"
                      label="Mendengar" color="primary" />
                  </VControl>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Audiovisual" true-value="Audio-visual"
                      label="Audio-visual" color="primary" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Demonstrasi" true-value="Demonstrasi"
                      label="Demonstrasi" color="primary" />
                  </VControl>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Diskusi" true-value="Diskusi"
                      label="Diskusi" color="primary" />
                  </VControl>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Membaca" true-value="Membaca"
                      label="Membaca" color="primary" />
                  </VControl>
                </div>
                <div class="column is-3">
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.YaBersedia" true-value="Ya" label="Ya"
                      color="primary" />
                  </VControl>
                  <VControl>
                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.TidakBersedia" true-value="Tidak"
                      label="Tidak" color="primary" />
                  </VControl>
                </div>
              </div>
            </div>
          </VCard>
        </div>

        <div class="column is-12">
          <VCard>
            <div class="column is-multiline">

              <!-- Medis -->
              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;"> Medis </h1>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-5">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.penjelasanPenyakit"
                          true-value="Penjelasan penyakit, penyebab, tanda dan gejala, prognosa"
                          label="Penjelasan penyakit, penyebab, tanda dan gejala, prognosa" color="primary" />
                      </VControl>
                    </VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.alatkedokteran"
                        true-value="Penggunaan alat kedokteran" label="Penggunaan alat kedokteran" color="primary" />
                    </VControl>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.penjelasankomplikasi"
                        true-value="Penjelasan komplikasi yang mungkin terjadi"
                        label="Penjelasan komplikasi yang mungkin terjadi" color="primary" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.hasilPemeriksaan"
                        true-value="Hasil pemeriksaan" label="Hasil pemeriksaan" color="primary" />
                    </VControl>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.PerkiraanRawat"
                        true-value="Perkiraan hari rawat" label="Perkiraan hari rawat" color="primary" />
                    </VControl>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.tindakanMedis" true-value="Tindakan Medis"
                        label="Tindakan Medis" color="primary" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.pilihanLainnya" true-value="Lainnya"
                        label="Lainnya" color="primary" />
                    </VControl>
                  </div>
                </div>
              </div>


              <!-- Keperawatan -->

              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;"> Keperawatan </h1>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-5">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.infoTentang"
                          true-value="Informasi tentang" label="Informasi tentang" color="primary" />
                      </VControl>
                    </VField>
                    <h1 style="margin-left: 50px; margin-top: -10px;"> - Hak, kewajiban, dan tanggung jawab </h1>
                    <h1 style="margin-left: 50px; "> - Tata tertib </h1>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.alatmedis"
                        true-value="Penggunaan alat-alat medis secara efektif dan aman"
                        label="Penggunaan alat-alat medis secara efektif dan aman" color="primary" />
                    </VControl>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.apd" true-value="Penggunaan APD"
                        label="Penggunaan APD" color="primary" />
                    </VControl>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.penangananlanjut"
                        true-value="Penanganan & cara perawatan lanjutan di rumah"
                        label="Penanganan & cara perawatan lanjutan di rumah" color="primary" />
                    </VControl>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.homecare"
                        true-value="Keamanan lingkungan perawatan di rumah"
                        label="Keamanan lingkungan perawatan di rumah" color="primary" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.prosedurPerawatan"
                          true-value="Prosedur perawatan" label="Prosedur perawatan" color="primary" />
                      </VControl>
                    </VField>
                    <h1 style="margin-left: 50px; margin-top: -10px;"> - Perawatan Luka </h1>
                    <h1 style="margin-left: 50px; "> - Perawatan Trakeostomi </h1>
                    <h1 style="margin-left: 50px; "> - Penyuntikan Insulin </h1>
                    <h1 style="margin-left: 50px; "> - Pemberian Obat Inhalasi </h1>
                    <h1 style="margin-left: 50px; "> - Pemberian Makanan Lewat NGT </h1>
                    <h1 style="margin-left: 50px; "> - Lain-lain </h1>
                  </div>
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.persiapanalat"
                          true-value="Alat-alat yang perlu disiapkan di rumah"
                          label="Alat-alat yang perlu disiapkan di rumah" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.cucitangan"
                          true-value="Cuci tangan yang benar" label="Cuci tangan yang benar" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.waktukontrol"
                          true-value="Waktu kontrol dan penanganan obat-obat di rumah"
                          label="Waktu kontrol dan penanganan obat-obat di rumah" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.keperawatanLain" true-value="Lain-lain"
                          label="Lain-lain" color="primary" />
                      </VControl>
                      <VControl v-if="item.keperawatanLain == 'Lain-lain'">
                        <VInput type="text" class="input" v-model="item.keperawatanLain_text" placeholder="..." />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <!-- Manajemen Nyeri -->

              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;"> Manajemen Nyeri </h1>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-12">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.Farmatologi" true-value="Farmatologi"
                          label="Farmatologi" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.NonFarmatologi"
                          true-value="Non Farmatologi" label="Non Farmatologi" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.NyeriLain" true-value="Lain-lain"
                          label="Lain-lain" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;"> Pengobatan </h1>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.namadankegunaanobat"
                          true-value="Nama obat dan kegunaan obat yang diberikan"
                          label="Nama obat dan kegunaan obat yang diberikan" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.aturanpemakaian"
                          true-value="Aturan pemakaian dan obat yang diberikan"
                          label="Aturan pemakaian dan obat yang diberikan" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.jumlahobat"
                          true-value="Jumlah obat yang diberikan" label="Jumlah obat yang diberikan" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.penyimpananobat"
                          true-value="Cara penyimpanan obat yang diberikan" label="Cara penyimpanan obat yang diberikan"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.efeksamping"
                          true-value="Efek samping obat yang diberikan" label="Efek samping obat yang diberikan"
                          color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.manfaatobat"
                          true-value="Manfaat obat-obatan yang diberikan" label="Manfaat obat-obatan yang diberikan"
                          color="primary" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.interaksiobat"
                          true-value="Interaksi obat dan makanan" label="Interaksi obat dan makanan" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.lainlainobat" true-value="Lain-lain"
                          label="Lain-lain" color="primary" />
                      </VControl>
                      <VControl v-if="item.lainlainobat == 'Lain-lain'">
                        <VInput type="text" class="input" placeholder="Lain-lain" v-model="item.TBLainLainObat" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;"> Rohaniawan </h1>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.bimbinganrohani"
                          true-value="Bimbingan Rohani" label="Bimbingan Rohani" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.konselingrohani"
                          true-value="Konseling Rohani" label="Konseling Rohani" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.rohanilainnya" true-value="Lain-lain"
                          label="Lain-lain" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>


              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;"> Nutrisions </h1>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.dietnutrisi"
                          true-value="Diet dan Nutrisi" label="Diet dan Nutrisi" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.nutrisilain" true-value="Lain-lain"
                          label="Lain-lain" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;"> Rehabilitasi Medis </h1>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.teknikrehab"
                          true-value="Teknik-teknik rehabilitasi" label="Teknik-teknik rehabilitasi" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.rehablain" true-value="Lain-lain"
                          label="Lain-lain" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <div class="is-flex">
                  <div class="column is-12">
                    <h1 style="font-weight: bold;"> Informasi Lainnya </h1>
                  </div>
                </div>
                <div class="is-flex">
                  <div class="column is-3">
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.penundaan"
                          true-value="Penundaan Pelayanan" label="Penundaan Pelayanan" color="primary" />
                      </VControl>
                    </VField>
                    <VField vertical>
                      <VControl>
                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="item.infolain" true-value="Lain-lain"
                          label="Lain-lain" color="primary" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="is-flex">
                <div class="column is-12">
                  <h1 style="font-weight: bold; margin-bottom: 20px;"> Metode Edukasi </h1>
                  <Multiselect v-model="item.metodeEdukasi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_metodeEdukasi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                  <VControl v-if="item.metodeEdukasi == 'Lainnya'">
                    <VInput type="text" v-model="item.metodeedukasilain" class="input"
                      placeholder="Keterangan Lainnya" />
                  </VControl>
                </div>
              </div>
              <div class="is-flex">
                <div class="column is-3">
                  <h1 style="font-weight: bold; margin-bottom: 10px;"> Waktu Edukasi </h1>
                  <!-- <VField>
                    <VDatePicker v-model="item.waktuEdukasi" mode="dateTime" style="width: 100%" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField> -->
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="..." v-model="item.TBWaktuEdukasi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>Menit</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>

        <div class="columns">
          <div class="column is-4 mt-3" style="text-align:center">
            <VCard class="border-card pink">
              <div class="column is-12">
                <VField>
                  <h1 style="font-weight: bold;">Pemberi Edukasi</h1>
                </VField>
                <TandaTangan :elemenID="`TTD_Pemberi_Edukasi-${index}`" :width="'150'" :height="'150'" class="dek" />
              </div>
              <div class="column">
                <VControl class="prime-auto">
                  <AutoComplete v-model="item.DDPember_Edukasi" :suggestions="d_Petugas"
                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                </VControl>
              </div>
            </VCard>
          </div>
          <div class="column is-4"></div>
          <div class="column is-4 mt-3" style="text-align:center">
            <VCard class="border-card pink">
              <div class="column is-12">
                <VField>
                  <h1 style="font-weight: bold;">Garut , tanggal dan jam</h1>
                </VField>
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
                <TandaTangan :elemenID="`TTDDokter-${index}`" :width="'150'" :height="'150'" class="dek" />
              </div>
              <div class="column pt-0">
                <VField>
                  <h1 style="font-weight: bold;">Pasien / Keluarga</h1>
                </VField>
                <VField class="is-autocomplete-select">
                  <VControl>
                    <VInput type="text" class="input" placeholder="Pasien / Keluarga" v-model="item.namaPasien" />
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>
        </div>
        <hr>
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
                  <td class="tg-0lax text-center" width="5%">#</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                  <td class="tg-0lax text-center" width="20%">No EMR</td>
                  <td class="tg-0lax text-center" width="20%">Dokter</td>
                  <td class="tg-0lax text-center" width="20%">Penyakit</td>
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
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.riwayatpenyakit }}</span><br>
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
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="8"
        :loading="isLoading" paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
        <template #header>
          <div class="columns is-multiline">
            <div class="column is-8">
              <VField>
                <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                </VControl>
              </VField>
            </div>
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
            </VButtons>
          </template>
        </Column>
        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
        <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
          <template #body="slotProps">
            {{ slotProps.data.registrasi.namaruangan }}
          </template>
        </Column>
        <Column field="created_at" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
  </VModal>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/asesmen-edukasi-pasien-keluarga'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import moment from 'moment'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
let prosesEdukasi = ref(EMR.prosesEdukasi())
let BidangDisiplin = ref(EMR.BidangDisiplin())
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const confirm = useConfirm();
const route = useRoute()
const pasien: any = ref({})
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_Petugas: any = ref([])
const dataTTD: any = ref([]);
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const isAlltemplate: any = ref(false);
const filterMenu: any = ref('')
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const d_metodeEdukasi: any = ref([
  { value: 'Diskusi', label: 'Diskusi (Discussion)' },
  { value: 'Demonstrasi', label: 'Demonstrasi (demo)' },
  { value: 'Ceramah', label: 'Ceramah (lecture)' },
  { value: 'Praktek langsung', label: 'Praktek langsung (direct pratice)' },
  { value: 'Audio visual', label: 'Audio visual' },
  { value: 'Lembar balik', label: 'Lembar balik' },
  { value: 'Booklet', label: 'Booklet' },
  { value: 'Leaflet', label: 'Leaflet' },
  { value: 'Lainnya', label: 'Lainnya' }
])
const COLLECTION: any = ref('InformasiEdukasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
  details: [{
    no: 1,
    tglKedatangan: new Date(),
    tanggal: new Date(),
    hubpasien: 'Pasien Sendiri'
  }],
  TTDDokter: {},
  TTD_Pemberi_Edukasi: {},
})

let dropdownHub: any = ref([
  "Pasien Sendiri",
  "Saudara Kandung",
  "Anak",
  "Orang tua",
  "Suami/Istri",
  "Teman",
  "Lainnya",
])

let dropdownAgama: any = ref([
  "BUDHA",
  "HINDU",
  "ISLAM",
  "KONGHUCU",
  "KRISTEN",
  "KATHOLIK",
  "LAIN-LAIN",
])

let dropdownPendidikan: any = ref([
  "Belum Tamat SD",
  "DIII",
  "DI/DII",
  "S1",
  "S2",
  "S3",
  "SD",
  "SMA",
  "SMP",
  "Tidak Sekolah",
  "TK",
  "Lainnya",
])

let dropdownBahasa: any = ref([
  "Aktif",
  "Pasif",
])

const kesulitanKomunikasi: any = ref([
  { label: 'Tidak Ada', value: 'Tidak Ada' },
  { label: 'Ada, Jelaskan', value: 'Ada' }
])
const bahasa: any = ref([
  { label: 'Indonesia', value: 'Indonesia' },
  { label: 'Inggris', value: 'Inggris' },
  { label: 'Mandarin', value: 'Mandarin' },
  { label: 'Bahasa Lainnya', value: 'Bahasa Lainnya' },
])
const penterjemah: any = ref([
  { label: 'Tidak Perlu', value: 'Tidak Perlu' },
  { label: 'Perlu,', value: 'Perlu' },
  { label: 'Lainnya, Jelaskan', value: 'Lainnya' }
])
const kesediaanPasien: any = ref([
  { label: 'Ya', value: 'Ya' },
  { label: 'Tidak,', value: 'Tidak' },
])
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}
const loadRiwayat = async () => {
  const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`);
  if (response.length) {
    input.value = response[0];
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk;
    }
    dataTTD.value = response[0];
    await nextTick();
    input.value.details.forEach((item: any, index: number) => {
      const elemenID = `TTD_Pemberi_Edukasi-${index}`;
      const elemenID2 = `TTDDokter-${index}`;
      const signatureElement = document.getElementById(elemenID);
      const signatureElement2 = document.getElementById(elemenID2);
      if (signatureElement) {
        const ttdValue = dataTTD.value[`TTD_Pemberi_Edukasi-${index}`];
        if (ttdValue) {
          H.tandaTangan().set(elemenID, ttdValue);
        }
      }
      if (signatureElement2) {
        const ttdValue = dataTTD.value[`TTDDokter-${index}`];
        if (ttdValue) {
          H.tandaTangan().set(elemenID2, ttdValue);
        }
      }
      else {
        console.log('Signature element not found:', elemenID);
        console.log('Signature element not found:', elemenID2);
      }
    });
    H.alert('info', 'Data berhasil dimuat')
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

      H.alert('info', 'Asesmen Edukasi sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini.');

      if (
        responseTglRuangan[0].registrasi.namaruangan.trim().toLowerCase() === props.registrasi.namaruangan.trim().toLowerCase() &&
        calculateDays < 90
      ) {
        console.log("masuk sini")
        confirm.require({
          message: `Asesmen Edukasi sudah pernah diinput ${calculateDays} hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya?`,
          group: 'templating',
          header: 'Asesmen Edukasi',
          icon: 'pi pi-exclamation-circle',
          accept: () => {
            if (responseHistori.length) {
              input.value = responseHistori[0];
              input.value.namatemplate = '';
              dataTTD.value = responseHistori[0];
              nextTick(() => {
                input.value.details.forEach((item: any, index: number) => {
                  const elemenID = `TTD_Pemberi_Edukasi-${index}`;
                  const elemenID2 = `TTDDokter-${index}`;
                  const signatureElement = document.getElementById(elemenID);
                  const signatureElement2 = document.getElementById(elemenID2);
                  if (signatureElement) {
                    const ttdValue = dataTTD.value[`TTD_Pemberi_Edukasi-${index}`];
                    if (ttdValue) {
                      H.tandaTangan().set(elemenID, ttdValue);
                    }
                  } else {
                    console.log('Signature element not found:', elemenID);
                  }
                  if (signatureElement2) {
                    const ttdValue = dataTTD.value[`TTDDokter-${index}`];
                    if (ttdValue) {
                      H.tandaTangan().set(elemenID2, ttdValue);
                    }
                  }
                  else {
                    console.log('Signature element not found:', elemenID2);
                  }
                })
              });
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

    // console.log("Ruangan pasien sekarang : " + props.registrasi.namaruangan);
    H.alert('info', 'Ruangan Pasien saat ini: ' + props.registrasi.namaruangan);
  }
};

const simpan = async () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)

  input.value.details.forEach((item: any, index: number) => {
    object[`TTDDokter-${index}`] = H.tandaTangan().get(`TTDDokter-${index}`);
    object[`TTD_Pemberi_Edukasi-${index}`] = H.tandaTangan().get(`TTD_Pemberi_Edukasi-${index}`);
  });
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
  await useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.details.forEach(item => {
    item.namaPasien = props.pasien.namapasien
    item.jenisKelaminPasien = props.pasien.jeniskelamin
    item.norm = props.pasien.nocm
    item.agamapasien = props.pasien.agama
    item.umurPasien = props.pasien.umur
    item.pendidikanpasien = props.pasien.pendidikan
    item.tanggalLahirPasien = props.pasien.tgllahir
    item.Alamat = props.pasien.alamatlengkap
    item.dokterRawat = props.registrasi.dokter
    item.tglPembuatan = new Date()
  });
}

//fungsi untuk update tanda tangan jika klik tanda plus
// const updateTandaTanganKeys = () => {
//   input.value.TTDDokter = {};
//   input.value.TTD_Pemberi_Edukasi = {};

//   input.value.details.forEach((item: any) => {
//     input.value.TTDDokter[`TTDDokter-${item.no}`] = input.value.TTDDokter[`TTDDokter-${item.no}`] || null;
//     input.value.TTD_Pemberi_Edukasi[`TTD_Pemberi_Edukasi-${item.no}`] = input.value.TTD_Pemberi_Edukasi[`TTD_Pemberi_Edukasi-${item.no}`] || null;
//   });
// };



const shiftTandaTanganKeysDown = () => {
  for (let i = input.value.details.length - 1; i >= 0; i--) {
    const oldDokterKey = `TTDDokter-${i}`;
    const newDokterKey = `TTDDokter-${i + 1}`;

    if (input.value.TTDDokter[oldDokterKey] !== undefined) {
      input.value.TTDDokter[newDokterKey] = input.value.TTDDokter[oldDokterKey];
      delete input.value.TTDDokter[oldDokterKey];
    }

    const oldPemberiKey = `TTD_Pemberi_Edukasi-${i}`;
    const newPemberiKey = `TTD_Pemberi_Edukasi-${i + 1}`;

    if (input.value.TTD_Pemberi_Edukasi[oldPemberiKey] !== undefined) {
      input.value.TTD_Pemberi_Edukasi[newPemberiKey] = input.value.TTD_Pemberi_Edukasi[oldPemberiKey];
      delete input.value.TTD_Pemberi_Edukasi[oldPemberiKey];
    }
  }
};


const addNewItem = async () => {
  let newItem: any = {
    no: input.value.details[input.value.details.length - 1]?.no + 1 || 1,
    namaPasien: props.pasien.namapasien,
    agamapasien: props.pasien.agama,
    umurPasien: props.pasien.umur,
    pendidikanpasien: props.pasien.pendidikan,
    Alamat: props.pasien.alamatlengkap,
    dokterRawat: props.registrasi.dokter,
    tglKedatangan: new Date(),
    tanggal: new Date(),
  };

  input.value.details.push(newItem);
  shiftTandaTanganKeysDown();
  await nextTick();
};


const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
  H.alert('error', 'Berhasil dihapus!')
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
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

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}`).then((responselast: any) => {
      isLoading.value = false
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

const addTemplate = (response) => {
  console.log(response);
  input.value = response;
  input.value.namatemplate = null;
  isAlltemplate.value = false;
  showModalTemplateFix.value = false;
  showModalTemplate.value = false;
  H.alert('success', 'Berhasil ditambahkan');
};

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(`/emr/hapus-template`, json).then((response: any) => {
    if (response.status !== 500) {
      isLoading.value = false
      isAlltemplate.value = false;
      H.alert('sucess', response.message);
      showModalTemplate.value = false;
      showModalTemplateFix.value = false;
    } else {
      H.alert('danger', response.message);
    }
  }).catch((e: any) => {
    isLoading.value = false
    H.alert('danger', e);
  })
}
// loadRiwayat()

onMounted(() => {
  setView()
  setAutoFill()
  fetchPasien()
})

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})

onBeforeMount(async () => {
  try {
    await loadRiwayat()
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
</script>
