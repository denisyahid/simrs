<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">


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

                      <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                      <VTag color="orange" :label="pasien.kelompokpasien" />
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="personal-dashboard personal-dashboard-v2">
      <div class="columns is-multiline">
        <div class="column is-12" v-if="pasien.namapasien == undefined">
          <VCard>
            <PlaceloadHeader />
          </VCard>
        </div>
        <div class="column is-12 mt-0">
          <div class="dashboard-card has-margin-bottom">
            <div class="card-head">
              <h3 class="dark-inverted"> TRANSAKSI PELAYANAN </h3>
            </div>
            <div class="active-projects">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VField>
                    <VControl icon="feather:search">
                      <input v-model="filters" type="text" class="input is-rounded" placeholder="Filter " />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                </div>
                <div class="column is-7">
                  <div class="columns is-multiline is-pulled-right mr-4">
                    <div class="column  pr-0 m-0">
                      <VButton class="ml-2 is-pulled-right" icon="feather:printer" raised bold @click="cetakLabel(item)"
                        :loading="isLoadingBill" color="purple">
                        Cetak Label
                      </VButton>
                    </div>
                    <!-- <div class="column  pr-0 m-0">
                      <VButton class="ml-2 is-pulled-right" icon="feather:trash" raised bold @click="batalLis(listChecked)" :disabled="disablekan" color="danger">
                        Hapus Order (LIS)
                      </VButton>
                    </div> -->
                    <div class="column pr-0 m-0">
                      <VButton class="ml-2 is-pulled-right" icon="pi pi-plus" raised bold @click="inputTindakan(item)"
                        :loading="isLoadingBill" color="info">
                        Input Tindakan
                      </VButton>
                    </div>
                    <!-- <div class="column pr-0">
                      <VButton class="ml-1 is-pulled-right" icon="feather:plus-circle" raised bold
                        @click="hasilLabLIS(item.norec)" :loading="isLoadingBill" color="primary">
                        Hasil Lab
                      </VButton>
                    </div> -->
                    <div class="column  m-0 ">
                      <SplitButton label="Lainnya" icon="pi pi-info-circle" size="medium" :model="listButton" />
                      <!-- <SpeedDial :model="listButton" direction="right" :transitionDelay="80" showIcon="pi pi-ellipsis-v"
                        hideIcon="pi pi-times" buttonClassName="p-button-outlined" className="speeddial-right"
                        class="secondary" type="semi-circle" :tooltipOptions="listButton" /> -->
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="column is-12">
                    <table class="tb-custom mt-3">
                      <thead>
                        <tr>
                          <th width="10%" class="text-center">
                            <VControl raw subcontrol style="margin-top:-10px">
                              <VCheckbox v-model="item.checkAll" label="#" color="info"
                                @change="checkedAll(item.checkAll)" :value="item.checkAll" />
                            </VControl>
                          </th>
                          <th width="30%">LAYANAN</th>
                          <th>HARGA SATUAN</th>
                          <th>JUMLAH</th>
                          <th>SUBTOTAL</th>
                          <th>OPSI</th>
                        </tr>

                      </thead>
                      <tbody v-if="isLoadingBill">
                        <tr>
                          <td colspan="5">
                            <div class="list-view list-view-v1 is-fullwidth">
                              <div class="list-view-inner">
                                <div v-for="key in 6" :key="key" class="list-view-item mt-2">
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
                          </td>
                        </tr>
                      </tbody>
                      <div style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
                        <tbody v-if="!isLoadingBill" v-for="(items, index) in dataSourcefiltered" :key="index">
                          <tr>
                            <td colspan="5" class="koneng">
                              {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                            </td>
                          </tr>
                          <tr v-for="(itemsDet, index2) in items.details" :key="index2">
                            <td width="5%">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="modelCheck[itemsDet.norec]" :value="itemsDet.itemsDet" color="info"
                                  square :class="modelCheck[items.norec] == true ? 'is-solid' : ''"
                                  @change="checkedItems()" />
                              </VControl>
                            </td>
                            <td width="30%">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <div class="title-ruangan">{{ itemsDet.namaruangan }}</div>
                                  <div class="title-layan">{{ itemsDet.namaproduk }} - {{
                                    itemsDet.dokterpemeriksa ?
                                      itemsDet.dokterpemeriksa : 'Dokter belum di input'
                                  }}</div>
                                  <div>
                                    <VTag :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                      :label="itemsDet.tglpelayanan" />
                                    <VTag :color="'secondary'" class="ml-2" label="Kirim LIS"
                                      v-if="itemsDet.idbridging != null" />
                                  </div>
                                  <div class="title-kelas">
                                    {{ itemsDet.namakelas }} / <b>{{ itemsDet.noorder }}</b>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="center">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <div class="title-ruangan">Jasa : {{ H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                  <div class="title-layan">{{ H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}</div>
                                  <div class="title-kelas">Diskon : {{ H.formatRp(itemsDet.hargadiscount, 'Rp. ') }}
                                  </div>
                                </div>
                              </div>
                              <!-- {{ H.formatRp(itemsDet.hargasatuan, 'Rp. ') }} -->
                            </td>
                            <td class="center">{{ itemsDet.jumlah }}</td>
                            <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}</td>
                            <td class="center">
                              <VIconButton color="warning" class="mr-2" light raised circle icon="feather:file"
                                v-if="itemsDet.namafile != null" @click="lihatDok(itemsDet)"
                                v-tooltip.bubble="'Lihat File'" />
                              <VIconButton color="primary" class="mr-2" light raised circle icon="feather:user"
                                @click="detailPetugas(itemsDet)" v-tooltip.bubble="'Detail Petugas Pelayanan'" />
                              <!-- <VIconButton color="info" light raised circle icon="pi pi-book" class="mr-1"
                                v-tooltip.bubble="'Hasil Pap Smear'" @click="HasilPapSmear(itemsDet)" /> -->
                              <!-- <VIconButton color="purple" light raised circle icon="pi pi-book" class="mr-1"
                                v-tooltip.bubble="'Hasil PCR'" @click="showModalInputPCR(itemsDet)" /> -->
                              <VIconButton color="warning" light raised circle icon="pi pi-book" class="mr-1"
                                v-tooltip.bubble="'Hasil Antigen'" @click="showModalInputMikro(itemsDet)" />
                              <VIconButton color="warning" light raised circle icon="pi pi-book" class="mr-1"
                                v-tooltip.bubble="'Hasil kultur'" @click="hasilLabCultture(itemsDet)" />
                              <VIconButton color="info" light raised icon="pi pi-book" class="mr-1"
                                v-tooltip.bubble="'Hasil Lab PA'" @click="HasilLabPA(itemsDet)" />
                              <VIconButton color="danger" class="mr-2" light raised circle icon="feather:trash"
                                @click="hapusItems(itemsDet)" v-tooltip.bubble="'Hapus Layanan'" />

                            </td>
                          </tr>
                        </tbody>
                        <div class="search-results-wrapper"
                          v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                          <div class="search-results-body ">
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
                    </table>
                  </div>

                </div>
              </div>
              <div class="content mt-0 mb-0">
                <div class="is-divider mt-3 mb-2" data-content="TOTAL"></div>
              </div>
              <div class="load-more-wrap has-text-centered p-1 mb-3">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                      <div class="label-status primary">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">TAGIHAN</span>
                      </div>
                      <small class="text-bold-custom">{{
                        H.formatRp(item.TOTAL,
                          'Rp.')
                      }}</small>

                    </VCardCustom>
                  </div>



                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <VModal :open="modalPetugas" title="Detail Petugas Tindakan" :noclose="false" size="medium" actions="right"
    @close="modalPetugas = false">
    <template #content>
      <form class="modal-form custom-mod ">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-6">
                  <p class="block-text">Tanggal Pelayanan</p>
                  <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>

                </div>
                <div class="column is-6">
                  <p class="block-text">Nama Pelayanan</p>
                  <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>

                </div>
                <div class="column is-12">
                  <VCard class="is-grey">
                    <div class="columns is-multiline p-1">
                      <div class="column is-12 mb--15 text-center" v-if="dataSourcePetugas.length == 0">
                        <img class="light-image" style=" max-width: 180px;" :src="H.assets().iconNotFoundCalendar"
                          alt="" />
                        <h3>{{ H.assets().notFound }}</h3>
                      </div>
                      <div class="column is-12 mb--15" v-else v-for="(item, index) in dataSourcePetugas" :key="index">
                        <div class="columns is-multiline p-0">
                          <div class="column is-9">
                            <div class="file-box-2">
                              <img :src="'/images/icons/files/dokter.svg'" alt="" />
                              <div class="meta">
                                <span>{{ item.namalengkap }} </span>
                                <span>
                                  <b>{{ item.jenispetugaspe }}</b>
                                </span>
                              </div>
                              <div class="is-right is-dots is-spaced dropdown end-action">
                                <i aria-hidden="true" class="fas fa-check-circle  is-pulled-right"
                                  style="color:var(--success)"></i>
                              </div>
                            </div>

                          </div>
                          <div class="column is-3 mt-5">
                            <VIconButton outlined type="button" class="mr-2" raised circle icon="feather:edit"
                              @click="editPetugas(item)" color="info">
                            </VIconButton>
                            <VIconButton outlined type="button" raised circle icon="feather:trash"
                              @click="hapusPetugas(item)" color="danger">
                            </VIconButton>
                          </div>

                        </div>
                      </div>

                    </div>
                  </VCard>
                </div>


              </div>
            </VCard>
          </div>
          <div class="column is-6">
            <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:plus-circle" fullwidth>
                <Multiselect mode="single" v-model="item.jenisPelaksana" :options="d_JenisPelaksana"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                  @select="changeJenis(item.jenisPelaksana)" track-by="label" />

              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField class="is-rounded-select" v-slot="{ id }">
              <VLabel>Pegawai</VLabel>
              <VControl fullwidth>
                <Multiselect mode="single" v-model="item.pegawai" :options="d_Pegawai" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" track-by="label" />

                <!-- <Multiselect mode="tags" :create-tag="true" placeholder="Pilih data" v-model="item.pegawai"
                  :options="d_Pegawai" :searchable="true" :attrs="{ id }" autocomplete="off" /> -->
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="savePetugas()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>

  <VModal :open="modalHasilLabPA" title="Hasil Lab PA" :noclose="false" size="big" actions="right"
    @close="modalHasilLabPA = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-2">
            <VField>
              <VLabel>No. PA</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.nomorpa" placeholder="Nomor PA" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Nama Pelayanan</VLabel>
              <VControl icon="feather:box">
                <VInput type="text" v-model="item.namaproduk" placeholder="Nama Pelayanan" class="is-rounded"
                  disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Tanggal">
              <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Dokter Baca</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.pegawaifk" :options="d_Dokter" :optionLabel="'label'" optionValue="value"
                  class="is-rounded" placeholder="Pilih Dokter" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
              <VLabel class="required-field">Dokter Pengirim</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.dokterpengirimfk" :searchable="true" :options="d_Dokter" :optionLabel="'label'"
                  optionValue="value" class="is-rounded" placeholder="Pilih Dokter" style="width: 100%;"
                  :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VLabel>Diagnosa Klinis</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.diagnosaklinik" placeholder="Diagnosa Klinis" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Keterangan</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.keteranganklinik" placeholder="Keterangan Klinik"
                  class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Jaringan Asal</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.jaringanasal" placeholder="Jaringan Asal" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-8 mt-5">
            <VField>
              <VControl>
                <VRadio v-model="item.jenis" value="pa" label="HISTOPATOLOGI" name="outlined_radio" color="info" />
                <VRadio v-model="item.jenis" value="sitologi" label="SITOLOGI" name="outlined_radio" color="danger" />
              </VControl>
            </VField>
          </div>

          <div class="column is-3" v-if="showJenis">
            <VField label="Diagnosis PB">
              <VControl icon="feather:user">
                <VInput type="text" v-model="item.diagnosapb" placeholder="Diagnosis PB" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3" v-if="showJenis">
            <VField label="Keterangan PB">
              <VControl icon="feather:user">
                <VInput type="text" v-model="item.keteranganpb" placeholder="Diagnosis PB" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3" v-if="showJenis">
            <VField label="Topografi">
              <VControl icon="feather:user">
                <VInput type="text" v-model="item.topografi" placeholder="Topografi" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3" v-if="showJenis">
            <VField label="Morfologi">
              <VControl icon="feather:user">
                <VInput type="text" v-model="item.morfologi" placeholder="Morfologi" class="is-rounded" />
              </VControl>
            </VField>
          </div>

          <div class="column is-12">
            <TabView class="tabview-custom">
              <TabPanel>
                <template #header>
                  <i class="pi pi-calendar mr-2"></i>
                  <span>Makroskopik</span>
                </template>
                <p>
                  <VField>
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.makroskopik" rows="4"
                        placeholder="Catatan Makroskopik" autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </p>
              </TabPanel>
              <TabPanel>
                <template #header>
                  <i class="fas fa-stethoscope mr-2"></i>
                  <span>Mikroskopik</span>
                </template>
                <p>
                  <VField>
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.mikroskopik" rows="4"
                        placeholder="Catatan Mikroskopik" autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </p>
              </TabPanel>
              <TabPanel>
                <template #header>
                  <i class="fas fa-file-medical-alt mr-2"></i>
                  <span>Kesimpulan</span>
                </template>
                <p>
                  <VField>
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.kesimpulan" rows="4"
                        placeholder="Catatan Kesimpulan" autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </p>
              </TabPanel>
              <TabPanel>
                <template #header>
                  <i class="fas fa-clinic-medical mr-2"></i>
                  <span>Anjuran</span>
                </template>
                <p>
                  <VField>
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.anjuran" rows="4"
                        placeholder="Catatan Anjuran" autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </p>
              </TabPanel>


            </TabView>

          </div>
        </div>
      </form>

    </template>
    <template #action>
      <VButton icon="feather:printer" @click="cetakHasilLap(item)" v-if="item.norecHasil" :loading="isLoadingTT"
        color="purple" raised>Cetak
      </VButton>
      <VButton icon="feather:save" @click="saveHasilLabPA(item)" :loading="isLoadingTT" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>

  <VModal :open="modalHasilPapSmear" title="Hasil Pap Smear" :noclose="false" size="big" actions="right"
    @close="modalHasilPapSmear = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField>
              <VLabel>No. Sediaan</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="number" v-model="input.nomorsediaan" placeholder="Nomor PA" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Dokter Baca</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.pegawaifk" :options="d_Dokter" optionLabel="label" class="is-rounded"
                  placeholder="Pilih Dokter" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Dokter Pengirim</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.dokterpengirimfk" :options="d_Dokter" optionLabel="label" class="is-rounded"
                  placeholder="Pilih Dokter" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12 is-flex">
            <h1 class="column is-2" style="font-weight: bold;">Kategori Umum</h1>
            <div class="column is-2" v-for="(data, i) in kategoriUmum">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title" class="p-0"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <h1 class="column is-2" style="font-weight: bold;">Adekuasi Pemeriksaan</h1>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <Dropdown v-model="input[adekuasiPemeriksaan]" :options="d_Adekuasi" optionLabel="label"
                    class="is-rounded" placeholder="Pilih Data" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="input.keterenganAdekuasi" placeholder="Keterangan Adekuasi"
                    class="is-rounded" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <h1 class="column is-2" style="font-weight: bold;">Infeksi Organisme</h1>
            <div class="column is-2" v-for="(data, i) in infeksiOrganisme">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title" class="p-0"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-flex">
            <h1 class="column is-2" style="font-weight: bold;">Non Neoplastik Lain</h1>
            <div class="column is-2" v-for="(data, i) in nonNeoPlastik">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title" class="p-0"
                    color="primary" square />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <Dropdown v-model="input[optionNonNeoPlastik]" :options="d_optionNonNeoPlastik" optionLabel="label"
                    class="is-rounded" placeholder="Pilih Data" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-12 is-multiline">
            <h1 class="column is-2" style="font-weight: bold;">Sel Skuamosa</h1>
            <div class="column is-12 is-flex card my-2" v-for="(data, i) in selSkuamosa">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2" v-if="data.option" v-for="(option, i) in data.option">
                <VField>
                  <VControl raw subcontrol>
                    <VRadio v-model="input[option.model]" :value="option.value.code" :label="option.title"
                      :name="option.model" square color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 is-multiline">
            <h1 class="column is-2" style="font-weight: bold;">Sel Glandular</h1>
            <div class="column is-12 is-flex card my-2" v-for="(data, i) in selGlandular">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2" v-if="data.option" v-for="(option, i) in data.option">
                <VField>
                  <VControl raw subcontrol>
                    <VRadio v-model="input[option.model]" :value="option.value.code" :label="option.title"
                      :name="option.model" square color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <h1 class="column is-2" style="font-weight: bold;">Neoplasma Ganas</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.neoplasmaGanas" rows="2" placeholder="Neoplasma Ganas"></VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <h1 class="column is-2" style="font-weight: bold;">Kesimpulan</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.kesimpulan" rows="4" placeholder="Kesimpulan"></VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <h1 class="column is-2" style="font-weight: bold;">Saran</h1>
            <VField>
              <VControl>
                <VTextarea v-model="input.saran" rows="2" placeholder="Saran"></VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
      </form>

    </template>
    <template #action>
      <VButton icon="feather:printer" @click="cetakHasilLap(item)" v-if="item.norecHasil" :loading="isLoadingTT"
        color="purple" raised>Cetak
      </VButton>
      <VButton icon="feather:save" @click="saveHasilLabPA(item)" :loading="isLoadingTT" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>

  <VModal :open="modalCatatan" title="Catatan" :noclose="false" size="medium" actions="right"
    @close="modalCatatan = false">
    <template #content>
      <div class="column is-12">
        <VField>
          <VControl>
            <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4" placeholder="Catatan"
              autocomplete="off" autocapitalize="off" spellcheck="true" />
          </VControl>
        </VField>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveCatatan(item)" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalKirimLIS" title="Kirim Ke LIS" :noclose="false" size="medium" actions="right"
    @close="modalKirimLIS = false">
    <template #content>
      <div class="column is-12">
        <VField class="is-rounded-select is-autocomplete-select">
          <VLabel class="required-field">Dokter Pengirim</VLabel>
          <VControl icon="feather:search" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.objectpegawaifk" :options="d_Dokter" optionLabel="label" class="is-rounded"
              placeholder="Pilih Dokter" style="width: 100%;" :filter="true" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VControl>
            <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4" placeholder="Catatan"
              autocomplete="off" autocapitalize="off" spellcheck="true" />
          </VControl>
        </VField>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveCatatan(item)" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <!-- modal hasil pcr -->
  <VModal :open="modalInputPCR" :title="'Formulir Hasil Pemeriksaan  ' + item.title" :noclose="false" size="big"
    actions="right" @close="modalInputPCR = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-4">
                  <VField label="No Passport">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="No Passport" v-model="item.noPassport">
                      </VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="No Pemerikasaan">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="No Pemeriksaan"
                        v-model="item.nopemerikasaan"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Ruangan">
                    <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" class="is-rounded mt-3"
                      @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="ketik untuk mencari..." />
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline p-1">
                <div class="column is-3">
                  <VField label="Tgl Selesai Sampel" class="is-rounded-select">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglSelesaiSample" selectionMode="single" :manualInput="true"
                          class="w-100 mt-4 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Tgl Terima Sampel" class="is-rounded-select">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglTerimaSample" selectionMode="single" :manualInput="true"
                          class="w-100 mt-4 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Jenis Spesimen">
                    <VControl class="prime-auto">
                      <Dropdown v-model="item.jenisspesimenfk" :options="d_JenisSpesimen" :optionLabel="'label'"
                        class="is-rounded mt-3" style="width: 100%;" :filter="true" appendTo="body" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Metode Periksa">
                    <VControl class="prime-auto">
                      <Dropdown v-model="item.metodeperiksafk" :options="d_MetodePeriksa" :optionLabel="'label'"
                        class="is-rounded mt-3" style="width: 100%;" :filter="true" appendTo="body" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline p-1">
                <div class="column is-3">
                  <VField label="Kode Sample">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="Kode Sample" v-model="item.kodesampel">
                      </VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Sample Ke">
                    <VControl raw subcontrol>
                      <VInput type="number" class="mt-3 is-rounded" placeholder="Kode Sample" v-model="item.sample">
                      </VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Hasil">
                    <div class="columns is-multiline">
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox label="Negatif" true-value="false" class="mt-3 is-rounded" v-model="item.hasil">
                          </VCheckbox>
                        </VControl>
                      </div>
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox label="Positif" true-value="true" class="mt-3 is-rounded" v-model="item.hasil">
                          </VCheckbox>
                        </VControl>
                      </div>
                    </div>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline p-1">
                <div class="column is-4">
                  <VField label="Petugas Veririfikator">
                    <AutoComplete v-model="item.petugasverifikatorfk" :suggestions="d_Dokter" class="is-rounded mt-3"
                      @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="ketik untuk mencari..." />
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Pegawai Pemeriksaan">
                    <AutoComplete v-model="item.pemeriksa" :suggestions="d_Dokter" class="is-rounded mt-3"
                      @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="ketik untuk mencari..." />
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Disetujuhi">
                    <AutoComplete v-model="item.setujuh" :suggestions="d_Dokter" class="is-rounded mt-3"
                      @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="ketik untuk mencari..." />
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline p-1">
                <div class="column is-12">
                  <VField label="Keterangan">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="3" placeholder="Keterangan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VSwitchBlock v-model="isInfoTambahan" label="Informasi Tambahan" color="danger" />
                  </VControl>
                </VField>
              </div>
              <div class="columns is-multiline p-1" v-if="isInfoTambahan">
                <div class="column is-6">
                  <VField label="Keperluan Melakukan Pemeriksaan PCR">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.keperluaan" rows="2"
                        placeholder="Keperluan Melakukan Pemeriksaan PCR" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Keadaan saat pemeriksaan PCR">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.keadaan" rows="2"
                        placeholder="Keadaan saat Pemeriksaan PCR" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Berpergiaan keluar kota / negri dalam kurung waktu 2 Minggu kedepan">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.keluarkota" rows="2"
                        placeholder="Berpergiaan keluar kota / negri dalam kurung waktu 2 Minggu kedepan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Kota / Negara yang dikunjungi">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.kotanegara" rows="2"
                        placeholder="Kota / Negara yang dikunjungi" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
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
      <VButton icon="feather:save" @click="cetakHasilPCR()" v-if="item.norec" :loading="isLoadingTT" color="purple"
        raised>
        Cetak
      </VButton>
      <VButton icon="feather:save" @click="savePCR()" :loading="isLoadingTT" color="info" raised>
        Save
      </VButton>
    </template>
  </VModal>

  <VModal :open="modalInputMikro" title="Formulir Hasil Pemeriksaan Lab Mikro" :noclose="false" size="big"
    actions="right" @close="modalInputMikro = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-4">
                  <VField label="No. Rekam Medis">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3" placeholder="No RM" v-model="item.nocm" disabled>
                      </VInput>
                    </VControl>
                  </VField>
                  <VField label="Nama Pasien">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3" placeholder="Nama Pasien" v-model="item.namapasien" disabled>
                      </VInput>
                    </VControl>
                  </VField>
                  <VField label="No. Registrasi">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3" placeholder="No. Registrasi" v-model="item.noreg" disabled>
                      </VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Jenis Spesimen Pemeriksaan Mikro">
                    <VControl class="prime-auto">
                      <Dropdown v-model="item.jenisspesimen" :options="d_JenisPemeriksaan" :optionLabel="'label'"
                        class="mt-3" style="width: 100%;" :filter="true" appendTo="body"
                        placeholder="Pilih Jenis Pemeriksaan" />
                    </VControl>
                  </VField>
                  <VField label="Kode Spesimen">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3" placeholder="Kode Spesimen" v-model="item.kodespesimen">
                      </VInput>
                    </VControl>
                  </VField>
                  <VField label="Asal">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3" placeholder="Asal Spesimen" v-model="item.asalspesimen">
                      </VInput>
                    </VControl>
                  </VField>
                  <VField label="Spesimen Ke">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3" placeholder="Spesimen Ke" v-model="item.spesimenke"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Tgl & Jam Spesimen Diterima" class="">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglterimaspesimen" selectionMode="single" :manualInput="true"
                          class="w-100 mt-4 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                  <VField label="Tgl Spesimen Dikerjakan" class="">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tgldikerjakanspesimen" selectionMode="single" :manualInput="true"
                          class="w-100 mt-4 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                  <VField label="Tgl Keluar Hasil" class="">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglkeluarhasil" selectionMode="single" :manualInput="true"
                          class="w-100 mt-4 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                  <VField label="Hasil Spesimen">
                    <VControl>
                      <VRadio v-model="item.hasilspesimen" value="Negatif" label="Negatif" name="outlined_radio"
                        color="info" />
                      <VRadio v-model="item.hasilspesimen" value="Positif" label="Positif" name="outlined_radio"
                        color="danger" />
                    </VControl>
                  </VField>
                  <VField label="Dokter Pemeriksa">
                    <VControl>
                      <AutoComplete v-model="item.dokterpemeriksa" :suggestions="d_Dokter" class="mt-3"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="ketik untuk mencari..." />
                    </VControl>
                  </VField>
                </div>
              </div>
              <!-- Data Header -->
              <!-- <div class="columns is-multiline p-1">
                <div class="column is-3">
                  
                </div>
                <div class="column is-3">
                  <VField label="Tgl Terima Sampel" class="is-rounded-select">
                    <VControl class="prime-auto ">
                      <div class="is-rounded is-rounded-select">
                        <Calendar v-model="item.tglterimasampel" selectionMode="single" :manualInput="true"
                          class="w-100 mt-4 is-rounded" showTime :showIcon="true" hourFormat="24"
                          :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  
                </div>
                <div class="column is-3">
                 
                </div>
              </div> -->
              <!-- Data Pemeriksaan Mikro -->
              <!-- <div class="columns is-multiline p-1">
                <div class="column is-3">
                  <VField label="PMN">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="PMN" v-model="item.pmn"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="GPC">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="GPC" v-model="item.gpc"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="GPR">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="GPR" v-model="item.gpr"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="GNDC">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="GNDC" v-model="item.gndc"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="SEC">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="SEC" v-model="item.sec"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="GNR">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="GNR" v-model="item.gnr"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="GNCB">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="GNCB" v-model="item.gncb"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="YC/PH/H">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="YC/PH/H" v-model="item.ycphh"></VInput>
                    </VControl>
                  </VField>
                </div>
              </div> -->
              <!-- Bagian Note -->
              <!-- <div class="columns is-multiline p-1">
                <div class="column is-12">
                  <VField label="Note">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.note" rows="4" placeholder="Note"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Pemeriksaan">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="Pemeriksaan" v-model="item.pemeriksaan">
                      </VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Pemeriksaan Penunjang">
                    <VControl raw subcontrol>
                      <VTextarea class="textarea mt-3 is-rounded" rows="3" placeholder="Pemeriksaan Penunjang"
                        v-model="item.pemeriksaanpenunjang"></VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div> -->
              <!-- Bagian Penunjang -->
              <!-- <div class="columns is-multiline p-1">
                <div class="column is-2">
                  <VField label="Bas">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="Bas" v-model="item.bas"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Eos">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="Eos" v-model="item.eos"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Bat">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="Bat" v-model="item.bat"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Seg">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="Seg" v-model="item.seg"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Limf">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="Limf" v-model="item.limf"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Sun">
                    <VControl raw subcontrol>
                      <VInput type="text" class="mt-3 is-rounded" placeholder="Sun" v-model="item.sun"></VInput>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline p-1">
                <div class="column is-12">
                  <VField label="Antibiotik yang diberikan">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.antibiotik" rows="4"
                        placeholder="Antibiotik yang diberikan" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Pemeriksaan Kultur">
                    <table class="table is-hoverable is-bordered is-fullwidth table-tg">
                      <thead>
                        <tr>
                          <th scope="col" width="20%">Tanggal</th>
                          <th scope="col" width="30%">Pemeriksa</th>
                          <th scope="col">Observasi Hasil dan Tindak Lanjut</th>
                          <th scope="col">
                            <div class="dark-inverted">Aksi</div>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td style="vertical-align: top;">
                            <VControl class="prime-auto">
                              <div class="is-rounded is-rounded-select">
                                <Calendar v-model="item.tglkultur" selectionMode="single" :manualInput="true"
                                  class="w-100 mt-4 is-rounded" showTime :showIcon="true" hourFormat="24"
                                  :date-format="'yy-mm-dd'" />
                              </div>
                            </VControl>
                          </td>
                          <td style="vertical-align: top">
                            <VControl>
                              <AutoComplete v-model="item.pemeriksakultur" :suggestions="d_Dokter" class="is-rounded mt-3"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="ketik untuk mencari..." />
                            </VControl>
                          </td>
                          <td>
                            <VControl>
                              <VTextarea class="textarea mt-3 is-rounded" rows="4"
                                placeholder="Observasi dan tindak lanjut" v-model="item.observasikultur"></VTextarea>
                            </VControl>
                          </td>
                          <td>
                            <div class="is-flex is-justify-content-flex-end">

                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Hasil Akhir Pelaporan Kultur">
                    <VControl raw subcontrol>
                      <VTextarea class="mt-3 is-rounded textarea" rows="4" placeholder="Hasil akhir pelaporan kultur"
                        v-model="item.hasilakhirkultur"></VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Hasil Uji Kepekaan dan Expertise">
                    <VControl raw subcontrol>
                      <VTextarea class="mt-3 is-rounded textarea" rows="4" placeholder="Hasil uji kepekaan dan expertise"
                        v-model="item.hasilujikepekaan"></VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12">
                
              </div> -->
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="cetakHasilMikro()" v-if="item.norecMikro" :loading="isLoadingTT"
        color="purple" raised>
        Cetak
      </VButton>
      <VButton icon="feather:save" @click="saveMikro()" :loading="isLoadingTT" color="info" raised>
        Save
      </VButton>
    </template>
  </VModal>
  <!-- end modal hasil pcr -->
  <a href="{{ linkHasil }}" class="madepcenah" target="_blank" style="display: none;"></a>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useWindowScroll } from '@vueuse/core'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import SpeedDial from 'primevue/speeddial'
import SplitButton from 'primevue/splitbutton';
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Dropdown from 'primevue/dropdown'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import * as EMR from '../laboratorium/papsmear'
import * as Mikro from '../laboratorium/labmikro'
import Calendar from 'primevue/calendar';
import $ from "jquery";
import AutoComplete from 'primevue/autocomplete';
useHead({
  title: 'Transaksi Pelayanan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREC_APD = useRoute().query.norec_apd as string
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREGISTRASI = useRoute().query.noregistrasi as string
let NOORDER = useRoute().query.noorder as string ?? ''
let kategoriUmum = ref(EMR.kategoriUmum())
let infeksiOrganisme = ref(EMR.infeksiOrganisme())
let adekuasiPemeriksaan = ref(EMR.adekuasiPemeriksaan())
let jenisPemeriksaan = ref(Mikro.jenisPemeriksaan())
let asalSpesimen = ref(Mikro.asalSpesimen())
let nonNeoPlastik = ref(EMR.nonNeoPlastik())
let selSkuamosa = ref(EMR.selSkuamosa())
let selGlandular = ref(EMR.selGlandular())
let isInfoTambahan = ref(false)
let optionNonNeoPlastik = ref(EMR.nonNeoPlastikSelect())
const isLoadingPasien: any = ref(false)
const isLoadingBill: any = ref(false)
const isLoadingPop: any = ref(false)
const pasien: any = ref({})
const dataSource: any = ref([])
const LISTRUANGAN_APD: any = ref([])
const LISTRUANGAN_APD_G: any = ref([])
const dataSourcePetugas: any = ref([])
const router = useRouter()
const listChecked: any = ref([])
const modelCheck: any = ref([])
const filters = ref('')
const filtersHide = ref('')
const isLoadingTT: any = ref(false)
const modalPetugas = ref(false)
const modalTanggal = ref(false)
const modalInputPCR = ref(false)
const disablekan = ref(false)
const modalInputMikro = ref(false)
const modalHasilLabPA: any = ref(false)
const modalHasilPapSmear: any = ref(false)
const modalKirimLIS: any = ref(false)
const modalCatatan: any = ref(false)
const dataSelect: any = ref({})
const d_JenisPelaksana: any = ref([])
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Adekuasi: any = ref([])
const d_optionNonNeoPlastik: any = ref([])
const showJenis: any = ref(false)
const input: any = ref([])
const d_JenisSpesimen: any = ref([])
const d_MetodePeriksa: any = ref([])
const d_JenisPemeriksaan: any = ref([])
const d_AsalSpesimen: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  registrasi: {},
  tglorder: new Date(),
  tanggal: new Date(),
  tglTerimaSample: new Date(),
  tglSelesaiSample: new Date(),
  produkCeklis: [],
  TOTAL: 0,
  DIBAYAR: 0,
  SISA: 0,
  DEPOSIT: 0,
  DISKON: 0,
  length: 0,
  pegawaiOrder: useUserSession().getUser().id,
  diskonKomponen: 0,
  namaKomponen: '',
  totalKomponen: 0,
  norder: ''
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const listButton: any = ref([
  {
    label: 'Catatan ',
    icon: 'pi pi-book',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis data Untuk Menambahkan Catatan')
        return
      }
      modalCatatan.value = true
    }
  },
  // {
  //   label: 'Kirim Ke LIS',
  //   icon: 'pi pi-send',
  //   command: () => {
  //     if (listChecked.value.length == 0) {
  //       H.alert('error', 'Ceklis Data yang akan Dikirim')
  //       return
  //     }

  //     for (let i = 0; i < listChecked.value.length; i++) {
  //       const element = listChecked.value[i];
  //       if (element.idbridging != null) {
  //         H.alert('error', `Produk ${element.namaproduk} dengan No. Lab ${element.noorder} sudah dikirim ke LIS !`)
  //         return
  //       }
  //     }

  //     modalKirimLIS.value = true
  //   }
  // },
  {
    label: 'Cetak Bukti Layanan ',
    icon: 'fas fa-print',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis data yang mau dicetak')
        return
      }

      let norec = listChecked.value.map((a: any) => a.norec).join("|");
      H.printBlade('laboratorium/layanan-lab-pertindakan?noregistrasi=' + item.registrasi.noregistrasi
        + '&norec=' + norec);
    }
  },
  // {
  //   label: 'Hasil Lab',
  //   icon: 'fas fa-plus',
  //   command: () => {
  //     hasilLabLIS(item.norec);
  //   }
  // },
  {
    label: 'Hasil Lab Manual',
    icon: 'fas fa-plus',
    command: () => {
      hasilLab(item.norec);
    }
  },

])
const confirm = useConfirm();

