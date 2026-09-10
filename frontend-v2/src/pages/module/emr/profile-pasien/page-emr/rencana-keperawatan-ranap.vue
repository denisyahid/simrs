<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top: 15px">
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

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">Nama Pasien:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.namaPasien" class="input" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">No RM:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput v-model="input.norm" class="input" disabled />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
          </div>
          <div class="column is-10">
            <VField>
              <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" disabled />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </div>

        <div class="column is-flex p-0">
          <div class="column is-2">
            <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
          </div>
          <div class="column is-10" style="display: flex">
            <VField v-for="items in JenisKelamin" :key="items.value">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label" :label="items.label"
                  color="primary" disabled circle />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12">
          <BerkasRanap :pasien="props.pasien" :registrasi="props.registrasi"></BerkasRanap>
        </div>

        <div class="column is-12" v-for="(items, index) in input.details" :key="index">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VButtons style="justify-content:end">
                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                  v-tooltip.bubble="'Tambah '">
                </VIconButton>
                <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                  @click="removeItem(index)" color="danger">
                </VIconButton>
              </VButtons>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <div class="is-12">
                    <div class="is-flex">
                      <div class="column is-2">
                        <h1 style="font-weight: bold;" class="mb-3 emr">RENCANA KEPERAWATAN RAWAT INAP :</h1>
                      </div>
                      <div class="column is-10">
                        <VField class="is-autocomplete-select" v-slot="{ id }">
                          <VControl>
                            <Multiselect v-model="items.rencanaKeperawatanRawatInap" placeholder="--Pilih--"
                              label="label" :options="RencanaKeperawatanRawatInap" :searchable="true" track-by="label"
                              mode="single" autocomplete="off">
                            </Multiselect>
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Resiko Infeksi'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in ResikoInfeksi" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in ResikoInfeksi" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice5'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl style="width:60%;" class="control">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl> 
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Nyeri Melahirkan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in NyeriMelahirkan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in NyeriMelahirkan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Kesiapan Persalinan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in KesiapanPersalinan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in KesiapanPersalinan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Risiko Ketidakseimbangan Elektrolit'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoKetidakseimbanganElektrolit" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoKetidakseimbanganElektrolit" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Risiko Ketidakseimbangan Cairan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoKetidakseimbanganCairan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoKetidakseimbanganCairan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Kesiapan Peningkatan Keseimbangan Cairan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in KesiapanPeningkatanKeseimbanganCairan"
                              :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in KesiapanPeningkatanKeseimbanganCairan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Ikterik Neonatus'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoIkterikNeonatus" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoIkterikNeonatus" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Alergi'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoAlergi" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoAlergi" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Kesiapan Peningkatan Manajemen Kesehatan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in KesiapanPeningkatanManajemenKesehatan"
                              :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in KesiapanPeningkatanManajemenKesehatan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Kesiapan Peningkatan Pengetahuan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in KesiapanPeningkatanPengetahuan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in KesiapanPeningkatanPengetahuan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Keletihan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Keletihan" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Keletihan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Pola Tidur'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanPolaTidur" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanPolaTidur" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Memori'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanMemori" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanMemori" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Persepsi Sensori'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanPersepsiSensori" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanPersepsiSensori" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Bunuh Diri'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoBunuhDiri" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoBunuhDiri" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Risiko Perlambatan Pemulihan Pascabedah'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoPerlambatanPemulihanPascabedah"
                              :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoPerlambatanPemulihanPascabedah" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Luka Tekan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoLukaTekan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoLukaTekan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Jatuh'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoJatuh" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoJatuh" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Konstipasi'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Konstipasi" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Konstipasi" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline is-flex" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right: -20%">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Menyusui Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in MenyusuiTidakEfektif" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in MenyusuiTidakEfektif" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline is-flex" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right: -20%">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Menyusui Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in MenyusuiEfektif" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in MenyusuiEfektif" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline is-flex" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right: -20%">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Perdarahan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoPerdarahan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoPerdarahan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Pola Napas Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in PolaNapasTidakEfektif" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in PolaNapasTidakEfektif" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div
                    >

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Perfusi Perifer Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoPerfusiPeriferTidakEfektif" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoPerfusiPeriferTidakEfektif" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Penurunan Kapasitas Adaptif Intrakranial'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in PenurunanKapasitasAdaptifIntrakranial" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in PenurunanKapasitasAdaptifIntrakranial" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Ketidaknyamanan Pasca Persalinan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in KetidaknyamananPascaPersalinan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in KetidaknyamananPascaPersalinan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Perfusi Renal Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoPerfusiRenalTidakEfektif" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoPerfusiRenalTidakEfektif" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Perfusi Miokard Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in RisikoPerfusiMiokardTidakEfektif" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in RisikoPerfusiMiokardTidakEfektif" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Ikterik Neonatus'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in IkterikNeonatus" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in IkterikNeonatus" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Penurunan Curah Jantung'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in PenurunanCurahJantung" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in PenurunanCurahJantung" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'checkboxbold'">
                                    <VControl>
                                      <VCheckbox style="font-weight:bold;" class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Perfusi Perifer Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in PerfusiPeriferTidakEfektif" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in PerfusiPeriferTidakEfektif" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Gangguan Integritas Kulit / Jaringan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in ResikoGangguanIntegritasKulitJaringan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in ResikoGangguanIntegritasKulitJaringan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Nyeri Kronis'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in NyeriKronis" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in NyeriKronis" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice5'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl style="width:60%;" class="control">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl> 
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Nyeri Akut'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in NyeriAkut" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in NyeriAkut" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Nausea'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Nausea" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Nausea" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Konfusi Akut'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in KonfusiAkut" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in KonfusiAkut" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Ketidakstabilan Kadar Glukosa Darah'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in KetidakstabilanKadarGlukosaDarah" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in KetidakstabilanKadarGlukosaDarah" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <div class="columns is-multiline" v-if="item3.type == 'checkbox2'">
                                    <VField class="column is-6">
                                      <VControl>
                                        <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                      </VControl>
                                    </VField>
                                    <VField class="column is-6">
                                      <VControl>
                                        <VCheckbox class="fontcheckbox" v-model="items[item3.model2]"
                                          :label="item3.labelDetail2" :true-value="item3.value2" color="primary" circle />
                                      </VControl>
                                    </VField>
                                  </div>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkbox3'">
                                    <VControl class="column is-6">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-6">
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'text2'">
                                    <div class="column is-6">
                                      <p>
                                        {{ item3.labelDetail }}
                                      </p>
                                    </div>
                                    <div class="column is-6">
                                      <p>
                                        {{ item3.labelDetail2 }}
                                      </p>
                                    </div>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Keputusasaan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Keputusasaan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Keputusasaan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'checkboxbold'">
                                    <VControl>
                                      <VCheckbox style="font-weight: bold;" class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Intoleransi Aktivitas'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in IntoleransiAktivitas" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in IntoleransiAktivitas" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Inkontinensia Fekal'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in InkontinensiaFekal" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in InkontinensiaFekal" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="control">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Hipovolemia'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Hipovolemia" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Hipovolemia" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Hipervolemia'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Hipervolemia" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Hipervolemia" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Hipotermia'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Hipotermia" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Hipotermia" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <!-- <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Hipertermia'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Hipertermia" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Hipertermia" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div> -->

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Ventilasi Spontan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanVentilasiSpontan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanVentilasiSpontan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Sirkulasi Spontan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanSirkulasiSpontan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanSirkulasiSpontan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Pertukaran Gas'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanPertukaranGas" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanPertukaranGas" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxevm'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Mobilitas Fisik'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanMobilitasFisik" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanMobilitasFisik" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxevm'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Menelan Berhubungan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanMenelanBerhubungan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanMenelanBerhubungan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxevm'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Integritas Kulit / Jaringan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanIntegritasKulitJaringan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanIntegritasKulitJaringan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxevm'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Eliminasi Urine'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanEliminasiUrine" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanEliminasiUrine" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxevm'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Bersihan Jalan Nafas Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in BersihanJalanNafasTidakEfektif" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in BersihanJalanNafasTidakEfektif" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxevm'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Resiko Konfusi Akut'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in ResikoKonfusiAkut" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in ResikoKonfusiAkut" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice5'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl style="width:60%;" class="control">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl> 
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Resiko Cedera'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in ResikoCedera" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in ResikoCedera" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="control">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxevm'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Defisit Nutrisi'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in DefisitNutrisi" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in DefisitNutrisi" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice5'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl style="width:60%;" class="control">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl> 
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Hipertermia'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Hipertermia" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Hipertermia" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtext'">
                                    <VControl class="column is-5" style="margin-right:-20%;">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-7">
                                      <VField>
                                        <VTextarea rows="2" v-model="items[item3.model2]"></VTextarea>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtextbox'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VInput type="text" class="input" v-model="items[item3.model2]" />
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice2'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl>
                                              <VButton static>/</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxhrtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>HR :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>x/menit</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl>
                                            <VButton static>TD :</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>mmHg</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                              <VButton static>{{ item3.namasatuan }}</VButton>
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice4'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice5'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl style="width:60%;" class="control">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl> 
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtd'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxemv'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model2]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model3]" />
                                          </VControl>
                                          <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                          </VControl>
                                          <VControl>
                                              <VInput type="text" class="input" v-model="items[item3.model4]" />
                                          </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                  <VField v-if="item3.type == 'textbold'">
                                    <p>
                                      <b>
                                        {{ item3.labelDetail }}
                                      </b>
                                    </p>
                                  </VField>
                                </template>
                                <template v-if="item2.keteranganTambahan == 'Hipertermia berhubungan dengan'">
                                  <h1>DS:</h1>
                                  <VField>
                                    <VControl>
                                      <VInput type="text" class="heightinput input" v-model="items.ds1" />
                                    </VControl>
                                  </VField>
    
                                  <VField>
                                    <VControl>
                                      <VInput type="text" class="heightinput input" v-model="items.ds2" />
                                    </VControl>
                                  </VField>
    
                                  <VField>
                                    <VControl>
                                      <VInput type="text" class="heightinput input" v-model="items.ds3" />
                                    </VControl>
                                  </VField>
                                  <h1>DO:</h1>
                                  <div class="columns is-multiline">
                                    <VField class="column is-12">
                                      <VControl>
                                        <VCheckbox style="font-weight: 900; " circle v-model="items.doKulitMerah" label="1. Suhu diatas normal" color="primary"
                                          true-value="Suhu diatas normal" />
                                      </VControl>
                                    </VField>
                                    <VField addons class="ml-4" style="margin-top:-20px;">
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.suhuDo" />
                                      </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>°C</VButton>
                                    </VControl>
                                  </VField>
                                  </div>
                                  <VField>
                                    <VControl>
                                      <VCheckbox circle v-model="items.doKulitMerah" label="2. Kulit Merah" color="primary"
                                        true-value="Kulit Merah" />
                                    </VControl>
                                  </VField>
    
                                  <VField>
                                    <VControl>
                                      <VCheckbox circle v-model="items.doKejang" label="3. Kejang" color="primary"
                                        true-value="Kejang" />
                                    </VControl>
                                  </VField>
    
                                  <VField>
                                    <VControl>
                                      <VCheckbox circle v-model="items.doTakikardia" label="4. Takikardia" color="primary"
                                        true-value="Takikardia" />
                                    </VControl>
                                  </VField>

                                  <VField>
                                    <VControl>
                                      <VCheckbox circle v-model="items.doTakipneu" label="5. Takipneu" color="primary"
                                        true-value="Takipneu" />
                                    </VControl>
                                  </VField>
    
                                  <VField>
                                    <VControl>
                                      <VCheckbox circle v-model="items.doKulitTerasaHangat" label="6. Kulit Terasa Hangat"
                                        color="primary" true-value="Kulit Terasa Hangat" />
                                    </VControl>
                                  </VField>
                                </template>
                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Ansietas'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Ansietas" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Ansietas" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'checkboxbold'">
                                    <VControl>
                                      <VCheckbox style="font-weight: bold;" class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>

                                  <VField class="columns is-multiline" v-if="item3.type == 'checkboxtbchoice3'">
                                    <VControl class="column is-12">
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -20px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model2]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama2 }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model3]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan2 }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                    <VControl class="column is-12" style="margin-top: -10px;">
                                      <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ item3.nama3 }}</VButton>
                                        </VControl>
                                        <VControl>
                                          <VInput type="text" class="input" v-model="items[item3.model4]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>{{ item3.namasatuan3 }}</VButton>
                                        </VControl>
                                      </VField>
                                    </VControl>
                                  </VField>

                                  <template v-if="item3.labelDetail == '12. Kurang terpapar informasi'">
                                    <h1>DS:</h1>
                                    <div class="is-12" v-for="(detailDO, indexDO) in AnsietasDS" :key="indexDO">
                                      <VField v-if="detailDO.type == 'checkboxbold'">
                                        <VControl>
                                          <VCheckbox style="font-weight: bold;" class="fontcheckbox" v-model="items[detailDO.model]"
                                            :label="detailDO.label" :true-value="detailDO.value" color="primary" circle />
                                        </VControl>
                                      </VField>
                                      <VField v-if="detailDO.type == 'checkbox'">
                                        <VControl>
                                          <VCheckbox class="fontcheckbox" v-model="items[detailDO.model]"
                                            :label="detailDO.label" :true-value="detailDO.value" color="primary"
                                            circle />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <h1>DO:</h1>
                                    <div class="is-12" v-for="(detailDO, indexDO) in AnsietasDO" :key="indexDO">
                                      <VField v-if="detailDO.type == 'checkboxbold'">
                                        <VControl>
                                          <VCheckbox style="font-weight: bold;" class="fontcheckbox" v-model="items[detailDO.model]"
                                            :label="detailDO.label" :true-value="detailDO.value" color="primary" circle />
                                        </VControl>
                                      </VField>
                                      <VField v-if="detailDO.type == 'checkbox'">
                                        <VControl>
                                          <VCheckbox class="fontcheckbox" v-model="items[detailDO.model]"
                                            :label="detailDO.label" :true-value="detailDO.value" color="primary"
                                            circle />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </template>
                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Berduka'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Berduka" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Berduka" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <p>{{ item3.section }}</p>
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>

                                  <template
                                    v-if="item3.labelDetail == '4. Antisipasi kehilangan (objek, pekerjaan, status, bagian tubuh, hubungan sosial)'">
                                    <h1>DS:</h1>
                                    <div class="is-12" v-for="(detailDO, indexDO) in AnsietasDSBerduka" :key="indexDO">
                                      <VField>
                                        <VControl>
                                          <VCheckbox class="fontcheckbox" v-model="items[detailDO.model]"
                                            :label="detailDO.label" :true-value="detailDO.value" color="primary"
                                            circle />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <h1>DO:</h1>
                                    <div class="is-12" v-for="(detailDO, indexDO) in AnsietasDOBerduka" :key="indexDO">
                                      <VField>
                                        <VControl>
                                          <VCheckbox class="fontcheckbox" v-model="items[detailDO.model]"
                                            :label="detailDO.label" :true-value="detailDO.value" color="primary"
                                            circle />
                                        </VControl>
                                      </VField>
                                    </div>
                                  </template>

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Termoregulasi Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Termoregulasi" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Termoregulasi" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>

                                  <template
                                    v-if="item3.labelDetail == '9. Efek agen farmakologis (misalnya sedasi)'">
                                    DS:
                                    <VField>
                                      <VControl>
                                        <VInput type="text" class="heightinput input" v-model="items.DSTermoregulasi" />
                                      </VControl>
                                    </VField>
                                    DO:
                                    <template v-for="(item4, index4) in TermoregulasiDO" :key="index4">
                                      <VField v-if="item4.type == 'checkboxbold'">
                                        <VControl>
                                          <VCheckbox style="font-weight: bold;" class="fontcheckbox" v-model="items[item4.model]"
                                            :label="item4.label" :true-value="item4.value" color="primary" circle />
                                        </VControl>
                                      </VField>
                                      <VField v-if="item4.type == 'checkbox'">
                                        <VControl>
                                          <VCheckbox class="fontcheckbox" v-model="items[item4.model]"
                                            :label="item4.label" :true-value="item4.value" color="primary"
                                            circle />
                                        </VControl>
                                      </VField>
                                    </template>
                                  </template>

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Perfusi Gastrointestinal Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GastroinTestinal" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GastroinTestinal" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>

                                  <template
                                    v-if="item3.labelDetail == '9. Gunakan pendekatan yang tenang dan meyakinkan'">
                                    DS:
                                    <VField>
                                      <VControl>
                                        <VInput type="text" class="heightinput input" v-model="items.DSTermoregulasi" />
                                      </VControl>
                                    </VField>
                                    DO:
                                    <template v-for="(item4, index4) in TermoregulasiDO" :key="index4">
                                      <VField>
                                        <VControl>
                                          <VCheckbox class="fontcheckbox" v-model="items[item4.model]"
                                            :label="item4.label" :true-value="item4.value" color="primary" circle />
                                        </VControl>
                                      </VField>
                                    </template>
                                  </template>

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Resiko Pendarahan'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in ResikoPendarahan" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in ResikoPendarahan" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>

                                  <template v-if="item3.labelDetail == '12. Tekanan darah normal'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.tekananDarahResikoPendarahan" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>mmHg</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '13. Frekuensi nadi membaik'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.frekuensiNadiResikoPendarahan" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '15. Suhu tubuh normal'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.suhuTubuhResikoPendarahan" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>°C</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Risiko Penurunan Curah Jantung'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in ResikoPenurunanCuraahJantung" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in ResikoPenurunanCuraahJantung" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>

                                  <template v-if="item3.labelDetail == '12. Tekanan darah normal'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.tekananDarahResikoPendarahan" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>mmHg</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '13. Frekuensi nadi membail'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.frekuensiNadiResikoPendarahan" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '15. Suhu tubuh normal'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.suhuTubuhResikoPendarahan" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>°C</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Gangguan Tumbuh Kembang'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in GangguanTumbuhKembang" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in GangguanTumbuhKembang" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>
                                  <VField v-if="item3.type == 'checkboxbold'">
                                    <VControl>
                                      <VCheckbox style="font-weight:bold;" class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>

                                  <template v-if="item3.labelDetail == '6. Defisiensi stimulus'">
                                    DS:
                                    <VField>
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.dsGangguanTumbuhKembang1" />
                                      </VControl>
                                    </VField>
                                    <VField>
                                      <VControl>
                                        <VInput type="text" class="heightinput input"
                                          v-model="items.dsGangguanTumbuhKembang2" />
                                      </VControl>
                                    </VField>
                                    DO:
                                    <template v-for="(item4, index4) in DOgangguanTumbuhKembang" :key="index4">
                                      <VField v-if="item4.type == 'checkboxbold'">
                                        <VControl>
                                          <VCheckbox style="font-weight: bold;" class="fontcheckbox" v-model="items[item4.model]"
                                            :label="item4.label" :true-value="item4.value" color="primary" circle />
                                        </VControl>
                                      </VField>
                                      <VField v-if="item4.type == 'checkbox'">
                                        <VControl>
                                          <VCheckbox class="fontcheckbox" v-model="items[item4.model]"
                                            :label="item4.label" :true-value="item4.value" color="primary"
                                            circle />
                                        </VControl>
                                      </VField>
                                    </template>

                                  </template>

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Risiko Syok'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in ResikoSyok" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in ResikoSyok" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                  <template v-if="item3.labelDetail == '8. TD membaik'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="number" class="heightinput input" v-model="items.tdMembaikText" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>N</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '10. Nadi membaik'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="number" class="heightinput input" v-model="items.nadiMembaikText" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>N</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '11. Pernafasan'">
                                    <VField addons>
                                      <VControl>
                                        <VInput type="number" class="heightinput input" v-model="items.pernafasanText" />
                                      </VControl>
                                      <VControl class="field-addon-body">
                                        <VButton static>RR</VButton>
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '3. Tingkat kesadaran meningkat GCS'">
                                    <VField addons>
                                      <VControl class="field-addon-body">
                                        <VButton static>E</VButton>
                                      </VControl>
                                      <VControl expanded>
                                        <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E"
                                          label="label" :options="d_gcse" :searchable="true" track-by="label"
                                          mode="single" autocomplete="off">
                                        </Multiselect>
                                      </VControl>
                                    </VField>
                                    <VField addons>
                                      <VControl class="field-addon-body">
                                        <VButton static>V</VButton>
                                      </VControl>
                                      <VControl expanded>
                                        <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V"
                                          label="label" :options="d_gcsv" :searchable="true" track-by="label"
                                          mode="single" autocomplete="off">
                                        </Multiselect>
                                      </VControl>
                                    </VField>
                                    <VField addons>
                                      <VControl class="field-addon-body">
                                        <VButton static>M</VButton>
                                      </VControl>
                                      <VControl expanded>
                                        <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M"
                                          label="label" :options="d_gcsm" :searchable="true" track-by="label"
                                          mode="single" autocomplete="off">
                                        </Multiselect>
                                      </VControl>
                                    </VField>
                                  </template>

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12" v-if="items.rencanaKeperawatanRawatInap === 'Diare'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in Diare" :key="index" style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in Diare" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                  <template v-if="item3.labelDetail == '7. Nyeri abdomen menurun'">
                                    <VField>
                                      <VControl>
                                        <VInput type="text" placeholder="Skala nyeri" class="heightinput input" v-model="items.skalaNyeri" />
                                      </VControl>
                                    </VField>
                                  </template>

                                  <!-- <template v-if="item3.labelDetail == '10. Bakteri pada air'">
                                    DS:
                                    <VField>
                                      <VControl>
                                        <VCheckbox style="font-weight:bold;" class="fontcheckbox" v-model="items.dosisBakteriUrgent"
                                          label="Urgency" true-value="Urgency" color="primary" circle />
                                      </VControl>
                                    </VField>

                                    <VField>
                                      <VControl>
                                        <VCheckbox style="font-weight:bold;" class="fontcheckbox" v-model="items.dosisBakteriNyeri"
                                          label="Nyeri/kram abdomen" true-value="Nyeri/kram abdomen" color="primary"
                                          circle />
                                      </VControl>
                                    </VField>
                                    DO:
                                    <VField>
                                      <VControl>
                                        <VCheckbox style="font-weight:bold;" class="fontcheckbox" v-model="items.DODefekasi"
                                          label="Defekasi lebih dari tiga kali dalam 24 jam"
                                          true-value="Defekasi lebih dari tiga kali dalam 24 jam" color="primary"
                                          circle />
                                      </VControl>
                                    </VField>
                                    <VField>
                                      <VControl>
                                        <VCheckbox style="font-weight:bold;" class="fontcheckbox" v-model="items.DOFeses"
                                          label="Feses lembek atau cair" true-value="Feses lembek atau cair"
                                          color="primary" circle />
                                      </VControl>
                                    </VField>
                                    <VField>
                                      <VControl>
                                        <VCheckbox style="font-weight:bold;" class="fontcheckbox" v-model="items.DOFrekuensi"
                                          label="Frekuensi peristaltic meningkat"
                                          true-value="Frekuensi peristaltic meningkat" color="primary" circle />
                                      </VControl>
                                    </VField>
                                    <VField>
                                      <VControl>
                                        <VCheckbox style="font-weight:bold;" class="fontcheckbox" v-model="items.DOBisingUsusHiperaktif"
                                          label="Bising usus hiperaktif" true-value="Bising usus hiperaktif"
                                          color="primary" circle />
                                      </VControl>
                                    </VField>
                                  </template> -->

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                                <VControl class="prime-auto">
                                  <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                </VControl>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="column is-12"
                      v-if="items.rencanaKeperawatanRawatInap === 'Risiko Perfusi Serebral Tidak Efektif'">
                      <table class="">
                        <thead>
                          <tr>
                            <th class="th-pri" style="width: 20%;">Tanggal</th>
                            <th class="th-pri" v-for="(item, index) in ResikoPerfusiSerebral" :key="index"
                              style="width: 20%;">
                              <p>{{ item.labelDiagnosaKeperawatan }}</p>
                            </th>
                            <th class="th-pri" style="width: 20%;">Nama Terang / Paraf</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="td-pri">
                              <VField>
                                <VDatePicker v-model="items.tanggalRencanaKeperawatan" mode="date" trim-weeks
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
                            </td>
                            <template v-for="(item2, index2) in ResikoPerfusiSerebral" :key="index2">
                              <td class="td-pri">
                                <p>{{ item2.keteranganTambahan }}</p>
                                <template class="p-1" style="display: flex;"
                                  v-if="item2.keteranganTambahan == 'Setelah dilakukan tindakan keperawatan selama'">
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                      <VButton static>x</VButton>
                                    </VControl>
                                  </VField>
                                  <VField addons>
                                    <VControl>
                                      <VInput type="number" class="heightinput input"
                                        v-model="items.banyakTindakanKeperawatanWaktu" />
                                    </VControl>
                                    <VControl style="width:60%; height:100%;">
                                      <Multiselect mode="single" v-model="items.mntjam" :options="d_mntjam"
                                        placeholder="mnt/jam" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                                    </VControl>
                                  </VField>
                                  <p>{{ item2.capt }}</p>
                                </template>

                                <template v-for="(item3, index3) in item2.detailDiagnosaKeperawatan" :key="index3">
                                  <p>{{ item3.section }}</p>
                                  <VField v-if="item3.type == 'checkbox'">
                                    <VControl>
                                      <VCheckbox class="fontcheckbox" v-model="items[item3.model]"
                                        :label="item3.labelDetail" :true-value="item3.value" color="primary" circle />
                                    </VControl>
                                  </VField>

                                  <VField v-if="item3.type == 'text'">
                                    <p>
                                      {{ item3.labelDetail }}
                                    </p>
                                  </VField>
                                  <template v-if="item3.labelDetail == '1. Tingkat kesadaran meningkat GCS'">
                                    <VField addons>
                                      <VControl class="field-addon-body">
                                        <VButton static>E</VButton>
                                      </VControl>
                                      <VControl expanded>
                                        <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E"
                                          label="label" :options="d_gcse" :searchable="true" track-by="label"
                                          mode="single" autocomplete="off">
                                        </Multiselect>
                                      </VControl>
                                    </VField>
                                    <VField addons>
                                      <VControl class="field-addon-body">
                                        <VButton static>V</VButton>
                                      </VControl>
                                      <VControl expanded>
                                        <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V"
                                          label="label" :options="d_gcsv" :searchable="true" track-by="label"
                                          mode="single" autocomplete="off">
                                        </Multiselect>
                                      </VControl>
                                    </VField>
                                    <VField addons>
                                      <VControl class="field-addon-body">
                                        <VButton static>M</VButton>
                                      </VControl>
                                      <VControl expanded>
                                        <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M"
                                          label="label" :options="d_gcsm" :searchable="true" track-by="label"
                                          mode="single" autocomplete="off">
                                        </Multiselect>
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '4. Nyeri kepala'">
                                    <VField>
                                      <VControl>
                                        <VInput v-model="input.nyeriKepala" type="text" placeholder="Nyeri Kepala" />
                                      </VControl>
                                    </VField>
                                  </template>

                                  <template v-if="item3.labelDetail == '6. Nilai rata-rata TD membaik'">
                                    <VField>
                                      <VControl>
                                        <VInput v-model="input.nilaiTD" type="text"
                                          placeholder="Rata-rata TD Membaik" />
                                      </VControl>
                                    </VField>
                                  </template>

                                </template>

                              </td>
                            </template>
                            <td class="td-pri">
                              <VControl class="prime-auto">
                                <AutoComplete v-model="items.CBBidan" :suggestions="d_pegawai"
                                  @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                              <div class="mt-4">
                                <span>Ruangan :</span>
                               <VControl class="prime-auto">
                                <AutoComplete v-model="items.RuanganSection" :suggestions="d_Ruangan"
                                  @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                              </VControl>
                            </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
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
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount, nextTick } from "vue";
import { useRoute, useRouter, onBeforeRouteLeave } from "vue-router";
import { useHead } from "@vueuse/head";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import ButtonEmr from "../page-emr-plugins/button-emr.vue";
import * as H from "/@src/utils/appHelper";
import AutoComplete from "primevue/autocomplete";
import Fieldset from "primevue/fieldset";
import * as EMR from "../page-emr-plugins/asesmen-awal-keper-rj";
import * as EMR2 from "../page-emr-plugins/rencana-keperawatan-ranap";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";
import BerkasRanap from './berkas-rencana-keper-ranap.vue';

