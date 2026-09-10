<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <VCardHead title="" class="no-border p-1 riwayat-reg-nomobile">
          <template #action>
            <VIconButton circle icon="feather:calendar" class="mr-2" raised bold @click="filter()">
            </VIconButton>
            <VIconButton circle icon="feather:refresh-cw" raised bold @click="reloadListReg()"
              :loading="isLoadingRegis">
            </VIconButton>
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
                  <div class="column is-12">
                    <VCard class="is-clickable is-grey hover" @click="emr(items)"
                      :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis' : ''" style="padding:10px;">
                      <i aria-hidden="true" class="lnir lnir-medicine mr-2"></i>
                      <span class="span-text-left-bar">{{ H.formatDateIndo(items.tglregistrasi) }}</span>
                      <span v-if="selectedRegistrasi.norec == items.norec" class="is-pulled-right ml-2 mt-3"
                        :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis mt-3' : ''"
                        style="margin-top: 2.5rem !important;">
                        <i aria-hidden="true" class="fas fa-arrow-right"></i>
                      </span>
                      <br>
                      <i aria-hidden="true" class="lnir lnir-house-alt mr-2"></i>
                      <span class="span-text-left-bar">{{ items.namaruangan }}</span>
                      <br>
                      <i aria-hidden="true" class="lnir lnir-wheelchair mr-2"></i>
                      <span class="span-text-left-bar">
                        KONSUL :
                        <span
                          v-html="items.apdd && Object.values(items.apdd).length ?
                            Object.values(items.apdd).slice(0, 6).map((item, index) =>
                              `<br><span style='color: ${index % 2 === 0 ? 'green' : 'darkgreen'};'>- ${item.namaruangan}</span>`).join('') : ''">
                        </span>
                      </span>
                      <br>
                      <i aria-hidden="true" class="fas fa-user-md mr-2"
                        :style="items.dokter ? '' : 'color: var(--light-text);'"></i>
                      <span class="span-text-left-bar" :style="items.dokter ? '' : 'color: var(--light-text);'">{{
                        items.dokter ?
                          items.dokter : '-' }}</span>
                      <br>
                      <div class="mt-1" style="justify-content: space-between; display: inline-flex;">
                        <i class="lnir lnir-diagnosis mr-2 " aria-hidden="true"
                          :style="[items.diagnosis.length ? '' : 'color: var(--light-text);']"> </i>
                        <span class="span-text-left-bar text-diagnosis"
                          :style="items.diagnosis.length ? '' : 'color: var(--light-text);'">{{
                            items.diagnosis.length ? items.diagnosis[0].kddiagnosa + ' - ' +
                              items.diagnosis[0].namadiagnosa : 'Diagnosis utama belum ada' }}</span>
                      </div>
                      <div class="mt-1 ml-2" style="justify-content: space-between; display: inline-flex;" v-if="items.isberkas == true ||
                        items.laboratorium.length && items.laboratorium[0].keteranganorder == 'Order Laboratorium'
                        || items.radiologi.length && items.radiologi[0].keteranganorder == 'Order Radiologi'">
                        <VIconButton icon="fas fa-book-medical" light raised bold @click="berkasPasien" color="danger"
                          v-if="items.isberkas == true" v-tooltip.bubble="'BERKAS PASIEN'" />
                        <VIconButton icon="fas fa-bong" light raised bold @click="hasilLab" color="danger"
                          v-if="items.laboratorium.length && items.laboratorium[0].keteranganorder == 'Order Laboratorium'"
                          v-tooltip.bubble="'LABORATORIUM'" style="margin-left: 10px" />
                        <VIconButton icon="fas fa-radiation" light raised bold @click="hasilRad" color="danger"
                          v-if="items.radiologi.length && items.radiologi[0].keteranganorder == 'Order Radiologi'"
                          v-tooltip.bubble="'RADIOLOGI'" style="margin-left: 10px" />
                      </div>

                      <div class="column is-1" v-if="items.isdetail"></div>
                      <div class="column is-11" v-if="items.isdetail">
                        <div class="columns is-multiline">

                          <div class="column is-6" v-if="riwayatPemeriksaan.LIST_EMR.length > 0">
                            <div class="project-files">
                              <div class="updates">
                                <div class="updates-header">
                                  <h3 class="dark-inverted">EMR</h3>

                                </div>

                                <div class="updates-list">
                                  <div class="panjang-250">
                                    <div v-for="(item, index) in riwayatPemeriksaan.LIST_EMR" :key="item.url_form"
                                      class="inner-list-item media-flex-center ">
                                      <VIconBox :rounded="true" :color="listColor[index + 1]">
                                        <i aria-hidden="true" :class="item.icon"></i>
                                      </VIconBox>
                                      <div class="flex-meta is-light">
                                        <a @click="editAss(item)">{{ item.namaemr }}</a>
                                        <span>{{ H.formatDateIndoSimple(item.last_update) }}</span>
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
                                            <a @click="() => { emits('cetakEMR', item) }"
                                              class="dropdown-item is-media">
                                              <div class="icon">
                                                <i aria-hidden="true" class="lnil lnil-trash"></i>
                                              </div>
                                              <div class="meta">
                                                <span>Cetak</span>
                                                <span>Cetak data EMR</span>
                                              </div>
                                            </a>
                                            <a @click="() => { emits('hapusEMR', item) }"
                                              class="dropdown-item is-media">
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
                          </div>

                          <div class="column is-6" v-if="riwayatPemeriksaan.LIST_LAB.length > 0">
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
                                  <div v-for="item in riwayatPemeriksaan.LIST_LAB" :key="item.id" class=" mb-2">
                                    <div :class="item.isdetail ? '' : ''">
                                      <div class="creative-list-item  is-clickable" :class="'is-' + item.color_status"
                                        style="margin-bottom:0;"
                                        :style="item.isdetail ? 'height:60%;border-radius: 10px 10px 0 0;' : ''"
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
                                          <tbody v-if="item.hasil_lab.length > 0"
                                            v-for="(items_d, rowIndex) in item.hasil_lab" :key="rowIndex">
                                            <tr
                                              v-if="props.riwayat.LIST_LAB_GROUP[items_d.treatment_name] != undefined &&
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
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="column is-6" v-if="riwayatPemeriksaan.LIST_RAD.length > 0">
                            <div class="project-files">
                              <div class="updates">
                                <!--Header-->
                                <div class="updates-header">
                                  <h3 class="dark-inverted">RADIOLOGI</h3>
                                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                                    @click="emits('openEMR', { namaemr: 'Resep', url_form: 'order-resep' })">View
                                    All</a>
                                </div>
                                <div class="updates-list" v-if="riwayatPemeriksaan.isLoading == true">
                                  <div class="panjang-250">
                                    <div class="update-item is-dark-bordered-12" v-for="(item, index) in 6"
                                      :key="index">
                                      <VPlaceload :lines="1" class="mr-2" />
                                      <span class="dark-inverted">
                                        <VPlaceload :lines="1" width="100%" />
                                      </span>
                                    </div>
                                  </div>
                                </div>
                                <div v-for="item in riwayatPemeriksaan.LIST_RAD" :key="item.id" class=" mb-2">
                                  <div :class="item.isdetail ? 'panjangaaaaaaa' : ''">
                                    <div class="timeline-item"
                                      style="background-color: #EAF1FF; padding: 10px;border-radius: 10px"
                                      :style="item.isdetail ? 'height:40%;border-radius: 10px 10px 0 0;' : ''">
                                      <div class="timeline-icon is-clickable"
                                        :class="[props.squared && 'is-squared', props.colored && 'is-' + item.color]"
                                        style=" border-color:#193C88;">
                                        <!-- <i aria-hidden="true" class="iconify" :data-icon="item.icon"></i> -->
                                        <i aria-hidden="true" :class="item.icon"></i>
                                      </div>
                                      <div class="timeline-content" style="margin-left: 10px;"
                                        @click="item.isdetail = !item.isdetail">
                                        <p>{{ item.namaproduk }}</p>
                                        <span>{{ item.tglorder }}</span>
                                      </div>
                                      <VTag :color="item.color_status" :label="item.status" />
                                    </div>
                                    <div v-if="item.isdetail == true" class="mt-5"
                                      style="border-radius: 0 0 10px 10px;background-color: #C8D3E9; padding: 10px;height:60%">
                                      <div class="mt-1-min" style="    display: flex;"
                                        v-if="item.expertise != null || item.order_complete == 1">
                                        <div style="width:50px;text-align:center;margin-right: 5px;"
                                          v-if="item.radiologiid == '00005448-1'"><img style="width:50px;height:40px"
                                            :src="'/images/simrs/0000079.png'">
                                          <a href="https://app.rsjpparamarta.com/service/v-echo-00005448-1.html"
                                            class="action-link" tabindex="0"
                                            style="color:var(--danger);  font-size: 0.8rem;">Video </a>
                                        </div>
                                        <VButton color="info" icon="feather:eye" raised rounded
                                          @click="lihatHasil(item)" :loading="item.isLoading" class="btn-slim mt-3">
                                          Hasil </VButton>
                                        <img style="width:30px;height:35px" class="ml-2 mt-2"
                                          :src="'/images/simrs/doc-rad.png'">
                                        <span class="mt-2 ml-2"
                                          style=" font-size: 0.8rem;font-style: inherit;font-weight: inherit;line-height: 1.1;height: 4.2em; width: 300px  !important; overflow: hidden !important;text-overflow: ellipsis;">
                                          {{ item.expertise ? item.expertise : 'Belum ada Expertise' }}
                                        </span>
                                      </div>
                                      <div v-else style="display: flex; flex-wrap: nowrap;">
                                        <span style="width:50px"></span>
                                        <img style="width:30px" :src="'/images/simrs/doc-rad.png'">
                                        <span class="mt-2 ml-2"
                                          style="font-size: 0.8rem;color: var(--light-text); font-style: inherit;font-weight: inherit;">Belum
                                          ada Expertise</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>





                          </div>

                          <div class="column is-6" v-if="riwayatPemeriksaan.LIST_TINDAKAN.length > 0">
                            <div class="project-files">
                              <!--Updates-->
                              <div class="updates">
                                <div class="updates-header">
                                  <h3 class="dark-inverted">TINDAKAN</h3>
                                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                                    @click="() => { emits('billingPasien') }">View All</a>
                                </div>
                                <div class="updates-list" v-if="riwayatPemeriksaan.isLoading == true">
                                  <div class="panjang-250">
                                    <div class="update-item is-dark-bordered-12" v-for="(item, index) in 6"
                                      :key="index">
                                      <VPlaceload :lines="1" class="mr-2" />
                                      <span class="dark-inverted">
                                        <VPlaceload :lines="1" width="100%" />
                                      </span>
                                    </div>
                                  </div>
                                </div>
                                <div class="updates-list" v-else-if="riwayatPemeriksaan.LIST_TINDAKAN.length > 0">
                                  <div class="panjang-250">
                                    <div class="update-item is-dark-bordered-12 "
                                      v-for="(item, index) in riwayatPemeriksaan.LIST_TINDAKAN" :key="index">
                                      <p>{{ item.namaproduk }}
                                        <span class="subtitle">{{ item.namaruangan }}</span>
                                      </p>
                                      <VTag :label="H.formatDateIndoSimple(item.tglpelayanan)" color="danger" rounded />
                                    </div>
                                  </div>
                                </div>


                              </div>
                            </div>
                          </div>

                          <div class="column is-6" v-if="riwayatPemeriksaan.LIST_RESEP.length > 0">
                            <div class="project-files">
                              <div class="updates">
                                <!--Header-->
                                <div class="updates-header">
                                  <h3 class="dark-inverted">RESEP</h3>
                                  <a v-if='isPasienAktif' class="action-link" tabindex="0"
                                    @click="emits('openEMR', { namaemr: 'Resep', url_form: 'order-resep' })">View
                                    All</a>
                                </div>
                                <div class="updates-list" v-if="riwayatPemeriksaan.isLoading == true">
                                  <div class="panjang-250">
                                    <div class="update-item is-dark-bordered-12" v-for="(item, index) in 6"
                                      :key="index">
                                      <VPlaceload :lines="1" class="mr-2" />
                                      <span class="dark-inverted">
                                        <VPlaceload :lines="1" width="100%" />
                                      </span>
                                    </div>
                                  </div>
                                </div>
                                <div class="updates-list" v-else-if="riwayatPemeriksaan.LIST_RESEP.length > 0">
                                  <div class="panjang-250">
                                    <div class="columns is-multiline">
                                      <div class="column is-12 mb--15"
                                        v-for="(item, index) in riwayatPemeriksaan.LIST_RESEP" :key="index">
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
                                            <i aria-hidden="true" class="fas fa-check-circle"
                                              style="color:var(--success)" v-if="item.status == 'Selesai'"></i>
                                            <i class="fas fa-pause-circle" aria-hidden="true"
                                              style="color:var(--warning)" v-else></i>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>



                          <div class="column is-12" v-else>
                            <VCard>
                              <span style="color: var(--light-text)">Data tidak ditemukan...</span>
                            </VCard>
                          </div>

                        </div>


                        <!-- <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="AssesmenMedis"
                                                    color="danger" v-tooltip.bubble="'Assesmen Medis'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;Assesmen Medis</span>
                                              </VCard>
                                              <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="hasilRad"
                                                    color="danger" v-tooltip.bubble="'Assesmen Keperawatan'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;Assesmen Keperawatan</span>
                                              </VCard>
                                              <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="hasilRad"
                                                    color="danger" v-tooltip.bubble="'CPPT'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;CPPT</span>
                                              </VCard>
                                              <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="hasilRad"
                                                    color="danger" v-tooltip.bubble="'Tindakan'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;Tindakan</span>
                                              </VCard>
                                              <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="hasilRad"
                                                    color="danger" v-tooltip.bubble="'Resep'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;Resep</span>
                                              </VCard>
                                              <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="hasilRad"
                                                    color="danger" v-tooltip.bubble="'Laboratorium'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;Laboratorium</span>
                                              </VCard>
                                              <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="hasilRad"
                                                    color="danger" v-tooltip.bubble="'Radiologi'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;Radiologi</span>
                                              </VCard>
                                              <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="hasilRad"
                                                    color="danger" v-tooltip.bubble="'Bedah'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;Bedah</span>
                                              </VCard>
                                              <VCard class="is-clickable is-grey" style="padding:10px">
                                                <VIconButton icon="fas fa-book-medical" light raised bold @click="hasilRad"
                                                    color="danger" v-tooltip.bubble="'Kontrol Pasien'" style="margin-left: 10px" /><span style="font-weight: bold">&emsp;Kontrol Pasien</span>
                                              </VCard> -->
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
            <VFlexPagination v-model:current-page="currentPage.page" class="mt-6" :item-per-page="currentPage.limit"
              :total-items="totalData" :max-links-displayed="10" />
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
          </div>
        </VCardHead>
      </div>

    </div>
  </div>

  <div class="column is-12" style="display: none !important">
    <VCard>
      <h1 style="font-weight: 500;">RIWAYAT PENGGUNAAN OBAT</h1>
      <div class="column" style="overflow: auto;">
        <table class="table-rpo">
          <thead>
            <tr>
              <th class="th-rpo" width="3%">No</th>
              <th class="th-rpo">Nama Obat</th>
              <th class="th-rpo">Bentuk/Sediaan</th>
              <th class="th-rpo">Dosis</th>
              <th class="th-rpo">Aturan Pakai</th>
              <th class="th-rpo" width="10%">#</th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in input.details" :key="index">
            <tr>
              <td class="td-rpo" style="text-align: center;">{{ item.no }}</td>
              <td class="td-rpo">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.obat" :suggestions="d_Obat" @complete="fetchObat($event)"
                      :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik nama obat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VInput type="text" v-model="item.aturanPakai" />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VInput type="text" v-model="item.bentuk" />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo">
                <VField class="">
                  <VControl>
                    <VInput type="text" v-model="item.dosis" />
                  </VControl>
                </VField>
              </td>
              <td class="td-rpo" style="vertical-align: inherit">
                <div class="column">
                  <VButtons style="justify-content:space-around">
                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                      v-tooltip.bubble="'Tambah '">
                    </VIconButton>
                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                      @click="removeItem(index)" color="danger">
                    </VIconButton>
                  </VButtons>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="column">
        <span style="font-weight: 500;">Catatan Petugas</span>
        <VField class="pt-3">
          <VControl>
            <VTextarea v-model="input.catatanPetugas" rows="2">
            </VTextarea>
          </VControl>
        </VField>
      </div>
    </VCard>
  </div>

  <div class="column is-12" style="display: none !important">
    <VCard>
      <div class="column is-3" style="margin-left: auto;">
        <span style="font-weight: 500;">Bandung</span>
        <VDatePicker v-model="input.tglDibuat" mode="dateTime" class="pt-3" style="width: 100%" trim-weeks
          :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }">
            <VField>
              <VControl icon="feather:calendar" fullwidth>
                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>

      <div class="columns is-multiline pt-5" style="justify-content: space-around;">
        <div class="column is-4">
          <div class="column p-0 mt-3" style="text-align: center;">
            <TandaTangan :elemenID="'signatureKeluarga'" :width="'180'" :height="'180'" class="dek" />
          </div>
          <div class="column">
            <span style="font-weight: 500;">Nama Pasien / Keluarga</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="item.namaBersangkutan" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-4">
          <div class="column p-0 mt-3" style="text-align: center;">
            <TandaTangan :elemenID="'signaturePetugas'" :width="'180'" :height="'180'" class="dek" />
          </div>
          <div class="column">
            <span style="font-weight: 500;">Petugas</span>
            <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas..."
                  @item-select="setTandaTanganPegawai($event)" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </VCard>
  </div>

  <VModal :open="showModalAssesmenMedis" title="Riwayat Assesmen Medis" :noclose="true" :size="'big'" actions="right"
    @close="showModalAssesmenMedis = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <div class="columns is-multiline">
            <div class="column is-3" v-if="riwayatPemeriksaan.LIST_EMR.length > 0">
              <div class="project-files">
                <div class="updates">
                  <div class="updates-header">
                    <h3 class="dark-inverted">EMR</h3>

                  </div>

                  <div class="updates-list">
                    <div class="panjang-250">
                      <div v-for="(item, index) in riwayatPemeriksaan.LIST_EMR" :key="item.url_form"
                        class="inner-list-item media-flex-center ">
                        <VIconBox :rounded="true" :color="listColor[index + 1]">
                          <i aria-hidden="true" :class="item.icon"></i>
                        </VIconBox>
                        <div class="flex-meta is-light">
                          <a @click="editAss(item)">{{ item.namaemr }}</a>
                          <span>{{ H.formatDateIndoSimple(item.last_update) }}</span>
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
                                  <i aria-hidden="true" class="lnil lnil-trash"></i>
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
            </div>

            <div class="column is-12" v-else>
              <VCard>
                <span style="color: var(--light-text)">Data tidak ditemukan...</span>
              </VCard>
            </div>

            <div class="column is-9" v-if="riwayatPemeriksaan.LIST_EMR.length > 0">

              <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                  v-if="selectedRegistrasi.norec != undefined && pasien">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="onTabMedis()"><span class="p-menuitem-icon fas fa-book-medical"></span><span
                        class="p-menuitem-text">Assesmen Medis</span></a>
                  </li>
                </ul>
              </div>
              <TProfilePasien></TProfilePasien>
              <!-- <TEMR></TEMR> -->
            </div>


          </div>
          <!-- <VField label="Alergi">
              <VControl>
                <VTextarea v-model="item.alergi" rows="3" placeholder="Alergi">
                </VTextarea>
              </VControl>
            </VField> -->
        </div>
      </form>
    </template>
    <template #action>
      <VButton @click="saveAlergiPasien(item.alergi)" :loading="isBtnLoading" color="primary" raised>
        Simpan</VButton>
    </template>
  </VModal>

  <Dialog v-model:visible="modalRiwayat" modal header="Detail Riwayat Pasien"
    :style="{ width: '90vw', 'max-height': '90vh', 'overflow-y': 'auto' }">
    <TabView>
      <TabPanel header="Tindakan">
        <DataTable :value="testRiwayatTIndakan" class="p-datatable-sm" tableStyle="min-width: 10rem"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="namaproduk" header="Nama Pelayanan" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="tglpelayanan" header="Tgl Pelayanan" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="namaruangan" header="Nama Ruangan" style="min-width: 200px;" frozen class="font-bold"></Column>
          <Column field="jumlah" header="Jumlah" style="min-width: 200px;" frozen class="font-bold">
          </Column>
        </DataTable>
      </TabPanel>
      <TabPanel header="Obat">
        <DataTable :value="testRiwayatTObat" class="p-datatable-sm" tableStyle="min-width: 10rem"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="noorder" header="No Order" style="min-width: 120px;" frozen class="font-bold"></Column>
          <Column field="namaruangan" header="Ruangan Pengorder" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="namaproduk" header="Nama Obat" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="namapegawai" header="Nama Dokter" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="tglpelayanan" header="Tgl Pelayanan" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="noregistrasi" header="No Registrasi" style="min-width: 200px;" frozen class="font-bold">
          </Column>
        </DataTable>
      </TabPanel>
      <TabPanel header="BHP">
        <DataTable class="p-datatable-sm" tableStyle="min-width: 10rem"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="noregistrasi" header="No Registrasi" style="min-width: 120px;" frozen class="font-bold">
          </Column>
          <Column field="noorder" header="No Order" style="min-width: 120px;" frozen class="font-bold"></Column>
          <Column field="namaruangan" header="Ruangan Pengorder" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="namaproduk" header="Nama Pelayanan" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="namalengkap" header="Nama Dokter" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="tanggal" header="Tgl Pelayanan" style="min-width: 200px;" frozen class="font-bold"></Column>
          <Column field="tglregistrasi" header="Tgl Registrasi" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column :exportable="false" header="Expertise" style="text-align: center;min-width: 100px;">
          </Column>
        </DataTable>
      </TabPanel>
      <TabPanel header="Laboratorium">
        <DataTable :value="testRiwayatLaboratorium" class="p-datatable-sm" tableStyle="min-width: 10rem"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="noorder" header="No Order" style="min-width: 120px;" frozen class="font-bold"></Column>
          <Column field="namaruangan" header="Ruangan Order" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <!-- <Column field="namaproduk" header="Nama Pelayanan" style="min-width: 200px;" frozen class="font-bold">
          </Column> -->
          <Column field="namalengkap" header="Nama Dokter" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="tglorder" header="Tgl Order" style="min-width: 200px;" frozen class="font-bold"></Column>
          <Column field="hasillab" header="Hasil Laboratorium" style="min-width: 200px;" frozen class="font-bold">
            <template #body="slotProps">
              <VIconButton type="button" v-if="slotProps.data.objectruangantujuanfk != [337, 302]" icon="feather:folder"
                class="mr-3" color="info" circle outlined raised v-tooltip.top="'Lihat Hasil'"
                @click="printHasil2(slotProps.data)">
              </VIconButton>
            </template>
          </Column>

        </DataTable>
      </TabPanel>
      <TabPanel header="Radiologi">
        <DataTable :value="testRiwayatRadiologi" class="p-datatable-sm" tableStyle="min-width: 10rem"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="noregistrasi" header="No Registrasi" style="min-width: 120px;" frozen class="font-bold">
          </Column>
          <Column field="namaruangan" header="Ruangan Pengorder" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="namaproduk" header="Nama Tindakan" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="namalengkap" header="Nama Dokter" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="tglorder" header="Tgl Order" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column :exportable="false" header="Hasil" style="text-align: center;min-width: 100px;">
            <template #body="slotProps">
              <VIconButton type="button" icon="feather:eye" class="mr-3" color="warning" circle outlined raised
                :loading="isLoadingBtn" v-tooltip.top="'Lihat Hasil'" @click="hasilRadiologi(slotProps.data)">
              </VIconButton>
            </template>
          </Column>
          <Column :exportable="false" header="Expertise" style="text-align: center;min-width: 100px;">
            <template #body="slotProps">
              <VIconButton type="button" icon="feather:folder" class="mr-3" color="info" circle outlined raised
                :loading="isLoadingBtn" v-tooltip.top="'Lihat Expertise'" @click="hasilExpertise(slotProps.data)">
              </VIconButton>
            </template>
          </Column>
        </DataTable>
      </TabPanel>
      <TabPanel header="Rekam Medis">
        <span>Klik tombol home untuk melihat riwayat kunjungan hari ini...</span>
        <DataTable :value="testRiwayatEmr" class="p-datatable-sm" tableStyle="min-width: 10rem"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="no" header="No" style="min-width: 10px;" frozen></Column>
          <Column header="Nama Dokumen" style="min-width: 120px;" frozen class="font-bold">
            <template #body="slotProps">
              <span>{{ slotProps.data.namaemr }}</span> | <VTag class="is-purple tag">{{ slotProps.data.ruangan !=
                undefined ? slotProps.data.ruangan : '-' }}</VTag>
            </template>
          </Column>
          <Column :exportable="false" header="Lihat Dokumen" style="text-align: center;min-width: 100px;">
            <template #body="slotProps">
              <VIconButton type="button" icon="feather:printer" class="mr-3" color="warning" circle outlined raised
                :loading="isLoadingBtn" v-tooltip.top="'Lihat Cetakan Form'" @click="cetakRiwayatEMR(slotProps.data)">
              </VIconButton>
              <VIconButton type="button" icon="feather:eye" class="mr-3" color="info" circle outlined raised
                :loading="isLoadingBtn" v-tooltip.top="'Lihat Form'" @click="riwayatEMR(slotProps.data)">
              </VIconButton>
              <VIconButton type="button" icon="feather:edit" class="mr-3" color="success" circle outlined raised
                :loading="isLoadingBtn" v-tooltip.top="'Edit Form'" @click="riwayatEMR(slotProps.data, 'edit')">
              </VIconButton>
            </template>
          </Column>
        </DataTable>
      </TabPanel>
    </TabView>
  </Dialog>

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
  <Dialog v-model:visible="modalExpertise" modal header="Ekspertise" :style="{ width: '50vw' }">
    <VButton icon="feather:save" @click="saveExpertise(dataExpertise)" :loading="isLoadingPop" color="primary"
      style="float: right;" raised>Cetak
    </VButton>
    <div class="columns is-multiline">
      <div class="column is-12">
        <VField>
          <VControl>
            <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="50" placeholder="Keterangan"
              autocomplete="off" autocapitalize="off" spellcheck="true" />
          </VControl>
        </VField>
      </div>
    </div>
  </Dialog>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Dialog from 'primevue/dialog';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import TEmrDetail from '../page-emr-plugins/t-emr-detail.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import TProfilePasien from './asesmen-medis-rawat-jalan.vue'
