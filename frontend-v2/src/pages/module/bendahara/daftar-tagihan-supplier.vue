
<template>
    <ConfirmDialog />
    <div class="column">
        <VCard>
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <h3 class="title is-5 mb-2 mr-1">Daftar Tagihan Rekanan</h3>
                            </div>
                            <div class="column is-3">
                                <span>Periode</span>
                                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks class="pt-2">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" class="input-calendar"
                                                    v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" class="input-calendar"
                                                    v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-3">
                                <span>Nama Supplier</span>
                                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan"
                                            @complete="fetchRekanan($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Supplier" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VLabel> No. Faktur / No. Dokumen </VLabel>
                                    <VControl>
                                        <VInput type="text" placeholder=" No Histori ..." autocomplete="off"
                                            v-model="item.nofaktur" v-on:keyup.enter="fetchData()"
                                            style="margin-top:5px;" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <span>Status</span>
                                <VField class="is-autocomplete-select pt-2">
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.status" :options="d_Setor"
                                            placeholder="Pilih data" :searchable="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column" style="margin-top: 25px; margin-left: auto:  !important;">
                                <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                                    @click="cari()" :loading="isLoadingBtn">
                                </VIconButton>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <Divider />
            <div class="columns is-multiline">
                <div class="column is-3" style="margin-top:10px; text-align: right;">
                    <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                        <div :class="'label-status'">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Total Tagihan</span>
                        </div>
                        <small class="text-bold-custom">{{ H.formatRp(item.totalTagihan, 'Rp. ')
                        }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3" style="margin-top:10px; text-align: right;">
                    <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                        <div :class="'label-status'">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Total Sudah Dibayar</span>
                        </div>
                        <small class="text-bold-custom">{{ H.formatRp(item.totalbayar, 'Rp. ')
                        }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3" style="margin-top:10px; text-align: right;">
                    <VCardCustom :style="'padding:5px 15px;margin:0;background:#fafafa'">
                        <div :class="'label-status'">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Sisa Tagihan</span>
                        </div>
                        <small class="text-bold-custom">{{ H.formatRp(item.sisaHutang, 'Rp. ')
                        }}</small>
                    </VCardCustom>
                </div>
                <!-- <div class="column is-8">
                    <div class="column is-12">
                        <VButton color="success" icon="fas fa-print" raised rounded style="margin-left:20px;"
                            @click="cetakLapHarian()"> Laporan Penerimaan Harian
                        </VButton>

                        <VButton color="warning" icon="fas fa-print" style="margin-left:20px;" raised rounded>
                            Laporan Penerimaan Per Transaksi
                        </VButton>
                    </div>
                </div> -->
            </div>

            <DataTable :value="dataSource" tableStyle="min-width: 50rem" scrollable :paginator="true" :rows="10"
                :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
                <template #header>
                    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                        <span class="text-xl text-900 font-bold">Rincian Tagihan</span>
                        <VButton color="info" icon="fas fa-paste" raised rounded style="margin-left:20px;"
                            @click="collectTagihan()"> Collecting Tagihan
                        </VButton>
                    </div>
                </template>
                <Column :exportable="false" header="#" style="min-width: 100px" frozen>
                    <template #body="slotProps">
                        <VIconButton type="button" icon="fas fa-sticky-note" class="mr-2" color="info" circle outlined
                            raised v-tooltip.top="'Detail'" @click="detail(slotProps.data)">
                        </VIconButton>
                        <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2" color="success" circle outlined
                            raised v-tooltip.left="'Bayar '" @click="bayarTagihan(slotProps.data)"
                            v-if="slotProps.data.status == 'BELUM LUNAS'">
                        </VIconButton>
                        <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2" color="success" circle outlined
                            raised v-tooltip.left="'Bayar '" @click="sudahBayar(slotProps.data)"
                            v-if="slotProps.data.status == 'LUNAS'">
                        </VIconButton>
                    </template>

                </Column>
                <Column field="nostruk" header="No Terima" style="min-width: 150px" frozen></Column>
                <Column field="status" header="Status" style="min-width: 100px;">
                    <template #body="slotProps">
                        <Tag :value="slotProps.data.status" :severity="getLabel(slotProps.data.status)" />
                    </template>
                </Column>
                <Column field="tglstruk" header="Tgl Struk" style="min-width: 150px"></Column>
                <Column field="namarekanan" header="Rekanan" style="min-width: 150px"></Column>
                <Column field="nodokumen" header="No. Dokumen" style="min-width: 150px"></Column>
                <Column field="tgljatuhtempo" header="Tgl Jatuh Tempo" style="min-width: 150px"></Column>

                <Column field="total" header="Total" style="min-width: 150px; text-align: right;">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.total, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="totalppn" header="Total PPN" style="min-width: 150px; text-align: right;">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totalppn, 'Rp. ') }}
                    </template>
                </Column>

                <Column field="totaldiskon" header="Total Diskon" style="min-width: 150px; text-align: right;">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totaldiskon, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="subtotal" header="Total Tagihan" style="min-width: 150px; text-align: right;">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.subtotal, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="sisautang" header="Sisa Hutang" style="min-width: 150px; text-align: right;">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.sisautang, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="totalbayar" header="Total Sudah Dibayar" style="min-width: 150px; text-align: right;">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totalbayar, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="nocollecting" header="No. Collecting" style="min-width: 150px"></Column>


                <template #footer> Total Transaksi = {{ dataSource ? dataSource.length : 0 }} </template>
            </DataTable>

        </VCard>
    </div>
    <Dialog v-model:visible="modalInput" modal header="Detail Tagihan" :style="{ width: '80vw' }">
        <div class="columns is-multiline">
            <div class="column is-3">
                <VField>
                    <VLabel>No. Terima</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.noSTRUK" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-3">
                <VField>
                    <VLabel>Tanggal Terima</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.tglStruke" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Nama Rekanan</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.nameRekanan" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>No. Faktur</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.noFaktur" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Tanggal Faktur</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.tglJatuh" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Tanggal Jatuh Tempo</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.tglJatuh" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <TabView class="tabview-custom " :scrollable="true">
                    <TabPanel>
                        <template #header>
                            <i class="fas fa-external-link-alt mr-2" aria-hidden="true"></i>
                            <span>Detail Tagihan</span>
                            <Badge :value="dataDetail.length" v-if="dataDetail.length > 0" severity="danger" class="ml-2" />
                        </template>


                        <DataTable :value="dataDetail" tableStyle="min-width: 50rem" :paginator="true" :rows="10"
                            :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <span class="text-xl text-900 font-bold">Rincian Tagihan</span>
                                    <VButton color="danger" icon="feather:printer" raised rounded style="margin-left:20px;"
                                        @click="cetakKwitansi()"> Cetak
                                    </VButton>
                                </div>
                            </template>
                            <Column field="no" header="No" frozen></Column>
                            <Column field="kdproduk" header="Kode Barang" style="min-width: 100px" frozen></Column>
                            <Column field="kdsirs" header="Kode SIRS" style="min-width: 100px;"></Column>
                            <Column field="namaproduk" header="Nama Barang" style="min-width: 200px"></Column>
                            <Column field="asalproduk" header="Asal Produk" style="min-width: 150px"></Column>
                            <Column field="qtyproduk" header="QTY Terima" style="min-width: 100px"></Column>
                            <Column field="satuanstandar" header="Satuan" style="min-width: 100px"></Column>
                            <Column field="hargasatuan" header="Harga Satuan" style="min-width: 100px">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.hargasatuan, 'Rp. ') }}
                                </template>
                            </Column>
                            <Column field="hargadiscount" header="Diskon" style="min-width: 100px">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.hargadiscount, 'Rp. ') }}
                                </template>
                            </Column>
                            <Column field="hargappn" header="PPN" style="min-width: 100px">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.hargappn, 'Rp. ') }}
                                </template>
                            </Column>
                            <Column field="subtotal" header="Total" style="min-width: 150px">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.subtotal, 'Rp. ') }}
                                </template>
                            </Column>

                        </DataTable>


                    </TabPanel>
                    <TabPanel>
                        <template #header>
                            <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
                            <span>Riwayat Pembayaran</span>


                        </template>

                        <DataTable :value="dataBayar" tableStyle="min-width: 50rem" scrollable :paginator="true" :rows="10"
                            :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
                            <template #header>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                    <span class="text-xl text-900 font-bold">Riwayat Pembayaran</span>
                                    <!-- <VButton color="danger" icon="feather:printer" raised rounded style="margin-left:20px;"
                                        @click="cetakKwitansiBayar()"> Cetak
                                    </VButton> -->

                                </div>
                            </template>
                            <Column field="no" header="No"></Column>
                            <Column field="tglsbk" header="Tgl SBK" style="min-width: 150px" frozen></Column>
                            <Column field="nosbk" header="No. SBK" style="min-width: 150px" frozen></Column>
                            <Column field="totalharusdibayar" header="Total Harus Dibayar" style="min-width: 150px; text-align: right;">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.totalharusdibayar, 'Rp. ') }}
                                </template>
                            </Column>
                            <Column field="totaldibayar" header="Total Setor" style="min-width: 150px; text-align: right;">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.totaldibayar, 'Rp. ') }}
                                </template>
                            </Column>
                         
                            <Column field="totalsisahutang" header="Total Sisa Hutang" style="min-width: 150px; text-align: right;">
                                <template #body="slotProps">
                                    {{ H.formatRp(slotProps.data.totalsisahutang, 'Rp. ') }}
                                </template>
                            </Column>
                         

                        </DataTable>


                    </TabPanel>
                </TabView>

            </div>

        </div>
        <template #footer>
            <!-- <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBtn"
                @click="simpan()"> Simpan
            </VButton> -->
        </template>
    </Dialog>

    <Dialog v-model:visible="modalBayar" modal header="Pembayaran Tagihan" :style="{ width: '60vw' }">
        <div class="columns is-multiline">
            <div class="column is-4">
                <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tglbayar" color="green" trim-weeks mode="dateTime"
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                            <VLabel class="required-field">Tanggal Pembayaran</VLabel>
                            <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                    </template>
                </VDatePicker>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>No. Dokumen</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.nodokumen" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Tgl Dokumen</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.tgldokumen" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
           
            <div class="column is-6">
                <VField>
                    <VLabel>Deskripsi Transaksi</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.deskripsiTransaksi" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Uraian Transaksi</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.uraianTransaksi" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Total Tagihan</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.tagihan" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabel>Total Sudah Dibayar</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.totalbayarawal" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div>
            
            <!-- <div class="column is-6">
                <VField>
                    <VLabel>Tagihan Terbilang</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.terbilangTagihan" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div> -->
            <div class="column is-4">
                <VField>
                    <VField label="Biaya Admin">
                        <VControl class="prime-auto">
                            <VInput type="text" v-model="item.nominal" v-on:input="changeNomi(item.nominal)" v-mask-currency
                                placeholder="Nominal" class="is-rounded" />
                        </VControl>
                    </VField>
                </VField>
            </div>
            <!-- <div class="column is-4">
                <VField>
                    <VLabel>Terbilang</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.terbilang" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div> -->
            
            <div class="column is-4" v-if="item.totalbayar != null">
                <VField>
                    <VLabel class="required-field">Total Bayar</VLabel>
                    <VControl class="prime-auto">
                        <VInput type="text" v-model="item.nominalBayar" v-mask-currency v-on:input="changeBayar(item.nominalBayar)"
                            placeholder="Nominal" class="is-rounded" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4" v-else>
                <VField>
                    <VLabel class="required-field">Total Bayar</VLabel>
                    <VControl class="prime-auto">
                        <VInput type="text" v-model="item.nominalBayar" v-mask-currency v-on:input="changeBayar(item.nominalBayar)"
                            placeholder="Nominal" class="is-rounded" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField class="is-autocomplete-select pt-2">
                    <VLabel class="required-field">Cara Bayar</VLabel>
                    <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.caraBayar" :options="d_caraBayar" placeholder="Pilih data"
                            :searchable="true" />
                    </VControl>
                </VField>
            </div>

            <!-- <div class="column is-6">
                <VField>
                    <VLabel>Terbilang</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.terbilangBayar" type="text" class="input is-rounded" disabled />
                    </VControl>
                </VField>
            </div> -->
            <Divider />

            <div class="column is-4">
                <VField>
                    <VLabel>Nama Bank</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.namabank" type="text" class="input is-rounded" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Nomor Rekening</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.norek" type="text" class="input is-rounded" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Nama Pemilik Rekening</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.namapemilik" type="text" class="input is-rounded" />
                    </VControl>
                </VField>
            </div>
        </div>
        <template #footer>
            <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBtn"
                @click="simpanBayar()"> Bayar
            </VButton>
        </template>
    </Dialog>

    <Dialog v-model:visible="modalConfirm" modal header="Pemberitahuan" :style="{ width: '20vw' }">
        <p> Tagihan Sudah Dibayar Lunas, Silakan cek pada detail tagihan</p>
    </Dialog>

    <VModal :open="modalCollect" size="big" noclose title="Collecting Tagihan" actions="center"
        @close="modalCollect = false, clear()" cancelLabel="Tutup">
        <template #content>
            <div class="column">
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tglCollect" color="green" trim-weeks
                            mode="dateTime" :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                <VField>
                                    <VLabel class="required-field">Tanggal</VLabel>
                                    <VControl icon="feather:calendar">
                                        <VInput type="text" placeholder="Select a date" :value="inputValue"
                                            v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>

                    <div class="column is-3">
                        <span>Nama Supplier</span>
                        <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan"
                                    @complete="fetchRekanan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Supplier" />
                            </VControl>
                        </VField>
                    </div>
                </div>


                <DataTable :value="dataSource" dataKey="no" class="p-datatable-sm" :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]" scrollable
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                    <!-- <Column selectionMode="multiple" headerStyle="width: 3rem"></Column> -->
                    <Column :exportable="false" header="#" style="text-align: center;">
                        <template #body="slotProps">
                            <VControl raw subcontrol>
                                <VCheckbox v-model="modelCheck[slotProps.data.norec]" :value="slotProps.data" color="info"
                                    square @change="checkedItems()" />
                            </VControl>
                        </template>
                    </Column>
                    <Column field="nostruk" header="No Terima" style="min-width: 150px" frozen></Column>
                    <Column field="status" header="Status" style="min-width: 100px;">
                        <template #body="slotProps">
                            <Tag :value="slotProps.data.status" :severity="getLabel(slotProps.data.status)" />
                        </template>
                    </Column>
                    <Column field="tglstruk" header="Tgl Struk" style="min-width: 150px"></Column>
                    <Column field="namarekanan" header="Rekanan" style="min-width: 150px"></Column>
                    <Column field="nodokumen" header="No. Dokumen" style="min-width: 150px"></Column>
                    <Column field="tgljatuhtempo" header="Tgl Jatuh Tempo" style="min-width: 150px"></Column>

                    <Column field="total" header="Total" style="min-width: 150px; text-align: right;">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.total, 'Rp. ') }}
                        </template>
                    </Column>
                    <Column field="totalppn" header="Total PPN" style="min-width: 150px; text-align: right;">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.totalppn, 'Rp. ') }}
                        </template>
                    </Column>

                    <Column field="totaldiskon" header="Total Diskon" style="min-width: 150px; text-align: right;">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.totaldiskon, 'Rp. ') }}
                        </template>
                    </Column>
                    <Column field="subtotal" header="Total Tagihan" style="min-width: 150px; text-align: right;">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.subtotal, 'Rp. ') }}
                        </template>
                    </Column>
                    <Column field="sisautang" header="Sisa Hutang" style="min-width: 150px; text-align: right;">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.sisautang, 'Rp. ') }}
                        </template>
                    </Column>
                    <Column field="totalbayar" header="Total Sudah Dibayar" style="min-width: 150px; text-align: right;">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.totalbayar, 'Rp. ') }}
                        </template>
                    </Column>
                </DataTable>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status success">
                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                    <span class="ml-1">Total Harga</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(totalPrice ?? 0, 'Rp') }}</small>
                            </VCardCustom>
                        </div>
                        <div class="column is-3">
                            <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status info">
                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                    <span class="ml-1">Total PPN</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(totalPPN ?? 0, 'Rp') }}</small>
                            </VCardCustom>
                        </div>
                        <div class="column is-3">
                            <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status info">
                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                    <span class="ml-1">Total Diskon</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(DiskonTotal ?? 0, 'Rp') }}</small>
                            </VCardCustom>
                        </div>

                        <div class="column is-3">
                            <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status danger">
                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                    <span class="ml-1">Total Tagihan</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(totalKlaim ?? 0, 'Rp') }}</small>
                            </VCardCustom>
                        </div>

                    </div>
                </div>
            </div>
        </template>

        <template #action>
            <VButton icon="feather:save" :loading="isLoading" @click="saveCollect()" color="primary" raised>Simpan</VButton>
        </template>
    </VModal>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import Tag from 'primevue/tag';
