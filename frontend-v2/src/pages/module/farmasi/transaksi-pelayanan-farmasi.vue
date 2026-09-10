
<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns is-multiline">

      <div class="column is-12" v-if="dataPasien.loading">
        <div class="block-green">
          <div class="flex-list-inner mb-4">
            <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
              <VFlexTableCell :column="{ grow: true, media: true }">
                <VPlaceloadAvatar size="medium" />
                <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="10%" height="10px" class="mx-1 mt-1" />
              </VFlexTableCell>
              <VFlexTableCell :column="{ align: 'end' }">
                <VPlaceload width="10%" class="mx-1" />
              </VFlexTableCell>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12" v-else-if="dataPasien">
        <div class="block-header">
          <div class="left">
            <div class="current-user">
              <VAvatar size="medium" picture="/images/avatars/svg/vuero-1.svg" squared />
              <h3>{{ dataPasien.namapasien }}</h3>
              <p class="block-text">{{ dataPasien.nocm + (dataPasien.jeniskelamin ==
                'PEREMPUAN' ? ' (P)'
                :
                ' (L)')
              }}</p>
            </div>
          </div>
          <div class="center">
            <div class="columns is-multiline">
              <div class="column is-4">
                <VField>
                  <VLabelText class="text-white">No Registrasi</VLabelText>
                  <VLabel class="text-white">
                    <i aria-hidden="true" class="bulet fas fa-circle"></i>
                    {{ dataPasien.noregistrasi }}
                  </VLabel>

                </VField>
              </div>

              <div class="column is-4">
                <VField>
                  <VLabelText>Tgl Masuk</VLabelText>
                  <VLabel>
                    <i aria-hidden="true" class="bulet fas fa-circle"></i>
                    {{ dataPasien.tglregistrasi }}
                  </VLabel>
                </VField>
              </div>
              <div class="column is-4">
                <VField>
                  <VLabelText>Ruangan Akhir</VLabelText>
                  <VLabel>
                    <i aria-hidden="true" class="bulet fas fa-circle"></i>
                    {{ dataPasien.namaruangan }}
                  </VLabel>
                </VField>
              </div>
              <div class="column is-4">
                <VField>
                  <VLabelText>Kelas</VLabelText>
                  <VLabel>
                    <i aria-hidden="true" class="bulet fas fa-circle"></i>
                    {{ dataPasien.namakelas }}
                  </VLabel>
                </VField>
              </div>
              <div class="column is-4">
                <VField>
                  <VLabelText>Tipe Pembiayaan</VLabelText>
                  <VLabel>
                    <i aria-hidden="true" class="bulet fas fa-circle"></i>
                    {{ dataPasien.kelompokpasien + ' / ' + dataPasien.namarekanan }}
                  </VLabel>
                </VField>
              </div>
              <div class="column is-4">
                <VField>
                  <VLabelText>Umur </VLabelText>
                  <VLabel>
                    <i aria-hidden="true" class="bulet fas fa-circle"></i>
                    {{ dataPasien.umur }}
                  </VLabel>
                </VField>
              </div>

            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
  <!-- <VCard>
        <div class="columns ">
            <div class="column">
                <h3 class="title is-5 mb-2 mr-1">{{ TITLE_PAGE }}</h3>
            </div>
            <div class="column ">
                <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right">
                    <template #content>
                        <a role="menuitem" href="#" class="dropdown-item is-media" @click="onInit()">
                            <div class="icon">
                                <i aria-hidden="true" class="pi pi-refresh"></i>
                            </div>
                            <div class="meta">
                                <span>Reload</span>
                            </div>
                        </a>
                    </template>
                </VDropdown>
            </div>
        </div>
        <div class="columns  all-projects m-3 mt-0">
            <div class="column is-12" v-if="dataPasien.loading">
                <div class="flex-list-inner mb-4">
                    <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                        <VFlexTableCell :column="{ grow: true, media: true }">
                            <VPlaceloadAvatar size="medium" />
                            <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                        </VFlexTableCell>
                        <VFlexTableCell>
                            <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                        </VFlexTableCell>
                        <VFlexTableCell>
                            <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                        </VFlexTableCell>
                        <VFlexTableCell :column="{ align: 'end' }">
                            <VPlaceload width="10%" class="mx-1" />
                        </VFlexTableCell>
                    </div>
                </div>
            </div>
            <div class="column is-12" v-else-if="dataPasien">
                <div class="grid-item mb-4">
                    <div class="top-section">
                        <div class="head">
                            <div class="title-wrap">
                                <div class="columns">
                                    <div class="column">
                                        <VAvatar size="small" color="danger" :initials="dataPasien.initials" />
                                    </div>
                                    <div class="column is-12 mr-3">
                                        <h3>{{ dataPasien.namapasien }}</h3>
                                        <p>{{ dataPasien.nocm + (dataPasien.jeniskelamin == 'PEREMPUAN' ? ' (P)' :
                                                '(L)')
                                        }}</p>
                                    </div>

                                </div>
                            </div>


                        </div>

                        <div class="body">
                            <div class="columns">
                                <div class="column">
                                    <h4 class="heading">Registrasi</h4>
                                    <p class="fs-075">No : {{ dataPasien.noregistrasi }}</p>
                                    <p class="fs-075">Tanggal : {{ dataPasien.tglregistrasi }}</p>
                                </div>
                                <div class="column">
                                    <h4 class="heading">Kelompok Pasien</h4>
                                    <p class="fs-075">{{ dataPasien.kelompokpasien }}</p>
                                    <p class="fs-075">{{ dataPasien.namakelas }}</p>
                                </div>
                                <div class="column">
                                    <h4 class="heading">Tanggal Pulang</h4>
                                    <p class="fs-075">{{ dataPasien.tglpulang }}</p>
                                </div>
                                <div class="column">
                                    <h4 class="heading">Umur</h4>
                                    <VTag color="info" :label="dataPasien.umur" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </VCard> -->
  <VCard class="mt-3">
    <div class="columns is-multiline mb-0 pb-0">
      <div class="column is-6">
        <h3 class="title is-5  mr-1">Data Resep</h3>
      </div>
      <div class="column is-6">

        <VControl class="is-pulled-right">
          <VSwitchBlock v-model="item.resepAkhir" :label="item.resepAkhir? 'Tambah Resep ke Ruangan Terakhir':'Pilih Ruangan Resep'" color="danger"  />
        </VControl>
        <VIconButton circle class="mr-5 mt-2 is-pulled-right" icon="feather:refresh-cw" raised bold @click="onInit()"
          v-tooltip.bubble="'Perbaharui Data '">
        </VIconButton>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <TabView v-model:activeIndex="activeIdx">
            <TabPanel header="Resep">
              <div class="columns is-multiline all-projects m-3 mt-0">
                <div class="column is-12" v-if="dataPasien.loading || isLoading">
                  <div class="flex-list-inner mb-4">
                    <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                      <VFlexTableCell>
                        <VPlaceload width="10%" class="mx-1" height="20px" />
                        <VPlaceload width="10%" class="mx-1 is-pulled-right" height="20px" />
                      </VFlexTableCell>
                      <VFlexTableCell>
                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                      </VFlexTableCell>
                      <VFlexTableCell>
                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                      </VFlexTableCell>
                      <VFlexTableCell>
                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                      </VFlexTableCell>
                      <VFlexTableCell :column="{ align: 'end' }">
                        <VPlaceload width="10%" height="20px" class="mx-1 mt-2" />
                      </VFlexTableCell>
                    </div>
                  </div>
                </div>
                <div class="column is-12" v-else-if="!dataPasien.loading && !isLoading">
                  <Toolbar>
                    <template #start>
                      <VIconButton type="button" icon="feather:plus" class=" mr-3"
                        color="success" circle outlined raised v-tooltip.left="'Tambah'" @click="tambahResep()">
                      </VIconButton>
                      <!-- <VIconButton type="button" v-if="dataPasien.statusbayar != 'Lunas'" icon="feather:plus" class=" mr-3"
                        color="success" circle outlined raised v-tooltip.left="'Tambah'" @click="tambahResep()">
                      </VIconButton> -->
                      <VIconButton type="button" icon="fa fa-history" class=" mr-3" color="purple" circle outlined raised
                        v-tooltip.left="'Riwayat Resep'">
                      </VIconButton>
                      <!-- <VIconButton type="button" icon="fa fa-history" class=" mr-3" color="purple" circle outlined raised
                        v-tooltip.left="'Riwayat Resep'" @click="expandAll">
                      </VIconButton> -->
                      <VIconButton type="button" @click="cetak()" icon="fa fa-print"
                        class=" mr-3" color="danger" circle outlined raised v-tooltip.center="'Cetak Kwitansi Retur'">
                      </VIconButton>
                    </template>
                    <template #end>
                      <span class="p-input-icon-left is-pulled-right">

                        <InputText v-model="item.global.value" placeholder="Search" />
                      </span>
                    </template>
                  </Toolbar>
                  <DataTable :value="dataSource" v-model:expandedRows="expandedRows" :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers" filterDisplay="menu" v-model:filters="item"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                    :globalFilterFields="['noresep', 'namaproduk', 'ruangandepo', 'namaruangan']" :scrollable="true"
                    size="small"
                    showGridlines
                    stripedRows
                    :loading="dataSource.loading" dataKey="norec_resep">
                    <Column :expander="true" :style="{ width: '50px' }" />
                    <Column :exportable="false" header="#" :style="{ width: '250px' }">
                      <template #body="slotProps">
                        <VIconButton type="button" icon="pi pi-pencil" class="mr-2" color="info" circle outlined raised
                          v-tooltip.top="'Edit Resep'" @click="editResep(slotProps.data)">
                        </VIconButton>

                        <!-- <VIconButton type="button" v-else icon="fas fa-undo" class="mr-2" color="warning" circle outlined raised
                          v-tooltip.top="'Retur Resep'" @click="returResep(slotProps.data)">
                        </VIconButton> -->
                        <!-- <VIconButton
                          type="button"
                          icon="fas fa-trash"
                          class="mr-2"
                          color="danger"
                          circle
                          outlined
                          raised
                          v-tooltip.top="'Hapus Resep'"
                          @click="confirmDelete(slotProps.data)"
                        >
                        </VIconButton> -->

                        <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined raised
                          v-tooltip.top="'Aksi Lainnya '" @click="toggle($event, slotProps.data)">
                        </VIconButton>
                        <OverlayPanel ref="op">
                          <VButton type="button" icon="fas fa-print" class="mr-2" light circle outlined color="info" raised
                            @click="cetakResep(selected)">
                            Cetak
                          </VButton>
                          <VButton type="button" icon="fas fa-print" class="mr-2" light circle outlined color="info" raised
                            @click="cetakLabel(selected)">
                            Label
                          </VButton>
                          <VButton type="button" icon="fas fa-print" class="mr-2" light circle outlined color="info" raised
                            @click="showModalDetailVerif(selected)">
                            Rekap Label
                          </VButton>
                          <VButton type="button" icon="fas fa-undo" class="mr-2" color="warning" circle outlined raised
                            @click="returResep(selected)">Retur
                          </VButton>