const isDetail: any = ref(false)
const dataSourcefiltered: any = computed(() => {

  if (!filters.value && !filtersHide.value) {
    return dataSource.value
  }
  var filtered: any = [];
  for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    var filteredD = [];
    for (let z = 0; z < element.details.length; z++) {
      const element2 = element.details[z];
      if (filters.value) {
        if (element2.namaproduk.match(new RegExp(filters.value, 'i'))
          || element2.namaruangan.match(new RegExp(filters.value, 'i'))
          || element2.dokterpemeriksa.match(new RegExp(filters.value, 'i'))

        ) {
          filteredD.push(element2);
          filtered.push({
            tglpelayanan_group: element.tglpelayanan_group,
            details: filteredD
          })
          break
        }
      } else if (filtersHide.value) {
        if (element2.jenis.match(new RegExp(filtersHide.value, 'i'))
        ) {
          for (let xxx = 0; xxx < filtered.length; xxx++) {
            const elementxxx = filtered[xxx];
            if (elementxxx.tglpelayanan_group == element.tglpelayanan_group) {
              filtered.splice(xxx, 1)
            }
          }
          filteredD.push(element2);
          filtered.push({
            tglpelayanan_group: element.tglpelayanan_group,
            details: filteredD
          })
          // break
        }
      }

    }
  }
  return filtered;
})

