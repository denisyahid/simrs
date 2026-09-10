<template>
    <div class="column">
        <VCard>
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Entry Anggaran</h3>
                            </div>
                            <!-- <div class="right">
                                <div class="buttons">
                                    <VField class="is-autocomplete-select">
                                        <span>Div</span>
                                        <VControl icon="feather:search" class="prime-auto-select">
                                            <Dropdown v-model="item.searchdivkeg" :options="d_listDiv"
                                                :optionLabel="'div'" class="is-stacked" placeholder="Pilih data"
                                                style="width: 100%;" showClear :filter="false" />
                                        </VControl>
                                    </VField>
                                    
                                    <VButton type="button" icon="feather:search" :loading="isLoadingCari" color="success" raised
                                        @click="fetchData()" > Search
                                    </VButton>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <VField>
                                    <VLabel class="required-field">Organisasi</VLabel>
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.organisasi" placeholder=""
                                            class="is-rounded_Z" disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField class="is-autocomplete-select">
                                    <span>Div</span>
                                    <VControl icon="feather:search" class="prime-auto-select">
                                        <Dropdown v-model="item.searchdivkeg" :options="d_listDiv"
                                            :optionLabel="'div'" class="is-stacked" placeholder="Pilih data"
                                            style="width: 100%;" showClear :filter="false" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField class="is-autocomplete-select">
                                    <span>Tahap</span>
                                    <VControl icon="feather:search" class="prime-auto-select">
                                        <Dropdown v-model="item.searchtahapkeg" :options="d_listTahapKegiatan"
                                            :optionLabel="'tahap'" class="is-stacked" placeholder="Pilih data"
                                            style="width: 100%;" showClear :filter="false" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select
                            mt-0 pt-0" v-slot="{ id }">
                                    <span>Tahun</span>
                                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="item.tahun" :options="d_Tahun" :optionLabel="'tahun'"
                                            class="is-rounded" placeholder="Tahun" style="width: 100%;" :filter="true"
                                            showClear />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <span><br></span>
                                <VButton type="button" icon="feather:search" :loading="isLoading" color="primary" raised
                                    @click="fetchData()" > Search
                                </VButton>
                            </div>
                        </div>
                    </div>
                    <DataTable paginator :rows="20" :loading="isLoadingTableAll" v-model:filters="filters" :value="dataSourceKegiatan" removableSort tableStyle="min-width: 50rem" :globalFilterFields="['id', 'kode','keterangan']" filterDisplay="row">
                        <template #header>
                            <div class="columns is-multiline">
                                
                                <div class="column is-10">
                                    <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised
                                        @click="createKegiatan()" > Tambah
                                    </VButton>
                                </div>
                                <div class="column is-2">
                                    <span class="p-input-icon-left">
                                        <InputText v-model="filters['global'].value" placeholder="Search" />
                                    </span>
                                </div>
                            </div>
                        </template>
                        <Column :exportable="false" header="#" style="width:30px">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="feather:file" class="mr-3" color="success"
                                    circle outlined raised v-tooltip-prime="'Detail'" :loading="isLoadingBtn"
                                    @click="editKegiatanT(slotProps.data)">
                                </VIconButton>
                            </template>
                        </Column>
                        <Column field="kode" header="Kode" sortable style="width: 10%"></Column>
                        <Column field="keterangan" header="Keterangan" sortable style="width: 30%"></Column>
                        <Column field="namalengkap" header="PPTK" sortable style="width: 20%"></Column>
                        <Column field="tahap" header="Tahap Anggaran" sortable style="width: 10%"></Column>
                        <Column field="tahun" header="Tahun Anggaran" sortable style="width: 5%"></Column>
                        <Column field="targetmasuk" header="target Kinerja" sortable style="width: 20%">
                            <template #body="slotProps">
                                {{ H.formatRp(slotProps.data.targetmasuk, "Rp. ") }}
                            </template>
                        </Column>
                        <Column field="namakelompok" header="Div" sortable style="width: 20%"></Column>
                        <Column :exportable="false" header="#" style="width:15%">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="feather:trash" class="mr-3" color="danger"
                                    circle outlined raised v-tooltip-prime="'Hapus'"
                                    @click="deleteKegiatanT(slotProps.data)">
                                </VIconButton>
                            </template>
                        </Column>
                        
                    </DataTable>
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <span><br></span>
                            <VButton type="button" icon="feather:search" :loading="isLoading" color="primary" raised
                                @click="SettingTahap()" > Copy Kegiatan Anggaran 
                            </VButton>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </VCard>
    </div>
<Dialog :loading="isLoadingDialog"  v-model:visible="popupTambah" :style="{width: '100%'}" header="Tambah Anggaran" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-2">
            <VField class="is-autocomplete-select">
                <span>Div</span>
                <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.div" :options="d_listDiv"
                        :optionLabel="'div'" class="is-stacked" placeholder="Pilih data"
                        style="width: 100%;" showClear :filter="false" />
                </VControl>
            </VField>
        </div>
        <div class="column is-2">
            <VField class="is-rounded-select is-autocomplete-select
            mt-0 pt-0" v-slot="{ id }" v-if="inputTahun">
                <span>Tahun</span>
                <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.tahuntambahkegiatan" :options="d_Tahun" :optionLabel="'tahun'"
                        class="is-rounded" placeholder="Tahun" style="width: 100%;" :filter="true"
                        showClear />
                </VControl>
            </VField>
        </div>
        <div class="column is-8">

        </div>
        <div class="column is-6">
            <VField>
                <span>Kode</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.kodeAdd" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
        
        <div class="column is-6">
            <VField>
                <span>Keterangan</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.keteranganAdd" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
    </div>
    <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="batalPopupDigit()" style="margin-right:5px">
            Batal
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
            @click="SimpanPopUpTambah()"> Simpan
        </VButton>
    </template>
