<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Penerimaan Darah</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined RouterLink>
                  Kembali
                </VButton>
                <div>
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save" @click="saveData()" 
                    :loading="isLoadBtnSave">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-body p-4">
          <div style="margin-top:2rem" v-if="loadData">
            <VPlaceloadWrap class="mt-5">
              <VPlaceload height="30px" width="40%" class="mx-2" />
              <VPlaceload height="30px" width="30%" class="mx-2" />
              <VPlaceload height="30px" width="40%" class="mx-2" />
            </VPlaceloadWrap>
            <VPlaceloadWrap class="mt-5">
              <VPlaceload height="30px" width="50%" class="mx-2" />
              <VPlaceload height="30px" width="30%" class="mx-2" />
              <VPlaceload height="30px" width="20%" class="mx-2" />
            </VPlaceloadWrap>
            <VPlaceloadWrap class="mt-5">
              <VPlaceload height="30px" width="50%" class="mx-2" />
              <VPlaceload height="30px" width="30%" class="mx-2" />
              <VPlaceload height="30px" width="20%" class="mx-2" />
            </VPlaceloadWrap>
          </div>

          <div v-else>
            <div class="columns is-multiline pl-3 pr-3">
              <!-- <div class="column is-4">
                <VField label="Nomor Terima">
                  <VControl>
                    <input v-model="item.noTerima" type="text" class="input" placeholder="Nomer Terima" />
                  </VControl>
                </VField>
              </div> -->
              <div class="column is-4">
                <VDatePicker v-model="item.tglPengambilDarah" color="green" trim-weeks mode="dateTime" :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel class="required-field" style="text-overflow:unset">Tanggal Pengambilan Darah</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Tanggal Pengambilan Darah" :value="inputValue"
                          v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-4">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Sumber Dana</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.sumberDana" :options="d_SumberDana" optionLabel="label"  
                      placeholder="Pilih Jenis Darah" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Gudang</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.ruangan" :options="d_Gudang" optionLabel="label"  
                      placeholder="Pilih Jenis Darah" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-multiline pl-3 pr-3 pb-3">
              <div class="column is-4 pt-0">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Sumber PMI</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown 
                    v-model="item.sumberPmi" 
                    :options="d_Rekanan" 
                    optionLabel="label" 
                    placeholder="Pilih Sumber PMI" 
                    style="width: 100%;" 
                    :filter="true" 
                  />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Kelompok Barang</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.kelompokProduk" :options="d_KelompokProduk" optionLabel="label"  
                      placeholder="Pilih Gudang" style="width: 100%;font-weight:bold" :filter="true" disabled />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 pt-0">
                <VField label="Pegawai Penerima" class="is-rounded-select is-autocomplete-select">
                  <VControl icon="fa:user" class="prime-auto-cus">
                    <AutoComplete v-model="item.pegawaimenerimafk" :suggestions="d_Pegawai" :optionLabel="'label'"
                      @complete="fetchPegawai($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai Penerima" />
                  </VControl>
                </VField>
              </div>
            </div>

          </div>
        </div>

      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="fas fa-plus-circle" @click="showModal(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="sourceDarah" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="sourceDarah.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="nomerKantong" header="No Kantong"></Column>
            <Column field="nopermintaan" header="No Permintaan"></Column>
            <Column field="tujuan" header="Tujuan"></Column>
            <Column field="jenisDarah" header="Jenis Darah" />
            <Column field="produkDarah" header="Produk" style="min-width:120px" />
            <Column field="golonganDarah" header="Golongan" />
            <Column field="satuan" header="Satuan" />
            <Column field="qtykantong" header="QTY" />
            <Column field="volumeDarah" header="Volume(ml)" />
            <Column field="tglkadaluarsa" header="TGL Kadaluarsa">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="hasilrelease" header="Hasil Release" />
            <Column field="suhulabel" header="Suhu" />
            <Column field="tglafteapdarah" header="Tgl Afteap">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglpengelolahandarah" header="Tgl Penglolahan">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglreleasedarah" header="Tgl Release">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
              <template #body="slotProps">
                <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
                  :loading="loadingBtnEdit" v-tooltip.top="'Edit'" @click="showModal(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </VCard>
      </div>


      <div class="column is-12 p-0 mt-5">
        <VCard>
          <span> Riwayat Penerimaan Darah</span>
          <DataTable :value="sourceDarahRiwayat" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="sourceDarahRiwayat.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
              <template #body="slotProps">
                <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
                  :loading="loadingBtnEdit" v-tooltip.top="'Pengurangan Stok'" @click="showModalPengurangan(slotProps.data)">
                </VIconButton>
                <!-- <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                </VIconButton> -->
              </template>
            </Column>
            <Column field="nomerKantong" header="No Kantong"></Column>
            <Column field="nopermintaan" header="No Permintaan"></Column>
            <Column field="tujuan" header="Tujuan"></Column>
            <Column field="jenisDarah" header="Jenis Darah" />
            <Column field="produkDarah" header="Produk" style="min-width:120px" />
            <Column field="golonganDarah" header="Golongan" />
            <Column field="satuan" header="Satuan" />
            <Column field="qtykantong" header="QTY" />
            <Column field="volumeDarah" header="Volume(ml)" />
            <Column field="tglkadaluarsa" header="TGL Kadaluarsa">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="hasilrelease" header="Hasil Release" />
            <Column field="suhulabel" header="Suhu" />
            <Column field="tglafteapdarah" header="Tgl Afteap">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglpengelolahandarah" header="Tgl Penglolahan">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglreleasedarah" header="Tgl Release">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
          </DataTable>
        </VCard>
      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <span> Riwayat Pengurangan Darah</span>
          <DataTable :value="sourceDarahRiwayat" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="sourceDarahRiwayat.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <!-- <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
              <template #body="slotProps">
                <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
                  :loading="loadingBtnEdit" v-tooltip.top="'Pengurangan Stok'" @click="showModalPengurangan(slotProps.data)">
                </VIconButton>
              </template>
            </Column> -->
            <Column field="nomerKantong" header="No Kantong"></Column>
            <Column field="nopermintaan" header="No Permintaan"></Column>
            <Column field="tujuan" header="Tujuan"></Column>
            <Column field="jenisDarah" header="Jenis Darah" />
            <Column field="produkDarah" header="Produk" style="min-width:120px" />
            <Column field="golonganDarah" header="Golongan" />
            <Column field="satuan" header="Satuan" />
            <Column field="qtyprodukpermintaan" header="QTY" />
            <Column field="volumepermintaan" header="Volume(ml)" />
            <Column field="tglkadaluarsa" header="TGL Kadaluarsa">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="hasilrelease" header="Hasil Release" />
            <Column field="suhulabel" header="Suhu" />
            <Column field="tglafteapdarah" header="Tgl Afteap">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglpengelolahandarah" header="Tgl Penglolahan">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglreleasedarah" header="Tgl Release">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
          </DataTable>
        </VCard>
      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <span> Semua Data Kalkulasi Darah</span>
          <DataTable :value="sourceDarahKalkulasi" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="sourceDarahRiwayat.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <!-- <Column field="no" header="No"></Column> -->
            <!-- <Column field="nomerKantong" header="No Kantong"></Column> -->
            <!-- <Column field="nopermintaan" header="No Permintaan"></Column> -->
            <!-- <Column field="tujuan" header="Tujuan"></Column> -->
            <!-- <Column field="jenisDarah" header="Jenis Darah" /> -->
            <Column field="namaproduk" header="Produk" style="min-width:120px" />
            <Column field="golongandarah" header="Golongan" />
            <Column field="satuanstandar" header="Satuan" />
            <Column field="total_qtyproduk" header="QTY Semua" />
            <!-- <Column field="volumeDarah" header="Volume(ml)" /> -->
            <!-- <Column field="tglkadaluarsa" header="TGL Kadaluarsa">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column> -->
            <!-- <Column field="hasilrelease" header="Hasil Release" /> -->
            <Column field="namasuhu" header="Suhu" />
            <!-- <Column field="tglafteapdarah" header="Tgl Afteap">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column> -->
            <!-- <Column field="tglpengelolahandarah" header="Tgl Penglolahan">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglreleasedarah" header="Tgl Release">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column> -->
          </DataTable>
        </VCard>
      </div>

    </div>
  </div>


  <VModal is="form" :open="modalInput" title="Form Input Detail" :cancel-label="'Tutup'" size="large" actions="right"
    @close="modalInput = false, clear()">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-4">
          <VField>
            <VLabel class="required-field">Nomor Kantong</VLabel>
            <VControl>
              <input v-model="item.nomerKantong" type="text" class="input" placeholder="Nomor Kantong" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Jenis Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.jenisDarah" :options="d_JenisDarah" optionLabel="label"
                placeholder="Pilih Jenis Darah" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Golongan Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.golonganDarah" :options="d_GolonganDarah" optionLabel="label"
                placeholder="Pilih Golongan Darah" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-6">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Produk Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.produkDarah" :options="d_Produk" optionLabel="label"
                @change="getSatuan(item.produkDarah)" placeholder="Pilih Produk Darah" style="width: 100%;"
                :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-6">
          <VDatePicker v-model="item.tglpenglolahan" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Pengelolahan</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Pengelolahan" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-4">
          <VDatePicker v-model="item.tglpengiriman" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Pengiriman</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal pengiriman" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-4">
          <VDatePicker v-model="item.tglaftep" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Aftap</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Aftap" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-4">
          <VDatePicker v-model="item.tglkadaluarsa" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Kadaluarsa</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Kadaluarsa" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-4">
          <VDatePicker v-model="item.tglrelease" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Release</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Release" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Suhu Simpan</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.suhu" :options="d_Suhu" optionLabel="label"
                placeholder="Pilih Suhu Simpan" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Hasil Release</VLabel> 
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown 
                v-model="item.hasilrelease" 
                :options="hasilrelease_M" 
                optionLabel="label" 
                placeholder="Pilih Hasil Release" 
                style="width: 100%;" 
                :filter="true" 
              />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Satuan</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" @change="getKonversi(item.satuan)"
                placeholder="Pilih Produk Darah" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Konversi">
            <VControl>
              <input v-model="item.konversi" type="text" class="input" placeholder="Konversi" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField>
            <VLabel class="required-field">QTY Kantong</VLabel>
            <VControl>
              <input v-model="item.qtykantong" type="number" class="input" placeholder="Jumlah Darah" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Volume Darah">
            <VControl>
              <input v-model="item.volumeDarah" type="number" class="input" placeholder="Volume Kantong" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="No Permintaan">
            <VControl>
              <input v-model="item.nopermintaan" type="text" class="input" placeholder="No Permintaan" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="No Pengiriman">
            <VControl>
              <input v-model="item.pengiriman" type="text" class="input" placeholder="No Pengiriman" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Tujuan">
            <VControl>
              <input v-model="item.tujuan" type="text" class="input" placeholder="Tujuan" />
            </VControl>
          </VField>
        </div>
        <div class="column is-6">
          <VField label="Catatan">
            <VControl>
              <VTextarea 
                v-model="item.catatan" 
                class="textarea" 
                placeholder="isi catatan"
              />
            </VControl>
          </VField>
        </div>        
      </div>

      <div class="column is-12">
        <div class="content">
          <div class="is-divider" data-content="Penerimaan Darah" />
        </div>
      </div>

      <DataTable :value="sourceDarah" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
        :loading="sourceDarah.loading" class="p-datatable-sm"
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
        <Column field="no" header="No"></Column>
        <Column field="nomerKantong" header="No Kantong"></Column>
        <Column field="nopermintaan" header="No Permintaan"></Column>
        <Column field="tujuan" header="Tujuan"></Column>
        <Column field="jenisDarah" header="Jenis Darah" />
        <Column field="produkDarah" header="Produk" style="min-width:120px" />
        <Column field="golonganDarah" header="Golongan" />
        <Column field="satuan" header="Satuan" />
        <Column field="qtykantong" header="QTY" />
        <Column field="volumeDarah" header="Volume(ml)" />
        <Column field="hasilrelease" header="Hasil Release" />
            <Column field="suhulabel" header="Suhu" />
            <Column field="tglafteapdarah" header="Tgl Afteap">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglpengelolahandarah" header="Tgl Penglolahan">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglreleasedarah" header="Tgl Release">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
        <Column field="tglkadaluarsa" header="TGL Kadaluarsa">
          <template #body="slotProps">
            {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
          </template>
        </Column>
        <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
          <template #body="slotProps">
            <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
              :loading="loadingBtnEdit" v-tooltip.top="'Edit'" @click="showModal(slotProps.data)">
            </VIconButton>
            <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised v-tooltip.top="'Hapus'"
              @click="dialogConfirm(slotProps.data)">
            </VIconButton>
          </template>
        </Column>
      </DataTable>

    </template>

    <template #action>
      <VButton color="primary" raised @click="addData(item)">Simpan</VButton>
    </template>
    
  </VModal>

  
  <VModal is="form" :open="modalPenguranganStok" title="Form Pengurangan stok darah" :cancel-label="'Tutup'" size="large" actions="right"
    @close="modalPenguranganStok = false, clear()">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-4">
          <VField>
            <VLabel class="required-field">Nomor Kantong</VLabel>
            <VControl>
              <input v-model="item.nomerKantong" type="text" class="input" placeholder="Nomor Kantong" />
            </VControl>
          </VField>
        </div>
        <!-- <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Jenis Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.jenisDarah" :options="d_JenisDarah" optionLabel="label"
                placeholder="Pilih Jenis Darah" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div> -->
        <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Golongan Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.golonganDarah" :options="d_GolonganDarah" optionLabel="label"
                placeholder="Pilih Golongan Darah" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Produk Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.produkDarah" :options="d_Produk" optionLabel="label"
                @change="getSatuan(item.produkDarah)" placeholder="Pilih Produk Darah" style="width: 100%;"
                :filter="true" />
            </VControl>
          </VField>
        </div>
      </div>
      <!-- <div class="columns is-multiline"> -->
        <!-- <div class="column is-6">
          <VDatePicker v-model="item.tglpenglolahan" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Pengelolahan</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Pengelolahan" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div> -->
        <!-- <div class="column is-4">
          <VDatePicker v-model="item.tglpengiriman" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Pengiriman</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal pengiriman" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div> -->
        <!-- <div class="column is-4">
          <VDatePicker v-model="item.tglaftep" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Aftap</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Aftap" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div> -->
        <!-- <div class="column is-4">
          <VDatePicker v-model="item.tglkadaluarsa" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Kadaluarsa</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Kadaluarsa" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div> -->
        <!-- <div class="column is-4">
          <VDatePicker v-model="item.tglrelease" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Release</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Release" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div> -->
        <!-- <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Suhu Simpan</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.suhu" :options="d_Suhu" optionLabel="label"
                placeholder="Pilih Suhu Simpan" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div> -->
        <!-- <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Hasil Release</VLabel> 
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown 
                v-model="item.hasilrelease" 
                :options="hasilrelease_M" 
                optionLabel="label" 
                placeholder="Pilih Hasil Release" 
                style="width: 100%;" 
                :filter="true" 
              />
            </VControl>
          </VField>
        </div> -->
      <!-- </div> -->

      <div class="columns is-multiline">
        <!-- <div class="column is-3">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Satuan</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" @change="getKonversi(item.satuan)"
                placeholder="Pilih Produk Darah" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div> -->
        <!-- <div class="column is-3">
          <VField label="Konversi">
            <VControl>
              <input v-model="item.konversi" type="text" class="input" placeholder="Konversi" />
            </VControl>
          </VField>
        </div> -->
        <div class="column is-3">
          <VField>
            <VLabel class="required-field">QTY Kantong diperlukan</VLabel>
            <VControl>
              <input v-model="item.qtykantong" type="text" class="input" placeholder="Jumlah Darah" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Volume Darah">
            <VControl>
              <input v-model="item.volumeDarah" type="text" class="input" placeholder="Volume Kantong" />
            </VControl>
          </VField>
        </div>
        <!-- <div class="column is-3">
          <VField label="No Permintaan">
            <VControl>
              <input v-model="item.nopermintaan" type="text" class="input" placeholder="No Permintaan" />
            </VControl>
          </VField>
        </div> -->
        <!-- <div class="column is-3">
          <VField label="No Pengiriman">
            <VControl>
              <input v-model="item.pengiriman" type="text" class="input" placeholder="No Pengiriman" />
            </VControl>
          </VField>
        </div> -->
        <!-- <div class="column is-3">
          <VField label="Tujuan">
            <VControl>
              <input v-model="item.tujuan" type="text" class="input" placeholder="Tujuan" />
            </VControl>
          </VField>
        </div> -->
        <div class="column is-6">
          <VField label="Alasan">
            <VControl>
              <VTextarea 
                v-model="item.alasan" 
                class="textarea" 
                placeholder="isi alasan"
              />
            </VControl>
          </VField>
        </div>        
      </div>

      <!-- <div class="column is-12">
        <div class="content">
          <div class="is-divider" data-content="Penerimaan Darah" />
        </div>
      </div> -->

      <!-- <DataTable :value="sourceDarah" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
        :loading="sourceDarah.loading" class="p-datatable-sm"
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
        <Column field="no" header="No"></Column>
        <Column field="nomerKantong" header="No Kantong"></Column>
        <Column field="nopermintaan" header="No Permintaan"></Column>
        <Column field="tujuan" header="Tujuan"></Column>
        <Column field="jenisDarah" header="Jenis Darah" />
        <Column field="produkDarah" header="Produk" style="min-width:120px" />
        <Column field="golonganDarah" header="Golongan" />
        <Column field="satuan" header="Satuan" />
        <Column field="qtykantong" header="QTY" />
        <Column field="volumeDarah" header="Volume" />
        <Column field="hasilrelease" header="Hasil Release" />
            <Column field="suhulabel" header="Suhu" />
            <Column field="tglafteapdarah" header="Tgl Afteap">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglpengelolahandarah" header="Tgl Penglolahan">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
            <Column field="tglreleasedarah" header="Tgl Release">
              <template #body="slotProps">
                {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
              </template>
            </Column>
        <Column field="tglkadaluarsa" header="TGL Kadaluarsa">
          <template #body="slotProps">
            {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
          </template>
        </Column>
        <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
          <template #body="slotProps">
            <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
              :loading="loadingBtnEdit" v-tooltip.top="'Edit'" @click="showModal(slotProps.data)">
            </VIconButton>
            <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised v-tooltip.top="'Hapus'"
              @click="dialogConfirm(slotProps.data)">
            </VIconButton>
          </template>
        </Column>
      </DataTable> -->

    </template>

    <template #action>
      <VButton color="primary" raised @click="savePenguranganStok(item)">Simpan</VButton>
    </template>
    
  </VModal>


</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import moment from 'moment'
import Checkbox from 'primevue/checkbox';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'
useHead({
  title: 'Form Purchase Request - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoadingPasien: any = ref(false)

const confirm = useConfirm()
const router = useRouter();
const route = useRoute()

let status = route.query.keterangan as string

const item: any = ref({
  tglPR: new Date(),
  tglPengambilDarah: new Date(),
  hasilrelease: null,
  sumberPmi:null,
  norec: null,
  sumberDana: null,
  sumberPmi: null,
  ruangan: null,
  kelompokProduk: null,
  pegawaimenerimafk: null,
})

const d_GolonganDarah = ref([])
const d_JenisDarah = ref([])
const d_Rekanan = ref([])
const d_KelompokProduk = ref([])
const d_SumberDana = ref([])
const d_Pegawai = ref([])
const d_Produk = ref([])
const d_Satuan = ref([])
const d_Gudang = ref([])
const d_Suhu = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const dataProdukDetail: any = ref([])
const modalInput: any = ref(false)
const modalPenguranganStok: any = ref(false)
const loadHarga = ref(false)
const listColor: any = ref([])
const norec_spd: any = ref('')
const nostruk: any = ref()
const noBukti: any = ref()
let sourceDarah: any = ref([])
let sourceDarahRiwayat: any = ref([])
let sourceDarahKalkulasi: any = ref([])
let loadingBtnEdit: any = ref(false)
let isLoadBtnSave: any = ref(false)
const loadData: any = ref(true)
let isLoadProduk: any = ref(false)

for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i]
  if (i <= 9 && element != 'primary') listColor.value.push(element)
}
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const hasilrelease_M = ref<any[]>([
  { value: 1, label: "Lulus" },
  { value: 2, label: "Tidak Lulus" }
]);

const saveData = async () => {

  if (sourceDarah.value.length == 0) {
    H.alert('error', 'Data Tidak Tersedia')
    return
  }
  if (!item.value.sumberDana) {
    H.alert('error', 'Sumber Dana Tidak Boleh Kosong')
    return
  }
  if (!item.value.sumberPmi) {
    H.alert('error', 'Sumber PMI Tidak Boleh Kosong')
    return
  }
  if (!item.value.pegawaimenerimafk) {
    H.alert('error', 'Sumber PMI Tidak Boleh Kosong')
    return
  }

  isLoadBtnSave.value = true
  let head = {
  norecOrder: null,
  nostruk: item.value.norec, 
  tglPengambilDarah: H.formatDate(item.value.tglPengambilDarah, 'YYYY-MM-DD HH:mm:ss'),
  asalproduk: item.value.sumberDana.value,
  namarekanan: item.value.sumberPmi.label,
  rekananfk: item.value.sumberPmi.value,
  ruangan: item.value.ruangan.label,
  ruanganfk: item.value.ruangan.value,
  kelompokProduk: item.value.kelompokProduk.value,
  pegawaimenerimafk: item.value.pegawaimenerimafk.value,
  namapegawaipenerima: item.value.pegawaimenerimafk.label,
  qtyproduk: sourceDarah.value.length
};

  let objSave = {
    struk : head,
    details: sourceDarah.value
  }

  console.log(objSave)
  await useApi().post('bank-darah/save-penerimaan-darah', objSave).then((response) => {
    // goToPageDaftar()
  })
  isLoadBtnSave.value = false
}

const addData = (e: any) => {

  if (!item.value.nomerKantong) {
    H.alert('error', 'Nomor Kantong Tidak Boleh Kosong')
    return
  }
  if (!item.value.jenisDarah) {
    H.alert('error', 'Jenis Darah Tidak Boleh Kosong')
    return
  }
  if (!item.value.golonganDarah) {
    H.alert('error', 'Golongan Darah Tidak Boleh Kosong')
    return
  }
  if (!item.value.produkDarah) {
    H.alert('error', 'Produk Darah Tidak Boleh Kosong')
    return
  }
  if (!item.value.tglkadaluarsa) {
    H.alert('error', 'Tgl Kadaluarsa Tidak Boleh Kosong')
    return
  }
  if (!item.value.qtykantong) {
    H.alert('error', 'Jumlah Kantong Tidak Boleh Kosong')
    return
  }
  if (!item.value.tglaftep) {
    H.alert('error', 'Tanggal aftep harus di pilih')
    return
  }
  if (!item.value.tglpenglolahan) {
    H.alert('error', 'Tanggal pengelolahan harus di pilih')
    return
  }
  if (!item.value.pengiriman) {
    H.alert('error', 'Pengiriman harus di pilih')
    return
  }
  if (!item.value.tglrelease) {
    H.alert('error', 'Tanggal release harus di pilih')
    return
  }
  if (!item.value.suhu) {
    H.alert('error', 'Suhu belum di pilih')
    return
  }
  if (!item.value.hasilrelease) {
    H.alert('error', 'Hasil release tidak boleh kosong')
    return
  }
  let datas: any = {}
  if (e.no) {
    sourceDarah.value.forEach((element: any, i: any) => {
      if (element.no == e.no) {
        datas.no = element.no,
          datas.nomerKantong = e.nomerKantong,
          datas.nopermintaan = e.nopermintaan,
          datas.pengiriman = e.pengiriman,
          datas.jenisDarah = e.jenisDarah.label,
          datas.tujuan = e.tujuan,
          datas.jenisDarahfk = e.jenisDarah.value,
          datas.golonganDarah = e.golonganDarah.label,
          datas.golonganDarahfk = e.golonganDarah.value,
          datas.produkDarah = e.produkDarah.label,
          datas.produkDarahfk = e.produkDarah.value,
          datas.satuanfk = e.satuan.value,
          datas.satuan = e.satuan.label,
          datas.konversi = e.konversi,
          datas.volumeDarah = e.volumeDarah ? e.volumeDarah : null,
          datas.qtykantong = e.qtykantong,
          datas.suhu = e.suhu.value,
          datas.suhulabel = e.suhu.label,
          datas.hasilrelease = e.hasilrelease.label,
          datas.tglkadaluarsa = H.formatDate(e.tglkadaluarsa, 'YYYY-MM-DD HH:mm:ss'),
          datas.tglaftep = H.formatDate(e.tglaftep, 'YYYY-MM-DD HH:mm:ss'),
          datas.tglpenglolahan = H.formatDate(e.tglpenglolahan, 'YYYY-MM-DD HH:mm:ss'),
          datas.tglrelease = H.formatDate(e.tglrelease, 'YYYY-MM-DD HH:mm:ss'),
          datas.tglpengiriman = H.formatDate(e.tglpengiriman, 'YYYY-MM-DD HH:mm:ss'),
          sourceDarah.value[i] = datas
      }
    });
  } else {
    datas = {
      no: sourceDarah.value.length == 0 ? 1 : sourceDarah.value.length + 1,
      nomerKantong: e.nomerKantong,
      jenisDarah: e.jenisDarah.label,
      jenisDarahfk: e.jenisDarah.value,
      golonganDarah: e.golonganDarah.label,
      golonganDarahfk: e.golonganDarah.value,
      produkDarah: e.produkDarah.label,
      produkDarahfk: e.produkDarah.value,
      satuanfk: e.satuan.value,
      satuan: e.satuan.label,
      konversi: e.konversi,
      catatan: e.catatan ? e.catatan : null,
      nopermintaan: e.nopermintaan ? e.nopermintaan : null,
      pengiriman: e.pengiriman ? e.pengiriman : null,
      tujuan: e.tujuan ? e.tujuan : null,
      volumeDarah: e.volumeDarah ? e.volumeDarah : null,
      qtykantong: e.qtykantong,
      suhu : e.suhu.value,
      suhulabel : e.suhu.label,
      hasilrelease : e.hasilrelease.label,
      tglkadaluarsa: H.formatDate(e.tglkadaluarsa, 'YYYY-MM-DD HH:mm:ss'),
      tglaftep : H.formatDate(e.tglaftep, 'YYYY-MM-DD HH:mm:ss'),
      tglpenglolahan : H.formatDate(e.tglpenglolahan, 'YYYY-MM-DD HH:mm:ss'),
      tglpengiriman : H.formatDate(e.tglpengiriman, 'YYYY-MM-DD HH:mm:ss'),
      tglrelease : H.formatDate(e.tglrelease, 'YYYY-MM-DD HH:mm:ss'),
    }
    sourceDarah.value.push(datas)
  }
  if (sourceDarah.value.length > 0) {
    clear()
  }
}


const savePenguranganStok = (e: any) => {

  let datas = {
    'nokantong':e.nomerKantong,
    'goldarah':e.golonganDarah.value,
    'prd_id':e.produkDarah.value,
    'qtykantong':e.qtykantong,
    'volumedarah':e.volumeDarah,
    'alasan':e.alasan,
    'norec_spd':norec_spd.value

  }
  console.log('data',datas)
  
  useApi().post(`bank-darah/update-penerimaan-darah`,{'data':datas}).then((response:any)=>{
     if(response != null){
      modalPenguranganStok.value=false
      listData()
      loadRiwayat()
      loadRiwayatDash()
      norec_spd.value=''
      clear()
    } 
  })
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      sourceDarah.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
          sourceDarah.value.splice(i, 1)
        }
        element.no - 1
      })
      clear()
    },
    reject: () => { },
  })
}

