<template>
  <ConfirmDialog group="confirm">
    <template #message="slotProps">
        <div style="width:500px;height:300px;">
            <table style="width:100%;height:100%;border-collapse: collapse">
            <tr>
                <td style="text-align:center;vertical-align:middle">
                <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
                </td>
            </tr>
            <tr>
                <td style="padding:7px;padding-bottom:0px;text-align:center">
                <p style="font-size:large;">
                    Apakah anda yakin dengan data berikut? <br><br>
                    - Status Naik Kelas: <b>{{item.isnaikkelas ? '✓' : 'X'}}</b> <br>
                    - Status Titip Kelas: <b>{{item.iskelastitip ? '✓' : 'X'}}</b> <br>
                    - Status Rawat Gabung: <b>{{item.israwatgabung ? '✓' : 'X'}}</b>
                </p>
                </td>
            </tr>
            </table>
        </div>
    </template>
  </ConfirmDialog>
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ INFO ? 'Riwayat Mutasi' : 'Pindah Atau Pulang' }}</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="back()">
                Kembali
              </VButton>
              <!-- <VButton type="submit" color="primary" raised icon="feather:save" :loading="isLoading"
                                @click="simpan()"> Save
                            </VButton> -->
            </div>
          </div>
        </div>
      </div>

      <div class="form-body p-2">
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12" v-if="!isLoadingPasien">
              <div class="block-header">
                <div class="left">
                  <div class="current-user">
                    <!-- <VAvatar size="medium" :color="'warning'" :initials="'ER'" /> -->
                    <VAvatar size="medium" :picture="pasien.jeniskelamin == 'PEREMPUAN'
                      ? '/images/avatars/svg/vuero-4.svg'
                      : '/images/avatars/svg/vuero-1.svg'
                      " squared />
                    <h3>{{ pasien.namapasien }}</h3>
                    <!-- <p class="block-text">
                                    {{ 'No RM : ' + pasien.nocm + (pasien.jeniskelamin ==
                                            'PEREMPUAN' ? ' (P)'
                                            :
                                            ' (L)')
                                    }}</p> -->
                  </div>
                </div>
                <div class="center">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No RM</h4>
                      <p class="block-text">{{ pasien.nocm }}</p>
                      <h4 class="block-heading">Tgl Lahir</h4>
                      <p class="block-text">{{ pasien.tgllahir }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">NIK</h4>
                      <p class="block-text">{{ pasien.noidentitas }}</p>
                      <h4 class="block-heading">Kelamin</h4>
                      <p class="block-text">{{ pasien.jeniskelamin }}</p>
                    </div>
                  </div>
                </div>
                <div class="right">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No HP</h4>
                      <p class="block-text">{{ pasien.nohp }}</p>
                      <h4 class="block-heading">Alamat</h4>
                      <p class="block-text">{{ pasien.alamatlengkap }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">Umur</h4>
                      <VTag color="orange" :label="pasien.umur" />
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="column is-2" v-if="dataSourceRiwayat.loading">
              <div class="list-view list-view-v1">
                <div class="list-view-inner">
                  <div v-for="key in 10" :key="key" class="list-view-item mb-10">
                    <VAvatarStack class="is-pushed-mobile">
                      <VPlaceload class="mx-2 h-hidden-tablet-p mt-3" />
                      <VPlaceloadAvatar size="small" class="mx-1" />
                    </VAvatarStack>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-10" v-if="dataSourceRiwayat.loading">
              <div class="list-view list-view-v1">
                <div class="list-view-inner">
                  <!--Item-->
                  <div v-for="key in 10" :key="key" class="list-view-item">
                    <VPlaceloadWrap>
                      <VPlaceloadAvatar size="medium" />
                      <VPlaceloadText last-line-width="60%" class="mx-2" />
                      <VPlaceload class="mx-2" disabled />
                      <VPlaceload class="mx-2 h-hidden-tablet-p" />
                      <VPlaceload class="mx-2 h-hidden-tablet-p" />
                      <VPlaceload class="mx-2" />
                    </VPlaceloadWrap>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="form-body p-2"></div>

  <VCard style="margin-top: 1rem">
    <div class="timeline-wrapper" v-if="dataSourceRiwayat.length > 0">
      <div class="timeline-header">

      </div>
      <span v-if="item.tglpulang == null" style="font-weight: bold; font-size: 13px; font-family: var(--font-alt);">
        Pasien di {{ item.namaruangan }}
        <VTag color="warning" label="Sedang Di Rawat" rounded /> sejak Tanggal {{ H.formatDateIndo(item.tglmasuk) }}
      </span>
      <VButton type="button" color="info" raised rounded icon="feather:plus-circle"
        class="is-pulled-right mr-3 mt-0 mb-0" @click="showModal(item)" v-if="!INFO">
        Pulang atau Pindah
      </VButton>
      <br />
      <div class="timeline-wrapper-inner">
        <div class="timeline-container">
          <div class="timeline-item is-unread" v-for="(items, index) in dataSourceRiwayat" :key="items.norec">
            <div class="date">
              <span>{{ H.formatDateIndo(items.tglmasuk) }}</span>
            </div>
            <div :class="'dot is-' + listColor[index + 1]"></div>

            <div class="content-wrap is-grey">
              <div class="content-box">
                <div class="status"></div>
                <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                  <i class="lnir lnir-diagnosis" aria-hidden="true"></i>
                </VIconBox>
                <div class="box-text" style="width:70%">
                  <div class="meta-text">
                    <p>
                      <span>{{ items.namaruangan + ' -- ' + items.noregistrasi }}</span>
                      <VTag style="margin-top: -3rem;" color="purple" rounded
                        v-if="items.tglpulang == null && items.tglkeluar == null">Pasien Sedang
                        di Rawat</VTag>
                      <VTag style="margin-top: -3rem;" color="danger" rounded
                        v-if="items.tglmeninggal != null && items.tglpulang != null">Pasien Sudah Meninggal</VTag>
                      <VTag style="margin-top: -3rem;" color="info" rounded
                        v-if="items.tglpulang != null && items.tglmeninggal == null">Pasien Sudah Pulang</VTag>

                    </p>
                    <table class="tb-order mt-1">
                      <tr v-if="items.namakamar != null">
                        <td>Kamar</td>
                        <td>:</td>
                        <td>{{
                          items.namakamar
                        }} </td>
                      </tr>
                      <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ items.namakelas }} </td>
                      </tr>
                      <tr v-if="items.namakamar != null">
                        <td>No. Bed</td>
                        <td>:</td>
                        <td>{{ items.nobed }} </td>
                      </tr>

                      <tr>
                        <td>Dokter</td>
                        <td>:</td>
                        <td>{{ items.namadokter }}</td>
                      </tr>
                      <tr v-if="items.namakamar != null && items.tglpulang == null">
                        <td>Lama Rawat</td>
                        <td>:</td>
                        <td> {{ items.selisihwaktu }}</td>
                      </tr>
                      <tr>
                        <td>Tanggal Keluar</td>
                        <td>:</td>
                        <td> {{ items.tglkeluar }}</td>
                      </tr>
                      <tr v-if="items.tglpulang">
                        <td>Tanggal Pulang</td>
                        <td>:</td>
                        <td> {{ items.tglpulang }}</td>
                      </tr>
                    </table>

                  </div>

                </div>

                <div class="box-end" style="width:40%" v-if="!INFO">
                  <div class="columns is-multiline">
                    <div class="column is-12 mt-3">

                    </div>
                    <div class="column is-6 mt-1">
                      <VButton color="danger" icon="feather:x-circle" raised rounded @click="saveBatalPindah"
                        :loading="isLoading" v-if="items.objectruanganasalfk != null && items.tglkeluar == null"> Batal
                        Pindah </VButton>

                      <!-- <VIconButton icon="feather:user" color="warning" raised circle
                            class="mr-2" v-tooltip.bubble="'Detail Dokter'">
                          </VIconButton>
                          <VIconButton icon="feather:trash" color="danger" raised circle
                            class="mr-2" v-tooltip.bubble="'Hapus Layanan'">
                          </VIconButton>
                          <VIconButton icon="feather:edit" color="primary" raised circle
                            v-tooltip.bubble="'Hasil Lab PA'">
                          </VIconButton> -->
                    </div>

                  </div>

                </div>

              </div>
            </div>
          </div>
        </div>


      </div>
    </div>
  </VCard>
  <VCard radius="rounded" class="mt-2" v-if="dataSourceRiwayat.length === 0">
    <VPlaceholderPage title="Data Tidak Ditemukan."
      subtitle="Silakan Cek Kembali Apakah Pasien merupakan Pasien Rawat Inap." larger>
      <template #image>
        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
      </template>
    </VPlaceholderPage>
  </VCard>

  <VModal :open="modalInput" title="Formulir Pulang atau Pindah" :noclose="false" size="big" actions="right"
    @close="modalInput = false">
    <template #content>
    <!-- <pre>{{ item }}</pre> -->
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-5">
                  <VField>
                    <VLabel>Status Keluar</VLabel>
                    <VControl>
                      <VRadio v-for="items in d_SK" :key="items.id" v-model="item.statuskeluar" :value="items"
                        :label="items.statuskeluar" name="{{items.id}}" color="primary" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showPulang && item.kelompokpasien == 'BPJS'">
                  <VField label="No. SEP">
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="item.nosep" placeholder="Nomor SEP" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showMeninggal && item.kelompokpasien == 'BPJS'">
                  <VField label="No. SEP">
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="item.nosep" placeholder="Nomor SEP" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showPindah">
                  <VField label="Tanggal Rencana Pindah" class="is-rounded-select">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglpindah" selectionMode="single" :manualInput="true"
                          class="w-100 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showPulang">
                  <VField label="Tanggal Keluar" class="is-rounded-select">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglpulang" selectionMode="single" :manualInput="true"
                          class="w-100 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showPulang">


                  <VField label="Kondisi Pasien " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.kondisipasien" :options="d_KP" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showMeninggal">
                  <VField label="Tanggal Meninggal" class="is-rounded-select">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglmeninggal" selectionMode="single" :manualInput="true"
                          class="w-100 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showMeninggal">
                  <VField label="Kondisi Pasien " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.kondisipasien" :options="d_KP" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 is-flex" v-if="showPindah && item.DEPARTEMEN_FK != 9"
                  style="align-items: center;">
                  <VControl>
                    <VSwitchBlock v-model="item.israwatgabung" label="Rawat Gabung" color="danger" class="p-0" />
                  </VControl>
                  <VControl>
                    <VSwitchBlock v-model="item.iskelastitip" label="Titip Kelas" color="danger" class="p-0 pl-3" />
                  </VControl>
                  <VControl>
                    <VSwitchBlock v-model="item.isnaikkelas" label="Naik Kelas" color="danger" class="p-0 pl-3" />
                  </VControl>
                </div>
                <div class="column is-4" v-if="showPindah">
                  <VField label="Ruangan Rencana Pindah " class="is-rounded-select is-autocomplete-select"
                    v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.namaruangan" :options="d_Ruangan"
                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                        @select="changeRuang(item.namaruangan)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showPindah">
                  <VField label="Kelas Kamar " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.namakelasrawat" :options="d_Kelas"
                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                        @select="changeKelas(item.namakelasrawat)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showPindah && item.namakelasrawat">
                  <VField label="Kelas Ditanggung/Rawat " class="is-rounded-select is-autocomplete-select"
                    v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.namakelas" :options="d_KelasAll" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showPindah && item.DEPARTEMEN_FK != 9">
                  <VField label="Kamar" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.kamar" :options="d_Kamar" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" @select="changeKamar(item.kamar)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showPindah && item.DEPARTEMEN_FK != 9">
                  <VField label="Nomor Tempat Tidur" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.bed" :options="d_TempatTidur" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4" v-if="showPulang">
                  <VField label="Status Pulang" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.statuspulang" :options="d_SP" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showMeninggal">
                  <VField label="Status Pulang" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.statuspulang" :options="d_SP" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4" v-if="showPulang">
                  <VField label="Nama Pembawa Pulang">
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="item.namalengkapambilpasien" placeholder="Nama Pembawa Pulang"
                        class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showPulang">
                  <VField label="Hubungan Keluarga" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.hubungankeluarga" :options="d_HK"
                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showPulang && item.kelompokpasien == 'BPJS'">
                  <VField label="No. KKL Manual">
                    <VControl icon="feather:book">
                      <VInput type="text" v-model="item.nolpmanual" placeholder="Nomor KKL Manual" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showMeninggal && item.kelompokpasien == 'BPJS'">
                  <VField label="No. KKL Manual">
                    <VControl icon="feather:book">
                      <VInput type="text" v-model="item.nolpmanual" placeholder="Nomor KKL Manual" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showMeninggal && item.kelompokpasien == 'BPJS'">
                  <VField label="No. Surat Meninggal">
                    <VControl icon="feather:book">
                      <VInput type="text" v-model="item.nosuratmeninggal" placeholder="Nomor Surat Meninggal"
                        class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3" v-if="showMeninggal">
                  <VField label="Penyebab Kematian" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.penyebabkematian" :options="d_PK"
                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showMeninggal">
                  <VField label="Nama Pembawa Pulang">
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="item.namalengkapambilpasien" placeholder="Nama Pembawa Pulang"
                        class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showMeninggal">
                  <VField label="Hubungan Keluarga" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.hubungankeluarga" :options="d_HK"
                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showRujuk">
                  <VField label="Tanggal Keluar" class="is-rounded-select">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglpulang" selectionMode="single" :manualInput="true"
                          class="w-100 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="showRujuk">
                  <VField label="Status Pulang" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.statuspulang" :options="d_SP" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="showRujuk">
                  <VField label="Tempat Rujukan">
                    <VControl icon="feather:home">
                      <VInput type="text" v-model="item.rujuk" placeholder="Tempat Rujukan" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="showRujuk">
                  <VField label="Alamat Rujukan">
                    <VControl icon="feather:home">
                      <VInput type="text" v-model="item.alamatrujuk" placeholder="Tempat Alamat" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showRujuk">
                  <VField label="Kondisi Pasien " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.kondisipasien" :options="d_KP" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4" v-if="showRujuk">
                  <VField label="Nama Pembawa Pulang">
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="item.namalengkapambilpasien" placeholder="Nama Pembawa Pulang"
                        class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4" v-if="showRujuk">
                  <VField label="Hubungan Keluarga" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.hubungankeluarga" :options="d_HK"
                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                    </VControl>
                  </VField>
                </div>
                <div class="column" :class="showPindah && item.DEPARTEMEN_FK == 9 ? 'is-9' : ' is-12'">
                  <VField label="Keterangan">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.catatan" rows="5" placeholder="Keterangan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="simpanStatus()" :loading="isLoading" color="primary" raised
        v-if="showPulang">
        Simpan Pulang
      </VButton>
      <VButton icon="feather:save" @click="simpanPindah()" :loading="isLoading" color="primary" raised
        v-if="showPindah">
        Simpan Pindah
      </VButton>
      <VButton icon="feather:save" @click="simpanMeninggal()" :loading="isLoading" color="primary" raised
        v-if="showMeninggal">Simpan Meninggal
      </VButton>
      <VButton icon="feather:save" @click="simpanRujuk()" :loading="isLoading" color="primary" raised v-if="showRujuk">
        Simpan
      </VButton>
    </template>

  </VModal>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { async } from '@firebase/util';
