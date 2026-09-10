<template>
  <ConfirmDialog group="templating2">
    <template #message="slotProps">
      <div style="width:500px;height:300px;">
        <table style="width:100%;height:100%;border-collapse: collapse">
          <tr>
            <td style="text-align:center;vertical-align:middle">
              <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
            </td>
          </tr>
          <tr>
            <td style="padding:7px;padding-bottom:0px;text-align:center">
              <p style="font-size:large;font-weight:bold">{{ slotProps.message.message }}</p>
            </td>
          </tr>
        </table>
      </div>
    </template>
  </ConfirmDialog>
  <div>
    <div>
      <div>
        <div :class="[!isStuck && 'px-0 mb-4']" class="form-layout is-stacked-2" style="width: 100%; max-width: none;">
          <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
              <div class="form-header-inner">
                <div class="left">
                  <h3>{{ formName }}
                    <span v-if="input.user_input && input.user_input.namalengkap" style="color: darkslategray">
                      <br>Milik - {{ input.user_input.namalengkap }}
                    </span>
                  </h3>
                </div>
                <div class="right">
                  <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :RUANGAN="namaRuanganDinamis" :COLLECTION="COLLECTION"
                    :isLockSimpan="paramRiwayat" :isLoading="isLoading" @simpan="simpan"
                    @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" :ID_EMR="input.id ? input.id : ''">
                  </ButtonEmr>
                </div>
              </div>

              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-3">

              <div class="form-header-inner pt-3 buttons">
                <VButton type="button" rounded outlined color="info" @click="modalTindakan = true" icon="lucide:layers">
                  Tindakan</VButton>
                <VButton type="button" rounded outlined color="info" @click="modalResep = true"
                  icon="lnir lnir-medical-sign">
                  Resep</VButton>
                <VButton type="button" rounded outlined color="info" @click="modalKonsultasi = true" icon="lucide:send">
                  Transfer Pasien</VButton>
                <VButton type="button" rounded outlined color="info" @click="modalLaboratorium = true"
                  icon="fas fa-temperature-high"> Laboratorium</VButton>
                <VButton type="button" rounded outlined color="info" @click="modalRadiologi = true"
                  icon="fas fa-radiation">
                  Radiologi</VButton>
                <VButton type="button" rounded outlined color="info" @click="modalPenunjangKhusus = true"
                  icon="lucide:file-text">Penunjang Khusus</VButton>
                <VButton type="button" rounded outlined color="info" @click="modalBedah = true" icon="lnil lnil-cut">
                  Bedah
                </VButton>
                <VButton type="button" class="mr-3" rounded outlined color="primary" raised icon="feather:folder"
                  :loading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
                </VButton>
                <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                  :loading="isLoading" @click="pilihTemplate(index)"> Pilih Riwayat
                </VButton>
              </div>
            </div>
          </div>
        </div>
        <Dialog v-model:visible="modalTindakan" modal header="Tindakan" :style="{ width: '100rem' }"
          :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
          <Tindakan v-if="modalTindakan" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
            :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
        </Dialog>
        <Dialog v-model:visible="modalResep" modal header="Order Resep" :style="{ width: '100rem' }"
          :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
          <Resep v-if="modalResep" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
            :norec_apd="props.registrasi.norec_apd" :ruanganfk="props.registrasi.objectruanganlastfk"
            :departemenfk="props.registrasi.objectdepartemenfk" :pasien="props.pasien" :registrasi="props.registrasi" />
        </Dialog>
        <Dialog v-model:visible="modalKonsultasi" modal header="Konsultasi" :style="{ width: '100rem' }"
          :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
          <Konsultasi v-if="modalKonsultasi" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
            :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
        </Dialog>
        <Dialog v-model:visible="modalLaboratorium" modal header="Order Laboratorium" :style="{ width: '100rem' }"
          :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
          <Laboratorium v-if="modalLaboratorium" :nocmfk="props.registrasi.nocmfk" :NOREC_PD="props.registrasi.norec_pd"
            :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
        </Dialog>
        <Dialog v-model:visible="modalRadiologi" modal header="Order Radiologi" :style="{ width: '100rem' }"
          :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
          <Radiologi v-if="modalRadiologi" :nocmfk="props.registrasi.nocmfk" :NOREC_PD="props.registrasi.norec_pd"
            :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
        </Dialog>
        <Dialog v-model:visible="modalPenunjangKhusus" modal header="Penunjang Khusus" :style="{ width: '100rem' }"
          :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
          <Penunjang v-if="modalPenunjangKhusus" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
            :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
        </Dialog>
        <Dialog v-model:visible="modalBedah" modal header="Bedah" :style="{ width: '100rem' }"
          :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
          <Bedah v-if="modalBedah" :nocmfk="props.registrasi.nocmfk" :norec_pd="props.registrasi.norec_pd"
            :norec_apd="props.registrasi.norec_apd" :pasien="props.pasien" :registrasi="props.registrasi" />
        </Dialog>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1>Nama Template&emsp;&emsp;
                <span style="color:red">**Hanya diisi jika ingin membuat template</span>
              </h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.namatemplate" rows="1" :disabled="paramRiwayat">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 columns is-multiline">
              <div class="column is-4">
                <h1 style="font-weight: bold;" class="pb-1">Tanggal Kedatangan</h1>
                <VField>
                  <VDatePicker v-model="input.tanggalKedatangan" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded"
                            :disabled="paramRiwayat" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4">
                <h1 style="font-weight: bold;" class="pb-1">Jam Kedatangan</h1>
                <VField>
                  <VDatePicker v-model="input.jamKedatangan" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents"
                            :disabled="paramRiwayat" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-4">
                <h1 style="font-weight: bold;" class="pb-1">Jam Asesmen Awal</h1>
                <VField>
                  <VDatePicker v-model="input.jamAsesmenAwal" color="green" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:clock">
                          <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents"
                            :disabled="paramRiwayat" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 pl-0 pr-0 pt-0">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-6">
                <h1>Alloanamnesis</h1>
                <VField>
                  <VTextarea v-model="input.kebpilihanallo" rows="3" :disabled="paramRiwayat">
                  </VTextarea>
                </VField>
                <VField>
                  <VControl>
                    <Multiselect v-model="input.pilihanallo" :attrs="{ value }" placeholder="--Pilih--" label="label"
                      :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off"
                      :disabled="paramRiwayat">
                    </Multiselect>
                  </VControl>
                </VField>
                <VField label="Alergi (Rekasi Obat)" v-if="props.registrasi.namadepartemen.indexOf('RAWAT INAP') > -1">
                  <VControl>
                    <VTextarea v-model="input.alergiRekasiObat" rows="3" :disabled="paramRiwayat" />
                  </VControl>
                </VField>

              </div>

              <div class="column is-6">
                <VField>
                  <h1>Anamnesis</h1>
                  <VTextarea v-model="input.anamnesis" rows="3" :disabled="paramRiwayat">
                  </VTextarea>
                </VField>
                <!-- <VField label="Indikasi Rawat Inap" v-if="props.registrasi.namadepartemen.indexOf('RAWAT INAP') > -1">
                  <VControl>
                    <VTextarea v-model="input.indikasi" rows="3" :disabled="paramRiwayat" />
                  </VControl>
                </VField> -->
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 columns is-multiline pb-0">
          <div class="column is-4">
            <h1>Keadaan Umum</h1>
            <VField>
              <VControl>
                <Multiselect v-model="input.keadaanumum" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off"
                  :disabled="paramRiwayat">
                </Multiselect>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1>Tekanan Darah</h1>
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarah"
                  :disabled="paramRiwayat" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>mmHg</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <h1>PR</h1>
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="PR" v-model="input.nadi" :disabled="paramRiwayat" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>x/menit</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pt-0">
            <h1>RR</h1>
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="RR" v-model="input.nafas" :disabled="paramRiwayat" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>x/menit</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pt-0">
            <h1>Suhu</h1>
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="Suhu" v-model="input.celcius" :disabled="paramRiwayat" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>°C </VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pt-0">
            <h1>SaO2</h1>
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="Saturasi O2 (SpO2)" v-model="input.sao2"
                  :disabled="paramRiwayat" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>%</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pt-0">
            <h1>Tinggi Badan</h1>
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.tt"
                  :disabled="paramRiwayat" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>cm</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pt-0">
            <h1>Berat Badan</h1>
            <VField addons>
              <VControl expanded>
                <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.bb"
                  :disabled="paramRiwayat" />
              </VControl>
              <VControl class="field-addon-body">
                <VButton static>gram</VButton>
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pt-0">
            <h1>GCS</h1>
            <VField addons>
              <VControl class="field-addon-body">
                <VButton static>E</VButton>
              </VControl>
              <VControl expanded>
                <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label" :options="d_gcse"
                  :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="paramRiwayat"
                  style="border-radius:0px 4px 4px 0px;height:100%">
                </Multiselect>
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline column is-12 pt-0 pb-0">
          <div class="column is-4 pt-0">
            <VField addons>
              <VControl class="field-addon-body">
                <VButton static>V</VButton>
              </VControl>
              <VControl expanded>
                <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label" :options="d_gcsv"
                  :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="paramRiwayat"
                  style="border-radius:0px 4px 4px 0px;height:100%">
                </Multiselect>
              </VControl>
            </VField>
          </div>
          <div class="column is-4 pt-0">
            <VField addons>
              <VControl class="field-addon-body">
                <VButton static>M</VButton>
              </VControl>
              <VControl expanded>
                <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label" :options="d_gcsm"
                  :searchable="true" track-by="label" mode="single" autocomplete="off" :disabled="paramRiwayat"
                  style="border-radius:0px 4px 4px 0px;height:100%">
                </Multiselect>
              </VControl>
            </VField>
          </div>
        </div>

        <hr>

          <div class="column is-3">
            <h1>Pilih Section Status Lokalis</h1>
            <VControl class="prime-auto">
              <AutoComplete v-model="input.section_SL" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" />
            </VControl>
          </div>

          <div class="column is-3 is-flex is-align-items-center is-justify-content-start py-1"
            v-if="namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 || namaRuanganDinamis.toUpperCase().indexOf('INTERNA') > -1 || (namaRuanganDinamis.toUpperCase().indexOf('BEDAH') > -1 || namaRuanganDinamis.toUpperCase().indexOf('BTKV') > -1 || namaRuanganDinamis.toUpperCase().indexOf('ORTHOPEDI SPINE') > -1 || namaRuanganDinamis.toUpperCase().indexOf('NEFROLOGI') > -1) && namaRuanganDinamis.toUpperCase().indexOf('BEDAH MULUT') == -1 && namaRuanganDinamis.toUpperCase().indexOf('BEDAH UROLOGI') == -1">
            <VButton type="button" rounded outlined color="link" icon="feather:file" :isLoading="isLoading"
              @click="pilihAsesmen(input.section_SL.label)"> Pilih Asesmen
            </VButton>
          </div>

        <hr>

        <div class="column is-12">
          <h1>Indikasi Rawat Inap</h1>
          <VField>
            <VTextarea rows="2" v-model="input.indikasi"></VTextarea>
          </VField>
        </div>

        <div class="column is-12">
          <h1>Alergi (Reaksi Obat)</h1>
          <VField>
              <VTextarea rows="2" v-model="input.alergiReaksiObat" ></VTextarea>
          </VField>
        </div>

        <div class="column is-12">
          <h1>Diet Yang Telah Diberikan & Diet Yang Harus Dilakukan Di Rumah</h1>
          <VField>
              <VTextarea rows="2" v-model="input.dietDiberikan" ></VTextarea>
          </VField>
        </div>

        <hr>

        <div class="columns is-multiline">
          <div class="column is-12">
            <TabMenu :model="[]" :scrollable="true" />
            <div class="p-tabmenu-uy">
              <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="halPF()"><span class="p-menuitem-icon fas fa-book-medical"></span>
                      <span class="p-menuitem-text"
                        v-if="namaRuanganDinamis.toUpperCase().indexOf('PSIKOLOGI') == -1">Pemeriksaan
                        Fisik</span>
                      <span class="p-menuitem-text" v-else>Status Lokalis</span>
                    </a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                  v-if="(namaRuanganDinamis.toUpperCase().indexOf('GIGI') > -1 || namaRuanganDinamis.toUpperCase().indexOf('MULUT') > -1 || namaRuanganDinamis.toUpperCase().indexOf('ENDODONSIA') > -1)">
                  <li class="p-tabmenuitem"
                    :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '', isLoading ? 'is-disabled' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="halPFL()">
                      <span class="p-menuitem-icon fas fa-laptop-medical"></span><span class="p-menuitem-text">Status
                        Lokalis Gigi</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar"
                  v-if="namaRuanganDinamis.toUpperCase().indexOf('GIGI') == -1 && namaRuanganDinamis.toUpperCase().indexOf('JANTUNG') == -1 && namaRuanganDinamis.toUpperCase().indexOf('LAKTASI') == -1 && namaRuanganDinamis.toUpperCase().indexOf('BEDAH MULUT') == -1 && namaRuanganDinamis.toUpperCase().indexOf('PSIKOLOGI') == -1">
                  <li class="p-tabmenuitem"
                    :class="[(TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : ''), isLoading ? 'is-disabled' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="halPFL()" :disabled="true">
                      <span class="p-menuitem-icon fas fa-laptop-medical"></span><span class="p-menuitem-text">Status
                        Lokalis</span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="column is-12"
                    v-if="isPemeriksaanFisik == false && namaRuanganDinamis.toUpperCase().indexOf('GIGI') > -1">
                    <TOdontogram></TOdontogram>
                </div> -->

        <div class="column is-12" align="center"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('JIWA') == -1"
          style="margin-top: -20px;">

          <template
            v-if="(namaRuanganDinamis.toUpperCase().indexOf('INTERNA') > -1 || namaRuanganDinamis.toUpperCase().indexOf('GERIATRI') > -1)">
            <ImgDraw elemenID="Gambar" :valueImg="input.Gambar" height="400" width="750"
              imageSrc="/images/simrs/internafix.png" />
            <VField style="display: none !important">
              <VControl>
                <VTextarea v-model="input.keteranganCanvasInterna" rows="10" style="margin-top: 15px;"
                  placeholder="Keterangan">
                </VTextarea>
              </VControl>
            </VField>
          </template>
          <ImgDraw elemenID="Gambar" :valueImg="input.Gambar" height="580" width="600"
            imageSrc="/images/simrs/paru-paru.jpeg" v-else-if="namaRuanganDinamis.toUpperCase().indexOf('PARU') > -1" />
          <ImgDraw elemenID="Gambar" :valueImg="input.Gambar" height="400" width="850"
            imageSrc="/images/simrs/penyakitdalam.png"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('HEMODIALIS') > -1" />
          <ImgDraw elemenID="Gambar" height="550" width="850"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('THT') > -1" imageSrc="/images/simrs/tht-full.png"
            :valueImg="input.Gambar" class="mb-5" />
          <ImgDraw elemenID="Gambar" height="500" width="500"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('VCT') > -1" imageSrc="/images/simrs/paru-paru.jpeg"
            :valueImg="input.Gambar" class="mb-5" />
          <ImgDrawOdon elemenID="Gambar" height="490" width="900"
            v-else-if="(namaRuanganDinamis.toUpperCase().indexOf('GIGI') > -1 || namaRuanganDinamis.toUpperCase().indexOf('MULUT') > -1 || namaRuanganDinamis.toUpperCase().indexOf('ENDODONSIA') > -1)"
            imageSrc="/images/simrs/odon.png" :valueImg="input.Gambar" class="mb-5" />
          <template v-else-if="namaRuanganDinamis.toUpperCase().indexOf('HEMATOLOGI') > -1">
            <h1 style="margin-bottom: 10px; margin-top: 5px;font-weight: bold; ">
              Status Lokalis
            </h1>
            <ImgDraw elemenID="Gambar" :valueImg="input.Gambar" height="600" width="800"
              imageSrc="/images/simrs/outline-human-body.jpg" />
          </template>
          <ImgDraw elemenID="Gambar" :valueImg="input.Gambar" height="500" width="700"
            imageSrc="/images/simrs/outline-human-body.jpg"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('MEDICAL') > -1" />
          <ImgDraw elemenID="Gambar" :valueImg="input.Gambar" height="600" width="500"
            imageSrc="/images/simrs/outline-only-body.jpg"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('KULIT') > -1 || namaRuanganDinamis.toUpperCase().indexOf('SPEKTRA') > -1 || namaRuanganDinamis.toUpperCase().indexOf('COSMEDIC') > -1" />
          <ImgDraw elemenID="Gambar" :valueImg="input.Gambar" height="300" width="1000"
            imageSrc="/images/simrs/obstetrifix.png"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn == 'Obstetri'" />
          <ImgDraw elemenID="Gambar" :valueImg="input.Gambar" height="400" width="800"
            imageSrc="/images/simrs/ginekologifix.png"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn == 'Ginekologi'" />
          <ImgDraw elemenID="Gambar" height="900" width="700" imageSrc="/images/simrs/kestrad.png"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('TRADISIONAL') > -1" />
          <ImgDraw elemenID="Gambar" height="130" width="866" imageSrc="/images/emr/wongBaker.png"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('FISIOTERAPI') > -1" />
          <template v-else-if="namaRuanganDinamis.toUpperCase().indexOf('JANTUNG') > -1">
            <ImgDraw elemenID="Gambar" height="400" width="700" imageSrc="" />
          </template>
          <ImgDraw elemenID="Gambar" height="600" width="500" imageSrc="/images/simrs/outline-only-body.jpg" v-else-if="namaRuanganDinamis.toUpperCase().indexOf('KULIT') > -1
            && namaRuanganDinamis.toUpperCase().indexOf('SPEKTRA') > -1
            && namaRuanganDinamis.toUpperCase().indexOf('COSMEDIC') > -1" />
          <template v-else-if="jenisTrauma != '' && jenisTrauma != null">
            <!-- IS TRAUMA -->
            <ImgDraw elemenID="Gambar" height="500" width="700" imageSrc="/images/simrs/odontogram.png"
              v-if="namaRuanganDinamis.toUpperCase().indexOf('BEDAH MULUT') > -1" />
            <template v-else-if="namaRuanganDinamis.toUpperCase().indexOf('DIGESTIVE') > -1">
              <!-- <ImgDraw elemenID="Gambar_1" height="500" width="700" imageSrc="/images/simrs/ususdigestive.jpg" />
              <br>
              <ImgDraw elemenID="Gambar_2" height="500" width="550" imageSrc="/images/simrs/liverdigestive.jpg" />
              <br>
              <ImgDraw elemenID="Gambar_3" height="760" width="570" imageSrc="/images/simrs/perutdigestive.jpg" /> -->
              <!-- <br> -->
              <ImgDraw elemenID="Gambar_1" height="550" width="500" imageSrc="/images/simrs/digestive_1_liver.png" />
              <ImgDraw elemenID="Gambar_2" height="490" width="420" imageSrc="/images/simrs/digestive_2_usus.png" />
              <ImgDraw elemenID="Gambar_3" height="620" width="600" imageSrc="/images/simrs/digestive_3_perut.png" />
              <ImgDraw elemenID="Gambar_4" height="500" width="700" imageSrc="/images/simrs/outline-human-body.jpg" />
            </template>
            <ImgDraw elemenID="Gambar" height="500" width="700" imageSrc="/images/simrs/outline-human-body.jpg"
              v-else />
            <template v-if="jenisTrauma == 'Trauma'">
              <TraumaScore @updateInput="handleInputUpdate" :input="input"></TraumaScore>
            </template>
          </template>
        </div>

        <!-- START HEMODIALISA -->
        <div class="column is-12 mt-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('HEMODIALIS') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Status Cairan">
                <div class="columns is-multiline column is-12">
                  <div class="column is-12" style="font-weight:bold">Kekurangan</div>
                  <div class="column is-12 columns">
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Normal" label="Normal"
                          v-model="input.CBNormalKekurangan" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Kelebihan" label="Kelebihan"
                          v-model="input.CBNormalKekurangan" />
                      </VControl>
                    </div>
                  </div>
                  <div class="column is-6">
                    <VField label="Berat badan saat ini :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSBeratBadanSaatIni" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>Kg</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Edema extremitas :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBEdemaExtremitas" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <label style="font-weight:lighter">Berat Badan Post HD terakhir :</label>
                    <VField addons>
                      <VControl>
                        <VInput type="number" class="input" v-model="input.TBSBeratBadanPostHD" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Acites :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBAcites" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <label style="font-weight:lighter">Berat Badan Kering :</label>
                    <VField addons>
                      <VControl>
                        <VInput type="number" class="input" v-model="input.TBSBeratBadanKering" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Kongesti paru :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBKongestiParu" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Status Nutrisi">
                <div class="columns is-multiline column is-12">
                  <div class="column is-12 columns">
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Buruk" label="Buruk"
                          v-model="input.CBBurukSN" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Sedang" label="Sedang"
                          v-model="input.CBBurukSN" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Baik" label="Baik"
                          v-model="input.CBBurukSN" />
                      </VControl>
                    </div>
                  </div>
                  <div class="column is-4">
                    <VField label="Tinggi Badan :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSTinggiBadanSN" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>cm</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="BMI :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSbmiSN" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>Kg/m<sup>2</sup></VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-4 columns is-multiline">
                    <div class="column is-12" style="font-weight:lighter">Kategori BMI</div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Under" label="Under"
                          v-model="input.CBUnderKB" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Normal" label="Normal"
                          v-model="input.CBUnderKB" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Over" label="Over"
                          v-model="input.CBUnderKB" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Obese" label="Obese"
                          v-model="input.CBUnderKB" />
                      </VControl>
                    </div>
                  </div>
                  <div class="column is-4">
                    <label style="font-weight:lighter">Rata-rata peningkatan BB antar HD :</label>
                    <VField addons>
                      <VControl>
                        <VInput type="number" class="input" v-model="input.TBSRataRataPeningkatanBBAntarHD" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <label style="font-weight:lighter">Kadar Albumin :</label>
                    <VField addons>
                      <VControl>
                        <VInput type="number" class="input" v-model="input.TBSKaadarAlbumin" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>mg/DI</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 columns is-multiline">
                    <div class="column is-12" style="font-weight:lighter">Nafsu Makan :</div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Kurang" label="Kurang"
                          v-model="input.CBKurangNM" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Normal" label="Normal"
                          v-model="input.CBKurangNM" />
                      </VControl>
                    </div>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Evaluasi Dialisis (Diisi Sesuai Jenis Pasien)">
                <div class="column is-12 columns">
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" circle true-value="Pasien Hemodialisis"
                        label="Pasien Hemodialisis" v-model="input.CBPasienHemodialisisTD" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" circle true-value="Pasien Peritonial dialysis"
                        label="Pasien Peritonial dialysis" v-model="input.CBPasienHemodialisisTD" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12" style="font-weight:bold">A. Pasien Hemodialisis</div>
                <div class="column is-12 columns is-multiline">
                  <div class="column is-6">
                    <VField label="Tgl mulai HD pertama :">
                      <VDatePicker v-model="input.DTglMulaiHDPertama" mode="date" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Frekuensi HD :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSFrekuensiHD" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/minggu</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Lama waktu setiap HD :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSLamaWaktuSetiapHD" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>Jam</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <label style="font-weight:lighter">Dialiser yang digunakan, tipe :</label>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBDialiserYangDigunakan" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VField label="Luas membrane :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBLuasMembrane" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6 columns is-multiline">
                    <div class="column is-12" style="font-weight:lighter">Jenis :</div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="Low Flux" label="Low Flux"
                          v-model="input.CBLowFluxJ" />
                      </VControl>
                    </div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" circle true-value="High Flux" label="High Flux"
                          v-model="input.CBLowFluxJ" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-12 columns is-multiline" style="margin-top:10px">
                  <div class="column is-12">Keluhan interdialitik yang sering timbul :</div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Kram" label="Kram"
                        v-model="input.CBKramKI" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Mual Muntah" label="Mual Muntah"
                        v-model="input.CBMualMuntahKI" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Hipertensi" label="Hipertensi"
                        v-model="input.CBHipertensiKI" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Dizziness" label="Dizziness"
                        v-model="input.CBDizzinessKI" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Hipoksemia" label="Hipoksemia"
                        v-model="input.CBHipoksemiaKI" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Komplikasi kardivaskuler"
                        label="Komplikasi kardivaskuler" v-model="input.CBKomplikasiKardivaskulerKI" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Tidak ada keluhan"
                        label="Tidak ada keluhan" v-model="input.CBTidakAdaKeluhanKI" />
                    </VControl>
                  </div>
                  <div class="column is-3">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Lainnya, sebutkan"
                        label="Lainnya, sebutkan" v-model="input.CBLainnyaSebutkan" />
                    </VControl>
                    <VControl style="margin-top: 5px">
                      <VInput type="text" class="input" v-model="input.TBLainnyaSebutkan" />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 columns is-multiline" style="margin-top:10px">
                  <div class="column is-12">Adekuasi Dialisis :</div>
                  <div class="column is-6">
                    <VField label="URR terakhir">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBURRTerakhir" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>%</VButton>
                        </VControl>
                      </VField>
                    </VField>
                    <div class="column is-12 columns">
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="dalam 6 bulan" label="dalam 6 bulan"
                            v-model="input.CBDalam6BulanURRTerakhir" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="1 tahun terakhir"
                            label="1 tahun terakhir" v-model="input.CB1TahunTerakhirURRTerakhir" />
                        </VControl>
                      </div>
                    </div>
                  </div>
                  <div class="column is-6">
                    <VField label="Kt/v :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBKTV" />
                      </VControl>
                    </VField>
                    <div class="column is-12 columns">
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="6 bulan" label="6 bulan"
                            v-model="input.CB6BulanKTV" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="1 tahun terakhir"
                            label="1 tahun terakhir" v-model="input.CB1TahunTerakhirKTV" />
                        </VControl>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="column is-12" style="font-weight:bold">B. Pasien Peritonial dialysis</div>
                <div class="column is-12 columns is-multiline">
                  <div class="column is-12">
                    <VField label="Total cairan keluar / hari :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSTotalCairanKeluar" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>MI</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Frekuensi pergantian /hari :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSFrekuensiPergantian" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>kali,</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <label style="font-weight:lighter">Konsentrasi cairan yang digunakan :</label>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBKCYD" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <label style="font-weight:lighter">Adekuasi Dialisis : Kt/V</label>
                  </div>
                  <div class="column is-6">
                    <label style="font-weight:lighter">Hasil PET :</label>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TBHasilPET" />
                    </VControl>
                  </div>
                  <div class="column is-12">Pemakaian Eritropoitin :</div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" circle true-value="Tidak ada" label="Tidak ada"
                        v-model="input.CBTidakAdaPE" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" circle true-value="Ada" label="Ada"
                        v-model="input.CBTidakAdaPE" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VField label="Sejak :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSejakPE" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Jenisnya :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBJenisnyaPE" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Dosisnya :">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBSDosisnyaPE" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/minggu</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-12 columns">
                    <div class="column is-6">Riwayat Transfusi selama Dialisis :</div>
                    <div class="column is-6">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="Ada" label="Ada"
                          v-model="input.CBAdaRTSD" />
                      </VControl>
                    </div>
                  </div>
                  <div class="column is-12 columns">
                    <div class="column is-6">
                      <VField label="Produksi urine perhari :">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBProduksiUrinePerhari" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <VField label="Warna">
                        <VControl>
                          <VInput type="text" class="input" v-model="input.TBWarnaPUP" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </Fieldset>
            </div>
            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Care Plan">
                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Adekuasi Dialisis"
                      label="Adekuasi Dialisis" v-model="input.CBAdekuasiDialisis" />
                  </VControl>
                </div>
                <div class="column is-12 columns is-multiline" style="margin-left:10px">
                  <div class="column is-12">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Peresepan Hemodialisis"
                        label="Peresepan Hemodialisis" v-model="input.CBPeresepanHemodialisis" />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VField label="Blood Flow rate :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSBloodFlowRate" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>ml/menit</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Anti Koagulan :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSAntiKoagulan" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>Unit</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="dosis awal :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSDosisAwal" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>unit</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Dosis pemeliharaan :">
                      <VField addons>
                        <VControl>
                          <VInput type="number" class="input" v-model="input.TBSDosisPemeliharaan" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>Unit/jam</VButton>
                        </VControl>
                      </VField>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Ukuran dialiser :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBUkuranDialiser" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Frekuensi HD / minggu :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBFrekuensiHDMinggu" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Akses Vaskuler :">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBAksesVaskulerPH" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12" style="margin-top:20px">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Peresepan CAPD" label="Peresepan CAPD"
                        v-model="input.CBPeresepanCAPD" />
                    </VControl>
                  </div>
                  <div class="column is-6">
                    <VField label="Frekuensi pergantian">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBFrekuensiPergantianPCAPD" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Konsetrasi">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBKonsetrasiPCAPD" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Status nutrisi" label="Status nutrisi"
                      v-model="input.CBStatusNutrisi" />
                  </VControl>
                </div>
                <div class="column is-12 columns">
                  <div class="column is-6">
                    <VField label="BB Kering">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBBBKering" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Diet">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBDiet" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12">
                  <VControl raw subcontrol>
                    <VCheckbox class="p-0" color="primary" square true-value="Manajemen Anemia" label="Manajemen Anemia"
                      v-model="input.CBManajamenAnemia" />
                  </VControl>
                </div>
                <div class="column is-12 columns is-multiline">
                  <div class="column is-12">Th/Anemia</div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Terapi Besi" label="Terapi Besi"
                        v-model="input.CBTerapiBesi" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Terapi erythropoietin"
                        label="Terapi erythropoietin" v-model="input.CBTerapiErythropoietin" />
                    </VControl>
                  </div>
                  <div class="column is-4">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square true-value="Transfusi darah" label="Transfusi darah"
                        v-model="input.CBTranfusiDarah" />
                    </VControl>
                  </div>
                </div>
              </Fieldset>
            </div>
          </div>
        </div>

        <!-- START PERIODONSIA -->
        <div class="column is-12 mt-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('PERIODONSIA') > -1">
          <div class="columns is-multiline">
            <div class="column is-12">
              <Periodonsia></Periodonsia>
            </div>
          </div>
        </div>

        <!-- START THT -->
        <div class="column is-12 mt-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('THT') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-8"></div>
            <div class="column is-4" align="right">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="input.lokalistht" true-value="Batas Normal"
                    @click="normallokalistht" label="Dalam Batas Normal" color="primary" circle
                    style="margin: 15px 0px 0px 30px; font-size: 10pt;" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Tes Suara Bisik</h1>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.suarabisik" rows="5">

                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Tes Garpu Tala</h1>
            </div>
            <div class="column is-12">
              <table class="table" width="100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Telinga Kiri</th>
                    <th>Telinga Kanan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      Rinne
                    </td>
                    <td>
                      <VField>
                        <VControl icon="">
                          <VInput type="text" v-model="input.rinnekiri" placeholder="" />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VControl icon="">
                          <VInput type="text" v-model="input.rinnekanan" placeholder="" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Weber
                    </td>
                    <td>
                      <VField>
                        <VControl icon="">
                          <VInput type="text" v-model="input.weberkiri" placeholder="" />
                        </VControl>
                      </VField>
                    </td>
                    <td>
                      <VField>
                        <VControl icon="">
                          <VInput type="text" v-model="input.weberkanan" placeholder="" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                </tbody>

              </table>
              <!-- <VField>
                                <VControl icon="">
                                    <VTextarea v-model="input.garputala" rows="5">

                                    </VTextarea>
                                </VControl>
                            </VField> -->
            </div>
          </div>
        </div>


        <!-- START POLI SARAF -->
        <div class="column is-12 mt-5 px-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('BEDAH UROLOGI') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-4">
              <span>
                Flank
              </span>
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.flank" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span>
                Suprapubik
              </span>
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.suprabolic" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span>
                Genetalia Externa
              </span>
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.genetaliaexterna" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START POLI SARAF -->
        <div class="column is-12 mt-5 px-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('SARAF') > -1 && namaRuanganDinamis.toUpperCase().indexOf('BEDAH SARAF') == -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-8"></div>
            <div class="column is-4" align="right">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="input.lokalissaraf" true-value="Batas Normal"
                    @click="normallokalissaraf" label="Dalam Batas Normal" color="primary" circle
                    style="margin: 15px 0px 0px 30px; font-size: 10pt;" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">C. Pemeriksaan Neurologik</h1>
            </div>
            <div class="column is-12">
              <span>
                1. Kranium <small style="font-weight: bold">(Inspeksi, Palpasi, Perkusi, Arskultasi, Transluminasi, dll)
                </small>
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.kranium" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                2. Korpus Vertebra <small style="font-weight: bold">(Inspeksi, Palpasi, Perkusi, Mobilitas, dll)
                </small>
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.vertabra" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                3. Tanda tanda perangsang selaput otak
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.selaputotak" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                4. Saraf Otak (I-XII) (Kanan/Kiri)
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.sarafotak" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                5. Motorik <small style="font-weight: bold">( Tenaga, Tonus, Koordinasi, Gerakan Involunter, Langkah dan
                  Gaya Jalan ) (Kanan/Kiri) </small>
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.motorik" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                6. Refleks <small style="font-weight: bold">( Fisiologik, Patologik ) (Kanan/Kiri) </small>
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.refleks" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                7. Sensorik <small style="font-weight: bold">( Permukaan, dalam ) (Kanan/Kiri) </small>
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.sensorik" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                8. Vegetatif
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.vegetatif" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                9. Fungsi Luhur <small style="font-weight: bold">( Kesadaran, reaksi emosi, fungsi intelek, proses
                  berfikir, fungsi psikomotorik, fungsi psikosensorik, fungsi bicara dan bahasa ) </small>
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.luhur" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                10. Tanda tanda kemunduran mental <small style="font-weight: bold">(Reflek Memegang, Refleks Menetek,
                  Refleks Snout, Reflek Glabela, Refleks Palmomental, Refleks Korneomandibuler, dll) </small>
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.tandamental" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                11. Nyeri Tekan Saraf
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.nyeritekansaraf" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                12. Tanda Lasegue
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.lasegue" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-3">
              <span>
                13. Lain Lain
              </span>
            </div>
            <div class="column is-12">
              <VField>
                <VControl icon="">
                  <VTextarea v-model="input.lainlain" rows="5">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START:JIWA -->
        <div class="column is-12"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('JIWA') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-6">
              <h1 style="font-weight: bold;">GENOGRAM</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.genogram" rows="8" style="margin-top: 15px;" placeholder="Genogram">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6" style="margin-top: 15px;">
              <h1 style="font-weight: bold;">Fungsi kerja / sosial</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.fungsikerja" placeholder="" />
                </VControl>
              </VField>
              <h1 style="font-weight: bold;">Fungsi premorbid</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.fungsipremorbid" placeholder="" />
                </VControl>
              </VField>
              <h1 style="font-weight: bold;">Faktor organik</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.faktororganik" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" style="margin-top: 15px;">
              <h1 style="font-weight: bold;">Faktor pencetus / penyebab</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.faktorpenyebab" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Faktor keluarga</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.faktorkeluarga" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <h1 style="font-weight: bold;">Riwayat NAPZA</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VRadio v-model="input.optionsnapza" :value="'ada'" label="Ada" name="Ada" color="primary"
                        id="Ada" />
                      <VRadio v-model="input.optionsnapza" :value="'tidak'" label="Tidak" name="Tidak" color="danger"
                        id="Tidak" />
                    </VControl>
                  </VField>
                </div>

                <!-- <div class="column is-3">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.adariwayatnapza"
                                                true-value="Ada" label="Ada" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox" v-model="input.tidakadariwayatnapza"
                                                true-value="Tidak" label="Tidak" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div> -->
              </div>
            </div>
            <div class="column is-9" style="margin-left: 255px;" v-if="input.optionsnapza == 'ada'">
              <h1 style="font-weight: bold;">Lama pemakaian</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.lamapemakaian" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-9" style="margin-left: 255px;" v-if="input.optionsnapza == 'ada'">
              <h1 style="font-weight: bold;">Jenis zat</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.jeniszat" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-9" style="margin-left: 255px;" v-if="input.optionsnapza == 'ada'">
              <h1 style="font-weight: bold;">Cara pemakaian</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.carapemakaian" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-9" style="margin-left: 255px;" v-if="input.optionsnapza == 'ada'">
              <h1 style="font-weight: bold;">Latar belakang pemakaian</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.latarpemakaian" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" style="margin-top: 100px;">
              <h1 style="font-weight: bold;">STATUS PSIKIATRI</h1>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Deskripsi umum</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.deskripsiumum" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Kesadaran</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kesadaran" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Kontak</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kontak" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Bicara</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.bicara" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Orientasi</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.orientasi" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Mood/Afek</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.mood" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Proses Berfikir</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.berfikir" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Persepsi</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.persepsi" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Kognisi dan Sensorium</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kognisi" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Daya nilai dan Tilikan</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.dayanilai" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Psikomotor</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.psikomotor" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Pengendalian Impuls</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.impuls" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">STATUS NEUROLOGIS</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.statusneurologis" rows="4" style="margin-top: 15px;" placeholder="Genogram">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">STATUS INTERNA</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.statusinterna" rows="4" style="margin-top: 15px;" placeholder="Genogram">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <!-- END:JIWA -->

        <!-- START:OBGYN -->
        <div class="column is-12"
          v-if="isPemeriksaanFisik && isPemeriksaanFisikLokalis == false && namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn == 'Obstetri'"
          style="margin-top: 40px; border: 1px solid #dcdedc">
          <div class="columns is-multiline">
            <div class="column is-8">
              <h1 style="font-weight: bold;">PENAPISAN MASALAH GENETIK (Termasuk pasien, suami,
                dan anggota keluarga)</h1>
            </div>
            <div class="column is-4" align="right">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="input.lokalisobgynnormal" true-value="Batas Normal"
                    label="Dalam Batas Normal" color="primary" circle
                    style="margin: 15px 0px 0px 30px; font-size: 10pt;" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Masalah Genetik</h1>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Usia ibu di atas 35 tahun</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.usiaibu" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Penyakit Haematologi</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.haematologi" :attrs="{ value }" placeholder="" label="label"
                    :options="d_mata" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Neural tube defect (Meningomylecel, Apina Bifida,
                Anencephali)</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.neural" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Down Syndrome</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.ds" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Huntington Chorea</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.huntington" :attrs="{ value }" placeholder="" label="label"
                    :options="d_mata" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Reterdasi Mental</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.reterdasi" :attrs="{ value }" placeholder="" label="label"
                    :options="d_mata" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Kelaianan Kromosom</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.kromosom" :attrs="{ value }" placeholder="" label="label"
                    :options="d_mata" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Riwayat Lahir Cacat</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.lahircacat" :attrs="{ value }" placeholder="" label="label"
                    :options="d_mata" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Riwayat Abortus pada trimester I dan KJDR</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.abortus" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Riwayat Narkoba</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.narkoba" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Lainnya</h1>
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.genetiklainnya" placeholder="" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12"
          v-if="isPemeriksaanFisik && isPemeriksaanFisikLokalis == false && namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn == 'Obstetri'"
          style="margin-top: 40px; border: 1px solid #dcdedc">
          <div class="columns is-multiline">
            <div class="column is-4" align="right">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="input.lokalisobgynnormal" true-value="Batas Normal"
                    label="Dalam Batas Normal" color="primary" circle
                    style="margin: 15px 0px 0px 30px; font-size: 10pt;" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Riwayat Infeksi</h1>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Resiko Tinggi HIV</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.hiv" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Resiko Tinggi Hepatitis B</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.hepatitisb" :attrs="{ value }" placeholder="" label="label"
                    :options="d_mata" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Tinggal bersama penderita / infeksi kronis</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.penderita" :attrs="{ value }" placeholder="" label="label"
                    :options="d_mata" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Riwayat Penyakit Menular Seksual</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.pms" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Riwayat Penyakit Menular Seksual Pasangan</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.pmspasangan" :attrs="{ value }" placeholder="" label="label"
                    :options="d_mata" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12"
          v-if="isPemeriksaanFisik && isPemeriksaanFisikLokalis == false && namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn == 'Obstetri'"
          style="margin-top: 40px; border: 1px solid #dcdedc">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 style="font-weight: bold;">Kehamilan Tidak Diinginkan</h1>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Gagal KB</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.gagalkb" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Korban Pemerkosaan</h1>
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VControl icon="">
                  <Multiselect v-model="input.korban" :attrs="{ value }" placeholder="" label="label" :options="d_mata"
                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Usia Menggugurkan</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.menggugurkan" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Lainnya</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kehamilanlainnya" placeholder="" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12"
          v-if="isPemeriksaanFisik && isPemeriksaanFisikLokalis == false && namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn == 'Obstetri'"
          style="margin-top: 40px; border: 1px solid #dcdedc">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold;" class="mb-5">Status General</h1>
              <div class="columns is-multiline mt-1" style="border: 1px solid #dcdedc">
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Kepala</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.kepalageneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Mata</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.matageneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Gigi</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.gigigeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Tiroid</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.tiroidgeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Payudara</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.payudarageneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Jantung</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.jantunggeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Paru</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.parugeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Perut</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.perutgeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Pelvic</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.pelvicgeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Tungkai Atas</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.tungkaigeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Tungkai Bawah</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.tungkaibawahgeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Kelenjar Limfe</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.limfegeneral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;" class="mb-5">Keterangan</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.keterangangeneral" rows="20">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;" class="mb-5">Pemeriksaan Luar</h1>
              <div class="columns is-multiline mt-1" style="border: 1px solid #dcdedc">
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Tinggi fundus uteri</h1>
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.tinggifundus" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Letak anak</h1>
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.letakanak" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Denyut jantung janin</h1>
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.denyutjantung" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">His</h1>
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.his" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;">Lainnya</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.luarlainnya" rows="3">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
              <h1 style="font-weight: bold;" class="mb-5">Pemeriksaan Dalam</h1>
              <div class="columns is-multiline mt-1" style="border: 1px solid #dcdedc">
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.dalamlainnya" rows="3">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12"
          v-if="isPemeriksaanFisik && isPemeriksaanFisikLokalis == false && namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn == 'Ginekologi'"
          style="margin-top: 40px;">
          <div class="columns is-multiline" style="border: 1px solid #dcdedc">
            <div class="column is-12" align="right">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="input.lokalisobgynnormal" true-value="Batas Normal"
                    label="Dalam Batas Normal" color="primary" circle
                    style="margin: 15px 0px 0px 30px; font-size: 10pt;" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;" class="mb-5">Status General</h1>
              <div class="columns is-multiline mt-1" style="border: 1px solid #dcdedc">
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Kepala</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.kepalageneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Mata</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.matageneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Gigi</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.gigigeneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Tiroid</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.tiroidgeneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Payudara</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.payudarageneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Jantung</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.jantunggeneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Paru</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.parugeneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Perut</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.perutgeneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Pelvic</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.pelvicgeneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Tungkai Atas</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.tungkaigeneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Tungkai Bawah</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.tungkaibawahgeneralgin" :attrs="{ value }" placeholder=""
                        label="label" :options="d_normal" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Kelenjar Limfe</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.limfegeneralgin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_normal" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;" class="mb-5">Keterangan</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.keterangangeneral" rows="20">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;" class="mb-5">Pemeriksaan Ginekologi</h1>
              <div class="columns is-multiline mt-1" style="border: 1px solid #dcdedc">
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Vulva</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.vulva" :attrs="{ value }" placeholder="" label="label"
                        :options="d_vulva" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Vagina</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.vagina" :attrs="{ value }" placeholder="" label="label"
                        :options="d_vagina" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Cervix</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.cervix" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cervix" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Uterus</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.uterus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_uterus" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Adnexa</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.adnexa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_adnexa" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">Rectum</h1>
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="">
                      <Multiselect v-model="input.rectum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_rectum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 style="font-weight: bold;">Lainnya</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.ginekologilainnya" rows="3">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline" style="border: 1px solid #dcdedc">
            <div class="column is-6">
              <h1 style="font-weight: bold;">Extremitas</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.extremitas" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">Rectal Toucher</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.rectal" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12"
          v-if="isPemeriksaanFisik && isPemeriksaanFisikLokalis == false && namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn == 'Obstetri'"
          style="margin-top: 40px; border: 1px solid #dcdedc">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 style="font-weight: bold;">Pemeriksaan Panggul</h1>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Promontorium</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Promontorium" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Linea Innominata</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Innominata" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Conjungata Diagonalis</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Conjungata" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Spina Ischiadica</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Spina" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Distansia Interspinosus</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Distansia" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Side Walls</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Walls" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Acrus Pubis</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Pubis" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Sacrum</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Sacrum" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Distansia Intertuberosum</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Intertuberosum" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Kesan Panggul</h1>
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.Kesan" placeholder="" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <!-- END:OBGYN -->

        <!-- START:KULIT -->
        <div class="column is-12"
          v-if="isPemeriksaanFisikLokalis == true && (namaRuanganDinamis.toUpperCase().indexOf('KULIT') > -1 || namaRuanganDinamis.toUpperCase().indexOf('COSMEDIC') > -1 || namaRuanganDinamis.toUpperCase().indexOf('SPEKTRA') > -1)"
          style="margin-top: 40px;">
          <!-- <ImgDraw elemenID="Gambar" height="680" width="900"
                        imageSrc="/images/simrs/outline-human-body.jpg" /> -->
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold;">STATUS DERMATOLOGI / VENEREOLOGI</h1>
            </div>
            <div class="column is-8" align="right">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="input.lokaliskulit" true-value="Batas Normal"
                    @click="normallokaliskulit" label="Dalam Batas Normal" color="primary" circle
                    style="margin: 15px 0px 0px 30px; font-size: 10pt;" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline" style="margin-top: 20px;">
            <div class="column is-4">
              <h1 style="font-weight: bold;">Lokasi<h1 style="font-weight: bold; font-size: 7pt;">
                  (Jabarkan lokasi sesuai
                  dengan regio anatomis)</h1>
              </h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.lokasiKulit" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Bentuk Kelainan Kulit<h1 style="font-weight: bold; font-size: 7pt;">
                  (Eflorisensi)</h1>
              </h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.kelainanKulit" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Stigmata Atopi<h1 style="font-weight: bold; font-size: 7pt;">
                  (Pit. Alba,
                  Ikhtiosis, Keratosis, Denie-Morgagni, dll)</h1>
              </h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.stigmata" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Mukosa</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.mukosa" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Rambut</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.rambut" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Kuku</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.kuku" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Fungsi Kelenjar Keringat<h1 style="font-weight: bold; font-size: 7pt;">
                  (Hiperhidrosis, Anhidrosis)</h1>
              </h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.kelenjarKeringat" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Kelenjar Limfe</h1>
              <VField style="margin-top: 30px;">
                <VControl>
                  <VTextarea v-model="input.kelenjarLimfe" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Saraf<h1 style="font-weight: bold; font-size: 7pt;">
                  (Penebalan saraf perfer,
                  parestesi, makula-anestesi)</h1>
              </h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.saraf" rows="3" style="margin-top: 15px;" placeholder="">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START ANAK -->

        <div class="column is-12"
          v-if="isPemeriksaanFisikLokalis == true && (namaRuanganDinamis.toUpperCase().indexOf('ANAK') > -1 || namaRuanganDinamis.toUpperCase().indexOf('KEMBANG') > -1)"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 style="font-weight: bold;">Pemeriksaan Khusus</h1>
            </div>
            <div class="column is-12">
              <VField>
                <VControl>
                  <VTextarea v-model="input.pemeriksaankhususanak" rows="10"></VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START REHAB MEDIK -->

        <div class="column is-12 mt-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('REHAB') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Postur</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.posturrehab" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Ambulansi</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.ambulansirehab" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Penilaian Nyeri</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.penilaiannyerirehab" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Resiko Jatuh</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.resikojatuhrehab" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">Status Musculoskeletal dan Neuromuscular</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.statusmn" rows="6">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">Pemeriksaan Khusus Fisik Lain</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.pfrehab" rows="6">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Balance</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.balancerehab" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Coordlation</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.coordlationrehab" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Transfer</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.transferrehab" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Pemeriksaan Fungsional</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.pfungrehab" placeholder="" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START INTERNA -->

        <div class="column is-12"
          v-if="isPemeriksaanFisikLokalis == true && (((namaRuanganDinamis.toUpperCase().indexOf('INTERNA') > -1 || namaRuanganDinamis.toUpperCase().indexOf('MEDICAL') > -1) && jenisInterna == 'Obstetri') || namaRuanganDinamis.toUpperCase().indexOf('GERIATRI') > -1)"
          style="margin-top: -20px;">
          <div class="columns is-multiline mt-5">

            <div class="column is-12">
              <div class="columns is-multiline" style="padding: 10px; padding-top: 0px">
                <div class="columns is-multiline column">
                  <div class="column is-12" style="font-weight: bold">1. PENAPISAN STATUS FUNGSIONAL
                    (ACTIVITY DAILY
                    LIVING BARTHEL INDEX)</div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <table class="tg">
                      <thead>
                        <tr>
                          <th>Aspek</th>
                          <th>Sebelum MRS</th>
                          <th>Saat MRS</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(data, index) in ListPSF" :key="index">
                          <td style="text-align:start !important;vertical-align:middle !important">{{ data.caption }}
                          </td>
                          <td style="text-align:center !important;vertical-align:middle !important">
                            <VControl>
                              <VInput type="text" class="input" v-model="input['TBsebelumMRS_' + index]" />
                            </VControl>
                          </td>
                          <td style="text-align:center !important;vertical-align:middle !important">
                            <VControl>
                              <VInput type="text" class="input" v-model="input['TBsaatMRS_' + index]" />
                            </VControl>
                          </td>
                          <td v-if="index === 0" :rowspan="11" style="font-weight:bold">
                            Mandiri (20)<br>
                            Ketergantungan ringan (12-19)<br>
                            Ketergantungan sedang (9-11)<br>
                            Ketergantungan berat (5-8)<br>
                            Ketergantungan total (0-4)
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="column is-12" style="font-weight: bold">2. PENAPISAN SINDROM DELIRIUM
                    (CONFUSION ASSESSMENT
                    METHOD)</div>
                  <div class="column is-12 columns is-multiline" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-3">1. Onset akut dan fluktuatif</div>
                    <div class="column is-3 columns">
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBOnsetAkutdanFluktuatif" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBOnsetAkutdanFluktuatif" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-3">3. Pikiran tidak terorganisir</div>
                    <div class="column is-3 columns">
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBPikiranTidakTerorganisir" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBPikiranTidakTerorganisir" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-3">2. Inatensi</div>
                    <div class="column is-3 columns">
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBInatensi" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBInatensi" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-3">4. Pikiran tidak terorganisir</div>
                    <div class="column is-3 columns">
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBPikiranTidakTerorganisir2" />
                        </VControl>
                      </div>
                      <div class="column is-6">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBPikiranTidakTerorganisir2" />
                        </VControl>
                      </div>
                    </div>

                    <div class="column is-12 columns">
                      <div class="column is-3">Delirium</div>
                      <div class="column is-8">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya"
                            label="Ya (poin 1 dan 2 plus salah satu dari 3 atau 4)" v-model="input.CBDelirium" />
                        </VControl>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">3. PENILAIAN STATUS NUTRISI
                    (MINI NUTRITIONAL
                    ASSESSMENT)</div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <div class="columns is-multiline">
                      <div class="column is-12">1. IMT (Kg/M<sup>2</sup>)</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) < 19" label="(0) < 19"
                              v-model="input.CBimt" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) 19-21" label="(1) 19-21"
                              v-model="input.CBimt" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) 21-23" label="(2) 21-23"
                              v-model="input.CBimt" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(3) >23" label="(3) >23"
                              v-model="input.CBimt" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">2. Lingkar lengan atas (Cm)</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) <21" label="(0) <21"
                              v-model="input.CBlingkarLenganAtas" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0,5) 21-22" label="(0,5) 21-22"
                              v-model="input.CBlingkarLenganAtas" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) >22" label="(1) >22"
                              v-model="input.CBlingkarLenganAtas" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">3. Lingkar betis (Cm)</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) ≤31" label="(0) ≤31"
                              v-model="input.CBlingkarBetis" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) >31" label="(1) >31"
                              v-model="input.CBlingkarBetis" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">4. BB selama 3 bulan terakhir</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Kehilangan > 3kg"
                              label="(0) Kehilangan > 3kg" v-model="input.CBbbSelama3bulan" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Tidak tahu"
                              label="(1) Tidak tahu" v-model="input.CBbbSelama3bulan" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) Kehilangan antara 1-3kg"
                              label="(2) Kehilangan antara 1-3kg" v-model="input.CBbbSelama3bulan" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(3) Tidak kehilangan BB"
                              label="(3) Tidak kehilangan BB" v-model="input.CBbbSelama3bulan" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">5. Hidup tidak tergantung (tidak di tempat
                        perawatan/RS)</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Ya" label="(0) Ya"
                              v-model="input.CBhidupTidakTergantung" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Tidak" label="(1) Tidak"
                              v-model="input.CBhidupTidakTergantung" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">6. Menggunakan lebih dari 3 jenis obat per hari
                      </div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Ya" label="(0) Ya"
                              v-model="input.CBmenggunakanLebihDari3JenisObat" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Tidak" label="(1) Tidak"
                              v-model="input.CBmenggunakanLebihDari3JenisObat" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">7. Mengalami stress psikologis atau penyakit akut
                        dalam 3 bulan
                        terakhir</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Ya" label="(0) Ya"
                              v-model="input.CBmengalamiStressPsikologis" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Tidak" label="(1) Tidak"
                              v-model="input.CBmengalamiStressPsikologis" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">8. Mobilitas</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square
                              true-value="(0) Hanya terbaring/di atas kursi roda"
                              label="(0) Hanya terbaring/di atas kursi roda" v-model="input.CBmobilitas" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square
                              true-value="(1) Bisa bangkit dari tempat tidur tapi tidak keluar rumah"
                              label="(1) Bisa bangkit dari tempat tidur tapi tidak keluar rumah"
                              v-model="input.CBmobilitas" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) Bisa keluar rumah"
                              label="(2) Bisa keluar rumah" v-model="input.CBmobilitas" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">9. Masalah neuropsikologis</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Demensia berat dan depresi"
                              label="(0) Demensia berat dan depresi" v-model="input.CBmasalahNeuropsikologis" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Demensia ringan"
                              label="(1) Demensia ringan" v-model="input.CBmasalahNeuropsikologis" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) Tidak ada masalah psikologis"
                              label="(2) Tidak ada masalah psikologis" v-model="input.CBmasalahNeuropsikologis" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">10. Nyeri tekan/luka kulit</div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Ya" label="(0) Ya"
                              v-model="input.CBnyeriTekanLukaKulit" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Tidak" label="(1) Tidak"
                              v-model="input.CBnyeriTekanLukaKulit" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">11. Jumlah daging yang dikonsumsi setiap hari
                      </div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) 1x makan" label="(0) 1x makan"
                              v-model="input.CBjumlahDagingYangDikonsumsi" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) 2x makan" label="(1) 2x makan"
                              v-model="input.CBjumlahDagingYangDikonsumsi" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) 3x makan" label="(2) 3x makan"
                              v-model="input.CBjumlahDagingYangDikonsumsi" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">12. Asupan protein terpilih</div>
                      <div class="column is-12" style="margin-left:10px">
                        <div class="columns">
                          <div class="column is-6">
                            a. Minimal 1x penyajian produk susu olahan per hari
                          </div>
                          <div class="column is-3">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square true-value="(0) Tidak" label="(0) Tidak"
                                v-model="input.CBminimal1xPenyajian" />
                            </VControl>
                          </div>
                          <div class="column is-3">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square true-value="(1) Ya" label="(1) Ya"
                                v-model="input.CBminimal1xPenyajian" />
                            </VControl>
                          </div>
                        </div>
                        <div class="columns">
                          <div class="column is-6">
                            b. Dua atau lebih penyajian produk kacang-kacangan dan telur per
                            minggu
                          </div>
                          <div class="column is-3">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square true-value="(0) Tidak" label="(0) Tidak"
                                v-model="input.CBduaAtauLebihPenyajian" />
                            </VControl>
                          </div>
                          <div class="column is-3">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square true-value="(1) Ya" label="(1) Ya"
                                v-model="input.CBduaAtauLebihPenyajian" />
                            </VControl>
                          </div>
                        </div>
                        <div class="columns">
                          <div class="column is-6">
                            c. Daging, ikan, unggas tiap hari
                          </div>
                          <div class="column is-3">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square true-value="(0) Tidak" label="(0) Tidak"
                                v-model="input.CBdagingIkanUnggas" />
                            </VControl>
                          </div>
                          <div class="column is-3">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square true-value="(1) Ya" label="(1) Ya"
                                v-model="input.CBdagingIkanUnggas" />
                            </VControl>
                          </div>
                        </div>
                      </div>

                      <div class="column is-12">13. Konsumsi 2 atau lebih penyajian sayur atau
                        buah-buahan per hari
                      </div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Tidak" label="(0) Tidak"
                              v-model="input.CBKonsumsi2ataulebihPenyajianSayur" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Ya" label="(1) Ya"
                              v-model="input.CBKonsumsi2ataulebihPenyajianSayur" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">14. Asupan makanan dalam 3 bulan terakhir
                        (kehilangan nafsu makan)
                      </div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Berat" label="(0) Berat"
                              v-model="input.CBAsupanMakananDalam3BulanTerakhir" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Sedang" label="(1) Sedang"
                              v-model="input.CBAsupanMakananDalam3BulanTerakhir" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) Ringan" label="(2) Ringan"
                              v-model="input.CBAsupanMakananDalam3BulanTerakhir" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">15. Jumlah cairan yang dikonsumsi per hari
                      </div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) <3 cangkir"
                              label="(0) <3 cangkir" v-model="input.CBJumlahCairanYangDikonsumsi" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0,5) 3-5 cangkir"
                              label="(0,5) 3-5 cangkir" v-model="input.CBJumlahCairanYangDikonsumsi" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) >5 cangkir"
                              label="(1) >5 cangkir" v-model="input.CBJumlahCairanYangDikonsumsi" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">16. Pola makan
                      </div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square
                              true-value="(0) Tidak bisa makan tanpa bantuan" label="(0) Tidak bisa makan tanpa bantuan"
                              v-model="input.CBpolaMakan" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square
                              true-value="(1) Makan sendiri dengan sedikit kesulitan"
                              label="(1) Makan sendiri dengan sedikit kesulitan" v-model="input.CBpolaMakan" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) Makan sendiri tanpa kesulitan"
                              label="(2) Makan sendiri tanpa kesulitan" v-model="input.CBpolaMakan" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">17. Apakah pasien merasakan memiliki masalah gizi
                      </div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) Malntrisi"
                              label="(0) Malntrisi" v-model="input.CBapakahPasienMerasakanMasalahGizi" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Tidak tahu/malnutrisi sedang"
                              label="(1) Tidak tahu/malnutrisi sedang"
                              v-model="input.CBapakahPasienMerasakanMasalahGizi" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) Tidak ada masalah gizi"
                              label="(2) Tidak ada masalah gizi" v-model="input.CBapakahPasienMerasakanMasalahGizi" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12">18. Penilaian pasien terhadap kesehatannya bila
                        dibandingkan dengan
                        kelompok usia yang sama
                      </div>
                      <div class="columns column is-12">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0) tidak baik"
                              label="(0) tidak baik" v-model="input.CBpenilaianPasienTerhadapKesehatannya" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(0,5) Tidak tahu"
                              label="(0,5) Tidak tahu" v-model="input.CBpenilaianPasienTerhadapKesehatannya" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(1) Sama baik"
                              label="(1) Sama baik" v-model="input.CBpenilaianPasienTerhadapKesehatannya" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="(2) Lebih baik"
                              label="(2) Lebih baik" v-model="input.CBpenilaianPasienTerhadapKesehatannya" />
                          </VControl>
                        </div>
                      </div>

                      <div class="column is-12 columns is-multiline" style="margin-top: 15px">
                        <div class="column is-4">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Normal (Skor Penapisan ≥24)"
                              label="Normal (Skor Penapisan ≥24)" v-model="input.CBtotalSkorPenilaianStatusNutrisi" />
                          </VControl>
                        </div>
                        <div class="column is-4">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square
                              true-value="Berisiko Malnutrisi (Skor Pengkajian 17-23,5)  "
                              label="Berisiko Malnutrisi (Skor Pengkajian 17-23,5)"
                              v-model="input.CBtotalSkorPenilaianStatusNutrisi" />
                          </VControl>
                        </div>
                        <div class="column is-4">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Malnutrisi (Skor Pengkajian <17)"
                              label="Malnutrisi (Skor Pengkajian <17)"
                              v-model="input.CBtotalSkorPenilaianStatusNutrisi" />
                          </VControl>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">4. PENAPISAN KOGNITIF (MI NI
                    MENTAL STATE
                    EXAMINATION)</div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-12">
                      <label style="font-weight:bold">1. ORIENTASI</label>
                      <p>(5) Sekarang (hari),(tanggal),(bulan),(tahun) berapa,(musim) apa?</p>
                      <p>(5) Sekarang kita berada di mana ? (jalan),(nomor
                        rumah),(kota),(kabupaten),(propinsi)</p>
                    </div>
                    <div class="column is-12">
                      <label style="font-weight:bold">2. REGISTRASI</label>
                      <p>(3) Pasien diminta untuk mengulang tiga kata yang disebutkan oleh
                        pemeriksa (bola, kursi,
                        sepatu)</p>
                      <div class="column is-12">
                        <VField label="Jumlah Percobaan :">
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBjumlahPercobaan" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="column is-12">
                      <label style="font-weight:bold">3. ATENSI dan KALKULASI</label>
                      <p>(5) Hitunglah berturut-turut selang 7 mulai dari 100 ke bawah. Berilah 1
                        angka untuk tiap
                        jawaban yang benar. Berhenti setelah 5 hitungan
                        (93,86,79,72,65). Kemungkinan lain, ejalah kata “dunia” dari akhir ke
                        awal (a-i-n-u-d)</p>
                    </div>
                    <div class="column is-12">
                      <label style="font-weight:bold">4. MENGINGAT</label>
                      <p>(3) Tanyalah kembali nama ke 3 benda yang telah disebutkan di atas.
                        Berilah 1 angka untuk
                        tiap jawaban yang benar.</p>
                    </div>
                    <div class="column is-12">
                      <label style="font-weight:bold">5. BAHASA</label>
                      <p>(2) Apakah nama benda-benda ini? Perlihatkan pensil dan arloji</p>
                      <p>(1) Ulanglah kalimat berikut : “ Jika tidak, dan Atau Tapi ”.</p>
                      <p>(3) Laksanakan 3 buah perintah ini : “ Peganglah selembar kertas dengan
                        tangan kananmu,
                        lipatlah kertas itu pada pertengahan dan letakkanlah di lantai”.</p>
                      <p>(1) Bacalah dan laksanakan perintah berikut “PEJAMKAN MATA ANDA”</p>
                      <p>(1) Tulislah sebuah kalimat</p>
                      <p>(1) Tirulah gambar ini (di samping gambar tersebut)</p>
                    </div>
                    <div class="column is-12">
                      <img src="/images/simrs/AAM_Geriatri.png">
                    </div>
                    <div class="column is-12 columns">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Normal (25 – 30)"
                            label="Normal (25 – 30)" v-model="input.CBSkorPenapisanKognitif" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square
                            true-value="Gangguan Kognitif ringan (MCI) (20 – 25)"
                            label="Gangguan Kognitif ringan (MCI) (20 – 25)" v-model="input.CBSkorPenapisanKognitif" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Gangguan kognitif pasti (< 20)"
                            label="Gangguan kognitif pasti (< 20)" v-model="input.CBSkorPenapisanKognitif" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak dapat dievaluasi"
                            label="Tidak dapat dievaluasi" v-model="input.CBSkorPenapisanKognitif" />
                        </VControl>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">5. PENAPISAN DEPRESI(GERIATRIC
                    DEPRESSION SCALE)
                  </div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <table class="tg">
                      <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th width="80%">Deskripsi</th>
                          <th width="7%">(0)</th>
                          <th width="7%">(1)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(data, index) in ListPenapisanDepresi" :key="index">
                          <td style="text-align:center">{{ index + 1 }}</td>
                          <td>{{ data.caption }}</td>
                          <td style="text-align:center">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.nilai0"
                                :label="data.nilai0" v-model="input['CBnilai0_' + index]" />
                            </VControl>
                          </td>
                          <td style="text-align:center">
                            <VControl raw subcontrol>
                              <VCheckbox class="p-0" color="primary" square :true-value="data.nilai1"
                                :label="data.nilai1" v-model="input['CBnilai1_' + index]" />
                            </VControl>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="column is-12 columns">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Normal (0-9)" label="Normal (0-9)"
                            v-model="input.CBnormalPenapisanDepresi" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Depresi Ringan (10 – 19)"
                            label="Depresi Ringan (10 – 19)" v-model="input.CBdepresiRinganPenapisanDepresi" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Depresi berat (20 – 30)"
                            label="Depresi berat (20 – 30)" v-model="input.CBdepresiBeratPenapisanDepresi" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak dapat dievaluasi"
                            label="Tidak dapat dievaluasi" v-model="input.CBtidakDapatDievaluasiPenapisanDepresi" />
                        </VControl>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">6. PENAPISAN INKONTINENSIA
                  </div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-12" v-for="(data, index) in listPI" :key="index">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="data.caption" :label="data.caption"
                          v-model="input['CBPI_' + index]" />
                      </VControl>
                    </div>
                    <div class="column is-12 columns" style="font-weight:bold;margin-top:10px">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak ada inkontinensia (0)"
                            label="Tidak ada inkontinensia (0)" v-model="input.CBtotalSkorPI" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Inkontinensia ringan (1 – 2.5)"
                            label="Inkontinensia ringan (1 – 2.5)" v-model="input.CBtotalSkorPI" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="I. sedang (4 – 6.5)"
                            label="I. sedang (4 – 6.5)" v-model="input.CBtotalSkorPI" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="I. berat (≥ 8)"
                            label="I. berat (≥ 8)" v-model="input.CBtotalSkorPI" />
                        </VControl>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">7. PENAPISAN DEEP VEIN
                    THROMBOSIS (WELLS SCORE
                    SYSTEM)
                  </div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-12" v-for="(data, index) in listPDVT" :key="index">
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="data.caption" :label="data.caption"
                          v-model="input['CBPDVT_' + index]" :value="data.value" @change="calculateTotalSkorPDVT" />
                      </VControl>
                    </div>
                    <div class="column is-12">
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBtotalSkorPDVT" disabled />
                      </VControl>
                    </div>
                    <div class="column is-12 columns" style="font-weight:bold;margin-top:10px">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Risiko rendah (< 1)"
                            label="Risiko rendah (< 1)" v-model="input.CBtotalSkorPDVT" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Risiko sedang (1 – 2)"
                            label="Risiko sedang (1 – 2)" v-model="input.CBtotalSkorPDVT" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Risiko tinggi (> 3)"
                            label="Risiko tinggi (> 3)" v-model="input.CBtotalSkorPDVT" />
                        </VControl>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">8. ULKUS DEKUBITUS
                  </div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <p>Tidak ada Ada (dilanjutkan dengan klarifikasi She)</p>
                    <p>Stadium I : Eritema nonblanchable pada kulit yang masih utuh atau perubahan
                      warna kulit yang
                      hangat, edema, dan berindurasi pada pasien dengan
                      kulit gelap</p>
                    <p>Stadium II : Sudah terjadi kehilangan lapisan kulit epidermis dan/atau dermis
                    </p>
                    <p>Stadium III: Ulkus sudah berkembang ke jaringan lunak dan ke lapisan fasia
                      dalam</p>
                    <p>Stadium IV : Jaringan otot dan tulang sudah terlibat</p>
                  </div>

                  <div class="column is-12" style="font-weight: bold">9. PENAPISAN INSOMNIA (INSOMNIA
                    SEVERITY INDEX)
                  </div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-12 columns is-multiline" v-for="(data, index) in listPenapisanInsomnia"
                      :key="index">
                      <div class="column is-12">{{ data.caption }}</div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square :true-value="data.cb0" :label="data.cb0"
                            v-model="input['CB0PenapisanInsomnia_' + index]" />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square :true-value="data.cb1" :label="data.cb1"
                            v-model="input['CB1PenapisanInsomnia_' + index]" />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square :true-value="data.cb2" :label="data.cb2"
                            v-model="input['CB2PenapisanInsomnia_' + index]" />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square :true-value="data.cb3" :label="data.cb3"
                            v-model="input['CB3PenapisanInsomnia_' + index]" />
                        </VControl>
                      </div>
                      <div class="column is-2">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square :true-value="data.cb4" :label="data.cb4"
                            v-model="input['CB4PenapisanInsomnia_' + index]" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-12 columns" style="font-weight:bold;margin-top:10px">
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak Insomnia (0-7)"
                            label="Tidak Insomnia (0-7)" v-model="input.CBSkorPenapisanInsomnia" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Borderline Insomnia (8-14))"
                            label="Borderline Insomnia (8-14))" v-model="input.CBSkorPenapisanInsomnia" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Insomnia Sedang (15-21)"
                            label="Insomnia Sedang (15-21)" v-model="input.CBSkorPenapisanInsomnia" />
                        </VControl>
                      </div>
                      <div class="column is-3">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Insomnia Berat (22-28)"
                            label="Insomnia Berat (22-28)" v-model="input.CBSkorPenapisanInsomnia" />
                        </VControl>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">10. IDENTIFIKASI FALLS DAN
                    RISIKO JATUH (SKALA
                    MORSE)
                  </div>
                  <div class="column is-12" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-12 columns">
                      <div class="column is-3">1. Falls :</div>
                      <div class="column is-9 columns">
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="≥ 3 kali" label="≥ 3 kali"
                              v-model="input.CBFallsIFRJ" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="1-2 kali" label="1-2 kali"
                              v-model="input.CBFallsIFRJ" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="saat ini" label="saat ini"
                              v-model="input.CBFallsIFRJ" />
                          </VControl>
                        </div>
                        <div class="column is-3">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="tidak pernah" label="tidak pernah"
                              v-model="input.CBFallsIFRJ" />
                          </VControl>
                        </div>
                      </div>
                    </div>
                    <div class="column is-12 columns">
                      <div class="column is-3">2. Total skor skala Morse :</div>
                      <div class="column is-9 columns">
                        <div class="column is-4">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Risiko rendah (0-7)"
                              label="Risiko rendah (0-7)" v-model="input.CBTotalSkorMorseIFRJ" />
                          </VControl>
                        </div>
                        <div class="column is-4">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Risiko tinggi (8-13)"
                              label="Risiko tinggi (8-13)" v-model="input.CBTotalSkorMorseIFRJ" />
                          </VControl>
                        </div>
                        <div class="column is-4">
                          <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="Risiko sangat tinggi (≥14)"
                              label="Risiko sangat tinggi (≥14)" v-model="input.CBTotalSkorMorseIFRJ" />
                          </VControl>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">11. IDENTIFIKASI FRAILTY
                  </div>
                  <div class="column is-12 columns" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-6">
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square
                            true-value="Penurunan berat badan yang progresif (1)"
                            label="Penurunan berat badan yang progresif (1)" v-model="input.CBPenurunanbbIF" />
                        </VControl>
                      </div>
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Energi dan endurance yang lemah (1)"
                            label="Energi dan endurance yang lemah (1)" v-model="input.CBEnergidanEnduranceIF" />
                        </VControl>
                      </div>
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Kecepatan berjalan melambat (1)"
                            label="Kecepatan berjalan melambat (1)" v-model="input.CBKecepatanBerjalanMelambatIF" />
                        </VControl>
                      </div>
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square
                            true-value="Keletihan atau daya tahan menurun (1)"
                            label="Keletihan atau daya tahan menurun (1)"
                            v-model="input.CBKeletihanatauDayatahanMenurunIF" />
                        </VControl>
                      </div>
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square
                            true-value="Tingkat aktivitas fisik yang rendah (1)"
                            label="Tingkat aktivitas fisik yang rendah (1)"
                            v-model="input.CBTingkatAktivitasFisikyangRendahIF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-6 columns">
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Non frail (0)" label="Non frail (0)"
                            v-model="input.CBifSkor" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Pre frail (1-2)"
                            label="Pre frail (1-2)" v-model="input.CBifSkor" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Frailty (≥3)" label="Frailty (≥3)"
                            v-model="input.CBifSkor" />
                        </VControl>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">12. IDENTIFIKASI FAILURE TO
                    THRIVE
                  </div>
                  <div class="column is-12 columns" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-6">
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square
                            true-value="Penurunan berat badan >5% dari berat badan awal (1)"
                            label="Penurunan berat badan >5% dari berat badan awal (1)"
                            v-model="input.CBPenurunanbbIFTT" />
                        </VControl>
                      </div>
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Penurunan nafsu makan (1)"
                            label="Penurunan nafsu makan (1)" v-model="input.CBPenurunanNafsuIFTT" />
                        </VControl>
                      </div>
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Malnutrisi (1)"
                            label="Malnutrisi (1)" v-model="input.CBMalnutrisiIFTT" />
                        </VControl>
                      </div>
                      <div class="column">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Imobilitas (1)"
                            label="Imobilitas (1)" v-model="input.CBImobilitasIFTT" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-6 columns">
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak (<4)" label="Tidak (<4)"
                            v-model="input.CBifttSkor" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="FailureTo Thrive (4)"
                            label="FailureTo Thrive (4)" v-model="input.CBifttSkor" />
                        </VControl>
                      </div>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">13. IDENTIFIKASI RISIKO FRAKTUR
                    (FRAX)
                  </div>
                  <div class="column is-12 columns is-multiline" style="margin-left: 10px;margin-top:-5px">
                    <div class="columns column is-12">
                      <div class="column is-4">1. Usia</div>
                      <div class="column is-4">
                        <VField addons>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBUsiaIRF" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Tahun</VButton>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">2. Jenis kelamin</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Pria" label="Pria"
                            v-model="input.CBjenisKelaminIRF" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Wanita" label="Wanita"
                            v-model="input.CBjenisKelaminIRF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">3. Berat badan</div>
                      <div class="column is-4">
                        <VField addons>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBberatBadanIRF" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Kg</VButton>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">4. Tinggi badan</div>
                      <div class="column is-4">
                        <VField addons>
                          <VControl>
                            <VInput type="text" class="input" v-model="input.TBtinggiBadanIRF" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>cm</VButton>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">5. Riwayat patah tulang</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBriwayatPatahTulangIRF" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBriwayatPatahTulangIRF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">6. Riwayat patah tulang femur pada orang tua</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBriwayatPatahTulangFemurIRF" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBriwayatPatahTulangFemurIRF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">7. Perokok</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBperokokIRF" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBperokokIRF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">8. Glukokortikoid</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBglukokortikoidIRF" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBglukokortikoidIRF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">9. Artritis rheumatoid</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBartritisRheumatoidIRF" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBartritisRheumatoidIRF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">10. Osteoporosis sekunder</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBOsteoporosisSekunderIRF" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBOsteoporosisSekunderIRF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="columns column is-12">
                      <div class="column is-4">11. Alkohol 3 unit atau lebih per hari</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBAlkohol3UnitIRF" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBAlkohol3UnitIRF" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-12">
                      <Fieldset :toggleable="true" legend="Nilai FRAX" style="margin-bottom: 10px">
                        <div class="columns is-12">
                          <div class="column is-6">
                            <label style="font-weight: bold">Osteoporosis Mayor :</label>
                            <p>a. Risiko berat (≥20%)</p>
                            <p>b. Risiko sedang (10-20%)</p>
                            <p>c. Risiko ringan (&lt;10%)</p>
                          </div>
                          <div class="column is-6">
                            <label style="font-weight: bold">Hip fracture :</label>
                            <p>a. Risiko berat (≥3%)</p>
                            <p>b. Risiko sedang (1,5-3%)</p>
                            <p>c. Risiko ringan (&lt;1,5%)</p>
                          </div>
                        </div>
                      </Fieldset>
                    </div>
                  </div>

                  <div class="column is-12" style="font-weight: bold">14. IMPAIRMENT LAINNYA
                  </div>
                  <div class="column is-12 columns is-multiline" style="margin-left: 10px;margin-top:-5px">
                    <div class="column is-12 columns">
                      <div class="column is-4">1. Impair of vision</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBimpairOFvision" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBimpairOFvision" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-12 columns">
                      <div class="column is-4">2. Impair of hearing</div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                            v-model="input.CBimpairOFhearing" />
                        </VControl>
                      </div>
                      <div class="column is-4">
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                            v-model="input.CBimpairOFhearing" />
                        </VControl>
                      </div>
                    </div>
                    <div class="column is-12">
                      <VField label="Lain-lain">
                        <VTextarea rows="2" v-model="input.TAlainlainIL"></VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- START PSIKOLOGI KLINIS -->

        <div class="column is-12"
          v-if="isPemeriksaanFisik == true && namaRuanganDinamis.toUpperCase().indexOf('PSIKOLOGI') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 style="font-weight: bold;">A. DATA AWAL</h1>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Rujukan</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="YA" label="Ya"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan" true-value="TIDAK" label="Tidak"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 pt-0" v-if="input.kebrujukan == 'YA'">
              <h1 class="bold">Dari</h1>
              <VField>
                <VControl>
                  <VInput type="text" class="heightinput input" placeholder="Keterangan Rujukan"
                    v-model.number="input.TBKetRujukanDari" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">B. STATUS PSIKOLOGIS</h1>
            </div>
            <div class="column is-12" style="font-weight: bold;">OBSERVASI</div>
            <div class="column is-3">
              <VField label="Penampilan">
                <VTextarea rows="2" v-model="input.TApenampilanSP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Ekspresi wajah">
                <VTextarea rows="2" v-model="input.TAExpresiSP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Perasaan (Mood)">
                <VTextarea rows="2" v-model="input.TAperasaanSP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Fungsi Umum">
                <VTextarea rows="2" v-model="input.TAfungsiUmumSP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Intelektual">
                <VTextarea rows="2" v-model="input.TAintelektualSP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Lain-lain">
                <VTextarea rows="2" v-model="input.TAlainlainSP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Isi Pikir">
                <VTextarea rows="2" v-model="input.TAisiPikirSP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Afeksi">
                <VTextarea rows="2" v-model="input.TAafeksiSP"></VTextarea>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">C. TES PSIKOLOGIS</h1>
            </div>
            <div class="column is-3">
              <VField>
                <label>Tes Psikologi yang diberikan :</label>
                <VTextarea rows="2" v-model="input.TAtpydTP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <label>Hasil Tes Psikologis :</label>
                <VTextarea rows="2" v-model="input.TAExpresiTP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <label>Target Intervensi :</label>
                <VTextarea rows="2" v-model="input.TAperasaanTP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <label>Intervensi yang dilakukan sekarang :</label>
                <VTextarea rows="2" v-model="input.TAfungsiUmumTP"></VTextarea>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <label>Intervensi Selanjutnya :</label>
                <VTextarea rows="2" v-model="input.TAintelektualTP"></VTextarea>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">E. PROSES PSIKOTERAPI / KONSELING</h1>
            </div>
            <div class="column is-12">
              <VField>
                <VTextarea rows="2" v-model="input.TAppk"></VTextarea>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">F. PROGNOSIS</h1>
            </div>
            <div class="column is-12">
              <VField>
                <VTextarea rows="2" v-model="input.TAprognosis"></VTextarea>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">G. TINDAK LANJUT</h1>
            </div>
            <div class="column is-4">
              <VField label="Pertemuan Selanjutnya :">
                <VTextarea rows="2" v-model="input.TAps"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Dirujuk kepada :">
                <VTextarea rows="2" v-model="input.TAdk"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Diakhiri tanggal :">
                <VDatePicker v-model="input.DdiakhiriTanggal" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
        </div>

        <!-- START ANASTESI -->
        <div class="column is-12 mt-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('ANESTESI') > -1">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold;">Jalan nafas/gigi geligi/leher</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.jalannafas" placeholder="" />
                </VControl>
              </VField>
            </div>
            <!-- <div class="column is-6"></div> -->
            <div class="column is-6">
              <h1 style="font-weight: bold;">Rencana Tindakan</h1>
              <VField>
                <VTextarea rows="6" v-model="input.rencanatindakan"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">Riwayat Kesehatan / Penyakit</h1>
              <VField>
                <VTextarea rows="6" v-model="input.riwayatkesehatan"></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-0 mt-5">
              <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Alergi Obat</h1>
              <div v-if="input.alergianestesi == 'YA'">
                <h1>Sebutkan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.alergianestesiText" :disabled="paramRiwayat" />
                </VControl>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.alergianestesi" true-value="YA" label="Ya"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.alergianestesi" true-value="TIDAK"
                    label="Tidak" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Alergi Makanan</h1>
              <div v-if="input.makanananestesi == 'YA'">
                <h1>Sebutkan</h1>
                <VControl>
                  <VInput type="text" class="input" v-model="input.makanananestesiText" :disabled="paramRiwayat" />
                </VControl>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.makanananestesi" true-value="YA" label="Ya"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.makanananestesi" true-value="TIDAK"
                    label="Tidak" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Operasi / anastesi sebelumnya</h1>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.anestesisebelumnya" true-value="YA"
                    label="Ya" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.anestesisebelumnya" true-value="TIDAK"
                    label="Tidak" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Sedang mengkonsumsi obat</h1>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mengkonsumsiobat" true-value="YA" label="Ya"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mengkonsumsiobat" true-value="TIDAK"
                    label="Tidak" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Riwayat anasthesi dan komplikasi</h1>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.riwayatkomplikasi" true-value="YA" label="Ya"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.riwayatkomplikasi" true-value="TIDAK"
                    label="Tidak" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1 style="font-weight: bold;">Kebiasaan</h1>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.merokok" true-value="Merokok" label="Merokok"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.alkohol" true-value="Alkohol"
                    label="Minum Alkohol" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 pt-0 mt-5">
              <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Respiratory</h1>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.asthma" true-value="Asthma"
                        label="Asthma" color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.bronchitis" true-value="Bronchitis"
                        label="Bronchitis" color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.recent" true-value="Recent URI"
                        label="Recent URI" color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.copd" true-value="COPD" label="COPD"
                        color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.sob" true-value="SOB" label="SOB"
                        color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.dyspepsia" true-value="Duspepsia"
                        label="Duspepsia" color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.wnlrespiratory" true-value="WNLRespiratory"
                    label="WNL" color="primary" @click="respiratory" />
                </VControl>
              </VField>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tuberculosis" true-value="Tuberculosis"
                        label="Tuberculosis" color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.arthopnea" true-value="Arthopnea"
                        label="Arthopnea" color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.pneumonia" true-value="Pneumonia"
                        label="Pneumonia" color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.productive" true-value="Productive Cough"
                        label="Productive Cough" color="primary" :disabled="disrespiratory" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-1"></div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Cardiovascular</h1>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hypertensi" true-value="Hypertensi"
                        label="Hypertensi" color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.angina" true-value="Angina"
                        label="Angina" color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mi" true-value="MI" label="MI"
                        color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.ashd" true-value="ASHD" label="ASHD"
                        color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.murmur" true-value="Murmur"
                        label="Murmur" color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.chf" true-value="CHF" label="CHF"
                        color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.wnlcardiovascular"
                    true-value="WNLCardiovascular" label="WNL" color="primary" :disabled="discardiovascular" />
                </VControl>
              </VField>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.pacemaker" true-value="Pacemaker"
                        label="Pacemaker" color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.dysrhythmia" true-value="Dysrhythmia"
                        label="Dysrhythmia" color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.rheumatic" true-value="Rheumatic"
                        label="Rheumatic Fever" color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.abnormal" true-value="Abnormal"
                        label="Abnormal EKG" color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.exercise" true-value="Exercise"
                        label="Exercise/tolerance valvular discase" color="primary" :disabled="discardiovascular" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-1"></div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Hepato</h1>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.bowel" true-value="Bowel"
                        label="Bowel obstruction" color="primary" :disabled="dishepato" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hiatal" true-value="Hiatal"
                        label="Hiatal hernia / reflux" color="primary" :disabled="dishepato" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.ucer" true-value="Ucer" label="Ucer"
                        color="primary" :disabled="dishepato" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.chrrosis" true-value="Chrrosis"
                        label="Chrrosis" color="primary" :disabled="dishepato" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.nausea" true-value="Nausea & Vormiting"
                        label="Nausea & Vormiting" color="primary" :disabled="dishepato" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.jaundice" true-value="Jaundice"
                        label="Hepatitis/Jaundice" color="primary" :disabled="dishepato" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.wnlhepato" true-value="WNLHepato" label="WNL"
                    color="primary" :disabled="dishepato" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Renal</h1>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diabetes" true-value="Diabetes"
                        label="Diabetes" color="primary" :disabled="disrenal" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.thyroid" true-value="Thyroid"
                        label="Thyroid Disease" color="primary" :disabled="disrenal" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.renal" true-value="Renal"
                        label="Renal Failure / Dalysis" color="primary" :disabled="disrenal" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.weight" true-value="Weight Loss/ Gain"
                        label="Weight Loss/ Gain" color="primary" :disabled="disrenal" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.dysrythmiarenal" true-value="Dysrythmia"
                        label="Dysrythmia" color="primary" :disabled="disrenal" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.urinary"
                        true-value="Urinary tract infection" label="Urinary tract infection" color="primary"
                        :disabled="disrenal" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.wnlrenal" true-value="WNLRenal" label="WNL"
                    color="primary" :disabled="disrenal" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Neuro/Musculoskeletal</h1>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.arteitis" true-value="Arteitis"
                        label="Arteitis" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.muscule" true-value="Muscule"
                        label="Muscule" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.weaknes" true-value="Weaknes"
                        label="Weaknes" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.DJD" true-value="DJD" label="DJD"
                        color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.seizures" true-value="Seizures"
                        label="Seizures" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.backproblems" true-value="Back problems"
                        label="Back problems" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.wnlback" true-value="WNLBack" label="WNL"
                    color="primary" :disabled="disneuro" />
                </VControl>
              </VField>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.neuromuscular" true-value="Neuromuscular"
                        label="Neuromuscular dis paralys" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.cva" true-value="CVA / strok / VIA"
                        label="CVA / strok / VIA" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.paresthesia" true-value="Paresthesia"
                        label="Paresthesia" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.syncope" true-value="Syncope"
                        label="Syncope" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.headaches" true-value="Headaches"
                        label="Headaches/ICP" color="primary" :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.consiousness"
                        true-value="Loss of consiousness" label="Loss of consiousness" color="primary"
                        :disabled="disneuro" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Other</h1>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.anemia" true-value="Anemia"
                        label="Anemia" color="primary" :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.immunosuppresed"
                        true-value="Immunosuppresed" label="Immunosuppresed" color="primary" :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.bleeding"
                        true-value="Bleeding tendencies" label="Bleeding tendencies" color="primary"
                        :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.pregnancy" true-value="Pregnancy"
                        label="Pregnancy" color="primary" :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.cancer" true-value="Cancer"
                        label="Cancer" color="primary" :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.sickie"
                        true-value="Sickie cell dis / trait" label="Sickie cell dis / trait" color="primary"
                        :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.wnlother" true-value="WNLOther" label="WNL"
                    color="primary" :disabled="disother" />
                </VControl>
              </VField>
            </div>
            <div class="column is-11">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.chemotherapy" true-value="Chemotherapy"
                        label="Chemotherapy" color="primary" :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.steroids" true-value="Recent steroids"
                        label="Recent steroids" color="primary" :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.dehydration" true-value="Dehydration"
                        label="Dehydration" color="primary" :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.transfusion"
                        true-value="Tranfusion history" label="Tranfusion history" color="primary"
                        :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.hemophilia" true-value="Hemophilia"
                        label="Hemophilia" color="primary" :disabled="disother" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 pt-0 mt-5">
              <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold;">Diagnostic Studies</h1>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">EKG</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.ekgdiagnostic" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">HB/Hct/CBC</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.hbdiagnostic" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Pulmonary Studies</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.pulmonarydiagnostic" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Electrolit</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.electrolitdiagnostic" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">X-ray</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.xraydiagnostic" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Urinalisis</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.urindiagnostic" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Lain-Lain</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.lainlaindiagnostic" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Lain-Lain</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.lainlainlaboratory" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">Masalah / Diagnostik</h1>
              <VField>
                <VTextarea rows="2" v-model="input.masalaahdiagnostik"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">Premidikasi</h1>
              <VField>
                <VTextarea rows="2" v-model="input.premidikasi"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">Rencana Anastesi</h1>
              <VField>
                <VTextarea rows="2" v-model="input.rencanaanastesi"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold;">PS ASA</h1>
              <VField>
                <VTextarea rows="2" v-model="input.psasa"></VTextarea>
              </VField>
            </div>
            <div class="column is-12 pt-0 mt-5">
              <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
            </div>
          </div>
        </div>

        <!-- START KESTRAD -->

        <div class="column is-12"
          v-if="isPemeriksaanFisik == true && namaRuanganDinamis.toUpperCase().indexOf('TRADISIONAL') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-8"></div>
            <div class="column is-4 bold">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="input.batasnormalkestrad" true-value="Dalam Batas Normal"
                    label="Dalam Batas Normal" color="primary" circle @click="normalKestrad" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Kepala</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kepalakestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Jantung</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.jantungkestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Mata</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.matakestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Paru</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.parukestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">THT</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.thtkestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Perut</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.perutkestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Leher</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.leherkestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Extremitas</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.extremitaskestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Thoraks</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.thorakskestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Lain</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.lainkestrad" placeholder="" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START NON TRAUMA -->

        <div class="column is-12"
          v-if="isPemeriksaanFisikLokalis == true && (jenisTrauma == 'Non Trauma' || namaRuanganDinamis.toUpperCase().indexOf('BEDAH MULUT') > -1)"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-6">
              <h1 style="font-weight: bold; text-align: center;">Status Lokalis</h1>
              <VField>
                <VTextarea rows="4" v-model="input.lokalisNonTrauma"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold; text-align: center;">Skema</h1>
              <VField>
                <VTextarea rows="4" v-model="input.skemaNonTrauma"></VTextarea>
              </VField>
            </div>
          </div>
        </div>

        <!-- START MATA -->

        <div class="column is-12"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('MATA') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 style="font-weight: bold;">Status Opthalmologi</h1>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold; text-align: center;">UVCA</h1>
            </div>
            <div class="column is-6">
              <h1 style="font-weight: bold; text-align: center;">BCVA</h1>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Visus Awal OD</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.visusawalodu" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Visus Awal OD</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.visusawalodb" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Visus Awal OS</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.visusawalosu" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Visus Awal OS</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.visusawalosb" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Kacamata OD</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kacamataodu" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Kacamata OD</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kacamataodb" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Kacamata OS</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kacamataosu" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Kacamata OS</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.kacamataosb" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 mt-5">
              <h1 style="font-weight: bold; text-align: center;">Nystagmus</h1>
            </div>
            <div class="column is-12">
              <h1 style="font-weight: bold; text-align: center;">Posisi/Hirschberg</h1>
            </div>
            <div class="column is-1"></div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.od" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 style="font-weight: bold; text-align: center;">OD</h1>
            </div>
            <div class="column is-1">
              <h1 style="font-weight: bold; text-align: center;">OS</h1>
            </div>
            <div class="column is-4">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.os" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-1"></div>
            <div class="column is-3">
              <h1 style="font-weight: bold; text-align: right;">Mata Kanan</h1>

            </div>
            <div class="column is-6"></div>
            <div class="column is-3">
              <h1 style="font-weight: bold; text-align: left;">Mata Kiri</h1>
            </div>
            <!-- Kanan -->
            <div class="column is-3">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Palpebra</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.palpebran" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Konjungtiva</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.konjungtivan" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Kornea</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.kornean" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Bilik Mata</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.bilikmatan" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Iris</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.irisn" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Pupil</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.pupiln" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Lensa</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.lensan" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Vitreus</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.vitreusn" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Funduskopi</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.funduskopin" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Schiotz</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.schiotzn" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Aplanasi</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.aplanasin" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">NCT</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.nctn" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-5">
              <ImgDraw elemenID="canvasmata" height="640" width="380" imageSrc="/images/simrs/matafix-mirror.png" />
            </div>
            <!-- KIRI -->
            <div class="column is-3">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Palpebra</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.palpebrai" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Konjungtiva</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.konjungtivai" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Kornea</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.korneai" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Bilik Mata</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.bilikmatai" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Iris</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.irisi" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Pupil</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.pupili" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Lensa</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.lensai" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Vitreus</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.vitreusi" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Funduskopi</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.funduskopii" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Schiotz</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.schiotzi" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">Aplanasi</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.aplanasii" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight: bold;">NCT</h1>
                </div>
                <div class="column is-8">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.ncti" placeholder="" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-2" style="margin-top: 50px;">
              <h1 style="font-weight: bold;">Test Anel</h1>
            </div>
            <div class="column is-10" style="margin-top: 50px;">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.testanel" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Test Buta Warna</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.testbutawarna" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <h1 style="font-weight: bold;">Test Fluoresin</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl icon="">
                  <VInput type="text" v-model="input.testfluoresin" placeholder="" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2" style="margin-top: 50px;">
              <h1 style="font-weight: bold;">Resep Kacamata</h1>
            </div>
            <div class="column is-2" style="margin-top: 50px;">
              <h1 style="font-weight: bold;">R/</h1>
            </div>
            <div class="column is-8">
              <VField>
                <VControl>
                  <VRadio v-model="input.optionsmata" :value="'jauh'" label="Jauh" name="Jauh" color="primary"
                    id="Jauh" />
                  <VRadio v-model="input.optionsmata" :value="'dekat'" label="Dekat" name="Dekat" color="primary"
                    id="Dekat" />
                  <VRadio v-model="input.optionsmata" :value="'progresif'" label="Progresif" name="Progresif"
                    color="primary" id="Progresif" />
                  <VRadio v-model="input.optionsmata" :value="'tidakada'" label="Tidak Ada" name="Tidak Ada"
                    color="danger" id="TidakAda" />
                </VControl>
              </VField>
            </div>
            <!-- <div class="column is-2" style="margin-top: 50px;">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.jauh" true-value="jauh"
                                    label="Jauh" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" style="margin-top: 50px;">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.dekat" true-value="dekat"
                                    label="Dekat" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" style="margin-top: 50px;">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.progresif" true-value="progresif"
                                    label="Progresif" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" style="margin-top: 50px;">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakadaresep" true-value="tidak"
                                    label="Tidak Ada" color="primary" circle @click="tidakada"/>
                                </VControl>
                            </VField>
                        </div> -->
            <div class="column is-12" v-if="input.optionsmata != 'tidakada' && input.optionsmata != undefined">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1 style="font-weight: bold;">OD</h1>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold;">OS</h1>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Spheris</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.spherisd" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Spheris</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.spheriss" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Cylinder</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.cylinderd" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Cylinder</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.cylinders" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Prisma</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.prismad" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Prisma</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.prismas" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Axis</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.axisd" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 style="font-weight: bold;">Axis</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.axiss" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 mt-5">
                  <h1 style="font-weight: bold;">Addition</h1>
                </div>
                <div class="column is-10 mt-5">
                  <VField>
                    <VControl icon="">
                      <VInput type="text" v-model="input.addition" placeholder="" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 mt-5">
                  <h1 style="font-weight: bold;">Pupil Distance</h1>
                </div>
                <div class="column is-10 mt-5">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="number" class="input" placeholder="" v-model="input.pupil" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- START GIGI -->
        <div class="column is-12 mt-5 px-5"
          v-if="(isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('GIGI') > -1 || isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('MULUT') > -1 || isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('ENDODONSIA') > -1)"
          style="margin-top: -20px;">
          <div class="columns is-multiline" v-for="getLokalis in lokalisGigi">
            <div class="column is-12">
              <h1 style="font-weight: bold;">{{ getLokalis.header }}</h1>
            </div>
            <div class="column" v-for="lokalisGigi in getLokalis.body" :class="lokalisGigi.class">
              <VField :label="lokalisGigi.label" style="width: auto !important;">
                <VControl fullwidth v-if="lokalisGigi.type == 'text'">
                  <VInput v-model="input[lokalisGigi.model]" :disabled="lokalisGigi.disabled" type="text" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START GIZI -->
        <div class="column is-12 mt-5 px-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('GIZI') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline" v-for="getLokalis in lokalisGiziKlinik">
            <div class="column is-12">
              <h1 style="font-weight: bold;">{{ getLokalis.header }}</h1>
            </div>
            <div class="column" v-for="lokalisGizi in getLokalis.body" :class="lokalisGizi.class">
              <VField :label="lokalisGizi.label" style="width: auto !important;">
                <VControl fullwidth v-if="lokalisGizi.type == 'dropdown'">
                  <VSelect v-model="input[lokalisGizi.model]" :disabled="lokalisGizi.disabled">
                    <VOption v-for="giziValue in lokalisGizi.children" :value="giziValue.value">
                      {{ giziValue.label }}
                    </VOption>
                  </VSelect>
                </VControl>
                <VControl fullwidth v-if="lokalisGizi.type == 'text'">
                  <VInput v-model="input[lokalisGizi.model]" :disabled="lokalisGizi.disabled" type="text" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START VCT -->
        <div class="column is-12 mt-5 px-5"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('VCT') > -1"
          style="margin-top: -20px;">
          <div class="columns is-multiline" v-for="getLokalis in lokalisPoliVCT">
            <div class="column is-12">
              <h1 style="font-weight: bold;">{{ getLokalis.header }}</h1>
            </div>
            <div class="column" v-for="lokalisVCT in getLokalis.body" :class="lokalisVCT.class">
              <VField :class="lokalisVCT.type == 'checkbox' ? 'is-flex' : ''"
                :label="lokalisVCT.type != 'checkbox' ? lokalisVCT.label : ''" style="width: auto !important;">
                <VControl fullwidth v-if="lokalisVCT.type == 'dropdown'">
                  <VSelect v-model="input[lokalisVCT.model]">
                    <VOption v-for="vctValue in lokalisVCT.children" :value="vctValue.value">
                      {{ vctValue.label }}
                    </VOption>
                  </VSelect>
                </VControl>
                <VControl fullwidth v-if="lokalisVCT.type == 'text'">
                  <VInput v-model="input[lokalisVCT.model]" :disabled="lokalisVCT.disabled" type="text" />
                </VControl>
                <div class="columns is-multiline" v-if="lokalisVCT.type == 'checkbox'">
                  <span v-if="lokalisVCT.label != ''">
                    {{ lokalisVCT.label }}
                  </span>
                  <div class="column is-3 pt-0" v-for="vctValue in lokalisVCT.children">
                    <VControl fullwidth>
                      <VRadio class="fontcheckbox" v-model="input[lokalisVCT.model]" :value="vctValue.label"
                        :label="vctValue.label" :name="lokalisVCT.model" color="primary" square />
                    </VControl>
                  </div>
                </div>
                <div class="columns is-multiline" v-if="lokalisVCT.type == 'checkboxMulti'">
                  <span v-if="lokalisVCT.label != ''">
                    {{ lokalisVCT.label }}
                  </span>
                  <div class="column is-3 pt-0" v-for="(vctValue, index) in lokalisVCT.children" :key="index">
                    <VControl fullwidth>
                      <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square :true-value="vctValue.label"
                          :label="vctValue.label" v-model="input[`${lokalisVCT.model}${index}`]" />
                      </VControl>
                    </VControl>
                  </div>
                </div>
                <div class="columns is-multiline" v-if="lokalisVCT.type == 'checkboxSatuan'">
                  <span v-if="lokalisVCT.label != ''">
                    {{ lokalisVCT.label }}
                  </span>
                  <div class="column is-2 pt-0" v-for="vctValue in lokalisVCT.children">
                    <VControl raw subcontrol>
                      <VCheckbox class="p-0" color="primary" square :true-value="vctValue.label" :label="vctValue.label"
                        v-model="input[vctValue.value]" />
                    </VControl>
                  </div>
                </div>
              </VField>
            </div>
          </div>
        </div>
        <!-- START ONKOLOGI RADIASI -->
        <div class="column is-12"
          v-if="isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1 || isPemeriksaanFisikLokalis == true && namaRuanganDinamis.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1"
          style="margin-top: -20px;">
          <div class="is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">STATUS LOKASI:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.statusLokasiOnkologi" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">KELENJAR GETAH BENING REGIONAL:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kelenjarGetahBening" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">KELENJAR GETAH BENING LAINNYA:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kelenjarGetahBeningLain" class="input" type="text" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-3">
              <h1 style="font-weight: bold">Patologi Anatomi</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.patologiAnatomi" class="textarea" />
                </VControl>
              </VField>
            </div>

            <div class="column is-2">
              <h1 style="font-weight: bold">Tanggal</h1>
              <VField>
                <VDatePicker v-model="input.tanggalOnkrad" mode="date">
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

            <div class="column is-7">
              <h1 style="font-weight: bold">Hasil</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.hasil" class="textarea" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns">
            <div class="column is-3">
              <h1 style="font-weight: bold">Hasil Laboratorium</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.hasilLabonkologi" class="textarea" />
                </VControl>
              </VField>
            </div>

            <div class="column is-2">
              <h1 style="font-weight: bold">Tanggal</h1>
              <VField>
                <VDatePicker v-model="input.tanggalOnkrad2" mode="date">
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

            <div class="column is-7">
              <h1 style="font-weight: bold">Hasil</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.keteranganHasilLab" class="textarea" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns">
            <div class="column is-3">
              <h1 style="font-weight: bold">Hasil Radiologi</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.hasilRadonkologi" class="textarea" />
                </VControl>
              </VField>
            </div>

            <div class="column is-2">
              <h1 style="font-weight: bold">Tanggal</h1>
              <VField>
                <VDatePicker v-model="input.tanggalOnkrad3" mode="date">
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

            <div class="column is-7">
              <h1 style="font-weight: bold">Hasil</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.keteranganHasilRad" class="textarea" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="columns">
            <div class="column is-3">
              <h1 style="font-weight: bold">Lain - Lain</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.lainLainHasil" class="textarea" />
                </VControl>
              </VField>
            </div>

            <div class="column is-2">
              <h1 style="font-weight: bold">Tanggal</h1>
              <VField>
                <VDatePicker v-model="input.tanggalOnkrad4" mode="date">
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

            <div class="column is-7">
              <h1 style="font-weight: bold">Hasil</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.keteranganHasilLain" class="textarea" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <!-- START PEMERIKSAAN FISIK -->

        <div class="columns is-multiline">
          <div class="column is-12"
            v-if="namaRuanganDinamis.toUpperCase().indexOf('OBGYN') == -1 && namaRuanganDinamis.toUpperCase().indexOf('PSIKOLOGI KLINIS') == -1 && namaRuanganDinamis.toUpperCase().indexOf('TRADISIONAL') == -1 && namaRuanganDinamis.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') == -1 && isPemeriksaanFisik && isPemeriksaanFisikLokalis == false">
            <TabMenu :model="[]" :scrollable="true" />
            <div class="column is-12 pt-0">
              <div class="columns">
                <div class="column is-4">
                  <VButton type="button" rounded outlined color="info" icon="feather:filter" isLoading="false"
                    @click="semua()"> Tampilkan Semua
                  </VButton>
                </div>
                <div class="column is-4">
                  <VControl>
                    <VCheckbox class="fontcheckbox ml-0 mt-0" v-model="input.semuanormal" true-value="Batas Normal"
                      @click="semuanormal" label="Dalam Batas Normal (untuk semua pemeriksaan fisik)" color="primary"
                      circle style="margin: 15px 0px 0px 30px;font-weight:bold;" />
                  </VControl>
                </div>
              </div>
            </div>

            <div class="p-tabmenu-uy">
              <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="kepala"><span class="p-menuitem-icon"></span><span
                        class="p-menuitem-text">Kepala</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="mata"><span class="p-menuitem-icon"></span><span class="p-menuitem-text">Mata</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="tht"><span class="p-menuitem-icon"></span><span class="p-menuitem-text">THT</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="leher"><span class="p-menuitem-icon"></span><span class="p-menuitem-text">Leher</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="thorax"><span class="p-menuitem-icon"></span><span
                        class="p-menuitem-text">Thorax</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="cor"><span class="p-menuitem-icon"></span><span class="p-menuitem-text">Cor</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="pulmo"><span class="p-menuitem-icon"></span><span class="p-menuitem-text">Pulmo</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="abdomen"><span class="p-menuitem-icon"></span><span
                        class="p-menuitem-text">Abdomen</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="extremitas"><span class="p-menuitem-icon"></span><span
                        class="p-menuitem-text">Extremitas</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="lainnya"><span class="p-menuitem-icon"></span><span
                        class="p-menuitem-text">Lainnya</span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <div class="column is-12 pt-0"
            v-if="namaRuanganDinamis.toUpperCase().indexOf('POLI ONKOLOGI RADIASI') > -1 && isPemeriksaanFisik == true && isPemeriksaanFisikLokalis == false || namaRuanganDinamis.toUpperCase().indexOf('PELAYANAN ONKOLOGI RADIASI') > -1 && isPemeriksaanFisik == true && isPemeriksaanFisikLokalis == false">
            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">1. Kulit atau Selaput Lendir:</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasNormalOR" true-value="Dalam Batas Normal"
                      label="Dalam Batas Normal" color="primary" circle @click="normalOR" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0 is-flex">
                <div class="column is-3 pt-0">
                  <h1>Anemi</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Anemi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3 pt-0">
                  <h1>Sianosis</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Sianosis" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3 pt-0">
                  <h1>Icterus</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Icterus" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3 pt-0">
                  <h1>Turgor</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Turgor" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect2" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">2. Mata:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-3 pt-0">
                  <h1>Penglihatan Ka.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.PenglihatanKa" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect3" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3 pt-0">
                  <h1>Penglihatan Ki</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.PenglihatanKi" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect3" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3 pt-0">
                  <h1>Refleksi Pupil Ka.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.refleksiPupilKa" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3 pt-0">
                  <h1>Refleksi Pupil Ki</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.refleksiPupilKi" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <!-- <div class="column is-3 pt-0">
                                <h1>Refleksi Pupil Ki</h1>
                                <VField class="is-autocomplete-select">
                                  <VControl>
                                    <Multiselect v-model="input.refleksiPupilKi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                      :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                  </VControl>
                                </VField>
                              </div> -->
              </div>
              <div class="column is-12 pt-0 is-flex">
                <!-- <div class="column is-3 pt-0">
                  <h1>Refleksi Pupil Ki</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.refleksiPupilKi" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div> -->
                <div class="column is-3 pt-0">
                  <h1>Pupil</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.pupil" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="pupil" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0">
                  <h1>Diplopia</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.diplopia" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">3. Telinga:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-3 pt-0">
                  <h1>Telinga Ka.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.TelingaKa" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect3" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3 pt-0">
                  <h1>Telinga Ki</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.TelingaKi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect3" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">4. Hidung:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-3 pt-0">
                  <h1>Obstetri Ka.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.ObstetriKa" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect3" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-3 pt-0">
                  <h1>Obstetri Ki</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.ObstetriKi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect3" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">5. Mulut:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Gigi geligi.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.GigiGeligi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect3" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Keterangan</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.KeteranganMulut" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Mukosa</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.MukosaMulut" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>

              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Lidah.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.LidahOR" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect3" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Keterangan</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.KeteranganLidah" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Thyroid</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Thyroid" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="thyroid" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">6. Tonsil:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-6 pt-0">
                  <h1>Tonsil Ka.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.TonsilKa" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 pt-0">
                  <h1>Tonsil Ki.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.TonsilKi" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">7. Leher:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Tekanan Vena juguler.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.TekananVena" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="tekananVena" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">8. Thoraks:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Pergerakan Pernafasan Ka.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.pergerakanPernafasanKa" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="thoraks1" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Pergerakan Pernafasan Ki.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.pergerakanPernafasanKi" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="thoraks1" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <h1>Pergerakan Pernafasan Ka & Ki.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.pergerakanPernafasanKaKi" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="thoraks2" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-6 pt-0">
                  <h1>Perkusi Kanan</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.perkusiKanan" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 pt-0">
                  <h1>Perkusi Kiri</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.perkusiKiri" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-6 pt-0">
                  <h1>Austulkasi Kanan</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.AustulkasiKanan" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6 pt-0">
                  <h1>Austulkasi Kiri</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.AustulkasiKiri" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-8">
                <h1 style="font-weight: bold;">9. Jantung:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Membesar.</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.membesar" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Bunyi Jantung</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.bunyiJantung" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4 pt-0">
                  <h1>Auskultasi</h1>
                  <VField>
                    <VControl>
                      <VInput v-model="input.auskultasiJantung" type="text" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold;">10. Abdomen:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Nyeri</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Nyeri" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Ascites</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Ascites" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Tumor</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Tumor" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold;">11. Hati / Limpa:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-12 pt-0">
                  <h1>Hati / Limva</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <VInput type="text" v-model="input.hatiLimva" placeholder="Keterangan" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold;">12. Tulang Punggung:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Kifosis</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Kifosis" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Skollosis</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Skollosis" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Nyeri Tekanan atau Ketut</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.NyeriTekanantulangPunggung" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold;">13. Pelvis:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">

                <div class="column is-4 pt-0">
                  <h1>Nyeri Tekanan atau Ketut</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.NyeriTekananPelvis" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold;">14. Extremitas:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Edema</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Edema" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Varices</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.Varices" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="multiSelect" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

                <div class="column is-4 pt-0">
                  <h1>Nyeri Tekanan atau Ketut</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.NyeriTekananExtermitas" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <h1>Reflek Patologis</h1>
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.ReflekPatologis" :attrs="{ value }" placeholder="--Pilih--"
                        label="label" :options="multiSelect" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>

              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold;">15. HPHT:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-4 pt-0">
                  <Vfield>
                    <VControl>
                      <VInput type="text" v-model="input.HPHT" placeholder="Keterangan" />
                    </VControl>
                  </Vfield>
                </div>

                <div class="column is-4 pt-0">
                  <VField class="is-autocomplete-select">
                    <VControl>
                      <Multiselect v-model="input.ketHpht" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="hpht" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="font-weight: bold;">16. Lain - lain:</h1>
              </div>

              <div class="column is-12 pt-0 is-flex">
                <div class="column is-12 pt-0">
                  <Vfield>
                    <VControl>
                      <VInput type="text" v-model="input.lainLain" placeholder="Keterangan" />
                    </VControl>
                  </Vfield>
                </div>
              </div>

              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
          </div>
          <div class="column is-12 pt-0"
            v-else-if="namaRuanganDinamis.toUpperCase().indexOf('OBGYN') == -1 && namaRuanganDinamis.toUpperCase().indexOf('PSIKOLOGI KLINIS') == -1 && namaRuanganDinamis.toUpperCase().indexOf('TRADISIONAL') == -1 && isPemeriksaanFisik == true && isPemeriksaanFisikLokalis == false">
            <div class="columns is-multiline" v-if="isKepala == true">
              <div class="column is-8">
                <h1 style="font-weight: bold;">1. Kepala</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                      label="Dalam Batas Normal" color="primary" circle @click="normalKepala" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isKepala == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.kepala" true-value="Kepala" label="Kepala"
                          color="primary" circle :disabled="paramRiwayat" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input type="number" v-model="input.ketKepala" class="input" style="height:25px"
                          v-if="input.kepala && input.kepala == 'Kepala'" :disabled="paramRiwayat" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.ubunubun" true-value="Ubun Ubun Besar"
                          label="Ubun Ubun Besar" color="primary" circle :disabled="paramRiwayat" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketUbunubun" class="input" style="height:25px"
                          v-if="input.ubunubun && input.ubunubun == 'Ubun Ubun Besar'" :disabled="paramRiwayat" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.normal" true-value="Normal" label="Normal"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.mikrosefali" true-value="Mikrosefali"
                          :disabled="paramRiwayat" label="Mikrosefali" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isKepala == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.lingkarkepala" true-value="Lingkar Kepala"
                          label="Lingkar Kepala" color="primary" circle :disabled="paramRiwayat" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input type="number" v-model="input.ketLingkarkepala" class="input" style="height:25px"
                          :disabled="paramRiwayat"
                          v-if="input.lingkarkepala && input.lingkarkepala == 'Lingkar Kepala'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.lingkarlainnya" true-value="Lainnya"
                          :disabled="paramRiwayat" label="Lainnya" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLingkarlainnya" class="input" style="height:25px"
                          :disabled="paramRiwayat" v-if="input.lingkarlainnya && input.lingkarlainnya == 'Lainnya'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4 columns">
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.makrosefali" true-value="Makrosefali"
                        :disabled="paramRiwayat" label="Makrosefali" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>

            <hr v-if="isKepala == true">

            <div class="columns is-multiline" v-if="isMata == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-8">
                <h1 style="font-weight: bold;">2. Mata</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalmata" circle :disabled="paramRiwayat"
                      true-value="Dalam Batas Normal" label="Dalam Batas Normal" color="primary" @click="normalMata" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isMata == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.anemis" true-value="Anemis" label="Anemis"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketAnemis" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.anemis && input.anemis == 'Anemis'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.konjungtiva" true-value="Konjungtiva Pucat"
                          :disabled="paramRiwayat" label="Konjungtiva Pucat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.ketKonjungtiva" :attrs="{ value }" placeholder="--Pilih--"
                          :disabled="paramRiwayat" label="label" :options="d_mata" :searchable="true" track-by="label"
                          mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.pupil" true-value="Pupil Isokor"
                          :disabled="paramRiwayat" label="Pupil Isokor" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.ketPupil" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :disabled="paramRiwayat" :options="d_mata" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isMata == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.ikterus" true-value="Ikterus" label="Ikterus"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketIkterus" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.ikterus && input.ikterus == 'Ikterus'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.hiperemi" true-value="Hiperemi" label="Hiperemi"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.ketHiperemi" :attrs="{ value }" placeholder="--Pilih--"
                          :disabled="paramRiwayat" label="label" :options="d_mata" :searchable="true" track-by="label"
                          mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.refleks" true-value="Refleks Cahaya"
                          :disabled="paramRiwayat" label="Refleks Cahaya" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketRefleks" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.refleks && input.refleks == 'Refleks Cahaya'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isMata == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.refpupil" true-value="Refleks Pupil"
                          :disabled="paramRiwayat" label="Refleks Pupil" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketRefpupil" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.refpupil && input.refpupil == 'Refleks Pupil'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.secret" true-value="Secret" label="Secret"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.ketSecret" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :disabled="paramRiwayat" :options="d_mata" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.oedema" true-value="Oedema" label="Oedema"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.ketOedema" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :disabled="paramRiwayat" :options="d_mata" :searchable="true" track-by="label" mode="single"
                          autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isMata == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.oedemapal" true-value="Oedema Palpebrae"
                          :disabled="paramRiwayat" label="Oedema Palpebrae" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketOedemapal" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.oedemapal && input.oedemapal == 'Oedema Palpebrae'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.skleraik" true-value="Sklera Ikteris"
                          :disabled="paramRiwayat" label="Sklera Ikteris" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.ketSkleraik" :attrs="{ value }" placeholder="--Pilih--"
                          :disabled="paramRiwayat" label="label" :options="d_mata" :searchable="true" track-by="label"
                          mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4"></div>
            </div>

            <hr v-if="isMata == true">

            <div class="columns is-multiline" v-if="isTHT == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-8">
                <h1 style="font-weight: bold;">3. THT</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormaltht" true-value="Dalam Batas Normal"
                      :disabled="paramRiwayat" label="Dalam Batas Normal" color="primary" circle @click="normalTHT" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isTHT == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.tonsil" true-value="Tonsil" label="Tonsil"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketTonsil" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.tonsil && input.tonsil == 'Tonsil'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.hidung" true-value="Hidung" label="Hidung"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketHidung" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.hidung && input.hidung == 'Hidung'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.lainlain" true-value="Lain-Lain"
                          :disabled="paramRiwayat" label="Lain-Lain" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLain" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.lainlain && input.lainlain == 'Lain-Lain'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isTHT == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.pharing" true-value="Pharing" label="Pharing"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketPharing" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.pharing && input.pharing == 'Pharing'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.bibir" true-value="Bibir" label="Bibir"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketBibir" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.bibir && input.bibir == 'Bibir'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.lidah" true-value="Lidah" label="Lidah"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLidah" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.lidah && input.lidah == 'Lidah'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isTHT == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.telinga" true-value="Telinga" label="Telinga"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketTelinga" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.telinga && input.telinga == 'Telinga'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
              </div>
              <div class="column is-4"></div>
            </div>

            <hr v-if="isTHT == true">

            <div class="columns is-multiline" v-if="isLeher == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-8">
                <h1 style="font-weight: bold;">4. Leher</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalleher" true-value="Dalam Batas Normal"
                      :disabled="paramRiwayat" label="Dalam Batas Normal" color="primary" circle @click="normalLeher" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isLeher == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.jvp" true-value="JVP" label="JVP" color="primary"
                          :disabled="paramRiwayat" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketJVP" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.jvp && input.jvp == 'JVP'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.kakukuduk" true-value="Kaku Kuduk" label="Kaku Kuduk"
                      :disabled="paramRiwayat" color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.tunggal" true-value="Tunggal" label="Tunggal"
                      :disabled="paramRiwayat" color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isLeher == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.kelenjar" true-value="Pembesaran Kelenjar"
                          :disabled="paramRiwayat" label="Pembesaran Kelenjar" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketKelenjar" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.kelenjar && input.kelenjar == 'Pembesaran Kelenjar'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.lainnya" true-value="Lainnya" label="Lainnya"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLainnya" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.lainnya && input.lainnya == 'Lainnya'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.multiple" true-value="Multiple" label="Multiple"
                      :disabled="paramRiwayat" color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>

            <hr v-if="isLeher == true">

            <div class="columns is-multiline" v-if="isThorax == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-8">
                <h1 style="font-weight: bold;">5. Thorax</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalthorax" true-value="Dalam Batas Normal"
                      :disabled="paramRiwayat" label="Dalam Batas Normal" color="primary" circle
                      @click="normalThorax" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isThorax == true">
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.simetris" true-value="Simetris"
                          :disabled="paramRiwayat" label="Simetris / Asimetris" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketSimetris" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.simetris && input.simetris == 'Simetris'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.retraksi" true-value="Retraksi" label="Retraksi"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketRetraksi" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.simetris && input.simetris == 'Simetris'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>

            <hr v-if="isThorax == true">

            <div class="columns is-multiline" v-if="isCor == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-8">
                <h1 style="font-weight: bold;" v-if="isCor == true">6. Cor</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.normalcor" true-value="Dalam Batas Normal"
                      :disabled="paramRiwayat" label="Dalam Batas Normal" color="primary" circle
                      @click="normalEKGCor" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>

              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Inspeksi
                  Iktus Kordis</h1>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.cornormal" true-value="Normal" label="Normal"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VCheckbox class="fontcheckbox" v-model="input.melebar" true-value="Melebar" label="Melebar"
                              :disabled="paramRiwayat" color="primary" circle />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <input v-model="input.ketMelebar" class="heightinput input" :disabled="paramRiwayat"
                              v-if="input.melebar && input.melebar == 'Melebar'" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1 style="font-weight: bold;" class="ml-3 fontcheckbox">Lokasi</h1>
                  </div>
                  <div class="column is-8">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLokasi" class="heightinput input" :disabled="paramRiwayat" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Inspeksi
                  Pulsasi</h1>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.apex" true-value="Apex" label="Apex"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.prekordium" true-value="Prekordium"
                          :disabled="paramRiwayat" label="Prekordium" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.epigastrium" true-value="Epigastrium"
                          :disabled="paramRiwayat" label="Epigastrium" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.corlainnya" true-value="Lainnya" label="Lainnya"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLainnya" class="heightinput input" :disabled="paramRiwayat"
                          v-if="input.corlainnya && input.corlainnya == 'Lainnya'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Suara
                  Jantung Utama</h1>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.s1s2" true-value="S1, S2" label="S1, S2"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                    <VField>
                      <VControl>
                        <input v-model="input.ketTunggal" class="heightinput input" :disabled="paramRiwayat"
                          v-if="input.s1s2 && input.s1s2 == 'S1, S2'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.regular" true-value="Regular" label="Regular"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.iregular" true-value="Iregular" label="Iregular"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.systole" true-value="Extra Systole"
                          :disabled="paramRiwayat" label="Extra Systole" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.gallop" true-value="Gallop" label="Gallop"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Palpasi
                  Iktus Kordis</h1>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.palpasinormal" true-value="Normal" label="Normal"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.kuatangkat" true-value="Kuat Angkat"
                          :disabled="paramRiwayat" label="Kuat Angkat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.meluas" true-value="Meluas" label="Meluas"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1 style="font-weight: bold;" class="ml-3 fontcheckbox">Lokasi</h1>
                  </div>
                  <div class="column is-8">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLokasiPalpasi" class="heightinput input" :disabled="paramRiwayat" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <h1 style="font-weight: bold; margin-bottom: 10px; margin-top: -10px;" class="ml-3">
                  Palpasi
                  Thrill
                </h1>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.sistolik" true-value="Sistolik" label="Sistolik"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.diastolik" true-value="Diatolik" label="Diatolik"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Suara
                  Jantung Tambahan</h1>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.murmur" true-value="Murmur" label="Murmur"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8">
                    <VField>
                      <VControl>
                        <input v-model="input.ketMurmur" class="heightinput input" :disabled="paramRiwayat"
                          v-if="input.murmur && input.murmur == 'Murmur'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 10px;" class="ml-3">Perkusi
                </h1>
                <div class="columns is-multiline">
                  <div class="column is-5">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.batasatas" true-value="Batas Atas"
                          :disabled="paramRiwayat" label="Batas Atas" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-7">
                    <VField>
                      <VControl>
                        <input v-model="input.ketBatasatas" class="heightinput input" :disabled="paramRiwayat"
                          v-if="input.batasatas && input.batasatas == 'Batas Atas'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-5">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.batasbawah" true-value="Batas Bawah"
                          :disabled="paramRiwayat" label="Batas Bawah" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-7">
                    <VField>
                      <VControl>
                        <input v-model="input.ketBatasbawah" class="heightinput input" :disabled="paramRiwayat"
                          v-if="input.batasbawah && input.batasbawah == 'Batas Bawah'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-5">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.bataskanan" true-value="Batas Kanan"
                          :disabled="paramRiwayat" label="Batas Kanan" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-7">
                    <VField>
                      <VControl>
                        <input v-model="input.ketBataskanan" class="heightinput input" :disabled="paramRiwayat"
                          v-if="input.bataskanan && input.bataskanan == 'Batas Kanan'" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-5">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.bataskiri" true-value="Batas Kiri"
                          :disabled="paramRiwayat" label="Batas Kiri" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-7">
                    <VField>
                      <VControl>
                        <input v-model="input.ketBataskiri" class="heightinput input" :disabled="paramRiwayat"
                          v-if="input.bataskiri && input.bataskiri == 'Batas Kiri'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>

            <hr v-if="isCor == true">

            <div class="columns is-multiline" v-if="isPulmo == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-8">
                <h1 style="font-weight: bold;">7. Pulmo</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalpulmo" true-value="Dalam Batas Normal"
                      :disabled="paramRiwayat" label="Dalam Batas Normal" color="primary" circle @click="normalPulmo" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isPulmo == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statis" true-value="Inspeksi Statis"
                          :disabled="paramRiwayat" label="Inspeksi Statis" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketStatis" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.statis && input.statis == 'Inspeksi Statis'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perkusi" true-value="Perkusi" label="Perkusi"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketPerkusi" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.perkusi && input.perkusi == 'Perkusi'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.auskultasi" true-value="Auskultasi Vesikuler"
                          :disabled="paramRiwayat" label="Auskultasi Vesikuler" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketAuskultasi" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.auskultasi && input.auskultasi == 'Auskultasi Vesikuler'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isPulmo == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.dinamis" true-value="Inspeksi Dinamis"
                          :disabled="paramRiwayat" label="Inspeksi Dinamis" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketDinamis" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.dinamis && input.dinamis == 'Inspeksi Dinamis'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.ronchi" true-value="Ronchi" label="Ronchi"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketRonchi" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.ronchi && input.ronchi == 'Ronchi'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.nafas" true-value="Suara Nafas"
                          :disabled="paramRiwayat" label="Suara Nafas" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketNafas" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.nafas && input.nafas == 'Suara Nafas'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isPulmo == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.palpasi" true-value="Palpasi: SF"
                          :disabled="paramRiwayat" label="Palpasi: SF" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketPalpasi" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.palpasi && input.palpasi == 'Palpasi: SF'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.wheezing" true-value="Auskultasi Wheezing"
                          :disabled="paramRiwayat" label="Auskultasi Wheezing" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketWheezing" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.wheezing && input.wheezing == 'Auskultasi Wheezing'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.lainnyapulmo" true-value="Lain-lain"
                          :disabled="paramRiwayat" label="Lain-lain" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLainnyapulmo" class="input" style="height:25px"
                          :disabled="paramRiwayat" v-if="input.lainnyapulmo && input.lainnyapulmo == 'Lain-lain'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>

            <hr v-if="isPulmo == true">

            <div class="columns is-multiline" v-if="isAbdomen == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-8">
                <h1 style="font-weight: bold;">8. Abdomen</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalabdomen" true-value="Dalam Batas Normal"
                      :disabled="paramRiwayat" label="Dalam Batas Normal" color="primary" circle
                      @click="normalAbdomen" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isAbdomen == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <h1>Peristaltik</h1>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl>
                        <Multiselect v-model="input.ketPeristaltik" :attrs="{ value }" placeholder="--Pilih--"
                          :disabled="paramRiwayat" label="label" :options="d_peristaltik" :searchable="true"
                          track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.distensi" true-value="Distensi" label="Distensi"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketDistensi" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.distensi && input.distensi == 'Distensi'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.meteorismus" true-value="Meteorismus"
                          :disabled="paramRiwayat" label="Meteorismus" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketMeteorismus" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.meteorismus && input.meteorismus == 'Meteorismus'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isAbdomen == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.souffle" true-value="Souffle" label="Souffle"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketSouffle" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.souffle && input.souffle == 'Souffle'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.ascites" true-value="Ascites" label="Ascites"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketAscites" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.ascites && input.ascites == 'Ascites'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.turgor" true-value="Turgor" label="Turgor"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketTurgor" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.turgor && input.turgor == 'Turgor'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isAbdomen == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.nyeri" true-value="Nyeri Tekan Lokasi"
                          :disabled="paramRiwayat" label="Nyeri Tekan Lokasi" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketNyeri" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.nyeri && input.nyeri == 'Nyeri Tekan Lokasi'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.lien" true-value="Lien" label="Lien"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLien" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.lien && input.lien == 'Lien'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.massa" true-value="Massa" label="Massa"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketMassa" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.massa && input.massa == 'Massa'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isAbdomen == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.hepar" true-value="Hepar" label="Hepar"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketHepar" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.hepar && input.hepar == 'Hepar'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4"></div>
              <div class="column is-4"></div>
            </div>

            <hr v-if="isAbdomen == true">

            <div class="columns is-multiline" v-if="isExtremitas == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-8">
                <h1 style="font-weight: bold;">9. Extremitas</h1>
              </div>
              <div class="column is-4 bold">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalextremitas" :disabled="paramRiwayat"
                      true-value="Dalam Batas Normal" label="Dalam Batas Normal" color="primary" circle
                      @click="normalExtremitas" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isExtremitas == true">
              <div class="column is-6">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <h1>Extremitas</h1>
                  </div>
                  <div class="column is-6">
                    <VField class="is-autocomplete-select" v-slot="{ id }">
                      <VControl>
                        <Multiselect v-model="input.ketExtremitas" :attrs="{ value }" placeholder="--Pilih--"
                          :disabled="paramRiwayat" label="label" :options="d_extremitas" :searchable="true"
                          track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-6 columns is-multiline mb-0" v-if="isExtremitas == true">
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.odema" true-value="Odema" label="Odema"
                        :disabled="paramRiwayat" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <input v-model="input.ketOdema" class="input" style="height:25px" :disabled="paramRiwayat"
                        v-if="input.odema && input.odema == 'Odema'" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-6" v-if="isExtremitas == true">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.capillary" true-value="Capillary Refill Time"
                          :disabled="paramRiwayat" label="Capillary Refill Time" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketCapillary" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.capillary && input.capillary == 'Capillary Refill Time'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="columns is-multiline column is-6" v-if="isExtremitas == true">
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.lanlan" true-value="Lain-lain" label="Lain-lain"
                        :disabled="paramRiwayat" color="primary" circle />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <input v-model="input.ketLanlan" class="input" style="height:25px" :disabled="paramRiwayat"
                        v-if="input.lanlan && input.lanlan == 'Lain-lain'" />
                    </VControl>
                  </VField>
                </div>
              </div>

            </div>

            <hr v-if="isExtremitas == true">

            <div class="columns is-multiline" v-if="isLainnya == true">
              <div class="column is-12 pb-0"></div>
              <div class="column is-12">
                <h1 style="font-weight: bold;">Lainnya</h1>
              </div>
              <div class="column is-12 pt-0">
                <hr style="border-top: 1px dotted hsl(0deg 6.81% 88.68%);border-width:3px">
              </div>
            </div>
            <div class="columns is-multiline" v-if="isLainnya == true">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.Kulit" true-value="Kulit" label="Kulit"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketKulit" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.Kulit && input.Kulit == 'Kulit'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.pubertasp" true-value="Pubertas Perempuan"
                          :disabled="paramRiwayat" label="Pubertas Perempuan" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketPubertasp" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.pubertasp && input.pubertasp == 'Pubertas Perempuan'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.lala" true-value="Lain-lain" label="Lain-lain"
                          :disabled="paramRiwayat" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketLala" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.lala && input.lala == 'Lain-lain'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline" v-if="isLainnya == true">
              <div class="column is-4 pt-0 pb-0">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.genetalia" true-value="Genetalia Eloxterna"
                          :disabled="paramRiwayat" label="Genetalia Eloxterna" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketGenetalia" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.genetalia && input.genetalia == 'Genetalia Eloxterna'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4 pt-0 pb-0">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.pubertasl" true-value="Pubertas Laki-Laki"
                          :disabled="paramRiwayat" label="Pubertas Laki-Laki" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <input v-model="input.ketPubertasl" class="input" style="height:25px" :disabled="paramRiwayat"
                          v-if="input.pubertasl && input.pubertasl == 'Pubertas Laki-Laki'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-4"></div>
            </div>

            <hr v-if="isLainnya == true">
          </div>


          <div class="column is-12 pl-0 pr-0">
            <div class="column is-12 pt-0 pb-0">
              <div class="columns">
                <div class="column" v-for="(pilihan) in anamnesa">
                  <h1>{{ pilihan.title }}</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input[pilihan.model]" rows="3" :disabled="paramRiwayat">
                      </VTextarea>
                    </VControl>
                  </VField>
                  <VButtons class="mt-1 ml-auto" style="text-align: right; justify-content: right">
                    <VIconButton class="mr-2" raised circle icon="fas fa-file-medical-alt" @click="setPenunjang()"
                      :loading="isLoading" color="success" v-tooltip-prime.top="'Set Penunjang Khusus'"
                      v-if="namaRuanganDinamis.toUpperCase().indexOf('OBGYN') > -1">
                    </VIconButton>
                    <VIconButton class="mr-2" raised circle icon="fas fa-notes-medical" @click="getLabo()"
                      :loading="isLoading" color="success" v-tooltip-prime.top="'Input Laboratorium'">
                    </VIconButton>
                    <VIconButton raised circle icon="fas fa-user-nurse" @click="getRadio()" :loading="isLoading"
                      color="success" v-tooltip-prime.top="'Input Radiologi'">
                    </VIconButton>
                  </VButtons>
                </div>
                <div class="column" style="">

                  <h1 style="margin-right: 10px;">Instruksi</h1>
                  <!-- <div class="mr-2">
                                        <VButtons>
                                            <VIconButton type="button" raised circle icon="fas fa-book-medical"
                                                @click="addInstruksi()" color="success" v-tooltip-prime.top="'Instruksi'">
                                            </VIconButton>
                                        </VButtons>
                                        <VIconButton type="button" raised circle icon="fas fa-book-medical"
                                            @click="addInstruksi()" color="success" v-tooltip-prime.top="'Instruksi'">
                                        </VIconButton>
                                    </div> -->
                  <VField style="margin-left: 10px;">
                    <VControl>
                      <VTextarea v-model="input.instruksiAsesmen" rows="3" :disabled="paramRiwayat" />
                      <VButtons class="mt-1 ml-auto" style="text-align: right; justify-content: right">
                        <VIconButton class="mr-2" raised circle icon="fas fa-book-medical" @click="getTindakan()"
                          :loading="isLoading" color="success" v-tooltip-prime.top="'Input Tindakan'">
                        </VIconButton>
                        <VIconButton raised circle icon="lnir lnir-medicine-alt" @click="getObat()" :loading="isLoading"
                          color="success" v-tooltip-prime.top="'Input Obat'">
                        </VIconButton>
                        <!-- <i class="" aria-hidden="true"></i><i class="fas " aria-hidden="true"></i> -->
                        <VIconButton raised circle icon="fas fa-sign-out-alt" @click="transferPasien()"
                          :loading="isLoading" color="success" v-tooltip-prime.top="'Transfer Pasien'">
                        </VIconButton>
                      </VButtons>

                    </VControl>
                  </VField>
                </div>

              </div>
            </div>
          </div>

          <div class="column is-12 mb-0 pt-0">
            <h1 class="required-field">Diagnosa</h1>
            <div class="column is-12 pt-0 pl-0 pr-0">
              <VField>
                <VControl>
                  <AutoComplete v-model="input.diagnosaIcd10" :suggestions="d_Diagnosa" :disabled="paramRiwayat"
                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" width="105%"
                    @item-select="handlerDiagnosaten($event)" placeholder=" ICD 10 ..." class="mt-2" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 pt-0 pl-0 pr-0">
              <VField>
                <VTextarea rows="2" v-model="input.TADiagnosa" :disabled="paramRiwayat"></VTextarea>
              </VField>
              <VField label="Diet yang telah diberikan & diet yang harus dilakukan di rumah diet"
                v-if="props.registrasi.namadepartemen.indexOf('RAWAT INAP') > -1">
                <VControl>
                  <VTextarea v-model="input.dietYangTelahDiberikan" rows="3" :disabled="paramRiwayat" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 mb-0 pt-0" v-if="namaRuanganDinamis.toUpperCase().indexOf('IGD') > -1">
            <h1>MOI</h1>
            <div class="column is-12 pt-0 pl-0 pr-0">
              <VField>
                <VTextarea rows="2" v-model="input.MOI"></VTextarea>
              </VField>
            </div>
          </div>

          <hr>

          <div class="column is-12" style="display: none !important">
            <div class="column is-12 pl-0 pr-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <h1>Diagnosa Primer</h1>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VTextarea v-model="input.diagnosaPrimer" rows="1" style="margin-top: -15px;">
                            </VTextarea>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <div class="column is-6">
                    <h1>Kode ICD</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.diagnosaIcd10" :suggestions="d_Diagnosa"
                          @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder=" ICD 10 ..."
                          @item-select="handlerDiagnosaten($event)" class="mt-2" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <div class="column is-12">
              <div style="overflow-y:auto;" class="mt-1">
                <table class="tabels" border="1" style="width: 100%;">
                  <thead>
                    <tr>
                      <th width="25%" style="vertical-align: inherit;text-align:center">
                        Daftar
                        Masalah</th>
                      <th width="40%" style="vertical-align: inherit;text-align:center">
                        Rencana
                        Intervensi</th>
                      <th width="25%" style="vertical-align: inherit;text-align:center">
                        Target
                      </th>
                      <th style="vertical-align: inherit;text-align:center;" width="10%">
                        #
                      </th>
                    </tr>
                  </thead>
                  <tbody v-for="(input, index) in input.details" :key="index">
                    <tr>
                      <td class="td-po">
                        <div class="pb-0">
                          <VField>
                            <VControl>
                              <!-- <VControl icon="feather:bookmark"> -->
                              <!-- <VInput type="text" v-model="input.daftarMasalah" placeholder="Daftar Masalah" /> -->
                              <VTextarea rows="1" v-model="input.daftarMasalah" placeholder="Daftar Masalah"
                                :disabled="paramRiwayat">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-po">
                        <div class="pb-0">
                          <VField>
                            <VControl>
                              <!-- <VControl icon="feather:bookmark"> -->
                              <!-- <VInput type="text" v-model="input.rencanaIntervensi" placeholder="Rencana Intervensi" /> -->
                              <VTextarea rows="1" v-model="input.rencanaIntervensi" placeholder="Rencana Intervensi"
                                :disabled="paramRiwayat">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-po">
                        <div class="pb-0">
                          <VField>
                            <VControl>
                              <!-- <VControl icon="feather:bookmark"> -->
                              <!-- <VInput type="text" v-model="input.target" placeholder="Target" /> -->
                              <VTextarea rows="1" v-model="input.target" placeholder="Target" :disabled="paramRiwayat">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-rpo" style="vertical-align: inherit">
                        <VButtons style="justify-content:space-around">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeItem(index)" color="danger">
                          </VIconButton>
                        </VButtons>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- <hr>

          <div>
            <h1 style="margin-bottom: 10px; margin-top: 5px;font-weight: bold; ">Kondisi
              Keluar RS</h1>
            <div class="columns is-multiline">
              <div class="column is-12">
                <h1 style="margin-bottom: 10px;" class="ml-3">
                  Riwayat Keluar RS
                </h1>
                <div class="columns is-multiline column is-12">
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Sembuh" label="Sembuh"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Membaik"
                          label="Membaik" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Belum Sembuh"
                          label="Belum Sembuh" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                          true-value="Tidak Ada Perkembangan" label="Tidak Ada Perkembangan" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Meninggal >= 48 Jam"
                          label="Meninggal > 48 Jam" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Meninggal <= 48 Jam"
                          label="Meninggal <= 48 Jam" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="DOA" label="DOA"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Rawat Inap"
                          label="Rawat Inap" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <h1 style="margin-bottom: 10px;" class="ml-3">
                  Status Keluar RS
                </h1>
                <div class="columns is-multiline column is-12">
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Belum Keluar RS"
                          label="Belum Keluar RS" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Diijinkan Pulang"
                          label="Diijinkan Pulang" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Pulang Paksa"
                          label="Pulang Paksa" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.statuskeluar" true-value="Dirujuk"
                          label="Dirujuk" color="primary" circle />
                      </VControl>
                      <div v-if="input.statuskeluar == 'Dirujuk'">
                        <h1>Tujuan</h1>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.tujuan_skrs" />
                        </VControl>
                        <h1>Alasan</h1>
                        <VControl>
                          <VInput type="text" class="input" v-model="input.alasan_skrs" />
                        </VControl>
                      </div>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12 pt-0">
                <h1 style="margin-bottom: 10px;" class="ml-3">Perlu Kontrol
                </h1>
                <div class="columns is-multiline column is-12">
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Ya" label="Ya"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Tidak" label="Tidak"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-0">
                    <VField>
                      <VControl>
                        <VCheckbox class="fontcheckbox" v-model="input.perlukontrol" true-value="Rujuk Balik"
                          label="Rujuk Balik" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <template v-if="namaRuanganDinamis.toUpperCase().indexOf('REHAB MEDIK') > -1">
                <div class="column is-12">
                  <h1 style="margin-bottom: 10px;font-weight: bold" class="ml-3">Tindakan Rehab Medik
                  </h1>
                  <div class="columns is-multiline">
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab" true-value="Infrared"
                            label="Infrared" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab" true-value="SWD" label="SWD"
                            color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab" true-value="MWD" label="MWD"
                            color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab" true-value="USD" label="USD"
                            color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab" true-value="TENS" label="TENS"
                            color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab"
                            true-value="Electrical Stimulation (E.S)" label="Electrical Stimulation" color="primary"
                            circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab" true-value="Exercise"
                            label="Exercise" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab" true-value="Traksi"
                            label="Traksi" color="primary" circle />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3 pt-0">
                      <VField>
                        <VControl>
                          <VCheckbox class="fontcheckbox" v-model="input.tindakanRehab" true-value="Lainnya"
                            label="Lainnya" color="primary" circle />
                        </VControl>
                      </VField>
                      <VField v-if="input.tindakanRehab == 'Lainnya'">
                        <VControl>
                          <VInput type="text" v-model="input.tindakanRehablain" />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <h1 style="margin-bottom: 10px;font-weight: bold" class="ml-3">Terapi Ringkasan Keluar
                  </h1>
                  <div class="columns is-multiline">
                    <div class="column is-12 pt-0">
                      <VField>
                        <VControl>
                          <VTextarea v-model="input.terapiRingkasan" rows="4" placeholder="">
                          </VTextarea>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div> -->
        </div>

      </div>
    </div>
  </div>

  <VModal :open="modalInput" title="Diagnosis ICD 10" :noclose="false" size="big" actions="right"
    @close="modalInput = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">

                <div class="column is-3">
                  <VField label="Tanggal">
                    <VDatePicker v-model="item.tglpelayanan" mode="dateTime" style="width: 100%;">
                      <template #default="{ inputValue }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Tanggal" class="is-rounded" disabled />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Jenis " class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect mode="single" v-model="item.jenisDiagnosis10" :options="d_JenisDiagnosa"
                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />


                    </VControl>
                  </VField>
                </div>
                <div class="column is-5">
                  <VField label="Diagnosis ICD 10 " class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="lnir lnir-diagnosis" fullwidth>

                      <Multiselect mode="single" v-model="item.diagnosa10" placeholder="Pilih data" :searchable="true"
                        :filter-results="false" :min-chars="0" :resolve-on-load="true" :delay="0" :options="async function (query: any) {
                          return await fetchDiagnosa10(query)
                        }" autocomplete="off" />


                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" style="display: none !important">
                  <VField>
                    <VControl>
                      <VRadio v-model="item.isKasusBaru" :value="'baru'" label="Kasus Baru" name="isKasusBaru"
                        color="primary" id="isKasusBaru" />
                      <VRadio v-model="item.isKasusBaru" :value="'lama'" label="Kasus Lama" name="isKasusLama"
                        color="danger" id="isKasusLama" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-9" style="display: none !important">
                  <VField label="Keterangan">
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.keterangan10" rows="3"
                        placeholder="Keterangan diagnosis (jika belum tahu kodenya) ..." autocomplete="off"
                        autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </div>

              </div>
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="simpanICD10()" :loading="isLoading" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>

  <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" width="5%">#</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                  <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                  <td class="tg-0lax text-center" width="20%">No EMR</td>
                  <td class="tg-0lax text-center" width="20%">Dokter</td>
                  <td class="tg-0lax text-center" width="20%">Section</td>
                </tr>
              </thead>
              <tbody v-for="resep in listTemplate">
                <tr>
                  <td style="width:5%;text-align:center">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.created_at }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                  </td>
                  <td style="width:20%;text-align:center">
                    <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                  </td>
                  <td style="width:25%;text-align:center">
                    <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
    @close="isAlltemplate = false; showModalTemplateFix = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10" paginator
        tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
        <template #header>
          <div class="columns is-multiline">
            <div class="column is-8">
              <VField>
                <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VControl>
                  <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                </VControl>
              </VField>
            </div>
          </div>
        </template>
        <template #empty> No customers found. </template>
        <template #loading>
          <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
          <p style="color:white">Loading data, please wait...</p>
        </template>
        <Column headerStyle="width: 3rem">
          <template #body="slotProps">
            <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
              color="info" v-tooltip-prime.top="'Pilih'">
            </VIconButton>
          </template>
        </Column>
        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
        <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
          <template #body="slotProps">
            {{ slotProps.data.registrasi.namaruangan }}
          </template>
        </Column>
        <Column field="created_at" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
  </VModal>

  <VModal :open="modalInstruksi" title="Instruksi" :noclose="true" size="small" actions="right"
    @close="modalInstruksi = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">Instruksi</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg" style="width:100% !important">
              <thead>
                <tr>
                  <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;font-size:9pt;"
                    width="12%">
                    #
                  </th>
                  <th class="td-fkprj" style="vertical-align:inherit;text-align: center;font-size:9pt;"> Instruksi
                    Keperawatan & Intervensi
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="tg-0lax" style="vertical-align: inherit;">
                    <div class="column p-0">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRencanaKeperawatan()"
                            color="info" v-tooltip-prime.top="'Tambah instruksi'">
                          </VIconButton>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="tg-0lax">
                    <div class="column p-1">
                      <VField v-for="(input, index) in input.instruksi"
                        class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                        <VControl icon="lnir lnir-diagnosis" fullwidth class="prime-auto-select">
                          <AutoComplete v-model="input.instruksi" :suggestions="d_instruksi"
                            @complete="fetchTujuan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Cari Instruksi Keperawatan ..." class="mt-2" />

                          <AutoComplete v-model="input.intervensi" :suggestions="d_intervensi"
                            @complete="fetchIntervensi()" :optionLabel="'label'" :dropdown="true" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Intervensi"
                            class="mt-2" />
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <Dialog v-model:visible="modalInputTP" modal header="Konsultasi" :style="{ width: '60vw' }" size="large">
    <div class="columns is-multiline">
      <div class="column is-3">
        <VDatePicker class="pt-0 pb-0 pl-0" v-model="inputTP.tanggal" color="green" trim-weeks mode="dateTime">
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
            <Dropdown v-model="inputTP.ruanganasal" :options="d_Ruangan" :optionLabel="'label'" class="is-rounded"
              placeholder="Ruang Asal" style="width: 100%;" :filter="true" showClear :disabled="true" />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField class="is-select is-autocomplete-select
                            mt-0 pt-0" v-slot="{ id }">
          <VLabel class="required-field">Ruangan Tujuan</VLabel>
          <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
            <Dropdown v-model="inputTP.ruangantujuan" :options="d_Ruangan" :optionLabel="'label'" class="is-rounded"
              placeholder="Ruang Tujuan" style="width: 100%;" :filter="true" showClear />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField class="is-select is-autocomplete-select
                            mt-0 pt-0" v-slot="{ id }">
          <VLabel>Dokter/Pegawai Medis </VLabel>
          <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
            <VInput type="text" placeholder="Dokter DPJP" v-model="inputTP.namadokter" class="is-rounded"
              :disabled="disabledJawab" />
          </VControl>
        </VField>
      </div>

    </div>
    <template #footer>
      <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="modalInputTP = false">
        Batal
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="postTransferPasien()"> Simpan
      </VButton>
    </template>
  </Dialog>

  <Dialog v-model:visible="modalConfirmObgyn" modal header="Pilih Jenis Assesmen Awal Obgyn Yang Sesuai"
    :style="{ width: '30vw' }" maximizable>
    <VButton icon="feather:book" color="success" raised @click="setRouting('Ginekologi', 'jenisobgyn')"
      style="float:right; margin-top: 20px; margin: 10px;" :loading="isLoading">
      Ginekologi
    </VButton>
    <VButton icon="feather:book" color="info" raised @click="setRouting('Obstetri', 'jenisobgyn')"
      style="float:right; margin-top: 20px; margin: 10px;" :loading="isLoading">
      Obstetri
    </VButton>
  </Dialog>

  <Dialog v-model:visible="modalConfirmInterna" modal header="Pilih Jenis Assesmen Awal Yang Sesuai"
    :style="{ width: '30vw' }" maximizable>
    <VButton icon="feather:book" color="success" raised @click="setRouting('Obstetri', 'jenisinterna')"
      style="float:right; margin-top: 20px; margin: 10px;" :loading="isLoading">
      Geriatri
    </VButton>
    <VButton icon="feather:book" color="info" raised @click="setRouting('Interna', 'jenisinterna')"
      style="float:right; margin-top: 20px; margin: 10px;" :loading="isLoading">
      Interna
    </VButton>
  </Dialog>

  <Dialog v-model:visible="modalConfirmTrauma" modal header="Pilih Jenis Assesmen Awal Yang Sesuai"
    :style="{ width: '30vw' }" maximizable>
    <VButton icon="feather:book" color="success" raised @click="setRouting('Trauma', 'jenistrauma')"
      style="float:right; margin-top: 20px; margin: 10px;" :loading="isLoading">
      Trauma
    </VButton>
    <VButton icon="feather:book" color="info" raised @click="setRouting('Non Trauma', 'jenistrauma')"
      style="float:right; margin-top: 20px; margin: 10px;" :loading="isLoading">
      Non Trauma
    </VButton>
  </Dialog>

  <ConfirmDialog group="templating">
  </ConfirmDialog>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
