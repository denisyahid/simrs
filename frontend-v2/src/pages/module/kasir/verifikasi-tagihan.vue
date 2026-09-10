

<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left is-flex is-align-items-center">
              <h3> Verifikasi Tagihan </h3>
              <h3 class="ml-4 p-4 has-background-link has-text-white" v-if="textBPJS">Pasien Gawat Darurat - Tercover BPJS</h3>
              <h3 class="ml-4 p-4 has-background-link has-text-white" v-if="textUmum">Pasien Tidak Gawat Darurat - Tidak Tercover BPJS</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-calculator rem-100" color="danger" outlined @click="billing()" style="display: none !important">
                  Billing
                </VButton>
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                  Kembali
                </VButton>
                <!-- <VButton rounded type="button" icon="fas fa-times-circle" class="is-fullwidth mr-3" color="warning" outlined raised @click="batalPulang(item)" v-if="slotProps.data.status == 'Belum Verifikasi'">
                Batal Pulang </VButton> -->
                <!-- <VButton type="button" rounded outlined color="danger" raised icon="feather:eye" @click="noAntrianFarmasi(item.NOREC_PD,pasien.noregistrasi)">
                  Pasien Datang
                </VButton> -->
                <VButton type="button" rounded outlined color="info" raised icon="feather:printer"
                  @click="modalBillingualBilling = true"> Cetak Invoice
                </VButton>
                <VButton type="button" rounded outlined color="info" raised icon="feather:printer" v-if="item.NOREC_SP && pasien.iddepartemen == 16"
                  @click="cetakBillingExcelCOB(false)"> Cetak Invoice COB
                </VButton>
                <VButton type="button" rounded outlined color="info" raised icon="feather:printer" v-if="item.NOREC_SP && pasien.iddepartemen == 16"
                  @click="cetakBillingExcel()"> Export Excel
                </VButton>
                <VButton type="button" rounded outlined color="info" raised icon="feather:printer" v-if="item.NOREC_SP && pasien.iddepartemen == 16"
                  @click="cetakBillingExcelCOB(true)"> Export Excel COB
                </VButton>
                <VButton type="button" rounded outlined color="info" raised icon="feather:printer" v-if="item.NOREC_SP"
                  @click="modalBillingualKwitansi = true"> Cetak Kwitansi
                </VButton>
                <VButton type="button" rounded outlined color="warning" raised icon="feather:printer" v-if="item.registrasi?.tglpulang != null"
                  @click="showModalTanggalPulang()"> Tanggal Pulang
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBill || isLoading || isLoadingPasien" :disabled="isDisabled"
                  @click="simpan()" v-if="hidesimpan == true || item.NOREC_SP == ''"> Simpan
                </VButton>
                <!-- <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3" color="info" outlined
                raised @click="openBill()" v-if="pasien.status != 'Lunas'">
                Open Bill </VButton> -->
              </div>
            </div>
          </div>
        </div>

        <Dialog v-model:visible="showBPJS" maximizable modal :style="{ width: '50vw' }">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table style="width:100%;height:100%;border-collapse: collapse">
                  <tr>
                    <td style="text-align:center;vertical-align:middle; height:250px;">
                      <i class="pi pi-info-circle" style="font-size:175px;text-align:center;color:#41AB5D"></i>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:7px; text-align:center">
                      <label style="font-size:24px; font-weight:bold;">Informasi Pasien Gawat Darurat</label>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:7px;text-align:center">
                      <label style="font-size:24px; font-weight:bold;">Pasien Tercover BPJS</label>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <template #footer>
            <div style="text-align: center;">
              <VButton color="purple" size="huge" style="font-size: 20px;" @click="showBPJS = false">
              OK
              </VButton>
            </div>
          </template>
        </Dialog>
        <Dialog v-model:visible="showUmum" maximizable modal :style="{ width: '50vw' }">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table style="width:100%;height:100%;border-collapse: collapse">
                  <tr>
                    <td style="text-align:center;vertical-align:middle; height:250px;">
                      <i class="pi pi-info-circle" style="font-size:175px;text-align:center;color:#ff0f0f"></i>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:7px; text-align:center">
                      <label style="font-size:24px; font-weight:bold;">Informasi Pasien Gawat Darurat</label>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:7px;text-align:center">
                      <label style="font-size:24px; font-weight:bold;">Pasien Tidak Tercover BPJS</label>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <template #footer>
            <div style="text-align: center;">
              <VButton color="purple" size="huge" style="font-size: 20px;" @click="showUmum = false">
              OK
              </VButton>
            </div>
          </template>
        </Dialog>

        <div class="form-body p-2">
          <div class=" hr2-dashboard business-dashboard hr-dashboard">
            <div class="columns is-multiline">
              <div class="column is-12" v-if="isLoadingPasien">
                <PlaceloadHeader class="m-3" />
              </div>
              <div class="column is-12" v-if="!isLoadingPasien">
                <HeadPasien :pasien="pasien" class="m-3" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form class="form-layout is-separate">
      <div class="form-outer">
        <div class="form-body">
          <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
            <div class="columns is-multiline">
              <div class="column is-12  personal-dashboard personal-dashboard-v2">
                <div class="dashboard-card has-margin-bottom">
                  <div class="card-head">
                    <h3 class="dark-inverted">BILLING #{{ item.length }} - (Rp. {{ H.formatRp(item.BILLING, '') }}) </h3>
                    <h3 class="dark-inverted" v-if="item.carabayar?.length > 0">
                      CARA BAYAR :  
                      <template v-for="(cbNama, cbIndex) in item.carabayar">
                        # {{ cbNama }}
                      </template>
                    </h3>
                    <div class="mb-4-min">
                      <VControl>
                        <VSwitchBlock v-model="item.isIUR" label="Koding" color="danger" @change="modalIur = true, isDisabled = false"
                          v-if="pasien.kelompokpasien != undefined && pasien.kelompokpasien.indexOf('BPJS') > -1" />
                      </VControl>
                    </div>
                  </div>
                  <div class="active-projects">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <div class="load-more-wrap has-text-centered p-1 mb-3">
                          <div class="columns is-multiline">
                            <div class="column is-3">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status primary">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">TAGIHAN</span>
                                </div>
                                <!-- <small class="text-bold-custom h-100">{{ H.formatRp(item.TOTAL, 'Rp.') }}</small> -->
                                <small class="text-bold-custom h-100">Rp. {{ item.TOTAL }}</small>
                              </VCardCustom>
                            </div>
                            <div class="column is-3">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status info">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">DEPOSIT</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.DEPOSIT, 'Rp.') }}</small>

                              </VCardCustom>
                            </div>
                            <div class="column is-3">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status warning">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">TOTAL KLAIM</span>
                                </div>
                                <VField class=" h-100">
                                  <VControl icon="fas fa-calculator">
                                    <input v-model="item.DIKLAIM" type="text" class="input is-rounded"
                                      placeholder="TOTAL KLAIM " v-if="isHide" />
                                    <input v-model="item.DIKLAIM_TXT" type="text" class="input is-rounded"
                                      placeholder="TOTAL KLAIM " v-mask-currency />
                                  </VControl>
                                </VField>
                                <!-- <small class="text-bold-custom">{{ H.formatRp(item.SISA, 'Rp.')}}</small> -->
                              </VCardCustom>
                            </div>
                            <div class="column is-3">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status danger">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">HARUS BAYAR</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.DIBAYAR < 0 ? 0 :
                                  item.DIBAYAR, 'Rp.') }}</small>

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
                            <div class="column is-3" v-if="item.IURBAYAR > 0">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status danger">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">IUR BAYAR</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.IURBAYAR, 'Rp.') }}</small>
                              </VCardCustom>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="column is-2">
                        <VField>
                          <VControl icon="feather:search">
                            <input v-model="filters" type="text" class="input is-rounded" placeholder="Filter " />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2 pt-1">
                        <VControl>
                          <VSwitchBlock v-model="isResep" label="Resep" color="danger"
                            @change="chamgeResep(isResep)" />
                        </VControl>
                      </div>
                      <div class="column is-2 pt-1">
                        <VControl>
                          <VSwitchBlock v-model="isPelayanan" label="Pelayanan" color="info"
                            @change="changeLayanan(isPelayanan)" />
                        </VControl>
                      </div>
                      <div class="column is-3 pt-1">
                        <VButton type="button" rounded outlined color="danger" raised icon="lucide:activity"
                          @click="showModalMultiPenjamin()"> Multi Penjamin
                        </VButton>
                        <!-- <VControl>
                          <VCheckbox v-model="isMultiPenjamin" :true-value="true" label="Multi penjamin" color="primary"
                            circle />
                        </VControl> -->
                      </div>

                      <div class="column is-3">
                        <div class="content mt-0 mb-0">
                          <div class="is-divider mt-3 mb-4" data-content="Info"></div>
                          <VTag color="info" label="Layanan" />
                          <VTag color="info" :label="item.totalLayanan" outlined class="ml-1" />
                          <VTag color="danger" label="Resep" class="ml-3" />
                          <VTag color="danger" :label="item.totalResep" outlined class="ml-1" />
                        </div>
                      </div>
                      <div class="column is-12">
                        <div class="column is-12">
                          <table class="tb-custom mt-3">
                            <thead>
                              <tr>
                                <th class="text-center">
                                  <VControl raw subcontrol style="margin-top:-10px">
                                    <VCheckbox v-model="item.checkAll" label="Check All" color="info"
                                      @change="checkedAll(item.checkAll)" :value="item.checkAll" />
                                  </VControl>
                                </th>
                                <th>URAIAN</th>
                                <th>HARGA SATUAN</th>
                                <th>JUMLAH</th>
                                <th>SUBTOTAL</th>
                                <th>STATUS</th>
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
                                      <VCheckbox v-model="modelCheck[itemsDet.norec]" :value="itemsDet.norec" color="info"
                                        square :checked="modelCheck[itemsDet.norec]" @change="checkedItems()" />
                                    </VControl>
                                  </td>
                                  <td width="30%">
                                    <div class="columns is-multiline">
                                      <div class="column is-12" @click="checkedItemsLABEL(itemsDet.norec)"
                                        style="cursor:pointer">
                                        <div class="title-ruangan">{{ itemsDet.namaruangan }}</div>
                                        <div class="title-layan">{{ itemsDet.namaproduk }}</div>
                                        <div>
                                          <VTag :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                            :label="itemsDet.tglpelayanan" />
                                        </div>
                                        <div class="title-kelas">{{ itemsDet.namakelas }}</div>
                                        <div class="title-kelas">DPJP : {{ itemsDet.dokterpemeriksa}}</div>
                                        <div class="title-kelas">Pemeriksa : 
                                          <span style="font-weight: bold; color: black;">{{ itemsDet.pemeriksa }}</span> 
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
                                  </td>
                                  <td class="center">{{ itemsDet.jumlah }}</td>
                                  <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}</td>
                                  <td class="center">{{ itemsDet.statusverifikasi }}</td>
                                </tr>
                              </tbody>
                              <div class="search-results-wrapper"
                                v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                                <div class="search-results-body ">
                                  <div class="page-placeholder">
                                    <div class="placeholder-content">
                                      <img class="light-image" style=" max-width: 340px;"
                                        :src="H.assets().iconNotFound_rev" alt="" />
                                      <img class="dark-image" style=" max-width: 340px;"
                                        :src="H.assets().iconNotFound_rev" alt="" />
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
                    <!-- <div class="content mt-0 mb-0">
                                            <div class="is-divider mt-3 mb-2" data-content="TOTAL"></div>
                                          </div> -->


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <Dialog v-model:visible="modalIur" modal header="KODING" :style="{ width: '60vw' }">
    <div class="columns is-multiline">
      <div class="column is-4">
        <VField label="Kelas Hak" class="is-rounded-select is-autocomplete-select  mt-0 pt-0 label-v3" v-slot="{ id }">
          <VControl icon="fas fa-arrows-alt-h" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.kelasHak" :options="d_KelasHak" :optionLabel="'nama'" class="is-rounded"
              placeholder="Kelas Hak" style="width: 100%;" :filter="true" showClear/>
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField label="Kelas Pelayanan" class="is-rounded-select is-autocomplete-select  mt-0 pt-0 label-v3"
          v-slot="{ id }">
          <VControl icon="fas fa-arrow-up" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.kelasPelayanan" :options="d_KelasNaik" :optionLabel="'namakelas'" class="is-rounded"
              placeholder="Kelas Pelayanan" style="width: 100%;" :filter="true" showClear/>
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField label="Jumlah Hari Naik Kelas" class="label-v3">
          <VControl icon="fas fa-bed">
            <input v-model="item.upgrade_class_los" type="number" class="input is-rounded"
              placeholder="Jumlah Hari Naik Kelas "/>
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField label="Jenis Tagihan" class="label-v3">
          <VSwitchBlock v-model="item.isIURReal" label="IUR Biaya" color="danger"
          v-if="pasien.kelompokpasien != undefined && pasien.kelompokpasien.indexOf('BPJS') > -1" />
        </VField>
      </div>
      <div class="column is-8">
        <VField label="Jumlah Tagihan" class="label-v3">
          <VControl icon="fas fa-calculator">
            <input v-model="item.TOTALTagihanIUR" type="text" class="input is-rounded"/>
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField label="Koding" class="is-rounded-select is-autocomplete-select  mt-0 pt-0 label-v3"
          v-slot="{ id }">
          <VControl class="prime-auto">
            <AutoComplete v-model="item.koding" @itemSelect="changeKoding()" :suggestions="d_Koding"
              :optionLabel="'text'" @complete="fetchKoding($event)" :dropdown="true" :minLength="3"
              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'text'" placeholder="Koding..."
              class="mt-2" />
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField label="Total Plafon" class="label-v3">
          <VControl icon="fas fa-calculator" class="mt-3">
            <input v-model="item.totalPlafon" type="text" class="input is-rounded" disabled/>
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField label="IUR Bayar" class="label-v3">
          <VControl icon="fas fa-calculator">
            <input v-model="item.iurBayar" type="text" class="input is-rounded"
              placeholder="IUR Bayar" />
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField label="Keuntungan RS" class="label-v3">
          <VControl icon="fas fa-calculator">
            <input v-model="item.keuntunganRS" type="text" class="input is-rounded"
              placeholder="Keuntungan RS" />
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField label="Beban RS" class="label-v3">
          <VControl icon="fas fa-calculator">
            <input v-model="item.bebanRS" type="text" class="input is-rounded"
              placeholder="Beban RS" />
          </VControl>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalIur = false">
        Tutup
      </VButton>
      <VButton rounded outlined :color="'warning'" class="mr-2" raised icon="fas fa-calculator" :loading="isTarif18" style="display: none !important"
        @click="detailTarif" bold>
        18 Variable Tarif
      </VButton>
      <VButtonImg rounded outlined :color="colorPLAFON" class="mr-2" icon="/images/icons/files/bpjs-no-bg.svg" raised
        :loading="isFlafon" @click="hitungBiayaIUR" bold
        v-tooltip-prime.top="'Hitung Tambahan Biaya Yang Dibayar Pasien'" style="display: none !important">
        Hitung IUR INACBG's
      </VButtonImg>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="saveIUR()"> Simpan
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalDetail" modal header="Detail 18 Variable" :style="{ width: '70vw' }">
    <div class="columns is-multiline">
      <div class="column is-12">
        <div>

          <VCard class="mt-2">
            <div class="columns is-multiline">
              <div class="column is-4">
                <listWidgetCustom title="Prosedur Non Bedah"
                  :color="tarif18.prosedur_non_bedah > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.prosedur_non_bedah, 'Rp.')" />
                <listWidgetCustom title="Tenaga Ahli" :color="tarif18.tenaga_ahli > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.tenaga_ahli, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Radiologi" :color="tarif18.tenaga_ahli > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.radiologi, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Rehabilitasi" :color="tarif18.rehabilitasi > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.rehabilitasi, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Obat" :color="tarif18.obat > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.obat, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Alkes" :color="tarif18.alkes > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.alkes, 'Rp.')" class="mt-1" />
              </div>
              <div class="column is-4">
                <listWidgetCustom title="Prosedur Bedah" :color="tarif18.prosedur_non_bedah > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.prosedur_non_bedah, 'Rp.')" />
                <listWidgetCustom title="Keperawatan" :color="tarif18.keperawatan > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.keperawatan, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Laboratorium" :color="tarif18.laboratorium > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.laboratorium, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Kamar / Akomodasi" :color="tarif18.kamar > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.kamar, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Obat Kronis" :color="tarif18.obat_kronis > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.obat_kronis, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="BMHP" :color="tarif18.bmhp > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.bmhp, 'Rp.')" class="mt-1" />
              </div>
              <div class="column is-4">
                <listWidgetCustom title="Konsultasi" :color="tarif18.konsultasi > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.konsultasi, 'Rp.')" />
                <listWidgetCustom title="Penunjang" :color="tarif18.penunjang > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.penunjang, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Pelayanan Darah" :color="tarif18.pelayanan_darah > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.pelayanan_darah, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Rawat Intensif" :color="tarif18.rawat_intensif > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.rawat_intensif, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Obat Kemoterapi" :color="tarif18.obat_kemoterapi > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.obat_kemoterapi, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Sewa Alat" :color="tarif18.sewa_alat > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.sewa_alat, 'Rp.')" class="mt-1" />
              </div>
            </div>
          </VCard>

          <Fieldset legend="Tarif Belum Dimapping" :toggleable="true"
            :collapsed="tarif18.belum_mapping.length > 0 ? false : true" class="mt-2">
            <div class="columns is-multiline">
              <div class="column is-3" v-for="item in tarif18.belum_mapping" :key="item.norec">
                <TStatusPojokKanan :title="item.namaproduk" :subtitle="H.formatRp(item.ttl, 'Rp.')"
                  class="inbox-widget-2" />
              </div>
            </div>

          </Fieldset>

          <VCard class="mt-2">
            <div class="columns is-multiline">
              <div class="column is-6">
                <b>Tarif Rumah Sakit INACBG's : {{ H.formatRp(tarif18.totalmappingtarif, 'Rp. ') }}</b>
              </div>
              <div class="column is-6">
                <b>Total Billing : {{ H.formatRp(tarif18.totalbilling, 'Rp. ') }}</b>
              </div>
            </div>
          </VCard>

        </div>
      </div>
    </div>
  </Dialog>
  <Dialog v-model:visible="modalUpdatePiutang" modal header="Update Status Piutang" :style="{ width: '25vw' }">
    <div class="column">
      <span style="font-weight: 500;">Status Piutang</span>
      <VField class="is-autocomplete-select pt-3">
        <VControl>
            <Multiselect v-model="item.statusPiutang" :attrs="{ value }"
                placeholder="--Pilih--" label="label" :options="d_Status"
                :searchable="true" track-by="label" mode="single" autocomplete="off">
            </Multiselect>
        </VControl>
      </VField>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalUpdatePiutang = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="saveUpdatePiutang()" :loading="isLoading"> Simpan
      </VButton>
    </template>
  </Dialog>
  <!-- modal multi penjamin -->
  <VModal :open="modalMultiPenjamin" title="Multi Penjamin" noclose size="big" actions="right"
    @close="modalMultiPenjamin = false; isMultiPenjamin = false" cancelLabel="Tutup">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField>
                <span>Jenis Pasien</span>
                <VControl class="prime-auto">
                  <AutoComplete v-model="item.jenisPasienMultiPenjamin" :suggestions="d_KelompokPasien"
                    :optionLabel="'label'" @complete="fetchKelompokPasien($event)" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Pasien..."
                    class="mt-2" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <span>Penjamin</span>
                <VControl class="prime-auto">
                  <AutoComplete v-model="item.penjaminfk" :suggestions="d_Penjamin" :optionLabel="'namarekanan'"
                    @complete="fetchPenjamin($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'namarekanan'" placeholder="Penjamin Pasien..." class="mt-2" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <span>Total Klaim</span>
                <VControl class="prime-auto mt-2">
                  <VInput v-mask-currency v-model="item.totalHarusBayarMultiPenjamin"></VInput>
                </VControl>
              </VField>
            </div>
            <div class="column mt-5">
              <VButton color="warning" class="mt-2" @click="addMultiPenjamin()" v-if="!item.NOREC_SP">Tambah</VButton>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <DataTable :value="d_SourceMultiPenjamin" class="p-datatable-sm" :loading="isLoading" :paginator="true"
            :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="#" frozen></Column>
            <Column field="kelompokpasien" header="Jenis" :sortable="true" style="min-width: 100px"></Column>
            <Column field="penjamin" header="Penjamin" :sortable="true" style="min-width: 100px"></Column>
            <Column field="total" header="Klaim" :sortable="true" style="min-width: 100px">
              <template #body="slotProps">
                {{ H.formatRupiah(slotProps.data.total, "") }}
              </template>
            </Column>
            <Column :exportable="false" header="Action" v-if="!item.NOREC_SP">
              <template #body="slotProps" >
                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                  v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="pi pi-trash" class="mr-3" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="hapusMultiPenjamin(slotProps.data)">
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </div>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="simpanMultiPenjamin()" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>
  <!-- end multi penjamin -->
  <VModal :open="modalModalTglPulang" title="Ubah Tanggal Pulang" noclose size="small" actions="right"
    @close="modalModalTglPulang = false" cancelLabel="Tutup">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField vertical>
            <span>Tanggal Pulang</span>
            <VControl fullwidth>
              <Calendar v-model="item.updatetglpulang" dateFormat="yy-mm-dd" showTime hourFormat="24" style="width: 100% !important; margin-top: 10px"/>
            </VControl>
          </VField>
        </div>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="simpanUpdatePulang()" color="primary" raised :loading="isLoading">Simpan</VButton>
    </template>
  </VModal>

  <VModal :open="modalBillingualBilling" title="Pilih Bahasa" noclose size="small" actions="right"
    @close="modalBillingualBilling = false" cancelLabel="Tutup">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-12 mx-auto text-center">
          <VButtons style="justify-content: center;">
            <VButton color="primary" icon="feather:printer" outlined raised @click="cetakBilling()"> Invoice Indonesia </VButton>
            <VButton color="info" icon="feather:printer" raised @click="cetakBillingEng()"> Invoice Inggris </VButton>
          </VButtons>
        </div>
      </div>
    </template>
  </VModal>

  <VModal :open="modalBillingualKwitansi" title="Pilih Bahasa" noclose size="small" actions="right"
    @close="modalBillingualKwitansi = false" cancelLabel="Tutup">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-12 mx-auto text-center">
          <VButtons style="justify-content: center;">
            <VButton color="primary" icon="feather:printer" outlined raised @click="cetakKwitansi2()"> Kwitansi Indonesia </VButton>
            <VButton color="info" icon="feather:printer" raised @click="cetakKwitansi2Eng()"> Kwitansi Inggris </VButton>
          </VButtons>
        </div>
      </div>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import Calendar from 'primevue/calendar';