import Divider from 'primevue/divider';
import sleep from '/@src/utils/sleep'

useHead({
    title: 'Daftar Tagihan Supplier - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    tglsetor: new Date(),
    tglbayar: new Date(),
    totalbayarawal: 0,
    tglCollect: new Date(),
})

const listColor: any = ref(Object.keys(useThemeColors()))

const activeTab = ref(0)
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const sourceOrder = ref([])
const dataSource = ref([])
const dataBayar = ref([])
const dataDetail = ref([])
const d_Rekanan = ref([])
const modalInput = ref(false)
const modalBayar = ref(false)
const modalConfirm = ref(false)
const modelCheck: any = ref([]);
const isLoadingBtn = ref(false)
const isLoadingBB = ref(false)
const isLoading = ref(false)
const d_CaraBayarFoot: any = ref([])
const d_caraBayar: any = ref([])
const d_caraSetor: any = ref([])
const confirm = useConfirm()
const modalCollect = ref(false)
const totalCollect: any = ref(0);
const totalTagihan: any = ref(0);
const totalKlaim: any = ref(0);
const totalPrice: any = ref(0);
const totalPPN: any = ref(0);
const DiskonTotal: any = ref(0);
const listChecked: any = ref([]);
const d_Setor = [
    {
        label: 'BELUM LUNAS',
        value: '1',
    },
    {
        label: 'LUNAS',
        value: '2',
    },
]

