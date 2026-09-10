<template>
  <ConfirmDialog></ConfirmDialog>
  <section>
    <VCard radius="smooth" elevated class="mb-3-min br-16">
      <div class="columns is-multiline">
        <div class="column is-3">
          <VIconButton icon="feather:arrow-left" light dark-outlined @click="backPage()" />
          <span class="title-emr ml-3">Kontrol Pasien</span>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-12">
          <TabView v-model:activeIndex="activeIdx">
            <TabPanel header="List Rencana Kontrol/RI">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VCard radius="rounded">
                    <div class="columns ">
                      <h3 class="title is-5 mb-2 mr-1 p-3">Riwayat Pemeriksaan </h3>
                    </div>
                    <div class="columns is-multiline is-mobile">
                      <div class="column is-12 p-0">
                        <div class="p-5">
                          <div class="columns is-multiline">
                            <div class="column is-3">
                              <VField label="Tanggal Reservasi" style="margin-bottom: 6px;" />
                              <div class="columns is-multiline">
                                <div class="column is-6">
                                  <VField>
                                    <Calendar v-model="input.tglAwal" selectionMode="single" :manualInput="false"
                                      class="w-100" :showIcon="true" :showTime="false" />
                                  </VField>
                                </div>
                                <div class="column is-6">
                                  <VField>
                                    <Calendar v-model="input.tglAkhir" selectionMode="single" :manualInput="false"
                                      class="w-100" :showIcon="true" :showTime="false" />
                                  </VField>
                                </div>
                              </div>

                              <!-- <VDatePicker v-model="filterTanggal.fPeriode"  is-single color="pink" locale="id"   trim-weeks>
                                <template #default="{ inputValue, inputEvents }" >
                                  <VField addons>
                                    <VControl icon="feather:calendar">
                                      <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                    </VControl>
                                    <VControl>
                                      <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                    </VControl>
                                    <VControl icon="feather:calendar">
                                      <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                    </VControl>
                                  </VField>
                                </template>
