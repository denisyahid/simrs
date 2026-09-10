<template>
  <section>
    <ConfirmDialog />
    <div>
      <div class="form-layout is-stacked">
        <div class="form-outer" style="margin-top:15px">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header pr-2">
            <div class="form-header-inner">
              <div class="left">
                <h3>Order Resep</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                    Kembali
                  </VButton> -->
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                    :loading="isLoading" @click="cekSimpan()" v-if="activeValue != 4"> Simpan
                  </VButton>
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                    :loading="isLoading" @click="simpanRetur()" v-if="activeValue == 4"> Simpan Retur
                  </VButton>
                </div>
              </div>
            </div>
          </div>

          <div class="form-body p-2">
            <div class="business-dashboard hr-dashboard" v-if="!props.pasien">
              <div class="columns is-multiline">
                <div class="column is-12" v-if="isLoadingPasien">
                  <PlaceloadHeader class="m-3" />
                </div>
                <div class="column is-12" v-if="!isLoadingPasien">
                  <HeadPasien :pasien="pasien" class="m-3" />
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12 ">
                <form class="form-layout is-separate">
                  <div class="form-outer">
                    <div class="form-body">
                      <div class="columns is-multiline">
                        <div class="column is-12 ">
                          <div class="form-section pl-0 pl-3 pr-0 pb-0 mb-0" style="width: 100%">
                            <VCard>
                              <div class="tabs-wrapper" :class="['tab-naver']">
                                <div class="tabs-inner">
                                  <div class="tabs is-boxed">
                                    <ul>
                                      <li v-for="(tab, key) in tabs" :key="key"
                                        :class="[activeValue === tab.value && 'is-active']">
                                        <slot name="tab-link" :active-value="activeValue" :tab="tab" :index="key"
                                          :toggle="toggle">
                                          <a tabindex="0" @keydown.space.prevent="toggle(tab.value)"
                                            @click="toggle(tab.value)">
                                            <VIcon v-if="tab.icon" :icon="tab.icon" />
                                            <span>
                                              <slot name="tab-link-label" :active-value="activeValue" :tab="tab"
                                                :index="key">
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

                          <div class="form-section  pt-0 pr-0" style="width: 100%">
                            <div class="form-section-inner has-padding-bottom h-700-o">
                              <VButton icon="feather:book" color="warning" raised @click="copyResep()"
                                style="float:right; margin-top: -20px;" :loading="isloadingCopy">
                                Copy Resep Terakhir
                              </VButton>
                              <VButton icon="feather:book" color="success" raised @click="openRiwayatDialog"
                                      style="float:left; margin-top: -20px;" :loading="isloadingCopy">
                                Obat Rutin
                              </VButton>

                              <h3 class="has-text-centered">Detail Order </h3>
                              <div class="columns is-multiline">
                                <div class="column is-6">
                                  <VField>
                                    <VLabel>Tanggal Resep</VLabel>
                                    <VDatePicker v-model="item.tglorder" is-range color="pink" trim-weeks :min-date="new Date()">
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

                                      <AutoComplete v-model="item.pegawaiOrder"
                                        :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)"
                                        :optionLabel="'namalengkap'"
                                        :dropdown="true"
                                        :minLength="3"
                                        :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'"
                                        :field="'namalengkap'"
                                        placeholder="ketik nama Dokter"
                                         />

                                    </VControl>
                                  </VField>
                                </div>
                                <div class="column is-3">
                                  <VField label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select"
                                    v-slot="{ id }">
                                    <VControl icon="feather:list" class="prime-auto-select">
                                      <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                                        placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                                        :disabled="disabledRuangan" />
                                    </VControl>

                                  </VField>
                                </div>


                                <div class="column is-12">
                                  <!-- <TListOrderResep title="" straight class="list-widget-v3"
                                    :loading="isLoadBtnEdit" @addItems="addItems" @editItems="editItems"
                                    @hapusItems="hapusItems" @copyResep="copyResep" squared colored>
                                  </TListOrderResep> -->
                                  <TListOrderResep title="" straight class="list-widget-v3" :items="dataSource"
                                    :loading="isLoadBtnEdit" @addItems="addItems" @editItems="editItems"
                                    @hapusItems="hapusItems" @copyResep="copyResep" squared colored>
                                  </TListOrderResep>
                                  <!-- <div class="load-more-wrap has-text-centered p-0 mb-3 mt-4" v-if="dataSource.length">
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
                                  </div> -->
                                </div>

                              </div>
                            </div>


                            </div>
                          </div>
                        <div class="column is-12 mt-0 mx-4 pr-5" v-if="activeValue == 2">
                          <div class="columns is-multiline">
                            <div class="column is-6 mt-2">
                              <VControl>
                                <VSwitchBlock v-model="isNORM" label="Tampilkan Semua Registrasi" color="danger" />
                              </VControl>
                            </div>
                            <div class="column is-6 ">
                              <VField label="Filter Penulis Resep "
                                class="is-rounded-select_Z  is-autocomplete-select is-pulled-right" v-slot="{ id }">
                                <VControl icon="fa:user-md" class="prime-auto-select">
                                  <AutoComplete v-model="item.pegawaiOrder" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true"
                                    :minLength="3" @item-select="changeFilter(item.filterPenulis)" showClear
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                    placeholder="Filter penulis Resep" />

                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-12">
                              <div class="content mb-0">
                                <div class="is-divider mb-0 mt-0"
                                  :data-content="'Terdapat ' + listRiwayat.length + ' data'">
                                </div>
                              </div>
                            </div>
                            <div class="column is-12" :loading="isLoadingRiwayat">
                              <div class="columns is-multiline mt-5" v-if="isLoadingRiwayat">
                                <VPlaceloadText :lines="1" class="p-2" />
                                <div class="column is-12" v-for="key in 3" :key="key">
                                  <VPlaceloadWrap>
                                    <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                    <VPlaceload height="50px" width="75%" class="mx-2" rounded="sm" />
                                  </VPlaceloadWrap>
                                </div>
                              </div>
                              <div class="timeline-wrapper" v-if="isLoadingRiwayat == false && listRiwayat.length > 0">
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
                                      <div class="collapse-icon  is-clickable mr-1 " open
                                        @click=" items.isExpand = false" v-else>
                                        <VIcon icon="feather:chevron-up" />
                                      </div>
                                      <div class="content-wrap is-grey"
                                        :style="[items.cito ? 'background-color:rgb(255 230 238)' : '']">
                                        <table class="is-fullwidth">
                                          <tr>
                                            <td style="width:25%" rowspan="2">
                                              <p class="td-label-x">
                                                {{ items.namaruangan }}
                                              </p>
                                              <span class="td-label-xxx">{{ items.namalengkap }}</span>
                                              <div>
                                                <VTag v-if="items.cito" :color="'danger'" :label="'Cito'" class="mx-1" />
                                                <VTag v-if="items.isbpl" :color="'info'" :label="'BPL'" class="mx-1" />
                                              </div>
                                            </td>
                                            <td style="width:10%">
                                              <span class="td-label">
                                                No Transaksi
                                              </span>
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
                                              <VIconButton icon="feather:edit" @click="editRiwayat(items)"
                                                color="primary" v-tooltip.bubble="'Edit'" raised circle class="mr-2">
                                              </VIconButton>
                                              <VIconButton icon="feather:trash" @click="DialogConfirm(items)"
                                                color="danger" v-tooltip.bubble="'Hapus'" raised circle class="mr-2">
                                              </VIconButton>
                                              <br>
                                              <VIconButton icon="feather:rotate-ccw" @click="reOrder(items)"
                                                color="info" v-tooltip.bubble="'Order Ulang'" raised circle
                                                class="mr-2 my-1">
                                              </VIconButton>
                                              <VIconButton icon="feather:printer" @click="cetakOrder(items)"
                                                color="warning" v-tooltip.bubble="'Cetak'" raised circle
                                                class="mr-2 my-1">
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
                                              <VTag :color="items.color_status"
                                              :label="items.statuspengerjaan" />
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

                                            <div class="column is-4">
                                              <VField>
                                                <VLabelText>Nama Produk
                                                </VLabelText>
                                              </VField>
                                            </div>
                                            <div class="column is-1">
                                              <VField>
                                                <VLabelText>Jumlah </VLabelText>
                                              </VField>
                                            </div>

                                            <div class="column is-2" v-if="items.isantibiotik == true">
                                              <VField>
                                                <VLabelText> Diagnosa / Tindakan </VLabelText>
                                              </VField>
                                            </div>

                                            <div class="column is-1">
                                              <VField>
                                                <VLabelText> Aturan Pakai </VLabelText>
                                              </VField>
                                            </div>
                                            <!-- <div class="column is-2">
                                              <VField>
                                                <VLabelText>Satuan Resep
                                                </VLabelText>
                                              </VField>
                                            </div> -->
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

                                            <div class="column is-4">
                                              <VField>

                                                <VLabel class="txt-elipsis-2">{{
                                                  itemsDet.namaproduk
                                                }} |
                                                  {{ itemsDet.satuanstandar }}
                                                </VLabel>
                                              </VField>
                                            </div>
                                            <div class="column is-1">
                                              <VField>

                                                <VLabel>{{ itemsDet.qtyproduk }}
                                                </VLabel>
                                              </VField>
                                            </div>

                                            <div class="column is-2" v-if="items.isantibiotik == true">
                                              <VField>
                                                <VLabel>{{ itemsDet.diagnosatindakan }}
                                                </VLabel>
                                              </VField>
                                            </div>

                                            <div class="column is-1">
                                              <VField>
                                                <VLabel>{{ itemsDet.aturanpakai }}
                                                </VLabel>
                                              </VField>
                                            </div>
                                            <!-- <div class="column is-2">
                                              <VField>
                                                <VLabel class="txt-elipsis-2" style="width: 130px!important;">
                                                  {{ itemsDet.satuanresep }}
                                                </VLabel>
                                              </VField>
                                            </div> -->

                                          </div>
                                        </VCard>

                                      </div>

                                    </div>
                                  </div>

                                </div>
                              </div>
                              <VCard radius="rounded" class="mt-2"
                                v-if="isLoadingRiwayat == false && listRiwayat.length === 0">
                                <VPlaceholderPage title="Data belum ada." style="min-height:200px"
                                  subtitle="Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu."
                                  larger>
                                  <template #image>
                                    <img class="light-image" src="/images/simrs/not-found-emr.png" style="width:100px"
                                      alt="" />
                                    <img class="dark-image"
                                      src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                      style="width:200px" alt="" />
                                  </template>
                                </VPlaceholderPage>
                              </VCard>
                            </div>

                            <!-- <div class="column is-12 mt-3">

                                      <VButton icon="lnir lnir-arrow-left is-fullwidth" color="info" dark-outlined
                                        @click="orderBaru()">
                                        Order Baru
                                      </VButton>

                                    </div> -->
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
                                              <div class="collapse-icon  is-clickable mr-1 " open
                                                @click=" items.isExpand = false" v-else>
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
                                                      <!-- <VIconButton icon="feather:edit" @click="editRiwayat(items)"
                                                        color="primary" v-tooltip.bubble="'Edit'" raised circle
                                                        class="mr-2">
                                                      </VIconButton> -->
                                                      <VIconButton icon="feather:trash" @click="DialogConfirm(items)"
                                                        color="danger" v-tooltip.bubble="'Hapus'" raised circle
                                                        class="mr-2">
                                                      </VIconButton>
                                                      <br>
                                                      <VIconButton icon="feather:rotate-ccw" @click="reOrder(items)"
                                                        color="info" v-tooltip.bubble="'Order Ulang'" raised circle
                                                        class="mr-2 my-1">
                                                      </VIconButton>
                                                      <VIconButton icon="feather:printer" @click="cetakOrder(items)"
                                                        color="warning" v-tooltip.bubble="'Cetak'" raised circle
                                                        class="mr-2 my-1">
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
                                                      <VTag :color="items.color_status"
                                                        :label="items.statuspengerjaan" />
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

                                                    <div class="column is-5">
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
                                                    <!-- <div class="column is-2">
                                                      <VField>
                                                        <VLabelText>Satuan Resep
                                                        </VLabelText>
                                                      </VField>
                                                    </div> -->
                                                  </div>
                                                  <div class="columns is-multiline"
                                                    v-for="(itemsDet, index2)  in items.details" :key="index2">

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

                                                    <div class="column is-5">
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
                                                    <!-- <div class="column is-2">
                                                      <VField>
                                                        <VLabel class="txt-elipsis-2">
                                                          {{ itemsDet.satuanresep }}
                                                        </VLabel>
                                                      </VField>
                                                    </div> -->

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
                                            <img class="light-image"
                                              src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                                            <img class="dark-image"
                                              src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
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

                        <div class="column is-12 mt-0 mr-4 pr-5" v-if="activeValue == 4">
                          <div class="columns is-multiline">
                            <div class="column is-12 ">
                              <div class="form-section p-0">
                                <div class="form-section-inner p-5">
                                  <h3 class="has-text-centered">Retur Obat</h3>
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <div class="timeline-wrapper">
                                        <div class="timeline-header"></div>
                                        <div class="timeline-wrapper-inner pt-0">
                                          <div class="timeline-container">
                                            <VControl icon="feather:search">
                                              <input v-model="filters" class="input custom-text-filter mb-4" placeholder="Cari Data..." />
                                            </VControl>
                                          
                                            <DataTable :value="listDataReturFiltered" v-model:selection="selectedRetur"
                                            :pt="{
                                                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                                                column: {
                                                    bodycell: ({ state }) => ({
                                                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                                                    })
                                                }
                                            }"
                                            @rowSelect="returSelected"
                                            @rowUnselect="returUnselected"
                                            @rowSelectAll="returSelectAll"
                                            @rowUnselectAll="returUnselectAll"
                                            scrollable
                                            :loading="isLoading"
                                            scrollHeight="600px"
                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                                              <template #empty>
                                                <VPlaceholderPage title="Data belum ada."
                                                  subtitle="Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu."
                                                  larger>
                                                  <template #image>
                                                    <img class="light-image"
                                                      src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                                                    <img class="dark-image"
                                                      src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                                  </template>
                                                </VPlaceholderPage>
                                              </template>
                                              <template #loading>
                                                <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                                                <p style="color:white">Loading data, please wait...</p>
                                              </template>
                                              <Column selectionMode="multiple" headerStyle="width: 3rem" frozen></Column>
                                              <Column field="noorder" header="No Order"></Column>
                                              <Column field="namaproduk" header="Deskripsi"></Column>
                                              <Column field="jumlah" header="Qty Retur">
                                                <template #body="{ data, field }">
                                                    <InputText
                                                        v-model="data[field]"
                                                        type="text" @keypress="onlyNumber($event)" :disabled="!data.isSelected"
                                                        />
                                                </template>
                                              </Column>
                                              <Column field="hargasatuan" header="Tarif">
                                                  <template #body="slotProps">
                                                      {{ H.formatRp(slotProps.data.hargasatuan,'Rp. ')}}
                                                  </template>
                                              </Column>
                                            </DataTable>
                                          </div>
                                        </div>
                                      </div>
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

              </div>
            </div>
          </div>
        </div>
      </div>

      <Dialog  v-model:visible="modalConfirm" modal header="Pilih Jenis Antibiotik" :style="{ width: '30vw' }"
      :maximizable="true" :modal="true">
        <VButton icon="feather:book" color="success" raised @click="modalinputdefinitif()"
          style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
          Definitif
        </VButton>
        <VButton icon="feather:book" color="info" raised @click="empiris()"
          style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
          Empiris
        </VButton>
        <VButton icon="feather:book" color="danger" raised @click="profilaksis()"
          style="float:right; margin-top: 20px; margin: 10px;" :loading="isloadingCopy">
          Profilaksis
        </VButton>
      </Dialog> 

      <Dialog
        v-model:visible="showRiwayatDialog"
        header="Resep Rutin"
        :modal="true"
        :width="800"
        :closable="true"
        @after-open="() => reseprutin(true)">
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="timeline-wrapper" v-if="riwayatList.length > 0">
              <div class="timeline-header"></div>
              <div class="timeline-wrapper-inner pt-0">
                <div class="timeline-container">
                  <div class="timeline-item is-unread " v-for="(items, index)  in riwayatList"
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
                    <div class="collapse-icon  is-clickable mr-1 " open
                      @click=" items.isExpand = false" v-else>
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
                            <!-- <VIconButton icon="feather:edit" @click="editRiwayat(items)"
                              color="primary" v-tooltip.bubble="'Edit'" raised circle
                              class="mr-2">
                            </VIconButton> -->
                            <!-- <VIconButton icon="feather:trash" @click="DialogConfirm(items)"
                              color="danger" v-tooltip.bubble="'Hapus'" raised circle
                              class="mr-2">
                            </VIconButton> -->
                            <br>
                            <div class="d-flex align-items-center" style="margin-bottom: 2rem">
                              <VButton @click="reOrderrutin(items)" color="info" raised class="mr-2 my-1">
                                <VIcon icon="feather:rotate-ccw" />
                                Order Ulang
                              </VButton>
                              </div>
                            <!-- <VIconButton icon="feather:printer" @click="cetakOrder(items)"
                              color="warning" v-tooltip.bubble="'Cetak'" raised circle
                              class="mr-2 my-1">
                            </VIconButton> -->
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
                            <VTag :color="items.color_status"
                              :label="items.statuspengerjaan" />
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

                          <div class="column is-5">
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
                          <!-- <div class="column is-2">
                            <VField>
                              <VLabelText>Satuan Resep
                              </VLabelText>
                            </VField>
                          </div> -->
                        </div>
                        <div class="columns is-multiline"
                          v-for="(itemsDet, index2)  in items.details" :key="index2">

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

                          <div class="column is-5">
                            <VField>

                              <VLabel class="txt-elipsis-4">{{
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
                          <!-- <div class="column is-2">
                            <VField>
                              <VLabel class="txt-elipsis-2">
                                {{ itemsDet.satuanresep }}
                              </VLabel>
                            </VField>
                          </div> -->

                        </div>
                      </VCard>

                    </div>

                  </div>
                </div>

              </div>
            </div>
            <VCard radius="rounded" class="mt-2" v-if="riwayatList.length === 0">
              <VPlaceholderPage title="Data belum ada."
                subtitle="Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu."
                larger>
                <template #image>
                  <img class="light-image"
                    src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                  <img class="dark-image"
                    src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>
            </VCard>
          </div>
        </div>
      </Dialog>
      
      <Dialog v-model:visible="modalInputTEST" modal header="Order Resep" :style="{ width: '80vw'}" :maximizable="true" :modal="true">
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--orange);">
                <div class="columns is-multiline">
                  <div class="column is-8">
                  </div>
                  <div class="column is-4">
                    <h3 class="title is-5  mt-3-min">
                      <table style="border: 3px solid red; border-style: dotted;" class="is-pulled-right">
                        <tr>
                          <td><span :class="'dot is-'" style="padding: 30px;"> {{ item.JENISOBAT }} </span></td>
                        </tr>
                      </table>
                    </h3>
                  </div>
                </div>

                <div class="column is-4">
                  <VField>
                    <VLabel>Jenis Kemasan</VLabel>
                    <VControl>
                      <VRadio v-for="items in d_kemasan" :key="items.id" v-model="item.jenisKemasan" :value="items"
                        :label="items.jeniskemasan" :name="items.id" color="primary" @change.stop="changeJenis(items.id)"/>
                    </VControl>
                  </VField>
                </div>

                <div class="columns is-multiline" v-if="showRacikanDose">

                  <div class="column is-4">
                    <VField>
                      <VLabel>Farmasi</VLabel>
                      <VControl icon="feather:list" class="prime-auto-select">
                        <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                          placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                          :disabled="disabledRuangan" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VLabel>Berat Badan</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.KeteranganPakai" placeholder="kg" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1">
                    <VField>
                      <VRadio v-model="item.cito" value="Cito" label="Cito" color="primary"
                        style="margin-top: 20px; height: 30px;" />
                    </VField>
                  </div>
                  <div class="column is-5">
                    <VField>
                      <VRadio v-model="item.obatina" value="BPJS" label="Obat Non INACbg" color="primary"
                        style="margin-top: 20px; height: 30px;" />
                    </VField>
                  </div>


                  <div class="column is-2">
                    <VField>
                      <VLabel>R/Ke</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="number" v-model="item.rke" placeholder="R/Ke" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Produk </VLabel>
                      <VControl icon="feather:search" class="prime-auto-select">
                        <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                          :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-rounded"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                          placeholder="ketik untuk mencari..." @item-select="changeProduk(item.produk)">
                          <template #option="slotProps">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <span style="font-weight:bold">{{ slotProps.option.name }}</span>
                              </div>
                              <div class="column is-12 mt-5-min">
                                <table style="width:50%">
                                  <tr>
                                    <td><b>{{ slotProps.option.namaproduk }}</b></td>
                                  </tr>
                                  <tr>
                                    <td>Kategory : {{ slotProps.option.satuanstandar }}</td>
                                  </tr>
                                  <tr>
                                    <td><b>Stok : {{ slotProps.option.stok }}</b></td>
                                  </tr>
                                </table>
                              </div>
                            </div>
                          </template>
                        </AutoComplete>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Satuan</VLabel>
                      <VControl icon="feather:search" class="prime-auto-select">
                        <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'satuanstandar'"
                          class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                          @change="changeSatuan(item.satuan)" />
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
                        <VInput type="number" v-model="item.dosis" placeholder="Dosis" class="is-rounded" />
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
                          <VInput type="text" v-model="item.aturanpakaitxt" placeholder="Aturan Pakai"
                            class="is-rounded" />
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
                              @keydown.enter.prevent="addListAturanPakai(true, opsi)"
                              @change="addListAturanPakai(opsi.isChecked, opsi)" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>

                  <div class="column" :class="[showRacikanDose ? 'is-3' : 'is-6']">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Satuan Resep</VLabel>
                      <VControl icon="feather:search" class="prime-auto-select">
                        <Dropdown v-model="item.satuanresep" :options="d_satuanResep" :optionLabel="'satuanresep'"
                          class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column " :class="[showRacikanDose ? 'is-12' : 'is-6']">
                    <VField>
                      <VLabel>Keterangan</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.KeteranganPakai" placeholder="Catatan" class="is-rounded" />
                      </VControl>
                    </VField>
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
                <div class="columns is-multiline" v-if="showRacikanDoseFalse">
                  <!-- allz -->

                  <form class="modal-form">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <VField>
                          <div class="columns is-multiline">
                            <!-- <div class="column is-4">
                              <VControl icon="feather:search">
                                <input v-model="item.namaprodukserch" type="text" class="input is-rounded"
                                  @keyup.enter.prevent="filterProdukna()" placeholder="Cari nama produk..." />
                              </VControl>
                            </div>
                            <div class="column is-4">
                              <VControl icon="feather:search">
                                <input v-model="item.kodeprodukserch" type="text" class="input is-rounded"
                                  @keyup.enter.prevent="filterProdukna()" placeholder="Cari kode produk..." />
                              </VControl>
                            </div> -->



                            <div class="column is-4">
                              <VButton @click="filterProdukna()" :loading="isLoading" type="button"
                                icon="feather:search" class="is-pulled-left mr-2" color="info" raised> Cari Data
                              </VButton>

                              <VButton @click="paketObat()" :loading="isloadingPaketObat" color="info" raised
                                class="is-pulled-left  mr-2">Paket Obat
                              </VButton>
                              <VIconButton type="button" raised circle icon="feather:refresh-cw" @click="clearInput()"
                                outlined color="danger" class="is-pulled-left mr-2"
                                v-tooltip-prime.right="'Kosongkan '">
                              </VIconButton>
                            </div>
                          </div>
                        </VField>

                      </div>
                      <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
                        <div class="columns is-multiline">

                          <div class="column is-4">
                            <div class="column is-12">
                              <UIWidget class="search-widget">
                                <template #body>
                                  <div class="field">
                                    <VControl icon="feather:search">
                                      <input v-model="item.namaprodukserch" type="text" class="input is-rounded"
                                        @keyup.enter.prevent="filterProdukna()" placeholder="Cari nama produk..." />
                                    </VControl>
                                  </div>
                                </template>
                              </UIWidget>

                            </div>
                            <DataTable v-model:selection="selectedProduct"
                            :value="dataProductTampil"
                            selectionMode="multiple"
                            paginator
                            :metaKeySelection="metaKey"
                            dataKey="id"
                            @rowSelect="onProductSelected"
                            @rowUnselect="onProductUnSelected"
                            tableStyle="min-width: 50rem">
                              <Column field="namaproduk" header="Nama"></Column>
                              <Column field="namaprodukuse" header="#"></Column>
                            </DataTable>
                            <!-- <div class="form-section pt-0 pl-0" :loading="isLoading">
                              <div class="form-section-inner">

                                <div class="column is-12 h-400-o">
                                  <div class="columns is-multiline mb-2" :loading="isLoading">
                                    <div class="column is-5" v-for="items in filteredLayanan" :key="items.id">

                                      <VField grouped>
                                        <VControl raw subcontrol>
                                          <VCheckbox v-model="item.produkCeklis[items.id]" :label="items.namaprodukuse"
                                            color="info" @change="getSelected()" />
                                        </VControl>
                                      </VField>

                                    </div>


                                  </div>
                                </div>
                              </div>
                            </div> -->
                          </div>

                          <div class="column is-8">

                            <div class="is-divider" data-content="Resep di buat" ></div>


                            <DataTable :value="dataSource" showGridlines class="p-datatable-sm"
                              :loading="isLoading" :editingRows="editingRows"
                              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :paginator="true"
                              :rows="5" :rowsPerPageOptions="[5, 10, 25]">
                              <Column :exportable="false" header="#" style="width:4rem">
                                <template #body="slotProps">
                                  <VButton icon="pi pi-trash" class="p-button-rounded p-button-danger"
                                    @click="hapusItems(slotProps.data)" />

                                    <VButton icon="pi pi-pencil" class="p-button-rounded p-button-warning mr-2"
                                    @click="editItems(slotProps.data)" :loading="slotProps.data.LoadBtnEdit" />
                                </template>
                              </Column>

                              <Column field="no" header="No"></Column>
                              <Column field="rke" style="text-align: end;" header="R-ke">
                                <template #body="slotProps">
                                  {{ slotProps.data.rke }}
                                </template>
                                <template #editor="{ data, field }">
                                  <input v-model="data[field]" autofocus />
                                  <!-- <InputText v-model="data[field]" autofocus /> -->
                                </template>
                              </Column>

                              <Column style="text-align: end;" header="Jenis Racikan" field="jenisobat">
                                <template #body="slotProps">
                                  {{ slotProps.data.jenisobat }}
                                </template>
                                <template #editor="{ data, field }">
                                  <Dropdown v-model="item.jenisRacikan1" :options="d_jenisRacikan"
                                    :optionLabel="'jenisracikan'" class="is-rounded" placeholder="Pilih data"
                                    style="width: 100%;" showClear :filter="true" />
                                </template>
                              </Column>
                              <!-- <Column field="jenisKemasan"></Column> -->
                              <!-- <Column field="produkfk" header="Kode Produk" :sortable="true"></Column> -->
                              <Column field="namaproduk" header="Produk" :sortable="true"></Column>
                              <Column field="satuanstandar" header="Satuan"></Column>
                              <Column field="nilaikonversi" header="Konversi"></Column>
                              <Column field="stock" header="Stok"></Column>
                              <Column field="totalstok" header="Stok IF"></Column>
                              <template v-if="item.JENISOBAT === 'Obat Empiris'">
                              <Column field="qtymax" header="QTY MAX"></Column>
                            </template>
                              <template v-if="item.JENISOBAT === 'Obat Profilaksis'">
                              <Column field="qtymax" header="QTY MAX"></Column>
                            </template>
                              <!-- <Column field="kekuatan" header="kekuatan"></Column> -->
                              <Column field="jumlahobat" style="text-align: end;" header="Jumlah Racikan">
                              <template #body="slotProps">
                                {{ slotProps.data.jumlahobat }}
                              </template>
                              <template #editor="{ data, field }">
                                <input
                                  v-model="data[field]"
                                  autofocus
                                />
                              </template>
                            </Column>
                              <!-- <Column field="dosis" style="text-align: end;" header="Dosis">
                                <template #body="slotProps" type="number">
                                  {{ slotProps.data.dosis }}
                                </template>
                                <template #editor="{ data, field }">
                                  <input v-model="data[field]" autofocus />
                                  <InputText v-model="data[field]" autofocus />
                                </template>
                              </Column> -->
                              <Column field="dosis" style="text-align: end;" header="Dosis">
                                <template #body="slotProps">
                                  <div class="p-inputgroup">
                                    <!-- Input untuk dosis -->
                                    <input v-model="item.dosispecahan" class="p-inputtext" type="text" :style="{ width: '3rem' }" style="display: none !important"/>
                                    <input v-model="slotProps.data.dosis" class="p-inputtext" type="number"  :style="{ width: '3rem' }"/>
                                    <!-- Tombol untuk menjalankan getSelisih -->
                                    <VButton
                                      icon="pi pi-check"
                                      class="p-button-rounded p-button-success ml-2"
                                      @click="getSelisih({ data: slotProps.data, field: 'dosis', newValue: slotProps.data.dosis })"
                                    />
                                  </div>
                                </template>
                              </Column>
                              <Column field="hargajual" header="Harga Jual"></Column>
                              <Column field="jumlah" style="text-align: end;" header="Jumlah">
                                <template #body="slotProps">
                                  {{ slotProps.data.jumlah }}
                                </template>
                                <template #editor="{ data, field }">
                                  <input v-model="data[field]" autofocus/>
                                  <!-- <InputText v-model="data[field]" autofocus /> -->
                                </template>
                              </Column>
                              <Column style="text-align: end;" header="Aturan Pakai" field="aturanpakai">
                                <template #body="slotProps">
                                  {{ slotProps.data.aturanpakai }}
                                </template>
                                <template #editor="{ data, field }">
                                  <input v-model="data[field]" autofocus />
                                  <!-- <InputText v-model="data[field]" autofocus /> -->
                                </template>
                              </Column>
                              <Column style="text-align: end;" header="Satuan Resep" field="satuanresep">
                                <template #body="slotProps">
                                  <Dropdown v-model="slotProps.data.satuanresep" :options="d_satuanResep"
                                    :optionLabel="'satuanresep'" class="is-rounded" placeholder="Pilih data"
                                    style="width: 100%;" showClear :filter="true" />
                                </template>
                                <template #editor="{ data, field }">
                                  <Dropdown v-model="item.satuanresep1" :options="d_satuanResep"
                                    :optionLabel="'satuanresep'" class="is-rounded" placeholder="Pilih data"
                                    style="width: 100%;" showClear :filter="true" autofocus />
                                </template>


                              </Column>


                              <template #paginatorstart>
                                <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                              </template>
                              <template #paginatorend>
                                <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                              </template>

                            </DataTable>
                          </div>
                        </div>

                      </div>
                    </div>
                  </form>
                </div>

                <div class="column is-12" v-if="showRacikanDose">
                  <div class="content">
                    <div class="is-divider" :data-content="infoStok" />
                  </div>
                </div>

                <div class="column is-12" v-if="showRacikanDose">
                  <DataTable :value="dataSourceStokProduk" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                    <Column field="no" header="No"></Column>
                    <Column field="namaruangan" header="ruangan"></Column>
                    <Column field="stok" header="Stok"></Column>
                  </DataTable>

                </div>

              </div>
            </div>

          </div>
        </form>
        <div class="column is-12">
          <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
            <h3 class="title is-5  mt-3-min">
              <span> Riwayat</span>
              <span class="is-pulled-right">
                <VTag color="success" :label="listSIMRSLama.length" rounded outlined />
              </span>
            </h3>
            <div class="columns is-multiline">
              <div class="column is-12" v-if="isLoadRiwayatOLD">
                <VPlaceloadText :lines="5" width="100%" last-line-width="25%" />
                <VPlaceloadText :lines="5" width="100%" class="mt-2" last-line-width="25%" />
                <VPlaceloadText :lines="5" width="100%" class="mt-2" last-line-width="25%" />
              </div>
              <div class="column is-12" v-else style="height:660px;overflow:auto">
                <div class="columns is-multiline">
                  <div class="column is-12 mb-0 mt-3-min" v-for="itemZ in listSIMRSLama" v-if="listSIMRSLama.length">
                    <TWidgetListResep :title="itemZ.namaproduk" :pegawai="itemZ.penulisResep" :subtitle="itemZ.jumlah"
                      :subtitle1="itemZ.aturanpakai" :subtitle2="H.formatDateIndoSimpleNoDay(itemZ.tglorder)"
                      :subtitle3="itemZ.simslama == true ? 'SIMRS LAMA' : ''" :color_sub_3="'danger'"
                      class="inbox-widget-3 success mb-0" />
                  </div>
                  <div class="column is-12" v-else>
                    <div class="flex-list-inner  text-center">
                      <VPlaceholderSection :title="H.assets().notFound" class="my-6">
                        <template #image>
                          <img class="light-image" :src="'/@src/assets/illustrations/placeholders/search-4-dark.svg'"
                            alt="" style="width: 100px;" />
                          <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                            style="width: 100px;" />
                        </template>
                      </VPlaceholderSection>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <template #footer>
          <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
            Tutup
          </VButton>
          <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
            @click="cekSimpan()"> Simpan
          </VButton>
        </template>
      </Dialog>

      <Dialog v-model:visible="modalInput" modal header="Order Resep" :style="{ width: '98vw' }" :maximizable="true" :modal="true">
        <!-- <VModal :open="modalInput" noclose title="Tambah Resep" size="big" actions="right" @close="modalInput = false"
        cancelLabel="Batal">-->
        <!-- <template #content> -->

        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--orange);">
                <div class="columns is-multiline">
                  <div class="column is-8">
                    <VButton @click="paketObat()" :loading="isloadingPaketObat" color="info" raised style="margin-top: -15px;"
                      class="is-pulled-left  mr-2">Paket Obat
                    </VButton>
                  </div>
                  <div class="column is-4">
                    <h3 class="title is-5  mt-3-min">
                      <table style="border: 3px solid red; border-style: dotted;" class="is-pulled-right">
                        <tr>
                          <td><span :class="'dot is-'" style="padding: 30px;"> {{ item.JENISOBAT }} </span></td>
                        </tr>
                      </table>
                    </h3>
                  </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-3">
                          <VField>
                            <VLabel>Jenis Resep</VLabel>
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox v-model="item.cito" class="p-0" label="Cito" color="primary" square />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox v-model="item.isrutin" class="p-0" label="Rutin" color="primary" square/>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-4">
                                <VField v-if="showBPL">
                                  <VControl raw subcontrol>
                                    <VCheckbox v-model="item.isbpl" class="p-0" label="BPL" color="primary" square />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                            
                            <div class="columns is-multiline">
                              <div class="column is-6">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox v-model="item.iskronis23" class="p-0" label="Non-Inacbgs 23 Hari" color="primary" square style="color: red;" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-6">
                                <VField>
                                  <VControl raw subcontrol>
                                    <VCheckbox v-model="item.iskronis30" class="p-0" label="Non-Inacbgs 30 Hari" color="primary" square style="color: red;"/>
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </VField>
                        </div>
                        <div class="column is-1">
                          <VField>
                            <VLabel>Jenis Kemasan</VLabel>
                            <VControl>
                              <VRadio v-for="items in d_kemasan" :key="items.id" v-model="item.jenisKemasan"
                                      :value="items" :label="items.jeniskemasan" name="jenisKemasan" color="primary" @change.stop="changeJenis(items.id)"/>
                            </VControl>
                          </VField>
                        </div>

                        <div class="column is-5">
                            <VField>
                              <VControl>
                                <VCheckbox class="p-0" label="Edit Racikan" style="font-weight: bold" color="primary" square v-model="editRacikan"/>
                              </VControl>
                            </VField>
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <span>R/ke </span>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl icon="feather:bookmark">
                                    <VInput  type="number"  v-model="item.rke" placeholder="R/Ke" label="'R/ke'"
                                    :disabled="!editRacikan"  @change="updateRacikanFields"/>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-2" style="margin-top: -15px;">
                                <span>Jenis Racikan</span>
                              </div>
                              <div class="column is-10" style="margin-top: -15px;">
                                <Dropdown v-model="item.jenisRacikan" :options="d_jenisRacikan" :optionLabel="'jenisracikan'"
                                  placeholder="Pilih data" showClear :filter="true" :disabled="!editRacikan" style="width:200px"/>
                              </div>
                              <div class="column is-2" style="margin-top: -15px;">
                                <span>Aturan Pakai</span>
                              </div>
                              <div class="column is-10" style="margin-top: -15px;">
                                <VField>
                                  <VControl icon="feather:bookmark">
                                    <VInput v-model="item.aturanpakai" placeholder="Aturan Pakai" :disabled="!editRacikan"/>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-2" style="margin-top: -15px;">
                                <span>Jumlah Racikan</span>
                              </div>
                              <div class="column is-10" style="margin-top: -15px;">
                                <VField>
                                  <VControl icon="feather:bookmark">
                                    <VInput type="number" field="jumlahobat" v-model="item.jumlahobat" placeholder="Jumlah Racikan"
                                    label="'Jumlah Racikan'" :disabled="!editRacikan"/>
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                        </div>

                        <div class="column is-3">
                          <VField>
                            <VLabel>Riwayat Alergi</VLabel>
                            <VControl>
                              <VTextarea v-model="item.riwayatalergi" rows="5" placeholder="Alergi">
                              </VTextarea>
                            </VControl>
                          </VField>
                          
                          <div class="columns is-align-items-center mt-3">
                            <div class="column is-3.5">
                              <span>Total Resep</span>
                            </div>
                            <div class="column is-9">
                              <VField>
                                <VControl icon="feather:bookmark">
                                  <VInput :value="H.formatRupiah(item.grandtotal, 'Rp')" placeholder="Total Biaya Resep" disabled/>
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>

                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div v-if="item.JENISOBAT === 'Obat Profilaksis'" class="column is-4">
                        <VField label="Jenis Operasi " class="is-rounded-select_Z  ">
                          <VControl  class="prime-auto-select">
                            <AutoComplete v-model="item.jenisoperasi" :suggestions="d_JenisOperasi"
                            @complete="jenisoperasi($event)" @select="fetchTindakan2()" :dropdown="true"
                            :minLength="3"  :optionLabel="'namaoperasi'"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            placeholder="Jenis Operasi" />
                          </VControl>
                        </VField>
                      </div>
                      
                      <div v-if="item.JENISOBAT === 'Obat Profilaksis'" class="column is-4">
                        <VField label="Devisi " class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:bookmark">
                            <VInput  field="divisi" v-model="item1.devisi" placeholder="devisi"
                            label="Devisi" :disabled="!devisi"/>
                          </VControl>
                        </VField>
                      </div>
                      
                      <div v-if="item.JENISOBAT === 'Obat Profilaksis'" class="column is-4">
                        <VField label="Jenis Antibiotik" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:bookmark">
                            <VInput  field="antibiotik" v-model="item1.antibiotik" placeholder="Jenis Antibiotik"
                            label="Jenis Antibiotik" :disabled="!antibiotik"/>
                          </VControl>
                        </VField>
                      </div>
                                  
                      <div v-if="item.JENISOBAT === 'Obat Empiris'" class="column is-3">
                        <VField label="Pilih KSM " class="is-rounded-select_Z">
                          <VControl  class="prime-auto-select">
                            <AutoComplete v-model="item.jeniksm" :suggestions="d_JenisKsm"
                            @complete="jenisksm($event)" :dropdown="true"
                            :minLength="3"  :optionLabel="'divisi'"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            placeholder="Pilih KSM" />
                          </VControl>
                        </VField>
                      </div>
                      
                      <div v-if="item.JENISOBAT === 'Obat Empiris'" class="column is-6">
                        <VField label="Keadaan Klinik/Penyakit" class="is-rounded-select_Z">
                          <VControl  class="prime-auto-select">
                            <AutoComplete v-model="item.jenistindakan" :suggestions="d_JenisTindakan"
                            @complete="jenistindakan($event)" @select="fetchTindakan1()" :dropdown="true"
                            :minLength="3"  :optionLabel="'namatindakan'"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            placeholder="Jenis Tindakan" />
                          </VControl>
                         </VField>
                      </div>
                    </div>
                  </div>

                <div class="columns is-multiline" v-if="showRacikanDose">
                  <div class="column is-4">
                    <VField>
                      <VLabel>Farmasi</VLabel>
                      <VControl icon="feather:list" class="prime-auto-select">
                        <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                          placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                          :disabled="disabledRuangan" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VLabel>Berat Badan</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.KeteranganPakai" placeholder="kg" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1">
                    <VField>
                      <VRadio v-model="item.cito" value="Cito" label="Cito" color="primary"
                        style="margin-top: 20px; height: 30px;" />
                    </VField>
                  </div>
                  <div class="column is-5">
                    <VField>
                      <VRadio v-model="item.obatina" value="BPJS" label="Obat Non INACbg" color="primary"
                        style="margin-top: 20px; height: 30px;" />
                    </VField>
                  </div>


                  <div class="column is-2">
                    <VField>
                      <VLabel>R/Ke</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="number" v-model="item.rke" placeholder="R/Ke" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Produk </VLabel>
                      <VControl icon="feather:search" class="prime-auto-select">
                        <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                          :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-rounded"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                          placeholder="ketik untuk mencari..." @item-select="changeProduk(item.produk)">
                          <template #option="slotProps">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <span style="font-weight:bold">{{ slotProps.option.name }}</span>
                              </div>
                              <div class="column is-12 mt-5-min">
                                <table style="width:50%">
                                  <tr>
                                    <td><b>{{ slotProps.option.namaproduk }}</b></td>
                                  </tr>
                                  <tr>
                                    <td>Kategory : {{ slotProps.option.satuanstandar }}</td>
                                  </tr>
                                  <tr>
                                    <td><b>Stok : {{ slotProps.option.stok }}</b></td>
                                  </tr>
                                </table>
                              </div>
                            </div>
                          </template>
                        </AutoComplete>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Satuan</VLabel>
                      <VControl icon="feather:search" class="prime-auto-select">
                        <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'satuanstandar'"
                          class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                          @change="changeSatuan(item.satuan)" />
                      </VControl>
                    </VField>
                  </div>
                  <!-- <div class="column is-4">
                    <VField>
                      <VLabel>Jenis Kemasan</VLabel>
                      <VControl>
                        <VRadio v-for="items in d_kemasan" :key="items.id" v-model="item.jenisKemasan" :value="items"
                          :label="items.jeniskemasan" name="{{items.id}}" color="primary" />
                      </VControl>
                    </VField>
                  </div> -->
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
                        <VInput type="number" v-model="item.dosis" placeholder="Dosis" class="is-rounded" />
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

                  <!-- <div class="column is-6">
                      <VField class="is-rounded-select is-autocomplete-select">
                        <VLabel>Produk</VLabel>
                        <VControl icon="feather:search" class="prime-auto-select">
                          <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)" autofocus
                            :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-rounded" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik untuk mencari..."
                            @item-select="changeProduk(item.produk)">
                            <template #option="slotProps">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <span style="font-weight:bold">{{ slotProps.option.namaproduk }}</span>
                                </div>
                                <div class="column is-12 mt-5-min">
                                  {{ slotProps.option.generik }}
                                </div>
                              </div>
                            </template>
                          </AutoComplete>
                        </VControl>
                        <small v-if="isLastObatByDate" style="color:red">Produk {{ item.namaproduk }}, terakhir order tanggal : {{
                          item.tglpelayanan }}</small>
                      </VField>
                    </div> -->

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
                          <VInput type="text" v-model="item.aturanpakaitxt" placeholder="Aturan Pakai"
                            class="is-rounded" />
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
                              @keydown.enter.prevent="addListAturanPakai(true, opsi)"
                              @change="addListAturanPakai(opsi.isChecked, opsi)" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>

                  <div class="column" :class="[showRacikanDose ? 'is-3' : 'is-6']">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Satuan Resep</VLabel>
                      <VControl icon="feather:search" class="prime-auto-select">
                        <Dropdown v-model="item.satuanresep" :options="d_satuanResep" :optionLabel="'satuanresep'"
                          class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                      </VControl>
                    </VField>
                  </div>

                  <div class="column " :class="[showRacikanDose ? 'is-12' : 'is-6']">
                    <VField>
                      <VLabel>Keterangan</VLabel>
                      <VControl icon="feather:bookmark">
                        <VInput type="text" v-model="item.KeteranganPakai" placeholder="Catatan" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <!-- <div class="column is-12">
                      <div class="content mb-0 mt-5-min">
                        <div class="is-divider mb-0" data-content="Informasi"></div>
                      </div>
                    </div> -->
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
                <div class="columns is-multiline" v-if="showRacikanDoseFalse">
                  <!-- allz -->

                  <form class="modal-form">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <VField>
                          <div class="columns is-multiline">
                            <div class="column is-4" v-show="false">
                              <VControl icon="feather:search">
                                <input v-model="item.produk" type="text" class="input is-rounded"
                                  @keyup.enter.prevent="filterProdukna()" placeholder="Cari nama produk..." />
                              </VControl>
                            </div>
                            <!-- <div class="column is-4">
                              <VControl icon="feather:search">
                                <input v-model="item.kodeprodukserch" type="text" class="input is-rounded"
                                  @keyup.enter.prevent="filterProdukna()" placeholder="Cari kode produk..." />
                              </VControl>
                            </div> -->

                            <!-- <div class="column is-4">
                              <VButton @click="filterProdukna()" :loading="isLoading" type="button"
                                icon="feather:search" class="is-pulled-left mr-2" color="info" raised> Cari Data
                              </VButton>
                              <VButton icon="feather:plus" @click="add()" outlined :loading="isLoading" color="info" raised
                                                                      class="is-pulled-left  mr-2">Tambah
                                                                  </VButton>
                            </div> -->
                          </div>
                        </VField>

                      </div>
                      <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green); width: 100%">
                        <div class="columns is-multiline">

                          <div class="column is-4">
                            <div style="text-align: center;
                            "
                            >
                              <b
                              >Info Warna Obat</b>
                              <div style=" display: flex; justify-content: center; gap: 12px; margin-top: 4px">
                                <p
                                style="
                                color: #0000CD;
                                border-style: solid;
                                border-color:#0000CD ;
                                border-radius: 10px;
                                /* margin-bottom: 5px; */
                                /* margin-top: 8px; */
                                width: 20px;
                                /* color: #FFFFFF; */
                                /* display: block; */
                                "
                                >Obat Non Fornas</p>
                                <!-- <br> -->
                              <p style="
                              color: #000000;
                              border-style : solid;
                              border-color : #000000;
                              border-radius: 10px;
                              width: 7rem;
                              /* display: block; */
                              /* color: #FFFFFF; */
                              "

                              >Obat Fornas</p>
                              </div>
                              </div>
                            <div class="column is-12">
                              <UIWidget class="search-widget">
                                <template #body>
                                  <div class="field">
                                    <div class="columns is-multiline">
                                      <div class="column is-12">
                                        <VControl icon="feather:search">
                                          <input v-model="item.namaprodukserch" type="text" class="input is-rounded"
                                            @keyup.enter.prevent="filterProdukna()" placeholder="Cari produk..." />
                                        </VControl>
                                      </div>
                                      <div class="column is-4" style="display: none !important">
                                        <VButton @click="filterProdukna()" :loading="isLoading" type="button"
                                          icon="feather:search" class="is-pulled-right mr-2" color="info" raised> Cari Data
                                        </VButton>
                                      </div>
                                    </div>
                                  </div>
                                </template>
                              </UIWidget>

                            </div>

                            <div class="form-section pt-0 pl-0" :loading="isLoading">
                              <div class="form-section-inner">

                                <div class="column is-12 h-400-o">
                                  <div class="columns is-multiline mb-2" :loading="isLoading">
                                    <div class="column is-6" v-for="items in filteredLayanan" :key="items.id">

                                      <VField grouped>
                                        <VControl raw subcontrol>
                                          <VCheckbox v-model="item.produkCeklis[items.id]"
                                          :label="items.namaprodukuse"
                                          :style="{ color: items.fornas !== true && items.objectdetailjenisprodukfk === 2546 ? 'blue' : 'black' }"
                                            color="info" @change="getSelected()" />
                                        </VControl>
                                      </VField>

                                    </div>


                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="column is-8">
                            <VIconButton type="button" raised circle icon="feather:help-circle" @click="help()"
                              outlined color="primary" class="is-pulled-right mr-2"
                              v-tooltip-prime.right="'Help '" style="margin-top: -15px;">
                            </VIconButton>
                            <VIconButton type="button" raised circle icon="feather:refresh-cw" @click="clearInput()"
                                outlined color="danger" class="is-pulled-right mr-2"
                                v-tooltip-prime.right="'Kosongkan '" style="margin-top: -15px;">
                            </VIconButton>

                            <div class="is-divider" data-content="Resep di buat" ></div>

                            <DataTable :value="dataSource" showGridlines editMode="cell" class="p-datatable-sm"
                                      :loading="isLoading" @cell-edit-complete="getSelisih" tableClass="editable-cells-table"
                                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                      currentPageReportTemplate="Showing {first} to {last} of {totalRecords} " :paginator="true"
                                      :rows="25" :rowsPerPageOptions="[5, 10, 25,1000]">

                              <Column :exportable="false" header="#" style="width: 4rem;">
                                <template #body="slotProps">
                                  <!-- <VButton icon="pi pi-trash" class="p-button-rounded p-button-danger"
                                            style="width: 2rem; height: 2rem;"
                                            @click="hapusItems(slotProps.data)" /> -->
                                            <VButton
                                                  icon="pi pi-trash"
                                                  class="p-button-rounded p-button-danger"
                                                  @click="showConfirmDialog(slotProps.data)"
                                                />
                                  <VButton icon="pi pi-pencil" class="p-button-rounded p-button-danger"
                                            @click="editItems(slotProps.data)"  v-if="slotProps.data.jeniskemasanfk !== 2" />
                                </template>
                              </Column>
                              <Dialog
                                v-model:visible="isDialogVisible"
                                header="Konfirmasi Hapus"
                                :footer="dialogFooter"
                                :breakpoint="'960px'">
                                <b>Apakah Anda yakin ingin menghapus item ini?</b>
                                <template #footer>
                                  <div>
                                      <VButton
                                        style="margin-right: 20px; background-color:#e84460; color: #FFFFFF"
                                        label="No"
                                        icon="pi pi-times"
                                        class="p-button-text"
                                        @click="cancelDelete"
                                      />
                                      <VButton
                                        style="background-color: #48bc84; color: #FFFFFF"
                                        label="Yes"
                                        icon="pi pi-check"
                                        class="p-button-text"
                                        @click="confirmDelete"
                                      />
                                  </div>
                                </template>
                              </Dialog>


                              <!-- Static Column: No -->
                              <Column field="no" header="No"></Column>

                              <!-- Editable Columns with Default Input Fields -->
                              <Column field="rke" header="R/ke-" style="text-align: end;">
                                <template #body="slotProps">
                                  <input v-model="slotProps.data.rke" placeholder="Enter Rke" class="editable-input" style="width: 3rem;" :disabled="slotProps.data.jeniskemasanfk === 2">
                                </template>
                              </Column>

                              <Column field="jenisobat" header="Jenis Racikan" style="width: 8rem; text-align: center; ">
                              <template #body="slotProps">
                                <Dropdown
                                  v-model="slotProps.data.jenisobat"
                                  :options="d_jenisRacikan"
                                  optionLabel="jenisracikan"
                                  style="width: 8rem; text-align:left;"
                                  :placeholder="slotProps.data.jeniskemasanfk === 2 ? 'Non-Racikan' : 'Racikan'"
                                  showClear
                                  disabled
                                  :filter="true"
                                />
                              </template>
                            </Column>

                              <!-- <Column field="produkfk" header="Kode Produk" :sortable="true"></Column> -->
                              <!-- <Column field="jenisKemasan"></Column> -->
                              <Column field="namaproduk" :sortable="true" style="min-width:150px">
                                <template #header>
                                  <div style="text-align: center;width: 80%; margin-left:15px">
                                    Nama Produk
                                  </div>
                                </template>
                                <template #body="{ data }">
                                  <div style="text-align: left;">
                                    {{ data.namaproduk }}
                                  </div>
                                </template>
                              </Column>
                              <Column field="satuanstandar" header="Satuan" class="text-center"></Column>
                              <Column 
                                field="stock" 
                                header="Stok Satelit Farmasi" 
                                headerStyle="text-align: center;" 
                                bodyStyle="text-align: right;" 
                                class="text-center">
                              </Column>
                              <Column 
                                field="totalstok" 
                                header="Stok Instalasi Farmasi" 
                                headerStyle="text-align: center;" 
                                bodyStyle="text-align: right;" 
                                class="text-center">
                              </Column>
                              <Column v-if="item.JENISOBAT === 'Obat Empiris'" field="qtymax" header="QTY MAX"></Column>
                              <Column v-if="item.JENISOBAT === 'Obat Profilaksis'" field="qtymax" header="QTY MAX"></Column>
                              <!-- <Column field="kekuatan" header="Kekuatan"></Column> -->
                              <Column field="kekuatan" header="Kekuatan" style="text-align: center; width: 3rem; display: none !important" v-show="shouldShowInput" >
                                <template #body="slotProps">
                                  <input v-model="slotProps.data.kekuatan" placeholder="Enter Jumlah" class="editable-input" style="width: 3rem;" disabled/>
                                </template>
                              </Column>
                              <Column field="jumlahobat" header="Jumlah Racikan" style="text-align: center; width: 3rem;" >
                                <template #body="slotProps">
                                  <input v-model="slotProps.data.jumlahobat" placeholder="Enter Jumlah" class="editable-input" style="width: 3rem;" disabled/>
                                </template>
                              </Column>

                              <!-- <Column field="dosis" header="Dosis" style="text-align: end;">
                                <template #body="slotProps">
                                  <input v-model="slotProps.data.dosis" type="number" placeholder="Enter Dosis" class="editable-input" style="width: 3rem;" :disabled="slotProps.data.jeniskemasanfk === 2"/>
                                </template>
                              </Column> -->

                              <Column field="dosis" style="text-align: end;" header="Dosis">
                                <template #body="slotProps">
                                  <div class="p-inputgroup">
                                    <!-- Input untuk dosis -->
                                    <input v-model="slotProps.data.dosispecahan" class="p-inputtext" style="width: 5rem; display: none !important" v-if="slotProps.data.jeniskemasanfk != 2"/>
                                    <input v-model="slotProps.data.dosis" class="p-inputtext" type="number" style="width: 5rem;" v-if="slotProps.data.jeniskemasanfk != 2" placeholder="Dosis Miligram"/>
                                    <!-- Tombol untuk menjalankan getSelisih -->
                                    <VButton
                                      icon="pi pi-check"
                                      class="p-button-rounded p-button-success ml-2"
                                      v-if="slotProps.data.jeniskemasanfk !== 2" style="display: none !important"
                                      @click="getSelisih({ data: slotProps.data, field: 'dosis', newValue: slotProps.data.dosis })"
                                    />
                                  </div>
                                </template>
                              </Column>

                              <Column field="keterangan" :sortable="true" style="min-width: 80px">
                                <template #header>
                                  <div style="text-align: center;width: 100%; margin-left:15px">
                                    Catatan
                                  </div>
                                </template>
                                <template #body="slotProps">
                                  <input v-model="slotProps.data.keterangan" placeholder="Catatan" class="editable-input" style="width:8rem;"/>
                                </template>
                              </Column>

                              <Column field="aturanpakai" :sortable="true" style="min-width: 80px">
                                <template #header>
                                  <div style="text-align: center;width: 100%; margin-left:15px">
                                    Aturan Pakai
                                  </div>
                                </template>
                                <template #body="slotProps">
                                  <input v-model="slotProps.data.aturanpakai" placeholder="Aturan Pakai" class="editable-input" style="width:8rem;" :disabled="slotProps.data.jeniskemasanfk === 1" />
                                </template>
                              </Column>

                              <Column field="hargajual" :sortable="true" style="min-width: 80px">
                                <template #header>
                                  <div style="text-align: center;width: 100%; margin-left:15px">
                                    Harga Satuan
                                  </div>
                                </template>
                                <template #body="{ data }">
                                  <div style="text-align: right;">
                                    {{ H.formatRupiah(Math.ceil(data.hargajual),'Rp') }}
                                  </div>
                                </template>
                              </Column>
                              <Column field="jumlah" header="Jumlah" style="text-align: end;">
                                <template #body="slotProps">
                                  <input v-if="slotProps.data.JENISOBAT !== 'Obat Biasa'" v-model="slotProps.data.jumlah" placeholder="Calculated" type="number"
                                    @input="kondisi(slotProps, $event); hitungTotal(slotProps)" class="editable-input" style="width: 4rem; text-align:right" :disabled="slotProps.data.jeniskemasanfk === 1" />
                                  <input v-else v-model="slotProps.data.jumlah" placeholder="Calculated" type="number"
                                    @input="hitungTotal(slotProps)" class="editable-input" style="width: 4rem; text-align: right;" :disabled="slotProps.data.jeniskemasanfk === 1" />
                                </template>
                              </Column>
                              <Column field="totalharga" :sortable="true" style="min-width: 80px">
                                <template #header>
                                  <div style="text-align: center;width: 100%; margin-left:15px">
                                    Total Harga
                                  </div>
                                </template>
                                <template #body="slotProps">
                                  <div style="text-align: right;">
                                    {{ H.formatRupiah(Math.ceil(slotProps.data.totalharga),'Rp') }}
                                  </div>
                                </template>
                              </Column>
                              


                              <!-- <Column field="satuanresep" header="Satuan Resep" style="text-align: end;">
                                <template #body="slotProps">
                                  <Dropdown v-model="slotProps.data.satuanresep" :options="d_satuanResep"
                                            :optionLabel="'satuanresep'" class="editable-dropdown" placeholder="Select Satuan"
                                            style="width: 100%;" showClear :filter="true" />
                                </template>
                              </Column> -->

                              <!-- Paginator Start and End Templates -->
                              <template #paginatorstart>
                                <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                              </template>
                              <template #paginatorend>
                                <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                              </template>
                            </DataTable>

                          </div>
                        </div>

                      </div>
                    </div>
                  </form>



                  <!-- end allz -->


                </div>




                <div class="column is-12" v-if="showRacikanDose">
                  <div class="content">
                    <div class="is-divider" :data-content="infoStok" />
                  </div>
                </div>

                <div class="column is-12" v-if="showRacikanDose">
                  <DataTable :value="dataSourceStokProduk" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                    <Column field="no" header="No"></Column>
                    <Column field="namaruangan" header="ruangan"></Column>
                    <Column field="stok" header="Stok"></Column>
                  </DataTable>

                </div>

              </div>
            </div>

          </div>
        </form>
        <div class="column is-12">
          <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
            <h3 class="title is-5  mt-3-min">
              <span> Riwayat</span>
              <span class="is-pulled-right">
                <VTag color="success" :label="listSIMRSLama.length" rounded outlined />
              </span>
            </h3>
            <div class="columns is-multiline">
              <div class="column is-12" v-if="isLoadRiwayatOLD">
                <VPlaceloadText :lines="5" width="100%" last-line-width="25%" />
                <VPlaceloadText :lines="5" width="100%" class="mt-2" last-line-width="25%" />
                <VPlaceloadText :lines="5" width="100%" class="mt-2" last-line-width="25%" />
              </div>
              <div class="column is-12" v-else style="height:660px;overflow:auto">
                <div class="columns is-multiline">
                  <div class="column is-12 mb-0 mt-3-min" v-for="itemZ in listSIMRSLama" v-if="listSIMRSLama.length">
                    <TWidgetListResep :title="itemZ.namaproduk" :pegawai="itemZ.penulisResep" :subtitle="itemZ.jumlah"
                      :subtitle1="itemZ.aturanpakai" :subtitle2="H.formatDateIndoSimpleNoDay(itemZ.tglorder)"
                      :subtitle3="itemZ.simslama == true ? 'SIMRS LAMA' : ''" :color_sub_3="'danger'"
                      class="inbox-widget-3 success mb-0" />
                  </div>
                  <div class="column is-12" v-else>
                    <div class="flex-list-inner  text-center">
                      <VPlaceholderSection :title="H.assets().notFound" class="my-6">
                        <template #image>
                          <img class="light-image" :src="'/@src/assets/illustrations/placeholders/search-4-dark.svg'"
                            alt="" style="width: 100px;" />
                          <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                            style="width: 100px;" />
                        </template>
                      </VPlaceholderSection>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- </template> -->
        <template #footer>
          <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
            Tutup
          </VButton>
          <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
            @click="cekSimpan()"> Simpan
          </VButton>

          <!-- <VButton class="ml-2" type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
            @click="simpan()"> Simpan
          </VButton> -->
          <!-- <VButton icon="feather:plus" @click="add()" :loading="isLoading" color="success" raised class="ml-2">Tambah
          </VButton> -->
        </template>
        <!-- <template #action>
          <VButton icon="feather:plus" @click="add()" :loading="isLoading" color="success" raised>Tambah
          </VButton>
        </template> -->
        <!-- </VModal> -->
      </Dialog>

      <VModal :open="modaldefinitif" title="Hasil Pemeriksaan Mikrobiologi" size="large" actions="right" @close="modaldefinitif = false">
        <template #content>
          <VField>
            <VLabel>Tanggal Hasil</VLabel>
            <VControl>
            <VInput type="text" v-model="item.tanggalhasil" placeholder="Masukkan Tanggal Hasil" class="is-radiusless" />
          </VControl>
          </VField>
          <VField>
            <VLabel>Jenis Sampel</VLabel>
            <VControl>
            <VInput type="text" v-model="item.jenissampel" placeholder="Masukkan Jenis Sampel" class="is-radiusless" />
          </VControl>
          </VField>   
          <VField>
            <VLabel>Hasil Bakteri</VLabel>
            <VControl>
            <VInput type="text" v-model="item.hasilbakteri" placeholder="Masukkan Hasil Bakteri" class="is-radiusless" />
          </VControl>
          </VField>  
          <VField>
            <VLabel>Rekomendasi</VLabel>
            <VControl>
            <VInput type="text" v-model="item.rekomendasi" placeholder="Masukkan Rekomendasi" class="is-radiusless" />
          </VControl>
          </VField>       

        </template>
        <template #action>
          <VButton icon="feather:plus" @click="definitif()" color="primary" raised  :loading="isSimpan">Simpan</VButton>
        </template>
      </VModal>
    </div>

    <Dialog v-model:visible="modalDataPaketObat" modal header="Paket Obat" :style="{ width: '80vw' }" :maximizable="true" :modal="true">
      <div class="columns">
        <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VField>
                                    <VControl icon="feather:search">
                                        <input v-model="item.namapaket" v-on:keyup.enter="filterpaket()" type="text" :loading="isloadingPaketObat"
                                            class="input is-rounded" placeholder="Filter Paket..." />
                                    </VControl>
                                </VField>
                                <VButton @click="filterpaket()" :loading="isLoading" type="button" icon="feather:search"
                                class="is-fullwidth mr-3" color="info" raised>
                                Pencarian
                                </VButton>
                            </div>
                        </div>
          <DataTable :value="dataSourcePaketObat" :rows="5" :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm"
            responsiveLayout="stack" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
            v-model:expanded-rows="expandedRows" :loading="isloadingPaketObat"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
            <Column expander style="width: 5rem" />
            <Column field="no" header="No"></Column>
            <Column field="paketId" header="Nomor Paket"></Column>
            <Column field="namapaket" header="Nama Paket"></Column>
            <Column :exportable="false" header="#" style="text-align: center;">
              <template #body="slotProps">
                <VIconButton v-tooltip.bottom.left="'Pilih Paket'" label="Bottom Left" color="primary" circle
                  icon="pi pi-check-circle" @click="pilihPaketObat(slotProps.data)" style="margin-right: 15px;"
                  :loading="isloadingTambahPaket" />
              </template>
            </Column>
            <template #expansion="slotProps">
              <div class="p-3">
                <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                  <Column field="no" header="No" />
                  <Column field="namaproduk" header="Nama Produk" />
                  <Column field="satuanresep" header="Satuan" />
                  <Column field="jumlah" header="Jumlah" style="text-align: center;" />
                  <Column field="aturanpakai" header="Aturan Pakai" />
                </DataTable>
              </div>
            </template>
          </DataTable>
        </div>
      </div>
    </Dialog>


  </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onBeforeMount, onMounted, watchEffect } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
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
import Dialog from 'primevue/dialog';
import Fieldset from 'primevue/fieldset';
import TWidgetListResep from '../t-widget-list-resep.vue'
import DataTable from 'primevue/datatable'
import InputText from 'primevue/inputtext';
import Column from 'primevue/column'