const currentPage: any = ref({
    limit: 5,
    rows: 50
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})

const fetchData = async () => {
    isLoading.value = true;
    let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
    let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
    let NoFaktur = item.value.NoFaktur ? `&NoFaktur=${item.value.NoFaktur}` : ''
    let rekanan = item.value.rekanan ? `&rekanan=${item.value.rekanan.value}` : ''
    let status = item.value.status ? `&status=${item.value.status}` : ''

    item.value.sisaHutang = 0
    await useApi().get(`bendahara/get-tagihan-supplier${tglAwal}${tglAkhir}${NoFaktur}${rekanan}${status}`).then((response) => {
        response.daftar.forEach((element: any, i: any) => {
            element.no = i + 1,
                element.subtotal = Math.round(parseFloat(element.subtotal))
            // element.sisautang = Math.round(parseFloat(element.sisautang))
        });
        isLoading.value = false;
        dataSource.value = response.daftar
        item.value.sisaHutang = response.sisahutang
        item.value.totalTagihan = response.totalTagihan
        item.value.totalbayar = response.totalbayar

    })
}
const fetchDropdown = () => {
    useApi().get(
        `/bendahara/get-list-bayar`).then((response: any) => {
            d_caraBayar.value = response.carabayar.map((e: any) => { return { label: e.carabayar, value: e.id, default: e } })
            d_caraSetor.value = response.carasetor.map((e: any) => { return { label: e.carasetor, value: e.id, default: e } })

        })
}

