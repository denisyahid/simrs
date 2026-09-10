<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="mt-5">
        <Fieldset :toggleable="true">
          <div class="columns is-multiline pl-3 pr-3">
            <!-- Kolom untuk Tanggal -->
            <div class="column is-6">
              <div class="column is-12">
                <span>Tanggal :</span>
              </div>
              <div class="column is-12" style="margin-top: 7px;">
                <VDatePicker v-model="input.tanggal11" mode="date" style="width: 100%" trim-weeks
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

              <!-- Kolom untuk Waktu diperiksa dokter -->
              <div class="column is-12">
                <span>Waktu diperiksa dokter :</span>
              </div>
              <div class="column is-12" style="margin-top: 7px;">
                <VDatePicker v-model="input.WaktuDiperiksadokter" mode="Time" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Jam" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>

              <!-- Kolom untuk Jam kedatangan -->
              <div class="column is-12">
                <span>Jam kedatangan :</span>
              </div>
              <div class="column is-12" style="margin-top: 7px;">
                <VDatePicker v-model="input.Jamkedatangan" mode="Time" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Jam" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
            </div>

            <!-- Kolom untuk Dokter dan Tanda Tangan -->
            <div class="column is-6">
              <div class="column is-12">
                <h1 class="p-0" style="font-weight: bold; margin-top: -8px;">Dokter</h1>
              </div>
              <div class="column is-12" style="margin-top: 7px;">
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.namaPetugas3" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Dokter..." class="mt-2" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12" style="margin-top: 7px;">
                <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
              </div>
            </div>
          </div>

          <div class="column is-12"
            style="background-color: #9AC8CD; height: 30px; width: 100%; border-top: 1px solid; display: flex; justify-content: left; align-items: center;">
            <span style="font-size: 14px;"><b>Data Subjektif</b></span>
          </div>

          <div class="column is-12">
            <div class="column is-8">
              <span>Keluhan Utama :</span>
              <VField>
                <VControl>
                  <VTextarea v-model="input.Keluhanutama" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-8">
              <span>Riwayat pengobatan :</span>
              <VField>
                <VControl>
                  <VTextarea v-model="input.Riwayatpengobatan" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-8">
              <span>Riwayat alergi :</span>
              <VField>
                <VControl>
                  <VTextarea v-model="input.Riwayatalergi" rows="3">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-8" style="overflow-x: scroll; width: 150vh; margin-left: -5px;">
            <table border="1" style="width: 150vh; border-collapse: collapse;">
              <thead>
                <tr>
                  <th style="background-color: #9AC8CD; height: 30px; border: 1px solid;" colspan="6">
                    <span style="font-size: 14px;"><b>Data Objektif</b></span>
                  </th>
                </tr>
              </thead>
              <thead>
                <tr>
                  <th style="text-align: center; background-color: #9AC8CD; width: 250px; border: 1px solid;"
                    colspan="2">
                    <b>AIRWAY</b>
                  </th>
                  <th style="text-align: center; background-color: #9AC8CD; width: 250px; border: 1px solid;">
                    <b>BREATHING</b>
                  </th>
                  <th style="text-align: center; background-color: #9AC8CD; width: 250px; border: 1px solid;">
                    <b>CIRCULATION</b>
                  </th>
                  <th style="text-align: center; background-color: #9AC8CD; width: 250px; border: 1px solid;">
                    <b>DISABILITY/NEUROLOGICAL</b>
                  </th>
                  <th style="text-align: center; background-color: #9AC8CD; width: 250px; border: 1px solid;">
                    <b>FETAL</b>
                  </th>

                </tr>
              </thead>


              <tbody>
                <tr>
                  <td colspan="2">
                    <div class="control" v-for="(data, index) in Airway" :key="index"
                      style="margin-top: -15px; width: 170px">
                      <label class="checkbox">
                        <VCheckbox v-model="input[data.model]" :true-value="data.label" color="primary" />
                        <span>{{ data.label }}</span>
                      </label>
                    </div>
                  </td>

                  <td>
                    <div class="control" v-for="(data, index) in Breathing" :key="index"
                      style="margin-top: -15px; width: 220px">
                      <label class="checkbox">
                        <VCheckbox v-model="input[data.model]" :true-value="data.label" color="primary" />
                        <span>{{ data.label }}</span>
                      </label>
                    </div>
                  </td>

                  <td>
                    <span><b>Nadi :</b></span>
                    <div class="columns is-multiline p-3" style="margin-bottom: -10px;">
                      <div class="column is-2" v-for="(data) in Nadi1" style="margin-top: -5px; width: 220px;">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <span><b>CRT :</b></span>
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in CRT1" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <span><b>Warna Kulit :</b></span>
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in WarnaKulit" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <span><b>Perdarahan per vaginam :</b></span>
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in PerdarahanPerVaginam" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <span><b>Turgor kulit :</b></span>
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Turgorkulit" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>

                  <td>
                    <span><b>Respon :</b></span>
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Respon1" :key="index" style="margin-right: 20px; width: 150px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <span><b>Pupil :</b></span>
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Pupil1" :key="index" style="margin-right: 20px; width: 150px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <span>Reflek :</span>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" v-model="input.Reflek1" />
                      </VControl>
                    </VField>
                    <!-- ------------------------------ -->
                    <span>GCS :</span>
                    <VField label="E :"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" v-model="input.e1" />
                      </VControl>
                    </VField>

                    <VField label="V :"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" v-model="input.v1" />
                      </VControl>
                    </VField>

                    <VField label="M :"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="text" class="input" v-model="input.m1" />
                      </VControl>
                    </VField>
                  </td>

                  <td>
                    <div class="column is-3"
                      style="width: 190px; display: flex; flex-direction: column; justify-content: center;">
                      <span><b>DJJ :</b></span>
                      <VField addons style="width: 170px; margin-top: 10px;">
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Djj..." v-model="input.djj1" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/mnt</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td style="height: 40px;" colspan="6">
                    <div class="columns is-multiline pt-3 pr-3 pl-3">
                      <div class="column is-2">
                        <span><b>TD :</b></span>
                        <VField addons style="width: 150px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Tekanan Darah ..." v-model="input.TD1" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHg</VButton>
                          </VControl>
                        </VField>
                      </div>

                      <div class="column is-2">
                        <span><b>N :</b></span>
                        <VField addons style="width: 150px;">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="N..." v-model="input.N1" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/menit</VButton>
                          </VControl>
                        </VField>
                      </div>

                      <div class="column is-2">
                        <span><b>R :</b></span>
                        <VField addons style="width: 150px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="R ..." v-model="input.R1" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>x/menit</VButton>
                          </VControl>
                        </VField>
                      </div>

                      <div class="column is-2">
                        <span><b>Sat O<sub>2</sub> :</b></span>
                        <VField addons style="width: 150px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Sat O2 ..." v-model="input.SatO2" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>%</VButton>
                          </VControl>
                        </VField>
                      </div>

                      <div class="column is-2">
                        <span><b>Suhu Axila :</b></span>
                        <VField addons style="width: 150px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu ..." v-model="input.SuhuAxila" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C</VButton>
                          </VControl>
                        </VField>
                      </div>

                      <div class="column is-2">
                        <span><b>Produksi urine</b></span>
                        <VField addons style="width: 150px">
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Produksi urine ..."
                              v-model="input.Produksiurine" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>ml/jam</VButton>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td colspan="6" style="height: 40px;">
                    <div class="column is-12">
                      <span><b>Nyeri :</b></span>
                      <div class="columns is-multiline p-3">
                        <!-- Checkboxes with labels -->
                        <div class="column is-narrow" v-for="(data, index) in Nyeri1" :key="index"
                          style="margin-left: -20px;">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                                color="primary" square />
                            </VControl>
                          </VField>
                        </div>

                        <div class="column is-3">
                          <div class="column is-narrow" style="flex-grow: 1; margin-top: -20px;">
                            <VField label="Lokasi :">
                              <VControl>
                                <VInput type="text" class="input" v-model="input.lokasi1" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <div class="column is-3">
                          <div class="column is-narrow" style="flex-grow: 1; margin-top: -20px;">
                            <VField label="Intensitas (0-10) :">
                              <VControl>
                                <VInput type="text" class="input" v-model="input.Intensitas1" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <span style="margin-top: -15px;"><b>Jenis :</b></span>
                        <div class="columns is-multiline p-3" style="margin-left: -50px;">
                          <!-- Checkboxes with labels -->
                          <div class="column is-narrow" v-for="(data, index) in Jenis1" :key="index">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                                  color="primary" square style="margin-left: -20px;" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th style="text-align: center; background-color: #9AC8CD; width: 200px; font-size: 17px;">
                    <span><b>Kategori / Kriteria</b></span>
                  </th>
                  <th style="text-align: center; background-color: red; color: white; width: 200px; font-size: 20px;">
                    <span><b>1 (Resusitative)</b></span>
                  </th>
                  <th style="text-align: center; background-color: #FF8225; width: 200px; font-size: 20px;">
                    <span><b>2 (Emergent)</b></span>
                  </th>
                  <th style="text-align: center; background-color: yellow; width: 200px; font-size: 20px;">
                    <span><b>3 (Urgent)</b></span>
                  </th>
                  <th style="text-align: center; background-color: #06D001; width: 200px; font-size: 20px;">
                    <span><b>4 (Less Urgent)</b></span>
                  </th>
                  <th style="text-align: center; background-color: aqua; width: 200px; font-size: 20px;">
                    <span><b>5 (Non Urgent)</b></span>
                  </th>
                </tr>

                <tr>
                  <td style="text-align: center; width: 200px">
                    <span><b>Asesment
                        dan terapi</b></span>
                  </td>
                  <td style="text-align: center; background-color: red; width: 200px; align-items: center;">
                    <span><b>Segera</b></span>
                  </td>
                  <td style="text-align: center; background-color: #FF8225; width: 200px;">
                    <span><b>≤ 15 menit</b></span>
                  </td>
                  <td style="text-align: center; background-color: yellow; width: 200px;">
                    <span><b>≤ 30 menit</b></span>
                  </td>
                  <td style="text-align: center; background-color: #06D001; width: 200px;">
                    <span><b>≤ 60 menit</b></span>
                  </td>
                  <td style="text-align: center; background-color: aqua; width: 200px;">
                    <span><b>≤ 120 menit</b></span>
                  </td>
                </tr>

                <tr>
                  <td style="text-align: center; width: 200px">
                    <span><b>Penilaian ulang
                        oleh bidan</b></span>
                  </td>
                  <td style="text-align: center; background-color: red; width: 200px; align-items: center;">
                    <span><b>Pengawasan terus
                        menerus</b></span>
                  </td>
                  <td style="text-align: center; background-color: #FF8225; width: 200px;">
                    <span><b>Setiap 15 menit</b></span>
                  </td>
                  <td style="text-align: center; background-color: yellow; width: 200px;">
                    <span><b>Setiap 15 menit</b></span>
                  </td>
                  <td style="text-align: center; background-color: #06D001; width: 200px;">
                    <span><b>Setiap 30 menit</b></span>
                  </td>
                  <td style="text-align: center; background-color: aqua; width: 200px;">
                    <span><b>Setiap 60 menit</b></span>
                  </td>
                </tr>

                <tr>
                  <td style="text-align: center; width: 200px">
                    <span>Persalinan / Ketuban</span>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Pembukaan" class="p-0" color="primary" square label="Pembukaan 8 cm -
                        lengkap" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Suspek" class="p-0" color="primary" square label="Suspek Persalinan
                          Preterm/PPROM
                          umur kehamilan < 37
                          minggu" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Tanda1" class="p-0" color="primary" square
                          label="Tanda persalinan fase aktif > 37 minggu" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Tanda2" class="p-0" color="primary" square
                          label="Tanda persalinan fase laten/tanda pecah ketuban > 37 minggu" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Tidak" class="p-0" color="primary" square
                          label="Tidak ada fase persalinan" />
                      </VControl>
                    </VField>
                  </td>
                </tr>
                <!-- --------------------------------- -->
                <tr>
                  <td style="text-align: center; width: 200px">
                    <span>Perdarahan</span>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Resusitative" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Emergent" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Urgent" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.BercakDarah" class="p-0" color="primary" square
                          label="Bercak darah" />
                      </VControl>
                    </VField>
                  </td>
                </tr>
                <!-- --------------------------------- -->
                <tr>
                  <td style="text-align: center; width: 200px">
                    <span>Hipertensi</span>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Kejang" class="p-0" color="primary" square label="Kejang" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Hipertensi" class="p-0" color="primary" square
                          label="Hipertensi (TD > 160/110 mmHg) dan atau sakit kepala menetap, gangguan virus, nyeri ulu hati" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.Hipertensi2" class="p-0" color="primary" square
                          label="Hipertensi ringan 140/90-160/110 mmHg dengan/tanpa keluhan subjektif" />
                      </VControl>
                    </VField>
                  </td>
                  <td></td>
                  <td></td>
                </tr>
                <!-- --------------------------------- -->
                <tr>
                  <td style="text-align: center; width: 200px">
                    <span>Penilaian janin</span>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <VField style="margin-bottom: 0;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.bradikardia" class="p-0" color="primary" square
                          label="DJJ bradikardia(< 100 dpm pada UK ≥38 mgg; <120 dpm pada UK <38 mgg" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Emergent2" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <!-- --------------------------------- -->
                <tr>
                  <td style="text-align: center; width: 200px">
                    <span>Lain-lain</span>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Lain_lain1" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Lain_lain2" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Lain_lain3" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Lain_lain4" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center; width: 200px">
                    <div class="columns is-multiline p-3"
                      style="display: flex; flex-direction: row; align-items: center;">
                      <div v-for="(data, index) in Lain_lain5" :key="index" style="margin-right: 20px;">
                        <VField style="margin-bottom: 0;">
                          <VControl raw subcontrol style="display: flex; align-items: center; margin-top: 10px;">
                            <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td colspan="6" style="height: 50px; text-align: center;">
                    <span>Adopted from Obstetrical Triage Acuity Scale of London Health Sciences Centre and The Canadian
                      Emergency
                      Department Triage and Acuity Scale,
                      Modified by Division Maternal Fetal Medicine of Obstetric and Gynecology Department of Medical
                      Faculty
                      Udayana University/Sanglah Hospital</span>
                  </td>
                </tr>

                <tr>
                  <td colspan="6" style="height: 50px;">
                    <div class="column is-12">
                      <span><b>Kategori Triage :</b></span>
                      <div style="display: flex; align-items: center; margin-top: 10px;">
                        <VField style="margin-bottom: 0; margin-right: 20px;">
                          <VControl raw subcontrol>
                            <span style="margin-right: 15px;"> 1</span>
                            <VCheckbox v-model="input.checkbox1" class="p-0" color="primary" square
                              style="transform: scale(1.5); width: 20px; height: 20px;" />
                            <VField label="Segera" style="margin-top: 10px; margin-left: 12px;"></VField>
                          </VControl>
                        </VField>
                        <VField style="margin-bottom: 0; margin-right: 20px;">
                          <VControl raw subcontrol>
                            <span style="margin-right: 15px;"> 2</span>
                            <VCheckbox v-model="input.checkbox2" class="p-0" color="primary" square
                              style="transform: scale(1.5); width: 20px; height: 20px;" />
                            <VField label="≤ 15 menit" style="margin-top: 10px;"></VField>
                          </VControl>

                        </VField>
                        <VField style="margin-bottom: 0; margin-right: 20px;">
                          <VControl raw subcontrol>
                            <span style="margin-right: 15px;"> 3</span>
                            <VCheckbox v-model="input.checkbox3" class="p-0" color="primary" square
                              style="transform: scale(1.5); width: 20px; height: 20px;" />
                            <VField label="≤ 30 menit" style="margin-top: 10px;"></VField>
                          </VControl>

                        </VField>
                        <VField style="margin-bottom: 0; margin-right: 20px;">
                          <VControl raw subcontrol>
                            <span style="margin-right: 15px;"> 4</span>
                            <VCheckbox v-model="input.checkbox4" class="p-0" color="primary" square
                              style="transform: scale(1.5); width: 20px; height: 20px;" />
                            <VField label="≤ 60 menit" style="margin-top: 10px;"></VField>
                          </VControl>

                        </VField>
                        <VField style="margin-bottom: 0; margin-right: 20px;">
                          <VControl raw subcontrol>
                            <span style="margin-right: 15px;"> 5</span>
                            <VCheckbox v-model="input.checkbox5" class="p-0" color="primary" square
                              style="transform: scale(1.5); width: 20px; height: 20px;" />
                            <VField label="≤ 120 menit" style="margin-top: 10px;"></VField>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="6" style="height: 50px;">
                    <div class="column is-12">
                      <span><b>Disposisi :</b></span>
                      <div class="columns is-multiline p-3">
                        <div class="columns is-flex is-align-items-center is-flex-wrap-wrap">
                          <!-- Checkboxes with labels -->
                          <div class="column is-narrow" v-for="(data, index) in Disposisi" :key="index"
                            style="display: flex; align-items: center; margin-right: 20px;">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <VField addons>
                            <VField style="margin-left: -40px;"></VField>
                            <VControl expanded>
                              <VInput type="text" class="input" v-model="input.jenis_kateter" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>


                  </td>
                </tr>

              </tbody>
            </table>

          </div>


        </Fieldset>
      </div>


    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'



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

