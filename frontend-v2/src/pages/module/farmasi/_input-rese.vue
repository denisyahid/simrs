
<template>
    <div class="columns">
        <div class="column is-12 form-layout is-stacked">
            <div class=" form-outer">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>{{ TITLE_PAGE }}</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined
                                    rounded>
                                    Cancel
                                </VButton>
                                <SplitButton v-if="item.noResep" label="Cetakan" icon="pi pi-info-circle"
                                    :model="listButton" rounded />
                                <VButton v-else icon="feather:save" type="submit" color="primary" raised @click="save()"
                                    rounded :loading="isSimpan">
                                    Save
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body">

                    <div class="columns is-multiline">
                        <div class="column is-3" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-6" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Pasien</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-3" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Pengkajian</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-12" v-if="!item.header.nocm">
                            <VCard>
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <div class="flex-table-item grid-item mb-4">
                                            <div class="flex-list-inner mb-4">
                                                <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                                                    <VFlexTableCell>
                                                        <VPlaceload width="10%" class="mx-1" height="20px" />
                                                        <VPlaceload width="10%" class="mx-1 is-pulled-right"
                                                            height="20px" />
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
                                    </div>
                                </div>
                            </VCard>
                        </div>
                    </div>
                    <div class="columns is-multiline" v-if="item.header.nocm">
                        <div class="column is-3">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Ruang Rawat</VLabel>
                                            <VLabel>{{ item.header.ruangrawat }} </VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Nomor Resep</VLabel>
                                            <VLabel>{{ item.resep ? item.resep : '-' }}</VLabel>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-6">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Pasien</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>No RM</VLabel>
                                            <VLabel>{{ item.header.nocm }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Nama Pasien</VLabel>
                                            <VLabel>{{ item.header.namapasien }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Jenis Kelamin</VLabel>
                                            <VLabel>{{ item.header.jeniskelamin }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Umur</VLabel>
                                            <VLabel>{{ item.header.umur }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Jenis Pasien</VLabel>
                                            <VLabel>{{ item.header.kelompokpasien }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Penjamin</VLabel>
                                            <VLabel>{{ item.header.namarekanan }}</VLabel>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-3">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Pengkajian</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Berat Badan</VLabel>
                                            <VLabel>{{ item.header.beratbadan }} </VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Tinggi Badan</VLabel>
                                            <VLabel>{{ item.header.tinggibadan }} </VLabel>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VCard>
                                        <h3 class="title is-5 mb-2">Data Resep</h3>
                                        <div class="columns is-multiline">
                                            <div class="column is-4">
                                                <VDatePicker v-model="item.tglAwal" color="green" trim-weeks
                                                    mode="dateTime">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VLabel class="item">Tgl Resep</VLabel>
                                                            <VControl icon="feather:calendar">
                                                                <VInput type="text" placeholder="Select a date"
                                                                    class="is-rounded" :value="inputValue"
                                                                    v-on="inputEvents" :disabled="disTanggal" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </div>
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel class="item">Penulis Resep</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select"
                                                        :loading="isLoadingSelect">
                                                        <Dropdown v-model="item.penulisResep" :options="d_penulisResep"
                                                            :optionLabel="'namalengkap'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel class="item">Ruangan</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select"
                                                        :loading="isLoadingSelect">
                                                        <Dropdown v-model="item.ruangan" :options="d_ruangan"
                                                            :optionLabel="'namaruangan'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3 mt-5-min">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="item.isreseppulang" class="p-0"
                                                            :label="'Resep Pulang'" color="primary" square />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <!-- <div class="column is-3 mt-5-min">
                                              <VField>
                                                  <VControl raw subcontrol>
                                                      <VCheckbox v-model="item.checkisKronis" class="p-0"
                                                          :label="'Obat Kronis'" color="primary" square />
                                                  </VControl>
                                              </VField>
                                          </div>
                                          <div class="column is-3 mt-5-min">
                                              <VField>
                                                  <VControl raw subcontrol>
                                                      <VCheckbox v-model="item.isresepcito" class="p-0" :label="'Cito'"
                                                          color="primary" square />
                                                  </VControl>
                                              </VField>
                                          </div> -->
                                        </div>
                                    </VCard>
                                </div>
                                <div class="column is-12">
                                    <VCard>
                                        <div class="column is-2">
                                            <VButton color="info" class="w-100 btn-slim" light rounded outlined
                                                v-tooltip-prime.right="'Riwayat Resep'" @click="isResep = true">Riwayat
                                                Resep </VButton>
                                        </div>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <div class="columns is-multiline">
                                                    <div class="column is-6">
                                                        <Toolbar class="mb-4">
                                                            <template #start>
                                                                <VButton icon="feather:plus" color="info" raised
                                                                    @click="addPopUp">
                                                                    Tambah
                                                                </VButton>
                                                            </template>
                                                        </Toolbar>
                                                    </div>
                                                    <div class="column is-6">
                                                        <Toolbar class="mb-4" style="height: 70px;">
                                                            <template #start>
                                                                <VFlexTableCell :column="{ align: 'end' }">
                                                                    <span class="bold-text">TOTAL BAYAR : </span>
                                                                    <span
                                                                        class="ml-1 bold-text light-text is-pulled-right">
                                                                        {{ H.formatRp(item.totalbayar, 'Rp. ') }}
                                                                    </span>
                                                                </VFlexTableCell>
                                                            </template>
                                                        </Toolbar>
                                                    </div>
                                                </div>

                                                <TListResep title="" straight class="list-widget-v3" :items="dataSource"
                                                    @editItems="editRow" @hapusItems="hapusRow" :iskronis="false"
                                                    @btnLoading="" squared colored>
                                                </TListResep>

                                            </div>
                                            <div class="column is-12" v-if="dataGridKronis.length">
                                                <VCard>
                                                    <h3 class="title is-5 mb-2">Obat Kronis</h3>
                                                    <TListResep title="" straight class="list-widget-v3"
                                                        :items="dataGridKronis" @editItems="editRow"
                                                        @hapusItems="hapusRow" :iskronis="true" squared colored>
                                                    </TListResep>
                                                </VCard>

                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <VModal :open="modalInput" title="Add Resep" size="big" actions="right" @close="modalInput = false" noclose>
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <!-- <div class="column is-3 mt-5-min">
                              <VField>
                                  <VControl raw subcontrol>
                                      <VCheckbox v-model="item.isreseppulang" class="p-0" :label="'Resep Pulang'"
                                          color="primary" square />
                                  </VControl>
                              </VField>
                          </div> -->
                        <div class="column is-2 mt-5-min">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="item.checkisDonasi" class="p-0" :label="'Obat Donasi'"
                                        color="info" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mt-5-min">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-if="isDisabledKronis" disabled v-model="item.checkisKronis" class="p-0"
                                        :label="'Obat Kronis'" color="primary" square />

                                    <VCheckbox v-else v-model="item.checkisKronis" class="p-0" :label="'Obat Kronis'"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-2 mt-5-min">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="item.checkisKronisAll" class="p-0" :label="'Obat Kronis All'"
                                        color="warning" square />
                                </VControl>
                            </VField>
                        </div>
                        <!-- <div class="column is-3 mt-5-min">
                              <VField>
                                  <VControl raw subcontrol>
                                      <VCheckbox v-model="item.isresepcito" class="p-0" :label="'Cito'" color="primary"
                                          square />
                                  </VControl>
                              </VField>
                          </div> -->
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">R/Ke</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.rke" placeholder="R/Ke" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VLabel class="item">Jenis Kemasan</VLabel>
                                <VControl>
                                    <VRadio v-for="items in d_kemasan" :key="items.id" v-model="item.jenisKemasan"
                                        :value="items" :label="items.jeniskemasan" name="{{items.id}}"
                                        color="primary" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="showRacikanDose">
                            <VField>
                                <VLabel class="item">Jumlah </VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.jumlahxmakan" placeholder="Jumlah"
                                        class="is-rounded" />

                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="showRacikanDose">
                            <VField>
                                <VLabel class="item">Dosis </VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.dosis" placeholder="Dosis" class="is-rounded" />
                                    <!-- <p class="help"> {{ (item.kekuatan ? 'Kekuatan : ' + item.kekuatan : '') + ' ' +
                                      (item.sediaan ?
                                          item.sediaan : '')
                                  }}</p> -->
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="showRacikanDose">
                            <VField>
                                <VLabel class="item">Kekuatan </VLabel>
                                <VControl icon="feather:bookmark">
                                    <!-- <VInput type="text" v-model="item.dosis" placeholder="Dosis" class="is-rounded" /> -->
                                    <VInput type="text" v-model="item.kekuatan" placeholder="Kekuatan"
                                        class="is-rounded">
                                    </VInput>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="showRacikanDose">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Jenis Racikan</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.jenisRacikan" :options="d_jenisRacikan"
                                        :optionLabel="'jenisracikan'" class="is-rounded" placeholder="Pilih data"
                                        style="width: 100%;" showClear :filter="true" />
                                </VControl>
                                <!-- <VControl icon="feather:search">
                                  <Multiselect mode="single" v-model="item.jenisRacikan" :options="d_jenisRacikan"
                                      placeholder="Pilih data" :searchable="true" />
                                          </VControl> -->
                            </VField>
                        </div>


                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Produk</VLabel>
                                <VControl icon="feather:search" class="prime-auto">
                                    <AutoComplete v-model="item.produk" :suggestions="d_produk"
                                        @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                        :minLength="3" class="is-rounded" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                                        placeholder="ketik untuk mencari..." @item-select="changeProduk(item.produk)">
                                        <template #option="slotProps">
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <span style="font-weight:bold">{{ slotProps.option.name }}</span>
                                                </div>
                                                <div class="column is-12 mt-5-min">
                                                    <table style="width:50%">
                                                        <tr>
                                                            <td><b>{{ slotProps.option.namaproduk }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kategory : {{ slotProps.option.satuanstandar }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Stok : {{ slotProps.option.stok }}</b></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </template>
                                    </AutoComplete>
                                </VControl>
                                <small v-if="isLastObatByDate" style="color:red">Produk {{ item.namaproduk }}, terakhir
                                    order tanggal : {{ item.lastorder }}</small>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Satuan</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'satuanstandar'"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear
                                        :filter="true" @change="changeSatuan(item.satuan)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">Konversi</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.nilaiKonversi" placeholder="Konversi" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">Stok</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.stok" placeholder="Stok" class="is-rounded"
                                        disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">Qty</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.jumlah" placeholder="Qty" class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Route</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.route" :options="d_route" :optionLabel="'name'"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear
                                        :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Tgl Kadaluarsa</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.tglKadaluarsa" :options="d_tglKadaluarsa"
                                        :optionLabel="'tglkadaluarsa'" class="is-rounded" placeholder="Pilih data"
                                        style="width: 100%;" showClear :filter="true"
                                        @change="changeExpired(item.tglKadaluarsa)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <div class="checkboxes">
                                <VField>
                                    <VLabel class="item">Aturan Pakai</VLabel>
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.aturanpakaitxt" placeholder="Aturan Pakai"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="columns is-multiline mt-4">
                                <div class="column is-2" v-for="(opsi) in listDataSigna">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="opsi.isChecked" class="p-0" :label="opsi.nama"
                                                @keydown.enter.prevent="addListAturanPakai(true, opsi)"
                                                @change="addListAturanPakai(opsi.isChecked, opsi)" color="primary"
                                                square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Satuan Resep</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.satuanresep" :options="d_satuanResep"
                                        :optionLabel="'satuanresep'" class="is-rounded" placeholder="Pilih data"
                                        style="width: 100%;" showClear :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VLabel class="item">Keterangan</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.KeteranganPakai" placeholder="Keterangan"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>


                        <div class="column is-3">
                            <VField>
                                <VLabel class="item">Harga</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.hargaSatuan" placeholder="Harga" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField>
                                <VLabel class="item">Harga Diskon</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.hargadiskon" placeholder="Harga Diskon"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">Diskon (%)</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.persenDiskon" placeholder="Diskon (%)"
                                        class="is-rounded" @input="changeDikson($event.target.value)" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-6" v-if="showRacikanDose">

                        </div>
                        <div class="column is-4 analytics-dashboard is-pulled-right">
                            <div class="dashboard-tile p-2">
                                <div class="tile-head">
                                    <h3 class="dark-inverted">Total</h3>
                                    <VIconBox color="danger" size="small" rounded>
                                        <i aria-hidden="true" class="fas fa-calculator"></i>
                                    </VIconBox>
                                </div>
                                <div class="tile-body">
                                    <span class="dark-inverted">{{ H.formatRp(item.total, 'Rp. ') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="content">
                            <div class="is-divider" data-content="Resep Dibuat" />
                        </div>
                    </div>

                    <TListResep title="" straight class="list-widget-v3" :items="dataSource" @editItems="editRow"
                        @hapusItems="hapusRow" @btnLoading="" squared colored>
                    </TListResep>

                    <div v-if="dataGridKronis.length">
                        <h3 class="title is-5 mb-2">Obat Kronis</h3>
                        <TListResep title="" straight class="list-widget-v3" :items="dataGridKronis"
                            @editItems="editRow" @hapusItems="hapusRow" :iskronis="true" squared colored>
                        </TListResep>
                    </div>

                    <div class="column is-12">
                        <div class="content">
                            <div class="is-divider" :data-content="infoStok" />
                        </div>
                    </div>

                    <div class="column is-12">
                        <DataTable :value="dataSourceStokProduk" :paginator="true" :rows="5"
                            :rowsPerPageOptions="[5, 10, 25]"
                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                            <Column field="no" header="No"></Column>
                            <Column field="namaruangan" header="ruangan"></Column>
                            <Column field="stok" header="Stok"></Column>
                        </DataTable>

                    </div>

                </form>
            </template>
            <template #action>
                <VButton icon="feather:plus" @click="add()" :loading="isLoading" color="primary" raised>Tambah</VButton>
            </template>
        </VModal>
    </div>


    <VModal :open="modalResepVerify" size="big" noclose title="Resep Verifikasi" actions="center"
        @close="modalResepVerify = false, clear()" cancelLabel="Tutup">
        <template #content>
            <div class="column">
                <DataTable :value="sourceDetailResep" dataKey="no" v-model:selection="selectedResep"
                    class="p-datatable-sm" :loading="sourceDetailResep.loading" :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]" scrollable
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 200px">
                    </Column>
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
            <VButton @click="cetakRekapLabel(selectedResep, true)" icon="feather:printer" :loading="isLoading"
                color="primary" raised>
                Label Injeksi</VButton>
            <VButton @click="cetakRekapLabel(selectedResep, false)" icon="feather:printer" :loading="isLoading"
                color="primary" raised>
                Rekap Label</VButton>
            <!-- <VButton @click="modalPilihApoteker = true" icon="feather:printer" :loading="isLoading" color="primary" raised>Cetak
                </VButton> -->
        </template>
    </VModal>

    <Dialog v-model:visible="isResep" header="Riwayat Resep" :style="{ width: '80vw' }">
        <RiwayatResep :pasien="props.pasien" :registrasi="props.registrasi" />
        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isResep = false">
                Tutup
            </VButton>
        </template>
    </Dialog>
</template>

<script setup lang="ts">
import Dialog from 'primevue/dialog'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed,
    defineComponent,
    watch,
    nextTick,
    onMounted,
    reactive,
    watchEffect
} from 'vue'
import RiwayatResep from './riwayat-resep.vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { formatRp } from '/@src/utils/appHelper'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import PrimeVue from 'primevue/config';
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import AutoComplete from 'primevue/autocomplete';
import SplitButton from 'primevue/splitbutton';
import { useCurrencyInput } from 'vue-currency-input'
import moment from 'moment'
import TListResep from './t-list-resep.vue'
import * as qzService from '/@src/utils/qzTrayService'
const TITLE_PAGE = 'Obat Alkes'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_PD = useRoute().query.norec_pd as string
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_APD = useRoute().query.norec_apd as string
let IS_NEWRESEP = useRoute().query.isnewresep ? useRoute().query.isnewresep as string : false
let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_RESEP: any = useRoute().query.norec_resep as string
let item: any = reactive({
    header: {},
    totalAll: 0,
    jumlah: 0,
    persenDiskon: 0,
    // aturanPakai: [],
    hargadiskon: 0,
    tglAwal: new Date(),
    rke: 1,
    resep: '-',
    // dataresep:null,
    chkp: 0,
    chks: 0,
    chksr: 0,
    chkm: 0,
})
const isResep: any = ref(false)
const isloadingLAMPAU: any = ref(false)
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const modalInput = ref(false)
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const dataSourceStokProduk: any = ref([])
const d_penulisResep: any = ref([])
const d_ruangan: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_aturanPakai: any = ref([])
const d_kemasan: any = ref([])
const d_jenisRacikan: any = ref([])
const d_route: any = ref([])
const d_tglKadaluarsa: any = ref([])
const d_satuanResep: any = ref([])
const d_asalProduk: any = ref([])
const dataSource: any = ref([])
const sourceDetailResep: any = ref([])
const listRiwayat = ref([])
const data2: any = ref([])
const dataOK: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const norecResep: any = ref('')
const isLoading: any = ref(false)
const isLoadingSelect: any = ref(false)
const modalResepVerify: any = ref(false)
const isLoadInput: any = ref(false)
const isSimpan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const disabledRuangan: any = ref(false)
const showGridKronis: any = ref(false)
const infoStok: any = ref('List Informasi Stok')
const dataGridKronis: any = ref([])
const dataSelected: any = ref({})
const isPemakaianObatAlkes: any = ref(false)
const disTanggal: any = ref(false)
const checkResepPulang: any = ref(false)
const isEdit: any = ref(false)
const buttonReady: any = ref(true)
const showRacikanDose: any = ref(false)
const isMerge: any = ref(false)
const selectedResep = ref();
const isLastObatByDate: any = ref(false)
const isDisabledKronis = ref(false)
const listDataSigna = ref([
    { "id": 1, "nama": "P", "isChecked": false },
    { "id": 2, "nama": "S", "isChecked": false },
    { "id": 3, "nama": "Sr", "isChecked": false },
    { "id": 4, "nama": "M", "isChecked": false }
])
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

const loadRiwayatOld = async () => {
    isloadingLAMPAU.value = true
    let paramsPD = ``
    // if(isCPPTOLD.value == true){
    //      paramsPD = ``
    // }
    // await sleep(1000)
    useApi().get(
        `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then(async (response: any) => {

            isloadingLAMPAU.value = false
            if (response.length) {
                let dataOLD = []
                for (let x = 0; x < response.length; x++) {
                    const element = response[x];
                    element.show = false
                    if (element.registrasi.norec_pd != props.registrasi.norec_pd) {
                        for (let y = 0; y < element.details.length; y++) {
                            const element2 = element.details[y];
                            element2.tgl = H.formatDate(new Date(element2.tgl), 'YYYY-MM-DD HH:mm')
                            element2.tglVerifikasi = H.formatDate(new Date(element2.tglVerifikasi), 'YYYY-MM-DD HH:mm')
                        }

                        dataOLD.push(element)
                    }
                }

                input2.value = dataOLD
            }
            // if (props.pasien.enabledEMRSimrsLama == 'true') {
            let lokal = false;
            if (window.location.host.indexOf('192.168') > -1) {
                lokal = true;
            }

            isloadingLAMPAU.value = true
            let responseX = await useApi().get(`/emr/history-sim-lama?prefix=catatan_dokter&nocm=${props.pasien.nocm}&local=${lokal}`)
            isloadingLAMPAU.value = false
            let no = input2.value.length + 1
            for (let x = 0; x < responseX.length; x++) {
                const element = responseX[x];
                let dokterDPJP = null
                if (element.DokterPemeriksa && element.DokterPemeriksa != '' && element.DokterPemeriksa != null) {
                    await fetchDokter({ query: element.DokterPemeriksa.split(' ')[0] })
                    if (d_Dokter.value.length) {
                        for (let y = 0; y < d_Dokter.value.length; y++) {
                            const element2 = d_Dokter.value[y];
                            if (element2.label.toLowerCase().indexOf(element.DokterPemeriksa.split(' ')[0].toLowerCase()) > -1) {
                                dokterDPJP = element2
                                break
                            }
                        }
                    }
                }

                let pushOld = {
                    'show': false,
                    'registrasi': {
                        'noregistrasi': element.Nopendaftaran,
                        'tglregistrasi': element.TglMasuk,
                        'namaruangan': '',
                        'dokter': element.DokterPemeriksa
                    },
                    'noemr': '',
                    'details': [{
                        'no': no++,
                        'tgl': element.TglMasuk,
                        'S': element.CatatanS,
                        'O': element.CatatanO,
                        'flag': 'dokter',
                        'diagnosaDokter': [{
                            'keterangan': element.DiagnosaDokter
                        }],
                        'diagnosaDokter9': [{
                            'no': 1
                        }],
                        'P': element.CatatanP,
                        'dokterDPJP': dokterDPJP
                    }]
                }
                input2.value.push(pushOld)
            }
            // }
            if (input2.value.length == 0) {
                H.alert('warning', 'Data Tidak Ada')
            }
        })
}

const listButton: any = ref([

    {
        label: 'Cetak Resep',
        icon: 'fas fa-print',
        command: () => {
            cetakResep(item.dataresep)
        }
    },
    // {
    //     label: 'Cetak Resep 23',
    //     icon: 'fas fa-print',
    //     command: () => {
    //         cetakResep23(item.dataresep)
    //     }
    // },
    {
        label: 'Cetak Label Obat',
        icon: 'fas fa-print',
        command: () => {
            cetakLabel(item.dataresep);
        }
    },
    {
        label: 'Rekap Label',
        icon: 'fas fa-print',
        command: () => {
            showModalDetail(item.dataresep);
        }
    },

])

const getProdukListStok = async (e:any)=>{

    let namaproduk
    let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''
    await useApi().get(`farmasi/get-stok-produk-by-ruangan?produkfk=${e}${isDonasi}`).then((response)=>{
        response.forEach((element:any,i:any) => {
            element.no = i + 1
            namaproduk = element.namaproduk
        });
        dataSourceStokProduk.value = response
        infoStok.value = `List Stok Produk ${namaproduk} Per Ruangan`
    })

}

const cetakRekapLabel = async (e: any, isInjeksi: any) => {
    if (!e) {
        H.alert('error', 'Obat tidak boleh kosong')
        return
    }

    let items: any = []
    e.forEach((element: any) => {
        items = [...new Set([...items, element.produkfk])]
    });

    let ketLabel = isInjeksi == true ? 'LABEL BIRU RESEP' : 'LABEL RESEP'
    qzService.printData(`report/farmasi/label-custom?pdf=true&norec_resep=${item.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`, ketLabel, 1)
    // H.printBlade(`report/farmasi/label-custom?pdf=true&norec_resep=${item.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`)
}


const fetchProduk = async (filter: any) => {
    let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''
    const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&ruanganfk=${item.ruangan.id}${isDonasi}&limit=10`)
    d_produk.value = response
}
const onInit = async () => {
    item.loading = false
    const response = await useApi().get(`/farmasi/input-resep-header?norec_apd=${NOREC_APD}`)
    item.loading = true
    item.header = response.data
    loadDrop()
}
const loadDrop = async () => {

    const response = await useApi().get(`/farmasi/input-resep-cbo?isnewresep=${IS_NEWRESEP}&departemenfk=${item.header.objectdepartemenfk}`)
    d_aturanPakai.value = response.signa.map((e: any) => { return { aturanpakai: e.signa, id: e.id } })
    d_kemasan.value = response.jeniskemasan
    // d_produk.value = response.produk//.map((e: any) => { return { label: e.namaproduk, value: e } })
    d_ruangan.value = response.ruangan//.map((e: any) => { return { label: e.namaruangan, value: e } })
    d_penulisResep.value = response.penulisresep//.map((e: any) => { return { label: e.namalengkap, value: e } })
    d_jenisRacikan.value = response.jenisracikan//.map((e: any) => { return { label: e.jenisracikan, value: e } })
    d_route.value = response.route//.map((e: any) => { return { label: e.name, value: e } })
    d_satuanResep.value = response.satuanresep//.map((e: any) => { return { label: e.satuanresep, value: e } })
    d_asalProduk.value = response.asalproduk//.map((e: any) => { return { label: e.asalproduk, value: e } })
    if (d_aturanPakai.value[0])
        item.aturanPakai = d_aturanPakai.value[0]
    if (d_kemasan.value[1])
        item.jenisKemasan = d_kemasan.value[1]
    item.tarifadminresep = response.tarifadminresep ? response.tarifadminresep : 0
    disabledRuangan.value = false;
    if (NOREC_RESEP != undefined) {
        NOREC_ORDER = 'EditResep'
        loadEdit()
    }
    if (NOREC_ORDER != undefined) {
        loadEdit()
    }

}

const loadEdit = async () => {
    delete item.ruangan
    if (NOREC_ORDER != '') {
        isEdit.value = true
        if (NOREC_ORDER == 'EditResep') {
            isSimpan.value = true;
            isLoading.value = true
            const response = await useApi().get(`/farmasi/input-resep-edit?norecResep=${NOREC_RESEP}`)

            isLoading.value = false
            isSimpan.value = false;
            isEdit.value = false
            disabledRuangan.value = true;
            let headerRESEP = response.detailresep
            let detailRESEP = response.pelayananPasien

            item.penulisResep = [];
            item.resep = headerRESEP.noresep

            d_ruangan.value.forEach((element: any) => {
                if (headerRESEP.id == element.id) {
                    item.ruangan = element
                }
            });


            item.penulisResep = { id: headerRESEP.pgid, namalengkap: headerRESEP.namalengkap }
            item.tglAwal = new Date(headerRESEP.tglresep);
            var resep = headerRESEP.noresep.split("/");
            var bulanNow = moment(new Date()).format('MM');
            if (resep[1].substr(2) != bulanNow) {
                useToaster().warn('Tanggal Resep Tidak Dapat Diubah (Hanya dapat diubah dibulan yang sama)')
                disTanggal.value = true;
            }
            if (detailRESEP.isreseppulang == '1') { checkResepPulang.value = true } else { checkResepPulang.value = false }

            data2.value = detailRESEP
            for (var i = data2.value.length - 1; i >= 0; i--) {
                const element = data2.value[i]
                if (element.iskronis == true)
                    element.obtkronis = "✔"
                else
                    element.obtkronis = ""
                element.noregistrasifk = NOREC_APD
                element.tglregistrasi = item.header.tglregistrasi
                element.kelasfk = data2.value[0].kelasfk

            }
            dataSource.value = data2.value

        } else {
            isLoadingSelect.value = true
            isSimpan.value = true;
            isLoading.value = true
            const response = await useApi().get(`/farmasi/input-resep-order?norec=${NOREC_ORDER}&nocmfk=${ID_PASIEN}`)
            isSimpan.value = false;
            isLoading.value = false
            isEdit.value = false
            disabledRuangan.value = true;
            if (response.strukorder == null) {
                H.alert('warning', 'Order sudah diverifikasi')
                return
            }

            let headerRESEP = response.strukorder
            let detailRESEP = response.orderpelayanan
            item.penulisResep = [];
            d_ruangan.value.forEach((element: any) => {
                if (headerRESEP.id == element.id) {
                    item.ruangan = element
                }
            });
            item.penulisResep = { id: headerRESEP.pgid, namalengkap: headerRESEP.namalengkap }
            isLoadingSelect.value = false
            if (headerRESEP.isreseppulang == '1') { checkResepPulang.value = true } else { checkResepPulang.value = false }
            data2.value = detailRESEP
            for (var i = data2.value.length - 1; i >= 0; i--) {
                const element = data2.value[i]
                element.noregistrasifk = NOREC_APD
                element.tglregistrasi = item.header.tglregistrasi
                element.kelasfk = item.header.klsid
                if (element.nilaikonversi == 0)
                    element.nilaikonversi = 1
                if (element.obatkronis == "1") {
                    var qtyOK: any = 0;
                    element.jumlahreal = parseFloat(element.jumlah);
                    qtyOK = (parseFloat(element.jumlah) * 7) / 30
                    element.jumlah = qtyOK;
                    element.jumlahobat = qtyOK;
                    element.jumlahcetak = parseFloat(element.jumlahreal) - qtyOK;
                    element.total = (parseFloat(qtyOK) * (parseFloat(element.hargasatuan) - parseFloat(element.hargadiscount)) * parseFloat(element.nilaikonversi)) + parseFloat(element.jasa);
                    dataOK.value.push(data2[i]);
                }
            }
            for (let x = 0; x < dataOK.value.length; x++) {
                const element = dataOK.value[x];
                element.no = x + 1;
            }
            for (let j = 0; j < data2.value.length; j++) {
                const element = data2.value[j];
                if (element.iskronis == true) {
                    element.obtkronis = "✔"
                } else {
                    element.obtkronis = ""
                }
            }
            setColor()
            dataSource.value = data2.value
            item.totalbayar = response.totalbayar
            countTotal()

        }
    }

    isSimpan.value = false
}

const setColor = () => {
    let z = 0
    for (let zx = 0; zx < data2.value.length; zx++) {
        const element = data2.value[zx];
        element.icon = 'fa-inverse lnir lnir-medicine-alt'
        element.color = listColor2.value[z]
        if (z > 4) {
            z = 0
        }
        z++
    }
    z = 0
    for (let zx = 0; zx < dataGridKronis.value.length; zx++) {
        const element = dataGridKronis.value[zx];
        element.icon = 'fa-inverse lnir lnir-medicine-alt'
        element.color = listColor2.value[z]
        if (z > 4) {
            z = 0
        }
        z++
    }
}

const addPopUp = () => {
    if (!item.ruangan) {
        useToaster().error('Ruangan harus di pilih')
        return
    }

    if (NOREC_APD) {
        dataSource.value.forEach((element: any, i: any) => {
            if (dataSource.value.length - 1 == i) {
                d_kemasan.value.forEach((e: any) => {
                    if (e.id == element.jeniskemasanfk) {
                        item.jenisKemasan = e
                        return
                    }
                });
                if (element.jeniskemasanfk == 1) {
                    item.rke = element.rke
                } else {
                    item.rke = parseInt(element.rke) + 1
                }
            }
        });
    }

    clearInput()
    modalInput.value = true
}
const add = () => {
    if (!item.produk) {
        useToaster().error('Produk harus di isi')
        return
    }
    if (!item.jumlah || item.jumlah == 0) {
        useToaster().error('Jumlah harus di isi')
        return
    }
    if (item.checkisDonasi != true && item.hargaSatuan == 0) {
        useToaster().error('Harga Satuan belum ada')
        return
    }

    if (item.stok == 0) {
        useToaster().error('Stok tidak ada')
        return
    }

    if (norecSPD.value == '') {
        useToaster().error('Stok tidak ada')
        return
    }
    if (!item.satuan) {
        useToaster().error('Satuan harus di isi')
        return
    }
    if (!item.satuan) {
        useToaster().error('Satuan harus di isi')
        return
    }
    if (!item.aturanpakaitxt) {
        useToaster().error('Aturan Pakai harus di isi')
        return
    }
    var dosis = 1;
    if (item.jenisKemasan.jeniskemasan == 'Racikan') {
        if (!item.dosis) {
            useToaster().error('Dosis harus di isi')
            return
        }
        dosis = item.dosis
        item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
    } else {
        item.jumlahxmakan = item.jumlah
    }
    let nomor = 0
    if (data2.value.length == 0) {
        nomor = 1
    } else {
        nomor = data2.value.length + 1
    }

    var qtyOK: any = 0;
    var qtyCetak = 0;
    var total = 0;
    var jumlahreal = 0;

    let jmlbulat = 0;
    let jml = 0;

    jmlbulat = item.jumlahbulat;
    jml = item.jumlah;
    const existingItem = dataOK.value.find(existing => existing.no === item.no);

    if (item.checkisKronis == true) {
        var datas: any = {};
        showGridKronis.value = true

        jumlahreal = parseFloat(item.jumlah);
        qtyOK = (parseFloat(item.jumlah) * 23) / 30
        // qtyOK = item.jumlah
        jml = parseFloat(item.jumlah) - qtyOK;
        // jmlbulat = jml;
        jmlbulat = qtyOK;
        qtyCetak = jumlahreal - jml;

        total = (parseFloat(qtyOK) * (parseFloat(item.hargaSatuan)
            - parseFloat(item.hargadiskon)) * parseFloat(item.nilaiKonversi)) + parseFloat(tarifJasa.value);

        if (existingItem) {
            existingItem.iskronis = item.checkisKronis;
            existingItem.no = item.no
            existingItem.noregistrasifk = NOREC_APD
            existingItem.generik = null
            existingItem.hargajual = String(item.hargaSatuan)
            existingItem.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : null
            existingItem.jenisobat = item.jenisRacikan ? item.jenisRacikan.jenisracikan : null
            existingItem.kelasfk = item.header.klsid
            existingItem.stock = String(item.stok)
            existingItem.harganetto = String(item.hargaNetto)
            existingItem.nostrukterimafk = norecTerima.value
            existingItem.norec_spd = norecSPD.value
            existingItem.ruanganfk = item.ruangan.id
            existingItem.rke = item.rke
            existingItem.jeniskemasanfk = item.jenisKemasan.id
            existingItem.jeniskemasan = item.jenisKemasan.jeniskemasan
            existingItem.aturanpakai = item.aturanpakaitxt //item.aturanPakai.aturanpakai
            existingItem.aturanpakaifk = 0// item.aturanPakai.id
            existingItem.ispagi = item.chkp
            existingItem.issiang = item.chks
            existingItem.issore = item.chksr
            existingItem.ismalam = item.chkm
            existingItem.lastorder = item.lastorder != undefined ? item.lastorder : ''
            // existingItem.iskronis = item.checkisKronis
            existingItem.routefk = item.route ? item.route.id : null
            existingItem.route = item.route ? item.route.name : null
            existingItem.asalprodukfk = item.asal.id
            existingItem.asalproduk = item.asal.asalproduk
            existingItem.produkfk = item.produk.id
            existingItem.namaproduk = item.produk.namaproduk
            existingItem.productname = item.produk.productname
            existingItem.nilaikonversi = item.nilaiKonversi
            existingItem.satuanstandarfk = item.satuan.ssid
            existingItem.satuanstandar = item.satuan.satuanstandar
            existingItem.satuanviewfk = item.satuan.ssid
            existingItem.satuanview = item.satuan.satuanstandar
            existingItem.jmlstok = String(item.stok)
            existingItem.jumlah = qtyOK
            existingItem.jumlahobat = qtyOK
            existingItem.dosis = dosis
            existingItem.hargasatuan = String(item.hargaSatuan)
            existingItem.hargadiscount = String(item.hargadiskon)
            existingItem.total = total
            existingItem.jmldosis = String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan)
            existingItem.jasa = tarifJasa.value
            existingItem.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null
        } else {
            datas = {
                no: nomor,
                noregistrasifk: NOREC_APD,
                generik: null,
                hargajual: String(item.hargaSatuan),
                jenisobatfk: item.jenisRacikan ? item.jenisRacikan.id : null,
                jenisobat: item.jenisRacikan ? item.jenisRacikan.jenisracikan : null,
                kelasfk: item.header.klsid,
                stock: String(item.stok),
                harganetto: String(item.hargaNetto),
                norec_spd: norecSPD.value,
                nostrukterimafk: norecTerima.value,
                ruanganfk: item.ruangan.id,
                rke: item.rke,
                jeniskemasanfk: item.jenisKemasan.id,
                jeniskemasan: item.jenisKemasan.jeniskemasan,
                aturanpakaifk: 0,
                aturanpakai: item.aturanpakaitxt,
                ispagi: item.chkp,
                issiang: item.chks,
                lastorder: item.lastorder != undefined ? item.lastorder : '',
                issore: item.chksr,
                ismalam: item.chkm,
                iskronis: item.checkisKronis,
                routefk: null,
                route: null,
                asalprodukfk: item.asal.id,
                asalproduk: item.asal.asalproduk,
                produkfk: item.produk.id,
                namaproduk: item.produk.namaproduk,
                productname: item.produk.productname,
                nilaikonversi: item.nilaiKonversi,
                satuanstandarfk: item.satuan.ssid,
                satuanstandar: item.satuan.satuanstandar,
                satuanviewfk: item.satuan.ssid,
                satuanview: item.satuan.satuanstandar,
                jmlstok: String(item.stok),
                jumlah: qtyOK,
                jumlahobat: qtyOK,
                jumlahcetak: qtyOK,
                dosis: dosis,
                hargasatuan: String(item.hargaSatuan),
                hargadiscount: String(item.hargadiskon),
                total: total,
                jmldosis: String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan),
                jasa: tarifJasa.value,
                keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
            };
            dataOK.value.push(datas);
        }
        qtyOK = (parseFloat(item.jumlah) * 7) / 30
        // qtyOK = item.jumlah
        jml = parseFloat(item.jumlah) - qtyOK;
        // jmlbulat = jml;
        jmlbulat = qtyOK;
        qtyCetak = jumlahreal - jml;

        total = (parseFloat(qtyOK) * (parseFloat(item.hargaSatuan)
            - parseFloat(item.hargadiskon)) * parseFloat(item.nilaiKonversi)) + parseFloat(tarifJasa.value);


        dataGridKronis.value = dataOK.value;
    }
    disabledRuangan.value = true;
    var data: any = {};
    if (item.no != undefined) {
        for (let x = 0; x < data2.value.length; x++) {
            const element = data2.value[x];
            if (element.no == item.no) {
                data.no = item.no
                data.noregistrasifk = NOREC_APD
                data.generik = null
                data.hargajual = item.hargaSatuan
                data.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : null
                data.jenisobat = item.jenisRacikan ? item.jenisRacikan.jenisracikan : null
                data.kelasfk = item.header.klsid
                data.stock = item.stok
                data.harganetto = item.hargaNetto
                data.nostrukterimafk = norecTerima.value
                data.norec_spd = norecSPD.value
                data.ruanganfk = item.ruangan.id
                data.rke = item.rke
                data.jeniskemasanfk = item.jenisKemasan.id
                data.jeniskemasan = item.jenisKemasan.jeniskemasan
                data.aturanpakai = item.aturanpakaitxt //item.aturanPakai.aturanpakai
                data.aturanpakaifk = 0// item.aturanPakai.id
                data.ispagi = item.chkp
                data.issiang = item.chks
                data.issore = item.chksr
                data.ismalam = item.chkm
                data.lastorder = item.lastorder != undefined ? item.lastorder : ''
                data.iskronis = item.checkisKronis
                data.isdonasi = item.checkisDonasi
                data.routefk = item.route ? item.route.id : null
                data.route = item.route ? item.route.name : null
                data.asalprodukfk = item.asal.id
                data.asalproduk = item.asal.asalproduk
                data.produkfk = item.produk.id
                data.namaproduk = item.produk.namaproduk
                data.productname = item.produk.productname
                data.nilaikonversi = item.nilaiKonversi
                data.satuanstandarfk = item.satuan.ssid
                data.satuanstandar = item.satuan.satuanstandar
                data.satuanviewfk = item.satuan.ssid
                data.satuanview = item.satuan.satuanstandar
                data.jmlstok = item.stok
                data.jumlah = jmlbulat
                data.jumlahobat = jml
                data.dosis = dosis
                data.hargasatuan = String(item.hargaSatuan)
                data.hargadiscount = String(item.hargadiskon)
                data.persendiscount = item.persenDiskon ? item.persenDiskon : 0
                data.total = item.total
                data.jmldosis = String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan)
                data.kekuatan = item.kekuatan,
                    data.jasa = tarifJasa.value
                data.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null
                data.satuanresepfk = item.satuanresep ? item.satuanresep.id : null
                data.satuanresep = item.satuanresep ? item.satuanresep.satuanresep : null
                data.tglkadaluarsa = item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null

                for (let i = 0; i < data2.value.length; i++) {
                    const element = data2.value[i];
                    if (element.iskronis == true) {
                        element.obtkronis = "✔"
                    } else {
                        element.obtkronis = ""
                    }
                }
                data2.value[x] = data;
            }
        }
    } else {
        if (data2.length > 0) {
            var racikan = data2.value[data2.value.length - 1].jeniskemasan
            if (racikan == 'Non Racikan') {
                item.rke = data2.value[data2.value.length - 1].rke + 1
            }
        }
        data = {
            no: nomor,
            noregistrasifk: NOREC_APD,
            generik: null,
            hargajual: String(item.hargaSatuan),
            jenisobatfk: item.jenisRacikan ? item.jenisRacikan.id : null,
            jenisobat: item.jenisRacikan ? item.jenisRacikan.jenisracikan : null,
            kelasfk: item.header.klsid,
            stock: String(item.stok),
            harganetto: String(item.hargaNetto),
            nostrukterimafk: norecTerima.value,
            norec_spd: norecSPD.value,
            ruanganfk: item.ruangan.id,
            rke: item.rke,
            jeniskemasanfk: item.jenisKemasan.id,
            jeniskemasan: item.jenisKemasan.jeniskemasan,
            aturanpakaifk: 0,//item.aturanPakai.id,
            aturanpakai: item.aturanpakaitxt,//item.aturanPakai.aturanpakai,
            ispagi: item.chkp,
            issiang: item.chks,
            issore: item.chksr,
            ismalam: item.chkm,
            iskronis: item.checkisKronis,
            isdonasi: item.checkisDonasi,
            // aturanpakai2: item.aturanPakai2 ,
            // sbsmid: item.sbsm.id,
            // sbsmname: item.sbsm.name,
            lastorder: item.datelast != undefined ? item.datelast : '',
            routefk: item.route ? item.route.id : null,
            route: item.route ? item.route.name : null,
            asalprodukfk: item.asal.id,
            asalproduk: item.asal.asalproduk,
            produkfk: item.produk.id,
            namaproduk: item.produk.namaproduk,
            productname: item.produk.productname,
            nilaikonversi: item.nilaiKonversi,
            satuanstandarfk: item.satuan.ssid,
            satuanstandar: item.satuan.satuanstandar,
            satuanviewfk: item.satuan.ssid,
            satuanview: item.satuan.satuanstandar,
            jmlstok: String(item.stok),
            jumlah: jmlbulat,//item.jumlahbulat,
            jumlahobat: jml,//item.jumlah,
            dosis: dosis,
            hargasatuan: String(item.hargaSatuan),
            hargadiscount: String(item.hargadiskon),
            persendiscount: item.persenDiskon ? item.persenDiskon : 0,
            total: item.total,
            jmldosis: String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan),
            kekuatan: item.kekuatan,
            jasa: tarifJasa.value,
            keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
            satuanresepfk: item.satuanresep ? item.satuanresep.id : null,
            satuanresep: item.satuanresep ? item.satuanresep.satuanresep : null,
            tglkadaluarsa: item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null,
        }
        data2.value.push(data)
        console.log(data2)
        for (let i = 0; i < data2.value.length; i++) {
            const element = data2.value[i];
            if (element.iskronis == true) {
                element.obtkronis = "✔"
            } else {
                element.obtkronis = ""
            }
        }

    }
    setColor()
    dataSource.value = data2.value
    if (item.jenisKemasan.jeniskemasan != 'Racikan') {
        item.rke = parseFloat(item.rke) + 1
    }

    countTotal()
    clearInput()
}
const countTotal = () => {
    let total = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        total = total + parseFloat(element.total)
    }
    TOTAL.value = formatRp(total, '')
}
const countTotalSub = () => {
    item.total = ((item.qty ? item.qty : 0) * (item.harga
        ? item.harga : 0)) - (item.diskon ? item.diskon : 0)
}

const showModalDetail = async (e: any) => {

    modalResepVerify.value = true
    sourceDetailResep.value = dataSource.value
}

const editRow = async (e: any) => {
    // if (isLoading.value == false)
    //     return
    e.btnLoading = true
    item.no = e.no
    item.rke = e.rke
    await fetchProduk({ query: e.productname })
    d_produk.value.forEach((element: any) => {
        if (element.id == e.produkfk) {
            item.produk = element
            return
        }
    })
    d_kemasan.value.forEach((element: any) => {
        if (element.id == e.jeniskemasanfk) {
            item.jenisKemasan = element
            return
        }
    });
    d_satuanResep.value.forEach((element: any) => {
        if (element.id == e.satuanresepfk) {
            item.satuanresep = element
            return
        }
    });
    d_aturanPakai.value.forEach((element: any) => {
        if (element.id == e.aturanpakaifk) {
            item.aturanPakai = element
            return
        }
    });
    d_jenisRacikan.value.forEach((element: any) => {
        if (element.id == e.jenisobatfk) {
            item.jenisRacikan = element
            return
        }
    });
    d_route.value.forEach((element: any) => {
        if (element.id == e.routefk) {
            item.route = element
            return
        }
    })
    item.aturanpakaitxt = e.aturanpakai
    item.chkp = 0
    item.chks = 0
    item.chksr = 0
    item.chkm = 0
    listDataSigna.value = []
    let sp = false
    if (e.ispagi != "0") {
        sp = true
        item.chkp = 1
    }
    let ss = false
    if (e.issiang != "0") {
        ss = true
        item.chks = 1
    }
    let sr = false
    if (e.issore != "0") {
        sr = true
        item.chksr = 1
    }
    let sm = false
    if (e.ismalam != "0") {
        sm = true
        item.chkm = 1
    }
    listDataSigna.value = [
        { "id": 1, "nama": "P", 'isChecked': sp },
        { "id": 2, "nama": "S", 'isChecked': ss },
        { "id": 3, "nama": "Sr", 'isChecked': sr },
        { "id": 4, "nama": "M", 'isChecked': sm }
    ]
    item.KeteranganPakai = e.keterangan
    item.persenDiskon = e.persendiscount

    if (e.iskronis == true) {
        item.checkisKronis = true
    } else {
        item.checkisKronis = false
    }

    if (e.isdonasi == true) {
        item.checkisDonasi = true
    } else {
        item.checkisDonasi = false
    }
    if (e.asalprodukfk) {
        d_asalProduk.value.forEach((element: any) => {
            if (element.id == e.asalprodukfk) {
                item.asal = element
                return
            }
        })
    }

    tarifJasa.value = e.jasa
    dataSelected.value = e
    GETKONVERSI()
    modalInput.value = true
    e.btnLoading = false
}

const hapusRow = (e: any) => {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].namaproduk == e.namaproduk) {
            data2.value.splice(i, 1);

        }
    }
    for (var i = dataOK.value.length - 1; i >= 0; i--) {
        if (dataOK.value[i].namaproduk == e.namaproduk) {
            dataOK.value.splice(i, 1);
        }
    }
    dataSource.value = data2.value
    dataGridKronis.value = dataOK.value
    countTotal()
    clearInput()
}

const save = async () => {
    isSimpan.value = true
    await H.statusClosingPasien(item.header.noregistrasi);
    isSimpan.value = false
    if (!item.penulisResep) {
        useToaster().error('Penulis Resep harus di pilih')
        return
    }
    if (!item.ruangan) {
        useToaster().error('Ruangan Harus di pilih ')
        return
    }
    if (data2.value.length == 0) {
        useToaster().error('Produk belum di pilih')
        return
    }
    for (var i = data2.value.length - 1; i >= 0; i--) {

        if (data2.value[i].isdonasi != true && data2.value[i].hargasatuan == 0) {
            useToaster().error("Terdapat obat dengan harga kosong, kemungkinan stock kosong!!")
            return
        }
    }
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (parseFloat(data2.value[i].jmlstok) < parseFloat(data2.value[i].jumlah)) {
            useToaster().error("Terdapat obat dengan jumlah melebihi STOK !! " + data2.value[i].namaproduk)
            return
        }
    }
    let objSave =
    {
        'strukresep': {
            'tglresep': moment(item.tglAwal).format('YYYY-MM-DD HH:mm:ss'),
            'tglregistrasi': item.header.tglregistrasi,
            'noregistrasi': item.header.noregistrasi,
            'pasienfk': NOREC_APD,
            'nocm': item.header.nocm,
            'namapasien': item.header.namapasien,
            'penulisresepfk': item.penulisResep.id,
            'ruanganfk': item.ruangan.id,
            'noorder': NOREC_ORDER ? NOREC_ORDER : '',
            'norecResep': NOREC_RESEP ? NOREC_RESEP : '',
            'noresep': item.resep,
            'retur': '-',
            'isobatalkes': isPemakaianObatAlkes.value,
            'isreseppulang': item.isreseppulang ? item.isreseppulang : null,
            'isresepcito': item.isresepcito ? item.isresepcito : null
        },
        'pelayananpasien': dataSource.value
    }

    // randomprice
    isSimpan.value = true
    await useApi().post(
        `/farmasi/input-resep-save`, objSave).then(async (response: any) => {

            //sendAntrol(NOREC_PD)
            isSimpan.value = false
            item.dataresep = response.noresep
            item.norec_resep = response.noresep.norec
            item.noResep = response.noresep.noresep;
            if (dataOK.value.length > 0) {
                var objSaveKronis = {
                    'strukresep': objSave.strukresep,
                    'norecresep': response.noresep.norec,
                    'noresep': response.noresep.noresep,
                    'noregistrasi': item.header.noregistrasi,
                    'pelayananpasienobatkronis': dataOK.value,
                }
                await useApi().post(`/farmasi/input-resep-kronis-save`, objSaveKronis)
            }
            if (NOREC_ORDER) {
                buttonReady.value = false
                // window.history.back();
            } else {
                cetakResep(item.dataresep)
                // cetakResep23(item.dataresep)
                clearInput()
                clearGrid()
            }

        }, (error) => {
            isSimpan.value = false
            // console.log(error)
        })


}
const changeProduk = (e: any) => {
    console.log(e)
    if (e != null && e != undefined) {
        GETKONVERSI()
        checkOrderLast(ID_PASIEN ? ID_PASIEN : item.header.nocmfk, e.id)
        getProdukListStok(e.id)
    }
}