const getLabel = (status: any) => {
    switch (status) {
        case 'BELUM LUNAS':
            return 'danger';
        case 'LUNAS':
            return 'success';
    }
}

const fetchDetailTagihan = async (norec: any) => {

    await useApi().get(`bendahara/get-detail-tagihan-sup?norec_sp=${norec}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataDetail.value = response.data

    })

}
const isMozilla = () => {
    return navigator.userAgent.indexOf('Firefox') !== -1;
}

const changeNomi =async (e: any) => {
    if (isMozilla()) { await sleep(1000) }
    item.value.nominal = H.formatRupiah(e)

    item.value.terbilang = H.terbilang(parseFloat(item.value.nominal));
}

const changeBayar = (e: any) => {
    // item.value.nominalBayar = H.formatRupiah(item.value.nominalBayar)

    item.value.terbilangBayar = H.terbilang((parseFloat(item.value.nominalBayar)));

    // console.log(item.value.nominalBayar)
}

const detailRekanan = async (rknid: any) => {
    const response = await useApi().get(`bendahara/detail-rekanan-tagihan?idrekanan=${rknid}`)
}

const riwayatPembayaran = async (nostruk: any) => {
    await useApi().get(`bendahara/get-riwayat-bayar?nostruk=${nostruk}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataBayar.value = response.data

    })
}

