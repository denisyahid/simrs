<template>
  <section>
    <FloatingButton @click="showMenuEMR()" />
    <div class="is-100">
      <div class="business-dashboard hr-dashboard">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard radius="smooth" elevated class="mb-3-min br-16">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VIconButton icon="feather:arrow-left" light dark-outlined @click="backPage()" />
                  <span class="title-emr ml-3">Rekam Medis</span>
                </div>
                <div class="column is-9">
                  <VIconButton circle class="mr-2 is-pulled-right classActiveNavEMR" icon="fas fa-angle-double-left"
                    raised bold @click="showNavStatusList" v-tooltip.bubble="'Lihat Daftar Pasien'">
                  </VIconButton>
                  <!-- <VButton rounded outlined color="primary" class="is-pulled-right mr-2" icon="feather:calendar" raised
                    bold>
                    {{ H.formatDateIndoSimpleMs(now) }}
                  </VButton> -->
                  <!-- <VButton rounded color="info" class="is-pulled-right mr-2" icon="feather:share-2" raised bold
                    @click="shareAPI">
                    Bagikan
                  </VButton> -->
                  <VButton rounded color="warning" class="is-pulled-right mr-2" icon="feather:user" raised bold
                    @click="profilPasien">
                    Profil
                  </VButton>
                  <VButton rounded color="info" class="is-pulled-right mr-2" icon="feather:list" raised bold
                    style="background: blue" @click="detailRegistrasiModals()">
                    Detail Registrasi
                  </VButton>
                  <VButton rounded color="danger" class="is-pulled-right mr-2" icon="fas fa-user-lock" bold
                    v-if="isClosing != null">
                    Closing
                  </VButton>
                  <!-- <VButton rounded outlined color="danger" class="is-pulled-right mr-2" icon="fas fa-user-lock" raised
                      bold @click="changeClosing(true)" v-if="isClosing == null" :loading="klikLoad">
                      Closing
                    </VButton> -->

                  <!-- <VButton rounded outlined color="success" class="is-pulled-right mr-2" icon="fas fa-unlock-alt" raised
                      bold @click="changeClosing(null)" v-else :loading="klikLoad">
                      Belum Closing
                    </VButton> -->


                  <VButton rounded color="success" class="is-pulled-right mr-2" icon="feather:printer" bold
                    v-if="dataSKDP.url_form" @click="showMenu(dataSKDP)">
                    <!-- {{ dataSKDP }} -->
                    SKDP
                  </VButton>
                  <VButton rounded color="success" class="is-pulled-right mr-2" icon="feather:external-link" bold
                    @click="iCare" :loading="isLoadingIcare" v-if="selectedRegistrasi.kelompokpasien == 'BPJS'">
                    I-CARE
                  </VButton>
                  <!-- <VButton rounded color="danger" class="is-pulled-right mr-2" icon="feather:printer" raised bold
                    @click="cetakSKD" :loading="isLoadingSKD">
                    Cetak SKD
                  </VButton> -->
                  <!-- <VButton rounded outlined color="primary" class="is-pulled-right mr-2"
                    v-tooltip-prime.top="'TOTAL BILLING '" icon="fas fa-calculator" bold>
                    {{ selectedRegistrasi.billing ? H.formatRp(selectedRegistrasi.billing, 'Rp. ') : 'Rp.0' }}
                  </VButton> -->
                  <VButtonImg rounded outlined :color="colorPLAFON" class="is-pulled-right mr-2"
                    icon="/images/icons/files/bpjs-no-bg.svg" :loading="isFlafon" @click="hitungBiayaSementara" bold
                    v-tooltip-prime.top="'PLAFON BPJS'">
                    {{
                      selectedRegistrasi.inacbg_totalgrouper ?
                        H.formatRp(selectedRegistrasi.inacbg_totalgrouper, 'Rp. ') :
                        'Rp.0'
                    }}
                  </VButtonImg>
                  <VButton rounded outlined color="dark" class="is-pulled-right mr-2" v-tooltip-prime.top="'RUANGAN '"
                    icon="fas fa-home" bold>
                    {{ selectedRegistrasi.namaruangan }}
                  </VButton>
                  <VButton rounded color="info" class="is-pulled-right mr-2" icon="fas fa-home" raised bold
                    @click="kodingRM()">
                    Koding RM
                  </VButton>
                  <VButton class="button is-info is-large has-text-weight-bold has-text-centered is-uppercase is-pulled-right mr-2"
                    style="border: 3px solid black; font-size: 1rem; padding: 1rem 2rem;" raised bold v-if="selectedRegistrasi.ispenjadwalankemoterapi">
                    Hari ini Pasien memiliki jadwal kemoterapi
                  </VButton>
                  <VButton
                    v-if="alergiObatPasien"
                    class="button is-warning is-large has-text-weight-bold has-text-centered is-uppercase is-pulled-right mr-2"
                    style="border: 3px solid black; font-size: 1rem; padding: 1rem 2rem;"
                    raised
                    bold
                  >
                    {{ alergiObatPasien }}
                  </VButton>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-12" style="margin: 0px 0px -20px -60px;">
                      <div class="columns is-multiline">
                        <div class="column is-1 text-center is-clickable is-show-riwayat-diagnosa">
                          <VIconButton class="is-pulled-right" icon="fas fa-expand-arrows-alt" raised bold
                            v-if="hideRiwayat" @click="hideRiwayat = false" v-tooltip.bubble="'Perkecil Tampilan EMR'">
                          </VIconButton>
                          <VIconButton class="is-pulled-right" icon="fas fa-expand" raised bold v-if="!hideRiwayat"
                            @click="hideRiwayat = true" v-tooltip.bubble="'Perbesar Tampilan EMR'">
                          </VIconButton>
                        </div>
                        <div class="column is-2" style="margin-top: -5px;">
                          <VControl>
                            <VSwitchBlock v-model="item.isRekap" label="Tampilan Home Baru" color="danger" />
                          </VControl>
                        </div>
                      </div>
                    </div>
                    <div class="column " :class="hideRiwayat == true ? 'is-hidden' : 'is-3'">
                      <div class="account-box is-navigation">
                        <div class="account-menu">
                          <VCard class="" style="padding: 5px 5px 5px 5px;">
                            <VCardHead title="" class="no-border p-1 riwayat-reg-nomobile h-600">
                              <div class="column is-12" v-if="!isLoadingPasien">
                                <HeadPasienEmr @open-modal="catatanPasien" @open-intruksi="openModalIntruksi"
                                  @open-histori-kanker="openModalKanker" :registrasi="registrasi" :pasien="pasien"
                                  :isaktif="PASIEN_AKTIF" />
                              </div>
                            </VCardHead>

                            <div class="column is-12" style="margin-top: -30px;">
                              <VButton class="tombol" color="success" raised
                                style="float:left; margin: 2px; width: 100%; display: none !important">
                                ASTOR-BM
                              </VButton>

                              <VButton class="tombol" raised>
                                ASTOR-BM
                              </VButton>
                              <VButton class="tombol" raised @click="cetakSEP()"
                                v-if="selectedRegistrasi && selectedRegistrasi.nosep != null">
                                CETAK SEP
                              </VButton>
                              <VButton class="tombol" raised @click="riwayatPasien()">
                                RIWAYAT PASIEN
                              </VButton>
                              <VButton class="tombol" raised @click="suratKeterangan()">
                                SURAT KETERANGAN
                              </VButton>
                              <VButton class="tombol" raised @click="informasiEdukasi()">
                                ASESMEN KEBUTUHAN INFORMASI & <br> EDUKASI
                              </VButton>
                              <VButton class="tombol" raised @click="catatanEdukasi()">
                                CATATAN INFORMASI DAN EDUKASI
                              </VButton>
                              <VButton class="tombol" raised @click="riwayatSanata()">
                                RIWAYAT SANATA
                              </VButton>
                              <VButton class="tombol" raised @click="setOK()"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') > -1">
                                SET OK
                              </VButton>
                              <VButton class="tombol" raised @click="setRujukan()"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') > -1">
                                SET RUJUKAN IGD
                              </VButton>
                              <VButton class="tombol" raised @click="setRanap()"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') > -1">
                                SET RANAP IGD
                              </VButton>
                              <VButton class="tombol" raised @click="onTabJadwalFisio()"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI FISIOTERAPI') > -1">
                                JADWAL KUNJUNGAN REHAB DAN FISIO
                              </VButton>
                              <VButton class="tombol" raised @click="onTabFisio()"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI FISIOTERAPI') > -1">
                                FORMULIR KEDOKTERAN FISIK & <br> REHABILITASI
                              </VButton>
                              <VButton class="tombol" raised @click="onTabJadwalFisio()"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI REHAB MEDIK') > -1">
                                JADWAL KUNJUNGAN REHAB DAN FISIO
                              </VButton>
                              <VButton class="tombol" raised @click="onTabFisio()"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI REHAB MEDIK') > -1">
                                FORMULIR KEDOKTERAN FISIK & <br> REHABILITASI
                              </VButton>
                              <VButton class="tombol" raised @click="onTabLembarRehabMedik()"
                                v-if="selectedRegistrasi.norec != undefined && pasien && (selectedRegistrasi.namaruangan.toUpperCase().indexOf('FISIO') > -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('REHAB MEDIK') > -1)">
                                LEMBAR HASIL REHAB MEDIK
                              </VButton>
                            </div>


                            <VCardHead title="Riwayat Registrasi" class="no-border p-1 riwayat-reg-nomobile"
                              style="display: none !important">
                              <template #action>
                                <VIconButton circle icon="feather:calendar" class="mr-2" raised bold @click="filter()">
                                </VIconButton>
                                <VIconButton circle icon="feather:refresh-cw" raised bold @click="reloadListReg()"
                                  :loading="isLoadingRegis"> </VIconButton>
                              </template>
                              <div v-if="isLoadingPasien">
                                <VPlaceloadWrap v-for="key in 6" :key="key">
                                  <VPlaceload width="100%" height="50px" class="mx-1 mt-2" />
                                </VPlaceloadWrap>
                              </div>
                              <div v-else-if="listRegistrasi.length == 0">
                                <VCard>
                                  <span style="color: var(--light-text)">Data tidak ditemukan...</span>
                                </VCard>
                              </div>
                              <div v-else-if="listRegistrasi.length > 0">
                                <div v-for="(itemsHEAD, key1) in listRegistrasi" :key="key1">
                                  <div class="text-center mt-0">
                                    <span class="span-text-left-bar" style="font-weight:bold;font-size:0.85rem">{{
                                      itemsHEAD.namadepartemen }}</span>
                                  </div>

                                  <div v-for="(items, key) in itemsHEAD.details" :key="key">
                                    <div class="columns p-0 mb-0 ">
                                      <div class="column is-1 mt-5 text-center is-clickable is-show-riwayat-diagnosa"
                                        style=" margin-top: 3.5rem !important;">
                                        <i aria-hidden="true" class="fas fa-chevron-right" v-if="!items.isdetail"
                                          @click="items.isdetail = true"></i>
                                        <i aria-hidden="true" class="fas fa-chevron-down is-success"
                                          v-if="items.isdetail" @click="items.isdetail = false"></i>
                                      </div>
                                      <div class="column is-11">
                                        <VCard class="is-clickable is-grey" @click="selectedRiwayat(items)"
                                          :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis' : ''"
                                          style="padding:10px">
                                          <i aria-hidden="true" class="lnir lnir-medicine mr-2"></i>
                                          <span class="span-text-left-bar">{{ H.formatDateIndo(items.tglregistrasi)
                                          }}</span>
                                          <span v-if="selectedRegistrasi.norec == items.norec"
                                            class="is-pulled-right ml-2 mt-3"
                                            :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis mt-3' : ''"
                                            style="margin-top: 2.5rem !important;">
                                            <i aria-hidden="true" class="fas fa-arrow-right"></i>
                                          </span>
                                          <br>
                                          <i aria-hidden="true" class="lnir lnir-house-alt mr-2"></i>
                                          <span class="span-text-left-bar">{{ items.namaruangan }}</span>
                                          <br>
                                          <i aria-hidden="true" class="fas fa-user-md mr-2"
                                            :style="items.dokter ? '' : 'color: var(--light-text);'"></i>
                                          <span class="span-text-left-bar"
                                            :style="items.dokter ? '' : 'color: var(--light-text);'">{{ items.dokter ?
                                              items.dokter : '-' }}</span>
                                          <br>
                                          <div class="mt-1"
                                            style="justify-content: space-between; display: inline-flex;">
                                            <i class="lnir lnir-diagnosis mr-2 " aria-hidden="true"
                                              :style="[items.diagnosis.length ? '' : 'color: var(--light-text);']"> </i>
                                            <span class="span-text-left-bar text-diagnosis"
                                              :style="items.diagnosis.length ? '' : 'color: var(--light-text);'">{{
                                                items.diagnosis.length ? items.diagnosis[0].kddiagnosa + ' - ' +
                                                  items.diagnosis[0].namadiagnosa : 'Diagnosis utama belum ada' }}</span>
                                          </div>
                                          <br>
                                          <!-- <div style="justify-content: space-between; display: flex;"> -->
                                          <div class="mt-1"
                                            style="justify-content: space-between; display: inline-flex;"
                                            v-if="items.isberkas == true ||
                                              items.laboratorium.length && items.laboratorium[0].keteranganorder == 'Order Laboratorium'
                                              || items.radiologi.length && items.radiologi[0].keteranganorder == 'Order Radiologi'">
                                            <VIconButton icon="fas fa-book-medical" light raised bold
                                              @click="berkasPasien" color="danger" v-if="items.isberkas == true"
                                              v-tooltip.bubble="'BERKAS PASIEN'" />
                                            <VIconButton icon="fas fa-bong" light raised bold @click="hasilLab"
                                              color="danger"
                                              v-if="items.laboratorium.length && items.laboratorium[0].keteranganorder == 'Order Laboratorium'"
                                              v-tooltip.bubble="'LABORATORIUM'" style="margin-left: 10px" />
                                            <VIconButton icon="fas fa-radiation" light raised bold @click="hasilRad"
                                              color="danger"
                                              v-if="items.radiologi.length && items.radiologi[0].keteranganorder == 'Order Radiologi'"
                                              v-tooltip.bubble="'RADIOLOGI'" style="margin-left: 10px" />

                                          </div>

                                        </VCard>
                                      </div>
                                    </div>
                                    <div class="columns p-0 mb-0 mt-2-min"
                                      v-if="items.isdetail && items.tekanandarah != null">
                                      <div class="column is-11 is-offset-1">
                                        <!-- <VCard style="background:rgb(194 255 239);border-color:rgb(194 255 239)"  class="bb-1 mt-1-min pt-2 pb-2">
                                          <i aria-hidden="true" class="fas fa-user-md mr-2"></i>
                                          <span class="span-text-left-bar">{{ items.dokter ?items.dokter :'-' }}</span>
                                        </VCard> -->
                                        <VCard style="background:rgb(255 222 175);border-color:rgb(255 222 175)"
                                          class="bb-1 mt-2-min pt-0 pb-2" v-if="items.tekanandarah != null">
                                          <div class="space-between">
                                            <span class="span-text-left-bar">Tinggi</span>
                                            <span class="span-text-left-bar"><b>{{ items.tinggibadan ? items.tinggibadan
                                              : '-' }}</b> Cm</span>
                                          </div>
                                          <div class="space-between">
                                            <span class="span-text-left-bar">Berat</span>
                                            <span class="span-text-left-bar"><b>{{ items.beratbadan ? items.beratbadan :
                                              '-'
                                                }}</b> Kg</span>
                                          </div>
                                          <div class="space-between">
                                            <span class="span-text-left-bar">TD</span>
                                            <span class="span-text-left-bar"><b>{{ items.tekanandarah ?
                                              items.tekanandarah
                                              : '-' }}</b> mmHg</span>
                                          </div>
                                          <div class="space-between">
                                            <span class="span-text-left-bar">RR</span>
                                            <span class="span-text-left-bar"><b>{{ items.pernafasan ? items.pernafasan :
                                              '-'
                                                }}</b> x/mnt</span>
                                          </div>
                                          <div class="space-between">
                                            <span class="span-text-left-bar">Nadi</span>
                                            <span class="span-text-left-bar"><b>{{ items.nadi ? items.nadi : '-' }}</b>
                                              x/mnt</span>
                                          </div>
                                        </VCard>

                                      </div>
                                    </div>

                                  </div>
                                </div>
                              </div>
                              <div class="dataTable-bottom">
                                <div class="dataTable-info">Menampilkan {{ currentPage.page }} ke {{ currentPage.limit
                                }}
                                  dari
                                  {{ totalData }} entri data
                                </div>
                              </div>
                              <div class="is-pulled-bottoms">
                                <VFlexPagination v-model:current-page="currentPage.page" class="mt-6"
                                  :item-per-page="currentPage.limit" :total-items="totalData"
                                  :max-links-displayed="10" />
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
                                      </select>
                                    </div>
                                  </VControl>
                                </VField>
                                <!-- <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                                      :total-items="totalData" :max-links-displayed="10">
                                      <template #before-pagination>
                                      </template>
                                      <template #before-navigation>
                                        <VFlex class="mr-4 mt-1" column-gap="1rem">
                                          <VField>

                                          </VField>

                                          </VField>
                                        </VFlex>
                                      </template>
                                    </VFlexPagination> -->
                              </div>
                            </VCardHead>

                            <VCardHead title="Registrasi" class="no-border p-1 riwayat-reg-mobile">
                              <template #action>
                                <VIconButton circle icon="feather:calendar" class="mr-2" raised bold @click="filter()">
                                </VIconButton>
                                <VIconButton circle icon="feather:refresh-cw" raised bold @click="reloadListReg()"
                                  :loading="isLoadingPasien"> </VIconButton>
                              </template>
                              <div v-if="isLoadingPasien">
                                <VPlaceloadWrap v-for="key in 6" :key="key">
                                  <VPlaceload width="100%" height="50px" class="mx-1 mt-2" />
                                </VPlaceloadWrap>
                              </div>
                              <div v-else-if="listRegistrasi.length == 0">
                                <VCard>
                                  <span style="color: var(--light-text)">Data tidak ditemukan...</span>
                                </VCard>
                              </div>
                              <div v-else-if="listRegistrasi.length > 0">
                                <div v-for="(itemsHEAD, key1) in listRegistrasi" :key="key1">
                                  <div class="text-center mt-0">
                                    <span class="span-text-left-bar" style="font-weight:bold;font-size:0.85rem">{{
                                      itemsHEAD.namadepartemen
                                      }}</span>
                                  </div>
                                  <div v-for="(items, key) in itemsHEAD.details" :key="key">
                                    <div class="columns p-0 mb-0 ">
                                      <div class="column is-1 mt-3 text-center is-clickable is-show-riwayat-diagnosa">
                                        <i aria-hidden="true" class="fas fa-chevron-right" v-if="!items.isdetail"
                                          @click="items.isdetail = true"></i>
                                        <i aria-hidden="true" class="fas fa-chevron-down is-success"
                                          v-if="items.isdetail" @click="items.isdetail = false"></i>
                                      </div>
                                      <div class="column is-11">
                                        <VCard class="is-clickable is-grey" @click="selectedRiwayat(items)" :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis' : ''
                                          " style="padding:10px">
                                          <i aria-hidden="true" class="lnir lnir-medicine mr-2"></i>
                                          <span class="span-text-left-bar">{{
                                            H.formatDateIndoSimpleDayNoTime(items.tglregistrasi)
                                            }}</span>
                                          <span v-if="selectedRegistrasi.norec == items.norec"
                                            class="is-pulled-right ml-2" :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis' : ''
                                              ">
                                            <i aria-hidden="true" class="fas fa-arrow-right"></i>
                                          </span>
                                        </VCard>
                                      </div>
                                    </div>

                                  </div>
                                </div>
                              </div>
                              <div class="dataTable-bottom">
                                <div class="dataTable-info">Menampilkan {{ currentPage.page }} ke {{ currentPage.limit
                                }}
                                  dari
                                  {{ totalData }} entri data
                                </div>
                              </div>
                              <div class="columns mt-3-min">
                                <VFlexPagination v-model:current-page="currentPage.page" class="mt-6"
                                  :item-per-page="currentPage.limit" :total-items="totalData"
                                  :max-links-displayed="10" />
                              </div>
                            </VCardHead>
                          </VCard>
                        </div>
                      </div>
                    </div>
                    <div class="column " :class="hideRiwayat == true ? 'is-12' : 'is-9'">
                      <div class="columns is-multiline">

                        <!-- Nurse Station Nuklir -->

                        <div class="column is-12"
                          v-if="kelompokUser.toUpperCase().indexOf('NURSE-STATION-KEDOKTERAN-NUKLIR') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanNurse()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Nurse Station</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidanan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Kebidanan</span></a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>

                        <!-- gizi -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('GIZI') > -1 && selectedRegistrasi.namaruangan != null">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="asesmenAwalGizi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Gizi</span>
                                  </a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="asuhanGiziNeonatus()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asuhan Gizi Neonatus</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="asesmenGiziBayiAnak()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Gizi Bayi & Anak Rawat Inap Intensif</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="asesmenGiziGeriatriRI()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Gizi Geriatri Rawat Inap & Intensif</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="monitoringEvaluasiGizi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Monitoring & Evaluasi Gizi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabGiziDewasaRanapIntensif()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Gizi Dewasa Rawat Inap dan Intensif</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabGeriatriRanapIntensif()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Gizi Geriatri Rawat Inap Dan Intensif</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabStrongKids()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Skrining Gizi Bayi dan Anak Strongkids</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- admission -->

                        <div class="column is-12"
                          v-else-if="(kelompokUser.toUpperCase().indexOf('ADMISSION') > -1 && selectedRegistrasi.namaruangan != null) || (kelompokUser.toUpperCase().indexOf('REGISTRASI') > -1 && selectedRegistrasi.namaruangan != null)">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="suratPermintaanDirawat()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Surat Permintaan Dirawat</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="suketGadar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Surat Keterangan Gawat Darurat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="persetujuanUmum()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Persetujuan Umum</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="pemberianInformasiRI()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Pemberian Informasi RI</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="checklistPemberianInformasiRI()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Checklist Pemberian Informasi RI</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="perkiraanBiaya()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Perkiraan Biaya</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="slipAdmission()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Slip Admission</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="suratPernyataanPermintaan()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Surat Pernyataan Permintaan Kelas RI</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="suratPernyataanPA()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Surat Pernyataan Penggunaan Asuransi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perinatologi -->
                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PERINATOLOGI') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabVital()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Vital Sign</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanNeonatus()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Neonatus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRencanaKeperawatanRanap()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rencana Keperawatan Rawat Inap</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat kemoterapi RI -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('RAWAT INAP KEMOTERAPI') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabVital()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Vital Sign</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanRanap()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Ranap</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRencanaKeperawatanRanap()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rencana Keperawatan Ranap</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanIntensif()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Intensif</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanNeonatus()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Neonatus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="pengkajianEWS()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">AEWS</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTimeOutPemberianObatKemoterapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Timeout Pemberian
                                      Obat Kemoterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="catatanPemberianObatKemoterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Catatan Pemberian Obat Kemoterapi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="monitoringPemberianObatKIV()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Monitoring Pemberian Obat Kemoterapi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onkologiBoardMeetingB()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Onkologi Board Meeting B</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabExtravasasi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Pengkajian Extravasasi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat kemoterapi -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEMOTERAPI') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="formulirADOKPK()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asuhan & Observasi Pasien Kemoterapi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabProtokolKemoterapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Protokol Kemoterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPemberianObatKemoterapiIntraVena()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pemberian Obat
                                      Kemoterapi Intra Vena</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanPemberianObatKemoterapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Catatan
                                      Pemberian Obat Kemoterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTimeOutPemberianObatKemoterapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Timeout Pemberian
                                      Obat Kemoterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="catatanPemberianObatKemoterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Catatan Pemberian Obat Kemoterapi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="monitoringPemberianObatKIV()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Monitoring Pemberian Obat Kemoterapi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabOnkologiBoardMeeting()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Onkologi Board Meeting</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabSuratPermintaanPenggunaanObatKhususKemo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Surat Permintaan
                                      Penggunaan Obat Khusus Kemoterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabExtravasasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pengkajian Extravasasi Pasien Kemoterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPencampuranSediaanKemo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pencampuran Sediaan Kemoterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="SuratPenggunaanObatKhususKronis()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Surat Penggunaan Obat
                                      Khusus Kronis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- HEMODIALISIS PERAWAT -->
                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('HEMODIALISIS') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPersetujuanTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Persetujuan Tindakan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPemakaianTerapoEri()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pemakaian Terapi Eritropotine
                                    </span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Informasi Dan Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirBuktiCanggih()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Bukti Canggih</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="pengkajianJatuhDewasa()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pengkajian Resiko Jatuh Dewasa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPeresepanHemo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Peresepan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="resumeTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resume Tindakan Hemodialisis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAsuhanObservasi2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Asuhan Keperawatan dan Observasi Pasien
                                      Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="informasiEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Asesmen Kebutuhan Informasi & Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabDataUmumHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Data Umum HD
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="statusHemodialisa()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Status Hemodialisa</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat poli capd -->
                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('CAPD') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPersetujuanTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Persetujuan Tindakan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPemakaianTerapoEri()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pemakaian Terapi Eritropotine
                                    </span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Informasi Dan Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirBuktiCanggih()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Bukti Canggih</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="pengkajianJatuhDewasa()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pengkajian Resiko Jatuh Dewasa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPeresepanHemo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Peresepan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="resumeTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resume Tindakan Hemodialisis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAsuhanObservasi2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Asuhan Keperawatan dan Observasi Pasien
                                      Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="informasiEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Asesmen Kebutuhan Informasi & Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabDataUmumHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Data Umum HD
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="statusHemodialisa()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Status Hemodialisa</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- Dokter Hemodialisis -->
                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('DOKTER') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('HEMODIALISIS') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onAssmedHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Medis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Informasi Dan Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPemakaianTerapoEri()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pemakaian Terapi Eritropotine
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="resumeTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resume Tindakan Hemodialisis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirBuktiCanggih()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Bukti Canggih</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPersetujuanTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Persetujuan Tindakan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPeresepanHemo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Peresepan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabMedis()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Assesmen Medis</span>
                                  </a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- DOKTER CAPD -->
                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('DOKTER') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('CAPD') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onAssmedHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Medis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Informasi Dan Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPemakaianTerapoEri()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pemakaian Terapi Eritropotine
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirBuktiCanggih()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Bukti Canggih</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPersetujuanTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Persetujuan Tindakan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPeresepanHemo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Peresepan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabMedis()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Assesmen Medis</span>
                                  </a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat Poli Nefrologi -->
                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI NEFROLOGI') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPersetujuanTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Persetujuan Tindakan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPemakaianTerapoEri()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pemakaian Terapi Eritropotine
                                    </span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Informasi Dan Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirBuktiCanggih()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Bukti Canggih</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="pengkajianJatuhDewasa()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pengkajian Resiko Jatuh Dewasa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPeresepanHemo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Peresepan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="resumeTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resume Tindakan Hemodialisis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAsuhanObservasi2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Asuhan Keperawatan dan Observasi Pasien
                                      Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="informasiEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Asesmen Kebutuhan Informasi & Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabDataUmumHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Data Umum HD
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="statusHemodialisa()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Status Hemodialisa</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- Dokter Poli Nefrologi -->
                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('DOKTER') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI NEFROLOGI') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onAssmedHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Medis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Informasi Dan Edukasi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPemakaianTerapoEri()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pemakaian Terapi Eritropotine
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirBuktiCanggih()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Bukti Canggih</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPersetujuanTindakanHD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Persetujuan Tindakan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPeresepanHemo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Peresepan Hemodialisa
                                    </span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabMedis()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Assesmen Medis</span>
                                  </a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat radioterapi -->

                        <!-- <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') !== -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="catatanKegiatanUOR()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Catatan Kegiatan</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabLembarPenyinaran()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Lembar Penyinaran</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabSkemaPenyinaran()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Skema Penyinaran</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabVerifikasiEpid()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Verif/Epid</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabFisikaRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Fisika Radioterapi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabJadwalKunjunganRehabDanFisio()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Jadwal
                                      Kunjungan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div> -->

                        <!-- igd ponek -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('IGD-PONEK') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="asesmenKandungan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Awal Kandungan Kebidanan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="triagePasienGinekologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Triage Pasien Obstetri Ginekologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="informasiEdukasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Informasi Edukasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabImplementasiKeperawatanIGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Implementasi
                                      Keperawatan IGD</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="pengkajianEWS()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">AEWS</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="tabMEOWS()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">MEOWS</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="triagePasienGinekologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Triage Pasien Ginekologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="persetujuanTindakanKedokteranSHK()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Persetujuan Tindakan
                                      Kedokteran SHK</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="penolakanTindakanKedokteranSHK()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penolakan Tindakan Kedokteran SHK</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanPemberianObat()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Pemberian Obat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formKeseimbanganCairan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Keseimbangan Cairan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirPemantauanKateter()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Pemantauan Kateter Intravena Perifer</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- dokter igd -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('DOKTER-IGD') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabMedis_IGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Medis IGD</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabEKG_IGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">EKG</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="ctgIGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CTG</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="suketGadar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Surat Keterangan Gawat Darurat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="suratPermintaanDirawat()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Surat Permintaan Dirawat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="perkiraanBiaya()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Perkiraan Biaya</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Berkas Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanPemberianObat()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Pemberian Obat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- bedah -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('DOKTER') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('BEDAH SENTRAL') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabMedis()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Assesmen Medis</span>
                                  </a>
                                </li>
                              </ul>

                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laporan Operasi' || TAB_DEFAULT == 'Laporan Operasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="obTabLaporanOperasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laporan Operasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Lembar Pencatatan Sedasi' || TAB_DEFAULT == 'Lembar Pencatatan Sedasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onLembarsedasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Lembar Pencatatan Sedasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'LAsuhan Keperawatan Peri - Operatif' || TAB_DEFAULT == 'Asuhan Keperawatan Peri - Operatif' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onAsuhanKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Asuhan Keperawatan Peri - Operatif</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Checklist Keselamatan Operasi' || TAB_DEFAULT == 'Checklist Keselamatan Operasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onChecklistKeselamatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Checklist Keselamatan Operasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Checklist Keselamatan Anestesi' || TAB_DEFAULT == 'Checklist Keselamatan Anestesi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onChecklistanatesi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CHECKLIST KESIAPAN ANESTHESI</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Assesment Pra Anestesi / Sedasi' || TAB_DEFAULT == 'Assesment Pra Anestesi / Sedasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onpraanestesisedasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesment Pra Anestesi / Sedasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Lembar Pencatatan Sedasi' || TAB_DEFAULT == 'Lembar Pencatatan Sedasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onlembarpencatatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Lembar Pencatatan Sedasi</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat bedah -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('BEDAH') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('BEDAH') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laporan Operasi' || TAB_DEFAULT == 'Laporan Operasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="obTabLaporanOperasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laporan Operasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Lembar Pencatatan Sedasi' || TAB_DEFAULT == 'Lembar Pencatatan Sedasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onLembarsedasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Lembar Pencatatan Sedasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'LAsuhan Keperawatan Peri - Operatif' || TAB_DEFAULT == 'Asuhan Keperawatan Peri - Operatif' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onAsuhanKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Asuhan Keperawatan Peri - Operatif</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Checklist Keselamatan Operasi' || TAB_DEFAULT == 'Checklist Keselamatan Operasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onChecklistKeselamatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Checklist Keselamatan Operasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Checklist Keselamatan Anestesi' || TAB_DEFAULT == 'Checklist Keselamatan Anestesi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onChecklistanatesi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CHECKLIST KESIAPAN ANESTHESI</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Assesment Pra Anestesi / Sedasi' || TAB_DEFAULT == 'Assesment Pra Anestesi / Sedasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onpraanestesisedasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesment Pra Anestesi / Sedasi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && userLogin.namaUser.indexOf('his') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Lembar Pencatatan Sedasi' || TAB_DEFAULT == 'Lembar Pencatatan Sedasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onlembarpencatatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Lembar Pencatatan Sedasi</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- psikolog -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('DOKTER') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PSIKOLOG') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation"><a role="menuitem" class="p-menuitem-link"
                                    aria-label="Dashboard" tabindex="0" @click="onTabAsesmenPsikologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen
                                      Psikologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanNurse()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Nurse Station</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>

                        <!-- dokter ruangan igd -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('DOKTER') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabMedis_IGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Medis IGD</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabEKG_IGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">EKG</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="ctgIGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CTG</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="suketGadar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Surat Keterangan Gawat Darurat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="suratPermintaanDirawat()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Surat Permintaan Dirawat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="perkiraanBiaya()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Perkiraan Biaya</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Berkas Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanPemberianObat()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Pemberian Obat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>


                        <!-- dokter BEDAH SENTRAL -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('DOKTER') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('BEDAH SENTRAL') == -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('TERAPI WICARA') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabJadwalFisio()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Jadwal Kunjungan Rehab & Fisio</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI GIZI') > -1 || userLogin.id == '875' && selectedRegistrasi.namadepartemen.toUpperCase().indexOf('RAWAT INAP') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="asesmenAwalGizi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Gizi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namadepartemen.toUpperCase().indexOf('RAWAT INAP') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabpengkajianAwalMedisIntensive()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Pengkajian Awal Medis Intensive</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEMOTERAPI') !== -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabExtravasasi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Pengkajian Extravasasi</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-else-if="selectedRegistrasi.objectdepartemenfk == 16">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabMedisRI()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Medis Rawat Inap</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namadepartemen.toUpperCase().indexOf('RAWAT INAP') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="obTabLaporanOperasi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Laporan Operasi</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation" v-else>
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabMedis()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Assesmen Medis</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabLembarPenyinaranRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Lembar Penyinaran</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabSkemaPenyinaranRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Skema Penyinaran</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabFisikaRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Fisika Radioterapi</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabVerifikasiEpid()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Verifikasi Epid</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabCatatanKegiatanRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Catatan Kegiatan</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('KESEHATAN TRADISIONAL') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKestrad()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Komplementer</span></a>
                                </li>

                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('GIZI') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabGiziGeriatri()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Asesmen Gizi Geriatri</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('LAKTASI') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonselorMenyusui()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Konselor Menyusui</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('LAKTASI') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPengamatanMenyusui()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pengamatan Menyusui</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('VCT') > -1 && kelompokUser != 'dokter'"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanVCT()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan VCT</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('VCT') > -1 && kelompokUser != 'dokter'"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onIkhtisarPasien()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Form Ikhtisar Pasien</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI VCT') > -1 && kelompokUser != 'dokter'"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAsesmenKonselor()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Konselor</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI VCT') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKunjunganVCT()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Form Kunjungan VCT</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabRadiasi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Perencanaan Radiasi Ekterna</span>
                                  </a>
                                </li>
                                <!-- <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabRadiasi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Perencanaan Radiasi Ekterna</span>
                                  </a>
                                </li> -->
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabClinicalDrawing()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Clinical Drawing</span>
                                  </a>
                                </li>
                                <!-- <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabClinicalDrawing()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Clinical Drawing</span>
                                  </a>
                                </li> -->
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabPREQC2()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">PRE-QC 2</span>
                                  </a>
                                </li>
                                <!-- <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabPREQC2()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">PRE-QC 2</span>
                                  </a>
                                </li> -->
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabPengkajianHarian()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Pengkajian Harian</span>
                                  </a>
                                </li>
                                <!-- <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabPengkajianHarian()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Pengkajian Harian</span>
                                  </a>
                                </li> -->
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabSkriningUmum()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Skrining Umum Pasien Baru</span>
                                  </a>
                                </li>
                                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && (selectedRegistrasi.namaruangan.toUpperCase().indexOf('PARU') > -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('THT') > -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('BEDAH DIGESTIVE') > -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('ONKOLOGI') > -1)">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenjadwalanKemoterapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penjadwalan Kemoterapi</span></a>
                                </li>
                              </ul>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabPersetujuanKedokteranNuklir()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Persetujuan Tindakan Kedokteran KN</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabFormIdentitas()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Formulir Identitas Dan Informasi Tentang Pasien
                                    </span>
                                  </a>
                                </li>
                                <!-- <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabSkriningUmum()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Skrining Umum Pasien Baru</span>
                                  </a>
                                </li> -->
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabPreQC1()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">PRE-QC 1</span>
                                  </a>
                                </li>
                                <!-- <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabPreQC1()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">PRE-QC 1</span>
                                  </a>
                                </li> -->
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabQAPasien()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">QA Pasien</span>
                                  </a>
                                </li>
                                <!-- <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabQAPasien()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">QA Pasien</span>
                                  </a>
                                </li> -->
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabCatatanKegiatanOnkrad()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Catatan Kegiatan</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="persetujuanProsedurTindakanOR()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Prosedur Tindakan</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="persetujuanTindakanKedokteran()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Persetujuan Tindakan</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabCatatanKegiatanOnkrad()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Catatan Kegiatan</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation" v-if="
                                    selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1 ||
                                    selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1
                                  ">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="laporanSelesaiRadiasi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Laporan Selesai Radiasi</span>
                                  </a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation" v-if="
                                    selectedRegistrasi.namaruangan.toUpperCase().indexOf('PERINATOLOGI') > -1 ||
                                    selectedRegistrasi.namaruangan.toUpperCase().indexOf('TUNJUNG') > -1
                                  ">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabAsesmenMedisNeonatus()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Medis Neonatus</span>
                                  </a>
                                </li>
                              </ul>


                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPeriodonsia()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Periodonsia</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTindakan = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="modalResep = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') == -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="modalLab = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') == -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('KULIT') == -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="ModalRadiologi = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('KULIT') == -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="modalPenunjangKhusus = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalBerkasDokter = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Berkas Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="ModalOrderBedah = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namadepartemen.toUpperCase().indexOf('RAWAT JALAN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="user != null && (user.id == 397 || user.id == 314 || user.id == 1355)">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanPemberianObat()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Pemberian Obat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namadepartemen.toUpperCase().indexOf('RAWAT INAP') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabFormulirDPJP()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Dokter Penanggung Jawab Pelayanan (DPJP)</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namadepartemen.toUpperCase().indexOf('RAWAT INAP') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="ringkasanPulangRI()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan Pulang</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namadepartemen.toUpperCase().indexOf('RAWAT INAP') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="ringkasanMasukKeluarRI()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan Masuk dan Keluar Rawat Inap</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabKNInVivo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN In Vivo</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabKNInVitro()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN In Vitro</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabKNTerapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN Terapi</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- laboratorium -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('LABORAT') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- farmasi -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('FARMASI') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRekonMasuk()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rekonsiliasi Obat Saat Admisi</span></a>
                                </li>
                              </ul>

                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>

                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirMESO()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">MESO</span></a>
                                </li>
                              </ul>

                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul> -->

                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul> -->

                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTindakan = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>

                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRekonPindah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rekonsiliasi Obat Saat Pindah/Transfer</span></a>
                                </li>
                              </ul>

                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRekonKeluar2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rekonsiliasi Obat Saat Discharge</span></a>
                                </li>
                              </ul>

                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>



                        <!-- radiologi -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('RADIOLOGI') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- nurse station -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('NURSE-STATION') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanNurse()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Nurse Station</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidananNurse()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Nurse Station</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidanan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Kebidanan</span></a>
                                </li>
                              </ul> -->
                            </div>
                          </div>
                        </div>

                        <!-- perawat ranap -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT-RANAP') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabVital()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Vital Sign</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanRanap()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Ranap</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRencanaKeperawatanRanap()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rencana Keperawatan Ranap</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar" v-if="selectedRegistrasi.namaruangan != null
                                && (selectedRegistrasi.namaruangan.toUpperCase().indexOf('RAWAT INAP VK') > -1
                                  || selectedRegistrasi.namaruangan.toUpperCase().indexOf('RAWAT INAP TUNJUNG') > -1
                                  || selectedRegistrasi.namaruangan.toUpperCase().indexOf('RUANG VK') > -1)">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="asesmenKandungan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Awal Kandungan Kebidanan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar" v-if="selectedRegistrasi.namaruangan != null
                                && (selectedRegistrasi.namaruangan.toUpperCase().indexOf('RAWAT INAP VK') > -1)">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="triagePasienGinekologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Triage Pasien Obstetri Ginekologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar" v-if="selectedRegistrasi.namaruangan != null
                                && selectedRegistrasi.namaruangan.toUpperCase().indexOf('SANDAT') == -1
                                && selectedRegistrasi.namaruangan.toUpperCase() != 'JEPUN'
                                && selectedRegistrasi.namaruangan.toUpperCase().indexOf('CEMPAKA') == -1
                                && selectedRegistrasi.namaruangan.toUpperCase().indexOf('KASUARI') == -1
                                && selectedRegistrasi.namaruangan.toUpperCase().indexOf('MERAK') == -1
                                && selectedRegistrasi.namaruangan.toUpperCase().indexOf('RAWAT INAP SUITE') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanIntensif()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Intensif</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabImplementasiKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Implementasi Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="evaluasiKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Evaluasi Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar" v-if="selectedRegistrasi.namaruangan != null
                                && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PERINATOLOGI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanNeonatus()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Neonatus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRekonMasuk()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rekonsili Obat Masuk</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRekonKeluar2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rekonsili Obat Keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="modalLab = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') == -1 || selectedRegistrasi.namaruangan.toUpperCase().indexOf('KULIT') == -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="ModalRadiologi = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- rekam medis -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('REKAM') > -1 && selectedRegistrasi.namaruangan != null">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabMedis()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Assesmen Medis</span>
                                  </a>
                                </li>
                                <!-- <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onRingkasanKeluar()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Resume Medis/Ringkasan Keluar</span>
                                  </a>
                                </li> -->
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="perkiraanBiaya()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Perkiraan Biaya</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat igd -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('IGD') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTriageIGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Triage IGD</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanIGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Asesmen Awal Keperawatan IGD</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabImplementasiKeperawatanIGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Implementasi
                                      Keperawatan IGD</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="perkiraanBiaya()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Perkiraan Biaya</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="pengkajianEWS()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">AEWS</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formTPIntraRS()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Transfer Pasien Intra RS</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBerkasPasien()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Berkas Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="modalTransferPasien = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabEKG_IGD()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">EKG</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- nuklir -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('NUKLIR') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanNurse()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Nurse Station</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidananNurse()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Nurse Station</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidanan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Kebidanan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabImplementasiKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Implementasi Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAmprahInVivo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Amprah In Vivo</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAmprahInVitro()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Amprah In Vitro</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKNInVivo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN In Vivo</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKNInVitro()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN In Vitro</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKNTerapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN Terapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanPemberianObatNuklir()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Pemberian Obat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat ranap nuklir -->

                        <div class="column is-12"
                          v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1 && selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('RAWAT INAP KEDOKTERAN NUKLIR') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabVital()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Vital Sign</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanRanap()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Ranap</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRencanaKeperawatanRanap()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rencana Keperawatan Ranap</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabImplementasiKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Implementasi Keperawatan</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAmprahInVivo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Amprah In Vivo</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAmprahInVitro()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Amprah In Vitro</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabKNInVivo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN In Vivo</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabKNInVitro()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN In Vitro</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabKNTerapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN Terapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formKeseimbanganCairan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Keseimbangan Cairan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanPemberianObat()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Pemberian Obat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="pengkajianJatuhDewasa()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pengkajian Resiko Jatuh Dewasa
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="evaluasiKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Evaluasi Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="pengkajianEWS()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">AEWS</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRekonMasuk()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rekonsili Obat Masuk</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRekonKeluar2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Rekonsili Obat Keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="modalLab = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="ModalRadiologi = true"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- perawat rajal -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('PERAWAT') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('TERAPI WICARA') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabJadwalFisio()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Jadwal Kunjungan Rehab & Fisio</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem" v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('TERAPI WICARA') > -1"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1 && selectedRegistrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanNurse()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Nurse Station</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidananNurse()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Nurse Station</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.namaruangan != null
                                  && selectedRegistrasi.namaruangan.toUpperCase().indexOf('RAWAT INAP KEDOKTERAN NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanRanap()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan Ranap</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('HEMODIALISIS') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="formulirBuktiCanggih()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Formulir Bukti Canggih</span></a>
                                </li>
                              </ul>
                              <ul
                                v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1"
                                class="p-tabmenu-nav p-reset content-scroll">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabCatatanKegiatanRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Catatan Kegiatan</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI GIZI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="asesmenAwalGizi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Gizi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI GIZI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="asesmenAwalGiziGeriatri()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Asesmen Gizi Geriatri</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1 && selectedRegistrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('VCT') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatanVCT()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan VCT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPREQC2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">PRE-QC 2</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('VCT') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onIkhtisarPasien()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Form Ikhtisar Pasien</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('VCT') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="ikhtisarPerawatanPasienHIV()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ikhtisar Perawatan Pasien HIV</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanKegiatanOnkrad()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Kegiatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidanan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Kebidanan</span></a>
                                </li>
                              </ul>
                              <ul
                                v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1"
                                class="p-tabmenu-nav p-reset content-scroll">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabLembarPenyinaranRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Lembar Penyinaran</span>
                                  </a>
                                </li>
                              </ul>
                              <ul
                                v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1"
                                class="p-tabmenu-nav p-reset content-scroll">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabSkemaPenyinaranRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Skema Penyinaran</span>
                                  </a>
                                </li>
                              </ul>
                              <ul
                                v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1"
                                class="p-tabmenu-nav p-reset content-scroll">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabFisikaRadioterapi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Fisika Radioterapi</span>
                                  </a>
                                </li>
                              </ul>
                              <ul
                                v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1"
                                class="p-tabmenu-nav p-reset content-scroll">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabVerifikasiEpid()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Verifikasi Epid</span>
                                  </a>
                                </li>
                              </ul>
                              <ul
                                v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('UNIT RADIOTERAPI') > -1"
                                class="p-tabmenu-nav p-reset content-scroll">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabRadiasi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Perencanaan Radiasi Ekterna</span>
                                  </a>
                                </li>
                              </ul>

                              <ul
                                v-if="selectedRegistrasi.namaruangan != undefined && selectedRegistrasi.namaruangan.toUpperCase().indexOf('KEMOTERAPI') > -1"
                                class="p-tabmenu-nav p-reset content-scroll">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabExtravasasi()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Pengkajian Ekstravasasi</span>
                                  </a>
                                </li>
                              </ul>

                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Perencanaan Radiasi Ekterna</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Perencanaan Radiasi Ekterna</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabClinicalDrawing()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Clinical Drawing</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"
                                  v-if="selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabQAPasien()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">QA Pasien</span>
                                  </a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabClinicalDrawing()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Clinical Drawing</span></a>
                                </li>
                              </ul> -->
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPREQC2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">PRE-QC 2</span></a>
                                </li>
                              </ul> -->
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPREQC2()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">PRE-QC 2</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPengkajianHarian()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pengkajian Harian</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPengkajianHarian()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Pengkajian Harian</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabSkriningUmum()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Skrining Umum Pasien Baru</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabSkriningUmum()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Skrining Umum Pasien Baru</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPreQC1()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">PRE-QC 1</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPreQC1()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">PRE-QC 1</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabQAPasien()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">QA Pasien</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabQAPasien()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">QA Pasien</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanKegiatanOnkrad()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Kegiatan</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanKegiatanOnkrad()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Kegiatan</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI FISIOTERAPI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAsesmenFisioterapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Fisioterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PSIKOLOGI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAsesmenPsikologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Psikologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI VCT') > -1"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKunjunganVCT()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Form Kunjungan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('VCT') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAsesmenKonselor()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Konselor</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('VCT') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAsesmenPDP()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen PDP</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && (selectedRegistrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') > -1)">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="penjadwalanRadioterapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penjadwalan
                                      Radioterapi
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabImplementasiKeperawatanNuklir()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Implementasi
                                      Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar" v-if="selectedRegistrasi.norec != undefined && pasien && (
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('HEMATOLOGI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('BEDAH ONKOLOGI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1
                              )">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar" v-if="selectedRegistrasi.norec != undefined && pasien && (
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('HEMATOLOGI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('BEDAH ONKOLOGI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1
                              )">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabResep()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep
                                    </span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar" v-if="selectedRegistrasi.norec != undefined && pasien && (
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('HEMATOLOGI') > -1 ||
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('BEDAH ONKOLOGI') > -1
                              )">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi
                                    </span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar" v-if="selectedRegistrasi.norec != undefined && pasien && (
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') == -1 &&
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') == -1 &&
                                selectedRegistrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') == -1
                              )">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.namaruangan != null && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBerkasPasien()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Berkas Pasien</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabJadwalKunjunganRehabDanFisio()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Jadwal Kunjungan
                                    </span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') == -1 && selectedRegistrasi.namaruangan.toUpperCase().indexOf('RADIOTERAPI') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanPemberianObat()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Pemberian Obat</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="catatanPemberianObatNuklir()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Pemberian Obat</span></a>
                                </li>
                              </ul>
                              <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAmprahInVivo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Amprah Vivo</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabAmprahInVitro()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Amprah Vitro</span></a>
                                </li>
                              </ul> -->
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKNInVivo()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN In Vivo</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKNInVitro()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN In Vitro</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('NUKLIR') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKNTerapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">KN Terapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- radioterapi -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('RADIOTERAPI') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidanan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Kebidanan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>

                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabCatatanKegiatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Catatan Kegiatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLembarPenyinaran()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Lembar Penyinaran</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabSkemaPenyinaran()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Skema Penyinaran</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabFisika()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Fisika Radioterapi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- jenazash -->

                        <div class="column is-12" v-else-if="kelompokUser.toUpperCase().indexOf('JENAZAH') > -1">
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton>
                          </div>
                        </div>

                        <!-- lainnya -->

                        <div class="column is-12" v-else>
                          <TabMenu :model="[]" :scrollable="true" />
                          <div class="p-tabmenu-uy">
                            <div class="p-tabmenu p-component p-no-width">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="setDashboard()">
                                    <span class="p-menuitem-icon fas fa-laptop-medical"></span><span
                                      class="p-menuitem-text">Home</span></a>
                                </li>
                              </ul>
                            </div>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabMedis()">
                                    <span class="p-menuitem-icon fas fa-book-medical"></span>
                                    <span class="p-menuitem-text">Assesmen Medis</span>
                                  </a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabVital()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Vital Sign</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKonsultasi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Transfer Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Konsultasi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenjadwalanKemoterapi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penjadwalan Kemoterapi</span></a>
                                </li>
                              </ul>
                            </div>
                            <!-- <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold>
                            </VIconButton>
                            <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabVital()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Vital Sign</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabMedis()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Medis</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeperawatan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Keperawatan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('OBGYN') > -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKebidanan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Assesmen Kebidanan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTab()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">CPPT</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Tindakan' || TAB_DEFAULT == 'Tindakan' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabTindakan()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Tindakan</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Order Resep' || TAB_DEFAULT == 'Order Resep' ? 'active' : '']"
                                  role="presentation">
                                  <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                                    @click="onTabResep()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Resep</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Laboratorium' || TAB_DEFAULT == 'Laboratorium' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabLaboratorium()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Laboratorium</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Radiologi' || TAB_DEFAULT == 'Radiologi' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabRadiologi()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Radiologi</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Bedah' || TAB_DEFAULT == 'Bedah' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabBedah()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Bedah</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Penunjang' || TAB_DEFAULT == 'Penunjang' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabPenunjang()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Penunjang Khusus</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien && selectedRegistrasi.namaruangan.toUpperCase().indexOf('IGD') == -1">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKontrol()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Kontrol Pasien</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                v-if="selectedRegistrasi.norec != undefined && pasien">
                                <li class="p-tabmenuitem"
                                  :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                  role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                    tabindex="0" @click="onTabKeluar()"><span
                                      class="p-menuitem-icon fas fa-book-medical"></span><span
                                      class="p-menuitem-text">Ringkasan keluar</span></a>
                                </li>
                              </ul>
                              <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                                <li class="p-tabmenuitem" :class="TAB_ACTIVE == items.label ? 'active' : ''"
                                  @click="onTabClick(items)" role="presentation" v-for="(items, index) in TAB_ITEMS"
                                  :key="items.label" v-tooltip-prime.bottom="items.label">

                                  <a role="menuitem" class="p-menuitem-link tooltiptext"
                                    :class="TAB_ITEMS.length > 10 ? 'p-menuitem-mengecil' : ''"
                                    aria-label="Documentation" tabindex="-1">
                                    <span class="p-menuitem-icon pi pi-fw pi-file"></span>
                                    <span class="p-menuitem-text " :class="'p-menuitem-mengecil'">{{ items.label }}
                                    </span>
                                    <span class="ml-2 p-menuitem-icon pi pi-fw pi-times-circle "
                                      style="color: var(--danger);" @click="removeTAB(index)"></span>
                                  </a>

                                </li>
                              </ul>
                            </div>
                            <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold>
                            </VIconButton> -->
                          </div>
                        </div>

                      </div>
                      <div class="columns is-multiline" v-if="TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard'">

                        <!-- FOR DEV MODE DITAMBAHKAN VITE_REVISION_DEV & AKAN DEFAULT MASUK KE NORMAL JIKA TIDAK DITAMBAHKAN ( SEMENTARA ) -->
                        <!-- LEBIH BAIK UNTUK TIDAK MENDEVELOP APAPUN TERLEBIH DAHULU PADA TEmrDetail -->

                        <div class="column " v-if="item.isRekap == false"
                          :class="[hideRiwayat == true ? 'is-12' : 'is-12', classActiveNavEMR != '' ? 'is-tablet-12' : '']">
                          <TEmrDetail :registrasi="selectedRegistrasi" :pasien="pasien" :alamat="alamat"
                            :skdp="dataSKDP" :riwayat="riwayatPemeriksaan" :hideRiwayat="hideRiwayat"
                            @showRiwayat="showRiwayat()" @reloadRiwayat="reloadRiwayat()"
                            @billingPasien="billingPasien()" @hiddenRiwayat="hiddenRiwayat()"
                            @suratIbu="suratIbu()"
                            :isLoadingRiwayat="isLoadingRiwayat" :isPasienAktif="PASIEN_AKTIF"
                            @showMenuEMR="showMenuEMR()" @editEMR="editEMR" @hapusEMR="hapusEMR" @cetakEMR="cetakEMR"
                            @openEMR="openEMR" @showNavStatus="showNavStatus">
                          </TEmrDetail>
                        </div>

                        <div v-else v-if="item.isRekap == true">
                          <div class="column "
                            :class="[hideRiwayat == true ? 'is-12' : 'is-12', classActiveNavEMR != '' ? 'is-tablet-12' : '']">
                            <TEmrDetailRev :registrasi="selectedRegistrasi" :pasien="pasien" :alamat="alamat"
                              :skdp="dataSKDP" :riwayat="riwayatPemeriksaan" :hideRiwayat="hideRiwayat"
                              @onTabRadiologi="onTabRadiologi()" @onTabLaboratorium="onTabLaboratorium()"
                              @onTabMedis="onTabMedis()" @onTabKeperawatan="onTabKeperawatan()"
                              @showRiwayat="showRiwayat()" @reloadRiwayat="reloadRiwayat()"
                              @billingPasien="billingPasien()" @hiddenRiwayat="hiddenRiwayat()"
                              @suratIbu="suratIbu()"
                              :isLoadingRiwayat="isLoadingRiwayat" :isPasienAktif="PASIEN_AKTIF"
                              @showMenuEMR="showMenuEMR()" @editEMR="editEMR" @hapusEMR="hapusEMR" @cetakEMR="cetakEMR"
                              @openEMR="openEMR" @showNavStatus="showNavStatus">
                            </TEmrDetailRev>
                          </div>
                        </div>
                      </div>
                      <div class="columns is-multiline" v-if="TAB_ACTIVE != 'Dashboard'">
                        <div class="column is-12">
                          <RouterView v-slot="{ Component, route }" :pasien="pasien" :alamat="alamat"
                            :registrasi="selectedRegistrasi" :FORM_NAME="TAB_ACTIVE" :FORM_URL="TAB_URL"
                            :COLLECTION="COLLECTION" v-if="pasien.nocm">
                            <component :is="Component" />
                          </RouterView>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </div>

    <VModal :open="modalFilter" :title="'Periode Registrasi ' + H.formatDateOnly(item.filterTgl.start)
      + ' s/d ' + H.formatDateOnly(item.filterTgl.end)" :noclose="true" size="small" actions="right"
      @close="modalFilter = false">
      <template #content>
        <form class="modal-form">
          <div class="columns">
            <div class="column is-12" style="text-align: center">
              <VField class="is-centered">
                <v-date-picker v-model="item.filterTgl" is-range class="is-centered" />
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:search" @click="reloadListReg()" :loading="isLoadingFilter" color="primary" raised>
          Filter</VButton>
      </template>
    </VModal>

    <VModal :open="showModalAlergi" title="Form Input Alergi Pasien" :noclose="true" size="small" actions="right"
      @close="showModalAlergi = false">
      <template #content>
        <form class="modal-form">
          <div class="column is-12 pt-0 pb-0">
            <VField label="Alergi">
              <VControl>
                <VTextarea v-model="item.alergi" rows="3" placeholder="Alergi">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </form>
      </template>
      <template #action>
        <VButton @click="saveAlergiPasien(item.alergi)" :loading="isBtnLoading" color="primary" raised>
          Simpan</VButton>
      </template>
    </VModal>


    <VModal :open="modalMENU" title="Action" :noclose="false" :size="classMODALMENU" actions="right"
      @close="modalMENU = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <UIWidget class="search-widget">
                <template #body>
                  <div class="field">
                    <div class="control">
                      <input type="text" v-model="filterMenu" class="input" placeholder="Search..." />
                      <button class="searcv-button" type="button">
                        <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                      </button>
                    </div>
                  </div>
                </template>
              </UIWidget>
              <ListWidgetEMR :title="classMODALMENU == 'medium' ? 'List Menu' : 'Kembali'" rounded
                class="list-widget-v1" @backMenuEMR="backMenuEMR()">
                <div class="list-menu" v-if="classMODALMENU == 'medium'">
                  <div class="columns is-multiline" v-if="filteredMenu.length == 0">
                    <div class="column is-12" v-for="index of 10">
                      <VPlaceloadText :lines="1" />
                    </div>
                  </div>
                  <div v-for="items in filteredMenu" :key="items.name"
                    class="inner-list-item media-flex-center is-clickable" @click="showMenu(items)">
                    <VIconBox :color="items.color">
                      <i aria-hidden="true" :class="items.icon"></i>
                    </VIconBox>
                    <div class="flex-meta is-light">
                      <a>{{ items.name }}</a>
                      <span>{{ items.desc }}</span>
                    </div>
                    <div class="flex-end">
                      <a class="go-icon is-up">
                        <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="list-menu" v-else>
                  <!-- <pre>{{listMenuEMR}}</pre> -->
                  <!-- <pre>{{listMenuEMRBundle}}</pre> -->
                  <div class="columns is-multiline" v-if="listMenuEMR.length == 0 && listMenuEMRBundle.length == 0">
                    <div class="column is-4" v-for="index of 30">
                      <VPlaceloadText :lines="1" />
                    </div>
                  </div>
                  <TNavigasiEmr v-if="listMenuEMR.length && listMenuEMRBundle.length == 0" :menuEMR="listMenuEMR"
                    :filterMenu="filterMenu" @accessMenu="accessMenuEMR" :totalMenu="totalMenu"></TNavigasiEmr>
                  <TNavigasiEmrBundle v-if="listMenuEMRBundle.length" :menuEMR="listMenuEMRBundle"
                    :filterMenu="filterMenu" @accessMenu="accessMenuDetail" @accessMenuEMR="accessMenuEMR"
                    :totalMenu="totalMenu" :detail="listMenuEMRBundleDetail" :isLoading="isLoading">
                  </TNavigasiEmrBundle>
                </div>
              </ListWidgetEMR>
            </div>
          </div>
        </form>
      </template>
    </VModal>
    <VModal :open="modalProfil" title="Profil Pasien" actions="right" @close="modalProfil = false" :size="'medium'">
      <template #content>
        <VTabs slider selected="Pasien" :tabs="[
          { label: 'Data Diri', value: 'Pasien' },
          { label: 'Alamat', value: 'Alamat' },
          { label: 'Keluarga', value: 'Keluarga' },
        ]">

          <template #tab="{ activeValue }">
            <p v-if="activeValue === 'Pasien'">
            <form class="modal-form">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField label="Nomor Identitas">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.noidentitas" placeholder="NIK" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="No BPJS">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.nobpjs" placeholder="No BPJS" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Nama Pasien">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.namapasien" placeholder="Nama Pasien" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tempat Lahir">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.tempatlahir" placeholder="Tempat Lahir" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tanggal Lahir">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.tgllahir" placeholder="Tanggal Lahir" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Jenis Kelamin">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.jeniskelamin" placeholder="Jenis Kelamin" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Agama">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.agama" placeholder="Agama" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Status Perkawinan">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.statusperkawinan" placeholder="Status Perkawinan"
                        class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Golongan Darah">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.golongandarah" placeholder="Status Perkawinan"
                        class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Pendidikan">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.pendidikan" placeholder="Pendidikan" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Pekerjaan">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.pekerjaan" placeholder="Pekerjaan" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Etnis">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.etnis" placeholder="Etnis" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Kebangsaan">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.kebangsaan" placeholder="Kebangsaan" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Negara">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.negara" placeholder="Negara" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
              </div>
            </form>
            </p>
            <p v-else-if="activeValue === 'Alamat'">
            <form class="modal-form">
              <div class="columns is-multiline">
                <div class="column is-9">
                  <VField label="Alamat Lengkap">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.alamatlengkap" placeholder="Alamat" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="Kode Pos">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.kodepos" placeholder="Kode Pos" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Kelurahan">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.namakelurahan" placeholder="Kelurahan" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Kecamatan">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.namakecamatan" placeholder="Kecamatan" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Kota / Kabupaten">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.namakotakabupaten" placeholder="Tanggal Lahir"
                        class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Propinsi">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.namapropinsi" placeholder="Propinsi" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>

              </div>
            </form>
            </p>
            <p v-else="activeValue ==='Keluarga'">
            <form class="modal-form">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VField label="No. Telepon">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.notelp" placeholder="Nomor Telepon" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="No. Handphone">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.nohp" placeholder="Nomor Handphone" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Nama Ayah">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.namaayah" placeholder="Kelurahan" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Nama Ibu">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="info.namaibu" placeholder="Kecamatan" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
              </div>
            </form>

            </p>
          </template>

        </VTabs>
      </template>
    </VModal>
    <VModal :open="modalCatatan" title="Catatan Pasien" noclose @close="modalCatatan = false" :size="'medium'"
      actions="right">
      <template #content>
        <div class="column is-12">
          <VTabs slider selected="history" :tabs="[
            { label: 'History', value: 'history' },
            { label: 'Tambah', value: 'tambah' },
          ]">
            <template #tab="{ activeValue }">
              <p v-if="activeValue === 'history'">
              <div class="column px-0">
                <div class="list-view-v3">
                  <div class="list-view-item" v-for="(data, i) in dokterCatatan" :index="i">
                    <p>{{ data.catatan }}</p>
                    <div class="list-view-item-inner mt-2" style="max-height:1000px;overflow: auto;">
                      <span>
                        <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                        <span>{{ data.tanggal }}</span>
                        <i aria-hidden="true" class="iconify" data-icon="feather:user"></i>
                        <span>{{ data.dokter }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              </p>
              <p v-if="activeValue === 'tambah'">
              <form class="modal-form">
                <div class="columns is-multiline">
                  <div class="column is-12 mt-2-min">
                    <VField label="Tanggal:">
                      <VDatePicker v-model="item.tglregistrasi" mode="dateTime" style="width: 100%" trim-weeks
                        :min-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField label="Catatan">
                      <VTextarea rows="8" placeholder="Catatn..." v-model="item.catatanDokter"></VTextarea>
                    </VField>
                  </div>
                </div>
              </form>
              </p>
            </template>
          </VTabs>
        </div>
      </template>
      <template #action>
        <VButton icon="feather:save" :loading="isLoading" @click="saveCatatanDokter()" color="primary" raised>Simpan
        </VButton>
      </template>
    </VModal>

    <VModal :open="modalHasilLab" title="Hasil Laboratorium" actions="right" @close="modalHasilLab = false"
      :size="'medium'">
      <template #content>
        <div class="column is-12" v-if="riwayatPemeriksaan.LIST_LAB.length > 0">
          <div class="project-files">
            <div class="widget creative-list-widget">
              <div class="widget-toolbar">

                <div class="right">
                  <!-- showMenu({ 'form': 'module-emr-order-laboratorium' }) -->
                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                    @click="emits('openEMR', { namaemr: 'Laboratorium', url_form: 'order-laboratorium' })">View
                    All</a>
                </div>
              </div>

              <div class="creative-list panjang-250">
                <VTag class="mr-1 mb-1" :color="'success'" :label="'Normal'" />
                <VTag class="mr-1 mb-1" :color="'danger'" :label="'Tinggi/Rendah Kritis'" />
                <VTag class="mr-1 mb-1" :color="'warning'" :label="'Tinggi/Rendah'" />
                <div v-for="item in riwayatPemeriksaan.LIST_LAB" :key="item.id" class=" mb-2">
                  <div :class="item.isdetail ? '' : ''">
                    <div class="creative-list-item  is-clickable" :class="'is-' + item.color_status"
                      style="margin-bottom:0;" :style="item.isdetail ? 'height:60%;border-radius: 10px 10px 0 0;' : ''"
                      @click="item.isdetail = !item.isdetail">
                      <i aria-hidden="true" :class="item.icon"></i>
                      <div class="meta">
                        <p>{{ item.namaproduk }}</p>
                        <span>{{ item.tglorder }}</span>

                      </div>
                      <VTag :color="(item.status == 'verifikasi' ? 'info' : '')"
                        :label="item.hasil_lab.length == 0 ? item.status : 'selesai'"
                        class="mt-0 ml-5 is-pulled-right" />
                    </div>
                    <div v-if="item.isdetail"
                      style="border-radius: 0 0 10px 10px; background-color: rgb(249 235 242); padding:  0 10px 10px 10px;"
                      class="f-table">
                      <table class="w-100">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Pemeriksaan</th>
                            <th>Hasil</th>
                            <th>Nilai Normal</th>
                          </tr>
                        </thead>
                        <tbody v-if="item.hasil_lab.length > 0" v-for="(items_d, rowIndex) in item.hasil_lab"
                          :key="rowIndex">
                          <tr v-if="riwayatPemeriksaan.LIST_LAB_GROUP[items_d.treatment_name] != undefined &&
                            riwayatPemeriksaan.LIST_LAB_GROUP[items_d.treatment_name].index === rowIndex">
                            <td colspan="3">
                              <span class="f-bold f-italic">{{ items_d.treatment_name
                              }}</span>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <VTag class="mb-1-min"
                                :color="(items_d.flag == '' ? 'success' : (items_d.flag == 'H' ? 'danger' : 'warning'))"
                                :label="''" />
                            </td>
                            <td>{{ items_d.examination_name }}</td>
                            <td><b>{{ items_d.result_value }}</b> {{
                              items_d.unit }}</td>
                            <td> {{ items_d.normal_value }}</td>
                          </tr>
                        </tbody>
                        <tbody v-else>
                          <tr class="text-center">
                            <td colspan="4">Belum ada hasil</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <VPlaceholderPage v-else-if="riwayatPemeriksaan.LIST_LAB.length === 0" :title="H.assets().notFound"
          :subtitle="H.assets().notFoundSubtitle" larger>
          <template #image>
            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>
      </template>
    </VModal>

    <VModal :open="modalHasilRad" title="Hasil Radiologi" actions="right" @close="modalHasilRad = false" :size="'big'">
      <template #content>

        <div class="project-files">
          <div class="list-widget" :class="['is-straight']">


            <div class="inner-list panjang-250">

              <div class="icon-timeline mt-4-min">
                <div v-for="item in riwayatPemeriksaan.LIST_RAD" :key="item.id" class=" mb-2">
                  <div :class="item.isdetail ? 'panjangaaaaaaa' : ''">
                    <div class="timeline-item" style="background-color: #EAF1FF; padding: 10px;border-radius: 10px"
                      :style="item.isdetail ? 'height:40%;border-radius: 10px 10px 0 0;' : ''">
                      <div class="timeline-icon is-clickable" @click="item.isdetail = !item.isdetail"
                        :class="[item.squared && 'is-squared', item.colored && 'is-' + item.color]"
                        style=" background:#193C88;  border-color:#193C88;">
                        <!-- <i aria-hidden="true" class="iconify" :data-icon="item.icon"></i> -->
                        <i aria-hidden="true" :class="item.icon"></i>
                      </div>
                      <div class="timeline-content" style="    margin-left: 10px;">
                        <p>{{ item.namaproduk }}</p>
                        <span>{{ item.tglorder }}</span>
                      </div>
                      <VTag :color="item.color_status" :label="item.status" />
                    </div>
                    <div v-if="item.isdetail == true"
                      style="border-radius: 0 0 10px 10px;background-color: #C8D3E9; padding: 10px;height:70%">
                      <div class="mt-1-min" style="    display: flex;"
                        v-if="item.expertise != null || item.order_complete == 1">
                        <!-- <div  style="width:50px;text-align:center"><img style="width:50px;height:40px"
                            :src="item.namaproduk == 'Trans Thoracal Echocardiografi (TTDewasa)' ? '/images/simrs/0000079.png' : ''">
                        <a href="https://app.rsjpparamarta.com/service/v-echo-00000184-9.html" class="action-link" tabindex="0" style="color:var(--danger);  font-size: 0.8rem;">Lihat Hasil</a>
                    </div> -->
                        <VButton color="info" icon="feather:eye" raised rounded @click="lihatHasil(item)"
                          :loading="item.isLoading" class="btn-slim mt-3"> Hasil </VButton>
                        <VButton color="warning" icon="feather:printer" raised rounded @click="cetakExpertise(item)"
                          :loading="item.isLoading" class="btn-slim mt-3 ml-3"> Cetak </VButton>
                        <img style="width:30px;height:35px" class="ml-2 mt-2" :src="'/images/simrs/doc-rad.png'">
                        <span class="mt-2 ml-2"
                          style="font-size: 0.8rem; font-style: inherit; font-weight: inherit; line-height: 1.1; height: auto; width: auto !important; overflow: hidden !important;text-overflow: ellipsis;">
                          {{ item.expertise ? item.expertise : 'Belum ada Expertise' }}


                        </span>
                      </div>
                      <div v-else style="display: flex; flex-wrap: nowrap;">
                        <span style="width:50px"></span>
                        <img style="width:30px" :src="'/images/simrs/doc-rad.png'">
                        <span class="mt-2 ml-2"
                          style="font-size: 0.8rem; color: var(--light-text); font-style: inherit; font-weight: inherit;">Belum
                          ada Expertise</span>
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

    <Dialog v-model:visible="showModalIntruksi" maximizable modal header="Header" :style="{ width: '75rem' }"
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
                {{ intru.details.intruksiPPA ?? '' }}&nbsp;(<span style="font-weight: bold;">{{
                  intru.details.dpjpUtama?.label ?? '' }}</span>)
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

    <Dialog v-model:visible="showModalHistoriKanker" maximizable modal header="Header" :style="{ width: '75rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
      <template #header>
        <div class="inline-flex items-center justify-center gap-2">
          <span class="font-bold whitespace-nowrap">Riwayat Kanker</span>
        </div>
      </template>
      <div>
        <div class="column is-12">
          <VTabs slider selected="history" :tabs="[
            { label: 'History', value: 'history' },
            { label: 'Tambah', value: 'tambah' },
          ]">
            <template #tab="{ activeValue }">
              <p v-if="activeValue === 'history'">
              <div class="column px-0">
                <div class="list-view-v3">
                  <div class="list-view-item" v-for="(data, i) in listHistoriKemo" :index="i">
                    <table>
                      <tr>
                        <th class="th-pri">No</th>
                        <th class="th-pri">Diagnosa</th>
                        <th class="th-pri">Staging</th>
                        <th class="th-pri">Tanggal Operasi</th>
                        <th class="th-pri">Kemoterapi</th>
                        <th class="th-pri">Tanggal Kemoterapi</th>
                        <th class="th-pri">Radiasi</th>
                        <th class="th-pri">Surveilans</th>
                        <th class="th-pri">Perkembangan</th>
                        <!-- <th>No. Rekam Medis</th> -->
                        <th class="th-pri">Tanggal</th>
                        <th class="th-pri">Dokter</th>
                      </tr>
                      <tr>
                        <td class="td-pri">{{ i + 1 }}</td>
                        <td class="td-pri">{{ data.diagnosa }}</td>
                        <td class="td-pri">{{ data.staging }}</td>
                        <td class="td-pri">{{ data.tanggalOperasi }}</td>
                        <td class="td-pri">{{ data.kemo }}</td>
                        <td class="td-pri">{{ data.tanggalKemoterapi }}</td>
                        <td class="td-pri">{{ data.radiasi }}</td>
                        <td class="td-pri">{{ data.surveilans }}</td>
                        <td class="td-pri">{{ data.perkembangan }}</td>
                        <!-- <td>{{ data.norec }}</td> -->
                        <td class="td-pri">{{ data.tanggal }}</td>
                        <td class="td-pri">{{ data.dokter }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
              </p>
              <p v-if="activeValue === 'tambah'">
              <div class="column is-12">
                <VField label="Diagnosa">
                  <VControl>
                    <VInput v-model="item.diagnosa" placeholder="Diagnosa" />
                  </VControl>
                </VField>

                <VField label="Staging">
                  <VControl>
                    <VInput v-model="item.staging" placeholder="Staging" />
                  </VControl>
                </VField>

                <VField label="Tanggal Operasi">
                  <VDatePicker v-model="item.tanggalOperasi" color="green" mode="date">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>

                <VField label="Kemoterapi">
                  <VControl>
                    <VInput v-model="item.kemoterapi" placeholder="Kemoterapi" />
                  </VControl>
                </VField>

                <VField label="Tanggal Kemoterapi">
                  <VDatePicker v-model="item.tanggalKemoterapi" color="green" mode="date">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>

                <VField label="Radiasi">
                  <VControl>
                    <VInput v-model="item.radiasi" placeholder="Radiasi" />
                  </VControl>
                </VField>

                <VField label="Surveilans">
                  <VControl>
                    <VInput v-model="item.surveilans" placeholder="Surveilans" />
                  </VControl>
                </VField>

                <VField label="Perkembangan">
                  <VControl>
                    <VInput v-model="item.perkembangan" placeholder="Perkembangan" />
                  </VControl>
                </VField>

              </div>
              <hr style="border-top:dashed red; margin: 0.5rem;" class="mb-1 mt-0 pt-0">
              <div class="column is-12">
                <VButton color="primary" @click="saveHistoriKanker()" :loading="isLoadingHistori" class="mt-3">Simpan
                </VButton>
              </div>
              </p>
            </template>
          </VTabs>
        </div>
      </div>
    </Dialog>

    <Dialog v-model:visible="modalBerkas" modal header="Berkas" :style="{ width: '60vw' }">
      <Berkas :pasien="pasien" :registrasi="selectedRegistrasi" />
    </Dialog>

    <Dialog v-model:visible="modalConfirmObgyn" modal header="Pilih Jenis Assesmen Awal Obgyn Yang Sesuai"
      :style="{ width: '30vw' }" maximizable>
      <VButton icon="feather:book" color="success" raised @click="ginekologi()"
        style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
        Ginekologi
      </VButton>
      <VButton icon="feather:book" color="info" raised @click="obstetri()"
        style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
        Obstetri
      </VButton>
    </Dialog>

    <Dialog v-model:visible="modalConfirmInterna" modal header="Pilih Jenis Assesmen Awal Yang Sesuai"
      :style="{ width: '30vw' }" maximizable>
      <VButton icon="feather:book" color="success" raised @click="obstetriinterna()"
        style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
        Geriatri
      </VButton>
      <VButton icon="feather:book" color="info" raised @click="interna()"
        style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
        Interna
      </VButton>
    </Dialog>

    <Dialog v-model:visible="modalConfirmTrauma" modal header="Pilih Jenis Assesmen Awal Yang Sesuai"
      :style="{ width: '30vw' }" maximizable>
      <VButton icon="feather:book" color="success" raised @click="trauma()"
        style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
        Trauma
      </VButton>
      <VButton icon="feather:book" color="info" raised @click="nontrauma()"
        style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
        Non Trauma
      </VButton>
    </Dialog>

    <Dialog v-model:visible="modalConfirmObstetri" modal header="Hasil Pemeriksaan" :style="{ width: '80vw' }"
      maximizable>

    </Dialog>

    <VModal :open="modalDetailPasien" title="Detail Pasien" :noclose="false" size="big" actions="right"
      @close="modalDetailPasien = false">
      <template #content>
        <DetailPasien v-if="modalDetailPasien" :noregistrasi="noreg_pasienDetail" :norec_pd="norec_pd_pasienDetail" />
      </template>
    </VModal>

    <Dialog v-model:visible="modalTindakan" modal header="Tindakan" :style="{ width: '100rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
      <Tindakan v-if="modalTindakan" :noregistrasi="noreg_pasienDetail" :norec_pd="norec_pasien_daftar"
        :registrasi="selectedRegistrasi" :pasien="pasien" />
    </Dialog>

    <Dialog v-model:visible="modalResep" modal header="Order Resep" :style="{ width: '100rem' }" maximizable>
      <OrderResep v-if="modalResep" :NOREC_PD="NOREC_PD" :registrasi="selectedRegistrasi" :pasien="pasien" />
    </Dialog>

    <Dialog v-model:visible="modalTransferPasien" modal header="Transfer Pasien" :style="{ width: '100rem' }"
      maximizable>
      <TransferPasien v-if="modalTransferPasien" :noregistrasi="noreg_pasienDetail" :norec_pd="item.NOREC_PD"
        :registrasi="selectedRegistrasi" :pasien="pasien" />
    </Dialog>

    <Dialog v-model:visible="modalLab" modal header="Order Laboratorium" :style="{ width: '100rem' }" maximizable>
      <Laboratorium v-if="modalLab" :NOREC_PD="NOREC_PD" :registrasi="selectedRegistrasi" :pasien="pasien" />
    </Dialog>

    <Dialog v-model:visible="ModalRadiologi" modal header="Order Radiologi" :style="{ width: '100rem' }" maximizable>
      <Radiologi v-if="ModalRadiologi" :NOREC_PD="NOREC_PD" :registrasi="selectedRegistrasi" :pasien="pasien" />
    </Dialog>

    <Dialog v-model:visible="ModalOrderBedah" modal header="Order Bedah" :style="{ width: '100rem' }" maximizable>
      <Bedah v-if="ModalOrderBedah" :noregistrasi="noreg_pasienDetail" :norec_pd="item.NOREC_PD"
        :registrasi="selectedRegistrasi" :pasien="pasien" />
    </Dialog>

    <Dialog v-model:visible="modalPenunjangKhusus" modal header="Penunjang Khusus" :style="{ width: '100rem' }"
      maximizable>
      <Penunjang v-if="modalPenunjangKhusus" :noregistrasi="noreg_pasienDetail" :norec_pd="item.NOREC_PD"
        :registrasi="selectedRegistrasi" :pasien="pasien" />
    </Dialog>

    <Dialog v-model:visible="modalBerkasDokter" modal header="Berkas Pasien" :style="{ width: '100rem' }" maximizable>
      <Berkas v-if="modalBerkasDokter" :noregistrasi="noreg_pasienDetail" :norec_pd="item.NOREC_PD"
        :registrasi="selectedRegistrasi" :pasien="pasien" />
    </Dialog>

  </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, watchEffect, inject, provide, nextTick } from 'vue'
import { useRoute, useRouter, onBeforeRouteUpdate, onBeforeRouteLeave, RouteLocationNormalized, Router, RouterView, } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabMenu from 'primevue/tabmenu';
import TActionLeftBar from './profile-pasien/t-action-left-bar.vue'
import TDetailRegistrasiRev from './profile-pasien/t-detail-registrasi-rev.vue'
import TEmrDetail from './profile-pasien/t-emr-detail.vue'
import TEmrDetailRev from './profile-pasien/t-emr-detail-revision.vue'
import AutoComplete from 'primevue/autocomplete';
// import TEmrAsesmen from './profile-pasien/t-emr-asesmen.vue'
import TStatusEmr from './profile-pasien/t-status-emr.vue'
import TListPasien from './profile-pasien/t-list-pasien.vue'
import TNavigasiEmr from './profile-pasien/t-navigasi-emr.vue'
import TNavigasiEmrBundle from './profile-pasien/t-navigasi-emr-bundle.vue'
import TRiwayatPasien from './profile-pasien/page-emr/riwayat-registrasi.vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import Dialog from 'primevue/dialog';
import { listActionEMR } from '/@src/data/module/hard/action_emr'
import FloatingButton from "./float-button.vue";
import { useUserSession } from '/@src/stores/userSession'
import sleep from '/@src/utils/sleep'
import { state, socket } from "/@src/socket.js";
import Berkas from './profile-pasien/page-emr/berkas-pasien.vue'
import Tindakan from './profile-pasien/page-emr/tindakan.vue'
import OrderResep from './profile-pasien/page-emr/order-resep.vue'
import TransferPasien from './profile-pasien/page-emr/konsultasi.vue'
import Laboratorium from './profile-pasien/page-emr/order-laboratorium.vue'
import Radiologi from './profile-pasien/page-emr/order-radiologi.vue'
import Bedah from './profile-pasien/page-emr/order-bedah.vue'
import Penunjang from './profile-pasien/page-emr/penunjang.vue'
import { useConfirm } from "primevue/useconfirm"
import DetailPasien from '../registrasi/detail-registrasi.vue'
// import $ from "jquery";
useHead({
  title: 'EMR - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const route = useRoute()
const ID_PASIEN: any = route.query.nocmfk
const NOREC_PD: any = route.query.norec_pd
const NOREC_APD: any = route.query.norec_apd
const kelompokQuery: any = route.query.kelompokuser;
const modalConfirmObgyn: any = ref(false)
const modalConfirmInterna: any = ref(false)
const modalConfirmObstetri: any = ref(false)
const modalConfirmTrauma: any = ref(false)
const isGynekologi: any = ref(false)
const isKardiotokografi: any = ref(false)
const isFetal: any = ref(false)
const routerChangeTAB: any = ref(false)
const showModalAlergi: any = ref(false)
const PASIEN_AKTIF = NOREC_PD == null ? false : true
const listWarna = ref([0, 1, 2])
const PARAMS: any = reactive({ ID_PASIEN: ID_PASIEN, NOREC_PD: NOREC_PD })
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
const klikLoad: any = ref(false)
const modalFilter: any = ref(false)
const modalProfil = ref(false)
const modalCatatan = ref(false)
const modalHasilLab = ref(false)
const modalHasilRad = ref(false)
const modalBerkas = ref(false)
const isLoadingFilter: any = ref(false)
const loadingList: any = ref(false)
const totalData = ref(0)
const pasien: any = ref({})
const visible = ref(false);
const registrasi: any = ref({})
const alamat: any = ref({})
const listRegistrasi: any = ref([])
const isClosing: any = ref()
const isLoadingPasien: any = ref(false)
const isLoadingRiwayat: any = ref(false)
const isRemoveTAB: any = ref(false)
const now: any = ref(new Date())
const { y } = useWindowScroll()
const router = useRouter()
const listMenu = ref([])//ref(listActionEMR)
const listMenuEMR: any = ref([])
const listMenuEMRBundle: any = ref([])
const listMenuEMRBundleDetail: any = ref([])
const dokterCatatan: any = ref([])
const modalMENU = ref(false)
const TAB_DEFAULT = ref('')
const TAB_ACTIVE: any = ref('Dashboard');
const TAB_URL = ref('')
const TAB_ACTIVE_ROUTER: any = ref(null)
const TAB_ROUTER_DEFAULT = ref('module-emr-profile-pasien-page-emr-not-found')
const TAB_ITEMS: any = ref([]);
const d_Alergi: any = ref([]);
const COLLECTION: any = ref()
const NAMA_RUANGAN: any = ref()
const isReload: any = ref(false)
const isStuck = computed(() => { return y.value > 30 })
const selectedRegistrasi: any = ref({})
const listPasienRJ: any = ref({})
const activeHead = ref(true)
const totalMenu = ref(0)
const classMODALMENU: any = ref('medium')
const idDepartemenRI: any = ref([])
const contentRefScrollMenu: any = ref(null);
const confirm = useConfirm();
const rowGroupLAB: any = ref({})
const isLoadingSKD: any = ref(false)
const namappkRumahSakit: any = ref('')
const isFlafon: any = ref(false)
const dataSourceINACBG: any = ref([])
const colorPLAFON: any = ref('info')
const squared: any = ref(true)
const isLoading: any = ref(false)
const currentPage: any = ref({
  limit: 5,
  rows: 25,
})
const currenPageChange: any = ref(false)
const classActiveNavLISTPASIEN: any = ref('')
const classActiveNavEMR: any = ref('active-nav-emr-mobile')
const hideRiwayat: any = ref(false)
const isBtnLoading: any = ref(false)
const filterMenu: any = ref('')
const dataAlergi: any = ref('')
const dataSKDP: any = ref([])
const userLogin = useUserSession().getUser()
const user = useUserSession().getUser().pegawai;
// console.log('USERLOIGIN', userLogin)
const kelompokUser = route.query.kelompokuser ?? useUserSession().getUser().kelompokUser.kelompokUser
console.log(kelompokUser);

const isLoadingIcare: any = ref(false)
const info: any = ref({})
const isLoadingPanggil: any = ref(false)
const isLoadingRegis: any = ref(false)
const urledit: any = ref(false)
const getENV: any = import.meta.env;
const changeTabFromChild: any = ref(null);
const NOREC_EMRPASIEN: any = ref('')
const listIntruksi: any = ref([]);
const filteredMenu: any = computed(() => {
  if (!filterMenu.value) {
    return listMenu.value
  }

  return listMenu.value.filter((items: any) => {
    return (
      items.name.match(new RegExp(filterMenu.value, 'i'))
    )
  })
})

for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i]
  if (i <= 9 && element != 'primary') listColor.value.push(element)
}
let jenisobgyn = null
let jenisinterna = null
let jenistrauma = null
let clearList = {
  LIST_VITAL: [],
  LIST_TINDAKAN: [],
  LIST_RESEP: [],
  LIST_KONSUL: [],
  LIST_CPPT: [],
  LIST_LAB: [],
  LIST_RAD: [],
  LIST_BEDAH: [],
  LIST_ORDERBEDAH: [],
  LIST_DIAGNOSIS: [],
  LIST_STATUSPASIEN: {},
  LIST_ASESMENAWAL: {},
  LIST_EMR: [],
  LIST_LAB_GROUP: [],
  LIST_EMR_OLD: [],
  LIST_EMR_IBS: [],
  isEmpty: true,
  isLoading: false,
}
const riwayatPemeriksaan: any = ref(clearList)
const riwayatAsesmen: any = ref({

  isEmpty: false,
  isLoading: false,
})
const item: any = reactive({
  tglregistrasi: new Date(),
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  isRekap: false,
  filterTgl: reactive({
    start: new Date(new Date().setDate(new Date().getDate() - 730)),
    end: new Date(),
  }),
})
console.log('item', item)

const pasienByID = async (id: any) => {
  if (routerChangeTAB.value) return

  // Clear cache tab EMR All
  localStorage.removeItem('cacheTAB_')

  isLoadingPasien.value = true
  isLoadingFilter.value = true
  let dari = H.formatDate(item.filterTgl.start, 'YYYY-MM-DD')
  let sampai = H.formatDate('2024-12-31', 'YYYY-MM-DD');
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  pasien.value = {}
  registrasi.value = {}
  alamat.value = {}
  selectedRegistrasi.value = {}
  offset = (parseInt(offset) - 1) * limit
  totalData.value = 0
  let norec_apd = route.query.norec_apd ? `&norec_apd=${route.query.norec_apd}` : ''
  await useApi().get(`/emr/header-pasien?nocmfk=${id}&norec_pd=${route.query.norec_pd}${norec_apd}&dari=${dari}&sampai=${sampai}&limit=${limit}&offset=${offset}`).then(async (response: any) => {
    pasien.value = response.pasien
    alamat.value = response.alamat
    registrasi.value = response.registrasi[0]
    // console.log("INI LAH REGISNYACOY",registrasi.value)
    idDepartemenRI.value = response.idDepartemenRI
    listRegistrasi.value = groupRegistrasi(response.registrasi)
    totalData.value = response.count_registrasi
    for (let x = 0; x < response.registrasi.length; x++) {
      const element = response.registrasi[x]
      if (element.norec_pd == NOREC_PD) {
        isClosing.value = element.isclosing
      }

      if (element.diagnosis.length > 0) {
        element.isdetail = true
      }


      if (route.query.norec_pd == element.norec) {
        selectNoreg(element)
        break
      }
    }
    if ((route.query.norec_pd == '' || route.query.norec_pd == null) && response.registrasi.length) {
      selectNoreg(response.registrasi[0])
    }

    isLoadingFilter.value = false
    isLoadingPasien.value = false
    await loadListPasien()

  })


}
const groupRegistrasi = (data: any) => {

  const groupedData = data.reduce((result, item) => {
    const departmentName = item.namadepartemen;

    // Check if the department name is already a key in the result object
    if (!result[departmentName]) {
      // If not, create a new key with an empty array
      result[departmentName] = [];
    }

    // Add the current item to the corresponding department array
    result[departmentName].push(item);

    return result;
  }, {});

  // Convert the object values to an array if needed
  // const groupedArray = Object.values(groupedData);

  const groupedArray = Object.entries(groupedData).map(([namadepartemen, details]) => ({
    namadepartemen,
    details,
  }));

  // Display the final result
  // console.log(groupedArray);

  return groupedArray
}
const modalDetailPasien = ref(false);
const noreg_pasienDetail = ref('');
const norec_pd_pasienDetail = ref('');
const detailRegistrasiModals = async (e: any) => {
  modalDetailPasien.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const kodingRM = (e: any) => {

    router.push({
        name: 'module-inacbgs-koding-detail',
        query: {
            page: route.query.pageKD,
            noregistrasi: selectedRegistrasi.value.noregistrasi,
            norec_pd: route.query.norec_pd,
            nocmfk: route.query.nocmfk,
            norec_apd: route.query.norec_apd,
        }
    }).then(() => {
      window.location.reload()
    })
}

const modalTindakan = ref(false);
const modalTindakanDokter = async (e: any) => {
  modalTindakan.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalResep = ref(false);
const modalResepDokter = async (e: any) => {
  modalResep.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalTransferPasien = ref(false);
const modalTransferPasienDokter = async (e: any) => {
  modalTransferPasien.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalLab = ref(false);
const modalLabDokter = async (e: any) => {
  modalLab.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const ModalRadiologi = ref(false);
const ModalRadiologiDokter = async (e: any) => {
  ModalRadiologi.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const ModalOrderBedah = ref(false);
const ModalOrderBedahDokter = async (e: any) => {
  ModalOrderBedah.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalPenunjangKhusus = ref(false);
const modalBerkasDokter = ref(false);
const modalPenunjangKhususDokter = async (e: any) => {
  modalPenunjangKhusus.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const selectNoreg = async (items: any) => {
  if (route.query.norec_apd) {
    if (items.apd != null) {
      items.norec_apd = items.apd.norec_apd
      items.objectruanganlastfk = items.apd.objectruanganfk
      items.objectruanganfk = items.apd.objectruanganfk
      items.objectkelasfk = items.apd.objectkelasfk
      items.namaruangan = items.apd.namaruangan
      items.namakelas = items.apd.namakelas
      items.tglregistrasi = items.apd.tglregistrasi
      // console.log(items.namaruangan)
    }
  }
  selectedRegistrasi.value.isloading = true
  selectedRegistrasi.value = items

  selectedRegistrasi.value.isloading = false
  if (route.query.isfull) {
    router.push({
      query: {
        norec_pd: selectedRegistrasi.value.norec,
        nocmfk: selectedRegistrasi.value.nocmfk,
        norec_apd: selectedRegistrasi.value.norec_apd,
      }
    })
  }
  selectedRegistrasi.value.billing = await fetchTotalBill()

  setColorFlafon(items)
  // console.log(items.apd.objectruanganfk)

  if (kelompokUser == 'dokter') {
    // TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi'
    // TAB_ITEMS == 'CPPT'
    // TAB_DEFAULT == 'CPPT'
    // console.log('dokter')
    // onTab()
  } else if (kelompokUser == 'perawat' && items.objectruanganlastfk == 237) {
    // TAB_ACTIVE.value = 'Formulir Asuhan dan Obeservasi Keperawatan Pasien Kemoterapi Rawat Jalan'
    // TAB_ITEMS == 'Formulir Asuhan dan Obeservasi Keperawatan Pasien kemoterapi Rawat Jalan'
    // TAB_DEFAULT == 'Formulir Asuhan dan Obeservasi Keperawatan Pasien kemoterapi Rawat Jalan'
    // onTab()

    // console.log('perawat')
  }


}
const selectedRiwayat = (e: any) => {
  TAB_ITEMS.value = []
  TAB_ACTIVE.value = 'Dashboard'
  selectNoreg(e)
}

function kardiotokografi() {
  isKardiotokografi.value = true
  isGynekologi.value = false
  isFetal.value = false
}

function gynekologi() {
  isKardiotokografi.value = false
  isGynekologi.value = true
  isFetal.value = false
}

function fetal() {
  isKardiotokografi.value = false
  isGynekologi.value = false
  isFetal.value = true
}

function suratKeterangan() {

  COLLECTION.value = 'Surat Keterangan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-surat-keterangan`
  TAB_ACTIVE.value = 'Surat Keterangan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-surat-keterangan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function setOK() {

  COLLECTION.value = 'Set OK'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-set-ok`
  TAB_ACTIVE.value = 'Set OK'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-set-ok`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function setRujukan() {

  COLLECTION.value = 'Set Rujukan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-set-rujukan`
  TAB_ACTIVE.value = 'Set Rujukan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-set-rujukan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function setRanap() {

  COLLECTION.value = 'Set Ranap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-set-ranap`
  TAB_ACTIVE.value = 'Set Ranap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-set-ranap`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

async function cetakSEP() {
  if (selectedRegistrasi.value.nosep == null) {
    H.alert('error', 'No SEP masih kosong')
    return
  }

  isLoading.value = true;
  await H.printBlade(`registrasi/pemakaian-asuransi/sep?pdf=true&nosep=${selectedRegistrasi.value.nosep}`, 'SEP', 1)
  // await qzService.printData(`registrasi/pemakaian-asuransi/sep?pdf=true&nosep=${selectedRegistrasi.nosep}`, 'SEP', 1)
  isLoading.value = false;
}

function riwayatPasien() {
  COLLECTION.value = 'Riwayat Pasien'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-riwayat-registrasi`
  TAB_ACTIVE.value = 'Riwayat Pasien'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-riwayat-registrasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onTabLembarPenyinaranRadioterapi = () => {
  COLLECTION.value = 'Lembar Penyinaran'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-lembaran-penyiaran-radioterapi`
  TAB_ACTIVE.value = 'Lembar Penyinaran'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-lembaran-penyiaran-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabGeriatriRanapIntensif = () => {
  COLLECTION.value = 'AssesmenGiziGeriatri'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-gizi-geriatri-rawat-inap-dan-intensif`
  TAB_ACTIVE.value = 'Lembar Penyinaran'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-gizi-geriatri-rawat-inap-dan-intensif`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabStrongKids = () => {
  COLLECTION.value = 'SkriningGiziBayidanAnakStrongkids'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-skrining-gizi-bayi-dan-anak-strongkids`
  TAB_ACTIVE.value = 'Lembar Penyinaran'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-skrining-gizi-bayi-dan-anak-strongkids`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabpengkajianAwalMedisIntensive = () => {
  COLLECTION.value = 'Pengkajian Awal Medis Intensive'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pengkajian-awal-medis-intensive`
  TAB_ACTIVE.value = 'Pengkajian Awal Medis Intensive'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pengkajian-awal-medis-intensive`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabBerkasPasien = () => {
  COLLECTION.value = 'Berkas Pasien'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-berkas-pasien`
  TAB_ACTIVE.value = 'Berkas Pasien'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-berkas-pasien`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabSkemaPenyinaranRadioterapi = () => {
  COLLECTION.value = 'Skema Penyinaran'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-skema-penyinaran-radioterapi`
  TAB_ACTIVE.value = 'Skema Penyinaran'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-skema-penyinaran-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const meows = () => {
  COLLECTION.value = 'PemantauanMEOWS'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pemantauan-meows`
  TAB_ACTIVE.value = 'Pemantauan Modified Early Obstetric Warning Score (MEOWS)'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pemantauan-meows`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabJadwalKunjunganRehabDanFisio = () => {
  COLLECTION.value = 'Jadwal Kunjungan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-jadwal-kunjungan-rehab-dan-fisio`
  TAB_ACTIVE.value = 'Jadwal Kunjungan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-jadwal-kunjungan-rehab-dan-fisio`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const formulirMESO = () => {
  COLLECTION.value = 'FormulirMESO'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-meso`
  TAB_ACTIVE.value = 'Formulir MESO'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-meso`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const persetujuanProsedurTindakanOR = () => {
  COLLECTION.value = 'PersetujuanProsedurTindakanOnkologiRadiasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-persetujuan-prosedur-tindakan-onkologi-radiasi`
  TAB_ACTIVE.value = 'Formulir Persetujuan Prosedur Tindakan Onkologi Radiasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-persetujuan-prosedur-tindakan-onkologi-radiasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const ctgIGD = () => {
  COLLECTION.value = 'CTG_IGD'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-ctg-igd`
  TAB_ACTIVE.value = 'CTG IGD'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-ctg-igd`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabFisikaRadioterapi = () => {
  COLLECTION.value = 'Fisika Radioterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-fisika-radioterapi`
  TAB_ACTIVE.value = 'Fisika Radioterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-fisika-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabVerifikasiEpid = () => {
  COLLECTION.value = 'Verifikasi EPID'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-verifikasi-epid`
  TAB_ACTIVE.value = 'Verifikasi EPID'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-verifikasi-epid`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const asuhanGiziNeonatus = () => {
  COLLECTION.value = 'AsuhanGiziNeonatus'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asuhan-gizi-neonatus`
  TAB_ACTIVE.value = 'Asuhan Gizi Neonatus'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asuhan-gizi-neonatus`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const asesmenGiziBayiAnak = () => {
  COLLECTION.value = 'AssesmenGiziBayiRawatInap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-assesmen-gizi-bayi-dan-anak-rawat-inap-dan-intesif`
  TAB_ACTIVE.value = 'Asesmen Gizi Bayi & Anak Rawat Inap Intensif'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-assesmen-gizi-bayi-dan-anak-rawat-inap-dan-intesif`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const asesmenGiziGeriatriRI = () => {
  COLLECTION.value = 'AssesmenGiziGeriatriRI'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-gizi-geriatri-rawat-inap-dan-intensif`
  TAB_ACTIVE.value = 'Asesmen Gizi Geriatri Rawat Inap & Intensif'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-gizi-geriatri-rawat-inap-dan-intensif`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const monitoringEvaluasiGizi = () => {
  COLLECTION.value = 'MonitoringDanEvaluasiGizi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-monitoring-dan-evaluasi-gizi`
  TAB_ACTIVE.value = 'Monitoring dan Evaluasi Gizi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-monitoring-dan-evaluasi-gizi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabAmprahInVivo = () => {
  // COLLECTION.value = 'MonitoringDanEvaluasiGizi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-amprah-in-vivo`
  TAB_ACTIVE.value = 'Order In Vivo'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-amprah-in-vivo`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabAmprahInVitro = () => {
  // COLLECTION.value = 'MonitoringDanEvaluasiGizi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-amprah-in-vitro`
  TAB_ACTIVE.value = 'Order In Vitro'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-amprah-in-vitro`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const evaluasiKeperawatan = () => {
  COLLECTION.value = 'EvaluasiKeperawatan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-evaluasi-keperawatan-ranap`
  TAB_ACTIVE.value = 'Evaluasi Keperawatan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-evaluasi-keperawatan-ranap`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabCatatanKegiatanRadioterapi = () => {
  COLLECTION.value = 'Catatan Kegiatan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-catatan-kegiatan-radioterapi`
  TAB_ACTIVE.value = 'Catatan Kegiatan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-catatan-kegiatan-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}


function informasiEdukasi() {
  COLLECTION.value = 'Asesmen Kebutuhan Informasi & Edukasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-edukasi-pasien-keluarga`
  TAB_ACTIVE.value = 'Asesmen Kebutuhan Informasi & Edukasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-edukasi-pasien-keluarga`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function statusHemodialisa() {
  COLLECTION.value = 'StatusHemodialisa'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-status-hemodialisa`
  TAB_ACTIVE.value = 'Status Hemodialisa'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-status-hemodialisa`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function SuratPenggunaanObatKhususKronis() {
  COLLECTION.value = 'SuratPenggunaanObatKhususKronis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-surat-penggunaan-obat-khusus-kronis-index_tabs`
  TAB_ACTIVE.value = 'Surat Penggunaan Obat Khusus Kronis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-surat-penggunaan-obat-khusus-kronis-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function catatanEdukasi() {
  COLLECTION.value = 'Infromasi Edukasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-informasi-edukasi-pasien`
  TAB_ACTIVE.value = 'Infromasi Edukasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-informasi-edukasi-pasien`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function onAssmedHD() {
  COLLECTION.value = 'Infromasi Edukasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-hemodialisa`
  TAB_ACTIVE.value = 'Infromasi Edukasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-hemodialisa`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

function pengkajianJatuhDewasa() {
  COLLECTION.value = 'PengkajianResikoJatuhDewasa'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pengkajian-resiko-jatuh-dewasa-ranap`
  TAB_ACTIVE.value = 'Pengkajian Resiko Jatuh'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pengkajian-resiko-jatuh-dewasa-ranap`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
function riwayatSanata() {
  window.open('http://his.balimandarahospital.com:8000/riwayatpx/' + pasien.value.nocm, '_blank')
}

const reloadListReg = async () => {
  // isLoadingRegis.value = true
  // await pasienByID(route.query.nocmfk)
  // selectNoreg(listRegistrasi.value[0])
  // isLoadingRegis.value = false
  // modalFilter.value = false

  await pasienByID(route.query.nocmfk)
  currenPageChange.value = false
  modalFilter.value = false
}
const backPage = () => {
  window.history.back()
}
const detailPelayanan = async (NOREC_PD: any) => {
  isLoadingRiwayat.value = true
  riwayatPemeriksaan.value = clearList

  await useApi().get(`/emr/detail-pelayanan?norec_pd=${route.query.norec_pd}&nocmfk=${ID_PASIEN}`).then(async (response: any) => {
    console.log("DETAIL PELAYANAN", response);
    isLoadingRiwayat.value = false
    let x = 0
    for (let i = 0; i < response.resep.length; i++) {
      const element = response.resep[i]
      element.no = x
      if (x > 3) {
        x = 0
      }
      x++
    }
    x = 0
    for (let i = 0; i < response.diagnosis.length; i++) {
      const element = response.diagnosis[i]
      element.color = listColor.value[x]
      element.icd = element.kddiagnosa + '-' + element.namadiagnosa
      if (x > 9) {
        x = 0
      }
      x++
    }
    x = 0
    for (let i = 0; i < response.konsul.length; i++) {
      const element = response.konsul[i]
      element.no = x
      if (x > 3) {
        x = 0
      }
      x++
    }
    x = 0
    for (let i = 0; i < response.laboratorium.length; i++) {
      const element = response.laboratorium[i]
      element.color = listColor.value[x]
      element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
      element.icon = 'fa fa-bong'
      if (x > 9) {
        x = 0
      }
      x++
    }
    x = 0
    for (let i = 0; i < response.radiologi.length; i++) {
      const element = response.radiologi[i]
      element.color = listColor.value[x]
      element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
      element.cetak = 'true'
      element.icon = 'fa-inverse fa fa-radiation'
      if (x > 9) {
        x = 0
      }
      x++
    }
    x = 0
    for (let i = 0; i < response.berkas.length; i++) {
      const element = response.berkas[i]
      element.no = x
      if (x > 3) {
        x = 0
      }
      x++
    }

    let alergil = 'Tidak ada';
    if (response.statuspasien.alergi == undefined) {
      alergil = response.statuspasien.alergi;
      response.statuspasien.lamarawat = response.statuspasien.lamarawat ? response.statuspasien.lamarawat : '-'
      response.statuspasien.tglpulang = response.statuspasien.tglpulang ? H.formatDateIndo(response.statuspasien.tglpulang) : '-'
      // response.statuspasien.statuspulang = response.statuspasien.statuspulang ? response.statuspasien.statuspulang : '-'
      response.statuspasien.kondisipasien = response.statuspasien.kondisipasien ? response.statuspasien.kondisipasien : '-'
    }
    // response.statuspasien.alergi = alergi;

    dataAlergi.value = alergil


    riwayatPemeriksaan.value.LIST_VITAL = response.vitalSign
    riwayatPemeriksaan.value.LIST_TINDAKAN = response.tindakan
    riwayatPemeriksaan.value.LIST_RESEP = response.resep
    riwayatPemeriksaan.value.LIST_DIAGNOSIS = response.diagnosis
    riwayatPemeriksaan.value.LIST_RAD = setHasilRad(response.radiologi)
    x = 0
    for (let i = 0; i < response.emr.length; i++) {
      const element = response.emr[i]
      if (element.table == "PengkajianResikoJatuhDewasa") {
        response.emr[i].url_form = "pengkajian-resiko-jatuh-dewasa-ranap"
        response.emr[i].namaemr = "Pengkajian Risiko Jatuh Dewasa"
      }
      x++
    }
    riwayatPemeriksaan.value.LIST_EMR = response.emr
    riwayatPemeriksaan.value.LIST_EMR_IBS = response.listIBS
    riwayatPemeriksaan.value.LIST_KONSUL = response.konsul
    riwayatPemeriksaan.value.LIST_BERKAS = response.berkas
    // if (selectedRegistrasi.value.noregistrasi == 2307000011) {
    //   // riwayatPemeriksaan.value.LIST_LAB = hasilLAB_THEO.value
    // } else {
    riwayatPemeriksaan.value.LIST_LAB = setHasilLab(response.laboratorium)
    riwayatPemeriksaan.value.LIST_LAB_GROUP = updateGroupLAB()

    // }

    riwayatPemeriksaan.value.LIST_BEDAH = response.bedah
    riwayatPemeriksaan.value.LIST_ORDERBEDAH = response.orderBedah
    riwayatPemeriksaan.value.LIST_STATUSPASIEN = response.statuspasien
    riwayatPemeriksaan.value.LIST_ASESMENAWAL = response.asesmenawal
    // if (riwayatPemeriksaan.value.LIST_ASESMENAWAL) {
    //   riwayatPemeriksaan.value.LIST_VITAL = [{
    //     'tes': 1
    //   }]
    // }

    riwayatPemeriksaan.value.isLoading = false
    riwayatPemeriksaan.value.isEmpty = response.empty
    if (response.enabledEMRSimrsLama == 'true') {
      let lokal = false;
      if (window.location.host.indexOf('192.168') > -1) {
        lokal = true;
      }
      let APISIMRS_LAMA: any = [
        { id: 'kajian_awal', name: '* KAJIAN AWAL' },
        { id: 'catatan_dokter', name: '* CATATAN DOKTER' },
        { id: 'diagnosa', name: '* DIAGNOSIS' },
        { id: 'order_resep', name: '* RESEP' },
        { id: 'rujukan_penunjang', name: '* RUJUKAN PENUNJANG' },
        { id: 'rujukan_nonpenunjang', name: '* RUJUKAN NON PENUNJANG' },
        { id: 'labpa', name: '* PATOLOGI ANATOMI' },
        { id: 'rad', name: '* RADIOLOGI' },
      ];
      for (let z = 0; z < APISIMRS_LAMA.length; z++) {
        const elementX = APISIMRS_LAMA[z];
        let responseX = await useApi().get(`/emr/history-sim-lama?prefix=${elementX.id}&nocm=${pasien.value.nocm}&local=${lokal}`)
        responseX.name = elementX.name
        riwayatPemeriksaan.value[elementX.id] = responseX
      }
    }

    // Dimatikan supaya tidak membuat lag
    // await useApi().get(`/emr/get-perjanjian?nocm=${pasien.value.nocm}&tanggal=${new Date()}`).then((response: any) => {
    //   isLoading.value = false
    //   if (response.data.length > 0) {
    //     dataSKDP.value = response
    //   }
    // })
  })
}
const updateGroupLAB = () => {
  rowGroupLAB.value = {};

  if (riwayatPemeriksaan.value.LIST_LAB.length) {
    for (let x = 0; x < riwayatPemeriksaan.value.LIST_LAB.length; x++) {
      const element = riwayatPemeriksaan.value.LIST_LAB[x];
      for (let i = 0; i < element.hasil_lab.length; i++) {
        let rowData = element.hasil_lab[i];
        let treatment_name = rowData.treatment_name;

        if (i == 0) {
          rowGroupLAB.value[treatment_name] = { index: 0, size: 1 };
        } else {
          let previousRowData = element.hasil_lab[i - 1];
          let previousRowGroup = previousRowData.treatment_name;
          if (treatment_name === previousRowGroup)
            rowGroupLAB.value[treatment_name].size++;
          else
            rowGroupLAB.value[treatment_name] = { index: i, size: 1 };
        }
      }
    }
  }
  return rowGroupLAB.value
}


const setHasilLab = (e: any) => {
  for (let x = 0; x < e.length; x++) {
    const element = e[x];
    element.isdetail = false
    if (element.hasil_lab.length > 0) {
      element.isdetail = true
    }
  }
  return e
}
const setHasilRad = (e: any) => {
  for (let x = 0; x < e.length; x++) {
    const element = e[x];
    element.isdetail = false
    if (element.expertise != null) {
      element.isdetail = true
    }
  }
  return e
}
const filter = () => {
  modalFilter.value = true
}
const minimizeRiwayat = (bool: any) => {

}
const showRiwayat = () => {
  hideRiwayat.value = false
}
const hiddenRiwayat = () => {
  hideRiwayat.value = true
}

const reloadRiwayat = () => {
  let norec_pede = route.query.norec_pd;
  if (selectedRegistrasi.value?.norec) {
    norec_pede = selectedRegistrasi.value.norec;
  }
  if (selectedRegistrasi.value.objectdepartemenfk == 9) {
    fetchDataAndProcess()
  }
  detailPelayanan(norec_pede)
  // console.log(selectedRegistrasi.value)
}
const suratIbu = () => {
  COLLECTION.value = 'SuratPermintaanDirawat'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-surat-permintaan-dirawat-ibu`
  TAB_ACTIVE.value = 'Surat Permintaan Dirawat Bayi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-surat-permintaan-dirawat-ibu`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const billingPasien = () => {
  let params = {}
  if (!PASIEN_AKTIF) {
    params = {
      nocmfk: selectedRegistrasi.value.nocmfk,
      norec_pasien_daftar: selectedRegistrasi.value.norec,
      noregistrasi: selectedRegistrasi.value.noregistrasi,
      isaktif: 'false'
    }
  } else {
    params = {
      nocmfk: selectedRegistrasi.value.nocmfk,
      norec_pasien_daftar: selectedRegistrasi.value.norec,
      noregistrasi: selectedRegistrasi.value.noregistrasi,
    }
  }
  router.push({
    name: 'module-kasir-billing',
    query: params
  })
}
const loadListPasien = async () => {
  listPasienRJ.value = {}
  loadingList.value = true
  let params = `&ruid=${selectedRegistrasi.value.objectruanganlastfk}`
  if (kelompokUser == 'dokter' && kelompokUser == 'dokter-igd') {
    params = `&dokid=${userLogin.pegawai.id}`
  }
  await useApi()
    .get(`/emr/list-pasien-rj?dari=${H.formatDate(new Date(), 'YYYY-MM-DD')}
    &sampai=${H.formatDate(new Date(), 'YYYY-MM-DD')}
    &norec_pd=${route.query.norec_pd}
    &statuspanggil=${item.statuspanggil}${params}`)
    .then((response: any) => {

      listPasienRJ.value = response
      loadingList.value = false
    })
}
const PILIHPASIEN = async (e: any) => {

  sendAntrol(e.norec_pd)
  router.push({
    query: {
      norec_pd: e.norec_pd,
      nocmfk: e.nocmfk,
      isfull: 'true'
    }
  })

}
const LOADPASIEN = async (e: any) => {
  item.statuspanggil = ''
  if (e != '') {
    item.statuspanggil = e
  }
  await loadListPasien()
}

const setRoutingEMR = (form: any, norec_emr: any, edit: any) => {
  let query: any = {};
  let params: any = {};

  // Extract base path from the current URL
  const currentBasePath = router.currentRoute.value.path;

  if (norec_emr != '') {
    query = {
      nocmfk: selectedRegistrasi.value.nocmfk,
      norec_pasien_daftar: selectedRegistrasi.value.norec_pd,
      norec_pd: selectedRegistrasi.value.norec_pd,
      norec_apd: selectedRegistrasi.value.norec_apd,
      nama_ruangan: NAMA_RUANGAN.value,
      jenisobgyn: jenisobgyn,
      jenisinterna: jenisinterna,
      jenistrauma: jenistrauma,
      norec_emr: norec_emr,
      edit: edit,
    };
    if (kelompokQuery) {
      query.kelompokuser = kelompokQuery ?? kelompokUser;
    }
  } else {
    query = {
      nocmfk: selectedRegistrasi.value.nocmfk,
      norec_pasien_daftar: selectedRegistrasi.value.norec_pd,
      norec_pd: selectedRegistrasi.value.norec_pd,
      norec_apd: selectedRegistrasi.value.norec_apd,
      nama_ruangan: NAMA_RUANGAN.value,
      jenisinterna: jenisinterna,
      jenistrauma: jenistrauma,
      jenisobgyn: jenisobgyn,
      edit: edit,
    };
    if (kelompokQuery) {
      query.kelompokuser = kelompokQuery ?? kelompokUser;
    }
  }

  console.log(query);
  if (form.indexOf('index_tab') > -1) {
    params = {
      index_tabs: 1,
    };
  }

  nextTick(() => {
    if(form && form.indexOf('module-emr-profile-pasien-page-emr') > -1) {
      router.push({
        name: `${form}`,
        query: query,
        params: params,
      }).then(() => {
        const newBasePath = router.currentRoute.value.path;

        if (currentBasePath === newBasePath &&
            newBasePath !== '/module/emr/profile-pasien' &&
            isReload.value === true) {
          location.reload();
        }
      });
    }else {
      try {
        router.push({
          name: form,
          query: query,
          params: params,
        }).then(() => {
          // Extract base path from the new URL
          const newBasePath = router.currentRoute.value.path;

          // Check if the base paths are the same and not the specific path to skip reload
          if (currentBasePath === newBasePath &&
              newBasePath !== '/module/emr/profile-pasien' &&
              isReload.value === true) {
            location.reload();
          }
        });
      } catch (error) {
        console.error('Error during routing:', form);
        router.push({
          name: `module-emr-profile-pasien-page-emr-${form}`,
          query: query,
          params: params,
        }).then(() => {
          const newBasePath = router.currentRoute.value.path;

          if (currentBasePath === newBasePath &&
              newBasePath !== '/module/emr/profile-pasien' &&
              isReload.value === true) {
            location.reload();
          }
        });
      }
    }
  });
};
/*


*/
const onTabClick = (e: any) => {
  console.log('Halo ini diklik')
  if (isRemoveTAB.value) return
  COLLECTION.value = e.items ? e.items.collection : e.collection
  TAB_URL.value = e.url_form
  TAB_ACTIVE.value = e.label
  TAB_ACTIVE_ROUTER.value = e.url_form?.includes("module-emr-profile-pasien-page-emr-")
  ? e.url_form
  : `module-emr-profile-pasien-page-emr-${e.url_form}` || TAB_ROUTER_DEFAULT.value;
  if (TAB_ITEMS.value.length) {
    setRoutingEMR(TAB_ACTIVE_ROUTER.value, e.norec_emr ? e.norec_emr : '')
  }
  else {
    TAB_ACTIVE.value = 'Dashboard'
  }
  isRemoveTAB.value = false
}
const removeTAB = (e: any) => {
  isRemoveTAB.value = true
  TAB_ITEMS.value.splice(e, 1)
  if (TAB_ITEMS.value.length == 0) {
    TAB_ACTIVE.value = 'Dashboard'
  } else {
    COLLECTION.value = TAB_ITEMS.value[TAB_ITEMS.value.length - 1].items ? TAB_ITEMS.value[TAB_ITEMS.value.length - 1].items.collection : TAB_ITEMS.value[TAB_ITEMS.value.length - 1].collection
    TAB_URL.value = TAB_ITEMS.value[TAB_ITEMS.value.length - 1].url_form
    TAB_ACTIVE_ROUTER.value = TAB_ITEMS.value[TAB_ITEMS.value.length - 1].url_form ? `${TAB_ITEMS.value[TAB_ITEMS.value.length - 1].url_form}` : TAB_ROUTER_DEFAULT.value
    setRoutingEMR(TAB_ACTIVE_ROUTER.value, e.norec_emr ? e.norec_emr : '')
  }
  setCacheEMRWhileReload()
}
const showMenuEMR = async () => {
  modalMENU.value = true
  await fetchMenuEMR()
}

// Handle the beforeRouteUpdate hook
onBeforeRouteUpdate(() => {
  routerChangeTAB.value = true
});
onBeforeRouteLeave(() => {
  routerChangeTAB.value = true
});
// Provide the RouterView reference
// provide('routerViewRef', routerViewRef);
const showMenu = async (e: any) => {
  // if(e.name == 'EMR' && pasien.value.statusemr && kelompokUser != 'rekam-medis'){
  //     H.alert('error','EMR telah terkunci, Silahkan minta akses peminjaman EMR')
  //     return
  // }

  if (e.name == 'Catatan Perkembangan Pasien Terintegrasi') {
    onTab()
    modalMENU.value = false
    return
  }
  if (e.collection == 'RencanaKeperawatanRawatInap') {
    NAMA_RUANGAN.value = e.namaruangan
    onTabRenpra()
    modalMENU.value = false
    return
  }
  if (e.url_form == 'module-emr-profile-pasien-page-emr-formulir-transfer-pasien-intra-rumah-sakit') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-formulir-transfer-pasien-intra-rumah-sakit-index_tabs';
  }
  if (e.url_form == 'module-emr-profile-pasien-page-emr-formulir-asuhan-keperawatan-dan-observasi-pasien-hemodialisa') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-formulir-asuhan-keperawatan-dan-observasi-pasien-hemodialisa-index_tabs';
  }
  if (e.url_form == 'formulir-pemantauan-kateter-intravena-perifer') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-formulir-pemantauan-kateter-intravena-perifer-index_tabs';
  }
  if (e.url_form == 'asuhan-keperawatan-peri-operatif') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-asuhan-keperawatan-peri-operatif-index_tabs';
  }
  if (e.url_form == 'module-emr-profile-pasien-page-emr-assesmen-pra-operasi-laki') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-assesmen-pra-operasi-laki-index_tabs';
  }
  if (e.url_form == 'module-emr-profile-pasien-page-emr-assesmen-pra-operasi-perempuan') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-assesmen-pra-operasi-perempuan-index_tabs';
  }
  if (e.url_form == 'module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-neonatus') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-asesmen-awal-keperawatan-neonatus-index_tabs';
  }
  if (e.url_form == 'module-emr-profile-pasien-page-emr-form-monitoring-tranfusi-darah') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-form-monitoring-tranfusi-darah-index_tabs';
  }
  if (e.url_form == 'surat-permintaan-penggunaan-obat-khusus-kemoterapi') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-surat-permintaan-penggunaan-obat-khusus-kemoterapi-index_tabs';
  }
  if (e.url_form == 'monitoring-pemberian-obat-kemoterapi-intra-vena') {
    e.url_form = 'module-emr-profile-pasien-page-emr-tabs-monitoring-pemberian-obat-kemoterapi-intra-vena-index_tabs';
  }
  isRemoveTAB.value = false
  // let norec_pd = '', nocmfk = ''
  // if (selectedRegistrasi.value.norec_pd == undefined) {
  //   norec_pd = NOREC_PD
  //   nocmfk = ID_PASIEN
  // } else if (selectedRegistrasi.value.norec_pd != NOREC_PD) {
  //   return
  // }
  if (e.name == 'Bundle EMR') {
    classMODALMENU.value = 'large'
    loadMenuEMRBundle()
  } else if (e.name != 'EMR') {
    for (let x = 0; x < TAB_ITEMS.value.length; x++) {
      const element: any = TAB_ITEMS.value[x];
      if (element.label == e.name) {
        TAB_ITEMS.value.splice(x, 1)
      }
    }

    modalMENU.value = false
    // if (e.name == 'Asesmen Awal') {
    //   setRoutingEMR(e.form, e.norec_emr ? e.norec_emr : '')
    // } else {
    COLLECTION.value = e.items ? e.items.collection : e.collection
    TAB_URL.value = e.url_form
    TAB_ACTIVE.value = e.name
    NAMA_RUANGAN.value = e.namaruangan
    if (urledit.value != true) {
      TAB_ACTIVE_ROUTER.value = e.url_form ? `module-emr-profile-pasien-page-emr-${e.url_form}` : TAB_ROUTER_DEFAULT.value
    } else {
      TAB_ACTIVE_ROUTER.value = e.url_form ? `${e.url_form}` : TAB_ROUTER_DEFAULT.value
    }
    TAB_ITEMS.value.push({ label: e.name, icon: 'pi pi-fw pi-file', url_form: e.url_form, items: e.items, collection: e.collection })
    setCacheEMRWhileReload()
    setRoutingEMR(TAB_ACTIVE_ROUTER.value, e.norec_emr ? e.norec_emr : '', e.edit ? e.edit : '')
  } else {
    classMODALMENU.value = 'large'
    loadMenuEMR()
  }
}

const changeClosing = async (e: any) => {
  klikLoad.value = true
  await useApi().post('/emr/closing-pasien', { 'norec_pd': NOREC_PD, 'closing': e }).then((response) => {
    reloadListReg()
  })
  klikLoad.value = false
}

const setCacheEMRWhileReload = () => {
  H.cacheHelper().set('xxx_cache_menu_' + route.query.nocmfk, {
    'menu': TAB_ITEMS.value,
    'active': TAB_ACTIVE.value,
    'url_form': TAB_URL.value,
    'collection': COLLECTION.value
  })
}
const backMenuEMR = () => {
  listMenuEMR.value = []
  listMenuEMRBundle.value = []
  listMenuEMRBundleDetail.value.data = []
  filterMenu.value = ''
  classMODALMENU.value = 'medium'
}
const setDashboard = () => {
  COLLECTION.value = 'Dashboard'
  TAB_URL.value = `module-emr-profile-pasien`
  TAB_ACTIVE.value = 'Dashboard'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
  // TAB_ACTIVE.value = 'Dashboard'
  if (selectedRegistrasi.value.objectdepartemenfk == 9) {
    fetchDataAndProcess()
  }
  detailPelayanan(selectedRegistrasi.value.norec)
}
const loadMenuEMR = () => {
  listMenuEMR.value = []
  totalMenu.value = 0
  useApi()
    .get(`/emr/menu-emr-detail?namaemr=asesmen&departemen=${selectedRegistrasi.value.objectdepartemenfk}&ruangan=${selectedRegistrasi.value.objectruanganlastfk}`)
    .then((response: any) => {
      listMenuEMR.value = response.data
      totalMenu.value = response.total
    })
}
const loadMenuEMRBundle = () => {
  listMenuEMRBundle.value = []
  useApi().get(`/emr/menu-emr-bundle`).then((response: any) => {
    listMenuEMRBundle.value = response
  })
}
const accessMenuDetail = async (e: any) => {
  isLoading.value = true
  await useApi().get(`/emr/menu-emr-bundle-detail?head=${e.id}`).then((response: any) => {
    isLoading.value = false
    listMenuEMRBundleDetail.value = response ? response : []
    if (listMenuEMRBundleDetail.value.data.length == 0) {
      H.alert('error', 'Bundle EMR Belum Di Mapping')
    }
  })
}
const accessMenuEMR = (e: any) => {
  showMenu({ name: e.label, icon: 'pi pi-fw pi-file', url_form: e.url_form, items: e })
}

const scrollLeft = () => {
  contentRefScrollMenu.value.scrollBy({
    left: -200,
    behavior: "smooth"
  });
}
const scrollRight = () => {
  contentRefScrollMenu.value.scrollBy({
    left: 200,
    behavior: "smooth"
  });
}

onMounted(async () => {
  await pasienByID(ID_PASIEN)
  if (selectedRegistrasi.value.objectdepartemenfk == 9) {
    await fetchDataAndProcess()
  }
  let cacheMenu = H.cacheHelper().get('xxx_cache_menu_' + route.query.nocmfk)
  // setDefaultMenu()
  if (cacheMenu && cacheMenu.menu.length) {
    TAB_ITEMS.value = cacheMenu.menu
    TAB_ACTIVE.value = cacheMenu.active
    TAB_URL.value = cacheMenu.url_form
    COLLECTION.value = cacheMenu.collection
    if (COLLECTION.value == null || COLLECTION.value == '') {
      let coll_name = await useApi().get(`emr/collection/${cacheMenu.url_form}`)
      COLLECTION.value = coll_name
    }
  }
  getIntruksiDokter()
  detailPelayanan(selectedRegistrasi.value.norec)
  // router.replace({
  //   query: {
  //     nocmfk: selectedRegistrasi.value.nocmfk,
  //     norec_pasien_daftar: selectedRegistrasi.value.norec_pd,
  //     norec_pd: selectedRegistrasi.value.norec_pd,
  //   }
  // })
})

onBeforeRouteLeave((to, from, next) => {
  if (to.name === from.name) {
    // Handle the refresh event here
    router.replace({
      query: {
        nocmfk: selectedRegistrasi.value.nocmfk,
        norec_pasien_daftar: selectedRegistrasi.value.norec_pd,
        norec_pd: selectedRegistrasi.value.norec_pd,
      }
    })
  }

  next()
});
currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

watch(
  () => currentPage.value.page,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      routerChangeTAB.value = false
      currenPageChange.value = true
      reloadListReg()
    } else {
      currenPageChange.value = false
      routerChangeTAB.value = true
    }
  }
)

watch(
  () => currentPage.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      routerChangeTAB.value = false
      currenPageChange.value = true
      reloadListReg()
    } else {
      currenPageChange.value = false
      routerChangeTAB.value = true
    }
  }
)

watch(TAB_ITEMS.value, () => {
  TAB_DEFAULT.value = ''
  if (TAB_ITEMS.value.length == 0) {
    TAB_DEFAULT.value = 'Dashboard';
  }
})

const internal = setInterval(() => {
  now.value = new Date()
}, 1000);

const hasilLAB_THEO: any = ref(H.hasilLAB_THEO())
TAB_ACTIVE_ROUTER.value = TAB_ROUTER_DEFAULT.value
const editEMR = (e: any) => {
  if (e.caption) {
    e.namaemr = e.caption
  }

  urledit.value = true
  showMenu({
    'name': e.namaemr,
    'url_form': e.url_form,
    'norec_emr': e.emrpasienfk,
    'namaruangan': e.ruangan,
    'collection': e.table,
    'edit': true
  })
}
const hapusEMR = (e: any) => {

  useApi()
    .post(`/emr/hapus-emr`, {
      'norec': e.emrpasienfk,
      'collection': e.collection ? e.collection : e.table,
    })
    .then((response: any) => {
      reloadRiwayat()
    })
}
const cetakEMR = (e: any) => {
  H.printBlade(`emr/cetak/${e.table}?emrpasienfk=${e.emrpasienfk}&pdf=true`)
}
const openEMR = (e: any) => {
  if (e.alergi) {
    item.alergi = dataAlergi.value
    showModalAlergi.value = e.alergi
  } else if (e.riwayat) {
    router.push({
      name: 'module-registrasi-riwayat-registrasi',
      query: {
        nocmfk: ID_PASIEN,
      },
    })
  }
  else {
    showMenu({
      'name': e.namaemr,
      'url_form': e.url_form,
    })
  }

}
const fetchMenuEMR = async () => {
  listMenu.value = []
  await useApi().get(`/emr/menu-emr`).then((response: any) => {
    for (let index = 0; index < response.length; index++) {
      const element = response[index];
      if (kelompokUser == 'perawat') {
        // if (index != 5 && index != 6 && index != 8) {
        listMenu.value.push(element)
        // }
      } else {
        listMenu.value.push(element)
      }
    }
  })
}
const cetakSKD = async () => {
  isLoadingSKD.value = true
  if (selectedRegistrasi.value.kelompokpasien.indexOf('umum') > -1) {
    useApi().get(
      `/emr/get-perjanjian?nocm=${pasien.value.nocm}&belum=true&limit=1`).then((response: any) => {
        isLoadingSKD.value = false
        if (response.data.length > 0) {
          H.printBlade('emr/cetak-surat-kontrol?norec=' + response.data[0].objectsuratfk + '&noregistrasi=' + selectedRegistrasi.value.noregistrasi + '&pdf=true');

        } else {
          H.alert('warning', 'Data tidak ada')
        }
      })
  } else {
    cariSurkon()
  }
}


const cariSurkon = async () => {

  let bulan: any = new Date().getMonth() + 1

  bulan = bulan.toString().length == 1 ? '0' + bulan.toString() : bulan
  let json = {
    "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${new Date().getFullYear()}/Nokartu/${'0002055721026'}/filter/${2}`,
    "method": "GET",
    "data": null
  }

  isLoadingSKD.value = true
  await useApi().postBPJS('/bridging/bpjs/tools', json).then(async (x) => {
    isLoadingSKD.value = false
    if (x.metaData.code == 200) {
      await useApi().get(
        `general/ppk-bpjs`
      ).then((response) => {
        namappkRumahSakit.value = response.BPJS_namaPPKRujukan
      })
      cetakSPRI(x.response.list[0])
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const cetakSPRI = (e: any) => {
  let nosuratkontrol = e.noSuratKontrol
  let tglrencanakontrol = e.tglRencanaKontrol
  let txttglentrirencanakontrol = e.tglTerbitKontrol
  let noka = e.noKartu
  let nama = pasien.value.namapasien
  let tl = pasien.value.tgllahir
  let arr = tl.split("-");
  let tglL = arr[2] + '-' + arr[1] + '-' + arr[0]
  let tgllahir = H.formatDate(new Date(tglL), 'YYYY-MM-DD')
  let namaPoliTujuan = e.namaPoliTujuan
  let jeniskelamin = pasien.value.jeniskelamin
  let jnsKontrol = e.jnsKontrol
  let namaDokter = e.namaDokter
  let kddx = '-'
  let nmdpjpsepasal = selectedRegistrasi.value.dokter ? selectedRegistrasi.value.dokter : '-'
  let iddok = selectedRegistrasi.value.objectpegawaifk ? selectedRegistrasi.value.objectpegawaifk : 'null'
  let dxawal = '-'

  if (e.noSepAsalKontrol != null) {
    let json = {
      "url": "sep/" + e.noSepAsalKontrol,
      "method": "GET",
      "data": null
    }
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      if (x.metaData.code == 200) {
        dxawal = x.response.diagnosa

        cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
          nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
          jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);


      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })

  } else {
    dxawal = '-'
    cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
      nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
      jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);

  }
}
const cetakBladeSKDP = (nosuratkontrol: any, tglrencanakontrol: any, txttglentrirencanakontrol: any, noka: any,
  nama: any, tgllahir: any, namappkRumahSakit: any, namaPoliTujuan: any, jeniskelamin: any, dxawal: any, jnsKontrol: any,
  kddx: any, namaDokter: any, nmdpjpsepasal: any, iddok: any) => {

  H.printBlade('emr/cetak-spri?nosuratkontrol='
    + nosuratkontrol + '&tglrencanakontrol=' + tglrencanakontrol + '&txttglentrirencanakontrol=' + txttglentrirencanakontrol
    + '&noka=' + noka
    + '&tgllahir=' + tgllahir
    + '&namappkRumahSakit=' + namappkRumahSakit
    + '&namaPoliTujuan=' + namaPoliTujuan
    + '&jeniskelamin=' + jeniskelamin
    + '&dxawal=' + dxawal
    + '&jnsKontrol=' + jnsKontrol
    + '&kddx=' + kddx
    + '&namaDokter=' + namaDokter
    + '&nmdpjpsepasal=' + nmdpjpsepasal
    + '&iddok=' + iddok
    + '&nama=' + nama);
}

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const shareAPI = () => {
  if (navigator.share) {
    navigator.share({
      title: 'TRANSMEDIC',
      // URL to share
      url: window.location.href,
    }).then(() => {
      console.log('Thanks for sharing!');
    }).catch(err => {
      // Handle errors, if occurred
      console.log(
        "Error while using Web share API:");
      console.log(err);
    });
  } else {
    // Alerts user if API not available
    alert("Browser doesn't support this API !");
  }
}
const hitungBiayaSementara = async () => {
  isFlafon.value = true
  const e = await useApi().get('/bridging/inacbgs/get-for-plafon?norec_pd=' + selectedRegistrasi.value.norec_pd)
  if (e.set_claim_data == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Tidak ada')
    return
  }
  if (e.set_claim_data.metadata.nomor_sep == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Belum di isi')
    return
  }
  if (e.set_claim_data.data.diagnosa == null ||
    e.set_claim_data.data.diagnosa == '' ||
    e.set_claim_data.data.diagnosa == false) {
    isFlafon.value = false
    H.alert('info', 'Data Diagnosa Belum di isi')
    return
  }
  if (e.set_claim_data.metadata.nomor_sep == null) {

    let nosepTEMP = '9999R9999999V000999'
    e.new_claim.data.nomor_sep = nosepTEMP
    e.set_claim_data.data.nomor_sep = nosepTEMP
    e.set_claim_data.metadata.nomor_sep = nosepTEMP
    e.grouper.data.nomor_sep = nosepTEMP
    e.delete_claim.data.nomor_sep = nosepTEMP
  }
  if (e.set_claim_data.data.tgl_pulang == null) {

    e.set_claim_data.data.tgl_pulang = e.set_claim_data.data.tgl_masuk
  }
  dataSourceINACBG.value = e.data
  isFlafon.value = true
  let json = []
  json.push(e.new_claim)
  json.push(e.set_claim_data)

  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {

    for (let x = 0; x < r.response.dataresponse.length; x++) {
      const element = r.response.dataresponse[x];
      if (element.dataresponse.metadata.code == 200) {
        await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.grouper] }).then(async (rr) => {

          let arrGroup = []
          for (let x = 0; x < rr.response.dataresponse.length; x++) {
            const elementx = rr.response.dataresponse[x];
            if (elementx.dataresponse.metadata.code == 200) {
              arrGroup.push({
                'nomor_sep': elementx.datarequest.data.nomor_sep,
                'inacbg_status': elementx.dataresponse.metadata.method,
                'dataresponse': elementx.dataresponse
              })
              break
            } else {
              H.alert('error', elementx.dataresponse.response.cbg.description)
            }
          }

          await saveGrouping(arrGroup, true)
          await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.delete_claim] }).then(async (xx) => {
            let arrStatus = []
            for (let x = 0; x < xx.response.dataresponse.length; x++) {
              const element = xx.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                arrStatus.push({
                  'nomor_sep': element.datarequest.data.nomor_sep,
                  'inacbg_status': null
                })
                break
                // H.alert('success', element.dataresponse.metadata.message)
              } else {
                H.alert('error', element.dataresponse.metadata.message)
              }
            }
            await saveStatus(arrStatus, true)
          })
        })
        break
      } else {
        if (element.dataresponse.metadata.message != 'Duplikasi nomor SEP') {
          H.alert('error', element.dataresponse.metadata.message)
        }
      }
    }

    isFlafon.value = false
  }, (error) => {
    isFlafon.value = false
  })
}
const saveGrouping = async (e: any, load: boolean) => {
  if (!e.length) return
  for (let i = 0; i < dataSourceINACBG.value.length; i++) {
    const element = dataSourceINACBG.value[i];
    for (var ii = 0; ii < e.length; ii++) {
      const elem2 = e[ii]
      if (element.nomor_sep == elem2.nomor_sep) {
        elem2.norec = element.norec
        elem2.jenis_rawat = element.jenis_rawat
      }
    }
  }
  let tarifINACB = 0
  for (var ii = 0; ii < e.length; ii++) {
    const elem2 = e[ii]
    let totaldijamin = 0
    let biayanaikkelas = 0
    totaldijamin = elem2.dataresponse.response.cbg.tariff
    if (elem2.jenis_rawat != 1) {
      // totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
    } else {
      // let hakkelas = elem2.dataresponse.response.kelas
      // if (hakkelas == "kelas_1") {
      //   totaldijamin = elem2.dataresponse.tarif_alt[0].tarif_inacbg
      // } else if (hakkelas == "kelas_2") {
      //   totaldijamin = elem2.dataresponse.tarif_alt[1].tarif_inacbg
      // } else if (hakkelas == "kelas_3") {
      //   totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
      // }

      biayanaikkelas = elem2.dataresponse.response.add_payment_amt ? elem2.dataresponse.response.add_payment_amt : 0
      if (biayanaikkelas < 0) {
        biayanaikkelas = 0
      }
    }
    let json = {
      'norec': elem2.norec,
      'totaldijamin': totaldijamin,
      'biayanaikkelas': biayanaikkelas,
      'inacbg_grouper': elem2.dataresponse
    }
    tarifINACB = totaldijamin
    await useApi().postBPJS('/bridging/inacbgs/save-grouping', json)

  }
  if (load)
    fetchDataINACBG(tarifINACB)
}
const fetchDataINACBG = async (tarifINACB: any) => {
  selectedRegistrasi.value.inacbg_totalgrouper = tarifINACB


  selectedRegistrasi.value.billing = await fetchTotalBill()
  setColorFlafon({
    'inacbg_totalgrouper': selectedRegistrasi.value.inacbg_totalgrouper,
    'billing': selectedRegistrasi.value.billing,
  })
}
const fetchTotalBill = async () => {
  return await useApi().get('/emr/total-biliing?noregistrasi=' + selectedRegistrasi.value.noregistrasi)
}
const setColorFlafon = (element: any) => {
  let ditanggung = parseFloat(element.inacbg_totalgrouper)
  let totaltagihan = parseFloat(element.billing);
  let presn = ditanggung * 0.1
  let totalPersen = ditanggung - presn

  if (ditanggung != 0 && totaltagihan >= ditanggung) {
    colorPLAFON.value = 'danger'
  } else if (ditanggung != 0 && totaltagihan >= totalPersen) {
    colorPLAFON.value = 'warning'
  } else {
    colorPLAFON.value = 'info'
  }
}
const saveStatus = async (e: any, load: boolean) => {
  if (!e.length) return
  for (let i = 0; i < dataSourceINACBG.value.length; i++) {
    const element = dataSourceINACBG.value[i];
    for (var ii = 0; ii < e.length; ii++) {
      const elem2 = e[ii]
      if (element.nomor_sep == elem2.nomor_sep) {
        elem2.norec = element.norec
      }
    }
  }
  await useApi().postBPJS('/bridging/inacbgs/save-status', { 'data': e }).then(async (r) => {
    // if (load)
    // fetchData()
  })
}
const showNavStatus = () => {
  if (classActiveNavEMR.value != '') {
    classActiveNavEMR.value = ''
  } else {
    classActiveNavEMR.value = 'active-nav-emr-mobile'
  }

}
const showNavStatusList = () => {
  if (classActiveNavLISTPASIEN.value != '') {
    classActiveNavLISTPASIEN.value = ''
  } else {
    classActiveNavLISTPASIEN.value = 'active-nav-emr-mobile-list'
  }
}

const iCare = async () => {
  if (!pasien.value.nobpjs) {
    H.alert('error', 'NO BPJS Masih kosong')
    return
  }
  if (!selectedRegistrasi.value.dpjplayan_kode) {
    H.alert('error', 'SEP belum diinputkan atau DPJP Layan SEP kosong')
    return
  }
  let json = {
    "url": "api/rs/validate",
    "method": "POST",
    "jenis": "i-care",
    "data": {
      "param": pasien.value.nobpjs,
      "kodedokter": parseInt(selectedRegistrasi.value.dpjplayan_kode)
    }
  }
  isLoadingIcare.value = true
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  isLoadingIcare.value = false
  if (res.metaData.code == 200) {
    window.open(res.response.url, "_blank")
  } else {
    H.alert('error', res.metaData.message)
  }

}
const profilPasien = async () => {
  await useApi()
    .get(
      `/emr/info-pasien?nocmfk=${route.query.nocmfk}`
    )
    .then((response: any) => {
      info.value = response
      modalProfil.value = true
    })

}
const catatanPasien = async () => {
  item.catatanDokter = "";
  await getCatatanDokter();
  modalCatatan.value = true
}


const saveAlergiPasien = async (e: any) => {
  isBtnLoading.value = true
  let data = {
    'id': ID_PASIEN,
    'alergi': e
  }
  await useApi().post('/emr/simpan-alergi-pasien', data).then((response) => {
    isBtnLoading.value = false
    showModalAlergi.value = false
    reloadRiwayat()
  })

}
const onTab = () => {
  console.log('ini CPPT:', kelompokUser)
  if (kelompokUser == 'perawat' && pasien.value.objectruanganlastfk == 237) {
    COLLECTION.value = 'CatatanPerkembanganPasienTerintegrasi'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-cppt-rev`
    TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-cppt-rev`
  } else {
    COLLECTION.value = 'CatatanPerkembanganPasienTerintegrasi'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-cppt-rev`
    TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-cppt-rev`
  }

  // COLLECTION.value = 'Tindakan'
  // TAB_URL.value = `module-emr-profile-pasien-page-emr-tindakan`
  // TAB_ACTIVE.value = 'Tindakan'
  // TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tindakan`

  // COLLECTION.value = 'CatatanPerkembanganPasienTerintegrasiRawatJalan'
  // TAB_URL.value = `module-emr-profile-pasien-page-emr-cppt-rj`
  // TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi Rawat Jalan'
  // TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-cppt-rj`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false

  // confirm.require({
  //   message: 'Apakah mau mengambil data assesmen sebelumnya?',
  //   header: 'Asesmen Awal Kurang Dari 90 Hari',
  //   icon: 'pi pi-info-circle',
  //   acceptClass: 'p-button-danger',
  //   accept: () => {
  //     setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  //     isRemoveTAB.value = false
  //   },
  //   reject: () => {
  //     setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  //     isRemoveTAB.value = false
  //    },
  // })
}

const onTabRenpra = () => {
  COLLECTION.value = 'RencanaKeperawatanRawatInap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-rencana-keperawatan-ranap`
  TAB_ACTIVE.value = 'Rencana Keperawatan Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-rencana-keperawatan-ranap`
  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const laporanSelesaiRadiasi = () => {
  COLLECTION.value = 'LaporanSelesaiRadiasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-laporan-selesai-radiasi`
  TAB_ACTIVE.value = 'Laporan Selesai Radiasi Unit Pelayanan Onkologi Radiasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-laporan-selesai-radiasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabAsesmenMedisNeonatus = () => {
  COLLECTION.value = 'AsesmenAwalMedisNeonatusRI'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-neonatus-ri`
  TAB_ACTIVE.value = 'Asesmen Awal Medis Neonatus'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-neonatus-ri`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabLaboratorium = () => {
  COLLECTION.value = 'Laboratorium'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-order-laboratorium`
  TAB_ACTIVE.value = 'Laboratorium'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-order-laboratorium`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onTabExtravasasi = () => {
  COLLECTION.value = 'FormulirPengkajianExtravasasiPadaPasienKemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-pengkajian-extravasasi-pada-pasien-kemoterapi`
  TAB_ACTIVE.value = 'Formulir Pengkajian Extravasasi Pada Pasien Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-pengkajian-extravasasi-pada-pasien-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabTimeOutPemberianObatKemoterapi = () => {
  COLLECTION.value = 'FormulirTimeoutPemberianObatKemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-timeout-pemberian-obat-kemoterapi`
  TAB_ACTIVE.value = 'Formulir Timeout Pemberian Obat kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-timeout-pemberian-obat-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onTabAsuhanObservasi = () => {
  COLLECTION.value = 'asuhankeperawatnPasienHemodialisis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asuhan-keperawatan-pasien-hemodialisis`
  TAB_ACTIVE.value = 'Asuhan Keperawatan Pasien Hemodialisis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asuhan-keperawatan-pasien-hemodialisis`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onTabPersetujuanTindakanHD = () => {
  COLLECTION.value = 'InformedConsentTindakanHemodialisa'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-informed-consent-hemodialisa`
  TAB_ACTIVE.value = 'Formulir Persetujuan Tindakan Hemodialisa'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-informed-consent-hemodialisa`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onTabPemakaianTerapoEri = () => {
  COLLECTION.value = 'PemakaianTerapiEriotropotine'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pemakaian-terapi-eriotropotine`
  TAB_ACTIVE.value = 'Formulir Persetujuan Tindakan Hemodialisa'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pemakaian-terapi-eriotropotine`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onTabAsuhanObservasi2 = () => {
  COLLECTION.value = 'FormulirAsuhanKeperawatanDanObservasiPasienHemodialisa'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-formulir-asuhan-keperawatan-dan-observasi-pasien-hemodialisa-index_tabs`
  TAB_ACTIVE.value = 'Asuhan Keperawatan Pasien Hemodialisis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-formulir-asuhan-keperawatan-dan-observasi-pasien-hemodialisa-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const resumeTindakanHD = () => {
  COLLECTION.value = 'ResumeTindakanHemodialisis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-resume-tindakan-hemodialisis`
  TAB_ACTIVE.value = 'Resume Tindakan Hemodialisis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-resume-tindakan-hemodialisis`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabBuktiPelayananCanggih = () => {
  COLLECTION.value = 'FormulirBuktiPelayananCanggih'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-bukti-pelayanan-canggih`
  TAB_ACTIVE.value = 'Bukti Pelayanan Canggih'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-bukti-pelayanan-canggih`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabDataUmumHD = () => {
  COLLECTION.value = 'DataUmumHD'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-data-umum-hd`
  TAB_ACTIVE.value = 'Data umum HD'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-data-umum-hd`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onTabRadiologi = () => {
  COLLECTION.value = 'Radiologi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-order-radiologi`
  TAB_ACTIVE.value = 'Radiologi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-order-radiologi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabBedah = () => {
  COLLECTION.value = 'Bedah'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-order-bedah`
  TAB_ACTIVE.value = 'Bedah'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-order-bedah`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabPenjadwalanKemoterapi = () => {
  COLLECTION.value = 'Penjadwalan Kemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-order-penjadwalan-kemoterapi`
  TAB_ACTIVE.value = 'Penjadwalan Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-order-penjadwalan-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabTindakan = () => {
  COLLECTION.value = 'Tindakan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tindakan`
  TAB_ACTIVE.value = 'Tindakan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tindakan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onLapoperasi = () => {
  COLLECTION.value = 'Laporan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-laporan-operasi`
  TAB_ACTIVE.value = 'Laporan Operasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-laporan-operasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onLembarsedasi = () => {
  COLLECTION.value = 'Lembar Pencatatan Sedasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-lembar-pencatatan-sedasi`
  TAB_ACTIVE.value = 'lembar pencatatan sedasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-lembar-pencatatan-sedasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onAsuhanKeperawatan = () => {
  COLLECTION.value = 'Asuhan Keperawatan Peri - Operatif'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-asuhan-keperawatan-peri-operatif-index_tabs`
  TAB_ACTIVE.value = 'Asuhan Keperawatan Peri - Operatif'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-asuhan-keperawatan-peri-operatif-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const obTabLaporanOperasi = () => {
  COLLECTION.value = 'LaporanOperasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-laporan-operasi-index_tabs`
  TAB_ACTIVE.value = 'Laporan Operasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-laporan-operasi-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onChecklistKeselamatan = () => {
  COLLECTION.value = 'Checklist Keselamatan Operasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-checklist-keselamatan-operasif`
  TAB_ACTIVE.value = 'Checklist Keselamatan Operasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-checklist-keselamatan-operasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onChecklistanatesi = () => {
  COLLECTION.value = 'CHECKLIST KESIAPAN ANESTHESI'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-checklist-kesiapan-anastesi`
  TAB_ACTIVE.value = 'CHECKLIST KESIAPAN ANESTHESI'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-checklist-kesiapan-anastesi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onpraanestesisedasi = () => {
  COLLECTION.value = 'Assesment Pra Anestesi / Sedasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-assesment-pra-anestesi-sedasi`
  TAB_ACTIVE.value = 'Assesment Pra Anestesi / Sedasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-assesment-pra-anestesi-sedasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onlembarpencatatan = () => {
  COLLECTION.value = 'Lembar Pencatatan Sedasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-lembar-pencatatan-sedasi`
  TAB_ACTIVE.value = 'Lembar Pencatatan Sedasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-lembar-pencatatan-sedasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabMedis_IGD = () => {
  COLLECTION.value = 'AsesmenAwalMedisGawatDarurat'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-gawat-darurat`
  TAB_ACTIVE.value = 'Asesmen Awal Medis Gawat Darurat'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-medis-gawat-darurat`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onkologiBoardMeetingB = () => {
  COLLECTION.value = 'OnBoardMeeting'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-on-board-meeting`
  TAB_ACTIVE.value = 'Onkologi Board Meeting B'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-on-board-meeting`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabCatatanKegiatanOnkrad = () => {
  COLLECTION.value = 'Catatan Kegiatan Onkologi Radiasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-catatan-kegiatan-runit-pelayanan-onkologi-radiasi`
  TAB_ACTIVE.value = 'Catatan Kegiatan Onkologi Radiasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-catatan-kegiatan-runit-pelayanan-onkologi-radiasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const ikhtisarPerawatanPasienHIV = () => {
  COLLECTION.value = 'IkhtisarPerawatanPasienHIVdanTerapiAntiretroviral'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-Ikhtisar-PPH&TA`
  TAB_ACTIVE.value = 'Ikhtisar Perawatan Pasien HIV'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-Ikhtisar-PPH&TA`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabPeriodonsia = () => {
  COLLECTION.value = 'Periodonsia'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-periodonsia`
  TAB_ACTIVE.value = 'Periodonsia'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-periodonsia`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabFormulirDPJP = () => {
  COLLECTION.value = 'FormulirDokterPenanggungJawabPelayanan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-dokter-penanggung-jawab`
  TAB_ACTIVE.value = 'Formulir Dokter Penanggung Jawab Pelayanan (DPJP)'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-dokter-penanggung-jawab`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabCatatanPemberianObat = () => {
  COLLECTION.value = 'CatatanPemberianObat'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-catatan-pemberian-obat`
  TAB_ACTIVE.value = 'Catatan Pemberian Obat'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-catatan-pemberian-obat`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const catatanPemberianObatNuklir = () => {
  COLLECTION.value = 'CatatanPemberianObatNuklir'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-catatan-pemberian-obat-nuklir`
  TAB_ACTIVE.value = 'Catatan Pemberian Obat Kedokteran Nuklir'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-catatan-pemberian-obat-nuklir`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const formKeseimbanganCairan = () => {
  COLLECTION.value = 'FormulirKeseimbanganCairan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-keseimbangan-cairan`
  TAB_ACTIVE.value = 'Formulir Keseimbangan Cairan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-keseimbangan-cairan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeperawatan = () => {
  COLLECTION.value = 'Assesmen Keperawatan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Keperawatan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeperawatanNurseNuklir = () => {
  COLLECTION.value = 'Assesmen Keperawatan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Keperawatan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabQAPasien = () => {
  COLLECTION.value = 'QA Pasien'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-qa-pasien-onkrad`
  TAB_ACTIVE.value = 'QA Pasien'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-qa-pasien-onkrad`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabFisio = () => {
  COLLECTION.value = 'Formulir Kedokteran Fisik dan Rehabilitasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-kedokteran-fisik-dan-rehabilitasi`
  TAB_ACTIVE.value = 'Formulir Kedokteran Fisik dan Rehabilitasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-kedokteran-fisik-dan-rehabilitasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const penjadwalanRadioterapi = () => {
  COLLECTION.value = 'Penjadwalan Radioterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-penjadwalan-radioterapi`
  TAB_ACTIVE.value = 'Penjadwalan Radioterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-penjadwalan-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabJadwalFisio = () => {
  COLLECTION.value = 'Jadwal Kunjungan Rehab dan Fisio'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-jadwal-kunjungan-rehab-dan-fisio`
  TAB_ACTIVE.value = 'Jadwal Kunjungan Rehab dan Fisio'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-jadwal-kunjungan-rehab-dan-fisio`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabLembarRehabMedik = () => {
  COLLECTION.value = 'LembarHasilTindakanUjiFungsiProsedurKFR'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-lembar-hasil-tindakan-uji-fungsi-prosedur-kfr`
  TAB_ACTIVE.value = 'Lembar Hasil Tindakan Uji Fungsi Rehabilitasi Medik'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-lembar-hasil-tindakan-uji-fungsi-prosedur-kfr`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabPengkajianHarian = () => {
  COLLECTION.value = 'Pengkajian Harian'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pengkajian-harian-onkrad`
  TAB_ACTIVE.value = 'Pengkajian Harian'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pengkajian-harian-onkrad`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabPreQC1 = () => {
  COLLECTION.value = 'PRE-QC 1'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-preqc1`
  TAB_ACTIVE.value = 'PRE-QC 1'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-preqc1`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const isLoadingHistori = ref(false)
const showModalIntruksi: any = ref(false);
const showModalHistoriKanker: any = ref(false);
const openModalIntruksi = () => {
  showModalIntruksi.value = true;
}
const openModalKanker = () => {
  showModalHistoriKanker.value = true;
  getHistoriKemo()
  // const pasien3 = pasien.value;
  // const registrasi3 = registrasi.value
  // console.log(JSON.stringify(pasien3, null, 2));
  // console.log(JSON.stringify(registrasi3, null, 2));
}
const saveHistoriKanker = async (pasien3, registrasi3) => {
  let json = {
    'tanggal': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
    'diagnosa': item.diagnosa,
    'staging': item.staging,
    'tanggalOperasi': H.formatDate(item.tanggalOperasi, 'YYYY-MM-DD HH:mm:ss'),
    'kemo': item.kemo,
    'tanggalKemoterapi': H.formatDate(item.tanggalKemoterapi, 'YYYY-MM-DD HH:mm:ss'),
    'radiasi': item.radiasi,
    'surveilans': item.surveilans,
    'perkembangan': item.perkembangan,
    'nocmfk': ID_PASIEN,
    'noregistrasifk': NOREC_PD
  }
  isLoading.value = true
  useApi().post(
    `dokter/save-riwayat-kanker`, json).then((response: any) => {
      isLoading.value = false
      // pasien.value.catatan = response.catatan
      getHistoriKemo()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const onTabPeresepanHemo = () => {
  COLLECTION.value = 'PeresepanHemodialisisRJ'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-peresepan-hemodialisis-rj`
  TAB_ACTIVE.value = 'Peresepan Hemodialisis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-peresepan-hemodialisis-rj`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabSkriningUmum = () => {
  COLLECTION.value = 'Skrining Umum Pasien Baru'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-skrining-umum-pasien-baru`
  TAB_ACTIVE.value = 'Skrining Umum Pasien Baru'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-skrining-umum-pasien-baru`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabPersetujuanKedokteranNuklir = () => {
  COLLECTION.value = 'PersetujuanTindakanKedokteranKN'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-persetujuan-tindakan-kedokteran-kn-index_tabs`
  TAB_ACTIVE.value = 'Persetujuan Tindakan Kedokteran KN'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-persetujuan-tindakan-kedokteran-kn-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabFormIdentitas = () => {
  COLLECTION.value = 'FormulirIdentitasDanInformasiTentangPasien'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-identitas-dan-informasi-tentang-pasien`
  TAB_ACTIVE.value = 'Formulir Identitas Dan Informasi Tentang Pasien'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-identitas-dan-informasi-tentang-pasien`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

// const onRingkasanKeluar = () => {
//   COLLECTION.value = 'RingkasanKeluar'
//   TAB_URL.value = `module-emr-profile-pasien-page-emr-ringkasan-keluar`
//   TAB_ACTIVE.value = 'Resume Medis/Ringkasan Keluar'
//   TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-ringkasan-keluar`

//   setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

//   isRemoveTAB.value = false
// }

const onTabKebidananNurse = async () => {

  COLLECTION.value = 'Nurse Station'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-kebidanan-pasien-rawat-jalan-nurse`
  TAB_ACTIVE.value = 'Nurse Station'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-kebidanan-pasien-rawat-jalan-nurse`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeperawatanNurse = () => {
  // kelompokQuery
  COLLECTION.value = 'Nurse Station'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan-nurse`
  TAB_ACTIVE.value = 'Nurse Station'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-pasien-rawat-jalan-nurse`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeperawatanIGD = () => {
  COLLECTION.value = 'Assesmen Keperawatan IGD'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-igd`
  TAB_ACTIVE.value = 'Assesmen Keperawatan IGD'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-igd`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabImplementasiKeperawatanIGD = () => {
  COLLECTION.value = 'ImplementasiKeperawatanIGD'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-implementasi-keperawatanIGD`
  TAB_ACTIVE.value = 'Implementasi Keperawatan IGD'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-implementasi-keperawatanIGD`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const asesmenKandungan = () => {
  COLLECTION.value = 'AsesmenAwalKebidananDanKandungan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-kebidanan-kandungan`
  TAB_ACTIVE.value = 'Asesmen Awal Kebidanan & Kandungan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-kebidanan-kandungan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const persetujuanTindakanKedokteran = () => {
  COLLECTION.value = 'PersetujuanTindakanKedokteran'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-persetujuan-tindakan-kedokteran-index_tabs`
  TAB_ACTIVE.value = 'Persetujuan Tindakan Kedokteran'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-persetujuan-tindakan-kedokteran-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const persetujuanTindakanKedokteranSHK = () => {
  COLLECTION.value = 'PersetujuanTindakanKedokteranSHK'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-persetujuan-tindakan-kedokteran-shk-index_tabs`
  TAB_ACTIVE.value = 'Persetujuan Tindakan Kedokteran'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-persetujuan-tindakan-kedokteran-shk-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const penolakanTindakanKedokteranSHK = () => {
  COLLECTION.value = 'PenolakanTindakanKedokteranSHK'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-penolakan-tindakan-kedokteran-shk`
  TAB_ACTIVE.value = 'Penolakan Tindakan Kedokteran'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-penolakan-tindakan-kedokteran-shk`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const catatanPemberianObat = () => {
  COLLECTION.value = 'CatatanPemberianObat'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-catatan-pemberian-obat`
  TAB_ACTIVE.value = 'Catatan Pemberian Obat'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-catatan-pemberian-obat`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const formTPIntraRS = () => {
  COLLECTION.value = 'FormulirTransferPasienIntraRS'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-formulir-transfer-pasien-intra-rumah-sakit-index_tabs`
  TAB_ACTIVE.value = 'Formulir Transfer Pasien Intra Rumah Sakit'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-formulir-transfer-pasien-intra-rumah-sakit-index_tabs`
  NAMA_RUANGAN.value = undefined;

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const monitoringPemberianObatKIV = () => {
  COLLECTION.value = 'FormulirAsuhanDanKeperawatanPasienKemoterapiRawatJalan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-monitoring-pemberian-obat-kemoterapi-intra-vena-index_tabs`
  TAB_ACTIVE.value = 'Monitoring Pemberian Obat Kemoterapi Intra Vena'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-monitoring-pemberian-obat-kemoterapi-intra-vena-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const formulirADOKPK = () => {
  COLLECTION.value = 'FormulirAsuhanDanKeperawatanPasienKemoterapiRawatJalan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-asuhan-dan-obeservasi-keperawatan-pasien-kemoterapi-rawat-jalan`
  TAB_ACTIVE.value = 'Formulir Asuhan dan Observasi Keperawatan Pasien Kemoterapi Rawat Jalan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-asuhan-dan-obeservasi-keperawatan-pasien-kemoterapi-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const timeoutPOK = () => {
  COLLECTION.value = 'FormulirTimeoutPemberianObatKemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-timeout-pemberian-obat-kemoterapi`
  TAB_ACTIVE.value = 'Formulir Timeout Pemberian Obat Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-timeout-pemberian-obat-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const catatanPemberianObatKemoterapi = () => {
  COLLECTION.value = 'FormulirCatataPemberianObatKemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-catatan-pemberian-obat-kemoterapi`
  TAB_ACTIVE.value = 'Formulir Catatan Pemberian Obat Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-catatan-pemberian-obat-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const catatanKegiatanUOR = () => {
  COLLECTION.value = 'CatatanKegiatanRadioterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-catatan-kegiatan-radioterapi`
  TAB_ACTIVE.value = 'Catatan Kegiatan Radioterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-catatan-kegiatan-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const formulirBuktiCanggih = () => {
  COLLECTION.value = 'FormulirBuktiCanggihPelayanan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-bukti-pelayanan-canggih`
  TAB_ACTIVE.value = 'Formulir Bukti Canggih Pelayanan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-bukti-pelayanan-canggih`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const asesmenAwalGizi = () => {
  COLLECTION.value = 'AsesmenAwalRencanaPerawatanKlinisGizi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-dan-rencana-perawatan-gizi-klinis-rawat-inap-intensif`
  TAB_ACTIVE.value = 'Asesmen Awal Dan Rencana Perawatan Gizi Klinis Rawat Inap Dan Intensif'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-dan-rencana-perawatan-gizi-klinis-rawat-inap-intensif`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const asesmenAwalGiziGeriatri = () => {
  COLLECTION.value = 'AsesmenGiziGeriatriRawatJalan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-gizi-geriatri-rawat-jalan`
  TAB_ACTIVE.value = 'Asesmen Asesmen Gizi Geriatri Rawat Jalan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-gizi-geriatri-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const persetujuanUmum = () => {
  COLLECTION.value = 'GeneralConsentPerawatanIntensif'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-general-consent`
  TAB_ACTIVE.value = 'Persetujuan Umum/General Consent'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-general-consent`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

// const persetujuanUmum = () => {
//   COLLECTION.value = 'GeneralConsentPerawatanIntensif'
//   TAB_URL.value = `module-emr-profile-pasien-page-emr-general-consent-perawatan-intensif`
//   TAB_ACTIVE.value = 'Persetujuan Umum/General Consent Perawatan Intensif'
//   TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-general-consent-perawatan-intensif`

//   setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

//   isRemoveTAB.value = false
// }

const pemberianInformasiRI = () => {
  COLLECTION.value = 'PemberianInformasiRawatInap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pemberian-informasi-rawat-inap`
  TAB_ACTIVE.value = 'Pemberian Informasi Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pemberian-informasi-rawat-inap`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const checklistPemberianInformasiRI = () => {
  COLLECTION.value = 'ChecklistPemberianInformasiRawatInap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-checklist-pemberian-informasi-rawat-inap`
  TAB_ACTIVE.value = 'Checklist Pemberian Informasi Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-checklist-pemberian-informasi-rawat-inap`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const perkiraanBiaya = () => {
  COLLECTION.value = 'perkiraanBiaya'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-perkiraan-biaya`
  TAB_ACTIVE.value = 'Perkiraan Biaya (Cost Estimasi)'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-perkiraan-biaya`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const slipAdmission = () => {
  COLLECTION.value = 'SlipAdmission'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-slip-admission`
  TAB_ACTIVE.value = 'Slip Admission'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-slip-admission`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const suratPermintaanDirawat = () => {
  COLLECTION.value = 'SuratPermintaanDirawat'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-surat-permintaan-dirawat`
  TAB_ACTIVE.value = 'Surat Permintaan Dirawat'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-surat-permintaan-dirawat`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const suratPernyataanPermintaan = () => {
  COLLECTION.value = 'PermintaanKelasRawatInap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-permintaan-kelas-rawat-inap`
  TAB_ACTIVE.value = 'Surat Pernyataan Permintaan Kelas Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-permintaan-kelas-rawat-inap`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const suratPernyataanPA = () => {
  COLLECTION.value = 'SuratPernyataanPenggunaanAsuransi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-surat-pernyataan-penggunaan-asuransi`
  TAB_ACTIVE.value = 'Surat Pernyataan Penggunaan Asuransi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-surat-pernyataan-penggunaan-asuransi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const suketGadar = () => {
  COLLECTION.value = 'SuratKeteranganGawatDarurat'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-surat-keterangan-gawat-darurat`
  TAB_ACTIVE.value = 'Surat Keterangan Gawat Darurat'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-surat-keterangan-gawat-darurat`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const triagePasienGinekologi = () => {
  COLLECTION.value = 'TriagePasienObstetriGinekologiIGD'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-triage-pasien-obstetri-ginekologi-igd`
  TAB_ACTIVE.value = 'Triage Pasien Obstetri Ginekologi IGD'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-triage-pasien-obstetri-ginekologi-igd`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const formulirPemantauanKateter = () => {
  COLLECTION.value = 'FormulirPemantauanKateterIntravenaPerifer'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-formulir-pemantauan-kateter-intravena-perifer-tabs_index`
  TAB_ACTIVE.value = 'Formulir Pemantauan Kateter Intravena Perifer'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-formulir-pemantauan-kateter-intravena-perifer-tabs_index`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const tabMEOWS = () => {
  COLLECTION.value = 'PemantauanMEOWS'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pemantauan-meows`
  TAB_ACTIVE.value = 'Pemantauan Modified Early Obstetric Warning Score (MEOWS)'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pemantauan-meows`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const pengkajianEWS = () => {
  COLLECTION.value = 'PemantauanAdultEWS'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pemantauan-adult-ews`
  TAB_ACTIVE.value = 'Pemantauan Adult Early Warning Score(AEWS)'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pemantauan-adult-ews`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabImplementasiKeperawatan = () => {
  COLLECTION.value = 'ImplementasiKeperawatan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-implementasi-keperawatan`
  TAB_ACTIVE.value = 'Implementasi Keperawatan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-implementasi-keperawatan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabImplementasiKeperawatanNuklir = () => {
  COLLECTION.value = 'ImplementasiKeperawatanNuklir'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-implementasi-keperawatan-nuklir`
  TAB_ACTIVE.value = 'Implementasi Keperawatan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-implementasi-keperawatan-nuklir`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabTriageIGD = () => {
  COLLECTION.value = 'Triage IGD'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-triage-pasien-igd`
  TAB_ACTIVE.value = 'Triage IGD'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-triage-pasien-igd`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeperawatanRanap = () => {
  COLLECTION.value = 'Assesmen Keperawatan Rawat Inap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-rawat-inap`
  TAB_ACTIVE.value = 'Assesmen Keperawatan Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-rawat-inap`
  NAMA_RUANGAN.value = undefined;
  isReload.value = true;

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

// REKONSILIASI OBAT

const onTabRekonMasuk = () => {
  COLLECTION.value = 'rekonmasuk'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-rekonsiliasi-masuk`
  TAB_ACTIVE.value = 'Rekonsili Obat Saat Admisi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-rekonsiliasi-masuk`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabRekonKeluar = () => {
  COLLECTION.value = 'rekonkeluar'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-rekonsiliasi-pindah-ruangan-index_tabs`
  TAB_ACTIVE.value = 'rekonkeluar'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-rekonsiliasi-pindah-ruangan-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
const onTabRekonKeluar2 = () => {
  COLLECTION.value = 'rekonkeluar'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-rekonsiliasi-keluar`
  TAB_ACTIVE.value = 'rekonkeluar'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-rekonsiliasi-keluar`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabRekonPindah = () => {
  COLLECTION.value = 'rekonpindahruangan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-rekon-pindah-index_tabs`
  TAB_ACTIVE.value = 'rekonpindahruangan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-rekon-pindah-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}
// END REKONSILIASI OBAT

function onTabRencanaKeperawatanRanap() {
  COLLECTION.value = 'RencanaKeperawatanRawatInap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-rencana-keperawatan-ranap`
  TAB_ACTIVE.value = 'Rencana Keperawatan Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-rencana-keperawatan-ranap`
  NAMA_RUANGAN.value = undefined
  isReload.value = true;

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeperawatanIntensif = () => {
  COLLECTION.value = 'Assesmen Keperawatan Rawat Inap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-intensif`
  TAB_ACTIVE.value = 'Assesmen Keperawatan Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-keperawatan-intensif`
  NAMA_RUANGAN.value = undefined;
  isReload.value = true;

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeperawatanNeonatus = () => {
  COLLECTION.value = 'Assesmen Keperawatan Rawat Inap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-asesmen-awal-keperawatan-neonatus-index_tabs`
  TAB_ACTIVE.value = 'Assesmen Keperawatan Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-asesmen-awal-keperawatan-neonatus-index_tabs`
  NAMA_RUANGAN.value = undefined;

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabPenunjang = () => {
  COLLECTION.value = 'Penunjang'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-penunjang`
  TAB_ACTIVE.value = 'Penunjang'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-penunjang`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabEKG_IGD = () => {
  COLLECTION.value = 'EKG_IGD'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-ekg-igd`
  TAB_ACTIVE.value = 'EKG IGD'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-ekg-igd`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKonsultasi = () => {
  COLLECTION.value = 'Konsultasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-konsultasi`
  TAB_ACTIVE.value = 'Konsultasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-konsultasi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKebidanan = async () => {

  COLLECTION.value = 'Assesmen Kebidanan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-awal-kebidanan-pasien-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Kebidanan'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-awal-kebidanan-pasien-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabAsesmenFisioterapi = async () => {

  COLLECTION.value = 'Assesmen Fisioterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-fisioterapi`
  TAB_ACTIVE.value = 'Assesmen Fisioterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-fisioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabAsesmenPsikologi = async () => {

  COLLECTION.value = 'Assesmen Psikologi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-psikologis`
  TAB_ACTIVE.value = 'Assesmen Psikologi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-psikologis`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabAsesmenPDP = async () => {

  COLLECTION.value = 'FormulirRujukanPasienKlinikDOTS'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-rujukan-pasien-klinik-dots`
  TAB_ACTIVE.value = 'Formulir Rujukan Pasien Ke Klinik DOTS Atau KTS/PDP'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-rujukan-pasien-klinik-dots`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabAsesmenKonselor = async () => {

  COLLECTION.value = 'AssesmenKonselorVCT'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-konselor-vct`
  TAB_ACTIVE.value = 'AssesmenKonselorVCT'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-konselor-vct`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeperawatanVCT = async () => {

  COLLECTION.value = 'AsesmenKeperawatanVCT'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-keperawatan-vct`
  TAB_ACTIVE.value = 'AsesmenKeperawatanVCT'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-keperawatan-vct`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onIkhtisarPasien = async () => {

  COLLECTION.value = 'FormIkhtisarPasien'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-Ikhtisar-PPH&TA`
  TAB_ACTIVE.value = 'FormIkhtisarPasien'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-Ikhtisar-PPH&TA`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKonselorMenyusui = async () => {

  COLLECTION.value = 'KonselorMenyusui'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-konselor-menyusui`
  TAB_ACTIVE.value = 'KonselorMenyusui'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-konselor-menyusui`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabGiziGeriatri = async () => {
  COLLECTION.value = 'AsesmenGiziGeriatriRawatJalan'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-gizi-geriatri-rawat-jalan`
  TAB_ACTIVE.value = 'Asesmen Gizi Geriatri'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-gizi-geriatri-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKestrad = async () => {
  COLLECTION.value = 'Catatan Komplementer'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-catatan-komplementer`
  TAB_ACTIVE.value = 'Catatan Komplementer'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-catatan-komplementer`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabPengamatanMenyusui = async () => {

  COLLECTION.value = 'PengamatanMenyusui'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pengamatan-menyusui`
  TAB_ACTIVE.value = 'PengamatanMenyusui'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pengamatan-menyusui`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKunjunganVCT = async () => {
  COLLECTION.value = 'FormKunjunganVCT'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-form-kunjungan-vct`
  TAB_ACTIVE.value = 'Form Kunjungan VCT'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-form-kunjungan-vct`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const ginekologi = async () => {
  jenisobgyn = 'Ginekologi'
  modalConfirmObgyn.value = false
  COLLECTION.value = 'Assesmen Medis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Medis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const obstetriinterna = async () => {
  jenisinterna = 'Obstetri'
  modalConfirmInterna.value = false
  COLLECTION.value = 'Assesmen Medis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Medis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const interna = async () => {
  jenisinterna = 'Interna'
  modalConfirmInterna.value = false
  COLLECTION.value = 'Assesmen Medis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Medis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const trauma = async () => {
  jenistrauma = 'Trauma'
  modalConfirmTrauma.value = false
  COLLECTION.value = 'Assesmen Medis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Medis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const nontrauma = async () => {
  jenistrauma = 'Non Trauma'
  modalConfirmTrauma.value = false
  COLLECTION.value = 'Assesmen Medis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Medis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}



const onTabCatatanKegiatan = () => {
  COLLECTION.value = 'Catatan Kegiatan Radioterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-catatan-kegiatan-radioterapi`
  TAB_ACTIVE.value = 'Catatan Kegiatan Radioterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-catatan-kegiatan-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabLembarPenyinaran = () => {
  COLLECTION.value = 'Formulir Lembaran Penyinaran Radioterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-lembaran-penyiaran-radioterapi`
  TAB_ACTIVE.value = 'Formulir Lembaran Penyinaran Radioterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-lembaran-penyiaran-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabSkemaPenyinaran = () => {
  COLLECTION.value = 'Formulir Skema Penyinaran Radioterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-skema-penyinaran-radioterapi`
  TAB_ACTIVE.value = 'Formulir Skema Penyinaran Radioterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-skema-penyinaran-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabProtokolKemoterapi = () => {
  COLLECTION.value = 'Protokol Kemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-protokol-kemoterapi`
  TAB_ACTIVE.value = 'Protokol Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-protokol-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabPemberianObatKemoterapiIntraVena = () => {
  COLLECTION.value = 'Pemberian Obat Kemoterapi Intra Vena'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-monitoring-pemberian-obat-kemoterapi-intra-vena-index_tabs`
  TAB_ACTIVE.value = 'Pemberian Obat Kemoterapi Intra Vena'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-monitoring-pemberian-obat-kemoterapi-intra-vena-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabCatatanPemberianObatKemoterapi = () => {
  COLLECTION.value = 'Pemberian Catatan Obat Kemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-catatan-pemberian-obat-kemoterapi`
  TAB_ACTIVE.value = 'Pemberian Catatan Obat Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-catatan-pemberian-obat-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabOnkologiBoardMeeting = () => {
  COLLECTION.value = 'Onkologi Board Meeting'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-on-board-meeting`
  TAB_ACTIVE.value = 'Onkologi Board Meeting'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-on-board-meeting`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabSuratPermintaanPenggunaanObatKhususKemo = () => {
  COLLECTION.value = 'Surat Permintaan Penggunaan Obat Khusus Kemoterapi/Targeted Therapy/Hormonal Therapy/ Immunotherapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-surat-permintaan-penggunaan-obat-khusus-kemoterapi-index_tabs`
  TAB_ACTIVE.value = 'Surat Permintaan Penggunaan Obat Khusus Kemoterapi/Targeted Therapy/Hormonal Therapy/ Immunotherapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-surat-permintaan-penggunaan-obat-khusus-kemoterapi-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabPencampuranSediaanKemo = () => {
  COLLECTION.value = 'Formulir Pencampuran Sediaan Kemoterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-pencampuran-sediaan-kemoterapi`
  TAB_ACTIVE.value = 'Formulir Pencampuran Sediaan Kemoterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-pencampuran-sediaan-kemoterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabFisika = () => {
  COLLECTION.value = 'Formulir Fisika Radioterapi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-formulir-fisika-radioterapi`
  TAB_ACTIVE.value = 'Formulir Fisika Radioterapi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-formulir-fisika-radioterapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabKNInVivo = () => {
  COLLECTION.value = 'Kedokteran Nuklir In Vivo'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-kedokteran-nuklir-invivo`
  TAB_ACTIVE.value = 'Kedokteran Nuklir In Vivo'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-kedokteran-nuklir-invivo`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabKNInVitro = () => {
  COLLECTION.value = 'Kedokteran Nuklir In Vivo'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-kedokteran-nuklir`
  TAB_ACTIVE.value = 'Kedokteran Nuklir In Vivo'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-kedokteran-nuklir`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabKNTerapi = () => {
  COLLECTION.value = 'Kedokteran Nuklir In Vivo'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-kedokteran-nuklir-terapi`
  TAB_ACTIVE.value = 'Kedokteran Nuklir In Vivo'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-kedokteran-nuklir-terapi`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}


const obstetri = async () => {
  jenisobgyn = 'Obstetri'
  modalConfirmObgyn.value = false
  COLLECTION.value = 'Assesmen Medis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Medis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabVital = () => {
  COLLECTION.value = 'Vital Sign'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-vital-sign`
  TAB_ACTIVE.value = 'Vital Sign'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-vital-sign`
  console.log("CLICK FROM CHILD")

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabPREQC2 = () => {
  COLLECTION.value = 'PRE-QC 2'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-pre-qc2`
  TAB_ACTIVE.value = 'PRE-QC 2'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-pre-qc2`
  console.log("CLICK FROM CHILD")

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabMedis = () => {
  if (selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('OBGYN') > -1) {
    modalConfirmObgyn.value = true
    console.log(selectedRegistrasi.value.namaruangan)
  } else if (selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('INTERNA') > -1) {
    modalConfirmInterna.value = true
    console.log(selectedRegistrasi.value.namaruangan)
  } else if ((selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('BEDAH') > -1 || selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('BTKV') > -1 || selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('ORTHOPEDI SPINE') > -1 || selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('NEFROLOGI') > -1) && selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('BEDAH MULUT') == -1 && selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('BEDAH UROLOGI') == -1) {
    modalConfirmTrauma.value = true
    console.log(selectedRegistrasi.value.namaruangan)
  } else if (selectedRegistrasi.value.namaruangan.toUpperCase().indexOf('KEDOKTERAN NUKLIR') > -1) {
    COLLECTION.value = 'AssessmentAwalMedisRawatInap'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-assesmen-medis-kedokteran-nuklir`
    TAB_ACTIVE.value = 'Assesmen Awal Medis Kedokteran Nuklir dan Teranostik Molekuler Rawat Jalan dan Rawat Inap'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-assesmen-medis-kedokteran-nuklir`

    setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
    isRemoveTAB.value = false
  } else {
    COLLECTION.value = 'Assesmen Medis'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
    TAB_ACTIVE.value = 'Assesmen Medis'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`

    setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
    isRemoveTAB.value = false
  }
}

const onTabMedisRI = () => {
  COLLECTION.value = 'AsesmenMedisRawatInap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-tabs-asesmen-medis-rawat-inap-index_tabs`
  TAB_ACTIVE.value = 'Asesmen Medis Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tabs-asesmen-medis-rawat-inap-index_tabs`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
}

const onTabKontrol = () => {
  console.log("MASUK KONTROL");

  COLLECTION.value = 'Surat Kontrol'
  TAB_URL.value = `module-integrasi-sistem-rencana-kontrol`
  TAB_ACTIVE.value = 'Surat Kontrol'
  TAB_ACTIVE_ROUTER.value = `module-integrasi-sistem-rencana-kontrol`
  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false

  confirm.require({
    message: 'Apakah mau mengambil data Riwayat Surat Kontrol sebelumnya?',
    header: 'Riwayat Surat Kontrol',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

      isRemoveTAB.value = false
    },
    reject: () => {
      setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

      isRemoveTAB.value = false
    },
  })


}

const onTabResep = () => {
  COLLECTION.value = 'Order Resep'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-order-resep`
  TAB_ACTIVE.value = 'Order Resep'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-order-resep`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabRadiasi = () => {
  COLLECTION.value = 'PerencaanRadiasiEksterna'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-perencanaan-radiasi-eksterna`
  TAB_ACTIVE.value = 'Perencanaan Radiasi Eksterna'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-perencanaan-radiasi-eksterna`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabClinicalDrawing = () => {
  COLLECTION.value = 'Clinical Drawing'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-clinical-drawing`
  TAB_ACTIVE.value = 'Clinical Drawing'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-clinical-drawing`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onTabKeluar = () => {
  COLLECTION.value = 'Ringkasan Keluar'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-ringkasan-keluar`
  TAB_ACTIVE.value = 'Ringkasan Keluar'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-ringkasan-keluar`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const ringkasanPulangRI = () => {
  COLLECTION.value = 'RingkasanPulang'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-ringkasan-pulang-rawat-inap`
  TAB_ACTIVE.value = 'Ringkasan Pulang'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-ringkasan-pulang-rawat-inap`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const ringkasanMasukKeluarRI = () => {
  COLLECTION.value = 'RingkasanMasukDanKeluarRawatInap'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-ringkasan-masuk-dan-keluar-rawat-inap`
  TAB_ACTIVE.value = 'Ringkasan Masuk dan Keluar Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-ringkasan-masuk-dan-keluar-rawat-inap`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const onCPPT = (e: any) => {
  COLLECTION.value = 'CatatanPerkembanganPasienTerintegrasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-cppt`
  TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi Rawat Inap'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-cppt`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const alergiObatPasien = ref('');

const fetchDataAndProcess = async (e: any) => {
    // Fetch AsesmenAwalMedisGawatDarurat
    const aka = await useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=isalergi,CBAlergiObat,TBAlergiObat`);

    if (aka) {
      if (aka.isalergi) {
        if(aka.TBAlergiObat) {
          alergiObatPasien.value = `Alergi Obat Pasien : ${aka.TBAlergiObat || ''}`;
        } else {
          alergiObatPasien.value = null;
        }
      } else {
        alergiObatPasien.value = null;
      }
    }
}

const PANGGILPASIEN = async (e: any) => {
  e.loading = true

  sendAntrol(e.norec_pd)
  await socket.emit('call-antrian-poli', {
    namapasien: e.namapasien,
    namaruangan: e.namaruangan,
    noantri: e.noantrian,
    nocm: e.nocm,
    norec: e.norec_apd,
  });

  if (e.status == null || e.status == 'Belum Dipanggil') {
    await useApi().post(
      `/dashboard/rawat-jalan/panggil`,
      {
        'norec_apd': e.norec_apd,
        'norec_pd': e.norec_pd,
      }
    ).then(async (response: any) => {
      e.status = response.status
      await loadListPasien()
      e.loading = false
    }).catch((e: any) => {
      e.loading = false
    })

  }
  await sleep(1000)
  e.loading = false
}

const hasilLab = async () => {

  modalHasilLab.value = true
}

const hasilRad = async () => {

  modalHasilRad.value = true
}
const berkasPasien = async () => {
  modalBerkas.value = true
}
const saveCatatanDokter = async () => {
  if (!item.catatanDokter) {
    H.alert('warning', "Catatan Dokter Harus Disis!");
    return;
  }
  let json = {
    'tanggal': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
    'catatan': item.catatanDokter,
    'nocmfk': ID_PASIEN,
    'noregistrasifk': NOREC_PD
  }
  isLoading.value = true
  useApi().post(
    `/dokter/save-catatan`, json).then((response: any) => {
      isLoading.value = false
      pasien.value.catatan = response.catatan
      getCatatanDokter()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const getCatatanDokter = async () => {
  isLoading.value = true;
  await useApi()
    .get(
      `/dokter/get-catatan?nocmfk=${route.query.nocmfk}`
    ).then((response: any) => {
      dokterCatatan.value = response
      isLoading.value = false
    })
}
const listHistoriKemo: any = ref([])
const getHistoriKemo = async () => {
  isLoading.value = true;
  await useApi()
    .get(
      `/dokter/get-kemo?nocmfk=${route.query.nocmfk}`
    ).then((response: any) => {
      listHistoriKemo.value = response
      item.value = response
      isLoading.value = false
    })
}

const lihatHasil = (dataItem: any) => {

  if (dataItem.order_complete == 0) {
    H.alert('warning', 'Hasil belum ada')
  } else {
    router.push({
      name: 'module-radiologi-hasil-pacs',
      query: {
        url: dataItem.url_pacs_hasil,
      },
    })
  }
  return
  if (dataItem.radiologiid === null || dataItem.radiologiid === '') {
    H.alert('warning', 'Hasil belum ada')
  } else {

    let viewer = null
    let patienIdMr = dataItem.radiologiid.replace('null', '1')
    dataItem.isLoading = true
    useApi().postNoMessage(`/general/api-tools`, {
      'method': 'get',
      'url': import.meta.env.VITE_URL_PACS_ENGINE + '/dcm4chee-arc/aets/TRANSMEDIC/rs/studies?limit=1&includefield=all&offset=0&PatientID=' + patienIdMr,
      'headers': {}
    }).then((response: any) => {
      dataItem.isLoading = false
      if (response.response == null) {
        H.alert('warning', 'Hasil foto belum dikirim ke PACS')
      } else {
        let data = response.response
        viewer = data[0]["0020000D"].Value[0]
        window.open(import.meta.env.VITE_URL_PACS_VIEWER
          + "/viewer/" + dataItem.objectruangantujuanfk
          + "/" + dataItem.norec_pp
          + "/" + props.norec_pd
          + "/" + dataItem.noorder + "/" + viewer, "pacs");
      }
    })

  }

}

const cetakExpertise = (e: any) => {
  H.printBlade("radiologi/cetak-ekspertise?echo=true&norec=" + e.norec_exper);
}

const setDefaultMenu = async () => {
  showMenu({
    "name": "Catatan Perkembangan Pasien Terintegrasi",
    "icon": "pi pi-fw pi-file",
    "url_form": "cppt-rev",
    "items": {
      "id": 310103,
      "label": "Catatan Perkembangan Pasien Terintegrasi",
      "headfk": 210239,
      "nourut": 305,
      "url_form": "cppt-rev",
      "collection": "CatatanPerkembanganPasienTerintegrasi"
    }
  })
}

const sendAntrol = async (norec_pd: any) => {
  const jsont4 = {
    "noregistrasifk": norec_pd,
    "taskid": 4,
    "waktu": new Date().getTime(),
  }
  await useApi()
    .postNoMessage(`/bridging/antrol/sendTaskId`, jsont4)
    .then(async (response: any) => {
      const jsont5 = {
        "noregistrasifk": norec_pd,
        "taskid": 5,
        "waktu": new Date().getTime(),
      }
      await useApi()
        .postNoMessage(`/bridging/antrol/sendTaskId`, jsont5)
        .then((response: any) => { })
    })
}
const detailRegistrasi = async () => {

  router.push({
    name: 'module-registrasi-detail-registrasi',
    query: {
      noregistrasi: selectedRegistrasi.value.noregistrasi,
      norec_pd: route.query.norec_pd,
      norec_apd: route.query.norec_apd,
    },
  })
}

// const catatanPasien = async () => {
//   item.catatanDokter = "";
//   await getCatatanDokter();
//   modalCatatan.value = true
// }


const getIntruksiDokter = (showB: bool = true) => {
  // console.log("User Login", userLogin);

  listIntruksi.value = [];
  // isLoading.value = true;
  useApi().get(`dashboard/get-intruksi-cppt-dokter?norec_pd=${route.query.norec_pd}`).then((res) => {
    if (res.length > 0) {
      listIntruksi.value = res;
      // isLoading.value = false;
    }
    console.log("RESPONSE INTRUKSI", res)
  });
}

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/emr/profile-pasien';

.riwayat-reg-mobile {
  display: none;
}

.classActiveNavEMR {
  display: none;
}

.p-tooltip .p-tooltip-text {
  background: #495057;
  color: #fff;
  padding: 0.75rem 0.75rem;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  border-radius: 6px;
}

a {
  color: #6c757d;
}

@media (max-width: 1144px) {
  .d-none {
    display: none;
  }

  .is-tablet-9 {
    width: 75% !important;
  }

  .is-offset-4-tablet {
    width: 75% !important;
    margin-left: 0 !important;
  }

  .riwayat-reg-mobile {
    display: unset;
  }

  .riwayat-reg-nomobile {
    display: none;
  }

  .classActiveNavEMR {
    display: unset;
  }

  .active-nav-emr-mobile {
    display: none;
  }

  .is-tablet-12 {
    width: 100% !important;
  }
}

.table-pri {
  width: 100% !important;
  border-collapse: collapse !important;
}

.tombol {
  float: left;
  margin: 2px;
  width: 100%;
  border-radius: 10px;
  opacity: 0.8;
  background-color: #3b9cf7;
  color: white;
}

.table-pri,
.tr-pri,
.th-pri,
.td-pri {
  border: 1.6px solid black !important;
}

.th-pri,
.td-pri {
  padding: 8px !important;
}

.space-between {

  display: flex;
  justify-content: space-between;
}

.text-diagnosis {
  max-width: 100%;
  white-space: pre-wrap;
  word-break: keep-all;
  text-overflow: ellipsis;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
}

.panjangaaaaaaa {
  height: 130px
}

.panjang-240 {
  height: 260px;
  overflow-x: hidden;
  overflow-y: auto;
}

.list-view-v3 {
  .list-view-item {
    @include vuero-r-card;
    cursor: pointer;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      >span {
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        color: var(--light-text);

        >span {
          padding: 0.5rem;
        }
      }

    }
  }
}

.form-layout.is-stacked {
  max-width: none;
  padding: 0 10px 0 10px;
}

.form-layout.is-separate {
  max-width: none;
}

.form-layout .form-outer {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 20px;
  background-color: var(--white);
  border-radius: var(--radius-large);
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
  padding: 0;
}

.hr-dashboard .block-header {
  display: none;
}

.block-green {
  background: var(--primary);
  font-family: var(--font);
  box-shadow: var(--primary-box-shadow);
  border-radius: 16px;
  padding: 25px;
  display: none;
}
</style>
