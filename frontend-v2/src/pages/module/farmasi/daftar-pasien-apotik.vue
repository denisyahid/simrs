
<template>
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
  <VCard v-if="activeValue == 1">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Daftar Pasien </h3> <span> ( {{ ds_PASIEN.total }}
        Totals)</span>
    </div>
    <div class="columns  all-projects m-3 mt-0">
      <div class="columns is-multiline  projects-card-grid">
        <div class="column is-8">
          <div class="flex-list-inner mb-4" v-if="ds_PASIEN.loading">
            <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
              <VFlexTableCell :column="{ grow: true, media: true }">
                <VPlaceloadAvatar size="medium" />
                <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
              </VFlexTableCell>
              <VFlexTableCell :column="{ align: 'end' }">
                <VPlaceload width="10%" class="mx-1" />
              </VFlexTableCell>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="ds_PASIEN.length === 0">
            <VCard>
              <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderSection>
            </VCard>
          </div>
          <div v-else-if="ds_PASIEN.length > 0">
            <div class="grid-item mb-4" v-for="(items, i) in ds_PASIEN" :key="items.id">
              <div class="top-section">
                <div class="head">
                  <div class="title-wrap">
                    <div class="columns">
                      <div class="column is-3">
                        <VAvatar size="small" :color="listColor[i]" :initials="items.initials" />
                      </div>
                      <div class="column is-12 mr-3">
                        <h3>{{ items.namapasien }}</h3>
                        <p>{{ items.nocm + (items.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)')
                        }}</p>
                      </div>
                    </div>
                  </div>
                  <!-- <ProjectCardDropdown /> -->
                </div>
                <div class="body">
                  <div class="columns">
                    <div class="column">
                      <h4 class="heading">Registrasi</h4>
                      <p class="fs-075">Nomor : {{ items.noregistrasi }}</p>
                      <p class="fs-075">Nomor Asuransi: {{ items.nobpjs }}</p>
                      <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p>
                    </div>
                    <div class="column">
                      <h4 class="heading">Kelompok Pasien</h4>
                      <p class="fs-075">Kelompok Pasien: {{ items.kelompokpasien }}</p>
                      <p class="fs-075">Kelas : {{ items.namakelas }}</p>
                    </div>
                    <div class="column">
                      <h4 class="heading">Tanggal Pulang</h4>
                      <p class="fs-075">Pulang : {{ items.tglpulang }}</p>
                      <p class="fs-075">Ruangan : {{ items.namaruangan }}</p>

                    </div>
                    <div class="column">
                      <h4 class="heading">Umur</h4>
                      <VTag color="info" :label="items.umur" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="bottom-section">
                <div class="foot-block">
                  <h4 class="heading">Action</h4>
                  <div class="developers">
                    <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="warning" outlined
                      raised @click="transaksiPelayanan(items)">
                      Transaksi Resep </VButton>
                        <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3"
                                            color="blue" outlined raised :to="{
                                                name: 'module-emr-profile-pasien',
                                                query: {
                                                nocmfk: items.nocmfk,
                                                norec_pd: items.norec_pd,
                                                },
                                            }">
                                            EMR </VButton>
                    <!-- <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3"
                                            color="info" outlined raised>
                                            Edit </VButton>
                                        <VButton type="button" icon="feather:trash" class="is-fullwidth mr-3"
                                            color="danger" outlined raised>
                                            Delete </VButton>
                                        <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3"
                                            color="warning" outlined raised>
                                            Riwayat </VButton> -->
                  </div>
                </div>
              </div>

            </div>
          </div>
          <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
            :total-items="totalPasien" :max-links-displayed="5">
            <template #before-pagination>
            </template>
            <template #before-navigation>
              <VFlex class="mr-4 mt-1" column-gap="1rem">
                <VField>

                </VField>
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
              </VFlex>
            </template>
          </VFlexPagination>
          <!-- <VFlexPagination v-model:current-page="currentPage.page" class="mt-6" :item-per-page="currentPage.limit"
            :total-items="currentPage.rows" :max-links-displayed="10" /> -->

        </div>
        <div class="column is-4">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VControl icon="feather:search">
                  <input v-model="item.qnama" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                    placeholder="Filter Nama..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h3 class="title is-5 mb-2 mr-1">Filters </h3>
            </div>
            <div class="column is-6">
              <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                All </a>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>Periode</VLabel>
                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
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
            <div class="column is-12">
              <VField>
                <VLabel>No RM</VLabel>
                <VControl icon="feather:user">
                  <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="filter()" placeholder="No RM" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>No Regisrasi</VLabel>
                <VControl icon="feather:book">
                  <VInput type="text" v-model="item.qnoregistrasi" v-on:keyup.enter="filter()"
                    placeholder="No Regisrasi" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VLabel>Ruangan</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.ruanganfk" placeholder="Pilih Ruangan" :searchable="true"
                    :filter-results="false" :min-chars="0" :resolve-on-load="true" :delay="0" :options="async function (query: any) {
                      return await fetchRuangan(query)
                    }" autocomplete="off" />

                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VLabel>Kelompok Pasien</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.qkelompokpasien" :options="d_Raungan" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VButton @click="filter()" :loading="ds_PASIEN.loading" type="button" icon="feather:search"
                class="is-fullwidth mr-3" color="info" raised> Apply Filters
              </VButton>
            </div>
          </div>

        </div>
      </div>
    </div>

  </VCard>
  <VCard v-if="activeValue == 2">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Daftar Pasien </h3> <span> ( {{ ds_PASIENRANAP.total }}
        Totals)</span>
    </div>
    <div class="columns  all-projects m-3 mt-0">
      <div class="columns is-multiline  projects-card-grid">
        <div class="column is-8">
          <div class="flex-list-inner mb-4" v-if="ds_PASIENRANAP.loading">
            <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
              <VFlexTableCell :column="{ grow: true, media: true }">
                <VPlaceloadAvatar size="medium" />
                <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
              </VFlexTableCell>
              <VFlexTableCell :column="{ align: 'end' }">
                <VPlaceload width="10%" class="mx-1" />
              </VFlexTableCell>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="ds_PASIENRANAP.length === 0">
            <VCard>
              <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderSection>
            </VCard>
          </div>
          <div v-else-if="ds_PASIENRANAP.length > 0">
            <div class="grid-item mb-4" v-for="(items, i) in ds_PASIENRANAP" :key="items.id">
              <div class="top-section">
                <div class="head">
                  <div class="title-wrap">
                    <div class="columns">
                      <div class="column is-3">
                        <VAvatar size="small" :color="listColor[i]" :initials="items.initials" />
                      </div>
                      <div class="column is-12 mr-3">
                        <h3>{{ items.namapasien }}</h3>
                        <p>{{ items.nocm + (items.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)')
                        }}</p>
                      </div>
                    </div>
                  </div>
                  <!-- <ProjectCardDropdown /> -->
                </div>
                <div class="body">
                  <div class="columns">
                    <div class="column">
                      <h4 class="heading">Registrasi</h4>
                      <p class="fs-075">Nomor : {{ items.noregistrasi }}</p>
                      <p class="fs-075">Nomor Asuransi : {{ items.nobpjs }}</p>
                      <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p>
                    </div>
                    <div class="column">
                      <h4 class="heading">Kelompok Pasien</h4>
                      <p class="fs-075">Kelompok Pasien: {{ items.kelompokpasien }}</p>
                      <p class="fs-075">Kelas : {{ items.namakelas }}</p>
                    </div>
                    <div class="column">
                      <h4 class="heading">Tanggal Pulang</h4>
                      <p class="fs-075">Pulang : {{ items.tglpulang }}</p>
                      <p class="fs-075">Ruangan : {{ items.namaruangan }}</p>

                    </div>
                    <div class="column">
                      <h4 class="heading">Umur</h4>
                      <VTag color="info" :label="items.umur" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="bottom-section">
                <div class="foot-block">
                  <h4 class="heading">Action</h4>
                  <div class="developers">
                    <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="warning" outlined
                      raised :to="{
                        name: 'module-farmasi-transaksi-pelayanan-farmasi',
                        query: {
                          norec_pd: items.norec_pd,
                        },
                      }">
                      Transaksi Resep </VButton>
                      <!-- <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3"
                                            color="info" outlined raised>
                                            Edit </VButton>
                                        <VButton type="button" icon="feather:trash" class="is-fullwidth mr-3"
                                            color="danger" outlined raised>
                                            Delete </VButton>
                                        <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3"
                                            color="warning" outlined raised>
                                            Riwayat </VButton> -->
                  </div>
                </div>
              </div>

            </div>
          </div>
          <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
            :total-items="totalPasienRanap" :max-links-displayed="5">
            <template #before-pagination>
            </template>
            <template #before-navigation>
              <VFlex class="mr-4 mt-1" column-gap="1rem">
                <VField>

                </VField>
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
              </VFlex>
            </template>
          </VFlexPagination>
          <!-- <VFlexPagination v-model:current-page="currentPage.page" class="mt-6" :item-per-page="currentPage.limit"
            :total-items="currentPage.rows" :max-links-displayed="10" /> -->

        </div>
        <div class="column is-4">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VControl icon="feather:search">
                  <input v-model="item.qnama" v-on:keyup.enter="filterRanap()" type="text" class="input is-rounded"
                    placeholder="Filter Nama..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h3 class="title is-5 mb-2 mr-1">Filters </h3>
            </div>
            <div class="column is-6">
              <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                All </a>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>No RM</VLabel>
                <VControl icon="feather:user">
                  <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="filterRanap()" placeholder="No RM" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>No Regisrasi</VLabel>
                <VControl icon="feather:book">
                  <VInput type="text" v-model="item.qnoregistrasi" v-on:keyup.enter="filterRanap()"
                    placeholder="No Regisrasi" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VLabel>Ruangan</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.ruanganfk" placeholder="Pilih Ruangan" :searchable="true"
                    :filter-results="false" :min-chars="0" :resolve-on-load="true" :delay="0" :options="async function (query: any) {
                      return await fetchRuangan(query)
                    }" autocomplete="off" />

                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VLabel>Kelompok Pasien</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.qkelompokpasien" :options="d_Raungan" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VButton @click="filterRanap()" :loading="ds_PASIENRANAP.loading" type="button" icon="feather:search"
                class="is-fullwidth mr-3" color="info" raised> Apply Filters
              </VButton>
            </div>
          </div>

        </div>
      </div>
    </div>

  </VCard>
  <VCard v-if="activeValue == 3">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Daftar Permohonan Permintaan Alat </h3> <span> ( {{ ds_Permohonan.total }}
        Totals)</span>
    </div>
    <div class="columns  all-projects m-3 mt-0">
      <div class="columns is-multiline  projects-card-grid">
        <div class="column is-8">
          <div class="flex-list-inner mb-4" v-if="ds_Permohonan.loading">
            <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
              <VFlexTableCell :column="{ grow: true, media: true }">
                <VPlaceloadAvatar size="medium" />
                <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
              </VFlexTableCell>
              <VFlexTableCell :column="{ align: 'end' }">
                <VPlaceload width="10%" class="mx-1" />
              </VFlexTableCell>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="ds_Permohonan.length === 0">
            <VCard>
              <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderSection>
            </VCard>
          </div>
          <div v-else-if="ds_Permohonan.length > 0">
            <div class="grid-item mb-4" v-for="(items, i) in ds_Permohonan" :key="items.id">
              <div class="top-section">
                <div class="head">
                  <div class="title-wrap">
                    <div class="columns">
                      <div class="column is-3">
                        <VAvatar size="small" :color="listColor[i]" :initials="items.initials" />
                      </div>
                      <div class="column is-12 mr-3">
                        <h3>{{ items.namapasien }}</h3>
                        <p>{{ items.nocm + (items.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)')
                        }}</p>
                      </div>
                    </div>
                  </div>
                  <!-- <ProjectCardDropdown /> -->
                </div>
                <div class="body">
                  <div class="columns">
                    <div class="column">
                      <h4 class="heading">Registrasi</h4>
                      <p class="fs-075">Nomor Registrasi: {{ items.noregistrasi }}</p>
                      <p class="fs-075">Nomor Asuransi : {{ items.nobpjs }}</p>
                      <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p>
                    </div>
                    <div class="column">
                      <h4 class="heading">Kelompok Pasien</h4>
                      <p class="fs-075">Kelompok Pasien: {{ items.kelompokpasien }}</p>
                      <p class="fs-075">Kelas : {{ items.namakelas }}</p>
                    </div>
                    <div class="column">
                      <h4 class="heading">Tanggal Pulang</h4>
                      <p class="fs-075">Pulang : {{ items.tglpulang }}</p>
                      <p class="fs-075">Ruangan : {{ items.namaruangan }}</p>

                    </div>
                    <div class="column">
                      <h4 class="heading">Umur</h4>
                      <VTag color="info" :label="items.umur" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="bottom-section">
                <div class="foot-block">
                  <div class="developers">
                    <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="warning" outlined
                      raised :to="{
                        name: 'module-farmasi-transaksi-pelayanan-farmasi',
                        query: {
                          norec_pd: items.norec_pd,
                        },
                      }">
                      Transaksi Resep </VButton>
                      <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3"
                                            color="blue" outlined raised :to="{
                                                name: 'module-emr-profile-pasien',
                                                query: {
                                                nocmfk: items.nocmfk,
                                                norec_pd: items.norec_pd,
                                                norec_apd: items.norec_apd,
                                                },
                                            }">
                                            EMR </VButton>

                                            <VButton 
                                            type="button" 
                                            
                                            icon="lnir lnir-printer"
                                            :disabled="isDisabled" 
                                            @click="printPermohonan(items.nocmfk, items.norec_pd, items.norec_apd, items.norec_emr, items.noregistrasi)">
                                            Cetak
                                          </VButton>

                    <!-- <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3"
                                            color="info" outlined raised>
                                            Edit </VButton>
                                        <VButton type="button" icon="feather:trash" class="is-fullwidth mr-3"
                                            color="danger" outlined raised>
                                            Delete </VButton>
                                        <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3"
                                            color="warning" outlined raised>
                                            Riwayat </VButton> -->
                  </div>
                </div>
              </div>

            </div>
          </div>
          <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
            :total-items="totalPermohonan" :max-links-displayed="5">
            <template #before-pagination>
            </template>
            <template #before-navigation>
              <VFlex class="mr-4 mt-1" column-gap="1rem">
                <VField>

                </VField>
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
              </VFlex>
            </template>
          </VFlexPagination>
          <!-- <VFlexPagination v-model:current-page="currentPage.page" class="mt-6" :item-per-page="currentPage.limit"
            :total-items="currentPage.rows" :max-links-displayed="10" /> -->

        </div>
        <div class="column is-4">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VControl icon="feather:search">
                  <input v-model="item.qnama" v-on:keyup.enter="filterPermohonan()" type="text" class="input is-rounded"
                    placeholder="Filter Nama..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h3 class="title is-5 mb-2 mr-1">Filters </h3>
            </div>
            <div class="column is-4">
              <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                All </a>
            </div>
            <div class="column is-4 switch-filter">
                <VControl>
                  <VSwitchBlock v-model="item.statusnya" label="Semua" color="danger" class="p-0" />
                 </VControl>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>No RM</VLabel>
                <VControl icon="feather:user">
                  <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="filterPermohonan()" placeholder="No RM" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>No Regisrasi</VLabel>
                <VControl icon="feather:book">
                  <VInput type="text" v-model="item.qnoregistrasi" v-on:keyup.enter="filterPermohonan()"
                    placeholder="No Regisrasi" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VLabel>Ruangan</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.ruanganfk" placeholder="Pilih Ruangan" :searchable="true"
                    :filter-results="false" :min-chars="0" :resolve-on-load="true" :delay="0" :options="async function (query: any) {
                      return await fetchRuangan(query)
                    }" autocomplete="off" />

                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VLabel>Kelompok Pasien</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.qkelompokpasien" :options="d_Raungan" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VButton @click="filterPermohonan()" :loading="ds_Permohonan.loading" type="button" icon="feather:search"
                class="is-fullwidth mr-3" color="info" raised> Apply Filters
              </VButton>
            </div>
          </div>

        </div>
      </div>
    </div>

  </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import * as H from "/@src/utils/appHelper";
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
useHead({
  title: 'Daftar Pasien Farmasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  statusnya: false,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  })
})

