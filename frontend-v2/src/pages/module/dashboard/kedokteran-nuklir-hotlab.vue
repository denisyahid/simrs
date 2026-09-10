<template>
    <ConfirmDialog />

    <div class="column is-12" style="margin-left: -10px; display: flex; justify-content: center; align-items: center;">
        <VCard style="padding-bottom: 0px">
            <div class="column c-title pt-2 mb-5">
                <label class="title-page">Pencarian</label>
            </div>
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-1" style="padding-top:2rem">
                        <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                            Export To Excel
                        </VButton>
                    </div>
                    <div class="column is-1" style="padding-top:2rem">
                        <VButton color="info" @click="cetakForm()" outlined icon="fas fa-print">
                            Cetak Formulir
                        </VButton>
                    </div>
                    <div class="column is-1" style="padding-top:2rem">
                        <VButton color="info" @click="cetakJadwal()" outlined icon="fas fa-print">
                            Cetak Jadwal
                        </VButton>
                    </div>
                    <div class="column is-3">
                        <VField label="Tanggal" style="margin-bottom: 6px;" />
                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                    </div>

                    <div class="column is-3" style="display: none !important">
                        <VField class="is-autocomplete-select" label="Ruangan">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" @select="fetchOrder()" v-model="item.ruangan"
                                    :options="d_ruangan" placeholder="Pilih Barang" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-2">
                        <VField label="Ruangan" class="is-rounded-select is-autocomplete-select mt-0 pt-0"
                            v-slot="{ id }">
                            <VControl>
                                <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan"
                                    optionLabel="label" filter placeholder="Pilih Ruangan" :maxSelectedLabels="3"
                                    style="display:flex" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Nama Pasien">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.namapasien" v-on:keyup.enter="fetchOrder()"
                                    placeholder="Nama Pasien" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column btn-search">
                        <VButton type="button" icon="feather:search" :loading="loadSearch" @click="fetchOrder()">
                            Cari Data
                        </VButton>
                    </div>

                </div>
            </div>
        </VCard>
    </div>

    <div class="column is-12" style="margin-left: -10px; display: flex; justify-content: center; align-items: center;">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Daftar Penjadwalan Nuklir</label>
                        <label for="">List Jadwal Pasien</label>
                    </div>
                    <div class="column pr-0" style="display: none !important">
                        <VButton type="button" icon="feather:x-circle" RouterLink
                            :to="{ name: 'module-farmasi-penjualan-obat-bebas' }"
                            class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
                            Tambah Obat Bebas
                        </VButton>
                    </div>
                </div>
            </div>

            <VControl raw subcontrol style="margin-top:-10px">
                <VCheckbox v-model="item.checkAll" label="Pilih Semua" color="info" @change="checkedAll(item.checkAll)"
                    :value="item.checkAll" />
            </VControl>

            <div class="column is-12 mt-5">
                <DataTable :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                    class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                    sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps">
                            <VControl raw subcontrol>
                                <VCheckbox color="info" :value="modelCheck[slotProps.data.norec]"
                                    v-model="modelCheck[slotProps.data.norec]" square
                                    @change="checkedItems($event, slotProps.data)" />
                            </VControl>
                        </template>
                    </Column>

                    <Column :exportable="false" header="##" style="text-align: center;">
                        <template #body="slotProps1">
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle
                                outlined raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps1.data)">
                            </VIconButton>
                            <OverlayPanel ref="op">
                                <VButton type="button" class="mr-2" light circle outlined color="info" raised
                                    @click="gotoPageEditOrder(selected)">
                                    Detail/Edit
                                </VButton>
                                <VButton type="button" class="mr-2" light circle outlined color="info" raised
                                    @click="gotoPageReschedule(selected)">
                                    Reschedule
                                </VButton>
                                <VButton type="button" class="mr-2" light circle outlined color="info"
                                    v-if="selected.idruangantujuan == 389" raised @click="gotoPageTransfer(selected)">
                                    Transfer
                                </VButton>
                                <VButton type="button" class="mr-2" color="danger" circle outlined
                                    @click="gotoPagePembatalan(selected)" raised>Pembatalan
                                </VButton>
                            </OverlayPanel>
                        </template>
                    </Column>
                    <Column field="no" header="No. Registrasi" />
                    <Column field="statusorder" header="Status Order" />
                    <Column field="tglregistrasi" header="Tanggal Registrasi" />
                    <Column field="noorder" header="Nomor Order" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="tgllahir" style="text-align: center;" header="Tanggal Lahir" />
                    <Column field="umur" style="text-align: center;" header="Umur (Tahun)" />
                    <Column field="nocm" style="text-align: center;" header="Nomor CM" />
                    <Column field="jeniskelamin" style="text-align: center;" header="Jenis Kelamin" />
                    <Column field="" style="text-align: center;" header="Diagnosa" />
                    <Column field="bb" style="text-align: center;" header="Berat Badan (kg)" />
                    <Column field="tb" style="text-align: center;" header="Tinggi Badan (cm)" />
                    <Column field="kelompokpasien" style="text-align: center;" header="Kelompok Pasien" />
                    <Column field="ruangantujuan" style="text-align: center;" header="Ruang Tujuan" />
                    <Column field="tindakan" style="text-align: center;" header="Tindakan Medis" />
                    <Column field="catatanklinis" style="text-align: center;" header="Catatan Klinis" />
                    <Column field="terapiradioaktif" style="text-align: center;" header="Terapi Radioaktif" />
                    <Column field="catatanterapiradioaktif" style="text-align: center;"
                        header="Catatan Terapi Radioaktif" />
                    <Column field="radionuklida" style="text-align: center;" header="Radionuklida" />
                    <Column field="farmaka" style="text-align: center;" header="Farmaka" />
                    <Column field="catatanfarmaka" style="text-align: center;" header="Catatan Farmaka Lain" />
                    <Column field="terapiiodium" style="text-align: center;" header="Dosis Terapi Iodium" />
                    <Column field="terapiradiofarmaka" style="text-align: center;" header="Dosis Terapi Radiofarmaka" />
                    <Column field="nobatchradionuklida" style="text-align: center;" header="No. Batch Radionuklida" />
                    <Column field="nobatchradiofarmaka" style="text-align: center;" header="No. Batch Radiofarmaka" />
                    <Column field="dosisradiofarmasistext" style="text-align: center;" header="Dosis Radiofarmaka" />
                    <Column field="jampermintaan" style="text-align: center;" header="Jam Permintaan" />
                    <Column field="dosisfullsyringetext" style="text-align: center;"
                        header="Dosis Full Syringe (Injeksi)" />
                    <Column field="jamfullsyringe" style="text-align: center;" header="Jam Full Syringe" />
                    <Column field="dosisemptysyringetext" style="text-align: center;"
                        header="Dosis Empty Syringe (Injeksi)" />
                    <Column field="jamemptysyringe" style="text-align: center;" header="Jam Empty Syringe" />
                    <Column field="rutelokasisuntik" style="text-align: center;" header="Rute Pemberian Suntik" />
                    <Column field="jaminjeksi" style="text-align: center;" header="Waktu Injeksi" />
                    <Column field="jenisterapi" style="text-align: center;" header="Jenis Terapi" />
                    <Column field="jenisakuisisi" style="text-align: center;" header="Jenis Akuisisi" />
                    <Column field="jamakuisisi" style="text-align: center;" header="Waktu Akuisisi" />
                    <Column field="treatment" style="text-align: center;" header="Jenis Perawatan" />
                    <Column field="paparanradiasi" style="text-align: center;" header="Paparan Radiasi" />
                    <Column field="treatment_ppr" style="text-align: center;" header="Jenis Perawatan PPR" />
                    <Column field="paparanradiasi_ppr" style="text-align: center;" header="Paparan Radiasi PPR 1" />
                    <Column field="paparanradiasi_ppr2" style="text-align: center;" header="Paparan Radiasi PPR 2" />
                    <Column field="paparanradiasi_ppr3" style="text-align: center;" header="Paparan Radiasi PPR 3" />
                    <Column field="paparanradiasi_ppr4" style="text-align: center;" header="Paparan Radiasi PPR 4" />
                    <Column field="paparanradiasi_ppr5" style="text-align: center;" header="Paparan Radiasi PPR 5" />
                    <Column field="paparanradiasi_ppr6" style="text-align: center;" header="Paparan Radiasi PPR 6" />
                    <Column field="paparanradiasi_ppr7" style="text-align: center;" header="Paparan Radiasi PPR 7" />
                    <Column field="dpjp" style="text-align: center;" header="Dokter Penanggung Jawab (DPJP)" />
                </DataTable>
            </div>
        </VCard>
    </div>



    <div class="column is-12" style="margin-left: -10px; display: flex; justify-content: center; align-items: center;">
        <VCard>
            <div class="column is-12" style="margin-bottom: 50px;">
                <div class="columns">
                    <div class="column is-3">
                        <VField label="Tanggal" style="margin-bottom: 6px;" />
                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                    </div>
                    <div class="column is-3">
                        <VField label="Nama Pasien">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.namapasienhis" v-on:keyup.enter="fetchOrderHis()"
                                    placeholder="Nama Pasien" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column btn-search">
                        <VButton type="button" icon="feather:search" :loading="loadSearchHis" @click="fetchOrderHis()">
                            Cari Data
                        </VButton>
                    </div>

                </div>
            </div>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Riwayat Reschedule dan Pembatalan</label>
                    </div>
                </div>
            </div>

            <div class="column is-12 mt-5">
                <DataTable :value="dataSourceHistory" :rows="5" :rowsPerPageOptions="[5, 10, 15]"
                    :loading="loadSearchHis" class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px"
                    selectionMode="single" sortMode="multiple" showGridlines v-model:expanded-rows="expandedRows"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <Column field="no" header="No" />
                    <Column field="noregistrasi" header="No Registrasi" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="nocm" header="No RM" />
                    <Column field="tglinput" header="Tgl Input" />
                    <Column field="tglawal" header="Tgl Awal" />
                    <Column field="tglakhir" header="Tgl Reschedule" />
                    <Column field="namalengkap" header="Pegawai" />
                    <Column field="jenis" header="Jenis" />
                    <Column field="alasan" header="Alasan" />
                </DataTable>
            </div>
        </VCard>
    </div>

    <VModal :open="modalDetail" title="Detail Resep" size="big" actions="right" @close="modalDetail = false">
        <template #content>
            <div class="column is-12">
                <DataTable :value="detailResep" :rows="10" :loading="loadSearch" class="p-datatable-sm"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines>
                    <Column field="no" header="No" />
                    <Column field="tglregistrasi" header="Tgl Registrasi" />
                    <Column field="noregistrasi" header="No Registrasi" style="text-align: center;" />
                    <Column field="noorder" header="No Order" />
                    <Column field="namapasien" header="Nama Pasien" />
                    <Column field="noidentitas" style="text-align: center;" header="No Identitas" />
                    <Column field="nobpjs" style="text-align: center;" header="No BPJS" />
                    <Column field="tgllahir" style="text-align: center;" header="Tgl Lahir" />
                    <Column field="nocm" style="text-align: center;" header="No CM" />
                    <Column field="jeniskelamin" style="text-align: center;" header="Jenis Kelamin" />
                    <Column field="kelompokpasien" style="text-align: center;" header="Cara Bayar" />
                    <Column field="ruangantujuan" style="text-align: center;" header="Tujuan" />
                    <Column field="tindakan" style="text-align: center;" header="Tindakan" />
                    <Column field="catatanklinis" style="text-align: center;" header="Tambahan Tindakan" />
                    <Column field="terapiradioaktif" style="text-align: center;" header="Terapi Radioaktif" />
                    <Column field="catatanterapiradioaktif" style="text-align: center;"
                        header="Tambahan Terapi Radioaktif" />
                    <Column field="terapiiodium" style="text-align: center;" header="Terapi Iodium" />
                    <Column field="terapiradiofarmaka" style="text-align: center;" header="Terapi Radiofarmaka" />

                </DataTable>
            </div>
            <div class="column is-12">
                <div class="content">
                    <div class="is-divider" data-content="Total Keseluruhan" />
                </div>
            </div>

            <div class="column is-12 p-0">
                <div class="column is-3 p-0" style="margin-left: auto;">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status" color="danger">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">TOTAL</span>
                        </div>
                        <small class="text-bold-custom h-100">{{
                            H.formatRp(item.totalTagihan, 'Rp.')
                            }}</small>
                    </VCardCustom>
                </div>
            </div>
        </template>
    </VModal>


    <VModal :open="modalDetailReschedule" title="Reschedule" :noclose="true" size="small" actions="right"
        @close="modalDetailReschedule = false">
        <template #content>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <h1 class="mb-3 emr">Tanggal Awal</h1>
                    </div>
                    <div class="column is-10">
                        <VDatePicker v-model="item.tglAwal" mode="dateTime" style="width: 100%;">
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" disabled />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-2">
                        <h1 class="mb-3 emr">Tanggal Reschedule</h1>
                    </div>
                    <div class="column is-10">
                        <VDatePicker v-model="item.tglReschedule" mode="dateTime" style="width: 100%;">
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-12">
                        <h1 class="mb-3 emr">Alasan Reschedule</h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="item.alasanreschedule" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:save" :loading="isLoadingSave" @click="saveReschedule()" color="primary" raised>
                Simpan
            </VButton>
        </template>
    </VModal>

    <VModal :open="modalDetailPembatalan" title="Pembatalan" :noclose="true" size="small" actions="right"
        @close="modalDetailPembatalan = false">
        <template #content>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 class="mb-3 emr">Alasan Pembatalan</h1>
                        <VField>
                            <VControl>
                                <VTextarea v-model="item.alasanpembatalan" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:save" :loading="isLoadingSave" @click="savePembatalan()" color="primary" raised>
                Simpan
            </VButton>
        </template>
    </VModal>

    <VModal :open="modalDetailOrder" title="Detail Order" :noclose="true" size="large" actions="right"
        @close="modalDetailOrder = false">
        <template #content>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-2" style="display: none !important">
                        <h1 class="mb-3 emr">Tanggal</h1>
                    </div>
                    <div class="column is-10" style="display: none !important">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.tglregistrasi" placeholder="NRM" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1 class="mb-3 emr">Nama Pasien</h1>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.namapasien" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1 class="mb-3 emr">No Registrasi</h1>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.noregistrasi" placeholder="Tanggal/Jam"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1 class="mb-3 emr">Berat Badan(BB)</h1>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.bb" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1 class="mb-3 emr">Tinggi Badan(TB)</h1>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.tb" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1 class="mb-3 emr">Jenis Radionuklida</h1>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.radionuklida" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1 class="mb-3 emr">Jenis Farmaka</h1>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.farmaka" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1 class="mb-3 emr">Dosis/Aktifitas Radiofarmaka</h1>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.terapiradiofarmaka" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-1">
                        <h1 class="mb-3 emr">No RM</h1>
                    </div>
                    <div class="column is-5">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="item.norm" placeholder="Tanggal/Jam" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>


                    <Fieldset legend="Data Radiofarmasis" :toggleable="true" style="margin-top: 75px;">
                        <div class="columns is-multiline">
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">No Batch Radionuklida</h1>
                            </div>
                            <div class="column is-9">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.nbradionuklida" placeholder=""
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3" style="text-align: center;" v-if="selected.idruangantujuan != 389">
                                <h1 style="font-weight: normal;" class="">No Batch Radiofarmaka</h1>
                            </div>
                            <div class="column is-9" v-if="selected.idruangantujuan != 389">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.nbradiofarmaka" placeholder=""
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Dosis Yang Disiapkan</h1>
                            </div>
                            <div class="column is-9">
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder=""
                                            v-model="item.dosisdisiapkan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mCi</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>



                    <Fieldset legend="Data Perawat" :toggleable="true">
                        <div class="columns is-multiline">
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Dosis Full Syringe Injeksi</h1>
                            </div>
                            <div class="column is-9">
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="" v-model="item.dosissyringe" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mCi</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Jam Full Syringe</h1>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VDatePicker v-model="item.jamsyringe" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:clock">
                                                    <VInput class="input form-timepicker is-rounded" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-7"></div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Dosis Empty Syringe</h1>
                            </div>
                            <div class="column is-9">
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="" v-model="item.dosisempty" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mCi</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Jam Empty Syringe</h1>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VDatePicker v-model="item.jamempty" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:clock">
                                                    <VInput class="input form-timepicker is-rounded" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-7"></div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Rute Pemberian Lokasi Suntik</h1>
                            </div>
                            <div class="column is-9">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.rutesuntik" placeholder=""
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Waktu Injeksi</h1>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VDatePicker v-model="item.waktuinjeksi" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:clock">
                                                    <VInput class="input form-timepicker is-rounded" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-7"></div>
                        </div>

                    </Fieldset>

                    <Fieldset legend="Data Radiografer" :toggleable="true">
                        <div class="columns is-multiline">
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Jam Permintaan</h1>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VDatePicker v-model="item.jampermintaan" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:clock">
                                                    <VInput class="input form-timepicker is-rounded" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-7"></div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Jenis Pemeriksaan</h1>
                            </div>
                            <div class="column is-9">
                                <VControl>
                                    <Multiselect v-model="item.jenispemeriksaan" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_JenisPemeriksaan"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Waktu Akuisisi</h1>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VDatePicker v-model="item.waktuakuisisi" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:clock">
                                                    <VInput class="input form-timepicker is-rounded" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-7"></div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Jenis Akuisisi</h1>
                            </div>
                            <div class="column is-9">
                                <VControl>
                                    <Multiselect v-model="item.jenisakuisisi" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_JenisAkuisisi" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </div>
                            <!-- <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Treatment</h1>
                            </div>
                            <div class="column is-9">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.treatment" placeholder=""
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Paparan Radiasi</h1>
                            </div>
                            <div class="column is-9">
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder=""
                                            v-model="item.paparanradiasi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>μSv/jam</VButton>
                                    </VControl>
                                </VField>
                            </div> -->
                        </div>
                    </Fieldset>

                    <Fieldset legend="PPR (Petugas Proteksi Radiasi)" :toggleable="true">
                        <div class="columns is-multiline">
                            <div class="column is-3" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Treatment</h1>
                            </div>
                            <div class="column is-9">
                                <VField>
                                    <VControl>
                                        <VInput type="text" v-model="item.treatment_ppr" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12 pb-0 pt-0 is-flex buttons" style="justify-content: right;">
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                    color="info" v-tooltip.bubble="'Tambah Paparan Radiasi'">
                                </VIconButton>
                                <VIconButton class="mt-1" v-if="tambahpr > 0" type="button" raised circle
                                    icon="feather:trash" @click="removeItem()" color="danger">
                                </VIconButton>
                            </div>
                            <div class="column is-3 pt-0" style="text-align: center;">
                                <h1 style="font-weight: normal;" class="">Paparan Radiasi 1</h1>
                            </div>
                            <div class="column is-9 pt-0">
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="item.paparanradiasi_ppr" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>μSv/jam</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3" style="text-align: center;"
                                v-if="tambahpr >= 1 || item.paparanradiasi_ppr2 != null">
                                <h1 style="font-weight: normal;" class="">Paparan Radiasi 2</h1>
                            </div>
                            <div class="column is-9" v-if="tambahpr >= 1 || item.paparanradiasi_ppr2 != null">
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="item.paparanradiasi_ppr2" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>μSv/jam</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3" style="text-align: center;"
                                v-if="tambahpr >= 2 || item.paparanradiasi_ppr3 != null">
                                <h1 style="font-weight: normal;" class="">Paparan Radiasi 3</h1>
                            </div>
                            <div class="column is-9" v-if="tambahpr >= 2 || item.paparanradiasi_ppr3 != null">
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="item.paparanradiasi_ppr3" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>μSv/jam</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>
                </div>
            </div>
        </template>

        <template #action>
            <VButton icon="feather:save" :loading="isLoadingSave" @click="save()" color="primary" raised>Simpan
            </VButton>
        </template>
    </VModal>
