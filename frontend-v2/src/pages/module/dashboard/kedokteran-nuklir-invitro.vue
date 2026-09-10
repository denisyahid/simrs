<template>
  <div>
    <FloatingButton @click="showPasienLama()" />
    <ConfirmDialog group="positionDialog"></ConfirmDialog>

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

                <div class="header-meta" style="margin-left : -7rem;">
                  <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Dashboard
                    Kedokteran Nuklir In Vitro
                  </h3>
                  <p>
                    Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                  </p>
                  <VControl>
                    <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" class="f-text"
                      placeholder="Filter ruangan" :searchable="true" autocomplete="off"
                      @select="changeRuang(item.filterRuangan)" />
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
              style="z-index: 6;top: 0px;position: relative;left: 13rem;" />
            <Badge :value="dataPenunjang.total" v-if="dataPenunjang.total > 0" severity="danger"
              style="z-index: 6;top: 0px;position: relative;left: 30rem;" />
            <VTabs class="slider-lab" slider selected="Pasien" :tabs="[
              { label: 'Daftar Order', value: 'Pasien' },
              { label: 'Pasien Penunjang', value: 'Penunjang' },
              { label: 'Daftar Pasien Registrasi', value: 'PasienDaftar' },
            ]" style="margin-top: -2rem;" @update:selected="changeSelected($event)">
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
                                              <input type="text" placeholder="No Registrasi/ No CM"
                                                  v-model="item.qnoregistrasi" />
                                          </div>
                                          <div class="search-salary">
                                              <i class="iconify" data-icon="feather:clipboard"></i>
                                              <input type="text" placeholder="NIK/No. BPJS" v-model="item.qnoidentitas" />
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
                  <div class="list-view-item mt-5" v-if="isLoading">
                    <div class="flex-list-inner mb-4">
                      <div class="flex-list-inner mb-4">
                        <VFlexTableCell :column="{ grow: true, media: true }">
                          <VPlaceloadAvatar size="medium" width="20%" />
                          <VPlaceloadText :lines="2" width="10%" last-line-width="40%" class="mx-2" />
                        </VFlexTableCell>
                        <VFlexTableCell>
                          <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                        </VFlexTableCell>
                        <VFlexTableCell class="mt-3">
                          <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                          <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                          <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                          <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                        </VFlexTableCell>
                        <VFlexTableCell :column="{ align: 'end' }">
                          <VPlaceload width="20%" height="20px" class="mx-1" />
                        </VFlexTableCell>
                      </div>
                    </div>
                  </div>
                  <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6"
                    v-else-if="dataOrderFiltered.length == 0">
                    <template #image>
                      <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                    </template>
                  </VPlaceholderPage>
                  <div class="list-view-inner" style="max-height:500px;overflow: auto; margin-top: 1rem; " v-else>
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
                              <span>{{ items.tgllahir }}</span>
                              <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                              <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
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
                            <VTag :label="'SEP : ' + items.nosep" v-if="items.nosep != null" :color="'success'"
                              class='ml-2' />
                          </div>
                          <div class="meta-right">

                            <VIconButton v-tooltip.bottom.left="'Verifikasi'" label="Bottom Left" color="success" circle
                              icon="pi pi-check-circle" v-if="items.statusorder == null || items.statusorder == 0"
                              @click="orderVerify(items)" :loading="item.loading" style="margin-right: 15px;" />


                            <!-- <VButton color="primary" raised style="margin-top: 11px;"
                                                          v-if="items.statusorder == 0" @click="orderVerify(items)">
                                                          Verifikasi <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                      </VButton> -->
                            <VIconButton v-tooltip.bottom.left="'Detail Order'" label="Bottom Left" color="primary"
                              circle icon="pi pi-book" v-else-if="items.statusorder == 1"
                              @click="getDetailVerify(items)" style="margin-right: 15px;" />


                            <!-- <VButton color="primary" raised style="margin-top: 11px;"
                                                          v-else-if="items.statusorder == 1" @click="getDetailVerify(items)">
                                                          Detail
                                                      </VButton> -->

                            <!-- <VIconButton v-tooltip.bottom.left="'Cetak Order'" label="Bottom Left"
                                                          color="warning" circle icon="pi pi-print" @click="cetakOrder(items)"
                                                          :loading="item.loading"   style="margin-right: 15px;"  /> -->


                            <!-- <VButton color="primary" raised style="margin-top: 11px;"
                                                          @click="cetakOrder(item)">
                                                          Detail
                                                      </VButton> -->
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
                                <option :value="data" v-for="(data, index) in H.pagination().typeSmall" :key="index">{{
                                  data }} results per page</option>
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
                      v-model="item.qsearch" v-on:keyup.enter="fetchPenunjang()" />
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
                    <div class="user-grid user-grid-v2" style="max-height:1000px;overflow: auto;">
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

                        <div v-for="(item, index) in dataPenunjang" :key="index" class="column is-4">
                          <div class="grid-item-wrap is-clickable">
                            <!-- @click="clickCard(item)" -->
                            <div :class="'grid-item-head ' + (
                              item.norec_apd != null ? 'is-registrasi' : ''
                            )">
                              <div class="flex-head">

                                <div class="meta">
                                  <span v-if="item.norec_apd != null" class="dark-inverted"
                                    v-tooltip-prime.top="item.namaruangan">
                                    {{ item.namaruangan }}
                                  </span>

                                  <span>
                                    {{
                                      H.formatDateIndoSimple(item.tglmasuk)
                                    }}
                                  </span>
                                </div>

                              </div>

                            </div>
                            <div class="flex-head" style=" display: flex; justify-content: space-between;">
                              <VTag v-if="item.kelompokpasien != null" class="mt-2 ml-2" :label="item.kelompokpasien"
                                :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'" rounded />
                              <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bottom.left="'AKSI'">

                                <template #content>
                                  <a role="menuitem" @click="PengkajianMedis(item)" class="dropdown-item is-media">
                                    <div class="icon">
                                      <i aria-hidden="true" class="lnil lnil-medical-sign"></i>
                                    </div>
                                    <div class="meta">
                                      <span>Pengkajian Medis</span>
                                    </div>
                                  </a>
                                  <a role="menuitem" @click="cetakLabelPrev(item)" class="dropdown-item is-media">
                                  <!-- <pre>{{ item }}</pre> -->
                                    <div class="icon">
                                      <i aria-hidden="true" class="lnil lnil-print"></i>
                                    </div>
                                    <div class="meta">
                                      <span>Cetak Label</span>
                                    </div>
                                  </a>
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
                              <h3 class="dark-inverted">{{ item.namapasien }}</h3>
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

                              <VTag :label="item.status" color="warning" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Belum Bayar'" />
                              <VTag :label="item.status" color="success" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Lunas'" />
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
                                      <option :value="data" v-for="(data, index) in H.pagination().typeGenaral"
                                        :key="index">{{ data }} results per page</option>
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
                <p v-else-if="activeValue == 'PasienDaftar'">
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
                                <!-- <i aria-hidden="true" class="iconify" data-icon="feather:credit-card"></i>
                                  <span>{{ item.nocm }}</span> -->
                                <!-- <i aria-hidden="true" class="fas fa-circle icon-separator"></i> -->
                                <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                <span>{{ item.tglregistrasi }}</span>

                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                <span>{{ item.noregistrasi }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                <span>{{ item.nocm }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                <span>{{ item.namaruangan }}</span><br>
                                <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                <span>{{ item.umur }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
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
                                <option :value="data" v-for="(data, index) in H.pagination().typeMedium" :key="index">{{
                                  data }} results per page</option>
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
          <UIWidget class="search-widget" style="margin-top: 1.7rem;">

            <template #body>
              <div class="field">
                <div class="control">
                  <input v-model="filters" class="input custom-text-filter" placeholder="Cari Dokter Praktek"
                    @click="fetchDetail()" />
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
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>

            <!--Tile Grid v1-->

            <div name="list" tag="div" class="columns is-multiline">
              <!--Grid item-->
              <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                <div v-for="item in dokterPraktek" :key="item.id" class="column is-12 p-0 pb-2 pl-2 pr-2 ">
                  <div class="tile-grid-item" style="">
                    <div class="tile-grid-item-inner">
                      <VAvatar size="small" picture="/images/avatars/svg/dokter.svg" color="primary" bordered />
                      <div class="meta">
                        <span class="dark-inverted text-elipsis-wrap" style="width:200px !important">{{
                          item.namalengkap
                          }}</span>
                        <span>
                          <i aria-hidden="true" class="iconify" data-icon="feather:clock"
                            style="padding-right: 3px;"></i>
                          {{ item.jammulai }} s.d {{ item.jamakhir }}</span>
                      </div>
                      <VTag
                        style="margin-left: auto;white-space: pre-line;display: block;height:auto;padding:5px 10px;text-align:center;justify-content :center;"
                        color="info" label="Tag Label" rounded elevated>
                        {{ item.hari }}
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
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>

            <!--Tile Grid v1-->

            <div name="list" tag="div" class="columns is-multiline">
              <!--Grid item-->
              <div class="columns is-multiline p-2" style="max-height:300px;overflow: auto;">
                <div v-for="item in dataStokObat" :key="item.id" class="column">
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
            </div>
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
                  <!-- <div class="column is-4">
                                      <VField class="is-autocomplete-select">
                                          <VLabel>Layanan</VLabel>
                                          <VControl icon="feather:search">
                                              <Multiselect mode="single" v-model="item.layanan" placeholder="Pilih Layanan"
                                                  :searchable="true" :options="d_Produk" @select="getHarga(item)" />
                                          </VControl>
                                      </VField>
                                  </div>
                                  <div class="column is-2">
                                      <VField label="Harga">
                                          <VControl icon="fas fa-money-bill-wave">
                                              <VInput type="text" placeholder="Harga" v-model="item.hargaLayanan" readonly
                                                  class="is-rounded" />
                                          </VControl>
                                      </VField>
                                  </div>
                                  <div class="column is-2">
                                      <VField label="Jumlah">
                                          <VInput type="text" v-model="item.jumlah" placeholder="Jumlah" class="is-rounded" />
                                      </VField>
                                  </div>
                                  <div class="column is-1">
                                      <VField class="mt-5">
                                          <VControl class="is-flex">
                                              <VLabel raw class="remember-toggle">
                                                  <VInput raw type="checkbox" v-model="item.iscito"
                                                      :checked="item.iscito == 'true' || item.iscito == true ? true : false"
                                                      @change="handleChange($event.target.checked)" />

                                                  <span class="toggler">
                                                      <span class="active">
                                                          <i aria-hidden="true" class="iconify" data-icon="feather:check"></i>
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
                                  </div> -->

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
                  <div class="column is-2">
                    <VField class="mt-5">
                      <VControl class="is-flex">
                        <VLabel raw class="remember-toggle">
                          <VInput raw type="checkbox" v-model="item.iscito"
                            :checked="item.iscito == 'true' || item.iscito == true ? true : false"
                            @change="handleChange($event.target.checked)" />

                          <span class="toggler">
                            <span class="active">
                              <i aria-hidden="true" class="iconify" data-icon="feather:check"></i>
                            </span>
                            <span class="inactive">
                              <i aria-hidden="true" class="iconify" data-icon="feather:circle"></i>
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
                      v-if="item.no && d_Komponen.length" @click="update(item)" color="warning" raised circle
                      class="mr-2">
                    </VIconButton>
                    <VIconButton v-tooltip.bottom.right="'Tambah Data'" icon="fas fa-plus"
                      v-else-if="!item.no && d_Komponen.length" @click="add(), clear()" color="info" raised circle>
                    </VIconButton>
                    <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash" @click="clear()" color="danger"
                      raised circle style="margin-left:10px">
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
                        <div class="box-text" style="width: 80%">
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
                    style="cursor:pointer; text-align: center; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Dokter Verifikator" class="is-rounded-select is-autocomplete-select">
                <VControl icon="fa:user-md" class="prime-auto-cus">
                  <AutoComplete v-model="item.dokterVerif" :suggestions="d_Dokter" :optionLabel="'label'"
                    @complete="fetchDokter($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter..." class="mt-2 is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Petugas Verifikator" class="is-rounded-select is-autocomplete-select">
                <VControl icon="fa:user" class="prime-auto-cus ">
                  <AutoComplete v-model="item.pegawaiVerif" :suggestions="d_Pegawai" :optionLabel="'label'"
                    @complete="fetchPegawai($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Petugas..." class="mt-2  is-rounded" />
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

        <!-- <div class="columns is-multiline" style="padding: 2rem 6rem;">
                  <div class="column is-6">
                      <div class="column is-12">
                          <VField label="Dokter Order">
                              <VControl class="mt-2">
                                  <VInput type="text" placeholder="Dokter Order" readonly class="is-rounded"
                                      v-model="item.dokterorder"
                                      style="cursor:pointer text-align: center;background: var(--fade-grey);" />
                              </VControl>
                          </VField>
                      </div>
                      <div class="column is-12">
                          <VField label="Catatan">
                              <VControl class="mt-2">
                                  <VTextarea rows="4" placeholder="Tulis Catatan..." v-model="item.catatan"></VTextarea>
                              </VControl>
                          </VField>
                      </div>
                  </div>

                  <div class="column is-6">
                      <div class="column is-12">
                          <VField class="is-rounded-select is-autocomplete-select">
                              <VLabel class="required-field">Dokter Verifikator</VLabel>
                              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                  <Dropdown v-model="item.objectpegawaifk" :options="d_Dokter" optionLabel="label"
                                      class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                              </VControl>
                          </VField>
                      </div>
                      <div class="column is-12">
                          <VField label="Catatan Klinis">
                              <VControl class="mt-3">
                                  <VTextarea rows="4" placeholder="Tulis Catatan Klinis..." v-model="item.catatanklinis">
                                  </VTextarea>
                              </VControl>
                          </VField>
                      </div>

                  </div>
              </div> -->

      </template>

      <template #action>
        <VButton v-if="item.namaFILE != null" color="info" raised icon="fas fa-file" class="mr-2" @click="lihatDok()">
          Lihat File
        </VButton>
        <VButton v-if="isLoadDataNoRec" icon="feather:printer" @click="cetakBuktiOrder(so_norec)" color="info"
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
          <div class="timeline-wrapper">
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
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import FloatingButton from "../emr/float-tambah.vue"
import Badge from 'primevue/badge';
import * as qzService from '/@src/utils/qzTrayService'


useHead({
  title: 'Dashboard Kedokteran Nuklir Invitro - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)


const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()

const NOREC_PD = useRoute().query.nocm as string
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser

const dataSource: any = ref([])
const filters = ref('')
const d_Dokter = ref([])
const d_Komponen = ref([])
const d_Pegawai = ref([])
const d_Ruangan = ref([])
const d_JenisKelamin = ref([])
const d_GolonganDarah = ref([])
const d_Produk = ref([])
const rowGroupMetadata = ref({})

const currentPageOrder: any = ref({
  limit: 5,
  rows: 50,
})
const currentPenunjang: any = ref({
  limit: 6,
  rows: 50,
})
const currentPasienDaftar: any = ref({
  limit: 10,
  rows: 50,
})
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
let modalGolonganDarah: any = ref(false)
let modalJenisKelamin: any = ref(false)
let modalTransaksi: any = ref(false)
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

const response1 = await useApi().get(`/dashboard/list-lab`)
d_Ruangan.value = response1.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })

d_Ruangan.value.forEach((element) => {
  console.log(element)
  item.value.filterRuangan = element.default.id
})


// const fetchDataOrder = async (q: any) => {
//     let ruanganid = ''
//     if (item.value.filterRuangan) {
//         ruanganid = `&ruanganid=${item.value.filterRuangan}`
//     }
//     let tglAwal = '&tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
//     let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
//     let qnamapasien = ''
//     if (item.value.qnamapasien) {
//         qnamapasien = `&qnamapasien=${item.value.qnamapasien}`
//     }
//     let search = item.value.search ? '&search=' + item.value.search : ''
//     let qnocm = ''
//     let qnoregistrasi = ''
//     let qnoidentitas = ''
//     let StatusOrder = ''
//     item.value.statusOrder = q
//     if (order) StatusOrder = '&statusorder=' + q
//     if (item.value.qnocm) qnocm = '&qnocm=' + item.value.qnocm
//     if (item.value.qnoregistrasi) qnoregistrasi = '&qnoregistrasi=' + item.value.qnoregistrasi
//     isLoading.value = true
//     await useApi().get('/dashboard/so-lab?ruanganid=' + ruanganid + '&search=' + search + '&tglAwal=' + tglAwal + '&tglAkhir=' + tglAkhir + '&qnamapasien=' + qnamapasien + '&statusorder=' + StatusOrder + '&qnocm=' + qnocm + '&qnoregistrasi=' + qnoregistrasi).then((response) => {
//         modalFilter.value = false
//         response.forEach((element: any, i: any) => {
//             element.no = i + 1
//             let ini = element.namapasien.split(' ')
//             let init = element.namapasien.substr(0, 2)
//             if (ini.length > 1) {
//                 init = init + ini[1].substr(0, 1)
//             }
//             element.initials = init
//             element.ruanganasal = (element.asal_ruangan.length > 14) ? element.ruanganasal = element.asal_ruangan.substring(0, 14) + '...' : element.ruanganasal = element.asal_ruangan
//             element.tglRegistrasi = moment(element.tglregistrasi).format('YYYY-MM-DD')
//         });
//         isData.value = response.length
//         dataOrder.value = response
//         isLoading.value = false
//     }).catch((err) => {
//         modalFilter.value = false
//     })
// }

const fetchDataOrder = async (q: any) => {
  try {
    let ruanganid = item.value.filterRuangan ? `ruanganid=${item.value.filterRuangan}` : '';
    let tglAwal = `&tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`;
    let tglAkhir = `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`;
    let qnamapasien = item.value.qnamapasien ? `&qnamapasien=${item.value.qnamapasien}` : '';
    let search = item.value.search ? `&search=${item.value.search}` : '';
    let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
    let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
    let qnoidentitas = ''; // Jika diperlukan tambahkan logika untuk qnoidentitas
    let statusOrder = '';
    let limit: any = currentPageOrder.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    if(order) statusOrder='&statusorder='+q

    isLoading.value = true;

    const response = await useApi().get(`/dashboard/so-lab?${ruanganid}${search}${tglAwal}${tglAkhir}${qnamapasien}${statusOrder}${qnocm}${qnoregistrasi}&limit=${limit}&offset=${offset}`);

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
    dataOrder.value.total = response.count;
    route.query.page = '1'
  } catch (err) {
    // Tangani kesalahan jika diperlukan
  } finally {
    isLoading.value = false;
  }
};


// const fetchPenunjang = async () => {
//     let ruanganid = ''
//     if (item.value.filterRuangan) {
//         ruanganid = item.value.filterRuangan
//     }
//     let qnama = ''
//     let qnocm = ''
//     let qnoregistrasi = ''
//     let tglAwal = '&tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
//     let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
//     let search = ''

//     if (item.value.qnama) qnama = `&qnama=${item.value.qnama}`
//     if (item.value.qnocm) qnocm = `&qnocm=${item.value.qnocm}`
//     if (item.value.qnoregistrasi) qnoregistrasi = `&qnoregistrasi=${item.value.qnoregistrasi}`
//     if (item.value.qsearch) search = `&qsearch=${item.value.qsearch}`

//     dataPenunjang.value.loading = true
//     dataPenunjang.value = []
//     const response = await useApi().get('/dashboard/penunjang-lab?ruanganid=' + ruanganid + '&tglAwal=' + tglAwal + '&tglAkhir=' + tglAkhir + '&qnama=' + qnama + '&qnocm=' + qnocm + '&qnoregistrasi=' + qnoregistrasi + '&qsearch=' + qsearch)
//     dataPenunjang.value.loading = false
//     dataPenunjang.value = response.data
// }
const orderBarang = () => {
  router.push({
    name: 'module-logistik-order-barang'
  })
}

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

    const response = await useApi().get(`/dashboard/penunjang-lab?ruanganid=${ruanganid}${tglAwal}${tglAkhir}${qnama}${qnocm}${qnoregistrasi}${search}&limit=${limit}&offset=${offset}`);

    dataPenunjang.value.loading = false;
    dataPenunjang.value = response.data;
    dataPenunjang.value.total = response.total
    route.query.page = '1'
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
  const response = await useApi().get(`/laboratorium/list-tindakan-for-order?kelasfk=${data.idkelas}&ruanganfk=338&idkebangsaan=${data.objectkebangsaanfk}`)
  d_Produk.value = response.list_tindakan[0].details.map((e: any) => {
    return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
  })
}

// const getKomponenHarga = async (param: any) => {
//     let datas = []
//     await useApi().get('/dashboard/get-komponen-lab?idProduk=' + param.layanan + '&idKelas=' + param.kelas + '&idJenLayan=' + param.idJenisPelayanan)
//         .then((response) => {
//             response.forEach((items: any) => {
//                 datas.push(items)
//             })
//         })
// }

const orderVerify = async (e: any) => {
  await H.statusClosingPasien(e.noregistrasi);
  detailOrderLayanan.value = []
  modalDetailOrder.value = true
  let data = {
    'idkelas': e.objectkelasfk,
    'idruangan': e.objectruangantujuanfk,
    'idJenisPelayanan': e.jenispelayananfk,
    'idRekanan': e.objectrekananfk,
    'objectkebangsaanfk': e.objectkebangsaanfk,
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
  item.value.objectkebangsaanfk = e.objectkebangsaanfk
  // item.value.objectpegawaifk = e.idPegawai
  item.value.dokterorder = e.nama_pegawai
  item.value.catatanklinis = e.catatanklinis
  item.value.catatan = e.keteranganlainnya
  item.value.tglregistrasi = e.tglregistrasi
  item.value.kelas = e.objectkelasfk
  item.value.pegawaiVerif = userLogin.pegawai.namalengkap
  item.value.namaFILE = e.namafile
  getListPelayanan(data)
  isLoadDataOrder.value = true
  const getHargaLayanan = await useApi().get(`/dashboard/get-order?strukorderfk=${e.so_norec}`)
  const response = await useApi().get(`/dashboard/so-lab?statusorder=${statusOrder.value}&noorder=${e.noorder}`)
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
  await H.statusClosingPasien(item.value.noregistrasi);
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

  let jsonVansLab = {
    'noorder': item.value.noorder,
    'iddokterverif': item.value.dokterVerif.value,
    'namadokterverif': item.value.dokterVerif.label,
    'details': datas,
    'catatan_klinis': item.value.catatanklinis,
    'noregistrasi': item.value.noregistrasi,
    'objectkelasfk': item.value.kelas,
  }

  isLoading.value = true
   useApi().post('/dashboard/save-order-pelayanan-lab', { 'data': datas, 'parameter': parameter, 'noorder': item.value.noorder }).then((response: any) => {
      isLoading.value = false
      so_norec = response.pelPasien.strukorderfk
      // modalDetailOrder.value = false
      fetchDataOrder(0)
      reload()
    }).catch((error) => {
      useToaster().error('Something Went Wrong')
      isLoading.value = false
    })
  // await useApi().postNoMessage('/bridging/penunjang/save-bridging-vans-lab', jsonVansLab).then(async (response: any) => {
    
  // }).catch((error) => {
  //   useToaster().error('Something Went Wrong')
  //   isLoading.value = false
  // })
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


const changeSwitch = (e: any) => {
  fetchDataOrder(e)
}

const changePeriode = () => {
  fetchDataOrder(0)
  chartLayananByRuangan()
  fetchPenunjang()
}

const getHarga = async (e: any) => {
  const response = await useApi().get(`/dashboard/get-pelayanan-lab?idkelas=${item.value.kelas}&idjenispelayanan=${item.value.idJenisPelayanan}&idruangan=${item.value.idRuanganTujuan}`)
  item.value.hargaLayanan = response[0].hargasatuan
  item.value.namaproduk = response[0].namaproduk

  console.log(item.value.hargaLayanan)
}

const panggilPasien = async (e: any) => {
  e.loading = true
  sendAntrol(e.norec_pd)
  await socket.emit('call-antrian-farmasi', {
    namapasien: e.namapasien,
    namaruangan: e.namaruangan,
    noantri: e.noantri,
    nocm: e.nocm,
    noregistrasi: e.noregistrasi,
    jenis: e.jenis,
    status: 'panggil'
  });
  await sleep(1000)
  e.loading = false
}

const sendAntrol = async (norec_pd) => {
  const jsont7 = {
    "noregistrasifk": norec_pd,
    "taskid": 7,
    "waktu": new Date().getTime(),
  }
  await useApi()
    .postNoMessage(`/bridging/antrol/sendTaskId`, jsont7)
    .then((response: any) => {
    })
}

// const getHarga = async ()=> {
//     await useApi().get(`/dashboard/get-pelayanan-lab?idkelas=${item.value.kelas}&idjenispelayanan=${item.value.idJenisPelayanan}&idruangan=${ item.value.idRuanganTujuan}`).then((response: any) => {
//         response.forEach((element: any, i: any) => {
//             element.no = i + 1
//         })
//         item.value.hargaLayanan = response[0].hargasatuan

//     })
// }

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
  const response = await useApi().get(`/dashboard/list-lab`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_JenisKelamin.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id } })
  d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e.id } })
  // d_Dokter.value = response.namalengkap.map((e: any) => { return { label: e.namalengkap, value: e.id } })
  d_Dokter.value = response.namalengkap.map((e: any) => {
    return { label: e.namalengkap, value: e.id }
  })

  d_Ruangan.value.forEach((element) => {
    console.log(element)
    item.value.filterRuangan = element.default.id
  })
  console.log(item.value.filterRuangan)
}