</Dialog>
<Dialog :loading="isLoadingDialog"  v-model:visible="popupSubSubKegiatan" :style="{width: '100%'}" header="ENTRY RINCIAN ANGGARAN" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-12">
            <VField>
                <span>URUSAN PEMERINTAHAN</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.urusanPemerintahan" placeholder=""
                        class="is-rounded_Z" disabled/>
                </VControl>
            </VField>
        </div>
        
        <div class="column is-12">
            <VField>
                <span>ORGANISASI</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.organisasi" placeholder=""
                        class="is-rounded_Z" disabled/>
                </VControl>
            </VField>
        </div>
        <div class="column is-12">
            <VField>
                <span>PROGRAM</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.program" placeholder=""
                        class="is-rounded_Z" disabled />
                </VControl>
            </VField>
        </div>
        <div class="column is-12">
            <VField>
                <span>KEGIATAN</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.kegiatan" placeholder=""
                        class="is-rounded_Z"  disabled/>
                </VControl>
            </VField>
        </div>
        <div class="column is-6">
            <VField>
                <span>TAHUN ANGGARAN</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.thnAnggaran" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
        <div class="column is-6">
            <VField class="is-autocomplete-select">
                <span>TAHAP</span>
                <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.searchtahapkeg" :options="d_listTahapKegiatan"
                        :optionLabel="'tahap'" class="is-stacked" placeholder="Pilih data"
                        style="width: 100%;" showClear :filter="false" />
                </VControl>
            </VField>
        </div>
        <div class="column is-6">
            <VField class="is-autocomplete-select">
                <span>JENIS BELANJA</span>
                <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.jenisbelanja" :options="d_listJenisBelanja"
                        :optionLabel="'jenisbelanja'" class="is-stacked" placeholder="Pilih data"
                        style="width: 100%;" showClear :filter="false" />
                </VControl>
            </VField>
        </div>
        <div class="column is-6">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <VField>
                        <span>PPTK</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.pptktext" placeholder=""
                                class="is-rounded_Z"  disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <span><br></span>
                    <VButton :loading="isLoadingBtn" icon="lnir lnir-arrow-left rem-100" rounded outlined color="primary" @click="TambahPPTK()" style="margin-right:5px">
                        Add
                    </VButton>
                    <VButton type="button" rounded outlined color="danger" raised icon="feather:trash" :loading="isLoadingSimpan"
                        @click="DeletePPTK()"> Delete
                    </VButton>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <VField>
                <span>SUB KEGIATAN</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.subKegiatan" placeholder=""
                        class="is-rounded_Z"  disabled/>
                </VControl>
            </VField>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <VField>
                        <span>SUB SUB KEGIATAN</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.subSubKegiatankode" placeholder=""
                                class="is-rounded_Z"  disabled/>
                        </VControl>
                        
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <span><br></span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.subSubKegiatan" placeholder=""
                                class="is-rounded_Z"  disabled/>
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <span>INDIKATOR</span>
                </div>
                <div class="column is-4">
                    <span>TOLOK UKUR</span>
                </div>
                <div class="column is-4">
                    <span>TARGET KINERJA</span>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <hr>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-2">
                    <span>Masukan</span>
                </div>
                <div class="column is-5">
                    <VField>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.tolokUkurMasukan" placeholder=""
                                class="is-rounded_Z"  disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-5">
                    <VField>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.targetKinerjaMasukan" placeholder=""
                                class="is-rounded_Z"  disabled/>
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-2">
                    <span>Keluaran</span>
                </div>
                <div class="column is-5">
                    <VField>
                        <VTextarea class="textarea is-rounded" v-model="item.tolokUkurKeluaran" rows="4" placeholder="Catatan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VField>
                </div>
                <div class="column is-5">
                    <VField>
                        <VTextarea class="textarea is-rounded" v-model="item.targetKinerjaKeluaran" rows="4" placeholder="Catatan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VField>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-2">
                    <span>Hasil</span>
                </div>
                <div class="column is-5">
                    <VField>
                        <VTextarea class="textarea is-rounded" v-model="item.tolokUkurHasil" rows="4" placeholder="Catatan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VField>
                </div>
                <div class="column is-5">
                    <VField>
                        <VTextarea class="textarea is-rounded" v-model="item.targetKinerjaHasil" rows="4" placeholder="Catatan"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VField>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <VField class="is-autocomplete-select">
                <span>Div</span>
                <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.subsubkegiatandiv" :options="d_listDiv"
                        :optionLabel="'div'" class="is-stacked" placeholder="Pilih data"
                        style="width: 100%;" showClear :filter="false" disabled />
                </VControl>
            </VField>
        </div>
    </div>
    <template #footer>
        <!-- <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="batalPopupDigit()" style="margin-right:5px">
            Batal
        </VButton> -->
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
            @click="SimpanPopUpSubSubKegiatan()"> Simpan
        </VButton>
        <VButton icon="feather:arrow-right" rounded outlined color="warning" @click="nextSubSubKegiatan()" style="margin-right:5px" :loading="isLoadingBtn">
            Next
        </VButton>
    </template>
</Dialog>

<Dialog :loading="isLoadingDialog"  v-model:visible="popupPPTK" :style="{width: '100%'}" header="Tambah PPTK" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-6">
            <VField>
                <span>Kode Sub Sub Kegiatan</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.kodesubsubpptk" placeholder=""
                        class="is-rounded_Z" disabled />
                </VControl>
            </VField>
        </div>
        
        
        <div class="column is-6">
            <VField>
                <span>Nama Sub Sub Kegiatan</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.namasubsubpptk" placeholder=""
                        class="is-rounded_Z" disabled/>
                </VControl>
            </VField>
        </div>
        <div class="column is-6">
            <VField class="is-autocomplete-select">
                <span>PPTK</span>
                <!-- <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.pptk" :options="d_listPenerima" @complete="fetchDokter($event)"
                        :optionLabel="'text'" class="is-stacked" placeholder="Pilih data"
                        style="width: 100%;" showClear :filter="false" />
                </VControl> -->
                <VControl icon="feather:search">
                    <AutoComplete v-model="item.pptk"
                        :suggestions="d_listPenerima" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                        :field="'label'" placeholder="ketik nama petugas" />
                </VControl>
            </VField>
        </div>
    </div>
    <DataTable paginator :rows="20" :loading="isLoadingTableAll" :value="dataSourcePPTK" removableSort tableStyle="min-width: 50rem"  filterDisplay="row">
        <template #header>
            <div class="columns is-multiline">
                <div class="column is-10">
                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary" raised
                        @click="SimpanPPTK()" > Simpan
                    </VButton>
                </div>
            </div>
        </template>
        <Column field="kode" header="No" sortable style="width: 10%"></Column>
        <Column field="keterangan" header="Kode Sub Sub Kegiatan" sortable style="width: 25%"></Column>
        <Column field="namalengkap" header="Nama Sub Sub Kegiatan" sortable style="width: 25%"></Column>
        <Column field="tahap" header="PPTK" sortable style="width: 15%"></Column>
    </DataTable>

</Dialog>

