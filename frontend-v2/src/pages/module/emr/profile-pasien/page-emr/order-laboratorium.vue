<template>
  <section>
    <ConfirmDialog />
    <div>
      <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3> Laboratorium</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <h3 color="info" v-if="isPenunjangSusulan != null" bold
                    style="font-weight: bold; color: blue; font-size: 16px; margin-top: -5px; margin-right: 20px; border-bottom: 1px solid blue">
                    Lab Berbeda Hari: {{ H.formatDate(isPenunjangSusulan, 'DD-MM-YYYY') }}
                  </h3>
                  <VButton type="button" rounded color="primary" raised icon="feather:save" :disabled="disabledSave"
                    :loading="isLoading" @click="simpan()"> Simpan
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
                          <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
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
                        <div class="column is-3 mt-0 " v-if="activeValue == 1">
                          <div class="columns is-multiline">
                            <div class="column is-12 ">
                              <div class="form-section  pt-0 pr-0">
                                <div class="form-section-inner has-padding-bottom h-700-o"
                                  style="padding: 5px; overflow-x: hidden; overflow-y: hidden">
                                  <h3 class="has-text-centered">Detail Order </h3>
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <VField label="Tanggal">
                                        <VDatePicker v-model="item.tglorder" mode="dateTime" style="width: 100%;">
                                          <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                              <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                              </VControl>
                                            </VField>
                                          </template>
                                        </VDatePicker>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Ruangan Asal">
                                        <VControl icon="feather:map-pin">
                                          <VInput type="text" placeholder="" autocomplete="off"
                                            v-model="item.registrasi.namaruangan" disabled />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Ruangan Tujuan" class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:home" fullwidth>
                                          <Multiselect mode="single" v-model="item.ruanganTujuan" :options="d_Ruangan"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                            autocomplete="off" @select="changeRuangan(item.ruanganTujuan)" />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Pengorder " class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="fa:user-md" fullwidth>
                                          <Multiselect mode="single" v-model="item.pegawaiOrder"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                            :options="d_Pegawai" autocomplete="off" />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Registrasi" class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:plus-circle" fullwidth>
                                          <Dropdown v-model="item.pilihRegistrasi" :options="d_Registrasi"
                                            :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                            placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                                            @change="getRegistrasi(item.pilihRegistrasi)" disabled />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField>
                                        <VLabel class="required-field">Catatan Klinis</VLabel>
                                        <VControl>
                                          <VTextarea class="textarea" v-model="item.catatanKlinis" rows="2"
                                            placeholder="catatan Klinis (optional) ..." autocomplete="off"
                                            autocapitalize="off" spellcheck="true" />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Keterangan">
                                        <VControl>
                                          <VTextarea class="textarea" v-model="item.keterangan" rows="2"
                                            placeholder="catatan order (optional) ..." autocomplete="off"
                                            autocapitalize="off" spellcheck="true" />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField>
                                        <VControl>
                                          <VSwitchBlock v-model="item.iscito" label="Cito" color="danger" />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="column is-9 mt-0" v-if="activeValue == 1">
                          <div class="form-section pt-0 pl-0">
                            <div class="form-section-inner" style="padding: 10px; height: 635px;">
                              <h3 class="has-text-centered">Detail Pemeriksaan</h3>
                              <div class="columns is-multiline">
                                <div class="column is-4">
                                  <VControl class="is-pulled-left">
                                    <VButton v-model="isPaket" color="danger" class="is-pulled-right"
                                      @click="fetchPaket()">Pilih Paket
                                    </VButton>
                                  </VControl>
                                </div>
                                <div class="column is-12">
                                  <UIWidget class="search-widget">
                                    <template #body>
                                      <div class="field">
                                        <div class="control">
                                          <input type="text" v-model="filterLayanan" class="input"
                                            placeholder="Search..." />
                                          <button class="searcv-button" type="button">
                                            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                          </button>
                                        </div>
                                      </div>
                                    </template>
                                  </UIWidget>
                                </div>
                                <div class="column is-8 h-400-o">
                                  <div class="gumball" style="overflow-y: auto; max-height: 400px;">
                                    <div class="column is-12" v-for="items in filteredLayanan"
                                      :key="items.detailjenisproduk">
                                      <div class="group-header">
                                        <VIconBox color="maroon" style="width:30px;height:30px; min-width: 30px; ">
                                          <i aria-hidden="true" class="lnir lnir-flask-alt" style="font-size: 1rem"></i>
                                        </VIconBox>
                                        <h4 class="ml-1">{{ items.detailjenisproduk }}</h4>
                                      </div>
                                      <div class="columns is-multiline mb-3">
                                        <div class="column is-4" v-for="itemProd in items.details" :key="itemProd.id">
                                          <VField grouped>
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="item.produkCeklis[itemProd.id]"
                                                :label="itemProd.namaproduk" color="info" @change="getSelected()" />
                                            </VControl>
                                          </VField>
                                          <!--? Bank Darah  -->
                                          <!-- <VField grouped v-if="item.ruanganTujuan == 302">
                                            <VControl>
                                              <VInput v-model="item.ss[itemProd.id]" :placeholder="'Masukan jumlah '"
                                                size="small" type="number" />
                                            </VControl>
                                          </VField> -->
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="column is-4 mt-0" v-if="activeValue == 1">
                                  <div class="group-header">
                                    <VIconBox color="maroon" style="width:30px;height:30px; min-width: 30px; ">
                                      <i aria-hidden="true" class="fas fa-check" style="font-size: 1rem"></i>
                                    </VIconBox>
                                    <h4 class="ml-1">Pemeriksaan Terpilih ({{ listChecked.length }})</h4>
                                  </div>
                                  <div class="column is-12 mt-0 pt-0" v-if="listChecked.length > 0">
                                    <div class="form-section pr-0 mt-0 pt-0">
                                      <div class="form-section-inner has-padding-bottom h-700-o  creative-list-widget "
                                        style="padding: 5px; overflow-x: hidden; overflow-y: hidden">
                                        <div class="columns is-multiline  creative-list">
                                          <div v-for="item in listChecked" :key="item.id"
                                            class="creative-list-item is-danger">
                                            <div class="columns is-multiline">
                                              <div class="column is-12">
                                                <div class="meta" style="width:100%">
                                                  <p class="is-pilih-text">{{ item.namaproduk }}</p>
                                                  <!-- <p class="is-pilih-price">{{ H.formatRp(item.hargasatuan, 'Rp.')}}</p> -->
                                                </div>
                                              </div>
                                              <div class="column is-12">
                                                <VTag :color="'danger'" :label="'Hapus'"
                                                  @click="clearSelectionItem(item)" class="mt-0 ml-5 is-cursor" />
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

                        <div class="column is-12 mt-0 mr-4 pr-5" v-if="activeValue == 2">
                          <div class="columns is-multiline">
                            <div class="column is-12 ">
                              <div class="form-section  pt-0 pr-0">
                                <div class="form-section-inner has-padding-bottom h-700-o ">
                                  <h3 class="has-text-centered">Riwayat </h3>
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <TRiwayatOrderLab title="" straight class="list-widget-v3" :items="listRiwayat"
                                        @editItems="editItems" @hapusItems="DialogConfirm"
                                        @hasilLabManual="printHasilLabManual" squared colored>
                                      </TRiwayatOrderLab>
                                    </div>
                                    <div class="column is-12 mt-3">
                                      <VButton icon="lnir lnir-arrow-left is-fullwidth" color="info" dark-outlined
                                        @click="activeValue = 1">
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
                                  <h3 class="has-text-centered">Riwayat Semua Pemeriksaan Laboratorium Pasien</h3>
                                  <div class="columns is-multiline">
                                    <!-- <div class="column is-12 mt-3">
                                      <VButton color="warning" dark-outlined @click="bukaLIS">
                                        Riwayat Pemeriksaan Pasien LAB PA
                                      </VButton>
                                    </div> -->
                                    <div class="column is-12">
                                      <TRiwayatOrderLabAll title="" straight class="list-widget-v3"
                                        :items="listRiwayatAll" @hasilLabManual="printHasilLabManual" squared colored>
                                      </TRiwayatOrderLabAll>
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
                              <div class="form-section  pt-0 pr-0">
                                <div class="form-section-inner has-padding-bottom h-700-o ">
                                  <h3 class="has-text-centered">Upload Berkas Pasien Laboratorium</h3>
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <UploadBerkas title="" straight class="list-widget-v3"
                                        :registrasi="props.registrasi" :pasien="props.pasien" :type="'lab'" squared
                                        colored>
                                      </UploadBerkas>
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

    </div>

    <Dialog v-model:visible="modalPenunjang" modal header="Penunjang" :style="{ width: '50vw' }">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField label="Tanggal Kunjungan Berikutnya">
            <VDatePicker v-model="item.tglkunjungan" mode="dateTime" style="width: 100%;">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
      </div>
      <template #footer>
        <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="modalPenunjang = false">
          Batal
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
          @click="susulan()"> Simpan
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="modalLis" modal header="Riwayat Pemeriksaan Laboratorium(PA)" :style="{ width: '50vw' }">
      <div class="columns is-multiline">
        <DataTable :value="dataSourceLIS" class="p-datatable-sm" tableStyle="min-width: 10rem"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <!-- <Column field="noregistrasi" header="No Registrasi" style="min-width: 120px;" frozen class="font-bold">
          </Column> -->
          <Column :exportable="false" header="Hasil" style="text-align: center;min-width: 100px;">
            <template #body="slotProps">
              <VIconButton type="button" icon="feather:eye" class="mr-3" color="warning" circle outlined raised
                :loading="isLoadingBtn" v-tooltip.top="'Lihat Hasil'" @click="hasilcetakLabALL(slotProps.data)">
              </VIconButton>
            </template>
          </Column>
          <Column field="SOURCE_NM" header="Ruangan Pengorder" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="ONO" header="Nomor Order" style="min-width: 200px;" frozen class="font-bold">
          </Column>
          <Column field="CLINICIAN_NM" header="Nama Dokter Pengorder" style="min-width: 200px;" frozen
            class="font-bold">
          </Column>
          <Column field="SOURCE_NM" header="Ruangan Pengorder" style="min-width: 200px;" frozen class="font-bold">
          </Column>
        </DataTable>
      </div>
      <template #footer>
        <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="modalLis = false">
          Tutup
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="modalPaket" modal header="Paket" :style="{ width: '60vw' }">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VCard>
            <DataTable :value="dataSourcePaket" v-model:expandedRows="expandedRows" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers" filterDisplay="menu"
              v-model:filters="filterPaket"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
              :globalFilterFields="['namapaket']" :scrollable="true" :loading="dataSourcePaket.loading" dataKey="id">
              <template #header>
                <div class="flex justify-content-end">
                  <!-- <span class="p-input-icon-left">
                                      <i class="pi pi-search"></i>
                                    </span> -->
                  <VField>
                    <VControl>
                      <VInput v-model="filterPaket.global.value" placeholder="Keyword Search" />
                    </VControl>
                  </VField>
                </div>
              </template>
              <Column :expander="true" :style="{ width: '50px' }" />
              <Column :exportable="false" header="#" :style="{ width: '50px' }">
                <template #body="slotProps">
                  <VIconButton type="button" icon="pi pi-plus" class="mr-2" color="info" circle outlined raised
                    v-tooltip.top="'Tambah'" @click="tambahPaket(slotProps.data)" :loading="slotProps.data.isLoading">
                  </VIconButton>
                </template>
              </Column>

              <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
              <Column field="namapaket" header="Nama Paket" style="width:250px" :sortable="true"></Column>
              <Column field="jml" header="Jumlah Pelayanan" style="width:100px"></Column>
              <template #expansion="slotProps">
                <div class="orders-subtable">
                  <h5>Paket : {{ slotProps.data.namapaket }}</h5>
                  <DataTable :value="slotProps.data.details" responsiveLayout="scroll">
                    <Column field="namaproduk" header="Pelayanan" :sortable="true"> </Column>
                  </DataTable>
                </div>
              </template>

            </DataTable>
          </VCard>
        </div>

      </div>

    </Dialog>
    <Dialog is="form" v-model:visible="modalBukti" modal header="Bukt Order" :style="{ width: '100rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
      <!-- <template #content> -->
      <div class="columns is-multiline">
        <div class="column is-4">
          <VField>
            <VLabel class="required-field">Klinis/Diagnosis</VLabel>
            <VControl>
              <input v-model="item.klinis_diagnosis" type="text" class="input" placeholder="klinis/diagnosis" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-12">
          <h1 style="margin-bottom: 10px;" class="ml-3">
            Riwayat Pemakaian Antibiotik
          </h1>
          <div class="columns is-multiline is-flex is-align-items-center pt-0">
            <!-- Sebelum Pemberian AB -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sebelumab" label="Sebelum Pemberian AB" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>

            <!-- Sedang Terapi AB -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.terapiab" label="Sedang terapi AB" color="primary"
                    circle />
                </VControl>
              </VField>
            </div>

            <!-- Jenis/Nama Antibiotik -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <!-- <VControl> -->
                <VLabel>Nama antibiotik :</VLabel>
                <VControl class="ml-2">
                  <input v-model="item.namaantibiotik" type="text" class="input" placeholder="Masuan Nama..." />
                </VControl>
                <!-- <VCheckbox 
                                    class="fontcheckbox" 
                                    v-model="item.namaantibiotik" 
                                    label="Jenis/nama antibiotik" 
                                    color="primary" 
                                    circle 
                                  /> -->
                <!-- </VControl> -->
              </VField>
            </div>

            <!-- Hari Ke -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VLabel>Hari Ke :</VLabel>
                <VControl class="ml-2">
                  <input v-model="item.harike" type="text" class="input" placeholder="Hari ke..." />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <h1 style="margin-bottom: 10px;" class="ml-3">
            Penggunaan medical device
          </h1>
          <div class="columns is-multiline is-flex is-align-items-center pt-0">
            <!-- Sebelum Pemberian AB -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.qvc" label="Q CVC" color="primary" circle />
                </VControl>
              </VField>
            </div>

            <!-- Sedang Terapi AB -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.ivline" label="IV Line" color="primary" circle />
                </VControl>
              </VField>
            </div>

            <!-- Jenis/Nama Antibiotik -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.namaantibiotik2" label="Jenis/nama antibiotik"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.wsd" label="WSD" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.eet" label="EET" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.ventilator" label="Ventilator" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.cpap" label="CPAP" color="primary" circle />
                </VControl>
              </VField>
            </div>

            <!-- Hari Ke -->
            <div class="column is-flex is-align-items-center is-3">
              <VField>
                <VLabel>Lainnya :</VLabel>
                <VControl class="ml-2">
                  <input v-model="item.lain1" type="text" class="input" placeholder="Lainnya..." />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <h1 style="margin-bottom: 10px;" class="ml-3">
            Jenis Spesimen
          </h1>
          <div class="columns is-multiline">
            <!-- Row 1 -->
            <div class="column is-4">
              <VField>
                <VLabel>1. Urine:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.midstream" label="Midstream" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.urineKateter" label="Urine kateter" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.supraPubik" label="Aspirasi supra pubik"
                    color="primary" />
                </VControl>
              </VField>
            </div>

            <!-- Row 2 -->
            <div class="column is-4">
              <VField>
                <VLabel>2. Darah:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sisi" label="Sisi" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sisi2" label="2 Sisi" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.tempatEndokarditis" label="3 tempat (endokarditis)"
                    color="primary" />
                </VControl>
              </VField>
            </div>

            <!-- Row 3 -->
            <div class="column is-4">
              <VField>
                <VLabel>3. Sputum:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.pagi" label="Pagi" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sewaktu" label="Sewaktu" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.induksi" label="Induksi" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.sputanEET" label="Sputan EET" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VLabel>Area pengambilan:</VLabel>
                  <input v-model="item.pengambilan1" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>4. Luka:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.dasarluka" label="Dasar Luka" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.aspirasipus" label="Aspirasi pus" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VLabel>Area pengambilan:</VLabel>
                  <input v-model="item.pengambilan2" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>5. Jaringan:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.ascites" label="Ascites" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VLabel>Area pengambilan jaringan:</VLabel>
                  <input v-model="item.pengambilan3" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
            <!-- Additional Rows -->
            <div class="column is-4">
              <VField>
                <VLabel>6. LCS:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.lcs" label="Ascites" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.lp" label="LP" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.vpshunt" label="VP shunt" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.evd" label="EVD" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>7. Cairan:</VLabel>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.ascites2" label="Ascites" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.perikardium2" label="Perikardium" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="item.pleura" label="pleura" color="primary" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VLabel>Area pengambilan:</VLabel>
                  <input v-model="item.pengambilan4" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>8. Lainnya:</VLabel>
                <VControl>
                  <input v-model="item.lainnya3" type="text" class="input" placeholder="..................." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Volume spesimen(ml):</VLabel>
              <VControl>
                <input v-model="item.volspes" type="text" class="input" placeholder="..................." />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4">
          <VField>
            <VControl>
              <VCheckbox class="fontcheckbox" v-model="item.spesimen" label="Pengambilan spesimen" color="primary" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField>
            <VLabel>Pengiriman spesimen:</VLabel>
            <VControl>
              <input v-model="item.pengirimanspesimen" type="text" class="input" placeholder="..................." />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VDatePicker v-model="item.waktu" color="green" trim-weeks mode="datetime">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel style="text-overflow:unset">Waktu</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Waktu" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-4">
          <VField>
            <VLabel>Cara Penyimpanan:</VLabel>
            <VControl>
              <input v-model="item.penyimpanan" type="text" class="input" placeholder="..................." />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField>
            <VControl>
              <VCheckbox class="fontcheckbox" v-model="item.empatcels" label="4°C" color="primary" />
            </VControl>
          </VField>
        </div>
      </div>
      <!-- </template> -->
      <!-- <template #action> -->
      <VButton color="primary" raised @click="addData(item)">Simpan</VButton>
      <!-- </template> -->
    </Dialog>
  </section>


  <!-- <div class="column is-6">
            <VDatePicker v-model="item.tglpenglolahan" color="green" trim-weeks mode="date">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VLabel class="required-field" style="text-overflow:unset">Tanggal Pengelolahan</VLabel>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Tanggal Pengelolahan" :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div> -->


  <Dialog v-model:visible="modalFormulirDarah" modal header="Formulir Permintaan Darah" :style="{ width: '70vw' }">
    <BankDarah :norec_pd="NOREC_PD" :pasien="props.pasien" :registrasi="props.registrasi" :ID_PASIEN="ID_PASIEN"
      :noorder="item.noorder" :tombol="false" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalFormulirDarah = false">
        Tutup
      </VButton>
    </template>
  </Dialog>

  <Dialog v-model:visible="modalHapusAmprah" modal header="Formulir Batal Amprah Darah" :style="{ width: '70vw' }">
    <HapusAmprah :norec_pd="NOREC_PD" :pasien="props.pasien" :registrasi="props.registrasi" :ID_PASIEN="ID_PASIEN"
      :noorder="item.noorder" :tombol="false" />
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalHapusAmprah = false">
        Tutup
      </VButton>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import Dialog from 'primevue/dialog';