</VDatePicker> -->
                            </div>
                            <div class="column is-1 mt-2">
                              <VField>
                                <VControl>
                                  <VSwitchBlock v-model="input.isAllPeriode" label="All Periode" color="danger" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-1" style="width: 100px;">
                              <VField>
                                <VLabel>No RM</VLabel>
                                <VInput v-model="input.norm" @input="handleFilters" class="p-column-filter"
                                  type="text" />
                              </VField>
                            </div>
                            <div class="column is-1">
                              <VField>
                                <VLabel>Nama</VLabel>
                                <VInput v-model="input.nama" @input="handleFilters" class="p-column-filter"
                                  type="text" />
                              </VField>
                            </div>
                            <div class="column is-2">
                              <VField label="Ruangan">
                                <VControl class="prime-auto">
                                  <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan"
                                    class=" w-100" placeholder="Filter ruangan" :searchable="true" autocomplete="off" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-2">
                              <VField label="Dokter">
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="item.filterDokter" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                                  <!-- <Multiselect mode="single" v-model="item.filterDokter" :options="d_Dokter" class=" w-100"
                                    placeholder="Filter Dokter" :searchable="true" autocomplete="off" @complete="fetchDokter($event)"/> -->
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-2 mt-5">
                              <VIconButton circle icon="feather:refresh-cw" raised bold @click="reloadData()"
                                :loading="isLoadingRiwayat" v-tooltip.bubble="'Perbaharui Data Kontrol'"
                                class="is-pulled-right ">
                              </VIconButton>
                              <VIconButton type="button" color="success" rounded raised icon="fas fa-search"
                                @click="reloadData()" :loading="isLoadingRiwayat"
                                class="is-rounded is-pulled-right mr-2">
                              </VIconButton>
                              <VButton rounded color="info" class="mr-2 is-pulled-right" icon="pi pi-pencil" raised bold
                                @click="editSurkon(selectedDataKontrol)" :loading="isLoadingRiwayat">
                                Edit
                              </VButton>
                              <VButton rounded color="primary" class="mr-2 is-pulled-right" icon="fas fa-print" raised
                                bold @click="cetak(selectedDataKontrol)" :loading="isLoadingCetak"
                                style="display: none !important">
                                Print Surat
                              </VButton>
                              <VButton rounded color="info" class="mr-2 is-pulled-right" icon="fas fa-eye" raised bold
                                @click="openModalDetail()" :loading="isLoadingRiwayat">
                                Detail
                              </VButton>
                              <VButton rounded color="danger" light class="mr-2 is-pulled-right" icon="fas fa-trash"
                                raised bold @click="openModalDelete()" :loading="isLoadingRiwayat">
                                Hapus
                              </VButton>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="column is-12">
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <div class="project-files">
                              <div class="">
                                <div class="updates-list">
                                  <div class="">
                                    <div class="column" v-if="isLoadingRiwayat">
                                      <VPlaceloadWrap v-for="s in 25">
                                        <VPlaceload class="mx-2 mb-3" />
                                        <VPlaceload class="mx-2" />
                                      </VPlaceloadWrap>
                                    </div>
                                    <div class="update-item is-dark-bordered-12 " style="display: block;"
                                      v-else-if="dataKontrol.length === 0">
                                      <div class="search-results-wrapper">
                                        <div class="search-results-body ">
                                          <!--Search Placeholder -->
                                          <div class="page-placeholder">
                                            <div class="placeholder-content">
                                              <img class="light-image" style=" max-width: 340px;"
                                                :src="H.assets().iconNotFound_rev" alt="" />
                                              <img class="dark-image" style=" max-width: 340px;"
                                                :src="H.assets().iconNotFound_rev" alt="" />
                                              <h3>{{ H.assets().notFound }}</h3>
                                              <p class="is-larger">
                                                {{ H.assets().notFoundSubtitle }}
                                              </p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="column" style="max-width:100%" v-else>
                                      <DataTable :value="dataKontrol" class="p-datatable-md" :loading="isLoadingRiwayat"
                                        :paginator="true" :rows="100" :rowsPerPageOptions="[5, 10, 25]" scrollable
                                        scrollHeight="600px" dataKey="id"
                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                        responsiveLayout="stack" breakpoint="960px"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                        showGridlines>

                                        <template #empty> No customers found. </template>
                                        <template #loading> Loading customers data. Please wait. </template>
                                        <Column headerStyle="width: 3rem" frozen>
                                          <template #body="slotProps">
                                            <div class="is-flex">
                                              <VRadio v-model="modelSelectedKontrol" :value="slotProps.data.norec"
                                                name="radioKontrol" color="primary"
                                                @click="onKontrolSelected(slotProps.data)"
                                                style="padding:0px;padding-left:10px; padding-right: 5px" />
                                              <i class="fas fa-clipboard" aria-hidden="true"
                                                v-if="slotProps.data.nosurat != null"
                                                style="color:var(--primary); padding-right:5px"></i>
                                            </div>
                                          </template>
                                        </Column>
                                        <Column field="noreservasi" header="No Reservasi" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="kelompokpasien" header="Jenis Pasien" :sortable="true"
                                          style="min-width: 90px">
                                        </Column>
                                        <Column field="nosuratkontrol" header="No Kontrol" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="nobpjs" header="No BPJS" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="tglinput" header="Tanggal Entry" :sortable="true"
                                          style="min-width: 90px">
                                          <template #body="slotProps">
                                            <span> {{ slotProps.data.tglinput != null ?
                                              H.formatDateToLocalString(slotProps.data.tglinput) : '-' }} </span>
                                          </template>
                                        </Column>
                                        <Column field="jamreservasi" header="Jam" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="nocm" header="NRM" :sortable="true" style="min-width: 90px">
                                        </Column>
                                        <Column field="type" header="Tipe" :sortable="true" style="min-width: 90px">
                                        </Column>
                                        <Column field="namapasien" header="Nama" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="notelepon" header="No HP" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="noantrianpoli" header="No Urut" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="namaruangan" header="Section" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="namadokter" header="Dokter" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column field="tanggalreservasi" header="Tanggal Reservasi" :sortable="true"
                                          style="min-width: 90px">
                                          <template #body="slotProps">
                                            <span> {{ H.formatDateToLocalString(slotProps.data.tanggalreservasi) }}
                                            </span>
                                          </template>
                                        </Column>
                                        <Column field="jenis" header="Status Pasien" :sortable="true"
                                          style="min-width: 90px"></Column>
                                        <Column header="Status" style="min-width: 90px" align="center">
                                          <template #body="slotProps">
                                            <div class="column" v-if="slotProps.data.ismobilejkn != true">
                                              <VTag class="ml-1" color="primary" :class="slotProps.data.norec" rounded
                                                v-if="slotProps.data.noantrian != null || slotProps.data.isconfirm == true">
                                                Sudah Check-In</VTag>
                                              <VTag class="ml-1" color="danger" :class="slotProps.data.norec" rounded
                                                v-else>Belum Check-In</VTag>
                                            </div>
                                            <div class="column" v-else>
                                              <VTag class="ml-1" color="primary" :class="slotProps.data.norec" rounded
                                                v-if="slotProps.data.noantrian != null && slotProps.data.isconfirm == true">
                                                Sudah Check-In</VTag>
                                              <VTag class="ml-1" color="danger" :class="slotProps.data.norec" rounded
                                                v-else>Belum Check-In</VTag>
                                            </div>
                                          </template>
                                        </Column>
                                        <Column header="Action" style="min-width: 90px" align="center">
                                          <template #body="slotProps">
                                            <div class="column is-flex" style="justify-content: center;" v-if="slotProps.data.ismobilejkn == true">
                                              <VTag class="ml-1" color="primary" rounded
                                                v-if="slotProps.data.noantrian != null && slotProps.data.isconfirm == true">
                                                Sudah Check-In</VTag>
                                              <button type="button" aria-hidden="false"
                                                class="button is-outlined is-raised is-primary" color="success"
                                                @click="checkin(slotProps.data)" v-else>
                                                <span style="color: black;">Check-In</span>
                                              </button>
                                            </div>
                                            <div class="column is-flex" style="justify-content: center;" v-else>
                                              <VTag class="ml-1" color="primary" rounded
                                                v-if="slotProps.data.noantrian != null || slotProps.data.isconfirm == true">
                                                Sudah Check-In</VTag>
                                              <button type="button" aria-hidden="false"
                                                class="button is-outlined is-raised is-primary" color="success"
                                                @click="checkin(slotProps.data)" v-else>
                                                <span style="color: black;">Check-In</span>
                                              </button>
                                            </div>
                                          </template>
                                        </Column>
                                        <Column header="MJKN" style="min-width: 90px" align="center">
                                          <template #body="slotProps">
                                            <div class="column is-flex" style="justify-content: center;">
                                              <span class="icon" circle v-if="slotProps.data.ismobilejkn == true && slotProps.data.isconfirm != true"><i aria-hidden="true"
                                                  class="fas fa-check" circle></i></span>
                                              <VTag class="ml-1" color="primary" rounded
                                                v-if="slotProps.data.ismobilejkn == true && slotProps.data.isconfirm == true">
                                                Sudah Check-In</VTag>
                                            </div>
                                          </template>
                                        </Column>
                                      </DataTable>
                                    </div>

                                  </div>
                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </VCard>
                </div>
              </div>
            </TabPanel>
            <TabPanel header="Buat Baru">
              <div class="columns is-multiline">
                <div class="column is-3" style="display: none !important">
                  <VControl>
                    <VLabel class="required-field">Pilih</VLabel>
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <VRadio v-model="input.jenis" :value="2" :label="'Rencana Kontrol'" name="jenisKntrl" square
                          color="primary" />
                      </div>
                      <div class="column is-6">
                        <VRadio v-model="input.jenis" :value="1" :label="'Rencana Rawat Inap'" name="jenisKntrl" square
                          color="primary" />
                      </div>
                    </div>
                  </VControl>
                </div>
                <div class="column is-3" v-if="input.jenis == 1" style="display: none !important">
                  <VField>
                    <VLabel class="required-field">No. Kartu</VLabel>
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="input.noKartu" placeholder="No. Kartu" class="is-rounded_Z" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" style="display: none !important">
                  <VIconButton icon="feather:search" @click="cariPasien" color="success" raised
                    :loading="isLoadingPasien" circle class="mt-5">
                  </VIconButton>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2" style="display: none !important">

                      <div class="s-card mt-5" style=" border-top: 3px solid var(--danger);">
                        <h3 class="title is-5 ">
                          <span> {{ peserta ? peserta.nama : '' }}</span>
                        </h3>
                        <p style=" font-size: 1rem;" class="mt-3-min"> NO RM : {{ peserta.mr ? peserta.mr.noMR : '' }}
                        </p>

                        <div class="boxx boxx-widget widget-user-2">
                          <div class="widget-user-header" v-if="isLoadingPasien">
                            <VFlexTableCell :column="{ grow: true, media: true }">

                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                            </VFlexTableCell>

                          </div>

                          <ul class="list-group list-group-unbordered" v-else>
                            <li class="list-group-item">
                              <span class="fas fa-address-card"></span> <a title="NIK" class="pull-right-container">{{
                                peserta.nik ?? '' }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-credit-card"></span> <a title="No.Kartu Bapel JKK"
                                class="pull-right-container">{{
                                  peserta.noKartu ?? '' }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-calendar"></span> <a title="Tanggal Lahir"
                                class="pull-right-container">{{
                                  peserta.tglLahir ?? '' }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-info-circle"></span> <a title="PISA" class="pull-right-container">{{
                                peserta.statusPeserta.keterangan ?? ''
                              }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fas fa-hospital-user"></span> <a title="Hak Kelas Rawat"
                                class="pull-right-container">{{
                                  peserta.hakKelas.keterangan ?? '' }} </a>

                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-stethoscope"></span> <a title="Faskes Tingkat 1"
                                class="pull-right-container">{{
                                  peserta.provUmum.kdProvider ?? '' }} - {{
                                  peserta.provUmum.nmProvider ?? '' }}</a>

                            </li>
                            <li class="list-group-item">
                              <span class="fas fa-calendar-plus"></span> <a title="TMT dan TAT Peserta"
                                class="pull-right-container">{{
                                  peserta.tglTMT ?? '' }}
                                s.d
                                {{ peserta.tglTAT ?? '' }}</a>

                            </li>
                            <li class="list-group-item">
                              <span class="fas fa-id-card-alt"></span> <a title="Jenis Peserta"
                                class="pull-right-container">{{
                                  peserta.jenisPeserta.keterangan ?? ''
                                }}</a>

                            </li>
                          </ul>

                        </div>
                      </div>
                      <div class="s-card mt-2" style=" border-top: 3px solid var(--info);" v-if="sep.noSep">
                        <h3 class="title is-5 ">
                          <span> SEP</span>

                        </h3>

                        <div class="boxx boxx-widget widget-user-2">

                          <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                              <span class="fa fa-sort-asc"></span> <a title="NIK" class="pull-right-container">{{
                                sep.noSep }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-calendar"></span> <a title="Tgl.SEP" class="pull-right-container">{{
                                sep.tglSep }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-medkit"></span> <a title="Tanggal Lahir"
                                class="pull-right-container">{{
                                  sep.jnsPelayanan }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-heartbeat"></span> <a title="Diag" class="pull-right-container">{{
                                sep.diagnosa
                              }}</a>
                            </li>

                          </ul>

                        </div>
                      </div>
                    </div>
                    <div class="column is-4" style="margin-top: -5px;">
                      <VField horizontal label="No. RM" class="is-rounded-select_Z  is-autocomplete-select">
                        <VControl icon="feather:user" fullwidth>
                          <VInput type="text" v-model="input.noRM" :value="input.noRM" placeholder="No. RM"
                            class="is-rounded_Z" @keyup="normFormat(input.noRM)" maxlength="8"
                            v-on:keyup.enter="cariRM" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-1" style="margin-top: -15px;">
                      <VField class="h-hidden-mobile mt-3">
                        <VIconButton type="button" color="success" class="is-rounded" rounded raised
                          icon="fas fa-search" @click="cariRM()" :loading="isLoading">
                        </VIconButton>
                      </VField>
                    </div>
                    <div class="column is-7" style="margin-top: -10px;">
                      <VControl>
                        <VSwitchBlock v-model="input.isReservasiBaru" label="Pasien Baru" color="danger" />
                      </VControl>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-6">
                          <div class="columns is-multiline">
                            <div class="column is-12" style="margin-top: 10px;">
                              <div class="fieldset-heading">
                                <h4 class="required-field"><b>Informasi Pasien</b></h4>
                              </div>
                            </div>
                            <div class="column is-6">
                              <VField id="nik" v-slot="{ field }">
                                <VLabel class="required-field">NIK / PASSPORT / KITAS</VLabel>
                                <VControl icon="feather:book" :loading="isLoadingNIK">
                                  <VInput type="text" v-model="input.nik" placeholder="Enter untuk pencarian Identitas"
                                    class="is-rounded_Z" v-on:keyup.enter="cariBPJS('nik')" />
                                  <p v-if="field?.errorMessage" class="help is-danger">
                                    {{ field.errorMessage }}
                                  </p>
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-6">
                              <VField id="nobpjs" v-slot="{ field }" label="No BPJS">
                                <VControl icon="feather:book" :loading="isLoadingBPJS">
                                  <VInput type="text" v-model="input.nobpjs" placeholder="Enter untuk pencarian No BPJS"
                                    class="is-rounded_Z" v-on:keyup.enter="cariBPJS('nobpjs')" />
                                  <p v-if="field?.errorMessage" class="help is-danger">
                                    {{ field.errorMessage }}
                                  </p>
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-12">
                              <VField>
                                <VLabel class="required-field">Nama Pasien</VLabel>
                                <VControl icon="feather:user">
                                  <VInput type="text" v-model="input.namapasien" placeholder="Nama Pasien"
                                    class="is-rounded_Z" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-6">
                              <VField>
                                <VLabel class="required-field">Tempat Lahir</VLabel>
                                <VControl icon="feather:map-pin">
                                  <VInput type="text" v-model="input.tempatlahir" placeholder="Tempat Lahir"
                                    class="is-rounded_Z" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-6">
                              <VField>
                                <VLabel class="required-field">Tgl Lahir</VLabel>
                                <VControl class="prime-auto">
                                  <Calendar v-model="input.tgllahir" selectionMode="single" :manualInput="true"
                                    class="w-100" :showIcon="true" :showTime="false" hourFormat="24"
                                    :date-format="'dd-mm-yy'" placeholder="dd-mm-yyyy" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-6">
                              <VField>
                                <VLabel class="required-field">No HP/Ponsel</VLabel>
                                <VControl icon="feather:phone">
                                  <VInput type="text" v-model="input.nohp" placeholder="No HP" class="is-rounded_Z"
                                    @keypress="onlyNumber($event)" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-6">
                              <VField>
                                <VLabel class="required-field">Jenis Kelamin</VLabel>
                                <VControl>
                                  <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">
                                    <div class="column is-12" v-if="d_JK.length == 0">
                                      <VPlaceloadText :lines="1" />
                                    </div>
                                    <div class="column is-4 p-0" v-for="items in d_JK" :key="items.id">
                                      <VRadio v-model="input.jenisKelamin" :value="items.id" class="p-0 mb-3"
                                        :label="items.jeniskelamin" name="{{items.id}}" square color="primary" />
                                    </div>
                                  </div>
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-12">
                              <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select" v-slot="{ id }">
                                <VLabel class="required-field">Agama</VLabel>
                                <VControl icon="feather:search">
                                  <Multiselect mode="single" v-model="input.agama" :options="d_Agama" autocomplete="off"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-12">
                              <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                <VLabel>Negara</VLabel>
                                <VControl icon="feather:search">
                                  <Multiselect mode="single" v-model="input.negara" :options="d_Negara"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                        <div class="column is-6">
                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <div class="fieldset-heading">
                                <h4 class="required-field"><b>Informasi Alamat</b></h4>
                              </div>

                              <div class="columns is-multiline" style="margin-top: 30px;">
                                <div class="column is-9">
                                  <VField>
                                    <VLabel class="required-field">Alamat Lengkap</VLabel>
                                    <VControl>
                                      <VTextarea v-model="input.alamat" rows="1" placeholder="Alamat Lengkap">
                                      </VTextarea>
                                    </VControl>
                                  </VField>
                                </div>

                                <div class="column is-3">
                                  <VField>
                                    <VLabel>RT / RW</VLabel>
                                    <VControl>
                                      <VInput type="text" v-model="input.rtrw" placeholder="RT / RW"
                                        class="is-rounded_Z" />
                                    </VControl>
                                  </VField>
                                </div>

                                <div class="column is-6">
                                  <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                    <VLabel>Provinsi</VLabel>
                                    <VControl icon="feather:search">
                                      <Multiselect mode="single" v-model="input.provinsi" :options="d_Provinsi"
                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                        @select="changeProvinsi(input.provinsi)" />
                                    </VControl>
                                  </VField>
                                </div>
                                <div class="column is-6">
                                  <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                    <VLabel>Kota Kabupaten</VLabel>
                                    <VControl icon="feather:search">
                                      <Multiselect mode="single" v-model="input.kotaKabupaten"
                                        :options="d_KotaKabupaten" placeholder="Pilih data" :searchable="true"
                                        :attrs="{ id }" :loading="isLoading"
                                        @select="changeKota(input.kotaKabupaten)" />
                                    </VControl>
                                  </VField>
                                </div>
                                <div class="column is-6">
                                  <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                    <VLabel>Kecamatan</VLabel>
                                    <VControl icon="feather:search">
                                      <Multiselect mode="single" v-model="input.kecamatan" :options="d_Kecamatan"
                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" :loading="isLoading"
                                        @select="changeKecamatan(input.kecamatan)" />
                                    </VControl>
                                  </VField>
                                </div>
                                <div class="column is-6">
                                  <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                    <VLabel>Kelurahan</VLabel>
                                    <VControl icon="feather:search">
                                      <Multiselect mode="single" v-model="input.desaKelurahan" :options="d_Kelurahan"
                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" :loading="isLoading"
                                        @select="changeDesa(input.desaKelurahan)" />
                                    </VControl>
                                  </VField>
                                </div>
                                <div class="column is-12">
                                  <VField>
                                    <VLabel>Kode Pos </VLabel>
                                    <VControl icon="feather:airplay" :loading="isLoadingKodePos">
                                      <VInput type="text" v-model="input.kodePos" placeholder="Kode Pos"
                                        class="is-rounded_Z" />
                                    </VControl>
                                  </VField>
                                </div>

                                <div class="column is-12">
                                  <VField>
                                    <VLabel>Email </VLabel>
                                    <VControl icon="feather:mail">
                                      <VInput type="email" v-model="input.email" placeholder="Email" inputmode="email"
                                        class="is-rounded_Z" />
                                    </VControl>
                                  </VField>
                                </div>
                                <div class="column is-12">
                                  <VField>
                                    <VLabel>Nama Ibu </VLabel>
                                    <VControl icon="pi pi-reddit">
                                      <VInput type="text" v-model="input.namaIbu" placeholder="Nama Ibu"
                                        class="is-rounded_Z" />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- <PasienBaru></PasienBaru> -->
                    </div>
                    <div class="column is-6" style="display: none !important">
                      <div class="s-card mt-5 p-6" style=" border-top: 3px solid var(--orange);">
                        <VField horizontal label="No Bukti" required>
                          <VControl fullwidth>
                            <VInput type="text" placeholder="No Bukti" autocomplete="off" v-model="input.nobukti"
                              disabled />
                          </VControl>
                        </VField>
                        <VField horizontal label="Diagnosa Akhir" required>
                          <VField>
                            <VControl>
                              <VTextarea v-model="input.diagnosaakhir" rows="3" disabled>
                              </VTextarea>
                            </VControl>
                          </VField>
                        </VField>
                        <VField horizontal label="Indikasi Kontrol" required>
                          <VField>
                            <VControl>
                              <VTextarea v-model="input.indikasikontrol" rows="3" disabled>
                              </VTextarea>
                            </VControl>
                          </VField>
                        </VField>
                        <VField horizontal label="Catatan" required>
                          <VField>
                            <VControl>
                              <VTextarea v-model="input.catatan" rows="3" disabled>
                              </VTextarea>
                            </VControl>
                          </VField>
                        </VField>
                        <VField horizontal label="Obat/Terapi" required>
                          <VField>
                            <VControl>
                              <VTextarea v-model="input.terapi" rows="3" disabled>
                              </VTextarea>
                            </VControl>
                          </VField>
                        </VField>
                      </div>
                    </div>
                    <div class="column is-12">
                      <div class="s-card mt-5 p-6" style=" border-top: 3px solid var(--orange);">
                        <h3 class="title is-5 head-sep ml-1">
                          <span v-if="noSuratKontrol"> {{ noSuratKontrol }}</span>
                        </h3>
                        <h3 class="title is-5 head-sep ml-1">
                          <span v-if="sep && sep.masaRujukan"> <small>Masa Berlaku Rujukan : </small> {{ sep.masaRujukan
                            }}</span>
                        </h3>

                        <div class="columns is-multiline">
                          <div class="column is-6">
                            <VCheckbox v-model="input.reservasi" value-true="reservasi" label="Buat Reservasi"
                              color="primary" paddingless />
                          </div>
                          <div class="column is-6">
                            <VCheckbox v-model="input.surkon" value-true="surkon" label="Buat Surat Kontrol BPJS"
                              color="primary" paddingless />
                          </div>
                        </div>

                        <VField horizontal label="Pelayanan" required style="display: none !important">
                          <VControl icon="fas fa-ambulance" fullwidth>
                            <VInput type="text" placeholder="Pelayanan" autocomplete="off" v-model="input.pelayanan"
                              disabled />
                          </VControl>
                        </VField>
                        <VField horizontal label="No. Surat Kontrol" required style="display: none !important">
                          <VControl icon="lnir lnir-hospital-alt-2" fullwidth>
                            <VInput type="text" placeholder="No. Surat Kontrol" autocomplete="off"
                              v-model="input.noSuratKontrol" disabled />
                          </VControl>
                        </VField>
                        <VField horizontal label="Pembiayaan" class="is-rounded-select_Z  is-autocomplete-select"
                          v-slot="{ id }" required>
                          <VControl icon="fas fa-calculator" fullwidth>
                            <AutoComplete v-model="input.kelompokpasienold" :suggestions="d_KelompokPasien"
                              :optionLabel="'kelompokpasien'" :dropdown="true" :appendTo="'body'"
                              @complete="fetchPembiayaan($event)" :loadingIcon="'pi pi-spinner'"
                              :field="'kelompokpasien'" placeholder="Kelompok Pasien" />
                          </VControl>
                        </VField>
                        <VField horizontal label="Kebangsaan" class="is-rounded-select_Z  is-autocomplete-select"
                          v-slot="{ id }" required>
                          <VControl icon="feather:search" fullwidth>
                            <Multiselect mode="single" v-model="input.kebangsaan" :options="d_Kebangsaan"
                              placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                          </VControl>
                        </VField>
                        <VField horizontal label="No. SEP" class="is-rounded-select_Z  is-autocomplete-select"
                          v-if="dissep">
                          <VControl icon="feather:user" fullwidth>
                            <VInput type="text" v-model="input.noSEP" :value="input.noSEP" placeholder="No. SEP"
                              class="is-rounded_Z" />
                          </VControl>
                          <VIconButton type="button" color="success" class="is-rounded" rounded raised
                            style="margin-left: 5px;" icon="fas fa-search" @click="cariSEP()" :loading="isLoading">
                          </VIconButton>
                        </VField>

                        <VField v-if="input.kelompokpasien" horizontal label="Penjamin" style="display:none !important"
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:command" fullwidth>
                            <AutoComplete v-model="input.rekanan" :suggestions="d_Rekanan" :optionLabel="'namarekanan'"
                              :dropdown="true" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namarekanan'"
                              placeholder="Rekanan" />
                          </VControl>
                        </VField>
                        <VField horizontal label="Sub/Specialis" required
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:home" fullwidth class="prime-auto ">
                            <AutoComplete v-model="input.poliKontrol" :suggestions="d_Subspesialis"
                              @complete="fetchSupspesialis($event)" :optionLabel="'noruangan'" :dropdown="true"
                              :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaruangan'"
                              placeholder="ketik spesialis/subspesialis" />
                          </VControl>
                        </VField>
                        <VField horizontal label="Dokter" required class="is-rounded-select_Z  is-autocomplete-select"
                          v-slot="{ id }">
                          <VControl icon="feather:home" fullwidth class="prime-auto ">
                            <AutoComplete v-model="input.kodeDokter" :suggestions="d_dpjpLayan"
                              @complete="changeSpe($event)" :optionLabel="'nama'" :dropdown="true" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'nama'" placeholder="Dokter" />
                          </VControl>
                        </VField>
                        <VField horizontal label="Tgl. Rencana Kontrol / Inap">
                          <Calendar v-model="input.tglRencanaKontrol" selectionMode="single" :manualInput="true"
                            style="width: 50%;" :showIcon="false" :showTime="false" hourFormat="24"
                            :date-format="'yy-mm-dd'" disabled />
                          <VButton light dark-outlined @click="pilihJadwal()" :loading="isLoading">Pilih Jadwal
                          </VButton>
                        </VField>
                        <VField horizontal label="Jam">
                          <VControl fullwidth>
                            <VInput type="text" placeholder="Jam" autocomplete="off" v-model="input.jam" disabled />
                          </VControl>
                        </VField>
                      </div>
                      <div class="mt-2 is-pulled-right">
                        <VButton icon="lnir lnir-arrow-left rem-100" class="ml-2" light dark-outlined
                          @click="backPage()">
                          Kembali
                        </VButton>
                        <VButton icon="fas fa-times-circle rem-100" class="ml-2" light dark-outlined @click="batal()">
                          Batal
                        </VButton>
                        <VButton type="button" color="warning" class="ml-2" rounded outlined raised
                          icon="feather:printer" @click="cetakSATU(noSuratKontrol)" v-if="noSuratKontrol"
                          :loading="isLoadingCetak">
                          Cetak </VButton>
                        <VButton type="button" color="danger" class="ml-2" rounded outlined raised icon="feather:trash"
                          v-if="noSuratKontrol" @click="hapus(noSuratKontrol)">
                          Hapus </VButton>
                        <VButton type="button" color="primary" class="ml-2" rounded outlined raised icon="feather:save"
                          :loading="isLoading" @click="save()"> {{ input.noSuratKontrol || input.noSuratKontrol !=
                            undefined ?
                            'Edit' : 'Simpan' }} </VButton>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </TabPanel>
          </TabView>
        </div>
      </div>
    </VCard>
  </section>

  <VModal :open="showModalSEP" title="List SEP" :noclose="false" size="large" actions="right"
    @close="showModalSEP = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List SEP</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listSEP.length > 0" border="1">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="10%">Tanggal</td>
                  <td class="tg-0lax text-center" width="15%">Pelayanan</td>
                  <td class="tg-0lax text-center" width="20%">Poli</td>
                  <td class="tg-0lax text-center" width="20%">No SEP</td>
                  <td class="tg-0lax text-center" width="10%">Diagnosa</td>
                  <td class="tg-0lax text-center" width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listSEP">
                <tr>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.tglSep }}</span><br>
                  </td>
                  <td style="width:10%;text-align:center">
                    <span class="mb-2" v-if="resep.jnsPelayanan == 1">Rawat Inap</span><br>
                    <span class="mb-2" v-if="resep.jnsPelayanan == 2">Rawat Jalan</span><br>
                  </td>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.poli }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.noSep }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.diagnosa }}</span><br>
                  </td>
                  <td style="width:10%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addSEP(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <VModal :open="showModalJadwal" title="List Kuota Dokter" :noclose="false" size="large" actions="right"
    @close="showModalJadwal = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">Kuota Dokter</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0" border="1">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="10%">Tanggal</td>
                  <td class="tg-0lax text-center" width="10%">Hari</td>
                  <td class="tg-0lax text-center" width="15%">Poli</td>
                  <td class="tg-0lax text-center" width="20%">Nama Dokter</td>
                  <td class="tg-0lax text-center" width="10%">Waktu</td>
                  <td class="tg-0lax text-center" width="15%">Sisa Antrian Poli</td>
                  <td class="tg-0lax text-center" width="5%">#</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.tanggalpraktek }}</span><br>
                  </td>
                  <td style="width:10%;text-align:center">
                    <span class="mb-2">{{ resep.haripraktek }}</span><br>
                  </td>
                  <td style="width:15%;text-align:center">
                    <span class="mb-2">{{ resep.namaruangan }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ input.kodeDokter?.nama ?? '-' }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.jammulai }} - {{ resep.jamakhir }}</span><br>
                  </td>
                  <td style="width:10%;text-align:center">
                    <span class="mb-2">{{ resep.sisa }}</span><br>
                  </td>
                  <td style="width:10%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <VModal :open="modalDetailRiwayat" size="small" :noclose="false" title="Detail Riwayat Kontrol" actions="right"
    @close="modalDetailRiwayat = false">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-3 no-padding-bottom">
          <img src="/images/simrs/logo_bali.png" style=" height: 60px;width: 60px;" />
        </div>
        <div class="column is-9 no-padding-bottom">
          <h3 class="title is-5 mb-2" style="text-align: left">BUKTI PENDAFTARAN<br>RSUD Bali Mandara</h3>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h5 class="is-5 mb-2">
            Tanggal: {{ H.formatDateToLocalString(selectedDataKontrol.tanggalreservasi) }}
          </h5>
          <h5 class="is-5 mb-2">
            Jam: {{ selectedDataKontrol.jamreservasi }}
          </h5>
        </div>
        <div class="column is-12 no-padding-top" style="height: 50px;">
          <!-- <img src='https://barcode.tec-it.com/barcode.ashx?data={{selectedDataKontrol.nocm}}&code=Code39&dpi=96&dataseparator='
          style=" height: 40px;width: 200px;-webkit-user-select: none;cursor:pointer"/> -->
        </div>
        <div class="column is-12 no-padding-bottom">
          <h5 class="is-5 mb-2">
            <b>{{ selectedDataKontrol.namapasien }} - {{ selectedDataKontrol.kebangsaan }} - {{ selectedDataKontrol.nocm
              }}</b>
          </h5>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h5 class="is-5 mb-2">
            <b>{{ selectedDataKontrol.umur }}</b>
          </h5>
        </div>
        <div class="column is-12 pt-0 mt-5">
          <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px;margin-top: -10px;">
        </div>
        <div class="column is-12 no-padding-bottom">
          <h3 class="title is-5 mb-2" style="text-align: center; margin-top: -20px;">Dokter: {{
            selectedDataKontrol.namadokter ? selectedDataKontrol.namadokter : '-' }}<br><br></h3>
          <h3 class="title is-5 mb-2" style="text-align: center; margin-top: -20px;">No. Reservasi: {{
            selectedDataKontrol.noreservasi }}<br><br></h3>
          <h3 class="title is-5 mb-2" style="text-align: center; margin-top: -20px;">No. Surat Kontrol: {{
            selectedDataKontrol.nosuratkontrol }}<br><br></h3>
          <h3 class="title is-5 mb-2" style="text-align: center; margin-top: -20px;">No. Antrian: {{
            selectedDataKontrol.noantrianpoli }}<br>{{ selectedDataKontrol.poli }}<br>{{
              selectedDataKontrol.kelompokpasien }}</h3>
        </div>
      </div>
    </template>
  </VModal>
  <VModal :open="modalDeleteRiwayat" size="small" actions="center" @close="modalDeleteRiwayat = false">
    <template #content>
      <VPlaceholderSection title="Hapus Riwayat" subtitle="Apakah anda yakin ingin menghapus data ini?" />
    </template>
    <template #action>
      <VButton color="danger" raised @click="deleteKontrol()" :loading="isLoading">
        Confirm
      </VButton>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { useRoute, useRouter, Router, } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import ConfirmDialog from 'primevue/confirmdialog'