<Dialog :loading="isLoadingDialog"  v-model:visible="popupRincianAnggaran"  :style="{width: '100%'}" header="ENTRY RINCIAN ANGGARAN" :modal="true" class="p-fluid">
	<div class="columns is-multiline">
        <div class="column is-12" style="font-size: large;">
			Sub Sub Kegiatan : {{rcn.judulKegiatan}}
		</div>
        <div class="column is-4" style="margin-top: 22px;">
			<div class="column is-12" style="font-size: large;text-align: left;">
				1 : {{rcn.mataAnggaranlv1}}
			</div>
			<div class="column is-12" style="text-align: left;">
				Total Rp. {{rcn.totallv1}}
			</div>
			<div class="column is-12" style="font-size: large;text-align: left;margin-top: 12px;">
				2 : {{rcn.mataAnggaranlv2}}
			</div>
			<div class="column is-12" style="text-align: left;">
				Total Rp. {{rcn.totallv2}}
			</div>
			<div class="column is-12" style="font-size: large;text-align: left;margin-top: 12px;">
				3 : {{rcn.mataAnggaranlv3}}
			</div>
			<div class="column is-12" style="text-align: left;">
				Total Rp. {{rcn.totallv3}}
			</div>
		</div>
        <div class="column is-8">
            <DataTable paginator :rows="5" :loading="isLoadingTable" v-model:filters="filtersAnggaran" :value="dataSourceDetailMT" removableSort tableStyle="min-width: 50rem" :globalFilterFields="['kodemataanggaran', 'namamataanggaran','total']" filterDisplay="row">
                <template #header>
                    <div class="columns is-multiline">
                        
                        
                        <div class="column is-2">
                            <span class="p-input-icon-left">
                                <InputText v-model="filtersAnggaran['global'].value" placeholder="Search" />
                            </span>
                        </div>
                        <div class="column is-10"></div>
                    </div>
                </template>
                <Column :exportable="false" header="Pilih" style="width:10%">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="feather:arrow-right" class="mr-3" color="success"
                                    circle outlined raised v-tooltip-prime="'Pilih'" :loading="isLoadingBtn"
                                    @click="klikDetailMT(slotProps.data)">
                                </VIconButton>
                            </template>
                        </Column>
                <Column field="kodemataanggaran" header="Kode Rek." sortable style="width: 20%"></Column>
                <Column field="namamataanggaran" header="Nama Rekening" sortable style="width: 30%"></Column>
                <Column field="total" header="Sub Total" sortable style="width: 20%">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.total, 'Rp. ') }}
                    </template>
                </Column>
            </DataTable>
        </div>
        <div class="column is-12">
            <DataTable paginator :rows="5" :loading="isLoadingTable" v-model:filters="filtersRincianAnggaran" :value="dataSourceRincianAnggaran" removableSort tableStyle="min-width: 50rem" :globalFilterFields="['keteranganbelanja']" filterDisplay="row">
                <template #header>
                    <div class="columns is-multiline">
                        
                        <div class="column is-4">
                            <VButton type="button" icon="feather:plus" :loading="isLoadingBtn" color="primary" raised :disabled="rcn.mataAnggaranlv1 == undefined"
                                @click="TambahKeterangan()" > Tambah
                            </VButton>
                        </div>
                        <div class="column is-4">
                            {{ rcn.mataAnggaranDipilih }}
                        </div>
                        <div class="column is-2">
                            <span class="p-input-icon-left">
                                <InputText v-model="filtersRincianAnggaran['global'].value" placeholder="Search" />
                            </span>
                        </div>
                    </div>
                </template>
                <Column :exportable="false" header="#" style="width:5%">
                    <template #body="slotProps">
                        <VIconButton type="button" icon="feather:arrow-right" class="mr-3" color="success"
                            circle outlined raised v-tooltip-prime="'Detail'" :loading="isLoadingBtn"
                            @click="editDetailMT(slotProps.data)">
                        </VIconButton>
                    </template>
                </Column>
                <Column field="nourut" header="No Urut" sortable style="width: 5%"></Column>
                <Column field="keteranganbelanja" header="Keterangan" sortable style="width: 30%"></Column>
                <Column field="jml" header="Jumlah" sortable style="width: 5%"></Column>
                <Column field="satuan" header="Satuan" sortable style="width: 5%"></Column>
                <Column field="hargasatuan" header="Harga Satuan" sortable style="width: 15%">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.hargasatuan, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="subtotal" header="Sub Total" sortable style="width: 25%">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.subtotal, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="asalproduk" header="Sumber Dana" sortable style="width: 15%"> </Column>
                <Column :exportable="false" header="#" style="width:10%">
                    <template #body="slotProps">
                        <VIconButton type="button" icon="feather:trash" class="mr-3" color="danger"
                            circle outlined raised v-tooltip-prime="'Hapus'" :loading="isLoadingBtn"
                            @click="HapusRincianKas(slotProps.data)">
                        </VIconButton>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</Dialog>

<Dialog :loading="isLoadingBtn"  v-model:visible="popupTambahKeteranganBelanja" :style="{width: '100%'}" header="Tambah Keterangan" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-1">
            <VField>
                <span>No Urut</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.noUrutKT" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
        <div class="column is-5">
            <VField>
                <span>Keterangan</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.keteranganKT" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
        <div class="column is-1">
            <VField>
                <span>Jumlah</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.jmlKT" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
        <div class="column is-2">
            <VField>
                <span>Satuan</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.satuanKT" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
        <div class="column is-3">
            <VField>
                <span>Harga Satuan</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.hargaSatuanKT" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
        <div class="column is-3">
            <VField>
                <span>Sub Total</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.subTotalKTText" placeholder=""
                        class="is-rounded_Z" disabled />
                </VControl>
            </VField>
        </div>
        
        <div class="column is-6">
            <VField class="is-autocomplete-select">
                <span>Sumber Dana</span>
                <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.sumberDanaKT" :options="d_listAsalProduk"
                        :optionLabel="'asalproduk'" class="is-stacked" placeholder="Pilih data"
                        style="width: 100%;" showClear :filter="false" />
                </VControl>
            </VField>
        </div>
        <div class="column is-6">
            <VField class="is-autocomplete-select">
                <span>Password</span>
                <VControl icon="feather:edit-3">
                    <VInput type="text" v-model="item.password" placeholder=""
                        class="is-rounded_Z" />
                </VControl>
            </VField>
        </div>
    </div>
    <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" rounded outlined color="danger" @click="batalPopupDigit()" style="margin-right:5px">
            Batal
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
            @click="SimpanPopUpAddKeterangan()"> Simpan
        </VButton>
    </template>
</Dialog>


