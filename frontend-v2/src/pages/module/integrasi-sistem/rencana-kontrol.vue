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
                              <VField label="Periode">
                                <VControl class="prime-auto">
                                  <Calendar inputId="range" v-model="filterTanggal.fPeriode" selectionMode="range"
                                    :manualInput="false" class="w-100" :showIcon="true" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-2">
                              <VField label="Ruangan">
                                <VControl class="prime-auto">
                                  <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" class=" w-100"
                                    placeholder="Filter ruangan" :searchable="true" autocomplete="off"/>
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-2 mt-5">
                                <VField>
                                    <VControl>
                                        <VSwitchBlock v-model="input.isAllPeriode" label="All Periode" color="danger" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-5 mt-5">
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
                                bold @click="cetak(selectedDataKontrol)" :loading="isLoadingCetak">
                                Print Surat
                              </VButton>
                              <VButton rounded color="info" class="mr-2 is-pulled-right" icon="fas fa-eye" raised bold
                                @click="openModalDetail()" :loading="isLoadingRiwayat">
                                Detail
                              </VButton>
                              <VButton rounded color="danger" light class="mr-2 is-pulled-right" icon="fas fa-trash"
                                raised bold @click="openModalDelete(selectedDataKontrol)" :loading="isLoadingRiwayat">
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
                                      <DataTable :filters="dataKontrol_FILTER" :value="dataKontrol"
                                        class="p-datatable-md" :loading="isLoadingRiwayat" :paginator="true" :rows="100"
                                        :rowsPerPageOptions="[5, 10, 25]" scrollable scrollHeight="600px"
                                        filterDisplay="row" dataKey="id"
                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                        showGridlines>

                                        <template #empty> No customers found. </template>
                                        <template #loading> Loading customers data. Please wait. </template>
                                        <Column headerStyle="width: 3rem" frozen>
                                          <template #body="slotProps">
                                            <VRadio v-model="modelSelectedKontrol" :value="slotProps.data.norec"
                                              name="radioKontrol" color="primary"
                                              @click="onKontrolSelected(slotProps.data)" />
                                          </template>
                                        </Column>
                                        <Column field="nobukti" header="No Surat" :sortable="true"
                                          style="min-width: 90px">
                                          <template #body="slotProps">
                                            <span> {{ slotProps.data.nobukti }} </span>
                                          </template>
                                        </Column>
                                        <Column field="nosuratkontrol" header="No Surat Kontrol" :sortable="true"
                                          style="min-width: 190px">
                                          <template #filter="{ filterModel }">
                                            <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                              @input="filterOnChange(filterModel.value, 'nosuratkontrol')" />
                                          </template>
                                        </Column>
                                        <Column field="poli" header="Poli" :sortable="true" style="min-width: 100px">
                                          <template #filter="{ filterModel }">
                                            <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                              @input="filterOnChange(filterModel.value, 'poli')" />
                                          </template>
                                        </Column>
                                        <Column field="namadokter" header="Dokter" :sortable="true"
                                          style="min-width: 100px">
                                          <template #filter="{ filterModel }">
                                            <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                              @input="filterOnChange(filterModel.value, 'namadokter')" />
                                          </template>
                                        </Column>
                                        <Column field="noantrianpoli" header="Antrian Poli" :sortable="true"
                                          style="min-width: 100px">
                                          <template #body="slotProps">
                                            <span><b>{{ slotProps.data.noantrianpoli }}</b></span>
                                          </template>
                                        </Column>
                                        <Column field="tglentry" header="Tanggal Entry" :sortable="true"
                                          style="min-width: 90px">
                                          <template #body="slotProps">
                                            <span> {{ slotProps.data.tglentry ? H.formatDateToLocalString(slotProps.data.tglentry) : '-' }} </span>
                                          </template>
                                        </Column>
                                        <Column field="tglkontrol" header="Tanggal Kontrol" :sortable="true"
                                          style="min-width: 90px">
                                          <template #body="slotProps">
                                            <span> {{ slotProps.data.tglkontrol ? H.formatDateToLocalString(slotProps.data.tglkontrol) : '-' }} </span>
                                          </template>
                                        </Column>
                                        <Column field="diagnosaakhir" header="Diagnosa Akhir" :sortable="true"
                                          style="min-width: 190px">
                                          <template #filter="{ filterModel }">
                                            <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                              @input="filterOnChange(filterModel.value, 'diagnosaakhir')" />
                                          </template>
                                        </Column>
                                        <Column field="indikasikontrol" header="Indikasi Kontrol" :sortable="true"
                                          style="min-width: 190px">
                                          <template #filter="{ filterModel }">
                                            <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                              @input="filterOnChange(filterModel.value, 'indikasikontrol')" />
                                          </template>
                                        </Column>
                                        <Column field="catatan" header="Catatan" :sortable="true"
                                          style="min-width: 190px">
                                          <template #filter="{ filterModel }">
                                            <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                              @input="filterOnChange(filterModel.value, 'catatan')" />
                                          </template>
                                        </Column>
                                        <Column field="terapi" header="Terapi" :sortable="true"
                                          style="min-width: 190px">
                                          <template #filter="{ filterModel }">
                                            <InputText v-model="filterModel.value" class="p-column-filter" type="text"
                                              @input="filterOnChange(filterModel.value, 'terapi')" />
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
                    <div class="column is-6">
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
                              <VTextarea v-model="input.diagnosaakhir" rows="3">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </VField>
                        <VField horizontal label="Indikasi Kontrol" required>
                          <VField>
                            <VControl>
                              <VTextarea v-model="input.indikasikontrol" rows="3">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </VField>
                        <VField horizontal label="Catatan" required>
                          <VField>
                            <VControl>
                              <VTextarea v-model="input.catatan" rows="3">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </VField>
                        <VField horizontal label="Obat/Terapi" required>
                          <VField>
                            <VControl>
                              <VTextarea v-model="input.terapi" rows="3">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </VField>
                      </div>
                    </div>
                    <div class="column is-6">
                      <div class="s-card mt-5 p-6" style=" border-top: 3px solid var(--orange);">
                        <h3 class="title is-5 head-sep ml-1">
                          <span v-if="noSuratKontrol"> {{ noSuratKontrol }}</span>
                        </h3>
                        <h3 class="title is-5 head-sep ml-1">
                          <span v-if="sep && sep.masaRujukan"> <small>Masa Berlaku Rujukan : </small> {{ sep.masaRujukan }}</span>
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
                              :optionLabel="'kelompokpasien'" :dropdown="true" :appendTo="'body'" @complete="fetchPembiayaan($event)"
                              :loadingIcon="'pi pi-spinner'" :field="'kelompokpasien'" placeholder="Kelompok Pasien" />
                          </VControl>
                        </VField>
                        <VField horizontal label="No. SEP" class="is-rounded-select_Z  is-autocomplete-select" v-if="dissep">
                          <VControl icon="feather:user" fullwidth>
                            <VInput type="text" v-model="input.noSEP" :value="input.noSEP" placeholder="No. SEP"
                              class="is-rounded_Z" />
                          </VControl>
                        </VField>
                        <VField v-if="input.kelompokpasienold" horizontal label="Penjamin" style="display:none !important"
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:command" fullwidth>
                            <AutoComplete v-model="input.rekanan" :suggestions="d_Rekanan"
                              :optionLabel="'namarekanan'" :dropdown="true" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'namarekanan'" placeholder="Rekanan" />
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
                            :date-format="'yy-mm-dd'" disabled/>
                          <VButton light dark-outlined @click="pilihJadwal()" :loading="isLoading">Pilih Jadwal</VButton>
                        </VField>
                        <VField horizontal label="Jam">
                          <VControl fullwidth>
                            <VInput type="text" placeholder="Jam" autocomplete="off" v-model="input.jam" disabled />
                          </VControl>
                        </VField>
                      </div>
                      <div class="mt-2 is-pulled-right">
                        <VButton icon="lnir lnir-arrow-left rem-100" class="ml-2" light dark-outlined @click="backPage()">
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

  <VModal :open="showModalJadwal" title="List Kuota Dokter" :noclose="true" size="large" actions="right"
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
                    <span class="mb-2">{{ input.kodeDokter.nama }}</span><br>
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
          <img src="/images/simrs/logo_bali.png" style=" height: 60px;width: 60px;"/>
        </div>
        <div class="column is-9 no-padding-bottom">
          <h3 class="title is-5 mb-2" style="text-align: left">BUKTI PENDAFTARAN<br>RSUD Bali Mandara</h3>
        </div>
        <div class="column is-12 no-padding-bottom">
          <h5 class="is-5 mb-2">
            Tanggal: {{ H.formatDateToLocalString(selectedDataKontrol.tanggalreservasi) }}
          </h5>
          <h5 class="is-5 mb-2">
            Jam: {{ selectedDataKontrol.jam }}
          </h5>
        </div>
        <div class="column is-12 no-padding-top" style="height: 50px;">
          <!-- <img src='https://barcode.tec-it.com/barcode.ashx?data={{selectedDataKontrol.nocm}}&code=Code39&dpi=96&dataseparator='
            style=" height: 40px;width: 200px;-webkit-user-select: none;cursor:pointer"/> -->
        </div>
        <div class="column is-12 no-padding-bottom">
          <h5 class="is-5 mb-2">
            <b>{{ selectedDataKontrol.namapasien }} - {{ selectedDataKontrol.nocm }} - {{ selectedDataKontrol.kebangsaan }}</b>
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
          <h3 class="title is-5 mb-2" style="text-align: center; margin-top: -20px;">{{ selectedDataKontrol.noantrianpoli }}<br>{{ selectedDataKontrol.poli }}<br>{{ selectedDataKontrol.kelompokpasien }}</h3><br>
          <!-- <h3 class="title is-5 mb-2" style="text-align: center; margin-top: -20px;">{{ selectedDataKontrol.noreservasi }}</h3> -->
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
  filter: d_Filter.value[0],
  jenis: 2,
  reservasi: 2,
  noKartu: '',
  noSEP: '',
  tglRencanaKontrol: new Date(),
})