// import DataTable from 'primevue/datatable'
// import Column from 'primevue/column'
import * as H from '/@src/utils/appHelper'
import * as EMR from '../../../page-emr-plugins/asesmen-medis-rj'
import AutoComplete from 'primevue/autocomplete';
import Slider from 'primevue/slider';
import Fieldset from 'primevue/fieldset';
import TOdontogram from '../../odontogram.vue'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import OrderLab from '../../order-laboratorium.vue'
import OrderRad from '../../order-radiologi.vue'
import OrderResep from '../../order-resep.vue'
import Tindakan from '../../tindakan.vue'
import Penunjang from '../../penunjang.vue'
import Resep from '../../order-resep.vue'
import Konsultasi from '../../konsultasi.vue'
import Laboratorium from '../../order-laboratorium.vue'
import Radiologi from '../../order-radiologi.vue'
import Periodonsia from '../../periodonsia.vue'
import Bedah from '../../order-bedah.vue'
import VueScrollTo from 'vue-scrollto'
import { useToaster } from '/@src/composable/toaster'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import ImgDraw from '../../../page-emr-plugins/img-draw.vue'
import ImgDrawOdon from '../../../page-emr-plugins/img-draw-odontogram.vue'
import TraumaScore from '../../../page-emr-plugins/trauma-score.vue'
import moment from 'moment'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';