import Calendar from 'primevue/calendar';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"

useHead({
  title: 'Pindah Pulang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let INFO = useRoute().query.info as string
let DEPARTEMEN_FK = useRoute().query.departemenfk as string
let pasien: any = ref({})

const confirm = useConfirm();
const isLoadingPasien: any = ref(false)
const isLoading: any = ref(false)
const isLoadingTT: any = ref(false)
const d_SK: any = ref([])
const d_SP: any = ref([])
const d_KP: any = ref([])
const d_HK: any = ref([])
const d_PK: any = ref([])
const d_Ruangan: any = ref([])
const d_Kamar: any = ref([])
const d_TempatTidur: any = ref([])
const d_Kelas: any = ref([])
const d_KelasAll: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  DEPARTEMEN_FK: DEPARTEMEN_FK != undefined ? DEPARTEMEN_FK : '',
  NOREC_APD: '',
  tglpindah: new Date(),
  tglmeninggal: new Date(),
  nobed: '',
  israwatgabung: false,
})
const dataSourceRiwayat: any = ref([])
const showPindah = ref(false)
const showPulang = ref(false)
const showMeninggal = ref(false)
const showRujuk = ref(false)
const modalInput: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
let listColor: any = ref(Object.keys(useThemeColors()))

const headerPasien = async (id: any) => {
  isLoadingPasien.value = true
  await useApi().get(`/diagnosa/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`)
    .then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.RUANGAN_ASAL = response.pasien.objectruanganasalfk
      item.KELAS_LAST = response.last_registrasi.objectkelasfk
      item.registrasi = response.last_registrasi
      item.namaruangan = response.last_registrasi.namaruangan
      item.nosep = response.last_registrasi.nosep
      item.kelompokpasien = response.last_registrasi.kelompokpasien
      item.tglmasuk = response.last_registrasi.tglmasuk
      item.tglpulang = response.last_registrasi.tglpulang
      item.objectruanganasalfk = response.last_registrasi.objectruanganasalfk
      item.nobed = response.last_registrasi.nobed
      item.nocm = response.pasien.nocm
      isLoadingPasien.value = false
      riwayatPasien()
    })
}

