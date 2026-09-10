<template>
    <div>
        <FloatingButton @click="showPasienLama()" />
        <ConfirmDialog group="positionDialog"></ConfirmDialog>

        <div class="business-dashboard hr-dashboard">
            <div class="columns">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="illustration-header-2 large-screen">
                                <div class="header-image">
                                    <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                                        style="max-width:75%; margin-left: 2rem; margin-top: 0.5rem;" />
                                </div>

                                <div class="header-meta" style="margin-left : -7rem;">
                                    <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Pelayanan
                                        Jenazah
                                    </h3>
                                    <p>
                                        Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                                    </p>
                                    <VControl>
                                        <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan"
                                            class="f-text" placeholder="Filter ruangan" :searchable="true"
                                            autocomplete="off" @select="changeRuang(item.filterRuangan)" />
                                    </VControl>
                                    <VTag @click="showModalFilter()" color="danger" rounded elevated
                                        style="position: relative; bottom: -1.5rem; cursor:pointer">{{
                                            H.formatDateToLocalString(item.periode.start) ==
                                            H.formatDateToLocalString(item.periode.end) ?
                                            H.formatDateToLocalString(item.periode.start) :
                                            H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
                                                H.formatDateToLocalString(item.periode.end) : '')
                                        }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="column is-12" style="margin-top: 2rem;">
                        <Badge :value="dataOrder.total" v-if="dataOrder.total > 0" severity="danger"
                            style="z-index: 6;top: 0px;position: relative;left: 20rem;" />
                        <VTabs class="slider-lab" slider selected="Pasien" :tabs="[
                            { label: 'Daftar Order', value: 'Pasien' },
                            { label: 'Pasien Forensik & Modikolegal', value: 'Penunjang' },
                            { label: 'Daftar Pasien Registrasi', value: 'PasienDaftar' },
                        ]" style="margin-top: -2rem;">
                            <template #tab="{ activeValue }">
                                <p v-if="activeValue === 'Pasien'">
                                <div class="list-view list-view-v3">

                                    <div class="search-menu mb-2">
                                        <div class="search-location" style="width: 100%">
                                            <i class="iconify" data-icon="feather:search"></i>
                                            <input type="text"
                                                placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                                                v-model="item.search" v-on:keyup.enter="fetchDataOrder()" />
                                        </div>

                                        <VButton raised class="search-button" @click="fetchDataOrder(order)"
                                            :loading="isLoading"> Cari Data
                                        </VButton>
                                    </div>

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
                                            <div v-for="(items, i) in dataOrderFiltered" :key="i" class="list-view-item">
                                                <div class="list-view-item-inner">
                                                    <VAvatar size="small" style="left: 8px;top: 4px;" :color="listColor[i]"
                                                        :initials="items.initials" />
                                                    <div class="meta-left">
                                                        <h3>
                                                            {{ items.namapasien }} | {{ items.pas_noidentitas }} | {{
                                                                items.pas_nocm }} <i
                                                                :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                                                aria-hidden="true"
                                                                :style="'color:' + (items.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                                        </h3>
                                                        <span>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:map-pin"></i>
                                                            <span>{{ items.asal_ruangan }}</span>
                                                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:clock"></i>
                                                            <span>{{ items.tglorder }}</span>
                                                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:check-circle"></i>
                                                            <span>{{ items.noregistrasi }}</span>
                                                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:calendar"></i>
                                                            <span>{{ items.tgllahir }}</span>
                                                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:calendar"></i>
                                                            <span>{{ items.umur }}</span>

                                                        </span>
                                                        <div>
                                                            <span style="font-weight: bold;">DPJP :
                                                                {{ items.nama_pegawai ? items.nama_pegawai : '-' }}
                                                            </span>
                                                        </div>
                                                        <VTag color="warning" rounded v-if="items.statusorder == 0">Pending
                                                        </VTag>
                                                        <VTag color="info" rounded v-if="items.statusorder == 1">
                                                            Terverifikasi</VTag>
                                                        <VTag color="primary" rounded v-if="items.statusorder == 2">Selesai
                                                        </VTag>
                                                        <VTag class="ml-5" color="danger" rounded v-if="items.iscito">Cito
                                                        </VTag>
                                                        <VTag :label="'SEP : ' + items.nosep" v-if="items.nosep != null"
                                                            :color="'success'" class='ml-2' />
                                                    </div>
                                                    <div class="meta-right">

                                                        <VIconButton v-tooltip.bottom.left="'Verifikasi'"
                                                            label="Bottom Left" color="success" circle
                                                            icon="pi pi-check-circle"
                                                            v-if="items.statusorder == null || items.statusorder == 0"
                                                            @click="orderVerify(items)" :loading="item.loading"
                                                            style="margin-right: 15px;" />


                                                        <!-- <VButton color="primary" raised style="margin-top: 11px;"
                                                        v-if="items.statusorder == 0" @click="orderVerify(items)">
                                                        Verifikasi <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                    </VButton> -->
                                                        <VIconButton v-tooltip.bottom.left="'Detail Order'"
                                                            label="Bottom Left" color="primary" circle icon="pi pi-book"
                                                            v-else-if="items.statusorder == 1"
                                                            @click="getDetailVerify(items)" style="margin-right: 15px;" />

                                                    </div>
                                                </div>
                                            </div>
                                        </TransitionGroup>
                                    </div>
                                </div>
                                 <div class="column is-12 p-0">
                                  <VFlexPagination v-model:current-page="currentPageOrder.page" :item-per-page="currentPageOrder.limit"
                                    :total-items="dataOrder.total" :max-links-displayed="5">
                                    <template #before-pagination>
                                    </template>
                                    <template #before-navigation>
                                      <VFlex class="mr-4 mt-1" column-gap="1rem">
                                        <VField>

                                        </VField>
                                        <VField>
                                          <VControl>
                                            <div class="select is-rounded">
                                              <select v-model="currentPageOrder.limit">
                                                <option :value="1">1 results per page</option>
                                                <option :value="5">5 results per page</option>
                                                <option :value="10">10 results per page</option>
                                                <option :value="15">15 results per page</option>
                                                <option :value="25">25 results per page</option>
                                                <option :value="50">50 results per page</option>
                                              </select>
                                            </div>
                                          </VControl>
                                        </VField>
                                      </VFlex>
                                    </template>
                                  </VFlexPagination>
                                </div>
                                </p>
                                <p v-else-if="activeValue === 'Penunjang'">
                                <div class="list-view list-view-v3">
                                    <div class="search-menu mb-2">
                                        <div class="search-location" style="width: 100%">
                                            <i class="iconify" data-icon="feather:search"></i>
                                            <input type="text"
                                                placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                                                v-model="item.qsearch" v-on:keyup.enter="fetchPenunjang()" />
                                        </div>
                                        <VButton raised class="search-button" @click="fetchPenunjang()"
                                            :loading="isLoading">
                                            Cari Data
                                        </VButton>
                                    </div>

                                    <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                        class="my-6" :class="[dataPenunjang.length !== 0 && 'is-hidden']">
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
                                            <div v-for="(items, i) in dataPenunjang" :key="i" class="list-view-item">
                                                <div class="list-view-item-inner">

                                                    <div class="meta-left">
                                                        <h3>
                                                            {{ items.namapasien }} | {{ items.noregistrasi }} | {{
                                                                items.nocm }} | {{ items.kelompokpasien }} <i
                                                                :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                                                aria-hidden="true"
                                                                :style="'color:' + (items.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                                        </h3>
                                                        <span>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:map-pin"></i>
                                                            <span>Tgl Regis : {{ items.tglregistrasi }}</span>
                                                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>

                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:map-pin"></i>
                                                            <span>{{ items.ruanganasal }}</span>
                                                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:check-circle"></i>
                                                            <span>{{ items.noregistrasi }}</span>
                                                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:calendar"></i>
                                                            <span>{{ items.tgllahir }}</span>
                                                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:calendar"></i>
                                                            <span>Umur : {{ items.umur }}</span>

                                                        </span>
                                                        <div>
                                                            <span style="font-weight: bold;">DPJP :
                                                                {{ items.dokter ? items.dokter : '-' }}
                                                            </span>
                                                        </div>
                                                        <VTag color="warning" rounded
                                                            v-if="items.status == 'Belum Diambil'"> Belum Diambil
                                                        </VTag>
                                                        <VTag color="info" rounded v-if="items.status == 'Sudah Diambil'">
                                                            Sudah Diambil</VTag>
                                                    </div>
                                                    <div class="meta-right">
                                                        <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                                            @click="PengkajianMedis(items)" v-tooltip.bottom.left="'EMR'" style="margin-right: 15px;">
                                                        </VIconButton>
                                                        <VIconButton v-tooltip.bottom.left="'Pengambilan Jenazah'"
                                                            label="Bottom Left" color="warning" circle
                                                            icon="pi pi-check-circle" @click="jenazahAmbil(items)"
                                                            :loading="item.loading" style="margin-right: 15px;" />

                                                        <VIconButton v-tooltip.bottom.left="'Rincian'" label="Bottom Left"
                                                            color="info" outlined circle icon="pi pi-arrow-right"
                                                            @click="transaksiPelayanan(items)" :loading="item.loading" />


                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </TransitionGroup>
                                          <div class="column is-12">
                                            <VFlexPagination v-model:current-page="currentPenunjang.page"
                                              :item-per-page="currentPenunjang.limit" :total-items="dataPenunjang.total"
                                              :max-links-displayed="5">
                                              <template #before-pagination>
                                              </template>
                                              <template #before-navigation>
                                                <VFlex class="mr-4 mt-1" column-gap="1rem">
                                                  <VField>
                                                  </VField>
                                                  <VField>
                                                    <VControl>
                                                      <div class="select is-rounded">
                                                        <select v-model="currentPenunjang.limit">
                                                          <option :value="1">1 results per page</option>
                                                          <option :value="5">5 results per page</option>
                                                          <option :value="10">10 results per page</option>
                                                          <option :value="15">15 results per page</option>
                                                          <option :value="25">25 results per page</option>
                                                          <option :value="50">50 results per page</option>
                                                        </select>
                                                      </div>
                                                    </VControl>
                                                  </VField>
                                                </VFlex>
                                              </template>
                                            </VFlexPagination>
                                          </div>
                                    </div>
                                </div>


                                </p>
                                <p v-else-if="activeValue == 'PasienDaftar'">
                                <div class="list-view list-view-v3">
                                    <div class="search-menu mb-2">
                                        <div class="search-location" style="width: 100%">
                                            <i class="iconify" data-icon="feather:search"></i>
                                            <input type="text"
                                                placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                                                v-model="item.rsearch" v-on:keyup.enter="fetchPasien()" />
                                        </div>

                                        <VButton raised class="search-button" @click="fetchPasien()" :loading="isLoading">
                                            Cari
                                            Data
                                        </VButton>
                                    </div>

                                    <VPlaceholderPage :class="[dataPasien.length !== 0 && 'is-hidden']"
                                        title="Tidak Ada Pasien Hari Ini."
                                        subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                                        <template #image>
                                            <img class="light-image"
                                                src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                                            <img class="dark-image"
                                                src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                        </template>
                                    </VPlaceholderPage>
                                    <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                                        <div name="list-complete" tag="div">
                                            <div v-for="(item, rowIndex) in dataPasien" :key="rowIndex">
                                                <div v-if="rowGroupMetadata[item.namaruangan].index === rowIndex">
                                                    <span
                                                        style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                                                            item.namaruangan }}</span>
                                                    <Badge :value="rowGroupMetadata[item.namaruangan].size"
                                                        v-if="rowGroupMetadata[item.namaruangan].size > 0"
                                                        class="ml-2 mt-2-min" />

                                                </div>

                                                <div class="list-view-item ">
                                                    <div class="list-view-item-inner">
                                                        <VAvatar size="small" picture="/images/avatars/svg/pasien.svg"
                                                            color="primary" bordered />
                                                        <div class="meta-left">
                                                            <h3>
                                                                {{ item.namapasien }} | {{ item.noregistrasi }} | {{
                                                                    item.nocm }} | <i
                                                                    :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                                                    aria-hidden="true"
                                                                    :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                                            </h3>
                                                            <span>
                                                                <i aria-hidden="true" class="iconify"
                                                                    data-icon="feather:map-pin"></i>
                                                                <span>Tgl Regis : {{ item.tglregistrasi }}</span>
                                                                <i aria-hidden="true"
                                                                    class="fas fa-circle icon-separator"></i>

                                                                <i aria-hidden="true" class="iconify"
                                                                    data-icon="feather:map-pin"></i>
                                                                <span>{{ item.namaruangan }}</span>
                                                                <i aria-hidden="true"
                                                                    class="fas fa-circle icon-separator"></i>

                                                                <i aria-hidden="true" class="iconify"
                                                                    data-icon="feather:calendar"></i>
                                                                <span>Tgl Meninggal : {{ item.tglmeninggal }}</span>
                                                                <i aria-hidden="true"
                                                                    class="fas fa-circle icon-separator"></i>
                                                                <i aria-hidden="true" class="iconify"
                                                                    data-icon="feather:calendar"></i>
                                                                <span>Umur : {{ item.umur }}</span>
                                                            </span>
                                                        </div>
                                                        <div class="meta-right">
                                                            <div class="buttons">

                                                                <!-- <VIconButton color="warning" circle icon="feather:book"
                                                                    raised @click="orderPermohonan(item)"
                                                                    v-tooltip.bottom.left="'Permohonan Pemulasaraan jenazah'">
                                                                </VIconButton> -->

                                                                <VIconButton color="danger" circle icon="feather:x" raised
                                                                    @click="saveBatalMeninggal(item)"
                                                                    v-tooltip.bottom.left="'Batal Meninggal'" v-if="item.tglmeninggal != null">
                                                                </VIconButton>

                                                                <VIconButton color="primary" circle
                                                                    icon="fas fa-stethoscope" raised @click="emr(item)"
                                                                    v-tooltip.bottom.left="'EMR'">
                                                                </VIconButton>

                                                                <!-- <VIconButton v-tooltip.bottom.left="'Transaksi Pelayanan'"
                                                                    label="Bottom Left" color="info" circle
                                                                    icon="pi pi-arrow-right" @click="daftar(item)"
                                                                    :loading="item.loading" /> -->

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                  <VFlexPagination v-model:current-page="currentPasienDaftar.page"
                                    :item-per-page="currentPasienDaftar.limit" :total-items="dataPasien.total"
                                    :max-links-displayed="5">
                                    <template #before-pagination>
                                    </template>
                                    <template #before-navigation>
                                      <VFlex class="mr-4 mt-1" column-gap="1rem">
                                        <VField>
                                        </VField>
                                        <VField>
                                        <VControl>
                                          <div class="select is-rounded">
                                            <select v-model="currentPasienDaftar.limit">
                                              <option :value="3">3 results per page</option>
                                              <option :value="6">6 results per page</option>
                                              <option :value="9">9 results per page</option>
                                              <option :value="15">15 results per page</option>
                                              <option :value="30">30 results per page</option>
                                              <option :value="60">60 results per page</option>
                                            </select>
                                          </div>
                                        </VControl>
                                        </VField>
                                      </VFlex>
                                    </template>
                                  </VFlexPagination>
                                </div>
                                </p>
                            </template>
                        </VTabs>
                    </div>
                </div>

            </div>
        </div>

        <VModal :open="modalDetailOrder" title="Verifikasi Order" noclose size="big" actions="right"
            @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
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
                                        <p class="block-text">
                                            {{ item.noregistrasi + (item.jeniskelamin ==
                                                'PEREMPUAN' ? ' (P)'
                                                :
                                                ' (L)')
                                            }}</p>
                                    </div>
                                </div>
                                <div class="center">
                                    <div class="columns">
                                        <div class="column">
                                            <h4 class="block-heading">No. Order</h4>
                                            <p class="block-text">{{ item.noorder }}</p>
                                            <h4 class="block-heading">Tgl Registrasi</h4>
                                            <p class="block-text">{{ item.tglregistrasi }}</p>
                                        </div>
                                        <div class="column">
                                            <h4 class="block-heading">Diagnosa</h4>
                                            <p class="block-text">
                                                {{ item.namadiagnosa ? item.namadiagnosa : 'Belum Ada Diagnosa' }}</p>
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
                                            <h4 class="block-heading">Ruangan Asal</h4>
                                            <p class="block-text">{{ item.ruanganasal }}</p>
                                            <h4 class="block-heading">Ruangan Tujuan</h4>
                                            <p class="block-text">{{ item.ruangantujuan }}</p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="column is-12 p-4 mt-5">
                    <Fieldset legend="Tambah Tindakan" :toggleable="true">
                        <div class="columns">
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
                                                    showClear :filter="true" @change="changeTindakan(item.produk)" />

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
                                    <div class="column is-2">
                                        <VField label="Jumlah">
                                            <VControl icon="lnir lnir-repeat-one">
                                                <VInput type="text" v-model="item.jumlah" placeholder="Jumlah"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <VField class="mt-5">
                                            <VControl class="is-flex">
                                                <VLabel raw class="remember-toggle">
                                                    <VInput raw type="checkbox" v-model="item.iscito"
                                                        :checked="item.iscito == 'true' || item.iscito == true ? true : false"
                                                        @change="handleChange($event.target.checked)" />

                                                    <span class="toggler">
                                                        <span class="active">
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:check"></i>
                                                        </span>
                                                        <span class="inactive">
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:circle"></i>
                                                        </span>
                                                    </span>
                                                </VLabel>
                                                <VLabel raw class="remember-me">Cito</VLabel>

                                            </VControl>
                                        </VField>
                                        <!-- <VField class="mt-4">
                                        <VControl>
                                            <VSwitchBlock v-model="item.iscito" label="Cito" color="danger" />
                                        </VControl>
                                    </VField> -->

                                    </div>
                                    <div class="columns" style="margin-left:8px; margin-top:30px">
                                        <VIconButton v-tooltip.bottom.left="'Update Data'" icon="feather:edit"
                                            v-if="item.no && d_Komponen.length" @click="update(item)" color="warning" raised
                                            circle class="mr-2">
                                        </VIconButton>
                                        <VIconButton v-tooltip.bottom.right="'Tambah Data'" icon="fas fa-plus"
                                            v-else-if="!item.no && d_Komponen.length" @click="add(), clear()" color="info"
                                            raised circle>
                                        </VIconButton>
                                        <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash" @click="clear()"
                                            color="danger" raised circle style="margin-left:10px">
                                        </VIconButton>
                                    </div>


                                </div>
                            </div>
                        </div>

                    </Fieldset>
                </div>
                <div class="column is-12">
                    <Fieldset legend="Data Order Tindakan" :toggleable="true">
                        <div class="column" v-for="(data) in 3" style="text-align:center" v-if="isLoadDataOrder">
                            <div class="columns is-multiline">
                                <div class="column is-2" style="margin-top: 27px;">
                                    <VPlaceload class="mx-2" />
                                </div>
                                <div class="column">
                                    <VPlaceloadText :lines="4" width="75%" last-line-width="20%" />
                                </div>

                            </div>
                        </div>
                        <div class="timeline-wrapper" v-else>
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
                                                <div class="box-text" style="width: 80%">
                                                    <div class="meta-text">
                                                        <p>
                                                            <span>{{ items.namaproduk }}</span>
                                                        </p>
                                                        <table class="tb-order">
                                                            <tr>
                                                                <td>Harga </td>
                                                                <td>:</td>
                                                                <td class="font-values">{{ H.formatRp(items.hargasatuan,
                                                                    'Rp. ')
                                                                }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Jumlah</td>
                                                                <td>:</td>
                                                                <td>{{ items.qtyproduk }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cito</td>
                                                                <td>:</td>
                                                                <td>{{ items.nilaiStatusCito ? 'Ya' : '' }} </td>
                                                            </tr>


                                                        </table>

                                                    </div>
                                                </div>
                                                <div class="box-end" style="width: 30%">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-6" style="margin-top: 0.5rem;">
                                                            <VIconButton v-tooltip.bottom.left="'Edit'" icon="feather:edit"
                                                                @click="edit(items)" color="warning" raised circle
                                                                class="mr-2">
                                                            </VIconButton>
                                                            <VIconButton v-tooltip.bottom.right="'Hapus'"
                                                                icon="feather:trash" @click="hapusItems(items)"
                                                                color="danger" raised circle>
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
                    </Fieldset>
                </div>

                <div class="column is-12" style="padding: 2rem 6rem;">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField label="Dokter Order">
                                <VControl class="mt-2">
                                    <VInput type="text" placeholder="Dokter Order" readonly class="is-rounded"
                                        v-model="item.dokterorder"
                                        style="cursor:pointer text-align: center;background: var(--fade-grey);" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Dokter Verifikator" class="is-rounded-select is-autocomplete-select">
                                <VControl icon="fa:user-md" class="prime-auto-cus">
                                    <AutoComplete v-model="item.dokterVerif" :suggestions="d_Dokter" :optionLabel="'label'"
                                        @complete="fetchDokter($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter..."
                                        class="mt-2 is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Petugas Verifikator" class="is-rounded-select is-autocomplete-select">
                                <VControl icon="fa:user" class="prime-auto-cus ">
                                    <AutoComplete v-model="item.pegawaiVerif" :suggestions="d_Pegawai"
                                        :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Petugas..." class="mt-2  is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField label="Catatan">
                                <VControl class="mt-2">
                                    <VTextarea rows="2" placeholder="Tulis Catatan..." v-model="item.catatan"></VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Catatan Klinis">
                                <VControl class="mt-3">
                                    <VTextarea rows="2" placeholder="Tulis Catatan Klinis..." v-model="item.catatanklinis">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>

                    </div>
                </div>

            </template>
            <template #action>
                <VButton v-if="item.namaFILE != null" color="info" raised icon="fas fa-file" class="mr-2"
                    @click="lihatDok()">
                    Lihat File
                </VButton>
                <VButton v-if="isLoadDataNoRec" icon="feather:printer" @click="cetakBuktiOrder()" color="info"
                    :loading="isLoading" raised>Cetak</VButton>
                <VButton icon="feather:save" @click="save()" :loading="isLoading" color="primary" raised>Simpan</VButton>
            </template>
        </VModal>

        <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
            @close="modalFilter = false">
            <template #content>
                <form class="modal-form">
                    <div class="columns">
                        <div class="column is-12" style="text-align: center">
                            <VField class="is-centered">
                                <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks
                                    :max-date="new Date()" />
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
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-11">
                    <div class="timeline-wrapper">
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

                                    <div class="content-wrap is-grey">
                                        <div class="content-box">
                                            <div class="status"></div>
                                            <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                                                <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                                            </VIconBox>
                                            <div class="box-text" style="width: 100%">
                                                <div class="meta-text">
                                                    <p>
                                                        <span>{{ items.namaproduk }}</span>
                                                    </p>
                                                    <table style="width: 100%; margin-top: 10px">
                                                        <tr>
                                                            <td class="font-labels" width="38%">Harga</td>
                                                            <td class="font-labels">:</td>
                                                            <td class="font-values" width="60%">{{ H.formatRp(items.total,
                                                                'Rp.') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-labels" width="50%">Jumlah</td>
                                                            <td class="font-labels">:</td>
                                                            <td class="font-values" width="50%">{{ items.jumlah }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-labels" width="50%">Dokter Pemeriksa</td>
                                                            <td class="font-labels">:</td>
                                                            <td class="font-values" width="100%">
                                                                {{ items.namalengkap }}
                                                            </td>
                                                        </tr>

                                                    </table>

                                                </div>

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

        <VModal :open="modalTransaksi" title="Pilih Ruangan" :noclose="true" size="small" actions="right"
            @close="modalTransaksi = false">
            <template #content>

                <div class="column is-12">
                    <VField label="Ruangan" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="feather:plus-circle" fullwidth>
                            <Multiselect mode="single" v-model="item.objectruangantujuanfk" :options="d_Ruangan"
                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                track-by="value" />

                        </VControl>
                    </VField>
                </div>


            </template>
            <template #action>
                <VButton icon="feather:save" @click="saveTransaksi(item.objectruangantujuanfk)" :loading="isLoading"
                    color="primary" raised>
                    Lanjut</VButton>
            </template>
        </VModal>

        <VModal :open="modalAmbil" title="Pengambilan Jenazah" :noclose="true" size="big" actions="right"
            @close="modalAmbil = false">
            <template #content>
                <form class="modal-form">
                    <div class="column is-12">
                        <div class="columns is-multiline">

                            <div class="column is-2">
                                <VField label="No. Registrasi">
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.noregis" placeholder="No. Registrasi"
                                            class="is-rounded" disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Nocm">
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.nocmpas" placeholder="Nomor SEP"
                                            class="is-rounded" disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Nama Pasien">
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.namapas" placeholder="Nomor SEP"
                                            class="is-rounded" disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Jenis Kelamin">
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.jk" placeholder="Nomor SEP" class="is-rounded"
                                            disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Umur">
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.umur" placeholder="Nomor SEP" class="is-rounded"
                                            disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Tanggal Registrasi" class="is-rounded-select">
                                    <VControl class="prime-auto ">
                                        <div class="is-rounded is-rounded-select">
                                            <Calendar v-model="item.tglregis" selectionMode="single" :manualInput="true"
                                                class="w-100 is-rounded" showTime :showIcon="true" hourFormat="24"
                                                :date-format="'yy-mm-dd'" disabled />
                                        </div>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Ruangan Meninggal" class="is-rounded-select  is-autocomplete-select"
                                    v-slot="{ id }">
                                    <VControl icon="feather:plus-circle" fullwidth>
                                        <Multiselect mode="single" v-model="item.ruangmeninggal" :options="d_Ruangan"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                            track-by="value" />

                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Tanggal Meninggal" class="is-rounded-select">
                                    <VControl class="prime-auto ">
                                        <div class="is-rounded is-rounded-select">
                                            <Calendar v-model="item.tglmeninggal" selectionMode="single" :manualInput="true"
                                                class="w-100 is-rounded" showTime :showIcon="true" hourFormat="24"
                                                :date-format="'yy-mm-dd'" />
                                        </div>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Tanggal Pengambilan" class="is-rounded-select">
                                    <VControl class="prime-auto ">
                                        <div class="is-rounded is-rounded-select">
                                            <Calendar v-model="item.tanggalpengambilan" selectionMode="single"
                                                :manualInput="true" class="w-100 is-rounded" showTime :showIcon="true"
                                                hourFormat="24" :date-format="'yy-mm-dd'" />
                                        </div>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Dokter Penanggungjawab" class="is-rounded-select is-autocomplete-select">
                                    <VControl icon="fa:user-md" class="prime-auto-cus">
                                        <AutoComplete v-model="item.objectpegawaifk" :suggestions="d_Dokter"
                                            :optionLabel="'label'" @complete="fetchDokter($event)" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Dokter..." class="mt-2 is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Nama Pengambil">
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.namapengambil" placeholder="Nama Lengkap"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Hubungan Keluarga" class="is-rounded-select is-autocomplete-select"
                                    v-slot="{ id }">
                                    <VControl icon="feather:list" fullwidth>
                                        <Multiselect mode="single" v-model="item.hubungankeluarga" :options="d_HK"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                            autocomplete="off" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-7">
                                <VField label="Alamat">
                                    <VControl icon="feather:home">
                                        <VInput type="text" v-model="item.alamat" placeholder="Alamat" class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField label="Keterangan">
                                    <VControl>
                                        <VTextarea class="textarea is-rounded" v-model="item.catatan" rows="2"
                                            placeholder="Keterangan" autocomplete="off" autocapitalize="off"
                                            spellcheck="true" />
                                    </VControl>
                                </VField>
                            </div>

                        </div>
                    </div>
                </form>




            </template>
            <template #action>
                <VButton icon="feather:save" @click="saveJenazah()" :loading="isLoading" color="primary" raised>
                    Simpan</VButton>
            </template>
        </VModal>

        <VModal :open="modalPermohonan" title="Permohonan Pemulasaraan Jenazah" :noclose="true" size="big" actions="right"
            @close="modalPermohonan = false">
            <template #content>
                <form class="modal-form">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <VField label="No. Surat Kematian">
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.nosurat" placeholder="No Surat Kematian"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField label="Nama Penanggung Jawab/Pemohon">
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.namapengambil" placeholder="Nama Penanggung Jawab"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Jenis Kelamin" class="is-rounded-select is-autocomplete-select"
                                    v-slot="{ id }">
                                    <VControl icon="feather:list" fullwidth>
                                        <Multiselect mode="single" v-model="item.objectjeniskelaminfk"
                                            :options="d_JenisKelamin" placeholder="Pilih data" :searchable="true"
                                            :attrs="{ id }" autocomplete="off" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Hubungan Keluarga" class="is-rounded-select is-autocomplete-select"
                                    v-slot="{ id }">
                                    <VControl icon="feather:list" fullwidth>
                                        <Multiselect mode="single" v-model="item.objecthubungankeluargafk" :options="d_HK"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                            autocomplete="off" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField label="Alamat">
                                    <VControl>
                                        <VTextarea class="textarea is-rounded" v-model="item.alamatlengkap" rows="2"
                                            placeholder="Alamat Lengkap" autocomplete="off" autocapitalize="off"
                                            spellcheck="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Petugas I" class="is-rounded-select is-autocomplete-select">
                                    <VControl icon="fa:user-md" class="prime-auto-cus">
                                        <AutoComplete v-model="item.petugassatu" :suggestions="d_Pegawai"
                                            :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Petugas..." class="mt-2 is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Petugas II" class="is-rounded-select is-autocomplete-select">
                                    <VControl icon="fa:user-md" class="prime-auto-cus">
                                        <AutoComplete v-model="item.petugasdua" :suggestions="d_Pegawai"
                                            :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Petugas..." class="mt-2 is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Petugas III" class="is-rounded-select is-autocomplete-select">
                                    <VControl icon="fa:user-md" class="prime-auto-cus">
                                        <AutoComplete v-model="item.petugastiga" :suggestions="d_Pegawai"
                                            :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Petugas..." class="mt-2 is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Petugas VI" class="is-rounded-select is-autocomplete-select">
                                    <VControl icon="fa:user-md" class="prime-auto-cus">
                                        <AutoComplete v-model="item.petugasempat" :suggestions="d_Pegawai"
                                            :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Petugas..." class="mt-2 is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField label="Petugas V" class="is-rounded-select is-autocomplete-select">
                                    <VControl icon="fa:user-md" class="prime-auto-cus">
                                        <AutoComplete v-model="item.petugaslima" :suggestions="d_Pegawai"
                                            :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Petugas..." class="mt-2 is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Pemulasaraan Jenazah</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.pemulasaraanjenazah" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.pemulasaraanjenazah" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Pengkafanan/Pemakaian Baju</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.pengkafanan" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.pengkafanan" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Plastisisasi</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.plastisisasi" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.plastisisasi" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Kantong Jenazah</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.kantongjenazah" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.kantongjenazah" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Peti Jenazah</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.petijenazah" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.petijenazah" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Disinfektan Pada Pasien/Jenazah</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.disinfektanjenazah" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.disinfektanjenazah" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Pelayanan Kerohanian</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.pelayanankerohanian" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.pelayanankerohanian" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Transportasi Ambulan</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.transportasiambulan" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.transportasiambulan" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Disinfektan Ambulan</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.disinfektanambulan" value="t" />
                                        <label class="ml-2">Ya</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.disinfektanambulan" value="f" />
                                        <label class="ml-2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold;">Status Purna Pasien / Jenazah</h1>
                                <div style="display:flex;margin-top: 12px;">
                                    <div class="flex align-items-center" style="margin-left: 1rem;margin-right: 5rem;">
                                        <RadioButton v-model="item.covid" value="t" />
                                        <label class="ml-2">Covid</label>
                                    </div>
                                    <div class="flex align-items-center">
                                        <RadioButton v-model="item.noncovid" value="t" />
                                        <label class="ml-2">Non Covid</label>
                                    </div>
                                </div>
                            </div>






                        </div>
                    </div>
                </form>




            </template>
            <template #action>
                <VButton icon="feather:save" @click="savePermohonan()" :loading="isLoading" color="primary" raised>
                    Simpan</VButton>
            </template>
        </VModal>
    </div>
</template>


<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment, { isDate } from 'moment'
import { useToaster } from '/@src/composable/toaster'
import Fieldset from 'primevue/fieldset'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import FloatingButton from "../emr/float-tambah.vue"
import Badge from 'primevue/badge';
import Calendar from 'primevue/calendar';
import RadioButton from 'primevue/radiobutton';
import * as qzService from '/@src/utils/qzTrayService'


useHead({
    title: 'Dashboard Jenazah - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)


const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()

const NOREC_PD = useRoute().query.nocm as string

const dataSource: any = ref([])
const filters = ref('')
const d_Dokter = ref([])
const d_HK = ref([])
const d_Komponen = ref([])
const d_Pegawai = ref([])
const d_Ruangan = ref([])
const d_JenisKelamin = ref([])
const d_GolonganDarah = ref([])
const d_Produk = ref([])
const rowGroupMetadata = ref({})


var date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });

let listColor: any = ref(Object.keys(useThemeColors()))
const modalDetail = ref(false)
const route = useRoute()
let statusOrder: any = ref([])
let result: any = ref([])
let so_norec = ref('')
let dokterPraktek: any = ref([])
let dataPenunjang: any = ref([])
let isLoading: any = ref(false)
let detailDiagnosa: any = ref(0)
let dataStokObat: any = ref([])
let sourceItemSelect: any = ref([])
let detailOrderVerify: any = ref(0)
let detailOrderLayanan: any = ref(0)
let modalDetailOrder: any = ref(false)
let modalDetailOrderVerify: any = ref(false)
let modalFilter: any = ref(false)
let modalPermohonan: any = ref(false)
let modalTransaksi: any = ref(false)
let modalAmbil: any = ref(false)
let dataPasien: any = ref([])
let isLoadDataOrder: any = ref(false)
let isLoadDataNoRec: any = ref(false)
let isData: any = ref()
let data2: any = ref([])
let chartLO: any = ref({
    series: [],
})
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})
const confirm = useConfirm();
const currentPageOrder: any = ref({
  limit: 5,
  rows: 50,
})
const currentPenunjang: any = ref({
  limit: 5,
  rows: 50,
})
const currentPasienDaftar: any = ref({
  limit: 9,
  rows: 50,
})
currentPageOrder.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
currentPenunjang.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
currentPasienDaftar.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
const order: any = ref(0)
const dataOrder: any = ref(0)
const dataOrderFiltered: any = computed(() => {
    let key = new RegExp(item.value.qnoregistrasi, 'i')
    let keyIdentitas = new RegExp(item.value.qnoidentitas, 'i')

    if (!item.value.qnoregistrasi && !item.value.qnoidentitas) {
        return dataOrder.value
    }

    if (item.value.qnoregistrasi && item.value.qnoidentitas) {
        return dataOrder.value.filter((item: any) => {
            return (
                (item.noregistrasi.match(key) || item.pas_nocm.match(key)) && (item.pas_noidentitas.match(keyIdentitas) || item.no_bpjs.match(keyIdentitas))
            )
        })
    }

    if (item.value.qnoregistrasi) {
        return dataOrder.value.filter((item: any) => {
            return (
                item.noregistrasi.match(key) || item.pas_nocm.match(key)
            )
        })
    }

    if (item.value.qnoidentitas) {
        return dataOrder.value.filter((item: any) => {
            return (
                item.pas_noidentitas.match(keyIdentitas) || item.no_bpjs.match(keyIdentitas)
            )
        })
    }
})
const router = useRouter()


const fetchDataOrder = async (q: any) => {
    try {
        let ruanganid = item.value.filterRuangan ? `&ruanganid=${item.value.filterRuangan}` : '';
        let tglAwal = `&tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`;
        let tglAkhir = `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`;
        let qnamapasien = item.value.qnamapasien ? `&qnamapasien=${item.value.qnamapasien}` : '';
        let search = item.value.search ? `&search=${item.value.search}` : '';
        let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
        let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
        let qnoidentitas = ''; // Jika diperlukan tambahkan logika untuk qnoidentitas
        let statusOrder = q ? `&statusorder=${q}` : '';
        let limit: any = currentPageOrder.value.limit
        let offset: any = route.query.page ? route.query.page : 1
        offset = (offset * limit) - limit

        isLoading.value = true;

        const response = await useApi().get(`/jenazah/list-verif?ruanganid=${ruanganid}${search}${tglAwal}${tglAkhir}${qnamapasien}${statusOrder}${qnocm}${qnoregistrasi}&limit=${limit}&offset=${offset}`);

        modalFilter.value = false;

        response.data.forEach((element: any, i: any) => {
            element.no = i + 1;
            let ini = element.namapasien.split(' ');
            let init = element.namapasien.substr(0, 2);
            if (ini.length > 1) {
                init = init + ini[1].substr(0, 1);
            }
            element.initials = init;
            element.ruanganasal = element.asal_ruangan.length > 14 ? element.asal_ruangan.substring(0, 14) + '...' : element.asal_ruangan;
            element.tglRegistrasi = moment(element.tglregistrasi).format('YYYY-MM-DD');
        });

        isData.value = response.total;
        dataOrder.value = response.data;
        dataOrder.value.total = response.total;
    } catch (err) {
        // Tangani kesalahan jika diperlukan
    } finally {
        isLoading.value = false;
    }
};




const fetchPenunjang = async () => {
    try {
        let ruanganid = item.value.filterRuangan || '';
        let tglAwal = '&tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD');
        let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD');
        let qnama = item.value.qnama ? `&qnama=${item.value.qnama}` : '';
        let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
        let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
        let search = item.value.qsearch ? `&search=${item.value.qsearch}` : ''
        let limit: any = currentPenunjang.value.limit
        let offset: any = route.query.page ? route.query.page : 1
        offset = (offset * limit) - limit

        dataPenunjang.value.loading = true;
        dataPenunjang.value = [];

        const response = await useApi().get(`/jenazah/pasien-forensik?ruanganid=${ruanganid}${tglAwal}${tglAkhir}${qnama}${qnocm}${qnoregistrasi}${search}&limit=${limit}&offset=${offset}`);

        dataPenunjang.value.loading = false;
        dataPenunjang.value = response.data;
        dataPenunjang.total = response.total;
    } catch (error) {
        console.error('Error fetching penunjang data:', error);
        dataPenunjang.value.loading = false;
    }
}


const fetchDetail = async () => {
    let ruanganid = ''
    if (item.value.filterRuangan) {
        ruanganid = `?ruanganid=${item.value.filterRuangan}`
    }
    dokterPraktek.value = []
    dataStokObat.value = []
    const response = await useApi().get(
        '/dashboard/lab-detail' + ruanganid
    )
    dokterPraktek.value = response.dokter
    dataStokObat.value = response.produk
}

const getListPelayanan = async (data: any) => {
    const response = await useApi().get(`/dashboard/get-pelayanan-lab?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}&idruangan=${data.idruangan}`)
    d_Produk.value = response.map((e: any) => {
        return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
    })
}


const orderVerify = async (e: any) => {
    detailOrderLayanan.value = []
    modalDetailOrder.value = true
    let data = {
        'idkelas': e.objectkelasfk,
        'idruangan': e.objectruangantujuanfk,
        'idJenisPelayanan': e.jenispelayananfk,
        'idRekanan': e.objectrekananfk
    }
    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.cito = e.cito
    item.value.namapasien = e.namapasien
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.ruanganasal = e.ruanganasal
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
    item.value.catatanklinis = e.catatanklinis
    item.value.catatan = e.keteranganlainnya
    item.value.tglregistrasi = e.tglregistrasi
    item.value.kelas = e.objectkelasfk
    item.value.pegawaiVerif = userLogin.pegawai.namalengkap
    item.value.namaFILE = e.namafile
    getListPelayanan(data)
    isLoadDataOrder.value = true
    const getHargaLayanan = await useApi().get(`/jenazah/detail-verif-pj?strukorderfk=${e.so_norec}&objectkelasfk=${e.objectkelasfk}`)
    const response = await useApi().get(`/jenazah/list-verif?statusorder=${statusOrder.value}&noorder=${e.noorder}`)

    getHargaLayanan.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    isLoadDataOrder.value = false
    detailOrderLayanan.value = getHargaLayanan
    isLoadDataNoRec.value = false
}
const showModalFilter = () => {
    modalFilter.value = true
}

const getDetailVerify = async (e: any) => {
    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.namapasien = e.namapasien
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.noorder = e.noorder
    item.value.norec_pp = e.norec
    item.value.no_rm = e.pas_nocm
    item.value.jeniskelamin = e.jeniskelamin
    item.value.kelompokpasien = e.kelompokpasien
    item.value.idRuanganTujuan = e.objectruangantujuanfk
    item.value.tglregistrasi = e.tglregistrasi
    item.value.namalengkap = e.namalengkap

    modalDetailOrderVerify.value = true

    const response = await useApi().get(`/dashboard/get-lab-verify?norec_so=${e.so_norec}`)
    const diagnosa = await useApi().get(`/dashboard/so-lab?statusorder=${statusOrder.value}&noorder=${e.noorder}`)
    // detailDiagnosa.value = diagnosa[0].detailDiagnosa
    response.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    detailOrderVerify.value = response
    // console.log(detailOrderVerify)
}

const save = async () => {

    if (detailOrderLayanan.value.length < 1) {
        useToaster().error('Order Tidak Boleh kosong')
        return
    }
    if (!item.value.dokterVerif) {
        useToaster().error('Dokter Verify Tidak Boleh Kosong')
        return
    }
    if (!item.value.pegawaiVerif) {
        useToaster().error('Dokter Verify Tidak Boleh Kosong')
        return
    }
    let datas = []
    let datass = []
    let parameter = {
        'idruangtujuan': item.value.idRuanganTujuan,
        'pd_norec': item.value.pdNorec,
        'objectpegawaiorderfk': item.value.objectpegawaiorderfk,
        'tglregistrasi': item.value.tglregistrasi,
        'noregistrasi': item.value.noregistrasi,
        'so_norec': item.value.soNorec,
        'catatan': item.value.catatan,
        'objectpegawaifk': item.value.dokterVerif.value,
        'pegawaiverifikatorfk': item.value.pegawaiVerif.value,
        'catatanklinis': item.value.catatanklinis,

    }
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        let response = detailOrderLayanan.value[i]
        let komponenHarga = detailOrderLayanan.value[i].komponenharga
        var objSave = {
            // 'noregistrasi': dataOrder.noregistrasi,
            'idProduk': response.prid,
            'hargaLayanan': response.hargasatuan,
            'tglpelayanan': response.tglpelayanan,
            'jumlah': response.qtyproduk,
            'komponenharga': komponenHarga,
            'cito': response.cito,
        }
        datas.push(objSave)
    }



    isLoading.value = true

    await useApi().post('/dashboard/save-order-pelayanan-lab', { 'data': datas, 'parameter': parameter }).then((response: any) => {
        isLoading.value = false
        so_norec.value = response.pelPasien.strukorderfk
         modalDetailOrder.value = false
        reload()
    }).catch((error) => {
        useToaster().error('Something Went Wrong')
        isLoading.value = false
         modalDetailOrder.value = false
    })

    isLoadDataNoRec.value = true
}

const hapusItems = (e: any) => {
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        if (detailOrderLayanan.value[i].no == e.no) {
            detailOrderLayanan.value.splice(i, 1);
        }
    }
    dataSource.value = detailOrderLayanan.value
}