let Airway = [
  {
    label: "Bebas",
    model: "bebas1",
  },
  {
    label: "Gargling",
    model: "Gargling1",
  },
  {
    label: "Stridor",
    model: "Stridor1",
  },
  {
    label: "Wheezing",
    model: "Wheezing1",
  },
  {
    label: "Ronchi",
    model: "Ronchi1",
  },
  {
    label: "Terintubasi",
    model: "Terintubasi1",
  }
]

let Disposisi = [
  {
    label: "Resuscitation Call",
    model: "ResuscitationCall1",
  },
  {
    label: "Green Code",
    model: "GreenCode1",
  },
  {
    label: "Resusitasi Intrauterin",
    model: "ResusitasiIntrauterin1",
  },
  {
    label: "OHDU",
    model: "OHDU1",
  },
  {
    label: "ICU",
    model: "ICU1",
  },
  {
    label: "Ruang Isolasi",
    model: "RuangIsolasi1",
  },
  {
    label: "VK",
    model: "VK1",
  },
  {
    label: "Ruang Tindakan",
    model: "RuangTindakan1",
  },
  {
    label: "Boleh Pulang",
    model: "BolehPulang1",
  },
  {
    label: "Ruangan",
    model: "Ruangan",
  }
]

let Lain_lain2 = [
  {
    label: "Trauma mayor",
    model: "TraumaMayor1",
  },
  {
    label: "Sesak napas",
    model: "SesakNapas1",
  },
  {
    label: "Partus rujukan bidan/dukun",
    model: "Partus1",
  },
  {
    label: "Paska kemoterapi",
    model: "Paskakemoterapi1",
  },
  {
    label: "Immunocompromised",
    model: "Immunocompromised1",
  },
  {
    label: "Oligouria",
    model: "Oligouria1",
  },
  {
    label: "Torsi kista",
    model: "Torsikista1",
  },
  {
    label: "Anaphilaxis",
    model: "Anaphilaxis1",
  },
  {
    label: "Nyeri berat (8-10)",
    model: "NyeriBerat1",
  },
]
let Breathing = [
  {
    label: "Spontan",
    model: "Spontan1",
  },
  {
    label: "Tachipneu",
    model: "Tachipneu1",
  },
  {
    label: "Dispneu",
    model: "Dispneu1",
  },
  {
    label: "Apneu",
    model: "Apneu1",
  },
  {
    label: "Ventilasi mekanik",
    model: "Ventilasi mekanik1",
  },
  {
    label: "Memakai ventilator",
    model: "Memakai ventilator1",
  }
]
let Lain_lain1 = [
  {
    label: "Nyeri perut berat akut",
    model: "Nyeriperut",
  },
  {
    label: "Penurunan kesadaran",
    model: "kesadaran1",
  },
  {
    label: "Prolaps tali pusat",
    model: "Prolapstalipusat1",
  },
  {
    label: "Distress napas berat",
    model: "berat1",
  },
  {
    label: "Suspek sepsis",
    model: "SuspekSepsis1",
  }
]