import InputText from 'primevue/inputtext';
import { useApi } from '/@src/composable/useApi'
import { FilterMatchMode } from 'primevue/api';
import { useConfirm } from "primevue/useconfirm"
import sleep from '/@src/utils/sleep'
import PasienBaru from '../registrasi/pasien-baru.vue'

useHead({
  title: 'Rencana Kontrol - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const route = useRoute()
let NOREC_PD = useRoute().query.norec_pd as string
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
  }
)




const activeIdx: any = ref(0)
const namappkRumahSakit = ref('')
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const d_Filter: any = ref([{ kode: 2, nama: 'Tgl Rencana Kontrol' }, { kode: 1, nama: 'Tgl Entri' }])
const filterTanggal: any = reactive({
  fPeriode: [
    new Date(),
    new Date()
  ]
})
let input: any = ref({
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
    periode: [
      new Date(),
      new Date()
    ]
  }),
  tglAwal: new Date(),
  tglAkhir: new Date(),
  filter: d_Filter.value[0],
  isReservasiBaru: false,
  diagnosaakhir: '-',
  indikasikontrol: '-',
  catatan: '-',
  terapi: '-',
  jenis: 2,
  reservasi: 2,
  noKartu: '',
  noSEP: '',
  tglRencanaKontrol: new Date(),
})

