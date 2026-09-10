<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div class="personal-dashboard personal-dashboard-v2">
    <div class="columns is-multiline">
      <div class="column is-12" v-if="pasien.namapasien == undefined">
        <VCard>
          <PlaceloadHeader />
        </VCard>
      </div>
      <div class="column is-12 mb-0" v-else-if="pasien.namapasien != undefined">
        <img src="/images/simrs/billing-ico.png" alt="" class="mix">

        <div class="dashboard-header">
          <VAvatar picture="/images/avatars/svg/vuero-1.svg" size="xl" />
          <div class="user-meta is-dark-bordered-12">
            <h3 class="title is-4 is-narrow is-bold">{{ pasien.namapasien }}</h3>
            <p class="light-text"> <strong>{{ pasien.nocm }}</strong></p>
            <p class="light-text"> {{ pasien.nohp }}</p>
            <p class="light-text">{{ pasien.tgllahir }} ({{ pasien.umur }})</p>
            <VTag color="info" class="mr-2" :label="'TITIP ' + pasien.namakelas" v-if="pasien.iskelastitip"/>
            <VTag color="success" :label="'NAIK ' + pasien.kelasrawat" v-if="pasien.isnaikkelas"/>
          </div>

          <div class="user-action">
            <div class="cta h-hidden-tablet-p">
              <div class="columns is-multiline mb-2">
                <div class="column is-6">
                  <VField>
                    <VLabelText style="color: var(--white) !important">Ruangan</VLabelText>
                    <VLabel style="color: var(--white) !important">{{ pasien.namaruangan }} </VLabel>
                  </VField>
                </div>

                <div class="column is-6">
                  <VField>
                    <VLabelText style="color: var(--white) !important">Tipe Pembiayaan</VLabelText>
                    <VLabel style="color: var(--white) !important">{{ pasien.kelompokpasien + ' / ' + pasien.namarekanan }} </VLabel>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline" v-if="isDetail == true">
                <div class="column is-6">
                  <VField>
                    <VLabelText style="color: var(--white) !important">No Registrasi</VLabelText>
                    <VLabel style="color: var(--white) !important">{{ pasien.noregistrasi }}</VLabel>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabelText style="color: var(--white) !important">Kelas</VLabelText>
                    <VLabel style="color: var(--white) !important">{{ pasien.namakelas }} </VLabel>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabelText style="color: var(--white) !important">Tgl Masuk</VLabelText>
                    <VLabel style="color: var(--white) !important">{{ H.formatDateIndoSimple(pasien.tglregistrasi) }} </VLabel>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabelText style="color: var(--white) !important">Tgl Keluar</VLabelText>
                    <VLabel style="color: var(--white) !important">{{ pasien.tglpulang != null ? H.formatDateIndoSimple(pasien.tglpulang) : '' }} </VLabel>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <VLabelText style="color: var(--white) !important">Alamat</VLabelText>
                    <VLabel style="color: var(--white) !important">{{ pasien.alamatlengkap }} </VLabel>
                  </VField>
                </div>
              </div>
              <div class="media-flex inverted-text">
                <i aria-hidden="true" class="lnil lnil-crown-alt-1"></i>
              </div>
              <div class="collapse-icon is-clickable" @click="isDetail = true" v-if="!isDetail">
                <VIcon icon="feather:chevron-down" style="margin-top: 4px;"
                  v-tooltip.danger.bubble="'Tampilkan detail info Pasien'" />
              </div>
              <div class="collapse-icon  is-clickable " open @click="isDetail = false" v-else>
                <VIcon icon="feather:chevron-up" style="margin-top: 4px;" v-tooltip.danger.bubble="'Hide info Pasien'" />
              </div>
              <!-- <a class="link inverted-text">Learn More</a> -->
            </div>
            <!-- <h3 class="title is-2 is-narrow">3</h3>
            <p class="light-text">Tasks are pending review</p>
            <a class="action-link" tabindex="0">View Tasks</a> -->
          </div>

        </div>
      </div>
      <div class="column is-12 mt-0">
        <div class="dashboard-card has-margin-bottom">
          <div class="card-head " style="align-content:space-between">
            <h3 class="dark-inverted">TAGIHAN #{{ item.length }} - (Rp. {{ H.formatRp(item.TOTAL, '') }}) </h3>
            <h3 class="dark-inverted" v-if="pasien.kelompokpasien && pasien.kelompokpasien.indexOf('BPJS') > -1">PLAFON
              BPJS #(Rp. {{ H.formatRp(item.tarif_inacbg ? item.tarif_inacbg : 0, '') }}) </h3>
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
              <div class="column is-2 pt-1">
                <VControl>
                  <VSwitchBlock v-model="item.isResep" label="Resep" color="danger" />
                </VControl>
              </div>
              <div class="column is-2 pt-1">
                <VControl>
                  <VSwitchBlock v-model="item.isTindakan" label="Tindakan" color="info" />
                </VControl>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline is-pulled-right">
                  <div class="column pr-0 m-0">
                    <VButton class="ml-2 is-pulled-right" icon="feather:plus-circle" raised bold @click="tambahTagihan()" style="display: none !important"
                      :color="PASIEN_AKTIF == true ? 'success' : 'primary-grey'"
                      v-bind:disabled="PASIEN_AKTIF == false || pasien.noregistrasi == undefined ? true : false"
                      :loading="isLoadingBill">
                      Tambah Tagihan
                    </VButton>
                  </div>
                  <div class="column pr-0 m-0" style="display: none !important">
                    <VButton color="purple" class="ml-2 is-pulled-right" icon="feather:printer" raised bold
                      @click="cetakBilling()" v-bind:disabled="pasien.noregistrasi == undefined ? true : false">
                      Print
                    </VButton>
                  </div>
                  <div class="column pr-0 m-0">
                    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                      Kembali
                    </VButton>
                  </div>
                  
                  <div class="column m-0">
                    <VIconButton color="grey" class="ml-2" icon="pi pi-ellipsis-v" raised bold @click="toggleOP($event)"
                      v-bind:disabled="pasien.noregistrasi == undefined ? true : false">

                    </VIconButton>
                    <!-- <SpeedDial :model="listButton" direction="right" :transitionDelay="80" showIcon="pi pi-ellipsis-v"
                      hideIcon="pi pi-times" buttonClassName="p-button-outlined" className="speeddial-right"
                      class="secondary" type="semi-circle" :tooltipOptions="{ position: 'right' }" /> -->
                    <OverlayPanel ref="op" appendTo="body" showCloseIcon style="width:400px">
                      <div class="columns is-multiline ">
                        <div class="column is-6">
                          <VButton type="button" icon="pi pi-trash" class="w-100" light circle outlined color="danger"
                            raised @click="confirmPosition('top')">
                            Hapus
                          </VButton>
                        </div>
                        <div class="column is-6">
                          <VButton type="button" icon="fas fa-print" class="w-100" light circle outlined color="info"
                            raised @click="rekapBil()">
                            Rekap Billing
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min" style="display: none !important">
                          <VButton type="button" icon="fas fa-print" class="w-100" light circle outlined color="info"
                            raised @click="buktiLayan()">
                            Bukti Layanan
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min" style="display: none !important">
                          <VButton type="button" icon="fas fa-print" class="w-100" color="info" circle outlined raised
                            @click="buktiLayanTindakan()">
                            Bukti Layanan Tindakan
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min" style="display: none !important">
                          <VButton type="button" icon="fas fa-print" class="w-100" color="info" circle outlined raised
                            @click="buktiLayanJasa()">
                            Bukti Layanan Jasa
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min">
                          <VButton type="button" icon="fas fa-print" class="w-100" color="info" circle outlined raised
                            @click="buktiLayanBPJS()">
                            Bukti Layanan BPJS
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min">
                          <VButton type="button" icon="fas fa-print" class="w-100" color="info" circle outlined raised
                            @click="buktiLayanVA()">
                            Bukti Layanan VA
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min">
                          <VButton type="button" icon="fas fa-print" class="w-100" color="info" circle outlined raised
                            @click="buktiLayanCaraBayar()">
                            Bukti Layanan Cara Bayar
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min">
                          <VButton type="button" icon="fas fa-print" class="w-100" color="info" circle outlined raised
                            @click="buktiLayanWNARajal()">
                            Bukti Layanan WNA Rajal
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min">
                          <VButton type="button" icon="fas fa-print" class="w-100" color="info" circle outlined raised
                            @click="buktiLayanWNARanap()">
                            Bukti Layanan WNA Ranap
                          </VButton>
                        </div>
                        <div class="column is-6 mt-3-min">
                          <VButton type="button" icon="fas fa-undo" class="w-100" color="warning" circle outlined raised
                            @click="konversiHarga()"> Konversi Harga
                          </VButton>
                        </div>
                        <!-- <div class="column is-6 mt-3-min">
                          <VButton circle outlined color="danger" icon="fas fa-user-lock" class="w-100"
                            raised bold @click="changeClosing(true)" v-if="isClosing == null" :loading="klikLoad">
                            Closing
                          </VButton>
                          <VButton outlined color="success"  class="w-100"
                            icon="fas fa-unlock-alt" raised bold @click="changeClosing(null)" v-else :loading="klikLoad">
                            Batal Closing
                          </VButton>
                        </div> -->

                      </div>
                    </OverlayPanel>
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
                            <VCheckbox v-if="!(item.isTindakan || item.isResep)" v-model="item.checkAll" label="#"
                              color="info" @change="checkedAll(item.checkAll)" :value="item.checkAll" />
                          </VControl>
                          <VControl raw subcontrol style="margin-top:-10px">
                            <VCheckbox v-if="item.isTindakan || item.isResep" v-model="item.checkedAllJenis" label="#"
                              color="info" @change="checkedAllJenis(item.checkedAllJenis)"
                              :value="item.checkedAllJenis" />
                          </VControl>
                        </th>
                        <th width="30%">URAIAN</th>
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
                      <tbody v-if="!isLoadingBill" v-for="(items, index)  in dataSourcefiltered" :key="index">
                        <tr>
                          <td colspan="5" class="koneng">
                            {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                          </td>
                        </tr>
                        <tr v-for="(itemsDet, index2)  in items.details" :key="index2">
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
                                <div class="title-ruangan" v-if="itemsDet.strukresepfk != null">{{ itemsDet.ruangan }}
                                </div>
                                <div class="title-ruangan" v-else>{{ itemsDet.namaruangan }}</div>
                                <div class="title-layan">
                                  <!-- <template v-if="itemsDet.keteranganlain == 'Tindakan'">
                                    {{ itemsDet.namaproduk }} - {{itemsDet.pemeriksa ? itemsDet.pemeriksa : (itemsDet.dokterpemeriksa ? :itemsDet.dokterpemeriksa : 'Pemeriksa belum di input')}}
                                  </template> -->
                                    {{ itemsDet.namaproduk }} - {{
                                    itemsDet.dokterpemeriksa ?
                                    itemsDet.dokterpemeriksa : 'Dokter belum di input'}}
                                  <!-- <template>
                                  </template> -->
                                  </div>
                                <div>
                                  <VTag :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                    :label="itemsDet.tglpelayanan" />
                                </div>
                                <div class="title-kelas">{{ itemsDet.namakelas }}</div>
                              </div>
                            </div>
                          </td>
                          <td class="center">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <div class="title-ruangan">Jasa : {{ H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                <div class="title-layan">{{ H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}</div>

                                <div class="title-kelas">Diskon : {{ H.formatRp(itemsDet.hargadiscount, 'Rp. ') }}</div>
                              </div>
                            </div>
                          </td>
                          <td class="center">{{ itemsDet.jumlah }}</td>
                          <td class="center">
                            <div class="columns is-multiline">
                              <div class="column is-12">

                                <div class="title-layan">{{ H.formatRp(itemsDet.total, 'Rp. ') }}</div>
                                <div class="title-kelas">
                                  <VTag v-if="itemsDet.keteranganlain != null && itemsDet.keteranganlain != 'Tindakan'"
                                    :color="'solid'" :label="itemsDet.keteranganlain" />
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="center">

                            <VIconButton color="danger" class="mr-2" light raised circle icon="feather:trash"
                              @click="hapusItems(itemsDet)" />
                            <VDropdown spaced dots right>
                              <template #button="{ open, toggle }">
                                <VIconButton icon="feather:more-vertical" class="is-trigger" @mouseenter="open"
                                  @focusin="open" light raised circle @click="toggle" color="info"
                                  v-bind:disabled="PASIEN_AKTIF == false ? true : false">
                                  Aksi
                                </VIconButton>
                              </template>
                              <template #content>
                                <a role="menuitem" class="dropdown-item is-media" @click="detailPetugas(itemsDet)">
                                  <div class="icon">
                                    <i class="iconify lnir lnir-users" aria-hidden="true"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Detail Pelaksana</span>
                                    <span>View petugas tindakan </span>
                                  </div>
                                </a>
                                <a role="menuitem" class="dropdown-item is-media" @click="detailTanggal(itemsDet)">
                                  <div class="icon">
                                    <i class="iconify lnir lnir-calender-alt-2" aria-hidden="true"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Ubah Tanggal</span>
                                    <span>Update tanggal pelayanan </span>
                                  </div>
                                </a>
                                <a role="menuitem" class="dropdown-item is-media" @click="detailKomponen(itemsDet)">
                                  <div class="icon">
                                    <i class="iconify fas fa-money-check" aria-hidden="true"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Komponen Harga</span>
                                    <span>view detail harga tindakan </span>
                                  </div>
                                </a>
                              </template>
                            </VDropdown>
                          </td>
                        </tr>
                      </tbody>
                      <div class="search-results-wrapper" v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
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
                <div class="column is-3">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status info">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">DISKON</span>
                    </div>
                    <small class="text-bold-custom">{{
                      H.formatRp(item.DISKON,
                        'Rp.')
                    }}</small>

                  </VCardCustom>
                </div>
                <div class="column is-3">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status danger">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">DEPOSIT</span>
                    </div>
                    <small class="text-bold-custom">{{
                      H.formatRp(item.DEPOSIT,
                        'Rp.')
                    }}</small>

                  </VCardCustom>
                </div>
                <div class="column is-3" v-if="item.PENGEMBALIAN > 0">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status info">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">PENGEMBALIAN </span>
                    </div>
                    <small class="text-bold-custom h-100">{{ H.formatRp(item.PENGEMBALIAN, 'Rp.') }}</small>
                  </VCardCustom>
                </div>
                <div class="column is-3">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status warning">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">DIBAYAR</span>
                    </div>
                    <small class="text-bold-custom">{{
                      H.formatRp(item.DIBAYAR,
                        'Rp.')
                    }}</small>

                  </VCardCustom>
                </div>
                <div class="column is-3 is-offset-3">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status primary">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">DIKLAIM</span>
                    </div>
                    <small class="text-bold-custom">{{
                      H.formatRp(item.DIKLAIM,
                        'Rp.')
                    }}</small>
                  </VCardCustom>
                </div>
                <div class="column is-3">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status info">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">SISA TAGIHAN</span>
                    </div>
                    <small class="text-bold-custom">{{
                      H.formatRp(item.SISA,
                        'Rp.')
                    }}</small>
                  </VCardCustom>
                </div>
                <div class="column is-3" v-if="item.IURBAYAR > 0">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status warning">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">IUR BAYAR</span>
                    </div>
                    <small class="text-bold-custom">{{
                      H.formatRp(item.IURBAYAR,
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
  <VModal :open="modalPetugas" title="Detail Petugas Tindakan" :noclose="false" size="large" actions="right"
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
                <Dropdown v-model="item.jenisPelaksana" class="is-rounded" :options="d_JenisPelaksana" :optionLabel="'label'"
                    placeholder="Pilih data" @change="changeJenis(item.jenisPelaksana.value)" style="width: 100%;" showClear :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField class="is-rounded-select" v-slot="{ id }">
              <VLabel>Pegawai</VLabel>
              <VControl fullwidth>
                <Dropdown v-model="item.pegawai" class="is-rounded" :options="d_Pegawai" :optionLabel="'label'"
                            placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
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
  <VModal :open="modalTanggal" title="Ubah Tanggal Tindakan" :noclose="false" size="small" actions="right"
    @close="modalTanggal = false">
    <template #content>
      <form class="modal-form custom-mod ">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-12">
                  <p class="block-text">Nama Pelayanan</p>
                  <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>
                </div>
                <div class="column is-12 text-center">
                  <VField class="is-centered">
                    <v-date-picker v-model="item.tglPelayanan" class="is-centered" :max-date="H.maxDate()" />
                  </VField>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveTglPelayanan()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalKomponen" title="Komponen Harga" :noclose="false" size="medium" actions="right"
    @close="modalKomponen = false">
    <template #content>
      <form class="modal-form custom-mod ">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-6">
                  <p class="block-text">Tgl Pelayanan</p>
                  <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>
                </div>
                <div class="column is-6">
                  <p class="block-text">Nama Pelayanan</p>
                  <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>
                </div>
                <div class="column is-12">
                  <VCard class="is-grey">
                    <div class="columns is-multiline p-1">
                      <div class="column is-12 mb--15 text-center" v-if="dataSourceKom.length == 0">
                        <img class="light-image" style=" max-width: 180px;" :src="H.assets().iconNotFoundList" alt="" />
                        <h3>{{ H.assets().notFound }}</h3>
                      </div>
                      <div class="column is-12 mb--15" v-else v-for="(item, index) in dataSourceKom" :key="index">
                        <div class="columns is-multiline p-0">
                          <div class="column is-10">
                            <div class="file-box-2">
                              <img :src="'/images/simrs/bill-list.png'" alt="" />
                              <div class="meta">
                                <span>{{ item.komponenharga }} </span>
                                <table style="width: 100%;">
                                  <tr>
                                    <th class="tb-th">Harga</th>
                                    <th class="tb-th">Jumlah</th>
                                    <th class="tb-th">Diskon</th>
                                  </tr>
                                  <tr>
                                    <td class="tb-th text-center"> {{ H.formatRp(item.hargasatuan, '') }}</td>
                                    <td class="tb-th text-center"> {{ item.jumlah }}</td>
                                    <td class="tb-th text-center"> {{ H.formatRp(item.hargadiscount, '') }}</td>
                                  </tr>
                                </table>


                              </div>
                              <div class="is-right is-dots is-spaced dropdown end-action">
                                <i aria-hidden="true" class="fas fa-check-circle  is-pulled-right"
                                  style="color:var(--success)"></i>
                              </div>
                            </div>

                          </div>
                          <div class="column is-2 mt-5">
                            <VIconButton outlined type="button" class="mr-2" raised circle icon="fas fa-calculator"
                              @click="updateDiskon(item)" color="info">
                            </VIconButton>
                          </div>
                        </div>
                      </div>
                      <div class="column is-12 " v-if="dataSourceKom.length != 0">
                        <VField class="is-pulled-right">
                          <VLabel class="fs-total">{{
                            H.formatRp(item.totalKomponen,
                              'Rp.')
                          }} </VLabel>
                        </VField>
                      </div>
                    </div>
                  </VCard>
                </div>
                <div class="column is-6" v-if="item.norecPPD">
                  <h4 class="block-heading">Nama Komponen </h4>
                  <p class="block-text"> {{ item.namaKomponen }}
                  </p>
                </div>
                <div class="column is-6" v-if="item.norecPPD">
                  <h4 class="block-heading">Harga </h4>
                  <p class="block-text">{{
                    H.formatRp(item.hargasatuankom,
                      'Rp.')
                  }}
                  </p>
                </div>
                <div class="column is-6" v-if="item.norecPPD">
                  <VField>
                    <VLabel style="color: black !important ;font-size: 100%;">Persen Diskon</VLabel>
                    <VControl icon="fas fa-calculator">

                      <VInput type="number" v-model="item.persenDiskon" placeholder="Diskon" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="item.norecPPD">
                  <h4 class="block-heading">Total Diskon</h4>
                  <p class="block-text">{{
                    H.formatRp(item.diskonKomponen,
                      'Rp.')
                  }}
                  </p>
                </div>
              </div>
            </VCard>
          </div>

        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveDiskon()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalRuangan" title="Pilih Ruangan Pelayanan" :noclose="false" size="small" actions="right"
    @close="modalRuangan = false">
    <template #content>
      <form class="modal-form custom-mod ">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-12 text-center">
                  <VField class="is-centered">
                    <VControl>
                      <VSelect v-model="item.ruangAPD" multiple size="8">
                        <VOptgroup :label="items.namadepartemen" v-for="items in LISTRUANGAN_APD_G"
                          :key="items.namadepartemen">
                          <VOption :value="items2.norec_apd" @click="lanjutKan(items2.norec_apd, PARAMETER)"
                            v-for="items2 in items.details">{{ items2.namaruangan }}</VOption>
                        </VOptgroup>
                      </VSelect>
                    </VControl>
                  </VField>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <!-- <template #action>
      <VButton icon="feather:save" @click="saveTglPelayanan()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template> -->
  </VModal>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import SpeedDial from 'primevue/speeddial'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import { useToaster } from '/@src/composable/toaster'
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
import OverlayPanel from 'primevue/overlaypanel';
import Dropdown from 'primevue/dropdown';
import * as qzService from '/@src/utils/qzTrayService'

useHead({
  title: 'Billing - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREGISTRASI = useRoute().query.noregistrasi as string
let PASIEN_AKTIF = ref(true)
if (useRoute().query.isaktif != undefined
  && useRoute().query.isaktif == 'false') {
  PASIEN_AKTIF.value = false
}
const op = ref();
const PARAMETER: any = ref('')
const isLoadingPasien: any = ref(false)
const isLoadingBill: any = ref(false)
const isLoadingPop: any = ref(false)
const pasien: any = ref({})
const isClosing: any = ref()
const dataSource: any = ref([])
const LISTRUANGAN_APD: any = ref([])
const LISTRUANGAN_APD_G: any = ref([])
const dataSourcePetugas: any = ref([])
const dataSourceKom: any = ref([])
const router = useRouter()
const listChecked: any = ref([])
const modelCheck: any = ref([])
const filters = ref('')
const filtersHide = ref('')
const isResep = ref(false)
const isTindakan = ref(false)
const klikLoad: any = ref(false)
const modalPetugas = ref(false)
const modalTanggal = ref(false)
const modalKomponen = ref(false)
const modalRuangan = ref(false)
const dataSelect: any = ref({})
const d_JenisPelaksana: any = ref([])
const d_Pegawai: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  tglorder: new Date(),
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
})
const listButton: any = ref([
  // {
  //   label: 'Detail Pelaksana',
  //   icon: 'fas fa-user-md',
  //   command: () => {

  //   }
  // },
  // {
  //   label: 'Ubah Tanggal',
  //   icon: 'fas fa-calendar-alt',
  //   command: () => {

  //   }
  // },
  {
    label: 'Hapus Layanan',
    icon: 'pi pi-trash',
    command: () => {
      confirmPosition('top')
    }
  },

  // {
  //   label: 'Komponen Harga',
  //   icon: 'fas fa-money-bill-alt',
  //   command: () => {

  //   }
  // },
  {
    label: 'Cetak Rekap Billing',
    icon: 'lnir lnir-printer',
    command: () => {
      qzService.printData(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}`, 'KWITANSI RANAP', 1)
    }
  },
  {
    label: 'Cetak Bukti Layanan',
    icon: 'fas fa-file-pdf',
    command: () => {
      if (LISTRUANGAN_APD.value.length == 1) {
        cetakBuktiLayanan(LISTRUANGAN_APD.value[0].norec_apd)
        return
      }
      PARAMETER.value = 'cetakBuktiLayanan'
      modalRuangan.value = true

    }
  },
  {
    label: 'Cetak Bukti Layanan Pertindakan ',
    icon: 'fas fa-print',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis data yang mau dicetak')
        return
      }

      let norec = listChecked.value.map((a: any) => a.norec).join("|");
      H.printBlade('kasir/billing/report/bukti-layanan-pertindakan?noregistrasi=' + pasien.value.noregistrasi
        + '&norec=' + norec);
    }
  },
  {
    label: 'Cetak Bukti Layanan Jasa ',
    icon: 'fas fa-print',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis data yang mau dicetak')
        return
      }

      let norec = listChecked.value.map((a: any) => a.norec).join("|");
      H.printBlade('kasir/billing/report/bukti-layanan-jasa?noregistrasi=' + pasien.value.noregistrasi
        + '&norec=' + norec);
    }
  },
  {
    label: 'Cetak Bukti Layanan BPJS ',
    icon: 'fas fa-print',
    command: () => {
      let norec = listChecked.value.map((a: any) => a.norec).join("|");
      qzService.printData(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&norec=${norec}`, 'KWITANSI RAJAL', 1)
    }
  },

])
const confirm = useConfirm();

const confirmPosition = (position: any) => {
  confirm.require({
    group: 'positionDialog',
    message: H.alertHapus(),
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: position,
    accept: () => {
      hapusItemsAll()
    },
    reject: () => {
    }
  });
};

function kembaliKeun() {
  window.history.back()
}

const changeClosing = async (e: any) => {
  klikLoad.value = true
  await useApi().post('/emr/closing-pasien', { 'norec_pd': NOREC_PD, 'closing': e }).then((response) => {
    headerPasien()
  })
  klikLoad.value = false
}

const isDetail: any = ref(false)
const dataSourcefiltered = computed(() => {
  if (!filters.value && !filtersHide.value) {
    return dataSource.value
  }
  if (filters.value) {
    return dataSource.value.map((group: any) => {
      return {
        tglpelayanan_group: group.tglpelayanan_group,
        details: group.details.filter((detail: any) =>
          detail.namaproduk.match(new RegExp(filters.value, 'i'))
          || detail.namaruangan.match(new RegExp(filters.value, 'i'))
          || detail.dokterpemeriksa.match(new RegExp(filters.value, 'i'))
        )
      };
    }).filter((group: any) => group.details.length > 0);

  }else  if (filtersHide.value) {
    var filtered: any = [];
    for (let x = 0; x < dataSource.value.length; x++) {
      const element = dataSource.value[x];
      var filteredD = [];
      for (let z = 0; z < element.details.length; z++) {
        const element2 = element.details[z];


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
    return filtered;
  }
});
async function headerPasien() {
  isLoadingPasien.value = true

  item.TOTAL = 0
  await useApi().get(
    `/general/header-pasien-first?norec_pd=${NOREC_PD}`).then(async (response: any) => {
      pasien.value = response.registrasi
      isClosing.value = pasien.value.isclosing
      isLoadingPasien.value = false
    })

  await fetchBill()
  useApi().postNoMessage('/general/save-jurnal-pelayananpasien_t-noreg', {'noregistrasi' : pasien.value.noregistrasi} )
}
async function fetchBill() {
  isLoadingBill.value = true
  dataSource.value = []

  await useApi()
  .post(`sysadmin/save-akomodasi`, {
      'noregistrasi': pasien.value.noregistrasi,
  })
  .then((response3: any) => { 
    
  })

  await useApi().get(
    `/kasir/billing?norec_pd=${NOREC_PD}`).then(async (response: any) => {
      dataSource.value = response.detail

      item.DIBAYAR = response.dibayar
      item.SISA = response.sisa
      item.TOTAL = response.total
      item.DEPOSIT = response.deposit
      item.DISKON = response.diskon
      item.DIKLAIM = response.klaim
      item.PENGEMBALIAN = response.pengembalian
      item.IURBAYAR = response.iurbayar
      item.length = response.length
      item.tarif_inacbg = response.tarif_inacbg
      LISTRUANGAN_APD.value = response.list_ruangan
      LISTRUANGAN_APD_G.value = groupRuang(response.list_ruangan)
      isLoadingBill.value = false

    })
}
function tambahTagihan() {
  if (LISTRUANGAN_APD.value.length == 1) {
    inputTindakan(LISTRUANGAN_APD.value[0].norec_apd)
  } else {
    PARAMETER.value = 'inputTindakan'
    modalRuangan.value = true
  }
}
function lanjutKan(norec_apd: any, params: any) {
  if (params == 'cetakBuktiLayanan') {
    cetakBuktiLayanan(norec_apd)
  }
  if (params == 'inputTindakan') {
    inputTindakan(norec_apd)
  }

}
function inputTindakan(norec_apd: any) {
  router.push({
    name: 'module-emr-tindakan',
    query: {
      norec_pasien_daftar: NOREC_PD,
      nocmfk: pasien.value.nocmfk,
      norec_apd: norec_apd
    }
  })
}
function cetakBilling() {
  qzService.printData(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}`, 'KWITANSI RANAP', 1)
}
function editItems(e: any) {

}
function hapusItems(e: any) {
  confirm.require({
    group: 'positionDialog',
    message: H.alertHapus(),
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'top',
    accept: () => {
      if (e.strukfk != null) {
        H.alert('error', H.alertKasir())
        return
      }
      if (e.strukresepfk != null) {
        H.alert('error', 'Resep hanya bisa dihapus di Farmasi')
        return
      }
      var objSave = {
        'data':
          [{
            'norec_pp': e.norec,
            'namaproduk': e.namaproduk,
            'namaruangan': e.namaruangan,
          }],
        'nocm': pasien.value.nocm,
        'namapasien': pasien.value.namapasien,
        'noregistrasi': pasien.value.noregistrasi,
      }
      nextHapus(objSave)
    },
    reject: () => {
    }
  });

}

function hapusItemsAll() {
  if (listChecked.value.length == 0) {
    H.alert('error', 'Ceklis data yang mau duhapus')
    return
  }

  let data: any = []
  listChecked.value.forEach((element: any) => {
    if (element.strukfk != null) {
      H.alert('error', H.alertKasir())
      return
    }
    if (element.strukresepfk != null) {
      H.alert('error', 'Resep hanya bisa dihapus di Farmasi')
      return
    }
    data.push({
      'norec_pp': element.norec,
      'namaproduk': element.namaproduk,
      'namaruangan': element.namaruangan,
    })
  })

  var objSave = {
    'data': data,
    'nocm': pasien.value.nocm,
    'namapasien': pasien.value.namapasien,
    'noregistrasi': pasien.value.noregistrasi,
  };
  nextHapus(objSave)

}
function nextHapus(objSave: any) {
  isLoadingBill.value = true
  useApi().post(
    `/kasir/billing/hapus-tindakan`, objSave).then((response: any) => {
      isLoadingBill.value = false
      fetchBill()
    }).catch((e: any) => {
      isLoadingBill.value = false
    })
}
async function detailPetugas(e: any) {
  clearInputPetugas()
  dataSelect.value = {}
  dataSelect.value = e
  await loadPetugas(e)
  await useApi().get(
    `tindakan/list-jenis-petugas`).then((response: any) => {
      d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
      // for (let x = 0; x < response.jenispetugaspelaksana.length; x++) {
      //   const element = response.jenispetugaspelaksana[x];
      //   if (element.jenispetugaspe.toLowerCase().indexOf('pemeriksa') > -1) {
      //     item.jenisPelaksana = element.id
      //     changeJenis(item.jenisPelaksana)
      //     break
      //   }
      // }
    })

  modalPetugas.value = true

}
async function loadPetugas(e: any) {
  isLoadingPop.value = true
  dataSourcePetugas.value = []
  await useApi().get(
    `/kasir/billing/petugas-tindakan?norec=${e.norec}`).then((response: any) => {
      isLoadingPop.value = false
      dataSourcePetugas.value = response
    }).catch((e: any) => {
      isLoadingPop.value = false
    })

}
function savePetugas() {
  let json = {
    'norec': item.norec_PPP ? item.norec_PPP : '',
    'objectjenispetugaspefk': item.jenisPelaksana,
    'objectpegawaifk': item.pegawai,
    'nomasukfk': dataSelect.value.norec_apd,
    'noregistrasi': pasien.value.noregistrasi,
    'pelayananpasien': dataSelect.value.norec,
    'namaproduk': dataSelect.value.namaproduk,
    'namaruangan': dataSelect.value.namaruangan,
  }
  useApi().post(
    `kasir/billing/save-jenis-petugas`, json).then((response: any) => {
      loadPetugas(dataSelect.value)
    })
}
function clearInputPetugas() {
  delete item.norec_PPP
  delete item.jenisPelaksana
  delete item.pegawai
}
function hapusPetugas(e: any) {
  useApi().post(
    `kasir/billing/delete-jenis-petugas`, {
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
  d_JenisPelaksana.value.forEach((element: any) => {
    if (element.value == e.objectjenispetugaspefk) {
      item.jenisPelaksana = element
      return
    }
  })
  item.nomasukfk = e.nomasukfk
  item.pelayananpasien = e.pelayananpasien
  await changeJenis(e.objectjenispetugaspefk)
  d_Pegawai.value.forEach((element: any) => {
    if (element.value == e.objectpegawaifk) {
      item.pegawai = element
      return
    }
  })
}
function detailTanggal(e: any) {
  dataSelect.value = {}
  dataSelect.value = e
  item.tglPelayanan = new Date(e.tglpelayanan)
  modalTanggal.value = true

}
function saveTglPelayanan() {
  isLoadingPop.value = true
  useApi().post(
    `kasir/billing/update-tgl-tindakan`, {
    'norec': dataSelect.value.norec,
    'namaproduk': dataSelect.value.namaproduk,
    'noregistrasi': pasien.value.noregistrasi,
    'tglpelayanan': H.formatDate(item.tglPelayanan, 'YYYY-MM-DD HH:mm'),
  }).then((response: any) => {
    isLoadingPop.value = false
    fetchBill()
  })
}
function detailKomponen(e: any) {
  delete item.norecPPD
  dataSelect.value = {}
  dataSelect.value = e
  modalKomponen.value = true
  loadKomponen(e)
}
async function loadKomponen(e: any) {
  isLoadingPop.value = true
  dataSourceKom.value = []
  item.totalKomponen = 0
  await useApi().get(
    `/kasir/billing/detail-tindakan?norec=${e.norec}`).then((response: any) => {
      isLoadingPop.value = false
      item.totalKomponen = response.total
      dataSourceKom.value = response.data
    }).catch((e: any) => {
      isLoadingPop.value = false
    })
}
function updateDiskon(e: any) {
  if (dataSelect.value.strukfk != null) {
    H.alert('error', H.alertKasir())
    return
  }
  item.norecPPD = e.norec
  item.namaKomponen = e.komponenharga
  item.hargasatuankom = e.hargasatuan
  item.jasaKomponen = e.jasa
  item.diskonKomponen = 0
}

function saveDiskon() {
  isLoadingPop.value = true
  if (item.diskonKomponen > item.hargasatuankom) {
    H.alert('error', 'Diskon tidak boleh lebih besar dari total jasa')
    return
  }

  useApi().post(
    `kasir/billing/update-diskon-tindakan`, {
    'norec': item.norecPPD,
    'norec_pp': dataSelect.value.norec,
    'namaproduk': dataSelect.value.namaproduk,
    'noregistrasi': pasien.value.noregistrasi,
    'tglpelayanan': H.formatDate(item.tglPelayanan, 'YYYY-MM-DD HH:mm'),
    'hargasatuan': item.hargasatuankom,
    'jasa': item.jasaKomponen,
    'hargadiscount': item.diskonKomponen,
  }).then((response: any) => {
    isLoadingPop.value = false
    delete item.norecPPD
    loadKomponen(dataSelect.value)
    fetchBill()
  })
}
function filter() {

}
function cetakBuktiLayanan(norec_apd: any) {
  H.printBlade('kasir/billing/report/bukti-layanan-ruangan?noregistrasi=' + pasien.value.noregistrasi + '&norec_apd=' + norec_apd);

}
// function chamgeResep(e: any) {
//   if (e == true) {
//     filtersHide.value = 'Resep'
//   } else {
//     filtersHide.value = ''
//   }
// }

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

function checkedAllJenis(e: any) {
  console.log(filtersHide.value)
  modelCheck.value = []
  listChecked.value = []
  if (e) {
    dataSource.value.forEach((e: any) => {
      e.details.forEach((f: any) => {
        if (f.jenis == filtersHide.value) {
          listChecked.value.push(f)
          //  console.log(listChecked.value.push(f))
          modelCheck.value[f.norec] = true
        }
        //         else{
        //           listChecked.value.push(f)
        //           modelCheck.value[f.norec] = true
        //         }
      });
    });
  }
  console.log(listChecked.value)
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

  console.log(listChecked.value)
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
const toggleOP = (event: any) => {
  op.value.toggle(event);
}
const rekapBil = () => {
  qzService.printData(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}`, 'KWITANSI RANAP', 1)
}
const buktiLayan = () => {
  if (LISTRUANGAN_APD.value.length == 1) {
    cetakBuktiLayanan(LISTRUANGAN_APD.value[0].norec_apd)
    return
  }
  PARAMETER.value = 'cetakBuktiLayanan'
  modalRuangan.value = true
}
const buktiLayanTindakan = () => {
  if (listChecked.value.length == 0) {
    H.alert('error', 'Ceklis data yang mau dicetak')
    return
  }

  let norec = listChecked.value.map((a: any) => a.norec).join("|");
  H.printBlade('kasir/billing/report/bukti-layanan-pertindakan?noregistrasi=' + pasien.value.noregistrasi
    + '&norec=' + norec);
}
const buktiLayanJasa = () => {
  if (listChecked.value.length == 0) {
    H.alert('error', 'Ceklis data yang mau dicetak')
    return
  }

  let norec = listChecked.value.map((a: any) => a.norec).join("|");
  H.printBlade('kasir/billing/report/bukti-layanan-jasa?noregistrasi=' + pasien.value.noregistrasi
    + '&norec=' + norec);
}
const buktiLayanBPJS = () => {
  let norec = listChecked.value.map((a: any) => a.norec).join("|");
  qzService.printData(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&norec=${norec}`, 'KWITANSI RAJAL', 1)
}
const buktiLayanVA = () => {
  let norec = listChecked.value.map((a: any) => a.norec).join("|");
  H.printBlade('report/bukti-layanan-va?noregistrasi=' + pasien.value.noregistrasi
    + '&norec=' + norec);
}
const buktiLayanCaraBayar = () => {
  let norec = listChecked.value.map((a: any) => a.norec).join("|");
  qzService.printData(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec=${norec}`, 'KWITANSI', 1)
}
const buktiLayanWNARajal = () => {
  qzService.printData(`kasir/daftar-penerimaan/report/kwitansi-rajal-wna?noregistrasi=${pasien.value.noregistrasi}`, 'KWITANSI RAJAL WNA', 1)
}
const buktiLayanWNARanap = () => {
  qzService.printData(`kasir/daftar-penerimaan/report/kwitansi-ranap-wna?noregistrasi=${pasien.value.noregistrasi}`, 'KWITANSI RANAP WNA', 1)
}
const konversiHarga = () => {

  router.push({
    name: 'module-kasir-konversi-harga',
    query: {
      nocmfk: pasien.value.nocmfk,
      norec_pasien_daftar: pasien.value.norec_pd,
      noregistrasi: pasien.value.noregistrasi,
    }
  })
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
  () => item.isResep,
  () => {
    if (item.isResep == true) {
      filtersHide.value = 'Resep'
    } else {
      filtersHide.value = ''
    }
  }
)
watch(
  () => item.isTindakan,
  () => {
    if (item.isTindakan == true) {
      filtersHide.value = 'Layanan'
    } else {
      filtersHide.value = ''
    }
  }
)
headerPasien()
qzService.connect()

</script>
<style lang="scss">
// @import '/@src/scss/abstracts/all';
// @import '/@src/scss/main';
@import '/@src/scss/module/kasir/billing';
// @import '/@src/scss/custom/config';

</style>