const headerPasien = async (id: any) => {
  isLoadingPasien.value = true
  useApi()
    .get(`/dashboard/headerpasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`)
    .then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.KELAS_LAST = response.last_registrasi.objectkelasfk
      item.registrasi = response.last_registrasi
      item.noregistrasi = response.registrasi[0].noregistrasi
      item.namaruangan = response.last_registrasi.namaruangan
      item.tglmasuk = response.last_registrasi.tglmasuk
      item.tglpulang = response.last_registrasi.tglpulang
      isLoadingPasien.value = false
      fetchLayanan()
    })
}

const fetchLayanan = async () => {
  isLoadingBill.value = true
  dataSource.value = []
  await useApi().get(
    `/laboratorium/layanan-lab?norec_pd=${NOREC_PD}&apd=${NOREC_APD}`).then(async (response: any) => {
      // `/laboratorium/layanan-lab?norec_pd=${NOREC_PD}&noorder=${NOORDER}`).then(async (response: any) => {   //next fitur

      dataSource.value = response.detail
      item.norder = response.noorder
      item.TOTAL = response.total
      item.PENGEMBALIAN = response.pengembalian
      item.length = response.length
      LISTRUANGAN_APD.value = response.list_ruangan
      LISTRUANGAN_APD_G.value = groupRuang(response.list_ruangan)
      isLoadingBill.value = false

    })
  modalKirimLIS.value = false
}