const noReservasi = ref()
const noSuratKontrol = ref()
let tglRujukan = ''
let isLoadingNIK = ref(false)
let isLoadingBPJS = ref(false)
const isLoadingPasien = ref(false)
const dissep = ref(false)
const item: any = ref({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
})
const isLoadingSKD: any = ref(false)
const isLoadingRiwayat: any = ref(false)
const pasien: any = ref({})
const registrasi: any = ref({})
const peserta: any = ref({
  hakKelas: {},
  jenisPeserta: {},
  mr: {},
  provUmum: {},
  statusPeserta: {},
  cob: {}
})
const sep: any = ref({})
const isLoadingCetak: any = ref(false)
const isLoading: any = ref(false)
const router = useRouter()
const isPasienFound = ref(false);
const d_Bulan: any = ref(H.monthList())
const d_Tahun: any = ref(H.yearList())
const d_Subspesialis: any = ref([])
const d_dpjpLayan: any = ref([])
const d_KelompokPasien: any = ref([])
const d_Rekanan: any = ref([])
const dataSourceSPRI: any = ref([])
const modalDetailRiwayat: any = ref(false);
const modalDeleteRiwayat: any = ref(false);
const showModalJadwal: any = ref(false)
const showModalSEP: any = ref(false)
let onTypingFilter: any = ref();
const listTemplate: any = ref([])
const listSEP: any = ref([])
const resSaveRiwayat: any = ref();
const parameter: any = ref({
  nocmfk: '',
  norec_pd: '',
})

console.log(parameter)

let d_JK: any = ref([])
let d_Agama: any = ref([])
let d_GolonganDarah: any = ref([])
let d_StatusPerkawinan: any = ref([])
let d_Pendidikan: any = ref([])
let d_Pekerjaan: any = ref([])
let d_Etnis: any = ref([])
let d_HubunganPasien: any = ref([])
let d_Kebangsaan: any = ref([])
let d_Negara: any = ref([])
let d_Kelurahan: any = ref([])
let d_Kecamatan: any = ref([])
let d_KotaKabupaten: any = ref([])
let d_Provinsi: any = ref([])
let d_Ruangan: any = ref([])
let d_Dokter: any = ref([])

let dataKontrol: any = ref([]);
let modelSelectedKontrol: any = ref('');
let dataKontrol_FILTER: any = ref({
  noreservasi: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  tanggalreservasi: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  jamreservasi: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  nocm: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  type: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  namapasien: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  noantrianpoli: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  namaruangan: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  namadokter: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  tanggalreservasi: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  jenis: { value: null, filterMatch: FilterMatchMode.CONTAINS },
})
let selectedDataKontrol: any = ref({});
const confirm = useConfirm();

const cariSEP = () => {

  let now = new Date()
  let dari = new Date(now.setDate(now.getDate() - 90))
  let json = {
    "url": "monitoring/HistoriPelayanan/NoKartu/" + pasien.value.nobpjs + "/tglMulai/" + H.formatDate(dari, 'YYYY-MM-DD') + "/tglAkhir/" + H.formatDate(new Date(), 'YYYY-MM-DD'),
    "jenis": "monitoring",
    "method": "GET",
    "data": null
  }

  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false
    if (x.metaData.code == 200) {
      console.log("data X", x);
      input.value.jenis = 2;
      input.value.noKartu = pasien.value.nobpjs;
      if (x.response.histori.length > 0) {
        listSEP.value = x.response.histori
        showModalSEP.value = true
        // let lastSEP = x.response.histori[0]
        // console.log('Last SEP', lastSEP)
        // input.value.noSEP = lastSEP.noSep;
        // input.value.
        // cariPasien();
      }
    } else {
      H.alert('error', x.metaData.message)
    }
  })

}

const checkin = async (e: any) => {
  console.log(e)
  if (e.type == 'BARU') {
    let json = {
      'pasien': {
        'id': '',
        'isPenunjang': false,
        'isJenazah': false,
        'isbayi': false,
        'nocmfkibu': null,
        'noidentitas': e.noidentitas,
        'nobpjs': e.nobpjs,
        'namapasien': e.namapasien,
        'tempatlahir': null,
        'tgllahir': H.formatDate(new Date(e.tgllahir), 'YYYY-MM-DD'),
        'objectjeniskelaminfk': e.objectjeniskelaminfk,
        'nohp': e.notelepon,
        'objectagamafk': e.objectagamafk != undefined ? e.objectagamafk : null,
        'email': e.email,
        'namaibu': null,
        'kode_pasien_baru': null,
        'objectstatusperkawinanfk': null,
        'objectgolongandarahfk': null,
        'objectpendidikanfk': null,
        'objectpekerjaanfk': null,
        'objectsukufk': null,
        'noaditional': null,
        'notelepon': e.notelepon,
        'namaayah': null,
        'namakeluarga': null,
        'namasuamiistri': null,
        'penanggungjawab': null,
        'hubungankeluargapj': null,
        'telponpenanggungjawab': null,
        'bahasa': null,
        'jeniskelaminpenanggungjawab': null,
        'umurpenanggungjawab': null,
        'pekerjaanpenangggungjawab': null,
        'alamatrmh': null,
        'objectkebangsaanfk': e.objectkebangsaanfk,
        'objectnegarafk': null,
        'progress': 0,
        'isReservasi': true,
        'antrianpasienregistrasifk': null,
        'norecEMR': null,
        'isIGD': false,
      },
      'alamat': {
        'alamatlengkap': e.alamatlengkap,
        'rtrw': null,
        'objectpropinsifk': null,
        'objectkotakabupatenfk': null,
        'objectkecamatanfk': null,
        'objectdesakelurahanfk': null,
        'kodepos': null,
      }
    }

    useApi().post('registrasi/save-pasien', json).then((x) => {
      e.nocmfk = x.data.id
      let antrian = {
        "jenis": e.jenis,
        "ruanganfk": e.objectruanganfk,
        "namaruangan": e.namaruangan,
        "nocmfk": e.nocmfk,
        "loketid": 3,
        "nopeserta": null,
        "namapeserta": e.namapasien,
        "kelompokpasien": e.idkelompokpasien,
        "objectpegawaifk": e.objectpegawaifk,
        "jenispelayanan": 1,
        "norec": e.antrianpasienregistrasifk,
        "noantrianpoli": e.noantrianpoli
      }

      useApi().post('medifirst2000/kiosk/save-antrian-kanker', antrian).then((x) => {
        router.push({
          name: 'module-dashboard-registrasi',
          query: {}
        })
      })
    })
  } else {
    let antrian = {
      "jenis": e.jenis,
      "ruanganfk": e.objectruanganfk,
      "namaruangan": e.namaruangan,
      "nocmfk": e.nocmfk,
      "loketid": 3,
      "nopeserta": null,
      "namapeserta": e.namapasien,
      "kelompokpasien": e.idkelompokpasien,
      "objectpegawaifk": e.objectpegawaifk,
      "jenispelayanan": 1,
      "norec": e.antrianpasienregistrasifk,
      "noantrianpoli": e.noantrianpoli
    }

    useApi().post('medifirst2000/kiosk/save-antrian-kanker', antrian).then((x) => {
      router.push({
        name: 'module-dashboard-registrasi',
        query: {}
      })
    })
  }

}