useHead({
  title: 'Order Resep - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const route = await useRoute()
// let ID_PASIEN = route.query.nocmfk as string
// let NOREC_PD = route.query.norec_pasien_daftar as string
let NOREC_APD = route.query.norec_apd as string
// const props = withDefaults(
//     defineProps<{
//       pasien?: any
//       registrasi?: any
//       FORM_NAME?: string
//       FORM_URL?: string
//       COLLECTION?: string
//       input?: any
//       item?: any
//       kelompokUser?: string
//     }>(),
//     {
//       pasien: {},
//       registrasi: {},
//       FORM_NAME: '',
//       FORM_URL: '',
//       COLLECTION: '',
//       input: {},
//       item: {},
//       kelompokUser: ''
//     }
//   )
const props: any = defineProps({

  registrasi: {
    type: Object as PropType<any>,
  },
  pasien: {
    type: Object as PropType<any>,
  },
  NOREC_PD: {
    type: Object as PropType<any>,
  },
  ID_PASIEN: {
    type: Object as PropType<any>,
  },
  noregis: {
    type: Object as PropType<any>,
  },
  selected: undefined,
  type: undefined,
  align: undefined,
  hilangkanStuck: false
})
let NOREC_PD =props.NOREC_PD
let ID_PASIEN =props.ID_PASIEN
useViewWrapper().setFullWidth(props.pasien ? true : false)
const isLoadingPasien: any = ref(false)
let item: any = reactive({
  NOREC_PD: props.NOREC_PD != undefined ? props.NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  produkCeklis: [],
  jeniskemasan : 1,
  jenisobat: 'Racikan',
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
  jumlah: 1,
  persenDiskon: 0,
  // aturanPakai: [],
  hargadiskon: 0,
  // jumlahobat: 1,
  dosis: 1,
  tglAwal: new Date(),
  rke: 1,
  kekuatan: 1,
  resep: '-',
  chkp: 0,
  chks: 0,
  chksr: 0,
  chkm: 0,
})
if(props.NOREC_PD == undefined) {
  props.NOREC_PD = props.registrasi.norec_pd
}
const tabs: any = ref([
    { label: 'Order', value: 1, icon: 'lnir lnir-medicine-alt' },
    { label: 'Riwayat', value: 2, icon: 'fas fa-list' },
    { label: 'Riwayat Resep Verif', value: 3, icon: 'fas fa-list' },
    { label: 'Retur Obat', value: 4, icon: 'lucide:skip-back' },
  ])
// const loginUser = useUserSession().getUser()
// if(loginUser.kelompokUser?.kelompokUser == 'it') {
//   tabs.value.push({ label: 'Retur Obat', value: 4, icon: 'lucide:skip-back' });
// }
const isloadingCopy: any = ref(false)
const isloadingPaketObat: any = ref(false)
const isloadingTambahPaket: any = ref(false)
const modalInput: any = ref(false)
const modaldefinitif: any = ref(false)
const userLogin: any = H.pegawaiLogin().id;
const alergi_RO: any = ref(null) // Riwayat Alergi reorder
const modalConfirm: any = ref(false)
const isAstorBM: any = ref(false)
const disabledRuangan: any = ref(false)
const disabledJenis: any = ref(false)
const shouldShowInput = ref(false);
const listChecked: any = ref([])
const dataSourceStokProduk: any = ref([])
const selected_count = ref(0);
const qtyHistory = ref(0);
const editRacikan = ref(false);
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
const isMerge: any = ref(false)
const isLoadRiwayatOLD: any = ref(false)
const isLoadBtnEdit: any = ref(false)
const listSIMRSLama: any = ref([])
const d_Produk: any = ref([])
const showModal = ref(false);
const selectedProduct: any = ref();
const showRiwayatDialog = ref(false);
const riwayatList = ref([]);
const dataProductTampil: any = ref([]);
const listDataRetur: any = ref([]);

// const d_ProdukDef: any = ref([])
const filterLayanan: any = ref('')
// const listChecked1: any = ref([])
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
const showRacikanDoseFalse = ref(false)
const isLoadingTT = ref(false)
const d_satuanResep = ref([])
const d_produk: any = ref([])
const m_historippra: any = ref([])
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
const infoStok: any = ref('List Informasi Stok')
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
const isNORM: any = ref(false)
const isLoadingRiwayat = ref(false)
const isLastObatByDate = ref(false)
const d_JenisOperasi = ref([])
const d_JenisKsm = ref([])
const d_JenisTindakan = ref([])
const JenisOperasi = ref([])
const expandedRows = ref()
const dataSourcePaketObat = ref([])
const metaKey = ref(false);
const modalDataPaketObat: any = ref(false)
const isDialogVisible = ref(false);
const itemToDelete = ref(null);
const selectedRetur = ref();
const emit = defineEmits<{
  (e: 'update:selected', value: string): void,
  (e: 'berhasilSimpan', value: bool): void,
}>()

const item1: any = ref({
  // JENISOBAT: "Obat Profilaksis",
  // jenisoperasi: null as JenisOperasi | null,
  // devisi: null as string | null,
  // antibiotik: null as string | null,
});

const tempProdukCeklis = ref({})
const changeJenis = (prop: any) => {
  console.log(item.jenisKemasan)
  if (prop) {
    // Check if prop equals 2, to disable or enable the jenisKemasan control
    if (prop == 2) {
      disabledJenis.value = true;
      editRacikan.value = false;
      item.rke = i + 1
      item.jumlahobat = 1;
      item.aturanpakai = '';
      console.log('aku rke', item.rke);
    } else {
      disabledJenis.value = false;
      editRacikan.value = true;
    }


    // Check if prop equals 1, to trigger the API call
    if (prop == 1) {
      // Perform API call if jenisKemasan is set to 1
      useApi().get('/farmasi/get-produkdetail?produkfk=' + data.produkfk + '&ruanganfk=' + item.ruangan.id +
        "&kpid=" + item.registrasi.objectkelompokpasienlastfk +
        "&norec_apd=" + item.NOREC_APD).then(function (response: any) {
          if (response.detail.length > 0) {
            dataProdukDetail.value = response.detail;
            item.stok = response.jmlstok / item.nilaiKonversi;
            if (response.kekuatan == undefined || response.kekuatan == 0) {
              response.kekuatan = 1;
            }
            item.kekuatan = response.kekuatan;
            item.sediaan = response.sediaan;
            item.tglKadaluarsa = response.detail[0];
            isLoading.value = false;
          }
        });
    }
  }
};

const showBPL = computed(() => item.DEPARTEMEN_FK !== 18);
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
if (ID_PASIEN == undefined) {
  ID_PASIEN = props.registrasi.nocmfk
}

async function loadalergi(){

  const response_TPI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + item.NOREC_PD + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + `&field=riwayatalergi`)
  item.riwayatalergi = response_TPI?.riwayatalergi ?? 'Tidak ada Riwayat Alergi';
  console.log('ini riwayat',item.riwayatalergi);
}


function openRiwayatPopup() {
  console.log("Button clicked, opening modal...");
  showRiwayatPopup.value = true;
}
const openRiwayatDialog = () => {
  showRiwayatDialog.value = true;
  reseprutin();
};
async function reseprutin(isVerify: true) {
  isLoading.value = true;
  riwayatList.value = []
  let params = `&norec_pd=${item.NOREC_PD}`
  if (isNORM.value) {
    params = ``
  }
  let penulis = ''
  if (item.pegawaiOrder) {
    penulis = `&penulisresepfk=${item.pegawaiOrder.id}`
  }
  await useApi().get(
    `/farmasi/riwayat-resep-rutin?nocmfk=${ID_PASIEN}&verif=${isVerify}${params}${penulis}`
  ).then((response: any) => {
    let z = 0
    response.forEach((element: any) => {
      element.isExpand = true
      element.icon = 'fa-inverse lnir lnir-medicine-alt'
      element.color = listColor2.value[z]
      element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
      if (z > 4) {
        z = 0
      }
      z++
    })
    riwayatList.value = response
    isLoading.value = false;
  })
}
async function loadRiwayat(isVerify: any) {
  listRiwayat.value = []
  console.log(props.registrasi.norec_pd)
  let params = `&norec_pd=${props.registrasi.norec_pd}`
  if (isNORM.value == true) {
    params = ``
  }
  let penulis = ''
  if (item.pegawaiOrder) {
    penulis = `&penulisresepfk=${item.pegawaiOrder.id}`
  }
  isLoadingRiwayat.value = true
  isLoading.value = true
  await useApi().get(
    `/farmasi/riwayat-order-resep?nocmfk=${ID_PASIEN}&verif=${isVerify}${params}${penulis}`).then((response: any) => {
      isLoadingRiwayat.value = false
      isLoading.value = false
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

async function loadRiwayatVerif(isVerify: any) {
  listRiwayat.value = []
  let params = `&norec_pd=${props.registrasi.norec_pd}`
  if (isNORM.value == true) {
    params = ``
  }
  let penulis = ''
  if (item.pegawaiOrder) {
    penulis = `&penulisresepfk=${item.pegawaiOrder.id}`
  }
  isLoadingRiwayat.value = true
  isLoading.value = true
  await useApi().get(
    `/farmasi/riwayat-resep-verif?nocmfk=${ID_PASIEN}&verif=${isVerify}${params}${penulis}`).then((response: any) => {
      isLoadingRiwayat.value = false
      isLoading.value = false
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

const getProdukListStok = async (e: any) => {

  let namaproduk
  await useApi().get(`farmasi/get-stok-produk-by-ruangan?produkfk=${e}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      namaproduk = element.namaproduk
    });
    dataSourceStokProduk.value = response
    infoStok.value = `List Stok Produk ${namaproduk} Per Ruangan`
  })

}

function pasienByID(id: any) {
  if (props.pasien != undefined) {
    console.log(item)
    console.log(props)
    pasien.value = props.pasien
    item.NOREC_APD = props.registrasi.norec_apd
    item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
    item.DEPARTEMEN_FK = props.registrasi.objectdepartemenfk
    item.registrasi = props.registrasi
  } else {
    isLoadingPasien.value = true
    useApi().get(`/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.registrasi = response.last_registrasi
      isLoadingPasien.value = false
      // fetchTindakan(item.RUANGAN_LAST)
    })
  }

}

watch(d_Dokter, (newVal) => {
  if (newVal.length && !item.pegawaiOrder) {
    item.pegawaiOrder = newVal.find(
      (dokter) => dokter.id === userLogin
    ) || newVal[0]; // Atur pegawaiOrder sesuai dengan kondisi
  }
});

// Panggil loadDrop saat komponen dipasang
onMounted(() => {
  pasienByID(ID_PASIEN)
  loadDrop()
  loadResepHariIni(item.NOREC_PD)
});

async function loadDrop() {
  // const userLogin = useUserSession().getUser();

  // console.log('titw2siw', userLogin)

  const response = await useApi().get(
    `/farmasi/input-resep-cbo?ruanganfk=${item.RUANGAN_LAST}&departemenfk=${item.DEPARTEMEN_FK}`
  )
  d_aturanPakai.value = response.signa.map((e: any) => {
    return { aturanpakai: e.signa, id: e.id }
  })
  d_kemasan.value = response.jeniskemasan
  // d_Ruangan.value = response.ruanganFarmasi
  d_Dokter.value = response.penulisresep
  d_Ruangan.value = response.ruangan
  console.log('D_RUANGAN', d_Ruangan.value)
  d_jenisRacikan.value = response.jenisracikan //.map((e: any) => { return { label: e.jenisracikan, value: e } })
  console.log('kiw', d_jenisRacikan);


  d_route.value = response.route //.map((e: any) => { return { label: e.name, value: e } })
  d_satuanResep.value = response.satuanresep //.map((e: any) => { return { label: e.satuanresep, value: e } })
  d_asalProduk.value = response.asalproduk //.map((e: any) => { return { label: e.asalproduk, value: e } })
  item.jenisKemasan = response.jeniskemasan[1]
  item.tarifadminresep = response.tarifadminresep ? response.tarifadminresep : 0
  item.tarifWNI = response.tarifWNI ? response.tarifWNI : 0
    item.tarifKitas = response.tarifKitas ? response.tarifKitas : 0
    item.tarifNonKitas = response.tarifNonKitas ? response.tarifNonKitas : 0
    item.ruangan = d_Ruangan.value[0]
    console.log('Ruangan', item.ruangan)

  
  // d_jasaNonRacikan.value = response.jasaNonRacikan[0]
  // d_jasaRacikan.value = response.jasaRacikan[0]
  // d_jasaRacikanLebih.value = response.jasaRacikanLebih[0]
  // // item.pegawaiOrder = userLogin;
  // item.pegawaiOrder = userLogin

  // item.pegawaiOrder = doctor || { id: userLogin.id };
  disabledRuangan.value = false

  console.log('PEGAWAI', item.registrasi.objectpegawaifk)

  response.penulisresep.forEach((element) => {
    // console.log('Checking prescriber:', element);
    if (item.registrasi.objectpegawaifk == element.id) {
      item.pegawaiOrder = element;
      // console.log('Matched prescriber:', element); // Log the matched prescriber
      return;
    }
  })
}

function changeProduk(e: any) {
  chack(ID_PASIEN, e.id)
  if (e != null && e != undefined) {
    GETKONVERSI()
    getProdukListStok(e.id)
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

const chack = async (nocmfk, produkid) => {
  await useApi().get(`/farmasi/check-obat-periode?produkfk=${produkid}&nocmfk=${nocmfk}`).then((response) => {
    if (response != null) {
      isLastObatByDate.value = true
      item.namaproduk = response.namaproduk
      item.tglpelayanan = response.tglpelayanan
    }
  })

}

function simpan() {
  // let validator = data2.value.filter((x) => typeof x.aturanpakai == undefined);
  let valid = [
    "aturanpakai",
    "dosis",
    "jumlahxmakan"
  ];

  // console.log('TEST DATA', data2.value)
  for (let iv = 0; iv < data2.value.length; iv++) {
    const dt = data2.value[iv];
    if (dt.aturanpakai == undefined || dt.aturanpakai == "") {
      H.alert('error', `Aturan Pakai pada ${dt.namaproduk} belum terisi`)
      return
    }
    if (dt.dosis == undefined || dt.dosis == "") {
      H.alert('error', `Dosis pada ${dt.namaproduk} belum terisi`)
      return
    }

    if (dt.jumlah == undefined || dt.jumlah == "") {
      H.alert('error', `Jumlah pada ${dt.namaproduk} belum terisi`)
      return
    }

    if(isAstorBM.value === true)
    {
        if(dt.qtymax < dt.jumlah)
        {
          H.alert('error', `Qty Amprah Produk ${dt.namaproduk} Melebihi Batas Maksimal`)
          return
        }
    }
  }

  if(item.JENISOBAT == 'Obat Profilaksis' && !item.jenisoperasi)
  {
    useToaster().error(`Jenis Operasi Belum Dipilih !`);
    return;
  }

  else if(item.JENISOBAT == 'Obat Empiris' && !item.jenistindakan)
  {
    useToaster().error(`Tindakan Belum Dipilih !`);
    return;
  }

  if (!item.pegawaiOrder) {
    H.alert('error', 'Pilih Penulis Resep dulu')
    return
  }

  if (!item.riwayatalergi) {
    H.alert('error', 'Riwayat Alergi Kosong!')
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
    tglResepHari = H.formatDate(someDate.setDate(someDate.getDate() + numberOfDaysToAdd), 'YYYY-MM-DD HH:mm:ss');

    var strukorder = {
      'norec': item.NOREC_SO ? item.NOREC_SO : '',
      // 'tglresep': tglResepHari,
      'tglresep': H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
      'penulisresepfk': item.pegawaiOrder.id,
      'ruanganfk': item.ruangan.id,
      'noregistrasifk': item.NOREC_APD,
      'nopasiendaftarfk': props.registrasi.norec_pd,
      'qtyproduk': data2.value.length,
      'cito' : item.cito ? true : false,
      'isrutin' : item.isrutin ? true : false,
      'isbpl' : item.isbpl ? true : false,
      'alergiobat' : item.riwayatalergi,
      'jenisoperasi' : item.jenisoperasi ? item.jenisoperasi.id : null,
      'jenisobat' : item.JENISOBAT ? item.JENISOBAT : null,
      'ppratindakan' : item.jenistindakan ? item.jenistindakan.id : null,
      'tglhasil' : item.tanggalhasil ? item.tanggalhasil : null,
      'jenissampel' : item.jenissampel ? item.jenissampel : null,
      'hasil' : item.hasilbakteri ? item.hasilbakteri : null,
      'rekomendasi' : item.rekomendasi ? item.rekomendasi : null,
      'noruangan': null,
      'isreseppulang': item.isreseppulang ? item.isreseppulang : null,
      'flag' : isAstorBM.value === true ? true : false,  
      'isantibiotik' : isAstorBM.value === true ? true : false  
    }
    var objSave = {
      'strukorder': strukorder,
      'orderpelayanan': data2.value,
      'noregistrasi': item.registrasi.noregistrasi,
    }

    console.log('op', data2.value)
    objSaves.push(objSave);
  }

  isLoading.value = true
  useApi().post(`/farmasi/simpan-order`, { 'data': objSaves }).then(async (response: any) => {
      await sendNotification(response);
      item.JENISOBAT = 'Obat Biasa'
      if(isAstorBM.value == true){
        getHistoriPpra()
        isAstorBM.value = false
      }
      delete item.NOREC_SO
      modalInput.value = false
      clearInput()
      clearTable()
      emit('berhasilSimpan', false)
    }).catch((e: any) => {
      console.log(e)
    }).finally(() => {
      isLoading.value = false;
      alergi_RO.value = null;
    });
}

async function sendNotification(e) {

    let ruanganAsal = item.registrasi.namaruangan
    let ruanganTujuan = ''
    let namapengorder = item.pegawaiOrder.namalengkap
    for (let x = 0; x < d_Ruangan.value.length; x++) {
      const element = d_Ruangan.value[x];
      console.log(element)

      if(Number(element.id) == Number(e.data.objectruangantujuanfk)){
        ruanganTujuan=element.namaruangan
        console.log(ruanganTujuan)
      }

    }

    let body = {
        norec: e.data.norec,
        judul: 'Order Resep #' + e.data.noorder,
        jenis: e.data.keteranganorder,
        pesanNotifikasi: `Permohonan dari ${ruanganAsal} ke ${ruanganTujuan}`,
        idRuanganAsal: e.data.objectruanganfk,
        idRuanganTujuan: e.data.objectruangantujuanfk,
        ruanganAsal: ruanganAsal,
        ruanganTujuan: e.data.objectruangantujuanfk,
        kelompokUser: null,
        idKelompokUser: null,
        idPegawai: item.pegawaiOrder.id,//H.pegawaiLogin().id,
        namapegawai: namapengorder,//H.pegawaiLogin().id,
        dataArray: [],
        urlForm: 'module-dashboard-apotik',
        params: null,
        group: 'mapping_login',
        namaFungsiFrontEnd: null,
        tgl: e.data.tglorder,
        tgl_string: H.formatDateIndoSimple(e.data.tglorder),
    }
    // console.log('ruanganTujuan:', body.ruanganTujuan);
   await H.sendSocket("sendNotification", body);
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
  // console.log('ehem', userLogin.pegawai.jenisPegawai)
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
async function jenisoperasi(filter: any) {
  let query = ''
  if (filter) {
    query = filter.query
  }
    const response = await useApi().get(`general/jenis-operasi?name=${query}&limit=10`);
  d_JenisOperasi.value = response.data
}
async function jenisksm(filter: any) {
  let query = ''
  if (filter) {
    query = filter.query
  }
    const response = await useApi().get(`general/jenis-ksm?name=${query}&limit=10`);
  d_JenisKsm.value = response.data
}
async function jenistindakan(filter: any) {
   let query = ''
  //  let ksm = '';
   let ksm = item.jeniksm.id
   if (filter) {
     query = filter.query
   }
    const response = await useApi().get(`general/jenis-tindakan?name=${query}&ksm=${ksm}`);
  d_JenisTindakan.value = response.data
}

const fetchTindakan1 = (e: any) => {
  isLoading.value = true
  var filterSerch = ""
  var filterSerch2 = ""
  console.log('nas',item.namaprodukserch)
  if(item.namaprodukserch == undefined)
  {
      filterSerch = ""
  }
  else
  {
      filterSerch = item.namaprodukserch
  }
  
  useApi()
    .get(
      `farmasi/get-produkdetail-antibiotik?idtindakan=${item.jenistindakan.id}&ruanganfk=${item.ruangan.id}&limit=10&namaproduk=${filterSerch}&kpid=${item.registrasi.objectkelompokpasienlastfk}&nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}`
    )
    .then((response: any) => {
      isLoading.value = false;
      // item.jeniksm.value = response.data;
      item.jenistindakan.value = response.data;
      d_ProdukDef.value = response
      d_Produk.value = response
    })
    .catch(() => {
      isLoading.value = false;
    });
};

const fetchTindakan2 = (e: any) => {
  isLoading.value = true
  var filterSerch = ""
  var filterSerch2 = ""
  
  // let isInacbgs = item.isinacbgs ? "true" : "false";

  if(item.namaprodukserch == undefined)
  {
      filterSerch = ""
  }
  else
  {
      filterSerch = item.namaprodukserch
  }

  useApi()
    .get(
      `farmasi/get-produkdetail-antibiotik-profilaksis?idjenisoperasi=${item.jenisoperasi.id}&ruanganfk=${item.ruangan.id}&limit=10&namaproduk=${filterSerch}&kpid=${item.registrasi.objectkelompokpasienlastfk}&nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}`
    )
    .then((response: any) => {
      isLoading.value = false;
      d_ProdukDef.value = response
      d_Produk.value = response
      // item.jeniksm.value = response.data;
      // item.jenisoperasi.value = response.data;
      console.log('valeu produk',d_Produk)
      console.log('valeu produk',d_ProdukDef)
    })
    .catch(() => {
      isLoading.value = false;
    });
  countTotal();
  
};

const qtymax = ref<number | null>(null);

function kondisi(data:any, event: any) {
   console.log('Data yang diedit:', data);
   console.log('EVENT yang diedit:', event);
  console.log('Nilai qtymax:', data.data.qtymax);
  qtymax.value = parseInt(data.data.qtymax); 
  if (qtymax.value !== null && parseInt(data.data.jumlah) > qtymax.value) {
    H.alert('warning', 'QTY Amprah melebihi Batas')
    // return;
  }
    // dataSource.value[data.index] = qtymax.value; 
  // }
}

function hitungTotal(data:any, event:any){
  data.data.totalharga = (data.data.jumlah || 0) * (data.data.hargajual || 0);
}

const kelompokUseruntukit = useUserSession().getUser().kelompokUser.kelompokUser


async function fetchDetails(id: number) {
  const response = await useApi().get(`general/jenis-operasi-details?id=${id}`);
  console.log(response);
  if (response) {
    item1.value.devisi = response.divisi;
    item1.value.antibiotik = response.antibiotik;
  }
}

async function addItems() {
  if(props.registrasi.norec_pd == undefined) {
    await H.statusClosingPasien(item.NOREC_PD);
  }else {
    await H.statusClosingPasien(props.registrasi.norec_pd);
  }
  if (!item.ruangan) {
    useToaster().error('Ruangan harus di pilih')
    return
  }

  clearInput()
  item.JENISOBAT = 'Obat Biasa'
  isAstorBM.value = false
  modalInput.value = true
  filterProdukna()
  if (alergi_RO.value) {
    item.riwayatalergi = alergi_RO.value;
  } else {
    loadalergi()
  }
  alergi_RO.value = null;

  // confirm.require({
  //   message: 'Apakah anda meresepkan Antimikroba Injeksi ?',
  //   header: 'Informasi',
  //   icon: 'pi pi-info-circle',
  //   acceptClass: 'p-button-danger',
    
  //   // accept: () => {
  //   //   if(kelompokUseruntukit == 'it')
  //   //    {
  //   //     modalConfirm.value = true
  //   //     isAstorBM.value = true
  //   //     // filterProdukna()
  //   //     d_Produk.value = [];
  //   //     loadalergi()
  //   //    }
  //   //   else{
  //   //     clearInput()
  //   //     item.JENISOBAT = 'Obat Biasa'
  //   //     isAstorBM.value = false
  //   //     modalInput.value = true
  //   //     filterProdukna()
  //   //     loadalergi()
  //   //   }
  //   // },
  //   // reject: () => {
  //   //   clearInput()
  //   //   item.JENISOBAT = 'Obat Biasa'
  //   //   isAstorBM.value = false
  //   //   modalInput.value = true
  //   //   filterProdukna()
  //   //   loadalergi()
  //   // },

  //   accept: () => {
  //     modalConfirm.value = true
  //     isAstorBM.value = true
  //     // filterProdukna()
  //     d_Produk.value = [];
  //     if (alergi_RO.value) {
  //       item.riwayatalergi = alergi_RO.value;
  //     } else {
  //       loadalergi()
  //     }
  //     alergi_RO.value = null;
  //   },
    
  //   reject: () => {
  //     clearInput()
  //     item.JENISOBAT = 'Obat Biasa'
  //     isAstorBM.value = false
  //     modalInput.value = true
  //     filterProdukna()
  //     if (alergi_RO.value) {
  //       item.riwayatalergi = alergi_RO.value;
  //     } else {
  //       loadalergi()
  //     }
  //     alergi_RO.value = null;
  //   },
  // })
}


function profilaksis() {
  confirm.require({
    message: 'Profilaksis',
    header: 'Informasi',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      clearInput()
      item.JENISOBAT = 'Obat Profilaksis'
      modalInput.value = true
      modalConfirm.value = false
      isAstorBM.value = true 
    }
  })
}

function empiris() {
  confirm.require({
    message: 'Apakah Anda Ingin Memilih Antibiotik Kategori Reserve?',
    header: 'Informasi',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      clearInput()
      item.JENISOBAT = 'Obat Empiris Reserve'
      filterProdukna()
      modalInput.value = true
      modalConfirm.value = false
      isAstorBM.value = true
    },
    reject: () => {
      clearInput()
      item.JENISOBAT = 'Obat Empiris'
      modalInput.value = true
      modalConfirm.value = false
      isAstorBM.value = true
    }
  })
}

function modalinputdefinitif()
{
  modaldefinitif.value = true;
  modalConfirm.value = false
}

function definitif() {
  if (!item.tanggalhasil || !item.jenissampel || !item.hasilbakteri || !item.rekomendasi) {
    H.alert('error', 'Silahkan Lengkapi Data')
    return
  }
  // confirm.require({
  //   message: 'Definitif',
  //   header: 'Informasi',
  //   icon: 'pi pi-info-circle',
  //   acceptClass: 'p-button-danger',
  //   accept: () => {
      delete item.jenistindakan
      delete item.jenisoperasi
      item.JENISOBAT = 'Obat Definitif'
      item.antibiotik = 'd'
      filterProdukna()
      modalInput.value = true
      modaldefinitif.value = false
      // modalConfirm.value = false
      isAstorBM.value = true
    }
//   })
// }

const loadDataSIMRSLama = async () => {
  let lokal = false;
  if (window.location.host.indexOf('192.168') > -1) {
    lokal = true;
  }

  isLoadRiwayatOLD.value = true
  listSIMRSLama.value = []
  let riwayat1 = []
  let responseXX = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${item.NOREC_PD}`)


  for (let x = 0; x < responseXX.length; x++) {
    const element = responseXX[x];
    for (let y = 0; y < element.details.length; y++) {
      const element2 = element.details[y];
      riwayat1.push({
        'namaproduk': element2.namaproduk,
        'jumlah': element2.jumlah,
        'penulisResep': element.namalengkap,
        'aturanpakai': element2.aturanpakai,
        'tglorder': element.tglorder,
        'simslama': false,
      })
    }
  }

  let responseX = await useApi().get(`/emr/history-sim-lama?prefix=order_resep&nocm=${pasien.value.nocm}&local=${lokal}`)

  for (let x = 0; x < responseX.length; x++) {
    const element = responseX[x];
    riwayat1.push({
      'namaproduk': element.NamaBarang,
      'penulisResep': element.dokter,
      'jumlah': element.Jml,
      'aturanpakai': element.AturanPakai,
      'tglorder': element.TglOrder,
      'simslama': true,
    })
  }
  listSIMRSLama.value = riwayat1
  listSIMRSLama.value.sort((a, b) => {
    // Convert date strings to Date objects for proper comparison
    const dateA = new Date(a.tglorder);
    const dateB = new Date(b.tglorder);

    // Compare dates
    return dateB - dateA;
  });

  isLoadRiwayatOLD.value = false
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
    '/farmasi/hapus-order', { 'norec': e.norec_so, 'antibiotik': e.isantibiotik  }).then(function (response: any) {
      isSimpan.value = false
      let verif = e.statuspengerjaan != "Menunggu" ? true : false
      loadRiwayat(verif)
    })
    getHistoriPpra()
}
function editRiwayat(e: any) {


  modalInput.value = true
  if (e.statuspengerjaan != "Menunggu") {
    H.alert('error', 'Tidak bisa di edit sudah di verifikasi')
    return
  }
  item.NOREC_SO = e.norec_so
  item.resep = e.noorder
  item.cito = e.cito
  item.isbpl = e.isbpl
  item.isrutin = e.isrutin
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
    if (element.kekuatan == null) {
      element.kekuatan = 1;
    }
  }
  data2.value = e.details
  for (var i = data2.value.length - 1; i >= 0; i--) {
    const element = data2.value[i]


    let jenisobat
    d_jenisRacikan.value.forEach(x => {
        if (x.id === element.jenisobatfk) {
            jenisobat = x;
        }
    });
    element.jenisobat = jenisobat
    // console.log('eleh', element.jenisobat);

    element.noregistrasifk = e.norec_apd
    element.kelasfk = item.header.klsid
    var dosisNA = 1;
    if (element.jeniskemasan == 'Racikan') {
      dosisNA = element.dosis
      element.jumlahobat = element.racikan
    } else {
      element.jumlahobat = element.jumlah
    }
    element.stock = element.jmlstok
    element.total = (parseFloat(element.jumlah) * (parseFloat(element.hargasatuan) - parseFloat(element.hargadiscount)))
    element.jmldosis = String(element.jumlahobat) + '/' + String(dosisNA) + '/' + String(element.kekuatan)
    element.jasa = tarifJasa.value

    console.log('rutin', element.isrutin);


  }
  setColor()
  dataSource.value = data2.value

  countTotal()
  // modalInput.value = true
  activeValue.value = 1
}

const editItems = async (e: any) => {
  // if (isLoading.value == false)
  //     return
  // console.log(e)
  isLoadBtnEdit.value = false
  // await fetchProduk({ query: e.productname })
  // d_produk.value.forEach((element: any) => {
  //   if (element.id == e.produkfk) {
  //     item.produk = element
  //   }
  // });
  modalInput.value = true
  // item.no = e.no
  item.rke = e.rke
  // item.cito = e.cito
  // item.isbpl = e.isbpl
  // item.isrutin = e.isrutin
  item.jenisKemasan = { id: e.jeniskemasanfk, jeniskemasan: e.jeniskemasan }
  d_satuanResep.value.forEach((element: any) => {
    if (e.satuanresepfk == element.id) {
      item.satuanresep = element
    }
  })
  // item.satuanresep = { id: e.satuanresepfk, satuanresep: e.satuanresep }

  // d_jenisRacikan.value.forEach((element: any) => {
  //   if (e.jenisRacikan == element.id) {
  //     item.jenisRacikan = element
  //   }
  // })



  d_aturanPakai.value.forEach((x: any) => {
    if (x.aturanpakai == e.aturanpakai) {
      item.aturanPakai = x
      return
    }
  });
  item.jenisRacikan = { id: e.jenisobatfk, jenisracikan: e.jenisobat.jenisracikan }
  console.log('aku', e.racikan);
  console.log('lagi', e.jumlahobat);


  console.log('aku edit', e.jenisobat.jenisracikan)
  item.aturanpakai = e.aturanpakai
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
  item.jenisRacikan = { id: e.jenisobatfk, jenisracikan: e.jenisobat.jenisracikan }
  item.route = { id: e.routefk, name: e.route }
  item.jumlah = e.jumlah
  item.jumlahobat = e.jumlahobat


  console.log('bpl', item.isbpl);




  if (e.iskronis == true) {
    item.checkisKronis = true
  } else {
    item.checkisKronis = false
  }
  if (e.asalprodukfk) {
    item.asal = { id: e.asalprodukfk, asalproduk: e.asalproduk }
  }

  tarifJasa.value = e.jasa
  console.log('edit data', e);
  dataSelected.value = e
  // GETKONVERSI()
  isLoadBtnEdit.value = false


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
  LenyapkanObat()
  // clearInput()
}

const showConfirmDialog = (item) => {
  itemToDelete.value = item;
  isDialogVisible.value = true;
};

const confirmDelete = () => {
  if (itemToDelete.value) {
    hapusItems(itemToDelete.value);
  }
  isDialogVisible.value = false;  // Menyembunyikan dialog setelah konfirmasi
};

const cancelDelete = () => {
  isDialogVisible.value = false;  // Menyembunyikan dialog jika dibatalkan
};


function reOrderrutin(data: any) {
  // modalInput.value = true
delete item.NOREC_SO
item.JENISOBAT = 'Obat Biasa'
let CariRuangan = d_Ruangan.value.find(x => x.id = data.objectruangantujuanfk)
// let CariDokter = d_Dokter.value.find(x => x.id = data.objectpegawaiorderfk)

// console.log('doc', CariDokter)
item.rke = 0
item.ruangan = CariRuangan
// item.pegawaiOrder = CariDokter
// item.jenisRacikan = Racikan
item.tglorder.start = new Date()
item.tglorder.end = new Date()
item.isreseppulang = data.isreseppulang === true ? true : false

data2.value = []
for (let i = 0; i < data.details.length; i++) {
  const e = data.details[i];
  if (e.objectsubkategoryfk === 8) {
    continue
  }

  // let jenisobat = d_jenisRacikan.value.find(x => x.id = e.jenisobatfk)
  let jenisobat
    d_jenisRacikan.value.forEach(x => {
        if (x.id === e.jenisobatfk) {
            jenisobat = x;
        }
    });

    item.jenisobat = jenisobat
  let kekuatan = e.kekuatan ?? 1;
  let hrg1 = Math.round(parseFloat(e.hargasatuan) * parseFloat(e.hasilkonversi))
  let hrgsdk = parseFloat(e.hargadiscount) * parseFloat(e.hasilkonversi)
  let total = (parseFloat(e.jumlah) * (hrg1 - hrgsdk)) + parseFloat(tarifJasa.value)
  // let jumlahobat = (parseFloat(e.jumlah) / parseFloat(e.dosis)) * parseFloat(kekuatan)
  let jumlahobat = 0;
    if (e.jeniskemasanfk === 2) {
      jumlahobat = 1;
    } else if (e.jeniskemasanfk === 1) {
      jumlahobat = (parseFloat(kekuatan) / parseFloat(e.dosis)) * parseFloat(e.jumlah);
    }

// console.log('obat', jumlahobat)
// console.log('jumlah', e.jumlah)
// console.log('dosis', e.dosis)
// console.log('kekuatan', e.kekuatan)
  var dataItem = {
    no: i + 1,
    noregistrasifk: item.NOREC_APD,
    generik: null,
    hargajual: String(e.hargasatuan),
    // jenisobat: jenisobat,
    jenisobat: item.jenisobat,
    jenisobatfk: item.jenisobat.id,
    kekuatan: kekuatan,
    kelasfk: item.registrasi.objectkelasfk,
    stock: String(e.jmlstok),
    harganetto: null,
    nostrukterimafk: e.nostrukterimafk,
    norec_spd: null,
    ruanganfk: item.ruangan.id,
    rke: e.rke,
    jeniskemasanfk: e.jeniskemasanfk,
    jeniskemasan: e.jeniskemasan,
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
    jumlahobat: jumlahobat,//item.jumlah,
    dosis: e.dosis,
    hargasatuan: String(e.hargasatuan),
    hargadiscount: String(e.hargadiscount),
    persendiscount: 0,
    total: total,
    jmldosis: String(e.jumlah) + '/' + String(e.dosis) + '/' + String(kekuatan),
    jasa: tarifJasa.value,
    keterangan: e.keteranganpakai,
    satuanresepfk: e.satuanresepfk,
    satuanresep: e.satuanresep,
    tglkadaluarsa: e.tglkadaluarsa,
    totalstok: e.totalstok,
  }
  data2.value.push(dataItem)

  console.log('tarikan data', dataItem)
  setColor()
  dataSource.value = data2.value
  if (e.jeniskemasanfk != 1) {
    item.rke = parseFloat(item.rke) + 1
  }
}
showRiwayatDialog.value = false;
countTotal()
clearInput()
}


function reOrder(data: any) {

  item.JENISOBAT = 'Obat Biasa'
  delete item.NOREC_SO
  // let CariRuangan = d_Ruangan.value.find(x => x.id = item.ruangan)
  // let CariDokter = d_Dokter.value.find(x => x.id = data.objectpegawaiorderfk)
  item.rke = 0
  item.ruangan = item.ruangan
  console.log('ruangannn',item.ruangan)
  // item.pegawaiOrder = CariDokter
  item.tglorder.start = new Date()
  item.tglorder.end = new Date()
  item.isreseppulang = data.isreseppulang === true ? true : false
  item.isrutin = data.isrutin
  item.isbpl = data.isbpl
  item.cito = data.cito
  alergi_RO.value = data.alergiobat
  item.riwayatalergi = alergi_RO.value;

  data2.value = []
  for (let i = 0; i < data.details.length; i++) {
    const e = data.details[i];
    if (e.objectsubkategoryfk === 8) {
    continue
    }
    // let jenisobat = d_jenisRacikan.value.find(x => x.id = e.jenisobatfk)
    let jenisobat
    d_jenisRacikan.value.forEach(x => {
        if (x.id === e.jenisobatfk) {
            jenisobat = x;
        }
    });
    item.jenisobat = jenisobat


    let kekuatan = e.kekuatan ?? 1;
    let hrg1 = Math.round(parseFloat(e.hargasatuan) * parseFloat(e.hasilkonversi))
    let hrgsdk = parseFloat(e.hargadiscount) * parseFloat(e.hasilkonversi)
    let total = (parseFloat(e.jumlah) * (hrg1 - hrgsdk)) + parseFloat(tarifJasa.value)
    // let jumlahobat = (parseFloat(kekuatan) / parseFloat(e.dosis)) * parseFloat(e.jumlah)

    let jumlahobat = 0;
    if (e.jeniskemasanfk === 2) {
      jumlahobat = 1;
    } else if (e.jeniskemasanfk === 1) {
      jumlahobat = (parseFloat(kekuatan) / parseFloat(e.dosis)) * parseFloat(e.jumlah);
    }

    var dataItem = {
    no: i + 1,
    noregistrasifk: item.NOREC_APD,
    generik: null,
    hargajual: String(e.hargasatuan),
    jenisobat: item.jenisobat ??null,
    jenisobatfk: item.jenisobatfk ?? null,
    kekuatan: kekuatan,
    kelasfk: item.registrasi.objectkelasfk,
    stock: String(e.jmlstok),
    harganetto: null,
    nostrukterimafk: e.nostrukterimafk,
    norec_spd: null,
    // ruanganfk: items.objectruangantujuanfk,
    rke: e.rke,
    jeniskemasanfk: e.jeniskemasanfk,
    jeniskemasan: e.jeniskemasan,
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
    jumlah: e.qtyproduk,//item.jumlahbulat,
    jumlahobat: jumlahobat,//item.jumlah,
    dosis: e.dosis,
    hargasatuan: String(e.hargasatuan),
    hargadiscount: String(e.hargadiscount),
    persendiscount: 0,
    total: total,
    jmldosis: String(e.jumlah) + '/' + String(e.dosis) + '/' + String(kekuatan),
    jasa: tarifJasa.value,
    keterangan: e.keteranganpakai,
    satuanresepfk: e.satuanresepfk,
    satuanresep: e.satuanresep,
    tglkadaluarsa: e.tglkadaluarsa,
    totalstok: e.totalstok,
  }
    data2.value.push(dataItem)
    console.log('data reorder', dataItem);

    setColor()
    dataSource.value = data2.value
    console.log('data order ulang',dataSource.value);
    if (e.jeniskemasanfk != 1) {
      item.rke = parseFloat(item.rke) + 1
    }
  }
  // modalInput.value = true
  activeValue.value = 1
  countTotal()
  clearInput()
}

const copyResep = async () => {
  isloadingCopy.value = true
  let penulis = ''
  if (item.pegawaiOrder) {
    penulis = `&penulisresepfk=${item.pegawaiOrder.id}`
  }
  let riwayat = await useApi().get(
    `/farmasi/riwayat-resep-verif?nocmfk=${ID_PASIEN}${penulis}&limit=1`)
  isloadingCopy.value = false
  riwayat = riwayat.filter(item => item.statusorder === 5)

  if (riwayat.length == 0) {
    H.alert('error', 'Belum ada resep terverifikasi')
    return
  }
  delete item.NOREC_SO
  // let CariRuangan = d_Ruangan.value.find(x => x.id = riwayat[0].objectruangantujuanfk)
  await fetchDokter({ query: riwayat[0].namalengkap })
  let CariDokter = d_Dokter.value.find(x => x.id = riwayat[0].objectpegawaiorderfk)
  item.rke = 0
  item.ruangan = item.ruangan
  item.pegawaiOrder = CariDokter
  item.tglorder.start = new Date()
  item.tglorder.end = new Date()
  item.isreseppulang = riwayat[0].isreseppulang === true ? true : false

  for (let i = 0; i < riwayat[0].details.length; i++) {
    const e = riwayat[0].details[i];
    if (e.objectsubkategoryfk === 8) {
    continue;
    }

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

const editingRows = ref(dataSource.value.map((_, index) => index));

function cetakOrder(data: any) {
  H.printBlade(`report/farmasi/resep?pdf=true&norec_order=${data.norec_order}`)
}

function countDosis() {
  // var dataceklina = listChecked.value;
  item.dosisNA = 1;
  if (item.jenisKemasan.jeniskemasan == 'Racikan') {
    item.dosisNA = item.dosis
    item.jumlahxmakan = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
    console.log('kekuatan', item.kekuatan)
    console.log('dosis',item.dosis)
    console.log('jumlah',item.jumlah)
    console.log('jumlahmakan', item.jumlahxmakan)
  } else {
    item.jumlahxmakan = item.jumlah
  }
}

function cekSimpan()
{
  let totalbayarr = 0;
  for (let x = 0; x < data2.value.length; x++) 
  {
      const element = data2.value[x];
      const totalbayarnya = (element.jumlah)*(element.hargajual)
      totalbayarr += totalbayarnya;
  }
  
  console.log('ini total bayar',totalbayarr)
  console.log('cek', item.iskronis23)
  console.log('cek2', item.iskronis30)
  if (item.registrasi.objectkelompokpasienlastfk == 2 && totalbayarr > 50000 && item.iskronis23 == false && item.iskronis30 == false) 
  {
    confirm1()
  }
  else
  {
    simpan()
  }
}

const confirm1 = () => {
    confirm.require({
      message: 'Nilai Resep Lebih Dari Rp. 50.000? Apakah Anda Ingin Melanjutkan ?',
      header: 'Konfirmasi Nilai Resep untuk Pasien BPJS',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Cancel',
        severity: 'secondary',
        outlined: true
      },
      acceptProps: {
        label: 'Save'
      },
      accept: () => {
        simpan(); 
      },
      reject: () => {
        toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
        return;  
      }
    });
  };

function add() {
  // console.log('MMK', listChecked.value)
  // console.log("isFornas:", listChecked[0]?.fornas );

  // console.log("list", listChecked.value[0]?.fornas);

  countDosis()

  if (!item.jenisKemasan) {
    useToaster().error('Pilih Jenis Kemasan terlebih Dahulu')
    return
  }
  // allz tambah eresep
  // if (
  //   item.registrasi.objectkelompokpasienlastfk !== 1 &&
  //   (listChecked.value[0]?.fornas === false)
  // ) {
  //   useToaster().error('Obat yang dipilih harus memiliki status Fornas untuk pasien ini.');
  //   return;
  // }
  if (item.jenisKemasan.id) {
    if (item.produkCeklis == undefined || item.produkCeklis.length == 0) {
      H.alert('error', 'Pilih layanan terlebih dahulu')
      return
    }
    var dataceklina = listChecked.value;
    var arrobj = Object.keys(item.produkCeklis)
    var datana = []

    // console.log('data', data2.value);
    
    for (var i = 0; i < dataceklina.length; i++) {
      for (var d = 0; d < arrobj.length; d++) {
        if (dataceklina[i]['id'] == parseInt(arrobj[d])) {
          if(data2.value == undefined) data2.value=[]
          let nomor = 0
          if (data2.value.length == 0) {
            console.log('jenis racikan', item.jenisRacikan)
            nomor = 1
            if (item.jenisKemasan.jeniskemasan == 'Racikan') {
              item.rke = 1
              item.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : null
              item.jenisobat = item.jenisRacikan ? item.jenisRacikan.jenisracikan : null
              item.tarifadminresep =  item.tarifadminresep ? item.tarifadminresep : 0
            } else {
              item.rke = data2.value.length + 1
              // item.rke = 99;
              item.jenisobatfk = item.jenisRacikan && item.jenisRacikan.id == 10 ? item.jenisRacikan.id : 10;
              item.jenisobat = item.jenisRacikan && item.jenisRacikan.jenisracikan == 'Non-Racikan' ? item.jenisRacikan.jenisracikan : 'Non-Racikan';
              item.tarifadminresep = item.tarifWNI ? item.tarifWNI : 0
            }

          } else {
            nomor = Math.max(...data2.value.map(item => item.no)) + 1;
            if (item.jenisKemasan.jeniskemasan == 'Racikan') {
              item.rke = item.rke
              item.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : null
              item.jenisobat = item.jenisRacikan ? item.jenisRacikan.jenisracikan : null
              item.tarifadminresep =  item.tarifadminresep ? item.tarifadminresep : 0

            } else {
              item.rke = Math.max(...data2.value.map(item => item.rke)) + 1;
              // item.rke = 99;
              item.jenisobatfk = item.jenisRacikan && item.jenisRacikan.id == 10 ? item.jenisRacikan.id : 10;
              item.jenisobat = item.jenisRacikan && item.jenisRacikan == 10 ?  'Non-Racikan' : null;
              item.tarifadminresep = item.tarifWNI ? item.tarifWNI : 0
            }

          }
          // console.log('hey', item.jenisobat);
          // console.log(item.jenisRacikan);

          // console.log('bangsa', item.registrasi.objectkebangsaanfk);

          // console.log('aku', item.jenisobatfk);


          var qtyOK: any = 0;
          var qtyCetak = 0;
          var total = 0;
          var jumlahreal = 0;

          let jmlbulat = 0;
          let jml = 0;

          jmlbulat = item.jumlahbulat;
          jml = item.jumlah;

          if (isNaN(jmlbulat) || jmlbulat == null) {
            jmlbulat = 1;
          }
          if (isNaN(jml) || jml == null) {
            jml = 1;
          }

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
                data.jenisobatfk = item.jenisobatfk
                data.jenisobat = item.jenisobat
                data.kelasfk = item.registrasi.objectkelasfk
                data.stock = item.stok
                data.qtymax = item.qtymax
                data.kekuatan = item.kekuatan
                data.harganetto = item.hargaNetto
                data.nostrukterimafk = norecTerima.value
                data.norec_spd = norecSPD.value
                data.ruanganfk = item.ruangan.id
                data.rke = item.rke
                data.jeniskemasanfk = item.jenisKemasan.id
                data.jeniskemasan = item.jenisKemasan.jeniskemasan
                data.aturanpakai = item.aturanpakaitxt ?? "" //item.aturanPakai.aturanpakai
                data.aturanpakaifk = 0// item.aturanPakai.id
                data.ispagi = item.chkp
                data.issiang = item.chks
                data.issore = item.chksr
                data.ismalam = item.chkm
                data.iskronis = item.checkisKronis
                data.iskronis23 = item.iskronis23 ? item.iskronis23 : null
                data.iskronis30 = item.iskronis30 ? item.iskronis30 : null
                data.route = item.route ? item.route.name : null
                data.asalprodukfk = item.asal.id
                data.asalproduk = item.asal.asalproduk
                data.produkfk = item.produk.id
                data.namaproduk = item.produk.namaproduk
                data.productname = item.produk.productname
                data.nilaikonversi = item.nilaiKonversi
                data.satuanstandarfk = item.satuan.ssid
                data.satuanstandar = item.satuan.satuanstandar
                data.satuanviewfk = item.satuan.ssid
                data.satuanview = item.satuan.satuanstandar
                data.jmlstok = item.stok
                data.jumlah = jmlbulat   //item.jumlah  // jmlbulat
                data.jumlahobat = jml
                data.dosis = item.dosisNA
                data.objectgenerikfk = item.objectgenerikfk
                data.hargasatuan = String(item.hargaSatuan)
                data.hargadiscount = String(item.hargadiskon)
                data.persendiscount = item.persenDiskon ? item.persenDiskon : 0
                data.total = item.total
                data.jmldosis = String(item.jumlahxmakan) + '/' + String(item.dosisNA) + '/' + String(item.kekuatan)
                data.jasa = item.tarifadminresep
                data.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null
                data.satuanresepfk = item.satuanresep ? item.satuanresep.id : null
                data.satuanresep = item.satuanresep ? item.satuanresep.satuanresep : null
                data.tglkadaluarsa = item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null
                data.racikan = item.jumlahobat ? item.jumlahobat : 1


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
            // let checkobat = checkResepTerakhir(item.produk);
            // if (checkobat != '')
            //   useToaster().warn(checkobat)

            if (data2.value.length > 0) {
              var racikan = data2.value[data2.value.length - 1].jeniskemasan
              if (racikan == 'Non Racikan') {
                item.rke = Math.max(...data2.value.map(item => item.rke)) + 1;
              }
            }
            data = {
              no: nomor,
              noregistrasifk: item.NOREC_APD,
              generik: null,
              hargajual: String(dataceklina[i]['hargajual']),
              jenisobatfk: item.jenisobatfk,
              jenisobat: item.jenisobat,
              kelasfk: item.registrasi.objectkelasfk,
              stock: String(dataceklina[i]['qtyproduk']),
              totalstok: String(dataceklina[i]['totalstok']),
              qtymax: String(dataceklina[i]['qtymax']),
              harganetto: String(dataceklina[i]['hargajual']),
              fornas: dataceklina[i]['fornas'],
              nostrukterimafk: String(dataceklina[i]['norec']),
              norec_spd: (dataceklina[i]['norec_spd']),
              ruanganfk: item.ruangan.id,
              rke: item.rke,
              kekuatan: item.kekuatan,
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
              iskronis23: item.iskronis23 ? item.iskronis23 : null,
              iskronis30: item.iskronis30 ? item.iskronis30 : null,
              route: item.route ? item.route.name : null,
              asalprodukfk: dataceklina[i]['objectasalprodukfk'],
              asalproduk: dataceklina[i]['asalproduk'],
              produkfk: dataceklina[i]['id'],
              namaproduk: dataceklina[i]['namaproduk'],
              productname: dataceklina[i]['namaproduk'],
              nilaikonversi: dataceklina[i]['nilaikonversi'],
              satuanstandarfk: dataceklina[i]['satuanstandarfk'],
              satuanstandar: dataceklina[i]['satuanstandar'],
              satuanviewfk: dataceklina[i]['satuanstandarfk'],
              satuanview: dataceklina[i]['satuanstandar'],
              jmlstok: String(dataceklina[i]['qtyproduk']),
              jumlah: jmlbulat,//item.jumlahbulat,
              jumlahobat: 1,//item.jumlah,
              dosis: item.dosisNA,
              hargasatuan: String(dataceklina[i]['hargajual']),
              hargadiscount: String(dataceklina[i]['hargadiscount']),
              persendiscount: item.persenDiskon ? item.persenDiskon : 0,
              // total: item.total,
              total: parseFloat(jmlbulat) * parseFloat(dataceklina[i]['hargajual']),
              jmldosis: String(item.jumlahxmakan) + '/' + String(item.dosisNA) + '/' + String(item.kekuatan),
              jasa: item.tarifadminresep,
              keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
              satuanresepfk: item.satuanresep ? item.satuanresep.id : null,
              satuanresep: item.satuanresep ? item.satuanresep.satuanresep : null,
              objectgenerikfk: dataceklina[i]['objectgenerikfk'] ? dataceklina[i]['objectgenerikfk'] : null,
              tglkadaluarsa: dataceklina[i]['tglkadaluarsa'] ? dataceklina[i]['tglkadaluarsa'] : null,
              racikan: isNaN(dataceklina[i]['jumlahobat']) || dataceklina[i]['jumlahobat'] == null ? '1' : String(dataceklina[i]['jumlahobat']),
            }
            console.log('data euy', data2.value)
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
        }
      }
    }
  } else {

    if (!item.produk.id) {
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
    if (!item.aturanpakaitxt || item.aturanpakaitxt == null) {
      useToaster().error('Aturan Pakai harus di isi')
      return
    }
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
          data.jenisobatfk = item.jenisobatfk
          data.jenisobat = item.jenisobat
          data.kelasfk = item.registrasi.objectkelasfk
          data.stock = item.stok
          data.qtymax = item.qtymax
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
          data.iskronis23 = item.iskronis23 ? item.iskronis23 : null
          data.iskronis30 = item.iskronis30 ? item.iskronis30 : null
          data.routefk = item.route ? item.route.id : null
          data.route = item.route ? item.route.name : null
          data.asalprodukfk = item.asal.id
          data.asalproduk = item.asal.asalproduk
          data.produkfk = item.produk.id
          data.namaproduk = item.produk.namaproduk
          data.productname = item.produk.productname
          data.nilaikonversi = item.nilaiKonversi
          data.satuanstandarfk = item.satuan.ssid
          data.satuanstandar = item.satuan.satuanstandar
          data.satuanviewfk = item.satuan.ssid
          data.satuanview = item.satuan.satuanstandar
          data.objectgenerikfk = item.objectgenerikfk
          data.jmlstok = item.stok
          data.jumlah = jmlbulat,//item.jumlahbulat, // item.jumlah
          data.jumlahobat = 1
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
          data.racikan = item.jumlahobat ? item.jumlahobat : 1
          data.iskronis = item.checkisKronis ? item.checkisKronis : null

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
        jenisobatfk: item.jenisobatfk,
        jenisobat: item.jenisobat,
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
        iskronis30: item.iskronis30 ? item.iskronis30 : null,
        iskronis23: item.iskronis23 ? item.iskronis23 : null,
        route: item.route ? item.route.name : null,
        asalprodukfk: item.asal.id,
        asalproduk: item.asal.asalproduk,
        produkfk: item.produk.id,
        namaproduk: item.produk.namaproduk,
        productname: item.produk.productname,
        nilaikonversi: item.nilaiKonversi,
        satuanstandarfk: item.satuan.ssid,
        satuanstandar: item.satuan.satuanstandar,
        objectgenerikfk: item.objectgenerikfk,
        satuanviewfk: item.satuan.ssid,
        satuanview: item.satuan.satuanstandar,
        jmlstok: String(item.stok),
        jumlah:  jmlbulat, //item.jumlah,//item.jumlahbulat,
        jumlahobat: 1,//item.jumlah,
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
        racikan: String(item.jumlahobat) ? String(item.jumlahobat) : 1,
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
  }

  // setColor()
  if (item.jenisKemasan.jeniskemasan != 'Racikan') {
    item.rke = parseFloat(item.rke) + 1
    // item.rke = 98;
  }
  dataSource.value = data2.value
  listChecked.value = []

  item.produkCeklis = []
  countTotal()
  // clearInput()
  item.iskronis30 = false
  item.iskronis23 = false
}

const updateRacikanFields = () => {
  dataSource.value.forEach((row) => {
    if (row.jeniskemasanfk === 1) {
      row.jenisobat = item.value.jenisRacikan;
      row.jumlahobat = item.value.jumlahobat;
    }
  });
};

watch(
  item,
  (newVal) => {
    console.log(props)
    console.log(item)
    console.log(dataSource)
    dataSource.value.forEach((row) => {
      // Cari data dengan nilai R/Ke yang sama
      if (row.rke === newVal.rke) {
        if (newVal.jenisRacikan) {
          row.jenisobatfk = newVal.jenisRacikan.id;
          row.jenisobat = newVal.jenisRacikan;
        }

        if (newVal.aturanpakai) {
          row.aturanpakai = newVal.aturanpakai;
        }
        if (newVal.jumlahobat) {
          row.jumlahobat = newVal.jumlahobat;
        }
      }
    });
  },
  { deep: true }
);

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
  // item.total = H.formatRp(total, 'Rp.')
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


watch(() => item.ruangan, (newValue, oldValue) => {
  console.log(item.ruangan)
})

function closeModal() {
  showModal.value = false;
}

watch(
  () => item.jenisoperasi,
  (newVal) => {
    if (newVal && newVal.id) {
      fetchDetails(newVal.id);
      fetchTindakan2(newVal.id);
    }
  }
);
watch(
  () => item.jenistindakan,
  (newVal) => {
    if (newVal && newVal.id) {
      fetchTindakan1(newVal.id);
    }
  }
);

// watch(
//   () => item.jenistindakan,
//   (newVal) => {
//     if (newVal && newVal.id) {
//       fetchTindakan2(newVal.id);
//     }
//   }
// );

// watch(() => item.namaprodukserch, (newValue, oldValue) => {
//   filterProdukna()
// })

// watch(() => item.jenisKemasan, (newValue, oldValue) => {
//   if(newValue.id == 2) {
//     disabledJenis.value = true;
//     // slotProps.value.data.jenisobat = 10;
//   }
// })

const paketObat = async () => {
  isloadingPaketObat.value = true
// let namaPaket = ''
// if (item.namapaket) namaPaket = `namapaket=${item.namapaket}`
    let namaPaket = item.namapaket ? `&namaPaket=${item.namapaket}` : ''

  await useApi()
    .get(`/farmasi/data-paket-obat?${namaPaket}`)
    .then((response: any) => {
      isloadingPaketObat.value = false
      response.data.forEach((element: any, i: any) => {
        expandedRows.value = element.details.forEach((data: any, i: any) => {
          data.no = i + 1
        })
        element.no = i + 1
      })
      dataSourcePaketObat.value = response.data
      modalDataPaketObat.value = true
    })
    .catch((err: any) => {
      isloadingPaketObat.value = false
    })
}

function filterpaket() {
  paketObat()
}


function LenyapkanObat() {
  delete item.produk
  delete item.asal
  delete item.satuan
  delete item.no
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
    delete item.satuanresep
    item.chkp = 0
    item.chks = 0
    item.chksr = 0
    item.chkm = 0
    listDataSigna.value.forEach((element: any) => {
      element.isChecked = false
    });
  }
  isLastObatByDate.value = false

}

function clearPacket() {
  // delete item.produk
  // delete item.asal
  // delete item.satuan
  // delete item.no
  delete item.aturanpakai

  // delete item.KeteranganPakai
  // delete dataSelected.value
  // delete item.tglKadaluarsa
  // item.isbpl = false;
  // item.cito = false;
  // item.isrutin = false;

  // item.qty = 1
  // item.totalAll = 0
  // TOTAL.value =0
  // modalInput.value = false
  // item.nilaiKonversi = 0
  // item.stok = 0
  item.jumlah = 0
  // item.jumlahbulat = item.jumlah
  // item.jenisRacikan = 10
  // item.jumlahobat = 0

  // item.hargadiskon = 0
  // item.total = 0
  // item.hargaSatuan = 0
  // item.hargaNetto = 0
  // item.rke = 1
  // item.persenDiskon = 0

  // if (item.jenisKemasan.jeniskemasan != 'Racikan') {
  //   delete item.satuanresep
  //   delete item.aturanpakaitxt
  //   item.jumlahxmakan = 1
  //   item.chkp = 0
  //   item.chks = 0
  //   item.chksr = 0
  //   item.chkm = 0
  //   listDataSigna.value.forEach((element: any) => {
  //     element.isChecked = false
  //   });
  // }
}

function help() {
  window.open('https://youtu.be/VSrmuuXkfQI', '_blank').focus();
}

function clearInput() {
  delete item.produk
  delete item.asal
  delete item.satuan
  delete item.jeniksm
  delete item.jenisoperasi
  delete item1.devisi
  delete item1.antibiotik
  delete item.jenisobat
  delete item.antibiotik
  delete item.tanggalhasil
  // delete item.namaprodukserch
  delete item.jenissampel
  delete item.hasilbakteri
  delete item.rekomendasi
  delete item.jenistindakan
  delete item.no
  delete item.aturanpakai

  delete item.KeteranganPakai
  delete dataSelected.value
  delete item.tglKadaluarsa
  item.isbpl = false;
  item.cito = false;
  item.isrutin = false;
  item.checkisKronis = false;

  item.qty = 1
  item.nilaiKonversi = 0
  item.stok = 0
  item.jumlah = 0
  item.jumlahbulat = item.jumlah
  item.jenisRacikan = 10
  item.jumlahobat = 1

  item.hargadiskon = 0
  item.total = 0
  item.hargaSatuan = 0
  item.hargaNetto = 0
  item.rke = 1
  item.persenDiskon = 0

  if (item.jenisKemasan.jeniskemasan != 'Racikan') {
    delete item.satuanresep
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
  isLastObatByDate.value = false
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
      // Cek kondisi objectkebangsaanfk
      if (item.header.objectkebangsaanfk == 1) {
          tarifJasa.value = parseFloat(item.tarifWNI);
      } else if (item.header.objectkebangsaanfk == 2) {
          tarifJasa.value = parseFloat(item.tarifKitas);
      } else if (item.header.objectkebangsaanfk == 3) {
          tarifJasa.value = parseFloat(item.tarifNonKitas);
      }
  }

  if (item.jenisKemasan.id == 1) {
      qty20 = Math.floor(parseFloat(item.jumlah) / 20);
      if (parseFloat(item.jumlah) % 20 == 0) {
          qty20 = qty20;
      } else {
          qty20 = qty20 + 1;
      }

      if (qty20 != 0) {
          tarifJasa.value = tarifJasa.value * qty20;
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
  isMerge.value = false
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
    `/farmasi/data-order-resep-hari-ini?norec=${props.registrasi.norec_pd}`)
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
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&ruanganfk=${item.ruangan.id}&limit=10`)
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

function onlyNumber(evt: any) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();
    } else {
        return true;
    }
}


const changeFilter = (e: any) => {
  loadRiwayat(false)
}
const pilihPaketObat = (e: any) => {
  // console.log('dataSelected:', dataSelected);
  // console.log('dataSelected:', e);
  isPaketObatSelected = true;
  if (e.details.length == 0) {
    H.alert('error', 'Obat pada paket ini tidak ada')
    return
  }

  if (item.nilaiKonversi == undefined || item.nilaiKonversi == 0) {
    item.nilaiKonversi = 1;
  }

  delete item.NOREC_SO
  item.tglorder.start = new Date()
  item.tglorder.end = new Date()
  e.details.forEach((element: any, i: any) => {
    isloadingTambahPaket.value = true
    let data: any = {}
    if (element.produkfk != undefined) {
      useApi().get('/farmasi/get-produkdetail?produkfk=' + element.produkfk + '&ruanganfk=' + item.ruangan.id + "&kpid=" + item.registrasi.objectkelompokpasienlastfk + "&norec_apd=" + item.NOREC_APD).then(function (response: any) {
        if (response != null) {
          // console.log('masuk if');

          dataProdukDetail.value = response.detail
          console.log('wei', dataProdukDetail.value)
          item.stok = response.jmlstok / item.nilaiKonversi
          if (response.kekuatan == undefined || response.kekuatan == 0) {
            response.kekuatan = 1
          }
          item.kekuatan = response.kekuatan
          item.sediaan = response.sediaan
          item.tglKadaluarsa = response.detail[0]

          if (e.no != undefined) {
            // console.log('masuk ke if set');

            dataProdukDetail.value.forEach((element: any) => {
            item.jumlah = element.jumlah
            item.dosis = element.dosis
            item.totalstok = element.totalstok
            item.hargaSatuan = element.hargajual
            item.hargadiskon = element.hargadiscount
            item.hargaNetto = element.harganetto
            // item.total = (parseFloat(element.jumlah) * (parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon)));
            // if (isNaN(item.total)) {
            //     item.total = 0;
            //   }
            // console.log('jumlah', element.jumlah)
            // console.log('hargaSatuan', item.hargaSatuan)
            // console.log('hargadiskon', item.hargadiskon)
            item.stok = element.qtyproduk

            })

            // item.jumlah = dataProdukDetail.jumlah
            // item.dosis = dataProdukDetail.dosis
            // item.jumlahobat = (parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan)
            // item.nilaiKonversi = dataProdukDetail.nilaikonversi
            // d_satuan.value.forEach((element: any) => {
            //   if (element.ssid == dataSelected.value.satuanviewfk) {
            //     item.satuan = element
            //   }
            // })
            // item.hargaSatuan = dataProdukDetail.hargasatuan
            // item.hargadiskon = dataProdukDetail.hargadiscount
            // item.hargaNetto = dataProdukDetail.harganetto
            // item.total = dataProdukDetail.total
          } else {
            // console.log('else set data');

            if (!isMerge.value) {
              item.jumlah = 1
            }
          }

          if (item.jenisKemasan.id === 1) {
          // Do nothing if id is 1 (don't increment rke)
          item.rke = item.rke;  // This line ensures no change in rke
        } else if (item.jenisKemasan.id === 2) {
          // Increment rke by 1 if id is 2
          item.rke = parseFloat(item.rke) + 1;
        }

          setNorecSPD()
          // console.log('kiwww');

          isLoading.value = false
        } else {
          // console.log('masuk else')
          if (response.jmlstok == 0) {
            useToaster().warn(`Stok ${element.namaproduk} belum ada`)
          }
          item.hargaSatuan = 0
          item.hargadiskon = 0
          item.stok = response.jmlstok
          item.hargaNetto = 0
          item.total = 0
          isLoading.value = false
        }
        // console.log('jasa', tarifJasa.value);


        data = {
          no: dataSource.value.length + 1,
          noregistrasifk: item.NOREC_APD,
          generik: null,
          hargajual: String(item.hargaSatuan),
          jenisobatfk: item.jenisRacikan ? item.jenisRacikan.id : null,
          iskronis23: item.iskronis23 ? item.iskronis23 : null,
          iskronis30: item.iskronis30 ? item.iskronis30 : null,
          jenisobat: item.jenisRacikan ? item.jenisRacikan.jenisracikan : null,
          kelasfk: item.registrasi.objectkelasfk,
          stock: String(item.stok),
          harganetto: String(item.hargaNetto),
          nostrukterimafk: response.nostrukterimafk != undefined ? response.nostrukterimafk : null,
          norec_spd: response.norec != undefined ? response.norec : null,
          ruanganfk: item.ruangan.id,
          rke: item.rke,
          jeniskemasanfk: item.jenisKemasan.id,
          jeniskemasan: item.jenisKemasan.jeniskemasan,
          aturanpakaifk: 0,//item.aturanPakai.id,
          aturanpakai: element.aturanpakai,//item.aturanPakai.aturanpakai,
          ispagi: element.ispagi != undefined ? element.ispagi : null,
          issiang: element.issiang != undefined ? element.issiang : null,
          issore: element.issore != undefined ? element.issore : null,
          ismalam: element.ismalam != undefined ? element.ismalam : null,
          asalprodukfk: item.asal != undefined ? item.asal.id : null,
          asalproduk: item.asal != undefined ? item.asal.asalproduk : null,
          produkfk: element.produkfk,
          namaproduk: element.namaproduk,
          nilaikonversi: 1,
          satuanstandarfk: element.objectsatuanstandarfk,
          satuanstandar: element.satuanstandar,
          satuanviewfk: item.satuan != undefined ? item.satuan.ssid : null,
          satuanview: item.satuan != undefined ? item.satuan.satuanstandar : null,
          satuanviewfk: element.objectsatuanstandarfk,
          satuanview: element.satuanstandar,
          jmlstok: String(item.stok),
          jumlah: element.jumlah,//item.jumlahbulat,
          jumlahobat: element.jumlahobat,//item.jumlah,
          dosis: item.dosisNA != undefined ? item.dosisNA : 1,
          hargasatuan: String(item.hargaSatuan),
          hargadiscount: String(item.hargadiskon),
          persendiscount: item.persenDiskon ? item.persenDiskon : 0,
          total: parseFloat(element.jumlah) * parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon),
          jmldosis: String(element.jumlah) + '//' + String(item.kekuatan),
          jasa: tarifJasa.value != undefined ? tarifJasa.value : null,
          keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
          satuanresepfk: item.satuanresep ? item.satuanresep.id : null,
          satuanresep: item.satuanresep ? item.satuanresep.satuanresep : null,
          tglkadaluarsa: item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null,
          routefk: null,
          route: null,
          iskronis: null,
          kekuatan: item.kekuatan,
          totalstok: item.totalstok,
        }
        console.log('testinggggg', data)
        data2.value.push(data)
        for (let i = 0; i < data2.value.length; i++) {
          const element = data2.value[i];
          if (element.iskronis == true) {
            element.obtkronis = "✔"
          } else {
            element.obtkronis = ""
          }
        }
      })
      modalDataPaketObat.value = false
      isloadingTambahPaket.value = false
    }
  })
  setColor()

  dataSource.value = data2.value
  console.log('haloooo')
  if (item.jenisKemasan.jeniskemasan != 'Racikan') {
  if (item.jenisKemasan.id === 1) {
    item.rke = item.rke;
  } else if (item.jenisKemasan.id === 2) {
    item.aturanpakai = ''
    item.jumlahobat = 1
    item.jenisRacikan =  null,
    item.jenisobat =  null,
    item.rke = parseFloat(item.rke) + 1;
  }
  console.log('Aduh')


}

isPaketObatSelected = true;
  countTotal()
  clearPacket()
  // clearInput()
}

const filters = ref('')
const listDataReturFiltered = computed(() => {
  if (!filters.value) {
    return listDataRetur.value
  }

  return listDataRetur.value.filter((items: any) => {
    return (
      items.namaproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})

const getDataObat = async () => {
  listDataRetur.value = []
  console.log('ini apd',item.NOREC_APD)
  console.log('ini pd',props.registrasi.norec_pd)
  let params = `&norec_pd=${item.NOREC_PD}`
  let params2 = `&norec_apd=${item.NOREC_APD}`
  let ruangan = `&ruanganfk=${item.NOREC_PD}`
  
  if (isNORM.value == true) {
    params = ``
  }
  let penulis = ''
  if (item.pegawaiOrder) {
    penulis = `&penulisresepfk=${item.pegawaiOrder.id}`
  }
  isLoading.value = true;
  try {
    await useApi().get(
    `/farmasi/riwayat-resep-verif?verif=true&nocmfk=${ID_PASIEN}${params}${params2}`).then((response: any) => {
      isLoading.value = false;
      let forResponse = [];
      if(response.length > 0) {
        for (let x = 0; x < response.length; x++) {
          const headObat = response[x];
          if(headObat.details.length > 0) {
            for (let t = 0; t < headObat.details.length; t++) {
              const det = headObat.details[t];
              det.noorder = headObat.noorder;
              det.isSelected = false;
              det.strukresepfk = headObat.norec_resep;
              det.jumlahAwal = det.jumlah;
              det.ruanganfk = headObat.objectruangantujuanfk
              det.noregistrasifk = headObat.noregistrasifk
              det.noregistrasi = headObat.noregistrasi
              det.tglregistrasi = headObat.tglregistrasi
              det.id_apd = headObat.norec_apd ? headObat.norec_apd : null
              det.id_pp = det.id_pp ? det.id_pp : null 
              forResponse.push(det);
            }
          }
        }
      }
      listDataRetur.value = forResponse;
    })
  } catch (error) {
    isLoading.value = false;
    H.alert('warning', 'Terjadi kesalahan');
    console.log('ERROR', error)
  }
  
}

const simpanRetur = async () => {
  isLoading.value = true;
  let dataKirim = [];
  for (let index = 0; index < listDataReturFiltered.value.length; index++) {
    const ss = listDataReturFiltered.value[index];
    if(ss.isSelected == true) {
      if(ss.jumlah == 0 || ss.jumlah == "0") {
        H.alert('warning', `Qty / Jumlah pada Obat : ${ss.namaproduk} tidak boleh 0`);
        isLoading.value = false;
        return;
      }
      if(parseInt(ss.jumlah) > parseInt(ss.jumlahAwal)) {
        H.alert('warning', `Qty / Jumlah pada Obat : ${ss.namaproduk} tidak boleh lebih dari ${ss.jumlahAwal}`);
        isLoading.value = false;
        return;
      }
      // ss.ruanganfk = item.registrasi.objectruanganlastfk
      dataKirim.push(ss);
    }
  }

  if(dataKirim.lenght == 0) {
    H.alert('warning', 'Silahkan pilih setidaknya 1 data untuk di retur!');
    isLoading.value = false;
    return;
  }

  let obj = {
    data: dataKirim
  };
  console.log('DATA KIRIM', obj);
  await useApi().post('/farmasi/retur-obat-perawat', obj).then((res) => {
    isLoading.value = false;
    getDataObat();
  }).catch((err) => {
    isLoading.value = false;
    console.log('ERR Post Retur', err)
  })
}

const returSelected = (event) => {
  listDataReturFiltered.value[event.index].isSelected = true;
  console.log(listDataReturFiltered.value);
};
const returUnselected = (event) => {
  listDataReturFiltered.value[event.index].isSelected = false;
}

const returSelectAll = (event) => {
  for (let index = 0; index < listDataReturFiltered.value.length; index++) {
    listDataReturFiltered.value[index].isSelected = true;   
  }
};

const returUnselectAll = (event) => {
  console.log("DATA UNSELECT ALL", event)
  for (let index = 0; index < listDataReturFiltered.value.length; index++) {
    listDataReturFiltered.value[index].isSelected = false;   
  }
};

watch(
  () => item.jenisKemasan,
  (newValue, oldValue) => {
    showRacikanDoseFalse.value = true
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
// watch(
//   () => item.namaprodukserch,
//   (newValue, oldValue) => {
//     if (newValue != oldValue) {
//       filterProdukna()
//     }
//   }
// )
function teanganNama(e: any) {
  if (e.keyCode === 13) {
    filterProdukna()
  }
}
// watch(
//   () => item.kodeprodukserch,
//   (newValue, oldValue,e) => {
//     if (newValue != oldValue) {
//       if (e.keyCode === 13) {
//       filterProdukna()

//       }
//     }
//   }
// )

watch(
  () => item.dosis,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      console.log('HALO INI DOSIS', item.dosis)
      jumlahkan()
    }
  }
)

watch(
  () => item.iskronis23,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (newValue === true) {
                item.iskronis30 = false
            } 
            else 
            {
              item.iskronis23 = false
            }
        }
    }  
)

watch(
  () => item.iskronis30,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (newValue === true) {
                item.iskronis23 = false

            } 
            else 
            {
              item.iskronis30 = false
            }
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
      loadRiwayatVerif(true)
    }
    if(value == 4) {
      getDataObat();
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
// watch(() => item.rke, (newValue, oldValue) => {
//   if (newValue != oldValue) {
//     if (tarifJasa == 0) {
//       for (var i = data2.value.length - 1; i >= 0; i--) {
//         tarifJasa.value = parseFloat(item.tarifadminresep)
//         if (data2.value[i].rke == item.rke) {
//           tarifJasa.value = 0
//           break;
//         }
//       }
//     }
//   }
// })
watch(() => isNORM.value, (newValue, oldValue) => {
  if (newValue == true && activeValue.value == 2) {
    loadRiwayat(false)  
  }
  else 
  {
    loadRiwayatVerif(false)
  }
})
onBeforeMount(() => {
  console.log(props.registrasi.noregistrasi)
  try {
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) {
      // item = cache.item

      const moc= cache.dataSource == null || cache.dataSource == undefined || cache.dataSource == '' ? []:[]
      dataSource.value = moc
      data2.value = cache.data2
    }
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});
onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, {
      // 'item':item,
      'dataSource': dataSource.value,
      'data2': data2.value,
    })
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});


const getHistoriPpra = () =>{
  useApi().get('/farmasi/riwayat-ppra'+'?noregistrasifk='+  props.registrasi.norec_pd).then((response:any)=>{
    // console.log('count',response.count); 
    
    m_historippra.value=response.key
  })
}

getHistoriPpra()

const getSelected = () => {
  if (item.produkCeklis.length > 0) {
    var arrobj = Object.keys(item.produkCeklis)
    for (var x = 0; x < arrobj.length; x++) {
      const element = arrobj[x];
      if (item.produkCeklis[parseInt(element)] == true) {
        for (var i = 0; i < d_ProdukDef.value.length; i++) {
          const element2 = d_ProdukDef.value[i];
          if (element2.id == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.namaproduk == element2.namaproduk) {
                listChecked.value.splice(z, 1)
              }
            }

            if 
            (
              item.registrasi.objectkelompokpasienlastfk != 1 &&
              item.registrasi.objectkelompokpasienlastfk != 3 &&
              element2.fornas != true && element2.objectdetailjenisprodukfk === 2546
            ) 
            {
              useToaster().error("Pasien BPJS ! , Obat yang dipilih Non Fornas !");
              continue; // Skip push jika kondisi terpenuhi
            }

            // console.log('regis', item.registrasi.objectkelompokpasienlastfk);

            // console.log('fornas', element2.fornas);

            // console.log('bangsat', ID_PASIEN.objectkebangsaanfk);

            // console.log('objectdetail', element2.objectdetailjenisprodukfk);

            item.kekuatan = element2.kekuatan !== undefined && element2.kekuatan > 0
              ? element2.kekuatan
              : 1;

              
              console.log('qty histori',m_historippra.value)

              let qtyfilter=0

             if(m_historippra.value.length > 0 && isAstorBM.value == true){
              for (let  b= 0; b < m_historippra.value.length; b++) {
                const xxx = m_historippra.value[b];
                if(element2.objectgenerikfk == xxx.objectgenerikfk){
                  qtyfilter=xxx.qtyorder
                }
              }
             }

            listChecked.value.push({
              norec: element2.norec,
              jeniskemasanfk: item.jenisKemasan.id,
              jeniskemasan: item.jenisKemasan.jeniskemasan,
              namaproduk: element2.namaproduk,
              kekuatan: element2.kekuatan,
              id: element2.id,
              tgl: element2.tgl,
              objectasalprodukfk: element2.objectasalprodukfk,
              asalproduk: element2.asalproduk,
              harganetto: element2.harganetto,
              hargadiscount: element2.hargadiscount,
              objectdetailjenisprodukfk: element2.objectdetailjenisprodukfk,
              hargajual: Math.ceil(element2.hargajual),
              persenhargajualproduk: element2.persenhargajualproduk,
              qtyproduk: element2.qtyproduk,
              totalstok: Math.ceil(element2.totalstok),
              qtymax: Math.max(0, parseInt(element2.qtymax) - parseInt(qtyfilter)),
              objectruanganfk: element2.objectruanganfk,
              nostrukterimafk: element2.nostrukterimafk,
              tglkadaluarsa: element2.tglkadaluarsa,
              persenup: element2.persenup,
              norec_spd: element2.norec_spd,
              fornas: element2.fornas,
              satuanstandar: element2.satuanstandar,
              nilaikonversi: element2.nilaikonversi,
              satuanstandarfk: element2.satuanstandarfk,
              namaprodukuse: element2.namaprodukuse,
              objectgenerikfk:element2.objectgenerikfk
            })
          }
          console.log('listchecked data',listChecked.value)
        }
      } else {
        for (var i = 0; i < d_ProdukDef.value.length; i++) {
          const element2 = d_ProdukDef.value[i];
          if (element2.id == element.id) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.namaproduk == element2.namaproduk) {
                listChecked.value.splice(z, 1)
              }
            }
          }
        }
      }
    }
    add()
  }
}

