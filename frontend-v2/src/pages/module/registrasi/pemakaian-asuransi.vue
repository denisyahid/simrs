
<template>
  <ConfirmDialog />
  <ConfirmDialog group="templating">
    <template #message="slotProps">
      <div class="items-center w-full gap-4 border-b border-surface-200 dark:border-surface-700">
        <div class="is-flex">
          <i :class="slotProps.message.icon" style="font-size: 25px; color: var(--danger)"
            class="!text-6xl text-primary-500 mr-3"></i>
          <p>{{ slotProps.message.message }}</p>
        </div>
        <VField vertical label="Alasan Menghapus (WAJIB DIISI)" class="mt-5"
          style="text-overflow: initial; text-overflow: initial;">
          <VControl fullwidth>
            <VInput type="text" placeholder="Alasan" autocomplete="off" v-model="item.alasanhapusSEP" />
          </VControl>
        </VField>
      </div>
    </template>
  </ConfirmDialog>
  <section>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top:15px">
        <!-- <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header"></div> -->
          <div class="form-header is-stuck stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Surat Eligibilitas Peserta</h3>
            </div>
            <div class="right">
              <div class="buttons">

                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="batal()">
                  Batal
                </VButton>
                <VButton type="button" color="info" rounded outlined raised icon="feather:external-link"
                  :loading="isLoadingIcare" @click="iCare()">
                  I-CARE </VButton>
                <VButton type="button" color="warning" rounded outlined raised icon="feather:printer" :loading="isCetak"
                  @click="cetakSEP({ noSep: (item.noSep ? item.noSep : '') })" v-if="item.noSep">
                  Cetak </VButton>
                <VButton type="button" color="danger" rounded outlined raised icon="feather:trash" v-if="item.noSep"
                  @click="DialogConfirm()">
                  Hapus </VButton>
                <VButton type="button" color="primary" rounded outlined raised icon="feather:save" :loading="isLoading"
                  @click="save()"> Simpan </VButton>
              </div>
            </div>
          </div>
        </div>
        <div class="form-body">
          <div class="form-section">
            <div class="form-section-inner is-horizontal">
              <VField horizontal label="Pilih">
                <VControl>
                  <VRadio v-model="item.pilih" v-for="items of d_AsalRujukan" :key="items.value"
                    style="margin-top: -10px;" :value="items.value" :label="items.label" name="{{items.value}}"
                    color="primary" @change="changeAsal(items.value)" />
                </VControl>

              </VField>
              <VField horizontal label=" Tanggal SEP">
                <VDatePicker v-model="item.tglSep" style="width: 100%;" trim-weeks mode="date">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
              <VField v-if="item.pilih == 1" horizontal label="Asal Rujukan" required
                class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fas fa-ambulance" fullwidth>
                  <Multiselect mode="single" v-model="item.asal" :options="d_Faskes" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" />
                </VControl>
              </VField>
              <VField v-if="item.pilih == 2" horizontal label="Pelayanan" required
                class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="fas fa-ambulance" fullwidth>
                  <Multiselect mode="single" v-model="item.jnsPelayanan" :options="d_Pelayanan" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off"
                    @select="changePelayanan(item.jnsPelayanan)" />
                </VControl>
              </VField>
              <VField horizontal label="No Kartu / No Rujukan">
                <VControl icon="feather:credit-card" fullwidth>
                  <VInput type="text" placeholder="No Kartu" autocomplete="off" v-model="item.noKartu" />
                </VControl>
                <VIconButton class="ml-3" type="button" color="blue" rounded raised icon="feather:save"
                  :loading="isLoadingKartu" @click="saveKartu()"> Save Kartu </VIconButton>
                <VIconButton class="ml-3" type="button" color="blue" rounded raised icon="feather:search"
                  :loading="isLoadingKartu" @click="cariNoKa()"> List Rujukan </VIconButton>

                <VControl icon="feather:credit-card" class="ml-3" fullwidth>
                  <VInput type="text" placeholder="No Rujukan" autocomplete="off" v-model="item.noKunjungan" />
                </VControl>
                <VButton class="ml-3" type="button" color="info" rounded outlined raised icon="feather:list"
                  :loading="isLoading" @click="fetchRujukan()"> List Rujukan </VButton>
              </VField>
              <VField horizontal label="NIK">
                <VControl icon="feather:credit-card" fullwidth>
                  <VInput type="text" placeholder="NIK" autocomplete="off" v-model="item.nik" />
                </VControl>
                <VIconButton class="ml-3" type="button" color="success" outlined rounded raised icon="feather:search"
                  :loading="isLoadingKartu" @click="cariNIK()"> </VIconButton>


              </VField>

            </div>
            <div class="columns">
              <div class="column is-4">

                <div class="s-card mt-5" style=" border-top: 3px solid var(--danger);">
                  <div class="boxx boxx-widget widget-user-2">
                    <div class="widget-user-header bg-aqua-active" v-if="isLoadingPasien">
                      <VFlexTableCell :column="{ grow: true, media: true }">
                        <VPlaceloadAvatar size="medium" />
                        <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                      </VFlexTableCell>

                    </div>
                    <div class="widget-user-header bg-aqua-active" v-else>
                      <div class="widget-user-image">
                        <img class="img-circle" src="/images/simrs/male.png" alt="User Avatar"
                          v-if="pasien.objectjeniskelaminfk == 1">
                        <img class="img-circle" src="/images/simrs/female.png" alt="User Avatar" v-else>
                      </div>
                      <h4 class="widget-user-username"> {{ pasien.namapasien }}
                      </h4>
                      <p class="widget-user-desc">{{ pasien.nobpjs }}</p>
                      <p class="widget-user-desc">{{ 'No RM ' + pasien.nocm }}</p>
                    </div>
                    <TabView v-model:activeIndex="activeIdx">
                      <TabPanel>
                        <template #header>
                          <i class="fas fa-user"></i>
                        </template>
                        <LoadingPemakaianAsuransi v-if="isLoadingPasien" />
                        <ul class="list-group list-group-unbordered" v-if="!isLoadingPasien">
                          <li class="list-group-item">
                            <span class="fas fa-address-card"></span> <a title="NIK" class="pull-right-container">{{
                              pasien.noidentitas }} </a>
                          </li>
                          <li class="list-group-item">
                            <span class="fa fa-credit-card"></span> <a title="No.Kartu Bapel JKK"
                              class="pull-right-container">{{ pasien.nobpjs }} </a>
                          </li>
                          <li class="list-group-item">
                            <span class="fa fa-phone"></span> <a title="No. HP/Ponsel" class="pull-right-container">{{
                              pasien.nohp }} </a>
                          </li>
                          <li class="list-group-item">
                            <span class="fa fa-calendar"></span> <a title="Tanggal Lahir"
                              class="pull-right-container">{{
                              pasien.tgllahir }}</a>
                          </li>
                          <li class="list-group-item">
                            <span class="fa fa-info-circle"></span> <a title="PISA" class="pull-right-container">{{
                              peserta.statusPeserta.keterangan
                              }}</a>
                          </li>
                          <li class="list-group-item">
                            <span class="fas fa-hospital-user"></span> <a title="Hak Kelas Rawat"
                              class="pull-right-container">{{ peserta.hakKelas.keterangan }} </a>

                          </li>
                          <li class="list-group-item">
                            <span class="fa fa-stethoscope"></span> <a title="Faskes Tingkat 1"
                              class="pull-right-container">{{ peserta.provUmum.kdProvider }} - {{
                              peserta.provUmum.nmProvider }}</a>

                          </li>
                          <li class="list-group-item">
                            <span class="fas fa-calendar-plus"></span> <a title="TMT dan TAT Peserta"
                              class="pull-right-container">{{
                              peserta.tglTMT }}
                              s.d
                              {{ peserta.tglTAT }}</a>

                          </li>
                          <li class="list-group-item">
                            <span class="fas fa-id-card-alt"></span> <a title="Jenis Peserta"
                              class="pull-right-container">{{ peserta.jenisPeserta.keterangan
                              }}</a>

                          </li>
                        </ul>

                      </TabPanel>
                      <TabPanel>
                        <template #header>
                          <i class="fas fa-hospital"></i>
                        </template>
                        <ul class="list-group list-group-unbordered">
                          <li class="list-group-item">
                            <span class="fa fa-sort-numeric-asc"></span> <a title="No. Asuransi"
                              class="pull-right-container"></a>

                          </li>
                          <li class="list-group-item">
                            <span class="fa fa-windows"></span> <a title="Nama Asuransi"
                              class="pull-right-container"></a>
                          </li>
                          <li class="list-group-item">
                            <span class="fa fa-calendar"></span> <a title="TMT dan TAT Asuransi"
                              class="pull-right-container"> s.d </a>

                          </li>
                          <li class="list-group-item">
                            <span class="fa fa-bank"></span> <a title="Nama Badan Usaha" class="pull-right-container">{{
                              peserta.jenisPeserta.keterangan
                              }}</a>

                          </li>
                        </ul>
                      </TabPanel>
                      <TabPanel>
                        <template #header>
                          <i class="fas fa-list-ul"></i>
                        </template>
                        <div class="list-group" style="height: 490px;overflow: auto;">
                          <a href="#" class="list-group-item" v-for="i in 3" v-if="isLoadingHistory">
                            <VPlaceloadText :lines="7" width="95%" last-line-width="20%" class="mx-2" />
                          </a>
                          <a class="list-group-item" v-for="his in histori">
                            <h5 class="list-group-item-heading"><b><u>{{ his.noSep }}</u></b>
                            </h5>
                            <div>
                              <small>{{ his.jnsPelayanan == '1' ? 'Rawat Inap' : 'Rawat Jalan'
                                }}</small>
                              <p class="list-group-item-text"><small></small></p>
                              <p class="list-group-item-text"><small>{{ his.poli }}</small></p>
                              <p class="list-group-item-text"><small> Tgl.SEP. {{ his.tglSep
                                  }}</small>
                              </p>
                              <p class="list-group-item-text"><small>{{ his.noRujukan }}</small>
                              </p>
                              <p class="list-group-item-text"><small>{{ his.diagnosa }}</small>
                              </p>
                              <p class="list-group-item-text"><small>{{ his.ppkPelayanan
                                  }}</small>
                              </p>
                              <VIconButton type="button" raised circle icon="feather:printer" @click="cetakSEP(his)"
                                :loading="isCetak" color="warning" v-tooltip-prime.top="'Cetak SEP '">
                              </VIconButton>
                            </div>
                          </a>
                          <!-- <span>Belum ada histori</span> -->
                        </div>
                        <VButton class="is-fullwidth" type="button" color="dark" rounded outlined raised
                          @click="modalHistory = true" icon="fas fa-cubes"> Histori
                        </VButton>
                      </TabPanel>
                    </TabView>
                  </div>
                </div>
              </div>
              <div class="column is-8">
                <div class="s-card mt-5 p-6" style=" border-top: 3px solid var(--orange);">
                  <span class="status-col  head-sep" style="position: absolute;margin-top: -25px !important"
                    v-if="item.noSep && isSEP_Auto"></span>
                  <h3 class="title is-5 head-sep ml-1">
                    <span v-if="item.noSep && isSEP_Auto"> SEP : {{ item.noSep }}</span>

                    <VField horizontal>
                      <VControl fullwidth>
                        <VSwitchBlock v-model="isSEP_Auto" label="SEP Otomatis" color="danger" />
                      </VControl>
                    </VField>
                    <VField horizontal v-if="!isSEP_Auto">
                      <VControl icon="fas fa-notes-medical" fullwidth>
                        <VInput type="text" placeholder="No SEP" autocomplete="off" v-model="item.noSep"
                          :disabled="isSEP_Auto" />
                      </VControl>
                      <VIconButton class="ml-3" type="button" color="success" outlined rounded raised
                        icon="feather:search" :loading="isLoadingKartu" @click="cariSEPByKartu()"> </VIconButton>
                    </VField>
                  </h3>
                  <VField horizontal label="Sub/Spesialis" required class="is-rounded-select_Z  is-autocomplete-select"
                    v-slot="{ id }"
                    v-if="PILIHPELAYANAN == 'rj' || PILIHPELAYANAN == 'igd' || PILIHPELAYANAN == 'pasca'">
                    <VControl class="cekbox " v-if="PILIHPELAYANAN == 'rj' || PILIHPELAYANAN == 'pasca'">
                      <Checkbox :inputId="'eksek'" :name="'eksek'" :binary="true" v-model="item.eksekutif"
                        class="mt-1" />
                      <label for="eksek" class="ml-2 " style="font-size: 0.75rem;">Eksekutif</label>
                    </VControl>
                    <VControl icon="feather:home" fullwidth class="prime-auto "
                      :class="PILIHPELAYANAN == 'igd' ? '' : 'ml-4'">
                      <AutoComplete v-model="item.spesialis" :suggestions="d_Subspesialis"
                        @complete="fetchSupspesialis($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                        placeholder="ketik spesialis/subspesialis" @change="changeSpesialis(item.spesialis)"
                        :disabled="PILIHPELAYANAN == 'igd'" />

                    </VControl>
                  </VField>

                  <VField horizontal label="DPJP yang Melayani" required
                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" v-if="PILIHPELAYANAN != 'ri'">
                    <VControl icon="feather:users" fullwidth class="prime-auto">
                      <Dropdown v-model="item.dpjpLayan" :options="d_dpjpLayan" :optionLabel="'nama'"
                        placeholder="DPJP yang Melayani" style="width: 100%;" :filter="true" />
                    </VControl>
                  </VField>
                  <VField horizontal label="Asal Rujukan " required v-if="PILIHPELAYANAN == 'ri'">
                    <VControl icon="fas fa-ambulance" fullwidth>
                      <VInput type="text" placeholder="Asal Rujukan " autocomplete="off" v-model="item.asalRS"
                        disabled />
                    </VControl>
                  </VField>
                  <VField horizontal label="PPK Asal Rujukan " required v-if="PILIHPELAYANAN != 'igd'">
                    <VControl icon="lnir lnir-hospital-alt-2" fullwidth>
                      <VInput type="text" placeholder="PPK Asal Rujukan " autocomplete="off" v-model="item.ppkRujukan"
                        disabled />
                    </VControl>
                  </VField>
                  <VField horizontal label="Tgl Rujukan " required v-if="PILIHPELAYANAN != 'igd'">
                    <VControl icon="lnir lnir-calender-alt-4" fullwidth>
                      <VDatePicker v-model="item.tglRujukan" style="width: 100%;" trim-weeks mode="date" disabled>
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl fullwidth>
                              <VInput :value="inputValue" v-on="inputEvents"/>
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                      <!-- <VInput type="text" placeholder="Tgl Rujukan " autocomplete="off" v-model="item.tglRujukan"
                        :disable="PILIHPELAYANAN != 'igd'" /> -->
                    </VControl>
                  </VField>
                  <VField horizontal label="No Surat Kontrol/SKDP" required v-if="(PILIHPELAYANAN == 'rj'
                      || PILIHPELAYANAN == 'pasca') && isRujukInternal == false && kunjunganPertama == false">
                    <VControl icon="lnir lnir-file-name" fullwidth>
                      <VInput type="text" placeholder="No Surat Kontrol/SKDP " autocomplete="off"
                        v-model="item.noSurat" />

                    </VControl>
                    <VIconButton class="ml-3" type="button" color="info" rounded outlined raised icon="feather:search"
                      :loading="isLoadingSKDP" @click="cariSKDP()"> List Rujukan
                    </VIconButton>
                  </VField>
                  <VField horizontal label="No SPRI" required v-if="PILIHPELAYANAN == 'ri'">
                    <VControl icon="lnir lnir-file-name" fullwidth>
                      <VInput type="text" placeholder="No Surat Kontrol/SKDP " autocomplete="off"
                        v-model="item.noSurat" />
                    </VControl>
                    <VIconButton class="ml-3" type="button" color="info" rounded outlined raised icon="feather:search"
                      :loading="isLoadingSKDP" @click="cariSKDP()"> </VIconButton>
                  </VField>

                  <!-- <VField horizontal label="No Rujukan" required v-if="PILIHPELAYANAN == 'ri'">
                                    <VControl icon="lnir lnir-add-files" fullwidth>
                                        <VInput type="text" placeholder="No Rujukan " autocomplete="off"
                                            v-model="item.noKunjungan" />
                                    </VControl>
                                                                                                                                                                                                                                                                                        </VField> -->
                  <VField horizontal label="DPJP Pemberi Surat SKDP/SPRI" required
                    v-if="PILIHPELAYANAN != 'igd' && isRujukInternal == false && kunjunganPertama == false">
                    <VControl icon="fas fa-user-nurse" fullwidth>
                      <VInput type="text" placeholder="DPJP Pemberi Surat SKDP/SPRI " autocomplete="off"
                        v-model="item.namaDPJP" disabled />
                    </VControl>
                  </VField>
                  <VField horizontal label="Kelas Rawat" class="is-rounded-select_Z  is-autocomplete-select"
                    v-slot="{ id }" v-if="PILIHPELAYANAN == 'ri'">
                    <VControl icon="fas fa-arrow-up" fullwidth class="prime-auto">
                      <Dropdown v-model="item.klsRawatHak" :options="d_KelasRawat" :optionLabel="'nama'"
                        placeholder="Kelas" style="width: 100%;" :filter="true" />
                    </VControl>
                    <VControl class="cekbox ">
                      <Checkbox :inputId="'naik'" :name="'city1'" :binary="true" v-model="item.naikKelas"
                        class="mt-1" />
                      <label for="naik" class="ml-2 " style="font-size: 0.75rem;">Naik Kelas</label>
                    </VControl>
                  </VField>
                  <VField horizontal label="-" v-if="PILIHPELAYANAN == 'ri'">
                    <label style="color: red; font-size: 0.8rem;">Ketentuan kenaikan kelas dikecualikan
                      untuk
                      Peserta kelas 3 (Permenkes 3/2023)</label>
                  </VField>

                  <div class="form-section is-grey mb-2" v-if="item.naikKelas == true && PILIHPELAYANAN == 'ri'">
                    <div class="form-section-inner is-horizontal">
                      <VField horizontal label="Kelas Rawat Inap " class="is-rounded-select_Z  is-autocomplete-select"
                        v-slot="{ id }">
                        <VControl icon="fas fa-bed" fullwidth class="prime-auto">
                          <Dropdown v-model="item.klsRawatNaik" :options="d_KelasNaik" :optionLabel="'nama'"
                            placeholder="Kelas" style="width: 100%;" :filter="true" />
                        </VControl>
                      </VField>
                      <VField horizontal label="Pembiayaan" class="is-rounded-select_Z  is-autocomplete-select"
                        v-slot="{ id }">
                        <VControl icon="fas fa-credit-card" fullwidth class="prime-auto">
                          <Dropdown v-model="item.pembiayaan" :options="d_Pembiayaan" :optionLabel="'nama'"
                            placeholder="Pembiayaan" style="width: 100%;" :filter="true" />
                        </VControl>
                      </VField>

                      <VField horizontal label="Penanggung Jawab">
                        <VControl fullwidth>
                          <VTextarea class="textarea" v-model="item.penanggungJawab" rows="2"
                            placeholder="Jika Pembiayaan Oleh [Pemberi Kerja] atau [Asuransi Kesehatan Tambahan]"
                            autocomplete="off" autocapitalize="off" spellcheck="true" />

                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <VField horizontal label="Diagnosa" class="is-rounded-select_Z  is-autocomplete-select"
                    v-slot="{ id }">
                    <VControl icon="fas fa-diagnoses" fullwidth class="prime-auto">
                      <AutoComplete v-model="item.diagAwal" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                        :optionLabel="'nama'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'nama'" placeholder="ketik diagnosa" />
                    </VControl>
                  </VField>
                  <VField horizontal label="No Telepon" required>
                    <VControl icon="feather:phone" fullwidth>
                      <VInput type="text" placeholder="No Telepon" autocomplete="off" v-model="item.noTelepon" />
                    </VControl>
                    <VControl class="cekbox ">
                      <Checkbox :inputId="'cob'" :name="'cob'" :binary="true" v-model="item.cob" class="mt-1" />
                      <label for="cob" class="ml-2 " style="font-size: 0.75rem;">Peserta COB</label>
                    </VControl>
                  </VField>
                  <VField horizontal label="Catatan">
                    <VControl fullwidth>
                      <VTextarea class="textarea" v-model="item.catatan" rows="2" placeholder="Catatan ..."
                        autocomplete="off" autocapitalize="off" spellcheck="true" />

                    </VControl>
                  </VField>
                  <VField horizontal label="Katarak" v-if="PILIHPELAYANAN == 'ri'">
                    <Checkbox :inputId="'katarak'" :name="'katarak'" :binary="true" v-model="item.katarak"
                      class="mt-1 mr-2" />

                    <VControl class="cekbox " fullwidth>
                      <p class="text-muted well well-sm no-shadow"> <i class="fa fa-check"></i>, Jika
                        Peserta Tersebut Mendapatkan Surat Perintah Operasi katarak</p>
                    </VControl>
                  </VField>
                  <VField horizontal label="Status Kecelakaan" required
                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="fas fa-biohazard" fullwidth class="prime-auto">
                      <Dropdown v-model="item.lakaLantas" :options="d_LakaLantas" :optionLabel="'nama'"
                        placeholder="laka Lantas" style="width: 100%;" :filter="true"
                        @change="changeLaka(item.lakaLantas)" />
                      <!-- <Multiselect mode="single" v-model="item.lakaLantas" :options="d_LakaLantas"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                                                                                                                                                                                @select="changeLaka(item.lakaLantas)" /> -->
                    </VControl>
                  </VField>
                  <!-- <VField horizontal label="Flag Procedure" required
                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" v-if="PILIHPELAYANAN == 'rj'">
                                    <VControl icon="fas fa-flag" fullwidth class="prime-auto">
                                        <Dropdown v-model="item.flagProcedure" :options="d_FlagProc" :optionLabel="'nama'"
                                            placeholder="Flag Procedure" style="width: 100%;" :filter="true"
                                            @change="changeFlagProcedur(item.flagProcedure)" />
                                    </VControl>
                                </VField>
                                <VField horizontal label="Penunjang" required
                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }"
                                    v-if="PILIHPELAYANAN == 'rj'">
                                    <VControl icon="fas fa-flask" fullwidth class="prime-auto">
                                        <Dropdown v-model="item.kdPenunjang" :options="d_Penunjang" :optionLabel="'nama'"
                                            placeholder="Penunjang" style="width: 100%;" :filter="true" />
                                    </VControl>
                                </VField> -->
                  <!-- <VField horizontal label="Assesment" required
                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                    <VControl icon="fas fa-folder-plus" fullwidth class="prime-auto">
                                        <Dropdown v-model="item.assesmentPel" :options="d_Assesment" :optionLabel="'nama'"
                                            placeholder="Assesment" style="width: 100%;" :filter="true" />
                                    </VControl>
                                                                                                                                                                                                                                    </VField> -->
                  <div class="form-section is-grey mb-2"
                    v-if="item.lakaLantas != undefined && item.lakaLantas.kode != 0">
                    <div class="form-section-inner is-horizontal">
                      <VField horizontal label=" Tanggal Kejadian">
                        <VDatePicker v-model="item.tglKejadian" style="width: 100%;" trim-weeks mode="date">
                          <template #default="{ inputValue, inputEvents }">
                            <VField>
                              <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </VField>
                      <VField horizontal label="No. LP" required>
                        <VControl icon="feather:alert-circle" fullwidth>
                          <VInput type="text" placeholder="No Laporan Polisi" autocomplete="off" v-model="item.noLP" />
                        </VControl>
                      </VField>

                      <VField horizontal label="Lokasi " class="is-rounded-select_Z  is-autocomplete-select"
                        v-slot="{ id }">
                        <VControl icon="fas fa-map" fullwidth class="prime-auto">
                          <Dropdown v-model="item.kdPropinsi" :options="d_Provinsi" :optionLabel="'nama'"
                            placeholder="Provinsi" style="width: 100%;" :filter="true"
                            @change="changeProv(item.kdPropinsi)" />

                        </VControl>
                      </VField>
                      <VField horizontal label="." class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="fas fa-map" fullwidth class="prime-auto">
                          <Dropdown v-model="item.kdKabupaten" :options="d_Kota" :optionLabel="'nama'"
                            placeholder="Kota/Kab" style="width: 100%;" :filter="true"
                            @change="changeKota(item.kdKabupaten)" />
                        </VControl>
                      </VField>
                      <VField horizontal label="." class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="fas fa-map" fullwidth class="prime-auto">
                          <Dropdown v-model="item.kdKecamatan" :options="d_Kecamatan" :optionLabel="'nama'"
                            placeholder="Kecamatan" style="width: 100%;" :filter="true" />
                        </VControl>
                      </VField>
                      <VField horizontal label="Keterangan Kejadian">
                        <VControl fullwidth>
                          <VTextarea class="textarea" v-model="item.keterangan" rows="2"
                            placeholder="ketik keterangan kejadian ..." autocomplete="off" autocapitalize="off"
                            spellcheck="true" />

                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <VField horizontal label="Tujuan Kunjungan" class="is-rounded-select_Z  is-autocomplete-select"
                    v-slot="{ id }" v-if="PILIHPELAYANAN == 'rj' || PILIHPELAYANAN == 'pasca'">
                    <VControl icon="fas fa-calendar" fullwidth class="prime-auto">
                      <Dropdown v-model="item.tujuanKunj" :options="d_TujuanKunjungan" :optionLabel="'nama'"
                        placeholder="Tujuan Kunjungan" style="width: 100%;" :filter="true" :showClear="true"
                        @change="changeTujuanKunjungan(item.tujuanKunj)" />
                    </VControl>
                  </VField>
                  <VField horizontal label="Prosedur" class="is-rounded-select_Z  is-autocomplete-select"
                    v-slot="{ id }" v-if="(PILIHPELAYANAN == 'rj' || PILIHPELAYANAN == 'pasca') && isProsedur">
                    <VControl icon="fas fa-flag" fullwidth class="prime-auto">
                      <Dropdown v-model="item.flagProcedure" :options="d_FlagProc" :optionLabel="'nama'"
                        placeholder="Flag Procedure" style="width: 100%;" :filter="true" :showClear="true"
                        @change="changeFlagProcedur(item.flagProcedure)" />
                    </VControl>
                  </VField>
                  <VField horizontal label="Penunjang" class="is-rounded-select_Z  is-autocomplete-select"
                    v-slot="{ id }" v-if="PILIHPELAYANAN == 'rj' && isProsedur">
                    <VControl icon="fas fa-flask" fullwidth class="prime-auto">
                      <Dropdown v-model="item.kdPenunjang" :options="d_Penunjang" :optionLabel="'nama'"
                        placeholder="Penunjang" style="width: 100%;" :filter="true" :showClear="true" />
                    </VControl>
                  </VField>
                  <VField horizontal label="Assesment" class="is-rounded-select_Z  is-autocomplete-select"
                    v-slot="{ id }" v-if="(PILIHPELAYANAN == 'rj' || PILIHPELAYANAN == 'pasca') && !isProsedur">
                    <VControl icon="fas fa-clipboard-list" fullwidth class="prime-auto">
                      <Dropdown v-model="item.assesmentPel" :options="d_Assesment" :optionLabel="'nama'"
                        placeholder="Assesment" style="width: 100%;" :filter="true" :showClear="true" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Dialog :header="item.asal == 1 ? 'Rujukan Faskes Tingkat 1' : 'Rujukan Faskes Tingkat 2'"
      v-model:visible="modalRujukan" :breakpoints="{ '960px': '75vw', '640px': '90vw' }" :style="{ width: '50vw' }"
      :maximizable="true" :modal="true">
      <div class="columns is-multiline" v-if="isLoadingRujukan">
        <div v-for="key in 2" :key="key" class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-2">
              <VPlaceloadText :lines="1" style="margin-top:2.5rem" />
            </div>
            <div class="column is-10">
              <VCard>
                <div class="tile-grid-item-inner placeload-wrap is-flex">
                  <VPlaceloadAvatar size="medium" />
                  <VPlaceloadText width="90%" last-line-width="60%" class="mx-2" :lines="4" />
                </div>
              </VCard>
            </div>
          </div>
        </div>
      </div>
      <div class="" v-else>
        <div class="update-item is-dark-bordered-12" style="display: block;" v-if="listRujukan.length == 0">
          <VPlaceholderPage :title="H.assets().notFound" larger>
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
        </div>
        <div class="list-widget" v-if="listRujukan.length > 0">
          <VField horizontal>
            <VControl icon="feather:search" fullwidth>
              <VInput type="text" placeholder="Cari Poli, PPK Asal" autocomplete="off" v-model="filtersRujukan" />
            </VControl>
          </VField>
          <div class="timeline-wrapper" v-if="listRujukan.length > 0">

            <div class="timeline-header"></div>
            <div class="timeline-wrapper-inner pt-0">
              <div class="timeline-container">
                <div class="timeline-item is-unread is-clickable" v-for="(item, index)  in listRujukanFiltered"
                  :key="item.noKunjungan">
                  <div class="date">
                    <span>{{ item.tglKunjungan }}</span>
                  </div>
                  <div :class="'dot is-' + item.color"></div>
                  <div class="content-wrap is-grey">
                    <div class="content-box ">
                      <div class="status"></div>
                      <VIconBox size="small" :color="'warning'" rounded>
                        <i class="fas fa-notes-medical" aria-hidden="true"></i>
                      </VIconBox>
                      <div class="box-text" style="width:70%">
                        <div class="meta-text">
                          <table class="tb-order">
                            <tr>
                              <td><span class="mt-5">No Rujukan</span></td>
                              <td>:</td>
                              <td class="text-value">
                                <VButton rounded style="margin-top:-10px" raised circle
                                  v-tooltip.bubble="'Pilih Rujukan'" @click="setRujukan(item)"
                                  :loading="item.loadingRujukan">
                                  {{ item.noKunjungan }}
                                </VButton>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3">
                                <div class="mt-3"></div>
                              </td>
                            </tr>
                            <tr class="mt-2">
                              <td>No Kartu </td>
                              <td>:</td>
                              <td class="text-value">{{ item.peserta.noKartu }} </td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td class="text-value">{{ item.peserta.nama }} </td>
                            </tr>
                            <tr>
                              <td>PPK Perujuk</td>
                              <td>:</td>
                              <td class="text-value">{{ item.provPerujuk.nama }} </td>
                            </tr>
                            <tr>
                              <td>Sub/Spesialis</td>
                              <td>:</td>
                              <td class="text-value">{{ item.poliRujukan.nama }} </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <div class="box-end" style="width:30%">
                        <div class="columns is-multiline">
                          <div class="column is-12 mt-3">
                            <div class="status is-pulled-right mt-2 ml-2"></div>
                            <VTag :label="'Exp : ' + item.tglExpired" :color="item.color_status" rounded
                              class="is-pulled-right" />
                          </div>
                          <div class="column is-12 ">
                            <VIconButton icon="feather:file-text" @click="setRujukan(item)" raised circle
                              class="mr-2 is-pulled-right" v-tooltip.bubble="'Pilih Rujukan'">
                            </VIconButton>
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
      <template #footer>

        <VButton icon="feather:corner-up-right" light dark-outlined @click="isRujukLuar = true">
          Rujukan Keluar
        </VButton>

      </template>
    </Dialog>
    <Dialog :header="'Rujukan Keluar'" v-model:visible="isRujukLuar" :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
      :style="{ width: '80vw' }" :maximizable="true" :modal="true">
      <RujukanKeluar />
    </Dialog>
    <Dialog :header="'Rencana Kontrol/Inap'" v-model:visible="modalSKDP"
      :breakpoints="{ '960px': '75vw', '640px': '90vw' }" :style="{ width: '65vw' }" :maximizable="true" :modal="true">
      <div class="columns is-multiline">
        <div class="column is-12">
          <TabView v-model:activeIndex="activeIdxSurkon">
            <TabPanel header="List Rencana Kontrol/RI">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VField class="is-rounded-select is-autocomplete-select
                      mt-0 pt-0" v-slot="{ id }">
                    <VLabel>Filter</VLabel>
                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                      <Dropdown v-model="input.filter" :options="d_Filter" :optionLabel="'nama'" class="is-rounded"
                        placeholder="Filter" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField class="is-rounded-select is-autocomplete-select
                      mt-0 pt-0" v-slot="{ id }">
                    <VLabel>Tahun</VLabel>
                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                      <Dropdown v-model="input.tahun" :options="d_Tahun" :optionLabel="'nama'" class="is-rounded"
                        placeholder="Tahun" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField class="is-rounded-select is-autocomplete-select
                      mt-0 pt-0" v-slot="{ id }">
                    <VLabel>Bulan</VLabel>
                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                      <Dropdown v-model="input.bulan" :options="d_Bulan" :optionLabel="'nama'" class="is-rounded"
                        placeholder="Bulan" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VLabel class="required-field">No. Kartu</VLabel>
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="item.noKartu" placeholder="No. Kartu" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <VIconButton icon="feather:search" @click="cariSKDP" color="success" raised :loading="isLoadingSKDP"
                    circle class="mt-5">
                  </VIconButton>
                </div>
              </div>
              <div class="columns is-multiline" v-if="isLoadingSKDP">
                <div v-for="key in 2" :key="key" class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <VPlaceloadText :lines="1" style="margin-top:2.5rem" />
                    </div>
                    <div class="column is-10">
                      <VCard>
                        <div class="tile-grid-item-inner placeload-wrap is-flex">
                          <VPlaceloadAvatar size="medium" />
                          <VPlaceloadText width="90%" last-line-width="60%" class="mx-2" :lines="4" />
                        </div>
                      </VCard>
                    </div>
                  </div>
                </div>
              </div>
              <div class="" v-else>
                <div class="update-item is-dark-bordered-12" style="display: block;" v-if="listSurkon.length == 0">
                  <VPlaceholderPage :title="H.assets().notFound" larger>
                    <template #image>
                      <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                    </template>
                  </VPlaceholderPage>
                </div>
                <div class="list-widget" v-if="listSurkon.length > 0">

                  <div class="timeline-wrapper" v-if="listSurkon.length > 0">
                    <div class="timeline-header"></div>
                    <div class="timeline-wrapper-inner pt-0">
                      <div class="timeline-container">
                        <div class="timeline-item is-unread is-clickable" v-for="(item, index)  in listSurkon"
                          :key="item.noSuratKontrol">
                          <div class="date">
                            <span>{{ item.tglRencanaKontrol }}</span>
                          </div>
                          <div :class="'dot is-' + item.color"></div>
                          <div class="content-wrap is-grey">
                            <div class="content-box ">
                              <div class="status"></div>
                              <VIconBox size="small" :color="'warning'" rounded>
                                <i class="fas fa-notes-medical" aria-hidden="true"></i>
                              </VIconBox>
                              <div class="box-text" style="width:70%">
                                <div class="meta-text">
                                  <table class="tb-order">
                                    <tr>
                                      <td><span class="mt-5">No Surat</span></td>
                                      <td>:</td>
                                      <td class="text-value">
                                        <VButton rounded style="margin-top:-10px" raised circle
                                          v-tooltip.bubble="'Pilih Surat'" @click="setSurat(item)">
                                          {{ item.noSuratKontrol }}
                                        </VButton>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <div class="mt-3"></div>
                                      </td>
                                    </tr>
                                    <tr class="mt-2">
                                      <td>Kontrol/Inap </td>
                                      <td>:</td>
                                      <td class="text-value">{{ item.namaJnsKontrol }} </td>
                                    </tr>
                                    <tr class="mt-2">
                                      <td>Tgl Entri </td>
                                      <td>:</td>
                                      <td class="text-value">{{ item.tglTerbitKontrol }} </td>
                                    </tr>
                                    <tr>
                                      <td>No SEP Asal</td>
                                      <td>:</td>
                                      <td class="text-value">{{ item.noSepAsalKontrol }} </td>
                                    </tr>
                                    <tr>
                                      <td>Poli Asal</td>
                                      <td>:</td>
                                      <td class="text-value">{{ item.namaPoliAsal }} </td>
                                    </tr>
                                    <tr>
                                      <td>Poli Tuju</td>
                                      <td>:</td>
                                      <td class="text-value">{{ item.namaPoliTujuan }} </td>
                                    </tr>
                                    <tr>
                                      <td>DPJP</td>
                                      <td>:</td>
                                      <td class="text-value">{{ item.namaDokter }} </td>
                                    </tr>
                                    <tr>
                                      <td>No Kartu</td>
                                      <td>:</td>
                                      <td class="text-value">{{ item.noKartu }} </td>
                                    </tr>
                                    <tr>
                                      <td>Nama Lengkap</td>
                                      <td>:</td>
                                      <td class="text-value">{{ item.nama }} </td>
                                    </tr>

                                  </table>
                                </div>
                              </div>
                              <div class="box-end" style="width:30%">
                                <div class="columns is-multiline">
                                  <div class="column is-12 mt-3">
                                    <div class="status is-pulled-right mt-2 ml-2"></div>
                                    <VTag :label="'Terbit SEP : ' + item.terbitSEP"
                                      :color="item.terbitSEP == 'Sudah' ? 'danger' : 'success'" rounded
                                      class="is-pulled-right" />
                                  </div>
                                  <div class="column is-12 ">
                                    <VIconButton icon="feather:file-text" @click="setSurat(item)" raised circle
                                      class="mr-2 is-pulled-right" v-tooltip.bubble="'Pilih Surat'">
                                    </VIconButton>
                                    <VIconButton icon="feather:printer" @click="cetakSurkon(item)" raised color="info"
                                      circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Cetak '">
                                    </VIconButton>
                                    <VIconButton icon="feather:edit" @click="editSurkon(item)" raised color="warning"
                                      circle class="mr-2 is-pulled-right" v-tooltip.bubble="'Edit '"
                                      v-if="item.terbitSEP != 'Sudah'">
                                    </VIconButton>
                                    <VIconButton icon="feather:trash" @click="hapusSurkon(item)"
                                      :loading="item.loadHapus" raised color="danger" circle
                                      class="mr-2 is-pulled-right" v-if="item.terbitSEP != 'Sudah'"
                                      v-tooltip.bubble="'Hapus'">
                                    </VIconButton>
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
            </TabPanel>
            <TabPanel header="Buat Baru">
              <div class="columns is-multiline">
                <div class="column is-6">
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

                <div class="column is-3" v-if="input.jenis == 1">
                  <VField>
                    <VLabel class="required-field">No. Kartu</VLabel>
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="input.noKartu" placeholder="No. Kartu" class="is-rounded_Z" />
                    </VControl>
                  </VField>
                </div>
                
                <!-- <div class="column is-2">
                    <VIconButton icon="feather:search" @click="setJenisPel" color="success" raised :loading="isLoadingPasien2"
                      circle class="mt-5">
                    </VIconButton>
                  </div> -->
                <div class="column is-12">
                  <div class="columns is-multiline">

                    <div class="column is-12">
                      <div class="s-card mt-0 p-6" style=" border-top: 3px solid var(--orange);">
                        <h3 class="title is-5 head-sep ml-1">
                          <span v-if="noSuratKontrol"> {{ noSuratKontrol }}</span>
                        </h3>
                        <VField horizontal label="No. Rujukan Peserta" v-if="input.jenis == 2">
                          <span class="mr-2">{{ rujukanAktif.length == 0 ? '-' : rujukanAktif.noKunjungan }}</span>
                          <VTag :label="rujukanAktif.length == 0 ? '' : 'Exp : ' + rujukanAktif.tglExpired"
                            :color="rujukanAktif.length == 0 ? '' : rujukanAktif.color_status" rounded
                            class="is-pulled-right"></VTag>
                        </VField>
                        <VField horizontal label="No. SEP" required v-if="input.jenis == 2">
                          <VControl icon="fas fa-ambulance" fullwidth>
                            <VInput type="text" placeholder="No. SEP" autocomplete="off" v-model="input.noSEP" />
                          </VControl>
                        </VField>
                        <VField horizontal label="Tgl. Rencana Kontrol / Inap">
                          <Calendar v-model="input.tglRencanaKontrol" selectionMode="single" :manualInput="true"
                            style="width: 50%;" :showIcon="true" :showTime="false" hourFormat="24"
                            :date-format="'yy-mm-dd'" />
                          <!-- <VDatePicker v-model="input.tglRencanaKontrol" style="width: 50%;" trim-weeks mode="date">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker> -->
                        </VField>

                        <!-- <VField horizontal label="Pelayanan" required>
                            <VControl icon="fas fa-ambulance" fullwidth>
                              <VInput type="text" placeholder="Pelayanan" autocomplete="off" v-model="input.pelayanan"
                                disabled />
                            </VControl>
                          </VField> -->
                        <VField horizontal label="No. Surat Kontrol" required>
                          <VControl icon="lnir lnir-hospital-alt-2" fullwidth>
                            <VInput type="text" placeholder="No. Surat Kontrol" autocomplete="off"
                              v-model="input.noSuratKontrol" disabled />
                          </VControl>
                        </VField>
                        <VField horizontal label="Sub/Spesialis" required
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:home" fullwidth class="prime-auto ">
                            <AutoComplete v-model="input.poliKontrol" :suggestions="d_Subspesialis_Sur"
                              @complete="fetchSupspesialisSur($event)" :optionLabel="'nama'" :dropdown="true"
                              :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                              placeholder="ketik spesialis/subspesialis" @item-select="changeSpe(input.poliKontrol)" />
                            <!-- <Dropdown v-model="input.poliKontrol" :options="d_Subspesialis" :optionLabel="'namaPoli'"
                                placeholder="Sub/Spesialis" style="width: 100%;" :filter="true" @change="changeSpe(input.poliKontrol)" /> -->
                          </VControl>
                        </VField>
                        <VField horizontal label="DPJP Tujuan Kontrol / Inap" required
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:users" fullwidth class="prime-auto">
                            <Dropdown v-model="input.kodeDokter" :options="d_dpjpLayan_Sur" :optionLabel="'namaDokter'"
                              placeholder="DPJP Tujuan Kontrol / Inap" style="width: 100%;" :filter="true" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="mt-2 is-pulled-right">
                        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="batalSurkon()">
                          Batal
                        </VButton>

                        <VButton type="button" class="ml-2" rounded outlined raised icon="feather:file-text"
                          v-if="noSuratKontrol"
                          @click="setSurat({ noSuratKontrol: noSuratKontrol, namaDokter: input.kodeDokter.namaDokter })">
                          Pilih
                          Surkon </VButton>
                        <VButton type="button" color="primary" class="ml-2" rounded outlined raised icon="feather:save"
                          :loading="isLoadingSur" @click="saveSurkon()"> {{ input.noSuratKontrol ? 'Edit' : 'Simpan' }}
                        </VButton>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="column is-12" v-if="input.jenis == 2">
                  <div class="s-card mt-5-min " style=" border-top: 3px solid var(--danger);">
                    <h3 class="title is-5 mt-2-min mb-1" style="font-size:1rem">
                      <span>History Pelayanan</span>
                    </h3>
                    <HistoryPelayananBPJS :dataSourceHistory="dataSourceHistory" :loadingMon="loadingMon"
                      @pilihSEP="pilihSEPHistory" @cariMon="cariMon">
                    </HistoryPelayananBPJS>
                  </div>
                </div>
              </div>
            </TabPanel>
          </TabView>
        </div>
      </div>
      <template #footer>
      </template>
    </Dialog>
  </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Dialog from 'primevue/dialog';