// import * as qzService from '/@src/utils/qzTrayService'

useHead({
  title: 'Verifikasi Tagihan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_SP = useRoute().query.norec_sp as string
let NOREC_SBM = useRoute().query.norec_sbm as string
let STATUS = useRoute().query.status as string
const isLoadingPasien: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_SP: NOREC_SP != undefined ? NOREC_SP : '',
  NOREC_APD: '',
  registrasi: {},
  tglorder: new Date(),
  produkCeklis: [],
  pegawaiOrder: useUserSession().getUser().id,
  totalResep: 0,
  totalLayanan: 0,
  DEPOSIT: 0,
  DIBAYAR: 0,
  DIKLAIM: 0,
  TOTAL: 0,
  PENGEMBALIAN: 0,
  isIURReal: false,
  updatetglpulang: new Date()
})
const modalDetail: any = ref(false)
const isDisabled: any = ref(false)
const isTarif18: any = ref(false)
const hidesimpan: any = ref(true)
const HASILGROUPING: any = ref(0)
const tarif18: any = ref({})
const isHide: any = ref(false)
const modelCheck: any = ref([])
const isLoadingBill: any = ref(false)
const listChecked: any = ref([])
const d_KelompokPasien: any = ref([])
const d_Penjamin: any = ref([])
const d_SourceMultiPenjamin: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const modalUpdatePiutang = ref(false)
const d_Status: any = ref([])
const listColor: any = ref([])
const userLogin: any = useUserSession().getUser()
const resPlafon: any = ref();
const rupiah = (number)=>{
    return new Intl.NumberFormat("id-ID").format(number);
  }
