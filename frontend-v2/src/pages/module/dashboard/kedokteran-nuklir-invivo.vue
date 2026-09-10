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
                  <h3 style="color:white">Dashboard Kedokteran Nuklir In Vivo</h3>
                  <p>Selamat Datang , {{ userLogin.pegawai.namaLengkap }}</p>
                  <VControl>
                    <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" class="f-text"
                      placeholder="Filter ruangan" :searchable="true" autocomplete="off"
                      @select="changeRuang(item.filterRuangan)" />
                  </VControl>
                  <VTag @click="showModalFilter()" color="danger" rounded elevated
                    style="position: relative; bottom: -1.5rem; cursor:pointer;">{{
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
          <Badge :value="dataOrder.length" severity="danger"
            style="z-index: 6;top: 38px;position: relative; left: 20px" />
          <div class="column is-12" style="margin-top: 2rem;">
            <VTabs slider selected="Pasien" :tabs="[
              { label: 'Daftar Order', value: 'Pasien' },
              { label: 'Pasien Penunjang', value: 'Penunjang' },
              { label: 'Daftar Pasien Registrasi', value: 'PasienDaftar' }]" style="margin-top: -2rem;"
              @update:selected="changeSelected()">
              <template #tab="{ activeValue }">
                <p v-if="activeValue === 'Pasien'">
                <div class="list-view list-view-v3">

                  <div class="search-menu mb-2">
                    <div class="search-location" style="width: 100%">
                      <i class="iconify" data-icon="feather:search"></i>
                      <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
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
                    <VButton raised class="search-button" @click="fetchDataOrder(order)" :loading="isLoading"> Cari Data
                    </VButton>
                  </div>

                  <VCard class="text-center pt-0 pb-0 mt-0">
                    <VRadio v-model="order" value="0" label="Pending" name="outlined_radio" color="warning" />
                    <VRadio v-model="order" value="1" label="Verifikasi" name="outlined_radio" color="info" />
                    <VRadio v-model="order" value="2" label="Selesai Pelayanan" name="outlined_radio" color="primary" />

                  </VCard>

                  <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6"
                    :class="[dataOrder.length !== 0 && 'is-hidden']">
                    <template #image>
                      <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
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

                            </h3>
                            <span>
                              <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                              <span>{{ items.ruangantujuan }}</span>
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
                              <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                              <span>{{ items.pas_nocm }}</span>
                              <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                              <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                              <span>{{ items.nobpjs }}</span>
                              <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                              <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                              <span>{{ items.noidentitas }}</span>

                            </span>
                            <br>
                            <VTag color="warning" rounded v-if="items.statusorder == 0">Pending
                            </VTag>
                            <VTag color="danger" rounded >{{ items.terapiradioaktif ? items.terapiradioaktif : '-' }}
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
                            <VIconButton v-tooltip.bottom.left="'Verifikasi'" label="Bottom Left" color="primary" circle
                              icon="pi pi-check-circle" v-if="items.statusorder == 0" @click="orderVerify(items)"
                              style="margin-right: 15px;" />

                            <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                              @click="emr(items)" v-tooltip.bottom="'EMR'" v-if="items.statusorder == 0"
                              style="margin-right: 15px;" />

                            <VIconButton v-tooltip.bottom.left="'Detail'" label="Bottom Left" v-else color="primary"
                              circle icon="pi pi-book" @click="getDetailVerify(items)" style="margin-right: 15px;" />

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
                            <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bubble="'CETAK'">
                              <template #content>
                                <a role="menuitem" class="dropdown-item is-media" @click="cetakLabel(items)">
                                  <div class="icon">
                                    <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Cetak Label</span>
                                    <span>Cetak Label Pasien</span>
                                  </div>
                                </a>
                                <a role="menuitem" class="dropdown-item is-media" @click="cetakSEP(items)">
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
                                    <span>Cetak Order Lab</span>
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
                <div class="search-menu mb-2">
                  <div class="search-location" style="width: 100%">
                    <i class="iconify" data-icon="feather:search"></i>
                    <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                      v-model="item.qsearch" v-on:keyup.enter="fetchPenunjang" />
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
                  <VButton raised class="search-button" @click="fetchPenunjang()" :loading="isLoading"> Cari
                    Data
                  </VButton>
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
                                      H.formatDateIndoSimple(item.tglorder)
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
                                  <a role="menuitem" @click="cetakLabelPrev(item)" class="dropdown-item is-media">
                                    <!-- <pre>{{ item }}</pre> -->
                                    <div class="icon">
                                      <i aria-hidden="true" class="lnil lnil-print"></i>
                                    </div>
                                    <div class="meta">
                                      <span>Cetak Label Pasien</span>
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
                              <h3 class="dark-inverted">{{ item.namapasien }}</h3>
                              <!-- <p>{{ item.nocm }}</p> -->
                              <p>No REG : {{ item.noregistrasi }}</p>
                              <p>No RM : {{ item.nocm }}</p>
                              <p>No BPJS : {{ item.nobpjs }}</p>
                              <p>NIK : {{ item.noidentitas }}</p>

                              <div class="buttons mt-4">
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
                <p v-else-if="activeValue === 'PasienDaftar'">
                <div class="list-view list-view-v3">
                  <div class="search-menu mb-2">
                    <div class="search-location" style="width: 100%">
                      <i class="iconify" data-icon="feather:search"></i>
                      <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                        v-model="item.rsearch" v-on:keyup.enter="fetchPasien()" />
                    </div>
                    <!-- <div class="search-location">
                                          <i class="iconify" data-icon="feather:activity"></i>
                                          <input type="text" placeholder="No Registrasi" v-model="item.qnoreg" />
                                      </div>
                                      <div class="search-salary">
                                          <i class="iconify" data-icon="feather:clipboard"></i>
                                          <input type="text" placeholder="No RM" v-model="item.qnocm" />
                                      </div>
                                      <div class="search-job">
                                          <i class="iconify" data-icon="feather:user"></i>
                                          <input type="text" placeholder="Nama Pasien" v-model="item.qnama" />
                                      </div> -->
                    <VButton raised class="search-button" @click="fetchPasien()" :loading="isLoading"> Cari
                      Data
                    </VButton>
                  </div>
                  <VPlaceholderPage :class="[dataPasien.length !== 0 && 'is-hidden']" title="Tidak Ada Pasien Hari Ini."
                    subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                    <template #image>
                      <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
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
                            <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                            <div class="meta-left">
                              <h3>
                                {{ item.namapasien }}
                                <i :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                  aria-hidden="true"
                                  :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                              </h3>
                              <span>

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
                              </span>
                            </div>
                            <div class="meta-right">
                              <div class="buttons">
                                <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                  @click="emr(item)" v-tooltip.bottom.left="'EMR'">
                                </VIconButton>

                                <VIconButton v-tooltip.bottom.left="'Transaksi Pelayanan'" label="Bottom Left"
                                  color="info" circle icon="pi pi-arrow-right" @click="daftar(item)"
                                  :loading="item.loading" />

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
                    :item-per-page="currentPasienDaftar.limit" :total-items="dataPasien.total" :max-links-displayed="5">
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

    <VModal :open="modalJumlahLabel" title="Jumlah Label Dicetak" :noclose="false" size="small" actions="right"
      @close="modalJumlahLabel = false">
      <template #content>
        <form class="modal-form">
          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VLabel>Jumlah Label</VLabel>
                  <VControl>
                    <VInput type="text" v-model="item.jumlahLabel" class="is-rounded_Z" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:save" @click="cetakLabel3()" :loading="isLoading" color="primary" raised>
          Cetak</VButton>
      </template>
    </VModal>

    <VModal :open="modalDetailOrder" title="Verifikasi Order" noclose size="big" actions="right"
      @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
      <template #content>
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12 p-0">
              <div class="block-header">
                <div class="left column is-3 p-0">
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
                      <h4 class="block-heading">Tgl Order</h4>
                      <p class="block-text">{{ item.tglorder }}</p>
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
                              <!-- <tr>
                                                              <td>Cito</td>
                                                              <td>:</td>
                                                              <td>{{ items.nilaiStatusCito ?'Ya':'' }} </td>
                                                          </tr> -->

                            </table>

                          </div>
                        </div>
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
            <div class="column is-4">
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
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Dokter Verifikator</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.dokterverify" :options="d_Dokter" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Petugas Verifikator</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.petugasverify" :options="d_DokterVerif" optionLabel="label" class="is-rounded"
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
              <VField label="Catatan Klinis">
                <VControl class="mt-3">
                  <VTextarea rows="4" placeholder="Tulis Catatan Klinis..." v-model="item.catatanklinis">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>



        <!-- Data Perawat -->


        <Fieldset legend="Data Radiofarmasis" :toggleable="true" style="display:none !important">
          <div class="columns is-multiline">
            <div class="column is-3" style="text-align: center;">
              <h1 style="font-weight: normal;" class="">No Batch Radionuklida</h1>
            </div>
            <div class="column is-9">
              <VField>
                <VControl>
                  <VInput type="text" v-model="item.nbradionuklida" placeholder="" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3" style="text-align: center;">
              <h1 style="font-weight: normal;" class="">No Batch Radiofarmaka</h1>
            </div>
            <div class="column is-9">
              <VField>
                <VControl>
                  <VInput type="text" v-model="item.nbradiofarmaka" placeholder="" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3" style="text-align: center;">
              <h1 style="font-weight: normal;" class="">Dosis Yang Disiapkan</h1>
            </div>
            <div class="column is-9">
              <VField>
                <VControl>
                  <VInput type="text" v-model="item.dosisdisiapkan" placeholder="" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3" style="text-align: center;">
              <h1 style="font-weight: normal;" class="">Jam Permintaan</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker v-model="item.jampermintaan" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:clock">
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
        </Fieldset>



        <Fieldset legend="Data Perawat" :toggleable="true" style="display:none !important">
          <div class="columns is-multiline">
            <div class="column is-3" style="text-align: center;">
              <h1 style="font-weight: normal;" class="">Dosis Full Syringe Injeksi</h1>
            </div>
            <div class="column is-9">
              <VField>
                <VControl>
                  <VInput type="text" v-model="item.dosissyringe" placeholder="" class="is-rounded" />
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
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
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
              <VField>
                <VControl>
                  <VInput type="text" v-model="item.dosisempty" placeholder="" class="is-rounded" />
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
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
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
                  <VInput type="text" v-model="item.rutesuntik" placeholder="" class="is-rounded" />
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
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
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
              <h1 style="font-weight: normal;" class="">Jenis Pemeriksaan</h1>
            </div>
            <div class="column is-9">
              <VControl>
                <Multiselect v-model="item.jenispemeriksaan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_JenisPemeriksaan" :searchable="true" track-by="label" mode="single" autocomplete="off">
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
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
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
                <Multiselect v-model="item.jenisakuisisi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_JenisAkuisisi" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </VControl>
            </div>
            <div class="column is-3" style="text-align: center;">
              <h1 style="font-weight: normal;" class="">Treatment</h1>
            </div>
            <div class="column is-9">
              <VField>
                <VControl>
                  <VInput type="text" v-model="item.treatment" placeholder="" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3" style="text-align: center;">
              <h1 style="font-weight: normal;" class="">Paparan Radiasi</h1>
            </div>
            <div class="column is-9">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="" v-model="item.paparanradiasi" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>μSv/jam</VButton>
                </VControl>
              </VField>
            </div>
          </div>
        </Fieldset>

      </template>
      <template #action>
        <VButton v-if="isLoadDataSoNorec" icon="feather:printer" @click="cetakBuktiOrder(so_norec)" color="info"
          :loading="isLoadingSave" raised>Cetak</VButton>
        <VButton v-if="isLoadDataSoNorec" icon="feather:save" @click="save()" color="primary" :loading="isLoadingSave"
          raised disabled>Simpan</VButton>
        <VButton v-else icon="feather:save" @click="save()" color="primary" :loading="isLoadingSave" raised>Simpan
        </VButton>
      </template>
    </VModal>

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
import Dropdown from 'primevue/dropdown'
import * as H from '/@src/utils/appHelper'
import FloatingButton from "../emr/float-tambah.vue"
import Badge from 'primevue/badge';
import * as qzService from '/@src/utils/qzTrayService'

useHead({
  title: 'Dashboard Kedokteran Nuklir Invivo - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const NOREC_PD = useRoute().query.nocm as string
const themeColors = useThemeColors()
const dataSource: any = ref([])
const filters = ref('')
const d_Komponen = ref([])
const d_Produk = ref([])
const d_Dokter = ref([])
const d_DokterVerif = ref([])
const d_JenisKelamin = ref([])
const d_GolonganDarah = ref([])
let modalJumlahLabel: any = ref(false)
const d_Ruangan = ref([])
const d_Ruangan1 = ref([
  { label: 'KEDOKTERAN NUKLIR IN-VIVO', value: 331 }
])
const modalHistori = ref(false)
const d_JenisPemeriksaan: any = ref([{ value: 1, label: 'Bone Scan' }, { value: 2, label: 'Brain Scan' }, { value: 3, label: 'Cardiac PYP' }, { value: 4, label: 'Ethambutol' }, { value: 5, label: 'Mibi Oncology' }, { value: 6, label: 'Muga Scan' }, { value: 7, label: 'One Day AC' }, { value: 8, label: 'Orbita' }, { value: 9, label: 'Renal GFR' }, { value: 10, label: 'Renogram' }, { value: 11, label: 'Three Phase Bone' }, { value: 12, label: 'Thyroid Uptake' }, { value: 13, label: 'Thyroid Scan' }, { value: 14, label: 'VQ Perfusi' }, { value: 15, label: 'WB Diagnostic' }, { value: 16, label: 'WB Post Therapy' }, {value: 17, label: 'Parathyroid Scan'}])
const d_JenisAkuisisi: any = ref([{ value: 1, label: 'WBS Static' }, { value: 2, label: 'SPEC-CT 1 Bed' }, { value: 3, label: 'SPEC-CT 2 Bed' }, { value: 4, label: 'SPEC-CT 3 Bed' }, { value: 5, label: 'SPEC-CT Cardiac' }, { value: 6, label: 'Three Phase' }, { value: 7, label: 'Dinamik' }, { value: 8, label: 'Planar' }])

let chartOP: any = ref({
  series: [],
})
const rowGroupMetadata = ref({})
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const currentPageOrder: any = ref({
  limit: 5,
  rows: 50,
})
const currentPasienDaftar: any = ref({
  limit: 3,
  rows: 50,
})
currentPageOrder.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
const currentPenunjang: any = ref({
  limit: 3,
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
const isOrder: any = ref(false)
let so_norec = ref('')
let statusOrder: any = ref([])
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
let dataPasien: any = ref([])
let isLoadingSave: any = ref(false)
let isLoadDataOrder: any = ref(false)
let isLoadDataSoNorec: any = ref(false)
let detailDiagnosa: any = ref(0)
let dataStokObat: any = ref([])
let detailOrderVerify: any = ref(0)
let detailOrderLayanan: any = ref(0)
let dataPenunjang: any = ref([])
let isData: any = ref()
let sourceItemSelect: any = ref([])
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
  waktuPemeriksaan: new Date(),
  // jampermintaan: new Date(),
  // jamsyringe: new Date(),
  // jamempty: new Date(),
  // waktuinjeksi: new Date(),
  waktuakuisisi: new Date(),

})
const order: any = ref(0)
const dataOrder: any = ref(0)
const router = useRouter()
const response1 = await useApi().get(`/dashboard/radiologi/get-dokter-nuklir`)
d_Ruangan.value = response1.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })

      //DEFAULT
// item.value.filterRuangan = 331

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dokterPraktek.value
  }
  return dokterPraktek.value.filter((item: any) => {
    return item.namalengkap.match(new RegExp(filters.value, 'i'))
  })
})