const cetak = ()=>{

}


const changeSwitch = (e: any) => {
    fetchDataOrder(e)
}

const changePeriode = () => {
    fetchDataOrder(0)
    fetchPenunjang()
    fetchPasien()
}

const getHarga = async (e: any) => {
    const response = await useApi().get(`/dashboard/get-pelayanan-lab?idkelas=${item.value.kelas}&idjenispelayanan=${item.value.idJenisPelayanan}&idruangan=${item.value.idRuanganTujuan}`)
    item.value.hargaLayanan = response[0].hargasatuan
    item.value.namaproduk = response[0].namaproduk

    console.log(item.value.hargaLayanan)
}


const clear = () => {
    item.value.id = ''
    delete item.value.no
    delete item.value.produk
    item.value.hargaLayanan = ''
    item.value.qtyproduk = ''
    item.value.jumlah = ''
    // item.value.nocm = ''
    // item.value.noregistrasi = ''
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
    // let jumlah = e.jumlah
    // let harga = e.hargaLayanan
    // let ruangan = e.ruangantujuan
    // let namaproduk = e.namaproduk
    // let layanan = e.produk
    // let cito = e.iscito
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
        cito: item.value.iscito ? item.value.iscito : false,
    }
    detailOrderLayanan.value.push(data)
}
const update = (e: any) => {
    let data: any = {}
    let datas: any = []
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
    const response = await useApi().get(`/jenazah/dd-pj`)
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_HK.value = response.hubungankeluarga.map((e: any) => { return { label: e.hubungankeluarga, value: e.id, default: e } })
    d_JenisKelamin.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id, default: e } })
    d_Dokter.value = response.namalengkap.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
    })
}

