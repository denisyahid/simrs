<template>
  <section>
    <div class="lifestyle-dashboard lifestyle-dashboard-v4">
      <div class="columns">
        <div class="column is-4">
          <div class="soccer-dashboard">
            <div class="soccer-dashboard-inner">
              <div class="columns">
                <div class="column is-12">
                  <!--Widget-->
                  <div class="live-match">
                    <div class="head">
                      <h3 class="title is-5">Monitoring Pasien</h3>
                    </div>
                    <div class="match">
                      <div class="left">
                        <img class="team-logo" src="/images/avatars/label/sehat.png" alt="" />
                        <span class="team-name">Pasien Pulang</span>
                      </div>
                      <div class="center">
                        <span class="score">{{ item.totalPulang }}</span>
                        <span class="separator">:</span>
                        <span class="score">{{ item.totalRawat }}</span>
                      </div>
                      <div class="right">
                        <img class="team-logo" src="/images/avatars/label/sakit.png" alt="" />
                        <span class="team-name">Pasien Rawat</span>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12" v-if="kelompokUser && kelompokUser.toUpperCase().indexOf('DOKTER') > -1">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h3 class="title is-5 mb-2">Rekap Pendapatan
                  </h3>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <CardCountRev icon="/images/simrs/icon-antrian.png" straight :total="item.totalPasien"
                    label="Pasien" />
                </div>
                <div class="column is-6">
                  <CardCountRev icon="/images/simrs/icon-registrasi.png" straight :total="item.totalTindakan"
                    label="Tindakan" />
                </div>
                <div class="column is-12">
                  <CardCountRev icon="/images/simrs/icon-reservasi.png" straight
                    :total="H.formatRp(item.pendapatanJasa, 'Rp.')" label="Pendapatan" />
                </div>
              </div>
            </VCard>
          </div>

          <UIWidget class="search-widget" style="margin-top: 0.3rem;">
            <template #body>
              <div class="field" style="padding: 2px">
                <div class="control">
                  <input v-model="filter" class="input custom-text-filter" placeholder="Cari Dokter Jaga" />
                  <button class="searcv-button" @click="fetchDetail()">
                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                  </button>
                </div>
              </div>
            </template>
          </UIWidget>

          <div class="featured-authors">
            <!--Header-->
            <div class="featured-authors-header">
              <h3 class="dark-inverted">Jadwal Dokter</h3>
            </div>

            <div class="tile-grid tile-grid-v2">

              <!--List Empty Search Placeholder -->
              <VPlaceholderPage :class="[dataDokter.length !== 0 && 'is-hidden']" title=" Data Tidak Ditemukan"
                subtitle="Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt=""
                    style="margin-top:-4rem;" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt=""
                    style="margin-top:-4rem;" />
                </template>
              </VPlaceholderPage>

              <!--Tile Grid v1-->

              <TransitionGroup name="list" tag="div" class="columns is-multiline">
                <!--Grid item-->
                <div class="columns is-multiline p-2" style="max-height:500px; overflow: auto;">
                  <div v-for="item in dataDokter" :key="item.id" class="column is-12">
                    <div class="tile-grid-item" @click="detailDokter(item)">
                      <div class="tile-grid-item-inner">

                        <VAvatar size="small" picture="/images/avatars/svg/user2.svg" color="primary" squared
                          bordered />

                        <div class="meta">
                          <span class="dark-inverted">{{ item.namalengkap }}</span>
                          <span style="font-size: 0.9rem; padding: 2px;"> <i aria-hidden="true" class="iconify"
                              data-icon="feather:clock" style="padding-right: 3px;"></i> {{ item.jammulai }} s.d {{
                                item.jamakhir }}</span>

                        </div>
                        <VTag style="margin-left: 2rem;" color="info" label="Tag Label" rounded elevated> {{ item.hari
                          }}
                        </VTag>

                      </div>

                    </div>
                  </div>
                </div>
              </TransitionGroup>
            </div>
          </div>

        </div>
        <div class="column is-8">
          <div class="columns is-multiline">
            <!--Header-->
            <div class="column is-12" style="margin-left : 2rem;">
              <div class="illustration-header-2">
                <div class="header-image">
                  <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                    style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                </div>
                <div class="header-meta">
                  <h4 style="color:white"> <i class="fas fa-bed"></i> {{ item.departemen }} </h4>
                  <h3> Nurse Station </h3>
                  <p>
                    Selamat Datang, {{ userLogin.pegawai.namaLengkap }}
                  </p>
                  <VControl>
                    <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label" filter
                      placeholder="Pilih Ruangan" :maxSelectedLabels="3" style="display:flex"
                      @change="changeRuang(sourceRuangan)" />
                    <!-- <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" placeholder="Pilih ruangan"
                    :searchable="true" autocomplete="off" @select="changeRuang(item.filterRuangan)" /> -->
                  </VControl>
                  <VControl>
                    <VSwitchBlock v-if="kelompokUser && kelompokUser.toUpperCase().indexOf('DOKTER') > -1" @change.stop="changeisRajal($event)"
                      v-model="item.isRajal" :label="'Rawat Jalan'" color="danger" />
                  </VControl>
                </div>
              </div>
            </div>

            <!--Content-->
            <div class="column is-12" style="margin-left : 2rem;">

              <div class="writing-stats">
                <!--Stat-->
                <div class="writing-stat">
                  <span>Dokter Jaga</span>
                  <div v-if="isLoad">
                    <VPlaceload class="mx-2 mt-3" width="60%" />
                    <VIconBox color="blue" size="small" rounded style="margin-top: -1.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-user"></i>
                    </VIconBox>
                  </div>
                  <div v-else>
                    <span class="dark-inverted" style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);"> {{
                      item.jumlahDokter }} </span>
                    <VIconBox color="blue" size="small" rounded style="margin-top: -2.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-user"></i>
                    </VIconBox>
                  </div>
                </div>
                <!--Stat-->
                <div class="writing-stat">
                  <span>Pasien Rawat Inap</span>
                  <div v-if="isLoad">
                    <VPlaceload class="mx-2 mt-3" width="60%" />
                    <VIconBox color="danger" size="small" rounded style="margin-top: -1.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-user-check"></i>
                    </VIconBox>
                  </div>
                  <div v-else>
                    <span class="dark-inverted" style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);">
                      {{ totalData }}</span>
                    <VIconBox color="danger" size="small" rounded style="margin-top: -2.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-user-check"></i>
                    </VIconBox>
                  </div>
                </div>

                <!--Stat-->
                <div class="writing-stat">
                  <span>Bed Tersedia</span>
                  <div v-if="isLoad">
                    <VPlaceload class="mx-2 mt-3" width="60%" />
                    <VIconBox color="primary" size="small" rounded style="margin-top: -1.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-bed"></i>
                    </VIconBox>
                  </div>
                  <div v-else>
                    <span class="dark-inverted" style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);"> {{
                      item.totalBedIsi }}</span>
                    <VIconBox color="primary" size="small" rounded style="margin-top: -2.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-bed"></i>
                    </VIconBox>
                  </div>
                </div>

                <div class="writing-stat">
                  <span>Bed Terpakai</span>
                  <div v-if="isLoad">
                    <VPlaceload class="mx-2 mt-3" width="60%" />
                    <VIconBox color="purple" size="small" rounded style="margin-top: -1.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-bed"></i>
                    </VIconBox>
                  </div>
                  <div v-else>
                    <span class="dark-inverted" style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);"> {{
                      item.totalBedKosong }}</span>
                    <VIconBox color="purple" size="small" rounded style="margin-top: -2.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-bed"></i>
                    </VIconBox>
                  </div>
                </div>
              </div>
              <!-- Daftar Kamar -->
              <div class="featured-authors">
                <!--Header-->
                <!-- <div class="featured-authors-header mb-0">
                <h3 class="dark-inverted"> DAFTAR PASIEN {{ item.namaruangan ? item.namaruangan : '' }}</h3>
              </div> -->
                <div class="column">
                  <VControl class="is-pulled-right" style="font-weight: bold !important;">
                    <VSwitchBlock v-model="item.isPasien" label="Pasien Dokter" color="danger"
                      v-if="kelompokUser && kelompokUser.toUpperCase().indexOf('DOKTER') > -1" />
                  </VControl>
                </div>



                <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-users mr-2" aria-hidden="true"></i>
                      <span>Daftar Pasien</span>
                      <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 0">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          
                        </div>
                        <div class="column is-5"
                        style="margin-left: 0.5rem;margin-bottom: 20px;padding: 0px;margin-top: -3.5rem;"
                        v-if="userLogin.kelompokUser.kelompokUser.includes('dokter')">
                          <VButton color="success" class="search-button"
                            @click="openModalIntruksi = true; notifBottom= false;" :loading="isLoadingTT" light> Intruksi
                            <Badge :value="listIntruksi.length ?? 0" severity="danger" class="ml-2" />
                          </VButton>
                          <VButton color="success" class="search-button ml-1"
                            @click="openModalIntruksiBersama = true; notifBottomBersama= false;" :loading="isLoadingTT" light> Intruksi Rawat Bersama
                            <Badge :value="listIntruksiBersama.length ?? 0" severity="danger" class="ml-2" />
                          </VButton>
                        </div>
                      </div>
                      <div class="list-view list-view-v3">
                        <div class="search-menu" style="margin-bottom : 1rem;">

                          <div class="search-location" style="width: 100%">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                              v-model="item.search" v-on:keyup.enter="fetchData()" />
                          </div>
                          <VButton raised class="search-button" :loading="isLoading" @click="fetchData()"> Cari Data
                          </VButton>
                        </div>
                        <VPlaceholderPage :class="[dataPasien.length !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien Rawat Inap Saat Ini."
                          subtitle="Silakan Registrasikan Pasien Sebagai Rawat Inap." larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                              alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner" style="max-height:500px; min-height: 300PX;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in dataPasien" :key="item.id" class="list-view-item">
                             <!-- <pre>{{ item.rawatgabung }}</pre> -->
                              <div class="list-view-item-inner">
                                <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" squared
                                  bordered />
                                <div class="meta-left">
                                  <h3>
                                    {{ item.namapasien }} |
                                    <VTag v-if="item.kelompokpasien != null" class="mt-3" :label="item.kelompokpasien"
                                      :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'" rounded /> |
                                    <i class="bulet fas fa-circle"></i>
                                    {{ item.namakelas }} |
                                    <VTag v-if="item.kelompokpasien != null" class="mt-3"
                                      :label="item.namakelasr ? 'Kelas Rawat : ' + item.namakelasr : ''" color="info"
                                      rounded />
                                    | {{ item.kebangsaan }}
                                  </h3>
                                  <span>
                                    <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                    <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.namaruangan }} - {{ item.reportdisplay }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                    <span>{{ H.formatDateIndo(item.tglregistrasi) }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noregistrasi }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nocm }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nobpjs }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                    <span>{{ item.noidentitas }}</span>
                                  </span><br>
                                  <span style="font-weight: bold;">
                                    DPJP :
                                    <i aria-hidden="true" class="iconify"></i>
                                    <span>{{ item.namalengkap }}</span>
                                  </span><br>
                                  <span style="font-weight: bold;" v-if="item.nama">
                                    Dokter Rawat Bersama :
                                    <i aria-hidden="true" class="iconify"></i>
                                    <span>{{ item.nama }}</span>
                                  </span>
                                  <!-- <span>
                                  <i aria-hidden="true" class="iconify" data-icon="healthicons:doctor-male-outline"></i>
                                  <span>{{ item.namalengkap }}</span>
                                </span> -->
                                  <div>
                                    <i class="fas fa-sort-amount-down-alt mr-2 mt-1" aria-hidden="true"></i>
                                    <VTag :label="item.selisihwaktu" :color="'warning'" class="mr-2 mt-3-min"
                                      v-tooltip.bubble="'LAMA RAWAT'" />
                                    <VTag :label="item.rawatgabung" :color="'info'" class="mr-2 mt-3-min"
                                      v-if="item.rawatgabung != false && item.rawatgabung != null" />
                                    <VTag class="is-purple tag mr-2 mt-3-min" v-if="item.iskelastitip != null && item.iskelastitip != false">
                                      Kelas Titip
                                    </VTag>
                                    <VTag class="is-info tag mr-2 mt-3-min" v-if="item.isnaikkelas != null && item.isnaikkelas != false">
                                      Naik Kelas
                                    </VTag>
                                  </div>
                                </div>
                                <div class="meta-right">
                                  <div class="buttons">
                                    <RouterLink :to="{
                                      // H.cacheHelper().set('xxx_cache_menu', undefined)
                                      name: 'module-emr-profile-pasien',
                                      query: {
                                        nocmfk: item.nocmfk,
                                        norec_pd: item.norec_pd,
                                        norec_apd: item.norec_apd,
                                      }
                                    }">
                                      <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                        @click="emr(item)" v-tooltip.bottom.left="'EMR'">
                                      </VIconButton>
                                    </RouterLink>
                                  </div>
                                  <div class="buttons">
                                    <RouterLink :to="{
                                      name: 'module-rawat-inap-pindah-pulang',
                                      query: {
                                        nocmfk: item.nocmfk,
                                        norec_pd: item.norec_pd,
                                        departemenfk: item.objectdepartemenfk,
                                      }
                                    }">
                                      <VIconButton v-tooltip.bottom.left="'Pulang atau Pindah'" label="Bottom Left"
                                        color="danger" circle icon="fas fa-home" />
                                    </RouterLink>
                                  </div>

                                  <VDropdown icon="feather:more-vertical" spaced right
                                    v-tooltip.bottom.left="'TINDAKAN PASIEN'">
                                    <template #content>
                                      <a role="menuitem" @click="openModalDpjp(item)" class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-user-plus" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Ubah Dokter DPJP & Dokter Rawat Bersama</span>
                                        </div>
                                      </a>
                                      <a role="menuitem" @click="openModalUbahBed(item)" class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-bed" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Ubah Bed Pasien</span>
                                        </div>
                                      </a>
                                      <a role="menuitem" @click="detailRegistrasi(item)" class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-th-list" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Detail Registrasi</span>
                                        </div>
                                      </a>
                                      <a role="menuitem" @click="gotoFormInsidenInter(item)"
                                        class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-th-list" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Input Insiden Internal</span>
                                        </div>
                                      </a>
                                      <a role="menuitem" href="#" class="dropdown-item is-media"
                                        @click="cetakGelangPasien(item)">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Cetak Gelang Pasien</span>
                                        </div>
                                      </a>
                                      <a role="menuitem" href="#" class="dropdown-item is-media"
                                        @click="cetakSEP(item)">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Cetak SEP</span>
                                        </div>
                                      </a>
                                      <a role="menuitem" href="#" class="dropdown-item is-media"
                                        @click="cetakLabelPasien(item)">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Cetak Label Pasien</span>
                                        </div>
                                      </a>
                                      <!-- <a role="menuitem" @click="showModalSKSakit(item)" class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Surat Sakit</span>
                                        </div>
                                      </a> -->
                                      <a role="menuitem" @click="cetakLembarKeluar(item)"
                                        class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Lembar Masuk dan Keluar</span>
                                        </div>
                                      </a>
                                      <!-- <a role="menuitem" href="#" class="dropdown-item is-media"
                                        @click="showModalSKDokter(item)">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Surat Sehat</span>
                                        </div>
                                      </a> -->
                                      <a role="menuitem" @click="suratKontrol(item)" class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Surat Kontrol</span>
                                        </div>
                                      </a>
                                      <a v-if="item.norec_pd != null" @click="cetakSuratKeteranganDokter(item)"
                                        role="menuitem" class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Surat Dokter</span>
                                        </div>
                                      </a>
                                      <a role="menuitem" href="#" class="dropdown-item is-media"
                                        @click="modalRawatInap(item)">
                                        <div class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Cetak Bukti Rawat Inap</span>
                                        </div>
                                      </a>
                                      <a role="menuitem" href="#" class="dropdown-item is-media"
                                        @click="BatalRawatInap(item)" style="background: rgb(190, 15, 15);">
                                        <div class="icon">
                                          <i aria-hidden="true" class="fas fa-window-close"
                                            style="color: antiquewhite;"></i>
                                        </div>
                                        <div class="meta">
                                          <span style="color:antiquewhite !important">Batal Rawat Inap</span>
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
                    </div>
                  </TabPanel>
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-user-check mr-2" aria-hidden="true"></i>
                      <span>Pasien Pulang</span>
                      <Badge :value="dataPasienPulang.length" v-if="dataPasienPulang.length > 0" severity="danger"
                        class="ml-2" />
                    </template>
                    <div v-if="activeTab == 1">
                      <div class="list-view list-view-v3">
                        <div class="search-menu" style="margin-bottom : 1rem;">
                          <div class="column is-6" style="margin-top: 10px;">
                            <VDatePicker v-model="item.periodePulang" is-range color="pink" trim-weeks>
                              <template #default="{ inputValue, inputEvents }">
                                <VField addons>
                                  <VControl icon="feather:calendar">
                                    <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                  </VControl>
                                  <VControl>
                                    <VButton static icon="feather:arrow-right" />
                                  </VControl>
                                  <VControl subcontrol icon="feather:calendar">
                                    <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </div>
                          <div class="search-location" style="width: 100%">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Pencarian ..." v-model="item.qsearch"
                              v-on:keyup.enter="fetchPasienPulang()" />
                          </div>

                          <VButton color="primary" raised class="search-button" @click="fetchPasienPulang()"
                            :loading="isLoadingTT"> Cari Data </VButton>
                        </div>

                        <VPlaceholderPage :class="[dataPasienPulang.length !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien Pulang Saat Ini." subtitle="Silakan Pulangkan Pasien dari Rawat Inap."
                          larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                              alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner" style="max-height:300px;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in dataPasienPulang" :key="item.id" class="list-view-item">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" squared
                                  bordered />
                                <div class="meta-left">
                                  <h3>
                                    {{ item.namapasien }}
                                  </h3>
                                  <span>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.namaruangan }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nocm }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noregistrasi }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <VTag :color="item.color" :label="item.status" />
                                    <br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:"></i>
                                    <span>
                                      {{ H.formatDateIndo(item.tglpulang) }}
                                    </span>

                                  </span>
                                  <div>
                                    <i class="fas fa-sort-amount-down-alt mr-2 mt-1" aria-hidden="true"></i>
                                    <VTag :label="item.lamarawat" :color="'warning'" class="mr-2 mt-3-min"
                                      v-tooltip.bubble="'LAMA RAWAT'" />
                                  </div>

                                </div>

                                <div class="meta-right">
                                  <div class="buttons">
                                    <!-- <RouterLink :to="{
                                      // H.cacheHelper().set('xxx_cache_menu', undefined)
                                      name: 'module-emr-profile-pasien',
                                      query: {
                                        nocmfk: item.nocmfk,
                                        norec_pd: item.norec_pd,
                                        norec_apd: item.norec_apd,
                                      }
                                    }"> -->
                                    <VIconButton color="primary" class="mr-3" circle icon="fas fa-stethoscope" outlined
                                      raised v-tooltip.bottom.left="'EMR'" @click="emr(item)">
                                    </VIconButton>
                                    <!-- </RouterLink> -->
                                  </div>

                                  <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="primary"
                                    circle outlined raised v-tooltip.bottom="'Aksi'" @click="toggle($event, item)">
                                  </VIconButton>

                                </div>

                              </div>
                            </div>
                          </TransitionGroup>
                        </div>

                      </div>
                    </div>
                  </TabPanel>
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-person-booth" aria-hidden="true"></i>
                      <span class="ml-2">Pasien Pindah</span>
                      <Badge :value="sourceMutasi.length" v-if="sourceMutasi.length > 0" severity="danger"
                        class="ml-2" />
                    </template>
                    <div v-if="activeTab == 2">
                      <div class="list-view list-view-v3">
                        <div class="search-menu" style="margin-bottom : 1rem;">
                          <div class="column is-6" style="margin-top: 10px;">
                            <VDatePicker v-model="item.periodeMutasi" is-range color="pink" trim-weeks>
                              <template #default="{ inputValue, inputEvents }">
                                <VField addons>
                                  <VControl icon="feather:calendar">
                                    <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                  </VControl>
                                  <VControl>
                                    <VButton static icon="feather:arrow-right" />
                                  </VControl>
                                  <VControl subcontrol icon="feather:calendar">
                                    <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker>
                          </div>
                          <div class="search-location" style="width: 100%">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Pencarian ..." v-model="item.qsearchmutasi"
                              v-on:keyup.enter="fetchPasienMutasi()" />
                          </div>

                          <VButton color="primary" raised class="search-button" @click="fetchPasienMutasi()"
                            :loading="isLoadingTT"> Cari Data </VButton>
                        </div>

                        <VPlaceholderPage :class="[sourceMutasi.length !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien Pulang Saat Ini." subtitle="Silakan Pulangkan Pasien dari Rawat Inap."
                          larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                              alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner" style="max-height:300px;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in sourceMutasi" :key="item.id" class="list-view-item">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" squared
                                  bordered />
                                <div class="meta-left">
                                  <h3>
                                    {{ item.namapasien }}
                                  </h3>
                                  <span>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.ruanganasal }}</span>
                                    <i class="fas fa-running ml-2 mr-2" aria-hidden="true"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.ruangansekarang }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nocm }}</span>
                                    <br>
                                  </span>
                                  <div>
                                    <i class="fas fa-sort-amount-down-alt mr-2 mt-1" aria-hidden="true"></i>
                                    <VTag :label="item.lamarawat" :color="'warning'" class="mr-2 mt-3-min"
                                      v-tooltip.bubble="'LAMA RAWAT'" />
                                    <VTag color="primary" :label="H.formatDateIndo(item.tglmasuk)"
                                      v-tooltip.bubble="'Tanggal Mutasi'" />
                                  </div>

                                </div>

                                <div class="meta-right">
                                  <div class="buttons">
                                    <RouterLink :to="{
                                      // H.cacheHelper().set('xxx_cache_menu', undefined)
                                      name: 'module-emr-profile-pasien',
                                      query: {
                                        nocmfk: item.nocmfk,
                                        norec_pd: item.norec_pd,
                                        norec_apd: item.norec_apd,
                                      }
                                    }">
                                      <VIconButton color="danger" class="mr-3" circle icon="fas fa-stethoscope" outlined
                                        raised v-tooltip.bottom.left="'EMR'">
                                      </VIconButton>
                                    </RouterLink>
                                  </div>
                                </div>

                              </div>
                            </div>
                          </TransitionGroup>
                        </div>

                      </div>
                    </div>
                  </TabPanel>
                </TabView>
                <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                  :total-items="totalPaging" :max-links-displayed="5">
                  <template #before-pagination>
                  </template>
                  <template #before-navigation>
                    <VFlex class="mr-4 mt-1" column-gap="1rem">
                      <VField>
                      </VField>
                      <VField>
                        <VControl>
                          <div class="select is-rounded">
                            <select v-model="currentPage.limit">
                              <option :value="1">1 results per page</option>
                              <option :value="5">5 results per page</option>
                              <option :value="10">10 results per page</option>
                              <option :value="15">15 results per page</option>
                              <option :value="25">25 results per page</option>
                              <option :value="50">50 results per page</option>
                              <option :value="100">100 results per page</option>
                            </select>
                          </div>
                        </VControl>
                      </VField>
                    </VFlex>
                  </template>
                </VFlexPagination>
              </div>
              <div class="featured-authors" style="margin-top : 2rem;">
                <!--Header-->
                <div class="featured-authors-header">

                  <h3 class="dark-inverted"> DAFTAR KAMAR </h3>
                  <UIWidget class="search-widget" style="width: 70%; margin-left: 10rem; padding: 20px;">
                    <template #body>
                      <div class="field" style="padding: 2px">
                        <div class="control">
                          <input v-model="filters" class="input custom-text-filter" placeholder="Cari Kamar" />
                          <button class="searcv-button">
                            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                          </button>
                        </div>
                      </div>
                    </template>
                  </UIWidget>
                </div>
                <div class="tile-grid tile-grid-v2">


                  <!--List Empty Search Placeholder -->
                  <VPlaceholderPage :class="[dataKamar.length !== 0 && 'is-hidden']"
                    title="We couldn't find any matching results." subtitle="Too bad. Looks like we couldn't find any matching results for the
             search terms you've entered. Please try different search terms or
             criteria." larger>
                    <template #image>
                      <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                    </template>
                  </VPlaceholderPage>

                  <!--Tile Grid v1-->

                  <TransitionGroup name="list" tag="div" class="columns is-multiline">
                    <!--Grid item-->
                    <div class="columns is-multiline p-2" style="min-height: 200px;max-height:400px;overflow: auto;">
                      <div v-for="item in dataKamar" :key="item.id" class="column is-6">
                        <div class="tile-grid-item" @click="detailKamar(item)">
                          <div class="tile-grid-item-inner">

                            <VAvatar size="small" picture="/images/avatars/svg/roo.png" color="primary" squared
                              bordered />

                            <div class="meta">
                              <span class="dark-inverted">{{ item.namakamar }}</span>
                              <span>
                                <span>{{ item.namakelas }}</span>

                              </span>

                            </div>
                            <VTag :label="item.kosong" color="info" style="margin-left: auto;" rounded />

                          </div>

                        </div>
                      </div>
                    </div>
                  </TransitionGroup>
                </div>


              </div>


            </div>

            <!--Content-->

          </div>
        </div>

      </div>
    </div>

    <template>
      <VButton bold @click="modalDetailDokter = true"> Open Modal </VButton>

      <VModal :open="modalDetailDokter" actions="right" @close="modalDetailDokter = false">
        <template #content>
          <VPlaceholderSection :title="item.objectpegawaifk" />

          <form class="modal-form">
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField>
                  <VLabel>Ruang Praktek</VLabel>
                  <VControl icon="feather:home">
                    <VInput type="textarea" v-model="item.objectruanganfk" placeholder="Asal Produk" class="is-rounded"
                      style="background-color: #0398E2; color:white;" disabled />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField>
                  <VLabel>Hari</VLabel>
                  <VControl icon="feather:bookmark">
                    <VInput type="textarea" v-model="item.hari" class="is-rounded"
                      style="background-color: #0398E2; color:white;" disabled />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField>
                  <VLabel>Jam Buka</VLabel>
                  <VControl icon="feather:clock">
                    <VInput type="textarea" v-model="item.jammulai" class="is-rounded"
                      style="background-color: #0398E2; color:white;" disabled />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField>
                  <VLabel>Jam Tutup</VLabel>
                  <VControl icon="feather:clock">
                    <VInput type="textarea" v-model="item.jamakhir" class="is-rounded"
                      style="background-color: #0398E2; color:white;" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
          </form>

        </template>

      </VModal>
    </template>

    <!-- Detail Produk-->
    <VModal :open="modalDetailProduk" title="Detail Stok Produk" actions="right" @close="modalDetailProduk = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VLabel>Nama Produk</VLabel>
                <VControl icon="fas fa-box">

                  <VInput type="textarea" v-model="item.objectprodukfk" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Asal Produk</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="textarea" v-model="item.objectasalprodukfk" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Jumlah Stok</VLabel>
                <VControl icon="fas fa-database">
                  <VInput type="textarea" v-model="item.qtyproduk" placeholder="Jumlah" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Harga Beli</VLabel>
                <VControl icon="fas fa-cart-plus" aria-hidden="true">
                  <VInput type="textarea" v-model="item.harganetto1" placeholder="Harga Beli" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Harga Jual</VLabel>
                <VControl icon="fas fa-shopping-cart" aria-hidden="true">
                  <VInput type="textarea" v-model="item.harganetto2" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
    </VModal>
    <!-- Detail Kamar-->
    <VModal :open="modalDetailKamar" title="Detail Kamar" actions="right" @close="modalDetailKamar = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-6">
              <VBlock :title="item.isi" subtitle="Bed Tersedia" center m-responsive t-responsive
                style=" border-style: outset; padding: 15px; border-radius: 20px;">
                <template #icon>
                  <VIconBox color="success" size="medium" rounded>
                    <i aria-hidden="true" class="fas fa-bed"></i>
                  </VIconBox>
                </template>
              </VBlock>
            </div>
            <div class="column is-6">
              <VBlock :title="item.kosong" subtitle="Bed Terpakai" center m-responsive t-responsive
                style=" border-style: outset; padding: 15px; border-radius: 20px;">
                <template #icon>
                  <VIconBox color="danger" size="medium" rounded>
                    <i aria-hidden="true" class="fas fa-bed"></i>
                  </VIconBox>
                </template>
              </VBlock>
            </div>

            <div class="column is-12">
              <VField label="Kamar">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namakamar" placeholder="Nama Kamar" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>

            <div class="column is-12">
              <VField label="Kelas">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namakelas" placeholder="Kelas" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
    </VModal>

    <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
      @close="modalFilter = false">
      <template #content>
        <form class="modal-form">
          <div class="columns">
            <div class="column is-12" style="text-align: center">
              <VField class="is-centered">
                <v-date-picker v-model="item.filterTgl" class="is-centered" trim-weeks :max-date="new Date()" />
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:search" @click="reload()" :loading="isLoading" color="primary" raised>
          Filter</VButton>
      </template>
    </VModal>

    <VModal :open="modalSKDokter" title="Filter Periode" :noclose="true" size="small" actions="right"
      @close="modalSKDokter = false">
      <template #content>
        <form class="modal-form">
          <div class="column">
            <VField label="Tinggi Badan">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.tinggiBadan" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <VField label="Berat Badan">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.beratBadan" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <VField label="Tekanan Darah">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.tekananDarah" class="is-rounded" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <VField label="Denyut Nadi">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.denyutNadi" class="is-rounded" />
              </VControl>
            </VField>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:" :loading="isLoading" color="primary" raised @click="saveSKDokter(item)">Simpan
        </VButton>
      </template>
    </VModal>
    <!-- modal pengajuan surat rawat inap -->
    <VModal :open="modalSuratRawatInap" title="Cetak Surat Rawat Inap" :noclose="true" size="medium" actions="right"
      @close="modalSuratRawatInap = false">
      <template #content>
        <form class="modal-form">
          <div class="columns">
            <div class="column is-12">
              <VField class="is-centered">
                <VControl>
                  <VInput type="text" v-model="dataPenangguangJawab.penanggungJawab" placeholder="Penaggung Jawab"
                    required />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-12">
              <VField class="is-centered">
                <VControl>
                  <VInput type="text" v-model="dataPenangguangJawab.alamat" placeholder="Alamat" required />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <!-- <VButton icon="feather:search" :loading="isLoading" color="primary" raised @click="saveSKDokter(item)">
        Simpan</VButton> -->
        <VButton icon="feather:printer" @click="printSuratRawatInap()" :loading="isLoading" color="primary" raised>
          Cetak</VButton>
      </template>
    </VModal>
    <!-- modal pengajuan surat rawat inap -->
    <!-- <VModal :open="modalSuratKematian" title="Cetak Surat Kematian" :noclose="true" size="medium" actions="right"
      @close="modalSuratKematian = false">
      <template #content>
        <form class="modal-form">
          <div class="columns">
            <div class="column is-12 mb-5">
              <VField>
                <VDatePicker v-model="input.tanggalmeninggal" mode="date" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-12">
              <VField class="is-centered">
                <VControl>
                  <VInput type="text" v-model="input.keterangan" placeholder="Keterangan" required />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:printer" @click="saveSuratKematian()" :loading="isLoading" color="primary" raised>
          Cetak</VButton>
      </template>
    </VModal> -->

    <VModal :open="modalSuratSakit" title="Cetak Surat Keterangan Sakit" :noclose="true" size="small" actions="right"
      @close="modalSuratSakit = false">
      <template #content>
        <form class="modal-form">
          <div class="column is-12 pl-2 pr-2 pt-0 pb-3">
            <VField label="Hasil Pemeriksaan">
              <VControl>
                <VTextarea rows="2" v-model="item.hasilPeriksa"
                  placeholder="Karena sakitnya, yang bersangkutan diharapkan" />
              </VControl>
            </VField>
          </div>
          <div class="column pl-2 pr-2 pt-2 pb-3">
            <div class="columns is-multiline pl-1 pr-1">
              <div class="column p-2">
                <VField label="Tanggal awal">
                  <VDatePicker v-model="item.tglAwalSakit" mode="date" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column p-2">
                <VField label="Tanggal Akhir">
                  <VDatePicker v-model="item.tglAkhirSakit" mode="date" style="width: 100%" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
          </div>

          <div class="column is-12 pl-2 pr-2 pt-0 pb-3">
            <VField label="Catatan">
              <VControl>
                <VTextarea rows="2" v-model="item.catatanSakit" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12 pl-2 pr-2 pt-0 pb-3">
            <VField label="Diagnosa">
              <VControl>
                <VTextarea rows="2" v-model="item.diagnosaSakit" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12 pl-2 pr-2 pt-0 pb-3">
            <VField label="Indikasi kembali ke RS">
              <VControl>
                <VTextarea rows="2" v-model="item.indikasiKembali" />
              </VControl>
            </VField>
          </div>
          <div class="column pl-2 pr-2 pt-0 pb-0">
            <VField label="Kembali ke rs">
              <VDatePicker v-model="item.tglKembaliRS" mode="date" style="width: 100%" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </form>
      </template>
      <template #action>
        <VButton @click="saveSKSakit()" :loading="isLoading" color="primary" raised>
          Simpan</VButton>
      </template>
    </VModal>
    <!-- modal uabh dokter dpjp-->
    <Dialog v-model:visible="modalChangeDokter" modal header="Form Dokter DPJP & Dokter Rawat Bersama"
      :style="{ width: '25vw' }">
      <div class="column">
        <span style="font-weight: 500;">Dokter I </span>
        <VField class="is-autocomplete-select pt-3">
          <VControl icon="feather:search">
            <AutoComplete v-model="item.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="ketik Nama Dokter" />
          </VControl>
        </VField>
      </div>
      <div class="column">
        <span style="font-weight: 500;">Dokter II </span>
        <VField class="is-autocomplete-select pt-3">
          <VControl icon="feather:search">
            <AutoComplete v-model="item.dokterPemeriksa2" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="ketik Nama Dokter" />
          </VControl>
        </VField>
      </div>
      <div class="columns" style="align-items: center;">
        <div class="column is-10">
          <div v-for="(dokter, index) in item.dokterPemeriksaList" :key="index" class="column" style="display: flex; align-items: center;">
            <div class="column is-12">
              <span style="font-weight: 500;">Dokter {{ toRoman(index + 3) }}</span>
              <VField class="is-autocomplete-select pt-3">
                <VControl icon="feather:search">
                  <AutoComplete
                    v-model="item.dokterPemeriksaList[index]"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="ketik Nama Dokter"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <!-- Add and Remove Buttons (always visible) -->
        <div class="column is-2" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
          <VIconButton 
            type="button" 
            raised 
            circle 
            icon="feather:plus" 
            @click="addNewItem(item)" 
            color="info" 
            class="mb-2">
          </VIconButton>
          <VIconButton 
            v-if="item.dokterPemeriksaList.length > 0" 
            type="button" 
            raised 
            circle 
            icon="feather:trash" 
            @click="removeItem(item, item.dokterPemeriksaList.length - 1)" 
            color="danger">
          </VIconButton>
        </div>
      </div>
      
      <template #footer>
        <VButton color="danger" icon="pi pi-times" outlined raised @click="modalChangeDokter = false"> Batal </VButton>
        <VButton color="primary" icon="pi pi-check" raised @click="saveChangeDokter()" :loading="btnLoadSimpan" class="ml-2"> Update
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="openModalIntruksi" maximizable modal header="Header" :style="{ width: '75rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
      <template #header>
        <div class="inline-flex items-center justify-center gap-2">
          <span class="font-bold whitespace-nowrap">List Intruksi</span>
        </div>
      </template>
      <table class="table is-hoverable is-fullwidth" v-if="!isLoading">
        <thead>
          <tr>
            <th scope="col">
              Tanggal
            </th>
            <th scope="col">
              NRM
            </th>
            <th scope="col">
              NoReg
            </th>
            <th scope="col">
              Section
            </th>
            <th scope="col">
              Nama
            </th>
            <th scope="col">
              PPA
            </th>
            <th scope="col">
              Catatan Perkembangan
            </th>
            <th scope="col" class="is-end">
              <div class="dark-inverted is-flex is-justify-content-flex-end">
                Status
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="listIntruksi.length > 0" v-for="intru in listIntruksi">
            <td>
              {{ H.formatDate(intru.details.created_at, 'YYYY-MM-DD') }}
            </td>
            <td>{{ intru.nocm }}</td>
            <td>{{ intru.noregistrasi }}</td>
            <td>{{ intru.namaruangan }}</td>
            <td>{{ intru.namapasien }}</td>
            <td>{{ intru.namaPPA }}</td>
            <td>
              <p>
                <span style="font-weight: bold;">S</span> : {{ intru.details.S ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">O</span> : {{ intru.details.O ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">A</span> : {{ intru.details.A ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">P</span> : {{ intru.details.P ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">Intruksi</span> :
                {{ intru.intruksi ?? '' }}&nbsp;(<span style="font-weight: bold;">{{
                  intru.namaDokter ?? '' }}</span>)
              </p>
              
            </td>
            <td class="is-end">
              <div class="is-flex is-justify-content-flex-end">
                <VButton type="button" color="danger" rounded circle v-tooltip.bottom.left="'Klik untuk Verifikasi'"
                  @click="verifCPPT(intru)" :loading="isLoading">
                  Belum Verif
                </VButton>
                <!-- <VTag class="ml-1 mb-1" v-tooltip="'klik untuk Verifikasi'"
                  color="warning" label="Belum Verifikasi"
                  rounded v-if="!intru.isconfirm"/> -->
                <VTag class="ml-1 mb-1" color="success" label="Terverifikasi" rounded v-if="intru.isconfirm" />
                <!-- <FlexTableDropdown /> -->
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <VPlaceload v-if="isLoading" height="200px" class="mx-2" />
      <br>
      <VPlaceload v-if="isLoading" height="200px" class="mx-2" />
      <p v-if="!isLoading && listIntruksi.length == 0">
        Tidak ada Data
      </p>
    </Dialog>

    <Dialog v-model:visible="openModalIntruksiBersama" maximizable modal header="Header" :style="{ width: '75rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
      <template #header>
        <div class="inline-flex items-center justify-center gap-2">
          <span class="font-bold whitespace-nowrap">List Intruksi</span>
        </div>
      </template>
      <table class="table is-hoverable is-fullwidth" v-if="!isLoading">
        <thead>
          <tr>
            <th scope="col">
              Tanggal
            </th>
            <th scope="col">
              NRM
            </th>
            <th scope="col">
              NoReg
            </th>
            <th scope="col">
              Section
            </th>
            <th scope="col">
              Nama
            </th>
            <th scope="col">
              PPA
            </th>
            <th scope="col">
              Catatan Perkembangan
            </th>
            <th scope="col" class="is-end">
              <div class="dark-inverted is-flex is-justify-content-flex-end">
                Status
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="listIntruksiBersama.length > 0" v-for="intru in listIntruksiBersama">
            <td>
              {{ H.formatDate(intru.details.created_at, 'YYYY-MM-DD') }}
            </td>
            <td>{{ intru.nocm }}</td>
            <td>{{ intru.noregistrasi }}</td>
            <td>{{ intru.namaruangan }}</td>
            <td>{{ intru.namapasien }}</td>
            <td>{{ intru.namaPPA }}</td>
            <td>
              <p>
                <span style="font-weight: bold;">S</span> : {{ intru.details.S ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">O</span> : {{ intru.details.O ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">A</span> : {{ intru.details.A ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">P</span> : {{ intru.details.P ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">Intruksi Rawat Bersama</span> :
                {{ intru.intruksi ?? '' }}&nbsp;(<span style="font-weight: bold;">{{
                  intru.namaDokter ?? '' }}</span>)
              </p>
              
            </td>
            <td class="is-end">
              <div class="is-flex is-justify-content-flex-end">
                <VButton type="button" color="danger" rounded circle v-tooltip.bottom.left="'Klik untuk Verifikasi'"
                  @click="verifCPPTRaber(intru)" :loading="isLoading">
                  Belum Verif
                </VButton>
                <!-- <VTag class="ml-1 mb-1" v-tooltip="'klik untuk Verifikasi'"
                  color="warning" label="Belum Verifikasi"
                  rounded v-if="!intru.isconfirm"/> -->
                <VTag class="ml-1 mb-1" color="success" label="Terverifikasi" rounded v-if="intru.isconfirm" />
                <!-- <FlexTableDropdown /> -->
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <VPlaceload v-if="isLoading" height="200px" class="mx-2" />
      <br>
      <VPlaceload v-if="isLoading" height="200px" class="mx-2" />
      <p v-if="!isLoading && listIntruksiBersama.length == 0">
        Tidak ada Data
      </p>
    </Dialog>

    <Sidebar v-model:visible="notifBottom" header="Bottom Sidebar" position="bottom" style="height: auto">
      <template #header>
        <div class="flex align-items-center gap-2 mx-auto" style="justify-content: center;">
          <span class="font-bold">Terdapat {{ listIntruksi.length ?? 0 }} Intruksi yang belum di verifikasi, silahkan
            verifikasi intruksi yang diberikan</span>
        </div>
      </template>
      <div class="flex align-items-center gap-2 mx-auto" style="justify-content: center;">
        <VButton color="primary" style="height: 38px;" raised class="text-center" :loading="isLoading"
          @click="openModalIntruksi = true; notifBottom = false;">
          Lihat Intruksi
        </VButton>
      </div>
    </Sidebar>

    <Sidebar v-model:visible="notifBottomBersama" header="Bottom Sidebar" position="bottom" style="height: auto">
      <template #header>
        <div class="flex align-items-center gap-2 mx-auto" style="justify-content: center;">
          <span class="font-bold">Terdapat {{ listIntruksiBersama.length ?? 0 }} Intruksi Rawat Bersama yang belum di verifikasi, silahkan
            verifikasi intruksi yang diberikan</span>
        </div>
      </template>
      <div class="flex align-items-center gap-2 mx-auto" style="justify-content: center;">
        <VButton color="primary" style="height: 38px;" raised class="text-center" :loading="isLoading"
          @click="openModalIntruksiBersama = true; notifBottomBersama = false;">
          Lihat Intruksi
        </VButton>
      </div>
    </Sidebar>

    <OverlayPanel ref="op">
      <!-- <VButton color="primary" icon="fas fa-door-open" outlined raised @click="batalPulang(item)">Batal
      Pulang</VButton>
    <VIconButton v-tooltip.bottom="'Billing'" label="Bottom Left" @click="billing(item)" color="danger" circle
      icon="lnir lnir-file-name" />
    <VIconButton v-tooltip.bottom="'Surat Kematian'" label="Bottom Left" v-if="item.status == 'Meninggal'"
      @click="saveSuratKematian(item)" color="primary" circle icon="lnir lnir-file-name" /> -->

      <VButton type="button" icon="fas fa-th-list" class="mr-2" circle outlined color="primary" raised
        @click="detailRegistrasi(selectedItem)">
        Detail Registrasi
      </VButton>
      <VButton type="button" icon="fas fa-door-open" class="mr-2" circle outlined color="warning" raised
        @click="batalPulang(selectedItem)">
        Batal Pulang
      </VButton>
      <VButton type="button" icon="fas fa-th-list" class="mr-2" circle outlined color="primary" raised
        @click="billing(selectedItem)">
        Billing
      </VButton>
      <VButton type="button" icon="fas fa-clipboard-check" :disabled="!selectedItem.tglmeninggal" class="mr-2" circle
        outlined color="danger" :loading="selectedItem.loadingBtnCetak" raised @click="saveSuratKematian(selectedItem)">
        Surat Meninggal
      </VButton>
    </OverlayPanel>
    <VModal :open="modalInput" title="Pindah Bed" :noclose="false" size="big" actions="right"
      @close="modalInput = false">
      <template #content>
        <!-- <pre>{{ itemSource }}</pre> -->
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VCard>
                <div class="columns is-multiline p-1">
                  <div class="column is-4">
                    <VField label="Ruangan Rencana Pindah " class="is-rounded-select is-autocomplete-select"
                      v-slot="{ id }">
                      <VControl icon="feather:list" fullwidth>
                        <VInput type="text" v-model="item.namaruanganPindah" class="is-rounded" disabled />
                        <!-- <Multiselect mode="single" v-model="item.namaruanganPindah" :options="d_Ruangan"
                          placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                          @select="changeRuangPindah(item.namaruanganPindah)" /> -->
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Kelas Kamar " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:list" fullwidth>
                        <Multiselect mode="single" v-model="item.namakelasrawat" :options="d_Kelas"
                          placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                          @select="changeKelas(item.namakelasrawat, itemSource.isRG)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="item.namakelasrawat">
                    <VField label="Kelas Rawat/Ditanggung " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:list" fullwidth>
                        <Multiselect mode="single" v-model="item.namakelas" :options="d_KelasAll"
                          placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Kamar" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:list" fullwidth>
                        <Multiselect mode="single" v-model="item.kamar" :options="d_Kamar" placeholder="Pilih data"
                          :searchable="true" :attrs="{ id }" autocomplete="off" @select="changeKamar(item.kamar)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Nomor Tempat Tidur" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:list" fullwidth>
                        <Multiselect mode="single" v-model="item.bed" :options="d_TempatTidur" placeholder="Pilih data"
                          :searchable="true" :attrs="{ id }" autocomplete="off" />
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
        <VButton icon="feather:save" @click="simpanPindah(item)" :loading="isLoading" color="primary" raised>
          Simpan Pindah
        </VButton>
      </template>

    </VModal>
  </section>

  <VModal :open="modalDetailPasien" title="Detail Pasien" :noclose="false" size="big" actions="right"
    @close="modalDetailPasien = false">
    <template #content>
      <DetailPasien v-if="modalDetailPasien" :noregistrasi="noreg_pasienDetail" :norec_pd="norec_pd_pasienDetail" />
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive, onMounted, defineComponent } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import SpeedDial from 'primevue/speeddial'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { formatRp } from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import OverlayPanel from 'primevue/overlaypanel';
import Dialog from 'primevue/dialog';
import * as qzService from '/@src/utils/qzTrayService'
import DetailPasien from '../registrasi/detail-registrasi.vue'
import Sidebar from 'primevue/sidebar';

useHead({
  title: 'Dashboard Rawat Inap - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const modalDetailPasien = ref(false);
let isLoadCount: any = ref(false)
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const kelompokUserID = useUserSession().getUser().kelompokUser.id
const pegawaiId = useUserSession().getUser().pegawai.id

const themeColors = useThemeColors()
const op = ref();
const activeTab = ref(0);
const userLogin = useUserSession().getUser()
const total = ref(0)
const totalData: any = ref(0)
const totalPaging: any = ref(0)
const router = useRouter()
const modalInput = ref(false)
const item: any = ref({
  isPasien: false,
  aktif: true,
  filterTgl: new Date(),
  totalPulang: 0,
  totalRawat: 0,
  totalBedKosong: 0,
  totalBedIsi: 0,
  jumlahDokter: 0,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  periodeMutasi: reactive({
    start: new Date(),
    end: new Date(),
  }),
  periodePulang: reactive({
    start: new Date(),
    end: new Date(),
  }),
  pasien: reactive({

  })
})
if (kelompokUser && kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
  item.value.isPasien = true
}
const modalFilter: any = ref(false)
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const listIntruksi: any = ref([]);
const notifBottom = ref(false);
const listIntruksiBersama: any = ref([]);
const notifBottomBersama = ref(false);
const modalDetailDokter = ref(false)
const modalDetailProduk = ref(false)
const modalDetailKamar = ref(false)
const modalSuratRawatInap = ref(false)
const modalSuratKematian = ref(false)
const modalSuratSakit = ref(false)
const modalChangeDokter = ref(false)
const openModalIntruksi = ref(false);
const openModalIntruksiBersama = ref(false);
let ID_RUANGAN = useRoute().query.id as string
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let dataSource: any = ref([])
let dataPasien: any = ref([])
let dataPasienPulang: any = ref([])
let d_Ruangan: any = ref([])
let dataStok: any = ref([])
let sourceRuangan: any = ref([])
let dataDokter: any = ref([])
let dataKamar: any = ref([])
let d_Dokter2: any = ref([])
const d_Kelas: any = ref([])
const d_KelasAll: any = ref([])
const d_Kamar: any = ref([])
const d_TempatTidur: any = ref([])
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let isLoad: any = ref(true)
const d_Dokter: any = ref([])
const itemSource: any = ref([])
let modalSKDokter: any = ref(false)
const filters = ref('')
const filter = ref('')
const input = ref('')
const sourceItem = ref([])
const sourceMutasi = ref([])
const selectedItem = ref()
const sourceItemSK = ref([])
const cetak = ref([]);
const dataPenangguangJawab = ref([]);
const btnLoadSimpan: any = ref(false)
const dataSourceStok = computed(() => {
  if (!filters.value) {
    return dataStok.value
  }
  return dataStok.value.filter((item: any) => {
    return item.namaproduk.match(new RegExp(filters.value, 'i'))
  })
})

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataDokter.value
  }
  return dataDokter.value.filter((item: any) => {
    return item.namapegawai.match(new RegExp(filters.value, 'i'))
  })
})


const fetchDropdown = async () => {
  const response = await useApi().get('/dashboard/dropdown-rawat-inap')
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Ruangan.value.forEach((element: any) => {
    sourceRuangan.value.push(element)
  })
  if (H.cacheHelper().get('ruanganDipilihRI') && H.cacheHelper().get('ruanganDipilihRI').length > 0) {
    sourceRuangan.value = H.cacheHelper().get('ruanganDipilihRI')
  }
  // reload()
}

const detailProduk = (e: any) => {
  item.value.id = e.id
  item.value.objectprodukfk = e.namaproduk
  item.value.objectasalprodukfk = e.asalproduk
  item.value.harganetto1 = e.harganetto1
  item.value.harganetto2 = e.harganetto2
  item.value.qtyproduk = e.qtyproduk
  modalDetailProduk.value = true
}

const route = useRoute()
isLoading.value = false

const toRoman = (num: number): string => {
  const romanNumerals: { [key: number]: string } = {
    1: "I", 2: "II", 3: "III", 4: "IV", 5: "V",
    6: "VI", 7: "VII", 8: "VIII", 9: "IX", 10: "X",
    20: "XX", 30: "XXX", 40: "XL", 50: "L",
    60: "LX", 70: "LXX", 80: "LXXX", 90: "XC", 100: "C"
  };

  if (romanNumerals[num]) return romanNumerals[num];

  let result = "";
  const digits = String(num).split("").reverse();

  for (let i = 0; i < digits.length; i++) {
    const digit = parseInt(digits[i]) * Math.pow(10, i);
    if (digit > 0) {
      result = (romanNumerals[digit] || "") + result;
    }
  }

  return result || num.toString();
};


const addNewItem = (item: any): void => {
  if (item.dokterPemeriksaList.length < 18) {
    item.dokterPemeriksaList.push({ value: '', label: '' });
  } else {
    H.alert('warning', 'Maksimal Dokter Pemeriksa 20 Tercapai.');
  }
};

const removeItem = (item: any, dokterIndex: number): void => {
  item.dokterPemeriksaList.splice(dokterIndex, 1);
};

const fetchData = async (e: any) => {
  isLoad.value = true;
  let search = item.value.search ? `&search=${item.value.search}` : '';
  let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : '';
  let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : '';
  let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : '';
  let idPegawai = kelompokUser && kelompokUser.toUpperCase().indexOf('DOKTER') > -1 && item.value.isPasien ? `&idpegawai=${H.pegawaiLogin().id}` : '';
  let ruanganid = '';
  let offset: any = '';
  let limit: any = currentPage.value.limit;

  if (search) {
    offset = 1;
  } else {
    offset = route.query.page ? route.query.page : 1;
  }
  offset = (parseInt(offset) - 1) * limit;
  let page: any = route.query.page ? route.query.page : 1;

  if (sourceRuangan.value != undefined) {
    let itemsRuang: any = [];
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])];
    });
    ruanganid = `ruanganfk=${itemsRuang}`;
  }

  isLoading.value = true;
  dataPasien.value = [];

  await useApi().get(`/dashboard/rawat-inap/list?page=${page}&limit=${limit}&offet=${offset}&${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${search}`)
    .then((response) => {
      dataPasien.value = response.data.map((item: any) => ({
        ...item,
        isRG: item.rawatgabung === 'Rawat Gabung'
      }));
      totalPaging.value = response.total;
      totalData.value = response.total;
    });

  route.query.page = '1';

  if (kelompokUser && kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
    await countPasien(idPegawai);
  }

  isLoad.value = false;
  isLoading.value = false;
}

const fetchDetail = async () => {
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang: any = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `ruanganfk=${itemsRuang}`
  }

  let tgl = item.value.filterTgl ? `&tgl=${H.formatDate(item.value.filterTgl, 'YYYY-MM-DD')}` : ''

  dataDokter.value = []
  dataKamar.value = []
  isLoading.value = true
  const response = await useApi().get(`/dashboard/detail-rawat-inap?${ruanganid}${tgl}&limit=10`)
  isLoading.value = false
  dataDokter.value = response.jadwal
  dataKamar.value = response.data
  dataStok.value = response.produk
  item.value.totalPulang = response.totalPulang
  item.value.totalRawat = response.totalRawat
  item.value.totalBedIsi = response.totalBedIsi
  item.value.totalBedKosong = response.totalBedKosong
  item.value.jumlahDokter = response.jumlahDokter
}