const fetchDataOrder = async (q: any) => {
  statusOrder.value = q
  isLoading.value = true
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  let qnamapasien = ''
  let search = ''
  let ruanganid = '&ruanganid=331'
  if (item.value.filterRuangan) {
    ruanganid = `&ruanganid=${item.value.filterRuangan}`
  }
  let qnocm = ''
  let qnoregistrasi = ''
  let StatusOrder = ''
  item.value.statusOrder = q
  if (order) StatusOrder = '&statusorder=' + q
  if (item.value.qnamapasien) qnamapasien = '&qnamapasien=' + item.value.qnamapasien
  if (item.value.qnocm) qnocm = '&qnocm=' + item.value.qnocm
  if (item.value.qnoregistrasi) qnoregistrasi = '&qnoregistrasi=' + item.value.qnoregistrasi
  if (item.value.search) search = '&search=' + item.value.search
  let limit: any = currentPageOrder.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  await useApi().get('/dashboard/radiologi?' + tglAwal + tglAkhir + StatusOrder + qnamapasien + qnocm + qnoregistrasi + ruanganid + search + `&limit=${limit}&offset=${offset}`).then((response) => {
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

const fetchDetail = async () => {
  dokterPraktek.value = []
  dataStokObat.value = []
  const response = await useApi().get(
    '/dashboard/radiologi/get-detail-rad'
  )
  dokterPraktek.value = response.dokter
  dataStokObat.value = response.produk
}

const fetchPenunjang = async () => {
  let qnama = ''
  let qnocm = ''
  let qsearch = ''
  let qnoregistrasi = ''
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = `&ruanganid=${item.value.filterRuangan}`
  }
  if (item.value.qnama) qnama = `&qnama=${item.value.qnama}`
  if (item.value.qsearch) qsearch = `&qsearch=${item.value.qsearch}`
  if (item.value.qnocm) qnocm = `&qnocm=${item.value.qnocm}`
  if (item.value.qnoregistrasi) qnoregistrasi = `&qnoregistrasi=${item.value.qnoregistrasi}`

  dataPenunjang.value.loading = true
  dataPenunjang.value = []
  let limit: any = currentPenunjang.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  const response = await useApi().get('/dashboard/radiologi/get-penunjang-rad?' + tglAwal + tglAkhir + qnama + qnocm + qnoregistrasi + ruanganid + qsearch + `&limit=${limit}&offset=${offset}`)

  dataPenunjang.value.loading = false
  dataPenunjang.value = response.data
  dataPenunjang.value.total = response.total

}


const getListPelayanan = async (data: any) => {
  const response = await useApi().get(`/dashboard/radiologi/get-pelayanan-nuklir-invivo?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}`)
  d_Produk.value = response.map((e: any) => {
    return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
  })
}

const getListDokter = async () => {
  const response = await useApi().get(`/dashboard/radiologi/get-dokter-nuklir`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_JenisKelamin.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id } })
  d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e.id } })
  d_Dokter.value = response.data.map((e: any) => {
    return { label: `${e.namalengkap}`, value: `${e.id}`, default: e }
  })
  d_DokterVerif.value = response.pegawaiRadiologi.map((e: any) => {
    return { label: e.namalengkap, value: e.id }
  })

  // console.log(d_Ruangan)

        //DEFAULT
  // d_Ruangan.value.forEach((element) => {
  //   console.log(element)
  //   item.value.filterRuangan = element.default.id
  // })
  item.value.filterRuangan = 331
  // console.log(item.value.filterRuangan)
}