let Lain_lain3 = [
  {
    label: "Nyeri abdomen/punggung yang lebih berat dalam kehamilan",
    model: "Nyeriabdomen",
  },
  {
    label: "Nyeri pinggang/hematuria",
    model: "Nyeripinggang",
  },
  {
    label: "Mual/muntah dan/atau diare dengan suspek dehidrasi",
    model: "MualMuntah",
  },
  {
    label: "Nyeri akut sedang (4-7)",
    model: "Nyeriakutsedang",
  },
  {
    label: "Dialisis",
    model: "Dialisis1",
  }
]

let Lain_lain4 = [
  {
    label: "Penilaian lanjutan dari poliklinik (hipertensi,DL)",
    model: "PenilaianLanjutan",
  },
  {
    label: "Trauma minor (kecelakaan lalu lintas ringan/jatuh",
    model: "TraumaMinor",
  },
  {
    label: "Mual/muntah dan/diare",
    model: "MualMuntah2",
  },
  {
    label: "Tanda infeksi (disuria, batuk, demam, menggigil)",
    model: "TandaInfeksi",
  }
]

let Lain_lain5 = [
  {
    label: "Hal-hal yang tidak menimbulkan ancaman bagi ibu atau fetus",
    model: "yangtidakmenimbulkanancaman",
  },
  {
    label: "Pemberian pematangan serviks",
    model: "Pemberianpematangan",
  },
  {
    label: "Plasenta previa tanpa indikasi rawat inap",
    model: "PlasentaPrevia",
  },
  {
    label: "ANC",
    model: "ANC1",
  },
  {
    label: "Rencana versi",
    model: "RencanaVersi1",
  },
  {
    label: "Rashes",
    model: "Rashes1",
  }
]