for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i];
  if (i <= 9 && element != 'primary')
    listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const d_KelasHak: any = ref([
  { id: 3, kode: 1, nama: 'Kelas 1', kdkelas: 'kelas_1' },
  { id: 2, kode: 2, nama: 'Kelas 2', kdkelas: 'kelas_2' },
  { id: 1, kode: 3, nama: 'Kelas 3', kdkelas: 'kelas_3' },
])
const d_KelasNaik: any = ref([])
const d_Koding: any = ref([])

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const pasien: any = ref({})
const registrasi: any = ref({})
const isLoading = ref(false)
const router = useRouter()
const filters = ref('')
const filtersHide = ref('')
const dataSource: any = ref([])
const isResep = ref(false)
const isPelayanan = ref(false)
const isMultiPenjamin: any = ref(false)
const modalMultiPenjamin: any = ref(false)
const modalModalTglPulang: any = ref(false)
const modalBillingualBilling: any = ref(false)
const modalBillingualKwitansi: any = ref(false)
const isFlafon: any = ref(false)
const dataSourceINACBG: any = ref([])
const colorPLAFON: any = ref('info')
const modalIur: any = ref(false)
const showBPJS: any = ref(false)
const showUmum: any = ref(false)
const textBPJS: any = ref()
const textUmum: any = ref()
const dataSourcefiltered = computed(() => {
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
const confirm = useConfirm();

const confirmPembayaran = (response: any) => {
  router.push({
        name: 'module-kasir-pembayaran-tagihan',
        query: {
          norec_sp: response.norec,
          nocmfk: ID_PASIEN,
          norec_pd: NOREC_PD,
          pageFrom: 'tagihanPasien'
        },
      })
};
const saveUpdatePiutang = async (data:any,status:any)=>{
  console.log(item.statusPiutang)
  let objectSave ={
    norec : NOREC_PD,
    objectstatuspiutangfk : item.statusPiutang
  }
  isLoading.value = true
  await useApi().post('/kasir/save-piutang', objectSave).then((response: any) => {
    isLoading.value = false
    modalUpdatePiutang.value = false
  })
}
const confirmPengembalian = () => {
  confirm.require({
    group: 'positionDialog',
    message: 'Lanjut ke pengembalian Deposit?',
    header: 'Info ',
    icon: 'pi pi-info-circle',
    position: 'top',
    accept: () => {
      router.push({
        name: 'module-kasir-pembayaran-tagihan',
        query: {
          norec_pd: NOREC_PD,
          nocmfk: ID_PASIEN,
          totalBayar: parseFloat(item.DIBAYAR) * -1,
          pageFrom: 'pengembalianDeposit'
        },
      })
    },
    reject: () => {
    }
  });
}
const pasienByID = async (id: any) => {
  isLoadingPasien.value = true
  useApi().get(
    `/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then(async (response: any) => {
      pasien.value = response.pasien
      registrasi.value = response.registrasi
      pasien.value.isClosing = response.registrasi[0].isclosing
      pasien.value.namaruangan = response.last_registrasi.namaruangan
      pasien.value.kelompokpasien = response.last_registrasi.kelompokpasien
      pasien.value.noregistrasi = response.registrasi[0].noregistrasi
      pasien.value.tglregistrasi = H.formatDateIndoSimple(response.last_registrasi.tglregistrasi)
      pasien.value.tglregistrasifix = response.last_registrasi.tglregistrasi
      pasien.value.status = response.last_registrasi.statusbayar
      pasien.value.kelas = response.registrasi?.[0]?.namakelas || []
      
      if(response.registrasi?.[0]?.kelasrawatfk) {
        pasien.value.kelas = response.registrasi?.[0]?.naikkelas
      }
      pasien.value.rekanan = response.registrasi?.[0]?.namarekanan || []
      pasien.value.kelasditanggung = response.registrasi?.[0]?.kelasditanggung || []
      pasien.value.nmprovider = response.registrasi?.[0]?.nmprovider ?? ''
      pasien.value.klsrawathak_kode = response.registrasi?.[0]?.klsrawathak_kode ?? ''
      pasien.value.objectkelompokpasienlastfk = response.registrasi?.[0]?.objectkelompokpasienlastfk ?? ''
      pasien.value.kelompokpasien = response.registrasi?.[0]?.kelompokpasien ?? ''
      pasien.value.iskelastitip = response.registrasi?.[0]?.iskelastitip
      pasien.value.isnaikkelas = response.registrasi?.[0]?.isnaikkelas

      item.inacbg_totalgrouper = response.last_registrasi.inacbg_totalgrouper
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.registrasi = response.last_registrasi
      isLoadingPasien.value = false
      item.updatetglpulang = item.registrasi?.tglpulang

      // item.DIKLAIM_TXT = item.inacbg_totalgrouper != null ? H.formatRupiah(parseFloat(item.inacbg_totalgrouper), '') : 0
      // item.iurBayar = item.inacbg_totalgrouper != null ? H.formatRupiah(parseFloat(item.inacbg_totalgrouper), '') : 0
      // fetchTindakan(item.RUANGAN_LAST)
      if (pasien.value.kelompokpasien.toLowerCase() == 'bpjs') {
        await fetchKelas()
        d_KelasHak.value.forEach(element => {
          if (element.kode == pasien.value.klsrawathak_kode) {
            item.kelasHak = element
          }
        });
        d_KelasNaik.value.forEach(element => {
          if (element.namakelas == pasien.value.kelas) {
            item.kelasPelayanan = element
          }
        });

        item.upgrade_class_los = H.daysDifference(response.last_registrasi.tglregistrasi, (response.last_registrasi.tglpulang != null ? response.last_registrasi.tglpulang : H.formatDate(new Date(), 'YYYY-MM-DD')))
        if (item.upgrade_class_los == 0) {
          item.upgrade_class_los = 1
        } else {
          item.upgrade_class_los = parseInt(item.upgrade_class_los)
        }

      }
      await fetchBill()
    })
}
const isDecimal = (value) => {
  return Number.isFinite(value) && !Number.isInteger(value);
}

const showBPJSDialog = () => {
  // showBPJS.value = true;
  textBPJS.value = true;
};
const showUmumDialog = () => {
  // showUmum.value = true;
  textUmum.value = true;
};

async function fetchBill() {
  isLoadingBill.value = true
  dataSource.value = []
  item.BILLING = 0
  item.DEPOSIT = 0
  item.PENGEMBALIAN = 0
  await useApi().get(
    `/kasir/verifikasi-tagihan?norec_pd=${NOREC_PD}&strukfk=null`).then(async (response: any) => {
      item.totalLayanan = 0
      item.totalResep = 0
      for (let x = 0; x < response.detail.length; x++) {
        const element = response.detail[x];
        for (let y = 0; y < element.details.length; y++) {
          const element2 = element.details[y];
          if (element2.strukresepfk == null) {
            item.totalLayanan = item.totalLayanan + 1
          } else {
            item.totalResep = item.totalResep + 1
          }
        }
      }
      dataSource.value = response.detail

      item.carabayar = [];
      if(STATUS == 'Sudah Closing'){
        hidesimpan.value == false
      }
      if(response.carabayar.length > 0) {
        for (let cb = 0; cb < response.carabayar.length; cb++) {
          const elCB = response.carabayar[cb];
          item.carabayar.push(elCB.carabayar)
        }
      }
      item.BILLING = response.total
      item.DEPOSIT = response.deposit
      item.checkAll = true
      checkedAll(item.checkAll)
      // item.DISKON = response.diskon
      item.length = response.length
      // LISTRUANGAN_APD.value = response.list_ruangan
      // LISTRUANGAN_APD_G.value = groupRuang(response.list_ruangan)
      getMultiPenjamin();
      if(response.iurbayar > 0) {
        item.IURBAYAR = response.iurbayar
      }
      isLoadingBill.value = false
      isLoading.value = false
    })

    // Cek Pembiayaan
    try {
    const Gadar = await useApi().get(
      `emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=Parameter_BPJS,Parameter_GawatDarurat`
    );

    if (Gadar) {
      if (Gadar.Parameter_BPJS === 'BPJS' && Gadar.Parameter_GawatDarurat === "Gawat Darurat") {
        showBPJSDialog()
      } else if (Gadar.Parameter_BPJS === 'BPJS' && Gadar.Parameter_GawatDarurat === "Tidak Gawat Darurat") {
        showUmumDialog()
      } else if (Gadar.Parameter_BPJS === 'Non BPJS' && Gadar.Parameter_GawatDarurat === "Gawat Darurat") {
        showUmumDialog()
      } else if (Gadar.Parameter_BPJS === 'Non BPJS' && Gadar.Parameter_GawatDarurat === "Tidak Gawat Darurat") {
        showUmumDialog()
      }
    } else {
    }
  } catch (error) {
    console.error('Error fetching Gadar:', error);
  }
}

function chamgeResep(e: any) {

  if (e == true) {
    if(isPelayanan.value) {
      filtersHide.value = '';
    }else {
      filtersHide.value = 'Resep'
    }
  } else {
    if(isPelayanan.value) {
      filtersHide.value = 'Layanan'
    }else {
      filtersHide.value = ''
    }
  }
}

function changeLayanan(e: any) {

  if (e == true) {
    if(isResep.value) {
      filtersHide.value = '';
    }else {
      filtersHide.value = 'Layanan'
    }
  } else {
    if(isResep.value) {
      filtersHide.value = 'Resep'
    }else {
      filtersHide.value = ''
    }
  }
}


function simpan() {

  // if(pasien.value.isClosing == null){
  //   H.alert('error','Pasien belum diclosing')
  //   return
  // }
  // if(STATUS == 'Sudah Closing'){
  //   H.alert('error', 'Sudah Diclosing')
  //   return
  // }
  // console.log('TOTAL VALUE', item.TOTALValue);
  if(item.BILLING > 0) {
    if (listChecked.value.length == 0) {
      H.alert('error', 'Ceklis data terlebih dahulu ruangan tujuan')
      return
    }
  }
  var objSave = {
    norec_pd: NOREC_PD,
    noregistrasi: pasien.value.noregistrasi,
    nocm: pasien.value.nocm,
    namapasien: pasien.value.namapasien,
    total: item.TOTALValue,
    deposit: item.DEPOSIT,
    klaim: item.DIKLAIM,
    totalbayar: item.DIBAYAR < 0 ? 0 : item.DIBAYAR,
    totaliurbayar: item.IURBAYAR != undefined ? item.IURBAYAR : 0,
    totalbebanrs: item.bebanRSValue != undefined ? item.bebanRSValue : 0,
    totalkeuntunganrs: item.keuntunganRSValue != undefined ? item.keuntunganRSValue : 0,
    details: listChecked.value,
    multipenjamin: d_SourceMultiPenjamin.value,
    kodingkelashak: item.kelasHak != undefined ? item.kelasHak.id : null,
    kodingkelasnaik: item.kelasPelayanan != undefined ? item.kelasPelayanan.id : null,
    kodingplafonvalue: item.totalPlafonValue != undefined ? item.totalPlafonValue : 0,
    kodingplafontotal: item.maxiurbayar != undefined ? item.maxiurbayar : 0,
    kodingdiagnosa: item.koding != undefined ? item.koding.kode : null,
    isclosingzero: item.BILLING > 0 ? false : true

  }
  isLoading.value = true
  useApi().post(
    `/kasir/verifikasi-tagihan/simpan`, objSave).then((response: any) => {
      isLoading.value = false
      isDisabled.value = true
      // console.log("Response Verif Tagihan", response.sp);
      if(response.sp?.norec) {
        item.NOREC_SP = response.sp.norec
      }
      if (item.DIBAYAR > 0 || item.IURBAYAR > 0 || pasien.value.kelompokpasien.toUpperCase().indexOf('KETENAGAKERJAAN') > -1 || pasien.value.kelompokpasien.toUpperCase().indexOf('IKS') > -1) {
        confirmPembayaran(response.sp)
      } else if (item.DIBAYAR < 0) {
        confirmPengembalian()
      } else {
        //window.history.back()
        fetchBill()
        saveKlaimSEP()
      }

      delete item.NOREC_SO
    }).catch((e: any) => {
      isLoading.value = false
    })

}

const saveKlaimSEP = async () => {
  isLoading.value = true
  if(pasien.value.iddepartemen == 18){
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
        'norec_pd': NOREC_PD,
        'documentklaimfk': 17,
        'namafile': "billing",
        'tglregistrasi': pasien.value.tglregistrasifix,
        'api': "Report-ReportCtrl@cetakBillbpjsKlaim"
    }).then((r) => {
        isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
  } else if(pasien.value.iddepartemen == 9){
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
        'norec_pd': NOREC_PD,
        'documentklaimfk': 29,
        'namafile': "billing_igd",
        'tglregistrasi': pasien.value.tglregistrasifix,
        'api': "Report-ReportCtrl@cetakBillbpjsKlaim"
    }).then((r) => {
        isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
  }
    
}

const cetakKwitansi2 = () => {
  console.log(pasien.value)
  let namabaru = item.namabaru ? item.namabaru : '-'
  let bangsa = pasien.value.kebangsaan;
    if(pasien.value.iddepartemen != 16){
      H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${item.NOREC_SP}&bangsa=WNI`)
    } else if(pasien.value.iddepartemen == 16){
      H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${item.NOREC_SP}&bangsa=WNI`)
    } 
}
const cetakBilling = () => {
  let namabaru = item.namabaru ? item.namabaru : '-'
  let bangsa = pasien.value.kebangsaan;

  if(pasien.value.iddepartemen != 16){
    H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&bangsa=WNI`)

    // qzService.printData(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&user=${userLogin.pegawai.namaLengkap}&bangsa=WNI`, 'KWITANSI RAJAL', 1)
  } else if(pasien.value.iddepartemen == 16){
    H.printBlade(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&bangsa=WNI`)

    // qzService.printData(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&user=${userLogin.pegawai.namaLengkap}&bangsa=WNI`, 'KWITANSI RANAP', 1)
  }
}