const clear = () => {
  delete item.value.no
  delete item.value.nomerKantong
  delete item.value.jenisDarah
  delete item.value.golonganDarah
  delete item.value.satuan
  delete item.value.konversi
  delete item.value.produkDarah
  delete item.value.volumeDarah
  delete item.value.qtykantong
  delete item.value.tglkadaluarsa
}

const fetchProduk = async (e: any) => {
  let response = await useApi().get(`/bank-darah/get-produk?idkelompokproduk=${e.value}`)
  d_Produk.value = response.produk.map((e: any) => {
    return { label: e.namaproduk, value: e.id, default: e }
  })
  d_Suhu.value = response.suhu.map((e:any)=>{
    return{label: e.namasuhu, value: e.id, default:e}
  })
  loadData.value = false
}

const getSatuan = (e: any) => {
  let el = e.default
  if (el.konversisatuan != 0) {
    d_Satuan.value = el.konversisatuan.map((element: any) => {
      return { label: element.satuanstandar.toUpperCase(), value: element.satuanstandarfk, konversi: element.nilaikonversi }
    })
    d_Satuan.value.forEach((data: any) => {
      if (data.value == el.satuanstandarfk) {
        item.value.satuan = data
        item.value.konversi = data.konversi
        return
      }
    })
  } else {
    d_Satuan.value = [{ label: el.satuanstandar.toUpperCase(), value: el.satuanstandarfk }]
    item.value.satuan = { label: el.satuanstandar.toUpperCase(), value: el.satuanstandarfk }
    item.value.konversi = 1
  }

}