useHead({
  title: 'Asesmen Medis Rawat Inap - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let paramRiwayat = useRoute().query.riwayat as boolean
paramRiwayat = paramRiwayat == "true" ? true : false
let paramEdit = useRoute().query.riwayat as boolean
paramEdit = paramEdit == "true" ? true : false
let jenispilihan = useRoute().query.jenisobgyn as string
let jenispilihaninterna = useRoute().query.jenisinterna as string
let jenispilihantrauma = useRoute().query.jenistrauma as string
let isfromCPPT = useRoute().query.iscppt as boolean

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

const namaRuanganDinamis = ref(''); // Save namaruangan dinamis (Section SL)
const formName = ref(props.FORM_NAME);
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const route = useRoute()
const pasien: any = ref({})
const showModalTemplate: any = ref(false)
const modalInput: any = ref(false)
const { scrollTo } = VueScrollTo
const loadData: any = ref(false);
const isLoadingPasien: any = ref(false)
const modalInstruksi: any = ref(false)
const modalConfirmObgyn: any = ref(false)
const modalConfirmInterna: any = ref(false)
const modalConfirmTrauma: any = ref(false)
const isAlltemplate: any = ref(false)
const sudahDisimpan = ref(false)
const filterMenu: any = ref('')

// const instruksi:any =ref([])
const d_JenisDiagnosa: any = ref([])
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_normal: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Abnormal' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_vulva: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Condiloma' }, { value: 3, label: 'Lesi' }])
const d_vagina: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Inlamasi' }, { value: 3, label: 'Discharge' }])
const d_cervix: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Inlamasi' }])
const d_uterus: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Fibroid' }])
const d_adnexa: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Massa' }])
const d_rectum: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Abnormal' }])
const d_extremitas: any = ref([{ value: 1, label: 'Hangat' }, { value: 2, label: 'Dingin' }])
const d_peristaltik: any = ref([{ value: 1, label: 'Peristaltik-> Normal' }, { value: 2, label: 'Peristaltik-> Meningkat' }, { value: 3, label: 'Peristaltik-> Menurun' }])
const isTrauma = ref(false);
const mapForTraumas = ref(false);
const dataNurse: any = ref();
const router = useRouter()
const multiSelect: any = ref([
  { value: 'Ya', label: 'Ya' },
  { value: 'Tidak', label: 'Tidak' }
]);
const multiSelect2: any = ref([
  { value: 'Baik', label: 'Baik' },
  { value: 'Tidak', label: 'Tidak' }
]);
const multiSelect3: any = ref([
  { value: 'Normal', label: 'Normal' },
  { value: 'Tidak', label: 'Tidak' }
]);
const pupil: any = ref([
  { value: 'Isokor', label: 'Isokor' },
  { value: 'Tidak', label: 'Tidak' }
])
const thyroid: any = ref([
  { value: 'Membesar', label: 'Membesar' },
  { value: 'Tidak membesar', label: 'Tidak membesar' }
])
const tekananVena: any = ref([
  { value: 'Meningkat', label: 'Meningkat' },
  { value: 'Tidak', label: 'Tidak' }
])
const thoraks1: any = ref([
  { value: 'Teratur', label: 'Teratur' },
  { value: 'Tidak', label: 'Tidak' }
])