const detailDokter = (e: any) => {
  item.value.id = e.id
  item.value.objectpegawaifk = e.namalengkap
  item.value.objectruanganfk = e.namaruangan
  item.value.hari = e.hari
  item.value.jammulai = e.jammulai
  item.value.jamakhir = e.jamakhir
  modalDetailDokter.value = true
}

const detailKamar = (e: any) => {
  item.value.id = e.id
  item.value.namakelas = e.namakelas
  item.value.namaruangan = e.namaruangan
  item.value.name = e.name
  item.value.namakamar = e.namakamar
  item.value.kosong = e.kosong
  item.value.isi = e.isi
  modalDetailKamar.value = true
}

let periode = H.cachePeriode().get('periode')
if (periode != undefined) {
  item.value.periode.start = new Date(periode['awal']);
  item.value.periode.end = new Date(periode['akhir']);
}

const fetchPasienPulang = async (e: any) => {

  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang: any = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `ruanganfk=${itemsRuang}`
  }
  let dari = H.formatDate(item.value.periodePulang.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.value.periodePulang.end, 'YYYY-MM-DD')
  let begin = `&dari=${dari}`
  let last = `&sampai=${sampai}`
  let chacePeriode = { 'awal': dari, 'akhir': sampai }
  H.cachePeriode().set('periode', chacePeriode);
  // debugger
  let qsearch = item.value.qsearch ? `&qsearch=${item.value.qsearch}` : ''
  let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
  let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
  let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
  let idPegawai = e ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let offset: any = '';
  let limit: any = currentPage.value.limit
  if (qsearch) {
    offset = 1
  } else {
    offset = route.query.page ? route.query.page : 1
  }
  offset = (parseInt(offset) - 1) * limit
  let page: any = route.query.page ? route.query.page : 1
  isLoadingTT.value = true
  dataPasienPulang.value = []
  await useApi().get(`/dashboard/rawat-inap-pasien-total?page=${page}&offset=${offset}&limit=${limit}&${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${begin}${last}${qsearch}`).then((response) => {
    dataPasienPulang.value = response.data
    totalPaging.value = response.total
  })
  isLoad.value = false
  isLoadingTT.value = false
  route.query.page = '1'
}