<!-- 
                          <VButton type="button" icon="fas fa-trash" class="mr-2" color="danger" circle outlined raised>Hapus
                          </VButton> -->
                          <VButton type="button" v-if="dataPasien.statusbayar == 'Lunas'" icon="fas fa-undo" class="mr-2"
                            color="primary" circle outlined raised v-tooltip.top="'Retur Resep Sudah dibayar'"
                            @click="returResep(selected)"> Retur Bayar
                          </VButton>
                        </OverlayPanel>
                      </template>
                    </Column>

                    <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
                    <Column field="noregistrasi" header="No Registrasi" style="width:150px" :sortable="true"></Column>
                    <Column field="tglpelayanan" header="Tgl Pelayanan" style="width:150px"></Column>
                    <Column field="noresep" header="No Resep" :sortable="true" style="width:100px"></Column>
                    <Column field="namaruangan" header="Ruang Rawat" style="width:200px"></Column>
                    <Column field="ruangandepo" header="Depo" style="width:200px"></Column>
                    <Column field="jasa" header="Jasa" footer="Grand Total :">
                      <template #body="slotProps">
                        {{ formatRp(slotProps.data.jasa, '') }}
                      </template>
                    </Column>
                    <Column field="total" header="Total" :footer="TOTAL">
                      <template #body="slotProps">
                        {{ formatRp(slotProps.data.total, '') }}
                      </template>
                    </Column>

                    <template #expansion="slotProps">
                      <div class="orders-subtable">
                        <h5>No Resep : {{ slotProps.data.noresep }}</h5>
                        <DataTable :value="slotProps.data.details" responsiveLayout="scroll">
                          <Column field="rke" header="R/ke" :sortable="true"></Column>
                          <Column field="jeniskemasan" header="Kemasan" :sortable="true">
                          </Column>
                          <Column field="namaproduk" header="Nama Obat" :sortable="true" style="width:300px">
                          </Column>
                          <Column field="satuanstandar" header="Satuan">
                          </Column>
                          <Column field="jumlah" header="Qty"></Column>
                          <Column field="hargasatuan" header="Harga ">
                            <template #body="slotProps">
                              {{ formatRp(slotProps.data.hargasatuan, '') }}
                            </template>
                          </Column>
                          <Column field="hargadiscount" header="Diskon">
                            <template #body="slotProps">
                              {{ formatRp(slotProps.data.hargadiscount, '') }}
                            </template>
                          </Column>

                          <Column field="tglkadaluarsa" header="Tgl Exp" style="width:300px"></Column>
                          <Column field="iskronis" header="Kronis">
                            <template #body="slotProps">
                              {{ slotProps.data.iskronis == true ? '✔' : '' }}
                            </template>
                          </Column>
                          <Column field="isdonasi" header="Donasi">
                            <template #body="slotProps">
                              {{ slotProps.data.isdonasi == true ? '✔' : '' }}
                            </template>
                          </Column>
                          <Column field="nostruk" header="No Struk"></Column>
                        </DataTable>
                      </div>
                    </template>
                    <template #paginatorstart>
                      <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                    </template>
                    <template #paginatorend>
                      <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                    </template>

                  </DataTable>
                </div>
            </div>
            </TabPanel>
            <TabPanel header="Resep Obat Kronis">
              <div class="columns is-multiline">
                <div class="column is-12" >
                  <Toolbar>
                    <template #start>

                    </template>
                    <template #end>
                      <span class="p-input-icon-left is-pulled-right">
                        <i class="pi pi-search" />
                        <InputText v-model="item.global.value" placeholder="Keyword Search" />
                      </span>
                    </template>
                  </Toolbar>
                  <DataTable :value="dataSourceKronis" v-model:expandedRows="expandedRows2" :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers" filterDisplay="menu" v-model:filters="item"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                    :globalFilterFields="['noresep', 'namaproduk', 'ruangandepo', 'namaruangan']" :scrollable="true"
                    :loading="dataSourceKronis.loading" dataKey="norec_resep">
                    <Column :expander="true" :style="{ width: '50px' }" />
                    <Column :exportable="false" header="#" :style="{ width: '250px' }">
                      <template #body="slotProps">

                        <VIconButton type="button" icon="fas fa-trash" class="mr-2" color="danger" circle outlined raised
                          v-tooltip.top="'Hapus Resep'" @click="hapusResepKronis(slotProps.data)">
                        </VIconButton>

                        <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined raised
                          v-tooltip.top="'Aksi Lainnya '" @click="toggle($event, slotProps.data)">
                        </VIconButton>
                        <OverlayPanel ref="op">
                          <VButton type="button" icon="fas fa-print" class="mr-2" light circle outlined color="info" raised
                            @click="cetakResep23Blade(selected)">
                            Cetak Resep 23
                          </VButton>
                          <VButton type="button" icon="fas fa-print" class="mr-2" light circle outlined color="info" raised
                            @click="CetakKwitansiResep23Blade(selected)">
                            Cetak Kwitansi 23
                          </VButton>

                        </OverlayPanel>
                      </template>
                    </Column>

                    <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
                    <Column field="noregistrasi" header="No Registrasi" style="width:150px" :sortable="true"></Column>
                    <Column field="tglpelayanan" header="Tgl Pelayanan" style="width:150px"></Column>
                    <Column field="noresep" header="No Resep" :sortable="true" style="width:100px"></Column>
                    <Column field="namaruangan" header="Ruang Rawat" style="width:200px"></Column>
                    <Column field="ruangandepo" header="Depo" style="width:200px"></Column>
                    <Column field="jasa" header="Jasa" footer="Grand Total :">
                      <template #body="slotProps">
                        {{ formatRp(slotProps.data.jasa, '') }}
                      </template>
                    </Column>
                    <Column field="total" header="Total" :footer="TOTAL">
                      <template #body="slotProps">
                        {{ formatRp(slotProps.data.total, '') }}
                      </template>
                    </Column>

                    <template #expansion="slotProps">
                      <div class="orders-subtable">
                        <h5>No Resep : {{ slotProps.data.noresep }}</h5>
                        <DataTable :value="slotProps.data.details" responsiveLayout="scroll">
                          <Column field="rke" header="R/ke" :sortable="true"></Column>
                          <Column field="jeniskemasan" header="Kemasan" :sortable="true">
                          </Column>
                          <Column field="namaproduk" header="Nama Obat" :sortable="true" style="width:300px">
                          </Column>
                          <Column field="satuanstandar" header="Satuan">
                          </Column>
                          <Column field="jumlah" header="Qty"></Column>
                          <Column field="hargasatuan" header="Harga ">
                            <template #body="slotProps">
                              {{ formatRp(slotProps.data.hargasatuan, '') }}
                            </template>
                          </Column>
                          <Column field="hargadiscount" header="Diskon">
                            <template #body="slotProps">
                              {{ formatRp(slotProps.data.hargadiscount, '') }}
                            </template>
                          </Column>

                          <Column field="tglkadaluarsa" header="Tgl Exp" style="width:300px"></Column>
                          <Column field="iskronis" header="Kronis">
                            <template #body="slotProps">
                              {{ slotProps.data.iskronis == true ? '✔' : '' }}
                            </template>
                          </Column>
                          <Column field="isdonasi" header="Donasi">
                            <template #body="slotProps">
                              {{ slotProps.data.isdonasi == true ? '✔' : '' }}
                            </template>
                          </Column>
                          <Column field="nostruk" header="No Struk"></Column>
                        </DataTable>
                      </div>
                    </template>
                    <template #paginatorstart>
                      <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                    </template>
                    <template #paginatorend>
                      <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                    </template>

                  </DataTable>
                </div>
              </div>
            </TabPanel>
            <TabPanel header="Retur Resep">
              <ReturRanap :NOREC_PASIEN_DAFTAR="NOREC_PD" :NOREC_APD="NOREC_APD" />
            </TabPanel>
        </TabView>
       </div>
    </div>

  </VCard>
                            <VModal 
                            :open="showDeleteModal" 
                            v-model:visible="showDeleteModal" 
                            title="Konfirmasi Hapus"  
                            :cancelLabel="shouldShowCancelLabel ? 'Tutup' : undefined" 
                            @close="showDeleteModal = false"
                          >
                          <template #content>
                            <span>Apakah Anda yakin ingin menghapus resep ini?</span>
                            <br>
                            <br>
                            <div style="display: flex; justify-content: flex-end;">
                              <VButton
                                color="danger"
                                @click="hapusResep(selectedData)"
                              >
                                Yakin
                              </VButton>
                            </div>
                            <!-- <VButton
                              color="secondary"
                              @click="showDeleteModal = false"
                            >
                              No
                            </VButton> -->
                          </template>
                        </VModal>
  <VModal :open="modalAntrian" title="Detail Registrasi" size="big" actions="right" @close="modalAntrian = false"
    cancelLabel="Tutup">
    <template #content>
      <form class="modal-form">
        <DataTable :value="dataSourceAntrian" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="dataSourceAntrian.loading">
          <Column :exportable="false" header="#" style="width:8rem">
            <template #body="slotProps">
              <VIconButton type="button" icon="pi pi-arrow-right" class="mr-3" color="danger" circle outlined raised
                v-tooltip.top="'Input Resep'" @click="gotoResep(slotProps.data)">
              </VIconButton>
            </template>
          </Column>
          <Column field="no" header="No"></Column>
          <Column field="tglregistrasi" header="Tgl Registrasi" style="width:150px"></Column>
          <Column field="namaruangan" header="Ruang" :sortable="true" style="width:200px"></Column>
          <Column field="namadokter" header="Dokter" style="width:200px"></Column>
          <Column field="namakelas" header="Kelas" style="width:150px"></Column>
          <Column field="tglmasuk" header="Tgl Masuk" style="width:150px"></Column>
          <Column field="tglkeluar" header="Tgl Keluar" style="width:150px"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalResepVerify" size="big" noclose title="Resep Verifikasi" actions="center"
    @close="modalResepVerify = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column">
        <DataTable :value="sourceDetailVerif" dataKey="no" v-model:selection="selectedResep" class="p-datatable-sm"
          :loading="sourceDetailVerif.loading" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 200px"></Column>
          <Column field="jmldosis" header="Jumlah Dosis" :sortable="true" style="min-width: 100px"></Column>
          <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column>
          <Column field="satuanstandar" header="Satuan" :sortable="true" style="min-width: 50px"></Column>
          <Column field="jmldosis" header="Dosis" :sortable="true" style="min-width: 50px"></Column>
          <Column field="aturanpakai" header="Aturan pakai" :sortable="true" style="min-width: 50px"></Column>
          <Column field="jeniskemasan" header="Kemasan" :sortable="true" style="min-width: 50px"></Column>
        </DataTable>
      </div>
    </template>
    <template #action>
      <VButton @click="cetakRekapLabel(selectedResep,true)" icon="feather:printer" :loading="isLoading" color="primary" raised>
        Label Injeksi</VButton>
      <VButton @click="cetakRekapLabel(selectedResep,false)" icon="feather:printer" :loading="isLoading" color="primary" raised>
        Rekap Label</VButton>
      <!-- <VButton @click="modalPilihApoteker = true" icon="feather:printer" :loading="isLoading" color="primary" raised>Cetak
      </VButton> -->
    </template>
  </VModal>