const thoraks2: any = ref([
  { value: 'Simetris', label: 'Simetris' },
  { value: 'Tidak', label: 'Tidak' }
])

const hpht: any = ref([
  { value: 'Hamil', label: 'Hamil' },
  { value: 'Tidak Hamil', label: 'Tidak Hamil' }
])

const traumaScoreData = ref({});


const item: any = reactive({

  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  DEPARTEMEN_FK: props.registrasi.objectdepartemenfk,
  registrasi: {
    ruanganfk: props.registrasi.objectruanganlastfk,
    departemenfk: props.registrasi.objectdepartemenfk,
  },
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  tglpelayanan: new Date(),
  airway: [],
  disability: []

})

const d_Kelas: any = ref([])
const COLLECTION: any = ref('AsesmenMedisRawatInap') //table mongodb
let NORECTP = '';
const NOREC_EMRPASIEN: any = ref('')
const jenisObgyn: any = ref('')
const jenisInterna: any = ref('')
const jenisTrauma: any = ref('')
const d_ko: any = ref('')
const isResumeMedis: any = ref(false);
const isAssesmenMedis: any = ref(false);
const input: any = ref({
  waktuTataLaksana: new Date,
  waktuKontrol: new Date,
  jamKedatangan: new Date,
  jamAsesmenAwal: new Date,
  tanggalKedatangan: new Date,
  batasnormalkepala: false,
  batasnormalkestrad: false,
  batasnormalmata: false,
  batasnormaltht: false,
  namatemplate: '',
  namadiagnosa: '',
  batasnormalleher: false,
  batasnormalthorax: false,
  batasnormalpulmo: false,
  batasnormalabdomen: false,
  batasnormalextremitas: false,
  batasNormalOR: false,
  lokaliskulit: false,
  lokalistht: false,
  lokalissaraf: false,
  normalcor: false,
  semuanormal: false,
  tidakadaresep: false,
  wnlrespiratory: false,
  optionsnapza: "tidak",
  wnlcardiovascular: null,
  murmur: null,
  jenisObgyn: route.query.jenisobgyn as string || "",
  jenisInterna: route.query.jenisinterna as string || "",
  jenisTrauma: route.query.jenistrauma as string || "",
  details: [{
    no: 1,
  }],
  instruksi: [{
    no: 1
  }]
})
const inputTP: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const children: any = ref([
  { label: 'BB Turun', value: 'BB Turun' },
  { label: 'Diare', value: 'Diare' },
  { label: 'Badan Panas', value: '0' },
  { label: 'Jamur dimulut', value: '0' },
  { label: 'Sulit menelan', value: '0' },
  { label: 'Batuk', value: '0' },
  { label: 'Gatal pada kulit', value: '0' },
  { label: 'Kelainan kulit', value: '0' },
  { label: 'Gangguan visus', value: '0' },
  { label: 'Herpes simplex', value: '0' },
  { label: 'Herpes zoster', value: '0' },
  { label: 'ISPA berulang', value: '0' },
  { label: 'Nyeri kepala', value: '0' },
  { label: 'TB paru', value: '0' },
  { label: 'IMS', value: '0' },
  { label: 'Infeksi paru non TB', value: '0' },
  { label: 'Kesadaran menurun', value: '0' },
  { label: 'Pembesaran KGB', value: '0' },
]);
const children2: any = ref([
  { label: 'Lymphoma Burkitt`s', value: '' },
  { label: 'Cocodiodomycosis', value: '' },
  { label: 'Cytomegalovirus', value: '0' },
  { label: 'Herpes simplex', value: '0' },
  { label: 'Kandidiasis Esofagus', value: '0' },
  { label: 'Cryptococcosis', value: '0' },
  { label: 'Sarkoma Kaposi', value: '0' },
  { label: 'Histoplasmosis', value: '0' },
  { label: 'Ca Cervix invasif', value: '0' },
  { label: 'Cryptosporidiosis', value: '0' },
  { label: 'HIV encephalopathy', value: '0' },
  { label: 'Isosporiasis', value: '0' },
  { label: 'HIV Wasting syndrome', value: '0' },
  { label: 'Penicilliosis', value: '0' },
  { label: 'Reccurent pneumonia', value: '0' },
  { label: 'Lymphoma Otak', value: '0' },
  { label: 'Salmonella septicemia', value: '0' },
  { label: 'PCP', value: '0' },
  { label: 'M. Tuberculosis complex', value: '0' },
  { label: 'Toxoplasmosis', value: '0' },
  { label: 'Lymphoma Immunoblaastic', value: '0' },
  { label: 'Cytomegalovirus retintis', value: '0' },
  { label: 'Kandidiasis trakea/broncus/paru', value: '0' },
  { label: 'Progressive Multifocal Leukeuncephalopathy', value: '0' },
  { label: 'Mycobacterium non TBC', value: '0' },
]);