const cetakKwitansi2Eng = () => {
  console.log(pasien.value)
  let namabaru = item.namabaru ? item.namabaru : '-'
  let bangsa = pasien.value.kebangsaan;
    if(pasien.value.iddepartemen != 16){
      H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${item.NOREC_SP}&bangsa=WNA`)
    } else if(pasien.value.iddepartemen == 16){
      H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${pasien.value.noregistrasi}&norec_sp=${item.NOREC_SP}&bangsa=WNA`)
    } 
}

const cetakBillingEng = () => {
  let namabaru = item.namabaru ? item.namabaru : '-'
  let bangsa = pasien.value.kebangsaan;

  if(pasien.value.iddepartemen != 16){
    H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&bangsa=WNA`)

    // qzService.printData(`report/bukti-layanan-bpjs?noregistrasi=${pasien.value.noregistrasi}&user=${userLogin.pegawai.namaLengkap}&bangsa=WNI`, 'KWITANSI RAJAL', 1)
  } else if(pasien.value.iddepartemen == 16){
    H.printBlade(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&bangsa=WNA`)

    // qzService.printData(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&user=${userLogin.pegawai.namaLengkap}&bangsa=WNI`, 'KWITANSI RANAP', 1)
  }
}

const cetakBillingExcel = () => {
  let bangsa = pasien.value.kebangsaan;
  H.printBlade(`kasir/billing/report/rincian-biaya?noregistrasi=${pasien.value.noregistrasi}&bangsa=WNI&isexcel=true`)
}
const cetakBillingExcelCOB = (isexcel) => {
  let ispdf = isexcel == false ? true : false;
  let bangsa = pasien.value.kebangsaan;
  H.printBlade(`kasir/billing/report/rincian-biaya-casemix?noregistrasi=${pasien.value.noregistrasi}&bangsa=WNI&isexcel=${isexcel}&pdf=${ispdf}`)
}