const filteredLayanan = computed(() => {
  // if (!filterLayanan.value) {
  //     return d_Produk.value
  // }
  var filtered: any = [];
  for (let i = 0; i < d_Produk.value.length; i++) {
    const element2 = d_Produk.value[i];
    if (filterLayanan.value) {
      if (element2.namaproduk.match(new RegExp(filterLayanan.value, 'i'))) {
        filtered.push({
          norec: element2.norec,
          namaproduk: element2.namaproduk,
          id: element2.id,
          tgl: element2.tgl,
          objectasalprodukfk: element2.objectasalprodukfk,
          asalproduk: element2.asalproduk,
          harganetto: element2.harganetto,
          hargadiscount: element2.hargadiscount,
          hargajual: element2.hargajual,
          persenhargajualproduk: element2.persenhargajualproduk,
          qtyproduk: element2.qtyproduk,
          objectruanganfk: element2.objectruanganfk,
          nostrukterimafk: element2.nostrukterimafk,
          tglkadaluarsa: element2.tglkadaluarsa,
          persenup: element2.persenup,
          norec_spd: element2.norec_spd,
          satuanstandar: element2.satuanstandar,
          nilaikonversi: element2.nilaikonversi,
          satuanstandarfk: element2.satuanstandarfk,
          namaprodukuse: element2.namaprodukuse,
          totalstok: element2.totalstok,
          qtymax: element2.qtymax,
          fornas: element2.fornas,
          objectdetailjenisprodukfk: element2.objectdetailjenisprodukfk,
        })
      }
    } else {
      filtered.push({
        norec: element2.norec,
        namaproduk: element2.namaproduk,
        id: element2.id,
        tgl: element2.tgl,
        objectasalprodukfk: element2.objectasalprodukfk,
        asalproduk: element2.asalproduk,
        harganetto: element2.harganetto,
        hargadiscount: element2.hargadiscount,
        hargajual: element2.hargajual,
        persenhargajualproduk: element2.persenhargajualproduk,
        qtyproduk: element2.qtyproduk,
        objectruanganfk: element2.objectruanganfk,
        nostrukterimafk: element2.nostrukterimafk,
        tglkadaluarsa: element2.tglkadaluarsa,
        persenup: element2.persenup,
        norec_spd: element2.norec_spd,
        satuanstandar: element2.satuanstandar,
        nilaikonversi: element2.nilaikonversi,
        qtymax: element2.qtymax,
        satuanstandarfk: element2.satuanstandarfk,
        namaprodukuse: element2.namaprodukuse,
        fornas: element2.fornas,
        objectdetailjenisprodukfk: element2.objectdetailjenisprodukfk,
      })

    }

  }
console.log('produk', filtered)
  dataProductTampil.value = filtered;
  return filtered;


})