const orderVerify = async (e: any) => {
  if(e.objectruangantujuanfk == 331 || e.objectruangantujuanfk == 389) {console.info('skip validation closing pasien');}
  else{await H.statusClosingPasien(e.noregistrasi);}

  detailOrderLayanan.value = []
  modalDetailOrder.value = true
  let data = {
    'idkelas': e.objectkelasfk,
    'idJenisPelayanan': e.jenispelayananfk,
    'idRekanan': e.objectrekananfk
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
  item.value.jeniskelamin = e.jeniskelamin
  item.value.kelompokpasien = e.kelompokpasien
  item.value.idRuanganTujuan = e.objectruangantujuanfk
  item.value.pdNorec = e.pd_norec
  item.value.soNorec = e.so_norec
  item.value.noregistrasi = e.noregistrasi
  item.value.objectpegawaiorderfk = e.objectpegawaiorderfk
  item.value.dokterorder = e.nama_pegawai
  item.value.tglorder = e.tglorder
  item.value.tglregistrasi = e.tglregistrasi
  item.value.kelas = e.objectkelasfk
  item.value.catatanklinis = e.catatanklinis
  item.value.catatan = e.keterangan
  item.value.objectkebangsaanfk = e.objectkebangsaanfk
  getListPelayanan(data)
  isLoadDataOrder.value = true
  isLoadDataSoNorec.value = false
  const getHargaLayanan = await useApi().get(`/dashboard/radiologi/get-order-layanan-rad?strukorderfk=${e.so_norec}&objectkelasfk=${e.objectkelasfk}`)
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

  modalDetailOrderVerify.value = true
  const response = await useApi().get(`/dashboard/radiologi/get-order-verify?norec_so=${e.so_norec}`)
  const diagnosa = await useApi().get(`/dashboard/radiologi?tglAwal=${e.tglorder}&tglAkhir=${e.tglorder}&statusorder=${statusOrder.value}&noorder=${e.noorder}`)
  detailDiagnosa.value = diagnosa.data ? diagnosa.data[0].detailDiagnosa : ''
  response.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  detailOrderVerify.value = response
  // console.log(detailOrderVerify)
}

const save = async () => {

  // console.log('ruangan tujuan',item.value.filterRuangan);
  if(item.value.idRuanganTujuan == 331 || item.value.idRuanganTujuan == 389) {console.info('skip validation closing pasien verification');}
  else{await H.statusClosingPasien(e.noregistrasi);}

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
  if (!item.value.waktuPemeriksaan) {
    useToaster().error('Waktu Pemeriksaan Tidak Boleh Kosong')
    return
  }
  let datas: any = []
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
    'radiograferfk': null,
    'catatanklinis': item.value.catatanklinis,
    'tglpelayanan': H.formatDate(item.value.waktuPemeriksaan, 'YYYY-MM-DD HH:mm:ss'),
    'nobatchradionuklida': item.value.nbradionuklida ? item.value.nbradionuklida : null,
    'nobatchradiofarmaka': item.value.nbradiofarmaka ? item.value.nbradiofarmaka : null,
    'dosisradiofarmasis': item.value.dosisdisiapkan ? item.value.dosisdisiapkan : null,
    'jampermintaan': item.value.jampermintaan ? H.formatDate(item.value.jampermintaan, 'HH:mm:ss') : null,
    'dosisfullsyringe': item.value.dosissyringe ? item.value.dosissyringe : null,
    'jamfullsyringe': item.value.jamsyringe ? H.formatDate(item.value.jamsyringe, 'HH:mm:ss') : null,
    'dosisemptysyringe': item.value.dosisempty ? item.value.dosisempty : null,
    'jamemptysyringe': item.value.jamempty ? H.formatDate(item.value.jamempty, 'HH:mm:ss') : null,
    'rutelokasisuntik': item.value.rutesuntik ? item.value.rutesuntik : null,
    'jaminjeksi': item.value.waktuinjeksi ? H.formatDate(item.value.waktuinjeksi, 'HH:mm:ss') : null,
    'pemeriksaanradiograferfk': item.value.jenispemeriksaan ? item.value.jenispemeriksaan : null,
    'jamakuisisi': item.value.waktuakuisisi ? moment(item.value.waktuakuisisi).format('YYYY-MM-DD HH:mm:ss') : null,
    'jenisakuisisifk': item.value.jenisakuisisi ? item.value.jenisakuisisi : null,
    'treatment': item.value.treatment ? item.value.treatment : null,
    'paparanradiasi': item.value.paparanradiasi ? item.value.paparanradiasi : null,
    'catatanDiagnosis': '-',
    'keteranganradiologi': '-'
  }
  isLoadingSave.value = true
  let objBridg: any = []
  for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
    let response = detailOrderLayanan.value[i]
    let komponenHarga = detailOrderLayanan.value[i].komponenharga
    var objSave = {
      'idProduk': response.prid,
      'hargaLayanan': response.hargasatuan,
      'tglpelayanan': response.tglpelayanan,
      'jumlah': response.qtyproduk,
      'komponenharga': komponenHarga,
      "iddokterverif": item.value.dokterverify.value,
    }
    datas.push(objSave)
    objBridg.push({
      produkfk: response.prid,
      namaproduk: response.namaproduk,
      qtyproduk: response.qtyproduk,
      objectkelasfk: item.value.kelas,
    })

  }
  let itemsave = {
    "details": objBridg,
    "noorder": item.value.noorder,
    "objectkelasfk": item.value.kelas,
    "objectruangantujuanfk": item.value.idRuanganTujuan,
    "objectpegawaiorderfk": item.value.objectpegawaiorderfk,
    "iddokterverif": item.value.dokterverify.value,
    "namadokterverif": item.value.dokterverify.label,
    "catatan_klinis": item.value.catatanklinis ? item.value.catatanklinis : null,
  }
  await useApi().post('/bridging/penunjang/save-bridging-zeta', itemsave).then(async (e: any) => {
    await useApi().post('/dashboard/radiologi/save-order-pelayanan', { 'data': datas, 'parameter': parameter }).then((response: any) => {
      so_norec = response.pelPasien.strukorderfk

      isLoadingSave.value = false
      fetchDataOrder(0)
      reload()
    }).catch((e: any) => {
      useToaster().error('Something Went Wrong')
    })

  }).catch((error) => {
    useToaster().error('Something Went Wrong')
  })

  // so_norec = "ada"
  isLoadDataSoNorec.value = true
  // console.log(so_norec);


}