const billing = (e: any) => {
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: e.norec_pd,
    },
  })
}

function batalPulang(e: any) {
  useApi().post('/rawatinap/batal-pulang-pasien', { 'norec_apd': e.norec_apd, 'norec_pd': e.norec_pd }).then((response) => {
    fetchPasienPulang()
  })
}

const BatalRawatInap = async (e: any) => {
  let json = {
    pasiendaftar: {
      norec_pd: e.norec_pd,
    },
    antrianpasiendiperiksa: {
      norec_apd: e.norec_apd,
    }
  }
  isLoading.value = true
  isLoad.value = true
  await useApi()
    .post(`/dashboard/batal-ranap`, json)
    .then((response: any) => {
      reload()
      isLoading.value = false
      isLoad.value = false
    })

}

// console.log(itemSource)

if (userLogin.mapLoginUserToRuangan.length) {
  for (let i = 0; i < userLogin.mapLoginUserToRuangan.length; i++) {
    const element = userLogin.mapLoginUserToRuangan[i];
    if (element.departemen.toLowerCase().indexOf('rawat inap') > -1) {
      // item.value.namaruangan = element.namaruangan
      item.value.departemen = element.departemen
      item.value.id_ruangan = element.id
      item.value.id_departemen = element.objectdepartemenfk
      break
    }
  }
}

