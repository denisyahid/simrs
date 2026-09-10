<template>
  <div>
    <FloatingButton @click="showPasienLama()" />
    <div class="business-dashboard hr-dashboard">
      <div class="columns">
        <div class="column is-8">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="illustration-header-2 large-screen">
                <div class="header-image">
                  <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                    style="max-width:75%; margin-left: 2rem; margin-top: 0.5rem;" />
                </div>
                <div class="header-meta" style="margin-left : -2rem;">
                  <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i>
                    Dashboard Radiologi
                  </h3>
                  <p>Selamat Datang , {{ userLogin.pegawai.namaLengkap }}</p>
                  <VControl>
                    <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" class="f-text"
                      placeholder="Filter ruangan" :searchable="true" autocomplete="off"
                      @select="changeRuang(item.filterRuangan)" />
                  </VControl>
                  <VTag @click="showModalFilter()" color="danger" rounded elevated
                    style="position: relative; bottom: -1.5rem; cursor:pointer; height: 3em">
                    {{
                      H.formatDateToLocalString(item.periode.start) ==
                        H.formatDateToLocalString(item.periode.end) ?
                        H.formatDateToLocalString(item.periode.start) :
                        H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
                          H.formatDateToLocalString(item.periode.end) : '')
                    }}
                    <i class="fas fa-filter ml-3" aria-hidden="true"></i>
                  </VTag>
                </div>
                <div class="column is-2" style="margin-top: -5px;" v-if="cariranapkun == true">
                  <VControl>
                    <VSwitchBlock v-model="rawatinapkun" label="Order Rawat Inap" color="info" value="true" />
                  </VControl>
                </div>
                <div class="column is-2" style="margin-top: -5px;">
                  <VControl>
                    <VSwitchBlock v-model="polikun" label="Order Poli" color="danger" value="true" />
                  </VControl>
                </div>
              </div>
            </div>
          </div>
          <Badge :value="dataOrder.length" v-if="dataOrder.length > 0" severity="danger"
            style="z-index: 6;top: 38px;position: relative; left: 20px" />
          <div class="column is-12" style="margin-top: 2rem;">
            <VTabs slider selected="Pasien" :tabs="[
              { label: 'Daftar Order', value: 'Pasien' },
              { label: 'Pasien Penunjang', value: 'Penunjang' },
              // { label: 'Daftar Penjadwalan', value: 'Penjadwalan' },
            ]" style="margin-top: -2rem;" @update:selected="changeSelected()">
              <template #tab="{ activeValue }">
                <p v-if="activeValue === 'Pasien'">
                <div class="list-view list-view-v3">
                  <VCard class="text-center pt-0 pb-0 mt-0">
                    <VRadio v-model="order" v-if="allregis != true" value="0" label="Pending" name="outlined_radio"
                      color="warning" />
                    <VRadio v-model="order" value="1" v-if="allregis != true" label="Verifikasi" name="outlined_radio"
                      color="info" />
                    <VRadio v-model="order" value="2" v-if="allregis != true" label="Selesai Pelayanan"
                      name="outlined_radio" color="primary" />
                    <!-- <VRadio v-model="order" v-if="allregis != true" value="5" label="Penjadwalan" name="outlined_radio"
                      color="primary" /> -->
                    <v-checkbox v-model="allregis" label="Input Manual Registrasi"></v-checkbox>
                  </VCard>
                  <div v-if="allregis == true">

                    <div class="list-view list-view-v3">
                      <div class="search-menu mb-2">
                        <div class="search-location" style="width: 100%">
                          <i class="iconify" data-icon="feather:search"></i>
                          <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                            v-model="item.rsearch" v-on:keyup.enter="fetchPasien(pasiennya)" />
                        </div>
                        <VButton raised class="search-button" @click="fetchPasien(pasiennya)" :loading="isLoading"> Cari
                          Pasien
                        </VButton>
                      </div>
                      <VCard class="text-center pt-0 pb-0 mt-0">
                        <VRadio v-model="pasiennya" value="0" label="Belum diinput tindakan" name="outlined_radio"
                          color="warning" />
                        <VRadio v-model="pasiennya" value="1" label="Sudah diinput tindakan" name="outlined_radio"
                          color="info" />
                        <!-- <VRadio v-model="pasiennya" value="2" label="Selesai Periksa" name="outlined_radio" color="primary" /> -->
                      </VCard>
                      <VPlaceholderPage :class="[dataPasien.length !== 0 && 'is-hidden']"
                        title="Tidak Ada Pasien Hari Ini."
                        subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                        <template #image>
                          <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                            alt="" />
                        </template>
                      </VPlaceholderPage>
                      <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                        <div name="list-complete" tag="div">
                          <div v-for="(item, rowIndex) in dataPasien" :key="rowIndex">
                            <div v-if="rowGroupMetadata[item.namaruangan].index === rowIndex">
                              <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                                item.namaruangan }}</span>
                              <Badge :value="rowGroupMetadata[item.namaruangan].size"
                                v-if="rowGroupMetadata[item.namaruangan].size > 0" class="ml-2 mt-2-min" />

                            </div>

                            <div class="list-view-item ">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary"
                                  bordered />
                                <div class="meta-left">
                                  <h3>
                                    {{ item.namapasien }}
                                    <i :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                      aria-hidden="true"
                                      :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                    - <span style="font-size: 8pt; color: #A2A5B9">{{ item.kebangsaan }}</span>
                                  </h3>
                                  <span>
                                    <div>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                      <span>{{ item.tglorder }}</span>

                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                      <span>{{ item.noregistrasi }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                      <span>{{ item.namaruangan }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                      <span>{{ item.umur }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                      <span>{{ item.nocm }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                      <span>{{ item.nobpjs }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                      <span>{{ item.noidentitas }}</span>
                                      <VTag color="danger" v-if="item.riawayatLayanan" style="margin-left: 5rem"
                                        rounded>Sudah di input
                                        tindakan</VTag>
                                    </div>
                                  </span>
                                </div>
                                <div class="meta-right">
                                  <div class="buttons">
                                    <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                      @click="emr(item)" v-tooltip.bottom.left="'EMR'">
                                    </VIconButton>
                                    <VIconButton v-if="item.norec_so != null"
                                      v-tooltip.bottom.left="'Cetak Label Pasien'" label="Bottom Left" color="warning"
                                      circle icon="feather:printer" @click="cetakLabelPenunjang(item)"
                                      :loading="item.loading" />
                                    <VIconButton v-if="item.norec_apd != null"
                                      v-tooltip.bottom.left="'Transaksi Tindakan Pelayanan'" label="Bottom Left"
                                      color="info" circle icon="pi pi-arrow-right" @click="inputTindakan(item)"
                                      :loading="item.loading" />
                                    <VIconButton v-else v-tooltip.bottom.left="'Registrasi Pelayanan'"
                                      label="Bottom Left" color="warning" circle icon="pi pi-arrow-right"
                                      @click="daftar(item)" :loading="item.loading" />
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



                  </div>
                  <div v-else>
                    <div class="search-menu mb-2">
                      <div class="search-location" style="width: 100%">
                        <i class="iconify" data-icon="feather:search"></i>
                        <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                          v-model="item.search" v-on:keyup.enter="fetchDataOrder(order, rawatinapkun, polikun)" />
                      </div>
                      <VButton raised class="search-button" @click="fetchDataOrder(order, rawatinapkun, polikun)"
                        :loading="isLoading">
                        Cari
                        Order
                      </VButton>
                    </div>
                    <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6"
                      :class="[dataOrder.length !== 0 && 'is-hidden']">
                      <template #image>
                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                          alt="" />
                      </template>
                    </VPlaceholderPage>
                    <div class="list-view-inner" style="max-height:500px;overflow: auto; margin-top: 1rem; ">
                      <TransitionGroup name="list-complete" tag="div">
                        <!--Item-->
                        <div v-for="(items, m) in dataOrder" :key="m" class="list-view-item">
                          <div class="list-view-item-inner">
                            <VAvatar size="small" style="left: 8px;top: 4px;" :color="listColor[i]"
                              :initials="items.initials" />
                            <div class="meta-left">
                              <h3>
                                {{ items.namapasien }} <i
                                  :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                  aria-hidden="true"
                                  :style="'color:' + (items.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                - <span style="font-size: 8pt; color: #A2A5B9">{{ items.kebangsaan }}</span>
                                - <span style="font-size: 8pt; color: #A2A5B9">{{ items.kelompokpasien }}</span>
                                - <span style="font-size: 8pt; color: #A2A5B9">{{ items.namakelas }}</span>
                              </h3>
                              <span>
                                <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                <span>{{ items.asal_ruangan }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                <span>{{ items.tglorder }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                <span>{{ items.noregistrasi }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                <span>{{ items.umur }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                <span>{{ items.tgllahir }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                <span>{{ items.pas_nocm }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                <span>{{ items.nobpjs }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                <span>{{ items.noidentitas }}</span>
                                <!-- <VTag label="Diorder Poli" color="success" rounded elevated
                                  style="font-size:0.8rem;font-weight: bold;" v-if="items.so_norec != null" />
                                <VTag label="Langsung Pendaftaran" color="success" rounded elevated
                                  style="font-size:0.8rem;font-weight: bold;" v-if="items.so_norec == null" /> -->

                              </span>
                              <br>
                              <VTag color="warning" rounded v-if="items.statusorder == 0">Pending
                              </VTag>
                              <VTag color="info" rounded v-if="items.statusorder == 1">
                                Terverifikasi</VTag>
                              <VTag color="primary" rounded v-if="items.statusorder == 2">Selesai
                              </VTag>
                              <VTag color="danger" class="ml-5" rounded v-if="items.iscito">Cito
                              </VTag>
                              <VTag :label="'SEP : ' + items.nosep" v-if="items.nosep != null" :color="'success'"
                                class='ml-2' />
                            </div>
                            <div class="meta-right">
                              <!-- <VButton color="primary" raised style="margin-top: 11px;"
                                                            v-if="items.statusorder == 0" @click="orderVerify(items)">
                                                            Verifikasi <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                        </VButton> -->
                              <!-- <VIconButton v-tooltip.bottom.left="'Penjadwalan'" label="Bottom Left" color="info"
                                circle icon="feather:calendar" v-if="items.statusorder == 0 "
                                @click="openPenjadwalan(items)" style="margin-right: 15px;" /> -->
                              <VIconButton v-tooltip.bottom.left="'Verifikasi'" label="Bottom Left" color="primary"
                                circle icon="pi pi-check-circle" v-if="items.statusorder == 0 || items.statusorder == 5"
                                @click="orderVerify(items)" style="margin-right: 15px;" />

                              <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                @click="emr(items)" v-tooltip.bottom="'EMR'"
                                v-if="items.statusorder == 0 || items.statusorder == 5" style="margin-right: 15px;" />
                              <VIconButton v-tooltip.bottom.left="'Cetak Order'" label="Bottom Left" color="purple"
                                v-if="items.statusorder == 0" circle icon="feather:printer" @click="cetakOrder(items)"
                                style="margin-right: 15px;" />
                              <VIconButton v-tooltip.bottom.left="'Cetak Label'" label="Bottom Left" color="warning"
                                v-if="items.statusorder == 0" circle icon="feather:printer" @click="cetakLabel(items)"
                                style="margin-right: 15px;" />
                              <VIconButton v-tooltip.bottom.left="'Cetak SEP'" label="Bottom Left" color="danger"
                                v-if="items.statusorder == 0 && items.nosep != null" circle icon="feather:printer"
                                @click="cetakSEP(items)" style="margin-right: 15px;" />

                              <VIconButton v-tooltip.bottom.left="'Detail'" label="Bottom Left"
                                v-if="items.statusorder != 0 && items.statusorder != 5" color="primary" circle
                                icon="pi pi-book" @click="getDetailVerify(items)" style="margin-right: 15px;" />
                              <VIconButton v-tooltip.bottom.left="'Cetak Order'" label="Bottom Left" color="purple"
                                v-if="items.statusorder != 0" circle icon="feather:printer" @click="cetakOrder(items)"
                                style="margin-right: 15px;" />
                              <VIconButton v-tooltip.bottom.left="'Cetak Label'" label="Bottom Left" color="warning"
                                v-if="items.statusorder != 0" circle icon="feather:printer" @click="cetakLabel(items)"
                                style="margin-right: 15px;" />
                              <VIconButton v-tooltip.bottom.left="'Batal Verif'" label="Bottom Left" color="danger"
                                v-if="items.statusorder == 1" circle icon="feather:trash" @click="batalVerif(items)"
                                style="margin-right: 15px;" />

                              <!-- <VButton color="primary" raised
                                                            style="margin-right: 15px;margin-top: 11px;" v-else
                                                            @click="getDetailVerify(items)">
                                                            Detail
                                                        </VButton> -->

                              <!-- <VIconButton v-tooltip.bottom.left="'Cetak Order'" label="Bottom Left"
                                                            color="warning" circle icon="pi pi-print" @click="cetakOrder(items)"
                                                            :loading="item.loading" /> -->
                              <!-- <VDropdown icon="feather:more-vertical" spaced right>
                                                            <template #content>
                                                                <a role="menuitem" href="#" class="dropdown-item is-media"
                                                                    @click="cetakOrder(items)">
                                                                    <div class="icon">
                                                                        <i aria-hidden="true" class="lnil lnil-printer"></i>
                                                                    </div>
                                                                    <div class="meta">
                                                                        <span>Cetak Order</span>
                                                                    </div>
                                                                </a>
                                                            </template>
                                                        </VDropdown> -->
                              <!-- <VDropdown v-if="items.nosep != null" icon="feather:more-vertical" spaced right v-tooltip.bubble="'CETAK'">
                                  <template #content>
                                    <a role="menuitem"  class="dropdown-item is-media"
                                      @click="cetakSEP(items)">
                                      <div class="icon">
                                        <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Cetak SEP</span>
                                        <span>Cetak Surat Elegibilitas</span>
                                      </div>
                                    </a>
                                    <a role="menuitem" class="dropdown-item is-media" @click="cetakOrder(items)">
                                      <div class="icon">
                                        <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Cetak Order</span>
                                        <span>Cetak Order Radiologi</span>
                                      </div>
                                    </a>
                                    <a role="menuitem" class="dropdown-item is-media" @click="cetakLabel(items)">
                                      <div class="icon">
                                        <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Cetak Label</span>
                                        <span>Cetak Label Rad</span>
                                      </div>
                                    </a>
                                  </template>
                                </VDropdown> -->
                            </div>
                          </div>
                        </div>
                      </TransitionGroup>
                    </div>
                    <div class="column is-12 p-0">
                      <VFlexPagination v-model:current-page="currentPageOrder.page"
                        :item-per-page="currentPageOrder.limit" :total-items="dataOrder.total" :max-links-displayed="5">
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
                  </div>
                </div>
                </p>

                <p v-else-if="activeValue === 'Penunjang'">
                <div class="search-menu mb-2">
                  <div class="search-location" style="width: 100%">
                    <i class="iconify" data-icon="feather:search"></i>
                    <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                      v-model="item.qsearch" v-on:keyup.enter="fetchPenunjang(pen)" />
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
                                    <input type="text" placeholder="Nama Pasien" v-model="item.qnama" />
                                </div> -->
                  <VButton raised class="search-button" @click="fetchPenunjang(pen)" :loading="isLoading"> Cari
                    Data
                  </VButton>
                  <VCard class="text-center pt-0 pb-0 mt-0">
                    <VRadio v-model="pen" value="0" label="Order poli" name="outlined_radio" color="warning" />
                    <VRadio v-model="pen" value="3" label="Penjadwalan" name="outlined_radio" color="success" />
                    <VRadio v-model="pen" value="1" label="Registrasi" name="outlined_radio" color="info" />
                    <VRadio v-model="pen" value="2" label="Order Rawat Inap" name="outlined_radio" color="primary" />
                  </VCard>
                </div>
                <VCard radius="rounded">

                  <VCard>
                    <div class="user-grid user-grid-v2">
                      <div class="columns is-multiline" v-if="dataPenunjang.loading">
                        <!--Grid item-->
                        <div v-for="key in 8" :key="key" class="column is-4">
                          <div class="grid-item">
                            <VPlaceloadAvatar size="big" centered class="mb-2" />

                            <VPlaceloadText class="mb-4" width="80%" :lines="3" last-line-width="60%" centered />

                            <div class="people">
                              <VPlaceloadAvatar size="small" class="mx-1" />
                              <VPlaceloadAvatar size="small" class="mx-1" />
                              <VPlaceloadAvatar size="small" class="mx-1" />
                              <VPlaceloadAvatar size="small" class="mx-1" />
                            </div>

                            <VButtons>
                              <VButton placeload="100%" dark-outlined>loading ...</VButton>
                              <VButton placeload="100%" dark-outlined>loading ...</VButton>
                            </VButtons>
                          </div>
                        </div>
                      </div>

                      <VPlaceholderPage v-else-if="dataPenunjang.length === 0" :title="H.assets().notFound"
                        :subtitle="H.assets().notFoundSubtitle" larger>
                        <template #image>
                          <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                          <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                            alt="" />
                        </template>
                      </VPlaceholderPage>

                      <TransitionGroup name="list" tag="div" class="columns is-multiline"
                        v-else-if="dataPenunjang.length > 0">

                        <div v-for="(item, c) in dataPenunjang" :key="c" class="column is-4">
                          <div class="grid-item-wrap is-clickable">
                            <!-- @click="clickCard(item)" -->
                            <div :class="'grid-item-head ' + (
                              item.norec_apd != null ? 'is-registrasi' : ''
                            )">
                              <div class="flex-head">

                                <div class="meta">
                                  <span v-if="item.norec_apd != null" class="dark-inverted">
                                    {{ item.ruanganasal }}
                                  </span>

                                  <span>
                                    {{
                                      item.tglorder ? H.formatDateIndoSimple(item.tglorder) :
                                        H.formatDateIndoSimple(item.tglregistrasi)
                                    }}
                                  </span>
                                </div>
                                <div v-if="item.isExpertise" class="status-icon " style="background-color: white;">
                                  <i aria-hidden="true" class="fas fa-check " style="color:var(--success)"></i>
                                </div>
                                <div v-else class="status-icon is-danger" style="margin-left: -20px;">
                                  <i aria-hidden="true" class="fas fa-times"></i>
                                </div>
                              </div>

                            </div>
                            <div class="flex-head" style=" display: flex; justify-content: space-between;">
                              <VTag v-if="item.kelompokpasien != null" class="mt-2 ml-2" :label="item.kelompokpasien"
                                :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'" rounded />
                              <VDropdown icon="feather:more-vertical" spaced right>
                                <template #content>
                                  <!-- <a role="menuitem" @click="PengkajianMedis(item)"
                                                                    class="dropdown-item is-media">
                                                                    <div class="icon">
                                                                        <i aria-hidden="true"
                                                                            class="lnil lnil-medical-sign"></i>
                                                                    </div>
                                                                    <div class="meta">
                                                                        <span>Pengkajian Medis</span>
                                                                    </div>
                                                                </a> -->
                                  <a role="menuitem" @click="UpdateJenisKelamin(item)" class="dropdown-item is-media">
                                    <div class="icon">
                                      <i aria-hidden="true" class="lnil lnil-user-alt"></i>
                                    </div>
                                    <div class="meta">
                                      <span>Ubah Jenis Kelamin</span>
                                    </div>
                                  </a>
                                  <a role="menuitem" @click="UpdateGolonganDarah(item)" class="dropdown-item is-media">
                                    <div class="icon">
                                      <i aria-hidden="true" class="lnil lnil-pencil"></i>
                                    </div>
                                    <div class="meta">
                                      <span>Ubah Golongan Darah</span>
                                    </div>
                                  </a>

                                </template>
                              </VDropdown>
                            </div>
                            <div class="grid-item">
                              <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')" :badge="(item.objectjeniskelaminfk == '1' ? '/images/other/male.png'
                                : '/images/other/female.png')" size="big" />
                              <h3 class="dark-inverted">{{ item.namapasien }}
                                - <span style="font-size: 8pt; color: #A2A5B9">{{ item.kebangsaan }}</span>
                              </h3>
                              <!-- <p>{{ item.nocm }}</p> -->
                              <p>No REG : {{ item.noregistrasi }}</p>
                              <p>No RM : {{ item.nocm }}</p>
                              <p>No BPJS : {{ item.nobpjs }}</p>
                              <p>NIK : {{ item.noidentitas }}</p>
                              <p>Ruangan Asal : {{ item.ruanganasal ?? '-' }}</p>
                              <p>Alamat : {{ item.alamatrmh ?? '-' }}</p>
                              <p>No Telp : {{ item.telponpenanggungjawab ?? '-' }}</p>
                              <br>
                              <VTag label="Diorder Poli" color="warning" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.objectdepartemenfk == 18" />
                              <VTag label="Penjadwalan" color="success" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.jadwal_tindakan != null" />
                              <VTag label="Langsung Pendaftaran" color="info" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.norec_so == null" />
                              <VTag label="Rawat Inap" color="success" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.objectdepartemenfk == 16" />
                              <br>

                              <div class="buttons mt-4">
                                <VIconButton v-if="item.norec_so != null" v-tooltip.bottom.left="'Cetak Label'"
                                  label="Bottom Left" color="warning" outlined circle icon="feather:printer"
                                  @click="cetakLabelPenunjang(item)" style="margin-right: 15px;" />
                                <VIconButton v-tooltip.bottom.left="'Rincian'" label="Bottom Left" color="info" outlined
                                  circle icon="pi pi-arrow-right" @click="transaksiPelayanan(item)"
                                  :loading="item.loading" />

                                <VIconButton v-tooltip.bottom.left="'EMR'" color="success" outlined circle
                                  icon="fas fa-stethoscope" @click="emr(item)" />
                              </div>
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
                    </div>
                  </VCard>
                </VCard>
                </p>
                <p v-else-if="activeValue === 'Penjadwalan'">
                <div class="search-menu mb-2">
                  <div class="search-location" style="width: 100%">
                    <i class="iconify" data-icon="feather:search"></i>
                    <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                      v-model="item.qsearch" v-on:keyup.enter="fetchPenjadwalan()" />
                  </div>
                  <VButton raised class="search-button" @click="fetchPenjadwalan()" :loading="isLoading">
                    Cari
                    Order
                  </VButton>
                </div>
                <VCard radius="rounded">
                  <div class="user-grid user-grid-v2">
                    <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()">
                      Export to
                      Excel </VButton>

                    <DataTable :value="dataPenjadwalan" class="p-datatable-sm" :paginator="true" :rows="10"
                      :rowsPerPageOptions="[5, 10, 25, 50, 100, 200, 500, 1000]"
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      scrollable scrollHeight="flex" tableStyle="min-width: 100rem" breakpoint="960px"
                      sortMode="multiple" :loading="isLoading"
                      currentPageReportTemplate="Showing {first} to {last} of {totalRecords} showGridlines ">
                      <Column field="no" header="No"></Column>
                      <Column field="tanggal_jadwal" header="Tgl Penjadwalan" :sortable="true"></Column>
                      <Column field="aksi" header="Aksi">
                        <template #body="slotProps">
                          <VIconButton v-if="slotProps.data.norec_pp != null"
                            v-tooltip.bottom.left="'Penjadwalan Ulang'" label="Bottom Left" color="info" circle
                            icon="feather:calendar" @click="reschedule(slotProps.data)"
                            :loading="slotProps.data.loading" />
                        </template>
                      </Column>
                      <Column field="tanggal_order" header="Tgl Order" :sortable="true"></Column>
                      <Column field="namapasien" header="Nama Pasien"></Column>
                      <Column field="jeniskelamin" header="JK"></Column>
                      <Column field="umur_pasien" header="Umur" :sortable="true"></Column>
                      <Column field="nocm" header="NO RM" :sortable="true"></Column>
                      <Column field="tgllahir" header="Tanggal Lahir"></Column>
                      <Column field="diagnosis" header="Diagnosis"></Column>
                      <Column field="namaproduk" header="Tindakan"></Column>
                      <Column field="ruang_asal" header="Ruang Order"></Column>
                      <Column field="dokterorder" header="Dokter Order"></Column>
                      <Column field="dokterpemeriksa" header="Dokter Pemeriksa"></Column>
                      <Column field="pegawai_penerima" header="Petugas Verifikator"></Column>
                      <Column field="kelompokpasien" header="Cara Bayar"></Column>
                      <Column field="aksi" header="Status" frozen style="min-width: 100px">
                        <template #body="slotProps">
                          <VTag color="success" v-if="slotProps.data.hasil" rounded>
                            Sudah Ekspertise
                          </VTag>
                          <VTag color="danger" v-else rounded>
                            Belum Ekspertise
                          </VTag>
                        </template>
                      </Column>
                    </DataTable>
                  </div>
                </VCard>
                </p>
              </template>
            </VTabs>
          </div>
        </div>
        <div class="column is-4">
          <div class="column is-12  p-0">
            <VCard>
              <ApexChart height="220" type="bar" :series="chartLO.series" :options="chartLO">
              </ApexChart>
            </VCard>
          </div>
          <div class="column is-12" style="margin-top: 3rem;">
            <VTabs slider selected="Dokter" :tabs="[
              { label: 'Jadwal Dokter', value: 'Dokter', icon: 'fas fa-users' },
              { label: 'Stok Barang', value: 'Stok', icon: 'feather:box' },
            ]" style="margin-top: -2rem;">
              <template #tab="{ activeValue }">
                <p v-if="activeValue === 'Dokter'">
                  <UIWidget class="search-widget">
                    <template #body>
                      <div class="field" style="padding: 2px">
                        <div class="control">
                          <input v-model="filters" class="input custom-text-filter" placeholder="Cari Dokter Praktek" />
                          <button class="searcv-button">
                            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                          </button>
                        </div>
                      </div>
                    </template>
                  </UIWidget>
                <div class="tile-grid tile-grid-v2">
                  <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6"
                    :class="[dataSourcefiltered.length !== 0 && 'is-hidden']">
                    <template #image>
                      <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                    </template>
                  </VPlaceholderPage>

                  <!--Tile Grid v1-->
                  <VCard>
                    <TransitionGroup name="list" tag="div" class="columns is-multiline">
                      <!--Grid item-->

                      <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;" key="2">
                        <div v-for="(item, i) in dataSourcefiltered" :key="i" class="column is-12">
                          <div class="tile-grid-item">
                            <div class="tile-grid-item-inner">
                              <VAvatar size="small" picture="/images/avatars/svg/dokter.svg" color="primary" bordered />
                              <div class="meta">
                                <span class="dark-inverted">{{ item.namalengkap }}</span>
                                <span> <i aria-hidden="true" class="iconify" data-icon="feather:clock"
                                    style="padding-right: 3px;"></i> {{ item.jammulai }} s.d
                                  {{ item.jamakhir }}</span>
                              </div>
                              <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded elevated> {{
                                item.hari }}
                              </VTag>
                            </div>
                          </div>
                        </div>
                      </div>

                    </TransitionGroup>
                  </VCard>

                </div>

                </p>
                <p v-else-if="activeValue === 'Stok'">
                <div class="tile-grid tile-grid-v2">

                  <!--List Empty Search Placeholder -->
                  <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6"
                    :class="[dataStokObat.length !== 0 && 'is-hidden']">
                    <template #image>
                      <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                        style="width:70%" />
                    </template>
                  </VPlaceholderPage>

                  <!--Tile Grid v1-->

                  <TransitionGroup name="list" tag="div" class="columns is-multiline">
                    <!--Grid item-->
                    <div class="columns is-multiline p-2" style="max-height:300px;overflow: auto;" key="1">
                      <div v-for="(item, n) in dataStokObat" :key="n" class="column is-6">
                        <div class="tile-grid-item">
                          <div class="tile-grid-item-inner">
                            <VAvatar size="small" picture="/images/simrs/produk-ico.png" color="primary" bordered />
                            <div class="meta">
                              <span class="dark-inverted">{{ item.namaproduk }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TransitionGroup>
                </div>
                </p>
              </template>

            </VTabs>

          </div>
        </div>
      </div>
    </div>

    <VModal :open="modalDetailOrder" title="Verifikasi Order" noclose size="big" actions="right"
      @close="modalDetailOrder = false, clear(), saveDiasabled = false, ikiWesPernahDikirim = false"
      cancelLabel="Tutup">
      <template #content>
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12 p-0">
              <div class="block-header">
                <div class="left">
                  <div class="current-user">
                    <VAvatar size="medium" :picture="item.jeniskelamin == 'Perempuan'
                      ? '/images/avatars/svg/vuero-4.svg'
                      : '/images/avatars/svg/vuero-1.svg'
                      " squared />
                    <h3>{{ item.namapasien }}</h3>
                    <p class="block-text">
                      {{ item.no_rm + (item.jeniskelamin ==
                        'Perempuan' ? ' (P)'
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
                      <h4 class="block-heading">Tgl Order</h4>
                      <p class="block-text">{{ item.tglorder }}</p>
                      <h4 class="block-heading">Umur</h4>
                      <p class="block-text">{{ item.umur }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">Diagnosa</h4>
                      <p class="block-text">
                        {{ item.namadiagnosa ? item.namadiagnosa : item.catatanklinis }}</p>
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
                      <h4 class="block-heading">Catatan Klinis</h4>
                      <p class="block-text">{{ item.catatanklinis }}</p>
                    </div>

                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="column is-12 p-4 mt-5">
          <Fieldset legend="Tambah Tindakan" :toggleable="true">
            <div class="columns pl-3">
              <div class="column is-1 pr-0" style="padding-left: 0px;margin-right: -38px">
                <VField label="No">
                  <VAvatar initials="1" />
                </VField>
              </div>
              <div class="column is-11 ml-5">
                <div class="columns">
                  <div class="column is-4">
                    <VField label="Pelayanan" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:list" class="prime-auto-select" fullwidth>
                        <Dropdown v-model="item.produk" :options="d_Produk" :optionLabel="'label'"
                          placeholder="Pilih data" style="width: 100%;" class="is-rounded" showClear :filter="true"
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
                  <div class="column is-2">
                    <VField label="Jumlah">
                      <VControl icon="lnir lnir-repeat-one">
                        <VInput type="text" v-model="item.jumlah" placeholder="Jumlah" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="columns mt-2" style="margin-left:40px">
                    <VButtons>
                      <VButton color="success" raised icon="feather:edit" v-if="item.no && d_Komponen.length"
                        @click="update(item)"> Update
                      </VButton>
                      <VButton color="info" raised icon="fas fa-plus" v-else-if="!item.no && d_Komponen.length"
                        @click="add(), clear()">
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
              <div class="timeline-wrapper-inner">
                <div class="timeline-container">
                  <div class="timeline-item is-unread" v-for="(items, index) in detailOrderLayanan" :key="items.norec">
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
                        <div class="box-text" style="width:70%">
                          <div class="meta-text">
                            <p>
                              <span>{{ items.namaproduk }}</span>
                            </p>
                            <table class="tb-order">
                              <tr>
                                <td>Harga </td>
                                <td>:</td>
                                <td class="font-values">{{ H.formatRp(items.hargasatuan, 'Rp. ')
                                }}</td>
                              </tr>
                              <tr>
                                <td>Jumlah</td>
                                <td>:</td>
                                <td>{{ items.qtyproduk }} </td>
                              </tr>
                              <tr>
                                <td>Keterangan Tindakan</td>
                                <td>:</td>
                                <td>{{ items.keteranganlainnya }} </td>
                              </tr>
                              <!-- <tr>
                                                            <td>Cito</td>
                                                            <td>:</td>
                                                            <td>{{ items.nilaiStatusCito ?'Ya':'' }} </td>
                                                        </tr> -->

                            </table>

                          </div>
                        </div>
                        <VField class="is-rounded-select is-autocomplete-select">
                          <VLabel class="required-field">Dokter Verifikator</VLabel>
                          <VControl icon="feather:search" fullwidth class="prime-auto-select">
                            <Dropdown v-model="items.dokterverify" :options="d_Dokter" optionLabel="label"
                              class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                          </VControl>
                        </VField>
                        <!-- <VField class="is-rounded-select is-autocomplete-select mr-2 ml-2 mt-2">
                          <VLabel>Tgl Penjadwalan</VLabel>
                          <VDatePicker v-model="items.jadwal_tindakan" mode="date" style="width: 100%">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth class="prime-auto-select">
                                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                    class="is-rounded" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField> -->
                        <div class="box-end" style="width: 30%">
                          <div class="columns is-multiline">
                            <div class="column is-6" style="margin-top: 0.5rem;">
                              <VIconButton v-tooltip.bottom.left="'Edit'" icon="feather:edit" @click="edit(items)"
                                color="warning" raised circle class="mr-2">
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

          </Fieldset>
        </div>

        <div class="column is-12" style="padding: 2rem 6rem;">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField label="Dokter Order">
                <VControl class="mt-2">
                  <VInput type="text" placeholder="Dokter Order" readonly class="is-rounded" v-model="item.dokterorder"
                    style="cursor:pointer; text-align: center;background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField label="Waktu Pemeriksaan">
                <VDatePicker v-model="item.waktuPemeriksaan" mode="dateTime" style="width: 100%" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <!-- <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Dokter Verifikator</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.dokterverify" :options="d_Dokter" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div> -->
            <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Petugas Verifikator / Admin</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.petugasverify" :options="d_DokterVerif" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Radiografer</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.radiografer" :options="d_DokterVerif" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns is-multiline">
            <div class="column is-6">
              <VField label="Catatan">
                <VControl class="mt-2">
                  <VTextarea rows="4" placeholder="Tulis Catatan..." v-model="item.catatan"></VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel class="required-field">Catatan Klinis</VLabel>
                <VControl class="mt-3">
                  <VTextarea rows="4" placeholder="Tulis Catatan Klinis..." v-model="item.catatanklinis">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Catatan Diagnosis</VLabel>
                <VControl class="mt-3">
                  <VTextarea rows="4" placeholder="Tulis Catatan Diagnosis..." v-model="item.catatanDiagnosis">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Keterangan</VLabel>
                <VControl class="mt-3">
                  <VTextarea rows="4" placeholder="Tulis Catatan Keterangan..." v-model="item.keteranganradiologi">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

      </template>
      <template #action>
        <VButton v-if="isLoadDataSoNorec" icon="feather:printer" @click="cetakLabel2(item.soNorec)" color="info"
          :loading="isLoadingSave" raised>Cetak</VButton>
        <VButton v-if="isLoadDataSoNorec" icon="feather:save" @click="save()" color="primary" :loading="loadingSaveKun"
          :disabled="saveDiasabled" raised disabled>Simpan</VButton>
        <VButton v-else icon="feather:save" @click="save()" color="primary" :disabled="saveDiasabled"
          :loading="loadingSaveKun" raised>Simpan
        </VButton>
      </template>
    </VModal>
    <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
      @close="modalFilter = false">
      <template #content>
        <v-checkbox v-model="allper" label="All Periode"></v-checkbox>
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
    <VModal :open="modalJenisKelamin" title="Jenis Kelamin" :noclose="true" size="small" actions="right"
      @close="modalJenisKelamin = false">
      <template #content>

        <div class="column is-12">
          <VField label="Jenis Kelamin" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
            <VControl icon="feather:plus-circle" fullwidth>
              <Multiselect mode="single" v-model="item.jeniskelamin" :options="d_JenisKelamin" placeholder="Pilih data"
                :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />

            </VControl>
          </VField>
        </div>


      </template>
      <template #action>
        <VButton icon="feather:save" @click="saveJenisKelamin(item)" :loading="isLoading" color="primary" raised>
          Simpan</VButton>
      </template>
    </VModal>
    <VModal :open="modalGolonganDarah" title="Golongan Darah" :noclose="true" size="small" actions="right"
      @close="modalGolonganDarah = false">
      <template #content>

        <div class="column is-12">
          <VField label="Jenis Kelamin" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
            <VControl icon="feather:plus-circle" fullwidth>
              <Multiselect mode="single" v-model="item.golongandarah" :options="d_GolonganDarah"
                placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />

            </VControl>
          </VField>
        </div>


      </template>
      <template #action>
        <VButton icon="feather:save" @click="saveGolonganDarah(item)" :loading="isLoading" color="primary" raised>
          Simpan</VButton>
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
                    <VAvatar size="medium"
                      :picture="item.jeniskelamin == 'PEREMPUAN' ? '/images/avatars/svg/vuero-4.svg' : '/images/avatars/svg/vuero-1.svg'"
                      squared />
                    <h3>{{ item.namapasien }}</h3>
                  </div>
                </div>
                <div class="center">
                  <div class="columns is-multiline">
                    <div class="column is-6 p-1">
                      <h4 class="block-heading">Nomor Identitas</h4>
                      <p class="block-text">{{ item.noidentitas }}</p>
                      <h4 class="block-heading">Tgl Lahir</h4>
                      <p class="block-text">{{ item.tgllahir }}</p>
                      <h4 class="block-heading">Umur</h4>
                      <p class="block-text">{{ item.umur }}</p>
                    </div>
                    <div class="column is-6 p-1">
                      <h4 class="block-heading">No RM</h4>
                      <p class="block-text">{{ item.no_rm }}</p>
                      <h4 class="block-heading">No BPJS</h4>
                      <p class="block-text">{{ item.nobpjs ? item.nobpjs : '-' }}</p>
                      <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                      <p>
                        <VTag color="orange" :label="item.kelompokpasien" />
                      </p>
                    </div>
                  </div>
                </div>
                <div class="right">
                  <div class="columns">
                    <div class="column is-6 p-1">
                      <h4 class="block-heading">No. Order</h4>
                      <p class="block-text">{{ item.noorder }}</p>
                      <h4 class="block-heading">Diagnosa</h4>
                      <p class="block-text" style="white-space: normal !important;">{{ item.catatanklinis ?
                        item.catatanklinis : '-' }}</p>
                    </div>
                    <div class="column is-6 p-1">
                      <h4 class="block-heading">Tgl Registrasi</h4>
                      <p class="block-text">{{ H.formatDateIndo(item.tglregistrasi) }}</p>
                      <h4 class="block-heading">No Registrasi</h4>
                      <p class="block-text">{{ item.noregistrasi }}</p>
                      <h4 class="block-heading">Ruangan</h4>
                      <p class="block-text">{{ item.ruangantujuan }}</p>
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
                <div class="timeline-item is-unread" v-for="(items, index) in detailOrderVerify" :key="items.norec">
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
                          <table class="tb-order mt-1">
                            <tr>
                              <td>Harga</td>
                              <td>:</td>
                              <td class="text-value">{{
                                H.formatRp(items.hargasatuan,
                                  'Rp. ')
                              }} </td>
                            </tr>
                            <tr>
                              <td>Jumlah</td>
                              <td>:</td>
                              <td class="text-value">{{ items.jumlah }} </td>
                            </tr>

                            <tr>
                              <td>Dokter Pemeriksa</td>
                              <td>:</td>
                              <td class="text-value">{{ items.namalengkap }}</td>
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
                placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />

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

    <Dialog v-model:visible="modalPenjadwalan" modal header="Penjadwalan" :style="{ width: '20vw' }">
      <div class="column is-12">
        <VField>
          <VDatePicker v-model="item.tglkunjungan" mode="dateTime" style="width: 100%;">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </VField>
        <VButton icon="feather:save" @click="savePenjadwalan(item.tglkunjungan, norec_SO)" color="info" raised>
          Simpan
        </VButton>
      </div>
    </Dialog>
    <Dialog v-model:visible="modalReschedule" modal header="Penjadwalan Ulang" :style="{ width: '20vw' }">
      <div class="column is-12">
        <VField>
          <VDatePicker v-model="item.tglnew" mode="date" style="width: 100%;">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </VField>
        <VButton icon="feather:save" @click="saveReschedule(item.tglnew, norec_pp)" color="info" raised>
          Simpan
        </VButton>
      </div>
    </Dialog>
    <Dialog v-model:visible="modalInputManual" modal header="Input Manual Tindakan" :style="{ width: '100rem' }">
      <OrderRadiologi v-if="modalInputManual" :style="{ width: '80rem' }" :NOREC_PD="source.norec_pd"
        :pasien="source.pasien" :registrasi="source.pasien" />
    </Dialog>
  </div>
</template>


<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment, { isDate } from 'moment'
import { useToaster } from '/@src/composable/toaster'
import Fieldset from 'primevue/fieldset'
import Dropdown from 'primevue/dropdown'
import * as H from '/@src/utils/appHelper'
import Dialog from "primevue/dialog"
import FloatingButton from "../emr/float-tambah.vue"
import Badge from 'primevue/badge';
import OrderRadiologi from '../emr/profile-pasien/page-emr/order-radiologi.vue'
import * as XLSX from "xlsx";
import * as qzService from '/@src/utils/qzTrayService'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

useHead({ title: 'Dashboard Radiologi - ' + import.meta.env.VITE_PROJECT, })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const NOREC_PD = useRoute().query.nocm as string
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const themeColors = useThemeColors()
const dataSource: any = ref([])
const filters = ref('')
const d_Komponen = ref([])
const d_Produk = ref([])
const d_Dokter = ref([])
const d_DokterVerif = ref([])
const d_JenisKelamin = ref([])
const d_GolonganDarah = ref([])
const d_Ruangan = ref([])
const modalHistori = ref(false)
const source: any = reactive({
  norec_pd: '',
  pasien: {}
})

let chartOP: any = ref({
  series: [],
})
const rowGroupMetadata = ref({})
const currentPage: any = ref({
  limit: 50,
  rows: 50,
})
const currentPageOrder: any = ref({
  limit: 50,
  rows: 50,
})
const currentPasienDaftar: any = ref({
  limit: 50,
  rows: 50,
})
currentPageOrder.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
const currentPenunjang: any = ref({
  limit: 50,
  rows: 50,
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
var date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });

let listColor: any = ref(Object.keys(useThemeColors()))
const modalDetail = ref(false)
const route = useRoute()
const userLogin = useUserSession().getUser()
let so_norec = ref('')
let statusOrder: any = ref([])
let statusRegis: any = ref([])
let statusPenunjang: any = ref([])
let result: any = ref([])
let dokterPraktek: any = ref([])
let dokterNonPraktek: any = ref([])
let isLoading: any = ref(false)
let modalDetailOrder: any = ref(false)
let modalFilter: any = ref(false)
let modalDetailOrderVerify: any = ref(false)
let modalGolonganDarah: any = ref(false)
let modalJenisKelamin: any = ref(false)
let modalTransaksi: any = ref(false)
let modalInputManual: any = ref(false)
let dataPasien: any = ref([])
let isLoadingSave: any = ref(false)
const saveDiasabled: any = ref(false)
const loadingSaveKun: any = ref(false)
const rawatinapkun: any = ref(false)
const polikun: any = ref(false)
let cariranapkun: any = ref(true)
let allper: any = ref(false)
let allregis: any = ref(false)
let modalPenjadwalan: any = ref(false)
let modalReschedule: any = ref(false)
let norec_pp: any = ref('')
let norec_SO: any = ref('')
let ikiWesPernahDikirim: any = ref(false)
let dataWesPernahDikirim: any = ref([])
let dataYangBaruDitambah: any = ref([])
let isLoadDataOrder: any = ref(false)
let isLoadDataSoNorec: any = ref(false)
let detailDiagnosa: any = ref(0)
let dataStokObat: any = ref([])
let detailOrderVerify: any = ref(0)
let detailOrderLayanan: any = ref(0)
let dataPenunjang: any = ref([])
const dataPenjadwalan: any = ref([])
const remakeData: any = ref([])
let isData: any = ref()
let sourceItemSelect: any = ref([])
let data2: any = ref([])
let chartLO: any = ref({
  series: [],
})
// const savedFilterRuangan = localStorage.getItem('idRuangan_Rad');
const savedFilterTgl_Awal = localStorage.getItem('periodeTgl_Awal');  //get value from localStorage user
const savedFilterTgl_Akhir = localStorage.getItem('periodeTgl_Akhir'); // get vvalue from localStorage user
const item: any = ref({
  periode: reactive({
    start: savedFilterTgl_Awal ? savedFilterTgl_Awal : new Date(),
    end: savedFilterTgl_Akhir ? savedFilterTgl_Akhir : new Date(),
  }),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
  waktuPemeriksaan: new Date()

})
const order: any = ref(0)
const pen: any = ref(0)
const pasiennya: any = ref(0)
const dataOrder: any = ref(0)
const router = useRouter()
item.value.filterRuangan = 330

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dokterPraktek.value
  }
  return dokterPraktek.value.filter((item: any) => {
    return item.namalengkap.match(new RegExp(filters.value, 'i'))
  })
})

const fetchDataOrder = async (q: any, carnap: any, polikun: any) => {
  statusOrder.value = q
  isLoading.value = true
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  let qnamapasien = ''
  let search = ''
  let ruanganid = ''
  let allPeriode = ''

  if (item.value.periode.start && item.value.periode.end && savedFilterTgl_Akhir != tglAkhir && savedFilterTgl_Awal != tglAwal) {
    // localStorage.setItem('idRuangan_Rad', item.value.filterRuangan)
    localStorage.setItem('periodeTgl_Awal', H.formatDate(item.value.periode.start, 'YYYY-MM-DD'))   //set value to loacalStorage computer user
    localStorage.setItem('periodeTgl_Akhir', H.formatDate(item.value.periode.end, 'YYYY-MM-DD'))    //set value to localSotrage computer user
    // ruanganid = `&ruanganid=${item.value.filterRuangan}`
    // console.log('set item')
  }
  else if (savedFilterTgl_Awal && savedFilterTgl_Akhir) {
    let tglAwalee = savedFilterTgl_Awal
    let tglAkhiree = savedFilterTgl_Akhir

    // ruanganid = `&ruanganid=${item.filterRuangan}`
    tglAwal = `tglAwal=${tglAwalee}`
    tglAkhir = `&tglAkhir=${tglAkhiree}`
    // console.log('finding data by saved item');
  }

  else {
    // ruanganid = ''
    tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
    // console.log('no filter set')
  }

  if (item.value.filterRuangan) {
    ruanganid = `&ruanganid=${item.value.filterRuangan}`  //DEFAULT
  }

  let qnocm = ''
  let qnoregistrasi = ''
  let StatusOrder = ''
  let buatranap = ''
  let buatpoli = ''
  item.value.statusOrder = q
  if (order) StatusOrder = '&statusorder=' + q
  if (item.value.qnamapasien) qnamapasien = '&qnamapasien=' + item.value.qnamapasien
  if (item.value.qnocm) qnocm = '&qnocm=' + item.value.qnocm
  if (item.value.qnoregistrasi) qnoregistrasi = '&qnoregistrasi=' + item.value.qnoregistrasi
  if (item.value.search) search = '&search=' + item.value.search
  if (carnap) buatranap = '&cariranap=true'
  if (allper.value == true) allPeriode = '&allperiode=true'
  if (allper.value != true) allPeriode = '&allperiode=false'
  if (!carnap) buatranap = '&cariranap=false'
  if (polikun) buatpoli = '&polikun=true'
  if (!polikun) buatpoli = '&polikun=false'
  let limit: any = currentPageOrder.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  await useApi().get('/dashboard/radiologi?' + tglAwal + tglAkhir + StatusOrder + qnamapasien + qnocm + buatranap + buatpoli + qnoregistrasi + allPeriode + ruanganid + search + `&limit=${limit}&offset=${offset}`).then((response) => {
    modalFilter.value = false
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
      let ini = element.namapasien.split(' ')
      let init = element.namapasien.substr(0, 2)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
      element.ruanganasal = (element.asal_ruangan.length > 14) ? element.ruanganasal = element.asal_ruangan.substring(0, 14) + '...' : element.ruanganasal = element.asal_ruangan
      element.tglorder = moment(element.tglorder).format('YYYY-MM-DD')
    });
    isData.value = response.length
    dataOrder.value = response.data
    dataOrder.value.total = response.total
    isLoading.value = false
  }).catch((err) => {
    modalFilter.value = false
  })
  isLoading.value = false
}

const exportExcel = () => {
  remakeData.value = dataPenjadwalan.value.map((e: any) => {
    return {
      TanggalJadwal: e.tanggal_jadwal,
      TanggalOrde: e.tanggal_order,
      NamaPasien: e.namapasien,
      JenisKelamin: e.jeniskelamin,
      UmurPasien: e.umur_pasien,
      Nocm: e.nocm,
      TanggalLahir: e.tgllahir,
      Diagnosis: e.diagnosis,
      Tindakan: e.namaproduk,
      RuangOrder: e.ruang_asal,
      DokterOrder: e.dokterorder,
      DOkterPemeriksa: e.dokterpemeriksa,
      CaraBayar: e.kelompokpasien,
      PetugasVerif: e.pegawai_penerima,
      Satus: e.status,
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
  window.open(_url, EXCEL_EXTENSION).focus()
  exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

const fetchDetail = async () => {
  dokterPraktek.value = []
  dataStokObat.value = []
  await useApi().get('/dashboard/radiologi/get-detail-rad').then((response) => {
    dokterPraktek.value = response.dokter
    dataStokObat.value = response.produk
  })
}

const fetchPenunjang = async (q: any) => {
  let qnama = ''
  let qnocm = ''
  let qsearch = ''
  let qnoregistrasi = ''
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  let ruanganid = ''
  let allPeriode = ''

  item.value.statusPenunjang = q
  if (pen) statusPenunjang = '&statusorder=' + q
  if (item.value.filterRuangan) {
    ruanganid = `&ruanganid=${item.value.filterRuangan}`
  }
  if (item.value.qnama) qnama = `&qnama=${item.value.qnama}`
  if (item.value.qsearch) qsearch = `&qsearch=${item.value.qsearch}`
  if (item.value.qnocm) qnocm = `&qnocm=${item.value.qnocm}`
  if (item.value.qnoregistrasi) qnoregistrasi = `&qnoregistrasi=${item.value.qnoregistrasi}`
  if (allper.value == true) allPeriode = '&allperiode=true'
  if (allper.value != true) allPeriode = '&allperiode=false'

  dataPenunjang.value.loading = true
  dataPenunjang.value = []
  let limit: any = currentPenunjang.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  const response = await useApi().get('/dashboard/radiologi/get-penunjang-rad?' + tglAwal + tglAkhir + statusPenunjang + qnama + allPeriode + qnocm + qnoregistrasi + ruanganid + qsearch + `&limit=${limit}&offset=${offset}`)

  dataPenunjang.value.loading = false
  dataPenunjang.value = response.data
  dataPenunjang.value.total = response.total

}


const getListPelayanan = async (data: any) => {
  const response = await useApi().get(`/dashboard/radiologi/get-pelayanan?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}&kebangsaan=${data.kebangsaan}`)
  d_Produk.value = response.map((e: any) => {
    return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
  })
}

const fetchDropdown = async () => {
  await useApi().get(`/dashboard/radiologi/get-dokter`).then((response) => {
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_JenisKelamin.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id } })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e.id } })
    d_Dokter.value = response.data.map((e: any) => { return { label: `${e.namalengkap}`, value: `${e.id}`, default: e } })
    d_DokterVerif.value = response.pegawaiRadiologi.map((e: any) => { return { label: e.namalengkap, value: e.id, sanata: e.sanata_id } })
  })

  //? Autofill
  item.value.filterRuangan = d_Ruangan.value.find((item) => item.default.objectdepartemenfk == '27').value;
}

const orderVerify = async (e: any) => {
  // console.log('masuk ke verifikasi')
  await H.statusClosingPasien(e.noregistrasi);
  getEmr(e.pd_norec, e.nocmfk)
  detailOrderLayanan.value = []
  modalDetailOrder.value = true
  let data = {
    'idkelas': e.objectkelasfk,
    'idJenisPelayanan': e.jenispelayananfk,
    'idRekanan': e.objectrekananfk,
    'kebangsaan': e.kebangsaan,
  }
  d_DokterVerif.value.forEach((element: any) => {
    if (element.value == H.pegawaiLogin().id) {
      item.value.dokterverify = element
      return
    }
  });
  item.value.idJenisPelayanan = e.jenispelayananfk
  item.value.namapasien = e.namapasien
  item.value.inisial = e.initials
  item.value.ruangantujuan = e.ruangantujuan
  item.value.ruanganasal = e.ruanganasal
  item.value.noorder = e.noorder
  item.value.no_rm = e.pas_nocm
  // item.value.
  item.value.jeniskelamin = e.jeniskelamin
  item.value.kelompokpasien = e.kelompokpasien
  item.value.idRuanganTujuan = e.objectruangantujuanfk
  item.value.pdNorec = e.pd_norec
  item.value.soNorec = e.so_norec
  item.value.noregistrasi = e.noregistrasi
  item.value.objectpegawaiorderfk = e.objectpegawaiorderfk
  item.value.dokterorder = e.nama_pegawai
  item.value.tglorder = e.tglorder
  item.value.umur = e.umur
  item.value.tglregistrasi = e.tglregistrasi
  item.value.kelas = e.objectkelasfk
  item.value.catatanklinis = e.catatanklinis
  item.value.catatanDiagnosis = e.catatanklinis
  item.value.catatan = e.keterangan
  item.value.objectkebangsaanfk = e.objectkebangsaanfk
  getListPelayanan(data)
  isLoadDataOrder.value = true
  isLoadDataSoNorec.value = false
  const getHargaLayanan = await useApi().get(`/dashboard/radiologi/get-order-layanan-rad?strukorderfk=${e.so_norec}&objectkelasfk=${e.objectkelasfk}&iscito=${e.iscito}`)
  // const response = await useApi().get(`/dashboard/radiologi?statusorder=${statusOrder.value}&noorder=${e.noorder}`)
  getHargaLayanan.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  isLoadDataOrder.value = false
  // detailDiagnosa.value = response[0].detailDiagnosa
  detailOrderLayanan.value = getHargaLayanan
}

const getDetailVerify = async (e: any) => {
  isLoadDataSoNorec = false

  item.value.tgllahir = e.tgllahir
  item.value.umur = e.umur
  item.value.noregistrasi = e.noregistrasi
  item.value.nobpjs = e.nobpjs ? e.nobpjs : '-'
  item.value.noidentitas = e.noidentitas
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
  item.value.tglorder = e.tglorder
  item.value.tglregistrasi = e.tglregistrasi
  item.value.catatanklinis = e.catatanklinis

  modalDetailOrderVerify.value = true
  const response = await useApi().get(`/dashboard/radiologi/get-order-verify?norec_so=${e.so_norec}`)
  const diagnosa = await useApi().get(`/dashboard/radiologi?tglAwal=${e.tglorder}&tglAkhir=${e.tglorder}&statusorder=${statusOrder.value}&noorder=${e.noorder}`)
  detailDiagnosa.value = diagnosa.data ? diagnosa.data[0].detailDiagnosa : ''
  response.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  detailOrderVerify.value = response
  console.log(detailOrderVerify)
}

const save = async () => {
  // if (!item.value.dokterverify || !item.value.dokterverify.value) {
  //   useToaster().error('Dokter Verifikator tidak boleh kosong');
  //   return;
  // }

  for (let b = 0; b < detailOrderLayanan.value.length; b++) {
    const element = detailOrderLayanan.value[b];
    console.log('eiiiii', element)
    if (!element.dokterverify || !element.dokterverify.default || !element.dokterverify.default.id) {
      useToaster().error('Dokter Verifikator tidak boleh kosong');
      return;
    }
  }
  if (!item.value.petugasverify || !item.value.petugasverify.value) {
    useToaster().error('Petugas Verifikator tidak boleh kosong');
    return;
  }
  if (!item.value.radiografer || !item.value.radiografer.value) {
    useToaster().error('Radiografer Tidak Boleh Kosong') //Default
    return
  }
  if (!item.value.catatanklinis || item.value.catatanklinis.trim().length < 2) {
    useToaster().error('Catatan Klinis harus diisi dan minimal 2 karakter');
    return;
  }

  await H.statusClosingPasien(item.value.noregistrasi);
  let datas: any = []
  let objBridg: any = []

  let parameter = {
    'idruangtujuan': item.value.idRuanganTujuan,
    'pd_norec': item.value.pdNorec,
    'objectpegawaiorderfk': item.value.objectpegawaiorderfk,
    'tglregistrasi': item.value.tglregistrasi,
    'noregistrasi': item.value.noregistrasi,
    'so_norec': item.value.soNorec,
    'catatan': item.value.catatan,
    'dokterverify': item.value.dokterverify.value,
    'pegawaiverifikatorfk': item.value.petugasverify.value,
    'radiograferfk': item.value.radiografer ? item.value.radiografer.value : null, //DEFAULT
    // 'radiograferfk': null,
    'catatanklinis': item.value.catatanklinis,
    'catatanDiagnosis': item.value.catatanDiagnosis ? item.value.catatanDiagnosis : null,
    'keteranganradiologi': item.value.keteranganradiologi ? item.value.keteranganradiologi : null,
    'tglpelayanan': H.formatDate(item.value.waktuPemeriksaan, 'YYYY-MM-DD HH:mm:ss'),
    'nobatchradionuklida': null,
    'nobatchradiofarmaka': null,
    'dosisradiofarmasis': null,
    'jampermintaan': null,
    'dosisfullsyringe': null,
    'jamfullsyringe': null,
    'dosisemptysyringe': null,
    'jamemptysyringe': null,
    'rutelokasisuntik': null,
    'jaminjeksi': null,
    'pemeriksaanradiograferfk': null,
    'jamakuisisi': null,
    'treatment': null,
    'paparanradiasi': null,
  }
  let itemsave = {
    "details": objBridg,
    "noorder": item.value.noorder,
    "objectkelasfk": item.value.kelas,
    "objectruangantujuanfk": item.value.idRuanganTujuan,
    "objectpegawaiorderfk": item.value.objectpegawaiorderfk,
    // "iddokterverif": item.value.dokterverify.value,    //DEFAULT
    // "namadokterverif": item.value.dokterverify.label,  //DEFAULT
    "idadmin": item.value.petugasverify.sanata,
    "namaadmin": item.value.petugasverify.label,
    "idradiografer": item.value.radiografer ? item.value.radiografer.id : null,     //DEFAULT
    "namaradiografer": item.value.radiografer ? item.value.radiografer.label : null,   //DEFAULT
    // "idradiografer": null,
    // "namaradiografer": null,
    "catatan_klinis": item.value.catatanklinis ? item.value.catatanklinis : null,
  }

  if (detailOrderLayanan.value.length < 1) {
    useToaster().error('Order Tidak Boleh kosong')
    return
  }
  if (!item.value.dokterverify) {
    useToaster().error('Dokter Verify Tidak Boleh Kosong')
    return
  }
  if (!item.value.petugasverify) {
    useToaster().error('Petugas Verify Tidak Boleh Kosong')
    return
  }
  // if (!item.value.radiografer) {
  //   useToaster().error('Radiografer Tidak Boleh Kosong')       //DEAFULT
  //   return
  // }
  if (!item.value.waktuPemeriksaan) {
    useToaster().error('Waktu Pemeriksaan Tidak Boleh Kosong')
    return
  }
  if (item.catatanklinis == '-' || (item.catatanklinis && item.catatanklinis.length < 2)) {
    useToaster().error('Catatan Klinis Harus Lengkap')
    return
  }
  if (ikiWesPernahDikirim.value == true) {
    console.log('masuk kondisi pengecekan data order')
    for (let v = dataYangBaruDitambah.value.length - 1; v >= 0; v--) {
      const element = dataYangBaruDitambah.value[v];
      if (element.status == false) {
        const objSave = {
          idProduk: element.prid,
          hargaLayanan: element.hargasatuan,
          tglpelayanan: element.tglpelayanan,
          keteranganlainnya: element.keteranganlainnya ?? NULL,
          jumlah: element.qtyproduk,
          komponenharga: element.komponenharga,
          iddokterverif: element.dokterverify.default.id,
          status: true
        };
        datas.push(objSave);

        // Push to the objBridg array
        objBridg.push({
          produkfk: element.prid,
          namaproduk: element.namaproduk,
          qtyproduk: element.qtyproduk,
          objectkelasfk: item.value.kelas,
          iddokterverif: element.dokterverify.default.id,
          namadokterverif: element.dokterverify.default.namalengkap,
        });
      }
    }
  }
  else {
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
      let response = detailOrderLayanan.value[i]
      console.log('tesss', response)
      let komponenHarga = detailOrderLayanan.value[i].komponenharga
      let jadwal = null;
      if (response.jadwal_tindakan && response.jadwal_tindakan != '-' && response.jadwal_tindakan != '') {
        jadwal = H.formatDate(response.jadwal_tindakan, 'YYYY-MM-DD HH:mm:ss')
      }
      var objSave = {
        'idProduk': response.prid,
        'hargaLayanan': response.hargasatuan,
        'tglpelayanan': response.tglpelayanan,
        'keteranganlainnya': response.keteranganlainnya ?? null,
        'jumlah': response.qtyproduk,
        'komponenharga': komponenHarga,
        'iddokterverif': response.dokterverify.default.id,
        'jadwal_tindakan': jadwal,
        'status': true
      }
      datas.push(objSave)
      objBridg.push({
        produkfk: response.prid,
        namaproduk: response.namaproduk,
        qtyproduk: response.qtyproduk,
        objectkelasfk: item.value.kelas,
        iddokterverif: response.dokterverify.default.id,
        namadokterverif: response.dokterverify.default.namalengkap,
        status: true
      })
    }
  }

  isLoadingSave.value = true
  loadingSaveKun.value = true

  useApi().post('/dashboard/radiologi/save-order-pelayanan', { 'data': datas, 'parameter': parameter }).then((response: any) => {
    dataWesPernahDikirim.value.push(datas)
    ikiWesPernahDikirim.value = true
    fetchDataOrder(0)
    loadingSaveKun.value = false
    isLoadingSave.value = false
  }).catch((error) => {
    useToaster().error('Something Went Wrong')
  })
  isLoadDataSoNorec.value = true   //Default
}

const cetakBuktiOrder = () => {
  // console.log(so_norec)

  H.printBlade('radiologi/cetakan-hasil-radiologi?noregistrasi=' + item.value.noregistrasi
    + '&so_norec=' + item.value.soNorec);
}

const hapusItems = (e: any) => {
  for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
    if (detailOrderLayanan.value[i].no == e.no) {
      detailOrderLayanan.value.splice(i, 1);
    }
  }
  dataSource.value = detailOrderLayanan.value
}


const changeSwitch = (e: any) => {
  fetchDataOrder(e)
}
const changeSwitchPen = (e: any) => {
  fetchPenunjang(e)
}

const getHarga = async (idProduk: any) => {
  const response = await useApi().get(`/dashboard/radiologi/get-pelayanan?idkelas=${item.value.kelas}&idjenispelayanan=${item.value.idJenisPelayanan}&idProduk=${idProduk}`)
  item.value.hargaLayanan = response[0].hargasatuan
  item.value.namaproduk = response[0].namaproduk
  item.value.jumlah = 1
}

const clear = () => {
  item.value.id = ''
  delete item.value.no
  delete item.value.produk
  item.value.hargaLayanan = ''
  item.value.qtyproduk = ''
  item.value.jumlah = ''
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
    status: false
  }
  detailOrderLayanan.value.push(data)
  dataYangBaruDitambah.value.push(data)

  console.log('add value ', dataYangBaruDitambah)
  saveDiasabled.value = false
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

const chartLayananByRuangan = async () => {
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  await useApi().get(`/dashboard/radiologi/chart-layanan-ruangan?${tglAwal}${tglAkhir}`).then((res: any) => {
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

const showModalFilter = () => {
  modalFilter.value = true
}

const changeRuang = (e: any) => {
  fetchDataOrder(0)
  fetchPenunjang(0)
  fetchPasien(0)
  chartLayananByRuangan()
  // fetchPenjadwalan(0)
}
const fetchPasien = async (e: any) => {
  // statusRegis.value = e
  let ruanganid = ''
  let statusRegis = ''
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
    , rsearch = ''
  if (item.value.qnama) namapasien = item.value.qnama
  if (item.value.qnoreg) noreg = item.value.qnoreg
  if (item.value.qnocm) nocm = item.value.qnocm
  if (item.value.fStatusAntrian) statuspanggil = item.value.fStatusAntrian
  if (item.value.rsearch) rsearch = item.value.rsearch
  if (pasiennya) statusRegis = '&statusregis=' + e
  isLoading.value = true
  let limit: any = currentPasienDaftar.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  dataPasien.value = []
  const response = await useApi().get(
    '/radiologi/list-pasien-regis?ruanganid=' + ruanganid
    + '&dari=' + dari
    + '&sampai=' + sampai
    + '&namapasien=' + namapasien
    + '&nocm=' + nocm
    + '&noregistrasi=' + noreg
    + '&statuspanggil=' + statuspanggil
    + statusRegis
    + '&rsearch=' + rsearch
    + '&limit=' + limit
    + '&offset=' + offset
  )
  isLoading.value = false
  response.data.sort(compare);
  dataPasien.value = response.data
  dataPasien.value.total = response.total
  console.log('data pasien', dataPasien.value);


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
const getEmr = (norec_pd: any, nocmfk: any) => {
  useApi().get(`emr/auto-fill?nocmfk=${nocmfk}&norec_pd=${norec_pd}&collection=AsesmenMedisRawatJalan&field=TADiagnosa,MOI,diagnosaIcd10`).then((response) => {
    if (response != null) {
      const dataDiagnosa = response.TADiagnosa
      if (dataDiagnosa != null) {
        item.catatanDiagnosis = dataDiagnosa;
      }
    } else {
      useApi().get(`emr/auto-fill?nocmfk=${nocmfk}&norec_pd=${norec_pd}&collection=AsesmenAwalMedisGawatDarurat&field=TADiagnosis,TAMOI,diagnosaIcd10`).then((response_1) => {
        if (response_1) {
          const dataDiagnosa = response_1.TADiagnosis
          if (dataDiagnosa != null) {
            item.catatanDiagnosis = dataDiagnosa;
          }
        }
      })
    }
  })
}

const daftar = (e: any) => {
  item.norec_pd = e.norec_pd
  item.objectruanganlastfk = e.objectruanganlastfk
  item.nocmfk = e.nocm
  item.noregistrasi = e.norec_pd
  // item.objectruangantujuanfk = e.objectruangantujuanfk
  item.objectruanganfk = e.objectruanganfk
  item.norec_apd = e.norec_apd
  item.objectkelasfk = e.objectkelasfk
  item.tglorder = e.tglorder
  sourceItemSelect.value = e
  modalTransaksi.value = true
}

const saveTransaksi = async (e: any) => {
  let json = {
    pasiendaftar: {
      'norec_pd': item.norec_pd,
      'tglorder': item.tglorder,
      'objectkelasfk': item.objectkelasfk,
      'noregistrasifk': item.norec_pd,
      'tglregistrasi': moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
      'objectruanganlastfk': item.objectruanganlastfk
    },
    antrianpasiendiperiksa: {
      'norec_apd': item.norec_apd,
      'objectruangantujuanfk': e,
    }
  }

  isLoading.value = true
  await useApi().post(`/radiologi/save-transaksi-rad`, json).then((response: any) => {
    isLoading.value = false
    clear()
    goTo(sourceItemSelect.value)
    // fetchPasien()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const goTo = (e: any) => {
  let bindData = sourceItemSelect.value
  router.push({
    name: 'module-radiologi-transaksi-radiologi',
    query: {
      nocmfk: bindData.nocmfk,
      norec_pasien_daftar: bindData.norec_pd,
      norec_apd: bindData.norec_apd
    },

  })
}

const emr = (e: any) => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd ? e.norec_pd : e.pd_norec,
      norec_apd: e.norec_apd,
    }
  })
}

const changePeriode = () => {
  modalFilter.value = false
  fetchDataOrder(0)
  fetchPenunjang(0)
  chartLayananByRuangan()
  // fetchPenjadwalan(0)
}

const transaksiPelayanan = (e: any) => {
  console.log(e)
  router.push({
    name: 'module-radiologi-transaksi-radiologi',
    query: {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.norec_pd,
      norec_apd: e.norec_apd,
    },

  })
}

const inputTindakan = (e: any) => {
  modalInputManual.value = true
  source.norec_pd = e.norec_pd
  source.pasien = e
}

const openPenjadwalan = (e: any) => {
  norec_SO.value = e.so_norec
  modalPenjadwalan.value = true
}

const savePenjadwalan = (tglpenjadwalan: any, id_so: any) => {
  let tgl = H.formatDate(tglpenjadwalan, 'YYYY-MM-DD HH:mm:ss')
  useApi().post(`/dashboard/radiologi/save-penjadwalan?norec_so=${id_so}&tglpenjadwalan=${tgl}`).then((response: any) => {
    norec_SO.value = ''
    fetchDataOrder(5, false, false)
  }).catch((e: any) => {
    console.error('error', e)
  })
  modalPenjadwalan.value = false
}

const UpdateJenisKelamin = (e: any) => {
  item.value.norm = e.nocm
  item.value.jeniskelamin = e.objectjeniskelaminfk
  modalJenisKelamin.value = true
}

const saveJenisKelamin = async (e: any) => {
  let json = {
    'nocm': item.value.norm,
    'objectjeniskelaminfk': item.value.jeniskelamin,
  }
  useApi().post(`/dashboard/save-jenkel`, json).then((response: any) => {
    modalJenisKelamin.value = false
    fetchPenunjang()
  })
}

const UpdateGolonganDarah = (e: any) => {
  item.value.nocm = e.nocm
  item.value.golongandarah = e.objectgolongandarahfk
  modalGolonganDarah.value = true
}

const saveGolonganDarah = async (e: any) => {
  let json = {
    'nocm': item.value.nocm,
    'objectgolongandarahfk': item.value.golongandarah,
  }
  useApi().post(`/dashboard/save-goldar`, json).then((response: any) => {
    modalGolonganDarah.value = false
    fetchPenunjang()
  })
}
const batalVerif = async (e: any) => {
  let json = { 'norec_so': e.so_norec }
  useApi().post(`/dashboard/radiologi/unverif-order`, json).then((response: any) => {
    fetchDataOrder(1)
  })
}

const cetakOrder = async (e: any) => {
  let so_norec = e.so_norec;
  H.printBlade(`report/cetak-order?type=radiologi&noregistrasi=${so_norec}`)
}
const cetakLabel = async (e: any) => {
  let so_norec = e.so_norec;
  H.printBlade(`report/cetak-label?noregistrasi=${so_norec}&dariradiologi=true`)
}
const cetakLabelPenunjang = async (e: any) => {
  let so_norec = e.norec_so;
  H.printBlade(`report/cetak-label?noregistrasi=${so_norec}&dariradiologi=true`)
}
const cetakLabel2 = async (noregistrasi) => {
  console.log(noregistrasi);
  H.printBlade(`report/cetak-label?noregistrasi=${noregistrasi}&dariradiologi=true`);
};
const cetakSEP = (e: any) => {
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true", 'SEP', 1)
}
const showPasienLama = async () => {
  router.push({
    name: 'module-registrasi-pasien-lama',
    query: {},
  })
}
const reload = async () => {
  fetchDataOrder(0)
  fetchPenunjang(0)
  // fetchPenjadwalan(0)
}

function changeTindakan(e: any) {
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
      H.alert('warn', 'Komponen Tarif tidak ada')
      return
    }
    item.value.hargaLayanan = response.harga.hargasatuan
    item.hargasatuanDef = response.harga.hargasatuan
    item.value.jumlah = 1
    d_Komponen.value = response.komponen

  })
}

const reschedule = (e: any) => {
  norec_pp.value = e.norec_pp
  modalReschedule.value = true
}

const saveReschedule = (tglnew: any, norec_pp: any,) => {
  let tgl = H.formatDate(tglnew, 'YYYY-MM-DD HH:mm:ss')
  useApi().post(`/dashboard/radiologi/save-penjadwalan-new?norec_pp=${norec_pp}&tglnew=${tgl}`).then((response: any) => {
    fetchPenjadwalan()
  }).catch((e: any) => {
    console.error('error', e)
  })
  modalReschedule.value = false
  delete item.value.tglnew
}

async function fetchPenjadwalan() {
  isLoading.value = true;
  let dari = ''
  if (item.value.periode.start) {
    dari = `&dari=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`
  }
  let sampai = ''
  if (item.value.periode.end) {
    sampai = `&sampai=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`
  }
  let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';
  await useApi().get(`/dashboard/radiologi/laporan-penjadwalan-rad?${dari}${sampai}${search}`).then((response) => {
    response.forEach((element, i) => {
      element.no = i + 1;
    });
    dataPenjadwalan.value = response;
    isLoading.value = false;
  }).catch((e) => {
    isLoading.value = false;
  });
}

watch(currentPenunjang.value, () => {
  fetchPenunjang(0)
})
watch(currentPageOrder.value, () => {
  fetchDataOrder(0)
})
watch(currentPasienDaftar.value, () => {
  fetchPasien(0)
})
watch(allper, (value) => {
  if (value == true) {
    // console.log(value)
    fetchPenunjang(0)
    fetchDataOrder(0)
  }
  else {
    fetchPenunjang(0)
    fetchDataOrder(0)
  }
})
// watch(allregis, (value) => {
//   if(value == true){
//     // console.log(value)      //testing value dashboard
//     // fetchPenunjang(0)
//     // fetchDataOrder(0)
//     console.log('data',value)
//   }
//   else{
//     console.log('else mode')
//     // fetchPenunjang(0)
//     // fetchDataOrder(0)
//   }
// })
watch(
  () => [
    order.value
  ], () => {
    changeSwitch(order.value)
  }
)
watch(
  () => [
    pen.value
  ], () => {
    changeSwitchPen(pen.value)
  }
)
const changeSelected = () => {
  currentPasienDaftar.value.limit = 30
  currentPageOrder.value.limit = 30
  currentPenunjang.value.limit = 30
  const newParams = { page: 1 };
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
  router.push({ query: { ...router.query, ...newParams } })
}

onMounted(() => {
  fetchDataOrder(0)
  fetchDropdown()
  chartLayananByRuangan()
  fetchPenunjang(0)
  fetchDetail()
  fetchPasien(0)
})
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  position: relative;
  background: var(--fade-grey-light-2);
  border: 1px solid var(--fade-grey);
  max-width: 400px;
  height: 35px;
  border-bottom: none;

}

.tb-order .text-value {
  font-family: var(--font-alt);
  color: var(--dark-text);
  font-weight: 400;
  font-size: 12px;
}

.user-grid-v2 {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    @include vuero-s-card;

    text-align: center;

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .people {
      display: flex;
      justify-content: center;
      padding: 8px 0 30px;

      .v-avatar {
        margin: 0 4px;
      }
    }

    .buttons {
      display: flex;
      justify-content: space-between;

      .button {
        width: calc(50% - 4px);
        color: var(--light-text);

        &:hover,
        &:focus {
          border-color: var(--fade-grey-dark-4);
          color: var(--primary);
          box-shadow: var(--light-box-shadow);
        }
      }
    }
  }

  .grid-item-wrap {
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    transition: all 0.3s; // transition-all test

    .grid-item-head {
      background: #fafafa;
      border-radius: var(--radius-large) 6px 0 0;
      padding: 20px;

      .flex-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;

        .meta {
          span {
            display: flex;

            &:first-child {
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.85rem;
              color: white;
            }

            &:nth-child(2) {
              font-size: 0.8rem;
              color: white;
            }
          }
        }

        .status-icon {
          height: 28px;
          width: 28px;
          min-width: 28px;
          border-radius: var(--radius-rounded);
          border: 1px solid var(--fade-grey-dark-3);
          display: flex;
          align-items: center;
          justify-content: center;

          &.is-success {
            background: var(--success);
            border-color: var(--success);
            color: var(--white);
          }

          &.is-warning {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
          }

          &.is-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: var(--white);
          }

          i {
            font-size: 8px;
          }
        }
      }

      .buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0;

        .button,
        .v-button {
          width: calc(50% - 4px);
          color: var(--light-text);
          margin-bottom: 0;

          &:hover,
          &:focus {
            border-color: var(--fade-grey-dark-4);
            color: var(--primary);
            box-shadow: var(--light-box-shadow);
          }
        }
      }
    }

    .grid-item {
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      border: none;
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .user-grid-v2 {
    .grid-item-wrap {
      border-color: var(--dark-sidebar-light-12);

      .grid-item-head {
        background: var(--dark-sidebar-light-4);
      }
    }
  }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
  background: var(--success) !important
}

.user-grid-v2 .grid-item-wrap .grid-item-head {
  padding: 10px;
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

.search-widget {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 10px;
  background-color: var(--white);
  border-radius: 16px;
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
}

.p-datatable.p-component {
  .p-datatable-wrapper {
    height: 1000px;
  }
}
</style>