const cetakBuktiOrder = () => {
  // console.log(so_norec)

  H.printBlade('radiologi/cetakan-hasil-radiologi?noregistrasi=' + item.value.noregistrasi
    + '&so_norec=' + so_norec);
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

const cetakLabel = async (e: any) => {
  let so_norec = e.so_norec;
  H.printBlade(`report/cetak-label?noregistrasi=${so_norec}&dariradiologi=true`)
}
const cetakLabel2 = async (e: any) => {
  let so_norec = e.norec_so;
  H.printBlade(`report/cetak-label?noregistrasi=${so_norec}&dariradiologi=true`)
}

const selectedNoregistrasi = ref(null);

const cetakLabelPrev = (e: any) => {
  selectedNoregistrasi.value = e.noregistrasi;
  console.log(e.noregistrasi)
  modalJumlahLabel.value = true;
};

const cetakLabel3 = async () => {
  if (!selectedNoregistrasi.value) return;

  isLoading.value = true;
  console.log("NOREGISTRASI", selectedNoregistrasi.value);

  await qzService.printData(
    `dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${selectedNoregistrasi.value}`,
    'LABEL PASIEN',
    item.value.jumlahLabel
  );

  isLoading.value = false;
  modalJumlahLabel.value = false; // Tutup modal setelah cetak selesai
};

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
  }
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
  fetchPenunjang()
  fetchPasien()
  chartLayananByRuangan()
}
const fetchPasien = async () => {
  // console.log(item.value.filterRuangan)
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
    , rsearch = ''
  if (item.value.qnama) namapasien = item.value.qnama
  if (item.value.qnoreg) noreg = item.value.qnoreg
  if (item.value.qnocm) nocm = item.value.qnocm
  if (item.value.fStatusAntrian) statuspanggil = item.value.fStatusAntrian
  if (item.value.rsearch) rsearch = item.value.rsearch
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
    + '&rsearch=' + rsearch
    + '&limit=' + limit
    + '&offset=' + offset
  )
  isLoading.value = false
  response.data.sort(compare);
  dataPasien.value = response.data
  dataPasien.value.total = response.total

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