const noSuratKontrol = ref()
const noReservasi = ref()
const norec_apr = ref('')
const norec_rk = ref('')
let tglRujukan = ''
let d_Ruangan: any = ref([])
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
const isPasienFound = ref(false);
const sudahPopup = ref(false);
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
const listTemplate: any = ref([])
const parameter: any = ref({
  nocmfk: route.query.nocmfk,
  norec_pd: route.query.norec_pd,
})

console.log(parameter)

let dataKontrol: any = ref([]);
let modelSelectedKontrol: any = ref('');
let dataKontrol_FILTER: any = ref({
  nosurat: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  tanggalreservasi: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  nosuratkontrol: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  terapi: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  catatan: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  diagnosaakhir: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  indikasikontrol: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  namadokter: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  poli: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  tanggalreservasi: { value: null, filterMatch: FilterMatchMode.CONTAINS },
  namapasien: { value: null, filterMatch: FilterMatchMode.CONTAINS },
})
let selectedDataKontrol: any = ref({
  nosuratkontrol: null
});
const confirm = useConfirm();

const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/registrasi/dropdown`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
}

useApi().get(
  `/emr/header-pasien?nocmfk=${parameter.value.nocmfk}&norec_pd=${parameter.value.norec_pd}`).then((response: any) => {
    pasien.value = response.pasien
    
    if(response.pasien) {
      peserta.value.nik = response.pasien.noidentitas;
      peserta.value.noKartu = response.pasien.nobpjs;
      peserta.value.tglLahir = response.pasien.tgllahir;
      peserta.value.mr.noMR = response.pasien.nocm
      peserta.value.nama = response.pasien.namapasien
      if(response.registrasi.length > 0) {
        registrasi.value = response.registrasi[0]
        useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
          d_KelompokPasien.value = response.kelompokpasien
          d_KelompokPasien.value.forEach(elO => {
            // console.log('kelompok pasien', elO.value)
            // console.log('kelompok pasien last', registrasi.value.objectkelompokpasienlastfk)
            if(elO.id == registrasi.value.objectkelompokpasienlastfk){
              console.log('ada yang sama')
              input.value.kelompokpasienold = elO
            }
          });
        });

        // useApi().get(`/registrasi/penjamin-by-kelompokpasien?id=${registrasi.value.objectkelompokpasienlastfk}`)
        // .then((response: any) => {
        //   input.value.rekanan = response[0]
        // })


        useApi().get(`emr/auto-fill?nocmfk=${parameter.value.nocmfk}&norec_pd=${parameter.value.norec_pd}&collection=AsesmenMedisRawatJalan&field=TADiagnosa`).then((response) => {
          if (response != null) {
            input.value.diagnosaakhir = response.TADiagnosa
          } else{
            useApi().get(`emr/auto-fill?nocmfk=${parameter.value.nocmfk}&norec_pd=${parameter.value.norec_pd}&collection=CPPTDetail&field=S,O,A,P&flag=dokter`).then((response) => {
              input.value.diagnosaakhir = response.A
            })
          }
        });

        useApi().get(`farmasi/riwayat-order-resep?norec_pd=${parameter.value.norec_pd}`).then((resObat) => {
          console.log('ini obat', resObat)
          if(resObat.length > 0) {
              isLoading.value = false;
              let stringObat = ''
              resObat[0].details.forEach(elO => {
                  stringObat += `# ${elO.namaproduk} \n`;
              });

              input.value.terapi = stringObat;
          }
        })

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
            if(e.id == registrasi.value.objectpegawaifk){
              input.value.kodeDokter = e
            }
          });
        })
        //let resObat = useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${parameter.value.norec_pd}`)
        

        
        if(registrasi.value.objectkelompokpasienlastfk == 2){
          peserta.value.hakKelas.keterangan = response.registrasi[0].namakelas
          let json = {
            "url": "monitoring/HistoriPelayanan/NoKartu/" + pasien.value.nobpjs + "/tglMulai/" + H.formatDate(registrasi.value.tglregistrasi, 'YYYY-MM-DD') + "/tglAkhir/" + H.formatDate(registrasi.value.tglregistrasi, 'YYYY-MM-DD'),
            "jenis": "monitoring",
            "method": "GET",
            "data": null
          }
          useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
            isLoadingSKD.value = false
            if (x.metaData.code == 200) {
              console.log("data X", x);
              input.value.jenis = 2;
              input.value.noKartu = pasien.value.nobpjs;
              if (x.response.histori.length > 0) {
                let lastSEP = x.response.histori[0]
                console.log('Last SEP', lastSEP)
                input.value.noSEP = lastSEP.noSep;

                let json2 = {
                  "url": `/RencanaKontrol/nosep/${input.value.noSEP}`,
                  "method": "GET",
                  "data": null
                }

                useApi().postBPJS('/bridging/bpjs/tools', json2).then((x) => {
                  if (x.metaData.code == 200) {
                    isPasienFound.value = true;
                    sep.value = x.response
                    if(x.response.provPerujuk) {
                      if(x.response.provPerujuk.tglRujukan) {
                        let today = new Date(); // Get today's date
                        let form_date=new Date(x.response.provPerujuk.tglRujukan)
                        let difference = Math.abs(today.getTime() - form_date.getTime());
                        let diff_days=Math.floor(90/(1000*3600*24))
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

                let json3 = {
                  "url": `/SEP/${input.value.noSEP}`,
                  "method": "GET",
                  "data": null
                }

                useApi().postBPJS('/bridging/bpjs/tools', json3).then((x) => {
                  if (x.metaData.code == 200) {
                    if(x.response.catatan.toUpperCase().indexOf('POST RANAP') > -1){
                      input.value.surkon = false
                      H.alert('warning', 'Pasien POST RANAP')
                    }
                  }
                })
                // input.value.
                // cariPasien();
              }
            } else {
              H.alert('error', x.metaData.message)
            }
          })

          
        }
      }
    }
  })

