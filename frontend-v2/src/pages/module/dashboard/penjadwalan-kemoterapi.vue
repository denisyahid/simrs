<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns">
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="illustration-header-2 large-screen">
              <div class="header-image">
                <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                  style="max-width:75%; margin-left: 2rem; margin-top: 1rem;" />
              </div>
              <div class="header-meta">
                <h3 style="color:white">
                  <i class="fas fa-home" aria-hidden="true"></i>
                  Dashboard Penjadwalan Kemoterapi
                </h3>
                <p>
                  Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                </p>
                <VControl>
                  <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" class="f-text"
                    placeholder="Filter ruangan" :searchable="true" autocomplete="off"
                    @select="changeRuang(item.filterRuangan)" />
                </VControl>
                <div class="columns is-multiline">
                  <div class="column is-12 pt-0">
                    <VTag @click="showModalFilter()" color="danger" rounded elevated
                      style="position: relative; bottom: -1.5rem; cursor:pointer; height: 3em">
                      {{
                        H.formatDateToLocalString(item.filterTgl.start) ==
                          H.formatDateToLocalString(item.filterTgl.end) ?
                          H.formatDateToLocalString(item.filterTgl.start) :
                          H.formatDateToLocalString(item.filterTgl.start) + ' - ' +
                          (item.filterTgl.end ?
                            H.formatDateToLocalString(item.filterTgl.end) : '')
                      }}
                      <i class="fas fa-filter ml-3" aria-hidden="true"></i>
                    </VTag>
                  </div>
                  <div class="column is-6 pb-0">
                    <VField grouped>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="item.jenisTgl" label="Tgl Order" color="info" true-value="tglorder" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6 pb-0">
                    <VField grouped>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="item.jenisTgl" label="Tgl Penjadwalan" color="info"
                          true-value="tglpenjadwalan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <Badge :value="dataOrder.length" v-if="dataOrder.length > 0" severity="danger"
          style="z-index: 5;top: 22px;position: relative;" />
        <div class="column is-12" style="margin-top: 1rem;">
          <VTabs @update:selected="changeMenu($event)" slider selected="Pasien" :tabs="[
            { label: 'Order Penjadwalan', value: 'Pasien' },
            // { label: 'List Penjadwalan Kemoterapi', value: 'Laporan' },
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
                    <VButton raised class="search-button" @click="fetchDataOrder(order)" :loading="isLoading"> Cari Data
                    </VButton>
                  </div>
                </Vcard>
                <VCard class="text-center pt-0 pb-0 mt-0">
                  <!-- <VRadio v-model="order" value="pending" label="Pending" name="outlined_radio" color="danger" /> -->
                  <VRadio v-model="order" value="hematologi" label="Belum Verifikasi Hematologi" name="outlined_radio"
                    color="danger" />
                  <VRadio v-model="order" value="kemoterapi" label="Belum Verifikasi Kemoterapi" name="outlined_radio"
                    color="warning" />
                  <VRadio v-model="order" value="farmasi" label="Belum Verifikasi Farmasi" name="outlined_radio"
                    color="info" />
                  <VRadio v-model="order" value="terverifikasi" label="Terverifikasi" name="outlined_radio"
                    color="success" />
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
                    <div v-for="(items, i) in dataOrder" :key="items.id" class="list-view-item">
                      <div class="list-view-item-inner">
                        <VAvatar size="small" style="left: 8px;top: 4px;" :color="listColor[i]"
                          picture="/images/avatars/svg/pasien.svg" />
                        <div class="meta-left">
                          <h3>
                            <i :class="item.objectjeniskelaminfk == 1 ? 'fas fa-mars' : 'fas fa-venus'"
                              aria-hidden="true"
                              :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                            &nbsp;<span>{{ items.namapasien }}</span>
                            |
                            <span>{{ items.noregistrasi }}</span>
                            |
                            <VTag v-if="items.kelompokpasien != null" class="mt-3 ml-2" :label="items.kelompokpasien"
                              :color="items.kelompokpasien == 'BPJS' ? 'green' : 'orange'" rounded /> |
                            <i class="bulet fas fa-circle"></i>
                            {{ items.namakelas }}
                          </h3>
                          <span>
                            <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                            <span>{{ items.ruangantujuan }}</span>
                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                            <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                            <span>Tgl Reg. {{ items.tglregistrasi }}</span>
                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                            <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                            <span>{{ items.nocm }}</span>
                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                            <i aria-hidden="true" class="iconify" data-icon="feather:credit-card"></i>
                            <span>{{ item.nobpjs ?? '-' }}</span>
                            <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                            <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                            <span>{{ items.noidentitas }}</span>
                          </span>
                          <h4><span><b>Tgl. Order :</b> {{ items.tglorder }}</span></h4>
                          <h4><span><b>Tgl. Kemoterapi</b> : {{ items.tglpenjadwalan }}</span></h4>
                          <VTag color="danger" rounded v-if="!items.verif_hematologi">
                            Belum Diverifikasi Oleh Hematologi
                          </VTag>
                          <VTag color="primary" rounded v-else>
                            Diverifikasi Oleh Hematologi
                          </VTag>
                          <VTag color="danger" rounded v-if="!items.verif_kemoterapi" class="ml-1">
                            Belum Diverifikasi Oleh Kemoterapi
                          </VTag>
                          <VTag color="primary" rounded v-else class="ml-1">
                            Diverifikasi Oleh Kemoterapi
                          </VTag>
                          <VTag color="danger" rounded v-if="!items.verif_farmasi" class="ml-1">
                            Belum Diverifikasi Oleh Farmasi
                          </VTag>
                          <VTag color="primary" rounded v-else class="ml-1">
                            Diverifikasi Oleh Farmasi
                          </VTag>
                        </div>
                        <div class="meta-right">
                          <VIconButton v-if="!items.verif_kemoterapi || !items.verif_farmasi || !items.verif_hematologi"
                            v-tooltip.bottom="'Verifikasi'" color="primary" circle icon="pi pi-arrow-right"
                            @click="orderVerify(items)" :loading="items.loading" style="margin-right: 15px;" />
                          <VIconButton v-else v-tooltip.bottom="'Preview'" color="blue" circle
                            icon="fas fa-file-medical-alt" @click="orderVerify(items, 'preview')"
                            :loading="items.loading" style="margin-right: 15px;" />
                          <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                            @click="emr(items)" v-tooltip.bottom="'EMR'" style="margin-right: 15px;">
                          </VIconButton>
                        </div>
                      </div>
                    </div>
                  </TransitionGroup>
                </div>
              </div>
              </p>
              <p v-else-if="activeValue === 'Laporan'">
              <div class="search-menu mb-2">
                <div class="search-location">
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
                </div>
                <VButton raised class="search-button" @click="fetchLaporan()" :loading="isLoading"> Cari
                  Data
                </VButton>
              </div>
              <VCard radius="rounded">
                <div class="user-grid user-grid-v2">
                  <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()">
                    Export to
                    Excel </VButton>
                  <DataTable :value="dataLaporan" class="p-datatable-sm" :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    scrollable scrollHeight="flex" tableStyle="min-width: 100rem" breakpoint="960px" sortMode="multiple"
                    :loading="isLoading"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} showGridlines ">
                    <Column field="no" header="No"></Column>
                    <Column field="jamoperasi" header="Jam Operasi"></Column>
                    <Column field="kamaroperasi" header="Ruang OK"></Column>
                    <Column field="namapasien" header="Nama Pasien" :sortable="true"></Column>
                    <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true"></Column>
                    <Column field="umur_pasien" header="Umur" :sortable="true"></Column>
                    <Column field="nocm" header="NO RM" :sortable="true"></Column>
                    <Column field="tgllahir" header="Tanggal Lahir" :sortable="true"></Column>
                    <Column field="diagnosis" header="Diagnosa Pre OP" :sortable="true"></Column>
                    <Column field="namaproduk" header="Tindakan"></Column>
                    <Column field="dokterpemeriksa" header="Dokter Pemeriksa"></Column>
                    <Column field="dokteranestesi" header="Dokter Anestesi"></Column>
                    <Column field="estimasi" header="Estimasi Jam"></Column>
                    <Column field="kelompokpasien" header="Cara Bayar"></Column>
                    <Column field="tgloperasi" header="Tanggal Operasi"></Column>
                    <Column field="asalruangan" header="Asal Ruangan"></Column>
                    <Column field="tinggibadan" header="Tinggi Badan"></Column>
                    <Column field="beratbadan" header="Berat Badan"></Column>
                    <Column field="riwayatswab" header="Riwayat Swab"></Column>
                    <Column field="userpenerima" header="Petugas Verifikator"></Column>
                  </DataTable>
                </div>
              </VCard>
              </p>
            </template>
          </VTabs>
        </div>
      </div>
      <!-- <div class="column is-4">
        <VCard>
          <div class="column is-12">
            <span style="font-weight: bold; font-size: 12px; font-family: var(--font-alt);">Jadwal Operasi
            </span>
          </div>
          <div class="tile-grid tile-grid-v2">
            <div class="columns is-multiline" v-if="dataOperasi.loading">
              <div v-for="key in 1" :key="key" class="column is-12">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VPlaceloadAvatar size="big" centered class="mb-2" />
                    <VPlaceloadText class="mb-4" width="80%" :lines="3" last-line-width="60%" centered />
                  </div>
                </div>
              </div>
            </div>
            <VPlaceholderPage v-else-if="dataOperasi.length === 0" :title="H.assets().notFound"
              :subtitle="H.assets().notFoundSubtitle" larger>
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>

            <TransitionGroup name="list" tag="div" class="columns is-multiline" v-else-if="dataOperasi.length > 0">
              <div class="column is-multiline" style="max-height: 300px; min-height: 50px;overflow: auto;">
                <div v-for="item in dataOperasi" :key="item.id" class="column is-12">
                  <div class="tile-grid-item">
                    <div class="tile-grid-item-inner">
                      <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                      <div class="meta">
                        <span class="dark-inverted">{{ item.namapasien }} - {{ item.asalruangan }}</span>
                        <span class="dark-inverted">{{ item.namaproduk }}</span>
                        <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded elevated> {{
                          item.namalengkap
                        }} </VTag>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </VCard>
      </div> -->
    </div>
  </div>

  <!-- Filter Tanggal -->
  <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.filterTgl" class="is-centered" is-range trim-weeks />
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

  <VModal :open="modalFilterJadwal" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilterJadwal = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.filterDate" class="is-centered" trim-weeks />
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="fetchJadwal()" :loading="isLoading" color="primary" raised>
        Filter</VButton>
    </template>
  </VModal>

  <!-- Verifikasi Penjadwalan -->
  <VModal :open="modalDetailOrder" title="Verifikasi Penjadwalan" noclose size="big" actions="right"
    @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="business-dashboard hr-dashboard">
        <div class="columns is-multiline">
          <div class="column is-12 p-0">
            <div class="block-header">
              <div class="left">
                <div class="current-user">
                  <VAvatar size="medium"
                    :picture="data.jeniskelamin == 'PEREMPUAN' ? '/images/avatars/svg/vuero-4.svg' : '/images/avatars/svg/vuero-1.svg'"
                    squared />
                  <h3 style="font-size: large;">{{ data.namapasien }}</h3>
                  <p class="block-text">
                    {{ data.nocm }}
                    -
                    {{ data.noregistrasi + ((data.jeniskelamin ? data.jeniskelamin.toUpperCase() : '') == 'PEREMPUAN' ?
                      ' (P)' : ' (L)') }}
                  </p>
                </div>
              </div>
              <div class="center">
                <div class="columns ml-0">
                  <div class="column">
                    <h4 class="block-heading">Tgl Registrasi</h4>
                    <p style="font-weight: normal;font-size: 12px;color: white;">{{
                      H.formatDateToLocalString(data.tglregistrasi) }}</p>
                    <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                    <p>
                      <VTag color="orange" :label="data.kelompokpasien" />
                    </p>
                  </div>
                  <div class="column">
                    <h4 class="block-heading">Ruangan Tujuan</h4>
                    <p style="font-weight: normal;font-size: 12px;color: white;">{{
                      data.ruangantujuan
                    }}</p>
                    <h4 class="block-heading">Nomor HP</h4>
                    <p style="font-weight: normal;font-size: 12px;color: white;">{{
                      data.nohp
                    }}</p>
                  </div>
                </div>
              </div>
              <div class="right">
                <h4 class="block-heading">Keterangan</h4>
                <p style="font-weight: normal;font-size: 12px;color: white;">{{
                  data.keterangan ?? '-'
                }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="columns is-multiline" style="padding: 2rem 0rem;">
        <div class="column is-12 pl-0">
          <div class="right">
            <div class="buttons">
              <VButton type="button" rounded outlined color="info" @click="openEMR(data, 'Protokol Kemoterapi')"
                :loading="isLoading" icon="lucide:file-text">
                Protokol Kemoterapi
              </VButton>
              <VButton type="button" rounded outlined color="info"
                @click="openEMR(data, 'Formulir Pencampuran Sediaan Kemoterapi')" :loading="isLoading"
                icon="lucide:file-text">
                Formulir Pencampuran Sediaan Kemoterapi
              </VButton>
              <VButton type="button" rounded outlined color="info"
                @click="openEMR(data, 'Surat Permintaan Obat Khusus Kemoterapi')" :loading="isLoading"
                icon="lucide:file-text">
                Surat Permintaan Obat Khusus Kemoterapi
              </VButton>
            </div>
          </div>
        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column is-2">
          <h6 class=" is-5  mt-3">
            <span> Tanggal Order </span>
          </h6>
        </div>
        <div class="column is-4">
          <VField>
            <VDatePicker v-model="data.tglorder" mode="dateTime" style="width: 100%" is24hr :format="H.formatDateIndo">
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal Order..." v-on="inputEvents" class="is-rounded"
                    disabled />
                </VControl>
              </template>
            </VDatePicker>
          </VField>
        </div>

        <div class="column is-2">
          <h6 class=" is-5  mt-3">
            <span>Tanggal Penjadwalan</span>
          </h6>
        </div>
        <div class="column is-4">
          <VField>
            <VDatePicker v-model="data.tglpenjadwalan" mode="dateTime" style="width: 100%" is24hr
              :format="H.formatDateIndo">
              <template #default="{ inputValue, inputEvents }">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal Penjadwalan..." v-on="inputEvents" class="is-rounded"
                    :disabled="isDisabled" />
                </VControl>
              </template>
            </VDatePicker>
          </VField>
        </div>

        <div class="column is-2">
          <h6 class=" is-5  mt-3">
            <span>Ruangan Asal</span>
          </h6>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="feather:list" class="prime-auto-select">
              <Dropdown v-model="data.ruanganfk" :options="d_RuanganAll" :optionLabel="'label'" placeholder="Pilih data"
                style="width: 100%;" class="is-rounded" showClear appendTo="body" :filter="true" disabled />
            </VControl>
          </VField>
        </div>

        <div class="column is-2">
          <h6 class=" is-5  mt-3">
            <span>Ruangan Tujuan</span>
          </h6>
        </div>
        <div class="column is-4">
          <VField>
            <VControl icon="feather:list" class="prime-auto-select">
              <Dropdown v-model="data.ruangantujuanfk" :options="d_Ruangan" :optionLabel="'label'"
                placeholder="Pilih data" style="width: 100%;" class="is-rounded" showClear appendTo="body"
                :filter="true" :disabled="isDisabled" />
            </VControl>
          </VField>
        </div>

        <div class="column is-2">
          <h6 class=" is-5  mt-3">
            <span> Petugas Pengirim </span>
          </h6>
        </div>
        <div class="column is-4">
          <VControl>
            <AutoComplete v-model="data.pegawaiorderfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="Ketik nama Dokter" class="is-rounded" disabled />
          </VControl>
        </div>

        <div class="column is-2">
          <h6 class=" is-5  mt-3">
            <span> Dokter KHOM </span>
          </h6>
        </div>
        <div class="column is-4">
          <VControl>
            <AutoComplete v-model="data.pegawaifk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="Ketik nama Dokter" class="is-rounded" :disabled="isDisabled"/>
          </VControl>
        </div>

        <div class="column is-4">
          <VField label="Diagnosa">
            <VTextarea rows="2" v-model="data.diagnosa" :disabled="isDisabled"></VTextarea>
          </VField>
        </div>

        <div class="column is-4">
          <VField label="Regimen">
            <VTextarea rows="2" v-model="data.regimen" :disabled="isDisabled"></VTextarea>
          </VField>
        </div>

        <div class="column is-4">
          <VField label="Keterangan">
            <VTextarea rows="2" v-model="data.keterangan" :disabled="isDisabled"></VTextarea>
          </VField>
        </div>
      </div>

    </template>
    <template #action>
      <VButton icon="feather:save" :loading="isLoading" @click="save()" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>

  <!-- Detail Order Verifikasi -->
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
                    <h4 class="block-heading">Tgl Operasi</h4>
                    <p class="block-text">{{ H.formatDateIndo(item.tgloperasi) }}</p>
                    <h4 class="block-heading">Estimasi Waktu</h4>
                    <p class="block-text">{{ item.estimasiwaktuoperasi || "-" }} Menit</p>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </VModal>

  <VModal :open="modalDetailPasien" title="Detail Pasien" :noclose="false" size="big" actions="right"
    @close="modalDetailPasien = false">
    <template #content>
      <DetailPasien v-if="modalDetailPasien" :noregistrasi="noreg_pasienDetail" :norec_pd="norec_pd_pasienDetail" />
    </template>
  </VModal>

  <VModal :open="showProtokolKemo" title="Protokol Kemoterapi" :noclose="false" size="big" actions="right"
    @close="closeEMR()">
    <template #content>
      <ProtokolKemo v-if="showProtokolKemo" :nocmfk="data.nocmfk" :norec_pd="data.norec_pd" :norec_apd="data.norec_apd"
        :pasien="data.pasien" :registrasi="data.registrasi" :hideButtons="true" />
    </template>
  </VModal>

  <VModal :open="showPercampuranSediaanKemo" title="Formulir Pencampuran Sediaan Kemoterapi" :noclose="false" size="big"
    actions="right" @close="closeEMR()">
    <template #content>
      <PercampuranSediaanKemo v-if="showPercampuranSediaanKemo" :nocmfk="data.nocmfk" :norec_pd="data.norec_pd"
        :norec_apd="data.norec_apd" :pasien="data.pasien" :registrasi="data.registrasi" :hideButtons="true" />
    </template>
  </VModal>

  <VModal :open="showPermintaanObatKhusus" title="Surat Permintaan Obat Khusus Kemoterapi" :noclose="false" size="big"
    actions="right" @close="closeEMR()">
    <template #content>
      <ObatKhususKemo v-if="showPermintaanObatKhusus" :nocmfk="data.nocmfk" :norec_pd="data.norec_pd"
        :norec_apd="data.norec_apd" :pasien="data.pasien" :registrasi="data.registrasi" :hideButtons="true" />
    </template>
  </VModal>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment, { isDate } from 'moment'
import Fieldset from 'primevue/fieldset'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import Dropdown from 'primevue/dropdown'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Calendar from 'primevue/calendar';
import MultiSelect from 'primevue/multiselect';
import Column from 'primevue/column'
import * as XLSX from "xlsx";
import * as qzService from '/@src/utils/qzTrayService'
import { useConfirm } from 'primevue/useconfirm'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import { Notyf } from 'notyf';
import ProtokolKemo from '../emr/profile-pasien/page-emr/protokol-kemoterapi.vue'
import ObatKhususKemo from '../emr/profile-pasien/page-emr/surat-permintaan-penggunaan-obat-khusus-kemoterapi.vue'
import PercampuranSediaanKemo from '../emr/profile-pasien/page-emr/formulir-pencampuran-sediaan-kemoterapi.vue'
import DetailPasien from '../registrasi/detail-registrasi.vue'

useHead({
  title: 'Dashboard Penjadwalan Kemoterapi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
let kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const NOREC_PD = useRoute().query.nocm as string
const dataSource: any = ref([])
const dataPasien: any = ref([])
const filters = ref('')
const d_Dokter = ref([])
const d_Status: any = ref([{ value: 1, label: 'Menunggu Operasi' }, { value: 2, label: 'Sedang Operasi' }, { value: 3, label: 'Selesai Operasi' }, { value: 4, label: 'Batal Operasi' }])
const d_Dokters = ref([])
const remakeData: any = ref([])
const d_DokterOperasi = ref([])
const d_Petugas = ref([])
const d_JenisPelaksana = ref([])
const d_Komponen = ref([])
const d_Pegawai = ref([])
const d_Ruangan = ref([])
const d_RuanganAll = ref([])
const d_Produk = ref([])
const d_JenisOperasi: any = ref([])
const router = useRouter()
const modalFilter: any = ref(false)
const isDisabled: any = ref(false)
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const listItem: any = ref([
  {
    pegawai: [],
    d_Pegawai: [],
    jenisPelaksana: null,
  }
])
const date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
const modalDetail = ref(false)
const route = useRoute()
const data: any = ref({})
const item: any = ref({
  filterDate: new Date(),
  jenisTgl: 'tglorder',
  filterTgl: ref({
    start: new Date(),
    end: new Date(),
  }),
  operatorhelperfk: []
})
const order: any = ref('hematologi')
const dataOrder: any = ref(0)
const dataLaporan: any = ref(0)

let listColor: any = ref(Object.keys(useThemeColors()))
let statusOrder: any = ref([])
let dokterPraktek: any = ref([])
let isLoading: any = ref(false)
let modalDetailOrder: any = ref(false)
const isLoadChange: any = ref(false)
let modalDetailOrderVerify: any = ref(false)
let modalFilterJadwal: any = ref(false)
let modalDetailPasien: any = ref(false)
let showProtokolKemo: any = ref(false)
let showPercampuranSediaanKemo: any = ref(false)
let showPermintaanObatKhusus: any = ref(false)
let isLoadBtn: any = ref(false)
let detailDiagnosa: any = ref([])
let dataStokObat: any = ref([])
let dataOperasi: any = ref([])
let detailOrderVerify: any = ref(0)
let detailPetugas: any = ref(0)
let detailOrderLayanan: any = ref(0)
let isData: any = ref()
let d_Kamar: any = ref([])
function getColorByType(objectjenispetugaspefk) {
  if (objectjenispetugaspefk === 6) {
    return '#bb2124';
  } else if (objectjenispetugaspefk === 17) {
    return '#5bc0de';
  } else {
    return 'gray';
  }
}

const noreg_pasienDetail = ref('');
const norec_pd_pasienDetail = ref('');
const detailRegistrasi = async (e: any) => {
  modalDetailPasien.value = true
  noreg_pasienDetail.value = e.noregistrasi;
  norec_pd_pasienDetail.value = e.norec_pd;
}

const pindahPulang = (e: any) => {
  router.push({
    name: 'module-rawat-inap-pindah-pulang',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      departemenfk: e.objectdepartemenfk,
    }
  })
}

async function fetchDokter(filter: any) {
  let query = ''
  if (filter) {
    query = filter.query
  }
  const response = await useApi().get(`/general/dokter-paging?name= ${query}&limit=10`)
  d_Dokter.value = response.dokter.map((e: any) => {
    return { label: e.namalengkap, value: e.id }
  })
}

const fetchDataOrder = async (q: any) => {
  try {
    let ruanganid = item.value.filterRuangan ? `&ruanganid=${item.value.filterRuangan}` : '';
    let dari = item.value.filterTgl.start ? `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}` : '';
    let sampai = item.value.filterTgl.end ? `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}` : '';
    let qnamapasien = item.value.qnamapasien ? `&qnamapasien=${item.value.qnamapasien}` : '';
    let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
    let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
    let statusOrder = q ? `&statusorder=${q}` : '';
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';
    let opsi = item.value.jenisTgl ? `&opsi=${item.value.jenisTgl}` : '';

    isLoading.value = true;

    const response = await useApi().get(`/penjadwalan-kemoterapi/get-penjadwalan?${ruanganid}${dari}${sampai}${qnamapasien}${statusOrder}${qnocm}${qnoregistrasi}${search}${opsi}`);

    modalFilter.value = false;
    isData.value = response.length;
    dataOrder.value = response;
  } catch (error) {
    console.error('Error fetching order data:', error);
  } finally {
    isLoading.value = false;
  }
}

async function fetchLaporan() {
  isLoading.value = true;
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  }
  let produk = item.value.namaproduk ? `&namaproduk=${item.value.namaproduk}` : ''
  let qnamapasien = item.value.qnama ? `&qnamapasien=${item.value.qnama}` : '';
  let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
  let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
  let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';
  await useApi().get(`/dashboard/laporan-tindakan-operasi?${dari}${sampai}${produk}${qnamapasien}${qnocm}${qnoregistrasi}${search}`).then((response) => {
    response.forEach((element, i) => {
      element.no = i + 1;
      element.jamoperasi = element.jamoperasi || '';
    });
    dataLaporan.value = response;
    isLoading.value = false;
  }).catch((e) => {
    isLoading.value = false;
  });
}

const fetchJadwal = async () => {
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = item.value.filterRuangan
  }
  let tgl = H.formatDate(item.value.filterDate, 'YYYY-MM-DD')

  dataOperasi.value = []
  dataOperasi.value.loading = true
  const response = await useApi().get('/dashboard/jadwal-operasi?ruanganid=' + ruanganid + '&tgl=' + tgl
  )
  dataOperasi.value.loading = false
  modalFilterJadwal.value = false
  dataOperasi.value = response.dataOperasi
}