function checkedAll(e: any) {
  modelCheck.value = []
  listChecked.value = []
  if (e) {
    dataSource.value.forEach((e: any) => {
      e.details.forEach((f: any) => {
        listChecked.value.push(f)
        modelCheck.value[f.norec] = true
        // modelCheck.value.push({ [f.norec]: true })
      });
    });
  }
  setTotalChecked()
}
function setTotalChecked() {

  let jml723 = 0
  let tol = 0
  item.DIBAYAR = tol
  if (item.DIKLAIM == null || item.DIKLAIM == 0)
    item.DIKLAIM = tol;
  item.TOTALValue = tol
  item.TOTAL = rupiah(tol)
  for (var i = 0; i < listChecked.value.length; i++) {
    const element = listChecked.value[i]
    if (element.iskronis == true) {
      jml723 = (parseFloat(element.total) / 30) * 7
    }
    tol = parseFloat(element.total) + tol
    item.DIBAYAR = tol
    item.TOTAL = rupiah(tol)
    item.TOTALValue = tol

    if (pasien.value.kelompokpasien.toLowerCase() == 'bpjs') {

      item.DIKLAIM_TXT = H.formatRupiah(parseFloat(tol - jml723), '')
      // item.DIKLAIM = tol - jml723;
    } else {
      // item.DIKLAIM = 0;
      item.DIKLAIM_TXT = 0;
    }
  }
  item.DIBAYAR = item.DIBAYAR - item.DEPOSIT - item.DIKLAIM
  item.PENGEMBALIAN = item.DEPOSIT > 0 ? (item.DIBAYAR < 0 ? (item.DIBAYAR * -1) : 0) : 0
}
function checkedItemsLABEL(e: any) {
  modelCheck.value[e] = !modelCheck.value[e]
  checkedItems()
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
  setTotalChecked()
}
function kembaliKeun() {
  window.history.back()
}

// const noAntrianFarmasi = (item: any) => {
//     let objSave = {
//       norec_pd: NOREC_PD,
//       noregistrasi: pasien.value.noregistrasi,
//     }
//     useApi().post('/farmasi/set-no-antrian-farmasi', objSave).then((response: any) => {
//       pasienByID(ID_PASIEN);  
//     }).catch((e: any) => {
//     })
// };


const hitungBiayaSementara = async () => {
  isFlafon.value = true
  const e = await useApi().get('/bridging/inacbgs/get-for-plafon?norec_pd='
    + NOREC_PD)
  if (e.set_claim_data == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Tidak ada')
    return
  }
  if (e.set_claim_data.metadata.nomor_sep == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Belum di isi')
    return
  }
  if (e.set_claim_data.data.diagnosa == null ||
    e.set_claim_data.data.diagnosa == '' ||
    e.set_claim_data.data.diagnosa == false) {
    isFlafon.value = false
    H.alert('info', 'Data Diagnosa Belum di isi')
    return
  }
  dataSourceINACBG.value = e.data
  isFlafon.value = true
  let json = []
  json.push(e.new_claim)
  json.push(e.set_claim_data)

  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {

    for (let x = 0; x < r.response.dataresponse.length; x++) {
      const element = r.response.dataresponse[x];
      if (element.dataresponse.metadata.code == 200) {
        await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.grouper] }).then(async (rr) => {

          let arrGroup = []
          for (let x = 0; x < rr.response.dataresponse.length; x++) {
            const elementx = rr.response.dataresponse[x];
            if (elementx.dataresponse.metadata.code == 200) {
              arrGroup.push({
                'nomor_sep': elementx.datarequest.data.nomor_sep,
                'inacbg_status': elementx.dataresponse.metadata.method,
                'dataresponse': elementx.dataresponse
              })
              break
            } else {
              H.alert('error', elementx.dataresponse.response.cbg.description)
            }
          }

          await saveGrouping(arrGroup, true)
          await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.delete_claim] }).then(async (xx) => {
            let arrStatus = []
            for (let x = 0; x < xx.response.dataresponse.length; x++) {
              const element = xx.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                arrStatus.push({
                  'nomor_sep': element.datarequest.data.nomor_sep,
                  'inacbg_status': null
                })
                break
                // H.alert('success', element.dataresponse.metadata.message)
              } else {
                H.alert('error', element.dataresponse.metadata.message)
              }
            }
            await saveStatus(arrStatus, true)
          })
        })
        break
      } else {
        if (element.dataresponse.metadata.message != 'Duplikasi nomor SEP') {
          H.alert('error', element.dataresponse.metadata.message)
        }
      }
    }

    isFlafon.value = false
  }, (error) => {
    isFlafon.value = false
  })
}
const saveGrouping = async (e: any, load: boolean) => {
  if (!e.length) return
  for (let i = 0; i < dataSourceINACBG.value.length; i++) {
    const element = dataSourceINACBG.value[i];
    for (var ii = 0; ii < e.length; ii++) {
      const elem2 = e[ii]
      if (element.nomor_sep == elem2.nomor_sep) {
        elem2.norec = element.norec
        elem2.jenis_rawat = element.jenis_rawat
      }
    }
  }
  let tarifINACB = 0
  for (var ii = 0; ii < e.length; ii++) {
    const elem2 = e[ii]
    let totaldijamin = 0
    let biayanaikkelas = 0
    if (elem2.jenis_rawat != 1) {
      totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
    } else {
      let hakkelas = elem2.dataresponse.response.kelas
      if (hakkelas == "kelas_1") {
        totaldijamin = elem2.dataresponse.tarif_alt[0].tarif_inacbg
      } else if (hakkelas == "kelas_2") {
        totaldijamin = elem2.dataresponse.tarif_alt[1].tarif_inacbg
      } else if (hakkelas == "kelas_3") {
        totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
      }

      biayanaikkelas = elem2.dataresponse.response.add_payment_amt ? elem2.dataresponse.response.add_payment_amt : 0
      if (biayanaikkelas < 0) {
        biayanaikkelas = 0
      }
    }
    let json = {
      'norec': elem2.norec,
      'totaldijamin': totaldijamin,
      'biayanaikkelas': biayanaikkelas,
      'inacbg_grouper': elem2.dataresponse
    }
    tarifINACB = totaldijamin
    await useApi().postBPJS('/bridging/inacbgs/save-grouping', json)

  }
  // if (load)
  // fetchDataINACBG(tarifINACB)
}
const fetchDataINACBG = async (tarifINACB: any) => {
  if (tarifINACB != undefined) {
    H.alert('success', 'Sukses')
  }
  item.inacbg_totalgrouper = tarifINACB
  item.DIKLAIM_TXT = tarifINACB

  setColorFlafon({
    'inacbg_totalgrouper': tarifINACB,
    'billing': item.BILLING,
  })
}