const d_Dokter: any = ref([])
const dataSourceICD9: any = ref([])
const dataSourceICD10: any = ref([])
const d_instruksi: any = ref([])
const d_intervensi: any = ref(1)
const d_Ruangan: any = ref([])
const tujuanKeper = ref('');
const isPemeriksaanFisik: any = ref(true)
const isPemeriksaanFisikLokalis: any = ref(false)
const gaadaresep: any = ref(false)
const isKepala: any = ref(true)
const isMata: any = ref(false)
const isTHT: any = ref(false)
const modalInputTP: any = ref(false);
const isLeher: any = ref(false)
const isThorax: any = ref(false)
const isCor: any = ref(false)
const isPulmo: any = ref(false)
const isAbdomen: any = ref(false)
const isExtremitas: any = ref(false)
const isLainnya: any = ref(false)
const confirm = useConfirm();
const { y } = useWindowScroll()
const currentStep = ref(0)
const disabledJawab: any = ref(false);
const userLogin = useUserSession().getUser()
const kelompokUser = route.query.kelompokuser ?? userLogin.kelompokUser.kelompokUser

const isStuck = computed(() => {
  return y.value > 30
})
const formattedInstruksi = computed(() => {
  return data_instruksi.value.map(item => {
    const instruksiLabel = item.instruksi ? item.instruksi.label : '';
    const intervensiLabel = item.intervensi ? item.intervensi.label : '';

    return `${instruksiLabel ? 'Instruksi: ' + instruksiLabel : ''}${(instruksiLabel && intervensiLabel) ? ', ' : ''}${intervensiLabel ? 'Intervensi: ' + intervensiLabel : ''}`;
  }).join('\n');// Join with new line for better formatting in the textarea
});
const isLoading = ref(false)
const listTemplateFix: any = ref([])
const data_instruksi: any = ref([])
const showModalTemplateFix: any = ref(false)