// useApi().get(
//     `/registrasi/pasien-registrasi`).then((response: any) => {
//       pasien.value = response.pasien
//       d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
//     })


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
  console.log(registrasi.value)
  isLoading.value = true
  useApi().get(`/medifirst2000/kiosk/get-dokterbyruangan-semuatgl?objectruanganfk=${input.value.poliKontrol.id}`).then((response: any) => {
    isLoading.value = false
    listTemplate.value = response
    showModalJadwal.value = true
  });
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

  H.printBlade(`emr/cetak-rencana-kontrol-poli?nobukti=${e.nobukti}&norec_pd=${parameter.value.norec_pd}&pdf=true`);


  // let json = {
  //   "url": `/RencanaKontrol/noSuratKontrol/${nosurat}`,
  //   "method": "GET",
  //   "data": null
  // }



  // let json = {
  //   "url": `Peserta/nokartu/${e.nokartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
  //   "method": "GET",
  //   "data": null
  // }
  // isLoadingCetak.value = true
  // let response = await useApi().postBPJS('/bridging/bpjs/tools', json)
  // isLoadingCetak.value = false
  // let nosuratkontrol = e.nosurat
  // let tglrencanakontrol = e.tglkontrol
  // let txttglentrirencanakontrol = e.tglentry
  // let noka = e.nokartu
  // let nama = e.namapasien
  // let tgllahir = e.tgllahir

  // let namaPoliTujuan = e.poli
  // let jeniskelamin = e.jeniskelamin
  // let jnsKontrol = e.jnsKontrol
  // let namaDokter = e.namadokter
  // let kddx = '-'
  // let nmdpjpsepasal = '-';// e.namaDokter ? e.namaDokter : '-'
  // let iddok = 'null'
  // let dxawal = '-'

  // if (e.nosepasal != null) {
  //   let json = {
  //     "url": "sep/" + e.nosepasal,
  //     "method": "GET",
  //     "data": null
  //   }
  //   useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
  //     if (x.metaData.code == 200) {
  //       dxawal = x.response.diagnosa

  //       cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
  //         nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
  //         jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);


  //     } else {
  //       H.alert('error', x.metaData.message);
  //     }
  //   })

  // } else {
  //   dxawal = '-'
  //   cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
  //     nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
  //     jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);

  // }
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
        if(x.response.provPerujuk) {
          if(x.response.provPerujuk.tglRujukan) {
            let today = new Date(); // Get today's date
            let form_date=new Date(x.response.provPerujuk.tglRujukan)
            let difference = Math.abs(today.getTime() - form_date.getTime());
            let diff_days=Math.floor(difference/(1000*3600*24))
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

  let url = `/registrasi/list-ruangan-rawat-jalan?query=${query}&iskontrol=true`;

  await useApi().get(url).then((res: any) => {
    let filtered = [];
    // if(res.ruangan_RJ.length > 0) {
    //   res.ruangan_RJ.forEach(element => {
    //     if(element.namaruangan.toUpperCase().indexOf('RAWAT INAP') > -1) {
    //       filtered.push(element) 
    //     }
    //   });
    // }
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
  // console.log(input.value.poliKontrol)
  let msgErr = '';
  console.log(registrasi.value.kelompokpasien);
  if(registrasi.value && registrasi.value.kelompokpasien !== "UMUM/PRIBADI") {
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

  // if(msgErr != '') {
  //   H.alert('error', msgErr)
  //   return;
  // }
  if (input.value.reservasi == true) {
    isLoading.value = true
    input.value.nocmfk = parameter.value.nocmfk
    input.value.kelompokpasien = input.value.kelompokpasienold.id
    useApi().post('pasien/create-riwayat-kontrol', input.value).then((x) => {
      isLoading.value = false
      console.log(x);
      noReservasi.value = x.data[0].noreservasi
      console.log('No Reservasi', noReservasi.value)
      sudahPopup.value = true
      // input.value.diagnosaakhir = undefined
      // input.value.indikasikontrol = undefined
      // input.value.catatan = undefined
      // input.value.terapi = undefined
      // input.value.reservasi = undefined
      // input.value.surkon = undefined
      // input.value.kelompokpasienold = undefined
      // input.value.noSEP = undefined
      // input.value.rekanan = undefined
      // input.value.poliKontrol = undefined
      // input.value.kodeDokter = undefined
      input.value.tglRencanaKontrol = undefined
      input.value.jam = undefined
      input.value.norec_apr = undefined
      input.value.norec_rk = undefined
      saveKlaimSEP()
      reloadData();
      activeIdx.value = 0
    })
  }
  
  
  if(input.value.surkon == true) {
    if (input.value.jenis == 1) {
      insertSPRI()
      reloadData()
    } else {
      insertRencanaKontrol()
    }
  } else{
    reloadData()
  }
  
}

const saveKlaimSEP = async () => {
  isLoading.value = true
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
        'norec_pd': parameter.value.norec_pd,
        'documentklaimfk': 206,
        'namafile': "skdp",
        'tglregistrasi': registrasi.value.tglregistrasi,
        'api': "EMR-ReportEMRCtrl@cetakRencanaKontrol"
    }).then((r) => {
        isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
}

const insertRencanaKontrol = () => {
  let json = {}

  if (input.value.norec_apr != null) {
    if(selectedDataKontrol.value.nosuratkontrol != null){
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
    } else{
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
      console.log('No Reservasi Value', noReservasi.value)
      let jsons = {
        "noreservasi": noReservasi.value,
        "nosuratkontrol": noSuratKontrol.value
      }
      useApi().post('pasien/update-surat-kontrol', jsons).then((x) => {
        reloadData()
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
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false
    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSPRI
    } else {
      H.alert('error', x.metaData.message)
    }
  })
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
    tglRencanaKontrol: new Date()
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
    if(input.value.kelompokpasienold){
      if(input.value.kelompokpasienold.id == 2){
        dissep.value = true
      } else{
        dissep.value = false
      }
    }
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
  const responseHistori = await useApi().get(`/pasien/get-riwayat-kontrol-terakhir?nocmfk=${parameter.value.nocmfk}`)
  console.log(responseHistori)
  console.log(sudahPopup.value)
  if (responseHistori.riwayat.length > 0 && sudahPopup.value == false) {
    sudahPopup.value = true
    confirm.require({
      message: 'Apakah mau mengambil data Riwayat Surat Kontrol sebelumnya?',
      header: 'Riwayat Surat Kontrol',
      icon: 'pi pi-info-circle',
      acceptClass: 'p-button-danger',
      accept: () => {
        activeIdx.value = 1
        const riwayatkontrol = responseHistori.riwayat[0]
        console.log('riwayat pre', riwayatkontrol)

        console.log('riwayat', riwayatkontrol.indikasikontrol)
        input.value.diagnosaakhir = riwayatkontrol.diagnosaakhir
        input.value.indikasikontrol = riwayatkontrol.indikasikontrol
        console.log('indikasi', input.value.indikasikontrol)
        input.value.catatan = riwayatkontrol.catatan
        input.value.terapi = riwayatkontrol.terapi
        let url = `/registrasi/list-ruangan-rawat-jalan`;
        useApi().get(url).then((res: any) => {
          d_Subspesialis.value = res.ruangan_RJ;
          d_Subspesialis.value.forEach(elO => {
            if(elO.id == riwayatkontrol.objectruanganfk){
              console.log('poli kontrol', 'ada yang sama')
              input.value.poliKontrol = elO
            }
          });
        });
        useApi().get(`/registrasi/dokter-paging`).then((response: any) => {
          d_dpjpLayan.value = response.dokter
          d_dpjpLayan.value.forEach(e => {
            console.log('dd dokter', e)
            if(e.id == riwayatkontrol.objectpegawaifk){
              console.log('dpjp', 'ada yang sama')
              input.value.kodeDokter = e
            }
          });
        })
        input.value.tglRencanaKontrol = riwayatkontrol.tglkontrol
        input.value.jam = riwayatkontrol.jam
        useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
          d_KelompokPasien.value = response.kelompokpasien
          d_KelompokPasien.value.forEach(es => {
            console.log('dd kel pasien', es)
            if(es.id == riwayatkontrol.objectkelompokpasienfk){
              console.log('kelompok pasien', 'ada yang sama')
              input.value.kelompokpasienold = es
            }
          });
        });

        // useApi().get(`/registrasi/penjamin-by-kelompokpasien?id=${riwayatkontrol.objectkelompokpasienfk}`)
        // .then((response: any) => {
        //   input.value.rekanan = response[0]
        // })
      },
      reject: () => {
      },
    })
  } 
    let dari = '';
    let sampai = '';
    let kategori = '&kategori=tglentri'
    let ruanganfk = '';
    isLoadingSKD.value = true
    console.log(registrasi.value)


    if (item.value.filterRuangan) ruanganfk = `&ruanganfk=${item.value.filterRuangan}`
    if (filterTanggal.fPeriode[0]) {
      dari = H.formatDate(filterTanggal.fPeriode[0], 'YYYY-MM-DD')
    }
    if (filterTanggal.fPeriode[1]) {
      sampai = H.formatDate(filterTanggal.fPeriode[1], 'YYYY-MM-DD')
    } else {
      sampai = H.formatDate(filterTanggal.fPeriode[0], 'YYYY-MM-DD')
    }

    dataKontrol.value = [];
    isLoadingRiwayat.value = true;
    let paramApi: string = `nocmfk=${parameter.value.nocmfk}&norec_pd=${parameter.value.norec_pd}&dari=${dari}&sampai=${sampai}${kategori}${ruanganfk}&isAllPeriode=${input.value.isAllPeriode}&orderby=tglkontrol`
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
  console.log(selectedDataKontrol.value)
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

const editSurkon = async () => {

  console.log(selectedDataKontrol.value)
        useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
          d_KelompokPasien.value = response.kelompokpasien
          d_KelompokPasien.value.forEach(elO => {
            if(elO.id == selectedDataKontrol.value.idkelompokpasien){
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
            if(e.id == selectedDataKontrol.value.objectpegawaifk){
              input.value.kodeDokter = e
            }
          });
        })

        
        if(selectedDataKontrol.value.idkelompokpasien == 2){
          let json = {
            "url": "monitoring/HistoriPelayanan/NoKartu/" + pasien.value.nobpjs + "/tglMulai/" + H.formatDate(registrasi.value.tglregistrasi, 'YYYY-MM-DD') + "/tglAkhir/" + H.formatDate(registrasi.value.tglregistrasi, 'YYYY-MM-DD'),
            "jenis": "monitoring",
            "method": "GET",
            "data": null
          }
          useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
            isLoadingSKD.value = false
            if (x.metaData.code == 200) {
              console.log("data X", x);
              input.value.jenis = 2;
              input.value.noKartu = pasien.value.nobpjs;
              if (x.response.histori.length > 0) {
                let lastSEP = x.response.histori[0]
                console.log('Last SEP', lastSEP)
                input.value.noSEP = lastSEP.noSep;

                let json2 = {
                  "url": `/RencanaKontrol/nosep/${input.value.noSEP}`,
                  "method": "GET",
                  "data": null
                }

                useApi().postBPJS('/bridging/bpjs/tools', json2).then((x) => {
                  if (x.metaData.code == 200) {
                    isPasienFound.value = true;
                    sep.value = x.response
                    if(x.response.provPerujuk) {
                      if(x.response.provPerujuk.tglRujukan) {
                        let today = new Date(); // Get today's date
                        let form_date=new Date(x.response.provPerujuk.tglRujukan)
                        let difference = Math.abs(today.getTime() - form_date.getTime());
                        let diff_days=Math.floor(90/(1000*3600*24))
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
                // input.value.
                // cariPasien();
              }
            } else {
              H.alert('error', x.metaData.message)
            }
          })



          
        }

        
      activeIdx.value = 1
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

watch(
      () =>input.value.isAllPeriode,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            reloadData()
          }
      }
  )

watch(
    () => input.value.kelompokpasienold,
    (newValue, oldValue) => {
      // batal()
      if(input.value.kelompokpasienold){
        if(input.value.kelompokpasienold.id == 2){
            input.value.reservasi = true
            input.value.surkon = true
          dissep.value = true
        } else{
          dissep.value = false
          input.value.reservasi = true
          input.value.surkon = false
        }
      }
    },
  );

const getKelompokPasien = () => {
  useApi().get(`/registrasi/list-kelompokpasien-all`).then((response: any) => {
    d_KelompokPasien.value = response.kelompokpasien
    d_KelompokPasien.value.forEach(elO => {
      if(elO.value == registrasi.value.objectkelompokpasienlastfk){
        input.value.kelompokpasienold = elO
      }
    });
  });
}


// cariSKD()
getDataKontrol()
fetchSupspesialis()
changeSpe()
fetchdDropdown()
// getKelompokPasien();
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/registrasi/pemakaian-asuransi.scss';
</style>