useHead({
  title: "Rencana Keperawatan Rawat Inap " + import.meta.env.VITE_PROJECT,
});
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);
let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
let norec_emr = useRoute().query.norec_emr as string;
let JenisKelamin = ref(EMR.JenisKelamin());
let RencanaKeperawatanRawatInap: any = ref(EMR2.RencanaKeperawatanRawatInap())
let ResikoInfeksi: any = ref(EMR2.ResikoInfeksi())
let ResikoKonfusiAkut: any = ref(EMR2.ResikoKonfusiAkut())
let ResikoCedera: any = ref(EMR2.ResikoCedera())
let IkterikNeonatus: any = ref(EMR2.IkterikNeonatus())
let ResikoGangguanIntegritasKulitJaringan: any = ref(EMR2.ResikoGangguanIntegritasKulitJaringan())
let KonfusiAkut: any = ref(EMR2.KonfusiAkut())
let DefisitNutrisi: any = ref(EMR2.DefisitNutrisi())
let Hipertermia: any = ref(EMR2.Hipertermia())
let Ansietas: any = ref(EMR2.Ansietas())
let AnsietasDS: any = ref(EMR2.AnsietasDS())
let AnsietasDO: any = ref(EMR2.AnsietasDO())
let Berduka: any = ref(EMR2.Berduka())
let AnsietasDSBerduka: any = ref(EMR2.AnsietasDSBerduka())
let AnsietasDOBerduka: any = ref(EMR2.AnsietasDOBerduka())
let Termoregulasi: any = ref(EMR2.Termoregulasi())
let TermoregulasiDO: any = ref(EMR2.TermoregulasiDO())
let GastroinTestinal: any = ref(EMR2.GastroinTestinal())
let ResikoPendarahan: any = ref(EMR2.ResikoPendarahan())
let PenurunanCurahJantung: any = ref(EMR2.PenurunanCurahJantung())
let PerfusiPeriferTidakEfektif: any = ref(EMR2.PerfusiPeriferTidakEfektif())
let ResikoPenurunanCuraahJantung: any = ref(EMR2.ResikoPenurunanCuraahJantung())
let GangguanTumbuhKembang: any = ref(EMR2.GangguanTumbuhKembang())
let DOgangguanTumbuhKembang: any = ref(EMR2.DOgangguanTumbuhKembang())
let ResikoSyok: any = ref(EMR2.ResikoSyok())
let Diare: any = ref(EMR2.Diare())
let NyeriMelahirkan: any = ref(EMR2.NyeriMelahirkan())
let KesiapanPersalinan: any = ref(EMR2.KesiapanPersalinan())
let RisikoKetidakseimbanganElektrolit: any = ref(EMR2.RisikoKetidakseimbanganElektrolit())
let RisikoKetidakseimbanganCairan: any = ref(EMR2.RisikoKetidakseimbanganCairan())
let KesiapanPeningkatanKeseimbanganCairan: any = ref(EMR2.KesiapanPeningkatanKeseimbanganCairan())
let RisikoIkterikNeonatus: any = ref(EMR2.RisikoIkterikNeonatus())
let RisikoAlergi: any = ref(EMR2.RisikoAlergi())
let KesiapanPeningkatanManajemenKesehatan: any = ref(EMR2.KesiapanPeningkatanManajemenKesehatan())
let KesiapanPeningkatanPengetahuan: any = ref(EMR2.KesiapanPeningkatanPengetahuan())
let Keletihan: any = ref(EMR2.Keletihan())
let GangguanPolaTidur: any = ref(EMR2.GangguanPolaTidur())
let GangguanMemori: any = ref(EMR2.GangguanMemori())
let GangguanPersepsiSensori: any = ref(EMR2.GangguanPersepsiSensori())
let RisikoBunuhDiri: any = ref(EMR2.RisikoBunuhDiri())
let RisikoPerlambatanPemulihanPascabedah: any = ref(EMR2.RisikoPerlambatanPemulihanPascabedah())
let RisikoLukaTekan: any = ref(EMR2.RisikoLukaTekan())
let RisikoJatuh: any = ref(EMR2.RisikoJatuh())
let Konstipasi: any = ref(EMR2.Konstipasi())
let MenyusuiTidakEfektif: any = ref(EMR2.MenyusuiTidakEfektif())
let MenyusuiEfektif: any = ref(EMR2.MenyusuiEfektif())
let RisikoPerdarahan: any = ref(EMR2.RisikoPerdarahan())
let PolaNapasTidakEfektif: any = ref(EMR2.PolaNapasTidakEfektif())
let RisikoPerfusiPeriferTidakEfektif: any = ref(EMR2.RisikoPerfusiPeriferTidakEfektif())
let PenurunanKapasitasAdaptifIntrakranial: any = ref(EMR2.PenurunanKapasitasAdaptifIntrakranial())
let KetidaknyamananPascaPersalinan: any = ref(EMR2.KetidaknyamananPascaPersalinan())
let NyeriKronis: any = ref(EMR2.NyeriKronis())
let NyeriAkut: any = ref(EMR2.NyeriAkut())
let Nausea: any = ref(EMR2.Nausea())
let KetidakstabilanKadarGlukosaDarah: any = ref(EMR2.KetidakstabilanKadarGlukosaDarah())
let Keputusasaan: any = ref(EMR2.Keputusasaan())
let IntoleransiAktivitas: any = ref(EMR2.IntoleransiAktivitas())
let InkontinensiaFekal: any = ref(EMR2.InkontinensiaFekal())
let Hipovolemia: any = ref(EMR2.Hipovolemia())
let Hipervolemia: any = ref(EMR2.Hipervolemia())
let Hipotermia: any = ref(EMR2.Hipotermia())
let GangguanVentilasiSpontan: any = ref(EMR2.GangguanVentilasiSpontan())
let GangguanSirkulasiSpontan: any = ref(EMR2.GangguanSirkulasiSpontan())
let GangguanPertukaranGas: any = ref(EMR2.GangguanPertukaranGas())
let GangguanMobilitasFisik: any = ref(EMR2.GangguanMobilitasFisik())
let GangguanMenelanBerhubungan: any = ref(EMR2.GangguanMenelanBerhubungan())
let GangguanIntegritasKulitJaringan: any = ref(EMR2.GangguanIntegritasKulitJaringan())
let GangguanEliminasiUrine: any = ref(EMR2.GangguanEliminasiUrine())
let RisikoPerfusiRenalTidakEfektif: any = ref(EMR2.RisikoPerfusiRenalTidakEfektif())
let RisikoPerfusiMiokardTidakEfektif: any = ref(EMR2.RisikoPerfusiMiokardTidakEfektif())
let BersihanJalanNafasTidakEfektif: any = ref(EMR2.BersihanJalanNafasTidakEfektif())
let ResikoPerfusiSerebral: any = ref(EMR2.ResikoPerfusiSerebral())
const NAMA_RUANGAN: any = ref()