const inputTindakan = (e: any) => {
  router.push({
    name: 'module-emr-tindakan',
    query: {
      norec_pasien_daftar: NOREC_PD,
      nocmfk: pasien.value.nocmfk,
      norec_apd: NOREC_APD,
      // noorder:NOORDER  //next fitur
    },
  })
}

const hapusItems = async (e: any) => {
  if (!e.norec_op) {
    H.alert('error', 'Tindakan tidak bisa di hapus, data tidak lengkap')
    return
  }
  await H.statusClosingPasien(pasien.value.noregistrasi);
  confirm.require({
    group: 'positionDialog',
    message: H.alertHapus(),
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'top',
    accept: () => {
      var objSave = {
        'data':
          [{
            'noorder': e.noorder,
            'norec_pp': e.norec,
            'norec_op': e.norec_op,
            'namaproduk': e.namaproduk,
            'namaruangan': e.namaruangan,
          }],
        'nocm': pasien.value.nocm,
        'namapasien': pasien.value.namapasien,
        // 'noregistrasi': pasien.value.noregistrasi,
        'noregistrasi': item.registrasi.noregistrasi,
        'norder': e.noorder
      }
      nextHapus(objSave)
    },
    reject: () => {
    }
  });

}

const nextHapus = async (objSave: any) => {
  isLoadingBill.value = true
  var itemsave = {
    "noorder": objSave.norder,
    "noregistrasi": item.registrasi.noregistrasi
  }
  useApi().post(`/laboratorium/hapus-tindakan-lab`, objSave).then((response: any) => {
    if (item.registrasi.objectruanganfk != 338) { // Lab Nuklir
      useApi().post('bridging/penunjang/edit-bridging-vans-lab', itemsave).then((e: any) => {
        H.alert('success', 'Success update data LIS')
        isLoadingBill.value = false
        console.log(objSave)
      }, (error) => {
        console.error(error)
      })
    }
    fetchLayanan()
  }).catch((e: any) => {
    isLoadingBill.value = false
  })
}
const detailPetugas = async (e: any) => {
  clearInputPetugas()
  dataSelect.value = {}
  dataSelect.value = e
  await loadPetugas(e)
  await useApi().get(
    `tindakan/list-jenis-petugas`).then((response: any) => {
      d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
    })

  modalPetugas.value = true

}
const loadPetugas = async (e: any) => {
  isLoadingPop.value = true
  dataSourcePetugas.value = []
  await useApi().get(
    `/laboratorium/petugas-lab?norec=${e.norec}`).then((response: any) => {
      isLoadingPop.value = false
      dataSourcePetugas.value = response
    }).catch((e: any) => {
      isLoadingPop.value = false
    })
}
const batalLis = async (e: any) => {

  if (e.length == 0 || e == null) {
    H.alert('warning', 'Minimal ceklis 1 tindakan !')
    return
  }
  if (e.length > 1) {
    H.alert('warning', 'Ceklis 1 tindakan Yang ingin di hapus !')
    return
  }

  confirm.require({
    group: 'positionDialog',
    message: 'Apakah anda serius menghapus data order Ke LIS ?, Ini akan menghapus semua parameter yang di order dan menghapus semua parameter yang ada di LIS !',
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'top',
    accept: () => {
      batalLISReal(e[0].noorder)
    },
    reject: () => {
    }
  });

}