const transaksiPelayanan = (e: any) => {
    router.push({
        name: 'module-jenazah-transaksi-pelayanan-jenazah',
        query: {
            nocmfk: e.nocmfk,
            norec_pasien_daftar: e.norec_pd,
            norec_apd: e.norec_apd
        },

    })
}

const PengkajianMedis = (e: any) => {
    console.log(e)
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec_pd,
            norec_apd: e.norec_apd,
        }
    })
}

const cetakBuktiOrder = () => {
    // console.log(so_norec)

    H.printBlade('jenazah/cetak-bukti-order?noregistrasi=' + item.value.noregistrasi
        + '&so_norec=' + so_norec.value);
}

const changeRuang = (e: any) => {
    for (let x = 0; x < d_Ruangan.value.length; x++) {
        const element = d_Ruangan.value[x];
        if (e == element.value) {
            item.value.namaruangan = element.label
            break
        }
    }
    fetchDataOrder(0)
    fetchDetail()
    fetchPenunjang()

}
const emr = (e: any) => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec_pd,
            norec_apd: e.norec_apd,
        }
    })
}


const batalVerifikasi = async (e: any) => {
    confirm.require({
        group: 'positionDialog',
        message: H.alertHapus(),
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: () => {
            var objSave = {
                'data':
                    [{
                        'noorder': e.noorder,
                        'norec_pp': e.norec
                    }],
            }
            nextHapus(objSave)
        },
        reject: () => {
        }
    });

}