const daftar = (e: any) => {
  // console.log(e.objectruanganlastfk)
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
  await useApi()
    .post(`/radiologi/save-transaksi-rad`, json)
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
  fetchDataOrder(0)
  fetchPenunjang()
  chartLayananByRuangan()
}

const transaksiPelayanan = (e: any) => {
  // console.log(e)
  router.push({
    name: 'module-radiologi-transaksi-radiologi',
    query: {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.norec_pd,
      norec_apd: e.norec_apd,
    },

  })
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
  useApi().post(
    `/dashboard/save-jenkel`, json).then((response: any) => {
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
  useApi().post(
    `/dashboard/save-goldar`, json).then((response: any) => {
      modalGolonganDarah.value = false
      fetchPenunjang()
    })
}

const cetakOrder = async (e: any) => {
  let so_norec = e.so_norec;
  H.printBlade(`report/cetak-order?type=radiologi&noregistrasi=${so_norec}`)
}
const cetakSEP = (e: any) => {
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
    'SEP', 1)
}
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
      H.alert('warn', 'Komponen Tarif tidak ada')
      return
    }
    item.value.hargaLayanan = response.harga.hargasatuan
    item.hargasatuanDef = response.harga.hargasatuan
    item.value.jumlah = 1
    d_Komponen.value = response.komponen

  })
}
watch(currentPenunjang.value, () => {
  fetchPenunjang()
})
// watch( item.value.filterRuangan,(newValue,oldValue)=>{
//   console.log('new value',newValue);

//   if(newValue == null || newValue == undefined ||  newValue == '') item.value.filterRuangan = 331
// })
watch(currentPageOrder.value, () => {
  fetchDataOrder(0)
})
watch(currentPasienDaftar.value, () => {
  fetchPasien()
})
watch(
  () => [
    order.value
  ], () => {
    changeSwitch(order.value)
  }
)
const changeSelected = () => {
  currentPasienDaftar.value.limit = 3
  currentPageOrder.value.limit = 3
  currentPenunjang.value.limit = 3
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

getListDokter()
chartLayananByRuangan()
fetchPenunjang()
fetchDetail()
fetchPasien()
fetchDataOrder(0)
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
</style>