import Checkbox from 'primevue/checkbox';
import Dropdown from 'primevue/dropdown';
import LoadingPemakaianAsuransi from './loading-pemakaian-asuransi.vue'
import * as qzService from '/@src/utils/qzTrayService'
import Calendar from 'primevue/calendar';

import HistoryPelayananBPJS from './history-pelayanan-bpjs.vue'
import moment from 'moment'
import sleep from '/@src/utils/sleep'
import RujukanKeluar from '../integrasi-sistem/rujukan.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
const confirm = useConfirm()
declare const grecaptcha: any;
useHead({
  title: 'Pemakaian Asuransi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const d_AsalRujukan: any = ref([
  { value: 1, label: 'Rujukan' },
  { value: 2, label: 'Rujukan Manual/IGD/RI' },
  { value: 3, label: 'Pasca Rawat' },
])
const d_Faskes: any = ref([
  { value: 1, label: 'Faskes Tingkat 1' },
  { value: 2, label: 'Faskes Tingkat 2' },
])
const d_Pelayanan: any = ref([
  { value: 2, label: 'Rawat Jalan' },
  { value: 1, label: 'Rawat Inap' },
])

const d_LakaLantas: any = ref([
  { kode: 0, nama: 'Bukan Kecelakaan' },
  { kode: 1, nama: 'Kecelakaan LaluLintas dan bukan Kecelakaan Kerja' },
  { kode: 2, nama: 'Kecelakaan LaluLintas dan Kecelakaan Kerja' },
  { kode: 3, nama: 'Kecelakaan Kerja' },
])
const d_TujuanKunjungan: any = ref([
  { kode: "0", nama: 'Normal' },
  { kode: "1", nama: 'Prosedur' },
  { kode: "2", nama: 'Konsul Dokter' },
])
const d_KelasRawat: any = ref([
  { kode: 1, nama: 'Kelas 1' },
  { kode: 2, nama: 'Kelas 2' },
  { kode: 3, nama: 'Kelas 3' },
])