const detail = (e: any) => {

    fetchDetailTagihan(e.norec)
    riwayatPembayaran(e.nostruk)

    item.value.noSTRUK = e.nostruk
    item.value.tglStruke = e.tglstruk
    item.value.nameRekanan = e.namarekanan
    item.value.noFaktur = e.nodokumen
    item.value.tglJatuh = e.tgljatuhtempo
    item.value.tglDok = e.tgldokumen

    modalInput.value = true
}

const bayarTagihan = (e: any) => {

    fetchDetailTagihan(e.norec)
    detailRekanan(e.rknid)

    item.value.norec_sp = e.norec
    item.value.sisaHutang = 0
    
    // item.value.sisautang = e.sisautang
    item.value.nodokumen = e.nodokumen
    item.value.nostruk = e.nostruk
    item.value.tgldokumen = e.tglstruk
    item.value.totalbayarawal = H.formatRupiah(parseFloat(e.totalbayar)) 
    item.value.tagihan = H.formatRupiah(parseFloat(e.subtotal))
    // item.value.rpTagihan = parseFloat(e.subtotal)
    item.value.terbilangTagihan = H.terbilang(item.value.tagihan);
    item.value.deskripsiTransaksi = 'PEMBAYARAN TAGIHAN SUPLIER A/N' + e.namarekanan
    item.value.uraianTransaksi = 'PEMBAYARAN TAGIHAN SUPLIER'

    modalBayar.value = true
}

