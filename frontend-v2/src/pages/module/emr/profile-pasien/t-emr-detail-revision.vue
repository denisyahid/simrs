<template>
    <VCard radius="rounded">

      <div class="columns is-multiline is-mobile">
        <div class="column is-6 p-3">
          <h3 class="title is-5 mb-2 mr-1">Dashboard </h3>
        </div>
        <div class="column is-6  p-3">
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
        </div>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <!-- Asesmen awal -->
              <VCard radius="small" class="mt-4" elevated>
                <div class="card-head">
                  <h3 class="title is-5 mb-2">
                    Asessmen Awal Medis ( {{ hariFormAsmed != null ? hariFormAsmed + ' Hari' : 'Belum ada riwayat Asesmen Medis' }} )
                  </h3>

                  <VButton color="info" rounded raised size="small" @click="() => { emits('onTabMedis') }">
                    Riwayat Asessmen Awal Medis
                  </VButton>
                </div>

                <div class="single-accordion is-exclusive mt-3">
                  <details class="accordion-item" :class="openAccordion[0] ? 'is-active' : ''"
                  :open="openAccordion[0] ?? undefined">
                    <summary class="accordion-header" tabindex="0" @keydown.space.prevent="() => toggleAccordion(0)"
                    @click.prevent="() => toggleAccordion(0)">
                      Buka Informasi
                    </summary>
                    <div class="accordion-content columns is-multiline">
                      <div class="card-inner pt-0">
                        <div class="columns">
                          <div class="column is-6">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:heart-pulse" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Status Assesment </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenAwal" />
                                <span v-else>
                                  <VTag
                                    v-if="dataAsesmen.namalengkap != null || dataAsesmen.namalengkap != undefined"
                                    color="success"
                                    label="Pernah Diperiksa"
                                    rounded
                                    elevated
                                  />
                                  <template v-else>
                                    Belum Ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                          <div class="column is-6">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:calendar" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Tanggal Input Assesmen </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenAwal" />
                                <span v-else>
                                  <template v-if="dataAsesmen.tglregistrasi != null || dataAsesmen.tglregistrasi != undefined">
                                    {{ H.formatDate(dataAsesmen.tglregistrasi, 'YYYY-MM-DD') }}
                                  </template>
                                  <template v-else>
                                    Belum ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                        </div>
                        <div class="columns">
                          <div class="column is-6">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:calendar-clock" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Tanggal Pasien Diperiksa Terakhir </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenAwal" />
                                <span v-else>
                                  <template v-if="dataAsesmen.tglemr != null || dataAsesmen.tglemr != undefined">
                                    {{ H.formatDate(dataAsesmen.tglemr, 'YYYY-MM-DD') }}
                                  </template>
                                  <template v-else>
                                    Belum ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                          <div class="column is-6">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:stethoscope" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Dokter </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenAwal" />
                                <span v-else>
                                  <template v-if="dataAsesmen.namalengkap != null || dataAsesmen.namalengkap != undefined">
                                    {{ dataAsesmen.namalengkap }}
                                  </template>
                                  <template v-else>
                                    Belum ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                        </div>
                        <div class="columns">
                          <div class="column is-12">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:library" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Section </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenAwal" />
                                <span v-else>
                                  <template v-if="dataAsesmen.namaruangan != null || dataAsesmen.namaruangan != undefined">
                                    {{ dataAsesmen.namaruangan }}
                                  </template>
                                  <template v-else>
                                    Belum ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                        </div>
                      </div>
                    </div>
                  </details>
                </div>
              </VCard>

              <!-- Asesmen Keperawatan -->
              <VCard radius="small" class="mt-4" elevated>
                <div class="card-head">
                  <h3 class="title is-5 mb-2">
                    Asessmen Keperawatan ( {{ hariFormAskep != null ? hariFormAskep + ' Hari' : 'Belum ada riwayat Asesmen Keperawatan' }} )
                  </h3>

                  <VButton color="info" rounded raised size="small"  @click="() => { emits('onTabKeperawatan') }">
                    Riwayat Asessmen Awal Perawat
                  </VButton>
                </div>

                <div class="single-accordion is-exclusive mt-3">
                  <details class="accordion-item" :class="openAccordion[1] ? 'is-active' : ''"
                  :open="openAccordion[1] ?? undefined">
                    <summary class="accordion-header" tabindex="0" @keydown.space.prevent="() => toggleAccordion(1)"
                    @click.prevent="() => toggleAccordion(1)">
                      Buka Informasi
                    </summary>
                    <div class="accordion-content columns is-multiline">
                      <div class="card-inner pt-0">
                        <div class="columns">
                          <div class="column is-6">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:heart-pulse" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Status Assesment </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenMedis" />
                                <span v-else>
                                  <VTag
                                    v-if="dataAsesmenPerawat.namalengkap != null || dataAsesmenPerawat.namalengkap != undefined"
                                    color="success"
                                    label="Pernah Diperiksa"
                                    rounded
                                    elevated
                                  />
                                  <template v-else>
                                    Belum Ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                          <div class="column is-6">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:calendar" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Tanggal Input Assesmen </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenMedis" />
                                <span v-else>
                                  <template v-if="dataAsesmenPerawat.tglregistrasi != null || dataAsesmenPerawat.tglregistrasi != undefined">
                                    {{ H.formatDate(dataAsesmenPerawat.tglregistrasi, 'YYYY-MM-DD') }}
                                  </template>
                                  <template v-else>
                                    Belum ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                        </div>
                        <div class="columns">
                          <div class="column is-6">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:calendar-clock" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Tanggal Pasien Diperiksa Terakhir </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenMedis" />
                                <span v-else>
                                  <template v-if="dataAsesmenPerawat.tglemr != null || dataAsesmenPerawat.tglemr != undefined">
                                    {{ H.formatDate(dataAsesmenPerawat.tglemr, 'YYYY-MM-DD') }}
                                  </template>
                                  <template v-else>
                                    Belum ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                          <div class="column is-6">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:stethoscope" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Suster </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenMedis" />
                                <span v-else>
                                  <template v-if="dataAsesmenPerawat.namalengkap != null || dataAsesmenPerawat.namalengkap != undefined">
                                    {{ dataAsesmenPerawat.namalengkap }}
                                  </template>
                                  <template v-else>
                                    Belum ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                        </div>
                        <div class="columns">
                          <div class="column is-12">
                            <VBlock>
                              <template #icon>
                                <VIconBox color="success" rounded>
                                  <VIcon icon="lucide:library" style="line-height: 0;"/>
                                </VIconBox>
                              </template>
                              <template #title>
                                <span> Section </span>
                                <VPlaceloadText :lines="1" width="50%" v-if="loadingAsesmenMedis" />
                                <span v-else>
                                  <template v-if="dataAsesmenPerawat.namaruangan != null || dataAsesmenPerawat.namaruangan != undefined">
                                    {{ dataAsesmenPerawat.namaruangan }}
                                  </template>
                                  <template v-else>
                                    Belum ada
                                  </template>
                                </span>
                              </template>
                            </VBlock>
                          </div>
                        </div>
                      </div>
                    </div>
                  </details>
                </div>
              </VCard>

              <!-- Pemeriksaan Penunjang -->
              <VCard radius="small" class="mt-4" elevated>
                <div class="card-head">
                  <h3 class="title is-5 mb-2">
                    Pemeriksaan Penunjang
                  </h3>
                </div>

                <div class="card-inner pt-0">
                  <div class="column is-12">
                    <div class="single-accordion is-exclusive">
                      <details class="accordion-item" :class="openAccordion[2] ? 'is-active' : ''"
                      :open="openAccordion[2] ?? undefined">
                        <summary class="accordion-header" tabindex="0" @keydown.space.prevent="() => toggleAccordion(2)" 
                        @click.prevent="() => toggleAccordion(2)">
                          Hasil Baca Radiologi
                        </summary>
                        <div class="accordion-content">
                          <div class="columns is-multiline">
                            <div class="columns">
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Nomor Order </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingRadiologi" />
                                    <span v-else>
                                      <template v-if="dataRadiologi.noorder != null || dataRadiologi.noorder != undefined">
                                        {{ dataRadiologi.noorder }}
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Tanggal </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingRadiologi" />
                                    <span v-else>
                                      <template v-if="dataRadiologi.tglorder != null || dataRadiologi.tglorder != undefined">
                                        {{ H.formatDate(dataRadiologi.tglorder) }}
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Status </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingRadiologi" />
                                    <span v-else>
                                      <template v-if="dataRadiologi.status != null || dataRadiologi.status != undefined">
                                        <VTag
                                        :color="dataRadiologi.color"
                                        :label="dataRadiologi.status"
                                        rounded
                                        elevated
                                      />
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                            </div>
                            <div class="column is-12">
                              <VButton
                                class="is-pulled-right"
                                color="primary"
                                icon="fas fa-arrow-right"
                                size="small"
                                raised
                                light
                                @click="() => { emits('onTabRadiologi') }"
                              >
                                Selengkapnya
                              </VButton>
                            </div>
                          </div>
                        </div>
                      </details>
                      <details class="accordion-item" :class="openAccordion[3] ? 'is-active' : ''" 
                      :open="openAccordion[3] ?? undefined">
                        <summary class="accordion-header" tabindex="0" @keydown.space.prevent="() => toggleAccordion(3)"
                          @click.prevent="() => toggleAccordion(3)">
                          Hasil Laboratorium
                        </summary>
                        <div class="accordion-content">
                          <div class="columns is-multiline">
                            <div class="columns">
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Nomor Order </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingLabo" />
                                    <span v-else>
                                      <template v-if="dataLabo.noorder != null || dataLabo.noorder != undefined">
                                        {{ dataLabo.noorder }}
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Tanggal </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingLabo" />
                                    <span v-else>
                                      <template v-if="dataLabo.tglorder != null || dataLabo.tglorder != undefined">
                                        {{ H.formatDate(dataLabo.tglorder) }}
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Status </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingLabo" />
                                    <span v-else>
                                      <template v-if="dataLabo.status != null || dataLabo.status != undefined">
                                        <VTag
                                        :color="dataLabo.color"
                                        :label="dataLabo.status"
                                        rounded
                                        elevated
                                      />
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                            </div>
                            <div class="column is-12">
                              <VButton
                                class="is-pulled-right"
                                color="primary"
                                icon="fas fa-arrow-right"
                                size="small"
                                raised
                                light
                                @click="() => { emits('onTabLaboratorium') }"
                              >
                                Selengkapnya
                              </VButton>
                            </div>
                          </div>
                        </div>
                      </details>
                    </div>
                  </div>
                </div>
                <VAccordion />
              </VCard>


              <VCard radius="small" class="mt-4" elevated>
                <div class="card-inner pt-0">
                  <div class="column is-12">
                    <div class="single-accordion is-exclusive">
                      <div>
                        <summary class="accordion-header">
                          Tindakan
                          <div class="update-item is-dark-bordered-12 mt-5">
                            <span class="subtitle" v-for="(item, index) in props.riwayat.LIST_TINDAKAN" style="font-size: 10pt;"> # {{ item.namaproduk }}</span>
                          </div>
                        </summary>
                      </div>
                      <div>
                        <summary class="accordion-header">
                          E-Resep
                          <div class="update-item is-dark-bordered-12 mt-5">
                            <span class="subtitle" v-for="(item, index) in props.riwayat.LIST_RESEP" style="font-size: 10pt;"> # {{ item.namaproduk }}</span>
                          </div>
                        </summary>
                      </div>
                      <div>
                        <summary class="accordion-header">
                          BHP
                        </summary>
                      </div>
                    </div>
                  </div>
                </div>
                <VAccordion />
              </VCard>

              <VCard radius="small" class="mt-4" elevated>
                <div class="card-inner pt-0">
                  <div class="column is-12">
                    <div class="single-accordion is-exclusive">
                      <details class="accordion-item" :class="openAccordion[2] ? 'is-active' : ''"
                      :open="openAccordion[2] ?? undefined">
                        <summary class="accordion-header" tabindex="0" @keydown.space.prevent="() => toggleAccordion(2)" 
                        @click.prevent="() => toggleAccordion(2)">
                          Resume Pasien Rawat Jalan
                        </summary>
                        <div class="accordion-content">
                          <div class="columns is-multiline">
                            <div class="columns">
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Nomor Order </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingRadiologi" />
                                    <span v-else>
                                      <template v-if="dataRadiologi.noorder != null || dataRadiologi.noorder != undefined">
                                        {{ dataRadiologi.noorder }}
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Tanggal </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingRadiologi" />
                                    <span v-else>
                                      <template v-if="dataRadiologi.tglorder != null || dataRadiologi.tglorder != undefined">
                                        {{ H.formatDate(dataRadiologi.tglorder) }}
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                              <div class="column is-12">
                                <VBlock>
                                  <template #title>
                                    <span> Status </span>
                                    <VPlaceloadText :lines="1" width="50%" v-if="loadingRadiologi" />
                                    <span v-else>
                                      <template v-if="dataRadiologi.status != null || dataRadiologi.status != undefined">
                                        <VTag
                                        :color="dataRadiologi.color"
                                        :label="dataRadiologi.status"
                                        rounded
                                        elevated
                                      />
                                      </template>
                                      <template v-else>
                                        Belum ada
                                      </template>
                                    </span>
                                  </template>
                                </VBlock>
                              </div>
                            </div>
                            <div class="column is-12">
                              <VButton
                                class="is-pulled-right"
                                color="primary"
                                icon="fas fa-arrow-right"
                                size="small"
                                raised
                                light
                                @click="() => { emits('onTabRadiologi') }"
                              >
                                Selengkapnya
                              </VButton>
                            </div>
                          </div>
                        </div>
                      </details>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>
          </div>
        </div>
      </div>
    </VCard>
   
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
    (e: 'onTabMedis'): void,
    (e: 'onTabKeperawatan'): void,
    (e: 'onTabRadiologi'): void,
    (e: 'onTabLaboratorium'): void,
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

  const getRandomArbitrary = (min:any, max:any)=> {
      return Math.random() * (max - min) + min;
  }
  const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
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
  const COLLECTION: any = ref('AsesmenAwalKeperawatanRawatInap') //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const modalInput = ref(false)
  const listColor: any = ref(Object.keys(useThemeColors()))
  const isEmpty = ref(true)
  const loadingAsesmenAwal: any = ref(false);
  const loadingRadiologi: any = ref(false);
  const loadingAsesmenMedis: any = ref(false)
  const loadingLabo: any = ref(false)
  const rowGroupMetadata = ref({})
  const dataAsesmen: any = ref({})
  const dataAsesmenPerawat: any = ref({});
  const dataRadiologi: any = ref({});
  const dataLabo: any = ref({});
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
  const openAccordion: any = ref([]);
  const isLoading: any = ref(false)
  const input: any = reactive({ tanggal: new Date() })
  const disabledJawab = ref(false)
  const d_Ruangan: any = ref([])
  const d_Dokter: any = ref([])

  console.log('props riwayat', props.riwayat)
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

  const fetchAsesmenAwal = async () => {
    loadingAsesmenAwal.value = true;
    dataAsesmen.value = {}
    let params = `&nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`
    await useApi().get(
        `/emr/riwayat-emr?jenis_emr=asesmen_medis${params}`).then((response: any) => {
            loadingAsesmenAwal.value = false;
            if (response.length) {
                dataAsesmen.value = response[0]
                console.log("DATA ASES ALRD TOOK 1")
                console.log(dataAsesmen.value);
            }
        })
  }

  const hariFormAsmed: any = ref('')
  const hariFormAskep: any = ref('')
  const fetchCheckEMR_90DaysAM = async () => {    
    isLoading.value = true
    const responseTglRuanganAM = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=AsesmenMedisRawatJalan`)
    const responseHistoriAM = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=AsesmenMedisRawatJalan`)
    isLoading.value = false
    if (responseTglRuanganAM.length && responseHistoriAM.length) {
      console.log("Ruangan dulu : " + responseTglRuanganAM[0].registrasi.namaruangan)
      var tgl_EMR_terakhir = moment(responseTglRuanganAM[0].created_at).format("DD-MM-YYYY");
      var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
      var tgl_Sekarang = moment();
      const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');

      if (responseTglRuanganAM[0].registrasi.namaruangan.trim() == props.registrasi.namaruangan.trim() && calculateDays < 90) {
        hariFormAsmed.value = calculateDays        
      }
    } else {
      hariFormAsmed.value = null      
    }
  }

  const fetchCheckEMR_90DaysAK = async () => {    
    isLoading.value = true
    const responseTglRuanganAK = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=AsesmenAwalKeperawatanPasienRawatJalan`)
    const responseHistoriAK = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=AsesmenAwalKeperawatanPasienRawatJalan`)
    isLoading.value = false
    if (responseTglRuanganAK.length && responseHistoriAK.length) {
      console.log("Ruangan dulu : " + responseTglRuanganAK[0].registrasi.namaruangan)
      var tgl_EMR_terakhir = moment(responseTglRuanganAK[0].created_at).format("DD-MM-YYYY");
      var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
      var tgl_Sekarang = moment();
      const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');

      if (responseTglRuanganAK[0].registrasi.namaruangan.trim() == props.registrasi.namaruangan.trim() && calculateDays < 90) {
        hariFormAskep.value = calculateDays
      }
    } else {
      hariFormAskep.value = null      
    }
  }
  fetchCheckEMR_90DaysAM()
  fetchCheckEMR_90DaysAK()

  const fetchKeperawatan = async () => {
    loadingAsesmenMedis.value = true;
    dataAsesmenPerawat.value = {}
    let params = `&nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`
    await useApi().get(
        `/emr/riwayat-emr?jenis_emr=asesmen_perawat${params}`).then((response: any) => {
          loadingAsesmenMedis.value = false;
            if (response.length) {
                dataAsesmenPerawat.value = response[0]
                console.log("DATA ASES ALRD TOOK 1")
                console.log(dataAsesmenPerawat.value);
            }
        })
}

  const toggleAccordion = (index: any) => {
    if(openAccordion.value.indexOf(index)) {
      openAccordion.value[index] = !openAccordion.value[index];
      switch (index) {
        case 0:
          fetchAsesmenAwal()
          break;
        case 1:
          fetchKeperawatan()
          break;
        case 2:
          fetchRiwayatRadiologi()
          break;
        case 3:
          fetchRiwayatLabo()
          break;

        default:
          console.log("nothing");
          break;
      }
      return;
    }
    openAccordion.value.push(index);    
  }

  const fetchRiwayatRadiologi = () => {
    loadingRadiologi.value = true;
    dataRadiologi.value = {}
    let nocm = props.pasien ? props.pasiem : dataPasien.value.nocm
    let noregistrasi = props.registrasi ? props.registrasi.registrasi : dataAdditional.registrasi.noregistrasi
    useApi().get(
        `/radiologi/riwayat-order?nocmfk=${ID_PASIEN}&nocm=${nocm}&norec_pd=${NOREC_PD}&noregistrasi=${noregistrasi}`).then((response: any) => {
          console.log("LOAD RADIOLOGI");
          console.log(response);
          console.log(Object.keys(dataRadiologi))
          loadingRadiologi.value = false;
            if(response.length > 0) {
              let z = 0
              for (let x = 0; x < response.length; x++) {
                  const element = response[x];
                  element.icon = 'fa fa-radiation'
                  element.color = listColor2.value[z]
                  element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
                  if (z > 4) {
                      z = 0
                  }
                  z++
              }
              dataRadiologi.value = response[0]
            }
        })
    }

    const fetchRiwayatLabo = () => {
      loadingLabo.value = true;
      dataLabo.value = []
      useApi().get(
        `/laboratorium/riwayat-order?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`).then((response: any) => {
          loadingLabo.value = false;
            let z = 0
            if(response.length > 0) {
              for (let x = 0; x < response.length; x++) {
                  const element = response[x];
                  element.icon = 'lnir lnir-flask-alt'
                  element.color = listColor2.value[z]
                  element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
                  if (z > 4) {
                      z = 0
                  }
                  z++
              }
              dataLabo.value = response[0];
            }
        })
    }

    const pasienByID = (id: any) => {
      if(props.pasien != undefined) {
        pasien.value = props.pasien
        dataAdditional.NOREC_APD = props.registrasi.norec_apd
        dataAdditional.RUANGAN_LAST = props.registrasi.objectruanganlastfk
        dataAdditional.registrasi = props.registrasi
      }else {
        useApi().get(
          `/general/header-pasien?nocmfk=${id}&norec_pd=${NOREC_PD}`).then((response: any) => {
              dataPasien.value = response.pasien
              dataAdditional.NOREC_APD = response.last_registrasi.norec_apd
              dataAdditional.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
              dataAdditional.registrasi = response.last_registrasi
          })
      }
    }
  
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
  //   fetchAsesmenAwal();
  // });

  // pasienByID(ID_PASIEN);
  </script>
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  
  @import '/@src/scss/custom/timeline-css';
  @import '/@src/scss/module/emr/emr-detail';
  
  .project-files {
    padding: 0;
  }

  </style>
  