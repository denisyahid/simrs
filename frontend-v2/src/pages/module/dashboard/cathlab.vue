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
                                    CathLab
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
                                   <VTag 
                                        @click="showModalFilter()" 
                                        color="danger" 
                                        rounded 
                                        elevated
                                        style="position: relative; bottom: -1.5rem; cursor:pointer" 
                                        :style="styleFilter">
                                        {{
                                            (item.periode && item.periode.start) ? 
                                                (H.formatDateToLocalString(item.periode.start) === H.formatDateToLocalString(item.periode.end) 
                                                    ? H.formatDateToLocalString(item.periode.start) 
                                                    : H.formatDateToLocalString(item.periode.start) + ' - ' + 
                                                    (item.periode.end ? H.formatDateToLocalString(item.periode.end) : ''))
                                                : 'Tanggal Tidak Tersedia'
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
                    <VTabs @update:selected="changeMenu($event)" slider selected="Pasien" :tabs="[
                        { label: 'Amprahan Operasi', value: 'Pasien' },
                        { label: 'Billing Operasi', value: 'Operasi' },
                        { label: 'Penjadwalan Tindakan', value: 'Laporan' },
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
                                                v-model="item.search" v-on:keyup.enter="fetchDataOrder(order)" />
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
                            <p v-else-if="activeValue === 'Operasi'">
                                <div class="list-view list-view-v3">

                                    <Vcard>
                                        <div class="search-menu mb-2">
                                            <div class="search-location" style="width: 100%">
                                                <i class="iconify" data-icon="feather:search"></i>
                                                <input type="text"
                                                    placeholder="Cari Nama Pasien, No Registrasi atau No RM"
                                                    v-model="item.qsearch" v-on:keyup.enter="fetchOperasi()" />
                                            </div>
                                            <div class="search-salary">
                                                <i class="iconify" data-icon="feather:clipboard"></i>
                                                <input type="text" placeholder="No RM" v-model="item.qnocm" />
                                            </div>
                                              <div class="column is-2">
                                                        <VField>
                                        <VControl class="prime-auto">
                                        <Calendar inputId="range" v-model="item.qPeriode" selectionMode="range" :manualInput="false" class="w-100" :showIcon="true" />
                                        </VControl>
                                    </VField>
                                        </div>
                                            <VButton raised class="search-button" @click="fetchOperasi(order)"
                                                :loading="isLoading"> Cari Data
                                            </VButton>
                                        </div>
                                    </Vcard>

                                    <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                        class="my-6" :class="[dataOrder.length !== 0 && 'is-hidden']">
                                        <template #image>
                                            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                            <img class="dark-image"
                                                src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                        </template>
                                    </VPlaceholderPage>
                                    <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                                        <!-- <VCard class="px-1 mx-1 py-0"> -->
                                        <DataTable
                                            :value="dataPasien"
                                            class="p-datatable-md"
                                            :loading="isLoading"
                                            rowGroupMode="subheader"
                                            groupRowsBy="namaruangan"
                                            sortField="namaruangan"
                                            sortMode="single"
                                            scrollable
                                            :metaKeySelection="metaKey" 
                                            selectionMode="single"
                                            scrollHeight="600px"
                                            tableStyle="min-width: 50rem"
                                            @rowSelect="onRowSelect"
                                            v-model:selection="selectedPasien"
                                            :totalRecords="dataPasien.total"
                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                            responsiveLayout="stack" breakpoint="960px"
                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
                                            >

                                            <template #empty>
                                                <VPlaceholderPage
                                                    title="Tidak Ada Pasien Hari Ini."
                                                    subtitle="Silakan Pilih Tanggal untuk melihat Data Pasien" larger>
                                                    <template #image>
                                                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                                                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                            alt="" />
                                                    </template>
                                                </VPlaceholderPage>
                                            </template>
                                            <template #loading>
                                                <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
                                                <p style="color:white">Loading data, please wait...</p>
                                            </template>
                                            <Column header="Action" style="width: 100px; text-align: center">
                                                <template #body="slotProps">
                                                    <VIconButton color="primary"  circle icon="lucide:shopping-cart" outlined raised class="text-center"
                                                    @click="inputTindakan(slotProps.data)" v-tooltip.bottom.left="'Tindakan'"></VIconButton>
                                                </template>
                                            </Column>
                                            <!-- <VIcon icon="lucide:shopping-cart" /> -->
                                            <Column header="Periksa" style="width: 100px; text-align: center">
                                                <template #body="slotProps">
                                                    <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised class="text-center"
                                                    @click="emr(slotProps.data)" v-tooltip.bottom.left="'EMR'"></VIconButton>
                                                </template>
                                            </Column>
                                            <!-- <Column header="Pindah" style="width: 100px; text-align: center">
                                                <template #body="slotProps">
                                                    <div class="buttons">
                                                        <RouterLink :to="{
                                                            name: 'module-rawat-inap-pindah-pulang',
                                                            query: {
                                                                nocmfk: slotProps.data.nocmfk,
                                                                norec_pd: slotProps.data.norec_pd,
                                                                departemenfk: slotProps.data.objectdepartemenfk,
                                                            }
                                                        }">
                                                            <VIconButton v-tooltip.bottom.left="'Pulang atau Pindah'"
                                                                color="danger" circle icon="fas fa-home" />
                                                        </RouterLink>
                                                    </div>
                                                </template>
                                            </Column> -->

                                            <template #groupheader="slotProps">
                                                <div class="flex items-center gap-2">
                                                    <span style="font-weight: bold; font-size: 16px;">
                                                        {{ slotProps.data.namaruangan }}
                                                    </span>
                                                </div>
                                            </template>
                                            <Column field="noregistrasi" header="No Reg" :sortable="true" style="width: 130px"></Column>
                                            <Column field="namapasien" header="Nama Pasien" :sortable="true" style="width: 190px">
                                                <template #body="slotProps">
                                                    <span>{{  slotProps.data.namapasien + ' - ' + slotProps.data.kebangsaan   }}</span>
                                                </template>
                                            </Column>
                                            <Column field="nocm" header="No RM" :sortable="true" style="width: 120px"></Column>
                                            <Column field="jeniskelamin" header="JK" :sortable="true" style="width: 90px"></Column>
                                            <Column field="namaruangan" header="Ruangan" :sortable="true" style="width: 190px"></Column>    
                                        </DataTable>
                                        <!-- </VCard> -->
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
                                        :loading="isLoading"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} showGridlines ">
                                        <!-- <Column :exportable="false" header="#">
                                            <template #body="slotProps">
                                                <VIconButton type="button" icon="pi pi-bookmark" class="mr-3" color="info"
                                                    circle outlined raised v-tooltip.top="'Detail'"
                                                    @click="edit(slotProps.data)">
                                                </VIconButton>
                                            </template>
                                        </Column> -->
                                        <Column field="noorder" header="No Order"></Column>
                                        <Column field="jamoperasi" header="Jam Operasi"></Column>
                                         <Column field="kamaroperasi" header="Ruang OK"></Column>
                                        <Column field="namapasien" header="Nama Pasien" :sortable="true"></Column>
                                        <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true"></Column>
                                        <Column field="umur_pasien" header="Umur" :sortable="true"></Column>
                                        <Column field="nocm" header="NO RM" :sortable="true"></Column>
                                        <Column field="tgllahir" header="Tanggal Lahir" :sortable="true"></Column>
                                        <Column field="diagnosis" header="Diagnosa Pre OP" :sortable="true"></Column>
                                        <Column field="namaproduk" header="Tindakan"></Column>
                                        <Column field="dokterpemeriksa" header="Dokter Pemeriksa"></Column>
                                        <Column field="dokteranestesi" header="Dokter Anestesi"></Column>
                                        <Column field="estimasi" header="Estimasi Jam"></Column>
                                        <Column field="kelompokpasien" header="Cara Bayar"></Column>
                                        <Column field="tgloperasi" header="Tanggal Operasi"></Column>
                                        <Column field="asalruangan" header="Asal Ruangan"></Column>
                                        <Column field="tinggibadan" header="Tinggi Badan"></Column>
                                        <Column field="beratbadan" header="Berat Badan"></Column>
                                        <Column field="riwayatswab" header="Riwayat Swab"></Column>
                                        <Column field="userpenerima" header="Petugas Verifikator"></Column>
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
                            <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks />
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
                    <VDatePicker
                        v-model="item.tgloperasi"
                        mode="dateTime"
                        style="width: 100%"
                        :format="H.formatDateIndo">
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput
                                        :value="inputValue"
                                        placeholder="Jadwal Operasi"
                                        v-on="inputEvents"
                                        class="is-rounded"/>
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
                            <AutoComplete v-model="item.dokterAnak" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="ketik nama Dokter" class="is-rounded" />
                        </VControl>
                    </VField>
                </div>
                <!-- <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Waktu Selesai Operasi </span>
                    </h6>
                </div> -->
                <!-- <div class="column is-4">
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
                </div> -->
                <!-- <div class="column is-2">
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
                    </VField>
                </div> -->
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
                            <AutoComplete v-model="item.userPengirim" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="ketik nama Dokter" class="is-rounded" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <h6 class=" is-5  mt-3">
                        <span> Rencana Tindakan </span>
                    </h6>
                </div>
                <div class="column is-4">
                    <VField>
                        <VControl class="mt-2">
                            <VInput type="text" placeholder="Rencana Tindakan (optional)" class="is-rounded"
                                v-model="item.keterangan" />
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
                            <AutoComplete v-model="item.userPenerima" :suggestions="d_pegawai"
                                @complete="fetchDokter1($event)" :optionLabel="'value'" :dropdown="true"
                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="ketik nama Perawat" class="is-rounded" />
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
                                            <AutoComplete v-model="item.dokterAnastesi" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
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
                                            <AutoComplete v-model="item.dokterOperator" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
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
                                    <!-- <div style="width: 20%;">
                                        <label :style="'font-weight: bold;'">Dokter <span
                                                style="color: #bb2124;">Anastesi</span> dan <span
                                                style="color: #5bc0de;">Operasi</span></label> -->
                                        <!-- <label>Dokter Anastesi dan Operasi </label> -->
                                        <!-- <div class="box-end" v-for="(item, index) in detailPetugas">
                                            <VTag style="margin-left: auto;" class="mt-2"
                                                :style="{ 'background-color': getColorByType(item.objectjenispetugaspefk), 'color': 'white', 'font-size': '12px' }"
                                                label="Tag Label" rounded elevated>
                                                {{ item.namalengkappetugas }}
                                            </VTag>
                                        </div>
                                    </div> -->
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
import { ref, computed, watch, reactive, onMounted } from 'vue'
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
import Calendar from 'primevue/calendar';
import MultiSelect from 'primevue/multiselect';
import Column from 'primevue/column'
import * as XLSX from "xlsx";
import * as qzService from '/@src/utils/qzTrayService'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import CardCountRev from '/@src/components/partials/widgets/stat/CardCountRev.vue'
import CardCountNoPic from '/@src/components/partials/widgets/stat/CardCountNoPic.vue'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import { Notyf } from 'notyf';
import DetailPasien from '../registrasi/detail-registrasi.vue'