const filterJadwal = () => {
  modalFilterJadwal.value = true
}

const getListPelayanan = async (data: any) => {
  const response = await useApi().get(`/dashboard/get-pelayanan-bedah?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}&objectkebangsaanfk=${item.value.objectkebangsaanfk}&objectruangantujuanfk=${item.value.idRuanganTujuan}`)
  d_Produk.value = response.map((e: any) => {
    return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
  })
}

const orderVerify = async (e: any, preview: any) => {
  if (order.value == 'farmasi' && kelompokUser != 'farmasi') {
    H.alert('warning', 'Hanya dapat diverifikasi oleh FARMASI!')
    return;
  }
  if (preview) {
    isDisabled.value = true
  }

  e.loading = true
  await useApi().get(`/penjadwalan-kemoterapi/get-detail-penjadwalan?norec_pj=${e.norec_pj}`).then((res) => {
    if (res) {
      data.value = res
      let d = data.value
      d.ruanganfk = { value: res.id_asalruangan, label: res.asalruangan }
      d.ruangantujuanfk = { value: res.id_ruangantujuan, label: res.ruangantujuan, default: { id: res.id_ruangantujuan, namaruangan: res.ruangantujuan, objectdepartemenfk: res.dep_ruangantujuan } }
      d.pegawaiorderfk = { value: res.id_pegawaiorderfk, label: res.namalengkap }
      d.pegawaifk = { value: res.id_pegawaifk, label: res.namalengkap2 }
      d.regimen = res.regimen_kemoterapi
      modalDetailOrder.value = true
    } else {
      H.alert('warning', 'Terjadi Kesalahan')
    }
  }).catch((e: any) => {
    console.log(e)
    H.alert('error', 'Terjadi Kesalahan')
  }).finally(() => {
    e.loading = false
  });
}

