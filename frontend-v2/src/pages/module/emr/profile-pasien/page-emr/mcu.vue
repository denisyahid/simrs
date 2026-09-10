<template>
  <div class="form-layout is-stacked-2" style="
     width: 100%;
     max-width: none;">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}<span v-if="isStuck"></span></h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun">
                Kembali
              </VButton>
              <VButton color="primary" v-if="isMCU" raised>
                <SplitButton style="width: 100%" label="Cetakan" icon="pi pi-info-circle" :model="listButton"/>
              <!-- <div class="column is-4">
              </div> -->
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan('')"> Simpan
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
        <div class="columns is-multiline mt-3">
          <div class="column is-12">
            <h1 style="font-weight: bold;">Bagian Identitas</h1>
          </div>
          <div class="column is-multiline is-6">
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Nama Pasien  : </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama " v-model="input.namaPasien" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span>Tempat/Tgl. Lahir: </span>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input" placeholder="Tempat Lahir" v-model="input.tempatLahir" :tabindex="3"/>
                    </VControl>
                    <VControl class="field-addon-body">
                      <VInput type="text" class="input" placeholder="Tanggal Lahir" v-model="input.tglLahir" :tabindex="3"/>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <!-- <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> NIP: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="NIP " v-model="input.nip" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div> -->
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> NIK: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="NIK" v-model="input.nik" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <!-- <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Jabatan: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Jabatan" v-model="input.jabatan" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div> -->
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Alamat: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Alamat " v-model="input.alamat" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Permintaan: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <!-- <Multiselect mode="single" v-model="input.permintaan" :options="d_Keperluan" placeholder="Pilih data"
                    :searchable="true" autocomplete="off" track-by="value" /> -->
                    <VInput type="text" class="input" placeholder="Keperluan" v-model="input.permintaan" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-multiline is-6">
            <!-- <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Nama Kecil: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nama Kecil" v-model="input.namaKecilPasien" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div> -->
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Jenis Kelamin: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_JK" :key="items.id">
                                <VRadio v-model="input.jenisKelamin" :value="items.id" class="p-0 mb-3"
                                    :label="items.jeniskelamin" name="JK" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <!-- <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> No. Karpeg: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="No. Karpeg" v-model="input.karpeg" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div> -->
            <!-- <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Gol. Ruang: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Gol. Ruang" v-model="input.golRuang" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div> -->
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Nomor Surat: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Nomor Surat" v-model="input.nomorsurat" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Tanggal Surat: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <Calendar v-model="input.tgl" selectionMode="single" :manualInput="true" class="w-100"
                            :showIcon="true" :date-format="'yy-mm-dd'"/>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          </div>
        <div class="column is-multiline mt-3">
          <div class="column is-12">
            <h1 style="font-weight: bold;">Bagian Vital Sign</h1>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Tekanan Darah: </span>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                        <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tensiPasien" :tabindex="3"/>
                    </VControl>
                    <VControl class="field-addon-body">
                        <VButton static :loading="isLoading">mmHG</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Nadi: </span>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                        <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadiPasien" :tabindex="3"/>
                    </VControl>
                    <VControl class="field-addon-body">
                        <VButton static :loading="isLoading">x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Pernafasan: </span>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                        <VInput type="text" class="input" placeholder="Pernafasan" v-model="input.pernafasanPasien" :tabindex="3"/>
                    </VControl>
                    <VControl class="field-addon-body">
                        <VButton static :loading="isLoading">x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Suhu Badan: </span>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                        <VInput type="text" class="input" placeholder="Suhu Badan" v-model="input.suhuPasien" :tabindex="3"/>
                    </VControl>
                    <VControl class="field-addon-body">
                        <VButton static :loading="isLoading"><sup>o</sup>C</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Tinggi Badan: </span>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                        <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.tinggiPasien" :tabindex="3"/>
                    </VControl>
                    <VControl class="field-addon-body">
                        <VButton static :loading="isLoading">cm</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Berat Badan: </span>
                </div>
                <div class="column is-9">
                  <VField addons>
                    <VControl expanded>
                        <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratPasien" :tabindex="3"/>
                    </VControl>
                    <VControl class="field-addon-body">
                        <VButton static :loading="isLoading">Kg</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
        </div>
        <div class="column is-multiline mt-3">
          <div class="column is-12">
            <h1 style="font-weight: bold;">Bagian SK Sehat</h1>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Dokter Pemeriksa: </span>
                </div>
                <div class="column is-9">
                  <VField class="is-autocomplete-select pt-3">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                        :field="'label'" placeholder="ketik Nama Dokter" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Hasil Pemeriksaan: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <div class="column is-4 p-0" v-for="items in d_HasilPemeriksaan" :key="items.id">
                            <VRadio v-model="input.hasilPemeriksaan" :value="items.label" class="p-0 mb-3"
                                :label="items.label" name="hasilPemeriksaan" square
                                color="primary" />
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Kondisi Penglihatan: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Kondisi Penglihatan" v-model="input.kondisiPenglihatan" />
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Kondisi Pendengaran: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Kondisi Pendengaran" v-model="input.kondisiPendengaran" />
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
        </div>
        <div class="column is-multiline mt-3">
          <div class="column is-12">
            <h1 style="font-weight: bold;">Bagian Kesehatan Jiwa</h1>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Tanda atau gejala gangguan jiwa yang bermakna: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_GangguanJiwa" :key="items.id">
                                <VRadio v-model="input.gangguanJiwa" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="gangguanJiwa" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Dokter Pemeriksa Kesehatan Jiwa: </span>
                </div>
                <div class="column is-9">
                  <VField class="is-autocomplete-select pt-3">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.dokterPemeriksaJiwa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                        :field="'label'" placeholder="ketik Nama Dokter" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
        </div>
        <div class="column is-multiline mt-3">
          <div class="column is-12">
            <h1 style="font-weight: bold;">Bagian Pemeriksaan NAPZA</h1>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Penampilan: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_Penampilan" :key="items.id">
                                <VRadio v-model="input.penampilan" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="penampilan" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Cara Berjalan: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_CaraBerjalan" :key="items.id">
                                <VRadio v-model="input.caraJalan" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="berjalan" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Cara Bicara: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_CaraBicara" :key="items.id">
                                <VRadio v-model="input.caraBicara" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="bicara" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Mata Pupil: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_MataPupil" :key="items.id">
                                <VRadio v-model="input.mataPupil" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="mataPupil" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Konjungtiva: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_Konjungtiva" :key="items.id">
                                <VRadio v-model="input.konjungtiva" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="konjungtiva" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Tremor: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_Tremor" :key="items.id">
                                <VRadio v-model="input.tremor" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="tremor" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Kulit: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_Kulit" :key="items.id">
                                <VRadio v-model="input.kulit" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="kulit" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Alur Pembicaraan: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_AlurPembicaraan" :key="items.id">
                                <VRadio v-model="input.alurPembicaraan" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="alurPembicaraan" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Waham: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_Waham" :key="items.id">
                                <VRadio v-model="input.waham" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="waham" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Halusinasi: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_Halusinasi" :key="items.id">
                                <VRadio v-model="input.halusinasi" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="halusinasi" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Lain-lain: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Lain-lain" v-model="input.lainlain" />
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Waktu Pemeriksaan: </span>
                </div>
                <div class="column is-9">
                  <VField>
                      <VControl class="prime-auto">
                        <Calendar v-model="input.tglPemeriksaanJiwa" selectionMode="single" :manualInput="true" class="w-100"
                          :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                      </VControl>
                    </VField>
                </div>
              </div>
            </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Metode Pemeriksaan Laboratorium: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Metode Laboratorium" v-model="input.metodeLaboratorium" />
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Cannabis: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_HasilMetode" :key="items.id">
                                <VRadio v-model="input.cannabis" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="cannabis" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Opiate: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_HasilMetode" :key="items.id">
                                <VRadio v-model="input.oppiate" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="oppiate" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Coccain: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_HasilMetode" :key="items.id">
                                <VRadio v-model="input.coccain" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="coccain" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Metamphetamine: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_HasilMetode" :key="items.id">
                                <VRadio v-model="input.metamphetamine" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="methamphetamine" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> MDMA: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_HasilMetode" :key="items.id">
                                <VRadio v-model="input.mdma" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="mdma" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Benzodiazepin: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_HasilMetode" :key="items.id">
                                <VRadio v-model="input.benzodiazepin" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="benzodiazepin" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Gejala-gejala penggunaan narkotika: </span>
                </div>
                <div class="column is-9">
                  <VField>
                    <VControl>
                        <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">

                            <div class="column is-4 p-0" v-for="items in d_GejalaNarkotika" :key="items.id">
                                <VRadio v-model="input.gejalaNarkotika" :value="items.label" class="p-0 mb-3"
                                    :label="items.label" name="gejalaNarkotika" square
                                    color="primary" />
                            </div>
                        </div>
                    </VControl>
                  </VField>
                </div>
              </div>
          </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Waktu Pemeriksaan Laboratorium: </span>
                </div>
                <div class="column is-9">
                  <VField>
                      <VControl class="prime-auto">
                        <Calendar v-model="input.tglPemeriksaanNapza" selectionMode="single" :manualInput="true" class="w-100"
                          :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                      </VControl>
                    </VField>
                </div>
              </div>
            </div>
          <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-3" style="margin-top:0.5rem">
                  <span> Dokter Pemeriksa NAPZA: </span>
                </div>
                <div class="column is-9">
                  <VField class="is-autocomplete-select pt-3">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.dokterPemeriksaNapza" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                        :field="'label'" placeholder="ketik Nama Dokter" />
                    </VControl>
                  </VField>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import * as EMR from '../page-emr-plugins/mcu'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import SplitButton from 'primevue/splitbutton';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
  defineProps<{
    pasien?: any
    alamat?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    alamat: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isMCU: any = ref(false)
let d_Penampilan = ref(EMR.Penampilan())
let d_GangguanJiwa = ref(EMR.GangguanJiwa())
let d_CaraBerjalan = ref(EMR.CaraBerjalan())
let d_CaraBicara = ref(EMR.CaraBicara())
let d_MataPupil = ref(EMR.MataPupil())
let d_Konjungtiva = ref(EMR.Konjungtiva())
let d_Tremor = ref(EMR.Tremor())
let d_Kulit = ref(EMR.Kulit())
let d_AlurPembicaraan = ref(EMR.AlurPembicaraan())
let d_HasilPemeriksaan = ref(EMR.HasilPemeriksaan())
let d_Waham = ref(EMR.Waham())
let d_Halusinasi = ref(EMR.Halusinasi())
let d_Keperluan = ref(EMR.Keperluan())
let d_HasilMetode = ref(EMR.HasilMetode())
let d_GejalaNarkotika = ref(EMR.GejalaNarkotika())
let d_JK = ref(EMR.JK())
const showPreviewResep:any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  selectedMenu: [false],
  filter: '',
  lab: [],
  lab_GROUP: [],
  radiologi: [],
  patologi: []
})
const pegawaiId = useUserSession().getUser().pegawai.id
const COLLECTION: any = ref('mcu') //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')

