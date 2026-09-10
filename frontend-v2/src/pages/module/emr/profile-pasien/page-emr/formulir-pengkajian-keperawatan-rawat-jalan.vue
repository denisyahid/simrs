<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                :disabled="isDisabled" @click="print"> Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              NAMA PASIEN
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" type="text" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              TANGGAL LAHIR PASIEN
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
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
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              JENIS KELAMIN
            </h1>
          </div>
          <div class="column is-10" style="display: flex;">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1 " :true-value="items.label"
                  :label="items.label" color="primary" circle />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              No. Rekam Medis
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput type="number" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              TANGGAL Kunjungan
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalKunjunganPasien" mode="dateTime" style="width: 100%" trim-weeks
                :max-date="new Date()">
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
          <div class="column is-2">
            <h1 style="font-weight: bold;">
              Unit Kerja
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="Unit Kerja" v-model="input.unitKerja" />
              </VControl>
            </VField>
          </div>

          <div class="column is-2">
            <h1 style="font-weight: bold;">
              Alasan Kunjungan
            </h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VTextarea v-model="input.alasanKunjungan" rows="1"></VTextarea>
              </VControl>
            </VField>
          </div>

          <div class="column is-10">
            <h1 style="font-weight: bold;">
              Riwayat Psikososial
            </h1>
          </div>

          <div class="column is-2"></div>
          <div class="column is-4">
            <p>
              Hubungan Pasien dengan Anggota Keluarga:
            </p>
          </div>

          <div class="column is-4" style="display: flex;">
            <VField v-for="items in RiwayatPsikososial" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.riwayatPsikososial" class="pt-1 pb-1 " :true-value="items.label"
                  :label="items.label" color="primary" square />
              </VControl>
            </VField>
          </div>

          <div class="column is-2"></div>
          <div class="column is-5">
            <div class="columns is-multiline">
              <VField>
                <VControl>
                  <VCheckbox v-model="input.keyakinan" true-value="Keyakinan" label="Keyakinan" color="primary"
                    square />
                </VControl>
              </VField>
              <Vfield>
                <VControl>
                  <VInput type="text" v-model="input.keyakinanDetail" placeholder="Detail keyakinan" />
                </VControl>
              </Vfield>
            </div>
          </div>

          <div class="column is-6">
            <div class="columns is-multiline">
              <VField>
                <VControl>
                  <VCheckbox v-model="input.kebutuhanPrivasiTambahan" true-value="Kebutuhan Privasi Tambahan"
                    label="Kebutuhan Privasi Tambahan" color="primary" square />
                </VControl>
              </VField>
              <Vfield>
                <VControl>
                  <VInput type="text" v-model="input.kebutuhanPrivasiTambahan"
                    placeholder="Detail Kebutuhan Privasi Tambahan" />
                </VControl>
              </Vfield>
            </div>
          </div>
          <div class="column is-12">
            <h1 style="font-weight: bold;">
              Status Psikologis
            </h1>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VCheckbox v-model="input.tenang" true-value="Tenang" label="Tenang" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VCheckbox v-model="input.Cemas" true-value="Cemas" label="Cemas" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VCheckbox v-model="input.Takut" true-value="Takut" label="Takut" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VCheckbox v-model="input.Marah" true-value="Marah" label="Marah" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VControl>
                <VCheckbox v-model="input.Sedih" true-value="Sedih" label="Sedih" color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-2"></div>
          <div class="column is-5">
            <VField>
              <VControl>
                <VCheckbox v-model="input.KecenderunganBunuhDiri" label="Kecenderungan bunuh diri dilaporkan ke :"
                  color="primary" square />
              </VControl>
            </VField>
          </div>
          <div class="column is-7">
            <VField>
              <VControl>
                <VInput v-model="input.keteranganKecenderunganBunuhDiri"
                  label="Kecenderungan bunuh diri dilaporkan ke :" color="primary" square placeholder="Keterangan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <h1 style="font-weight: bold;">
              PEMERIKSAAN FISIK DAN SKRINING GIZI :
            </h1>
          </div>
          <div class="columns is-multiline p-3">
            <div class="column is-3" v-for="(data, i) in vitalSign ">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> {{ data.label }} : </span>
                </div>
                <div class="column is-12">
                  <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                  <VField addons v-else>
                    <VControl>
                      <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>{{ data.addon }} </VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <Fieldset class="p-fieldsets" legend="MST" :toggleable="true">
              <h1 style="font-weight: bold;">Berdasarkan Mainutrition Screening Tooll (MST)</h1>
              <div class="column is-12">
                <div class="columns is-multiline px-5" style="border-bottom: 1px solid;">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-10">
                        <h1 class="emr">Parameter</h1>
                      </div>
                      <div class="column is-2">
                        <h1 class="emr">Nilai</h1>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline px-3">
                  <div class="column is-12" v-for="(data, i) in skriningGizi">
                    <span class="label-pengkajian"> {{ data.label }}</span>
                    <div class="px-3">
                      <div class=" px-1" v-for="(dataDetail, i) in data.children">
                        <div class="columns is-multiline">
                          <div class="column is-8">
                            <span class="label-pengkajian"> {{ dataDetail.label }}</span>
                          </div>
                          <div class="column is-2">
                            <VField v-if="dataDetail.children == null">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[dataDetail.model]" :true-value="dataDetail.value"
                                  :label="dataDetail.text" class="p-0" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-2">
                            {{ dataDetail.value }}
                          </div>
                        </div>
                        <div class="px-1" v-if="dataDetail.children">
                          <div class="columns is-multiline px-1" v-for="(dataDetailChildren, i) in dataDetail.children">
                            <div class="column is-8">
                            </div>
                            <div class="column is-2">
                              <VField>
                                <VControl raw subcontrol>
                                  <VCheckbox v-model="input[dataDetailChildren.model]"
                                    :true-value="dataDetailChildren.value" :label="dataDetailChildren.label" class="p-0"
                                    color="primary" square />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column is-2">
                              {{ dataDetailChildren.value }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-10">
                    <div class="is-pulled-right">
                      <span style="font-weight: bold;font-size:15px;" class="label-pengkajian mt-3">Total
                        Skor</span>
                      <VField class="pt-3">
                        <VControl>
                          <VInput type="text" v-model="input.totalSkor" disabled />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column is-12">
                    <span class="label-pengkajian"> 3. Pasien dengan diagnosis khusus</span>
                    <div class="columns is-multiline p-3">
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.adaDiagnosaKhusus" true-value="Ya" label="Ya" class="p-0"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.adaDiagnosaKhusus" true-value="Tidak" label="Tidak" class="p-0"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="columns is-multiline px-3">
                      <div class="column is-2" v-if="input.adaDiagnosaKhusus == 'Ya'"
                        v-for="(data, index) in diagnosaKhusus">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12" v-if="input.diagnosaKhusus == 'lain_lain'">
                        <VField label="Diagnosa Lain">
                          <VTextarea rows="2" placeholder="Diagnosa Lain......" v-model="input.diagnosaLain">
                          </VTextarea>
                        </VField>
                      </div>
                    </div>
                    <div class="columns is-multiline px-3">
                      <div class="column is-8">
                        <p>DM / Kemoterapi / Hemodialisa / Geriatri / Immunitas menurun / lain-lain, sebutkan : </p>
                      </div>
                      <div class="column is-4">
                        <VField>
                          <VControl>
                            <VInput v-model="input.keteranganDiagnosaKhusus" label="Keterangan" color="primary" square
                              placeholder="Keterangan" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="columns is-multiline px-3">
                      <div class="column is-12">
                        <p style="font-weight: bold;font-size:12px;">(Bila skor ≥ 2 dan atau pasien dengan diagnosi s/
                          kondisi
                          khusus
                          dilaporkan ke dokter
                          pemeriksa)</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <h1 class="mb-3 emr" style="font-weight: bold;">STATUS FUNGSIONAL</h1>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.mandiri" true-value="Mandiri" label="Mandiri" color="primary" square />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.perluBantuan" true-value="Perlu Bantuan"
                        label="Perlu Bantuan, sebutkan:" color="primary" square />
                    </VControl>
                  </VField>
                  <div class="column is-8">
                    <Vfield>
                      <VControl>
                        <VInput type="text" v-model="input.detailPerluBantuan" placeholder="Keterangan Perlu Bantuan" />
                      </VControl>
                    </Vfield>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.ketergantunganTotal" true-value="ketergantunganTotal"
                        label="Ketergantungan total, dilaporkan ke dokter pukul :" color="primary" square />
                    </VControl>
                  </VField>
                  <div class="column is-7">
                    <Vfield>
                      <VControl>
                        <VInput type="text" v-model="input.detailketergantunganTotal"
                          placeholder="Keterangan Perlu Bantuan" />
                      </VControl>
                    </Vfield>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <Fieldset class="p-fieldsets" legend="Resiko Jatuh" :toggleable="true">
                  <div class="column is-12">
                    <div class="columns is-multiline">

                      <div class="column is-12">
                        <h1 style="font-weight: bold;">1.&nbsp Perhatikan cara
                          berjalan pasien saat akan
                          duduk
                          dikursi, apakah pasien tampak
                          seimbang(sempoyongan/limbung) ?</h1>
                        <div class="columns is-mulitiline">
                          <div class="column is-2" v-for="(data) in Pilihan">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.title" :label="data.title"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                      <div class="column is-12">

                        <h1 style="font-weight: bold;">2.&nbsp Apakah pasien
                          memegang pinggiran kursi atau
                          meja
                          atau benda lain sebagai penopang saat akan duduk ?
                        </h1>
                        <div class="columns is-mulitiline">
                          <div class="column is-2" v-for="(data) in pegangKursisaatDuduk">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.title" :label="data.title"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                      <div class="column is-12">

                        <h1 style="font-weight: bold;">3.&nbsp Hasil
                        </h1>
                        <div class="columns is-mulitiline">
                          <div class="column is-4" v-for="(data) in hasilResikoJatuh">
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input[data.model]" :true-value="data.title" :label="data.title"
                                  color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </Fieldset>
              </div>

              <div class="column is-12 is-mulitiline">

                <h1 style="font-weight: bold;">
                  Diberitahukan ke dokter :
                </h1>
                <div class="columns is-mulitiline">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.yaDibertihukan" true-value="yaDibertihukan" label="Ya, pukul:"
                        color="primary" square />
                    </VControl>
                  </VField>
                  <div class="column is-9">
                    <VField>
                      <VDatePicker v-model="input.tanggalKunjunganPasien" mode="dateTime" style="width: 100%">
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
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.tidakDiberitahukan" true-value="tidakDiberitahukan" label="Tidak"
                        color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>

              <div class="column is-12">
                <Fieldset class="p-fieldsets" legend="1.PENILAIAN NYERI" :toggleable="true">
                  <div class="columns is-multiline p-3">
                    <div class="columns is-multiline is-12 pl-1 pr-5 pt-5 pb-0">
                      <div class="column is-7">
                        <h1 style="font-weight: bold;">BERAPAKAH SKALA NYERI ANDA ? </h1>
                        <div class="columns pt-4">
                          <div class="column" style="text-align: center;" v-for="(image, i) in listImageNyeri.detail">
                            <VAvatar size="medium" :picture="image.img" style="cursor:pointer !important"
                              :class="isAktive == i ? 'active' : ''" @click="skor(image, i)" />
                            <p>{{ image.descNilai }}</p>
                            <p>{{ image.nama }}</p>
                          </div>
                        </div>
                      </div>
                      <div class="column is-5">
                        <h1 style="font-weight: bold;">Score</h1>
                        <div class="pt-4">
                          <VField v-for="(skor) in listSkoringNyeri.detail">
                            <VControl raw subcontrol class="p-0">
                              <VCheckbox class="pt-0" v-model="input.skoringNyeri" :true-value="skor.descNilai"
                                :label="skor.nama" color="primary" circle />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                      <h1 style="font-weight: bold;">SKRINING NYERI :</h1>
                      <div class="columns is-multiline px-3 mt-5">
                        <div class="column is-12" v-for="(data, index) in skriningNyeri">
                          <span class="label-pengkajian">{{ data.label }}</span>
                          <div class="columns is-flex is-multiline mt-3 px-3">
                            <div class="column is-3" v-for="(detail, index2) in data.children">
                              <VField v-if="detail.type == 'checkbox'">
                                <VControl raw subcontrol>
                                  <VCheckbox v-model="input[detail.model]" :true-value="detail.value"
                                    :label="detail.label" class="p-0" color="primary" square />
                                </VControl>
                              </VField>
                              <div class="column" v-if="detail.type == 'date'">
                                <VField label="Dilaporkan Pada Dokter">
                                  <VDatePicker v-model="input.tanggalWaktuNyeriDiberitahuanPadaDokter" mode="dateTime"
                                    style="width: 100%" trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                      <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                          <VInput :value="inputValue" placeholder="Tanggal Waktu Kunjungan"
                                            v-on="inputEvents" />
                                        </VControl>
                                      </VField>
                                    </template>
                                  </VDatePicker>
                                </VField>
                              </div>
                              <VField v-if="detail.type == 'text'" :label="detail.label">
                                <VControl>
                                  <VInput type="text" class="input" :placeholder="detail.label"
                                    v-model="input[detail.model]" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </Fieldset>
              </div>

              <div class="column is-12">
                <h1 class="title-pri">MASALAH KEPERAWATAN</h1>
                <VCard>
                  <div class="column is-12" style="overflow-y: auto;">
                    <table class="table-pri">
                      <thead>
                        <tr class="tr-pri">
                          <th class="th-pri" style="text-align: center;">Masalah Keperawatan</th>
                          <th class="th-pri" style="text-align: center;">TUJUAN / TARGET TERUKUR</th>

                        </tr>
                      </thead>
                      <tbody>
                        <tr class="tr-pri">
                          <td class="td-pri">
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.masalahKeperawatan" class="input" type="text" />
                              </VControl>
                            </VField>
                          </td>
                          <td class="td-pri">
                            <VField>
                              <VControl>
                                <VTextarea v-model="input.tujuanTargetTerukur" class="input" type="text" />
                              </VControl>
                            </VField>
                          </td>

                        </tr>
                      </tbody>
                    </table>
                  </div>
                </VCard>
              </div>

              <div class="column is-12">
                <div class="column is-8"></div>
                <div class="column is-4">
                  <VField label="Garut">
                    <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </VField>
                  <div class="column" style="text-align:center;">
                    <h1>Tanda Tangan</h1>
                    <TandaTangan :elemenID="'TTDperawat1'" :width="'150'" :height="'150'" class="dek" />
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.CBBidan" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </VCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';
import * as EMR from '../page-emr-plugins/pengkajian-keperawatan-rawat-jalan'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string

let vitalSign = ref(EMR.vitalSign())
let skriningGizi = ref(EMR.skriningGizi())
let diagnosaKhusus = ref(EMR.diagnosaKhusus())
let Pilihan = ref(EMR.Pilihan())
let pegangKursisaatDuduk = ref(EMR.pegangKursisaatDuduk())
let hasilResikoJatuh = ref(EMR.hasilResikoJatuh())
let skriningNyeri = ref(EMR.skriningNyeri())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let JenisKelamin: any = ref(EMR.JenisKelamin())

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
const RiwayatPsikososial: any = ref([
  { label: 'Baik', value: 'Baik' },
  { label: 'Tidak Baik', value: 'Tidak Baik' }
])

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const dataTTD: any = ref([])
const d_Perawat: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('FormulirPermintaanKonselingGizi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
  details: [],
})
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
      if (response.length > 0) {
        isDisabled.value = false
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
      } else {
        isDisabled.value = true
      }
    })
    H.tandaTangan().set("TTDperawat1", dataTTD.value.TTDperawat)
}