const props = withDefaults(
  defineProps<{
    pasien?: any;
    registrasi?: any;
    FORM_NAME?: string;
    FORM_URL?: string;
    COLLECTION?: string;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: "",
    FORM_URL: "",
    COLLECTION: "",
  }
);

const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])

const route = useRoute();
const pasien: any = ref({});
const d_pegawai: any = ref([]);
const d_Dokter: any = ref([]);
const d_mntjam: any = ref([{ value: 'mnt', label: 'mnt' }, { value: 'jam', label: 'jam' }])
const loadData: any = ref(true);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date(),
    jam: new Date(),
  },
  airway: [],
  disability: [],
});

const COLLECTION: any = ref("RencanaKeperawatanRawatInap"); //table mongodb
const modalInput: any = ref(false)
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggalJam: new Date(),
  details: [{
    no: 1,
  }]
});
const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const isLoading = ref(false);
const isAktive = ref();

const dataTTD: any = ref([]);
const d_Ruangan: any = ref([]);

NAMA_RUANGAN.value = route.query.nama_ruangan as string ?? props.registrasi.namaruangan;
console.log("ini aneh", NAMA_RUANGAN.value)

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  );
  d_Ruangan.value = response;
};

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
    nama: props.pasien.namapasien,
    tanggal: new Date(),
    tanggalJam: new Date(),
  });
};
const removeItem = (index: any) => {
  input.value.details.splice(index, 1);
};

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&namaruangan=${NAMA_RUANGAN.value}`
    )
    .then((response: any) => {
      if (response.length) {
        input.value = response[0]; //set ke inputan
        if (NOREC_EMRPASIEN.value == "") {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk;
        }
        dataTTD.value = response[0];
      }
    });
  H.tandaTangan().set("TTDAhliGizi", dataTTD.value.TTDAhliGizi);
};
const simpan = () => {
  let ID = input.value.id ? input.value.id : "";

  let object: any = {};

  object = input.value;
  object.nocm = pasien.value.nocm;

  if (route.query.nama_ruangan) {
      object.registrasi = H.setObjectRegistrasi(input.value.registrasi)
      object.pasien = H.setObjectPasien(input.value.pasien)
  } else {
      object.registrasi = H.setObjectRegistrasi(props.registrasi)
      object.pasien = H.setObjectPasien(pasien.value)
  }
  object["TTDAhliGizi"] = H.tandaTangan().get("TTDAhliGizi");

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: "rencana-keperawatan-ranap",
    name_form: props.FORM_NAME,
    jenis_emr: "asesmen_medis",
    data: object,
  };
  console.log(json);

  isLoading.value = true;
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false;
      // NOREC_EMRPASIEN.value = response.norec_emr
    })
    .catch((e: any) => {
      isLoading.value = false;
    });

  // console.log(resultValue)
};

const kembaliKeun = () => {
  window.history.back();
};
const fetchPasien = () => {
  pasien.value = props.pasien;
  pasien.value.registrasi = props.registrasi;
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : "";
  console.log(norec_emr);
};

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiPerawat&limit=10`
  ).then((response) => {
    d_pegawai.value = response
  })
}
const getDataExist = async () => {
  await useApi()
    .get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`)
    .then(response => {
      if (response != null || response != undefined) {
        input.value.beratbadanObgyn = response.beratBadan;
        input.value.tinggibadanObgyn = response.tinggiBadan;
        input.value.IMT = response.IMT;
        input.value.lingkarPerut = response.lingkarPerut;
        input.value.nadiObgyn = response.nadi;
        input.value.celciusObgyn = response.suhu;
        input.value.tekananDarahObgyn = response.tekananDarah;
        input.value.nafasObgyn = response.pernapasan;
        input.value.sao2Obgyn = response.SPO2;
      }
    });
};

const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {
  }
};

const print = async () => {
  H.printBlade(
    `emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  );
};

onBeforeMount(async () => {
  try {
    await loadRiwayat();
    if (!route.query.nama_ruangan) {
      let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`);
      if (cache) input.value = cache;
    }
    loadData.value = false;
  } catch (error) {
    console.error("Error mount cache TAB EMR:", error);
  }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name;
        if (!route.query.nama_ruangan) {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value);
        }
        next(); // Proceed without changing the URL
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
        next();
    }
});


const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien;
  input.value.norm = props.pasien.nocm;
  input.value.tanggalLahirPasien = props.pasien.tgllahir;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
};

setAutoFill();
getDataExist();
fetchPasien();
</script>

<style lang="scss">
.table-rpo {
  width: 100%;
  border: 1px solid;
}

.fontcheckbox {
    font-weight: 400;
    color: #000; // Set a fixed color to prevent it from turning gray

    input[type="checkbox"] {
        margin-right: 0.5rem;
    }

    &:hover {
      font-weight: 500;
        color: #000; // Keep it bold and black even on hover
    }
}
.th-rpo,
.td-rpo {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.th-rpo {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