const PulangPindah = async (e: any) => {
  router.push({
    name: 'module-rawat-inap-pindah-pulang',
    query: {
      nocmfk: item.nocmfk,
      norec_pd: item.norec_pd,

    },
  })
}

const changeRuang = async (e: any) => {
  H.cacheHelper().set('ruanganDipilihRI', sourceRuangan.value)
}

const showModalFilter = () => {
  modalFilter.value = true
}
const reload = () => {
  // fetchDetail()
  fetchPasienPulang()
  fetchPasienMutasi()
  fetchData()
}
const emr = (e: any) => {
  H.checkAksesEMR(e, kelompokUser, kelompokUserID, pegawaiId)
  H.cacheHelper().set('xxx_cache_menu', undefined)
  H.cacheHelper().set('xxx_cache_menu_' + e.nocmfk, undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      norec_apd: e.norec_apd,
    }
  })
}

const saveSKDokter = async (e: any) => {

  isLoading.value = true
  let objSave = {
    "norec": sourceItemSK.value.norec,
    "norec_pd": sourceItem.value.norec_pd,
    "dokterfk": sourceItem.value.objectpegawaifk,
    "tinggibadan": item.value.tinggiBadan,
    "beratbadan": item.value.beratBadan,
    "tekanandarah": item.value.tekananDarah,
    "denyutjantung": item.value.denyutNadi,
  }

  await useApi().post('dashboard/save-surat-keterangan-dokter', objSave).then((respon) => {
    isLoading.value = false
    modalSKDokter.value = false
    // useApi().get('report/ranap/cetak-surat-sehat?noregistrasi=' + sourceItem.value.noregistrasi)
    H.printBlade('report/ranap/cetak-surat-sehat?noregistrasi=' + sourceItem.value.noregistrasi)
  })
}