useHead({
    title: 'Dashboard Cathlab - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()

const NOREC_PD = useRoute().query.nocm as string
const dataSource: any = ref([])
const dataPasien: any = ref([])
const filters = ref('')
const d_Dokter = ref([])
const d_Status: any = ref([{ value: 1, label: 'Menunggu Operasi' }, { value: 2, label: 'Sedang Operasi' }, { value: 3, label: 'Selesai Operasi' }, { value: 4, label: 'Batal Operasi' }])
const d_Dokters = ref([])
const remakeData: any = ref([])
const d_DokterOperasi = ref([])
const d_GolonganDarah = ref([])
const d_Petugas = ref([])
const d_JenisPelaksana = ref([])
const d_Komponen = ref([])
const d_Pegawai = ref([])
const d_Ruangan = ref([])
const d_JenisKelamin = ref([])
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
    periode: ref({
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


const d_pegawai: any = ref([])

const fetchDokter1 = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=100`
  ).then((response) => {
    d_pegawai.value = response
  })
}

async function fetchDokter(filter: any) {
    let query = ''
    if (filter) {
        query = filter.query
    }
    const response = await useApi().get(`/general/dokter-paging?name= ${query}&limit=10`)
    // d_Dokters.value =
    d_Dokter.value = response.dokter.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
    })
    // return response.dokter.map((item: any) => {
    //     return { value: item.id, label: item.namalengkap, default: item }
    // })
}

const fetchDataOrder = async (q: any) => {
    console.log(item.value.isTglOrder);

    if (!item.value.isTglOrder && !item.value.isTglOperasi) { 
        H.alert('warning', 'Pilih salah satu tgl order atau tgl operasi'); 
        return; 
    }
    if (item.value.isTglOrder && item.value.isTglOperasi) { 
        H.alert('warning', 'Pilih salah satu tgl order atau tgl operasi'); 
        return; 
    }

    try {
        // Construct query parameters
        let ruanganid = item.value.filterRuangan ? `&ruanganid=${item.value.filterRuangan}` : '';
        let tglAwal = item.value.periode.start 
            ? `&tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}` 
            : '';
        let tglAkhir = item.value.periode.end 
            ? `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}` 
            : '';
        let qnamapasien = item.value.qnamapasien ? `&qnamapasien=${item.value.qnamapasien}` : '';
        let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
        let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
        let statusOrder = q ? `&statusorder=${q}` : '';
        let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';

        let opsi = '';
        if (item.value.isTglOrder) opsi = 'tglorder';
        if (item.value.isTglOperasi) opsi = 'tgloperasi';

        console.log('tgl awal',tglAwal)
        console.log('tgl akhir',tglAkhir)
        isLoading.value = true;

        // Final API call with all query params
        const response = await useApi().get(
            `/dashboard/cathlab?${ruanganid}${tglAwal}${tglAkhir}${qnamapasien}${statusOrder}${qnocm}${qnoregistrasi}${search}&opsi=${opsi}`
        );

        modalFilter.value = false;

        // Process response
        response.forEach((element: any, i: number) => {
            element.no = i + 1; // Start numbering from 1
            const nameParts = element.namapasien.split(' ');
            let initials = nameParts[0].substr(0, 2);
            if (nameParts.length > 1) {
                initials += nameParts[1].substr(0, 1);
            }
            element.initials = initials;
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
};



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


async function fetchLaporan() {
    isLoading.value = true;

    let tglAwal = item.value.periode?.start 
        ? `&tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}` 
        : '';
    let tglAkhir = item.value.periode?.end 
        ? `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}` 
        : '';
    let namaproduk = item.value.namaproduk ? `&namaproduk=${item.value.namaproduk}` : '';
    let qnamapasien = item.value.qnama ? `&qnamapasien=${item.value.qnama}` : '';
    let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
    let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';

    try {
        const response = await useApi().get(
            `/dashboard/get-operasi-pasien-cathlab?${tglAwal}${tglAkhir}${namaproduk}${qnamapasien}${qnocm}${qnoregistrasi}${search}`
        );
        dataLaporan.value = response.data.map((element, i) => ({
            no: i + 1,
            norec: element.norec || '',
            noorder: element.noorder || '',
            jamoperasi: element.jamoperasi || '',
            kamaroperasi: element.kamaroperasi || '',
            namapasien: element.namapasien || '',
            jeniskelamin: element.jeniskelamin || '',
            umur_pasien: element.umur_pasien || '',
            nocm: element.nocm || '',
            tgllahir: element.tgllahir || '',
            diagnosis: element.diagnosis || '',
            namaproduk: element.namaproduk || '',
            dokterpemeriksa: element.dokterpemeriksa || '',
            dokteranastesi: element.dokteranastesi || '',
            durasi: element.durasi || '',
            kelompokpasien: element.kelompokpasien || '',
            tgloperasi: element.tgloperasi || '',
            tinggibadan: element.tinggibadan || '',
            beratbadan: element.beratbadan || '',
            riwayatswab: element.riwayatswab || '',
            userpenerima: element.userpenerima || ''
        }));
    } catch (error) {
        console.error('Error fetching laporan:', error);
    } finally {
        isLoading.value = false;
    }
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

// const getListPelayanan = async (data: any) => {
//     const response = await useApi().get(`/dashboard/get-pelayanan-bedah?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}&objectkebangsaanfk=${item.value.objectkebangsaanfk}&objectruangantujuanfk=${item.value.idRuanganTujuan}`)
//     d_Produk.value = response.map((e: any) => {
//         return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
//     })
// }


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
    modalDetailOrder.value = true
    let data = {
        'idkelas': e.objectkelasfk,
        'idJenisPelayanan': e.jenispelayananfk,
        'idRekanan': e.objectrekananfk
    }
    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.namapasien = e.namapasien
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.noorder = e.noorder
    item.value.no_rm = e.pas_nocm
    item.value.jeniskelamin = e.jeniskelamin
    item.value.kelompokpasien = e.kelompokpasien
    item.value.idRuanganTujuan = e.objectruangantujuanfk
    item.value.pdNorec = e.pd_norec
    item.value.soNorec = e.so_norec
    item.value.noregistrasi = e.noregistrasi
    item.value.objectpegawaiorderfk = e.objectpegawaiorderfk
    item.value.dokterorder = e.nama_pegawai
    item.value.tglregistrasi = e.tglregistrasi
    item.value.kelas = e.objectkelasfk
    // getListPelayanan(data)
    const getHargaLayanan = await useApi().get(`/dashboard/cathlab/get-order-layanan?strukorderfk=${e.so_norec}&objectkelasfk=${e.objectkelasfk}`)//&objectkelasfk=${e.objectkelasfk}
    // const response = await useApi().get(`/dashboard/cathlab?statusorder=${statusOrder.value}&noorder=${e.noorder}`)
    getHargaLayanan.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    detailDiagnosa.value = e.detailDiagnosa.map((r: any) => r.kddiagnosa).join(', ')

    detailOrderLayanan.value = getHargaLayanan
}

// Helper Function
const getStatusOperasi = (status: number) => {
    switch (status) {
        case 1: return { value: 1, label: "Menunggu Operasi" };
        case 2: return { value: 2, label: "Sedang Operasi" };
        case 3: return { value: 3, label: "Selesai Operasi" };
        case 4: return { value: 4, label: "Batal Operasi" };
        default: return { value: null, label: "Status Tidak Diketahui" };
    }
};


const getDetailVerify = async (e: any) => {

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

    modalDetailOrderVerify.value = true
    // const response = await useApi().get(`/dashboard/cathlab/get-order-verify?norec_so=${e.so_norec}`)
    // const diagnosa = await useApi().get(`/dashboard/cathlab?tglAwal=${e.tglregistrasi}&tglAkhir=${e.tglregistrasi}&statusorder=${statusOrder.value}&noorder=${e.noorder}`)
    detailDiagnosa.value = e.detailDiagnosa.map((r: any) => r.kddiagnosa).join(', ')
    response.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    detailOrderVerify.value = response
}
const save = async () => {
    let arrAnas = [];
    let arrOperator = [];
    if (item.value.operatorhelperfk && item.value.operatorhelperfk.length > 0) {
        item.value.operatorhelperfk.forEach(dtOp => {
            console.log('op', dtOp)
            if (dtOp) {
                arrOperator.push(dtOp.value)
            }
        });
    }

    if (item.value.anastesitambahanfk && item.value.anastesitambahanfk.length > 0) {
        item.value.anastesitambahanfk.forEach(dtAnas => {
            console.log('ana', dtAnas)
            if (dtAnas) {
                arrAnas.push(dtAnas.value)
            }
        });
    }

    if (!item.value.estimasiwaktuoperasi) {
        useToaster().warn('estimasiwaktuoperasi Tidak Boleh kosong')
        return
    }

    let parameter = {
        'idruangtujuan': item.value.idRuanganTujuan,
        'pd_norec': item.value.pdNorec,
        'tglregistrasi': item.value.tglregistrasi,
        'noregistrasi': item.value.noregistrasi,
        'so_norec': item.value.soNorec,
        'tgloperasi': item.value.tgloperasi,
        'objectkelasfk': item.value.kelas,
        'estimasiwaktuoperasi': item.value.estimasiwaktuoperasi,
        'kamaroperasifk': item.value.kamaroperasi ? item.value.kamaroperasi.id : null,
        'dokteroperatorfk': item.value.dokterOperator ? item.value.dokterOperator.value : null,
        'dokteranastesifk': item.value.dokterAnastesi ? item.value.dokterAnastesi.value : null,
        'tglselesai': item.value.tglselesai,
        'objectpegawaiorderfk': item.value.userPengirim ? item.value.userPengirim.value : null,
        'dokteranakfk': item.value.dokterAnak ? item.value.dokterAnak.value : null,
        'penerimafk': item.value.userPenerima ? item.value.userPenerima.value : null,
        'statusoperasi': item.value.statusoperasi ? item.value.statusoperasi.value : null,
        'arranastesi': arrAnas,
        'arroperator': arrOperator
    }

    isLoading.value = true;
    await useApi().post('/dashboard/cathlab/save-order-pelayanan', { 'parameter': parameter }).then((response: any) => {
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

const inputTindakan = (e: any) => {
  router.push({
    name: 'module-emr-tindakan',
    query: {
      norec_pasien_daftar: e.norec_pd,
      nocmfk: e.nocmfk,
      norec_apd: e.norec_apd
    },
  })
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
     const response = await useApi().get(`/dashboard/cathlab/get-dokter`)
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })

    d_JenisKelamin.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id } })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e.id } })
    d_Dokter.value = response.data.map((e: any) => {
        return { label: `${e.namalengkap}`, value: `${e.id}`, default: e }
    })
    item.value.filterRuangan = d_Ruangan.value[0].value
}

const changePeriode = () => {
    // if (item.value.periode.start != item.value.periode.end) {
    //     styleFilter.value = 'left:37rem;'
    // }
    fetchDataOrder(0)
    fetchLaporan()
    fetchPenunjang()
    chartLayananByRuangan()
    modalFilter.value=false
}

const initCache = () => {
    let chacePeriode = H.cacheHelper().get('cathlab');
    if (chacePeriode != undefined) {
        item.value.periode.start = new Date(chacePeriode[0]);
        item.value.periode.end = new Date(chacePeriode[1]);
    }
    // if (item.value.periode.start != item.value.periode.end) {
    //     styleFilter.value = 'left:37rem;'
    // }
}


const chartLayananByRuangan = async () => {
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
    await useApi().get(`/dashboard/cathlab/chart-layanan-ruangan?${tglAwal}${tglAkhir}`).then((res: any) => {
        item.value.chartLength = res.chartLO.count.length
        // item.value = res
        // console.log(res.chartLO.categories)
        chartLO.value = {
            series: [
                {
                    name: 'pasien',
                    data: res.chartLO.count
                }
            ],
            chart: {
                height: 220,
                type: 'bar',
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        // fontSize: '8px',
                        position: 'top', // top, center, bottom
                    },
                },
            },
            dataLabels: {
                enabled: true,
                // formatter: formatters.asPercent,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ['#304758'],
                },
            },
            xaxis: {
                categories: res.chartLO.categories,
                position: 'top',
                axisBorder: {
                    show: true,
                },
                axisTicks: {
                    show: false,
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        },
                    },
                },
                // tooltip: {
                //     enabled: true,
                // },
            },
            yaxis: {
                axisBorder: {
                    show: true,
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    // formatter: formatters.asPercent,
                },
            },
            colors: [themeColors.green, themeColors.secondary, themeColors.orange],
            title: {
                text: 'Pelayanan Berdasarkan Ruangan',
                align: 'left',
            },
        }
    })

}
const exportExcel = () => {
    remakeData.value = dataLaporan.value.map((e: any) => {
        return {
            jamoperasi: e.jam, ruangantujuan: e.RuangOK, NamaPasien: e.namapasien, NamaPasien: e.namapasien, AsalRuangan: e.asalruangan, Tindakan: e.namaproduk,
            tgloperasi: e.tgloperasi, jamoperasi: e.jamoperasi, dokterPemeriksa: e.dokterpemeriksa, Harga: e.hargasatuan, Total: e.total,
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

// const changeRuang = async (e: any) => {
//     for (let x = 0; x < d_Ruangan.value.length; x++) {
//         const element: any = d_Ruangan.value[x];
//         if (e == element.value) {
//             item.value.namaruangan = element.label
//             break
//         }
//     }
//     await fetchDataOrder(0)
//     fetchDetail()
//     fetchJadwal()
// }

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

const fetchPetugas = async () => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap`
    ).then((response) => {
        d_Petugas.value = response;
    })
}

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