const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}

const getDataExist = async () => {
  await useApi().get(
    `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  ).then((response) => {
    // input.value.beratBadan = response.beratBadan
    // input.value.tinggiBadan = response.tinggiBadan
    // input.value.IMT = response.IMT
    console.log()
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.riwayatPsikososial = RiwayatPsikososial.value
  object['TTDperawat1'] = H.tandaTangan().get("TTDperawat1")
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
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const print = async () => {
  H.printBlade(`emr/cetak-formulir-permintaan-konseling-gizi?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tglPembuatan = new Date()

  isLoadingVitalSign.value = true;
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if (response != null) {
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.suhu = response.suhu
      input.value.nadi = response.nadi
    }
    isLoadingVitalSign.value = false;
  })
}

watch(() => [
  input.value.turunBeratBadan,
  input.value.tidakAdaTurunBeratBadan,
  input.value.asupanMakan,
], () => {


  let poin1 = input.value.turunBeratBadan ? parseInt(input.value.turunBeratBadan) : 0
  let poin2 = input.value.tidakAdaTurunBeratBadan ? parseInt(input.value.tidakAdaTurunBeratBadan) : 0
  let poin3 = input.value.asupanMakan ? parseInt(input.value.asupanMakan) : 0

  const total = poin1 + poin2 + poin3
  input.value.totalSkor = total
})

setView()
getDataExist()
loadRiwayat()
setAutoFill()
fetchPerawat( { query: ''} )
</script>