//import TEMR from "../emr/float-tambah.vue"


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Obat: any = ref([])
const listRegistrasi: any = ref([])
const routerChangeTAB: any = ref(false)
const isLoadingPasien: any = ref(false)
const isLoadingFilter: any = ref(false)
const selectedRegistrasi: any = ref({})
const listColor: any = ref(Object.keys(useThemeColors()))
const listPasienRJ: any = ref({})
const dataExpertise: any = ref({})
const isClosing: any = ref()
const idDepartemenRI: any = ref([])
const userLogin = useUserSession().getUser()
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const route = useRoute()
const totalData = ref(0)
const pasien: any = ref({})
const loadingList: any = ref(false)
const TAB_ITEMS: any = ref([]);
const router = useRouter()
const dataAlergi: any = ref('')
const rowGroupLAB: any = ref({})
const modalRiwayat = ref(false);
const isLoadingBtn = ref(false);
const modalExpertise = ref(false)
const isLoadingPop = ref(false)
const idDokterBaca = ref()
const norecHasilRadiologi = ref('')

const hideRiwayat: any = ref(false)
const modalFilter: any = ref(false)
const currenPageChange: any = ref(false)
const isLoadingRiwayat: any = ref(false)
const showModalAssesmenMedis: any = ref(false)
const TAB_ACTIVE_ROUTER: any = ref(null)
const modalMENU = ref(false)
const TAB_URL = ref('')
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
const listMenuEMR: any = ref([])
const totalMenu = ref(0)
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
const testRiwayatTIndakan: any = ref([])
const testRiwayatTObat: any = ref([])
const testRiwayatLaboratorium: any = ref([])
const testRiwayatRadiologi: any = ref([])
const testRiwayatEmr: any = ref([])
const TAB_ACTIVE: any = ref('Dashboard');
const alamat: any = ref({})
const currentPage: any = ref({
  limit: 5,
  rows: 25,
})
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  filterTgl: reactive({
    start: new Date(new Date().setDate(new Date().getDate() - 730)),
    end: new Date(),
  }),
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    no: 1,
  }],
  tglDibuat: new Date()
})