const emit = defineEmits<{
  (e: 'update:selected', value: string): void
}>()

const activeValue: any = ref(1)
const COLLECTION: any = ref('PermohonanPermintaanAlatKUAO')


const printPermohonan = async (nocmfk, norec_pd, norec_apd, norec_emr, noregistrasi) => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?nocmfk=${nocmfk}&norec_pd=${norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${norec_emr}&noregistrasi=${noregistrasi}&pdf=true`);
    var objSave = {
      'noregistrasifk' : norec_apd,
    }

    useApi().post(
        `/farmasi/update-permohonan-permintaan-alat-kuao`, objSave).then((response: any) => {
        }).catch((e: any) => {
            // isLoading.value = false
        })
    fetchPermohonan()
};

const tabs: any = ref([
  { label: 'Daftar Pasien', value: 1, icon: 'fas fa-list' },
  { label: 'Daftar Pasien Rawat Inap', value: 2, icon: 'fas fa-list' },
  { label: 'Daftar Permohonan Permintaan Alat', value: 3, icon: 'fas fa-list' },
])

const selectedTabs: any = ref()
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
  hilangkanStuck: false
})
const sliderClass = computed(() => {
  if (!props.slider) {
    return ''
  }

  if (props.type === 'rounded') {
    if (props.tabs.length === 2) {
      return 'is-triple-slider'
    }

    return ''
  }

  if (!props.type) {
    if (props.tabs.length === 2) {
      return 'is-squared is-triple-slider'
    }
  }

  return ''
})

function toggle(value: string) {
  activeValue.value = value
}
watch(
  () => selectedTabs,
  (value) => {
    activeValue.value = value
  }
)
watch(activeValue, (value: any) => {
  emit('update:selected', value)
})

let sourceRuangan: any = ref([])
let dataPasien: any = ref([])
let listKelompokPasien: any = ref([])

let ds_PASIEN: any = ref([])
let totalPasien: any = ref(0)
let totalPasienRanap: any = ref(0)
let totalPermohonan: any = ref(0)
let ds_PASIENRANAP: any = ref([])
let ds_Permohonan: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const currentPage: any = ref({
  limit: 5,
  rows: 50
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  if (activeValue.value == 1) 
  {
    fetchPasien()
  } 
  else if (activeValue.value == 2) 
  {
    fetchPasienRanap()
  }
  else if (activeValue.value == 3)
  {
    fetchPermohonan()
  }
})

async function fetchPasien() {
  ds_PASIEN.value.loading = true

  let searchQuery = `&q=`
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  let nama = item.value.qnama ? `&namapasien=${item.value.qnama}` : ''
  let nocm = item.value.qnocm ? `&nocm=${item.value.qnocm}` : ''
  let qnoregistrasi = item.value.qnoregistrasi ? `&noreg=${item.value.qnoregistrasi}` : ''
  let qruangan = item.value.ruanganfk ? `&ruid=${item.value.ruanganfk}` : ''
  let qkelompokpasien = item.value.qkelompokpasien ? `&kpid=${item.value.qkelompokpasien}` : ''
  // if (item.value.ruanganfk) qkelompokpasien = `&kpid=${item.value.qkelompokpasien}`

  const response = await useApi().get(
    `/farmasi/daftar-pasien-farmasi-grid?dari=${moment(item.value.periode.start).format('YYYY-MM-DD')
    }&sampai=${moment(item.value.periode.end).format('YYYY-MM-DD')}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${nama}${nocm}${qnoregistrasi}${qruangan}${qkelompokpasien}`)
  ds_PASIEN.value.loading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    let ini = element.namapasien.split(' ')
    let init = element.namapasien.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init
  }

  ds_PASIEN.value = response.data
  totalPasien.value = response.total
  route.query.page = '1'
}

async function fetchPasienRanap() {
  ds_PASIENRANAP.value.loading = true
  let searchQuery = `&q=`
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  let nama = item.value.qnama ? `&namapasien=${item.value.qnama}` : ''
  let nocm = item.value.qnocm ? `&nocm=${item.value.qnocm}` : ''
  let qnoregistrasi = item.value.qnoregistrasi ? `&noreg=${item.value.qnoregistrasi}` : ''
  let qruangan = item.value.ruanganfk ? `&ruid=${item.value.ruanganfk}` : ''
  let qkelompokpasien = item.value.qkelompokpasien ? `&kpid=${item.value.qkelompokpasien}` : ''

  const response = await useApi().get(
    `/farmasi/daftar-pasien-ranap?offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${nama}${nocm}${qnoregistrasi}${qruangan}${qkelompokpasien}`)
  ds_PASIENRANAP.value.loading = false
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    let ini = element.namapasien.split(' ')
    let init = element.namapasien.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init
  }

  ds_PASIENRANAP.value = response.data
  totalPasienRanap.value = response.total
  route.query.page = '1'
}

async function fetchPermohonan() {
  ds_Permohonan.value.loading = true
  let searchQuery = `&q=`
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  let nama = item.value.qnama ? `&namapasien=${item.value.qnama}` : ''
  let nocm = item.value.qnocm ? `&nocm=${item.value.qnocm}` : ''
  let qnoregistrasi = item.value.qnoregistrasi ? `&noreg=${item.value.qnoregistrasi}` : ''
  let statusnya = item.value.statusnya == true ? '&statuscetak=t' : ''
  let qruangan = item.value.ruanganfk ? `&ruid=${item.value.ruanganfk}` : ''
  let qkelompokpasien = item.value.qkelompokpasien ? `&kpid=${item.value.qkelompokpasien}` : ''

  const response = await useApi().get(
    `/farmasi/daftar-permohonan?offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${nama}${nocm}${qnoregistrasi}${qruangan}${qkelompokpasien}${statusnya}`)
    ds_Permohonan.value.loading = false
  for (let x = 0; x < response.length; x++) {
    const element = response[x];
    let ini = element.namapasien.split(' ')
    let init = element.namapasien.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init
  }

  ds_Permohonan.value = response.data
  totalPermohonan.value = response.total
  route.query.page = '1'
}



async function listDropdown() {
  const response = await useApi().get(
    `/farmasi/daftar-pasien-farmasi-cbo`)
  listKelompokPasien.value = response.kelompokpasien.map((e): any => { return { label: e.kelompokpasien, value: e.id } })
}

function clearFilter() {
  delete item.value.qnama
  delete item.value.qnocm
  delete item.value.qnik
  delete item.value.qnoregistrasi
  delete item.value.qbpjs
  delete item.value.qalamat
  delete item.value.ruanganfk
  fetchPasien()
  fetchPasienRanap()
  fetchPermohonan()
}

async function fetchRuangan(filter: any) {
  let query = ''
  if (filter) {
    query = filter.toLowerCase()
  }
  const response = await useApi().get(
    `/farmasi/daftar-ruangan-cbo?name=${query}&limit=10`)

  return response.ruangan.map((item: any) => {
    return { value: item.id, label: item.namaruangan, }
  })
}

function filter() {
  fetchPasien()
}

function filterRanap() {
  fetchPasienRanap()
}

function filterPermohonan() {
  fetchPermohonan()
}

function transaksiPelayanan(select: any) {
  useApi().postNoMessage('/general/save-jurnal-pelayananpasien_t-noreg', { 'noregistrasi': select.noregistrasi })
  router.push({ name: 'module-farmasi-transaksi-pelayanan-farmasi', query: { norec_pd: select.norec_pd } })
}

watch(
    () => item.value.statusnya,
    () => {
      fetchPermohonan()
    }
)

watch(
  () => activeValue.value,
  (newValue, oldValue) => {
    if (newValue) {
      if (newValue == 1) 
      {
        fetchPasien()
      } 
      else if (newValue == 2) 
      {
        fetchPasienRanap()
      }
      else if (newValue == 3)
      {
        fetchPermohonan()
      }
    } else {
    }
  }
)
fetchPasien()
listDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.fs-075 {
  font-size: 0.9rem;
}


.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.form-layout {
  // max-width: 740px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            >h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    +.radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              >p {
                padding-top: 12px;

                >span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              >h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked+.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          >p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}
</style>