const getKonversi = (e: any) => {
  item.value.konversi = e.konversi
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const listData = async () => {
  let response = await useApi().get('bank-darah/get-combo')

  d_GolonganDarah.value = response.golongandarah.map((e: any) => {
    return { label: e.golongandarah, value: e.id }
  })
  // d_JenisDarah.value = response.jenisdarah.map((e: any) => {
  //   return { label: e.jenisdarah, value: e.id }
  // })
  d_JenisDarah.value = response.jenisdarah.map((e: any) => {
    return { label: e.detailjenisproduk, value: e.id }
  })
  d_Rekanan.value = response.rekanan.map((e: any) => {
    return { label: e.namarekanan, value: e.id ,default: e}
  })
  d_KelompokProduk.value = response.kelompokproduk.map((e: any) => {
    return { label: e.kelompokproduk, value: e.id }
  })
  d_SumberDana.value = response.sumberdana.map((e: any) => {
    return { label: e.asalproduk, value: e.id }
  })
  d_Gudang.value = response.ruangan.map((e: any) => {
    return { label: e.namaruangan, value: e.id }
  })

  d_KelompokProduk.value.forEach((element: any) => {
    if (element.label == 'Pelayanan Bank Darah') {
      item.value.kelompokProduk = { label: element.label, value: element.value }
    }
  });

  fetchProduk(item.value.kelompokProduk)

}

const showModal = (e:any) => {
  if(e.no){
    item.value.no = e.no
    item.value.nomerKantong =  e.nomerKantong,
    item.value.jenisDarah =  {label : e.jenisDarah , value : e.jenisDarahfk}
    item.value.golonganDarah = {label : e.golonganDarah, value: e.golonganDarahfk },
    item.value.satuan = {label : e.satuan , value : e.satuanfk}
    item.value.konversi = e.konversi
    item.value.volumeDarah = e.volumeDarah
    item.value.qtykantong = e.qtykantong
    item.value.tglkadaluarsa = e.tglkadaluarsa
    d_Produk.value.forEach(element => {
      if(element.value == e.produkDarahfk){
        item.value.produkDarah = element
      }
    });
  }
  modalInput.value = true
}
const showModalPengurangan = (e:any) => {
  if(e.no){
    item.value.no = e.no
    item.value.nomerKantong =  e.nomerKantong,
    item.value.jenisDarah =  {label : e.jenisDarah , value : e.jenisDarahfk}
    item.value.golonganDarah = {label : e.golonganDarah, value: e.golonganDarahfk },
    item.value.satuan = {label : e.satuan , value : e.satuanfk}
    item.value.konversi = e.konversi
    item.value.volumeDarah = e.volumeDarah
    // item.value.qtykantong = e.qtykantong
    item.value.tglkadaluarsa = e.tglkadaluarsa
    norec_spd.value =e.norec_spd
    d_Produk.value.forEach(element => {
      if(element.value == e.produkDarahfk){
        item.value.produkDarah = element
      }
    });
  }
  console.log('data pengurangan stok',e)
  modalPenguranganStok.value = true
}

const loadRiwayat = async ()=>{

  if(route.query.norec){
   let response = await useApi().get(`bank-darah/get-detail-penerimaan?norec=${route.query.norec}`)
   let header = response.detailterima
   sourceDarah.value = response.pelayananPasien
   console.log(sourceDarah.value)
   item.value.norec = header.norec
   item.value.tglPengambilDarah = header.tglstruk
   item.value.sumberDana = {label : header.asalproduk , value : header.asalprodukfk}
   item.value.ruangan = {label : header.namaruangan , value : header.namaruanganfk }
   item.value.sumberPmi = {label : header.namarekanan , value : header.objectrekananfk }
   item.value.kelompokProduk = {label : header.kelompokproduk , value : header.objectkelompokprodukfk }
   item.value.pegawaimenerimafk = {label : header.namalengkap , value : header.pgid }
  }
}

const loadRiwayatDash = async ()=>{

  let response = await useApi().get(`bank-darah/get-detail-penerimaan`)
  let header = response.detailterima
  sourceDarahRiwayat.value = response.pelayananPasien
  sourceDarahKalkulasi.value = response.datakalkulasi
}

const back = () => {
  window.history.back()
}

listData()
loadRiwayat()
loadRiwayatDash()

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

.border-style {
  border-style: solid;
  border-width: 1px;
  color: #0398e2;
  border-radius: 10px;
}

.p-dialog-content {
  overflow-y: unset;
}
</style>