</template>
<script setup lang="ts">
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useHead } from '@vueuse/head'
import { useApi } from '/@src/composable/useApi'
import { formatRp } from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import * as H from '/@src/utils/appHelper'
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { useToaster } from '/@src/composable/toaster'
import { FilterMatchMode } from 'primevue/api';
import OverlayPanel from 'primevue/overlaypanel';
import SplitButton from 'primevue/splitbutton';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import sleep from '/@src/utils/sleep';
import * as qzService from '/@src/utils/qzTrayService'
import ReturRanap from '../farmasi/retur-resep-farmasi-ranap.vue'
import ConfirmDialog from 'primevue/confirmdialog';
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
const TITLE_PAGE = 'Pelayanan Farmasi'
useHead({
  title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const NOREC_PD = useRoute().query.norec_pd as string
const NOREC_APD = useRoute().query.norec_APD as string
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const dataPasien: any = ref({ loading: true })
const dataSource: any = ref([])
const selectedResep = ref();
const dataSourceKronis: any = ref([])
const dataSourceAntrian: any = ref([])
const item: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
  resepAkhir: false
})
const op = ref();
const modalAntrian = ref(false)
const router = useRouter()
const modalResepVerify = ref(false)
const isLoading: any = ref(false)
const TOTAL: any = ref(0)
const TOTALKRONIS: any = ref(0)
const expandedRows: any = ref([]);
const expandedRows2: any = ref([]);
const sourceDetailVerif: any = ref([])
const selected: any = ref({})
const confirm = useConfirm();
const toast = useToast();
const showDeleteModal = ref(false);
const selectedData = ref(null);
let activeIdx: any = ref(0)