const dropdownList = async () => {
  await useApi().get(`/rawatinap/combo-pindah?departemenfk=${DEPARTEMEN_FK}`)
    .then((response: any) => {
      if (item.DEPARTEMEN_FK == 9) {
        response.statuskeluar.forEach(element => {
          if (element.statuskeluar != 'Rujuk' && element.statuskeluar != 'Pindah') {
            d_SK.value.push(element)
          }
        });
      } else {
        d_SK.value = response.statuskeluar
      }
      d_KP.value = response.kondisipasien.map((e: any) => {
        return { label: e.kondisipasien, value: e.id, default: e }
      })
      d_SP.value = response.statuspulang.map((e: any) => {
        return { label: e.statuspulang, value: e.kdstatuspulang, default: e }
      })
      d_HK.value = response.hubungankeluarga.map((e: any) => {
        return { label: e.hubungankeluarga, value: e.id, default: e }
      })
      d_PK.value = response.penyebabkematian.map((e: any) => {
        return { label: e.penyebabkematian, value: e.id, default: e }
      })
      d_Ruangan.value = response.namaruangan.map((e: any) => {
        return { label: e.namaruangan, value: e.id, default: e }
      })
    })
}

const riwayatPasien = async () => {
  dataSourceRiwayat.value.loading = true
  useApi()
    .get(`/rawatinap/riwayat-apd?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`)
    .then((response: any) => {
      for (let x = 0; x < response.length; x++) {
        const element = response[x]
        element.no = x + 1
        if (element.israwatgabung == true) {
          item.israwatgabung = element.israwatgabung
        }
        item.isnaikkelas = element.isnaikkelas
        item.iskelastitip = element.iskelastitip
      }
      dataSourceRiwayat.value = response
      dataSourceRiwayat.value.loading = false
    })
    .catch((e: any) => { })
}
const showModal = (e: any) => {
  clearInput()
  item.tglpulang = new Date()
  modalInput.value = true
  fetchKelas()
}