const save = async () => {
  let d = data.value
  let parameter = {
    norec_pj: d.norec_pj,
    nocmfk: d.nocmfk,
    noregistrasi: d.noregistrasi,
    tanggal: H.formatDate(d.tglorder, 'YYYY-MM-DD HH:mm:ss'),
    tglpenjadwalan: H.formatDate(d.tglpenjadwalan, 'YYYY-MM-DD HH:mm:ss'),
    norec_apd: d.norec_apd,
    norec_pd: d.norec_pd,
    objectruanganfk: d.ruanganfk.value,
    pegawaiorderfk: d.pegawaiorderfk.value,
    pegawaifk: d.pegawaifk.value,
    regimen: d.regimen,
    diagnosa: d.diagnosa,
    objectruangantujuanfk: d.id_ruangantujuan,
    keterangan: d.keterangan != undefined ? d.keterangan : null,
    verif_kemoterapi: kelompokUser == ('perawat' || 'dokter') && order.value == 'kemoterapi' ? true : null,
    verif_hematologi: kelompokUser == ('perawat' || 'dokter') && order.value == 'hematologi' ? true : null,
    verif_farmasi: kelompokUser == 'farmasi' ? true : null,
    kelompokUser: kelompokUser.toUpperCase()
  }

  isLoading.value = true;
  await useApi().post('/penjadwalan-kemoterapi/verifikasi-penjadwalan', { 'parameter': parameter }).then((response: any) => {
    modalDetailOrder.value = false
    fetchDataOrder(order.value)
  }).catch((error) => {
    useToaster().error('Something Went Wrong')
  }).finally(() => {
    isLoading.value = false;
  });
}