async function onInit() {
  dataPasien.value.loading = true
  const response = await useApi().get(
    `/farmasi/transaksi-pelayanan-farmasi?norec_pd=${NOREC_PD}`)

  let ini = response.header.namapasien.split(' ')
  let init = response.header.namapasien.substr(0, 1)
  if (ini.length > 1) {
    init = init + ini[1].substr(0, 1)
  }
  let total = 0
  for (let x = 0; x < response.detail.length; x++) {
    const element = response.detail[x];
    total = total + element.total
    element.tglkadaluarsa = H.formatDate(element.tglkadaluarsa, 'DD-MMM-YYYY')
  }
  TOTAL.value = formatRp(total, '')
  response.header.initials = init
  dataPasien.value = response.header
  let groupData = group(response.detail)
  dataSource.value = groupData
  await sleep(2000)
  expandAll()
  dataPasien.value.loading = false
}
const onInitKronis = async() =>{

  const response = await useApi().get(
    `/farmasi/transaksi-pelayanan-farmasi-kronis?norec_pd=${NOREC_PD}`)

  let total = 0
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    total = total + element.total
    element.tglkadaluarsa = H.formatDate(element.tglkadaluarsa, 'DD-MMM-YYYY')
  }
  TOTALKRONIS.value = formatRp(total, '')
  let groupData = group(response)
  dataSourceKronis.value = groupData
}