const d_KelasNaik: any = ref([
  { kode: 1, nama: 'VVIP' },
  { kode: 2, nama: 'VIP' },
  { kode: 3, nama: 'Kelas 1' },
  { kode: 4, nama: 'Kelas 2' },
  { kode: 5, nama: 'Kelas 3' },
  { kode: 6, nama: 'ICCU' },
  { kode: 7, nama: 'ICU' },
])
const d_Pembiayaan: any = ref([
  { kode: 1, nama: 'Pribadi' },
  { kode: 2, nama: 'Pemberi Kerja' },
  { kode: 3, nama: 'Asuransi Kesehatan Tambahan' },

])
const d_FlagProc = ref([
  {
    kode: "0", nama: "Prosedur Tidak Berkelanjutan",
    details: [
      { kode: "7", nama: "Laboratorium" },
      { kode: "8", nama: "USG" },
      { kode: "11", nama: "MRI" },
      { kode: "9", nama: "Farmasi" },
      { kode: "10", nama: "Lain-Lain" },
    ]
  },
  {
    kode: "1", nama: "Prosedur dan Terapi Berkelanjutan",
    details: [
      { kode: "1", nama: "Radioterapi" },
      { kode: "2", nama: "Kemoterapi" },
      { kode: "3", nama: "Rehabilitasi Medik" },
      { kode: "4", nama: "Rehabilitasi Psikososial" },
      { kode: "5", nama: "Transfusi Darah" },
      { kode: "6", nama: "Pelayanan Gigi" },
      { kode: "12", nama: "HEMODIALISA" },
    ]
  },
])
const d_Assesment = ref([
  { kode: "1", nama: "Poli spesialis tidak tersedia pada hari sebelumnya" },
  { kode: "2", nama: "Jam Poli telah berakhir pada hari sebelumnya" },
  { kode: "3", nama: "Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya" },
  { kode: "4", nama: "Atas Instruksi RS" },
  { kode: "5", nama: "Tujuan Kontrol" },
])
const d_Bulan: any = ref(H.monthList())
const d_Tahun: any = ref(H.yearList())
const d_Penunjang: any = ref([])
const d_Provinsi: any = ref([])
const d_Kota: any = ref([])
const d_Kecamatan: any = ref([])
const d_Subspesialis: any = ref([])
const d_dpjpLayan: any = ref([])
const d_dpjpLayan_Sur: any = ref([])
const d_Subspesialis_Sur: any = ref([])
const d_Diagnosa: any = ref([])
const modalRujukan: any = ref(false)
const modalSKDP: any = ref(false)
const listRujukan: any = ref([])
const PILIHPELAYANAN: any = ref(false)
const ISFLAG: any = ref(false)
const isProsedur: any = ref(false)
const isSEP_Auto: any = ref(true)
const isLoadingIcare: any = ref(false)
const isLoadingSur: any = ref(false)
const isRujukInternal: any = ref(false)
const modalHistory: any = ref(false)
const isCetak: any = ref(false)
const isRujukLuar: any = ref(false)
const router = useRouter()
const kunjunganPertama: any = ref(false)
const dataSourceHistory: any = ref([])
const dataRefPoliJKN:any = ref([])
const loadingMon: any = ref(false)
const rujukanAktif:any = ref([])
const item: any = reactive({
  tglSep: new Date(),
  lakaLantas: d_LakaLantas.value[0],
  pilih: d_AsalRujukan.value[0].value,
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  asal: d_Faskes.value[0].value,
  tglKejadian: new Date(),
  jnsPelayanan: d_Pelayanan.value[0].value,
  asalRS: 'Faskes Tingkat 2',

})
const d_Filter: any = ref([{ kode: 2, nama: 'Tgl Rencana Kontrol' }, { kode: 1, nama: 'Tgl Entri' }])
const bulanAyena = d_Bulan.value[new Date().getMonth()]