const checkOrderLast = async (nocmfk: any, produkid: any) => {
    await useApi().get(`/farmasi/check-obat-periode?produkfk=${produkid}&nocmfk=${nocmfk}`).then((response) => {
        if (response != null) {
            isLastObatByDate.value = true
            item.namaproduk = response.namaproduk
            item.lastorder = response.tglpelayanan
        } else {
            item.lastorder = response;
        }
    })
    // console.log(item.lastorder)
    // debugger
}

const changeExpired = (e: any) => {
    if (e != null && e != undefined) {
        setNorecSPD()
    }
}
const GETKONVERSI = () => {

    d_satuan.value = []
    if (item.produk == undefined) return


    if (item.produk.konversisatuan.length == 0) {
        d_satuan.value = [{
            satuanstandar: item.produk.satuanstandar,
            ssid: item.produk.ssid,
            nilaikonversi: 1
        }]
    } else {
        d_satuan.value = item.produk.konversisatuan
    }
    d_satuan.value.forEach((element: any) => {
        if (element.ssid == item.produk.ssid) {
            item.satuan = element
            return
        }
    });

    item.nilaiKonversi = 1
    isLoading.value = true
    dataProdukDetail.value = []
    d_tglKadaluarsa.value = []

    let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''

    useApi().get(
        '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
        '&ruanganfk=' + item.ruangan.id + isDonasi +
        "&kpid=" + item.header.kpid +
        "&norec_apd=" + NOREC_APD).then(function (response: any) {
            if (response.detail.length > 0) {
                response.detail.forEach((element: any) => {

                    if (element.tglkadaluarsa != null)
                        d_tglKadaluarsa.value.push({ tglkadaluarsa: element.tglkadaluarsa })
                });

                // .map((e: any) => {
                //     return { label: e.tglkadaluarsa, value: e }
                // })
                dataProdukDetail.value = response.detail
                item.stok = response.jmlstok / item.nilaiKonversi
                if (response.kekuatan == undefined || response.kekuatan == 0) {
                    response.kekuatan = 1
                }
                item.kekuatan = response.kekuatan
                item.sediaan = response.sediaan
                item.tglKadaluarsa = d_tglKadaluarsa.value.length ? d_tglKadaluarsa.value[0] : undefined

                if (dataSelected.value.no != undefined) {

                    item.jumlah = dataSelected.value.jumlahobat
                    item.jumlahbulat = dataSelected.value.jumlahobat
                    item.dosis = dataSelected.value.dosis
                    item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
                    item.nilaiKonversi = dataSelected.value.nilaikonversi

                    d_satuan.value.forEach((element: any) => {
                        if (element.ssid == dataSelected.value.satuanviewfk) {
                            item.satuan = element
                            return
                        }
                    });
                    if (item.checkisDonasi === true) {

                    }
                    item.hargaSatuan = dataSelected.value.hargasatuan
                    item.hargadiskon = dataSelected.value.hargadiscount
                    item.hargaNetto = dataSelected.value.harganetto
                    item.total = dataSelected.value.total
                } else {
                    if (!isMerge.value) {
                        item.jumlah = 1
                    }
                }

                setNorecSPD()
                isLoading.value = false
            } else {

                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
                H.alert('error', 'Stok Produk Kosong')
                isLoading.value = false
            }

        });

}