const cekUserSession = () =>{
  console.log('user login',kelompokUser);
}
const confirm2 = (callback) => {
    confirm.require({
        message: 'Yakin ingin menghapus?',
        header: 'Perhatian!!!',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Batal',
        acceptLabel: 'Hapus',
        rejectClass: 'p-button-secondary p-button-outlined',
        acceptClass: 'p-button-danger',
        accept: callback,
        reject: () => {
            toast.add({ severity: 'error', summary: 'Rejected', detail: 'Penghapusan dibatalkan', life: 3000 });
        }
    });
};

const tambahResep = async () => {
  await H.statusClosingPasien(NOREC_PD); 

  if (!item.value.resepAkhir) {
    modalAntrian.value = true
  }

  dataSourceAntrian.value.loading = true
  const response = await useApi().get(
    `/farmasi/transaksi-pelayanan-farmasi-modal?norec_pd=${NOREC_PD}`)
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    element.no = x + 1
  }
  dataSourceAntrian.value = response
  dataSourceAntrian.value.loading = false

  if (item.value.resepAkhir == true) {
    gotoResep({ norec_pd: response[0].norec_pd, norec: response[0].norec,isnewresep: true })
  }

}
function clearFilter() {

}
function editResep(e: any) {
  if (e.nostruk != null) {
    useToaster().error('Data Sudah di verifikasi tidak bisa diedit')
    return
  }
  router.push({
    name: 'module-farmasi-input-resep',
    query: {
      norec_apd: e.norec_apd,
      norec_pd: e.norec_pd,
      norec_resep: e.norec_resep,
    },
    params: {
      norec_apd: e.norec_apd,
      norec_pd: e.norec_pd,
      norec_resep: e.norec_resep,
    }
  })
}