const nextHapus = async (objSave: any) => {
    isLoading.value = true
    useApi().post(
        `/dashboard/batal-verif-lab`, objSave).then((response: any) => {
            isLoading.value = false
            modalDetailOrderVerify.value = false
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const fetchPasien = async () => {
    let ruanganid = ''
    if (item.value.filterRuangan) {
        ruanganid = item.value.filterRuangan
    }
    let dari = ''
    if (item.value.periode.start) {
        dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    }
    let sampai = ''
    if (item.value.periode.end) {
        sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
    }
    let status = ''


    let namapasien = ''
        , nocm = ''
        , noreg = ''
        , statuspanggil = ''
        , search = ''
    if (item.value.qnama) namapasien = item.value.qnama
    if (item.value.qnoreg) noreg = item.value.qnoreg
    if (item.value.qnocm) nocm = item.value.qnocm
    if (item.value.rsearch) search = item.value.rsearch
    let limit: any = currentPasienDaftar.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit

    isLoading.value = true
    dataPasien.value = []
    const response = await useApi().get(
        '/jenazah/get-status-pj?ruanganid=' + ruanganid
        + '&dari=' + dari
        + '&sampai=' + sampai
        + '&namapasien=' + namapasien
        + '&nocm=' + nocm
        + '&search=' + search
        + '&noregistrasi=' + noreg
        + '&limit=' + limit
        + '&offset=' + offset

    )
    isLoading.value = false
    response.data.sort(compare);
    dataPasien.value = response.data
    dataPasien.total = response.total

    updateRowGroupMetaData();
}
const compare = (a: any, b: any) => {
    if (a.namaruangan > b.namaruangan) {
        return -1;
    }
    if (a.namaruangan < b.namaruangan) {
        return 1;
    }
    return 0;
}


const daftar = (e: any) => {
    item.norec_pd = e.norec_pd
    item.nocmfk = e.nocm
    item.noregistrasi = e.norec_pd
    // item.objectruangantujuanfk = e.objectruangantujuanfk
    item.objectruanganfk = e.objectruanganfk
    item.objectruanganlastfk = e.objectruanganlastfk
    item.norec_apd = e.norec_apd
    item.objectkelasfk = e.objectkelasfk
    item.tglregistrasi = e.tglregistrasi
    sourceItemSelect.value = e
    modalTransaksi.value = true
}

const saveTransaksi = async (e: any) => {
    let json = {
        pasiendaftar: {
            'norec_pd': item.norec_pd,
            'tglregistrasi': item.tglregistrasi,
            'objectkelasfk': item.objectkelasfk,
            'noregistrasifk': item.norec_pd,

        },
        antrianpasiendiperiksa: {
            'norec_apd': item.norec_apd,
            'objectruangantujuanfk': e,
            'objectruanganlastfk': item.objectruanganlastfk
        }
    }

    isLoading.value = true
    await useApi()
        .post(`/laboratorium/save-penunjang`, json)
        .then((response: any) => {
            isLoading.value = false
            clear()
            goTo(sourceItemSelect.value)
            // fetchPasien()
        })
        .catch((e: any) => {
            isLoading.value = false
        })
}


const cetakOrder = async (e: any) => {
    let norec_pd = e.so_norec;
    H.printBlade(`report/cetak-order?type=laboratorium&noregistrasi=${norec_pd}`)
}
const cetakSEP = (e: any) => {
    qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
        'SEP', 1)
}

const updateRowGroupMetaData = () => {
    rowGroupMetadata.value = {};

    if (dataPasien.value) {
        for (let i = 0; i < dataPasien.value.length; i++) {
            let rowData = dataPasien.value[i];
            let namaruangan = rowData.namaruangan;

            if (i == 0) {
                rowGroupMetadata.value[namaruangan] = { index: 0, size: 1 };
            }
            else {
                let previousRowData = dataPasien.value[i - 1];
                let previousRowGroup = previousRowData.namaruangan;
                if (namaruangan === previousRowGroup)
                    rowGroupMetadata.value[namaruangan].size++;
                else
                    rowGroupMetadata.value[namaruangan] = { index: i, size: 1 };
            }
        }
    }
}

const fetchPegawai = async (filter: any) => {
    // let data = filter.query ? filter.query : filter
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
function handleChange(e: any) {
    item.iscito = e
}
function changeTindakan(e: any) {
    // console.log(e)
    isLoading.value = true
    d_Komponen.value = []
    item.value.hargaLayanan = 0
    useApi().get(
        '/tindakan/list-tindakan-komponen?idRuangan=' + item.value.idRuanganTujuan
        + '&idKelas=' + 2 // item.value.kelas
        + '&idProduk=' + e.id
        + '&idJenisPelayanan=' + item.value.idJenisPelayanan
        // + '&idPenjamin=' + item.registrasi.objectrekananfk
    ).then((response: any) => {
        isLoading.value = false
        if (response.komponen.length == 0) {
            H.alert('warn', 'Komponen Tarif tidak ada')
            return
        }
        item.value.hargaLayanan = response.harga.hargasatuan
        item.hargasatuanDef = response.harga.hargasatuan
        item.value.jumlah = 1
        d_Komponen.value = response.komponen

    })
}

const jenazahAmbil = (e: any) => {

    item.value.noregis = e.noregistrasi
    item.value.nocmpas = e.nocm
    item.value.namapas = e.namapasien
    item.value.jk = e.jeniskelamin
    item.value.umur = e.umur
    item.value.tglregis = e.tglregistrasi
    item.value.ruangmeninggal = e.ruanganasal
    item.value.norec_pd = e.norec_pd

    modalAmbil.value = true
}

const saveJenazah = async () => {
    let json = {
        'pengambilanjenazah': {
            'norec': item.value.norec ? item.value.norec : '',
            'objectpasiendaftarfk': item.value.norec_pd,
            'namapengambil': item.value.namapengambil,
            'objectpegawaifk': item.value.objectpegawaifk.value,
            'objectruanganfk': item.value.ruangmeninggal,
            'tanggalpengambilan': item.value.tanggalpengambilan,
            'objecthubunganfk': item.value.hubungankeluarga,
            'alamatlengkap': item.value.alamat,
            'keterangan': item.value.catatan,
        }

    }
    useApi().post(
        `/jenazah/save-pengambilan-jenazah`, json).then((response: any) => {
            modalAmbil.value = false
            fetchPenunjang()
        })
}

const saveBatalMeninggal = async (e: any) => {

    let json = {
        pasiendaftar: {
            norec_pd: e.norec_pd,
            nocmfk: e.nocmfk
        }
    }
    isLoading.value = true
    await useApi()
        .post(`/jenazah/save-batal-meninggal`, json)
        .then((response: any) => {
            isLoading.value = false
            fetchPasien()
        })
        .catch((e: any) => {
            isLoading.value = false
        })
}

const orderPermohonan = (e: any) => {
    console.log(e)

    item.value.nores_pd = e.norec_pd
    modalPermohonan.value = true
}

const savePermohonan = async () => {
    let json = {
        norec: item.value.norec ? item.value.norec :'',
        nores_pd: item.value.nores_pd,
        nosurat: item.value.nosurat,
        penanggungjawab: item.value.penanggungjawab,
        objectjeniskelaminfk: item.value.objectjeniskelaminfk,
        objecthubungankeluargafk: item.value.objecthubungankeluargafk,
        alamat: item.value.alamat,
        covid: item.value.covid ? item.value.covid: '',
        noncovid : item.value.noncovid ? item.value.noncovid: '',
        petugassatu : item.value.petugassatu ? item.value.petugassatu: '',
        petugasdua : item.value.petugasdua ? item.value.petugasdua: '',
        petugastiga : item.value.petugastiga ? item.value.petugastiga: '',
        petugasempat : item.value.petugasempat ? item.value.petugasempat: '',
        petugaslima : item.value.petugaslima ? item.value.petugaslima: '',
        pemulasaraanjenazah : item.value.pemulasaraanjenazah ? item.value.pemulasaraanjenazah: '',
        pengkafanan : item.value.pengkafanan ? item.value.pengkafanan: '',
        plastisisasi : item.value.plastisisasi ? item.value.plastisisasi: '',
        kantongjenazah : item.value.kantongjenazah ? item.value.kantongjenazah: '',
        petijenazah : item.value.petijenazah ? item.value.petijenazah: '',
        disinfektanjenazah : item.value.disinfektanjenazah ? item.value.disinfektanjenazah: '',
        pelayanankerohanian : item.value.pelayanankerohanian ? item.value.pelayanankerohanian: '',
        transportasiambulan : item.value.transportasiambulan ? item.value.transportasiambulan: '',
        disinfektanambulan : item.value.disinfektanambulan ? item.value.disinfektanambulan: '',

    }
    isLoading.value = true
    await useApi()
        .post(`/jenazah/save-permohonan-pj`, json)
        .then((response: any) => {
            isLoading.value = false
            modalPermohonan.value = false
            fetchPasien()

        })
        .catch((e: any) => {
            isLoading.value = false
        })
}

watch(
    () => [
        order.value
    ], () => {
        changeSwitch(order.value)
    }
)
watch(
    () => [
        item.value.fStatusAntrian
    ], () => {
        fetchPasien()
    }
)
const showPasienLama = async () => {
    router.push({
        name: 'module-registrasi-pasien-lama',
        query: {

        },

    })
}

const reload = async () => {
    fetchDataOrder(0)
    fetchPenunjang()
}
const lihatDok = () => {
    H.openFile('berkas_lab/' + item.value.soNorec + '/' + item.value.namaFILE);
}

fetchDataOrder(0)
fetchDetail()
fetchPenunjang()
fetchdDropdown()
fetchPasien()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';
@import '/@src/scss/module/dashboard/laboratorium.scss';

.slider-lab {
    .tabs-inner {
        margin-right: unset !important;
    }
}

.control.has-icon.prime-auto-cus .form-icon {
    top: 6px;
}</style>