const onProductSelected = async (event) => {
  item.produkCeklis[event.data.id]
  getSelected()
};
const onProductUnSelected = async (event) => {
    // await deleteItem(event.data.id);
    clearSelectionItem(event.data)
}


const clearSelection = () => {
  var arrobj = Object.keys(item.produkCeklis)
  for (let x = 0; x < arrobj.length; x++) {
    const element2 = arrobj[x];
    item.produkCeklis[element2] = false
  }
  getSelected()
}
const clearSelectionItem = (select: any) => {
  var arrobj = Object.keys(item.produkCeklis)
  for (let x = 0; x < arrobj.length; x++) {
    const element2 = arrobj[x];
    if (element2 == select.id) {
      item.produkCeklis[element2] = false
    }
  }
  getSelected()
}

// const fetchTindakan = (e: any) => {
//   isLoading.value = true;
//   var filterSerch = "";
//   var filterSerch2 = "";

//   if (e != undefined) {
//     filterSerch = e[0].nama;
//     filterSerch2 = e[0].id;
//   }

//   useApi()
//     .get(
//       `farmasi/get-produkdetail-ceklis?ruanganfk=${item.ruangan.id}&limit=10&namaproduk=${filterSerch}&kpid=${item.registrasi.objectkelompokpasienlastfk}&nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}`
//     )
//     .then((response: any) => {
//       isLoading.value = false;
//       const filteredResponse = response.filter(
//         (item: any) => item.objectsubkategoryfk !== 8
//       );
//       d_ProdukDef.value = filteredResponse;
//       d_Produk.value = filteredResponse;
//     });
// };