async function returResep(e: any) {
  console.log('ini norec',NOREC_PD)
  await H.statusClosingPasien(NOREC_PD); 
  router.push({
    name: 'module-farmasi-retur-resep-farmasi',
    query: {
      norec_apd: e.norec_apd,
      norec_pd: e.norec_pd,
      norec_resep: e.norec_resep,
    } 
  });
}


function gotoResep(e: any) {
  router.push({
    name: 'module-farmasi-input-resep',
    query: {
      norec_apd: e.norec,
      norec_pd: e.norec_pd,
      isnewresep : e.isnewresep
    },
    params: {
      norec_apd: e.norec,
      norec_pd: e.norec_pd,
      isnewresep : e.isnewresep
    }
  })
}
function confirmDelete(data) {
  showDeleteModal.value = true;
  selectedData.value = data;

}
function hapusResep(e) {
  showDeleteModal.value = false;

  if (e.nostruk != null) {
    useToaster().error('Data Sudah di verifikasi tidak bisa dihapus');
    return;
  }

  dataSource.value.loading = true;
  useApi().post(
    '/farmasi/transaksi-pelayanan-farmasi-hapus', {
      'norec': e.norec_resep
    }
  ).then((response) => {
    dataSource.value.loading = false;
    if (!response.isAxiosError) {
      onInit();
    }
  });
}
const nextHapus = (e:any) =>{
  if (e.nostruk != null) {
    useToaster().error('Data Sudah di verifikasi tidak bisa dihapus')
    return
  }
  // isLoading.value = true
  dataSource.value.loading = true
  useApi().post(
    `/farmasi/transaksi-pelayanan-farmasi-hapus`, {
    'norec': e.norec_resep
  }
  ).then((response: any) => {
    // isLoading.value = false
    dataSource.value.loading = false
    if (!response.isAxiosError) {
      onInit()
    }
  })
}
function group(datas: any) {
  let sama = false
  let arrGroup = [];
  for (let i = 0; i < datas.length; i++) {
    sama = false
    datas[i].rke = parseInt(datas[i].rke)
    // datas[i].details = []
    for (let x = 0; x < arrGroup.length; x++) {
      if (arrGroup[x].norec_resep == datas[i].norec_resep) {
        arrGroup[x].total = parseFloat(arrGroup[x].total) + parseFloat(datas[i].total)
        // arrGroup[x].details.push(datas[i])
        sama = true;
      }
    }
    if (sama == false) {
      let result = {
        'norec_apd': datas[i].norec_apd,
        'norec_pd': datas[i].norec_pd,
        'norec_resep': datas[i].norec_resep,
        'noresep': datas[i].noresep,
        'dokter': datas[i].dokter,
        'tglpelayanan': datas[i].tglpelayanan,
        'ruangandepoid': datas[i].ruangandepoid,
        'namaruangan': datas[i].namaruangan,
        'ruangandepo': datas[i].ruangandepo,
        'noregistrasi': datas[i].noregistrasi,
        'nostruk': datas[i].nostruk,
        'total': datas[i].total,
        'details': []
      }
      arrGroup.push(result)
    }
  }
  datas.sort(compareRKE);
  arrGroup.sort(compare);
  for (let x = 0; x < arrGroup.length; x++) {
    const element: any = arrGroup[x];
    element.no = x + 1
    for (let z = 0; z < datas.length; z++) {
      const elementx = datas[z];
      elementx.no = z + 1;
      if (elementx.norec_resep == element.norec_resep) {
        element.details.push(elementx)
      }
    }
  }
  console.log(arrGroup)
  return arrGroup

}
function compare(a: any, b: any) {
  if (a.tglpelayanan > b.tglpelayanan) {
    return -1;
  }
  if (a.tglpelayanan < b.tglpelayanan) {
    return 1;
  }
  return 0;
}
function compareRKE(a: any, b: any) {
  if (a.rke < b.rke) {
    return -1;
  }
  if (a.rke > b.rke) {
    return 1;
  }
  return 0;
}