const GETKONVERSIEDIT = () => {

    d_satuan.value = []
    if (item.produk == undefined) return


    if (item.produk.konversisatuan.length == 0) {
        d_satuan.value = [{
            satuanstandar: item.produk.satuanstandar,
            ssid: item.produk.ssid,
            nilaikonversi: 1
        }]
    } else {
        d_satuan.value = item.produk.konversisatuan
    }
    d_satuan.value.forEach((element: any) => {
        if (element.ssid == item.produk.ssid) {
            item.satuan = element
            return
        }
    });

    item.nilaiKonversi = 1
    isLoading.value = true
    dataProdukDetail.value = []
    d_tglKadaluarsa.value = []

    useApi().get(
        '/farmasi/get-produkdetailedit?produkfk=' + item.produk.id +
        '&ruanganfk=' + item.ruangan.id +
        "&kpid=" + item.header.kpid +
        "&norec_apd=" + NOREC_APD).then(function (response: any) {
            if (response.detail.length > 0) {
                response.detail.forEach((element: any) => {

                    if (element.tglkadaluarsa != null)
                        d_tglKadaluarsa.value.push({ tglkadaluarsa: element.tglkadaluarsa })
                });

                // .map((e: any) => {
                //     return { label: e.tglkadaluarsa, value: e }
                // })
                dataProdukDetail.value = response.detail
                item.stok = response.jmlstok / item.nilaiKonversi
                if (response.kekuatan == undefined || response.kekuatan == 0) {
                    response.kekuatan = 1
                }
                item.kekuatan = response.kekuatan
                item.sediaan = response.sediaan
                item.tglKadaluarsa = d_tglKadaluarsa.value.length ? d_tglKadaluarsa.value[0] : undefined
                if (dataSelected.value.no != undefined) {
                    let jmlx = 0
                    if (dataSelected.value.iskronis === true) {
                        jmlx = parseInt(dataSelected.value.jumlahobat) + parseInt(dataSelected.value.jumlah)
                    } else {
                        jmlx = dataSelected.value.jumlahobat
                    }
                    item.jumlah = jmlx
                    item.jumlahbulat = jmlx
                    item.dosis = dataSelected.value.dosis
                    item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
                    item.nilaiKonversi = dataSelected.value.nilaikonversi

                    d_satuan.value.forEach((element: any) => {
                        if (element.ssid == dataSelected.value.satuanviewfk) {
                            item.satuan = element
                            return
                        }
                    });

                    if (item.checkisDonasi === true) {
                        item.hargaSatuan = 0
                        item.hargaNetto = 0
                        item.hargadiskon = 0
                        item.total = 0

                    } else {
                        item.hargaSatuan = dataSelected.value.hargasatuan
                        item.hargadiskon = dataSelected.value.hargadiscount
                        item.hargaNetto = dataSelected.value.harganetto
                        item.total = dataSelected.value.total
                    }


                } else {
                    if (!isMerge.value) {
                        item.jumlah = 1
                    }
                }

                setNorecSPD()
                isLoading.value = false
            } else {
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
                H.alert('error', 'Stok Produk Kosong')
                isLoading.value = false
            }

        });

}
const clearInput = () => {
    delete item.produk
    delete item.asal
    delete item.satuan
    delete item.no

    delete item.KeteranganPakai
    delete dataSelected.value
    delete item.tglKadaluarsa

    item.qty = 1
    // item.totalAll = 0
    // TOTAL.value =0
    // modalInput.value = false
    item.nilaiKonversi = 0
    item.stok = 0
    item.jumlah = 0
    item.jumlahbulat = item.jumlah

    item.hargadiskon = 0
    item.total = 0
    item.hargaSatuan = 0
    item.hargaNetto = 0
    item.persenDiskon = 0
    if (item.checkisKronisAll != true) {
        item.checkisKronis = false
    }
    item.checkisDonasi = false

    if (item.jenisKemasan.jeniskemasan != 'Racikan') {
        delete item.satuanresep
        delete item.aturanpakaitxt
        delete item.dosis
        delete item.jenisRacikan
        item.jumlahxmakan = 1
        item.chkp = 0
        item.chks = 0
        item.chksr = 0
        item.chkm = 0

        listDataSigna.value.forEach((element: any) => {
            element.isChecked = false
        });
    }

    dataSelected.value = {}
}
const clearGrid = () => {
    data2.value = []
    dataSource.value = data2.value
    dataOK.value = []
    dataGridKronis.value = dataOK.value

}
const changeDikson = (e: any) => {
    if (e) {
        item.hargadiskon = 0;
        if (item.persenDiskon > 100) {
            item.persenDiskon = 100
        }
        if (item.persenDiskon < 0) {
            item.persenDiskon = 0
        }

        if (item.persenDiskon > 0) {
            var diskon = (parseFloat(item.hargaSatuan) * parseFloat(item.persenDiskon)) / 100
            if (!isNaN(diskon)) {
                item.hargadiskon = diskon;
                item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
            }
        }
    } else {
        item.hargadiskon = 0
        item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
    }
}

