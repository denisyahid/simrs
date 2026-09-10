<template>
  <!-- <div class="finance-dashboard stock-dashboard"> -->
  <!-- <div class="columns is-multiline"> -->
  <!-- <div class="column " :class="props.hideRiwayat == true ? 'is-10' : 'is-9'"> -->
  <!-- <div class="column " :class="props.hideRiwayat == true ? 'is-12' : 'is-9'"> -->
  <VCard radius="rounded">
    <div class="columns ">
      <a v-if="props.hideRiwayat == true" @click="() => { emits('showRiwayat') }" class="is-expandeds mr-2"
        style="margin-top:-5px;">
        <i class="iconify" data-icon="feather:arrow-right" aria-hidden="true"> </i>
      </a>
      <h3 class="title is-5 mb-2 mr-1">Dashboard </h3>
    </div>
    <div class="columns is-multiline is-mobile">
      <div class="column is-12  p-0">
        <VIconButton circle class="mr-2 is-pulled-right classActiveNavEMR" icon="fas fa-angle-double-left" raised bold
          @click="() => { emits('showNavStatus') }" v-tooltip.bubble="'Lihat Status EMR'">
        </VIconButton>
        <VIconButton circle class="mr-2 is-pulled-right" icon="feather:refresh-cw" raised bold
          @click="() => { emits('reloadRiwayat') }" :loading="props.isLoadingRiwayat"
          v-tooltip.bubble="'Perbaharui Data EMR'">
        </VIconButton>

        <VIconButton style="display:none !important;" circle class="mr-2 is-pulled-right" icon="fas fa-expand-arrows-alt" raised bold
          v-if="props.hideRiwayat == true" @click="() => { emits('showRiwayat') }"
          v-tooltip.bubble="'Perkecil Tampilan EMR'">
        </VIconButton>
        <VIconButton style="display:none !important;" circle class="mr-2 is-pulled-right" icon="fas fa-expand" raised bold
          v-if="props.hideRiwayat == false" @click="() => { emits('hiddenRiwayat') }"
          v-tooltip.bubble="'Perbesar Tampilan EMR'">
        </VIconButton>

        <VButton rounded color="success" class="mr-2 is-pulled-right" icon="feather:file-text" raised bold
          @click="() => { emits('billingPasien') }">
          Billing Tagihan
        </VButton>
        <div v-for="(item, index) in props.riwayat.LIST_EMR" :key="item.url_form">
        <VButton rounded color="danger" class="mr-2 is-pulled-right" icon="feather:file-text" raised bold v-if="item.namaemr == 'Assesmen Medis'">
          Assesmen Medis
          {{ hitungHariSejak(item.last_update) }}
          </VButton>
          <VButton rounded color="danger" class="mr-2 is-pulled-right" icon="feather:file-text" raised bold v-if="item.namaemr == 'Assesmen Keperawatan'">
          Assesmen Keperawatan
          {{ hitungHariSejak(item.last_update) }}
          </VButton>
        </div>
        <!-- <VButton rounded :color="isPasienAktif == true ? 'info' : 'primary-grey'"
                    v-bind:disabled="isPasienAktif == false ? true : false" class="mr-2 is-pulled-right"
                    icon="feather:plus-circle" raised bold @click="() => { emits('showMenuEMR') }">
                    Tambah
                </VButton> -->

      </div>
      <div class="column is-12">
        <div class="columns is-multiline h-350 ">
          <div class="column is-12" v-if="props.riwayat.isEmpty">
            <div class="project-files">
              <div class="updates">
                <div class="updates-header">
                  <h3 class="dark-inverted">Riwayat Pemeriksaan</h3>
                  <!-- <a class="action-link" tabindex="0">View All</a> -->
                </div>

                <div class="updates-list">
                  <div class="panjang-300">
                    <VLoader size="large" :active="props.isLoadingRiwayat" :translucent="true">
                    <div class="update-item is-dark-bordered-12 " style="display: block;">
                      <div class="search-results-wrapper">
                        <div class="search-results-body ">
                          <!--Search Placeholder -->
                          <div class="page-placeholder">
                            <div class="placeholder-content">
                              <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                alt="" />
                              <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                alt="" />
                              <h3>{{ H.assets().notFound }}</h3>
                              <p class="is-larger">
                                {{ H.assets().notFoundSubtitle }}
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    </VLoader>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <!-- <pre>{{ props.registrasi }}</pre> -->
          <!-- <div class="column is-12" v-if="props.registrasi.namaruangan == 'IBS'">
            <VButton rounded color="success" class="mr-2 is-pulled-right" icon="feather:file-text" raised bold @click="clickListFormIBS()">
              List Keperluan IBS
            </VButton>
          </div> -->
          <!-- <div class="column is-12">
          </div> -->
          <Dialog v-model:visible="modalFormIBS" header="List Form" :style="{ width: '100rem' }">
            <table class="table-pri">
              <thead style="align-items: center;">
                <tr>
                  <th class="th-pri">No</th>
                  <th class="th-pri">Nama Form</th>
                  <th class="th-pri">Status</th>
                </tr>
              </thead>
              <tbody>
                <!-- <pre>{{ props.riwayat.LIST_EMR_IBS }}</pre> -->
                <!-- <tr v-for="(status, namaForm, index) in props.riwayat.LIST_EMR_IBS" :key="index">
                  <td class="td-pri">{{ index + 1 }}</td>
                  <td class="td-pri">
                    <span v-if="!status.namaemr">{{ status.caption || namaForm }}</span>
                    <a v-else @click="() => { emits('editEMR', status) }">{{ status.namaemr }}</a>
                  </td>
                  <td class="td-pri">
                    <span v-if="status.namaemr">Sudah Terisi</span>
                    <span v-else>Belum Terisi</span>
                  </td>
                </tr> -->
                <tr v-for="(status, namaForm, index) in props.riwayat.LIST_EMR_IBS" :key="index">
                  <td class="td-pri">{{ namaForm + 1 }}</td>
                  <td class="td-pri">
                    <a @click="() => { emits('editEMR', status) }">
                      {{ status.namaemr || status.caption || namaForm }}
                    </a>
                  </td>
                  <td class="td-pri">
                    <span v-if="status.namaemr">Sudah Terisi</span>
                    <span v-else>Belum Terisi</span>
                  </td>
                </tr>
              </tbody>
            </table>

          </Dialog>
          <div class="column is-12" v-if="props.riwayat.LIST_ASESMENAWAL != null">
            <TEmrAsesmenAwal v-if="props.registrasi" :pasien="props.pasien" :registrasi="props.registrasi"
              :isLoadingRiwayat="isLoadingRiwayat" @editEMR="editEMRAsesmen">
            </TEmrAsesmenAwal>
          </div>

          <div class="column is-6" v-if="props.riwayat.LIST_DIAGNOSIS.length > 0">
            <div class="project-files">
              <div class="updates">
                <!--Header-->
                <div class="updates-header">
                  <h3 class="dark-inverted">DIAGNOSIS</h3>
                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                    @click="emits('openEMR', { namaemr: 'Diagnosis', url_form: 'input-diagnosis' })">View
                    All</a>
                </div>

                <div class="updates-list">
                  <div class="panjang-250">
                    <div class="columns is-multiline">
                      <div class="column is-6" style="margin-top:-5px"
                        v-for="(item, index) in props.riwayat.LIST_DIAGNOSIS" :key="index">
                        <VCard class="is-clickable mt-2 is-grey p-3">

                          <span class="span-text-diagnos"> <i aria-hidden="true"
                              :class="'lnir lnir-diagnosis mr-2 is-' + item.color"></i>{{
                                item.icd
                              }}</span>
                          <VTag :label="item.jenis" :color="item.jenis == 'ICD X' ? 'info' : 'warning'" rounded />
                        </VCard>
                      </div>

                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.LIST_RESEP.length > 0">
            <div class="project-files">
              <div class="updates">
                <!--Header-->
                <div class="updates-header">
                  <h3 class="dark-inverted">RESEP</h3>
                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                    @click="emits('openEMR', { namaemr: 'Resep', url_form: 'order-resep' })">View All</a>
                </div>
                <div class="updates-list" v-if="props.riwayat.isLoading == true">
                  <div class="panjang-250">
                    <div class="update-item is-dark-bordered-12" v-for="(item, index) in 6" :key="index">
                      <VPlaceload :lines="1" class="mr-2" />
                      <span class="dark-inverted">
                        <VPlaceload :lines="1" width="100%" />
                      </span>
                    </div>
                  </div>
                </div>
                <div class="updates-list" v-else-if="props.riwayat.LIST_RESEP.length > 0">
                  <div class="panjang-250">
                    <div class="columns is-multiline">
                      <div class="column is-12 mb--15" v-for="(item, index) in props.riwayat.LIST_RESEP" :key="index">
                        <div class="space-b">
                          <div class="file-box" style="width:95%">
                            <img :src="'/images/icons/files/resep-' + item.no + '.svg'" alt="" />
                            <div class="meta">
                              <span>{{ item.namaproduk }} </span>
                              <span>
                                <b>{{ item.jumlah }}</b> Qty
                                <i aria-hidden="true" class="fas fa-circle"></i>
                                {{ H.formatRp(item.total, 'Rp.') }}

                              </span>
                            </div>
                            <div class="is-right is-dots is-spaced dropdown end-action">
                              <span> {{
                                H.formatDateIndoSimple(item.tglpelayanan)
                              }}</span>
                            </div>
                          </div>
                          <div class="ml-2 mt-5">
                            <i aria-hidden="true" class="fas fa-check-circle" style="color:var(--success)"
                              v-if="item.status == 'Selesai'"></i>
                            <i class="fas fa-pause-circle" aria-hidden="true" style="color:var(--warning)" v-else></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- <div class="column is-6" v-if="props.riwayat.LIST_EMR.length > 0">
            <div class="project-files">
              <div class="updates">
                <div class="updates-header">
                  <h3 class="dark-inverted">EMR</h3>

                </div>
                <div class="updates-list">
                  <div class="panjang-250">
                    <div v-for="(item, index) in props.riwayat.LIST_EMR" :key="item.url_form"
                      class="inner-list-item media-flex-center ">
                      <VIconBox :rounded="true" :color="listColor[index + 1]">
                        <i aria-hidden="true" :class="item.icon"></i>
                      </VIconBox>
                      <div class="flex-meta is-light">
                        <a @click="() => { emits('editEMR', item) }">{{ item.namaemr }}</a>
                        <span>{{ H.formatDateIndoSimple(item.last_update) }}</span>
                        <VTag :label="item.ruangan" color="solid" rounded class="mt-1" /> <br>
                        <VTag :label="item.author" color="solid" rounded class="mt-1" />
                      </div>
                      <div class="flex-end">
                        <VDropdown icon="feather:more-vertical" dots right spaced>
                          <template #content>
                            <a @click="() => { emits('editEMR', item) }" class="dropdown-item is-media">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-eye-alt"></i>
                              </div>
                              <div class="meta">
                                <span>Lihat</span>
                                <span> Lihat atau ubah data EMR</span>
                              </div>
                            </a>
                            <a @click="() => { emits('cetakEMR', item) }" class="dropdown-item is-media">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-printer"></i>
                              </div>
                              <div class="meta">
                                <span>Cetak</span>
                                <span>Cetak data EMR</span>
                              </div>
                            </a>
                            <a @click="() => { emits('hapusEMR', item) }" class="dropdown-item is-media">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-trash"></i>
                              </div>
                              <div class="meta">
                                <span>Hapus</span>
                                <span>Hapus data EMR</span>
                              </div>
                            </a>
                            <hr class="dropdown-divider" />

                          </template>
                        </VDropdown>
                      </div>
                    </div>
                  </div>
                </div>


              </div>
            </div>
          </div> -->
          <!-- search emr -->
          <div class="column is-6" v-if="props.riwayat.LIST_EMR.length > 0">
            <div class="project-files">
              <div class="updates">
                <div class="updates-header">
                  <h3 class="dark-inverted">EMR</h3>
                </div>
                <div class="updates-header">
                  <input class="input is-rounded" type="text" placeholder="Cari EMR..." v-model="search" />
                </div>
                <!-- <pre>{{ props.riwayat.LIST_EMR }}</pre> -->
                <div class="updates-list" v-if="filteredEmr.length > 0">
                  <div class="panjang-250">
                    <div v-for="(item, index) in filteredEmr"
                      :key="`emr-${index}-${item.namaemr}-${item.last_update}`"
                      class="inner-list-item media-flex-center ">
                      <VIconBox :rounded="true" :color="listColor[index + 1]">
                        <i aria-hidden="true" :class="item.icon"></i>
                      </VIconBox>
                      <div class="flex-meta is-light">
                        <a @click="() => { emits('editEMR', item) }">{{ item.namaemr }}</a>
                        <span class="mb-1">{{ H.formatDateIndoSimple(item.last_update) }}</span>
                        <VTag v-if="item.ruangan" :label="item.ruangan" color="info" outlined rounded class="mr-1" />
                        <VTag :label="item.author" color="solid" rounded class="mr-1" />
                      </div>
                      <div class="flex-end">
                        <VDropdown icon="feather:more-vertical" dots right spaced>
                          <template #content>
                            <a @click="() => { emits('editEMR', item) }" class="dropdown-item is-media">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-eye-alt"></i>
                              </div>
                              <div class="meta">
                                <span>Lihat</span>
                                <span> Lihat atau ubah data EMR</span>
                              </div>
                            </a>
                            <a @click="() => { emits('cetakEMR', item) }" class="dropdown-item is-media">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-printer"></i>
                              </div>
                              <div class="meta">
                                <span>Cetak</span>
                                <span>Cetak data EMR</span>
                              </div>
                            </a>
                            <a @click="() => { emits('hapusEMR', item) }" class="dropdown-item is-media">
                              <div class="icon">
                                <i aria-hidden="true" class="lnil lnil-trash"></i>
                              </div>
                              <div class="meta">
                                <span>Hapus</span>
                                <span>Hapus data EMR</span>
                              </div>
                            </a>
                            <hr class="dropdown-divider" />

                          </template>
                        </VDropdown>
                      </div>
                    </div>
                    <!-- <div v-if="!search.value" class="update-item is-dark-bordered-12">
                      <span class="dark-inverted">Data EMR tidak ditemukan</span>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.LIST_TINDAKAN.length > 0">
            <div class="project-files">
              <!--Updates-->
              <div class="updates">
                <div class="updates-header">
                  <h3 class="dark-inverted">TINDAKAN</h3>
                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                  @click="() => { emits('billingPasien') }">View All</a>
                </div>
                <div class="updates-list" v-if="props.riwayat.isLoading == true">
                  <div class="panjang-250">
                    <div class="update-item is-dark-bordered-12" v-for="(item, index) in 6" :key="index">
                      <VPlaceload :lines="1" class="mr-2" />
                      <span class="dark-inverted">
                        <VPlaceload :lines="1" width="100%" />
                      </span>
                    </div>
                  </div>
                </div>
                <div class="updates-list" v-else-if="props.riwayat.LIST_TINDAKAN.length > 0">
                  <div class="panjang-250">
                    <div class="update-item is-dark-bordered-12 " v-for="(item, index) in props.riwayat.LIST_TINDAKAN"
                      :key="index">
                      <p>{{ item.namaproduk }}
                        <span class="subtitle">{{ item.namaruangan }}</span>
                      </p>

                      <!-- <span class="dark-inverted"> -->
                      <VTag :label="H.formatDateIndoSimple(item.tglpelayanan)" color="danger" rounded />
                      <!-- {{H.formatDateIndoSimple(item.tglpelayanan)}} -->
                      <!-- </span> -->
                    </div>
                  </div>
                </div>


              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.LIST_CPPT.length > 0">
            <div class="project-files">
              <!--Updates-->
              <div class="updates">
                <!--Header-->
                <div class="updates-header">
                  <h3 class="dark-inverted">CPPT</h3>
                  <a v-if='isPasienAktif' class="action-link" tabindex="0">View All</a>
                </div>

                <div class="updates-list" v-if="props.riwayat.isLoading == true">
                  <div class="panjang-250">
                    <div class="update-item is-dark-bordered-12" v-for="(item, index) in 6" :key="index">
                      <VPlaceload :lines="1" class="mr-2" />
                      <span class="dark-inverted">
                        <VPlaceload :lines="1" width="100%" />
                      </span>
                    </div>
                  </div>
                </div>
                <div class="updates-list" v-else-if="props.riwayat.LIST_CPPT.length > 0">
                  <div class="panjang-250">
                    <div class="update-item is-dark-bordered-12 " v-for="(item, index) in props.riwayat.LIST_CPPT"
                      :key="index">
                      <p>{{ item.namaproduk }}</p>
                      <span class="dark-inverted">{{
                        H.formatDateIndoSimple(item.tglpelayanan)
                      }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.LIST_LAB.length > 0">
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>LABORATORIUM </h3>

                  </div>
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
                  <div v-for="item in props.riwayat.LIST_LAB" :key="item.id" class=" mb-2">
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
                            <tr v-if="props.riwayat.LIST_LAB_GROUP[items_d.treatment_name] != undefined &&
                              props.riwayat.LIST_LAB_GROUP[items_d.treatment_name].index === rowIndex">
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
                        <!-- <div v-for="items_d in item.hasil_lab" class="mt-1">
                                                    <span style=" font-size: 0.9rem; " class="mr-1">
                                                        <VTag class="mb-2-min"
                                                            :color="(items_d.flag == '' ? 'success' : (items_d.flag == 'H' ? 'danger' : 'warning'))"
                                                            :label="''" />
                                                    </span>
                                                    <span style=" font-size: 0.9rem; "> <b>{{
                                                        items_d.examination_name }} </b></span>
                                                    <i aria-hidden="true" class="fas fa-circle laboratsss" style=""></i>
                                                    <span style=" font-size: 0.9rem; ">Hasil : <b class="mr-1">{{
                                                        items_d.result_value }}</b>{{ items_d.unit }} </span>
                                                    <i aria-hidden="true" class="fas fa-circle laboratsss" style=""></i>


                                                    <i aria-hidden="true" class="fas fa-circle laboratsss" style=""></i>
                                                    <span style=" font-size: 0.9rem; ">Satuan : <b> {{
                                                        items_d.unit }}</b></span>
                                                </div> -->
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.LIST_RAD.length > 0">
            <TLabView title="RADIOLOGI" straight class="list-widget-v3 " :items="props.riwayat.LIST_RAD"
              @goto-detail="emits('openEMR', { namaemr: 'Radiologi', url_form: 'order-radiologi' })" squared colored
              :isPasienAktif="isPasienAktif" :norec_pd="props.registrasi.norec_pd" :pasien="props.pasien">
            </TLabView>

          </div>
          <div class="column is-6" v-if="props.riwayat.LIST_BEDAH.length > 0">
            <div class="project-files">
              <div class="updates">
                <!--Header-->
                <div class="updates-header">
                  <h3 class="dark-inverted">BEDAH</h3>
                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                    @click="emits('openEMR', { namaemr: 'Bedah', url_form: 'order-bedah' })">View
                    All</a>
                </div>
                <div class="updates-list">
                  <div class="panjang-250">
                    <div class="columns is-multiline">
                      <div class="timeline2-wrapper">
                        <div class="timeline2-header"></div>
                        <div class="timeline2-wrapper-inner">
                          <div class="timeline2-container">
                            <div class="timeline2-item is-unread" v-for="(item, index) in props.riwayat.LIST_BEDAH"
                              :key="index">

                              <div class="date">
                                <span>{{
                                  H.formatDateIndoSimple(item.tgloperasi)
                                }}</span>
                              </div>
                              <div :class="'dot is-' + listColor[index + 1]">
                              </div>
                              <div class="content-wrap">
                                <div class="content-box">
                                  <div class="status"></div>
                                  <div class="box-text">
                                    <div class="meta-text">
                                      <p>
                                        <span>{{
                                          item.namaproduk
                                        }}</span>
                                        <a></a>
                                        <br>
                                        <VTag :label="item.status" :color="item.color_status" rounded />.
                                      </p>
                                      <span> {{
                                        item.namalengkap
                                      }}</span>
                                    </div>
                                  </div>
                                  <div class="box-end">
                                    <!-- <a> {{
                                                                                        item.estimasiwaktuoperasi
                                                                                    }}</a> -->
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
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.LIST_KONSUL.length > 0">
            <div class="project-files">
              <div class="updates">
                <!--Header-->
                <div class="updates-header">
                  <h3 class="dark-inverted">KONSULTASI</h3>
                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                    @click="emits('openEMR', { namaemr: 'Konsultasi', url_form: 'konsultasi' })">View All</a>
                </div>
                <div class="updates-list" v-if="props.riwayat.isLoading == true">
                  <div class="panjang-250">
                    <div class="update-item is-dark-bordered-12" v-for="(item, index) in 6" :key="index">
                      <VPlaceload :lines="1" class="mr-2" />
                      <span class="dark-inverted">
                        <VPlaceload :lines="1" width="100%" />
                      </span>
                    </div>
                  </div>
                </div>
                <div class="updates-list" v-else-if="props.riwayat.LIST_KONSUL.length > 0">
                  <div class="panjang-250">
                    <div class="columns is-multiline">
                      <div class="column is-12 mb--15" v-for="(item, index) in props.riwayat.LIST_KONSUL" :key="index">
                        <div class="space-b">
                          <div class="file-box" style="width:95%">
                            <img :src="'/images/icons/files/resep-' + item.no + '.svg'" alt="" />
                            <div class="meta">
                              <span>{{ item.ruangantujuan }} </span>
                              <span>{{ item.pengonsul }} </span>
                              <!-- <span>
                                <b>{{ item.norec }}</b> Qty
                                <i aria-hidden="true" class="fas fa-circle"></i>
                                {{ H.formatRp(item.total, 'Rp.') }}

                              </span> -->
                            </div>
                            <div class="is-right is-dots is-spaced dropdown end-action">
                              <span> {{
                                H.formatDateIndoSimple(item.tglorder)
                              }}</span>
                            </div>
                            <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2" color="success" circle
                              outlined raised v-tooltip.bottom="'Jawab '" @click="jawab(item)">
                            </VIconButton>
                          </div>
                          <div class="ml-2 mt-5">
                            <i aria-hidden="true" class="fas fa-check-circle" style="color:var(--success)"
                              v-if="item.status == 'Selesai'"></i>
                            <i class="fas fa-pause-circle" aria-hidden="true" style="color:var(--warning)" v-else></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- {{ props.skdp.length }} -->
          <div class="column is-6" v-if="props.skdp.length > 0">
            <div class="project-files">
              <div class="updates">
                <div class="updates-header">
                  <h3 class="dark-inverted">SKDP</h3>
                  <!-- <a v-if='isPasienAktif' class="action-link" tabindex="0"
                    @click="emits('openEMR', { namaemr: 'Konsultasi', url_form: 'konsultasi' })">View All</a> -->
                </div>
                <div class="updates-list" v-if="props.riwayat.isLoading == true">
                  <div class="panjang-250">
                    <div class="update-item is-dark-bordered-12" v-for="(item, index) in 6" :key="index">
                      <VPlaceload :lines="1" class="mr-2" />
                      <span class="dark-inverted">
                        <VPlaceload :lines="1" width="100%" />
                      </span>
                    </div>
                  </div>
                </div>
                <div class="updates-list" v-else-if="props.skdp">
                  <div class="panjang-250">
                    <div class="columns is-multiline">
                      <div class="column is-12 mb--15" v-for="(item, index) in props.skdp" :key="index">
                        <div class="space-b">
                          <div class="file-box" style="width:95%">
                            <img :src="'/images/icons/files/resep-1.svg'" alt="" />
                            <div class="meta">
                              <!-- {{ item[0] }} -->
                              <span>{{ item[0].namaruangan }} </span>
                              <span>{{ item[0].tglperjanjian }} </span>
                              <span>{{ item[0].namalengkap }} </span>
                            </div>
                            <!-- <div class="is-right is-dots is-spaced dropdown end-action">
                              <span> {{
                                H.formatDateIndoSimple(item.tglorder)
                              }}</span> -->
                            <!-- </div> -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- EMR SIMRS LAMA -->
          <div class="column is-6" v-if="props.riwayat.kajian_awal != undefined && props.riwayat.kajian_awal.length > 0" >
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>{{props.riwayat.kajian_awal.name}} </h3>
                  </div>
                  <div class="right">
                  </div>
                </div>
                <div class="creative-list panjang-250">
                  <div v-for="(item, xx) in props.riwayat.kajian_awal" :key="xx" class=" mb-1">
                    <TWidgetList
                        v-if="item.Keluhan != null && item.Keluhan != '-'  && item.Keluhan != ''"
                       :title="'Keluhan'" :subtitle="item.Keluhan" :subtitle2="H.formatDateOnly(item.TglMasuk)"
                       :subtitle3="item.NamaPerawat"
                       :color_sub_3="'success'"
                       class="inbox-widget-3" />
                      <TWidgetList v-if="item.RwytPenyakit != null && item.RwytPenyakit != '-'  && item.RwytPenyakit != ''"
                       :title="'Riwayat Penyakit'" :subtitle="item.RwytPenyakit" :subtitle2="H.formatDateOnly(item.TglMasuk)"
                       :subtitle3="item.NamaPerawat"
                       :color_sub_3="'danger'"
                       class="inbox-widget-3" />
                      <TWidgetList
                      v-if="item.RwytAlergi != null && item.RwytAlergi != '-'  && item.RwytAlergi != ''"
                      :title="'Riwayat Alergi'" :subtitle="item.RwytAlergi" :subtitle2="H.formatDateOnly(item.TglMasuk)"
                      :subtitle3="item.NamaPerawat"
                      :color_sub_3="'info'"
                      class="inbox-widget-3" />
                      <TWidgetList
                      v-if="item.Rwytobat != null && item.Rwytobat != '-'  && item.Rwytobat != ''"
                      :title="'Riwayat Obat'" :subtitle="item.Rwytobat" :subtitle2="H.formatDateOnly(item.TglMasuk)"
                      :subtitle3="item.NamaPerawat"
                      :color_sub_3="'purple'"
                      class="inbox-widget-3" />
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.catatan_dokter != undefined && props.riwayat.catatan_dokter.length > 0" >
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>{{props.riwayat.catatan_dokter.name}} </h3>
                  </div>
                  <div class="right">
                  </div>
                </div>
                <div class="creative-list panjang-250">
                  <div v-for="(item, xx) in props.riwayat.catatan_dokter" :key="xx" class=" mb-1">
                    <TWidgetList
                        v-if="item.CatatanS != null && item.CatatanS != '-'  && item.CatatanS != ''"
                       :title="'S'" :subtitle="item.CatatanS"
                        :subtitle2="H.formatDateOnly(item.TglMasuk)"
                        :subtitle3="item.DokterPemeriksa"
                        :color_sub_3="'success'"
                        class="inbox-widget-3" />

                    <TWidgetList
                        v-if="item.CatatanO != null && item.CatatanO != '-'  && item.CatatanO != ''"
                        :title="'O'" :subtitle="item.CatatanO"
                        :subtitle2="H.formatDateOnly(item.TglMasuk)"
                        :subtitle3="item.DokterPemeriksa"
                        :color_sub_3="'green'"
                        class="inbox-widget-3" />
                        <TWidgetList
                        v-if="item.DiagnosaDokter != null && item.DiagnosaDokter != '-'  && item.DiagnosaDokter != ''"
                       :title="'A'" :subtitle="item.DiagnosaDokter"
                        :subtitle2="H.formatDateOnly(item.TglMasuk)"
                        :subtitle3="item.DokterPemeriksa"
                        :color_sub_3="'blue'"
                        class="inbox-widget-3" />
                        <TWidgetList
                        v-if="item.CatatanP != null && item.CatatanP != '-'  && item.CatatanP != ''"
                       :title="'P'" :subtitle="item.CatatanP"
                        :subtitle2="H.formatDateOnly(item.TglMasuk)"
                        :subtitle3="item.DokterPemeriksa"
                        :color_sub_3="'info'"
                        class="inbox-widget-3" />

                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.diagnosa != undefined && props.riwayat.diagnosa.length > 0" >
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>{{props.riwayat.diagnosa.name}} </h3>
                  </div>
                  <div class="right">
                  </div>
                </div>
                <div class="creative-list panjang-250">
                  <div v-for="(item, xx) in props.riwayat.diagnosa" :key="xx" class=" mb-1">
                    <TWidgetList
                        v-if="item.NamaDiagnosaDokter != null && item.NamaDiagnosaDokter != '-'  && item.NamaDiagnosaDokter != ''"
                        :title="item.JenisDiagnosa" :subtitle="item.KdDiagnosaICD10 + ' ' + item.NamaDiagnosaDokter"
                        :subtitle2="H.formatDateOnly(item.TglPeriksa)"
                        :subtitle3="item.DokterPemeriksa"
                        :color_sub_3="'orange'"
                        class="inbox-widget-3" />

                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.order_resep != undefined && props.riwayat.order_resep.length > 0" >
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>{{props.riwayat.order_resep.name}} </h3>
                  </div>
                  <div class="right">
                  </div>
                </div>
                <div class="creative-list panjang-250">
                  <div v-for="(item, xx) in props.riwayat.order_resep" :key="xx" class=" mb-1">
                    <TWidgetList
                        :title="item.NamaBarang" :subtitle="'jml : ' + item.Jml + ' aturan pakai : ' + item.AturanPakai"
                        :subtitle2="H.formatDateOnly(item.TglOrder)"
                        :subtitle3="item.Dokter"
                        :color_sub_3="'danger'"
                        class="inbox-widget-3" />
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.rujukan_penunjang != undefined && props.riwayat.rujukan_penunjang.length > 0" >
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>{{props.riwayat.rujukan_penunjang.name}} </h3>
                  </div>
                  <div class="right">
                  </div>
                </div>
                <div class="creative-list panjang-250">
                  <div v-for="(item, xx) in props.riwayat.rujukan_penunjang" :key="xx" class=" mb-1">
                    <TWidgetList
                        :title="item.RuanganTujuan" :subtitle="'asal : ' +item.RuanganPerujuk"
                        :subtitle2="H.formatDateOnly(item.TglDirujuk)"
                        :subtitle3="item.DokterPerujuk"
                        :color_sub_3="'success'"
                        class="inbox-widget-3" />
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.rad != undefined && props.riwayat.rad.length > 0" >
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>{{props.riwayat.rad.name}} </h3>
                  </div>
                  <div class="right">
                  </div>
                </div>
                <div class="creative-list panjang-250">
                  <div v-for="(item, xx) in props.riwayat.rad" :key="xx" class=" mb-1">
                    <TWidgetList
                        :title="item.Dokter" :subtitle="'Expertise : ' +item.CatatanDokter"
                        :subtitle2="H.formatDateOnly(item.TglHasil)"
                        :color_sub_3="'success'"
                        class="inbox-widget-3" />
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-6" v-if="props.riwayat.labpa != undefined && props.riwayat.labpa.length > 0" >
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>{{props.riwayat.labpa.name}} </h3>
                  </div>
                  <div class="right">
                  </div>
                </div>
                <div class="creative-list panjang-250">
                  <div v-for="(item, xx) in props.riwayat.labpa" :key="xx" class=" mb-1">
                    <TWidgetList
                        v-if="item.Makro != null && item.Makro != '-'  && item.Makro != ''"
                        :title="'Mikro'" :subtitle="item.Makro"
                        :subtitle2="H.formatDateOnly(item.TglTransaksi)"
                        :subtitle3="item.DokterPA"
                        :color_sub_3="'green'"
                        class="inbox-widget-3" />
                        <TWidgetList
                        v-if="item.Mikro != null && item.Mikro != '-'  && item.Mikro != ''"
                       :title="'Kesimpulan'" :subtitle="item.Mikro"
                        :subtitle2="H.formatDateOnly(item.TglTransaksi)"
                        :subtitle3="item.DokterPA"
                        :color_sub_3="'blue'"
                        class="inbox-widget-3" />
                        <TWidgetList
                        v-if="item.Kesimpulan != null && item.Kesimpulan != '-'  && item.Kesimpulan != ''"
                       :title="'Saran'" :subtitle="item.Kesimpulan"
                        :subtitle2="H.formatDateOnly(item.TglTransaksi)"
                        :subtitle3="item.DokterPA"
                        :color_sub_3="'success'"
                        class="inbox-widget-3" />
                        <TWidgetList
                        v-if="item.Saran != null && item.Saran != '-'  && item.Saran != ''"
                        :title="'Saran'" :subtitle="item.Saran"
                        :subtitle2="H.formatDateOnly(item.TglTransaksi)"
                        :subtitle3="item.DokterPA"
                        :color_sub_3="'danger'"
                        class="inbox-widget-3" />
                    </div>
                </div>
              </div>
            </div>
          </div>


          <!-- <div class="column is-6" v-if="props.riwayat.rujukan_nonpenunjang != undefined && props.riwayat.rujukan_nonpenunjang.length > 0" >
            <div class="project-files">
              <div class="widget creative-list-widget">
                <div class="widget-toolbar">
                  <div class="left">
                    <h3>{{props.riwayat.rujukan_nonpenunjang.name}} </h3>
                  </div>
                  <div class="right">
                  </div>
                </div>
                <div class="creative-list panjang-250">
                  <div v-for="(item, xx) in props.riwayat.rujukan_nonpenunjang" :key="xx" class=" mb-1">
                    <TWidgetList
                        :title="item.RuanganTujuan" :subtitle="'asal : ' +item.RuanganPerujuk"
                        :subtitle2="H.formatDateOnly(item.TglDirujuk)"
                        :subtitle3="item.DokterPerujuk"
                        :color_sub_3="'success'"
                        class="inbox-widget-3" />
                    </div>
                </div>
              </div>
            </div>
          </div> -->
          <!-- END EMR SIMRS LAMA -->
        </div>
      </div>
    </div>
  </VCard>
  <!-- </div> -->
  <!-- <div class="column " :class="props.hideRiwayat == true ? 'is-2' : 'is-3'"> -->
  <!-- <div class="column is-3" v-if="props.hideRiwayat == false">
                <div class="stock-card h-500">
                    <div class="action-bar">
                        <h3>Status </h3>
                    </div>
                    <StatusCheck :color="'amazon'" :icon="'fas fa-inverse fa-stethoscope'" :name="'Vital Sign'"
                        :status="props.riwayat.LIST_VITAL.length > 0"></StatusCheck>
                    <StatusCheck :color="'snapchat'" :icon="'fa-inverse lnir lnir-diagnosis'" :name="'Diagnosis'"
                        :status="props.riwayat.LIST_DIAGNOSIS.length > 0"></StatusCheck>
                    <StatusCheck :color="'tindakan'" :icon="'fa-inverse fa fa-clipboard-list'" :name="'Tindakan'"
                        :status="props.riwayat.LIST_TINDAKAN.length > 0"></StatusCheck>
                    <StatusCheck :color="'invision'" :icon="'fa-inverse fa fa-bong'" :name="'Laboratorium'"
                        :status="props.riwayat.LIST_LAB.length > 0"></StatusCheck>
                    <StatusCheck :color="'facebook'" :icon="'fa-inverse fa fa-radiation'" :name="'Radiologi'"
                        :status="props.riwayat.LIST_RAD.length > 0"></StatusCheck>
                    <StatusCheck :color="'pink'" :icon="'fa-inverse lnir lnir-hospital-bed-alt'" :name="'Bedah'"
                        :status="props.riwayat.LIST_BEDAH.length > 0"></StatusCheck>
                    <StatusCheck :color="'twitter'" :icon="'fa-inverse lnir lnir-medicine-alt'" :name="'Resep'"
                        :status="props.riwayat.LIST_RESEP.length > 0"></StatusCheck>
                    <div class="content">
                        <div class="is-divider" data-content="Lainnya"></div>
                        <div style="height: 300px;overflow: auto;padding: 5px;">
                            <TStatusPojokKanan :title="'Status Pasien'"
                                :subtitle="props.riwayat.LIST_STATUSPASIEN.statuspasien" class="inbox-widget-2" />
                            <TStatusPojokKanan :title="'Alergi'" :subtitle="props.riwayat.LIST_STATUSPASIEN.alergi"
                                class="inbox-widget-2" />
                            <TStatusPojokKanan :title="'Lama Rawat'"
                                :subtitle="props.riwayat.LIST_STATUSPASIEN.lamarawat" class="inbox-widget-2" />
                            <TStatusPojokKanan :title="'Tgl Pulang'"
                                :subtitle="props.riwayat.LIST_STATUSPASIEN.tglpulang" class="inbox-widget-2" />
                            <TStatusPojokKanan :title="'Status Pulang'"
                                :subtitle="props.riwayat.LIST_STATUSPASIEN.statuspulang" class="inbox-widget-2" />


                        </div>

                    </div>
                </div>
            </div> -->
  <!-- </div> -->

  <!-- </div> -->
  <Dialog v-model:visible="modalInput" modal header="Konsultasi" :style="{ width: '60vw' }">
            <div class="columns is-multiline">
                <div class="column is-3">
                    <VDatePicker class="pt-0 pb-0 pl-0" v-model="input.tanggal" color="green" trim-weeks mode="dateTime">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VLabel class="required-field">Tanggal</VLabel>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                        class="is-rounded" :disabled="disabledJawab" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-3">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">Ruang Asal</VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.ruanganasal" :options="d_Ruangan" :optionLabel="'label'"
                                class="is-rounded" placeholder="Ruang Asal" style="width: 100%;" :filter="true" showClear
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">Ruangan Tujuan</VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.ruangantujuan" :options="d_Ruangan" :optionLabel="'label'"
                                class="is-rounded" placeholder="Ruang Tujuan" style="width: 100%;" :filter="true"
                                :disabled="disabledJawab" showClear />
                        </VControl>
                    </VField>
                </div>
                <!-- <div class="column is-3">
                    <span style="font-weight: 500;">Kelas</span>
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="feather:search">
                          <Dropdown v-model="input.kelas" :options="d_Kelas" :optionLabel="'label'"
                                placeholder="ketik Nama Kelas" style="width: 100%;" :filter="true" showClear
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div> -->
                <div class="column is-3">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="mb-0">Dokter/Pegawai Medis </VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.dokter" :options="d_Dokter" :optionLabel="'label'"
                                placeholder="Cari Petugas ..." style="width: 100%;" :filter="true" showClear
                                :disabled="disabledJawab" />
                            <!-- <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchPegawai($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas ..."
                                class="mt-2 is-rounded" :disabled="disabledJawab" /> -->
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top: 20px;">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="input.rawatBersama" label="Rawat Bersama" color="danger"
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top: 20px;">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="input.konsultasi" label="Konsultasi" color="danger"
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VLabel>Lain-lain</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="input.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain "
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VTextarea v-model="input.keterangan" rows="3" placeholder="Keterangan"
                                :disabled="disabledJawab">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12" v-if="disabledJawab">
                    <VField>
                        <VLabel>Jawaban</VLabel>
                        <VControl>
                            <VTextarea v-model="input.jawaban" rows="3" placeholder="Jawaban">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </div>
            <template #footer>
                <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                    Batal
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                    @click="simpan()"> Simpan
                </VButton>
            </template>
        </Dialog>

</template>
<script setup lang="ts">
import { h, reactive, ref, computed, defineComponent, watch, PropType, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import * as H from '/@src/utils/appHelper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { listActionEMR } from '/@src/data/module/hard/action_emr'
import TEmrAsesmenAwal from './t-emr-asesmen-awal.vue'
import TLabView from './t-lab-view.vue'
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import TWidgetList from './t-widget-list.vue'
import moment from 'moment'
// import Dialog from 'primevue/dialog';

const emits = defineEmits<{
  (e: 'showRiwayat'): void,
  (e: 'hiddenRiwayat'): void,
  (e: 'reloadRiwayat'): void,
  (e: 'billingPasien'): void,
  (e: 'showMenuEMR'): void,
  (e: 'editEMR', value: any): void,
  (e: 'hapusEMR', value: any): void,
  (e: 'cetakEMR', value: any): void,
  (e: 'openEMR', value: any): void,
  (e: 'showNavStatus'): void,
}>()
const props = defineProps({
  registrasi: {
    type: Object as PropType<any>,
  },
  riwayat: {
    type: Object as PropType<any>,
  },
  pasien: {
    type: Object as PropType<any>,
  },
  skdp: {
    type: Array as PropType<any>,
  },
  hideRiwayat: {
    type: Boolean
  },
  isLoadingRiwayat: {
    type: Boolean
  },
  isPasienAktif: {
    type: Boolean
  },
})
// const props = withDefaults(defineProps<VTEmrDetailProps>(), {
//     registrasi: () => { },
// })
const modalFormIBS = ref(false)
const clickListFormIBS = () => {
  modalFormIBS.value = true;
}
const getRandomArbitrary = (min:any, max:any)=> {
    return Math.random() * (max - min) + min;
}
const avatarColors: any = reactive([
    'primary',
    'success',
    'info',
    'warning',
    'danger',
    'h-purple',
    'h-orange',
    'h-blue',
    'h-green',
    'h-red',
    'h-yellow',
])
const router = useRouter()
const route = useRoute()
const ID_PASIEN: any = route.query.nocmfk
const NOREC_PD: any = route.query.norec_pd
const modalInput = ref(false)
const listColor: any = ref(Object.keys(useThemeColors()))
const isEmpty = ref(true)
const rowGroupMetadata = ref({})
isEmpty.value = props.riwayat.isEmpty
const topicList: any = ref([{
  id: 0,
  icon: 'feather:chrome',
  color: 'info',
  name: 'Browsers',
  category: 'Technology',

},
{
  id: 1,
  icon: 'feather:wind',
  color: 'green',
  name: 'Natural Ecosystems',
  category: 'Environment',

},
{
  id: 2,
  icon: 'feather:cpu',
  color: 'orange',
  name: 'Computer Chips',
  category: 'Technology',

},
{
  id: 3,
  icon: 'feather:music',
  color: 'purple',
  name: 'Modal Improvisation',
  category: 'Music',

},
{
  id: 4,
  icon: 'feather:monitor',
  color: 'yellow',
  name: 'Old Movies',
  category: 'Entertainment',
  users: [
    {
      id: 28,
      picture: '/demo/avatars/32.jpg',
    },
    {
      id: 20,
      picture: '/demo/avatars/22.jpg',
    },
  ],
},
{
  id: 5,
  icon: 'feather:github',
  color: 'purple',
  name: 'Git Management',
  category: 'Technology',

}])
const isLoading: any = ref(false)
const input: any = reactive({ tanggal: new Date() })
const disabledJawab = ref(false)
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
// const d_Kelas: any = ref([])
const add = async () => {
    disabledJawab.value = false
    input.tanggal = new Date()
    d_Ruangan.value.forEach((element) => {
        if (element.value == props.registrasi.objectruanganlastfk) {
            input.ruanganasal = element
        }
    });
    // await fetchKelas({ query: 'NON KELAS' })
    // if (d_Kelas.value.length) {
    //     input.kelas = d_Kelas.value[0]
    // }


    modalInput.value = true
}
const search = ref('');
const filteredEmr = computed(() => {
  const keyword = search.value.toLowerCase().trim();
  const listEmr = props.riwayat.LIST_EMR || [];

  if (!keyword) return [...listEmr].sort((a, b) => new Date(b.last_update) - new Date(a.last_update));

  return listEmr.map((item) => {
    const namaLower = item.namaemr ? item.namaemr.toLowerCase() : '';
    const score = namaLower.includes(keyword) ? keyword.length / namaLower.length : 0;
    return { ...item, score };
  }).filter((item) => item.score > 0).sort((a, b) => b.score - a.score || new Date(b.last_update) - new Date(a.last_update));
});

const simpan = async () => {
    if (!input.tanggal) {
        H.alert('error', 'Tanggal harus di isi')
        return
    }
    if (!input.ruanganasal) {
        H.alert('error', 'Ruang Asal harus di isi')
        return
    }
    if (!input.ruangantujuan) {
        H.alert('error', 'Ruang Tujuan harus di isi')
        return
    }
    // if (!input.dokter) {
    //     H.alert('error', 'Dokter harus di isi')
    //     return
    // }
    // if (!input.kelas) {
    //     H.alert('error', 'Kelas Konsultasi Harus di isi')
    //     return
    // }
    let formData = {
        'norec_so': input.norec != undefined ? input.norec : '',
        'norec_pd': props.registrasi.norec_pd,
        'nocmfk': props.pasien.nocmfk,
        'pegawaifk': input.dokter ? input.dokter.value : null,
        'objectruanganasalfk': input.ruanganasal.value,
        'objectruangantujuanfk': input.ruangantujuan.value,
        'keterangan': input.keterangan ? input.keterangan : '',
        'tanggalKonsul': H.formatDate(input.tanggal, 'YYYY-MM-DD HH:mm'),
        'rawatbersama': input.rawatBersama ? input.rawatBersama : null,
        'konsultasi': input.konsultasi ? input.konsultasi : null,
        'lainlain': input.lainlain ? input.lainlain : null,
        'ruangantujuan': input.ruangantujuan.label,
        // 'objectkelasfk': input.kelas.value,
        'noregistrasi': props.registrasi.noregistrasi,
        'nocm': props.pasien.nocm,
        'namapasien': props.pasien.namapasien,
    }
    isLoading.value = true
    if (disabledJawab.value == true) {
        formData = {
            'norec_so': input.norec,
            'jawaban': input.jawaban,
        }

        await useApi().post('/emr/jawab-order-konsul', formData).then((r) => {
            isLoading.value = false
            loadRiwayat()
            modalInput.value = false
        }).catch((e: any) => {
            isLoading.value = false
        })
        return
    }


    await useApi().post('/emr/simpan-order-konsul', formData).then((r) => {
        isLoading.value = false
        sendNotification(r)
        loadRiwayat()
        modalInput.value = false
    }).catch((e: any) => {
        isLoading.value = false
    })

}
const edit = async (e: any) => {
    disabledJawab.value = false
    input.norec = e.norec
    input.tanggal = new Date(e.tglorder)
    d_Ruangan.value.forEach((element: any) => {
        if (element.value == e.objectruanganfk) {
            input.ruanganasal = element
        }
        if (element.value == e.objectruangantujuanfk) {
            input.ruangantujuan = element
        }
    });

    d_Dokter.value.forEach((element: any) => {
        if (element.value == e.pegawaifk) {
            input.dokter = element
        }
    });
    // d_Kelas.value.forEach((element: any) => {
    //     if (element.value == e.objectkelasfk) {
    //         input.kelas = element
    //     }
    // });
    input.konsultasi = e.konsultasi ? e.konsultasi : false
    input.lainlain = e.lainlain
    input.rawatBersama = e.rawatbersama ? e.rawatbersama : false
    input.keterangan = e.keteranganorder
    input.jawaban = e.keteranganlainnya

    modalInput.value = true
}
const jawab = async (e: any) => {
  console.log(e);
    edit(e)
    disabledJawab.value = true
}
const kembaliKeun = () => {
    modalInput.value = false
    input = {
        tanggal: new Date()
    }
}
const loadDrop = async () => {
    d_Ruangan.value = await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan`)
    // d_Kelas.value = await useApi().get(`emr/dropdown/kelas_m?select=id,namakelas`)
    d_Dokter.value = await useApi().get( `emr/dropdown/pegawai_m?select=id,namalengkap`)
}
// const fetchPegawai = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//     ).then((response) => {
//         d_Dokter.value = response
//     })
// }
// const fetchKelas = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`
//     ).then((response) => {
//         d_Kelas.value = response
//     })
// }
const listMenu = ref(listActionEMR)
const filterMenu: any = ref('')
const filteredMenu = computed(() => {
  if (!filterMenu.value) {
    return listMenu.value
  }

  return listMenu.value.filter((items) => {
    return (
      items.name.match(new RegExp(filterMenu.value, 'i'))
    )
  })
})

function tambah() {
  modalInput.value = true
}
const showMenu = async (e: any) => {
  let norec_pd = '', nocmfk = ''
  if (props.registrasi.norec_pd == undefined) {
    norec_pd = NOREC_PD
    nocmfk = ID_PASIEN
  } else if (props.registrasi.norec_pd != NOREC_PD) {
    return
  }
  await router.push({
    name: e.form,
    query: {
      nocmfk: nocmfk,
      norec_pasien_daftar: norec_pd,
      norec_pd: norec_pd,
    }
  })
}

const editEMRAsesmen = (e: any) => {

  let json = {
    'namaemr': 'Asesmen Awal',
    'url_form': 'asesmen-awal',
    'emrpasienfk': e.norec
  }

  emits('editEMR', json)
}

const hitungHariSejak = (tanggal: string) => {
  if (!tanggal) return '-'

  const parsed = moment(tanggal, 'YYYY-MM-DD HH:mm:ss')

  if (!parsed.isValid()) return '-'

  const diff = moment().diff(parsed, 'days')

  if (diff >= 90) {
    return 'Sudah harus diisi'
  }

  return `Sudah diisi selama ${diff} hari`
}

// const updateRowGroupMetaData = async() => {
//     rowGroupMetadata.value = {};

//     if (props.riwayat.LIST_LAB.length) {
//         for (let x = 0; x < props.riwayat.LIST_LAB.length; x++) {
//             const element = props.riwayat.LIST_LAB[x];
//             for (let i = 0; i < element.hasil_lab.length; i++) {
//                 let rowData = element.hasil_lab[i];
//                 let treatment_name = rowData.treatment_name;

//                 if (i == 0) {
//                     rowGroupMetadata.value[treatment_name] = { index: 0, size: 1 };
//                 } else {
//                     let previousRowData = element.hasil_lab[i - 1];
//                     let previousRowGroup = previousRowData.treatment_name;
//                     if (treatment_name === previousRowGroup)
//                         rowGroupMetadata.value[treatment_name].size++;
//                     else
//                         rowGroupMetadata.value[treatment_name] = { index: i, size: 1 };
//                 }
//             }
//         }
//     }
// }
// updateRowGroupMetaData()
// onMounted(() => {
//   loadDrop()
// });
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/emr-detail';

.project-files {
  padding: 0;
}
</style>
