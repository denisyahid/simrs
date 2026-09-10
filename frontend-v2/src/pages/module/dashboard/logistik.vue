<template>
  <ConfirmDialog />
  <div class="business-dashboard hr-dashboard">
    <div class="columns is-multiline">
      <div class="column is-7 mt-3">
        <div class="block-header" style="background-color: #215E43;height: 22rem;">
          <div class="columns is-multiline">
            <div class="column is-6" style="padding-top: 5rem">
              <img src="/images/avatars/label/dashboard/logistik.png">
            </div>
            <div class="column is-6" style="padding-top: 6rem;">
              <span style="color:#F7F7F7"><i class="fas fa-dolly mr-3" aria-hidden="true" style="color:#F7F7F7"></i>
                Logistics</span>
              <h3 class="pt-3 pb-1 title-dash">
                Layanan Logistics</h3>
              <span style="color:#F7F7F7">Selamat Datang , {{ H.namaPegawai() }}</span>
              <VField class="column is-10  is-autocomplete-select p-0 mt-3">
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Pilih Ruangan" :searchable="true"
                    :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-5">
        <div class="dashboard-card is-gauge mt-3" style="height: 22rem;">
          <div class="column border-custom">
            <span style="font-weight: bold; font-size: 15px">Medis Non Medis
            </span>
          </div>
          <ApexChart height="220" style="height: 220px;" type="bar" :series="chartMedisNonMedis.series"
            :options="chartMedisNonMedis">
          </ApexChart>
        </div>
      </div>
    </div>
  </div>


  <div class="columns" style="margin-top:1rem">
    <div class="column is-7">
      <VCard style="height: 100px; width:99.9%"/>
      <VTabs class="slider-tri" slider selected="permintaan" :tabs="[
        { label: 'Permintaan', value: 'permintaan' },
        { label: 'Penerimaan', value: 'penerimaan' },
        { label: 'Distribusi', value: 'distribusi' },
      ]" style="margin-top:-90px;padding:5px">
        <template #tab="{ activeValue }">
          <p v-if="activeValue === 'permintaan'">
            <VCard style="
              border-top-right-radius: unset;
              padding-bottom: 0px;
              margin-top: -16px;
              border-top-left-radius:unset;
              border-top-style: none;
              margin-left: -6px;
              width: 101%;
              padding-top: 0px; 
              margin-bottom: 10px;">
              
             <VButton color="primary" RouterLink :to="{ name: 'module-logistik-order-barang' }" raised
                style="float:right; top: -2.8rem;padding: 15px;font-size: 14px;">
                <i class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Order Barang
              </VButton>
              <div class="search-menu" style="margin-bottom : 1rem;margin-top:-14px">
                <VDatePicker v-model="item.qrangeDate" is-range color="pink" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                      </VControl>
                      <VControl>
                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                      </VControl>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>

                <div class="search-location" style="padding-right: 0px; margin-right: -15px;">
                  <i class="iconify" data-icon="feather:code"></i>
                  <input type="text" placeholder="No Order" v-model="item.noorder" style="margin-right:20px" />
                </div>

                <VButton color="primary" raised class="search-button" @click="fetchDataOrder()"
                  style="height:57px;color:whitesmoke;font-size: 14px;">
                  Cari Data </VButton>
              </div>

            </VCard>
          <div v-if="dataOrder.loading">
            <div v-for="key in 4" :key="key" class="column is-12">
              <VCard>
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner placeload-wrap">
                    <div class="columns">
                      <div class="column is-1">
                        <VPlaceloadAvatar rounded="sm" />
                      </div>
                      <div class="column">
                        <div class="column mb-4 pt-0">
                          <VPlaceload class="mx-2" width="30%" />
                        </div>
                        <div class="columns pl-5">
                          <VPlaceload class="mx-2" width="30%" />
                          <VPlaceload class="mx-2" width="30%" />
                          <VPlaceload class="mx-2" width="30%" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>
          </div>

          <div v-else class="column is-12 p-0" style="overflow: scroll;height: 44rem;">
            <!--  -->
            <div class="column is-12 p-0 pb-3" v-if="orderLength > 0" v-for="(item) in dataOrder" :key="item.id">
              <VCard>
                <div class="columns is-multiline">
                  <div class="column is-1" style="padding-top:37px">
                    <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared
                      bordered />
                  </div>
                  <div class="column is-11" style="padding-left: 23px;">
                    <div class="columns is-multiline">
                      <div class="column is-6 pb-0">
                        <label style="font-weight:400">Status</label>
                        <h3 class="field mt-1">{{ item.status }}</h3>
                      </div>
                      <div class="column is-6" style="text-align: end;">
                        <VDropdown icon="feather:more-vertical" spaced right>
                          <template #content>
                            <a role="menuitem" class="dropdown-item is-media" @click="detailPermintaan(item)">
                              <div class="icon">
                                <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Detail</span>
                                <span>Untuk melihat data </span>
                              </div>
                            </a>
                            <a role="menuitem" @click="kirimOrder(item)" class="dropdown-item is-media"
                              v-if="item.status != 'Kirim Order Barang' && item.statusorder == 'Belum Kirim' || item.statusorder == 'Batal Kirim'">
                              <div class="icon">
                                <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Kirim</span>
                                <span>Untuk Mengirim Barang</span>
                              </div>
                            </a>

                            <a role="menuitem" @click="verifOrder(item)" class="dropdown-item is-media"
                              v-if="item.statusorder == 'Sudah Kirim' && item.status == 'Kirim Order Barang'">
                              <div class="icon">
                                <i class="fas fa-check-circle" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Verifikasi</span>
                                <span>Verifikasi Terima Barang</span>
                              </div>
                            </a>
                            <a role="menuitem" class="dropdown-item is-media" @click="editKirimBarang(item)"
                              v-if="item.status == 'Terima Order Barang' && item.statusorder == 'Sudah Kirim'">
                              <div class="icon">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Edit</span>
                                <span>Untuk merubah Data Dikirim </span>
                              </div>
                            </a>
                            <a role="menuitem" class="dropdown-item is-media" @click="editOrder(item)"
                              v-if="item.status == 'Kirim Order Barang' && item.statusorder == 'Belum Kirim'">
                              <div class="icon">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Edit</span>
                                <span>Untuk merubah data Order</span>
                              </div>
                            </a>

                            <a role="menuitem" class="dropdown-item is-media"
                              v-if="item.status == 'Terima Order Barang' && item.statusorder == 'Sudah Kirim'"
                              @click="DialogConfirmOrder(item, 'batal kirim')">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                              </div>
                              <div class="meta">
                                <span>Remove</span>
                                <span>Batal Kirim</span>
                              </div>
                            </a>
                            <a role="menuitem" class="dropdown-item is-media"
                              v-if="item.status == 'Kirim Order Barang' && item.statusorder == 'Belum Kirim'"
                              @click="DialogConfirmOrder(item, 'hapus order')">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                              </div>
                              <div class="meta">
                                <span>Remove</span>
                                <span>Hapus Order</span>
                              </div>
                            </a>
                          </template>
                        </VDropdown>
                      </div>
                    </div>
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <label style="font-weight:400">Unit Pengorder</label>
                        <h3 class="field mt-1">{{ item.ruanganAsal }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="font-weight:400">Tanggal order</label>
                        <h3 class="field mt-1">{{ item.tglOrder }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="text-align: center;">No Order</label>
                        <h3 class="field mt-1">{{ item.noorder }}</h3>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="column is-11" style="padding-left: 23px;">
                    <label style="font-wight:400">Status</label>
                    <h3 class="field mt-1">{{ item.status }}</h3>
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <label style="font-wight:400">Unit Pengorder</label>
                        <h3 class="field mt-1">{{ item.ruanganTujuan }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="font-wight:400">Tanggal order</label>
                        <h3 class="field mt-1">{{ item.tglOrder }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="text-align: center;">No Order</label>
                        <h3 class="field mt-1">{{ item.noorder }}</h3>
                      </div>
                    </div>
                  </div> -->
                </div>
              </VCard>
            </div>

            <div v-else class="p-0" style="margin-top: -58px;">
              <div class="column p-0 m-0" style="display: flex;justify-content: center;">
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                  style="max-width: 38%;margin-top: 2rem;" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                  style="max-width: 38%;margin-top: 2rem;" />
              </div>
              <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
                Daftar Permintaan Saat ini tidak tersedia</h3>
            </div>
          </div>
          </p>

          <p v-else-if="activeValue === 'penerimaan'">
            <VCard style="
              border-top-right-radius: unset;
              padding-bottom: 0px;
              margin-top: -16px;
              border-top-left-radius:unset;
              border-top-style: none;
              margin-left: -6px;
              width: 101%;
              padding-top: 0px; 
              margin-bottom: 10px;">

              <VButton color="primary" RouterLink :to="{ name: 'module-logistik-form-penerimaan-barang-suplier' }"
                raised style="float:right;top: -2.6rem;padding: 15px;font-size: 14px;"><i class="fas fa-plus mr-3 p-0"
                  aria-hidden="true"></i>Tambah Penerimaan</VButton>
              <div class="search-menu" style="margin-bottom : 1rem;margin-top:-14px">
                <VField class="mt-3">
                  <VDatePicker v-model="item.datePenerimaan" is-range color="pink" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" v-on="inputEvents.start" />
                        </VControl>
                        <VControl>
                          <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                        </VControl>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.end" v-on="inputEvents.end" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>

                <div class="search-location" style="padding-right: 0px; margin-right: -15px;">
                  <i class="iconify" data-icon="feather:code"></i>
                  <input type="text" placeholder="Struk Kirim" v-model="item.nostruk" style="margin-right:20px" />
                </div>

                <VButton color="primary" raised class="search-button" @click="fetchDataPenerimaanBarang()"
                  style="height:57px;color:whitesmoke;font-size: 14px;">
                  Cari Data </VButton>
              </div>

            </VCard>
          <div v-if="dataPenerimaan.loading">
            <div v-for="key in 4" :key="key" class="column is-12">
              <VCard>
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner placeload-wrap">
                    <div class="columns">
                      <div class="column is-1">
                        <VPlaceloadAvatar rounded="sm" />
                      </div>
                      <div class="column">
                        <div class="column mb-4 pt-0">
                          <VPlaceload class="mx-2" width="30%" />
                        </div>
                        <div class="columns pl-5">
                          <VPlaceload class="mx-2" width="30%" />
                          <VPlaceload class="mx-2" width="30%" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>
          </div>

          <div v-else class="column is-12 p-0" style="overflow: scroll;height: 44rem;">

            <div class="column is-12 p-0 pb-3" v-if="dataPenerimaan.lengthData > 0" v-for="(item) in dataPenerimaan"
              :key="item.id">
              <VCard>
                <div class="columns is-multiline">
                  <div class="column is-1" style="padding-top:37px;">
                    <VAvatar size="medium" picture="/images/avatars/svg/lab.svg" style="cursor: pointer;"
                      v-tooltip.bottom.center="'Detail'" squared bordered @click="detailPenerimaan(item)" />
                  </div>
                  <div class="column is-11" style="padding-left: 23px;">
                    <label style="font-weight:400">Rekanan</label>
                    <h3 class="field mt-1">{{ item.namarekanan }}</h3>
                    <div class="columns">
                      <div class="column is-3">
                        <label style="font-weight:400">Nomer Terima</label>
                        <h3 class="field mt-1">{{ item.nostruk }}</h3>
                      </div>
                      <div class="column is-1">
                        <label style="font-weight:400">Item</label>
                        <h3 class="field mt-1">{{ item.jmlitem }}</h3>
                      </div>
                      <div class="column is-5">
                        <label style="font-weight:400">Tanggal Diterima</label>
                        <h3 class="field mt-1">{{ item.tglDiterima }}</h3>
                      </div>
                      <div class="column" style="text-align:right">
                        <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                          raised v-tooltip.top="'Aksi'" @click="toggle($event, item)">
                        </VIconButton>
                      </div>
                      <!-- <div class="column">
                        <VIconButton v-tooltip.top="'Aksi'" icon="fas fa-ellipsis-v" @click="toggle($event, item)" color="warning" raised
                          circle>
                        </VIconButton>
                            <OverlayPanel ref="op">
                              <VButtons>
                                <VButton color="info" outlined @click="gotoPageEdit(selected)">
                                  <i class="fas fa-pen-square mr-2" aria-hidden="true"></i> Edit
                                </VButton>
                                <VButton color="danger" raised @click="dialogConfirm(selected)" :loading="dataSource.loadDelete">
                                  <i class="fas fa-times-circle mr-2" aria-hidden="true"></i> Hapus
                                </VButton>
                              </VButtons>
                            </OverlayPanel>
                        <VIconButton v-tooltip.bottom.left="'Retur'" icon="fas fa-undo" color="info" raised circle
                          class="mr-2" @click="returPenerimaan(item)">
                        </VIconButton>
                        <VIconButton v-tooltip.bottom.center="'Edit Barang'" icon="feather:edit" color="warning" raised
                          circle class="mr-2" @click="editPenerimaan(item)">
                        </VIconButton>
                        <VIconButton v-tooltip.bottom.right="'Batal Kirim Barang'" icon="lnir lnir-cross-circle"
                          color="danger" raised circle class="mr-2" @click="dialogConfirmPenerimaan(item)">
                        </VIconButton>
                      </div> -->
                    </div>
                  </div>
                </div>
              </VCard>
            </div>

            <div v-else class="p-0">
              <div class="column p-0 m-0" style="display: flex;justify-content: center;">
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                  style="max-width: 38%;" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                  style="max-width: 38%;" />
              </div>
              <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">Data
                yang dicari tidak ada</h3>
            </div>

          </div>

          </p>

          <p v-else-if="activeValue === 'distribusi'">
            <VCard style="
              border-top-right-radius: unset;
              padding-bottom: 0px;
              margin-top: -16px;
              border-top-left-radius:unset;
              border-top-style: none;
              margin-left: -6px;
              width: 101%;
              padding-top: 0px; 
              margin-bottom: 10px;">
              <VButton color="primary" raised style="float:right;top: -2.6rem;padding: 15px;font-size: 14px;"
                RouterLink :to="{ name: 'module-logistik-distribusi-barang' }"><i class="fas fa-plus mr-3 p-0"
                  aria-hidden="true"></i>Kirim
                Barang</VButton>
              <div class="search-menu" style="margin-bottom : 1rem;margin-top:-14px">
                <VField class="mt-3">
                  <VDatePicker v-model="item.dateDistribusi" is-range color="pink" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" v-on="inputEvents.start" />
                        </VControl>
                        <VControl>
                          <VButton static icon="feather:arrow-right" />
                        </VControl>
                        <VControl subcontrol icon="feather:calendar">
                          <VInput :value="inputValue.end" v-on="inputEvents.end" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>

                <div class="search-location" style="padding-right: 0px; margin-right: -15px;">
                  <i class="iconify" data-icon="feather:code"></i>
                  <input type="text" placeholder="Struk Kirim" v-model="item.nokirim" style="margin-right:20px" />
                </div>

                <VButton color="primary" raised class="search-button" @click="fetchDataDistribusi()"
                  style="height:57px;color:whitesmoke;font-size: 14px;">
                  Cari Data </VButton>
              </div>

            </VCard>
          <div v-if="dataDistribusi.loading">
            <div v-for="key in 4" :key="key" class="column is-12">
              <VCard>
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner placeload-wrap is-flex">
                    <VPlaceloadAvatar rounded="sm" />
                    <VPlaceloadText width="70%" last-line-width="70%" class="mx-2 mt-2 ml-5" />
                    <VPlaceloadText width="70%" last-line-width="70%" class="mx-2 mt-2" />
                  </div>
                </div>
              </VCard>
            </div>
          </div>

          <div class="column is-12 p-0 m-0" v-else style="height: 46rem; overflow-y: scroll">
            <div class="column is-12 p-0 mb-4" v-for="items in dataDistribusi" :key="items.id">
              <VCard>
                <div class="column is-12 p-0">
                  <div class="columns">
                    <div class="column is-1" style="padding-top:37px">
                      <VAvatar size="medium" picture="/images/avatars/svg/lab.svg" squared bordered />
                    </div>
                    <div class="column is-10" style="cursor: pointer;" @click="detailDistribusi(items)">
                      <div class="columns">
                        <div class="column is-5">
                          <div class="column is-12 pb-0">
                            <label style="font-weight:400">Tanggal Kirim</label>
                            <h3 class="field mt-1">{{ items.tglkirim }}</h3>
                          </div>
                          <div class="column is-12">
                            <label style="font-weight:400">Ruangan Asal</label>
                            <h3 class="field mt-1">{{ items.namaruanganasal }}</h3>
                          </div>
                        </div>
                        <div class="column is-5 ml-3">
                          <div class="column is-12 pb-0">
                            <label style="font-weight:400">No Struk</label>
                            <h3 class="field mt-1">{{ items.nostruk }}</h3>
                          </div>
                          <div class="column is-12">
                            <label style="font-weight:400">Ruangan Tujuan</label>
                            <h3 class="field mt-1">{{ items.namaruangantujuan }}</h3>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="column pt-3 pl-0">
                      <VButtons>
                        <TdaftarDistribusi @batalKirim="batalKirim(items)" @editItems="editDis(items)"
                          @cetakBuktiKirim="cetakBuktiKirim(items)" @gotoRetur="gotoRetur(items)" />
                        <!-- <VIconButton color="warning" outlined circle icon="feather:edit"
                          v-tooltip.bottom.left="'Ubah Distribusi'" @click="editDis(items)" />
                        <VIconButton color="danger" outlined circle icon="fas fa-times"
                          v-tooltip.bottom.left="'Batal Kirim'" @click="batalKirim(items)" />
                        <VIconButton color="primary" circle outlined icon="fas fa-print"
                          v-tooltip.bottom.left="'Cetak Bukti'" /> -->
                      </VButtons>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>
          </div>


          <div v-if="dataDistribusi.lengthData == 0" class="p-0">
            <div class="column p-0 m-0" style="display: flex;justify-content: center;">
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                style="max-width: 38%;margin-top: 2rem;" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                style="max-width: 38%;margin-top: 2rem;" />
            </div>
            <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
              Daftar Permintaan Saat ini tidak tersedia</h3>
          </div>
          </p>

        </template>
      </VTabs>
      <OverlayPanel ref="op">
        <VButtons class="mb-1">
          <VButton color="info" outlined @click="editPenerimaan(selected)" class="custom">
            <i class="fas fa-pen-square mr-2" aria-hidden="true"></i> Edit
          </VButton>
          <VButton color="primary" outlined @click="editHead(selected)" :loading="btnLoadEdit" class="custom">
            <i class="fas fa-pen-square mr-2" aria-hidden="true"></i> Edit Header
          </VButton>
          <VButton color="warning" outlined @click="returPenerimaan(selected)" class="custom">
            <i class="fas fa-undo mr-2" aria-hidden="true"></i> Retur
          </VButton>
        </VButtons>
        <VButtons style="justify-content: space-evenly;">
          <VButton color="danger" outlined @click="dialogConfirmPenerimaan(selected)" class="custom">
            <i class="fas fa-times-circle mr-2" aria-hidden="true"></i> Batal Terima
          </VButton>
          <VButton color="black" outlined @click="cetakBuktiPopUp(selected)" class="custom">
            <i class="fas fa-print mr-2" aria-hidden="true"></i> Cetak Bukti
          </VButton>
        </VButtons>
      </OverlayPanel>
    </div>

    <div class="column is-5 pt-0">
      <UIWidget class="search-widget">
        <template #body>
          <div class="field">
            <div class="control">
              <input v-model="item.namaproduk" class="input custom-text-filter" placeholder="Cari Persediaan..."
                v-on:keyup.enter="fetchStokProduk(item.namaproduk)" />
              <button class="searcv-button" @click="fetchStokProduk(item.namaproduk)">
                <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
              </button>
            </div>
          </div>
        </template>
      </UIWidget>

      <div class="column is-multiline p-0" style="max-height:210px;overflow: auto;">
        <div v-if="dataStokProduk.loading">
          <div v-for="key in 4" :key="key" class="column is-12">
            <VCard>
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner placeload-wrap">
                  <div class="columns">
                    <div class="column is-1">
                      <VPlaceloadAvatar rounded="sm" />
                    </div>
                    <div class="column">
                      <div class="column pt-0">
                        <VPlaceload class="mx-2" width="70%" />
                      </div>
                      <div class="column pt-2 pb-0">
                        <VPlaceload class="mx-2" width="60%" />
                      </div>
                      <div class="column pb-0">
                        <VPlaceload class="mx-2" width="40%" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
        </div>
        <div class="column is-12 p-0 mb-2" v-for="products in dataSourcefiltered" :key="products.norec">
          <VCard>
            <div class="columns">
              <div class="column is-3 p-0 ml-3 mt-3">
                <VAvatar size="medium" picture="/images/avatars/icon/ic_generic.png" color="primary" squared bordered />
              </div>
              <div class="column p-0">
                <h3 class="field" style="font-weight: 600; margin-bottom: 0px;">{{ products.namaproduk }}</h3>
                <span class="field" style="font-weight: 300;color: var(--light-text);">Jenis : {{ products.jenis }} |
                  Stok
                  : {{ products.stok }} Pcs</span>
                <!-- <VButton color="primary" raised style="display:flex;margin-top: 8px;">Kartu Stock <i
                    class="fas fa-print ml-3" aria-hidden="true"></i></VButton> -->
              </div>
            </div>
          </VCard>
        </div>
      </div>


      <div class="dashboard-card is-gauge mt-3">
        <div class="columns is-multiline" style="margin:0px;padding:0px">
          <div class="column is-8" style="margin:0px;padding:0px">
            <h4 class="dark-inverted" style="font-weight: bold; font-size: 15px">Jumlah Permintaan tiap Ruangan dalam 1
              Bulan</h4>
          </div>
          <div class="column m-0 p-0" style="display: flex;justify-content: end;">
            <VTag color="warning" :label="dateNow" rounded elevated
              style="font-weight: 600; margin-top:10px; margin-left:20px" />
          </div>
        </div>
        <ApexChart id="apex-chart-18" :height="265" :type="'donut'" :series="chartRNG.series" :options="chartRNG" />
      </div>
    </div>

  </div>

  <VModal :open="modalDetailPenerimaan" title="Detail Penerimaan" size="big" actions="right"
    @close="modalDetailPenerimaan = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column is-12 p-0">
        <VCard color="primary">
          <div class="columns ismultiline">
            <div class="column is-2">
              <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
            </div>
            <div class="column is-3">
              <div class="mb-4">
                <label for="TGL Struk " class="labelin">No Faktur</label>
                <h3>{{ item.noFaktur }}</h3>
              </div>
            </div>

            <div class="column is-3">
              <div class="mb-4">
                <label for="No Struk" class="labelin">Supplier</label>
                <h3>{{ item.suplayer }}</h3>
              </div>
            </div>

            <div class="column is-3 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Nama Ruangan</label>
                <h3>{{ item.namaruangan }}</h3>
              </div>
            </div>

          </div>
        </VCard>
      </div>

      <form class="modal-form">
        <DataTable :value="sourceDetailPenerimaan" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          :loading="dataSourceDetailSuplier.loading">
          <Column field="no" header="No"></Column>
          <Column field="namaproduk" header="Nama Produk"></Column>
          <Column field="satuan" header="Satuan Standar" :sortable="true"></Column>
          <Column field="harga" header="Harga Satuan"></Column>
          <Column field="jumlah" header="Qty" :sortable="true"></Column>
          <Column field="hargadiscount" header="Diskon" :sortable="true"></Column>
          <Column field="hargappn" header="PPN"></Column>
          <Column field="total" header="Total"></Column>
          <Column field="nobatch" header="nobatch"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalDetailPermintaan" title="Detail Permintaan" size="big" actions="right"
    @close="modalDetailPermintaan = false" cancelLabel="Tutup">
    <template #content>

      <div class="column is-12 p-0">
        <VCard color="primary">
          <div class="columns ismultiline">
            <div class="column is-2">
              <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
            </div>
            <div class="column is-3">
              <div class="mb-4">
                <label class="labelin">
                  {{ dataDetail.status == 'Terima Order Barang' ? 'Ruangan Pengirim' : 'Ruangan Pengorder'}}
                </label>
                <h3>{{ dataDetail.status == 'Terima Order Barang' ? dataDetail.ruanganTujuan : dataDetail.ruanganAsal }}
                </h3>
              </div>
            </div>

            <div class="column is-3">
              <div class="mb-4">
                <label class="labelin">Ruangan Tujuan</label>
                <h3>{{ dataDetail.status == 'Terima Order Barang' ? dataDetail.ruanganAsal : dataDetail.ruanganTujuan }}
                </h3>
              </div>
            </div>

            <div class="column is-2">
              <div class="mb-4">
                <label class="labelin">Tanggal Permintaan</label>
                <h3>{{ dataDetail.tglOrder }}</h3>
              </div>
            </div>

            <div class="column is-2 pl-3">
              <div class="mb-4">
                <label class="labelin" style="display:block">Jenis Kirim</label>
                <h3>{{ dataDetail.jeniskirim }}</h3>
              </div>
            </div>

          </div>
        </VCard>
      </div>
      <form class="modal-form">
        <DataTable :value="DSPermintaan" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
          <Column field="no" header="No"></Column>
          <Column field="namaproduk" :sortable="true" header="Nama Produk"></Column>
          <Column field="qtyproduk" header="Qty Produk"></Column>
          <Column field="satuanstandar" header="Satuan"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalDetailDistribusi" title="Detail Distribusi" size="big" actions="right"
    @close="modalDetailDistribusi = false" cancelLabel="Tutup">
    <template #content>

      <div class="column is-12 p-0">
        <VCard color="primary">
          <div class="columns ismultiline">
            <div class="column is-1">
              <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
            </div>
            <div class="column is-1">
              <div class="mb-4">
                <label for="TGL Struk " class="labelin">TGL Struk</label>
                <h3>{{ item.tglstruk }}</h3>
              </div>
            </div>

            <div class="column is-2">
              <div class="mb-4">
                <label for="No Struk" class="labelin">No Struk</label>
                <h3>{{ item.nostruk }}</h3>
              </div>
            </div>

            <div class="column is-1" style="margin-left: -39px;">
              <div class="mb-4">
                <label for="Item" class="labelin">Item</label>
                <h3>{{ item.totalItem }}</h3>
              </div>
            </div>

            <div class="column is-2 pl-0" style="margin-left: -32px;">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Ruang Asal</label>
                <h3>{{ item.ruanganasal }}</h3>
              </div>
            </div>

            <div class="column is-2 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Ruang Tujuan</label>
                <h3>{{ item.ruangantujuan }}</h3>
              </div>
            </div>

            <div class="column is-2 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Petugas</label>
                <h3>{{ item.petugas }}</h3>
              </div>
            </div>

          </div>
        </VCard>
      </div>

      <form class="modal-form">
        <DataTable :value="dataSourceDetailDistribusi" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          :loading="dataSourceDetailDistribusi.loading">
          <Column field="no" header="No"></Column>
          <Column field="namaproduk" header="Nama Produk"></Column>
          <Column field="kdproduk" header="KD Produk"></Column>
          <Column field="satuanstandar" header="Satuan Standar"></Column>
          <Column field="qtyproduk" header="Qty" :sortable="true"></Column>
          <Column field="qtyprodukretur" header="Qty Retur" :sortable="true"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalBatalKirim" title="Batal Kirim" size="medium" actions="right" @close="modalBatalKirim = false"
    cancelLabel="Tutup">
    <template #content>

      <div class="columns is-multiline">
        <div class="column is-12">

          <div class="column is-5" style="margin-left:-1rem; margin-bottom: -2rem;">
            <img src="/images/avatars/label/dashboard/logistik.png" style="width: 80%;">
          </div>
          <div class="column is-8" style="margin-top: -9rem; margin-left: 12rem;">
            <span style="margin-bottom:1rem;font-weight: bold; font-size: 12px; font-family: var(--font-alt);">Alasan
              Pembatalan
            </span>
            <br />

            <VField>
              <VControl>
                <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4"
                  placeholder="Alasan Pembatalan Pengiriman Barang" autocomplete="off" autocapitalize="off"
                  spellcheck="true" />
              </VControl>
            </VField>
          </div>

        </div>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:plus" color="primary" @click="saveBatal()" :loading="isLoading" raised>Simpan
      </VButton>
    </template>
  </VModal>

  <Dialog v-model:visible="modalEditHead" header="Edit Header" :style="{ width: '70vw' }" modal>
    <VTabs slider type="rounded" selected="penerimaan" :tabs="[
      { label: 'Penerimaan', value: 'penerimaan' },
      { label: 'Faktur', value: 'faktur' },
      { label: 'Data PO', value: 'po' },
    ]">
      <template #tab="{ activeValue }">
        <p v-if="activeValue === 'penerimaan'">
        <div class="column is-12">
          <div class="columns">
            <div class="column is-4">
              <VField>
                <VLabel class="required-field">Tanggal Terima</VLabel>
                <Calendar v-model="item.tglTerima" showIcon iconDisplay="input" dateFormat="dd/mm/yy" disabled
                  style="font-weight:bold" />
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Gudang</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.gudang" :options="d_Gudang" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Gudang" style="width: 100%;font-weight:bold" :filter="true" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai Penerima</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.penerimaan" :options="d_Pegawai" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Pegawai Penerima" style="width: 100%;font-weight:bold;" :filter="true"
                    disabled />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-3 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Kelompok Barang</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.kelompokbarang" :options="d_kelompokBarang" optionLabel="label"
                    class="is-rounded" placeholder="Pilih Kelompok Barang" style="width: 100%;font-weight:bold"
                    :filter="true" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Sumber Dana</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.sumberdana" :options="d_SumberDana" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Sumber Dana" style="width: 100%;font-weight:bold" :filter="true" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6 pt-0" v-if="item.kaskecil == true">
              <div class="columns">
                <div class="column is-6">
                  <VField>
                    <VLabel class="required-field">No Bukti</VLabel>
                    <VControl>
                      <input v-model="item.noBuktiKK" type="text" class="input is-rounded" placeholder="No Dokumen"
                        disabled style="font-weight:bold" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="columns" v-if="item.kaskecil == true">
            <div class="column pt-1 is-3">
              <VDatePicker v-model="item.tanggalKK" color="green" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel class="required-field">Tanggal</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Ruangan</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.ruanganKK" :options="d_Gudang" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;font-weight:bold" :filter="true" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.pegawaiKK" :options="d_komit" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;font-weight:bold" :filter="true" disabled />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        </p>

        <p v-else-if="activeValue === 'faktur'">
        <div class="column is-12">
          <div class="columns">
            <div class="column is-2">
              <VField>
                <VLabel class="required-field">Tanggal Faktur</VLabel>
                <Calendar v-model="item.tglFaktur" showIcon iconDisplay="input" dateFormat="dd/mm/yy" />
              </VField>
            </div>
            <div class="column is-5">
              <VField>
                <VLabel class="required-field">No Faktur</VLabel>
                <VControl>
                  <input v-model="item.noFaktur" type="text" class="input is-rounded" placeholder="No Dokumen" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2 p-0" style="margin-top: 2rem;">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox label="otomatis" @change="noSurat(item)" v-model="item.noOtom" color="info" square />
                </VControl>
              </VField>
            </div>
            <div class="column p-0" style="margin-top: 2rem;">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="item.nothingFaktur" label="Belum ada no faktur" @change="noSurat(item)"
                    color="info" square />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-5">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Nama Suplier</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.supplier" :options="d_suplier" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Suplier" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>Tanggal Jatuh Tempo</VLabel>
                <Calendar v-model="item.tglTempo" showIcon iconDisplay="input" dateFormat="dd/mm/yy" />
              </VField>
            </div>
          </div>
        </div>
        </p>

        <p v-else-if="activeValue === 'po'">
        <div class="column is-12">
          <div class="columns">
            <div class="column is-3">
              <VField>
                <VLabel>Tanggal</VLabel>
                <Calendar v-model="item.tanggalPo" showIcon iconDisplay="input" dateFormat="dd/mm/yy" />
              </VField>
            </div>
            <div class="column is-5">
              <VField>
                <VLabel>No Usulan</VLabel>
                <VControl>
                  <input v-model="item.nousulan" type="text" class="input is-rounded" placeholder="No Usulan..." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-5 pt-3">
              <VField>
                <VLabel>Nama Pengadaan</VLabel>
                <VControl>
                  <input v-model="item.namapengadaan" type="text" class="input is-rounded"
                    placeholder="Nama Pengadaan..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-7 pt-3">
              <VField>
                <VLabel>No Kontrak</VLabel>
                <VControl>
                  <input v-model="item.nokontrak" type="text" class="input is-rounded" placeholder="No Kontrak" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        </p>
      </template>
    </VTabs>
    <template #footer>
      <VButton raised class="mr-3 custom" @click="clear()">Batal</VButton>
      <VButton icon="feather:plus" color="primary" class="custom" raised @click="updateHead(item)" :loading=loadUpdate>
        Update
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalCetakBukti" modal header="Cetak Bukti" :style="{ width: '50vw' }">
    <div class="column is-12">
      <div class="columns">
        <div class="column is-6">
          <Fieldset legend="Petugas Penyerahan" style="padding:10px">
            <div class="column pt-0">
              <VField class=" is-autocomplete-select" label="Jabatan">
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.jabatanpenyerah" :options="d_jabatan"
                    placeholder="Pilih jabatan" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column pt-1">
              <VField class="is-rounded-select is-autocomplete-select" label="Pegawai Penyerah">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.pegawaipenyerah" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
                </VControl>
              </VField>
            </div>
          </Fieldset>
        </div>
        <div class="column is-6">
          <Fieldset legend="Petugas Penerima" style="padding:10px">
            <div class="column pt-0">
              <VField class="is-autocomplete-select" label="Jabatan">
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.jabatanpenerima" :options="d_jabatan"
                    placeholder="Pilih jabatan" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column pt-1">
              <VField class="is-rounded-select is-autocomplete-select" label="Pegawai Penerima">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.pegawaipenerima" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
                </VControl>
              </VField>
            </div>
          </Fieldset>
        </div>
      </div>
      <div class="column is-12 p-0">
        <Fieldset legend="Mengetahui" style="padding:10px">
          <div class="column pt-0">
            <VField class="is-autocomplete-select" label="Jabatan">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.jabatanketahui" :options="d_jabatan"
                  placeholder="Pilih jabatan" :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column pt-1">
            <VField class="is-rounded-select is-autocomplete-select" label="Pegawai">
              <VControl icon="feather:search" class="prime-auto-select">
                <AutoComplete v-model="item.pegawaiketahui" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
              </VControl>
            </VField>
          </div>
        </Fieldset>
      </div>
    </div>
    <template #footer>
      <VButton raised class="mr-3 custom" @click="modalCetakBukti = false">Batal</VButton>
      <VButton icon="feather:printer" color="primary" class="custom" raised @click="cetakBukti(item)">Cetak</VButton>
    </template>
  </Dialog>
</template>


<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed, watch } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import SpeedDial from 'primevue/speeddial';
import TdaftarDistribusi from '../logistik/t_daftar-distribusi.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import OverlayPanel from 'primevue/overlaypanel';
import Dialog from 'primevue/dialog';
import Fieldset from 'primevue/fieldset';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'

useHead({
  title: 'Dashboard Logistik - Transmedic',
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const themeColors = useThemeColors()
const route = useRoute()
const router = useRouter()
const confirm = useConfirm()
const dataSourceDetailSuplier: any = ref([])
const DSPermintaan: any = ref([])
const dataSourceDetailDistribusi: any = ref([])
const dataDetail: any = ref([])
const d_jabatan: any = ref([])
const d_Pegawai: any = ref([])
const d_SumberDana = ref([])
const d_kelompokBarang = ref([])
const d_komit = ref([])
const d_Gudang = ref([])
const d_suplier = ref([])
const op = ref();
const selected: any = ref({})
const btnLoadEdit = ref(false)
const modalEditHead = ref(false)
const modalDetailPenerimaan = ref(false)
const modalDetailPermintaan = ref(false)
const modalDetailDistribusi = ref(false)
const modalCetakBukti = ref(false)
const modalBatalKirim = ref(false)

const sourceDetailPenerimaan = ref([])

let chartRNG: any = ref({
  series: [],
})

const chartMedisNonMedis: any = ref({
  series: [],
})

const dateNow = H.formatDateToLocalString(new Date());
const userLogin = useUserSession().getUser()
let countRuangan: any = ref([])
let d_Ruangan: any = ref([])
let isData: any = ref(true)
let orderLength: any = ref()
let isOrder: any = ref(true)
let item: any = ref({
  qrangeDate: {
    start: new Date(),
    end: new Date()
  },
  datePenerimaan: {
    start: new Date(),
    end: new Date()
  },
  dateDistribusi: {
    start: new Date(),
    end: new Date()
  },
})
const order: any = ref(0)
const dataOrder: any = ref([])
const dataPenerimaan: any = ref([])
const dataDistribusi: any = ref([])
const dataStokProduk: any = ref([])
const isLoading: any = ref(false)
const loadUpdate: any = ref(false)

const filters = ref('')

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataStokProduk.value
  }

  return dataStokProduk.value.filter((item: any) => {
    return (
      item.namaproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})

const changeRuang = (e: any) => {
  fetchDataOrder()
  fetchDataPenerimaanBarang()
  fetchStokProduk()
  fetchDataDistribusi()
}

const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/logistik/list-ruangan`)
  d_Ruangan.value = response.map((e: any) => {
    return { label: e.namaruangan, value: e.id, default: e }
  })
}

const fetchDataOrder = async () => {
  isOrder.value = true
  dataOrder.value.loading = true
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.qrangeDate.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.qrangeDate.end, 'YYYY-MM-DD')
  let ruanganid = item.value.filterRuangan ? `&ruangantujuanfk=${item.value.filterRuangan}` : ''
  let noorder = item.value.noorder ? `&noorder=${item.value.noorder}` : ''


  await useApi().get(`/dashboard/logistik/get-daftar-order?${tglAwal}${tglAkhir}${ruanganid}${noorder}`).then((response: any) => {
    response.daftar.forEach((element: any) => {
      let tglOrder = H.formatDateToLocalString(element.tglorder)
      let ruanganAsal = (element.namaruanganasal != null && element.namaruanganasal.length > 15) ? `${element.namaruanganasal.substr(0, 15)}...` : element.namaruanganasal
      let ruanganTujuan = (element.namaruangantujuan != null && element.namaruangantujuan.length > 15) ? `${element.namaruangantujuan.substr(0, 15)}...` : element.namaruangantujuan
      let keterangan = (element.keterangan != null && element.keterangan.length > 15) ? element.keterangan.substr(0, 15) + '...' : element.keterangan
      element.ruanganAsal = ruanganAsal
      element.ruanganTujuan = ruanganTujuan
      element.tglOrder = tglOrder
      element.keterangan = keterangan
    })
    orderLength.value = response.daftar.length
    dataOrder.value = response.daftar
    dataOrder.value.loading = false
  }).catch((e) => {
    dataOrder.value.loading = false
  })
}

const fetchDataPenerimaanBarang = async () => {
  dataPenerimaan.value.loading = true
  let ruanganid = item.value.filterRuangan ? `&ruangan=${item.value.filterRuangan}` : ''
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.datePenerimaan.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.datePenerimaan.end, 'YYYY-MM-DD')
  let noStruk = item.value.nostruk ? '&nostruk=' + item.value.nostruk : ''
  await useApi().get('/logistik/penerimaan-barang?' + tglAwal + tglAkhir + ruanganid + noStruk).then((response) => {
    dataPenerimaan.value.loading = false
    response.daftar.forEach((element: any) => {
      let tglDiterima = new Date(element.tglstruk).toLocaleDateString('id-ID', { year: "numeric", month: "long", day: "numeric" })
      let rekanan = (element.namarekanan.length > 22) ? `${element.namarekanan.substring(0, 23)}...` : element.namarekanan
      element.rekanan = rekanan
      element.tglDiterima = tglDiterima
    });
    dataPenerimaan.value = response.daftar
    dataPenerimaan.value.lengthData = response.daftar.length
    dataPenerimaan.value.loading = false

    useApi().postNoMessage('/general/save-jurnal-penerimaan-barang', {
      tglAwal: H.formatDate(item.value.datePenerimaan.start, 'YYYY-MM-DD 00:00:00'),
      tglAkhir: H.formatDate(item.value.datePenerimaan.end, 'YYYY-MM-DD 23:59:59'),
    })
  })
}

const fetchDataDistribusi = async () => {
  let tglAwal = 'tglAwal=' + moment(item.value.dateDistribusi.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.dateDistribusi.end).format('YYYY-MM-DD')
  let noKirim = item.value.nokirim ? `&nokirim=${item.value.nokirim}` : '';
  let ruanganid = item.value.filterRuangan ? `&ruangantujuanfk=${item.value.filterRuangan}` : '';
  dataDistribusi.value.loading = true

  await useApi().get(`/dashboard/logistik/get-data-distribusi?${tglAwal}${tglAkhir}${noKirim}${ruanganid}`).then((response) => {
    response.daftar.forEach((element: any) => {
      element.tglkirim = H.formatDateToLocalString(element.tglstruk)
      element.totalItem = element.details.length
    });
    dataDistribusi.value = response.daftar
    dataDistribusi.value.loading = false
    dataDistribusi.value.lengthData = response.daftar.length
  })
}

const fetchStokProduk = async (e: any) => {
  let ruanganid = item.value.filterRuangan ? `ruangan=${item.value.filterRuangan}` : ''
  let produk = e ? `&namaproduk=${e}` : ''
  dataStokProduk.value.loading = true
  // if (item.value.filterRuangan) {
  //   ruanganid = '?ruangan=' + item.value.filterRuangan
  // }
  await useApi().get('/dashboard/logistik/get-stok-produk?' + ruanganid + produk).then(response => {
    dataStokProduk.value = response
    dataStokProduk.value.loading = false
  })
}

const fetchChartRequestByRuangan = async () => {
  await useApi().get('/dashboard/logistik/chart-request-ruangan').then((response) => {
    chartRNG.value = {
      series: response.chartRNG.count,
      chart: {
        height: 290,
        type: 'donut',
      },
      colors: [
        themeColors.accent,
        themeColors.info,
        themeColors.green,
        themeColors.purple,
        themeColors.orange,
      ],
      labels: response.chartRNG.ruangan,
      responsive: [
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 280,
              toolbar: {
                show: false,
              },
            },
            legend: {
              position: 'top',
            },
          },
        },
      ],
      legend: {
        position: 'right',
        horizontalAlign: 'center',
      },
    }
  })
}

const fetchChartMedisNon = async () => {
  await useApi()
    .get(`/dashboard/logistik/chart-medis-non-medis`)
    .then((response: any) => {
      // item.value = response
      chartMedisNonMedis.value = {
        series: response.chartMedis.series,
        chart: {
          type: 'bar',
          height: 260,
          toolbar: {
            show: false,
          },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded',
          },
        },
        colors: [themeColors.accent, themeColors.info, themeColors.green, themeColors.purple],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent'],
        },
        xaxis: {
          categories: response.chartMedis.categories,
        },
        yaxis: {
          title: {
            text: 'Jumlah',
          },
        },
        fill: {
          opacity: 1,
        },
        legend: {
          position: 'top',
          horizontalAlign: 'center',
        },
        title: {
          text: '',
          align: 'left',
        },
        tooltip: {
          // y: {
          //   formatter: formatters.asKDollar,
          // },
        },
      }
      countRuangan.value = response
      countRuangan.value.total = response.length
    })
}

const detailPermintaan = async (e: any) => {
  modalDetailPermintaan.value = true
  e.details.forEach((element: any, i: any) => {
    console.log(element)
    element.no = i + 1
  });
  DSPermintaan.value = e.details
  dataDetail.value.status = e.status
  dataDetail.value.keterangan = e.keterangan
  dataDetail.value.tglOrder = e.tglorder
  dataDetail.value.ruanganAsal = e.namaruanganasal
  dataDetail.value.ruanganTujuan = e.namaruangantujuan
  dataDetail.value.jeniskirim = e.jeniskirim
}

const detailPenerimaan = async (e: any) => {
  modalDetailPenerimaan.value = true
  e.details.forEach((element: any, i: any) => {
    element.no = i + 1
    element.harga = H.formatRp(element.hargasatuan, 'Rp.')
    element.total = H.formatRp(element.totalall, 'Rp.')
    element.hargadiscount = H.formatRp(element.hargadiskon, 'Rp.')
    element.hargappn = H.formatRp(element.nilaippn, 'Rp.')
  });
  item.value.noFaktur = e.nofaktur
  item.value.suplayer = e.namarekanan
  item.value.namaruangan = e.namaruangan
  sourceDetailPenerimaan.value = e.details
}

const detailDistribusi = (e: any) => {
  modalDetailDistribusi.value = true
  item.value.tglstruk = e.tglkirim
  item.value.totalItem = e.totalItem
  item.value.nostruk = e.nostruk
  item.value.qty = e.jmlitem
  item.value.ruanganasal = e.namaruanganasal
  item.value.ruangantujuan = e.namaruangantujuan
  item.value.petugas = e.petugas
  e.details.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  dataSourceDetailDistribusi.value = e.details
}

const clearInput = () => {
  delete item.value.keterangan
  modalBatalKirim.value = false

}

const batalKirim = (e: any) => {
  item.value.noorderfk = e.noorderfk
  item.value.norec = e.norec
  item.value.ruasid = e.ruasalid
  item.value.rutujuanid = e.rutujuanid
  item.value.jenispermintaanfk = e.jenispermintaanfk
  item.value.jmlitem = e.jmlitem
  item.value.nostruk = e.nostruk
  item.value.namaruangantujuan = e.namaruangantujuan
  item.value.namaruanganasal = e.namaruanganasal
  modalBatalKirim.value = true

}

const saveBatal = async () => {
  let param = {
    'strukkirim': {
      'noorderfk': item.value.noorderfk,
      'noreckirim': item.value.norec,
      'objectruanganasal': item.value.ruasid,
      'obejectruangantujuan': item.value.rutujuanid,
      'namaruanganasal': item.value.namaruanganasal,
      'namaruangantujuan': item.value.namaruangantujuan,
      'jenispermintaanfk': item.value.jenispermintaanfk,
      'nostruk': item.value.nostruk,
      'jmlitem': item.value.jmlitem,
      'keterangan': item.value.keterangan,
    }
  }
  isLoading.value = true
  await useApi().post('/dashboard/logistik/batal-kirim-barang', param).then((response: any) => {
    isLoading.value = false
    fetchDataDistribusi()
    clearInput()
  }, (error) => {
    isLoading.value = false
  })
}

const updateHead = async (e: any) => {
  if (!item.value.tglTerima) {
    useToaster().error('Tanggal Terima Tidak Boleh Kosong')
    return
  }
  if (!item.value.gudang) {
    useToaster().error('Gudang Tidak Boleh Kosong')
    return
  }
  if (!item.value.penerimaan) {
    useToaster().error('Pegawai Penerima Tidak Boleh Kosong')
    return
  }
  if (!item.value.kelompokbarang) {
    useToaster().error('kelompok Barang Tidak Boleh Kosong')
    return
  }
  if (!item.value.sumberdana) {
    useToaster().error('Sumber Dana Tidak Boleh Kosong')
    return
  }

  if (item.value.sumberdana == 7) {
    if (!item.value.noBuktiKK) {
      useToaster().error('No Bukti Tidak Boleh Kosong')
      return
    }
    if (!item.value.tanggalKK) {
      useToaster().error('Tanggal Kas Kecil Tidak Boleh Kosong')
      return
    }
    if (!item.value.ruanganKK) {
      useToaster().error('Ruangan Kas Kecil Tidak Boleh Kosong')
      return
    }
    if (!item.value.pegawaiKK) {
      useToaster().error('pegawai Kas Kecil Tidak Boleh Kosong')
      return
    }
  }

  if (!item.value.tglFaktur) {
    useToaster().error('Tanggal Faktur Tidak Boleh Kosong')
    return
  }
  if (!item.value.noFaktur) {
    useToaster().error('No Faktur Tidak Boleh Kosong')
    return
  }
  if (!item.value.supplier) {
    useToaster().error('Supplier Tidak Boleh Kosong')
    return
  }

  //     isLoadingBtn.value = true
  const objSave = {
    // details: detailPenerimaan.value,
    struk: {
      nosppb: '',
      nostruk: e.nostruk,
      norec: e.norec,
      norecsppb: '',
      norecrealisasi: item.value.norecrealisasi ? item.value.norecrealisasi : '',
      objectmataanggaranfk: '',
      norecOrder: '',
      jenissusulan: 'Medis',
      jenissusulanfk: 2,
      noorder: item.value.nosppb ? item.value.nosppb : '',
      noBuktiKK: item.value.noBuktiKK ? item.value.noBuktiKK : '',
      pegawaiKK: item.value.pegawaiKK ? item.value.pegawaiKK.value.id : null,
      ruanganfkKK: item.value.ruanganKK ? item.value.ruanganKK.value : '',
      ruanganfk: item.value.gudang.value,
      asalproduk: item.value.sumberdana.value,
      pegawaikomit: item.value.pembuatkomit ? item.value.pembuatkomit.namalengkap : '',
      namapegawaipenerima: item.value.penerimaan.value.namalengkap,
      namarekanan: item.value.supplier.label,
      namapengadaan: item.value.namapengadaan ? item.value.namapengadaan : '',
      ketTerima: item.value.ketTerima ? item.value.ketTerima : '',
      nokontrak: item.value.nokontrak ? item.value.nokontrak : '',
      pegawaimenerimafk: item.value.penerimaan.value.id,
      rekananfk: item.value.supplier.value.id,
      nofaktur: item.value.noFaktur,
      kelompokprodukfk: item.value.kelompokbarang.value,
      nousulan: item.value.nousulan ? item.value.nousulan : '',
      qtyproduk: e.qtypelayanan,
      tglSppb: null,
      tglKK: item.value.tanggalKK ? H.formatDate(item.value.tanggalKK, 'YYYY-MM-DD HH:mm:ss') : null,
      tglTempo: item.value.tglTempo ? H.formatDate(item.value.tglTempo, 'YYYY-MM-DD HH:mm:ss') : null,
      tglfaktur: item.value.tglFaktur ? H.formatDate(item.value.tglFaktur, 'YYYY-MM-DD HH:mm:ss') : null,
      tglstruk: item.value.tglTerima ? H.formatDate(item.value.tglTerima, 'YYYY-MM-DD HH:mm:ss') : null,
      tglorder: item.value.tanggalPo ? H.formatDate(item.value.tanggalPo, 'YYYY-MM-DD HH:mm:ss') : null,
      tglkontrak: item.value.tanggalPo ? H.formatDate(item.value.tanggalPo, 'YYYY-MM-DD HH:mm:ss') : null,
      tglrealisasi: item.value.tglFaktur ? H.formatDate(item.value.tglFaktur, 'YYYY-MM-DD HH:mm:ss') : null,
    },
  }
  loadUpdate.value = true
  await useApi()
    .post('/logistik/penerimaan-barang/edit-header-penerimaan-suplier', objSave)
    .then((response) => {
      modalEditHead.value = false
      loadUpdate.value = true
      fetchDataPenerimaanBarang()
    })
    .catch((err) => {
      modalEditHead.value = false
      console.log(err)
    })
}



const editDis = (e: any) => {
  router.push({
    name: 'module-logistik-distribusi-barang',
    query: {
      norec: e.norec,
    },
  })
}

const filter = () => {
  item.isDate = false
  fetchDataPenerimaanBarang()
}

const cetakBuktiPopUp = (e: any) => {
  item.value.norec = e.norec
  modalCetakBukti.value = true
}

const returPenerimaan = (e: any) => {
  router.push({
    name: 'module-logistik-retur-penerimaan-barang-suplier',
    query: {
      norec: e.norec,
    },
  })
}

const editPenerimaan = (e: any) => {
  router.push({
    name: 'module-logistik-form-penerimaan-barang-suplier',
    query: {
      norec: e.norec,
    },
  })
}

const dialogConfirmPenerimaan = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      batalTerima(e)
    },
    reject: () => { },
  })
}

const batalTerima = async (e: any) => {
  await useApi().post('/logistik/penerimaan-barang/delete-penerimaan-suplier', { 'nostruk': e.norec }).then((response) => {
    console.log(response)
    fetchDataPenerimaanBarang()
  }).catch((err: any) => {
    console.log(err)
  })
}

const kirimOrder = (e: any) => {
  if (e.statusorder == 'Sudah Kirim') {
    H.alert('error', 'Barang Sudah Dikirim')
    return
  }
  router.push({
    name: 'module-logistik-distribusi-barang',
    query: {
      norec_order: e.norec,
    },
  })
}

const editOrder = (e: any) => {
  router.push({
    name: 'module-logistik-order-barang',
    query: {
      norec: e.norec,
    },
  })
}

const editKirimBarang = (e: any) => {
  router.push({
    name: 'module-logistik-distribusi-barang',
    query: {
      iseditkirim: true,
      norec_order: e.norec,
    },
  })
}

const verifOrder = (e: any) => {
  router.push({
    name: 'module-logistik-verifikasi-pengiriman-barang',
    query: {
      norec_order: e.norec,
    },
  })
}

const DialogConfirmOrder = (e: any, info: any) => {
  let message = info == 'batal kirim' ? 'Apakah Anda yakin akan membatalkan kirim barang ini ?' : 'Apakah Anda yakin menghapus data ini ?'
  let konfirm = info == 'batal kirim' ? 'Konfirmasi Batal Kirim' : 'Konfirmasi Hapus Order'
  confirm.require({
    message: message,
    header: konfirm,
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      if (info == 'batal kirim') {
        batalKirimBarang(e)
      } else {
        hapusItems(e)
      }
    },
    reject: () => { },
  })
}

const batalKirimBarang = async (e: any) => {
  // console.log(e)
  let data = {
    norec_so: e.norec,
    ruangantujuanfk: e.idruangantujuan,
    ruanganasalfk: e.idruanganasal,
    namaruanganasal: e.namaruanganasal,
    namaruangantujuan: e.namaruangantujuan
  }
  // console.log(data)
  await useApi().post('/logistik/batal-kirim-order-barang', data).then((response) => {
    isLoading.value = false
    fetchDataOrder()
  })
}

const hapusItems = async (e: any) => {
  useApi().post(
    `/logistik/hapus-order-barang`, { norec: e.norec }).then((response: any) => {
      isLoading.value = false
      fetchDataOrder()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchJabatan = async () => {
  await useApi().get('logistik/penerimaan-barang/get-jabatan').then((respon) => {
    d_jabatan.value = respon.map((e: any) => {
      return { label: e.namajabatan, value: e.id }
    })
  })
}

const cetakBuktiOrder = async (data: any) => {
  H.printBlade(`/report/logistik/cetak-bukti-order?norec=${data.norec}`)
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const toggle = (event: any, e: any) => {
  op.value.toggle(event);
  selected.value = e
}

const clear = () => {
  sourceDetailPenerimaan.value = []
  delete item.value.jabatanpenyerah
  delete item.value.pegawaipenyerah
  delete item.value.jabatanpenerima
  delete item.value.pegawaipenerima
  delete item.value.jabatanketahui
  delete item.value.pegawaiketahui
}

const listDataCombo = async () => {
  await useApi()
    .get('logistik/penerimaan-barang/get-data-combo')
    .then((response) => {
      d_komit.value = response.pembuatkomit.map((e: any) => {
        return { label: e.namalengkap, value: e }
      })
      d_Pegawai.value = response.pegawai.map((e: any) => {
        return { label: e.namalengkap, value: e }
      })
      d_SumberDana.value = response.sumberdana.map((e: any) => {
        return { label: e.asalproduk, value: e.id }
      })
      d_kelompokBarang.value = response.kelompokbarang.map((e: any) => {
        return { label: e.kelompokproduk, value: e.id }
      })
      d_Gudang.value = response.ruangan.map((e: any) => {
        return { label: e.namaruangan, value: e.id }
      })
      d_suplier.value = response.suplier.map((e: any) => {
        return { label: e.namarekanan, value: e }
      })
    })
}

const editHead = async (e: any) => {
  console.log(e)
  btnLoadEdit.value = true
  await listDataCombo()
  await useApi().get(`/logistik/get-detail-penerimaan?norec=${e.norec}`).then((response) => {
    let dataPeneriman = response.detailterima
    let datapelayanan = response.pelayananPasien
    // item.value.asalpro = dataPenerimaan.asalprodukfk
    d_Pegawai.value.forEach((e: any) => {
      if (e.value.id == dataPeneriman.pgid) {
        item.value.penerimaan = e
        return
      }
    });
    d_Gudang.value.forEach((e: any) => {
      if (e.value == dataPeneriman.objectruanganfk) {
        item.value.gudang = e
        return
      }
    });
    d_kelompokBarang.value.forEach((e: any) => {
      if (e.value == dataPeneriman.objectkelompokprodukfk) {
        item.value.kelompokbarang = e
        return
      }
    });
    d_SumberDana.value.forEach((e: any) => {
      if (e.value == dataPeneriman.asalprodukfk) {
        item.value.sumberdana = e
        return
      }
    });
    d_suplier.value.forEach((e: any) => {
      if (e.value.id == dataPeneriman.objectrekananfk) {
        item.value.supplier = e
        return
      }
    });
    d_komit.value.forEach((e: any) => {
      if (e.value.id == dataPeneriman.objectpegawaipenanggungjawabfk) {
        item.value.pegawaiKK = e
        return
      }
    })
    d_Gudang.value.forEach((e: any) => {
      if (e.value == dataPeneriman.objectruanganasalfk) {
        item.value.ruanganKK = e
        return
      }
    });
    // item.value.kelompokbarang = dataPeneriman.objectkelompokprodukfk
    item.value.noBKK = dataPeneriman.nobukti ? true : false
    item.value.noBuktiKK = item.value.noBKK == true ? dataPeneriman.nobukti : ''
    // noBukti.value = dataPeneriman.nobukti
    item.value.norec = dataPeneriman.norec
    item.value.norecrealisasi = dataPeneriman.norecrealisasi
    item.value.nostruk = dataPeneriman.nostruk
    item.value.tanggalKK = dataPeneriman.tglspk
    item.value.nostruk = dataPeneriman.nostruk
    item.value.noBukti = dataPeneriman.nobukti
    item.value.tglTerima = H.formatDate(dataPeneriman.tglstruk, 'MM/DD/YYYY')
    item.value.tglFaktur = H.formatDate(dataPeneriman.tglfaktur, 'MM/DD/YYYY')
    item.value.noFaktur = dataPeneriman.nofaktur
    item.value.tglTempo = H.formatDate(dataPeneriman.tgljatuhtempo, 'MM/DD/YYYY')
    item.value.nousulan = dataPeneriman.nousulan
    item.value.tanggalPo = H.formatDate(dataPeneriman.tgldokumen, 'MM/DD/YYYY')
    item.value.namapengadaan = dataPeneriman.namapengadaan
    item.value.nokontrak = dataPeneriman.nokontrak
    detailPenerimaan.value = datapelayanan
    if (dataPeneriman.asalprodukfk == 7) {
      d_komit.value.forEach((e: any) => {
        if (e.value.id == dataPeneriman.objectpegawaipenanggungjawabfk) {
          item.value.pegawaiKK = e
          return
        }
      })
      d_Gudang.value.forEach((e: any) => {
        if (e.value == dataPeneriman.objectruanganasalfk) {
          item.value.ruanganKK = e
          return
        }
      });
      return
    }
  })
  modalEditHead.value = true
  btnLoadEdit.value = false
}

const cetakBukti = async (e: any) => {
  if (!e.pegawaipenyerah) {
    H.alert('error', 'Petugas Penyerahan Harus Diisi');
    return
  }
  if (!e.pegawaipenerima) {
    H.alert('error', 'Petugas Penerima Harus Diisi');
    return
  }

  if (!e.pegawaiketahui) {
    H.alert('error', 'Petugas Mengetahui Harus Diisi');
    return
  }
  let pegawaiPenyerah = `&pegawaiMeminta=${e.pegawaipenyerah.value}`
  let pegawaiMengetahui = `&pegawaiMengetahui=${e.pegawaiketahui.value}`
  let pegawaiPenerima = `&pegawaiPenerima=${e.pegawaipenerima.value}`
  H.printBlade(`logistik/report/cetak-bukti-penerimaan-barang?norec=${e.norec}${pegawaiPenyerah}${pegawaiPenerima}${pegawaiMengetahui}`);

}

const cetakBuktiKirim = (e: any) => {
  H.printBlade(`logistik/cetak-bukti-kirim?nokirim=${e.nostruk}&jenis=distribusi`)
}

const gotoRetur = (e: any) => {
  router.push({
    name: 'module-logistik-retur-kirim-barang',
    query: {
      norec: e.norec,
    },
  })
}


fetchChartMedisNon()
fetchJabatan()
fetchdDropdown()
fetchDataOrder()
fetchDataPenerimaanBarang()
fetchDataDistribusi()
fetchStokProduk();

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/logistik.scss';

.slider-tri {
  .tabs-inner {
    margin-right: 26rem !important;
  }
}

.button.v-button.custom {
  padding: 15px !important;
  height: 38px !important;
  line-height: 1.1 !important;
  font-size: 0.95rem !important;
  font-family: var(--font) !important;
  transition: all 0.3s !important;
}
</style>