// ==================== Start List Data =================
const anamnesa = ref(EMR.anamnesa())
const anamnesa1 = ref(EMR.anamnesa1())
const listFaktorResJantung = ref(EMR.faktorResJantung())
const keadaanUmum = ref(EMR.keadaanUmum_1())
const pemeriksaanFisik = ref(EMR.pemeriksaanFisik())
const kesadaran = ref(EMR.kesadaran())
const descRangeKesadaran = ref(EMR.dscRangeKesadaran())
const keadaanUmum2 = ref(EMR.keadaanUmum_2())
const pemeriksaanPenunjang = ref(EMR.penunjang())
const dateAndDescrip = ref(EMR.dateAndDescrip())
const prognosis = ref(EMR.prognosis())
const lokalisGiziKlinik = ref(EMR.lokalisGiziKlinik())
const lokalisGigi = ref(EMR.lokalisGigi())
const lokalisPoliVCT = ref(EMR.lokalisPoliVCT())
const d_Diagnosa = ref([])
const listTemplate: any = ref([])

// ==================== End List Data ==================

let ListPSF = ref([
  { caption: "1. Mengontrol BAB" },
  { caption: "2. Mengontrol BAK" },
  { caption: "3. Membersihkan diri" },
  { caption: "4. Penggunaan toilet" },
  { caption: "5. Makan" },
  { caption: "6. Berpindah dari tidur ke duduk" },
  { caption: "7. Berjalan" },
  { caption: "8. Berpakaian" },
  { caption: "9. Naik turun tangga" },
  { caption: "10. Mandi" },
  { caption: "Total" },
])

let ListPenapisanDepresi = ref([
  { caption: "Apakah Anda sebenarnya puas dengan kehidupan anda?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah Anda telah meninggalkan banyak kegiatan dan minat atau kesenangan Anda?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda merasa bahwa hidup Anda kosong?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda sering merasa bosan?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda sangat berharap terhadap masa depan?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah Anda merasa terganggu dengan pikiran bahwa Anda tidak dapat keluar dari pikiran Anda?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda merasa mempunyai semangat yang baik setiap saat?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah Anda merasa takut bahwa sesuatu yang buruk akan terjadi pada diri Anda?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda merasa bahagia untuk sebagian besar hidup anda?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah Anda sering merasa tidak berdaya?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda sering merasa resah dan gelisah?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda lebih senang berada di rumah daripada keluar dan melakukan hal-hal baru?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda sering merasa khawatir dengan masa depan?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda merasa memiliki lebih banyak masalah dengan daya ingat dibandingkan kebanyakan orang?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah menurut Anda hidup Anda saat ini menyenangkan?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah Anda sering merasa sedih?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah saat ini Anda merasa tidak berharga?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda merasa khawatir tentang masa lalu Anda?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda merasa hidup ini sangat menarik dan menyenangkan?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah sulit bagi Anda untuk memulai sesuatu hal yang baru?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda merasa penuh semangat?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah Anda merasa bahwa keadaan Anda sekarang tidak ada harapan?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda merasa orang lain memiliki keadaan yang lebih baik dari Anda?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda sering merasa sedih atas hal-hal kecil?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda sering merasa ingin menangis?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda memiliki kesulitan berkonsentrasi?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah Anda senang ketika bangun di pagi hari?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah Anda lebih memilih menghindari pertemuan sosial atau bermasyarakat?", nilai0: "Tidak", nilai1: "Ya" },
  { caption: "Apakah mudah bagi Anda untuk membuat keputusan?", nilai0: "Ya", nilai1: "Tidak" },
  { caption: "Apakah pikiran Anda secerah biasanya?", nilai0: "Ya", nilai1: "Tidak" },
])

let listPI = ref([
  { caption: "Apakah anda mengompol atau BAB tanpa disadari" },
  { caption: "Tidak pernah (0)" },
  { caption: "Kadang-kadang kehilangan kontrol berkemih/menggunakan alat bantu untuk berkemih & BAB (1)" },
  { caption: "Kehilangan kontrol berkemih sedikitnya sekali dalam sebulan (2,5)" },
  { caption: "Kehilangan kontrol berkemih sedikitnya 2 kali sebulan/kadang-kadang kehilangan kontrol BAB (4)" },
  { caption: "Kehilangan kontrol BAB sedikitnya sekali dalam sebulan (5)" },
  { caption: "Kehilangan kontrol berkemih sedikitnya sekali dalam seminggu (5,5)" },
  { caption: "Kehilangan kontrol BAB sedikitnya 2 kali sebulan (6,5)" },
  { caption: "Kehilangan kontrol BAB sedikitnya sekali seminggu/kehilangan kontrol berkemih sedikitnya sekali setiap hari (8)" },
  { caption: "Kehilangan kontrol BAB sedikitnya sekali sehari (10)" },
  { caption: "Tidak bisa mengontrol fungsi berkemih sama sekali (10,5)" },
  { caption: "Tidak bisa mengontrol BAB sama sekali (11,5)" },
])

let listPDVT = ref([
  { caption: "Kanker aktif (dalam terapi atau paliatif) (1)", value: "1" },
  { caption: "Paralisis, paresis, atau imobilisasi ekstremitas bawah (1)", value: "1" },
  { caption: "Tirah baring lebih dari 3 hari karena pembedahan (dalam 4 bulan) (1)", value: "1" },
  { caption: "Nyeri tekan terlokalisasi sepanjang distribusi vena dalam (1)", value: "1" },
  { caption: "Pembengkakan seluruh tungkai (1)", value: "1" },
  { caption: "Bengkak pada betis unilateral lebih dari 3 cm (di bawah tuberositas tibia) (1)", value: "1" },
  { caption: "Edema pitting unilateral (1)", value: "1" },
  { caption: "Kolateral vena superfisial (1)", value: "1" },
  { caption: "Ada diagnosis alternatif lain selain DVT dengan kemungkinan sama atau lebih (-2)", value: "-2" },
])

let listPenapisanInsomnia = ref([
  { caption: "Sulit memulai tidur", cb0: "(0) tidak ada", cb1: "(1) ringan", cb2: "(2) sedang", cb3: "(3) berat", cb4: "(4) sangat berat" },
  { caption: "Sulit mempertahankan tidur", cb0: "(0) tidak ada", cb1: "(1) ringan", cb2: "(2) sedang", cb3: "(3) berat", cb4: "(4) sangat berat" },
  { caption: "Bangun dari tidur terlalu awal", cb0: "(0) tidak ada", cb1: "(1) ringan", cb2: "(2) sedang", cb3: "(3) berat", cb4: "(4) sangat berat" },
  { caption: "Kepuasan terhadap pola tidur saat ini", cb0: "(0) sangat puas", cb1: "(1) Puas", cb2: "(2) Sedikit puas", cb3: "(3) Tidak puas", cb4: "(4) Sangat tidak puas" },
  { caption: "Apakah gangguan tidur ini mempengaruhi kualitas hidup anda", cb0: "(0) Tidak jelas", cb1: "(1) Sedikit", cb2: "(2) Kadang-kadang", cb3: "(3) Jelas", cb4: "(4) Sangat jelas" },
  { caption: "Apakah anda mengkhawatirkan gangguan tidur anda saat ini", cb0: "(0) tidak", cb1: "(1) Sedikit", cb2: "(2) Kadang-kadang", cb3: "(3) Khawatir", cb4: "(4) Sangat khawatir" },
  { caption: "Apakah gangguan tidur anda mempengaruhi aktivitas/fungsi anda sehari-hari", cb0: "(0) tidak", cb1: "(1) Sedikit", cb2: "(2) Kadang-kadang", cb3: "(3) Banyak mengganggu", cb4: "(4) Sangat mengganggu" }
])

function kepala() {
  isKepala.value = true
  isMata.value = false
  isTHT.value = false
  isLeher.value = false
  isThorax.value = false
  isCor.value = false
  isPulmo.value = false
  isAbdomen.value = false
  isExtremitas.value = false
  isLainnya.value = false
}

function mata() {
  isKepala.value = false
  isMata.value = true
  isTHT.value = false
  isLeher.value = false
  isThorax.value = false
  isCor.value = false
  isPulmo.value = false
  isAbdomen.value = false
  isExtremitas.value = false
  isLainnya.value = false
}

function tht() {
  isKepala.value = false
  isMata.value = false
  isTHT.value = true
  isLeher.value = false
  isThorax.value = false
  isCor.value = false
  isPulmo.value = false
  isAbdomen.value = false
  isExtremitas.value = false
  isLainnya.value = false
}

function leher() {
  isKepala.value = false
  isMata.value = false
  isTHT.value = false
  isLeher.value = true
  isThorax.value = false
  isCor.value = false
  isPulmo.value = false
  isAbdomen.value = false
  isExtremitas.value = false
  isLainnya.value = false
}

function thorax() {
  isKepala.value = false
  isMata.value = false
  isTHT.value = false
  isLeher.value = false
  isThorax.value = true
  isCor.value = false
  isPulmo.value = false
  isAbdomen.value = false
  isExtremitas.value = false
  isLainnya.value = false
}

function cor() {
  isKepala.value = false
  isMata.value = false
  isTHT.value = false
  isLeher.value = false
  isThorax.value = false
  isCor.value = true
  isPulmo.value = false
  isAbdomen.value = false
  isExtremitas.value = false
  isLainnya.value = false
}

function pulmo() {
  isKepala.value = false
  isMata.value = false
  isTHT.value = false
  isLeher.value = false
  isThorax.value = false
  isCor.value = false
  isPulmo.value = true
  isAbdomen.value = false
  isExtremitas.value = false
  isLainnya.value = false
}

function abdomen() {
  isKepala.value = false
  isMata.value = false
  isTHT.value = false
  isLeher.value = false
  isThorax.value = false
  isCor.value = false
  isPulmo.value = false
  isAbdomen.value = true
  isExtremitas.value = false
  isLainnya.value = false
}

function extremitas() {
  isKepala.value = false
  isMata.value = false
  isTHT.value = false
  isLeher.value = false
  isThorax.value = false
  isCor.value = false
  isPulmo.value = false
  isAbdomen.value = false
  isExtremitas.value = true
  isLainnya.value = false
}

function lainnya() {
  isKepala.value = false
  isMata.value = false
  isTHT.value = false
  isLeher.value = false
  isThorax.value = false
  isCor.value = false
  isPulmo.value = false
  isAbdomen.value = false
  isExtremitas.value = false
  isLainnya.value = true
}

var tampil = 1;
function semua() {
  if (tampil == 1) {
    isKepala.value = true
    isMata.value = true
    isTHT.value = true
    isLeher.value = true
    isThorax.value = true
    isCor.value = true
    isPulmo.value = true
    isAbdomen.value = true
    isExtremitas.value = true
    isLainnya.value = true
    tampil = tampil - 1;
    return tampil;
  } else {
    isKepala.value = true
    isMata.value = false
    isTHT.value = false
    isLeher.value = false
    isThorax.value = false
    isCor.value = false
    isPulmo.value = false
    isAbdomen.value = false
    isExtremitas.value = false
    isLainnya.value = false
    tampil = tampil + 1
    return tampil;
  }
}

function normalKepala() {
  if (input.value.batasnormalkepala == false) {
    input.value.kepala = "Kepala"
    input.value.ketKepala = "Normal"
    input.value.normal = "Normal"
  } else {
    input.value.kepala = undefined
    input.value.ketKepala = undefined
    input.value.normal = undefined
  }
}

function normalKestrad() {
  if (input.value.batasnormalkestrad == false) {
    input.value.kepalakestrad = "Dalam Batas Normal"
    input.value.jantungkestrad = "Dalam Batas Normal"
    input.value.matakestrad = "Dalam Batas Normal"
    input.value.parukestrad = "Dalam Batas Normal"
    input.value.thtkestrad = "Dalam Batas Normal"
    input.value.perutkestrad = "Dalam Batas Normal"
    input.value.leherkestrad = "Dalam Batas Normal"
    input.value.extremitaskestrad = "Dalam Batas Normal"
    input.value.thorakskestrad = "Dalam Batas Normal"
    input.value.lainkestrad = "Dalam Batas Normal"
  } else {
    input.value.kepalakestrad = undefined
    input.value.jantungkestrad = undefined
    input.value.matakestrad = undefined
    input.value.parukestrad = undefined
    input.value.thtkestrad = undefined
    input.value.perutkestrad = undefined
    input.value.leherkestrad = undefined
    input.value.extremitaskestrad = undefined
    input.value.thorakskestrad = undefined
    input.value.lainkestrad = undefined
  }
}

function respiratory() {
  // console.log('respiratory', input.value.wnlrespiratory)
  // if (input.value.wnlrespiratory == false) {
  //     disrespiratory.value = true
  // } else {
  //     disrespiratory.value = false
  // }
  // console.log('disabled', disrespiratory.value)
}

function tidakada() {
  if (input.value.tidakadaresep == false) {
    gaadaresep.value = true
  } else {
    gaadaresep.value = false
  }
}

function normalMata() {
  if (input.value.batasnormalmata == false) {
    input.value.anemis = "Anemis"
    input.value.ikterus = "Ikterus"
    input.value.refpupil = "Refleks Pupil"
    input.value.oedemapal = "Oedema Palpebrae"
    input.value.ketAnemis = "-/-"
    input.value.ketIkterus = "-/-"
    input.value.ketRefpupil = "+/+"
    input.value.ketOedemapal = "-/-"
  } else {
    input.value.anemis = undefined
    input.value.ikterus = undefined
    input.value.refpupil = undefined
    input.value.oedemapal = undefined
    input.value.ketAnemis = undefined
    input.value.ketIkterus = undefined
    input.value.ketRefpupil = undefined
    input.value.ketOedemapal = undefined
  }

}

function normalTHT() {
  if (input.value.batasnormaltht == false) {
    input.value.tonsil = "Tonsil"
    input.value.ketTonsil = "Normal"
    input.value.pharing = "Pharing"
    input.value.ketPharing = "Normal"
    input.value.telinga = "Telinga"
    input.value.ketTelinga = "Normal"

    input.value.hidung = "Hidung"
    input.value.ketHidung = "Normal"
    input.value.bibir = "Bibir"
    input.value.ketBibir = "Normal"
    input.value.lidah = "Lidah"
    input.value.ketLidah = "Normal"

    input.value.lainlain = "Lain-Lain"
    input.value.ketLain = "Normal"
  } else {
    input.value.tonsil = undefined
    input.value.ketTonsil = undefined
    input.value.pharing = undefined
    input.value.ketPharing = undefined
    input.value.telinga = undefined
    input.value.ketTelinga = undefined

    input.value.hidung = undefined
    input.value.ketHidung = undefined
    input.value.bibir = undefined
    input.value.ketBibir = undefined
    input.value.lidah = undefined
    input.value.ketLidah = undefined

    input.value.lainlain = undefined
    input.value.ketLain = undefined
  }
}

function normalLeher() {
  if (input.value.batasnormalleher == false) {
    input.value.jvp = "JVP"
    input.value.ketJVP = "Normal"
    input.value.lainnya = "Lainnya"
    input.value.ketLainnya = "Normal"
  } else {
    input.value.jvp = undefined
    input.value.ketJVP = undefined
    input.value.lainnya = undefined
    input.value.ketLainnya = undefined
  }
}

function normalThorax() {
  if (input.value.batasnormalthorax == false) {
    input.value.simetris = "Simetris"
    input.value.ketSimetris = "Simetris"
  } else {
    input.value.simetris = undefined
    input.value.ketSimetris = undefined
  }
}

function normalPulmo() {
  if (input.value.batasnormalpulmo == false) {
    input.value.auskultasi = "Auskultasi_Vesikuler"
    input.value.ketAuskultasi = "+/+"
    input.value.ronchi = "Ronchi"
    input.value.ketRonchi = "-/-"
    input.value.wheezing = "Auskultasi_Wheezing"
    input.value.ketWheezing = "-/-"
  } else {
    input.value.auskultasi = undefined
    input.value.ketAuskultasi = undefined
    input.value.ronchi = undefined
    input.value.ketRonchi = undefined
    input.value.wheezing = undefined
    input.value.ketWheezing = undefined
  }
}

function normalAbdomen() {
  console.log(input.value.ketPeristaltik)
  if (input.value.batasnormalabdomen == false) {
    input.value.ketPeristaltik = 1
    input.value.souffle = "Souffle"
    input.value.ketSouffle =
      input.value.nyeri = "Nyeri Tekan Lokasi"
    input.value.ketNyeri = "Tidak Ada"
    input.value.lien = "Lien"
    input.value.ketLien = "Tidak Teraba"
    input.value.hepar = "Hepar"
    input.value.ketHepar = "Tidak Teraba"
  } else {
    input.value.ketPeristaltik = undefined
    input.value.souffle = undefined
    input.value.ketSouffle = undefined
    input.value.nyeri = undefined
    input.value.ketNyeri = undefined
    input.value.lien = undefined
    input.value.ketLien = undefined
    input.value.hepar = undefined
    input.value.ketHepar = undefined
  }
}

function normalExtremitas() {
  if (input.value.batasnormalextremitas == false) {
    input.value.ketExtremitas = 1
    input.value.odema = "Odema"
    input.value.ketOdema = "Tidak ada"
  } else {
    input.value.ketExtremitas = undefined
    input.value.odema = undefined
    input.value.ketOdema = undefined
  }
}

function semuanormal() {
  if (input.value.semuanormal == false) {
    input.value.batasnormalkepala = false,
      input.value.batasnormalmata = false,
      input.value.batasnormaltht = false,
      input.value.batasnormalleher = false,
      input.value.batasnormalthorax = false,
      input.value.batasnormalpulmo = false,
      input.value.batasnormalabdomen = false,
      input.value.batasnormalextremitas = false,
      input.value.lokaliskulit = false,
      input.value.normalcor = false,

      normalKepala()
    normalMata()
    normalTHT()
    normalLeher()
    normalThorax()
    normalPulmo()
    normalAbdomen()
    normalExtremitas()
    normalEKGCor()
  } else {
    input.value.batasnormalkepala = true,
      input.value.batasnormalmata = true,
      input.value.batasnormaltht = true,
      input.value.batasnormalleher = true,
      input.value.batasnormalthorax = true,
      input.value.batasnormalpulmo = true,
      input.value.batasnormalabdomen = true,
      input.value.batasnormalextremitas = true,
      input.value.lokaliskulit = true,
      input.value.normalcor = true,

      normalKepala()
    normalMata()
    normalTHT()
    normalLeher()
    normalThorax()
    normalPulmo()
    normalAbdomen()
    normalExtremitas()
    normalEKGCor()

    setDefaultNormal()
  }



}

function setDefaultNormal() {
  input.value.batasnormalkepala = false,
    input.value.batasnormalmata = false,
    input.value.batasnormaltht = false,
    input.value.batasnormalleher = false,
    input.value.batasnormalthorax = false,
    input.value.batasnormalpulmo = false,
    input.value.batasnormalabdomen = false,
    input.value.batasnormalextremitas = false,
    input.value.lokaliskulit = false,
    input.value.normalcor = false
}

function normallokaliskulit() {
  console.log(input.value.lokaliskulit)
  if (input.value.lokaliskulit == false) {
    input.value.lokasiKulit = "Normal"
    input.value.kelainanKulit = "Normal"
    input.value.stigmata = "Normal"
    input.value.mukosa = "Normal"
    input.value.rambut = "Normal"
    input.value.kuku = "Normal"
    input.value.kelenjarKeringat = "Normal"
    input.value.kelenjarLimfe = "Normal"
    input.value.saraf = "Normal"
  } else {
    input.value.lokasiKulit = undefined
    input.value.kelainanKulit = undefined
    input.value.stigmata = undefined
    input.value.mukosa = undefined
    input.value.rambut = undefined
    input.value.kuku = undefined
    input.value.kelenjarKeringat = undefined
    input.value.kelenjarLimfe = undefined
    input.value.saraf = undefined
  }
}


function normallokalistht() {
  console.log(input.value.lokalistht)
  if (input.value.lokalistht == false) {
    input.value.suarabisik = "Normal"
    input.value.rinnekiri = "Normal"
    input.value.rinnekanan = "Normal"
    input.value.weberkiri = "Normal"
    input.value.weberkanan = "Normal"
  } else {
    input.value.suarabisik = undefined
    input.value.rinnekiri = undefined
    input.value.rinnekanan = undefined
    input.value.weberkiri = undefined
    input.value.weberkanan = undefined
  }
}

function normallokalissaraf() {
  console.log(input.value.lokalissaraf)
  if (input.value.lokalissaraf == false) {
    input.value.kranium = "Dalam Batas Normal"
    input.value.vertabra = "Dalam Batas Normal"
    input.value.selaputotak = "Dalam Batas Normal"
    input.value.sarafotak = "Dalam Batas Normal"
    input.value.motorik = "Dalam Batas Normal"
    input.value.refleks = "Dalam Batas Normal"
    input.value.sensorik = "Dalam Batas Normal"
    input.value.vegetatif = "Dalam Batas Normal"
    input.value.luhur = "Dalam Batas Normal"
    input.value.tandamental = "Dalam Batas Normal"
    input.value.nyeritekansaraf = "Dalam Batas Normal"
    input.value.lasegue = "Dalam Batas Normal"
    input.value.lainlain = "Dalam Batas Normal"
  } else {
    input.value.kranium = undefined
    input.value.vertabra = undefined
    input.value.selaputotak = undefined
    input.value.sarafotak = undefined
    input.value.motorik = undefined
    input.value.refleks = undefined
    input.value.sensorik = undefined
    input.value.vegetatif = undefined
    input.value.luhur = undefined
    input.value.tandamental = undefined
    input.value.nyeritekansaraf = undefined
    input.value.lasegue = undefined
    input.value.lainlain = undefined
  }
}

function normalOR() {
  if (input.value.batasNormalOR == false) {
    input.value.Anemi = "Tidak"
    input.value.Sianosis = "Tidak"
    input.value.Icterus = "Tidak"
    input.value.Turgor = "Baik"
    input.value.PenglihatanKa = "Normal"
    input.value.PenglihatanKi = "Normal"
    input.value.refleksiPupilKa = "Ya"
    input.value.refleksiPupilKi = "Ya"
    input.value.pupil = "Isokor"
    input.value.diplopia = "Tidak"
    input.value.TelingaKa = "Normal"
    input.value.TelingaKi = "Normal"
    input.value.ObstetriKa = "Ya"
    input.value.ObstetriKi = "Tidak"
    input.value.GigiGeligi = "Normal"
    input.value.MukosaMulut = "Tidak Hiperemis"
    input.value.LidahOR = "Normal"
    input.value.Thyroid = "Tidak membesar"
    input.value.TonsilKa = "T1"
    input.value.TonsilKi = "T1"
    input.value.TekananVena = "Tidak"
    input.value.pergerakanPernafasanKa = "Teratur"
    input.value.pergerakanPernafasanKi = "Teratur"
    input.value.pergerakanPernafasanKaKi = "Simetris"
    input.value.perkusiKanan = "Sonor"
    input.value.perkusiKiri = "Sonor"
    input.value.AustulkasiKanan = "Vesikuler"
    input.value.AustulkasiKiri = "Vesikuler"
    input.value.membesar = "Tidak"
    input.value.bunyiJantung = "8/18/2 Reguler"
    input.value.auskultasiJantung = "Murmur - Gallop -"
    input.value.Nyeri = "Tidak"
    input.value.Ascites = "Tidak"
    input.value.Tumor = "Tidak"
    input.value.hatiLimva = "Tidak teraba membesar"
    input.value.Kifosis = "Tidak"
    input.value.Skollosis = "Tidak"
    input.value.NyeriTekanantulangPunggung = "Tidak"
    input.value.NyeriTekananPelvis = "Tidak"
    input.value.Edema = "Ya"
    input.value.Varices = "Tidak"
    input.value.NyeriTekananExtermitas = "Tidak"
    input.value.ReflekPatologis = "Tidak"
  } else {
    input.value.Anemi = undefined;
    input.value.Sianosis = undefined;
    input.value.Icterus = undefined;
    input.value.Turgor = undefined;
    input.value.PenglihatanKa = undefined;
    input.value.PenglihatanKI = undefined;
    input.value.refleksiPupilKa = undefined;
    input.value.refleksiPupilKi = undefined;
    input.value.pupil = undefined;
    input.value.diplopia = undefined;
    input.value.TelingaKa = undefined;
    input.value.TelingaKi = undefined;
    input.value.ObstetriKa = undefined;
    input.value.ObstetriKi = undefined;
    input.value.GigiGeligi = undefined;
    input.value.MukosaMulut = undefined;
    input.value.LidahOR = undefined;
    input.value.Thyroid = undefined;
    input.value.TonsilKa = undefined;
    input.value.TonsilKi = undefined;
    input.value.TekananVena = undefined;
    input.value.pergerakanPernafasanKa = undefined;
    input.value.pergerakanPernafasanKi = undefined;
    input.value.pergerakanPernafasanKaKi = undefined;
    input.value.perkusiKanan = undefined;
    input.value.perkusiKiri = undefined;
    input.value.AustulkasiKanan = undefined;
    input.value.AustulkasiKiri = undefined;
    input.value.membesar = undefined;
    input.value.bunyiJantung = undefined;
    input.value.auskultasiJantung = undefined;
    input.value.Nyeri = undefined;
    input.value.Ascites = undefined;
    input.value.Tumor = undefined;
    input.value.hatiLimva = undefined;
    input.value.Kifosis = undefined
    input.value.Skollosis = undefined
    input.value.NyeriTekanantulangPunggung = undefined
    input.value.NyeriTekananPelvis = undefined;
    input.value.Edema = undefined;
    input.value.Varices = undefined;
    input.value.NyeriTekananExtermitas = undefined;
    input.value.ReflekPatologis = undefined;
  }
}