const setColorFlafon = (element: any) => {
  let ditanggung = parseFloat(element.inacbg_totalgrouper)
  let totaltagihan = parseFloat(element.billing);
  let presn = ditanggung * 0.1
  let totalPersen = ditanggung - presn

  if (ditanggung != 0 && totaltagihan >= ditanggung) {
    colorPLAFON.value = 'danger'
  } else if (ditanggung != 0 && totaltagihan >= totalPersen) {
    colorPLAFON.value = 'warning'
  } else {
    colorPLAFON.value = 'info'
  }
}
const saveStatus = async (e: any, load: boolean) => {
  if (!e.length) return
  let jsons = []
  for (let i = 0; i < dataSourceINACBG.value.length; i++) {
    const element = dataSourceINACBG.value[i];
    for (var ii = 0; ii < e.length; ii++) {
      const elem2 = e[ii]
      if (element.nomor_sep == elem2.nomor_sep) {
        elem2.norec = element.norec
        jsons.push(elem2)
      }
    }
  }
  if (jsons.length == 0) return

  await useApi().postBPJS('/bridging/inacbgs/save-status', { 'data': jsons }).then(async (r) => {
    // if (load)
    // fetchData()
    isFlafon.value = false
  }).catch((e: any) => {
    isFlafon.value = false
  })
}
const billing = () => {
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: NOREC_PD,
    },
  })
}
const fetchKelas = async () => {
  await useApi().get('/kasir/list-kelas').then(async (r) => {
    d_KelasNaik.value = r.kelas
  })
}
// const fetchKoding = async () => {
//   await useApi().get('/kasir/list-koding').then(async (r) => {
//     d_Koding.value = r.koding
//   })
// }

const fetchKoding = async (filter: any) => {
  await useApi().get(`kasir/list-koding?query=${filter.query}`
  ).then((response) => {
    d_Koding.value = response.koding
  })
}
const hitungBiayaIUR = async () => {
  isFlafon.value = true
  const e = await useApi().get('/bridging/inacbgs/get-for-plafon?norec_pd='
    + NOREC_PD)
  if (e.set_claim_data == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Tidak ada')
    return
  }
  if (e.set_claim_data.metadata.nomor_sep == null) {
    // isFlafon.value = false
    let nosepTEMP = '9999R9999999V000999'
    e.new_claim.data.nomor_sep = nosepTEMP
    e.set_claim_data.data.nomor_sep = nosepTEMP
    e.set_claim_data.metadata.nomor_sep = nosepTEMP
    e.grouper.data.nomor_sep = nosepTEMP
    e.delete_claim.data.nomor_sep = nosepTEMP

    // H.alert('info', 'Data SEP Belum di isi')
    // return
  }

  if (e.set_claim_data.data.jenis_rawat != 1) {
    isFlafon.value = false
    H.alert('error', 'IUR Bayar INACBG hanya untuk Rawat Inap')
    return
  }
  if (e.set_claim_data.data.diagnosa == null ||
    e.set_claim_data.data.diagnosa == '' ||
    e.set_claim_data.data.diagnosa == false) {
    isFlafon.value = false
    H.alert('info', 'Data Diagnosa Belum di isi')
    return
  }

  if (!item.kelasHak) {
    H.alert('info', 'Kelas Hak Harus di isi')
    return
  }
  if (!item.kelasPelayanan) {
    H.alert('info', 'Kelas Pelayanan/Naik Harus di isi')
    return
  }
  if (!item.upgrade_class_los) {
    H.alert('info', 'Kelas Pelayanan/Naik Harus di isi')
    return
  }


  e.set_claim_data.data.kelas_rawat = item.kelasHak.kode
  e.set_claim_data.data.upgrade_class_ind = "1"
  e.set_claim_data.data.upgrade_class_class = item.kelasPelayanan.namabpjs
  e.set_claim_data.data.upgrade_class_los = item.upgrade_class_los
  e.set_claim_data.data.upgrade_class_payor = "peserta"
  e.set_claim_data.data.add_payment_pct = ''
  if (item.kelasPelayanan.namabpjs == 'vip' || item.kelasPelayanan.namabpjs == 'vvip') {

    // if (e.data[0].belum_mapping.length > 0) {
    //   H.alert('error', '18 Variable tarif masih ada yang belum di mapping')
    //   detailTarif()
    //   return
    // }
  }
  dataSourceINACBG.value = e.data
  isFlafon.value = true
  let json = []
  json.push(e.new_claim)
  json.push(e.set_claim_data)

  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {

    for (let x = 0; x < r.response.dataresponse.length; x++) {
      const element = r.response.dataresponse[x];
      if (element.dataresponse.metadata.code == 200
      ) {
        if (element.datarequest.metadata.method == 'set_claim_data') {
          await lanjutIUR(e)
          break
        }
      } else {
        if (element.dataresponse.metadata.message != 'Duplikasi nomor SEP') {
          H.alert('error', element.dataresponse.metadata.message)
        }
        // else{
        // lanjutIUR(e)
        // }
      }
    }

    isFlafon.value = false
  }, (error) => {
    isFlafon.value = false
  })
}
const lanjutIUR = async (e: any) => {
  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.grouper] }).then(async (rr) => {

    let arrGroup = []
    for (let x = 0; x < rr.response.dataresponse.length; x++) {
      const elementx = rr.response.dataresponse[x];
      if (elementx.dataresponse.metadata.code == 200) {
        let tarifNAIKKELAS = 0;
        if (elementx.dataresponse.response.add_payment_amt != undefined) {
          // item.DIBAYAR = elementx.dataresponse.response.add_payment_amt
          item.iurBayar = elementx.dataresponse.response.add_payment_amt
          item.iurBayarValue = elementx.dataresponse.response.add_payment_amt
          HASILGROUPING.value = elementx.dataresponse.response.cbg.tariff
          if ((item.kelasPelayanan.namabpjs == 'vip' || item.kelasPelayanan.namabpjs == 'vvip') && HASILGROUPING.value != 0) {
            let KOEFISIEN_NAIK_DIATAS_KELAS1 = (e.data[0].totalbilling - HASILGROUPING.value) / HASILGROUPING.value * 100
            if (KOEFISIEN_NAIK_DIATAS_KELAS1 > 75) {
              KOEFISIEN_NAIK_DIATAS_KELAS1 = 75 // peraturan kemkes max koefisien naik 75%
            }
              // untuk naik dari kelas 2 ke VIP
            if(item.kelasHak.nama == 'Kelas 2'){
              const tarifINACB1 = elementx.dataresponse.tarif_alt[0].tarif_inacbg
              const tarifINACB2 = elementx.dataresponse.tarif_alt[1].tarif_inacbg
              const selisihTarif = tarifINACB1 - tarifINACB2
              const persentase = 0.75*tarifINACB1
              const akumulasi = parseFloat(tarifINACB2) + parseFloat(selisihTarif) + parseFloat(persentase)
              const riilCost = e.data[0].totalbilling
              console.log(tarifINACB1, tarifINACB2, selisihTarif, persentase, akumulasi, riilCost)
              if(riilCost < tarifINACB2){
                item.iurBayar = 0
                item.iurBayarValue = 0
                break
              }else{
                if(akumulasi < riilCost){
                  item.iurBayar = selisihTarif + persentase
                  item.iurBayarValue = selisihTarif + persentase
                  break
                }else{
                  item.iurBayar = riilCost - tarifINACB2
                  item.iurBayarValue = riilCost - tarifINACB2
                  break
                }
              }
            }
            setIURBAYAR_HITUNG_KOEFISIEN(KOEFISIEN_NAIK_DIATAS_KELAS1.toFixed(5), HASILGROUPING.value)
            break;
          } else {
            H.alert('info', 'Tambahan Biaya Yang Dibayar Pasien Untuk Naik/Turun Kelas : ' + H.formatRp(elementx.dataresponse.response.add_payment_amt, 'Rp.'))
          }
        }
        // else{
        //   H.alert('info', elementx.dataresponse.response.cbg.description)
        // }
        arrGroup.push({
          'nomor_sep': elementx.datarequest.data.nomor_sep,
          'inacbg_status': elementx.dataresponse.metadata.method,
          'dataresponse': elementx.dataresponse
        })
        break
      } else {
        H.alert('error', elementx.dataresponse.metadata.message)
      }
    }

    // await saveGrouping(arrGroup, true)
    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.delete_claim] }).then(async (xx) => {
      let arrStatus = []
      for (let x = 0; x < xx.response.dataresponse.length; x++) {
        const element = xx.response.dataresponse[x];
        if (element.dataresponse.metadata.code == 200) {
          arrStatus.push({
            'nomor_sep': element.datarequest.data.nomor_sep,
            'inacbg_status': null
          })
          break
          // H.alert('success', element.dataresponse.metadata.message)
        } else {
          H.alert('error', element.dataresponse.metadata.message)
        }
      }
      await saveStatus(arrStatus, true)
    })
  })
}
const setIURBAYAR_HITUNG_KOEFISIEN = (koefisien: any, klaim: any) => {
  let tambahanbiaya = klaim - klaim + (klaim * parseFloat(koefisien) / 100)
  tambahanbiaya = tambahanbiaya.toFixed(0)
  item.iurBayar = tambahanbiaya
  item.iurBayarValue = tambahanbiaya

  H.alert('info', 'Tambahan Biaya Yang Dibayar Pasien Untuk Naik/Turun Kelas : ' + H.formatRp(tambahanbiaya, 'Rp.'))
}
const saveIUR = () => {
  console.log('IUR BAYAR',item.iurBayarValue);
  if (item.iurBayarValue == null && item.iurBayarValue == undefined && item.iurBayarValue == '') {
    H.alert('error', 'Tambahan Biaya harus di isi')
    return
  }
  item.IURBAYAR = item.iurBayarValue
  modalIur.value = false
}
const detailTarif = async () => {
  isTarif18.value = true
  const x = await useApi().get('/bridging/inacbgs/get-for-plafon?norec_pd=' + NOREC_PD)
  isTarif18.value = false
  let e = x.data[0]
  tarif18.value = e.tarif_rs
  tarif18.value.totalmappingtarif = e.totalmappingtarif
  tarif18.value.totalbilling = e.totalbilling
  tarif18.value.belum_mapping = e.belum_mapping
  modalDetail.value = true
}
const fetchJenisPasien = (query: any) => {

}
const fetchKelompokPasien = async (filter: any) => {
  await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}
