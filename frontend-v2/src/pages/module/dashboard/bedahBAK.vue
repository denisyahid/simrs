<template>
    <div class="business-dashboard hr-dashboard">
        <div class="columns">
            <div class="column is-8">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="illustration-header-2 large-screen">
                            <div class="header-image">
                                <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                                    style="max-width:75%; margin-left: 2rem; margin-top: 1rem;" />
                            </div>
                            <div class="header-meta">
                                <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Dashboard
                                    Bedah
                                </h3>
                                <p>
                                    Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                                </p>
                                <VControl>
                                    <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan"
                                        class="f-text" placeholder="Filter ruangan" :searchable="true"
                                        autocomplete="off" @select="changeRuang(item.filterRuangan)" />
                                </VControl>
                                <div class="columns is-multiline">
                                    <div class="column is-6" style="margin-top: -5px;">
                                        <VField grouped>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="item.isTglOrder" label="Tgl Order" color="info" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6" style="margin-top: -5px;">
                                        <VField grouped>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="item.isTglOperasi" label="Tgl Operasi"
                                                    color="info" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12" style="margin-top: -50px;">
                                        <VTag @click="showModalFilter()" color="danger" rounded elevated
                                            style="position: relative; bottom: -1.5rem; cursor:pointer; height: 3em">
                                            {{
                                                H.formatDateToLocalString(item.filterTgl.start) ==
                                                    H.formatDateToLocalString(item.filterTgl.end) ?
                                                    H.formatDateToLocalString(item.filterTgl.start) :
                                                    H.formatDateToLocalString(item.filterTgl.start) + ' - ' +
                                                    (item.filterTgl.end ?
                                                        H.formatDateToLocalString(item.filterTgl.end) : '')
                                            }}
                                            <i class="fas fa-filter ml-3" aria-hidden="true"></i>
                                        </VTag>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Badge :value="dataOrder.length" v-if="dataOrder.length > 0" severity="danger"
                    style="z-index: 5;top: 22px;position: relative;" />
                <div class="column is-12" style="margin-top: 1rem;">
                    <VTabs slider selected="Pasien" :tabs="[
                        { label: 'Daftar Order', value: 'Pasien' },
                        { label: 'Laporan Tindakan', value: 'Laporan' },
                    ]" style="margin-top: -2rem;">

                        <template #tab="{ activeValue }">

                            <p v-if="activeValue === 'Pasien'">

                            <div class="list-view list-view-v3">

                                <Vcard>
                                    <div class="search-menu mb-2">
                                        <div class="search-location" style="width: 100%">
                                            <i class="iconify" data-icon="feather:search"></i>
                                            <input type="text"
                                                placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                                                v-model="item.qsearch" v-on:keyup.enter="fetchDataOrder()" />
                                        </div>
                                        <!-- <div class="search-location">
                                            <i class="iconify" data-icon="feather:activity"></i>
                                            <input type="text" placeholder="No Registrasi" v-model="item.qnoregistrasi" />
                                        </div>
                                        <div class="search-salary">
                                            <i class="iconify" data-icon="feather:clipboard"></i>
                                            <input type="text" placeholder="No RM" v-model="item.qnocm" />
                                        </div>
                                        <div class="search-job">
                                            <i class="iconify" data-icon="feather:user"></i>
                                            <input type="text" placeholder="Nama Pasien" v-model="item.qnamapasien" />
                                        </div> -->
                                        <VButton raised class="search-button" @click="fetchDataOrder(order)"
                                            :loading="isLoading"> Cari Data
                                        </VButton>
                                    </div>
                                </Vcard>
                                <VCard class="text-center pt-0 pb-0 mt-0">
                                    <VRadio v-model="order" value="0" label="Pending" name="outlined_radio"
                                        color="warning" />
                                    <VRadio v-model="order" value="1" label="Verifikasi" name="outlined_radio"
                                        color="info" />
                                    <VRadio v-model="order" value="2" label="Selesai Pelayanan" name="outlined_radio"
                                        color="primary" />

                                </VCard>

                                <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                    class="my-6" :class="[dataOrder.length !== 0 && 'is-hidden']">
                                    <template #image>
                                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                    </template>
                                </VPlaceholderPage>
                                <div class="list-view-inner"
                                    style="max-height:500px;overflow: auto; margin-top: 1rem; ">
                                    <TransitionGroup name="list-complete" tag="div">
                                        <!--Item-->
                                        <div v-for="(items, i) in dataOrder" :key="items.id" class="list-view-item">
                                            <div class="list-view-item-inner">
                                                <VAvatar size="small" style="left: 8px;top: 4px;" :color="listColor[i]"
                                                    :initials="items.initials" />
                                                <div class="meta-left">
                                                    <h3>
                                                        {{ items.namapasien }} <i
                                                            :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                                            aria-hidden="true"
                                                            :style="'color:' + (items.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                                        |
                                                        <span>{{ items.noregistrasi }}</span>
                                                        |
                                                        <VTag v-if="items.kelompokpasien != null" class="mt-3 ml-2"
                                                            :label="items.kelompokpasien"
                                                            :color="items.kelompokpasien == 'BPJS' ? 'green' : 'orange'"
                                                            rounded /> |
                                                        <i class="bulet fas fa-circle"></i>
                                                        {{ items.namakelas }}
                                                    </h3>
                                                    <span>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:map-pin"></i>
                                                        <span>{{ items.asal_ruangan }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:clock"></i>
                                                        <span>Tgl Reg. {{ items.tglregistrasi }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:check-circle"></i>
                                                        <span>{{ items.noorder }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:clipboard"></i>
                                                        <span>{{ items.nocm }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:calendar"></i>
                                                        <span>{{ items.umur }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:calendar"></i>
                                                        <span>{{ item.nobpjs }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="teenyicons:id-outline"></i>
                                                        <span>{{ items.noidentitas }}</span>

                                                    </span>
                                                    <h4><span>Tgl. Order &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{
                                                        items.tanggalorder }}</span></h4>
                                                    <h4><span>Tgl. Operasi &ensp;{{ items.tanggaloperasi }}</span></h4>
                                                     <h4><span>No. Registrasi  &ensp;{{ items.noregistrasi }}</span></h4>
                                                    <VTag color="warning" rounded v-if="items.statusorder == 0">Pending
                                                    </VTag>
                                                    <VTag color="info" rounded v-if="items.statusorder == 1">
                                                        Terverifikasi</VTag>
                                                    <VTag color="primary" rounded v-if="items.statusorder == 2">Selesai
                                                    </VTag>
                                                    <VTag color="danger" class="ml-5" rounded v-if="items.iscito">Cito
                                                    </VTag>
                                                    <VTag color="danger" class="ml-5" rounded v-if="items.iselektif">
                                                        Elektif
                                                    </VTag>
                                                    <VTag color="danger" class="ml-5" rounded v-if="items.urgent">Urgent
                                                    </VTag>
                                                    <VTag color="primary" rounded v-if="items.statusoperasi == 1">
                                                        Menunggu Operasi
                                                    </VTag>
                                                    <VTag color="primary" rounded v-if="items.statusoperasi == 2">Sedang
                                                        Operasi
                                                    </VTag>
                                                    <VTag color="primary" rounded v-if="items.statusoperasi == 3">
                                                        Selesai Operasi
                                                    </VTag>
                                                    <VTag color="danger" rounded v-if="items.statusoperasi == 4">Batal
                                                        Operasi
                                                    </VTag>
                                                </div>
                                                <div class="meta-right">
                                                    <VIconButton v-if="items.statusorder == 0"
                                                        v-tooltip.bottom="'Verifikasi'" color="primary" circle
                                                        icon="pi pi-arrow-right" @click="orderVerify(items)"
                                                        :loading="items.loading" style="margin-right: 15px;" />
                                                    <VIconButton v-else v-tooltip.bottom="'Preview'" color="blue" circle
                                                        icon="fas fa-file-medical-alt" @click="orderVerify(items)"
                                                        :loading="items.loading" style="margin-right: 15px;" />

                                                    <VIconButton color="primary" circle icon="fas fa-stethoscope"
                                                        outlined raised @click="emr(items)" v-tooltip.bottom="'EMR'"
                                                        style="margin-right: 15px;">
                                                    </VIconButton>
                                                    <VIconButton v-tooltip.bottom.left="'Cetak SEP'" label="Bottom Left"
                                                        color="warning" circle icon="pi pi-print"
                                                        @click="cetakSEP(items)" :loading="item.loading">
                                                    </VIconButton>
                                                </div>
                                            </div>
                                        </div>
                                    </TransitionGroup>
                                </div>
                            </div>
                            </p>
                            <p v-else-if="activeValue === 'Laporan'">
                            <div class="search-menu mb-2">
                                <div class="search-location">
                                    <i class="iconify" data-icon="feather:activity"></i>
                                    <input type="text" placeholder="No Registrasi" v-model="item.qnoregistrasi" />
                                </div>
                                <div class="search-salary">
                                    <i class="iconify" data-icon="feather:clipboard"></i>
                                    <input type="text" placeholder="No RM" v-model="item.qnocm" />
                                </div>
                                <div class="search-job">
                                    <i class="iconify" data-icon="feather:user"></i>
                                    <input type="text" placeholder="Nama Pasien" v-model="item.qnama" />
                                </div>
                                <VButton raised class="search-button" @click="fetchLaporan()" :loading="isLoading"> Cari
                                    Data
                                </VButton>
                            </div>
                            <VCard radius="rounded">
                                <div class="user-grid user-grid-v2">
                                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                        @click="exportExcel()"> Export to
                                        Excel </VButton>
                                    <DataTable :value="dataLaporan" class="p-datatable-sm" :paginator="true" :rows="10"
                                        :rowsPerPageOptions="[5, 10, 25]"
                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                        scrollable scrollHeight="flex" tableStyle="min-width: 100rem" breakpoint="960px"
                                        sortMode="multiple"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} showGridlines ">
                                        <!-- <Column :exportable="false" header="#">
                                            <template #body="slotProps">
                                                <VIconButton type="button" icon="pi pi-bookmark" class="mr-3" color="info"
                                                    circle outlined raised v-tooltip.top="'Detail'"
                                                    @click="edit(slotProps.data)">
                                                </VIconButton>
                                            </template>
                                        </Column> -->
                                        <Column field="no" header="No"></Column>
                                        <Column field="namapasien" header="Nama Pasien" :sortable="true"></Column>
                                        <Column field="tgloperasi" header="Tanggal Operasi"></Column>
                                        <Column field="namaproduk" header="Tindakan"></Column>
                                        <Column field="asalruangan" header="Asal Ruangan"></Column>
                                        <Column field="dokterpemeriksa" header="Dokter Pemeriksa"></Column>

                                    </DataTable>
                                </div>

                            </VCard>
                            </p>
                        </template>
                    </VTabs>
                </div>
            </div>
            <div class="column is-4">
                <VCard>
                    <div class="column is-12">
                        <span style="font-weight: bold; font-size: 12px; font-family: var(--font-alt);">Jadwal Operasi
                        </span>

                        <!-- <VTag @click="filterJadwal()" color="danger" rounded elevated class="live-block is-clickable"
                            style="margin-left: 1.2rem;">
                            <span>{{ H.formatDateNoTime(item.filterDate) }}<i class="fas fa-filter ml-3"
                                    aria-hidden="true"></i>
                            </span>
                        </VTag> -->
                    </div>
                    <div class="tile-grid tile-grid-v2">
                        <div class="columns is-multiline" v-if="dataOperasi.loading">
                            <!--Grid item-->
                            <div v-for="key in 1" :key="key" class="column is-12">
                                <div class="tile-grid-item">
                                    <div class="tile-grid-item-inner">
                                        <VPlaceloadAvatar size="big" centered class="mb-2" />
                                        <VPlaceloadText class="mb-4" width="80%" :lines="3" last-line-width="60%"
                                            centered />


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--List Empty Search Placeholder -->
                        <VPlaceholderPage v-else-if="dataOperasi.length === 0" :title="H.assets().notFound"
                            :subtitle="H.assets().notFoundSubtitle" larger>
                            <template #image>
                                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                    alt="" />
                            </template>
                        </VPlaceholderPage>

                        <!--Tile Grid v1-->

                        <TransitionGroup name="list" tag="div" class="columns is-multiline"
                            v-else-if="dataOperasi.length > 0">
                            <!--Grid item-->
                            <div class="column is-multiline"
                                style="max-height: 300px; min-height: 50px;overflow: auto;">
                                <div v-for="item in dataOperasi" :key="item.id" class="column is-12">
                                    <div class="tile-grid-item">
                                        <div class="tile-grid-item-inner">
                                            <VAvatar size="small" picture="/images/avatars/svg/pasien.svg"
                                                color="primary" bordered />
                                            <div class="meta">

                                                <span class="dark-inverted">{{ item.namapasien }} - {{
                                                    item.asalruangan }}</span>

                                                <span class="dark-inverted">{{ item.namaproduk }}</span>
                                                <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded
                                                    elevated> {{
                                                        item.namalengkap }} </VTag>


                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>
                        </TransitionGroup>

                    </div>

                </VCard>

                <UIWidget class="search-widget" style="margin-top: 1.7rem;">
                    <template #body>
                        <div class="field">
                            <div class="control">
                                <input v-model="filters" class="input custom-text-filter"
                                    placeholder="Cari Dokter Praktek" @click="fetchDetail()" />
                                <button class="searcv-button">
                                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </UIWidget>
                <div class="column border-custom mb-2 mt-5-min">
                    <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Jadwal Dokter
                    </span>
                </div>
                <div class="tile-grid tile-grid-v2">
                    <VPlaceholderPage :class="[dokterPraktek.length !== 0 && 'is-hidden']"
                        title="Tidak Ada Dokter Praktek Hari Ini."
                        subtitle=" Silakan Pilih Ruangan untuk Melihat Jadwal Praktek Dokter" larger>
                        <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                                alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                alt="" />
                        </template>
                    </VPlaceholderPage>

                    <!--Tile Grid v1-->

                    <div name="list" tag="div" class="columns is-multiline">
                        <!--Grid item-->
                        <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                            <div v-for="item in dokterPraktek" :key="item.id" class="column is-12 p-0 pb-2 pl-2 pr-2 ">
                                <div class="tile-grid-item">
                                    <div class="tile-grid-item-inner">
                                        <VAvatar size="small" picture="/images/avatars/svg/dokter.svg" color="primary"
                                            bordered />
                                        <div class="meta">
                                            <span class="dark-inverted text-elipsis-wrap"
                                                style="width:200px !important">{{
                                                    item.namalengkap
                                                }}</span>
                                            <span>
                                                <i aria-hidden="true" class="iconify" data-icon="feather:clock"
                                                    style="padding-right: 3px;"></i>
                                                {{ item.jammulai }} s.d {{ item.jamakhir }}</span>
                                        </div>
                                        <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded
                                            elevated> {{
                                                item.hari }}
                                        </VTag>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="column border-custom mb-2" style="margin-top: 2rem;">
                    <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Stok Produk
                        <VIconButton v-tooltip.bottom.right="'Order Barang'" label="Bottom Right" color="primary" circle
                            icon="feather:shopping-cart" style="margin-left: 13rem;" @click="orderBarang()" />
                    </span>
                </div>
                <div class="tile-grid tile-grid-v2">

                    <!--List Empty Search Placeholder -->
                    <VPlaceholderPage :class="[dataStokObat.length !== 0 && 'is-hidden']" title="Tidak Ada Stok Produk."
                        subtitle=" Silakan Pilih Ruangan untuk Stok Produk" larger>
                        <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                                alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                alt="" />
                        </template>
                    </VPlaceholderPage>

                    <!--Tile Grid v1-->

                    <div name="list" tag="div" class="columns is-multiline">
                        <!--Grid item-->
                        <div class="columns is-multiline p-2" style="max-height:300px;overflow: auto;">
                            <div v-for="item in dataStokObat" :key="item.id" class="column is-6">
                                <div class="tile-grid-item">
                                    <div class="tile-grid-item-inner">
                                        <VAvatar size="small" picture="/images/simrs/produk-ico.png" color="primary"
                                            bordered />
                                        <div class="meta">
                                            <span class="dark-inverted">{{ item.namaproduk }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>

    <!-- Filter Tanggal -->
    <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
        @close="modalFilter = false">
        <template #content>
            <form class="modal-form">
                <div class="columns">
                    <div class="column is-12" style="text-align: center">
                        <VField class="is-centered">
                            <v-date-picker v-model="item.filterTgl" class="is-centered" is-range trim-weeks />
                        </VField>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="feather:search" @click="changePeriode()" :loading="isLoading" color="primary" raised>
                Filter</VButton>
        </template>
    </VModal>

    <VModal :open="modalFilterJadwal" title="Filter Periode" :noclose="true" size="small" actions="right"
        @close="modalFilterJadwal = false">
        <template #content>
            <form class="modal-form">
                <div class="columns">
                    <div class="column is-12" style="text-align: center">
                        <VField class="is-centered">
                            <v-date-picker v-model="item.filterDate" class="is-centered" trim-weeks />
                        </VField>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="feather:search" @click="fetchJadwal()" :loading="isLoading" color="primary" raised>
                Filter</VButton>
        </template>
    </VModal>

    <!-- Verifikasi Order -->
    <VModal :open="modalDetailOrder" title="Verifikasi Order" noclose size="big" actions="right"
        @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
        <template #content>
            <div class="business-dashboard hr-dashboard">
                <div class="columns is-multiline">
                    <div class="column is-12 p-0">
                        <div class="block-header">
                            <div class="left">
                                <div class="current-user">
                                    <!-- <VAvatar size="medium" :color="'warning'" :initials="'ER'" /> -->
                                    <VAvatar size="medium" :picture="item.jeniskelamin == 'PEREMPUAN'
                                        ? '/images/avatars/svg/vuero-4.svg'
                                        : '/images/avatars/svg/vuero-1.svg'
                                        " squared />
                                    <h3>{{ item.namapasien }}</h3>
                                    <p class="block-text">
                                        {{ item.noregistrasi + (item.jeniskelamin ==
                                            'PEREMPUAN' ? ' (P)'
                                            :
                                            ' (L)')
                                        }}
                                    </p>
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <h5 class="block-heading">
                                                CITO
                                            </h5>
                                            <VTag :color="item.iscito ? 'danger' : 'secondary'"
                                                :label="item.iscito ? 'Ya' : 'Tidak'" />
                                        </div>
                                        <div class="column is-4">
                                            <h5 class="block-heading">
                                                ELEKTIF
                                            </h5>
                                            <VTag :color="item.iselektif ? 'danger' : 'secondary'"
                                                :label="item.iselektif ? 'Ya' : 'Tidak'" />
                                        </div>
                                        <div class="column is-4">
                                            <h5 class="block-heading">
                                                URGENT
                                            </h5>
                                            <VTag :color="item.isurgent ? 'danger' : 'secondary'"
                                                :label="item.isurgent ? 'Ya' : 'Tidak'" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="center">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">No. Order</h4>
                                        <p class="block-text">{{ item.noorder }}</p>
                                        <h4 class="block-heading">Tgl Registrasi</h4>
                                        <p style="font-weight: normal;font-size: 12px;color: white;">{{
                                            H.formatDateToLocalString(item.tglregistrasi) }}</p>
                                    </div>
                                    <div class="column">
                                        <h4 class="block-heading">Ruangan</h4>
                                        <p style="font-weight: normal;font-size: 12px;color: white;">{{
                                            item.ruangantujuan
                                        }}</p>
                                        <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                                        <p>
                                            <VTag color="orange" :label="item.kelompokpasien" />
                                        </p>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">Tinggi Badan</h4>
                                        <p class="block-text">{{ item.tinggibadan ? item.tinggibadan : '-' }}</p>
                                        <h4 class="block-heading">No HP</h4>
                                        <p style="font-weight: normal;font-size: 12px;color: white;">
                                            {{ item.nohp ? item.nohp : '-' }}
                                        </p>
                                    </div>
                                    <div class="column">
                                        <h4 class="block-heading">Berat Badan</h4>
                                        <p class="block-text" style="font-weight: normal;font-size: 12px;color: white;">
                                            {{ item.beratbadan ? item.beratbadan : '-' }}</p>
                                        <h4 class="block-heading">No HP Keluarga</h4>
                                        <p style="font-weight: normal;font-size: 12px;color: white;">
                                            {{ item.nohp ? item.nohp : '-' }}
                                        </p>
                                    </div>

                                </div>
                            </div>
                            <div class="right">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">Diagnosa</h4>
                                        <p style="font-weight: normal;font-size: 12px;color: white;">
                                            {{ item.diagnosis }}
                                        </p>
                                        <h4 class="block-heading">Alat Amprahan</h4>
                                        <p style="font-weight: normal;font-size: 12px;color: white;">
                                            {{ item.alat ? item.alat : '-' }}
                                        </p>
                                        <h4 class="block-heading">Riwayat Swab</h4>
                                        <p style="font-weight: normal;font-size: 12px;color: white;">
                                            {{ item.riwayatswab ? item.riwayatswab : '-' }}
                                        </p>
                                        <h4 class="block-heading">Riwayat Vaksin</h4>
                                        <p style="font-weight: normal;font-size: 12px;color: white;">
                                            {{ item.riwayatvaksin ? item.riwayatvaksin : '-' }}
                                        </p>
                                        <!-- <p style="font-weight: normal;font-size: 12px;color: white;"
                                            v-for="(data, i) in detailDiagnosa" :key="i">
                                            &#9679; {{ data.kddiagnosa }} - {{ data.namadiagnosa }}
                                        </p> -->
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="columns is-multiline" style="padding: 2rem 0rem;">
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Jadwal Operasi </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField>
                        <VDatePicker v-model="item.tgloperasi" mode="dateTime" style="width: 100%">
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Jadwal Operasi" v-on="inputEvents"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </VField>
                </div>
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Ruangan </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl icon="feather:list" class="prime-auto-select">
                            <Dropdown v-model="item.kamaroperasi" :options="d_Kamar" :optionLabel="'namakamarok'"
                                placeholder="Pilih data" style="width: 100%;" class="is-rounded" showClear
                                appendTo="body" :filter="true" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Dokter Anak </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField class="label-unset">
                        <VControl>
                            <AutoComplete v-model="item.dokterAnak" :suggestions="d_Dokters"
                                @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true"
                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                placeholder="ketik nama Dokter" class="is-rounded" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Waktu Selesai Operasi </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField>
                        <VDatePicker v-model="item.tglselesai" mode="dateTime" style="width: 100%">
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Jadwal Operasi" v-on="inputEvents"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </VField>
                </div>
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Nama Tindakan Operasi </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl fullWidth>
                            <VInput type="text" v-model="item.jenisoperasi" placeholder="" class="is-rounded"
                                disabled />
                        </VControl>
                        <!-- <VControl fullwidth>
                            <VInput :value="item.jenisoperasi" disabled="true"
                                class="is-rounded" />
                        </VControl> -->
                    </VField>
                </div>
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Status Operasi </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl icon="feather:list" class="prime-auto-select">
                            <Dropdown v-model="item.statusoperasi" :options="d_Status" :optionLabel="'label'"
                                placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                                class="is-rounded" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Estimasi Waktu Operasi </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl class="mt-2">
                            <VInput type="text" placeholder="Estimasi Waktu" class="is-rounded"
                                v-model="item.estimasiwaktuoperasi" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> User Pengirim </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField class="label-unset">
                        <VControl>
                            <AutoComplete v-model="item.userPengirim" :suggestions="d_Dokters"
                                @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true"
                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                placeholder="ketik nama Dokter" class="is-rounded" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="display: none !important">
                    <h6 class=" is-5  mt-3">
                        <span> Tindakan Operasi </span>
                    </h6>
                </div>
                <div class="column is-4" style="display: none !important">
                    <VField>
                        <VControl class="mt-2">
                            <VInput type="text" placeholder="Tindakan Operasi" class="is-rounded"
                                v-model="item.tindakanoperasi" />
                        </VControl>
                    </VField>
                </div>

                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> User Penerima </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField class="label-unset">
                        <VControl>
                            <AutoComplete v-model="item.userPenerima" :suggestions="d_Dokters"
                                @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true"
                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                placeholder="ketik nama Dokter" class="is-rounded" />
                        </VControl>
                    </VField>
                </div>

                <div class="column is-3" style="display: none !important">
                    <VField label="Kamar Operasi" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="feather:home" class="prime-auto-select" fullwidth>
                            <Dropdown v-model="item.kamaroperasi" :options="d_Kamar" :optionLabel="'namakamarok'"
                                placeholder="Pilih data" style="width: 100%;" class="is-rounded" showClear
                                appendTo="body" :filter="true" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <div class="columns">
                        <!-- Anastesi -->
                        <div class="column is-6" v-if="item.isanastesi == 1">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h6 class="is-5 mt-3">
                                        <span> Dokter Anastesi :</span>
                                    </h6>
                                </div>
                                <div class="column is-8">
                                    <VField class="label-unset">
                                        <VControl>
                                            <AutoComplete v-model="item.dokterAnastesi" :suggestions="d_Dokters"
                                                @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                                placeholder="ketik nama Dokter" class="is-rounded"/>
                                        </VControl>
                                    </VField>
                                </div>
                                <template v-if="item.isanastesi"
                                v-for="(idanastesi, indx) in item.anastesitambahanfk" :key="indx">
                                    <div class="column is-4">&nbsp;</div>
                                    <div class="column is-8">
                                        <VField class="label-unset">
                                            <VControl>
                                                <AutoComplete v-model="item.anastesitambahanfk[indx]" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="ketik nama Dokter" class="is-rounded"/>
                                            </VControl>
                                        </VField>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h6 class=" is-5  mt-3">
                                        <span> Dokter Operator </span>
                                    </h6>
                                </div>
                                <div class="column is-8">
                                    <VField class="label-unset">
                                        <VControl>
                                            <AutoComplete v-model="item.dokterOperator" :suggestions="d_Dokters"
                                                @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                                placeholder="ketik nama Dokter" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                                <template v-if="item.operatorhelperfk.length > 0"
                                v-for="(idoperator, indxo) in item.operatorhelperfk" :key="indx">
                                    <div class="column is-4">&nbsp;</div>
                                    <div class="column is-8">
                                        <VField class="label-unset">
                                            <VControl>
                                                <AutoComplete v-model="item.operatorhelperfk[indxo]" :suggestions="d_Dokter"
                                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="ketik nama Dokter" class="is-rounded"/>
                                            </VControl>
                                        </VField>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <!-- Dokter Tambahan -->
                    </div>
                </div>
                <!-- <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                    <VCard class="is-grey">
                        <div class="columns is-multiline p-1">
                            <div class="column is-2">
                                <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select"
                                    v-slot="{ id }">
                                    <VControl icon="feather:plus-circle" fullwidth>
                                        <Multiselect mode="single" v-model="item.jenisPelaksana" :options="d_JenisPelaksana"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                            @select="changeJenis(item.jenisPelaksana)" track-by="value" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField class="is-rounded-select" v-slot="{ id }">
                                    <VLabel class="required-field">Pegawai</VLabel>
                                    <VControl fullwidth>
                                        <Multiselect mode="tags" :create-tag="true" placeholder="Pilih data"
                                            v-model="item.pegawai" :options="d_Pegawai" :searchable="true" :attrs="{ id }"
                                            autocomplete="off" appendTo="body" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-1 mt-3">
                                <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                                    icon="feather:trash" @click="removeItem(index)" color="danger">
                                </VIconButton>
                            </div>
                            <div class="column is-1 is-flex mt-3">
                                <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                                    @click="addNewItem()"> Tambah Pelaksana
                                </VButton>
                            </div>
                        </div>
                    </VCard>
                </div> -->
            </div>


            <div class="column is-12 p-4 mt-5">
                <Fieldset legend="Tambah Tindakan" :toggleable="true">

                    <div class="columns p-3">
                        <div class="column is-1 pr-0" style="padding-left: 0px;margin-right: -38px">
                            <VField label="No">
                                <VAvatar initials="1" />
                            </VField>
                        </div>
                        <div class="column is-11 ml-5">
                            <div class="columns">
                                <div class="column is-4">
                                    <VField label="Pelayanan" class="is-rounded-select  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:list" class="prime-auto-select" fullwidth>
                                            <Dropdown v-model="item.produk" :options="d_Produk" :optionLabel="'label'"
                                                placeholder="Pilih data" style="width: 100%;" class="is-rounded"
                                                showClear appendTo="body" :filter="true"
                                                @change="changeTindakan(item.produk)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <VField label="Harga">
                                        <VLabel class="mt-4">{{
                                            H.formatRp((item.hargaLayanan ? item.hargaLayanan : 0),
                                                'Rp.')
                                        }}</VLabel>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField class="label-unset" label="Dokter Operator">
                                        <VControl>
                                            <AutoComplete v-model="item.dokterOperatorTindakan" :suggestions="d_Dokters"
                                                @complete="fetchDokter($event)" :optionLabel="'namalengkap'"
                                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                                placeholder="ketik nama Dokter" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-1">
                                    <VField label="Jumlah">
                                        <VInput type="text" v-model="item.jumlah" placeholder="Jumlah"
                                            class="is-rounded" />
                                    </VField>
                                </div>
                                <div class="columns mt-2" style="margin-left:40px">
                                    <VButtons>
                                        <VButton color="success" raised icon="feather:edit"
                                            v-if="item.no && d_Komponen.length" @click="update(item)"> Update
                                        </VButton>
                                        <VButton color="info" raised icon="fas fa-plus"
                                            v-else-if="!item.no && d_Komponen.length" @click="add(), clear()">
                                            Tambah
                                        </VButton>
                                        <VButton raised @click="clear()"> Batal </VButton>
                                    </VButtons>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-11">
                <div class="timeline-wrapper" v-if="detailOrderLayanan.length > 0">
                    <div class="timeline-header">
                    </div>
                    <div class="timeline-wrapper-inner">
                        <div class="timeline-container">
                            <div class="timeline-item is-unread" v-for="(items, index) in detailOrderLayanan"
                                :key="items.norec">
                                <div class="date">
                                    <span>{{ H.formatDateIndo(items.tglpelayanan) }}</span>
                                </div>
                                <div :class="'dot is-' + listColor[index + 1]"></div>

                                <div class="content-wrap is-grey">
                                    <div class="content-box">
                                        <div class="status"></div>
                                        <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                                            <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                                        </VIconBox>
                                        <div class="box-text" style="width: 85%">
                                            <div class="meta-text">
                                                <p>
                                                    <span>{{ items.namaproduk + '&nbsp' + '&nbsp' + '[' +
                                                        items.qtyproduk +
                                                        ']' }}</span>
                                                </p>
                                                <table style="width: 100%; margin-top: 10px">
                                                    <tr>
                                                        <td class="font-labels" width="50%">Harga</td>
                                                        <td class="font-labels">:</td>
                                                        <td class="font-values" width="50%">{{
                                                            H.formatRp(items.hargasatuan,
                                                                'Rp. ') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-labels" width="50%">Ruangan Tujuan</td>
                                                        <td class="font-labels">:</td>
                                                        <td class="font-values" width="60%">
                                                            {{ items.ruangantujuan }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-labels" width="50%">Dokter Operator</td>
                                                        <td class="font-labels">:</td>
                                                        <td class="font-values" width="60%">
                                                            {{ items.dokterOpTindakan }}
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="box-end" style="width: 30%">
                                            <div class="columns is-multiline">
                                                <div class="column is-6" style="margin-top: 0.5rem;">
                                                    <VIconButton v-tooltip.bottom.left="'Edit'" icon="feather:edit"
                                                        @click="edit(items)" color="warning" raised circle class="mr-2">
                                                    </VIconButton>
                                                    <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash"
                                                        @click="hapusItems(items)" color="danger" raised circle>
                                                    </VIconButton>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                <VCard class="is-grey">
                    <div class="columns is-multiline p-1">
                        <div class="column is-3">
                            <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select"
                                v-slot="{ id }">
                                <VControl icon="feather:plus-circle" fullwidth>
                                    <Dropdown v-model="item.jenisPelaksana" :options="d_JenisPelaksana"
                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                        placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                                        @change="changeJenis(item)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                <VLabel>Pegawai</VLabel>
                                <VControl icon="feather:user" fullwidth :loading="isLoadChange"
                                    class="prime-auto-select">
                                    <MultiSelect v-model="item.pegawai" display="chip" class="w-100 is-rounded"
                                        :options="item.d_Pegawai" optionLabel="label" optionValue="value" filter
                                        placeholder="Pilih Data" :maxSelectedLabels="3" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-1 mt-3">
                            <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                                icon="feather:trash" @click="removeItem(index)" color="danger">
                            </VIconButton>
                        </div>
                        <div class="column is-1 is-flex mt-3">
                            <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                                @click="addNewItem()"> Tambah Pelaksana
                            </VButton>
                        </div>
                    </div>
                </VCard>
            </div>


        </template>
        <template #action>
            <VButton icon="feather:save" :loading="isLoading" @click="save()" color="primary" raised>Simpan</VButton>
        </template>
    </VModal>

    <!-- Detail Order Verifikasi -->
    <VModal :open="modalDetailOrderVerify" title="Detail Order" noclose size="big" actions="right"
        @close="modalDetailOrderVerify = false, clear()" cancelLabel="Tutup">
        <template #content>
            <div class="business-dashboard hr-dashboard">
                <div class="columns is-multiline">
                    <div class="column is-12 p-0">
                        <div class="block-header">
                            <div class="left">
                                <div class="current-user">
                                    <VAvatar size="medium" :picture="item.jeniskelamin == 'PEREMPUAN'
                                        ? '/images/avatars/svg/vuero-4.svg'
                                        : '/images/avatars/svg/vuero-1.svg'
                                        " squared />
                                    <h3>{{ item.namapasien }}</h3>

                                </div>
                            </div>
                            <div class="center">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">No. Order</h4>
                                        <p class="block-text">{{ item.noorder }}</p>
                                        <h4 class="block-heading">Tgl Registrasi</h4>
                                        <p class="block-text">{{ H.formatDateIndo(item.tglregistrasi) }}</p>
                                    </div>
                                    <div class="column">
                                        <h4 class="block-heading">Ruangan</h4>
                                        <p class="block-text">{{ item.ruangantujuan }}</p>
                                        <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                                        <p>
                                            <VTag color="orange" :label="item.kelompokpasien" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">Diagnosa</h4>
                                        <p class="block-text">
                                            {{ item.namadiagnosa ? item.namadiagnosa : 'Belum Ada Diagnosa' }}</p>
                                        <h4 class="block-heading">Tgl Operasi</h4>
                                        <p class="block-text">{{ H.formatDateIndo(item.tgloperasi) }}</p>
                                        <h4 class="block-heading">Estimasi Waktu</h4>
                                        <p class="block-text">{{ item.estimasiwaktuoperasi || "-" }} Menit</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-11">
                <div class="timeline-wrapper" v-if="detailOrderVerify.length > 0">
                    <div class="timeline-header">
                    </div>
                    <div class="timeline-wrapper-inner">
                        <div class="timeline-container">
                            <div class="timeline-item is-unread" v-for="(items, index) in detailOrderVerify"
                                :key="items.norec">
                                <div class="date">
                                    <span>{{ H.formatDateIndo(items.tglpelayanan) }}</span>
                                </div>
                                <div :class="'dot is-' + listColor[index + 1]"></div>
                                <!-- test -->
                                <div class="content-wrap is-grey">
                                    <div class="content-box">
                                        <div class="status"></div>
                                        <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                                            <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                                        </VIconBox>
                                        <div class="box-text" style="width: 100%">
                                            <div class="meta-text column is-12">
                                                <p>
                                                    <span>{{ items.namaproduk }}</span>
                                                </p>
                                                <table style="margin-top: 10px" width="100%">
                                                    <tr>
                                                        <td class="font-labels" width="10%">Harga</td>
                                                        <td class="font-labels" width="2%">:</td>
                                                        <td class="font-values" width="60%">{{ H.formatRp(items.total,
                                                            'Rp.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-labels" width="50%">Jumlah</td>
                                                        <td class="font-labels">:</td>
                                                        <td class="font-values" width="50%">{{ items.jumlah }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-labels" width="50%">Pengorder</td>
                                                        <td class="font-labels">:</td>
                                                        <td class="font-values" width="60%">
                                                            {{ items.namalengkap }}
                                                        </td>
                                                    </tr>

                                                </table>

                                            </div>

                                        </div>

                                        <!-- <div class="box-end" style="width: 20%">
                                            <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded
                                                elevated> {{ items.namalengkap }} </VTag>


                                        </div> -->
                                        <!-- <div style="width: 20%;">
                                            <div class="box-end" v-for="(item, index) in detailPetugas">
                                                <VTag style="margin-left: auto;" class="mt-2" color="info" label="Tag Label" rounded
                                                    elevated>
                                                    {{ item.namalengkappetugas }}
                                                </VTag>
                                            </div>
                                        </div> -->


                                    </div>
                                    <div style="width: 20%;">
                                        <label :style="'font-weight: bold;'">Dokter <span
                                                style="color: #bb2124;">Anastesi</span> dan <span
                                                style="color: #5bc0de;">Operasi</span></label>
                                        <!-- <label>Dokter Anastesi dan Operasi </label> -->
                                        <div class="box-end" v-for="(item, index) in detailPetugas">
                                            <VTag style="margin-left: auto;" class="mt-2"
                                                :style="{ 'background-color': getColorByType(item.objectjenispetugaspefk), 'color': 'white', 'font-size': '12px' }"
                                                label="Tag Label" rounded elevated>
                                                {{ item.namalengkappetugas }}
                                            </VTag>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </template>

    </VModal>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment, { isDate } from 'moment'
import Fieldset from 'primevue/fieldset'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import Dropdown from 'primevue/dropdown'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import MultiSelect from 'primevue/multiselect';
import Column from 'primevue/column'
import * as XLSX from "xlsx";
import * as qzService from '/@src/utils/qzTrayService'

useHead({
    title: 'Dashboard Bedah - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()

const NOREC_PD = useRoute().query.nocm as string
const dataSource: any = ref([])
const filters = ref('')
const d_Dokter = ref([])
const d_Status: any = ref([{ value: 1, label: 'Menunggu Operasi' }, { value: 2, label: 'Sedang Operasi' }, { value: 3, label: 'Selesai Operasi' }, { value: 4, label: 'Batal Operasi' }])
const d_Dokters = ref([])
const remakeData: any = ref([])
const d_DokterOperasi = ref([])
const d_Petugas = ref([])
const d_JenisPelaksana = ref([])
const d_Komponen = ref([])
const d_Pegawai = ref([])
const d_Ruangan = ref([])
const d_Ruangans = ref([])
const d_Produk = ref([])
const d_JenisOperasi: any = ref([])
const router = useRouter()
const modalFilter: any = ref(false)
const currentPage: any = ref({
    limit: 5,
    rows: 50,
})
const listItem: any = ref([
    {
        pegawai: [],
        d_Pegawai: [],
        jenisPelaksana: null,
    }
])
const date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
const modalDetail = ref(false)
const route = useRoute()
const item: any = ref({
    filterDate: new Date(),
    isTglOrder: ref(true),
    filterTgl: ref({
        start: new Date(),
        end: new Date(),
    }),
    operatorhelperfk: []
})
const order: any = ref(0)
const dataOrder: any = ref(0)
const dataLaporan: any = ref(0)

let listColor: any = ref(Object.keys(useThemeColors()))
let statusOrder: any = ref([])
let dokterPraktek: any = ref([])
let isLoading: any = ref(false)
let modalDetailOrder: any = ref(false)
const isLoadChange: any = ref(false)
let modalDetailOrderVerify: any = ref(false)
let modalFilterJadwal: any = ref(false)
let isLoadBtn: any = ref(false)
let detailDiagnosa: any = ref([])
let dataStokObat: any = ref([])
let dataOperasi: any = ref([])
let detailOrderVerify: any = ref(0)
let detailPetugas: any = ref(0)
let detailOrderLayanan: any = ref(0)
let isData: any = ref()
let d_Kamar: any = ref([])
function getColorByType(objectjenispetugaspefk) {
    if (objectjenispetugaspefk === 6) {
        return '#bb2124';
    } else if (objectjenispetugaspefk === 17) {
        return '#5bc0de';
    } else {
        return 'gray';
    }
}

async function fetchDokter(filter: any) {
    let query = ''
    if (filter) {
        query = filter.query
    }
    const response = await useApi().get(`/general/dokter-paging?name= ${query}&limit=10`)
    d_Dokters.value = response.dokter
    // return response.dokter.map((item: any) => {
    //     return { value: item.id, label: item.namalengkap, default: item }
    // })
}

const fetchDataOrder = async (q: any) => {

    console.log(item.value.isTglOrder)
    if (!item.value.isTglOrder && !item.value.isTglOperasi) { H.alert('warning', 'Pilih salah satu tgl order atau tgl operasi'); return }
    if (item.value.isTglOrder && item.value.isTglOperasi) { H.alert('warning', 'Pilih salah satu tgl order atau tgl operasi'); return }


    try {
        let ruanganid = item.value.filterRuangan ? `&ruanganid=${item.value.filterRuangan}` : '';
        let dari = item.value.filterTgl.start ? `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}` : '';
        let sampai = item.value.filterTgl.end ? `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}` : '';
        let qnamapasien = item.value.qnamapasien ? `&qnamapasien=${item.value.qnamapasien}` : '';
        let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
        let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
        let statusOrder = q ? `&statusorder=${q}` : '';
        let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';

        let opsi = ''
        if (item.value.isTglOrder) {
            opsi = 'tglorder'
        } else if (item.value.isTglOperasi) {
            opsi = 'tgloperasi'
        }

        isLoading.value = true;

        const response = await useApi().get(`/dashboard/get-order-bedah?${ruanganid}${dari}${sampai}${qnamapasien}${statusOrder}${qnocm}${qnoregistrasi}${search}&opsi=${opsi}`);

        modalFilter.value = false;

        response.forEach((element: any, i: any) => {
            element.no = i;
            let ini = element.namapasien.split(' ');
            let init = element.namapasien.substr(0, 2);
            if (ini.length > 1) {
                init = init + ini[1].substr(0, 1);
            }
            element.initials = init;
            element.ruanganasal = element.asal_ruangan;
            element.tglRegistrasi = moment(element.tglregistrasi).format('YYYY-MM-DD');
        });

        isData.value = response.length;
        dataOrder.value = response;
    } catch (error) {
        console.error('Error fetching order data:', error);
    } finally {
        isLoading.value = false;
    }
}

function changeTindakan(e: any) {
    // console.log(e)
    isLoading.value = true
    d_Komponen.value = []
    item.value.hargaLayanan = 0
    useApi().get(
        '/tindakan/list-tindakan-komponen?idRuangan=' + item.value.idRuanganTujuan
        + '&idKelas=' + item.value.kelas
        + '&idProduk=' + e.id
        + '&idJenisPelayanan=' + item.value.idJenisPelayanan
        + '&objectkebangsaanfk=' + item.value.objectkebangsaanfk
        // + '&idPenjamin=' + item.registrasi.objectrekananfk
    ).then((response: any) => {
        isLoading.value = false
        if (response.komponen.length == 0) {
            H.alert('warning', 'Komponen Tarif tidak ada')
            return
        }
        item.value.hargaLayanan = response.harga.hargasatuan
        item.hargasatuanDef = response.harga.hargasatuan
        item.value.jumlah = 1
        d_Komponen.value = response.komponen

    })
}


// const fetchDataOrder = async (q: any) => {

//     let ruanganid = ''
//     if (item.value.filterRuangan) {
//         ruanganid = `&ruanganid=${item.value.filterRuangan}`
//     }
//     let dari = ''
//     if (item.value.filterTgl.start) {
//         dari = `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
//     }
//     let sampai = ''
//     if (item.value.filterTgl.end) {
//         sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
//     }
//     let qnamapasien = ''
//     if (item.value.qnamapasien) {
//         qnamapasien = `&qnamapasien=${item.value.qnamapasien}`
//     }
//     let qnocm = ''
//     let qnoregistrasi = ''
//     let StatusOrder = ''
//     let search = ''
//     item.value.statusOrder = q
//     if (order) StatusOrder = '&statusorder=' + q
//     if (item.value.qnocm) qnocm = '&qnocm=' + item.value.qnocm
//     if (item.value.search) search = '&search=' + item.value.search
//     if (item.value.qnoregistrasi) qnoregistrasi = '&qnoregistrasi=' + item.value.qnoregistrasi
//     await useApi().get('/dashboard/get-order-bedah?ruanganid=' + ruanganid + '&dari=' + dari + '&sampai=' + sampai + '&qnamapasien=' + qnamapasien + '&statusorder=' + StatusOrder + '&qnocm=' + qnocm + '&qnoregistrasi=' + qnoregistrasi + '&search=' + search).then((response) => {
//         modalFilter.value = false
//         response.forEach((element: any, i: any) => {
//             element.no = i
//             let ini = element.namapasien.split(' ')
//             let init = element.namapasien.substr(0, 2)
//             if (ini.length > 1) {
//                 init = init + ini[1].substr(0, 1)
//             }
//             element.initials = init
//             // element.ruanganasal = (element.asal_ruangan.length > 20) ? element.ruanganasal = element.asal_ruangan.substring(0, 20) + '...' : element.ruanganasal = element.asal_ruangan
//             element.ruanganasal = element.asal_ruangan
//             element.tglRegistrasi = moment(element.tglregistrasi).format('YYYY-MM-DD')
//         });
//         isData.value = response.length
//         dataOrder.value = response
//         isLoading.value = false
//     }).catch((err) => {
//         modalFilter.value = false
//     })
// }

async function fetchLaporan() {
    dataSource.value.loading = true
    let dari = ''
    if (item.value.filterTgl.start) {
        dari = `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
    }
    let sampai = ''
    if (item.value.filterTgl.end) {
        sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
    }
    let produk = item.value.namaproduk ? `&namaproduk=${item.value.namaproduk}` : ''

    await useApi().get(`/dashboard/laporan-tindakan-operasi?${dari}${sampai}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataLaporan.value = response
        dataLaporan.value.loading = false
    }).catch((e) => {
        dataLaporan.value.loading = false
    })
}

const fetchJadwal = async () => {
    let ruanganid = ''
    if (item.value.filterRuangan) {
        ruanganid = item.value.filterRuangan
    }
    let tgl = H.formatDate(item.value.filterDate, 'YYYY-MM-DD')

    dataOperasi.value = []
    dataOperasi.value.loading = true
    const response = await useApi().get('/dashboard/jadwal-operasi?ruanganid=' + ruanganid + '&tgl=' + tgl
    )
    dataOperasi.value.loading = false
    modalFilterJadwal.value = false
    dataOperasi.value = response.dataOperasi
}

const filterJadwal = () => {
    modalFilterJadwal.value = true
}
const fetchDetail = async () => {
    let ruanganid = ''
    if (item.value.filterRuangan) {
        ruanganid = item.value.filterRuangan
    }
    dokterPraktek.value = []
    dataStokObat.value = []
    const response = await useApi().get(
        '/dashboard/bedah-detail?ruanganid=' + ruanganid
    )
    dokterPraktek.value = response.dokter
    dataStokObat.value = response.produk
}

const getListPelayanan = async (data: any) => {
    const response = await useApi().get(`/dashboard/get-pelayanan-bedah?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}&objectkebangsaanfk=${item.value.objectkebangsaanfk}&objectruangantujuanfk=${item.value.idRuanganTujuan}`)
    d_Produk.value = response.map((e: any) => {
        return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
    })
}


const getKomponenHarga = async (param: any) => {
    let datas = []
    await useApi().get('/dashboard/get-komponen-bedah?idProduk=' + param.layanan + '&idKelas=' + param.kelas + '&idJenLayan=' + param.idJenisPelayanan)
        .then((response) => {
            response.forEach((items: any) => {
                datas.push(items)
            })
        })
}

const orderVerify = async (e: any) => {
    dropdownList()
    await H.statusClosingPasien(e.noregistrasi);
    e.loading = true
    let data = {
        'idkelas': e.objectkelasfk,
        'idJenisPelayanan': e.jenispelayananfk,
        'idRekanan': e.objectrekananfk
    }
    item.value.jenisoperasi = e.jenisoperasi
    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.namapasien = e.namapasien
    item.value.objectkebangsaanfk = e.objectkebangsaanfk
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.noorder = e.noorder
    item.value.no_rm = e.pas_nocm
    item.value.jeniskelamin = e.jeniskelamin
    item.value.kelompokpasien = e.kelompokpasien
    item.value.idRuanganTujuan = e.objectruangantujuanfk
    item.value.pdNorec = e.pd_norec
    item.value.noregistrasi = e.noregistrasi
    item.value.soNorec = e.so_norec
    item.value.objectpegawaiorderfk = e.objectpegawaiorderfk
    item.value.dokterorder = e.nama_pegawai
    item.value.tgloperasi = e.tgloperasi
    item.value.tglregistrasi = e.tglregistrasi
    item.value.kelas = e.objectkelasfk
    item.value.noregistrasi = e.noregistrasi
    item.value.estimasiwaktuoperasi = e.durasi
    item.value.iscito = e.iscito
    item.value.iselektif = e.iselektif
    item.value.isurgent = e.isurgent
    item.value.nohp = e.nohp
    item.value.alat = e.alat
    item.value.isanastesi = e.isanastesi

    if (e.objectkamaroperasifk)
        item.value.kamaroperasi = { id: e.objectkamaroperasifk, namakamarok: e.namakamarok }
    //item.value.dokterOperator = {id:e.dokteroperatorfk,namalengkap:e.dokteroperator}
    getListPelayanan(data);
    console.log("DETAIL FROM E", e)
    const getHargaLayanan = await useApi().get(`/dashboard/get-detail-order?strukorderfk=${e.so_norec}&objectkelasfk=${e.objectkelasfk}`)
    console.log(getHargaLayanan)
    item.value.estimasiwaktuoperasi = getHargaLayanan[0].durasi
    // const responsed = await useApi().get(`/general/dokter-paging?limit=10`)
    // responsed.dokter.forEach(element => {
    //     if (getHargaLayanan[0].dokteroperatorfk == element.id) {
    //     item.dokterOperator = element
    //     return
    //     }
    // });

    item.value.tinggibadan = e.tinggibadan
    item.value.beratbadan = e.beratbadan
    item.value.diagnosis = e.diagnosis

    item.value.dokterOperator = { id: getHargaLayanan[0].dokteroperatorfk, namalengkap: getHargaLayanan[0].dokteroperator }
    item.value.dokterAnastesi = { id: getHargaLayanan[0].dokteranastesifk, namalengkap: getHargaLayanan[0].dokteranastesi }
    // item.value.jenisoperasi = getHargaLayanan[0].jenisoperasifk
    if (getHargaLayanan[0].statusoperasi == 1) {
        item.value.statusoperasi = { value: 1, label: 'Menunggu Operasi' }
    } else if (getHargaLayanan[0].statusoperasi == 2) {
        item.value.statusoperasi = { value: 2, label: 'Sedang Operasi' }
    } else if (getHargaLayanan[0].statusoperasi == 3) {
        item.value.statusoperasi = { value: 3, label: 'Selesai Operasi' }
    } else if (getHargaLayanan[0].statusoperasi == 4) {
        item.value.statusoperasi = { value: 4, label: 'Batal Operasi' }
    }
    if (getHargaLayanan[0].dokteranakfk != null) {
        item.value.dokterAnak = { id: getHargaLayanan[0].dokteranakfk, namalengkap: getHargaLayanan[0].dokteranak }
    }
    if (getHargaLayanan[0].penerimafk != null) {
        item.value.userPenerima = { id: getHargaLayanan[0].penerimafk, namalengkap: getHargaLayanan[0].namapenerima }
    }
    item.value.userPengirim = { id: getHargaLayanan[0].dokteroperatorfk, namalengkap: getHargaLayanan[0].pengorder }
    item.value.tglselesai = getHargaLayanan[0].tglselesai
    // console.log(item.dokterOperator)
    const diagnosa = await useApi().get(`diagnosa/riwayat-diagnosa-x?nocmfk=${e.nocmfk}&norec_pd=${e.pd_norec}`)
    getHargaLayanan.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    let needAnastesi = [];
    if(e.anastesitambahanfk != null) {
        let arrAnastesi = JSON.parse(e.anastesitambahanfk);
        if(arrAnastesi.length > 0) {
            arrAnastesi.forEach((a, ia) => {
                let dtFilter = d_Dokter.value.filter((dt_d) => {
                    return dt_d.value === a;
                });
                if(dtFilter.length > 0) {
                    needAnastesi.push({
                        value: dtFilter[0].value,
                        label: dtFilter[0].label
                    })
                }
            })
        }
    }
    item.value.isoperatorhelper = false;

    let needHelper = [];
    if(e.operatorhelperfk != null) {
        let arrHelper = JSON.parse(e.operatorhelperfk);
        if(arrHelper.length > 0) {
            arrHelper.forEach((a, ia) => {
                let dtFilter = d_Dokter.value.filter((dt_d) => {
                    return dt_d.value === a;
                });
                if(dtFilter.length > 0) {
                    needHelper.push({
                        value: dtFilter[0].value,
                        label: dtFilter[0].label
                    })
                    item.value.isoperatorhelper = true;
                }
            })
        }
    }

    item.value.anastesitambahanfk = needAnastesi;
    item.value.operatorhelperfk = needHelper;
    console.log("Operator heloer", item.value.operatorhelperfk.length)
    console.log("Need Anastesi",needAnastesi);
    detailDiagnosa.value = diagnosa.data

    //detailOrderLayanan.value = getHargaLayanan
    useApi().get(`emr/auto-fill?nocmfk=${e.nocmfk}&norec_pd=${e.pd_norec}&collection=AsesmenAwalMedisGawatDarurat&field=TAVaksin`).then((dataEMR) => {
        console.log("DATA EMR", dataEMR)
        if (dataEMR != null && dataEMR.TAVaksin) {
            item.value.riwayatvaksin = dataEMR.TAVaksin;
        }
    })
    detailOrderLayanan.value = []
    modalDetailOrder.value = true
    e.loading = false
}

const getDetailVerify = async (e: any) => {
    e.loading = true
    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.namapasien = e.namapasien
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.noorder = e.noorder
    item.value.no_rm = e.pas_nocm
    item.value.jeniskelamin = e.jeniskelamin
    item.value.kelompokpasien = e.kelompokpasien
    item.value.idRuanganTujuan = e.objectruangantujuanfk
    item.value.dokterorder = e.nama_pegawai
    item.value.tglregistrasi = e.tglregistrasi
    item.value.tgloperasi = e.tgloperasi

    const response = await useApi().get(`/dashboard/get-bedah-verify?norec_so=${e.so_norec}`)
    const petugas = await useApi().get(`/dashboard/get-petugas-verify?norec_so=${e.so_norec}`)
    const diagnosa = await useApi().get(`diagnosa/riwayat-diagnosa-x?nocmfk=${e.nocmfk}&norec_pd=${e.pd_norec}`)
    response.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    detailDiagnosa.value = diagnosa.data
    detailOrderVerify.value = response
    detailPetugas.value = petugas
    e.loading = false
    modalDetailOrderVerify.value = true
}

const save = async () => {
    console.log(item.value.jenisoperasi)
    await H.statusClosingPasien(item.value.noregistrasi);
    if (detailOrderLayanan.value.length < 1) {
        useToaster().error('Order Tidak Boleh kosong')
        return
    }
    if (!item.value.estimasiwaktuoperasi) {
        useToaster().warn('estimasiwaktuoperasi Tidak Boleh kosong')
        return
    }
    if (listItem.value.length <= 0) {
        useToaster().error('Pilih Setidaknya 1 Petugas')
        return
    }
    let datas = []
    let datass = []
    let petugas = []
    for (let i = 0; i < listItem.value.length; i++) {
        const element = listItem.value[i];
        var jenispet = ''
        for (let z = 0; z < d_JenisPelaksana.value.length; z++) {
            const element2: any = d_JenisPelaksana.value[z];
            if (element.jenisPelaksana == element2.value) {
                jenispet = element2.label
                break
            }
        }
        var listPegawai: any = []
        for (let k = 0; k < element.pegawai.length; k++) {
            const elementxx = element.pegawai[k];
            var namaPegawai = ''
            for (let zz = 0; zz < d_Pegawai.value.length; zz++) {
                const elementPeg: any = d_Pegawai.value[zz];
                if (elementxx == elementPeg.value) {
                    namaPegawai = elementPeg.label
                    break
                }
            }
            listPegawai.push({
                'id': elementxx,
                'namalengkap': namaPegawai
            })
        }
        petugas.push({
            "objectjenispetugaspefk": element.jenisPelaksana,
            "jenispetugaspe": jenispet,
            "listpegawai": listPegawai
        })
    }
    let parameter = {
        'idruangtujuan': item.value.idRuanganTujuan,
        'pd_norec': item.value.pdNorec,
        'tglregistrasi': item.value.tglregistrasi,
        'noregistrasi': item.value.noregistrasi,
        'so_norec': item.value.soNorec,
        'objectkelasfk': item.value.kelas,
        'estimasiwaktuoperasi': item.value.estimasiwaktuoperasi,
        'kamaroperasifk': item.value.kamaroperasi ? item.value.kamaroperasi.id : null,
        'dokteroperatorfk': item.value.dokterOperator ? item.value.dokterOperator.id : null,
        'dokteranastesifk': item.value.dokterAnastesi ? item.value.dokterAnastesi.id : null,
        'tglselesai': item.value.tglselesai,
        'objectpegawaiorderfk': item.value.userPengirim ? item.value.userPengirim.id : null,
        'dokteranakfk': item.value.dokterAnak ? item.value.dokterAnak.id : null,
        'penerimafk': item.value.userPenerima ? item.value.userPenerima.id : null,
        'statusoperasi': item.value.statusoperasi ? item.value.statusoperasi.value : null,
    }
    // 'jenisoperasifk': item.value.jenisoperasi?item.value.jenisoperasi:null,

    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        let response = detailOrderLayanan.value[i]
        let komponenHarga = detailOrderLayanan.value[i].komponenharga
        var objSave = {
            'idProduk': response.prid,
            'hargaLayanan': response.hargasatuan,
            'tglpelayanan': response.tglpelayanan,
            'jumlah': response.qtyproduk,
            'komponenharga': komponenHarga,
            'pelayananpetugas': petugas
        }
        datas.push(objSave)
    }
    isLoading.value = true;
    await useApi().post('/dashboard/save-order-pelayanan-bedah', { 'data': datas, 'parameter': parameter }).then((response: any) => {
        modalDetailOrder.value = false
        fetchDataOrder(0)
        isLoading.value = false;
    }).catch((error) => {
        isLoading.value = false;
        useToaster().error('Something Went Wrong')
    })
}

const hapusItems = (e: any) => {
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        if (detailOrderLayanan.value[i].no == e.no) {
            detailOrderLayanan.value.splice(i, 1);
        }
    }
    dataSource.value = detailOrderLayanan.value
    console.log(detailOrderLayanan.value)
}


const changeSwitch = (e: any) => {
    fetchDataOrder(e)
}

const showModalFilter = () => {
    modalFilter.value = true
}

const clear = () => {
    item.value.id = ''
    item.value.no = ''
    item.value.layanan = ''
    item.value.hargaLayanan = ''
    item.value.qtyproduk = ''
    item.value.jumlah = ''
}

const reload = () => {
    fetchJadwal()
}
const orderBarang = () => {
    router.push({
        name: 'module-logistik-order-barang'
    })
}

const add = async () => {

    let datas: any = []
    if (!item.value.produk) {
        useToaster().error('Layanan Tidak Boleh Kosong')
        return
    }
    if (!item.value.hargaLayanan) {
        useToaster().error('Harga Tidak Boleh Kosong')
        return
    }
    if (!item.value.jumlah) {
        useToaster().error('Jumlah Tidak Boleh Kosong')
        return
    }
    if (!item.value.dokterOperatorTindakan) {
        useToaster().error('Dokter Operator Tindakan Tidak boleh kosong')
        return;
    }

    // let jumlah = e.jumlah
    // let harga = e.hargaLayanan
    // let ruangan = e.ruangantujuan
    // let namaproduk = e.namaproduk
    // let layanan = e.layanan
    let no

    (detailOrderLayanan.value.length == 0) ? no = 1 : no = detailOrderLayanan.value.length + 1

    let data = {
        no: no,
        prid: item.value.produk.id,
        tglpelayanan: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
        namaproduk: item.value.produk.namaproduk,
        komponenharga: d_Komponen.value,
        hargasatuan: item.value.hargaLayanan,
        qtyproduk: item.value.jumlah,
        ruangantujuan: item.value.ruangantujuan,
        dokterOpTindakan: item.value.dokterOperatorTindakan.namalengkap,
        dokterOpId: item.value.dokterOperatorTindakan.id
    }
    console.log("DATA PUSH", data);
    detailOrderLayanan.value.push(data)
}



const update = (e: any) => {
    // console.log(e)
    let data: any = {}
    for (let x = 0; x < detailOrderLayanan.value.length; x++) {
        const element = detailOrderLayanan.value[x];
        if (element.no == e.no) {
            data.no = element.no
            data.qtyproduk = e.jumlah
            data.hargasatuan = e.hargaLayanan
            data.komponenharga = d_Komponen.value
            data.prid = e.produk.id
            data.tglpelayanan = moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
            data.namaproduk = e.produk.namaproduk
            data.ruangantujuan = e.ruangantujuan
            data.dokterOpTindakan = e.dokterOperatorTindakan.namalengkap,
                data.dokterOpId = e.dokterOperatorTindakan.id

            detailOrderLayanan.value[x] = data
        }
    }
    clear()

}
const edit = (e: any) => {
    item.value.no = e.no
    d_Produk.value.forEach(element => {
        if (element.id == e.prid) {
            item.value.produk = element
            return
        }
    });
    d_Komponen.value = e.komponenharga
    item.value.hargaLayanan = e.hargasatuan
    item.value.jumlah = e.qtyproduk
}

const fetchdDropdown = async () => {
    const response = await useApi().get(`/dashboard/list-bedah`)
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_Dokter.value = response.namalengkap.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
    })
    d_Kamar.value = response.kamaroperasi

    useApi().get(
        `/bedah/list-dropdown`).then((response: any) => {
            d_JenisOperasi.value = response.jenisOperasi.map((e: any) => { return { label: e.jenisoperasi, value: e.id } })
        })
}

const changePeriode = () => {

    fetchDataOrder(0)
    fetchLaporan()
}

const exportExcel = () => {
    remakeData.value = dataLaporan.value.map((e: any) => {
        return {
            NamaPasien: e.namapasien, AsalRuangan: e.asalruangan, Tindakan: e.namaproduk,
            tgloperasi: e.tgloperasi, dokterPemeriksa: e.dokterpemeriksa, Harga: e.hargasatuan, Total: e.total,
        }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    const _url = window.URL.createObjectURL(data)
    // window.open(_url, EXCEL_EXTENSION).focus();
    window.open(_url, EXCEL_EXTENSION).focus()
    exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}
const cetakSEP = (e: any) => {
    qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
        'SEP', 1)
}

async function loadDrop() {
    const response = await useApi().get(`/dashboard/registrasi/dropdown`)
    d_Ruangans.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
}

const changeRuang = (e: any) => {
    for (let x = 0; x < d_Ruangan.value.length; x++) {
        const element: any = d_Ruangan.value[x];
        if (e == element.value) {
            item.value.namaruangan = element.label
            break
        }
    }
    fetchDataOrder(0)
    fetchDetail()
    fetchJadwal()
}

const emr = (e: any) => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.pd_norec,
            norec_apd: e.norec_apd
        }
    })
}
// const fetchPetugas = async () => {
//     await useApi().get(
//         `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap`
//     ).then((response) => {
//         d_Petugas.value = response
//     })
// }

const changeJenis = async (e: any) => {

    // if (!e.jenisPelaksana) {
    //     H.alert('warning', 'Jenis pelaksana wajib dipilih')
    //     return
    // }
    isLoadChange.value = true
    await useApi().get('/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e.jenisPelaksana).then((response: any) => {
        if (response != null) {
            e.d_Pegawai = response.map((e: any) => {
                return {
                    label: e.namalengkap, value: e.id
                }
            })
        } else {
            e.d_Pegawai = []
        }
    })
    isLoadChange.value = false
}

function dropdownList() {
    useApi().get(`tindakan/list-jenis-petugas`).then((response: any) => {
        d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
        item.nilaiCito = response.cito != null ? parseFloat(response.cito) : 1
        // console.log(response.jenispetugaspelaksana)
        for (let z = 0; z < listItem.value.length; z++) {
            const elementz = listItem.value[z];
            for (let x = 0; x < response.jenispetugaspelaksana.length; x++) {
                const element = response.jenispetugaspelaksana[x];
                if (element.jenispetugaspe.toLowerCase().indexOf('pemeriksa') > -1) {
                    elementz.jenisPelaksana = element.id
                    changeJenis(elementz)
                    break
                }
            }
        }
    })
}

const addNewItem = () => {
    listItem.value.push({
        jenisPelaksana: null,
        pegawai: [],
    });
}
const removeItem = (index: any) => {
    listItem.value.splice(index, 1)
}
watch(
    () => [
        order.value
    ], () => {
        changeSwitch(order.value)
    }
)
watch(
    () => item.value.filterTgl,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            fetchDataOrder(0)
        }
    }
)


fetchDataOrder(0)
fetchLaporan()
fetchDetail()
fetchJadwal()
fetchdDropdown()
loadDrop()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';

.label-unset {
    text-overflow: unset !important;
}

.search-menu {
    height: 56px;
    white-space: nowrap;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    background-color: white;
    border-radius: 8px;
    width: 100%;
    padding-left: 0.75rem;

    >div:not(:last-of-type) {
        border-right: 1px solid var(--search-border-color);
    }

    .search-bar {
        height: 55px;
        width: 100%;
        position: relative;
        display: flex;
        align-items: center;
        padding-right: 1.5rem;

        .field {
            width: 100%;
        }

        .multiselect-tags {
            padding-left: 2.5rem;
        }
    }

    .search-location,
    .search-job,
    .search-salary {
        display: flex;
        align-items: center;
        width: 50%;
        font-size: 14px;
        font-weight: 500;
        padding: 0 25px;
        height: 100%;
        font-family: var(--font);

        input {
            width: 100%;
            height: 90%;
            display: block;
            font-family: var(--font);
            color: var(--input-color);
            background-color: transparent;
            border: none;
        }

        svg {
            margin-right: 0.5rem;
            width: 18px;
            color: var(--primary);
            flex-shrink: 0;
        }
    }

    .search-button {
        background-color: var(--primary);
        min-width: 100px;
        height: 56px;
        border: none;
        font-weight: 500;
        font-family: var(--font);
        padding: 0 1rem;
        border-radius: 0 0.75rem 0.75rem 0;
        color: white;
        cursor: pointer;
        margin-left: auto;
    }
}
</style>