const input: any = ref({
  tanggal: new Date(),
  tahun: {
    kode: new Date().getFullYear(),
    nama: new Date().getFullYear(),
  },
  bulan: bulanAyena,
  filter: d_Filter.value[0]
})

const sep: any = ref({})
const noSuratKontrol = ref()
const LOG: any = ref('')
const isLoading: any = ref(false)
const isLoadingPasien: any = ref(false)
const isLoadingPasien2: any = ref(false)
const isLoadingRujukan: any = ref(false)
const isLoadingKartu: any = ref(false)
const isLoadingSKDP: any = ref(false)
const isLoadingHistory: any = ref(false)
const pasien: any = ref({})
const peserta: any = ref({
  hakKelas: {},
  jenisPeserta: {},
  mr: {},
  provUmum: {},
  statusPeserta: {},
  cob: {}
})
const filtersRujukan: any = ref('')
const registrasi: any = ref({})
const rujukanLast: any = ref({})
const histori: any = ref([])
const listSurkon: any = ref([])
const activeIdx: any = ref(0)
const activeIdxSurkon: any = ref(0)
const SETTING: any = ref({})
const listRujukanFiltered = computed(() => {
  if (!filtersRujukan.value) {
    return listRujukan.value
  }

  return listRujukan.value.filter((items: any) => {
    return (
      items.poliRujukan.nama.match(new RegExp(filtersRujukan.value, 'i'))
      ||
      items.provPerujuk.nama.match(new RegExp(filtersRujukan.value, 'i'))
    )
  })
})
const pasienByID = async (id: any) => {
  isLoadingPasien.value = true

  await useApi().get(
    `/registrasi/pemakaian-asuransi?id=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`).then(async (response: any) => {
      pasien.value = response.pasien
      item.noKartu = (pasien.value.nobpjs == null || pasien.value.nobpjs == '') ? '0000000000000' : pasien.value.nobpjs
      item.nik = pasien.value.noidentitas
      item.noTelepon = (pasien.value.nohp == null || pasien.value.nohp == '') ? '000000000000' : pasien.value.nohp
      SETTING.value = response.setting
      item.DEPARTEMEN = response.registrasi.objectdepartemenfk
      await apiNoKartu(item.noKartu)

      await getWaktuygdikiosk(item.noKartu)

      item.idKelompokPasienBPJS = response.idKelompokPasienBPJS

      item.jenispelayanan = response.jenispelayanan ? response.jenispelayanan[0].id : undefined
      for (let x = 0; x < response.asalrujukan.length; x++) {
        const element = response.asalrujukan[x];
        if (element.asalrujukan.toLowerCase() == 'datang sendiri') {
          item.asalrujukan = element.id
          break
        }
      }
      if (response.registrasi != null && ID_PASIEN == response.registrasi.nocmfk) {

        registrasi.value = response.registrasi
        if (response.registrasi.nosep && registrasi.value.israwatinap != true) {
          item.noSep = response.registrasi.nosep
          item.tglSep = response.registrasi.tglsep
          item.NOREC_PA = response.registrasi.pemakaianasuransi.norec
          item.ID_AP = response.registrasi.pemakaianasuransi.objectasuransipasienfk
        }

        if (registrasi.value.israwatinap == true) {
          item.jnsPelayanan = 1
        }
        if (response.registrasi.kdsubspesialisbpjs) {
          await fetchSupspesialis({ query: response.registrasi.kdsubspesialisbpjs })
          d_Subspesialis.value.forEach((element: any) => {
            if (element.kode == response.registrasi.kdsubspesialisbpjs) {
              item.spesialis = element
              return
            }
          });

          // item.spesialis = { kode: response.registrasi.kdsubspesialisbpjs, nama: response.registrasi.namasubspesialisbpjs }
          if (response.registrasi.kdsubspesialisbpjs == 'IGD') {
            item.pilih = d_AsalRujukan.value[1].value
            changeAsal(item.pilih)
          }
          await changeSpesialis(item.spesialis)
          if (!item.dpjpLayan)
            item.dpjpLayan = { kode: response.registrasi.dpjplayan_kode, nama: response.registrasi.dpjplayan_nama }
          // fetchSupspesialis(response.registrasi.namasubspesialisbpjs)
        }
        if (response.registrasi.pemakaianasuransi == null) {
          cariNoKa()
        }
      }
      isLoadingPasien.value = false
    })

}
const fetchSupspesialis = async (filter: any) => {
  if (!filter.query) return
  let json = {
    "url": "referensi/poli/" + encodeURI(filter.query),
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    console.log('POLI', res.response.poli)
    res.response.poli = res.response.poli.filter(item => item.nama !== 'SARANA RADIOTERAPI');
    d_Subspesialis.value = res.response.poli

  } else {
    H.alert('error', res.metaData.message)
  }
}
const fetchDPJP = async () => {
  if (item.spesialis == undefined) return
  let jenisPel = registrasi.value.israwatinap == true ? 1 : 2
  let json = {
    "url": `referensi/dokter/pelayanan/${jenisPel}/tglPelayanan/${H.formatDate(item.tglSep, 'YYYY-MM-DD')}/Spesialis/${item.spesialis.kode}`,
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    d_dpjpLayan.value = res.response.list
  } else {
    H.alert('error', res.metaData.message)
  }
}
const changeSpesialis = async (e: any) => {
  if (e == undefined) return
  isRujukInternal.value = false
  if (item.RujukankePoli != undefined && item.RujukankePoli != e.kode) {
    isRujukInternal.value = true
  }
  let jenisPel = registrasi.value.israwatinap == true ? 1 : 2
  if (registrasi.value.kdsubspesialisbpjs == 'IGD') {
    jenisPel = 1
  }
  if (registrasi.value.namaruangan == 'IGD') {
    jenisPel = 1
  }

  
  if (item.tglSep == undefined) {
    return
  }
  if (e.kode == "HDL") {
    jenisPel = 1
  }
  if (e.kode == "IGD") {
    jenisPel = 1
  }
  let json = {
    "url": `referensi/dokter/pelayanan/${jenisPel}/tglPelayanan/${H.formatDate(item.tglSep, 'YYYY-MM-DD')}/Spesialis/${e.kode}`,
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    d_dpjpLayan.value = res.response.list

    d_dpjpLayan.value.forEach((element: any) => {
      if (element.kode == registrasi.value.kddokterbpjs) {
        item.dpjpLayan = element
        return
      }
    });

  }
  // else {
  //     H.alert('error', res.metaData.message)
  // }

  if (PILIHPELAYANAN.value == 'pasca') {
    cariSepRanap()
  }
  ISFLAG.value = false
  if (e.kode == 'HDL') {
    ISFLAG.value = true
  }

}
const changeLaka = (e: any) => {

  if (e.kode != 0) {
    fetchProv()
  } else {
    d_Provinsi.value = []
  }
}
const fetchProv = async () => {
  d_Provinsi.value = await apiReferensi(`referensi/propinsi`, 'list')
}
const changeProv = async (e: any) => {
  if (e == undefined || e == '') return
  d_Kota.value = await apiReferensi(`referensi/kabupaten/propinsi/${e.kode}`, 'list')
}
const changeKota = async (e: any) => {
  if (e == undefined || e == '') return
  d_Kecamatan.value = await apiReferensi(`referensi/kecamatan/kabupaten/${e.kode}`, 'list')
}
const fetchDiagnosa = async (e: any) => {
  if (e.query == undefined || e.query == '') return
  d_Diagnosa.value = await apiReferensi(`referensi/diagnosa/${encodeURI(e.query)}`, 'diagnosa')

}

const apiReferensi = async (url: any, balikan: any) => {
  let json = {
    "url": url,
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    return res.response[balikan]
    // .map((item: any) => {
    //     return { value: item.kode, label: item.nama, default: item }
    // })
  } else {
    H.alert('error', res.metaData.message)
    return []
  }
}

