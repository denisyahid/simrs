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
                    Laboratorium
                  </h3>
                  <p>
                    Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                  </p>
                  <div class="column p-0 columns is-multiline">
                    <div class="column is-12 py-1">
                      <VControl>
                        <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" class="f-text"
                          placeholder="Filter ruangan" :searchable="true" autocomplete="off"
                          @select="changeRuang(item.filterRuangan)" />
                      </VControl>
                    </div>
                    <div class="column is-12 py-1">
                      <VControl class="prime-auto">
                        <AutoComplete v-model="item.filterRuanganPengorder" :suggestions="d_RuanganAsal"
                          @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Filter ruangan pengorder" />
                      </VControl>
                    </div>
                  </div>
                  <VTag @click="showModalFilter()" color="danger" rounded elevated
                    style="position: relative; bottom: -1.5rem; cursor:pointer">{{
                      H.formatDateToLocalString(item.periode.start) ==
                        H.formatDateToLocalString(item.periode.end) ?
                        H.formatDateToLocalString(item.periode.start) :
                        H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
                          H.formatDateToLocalString(item.periode.end) : '')
                    }} <i class="fas fa-filter ml-3" aria-hidden="true"></i>
                  </VTag>
                  <div class="is-6 p-0" style="margin-left: 200px; margin-top: -10px">
                    <VControl>
                      <VSwitchBlock @change.stop="changeisRanap($event)" v-model="item.isRanap" :label="'Rawat Inap'"
                        color="danger" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="column is-12" style="margin-top: 2rem;">
            <Badge :value="dataOrder.total" v-if="dataOrder.total > 0" severity="danger"
              style="z-index: 6;top: 0px;position: relative;left: 20rem;" />
            <Badge :value="dataPenunjang.total" v-if="dataPenunjang.total > 0" severity="danger"
              style="z-index: 6;top: 0px;position: relative;left: 47rem;" />
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
                              - <span style="font-size: 8pt; color: #A2A5B9">{{ items.kebangsaan }}</span>
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
                              <span>{{ items.tgllahir }}</span>
                              <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                              <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                              <span>{{ items.umur }}</span>
                              <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                              <span>{{ items.nohp }}</span>

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

                            <VIconButton v-tooltip.bottom.left="'Ubah Dokter Pengorder'" label="Bottom Left"
                              color="warning" circle icon="pi pi-user" @click="openModalChange(items.so_norec)"
                              :loading="item.loading" style="margin-right: 15px;" />

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
                            <VIconButton v-tooltip.bottom.left="'Batal Verif'" label="Bottom Left" color="danger" v-if="items.statusorder == 1" circle icon="feather:trash" @click="batalVerifikasi(items)" style="margin-right: 15px;" />
                            <!-- <VIconButton v-tooltip.bottom.left="'Permintaan Darah'" v-if="tomboldarah == true" label="Bottom Left" color="warning" circle
                              icon="pi pi-check-circle" 
                              @click="buka()" :loading="item.loading" style="margin-right: 15px;" /> -->


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
                                <!-- <a role="menuitem" class="dropdown-item is-media" @click="kirimLIS(items)">
                                  <div class="icon">
                                    <i class="iconify" data-icon="feather:plane" aria-hidden="true"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Kirim Ulang LIS</span>
                                    <span>Kirim LIS</span>
                                  </div>
                                </a> -->
                                <a v-if="items.objectruangantujuanfk == 302" role="menuitem"
                                  class="dropdown-item is-media" @click="buka(items)">
                                  <div class="icon">
                                    <i class="iconify" data-icon="feather:plane" aria-hidden="true"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Form Permintaan Darah</span>
                                    <span>Form Permintaan Darah</span>
                                  </div>
                                </a>
                                <a v-if="items.objectruangantujuanfk == 337" role="menuitem"
                                  class="dropdown-item is-media" @click="bukamikro(items)">
                                  <div class="icon">
                                    <i class="iconify" data-icon="feather:plane" aria-hidden="true"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Form Mikrobiologi</span>
                                    <span>Form Mikrobiologi</span>
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
                                <a role="menuitem" class="dropdown-item is-media" @click="cetakLabel(items)">
                                  <div class="icon">
                                    <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Cetak Label</span>
                                    <span>Cetak Label</span>
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
                  <VButton raised class="search-button" @click="fetchPenunjang(penunjang)" :loading="isLoading"> Cari
                    Data
                  </VButton>
                </div>
                <VCard class="text-center pt-0 pb-0 mt-0">
                  <VRadio v-model="penunjang" value="0" label="Langsung Pendaftaran" name="outlined_radio"
                    color="warning" />
                  <VRadio v-model="penunjang" value="1" label="Diorder Poli" name="outlined_radio" color="info" />
                </VCard>
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
                            <div :class="'grid-item-head ' + (item.norec_apd != null ? 'is-registrasi' : '')">
                              <div class="flex-head">
                                <div class="meta">
                                  <span v-if="item.norec_apd != null" class="dark-inverted"
                                    v-tooltip-prime.top="item.namaruangan">
                                    {{ item.namaruangan }}
                                  </span>
                                  <span>
                                    {{ H.formatDateIndoSimple(item.tglmasuk) }}
                                  </span>
                                  <span>
                                    Pemesanan dilakukan oleh ruangan :&nbsp;<b>{{ item.ruanganasal ?? '-' }}</b>
                                  </span>
                                  <span style="font-weight:bold">
                                    {{ item.noorder }}
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
                                  <a role="menuitem" @click="cetakLabel(item)" class="dropdown-item is-media">
                                    <div class="icon">
                                      <!-- <i aria-hidden="true" class="lnil lnil-pencil"></i> -->
                                    </div>
                                    <div class="meta">
                                      <span>Cetak Label</span>
                                    </div>
                                  </a>

                                </template>
                              </VDropdown>
                            </div>
                            <div class="grid-item">
                              <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')" :badge="(item.objectjeniskelaminfk == '1' ? '/images/other/male.png'
                                : '/images/other/female.png')" size="big" />
                              <h3 class="dark-inverted">{{ item.namapasien }} - <span
                                  style="font-size: 8pt; color: #A2A5B9">{{
                                    item.kebangsaan }}</span></h3>
                              <p>No REG : {{ item.noregistrasi }}</p>
                              <p>No RM : {{ item.nocm }}</p>
                              <p>No BPJS : {{ item.nobpjs ?? '-' }}</p>
                              <p>NIK : {{ item.noidentitas }}</p>
                              <p>No Hp : {{ item.nohp }}</p>

                              <div class="buttons mt-4">
                                <VIconButton v-tooltip.bottom.left="'Rincian'" label="Bottom Left" color="info" outlined
                                  circle icon="pi pi-arrow-right" @click="transaksiPelayanan(item)"
                                  :loading="item.loading" />

                                <VIconButton v-if="item.norec_so != null" v-tooltip.bottom.left="'Cetak order'"
                                  color="warning" outlined circle icon="feather:printer"
                                  @click="cetakOrderkun(item.norec_so)" />
                                <VIconButton v-tooltip.bottom.left="'EMR'" color="success" outlined circle
                                  icon="fas fa-stethoscope" @click="emr(item)" />
                              </div>

                              <VTag :label="item.status" color="warning" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Belum Bayar'" />
                              <VTag :label="item.status" color="success" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Lunas'" />
                              <VTag label="Diorder Ruangan" color="success" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.norec_so != null" />
                              <VTag label="Langsung Pendaftaran" color="success" rounded elevated
                                style="font-size:0.8rem;font-weight: bold;" v-if="item.norec_so == null" />
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
                                - <span style="font-size: 8pt; color: #A2A5B9">{{ item.kebangsaan }}</span>
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

    <VModal :open="modalDetailOrder" title="Verifikasi Order" noclose size="big" actions="right"
      @close="modalDetailOrder = false, clear(), disabledSave = false" cancelLabel="Tutup">

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
                      {{ item.noregistrasi + (item.jeniskelamin ==
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
        <div class="column is-12 mt-5">
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
                              <tr v-if="items.cito != false">
                                <td>Cito</td>
                                <td>:</td>
                                <td>{{ items.cito == true ? 'Ya' : '' }} </td>
                              </tr>


                            </table>

                          </div>
                        </div>
                        <div class="box-end" style="width: 30%">
                          <div class="columns is-multiline">
                            <div class="column is-6" style="margin-top: -5px;">
                              <VControl>
                                <VSwitchBlock v-model="items.istidaktagih" label="Tidak ditagihkan" color="info"
                                  value="true" />
                              </VControl>
                            </div>
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
        <!-- <VButton icon="feather:save" @click="saveLIS()" :loading="isLoading" color="primary" raised>Kirim Ulang LIS</VButton> -->
        <VButton v-if="item.namaFILE != null" color="info" raised icon="fas fa-file" class="mr-2" @click="lihatDok()">
          Lihat File
        </VButton>
        <VButton v-if="isLoadDataNoRec" icon="feather:printer" @click="cetakBuktiOrder(so_norec)" color="info"
          :loading="isLoading" raised>Cetak</VButton>
        <VButton icon="feather:save" @click="save()" :disabled="disabledSave" :loading="isLoading" color="primary"
          raised>Simpan</VButton>
      </template>
    </VModal>

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
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">Catatan</h4>
                      <p class="block-text">
                        {{ item.catatanpasien ? item.catatanpasien : '-' }}</p>
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
                            <tr v-if="isedit == true">
                              <td class="font-labels" width="50%">Jumlah</td>
                              <td class="font-labels">:</td>
                              <td class="font-values" width="50%">
                                <br>
                                <VField>
                                  <VControl icon="lnir lnir-repeat-one">
                                    <VInput type="text" v-model="items.jumlah" placeholder="Jumlah"
                                      class="is-rounded" />
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
      <!-- <template #action>
        <VButton icon="feather:save" v-if="buttonSaveEdit == false"  :loading="isLoading" @click="editDetail()" color="info" raised>Edit</VButton>
        <VButton icon="feather:save" v-if="buttonSaveEdit == true " :loading="loadingsave"  @click="simpanKen(detailOrderVerify[0].norec_so)" color="primary" raised>simpan Edit</VButton>
      </template> -->
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


    <Dialog v-model:visible="modalBankDarah" modal header="Permintaan Darah" :style="{ width: '70vw' }">
      <BankDarah :norec_pd="norec_pd" :pasien="pasien" :ID_PASIEN="id_pasien" :noorder="noorder" :isDashLab="true"
        :tombol="false" />
      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalBankDarah = false">
          Tutup
        </VButton>
      </template>
    </Dialog>
    <!-- <VModal :open="modalPrint" title="Ceklis untuk tindakan yang sudah selesai" :noclose="false" size="medium" actions="right"
      @close="modalPrint = false">
      <template #content>
        <form class="modal-form custom-mod ">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VCard>
                <div class="columns is-multiline p-1">
                  <div class="column is-12">
                    <VCard class="is-grey">
                      <div class="columns is-multiline p-1">
                        <div class="column is-12 mb--15"  v-for="(item, index) in dataPrint" :key="index">
                          <div class="columns is-multiline p-0">
                            <div class="column is-9">
                              <div class="file-box-2">
                                <div class="meta">
                                  <span>{{item.namaproduk}}</span>
                                </div>
                              </div>
                            </div>
                            <div class="column is-3 mt-5">
                              <input 
                                type="checkbox" 
                                class="mr-2" 
                                style="width: 20px; height: 20px; cursor: pointer;" 
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </VCard>
                  </div>
                </div>
              </VCard>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:save" @click="printOrder()" color="primary" raised>Cetak
        </VButton>
      </template>
    </VModal> -->
    <Dialog is="form" v-model:visible="modalBukti" modal header="Bukti Order" :style="{ width: '100rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
      <!-- <template #content> -->
      <div class="columns is-multiline">
        <div class="column is-4">
          <VField>
            <VLabel class="required-field">Klinis/Diagnosis</VLabel>
            <VControl>
              <input v-model="item.klinis_diagnosis" type="text" class="input" placeholder="klinis/diagnosis" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-12">
          <h1 style="margin-bottom: 10px;" class="ml-3">
            Riwayat Pemakaian Antibiotik
          </h1>
          <div class="columns is-multiline is-flex is-align-items-center pt-0">
            <!-- Sebelum Pemberian AB -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sebelumab" label="Sebelum Pemberian AB" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>

            <!-- Sedang Terapi AB -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.terapiab" label="Sedang terapi AB" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>

            <!-- Jenis/Nama Antibiotik -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <!-- <VControl> -->
                <VLabel>Nama antibiotik :</VLabel>
                <VControl class="ml-2">
                  <input v-model="item.namaantibiotik" type="text" class="input" placeholder="Masuan Nama..." />
                </VControl>
                <!-- <VCheckbox 
                                    class="fontcheckbox" 
                                    v-model="item.namaantibiotik" 
                                    label="Jenis/nama antibiotik" 
                                    color="primary" 
                                    circle 
                                  /> -->
                <!-- </VControl> -->
              </VField>
            </div>

            <!-- Hari Ke -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VLabel>Hari Ke :</VLabel>
                <VControl class="ml-2">
                  <input v-model="item.harike" type="text" class="input" placeholder="Hari ke..." />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <h1 style="margin-bottom: 10px;" class="ml-3">
            Penggunaan medical device
          </h1>
          <div class="columns is-multiline is-flex is-align-items-center pt-0">
            <!-- Sebelum Pemberian AB -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.qvc" label="Q CVC" color="primary" circle />
                </VControl>
              </VField>
            </div>

            <!-- Sedang Terapi AB -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.ivline" label="IV Line" color="primary" circle />
                </VControl>
              </VField>
            </div>

            <!-- Jenis/Nama Antibiotik -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.namaantibiotik2" label="Jenis/nama antibiotik"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.wsd" label="WSD" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.eet" label="EET" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.ventilator" label="Ventilator" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.cpap" label="CPAP" color="primary" circle />
                </VControl>
              </VField>
            </div>

            <!-- Hari Ke -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VLabel>Lainnya :</VLabel>
                <VControl class="ml-2">
                  <input v-model="item.lain1" type="text" class="input" placeholder="Lainnya..." />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <h1 style="margin-bottom: 10px;" class="ml-3">
            Jenis Spesimen
          </h1>
          <div class="columns is-multiline">
            <!-- Row 1 -->
            <div class="column is-4">
              <VField>
                <VLabel>1. Urine:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.midstream" label="Midstream" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.urineKateter" label="Urine kateter" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.supraPubik" label="Aspirasi supra pubik"
                    color="primary" />
                </VControl>
              </VField>
            </div>

            <!-- Row 2 -->
            <div class="column is-4">
              <VField>
                <VLabel>2. Darah:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sisi" label="Sisi" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sisi2" label="2 Sisi" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.tempatEndokarditis" label="3 tempat (endokarditis)"
                    color="primary" />
                </VControl>
              </VField>
            </div>

            <!-- Row 3 -->
            <div class="column is-4">
              <VField>
                <VLabel>3. Sputum:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.pagi" label="Pagi" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sewaktu" label="Sewaktu" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.induksi" label="Induksi" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sputanEET" label="Sputan EET" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VLabel>Area pengambilan:</VLabel>
                  <input v-model="item.pengambilan1" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>4. Luka:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.dasarluka" label="Dasar Luka" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.aspirasipus" label="Aspirasi pus" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VLabel>Area pengambilan:</VLabel>
                  <input v-model="item.pengambilan2" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>5. Jaringan:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.ascites" label="Ascites" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VLabel>Area pengambilan jaringan:</VLabel>
                  <input v-model="item.pengambilan3" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
            <!-- Additional Rows -->
            <div class="column is-4">
              <VField>
                <VLabel>6. LCS:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.lcs" label="Ascites" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.lp" label="LP" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.vpshunt" label="VP shunt" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.evd" label="EVD" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>7. Cairan:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.ascites2" label="Ascites" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.perikardium2" label="Perikardium" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.pleura" label="pleura" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VLabel>Area pengambilan:</VLabel>
                  <input v-model="item.pengambilan4" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>8. Lainnya:</VLabel>
                <VControl>
                  <input v-model="item.lainnya3" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Volume spesimen(ml):</VLabel>
              <VControl>
                <input v-model="item.volspes" type="text" class="input" placeholder="..................." />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4">
          <VField>
            <VControl>
              <VCheckbox class="fontcheckbox" v-model="item.spesimen" label="Pengambilan spesimen" color="primary" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField>
            <VLabel>Pengiriman spesimen:</VLabel>
            <VControl>
              <input v-model="item.pengirimanspesimen" type="text" class="input" placeholder="..................." />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VDatePicker v-model="item.waktu" color="green" trim-weeks mode="datetime">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel style="text-overflow:unset">Waktu</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Waktu" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-4">
          <VField>
            <VLabel>Cara Penyimpanan:</VLabel>
            <VControl>
              <input v-model="item.penyimpanan" type="text" class="input" placeholder="..................." />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField>
            <VControl>
              <VCheckbox class="fontcheckbox" v-model="item.empatcels" label="4°C" color="primary" />
            </VControl>
          </VField>
        </div>
      </div>
      <!-- </template> -->
      <!-- <template #action> -->
      <!-- <VButton color="primary" raised @click="addData(item)">Simpan</VButton> -->
      <!-- </template> -->
    </Dialog>
  </div>
  <VModal :open="ModalChangeDokter" title="Edit Dokter Order Laboratorium" :noclose="false" size="medium"
    actions="center" @close="ModalChangeDokter = false">
    <template #content>
      <form class="modal-form custom-mod ">
        <VField class="is-rounded-select" v-slot="{ id }">
          <VControl fullwidth class="prime-auto ">
            <AutoComplete v-model="item.dokter_change" :suggestions="d_Dokter_Change"
              @complete="fetchDokterChange($event)" :optionLabel="'namalengkap'" :dropdown="true" :minLength="3"
              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
              placeholder="ketik nama Dokter" />
          </VControl>
        </VField>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveChangeOrders()" :loading="isLoadSaveChangeDokter" color="primary" raised>
        Simpan
      </VButton>
    </template>
  </VModal>
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
import Dialog from 'primevue/dialog';
import BankDarah from '../emr/profile-pasien/page-emr/formulir-permintaan-darah.vue'
import * as qzService from '/@src/utils/qzTrayService'


useHead({
  title: 'Dashboard Laboratorium - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)


const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()

const NOREC_PD = useRoute().query.nocm as string

const dataSource: any = ref([])
const filters = ref('')
const d_Dokter = ref([])
const d_Dokter_Change = ref([])
const d_Komponen = ref([])
const d_Pegawai = ref([])
const d_Ruangan = ref([])
const d_RuanganAsal = ref([])
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
const page = ref(0);
let statusOrder: any = ref([])
let statusOrderPenunjang: any = ref([])
let result: any = ref([])
let so_norec = ref('')
let so_norec_change_dokter = ref('')
let dokterPraktek: any = ref([])
let dataPenunjang: any = ref([])
// let dataPrint: any = ref([])
let isLoading: any = ref(false)
let isLoadSaveChangeDokter: any = ref(false)
let loadingsave: any = ref(false)
let modalBukti: any = ref(false)
let ModalChangeDokter: any = ref(false)
let detailDiagnosa: any = ref(0)
let dataStokObat: any = ref([])
let sourceItemSelect: any = ref([])
let detailOrderVerify: any = ref(0)
let detailOrderLayanan: any = ref(0)
const pasien = ref({});
let norec_pd = ref('')
let id_pasien = ref('')
let noorder = ref('')
const registrasi = ref({});
const isedit: any = ref(false)
const buttonSaveEdit: any = ref(false)
let modalDetailOrder: any = ref(false)
let modalDetailOrderVerify: any = ref(false)
let modalFilter: any = ref(false)
let modalGolonganDarah: any = ref(false)
let tomboldarah: any = ref(false)
let modalBankDarah: any = ref(false)
let modalJenisKelamin: any = ref(false)
// let modalPrint: any = ref(false)
let modalTransaksi: any = ref(false)
let dataPasien: any = ref([])
let isLoadDataOrder: any = ref(false)
let isLoadDataNoRec: any = ref(false)
let disabledSave: any = ref(false)
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
const penunjang: any = ref(0)
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
    let ruanganid = item.value.filterRuangan ? `&ruanganid=${item.value.filterRuangan}` : '';
    let ruanganAsal_id = item.value.filterRuanganPengorder && item.value.filterRuanganPengorder.value ? `&ruanganAsal_id=${item.value.filterRuanganPengorder.value}` : '';
    let tglAwal = `&tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`;
    let tglAkhir = `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`;
    let qnamapasien = item.value.qnamapasien ? `&qnamapasien=${item.value.qnamapasien}` : '';
    let search = item.value.search ? `&search=${item.value.search}` : '';
    let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
    let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
    let qnoidentitas = ''; // Jika diperlukan tambahkan logika untuk qnoidentitas
    let statusOrder = '';
    let limit: any = currentPageOrder.value.limit
    let offset: any = page.value ? page.value : 1;
    offset = (offset * limit) - limit;

    if (order) statusOrder = '&statusorder=' + q

    isLoading.value = true;

    const response = await useApi().get(`/dashboard/so-lab?ruanganid=${ruanganid}${ruanganAsal_id}${search}${tglAwal}${tglAkhir}${qnamapasien}${statusOrder}${qnocm}${qnoregistrasi}&limit=${limit}&offset=${offset}`);

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
const buka = (e: any) => {
  norec_pd.value = e.pd_norec;
  id_pasien.value = e.id_pasien;
  noorder.value = e.noorder;
  pasien.value = {
    namapasien: e.namapasien,
    nocm: e.pas_nocm,
    tgllahir: e.tgllahir,
    jeniskelamin: e.jeniskelamin,
    umur: e.umur,
    alamatlengkap: e.alamat
  };
  modalBankDarah.value = true
}
const bukamikro = (e: any) => {
  modalBukti.value = true
  useApi().get(`laboratorium/bukti-lab-mikro?noorder=${e.noorder}`).then((response: any) => {
    item.value.klinis_diagnosis = response.klinis_diagnosis
    item.value.sebelumab = response.sebelumab
    item.value.terapiab = response.terapiab
    item.value.namaantibiotik = response.namaantibiotik
    item.value.harike = response.harike
    item.value.qvc = response.qvc
    item.value.ivline = response.ivline
    item.value.namaantibiotik2 = response.namaantibiotik2
    item.value.wsd = response.wsd
    item.value.eet = response.eet
    item.value.ventilator = response.ventilator
    item.value.cpap = response.cpap
    item.value.lain1 = response.lain1
    item.value.midstream = response.midstream
    item.value.urineKateter = response.urineKateter
    item.value.supraPubik = response.supraPubik
    item.value.sisi = response.sisi
    item.value.sisi2 = response.sisi2
    item.value.tempatEndokarditis = response.tempatEndokarditis
    item.value.pagi = response.pagi
    item.value.sewaktu = response.sewaktu
    item.value.induksi = response.induksi
    item.value.sputanEET = response.sputanEET
    item.value.pengambilan1 = response.pengambilan1
    item.value.dasarluka = response.dasarluka
    item.value.aspirasipus = response.aspirasipus
    item.value.pengambilan2 = response.pengambilan2
    item.value.ascites = response.ascites
    item.value.pengambilan3 = response.pengambilan3
    item.value.lcs = response.lcs
    item.value.lp = response.lp
    item.value.vpshunt = response.vpshunt
    item.value.evd = response.evd
    item.value.ascites2 = response.ascites2
    item.value.perikardium2 = response.perikardium2
    item.value.pleura = response.pleura
    item.value.pengambilan4 = response.pengambilan4
    item.value.lainnya3 = response.lainnya3
    item.value.volspes = response.volspes
    item.value.spesimen = response.spesimen
    item.value.pengirimanspesimen = response.pengirimanspesimen
    item.value.penyimpanan = response.penyimpanan
    item.value.empatcels = response.empatcels
  })
}

const fetchPenunjang = async (q: any) => {
  try {
    let ruanganid = item.value.filterRuangan || '';
    let ruanganAsal_id = item.value.filterRuanganPengorder && item.value.filterRuanganPengorder.value ? `&ruanganAsal_id=${item.value.filterRuanganPengorder.value}` : '';
    let tglAwal = '&tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD');
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD');
    let qnama = item.value.qnama ? `&qnama=${item.value.qnama}` : '';
    let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
    let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : ''
    let limit: any = currentPenunjang.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    let statusOrderPenunjang = '';
    offset = (offset * limit) - limit

    if (penunjang) statusOrderPenunjang = '&statusorder=' + q
    dataPenunjang.value.loading = true;
    dataPenunjang.value = [];

    const response = await useApi().get(`/dashboard/penunjang-lab?ruanganid=${ruanganid}${ruanganAsal_id}${tglAwal}${tglAkhir}${qnama}${qnocm}${qnoregistrasi}${statusOrderPenunjang}${search}&limit=${limit}&offset=${offset}`);

    dataPenunjang.value.loading = false;
    dataPenunjang.value = response.data;
    dataPenunjang.value.total = response.total
    route.query.page = '1'
    isLoading.value = false
  } catch (error) {
    console.error('Error fetching penunjang data:', error);
    dataPenunjang.value.loading = false;
  }
}
const editDetail = () => {
  isedit.value = true
  buttonSaveEdit.value = true
}
const simpanKen = (norec_so: any) => {
  // console.log('data', detailOrderVerify);

  let datas = [];
  let objSave = {}


  for (let x = 0; x < detailOrderVerify.value.length; x++) {
    const element = detailOrderVerify.value[x];
    objSave = {
      idProduk: element.id,
      jumlah: element.jumlah
    };
    datas.push(objSave);
  }
  loadingsave.value = true
  useApi()
    .post(`/bank-darah/update-tindakan-darah?norec_so=${norec_so}`, { data: datas })
    .then((response) => {
      if (response.data != null) {
        H.alert('success', 'Data updated');
        loadingsave.value = false
      }
    })
    .catch((error) => {
      console.error('Update failed:', error);
      H.alert('error', 'Data update failed');
    });
};

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
  const response = await useApi().get(`/dashboard/get-pelayanan-lab?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}&idruangan=${data.idruangan}&objectkebangsaanfk=${item.value.objectkebangsaanfk}`)
  d_Produk.value = response.map((e: any) => {
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
  await fetchDataOrder();
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
  item.value.catatanpasien = e.keteranganlainnya

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
  isLoading.value = true
  await H.statusClosingPasien(item.value.noregistrasi);
  isLoading.value = false

  if (detailOrderLayanan.value.length < 1) {
    useToaster().error('Order Tidak Boleh kosong')
    return
  }
  if (!item.value.dokterVerif) {
    useToaster().error('Dokter Verify Tidak Boleh Kosong')
    return
  }
  if (!item.value.pegawaiVerif) {
    useToaster().error('Petugas Verify Tidak Boleh Kosong')
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
      'istidaktagih': response.istidaktagih ? response.istidaktagih : null
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
  await useApi().post('/dashboard/save-order-pelayanan-lab', { 'data': datas, 'parameter': parameter, 'noorder': item.value.noorder }).then((res: any) => {
    isLoading.value = false
    //? Briding Lab
    // if (res.kode == 200 || res.kode == 201) {
    //   useApi().postNoMessage('/bridging/penunjang/save-bridging-vans-lab', jsonVansLab).then(async (response: any) => {
    //     isLoading.value = false
    //     so_norec = res.pelPasien.strukorderfk
    //     if (res.kode == 200 || res.kode == 201) {
    //       disabledSave.value = true
    //       // modalDetailOrder.value = false
    //       console.log('masuk kondisi disabled save')
    //       fetchDataOrder(0)
    //       reload()
    //     }
    //   }).catch((error) => {
    //     console.log(error)
    //     useToaster().error('Something Went Wrong')
    //     isLoading.value = false
    //   })
    // }
  }).catch((error) => {
    console.log(error)
    isLoading.value = false
  })
  isLoadDataNoRec.value = true
}

const saveLIS = async () => {
  // await H.statusClosingPasien(item.value.noregistrasi);
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
  await useApi().postNoMessage('/bridging/penunjang/save-bridging-vans-lab', jsonVansLab).then(async (response: any) => {
    fetchDataOrder(0)
    reload()
  }).catch((error) => {
    useToaster().error('Something Went Wrong')
    isLoading.value = false
  })
  isLoadDataNoRec.value = true
}

const hapusItems = (e: any) => {

  console.log('e data', e);

  if (e.norec_op == undefined) {
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
      if (detailOrderLayanan.value[i].no == e.no) {
        detailOrderLayanan.value.splice(i, 1);
      }
    }
    dataSource.value = detailOrderLayanan.value
  }
  else {
    useApi().post(
      `/laboratorium/hapus-tindakan-lab-verif?norec_op=${e.norec_op}`).then((response: any) => {
        H.alert('success', 'update success')
        for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
          if (detailOrderLayanan.value[i].no == e.no) {
            detailOrderLayanan.value.splice(i, 1);
          }
        }
        dataSource.value = detailOrderLayanan.value
      }, (error) => {
        console.error(error)
      })
  }
}


const changeSwitch = (e: any) => {
  fetchDataOrder(e)
}

const changePeriode = () => {
  fetchDataOrder(0)
  chartLayananByRuangan()
  fetchPenunjang(0)
}

const getHarga = async (e: any) => {
  const response = await useApi().get(`/dashboard/get-pelayanan-lab?idkelas=${item.value.kelas}&idjenispelayanan=${item.value.idJenisPelayanan}&idruangan=${item.value.idRuanganTujuan}&objectkebangsaanfk=${item.value.objectkebangsaanfk}`)
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

  let no;
  (detailOrderLayanan.value.length == 0) ? no = 1 : no = detailOrderLayanan.value.length + 1

  // Validasi ketika ada pemeriksaan yang sama
  if (detailOrderLayanan.value.length > 0) {
    const idProdukList = detailOrderLayanan.value.map(item => item.prid);
    const current_ID_Produk = item.value.produk.id;
    const hasMatch = idProdukList.includes(item.value.produk.id);
    if (hasMatch) {
      H.alert('warning', 'Terdapat pemeriksaan yang sama!')
      return;
    }
  }

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
  disabledSave.value = false
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
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan,objectdepartemenfk&param_search=namaruangan&query=${filter.query}&limit=200`)
  if (response && response.length) {
    d_RuanganAsal.value = response.filter(e =>
      ![1,2,3,4,5,6,12,14,18,21,24,25,27,30,37,45,48,73,75].includes(e.objectdepartemenfk)
    );
  }
}

const transaksiPelayanan = (e: any) => {
  router.push({
    name: 'module-laboratorium-transaksi',
    query: {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.norec_pd,
      norec_apd: e.norec_apd,
      noorder: e.noorder
    },

  })
}

const PengkajianMedis = (e: any) => {
  H.checkAksesEMR(e, kelompokUser, kelompokUserID, pegawaiId)
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

const cetakLabel = (e: any) => {
  // console.log('e',e);
  qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', 1)
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
      fetchPenunjang(0)
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
      fetchPenunjang(0)
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
  fetchPenunjang(0)
  chartLayananByRuangan()

}
const emr = (e: any) => {
  // H.checkAksesEMR(e,kelompokUser,kelompokUserID,pegawaiId)
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
    message: 'Yakin mau membatalkan verifikasi lab ini',
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'middle',
    accept: () => {
      var objSave = {'norec_so': e.so_norec}
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
      fetchDataOrder(1)
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchPasien = async () => {
  let limit: any = currentPasienDaftar.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  let namapasien = '', nocm = '', noreg = '', statuspanggil = '', search = '', ruanganid = '', dari = '', sampai = '', status = '';

  if (item.value.filterRuangan) {
    ruanganid = item.value.filterRuangan
  }
  if (item.value.periode.start) {
    dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  }
  if (item.value.periode.end) {
    sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  }
  if (item.value.qnama) namapasien = item.value.qnama
  if (item.value.qnoreg) noreg = item.value.qnoreg
  if (item.value.qnocm) nocm = item.value.qnocm
  if (item.value.rsearch) search = item.value.rsearch

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
  item.noregiskun = e.noregistrasi
  // item.objectruangantujuanfk = e.objectruangantujuanfk
  item.objectruanganfk = e.objectruanganfk
  item.objectruanganlastfk = e.objectruanganlastfk
  item.norec_apd = e.norec_apd
  item.objectkelasfk = e.objectkelasfk
  item.tglregistrasi = e.tglregistrasi
  sourceItemSelect.value = e
  modalTransaksi.value = true
}

const saveTransaksi = async (e: any, x: any) => {
  let json = {
    pasiendaftar: {
      'norec_pd': item.norec_pd,
      'tglregistrasi': item.tglregistrasi,
      'objectkelasfk': item.objectkelasfk,
      'noregistrasifk': item.norec_pd,
      'noregiskun': item.noregiskun

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
  let norec_pd = e.so_norec ? e.so_norec : e.norec_so;
  H.printBlade(`report/cetak-order?type=laboratorium&noregistrasi=${norec_pd}`)
}
const cetakOrderkun = async (e: any) => {
  // useApi().get(`/dashboard/riwayat-order-print?norec_so=${e}`).then((resp:any)=>{
  //   dataPrint.value = resp.data
  // })
  // let norec_pd = e.norec_so ;
  H.printBlade(`report/cetak-order?type=laboratorium&noregistrasi=${e}`)
  // modalPrint.value=true
  // let norec_pd = e.norec_so ;
  // H.printBlade(`report/cetak-order?type=laboratorium&noregistrasi=${norec_pd}`)
}

// const printOrder = async (e:any) => {
//   let norec_pd = e.norec_so ;
//   H.printBlade(`report/cetak-order?type=laboratorium&noregistrasi=${norec_pd}`)
// }

const cetakSEP = (e: any) => {
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
    'SEP', 1)
}

const kirimLIS = (e: any) => {

  orderVerify(e)
}

const fetchDokterChange = async (filter: any) => {
  if (!filter || !filter.query) { return; }
  const response = await useApi().get(`/registrasi/dokter-paging?name=${filter.query}&limit=10`)
  d_Dokter_Change.value = response.dokter.map((val: any) => (
    {
      id: val.id,
      namalengkap: val.namalengkap
    }
  ))
}

const changeisRanap = (v: any) => {
  item.value.isRanap = !item.value.isRanap;

  if (item.value.isRanap) {
    H.cacheHelper().set('lockedRoute', 'module-dashboard-rawat-inap');
    router.push({
      name: 'module-dashboard-rawat-inap'
    });
  } else {
    H.cacheHelper().set('lockedRoute', null);
    router.push({
      name: 'module-dashboard-rawat-jalan'
    });
  }
};

const saveChangeOrders = () => {

  isLoadSaveChangeDokter.value = true

  let objSave = {
    id_dokter: item.value.dokter_change.id,
    so_norec: so_norec_change_dokter.value
  }
  try {
    useApi().post('/dashboard/change-dokter-order', objSave).then((response) => {
      ModalChangeDokter.value = false
      isLoadSaveChangeDokter.value = false
    })
  } catch (error) {
    console.error('message', error);
    H.alert('error', "Something Went Wrong")
  }
}

const openModalChange = (e: any) => {
  so_norec_change_dokter.value = e
  ModalChangeDokter.value = true
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
  if (!filter || !filter.query) { d_Pegawai.value = []; return; }
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchDokter = async (filter: any) => {
  if (!filter || !filter.query) { d_Dokter.value = []; return; }
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
  page.value = currentPageOrder.value.page
  fetchDataOrder(0)
})
watch(currentPenunjang.value, () => {
  fetchPenunjang(0)
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

watch(item.value.filterRuangan, (newValue, oldValue) => {
  if (newValue == 302) {
    tomboldarah.value = true
  }
});
watch(penunjang, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    isLoading.value = true
    fetchPenunjang(newValue)
  }
});

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
  fetchPenunjang(0)
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
fetchDataOrder(0)
fetchDetail()
chartLayananByRuangan()
fetchPenunjang(0)
fetchPegawai()
fetchdDropdown()
fetchPasien()
qzService.connect()

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