const filter = () => {
  modalFilter.value = true
}

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const cetakRiwayatEMR = (e: any) => {
  H.printBlade(`emr/cetak/${e.table}?nocmfk=${e.nocmfk}&norec_pd=${e.norec_pd}&emrpasienfk=${e.emrpasienfk}&collection=${e.table}&pdf=true`)
}

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  console.log("masuk riwayat Registrasi");
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].ttdKeluarga) {
          H.tandaTangan().set("signatureKeluarga", response[0].ttdKeluarga)
        }
        if (response[0].ttdPetugas) {
          H.tandaTangan().set("signaturePetugas", response[0].ttdPetugas)
        }
      }
    })
}

const AssesmenMedis = async () => {

  showModalAssesmenMedis.value = true
}

const reloadListReg = async () => {
  await pasienByID(route.query.nocmfk)
  currenPageChange.value = false
  modalFilter.value = false
}

const editAss = (e: any) => {
  console.log('ini masuk edit')
  showMenu({
    'name': e.namaemr,
    'url_form': e.url_form,
    'norec_emr': e.emrpasienfk,
    'collection': e.table
  })
}

const onTabMedis = () => {
  COLLECTION.value = 'Assesmen Medis'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`
  TAB_ACTIVE.value = 'Assesmen Medis'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

  isRemoveTAB.value = false
}

const setRoutingEMR = (form: any, norec_emr: any) => {

  console.log('ini masuk ke routing baru')

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
  if (form.indexOf('index_tab') > -1) {
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

const showMenu = async (e: any) => {

  if (e.name == 'Catatan Perkembangan Pasien Terintegrasi') {
    onTab()
    modalMENU.value = false
    return
  }
  //isRemoveTAB.value = false

  if (e.name != 'EMR') {
    for (let x = 0; x < TAB_ITEMS.value.length; x++) {
      const element: any = TAB_ITEMS.value[x];
      if (element.label == e.name) {
        TAB_ITEMS.value.splice(x, 1)
      }
    }

    modalMENU.value = false
    COLLECTION.value = e.items ? e.items.collection : e.collection
    TAB_URL.value = e.url_form
    TAB_ACTIVE.value = e.name
    TAB_ACTIVE_ROUTER.value = e.url_form ? `${e.url_form}` : TAB_ROUTER_DEFAULT.value

    TAB_ITEMS.value.push({ label: e.name, icon: 'pi pi-fw pi-file', url_form: e.url_form, items: e.items })
    setCacheEMRWhileReload()
    setRoutingEMR(TAB_ACTIVE_ROUTER.value, e.norec_emr ? e.norec_emr : '')

  } else {
    classMODALMENU.value = 'large'
    loadMenuEMR()
  }
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

const setCacheEMRWhileReload = () => {
  H.cacheHelper().set('xxx_cache_menu_' + route.query.nocmfk, {
    'menu': TAB_ITEMS.value,
    'active': TAB_ACTIVE.value,
    'url_form': TAB_URL.value,
    'collection': COLLECTION.value
  })
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
  // if (route.query.isfull) {
  //   router.push({
  //     query: {
  //       norec_pd: selectedRegistrasi.value.norec,
  //       nocmfk: selectedRegistrasi.value.nocmfk,
  //       norec_apd: selectedRegistrasi.value.norec_apd,
  //     }
  //   })
  // }

  // if (kelompokUser == 'dokter') {
  //   TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi'
  //   TAB_ITEMS == 'CPPT'
  //   TAB_DEFAULT == 'CPPT'
  //   onTab()
  // }
  // await detailPelayanan(items.norec)

}

const onTab = () => {
  console.log('ini CPPT')
  COLLECTION.value = 'CatatanPerkembanganPasienTerintegrasi'
  TAB_URL.value = `module-emr-profile-pasien-page-emr-cppt-rev`
  TAB_ACTIVE.value = 'Catatan Perkembangan Pasien Terintegrasi'
  TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-cppt-rev`

  setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')
  isRemoveTAB.value = false
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
      console.log(riwayatPemeriksaan.value.LIST_RAD)
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

const riwayatEMR = async (e: any, edit: any) => {
  let query: any = {}
  let params: any = {}

  H.alert('warning', `Anda sedang melihat riwayat ${e.namaemr}`)
  if (e.emrpasienfk != '') {
    query = {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.noregistrasifk,
      norec_pd: e.noregistrasifk,
      norec_apd: e.norec_apd,
      nama_ruangan: e.ruangan,
      // jenisobgyn: jenisobgyn,
      // jenisinterna: jenisinterna,
      // jenistrauma: jenistrauma,
      norec_emr: e.emrpasienfk,
      riwayat: edit != null ? false : true,
      edit: edit != null ? true : false,
    }
    // if (kelompokQuery) {
    //   query.kelompokuser = kelompokQuery ?? kelompokUser
    // }
  }

  try {
    if (e.namaemr == 'Assesmen Medis') {
      router.push({
        name: 'module-emr-profile-pasien-page-emr-asesmen-medis-rawat-jalan',
        query: query,
        params: params
      });
    } else {
      router.push({
        name: e.url_form,
        query: query,
        params: params
      });
    }
  } catch (error) {
    router.push({
      name: `module-emr-profile-pasien-page-emr-${e.url_form}`,
      query: query,
      params: params
    });
  }
}

const emr = async (e: any) => {
  modalRiwayat.value = true;
  isLoadingRiwayat.value = true
  riwayatPemeriksaan.value = clearList

  await useApi().get(`/emr/detail-pelayanan?norec_pd=${e.norec_pd}&nocmfk=${e.nocmfk}&riwayatpasien=true`).then(async (response: any) => {
    isLoadingRiwayat.value = false
    let x = 0

    testRiwayatTIndakan.value = response.tindakan
    testRiwayatTObat.value = response.resep
    //testRiwayatLaboratorium.value = response.laboratorium
    testRiwayatLaboratorium.value = response.onlylaboratorium
    testRiwayatEmr.value = response.emr
    testRiwayatRadiologi.value = response.radiologi
  })
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

const selectedRiwayat = (e: any) => {
  TAB_ITEMS.value = []
  TAB_ACTIVE.value = 'Dashboard'
  e.isdetail = true
  selectNoreg(e)
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
      &norec_pd=${selectedRegistrasi.value.norec}`)
    .then((response: any) => {

      listPasienRJ.value = response
      loadingList.value = false
    })
}


const hasilRadiologi = (e: any) => {
  console.log('e', e);
  useApi().get(
    `/radiologi/hasil-pacs?noorder=${e.noorder}&idproduk=${e.sanata_jasa_id}`).then(async (response: any) => {
      if (response.data.length == 0) {
        H.alert('warning', 'Hasil belum ada')
      } else {
        window.open(response.testurl, '_blank')
      }
    })
}

const saveExpertise = async (e: any) => {
  console.log('e data', e);
  H.printBlade(`radiologi/cetak-ekspertise?noorder=${e.noorder}&idproduk=${e.sanata_jasa_id}`);
  // isLoadingPop.value = true

  // norecHasilRadiologi.value = ''

  // let objSave = {
  //   norec: e.norec_exper,
  //   norec_so: e.norec,
  //   norec_pd: e.norec_pd,
  //   produkfk: e.objectprodukfk,
  //   hasil: {
  //     keterangan: item.keterangan,
  //     pelayananpasienfk: e.norec_ppkun,
  //     noregistrasifk: e.norec_apd,
  //     pegawaifk: idDokterBaca.value
  //   }
  // }
  // useApi().post(
  //   `/radiologi/save-expertise`, objSave).then((response: any) => {
  //     norecHasilRadiologi.value = response.norec_hh
  //     dataExpertise.value = {}
  //     cetakExpertise(response.norec_hh)
  //   }, (error) => {
  //     isLoadingPop.value = false
  //   })
}

const cetakExpertise = (e: any) => {
  isLoadingPop.value = false
  H.printBlade("radiologi/cetak-ekspertise?norec=" + e);
}
const printHasil2 = async (e: any) => {
  let showHiv = false;
  if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1 || kelompokUser.toUpperCase().indexOf('LAB') > -1) {
    showHiv = true;
  }

  if (e.objectruangantujuanfk == 335) {
    H.printBlade('laboratorium/cetakan-hasil-lab-new?noregistrasi=' + e.noregistrasi + '&norec_apd=' + e.norec_apd + '&noorder=' + e.noorder + '&norec_so=' + e.norec + '&showHIV=' + showHiv);
  }
  else if (e.objectruangantujuanfk == 336) {
    H.printBlade(`laboratorium/get-hasil-pa-bridging?noorder=${e.noorder}`);
  }
  else if (e.objectruangantujuanfk == 337) {
    H.printBlade(`laboratorium/cetakan-hasil-culture-new?noorder=${e.noorder}`);
  }
  else {
    H.alert('error', 'Not Valid Data')
    return
  }

}
const hasilExpertise = (e: any) => {
  console.log('e hasil expertisse', e);
  dataExpertise.value = {}
  modalExpertise.value = true
  idDokterBaca.value = e.iddokterbaca
  dataExpertise.value = e

  // console.log('id dokter baca',idDokterBaca.value);

  useApi().get(
    `/radiologi/hasil-pacs?noorder=${e.noorder}&idproduk=${e.sanata_jasa_id}`).then(async (response: any) => {
      console.log(response.data[0])
      if (response.data.length == 0) {
        H.alert('warning', 'Hasil belum ada')
      }
      else {
        item.keterangan = response.data[0].expertise_text_finding + '\n\n' + response.data[0].expertise_text_conclusion
        modalExpertise.value = true
      }
      // else{

      // useApi().get(
      //   `/radiologi/get-expertise?norec=${dataItem.norec_pp}`).then((response: any) => {
      //     norecHasilRadiologi.value = '';
      //     if (response != null) {
      //     item.keterangan = response.keterangan
      //       modalExpertise.value = true
      //     } else {
      //       H.alert('warning', 'Hasil belum ada')
      //     }
      //   })
      // }
    });
}

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
      // `/emr/header-pasien?nocmfk=${id}&norec_pd=${route.query.norec_pd}${norec_apd}&dari=${dari}&sampai=${sampai}&nyariregis=true&limit=${limit}&offset=${offset}`
      `/emr/header-pasien?nocmfk=${id}&nyariregis=true&offset=${offset}&limit=${limit}`
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
        //selectNoreg(response.registrasi[0])
      }

      isLoadingFilter.value = false
      isLoadingPasien.value = false
      // await loadListPasien()

    })


}

await pasienByID(ID_PASIEN)

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.ttdKeluarga = H.tandaTangan().get("signatureKeluarga")
  object.ttdPetugas = H.tandaTangan().get("signaturePetugas")
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const setTandaTanganPegawai = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePetugas", element.ttd)
    } else {
      H.tandaTangan().set("signaturePetugas", '')
    }
  })
}

const fetchObat = async (filter: any) => {

  await useApi().get(
    `emr/get-obat?filter=${filter.query}`
  ).then((response) => {
    d_Obat.value = response
  })
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
// loadRiwayat()
</script>

<style lang="scss">
.table-rpo {
  width: 100%;
  border: 1px solid;
}

.th-rpo,
.td-rpo {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}

@import '/@src/scss/abstracts/all';

.panjangaaaaaaa {
  height: 130px
}

.panjang-240 {
  height: 260px;
  overflow-x: hidden;
  overflow-y: auto;
}

.is-dark {
  .list-widget {
    @include vuero-card--dark;
  }
}

.list-widget {

  @include vuero-l-card;

  padding: 30px;

  &:not(:last-child) {
    margin-bottom: 1.5rem;
  }

  &.is-straight {
    @include vuero-s-card;
    border-radius: 16px;
    // margin-top: 8px;
  }

  .widget-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 32px;
    margin-bottom: 10px;

    h3 {
      color: var(--dark-text);
      font-size: 1.1rem;
      font-weight: 500;
    }
  }

  .inner-list {
    padding: 10px 0;

    .inner-list-item {
      +.inner-list-item {
        margin-top: 24px;
      }
    }
  }
}

.list-widget {
  .icon-timeline {
    .timeline-item {
      position: relative;
      display: flex;
      padding-bottom: 30px;

      &::after {
        content: '';
        position: absolute;
        top: 36px;
        left: 18px;
        width: 1px;
        height: calc(100% - 36px);
        border-left: 1px solid var(--fade-grey-dark-3);
      }

      .timeline-icon {
        position: relative;
        // height: 36px;
        width: 56px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-rounded);
        color: var(--light-text);
        box-shadow: var(--light-box-shadow);

        &::after {
          content: '';
          position: absolute;
          top: 17px;
          left: 40px;
          width: 20px;
          height: 1px;
          border-top: 1px solid var(--fade-grey-dark-3);
        }

        &.is-squared {
          border-radius: 10px;

          img {
            border-radius: 10px;
          }
        }

        &.is-primary {
          background: var(--primary);
          border-color: var(--primary);
          box-shadow: var(--primary-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-info {
          background: var(--info);
          border-color: var(--info);
          box-shadow: var(--info-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-success {
          background: var(--success);
          border-color: var(--success);
          box-shadow: var(--success-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-orange {
          background: var(--orange);
          border-color: var(--orange);
          box-shadow: var(--orange-box-shadow);

          svg {
            color: var(--smoke-white);
          }
        }

        &.is-yellow {
          background: var(--yellow);
          border-color: var(--yellow);

          svg {
            color: var(--smoke-white);
          }
        }

        img {
          display: block;
          height: 28px;
          width: 28px;
          border-radius: var(--radius-rounded);
        }

        svg {
          height: 16px;
          width: 16px;
          stroke-width: 1.6px;
        }
      }

      .timeline-content {
        margin-left: 34px;
        line-height: 1.2;

        span {
          font-size: 0.8rem;
          color: var(--light-text);
        }

        p {
          font-family: var(--font-alt);
          font-size: 0.75rem;
          font-weight: 500;
          color: var(--dark-text);
          width: 260px;
          white-space: nowrap;
          overflow: hidden !important;
          text-overflow: ellipsis;
        }
      }
    }
  }
}

.is-dark {
  .list-widget {
    .icon-timeline {
      .timeline-item {
        &::after {
          border-color: var(--dark-sidebar-light-12) !important;
        }

        .timeline-icon:not(.is-primary):not(.is-info):not(.is-success):not(.is-orange):not(.is-yellow) {
          background: var(--dark-sidebar-light-3) !important;
          border-color: var(--dark-sidebar-light-12) !important;
        }

        .timeline-icon {
          &::after {
            border-color: var(--dark-sidebar-light-12) !important;
          }

          &.is-primary {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: var(--primary-box-shadow);

            svg {
              color: var(--smoke-white);
            }
          }
        }

        .timeline-content {
          p {
            color: var(--dark-dark-text);
          }
        }
      }
    }
  }
}

.list-widget .icon-timeline .timeline-item::after {
  content: none !important;

}

.list-widget .icon-timeline .timeline-item .timeline-icon::after {
  content: none !important;
}

.hover:hover {
  background-color: rgb(255, 255, 237);
}
</style>