const cariRM = () => {
  isLoading.value = true
  delete input.value
  useApi().get(`/registrasi/pasien-hari-ini-reservasi?nocm=${input.value.noRM}`).then((responseX: any) => {
    if (responseX != null) {
      if (responseX.nocmfk != null) {
        parameter.value.nocmfk = responseX.nocmfk
      }
      if (responseX.norec_pd != null) {
        parameter.value.norec_pd = responseX.norec_pd
        useApi().get(
          `/emr/header-pasien?nocmfk=${parameter.value.nocmfk}&norec_pd=${parameter.value.norec_pd}`).then((response: any) => {
            isLoading.value = false

            pasien.value = response.pasien

            if (response.pasien) {
              input.value.nik = response.pasien.noidentitas
              input.value.nobpjs = response.pasien.nobpjs
              input.value.namapasien = response.pasien.namapasien
              input.value.tempatlahir = response.pasien.tempatlahir
              input.value.tgllahir = response.pasien.tgllahir
              input.value.nohp = response.pasien.nohp
              input.value.jenisKelamin = response.pasien.objectjeniskelaminfk
              input.value.agama = response.pasien.objectagamafk
              input.value.namaIbu = response.pasien.namaibu;
              input.value.kebangsaan = response.pasien.objectkebangsaanfk
              input.value.negara = response.pasien.objectnegarafk


              input.value.alamat = response.pasien.alamatlengkap

              if (response.registrasi.length > 0) {
                registrasi.value = response.registrasi[0]
                useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
                  d_KelompokPasien.value = response.kelompokpasien
                  d_KelompokPasien.value.forEach(elO => {
                    // console.log('kelompok pasien', elO.value)
                    // console.log('kelompok pasien last', registrasi.value.objectkelompokpasienlastfk)
                    if (elO.id == registrasi.value.objectkelompokpasienlastfk) {
                      console.log('ada yang sama')
                      input.value.kelompokpasienold = elO
                    }
                  });
                });

                // useApi().get(`/registrasi/penjamin-by-kelompokpasien?id=${registrasi.value.objectkelompokpasienlastfk}`)
                // .then((response: any) => {
                //   input.value.rekanan = response[0]
                // })


                // useApi().get(`emr/auto-fill?nocmfk=${parameter.value.nocmfk}&norec_pd=${parameter.value.norec_pd}&collection=AsesmenMedisRawatJalan&field=TADiagnosa`).then((response) => {
                //     if (response != null) {
                //     input.value.diagnosaakhir = response.TADiagnosa
                //     } else{
                //       useApi().get(`emr/auto-fill?nocmfk=${parameter.value.nocmfk}&norec_pd=${parameter.value.norec_pd}&collection=CPPTDetail&field=S,O,A,P`).then((response) => {
                //         input.value.diagnosaakhir = response.A
                //       })
                //     }
                // });

                // useApi().get(`farmasi/riwayat-order-resep?norec_pd=${parameter.value.norec_pd}`).then((resObat) => {
                //     console.log('ini obat', resObat)
                //     if(resObat.length > 0) {
                //         isLoading.value = false;
                //         let stringObat = ''
                //         resObat[0].details.forEach(elO => {
                //             stringObat += `# ${elO.namaproduk} \n`;
                //         });

                //         input.value.terapi = stringObat;
                //     } else{
                //         input.value.terapi = '-'
                //     }
                // })
                //let resObat = useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${parameter.value.norec_pd}`)



                let url = `/registrasi/list-ruangan-rawat-jalan-semua`;

                useApi().get(url).then((res: any) => {
                  d_Subspesialis.value = res.ruangan_RJ;
                  d_Subspesialis.value.forEach(element => {
                    if (element.id == registrasi.value.objectruanganlastfk) {
                      input.value.poliKontrol = element
                    }
                  });
                });

                useApi().get(`/registrasi/dokter-paging`).then((response: any) => {
                  d_dpjpLayan.value = response.dokter
                  d_dpjpLayan.value.forEach(e => {
                    if (e.id == registrasi.value.objectpegawaifk) {
                      input.value.kodeDokter = e
                    }
                  });
                })

                // if(registrasi.value.objectkelompokpasienlastfk == 2){
                //   let json = {
                //       "url": "monitoring/HistoriPelayanan/NoKartu/" + pasien.value.nobpjs + "/tglMulai/" + H.formatDate(new Date(), 'YYYY-MM-DD') + "/tglAkhir/" + H.formatDate(new Date(), 'YYYY-MM-DD'),
                //       "jenis": "monitoring",
                //       "method": "GET",
                //       "data": null
                //   }
                //   useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
                //   isLoadingSKD.value = false
                //       if (x.metaData.code == 200) {
                //           console.log("data X", x);
                //           input.value.jenis = 2;
                //           input.value.noKartu = pasien.value.nobpjs;
                //           if (x.response.histori.length > 0) {
                //           let lastSEP = x.response.histori[0]
                //           console.log('Last SEP', lastSEP)
                //           input.value.noSEP = lastSEP.noSep;
                //           // input.value.
                //           // cariPasien();
                //           }
                //       } else {
                //           H.alert('error', x.metaData.message)
                //       }
                //   })
                // }

              }
            }
          })
      } else {
        useApi().get(
          `/emr/header-pasien?nocmfk=${parameter.value.nocmfk}&norec_pd=`).then((response: any) => {
            isLoading.value = false
            pasien.value = response.pasien
            input.value.terapi = '-'
            input.value.diagnosaakhir = '-'

            if (response.pasien) {
              input.value.nik = response.pasien.noidentitas
              input.value.nobpjs = response.pasien.nobpjs
              input.value.namapasien = response.pasien.namapasien
              input.value.tempatlahir = response.pasien.tempatlahir
              input.value.tgllahir = response.pasien.tgllahir
              input.value.nohp = response.pasien.nohp
              input.value.jenisKelamin = response.pasien.objectjeniskelaminfk
              input.value.agama = response.pasien.objectagamafk
              input.value.kebangsaan = response.pasien.objectkebangsaanfk
              input.value.negara = response.pasien.objectnegarafk


              input.value.alamat = response.pasien.alamatlengkap
            }
          })
      }
    }
  })

}


// useApi().get(
//     `/registrasi/pasien-registrasi`).then((response: any) => {
//       pasien.value = response.pasien
//       d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
//     })

async function listDropdown() {

  const response = await useApi().get(
    `/registrasi/list-dropdown`)
  d_JK.value = []
  for (let x = 0; x < response.jk.length; x++) {
    const element = response.jk[x];
    if (element.jeniskelamin != '-') {
      d_JK.value.push(element)
    }
  }
  d_Agama.value = response.agama.map((e: any) => { return { label: e.agama, value: e.id, default: e } })
  d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e } })
  d_HubunganPasien.value = response.hubunganpasien.map((e: any) => { return { label: e.hubungankeluarga, value: e.id } })
  d_StatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e.id, default: e } })
  d_Pendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id, default: e } })
  d_Pekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id, default: e } })
  d_Etnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e.id, default: e } })
  d_Kebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id, default: e } })
  d_Negara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e.id, default: e } })
  // d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e } })
  d_Provinsi.value = response.provinsi.map((e: any) => { return { label: e.namapropinsi, value: e.id, default: e } })

  for (let x = 0; x < response.negara.length; x++) {
    const element = response.negara[x];
    if (element.namanegara.toLowerCase() == 'indonesia') {
      input.negara = element.id
      break
    }
  }
  for (let x = 0; x < response.kebangsaan.length; x++) {
    const element = response.kebangsaan[x];
    if (element.name.toLowerCase() == 'wni') {
      input.kebangsaan = element.id
      break
    }
  }
  if (ID_PASIEN) {
    ID_PASIEN_SET.value = ID_PASIEN
    pasienByID(ID_PASIEN)
  }
  if (route.query.noreservasi) {
    pasienReservasi(route.query)
  }


}

const editSurkon = async () => {

  input.value.noRM = selectedDataKontrol.value.nocm
  cariRM()

  console.log(selectedDataKontrol.value)
  useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
    d_KelompokPasien.value = response.kelompokpasien
    d_KelompokPasien.value.forEach(elO => {
      if (elO.id == selectedDataKontrol.value.idkelompokpasien) {
        input.value.kelompokpasienold = elO
      }
    });
  });


  input.value.tglRencanaKontrol = selectedDataKontrol.value.tglkontrol
  input.value.jam = selectedDataKontrol.value.jam
  input.value.diagnosaakhir = selectedDataKontrol.value.diagnosaakhir
  input.value.terapi = selectedDataKontrol.value.terapi
  input.value.catatan = selectedDataKontrol.value.catatan
  input.value.indikasikontrol = selectedDataKontrol.value.indikasikontrol
  input.value.norec_apr = selectedDataKontrol.value.norec_apr
  input.value.norec_rk = selectedDataKontrol.value.norec

  let url = `/registrasi/list-ruangan-rawat-jalan-semua`;

  useApi().get(url).then((res: any) => {
    d_Subspesialis.value = res.ruangan_RJ;
    d_Subspesialis.value.forEach(element => {
      if (element.id == selectedDataKontrol.value.objectruanganfk) {
        input.value.poliKontrol = element
      }
    });
  });

  useApi().get(`/registrasi/dokter-paging`).then((response: any) => {
    d_dpjpLayan.value = response.dokter
    d_dpjpLayan.value.forEach(e => {
      if (e.id == selectedDataKontrol.value.objectpegawaifk) {
        input.value.kodeDokter = e
      }
    });
  })


  if (selectedDataKontrol.value.idkelompokpasien == 2) {

    input.value.noSEP = selectedDataKontrol.value.nosepasal;

    let json2 = {
      "url": `/RencanaKontrol/nosep/${input.value.noSEP}`,
      "method": "GET",
      "data": null
    }

    useApi().postBPJS('/bridging/bpjs/tools', json2).then((x) => {
      if (x.metaData.code == 200) {
        isPasienFound.value = true;
        sep.value = x.response
        if (x.response.provPerujuk) {
          if (x.response.provPerujuk.tglRujukan) {
            let today = new Date(); // Get today's date
            let form_date = new Date(x.response.provPerujuk.tglRujukan)
            let difference = Math.abs(today.getTime() - form_date.getTime());
            let diff_days = Math.floor(90 / (1000 * 3600 * 24))
            let masarujukan = today.setDate(today.getDate() + diff_days)
            let options = { day: 'numeric', month: 'long', year: 'numeric' };
            let formattedDate = H.formatDate(new Date(new Date(x.response.provPerujuk.tglRujukan).setDate(new Date(x.response.provPerujuk.tglRujukan).getDate() + 90)), 'YYYY-MM-DD')
            // new Intl.DateTimeFormat('id-ID', options).format(H.formatDate(new Date(new Date(x.response.provPerujuk.tglRujukan).setDate(new Date(x.response.provPerujuk.tglRujukan).getDate() + 90)), 'YYYY-MM-DD'));
            // H.formatDate(new Date(new Date(x.response.provPerujuk.tglRujukan).setDate(new Date(x.response.provPerujuk.tglRujukan).getDate() + 90)), 'YYYY-MM-DD')
            sep.value.masaRujukan = Intl.DateTimeFormat('id-ID', options).format(new Date(formattedDate));
            // formattedDate.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });;

            // sep.masaRujukan
          }
        }
      } else {
        isLoadingPasien.value = false;
        H.alert('error', x.metaData.message)
      }
    })






  }


  activeIdx.value = 1
}

