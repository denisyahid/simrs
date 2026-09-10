
<template>
  <section>
    <ConfirmDialog />
    <div>
      <div class="form-layout is-stacked">
        <div class="form-outer" style="margin-top:15px">
          <div :class="[isStuck &&  'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Order Resep</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                    Kembali
                  </VButton>
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                    @click="simpan()"> Simpan
                  </VButton>
                  <VIconButton icon="feather:rotate-ccw" @click="CopyResep(items)" color="info"
                                              v-tooltip.bubble="'Copy Resep'" raised circle class="mr-2 my-1" v-if="hilangkanStuck == true">
                                            </VIconButton>
                </div>
              </div>
            </div>
          </div>
          <div class="form-body p-2" v-if="!props.pasien">
            <div class="business-dashboard hr-dashboard">
              <div class="columns is-multiline">
                <div class="column is-12" v-if="isLoadingPasien">
                  <PlaceloadHeader class="m-3" />
                </div>
                <div class="column is-12" v-if="!isLoadingPasien">
                  <HeadPasien :pasien="pasien" class="m-3" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <form class="form-layout is-separate">
        <div class="form-outer">
          <div class="form-body">
            <div class="columns is-multiline">
              <div class="column is-12 ">
                <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
                  <VCard>
                    <div class="tabs-wrapper" :class="['tab-naver']">
                      <div class="tabs-inner">
                        <div class="tabs is-boxed">
                          <ul>
                            <li v-for="(tab, key) in tabs" :key="key" :class="[activeValue === tab.value && 'is-active']">
                              <slot name="tab-link" :active-value="activeValue" :tab="tab" :index="key" :toggle="toggle">
                                <a tabindex="0" @keydown.space.prevent="toggle(tab.value)" @click="toggle(tab.value)">
                                  <VIcon v-if="tab.icon" :icon="tab.icon" />
                                  <span>
                                    <slot name="tab-link-label" :active-value="activeValue" :tab="tab" :index="key">
                                      {{ tab.label }}
                                    </slot>
                                  </span>
                                </a>
                              </slot>
                            </li>
                            <li v-if="sliderClass" class="tab-naver"></li>
                          </ul>
                        </div>
                      </div>

                      <div class="tab-content is-active">
                        <Transition :name="'fade-fast'" mode="out-in">
                          <slot name="tab" :active-value="activeValue"></slot>
                        </Transition>
                      </div>
                    </div>

                  </VCard>

                </div>
              </div>
              <div class="column is-12 mt-0 mb-0 pb-0" v-if="activeValue == 1">

                <div class="form-section  pt-0 pr-0">
                  <div class="form-section-inner has-padding-bottom h-700-o">
                    <h3 class="has-text-centered">Detail Order </h3>
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <VField>
                          <VLabel>Tanggal Resep</VLabel>
                          <VDatePicker v-model="item.tglorder" is-range color="pink" trim-weeks>
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
                        </VField>
                      </div>


                      <div class="column is-3">
                        <VField label="Penulis Resep " class="is-rounded-select_Z  is-autocomplete-select"
                          v-slot="{ id }">
                          <VControl icon="fa:user-md" class="prime-auto-select">

                            <AutoComplete v-model="item.pegawaiOrder" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                              placeholder="ketik nama Dokter" />

                          </VControl>
                          <!-- <VControl icon="fa:user-md" fullwidth>
                                                      <Multiselect mode="single" v-model="item.pegawaiOrder"
                                                          placeholder="Pilih data" :searchable="true" :filter-results="false"
                                                          :min-chars="0" :attrs="{ id }" :resolve-on-load="true" :delay="0"
                                                          :options="async function (query: any) {
                                                              return await fetchDokter(query)
                                                          }" autocomplete="off" />

                                                  </VControl> -->
                          <!-- <VControl icon="feather:list" fullwidth>
                                                          <Multiselect mode="single" v-model="item.pegawaiOrder"
                                                              :options="d_Pegawai" placeholder="Pilih data"
                                                              :searchable="true" :attrs="{ id }" autocomplete="off" />
                                                      </VControl> -->
                        </VField>
                      </div>
                      <div class="column is-3">
                        <VField label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:list" class="prime-auto-select">
                            <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                              placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                              :disabled="disabledRuangan" />
                            <!--
                                                      <AutoComplete v-model="item.dokter" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                                                          :optionLabel="'namaruangan'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                          :loadingIcon="'pi pi-spinner'" :field="'namaruangan'" placeholder="ketik untuk mencari" /> -->

                          </VControl>
                          <!-- <VControl icon="feather:list" fullwidth>
                                                      <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan"
                                                          placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                          autocomplete="off" :disabled="disabledRuangan" />
                                                  </VControl> -->
                        </VField>
                      </div>

                      <!-- <div class="column is-12">
                                                      <VField>
                                                          <VControl>
                                                              <VSwitchBlock v-model="item.isreseppulang" label="Resep Pulang"
                                                                  color="danger" />
                                                          </VControl>
                                                      </VField>
                                                  </div> -->



                      <div class="column is-12">
                        <TListOrderResep title="" straight class="list-widget-v3" :items="dataSource" @addItems="addItems"
                          @editItems="editItems" @hapusItems="hapusItems" squared colored>
                        </TListOrderResep>
                        <div class="load-more-wrap has-text-centered p-0 mb-3 mt-4" v-if="dataSource.length">
                          <div class="columns is-multiline">
                            <div class="column is-4 is-offset-8">
                              <VCard>
                                <div class="columns is-multiline">
                                  <div class="column is-3 mt-1">
                                    <VField label="TOTAL" style="text-align: left;">

                                    </VField>
                                  </div>
                                  <div class="column is-9">
                                    <VField>
                                      <VLabel class="fs-total">{{
                                        item.TOTAL
                                      }} </VLabel>
                                    </VField>
                                  </div>
                                </div>
                              </VCard>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>


                </div>
              </div>
              <div class="column is-12 mt-0 mr-4 pr-5" v-if="activeValue == 2">
                <div class="columns is-multiline">
                  <div class="column is-12 ">
                    <div class="form-section  pt-0 pr-0">
                      <div class="form-section-inner has-padding-bottom h-700-o ">
                        <h3 class="has-text-centered">Riwayat </h3>
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <div class="timeline-wrapper" v-if="listRiwayat.length > 0">
                              <div class="timeline-header"></div>
                              <div class="timeline-wrapper-inner pt-0">
                                <div class="timeline-container">
                                  <div class="timeline-item is-unread " v-for="(items, index)  in listRiwayat"
                                    :key="items.norec">

                                    <div class="date">
                                      <span>{{ items.tglorder
                                      }}</span>
                                    </div>
                                    <div :class="'dot is-' + listColor[index + 1]">
                                    </div>
                                    <div class="collapse-icon is-clickable" @click=" items.isExpand = true"
                                      v-if="!items.isExpand">
                                      <VIcon icon="feather:chevron-down" />
                                    </div>
                                    <div class="collapse-icon  is-clickable mr-1 " open @click=" items.isExpand = false"
                                      v-else>
                                      <VIcon icon="feather:chevron-up" />
                                    </div>
                                    <div class="content-wrap is-grey">
                                      <table class="is-fullwidth">
                                        <tr>
                                          <td style="width:25%" rowspan="2">
                                            <p class="td-label-x">{{
                                              items.namaruangan
                                            }}</p>
                                            <span class="td-label-xxx">{{
                                              items.namalengkap
                                            }}</span>
                                          </td>
                                          <td style="width:10%">
                                            <span class="td-label">No
                                              Transaksi</span>
                                          </td>
                                          <td style="width:15%">
                                            <span class="td-label">Ruangan
                                              Asal</span>
                                          </td>

                                          <td style="width:10%">
                                            <span class="td-label">Status
                                            </span>
                                          </td>
                                          <td style="width:15%" rowspan="2">
                                            <VIconButton icon="feather:edit" @click="editRiwayat(items)" color="primary"
                                              v-tooltip.bubble="'Edit'" raised circle class="mr-2">
                                            </VIconButton>
                                            <VIconButton icon="feather:trash" @click="DialogConfirm(items)" color="danger"
                                              v-tooltip.bubble="'Hapus'" raised circle class="mr-2">
                                            </VIconButton>
                                            <br>
                                            <VIconButton icon="feather:rotate-ccw" @click="reOrder(items)" color="info"
                                              v-tooltip.bubble="'Order Ulang'" raised circle class="mr-2 my-1">
                                            </VIconButton>
                                            <VIconButton icon="feather:printer" @click="cetakOrder(items)" color="warning"
                                              v-tooltip.bubble="'Cetak'" raised circle class="mr-2 my-1">
                                            </VIconButton>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>
                                            <span class="td-label-xx">{{
                                              items.noorder
                                            }}</span>
                                          </td>
                                          <td>
                                            <span class="td-label-xx">{{
                                              items.namaruanganrawat
                                            }}</span>
                                          </td>
                                          <td>
                                            <VTag :color="items.color_status" :label="items.statuspengerjaan" />
                                          </td>
                                        </tr>

                                      </table>
                                      <VCard custom="card-green" class="mt-1" v-if="items.isExpand">
                                        <div class="columns is-multiline">
                                          <div class="column is-1">
                                            <VField>
                                              <VLabelText>R/ke </VLabelText>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>
                                              <VLabelText>Jenis Kemasan
                                              </VLabelText>
                                            </VField>
                                          </div>

                                          <div class="column is-3">
                                            <VField>
                                              <VLabelText>Nama Produk
                                              </VLabelText>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>
                                              <VLabelText>Jumlah </VLabelText>
                                            </VField>
                                          </div>

                                          <div class="column is-2">
                                            <VField>
                                              <VLabelText>Aturan Pakai
                                              </VLabelText>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>
                                              <VLabelText>Satuan Resep
                                              </VLabelText>
                                            </VField>
                                          </div>
                                        </div>
                                        <div class="columns is-multiline" v-for="(itemsDet, index2)  in items.details"
                                          :key="index2">

                                          <div class="column is-1">
                                            <VField>
                                              <VLabel>{{ itemsDet.rke }} </VLabel>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>

                                              <VLabel> {{ itemsDet.jeniskemasan }}
                                              </VLabel>
                                            </VField>
                                          </div>

                                          <div class="column is-3">
                                            <VField>

                                              <VLabel class="txt-elipsis-2">{{
                                                itemsDet.namaproduk
                                              }} |
                                                {{ itemsDet.satuanstandar }}
                                              </VLabel>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>

                                              <VLabel>{{ itemsDet.jumlah }}
                                              </VLabel>
                                            </VField>
                                          </div>

                                          <div class="column is-2">
                                            <VField>
                                              <VLabel>{{ itemsDet.aturanpakai }}
                                              </VLabel>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>
                                              <VLabel class="txt-elipsis-2">
                                                {{ itemsDet.satuanresep }}
                                              </VLabel>
                                            </VField>
                                          </div>

                                        </div>
                                      </VCard>

                                    </div>

                                  </div>
                                </div>

                              </div>
                            </div>
                            <VCard radius="rounded" class="mt-2" v-if="listRiwayat.length === 0">
                              <VPlaceholderPage title="Data belum ada."
                                subtitle="Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu."
                                larger>
                                <template #image>
                                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg"
                                    alt="" />
                                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                    alt="" />
                                </template>
                              </VPlaceholderPage>
                            </VCard>
                          </div>

                          <div class="column is-12 mt-3">

                            <VButton icon="lnir lnir-arrow-left is-fullwidth" color="info" dark-outlined
                              @click="orderBaru()">
                              Order Baru
                            </VButton>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div class="column is-12 mt-0 mr-4 pr-5" v-if="activeValue == 3">
                <div class="columns is-multiline">
                  <div class="column is-12 ">
                    <div class="form-section  pt-0 pr-0">
                      <div class="form-section-inner has-padding-bottom h-700-o ">
                        <h3 class="has-text-centered">Riwayat Resep Verifikasi</h3>
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <div class="timeline-wrapper" v-if="listRiwayat.length > 0">
                              <div class="timeline-header"></div>
                              <div class="timeline-wrapper-inner pt-0">
                                <div class="timeline-container">
                                  <div class="timeline-item is-unread " v-for="(items, index)  in listRiwayat"
                                    :key="items.norec">

                                    <div class="date">
                                      <span>{{ items.tglorder
                                      }}</span>
                                    </div>
                                    <div :class="'dot is-' + listColor[index + 1]">
                                    </div>
                                    <div class="collapse-icon is-clickable" @click=" items.isExpand = true"
                                      v-if="!items.isExpand">
                                      <VIcon icon="feather:chevron-down" />
                                    </div>
                                    <div class="collapse-icon  is-clickable mr-1 " open @click=" items.isExpand = false"
                                      v-else>
                                      <VIcon icon="feather:chevron-up" />
                                    </div>
                                    <div class="content-wrap is-grey">
                                      <table class="is-fullwidth">
                                        <tr>
                                          <td style="width:25%" rowspan="2">
                                            <p class="td-label-x">{{
                                              items.namaruangan
                                            }}</p>
                                            <span class="td-label-xxx">{{
                                              items.namalengkap
                                            }}</span>
                                          </td>
                                          <td style="width:10%">
                                            <span class="td-label">No
                                              Transaksi</span>
                                          </td>
                                          <td style="width:15%">
                                            <span class="td-label">Ruangan
                                              Asal</span>
                                          </td>

                                          <td style="width:10%">
                                            <span class="td-label">Status
                                            </span>
                                          </td>
                                          <td style="width:15%" rowspan="2">
                                            <VIconButton icon="feather:edit" @click="editRiwayat(items)" color="primary"
                                              v-tooltip.bubble="'Edit'" raised circle class="mr-2">
                                            </VIconButton>
                                            <VIconButton icon="feather:trash" @click="DialogConfirm(items)" color="danger"
                                              v-tooltip.bubble="'Hapus'" raised circle class="mr-2">
                                            </VIconButton>
                                            <br>
                                            <VIconButton icon="feather:rotate-ccw" @click="reOrder(items)" color="info"
                                              v-tooltip.bubble="'Order Ulang'" raised circle class="mr-2 my-1">
                                            </VIconButton>
                                            <VIconButton icon="feather:printer" @click="cetakOrder(items)" color="warning"
                                              v-tooltip.bubble="'Cetak'" raised circle class="mr-2 my-1">
                                            </VIconButton>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>
                                            <span class="td-label-xx">{{
                                              items.noorder
                                            }}</span>
                                          </td>
                                          <td>
                                            <span class="td-label-xx">{{
                                              items.namaruanganrawat
                                            }}</span>
                                          </td>
                                          <td>
                                            <VTag :color="items.color_status" :label="items.statuspengerjaan" />
                                          </td>
                                        </tr>

                                      </table>
                                      <VCard custom="card-green" class="mt-1" v-if="items.isExpand">
                                        <div class="columns is-multiline">
                                          <div class="column is-1">
                                            <VField>
                                              <VLabelText>R/ke </VLabelText>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>
                                              <VLabelText>Jenis Kemasan
                                              </VLabelText>
                                            </VField>
                                          </div>

                                          <div class="column is-3">
                                            <VField>
                                              <VLabelText>Nama Produk
                                              </VLabelText>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>
                                              <VLabelText>Jumlah </VLabelText>
                                            </VField>
                                          </div>

                                          <div class="column is-2">
                                            <VField>
                                              <VLabelText>Aturan Pakai
                                              </VLabelText>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>
                                              <VLabelText>Satuan Resep
                                              </VLabelText>
                                            </VField>
                                          </div>
                                        </div>
                                        <div class="columns is-multiline" v-for="(itemsDet, index2)  in items.details"
                                          :key="index2">

                                          <div class="column is-1">
                                            <VField>
                                              <VLabel>{{ itemsDet.rke }} </VLabel>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>

                                              <VLabel> {{ itemsDet.jeniskemasan }}
                                              </VLabel>
                                            </VField>
                                          </div>

                                          <div class="column is-3">
                                            <VField>

                                              <VLabel class="txt-elipsis-2">{{
                                                itemsDet.namaproduk
                                              }} |
                                                {{ itemsDet.satuanstandar }}
                                              </VLabel>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>

                                              <VLabel>{{ itemsDet.jumlah }}
                                              </VLabel>
                                            </VField>
                                          </div>

                                          <div class="column is-2">
                                            <VField>
                                              <VLabel>{{ itemsDet.aturanpakai }}
                                              </VLabel>
                                            </VField>
                                          </div>
                                          <div class="column is-2">
                                            <VField>
                                              <VLabel class="txt-elipsis-2">
                                                {{ itemsDet.satuanresep }}
                                              </VLabel>
                                            </VField>
                                          </div>

                                        </div>
                                      </VCard>

                                    </div>

                                  </div>
                                </div>

                              </div>
                            </div>
                            <VCard radius="rounded" class="mt-2" v-if="listRiwayat.length === 0">
                              <VPlaceholderPage title="Data belum ada."
                                subtitle="Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu."
                                larger>
                                <template #image>
                                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg"
                                    alt="" />
                                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                    alt="" />
                                </template>
                              </VPlaceholderPage>
                            </VCard>
                          </div>

                          <div class="column is-12 mt-3">

                            <VButton icon="lnir lnir-arrow-left is-fullwidth" color="info" dark-outlined
                              @click="orderBaru()">
                              Order Baru
                            </VButton>

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
      </form>

      <VModal :open="modalInput" noclose title="Tambah Resep" size="big"  actions="right" @close="modalInput = false"
        cancelLabel="Batal">
        <template #content>
          <form class="modal-form">
            <div class="columns is-multiline">
              <div class="column is-2">
                <VField>
                  <VLabel>R/Ke</VLabel>
                  <VControl icon="feather:bookmark">
                    <VInput type="number" v-model="item.rke" placeholder="R/Ke" class="is-rounded" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField>
                  <VLabel>Jenis Kemasan</VLabel>
                  <VControl>
                    <VRadio v-for="items in d_kemasan" :key="items.id" v-model="item.jenisKemasan" :value="items"
                      :label="items.jeniskemasan" name="{{items.id}}" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3" v-if="showRacikanDose">
                <VField>
                  <VLabel>Jumlah </VLabel>
                  <VControl icon="feather:bookmark">
                    <VInput type="text" v-model="item.jumlahxmakan" placeholder="Jumlah" class="is-rounded" />

                  </VControl>
                </VField>
              </div>
              <div class="column is-3" v-if="showRacikanDose">
                <VField>
                  <VLabel>Dosis </VLabel>
                  <VControl icon="feather:bookmark">
                    <VInput type="text" v-model="item.dosis" placeholder="Dosis" class="is-rounded" />
                    <p class="help"> {{ (item.kekuatan ? 'Kekuatan : ' + item.kekuatan : '') + ' ' +
                      (item.sediaan ?
                        item.sediaan : '')
                    }}</p>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3" v-if="showRacikanDose">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel>Jenis Racikan</VLabel>
                  <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.jenisRacikan" :options="d_jenisRacikan" :optionLabel="'jenisracikan'"
                      class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel>Produk</VLabel>
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                      :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-rounded" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik untuk mencari..."
                      @item-select="changeProduk(item.produk)" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel>Satuan</VLabel>
                  <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'satuanstandar'" class="is-rounded"
                      placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                      @change="changeSatuan(item.satuan)" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VLabel>Qty</VLabel>
                  <VControl icon="feather:bookmark">
                    <VInput type="number" v-model="item.jumlah" placeholder="Qty" class="is-rounded" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <div class="checkboxes">
                  <VField>
                    <VLabel>Aturan Pakai</VLabel>
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.aturanpakaitxt" placeholder="Aturan Pakai" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column">
                <div class="columns is-multiline mt-4">
                  <div class="column is-2" v-for="(opsi) in listDataSigna">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="opsi.isChecked" class="p-0" :label="opsi.nama"
                          @change="addListAturanPakai(opsi.isChecked, opsi)" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              <div class="column is-6">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel>Satuan Resep</VLabel>
                  <VControl icon="feather:search" class="prime-auto-select">
                    <Dropdown v-model="item.satuanresep" :options="d_satuanResep" :optionLabel="'satuanresep'"
                      class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6">
                <VField>
                  <VLabel>Keterangan</VLabel>
                  <VControl icon="feather:bookmark">
                    <VInput type="text" v-model="item.KeteranganPakai" placeholder="Catatan" class="is-rounded" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <div class="content mb-0">
                  <div class="is-divider mb-0" data-content="Informasi"></div>
                </div>
              </div>
              <div class="column is-3">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status primary">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">Konversi</span>
                  </div>
                  <small class="text-bold-custom">{{ item.nilaiKonversi ? item.nilaiKonversi : 0
                  }}</small>

                </VCardCustom>
              </div>
              <div class="column is-3">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status danger">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">Stok</span>
                  </div>
                  <small class="text-bold-custom">{{ item.stok ? H.formatRp(item.stok, '') : 0 }}</small>
                </VCardCustom>
              </div>
              <div class="column is-3">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status warning">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">Harga</span>
                  </div>
                  <small class="text-bold-custom">{{ item.hargaSatuan ? H.formatRp(item.hargaSatuan,
                    'Rp.') : 0
                  }}</small>
                </VCardCustom>
              </div>
              <div class="column is-3">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status info">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">Total</span>
                  </div>
                  <small class="text-bold-custom">{{ item.total ? H.formatRp(item.total, 'Rp.') : 0
                  }}</small>
                </VCardCustom>
              </div>
            </div>

            <div class="column is-12">
              <div class="content">
                <div class="is-divider" data-content="Resep di buat" />
              </div>
            </div>
            <TListOrderResepModal title="" straight class="list-widget-v3" :items="dataSource"
              @editItems="editItems" @hapusItems="hapusItems" squared colored>
            </TListOrderResepModal>

          </form>
        </template>
        <template #action>
          <VButton icon="feather:plus" @click="add()" :loading="isLoading" color="success" raised>Tambah
          </VButton>
        </template>
      </VModal>
    </div>
  </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import TListOrderResep from '../t-list-order-resep.vue'