const fetchTindakan = (e: any) => {
  isLoading.value = true
  var filterSerch = ""
  var filterSerch2 = ""
  
  // let isInacbgs = item.isinacbgs ? "true" : "false";

  if (e != undefined) {
    filterSerch = e[0].nama,
      filterSerch2 = e[0].id
  }
  useApi().get(
    `farmasi/get-produkdetail-ceklis?ruanganfk=${item.ruangan.id}&limit=10&namaproduk=${filterSerch}&kpid=${item.registrasi.objectkelompokpasienlastfk}&nocmfk=${ID_PASIEN}&norec_apd=${item.NOREC_APD}&jenisobat=${item.antibiotik}`)
    .then((response: any) => {
      isLoading.value = false
      d_ProdukDef.value = response
      d_Produk.value = response
    })
}


const filterProdukna = (e: any) => {
  
  var filteredDatana: any = [];
  if (item.namaprodukserch != undefined && item.namaprodukserch != '') {
    filteredDatana.push({
      nama: item.namaprodukserch,
      id: ""
    })
  } else if (item.kodeprodukserch != undefined && item.kodeprodukserch != '') {
    filteredDatana.push({
      nama: "",
      id: item.kodeprodukserch
    })
  } else if (item.kodeprodukserch != undefined && item.namaprodukserch != undefined && item.kodeprodukserch != '' && item.namaprodukserch != '') {
    filteredDatana.push({
      nama: item.namaprodukserch,
      id: item.kodeprodukserch
    })
  } else {
    filteredDatana.push({
      nama  : "",
      id: ""
    })
  }

  console.log('ini apa', item.JENISOBAT)
  if(item.JENISOBAT == 'Obat Biasa')
  {
    fetchTindakan(filteredDatana)
  }

  else if(item.JENISOBAT == 'Obat Profilaksis')
  {
    if(!item.jenisoperasi)
    {
      useToaster().error(`Jenis Operasi Belum Dipilih !`);
      return;  
    }
    else 
    {
      fetchTindakan2(filteredDatana)  
    }
  }

  else if(item.JENISOBAT == 'Obat Empiris')
  {
    if(!item.jenistindakan)
    {
      useToaster().error(`Jenis Tindakan / KSM Belum Dipilih !`);
      return;
    }
    else 
    {
      fetchTindakan1(filteredDatana)
    }
  }

  else if(item.JENISOBAT == 'Obat Empiris Reserve')
  {
    
    if(!item.jenistindakan)
    {
      if (!item.jenistindakan) 
      {
        item.jenistindakan = {}; 
      }
        item.jenistindakan.id = 3;
        fetchTindakan1(filteredDatana)
    }
  }

  else if(item.JENISOBAT == 'Obat Definitif')
  {
    fetchTindakan(filteredDatana)
  }
  
  // fetchTindakan1(filteredDatana)
}