async function cariBPJS(params: any) {
  console.log(input.value.nik.length)
  if (params == 'nik') {
    if (input.value.nik.length != 16) {
      H.alert('info', 'Panjang NIK harus 16 digit')
      return
    }
    isLoadingNIK.value = true

    let json = {
      "url": `Peserta/nik/${input.value.nik}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
      "method": "GET",
      "data": null
    }
    await useApi().post(
      `/bridging/bpjs/tools`, json).then((e: any) => {
        isLoadingNIK.value = false
        if (e.peserta) {
          input.value.namapasien = e.peserta.nama
          input.value.nobpjs = e.peserta.noKartu
          input.value.tgllahir = new Date(e.peserta.tglLahir)
          item.nohp = e.peserta.mr.noTelepon
          if (e.peserta.sex.toUpperCase() === "L") {
            input.value.jenisKelamin = 1
          }
          if (e.peserta.sex.toUpperCase() === "P") {
            input.value.jenisKelamin = 2
          }
        } else {
          H.alert('info', e.metaData.message)
          console.log(e.metaData.message)
        }
      })

  }
  if (params == 'nobpjs') {
    isLoadingBPJS.value = true

    let json = {
      "url": `Peserta/nokartu/${input.value.nobpjs}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
      "method": "GET",
      "data": null
    }
    await useApi().post(
      `/bridging/bpjs/tools`, json).then((e: any) => {
        isLoadingBPJS.value = false
        if (e.peserta) {
          input.value.namapasien = e.peserta.nama
          input.value.nik = e.peserta.nik
          input.value.tgllahir = new Date(e.peserta.tglLahir)
          item.nohp = e.peserta.mr.noTelepon
          if (e.peserta.sex.toUpperCase() === "L") {
            input.value.jenisKelamin = 1
          }
          if (e.peserta.sex.toUpperCase() === "P") {
            input.value.jenisKelamin = 2
          }
        } else {
          H.alert('info', e.metaData.message)
          console.log(e.metaData.message)
        }
      })

  }
}

const changeKelompok = async (e: any) => {
  d_Rekanan.value = []

  delete item.rekanan
  if (e) {
    await useApi().get(
      `/registrasi/penjamin-by-kelompokpasien?id=${e}`)
      .then((response: any) => {
        if (response.length > 0) {
          d_Rekanan.value = response.map((e: any) => { return { label: e.namarekanan, value: e.id, default: e } })
          item.rekanan = response[0].id
          if (response.length == 1) {

          }
          console.log(item.rekanan)
        }
      })
      .catch((error: any) => { })
  }

}


const cariSKD = () => {
  // let bulan = input.value.bulan.kode + 1

  // bulan = bulan.toString().length == 1 ? '0' + bulan.toString() : bulan
  let json = {
    // "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${input.value.tahun.kode}/Nokartu/${input.value.nobpjs}/filter/${input.value.filter.kode}`,
    "url": `RencanaKontrol/ListRencanaKontrol/tglAwal/${H.formatDate(input.value.filterTgl.start, 'YYYY-MM-DD')}/tglAkhir/${H.formatDate(input.value.filterTgl.end, 'YYYY-MM-DD')}/filter/${input.value.filter.kode}`,
    "method": "GET",
    "data": null
  }
  isLoadingSKD.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoadingSKD.value = false
    if (x.metaData.code == 200) {
      dataSourceSPRI.value = x.response.list
    } else {
      H.alert('error', x.metaData.message)
    }
  })

}

const pilihJadwal = async (e: any) => {
  if (!input.value.poliKontrol) {
    H.alert('warning', 'Pilih Spesialis Terlebih Dahulu');
    return
  }
  if (!input.value.kodeDokter) {
    H.alert('warning', 'Pilih Dokter Terlebih Dahulu');
    return
  }

  console.log('POLI', input.value.poliKontrol)

  let dokter = ''
  if(input.value.poliKontrol.namaruangan.indexOf('SORE') > -1){
    dokter = input.value.kodeDokter.id
  }

  console.log(registrasi.value)
  isLoading.value = true
  useApi().get(`/medifirst2000/kiosk/get-dokterbyruangan-semuatgl?objectruanganfk=${input.value.poliKontrol.id}&dokter=${dokter}`).then((response: any) => {
    isLoading.value = false
    listTemplate.value = response
    showModalJadwal.value = true
  });
}

async function changeProvinsi(event: any) {
  d_KotaKabupaten.value = []
  let query = event == '' ? '' : event;
  isLoading.value = true

  const response = await useApi().get(
    `/registrasi/kotakabupaten?provfk=${query}`)
  isLoading.value = false

  d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e.id, default: e } })

}
async function changeKota(event: any) {
  d_Kecamatan.value = []
  let query = event == '' ? '' : event;
  isLoading.value = true

  const response = await useApi().get(
    `/registrasi/kecamatan?kotafk=${query}`)
  isLoading.value = false

  d_Kecamatan.value = response.kecamatan.map((e: any) => { return { label: e.namakecamatan, value: e.id, default: e } })

}
async function changeKecamatan(event: any) {
  d_Kelurahan.value = []
  let query = event == '' ? '' : event;
  isLoading.value = true

  const response = await useApi().get(
    `/registrasi/desakelurahan?kecfk=${query}`)
  isLoading.value = false

  d_Kelurahan.value = response.desa.map((e: any) => { return { label: e.namadesakelurahan, value: e.id, default: e } })

}
function changeDesa(event: any) {
  input.kodePos = event.kodepos
}