import FileUpload from 'primevue/fileupload';
import TRiwayatOrderLab from '../t-riwayat-order-lab.vue'
import TRiwayatOrderLabAll from '../t-riwayat-order-lab-all.vue'
import UploadBerkas from '../page-emr/berkas-pasien.vue'
import Dropdown from "primevue/dropdown"
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode } from 'primevue/api';
import BankDarah from '../../profile-pasien/page-emr/formulir-permintaan-darah.vue'
import HapusAmprah from '../../profile-pasien/page-emr/formulir-pembatalan-penggunaan-komponen-darah.vue'

useHead({ title: 'Order Laboratorium - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(props.pasien ? true : false)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
const props = defineProps({
  registrasi: { type: Object as PropType<any> },
  pasien: { type: Object as PropType<any> },
  selected: undefined,
  type: undefined,
  align: undefined,
  NOREC_PD: { type: Object as PropType<any> }
})
const isLoadingPasien: any = ref(false)
const modalPenunjang: any = ref(false)
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const isPenunjangSusulan: any = ref()
const item: any = reactive({
  // NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : props.NOREC_PD,
  NOREC_APD: '',
  registrasi: {},
  tglorder: new Date(),
  produkCeklis: [],
  ss: [],
  pegawaiOrder: useUserSession().getUser().id,
  isorderlab: true,
  noorder: ''
})
const tabs: any = ref([
  { label: 'Order', value: 1, icon: 'fas fa-bong' },
  { label: 'Riwayat', value: 2, icon: 'fas fa-list' },
  { label: 'Riwayat Order Lab Pasien', value: 3, icon: 'fas fa-list' },
  { label: 'Upload Berkas External Lab', value: 4, icon: 'fas fa-folder' }
])
const router = useRouter()
const route = useRoute()
const listChecked: any = ref([])
const selected_count = ref(0);
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
const filterPaket: any = ref({ 'global': { value: null, matchMode: FilterMatchMode.CONTAINS }, })
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const item2: any = reactive({ tglpelayanan: new Date(), })
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const dataBukti: any = ref([])
const isLoading = ref(false)
const confirm = useConfirm();
const d_Registrasi: any = ref([])
const d_Produk: any = ref([])
const d_Pegawai: any = ref([])
const d_ProdukDef: any = ref([])
const inimasuk = ref(false)
const disabledSave = ref(false)
const modalFormulirDarah = ref(false)
const modalHapusAmprah = ref(false)
const historySave: any = ref([])
const filePasien: any = ref()
const filterLayanan: any = ref('')
const selectedTabs: any = ref()
const listRiwayat = ref([])
const listRiwayatAll = ref([])
const files = ref([])
const fileFoto: any = ref(null)
const isPaket: any = ref(false)
const dataSourcePaket: any = ref([])
const dataSourceLIS: any = ref([])
const modalPaket: any = ref(false)
const modalBukti: any = ref(false)
const modalLis: any = ref(false)
const NOREC_EMRPASIEN: any = ref('')
const emit = defineEmits<{ (e: 'update:selected', value: string): void }>()
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

// const filteredLayanan = computed(() => {
//   if (!filterLayanan.value) { return d_Produk.value }
//   var filtered: any = [];

//   for (let i = 0; i < d_Produk.value.length; i++) {
//     const element = d_Produk.value[i];
//     filtered.push({
//       'detailjenisproduk': element.detailjenisproduk,
//       'id': element.id,
//       'details': []
//     })
//     for (let ii = 0; ii < element.details.length; ii++) {
//       const element2 = element.details[ii];
//       if (element2.namaproduk.match(new RegExp(filterLayanan.value, 'i'))) {
//         filtered[filtered.length - 1].details.push(element2)
//       }
//     }
//   }
//   return filtered;
// })

const filteredLayanan = computed(() => {
  if (!filterLayanan.value) { return d_Produk.value; }

  const searchTerm = filterLayanan.value.toLowerCase();
  const filtered: any[] = [];

  for (const group of d_Produk.value) {
    const matchedDetails = group.details.filter(detail => detail.namaproduk.toLowerCase().includes(searchTerm));
    if (matchedDetails.length > 0) {
      filtered.push({
        detailjenisproduk: group.detailjenisproduk,
        id: group.id,
        details: matchedDetails
      });
    }
  }

  return filtered;
});

const fetchPaket = async () => {
  modalPaket.value = true
  await useApi().get(`/tindakan/list-paket?flag=lab`).then((response: any) => {
    dataSourcePaket.value = response
  }).catch((e: any) => {

  })
}

const toggle = (value: string) => {
  activeValue.value = value
}

const loadRiwayat = () => {
  listRiwayat.value = []
  useApi().get(`/laboratorium/riwayat-order?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}&ruangan=${props.registrasi.namaruangan.trim()}`).then((response: any) => {
    let z = 0
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
    listRiwayat.value = response
  })
}
const loadRiwayatAll = () => {
  listRiwayatAll.value = []
  useApi().get(`/laboratorium/riwayat-order?nocmfk=${ID_PASIEN}&ruangan=${props.registrasi.namaruangan}`).then((response: any) => {
    let z = 0
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
    listRiwayatAll.value = response
  })
}
const pasienByID = (id: any) => {
  useApi().get(`/emr/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
    if (response.registrasi[0].isPenunjangSusulan != null) {
      isPenunjangSusulan.value = response.registrasi[0].isPenunjangSusulan
    }
  })
  if (props.pasien != undefined) {
    pasien.value = props.pasien
    item.NOREC_APD = props.registrasi.norec_apd
    item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
    item.registrasi = props.registrasi
    item.keterangan = null;
    //? BANK DARAH
    // item.keterangan = props.pasien.golongandarah && item.ruanganTujuan == 302 ? 'golongan darah pasien ' + props.pasien.golongandarah : ''
    item.isorderlab = props.pasien.isFilterProdukLab == 'true' ? true : false
  } else {
    isLoadingPasien.value = true
    useApi().get(`/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.registrasi = response.last_registrasi
      item.isorderlab = response.isFilterProdukLab == 'true' ? true : false
      isLoadingPasien.value = false
      // fetchTindakan(item.RUANGAN_LAST)
    })
  }
}