const changeSwitch = (e: any) => {
  fetchDataOrder(e)
}

const showModalFilter = () => {
  modalFilter.value = true
}

const clear = () => {
  item.value.id = ''
  item.value.no = ''
  item.value.layanan = ''
  item.value.hargaLayanan = ''
  item.value.qtyproduk = ''
  item.value.jumlah = ''
  isDisabled.value = false
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

const fetchdDropdown = async () => {
  const ruanganTujuan = await useApi().get(`penjadwalan-kemoterapi/list-dropdown`)
  const ruangan = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&limit=100`)
  d_Ruangan.value = ruanganTujuan['ruangan'].map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_RuanganAll.value = ruangan
}

const changePeriode = () => {
  fetchDataOrder(0)
  fetchLaporan()
}

const exportExcel = () => {
  remakeData.value = dataLaporan.value.map((e: any) => {
    return {
      jamoperasi: e.jam, ruangantujuan: e.RuangOK, NamaPasien: e.namapasien, NamaPasien: e.namapasien, AsalRuangan: e.asalruangan, Tindakan: e.namaproduk,
      tgloperasi: e.tgloperasi, jamoperasi: e.jamoperasi, dokterPemeriksa: e.dokterpemeriksa, Harga: e.hargasatuan, Total: e.total,
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
  // window.open(_url, EXCEL_EXTENSION).focus();
  window.open(_url, EXCEL_EXTENSION).focus()
  exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

const changeRuang = async (e: any) => {
  for (let x = 0; x < d_Ruangan.value.length; x++) {
    const element: any = d_Ruangan.value[x];
    if (e == element.value) {
      item.value.namaruangan = element.label
      break
    }
  }
  await fetchDataOrder(0)

  // fetchJadwal()
}

const emr = (e: any) => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      norec_apd: e.norec_apd
    }
  })
}

const changeMenu = (dt: any) => {
  switch (dt) {
    case "Pasien":
      fetchDataOrder('hematologi')
      break;

    case "Laporan":
      fetchLaporan()
      break;

    default:
      break;
  }
}

async function openEMR(e, emr) {
  await router.push({
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      departemenfk: e.objectdepartemenfk,
      isfromKemo: true,
      paramKemo: order.value
    }
  })
  // Get data profile pasien
  isLoading.value = true
  await useApi().get(`/emr/header-pasien?nocmfk=${e.nocmfk}&norec_pd=${e.norec_pd}&norec_apd=${e.norec_apd}`).then(async (res: any) => {
    data.value.registrasi = res.registrasi[0]
    data.value.pasien = res.pasien
    switch (emr) {
      case 'Protokol Kemoterapi':
        showProtokolKemo.value = true;
        break; ``
      case 'Formulir Pencampuran Sediaan Kemoterapi':
        showPercampuranSediaanKemo.value = true;
        break;
      case 'Surat Permintaan Obat Khusus Kemoterapi':
        showPermintaanObatKhusus.value = true;
        break;
      default:
        break;
    }
  }).catch((e: any) => {
    H.alert('warning', 'Terjadi kesalahan')
  }).finally(() => {
    isLoading.value = false;
  });
}

async function closeEMR(e) {
  await router.push({ query: {} })
  showProtokolKemo.value = false;
  showPercampuranSediaanKemo.value = false;
  showPermintaanObatKhusus.value = false;
}

watch(
  () => [
    order.value
  ], () => {
    changeSwitch(order.value)
  }
)
watch(
  () => item.value.filterTgl,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchDataOrder(order.value)
    }
  }
)


onMounted(() => {
  fetchDataOrder('hematologi')
  // fetchJadwal()
  fetchdDropdown()
})

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';

.label-unset {
  text-overflow: unset !important;
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

.p-datatable.p-component {
  .p-datatable-wrapper {
    height: 1000px;
  }
}
</style>