const cetak = async () => {
  qzService.printData(`farmasi/report-kwitansi-pengembalian-obat-dibayar?pdf=true&norec_pd=${NOREC_PD}`,'KWITANSI', 1)
  // await useApi().get(`/farmasi/report-kwitansi-pengembalian-obat-dibayar?norec_pd=${NOREC_PD}`)
  // H.printBlade(`farmasi/report-kwitansi-pengembalian-obat-dibayar?pdf=true&norec_pd=${NOREC_PD}`)
}
const cetakResep = async (e: any) => {
  // let stt
  // if (confirm('View Resep ? ')) {
  //   stt = true;
  // } else {
  //   stt = false
  // }
  // console.log(stt)
  qzService.printData(`report/farmasi/resep?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`,'RESEP',1)
  // H.printBlade(`report/farmasi/resep?pdf=true&norec=${e.norec_resep}&norec_order=`)
}
const cetakLabel = async (e: any) => {
  qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norec=${e.norec_resep}`, 'LABEL RESEP', 1)
  // H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norec=${e.norec_resep}`)
}
const cetakRekapLabel = async (e: any,isInjeksi:any) => {
  if (!e) {
    H.alert('error', 'Obat disalin tidak boleh kosong')
    return
  }
  let items = []
  e.forEach((element: any) => {
    items = [...new Set([...items, element.produkfk])]
  });

  qzService.printData(`report/farmasi/label-custom?pdf=true&norec_resep=${selected.value.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`, 'LABEL RESEP', 1)
  // H.printBlade(`report/farmasi/label-custom?pdf=true&norec_resep=${selected.value.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`)

  // let Norec = e.norec_resep;
  // useApi().get("report/farmasi/get-data-waktuminum-resep?Norec_sr=" + Norec).then((dat) => {
  //   let Produkfk = ''


  //   let JmlPagi: any = 0;
  //   let JmlSiang: any = 0;
  //   let JmlSore: any = 0;
  //   let JmlMalam: any = 0;
  //   let waktuMinum: any = ''
  //   dat.forEach((element: any) => {
  //     let customData: any = {};
  //     for (let key in element) {
  //       switch (key) {
  //         case "pagi":
  //           if (element.pagi != '-') {
  //             if (JmlPagi < 1) {
  //               JmlPagi = parseFloat(JmlPagi) + 1;
  //               waktuMinum = 'Pagi';
  //             }
  //           }
  //           break;
  //         case "siang":
  //           if (element.siang != '-') {
  //             if (JmlSiang < 1) {
  //               JmlSiang = parseFloat(JmlSiang) + 1;
  //               waktuMinum += ',Siang';
  //               // H.printBlade("report/farmasi/cetak-apotik-rekap-label?pdf=true&norec=" + Norec +  "&produkfk=" + Produkfk + '&waktuMinum=' + isSiang);
  //             }
  //           }
  //           break;
  //         case "sore":
  //           if (element.sore != '-') {
  //             if (JmlSore < 1) {
  //               JmlSore = parseFloat(JmlSore) + 1;

  //               waktuMinum += ',Sore';
  //               // H.printBlade("report/farmasi/cetak-apotik-rekap-label?pdf=true&norec=" + Norec +  "&produkfk=" + Produkfk + '&waktuMinum=' + isSore);
  //             }
  //           }
  //           break;
  //         case "malam":
  //           if (element.malam != '-') {
  //             if (JmlMalam < 1) {
  //               JmlMalam = parseFloat(JmlMalam) + 1;
  //               // let isMalam = 'Malam';
  //               waktuMinum += ',Sore';
  //               // H.printBlade("report/farmasi/cetak-apotik-rekap-label?pdf=true&norec=" + Norec +  "&produkfk=" + Produkfk + '&waktuMinum=' + isMalam);
  //             }
  //           }
  //           break;
  //         default:
  //           customData[key] = element[key];
  //           break;
  //       }
  //     };
  //   });
  //   waktuMinum = waktuMinum.substring(1, waktuMinum.length)
  //   H.printBlade("report/farmasi/cetak-apotik-rekap-label?pdf=true&norec=" + Norec + "&produkfk=" + Produkfk + '&waktuMinum=' + waktuMinum);
  // })
  // return
}