const getEmr = () => {
  // Pertama coba ambil dari CPPT
  useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail" + "&flag=dokter" + "&ruangan=" + props.registrasi.namaruangan + "&field=A").then((cpptResponse) => {
    if (cpptResponse != null && cpptResponse.A) {
      item.catatanKlinis = cpptResponse.A;
    } else {
      // Jika CPPT kosong, ambil dari EMR biasa
      const collection = 'AsesmenMedisRawatJalan';
      const fields = 'TADiagnosa,MOI,diagnosaIcd10';

      useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${collection}&field=${fields}`).then((emrResponse) => {
        if (emrResponse) {
          item.catatanKlinis = emrResponse.TADiagnosa ? emrResponse.TADiagnosa : null;
        } else {
          // Fallback terakhir ke Asesmen Gawat Darurat
          useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=TADiagnosis,TAMOI,diagnosaIcd10`).then((gdResponse) => {
            if (gdResponse) {
              item.catatanKlinis = gdResponse.TADiagnosis ? gdResponse.TADiagnosis : null;
            }
          });
        }
      });
    }
  });
};

const tambahPaket = async (e: any) => {
  for (var x = 0; x < e.details.length; x++) {
    const elementx = e.details[x]
    listChecked.value.push({ namaproduk: elementx.namaproduk, hargasatuan: 0, id: elementx.objectprodukfk })
    item.produkCeklis[parseInt(elementx.objectprodukfk)] = true
  }
  modalPaket.value = false
}
const changeRuangan = (e: any) => {
  fetchTindakan(e)
}
const fetchDropdown = async () => {
  useApi().get(`/tindakan/list-dropdown-registrasi?nocmfk=${ID_PASIEN}`).then((response: any) => {
    d_Registrasi.value = response.registrasi.map((e: any) => { return { label: e.tglregistrasi + ' - (' + e.noregistrasi + ' - ' + e.namaruangan + ')', value: e, default: e } })
    for (let i = 0; i < response.registrasi.length; i++) {
      console.log(H.formatDate(new Date(), 'DD-MM-YYYY'))
      let ftregis = response.registrasi[i];
      if (item.registrasi.noregistrasi == ftregis.noregistrasi &&
        item.registrasi.objectruanganfk == ftregis.objectruanganfk
      ) {
        console.log('UHUYYY', ftregis);
        item.pilihRegistrasi = d_Registrasi.value[i].value
        getRegistrasi(item.pilihRegistrasi)
      }
    }
  })
  await useApi().get(`/laboratorium/list-dropdown`).then((response: any) => {
    d_Ruangan.value = response.ruanganLab.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    item.ruanganTujuan = d_Ruangan.value[0].value
    item.departemenfk = d_Ruangan.value[0].default.objectdepartemenfk
    fetchTindakan(item.ruanganTujuan)
  })

  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`).then((response) => {
    d_Pegawai.value = response
    d_Pegawai.value.forEach(element => {
      if (props.registrasi.objectpegawaifk == element.value) {
        item.pegawaiOrder = element.value
      }
    });
  })
}

const getRegistrasi = async (e: any) => {
  // item.tglorder =  e.tanggal  // ini buat langsung nge set sesaui tgl keluar registrasi
}

const fetchTindakan = (e: any) => {
  isLoading.value = true
  console.log(pasien)
  if (pasien.value.objectkebangsaanfk == null || pasien.value.objectkebangsaanfk == undefined || pasien.value.objectkebangsaanfk == '') {
    H.alert('error', 'Kebangsaan Kosong, Mohon Di isi Terlebih Dahulu !!')
    return
  }

  try {
    useApi().get(`/laboratorium/list-tindakan-for-order?ruanganfk=${e}&idkebangsaan=${pasien.value.objectkebangsaanfk}&kelasfk=${item.registrasi.objectkelasfk}&isruangan=true`).then((response: any) => {
      isLoading.value = false
      let x = 0
      d_ProdukDef.value = response.data
      d_Produk.value = response.list_tindakan
    })
  } catch (error) {
    console.error(error);
    isLoading.value = false
  }
}
const onUpload = () => {

}
const onAddFile = (error: any, fileInfo: any) => {
  if (error) {
    console.error(error)
    return
  }

  const _file = fileInfo.file as File
  if (_file) {
    fileFoto.value = _file
  }
}

const onRemoveFile = (error: any, fileInfo: any) => {
  if (error) {
    console.error(error)
    return
  }

  fileFoto.value = null
}

const addData = (e: any) => {
  dataBukti.value.push(e)
  console.log(e)

  useApi().post('/laboratorium/save-data-bukti', e).then((Response: any) => {
    if (Response.data != null) {
      let noorder = Response.data.noorderfk
      H.alert('seccess', 'data tersimpan')
      H.printBlade(`laboratorium/cetak-bukti-lab?noorder=${noorder}`)
      modalBukti.value = false
    }
  }).catch((e: any) => {
    console.error(e)
  })
}

const simpan = async () => {
  isLoading.value = true
  await H.statusClosingPasien(NOREC_PD);
  isLoading.value = false

  if (item.ruanganTujuan == undefined) {
    H.alert('error', 'Pilih ruangan tujuan')
    return
  }
  if (item.pegawaiOrder == undefined) {
    H.alert('error', 'Pilih Pengorder')
    return
  }
  if (item.tglorder == undefined) {
    H.alert('error', 'Pilih Tgl Order  terlebih dahulu')
    return
  }
  if ((item.produkCeklis == undefined || item.produkCeklis.length == 0) && item.ruanganTujuan != 124) {
    H.alert('error', 'Pilih layanan terlebih dahulu')
    return
  }
  if (!item.keterangan && item.produkCeklis.length == 0 && item.ruanganTujuan == 124) {
    H.alert('error', 'Keterangan Harus DI isi, karna tidak memilih tindakan !')
    return
  }
  if (item.catatanKlinis == undefined || item.catatanKlinis.length == 0) {
    H.alert('error', 'Catatan klinis wajib diisi')
    return
  }

  let udahorder = false
  let squidword = false
  let tglorder = H.formatDate(item.tglorder, 'YYYY-MM-DD')
  let tayo = H.formatDate(item.tglorder, 'YYYY-MM-DD')
  let namaProduk = []

  isLoading.value = true

  await useApi().get(`/laboratorium/riwayat-order?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
    isLoading.value = false
    for (let i = 0; i < response.length; i++) {
      const element = response[i];
      const tglhisorder = H.formatDate(element.tglorder, 'YYYY-MM-DD')
      if (tglorder == tglhisorder) {
        udahorder = true
      }
      if (element.details && element.details.length > 0) {
        for (let spongebob = 0; spongebob < listChecked.value.length; spongebob++) {
          const patrick = listChecked.value[spongebob]
          for (let j = 0; j < element.details.length; j++) {
            const sandy = element.details[j]
            if (tayo == tglhisorder && patrick.id == sandy.idproduk) {
              squidword = true
              namaProduk.push(sandy.namaproduk)
            }
          }
        }
      }
    }

    if (squidword && namaProduk.length > 0) {
      confirm.require({
        // message: 'Order Laboratorium sudah dilakukan, Apakah ingin order lagi ?',
        message: 'Terdapat Tindakan Laboratorium Sudah Di Lakukan Pada Tanggal Order Yang Sama ' + JSON.stringify(namaProduk) + '. Apakah Mau Menambahkan Tindakan Lagi ?',
        header: 'Konfirmasi Order Laboratorium',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
          lanjutsimpan()
        },
        reject: () => { },
      })
    }

    else if (udahorder) {
      confirm.require({
        message: 'Pastikan Lagi Untuk Order nya, Apakah Sudah Sesuai ?',
        header: 'Konfirmasi Order Laboratorium',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
          lanjutsimpan()
        },
        reject: () => { },
      })
    }

    else {
      lanjutsimpan()
    }
  })
}