const sudahBayar = () => {
    modalConfirm.value = true
}

const cetakKwitansi = async () => {

}

const cetakKwitansiBayar = async () => {

}

const kembaliKeun = () => {
    modalBayar.value = false
}
const simpanBayar = async () => {

    if (!item.value.caraBayar) {
        H.alert('error', 'Cara Bayar Harus Diisi')
        return
    }
    if (!item.value.nominalBayar) {
        H.alert('error', 'Nominal Pembayaran Harus Diisi')
        return
    }

    let json = {
        'sbk': {
            'nostruk': item.value.norec_sp,
            'nosbk': item.value.nosbk ? item.value.nosbk : '',
            'carabayar': item.value.caraBayar,
            'kelompoktransaksi': 107,
            'keteranganlainnya': item.value.deskripsiTransaksi,
            'tagihan': H.unFormatRupiah(item.value.tagihan),
            'totalbayar': H.unFormatRupiah (item.value.nominalBayar),
            'tglsbk': moment(item.value.tglbayar).format('YYYY-MM-DD HH:mm'),
            'bankrekanan': item.value.namabank ? item.value.namabank : '',
            'rekeningrekanan': item.value.norek ? item.value.norek : '',
            'pemilikrekanan': item.value.namapemilik ? item.value.namapemilik : '',
            'sisautang': H.unFormatRupiah(item.value.tagihan) - H.unFormatRupiah (item.value.nominalBayar) ,
            'keterangan': item.value.deskripsiTransaksi,
            'biayaadmin':  H.unFormatRupiah(item.value.nominal) ?  H.unFormatRupiah(item.value.nominal) : 0

        }

    }
    isLoadingBtn.value = true
    await useApi().post(
        `bendahara/save-bayar-tagihan-suplier`, json).then((response: any) => {
            isLoadingBtn.value = false
            modalBayar.value = false
            fetchData()
            clear()
        }).catch((e: any) => {
            isLoadingBtn.value = false
        })
}




const fetchRekanan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Rekanan.value = response
    })
}





const cari = () => {
    fetchData()
}

const clear = () => {
    delete item.value.nodokumen
    delete item.value.tgldokumen
    delete item.value.caraBayar
    delete item.value.deskripsiTransaksi
    delete item.value.uraianTransaksi
    delete item.value.nominal
    delete item.value.terbilang
    delete item.value.totalbayarawal
    delete item.value.tagihan
    delete item.value.terbilangTagihan
    delete item.value.nominalBayar

}

// watch(
//     () => item.value.nominalBayar,
//     (newValue, oldValue) => {
//         if (newValue != oldValue) {
//             item.value.nominalBayar = newValue
//         }
//         item.value.sisaHutang = item.value.tagihan - newValue;
//     }
// )

const collectTagihan = (e: any) => {

    fetchData()


    modalCollect.value = true
}

function checkedAll(e: any) {
    totalCollect.value = 0;
    totalKlaim.value = 0;
    modelCheck.value = []
    listChecked.value = []
    if (e) {
        dataSource.value.forEach((e: any) => {
            listChecked.value.push(e)
            modelCheck.value[e.norec] = true
        });
    }
    totalCollect.value = listChecked.value.length;
    listChecked.value.map((value: any, index: number) => {
        value.no = index + 1;
        totalKlaim.value += Number(value.subtotal);
    })
}
function checkedItems() {
    totalCollect.value = 0;
    totalKlaim.value = 0;
    totalPrice.value = 0;
    totalPPN.value = 0;
    DiskonTotal.value = 0;
    const objectKeys = Object.keys(modelCheck.value);
    for (let x = 0; x < objectKeys.length; x++) {
        const element = objectKeys[x];

        if (modelCheck.value[element] === true) {
            const checkedItem = dataSource.value.find(item => item.norec === element);

            if (checkedItem && !listChecked.value.some(item => item.norec === element)) {
                listChecked.value.push(checkedItem);
            }
        } else {
            listChecked.value = listChecked.value.filter(item => item.norec !== element);
        }
    }
    totalCollect.value = listChecked.value.length;
    listChecked.value.map((value: any, index: number) => {
        value.no = index + 1;
        totalKlaim.value += Number(value.subtotal);
        totalPrice.value += Number(value.total);
        totalPPN.value += Number(value.totalppn);
        DiskonTotal.value += Number(value.totaldiskon);
    })

    console.log(listChecked.value)
}