const saveSKSakit = async () => {
  isLoading.value = true
  let objSave = {
    "norec": sourceItemSK.value.norec,
    "norec_pd": sourceItem.value.norec_pd,
    "dokterfk": sourceItem.value.objectpegawaifk,
    "tglijinawal": item.value.tglAwalSakit,
    "tglijinakhir": item.value.tglAkhirSakit,
    "keterangan": item.value.catatanSakit,
    "indikasi": item.value.indikasiKembali,
    "hasilpemeriksaan": item.value.hasilPeriksa,
    "diagnosa": item.value.diagnosaSakit,
    "tglkontrol": item.value.tglKembaliRS,
  }
  await useApi().post('dashboard/save-surat-keterangan-sakit', objSave).then((response) => {
    isLoading.value = false
    modalSuratSakit.value = false
  })
  H.printBlade('report/ranap/cetak-surat-sakit?noregistrasi=' + sourceItem.value.noregistrasi)
}

const showModalSKDokter = async (e: any) => {
  sourceItem.value = e
  await useApi().get('dashboard/get-data-surat-keterangan?norec_pd=' + e.norec_pd + '&jenissurat=' + 'SuratKeteranganSehat').then((response) => {
    modalSKDokter.value = true
    item.value.tinggiBadan = response.tinggibadan ? response.tinggibadan : ''
    item.value.beratBadan = response.beratbadan ? response.beratbadan : ''
    item.value.tekananDarah = response.tekanandarah ? response.tekanandarah : ''
    item.value.denyutNadi = response.denyutjantung ? response.denyutjantung : ''
    sourceItemSK.value = response
  })
}