const lanjutsimpan = () => {
  disabledSave.value = true
  var api = '';
  var arrobj = Object.keys(item.produkCeklis)
  var data2 = []
  var dataEx = []

  //? BANK DARAH
  // if (item.ruanganTujuan == 302) {
  //   for (let m = 0; m < listChecked.value.length; m++) {
  //     const budi = listChecked.value[m];

  //     for (var i = arrobj.length - 1; i >= 0; i--) {
  //       const key = arrobj[i]
  //       if (item.produkCeklis[parseInt(arrobj[i])] == true && budi.id == key) {
  //         var data = {
  //           no: i + 1,
  //           produkfk: arrobj[i],
  //           // qtyproduk: 1,        //default code
  //           qtyproduk: budi.qtyitem ? budi.qtyitem : 1,
  //           objectkelasfk: item.registrasi.objectkelasfk,
  //           nourut: null,
  //         }
  //         data2.push(data)
  //         dataEx.push(data)
  //       }
  //     }
  //   }
  // } else {
  // }

  for (var i = arrobj.length - 1; i >= 0; i--) {
    const key = arrobj[i]
    if (item.produkCeklis[parseInt(arrobj[i])] == true) {
      var data = {
        no: i + 1,
        produkfk: arrobj[i],
        qtyproduk: 1,        //default code
        // qtyproduk: budi.qtyitem ? budi.qtyitem : 1,
        objectkelasfk: item.registrasi.objectkelasfk,
        nourut: null,
      }
      data2.push(data)
      dataEx.push(data)
    }
  }

  var objSave = {
    noregistrasi: item.pilihRegistrasi.noregistrasi,
    tanggal: H.formatDate(item.tglorder, 'YYYY-MM-DD HH:mm:ss'),
    tgloperasi: null,
    norec_so: item.NOREC_SO ? item.NOREC_SO : '',
    norec_apd: item.pilihRegistrasi.norec_apd,
    norec_pd: item.pilihRegistrasi.norec_pd,
    qtyproduk: data2.length,
    objectruanganfk: item.registrasi.objectruanganlastfk,
    pegawaiorderfk: item.pegawaiOrder,
    objectruangantujuanfk: item.ruanganTujuan,
    departemenfk: item.departemenfk,
    catatanKlinis: item.catatanKlinis ? item.catatanKlinis : null,
    keterangan: item.keterangan != undefined ? item.keterangan : null,
    iscito: item.iscito != undefined && item.iscito == true ? item.iscito : false,
    // filePasien: filePasien.value,
    namafile: item.registrasi.noregistrasi,
    details: data2,
  }

  api = '/laboratorium/simpan-order';

  isLoading.value = true
  useApi().post(api, objSave).then((response: any) => {
    if (fileFoto.value != null) {
      const formData = new FormData()
      formData.append('norec_so', response.data.norec)
      formData.append('file', fileFoto.value)
      useApi().postNoMessage('/laboratorium/save-berkas-lab', formData)
    }
    item.noorder = response.data.noorder
    historySave.value = dataEx
    isLoading.value = false
    item.produkCeklis = []
    inimasuk.value = false
    listChecked.value = []
    delete item.keterangan
    sendNotification(response);
    delete item.NOREC_SO
    //? BANK DARAH
    // if (item.ruanganTujuan == 302) {
    //   modalFormulirDarah.value = true
    // }

    //? LAB MIKRO
    // if (item.ruanganTujuan == 337) {
    //   confirm.require({
    //     // message: 'Order Laboratorium sudah dilakukan, Apakah ingin order lagi ?',
    //     message: 'Apakah anda ingin menginput bukti order ?',
    //     header: 'Konfirmasi Order Laboratorium',
    //     icon: 'pi pi-info-circle',
    //     acceptClass: 'p-button-danger',
    //     accept: () => {
    //       modalBukti.value = true
    //     },
    //     reject: () => { },
    //   })
    // }
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const susulanModal = () => {
  modalPenunjang.value = true
}

const susulan = () => {
  isLoading.value = true
  var objSave = {
    noregistrasi: item.pilihRegistrasi.noregistrasi,
    norec_pd: item.pilihRegistrasi.norec_pd,
    isradiologi: false,
    islaboratorium: true,
    tanggal: H.formatDate(item.tglkunjungan, 'YYYY-MM-DD')
  }

  useApi().post(`/laboratorium/simpan-order-susulan`, objSave).then((response: any) => {
    isLoading.value = false
    if (response.data.isPenunjangSusulan != null) {
      isPenunjangSusulan.value = response.data.isPenunjangSusulan
    }
    modalPenunjang.value = false
  })

}

const sendNotification = (e) => {
  let ruanganAsal = item.registrasi.namaruangan
  let ruanganTujuan = ''
  let namapengorder = ''
  d_Ruangan.value.forEach((element: any) => {
    if (element.value == e.data.objectruangantujuanfk) {
      ruanganTujuan = element.label
    }
  });

  d_Pegawai.value.forEach((dtPegawai: any) => {
    if (item.pegawaiOrder == dtPegawai.value) {
      namapengorder = dtPegawai.label;
    }
  })

  let body = {
    norec: e.data.norec,
    judul: 'Order Laboratorium #' + e.data.noorder,
    jenis: e.data.keteranganorder,
    pesanNotifikasi: `Permohonan dari ${ruanganAsal} ke ${ruanganTujuan}`,
    idRuanganAsal: e.data.objectruanganfk,
    idRuanganTujuan: e.data.objectruangantujuanfk,
    ruanganAsal: ruanganAsal,
    ruanganTujuan: ruanganTujuan,
    kelompokUser: null,
    idKelompokUser: null,
    idPegawai: e.data.objectpegawaiorderfk,//H.pegawaiLogin().id,
    namapegawai: namapengorder,//H.pegawaiLogin().id,
    dataArray: [],
    urlForm: 'module-dashboard-laboratorium',
    params: null,
    group: 'mapping_login',
    namaFungsiFrontEnd: null,
    tgl: e.data.tglorder,
    tgl_string: H.formatDateIndoSimple(e.data.tglorder),
  }
  H.sendSocket("sendNotification", body);
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

const kembaliKeun = () => {
  window.history.back()
}

const fetchDokter = async (filter: any) => {
  let query = ''
  if (filter) {
    query = filter.toLowerCase()
  }
  const response = await useApi().get(`/general/dokter-paging?name= ${query}&limit=10`)
  return response.dokter.map((item: any) => {
    return { value: item.id, label: item.namalengkap, default: item }
  })
}

const getSelected = () => {
  //? BANK DARAH
  // if (item.ruanganTujuan == 302 && inimasuk.value == false) {
  //   H.alert('error', 'Input Jumlah Amprahan Darah Dahulu')
  //   item.produkCeklis = []
  //   return
  // }

  if (item.produkCeklis.length > 0) {
    var arrobj = Object.keys(item.produkCeklis)
    var bb = Object.keys(item.ss)
    for (var x = 0; x < arrobj.length; x++) {
      const element = arrobj[x];
      if (item.produkCeklis[parseInt(element)] == true) {
        for (var i = 0; i < d_ProdukDef.value.length; i++) {
          const element2 = d_ProdukDef.value[i];
          //? BANK DARAH
          // if (item.ruanganTujuan == 302) {
          //   for (let b = 0; b < bb.length; b++) {
          //     const elementx = bb[b];
          //     const qty = item.ss[elementx];

          //     if (element2.id == element && element2.id == elementx) {
          //       for (var z = 0; z < listChecked.value.length; z++) {
          //         const element3 = listChecked.value[z];
          //         if (element3.namaproduk == element2.namaproduk) {
          //           listChecked.value.splice(z, 1)
          //         }
          //       }
          //       listChecked.value.push({ namaproduk: element2.namaproduk, hargasatuan: element2.hargasatuan, id: element2.id, qtyitem: qty ? qty : null })
          //     }
          //   }
          // } else {
          // }

          if (element2.id == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element3 = listChecked.value[z];
              if (element3.namaproduk == element2.namaproduk) {
                listChecked.value.splice(z, 1)
              }
            }
            listChecked.value.push({ namaproduk: element2.namaproduk, hargasatuan: element2.hargasatuan, id: element2.id, qtyitem: 1 })
          }
        }
      } else {
        for (var i = 0; i < d_ProdukDef.value.length; i++) {
          const element2 = d_ProdukDef.value[i];
          if (element2.id == element) {
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
  }
}
const editItems = async (e: any) => {
  if (e.status != 'pending') {
    H.alert('error', 'Order sudah diverifikasi')
    return
  }
  item.NOREC_SO = e.norec
  useApi().get(`/laboratorium/detail-order?norec=${e.norec}`).then((response: any) => {
    for (let x = 0; x < response.length; x++) {
      const element = response[x];
      item.pegawaiOrder = element.objectpegawaiorderfk
      item.tglorder = new Date(element.tglorder)
      item.ruanganTujuan = element.objectruangantujuanfk
      item.keterangan = element.keteranganlainnya
      item.iscito = element.cito
      item.produkCeklis[parseInt(element.produkfk)] = true
    }
    getSelected()
    activeValue.value = 1
  }).catch((e: any) => {
  })

  activeValue.value = 1

}

const hapusAmprah = async (e: any) => {
  item.NOREC_SO = e.norec
  modalHapusAmprah.value = true
  useApi().post(`/laboratorium/hapus-amprah?norec=${e.norec}`).then((response: any) => {
    loadRiwayat()
  }).catch((e: any) => {

  })
}

const DialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusItems(e)

    },
    reject: () => { },
  })
}
const hapusItems = (e: any) => {
  if (e.status != 'pending') {
    H.alert('error', 'Order sudah diverifikasi')
    return
  }
  useApi().post(`/laboratorium/delete-order`, { noorder: e.noorder }).then((response: any) => {
    isLoading.value = false
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const hasilItems = (e: any) => {
  let data: any = []
  e.details.forEach((element: any) => {
    data.push(element.pp_norec)
  });

  router.push({
    name: 'module-laboratorium-hasil-lab',
    query: {
      nocmfk: pasien.value.nocmfk,
      norec_apd: e.norec_apd,
      norec_pd: NOREC_PD,
      norec_pp: '',//data,
    },
  })
}
const hasilItemsBrid = (e: any) => {
  router.push({
    name: 'module-laboratorium-hasil-lab-bridging',
    query: {
      nocmfk: pasien.value.nocmfk,
      norec_apd: e.norec_apd,
      norec_pd: NOREC_PD,
      noorder: e.noorder,//data,
    },
  })
}

const bukaLIS = () => {
  modalLis.value = true

  useApi().get(`/laboratorium/riwayat-order-lis?nocmfk=${ID_PASIEN}`).then((response: any) => {
    dataSourceLIS.value = response.dataLIS
  })
}
const hasilcetakmikro = (e: any) => {
  H.printBlade('laboratorium/cetakan-hasil-culture-new?noregistrasi=' + e.noregistrasi + '&norec_apd=' + e.norec_apd + '&noorder=' + e.noorder + '&norec_so=' + e.norec_so);
}
const hasilcetakLabALL = (e: any) => {
  H.printBlade('laboratorium/get-hasil-pa-bridging-all?noorder=' + e.ONO);
}
const printHasil2 = async (e: any) => {
  let showHiv = false;
  if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1 || kelompokUser.toUpperCase().indexOf('LAB') > -1) {
    showHiv = true;
  }

  H.printBlade('laboratorium/cetakan-hasil-lab-new?noregistrasi=' + e.noregistrasi + '&norec_apd=' + e.norec_apd + '&noorder=' + e.noorder + '&norec_so=' + e.norec + '&showHIV=' + showHiv);
}
const printHasilLabPA = async (e: any) => {
  console.log('e yang masuk data', e);
  H.printBlade(`laboratorium/get-hasil-pa-bridging?noorder=${e.noorder}`);
}
const printHasilLabManual = async (e: any) => {
  const idProduk = e.details.map(element => element.idproduk);
  const norec_pp = e.details.map(element => element.norec_pp);

  H.printBlade('laboratorium/cetakan-hasil-lab-manual?noregistrasi=' + e.noregistrasi + '&norec_apd=' + e.norec_apd + '&product=' + idProduk + '&norec_pp=' + norec_pp + '&norec_so=' + e.norec);
}
const changeSwitch = (e: any) => {
  useApi().postNoMessage(`/laboratorium/update-filter-produk-lab`, { 'isFilterProdukLab': e ? 'true' : 'false' }).then((response: any) => {
    isLoading.value = false
    fetchTindakan(item.ruanganTujuan)
  }).catch((e: any) => {
    isLoading.value = false
  })
}

onMounted(() => {
  pasienByID(ID_PASIEN)
  fetchDropdown()
  getEmr()
})

watch(() => isPaket.value, (newValue, oldValue) => {
  if (newValue == true) {
    fetchPaket()
  }
})
watch(() => selectedTabs,
  (value) => {
    activeValue.value = value
  })

watch(activeValue, (value: any) => {
  emit('update:selected', value)
})
watch(() => activeValue.value, (value) => {
  if (value == 2) {
    loadRiwayat()
  }
})
watch(() => activeValue.value, (value) => {
  if (value == 3) {
    loadRiwayatAll()
  }
})
watch(() => Object.keys(item.ss), (newValue) => {
  if (newValue.length > 0) {
    inimasuk.value = true;
  } else {
    inimasuk.value = false;
  }
}
)
watch(() => listChecked.value.length, (newValue) => {
  if (newValue != historySave.value.length) {
    disabledSave.value = false
    historySave.value = []
  }
  if (newValue == historySave.value.length) disabledSave.value = true
})
watch(() => item.ruanganTujuan, (newValue) => {
  //? BANK DARAH
  // if (newValue == 302) {
  //   pasienByID(ID_PASIEN)
  // }
})

for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i];
  if (i <= 9 && element != 'primary')
    listColor.value.push(element)
}
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/order-laboratorium.scss';

.form-layout.is-separate {
  max-width: 1240px;
}
</style>