const fetchPenjamin = async (filter: any) => {
  let kelompokId = item.jenisPasienMultiPenjamin ? item.jenisPasienMultiPenjamin.value : ''
  await useApi().get(`kasir/tagihan-non-layanan/penjamin-by-kelompokpasien?id=${kelompokId}&query=${filter.query}&limit=10`).then((response) => {
    d_Penjamin.value = response
  })
}
const showModalMultiPenjamin = () => {
  modalMultiPenjamin.value = true
  item.jenisPasienMultiPenjamin = { value: pasien.value.objectkelompokpasienlastfk, label: pasien.value.kelompokpasien }
  item.totalHarusBayarMultiPenjamin = H.formatRupiah(item.DIBAYAR, "")
}
const hapusMultiPenjamin = (data: any) => {
  const existingIndex = d_SourceMultiPenjamin.value.findIndex((item: any) => data.no === item.no);
  if (existingIndex !== -1) {
    d_SourceMultiPenjamin.value.splice(existingIndex, 1);
  }
}

const showModalTanggalPulang = () => {
  var return_value = prompt("Sandi :");
  if( return_value === "rsbm") {
    modalModalTglPulang.value = true;
  }else {
    alert("Kata sandi salah")
    return;
  }
}

// const showModalBillingual = () => {
//   modalModalTglPulang.value = true;
// }

const addMultiPenjamin = () => {
  if (!item.jenisPasienMultiPenjamin) {
    H.alert("warning", "Pilih jenis pasien");
    return;
  }
  if (!item.penjaminfk) {
    H.alert("warning", "Pilih penjamin");
    return;
  }
  let total: any = 0;
  d_SourceMultiPenjamin.value.forEach((data: any, index: number) => {
    total += data.total;
  })
  const existingIndex = d_SourceMultiPenjamin.value.findIndex((data: any) => data.no === item.no);

  if (existingIndex !== -1) {
    d_SourceMultiPenjamin.value[existingIndex] = {
      no: item.no,
      kelompokpasien: item.jenisPasienMultiPenjamin.label,
      penjamin: item.penjaminfk.namarekanan,
      penjaminfk: item.penjaminfk.id,
      kelompokpasienfk: item.jenisPasienMultiPenjamin.value,
      total: H.unFormatRupiah(item.totalHarusBayarMultiPenjamin)
    };
    item.no = d_SourceMultiPenjamin.value.length + 1;
    console.log('ITEM NUMBER', item.no);
  } else {
    d_SourceMultiPenjamin.value.push({
      norec: '',
      no: d_SourceMultiPenjamin.value.length + 1,
      kelompokpasien: item.jenisPasienMultiPenjamin.label,
      penjamin: item.penjaminfk.namarekanan,
      penjaminfk: item.penjaminfk.id,
      kelompokpasienfk: item.jenisPasienMultiPenjamin.value,
      total: H.unFormatRupiah(item.totalHarusBayarMultiPenjamin)
    });
  }
  console.log("source Multi Penjamin", d_SourceMultiPenjamin.value);

  let totalFix: any = 0;
  d_SourceMultiPenjamin.value.forEach((data: any, index: number) => {
    totalFix += data.total;
  });
  item.totalHarusBayarMultiPenjamin = item.DIKLAIM;
}
const simpanMultiPenjamin = () => {
  let total: any = 0
  d_SourceMultiPenjamin.value.map((data: any, index: number) => {
    total += data.total
  })
  item.DIKLAIM_TXT = H.formatRupiah(total, "")
  modalMultiPenjamin.value = false
}
const edit = (data: any) => {
  item.no = data.no
  item.jenisPasienMultiPenjamin = { label: data.kelompokpasien, value: data.kelompokpasienfk }
  item.penjaminfk = { id: data.penjaminfk, namarekanan: data.penjamin }
  item.totalHarusBayarMultiPenjamin = H.formatRupiah(data.total, '')
}

const getMultiPenjamin = async () => {
  await useApi().get(`kasir/get-multipenjamin?noregistrasi=${pasien.value.noregistrasi}`).then((res) => {
    console.log("RES MULTI PENJAMIN", res);
    let penjamin = 0;
    item.TOTALTagihanIURValue = item.BILLING;
    res.forEach(element => {
      penjamin = parseInt(penjamin) + parseInt(element.totalppenjamin);
      if(element.namarekanan.indexOf('BPJS KESEHATAN') == -1) {
        item.TOTALTagihanIURValue = parseFloat(item.TOTALTagihanIURValue) - parseFloat(element.totalppenjamin)
      }
      d_SourceMultiPenjamin.value.push({
        norec: element.norec,
        no: d_SourceMultiPenjamin.value.length + 1,
        kelompokpasien: element.kelompokpasien,
        penjamin: element.namarekanan,
        penjaminfk: element.penjaminfk,
        kelompokpasienfk: element.kelompokpasienfk,
        total: H.unFormatRupiah(element.totalppenjamin)
      });
    });
    // item.totalHarusBayarMultiPenjamin = penjamin;
    if(penjamin > 0) {
      item.DIKLAIM = parseInt(penjamin);
      item.DIKLAIM_TXT = H.formatRupiah(parseInt(penjamin), '')
    }
    item.TOTALTagihanIUR = rupiah(item.TOTALTagihanIURValue);
    let resulttotal = parseInt(item.BILLING) - parseInt(penjamin);
    item.totalHarusBayarMultiPenjamin = H.formatRupiah(resulttotal, '');
  })
}

const simpanUpdatePulang = async() => {
  let objectSave = {
    tanggalpulang: item.updatetglpulang,
    norec_pd: NOREC_PD,
    namapasien: pasien.value.namapasien
  }
  isLoading.value = true
  await useApi().post('/kasir/update-tglpulang', objectSave).then((response: any) => {
    isLoading.value = false
    modalModalTglPulang.value = false
  }).catch((err) => {
    isLoading.value = false
  })

}

watch(
  () => item.DIKLAIM,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue > item.TOTALValue && item.inacbg_totalgrouper == null) {
        item.DIKLAIM = 0
      }
      item.DIBAYAR = item.TOTALValue - parseFloat(newValue) - item.DEPOSIT;
    }
  }
)
watch(
  () => item.DIKLAIM_TXT,
  (newValue, oldValue) => {
    item.DIKLAIM = H.unFormatRupiah(newValue)
  }
)