import TListOrderResepModal from '../t-list-order-resep-modal.vue'
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import { elements } from '/@src/data/landing/components'
useHead({
  title: 'Order Resep - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const route = await useRoute()
let ID_PASIEN = route.query.nocmfk as string
let NOREC_PD = route.query.norec_pasien_daftar as string

const props: any = defineProps({
  registrasi: {
    type: Object as PropType<any>,
  },
  pasien: {
    type: Object as PropType<any>,
  },
  selected: undefined,
  type: undefined,
  align: undefined,
  hilangkanStuck : false
})
useViewWrapper().setFullWidth(props.pasien ? true : false)
const isLoadingPasien: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  produkCeklis: [],
  // pegawaiOrder: {
  //   id: useUserSession().getUser().id,
  //   namalengkap: useUserSession().getUser().pegawai.namaLengkap
  // },
  tglorder: {
    start: new Date(),
    end: new Date(),
  },
  header: {},
  totalAll: 0,
  jumlah: 0,
  persenDiskon: 0,
  // aturanPakai: [],
  hargadiskon: 0,
  tglAwal: new Date(),
  rke: 1,
  resep: '-',
  chkp: 0,
  chks: 0,
  chksr: 0,
  chkm: 0,
})
const tabs: any = ref([
  { label: 'Order', value: 1, icon: 'lnir lnir-medicine-alt' },
  { label: 'Riwayat', value: 2, icon: 'fas fa-list' },
  { label: 'Riwayat Resep Verif', value: 3, icon: 'fas fa-list' }
])
const modalInput: any = ref(false)
const disabledRuangan: any = ref(false)
const listChecked: any = ref([])
const selected_count = ref(0);
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
const isMerge: any = ref(false)
const listDataSigna = ref([
  { "id": 1, "nama": "P", "isChecked": false },
  { "id": 2, "nama": "S", "isChecked": false },
  { "id": 3, "nama": "Sr", "isChecked": false },
  { "id": 4, "nama": "M", "isChecked": false }
])
for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i];
  if (i <= 9 && element != 'primary')
    listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30 && props.hilangkanStuck == false
})
const isDetail: any = ref([true])
const dataSource: any = ref([])
const data2: any = ref([])
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const isLoading = ref(false)
const isSimpan = ref(false)
const showRacikanDose = ref(false)
const isLoadingTT = ref(false)
const d_satuanResep = ref([])
const d_produk: any = ref([])
const d_ProdukDef: any = ref([])
const d_tglKadaluarsa: any = ref([])
const d_satuan: any = ref([])
const d_asalProduk: any = ref([])
const d_aturanPakai: any = ref([])
const d_kemasan: any = ref([])
const d_jenisRacikan: any = ref([])
const d_route: any = ref([])
const d_Dokter: any = ref([])
const d_resepHariini: any = ref([])
const dataSelected: any = ref({})
const confirm = useConfirm();
const selectedTabs: any = ref()
const listRiwayat: any = ref([])
const dataProdukDetail: any = ref([])
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const emit = defineEmits<{
  (e: 'update:selected', value: string): void
}>()