const cetakSATU = async (e: any) => {
  let bulan: any = new Date(input.value.tglRencanaKontrol).getMonth() + 1
  if (bulan.length == 1) {
    bulan = '0' + bulan
  }
  let json = {
    "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${new Date(input.value.tglRencanaKontrol).getFullYear()}/Nokartu/${input.value.noKartu}/filter/${2}`,
    "method": "GET",
    "data": null
  }
  isLoadingCetak.value = true
  let res = await useApi().postBPJS('/bridging/bpjs/tools', json)
  isLoadingCetak.value = false
  if (res.metaData.code == 200) {
    let stt = false
    for (let x = 0; x < res.response.list.length; x++) {
      const element = res.response.list[x];
      if (element.noSuratKontrol == e) {
        stt = true
        cetak(element)
        console.log(element);
        break
      }
    }
    if (!stt) {
      H.alert('error', 'Data tidak ada');
    }
  } else {
    H.alert('error', res.metaData.message);
  }


}
const edit = async (e: any) => {
  if (e.terbitSEP == 'Sudah') {
    H.alert('error', 'SEP sudah terbit tidak bisa di edit');
    return
  }
  activeIdx.value = 1
  await sleep(2000)
  if (e.namaJnsKontrol == 'SPRI') {
    input.value.jenis = 1
    input.value.noKartu = e.noKartu
  } else {
    input.value.jenis = 2
    input.value.noSEP = e.noSepAsalKontrol
    input.value.noKartu = e.noKartu
  }


  input.value.noSuratKontrol = e.noSuratKontrol
  noSuratKontrol.value = e.noSuratKontrol
  input.value.tglRencanaKontrol = new Date(e.tglRencanaKontrol)

  await fetchSupspesialis({ query: e.namaPoliTujuan })
  for (let x = 0; x < d_Subspesialis.value.length; x++) {
    const element = d_Subspesialis.value[x];
    if (element.nama == e.namaPoliTujuan) {
      input.value.poliKontrol = element
      await changeSpe(element)
      break
    }
  }
  d_dpjpLayan.value.forEach(element => {
    if (element.kodeDokter == e.kodeDokter) {
      input.value.kodeDokter = element
    }
  });

  cariPasien()
}
const cetak = async (e: any) => {
  // let json = {
  //   "url": `/RencanaKontrol/noSuratKontrol/${nosurat}`,
  //   "method": "GET",
  //   "data": null
  // }
  let json = {
    "url": `Peserta/nokartu/${e.nokartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  isLoadingCetak.value = true
  let response = await useApi().postBPJS('/bridging/bpjs/tools', json)
  isLoadingCetak.value = false
  let nosuratkontrol = e.nosurat
  let tglrencanakontrol = e.tglkontrol
  let txttglentrirencanakontrol = e.tglentry
  let noka = e.nokartu
  let nama = e.namapasien
  let tgllahir = e.tgllahir

  let namaPoliTujuan = e.poli
  let jeniskelamin = e.jeniskelamin
  let jnsKontrol = e.jnsKontrol
  let namaDokter = e.namadokter
  let kddx = '-'
  let nmdpjpsepasal = '-';// e.namaDokter ? e.namaDokter : '-'
  let iddok = 'null'
  let dxawal = '-'

  if (e.nosepasal != null) {
    let json = {
      "url": "sep/" + e.nosepasal,
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
        H.alert('error', x.metaData.message);
      }
    })

  } else {
    dxawal = '-'
    cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
      nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
      jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);

  }
}
const cetakBladeSKDP = (nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
  nama, tgllahir, namappkRumahSakit, namaPoliTujuan, jeniskelamin, dxawal, jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok) => {


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

const addSEP = (response: any) => {
  console.log(response)
  input.value.noSEP = response.noSep
  showModalSEP.value = false
}

const addTemplate = (response: any) => {
  console.log(response)
  input.value.tglRencanaKontrol = response.tanggal
  input.value.jam = response.jammulai + ' - ' + response.jamakhir
  for (let y = 0; y < d_dpjpLayan.value.length; y++) {
    const elements = d_dpjpLayan.value[y];
    if (elements.id == response.iddokter) {
      input.value.kodeDokter = elements
    }
  }
  for (let x = 0; x < d_Subspesialis.value.length; x++) {
    const element = d_Subspesialis.value[x];
    if (element.id == response.objectruanganfk) {
      input.value.poliKontrol = element
    }
  }
  showModalJadwal.value = false
  // input.value.poliKontrol = response.objectruanganfk
  // input.value.kodeDokter = response.iddokter
}

const cariPasien = () => {

  // if (input.value.jenis == 1) {
  //   input.value.pelayanan = 'Rawat Inap'
  //   if (!input.value.noKartu) {
  //     H.alert('error', 'Nomor Kartu tidak boleh kosong')
  //     return;
  //   }
  // } else {
  //   input.value.pelayanan = 'Rawat Jalan'
  //   if (!input.value.noSEP) {
  //     H.alert('error', 'Nomor SEP tidak boleh kosong')
  //     return;
  //   }
  // }
  isLoadingPasien.value = true;
  // Preventing if the data not found and form still exist
  peserta.value = {}
  let json: any = {}
  sep.value = {}
  console.log("IS LOADING PASIEN", isLoadingPasien.value);

  if (input.value.jenis == 1) {
    json = {
      "url": `Peserta/nokartu/${input.value.noKartu.trim()}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
      "method": "GET",
      "data": null
    }
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      isLoadingPasien.value = false
      if (x.metaData.code == 200) {
        peserta.value = x.response.peserta
        isPasienFound.value = true;

      } else {
        H.alert('error', x.metaData.message)
      }
    })
  } else {
    json = {
      "url": `/RencanaKontrol/nosep/${input.value.noSEP.trim()}`,
      "method": "GET",
      "data": null
    }

    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      if (x.metaData.code == 200) {
        isPasienFound.value = true;
        sep.value = x.response
        if (x.response.provPerujuk) {
          if (x.response.provPerujuk.tglRujukan) {
            let today = new Date(); // Get today's date
            let form_date = new Date(x.response.provPerujuk.tglRujukan)
            let difference = Math.abs(today.getTime() - form_date.getTime());
            let diff_days = Math.floor(difference / (1000 * 3600 * 24))
            let masarujukan = today.setDate(today.getDate() + diff_days)
            let options = { day: 'numeric', month: 'long', year: 'numeric' };
            let formattedDate = new Intl.DateTimeFormat('id-ID', options).format(masarujukan);
            sep.value.masaRujukan = formattedDate;

            // sep.masaRujukan
          }
        }
        let json2 = {
          "url": `Peserta/nokartu/${x.response.peserta.noKartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
          "method": "GET",
          "data": null
        }
        useApi().postBPJS('/bridging/bpjs/tools', json2).then((xx) => {
          isLoadingPasien.value = false
          if (xx.metaData.code == 200) {
            peserta.value = xx.response.peserta

          } else {
            H.alert('error', xx.metaData.message)
          }
        })
      } else {
        isLoadingPasien.value = false;
        H.alert('error', x.metaData.message)
      }
    })

  }


}

const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/registrasi/dropdown`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
}
// const fetchdDropdown = async (filter: { query?: string } = {}) => {
//   const response = await useApi().get(`/dashboard/registrasi/dropdown`);
//   d_Ruangan.value = response.ruangan.map((e: any) => {
//     return { label: e.namaruangan, value: e.id, default: e };
//   });
// }
const fetchDokter = async (filter: any) => {
  const dokterResponse = await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`);
  d_Dokter.value = dokterResponse;
}

const fetchSubSpe = () => {



  // isLoadingPasien.value = true
  // if(input.value.reservasi == 1){
  //   let nomor = ''
  //   if (input.value.jenis == 1) {
  //     nomor = input.value.noKartu
  //   } else {
  //     nomor = input.value.noSEP
  //   }
  //   let json = {
  //     "url": `/RencanaKontrol/ListSpesialistik/JnsKontrol/${input.value.jenis}/nomor/${nomor}/TglRencanaKontrol/${H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD')}`,
  //     "method": "GET",
  //     "data": null
  //   }


  //   useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
  //     if (x.metaData.code == 200) {
  //       d_Subspesialis.value = x.response.list

  //     } else {
  //       H.alert('error', x.metaData.message)
  //     }
  //   })
  // } else{
  useApi().get(
    `/registrasi/pasien-registrasi`).then((response: any) => {
      d_RuanganRJ.value = response.ruangan_RJ.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    })

  const response = useApi().get(
    `/registrasi/dokter-paging?limit=10`)
  d_dpjpLayan.value = response.dokter
  // }

}
const fetchSupspesialis = async (filter: any) => {
  let query = '';
  if (filter != undefined) {
    query = filter.query;
  }

  let url = `/registrasi/list-ruangan-rawat-jalan?query=${query}`;

  await useApi().get(url).then((res: any) => {
    d_Subspesialis.value = res.ruangan_RJ;
  });
}
const fetchPembiayaan = async (filter: any) => {
  let query = '';
  if (filter != undefined) {
    query = filter.query;
  }

  useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
    d_KelompokPasien.value = response.kelompokpasien
  });
}
const changeSpe = async (filter: any) => {
  // if(input.value.reservasi == 1){
  //   if (!filter.query) return
  //   if (filter.query.length < 4) return

  //   let url = `/pasien/get-dokter-kontrol?search=${filter.query}`;

  //   useApi()
  //   .get(url)
  //   .then((res: any) => {
  //     d_dpjpLayan.value = res;
  //   })
  // } else{
  let query = '';
  // console.log(filter);
  if (filter != undefined) {
    query = filter.query;
  }

  useApi().get(
    `/registrasi/dokter-paging?name=${query}&limit=25`).then((response: any) => {
      d_dpjpLayan.value = response.dokter
    })
  // }

}
const save = () => {
  let msgErr = '';
  console.log(registrasi.value.kelompokpasien);
  if (registrasi.value && registrasi.value.kelompokpasien !== "UMUM/PRIBADI") {
    // if (!input.value.noKartu && !input.value.noSEP) {
    //   msgErr = 'Nomor SEP atau Nomor Kartu tidak boleh kosong';
    //   H.alert('error', msgErr)
    //   return;
    // }
    // if (!isPasienFound.value) {
    //   msgErr = 'Silahkan cari pasien terlebih dahulu';
    //   H.alert('error', msgErr)
    //   return;
    // }
  }
  console.log('INPUT VALUE', input.value)
  console.log('kelompok pasien', input.value.kelompokpasienold)
  if (!input.value.surkon && !input.value.reservasi) {
    msgErr = 'Silahkan pilih tujuan pembuatan Kontrol';
    H.alert('error', msgErr)
    return;
  }
  if (input.value.kelompokpasienold == undefined || input.value.kelompokpasienold == null || input.value.kelompokpasienold == '') {
    msgErr = 'Pembiayaan tidak boleh kosong';
    H.alert('error', msgErr)
    return;
  }

  if (input.value.isReservasiBaru == true) {
    if (!input.value.nik) { H.alert('warning', 'NIK harus di isi'); return }
    if (!input.value.namapasien) { H.alert('warning', 'Nama harus di isi'); return }
    if (!input.value.tempatlahir) { H.alert('warning', 'Tempat Lahir harus di isi'); return }
    if (!input.value.tgllahir) { H.alert('warning', 'Tgl Lahir harus di isi'); return }
    if (!input.value.jenisKelamin) { H.alert('warning', 'Jenis Kelamin harus di isi'); return }
    if (!input.value.kebangsaan) { H.alert('warning', 'Kebangsaan harus di isi'); return }
    if (!input.value.alamat) { H.alert('warning', 'Alamat Lengkap harus di isi'); return }
    if (!input.value.nohp) { H.alert('warning', 'No HP harus di isi'); return }
  }

  if (input.value.reservasi == true) {
    isLoading.value = true
    input.value.nocmfk = parameter.value.nocmfk
    input.value.kelompokpasien = input.value.kelompokpasienold.id
    useApi().post('pasien/create-riwayat-kontrol', input.value).then(async (x) => {
      isLoading.value = false
      console.log(x);
      selectedDataKontrol.value = x.data[0]
      resSaveRiwayat.value = x.data[0];
      selectedDataKontrol.value.namadokter = input.value.kodeDokter ? input.value.kodeDokter.namalengkap : ''
      noReservasi.value = x.data[0].noreservasi
      modalDetailRiwayat.value = true
      if(input.value.kelompokpasien != 2) {
        await sendAntrol();
      }
      batal()
      if (x.metaData.code == 200) {
        // H.alert('success', x.metaData.message)
        activeIdx.value = 1
        reloadData();
        // noSuratKontrol.value = x.response.noSuratKontrol
      } else {
        isLoading.value = false
        H.alert('error', x.metaData.message)
      }
    }).catch((res) => {
      isLoading.value = false
    })
  }
  if (input.value.surkon == true) {
    if (input.value.jenis == 1) {
      insertSPRI()
    } else {
      insertRencanaKontrol()
    }
  }

  // sendAntrol();

}

const insertRencanaKontrol = () => {
  modalDetailRiwayat.value = false

  console.log(input.value.kodeDokter)
  let json = {}

  if (input.value.norec_apr != null) {
    if (selectedDataKontrol.value.nosuratkontrol != null) {
      json = {
        "url": `/RencanaKontrol/Update`,
        "method": "PUT",
        "data": {
          "request": {
            "noSuratKontrol": selectedDataKontrol.value.nosuratkontrol,
            "noSEP": input.value.noSEP,
            "kodeDokter": input.value.kodeDokter.kddokterbpjs,
            "poliKontrol": input.value.poliKontrol.noruangan,
            "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
            "user": H.namaPegawai()
          }
        }
      }
    } else {
      json = {
        "url": `/RencanaKontrol/insert`,
        "method": "POST",
        "data": {
          "request": {
            "noSEP": input.value.noSEP,
            "kodeDokter": input.value.kodeDokter.kddokterbpjs, // ?? 260031,
            "poliKontrol": input.value.poliKontrol.noruangan,
            "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
            "user": H.namaPegawai()
          }
        }
      }
    }
  } else {
    json = {
      "url": `/RencanaKontrol/insert`,
      "method": "POST",
      "data": {
        "request": {
          "noSEP": input.value.noSEP,
          "kodeDokter": input.value.kodeDokter.kddokterbpjs, // ?? 260031,
          "poliKontrol": input.value.poliKontrol.noruangan,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false
    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSuratKontrol
      selectedDataKontrol.value.nosuratkontrol = noSuratKontrol.value
      selectedDataKontrol.value.namadokter = x.response.namaDokter
      let jsons = {
        "noreservasi": noReservasi.value,
        "nosuratkontrol": noSuratKontrol.value
      }
      useApi().post('pasien/update-surat-kontrol', jsons).then((x) => {
        modalDetailRiwayat.value = true
        sendAntrol()
        // GAADA NOREC PD :(
      })
    } else {
      H.alert('error', x.metaData.message)
    }
  })

}
const insertSPRI = () => {
  let json = {}

  if (input.value.noSuratKontrol) {
    json = {
      "url": `/RencanaKontrol/UpdateSPRI`,
      "method": "PUT",
      "data": {
        "request": {
          "noSPRI": input.value.noSuratKontrol,
          "kodeDokter": input.value.kodeDokter.kddokterbpjs,
          "poliKontrol": input.value.poliKontrol.kdinternal,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  } else {
    json = {
      "url": `/RencanaKontrol/InsertSPRI`,
      "method": "POST",
      "data": {
        "request": {

          "noKartu": input.value.noKartu,
          "kodeDokter": input.value.kodeDokter.kddokterbpjs,
          "poliKontrol": input.value.poliKontrol.kdinternal,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then(async (x) => {
    isLoading.value = false
    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSPRI
      await sendAntrol();
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}

const sendAntrol = async () => {
  try {
    let status = false;
    let error = '';
    let kodeDokterBPJS;
    let jeniskunjungan = 3;
    let nomorreferensi = noSuratKontrol.value;
    let kdpoli = input.value.poliKontrol.kdsubspesialisbpjs ? input.value.poliKontrol.kdsubspesialisbpjs : input.value.poliKontrol.noruangan;

    const jsonJadwalDokter = {
      "url": `jadwaldokter/kodepoli/${kdpoli}/tanggal/${H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD')}`,
      "jenis": "antrean",
      "method": "GET",
      "data": null
    }
    const res = await useApi().postBPJS(`/bridging/bpjs/tools`, jsonJadwalDokter)
    if (res.metaData.code == 200) {
      for (var i = res.response.length - 1; i >= 0; i--) {
        const element = res.response[i]
        const dokternya = input.value.kodeDokter ? input.value.kodeDokter.kddokterbpjs : ""
        if (element.kodedokter == dokternya) {
          kodeDokterBPJS = {
            "jadwal": element.jadwal,
            "namadokter": element.namadokter,
            "kodedokter": element.kodedokter,
          }
          break;
        }
      }
    }

    const jsonAntrol = {
      "url": "antrean/add",
      "jenis": "antrean",
      "method": "POST",
      "data": {
        "kodebooking": noReservasi.value,
        "jenispasien": input.value.surkon == true ? 'JKN' : 'NON JKN',
        "nomorkartu": resSaveRiwayat.value ? resSaveRiwayat.value.nobpjs : '',
        "nik": resSaveRiwayat.value ? resSaveRiwayat.value.noidentitas : '0000000000000000',
        "nohp": resSaveRiwayat.value ? resSaveRiwayat.value.nohp : '000000000000',
        "kodepoli": kdpoli,
        "namapoli": input.value.poliKontrol.namaruangan,
        "pasienbaru": input.value.isReservasiBaru == true ? 'Ya' : 'Tidak',
        "norm": resSaveRiwayat.value ? resSaveRiwayat.value.nocm : '',
        "tanggalperiksa": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
        "kodedokter": kodeDokterBPJS.kodedokter,
        "namadokter": kodeDokterBPJS.namadokter,
        "jampraktek": kodeDokterBPJS.jadwal,
        "jeniskunjungan": jeniskunjungan,
        "nomorreferensi": nomorreferensi,
        "nomorantrean": resSaveRiwayat.value ? resSaveRiwayat.value.jenis + resSaveRiwayat.value.noantrianpoli : '',
        "angkaantrean": resSaveRiwayat.value ? resSaveRiwayat.value.noantrianpoli : '',
        "estimasidilayani": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD HH:mm').valueOf(),
        "sisakuotajkn": 0,
        "kuotajkn": 0,
        "sisakuotanonjkn": 0,
        "kuotanonjkn": 0,
        "keterangan": "Peserta harap 60 menit lebih awal guna pencatatan administrasi."
      }
    }
    await useApi().postNoMessage(`/bridging/bpjs/tools`, jsonAntrol).then(async (response: any) => {
      console.log('JSON ANTROL', response);
      if (response.metadata.code == 200) {
        status = true
      } else {
        error = response.metadata.message
        status = false
      }
    }).catch((e: any) => {
      status = false
      console.log('CATCH ERR ANTROL', e);
    })

    const result = {
      "status": status,
      "error": error
    }

    console.log('RESULT BPJS ANTROL', result);
  } catch (error) {
    const result = {
      "status": false,
      "error": error
    }

    console.log('RESULT BPJS ANTROL CATCH', error);
  }

  return result;
  
}

const batal = () => {
  delete input.value.kodeDokter
  delete input.value.poliKontrol
  delete input.value.jam
  delete input.value.diagnosaakhir
  delete input.value.indikasikontrol
  delete input.value.catatan
  delete input.value.terapi

  input.value = {
    filterTgl: {
      start: new Date(),
      end: new Date(),
    },
    filter: d_Filter.value[0],
    jenis: 2,
    tglRencanaKontrol: new Date(),
    tglAwal: new Date(),
    tglAkhir: new Date(),
  }
  pasien.value = {}
  sep.value = {}
}

const hapus = (nosurat) => {
  let json = {
    "url": `/RencanaKontrol/Delete`,
    "method": "DELETE",
    "data": {
      "request": {
        "t_suratkontrol": {
          "noSuratKontrol": nosurat,
          "user": H.namaPegawai()
        }
      }
    }
  }

  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false

    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      delete noSuratKontrol.value
      batal()
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const init = () => {
  confirm.require({
    message: 'Apakah mau mengambil data Riwayat Surat Kontrol sebelumnya?',
    header: 'Riwayat Surat Kontrol',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
    },
    reject: () => {
    },
  })
  useApi().get(
    `general/ppk-bpjs`
  ).then((response) => {
    namappkRumahSakit.value = response.BPJS_namaPPKRujukan
  })
}


const backPage = () => {
  window.history.back()
}

watch(
  () => input.value.isAllPeriode,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      reloadData()
    }
  }
)

watch(
  () => input.jenis,
  (newValue, oldValue) => {
    batal()
  },
  () => input.noSEP,
  () => input.noKartu,
  () => activeIdx.value,
);

watch(
  () => input.value.kelompokpasienold,
  (newValue, oldValue) => {
    // batal()
    if (input.value.kelompokpasienold) {
      if (input.value.kelompokpasienold.id == 2) {
        input.value.reservasi = true
        input.value.surkon = true
        dissep.value = true
      } else {
        dissep.value = false
        input.value.reservasi = true
      }
    }
  },
);

watch(
  () => input.value.isReservasiBaru,
  (newValue, oldValue) => {
    delete input.value
  },
);

// init()
if (route.query.nosep && route.query.nokartu) {
  input.value.noKartu = route.query.nokartu
  input.value.noSEP = route.query.nosep
  input.value.jenis = 2
  cariPasien()
}
if (route.query.nosep == undefined && route.query.nokartu) {
  input.value.noKartu = route.query.nokartu
  input.value.jenis = 2
  cariPasien()
}
// fetchSubSpe()
const getDataKontrol = async () => {
  let dari = '';
  let sampai = '';
  let norm = '';
  let namapasien = '';
  let ruanganfk = '';
  let dokter = '';
  let kategori = '&kategori=tglreservasi'
  isLoadingSKD.value = true
  console.log(item.filterRuangan)
  console.log(item.value.filterDokter)


  if (input.value.nama) namapasien = `&namapasien=${input.value.nama}`
  if (input.value.norm) norm = `&norm=${input.value.norm}`
  if (item.value.filterRuangan) ruanganfk = `&ruanganfk=${item.value.filterRuangan}`
  if (item.value.filterDokter) dokter = `&dokter=${item.value.filterDokter.value}`


  console.log('Nama Pasien', namapasien)
  console.log('No RM', norm)


  if (input.value.tglAwal) {
    dari = H.formatDate(input.value.tglAwal, 'YYYY-MM-DD')
  }
  if (input.value.tglAkhir) {
    sampai = H.formatDate(input.value.tglAkhir, 'YYYY-MM-DD')
  } else {
    sampai = H.formatDate(input.value.tglAwal, 'YYYY-MM-DD')
  }

  dataKontrol.value = [];
  isLoadingRiwayat.value = true;
  let paramApi: string = `dari=${dari}&sampai=${sampai}${kategori}${norm}${namapasien}${ruanganfk}${dokter}&isAllPeriode=${input.value.isAllPeriode}`
  let uriApi: string = `/pasien/get-riwayat-kontrol?${paramApi}`;
  await useApi()
    .get(uriApi)
    .then((res: any) => {
      isLoadingRiwayat.value = false;
      dataKontrol.value = res.riwayat;
      onKontrolSelected(res.riwayat[0]);
    }).catch((e: any) => {
      isLoadingRiwayat.value = false;
    });

}

const handleFilters = (event: any) => {
  clearTimeout(onTypingFilter);
  onTypingFilter = setTimeout(() => {
    reloadData();
  }, 800);
}

const reloadData = () => {
  selectedDataKontrol.value = {};
  getDataKontrol();
}

const onKontrolSelected = (val: any) => {
  selectedDataKontrol.value = val;
  modelSelectedKontrol.value = val.norec;
}

const filterOnChange = (val: any, field: string) => {
  dataKontrol_FILTER.value[field].value = val;
}

const openModalDetail = () => {
  if (selectedDataKontrol.value == undefined || selectedDataKontrol.value == null || Object.keys(selectedDataKontrol.value).length == 0) {
    H.alert('error', "Silahkan pilih data terlebih dahulu");
    return;
  }

  modalDetailRiwayat.value = true;
}

const openModalDelete = () => {
  if (selectedDataKontrol.value == undefined || selectedDataKontrol.value == null || Object.keys(selectedDataKontrol.value).length == 0) {
    H.alert('error', "Silahkan pilih data terlebih dahulu");
    return;
  }
  modalDeleteRiwayat.value = true;

}

const deleteKontrol = async () => {
  // let norec = selectedDataKontrol.value.norec;
  let json = {
    norec: selectedDataKontrol.value.norec,
    norec_apr: selectedDataKontrol.value.norec_apr
  }
  let uriApi: string = `/pasien/hapus-riwayat-kontrol`;
  isLoading.value = true;

  await useApi().post(uriApi, json)
    .then((response) => {
      modalDeleteRiwayat.value = false;
      isLoading.value = false;
      hapus(selectedDataKontrol.value.nosuratkontrol)
      reloadData();
    })


}

const getKelompokPasien = () => {
  useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
    d_KelompokPasien.value = response.kelompokpasien
    d_KelompokPasien.value.forEach(elO => {
      if (elO.value == registrasi.value.objectkelompokpasienlastfk) {
        input.value.kelompokpasienold = elO
      }
    });
  });
}

const normFormat = (val) => {
  let rules = {
    2: '.',
    5: '.',
  }
  console.log("s", val);

  let formatter = val.split('')
  for (let key in rules) {
    let i = Number(key);
    if (formatter.length >= (i + 1) && formatter[i] !== rules[i]) {
      formatter[i - 1] = formatter[i - 1] + rules[i];
    }
  }
  let formatted = formatter.join('');
  console.log('formatted', formatted)
  input.value.noRM = formatted;
}


// cariSKD()
listDropdown()
getDataKontrol()
fetchSupspesialis()
fetchdDropdown()
changeSpe()
// getKelompokPasien();
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/registrasi/pemakaian-asuransi.scss';
</style>