const input: any = ref({})
const riwayatResep: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const listButton : any = ref([
    {
      label: 'SK Sehat',
      icon: 'fas fa-print',
      command: () => {
        cetakSKS()
      }
    },
    {
      label: 'SKP Kesehatan Jiwa',
      icon: 'fas fa-print',
      command: () => {
        cetakSKJiwa()
      }
    },
    {
      label: 'SKP Napza',
      icon: 'fas fa-print',
      command: () => {
        cetakSKNapza()
      }
    },
  ])

const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        isMCU.value = true
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const cetakSKS = () => {
  H.printBlade(`report/cetak-sks?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}
const cetakSKJiwa = () => {
  H.printBlade(`report/cetak-skjiwa?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}
const cetakSKNapza = () => {
  H.printBlade(`report/cetak-sknapza?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const simpan = (e: any) => {
  let ID = input.value.id ? input.value.id : ''
  console.log(input.value);

    let object: any = {}
    input.value.tgl = H.formatDate(input.value.tgl, "YYYY-MM-DD")
    input.value.tglPemeriksaanJiwa = H.formatDate(input.value.tglPemeriksaanJiwa, "YYYY-MM-DD HH:mm")
    input.value.tglPemeriksaanNapza = H.formatDate(input.value.tglPemeriksaanNapza, "YYYY-MM-DD HH:mm")

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'mcu',
        'data': object
    }
    isLoading.value = true
    useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
      isMCU.value = true
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  console.log(props.pasien);
  console.log(props.alamat);

  isLoading.value = true
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2"
  ).then((response) => {
    // console.log(response);

    if (response == null) {
      isLoading.value = false
      input.value.tensiPasien = ''
      input.value.tinggiPasien = ''
      input.value.beratPasien = ''
      input.value.nadiPasien = ''
      input.value.pernafasanPasien = ''
      input.value.suhuPasien = ''
      return
    }
    isLoading.value = false
    input.value.tensiPasien = response.tekananDarah
    input.value.tinggiPasien = response.tinggiBadan
    input.value.beratPasien = response.beratBadan
    input.value.nadiPasien = response.nadi
    input.value.pernafasanPasien = response.pernapasan
    input.value.suhuPasien = response.suhu
  })
  // console.log(props.pasien);
  if(!isMCU.value){
      input.value.namaPasien = props.pasien.namapasien
      input.value.tgl = new Date()
      input.value.tglPemeriksaanJiwa = new Date()
      input.value.tglPemeriksaanNapza = new Date()
      const rtrw = props.alamat[0].rtrw ? ` RT/RW. ${props.alamat[0].rtrw}` : ''
      const desakelurahan = props.alamat[0].namadesakelurahan ? `, ${props.alamat[0].namadesakelurahan}` : ''
      const kecamatan = props.alamat[0].namakecamatan ? `, Kec. ${props.alamat[0].namakecamatan}` : ''
      const kotakabupaten = props.alamat[0].namakotakabupaten ? `, ${props.alamat[0].namakotakabupaten}` : ''
      input.value.alamat = `${props.pasien.alamatlengkap}${rtrw}${desakelurahan}${kecamatan}${kotakabupaten}`
      input.value.tempatLahir = props.pasien.tempatlahir
      input.value.tglLahir = props.pasien.tgllahir
      input.value.jenisKelamin = props.pasien.objectjeniskelaminfk
      input.value.nik = props.pasien.noidentitas
      input.value.hasilPemeriksaan = 'Sehat'
      input.value.metodeLaboratorium = 'Rapid Test'
      input.value.gangguanJiwa = 'Tidak Ditemukan'
      // input.value.permintaan = 'Persyaratan Pemberkasan PPPK'
      input.value.penampilan = 'Rapih'
      input.value.caraJalan = 'Biasa'
      input.value.caraBicara = 'Biasa'
      input.value.mataPupil = 'Normal'
      input.value.konjungtiva = 'Normal'
      input.value.tremor = 'Tidak ada'
      input.value.kulit = 'Tidak ada'
      input.value.gejalaNarkotika = 'Tidak ada'
      input.value.halusinasi = 'Tidak ada'
      input.value.alurPembicaraan = 'Normal'
      input.value.waham = 'Tidak ada'
      input.value.cannabis = 'Non Reaktif'
      input.value.oppiate = 'Non Reaktif'
      input.value.coccain = 'Non Reaktif'
      input.value.metamphetamine = 'Non Reaktif'
      input.value.mdma = 'Non Reaktif'
      input.value.benzodiazepin = 'Non Reaktif'
      return
    }
}

const clear = () => {
  item.filter = ''
  isPerawat.value = false
  isProfesi.value = false
  isDokter.value = false
}

setView()
loadRiwayat()
setAutoFill()
// fetchDiagnosaX()
</script>

<style lang="scss">
.CPPT_HEIGHT {
  overflow: auto;
  height: 600px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  background-color: var(--white);
  border-color: var(--fade-grey-dark-2) !important;
}

.tg-card {
  background-color: #feffed;
}

.is-dark {
  .tg {
    background-color: var(--dark-sidebar-light-6)
  }

  .tg-card {
    background-color: var(--dark-sidebar-light-6)
  }
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
}

.tg th {
  border-color: var(--fade-grey-dark-3) !important;
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
  vertical-align: top
}

.scroll-container-rev {
  height: 1000px;
  overflow: auto;
}

@media (max-width: 1144px) {

  .table-tg {
    width: 150%;
  }
}
</style>