const setNorecSPD = () => {

    if (!item.jumlah) return

    if (item.jenisKemasan == undefined) {
        return
    }
    if (item.stok == 0) {
        item.jumlah = 0
        return;
    }
    var qty20 = 0
    tarifJasa.value = parseFloat(item.tarifadminresep)
    if (parseFloat(tarifJasa.value) != 0) {
        if (item.jenisKemasan.id == 2) {
            tarifJasa.value = 0;// parseFloat(item.tarifadminresep)
        }
        if (item.jenisKemasan.id == 1) {
            qty20 = Math.floor(parseFloat(item.jumlah) / 20)
            if (parseFloat(item.jumlah) % 20 == 0) {
                qty20 = qty20
            } else {
                qty20 = qty20 + 1
            }

            if (qty20 != 0) {
                tarifJasa.value = tarifJasa.value * qty20
            }
        }
    }
    if (item.no == undefined) {
        for (var i = data2.value.length - 1; i >= 0; i--) {
            if (data2.value[i].rke == item.rke) {
                tarifJasa.value = 0
            }
        }
    }
    item.jumlahbulat = item.jumlah

    var ada = false;
    for (var i = 0; i < dataProdukDetail.value.length; i++) {
        ada = false
        const element = dataProdukDetail.value[i]
        var tglExpiredPilih = element.tglkadaluarsa
        if (item.tglKadaluarsa != undefined) {
            tglExpiredPilih = moment(item.tglKadaluarsa.tglkadaluarsa).format('YYYY-MM-DD HH:mm:ss')
        }

        console.log(element)
        if (parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi)
            <= parseFloat(element.qtyproduk)
            && tglExpiredPilih == element.tglkadaluarsa
        ) {
            if (item.checkisDonasi === true) {
                hrg1.value = 0
                hrgsdk.value = 0
                item.hargaSatuan = 0
                item.hargaNetto = 0
                item.hargadiskon = 0
                item.total = 0

            } else {
                hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
                hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
                item.hargaSatuan = hrg1.value
                item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
                if (item.hargadiskon == 0) {
                    item.hargadiskon = hrgsdk.value
                } else {
                    hrgsdk.value = item.hargadiskon
                }
                item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)


            }

            norecTerima.value = element.norec
            norecSPD.value = element.norec_spd
            item.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
            ada = true;
            break;
        }
    }
    if (ada == false) {

        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.hargaNetto = 0
        item.total = 0

        let isDonasi = item.checkisDonasi ? `?isdonasi=${item.checkisDonasi}` : ''

        norecSPD.value = ''
        norecTerima.value = ''
        isMerge.value = false
        if (dataProdukDetail.value.length > 1) {

            var objSave =
            {
                produkfk: item.produk.id,
                ruanganfk: item.ruangan.id
            }

            isSimpan.value = true;
            useApi().postNoMessage(
                `/farmasi/save-stock-merger${isDonasi}`, objSave).then(function (response: any) {
                    isMerge.value = true
                    GETKONVERSI()
                    isSimpan.value = false
                })


        }
    }
    if (item.jumlah == 0) {
        item.hargaSatuan = 0
        item.hargaNetto = 0
    }
}