<Dialog :loading="isLoadingDialog"  v-model:visible="popupTambahTahap" :style="{width: '100%'}" header="Copy Kegiatan Anggaran" :modal="true" class="p-fluid">
    <div class="column is-12">
        <div class="column is-12" style="font-size: large;text-align: left;">
            List Sub Sub Kegiatan
        </div>
        <DataTable paginator :rows="20" :loading="isLoadingTable" :value="dataSourceTambahTahap" removableSort tableStyle="min-width: 50rem" v-model:selection="selectedListSub">
            <Column selectionMode="multiple" style="width: 5%"></Column>
            <Column field="kodesubsubkegiatan" header="Kode" sortable style="width: 20%"></Column>
            <Column field="keterangansubsubkegiatan" header="Sub Sub Kegiatan" sortable style="width: 70%"></Column>
        </DataTable>
    </div>
    <div class="column is-12">
        <div class="columns is-multiline">
            <div class="column is-6">
                <VCard>
                    <div class="column is-  2">
                        <VField class="is-rounded-select is-autocomplete-select
                        mt-0 pt-0" v-slot="{ id }">
                            <span>Tahun Awal</span>
                            <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.tahunawal" :options="d_Tahun" :optionLabel="'tahun'"
                                    class="is-rounded" placeholder="Tahun" style="width: 100%;" :filter="true"
                                    showClear :loading="isLoadingCombo"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField class="is-autocomplete-select">
                            <span>Tahap Awal</span>
                            <VControl icon="feather:search" class="prime-auto-select">
                                <Dropdown v-model="item.tahapawal" :options="d_Tahap"
                                    :optionLabel="'tahap'" class="is-rounded" placeholder="Pilih data"
                                    style="width: 100%;" showClear :filter="false" :loading="isLoadingCombo"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField class="is-autocomplete-select">
                            <span>Sub Kegiatan</span>
                            <VControl icon="feather:search">
                                <Dropdown v-model="item.subkegiatantransfer" :options="d_subKegiatan" :optionLabel="'keterangan'"
                                    class="is-rounded" placeholder="Sub Kegiatan" :loading="isLoadingCombo" style="width: 100%;" :filter="true"
                                    showClear  />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                            <VButton type="button" icon="feather:search" :loading="isLoadingBtn" color="primary" raised 
                                @click="CariSettingTahap()" > Cari
                            </VButton>
                        </div>
                </VCard>
            </div>
            <div class="column is-6">
            <VCard>
                    <div class="column is-  2">
                        <VField class="is-rounded-select is-autocomplete-select
                        mt-0 pt-0" v-slot="{ id }">
                            <span>Tahun Akhir</span>
                            <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.tahunakhir" :options="d_Tahun" :optionLabel="'tahun'"
                                    class="is-rounded" placeholder="Tahun" style="width: 100%;" :filter="true"
                                    showClear :loading="isLoadingCombo"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField class="is-autocomplete-select">
                            <span>Tahap Akhir</span>
                            <VControl icon="feather:search" class="prime-auto-select">
                                <Dropdown v-model="item.tahapakhir" :options="d_Tahap"
                                    :optionLabel="'tahap'" class="is-rounded" placeholder="Pilih data"
                                    style="width: 100%;" showClear :filter="false" :loading="isLoadingCombo"/>
                            </VControl>
                        </VField>
                    </div>
                    
                    <div class="column is-4">
                            <VButton type="button" icon="feather:save" :loading="isLoadingBtn" color="primary" raised 
                                @click="SaveSettingTahap()" > Simpan
                            </VButton>
                        </div>
                </VCard>
            </div>
        </div>
    </div>
</Dialog>
<ConfirmDialog/>

</template>
<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Sidebar from 'primevue/sidebar';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'
import { useApi } from '/@src/composable/useApi'
import Panel from 'primevue/panel';
import Dialog from 'primevue/dialog';
import Badge from 'primevue/badge';
import { useWindowScroll } from '@vueuse/core'
import AutoComplete from 'primevue/autocomplete';