let Nadi1 = [
  {
    label: "Kuat",
    model: "Kuat1"
  },
  {
    label: "Lemah",
    model: "Lemah1"
  }
]

let Turgorkulit = [
  {
    label: "Baik",
    model: "Baik1"
  },
  {
    label: "Buruk",
    model: "Buruk1"
  }
]

let Urgent = [
  {
    label: "Perdarahan disertai keram > 37 minggu",
    model: "Perdarahan5"
  },
  {
    label: "Perdarahan trisemester pertama tanpa nyeri perut",
    model: "Perdarahan6"
  }
]

let CRT1 = [
  {
    label: "<2'",
    model: "kurangdari2"
  },
  {
    label: ">2'",
    model: "lebihdari2"
  }
]

let Nyeri1 = [
  {
    label: "Tidak",
    model: "Tidak2"
  },
  {
    label: "Ya",
    model: "Ya2"
  }
]

let Jenis1 = [
  {
    label: "Akut",
    model: "Akut2"
  },
  {
    label: "Kronis",
    model: "Kronis2"
  }
]

let Resusitative = [
  {
    label: "Perdarahan per vaginam 500 ml/30 menit dengan/tanpa nyeri perut",
    model: "Perdarahan2"
  },
  {
    label: "Kehamilan ektopik terganggu",
    model: "Kehamilan2"
  },
  {
    label: "Inversio uteri akut",
    modal: "Inversio2"
  }
]