const jumlahkan = () => {
    if (item.stok > 0) {
        item.jumlah = (parseFloat(item.jumlahxmakan) * parseFloat(item.dosis)) / parseFloat(item.kekuatan)
        item.jumlahbulat = item.jumlah
    }
}
const changeSatuan = (e: any) => {
    item.nilaiKonversi = item.satuan.nilaikonversi
}
const back = () => {
    window.history.back()
}
const addListAturanPakai = (bool: boolean, data: any) => {
    let jml = 0
    if (bool == true) {
        if (data.id == 1) {
            item.chkp = 1
        }
        if (data.id == 2) {
            item.chks = 1
        }
        if (data.id == 3) {
            item.chksr = 1
        }
        if (data.id == 4) {
            item.chkm = 1
        }
    } else {
        if (data.id == 1) {
            item.chkp = 0
        }
        if (data.id == 2) {
            item.chks = 0
        }
        if (data.id == 3) {
            item.chksr = 0
        }
        if (data.id == 4) {
            item.chkm = 0
        }
    }
    if (item.chkp == 1) {
        jml = jml + 1
    }
    if (item.chks == 1) {
        jml = jml + 1
    }
    if (item.chksr == 1) {
        jml = jml + 1
    }
    if (item.chkm == 1) {
        jml = jml + 1
    }
    item.aturanpakaitxt = jml + 'x1'
    if (jml == 0) {
        item.aturanpakaitxt = ''
    }
}
const cetakResep = async (item: any) => {
    qzService.printData(`report/farmasi/resep?pdf=true&norec=${item.norec}`, 'RESEP', 1);
    // H.printBlade(`report/farmasi/resep?pdf=true&norec=${item.norec}`)
}