</template>

<script setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import OverlayPanel from 'primevue/overlaypanel';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Fieldset from 'primevue/fieldset'
import * as qzService from '/@src/utils/qzTrayService'
import * as XLSX from "xlsx";
import MultiSelect from 'primevue/multiselect';


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Penjadwalan - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
    filterTglHis: reactive({
        starthis: new Date(),
        endhis: new Date(),
    }),
    jampermintaan: new Date().setHours(0, 0, 0, 0),
    jamsyringe: new Date().setHours(0, 0, 0, 0),
    jamempty: new Date().setHours(0, 0, 0, 0),
    waktuinjeksi: new Date().setHours(0, 0, 0, 0),
    waktuakuisisi: new Date().setHours(0, 0, 0, 0),
    tglReschedule: new Date(),
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const itemChecked: any = ref([])
const dataSourceHistory: any = ref([])
const detailResep: any = ref([])
const remakeData: any = ref([])
const modelCheck: any = ref([])
const d_Ruangan: any = ref([])
const op = ref();
const selected: any = ref({})
const selectedOrder: any = ref({})
const modalDetail: any = ref(false)
const modalRetur: any = ref(false)
const isLoadingSave: any = ref(false)
const modalDetailOrder: any = ref(false)
const modalDetailReschedule: any = ref(false)
const modalDetailPembatalan: any = ref(false)
let sourceRuangan: any = ref([])
const expandedRows = ref();
let d_ruangan: any = ref([])
let loadSearch: any = ref(false)
let loadSearchHis: any = ref(false)
const d_JenisPemeriksaan: any = ref([{ value: 1, label: 'Bone Scan' }, { value: 2, label: 'Brain Scan' }, { value: 3, label: 'Cardiac PYP' }, { value: 4, label: 'Ethambutol' }, { value: 5, label: 'Mibi Oncology' }, { value: 6, label: 'Muga Scan' }, { value: 7, label: 'One Day AC' }, { value: 8, label: 'Orbita' }, { value: 9, label: 'Renal GFR' }, { value: 10, label: 'Renogram' }, { value: 11, label: 'Three Phase Bone' }, { value: 12, label: 'Thyroid Uptake' }, { value: 13, label: 'Thyroid Scan' }, { value: 14, label: 'VQ Perfusi' }, { value: 15, label: 'WB Diagnostic' }, { value: 16, label: 'WB Post Therapy' }])
const d_JenisAkuisisi: any = ref([{ value: 1, label: 'WBS Static' }, { value: 2, label: 'SPEC-CT 1 Bed' }, { value: 3, label: 'SPEC-CT 2 Bed' }, { value: 4, label: 'SPEC-CT 3 Bed' }, { value: 5, label: 'SPEC-CT Cardiac' }, { value: 6, label: 'Three Phase' }, { value: 7, label: 'Dinamik' }, { value: 8, label: 'Planar' }])
const user = useUserSession().getUser();