const activeValue: any = ref(1)
const sliderClass = computed(() => {
  if (!props.slider) {
    return ''
  }

  if (props.type === 'rounded') {
    if (props.tabs.length === 3) {
      return 'is-triple-slider'
    }
    if (props.tabs.length === 2) {
      return 'is-slider'
    }

    return ''
  }

  if (!props.type) {
    if (props.tabs.length === 3) {
      return 'is-squared is-triple-slider'
    }
    if (props.tabs.length === 2) {
      return 'is-squared is-slider'
    }
  }

  return ''
})

function toggle(value: string) {
  activeValue.value = value
}


function loadRiwayat(isVerify:any) {
  listRiwayat.value = []
  useApi().get(
    `/farmasi/riwayat-order-resep?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}&verif=${isVerify}`).then((response: any) => {
      let z = 0
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        element.isExpand = true
        element.icon = 'fa-inverse lnir lnir-medicine-alt'
        element.color = listColor2.value[z]
        element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
        if (z > 4) {
          z = 0
        }
        z++
      }
      listRiwayat.value = response
    })
}
function pasienByID(id: any) {
  if (props.pasien != undefined) {
    pasien.value = props.pasien
    item.NOREC_APD = props.registrasi.norec_apd
    item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
    item.registrasi = props.registrasi
  } else {
    isLoadingPasien.value = true
    useApi().get(
      `/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
        pasien.value = response.pasien
        item.NOREC_APD = response.last_registrasi.norec_apd
        item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
        item.registrasi = response.last_registrasi
        isLoadingPasien.value = false
        // fetchTindakan(item.RUANGAN_LAST)
      })
  }

}

async function loadDrop() {
  const response = await useApi().get(
    `/farmasi/input-resep-cbo`)
  d_aturanPakai.value = response.signa.map((e: any) => { return { aturanpakai: e.signa, id: e.id } })
  d_kemasan.value = response.jeniskemasan
  d_Ruangan.value = response.ruanganFarmasi
  d_jenisRacikan.value = response.jenisracikan//.map((e: any) => { return { label: e.jenisracikan, value: e } })
  d_route.value = response.route//.map((e: any) => { return { label: e.name, value: e } })
  d_satuanResep.value = response.satuanresep//.map((e: any) => { return { label: e.satuanresep, value: e } })
  d_asalProduk.value = response.asalproduk//.map((e: any) => { return { label: e.asalproduk, value: e } })
  item.jenisKemasan = response.jeniskemasan[1]
  item.tarifadminresep = response.tarifadminresep ? response.tarifadminresep : 0
  item.ruangan = d_Ruangan.value[0]
  disabledRuangan.value = false;

  response.penulisresep.forEach(element => {
    if (item.registrasi.objectpegawaifk == element.id) {
      item.pegawaiOrder = element
      return
    }
  });
}

function changeProduk(e: any) {
  if (e != null && e != undefined) {
    GETKONVERSI()
  }
}
function GETKONVERSI() {

  d_satuan.value = item.produk.konversisatuan
  if (item.produk.konversisatuan.length == 0) {
    d_satuan.value = [
      {
        ssid: item.produk.ssid, satuanstandar: item.produk.satuanstandar
      }]
  }
  item.produk.konversisatuan.forEach((element: any) => {
    if (element.ssid == item.produk.ssid) {
      item.satuan = element
      return
      // item.satuan = { ssid: element.ssid, satuanstandar: element.satuanstandar, nilaikonversi: element.nilaikonversi }
      // return
    }
  });
  d_satuan.value.forEach((element: any) => {
    if (element.ssid == item.produk.ssid) {
      item.satuan = element
    }
  })

  item.nilaiKonversi = 1
  isLoading.value = true
  dataProdukDetail.value = []
  useApi().get('/farmasi/get-produkdetail?produkfk=' + item.produk.id + '&ruanganfk=' + item.ruangan.id +
    "&kpid=" + item.registrasi.objectkelompokpasienlastfk +
    "&norec_apd=" + item.NOREC_APD).then(function (response: any) {
      if (response.detail.length > 0) {
        dataProdukDetail.value = response.detail
        item.stok = response.jmlstok / item.nilaiKonversi
        if (response.kekuatan == undefined || response.kekuatan == 0) {
          response.kekuatan = 1
        }
        item.kekuatan = response.kekuatan
        item.sediaan = response.sediaan
        item.tglKadaluarsa = response.detail[0]
        if (dataSelected.value.no != undefined) {

          item.jumlah = dataSelected.value.jumlah
          item.dosis = dataSelected.value.dosis
          item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
          item.nilaiKonversi = dataSelected.value.nilaikonversi
          d_satuan.value.forEach((element: any) => {
            if (element.ssid == dataSelected.value.satuanviewfk) {
              item.satuan = element
            }
          })
          item.hargaSatuan = dataSelected.value.hargasatuan
          item.hargadiskon = dataSelected.value.hargadiscount
          item.hargaNetto = dataSelected.value.harganetto
          item.total = dataSelected.value.total
        } else {
          if (!isMerge.value) {
            item.jumlah = 1
          }

        }

        setNorecSPD()
        isLoading.value = false
      } else {
        if (response.jmlstok == 0) {
          useToaster().warn(`Stok ${item.produk.namaproduk} belum ada`)
        }
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.stok = response.jmlstok
        item.hargaNetto = 0
        item.total = 0
        isLoading.value = false
      }

    });

}
function simpan() {

  if (!item.pegawaiOrder) {
    H.alert('error', 'Pilih Penulis Resep dulu')
    return
  }
  if (data2.value.length == 0) {
    H.alert('error', 'Pilih produk dulu')
    return
  }
  let diffDays = H.diff_day(item.tglorder.start, item.tglorder.end)
  if (diffDays < 1) {
    H.alert('error', "Tanggal Akhir tidak boleh lebih kecil!!")
    return
  }
  var tglResepHari = ''
  var objSaves = [];
  for (var i = diffDays - 1; i >= 0; i--) {
    var someDate = item.tglorder.start
    var numberOfDaysToAdd = i;
    tglResepHari = H.formatDate(someDate.setDate(someDate.getDate() + numberOfDaysToAdd), 'YYYY-MM-DD hh:mm:ss');

    var strukorder = {
      'norec': item.NOREC_SO ? item.NOREC_SO : '',
      'tglresep': tglResepHari,
      'penulisresepfk': item.pegawaiOrder.id,
      'ruanganfk': item.ruangan.id,
      'noregistrasifk': item.NOREC_APD,
      'qtyproduk': data2.value.length,
      'noruangan': null,
      'isreseppulang': item.isreseppulang ? item.isreseppulang : null,
    }
    var objSave = {
      'strukorder': strukorder,
      'orderpelayanan': data2.value,
      'noregistrasi': item.registrasi.noregistrasi,
    }
    objSaves.push(objSave);
  }

  isLoading.value = true
  useApi().post(
    `/farmasi/simpan-order`, { 'data': objSaves }).then((response: any) => {
      isLoading.value = false
      delete item.NOREC_SO
      clearInput()
      clearTable()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
function kembaliKeun() {
  window.history.back()
}
function clearTable() {
  data2.value = []
  dataSource.value = data2.value
}
function orderBaru() {
  clearInput()
  clearTable()
  activeValue.value = 1
}
async function fetchDokter(filter: any) {
  let query = ''
  if (filter) {
    query = filter.query
  }
  const response = await useApi().get(`/general/dokter-paging?name= ${query}&limit=10`)
  d_Dokter.value = response.dokter
  // return response.dokter.map((item: any) => {
  //     return { value: item.id, label: item.namalengkap, default: item }
  // })
}


function addItems() {
  if (!item.ruangan) {
    useToaster().error('Ruangan harus di pilih')
    return
  }
  clearInput()
  modalInput.value = true
}
const DialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusRiwayat(e)

    },
    reject: () => { },
  })
}

function hapusRiwayat(e: any) {
  if (e.statuspengerjaan != "Menunggu") {
    H.alert('error', 'Tidak bisa di edit sudah di verifikasi')
    return
  }
  isSimpan.value = true
  useApi().post(
    '/farmasi/hapus-order', { 'norec': e.norec_so }).then(function (response: any) {
      isSimpan.value = false
      let verif = e.statuspengerjaan != "Menunggu" ? true : false
      loadRiwayat(verif)
    })

}
function editRiwayat(e: any) {

  if (e.statuspengerjaan != "Menunggu") {
    H.alert('error', 'Tidak bisa di edit sudah di verifikasi')
    return
  }
  item.NOREC_SO = e.norec_so
  item.resep = e.noorder
  d_Ruangan.value.forEach((element: any) => {
    if (e.objectruangantujuanfk == element.id) {
      item.ruangan = element
    }
  });

  // disabledRuangan.value = true;
  item.penulisResep = { id: e.objectpegawaiorderfk, namalengkap: e.namalengkap }
  item.tglorder = {
    start: new Date(e.tglorder_def),
    end: new Date(e.tglorder_def),
  }
  for (let x = 0; x < e.details.length; x++) {
    const element = e.details[x];
    element.produkfk = element.objectprodukfk
    element.no = x + 1
  }
  data2.value = e.details
  for (var i = data2.value.length - 1; i >= 0; i--) {
    const element = data2.value[i]
    element.noregistrasifk = e.norec_apd
    element.kelasfk = item.header.klsid
    var dosisNA = 1;
    if (element.jeniskemasan == 'Racikan') {
      dosisNA = element.dosis
      element.jumlahxmakan = (parseFloat(element.jumlah) / parseFloat(element.dosis)) * parseFloat(element.kekuatan)
    } else {
      element.jumlahxmakan = element.jumlah
    }
    element.total = (parseFloat(element.jumlah) * (parseFloat(element.hargasatuan) - parseFloat(element.hargadiscount))) + parseFloat(tarifJasa.value)
    element.jmldosis = String(element.jumlahxmakan) + '/' + String(dosisNA) + '/' + String(element.kekuatan)
    element.jasa = tarifJasa.value

  }
  setColor()
  dataSource.value = data2.value

  countTotal()
  activeValue.value = 1
}
const editItems = async (e: any) => {
  // if (isLoading.value == false)
  //     return
  // console.log(e)
  modalInput.value = true
  item.no = e.no
  item.rke = e.rke
  item.jenisKemasan = { id: e.jeniskemasanfk, jeniskemasan: e.jeniskemasan }
  d_satuanResep.value.forEach((element: any) => {
    if (e.satuanresepfk == element.id) {
      item.satuanresep = element
    }
  })
  // item.satuanresep = { id: e.satuanresepfk, satuanresep: e.satuanresep }


  d_aturanPakai.value.forEach((x: any) => {
    if (x.aturanpakai == e.aturanpakai) {
      item.aturanPakai = x
      return
    }
  });
  item.aturanpakaitxt = e.aturanpakai
  item.chkp = 0
  item.chks = 0
  item.chksr = 0
  item.chkm = 0
  listDataSigna.value = []
  let sp = false
  if (e.ispagi != "0") {
    sp = true
    item.chkp = 1
  }
  let ss = false
  if (e.issiang != "0") {
    ss = true
    item.chks = 1
  }
  let sr = false
  if (e.issore != "0") {
    sr = true
    item.chksr = 1
  }
  let sm = false
  if (e.ismalam != "0") {
    sm = true
    item.chkm = 1
  }
  listDataSigna.value = [
    { "id": 1, "nama": "P", 'isChecked': sp },
    { "id": 2, "nama": "S", 'isChecked': ss },
    { "id": 3, "nama": "Sr", 'isChecked': sr },
    { "id": 4, "nama": "M", 'isChecked': sm }
  ]
  item.KeteranganPakai = e.keterangan
  item.persenDiskon = e.persendiscount
  item.jenisRacikan = { id: e.jenisobatfk, jenisracikan: e.jenisobat }
  item.route = { id: e.routefk, name: e.route }
  item.jumlah = e.jumlah

  if (e.iskronis == true) {
    item.checkisKronis = true
  } else {
    item.checkisKronis = false
  }
  if (e.asalprodukfk) {
    item.asal = { id: e.asalprodukfk, asalproduk: e.asalproduk }
  }
  await fetchProduk({ query: e.namaproduk })
  d_produk.value.forEach((element: any) => {
    if (element.id == e.produkfk) {
      item.produk = element
    }
  });

  tarifJasa.value = e.jasa
  dataSelected.value = e
  GETKONVERSI()
}
function hapusItems(e: any) {
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (data2.value[i].no == e.no) {
      data2.value.splice(i, 1);
    }
  }
  if (data2.value.length == 0) {
    disabledRuangan.value = false
  }
  dataSource.value = data2.value
  countTotal()
  clearInput()
}
function reOrder(data: any) {
  delete item.NOREC_SO
  let CariRuangan = d_Ruangan.value.find(x => x.id = data.objectruangantujuanfk)
  let CariDokter = d_Dokter.value.find(x => x.id = data.objectpegawaiorderfk)
  item.rke = 0
  item.ruangan = CariRuangan
  item.pegawaiOrder = CariDokter
  item.tglorder.start = new Date()
  item.tglorder.end = new Date()
  item.isreseppulang = data.isreseppulang === true ? true : false

  data2.value = []
  for (let i = 0; i < data.details.length; i++) {
    const e = data.details[i];

    let hrg1 = Math.round(parseFloat(e.hargasatuan) * parseFloat(e.hasilkonversi))
    let hrgsdk = parseFloat(e.hargadiscount) * parseFloat(e.hasilkonversi)
    let total = (parseFloat(e.jumlah) * (hrg1 - hrgsdk)) + parseFloat(tarifJasa.value)
    let jumlahxmakan = (parseFloat(e.jumlah) / parseFloat(e.dosis)) * parseFloat(e.kekuatan)

    var dataItem = {
      no: i + 1,
      noregistrasifk: item.NOREC_APD,
      generik: null,
      hargajual: String(e.hargasatuan),
      jenisobatfk: e.jenisobatfk,
      kelasfk: item.registrasi.objectkelasfk,
      stock: String(e.jmlstok),
      harganetto: null,
      nostrukterimafk: e.nostrukterimafk,
      norec_spd: null,
      ruanganfk: item.ruangan.id,
      rke: e.rke,
      jeniskemasanfk: e.jeniskemasanfk,
      jeniskemasan: null,
      aturanpakaifk: 0,//item.aturanPakai.id,
      aturanpakai: e.aturanpakai,//item.aturanPakai.aturanpakai,
      ispagi: e.ispagi,
      issiang: e.issiang,
      issore: e.issore,
      ismalam: e.ismalam,
      iskronis: null,
      // aturanpakai2: item.aturanPakai2 ,
      // sbsmid: item.sbsm.id,
      // sbsmname: item.sbsm.name,
      routefk: e.routefk,
      route: e.namaroute,
      asalprodukfk: e.asalprodukfk,
      asalproduk: e.asalproduk,
      produkfk: e.objectprodukfk,
      namaproduk: e.namaproduk,
      nilaikonversi: e.hasilkonversi,
      satuanstandarfk: e.objectsatuanstandarfk,
      satuanstandar: e.satuanstandar,
      satuanviewfk: e.satuanviewfk,
      satuanview: null,
      jmlstok: String(e.jmlstok),
      jumlah: e.jumlah,//item.jumlahbulat,
      jumlahobat: e.jumlah,//item.jumlah,
      dosis: e.dosis,
      hargasatuan: String(e.hargasatuan),
      hargadiscount: String(e.hargadiscount),
      persendiscount: 0,
      total: total,
      jmldosis: String(e.jumlah) + '/' + String(e.dosis) + '/' + String(e.kekuatan),
      jasa: tarifJasa.value,
      keterangan: e.keteranganpakai,
      satuanresepfk: e.satuanresepfk,
      satuanresep: e.satuanresep,
      tglkadaluarsa: e.tglkadaluarsa,
    }
    data2.value.push(dataItem)

    setColor()
    dataSource.value = data2.value
    if (e.jeniskemasanfk != 1) {
      item.rke = parseFloat(item.rke) + 1
    }
  }
  activeValue.value = 1
  countTotal()
  clearInput()
}

function CopyResep(data: any) {
  delete item.NOREC_SO
  let CariRuangan = d_Ruangan.value.find(x => x.id = data.objectruangantujuanfk)
  let CariDokter = d_Dokter.value.find(x => x.id = data.objectpegawaiorderfk)
  item.rke = 0
  item.ruangan = CariRuangan
  item.pegawaiOrder = CariDokter
  item.tglorder.start = new Date()
  item.tglorder.end = new Date()
  // item.isreseppulang = data.isreseppulang === true ? true : false

  data2.value = []
  for (let i = 0; i < data.details.length; i++) {
    const e = data.details;

    let hrg1 = Math.round(parseFloat(e.hargasatuan) * parseFloat(e.hasilkonversi))
    let hrgsdk = parseFloat(e.hargadiscount) * parseFloat(e.hasilkonversi)
    let total = (parseFloat(e.jumlah) * (hrg1 - hrgsdk)) + parseFloat(tarifJasa.value)
    let jumlahxmakan = (parseFloat(e.jumlah) / parseFloat(e.dosis)) * parseFloat(e.kekuatan)

    var dataItem = {
      no: i + 1,
      noregistrasifk: item.NOREC_APD,
      generik: null,
      hargajual: String(e.hargasatuan),
      jenisobatfk: e.jenisobatfk,
      kelasfk: item.registrasi.objectkelasfk,
      stock: String(e.jmlstok),
      harganetto: null,
      nostrukterimafk: e.nostrukterimafk,
      norec_spd: null,
      ruanganfk: item.ruangan.id,
      rke: e.rke,
      jeniskemasanfk: e.jeniskemasanfk,
      jeniskemasan: null,
      aturanpakaifk: 0,//item.aturanPakai.id,
      aturanpakai: e.aturanpakai,//item.aturanPakai.aturanpakai,
      ispagi: e.ispagi,
      issiang: e.issiang,
      issore: e.issore,
      ismalam: e.ismalam,
      iskronis: null,
      // aturanpakai2: item.aturanPakai2 ,
      // sbsmid: item.sbsm.id,
      // sbsmname: item.sbsm.name,
      routefk: e.routefk,
      route: e.namaroute,
      asalprodukfk: e.asalprodukfk,
      asalproduk: e.asalproduk,
      produkfk: e.objectprodukfk,
      namaproduk: e.namaproduk,
      nilaikonversi: e.hasilkonversi,
      satuanstandarfk: e.objectsatuanstandarfk,
      satuanstandar: e.satuanstandar,
      satuanviewfk: e.satuanviewfk,
      satuanview: null,
      jmlstok: String(e.jmlstok),
      jumlah: e.jumlah,//item.jumlahbulat,
      jumlahobat: e.jumlah,//item.jumlah,
      dosis: e.dosis,
      hargasatuan: String(e.hargasatuan),
      hargadiscount: String(e.hargadiscount),
      persendiscount: 0,
      total: total,
      jmldosis: String(e.jumlah) + '/' + String(e.dosis) + '/' + String(e.kekuatan),
      jasa: tarifJasa.value,
      keterangan: e.keteranganpakai,
      satuanresepfk: e.satuanresepfk,
      satuanresep: e.satuanresep,
      tglkadaluarsa: e.tglkadaluarsa,
    }
    data2.value.push(dataItem)

    setColor()
    dataSource.value = data2[0].value
    if (e.jeniskemasanfk != 1) {
      item.rke = parseFloat(item.rke) + 1
    }
  }
  // activeValue.value = 1
  countTotal()
  clearInput()
}

function cetakOrder(data: any) {
  H.printBlade(`report/farmasi/resep?pdf=true&norec_order=${data.norec_order}`)
}

function countDosis() {
  item.dosisNA = 1;
  if (item.jenisKemasan.jeniskemasan == 'Racikan') {
    item.dosisNA = item.dosis
    item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
  } else {
    item.jumlahxmakan = item.jumlah
  }
}
function add() {
  if (!item.produk) {
    useToaster().error('Produk harus di isi')
    return
  }
  if (!item.jumlah || item.jumlah == 0) {
    useToaster().error('Jumlah harus di isi')
    return
  }
  if (item.hargaSatuan == 0) {
    useToaster().error('Harga Satuan belum ada')
    return
  }

  if (item.stok == 0) {
    useToaster().error('Stok tidak ada')
    return
  }

  if (norecSPD.value == '') {
    useToaster().error('Stok tidak ada')
    return
  }
  if (!item.satuan) {
    useToaster().error('Satuan harus di isi')
    return
  }
  if (!item.satuan) {
    useToaster().error('Satuan harus di isi')
    return
  }
  if (!item.aturanpakaitxt) {
    useToaster().error('Aturan Pakai harus di isi')
    return
  }
  countDosis()
  let nomor = 0
  if (data2.value.length == 0) {
    nomor = 1
  } else {
    nomor = data2.value.length + 1
  }
  var qtyOK: any = 0;
  var qtyCetak = 0;
  var total = 0;
  var jumlahreal = 0;

  let jmlbulat = 0;
  let jml = 0;

  jmlbulat = item.jumlahbulat;
  jml = item.jumlah;

  disabledRuangan.value = true;
  var data: any = {};
  if (item.no != undefined) {
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == item.no) {
        data.no = item.no
        data.noregistrasifk = item.NOREC_APD
        data.generik = null
        data.hargajual = item.hargaSatuan
        data.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : null
        data.jenisobat = item.jenisRacikan ? item.jenisRacikan.jenisracikan : null
        data.kelasfk = item.registrasi.objectkelasfk
        data.stock = item.stok
        data.harganetto = item.hargaNetto
        data.nostrukterimafk = norecTerima.value
        data.norec_spd = norecSPD.value
        data.ruanganfk = item.ruangan.id
        data.rke = item.rke
        data.jeniskemasanfk = item.jenisKemasan.id
        data.jeniskemasan = item.jenisKemasan.jeniskemasan
        data.aturanpakai = item.aturanpakaitxt //item.aturanPakai.aturanpakai
        data.aturanpakaifk = 0// item.aturanPakai.id
        data.ispagi = item.chkp
        data.issiang = item.chks
        data.issore = item.chksr
        data.ismalam = item.chkm
        data.iskronis = item.checkisKronis
        data.routefk = item.route ? item.route.id : null
        data.route = item.route ? item.route.name : null
        data.asalprodukfk = item.asal.id
        data.asalproduk = item.asal.asalproduk
        data.produkfk = item.produk.id
        data.namaproduk = item.produk.namaproduk
        data.nilaikonversi = item.nilaiKonversi
        data.satuanstandarfk = item.satuan.ssid
        data.satuanstandar = item.satuan.satuanstandar
        data.satuanviewfk = item.satuan.ssid
        data.satuanview = item.satuan.satuanstandar
        data.jmlstok = item.stok
        data.jumlah = jmlbulat
        data.jumlahobat = jml
        data.dosis = item.dosisNA
        data.hargasatuan = String(item.hargaSatuan)
        data.hargadiscount = String(item.hargadiskon)
        data.persendiscount = item.persenDiskon ? item.persenDiskon : 0
        data.total = item.total
        data.jmldosis = String(item.jumlahxmakan) + '/' + String(item.dosisNA) + '/' + String(item.kekuatan)
        data.jasa = tarifJasa.value
        data.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null
        data.satuanresepfk = item.satuanresep ? item.satuanresep.id : null
        data.satuanresep = item.satuanresep ? item.satuanresep.satuanresep : null
        data.tglkadaluarsa = item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null

        for (let i = 0; i < data2.value.length; i++) {
          const element = data2.value[i];
          if (element.iskronis == true) {
            element.obtkronis = "✔"
          } else {
            element.obtkronis = ""
          }
        }
        data2.value[x] = data;
      }
    }
  } else {
    let checkobat = checkResepTerakhir(item.produk);
    if (checkobat != '')
      useToaster().warn(checkobat)

    if (data2.length > 0) {
      var racikan = data2.value[data2.value.length - 1].jeniskemasan
      if (racikan == 'Non Racikan') {
        item.rke = data2.value[data2.value.length - 1].rke + 1
      }
    }
    data = {
      no: nomor,
      noregistrasifk: item.NOREC_APD,
      generik: null,
      hargajual: String(item.hargaSatuan),
      jenisobatfk: item.jenisRacikan ? item.jenisRacikan.id : null,
      jenisobat: item.jenisRacikan ? item.jenisRacikan.jenisracikan : null,
      kelasfk: item.registrasi.objectkelasfk,
      stock: String(item.stok),
      harganetto: String(item.hargaNetto),
      nostrukterimafk: norecTerima.value,
      norec_spd: norecSPD.value,
      ruanganfk: item.ruangan.id,
      rke: item.rke,
      jeniskemasanfk: item.jenisKemasan.id,
      jeniskemasan: item.jenisKemasan.jeniskemasan,
      aturanpakaifk: 0,//item.aturanPakai.id,
      aturanpakai: item.aturanpakaitxt,//item.aturanPakai.aturanpakai,
      ispagi: item.chkp,
      issiang: item.chks,
      issore: item.chksr,
      ismalam: item.chkm,
      iskronis: item.checkisKronis,
      routefk: item.route ? item.route.id : null,
      route: item.route ? item.route.name : null,
      asalprodukfk: item.asal.id,
      asalproduk: item.asal.asalproduk,
      produkfk: item.produk.id,
      namaproduk: item.produk.namaproduk,
      nilaikonversi: item.nilaiKonversi,
      satuanstandarfk: item.satuan.ssid,
      satuanstandar: item.satuan.satuanstandar,
      satuanviewfk: item.satuan.ssid,
      satuanview: item.satuan.satuanstandar,
      jmlstok: String(item.stok),
      jumlah: jmlbulat,//item.jumlahbulat,
      jumlahobat: jml,//item.jumlah,
      dosis: item.dosisNA,
      hargasatuan: String(item.hargaSatuan),
      hargadiscount: String(item.hargadiskon),
      persendiscount: item.persenDiskon ? item.persenDiskon : 0,
      total: item.total,
      jmldosis: String(item.jumlahxmakan) + '/' + String(item.dosisNA) + '/' + String(item.kekuatan),
      jasa: tarifJasa.value,
      keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
      satuanresepfk: item.satuanresep ? item.satuanresep.id : null,
      satuanresep: item.satuanresep ? item.satuanresep.satuanresep : null,
      tglkadaluarsa: item.tglKadaluarsa ? item.tglKadaluarsa : null,
    }
    data2.value.push(data)
    for (let i = 0; i < data2.value.length; i++) {
      const element = data2.value[i];
      if (element.iskronis == true) {
        element.obtkronis = "✔"
      } else {
        element.obtkronis = ""
      }
    }

  }
  setColor()

  dataSource.value = data2.value
  if (item.jenisKemasan.jeniskemasan != 'Racikan') {
    item.rke = parseFloat(item.rke) + 1
  }
  countTotal()
  clearInput()
}
function setColor() {
  let z = 0
  for (let zx = 0; zx < data2.value.length; zx++) {
    const element = data2.value[zx];
    element.icon = 'fa-inverse lnir lnir-medicine-alt'
    element.color = listColor2.value[z]
    if (z > 4) {
      z = 0
    }
    z++
  }
}

function countTotal() {
  let total = 0
  for (let x = 0; x < data2.value.length; x++) {
    const element = data2.value[x];
    total = total + parseFloat(element.total)
  }
  item.TOTAL = H.formatRp(total, 'Rp.')
}
function changeSatuan(e: any) {
  item.nilaiKonversi = item.satuan.nilaikonversi
}
function jumlahkan() {
  if (item.stok > 0) {
    item.jumlah = (parseFloat(item.jumlahxmakan) * parseFloat(item.dosis)) / parseFloat(item.kekuatan)
    item.jumlahbulat = item.jumlah
  }
}

function clearInput() {
  delete item.produk
  delete item.asal
  delete item.satuan
  delete item.no
  delete item.satuanresep
  delete item.KeteranganPakai
  delete dataSelected.value
  delete item.tglKadaluarsa

  item.qty = 1
  // item.totalAll = 0
  // TOTAL.value =0
  // modalInput.value = false
  item.nilaiKonversi = 0
  item.stok = 0
  item.jumlah = 0
  item.jumlahbulat = item.jumlah

  item.hargadiskon = 0
  item.total = 0
  item.hargaSatuan = 0
  item.hargaNetto = 0
  item.persenDiskon = 0

  if (item.jenisKemasan.jeniskemasan != 'Racikan') {
    delete item.aturanpakaitxt
    item.jumlahxmakan = 1
    item.chkp = 0
    item.chks = 0
    item.chksr = 0
    item.chkm = 0
    listDataSigna.value.forEach((element: any) => {
      element.isChecked = false
    });
  }

}
function setNorecSPD() {

  if (!item.jumlah) return

  if (item.jenisKemasan == undefined) {
    return
  }
  if (item.stok == 0) {
    item.jumlah = 0
    return;
  }
  var qty20 = 0
  tarifJasa.value = parseFloat(item.tarifadminresep)
  if (parseFloat(tarifJasa.value) != 0) {
    if (item.jenisKemasan.id == 2) {
      tarifJasa.value = parseFloat(item.tarifadminresep)
    }
    if (item.jenisKemasan.id == 1) {
      qty20 = Math.floor(parseFloat(item.jumlah) / 20)
      if (parseFloat(item.jumlah) % 20 == 0) {
        qty20 = qty20
      } else {
        qty20 = qty20 + 1
      }

      if (qty20 != 0) {
        tarifJasa.value = tarifJasa.value * qty20
      }
    }
  }
  if (item.no == undefined) {
    for (var i = data2.value.length - 1; i >= 0; i--) {
      if (data2.value[i].rke == item.rke) {
        tarifJasa.value = 0
      }
    }
  }
  item.jumlahbulat = item.jumlah

  var ada = false;
  for (var i = 0; i < dataProdukDetail.value.length; i++) {
    ada = false
    const element = dataProdukDetail.value[i]
    // var tglExpiredPilih = element.tglkadaluarsa
    // if (item.tglKadaluarsa != undefined) {
    //     tglExpiredPilih = H.formatDate(item.tglKadaluarsa.tglkadaluarsa, 'YYYY-MM-DD HH:mm:ss')
    // }


    if (parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi)
      <= parseFloat(element.qtyproduk)
    ) {
      item.tglKadaluarsa = element.tglkadaluarsa
      hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
      hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
      item.hargaSatuan = hrg1.value
      item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
      if (item.hargadiskon == 0) {
        item.hargadiskon = hrgsdk.value
      } else {
        hrgsdk.value = item.hargadiskon
      }

      if (item.jenisKemasan.jeniskemasan != 'Racikan') {
        item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value))
      } else {
        item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
      }
      // item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
      norecTerima.value = element.norec
      norecSPD.value = element.norec_spd
      item.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
      ada = true;
      break;
    }
  }
  if (ada == false) {
    item.hargaSatuan = 0
    item.hargadiskon = 0
    item.hargaNetto = 0
    item.total = 0

    norecSPD.value = ''
    norecTerima.value = ''
    isMerge.value = falser
    if (dataProdukDetail.value.length > 1) {
      var objSave =
      {
        produkfk: item.produk.id,
        ruanganfk: item.ruangan.id
      }

      isSimpan.value = true;
      useApi().postNoMessage(
        '/farmasi/save-stock-merger', objSave).then(function (response: any) {
          isMerge.value = true
          GETKONVERSI()

          isSimpan.value = false
        })


    }
  }
  if (item.jumlah == 0) {
    item.hargaSatuan = 0
    item.hargaNetto = 0
  }
}
const loadResepHariIni = async (norec_pd: any) => {

  const response = await useApi().get(
    `/farmasi/data-order-resep-hari-ini?norec=${norec_pd}`)
  d_resepHariini.value = response

}

const checkResepTerakhir = (produk: any) => {
  let msg = ''
  if (d_resepHariini.value.length > 0) {
    for (let i = 0; i < d_resepHariini.value.length; i++) {
      const element = d_resepHariini.value[i];
      msg = ''
      if (produk.id == element.idproduk) {
        msg = produk.namaproduk + ' ini sudah pernah diorder pada hari ini'
        break
      }
    }
    return msg
  } else {
    return msg
  }
}

const loadResepVerif = async () => {
  await useApi().get(`/farmasi/riwayat-resep-verif?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}`).then((response) => {

  })
}


const fetchProduk = async (filter: any) => {
  const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&ruanganfk=${item.ruangan.id}&limit=10`)
  d_produk.value = response
}
const addListAturanPakai = (bool, data) => {

  let jml = 0
  if (bool == true) {
    if (data.id == 1) {
      item.chkp = 1
    }
    if (data.id == 2) {
      item.chks = 1
    }
    if (data.id == 3) {
      item.chksr = 1
    }
    if (data.id == 4) {
      item.chkm = 1
    }
  } else {
    if (data.id == 1) {
      item.chkp = 0
    }
    if (data.id == 2) {
      item.chks = 0
    }
    if (data.id == 3) {
      item.chksr = 0
    }
    if (data.id == 4) {
      item.chkm = 0
    }
  }
  if (item.chkp == 1) {
    jml = jml + 1
  }
  if (item.chks == 1) {
    jml = jml + 1
  }
  if (item.chksr == 1) {
    jml = jml + 1
  }
  if (item.chkm == 1) {
    jml = jml + 1
  }
  item.aturanpakaitxt = jml + 'x1'
  if (jml == 0) {
    item.aturanpakaitxt = ''
  }
}
watch(
  () => item.jenisKemasan,
  (newValue, oldValue) => {
    if (newValue.jeniskemasan == 'Racikan') {
      showRacikanDose.value = true
    } else {
      showRacikanDose.value = false
    }
  }
)