const handleJumlahObatChange = () => {
  getSelisih({
    data: item.value,
    field: "jumlahobat",
    newValue: item.value.jumlahobat,
  });
};


const getSelisih = (event: any) => {
  let { data, field, newValue } = event
  console.log('masuk ke functon get seleisih')
  // data.jumlah
  if (field == 'jumlah') {
    data.jumlah = newValue;
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == data.no) {
        element.jumlah = newValue,
          element.total = element.hargajual * newValue
      }
    }
  } else if (field == 'aturanpakai') {
    data.aturanpakai = newValue;
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == data.no) {
        element.aturanpakai = newValue
      }
    }
  } else if (field == 'satuanresep') {
    data.satuanresepfk = item.satuanresep1 ? item.satuanresep1.id : null;
    data.satuanresep = item.satuanresep1 ? item.satuanresep1.satuanresep : null;
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == data.no) {
        element.satuanresepfk = item.satuanresep1 ? item.satuanresep1.id : null,
          element.satuanresep = item.satuanresep1 ? item.satuanresep1.satuanresep : null
      }
    }
  } else if (field == 'jumlahobat') {
    isLoading.value = true
    useApi().get('/farmasi/get-produkdetail?produkfk=' + data.produkfk + '&ruanganfk=' + item.ruangan.id +
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
          isLoading.value = false
        }
      });
    data.jumlahobat = newValue

    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == data.no) {
        element.jumlahobat = newValue
      }
    }
  } else if (field == 'dosis') {
    console.log('Field is "dosis". Updating dosis...');
    data.dosis = newValue;
    console.log('Updated data.dosis:', data.dosis);

    for (let x = 0; x < data2.value.length; x++) {
      var jumlahRacikannya = 0;
      const element = data2.value[x];
      if (element.no == data.no) {
        jumlahRacikannya = (parseFloat(data.jumlahobat) * parseFloat(data.dosis));
        console.log('Calculated jumlahobat:', data.jumlahobat);
        console.log('Calculated dosis:', data.dosis);
        console.log('Calculated racikan:', jumlahRacikannya);
        element.dosis = newValue;
        // data.jumlah = jumlahRacikannya / kekuatan;
        data.jumlah = jumlahRacikannya / parseFloat(data.kekuatan);
        console.log(`Updated element ${x}:`, element);
        console.log('Calculated racikan:', jumlahRacikannya);
        console.log('Calculated kekuatan:', data.kekuatan);
        console.log('Calculated jumlah:', data.jumlah);
      }
    }
  }  else if (field == 'rke') {
    data.rke = newValue

    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == data.no) {
        element.rke = newValue

      }
    }
  }
  else if (field == 'jenisobat') {

    data.jenisobatfk = item.jenisRacikan1 ? item.jenisRacikan1.id : null
    data.jenisobat = item.jenisRacikan1 ? item.jenisRacikan1.jenisracikan : null

    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == data.no) {
        element.jenisobatfk = item.jenisRacikan1 ? item.jenisRacikan1.id : null
        element.jenisobat = item.jenisRacikan1 ? item.jenisRacikan1.jenisracikan : null

      }
    }
  }



  console.log('Final data:', data);
  // console.log(event)
}