// const cetakResep23 = (item: any) => {
//     // H.printBlade(`report/farmasi/resep-obat-23?pdf=true&norec=${item.norec}`)
//     qzService.printData(`report/farmasi/resep-obat-23?pdf=true&norec=${item.norec}`, 'RESEP', 1);

// }

const cetakLabel = async (e: any) => {
    let norec_resp = e.norec ? `&norec=${e.norec}` : ''
    let norec_pd = NOREC_PD ? `&norecpd=${NOREC_PD}` : ''

    // H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true${norec_pd}${norec_resp}`);
    qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true${norec_pd}${norec_resp}`, 'LABEL RESEP', 1);

    // if (e.jenis == "R") {
    //     // H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norecpd=${NOREC_PD}`)
    //     qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norecpd=${NOREC_PD}`, 'LABEL RESEP', 1);
    // } else {
    //     // H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norec=${e.norec}`)
    //     qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norec=${e.norec}`, 'LABEL RESEP', 1);
    // }
}

watch(
    () => item.checkisKronisAll,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (newValue === true) {
                isDisabledKronis.value = true
                item.checkisKronis = true

            } else {
                isDisabledKronis.value = false
                // item.checkisKronis = false

            }
        }
    }
)

watch(
    () => item.jenisKemasan,
    (newValue, oldValue) => {
        if (newValue.jeniskemasan == 'Racikan') {
            showRacikanDose.value = true
        } else {
            showRacikanDose.value = false
        }
    }
)