const fetchdDropdown = async () => {
    const response = await useApi().get(`/dashboard/dropdown-rawat-jalan`)
    d_Ruangan.value = response.ruanganJadwalNuklir.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
}

const tambahpr: any = ref(0)
function addNewItem() {
    tambahpr.value = parseFloat(tambahpr.value) + 1
}
function removeItem() {
    tambahpr.value = parseFloat(tambahpr.value) - 1
}

const fetchOrder = async () => {

    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let idRuangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
    let namaPasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
    let ruanganid = ''
    if (sourceRuangan.value != undefined) {
        let itemsRuang = []
        sourceRuangan.value.forEach((element: any) => {
            itemsRuang = [...new Set([...itemsRuang, element.value])]
        });
        ruanganid = `&ruanganfk=${itemsRuang}`
    }
    loadSearch.value = true
    await useApi().get(`/farmasi/get-daftar-floor-stock?${tglAwal}${tglAkhir}${ruanganid}${namaPasien}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            // expandedRows.value = element.details.forEach((data: any, i: any) => {
            //     data.no = i + 1
            //     data.tglkadaluarsa = data.tglkadaluarsa ? H.formatDate(data.tglkadaluarsa, 'DD-MMM-YYYY') : ''

            // })
            element.no = i + 1
            element.tglregistrasi = moment(element.tglregistrasi).format('DD-MM-YYYY HH:mm:ss')
            // element.norm = element.nostruk_intern ? element.nostruk_intern : '-'
            // element.namapasien = element.namapasien_klien
            // element.tglstruk = moment(element.tglstruk).format('DD-MM-YYYY')

        });
        dataSource.value = response.data
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })
}

const fetchOrderHis = async () => {

    let tglAwalhis = 'tglAwal=' + H.formatDate(item.value.filterTglHis.starthis, 'YYYY-MM-DD')
    let tglAkhirhis = '&tglAkhir=' + H.formatDate(item.value.filterTglHis.endhis, 'YYYY-MM-DD')
    let namaPasienhis = item.value.namapasienhis ? `&namapasien=${item.value.namapasienhis}` : ''
    loadSearch.value = true
    await useApi().get(`/farmasi/get-daftar-floor-stock-history?${tglAwalhis}${tglAkhirhis}${namaPasienhis}`).then((responsex: any) => {
        responsex.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataSourceHistory.value = responsex.data
        console.log(dataSourceHistory.value)
        loadSearch.value = false
    }).catch((err: any) => {
        loadSearch.value = false
    })
}

const exportExcel = () => {
    console.log(dataSource.value)

    remakeData.value = dataSource.value.map((e: any) => {
        return {
            No: e.no, TglRegistrasi: e.tglregistrasi, NoOrder: e.noorder, NamaPasien: e.namapasien,
            TglLahir: e.tgllahir, Umur: e.umur, NoCM: e.nocm, JenisKelamin: e.jeniskelamin, Diagnosa: '', BB: e.bb, TB: e.tb, CaraBayar: e.kelompokpasien,
            Tujuan: e.ruangantujuan, TindakanInVivo: e.tindakan, TambahanTindakanInVivo: e.catatanklinis, Terapi: e.terapiradioaktif, TambahanTerapi: e.catatanterapiradioaktif,
            Radionuklida: e.radionuklida, Farmaka: e.farmaka, FarmakaLainLain: e.catatanfarmaka, DosisTerapi: e.terapiiodium, DosisDiagnostikInVivo: e.terapiradiofarmaka,
            NoBatchRadionuklida: e.nobatchradionuklida, NoBatchRadiofarmaka: e.nobatchradiofarmaka, DosisYangDisiapkan: e.dosisradiofarmasistext, JamPermintaan: e.jampermintaan,
            DosisFullSyringeInjeksi: e.dosisfullsyringetext, JamFullSyringe: e.jamfullsyringe, DosisEmptySyringeInjeksi: e.dosisemptysyringetext, JamEmptySyringe: e.jamemptysyringe,
            RutePemberianLokasiSuntik: e.rutelokasisuntik, WaktuInjeksi: e.jaminjeksi, JenisPemeriksaan: e.jenisterapi, JenisAkuisisi: e.jenisakuisisi, WaktuAkuisisi: e.jamakuisisi,
            Treatment: e.treatment, PaparanRadiasi: e.paparanradiasi, DPJP: e.dpjp
        }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const cetakForm = () => {
    if (itemChecked.value.length == 0) {
        H.alert('error', 'Ceklis data yang mau dicetak')
        return
    }

    let norec = itemChecked.value.map((a: any) => a.norec).join("|");
    H.printBlade('kasir/billing/report/cetak-form-nuklir?norec=' + norec);
}

const cetakJadwal = () => {
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let idRuangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
    let namaPasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''

    H.printBlade('kasir/billing/report/cetak-form-jadwal?' + tglAwal + tglAkhir + idRuangan + namaPasien);
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    const _url = window.URL.createObjectURL(data)
    window.open(_url, EXCEL_EXTENSION).focus();
    // window.open(_url,EXCEL_EXTENSION).focus()
    // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

const saveReschedule = async () => {

    let parameter = {
        'so_norec': selectedOrder.value.norec,
        'norec_pd': selectedOrder.value.pd_norec,
        'nocmfk': selectedOrder.value.nocmfk,
        'pegawaifk': user.pegawai.id,
        'tglregistrasi': moment(item.value.tglregistrasi).format('DD-MM-YYYY HH:mm:ss'),
        'tglawal': item.value.tglAwal ? moment(item.value.tglAwal).format('YYYY-MM-DD HH:mm:ss') : null,
        'tglakhir': item.value.tglReschedule ? moment(item.value.tglReschedule).format('YYYY-MM-DD HH:mm:ss') : null,
        'alasan': item.value.alasanreschedule ? item.value.alasanreschedule : null,
        'jenis': 'Reschedule'
    }
    isLoadingSave.value = true
    await useApi().post('/dashboard/radiologi/save-order-pelayanan-edit-reschedule', { 'parameter': parameter }).then((response: any) => {
        isLoadingSave.value = false
        fetchOrder()
        fetchOrderHis()
    }).catch((e: any) => {
        useToaster().error('Something Went Wrong')
    })

}

const savePembatalan = async () => {

    let parameter = {
        'so_norec': selectedOrder.value.norec,
        'norec_pd': selectedOrder.value.pd_norec,
        'nocmfk': selectedOrder.value.nocmfk,
        'pegawaifk': user.pegawai.id,
        'tglregistrasi': moment(item.value.tglregistrasi).format('DD-MM-YYYY HH:mm:ss'),
        'tglawal': null,
        'tglakhir': null,
        'alasan': item.value.alasanpembatalan ? item.value.alasanpembatalan : null,
        'jenis': 'Pembatalan'
    }
    isLoadingSave.value = true
    await useApi().post('/dashboard/radiologi/save-order-pelayanan-edit-reschedule', { 'parameter': parameter }).then((response: any) => {
        isLoadingSave.value = false
        fetchOrder()
        fetchOrderHis()
    }).catch((e: any) => {
        useToaster().error('Something Went Wrong')
    })

}

const save = async () => {

    let parameter = {
        'so_norec': selectedOrder.value.norec,
        'norec_pd': selectedOrder.value.pd_norec,
        'nocmfk': selectedOrder.value.nocmfk,
        'tglregistrasi': item.value.tglregistrasi ? moment(item.value.tglregistrasi).format('DD-MM-YYYY HH:mm:ss') : null,
        'noregistrasi': item.value.noregistrasi,
        'nobatchradionuklida': item.value.nbradionuklida ? item.value.nbradionuklida : null,
        'nobatchradiofarmaka': item.value.nbradiofarmaka ? item.value.nbradiofarmaka : null,
        'dosisradiofarmasis': item.value.dosisdisiapkan ? item.value.dosisdisiapkan : null,
        'jampermintaan': item.value.jampermintaan ? moment(item.value.jampermintaan).format('YYYY-MM-DD HH:mm:ss') : null,
        'dosisfullsyringe': item.value.dosissyringe ? item.value.dosissyringe : null,
        'jamfullsyringe': item.value.jamsyringe ? moment(item.value.jamsyringe).format('YYYY-MM-DD HH:mm:ss') : null,
        'dosisemptysyringe': item.value.dosisempty ? item.value.dosisempty : null,
        'jamemptysyringe': item.value.jamempty ? moment(item.value.jamempty).format('YYYY-MM-DD HH:mm:ss') : null,
        'rutelokasisuntik': item.value.rutesuntik ? item.value.rutesuntik : null,
        'jaminjeksi': item.value.waktuinjeksi ? moment(item.value.waktuinjeksi).format('YYYY-MM-DD HH:mm:ss') : null,
        'pemeriksaanradiograferfk': item.value.jenispemeriksaan ? item.value.jenispemeriksaan : null,
        'jamakuisisi': item.value.waktuakuisisi ? moment(item.value.waktuakuisisi).format('YYYY-MM-DD HH:mm:ss') : null,
        'jenisakuisisifk': item.value.jenisakuisisi ? item.value.jenisakuisisi : null,
        'treatment': item.value.treatment ? item.value.treatment : null,
        'paparanradiasi': item.value.paparanradiasi ? item.value.paparanradiasi : null,
        'treatment_ppr': item.value.treatment_ppr ? item.value.treatment_ppr : null,
        'terapiradiofarmaka': item.value.terapiradiofarmaka ? item.value.terapiradiofarmaka : null,
        'paparanradiasi_ppr': item.value.paparanradiasi_ppr ? item.value.paparanradiasi_ppr : null,
        'paparanradiasi_ppr2': item.value.paparanradiasi_ppr2 ? item.value.paparanradiasi_ppr2 : null,
        'paparanradiasi_ppr3': item.value.paparanradiasi_ppr3 ? item.value.paparanradiasi_ppr3 : null,
        'paparanradiasi_ppr4': item.value.paparanradiasi_ppr4 ? item.value.paparanradiasi_ppr4 : null,
        'paparanradiasi_ppr5': item.value.paparanradiasi_ppr5 ? item.value.paparanradiasi_ppr5 : null,
        'paparanradiasi_ppr6': item.value.paparanradiasi_ppr6 ? item.value.paparanradiasi_ppr6 : null,
        'paparanradiasi_ppr7': item.value.paparanradiasi_ppr7 ? item.value.paparanradiasi_ppr7 : null,
    }
    isLoadingSave.value = true
    await useApi().post('/dashboard/radiologi/save-order-pelayanan-edit', { 'parameter': parameter }).then((response: any) => {
        isLoadingSave.value = false
        modalDetailOrder.value = false
        fetchOrder()
        fetchOrderHis()
    }).catch((e: any) => {
        useToaster().error('Something Went Wrong')
    })

}

const gotoPageReschedule = (e: any) => {
    modalDetailReschedule.value = true
    selectedOrder.value = e
    item.value.tglAwal = e.tglregistrasi
}

const gotoPageTransfer = (e: any) => {
    selectedOrder.value = e
    router.push({
        name: 'module-emr-profile-pasien-page-emr-kedokteran-nuklir-invivo',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.pd_norec,
            norec_apd: e.norec_apd,
        }
    })
}

const gotoPagePembatalan = (e: any) => {
    modalDetailPembatalan.value = true
    selectedOrder.value = e
}

function checkedAll(e: any) {
    modelCheck.value = []
    itemChecked.value = []
    if (e) {
        dataSource.value.forEach((e: any) => {
            itemChecked.value.push(e)
            modelCheck.value[e.norec] = true
        });
    }
}

const checkedItems = (event: any, e: any) => {
    itemChecked.value = []
    let objectK = Object.keys(modelCheck.value)
    for (let x = 0; x < objectK.length; x++) {
        const element = objectK[x];
        console.log(modelCheck.value[element])
        if (modelCheck.value[element] == true) {
            dataSource.value.forEach((elements: any) => {
                if (elements.norec == element) {
                    itemChecked.value.push(elements)
                }
            })
            // for (let z = 0; z < itemChecked.value.length; z++) {
            //     const element1 = itemChecked.value[z];
            //     if(element1.norec != e.norec){

            //     }
            // }
        } else if (modelCheck.value[element] == false) {
            for (let y = 0; y < itemChecked.value.length; y++) {
                const element2 = itemChecked.value[y];
                if (element2.norec == element) {
                    itemChecked.value.splice(y, 1)
                }
            }
        }
    }
    // if(item.value.check == true){
    //
    // }

    console.log(itemChecked.value)
}

const gotoPageEditOrder = (e: any) => {
    console.log(e)
    modalDetailOrder.value = true
    selectedOrder.value = e
    if (e.paparan == null && e.idruangantujuan != 389) {
        item.value.paparanradiasi = '<70'
    } else if (e.paparan != null) {
        item.value.paparanradiasi = e.paparan
    } else if (e.paparan == null && e.idruangantujuan == 389) {
        item.value.paparanradiasi = null
    }
    item.value.tglregistrasi = e.tglregistrasi
    item.value.noregistrasi = e.noregistrasi
    item.value.norm = e.nocm
    item.value.namapasien = e.namapasien
    item.value.bb = e.bb
    item.value.tb = e.tb
    item.value.radionuklida = e.radionuklida
    item.value.farmaka = e.farmaka
    item.value.terapiradiofarmaka = e.terapiradiofarmaka
    item.value.nbradionuklida = e.nobatchradionuklida
    item.value.nbradiofarmaka = e.nobatchradiofarmaka
    item.value.dosisdisiapkan = e.dosisradiofarmasis
    item.value.dosissyringe = e.dosisfullsyringe
    item.value.dosisempty = e.dosisemptysyringe
    item.value.rutesuntik = e.rutelokasisuntik
    item.value.jenispemeriksaan = e.pemeriksaanradiograferfk
    item.value.jenisakuisisi = e.jenisakuisisifk
    item.value.treatment = e.treatment
    item.value.treatment_ppr = e.treatment_ppr
    item.value.paparanradiasi_ppr = e.paparanradiasi_ppr
    item.value.paparanradiasi_ppr2 = e.paparanradiasi_ppr2
    item.value.paparanradiasi_ppr3 = e.paparanradiasi_ppr3
    item.value.paparanradiasi_ppr4 = e.paparanradiasi_ppr4
    item.value.paparanradiasi_ppr5 = e.paparanradiasi_ppr5
    item.value.paparanradiasi_ppr6 = e.paparanradiasi_ppr6
    item.value.paparanradiasi_ppr7 = e.paparanradiasi_ppr7

    if (e.paparanradiasi_ppr != null && e.paparanradiasi_ppr7 == null) {
        tambahpr.value = 0
    } else if (e.paparanradiasi_ppr2 != null && e.paparanradiasi_ppr7 == null) {
        tambahpr.value = 1
    } else if (e.paparanradiasi_ppr3 != null && e.paparanradiasi_ppr7 == null) {
        tambahpr.value = 2
    } else if (e.paparanradiasi_ppr4 != null && e.paparanradiasi_ppr7 == null) {
        tambahpr.value = 3
    } else if (e.paparanradiasi_ppr5 != null && e.paparanradiasi_ppr7 == null) {
        tambahpr.value = 4
    } else if (e.paparanradiasi_ppr6 != null && e.paparanradiasi_ppr7 == null) {
        tambahpr.value = 5
    } else if (e.paparanradiasi_ppr7 != null) {
        tambahpr.value = 6
    } else {
        tambahpr.value = 0
    }

    if (e.jampermintaan != null) {
        item.value.jampermintaan = e.jampermintaan
    }
    if (e.jamfullsyringe != null) {
        item.value.jamsyringe = e.jamfullsyringe
    }
    if (e.jamemptysyringe != null) {
        item.value.jamempty = e.jamemptysyringe
    }
    if (e.jaminjeksi != null) {
        item.value.waktuinjeksi = e.jaminjeksi
    }
    if (e.jamakuisisi != null) {
        item.value.waktuakuisisi = new Date(e.jamakuisisi)
    }

}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda yakin menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deletePenerimaan(e)
        },
        reject: () => { },
    })
}

const deletePenerimaan = async (e: any) => {

    await useApi().post('/farmasi/delete-resep-bebas', { 'norec_sp': e.norec }).then((response) => {
        fetchOrder()
    }).catch((err: any) => {

    })
}

const gotoPageEdit = (e: any) => {
    dataSource.value.forEach((element: any) => {
        if (element.no == e.no) {
            if (element.nosbm) {
                H.alert('error', 'Tagihan Sudah Lunas Tidak Bisa Dirubah')
            } else {
                router.push({
                    name: 'module-farmasi-penjualan-obat-bebas',
                    query: {
                        norec: e.norec,
                    },
                })
            }
        }
    });

}

const gotoPageRetur = (e: any) => {
    console.log(e.no)
    dataSource.value.forEach((element: any) => {
        if (element.no == e.no) {
            if (element.nosbm) {
                H.alert('error', 'Tagihan Sudah Lunas Tidak Bisa Dirubah')
            } else {
                router.push({
                    name: 'module-farmasi-retur-obat-bebas',
                    query: {
                        norec: e.norec,
                    },
                })
            }
        }
    });

}

const getListCombo = async () => {
    const response = await useApi().get(`/farmasi/input-resep-cbo-ruang`)
    d_ruangan.value = response.ruanganFarmasi.map((e: any) => { return { label: e.namaruangan, value: e.id } })
}

const showDetail = (e: any) => {

    let total = 0
    e.details.forEach((element: any, i: any) => {
        element.no = i + 1
        element.tglKadaluarsa = moment(element.tglkadaluarsa).format('DD-MM-YYYY')
        total = parseFloat(element.total) + total
    });
    item.value.totalTagihan = total
    detailResep.value = e.details
    modalDetail.value = true
}

const toggle = (event: any, e: any) => {
    console.log(event)
    op.value.toggle(event);
    selected.value = e
}


const cetakLabel = (e: any) => {
    //   console.log(e)
    qzService.printData(`report/farmasi/cetak-apotik-label-kecil-bebas?pdf=true&norec=${e.norec}`, 'LABEL RESEP', 1);
    //   H.printBlade(`report/farmasi/cetak-apotik-label-kecil-bebas?pdf=true&norec=${e.norec}`)
}
const cetakResep = (e: any) => {
    qzService.printData(`report/farmasi/resep-obat-bebas?pdf=true&norec=${e.norec}`, 'RESEP', 1)
    //   H.printBlade(`report/farmasi/resep-obat-bebas?pdf=true&norec=${e.norec}`)
}

getListCombo()
fetchOrder()
fetchOrderHis()
fetchdDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
    margin-top: 14px;
}
</style>