let isPaketObatSelected = false; 
const calculateJumlah = (item) => {
  // if (isPaketObatSelected) {
  //   console.log('masuk paket');
  //   return item.jumlah || 0; 
  // } else {
  console.log(item.dosispecahan)
    if (item.jeniskemasanfk === 1) {
      console.log('masuk perhitungan');
      if(item.dosispecahan != undefined && item.dosispecahan != null){
        return parseFloat(item.dosispecahan)*parseFloat(item.jumlahobat)
      } else{
        return (parseFloat(item.jumlahobat) * parseFloat(item.dosis)) / parseFloat(item.kekuatan || 1) || 0;
      }
    } else {
      console.log('default jumlah');
      return item.jumlah || 1;
    }
  // }
};

const calculateJumlahDosis = (item) => {
  // if (isPaketObatSelected) {
  //   console.log('masuk paket');
  //   return item.jumlah || 0; 
  // } else {
  console.log(item.dosispecahan)
  return parseFloat(item.dosispecahan)*parseFloat(item.kekuatan || 1)
  // }
};

watchEffect(() => {
  if (!isPaketObatSelected) {
    dataSource.value.forEach((item) => {
      if (!item.isPaket && item.jeniskemasanfk === 1) {
        if(item.dosispecahan != undefined && item.dosispecahan != null){
          item.dosis = calculateJumlahDosis(item);
        }
        item.jumlah = calculateJumlah(item);
        item.totalharga = (item.jumlah || 0) * (item.hargajual || 0);
      }
    });
  }
       item.grandtotal = dataSource.value.reduce((sum, item) => sum + (item.totalharga || 0), 0);
});


// allz
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/emr/order-resep.scss';
</style>