const showModalDetailVerif = async (e: any) => {
modalResepVerify.value = true
sourceDetailVerif.value.loading = true
item.value.norec_resep = e
const response = await useApi().get(`/farmasi/input-resep-edit?norecResep=${e.norec_resep}`)
sourceDetailVerif.value.loading = false
sourceDetailVerif.value = response.pelayananPasien
}

const toggle = (event: any, e: any) => {
  op.value.toggle(event);
  selected.value = e
}
const hapusResepKronis= (e: any) => {
  if (e.nostruk != null) {
    useToaster().error('Data Sudah di verifikasi tidak bisa dihapus')
    return
  }
  // isLoading.value = true
  dataSourceKronis.value.loading = true
  useApi().post(
    `/farmasi/transaksi-pelayanan-farmasi-hapus-kronis`, {
    'norec': e.norec_resep
  }
  ).then((response: any) => {
    // isLoading.value = false
    dataSourceKronis.value.loading = false
    if (!response.isAxiosError) {
      onInitKronis()
    }
  })

}
const CetakKwitansiResep23Blade = (e:any) =>{
  H.printBlade(`report/farmasi/kwitansi-obat-23?pdf=true&norec=${e.norec_resep}`)
}
const cetakResep23Blade = (e:any) =>{
  H.printBlade(`report/farmasi/resep-obat-23?pdf=true&norec=${e.norec_resep}`)
}

const expandAll = () => {
    expandedRows.value = dataSource.value.filter((p) => p.norec_resep);
};
watch(
  () => activeIdx.value,
  (newValue, oldValue) => {
    if (newValue == 0) {
      onInit()
    }
    if (newValue == 1) {
      onInitKronis()
    }
  }
)
cekUserSession()

onInit()

</script>

<style lang="scss">
.modal.is-big .modal-content {
  width: 100%;
  max-width: 1240px;
}

@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';

// .dropdown.is-dots .is-trigger svg {
//     color: white;
// }

.field>label {
  color: var(--white) !important;
}

.hr-dashboard .block-header .center {
  width: 70% !important;
  padding-right: 30px;
  margin-right: 0px;
  border-right: none !important;
}

.text-white {
  color: var(--white) !important;
}
</style>
