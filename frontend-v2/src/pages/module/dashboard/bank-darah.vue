<template>
  <div>
    <FloatingButton @click="showPasienLama()" />
    <ConfirmDialog group="positionDialog"></ConfirmDialog>
    <div class="business-dashboard hr-dashboard">
      <div class="business-dashboard hr-dashboard">
        <div class="columns">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="illustration-header-2 large-screen">
                  <div class="header-image">
                    <img src="/@src/assets/illustrations/dashboards/lifestyle/daslab.png" alt=""
                      style="max-width:75%; margin-left: 2rem; margin-top: 0.5rem;" />
                  </div>

                  <div class="header-meta" style="margin-left : -7rem;">
                    <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Pelayanan
                      Bank Darah
                    </h3>
                    <p>
                      Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                    </p>

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
                { label: 'Daftar Pasien Penunjang', value: 'Penunjang' },
                { label: 'Daftar Pasien Registrasi', value: 'PasienDaftar' },
              ]" style="margin-top: -2rem;">
                <template #tab="{ activeValue }">
                  <p v-if="activeValue === 'Pasien'">
                  <div class="list-view list-view-v3">

                    <Vcard>
                      <div class="search-menu mb-2">
                        <div class="search-location" style="width: 100%">
                          <i class="iconify" data-icon="feather:search"></i>
                          <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                            v-model="item.qsearch" v-on:keyup.enter="fetchDataOrder()" />
                        </div>
                        <VButton raised class="search-button" @click="fetchDataOrder()" :loading="isLoading"> Cari
                          Data
                        </VButton>
                      </div>
                    </Vcard>
                    <VCard class="text-center pt-0 pb-0 mt-0">
                      <VRadio v-model="item.statusOrder" value="0" label="Pending" name="outlined_radio"
                        color="warning" />
                      <VRadio v-model="item.statusOrder" value="1" label="Verifikasi" name="outlined_radio"
                        color="info" />
                      <VRadio v-model="item.statusOrder" value="2" label="Selesai Pelayanan" name="outlined_radio"
                        color="primary" />
                    </VCard>

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
                                <VTag v-if="items.kelompokpasien != null" class="mt-3 ml-2"
                                  :label="items.kelompokpasien"
                                  :color="items.kelompokpasien == 'BPJS' ? 'green' : 'orange'" rounded /> |
                                <i class="bulet fas fa-circle"></i>
                                {{ items.namakelas }}
                              </h3>
                              <span>
                                <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                <span>{{ items.asal_ruangan }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                <span>{{ items.tglregistrasi }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                <span>{{ items.noorder }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                <span>{{ items.nocm }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                <span>{{ items.umur }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                <span>{{ item.nobpjs }}</span>
                                <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                <span>{{ items.noidentitas }}</span>

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
                            </div>
                            <div class="meta-right">
                              <VIconButton v-if="items.statusorder == 0" v-tooltip.bottom="'Verifikasi'" color="primary"
                                circle icon="pi pi-arrow-right" @click="orderVerify(items)" :loading="items.loading"
                                style="margin-right: 15px;" />
                              <VIconButton v-if="items.statusorder == 1" v-tooltip.bottom="'Terima Darah'"
                                color="danger" circle icon="fas fa-hand-holding-water" @click="ambilDarah(items)"
                                outlined raised :loading="items.loading" style="margin-right: 15px;" />
                              <VIconButton v-if="items.statusorder != 0" v-tooltip.bottom="'Detail'" color="blue" circle
                                icon="fas fa-file-medical-alt" @click="getDetailVerify(items)" :loading="items.loading"
                                style="margin-right: 15px;" />

                              <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                @click="emr(items)" v-tooltip.bottom="'EMR'" style="margin-right: 15px;">
                              </VIconButton>
                            </div>
                          </div>
                        </div>
                      </TransitionGroup>
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
                    </div>
                  </div>
                  </p>
                  <p v-else-if="activeValue == 'PasienDaftar'">
                  <div class="list-view list-view-v3">
                    <div class="search-menu mb-2">
                      <div class="search-location" style="width: 100%">
                        <i class="iconify" data-icon="feather:search"></i>
                        <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
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
                              <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                              <div class="meta-left">
                                <h3>
                                  {{ item.namapasien }} | {{ item.noregistrasi }} | {{
                                  item.nocm }} | <i
                                    :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                    aria-hidden="true"
                                    :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                </h3>
                                <span>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                  <span>Tgl Regis : {{ item.tglregistrasi }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>

                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                  <span>{{ item.namaruangan }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>

                                  <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                  <span>Tgl Meninggal : {{ item.tglmeninggal }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                  <span>Umur : {{ item.umur }}</span>
                                </span>
                              </div>
                              <div class="meta-right">
                                <div class="buttons">

                                  <VIconButton color="primary" circle icon="fas fa-stethoscope" raised
                                    @click="emr(item)" v-tooltip.bottom.left="'EMR'">
                                  </VIconButton>

                                  <VIconButton v-tooltip.bottom.left="'Transaksi Pelayanan'" label="Bottom Left"
                                    color="info" circle icon="pi pi-arrow-right" @click="transaksiPelayanan(item)"
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
                                <option :value="5">5 results per page</option>
                                <option :value="10">10 results per page</option>
                                <option :value="15">15 results per page</option>
                                <option :value="20">20 results per page</option>
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
                  <p v-else-if="activeValue == 'Penunjang'">
                  <div class="search-menu mb-2">
                    <div class="search-location" style="width: 100%">
                      <i class="iconify" data-icon="feather:search"></i>
                      <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                        v-model="item.qsearch" v-on:keyup.enter="fetchPenunjang()" />
                    </div>
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
                                <!-- <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bottom.left="'AKSI'">
                                  <template #content>
                                    <a role="menuitem" @click="PengkajianMedis(item)" class="dropdown-item is-media">
                                      <div class="icon">
                                        <i aria-hidden="true" class="lnil lnil-medical-sign"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Pengkajian Medis</span>
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
                                </VDropdown> -->
                              </div>
                              <div class="grid-item">
                                <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')"
                                  :badge="(item.objectjeniskelaminfk == '1' ? '/images/other/male.png'
                                  : '/images/other/female.png')" size="big" />
                                <h3 class="dark-inverted">{{ item.namapasien }}</h3>
                                <p>No REG : {{ item.noregistrasi }}</p>
                                <p>No RM : {{ item.nocm }}</p>
                                <p>No BPJS : {{ item.nobpjs }}</p>
                                <p>NIK : {{ item.noidentitas }}</p>

                                <div class="buttons mt-4">
                                  <VIconButton v-tooltip.bottom.left="'Rincian'" label="Bottom Left" color="info"
                                    outlined circle icon="pi pi-arrow-right" @click="transaksiPelayanan(item)"
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
                </template>
              </VTabs>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal filter date -->
  <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks :max-date="new Date()" />
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
  <!-- end modal filter date -->
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
                    }}</p>
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
                    <p style="font-weight: normal;font-size: 12px;color: white;">{{ item.ruangantujuan
                      }}</p>
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
                    <p style="font-weight: normal;font-size: 12px;color: white;" v-for="(data, i) in detailDiagnosa"
                      :key="i">
                      &#9679; {{ data.kddiagnosa }} - {{ data.namadiagnosa }}</p>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>
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
                  <VField label="Pelayanan" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" class="prime-auto-select" fullwidth>
                      <Dropdown v-model="item.produk" :options="d_Produk" :optionLabel="'label'"
                        placeholder="Pilih data" style="width: 100%;" class="is-rounded" showClear appendTo="body"
                        :filter="true" @change="changeTindakan(item.produk)" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Harga">
                    <VLabel class="mt-4">{{
                      H.formatRp((item.hargaLayanan ? item.hargaLayanan : 0),
                      'Rp.')
                      }}</VLabel>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField label="Jumlah">
                    <VInput type="text" v-model="item.jumlah" placeholder="Jumlah" class="is-rounded" />
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

      <div class="column is-11">
        <div class="timeline-wrapper" v-if="detailOrderLayanan.length > 0">
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
                          <span>{{ items.namaproduk + '&nbsp' + '&nbsp' + '[' + items.qtyproduk +
                            ']' }}</span>
                        </p>
                        <table style="width: 100%; margin-top: 10px">
                          <tr>
                            <td class="font-labels" width="50%">Harga</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="50%">{{ H.formatRp(items.hargasatuan,
                              'Rp. ') }}</td>
                          </tr>
                          <tr>
                            <td class="font-labels" width="50%">Ruangan Tujuan</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="60%">
                              {{ items.ruangantujuan }}
                            </td>
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
                          <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash" @click="hapusItems(items)"
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
      </div>

      <div class="column is-12" v-for="(item, index) in listItem" :key="index">
        <VCard class="is-grey">
          <div class="columns is-multiline p-1">
            <div class="column is-3">
              <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:plus-circle" fullwidth>
                  <Dropdown v-model="item.jenisPelaksana" :options="d_JenisPelaksana" :optionLabel="'label'"
                    :optionValue="'value'" class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear
                    :filter="true" @change="changeJenis(item)" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                <VLabel>Pegawai</VLabel>
                <VControl icon="feather:user" fullwidth :loading="isLoadChange" class="prime-auto-select">
                  <MultiSelect v-model="item.pegawai" display="chip" class="w-100 is-rounded" :options="item.d_Pegawai"
                    optionLabel="label" optionValue="value" filter placeholder="Pilih Data" :maxSelectedLabels="3" />
                </VControl>
              </VField>
            </div>

            <div class="column is-1 mt-3">
              <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                icon="feather:trash" @click="removeItem(index)" color="danger">
              </VIconButton>
            </div>
            <div class="column is-1 is-flex mt-3">
              <VButton type="button" rounded outlined color="info" raised icon="feather:plus" @click="addNewItem()">
                Tambah Pelaksana
              </VButton>
            </div>
          </div>
        </VCard>
      </div>

      <div class="columns is-multiline" style="padding: 2rem 0rem;">
        <div class="column is-3">
          <VField label="Kode Kantong" class="label-unset">
            <VControl icon="feather:code">
              <VInput class="is-rounded" type="text" placeholder="Kode Kantong Darah.." autocomplete="off"
                v-model="item.kodekantongdarah" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Catatan Order" class="label-unset">
            <VControl icon="feather:book">
              <VInput class="is-rounded" type="text" placeholder="Catatan Order" autocomplete="off"
                v-model="item.catatanorder" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Tanggal order">
            <VDatePicker v-model="item.tglorder" mode="dateTime" style="width: 100%">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal Order" v-on="inputEvents" class="is-rounded" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Dokter Order">
            <VControl class="mt-2">
              <VInput type="text" placeholder="Dokter Order" readonly class="is-rounded" v-model="item.dokterorder"
                style="cursor:pointer text-align: center;background: var(--fade-grey);" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Golongan Darah">
            <VControl class="mt-2">
              <VInput type="text" placeholder="Golongan Darah" readonly class="is-rounded" v-model="item.golongandarah"
                style="cursor:pointer text-align: center;background: var(--fade-grey);" />
            </VControl>
          </VField>
        </div>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" :loading="isLoading" @click="save()" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>
  <!-- end modal order verif -->
  <!-- Detail Order Verifikasi -->
  <VModal :open="modalDetailOrderVerify" title="Detail Order" noclose size="big" actions="right"
    @close="modalDetailOrderVerify = false, clear(),isedit = false" cancelLabel="Tutup">
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
                    <h4 class="block-heading">Tgl Order</h4>
                    <p class="block-text">{{ H.formatDateIndo(item.tglorder) }}</p>
                    <h4 class="block-heading">Kode Kantong Darah</h4>
                    <p class="block-text">{{ item.kodekantongdarah || "-" }}</p>
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
                          <tr v-if="isedit == true">
                            <td class="font-labels" width="50%">Jumlah</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="50%">
                              <br>
                              <VField>
                                <VControl icon="lnir lnir-repeat-one">
                                  <VInput type="text" v-model="items.jumlah" placeholder="Jumlah" class="is-rounded" />
                                </VControl>
                              </VField>
                              <br>
                            </td>
                          </tr>
                          <tr v-else>
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
                  </div>
                  <div style="width: 20%;">
                    <label :style="'font-weight: bold;'">Dokter <span style="color: #bb2124;">Petugas</span> dan <span
                        style="color: #5bc0de;">Perawat</span></label>
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
    <template #action>
      <VButton icon="feather:save" v-if="buttonSaveEdit == false" :loading="isLoading" @click="editDetail()" color="info" raised>Edit</VButton>
      <VButton icon="feather:save" v-if="buttonSaveEdit == true" :loading="isLoading"  @click="simpanKen(detailOrderVerify[0].norec_so)" color="primary" raised>simpan Edit</VButton>
    </template>
  </VModal>
  <!-- end modal detail order -->
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
import Badge from 'primevue/badge';
import MultiSelect from 'primevue/multiselect';
import FloatingButton from "../emr/float-tambah.vue"

useHead({
  title: 'Dashboard Bank Darah - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const userLogin = useUserSession().getUser()
const modalFilter: any = ref(false)
const modalDetailOrderVerify: any = ref(false)
const dataOrder: any = ref([])
const dataPasien: any = ref([])
const rowGroupMetadata: any = ref([])
const detailOrderVerify: any = ref([])
const dataSource: any = ref([])
const detailOrderLayanan: any = ref([])
const d_Komponen: any = ref([])
const d_Pegawai: any = ref([])
const d_JenisPelaksana: any = ref([])
const listItem: any = ref([
  {
    pegawai: [],
    d_Pegawai: [],
    jenisPelaksana: null,
  }
])
const d_Produk: any = ref([])
const detailPetugas: any = ref([])
const detailDiagnosa: any = ref([])
const dataPenunjang: any = ref([])
const isLoading: any = ref(false)
const isedit: any = ref(false)
const buttonSaveEdit: any = ref(false)
const isLoadChange: any = ref(false)
const modalDetailOrder: any = ref(false)
const router = useRouter()
const route = useRoute()
const order: any = ref(0)
let listColor: any = ref(Object.keys(useThemeColors()))
const currentPasienDaftar: any = ref({
  limit: 10,
  rows: 50,
})
const currentPenunjang: any = ref({
  limit: 3,
  rows: 50,
})
const currentPageOrder: any = ref({
  limit: 5,
  rows: 50,
})
currentPasienDaftar.value.page = computed(() => {
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
currentPageOrder.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
const showModalFilter = () => {
  modalFilter.value = true
}
const item: any = ref({
  statusOrder: 0,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const changePeriode = () => {
  fetchDataOrder()
  fetchPenunjang()
  fetchPasien()
}
function getColorByType(objectjenispetugaspefk: any) {
  if (objectjenispetugaspefk === 6) {
    return '#bb2124';
  } else if (objectjenispetugaspefk === 17) {
    return '#5bc0de';
  } else {
    return 'gray';
  }
}
const fetchDataOrder = async (e: any) => {
  try {
    let dari = 'dari=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD');
    let sampai = '&sampai=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD');
    let statusOrder = '&statusorder=' + item.value.statusOrder
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';
    let limit: any = currentPageOrder.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit

    isLoading.value = true;
    const response = await useApi().get(`/bank-darah/get-order-darah?${dari}${sampai}${statusOrder}${search}&limit=${limit}&offset=${offset}`);
    modalFilter.value = false;
    response.data.forEach((element: any, i: any) => {
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
    dataOrder.value = response.data;
    dataOrder.value.total = response.total
  } catch (error) {
    console.error('Error fetching order data:', error);
  } finally {
    isLoading.value = false;
  }

}
const fetchPenunjang = async () => {
  try {
    let limit: any = currentPenunjang.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD');
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD');
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : ''

    dataPenunjang.value.loading = true;
    dataPenunjang.value = [];

    const response = await useApi().get(`/bank-darah/get-penunjang?${tglAwal}${tglAkhir}${search}&limit=${limit}&offset=${offset}`);

    dataPenunjang.value.loading = false;
    dataPenunjang.value = response.data;
    dataPenunjang.value.total = response.total
  } catch (error) {
    dataPenunjang.value.loading = false;
  }

}
const editDetail = () =>{
  isedit.value = true
  buttonSaveEdit.value=true
}
const simpanKen = (norec_so : any) => {
  // console.log('data', detailOrderVerify);

  let datas = [];
  let objSave={}


  for (let x = 0; x < detailOrderVerify.value.length; x++) {
    const element = detailOrderVerify.value[x];
    objSave = {
      idProduk: element.id, 
      jumlah: element.jumlah 
    };
    datas.push(objSave); 
  }

  useApi()
    .post(`/bank-darah/update-tindakan-darah?norec_so=${norec_so}`, { data: datas })
    .then((response) => {
      if (response.data != null) {
        H.alert('success', 'Data updated');
      }
    })
    .catch((error) => {
      console.error('Update failed:', error);
      H.alert('error', 'Data update failed');
    });
};

const fetchPasien = async () => {
  let limit: any = currentPasienDaftar.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
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

  isLoading.value = true
  dataPasien.value = []
  const response = await useApi().get(
    '/bank-darah/list-pasien-regist?ruanganid=' + ruanganid
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
const transaksiPelayanan = (e: any) => {
  router.push({
    name: 'module-bankDarah-transaksi',
    query: {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.norec_pd,
      norec_apd: e.norec_apd
    },

  })
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
  item.value.tglorder = e.tglorder
  item.value.kodekantongdarah = e.kodekantongdarah

  const response = await useApi().get(`/bank-darah/get-darah-verify?norec_so=${e.so_norec}`)
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
const changeTindakan = (e: any) => {
  isLoading.value = true
  d_Komponen.value = []
  item.value.hargaLayanan = 0
  useApi().get(
    '/tindakan/list-tindakan-komponen?idRuangan=' + item.value.idRuanganTujuan
    + '&idKelas=' + item.value.kelas
    + '&idProduk=' + e.id
    + '&idJenisPelayanan=' + item.value.idJenisPelayanan
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
const orderVerify = async (e: any) => {
  dropdownList()
  e.loading = true
  let data = {
    'idkelas': e.objectkelasfk,
    'idJenisPelayanan': e.jenispelayananfk,
    'idRekanan': e.objectrekananfk
  }
  //
  item.value.golongandarah = e.golongandarah
  item.value.dokterorder = e.nama_pegawai
  item.value.catatanorder = e.keterangan
  item.value.tglorder = e.tglorder
  item.value.jenisoperasi = e.jenisoperasi
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
  item.value.noregistrasi = e.noregistrasi
  item.value.soNorec = e.so_norec
  item.value.objectpegawaiorderfk = e.objectpegawaiorderfk
  item.value.tgloperasi = e.tgloperasi
  item.value.tglregistrasi = e.tglregistrasi
  item.value.kelas = e.objectkelasfk
  item.value.noregistrasi = e.noregistrasi
  if (e.objectkamaroperasifk)
    item.value.kamaroperasi = { id: e.objectkamaroperasifk, namakamarok: e.namakamarok }
  getListPelayanan(data)
  const getHargaLayanan = await useApi().get(`/dashboard/get-detail-order?strukorderfk=${e.so_norec}&objectkelasfk=${e.objectkelasfk}`)
  const diagnosa = await useApi().get(`diagnosa/riwayat-diagnosa-x?nocmfk=${e.nocmfk}&norec_pd=${e.pd_norec}`)
  getHargaLayanan.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  detailDiagnosa.value = diagnosa.data
  detailOrderLayanan.value = getHargaLayanan
  modalDetailOrder.value = true;
  e.loading = false
}
const removeItem = (index: any) => {
  listItem.value.splice(index, 1)
}
const getListPelayanan = async (data: any) => {
  const response = await useApi().get(`/bank-darah/get-pelayanan-darah?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}`)
  d_Produk.value = response.map((e: any) => {
    return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
  })
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

const ambilDarah = (e: any) => {
  router.push({
    name: 'module-darah-form-pengurangan-darah',
    query: {
      noorder: e.noorder,
    }
  })
}

const changeJenis = async (e: any) => {
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
const clear = () => {
  item.value.id = ''
  item.value.no = ''
  item.value.layanan = ''
  item.value.hargaLayanan = ''
  item.value.qtyproduk = ''
  item.value.jumlah = ''
}
const save = async () => {
  if (detailOrderLayanan.value.length < 1) {
    H.alert('warning', 'Order Tidak Boleh kosong')
    return
  }
  if (listItem.value.length <= 0) {
    useToaster().error('Pilih Setidaknya 1 Petugas')
    return
  }
  // save-order-pelayanan-darah
  let datas = []
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
    'objectpegawaiorderfk': item.value.objectpegawaiorderfk,
    'tglregistrasi': item.value.tglregistrasi,
    'noregistrasi': item.value.noregistrasi,
    'so_norec': item.value.soNorec,
    'objectkelasfk': item.value.kelas,
    'kodekantongdarah': item.value.kodekantongdarah,
  }

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
  await useApi().post('/bank-darah/save-order-pelayanan-darah', { 'data': datas, 'parameter': parameter }).then((response: any) => {
    modalDetailOrder.value = false
    fetchDataOrder(0)
    fetchPenunjang()
    isLoading.value = false;
  }).catch((error) => {
    isLoading.value = false;
    useToaster().error('Something Went Wrong')
  })

}
const update = (e: any) => {
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
const addNewItem = () => {
  listItem.value.push({
    jenisPelaksana: null,
    pegawai: [],
  });

}
const add = () => {
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
function dropdownList() {
  useApi().get(`tindakan/list-jenis-petugas`).then((response: any) => {
    d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
    item.nilaiCito = response.cito != null ? parseFloat(response.cito) : 1
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
const hapusItems = (e: any) => {
  for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
    if (detailOrderLayanan.value[i].no == e.no) {
      detailOrderLayanan.value.splice(i, 1);
    }
  }
  dataSource.value = detailOrderLayanan.value
  clear()
}
const edit = (e: any) => {
  item.value.no = e.no
  d_Produk.value.forEach((element: any) => {
    if (element.id == e.prid) {
      item.value.produk = element
      return
    }
  });
  d_Komponen.value = e.komponenharga
  item.value.hargaLayanan = e.hargasatuan
  item.value.jumlah = e.qtyproduk
}
const showPasienLama = async () => {
  router.push({
    name: 'module-registrasi-pasien-lama',
    query: {

    },

  })
}

fetchDataOrder()
fetchPenunjang()
fetchPasien()
watch(currentPenunjang.value, () => {
  fetchPenunjang()
})
watch(currentPageOrder.value, () => {
  fetchDataOrder(0)
})
watch(currentPasienDaftar.value, () => {
  fetchPasien()
})
watch(
  () => [
    item.value.statusOrder
  ], () => {
    fetchDataOrder()
  }
)
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