const showModalSKSakit = async (e: any) => {
  sourceItem.value = e
  await useApi().get('dashboard/get-data-surat-keterangan?norec_pd=' + e.norec_pd + '&jenissurat=' + 'SuratKeteranganSakit').then((response) => {
    modalSuratSakit.value = true
    item.value.tglAwalSakit = response.tglawal ? response.tglawal : ''
    item.value.tglAkhirSakit = response.tglakhir ? response.tglakhir : ''
    item.value.tglKembaliRS = response.tglkontrol ? response.tglkontrol : ''
    item.value.diagnosaSakit = response.diagnosa ? response.diagnosa : ''
    item.value.hasilPeriksa = response.hasilpemeriksaan ? response.hasilpemeriksaan : ''
    item.value.indikasiKembali = response.indikasi ? response.indikasi : ''
    item.value.catatanSakit = response.keterangan ? response.keterangan : ''
    sourceItemSK.value = response
  })
}

const test = () => {
  console.log('ee')
}

const modalRawatInap = (e: any) => {
  modalSuratRawatInap.value = true;
  cetak.value = e;
}

const printSuratRawatInap = async (e: any) => {
  if (dataPenangguangJawab.value.penanggungJawab == undefined || dataPenangguangJawab.value.alamat == undefined) {
    H.alert('error', 'isi semua data')
    return
  }
  H.printBlade(`report/cetak-lembar-ranap?noregistrasi=${cetak.value.norec_pd}&penaggungJawab=${dataPenangguangJawab.value.penanggungJawab}&alamat=${dataPenangguangJawab.value.alamat}`)
  dataPenangguangJawab.value.penanggungJawab = null;
  dataPenangguangJawab.value.alamat = null;
}

