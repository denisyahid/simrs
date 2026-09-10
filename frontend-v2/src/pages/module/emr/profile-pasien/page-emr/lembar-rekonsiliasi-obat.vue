<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12 ">
      <VCard>
        <div class="columns is-multiline p-2">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <h1 style="font-weight : bold; margin-bottom: 1rem;">Alamat</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.alamatlengkap" placeholder="Alamat" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight : bold; margin-bottom: 1rem;"> Tanggal Masuk RS</h1>
                  <VField class="mt-2">
                    <VDatePicker v-model="input.tglregis" mode="dateTime" style="width: 100%" trim-weeks
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
                <div class="column is-4">
                  <h1 style="font-weight : bold; margin-bottom: 1rem;"> Status Pasien</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.status" placeholder="Status Pasien" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight : bold; margin-bottom: 1rem;">Dokter Ruangan</h1>
                  <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.dokterRuangan" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ketik untuk mencari..."
                        class="mt-2" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight : bold; margin-bottom: 1.5rem;">Alergi Obat</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.alergi" placeholder="Aleri Obat" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <h1 style="font-weight : bold; margin-bottom: 1.5rem;">Sumber Informasi</h1>
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.sumberInformasi" placeholder="Sumber Informasi" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="columns is-multiline">
          <div class="column is-12">
            <Fieldset :toggleable="true" legend="Obat Dibawa Pasien Masuk IGD/Masuk Rawat Inap Dari Poliknik">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <td class="tg-0lax text-center">No</td>
                      <td class="tg-0lax text-center">Nama Obat</td>
                      <td class="tg-0lax text-center">Jumlah</td>
                      <td class="tg-0lax text-center">Dosis</td>
                      <td class="tg-0lax text-center">Frekuensi</td>
                      <td class="tg-0lax text-center">Rute</td>
                      <td class="tg-0lax text-center">Obat dilanjutkan saat masuk?</td>
                      <td class="tg-0lax text-center">Obat dilanjutkan saat pulang</td>
                      <td class="tg-0lax text-center">#</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in input.detailObatDibawaPasien" :key="index">
                      <td>{{ data.no }}</td>
                      <td width="20%">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="data.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                              :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-input"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                              placeholder="ketik untuk mencari..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.aturanpakaitxt" placeholder="Aturan Pakai.."
                              class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.dosis" placeholder="Dosis..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.frekuensi" placeholder="Frekuensi..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.rute" placeholder="Rute..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatMasuk" class="pt-1 pb-1 "
                                true-value="Sudah Mengerti" label="Ya" color="primary" square />
                            </VControl>
                          </VField>
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatMasuk" class="pt-1 pb-1 " true-value="Re-Edukasi"
                                label="Tidak" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatPulang" class="pt-1 pb-1 "
                                true-value="Sudah Mengerti" label="Ya" color="primary" square />
                            </VControl>
                          </VField>
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatPulang" class="pt-1 pb-1 " true-value="Re-Edukasi"
                                label="Tidak" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-fkprj" style="vertical-align: inherit;">
                        <div class="column">
                          <VIconButton type="button" raised circle icon="feather:plus"
                            @click="addNewItemObatDibawaPulang()" color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeItemObatDibawaPulang(index)" color="danger">
                          </VIconButton>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="column is-8">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
                    <VField class="mt-3">
                      <VDatePicker v-model="input.tglPembuatanBawaPulang" mode="dateTime" style="width: 100%" trim-weeks
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
                </div>
              </div>
              <div class="column is-8">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-6 ">
                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold;">Perawat </h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.perawatObatDibawaPasien" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 is-offset-2">
                    <h1 class="p-0" style="font-weight: bold;">Apoteker</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.apotekerObatDibawaPasien" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12 mt-5">
            <Fieldset :toggleable="true" legend="Data Obat Selama Perawatan IGD">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <td class="tg-0lax text-center">No</td>
                      <td class="tg-0lax text-center">Nama Obat</td>
                      <td class="tg-0lax text-center">Jumlah</td>
                      <td class="tg-0lax text-center">kukuatan</td>
                      <td class="tg-0lax text-center">Frekuensi</td>
                      <td class="tg-0lax text-center">Rute</td>
                      <td class="tg-0lax text-center">Obat dilanjutkan saat masuk?</td>
                      <td class="tg-0lax text-center">Obat dilanjutkan saat pulang</td>
                      <td class="tg-0lax text-center">#</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in input.detailObatPerawatanIGD" :key="index">
                      <td>{{ data.no }}</td>
                      <td width="20%">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="data.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                              :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-input"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                              placeholder="ketik untuk mencari..."/>
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.aturanpakaitxt" placeholder="Aturan Pakai.."
                              class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.dosis" placeholder="Dosis..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.frekuensi" placeholder="Frekuensi..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.rute" placeholder="Rute..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatMasuk" class="pt-1 pb-1 "
                                true-value="Sudah Mengerti" label="Ya" color="primary" square />
                            </VControl>
                          </VField>
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatMasuk" class="pt-1 pb-1 " true-value="Re-Edukasi"
                                label="Tidak" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatPulang" class="pt-1 pb-1 "
                                true-value="Sudah Mengerti" label="Ya" color="primary" square />
                            </VControl>
                          </VField>
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatPulang" class="pt-1 pb-1 " true-value="Re-Edukasi"
                                label="Tidak" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-fkprj" style="vertical-align: inherit;">
                        <div class="column">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemObatRawatIGD()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeItemObatRawatIGD(index)" color="danger">
                          </VIconButton>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="column is-8">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
                    <VField class="mt-3">
                      <VDatePicker v-model="input.tglPembuatanObatIGD" mode="dateTime" style="width: 100%" trim-weeks
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
                  <div class="column is-4">
                    <h1 style="font-weight: bold;"> Intruksi dokter </h1>
                    <VField class="mt-3">
                      <VControl>
                        <VTextarea rows="2" v-model="input.InstruksiDokter1" placeholder="Intruksi dokter..."
                          class="input" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-8">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <TandaTangan :elemenID="'signature_5'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-6 ">
                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold;">Perawat </h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.perawatObatIGD" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 is-offset-2">
                    <h1 class="p-0" style="font-weight: bold;">Apoteker</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.apotekerObatIGD" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
          <div class="column is-12 mt-5">
            <Fieldset :toggleable="true" legend="Data Obat Selama Perawatan Di Rawat Inap">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <td class="tg-0lax text-center">No</td>
                      <td class="tg-0lax text-center">Nama Obat</td>
                      <td class="tg-0lax text-center">Jumlah</td>
                      <td class="tg-0lax text-center">kukuatan</td>
                      <td class="tg-0lax text-center">Frekuensi</td>
                      <td class="tg-0lax text-center">Rute</td>
                      <td class="tg-0lax text-center">Obat dilanjutkan saat masuk?</td>
                      <td class="tg-0lax text-center">Obat dilanjutkan saat pulang</td>
                      <td class="tg-0lax text-center">#</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in input.detailObatRawatInap" :key="index">
                      <td>{{ data.no }}</td>
                      <td width="20%">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="data.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                              :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-input"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                              placeholder="ketik untuk mencari..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.aturanpakaitxt" placeholder="Aturan Pakai.."
                              class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.dosis" placeholder="Dosis..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.frekuensi" placeholder="Frekuensi..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="data.rute" placeholder="Rute..." class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatMasuk" class="pt-1 pb-1 "
                                true-value="Sudah Mengerti" label="Ya" color="primary" square />
                            </VControl>
                          </VField>
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatMasuk" class="pt-1 pb-1 " true-value="Re-Edukasi"
                                label="Tidak" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td>
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatPulang" class="pt-1 pb-1 "
                                true-value="Sudah Mengerti" label="Ya" color="primary" square />
                            </VControl>
                          </VField>
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="data.DilanjutkanSaatPulang" class="pt-1 pb-1 " true-value="Re-Edukasi"
                                label="Tidak" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-fkprj" style="vertical-align: inherit;">
                        <div class="column">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemObatRawatInap()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeItemObatRawatInap(index)" color="danger">
                          </VIconButton>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="column is-8">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <h1 style="font-weight: bold;"> Bandung, Tanggal </h1>
                    <VField class="mt-3">
                      <VDatePicker v-model="input.tglPembuatanObatIGD" mode="dateTime" style="width: 100%" trim-weeks
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
                </div>
              </div>
              <div class="column is-8">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <TandaTangan :elemenID="'signature_6'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-6 ">
                    <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold;">Perawat </h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.perawatObatDibawaPasien" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 is-offset-2">
                    <h1 class="p-0" style="font-weight: bold;">Apoteker</h1>
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="input.apotekerObatDibawaPasien" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Ketik untuk mencari..." class="mt-2" @item-select="setTandaTangan($event)" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
        </div>
      </VCard>
    </div>
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
import { useThemeColors } from '/@src/composable/useThemeColors'
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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_produk: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  detailObatDibawaPasien: [{ no: 1 }],
  detailObatPerawatanIGD: [{ no: 1 }],
  detailObatRawatInap: [{ no: 1 }],
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
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaTanganApotekerObatRawatMasukIGD) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganApotekerObatRawatMasukIGD)
        }
        if (response[0].tandaTanganApotekerRawatInap) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganApotekerRawatInap)
        }
        if (response[0].tandaTanganApotekerSelamatIGD) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganApotekerSelamatIGD)
        }
        if (response[0].tandaTanganDokterObatRawatMasukIGD) {
          H.tandaTangan().set("signature_4", response[0].tandaTanganDokterObatRawatMasukIGD)
        }
        if (response[0].tandaTanganDokterSelamatIGD) {
          H.tandaTangan().set("signature_5", response[0].tandaTanganDokterSelamatIGD)
        }
        if (response[0].tandaTanganDokterkerRawatInap) {
          H.tandaTangan().set("signature_6", response[0].tandaTanganDokterkerRawatInap)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganApotekerObatRawatMasukIGD = H.tandaTangan().get("signature_1")
  object.tandaTanganApotekerSelamatIGD = H.tandaTangan().get("signature_2")
  object.tandaTanganApotekerRawatInap = H.tandaTangan().get("signature_3")
  object.tandaTanganDokterObatRawatMasukIGD = H.tandaTangan().get("signature_4")
  object.tandaTanganDokterSelamatIGD = H.tandaTangan().get("signature_5")
  object.tandaTanganDokterkerRawatInap = H.tandaTangan().get("signature_6")
  object.pasien = H.setObjectPasien(props.pasien)
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

const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const addNewItemObatDibawaPulang = () => {
  input.value.detailObatDibawaPasien.push({
    no: input.value.detailObatDibawaPasien[input.value.detailObatDibawaPasien.length - 1].no + 1,
  });
}
const removeItemObatDibawaPulang = (index: any) => {
  input.value.detailObatDibawaPasien.splice(index, 1)
}
const addNewItemObatRawatIGD = () => {
  input.value.detailObatPerawatanIGD.push({
    no: input.value.detailObatPerawatanIGD[input.value.detailObatPerawatanIGD.length - 1].no + 1,
  });
}
const removeItemObatRawatIGD = (index: any) => {
  input.value.detailObatPerawatanIGD.splice(index, 1)
}
const addNewItemObatRawatInap = () => {
  input.value.detailObatRawatInap.push({
    no: input.value.detailObatRawatInap[input.value.detailObatRawatInap.length - 1].no + 1,
  });
}
const removeItemObatRawatInap = (index: any) => {
  input.value.detailObatRawatInap.splice(index, 1)
}


const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatanBawaPulang = new Date()
  input.value.tglPembuatanObatIGD = new Date()
  input.value.alamatlengkap = props.pasien.alamatlengkap
  input.value.tglregis = props.registrasi.tglregistrasi
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top;
  font-weight: bold;
}
</style>