const batalLISReal = (e: any) => {
  useApi().post(`bridging/penunjang/delete-bridging-lab?noorder=${e}`).then((res: any) => {
    if (res != null) {
      H.alert('success', 'Data LIS ter update')
      disablekan.value = true
      useApi().post(`/laboratorium/hapus-tindakan-lab-all?noorder=${e}`).then((respo: any) => {
        fetchLayanan()
      }).catch((e: any) => {
        console.error(e)
      })
    }
  }).catch((e: any) => {
    console.error(e)
  })

}

const savePetugas = () => {
  let json = {
    'norec': item.norec_PPP ? item.norec_PPP : '',
    'objectjenispetugaspefk': item.jenisPelaksana,
    'objectpegawaifk': item.pegawai,
    'nomasukfk': dataSelect.value.norec_apd,
    // 'noregistrasi': pasien.value.noregistrasi,
    'noregistrasi': item.noregistrasi,
    'pelayananpasien': dataSelect.value.norec,
    'namaproduk': dataSelect.value.namaproduk,
    'namaruangan': dataSelect.value.namaruangan,
  }
  useApi().post(
    `/laboratorium/save-petugas-lab`, json).then((response: any) => {
      loadPetugas(dataSelect.value)
      fetchLayanan()
    })
}
const clearInputPetugas = () => {
  delete item.norec_PPP
  delete item.jenisPelaksana
  delete item.pegawai
}
function hapusPetugas(e: any) {
  useApi().post(
    `/laboratorium/delet-petugas-lab`, {
    'norec': e.norec,
    'objectpegawaifk': e.objectpegawaifk,
    'namaproduk': dataSelect.value.namaproduk,
    'noregistrasi': pasien.value.noregistrasi,
  }).then((response: any) => {
    loadPetugas(dataSelect.value)
  })
}
async function changeJenis(e: any) {
  d_Pegawai.value = []
  await useApi().get(
    '/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e
  ).then(async (response: any) => {
    d_Pegawai.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
  })
}
async function editPetugas(e: any) {
  item.norec_PPP = e.norec
  item.jenisPelaksana = e.objectjenispetugaspefk
  item.nomasukfk = e.nomasukfk
  item.pelayananpasien = e.pelayananpasien
  await changeJenis(e.objectjenispetugaspefk)
  item.pegawai = e.objectpegawaifk
}
function detailTanggal(e: any) {
  dataSelect.value = {}
  dataSelect.value = e
  item.tglPelayanan = new Date(e.tglpelayanan)
  modalTanggal.value = true

}

function filter() {

}
function cetakBuktiLayanan(norec_apd: any) {
  H.printBlade('kasir/billing/report/bukti-layanan-ruangan?noregistrasi=' + pasien.value.noregistrasi + '&norec_apd=' + norec_apd);

}

const listPegawai = async () => {
  await useApi().get(
    `/dashboard/list-lab`).then((response: any) => {
      d_Dokter.value = response.namalengkap.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
    })
}

const linkHasil: any = ref('')