let WarnaKulit = [
  {
    label: "Normal",
    model: "Normal1"
  },
  {
    label: "Pucat",
    model: "Pucat1"
  },
  {
    label: "Kuning",
    model: "Kuning1"
  }
]

let Emergent = [
  {
    label: "Perdarahan disertai keram < 37 tahun",
    model: "Perdarahan3"
  },
  {
    label: "Kehamilan ektopik",
    model: "Kehamilan3"
  },
  {
    label: "Perdarahan",
    model: "Perdarahan4"
  }
]

let Emergent2 = [
  {
    label: "Denyut jantung janin takikardia (>160 dpm)",
    model: "DenyutJantung"
  },
  {
    label: "Oligohidramnion",
    model: "Oligohidramnion1"
  },
  {
    label: "Penurunan gerak janin",
    model: "Penurunangerajanin"
  }
]

let Respon1 = [
  {
    label: "Alert",
    model: "Alert1"
  },
  {
    label: "Verbal",
    model: "Verbal1"
  },
  {
    label: "Pain",
    model: "Pain1"
  },
  {
    label: "Unrespons",
    model: "Unrespons1"
  }
]

let Pupil1 = [
  {
    label: "Isokor",
    model: "Isokor1"
  },
  {
    label: "Pin point",
    model: "Pin point1"
  },
  {
    label: "Anisokor",
    model: "Anisokor1"
  },
  {
    label: "Midriasis",
    model: "Midriasis1"
  }
]

let PerdarahanPerVaginam = [
  {
    label: "Tidak Ada",
    model: "TidakAda1"
  },
  {
    label: "Terkontrol",
    model: "Terkontrol1"
  },
  {
    label: "Tidak Terkontrol",
    model: "Tidak1"
  }
]

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Petugas: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPasien)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganPasien = H.tandaTangan().get("signature_1")
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    } else {
      H.tandaTangan().set("signature_" + i, '')
    }
  })
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
th,
.triase td {
  border: 1px solid black;
}

table.triase,
th {
  // text-align: center;

}

.bg-green {
  background-color: var(--primary);
}

.bg-warning {
  background-color: var(--warning);
}

.bg-danger {
  background-color: var(--danger);
}

.triase th,
td {
  padding: 8px;
  vertical-align: top !important;
}
</style>