import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import moment from 'moment'
const confirm = useConfirm();
useHead({
  title: 'Entry Anggaran - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)


const item: any = reactive({})
const rcn: any = reactive({})
let isLoadingSimpan: any = ref(false)
let isLoadingTableAll: any = ref(false)
let isLoadingBtn: any = ref(false)

let d_listDiv: any = ref([])
let d_listTahapKegiatan: any = ref([])
let d_listJenisBelanja: any = ref([])
let d_listPenerima: any = ref([])
let d_listAsalProduk: any = ref([])
let d_subKegiatan: any = ref([])
let d_Tahap: any = ref([])
const selectedListSub = ref();

let dataSourceKegiatan: any = ref([])
let dataSourcePPTK: any = ref([])
let dataSourceRincianAnggaran: any = ref([])
let dataSourceDetailMT: any = ref([])
let dataSourceTambahTahap: any = ref([])
let DataTabKeteranganBelanja: any = ref([])

let popupTambah: any = ref(false)
let popupUrusan: any = ref(false)
let popupKegiatan: any = ref(false)
let popupSubSubKegiatan: any = ref(false)
let popupPPTK: any = ref(false)
let popupRincianAnggaran: any = ref(false)
let popupTambahKeteranganBelanja: any = ref(false)
let popupTambahTahap: any = ref(false)
const d_Bulan: any = ref(H.monthList())
const d_Tahun: any = ref([])
var DataKeteranganBelanja: any = ref([]);
let inputTahun: any = ref(false)
let nourut: any = reactive(false)
let idkegiatan: any = reactive(null)
let idkegiatananggaran: any = reactive('')
let idkegiatanangkas: any = reactive(0)
var norecrcntotalanggaran: any = ref('')
for (let i = parseFloat(H.formatDate(new Date(),'YYYY')) - 5; i <= parseFloat(H.formatDate(new Date(),'YYYY')) + 5; i++) {
    d_Tahun.value.push({
        id: i, tahun: i
    })
}


const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const filtersPPTK = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const filtersAnggaran = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const filtersRincianAnggaran = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

// item.organisasi = '1.02.2.22.0.00.01.0004 - RS Jiwa GRHASIA DINKES DIY'
loadCombo()
async function loadCombo(){
    isLoadingTableAll.value = true
    await useApi().get(
        `anggaran/get-combo`
    ).then((response) => {
        d_listTahapKegiatan.value = response.tahap
        d_listJenisBelanja.value = response.jenisbelanja
        d_listDiv.value = response.kelompokanggaran
        d_listAsalProduk.value = response.asalproduk
        isLoadingTableAll.value= false
        d_subKegiatan.value = response.kegiatancombo
        d_Tahap.value = response.tahap
    })
    await useApi().get('anggaran/get-data-setting-anggaran').then((response) => {
        d_listTahapKegiatan.value.forEach(element => {
            if(element.id == response.data[0].objecttahapaktivfk){
                item.searchtahapkeg = element
                item.tahapdetail = element
            }
        });
        d_Tahun.value.forEach(element => {
            if(element.tahun == response.data[0].tahunanggaran){
                item.tahun = element
            }
        });
        item.organisasi = response.data[0].organisasi
        loadData()

    })
}
async function loadData() {
    item.id = undefined
    isLoadingTableAll.value = true
    isLoadingBtn.value = false
    var div = ''
    if(item.searchdivkeg != undefined){
        div= "&div="+ item.searchdivkeg.id
    }
    var tahap = ''
    if(item.searchtahapkeg != undefined){
        tahap= "&tahap="+ item.searchtahapkeg.id
    }
    var tahun = ''
    if(item.tahun != undefined){
        tahun= "&tahun="+ item.tahun.id
    }
    var tahunawal = ''
    if(item.tahunawal != undefined){
        tahunawal= "&tahunawal="+ item.tahunawal.id
    }
    var subkegiatan = ''
    if(item.subkegiatantransfer != undefined){
        subkegiatan= "&subkegiatan="+ item.subkegiatantransfer.id
    }
    var tahapawal = ''
    if(item.tahapawal != undefined){
        tahapawal= "&tahapawal="+ item.tahapawal.id
    }
    await useApi().get(
        `anggaran/get-kegiatan-anggaran?`+div + tahap+tahun+ tahunawal + tahapawal +subkegiatan
    ).then((response) => {
        isLoadingTableAll.value = false
        dataSourceKegiatan.value = response.data
        dataSourceTambahTahap.value = response.kegiatansubsub
    })
}
async function createKegiatan() {
    item.id = undefined
    popupTambah.value = true
}
async function SimpanPopUpTambah(){
    isLoadingSimpan.value = true
    var jmlkode = item.kodeAdd.length
    var divS = 0;
    var id = ''
    if(item.id != undefined){
        id = item.id
    }
    if(jmlkode == 7){
        divS = 1
    }else 
    if(jmlkode == 12){
        divS = 2
    }else if(jmlkode == 15){
        divS = 3
    }else if(jmlkode > 15){
        divS = 4
    }
    var tahuns = null
    if(item.tahuntambahkegiatan != undefined){
        tahuns = moment(item.tahuntambahkegiatan).format('YYYY')
    }
    var jenisbelanja = null
    if(item.jenisbelanjatambah != undefined){
        jenisbelanja = item.jenisbelanjatambah.id
    }
    var objSave = {
        id: '',
        kode: item.kodeAdd,
        keterangan: item.keteranganAdd,
        kodeexternal: null,
        namaexternal: null,
        reportdisplay: null,
        tahun: tahuns,
        tahap: item.tahaptambah!= undefined ? item.tahaptambah.id : null,
        indikatormasuk: null,
        indikatorkeluaran: null,
        indikatorhasil: null,
        targetmasuk: null,
        targetkeluaran: null,
        targethasil: null,
        div: item.div.id,
        pptk: item.pptktambah != undefined ? item.pptktambah.value : null,
        jenisbelanja: jenisbelanja
    }
    await useApi().post('perencanaan/save-kegiatan-anggaran', objSave).then( (response)=> {
        isLoadingSimpan.value = false
        loadData()
        popupTambah.value = false
        item.div = undefined
        item.kodeAdd = undefined
        item.keteranganAdd = undefined
    })
}
async function fetchData() {
    loadData()
}

watch(
    () => item.div,
    (newValue, oldValue) => {
        if(item.div != undefined){
            if(item.div.id == 4){
                inputTahun.value = true
            }else{
                inputTahun.value = false
            }
        }else{
            inputTahun.value = false
        }

    }
)
async function editKegiatanT(data) {
    isLoadingBtn.value = true
    norecrcntotalanggaran = data.id 
    item.targetKinerjaMasukan = H.formatRp(data.targetmasuk, 'Rp. ') 
    item.targetKinerjaMasukanH = data.targetmasuk
    await useApi().get("perencanaan/get-total-anggaran-rcn?kegiatan=" + norecrcntotalanggaran).then( (data) => {
        var total = 0
        if(data.data.length == 0){
            rcn.totalAnggaran = H.formatRp(total, 'Rp. ')
            rcn.paguAnggaran = 0
        } else{
            total = data.data[0].total
            rcn.totalAnggaran = H.formatRp(total, 'Rp. ')
            rcn.paguAnggaran = 0
        }
    })
    idkegiatan = data.id

    item.savepptk = data.objectpptkfk != null ? data.objectpptkfk: null

    await useApi().get("perencanaan/get-jenis-belanja?kegiatan=" + data.id).then((response)=> {
        if(response.data.length == 0){
            item.jenisbelanja = null
        } else{
            item.jenisbelanja = {id:response.data[0].id, jenisbelanja:response.data[0].jenisbelanja}
        }
        
    })

    item.id = data.id
    item.subsubketerangan = data.id
    let kodeArr = data.kode.split(" ");
    let kodeArr2 = kodeArr[0].split(".");
    let a1 = kodeArr2[0] + '.' + kodeArr2[1]
    let b1 = kodeArr2[2]
    let c1 = kodeArr2[3]  + '.' + kodeArr2[4]
    let d1 = kodeArr2[5]  

    if(data.div == 0){
        item.div = {div: data.namakelompok, id: data.div}
        popupTambah.value = true
        item.kodeAdd = a1
        item.keteranganAdd = data.keterangan
        item.id = data.id
    }
    else if(data.div == 1){
        item.div = {div: data.namakelompok, id: data.div}
        popupTambah.value = true
        item.keteranganAdd = data.keterangan
        item.kodeAdd = b1
        item.id = data.id

    }else if(data.div == 2){
        item.div = {div: data.namakelompok, id: data.div}
        popupTambah.value = true
        item.keteranganAdd = data.keterangan
        item.kodeAdd = c1
        item.id = data.id

    }else if(data.div == 3){
        item.div = {div: data.namakelompok, id: data.div}
        popupTambah.value = true
        item.keteranganAdd = data.keterangan
        item.kodeAdd = d1 
        item.id = data.id

    }else if(data.div == 4){
        idkegiatananggaran = data.id
        item.subsubkegiatandiv = {div: data.namakelompok, id: data.div}
        if(data.kodeurusan == null){
            isLoadingBtn.value = false
            H.alert('warning','Harap isi Anggaran terlebih dahulu dengan lengkap!')
            popupTambah.value = true
            return
        }
        item.urusanPemerintahan = data.kodeurusan + ' ' + data.keteranganurusan
        item.program = data.kodeprogram + ' ' + data.keteranganprogram
        item.kegiatan = data.kodekegiatan + ' ' + data.keterangankegiatan
        item.subKegiatan = data.kodesubkegiatan + ' ' + data.keterangansubkegiatan
        
        for (let index = 0; index < dataSourceKegiatan.length; index++) {
            let element = dataSourceKegiatan[index];
            if(element.kode == a1 +'.'+ b1 +'.'+ c1+'.'+ d1){
                //item.subKegiatan = element.kode + ' ' + element.keterangan
            }
            item.kode4 = a1 +'.'+ b1 +'.'+ c1+'.'+ d1
        }
        let TotalAnggaran = 0;
        
        item.subSubKegiatankode = kodeArr[0]//data.kode//.substring(16) 
        item.subSubKegiatan =  data.keterangan
        item.thnAnggaran = moment(data.tahun).format('YYYY') 
        item.pptktext = data.namalengkap
        

        rcn.judulKegiatan =  data.keterangan

        //item.tolokUkurMasukan = data.indikatormasuk
        item.tolokUkurMasukan = 'Dana'
        // item.targetKinerjaMasukan = H.formatRp(TotalAnggaran, "Rp. ")//data.targetmasuk
        // item.targetKinerjaMasukanH = TotalAnggaran//data.targetmasuk
        item.tolokUkurKeluaran = data.indikatorkeluaran
        item.targetKinerjaKeluaran = data.targetkeluaran
        item.tolokUkurHasil = data.indikatorhasil
        item.targetKinerjaHasil = data.targethasil
        popupSubSubKegiatan.value = true;  
        isLoadingBtn.value = false
        LoadTotalKeterangan()
    }

    item.keterangan = data.keterangan
    item.div = data.div
}
async function LoadTotalKeterangan(){
var idpegawai = ''
    
    await useApi().get("perencanaan/get-total-mata-anggaran?tahun=" + moment(item.thnAnggaran).format('YYYY') + 
    "&tahap=" + item.tahapdetail.id + "&idpegawai=" + idpegawai + "&cek=" + item.isCekAdaAnggaranSaja + "&noreckegiatan=" + idkegiatananggaran).then((response)=> {
            var DataTotalMataAnggaran = response.data
        //    var listAsalProduk = response.asalproduk
            dataSourceDetailMT.value = DataTotalMataAnggaran
            DataTabKeteranganBelanja = []; //response.keteranganbelanja
            // let DataInsertAlokasi = [];
            let dataAlokasi = response.alokasi;
            for (let index = 0; index < response.keteranganbelanja.length; index++) {
                let element = response.keteranganbelanja[index];
                for (let aa = 0; aa < dataAlokasi.length; aa++) {
                    let alokasielement = dataAlokasi[aa];
                    for (let bb = 0; bb < alokasielement.length; bb++) {
                        let elementbb = alokasielement[bb];
                        if (elementbb.kodemataanggaran == element.kodemataanggaran) {
                            if (elementbb.bulanint == 1) {
                                elementbb.jan = elementbb.nilai
                            }
                            if (elementbb.bulanint == 2) {
                                elementbb.feb = elementbb.nilai
                            }
                            if (elementbb.bulanint == 3) {
                                elementbb.mar = elementbb.nilai
                            }
                            if (elementbb.bulanint == 4) {
                                elementbb.apr = elementbb.nilai
                            }
                            if (elementbb.bulanint == 5) {
                                elementbb.mei = elementbb.nilai
                            }
                            if (elementbb.bulanint == 6) {
                                elementbb.jun = elementbb.nilai
                            }
                            if (elementbb.bulanint == 7) {
                                elementbb.jul = elementbb.nilai
                            }
                            if (elementbb.bulanint == 8) {
                                elementbb.agt = elementbb.nilai
                            }
                            if (elementbb.bulanint == 9) {
                                elementbb.sep = elementbb.nilai
                            }
                            if (elementbb.bulanint == 10) {
                                elementbb.okt = elementbb.nilai
                            }
                            if (elementbb.bulanint == 11) {
                                elementbb.nov = elementbb.nilai
                            }
                            if (elementbb.bulanint == 12) {
                                elementbb.des = elementbb.nilai
                            }

                            elementbb.subtotal = element.subtotal
                        }
                        DataTabKeteranganBelanja.push(elementbb)
                    }

                }
                // DataInsertAlokasi.push(element)

            }
            // DataTabKeteranganBelanja = DataInsertAlokasi
            // // item.DataKeteranganBelanja.value = DataInsertAlokasi
            // dataSourceKeteranganBelanja = new kendo.data.DataSource({
            //     data: response.alokasimataanggaran,
            //     group: groupmataanggaran,

            // });


            // dataSearchSourceKeteranganBelanja = dataSourceKeteranganBelanja

            // dataSourceDetailKeteranganBelanja = new kendo.data.DataSource({
            //     data: response.detailalokasimataanggaran,
            // });

        })
    
}
async function DeletePPTK(){
    // if (datakegiatanbro.islockrba == true) {
    // toastr.error('Sudah dilock!')
    // return
    // }
    item.pptktext = ''
    item.savepptk = null
}
async function TambahPPTK(){
    // if(datakegiatanbro.islockrba == true) {
    //     toastr.error('Sudah dilock!')
    //     return
    // }
    isLoadingBtn.value = true
    await useApi().get("perencanaan/get-daftar-pptk?id=" + idkegiatan + "&tahun=" + item.thnAnggaran + "&kode=" + item.subSubKegiatankode).then((response)=> {
            var datas = response.data
            for(var i = 0; i < response.data.length; i++){
                response.data[i].no = i+1
            }
            dataSourcePPTK.value = datas
            
        });
    if(item.subSubKegiatankode != undefined && item.subSubKegiatankode != ''){
        item.kodesubsubpptk = item.subSubKegiatankode
        item.namasubsubpptk = item.subSubKegiatan
    } else if(item.subsubkegiatanangkas != undefined){
        item.kodesubsubpptk = item.subsubkegiatanangkas.kode
        item.namasubsubpptk = item.subsubkegiatanangkas.keterangan
    }
    
    popupPPTK.value = true
    isLoadingBtn.value = false

}
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_listPenerima.value = response
    })
}
async function SimpanPPTK(){
    item.savepptk = item.pptk.value
    item.pptktext = item.pptk.label
    //console.log(item.pptk.value)
    popupPPTK.value = false
}