const apiNoKartu = async (noKartu: any) => {
  isLoadingPasien.value = true
  // let token = await grecaptcha.execute( import.meta.env.VITE_CAPTCHA_SITEKEY, { action: 'submit' })
  let json = {
    "url": `Peserta/nokartu/${noKartu}/tglSEP/${H.formatDate(item.tglSep, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null,
    // "token":token
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    peserta.value = res.response.peserta
    d_KelasRawat.value.forEach((element: any) => {
      if (element.kode == peserta.value.hakKelas.kode) {
        item.klsRawatHak = element
        return
      }
    });

    if(item.noTelepon == null || item.noTelepon == undefined || item.noTelepon == '000000000000'){
      item.noTelepon = res.response.peserta.mr.noTelepon
      pasien.value.nohp = res.response.peserta.mr.noTelepon
    }
    if (item.noTelepon != null)
      item.noTelepon = item.noTelepon.length > 14 ? item.noTelepon.substr(0, 14) : item.noTelepon
  } else {
    H.alert('error', res.metaData.message)
  }
  isLoadingPasien.value = false

}
const apiRujukan = async (noKartu: any) => {
  var url = ''
  if (item.asal == 2) {
    url = `Rujukan/RS/Peserta/${noKartu}`
  } else {
    url = `Rujukan/Peserta/${noKartu}`
  }
  let json = {
    "url": url,
    "method": "GET",
    "data": null
  }
  rujukanLast.value = {}
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    rujukanLast.value = res.response.rujukan
    await setRujukan(rujukanLast.value)

  } else {
    H.alert('error', res.metaData.message)
  }
  isLoading.value = false
}

// const apiSuratKontrol =async  ()=> {
//     let json = {
//         "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${H.formatDate(item.tglSep, 'MM')}/Tahun/${new Date(item.tglSep).getFullYear()}/Nokartu/${item.noKartu}/filter/2`,//tgl kontrol
//         "method": "GET",
//         "data": null
//     }
//     const res = await useApi().postBPJS(
//         `/bridging/bpjs/tools`, json)
//     if (res.metaData.code == 200) {
//         for (let x = 0; x < res.response.list.length; x++) {
//             const element = res.response.list[x];
//             if (element.terbitSEP == 'Belum' &&
//                 element.tglRencanaKontrol == H.formatDate(item.tglSep, 'YYYY-MM-DD')) {
//                 item.noSurat = element.noSuratKontrol
//                 item.namaDPJP = element.namaDokter
//                 item.kodeDPJP = element.kodeDokter
//                 break
//             }
//         }

//     } else {
//         H.alert('error', res.metaData.message)
//     }
// }
const changeTujuanKunjungan = (event: any) => {
  isProsedur.value = false
  if (event == null) return;
  if (event.kode == "1") {
    isProsedur.value = true
    delete item.flagProcedure
    delete item.kdPenunjang
    delete item.assesment
  }
}
const changeFlagProcedur = (event: any) => {
  d_Penunjang.value = [];
  event.details.forEach((response: any) => {
    d_Penunjang.value.push(response);
  });
}
const apiSuratKontrolByNo = async (nomor: any) => {
  let json = {
    "url": `RencanaKontrol/noSuratKontrol/${nomor}`,
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    item.noSurat = res.response.noSuratKontrol
    item.namaDPJP = res.response.namaDokter
    item.kodeDPJP = res.response.kodeDokter

  } else {
    H.alert('error', res.metaData.message)
  }
}
const fetchRujukan = async () => {
  if (!item.noKartu) {
    return
  }
  let url = ''
  if (item.asal == 2) {
    url = `Rujukan/RS/List/Peserta/${item.noKartu}`
  } else {
    url = `Rujukan/List/Peserta/${item.noKartu}`
  }
  let json = {
    "url": url,
    "method": "GET",
    "data": null
  }
  listRujukan.value = []
  modalRujukan.value = true
  isLoadingRujukan.value = true
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  isLoadingRujukan.value = false
  if (res.metaData.code == 200) {
    res.response.rujukan.forEach((element: any) => {
      element.tglExpired = H.formatDate(new Date(new Date(element.tglKunjungan).setDate(new Date(element.tglKunjungan).getDate() + 90)), 'YYYY-MM-DD')
      element.color_status = new Date(element.tglExpired) <= new Date() ? 'danger' : 'success'
    });
    listRujukan.value = res.response.rujukan
  } else {
    H.alert('error', res.metaData.message)
  }
}
const setRujukan = async (e: any) => {

  item.ppkRujukan_kode = e.provPerujuk.kode
  item.ppkRujukan = e.provPerujuk.nama
  item.tglRujukan = e.tglKunjungan
  item.noKunjungan = e.noKunjungan
  item.diagAwal = { kode: e.diagnosa.kode, nama: e.diagnosa.kode + ' - ' + e.diagnosa.nama }
  item.RujukankePoli = e.poliRujukan.kode
  isRujukInternal.value = false
  if (item.spesialis) {
    if (item.RujukankePoli != item.spesialis.kode) {
      item.assesmentPel = d_Assesment.value[0]
      isRujukInternal.value = true
    }
  }
  var countKunjungan = 0;
  for (let i = 0; i < histori.value.length; i++) {
    const element = histori.value[i];
    if (element.noRujukan == item.noKunjungan && (item.spesialis && item.spesialis.kode != 'IGD')) {
      countKunjungan = countKunjungan + 1
    }
  }
  countKunjungan++
  // H.alert('info', "Kunjungan ke- " + countKunjungan + " Dengan Rujukan yang sama.")
  let json = {
    "url": `/Rujukan/JumlahSEP/${item.asal}/${e.noKunjungan}`,
    "method": "GET",
    "data": null
  }
  kunjunganPertama.value = false
  const res = await useApi().postBPJS(`/bridging/bpjs/tools`, json)

  if (res.metaData.code == 200) {
    H.alert('info', "Kunjungan ke- " + res.response.jumlahSEP + " Dengan Rujukan yang sama.")
    if (res.response.jumlahSEP == 0) {
      kunjunganPertama.value = true
    }
    countKunjungan = res.response.jumlahSEP
  } else {
    console.log(res)
  }
  item.tujuanKunj = d_TujuanKunjungan.value[0] //normal
  if(countKunjungan > 0){
    cariSuratKontrol(e.poliRujukan.kode,countKunjungan)
  }

  if (e.poliRujukan.kode == "HDL" || e.poliRujukan.kode == '008' ) {
    item.tujuanKunj = d_TujuanKunjungan.value[2]
    item.assesmentPel = d_Assesment.value[4]
    setTglRujukanKhusus(e.noKunjungan, e.diagnosa.kode, e);
  } else {
    modalRujukan.value = false
  }


}
const setTglRujukanKhusus = async (norujukan, diagnosa, e) => {
  // perpanjang rujukan khusus
  let json = {
    "url": `Rujukan/Khusus/insert`,
    "method": "POST",
    "data": {
      "noRujukan": norujukan,
      "diagnosa": [
        { "kode": "P;" + diagnosa },
      ],
      "procedure": [],
      "user": H.namaPegawai()
    }
  }
  e.loadingRujukan = true
  const res = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
  e.loadingRujukan = false
  if (res.metaData.code == 200) {
    modalRujukan.value = false
    item.tglRujukan = res.response.rujukan.tglrujukan_awal;
  } else {
    if(res.metaData.message == 'Nomor rujukan Poli Terakhir Bukan Hemodialisa'){
        // cek rujukan Thalasesia, CAD,
        cariRujukanKhusus(norujukan, e)


    }else{
        modalRujukan.value = false
        let tglberlakumsg = res.metaData.message.substr(33, 10);
        item.tglRujukan = convertDateFormat(tglberlakumsg);
    }
  }


}
const cariRujukanKhusus = async(norujukan:any , e:any) => {
    let dari = new Date(new Date().setDate(new Date().getDate() - 90))
    let bulan = H.monthList()[dari.getMonth()].kode
    let json = {
        "url": `/Rujukan/Khusus/List/Bulan/${bulan}/Tahun/${dari.getFullYear()}`,
        "method": "GET",
        "data": null
    }
    e.loadingRujukan = true
    const resKhusus = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
    e.loadingRujukan = false
    let ketemu = false
    if (resKhusus.metaData.code == 200) {
        resKhusus.response.rujukan.forEach((element) => {
            if(element.norujukan == norujukan){
                item.tglRujukan = element.tglrujukan_awal;

                H.alert('info', `Tgl Rujukan Berlaku ${element.tglrujukan_awal} s/d ${element.tglrujukan_berakhir}  `)
                modalRujukan.value = false
                ketemu = true
                return
            }
        });
    }
    if(!ketemu){
        let dari = new Date(new Date().setDate(new Date().getDate() - 60))
        let bulan = H.monthList()[dari.getMonth()].kode
        let json = {
            "url": `/Rujukan/Khusus/List/Bulan/${bulan}/Tahun/${dari.getFullYear()}`,
            "method": "GET",
            "data": null
        }
        e.loadingRujukan = true
        const resKhusus = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
        e.loadingRujukan = false
        ketemu = false
        if (resKhusus.metaData.code == 200) {
            resKhusus.response.rujukan.forEach((element) => {
                if(element.norujukan == norujukan){
                    item.tglRujukan = element.tglrujukan_awal;

                    H.alert('info', `Tgl Rujukan Berlaku ${element.tglrujukan_awal} s/d ${element.tglrujukan_berakhir}  `)
                    modalRujukan.value = false
                    ketemu = true
                    return
                }
            });
        }
    }
    if(!ketemu){
        let dari = new Date(new Date().setDate(new Date().getDate() - 30))
        let bulan = H.monthList()[dari.getMonth()].kode
        let json = {
            "url": `/Rujukan/Khusus/List/Bulan/${bulan}/Tahun/${dari.getFullYear()}`,
            "method": "GET",
            "data": null
        }
        e.loadingRujukan = true
        const resKhusus = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
        e.loadingRujukan = false
        ketemu = false
        if (resKhusus.metaData.code == 200) {
            resKhusus.response.rujukan.forEach((element) => {
                if(element.norujukan == norujukan){
                    item.tglRujukan = element.tglrujukan_awal;

                    H.alert('info', `Tgl Rujukan Berlaku ${element.tglrujukan_awal} s/d ${element.tglrujukan_berakhir}  `)
                    modalRujukan.value = false
                    ketemu = true
                    return
                }
            });
        }
    }
    if(!ketemu){
       H.alert('error', 'Nomor rujukan Poli Terakhir Bukan Hemodialisa')
    }
}
const convertDateFormat = (inputDate) => {
  // Split the input date into day, month, and year
  const [day, month, year] = inputDate.split('-');

  // Create a new Date object with the parsed values
  const parsedDate = new Date(`${year}-${month}-${day}`);

  // Format the date in 'YYYY-MM-DD' format
  const formattedDate = parsedDate.toISOString().split('T')[0];

  return formattedDate;
}

const setSurat = async (e: any) => {
  item.noSurat = e.noSuratKontrol
  item.namaDPJP = e.namaDokter,//{ kode: e.kodeDokter, nama: e.namaDokter }

    modalSKDP.value = false
}
const cariSuratKontrol = async (poli: any, jumlahRujukanSEP:any) => {
  delete item.noSurat
  delete item.namaDPJP
  delete item.kodeDPJP

  var pelayananByRujukan = []
  var countKunjungan = 0;
  for (let i = 0; i < histori.value.length; i++) {
    const element = histori.value[i];
    if (element.noRujukan == item.noKunjungan && (item.spesialis && item.spesialis.kode != 'IGD')) {
      countKunjungan = countKunjungan + 1
      pelayananByRujukan.push(element);
    }
  }
  countKunjungan++
  // H.alert('info', "Kunjungan ke- " + countKunjungan + " Dengan Rujukan yang sama.")

  // buat kan surat kontrol apabila kunjungan lebih dari 1
  if (jumlahRujukanSEP > 1 && poli == item.spesialis.kode) {
    delete item.tujuanKunj
    var noSepTerakhir = pelayananByRujukan.length ?  pelayananByRujukan[0].noSep : ''
    setSuratKontrol(noSepTerakhir)
  }
}
const apiSuratKontrol = async () => {

  let bulan = input.value.bulan.kode + 1

  bulan = bulan.toString().length == 1 ? '0' + bulan.toString() : bulan
  let year = input.value.tahun.kode
  // var bulan = moment(new Date()).format('MM');
  // var year = moment(new Date()).format('YYYY');

  var json = {
    "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${year}/Nokartu/${item.noKartu}/filter/${input.value.filter.kode}`,
    "method": "GET",
    "data": null
  }

  const resSurkon = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  return resSurkon
}
const setSuratKontrol = async (noSepTerakhir: any) => {
  if(noSepTerakhir == '')return
  var ketemu = false;
  // isLoadingRujukan.value = true
  listSurkon.value = []
  const resSurkon: any = await apiSuratKontrol()
  // isLoadingRujukan.value = false

  if (resSurkon.metaData.code == 200) {
    for (let i = 0; i < resSurkon.response.list.length; i++) {
      const element = resSurkon.response.list[i];
      if (element.poliTujuan == item.spesialis.kode &&
        element.noSepAsalKontrol == noSepTerakhir &&
        element.jnsKontrol == "2") {
        // cek tgl kontrolnya sesuai gak
        if (element.tglRencanaKontrol == moment(new Date()).format('YYYY-MM-DD')) {
          if (element.kodeDokter == item.dpjpLayan.kode) {
            ketemu = true;
            var optionListSurkon = {
              label: element.noSuratKontrol,
              value: element
            }
            listSurkon.value.push(optionListSurkon);
            item.noSurat = element.noSuratKontrol
            item.namaDPJP = element.namaDokter
            item.kodeDPJP = element.kodeDokter
            item.tujuanKunj = d_TujuanKunjungan.value[2] //Konsul Dokter
            item.assesmentPel = d_Assesment.value[4] //Tujuan Kontrol

            break;
          } else {
            // var JsonDelate = {
            //   "url": `RencanaKontrol/Delete`,
            //   "method": "DELETE",
            //   "data": {
            //     "request": {
            //       "t_suratkontrol": {
            //         "noSuratKontrol": element.noSuratKontrol,
            //         "user": H.namaPegawai()
            //       }
            //     }
            //   }
            // }
            // isLoading.value = true;
            // const resSurkonDel = await useApi().postBPJS(
            //   `/bridging/bpjs/tools`, JsonDelate)
            // isLoading.value = false;
          }
        } else {
          updateSuratKontrol(element.noSuratKontrol, noSepTerakhir,
            item.dpjpLayan.kode, item.spesialis.kode);
        }
      }
    }
  } else {
    // H.alert('error', res.metaData.message)
  }


  isLoading.value = false;
  if (!ketemu) {
    var noSep = noSepTerakhir
    var kdDokter = item.dpjpLayan.kode
    var kdPoli = item.spesialis.kode
    if (kdPoli == null) return
    if (kdDokter == null) return
    createSuratKontrol(noSep, kdDokter, kdPoli)
  }

}

const createSuratKontrol = async (noSep: any, kdDokter: any, kdPoli: any) => {
  var JsonSave = {
    "url": `RencanaKontrol/insert`,
    "method": "POST",
    "data": {
      "request": {
        "noSEP": noSep,
        "kodeDokter": kdDokter,
        "poliKontrol": kdPoli,
        "tglRencanaKontrol": moment(new Date()).format("YYYY-MM-DD"),
        "user": H.namaPegawai()
      }
    }
  }
  isLoading.value = true;
  try {
    const resSurkon = await useApi().postBPJS(
      `/bridging/bpjs/tools`, JsonSave)
    isLoading.value = false;
    item.noSurat = ''
    H.alert('info', resSurkon.metaData.message)
    if (resSurkon.metaData.code == 200) {
      setSuratKontrol(noSep);
    } else {
      H.alert('error', resSurkon.metaData.message)
    }
  } catch (error) {
    isLoading.value = false;
  }


}
const updateSuratKontrol = async (noSuratKontrol: any, noSep: any, kdDokter: any, kdPoli: any) => {
  var JsonSave = {
    "url": `RencanaKontrol/Update`,
    "method": "PUT",
    "data": {
      "request": {
        "noSuratKontrol": noSuratKontrol,
        "noSEP": noSep,
        "kodeDokter": kdDokter,
        "poliKontrol": kdPoli,
        "tglRencanaKontrol": moment(new Date()).format("YYYY-MM-DD"),
        "user": H.namaPegawai()
      }
    }
  }
  isLoading.value = true;
  const resSurkon = await useApi().postBPJS(
    `/bridging/bpjs/tools`, JsonSave)
  isLoading.value = false;
  item.noSurat = ''
  H.alert('info', resSurkon.metaData.message)
  if (resSurkon.metaData.code == 200) {
    setSuratKontrol(noSep);
  }
}
const cariSKDP = async () => {
  listSurkon.value = []
  isLoadingSKDP.value = true
  modalSKDP.value = true
  const res: any = await apiSuratKontrol()
  const res2: any = await apiRujukanAktif()
  rujukanAktif.value = res2
  isLoadingSKDP.value = false

  if (res.metaData.code == 200) {
    listSurkon.value = res.response.list
  } else {
    H.alert('error', res.metaData.message)
  }
}
const apiHistory = async () => {
  isLoadingHistory.value = true;
  let now = new Date()
  let dari = new Date(now.setDate(now.getDate() - 90))
  let nokartu = null
  if(pasien.value.nobpjs != null){
    nokartu = pasien.value.nobpjs
  } else{
    nokartu = item.noKartu
  }
  let json = {
    "url": `monitoring/HistoriPelayanan/NoKartu/${pasien.value.nobpjs}/tglMulai/${H.formatDate(dari, 'YYYY-MM-DD')}/tglAkhir/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  histori.value = []
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    histori.value = res.response.histori
    for (let x = 0; x < histori.value.length; x++) {
      const element = histori.value[x];
      if (element.tglSep == H.formatDate(item.tglSep, 'YYYY-MM-DD')
        && element.jnsPelayanan == (registrasi.value.israwatinap == true ? '1' : '2')
        && element.poli == item.spesialis?.nama) {
        item.noSep = element.noSep
        break

      }
    }
    isLoadingHistory.value = false;
  } else {
    H.alert('error', res.metaData.message)
    isLoadingHistory.value = false;
  }
}

const changePelayanan = (e: any) => {
  console.log("GET DATA PILIH", e)
  if (e == 1) { //ranap
    PILIHPELAYANAN.value = 'ri'
    delete item.spesialis
  }
  if (e == 2) { //rajal
    PILIHPELAYANAN.value = 'igd'
  }
}
const changeAsal = (e: any) => {
  PILIHPELAYANAN.value = ''
  item.asal = 1
  if (e == 2) { //IGD
    item.spesialis = { kode: 'IGD', nama: 'Instalasi Gawat Darurat' }
    PILIHPELAYANAN.value = 'igd'
    if(registrasi.value.israwatinap == true) {
      PILIHPELAYANAN.value = 'ri'
    }
    item.ppkRujukan = SETTING.value.BPJS_namaPPKRujukan
    item.ppkRujukan_kode = SETTING.value.BPJS_kodePPKRujukan
  }
  if (e == 1) {
    PILIHPELAYANAN.value = 'rj'
    if (registrasi.value.kdsubspesialisbpjs) {
      item.spesialis = { kode: registrasi.value.kdsubspesialisbpjs, nama: registrasi.value.namasubspesialisbpjs }
    }

    if (rujukanLast.value.noKunjungan) {
      item.ppkRujukan_kode = rujukanLast.value.provPerujuk.kode
      item.ppkRujukan = rujukanLast.value.provPerujuk.nama
      item.tglRujukan = rujukanLast.value.tglKunjungan
      item.noKunjungan = rujukanLast.value.noKunjungan
      item.diagAwal = { kode: rujukanLast.value.diagnosa.kode, nama: rujukanLast.valuee.diagnosa.kode + ' - ' + rujukanLast.value.diagnosa.nama }
    }
  }
  if (e == 3) {
    delete item.spesialis
    delete item.noKunjungan
    PILIHPELAYANAN.value = 'pasca'
    item.asal = 2
    item.ppkRujukan = SETTING.value.BPJS_namaPPKRujukan
    item.ppkRujukan_kode = SETTING.value.BPJS_kodePPKRujukan
    // cariSepRanap()
  }
  changeSpesialis(item.spesialis)
}
const cariSepRanap = async () => {
  await apiHistory()
  let noSepTerakhir = ''
  if (histori.value.length > 0) { //cari sep terakhir
    if (histori.value[0].jnsPelayanan == '1') {
      item.noKunjungan = histori.value[0].noSep
      item.ppkRujukan = histori.value[0].ppkPelayanan
      item.ppkRujukan_kode = SETTING.value.BPJS_kodePPKRujukan
      item.tglRujukan = H.formatDate(new Date(), 'YYYY-MM-DD')

      noSepTerakhir = histori.value[0].noSep
    } else {
      for (let x = 0; x < histori.value.length; x++) {
        const element = histori.value[x];
        if (element.jnsPelayanan == '1') {
          item.noKunjungan = element.noSep
          item.ppkRujukan = element.ppkPelayanan
          item.ppkRujukan_kode = SETTING.value.BPJS_kodePPKRujukan
          item.tglRujukan = H.formatDate(new Date(), 'YYYY-MM-DD')

          noSepTerakhir = element.noSep
        }
      }
    }
    setSuratKontrol(noSepTerakhir)
  }
}
const cariNoKa = async () => {
  if (item.noKartu == undefined) {
    H.alert('warning', 'No Kartu harus di isi')
    return
  }
  isLoadingKartu.value = true
  if (PILIHPELAYANAN.value == 'rj') {
    // await apiNoKartu(item.noKartu)
    await apiHistory()
    await apiRujukan(item.noKartu)
  } else if (PILIHPELAYANAN.value == 'ri') {
    // await apiNoKartu(item.noKartu)

  } else if (PILIHPELAYANAN.value == 'igd') {
    // await apiNoKartu(item.noKartu)
  } else if (PILIHPELAYANAN.value == 'pasca') {
    // await apiNoKartu(item.noKartu)
    await apiHistory()
  }

  isLoadingKartu.value = false


}
const saveKartu = async () => {
  let json = {
    'nokartu': item.noKartu,
    'id': pasien.value.nocmfk
  }
  isLoadingKartu.value = true
  await useApi().post(
    `/registrasi/save-pasien-kartu`, json).then(async (rePasien: any) => {
        isLoading.value = false
        cariNoKa()
    }).catch((e: any) => {
        isLoading.value = false
        console.clear()
        console.log(e)
    })
}
const save = async () => {
  if (isSEP_Auto.value) {

    isLoading.value = true;
    let json = {}
    if(item.jnsPelayanan == 2 ){
        if (!item.spesialis) {
            H.alert('error', `Sub/Spesialis harus di isi`)
            return
        }
    }
    if (item.noSep != undefined && item.noSep.length == 19 && registrasi.value.israwatinap != true) {
      json = {
        "url": `SEP/2.0/update`,
        "method": "PUT",
        "data": {
          "request": {
            "t_sep": {
              "noSep": item.noSep,
              "klsRawat": {
                "klsRawatHak": item.klsRawatHak.kode,
                "klsRawatNaik": item.klsRawatNaik ? item.klsRawatNaik.kode : '',
                "pembiayaan": item.pembiayaan ? item.pembiayaan.kode : '',
                "penanggungJawab": item.pembiayaan ? (item.pembiayaan.kode == 1 ? 'Pribadi' : (item.penanggungJawab ? item.penanggungJawab : '')) : '',
              },
              "noMR": pasien.value.nocm,
              "catatan": item.catatan ? item.catatan : '',
              "diagAwal": item.diagAwal ? item.diagAwal.kode : '',
              "poli": {
                "tujuan": item.spesialis ? item.spesialis.kode : '',
                "eksekutif": item.eksekutif ? "1" : "0"
              },
              "cob": {
                "cob": item.cob ? "1" : "0"
              },
              "katarak": {
                "katarak": item.katarak ? "1" : "0"
              },
              "jaminan": {
                "lakaLantas": item.lakaLantas != undefined ? item.lakaLantas.kode : '0',
                "penjamin": {
                  "tglKejadian": (item.lakaLantas != undefined && item.lakaLantas.kode != 0) ? H.formatDate(item.tglKejadian, 'YYYY-MM-DD') : '',
                  "keterangan": item.keterangan ? item.keterangan : '',
                  "suplesi": {
                    "suplesi": "0",
                    "noSepSuplesi": "",
                    "lokasiLaka": {
                      "kdPropinsi": (item.lakaLantas != undefined && item.lakaLantas.kode != 0) ? (item.kdPropinsi ? item.kdPropinsi.kode : '') : '',
                      "kdKabupaten": (item.lakaLantas != undefined && item.lakaLantas.kode != 0) ? (item.kdKabupaten ? item.kdKabupaten.kode : '') : '',
                      "kdKecamatan": (item.lakaLantas != undefined && item.lakaLantas.kode != 0) ? (item.kdKecamatan ? item.kdKecamatan.kode : '') : '',
                    }
                  }
                }
              },
              "dpjpLayan": item.dpjpLayan ? item.dpjpLayan.kode : '',
              "noTelp": item.noTelepon ? item.noTelepon : '',
              "user": H.namaPegawai()
            }
          }
        }

      }
    } else {
      json = {
        "url": `SEP/2.0/insert`,
        "method": "POST",
        "data": {
          "request": {
            "t_sep": {
              "noKartu": item.noKartu,
              "tglSep": H.formatDate(item.tglSep, 'YYYY-MM-DD'),
              "ppkPelayanan": SETTING.value.BPJS_kodePPKRujukan,
              "jnsPelayanan": item.jnsPelayanan,
              "klsRawat": {
                "klsRawatHak": item.klsRawatHak.kode,
                "klsRawatNaik": item.klsRawatNaik ? item.klsRawatNaik.kode : '',
                "pembiayaan": item.pembiayaan ? item.pembiayaan.kode : '',
                "penanggungJawab": item.pembiayaan ? (item.pembiayaan.kode == 1 ? 'Pribadi' : (item.penanggungJawab ? item.penanggungJawab : '')) : '',
              },
              "noMR": pasien.value.nocm,
              "rujukan": {
                "asalRujukan": registrasi.value.israwatinap == false ? (item.pilih == 1 ? item.asal : 2) : 2,
                "tglRujukan": item.tglRujukan ? item.tglRujukan : H.formatDate(new Date(), 'YYYY-MM-DD'),
                "noRujukan": registrasi.value.israwatinap == false ? (item.noKunjungan ? item.noKunjungan : "") : (item.noSurat ? item.noSurat : ''),
                "ppkRujukan": item.jnsPelayanan  == 1 ? SETTING.value.BPJS_kodePPKRujukan : (item.spesialis.kode == 'IGD' ?  SETTING.value.BPJS_kodePPKRujukan : (item.ppkRujukan_kode ? item.ppkRujukan_kode : ""))
              },
              "catatan": item.catatan ? item.catatan : '',
              "diagAwal": item.diagAwal ? item.diagAwal.kode : '',
              "poli": {
                "tujuan": item.spesialis ? item.spesialis.kode : '',
                "eksekutif": item.eksekutif ? "1" : "0"
              },
              "cob": {
                "cob": item.cob ? "1" : "0"
              },
              "katarak": {
                "katarak": item.katarak ? "1" : "0"
              },
              "jaminan": {
                "lakaLantas": item.lakaLantas != undefined ? item.lakaLantas.kode : '0',
                "noLP": item.noLP ? item.noLP : '',
                "penjamin": {
                  "tglKejadian": (item.lakaLantas != undefined && item.lakaLantas.kode != 0) ? H.formatDate(item.tglKejadian, 'YYYY-MM-DD') : '',
                  "keterangan": item.keterangan ? item.keterangan : '',
                  "suplesi": {
                    "suplesi": "0",
                    "noSepSuplesi": "",
                    "lokasiLaka": {
                      "kdPropinsi": (item.lakaLantas != undefined && item.lakaLantas.kode != 0) ? (item.kdPropinsi ? item.kdPropinsi.kode : '') : '',
                      "kdKabupaten": (item.lakaLantas != undefined && item.lakaLantas.kode != 0) ? (item.kdKabupaten ? item.kdKabupaten.kode : '') : '',
                      "kdKecamatan": (item.lakaLantas != undefined && item.lakaLantas.kode != 0) ? (item.kdKecamatan ? item.kdKecamatan.kode : '') : '',
                    }
                  }
                }
              },
              "tujuanKunj": item.tujuanKunj != undefined ? item.tujuanKunj.kode : item.spesialis != undefined ? (item.spesialis.kode == 'IGD' ? 0 : 0) : 0,
              "flagProcedure": item.flagProcedure ? item.flagProcedure.kode : '',
              "kdPenunjang": item.kdPenunjang ? item.kdPenunjang.kode : '',
              "assesmentPel": item.assesmentPel ? item.assesmentPel.kode : '',
              "skdp": {
                "noSurat": item.noSurat ? item.noSurat : '',
                "kodeDPJP": item.kodeDPJP ? item.kodeDPJP : '',
              },
              "dpjpLayan": item.jnsPelayanan == 2 ? (item.dpjpLayan != undefined ? item.dpjpLayan.kode : '') : '',
              "noTelp": item.noTelepon ? item.noTelepon : '',
              "user": H.namaPegawai()
            }
          }
        }
      }

      //send antrian online
      if(registrasi.value.objectdepartemenfk == 18 && item.spesialis.kode !== 'UGD' && item.spesialis.kode !== 'IGD'
        && item.spesialis.kode !== 'HDL') {
          //  isLoading.value = true

          // Enable Antrol For a while
          if(item.noSep == undefined) {
            const resAntrol = await sendAntrol();
            isLoading.value = false
            console.log('ANTROL 1', resAntrol)
            if(resAntrol) {
              // send taks id 1
              const jsont1 = {
                "noregistrasifk": item.NOREC_PD,
                "taskid": 1,
                "waktu": new Date().getTime(),
              }
              useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont1).then(async (rt1: any) => {
                if (rt1.metaData.code == 200) {
                  // send taks id 2
                  const jsont2 = {
                    "noregistrasifk": item.NOREC_PD,
                    "taskid": 2,
                    "waktu": moment(new Date()).add(5, 'm').valueOf(),
                  }
                  useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont2).then(async (rt2: any) => {
                    if (rt2.metaData.code == 200) {
                      // send taks id 3
                      const jsont3 = {
                        "noregistrasifk": item.NOREC_PD,
                        "taskid": 3,
                        "waktu": moment(new Date()).add(10, 'm').valueOf(),
                      }
                      useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont3).then(async (rt3: any) => {
                        if (rt3.metaData.code == 200) {
                          const jsont4 = {
                            "noregistrasifk": item.NOREC_PD,
                            "taskid": 4,
                            "waktu": moment(new Date()).add(20, 'm').valueOf(),
                          }
                          useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont4).then(async (rt4: any) => {
                            if (rt4.metaData.code == 200) {
                              const jsont5 = {
                                "noregistrasifk": item.NOREC_PD,
                                "taskid": 5,
                                "waktu": moment(new Date()).add(30, 'm').valueOf(),
                              }
                              useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont4).then(async (rt5: any) => {
                                
                              })
                            }
                          })
                        }
                      })
                    }
                  })
                }
              })
            }
          }
          isLoading.value = false
       }
    }

    isLoading.value = true
    const res = await useApi().postBPJS(
      `/bridging/bpjs/tools`, json)
    isLoading.value = false
    if (res.metaData.code == 200) {
      if (item.noSep != undefined && item.noSep.length == 19) {
        item.noSep = res.response.sep.noSep
        LOG.value = `Ubah No. SEP ${item.noSep} dibuat BRIDGING dengan tgl ${H.formatDate(item.tglSep, 'YYYY-MM-DD')} `
      } else {
        item.noSep = res.response.sep.noSep
        LOG.value = `Tambah No. SEP ${item.noSep} dibuat BRIDGING dengan tgl ${H.formatDate(item.tglSep, 'YYYY-MM-DD')} `
      }
      H.alert('success', `Sukses, NO SEP : ${item.noSep}`)
      await savePemakaianAsuransi()
      await saveKlaimSEP()
      // H.printBlade('registrasi/pemakaian-asuransi/sep?nosep=' + item.noSep + '&pdf=false');
      qzService.printData(`registrasi/pemakaian-asuransi/sep?pdf=true&nosep=${item.nosep}`, 'SEP', 1)
      router.push({
        name: 'module-dashboard-registrasi'
      })
    } else {
      // if (res.metaData.message == 'Peserta Belum Melakukan Verifikasi Sidik Jari') {
      //     await pengajuanSidikJari(item.noKartu)
      //     return
      // } else {
      H.alert('error', res.metaData.message)
      // }

    }
  } else {
    let noSEP = item.noSep ? item.noSep : ' '
    let jenis = item.NOREC_PA ? 'Ubah ' : 'Tambah '
    LOG.value = `${jenis} No. SEP ${noSEP} dibuat MANUAL dengan tgl ${H.formatDate(item.tglSep, 'YYYY-MM-DD')} `

    //send antrian online
    if(registrasi.value.objectdepartemenfk == 18 && item.spesialis != undefined ) {
          //  isLoading.value = true
          // if(item.noSep == undefined) {
            const resAntrol = await sendAntrol();
            isLoading.value = false
            console.log('ANTROL 2', resAntrol)
            if(resAntrol) {
                    // send taks id 1
              const jsont1 = {
                "noregistrasifk": item.NOREC_PD,
                "taskid": 1,
                "waktu": new Date().getTime(),
              }
              useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont1).then(async (rt1: any) => {
                if (rt1.metaData.code == 200) {
                  // send taks id 2
                  const jsont2 = {
                    "noregistrasifk": item.NOREC_PD,
                    "taskid": 2,
                    "waktu": moment(new Date()).add(5, 'm').valueOf(),
                  }
                  useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont2).then(async (rt2: any) => {
                    if (rt2.metaData.code == 200) {
                      // send taks id 3
                      const jsont3 = {
                        "noregistrasifk": item.NOREC_PD,
                        "taskid": 3,
                        "waktu": moment(new Date()).add(10, 'm').valueOf(),
                      }
                      useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont3).then(async (rt3: any) => {
                        if (rt3.metaData.code == 200) {
                          const jsont4 = {
                            "noregistrasifk": item.NOREC_PD,
                            "taskid": 4,
                            "waktu": moment(new Date()).add(20, 'm').valueOf(),
                          }
                          useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont4).then(async (rt4: any) => {
                            if (rt4.metaData.code == 200) {
                              const jsont5 = {
                                "noregistrasifk": item.NOREC_PD,
                                "taskid": 5,
                                "waktu": moment(new Date()).add(30, 'm').valueOf(),
                              }
                              useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont5).then(async (rt5: any) => {
                                
                              })
                            }
                          })
                        }
                      })
                    }
                  })
                }
              })
            }
          // }
          isLoading.value = false
       }

    await savePemakaianAsuransi()
    await saveKlaimSEP()
  }

}
const saveKlaimSEP = async () => {
  isLoading.value = true
  if(item.DEPARTEMEN == 18){
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
        'norec_pd': item.NOREC_PD,
        'documentklaimfk': 16,
        'namafile': "sep_rajal",
        'tglregistrasi': registrasi.value.tglregistrasi,
        'api': "Registrasi-PemakaianAsuransiCtrl@cetakSEP"
    }).then((r) => {
        isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
  } else if(item.DEPARTEMEN == 9){
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
        'norec_pd': item.NOREC_PD,
        'documentklaimfk': 28,
        'namafile': "sep_igd",
        'tglregistrasi': registrasi.value.tglregistrasi,
        'api': "Registrasi-PemakaianAsuransiCtrl@cetakSEP"
    }).then((r) => {
        isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
  }
    
}
const savePemakaianAsuransi = async () => {
  let jsonInternal = {
    'asuransipasien': {
      'id': item.ID_AP ? item.ID_AP : '',
      'nocmfk': pasien.value.nocmfk,
      'nocm': pasien.value.nocm,
      'noregistrasi': registrasi.value.noregistrasi,
      'kdpenjaminpasien': registrasi.value.objectrekananfk,
      'objectkelasdijaminfk': item.klsRawatHak ? item.klsRawatHak.kode : null,
      'namapeserta': peserta.value.nama ? peserta.value.nama : null,
      'noasuransi': peserta.value.noKartu ? peserta.value.noKartu : null,
      'noidentitas': peserta.value.nik ? peserta.value.nik : null,
      'kelompokpasien': registrasi.value.objectkelompokpasienlastfk,
      'tgllahir': peserta.value.tglLahir ? peserta.value.tglLahir : null,
      'jenispeserta': peserta.value.jenisPeserta.keterangan ? peserta.value.jenisPeserta.keterangan : null,
      'notelpmobile': item.noTelepon ? item.noTelepon : null,
    },
    'pemakaianasuransi': {
      'norec': item.NOREC_PA ? item.NOREC_PA : '',
      'noregistrasifk': item.NOREC_PD,
      'norujukan': item.noKunjungan ? item.noKunjungan : null,
      'nosep': item.noSep ? item.noSep : null,
      'nokartu': item.noKartu,
      'tglsep': item.tglSep ? H.formatDate(item.tglSep, 'YYYY-MM-DD') : null,
      'ppkpelayanan': SETTING.value.BPJS_kodePPKRujukan,
      'jnspelayanan': registrasi.value.israwatinap == true ? '1' : '2',
      'klsrawathak_kode': item.klsRawatHak ? item.klsRawatHak.kode : null,
      'klsrawathak_nama': item.klsRawatHak ? item.klsRawatHak.nama : null,
      'klsrawatnaik_kode': item.klsRawatNaik ? item.klsRawatNaik.kode : null,
      'klsrawatnaik_nama': item.klsRawatNaik ? item.klsRawatNaik.nama : null,
      'pembiayaan_kode': item.pembiayaan ? item.pembiayaan.kode : null,
      'pembiayaan_nama': item.pembiayaan ? item.pembiayaan.nama : null,
      'nomr': pasien.value.nocm ? pasien.value.nocm : null,
      'asalrujukan': item.asal ? item.asal : null,
      'tglrujukan': item.tglRujukan ? item.tglRujukan : null,
      'ppkrujukan': item.ppkRujukan_kode ? item.ppkRujukan_kode : null,
      'ppkrujukan_nama': item.ppkRujukan ? item.ppkRujukan : null,
      'kdprovider': peserta.value.provUmum.kdProvider ? peserta.value.provUmum.kdProvider : null,
      'nmprovider': peserta.value.provUmum.nmProvider ? peserta.value.provUmum.nmProvider : null,
      'catatan': item.catatan ? item.catatan : null,
      'diagawal_kode': item.diagAwal ? (item.diagAwal.kode != undefined?item.diagAwal.kode:null) : null,
      'diagawal_nama': item.diagAwal ?  (item.diagAwal.nama != undefined?item.diagAwal.nama:null) : null,
      'poli_kode': item.spesialis ? item.spesialis.kode : null,
      'poli_nama': item.spesialis ? item.spesialis.nama : null,
      'eksekutif': item.eksekutif ? item.eksekutif : '0',
      'cob': item.cob ? item.cob : '0',
      'katarak': item.katarak ? item.katarak : '0',
      'lakalantas_kode': item.lakaLantas ? item.lakaLantas.kode : null,
      'lakalantas_nama': item.lakaLantas ? item.lakaLantas.nama : null,
      'nolp': item.noLP ? item.noLP : null,
      'tglkejadian': item.lakaLantas != undefined && item.lakaLantas.kode != 0 ? H.formatDate(item.tglKejadian, 'YYYY-MM-DD') : null,
      'keterangan': item.keterangan ? item.keterangan : null,
      'suplesi': 0,
      'nosepsuplesi': null,
      'kdpropinsi_kode': item.kdPropinsi ? item.kdPropinsi.kode : null,
      'kdpropinsi_nama': item.kdPropinsi ? item.kdPropinsi.nama : null,
      'kdkabupaten_kode': item.kdKabupaten ? item.kdKabupaten.kode : null,
      'kdkabupaten_nama': item.kdKabupaten ? item.kdKabupaten.nama : null,
      'kdkecamatan_kode': item.noKunjungan ? item.noKunjungan : null,
      'kdkecamatan_nama': item.noKunjungan ? item.noKunjungan : null,
      'tujuankun_kode': item.tujuanKunj ? item.tujuanKunj.kode : null,
      'tujuankun_nama': item.tujuanKunj ? item.tujuanKunj.nama : null,
      'flagprocedure_kode': item.flagProcedure ? item.flagProcedure.kode : null,
      'flagprocedure_nama': item.flagProcedure ? item.flagProcedure.nama : null,
      'kdpenunjang_kode': item.kdPenunjang ? item.kdPenunjang.kode : null,
      'kdpenunjang_nama': item.kdPenunjang ? item.kdPenunjang.nama : null,
      'assesmentpel_kode': item.assesmentPel ? item.assesmentPel.kode : null,
      'assesmentpel_nama': item.assesmentPel ? item.assesmentPel.nama : null,
      // 'objectdiagnosafk': item.noKunjungan ? item.noKunjungan : null,
      'nosurat': item.noSurat ? item.noSurat : null,
      'kodedpjp': item.kodeDPJP ? item.kodeDPJP : null,
      'namadpjp': item.namaDPJP ? item.namaDPJP : null,
      'dpjplayan_kode': item.dpjpLayan ? item.dpjpLayan.kode : null,
      'dpjplayan_nama': item.dpjpLayan ? item.dpjpLayan.nama : null,
      'notelp': item.noTelepon ? item.noTelepon : null,
      'user': H.namaPegawai(),
      'backdate': cekBackdate(H.formatDate(item.tglSep, 'YYYY-MM-DD'), H.formatDate(new Date(), 'YYYY-MM-DD')),
      'LOG': LOG.value,
      'isrujukinternal': isRujukInternal.value ? isRujukInternal.value : null,

    }
  }
  isLoading.value = true

  await useApi().post(
    `/registrasi/pemakaian-asuransi/save`, jsonInternal).then((response: any) => {
      isLoading.value = false
      // item.NOREC_PA = response.pa.pd.norec
      qzService.printData(`registrasi/pemakaian-asuransi/sep?pdf=true&nosep=${response.registrasi.pa.nosep}`, 'SEP', 1)
      // H.printBlade('registrasi/pemakaian-asuransi/sep?nosep=' + response.registrasi.pa.nosep + '&pdf=false');
      router.push({
        name: 'module-dashboard-registrasi',
        query: {}
      })

    }).catch((e: any) => {
      isLoading.value = false
      console.log(e)
    })
    
}
const pengajuanSidikJari = async (noKa: any) => {
  var jsonSidik = {
    "url": "Sep/pengajuanSEP",
    "method": "POST",
    "data": {
      "request": {
        "t_sep": {
          "noKartu": noKa,
          "tglSep": moment(new Date()).format('YYYY-MM-DD'),
          "jnsPelayanan": "2",
          "jnsPengajuan": "2",
          "keterangan": "Finger Print",
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, jsonSidik)
  isLoading.value = false
  await approveSidikJari(noKa)

}
const approveSidikJari = async (noKa: any) => {
  var jsonAprrove = {
    "url": "Sep/aprovalSEP",
    "method": "POST",
    "data": {
      "request": {
        "t_sep": {
          "noKartu": noKa,
          "tglSep": moment(new Date()).format('YYYY-MM-DD'),
          "jnsPelayanan": "2",
          "jnsPengajuan": "2",
          "keterangan": "Finger Print APPROVE",
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, jsonAprrove)
  isLoading.value = false
  save()
  return

}

const DialogConfirm = () => {
    confirm.require({
        group: 'templating',
        message: 'Apakah anda yakin akan menghapus data SEP?',
        header: 'Konfirmasi Hapus Data SEP',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
          if(!item.alasanhapusSEP) {
            H.alert('warning', 'Alasan wajib diisi.');
            resolve(false)
            return;
          }

          hapusSEP()
        },
        reject: () => { },
    })
}

const hapusSEP = async () => {
  var json = {
    "url": "SEP/2.0/delete",
    "method": "DELETE",
    "data": {
      "request": {
        "t_sep": {
          "noSep": item.noSep,
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  isLoading.value = false
  if (res.metaData.code == 200) {
    LOG.value = `Hapus No. SEP ${item.noSep} dibuat BRIDGING dengan tgl ${H.formatDate(item.tglSep, 'YYYY-MM-DD')} dihapus dengan alasan : ${item.alasanhapusSEP}`
    delete item.noSep
    await savePemakaianAsuransi()
  }

}

const cekBackdate = (tglsep: any, fdate: any) => {
  var sepdate = new Date(tglsep);
  var currDate = new Date(fdate);
  var backdate = sepdate < new Date(currDate.getFullYear(), currDate.getMonth(), currDate.getDate()) ? true : false
  return backdate;
}
const batal = () => {
  window.history.back()
}
const cetakSEP = (e: any) => {
  let ppk = e.noSep.substr(0, 8)
  if (SETTING.value.BPJS_kodePPKRujukan != ppk) {
    H.alert('warning', 'SEP dari faskes lain tidak bisa dicetak')
    return
  }
  isCetak.value = true
  qzService.printData(`registrasi/pemakaian-asuransi/sep?pdf=true&nosep=${e.noSep}`, 'SEP', 1)
  // H.printBlade('registrasi/pemakaian-asuransi/sep?nosep=' + e.noSep + '&pdf=false');
  // qzService.printData('registrasi/pemakaian-asuransi/sep?nosep=' + e.noSep + "&pdf=true", 'SEP', 1)
  isCetak.value = false

}

const cariSEPByKartu = async (nosep: any) => {
  var tglmulai = H.formatDate(registrasi.value.tglregistrasi, 'YYYY-MM-DD');
  var tglakhir = H.formatDate(addDays(tglmulai,3),'YYYY-MM-DD');
  var noKartu = item.noKartu;
  var json = {
    "url": `monitoring/HistoriPelayanan/NoKartu/${noKartu}/tglMulai/${tglmulai}/tglAkhir/${tglakhir}`,
    "method": "GET",
    "data": null
  }
  isLoading.value = true
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  isLoading.value = false
  if (res.metaData.code == 200) {
    console.log(res.response);

    var dtres = res.response.histori;
    dtres.forEach((sep) => {
      console.log(sep);
      console.log(registrasi.value);
      if (H.formatDate(sep.tglSep, 'YYYY-MM-DD') == tglmulai
        && sep.poliTujSep == registrasi.value.kdsubspesialis) {
        item.noSep = sep.noSep;
      }
    })

  }else{
    H.alert('Informasi', 'Tidak ditemukan data SEP')
  }
}

function addDays(date: Date, days: number): Date { 
  const result = new Date(date); 
  result.setDate(result.getDate() + days); 
  return result; 
}

const cariSEP = async (noSep: any) => {
  var json = {
    "url": "SEP/" + noSep,
    "method": "GET",
    "data": null
  }
  isLoading.value = true
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  isLoading.value = false
  if (res.metaData.code == 200) {
    item.tglRujukan = res.response.tglSep
    item.tglSep = res.response.tglSep
    item.noSurat = res.response.kontrol.noSurat
    item.namaDPJP = res.response.kontrol.nmDokter
    item.kodeDPJP = res.response.kontrol.kdDokter
    item.catatan = res.response.catatan
    item.noKunjungan = res.response.noRujukan
    item.ppkRujukan = res.response.jnsPelayanan == 'Rawat Jalan' ? peserta.value.provUmum.nmProvider : SETTING.value.BPJS_namaPPKRujukan
    item.eksekutif = res.response.poliEksekutif == '1' ? true : false
    item.cob = res.response.cob == '1' ? true : false

    await fetchSupspesialis({ query: res.response.poli })
    for (let x = 0; x < d_Subspesialis.value.length; x++) {
      const element = d_Subspesialis.value[x];
      if (element.nama == res.response.poli) {
        item.spesialis = element
        await changeSpesialis(item.spesialis)
        break
      }
    }
    d_dpjpLayan.value.forEach((element: any) => {
      if (element.kode == res.response.dpjp.kdDPJP) {
        item.dpjpLayan = element
        return
      }
    });

    await fetchDiagnosa({ query: res.response.diagnosa })
    for (let i = 0; i < d_Diagnosa.value.length; i++) {
      const element = d_Diagnosa.value[i];
      let nama = element.nama.split(' - ')
      if (nama.length > 0 && nama[1] == res.response.diagnosa) {
        item.diagAwal = element
        break
      }
    }
    d_KelasRawat.value.forEach((e: any) => {
      if (e.nama == res.response.kontrol.kelasRawat) {
        item.klsRawatHak = e
      }
    });

  }
}
const cariNIK = async () => {
  if (item.nik == undefined) {
    H.alert('warning', 'NIK harus di isi')
    return
  }
  isLoadingKartu.value = true
  await apiNIK(item.nik)

  isLoadingKartu.value = false


}
const apiNIK = async (noKartu: any) => {
  isLoadingPasien.value = true
  // let token = await grecaptcha.execute( import.meta.env.VITE_CAPTCHA_SITEKEY, { action: 'submit' })
  let json = {
    "url": `Peserta/nik/${noKartu}/tglSEP/${H.formatDate(item.tglSep, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null,
    // "token":token
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    peserta.value = res.response.peserta
    d_KelasRawat.value.forEach((element: any) => {
      if (element.kode == peserta.value.hakKelas.kode) {
        item.klsRawatHak = element
        return
      }
    });

    item.noTelepon = res.response.peserta.mr.noTelepon
    if (item.noTelepon != null)
      item.noTelepon = item.noTelepon.length > 14 ? item.noTelepon.substr(0, 14) : item.noTelepon
  } else {
    H.alert('error', res.metaData.message)
  }
  isLoadingPasien.value = false

}

const iCare = async () => {
  if (!item.dpjpLayan == undefined) {
    H.alert('error', 'DPJP Layan  kosong')
    return
  }
  let json = {
    "url": "api/rs/validate",
    "method": "POST",
    "jenis": "i-care",
    "data": {
      "param": item.noKartu,
      "kodedokter": item.dpjpLayan ? parseInt(item.dpjpLayan.kode) : null
    }
  }
  isLoadingIcare.value = true
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  isLoadingIcare.value = false
  if (res.metaData.code == 200) {
    window.open(res.response.url, "_blank")
  } else {
    H.alert('error', res.metaData.message)
  }

}

const sendAntrol = async () => {
  console.log('Halo masuk send antrol')

  return new Promise(async (resolve, reject) => {
    let status = false;
    let error = '';
    let kodeDokterBPJS;
    let jeniskunjungan;
    let nomorreferensi;
    let kdpoli = item.spesialis.kode;
    const jsonCheckAntrean = {
      "url": `antrean/pendaftaran/kodebooking/${registrasi.value.noregistrasi}`,
      "jenis": "antrean",
      "method": "GET",
      "data": null
    }
    await useApi().postBPJS(`/bridging/bpjs/tools`, jsonCheckAntrean).then(async (chkantrol) => {
      console.log("MASUK ANTROL", chkantrol);
      //if(c  hkantrol.metaData.code == 200) {
        for (let i = 0; i < dataRefPoliJKN.value.length; i++) {
          const elpoli = dataRefPoliJKN.value[i];
          if(elpoli.kdsubspesialis == item.spesialis.kode) {
            kdpoli = elpoli.kdpoli
            break;
          }
        }
        const jsonJadwalDokter = {
          "url": `jadwaldokter/kodepoli/${kdpoli}/tanggal/${H.formatDate(item.tglSep, 'YYYY-MM-DD')}`,
          "jenis": "antrean",
          "method": "GET",
          "data": null
        }
        const res = await useApi().postBPJS(`/bridging/bpjs/tools`, jsonJadwalDokter)
        if (res.metaData.code == 200) {
          for (var i = res.response.length - 1; i >= 0; i--) {
            const element = res.response[i]
            const dokternya = item.dpjpLayan ? item.dpjpLayan.kode : ""
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
  
        // Start Penentuan Jenis Kunjungan
        switch (item.pilih) {
          // Rujukan
          case 1:
            // Rujukan Faskes 1 / Puskesmas
            if (item.asal == 1) {
              jeniskunjungan = 1 // (Rujukan FKTP)
              nomorreferensi = item.noKunjungan ? item.noKunjungan : ""
              if (item.noSurat) {
                jeniskunjungan = 3 // (Kontrol)
                nomorreferensi = item.noSurat
              }
            }
            // Rujukan Faskes 2 / Rumah Sakit
            if (item.asal == 2) {
              jeniskunjungan = 4 // (Rujukan Antar RS)
              nomorreferensi = item.noKunjungan ? item.noKunjungan : ""
              if (item.noSurat) {
                jeniskunjungan = 3 // (Kontrol)
                nomorreferensi = item.noSurat
              }
            }
            break;
          case 3:
            jeniskunjungan = 3 // (Kontrol)
            nomorreferensi = item.noKunjungan ? item.noKunjungan : ""
            break;
        }
  
        if (item.spesialis.kode != item.RujukankePoli) {
          jeniskunjungan = 2
          nomorreferensi = item.noKunjungan ? item.noKunjungan : ""
        }
        // End Penentuan Jenis Kunjungan
  
        const jsonAntrol = {
          "noregistrasifk": item.NOREC_PD,
          "kodedokter": kodeDokterBPJS ? kodeDokterBPJS.kodedokter : "",
          "namadokter": kodeDokterBPJS ? kodeDokterBPJS.namadokter : "",
          "jampraktek": kodeDokterBPJS ? kodeDokterBPJS.jadwal : "",
          "jeniskunjungan": jeniskunjungan,
          "nomorreferensi": nomorreferensi,
          "nohp": item.noTelepon ? item.noTelepon : "000000000000",
        }
        await useApi().postNoMessage(`/bridging/antrol/sendDataAntrean`, jsonAntrol).then(async (response: any) => {
          resolve(true)
        }).catch((e: any) => {
          resolve(false);
        })
      //}
    }).catch((errAntrol) => {
      resolve(false);
    })

  })
}

const getWaktuygdikiosk = async (nokartu: any) => {
  await useApi().get(`/bridging/antrol/ambilWaktudiKiosk?nokartu=${nokartu}&tanggal=${H.formatDate(new Date(), 'YYYY-MM-DD')}`).then(async (response: any) => {
    item.waktutaksid1 = response.waktutaksid1
    item.waktutaksid2 = response.waktutaksid2
  })
}
const setJenisPel = () => {
  if (input.value.jenis == 1) {
    input.value.pelayanan = 'Rawat Inap'
  } else {
    input.value.pelayanan = 'Rawat Jalan'
  }
  let json: any = {}
  // sep.value = {}
  // if (input.value.jenis == 1) {
  //   json = {
  //     "url": `Peserta/nokartu/${input.value.noKartu.trim()}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
  //     "method": "GET",
  //     "data": null
  //   }
  //   isLoadingPasien2.value = true
  //   useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
  //     isLoadingPasien2.value = false
  //     if (x.metaData.code == 200) {
  //       peserta.value = x.response.peserta

  //     } else {
  //       H.alert('error', x.metaData.message)
  //     }
  //   })
  // } else {
  //   json = {
  //     "url": ` /RencanaKontrol/nosep/${input.value.noSEP.trim()}`,
  //     "method": "GET",
  //     "data": null
  //   }
  //   isLoadingPasien2.value = true

  //   useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
  //     isLoadingPasien2.value = false
  //     if (x.metaData.code == 200) {
  //       sep.value = x.response
  //       let json2 = {
  //         "url": `Peserta/nokartu/${x.response.peserta.noKartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
  //         "method": "GET",
  //         "data": null
  //       }
  //       useApi().postBPJS('/bridging/bpjs/tools', json2).then((xx) => {
  //         isLoadingPasien2.value = false
  //         if (xx.metaData.code == 200) {
  //           peserta.value = xx.response.peserta

  //         } else {
  //           H.alert('error', xx.metaData.message)
  //         }
  //       })
  //     } else {
  //       H.alert('error', x.metaData.message)
  //     }
  //   })

  // }


}
const hapusSurkon = (e: any) => {
  let json = {
    "url": `/RencanaKontrol/Delete`,
    "method": "DELETE",
    "data": {
      "request": {
        "t_suratkontrol": {
          "noSuratKontrol": e.noSuratKontrol,
          "user": H.namaPegawai()
        }
      }
    }
  }

  e.loadHapus = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    e.loadHapus = false

    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      delete noSuratKontrol.value
      batalSurkon()
      cariSKDP()
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const batalSurkon = () => {
  delete input.value.kodeDokter
  delete input.value.poliKontrol
  delete noSuratKontrol.value
  input.value = {
    filterTgl: {
      start: new Date(),
      end: new Date(),
    },
    noKartu: item.noKartu,
    noSEP: item.noSep,
    filter: d_Filter.value[0],
    jenis: 1,
    tglRencanaKontrol: new Date(),
    tahun: {
      kode: new Date().getFullYear(),
      nama: new Date().getFullYear(),
    },
    bulan: bulanAyena,
  }
}
const changeSpe = async (e: any) => {
  let json = {
    "url": `/RencanaKontrol/JadwalPraktekDokter/JnsKontrol/${input.value.jenis}/KdPoli/${e.kode}/TglRencanaKontrol/${H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  await useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    if (x.metaData.code == 200) {
      d_dpjpLayan_Sur.value = x.response.list
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}

const fetchSupspesialisSur = async (filter: any) => {
  if (!filter.query) return
  if (filter.query.length < 3) return
  let json = {
    "url": "referensi/poli/" + encodeURI(filter.query),
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    d_Subspesialis_Sur.value = res.response.poli

  } else {
    H.alert('error', res.metaData.message)
  }
}

const saveSurkon = () => {
  if (input.value.jenis == 1) {
    insertSPRI()
  } else {
    insertRencanaKontrol()
  }

}
const insertRencanaKontrol = () => {
  let json = {}

  if (input.value.noSuratKontrol) {
    json = {
      "url": `/RencanaKontrol/Update`,
      "method": "PUT",
      "data": {
        "request": {
          "noSuratKontrol": input.value.noSuratKontrol,
          "noSEP": input.value.noSEP,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
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
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoadingSur.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoadingSur.value = false
    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSuratKontrol
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
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
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
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoadingSur.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoadingSur.value = false
    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSPRI
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}

const editSurkon = async (e: any) => {
  if (e.terbitSEP == 'Sudah') {
    H.alert('error', 'SEP sudah terbit tidak bisa di edit');
    return
  }

  activeIdxSurkon.value = 1
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

  await fetchSupspesialisSur({ query: e.namaPoliTujuan })
  for (let x = 0; x < d_Subspesialis_Sur.value.length; x++) {
    const element = d_Subspesialis_Sur.value[x];
    if (element.nama == e.namaPoliTujuan) {
      input.value.poliKontrol = element
      await changeSpe(element)
      break
    }
  }
  d_dpjpLayan_Sur.value.forEach(element => {
    if (element.kodeDokter == e.kodeDokter) {
      input.value.kodeDokter = element
    }
  });

  setJenisPel()
}
const cetakSurkon = async (e: any) => {

  let json = {
    "url": `Peserta/nokartu/${e.noKartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  e.isLoading = true
  let response = await useApi().postBPJS('/bridging/bpjs/tools', json)
  e.isLoading = false
  let nosuratkontrol = e.noSuratKontrol
  let tglrencanakontrol = e.tglRencanaKontrol
  let txttglentrirencanakontrol = e.tglTerbitKontrol
  let noka = e.noKartu
  let nama = e.nama
  let tgllahir = response.response.peserta.tglLahir

  let namaPoliTujuan = e.namaPoliTujuan
  let jeniskelamin = response.response.peserta.sex
  let jnsKontrol = e.jnsKontrol
  let namaDokter = e.namaDokter
  let kddx = '-'
  let nmdpjpsepasal = '-';// e.namaDokter ? e.namaDokter : '-'
  let iddok = 'null'
  let dxawal = '-'

  if (e.noSepAsalKontrol != null) {
    let json = {
      "url": "sep/" + e.noSepAsalKontrol,
      "method": "GET",
      "data": null
    }
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      if (x.metaData.code == 200) {
        dxawal = x.response.diagnosa

        cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
          nama, tgllahir, SETTING.value.BPJS_namaPPKRujukan, namaPoliTujuan, jeniskelamin, dxawal,
          jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);


      } else {
        H.alert('error', x.metaData.message);
      }
    })

  } else {
    dxawal = '-'
    cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
      nama, tgllahir, SETTING.value.BPJS_namaPPKRujukan, namaPoliTujuan, jeniskelamin, dxawal,
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
const rujukanKeluar = () => {
  router.push({ name: 'module-integrasi-sistem-rujukan' })
}
const pilihSEPHistory = (e: any) => {
  input.value.noSEP = e.noSep
  H.alert('info', `SEP ${e.noSep} ${e.poli} terpilih `)
}
const cariMon = async (e: any) => {
  let now = new Date()
  let dari = new Date(now.setDate(now.getDate() - 90))
  let json = {
    "url": `monitoring/HistoriPelayanan/NoKartu/${pasien.value.nobpjs}/tglMulai/${H.formatDate(e.start, 'YYYY-MM-DD')}/tglAkhir/${H.formatDate(e.end, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  loadingMon.value = true
  dataSourceHistory.value = []
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  loadingMon.value = false
  if (res.metaData.code == 200) {
    dataSourceHistory.value = res.response.histori
  } else {
    H.alert('error', res.metaData.message)
  }
}
const fetchRefPoliJKN = async () => {
  isLoading.value = true
  let json = {
    "url": `ref/poli`,
    "jenis": "antrean",
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  isLoading.value = false
  if (res.metaData.code == 200) {
    dataRefPoliJKN.value = res.response
  } else {
    dataRefPoliJKN.value = []
  }
  isLoading.value = false
}

const apiRujukanAktif = async () => {

  if (!item.noKartu) {
    return []
  }

  let jsonPcare = {
    "url": `Rujukan/Peserta/${item.noKartu}`,
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(`/bridging/bpjs/tools`, jsonPcare)
  if (res.metaData.code == 200) {
    res.response.rujukan.tglExpired = H.formatDate(new Date(new Date(res.response.rujukan.tglKunjungan).setDate(new Date(res.response.rujukan.tglKunjungan).getDate() + 90)), 'YYYY-MM-DD')
    res.response.rujukan.color_status = new Date(res.response.rujukan.tglExpired) <= new Date() ? 'danger' : 'success'
    return res.response.rujukan
  } else {
    let jsonRS = {
      "url": `Rujukan/RS/Peserta/${item.noKartu}`,
      "method": "GET",
      "data": null
    }
    const res2 = await useApi().postBPJS(`/bridging/bpjs/tools`, jsonRS)
    if (res2.metaData.code == 200) {
      res2.response.rujukan.tglExpired = H.formatDate(new Date(new Date(res2.response.rujukan.tglKunjungan).setDate(new Date(res2.response.rujukan.tglKunjungan).getDate() + 90)), 'YYYY-MM-DD')
      res2.response.rujukan.color_status = new Date(res2.response.rujukan.tglExpired) <= new Date() ? 'danger' : 'success'
      return res2.response.rujukan
    } else {
      return []
    }
  }
  isLoading.value = false
}

watch(
  () => activeIdxSurkon.value,
  (newValue, oldValue) => {
    batalSurkon()
    if (newValue != oldValue) {
      if (newValue == 1) {
        setJenisPel()
      }
      if (newValue == 0) {
        cariSKDP()
      }

    }
  }
)

watch(
  () => activeIdx.value,
  (newValue, oldValue) => {
    if (newValue == 0) {

    }
    if (newValue == 1) {

    }
    if (newValue == 2) {
      apiHistory()
    }
  }
)

watch(
  () => item.noSurat,
  (newValue, oldValue) => {
    if (newValue != undefined && newValue != oldValue
      && newValue.length == 19
      && item.kodeDPJP == undefined) {
      apiSuratKontrolByNo(newValue)
    }
  }
)
watch(
  () => item.noSep,
  (newValue, oldValue) => {
    if (newValue != undefined && newValue != oldValue
      && newValue.length == 19) {
      cariSEP(newValue)
    }
  }
)
watch(
  () => item.jnsPelayanan,
  (newValue, oldValue) => {
    if (newValue != oldValue
      && newValue == 1
    ) {
      item.ppkRujukan = SETTING.value.BPJS_namaPPKRujukan
      item.ppkRujukan_kode = SETTING.value.BPJS_kodePPKRujukan
      item.tglRujukan = H.formatDate(new Date(), 'YYYY-MM-DD')

    }
  }
)

onMounted(() => {
  changeAsal(item.pilih)
  pasienByID(ID_PASIEN)
  fetchRefPoliJKN()
})
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/registrasi/pemakaian-asuransi.scss';

.view-wrapper.has-top-nav .is-navbar-lg {
  margin-top: 138px;
}
</style>