const transaksiPelayanan = (e: any) => {
  router.push({
    name: 'module-laboratorium-transaksi',
    query: {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.norec_pd,
      norec_apd: e.norec_apd
    },

  })
}

const PengkajianMedis = (e: any) => {
  // H.checkAksesEMR(e, kelompokUser, kelompokUserID, pegawaiId)
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

  H.printBlade('laboratorium/layanan-lab-pertindakan?noregistrasi=' + item.value.noregistrasi
    + '&so_norec=' + so_norec);
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
  chartLayananByRuangan()

}
const emr = (e: any) => {
  // H.checkAksesEMR(e, kelompokUser, kelompokUserID, pegawaiId)
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

const chartLayananByRuangan = async () => {
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  await useApi().get(`/dashboard/chart-lab-ruangan?${tglAwal}${tglAkhir}`).then((res: any) => {
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
    '/laboratorium/get-regis-pasien?ruanganid=' + ruanganid
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

const goTo = (e: any) => {

  let bindData = sourceItemSelect.value
  router.push({
    name: 'module-laboratorium-transaksi',
    query: {
      nocmfk: bindData.nocmfk,
      norec_pasien_daftar: bindData.norec_pd,
      norec_apd: bindData.norec_apd
    },

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
    `/dashboard/radiologi/get-pegawai-invitro`
  ).then((response) => {
    d_Pegawai.value = response.map((e: any) => {
      return { label: e.namalengkap, value: e.id }
    })
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/custom/get-user-ruangan?ruangan=268&query=${filter.query}&jenis=DOKTER`).then((response) => {
    d_Dokter.value = response.map((e: any) => {
    return { label: e.namalengkap, value: e.id }
  })
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
    + '&idKelas=' + item.value.kelas
    + '&objectkebangsaanfk=' + item.value.objectkebangsaanfk
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
watch(currentPageOrder.value, () => {
  fetchDataOrder(0)
})
watch(currentPenunjang.value, () => {
  fetchPenunjang()
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
const changeSelected = (key: any) => {
  const newParams = { page: 1 };
  if (key == "Pasien") {
    currentPageOrder.value.limit = 5
    currentPageOrder.value.page = computed(() => {
      try {
        return Number.parseInt(route.query.page as string) || 1
      } catch { }
      return 1
    })
  }
  if (key == "Penunjang") {
    currentPenunjang.value.limit = 6
    currentPenunjang.value.page = computed(() => {
      try {
        return Number.parseInt(route.query.page as string) || 1
      } catch { }
      return 1
    })
  }
  if (key == "PasienDaftar") {
    currentPasienDaftar.value.limit = 10
    currentPasienDaftar.value.page = computed(() => {
      try {
        return Number.parseInt(route.query.page as string) || 1
      } catch { }
      return 1
    })
  }
  router.push({ query: { ...router.query, ...newParams } })
}
const cetakLabel = async (e: any) => {
  let so_norec = e.so_norec;
  H.printBlade(`report/cetak-label?noregistrasi=${so_norec}&dariradiologi=true`)
}

const cetakLabel2 = async (e: any) => {
  let so_norec = e.norec_so;
  H.printBlade(`report/cetak-label?noregistrasi=${so_norec}&dariradiologi=true`)
}
let modalJumlahLabel: any = ref(false)
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
fetchDataOrder(0)
fetchDetail()
chartLayananByRuangan()
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
}
</style>
