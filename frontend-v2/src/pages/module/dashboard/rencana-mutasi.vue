<template>
    <section>
        <div class="lifestyle-dashboard lifestyle-dashboard-v4">
            <div class="columns">
                <div class="column is-8">
                    <div class="columns is-multiline">
                        <!--Header-->
                        <div class="column is-12">
                            <div class="illustration-header-2">
                                <div class="header-image">
                                    <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                                        style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                                </div>
                                <div class="header-meta">
                                    <h4 style="color:white"> <i class="fas fa-bed"></i> </h4>
                                    <h3> Rencana Mutasi Pasien </h3>
                                    <p>
                                        Selamat Datang, {{ userLogin.pegawai.namaLengkap }}
                                    </p>
                                    <VControl v-if="keluser != 11">
                                        <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan"
                                            optionLabel="label" filter placeholder="Pilih Ruangan"
                                            :maxSelectedLabels="3" style="display:flex"
                                            @change="changeRuang(sourceRuangan)" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <!--Content-->
                        <div class="column is-12">
                            <div class="writing-stats">
                                <!--Stat-->
                                <div class="writing-stat">
                                    <span>Total Rencana Mutasi</span>
                                    <div v-if="isLoad">
                                        <VPlaceload class="mx-2 mt-3" width="60%" />
                                        <VIconBox color="blue" size="small" rounded
                                            style="margin-top: -1.5rem; margin-left: 12.5rem">
                                            <i aria-hidden="true" class="fas fa-users"></i>
                                        </VIconBox>
                                    </div>
                                    <div v-else>
                                        <span class="dark-inverted"
                                            style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);">{{
                                            item.totalRencanaMutasi }}</span>
                                        <VIconBox color="blue" size="small" rounded
                                            style="margin-top: -2.5rem; margin-left: 12.5rem">
                                            <i aria-hidden="true" class="fas fa-users"></i>
                                        </VIconBox>
                                    </div>
                                </div>
                                <!--Stat-->
                                <div class="writing-stat">
                                    <span>Belum di Mutasi</span>
                                    <div v-if="isLoad">
                                        <VPlaceload class="mx-2 mt-3" width="60%" />
                                        <VIconBox color="warning" size="small" rounded
                                            style="margin-top: -1.5rem; margin-left: 12.5rem">
                                            <i aria-hidden="true" class="fas fa-user-injured"></i>
                                        </VIconBox>
                                    </div>
                                    <div v-else>
                                        <span class="dark-inverted"
                                            style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);">{{
                                            item.totalBelumMutasi }}</span>
                                        <VIconBox color="warning" size="small" rounded
                                            style="margin-top: -2.5rem; margin-left: 12.5rem">
                                            <i aria-hidden="true" class="fas fa-user-injured"></i>
                                        </VIconBox>
                                    </div>
                                </div>

                                <!--Stat-->
                                <!-- <div class="writing-stat">
                                    <span>Sudah di Mutasi</span>
                                    <div v-if="isLoad">
                                        <VPlaceload class="mx-2 mt-3" width="60%" />
                                        <VIconBox color="primary" size="small" rounded
                                            style="margin-top: -1.5rem; margin-left: 12.5rem">
                                            <i aria-hidden="true" class="fas fa-bed"></i>
                                        </VIconBox>
                                    </div>
                                    <div v-else>
                                        <span class="dark-inverted"
                                            style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);">{{item.totalSudahMutasi}}</span>
                                        <VIconBox color="primary" size="small" rounded
                                            style="margin-top: -2.5rem; margin-left: 12.5rem">
                                            <i aria-hidden="true" class="fas fa-bed"></i>
                                        </VIconBox>
                                    </div>
                                </div> -->

                                <!--Stat-->
                                <div class="writing-stat">
                                    <span>Di tolak</span>
                                    <div v-if="isLoad">
                                        <VPlaceload class="mx-2 mt-3" width="60%" />
                                        <VIconBox color="danger" size="small" rounded
                                            style="margin-top: -1.5rem; margin-left: 12.5rem">
                                            <i aria-hidden="true" class="fas fa-times-circle"></i>
                                        </VIconBox>
                                    </div>
                                    <div v-else>
                                        <span class="dark-inverted"
                                            style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);">{{
                                            item.totalDitolak }}</span>
                                        <VIconBox color="danger" size="small" rounded
                                            style="margin-top: -2.5rem; margin-left: 12.5rem">
                                            <i aria-hidden="true" class="fas fa-user-injured"></i>
                                        </VIconBox>
                                    </div>
                                </div>
                            </div>

                            <div class="featured-authors">
                                <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                                    <TabPanel>
                                        <template #header>
                                            <i class="fas fa-users mr-2" aria-hidden="true"></i>
                                            <span>Daftar Rencana Mutasi</span>
                                            <Badge :value="dataRencanaMutasi.length" v-if="dataRencanaMutasi.length > 0"
                                                severity="danger" class="ml-2" />
                                        </template>
                                        <div v-if="activeTab == 0">
                                            <div class="list-view list-view-v3">
                                                <div class="search-menu" style="margin-bottom : 1rem;">

                                                    <div class="search-location" style="width: 100%">
                                                        <i class="iconify" data-icon="feather:search"></i>
                                                        <input type="text"
                                                            placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                                                            v-model="item.search" v-on:keyup.enter="fetchData()" />
                                                    </div>
                                                    <VButton raised class="search-button" :loading="isLoading"
                                                        @click="fetchData()"> Cari Data
                                                    </VButton>
                                                </div>
                                                <VPlaceholderPage
                                                    :class="[dataRencanaMutasi.length !== 0 && 'is-hidden']"
                                                    title="Tidak Ada Pasien Rawat Inap Saat Ini."
                                                    subtitle="Silakan Registrasikan Pasien Sebagai Rawat Inap." larger>
                                                    <template #image>
                                                        <img class="light-image"
                                                            src="/@src/assets/illustrations/placeholders/search-4.png"
                                                            alt="" />
                                                        <img class="dark-image"
                                                            src="/@src/assets/illustrations/placeholders/search-4.png"
                                                            alt="" />
                                                    </template>
                                                </VPlaceholderPage>

                                                <div class="list-view-inner"
                                                    style="max-height:500px; min-height:100px;overflow: auto;">
                                                    <TransitionGroup name="list-complete" tag="div">
                                                        <!--Item-->
                                                        <div v-for="item in dataRencanaMutasi" :key="item.id"
                                                            class="list-view-item">
                                                            <div class="list-view-item-inner">
                                                                <VAvatar size="medium"
                                                                    picture="/images/avatars/svg/pasien.svg"
                                                                    color="primary" squared bordered />
                                                                <div class="meta-left">
                                                                    <h3>
                                                                        {{ item.namapasien }} |
                                                                        <VTag v-if="item.kelompokpasien != null"
                                                                            class="mt-3 ml-2"
                                                                            :label="item.kelompokpasien"
                                                                            :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'"
                                                                            rounded /> |
                                                                        <i class="bulet fas fa-circle"></i>
                                                                        {{ item.namakelas }}
                                                                    </h3>
                                                                    <span>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="ic:baseline-house"></i>
                                                                        <span>{{ item.namakotakabupaten }} / {{
                                            item.namakecamatan }}</span><br>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:map-pin"></i>
                                                                        <span style="font-weight: bold;">{{
                                            item.namaruangan }} - {{ item.namakamar }} -
                                                                            {{
                                            item.reportdisplay
                                        }}</span><br>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:clock"></i>
                                                                        <span>{{ H.formatDateIndo(item.tglorder)
                                                                            }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:check-circle"></i>
                                                                        <span>{{ item.noregistrasi }}</span><br>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:clipboard"></i>
                                                                        <span>{{ item.nocm }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:clipboard"></i>
                                                                        <span>{{ item.nobpjs }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="teenyicons:id-outline"></i>
                                                                        <span>{{ item.noidentitas }}</span>
                                                                    </span><br>
                                                                    <span style="font-weight: bold;">DPJP :
                                                                        <i aria-hidden="true" class="iconify"></i>
                                                                        <span>{{ item.namalengkap }}</span>
                                                                    </span><br>
                                                                    <span style="font-weight: bold;">Keterangan :
                                                                        <i aria-hidden="true" class="iconify"></i>
                                                                        <span
                                                                            v-if="item.keterangaantrian == 'registrasi'">Registrasi
                                                                            ke Rawat Inap</span>
                                                                        <span
                                                                            v-else-if="item.keterangaantrian == 'pindah ruangan'">Pindah
                                                                            Ruangan</span>
                                                                        <span
                                                                            v-else-if="item.keterangaantrian == 'mutasi'">Mutasi
                                                                            ke Rawat Inap</span>
                                                                        <span v-else> - </span>
                                                                    </span>
                                                                </div>
                                                                <div class="meta-right">
                                                                    <VDropdown icon="feather:more-vertical" spaced right
                                                                        v-tooltip.top.left="'Action'"
                                                                        v-if="keluser == 11">
                                                                        <template #content>
                                                                            <a role="menuitem"
                                                                                @click="terimaRencanaMutasi(item)"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-user-plus"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Terima</span>
                                                                                </div>
                                                                            </a>
                                                                            <a role="menuitem"
                                                                                @click="batalRencanaMutasi(item)"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-ban"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Batal</span>
                                                                                </div>
                                                                            </a>
                                                                            <a role="menuitem" @click="cetakSEP(item)"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i aria-hidden="true"
                                                                                        class="lnil lnil-printer"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Cetak SEP</span>
                                                                                    <span>Cetak Surat Elegibilitas
                                                                                    </span>
                                                                                </div>
                                                                            </a>
                                                                            <hr class="dropdown-divider" />
                                                                            <a @click="cetakLabel2(item)"
                                                                                role="menuitem"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-print"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Label Pasien IGD</span>
                                                                                    <span>Cetak Label</span>
                                                                                </div>
                                                                            </a>
                                                                            <hr class="dropdown-divider" />
                                                                            <a @click="cetakBuktiPelayanan(item)"
                                                                                role="menuitem"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-print"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Bukti Pelayanan</span>
                                                                                    <span>Cetak Bukti Pelayanan </span>
                                                                                </div>
                                                                            </a>
                                                                            <hr class="dropdown-divider" />
                                                                            <a @click="cetakBuktiPelayanan2(item)"
                                                                                role="menuitem"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-print"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Bukti Pelayanan IGD</span>
                                                                                    <span>Cetak Bukti Pelayanan </span>
                                                                                </div>
                                                                            </a>
                                                                            <hr class="dropdown-divider" />
                                                                            <a @click="cetakGelangPasienLaki(item)"
                                                                                role="menuitem"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-print"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Gelang Pasien Laki</span>
                                                                                    <span>Cetak Gelang</span>
                                                                                </div>
                                                                            </a>
                                                                            <hr class="dropdown-divider" />
                                                                            <a @click="cetakGelangPasienPerempuan(item)"
                                                                                role="menuitem"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-print"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Gelang Pasien Perempuan</span>
                                                                                    <span>Cetak Gelang</span>
                                                                                </div>
                                                                            </a>
                                                                        </template>
                                                                    </VDropdown>
                                                                    <VDropdown icon="feather:more-vertical" spaced right
                                                                        v-tooltip.top.left="'TINDAK LANJUT RENCANA MUTASI'"
                                                                        v-else>
                                                                        <template #content>
                                                                            <a role="menuitem"
                                                                                @click="terimaRencanaMutasi(item)"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-user-plus"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Terima</span>
                                                                                </div>
                                                                            </a>
                                                                            <a role="menuitem"
                                                                                @click="batalRencanaMutasi(item)"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-ban"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Batal</span>
                                                                                </div>
                                                                            </a>
                                                                            <a v-if="keluser == 11" @click="cetakGelangPasienLaki(item)"
                                                                                role="menuitem"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-print"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Gelang Pasien Laki</span>
                                                                                    <span>Cetak Gelang</span>
                                                                                </div>
                                                                            </a>
                                                                            <a v-if="keluser == 11" @click="cetakGelangPasienPerempuan(item)"
                                                                                role="menuitem"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-print"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Gelang Pasien Perempuan</span>
                                                                                    <span>Cetak Gelang</span>
                                                                                </div>
                                                                            </a>
                                                                        </template>
                                                                    </VDropdown>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </TransitionGroup>
                                                </div>

                                            </div>
                                             <div class="column is-12 p-0">
                                              <VFlexPagination v-model:current-page="currentPageMutasi.page" :item-per-page="currentPageMutasi.limit"
                                                :total-items="dataRencanaMutasi.total" :max-links-displayed="5">

                                                <template #before-pagination>
                                                </template>

                                                <template #before-navigation>
                                                  <VFlex class="mr-4 mt-1" column-gap="1rem">
                                                    <VField>

                                                    </VField>
                                                    <VField>
                                                      <VControl>
                                                        <div class="select is-rounded">
                                                          <select v-model="currentPageMutasi.limit">
                                                            <option :value="data" v-for="(data, index) in H.pagination().typeSmall" :key="index">{{ data }} results per page</option>
                                                          </select>
                                                        </div>
                                                      </VControl>
                                                    </VField>
                                                  </VFlex>
                                                </template>
                                              </VFlexPagination>
                                            </div>
                                        </div>
                                    </TabPanel>
                                    <TabPanel>
                                        <template #header>
                                            <i class="fas fa-times-circle mr-2" aria-hidden="true"></i>
                                            <span>Daftar Ditolak</span>
                                            <Badge :value="dataRencanaMutasiDitolak.length"
                                                v-if="dataRencanaMutasiDitolak.length > 0" severity="danger"
                                                class="ml-2" />
                                        </template>
                                        <div v-if="activeTab == 1">
                                            <div class="list-view list-view-v3">
                                                <div class="search-menu" style="margin-bottom : 1rem;">
                                                    <div class="search-location" style="width: 100%">
                                                        <i class="iconify" data-icon="feather:search"></i>
                                                        <input type="text"
                                                            placeholder="Cari Nama Pasien, No RM, BPJS, Atau NIK"
                                                            v-model="item.search"
                                                            v-on:keyup.enter="fetchDataDitolak()" />
                                                    </div>
                                                    <VButton raised class="search-button" :loading="isLoading"
                                                        @click="fetchDataDitolak()"> Cari Data
                                                    </VButton>
                                                </div>

                                                <VPlaceholderPage
                                                    :class="[dataRencanaMutasiDitolak.length !== 0 && 'is-hidden']"
                                                    title="Tidak Ada Pasien Pulang Saat Ini."
                                                    subtitle="Silakan Pulangkan Pasien dari Rawat Inap." larger>
                                                    <template #image>
                                                        <img class="light-image"
                                                            src="/@src/assets/illustrations/placeholders/search-4.png"
                                                            alt="" />
                                                        <img class="dark-image"
                                                            src="/@src/assets/illustrations/placeholders/search-4.png"
                                                            alt="" />
                                                    </template>
                                                </VPlaceholderPage>

                                                <div class="list-view-inner" style="max-height:300px;overflow: auto;">
                                                    <TransitionGroup name="list-complete" tag="div">
                                                        <!--Item-->
                                                        <div v-for="item in dataRencanaMutasiDitolak" :key="item.id"
                                                            class="list-view-item">
                                                            <div class="list-view-item-inner">
                                                                <VAvatar size="small"
                                                                    picture="/images/avatars/svg/pasien.svg"
                                                                    color="primary" squared bordered />
                                                                <div class="meta-left">
                                                                    <h3>
                                                                        {{ item.namapasien }} |
                                                                        <VTag v-if="item.kelompokpasien != null"
                                                                            class="mt-3 ml-2"
                                                                            :label="item.kelompokpasien"
                                                                            :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'"
                                                                            rounded /> |
                                                                        <i class="bulet fas fa-circle"></i>
                                                                        {{ item.namakelas }}
                                                                    </h3>
                                                                    <span>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="ic:baseline-house"></i>
                                                                        <span>{{ item.namakotakabupaten }} / {{
                                            item.namakecamatan }}</span><br>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:map-pin"></i>
                                                                        <span style="font-weight: bold;">{{
                                            item.namaruangan }} - {{ item.namakamar }} -
                                                                            {{
                                            item.reportdisplay
                                        }}</span><br>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:clock"></i>
                                                                        <span>{{ H.formatDateIndo(item.tglorder)
                                                                            }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:check-circle"></i>
                                                                        <span>{{ item.noregistrasi }}</span><br>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:clipboard"></i>
                                                                        <span>{{ item.nocm }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:clipboard"></i>
                                                                        <span>{{ item.nobpjs }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="teenyicons:id-outline"></i>
                                                                        <span>{{ item.noidentitas }}</span>
                                                                    </span><br>
                                                                    <span style="font-weight: bold;">DPJP :
                                                                        <i aria-hidden="true" class="iconify"></i>
                                                                        <span>{{ item.namalengkap }}</span>
                                                                    </span>
                                                                </div>

                                                                <div class="meta-right">
                                                                    <VDropdown icon="feather:more-vertical" spaced right
                                                                        v-tooltip.top.left="'TINDAK LANJUT DITOLAK'">
                                                                        <template #content>
                                                                            <a role="menuitem"
                                                                                @click="pindahRuangLain(item)"
                                                                                class="dropdown-item is-media">
                                                                                <div class="icon">
                                                                                    <i class="fas fa-sign-in-alt"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Pindah Ruangan Lain</span>
                                                                                </div>
                                                                            </a>
                                                                        </template>
                                                                    </VDropdown>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </TransitionGroup>
                                                </div>

                                            </div>
                                             <div class="column is-12 p-0">
                                              <VFlexPagination v-model:current-page="currentPageTolakMutasi.page" :item-per-page="currentPageTolakMutasi.limit"
                                                :total-items="dataRencanaMutasiDitolak.total" :max-links-displayed="5">

                                                <template #before-pagination>
                                                </template>

                                                <template #before-navigation>
                                                  <VFlex class="mr-4 mt-1" column-gap="1rem">
                                                    <VField>

                                                    </VField>
                                                    <VField>
                                                      <VControl>
                                                        <div class="select is-rounded">
                                                          <select v-model="currentPageTolakMutasi.limit">
                                                            <option :value="data" v-for="(data, index) in H.pagination().typeSmall" :key="index">{{ data }} results per page</option>
                                                          </select>
                                                        </div>
                                                      </VControl>
                                                    </VField>
                                                  </VFlex>
                                                </template>
                                              </VFlexPagination>
                                            </div>
                                        </div>
                                    </TabPanel>
                                </TabView>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="column is-4">
                    <UIWidget class="search-widget">
                        <template #body>
                            <div class="field">
                                <div class="control">
                                    <input v-model="filters" class="input custom-text-filter"
                                        placeholder="Cari Ruagan / Kelas / Kamar" />
                                    <button class="searcv-button" @click="fetchDaftarKamar()">
                                        <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </UIWidget>
                    <div class="column border-custom mb-2 mt-5-min">
                        <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Daftar Kamar {{
                                            item.namaruangan ? item.namaruangan : '' }}
                        </span>
                    </div>
                    <div class="tile-grid tile-grid-v2">
                        <!--List Empty Search Placeholder -->
                        <VPlaceholderPage :class="[dataKamar.length !== 0 && 'is-hidden']"
                            title="We couldn't find any matching results." subtitle="Too bad. Looks like we couldn't find any matching results for the
             search terms you've entered. Please try different search terms or
             criteria." larger>
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
                                <div v-for="item in dataKamar" :key="item.id" class="column is-12">
                                    <div class="tile-grid-item">
                                        <div class="tile-grid-item-inner">
                                            <VAvatar size="small" picture="/images/avatars/svg/roo.png" color="primary"
                                                squared bordered />
                                            <div class="meta">
                                                <span class="dark-inverted">{{ item.namaruangan }}</span>
                                                <span>{{ item.namakamar }}</span>
                                                <span>{{ item.namakelas }}</span>
                                            </div>
                                            <!-- <VTag :label="item.kosong + ' Terpakai'" color="danger" rounded /> -->
                                            <VTag :label="item.isi + ' Tersedia'"
                                                :color="item.isi == 0 ? 'danger' : 'success'" style="margin-left: auto;"
                                                rounded />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <VModal :open="modalInput" title="Terima Rencana Mutasi" :noclose="false" size="big" actions="right"
        @close="modalInput = false">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline p-1" v-if="!item.isterima">
                                <div class="column is-4">
                                    <VField label="Tanggal Rencana Pindah" class="is-rounded-select">
                                        <VControl class="prime-auto ">
                                            <div class="is-rounded is-rounded-select">
                                                <Calendar v-model="item.tglpindah" selectionMode="single"
                                                    :manualInput="true" class="w-100 is-rounded" showTime
                                                    :showIcon="true" hourFormat="24" :date-format="'yy-mm-dd'" />
                                            </div>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField horizontal label="&nbsp;">
                                        <VControl>
                                            <VSwitchBlock v-model="item.israwatgabung" label="Rawat Gabung"
                                                color="danger" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline p-1">
                                <div class="column is-4">
                                    <VField label="Ruangan Rencana Pindah "
                                        class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:list" fullwidth>
                                            <Multiselect :disabled="item.isterima" mode="single"
                                                v-model="item.namaruangan" :options="d_RuanganAll"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                autocomplete="off" @select="changeRuangPindah(item.namaruangan)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField label="Kelas Rawat " class="is-rounded-select is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:list" fullwidth>
                                            <Multiselect mode="single" v-model="item.namakelasrawat" :options="d_Kelas"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                autocomplete="off" @select="changeKelas(item.namakelasrawat)" disabled/>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4" v-if="item.namakelasrawat">
                                    <VField label="Kelas Ditanggung " class="is-rounded-select is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:list" fullwidth>
                                            <Multiselect mode="single" v-model="item.namakelas" :options="d_KelasAll"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                autocomplete="off" disabled/>
                                        </VControl>
                                    </VField>
                                </div>
                                <!-- <div class="column is-4">
                                    <VField label="Kamar" class="is-rounded-select is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:list" fullwidth>
                                            <Multiselect mode="single" v-model="item.kamar" :options="d_Kamar"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                autocomplete="off" @select="changeKamar(item.kamar)" disabled/>
                                        </VControl>
                                    </VField>
                                </div> -->
                                <div class="column is-4">
                                    <VField>
                                        <VLabel> Kamar</VLabel>
                                        <VControl>
                                            <VInput type="text" placeholder=" No Histori ..." autocomplete="off"
                                                v-model="item.namakamar" disabled />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VLabel> No. Tempat Tidur</VLabel>
                                        <VControl>
                                            <VInput type="text" placeholder=" No Histori ..." autocomplete="off"
                                                v-model="item.nobed" disabled />
                                        </VControl>
                                    </VField>
                                </div>
                       
                                <div class="column is-12" v-if="!item.isterima">
                                    <VField label="Keterangan">
                                        <VControl>
                                            <VTextarea class="textarea is-rounded" v-model="item.catatan" rows="5"
                                                placeholder="Keterangan" autocomplete="off" autocapitalize="off"
                                                spellcheck="true" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="feather:save" @click="savepindahRuangLain()" :loading="isLoading" color="primary" raised
                v-if="!item.isterima">
                Simpan Pindah
            </VButton>
            <VButton icon="feather:save" @click="saveterimaRencanaMutasi()" :loading="isLoading" color="primary" raised
                v-if="item.isterima">
                Terima
            </VButton>
        </template>

    </VModal>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import MultiSelect from 'primevue/multiselect'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Calendar from 'primevue/calendar';
import { async } from '@firebase/util';
import { useToaster } from '/@src/composable/toaster'
import * as qzService from '/@src/utils/qzTrayService'

useHead({
    title: 'Dashboard Rencana Mutasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const userLogin = useUserSession().getUser()
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const activeTab = ref(0);
const filters = ref('')
const route = useRoute()
const modalInput: any = ref(false)
const item = ref({
    totalRencanaMutasi: 0,
    totalBelumMutasi: 0,
    totalSudahMutasi: 0,
    totalDitolak: 0,
    filterTgl: new Date(),
})
const currentPageMutasi: any = ref({
  limit: 5,
  rows: 50,
})
const currentPageTolakMutasi: any = ref({
  limit: 5,
  rows: 50,
})
let dataRencanaMutasi: any = ref([])
let dataRencanaMutasiDitolak: any = ref([])
let d_Ruangan: any = ref([])
let d_RuanganAll: any = ref([])
let d_Kelas: any = ref([])
let d_KelasAll: any = ref([])
let d_TempatTidur: any = ref([])
let d_Kamar: any = ref([])
let sourceRuangan: any = ref([])
let dataKamar: any = ref([])
let isLoading: any = ref(false)
let isLoad: any = ref(true)
let keluser: any = ref(0)
const fetchDropdown = async () => {
    keluser.value = H.kelompokUserId()
    const response = await useApi().get('/dashboard/dropdown-rencana-mutasi')
    d_RuanganAll.value = response.ruanganAll.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_Ruangan.value.forEach((element) => {
        sourceRuangan.value.push(element)
    })
}

const fetchKelas = async () => {
    await useApi().get(
        `emr/dropdown/kelas_m?select=id,namakelas`
    ).then((response) => {
        d_KelasAll.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
    })
}

const fetchData = async (e: any) => {
    isLoad.value = true
    let ruanganid = ''
    if (sourceRuangan.value != undefined) {
        let itemsRuang = []
        sourceRuangan.value.forEach((element: any) => {
            itemsRuang = [...new Set([...itemsRuang, element.value])]
        });
        ruanganid = `ruanganfk=${itemsRuang}`
    }
    let search = item.value.search ? `&search=${item.value.search}` : ''
    let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
    let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
    let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
    let idPegawai = e ? `&idpegawai=${H.pegawaiLogin().id}` : ''
    let limit: any = currentPageMutasi.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    isLoading.value = true
    dataRencanaMutasi.value = []
    await useApi().get(`/dashboard/rencana-mutasi/list?limit=${limit}&offset=${offset}&${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${search}`).then((response) => {
        dataRencanaMutasi.value = response.data
        dataRencanaMutasi.value.total = response.total

        fetchDetail()
    })

    isLoad.value = false
    isLoading.value = false
}

const fetchDataDitolak = async (e: any) => {
    isLoad.value = true
    let ruanganid = ''
    if (sourceRuangan.value != undefined) {
        let itemsRuang = []
        sourceRuangan.value.forEach((element: any) => {
            itemsRuang = [...new Set([...itemsRuang, element.value])]
        });
        ruanganid = `ruanganfk=${itemsRuang}`
    }
    let search = item.value.search ? `&search=${item.value.search}` : ''
    let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
    let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
    let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
    let idPegawai = e ? `&idpegawai=${H.pegawaiLogin().id}` : ''
    let limit: any = currentPageTolakMutasi.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit

    isLoading.value = true
    dataRencanaMutasiDitolak.value = []
    await useApi().get(`/dashboard/rencana-mutasi-tolak/list?limit=${limit}&offeset=${offset}&${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${search}`).then((response) => {
        dataRencanaMutasiDitolak.value = response.data
        dataRencanaMutasiDitolak.value.total = response.total
        fetchDetail()
    })

    isLoad.value = false
    isLoading.value = false
}

const fetchDetail = async () => {
    let ruanganid = ''
    if (sourceRuangan.value != undefined) {
        let itemsRuang = []
        sourceRuangan.value.forEach((element: any) => {
            itemsRuang = [...new Set([...itemsRuang, element.value])]
        });
        ruanganid = `ruanganfk=${itemsRuang}`
    }

    let tgl = item.value.filterTgl ? `&tgl=${H.formatDate(item.value.filterTgl, 'YYYY-MM-DD')}` : ''

    dataKamar.value = []
    isLoading.value = true
    const response = await useApi().get(`/dashboard/detail-rencana-mutasi?${ruanganid}${tgl}&limit=10`)
    isLoading.value = false
    dataKamar.value = response.data
    item.value.totalRencanaMutasi = response.totalRencanaMutasi
    item.value.totalBelumMutasi = response.totalBelumMutasi
    item.value.totalSudahMutasi = response.totalSudahMutasi
    item.value.totalDitolak = response.totalDitolak

}

const fetchDaftarKamar = async () => {
    let namakamar = ''
    if (filters.value != undefined) {
        namakamar = filters.value
    }

    dataKamar.value = []

    const response = await useApi().get(
        '/dashboard/detail-rencana-mutasi?namakamar=' + namakamar + '&limit=10'
    )
    dataKamar.value = response.data
    item.value.totalRencanaMutasi = response.totalRencanaMutasi
    item.value.totalBelumMutasi = response.totalBelumMutasi
    item.value.totalSudahMutasi = response.totalSudahMutasi
    item.value.totalDitolak = response.totalDitolak
}

const savePindah = async (data: any) => {
    let json = {
        pasiendaftar: {
            norec_pd: data.norec_pd,
            objectruanganasalfk: data.objectruanganfk,
            objectruangantujuanfk: data.objectruangantujuanfk,
        },
        antrianpasiendiperiksa: {
            norec_apd: data.norec_apd,
            tglkeluar: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
            objectkelasfk: data.objectkelasfk,
            objectkelasrawatfk: data.objectkelasrawatfk,
            nobed: data.nobed,
            objectkamarfk: data.objectkamarfk ? data.objectkamarfk : null,
            objectbedfk: data.objectbedfk ? data.objectbedfk : null,
            israwatgabung: data.israwatgabung ? data.israwatgabung : null,
        },
    }
    isLoading.value = true
    await useApi()
        .post(`/rawatinap/save-pindah-pasien`, json)
        .then(async (response: any) => {
            await useApi()
                .post(`/dashboard/update-rencana-mutasi`, { norec: data.norec, action: 'terima' })
                .then((response: any) => {
                    isLoading.value = false
                    modalInput.value = false
                    reload()
                })
        })
        .catch((e: any) => {
            isLoading.value = false
        })
}

const saveRanap = async (data: any) => {
    let json = {
        'pasiendaftar': {
            'norec': '',
            'nocmfk': data.nocmfk,
            'tglregistrasi': H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
            'objectruanganlastfk': data.objectruangantujuanfk,
            'asalrujukanfk': data.asalrujukanfk,
            'keteranganasalrujukan': data.keteranganasalrujukan ? data.keteranganasalrujukan : null,
            'objectkelompokpasienlastfk': data.objectcarabayar_quofk,
            'jenispelayananfk': data.jenispelayanan,
            'objectpegawaifk': data.objectpegawaitujuanfk ? data.objectpegawaitujuanfk : null,
            'objectpegawairawatbersamafk': data.objectpegawaispsfk ? data.objectpegawaispsfk : null,
            'objectkelasfk': data.objectkelasfk ? data.objectkelasfk : null,
            'objectkelasrawatfk': data.objectkelasrawatfk ? data.objectkelasrawatfk : null,
            'israwatinap': true,
            'catatan': data.keteranganlainnya ? data.keteranganlainnya : null,
            'statuspasien': 'LAMA',//item.statuspasien ? item.statuspasien : 'LAMA',
            'objectrekananfk': data.objectrekananfk != undefined ? data.objectrekananfk : null,
            'nocm': data.nocm,
            'namapasien': data.namapasien,
            'iskelastitip': data.iskelastitip,
            'antrianpasienregistrasifk': null,
        },
        'antrianpasiendiperiksa': {
            'norec': '',
            'objectkamarfk': data.objectkamarfk ? data.objectkamarfk : null,
            'nobed': data.objectbedfk ? data.objectbedfk : null,
            'israwatgabung': data.israwatgabung ? true : null,
        }
    }
    isLoading.value = true
    let url = '/registrasi/save-registrasi';
    await useApi().post(url, json).then(async (response: any) => {
        await useApi()
            .post(`/dashboard/update-rencana-mutasi`, { norec: data.norec, action: 'terima' })
            .then((response2: any) => {
                isLoading.value = false
                modalInput.value = false
                reload()
            })

        await useApi()
            .postNoMessage(`sysadmin/save-akomodasi-langsung`, {
                'norec': response.dataPD.norec,
                'norec_apd': response.dataAPD.norec
            })
            .then((response3: any) => { })
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const saveMutasi = async (data: any) => {
    let json = {
        'pasiendaftar': {
            'norec_pd': data.norec_pd,
            'nocmfk': data.nocmfk,
            'objectruangantujuanfk': data.objectruangantujuanfk,
            'asalrujukanfk': data.asalrujukanfk,
            'jenispelayananfk': data.jenispelayanan,
            'objectkelompokpasienlastfk': data.objectcarabayar_quofk,
            'objectpegawaifk': data.objectpegawaitujuanfk ? data.objectpegawaitujuanfk : null,
            'objectkelasfk': data.objectkelasfk ? data.objectkelasfk : null,
            'objectkelasrawatfk': data.objectkelasrawatfk ? data.objectkelasrawatfk : null,
        },
        'antrianpasiendiperiksa': {
            'norec_apd': data.norec_apd,
            'noregistrasifk': data.norec_pd,
            'objectruanganasalfk': data.objectruanganfk,
            'objectruangantujuanfk': data.objectruangantujuanfk,
            'objectasalrujukanfk': data.asalrujukanfk,
            'tglregistrasi': H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
            'objectkelasfk': data.objectkelasfk,
            'objectkelasrawatfk': data.objectkelasrawatfk,
            'objectkamarfk': data.objectkamarfk,
            'objectbedfk': item.value.bed,
            'objectpegawaifk': data.objectpegawaitujuanfk ? data.objectpegawaitujuanfk : null,
            'israwatgabung': data.israwatgabung ? data.israwatgabung : null
        }
    }
    isLoading.value = true
    await useApi().post(
        `/registrasi/save-mutasi`, json).then(async (response: any) => {
            await useApi()
                .post(`/dashboard/update-rencana-mutasi`, { norec: data.norec, action: 'terima' })
                .then((response2: any) => {
                    isLoading.value = false
                    modalInput.value = false
                    reload()
                })

            await useApi()
                .postNoMessage(`sysadmin/save-akomodasi-langsung`, {
                    'norec': response.noregistrasifk,
                    'norec_apd': response.norec
                })
                .then((response3: any) => { })

        }).catch((e: any) => {
            isLoading.value = false
            console.log(e)
        })
}
const terimaRencanaMutasi = async (e: any) => {
    console.log(e)
    try {
        await changeRuangPindah(e.objectruangantujuanfk)
        item.value.namaruangan = e.objectruangantujuanfk

        await changeKelas(e.objectkelasfk)
        item.value.namakelas = e.objectkelasfk

        await changeKamar(e.objectkamarfk)

        item.value.isterima = true
        item.value.namakelasrawat = e.objectkelasrawatfk
        item.value.namakamar = e.namakamar
        item.value.kamar = e.objectkamarfk
        item.value.bed = e.objectbedfk
        item.value.nobed = e.reportdisplay
        item.value.rencanamutasi = e
        modalInput.value = true
    } catch (error) {
        console.error(error);
        // Handle errors here
    }
}
const saveterimaRencanaMutasi = () => {
    // if (!item.value.namaruangan) {
    //     useToaster().error('Nama Ruangan  harus di isi')
    //     return
    // }
    // if (!item.value.namakelas) {
    //     useToaster().error('Nama Kelas Rawat  harus di isi')
    //     return
    // }
    // if (!item.value.namakelasrawat) {
    //     useToaster().error('Nama Kelas Rawat  harus di isi')
    //     return
    // }
    // if (!item.value.kamar) {
    //     useToaster().error('Nama Kamar Rawat  harus di isi')
    //     return
    // }
    // if (!item.value.bed) {
    //     useToaster().error('Nama Bed Rawat  harus di isi')
    //     return
    // }

    console.log(item.value.rencanamutasi);
    let data = item.value.rencanamutasi
    data.objectkelasfk = item.value.namakelas
    data.objectkelasrawatfk = item.value.namakelasrawat
    data.objectkamarfk = item.value.kamar
    data.objectbedfk = item.value.bed
    switch (data.keterangaantrian) {
        case "pindah ruangan":
            savePindah(data)
            break;
        case "registrasi":
            saveRanap(data)
            break;
        case "mutasi":
            saveMutasi(data)
            break;
    }
}
const batalRencanaMutasi = async (e: any) => {
    await useApi()
        .post(`/dashboard/update-rencana-mutasi`, { norec: e.norec, action: 'tolak' })
        .then((response: any) => {
            reload()
        })
}
const pindahRuangLain = async (e: any) => {
    item.value.tglpindah = new Date()
    item.value.norec = e.norec
    modalInput.value = true
}
const savepindahRuangLain = async () => {
    if (!item.value.tglpindah) {
        useToaster().error('Tanggal Pindah Ruangan  harus di isi')
        return
    }
    if (!item.value.namaruangan) {
        useToaster().error('Nama Ruangan  harus di isi')
        return
    }
    if (!item.value.namakelas) {
        useToaster().error('Nama Kelas Rawat  harus di isi')
        return
    }
    if (!item.value.namakelasrawat) {
        useToaster().error('Nama Kelas Rawat  harus di isi')
        return
    }
    if (!item.value.kamar) {
        useToaster().error('Nama Kamar Rawat  harus di isi')
        return
    }
    if (!item.value.bed) {
        useToaster().error('Nama Bed Rawat  harus di isi')
        return
    }

    let json = {
        norec: item.value.norec,
        objectruangantujuanfk: item.value.namaruangan,
        objectkelasfk: item.value.namakelas,
        objectkelasrawatfk: item.value.namakelasrawat,
        objectkamarfk: item.value.kamar,
        objectbedfk: item.value.bed,
        action: 'ubah',
    }
    isLoading.value = true
    await useApi()
        .post(`/dashboard/update-rencana-mutasi`, json)
        .then((response: any) => {
            modalInput.value = false
            isLoading.value = false
            reload()
        })
}

const klikTab = (e: any) => {
    activeTab.value = e.index
    console.log(activeTab.value);
    if (activeTab.value == 0) {
        fetchData()
    }
    if (activeTab.value == 1) {
        // fetchDataDitolak()
    }
}
const changeRuang = async (e: any) => {
    reload()
}

const reload = () => {
    fetchData()
    fetchDataDitolak()
}

const changeRuangPindah = (e: any) => {
    item.value.namakelas = null;
    if (e) {
        setKelas(e)
    }
}

const setKelas = async (e: any) => {
    d_Kelas.value = []
    await useApi().get(
        `/rawatinap/kelas-ranap-by-ruangan?id=${e}`)
        .then((response: any) => {
            if (response.length == 1) {
                item.value.namakelas = response[0].id
            }
            d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
        })
        .catch((error: any) => { console.log(error) })
}

const changeKelas = async (e: any) => {
    d_Kamar.value = []
    delete item.value.kamar
    delete item.value.bed
    if (e && item.value.namaruangan) {
        await useApi().get(
            `/rawatinap/kamar-ranap-by-kelas?id=${e}&idRuangan=${item.value.namaruangan}&isRG=false`)
            .then((response: any) => {
                d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
            })
            .catch((error: any) => { console.log(error) })
    }

}

const changeKamar = async (e: any) => {
    d_TempatTidur.value = []
    delete item.value.bed
    if (e) {
        for (let x = 0; x < d_Kamar.value.length; x++) {
            const element = d_Kamar.value[x];
            if (element.value == e) {
                d_TempatTidur.value = element.default.details.map((e: any) => { return { label: e.reportdisplay, value: e.id, default: e } })
            }
        }
    }
}

const cetakSEP = (e: any) => {
    if (!e.noregistrasi) {
        useToaster().error('Data tidak ditemukan')
        return
    }
    H.printBlade('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=false")
    // qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true", 'SEP', 1)
}
const cetakLabel2 = async (e: any) => {
    if (!e.noregistrasi) {
        useToaster().error('Data tidak ditemukan')
        return
    }
    qzService.printData(`dashboard/registrasi/cetak-label-pasien-2?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', 1)
    // H.printBlade(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}
const cetakBuktiPelayanan = (e: any) => {
    if (!e.norec_pd) {
        useToaster().error('Data tidak ditemukan')
        return
    }
    qzService.printData(`report/cetak-bukti-pelayanan-2?norec_pd=${e.norec_pd}&rm=${e.namaruangan}&ins=${e.namadepartemen}`, 'CETAK BUKTI LAYANAN', 1)
    // H.printBlade(`report/cetak-bukti-pelayanan?norec_pd=${e.norec_pd}`);
}
const cetakBuktiPelayanan2 = (e: any) => {
    if (!e.norec_pd) {
        useToaster().error('Data tidak ditemukan')
        return
    }
    qzService.printData(`report/cetak-bukti-pelayanan-2?norec_pd=${e.norec_pd}`, 'CETAK BUKTI LAYANAN', 1)
    // H.printBlade(`report/cetak-bukti-pelayanan?norec_pd=${e.norec_pd}`);
}

const cetakGelangPasienLaki = (e: any) => {
    if (!e.norec_pd) {
        useToaster().error('Data tidak ditemukan')
        return
    }
    qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'GELANG PASIEN LAKI', 1)
    // H.printBlade(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}

const cetakGelangPasienPerempuan = (e: any) => {
    if (!e.norec_pd) {
        useToaster().error('Data tidak ditemukan')
        return
    }
    qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'GELANG PASIEN PEREMPUAN', 1)
    // H.printBlade(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}

qzService.connect()
fetchDropdown()
fetchKelas()
fetchData()
fetchDataDitolak()
watch(currentPageMutasi.value, () => {
  fetchData()
})
watch(currentPageTolakMutasi.value, () => {
  fetchDataDitolak()
})
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
    position: relative !important;
    background: var(--fade-grey-light-2) !important;
    border: 1px solid var(--fade-grey) !important;
    max-width: 70% !important;
    height: 35px !important;
    border-bottom: none !important;
}

.lifestyle-dashboard-v4 {
    .illustration-header-2 {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 16px;
        background: var(--primary-dark-24);
        font-family: var(--font);
        box-shadow: var(--primary-box-shadow);

        .header-image {
            position: relative;
            height: 175px;
            width: 320px;

            img {
                position: absolute;
                top: 0;
                left: -40px;
                display: block;
                pointer-events: none;
            }
        }

        .header-meta {
            margin-left: 0;
            padding-right: 30px;

            h3 {
                color: var(--smoke-white);
                font-family: var(--font-alt);
                font-weight: 700;
                font-size: 1.3rem;
                max-width: 280px;
            }

            p {
                font-weight: 400;
                color: var(--smoke-white-dark-2);
                margin-bottom: 16px;
                max-width: 320px;
            }

            .action-link {
                span {
                    font-size: 0.8rem;
                    text-transform: uppercase;
                    margin-right: 6px;
                }

                i {
                    font-size: 12px;
                }
            }
        }
    }

    .writing-stats {
        display: flex;
        margin-bottom: 1rem;
        margin-left: -8px;
        margin-right: -8px;

        .writing-stat {
            @include vuero-l-card;

            margin: 8px;
            width: calc(33.3% - 16px);
            padding: 12px;

            span {
                display: block;

                &:first-child {
                    font-family: var(--font-alt);
                    font-size: 0.8rem;
                    font-weight: 500;
                    text-transform: uppercase;
                    margin-bottom: 5px;
                    color: var(--light-text);
                }

                &:nth-child(2) {
                    font-family: var(--font);
                    font-weight: 700;
                    font-size: 1.8rem;
                    color: var(--dark-text);
                }
            }
        }
    }

    .featured-authors {
        @include vuero-l-card;

        padding: 20px;

        .featured-authors-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;

            h3 {
                font-family: var(--font-alt);
                font-weight: 600;
                font-size: 1.1rem;
                color: var(--dark-text);
            }

            .action-link {
                font-size: 0.9rem;
            }
        }

        .featured-authors-list {
            .featured-authors-item {
                &:not(:last-child) {
                    margin-bottom: 20px;
                }

                .media-flex-center {
                    .flex-end {
                        span {
                            font-family: var(--font-alt);
                            font-weight: 600;
                            color: var(--dark-text);
                        }
                    }
                }
            }
        }
    }

    .updates {
        @include vuero-l-card;

        padding: 20px;
        margin-top: 8px;

        .updates-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;

            h3 {
                font-family: var(--font-alt);
                font-weight: 600;
                font-size: 1.1rem;
                color: var(--dark-text);
            }

            .action-link {
                font-size: 0.9rem;
            }
        }

        .updates-list {
            .update-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 16px;
                padding-bottom: 16px;
                border-bottom: 1px solid var(--fade-grey-dark-3);

                &:last-child {
                    margin-bottom: 0;
                    border-bottom: none;
                }

                p {
                    font-size: 0.9rem;
                }

                span {
                    display: block;
                    min-width: 60px;
                    text-align: right;
                    font-family: var(--font);
                    font-weight: 600;
                    font-size: 0.8rem;
                    color: var(--dark-text);
                }
            }
        }
    }

    .page-placeholder .placeholder-content h3 {
        font-size: 0.9rem;
        font-weight: 600;
        font-family: var(--font-alt);
        color: var(--dark-text);
    }

    .tile-grid-v2 {
        .tile-grid-item {
            @include vuero-s-card;

            border-radius: 14px;
            padding: 16px;
            cursor: pointer;

            &:hover,
            &:focus {
                border-color: var(--primary);
                box-shadow: var(--light-box-shadow);
            }

            .tile-grid-item-inner {
                display: flex;
                align-items: center;

                >img {
                    display: block;
                    width: 50px;
                    height: 50px;
                    min-width: 50px;
                }

                .meta {
                    margin-left: 10px;
                    line-height: 1.4;

                    span {
                        display: block;
                        font-family: var(--font);

                        &:first-child {
                            color: var(--dark-text);
                            font-family: var(--font-alt);
                            font-weight: 600;
                            font-size: 0.9rem;
                        }

                        &:nth-child(2) {
                            display: flex;
                            align-items: center;

                            span {
                                display: inline-block;
                                color: var(--light-text);
                                font-size: 0.8rem;
                                font-weight: 400;
                            }

                            .icon-separator {
                                position: relative;
                                font-size: 4px;
                                color: var(--light-text);
                                padding: 0 6px;
                            }
                        }
                    }
                }

                .dropdown {
                    margin-left: auto;
                }
            }
        }
    }

    .is-dark {
        .tile-grid {
            .tile-grid-item {
                @include vuero-card--dark;
            }
        }

        .tile-grid-v2 {
            .tile-grid-item {
                @include vuero-card--dark;

                &:hover,
                &:focus {
                    border-color: var(--primary) !important;
                }
            }
        }
    }

    .articles-feed {
        background: var(--widget-grey);
        padding: 30px;
        border-radius: 12px;

        .articles-feed-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;

            h3 {
                font-family: var(--font-alt);
                font-weight: 600;
                font-size: 1.1rem;
                color: var(--dark-text);
            }

            .action-link {
                font-size: 0.9rem;
            }
        }

        .articles-feed-subheader {
            margin-bottom: 20px;

            .selector {
                .button {
                    font-size: 0.8rem;
                    border-radius: 50px;
                    margin-right: 4px;

                    &.is-selected {
                        background: var(--primary);
                        color: var(--white);
                        border-color: var(--primary);
                        box-shadow: var(--primary-box-shadow);
                    }
                }
            }
        }

        .articles-feed-list {
            .articles-feed-list-inner {
                .articles-feed-item {
                    display: block;

                    &:not(:last-child) {
                        margin-bottom: 20px;
                    }

                    .featured-image {
                        height: 180px;
                        overflow: hidden;
                        border-top-left-radius: 18px;
                        border-top-right-radius: 18px;

                        img {
                            display: block;
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }
                    }

                    .featured-content {
                        position: relative;
                        padding: 25px;
                        border-radius: 18px;
                        background: var(--white);
                        margin-top: -40px;
                        z-index: 1;

                        h4,
                        p {
                            margin-bottom: 10px;
                        }

                        h4 {
                            font-family: var(--font-alt);
                            font-size: 1rem;
                            font-weight: 600;
                            color: var(--dark-text);
                        }

                        .media-flex-center {
                            .flex-meta {
                                span {
                                    font-size: 0.8rem;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

.is-dark {
    .lifestyle-dashboard-v4 {
        .illustration-header-2 {
            background: var(--dark-sidebar);
            box-shadow: none;
        }

        .writing-stats {
            .writing-stat {
                @include vuero-card--dark;
            }
        }

        .updates,
        .featured-authors {
            @include vuero-card--dark;
        }

        .articles-feed {
            background: var(--dark-sidebar-light-8);

            .articles-feed-subheader {
                .selector {
                    .button {
                        &.is-selected {
                            background: var(--primary) !important;
                            border-color: var(--primary) !important;
                            box-shadow: var(--primary-box-shadow) !important;
                            color: var(--white) !important;
                        }
                    }
                }
            }

            .articles-feed-list {
                .articles-feed-list-inner {
                    .articles-feed-item {
                        .featured-content {
                            background: var(--dark-sidebar);
                        }
                    }
                }
            }
        }
    }
}

.hr-dashboard {
    .block-header {
        display: flex;
        border-radius: 16px;
        padding: 50px;
        background: var(--primary);
        font-family: var(--font);
        box-shadow: var(--primary-box-shadow);

        .left,
        .right {
            width: 30%;
        }

        .center {
            display: flex;
            flex-direction: column;
            width: 40%;
            padding-right: 30px;
            margin-right: 30px;
            border-right: 1px solid var(--primary-light-10);

            .block-text {
                margin-bottom: 16px;
            }

            .candidates {
                margin-top: auto;

                >.v-avatar {
                    margin-right: 10px;
                }

                button {
                    height: 40px;
                    width: 40px;
                    display: inline-flex;
                    justify-content: center;
                    align-items: center;
                    border-radius: 10px;
                    background: var(--white);
                    color: var(--light-text);
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s; // transition-all test

                    svg {
                        height: 18px;
                        width: 18px;
                    }
                }
            }
        }

        .left {
            display: flex;
            justify-content: center;
            align-items: center;

            .current-user {
                .v-avatar {
                    margin-bottom: 1rem;
                }

                h3 {
                    font-family: var(--font-alt);
                    font-weight: 700;
                    font-size: 1.8rem;
                    color: var(--white);
                    line-height: 1.2;
                }
            }
        }

        .right {
            display: flex;
            flex-direction: column;

            .button {
                margin-top: auto;
            }
        }

        .block-heading {
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--white);
            margin-bottom: 4px;
        }

        .block-text {
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--white);
            margin-bottom: 16px;
        }

        .header-meta {
            margin-left: 0;
            padding-right: 30px;

            h3 {
                color: var(--smoke-white);
                font-family: var(--font-alt);
                font-weight: 700;
                font-size: 1.3rem;
                max-width: 280px;
            }

            p {
                font-weight: 400;
                color: var(--smoke-white-dark-2);
                margin-bottom: 16px;
                max-width: 320px;
            }

            .action-link {
                span {
                    font-size: 0.8rem;
                    text-transform: uppercase;
                    margin-right: 6px;
                }

                i {
                    font-size: 12px;
                }
            }
        }
    }

    .feed-settings {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;

        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        .button {
            font-size: 0.8rem;
            border-radius: 8px;
            margin-right: 4px;

            &.is-selected {
                background: var(--primary);
                color: var(--white);
                border-color: var(--primary);
                box-shadow: var(--primary-box-shadow);
            }
        }
    }

    .side-text {
        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 8px;
        }

        p {
            font-size: 0.95rem;
            margin-bottom: 8px;
        }

        .action-link {
            font-size: 0.9rem;
        }
    }

    .recent-rookies {
        .recent-rookies-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;

            h3 {
                font-family: var(--font-alt);
                font-size: 2rem;
                font-weight: 600;
                color: var(--dark-text);
            }
        }

        .user-grid {
            &.user-grid-v4 {
                .grid-item {
                    @include vuero-l-card;
                }
            }
        }
    }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
    position: relative;
    background: var(--fade-grey-light-2);
    border: 1px solid var(--fade-grey);
    max-width: 70%;
    height: 35px;
    border-bottom: none;

}

.tile-grid {
    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }
}

.tile-grid-v2 {
    .tile-grid-item {
        @include vuero-s-card;

        border-radius: 14px;
        padding: 16px;
        cursor: pointer;

        &:hover,
        &:focus {
            border-color: var(--primary);
            box-shadow: var(--light-box-shadow);
        }

        .tile-grid-item-inner {
            display: flex;
            align-items: center;

            >img {
                display: block;
                width: 50px;
                height: 50px;
                min-width: 50px;
            }

            .meta {
                margin-left: 10px;
                line-height: 1.4;

                span {
                    display: block;
                    font-family: var(--font);

                    &:first-child {
                        color: var(--dark-text);
                        font-family: var(--font-alt);
                        font-weight: 600;
                        font-size: 1rem;
                    }

                    &:nth-child(2) {
                        display: flex;
                        align-items: center;

                        span {
                            display: inline-block;
                            color: var(--light-text);
                            font-size: 0.8rem;
                            font-weight: 400;
                        }

                        .icon-separator {
                            position: relative;
                            font-size: 4px;
                            color: var(--light-text);
                            padding: 0 6px;
                        }
                    }
                }
            }

            .dropdown {
                margin-left: auto;
            }
        }
    }
}

.title.is-5 {
    font-size: 1.05rem;
}

.is-dark {
    .tile-grid {
        .tile-grid-item {
            @include vuero-card--dark;
        }
    }

    .tile-grid-v2 {
        .tile-grid-item {
            @include vuero-card--dark;

            &:hover,
            &:focus {
                border-color: var(--primary) !important;
            }
        }
    }
}

.soccer-dashboard {
    .soccer-dashboard-inner {
        .live-match {
            @include vuero-l-card;

            padding: 1.5rem;
            margin-bottom: 1.5rem;

            .head {
                margin-bottom: 1.5rem;

                .league {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;

                    .left {
                        span {
                            display: block;
                            font-family: var(--font);

                            &:first-child {
                                color: var(--dark-text);
                            }

                            &:nth-child(2) {
                                color: var(--light-text);
                                font-size: 0.9rem;
                            }
                        }
                    }

                    .right {
                        .live-block {
                            display: inline-flex;
                            align-items: center;
                            padding: 0.25rem 0.75rem;
                            border-radius: 50rem;
                            background: var(--danger);
                            color: var(--white);
                            box-shadow: var(--danger-box-shadow);

                            span {
                                display: inline-block;
                                font-family: var(--font);
                                font-size: 0.85rem;
                                margin-left: 0.25rem;
                            }
                        }
                    }
                }
            }

            .match {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1.5rem;

                .left,
                .right {
                    text-align: center;

                    .team-logo {
                        display: block;
                        min-width: 50px;
                        max-width: 50px;
                        text-align: center;
                        margin-bottom: 0.25rem;
                    }

                    .team-name {
                        display: block;
                        font-family: var(--font);
                        font-weight: 500;
                    }
                }

                .center {
                    display: flex;
                    justify-content: center;
                    align-items: center;

                    .score {
                        display: block;
                        font-family: var(--font);
                        font-weight: 700;
                        font-size: 2.20rem;
                    }

                    .separator {
                        position: relative;
                        top: -2px;
                        display: block;
                        padding: 0 0.5rem;
                        font-family: var(--font);
                        font-weight: 700;
                        font-size: 1.75rem;
                    }
                }
            }

            .action {
                .v-button {
                    border-radius: 0.65rem;
                    height: 44px;
                }
            }
        }

        .leagues {
            @include vuero-l-card;

            padding: 2rem;

            .head {
                margin-bottom: 1.5rem;
            }

            .leagues-list {
                .league-item {
                    display: flex;
                    align-items: center;

                    &:not(:last-child) {
                        margin-bottom: 1rem;
                    }

                    .league-logo {
                        display: block;
                        min-width: 38px;
                        max-width: 38px;
                    }

                    .meta {
                        margin-left: 0.5rem;
                        line-height: 1.2;

                        .league-name {
                            display: block;
                            font-family: var(--font-alt);
                            font-size: 1rem;
                            font-weight: 600;
                            color: var(--dark-text);
                        }

                        .league-country {
                            display: block;
                            font-family: var(--font);
                            font-size: 0.9rem;
                            color: var(--light-text);
                        }
                    }

                    .end {
                        margin-left: auto;
                        font-family: var(--font);
                        font-size: 0.9rem;
                        color: var(--light-text);
                    }
                }
            }
        }

        .dashboard-cta {
            background-color: var(--primary);
            padding: 2rem;
            border-radius: 1rem;
            position: relative;
            margin-bottom: 1.5rem;

            .dashboard-cta-title {
                font-family: var(--font-alt);
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--white);
                margin: 0 0 0.25rem;
            }

            .dashboard-cta-text {
                color: var(--white);
                opacity: 0.9;
                font-family: var(--font);
                line-height: 1.7;
                margin-top: 0;
                max-width: 58%;
                margin-bottom: 1rem;
            }

            .dashboard-cta-img {
                width: 40%;
                max-width: 350px;
                position: absolute;
                overflow: hidden;
                height: calc(110%);
                top: -10%;
                right: 2rem;

                img {
                    width: 100%;
                    height: auto;
                }
            }
        }

        .matches-card {
            @include vuero-l-card;

            padding: 0;
            overflow: hidden;

            .matches-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 3rem;
                padding: 2rem 2rem 0;

                .header-nav {
                    display: flex;

                    .nav-item {
                        .nav-link {
                            font-family: var(--font);
                            margin-right: 1rem;
                            border-bottom: 3px solid transparent;
                            padding-bottom: 1rem;
                            color: var(--light-text);

                            &.is-active {
                                color: var(--dark-text);
                                border-bottom-color: var(--primary);
                            }
                        }
                    }
                }
            }

            .matches-card-body {
                overflow-x: auto;

                .table {
                    width: 100%;

                    thead th {
                        border: none;
                        font-family: var(--font);
                        font-size: 0.8rem;
                        text-transform: uppercase;
                    }

                    tr {

                        th:first-child,
                        td:first-child {
                            padding-left: 2rem;
                        }

                        th:last-child,
                        td:last-child {
                            padding-right: 2rem;
                        }

                        td {
                            padding-top: 1.5rem;
                            padding-bottom: 1.5rem;

                            &.score-cell {
                                min-width: 300px;
                            }

                            .match-time-row {
                                display: flex;
                                align-items: center;

                                .match-time {
                                    font-family: var(--font);
                                    color: var(--light-text);
                                    margin-right: 0.75rem;
                                }

                                .tag {
                                    font-family: var(--font);

                                    svg {
                                        color: var(--warning);
                                    }

                                    &.is-live {
                                        svg {
                                            color: var(--danger);
                                        }
                                    }
                                }
                            }

                            .table-action {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 36px;
                                width: 36px;
                                border-radius: 50%;
                                color: var(--light-text);
                                transition: background-color 0.3s;

                                &:hover,
                                &:focus {
                                    background: var(--widget-grey);
                                }
                            }
                        }
                    }

                    .score {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        width: 100%;

                        .score-vertical {
                            justify-content: flex-start;
                        }

                        .score-team {
                            text-align: center;

                            span {
                                display: block;
                                font-weight: 500;
                                padding-top: 0.25rem;
                            }

                            img {
                                width: 40px;
                            }

                            &.score-team-vertical {
                                display: flex;
                                align-items: center;
                                flex: 1;

                                &:first-child {
                                    justify-content: flex-end;
                                }

                                span {
                                    white-space: nowrap;
                                    font-size: inherit;
                                }

                                img {
                                    width: 32px;
                                    margin: 0 0.5rem;
                                }
                            }
                        }

                        .score-result {
                            text-align: center;
                            width: 100%;
                            font-weight: 900;
                            font-size: 1.75rem;
                            margin: 0;
                            letter-spacing: 0.4em;

                            &.score-result-not-started {
                                color: gray;
                            }

                            &.score-result-vertical {
                                letter-spacing: 0.2em;
                                font-size: inherit;
                                flex: 0 0 auto;
                                width: auto;
                            }
                        }
                    }
                }
            }
        }

        .matches {
            .nav-item {
                &:first-child {
                    .nav-link {
                        padding-left: 0;
                    }
                }

                .nav-link {
                    padding-top: 0;
                    padding-bottom: 0;
                }
            }
        }
    }
}

.is-dark {
    .soccer-dashboard {
        .soccer-dashboard-inner {

            .live-match,
            .leagues {
                @include vuero-card--dark;

                .head {
                    .title {
                        color: var(--white) !important;
                    }
                }

                .match {

                    .left,
                    .right {
                        .team-name {
                            color: var(--white) !important;
                        }
                    }
                }

                .leagues-list {
                    .league-item {
                        .meta {
                            span:first-child {
                                color: var(--white) !important;
                            }
                        }
                    }
                }
            }

            .matches-card {
                @include vuero-card--dark;

                .matches-card-header {
                    .header-nav {
                        .nav-item {
                            .nav-link {
                                &.is-active {
                                    color: var(--white) !important;
                                }
                            }
                        }
                    }
                }
            }

            .matches-card-body {
                .table {
                    .score {
                        .score-team {
                            &.score-team-vertical {
                                >span {
                                    color: var(--white) !important;
                                }
                            }
                        }
                    }

                    tr td {
                        .table-action {
                            &:hover {
                                background: var(--dark-sidebar-light-2) !important;
                            }
                        }
                    }
                }
            }
        }
    }
}

.user-grid {
    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }

    .grid-item {
        position: relative;
        @include vuero-s-card;

        text-align: center;

        &:hover,
        &:focus {
            .button-wrap {
                >div {
                    a {
                        opacity: 1;
                        pointer-events: all;
                    }
                }
            }
        }

        .dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
            text-align: left;
        }

        >.v-avatar {
            display: block;
            margin: 0 auto 4px;
        }

        h3 {
            font-family: var(--font-alt);
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        p {
            font-size: 0.7rem;
        }

        .button-wrap {
            margin: 20px 0 0;

            .v-button {
                width: 100%;
                max-width: 120px;
                margin: 0 auto;
            }

            >div {
                margin: 6px 0 0;

                a {
                    opacity: 0;
                    pointer-events: none;
                    color: var(--light-text);
                    font-weight: 500;
                    font-size: 0.8rem;
                    transition: opacity 0.3s, color 0.3s;

                    &:hover,
                    &:focus {
                        color: var(--primary);
                    }
                }
            }
        }
    }
}

.is-dark {
    .user-grid {
        .grid-item {
            @include vuero-card--dark;
        }
    }

    .hr-dashboard {
        .block-header {
            background: var(--dark-sidebar);
            box-shadow: none;

            .center {
                border-color: var(--dark-sidebar-light-10);

                .candidates {
                    button {
                        background: var(--dark-sidebar-light-10);
                        border: 1px solid transparent;
                        transition: all 0.3s; // transition-all test

                        &:hover {
                            border-color: var(--primary);

                            svg {
                                color: var(--primary);
                            }
                        }
                    }
                }
            }
        }

        .feed-settings {
            .button {
                &.is-selected {
                    background: var(--primary) !important;
                    border-color: var(--primary) !important;
                    box-shadow: var(--primary-box-shadow) !important;
                    color: var(--white) !important;
                }
            }
        }

        .recent-rookies {
            .user-grid {
                &.user-grid-v4 {
                    .grid-item {
                        @include vuero-card--dark;
                    }
                }
            }
        }
    }
}

.list-view-v3 {
    .list-view-item {
        @include vuero-r-card;

        margin-bottom: 16px;
        padding: 16px;

        .list-view-item-inner {
            display: flex;
            align-items: center;

            >img {
                width: 100%;
                max-width: 60px;
                min-width: 60px;
                max-height: 60px;
                min-height: 60px;
                border-radius: var(--radius-rounded);
                border: 1px solid var(--fade-grey);
            }

            .meta-left {
                margin-left: 16px;

                h3 {
                    font-family: var(--font-alt);
                    color: var(--dark-text);
                    font-weight: 500;
                    font-size: 0.85rem;
                    line-height: 1;
                }

                >span:not(.tag) {
                    font-size: 0.9rem;
                    color: var(--light-text);

                    svg {
                        position: relative;
                        top: 1px;
                        height: 12px;
                        width: 12px;
                    }

                    .icon-separator {
                        position: relative;
                        top: -3px;
                        font-size: 5px;
                        color: var(--light-text);
                        padding: 0 8px;
                    }

                    .iconify {
                        margin-right: 0.25rem;
                    }
                }
            }

            .meta-right {
                margin-left: auto;
                display: flex;
                align-items: center;
                justify-content: flex-end;

                .buttons {
                    margin-bottom: 0;
                    margin-right: 10px;
                }
            }
        }
    }
}

.illustration-header-2 {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 16px;
    background: var(--primary-dark-24);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .header-image {
        position: relative;
        height: 175px;
        width: 320px;

        img {
            position: absolute;
            top: 0;
            left: -40px;
            display: block;
            pointer-events: none;
        }
    }

    .header-meta {
        margin-left: 0;
        padding-right: 30px;

        h3 {
            color: var(--smoke-white);
            font-family: var(--font-alt);
            font-weight: 700;
            font-size: 1.3rem;
            max-width: 280px;
        }

        p {
            font-weight: 400;
            color: var(--smoke-white-dark-2);
            margin-bottom: 16px;
            max-width: 320px;
        }

        .action-link {
            span {
                font-size: 0.8rem;
                text-transform: uppercase;
                margin-right: 6px;
            }

            i {
                font-size: 12px;
            }
        }
    }
}

.is-dark {
    .list-view-v3 {
        .list-view-item {
            @include vuero-card--dark;

            .list-view-item-inner {
                >img {
                    border-color: var(--dark-sidebar-light-12);
                }

                .meta-left {
                    h3 {
                        color: var(--dark-dark-text) !important;
                    }
                }

                .meta-right {
                    .buttons {
                        .button {
                            &:nth-child(2) {
                                background: var(--dark-sidebar-light-2);
                                border-color: var(--dark-sidebar-light-8);
                                color: var(--dark-dark-text);
                                transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                    height 0.3s, width 0.3s;

                                &:hover,
                                &:focus {
                                    border-color: var(--primary);
                                    color: var(--primary);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

.search-menu {
    height: 56px;
    white-space: nowrap;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    background-color: whitesmoke;
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
            background-color: whitesmoke;
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
        color: var(--button-color);
        cursor: pointer;
        margin-left: auto;
    }
}

.p-tabview .p-tabview-nav li.p-highlight .p-tabview-nav-link {
    background: #ffffff !important;
    border-color: #4CAF50 !important;
    color: #4CAF50 !important;
}

@media only screen and (max-width: 767px) {
    .lifestyle-dashboard-v4 {
        .illustration-header-2 {
            flex-direction: column;
            text-align: center;

            .header-image {
                height: auto;
                width: 100%;

                img {
                    position: relative;
                    width: 100%;
                    max-width: 260px;
                    margin: 0 auto;
                    top: 0;
                    left: 0;
                    margin-top: -34px;
                }
            }

            .header-meta {
                padding: 20px;

                >p {
                    max-width: 280px;
                    margin-left: auto;
                    margin-right: auto;
                }
            }
        }

        .writing-stats {
            .writing-stat {
                text-align: center;
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .lifestyle-dashboard-v4 {
        .articles-feed {
            .articles-feed-list {
                .articles-feed-list-inner {
                    display: flex;
                    flex-wrap: wrap;
                    margin-left: -12px;
                    margin-right: -12px;

                    .articles-feed-item {
                        width: calc(50% - 24px);
                        margin: 12px;
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
    .lifestyle-dashboard-v4 {
        .updates {
            .updates-list {
                .update-item {
                    >span {
                        display: none;
                    }
                }
            }
        }

        .articles-feed {
            padding: 20px;
        }
    }
}
</style>