const saveCollect = async () => {
    var totalharga = 0;
    var totalppn = 0;
    var totaldiskon = 0;
    var totaltagihan = 0;

    if (listChecked.value.length == 0) {
        H.alert('warning', 'Belum ada data yang di pilih !')
        return
    }

    for (var i = listChecked.value.length - 1; i >= 0; i--) {
        totalharga = totalharga + parseFloat(listChecked.value[i].total);
        totalppn = totalppn + parseFloat(listChecked.value[i].totalppn);
        totaldiskon = totaldiskon + parseFloat(listChecked.value[i].totaldiskon);
    }
    totaltagihan = totalharga - totaldiskon + totalppn;

    var objSave = {
        "norec": '',
        "objectrekananfk": item.value.rekanan.value,
        "totalharga": totalharga != undefined ? totalharga : 0,
        "totalppn": totalppn != undefined ? totalppn : 0,
        "totaldiskon": totaldiskon != undefined ? totaldiskon : 0,
        "totaltagihan": totaltagihan != undefined ? totaltagihan : 0,
        "tglcollecting":  moment(item.value.tglCollect).format('YYYY-MM-DD HH:mm'),
        "detail": listChecked.value
    }
    isLoading.value = true
    await useApi().post(
        `bendahara/save-collecting`, objSave).then((response: any) => {
            isLoading.value = false
            modalCollect.value = false
            fetchData()
        }).catch((e: any) => {
            isLoading.value = false
        })
}




fetchData()
fetchDropdown()



watch(currentPage.value, () => {
    fetchData()
})



</script>
  
  
  
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';



.fs-075 {
    font-size: 0.9rem;
}


.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.form-layout {
    // max-width: 740px;
    margin: 0 auto;

    &.is-separate {
        // max-width: 1040px;

        .form-outer {
            background: none;
            border: none;

            .form-body {
                display: flex;

                .form-section {
                    flex-grow: 2;
                    padding: 10px;
                    width: 50%;

                    .form-section-inner {
                        @include vuero-s-card;

                        padding: 40px;

                        &.has-padding-bottom {
                            padding-bottom: 60px;
                            height: 100%;
                        }

                        >h3 {
                            font-family: var(--font-alt);
                            font-size: 1.2rem;
                            font-weight: 600;
                            color: var(--dark-text);
                            margin-bottom: 30px;
                        }

                        .columns {
                            .column {
                                padding-top: 0.25rem;
                                padding-bottom: 0.25rem;
                            }
                        }

                        .radio-boxes {
                            display: flex;
                            justify-content: space-between;
                            margin-left: -8px;
                            margin-right: -8px;

                            .radio-box {
                                position: relative;
                                width: calc(50% - 16px);
                                margin: 8px;

                                &:focus-within {
                                    border-radius: 3px;
                                    outline-offset: var(--accessibility-focus-outline-offset);
                                    outline-width: var(--accessibility-focus-outline-width);
                                    outline-style: var(--accessibility-focus-outline-style);
                                    outline-color: var(--primary);
                                }

                                input {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    height: 100%;
                                    width: 100%;
                                    opacity: 0;
                                    cursor: pointer;

                                    &:checked {
                                        +.radio-box-inner {
                                            background: var(--primary);
                                            border-color: var(--primary);
                                            box-shadow: var(--primary-box-shadow);

                                            .fee,
                                            p {
                                                color: var(--smoke-white);
                                            }
                                        }
                                    }
                                }

                                .radio-box-inner {
                                    background: var(--white);
                                    border: 1px solid var(--fade-grey-dark-3);
                                    text-align: center;
                                    border-radius: var(--radius);
                                    font-family: var(--font);
                                    font-weight: 600;
                                    font-size: 0.9rem;
                                    transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                        height 0.3s, width 0.3s;
                                    padding: 30px 20px;

                                    .fee {
                                        font-family: var(--font);
                                        font-weight: 700;
                                        color: var(--dark-text);
                                        font-size: 2.4rem;
                                        line-height: 1;

                                        span {
                                            &::after {
                                                content: '$';
                                                position: relative;
                                                top: -10px;
                                                font-size: 1.5rem;
                                            }
                                        }
                                    }

                                    p {
                                        font-family: var(--font-alt);
                                    }
                                }
                            }
                        }

                        .control {
                            >p {
                                padding-top: 12px;

                                >span {
                                    display: block;
                                    font-size: 0.9rem;

                                    span {
                                        font-weight: 500;
                                        color: var(--dark-text);
                                    }
                                }
                            }
                        }
                    }

                    .form-section-outer {
                        .checkboxes {
                            padding: 16px 0;

                            .checkbox {
                                padding: 0;
                                font-size: 0.9rem;
                            }
                        }

                        .button-wrap {
                            .button {
                                min-height: 60px;
                                font-size: 1.05rem;
                                font-weight: 600;
                                font-family: var(--font-alt);
                            }
                        }
                    }
                }
            }
        }
    }
}