function normalEKGCor() {
  if (input.value.normalcor == false) {
    input.value.cornormal = 'Normal'
    input.value.apex = 'Apex'
    input.value.s1s2 = 'S1, S2'
    input.value.batasatas = 'Batas Atas'
    input.value.ketBatasatas = 'ICS II'
    input.value.batasbawah = 'Batas Bawah'
    input.value.ketBatasbawah = 'ICS IV'
    input.value.bataskanan = 'Batas Kanan'
    input.value.ketBataskanan = 'PSL D'
    input.value.bataskiri = 'Batas Kiri'
    input.value.ketBataskiri = 'PSL D'
    input.value.ketTunggal = "Tunggal"
    input.value.regular = "Regular"
    input.value.murmur = "Murmur"
    input.value.ketMurmur = "Tidak ada"
    input.value.palpasinormal = 'Normal'
  } else {
    input.value.cornormal = undefined;
    input.value.apex = undefined;
    input.value.s1s2 = undefined;
    input.value.batasatas = undefined;
    input.value.ketBatasatas = undefined;
    input.value.batasbawah = undefined;
    input.value.ketBatasbawah = undefined;
    input.value.bataskanan = undefined;
    input.value.ketBataskanan = undefined;
    input.value.bataskiri = undefined;
    input.value.ketBataskiri = undefined;
    input.value.ketTunggal = undefined;
    input.value.regular = undefined;
    input.value.murmur = undefined;
    input.value.ketMurmur = undefined;
    input.value.palpasinormal = undefined;
  }
}

function tambahDiagnosa10() {
  item.tglpelayanann = new Date()
  modalInput.value = true
}

async function dropdownList() {
  useApi().get(
    `/diagnosa/list-dropdown`).then((response: any) => {
      d_JenisDiagnosa.value = response.jenisdiagnosa.map((e: any) => { return { label: e.jenisdiagnosa, value: e.id, default: e } })
    })

  d_Ruangan.value = await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan&settingdatafix=objectdepartemenfk,kdDepartemenRawatJalanFix,kdDepartemenRanapFix`)
  console.log("ruangan ", d_Ruangan);
}

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
    console.log(d_Dokter.value)
  })
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`).then((response) => {
    d_Ruangan.value = response
  })
}

const validateStep = async () => {
  if (currentStep.value === 4) {
    if (isLoading.value) {
      return
    }

    isLoading.value = true

    return
  }

  isLoading.value = true
  await sleep(400)
  currentStep.value += 1

  nextTick(() => {
    scrollTo(`#form-step-${currentStep.value}`, 1000)
    isLoading.value = false
  })
}

function highlightMatch(text) {
  if (!filterMenu.value) return text;

  const term = new RegExp(`(${filterMenu.value})`, 'gi');
  return text.replace(term, '<span style="background-color: yellow;">$1</span>');
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

function hapusItems(e: any) {
  useApi().post(
    `/diagnosa/delete-diagnosa-x`, { norec: e.norec_diagnosapasien }).then((response: any) => {
      useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
          element.no = i + 1
          element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD10.value = response
        isLoading.value = false
      })
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const DialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusItems(e)

    },
    reject: () => { },
  })
}

const diagnosa = async () => {
  await useApi().get(`emr/get-diagnosa-pasien-icd9?nocmfk=${ID_PASIEN}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
    });
    dataSourceICD9.value = response
    // console.log(response)
  })
  await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
    });
    dataSourceICD10.value = response
  })
}

const catatanPasien = async () => {
  modalConfirmObgyn.value = true
}

const getTindakan = () => {
  isLoading.value = true;
  let stringTindakan = '';
  useApi().get(
    `/kasir/billing?norec_pd=${item.NOREC_PD}&istindakan=true`).then(async (response: any) => {
      isLoading.value = false;
      if (response.detail.length > 0 && response.detail[0].details.length > 0) {
        let details = response.detail[0].details;
        if (input.value.instruksiAsesmen != undefined || input.value.instruksiAsesmen != null) {
          stringTindakan = '\nTindakan : \n'
        } else {
          stringTindakan = 'Tindakan : \n'
        }

        details.forEach(elT => {
          stringTindakan += `# ${elT.namaproduk} `
        });

        if (input.value.instruksiAsesmen != undefined || input.value.instruksiAsesmen != null) {
          input.value.instruksiAsesmen += stringTindakan;
        } else {
          input.value.instruksiAsesmen = stringTindakan
        }
      } else {
        H.alert('warning', 'Belum ada Tindakan');
      }
    })
}

const getObat = async (index: any) => {
  isLoading.value = true;
  let resObat = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${item.NOREC_PD}`)
  if (resObat.length > 0) {
    isLoading.value = false;
    let filterNoreg = resObat.filter((dt) => {
      return dt.noregistrasi == props.registrasi.noregistrasi;
    });
    if (filterNoreg.length > 0) {
      let stringObat = ''
      if (input.value.instruksiAsesmen != undefined || input.value.instruksiAsesmen != null) {
        stringObat = '\nObat : \n'
      } else {
        stringObat = 'Obat : \n'
      }


      filterNoreg[0].details.forEach(elO => {
        stringObat += `# ${elO.namaproduk} `;
      });

      if (input.value.instruksiAsesmen != undefined || input.value.instruksiAsesmen != null) {
        input.value.instruksiAsesmen += stringObat;
      } else {
        input.value.instruksiAsesmen = stringObat
      }
    } else {
      isLoading.value = false;
      H.alert('warning', 'Belum ada Obat');
    }

  } else {
    isLoading.value = false;
    H.alert('warning', 'Belum ada Obat');
  }

}

const getLabo = () => {
  isLoading.value = true
  // let stringLabo = 'Labora';
  let uri = `laboratorium/riwayat-order?nocmfk=${props.pasien.nocmfk}&norec_pd=${item.NOREC_PD}`

  useApi().get(uri).then((res) => {
    let hasilLab = '';
    if (res.length > 0) {
      for (let index = 0; index < res.length; index++) {
        const element = res[index];
        if (element.details && element.details.length > 0) {
          for (let i = 0; i < element.details.length; i++) {
            const detail = element.details[i];
            hasilLab += '# ' + detail.namaproduk + ' ';
          }
        }
      }
      if (input.value.hasilpemeriksaanpenunjang != undefined || input.value.hasilpemeriksaanpenunjang != null) {
        input.value.hasilpemeriksaanpenunjang += '\nLaboratorium : ' + hasilLab;
      } else {
        input.value.hasilpemeriksaanpenunjang = 'Laboratorium : ' + hasilLab;
      }
      H.alert('success', 'Berhasil ditambahkan')
    } else {
      H.alert('warning', 'Belum ada riwayat')
    }
    isLoading.value = false
  })
}

const transferPasien = async () => {
  disabledJawab.value = true
  isLoading.value = true
  inputTP.value.tanggal = new Date()
  console.log("INPUTTP", props);
  NORECTP = ''
  d_Ruangan.value.forEach((element) => {
    if (element.value == props.registrasi.objectruanganlastfk) {
      inputTP.value.ruanganasal = element
    }
  });
  inputTP.value.namadokter = props.registrasi.dokter
  inputTP.value.dokter = props.registrasi.objectpegawaifk
  await fetchKelas({ query: 'NON KELAS' })
  if (d_Kelas.value.length) {
    inputTP.value.kelas = d_Kelas.value[0]
  }
  isLoading.value = false
  modalInputTP.value = true
}

const postTransferPasien = async () => {
  if (!inputTP.value.tanggal) {
    H.alert('error', 'Tanggal harus di isi')
    return
  }
  if (!inputTP.value.ruanganasal) {
    H.alert('error', 'Ruang Asal harus di isi')
    return
  }
  if (!inputTP.value.ruangantujuan) {
    H.alert('error', 'Ruang Tujuan harus di isi')
    return
  }
  // if (!inputTP.dokter) {
  //     H.alert('error', 'Dokter harus di isi')
  //     return
  // }
  if (!inputTP.value.kelas) {
    H.alert('error', 'Kelas Konsultasi Harus di isi')
    return
  }
  let object = {
    "norec_pd": props.registrasi.norec_pd,
    "asalRujukanfk": 23,
    "norec": NORECTP,
    //"noAntrian": dataSource.value.length + 1,
    "dokterfk": inputTP.value.dokter,
    "objectruanganasalfk": inputTP.value.ruanganasal.value,
    "objectruangantujuan": inputTP.value.ruangantujuan.value,
    "kelasfk": inputTP.value.kelas.value,
  }
  // console.log("data kirim", object);
  // return;
  isLoading.value = true
  await useApi().post('registrasi/simpan-pasien-konsul', object).then((response) => {
    isLoading.value = false

    if (input.value.instruksiAsesmen != undefined || input.value.instruksiAsesmen != null) {
      let words = `\nKonsul : ${inputTP.value.ruangantujuan.label} Kepada:\nYth.TS ${inputTP.value.ruangantujuan.label} Di RSBM DH,\nMenghadapakan pasien Di atas dengan diagnosa ${input.value.diagnosaIcd10 ?? '...'}. Pasien saya rencanakan tindakan ... . Mohon evaluasi Di bidang TS.`
      input.value.instruksiAsesmen += words;
    } else {
      let words = `Konsul : ${inputTP.value.ruangantujuan.label} Kepada:\nYth.TS ${inputTP.value.ruangantujuan.label} Di RSBM DH,\nMenghadapakan pasien Di atas dengan diagnosa ${input.value.diagnosaIcd10 ?? '...'}. Pasien saya rencanakan tindakan ... . Mohon evaluasi Di bidang TS.`
      input.value.instruksiAsesmen = words;
      // input.value.instruksiAsesmen = 'Pasien ditransferkan dari Ruangan : ' + inputTP.value.ruanganasal.label + ' ke ' + inputTP.value.ruangantujuan.label + ' oleh ' + inputTP.value.namadokter;
    }
    H.alert('success', 'Berhasil ditambahkan')

    modalInputTP.value = false
    sendNotification(r)

    // loadRiwayat()
  }).catch((err) => {
    isLoading.value = false
  })

}

const fetchKelas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Kelas.value = response
  })
}

const getRadio = () => {
  isLoading.value = true
  // let stringLabo = 'Labora';
  let uri = `radiologi/layanan-radiologi?norec_pd=${item.NOREC_PD}`;

  useApi().get(uri).then((res) => {
    let hasilRadio = '';
    if (res && res.detail.length > 0) {
      for (let index = 0; index < res.detail.length; index++) {
        const group = res.detail[index];
        if (group.details.length > 0) {
          for (let i = 0; i < group.details.length; i++) {
            const detail = group.details[i];
            if (detail.hasil != null) {
              hasilRadio += '# ' + detail.hasil + ' ';
            }
          }
        }
      }
      if (input.value.hasilpemeriksaanpenunjang != undefined || input.value.hasilpemeriksaanpenunjang != null) {
        input.value.hasilpemeriksaanpenunjang += '\nRadiologi : ' + hasilRadio;
      } else {
        input.value.hasilpemeriksaanpenunjang = 'Radiologi : ' + hasilRadio;
      }
      H.alert('success', 'Berhasil ditambahkan')
    } else {
      H.alert('warning', 'Belum ada riwayat')
    }
    isLoading.value = false
  })
}

const addDiagnosaDok = async (data: any) => {
  input.value.diagnosaDokter.push({
    no: input.value.diagnosaDokter[input.value.diagnosaDokter.length - 1].no + 1,
    norecDiagnosa: '',
  });
}

const removeDiagnosaDok = async (data: any, index) => {
  data.isLoadBtnDiagnosaDokter = true
  if (data.norecDiagnosa) {
    await useApi().post(`/diagnosa/delete-diagnosa-x`, { norec: data.norecDiagnosa }).then((response: any) => {
      data.isLoadBtnDiagnosaDokter = false
      riwayatDiagnosa10()
    }).catch((e: any) => {

    })
  } else {
    input.value.diagnosaDokter.splice(index, 1)
  }
}

async function checkAsmed() {
  if (kelompokUser && kelompokUser.toUpperCase() != 'DOKTER') {
    return;
  }
  let params = `?norec_pd=${props.registrasi.norec_pd}&namaruangan=${props.registrasi.namaruangan}`
  let uri = `/emr/check-assesmen-medis${params}`;
  await useApi().get(uri).then((res) => {
    if (res) {
      isAssesmenMedis.value = res;
    }
  })
}