const changeKoding = () => {
  console.log("KODING CHANGED", item.koding)
  let kodedepartemen = ''
    if(pasien.value.iddepartemen == 16){
      kodedepartemen = 'RI'
    } else{
      kodedepartemen = 'RJ'
    }
    useApi().get(`/kasir/get-plafon?koding=${item.koding.kode}&departemen=${kodedepartemen}`).then(async (r) => {
      let maksiurbayar = 0
      item.totalPlafonValue = 0;
      if(r.length > 0) {
        resPlafon.value = r[0];
        if(item.kelasHak.kode == 1){
          item.totalPlafonValue = r[0].tarifkelas1
        } else if(item.kelasHak.kode == 2){
          item.totalPlafonValue = r[0].tarifkelas2
        } else if(item.kelasHak.kode == 3){
          item.totalPlafonValue = r[0].tarifkelas3
        }
        item.totalPlafon = rupiah(item.totalPlafonValue)
        if(item.isIURReal) {
          let kelashakdipilih = item.kelasHak.kode;
          let kelasnow = null;
          item.maxiurbayar = 0;
          console.log("ITEM KELAS PELAYANAN", item.kelasPelayanan)
          if(item.kelasPelayanan.id == 1) {
            kelasnow = "3"
          }else if (item.kelasPelayanan.id == 2) {
            kelasnow = "2"
          }else if (item.kelasPelayanan.id == 3) {
            kelasnow = "1"
          }else if (item.kelasPelayanan.id == 4) {
            kelasnow = "VIP"
          }else if (item.kelasPelayanan.id == 5) {
            kelasnow = "VVIP"
          }
          // item.kodingkelashak =  item.kelasHak.id;
          // item.kodingkelasnaik = item.kelasPelayanan.id;
          // item. 
          console.log("Kelas now", kelasnow)
          if(kelasnow != null && isNaN(parseInt(kelasnow)) == false ) {
            if(parseInt(kelasnow) < parseInt(kelashakdipilih)) {
              item.maxiurbayar = parseInt(r[0]['tarifkelas'+kelasnow]) - parseInt(r[0]['tarifkelas'+kelashakdipilih]);
              let harusdibayar = parseFloat(item.TOTALTagihanIURValue) - parseFloat(item.totalPlafonValue)
              let keuntungan = parseFloat(item.totalPlafonValue) - parseFloat(item.TOTALTagihanIURValue) > 0 ? parseFloat(item.totalPlafonValue) - parseFloat(item.TOTALTagihanIURValue) : 0;
              item.iurBayar = rupiah(harusdibayar > item.maxiurbayar ? item.maxiurbayar : (harusdibayar < 0 ? 0 : harusdibayar));
              item.iurBayarValue = harusdibayar > item.maxiurbayar ? item.maxiurbayar : (harusdibayar < 0 ? 0 : harusdibayar);
              item.bebanRS = rupiah(harusdibayar > item.iurBayarValue ? harusdibayar - item.iurBayarValue : 0)
              item.bebanRSValue = harusdibayar > item.iurBayarValue ? harusdibayar - item.iurBayarValue : 0
              item.keuntunganRS = rupiah(keuntungan);
              item.keuntunganRSValue = keuntungan              
            }
            // else {
            //   handlerIURBayar(item.isIURReal)
            // }
          }else {
            // handlerIURBayar(item.isIURReal);
            if(kelasnow == 'VIP' && item.kelasHak.kode != 1) {
              // item.maxiurbayar = parseFloat(0.75 * item.totalPlafonValue);
              // let harusdibayar = parseFloat(item.TOTALTagihanIURValue) - parseFloat(item.totalPlafonValue)
              // let keuntungan = parseFloat(item.totalPlafonValue) - parseFloat(item.TOTALTagihanIURValue) > 0 ? parseFloat(item.totalPlafonValue) - parseFloat(item.TOTALTagihanIURValue) : 0;
              // item.iurBayar = rupiah(harusdibayar > item.maxiurbayar ? item.maxiurbayar : (harusdibayar < 0 ? 0 : harusdibayar));
              // item.iurBayarValue = harusdibayar > item.maxiurbayar ? item.maxiurbayar : (harusdibayar < 0 ? 0 : harusdibayar);
              // item.bebanRS = rupiah(harusdibayar > item.iurBayarValue ? harusdibayar - item.iurBayarValue : 0)
              // item.bebanRSValue = harusdibayar > item.iurBayarValue ? harusdibayar - item.iurBayarValue : 0
              // item.keuntunganRS = rupiah(keuntungan);
              // item.keuntunganRSValue = keuntungan  
              // Harga Plafon Kelas bawaan
              let plafonLoncat = parseFloat(r[0].tarifkelas1);
              item.maxiurbayar = parseFloat(0.75 * plafonLoncat);

              let harusdibayar = parseFloat(item.TOTALTagihanIURValue) - plafonLoncat;
              let keuntungan = parseFloat(item.totalPlafonValue) - parseFloat(item.TOTALTagihanIURValue) > 0 ? parseFloat(item.totalPlafonValue) - parseFloat(item.TOTALTagihanIURValue) : 0;
              let hargaiur = plafonLoncat - item.totalPlafonValue + (harusdibayar > item.maxiurbayar ? item.maxiurbayar : (harusdibayar < 0 ? 0 : harusdibayar));
              item.iurBayar = rupiah(hargaiur);
              item.iurBayarValue = hargaiur
              item.bebanRS = rupiah(harusdibayar > item.iurBayarValue ? harusdibayar - item.iurBayarValue : 0)
              item.bebanRSValue = harusdibayar > item.iurBayarValue ? harusdibayar - item.iurBayarValue : 0
              item.keuntunganRS = rupiah(keuntungan);
              item.keuntunganRSValue = keuntungan  
            }else {
              handlerIURBayar(item.isIURReal)
            }
          }

        }
        // else {
        //   handlerIURBayar(item.isIURReal)
        // }
      }else {
          item.keuntunganRS = 0
          item.keuntunganRSValue = 0
          item.iurBayarValue = 0
          item.iurBayar = 0
          item.bebanRS = 0
          item.bebanRSValue = 0
      }
      
      
    })
}
watch(
  () => modalIur.value,
  (newValue, oldValue) => {
    if (newValue == false) {
      item.isIUR = false
    }
    // else {
    //   item.TOTALTagihanIUR = item.TOTALValue
    //   d_SourceMultiPenjamin.value.forEach((itemPenjamin: any) => {
    //     if(itemPenjamin?.penjamin && itemPenjamin.penjamin.indexOf('BPJS KESEHATAN') == -1) {
    //       item.TOTALTagihanIUR = parseFloat(item.TOTALTagihanIUR) - parseFloat(itemPenjamin.total)
    //     }
    //   })
    //   item.TOTALTagihanIURValue = item.TOTALTagihanIUR
    //   item.TOTALTagihanIUR = rupiah(item.TOTALTagihanIUR);
    // }
  }
)
watch(
  () => isMultiPenjamin.value,
  (newValue, oldValue) => {
    if(newValue)
      showModalMultiPenjamin()
  }
)

watch(
  () => [item.kelasHak, item.kelasPelayanan, item.upgrade_class_los, item.isIURReal],
  (newValue, oldValue) => {
    console.log("watcher");
    if(item.koding?.kode) {
      changeKoding();
    }
  }

)

function handlerIURBayar(newValue: bool) {
  let maksiurbayar = 0
  maksiurbayar = parseFloat(0.75 * item.totalPlafonValue)
  if(newValue) {
    if(parseFloat(item.totalPlafonValue) <= parseFloat(item.TOTALTagihanIURValue)) {
      if((parseFloat(item.TOTALTagihanIURValue) - parseFloat(item.totalPlafonValue)) < maksiurbayar){
        item.iurBayar = rupiah(parseFloat(item.TOTALTagihanIURValue) - parseFloat(item.totalPlafonValue))
        item.iurBayarValue = parseFloat(item.TOTALTagihanIURValue) - parseFloat(item.totalPlafonValue)
        item.bebanRS = 0
        item.bebanRSValue = 0
        item.keuntunganRSValue = 0
        item.keuntunganRS = 0
        
      } else{
        item.iurBayar = rupiah(maksiurbayar)
        item.iurBayarValue = maksiurbayar;
        let bebanNew = item.TOTALTagihanIURValue - item.totalPlafonValue - item.iurBayarValue
        item.bebanRS = rupiah(bebanNew)
        item.bebanRSValue = bebanNew
        item.keuntunganRS = 0
        item.keuntunganRSValue = 0
        // console.log('BEBAN RS', item.bebanRSValue)
      }
    } else{
      let tKeuntungan = parseFloat(item.totalPlafonValue) - parseFloat(item.TOTALTagihanIURValue)
      item.keuntunganRS = isNaN(tKeuntungan) ? 0 : rupiah(tKeuntungan)
      item.keuntunganRSValue = isNaN(tKeuntungan) ? 0 : tKeuntungan
      item.iurBayarValue = 0
      item.iurBayar = 0
      item.bebanRS = 0
      item.bebanRSValue = 0
    }
  }else {
    let kekurangan = parseFloat(item.TOTALTagihanIURValue) - parseFloat(item.totalPlafonValue); 
    // console.log("KURANGNYA BERAPAAAAAA", kekurangan)
    if(kekurangan < 0) {
      kekurangan = Math.abs(kekurangan);
      item.bebanRS = 0;
      item.bebanRSValue = 0;
      item.iurBayarValue = 0
      item.iurBayar = 0
      item.keuntunganRS = rupiah(kekurangan)
      item.keuntunganRSValue = kekurangan
    }else {
      item.bebanRS = rupiah(kekurangan);
      item.bebanRSValue = kekurangan;
      item.iurBayarValue = 0
      item.iurBayar = 0
      item.keuntunganRS = 0
      item.keuntunganRSValue = 0
    }
    // if((parseFloat(item.TOTAL) - parseFloat(item.totalPlafon)) < maksiurbayar) {
    //   item.iurBayar = 0
    //   item.keuntunganRS = 0
    // }
    // let rsm = parseFloat(item.TOTAL) - parseFloat(item.totalPlafon) - maksiurbayar;
    // console.log("beban rs", rsm);
  }
}

watch(
  () => item.isIURReal,
  // () => item.koding, 
  (newValue, oldValue) => {
    handlerIURBayar(newValue);
  }
)
onMounted(async () => {
  await pasienByID(ID_PASIEN)
  // getMultiPenjamin()
})
// qzService.connect()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/kasir/billing';
@import '/@src/scss/module/kasir/verifikasi-tagihan';

.form-layout.is-separate {
  width: 100%;
}

.form-layout .form-outer .form-body {
  padding: 0;
}

.field.label-v3>label {
  color: var(--light-text) !important;
}

.banking-dashboard-v2 .dashboard-card {
    border-radius: 0px !important;
}

.hr-dashboard .block-header {
    display: flex !important;
    border-radius: 16px;
    padding: 20px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);
}

</style>