async function nextSubSubKegiatan() {
    isLoadingBtn.value = true
    rcn.mataAnggaranlv1 = undefined
    rcn.mataAnggaranlv2 = undefined
    rcn.mataAnggaranlv3 = undefined
    dataSourceRincianAnggaran.value = []
    item.isCekAdaAnggaranSaja = false
    // await useApi().get("perencanaan/get-total-anggaran-rcn?kegiatan=" + norecrcntotalanggaran).then( (response) => {
    //     var total = 0
    //     if(response.data.length == 0){
    //         rcn.totalAnggaran = H.formatRp(total, "Rp. ")
    //         rcn.paguAnggaran = 0
    //     } else{
    //         total = response.data[0].total
    //         rcn.totalAnggaran = H.formatRp(total, "Rp. ")
    //         rcn.paguAnggaran = 0
    //     }

    //     item.targetKinerjaMasukan = H.formatRp(total, "Rp. ")
    //     item.targetKinerjaMasukanH = total
    //     popupRincianAnggaran.value = true
    //     isLoadingBtn.value = false
        
    // })
    popupRincianAnggaran.value = true
        isLoadingBtn.value = false
    
    
}
async function klikDetailMT(dataMataAnggaranSelected) {
    isLoadingBtn.value = true
    if (dataMataAnggaranSelected != undefined) {
        let kodeArr = dataMataAnggaranSelected.kodemataanggaran.split(".");
        let a1 = kodeArr[0] 
        let b1 = kodeArr[1]
        let c1 = kodeArr[2] 
        let cek = 'III.'
        // console.log(a1)
        // console.log(b1)
        // console.log(c1)
        let myRega1 = new RegExp(a1 + ".*")
        let myRegb1 = new RegExp(a1 + '.' + b1 + ".*")
        let myRegc1 = new RegExp(a1 + '.' + b1 + '.' + c1 + ".*")
        
        let d1 = dataMataAnggaranSelected.kodemataanggaran
        rcn.totallv1 = 0
        rcn.totallv2 = 0
        rcn.totallv3 = 0
        for (let index = 0; index < dataSourceDetailMT.value.length; index++) {
            let element = dataSourceDetailMT.value[index];
            if(a1 == 'I'){
                if(element.kodemataanggaran.match(myRega1) && !element.kodemataanggaran.match(/III.*/) && !element.kodemataanggaran.match(/II.*/)){
                    rcn.mataAnggaranlv1 = a1
                    rcn.totallv1 += parseInt(element.total)
                }
            } else if(a1 == 'II'){
                if(element.kodemataanggaran.match(myRega1) && !element.kodemataanggaran.match(/III.*/)){
                    rcn.mataAnggaranlv1 = a1
                    rcn.totallv1 += parseInt(element.total)
                }
            } else if(a1 == 'III'){
                if(element.kodemataanggaran.match(myRega1)){
                    rcn.mataAnggaranlv1 = a1
                    rcn.totallv1 += parseInt(element.total)
                }
            }
            
            if(element.kodemataanggaran.match(myRegb1)){
                rcn.mataAnggaranlv2 = a1 + '.' + b1
                rcn.totallv2 += parseInt(element.total)
            }
            if(element.kodemataanggaran.match(myRegc1)){
                rcn.mataAnggaranlv3 = a1 + '.' + b1 + '.' + c1
                rcn.totallv3 += parseInt(element.total)
            }
        }
        rcn.totallv3 = H.formatRp(rcn.totallv3, "Rp. ")
        rcn.totallv2 = H.formatRp(rcn.totallv2, "Rp. ")
        rcn.totallv1 = H.formatRp(rcn.totallv1, "Rp. ")
        rcn.mataAnggaranDipilih= dataMataAnggaranSelected.kodemataanggaran + " " + dataMataAnggaranSelected.namamataanggaran
        rcn.idmataanggaran = dataMataAnggaranSelected.id 

        if(idkegiatanangkas != 0){
            item.id = idkegiatanangkas
        } else{
            item.id = item.id
        }

        let TotalAnggaran = 0;
        await useApi().get("perencanaan/get-keterangan-belanja?objectkegiatanfk=" + item.id+"&idmataanggaran="+rcn.idmataanggaran).then((response) => {
            DataKeteranganBelanja.value = response.data
            dataSourceRincianAnggaran.value = response.data
            for (let index = 0; index < DataKeteranganBelanja.value.length; index++) {
                let element = DataKeteranganBelanja.value[index];
                DataKeteranganBelanja.value[index].statCheckbox2 = false
                TotalAnggaran = TotalAnggaran + parseFloat(element.subtotal)
            }
            TotalAnggaran = parseFloat(TotalAnggaran)
            rcn.paguAnggaran = H.formatRp(0, "Rp. ")
            rcn.totalAnggaran = H.formatRp(TotalAnggaran, "Rp. ")
            rcn.sisaAnggaran = H.formatRp(TotalAnggaran - 0, "Rp. ")
            // item.targetKinerjaMasukan = H.formatRp(TotalAnggaran, "Rp. ")
            // item.targetKinerjaMasukanH = TotalAnggaran
            isLoadingBtn.value = false
            
        })

        rcn.paguAnggaran = H.formatRp(0, "Rp. ")
        rcn.totalAnggaran = H.formatRp(dataMataAnggaranSelected.total, "Rp. ")
        rcn.sisaAnggaran = H.formatRp(dataMataAnggaranSelected.total - 0, "Rp. ")
    }
}
watch(
    () => item.hargaSatuanKT, 
    (newValue, oldValue) => {
        if(item.hargaSatuanKT != undefined){
            
            item.subTotalKT = item.hargaSatuanKT*item.jmlKT
            item.subTotalKTText = H.formatRp(item.subTotalKT, "Rp. ")
        }
    }
)
watch(
    () => item.jmlKT, 
    (newValue, oldValue) => {
        if(item.jmlKT != undefined){
            
            item.subTotalKT = item.hargaSatuanKT*item.jmlKT
            item.subTotalKTText = H.formatRp(item.subTotalKT, "Rp. ")
        }
    }
)
async function TambahKeterangan(){
    // if (dataDetailMTSelected == undefined){
    //     toastr.error('Pilih dulu mata anggaran')
    //     return
    // }
    // if (datakegiatanbro.islockrba == true) {
    //     toastr.error('Sudah dilock!')
    //     return
    // }
    isLoadingBtn.value = true
    item.hargaSatuanKT = 0;
    H.formatRp(item.hargaSatuanKT, "Rp. ")
    if(idkegiatanangkas != 0){
        item.id = idkegiatanangkas
    } else{
        item.id = item.id
    }
    await useApi().get("perencanaan/get-keterangan-belanja?objectkegiatanfk=" + item.id+"&idmataanggaran="+rcn.idmataanggaran).then((response) => {
        DataKeteranganBelanja.value = response.data
        for (let i = 0; i < DataKeteranganBelanja.value.length; i++) {
            const element = DataKeteranganBelanja.value[i];
            // item.noUrutKT = element
        }
        if(DataKeteranganBelanja.value.length == 0){
            item.noUrutKT = 1
        }else{
            item.noUrutKT = DataKeteranganBelanja.value[DataKeteranganBelanja.value.length-1].nourut + 1
        }
        nourut = false
        item.keteranganKT = ''
        item.jmlKT = ''
        item.hargaSatuanKT = ''
        item.subTotalKT = ''
        item.satuanKT = ''

        item.norecKT = ''
        popupTambahKeteranganBelanja.value = true
        isLoadingBtn.value = false
    })
    
}
async function SimpanPopUpAddKeterangan(){
    isLoadingSimpan.value = true
    var idasalproduk = 0;
    var idpegawai = '';
    if(item.sumberDanaKT != undefined){
        idasalproduk = item.sumberDanaKT.id;
    }
    var norecKT = ''
    if(item.norecKT != ''){
        norecKT = item.norecKT
    }
    var pass = null
    if(item.password != '' && item.password != undefined){
        pass = item.password
    }
    if (item.sumberDanaKT == undefined){
        H.alert('error','Sumber Dana Tidak Boleh Kosong!')
        return
    }
    // if (datakegiatanbro.islockrba == true) {
    //     H.alert('error','Sudah dilock!')
    //     return
    // }
    var objSave =
    {
        norec: norecKT,
        objectkegiatanfk: item.id,
        objectmataanggaranfk: rcn.idmataanggaran,
        keteranganbelanja: item.keteranganKT,
        nourut: item.noUrutKT,
        jml: item.jmlKT,
        hargasatuan: item.hargaSatuanKT,
        subtotal: item.subTotalKT,
        satuan: item.satuanKT,
        objectasalprodukfk: idasalproduk,
        idtahap: item.tahapdetail.id,
        tahap: item.tahapdetail.tahap,
        password: pass,
    }

    const response = await useApi().post('perencanaan/save-keterangan-belanja', objSave).catch((e: any) => {
      isLoadingSimpan.value = false
    })
    
        if(idkegiatanangkas != 0){
            item.id = idkegiatanangkas
        } else{
            item.id = item.id
        }
        let TotalAnggaran = 0;
        const response1 = await useApi().get("perencanaan/get-keterangan-belanja?objectkegiatanfk=" + item.id+"&idmataanggaran="+rcn.idmataanggaran)
            dataSourceRincianAnggaran.value = response1.data
            for (let index = 0; index < response1.data.length; index++) {
                let element = response1.data[index];
                response1.data[index].statCheckbox2 = false
                TotalAnggaran = TotalAnggaran + parseFloat(element.subtotal)
            }
            TotalAnggaran = parseFloat(TotalAnggaran)
        const response2 = await useApi().get("perencanaan/get-total-anggaran-rcn?kegiatan=" + norecrcntotalanggaran)
        var total = 0
        if(response2.data.length == 0){
            rcn.totalAnggaran = H.formatRp(total, "Rp. ")
            rcn.paguAnggaran = 0
        } else{
            total = response2.data[0].total
            rcn.totalAnggaran = H.formatRp(total, "Rp. ")
            rcn.paguAnggaran = 0
        }

        // item.targetKinerjaMasukan = H.formatRp(total, "Rp. ")
        // item.targetKinerjaMasukanH = total
        LoadTotalKeterangan()
        // const response3 = await useApi().get("perencanaan/get-total-mata-anggaran?tahun="+moment(item.thnAnggaran).format('YYYY')+"&tahap="+item.tahapdetail.id+"&idpegawai="+idpegawai+"&cek=" + item.isCekAdaAnggaranSaja+"&noreckegiatan=" + idkegiatananggaran)
        //    var  DataTotalMataAnggaran = response3.data
        //     listAsalProduk = response3.asalproduk
        //     dataSourceDetailMT.value = DataTotalMataAnggaran
        popupTambahKeteranganBelanja.value = false
        isLoadingSimpan.value = false

}
async function SimpanPopUpSubSubKegiatan(){
// if (datakegiatanbro.islockrba == true) {
//     toastr.error('Sudah dilock!')
//     return
// }
isLoadingSimpan.value = true
var jmlkode = item.kodeHead + '.' + item.kode
jmlkode = jmlkode.length
var divS = 0;
if(jmlkode == 7){
    divS = 1
}else 
if(jmlkode == 12){
    divS = 2
}else if(jmlkode == 15){
    divS = 3
}else if(jmlkode > 15){
    divS = 4
}
var idtahap = null
var tahap = null
if(item.searchtahapkeg != undefined && item.searchtahapkeg != '' && item.searchtahapkeg != [] && item.searchtahapkeg != null){
    idtahap = item.searchtahapkeg.id
    tahap = item.searchtahapkeg.tahap
}

var kodesubsubkegiatan = null
var subsubkegiatan = null
if(item.subSubKegiatankode != ''){
    kodesubsubkegiatan = item.subSubKegiatankode
}

if(item.subsubkegiatanangkas != undefined){
    subsubkegiatan = item.subsubkegiatanangkas.keterangan
} else{
    subsubkegiatan = item.subSubKegiatan
}

//if()

var objSave =
    {
        id: item.id,
        kode: kodesubsubkegiatan,
        keterangan: subsubkegiatan,
        kodeexternal: null,
        namaexternal: null,
        reportdisplay: null,
        tahun: item.thnAnggaran,
        idtahap: idtahap,
        tahap: tahap,
        jenisbelanja: item.jenisbelanja.id,
        indikatormasuk: item.tolokUkurMasukan,
        indikatorkeluaran:  item.tolokUkurKeluaran,
        indikatorhasil:  item.tolokUkurHasil,
        targetmasuk:  item.targetKinerjaMasukan,
        targetkeluaran:  item.targetKinerjaKeluaran,
        targethasil:  item.targetKinerjaHasil,
        pptk:  item.savepptk,
        div: item.subsubkegiatandiv.id
    }
await useApi().post('perencanaan/save-kegiatan-anggaran', objSave).then((response) => {
    var div = ''
    if(item.searchdivkeg != undefined){
        div = item.searchdivkeg.id
    }

    var thn = ''
    if(item.tahun != undefined){
        thn = moment(item.tahun).format('YYYY')
    }

    var thp = ''
    if(item.searchtahapkeg != undefined){
        thp = item.searchtahapkeg.id
    }
    
    loadData()
    isLoadingSimpan.value = false
    popupSubSubKegiatan.value = false
}).catch((e: any) => {
    isLoadingSimpan.value = false
})
}
async function HapusRincianKas(dataItem){
    dialogConfirmHapusRincian(dataItem)
    // var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
    // if (dataItem == undefined) {
    //     alert("Data Belum Dipilih!")
    //     return;
    // };
    // //console.log(dataItem);
    // // return 
    // if (datakegiatanbro.islockrba == true) {
    //     toastr.error('Sudah dilock!')
    //     return
    // }
    
}
const dialogConfirmHapusRincian = (e: any) => {
    isLoadingBtn.value = true
    confirm.require({
    message: 'Yakin ingin menghapus data '+e.keteranganbelanja+'?',
    header: 'Konfirmasi hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
        var itemDelete = {
            "norec": e.norec
        }

         useApi().post('anggaran/delete-anggaran-kas', itemDelete).then((response) => {
            loadData()
            isLoadingBtn.value = false
       
                
            })
},
    reject: () => {
        loadData()
        isLoadingBtn.value = false

     },
  })
}
const dialogConfirmHapus = (e: any) => {
    isLoadingBtn.value = true
    confirm.require({
    message: 'Yakin ingin menghapus data '+e.keterangan+' beserta dengan Keterangan Belanjanya ?',
    header: 'Konfirmasi hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
        var objSave =
            {
                id: e.id,
                div: e.div,
            }
        // if(dataItem.islockrba == true){
        //     toastr.error('Sudah dilock!')
        //     return
        // }
        useApi().post('perencanaan/delete-kegiatan-anggaran', objSave).then( (response)=> {
            isLoadingBtn.value = false
                loadData()
            })
            },
    reject: () => {
        loadData()
        isLoadingBtn.value = false

     },
  })
}
async function deleteKegiatanT(dataItem: any) {
dialogConfirmHapus(dataItem)

}
function editDetailMT(dataItem: any) {
    nourut = true
    item.norecKT = dataItem.norec
    item.keteranganKT = dataItem.keteranganbelanja
    item.noUrutKT = dataItem.nourut
    item.jmlKT = dataItem.jml
    item.hargaSatuanKT = dataItem.hargasatuan
    item.subTotalKT = dataItem.subtotal
    item.satuanKT = dataItem.satuan
    item.sumberDanaKT = {id:dataItem.objectasalprodukfk,asalproduk:dataItem.asalproduk}
    rcn.idmataanggaran = dataItem.objectmataanggaranfk
    item.password = dataItem.password
    item.passwordbenar = undefined
    popupTambahKeteranganBelanja.value = true
}
async function SettingTahap(){
    popupTambahTahap.value = true
}
async function CariSettingTahap() {
    loadData()
}
async function SaveSettingTahap() {
    if (selectedListSub.value.length == 0) {
        H.alert('error','Data belum di pilih')
        return
    }
    console.log(selectedListSub.value);
    
    var objSave = {
        "details": selectedListSub.value,
        "tahapawal": item.tahapawal,
        "tahapakhir": item.tahapakhir,
        "tahunakhir": moment(item.tahunakhir).format('YYYY'),
    }
    console.log(objSave);
    await useApi().post('perencanaan/save-setting-tahap', objSave).then((response) => {
        popupTambahTahap.value = false
        loadData();
        selectedListSub.value = []
    })

}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: '100%';
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 580px;
    margin: 0 auto;
}
</style>