const hasilLabCultture = async (e: any) => {
  console.log('e data', e);
  H.printBlade('laboratorium/cetakan-hasil-culture-new?noregistrasi=' + e.noregistrasi + '&norec_apd=' + e.norec_apd + '&noorder=' + e.noorder + '&norec_so=' + e.norec_so);
}
const HasilLabPA = async (e: any) => {
  H.printBlade(`laboratorium/get-hasil-pa-bridging?noorder=${e.noorder}`);

  // useApi().get(
  //   `/laboratorium/get-hasil-pa-bridging?noorder=${e.noorder}`).then(async (response: any) => {
  // //     console.log(response.data[0])
  // //     if (response.data.length == 0) {
  // //       H.alert('warning', 'Hasil belum ada')
  // //       return
  // //     } else {
  // //       console.log('masuk else')
  // //       // linkHasil.value = response.data[0].pdf_url;
  // //       // console.log("ASD", $('.madepcenah'))
  // //       // $(function() {
  // //       // })
  //        console.log(response)
  // window.open(http + ':' +response,'_blank')
  // //       // H.printBladeLabPA(response.data[0].pdf_url)
  // //     }
  //   })


  // item.norec = e.norec
  // item.namaproduk = e.namaproduk
  // item.pelayananpasienfk = e.norec
  // item.noregistrasifk = e.norec_apd
  // item.keteranganklinik = e.keteranganlainnya
  // useApi().get(
  //   `/laboratorium/get-hasil-pa?norec_pp=${e.norec}`).then((response: any) => {
  //     if (response != null) {
  //       item.nomorsediaan = response.nomorpa
  //       item.namaproduk = e.namaproduk
  //       item.dokterpengirimfk = response.dokterpengirimfk
  //       item.pegawaifk = response.pegawaifk
  //       item.jaringanasal = response.jaringanasal
  //       item.pelayananpasienfk = response.norec
  //       item.noregistrasifk = response.norec_apd
  //       item.diagnosaklinik = response.diagnosaklinik
  //       item.anjuran = response.anjuran
  //       item.kategori = response.kategori
  //       item.keteranganklinik = response.keteranganklinik
  //       item.diagnosapb = response.diagnosapb
  //       item.keteranganpb = response.keteranganpb
  //       item.topografi = response.topografi
  //       item.morfologi=response.morfologi
  //       item.jenis = response.jenis
  //       item.kesimpulan = response.kesimpulan
  //       item.makroskopik = response.makroskopik
  //       item.mikroskopik = response.mikroskopik
  //       item.norecHasil = response.pelayananpasienfk
  //       item.norecPa = response.norec
  //       item.nomorpa = response.nomorpa
  //     } else {
  //       setTemplate();
  //     }
  //   })
  // listPegawai()
  // modalHasilLabPA.value = true
}
const HasilPapSmear = async (e: any) => {
  item.norec = e.norec
  item.namaproduk = e.namaproduk
  item.pelayananpasienfk = e.norec
  item.noregistrasifk = e.norec_apd
  item.keteranganklinik = e.keteranganlainnya
  d_Adekuasi.value = adekuasiPemeriksaan.value.map(element => {
    return { label: element.value.description, id: element.value.code }
  })
  d_optionNonNeoPlastik.value = optionNonNeoPlastik.value.map(element => {
    return { label: element.value.description, id: element.value.code }
  })

  useApi().get(
    `/laboratorium/get-hasil-pa?norec_pp=${e.norec}`).then((response: any) => {
      if (response != null) {
        item.nomorsediaan = response.nomorsediaan
        item.namaproduk = e.namaproduk
        item.dokterpengirimfk = response.dokterpengirimfk
        item.jaringanasal = response.jaringanasal
        item.pelayananpasienfk = response.norec
        item.noregistrasifk = response.norec_apd
        item.diagnosaklinik = response.diagnosaklinik
        item.anjuran = response.anjuran
        item.kategori = response.kategori
        item.keteranganklinik = response.keteranganklinik
        item.diagnosapb = response.diagnosapb
        item.keteranganpb = response.keteranganpb
        item.topografi = response.topografi
        item.jenis = response.jenis
        item.kesimpulan = response.kesimpulan
        item.makroskopik = response.makroskopik
        item.mikroskopik = response.mikroskopik
        item.norecHasil = response.pelayananpasienfk
      } else {
        setTemplate();
      }
    })
  modalHasilPapSmear.value = true
}

const setTemplate = () => {
  useApi().get(
    `/general/template-expertise`).then((response: any) => {

      let defaultselected = {}
      for (let i = 0; i < response.data.length; i++) {
        const element = response.data[i];
        if (element.default) {
          defaultselected = element;
        }
      }
      item.keterangan = defaultselected.template;
    });
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchMetodePeriksa = async () => {
  const response = await useApi().get(
    `/emr/dropdown/metodepemeriksanpcr_m?select=id,namametodeperiksa`)
  d_MetodePeriksa.value = response
}

const fetchJenisSpesimen = async () => {
  const response = await useApi().get(
    `/emr/dropdown/spesimenpcr_m?select=id,namajenisspesimen`)
  d_JenisSpesimen.value = response
}
const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Dokter.value = response
}


const saveHasilLabPA = async (e: any) => {
  if (!item.pegawaifk) { H.alert('warning', 'Dokter Pembaca harus di isi'); return }
  if (!item.dokterpengirimfk) { H.alert('warning', 'Dokter Pengirim harus di isi'); return }

  let json = {
    'norec': item.norecPa ?? '',
    'nomorsediaan': item.nomorsediaan,
    'tanggal': item.tanggal,
    'pegawaifk': item.pegawaifk.value ? item.pegawaifk.value : item.pegawaifk,
    'dokterpengirimfk': item.dokterpengirimfk.value ? item.dokterpengirimfk.value : item.dokterpengirimfk,
    'jenis': item.jenis ? item.jenis : null,
    'pelayananpasienfk': item.norec,
    'noregistrasifk': item.noregistrasifk,
    'diagnosaklinik': item.diagnosaklinik ? item.diagnosaklinik : null,
    'keteranganklinik': item.keteranganklinik ? item.keteranganklinik : null,
    'diagnosapb': item.diagnosapb ? item.diagnosapb : null,
    'keteranganpb': item.keteranganpb ? item.keteranganpb : null,
    'topografi': item.topografi ? item.topografi : null,
    'morfologi': item.morfologi ? item.morfologi : null,
    'makroskopik': item.makroskopik ? item.makroskopik : null,
    'mikroskopik': item.mikroskopik ? item.mikroskopik : null,
    'kesimpulan': item.kesimpulan ? item.kesimpulan : null,
    'anjuran': item.anjuran ? item.anjuran : null,
    'jaringanasal': item.jaringanasal ? item.jaringanasal : null,
    'nomorpa': item.nomorpa ? item.nomorpa : null
  }
  isLoadingTT.value = true
  await useApi().post(
    `/laboratorium/save-hasillab-pa`, json).then((response: any) => {
      isLoadingTT.value = false;
      item.norecHasil = response.pelayananpasienfk;
      clear()
      fetchLayanan()
    }, (error) => {
      isLoadingTT.value = false
      // console.log(error)
    })
}
const cetakHasilLap = async (e: any) => {
  H.printBlade(`laboratorium/cetak-ekspertise?pelayananpasienfk=${item.norecHasil}&type=laboratorium`);
}

const inputCatatan = async (e: any) => {
  modalCatatan.value = true
}

const saveCatatan = async (e: any) => {
  sendLIS()
}

function checkedAll(e: any) {
  modelCheck.value = []
  listChecked.value = []
  if (e) {
    dataSource.value.forEach((e: any) => {
      e.details.forEach((f: any) => {
        listChecked.value.push(f)
        modelCheck.value[f.norec] = true
      });
    });
  }
}
function checkedItems() {
  let objectK = Object.keys(modelCheck.value)

  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (modelCheck.value[element] == true) {
      for (var i = 0; i < dataSource.value.length; i++) {
        const element2 = dataSource.value[i];
        for (let xx = 0; xx < element2.details.length; xx++) {
          const element3 = element2.details[xx];
          if (element3.norec == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element4 = listChecked.value[z];
              if (element4.norec == element3.norec) {
                listChecked.value.splice(z, 1)
              }
            }
            listChecked.value.push(element3)
          }
        }

      }
    } else {
      for (var i = 0; i < dataSource.value.length; i++) {
        const element2 = dataSource.value[i];
        for (let xx = 0; xx < element2.details.length; xx++) {
          const element3 = element2.details[xx];
          if (element3.norec == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element4 = listChecked.value[z];
              if (element4.norec == element3.norec) {
                listChecked.value.splice(z, 1)
              }
            }
          }
        }
      }
    }
  }
}

const hasilLab = (norec: any) => {
  let data: any = []
  listChecked.value.forEach((element: any) => {
    data.push(element.norec)
  });

  if (listChecked.value.length == 0) {
    H.alert('error', 'Ceklis Data untuk Melihat Hasil Lab')
    return
  }

  router.push({
    name: 'module-laboratorium-hasil-lab',
    query: {
      nocmfk: pasien.value.nocmfk,
      norec_apd: NOREC_APD,
      norec_pd: NOREC_PD,
      norec_pp: data,
    },
  })
}
const hasilLabLIS = (norec: any) => {
  let data: any = []
  let dataSO: any = []
  listChecked.value.forEach((element: any) => {
    data.push(element.noorder)
    dataSO.push(element.norec_so)
  });

  if (listChecked.value.length == 0) {
    H.alert('error', 'Ceklis Data untuk Melihat Hasil Lab')
    return
  }

  router.push({
    name: 'module-laboratorium-hasil-lab-bridging',
    query: {
      nocmfk: pasien.value.nocmfk,
      norec_apd: NOREC_APD,
      norec_pd: NOREC_PD,
      noorder: data,
      norec_so: dataSO
    },
  })
}