watch(
  () => item.jumlah,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      setNorecSPD()
    }
  }
)
watch(
  () => item.jumlahxmakan,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      jumlahkan()
    }
  }
)
watch(
  () => item.dosis,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      jumlahkan()
    }
  }
)
watch(
  () => item.hargadiskon,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      hrgsdk.value = item.hargadiskon
      item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
      if (isNaN(item.total)) {
        item.total = 0
      }
    }
  }
)
watch(
  () => selectedTabs,
  (value) => {
    activeValue.value = value
  }
)

watch(activeValue, (value: any) => {
  emit('update:selected', value)
})

watch(
  () => activeValue.value,
  (value) => {
    if (value == 2) {
      loadRiwayat(false)
    }
    if (value == 3) {
      loadRiwayat(true)
    }
  }
)
watch(
  () => item.nilaiKonversi,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (item.stok > 0) {
        item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
        item.jumlah = 0
        item.jumlahbulat = 0;
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.hargaNetto = 0
        item.total = 0
      }
    }
  }
)
watch(() => item.rke, (newValue, oldValue) => {
  if (newValue != oldValue) {
    if (tarifJasa == 0) {
      for (var i = data2.value.length - 1; i >= 0; i--) {
        tarifJasa.value = parseFloat(item.tarifadminresep)
        if (data2.value[i].rke == item.rke) {
          tarifJasa.value = 0
          break;
        }
      }
    }
  }
})


pasienByID(ID_PASIEN)
loadDrop()
loadResepHariIni(item.NOREC_PD)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/emr/order-resep.scss';
</style>