const cetakGelangPasien = (e: any) => {
  cetak.value = e;
  qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${cetak.value.noregistrasi}`, 'GELANG PASIEN', 1)
  // H.printBlade(`report/cetak-gelang-pasien?noregistrasi=${cetak.value.noregistrasi}`)
}

const cetakLabelPasien = (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', 1)
  // let dokter = `&dokter=${e.namalengkap}`
  // let tglregistrasi = `&tglregistrasi=${e.tglregistrasi}`
  // H.printBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=${e.noregistrasi}${dokter}${tglregistrasi}`);
}

const cetakSuratKeteranganDokter = async (e: any) => {

  let dokter = `&dokter=${e.namalengkap}`
  let kelompokpasien = `&kelompokpasien=${e.kelompokpasien}`
  let objectdepartemenfk = `&objectdepartemenfk=${e.objectdepartemenfk}`
  let tglregistrasi = `&tglregistrasi=${e.tglregistrasi}`
  let norec_pd = `&norec_pd=${e.norec_pd}`
  H.printBlade(`dashboard/registrasi/cetak-surat-keterangan-dokter?noregistrasi=${e.noregistrasi}${dokter}${kelompokpasien}${objectdepartemenfk}${tglregistrasi}${norec_pd}`);
}

const saveSuratKematian = async (e: any) => {
  e.loadingBtnCetak = true
  cetak.value = e;
  let json = {
    norec_pd: cetak.value.norec_pd,
    tglmeninggal: cetak.value.tglmeninggal,
    dokterfk: cetak.value.dokterfk,
    ketarangan: "sakit"
  }

  isLoading.value = true
  await useApi()
    .post(`/general/save-surat-keterangan-kematian`, json)
    .then((response: any) => {
      isLoading.value = false
      // H.printBlade(`report/cetak-surat-kematian?jenissuratfk=21&norec=${cetak.value.norec_pd}`)
      // H.printBlade(`report/cetak-surat-kematian?jenissuratfk=21&norec=${cetak.value.norec_pd}`)
      // fetchData()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  cetakSuratKematian(cetak.value.norec_pd)
  e.loadingBtnCetak = false
}

const cetakSuratKematian = (e: any) => {
  H.printBlade(`report/cetak-surat-kematian?jenissuratfk=21&norec=${e}`)
}

const cetakLembarKeluar = (e: any) => {
  // useApi().get('report/cetak-surat-ranap')
  H.printBlade(`report/cetak-lembar-keluar-masuk?norec=${e.norec_pd}`)
}

const cetakRegisRanap = (e: any) => {

  // H.printBlade(`report/cetak-surat-pendaftaran-ranap?norec=${e.norec_pd}`)
  useApi().get(`report/cetak-surat-pendaftaran-ranap?norec=${e.norec_pd}&nocmfk=${e.nocmfk}`)
}

const cetakSEP = (e: any) => {
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
    'SEP', 1)
}

const saveSuratMeninggal = async (e: any) => {
  cetak.value = e;
  let json = {
    norec_pd: cetak.value.norec_pd,
    tglmeninggal: cetak.value.tglmeninggal,
    dokterfk: cetak.value.dokterfk,
    ketarangan: "sakit"
  }

  isLoading.value = true
  await useApi()
    .post(`/general/save-surat-keterangan-meninggal`, json)
    .then((response: any) => {
      isLoading.value = false
      H.printBlade(`report/cetak-surat-meninggal?jenissuratfk=22&norec=${cetak.value.norec_pd}`)
      fetchData()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}
const openModalDpjp = (data: any) => {
  modalChangeDokter.value = true
  item.value.dokterPemeriksa = data.objectpegawaifk ? { value: data.objectpegawaifk, label: data.namalengkap } : ''
  item.value.dokterPemeriksa2 = data.objectpegawairawatbersamafk ? { value: data.objectpegawairawatbersamafk, label: data.nama } : ''
  // Use a regular expression to correctly split the names by ", dr." to handle cases where commas are part of the name
  const dokterNames = data.dokter_nama ? data.dokter_nama.split(/, (?=dr\.)/) : [];

  console.log(dokterNames);

  item.value.dokterPemeriksaList = data.objectpegawairawatbersamadinamisfk
    ? data.objectpegawairawatbersamadinamisfk.split(', ').map((dokterId: string, index: number) => ({
        value: parseInt(dokterId, 10) || 0,
        label: dokterNames[index] || '',
      }))
    : [{}];

  console.log(item.value.dokterPemeriksaList);
  console.log(item.value.dokterPemeriksa2);

  itemSource.value = data
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const saveChangeDokter = async () => {
  if (!item.value.dokterPemeriksa) {
    H.alert('error', 'Dokter Wajib Dipilih')
    return
  }

  let dokterIds = "";

  if (item.value?.dokterPemeriksaList && typeof item.value.dokterPemeriksaList === 'object') {
  dokterIds = Object.values(item.value.dokterPemeriksaList)
    .map((dokter) => dokter?.value ?? '')
    .filter((id) => id)
    .join(', ');
  }

  btnLoadSimpan.value = true
  await useApi().post('registrasi/change-dokter-dpjp', { 'norec_pd': itemSource.value.norec_pd, 'objectpegawaifk': item.value.dokterPemeriksa.value, 'objectpegawairawatbersamafk': item.value.dokterPemeriksa2.value, 'objectpegawairawatbersamadinamisfk': dokterIds }).then((response) => {
    btnLoadSimpan.value = false
    modalChangeDokter.value = false
    fetchData()
  }).catch((err) => {
    console.log(err)
  })
}

const toggle = (event: any, e: any) => {
  selectedItem.value = e
  op.value.toggle(event);
}

const countPasien = async (pegawai: any) => {
  await useApi().get(`dashboard/get-jumlah-pendapatan?${pegawai}`).then((response) => {
    item.value.totalPasien = response.jumlahPasien
    item.value.totalTindakan = response.jumlahTindakan
    item.value.pendapatanJasa = response.pendapatanJasa
    isLoadCount.value = false
  })
}

const fetchPasienMutasi = async () => {
  let dari = `?tglAwal=${H.formatDate(item.value.periodeMutasi.start, 'YYYY-MM-DD')}`
  let sampai = `&tglAkhir=${H.formatDate(item.value.periodeMutasi.end, 'YYYY-MM-DD')}`
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang: any = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }

  let search = item.value.qsearchmutasi ? `&search=${item.value.qsearchmutasi}` : ''
  let offset: any = '';
  let limit: any = currentPage.value.limit
  if (search) {
    offset = 1
  } else {
    offset = route.query.page ? route.query.page : 1
  }
  offset = (parseInt(offset) - 1) * limit
  let page: any = route.query.page ? route.query.page : 1
  isLoadingTT.value = true
  await useApi().get(`dashboard/get-mutasi-ranap${dari}${sampai}${search}${ruanganid}&page=${page}&offset=${offset}&limit=${limit}`).then((response) => {
    sourceMutasi.value = response.data
    totalPaging.value = response.total
    isLoadingTT.value = false
  })
}
const noreg_pasienDetail = ref('');
const norec_pd_pasienDetail = ref('');
const detailRegistrasi = async (e: any) => {
  modalDetailPasien.value = true
  noreg_pasienDetail.value = e.noregistrasi;
  norec_pd_pasienDetail.value = e.norec_pd;
  // let checkdataSelected = checkSelected(e);
  // if (!checkdataSelected) return;

  // let apd = await getAPD(e);

  // router.push({
  //   name: 'module-registrasi-detail-registrasi',
  //   query: {
  //     noregistrasi: e.noregistrasi,
  //     norec_pd: e.norec_pd,
  //     norec_apd: apd,
  //   },
  // })
}

const gotoFormInsidenInter = (e: any) => {

  router.push({
    name: 'module-indikator-form-insiden-internal',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
    },
  })
}
// const openModalUbahBed = (data) => {
//   modalInput.value = true;
//   item.value.kamar = null;
//   item.value.bed = null;
//   fetchKelas();
//   item.value.namaruanganPindah = data.namaruangan ? data.namaruangan : '';
//   itemSource.value = data;
//   setAutoFill()
//   changeRuangPindah();
//   console.log('isRG before changeKelas', data.rawatgabung == 'Rawat Gabung');
//   console.log(JSON.stringify(data, null, 2));
// }
// const changeKelas = async (e: any) => {
//   d_Kamar.value = [];
//   item.kamar = null;
//   item.bed = null;
//   if (e && itemSource.value.objectruanganlastfk) {
//     isLoadingTT.value = true;
//     const isRG = item.rawatgabung !== 'Rawat Gabung';
//     console.log('isRG', dataPasien);
//     useApi().get(
//       // `/rawatinap/kamar-ranap-by-kelas?id=${e}&idRuangan=${itemSource.value.objectruanganlastfk}&isRG=${isRG}`)
//       `/rawatinap/kamar-ranap-by-kelas?id=${e}&idRuangan=${itemSource.value.objectruanganlastfk}&isRG=false`)
//       .then((response: any) => {
//         isLoadingTT.value = false;
//         d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } });
//       })
//       .catch((error: any) => { isLoadingTT.value = false; });
//   }
//   console.log(e);
// };
const openModalUbahBed = (data) => {
  modalInput.value = true;
  item.value.kamar = null;
  item.value.bed = null;
  fetchKelas();
  item.value.namaruanganPindah = data.namaruangan ? data.namaruangan : '';
  itemSource.value = data;
  setAutoFill();
  changeRuangPindah();
  const isRG = data && data.rawatgabung === 'Rawat Gabung';
  console.log('isRG dari open modal:', isRG);
  changeKelas(item.namakelasrawat, isRG);
  // console.log('Data item at openModalUbahBed', JSON.stringify(data, null, 2));
}

const changeKelas = async (e: any, isRG: boolean) => {
  d_Kamar.value = [];
  item.kamar = null;
  item.bed = null;
  if (e && itemSource.value.objectruanganlastfk) {
    isLoadingTT.value = true;
    console.log('isRG in changeKelas:', isRG);
    useApi().get(
      `/rawatinap/kamar-ranap-by-kelas?id=${e}&idRuangan=${itemSource.value.objectruanganlastfk}&isRG=${isRG}`)
      .then((response: any) => {
        isLoadingTT.value = false;
        d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } });
      })
      .catch((error: any) => { isLoadingTT.value = false; });
  }
}