const loadRiwayat = async () => {
  isLoading.value = true;

  try {
    // Coba ambil riwayat terlebih dahulu
    let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`);

    if (responsex.length) {
      // Jika ada riwayat, load riwayat tersebut
      if (isAssesmenMedis.value == false || paramEdit == true) {
        input.value = responsex[0]; // set ke inputan
        jenisObgyn.value = responsex[0].jenisObgyn;
        jenisInterna.value = responsex[0].jenisInterna;
        jenisTrauma.value = responsex[0].jenisTrauma;

        if (input.value.kondisiVCT) {
          const matchingIndex = children.value.findIndex(child => child.label === input.value.kondisiVCT);
          if (matchingIndex !== -1) {
            input.value[`kondisiVCT${matchingIndex}`] = children.value[matchingIndex].label;
          }
        }

        if (input.value.aidsVCT) {
          const matchingIndex = children2.value.findIndex(child => child.label === input.value.aidsVCT);
          if (matchingIndex !== -1) {
            input.value[`aidsVCT${matchingIndex}`] = children2.value[matchingIndex].label;
          }
        }

        data_instruksi.value = responsex[0].instruksi;

        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = responsex[0].emrpasienfk;
        }

        H.alert('info', 'Data berhasil dimuat');
        await loadGambar("canvasmata", input.value.canvasmata);
      }
    } else {
      // Jika tidak ada riwayat, jalankan autofill
      AutoFill_Input()
      await setAutoFill();
    }
  } catch (err) {
    console.log("ERROR ASMED AUTOFILL", err);
  } finally {
    isLoading.value = false;
  }
}

const setAutoFill = async () => {
  return new Promise(async (resolve, reject) => {
    try {
      if (namaRuanganDinamis.toUpperCase().indexOf('IGD') > -1) {
        let fieldIGD = "TBeGCS,TBvGCS,TBmGCS,TBcelciusTTV,TBPernafasanTTV,TBberatBadanTTV,TBnadiTTV,TBtekananDarahTTV,TBnspo2TTV,TBtinggiBadanTTV,keadaanumum";
        let res = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=TriagePasienIGD" + "&field=" + fieldIGD);

        if (res) {
          let data = '';
          dataNurse.value = {
            tinggiBadan: res.TBtinggiBadanTTV ?? '',
            beratBadan: res.TBberatBadanTTV ?? '',
          };
          data += res.TBcelciusTTV ? `Suhu : ${res.TBcelciusTTV} °C\n` : '';
          data += res.TBnadiTTV ? `Nadi : ${res.TBnadiTTV} x/mnt\n` : '';
          data += res.TBPernafasanTTV ? `Pernafasan : ${res.TBPernafasanTTV} x/mnt\n` : '';
          data += res.TBtekananDarahTTV ? `Tekanan Darah : ${res.TBtekananDarahTTV} mmHg\n` : '';
          data += res.TBtinggiBadanTTV ? `Tinggi Badan : ${res.TBtinggiBadanTTV} Cm\n` : '';
          data += res.TBberatBadanTTV ? `Berat Badan : ${res.TBberatBadanTTV} Kg\n` : '';
          data += res.TBnspo2TTV ? `SPO2 : ${res.TBnspo2TTV} %\n` : '';

          input.value.tekananDarah = res.TBtekananDarahTTV;
          input.value.nadi = res.TBnadiTTV;
          input.value.nafas = res.TBPernafasanTTV;
          input.value.celcius = res.TBcelciusTTV;
          input.value.sao2 = res.TBnspo2TTV;
          input.value.gcse = res.TBeGCS;
          input.value.gcsv = res.TBvGCS;
          input.value.gcsm = res.TBmGCS;
          input.value.keadaanumum = res.keadaanumum;
        }
      } else {
        let response = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,keadaanumumobgyn,keadaanumum,pernapasan,suhu,nadi,beratBadan,SPO2");

        if (response == null) {
          response = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn");

          if (response == null) {
            response = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKebidananRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn");
          }
        }

        if (response) {
          let data = '';
          let datax = '';
          dataNurse.value = {
            tinggiBadan: response.tinggiBadan ?? '',
            beratBadan: response.beratBadan ?? '',
          };
          data += response.suhu ? `Suhu : ${response.suhu} °C\n` : '';
          data += response.nadi ? `Nadi : ${response.nadi} x/mnt\n` : '';
          data += response.pernapasan ? `Pernafasan : ${response.pernapasan} x/mnt\n` : '';
          data += response.tekananDarah ? `Tekanan Darah : ${response.tekananDarah} mmHg\n` : '';
          data += response.tinggiBadan ? `Tinggi Badan : ${response.tinggiBadan} Cm\n` : '';
          data += response.beratBadan ? `Berat Badan : ${response.beratBadan} Kg\n` : '';
          data += response.SPO2 ? `SPO2 : ${response.SPO2} %\n` : '';
          data += response.IMT ? `IMT : ${response.IMT}\n` : '';

          datax += response.keluhanutama ? `Keluhan Utama : ${response.keluhanutama}\n` : 'Keluhan Utama : -\n';
          datax += response.riwayatpenyakit ? `Riwayat Penyakit : ${response.riwayatpenyakit}\n` : 'Riwayat Penyakit : -\n';
          datax += response.riwayatpenyakitdahulu ? `Riwayat penyakit dahulu : ${response.riwayatpenyakitdahulu}\n` : 'Riwayat penyakit dahulu : -\n';
          datax += response.riwayatpengobatan ? `Riwayat pengobatan : ${response.riwayatpengobatan}\n` : 'Riwayat pengobatan : -\n';
          datax += response.riwayatpenyakitkeluarga ? `Riwayat penyakit keluarga : ${response.riwayatpenyakitkeluarga}\n` : 'Riwayat penyakit keluarga : -\n';
          datax += response.riwayatalergi ? `Riwayat alergi : ${response.riwayatalergi}\n` : 'Riwayat alergi : -\n';

          input.value.tekananDarah = response.tekananDarahObgyn;
          input.value.nadi = response.nadiObgyn;
          input.value.nafas = response.nafasObgyn;
          input.value.celcius = response.celciusObgyn;
          input.value.sao2 = response.sao2Obgyn;
          input.value.keadaanumum = response.keadaanumumobgyn;
          input.value.kebpilihanallo = response.keballoanamnesis;
          input.value.anamnesis = datax;
        }
      }

      resolve(true);
    } catch (err) {
      reject(err);
      console.log('ERR', err);
    }
  });
};

const pilihTemplate = async (index: any) => {
  isLoading.value = true;
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    .then((responselast: any) => {
      isLoading.value = false;
      if (responselast.length) {
        listTemplate.value = responselast;
        showModalTemplate.value = true;
      } else {
        H.alert('warning', 'Data tidak ada');
      }
    });
}

const pilihTemplate2 = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      if (responselast.length) {
        listTemplate.value = responselast
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const addTemplate = (response) => {
  let TTV = {
    e: input.value.gcse,
    v: input.value.gcsv,
    m: input.value.gcsm,
    tekananDarah: input.value.tekananDarah,
    nadi: input.value.nadi,
    nafas: input.value.nafas,
    suhu: input.value.celcius,
    sao2: input.value.sao2,
    keadaanumum: input.value.keadaanumum,
    tinggiBadan: input.value.tinggibadan
  }
  console.log("TTV DATA", TTV)
  console.log(response);
  input.value = response;
  input.value = response;
  delete input.value['_id'];
  input.value.gcse = TTV.e;
  input.value.gcsv = TTV.v;
  input.value.gcsm = TTV.m;
  input.value.tekananDarah = TTV.tekananDarah;
  input.value.nadi = TTV.nadi;
  input.value.celcius = TTV.suhu;
  input.value.sao2 = TTV.sao2;
  input.value.keadaanumum = TTV.keadaanumum;
  input.value.tinggibadan = TTV.tinggiBadan;
  input.value.namatemplate = null;
  showModalTemplateFix.value = false;
  showModalTemplate.value = false;
  H.alert('success', 'Berhasil ditambahkan');
};
async function fetchDiagnosa10(filter: any) {
  let query = ''
  if (filter) {
    query = filter.toLowerCase()
  }
  const response = await useApi().get(
    `/diagnosa/diagnosa-x-paging?name=${query}&limit=10`)

  return response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + ' - ' + item.namadiagnosa, default: item }
  })
}

const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById('Gambar');
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = value
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
    }
  }

  let canvasSkema: any = document.getElementById('Skema');
  if (canvasSkema) {
    let contextS = canvasSkema.getContext("2d");
    contextS.clearRect(0, 0, canvasSkema.width, canvasSkema.height);
    let imgSkema = value
    let backgroundS = new Image();
    backgroundS.src = imgSkema
    backgroundS.onload = function () {
      contextS.drawImage(backgroundS, 0, 0, canvasSkema.width, canvasSkema.height);
    }
  }

  let canvasMata: any = document.getElementById('canvasmata');
  console.log('canvasmata', canvasMata);
  if (canvasMata) {
    let contextMata = canvasMata.getContext("2d");
    contextMata.clearRect(0, 0, canvasMata.width, canvasMata.height);
    let imgMata = value
    let backgroundMata = new Image();
    backgroundMata.src = imgMata
    backgroundMata.onload = function () {
      contextMata.drawImage(backgroundMata, 0, 0, canvasMata.width, canvasMata.height);
    }
  }
}

async function simpanICD10() {
  if (!item.tglpelayanan) {
    useToaster().error('Tgl harus di isi')
    return
  }
  if (!item.jenisDiagnosis10) {
    useToaster().error('Jenis Diagnosis harus di isi')
    return
  }
  if (!item.diagnosa10) {
    useToaster().error('Diagnosis harus di isi')
    return
  }

  let json = {
    'diagnosapasien': {
      'norec': item.NOREC_DIAGNOSA10 ? item.NOREC_DIAGNOSA10 : '',
      'noregistrasifk': item.NOREC_APD,
      'tglregistrasi': item.registrasi.tglregistrasi,
      'ketdiagnosis': item.keterangan10 ? item.keterangan10 : null,
      'iskasusbaru': item.isKasusBaru == 'baru' ? true : null,
      'iskasuslama': item.isKasusBaru == 'lama' ? true : null,
    },
    'detaildiagnosapasien': {
      'objectdiagnosafk': item.diagnosa10,
      'tglinputdiagnosa': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm:ss'),
      'objectjenisdiagnosafk': item.jenisDiagnosis10,
      'noregistrasi': item.registrasi.noregistrasi
    },
    'pasien': {
      'nocm': props.pasien.nocm,
      'namapasien': props.pasien.namapasien,
      'noregistrasi': props.registrasi.noregistrasi,
    }
  }

  isLoading.value = true
  await useApi().post(`/diagnosa/save-diagnosa`, json).then((response: any) => {
    useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
      response.forEach((element: any, i: any) => {
        element.no = i + 1
        element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
      });
      dataSourceICD10.value = response
      isLoading.value = false
      modalInput.value = false
    })
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const simpan = async () => {
  if (!input.value.anamnesis || input.value.anamnesis && input.value.anamnesis.replace(/\s/g, "").length < 4) {
    H.alert('error', 'Anamnesis, ' + 'diisi minimal 4 karakter');
    return;
  }
  if (!input.value.instruksiAsesmen || input.value.instruksiAsesmen && input.value.instruksiAsesmen.replace(/\s/g, "").length < 4) {
    H.alert('error', 'Intruksi, ' + 'diisi minimal 4 karakter');
    return;
  }
  if (!input.value.TADiagnosa || input.value.TADiagnosa && input.value.TADiagnosa.replace(/\s/g, "").length < 4) {
    H.alert('error', 'Diagnosa, ' + 'diisi minimal 4 karakter');
    return;
  }
  if (!input.value.TADiagnosa) {
    H.alert('warning', 'Free text diagnosa wajib dipilih');
    return;
  }

  let ID = input.value.id ? input.value.id : '';
  let object: any = {};
  object = input.value;
  object.nocm = pasien.value.nocm;
  object['Gambar'] = H.tandaTangan().get('Gambar');
  object['canvasmata'] = H.tandaTangan().get('canvasmata');

  if (isTrauma.value) {
    object['Skema'] = H.tandaTangan().get('Skema');
  }

  for (let indGambar = 0; indGambar < 10; indGambar++) {
    let getImage = H.tandaTangan().get('Gambar_' + indGambar);
    if (getImage != null) {
      object['Gambar_' + indGambar] = getImage;
    }
  }

  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate;
  }

  object.pasien = H.setObjectPasien(pasien.value);
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi);
  object.jenisObgyn = jenisObgyn.value;
  object.jenisInterna = jenisInterna.value;
  object.jenisTrauma = jenisTrauma.value;
  if (route.params.index_tabs) { object.index_tabs = parseInt(route.params.index_tabs) }

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: 'module-emr-profile-pasien-page-emr-tabs-asesmen-medis-rawat-inap-index_tabs',
    name_form: formName.value,
    jenis_emr: 'asesmen_medis',
    data: object,
  };

  isLoading.value = true;
  try {
    const response: any = await useApi().post(`/emr/simpan-emr`, json);
    // Update NOREC dan ID input
    NOREC_EMRPASIEN.value = response.norec_emr;
    input.value.id = response.id;

    loadRiwayat()
    sudahDisimpan.value = true
  } catch (e: any) {
    console.error('Error saat menyimpan EMR:', e);
    H.alert('error', 'Gagal menyimpan data EMR');
  } finally {
    isLoading.value = false;
  }
};


const halPF = () => {
  isPemeriksaanFisik.value = true
  isPemeriksaanFisikLokalis.value = false
}
const halPFL = async () => {
  isPemeriksaanFisik.value = false
  isPemeriksaanFisikLokalis.value = true
  await loadGambar('canvasmata', input.value.casvasmata)
}

const saveKlaimSEP = async () => {
  isLoading.value = true
  if (props.registrasi.objectdepartemenfk == 18) {
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
      'norec_pd': props.registrasi.norec_pd,
      'documentklaimfk': 207,
      'namafile': "resume_medis_rj",
      'tglregistrasi': props.registrasi.tglregistrasi,
      'api': "EMR-ReportEMRCtrl@cetakEMR-RingkasanKeluar"
    }).then((r) => {
      isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
  } else if (props.registrasi.objectdepartemenfk == 9) {
    await useApi().post('/bridging/inacbgs/collect-dokumen', {
      'norec_pd': props.registrasi.norec_pd,
      'documentklaimfk': 208,
      'namafile': "resume_igd",
      'tglregistrasi': props.registrasi.tglregistrasi,
      'api': "EMR-ReportEMRCtrl@cetakEMR-RingkasanKeluar"
    }).then((r) => {
      isLoading.value = false
    }).catch((er) => {
      isLoading.value = false
    })
  }

}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('error', 'Nama Template harus diisi untuk menyimpan.');
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}
  object = input.value
  object.nocm = pasien.value.nocm
  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': formName.value,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true

  useApi().post(
    `/emr/simpan-emr-template`, json).then((response: any) => {
      isLoading.value = false
      input.value.namatemplate = null
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

const fetchDiagnosa = async (filter: any) => {
  let q = '';
  console.log("DATA FILTER", filter)
  if (filter != undefined) {
    q = filter.query;
  }

  const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${q}&limit=10`)
  d_Diagnosa.value = response.diagnosa.map((item: any) => {
    return { value: item.id, label: item.kddiagnosa + " - " + item.namadiagnosa, namadiagnosa: item.namadiagnosa }
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const countRangeNilai = (e: any) => {

  let cmc = {
    "keterangan": "CMC (14-15)",
    "poin": 15
  }
  let apatis = {
    "keterangan": "Apatis (12-13)",
    "poin": 13
  }
  let somnolen = {
    "keterangan": "Somnolen (10-11)",
    "poin": 11
  }
  let delirium = {
    "keterangan": "Delirium (7-9)",
    "poin": 9
  }
  let stupar = {
    "keterangan": "Stupar (4-6)",
    "poin": 6
  }
  let koma = {
    "keterangan": "Koma ( <= 3)",
    "poin": 3
  }

  descRangeKesadaran.value.forEach((elements: any) => {
    if (e <= 3 && e <= elements.value.poin) {
      input.value.rangeKesadaran = koma
    }
    else if (e <= 6 && e <= elements.value.poin) {
      input.value.rangeKesadaran = stupar
    }
    else if (e <= 9 && e <= elements.value.poin) {
      input.value.rangeKesadaran = delirium
    }
    else if (e <= 11 && e <= elements.value.poin) {
      input.value.rangeKesadaran = somnolen
    }
    else if (e <= 13 && e <= elements.value.poin) {
      input.value.rangeKesadaran = apatis
    }
    else if (e > 13 && e > elements.value.poin) {
      input.value.rangeKesadaran = cmc
    }
  })
}
const getDataExist = async () => {
  isLoading.value = true

  await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
    if (response != null) {
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.nadi = response.nadi
      input.value.celcius = response.suhu
      input.value.tekananDarah = response.tekananDarah
      input.value.nafas = response.pernapasan
      input.value.sao2 = response.SPO2
    }
    isLoading.value = false
  })
}

const print = async () => {
  H.printBlade(`emr/cetak-asesmen-medis-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}
const addInstruksi = () => {
  modalInstruksi.value = true
}
const fetchTujuan = async (filter: any) => {
  await useApi().get(`/emr/list-tujuan-keperawatan?query=${filter.query}`).then((response: any) => {
    d_instruksi.value = response.tujuanPerawat.map((e: any) => {
      return { value: e.id, label: e.tujuankep, default: e }
    })
  })
}

const fetchIntervensi = async (keperawatanfk: any) => {
  let keperawatan = keperawatanfk ? keperawatanfk.value : ''
  await useApi().get(`/emr/list-intervensi?keperawatanfk=${tujuanKeper.value}`).then((response: any) => {
    d_intervensi.value = response.intervensi.map((e: any) => {
      return { value: e.id, label: e.name, }
    })
  })
}
const addRencanaKeperawatan = () => {

  input.value.instruksi.push({
    no: input.value.instruksi[input.value.instruksi.length - 1].no + 1,
  })
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(
    `/emr/hapus-template`, json).then((response: any) => {
      if (response.status !== 500) {
        isLoading.value = false
        isAlltemplate.value = false;
        H.alert('sucess', response.message);
        pilihTemplateFix();
      } else {
        H.alert('danger', response.message);
      }
    }).catch((e: any) => {
      isLoading.value = false
      H.alert('danger', e);
    })
}

const handlerDiagnosaten = (e: any) => {
  console.log("Pilih diagnosa", e);
  let stringadd = '';
  if (e.value && e.value.namadiagnosa) {
    stringadd = e.value.namadiagnosa + ','
  }
  if (input.value.TADiagnosa != undefined) {
    input.value.TADiagnosa += stringadd;
  } else {
    input.value.TADiagnosa = stringadd;
  }
}

function setPenunjang() {
  isLoading.value = true;
  let str = ''
  let gcol = `PemeriksaanKardiotokografi,PemeriksaanObstetri,PemeriksaanGynekologi,PemeriksaanFetal`;
  useApi().get(`emr/get-penunjang-khusus?tables=${gcol}&norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((dt) => {
    isLoading.value = false;
    if (dt.length > 0) {
      for (let kObject = 0; kObject < dt.length; kObject++) {
        const val = dt[kObject];
        if (val.table == 'PemeriksaanKardiotokografi') {
          str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${val.jenisPemeriksaanObgyn}\n` : ''
          str += val.tdawal ? `TD Awal : ${val.tdawal}\n` : ''
          str += val.tg15 ? `TD Menit ke 15 : ${val.tg15}\n` : ''
          str += val.carapantau ? `Cara Pantau : ${val.carapantau}\n` : ''
          str += val.kecepatankertas ? `Kecepatan Kertas : ${val.kecepatankertas} cm/menit\n` : ''
          str += val.periksaDalam ? `Periksa Dalam : ${val.periksaDalam}\n` : ''
          str += val.denganhasil ? `Dengan Hasil : ${val.denganhasil}\n` : ''
          str += val.diagnosis ? `Diagnosis : ${val.diagnosis}\n` : ''
          str += val.denyutjantung ? `Denyut Jantung Janin : ${val.denyutjantung}\n` : ''
          str += val.frekuensidasar ? `Frekuensi Dasar : ${val.frekuensidasar}\n` : ''
          str += val.akselerasi ? `Akselerasi : ${val.akselerasi}\n` : ''
          str += val.deselerasi ? `Deselerasi : ${val.deselerasi}\n` : ''
          str += val.variabilitas ? `Variabilitas : ${val.variabilitas}\n` : ''
          str += val.jenisnya ? `Jenisnya : ${val.jenisnya}\n` : ''
          str += val.beratnya ? `Beratnya : ${val.beratnya}\n` : ''
          str += val.ssp ? `Pola disfungsi SSP : ${val.ssp}\n` : ''
          str += val.yaitu ? `Yaitu : ${val.yaitu}\n` : ''
          str += val.kontraksi ? `Kontraksi Uterus/His : ${val.kontraksi}\n` : ''
          str += val.frekuensi ? `Frekuensi : ${val.frekuensi} /10menit\n` : ''
          str += val.kekuatan ? `Kekuatan : ${val.kekuatan} mmHg\n` : ''
          str += val.lamanya ? `Lamanya : ${val.lamanya} menit\n` : ''
          str += val.relaksasi ? `Relaksasi : ${val.relaksasi}\n` : ''
          str += val.konfigurasi ? `Konfigurasi : ${val.konfigurasi}\n` : ''
          str += val.tumusdasar ? `Tumus Dasar : ${val.tumusdasar} mmHg\n` : ''
          str += val.gerakjanin ? `Gerak Janin : ${val.gerakjanin} kali\n` : ''
          str += val.lamagerak ? `dalam : ${val.lamagerak} menit\n` : ''
          str += val.diagnosisktg ? `Diagnosis KTG : ${val.diagnosisktg}\n` : ''
          str += val.kategoridiagnosisktg ? `Kategori : ${val.kategoridiagnosisktg}\n` : ''
          str += val.saran ? `Saran : ${val.saran}\n` : ''
        } else if (val.table == 'PemeriksaanObstetri' || val.table == 'PemeriksaanFetal') {
          str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${val.jenisPemeriksaanObgyn}\n` : ''
          str += val.kondisiTeknisFetal ? `Kondisi Teknis : ${val.kondisiTeknisFetal} \n` : ''
          str += val.karenaFetal ? `Karena : ${val.karenaFetal} \n` : ''
          str += val.janin ? `Janin : ${val.janin} \n` : ''
          str += val.jumlahJanin ? `Jumlah Janin : ${val.jumlahJanin} \n` : ''
          str += val.khorionisitas ? `Khorionisitas : ${val.khorionisitas} \n` : ''
          str += val.djj ? `DJJ : ${val.djj} \n` : ''
          str += val.ketDJJ ? `Keterangan DJJ : ${val.ketDJJ} x/menit\n` : ''
          str += val.fetalmovement ? `Fetal Movement : ${val.fetalmovement}\n` : ''
          str += val.gestasionalsac || val.usiaGestasional || val.ketGestasional ? `Biometri :\n` : ''
          str += val.gestasionalsac ? `Gestasional sac : ${val.gestasionalsac} - ${val.usiaGestasional ?? ''}\n` : ''
          str += val.ketGestasional ? `AVE : ${val.ketGestasional}\n` : ''
          str += val.crown || val.usiaCrown || val.ketCrown ? `Crown-rump lenght : ${val.crown} - ${val.usiaCrown}\n` : ''
          str += val.ketCrown ? `EDD : ${val.ketCrown} \n` : ''
          str += val.biparietal || val.usiaBiparietal ? `Biparietal Diameter : ${val.biparietal} - ${val.usiaBiparietal} \n` : ''
          str += val.ketBiparietal ? `EFW : ${val.ketBiparietal} \n` : ''
          str += val.headcircum || val.usiaHeadcircum ? `Head Circumference : ${val.headcircum} - ${val.usiaHeadcircum} \n` : ''
          str += val.abdominalcircum || val.usiaAbdominalcircum ? `Abdominal Circumference : ${val.abdominalcircum} - ${val.usiaAbdominalcircum} \n` : ''
          str += val.Ketabdominalcircum ? `Keterangan Abdominalcircum : ${val.Ketabdominalcircum} \n` : ''
          str += val.femoral || val.usiaFemoral ? `Femoral Lenght : ${val.femoral} - ${val.usiaFemoral} \n` : ''
          str += val.plasenta ? `Plasenta : ${val.plasenta} \n` : ''
          str += val.menutupi ? `Menutupi : ${val.menutupi} \n` : ''
          str += val.ukuranMenutupi ? `Ukuran Menutupi : ${val.ukuranMenutupi} mm dari OUI\n` : ''
          str += val.maturasi ? `Maturasi : ${val.maturasi} \n` : ''
          str += val.cairanaminion ? `Cairan aminion : ${val.cairanaminion} \n` : ''
          str += val.AFI ? `AFI : ${val.AFI} \n` : ''
          str += val.SDP ? `SDP : ${val.SDP} \n` : ''
          str += val.temuanAbnormal ? `Temuan Abnormal : ${val.temuanAbnormal} \n` : ''
          str += val.kongenitalMayor ? `Kelainan kongenital mayor : ${val.kongenitalMayor} \n` : ''
          str += val.temuanAbnormalKongenital ? `Temuan Abnormal Kongenital : ${val.temuanAbnormalKongenital} \n` : ''
          str += val.adneksa ? `Adneksa : ${val.adneksa} \n` : ''
          str += val.temuanAbnormalAdneksa ? `Temuan Abnormal Adneksa : ${val.temuanAbnormalAdneksa} \n` : ''
          str += val.arteriUterina ? `Arteri Uterina : ${val.arteriUterina} \n` : ''
          str += val.arteriUmbilicalis ? `Arteri Umbilicalis : ${val.arteriUmbilicalis} \n` : ''
          str += val.riUterina ? `RI Uterina: ${val.riUterina} \n` : ''
          str += val.riUmbilicalis ? `RI Umbilicalis : ${val.riUmbilicalis} \n` : ''
          str += val.piUterina ? `PI Uterina : ${val.piUterina} \n` : ''
          str += val.piUmbilicalis ? `PI Umbilicalis: ${val.piUmbilicalis} \n` : ''
          str += val.ratioUterina ? `S/D Ratio Uterina: ${val.ratioUterina} \n` : ''
          str += val.ratioUmbilicalis ? `S/D Ratio Umbilicalis: ${val.ratioUmbilicalis} \n` : ''
          str += val.ductusVenosus ? `Ductus Venosus : ${val.ductusVenosus} \n` : ''
          str += val.arteriSerebi ? `Arteri Serebi Media : ${val.arteriSerebi} \n` : ''
          str += val.riDuctus ? `RI Ductus : ${val.riDuctus} \n` : ''
          str += val.riSerebi ? `RI Serebi : ${val.riSerebi} \n` : ''
          str += val.piDuctus ? `PI Ductus: ${val.piDuctus} \n` : ''
          str += val.piSerebi ? `PI Serebi: ${val.piSerebi} \n` : ''
          str += val.ratioDuctus ? `S/D Ratio Ductus: ${val.ratioDuctus} \n` : ''
          str += val.ratioSerebi ? `S/D Ratio Serebi: ${val.ratioSerebi} \n` : ''
          str += val.fetalLainnya ? `Lain-Lain : ${val.fetalLainnya} \n` : ''
          str += val.fetalSaran ? `Kesimpulan & Saran : ${val.fetalSaran} \n` : ''

        } else if (val.table == 'PemeriksaanGynekologi') {
          str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${val.jenisPemeriksaanObgyn}\n` : ''
          str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
          str += val.karena ? `Karena : ${val.karena} \n` : ''
          str += val.vesicaUrinaria ? `Vesica Urinaria : ${val.vesicaUrinaria} \n` : ''
          str += val.cairanBebas ? `Cairan Bebas : ${val.cairanBebas} \n` : ''
          str += val.uterus ? `Uterus : ${val.uterus} \n` : ''
          str += val.adnexa ? `Adnexa : ${val.adnexa} \n` : ''
          str += val.obstetriLainnya ? `Lain-Lain : ${val.obstetriLainnya} \n` : ''
          str += val.kesimpulansaran ? `Kesimpulan & Saran : ${val.kesimpulansaran} \n` : ''
        }
      }

      let detail = input.value;

      if (str != '') {
        if (detail.hasilpemeriksaanpenunjang == undefined) {
          detail.hasilpemeriksaanpenunjang = ''
          detail.hasilpemeriksaanpenunjang += str;
        } else {
          detail.hasilpemeriksaanpenunjang += '\n' + str
        }

        H.alert('success', 'Berhasil ambil data')
      } else {
        H.alert('warning', 'Penunjang Khusus belum ada')
      }
    }
  });

}

function mapNeedTrauma(poli) {
  let arr = [
    "POLI BEDAH SARAF",
    "POLI BEDAH UMUM",
    "POLI VIP BEDAH ORTHOPEDI",
    "POLI VIP BEDAH UMUM",
    "POLI BEDAH ORTHOPEDI SORE",
    "POLI BEDAH UMUM SORE",
    "POLI BEDAH DIGESTIVE",
    "POLI VIP BEDAH DIGESTIVE",
  ];

  return arr.some(item => item.trim() === poli);
}

function mapNonTrauma(poli) {
  let arr = [
    "POLI BEDAH PLASTIK",
    "POLI BEDAH ONKOLOGI",
    "POLI BEDAH UROLOGI",
    "POLI BTKV",
    "POLI VIP BEDAH UROLOGI",
    "POLI ORTHOPEDI SPINE",
    // "PELAYANAN ONKOLOGI RADIASI",
    "POLI VIP BEDAH PLASTIK",
    "POLI NEFROLOGI",
    "POLI BEDAH PLASTIK SORE",
    "POLI BEDAH ONKOLOGI SORE",
    "POLI BTKV SORE"
  ]

  return arr.some(item => item.trim() === poli);
}

function checkResume() {
  // if not dokter just end
  if (kelompokUser && kelompokUser.toUpperCase() != 'DOKTER') {
    return;
  }

  let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
  let uri = `/emr/check-resume-medis${params}`;
  useApi().get(uri).then((res) => {
    console.log(`res Check Resume`, res);
    if (res) {
      isResumeMedis.value = true;
    }
  })
}
console.log(props.pasien)

async function makeRingkasanData(json: any) {
  return new Promise((resolve, reject) => {
    try {
      let tanggaldatang = '';
      let dpjpUtamas: any = {
        value: json.data.user_input ? json.data.user_input.pegawaifk : json.data.registrasi.objectpegawaifk,
        label: json.data.user_input ? json.data.user_input.namalengkap : json.data.registrasi.dokter,
      };

      let fisik = '';
      fisik += json.data.celcius ? `Suhu : ${json.data.celcius} °C\n` : 'Suhu : -\n'
      fisik += json.data.nadi ? `Nadi : ${json.data.nadi} x/mnt\n` : 'Nadi : -\n'
      fisik += json.data.nafas ? `Pernafasan : ${json.data.nafas} x/mnt\n` : 'Pernafasan : -\n'
      fisik += json.data.tekananDarah ? `Tekanan Darah : ${json.data.tekananDarah} mmHg\n` : 'Tekanan Darah : -n\n'
      fisik += dataNurse.tinggiBadan ? `Tinggi Badan : ${dataNurse.tinggiBadan} Cm\n` : 'Tinggi Badan : -\n'
      fisik += dataNurse.beratBadan ? `Berat Badan : ${dataNurse.beratBadan} Kg\n` : 'Berat Badan : -\n'
      fisik += json.data.spo2 ? `SPO2 : ${json.data.spo2} %\n` : ''

      let pemeriksaanPenunjang = '';
      if (namaRuanganDinamis.toUpperCase().indexOf('MATA') > -1) {
        pemeriksaanPenunjang += json.data.visusawalodb ? `Visus Awal ODB (UVCA) : ${json.data.visusawalodu}\n` : ''
        pemeriksaanPenunjang += json.data.visusawalosu ? `Visus Awal OS (UVCA) : ${json.data.visusawalosu}\n` : ''
        pemeriksaanPenunjang += json.data.visusawalodb ? `Visus Awal OU (BVCA) : ${json.data.visusawalodb}\n` : ''
        pemeriksaanPenunjang += json.data.visusawalosb ? `Visus Awal ODB (BVCA) : ${json.data.visusawalosb}\n` : ''
      } else {
        pemeriksaanPenunjang += json.data.hasilpemeriksaanpenunjang
      }

      let object = {
        "detailDS": [
          {
            "no": 1,
            "TADiagnosaSekunder": ""
          }
        ],
        "detailDT": [
          {
            "no": 1,
            "TADeskripsiTindakan": ""
          }
        ],
        "waktuTataLaksana": json.data.jamKedatangan,
        "waktuKontrol": json.data.jamKedatangan,
        "jamKedatangan": json.data.jamKedatangan,
        "jamAsesmenAwal": json.data.jamKedatangan,
        "riwayatkeluar": json.data.riwayatkeluar,
        "statuskeluar": json.data.statuskeluar,
        "perlukontrol": json.data.perlukontrol,
        "tanggalKedatangan": json.data.jamKedatangan,
        "dpjpUtama": dpjpUtamas,
        "TAKondisiSaatMasuk": '',
        "TADiagnosisPrimer": json.data.TADiagnosa ?? null,
        "gcse": json.data.gcse,
        "gcsv": json.data.gcsv,
        "gcsm": json.data.gcsm,
        "kesanUmum": json.data.keadaanumum,
        "nadi": json.data.nadi,
        "nafas": json.data.nafas,
        "celcius": json.data.celcius,
        "tekananDarah": json.data.tekananDarah ?? '',
        "anamnesis": json.data.anamnesis ?? '',
        "pemeriksaanfisik": fisik,
        "intruksi": json.data.instruksiAsesmen ?? '',
        // "hasilpemeriksaanpenunjang": json.data.hasilpemeriksaanpenunjang ?? '',
        "hasilpemeriksaanpenunjang": pemeriksaanPenunjang,
        "sumber": "AsmedRajal"
      }
      object.nocm = json.data.pasien.nocm
      object.pasien = json.data.pasien
      object.registrasi = json.data.registrasi
      let sendData = {
        'id': '',
        'norec_emr': '',
        'collection': 'RingkasanKeluar',
        'url_form': 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        'name_form': 'Ringkasan Keluar',
        'jenis_emr': 'asesmen_medis',
        'data': object
      }

      useApi().postNoMessage(
        `/emr/simpan-emr`, sendData).then(async (response: any) => {
          isLoading.value = false
          H.alert('success', 'Ringkasan keluar berhasil dibuat');
          return resolve(true)
        }).catch((e: any) => {
          isLoading.value = true
          H.alert('error', 'Ringkasan keluar gagal dibuat');
          return resolve(false)
        })
    } catch (error) {
      return reject(error);
    }
  })

}

onMounted(() => {
  loadRiwayat()
  if (isfromCPPT) {
    H.alert('error', 'Silahkan isi Asesmen Medis Terlebih Dahulu !');
    formName.value = 'Assesmen Medis'
  }
  // getDataExist();
  diagnosa()
  fetchPasien()
  dropdownList()
  // fetchDiagnosa()
  // checkResume()
});

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})

watch(
  () => input.value.lokalisobgynnormal,
  (newValue, oldValue) => {
    if (newValue == "Batas Normal") {

      if (namaRuanganDinamis.value.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn.value == 'Obstetri') {
        input.value.usiaibu = 2
        input.value.haematologi = 2
        input.value.neural = 2
        input.value.ds = 2
        input.value.huntington = 2
        input.value.reterdasi = 2
        input.value.kromosom = 2
        input.value.lahircacat = 2
        input.value.abortus = 2
        input.value.narkoba = 2
        input.value.hiv = 2
        input.value.hepatitisb = 2
        input.value.penderita = 2
        input.value.pms = 2
        input.value.pmspasangan = 2
        input.value.gagalkb = 2
        input.value.korban = 2
        // input.value.menggugurkan = 2

        input.value.kepalageneral = 1
        input.value.matageneral = 1
        input.value.gigigeneral = 1
        input.value.tiroidgeneral = 1
        input.value.payudarageneral = 1
        input.value.jantunggeneral = 1
        input.value.parugeneral = 1
        input.value.perutgeneral = 1
        input.value.pelvicgeneral = 1
        input.value.tungkaigeneral = 1
        input.value.tungkaibawahgeneral = 1
        input.value.limfegeneral = 1

        input.value.tinggifundus = "DBM"
        input.value.letakanak = "DBM"
        input.value.denyutjantung = "DBM"
        input.value.his = "DBM"
        input.value.Promontorium = "DBM"
        input.value.Innominata = "DBM"
        input.value.Conjungata = "DBM"
        input.value.Spina = "DBM"
        input.value.Distansia = "DBM"
        input.value.Walls = "DBM"
        input.value.Pubis = "DBM"
        input.value.Sacrum = "DBM"
        input.value.Intertuberosum = "DBM"
        input.value.Kesan = "DBM"
      } else if (namaRuanganDinamis.value.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn.value == 'Ginekologi') {
        input.value.kepalageneralgin = 1
        input.value.matageneralgin = 1
        input.value.gigigeneralgin = 1
        input.value.tiroidgeneralgin = 1
        input.value.payudarageneralgin = 1
        input.value.jantunggeneralgin = 1
        input.value.parugeneralgin = 1
        input.value.perutgeneralgin = 1
        input.value.pelvicgeneralgin = 1
        input.value.tungkaigeneralgin = 1
        input.value.tungkaibawahgeneralgin = 1
        input.value.limfegeneralgin = 1
        input.value.vulva = 1
        input.value.vagina = 1
        input.value.cervix = 1
        input.value.uterus = 1
        input.value.adnexa = 1
        input.value.rectum = 1
        input.value.extremitas = "Dalam Batas Normal"
        input.value.rectal = "Dalam Batas Normal"
      }

    } else {
      if (namaRuanganDinamis.value.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn.value == 'Obstetri') {
        delete input.value.usiaibu
        delete input.value.haematologi
        delete input.value.neural
        delete input.value.ds
        delete input.value.huntington
        delete input.value.reterdasi
        delete input.value.kromosom
        delete input.value.lahircacat
        delete input.value.abortus
        delete input.value.narkoba
        delete input.value.hiv
        delete input.value.hepatitisb
        delete input.value.penderita
        delete input.value.pms
        delete input.value.pmspasangan
        delete input.value.gagalkb
        delete input.value.korban
        // input.value.menggugurkan = 2

        delete input.value.kepalageneral
        delete input.value.matageneral
        delete input.value.gigigeneral
        delete input.value.tiroidgeneral
        delete input.value.payudarageneral
        delete input.value.jantunggeneral
        delete input.value.parugeneral
        delete input.value.perutgeneral
        delete input.value.pelvicgeneral
        delete input.value.tungkaigeneral
        delete input.value.tungkaibawahgeneral
        delete input.value.limfegeneral

        delete input.value.tinggifundus
        delete input.value.letakanak
        delete input.value.denyutjantung
        delete input.value.his
        delete input.value.Promontorium
        delete input.value.Innominata
        delete input.value.Conjungata
        delete input.value.Spina
        delete input.value.Distansia
        delete input.value.Walls
        delete input.value.Pubis
        delete input.value.Sacrum
        delete input.value.Intertuberosum
        delete input.value.Kesan
      } else if (namaRuanganDinamis.value.toUpperCase().indexOf('OBGYN') > -1 && jenisObgyn.value == 'Ginekologi') {
        delete input.value.kepalageneralgin
        delete input.value.matageneralgin
        delete input.value.gigigeneralgin
        delete input.value.tiroidgeneralgin
        delete input.value.payudarageneralgin
        delete input.value.jantunggeneralgin
        delete input.value.parugeneralgin
        delete input.value.perutgeneralgin
        delete input.value.pelvicgeneralgin
        delete input.value.tungkaigeneralgin
        delete input.value.tungkaibawahgeneralgin
        delete input.value.limfegeneralgin
        delete input.value.tungkaigeneralgin
        delete input.value.extremitas
        delete input.value.rectal
        delete input.value.vulva
        delete input.value.vagina
        delete input.value.cervix
        delete input.value.uterus
        delete input.value.adnexa
        delete input.value.rectum
      }
    }
  }
)

watch(() => route.params.index_tabs, (newValue, oldValue) => {
  loadRiwayat()
  let rouutename = route.name + '-' + route.params.index_tabs
  let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
  if (cache) {
    input.value = cache
  }
})

onBeforeMount(async () => {
  try {
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name + '-' + route.params.index_tabs
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    if (!sudahDisimpan.value) {
      const konfirmasi = H.alert('warning', 'Belum Disimpan!!!');
      if (!konfirmasi) {
        return next(false);
      }
    }
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});

watch(() => [
  input.value.kesadaranE,
  input.value.kesadaranM,
  input.value.kesadaranV,
  input.value.totalKesadaran,
], () => {
  let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
  let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
  let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0
  const jumlahNilai = poin1 + poin2 + poin3
  countRangeNilai(jumlahNilai)
  input.value.totalKesadaran = jumlahNilai
})

watch(() => [input.value.section_SL], () => {
  if (input.value.section_SL && input.value.section_SL.label) {
    namaRuanganDinamis.value = input.value.section_SL.label;
  }
})

// Watch for changes in wnlcardiovascular
watch(
  () => input.value.wnlcardiovascular,
  (newValue) => {
    if (newValue === "WNLCardiovascular") {
      input.value.murmur = "Murmur"; // Automatically check 'Murmur'
    } else {
      input.value.murmur = null; // Uncheck 'Murmur' if 'WNL' is unchecked
    }
  }
);

watch(() => [item.diagnosa10,], () => {
  useApi().get(`emr/get-kasus-diagnosa?nocmfk=${ID_PASIEN}&iddiagnosa=${item.diagnosa10}`).then((response) => {
    if (response.datas.length > 0) {
      item.isKasusBaru = 'lama'
    } else {
      item.isKasusBaru = 'baru'
    }
  })
})

watch(() => [
  input.value.kehilanganbbGizi,
  input.value.asupanterakhirGizi,
  input.value.gangguansaluranGizi,
  input.value.kapasitasfungsiGizi,
  input.value.stressMetabolikGizi,
  input.value.pemeriksafisikkGizi,
  input.value.sgaGizi,
  input.value.imtGizi,
  input.value.albuminGizi,
  input.value.tclGizi,
  // input.value.point2
], () => {
  let bb = input.value.kehilanganbbGizi ? parseInt(input.value.kehilanganbbGizi) : 0;
  let asupan = input.value.asupanterakhirGizi ? parseInt(input.value.asupanterakhirGizi) : 0;
  let saluran = input.value.gangguansaluranGizi ? parseInt(input.value.gangguansaluranGizi) : 0;
  let kapasitas = input.value.kapasitasfungsiGizi ? parseInt(input.value.kapasitasfungsiGizi) : 0;
  let stress = input.value.stressMetabolikGizi ? parseInt(input.value.stressMetabolikGizi) : 0;
  let pemeriksa = input.value.pemeriksa ? parseInt(input.value.pemeriksa) : 0;
  let sga = input.value.sga ? parseInt(input.value.sga) : 0;
  let imt = input.value.imtGizi ? parseInt(input.value.imtGizi) : 0;
  let albumin = input.value.albuminGizi ? parseInt(input.value.albuminGizi) : 0;
  let tcl = input.value.tcl ? parseInt(input.value.tcl) : 0;
  const jumlahNilai = bb + asupan + saluran + kapasitas + stress + pemeriksa + sga + imt + albumin + tcl;
  input.value.totalScoreGizi = jumlahNilai;
  if (jumlahNilai <= 1) {
    input.value.malnutrisiGizi = 'Rendah';
    input.value.resikoMalnutrisiGizi = 0;
  } else if (jumlahNilai >= 1 && jumlahNilai <= 3) {
    input.value.malnutrisiGizi = 'Sedang';
    input.value.resikoMalnutrisiGizi = 1;
  } else if (jumlahNilai >= 4) {
    input.value.malnutrisiGizi = 'Berat';
    input.value.resikoMalnutrisiGizi = 2;
  }
})


function handleInputUpdate(newData) {
  traumaScoreData.value = newData;
  for (var i in newData) {
    input.value[i] = newData[i];
  }
}

const modalTindakan = ref(false);
const modalTindakanDokter = async (e: any) => {
  modalTindakan.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalResep = ref(false);
const modalResepDokter = async (e: any) => {
  modalResep.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

watch(modalResep, (newValue) => {
  if (newValue) {
    console.log(props.registrasi.objectruanganlastfk);
    console.log(props.registrasi.objectdepartemenfk);
  }
});

const modalKonsultasi = ref(false);
const modalKonsultasiDokter = async (e: any) => {
  modalKonsultasi.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}

const modalLaboratorium = ref(false);
const modalLaboratoriumDokter = async (e: any) => {
  modalLaboratorium.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}
const modalRadiologi = ref(false);
const modalRadiologiDokter = async (e: any) => {
  modalRadiologi.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}
const modalPenunjangKhusus = ref(false);
const modalPenunjangKhususDokter = async (e: any) => {
  modalPenunjangKhusus.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}
const modalBedah = ref(false);
const modalBedahDokter = async (e: any) => {
  modalBedah.value = true
  const pasien3 = pasien?.value
  console.table(pasien3)
  if (pasien3) {
    noreg_pasienDetail.value = pasien3.noregistrasi;
    norec_pd_pasienDetail.value = route.query.norec_pasien_daftar;
  }
}
const AutoFill_Input = () => {
  input.value = {}
  let d = input.value
  d.waktuTataLaksana = new Date;
  d.waktuKontrol = new Date;
  d.jamKedatangan = new Date;
  d.jamAsesmenAwal = new Date;
  d.tanggalKedatangan = new Date;
  d.batasnormalkepala = false;
  d.batasnormalkestrad = false;
  d.batasnormalmata = false;
  d.batasnormaltht = false;
  d.namatemplate = '';
  d.namadiagnosa = '';
  d.batasnormalleher = false;
  d.batasnormalthorax = false;
  d.batasnormalpulmo = false;
  d.batasnormalabdomen = false;
  d.batasnormalextremitas = false;
  d.batasNormalOR = false;
  d.lokaliskulit = false;
  d.lokalistht = false;
  d.lokalissaraf = false;
  d.normalcor = false;
  d.semuanormal = false;
  d.tidakadaresep = false;
  d.wnlrespiratory = false;
  d.optionsnapza = "tidak";
  d.wnlcardiovascular = null;
  d.murmur = null;
  d.jenisObgyn = route.query.jenisobgyn as string || "";
  d.jenisInterna = route.query.jenisinterna as string || "";
  d.jenisTrauma = route.query.jenistrauma as string || "";
  d.details = [{
    no: 1,
  }];
  d.instruksi = [{
    no: 1,
  }];
}
const pilihAsesmen = async (e: any) => {
  if (namaRuanganDinamis.value.toUpperCase().indexOf('OBGYN') > -1) {
    modalConfirmObgyn.value = true
  } else if (namaRuanganDinamis.value.toUpperCase().indexOf('INTERNA') > -1) {
    modalConfirmInterna.value = true
  } else if ((namaRuanganDinamis.value.toUpperCase().indexOf('BEDAH') > -1 || namaRuanganDinamis.value.toUpperCase().indexOf('BTKV') > -1 || namaRuanganDinamis.value.toUpperCase().indexOf('ORTHOPEDI SPINE') > -1 || namaRuanganDinamis.value.toUpperCase().indexOf('NEFROLOGI') > -1) && namaRuanganDinamis.value.toUpperCase().indexOf('BEDAH MULUT') == -1 && namaRuanganDinamis.value.toUpperCase().indexOf('BEDAH UROLOGI') == -1) {
    modalConfirmTrauma.value = true
  } else {
    H.alert('warning', 'Terjadi Kesalahan')
  }
}

const setRouting = (rupa: any, jenis: any) => {
  let query: any = {};

  if (jenis == 'jenisobgyn') {
    jenisObgyn.value = rupa;
  } else if (jenis == 'jenisinterna') {
    jenisInterna.value = rupa;
  } else if (jenis == 'jenistrauma') {
    jenisTrauma.value = rupa;
  } else {
    H.alert('error', 'Terjadi Kesalahan')
  }

  query = {
    nocmfk: props.registrasi.nocmfk,
    norec_pasien_daftar: props.registrasi.norec_pd,
    norec_pd: props.registrasi.norec_pd,
    norec_apd: props.registrasi.norec_apd,
    jenisobgyn: jenisObgyn.value,
    jenisinterna: jenisInterna.value,
    jenistrauma: jenisTrauma.value
  };

  router.push({ query: query })

  modalConfirmObgyn.value = false;
  modalConfirmInterna.value = false;
  modalConfirmTrauma.value = false;
}
</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

#sticky-buttons {
  position: sticky;
  top: 0;
  z-index: 1000;
  background-color: #fff;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.form-layout.is-stacked {
  max-width: none;
  padding: 0 10px 0 10px;
}

.form-layout.is-separate {
  max-width: none;
}

.form-layout .form-outer {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 20px;
  background-color: var(--white);
  border-radius: var(--radius-large);
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
  padding: 0;
}

.hr-dashboard .block-header {
  display: none;
}

.block-green {
  background: var(--primary);
  font-family: var(--font);
  box-shadow: var(--primary-box-shadow);
  border-radius: 16px;
  padding: 25px;
  display: none;
}

.tabels tr td {
  border: 1px solid #b3b3b3;
}

.tabels tr th {
  border: 1px solid #b3b3b3;
}

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

.checkbox.is-outlined {
  padding: unset !important;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}

.heightinput {
  height: 25px
}

.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
}

.center {
  text-align: center;
}

.vm {
  vertical-align: middle;
}

.bold {
  font-weight: bold;
}

// .assesment th,
// td {
//     padding: 8px;
//     vertical-align: middle !important;
// }

.fontcheckbox {
  font-size: 12.5px;
  color: black;
}

hr {
  border-top: 1px solid hsl(0deg 6.81% 88.68%);
  display: block;
  height: 2px;
  margin: 0px;
}
</style>