function groupRuang(result: any) {
  let sama = false
  let arrGroup: any = [];
  for (let i = 0; i < result.length; i++) {
    sama = false
    for (let x = 0; x < arrGroup.length; x++) {
      if (arrGroup[x].namadepartemen == result[i].namadepartemen) {
        sama = true;
      }
    }
    if (sama == false) {
      let data = {
        'namadepartemen': result[i].namadepartemen,
        'details': [],
      }
      arrGroup.push(data)
    }
  }
  for (let x = 0; x < arrGroup.length; x++) {
    const element = arrGroup[x];
    for (let y = 0; y < result.length; y++) {
      const element2 = result[y];
      if (element.namadepartemen == element2.namadepartemen) {
        element.details.push(element2)
      }
    }

  }
  return arrGroup
}
const clear = () => {

  item.norec = ''
  item.nomorsediaan = ''
  item.pegawaifk = ''
  item.dokterpengirimfk = ''
  item.pelayananpasienfk = ''
  item.noregistrasifk = ''
  item.diagnosaklinik = ''
  item.keteranganklinik = ''
  item.diagnosapb = ''
  item.keteranganpb = ''
  item.topografi = ''
  item.morfologi = ''
  item.makroskopik = ''
  item.mikroskopik = ''
  item.kesimpulan = ''
  item.anjuran = ''
  item.jaringanasal = ''
  item.norecHasil = ""

  modalHasilLabPA.value = false
  modalHasilPapSmear.value = false
  modalCatatan.value = false
}
const sendLIS = () => {

  let objSaveOrder: any = {}
  let objOrder: any = []
  let objBridg: any = []

  if (!item.objectpegawaifk) {
    H.alert('error', 'Pilih Dokter Pengirim terlebih dahulu !')
    return
  }

  for (let x = 0; x < listChecked.value.length; x++) {
    const element = listChecked.value[x];
    if (element.idbridging != null) {
      H.alert('warning', 'Data sudah pernah dikirim ke LIS')
      return
    }
  }
  for (let x = 0; x < listChecked.value.length; x++) {
    const element = listChecked.value[x];
    if (element.noorder == null) {
      objOrder.push({
        no: x + 1,
        produkfk: element.produkfk,
        namaproduk: element.namaproduk,
        qtyproduk: 1,
        objectkelasfk: item.KELAS_LAST,
        nourut: null,
        norec_pp: element.norec,
      })
    } else {
      objBridg.push({ produkfk: element.produkfk, namaproduk: element.namaproduk, })
    }
    objSaveOrder = {
      status: "bridinglangsung",
      noregistrasi: item.noregistrasi,
      tanggal: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
      tgloperasi: null,
      norec_so: '',
      norec_apd: element.norec_apd,
      norec_pd: item.registrasi.norec_pd,
      qtyproduk: objOrder.length,
      objectruanganfk: item.registrasi.objectruanganlastfk,
      pegawaiorderfk: element.pegawaifk,
      namalengkap: element.namalengkap,
      objectruangantujuanfk: item.registrasi.objectruanganfk,
      departemenfk: element.objectdepartemenfk,
      keterangan: null,
      noord: element.noorder,
      iscito: false,
      details: objOrder,
    }
  }
  if (objOrder.length) {
    isLoadingPop.value = true
    useApi().post(
      `/laboratorium/simpan-order`, objSaveOrder).then((response: any) => {

        let itemsave = {
          "bridging": objOrder,
          "noorder": response.data.noorder,
          "objectkelasfk": item.KELAS_LAST,
          "objectruangantujuanfk": item.registrasi.objectruanganfk,
          "objectpegawaiorderfk": objSaveOrder.pegawaifk,
          "iddokterverif": item.objectpegawaifk.value,
          "namadokterverif": item.objectpegawaifk.label,
          "noregistrasi": item.noregistrasi,
          "catatan_klinis": item.keterangan ? item.keterangan : null,
        }
        useApi().post('bridging/penunjang/save-bridging-vans-lab', itemsave).then((e: any) => {
          isLoadingPop.value = false
          fetchLayanan()
        }, (error) => {
          isLoadingPop.value = false
        })
      }, (error) => {
        isLoadingPop.value = false
      })
  }
  if (objBridg.length) {
    isLoadingPop.value = true
    let itemsave = {
      "bridging": objBridg,
      "noorder": objSaveOrder.noord,
      "objectkelasfk": item.KELAS_LAST,
      "objectruangantujuanfk": item.registrasi.objectruanganfk,
      "objectpegawaiorderfk": objSaveOrder.pegawaifk,
      "iddokterverif": item.objectpegawaifk.value,
      "namadokterverif": item.objectpegawaifk.label,
      "noregistrasi": item.noregistrasi,
      "catatan_klinis": item.keterangan ? item.keterangan : null,
    }

    useApi().post('bridging/penunjang/save-bridging-vans-lab', itemsave).then((e: any) => {
      isLoadingPop.value = false
      fetchLayanan()
    }, (error) => {
      isLoadingPop.value = false
    })
  }

}