const changeRuangPindah = () => {
  const e = itemSource.value.objectruanganlastfk;
  item.namakelas = null;
  item.namakelasrawat = null;
  item.kamar = null;
  item.bed = null;
  if (e) {
    setKelas(e);
  }
};

const setKelas = async (e: any) => {
  isLoadingTT.value = true
  d_Kelas.value = []
  useApi().get(
    `/rawatinap/kelas-ranap-by-ruangan?id=${itemSource.value.objectruanganlastfk ? itemSource.value.objectruanganlastfk : e}`)
    .then((response: any) => {
      if (response.length == 1) {
        item.namakelas = response[0].id
      }
      isLoadingTT.value = false
      d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
  // console.log(e)
}

// const changeKelas = async (e: any) => {
//   d_Kamar.value = []
//   item.kamar = null;
//   item.bed = null;
//   if (e && itemSource.value.objectruanganlastfk) {
//     isLoadingTT.value = true
//     useApi().get(
//       `/rawatinap/kamar-ranap-by-kelas?id=${e}&idRuangan=${itemSource.value.objectruanganlastfk}&isRG=false`)
//       .then((response: any) => {
//         isLoadingTT.value = false
//         d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
//       })
//       .catch((error: any) => { isLoadingTT.value = false })
//   }
//   console.log(e)
//   // console.log(itemSource.value.namaruangan)
// }


const fetchKelas = async () => {
  await useApi().get(
    `emr/dropdown/kelas_m?select=id,namakelas`
  ).then((response) => {
    d_KelasAll.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
  })
}
const changeKamar = async (e: any) => {
  d_TempatTidur.value = []
  if (e) {
    for (let x = 0; x < d_Kamar.value.length; x++) {
      const element = d_Kamar.value[x];
      if (element.value == e) {
        d_TempatTidur.value = element.default.details.map((e: any) => { return { label: e.reportdisplay, value: e.id, default: e } })
      }
    }
  }
}
const setAutoFill = () => {
  try {
    const objectkelasrawatfk = itemSource.value.kelasrawatfk;
    const objectkelasfk = itemSource.value.objectkelasfk;
    const norecKamar = itemSource.value.norec_kamar;
    item.value.namakelasrawat = objectkelasrawatfk || '';
    item.value.namakelas = objectkelasfk || '';
    // item.value.kamar = norecKamar || '';
    // console.log(objectkelasrawatfk)
    // console.log(norecKamar)

    changeKelas(item.value.namakelasrawat);
  } catch (error) {
    console.error('Error setting autofill:', error);
    item.namakelasrawat = '';
    item.namakelas = '';
    // item.kamar = '';
  }
};

const simpanPindah = async () => {
  if (!item.value.namakelas) {
    useToaster().warn('Nama Kelas harus di isi')
    return
  }
  if (!item.value.namakelasrawat) {
    useToaster().warn('Nama Kelas Rawat  harus di isi')
    return
  }
  if (!item.value.kamar) {
    useToaster().warn('Nama Kamar Rawat  harus di isi')
    return
  }
  if (!item.value.bed) {
    useToaster().warn('Nama Bed Rawat  harus di isi')
    return
  }
  item.tglpindah = new Date();
  let json = {
    pasien: {
      nocm: itemSource.value.nocm,
      namapasien: itemSource.value.namapasien,
      noregistrasi: itemSource.value.noregistrasi,
    },
    pasiendaftar: {
      norec_pd: itemSource.value.norec_pd,
      objectruangantujuanfk: itemSource.value.objectruanganlastfk,
    },
    antrianpasiendiperiksa: {
      norec_apd: itemSource.value.norec_apd,
      tglkeluar: H.formatDate(item.tglpindah, 'YYYY-MM-DD HH:mm:ss'),
      objectkelasfk: item.value.namakelasrawat,
      objectkelasrawatfk: item.value.namakelas,
      objectkamarfk: item.value.kamar ? item.value.kamar : null,
      objectbedfk: item.value.bed ? item.value.bed : null,
    },
  }
  isLoading.value = true
  await useApi().post(`/rawatinap/save-pindah-bed-pasien`, json).then((response: any) => {
    isLoading.value = false
    item.value.kamar = null;
    item.value.bed = null;
    modalInput.value = false
    fetchData()
  })
    .catch((e: any) => {
      if (e.message == 'Bed Sudah Terisi, Silakan Pilih Bed Lain') {
        changeKelas(item.namakelasrawat)
      }
      isLoading.value = false
    })
}

const klikTab = (e: any) => {
  activeTab.value = e.index
  route.query.page = '1'
  currentPage.value.limit = 5
  // console.log("Curent Page",currentPage.value.page);
  // currentPage.value.page = 1
  if (activeTab.value == 0) {
    fetchData()
  }
  if (activeTab.value == 1) {
    fetchPasienPulang()
  }
  if (activeTab.value == 2) {
    fetchPasienMutasi()
  }
}

const suratKontrol = (e: any) => {
  router.push({
    name: 'module-integrasi-sistem-rencana-kontrol',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      norec_apd: e.norec_apd,
    }
  })
}


const changeisRajal = (v: any) => {
  item.value.isRajal = !item.value.isRajal;

  if (item.value.isRajal) {
    H.cacheHelper().set('lockedRoute', 'module-dashboard-rawat-jalan');
    router.push({
      name: 'module-dashboard-rawat-jalan'
    });
  } else {
    H.cacheHelper().set('lockedRoute', null);
    router.push({
      name: 'module-dashboard-rawat-ranap'
    });
  }
};



watch(
  () => item.value.isPasien, () => {
    fetchData(item.value.isPasien)
    fetchPasienPulang(item.value.isPasien)
  }
)

onMounted(async () => {
  await fetchDropdown()
  await fetchData()
  fetchDetail()
  if (kelompokUser && kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
    getIntruksiDokter();
    getIntruksiDokterRaber();
  }
  // await fetchPasienPulang(),
  // await fetchPasienMutasi()
})

const verifCPPT = async (data: any) => {
  isLoading.value = true;
  useApi().post(`dashboard/verif-intruksi-cppt?norec=${data.norec}&cpptdetail=${data.norec_cpptdetail}`).then(async (res) => {
    await getIntruksiDokter(false);
    isLoading.value = false;
  })
}
const verifCPPTRaber = async (data: any) => {
  isLoading.value = true;
  useApi().post(`dashboard/verif-intruksi-cppt?norec=${data.norec}&cpptdetail=${data.norec_cpptdetail}`).then(async (res) => {
    await getIntruksiDokterRaber(false);
    isLoading.value = false;
  })
}

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  if (activeTab.value == 0) {
    fetchData(item.value.isPasien)
  } else {
    fetchPasienPulang(item.value.isPasien);
  }
})
const getIntruksiDokter = (showB: bool = true) => {

listIntruksi.value = [];
isLoading.value = true;
useApi().get(`dashboard/get-intruksi-cppt-dokter-ranap?dpjp=${userLogin.pegawai.id}`).then((res) => {
  if (res.length > 0) {
    if (showB == true) {
      notifBottom.value = true;
    }
    listIntruksi.value = res;
    isLoading.value = false;
  }
});
}
const getIntruksiDokterRaber = (showB: boolean = true): void => {
  listIntruksiBersama.value = [];
  isLoading.value = true;

  useApi()
    .get(`dashboard/get-intruksi-cppt-dokter-ranap?dpjp=${userLogin.pegawai.id}&raber=true`)
    .then((res: any[]) => {
      if (res.length > 0) {
        const splittedData: any[] = [];
        
        res.forEach((item) => {
          if (item.details?.dpjpRawatBersama?.length > 0) {
            item.details.dpjpRawatBersama.forEach((intruksi: any) => {
              const newItem = { ...item, intruksi: intruksi.intruksi };
              splittedData.push(newItem);
            });
          } else {
            splittedData.push(item);
          }
        });

        if (showB) {
          notifBottomBersama.value = true;
        }
        listIntruksiBersama.value = splittedData;
      }
    })
    .finally(() => {
      isLoading.value = false;
    });
};

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