// Simpan Pulang Pasien
const simpanStatus = async () => {
  if (!item.tglpulang) {
    useToaster().error('Tanggal Pulang  harus di isi')
    return
  }
  if (!item.statuspulang) {
    useToaster().error('Status Pulang  harus di isi')
    return
  }
  if (!item.kondisipasien) {
    useToaster().error('Kondisi Pasien  harus di isi')
    return
  }
  if (item.kelompokpasien.indexOf("BPJS") !== -1) {
    let json = {
      "url": "SEP/2.0/updtglplg",
      "method": "PUT",
      "data": {
        "request": {
          "t_sep": {
            "noSep": item.nosep == undefined ? "" : item.nosep,
            "statusPulang": item.statuspulang,
            "noSuratMeninggal": item.nosuratmeninggal == undefined ? "" : item.nosuratmeninggal,
            "tglMeninggal": item.statuspulang == 4 ? H.formatDate(item.tglmeninggal, 'YYYY-MM-DD') : "",
            "tglPulang": H.formatDate(item.tglpulang, 'YYYY-MM-DD'),
            "noLPManual": item.nolpmanual ?? null,
            "user": H.namaPegawai()
          }
        }
      }

    }
    await useApi()
      .post(`/bridging/bpjs/tools`, json)
  }

  let objSave = {
    pasiendaftar: {
      norec_pd: item.NOREC_PD,
      objectstatuskeluarfk: item.statuskeluar,
      objectstatuspulangfk: item.statuspulang,
      objecthubungankeluargaambilpasienfk: item.hubungankeluarga ? item.hubungankeluarga : null,
      objectkondisipasienfk: item.kondisipasien,
      tglpulang: H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
      namalengkapambilpasien: item.namalengkapambilpasien ? item.namalengkapambilpasien : null,
    },
    antrianpasiendiperiksa: {
      norec_apd: item.NOREC_APD,
      tglkeluar: H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
      nobed: item.nobed
    },
    'nocm': pasien.value.nocm,
    'namapasien': pasien.value.namapasien,
    'noregistrasi': pasien.value.noregistrasi,
  }
  isLoading.value = true
  await useApi()
    .post(`/rawatinap/save-pulang-pasien`, objSave)
    .then((response: any) => {
      isLoading.value = false
      modalInput.value = false
      riwayatPasien()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

// Simpan Pindah Pasien
const simpanPindah = async () => {
  console.log(item.bed)
  if (!item.tglpulang) {
    useToaster().error('Tanggal Pindah Ruangan  harus di isi')
    return
  }
  if (!item.namaruangan) {
    useToaster().error('Nama Ruangan  harus di isi')
    return
  }
  if (!item.namakelas) {
    useToaster().error('Nama Kelas Rawat  harus di isi')
    return
  }
  if (!item.namakelasrawat) {
    useToaster().error('Nama Kelas Rawat  harus di isi')
    return
  }

  if (item.DEPARTEMEN_FK != 9) {
    if (!item.kamar) {
      useToaster().error('Nama Kamar Rawat  harus di isi')
      return
    }
    if (!item.bed) {
      useToaster().error('Nama Bed Rawat  harus di isi')
      return
    }
  }

  let namabed = null
  d_TempatTidur.value.forEach(element => {
    console.log(element.value)
    console.log(item.bed)
    console.log(element.label)
    if (element.value == item.bed) {
      namabed = element.label
    }
  });

  console.log('GANTI')
  console.log(namabed)

  let json = {
    pasiendaftar: {
      norec_pd: item.NOREC_PD,
      objectruanganasalfk: item.RUANGAN_LAST,
      objectruangantujuanfk: item.namaruangan,
      departemenfk: item.DEPARTEMEN_FK,
      iskelastitip: item.iskelastitip != null ? item.iskelastitip : null,
      isnaikkelas: item.isnaikkelas != null ? item.isnaikkelas : null
    },
    antrianpasiendiperiksa: {
      norec_apd: item.NOREC_APD,
      tglkeluar: H.formatDate(item.tglpindah, 'YYYY-MM-DD HH:mm:ss'),
      // objectkelasfk: item.namakelas,
      // objectkelasrawatfk: item.namakelasrawat,
      objectkelasfk: item.namakelasrawat,
      objectkelasrawatfk: item.namakelas,
      objectkamarfk: item.kamar ? item.kamar : null,
      objectbedfk: item.bed ? item.bed : null,
      nobed: item.bed ? item.bed : null,
      israwatgabung: item.israwatgabung ? item.israwatgabung : null,
    },
  }

  confirm.require({
    header: 'Konfirmasi Hak Kelas & Hak Rawat Inap Pasien',
    group: 'confirm',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: async () => {
      isLoading.value = true
      await useApi().post(`/rawatinap/save-pindah-pasien`, json).then((response: any) => {
        isLoading.value = false
        modalInput.value = false
        riwayatPasien()
      }).catch((e: any) => {
        if (e.message == 'Bed Sudah Terisi, Silakan Pilih Bed Lain') {
          changeKelas(item.namakelasrawat)
        }
        isLoading.value = false
      })
    },
    reject: () => { },
  })
}

// Simpan Pasien Meninggal
const simpanMeninggal = async () => {
  if (!item.tglmeninggal) {
    useToaster().error('Tanggal Meninggal  harus di isi')
    return
  }
  if (!item.statuspulang) {
    useToaster().error('Status Pasien  harus di isi')
    return
  }
  if (!item.kondisipasien) {
    useToaster().error('Kondisi Pasien  harus di isi')
    return
  }
  if (item.kelompokpasien == 'BPJS') {
    let json = {
      "url": "SEP/2.0/updtglplg",
      "method": "PUT",
      "data": {
        "request": {
          "t_sep": {
            "noSep": item.nosep,
            "statusPulang": item.statuspulang,
            "noSuratMeninggal": item.nosuratmeninggal == undefined ? "" : item.nosuratmeninggal,
            "tglMeninggal": item.statuspulang == 4 ? H.formatDate(item.tglmeninggal, 'YYYY-MM-DD') : "",
            "tglPulang": H.formatDate(item.tglpulang, 'YYYY-MM-DD'),
            "noLPManual": item.nolpmanual ?? null,
            "user": H.namaPegawai()
          }
        }
      }

    }
    await useApi()
      .post(`/bridging/bpjs/tools`, json)
  }

  let json = {
    pasiendaftar: {
      norec_pd: item.NOREC_PD,
      objectstatuskeluarfk: item.statuskeluar,
      objectstatuspulangfk: item.statuspulang,
      objecthubungankeluargaambilpasienfk: item.hubungankeluarga ? item.hubungankeluarga : null,
      objectkondisipasienfk: item.kondisipasien,
      objectpenyebabkematianfk: item.penyebabkematian,
      // tglpulang: H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
      tglmeninggal: H.formatDate(item.tglmeninggal, 'YYYY-MM-DD HH:mm:ss'),
      namalengkapambilpasien: item.namalengkapambilpasien ? item.namalengkapambilpasien : null,
    },
    antrianpasiendiperiksa: {
      norec_apd: item.NOREC_APD,
      nobed: item.nobed,
      tglkeluar: H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
    },
    pasien: {
      nocm: item.nocm,
      nocmfk: ID_PASIEN,
      tglmeninggal: H.formatDate(item.tglmeninggal, 'YYYY-MM-DD HH:mm:ss'),
    }
  }
  isLoading.value = true
  await useApi()
    .post(`/rawatinap/save-meninggal-pasien`, json)
    .then((response: any) => {
      isLoading.value = false
      modalInput.value = false
      riwayatPasien()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

// Simpan Pasien Rujuk
const simpanRujuk = async () => {
  if (!item.tglpulang) {
    useToaster().error('Tanggal Pulang  harus di isi')
    return
  }
  if (!item.rujuk) {
    useToaster().error('Tempat Rujukan  harus di isi')
    return
  }
  if (!item.statuspulang) {
    useToaster().error('Status Pulang  harus di isi')
    return
  }
  if (!item.kondisipasien) {
    useToaster().error('Kondisi Pasien harus di isi')
    return
  }

  let json = {
    pasiendaftar: {
      norec_pd: item.NOREC_PD,
      nocmfk: item.nocmfk,
      objectstatuskeluarfk: item.statuskeluar,
      objectstatuspulangfk: item.statuspulang,
      objecthubungankeluargaambilpasienfk: item.hubungankeluarga ? item.hubungankeluarga : null,
      objectkondisipasienfk: item.kondisipasien,
      tglpulang: H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
      rujuk: item.rujuk,
      alamatrujuk: item.alamatrujuk ? item.alamatrujuk : null,
      namalengkapambilpasien: item.namalengkapambilpasien ? item.namalengkapambilpasien : null,
    },
    antrianpasiendiperiksa: {
      norec_apd: item.NOREC_APD,
      nobed: item.nobed,
      tglkeluar: H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
    },
  }
  isLoading.value = true
  await useApi()
    .post(`/rawatinap/save-rujuk-pasien`, json)
    .then((response: any) => {
      isLoading.value = false
      modalInput.value = false
      riwayatPasien()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const back = () => {
  window.history.back()
}

const clearInput = () => {

  delete item.statuspulang
  delete item.kondisipasien
  // dropdown remove value
  delete item.namakelas;
  delete item.namakelasrawat;
  delete item.kamar;
  delete item.bed,
    delete item.ruangan,
    modalInput.value = false
}

const changeRuang = (e: any) => {
  item.namakelas = null;
  if (e) {
    if (showPindah.value = true) {
      setKelas(e)
    }
  }
}
const setKelas = async (e: any) => {
  isLoadingTT.value = true
  d_Kelas.value = []
  useApi().get(
    `/rawatinap/kelas-ranap-by-ruangan?id=${e}`)
    .then((response: any) => {
      if (response.length == 1) {
        item.namakelas = response[0].id
      }
      isLoadingTT.value = false
      d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}
const changeKelas = async (e: any) => {
  d_Kamar.value = []
  delete item.kamar
  delete item.bed
  let isRG = ''
  if (item.israwatgabung) { isRG = '&isRG=true' }
  if (e && item.namaruangan) {
    isLoadingTT.value = true
    useApi().get(
      `/rawatinap/kamar-ranap-by-kelas?id=${e}&idRuangan=${item.namaruangan}&isRG=${item.israwatgabung}`)
      .then((response: any) => {
        isLoadingTT.value = false
        d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
      })
      .catch((error: any) => { isLoadingTT.value = false })
  }

}
const fetchKelas = async () => {
  await useApi().get(
    `emr/dropdown/kelas_m?select=id,namakelas`
  ).then((response) => {
    d_KelasAll.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
  })
}
const changeKamar = async (e: any) => {
  d_TempatTidur.value = []
  if (e) {
    for (let x = 0; x < d_Kamar.value.length; x++) {
      const element = d_Kamar.value[x];
      if (element.value == e) {
        d_TempatTidur.value = element.default.details.map((e: any) => { return { label: e.reportdisplay, value: e.id, default: e } })
      }
    }
  }
}

const saveBatalPindah = async () => {

  let json = {
    pasiendaftar: {
      norec_pd: item.NOREC_PD,
      objectruanganasalfk: item.RUANGAN_ASAL,
      objectkelasfk: item.KELAS_LAST,
      objectruanganlastfk: item.RUANGAN_ASAL
    },
    antrianpasiendiperiksa: {
      norec_apd: item.NOREC_APD,
    }
  }
  isLoading.value = true
  await useApi()
    .post(`/rawatinap/batal-pindah-pasien`, json)
    .then((response: any) => {
      isLoading.value = false
      riwayatPasien()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}
const cacheKey = () => {
  return {
    cacheKey: 0 // Initialize cacheKey
  };
}

watch(
  () => item.statuskeluar,
  (newValue, oldValue) => {
    if (newValue.statuskeluar == 'Pindah') {
      showPindah.value = true
      item.statuspulang = d_SP.value[1] && d_SP.value[1].value
    } else {
      showPindah.value = false
    }

  }
)
watch(
  () => item.statuskeluar,
  (newValue, oldValue) => {
    if (newValue.statuskeluar == 'Meninggal') {
      showMeninggal.value = true
    } else {
      showMeninggal.value = false
    }
  }
)
watch(
  () => item.statuskeluar,
  (newValue, oldValue) => {
    if (newValue.statuskeluar == 'Rujuk') {
      showRujuk.value = true
      item.statuspulang = d_SP.value[1] && d_SP.value[1].value
    } else {
      showRujuk.value = false
    }
  }
)
watch(
  () => item.statuskeluar,
  (newValue, oldValue) => {
    if (newValue.statuskeluar == 'Pulang') {
      showPulang.value = true
      item.statuspulang = d_SP.value[1] && d_SP.value[1].value
    } else {
      showPulang.value = false
    }
  }
)

const data = [
  {
    type: 'messages',
    count: 5,
    status: 'new',
  },
  {
    type: 'tasks',
    count: 3,
    status: 'pending',
  },
]

const columns = {
  type: {
    label: 'Type',
    grow: 'lg',
    media: true,
  },
  count: {
    label: 'Count',
    align: 'center',
  },
  status: 'Status',
  actions: {
    label: 'Actions',
    align: 'end',
  },
} as const

headerPasien(ID_PASIEN)
if (!INFO) {
  dropdownList()
}

</script>

<style lang="scss" scoped>
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';

.list-view-v1 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      .meta-left {
        margin-left: 16px;

        h3 {
          font-family: var(--font-alt);
          color: var(--dark-text);
          font-weight: 600;
          font-size: 1rem;
          line-height: 1;
        }

        >span:not(.tag) {
          font-size: 0.9rem;
          color: var(--light-text);

          svg {
            height: 12px;
            width: 12px;
          }
        }
      }

      .meta-right {
        margin-left: auto;
        display: flex;
        justify-content: flex-end;
        align-items: center;

        .tags {
          margin-right: 30px;
          margin-bottom: 0;

          .tag {
            margin-bottom: 0;
          }
        }

        .stats {
          display: flex;
          align-items: center;
          margin-right: 30px;

          .stat {
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
            color: var(--light-text);

            >span {
              font-family: var(--font);

              &:first-child {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--dark-text);
                line-height: 1.4;
              }

              &:nth-child(2) {
                text-transform: uppercase;
                font-family: var(--font-alt);
                font-size: 0.75rem;
              }
            }

            svg {
              height: 16px;
              width: 16px;
            }

            i {
              font-size: 1.4rem;
            }
          }

          .separator {
            height: 25px;
            width: 2px;
            border-right: 1px solid var(--fade-grey-dark-3);
            margin: 0 16px;
          }
        }

        .network {
          display: flex;
          justify-content: flex-end;
          align-items: center;
          min-width: 145px;

          >span {
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--light-text);
            margin-left: 6px;
          }
        }

        .dropdown {
          margin-left: 30px;
        }
      }
    }
  }
}

.is-dark {
  .list-view-v1 {
    .list-view-item {
      @include vuero-card--dark;

      .list-view-item-inner {
        .meta-left {
          h3 {
            color: var(--dark-dark-text) !important;
          }
        }

        .meta-right {
          .stats {
            .stat {
              span {
                &:first-child {
                  color: var(--dark-dark-text);
                }
              }
            }

            .separator {
              border-color: var(--dark-sidebar-light-16) !important;
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .list-view-v1 {
    .list-view-item {
      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            >span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .list-view-v1 {
    display: flex;
    flex-wrap: wrap;

    .list-view-item {
      margin: 10px;
      width: calc(50% - 20px);

      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            >span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item[data-v-4206d2a0]::before {
  content: none;
}

.p-inputtext .p-component {
  border-top-left-radius: 15px;
  border-bottom-left-radius: 15px;
  display: none;
}

.is-rounded-select {
  .p-calendar {
    border-radius: 20px !important;

    .p-inputtext {
      border-top-left-radius: 15px !important;
      border-bottom-left-radius: 15px !important;
    }
  }

  .p-calendar-w-btn .p-datepicker-trigger {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-top-right-radius: 15px !important;
    border-bottom-right-radius: 15px !important;
  }
}
</style>
