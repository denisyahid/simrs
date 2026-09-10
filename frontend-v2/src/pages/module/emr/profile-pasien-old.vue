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
                  <div class="column is-5 is-offset-4 is-offset-4-tablet">
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
                    <VButton rounded color="info" class="is-pulled-right mr-2" icon="feather:list" raised bold style="background:var(--yellow)"
                      @click="detailRegistrasi">
                      Detail Registrasi
                    </VButton>
  
  
                    <VButton rounded color="danger" class="is-pulled-right mr-2" icon="fas fa-user-lock" raised bold
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
  
  
                    <VButton rounded color="success" class="is-pulled-right mr-2" icon="feather:printer" raised bold
                      v-if="dataSKDP.url_form" @click="showMenu(dataSKDP)">
                      <!-- {{ dataSKDP }} -->
                      SKDP
                    </VButton>
                    <VButton rounded color="success" class="is-pulled-right mr-2" icon="feather:external-link" raised bold
                      @click="iCare" :loading="isLoadingIcare" v-if="selectedRegistrasi.kelompokpasien == 'BPJS'">
                      I-CARE
                    </VButton>
                    <!-- <VButton rounded color="danger" class="is-pulled-right mr-2" icon="feather:printer" raised bold
                      @click="cetakSKD" :loading="isLoadingSKD">
                      Cetak SKD
                    </VButton> -->
                    <VButton rounded outlined color="primary" class="is-pulled-right mr-2"
                      v-tooltip-prime.top="'TOTAL BILLING '" icon="fas fa-calculator" raised bold>
                      {{ selectedRegistrasi.billing ? H.formatRp(selectedRegistrasi.billing, 'Rp. ') : 'Rp.0' }}
                    </VButton>
                    <VButtonImg rounded outlined :color="colorPLAFON" class="is-pulled-right mr-2"
                      icon="/images/icons/files/bpjs-no-bg.svg" raised :loading="isFlafon" @click="hitungBiayaSementara"
                      bold v-tooltip-prime.top="'PLAFON BPJS'">
                      {{
                        selectedRegistrasi.inacbg_totalgrouper ?
                          H.formatRp(selectedRegistrasi.inacbg_totalgrouper, 'Rp. ') :
                          'Rp.0'
                      }}
                    </VButtonImg>
                  </div>
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-3 " v-if="isLoadingPasien">
                        <PlaceloadHeader />
                      </div>
                      <div class="column is-3" v-if="!isLoadingPasien">
                        <HeadPasienEmr @open-modal="catatanPasien" :pasien="pasien" :isaktif="PASIEN_AKTIF" />
                      </div>
                      <div class="column is-5 "
                        :class="[classActiveNavLISTPASIEN != '' ? classActiveNavLISTPASIEN : 'is-tablet-9']">
                        <TDetailRegistrasiRev :selectedRegistrasi="selectedRegistrasi" :isaktif="PASIEN_AKTIF" />
                      </div>
                      <div class="column is-4 "
                        :class="[classActiveNavLISTPASIEN != '' ? classActiveNavLISTPASIEN : 'd-none']">
                        <TListPasien @refreshList="loadListPasien()" @PILIHPASIEN="PILIHPASIEN" @LOADPASIEN="LOADPASIEN"
                          :listPasienRJ="listPasienRJ" :isaktif="PASIEN_AKTIF" :loadingList="loadingList"
                          :selectedRegistrasi="selectedRegistrasi" @PANGGILPASIEN="PANGGILPASIEN" />
                      </div>
                    </div>
                  </div>
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column " :class="hideRiwayat == true ? 'is-hidden' : 'is-3'">
                        <div class="account-box is-navigation">
                          <div class="account-menu">
                            <VCard class="h-500" style="padding: 10px 20px 10px 20px;">
  
                              <VCardHead title="Riwayat Registrasi" class="no-border p-1 riwayat-reg-nomobile">
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
                                      <span class="span-text-left-bar" style="font-weight:bold;font-size:0.85rem">{{itemsHEAD.namadepartemen }}</span>
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
                                          <VCard class="is-clickable is-grey" @click="selectedRiwayat(items)" :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis' : '' " style="padding:10px">
                                            <i aria-hidden="true" class="lnir lnir-medicine mr-2"></i>
                                            <span class="span-text-left-bar">{{H.formatDateIndo(items.tglregistrasi)}}</span>
                                            <span v-if="selectedRegistrasi.norec == items.norec"
                                              class="is-pulled-right ml-2 mt-3" :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis mt-3' : ''" style="margin-top: 2.5rem !important;">
                                              <i aria-hidden="true" class="fas fa-arrow-right"></i>
                                            </span>
                                            <br>
                                            <i aria-hidden="true" class="lnir lnir-house-alt mr-2"></i>
                                            <span class="span-text-left-bar">{{ items.namaruangan }}</span>
                                            <br>
                                            <i aria-hidden="true" class="fas fa-user-md mr-2"
                                              :style="items.dokter ? '' : 'color: var(--light-text);'"></i>
                                            <span class="span-text-left-bar"
                                              :style="items.dokter ? '' : 'color: var(--light-text);'">{{ items.dokter ? items.dokter : '-' }}</span>
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
                          <div class="column is-12">
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
                              <VIconButton @click="scrollLeft" icon="fas fa-angle-left" class="mr-1 mt-2" raised bold></VIconButton>
                              <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
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
                                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                      tabindex="0" @click="onTabResep()"><span
                                        class="p-menuitem-icon fas fa-book-medical"></span><span
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
                                    :class="[TAB_ITEMS == 'Surat Kontrol' || TAB_DEFAULT == 'Surat Kontrol' ? 'active' : '']"
                                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                      tabindex="0" @click="onTabKontrol()"><span
                                        class="p-menuitem-icon fas fa-book-medical"></span><span
                                        class="p-menuitem-text">Kontrol Pasien</span></a>
                                  </li>
                                </ul>
                                
                                <!-- <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                  v-if="selectedRegistrasi.objectdepartemenfk == 18">
                                  <li class="p-tabmenuitem"
                                    :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                      tabindex="0" @click="onTab(items)"><span
                                        class="p-menuitem-icon fas fa-book-medical"></span><span
                                        class="p-menuitem-text">CPPT</span></a>
                                  </li>
                                </ul>
                                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                                  v-else="selectedRegistrasi.objectdepartemenfk == 24">
                                  <li class="p-tabmenuitem"
                                    :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard"
                                      tabindex="0" @click="onCPPT(items)"><span
                                        class="p-menuitem-icon fas fa-book-medical"></span><span
                                        class="p-menuitem-text">CPPT</span></a>
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
                              </div>
                              <VIconButton @click="scrollRight" icon="fas fa-angle-right" class="ml-1 mt-2" raised bold></VIconButton>
                            </div>
                          </div>
                        </div>
                        <div class="columns is-multiline" v-if="TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard'">
                          <div class="column "
                            :class="[hideRiwayat == true ? 'is-12' : 'is-9', classActiveNavEMR != '' ? 'is-tablet-12' : '']">
                            <TEmrDetail :registrasi="selectedRegistrasi" :pasien="pasien" :alamat="alamat"
                              :skdp="dataSKDP" :riwayat="riwayatPemeriksaan" :hideRiwayat="hideRiwayat"
                              @showRiwayat="showRiwayat()" @reloadRiwayat="reloadRiwayat()"
                              @billingPasien="billingPasien()" @hiddenRiwayat="hiddenRiwayat()"
                              :isLoadingRiwayat="isLoadingRiwayat" :isPasienAktif="PASIEN_AKTIF"
                              @showMenuEMR="showMenuEMR()" @editEMR="editEMR" @hapusEMR="hapusEMR" @cetakEMR="cetakEMR"
                              @openEMR="openEMR" @showNavStatus="showNavStatus">
                            </TEmrDetail>
                          </div>
                          <div class="column  " :class="[hideRiwayat == true ? 'is-hidden' : 'is-3', classActiveNavEMR]">
                            <TStatusEmr :riwayat="riwayatPemeriksaan" @openEMR="openEMR"></TStatusEmr>
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
                    <div class="columns is-multiline" v-if="listMenuEMR.length == 0">
                      <div class="column is-4" v-for="index of 30">
                        <VPlaceloadText :lines="1" />
                      </div>
                    </div>
                    <TNavigasiEmr v-if="listMenuEMR.length" :menuEMR="listMenuEMR" :filterMenu="filterMenu"
                      @accessMenu="accessMenuEMR" :totalMenu="totalMenu"></TNavigasiEmr>
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
                      <VField label="Catatan Dokter">
                        <VTextarea rows="8" placeholder="Catatn Dokter..." v-model="item.catatanDokter"></VTextarea>
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
  
      <Dialog v-model:visible="modalBerkas" modal header="Berkas" :style="{ width: '60vw' }">
        <Berkas :pasien="pasien" :registrasi="selectedRegistrasi" />
      </Dialog>
  
  
  
    </section>
  </template>
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted, watchEffect, inject, provide } from 'vue'
  import { useRoute, useRouter, onBeforeRouteUpdate, onBeforeRouteLeave, RouteLocationNormalized, Router, RouterView, } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import TabMenu from 'primevue/tabmenu';
  import TActionLeftBar from './profile-pasien/t-action-left-bar.vue'
  import TDetailRegistrasiRev from './profile-pasien/t-detail-registrasi-rev.vue'
  import TEmrDetail from './profile-pasien/t-emr-detail.vue'
  // import TEmrAsesmen from './profile-pasien/t-emr-asesmen.vue'
  import TStatusEmr from './profile-pasien/t-status-emr.vue'
  import TListPasien from './profile-pasien/t-list-pasien.vue'
  import TNavigasiEmr from './profile-pasien/t-navigasi-emr.vue'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import Dialog from 'primevue/dialog';
  import { listActionEMR } from '/@src/data/module/hard/action_emr'
  import FloatingButton from "./float-button.vue";
  import { useUserSession } from '/@src/stores/userSession'
  import sleep from '/@src/utils/sleep'
  import { state, socket } from "/@src/socket.js";
  import Berkas from './profile-pasien/page-emr/berkas-pasien.vue'
  import { useConfirm } from "primevue/useconfirm"
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
  const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
  const isLoadingIcare: any = ref(false)
  const info: any = ref({})
  const isLoadingPanggil: any = ref(false)
  const isLoadingRegis: any = ref(false)
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
    filterTgl: reactive({
      start: new Date(new Date().setDate(new Date().getDate() - 730)),
      end: new Date(),
    }),
  })
  
  
  const pasienByID = async (id: any) => {
    if (routerChangeTAB.value) return
  
    // Clear cache tab EMR All
    localStorage.removeItem('cacheTAB_')
  
    isLoadingPasien.value = true
    isLoadingFilter.value = true
    let dari = H.formatDate(item.filterTgl.start, 'YYYY-MM-DD')
    let sampai = H.formatDate(item.filterTgl.end, 'YYYY-MM-DD')
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    pasien.value = {}
    alamat.value = {}
    selectedRegistrasi.value = {}
    offset = (parseInt(offset) - 1) * limit
    totalData.value = 0
    let norec_apd = route.query.norec_apd ? `&norec_apd=${route.query.norec_apd}` : ''
    await useApi()
      .get(
        `/emr/header-pasien?nocmfk=${id}&norec_pd=${route.query.norec_pd}${norec_apd}&dari=${dari}&sampai=${sampai}&limit=${limit}&offset=${offset}`
      )
      .then(async (response: any) => {
        pasien.value = response.pasien
        alamat.value = response.alamat
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
  
    if (kelompokUser == 'dokter') {
      TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi'
      TAB_ITEMS == 'CPPT'
      TAB_DEFAULT == 'CPPT'
      onTab()
    }
    await detailPelayanan(items.norec)
  
  }
  const selectedRiwayat = (e: any) => {
    TAB_ITEMS.value = []
    TAB_ACTIVE.value = 'Dashboard'
    selectNoreg(e)
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
  
    await useApi()
      .get(`/emr/detail-pelayanan?norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`)
      .then(async (response: any) => {
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
  
        response.statuspasien.alergi = response.statuspasien.alergi ? response.statuspasien.alergi : 'Tidak ada'
        dataAlergi.value = response.statuspasien.alergi
        response.statuspasien.lamarawat = response.statuspasien.lamarawat ? response.statuspasien.lamarawat : '-'
        response.statuspasien.tglpulang = response.statuspasien.tglpulang ? H.formatDateIndo(response.statuspasien.tglpulang) : '-'
        // response.statuspasien.statuspulang = response.statuspasien.statuspulang ? response.statuspasien.statuspulang : '-'
        response.statuspasien.kondisipasien = response.statuspasien.kondisipasien ? response.statuspasien.kondisipasien : '-'
  
        riwayatPemeriksaan.value.LIST_VITAL = response.vitalSign
        riwayatPemeriksaan.value.LIST_TINDAKAN = response.tindakan
        riwayatPemeriksaan.value.LIST_RESEP = response.resep
        riwayatPemeriksaan.value.LIST_DIAGNOSIS = response.diagnosis
        riwayatPemeriksaan.value.LIST_RAD = setHasilRad(response.radiologi)
        riwayatPemeriksaan.value.LIST_EMR = response.emr
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
        await useApi().get(
          `/emr/get-perjanjian?nocm=${pasien.value.nocm}&tanggal=${new Date()}`).then((response: any) => {
            isLoading.value = false
            if (response.data.length > 0) {
              dataSKDP.value = response
            }
          })
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
    detailPelayanan(selectedRegistrasi.value.norec)
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
    if (kelompokUser == 'dokter') {
      params = `&dokid=${userLogin.pegawai.id}`
    }
    await useApi()
      .get(`/emr/list-pasien-rj?dari=${H.formatDate(new Date(), 'YYYY-MM-DD')}
      &sampai=${H.formatDate(new Date(), 'YYYY-MM-DD')}
      &norec_pd=${selectedRegistrasi.value.norec}
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
  
  const setRoutingEMR = (form: any, norec_emr: any) => {
  
    let query: any = {}
    let params: any = {}
    if (norec_emr != '') {
      query = {
        nocmfk: selectedRegistrasi.value.nocmfk,
        norec_pasien_daftar: selectedRegistrasi.value.norec_pd,
        norec_pd: selectedRegistrasi.value.norec_pd,
        norec_apd: selectedRegistrasi.value.norec_apd,
        norec_emr: norec_emr,
      }
    } else {
      query = {
        nocmfk: selectedRegistrasi.value.nocmfk,
        norec_pasien_daftar: selectedRegistrasi.value.norec_pd,
        norec_pd: selectedRegistrasi.value.norec_pd,
        norec_apd: selectedRegistrasi.value.norec_apd,
      }
    }
    if(form.indexOf('index_tab') > -1){
      params = {
        index_tabs: 1
      }
    }
    router.push({
      name: form,
      query: query,
      params: params
    })
  }
  /*
  
  
  */
  const onTabClick = (e: any) => {
    if (isRemoveTAB.value) return
    COLLECTION.value = e.items ? e.items.collection : e.collection
    TAB_URL.value = e.url_form
    TAB_ACTIVE.value = e.label
    TAB_ACTIVE_ROUTER.value = e.url_form ? `module-emr-profile-pasien-page-emr-${e.url_form}` : TAB_ROUTER_DEFAULT.value
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
      TAB_ACTIVE_ROUTER.value = TAB_ITEMS.value[TAB_ITEMS.value.length - 1].url_form ? `module-emr-profile-pasien-page-emr-${TAB_ITEMS.value[TAB_ITEMS.value.length - 1].url_form}` : TAB_ROUTER_DEFAULT.value
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
    isRemoveTAB.value = false
    // let norec_pd = '', nocmfk = ''
    // if (selectedRegistrasi.value.norec_pd == undefined) {
    //   norec_pd = NOREC_PD
    //   nocmfk = ID_PASIEN
    // } else if (selectedRegistrasi.value.norec_pd != NOREC_PD) {
    //   return
    // }
    if (e.name != 'EMR') {
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
      TAB_ACTIVE_ROUTER.value = e.url_form ? `module-emr-profile-pasien-page-emr-${e.url_form}` : TAB_ROUTER_DEFAULT.value
  
      TAB_ITEMS.value.push({ label: e.name, icon: 'pi pi-fw pi-file', url_form: e.url_form, items: e.items })
      setCacheEMRWhileReload()
      setRoutingEMR(TAB_ACTIVE_ROUTER.value, e.norec_emr ? e.norec_emr : '')
  
      // }
  
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
    filterMenu.value = ''
    classMODALMENU.value = 'medium'
  }
  const setDashboard = () => {
    TAB_ACTIVE.value = 'Dashboard'
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
    showMenu({
      'name': e.namaemr,
      'url_form': e.url_form,
      'norec_emr': e.emrpasienfk,
      'collection': e.table
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
    H.printBlade(`emr/cetak/${e.table}?emrpasienfk=${e.emrpasienfk}&pdf=false`)
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
      listMenu.value = response
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
    console.log('ini CPPT')
    COLLECTION.value = 'CatatanPerkembanganPasienTerintegrasi'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-cppt-rev`
    TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-cppt-rev`
  
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
  
  const onTabLaboratorium = () => {
    COLLECTION.value = 'Laboratorium'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-order-laboratorium`
    TAB_ACTIVE.value = 'Laboratorium'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-order-laboratorium`
  
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
  
  const onTabTindakan = () => {
    COLLECTION.value = 'Tindakan'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-tindakan`
    TAB_ACTIVE.value = 'Tindakan'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-tindakan`
  
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
  
  const onTabMedis = () => {
    COLLECTION.value = 'Assesmen Medis'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
    TAB_ACTIVE.value = 'Assesmen Medis'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
  
    setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  
    isRemoveTAB.value = false
  }
  
  const onTabKontrol = () => {
    COLLECTION.value = 'Surat Kontrol'
    TAB_URL.value = `module-integrasi-sistem-rencana-kontrol`
    TAB_ACTIVE.value = 'Surat Kontrol'
    TAB_ACTIVE_ROUTER.value = `module-integrasi-sistem-rencana-kontrol`
  
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
  
  const onCPPT = (e: any) => {
    COLLECTION.value = 'CatatanPerkembanganPasienTerintegrasi'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-cppt`
    TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi Rawat Inap'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-cppt`
  
    setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
    
    isRemoveTAB.value = false
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
          'norec_pd' :e.norec_pd,
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
  </style>
  