.is-dark {
    .form-layout {
        &.is-separate {
            .form-outer {
                background: none !important;

                .form-body {
                    .form-section {
                        .form-section-inner {
                            @include vuero-card--dark;

                            >h3 {
                                color: var(--dark-dark-text);
                            }

                            .radio-boxes {
                                .radio-box {
                                    input:checked+.radio-box-inner {
                                        background: var(--primary);
                                        border-color: var(--primary);
                                        box-shadow: var(--primary-box-shadow);

                                        .fee,
                                        p {
                                            color: var(--smoke-white);
                                        }
                                    }

                                    .radio-box-inner {
                                        background: var(--dark-sidebar-light-2);
                                        border-color: var(--dark-sidebar-light-12);

                                        .fee {
                                            color: var(--dark-dark-text);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;
                    flex-direction: column;

                    .form-section {
                        width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;

                    // flex-direction: column;

                    .form-section {
                        // width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

.all-projects {
    .all-projects-header {
        display: flex;
        padding: 20px;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        margin-bottom: 1.5rem;

        .header-item {
            width: 25%;
            border-right: 1px solid var(--fade-grey-dark-3);

            &:last-child {
                border-right: none;
            }

            .item-inner {
                text-align: center;

                .lnil,
                .lnir {
                    font-size: 2.2rem;
                    margin-bottom: 6px;
                    color: var(--primary);
                }

                span {
                    display: block;
                    font-family: var(--font);
                    font-weight: 600;
                    font-size: 1.4rem;
                    color: var(--dark-text);
                }

                p {
                    font-family: var(--font-alt);
                }
            }
        }
    }

    .projects-card-grid {
        .grid-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
            padding: 20px;
            background: var(--white);
            border: 1px solid var(--fade-grey-dark-3);
            border-radius: var(--radius-large);

            .top-section {
                .head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;

                    h3 {
                        font-size: 1rem;
                        font-family: var(--font-alt);
                        color: var(--dark-text);
                        font-weight: 600;
                    }
                }

                .body {
                    p {
                        font-family: var(--font);
                        color: var(--light-text);
                    }
                }
            }

            .bottom-section {
                display: flex;

                .foot-block {
                    margin-right: 30px;

                    .heading {
                        font-family: var(--font-alt);
                        font-size: 0.75rem;
                        color: var(--light-text-dark-22);
                    }

                    >p {
                        padding-top: 5px;
                    }

                    .developers {
                        display: flex;

                        .v-avatar {
                            margin-right: 6px;
                        }
                    }
                }
            }
        }
    }
}

.heading {
    font-family: var(--font-alt);
    font-size: 0.75rem;
    color: var(--light-text-dark-22);
}

.is-dark {
    .all-projects {
        .all-projects-header {
            background: var(--dark-sidebar-light-6);
            border-color: var(--dark-sidebar-light-12);

            .header-item {
                border-color: var(--dark-sidebar-light-18);

                span {
                    color: var(--dark-dark-text);
                }

                i {
                    color: var(--primary) !important;
                }
            }
        }

        .projects-card-grid {
            .grid-item {
                background: var(--dark-sidebar-light-6);
                border-color: var(--dark-sidebar-light-12);

                .top-section {
                    .head {
                        h3 {
                            color: var(--dark-dark-text);
                        }
                    }
                }

                .bottom-section {
                    .foot-block {
                        .heading {
                            color: var(--light-text-dark-12);
                        }
                    }
                }
            }
        }
    }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
    max-width: 30% !important;
}
</style>
  