watch(
    () => item.jumlah,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            setNorecSPD()
        }
    }
)
watch(
    () => item.jumlahxmakan,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            jumlahkan()
        }
    }
)
watch(
    () => item.dosis,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            jumlahkan()
        }
    }
)
watch(
    () => item.hargadiskon,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            hrgsdk.value = item.hargadiskon
            item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
            if (isNaN(item.total)) {
                item.total = 0
            }
        }
    }
)
watch(
    () => item.nilaiKonversi,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (item.stok > 0) {
                item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
                item.jumlah = 0
                item.jumlahbulat = 0;
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
            }
        }
    }
)
watch(() => item.rke, (newValue, oldValue) => {
    if (newValue != oldValue) {
        if (tarifJasa == 0) {
            for (var i = data2.value.length - 1; i >= 0; i--) {
                tarifJasa.value = parseFloat(item.tarifadminresep)
                if (data2.value[i].rke == item.rke) {
                    tarifJasa.value = 0
                    break;
                }
            }
        }
    }
})

watch(() => [
    item.jenisKemasan,
], (newValue, oldValue) => {
    if (item.no) {
        item.rke = item.rke
    } else {
        dataSource.value.forEach((element: any, i: any) => {
            if (dataSource.value.length - 1 == i) {
                if (item.jenisKemasan.id == 1 && element.jeniskemasanfk == 1) {
                    item.rke = element.rke
                    return
                } if (item.jenisKemasan.id == 2 && element.jeniskemasanfk == 1) {
                    item.rke = parseFloat(element.rke) + 1
                    return
                }
            }
        });
    }
})

const sendAntrol = async (norec_pd: any) => {
    const jsont6 = {
        "noregistrasifk": norec_pd,
        "taskid": 6,
        "waktu": new Date().getTime(),
    }
    await useApi()
        .postNoMessage(`/bridging/antrol/sendTaskId`, jsont6)
        .then((response: any) => {
        })
}
const clear = () => {

}
onInit()
// loadRiwayat()
onMounted(() => {

})
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
// @import '/@src/scss/custom/config';

.form-layout .form-outer {
    border: 1px solid transparent;
    background-color: transparent;
}

.bold-text {
    font-weight: bold;
}

// .form-layout {
//     .form-outer {
//         padding: 20px 40px 40px;
//     }
// }
</style>