const fetchOperasi = async () => {
  try {
    let ruanganid = item.value.filterRuangan ? `&ruanganid=${item.value.filterRuangan}` : '';
    let qnamapasien = item.value.qnamapasien ? `&qnamapasien=${item.value.qnamapasien}` : '';
    let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
    let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';

    let dari = '';
    let sampai = '';
    if (item.value.qPeriode) {
       if (item.value.qPeriode[0]) {
        dari = `&dari=${H.formatDate(item.value.qPeriode[0], 'YYYY-MM-DD')}`;
      }
      if (item.value.qPeriode[1]) {
        sampai = `&sampai=${H.formatDate(item.value.qPeriode[1], 'YYYY-MM-DD')}`;
      } else {
        sampai = `&sampai=${H.formatDate(item.value.qPeriode[0], 'YYYY-MM-DD')}`;
      }
    }

    isLoading.value = true;

    const response = await useApi().get(
      `/dashboard/get-operasi-pasien-cathlab?${qnamapasien}${qnocm}${qnoregistrasi}${search}${ruanganid}${dari}${sampai}`
    );

    console.log('response Operasi', response.data);

    response.data.forEach((element, i) => {
      element.no = i + 1;
      let ini = element.namapasien.split(' ');
      let init = element.namapasien.substr(0, 2);
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1);
      }
      element.initials = init;
      element.ruanganasal = element.asal_ruangan;
      element.tglRegistrasi = moment(element.tglregistrasi).format('YYYY-MM-DD');
    });

    dataPasien.value = response.data;
    isData.value = response.data.length;
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    isLoading.value = false;
  }
};


const changeMenu = (dt: any) => {
    console.log("data selected", dt);
    switch (dt) {
        case "Pasien":
            fetchDataOrder(0)
            break;

        case "Operasi":
            isLoading.value = true;
            fetchOperasi()
            break;

        case "Laporan":
            fetchLaporan()
            break;
    
        default:
            break;
    }
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


onMounted(() => {

    fetchDetail()
    fetchJadwal()
    fetchdDropdown()
})

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

.p-datatable.p-component {
    .p-datatable-wrapper {
        height: 1000px;
    }
}

</style>