const showModalInputPCR = async (e: any) => {
  await fetchMetodePeriksa();
  await fetchJenisSpesimen();
  if (e.namaproduk.toLowerCase().includes('pcr')) {
    item.title = "PCR";
  } else {
    item.title = "ANTIGEN";
  }
  item.norec = ''
  item.namaproduk = e.namaproduk
  item.pelayananpasienfk = e.norec
  item.noregistrasifk = e.norec_apd
  item.keteranganklinik = e.keteranganlainnya
  useApi().get(
    `/laboratorium/get-hasil-pcr?norec_pp=${e.norec}`).then((response: any) => {
      if (response != null) {
        item.norec = response.norec,
          item.nopassport = response.nopassport,
          item.nopemerikasaan = response.nopemerikasaan,
          item.ruanganfk = { label: response.namaruangan, value: response.idruangan },
          item.metodeperiksafk = { label: response.namametodeperiksa, value: response.idmetodeperiksa },
          item.jenisspesimenfk = { label: response.namajenisspesimen, value: response.idjenisspesimen },
          item.tglterimasampel = response.tglterimasampel,
          item.tglselesaisampel = response.tglselesaisampel,
          item.kodesampel = response.kodesampel,
          item.sample = response.sample,
          item.hasil = response.hasil,
          item.keterangan = response.keterangan,
          item.keperluaan = response.keperluaan,
          item.keadaan = response.keadaan,
          item.keluarkota = response.keluarkota,
          item.kotanegara = response.kotanegara,
          item.noregistrasi = response.noregistrasi,
          item.pelayananpasienfk = response.pelayananpasienfk,
          item.pemeriksa = { label: response.pegawaipemeriksa, value: response.idpegawaipemeriksa },
          item.setujuh = { label: response.namapetuggasapprovalfk, value: response.idpetuggasapprovalfk },
          item.petugasverifikatorfk = { label: response.pegawaiverifikator, value: response.idpegawaiverifikator }
      } else {

      }
    })
  modalInputPCR.value = true
}
const cetakHasilPCR = () => {
  H.printBlade(`report/cetak-hasil-pcr?norec=${item.norec}`);
}
const savePCR = async () => {
  if (!item.nopemerikasaan) {
    H.alert('warning', 'Nomer Pemeriksaan Harus Disis !');
    return;
  }
  let json = {
    'norec': item.norec ?? '',
    'nopassport': item.noPassport,
    'nopemerikasaan': item.nopemerikasaan,
    'objectruanganfk': item.ruanganfk ? item.ruanganfk.value : null,
    'metodeperiksafk': item.metodeperiksafk ? item.metodeperiksafk.value : null,
    'jenisspesimenfk': item.jenisspesimenfk ? item.jenisspesimenfk.value : null,
    'tglterimasampel': H.formatDate(item.tglTerimaSample, 'YYYY-MM-DD HH:mm:ss'),
    'tglselesaisampel': H.formatDate(item.tglSelesaiSample, 'YYYY-MM-DD HH:mm:ss'),
    'kodesampel': item.kodesampel,
    'sample': item.sample,
    'hasil': item.hasil ?? false,
    'keterangan': item.keterangan,
    'keperluaan': item.keperluaan,
    'keadaan': item.keadaan,
    'keluarkota': item.keluarkota,
    'kotanegara': item.kotanegara,
    'noregistrasi': item.noregistrasi,
    'pelayananpasienfk': item.pelayananpasienfk,
    'petuggasapprovalfk': item.setujuh ? item.setujuh.value : null,
    'petugasfk': item.pemeriksa ? item.pemeriksa.value : null,
    'petugasverifikatorfk': item.petugasverifikatorfk ? item.petugasverifikatorfk.value : null,
  }
  isLoadingTT.value = true
  await useApi().post(
    `/laboratorium/save-hasillab-pcr`, json).then((response: any) => {
      isLoadingTT.value = false;
      item.norec = response.norec;
      clearPcr()
      fetchLayanan()
    }, (error) => {
      isLoadingTT.value = false
    })
  modalInputPCR.value = false
}
const clearPcr = () => {
  item.norec = "",
    item.nopassport = "",
    item.nopemerikasaan = "",
    item.objectruanganfk = "",
    item.metodeperiksafk = "",
    item.jenisspesimenfk = "",
    item.tglterimasampel = "",
    item.tglselesaisampel = "",
    item.kodesampel = "",
    item.sample = "",
    item.hasil = "",
    item.keterangan = "",
    item.keperluaan = "",
    item.keadaan = "",
    item.keluarkota = "",
    item.kotanegara = "",
    item.noregistrasi = "",
    item.pelayananpasienfk = "",
    item.petugasfk = "",
    item.petuggasapprovalfk = ""
}
const showModalInputMikro = async (e: any) => {
  clearMikro()
  d_JenisPemeriksaan.value = jenisPemeriksaan.value.map(element => {
    return { label: element.label, value: element.value }
  })
  d_AsalSpesimen.value = asalSpesimen.value.map(element => {
    return { label: element.label, value: element.value }
  })
  // console.log(e);
  // console.log(pasien.value);

  item.nocmfk = pasien.value.nocm
  item.norec_pd = item.NOREC_PD
  item.norec_apd = item.NOREC_APD

  item.nocm = pasien.value.nocm
  item.namapasien = pasien.value.namapasien
  item.noreg = item.registrasi.noregistrasi

  item.norecMikro = ''
  item.pelayananpasienfk = e.norec
  item.norec = e.norec_apd
  useApi().get(
    `/laboratorium/get-hasil-mikro?norec_pp=${e.norec}`).then((response: any) => {
      if (response != null) {
        item.norecMikro = response.norec
        item.kodespesimen = response.kodespesimen
        item.spesimenke = response.spesimenke
        item.tglterimasampel = response.tglterimasampel
        item.tglkeluarhasil = response.tglkeluarhasil
        item.jenisspesimen = { value: response.jenisspesimen, label: response.namajenisspesimen }
        item.asalspesimen = response.asalspesimen
        item.hasilspesimen = response.hasilspesimen
        item.tglterimaspesimen = response.tglterimaspesimen
        item.tgldikerjakanspesimen = response.tgldikerjakanspesimen
        item.dokterpemeriksa = { value: response.dokterpemeriksafk, label: response.namalengkap }
        item.pmn = response.pmn
        item.gpc = response.gpc
        item.gpr = response.gpr
        item.gndc = response.gndc
        item.sec = response.sec
        item.gnr = response.gnr
        item.gncb = response.gncb
        item.ycphh = response.ycphh
        item.note = response.note
        item.pemeriksaan = response.pemeriksaan
        item.pemeriksaanpenunjang = response.pemeriksaanpenunjang
        item.bas = response.bas
        item.eos = response.eos
        item.bat = response.bat
        item.seg = response.seg
        item.limf = response.limf
        item.sun = response.sun
        item.antibiotik = response.antibiotik
        item.tglkultur = response.tglkultur
        item.pemeriksakultur = { value: response.pemeriksakultur, label: response.namapemeriksakultur }
        item.observasikultur = response.observasikultur
        item.hasilakhirkultur = response.hasilakhirkultur
        item.hasilujikepekaan = response.hasilujikepekaan
        item.nocmfk = response.nocmfk
        item.norec_pd = response.norec_pd
        item.norec_apd = response.norec_apd
        item.pelayananpasienfk = response.pelayananpasienfk
      }
    })
  modalInputMikro.value = true
}
const cetakHasilMikro = () => {
  H.printBlade(`report/cetak-hasil-mikro?pdf=true&norec=${item.norecMikro}`);
}
const saveMikro = async () => {
  if (!item.kodespesimen) {
    H.alert('warning', 'Kode Spesimen Harus Diisi !');
    return;
  }
  // console.log(item);

  let json = {
    'norec': item.norecMikro ?? '',
    'kodespesimen': item.kodespesimen,
    'tglterimaspesimen': H.formatDate(item.tglterimaspesimen, 'YYYY-MM-DD HH:mm'),
    'tgldikerjakanspesimen': H.formatDate(item.tgldikerjakanspesimen, 'YYYY-MM-DD'),
    'tglkeluarhasil': H.formatDate(item.tglkeluarhasil, 'YYYY-MM-DD'),
    'jenisspesimen': item.jenisspesimen.value,
    'asalspesimen': item.asalspesimen,
    'namajenisspesimen': item.jenisspesimen.label,
    'namaasalspesimen': item.asalspesimen.label,
    'pmn': item.pmn,
    'gpc': item.gpc,
    'gpr': item.gpr,
    'gndc': item.gndc,
    'sec': item.sec,
    'gnr': item.gnr,
    'gncb': item.gncb,
    'ycphh': item.ycphh,
    'note': item.note,
    'pemeriksaan': item.pemeriksaan,
    'pemeriksaanpenunjang': item.pemeriksaanpenunjang,
    'bas': item.bas,
    'eos': item.eos,
    'bat': item.bat,
    'seg': item.seg,
    'limf': item.limf,
    'sun': item.sun,
    'antibiotik': item.antibiotik,
    'tglkultur': H.formatDate(item.tglkultur, 'YYYY-MM-DD HH:mm'),
    'pemeriksakultur': item.pemeriksakultur.value,
    'namapemeriksakultur': item.pemeriksakultur.label,
    'observasikultur': item.observasikultur,
    'hasilakhirkultur': item.hasilakhirkultur,
    'hasilujikepekaan': item.hasilujikepekaan,
    'nocmfk': item.nocmfk,
    'norec_pd': item.norec_pd,
    'norec_apd': item.norec_apd,
    'pelayananpasienfk': item.pelayananpasienfk,
    'spesimenke': item.spesimenke,
    'hasilspesimen': item.hasilspesimen,
    'dokterpemeriksafk': item.dokterpemeriksa.value,
  }
  // console.log(json);

  isLoadingTT.value = true
  await useApi().post(
    `/laboratorium/save-hasillab-mikro`, json).then((response: any) => {
      isLoadingTT.value = false;
      item.norec = response.norec;
      clearMikro()
      fetchLayanan()
    }, (error) => {
      isLoadingTT.value = false
    })
  modalInputMikro.value = false
}
const clearMikro = () => {
  item.norecMikro = ""
  item.kodespesimen = ""
  item.tglterimasampel = ""
  item.tglkeluarhasil = ""
  item.jenisspesimen = ""
  item.asalspesimen = ""
  item.pmn = ""
  item.gpc = ""
  item.gpr = ""
  item.gndc = ""
  item.sec = ""
  item.gnr = ""
  item.gncb = ""
  item.ycphh = ""
  item.note = ""
  item.pemeriksaan = ""
  item.pemeriksaanpenunjang = ""
  item.bas = ""
  item.eos = ""
  item.bat = ""
  item.seg = ""
  item.limf = ""
  item.sun = ""
  item.antibiotik = ""
  item.tglkultur = ""
  item.pemeriksakultur = ""
  item.namapemeriksakultur = ""
  item.observasikultur = ""
  item.hasilakhirkultur = ""
  item.hasilujikepekaan = ""
  item.nocmfk = ""
  item.norec_pd = ""
  item.norec_apd = ""
  item.pelayananpasienfk = ""
}


watch(
  () => item.persenDiskon,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue > 100) {
        item.persenDiskon = "";
      }
      item.diskonKomponen = ((parseFloat(item.hargasatuankom)) * newValue) / 100
    }
  }
)
watch(
  () => item.jenis,
  (newValue, oldValue) => {
    if (newValue == 'pa') {
      showJenis.value = true
    } else {
      showJenis.value = false
    }
  }
)
const cetakLabel = async (e: any) => {
  console.log(JSON.stringify(e));
  let so_norec = e.NOREC_PD;
  H.printBlade(`report/cetak-label-tindakan?norec=${so_norec}&type=laboratorium`);
}
const lihatDok = (e: any) => {
  H.openFile('berkas_lab/' + e.norec_so + '/' + e.namafile);
}
headerPasien(ID_PASIEN)
listPegawai()

</script>
<style lang="scss">
@import '/@src/scss/main';
@import '/@src/scss/module/kasir/billing';

.field>label {
  color: hsl(0deg, 0%, 4%) !important;
}

.hr-dashboard {
  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 20px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .left,
    .right {
      width: 30%;
    }

    .center {
      display: flex;
      flex-direction: column;
      width: 40%;
      padding-right: 10px;
      margin-right: 30px;
      border-right: 1px solid var(--primary-light-10);

      .block-text {
        margin-bottom: 16px;
      }

      .candidates {
        margin-top: auto;

        >.v-avatar {
          margin-right: 10px;
        }

        button {
          height: 40px;
          width: 40px;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          border-radius: 10px;
          background: var(--white);
          color: var(--light-text);
          border: none;
          cursor: pointer;
          transition: all 0.3s; // transition-all test

          svg {
            height: 18px;
            width: 18px;
          }
        }
      }
    }

    .left {
      display: flex;
      justify-content: center;
      align-items: center;

      .current-user {
        .v-avatar {
          margin-bottom: 1rem;
        }

        h3 {
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--white);
          line-height: 1.2;
        }
      }
    }

    .right {
      display: flex;
      flex-direction: column;

      .button {
        margin-top: auto;
      }
    }

    .block-heading {
      font-family: var(--font-alt);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--white);
      margin-bottom: 4px;
    }

    .block-text {
      font-family: var(--font);
      font-size: 0.9rem;
      color: var(--white);
      margin-bottom: 16px;
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
  }

  .feed-settings {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    .button {
      font-size: 0.8rem;
      border-radius: 8px;
      margin-right: 4px;

      &.is-selected {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
        box-shadow: var(--primary-box-shadow);
      }
    }
  }

  .side-text {
    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 8px;
    }

    p {
      font-size: 0.95rem;
      margin-bottom: 8px;
    }

    .action-link {
      font-size: 0.9rem;
    }
  }

  .recent-rookies {
    .recent-rookies-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-size: 2rem;
        font-weight: 600;
        color: var(--dark-text);
      }
    }

    .user-grid {
      &.user-grid-v4 {
        .grid-item {
          @include vuero-l-card;
        }
      }
    }
  }
}
</style>
