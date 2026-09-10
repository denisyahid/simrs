<template>
  <VCard>
    <div class="form-layout is-stacked-2" v-if="!props.hideButtons">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" @simpan="simpan"
                @kembaliKeun="kembaliKeun" :isLoading="isLoading"></ButtonEmr>
              <VButton type="button" rounded outlined color="dark" raised icon="feather:save" :loading="isLoading"
                @click="tutupJadwal()"> Tutup Jadwal Monitoring
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="previewTutupJadwalClick()"> Preview
              </VButton>
            </div>
          </div>
        </div>

      </div>
    </div>
    <Dialog v-model:visible="previewTutupJadwal" header="Preview" :style="{ width: '100rem' }">
      <PreviewMonitoringIcu :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :listTutupJadwal="listTutupJadwal" @load-riwayat="loadRiwayatTutupJadwal"></PreviewMonitoringIcu>
    </Dialog>

    <!-- <div class="column is-12 pt-0">
      <span style="font-weight: bold; color: gray;">Identitas Pasien</span>
    </div>
    <hr class="p-0">

    <div class="column pt-0">
      <div class="columns is-multiline">
        <div class="column is-2">
          <span class="label-icu">No RM</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.norm" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <span class="label-icu">Nama Pasien</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.namaPasien" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <span class="label-icu">Tanggal</span>
          <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField class="pb-0 pt-3">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
</VDatePicker>
</div>
<div class="column">
  <span class="label-icu">Petugas</span>
  <VField class="pt-3">
    <VControl class="prime-auto">
      <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
        :field="'label'" />
    </VControl>
  </VField>
</div>
</div>
</div> -->

    <span style="font-weight: bold; color: darkgray;">I. Tanda Vital</span>
    <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0 pt-0">
    <!-- <div class="column p-0">
      <div class="columns is-multiline column">
        <VCard>
          <div class="columns is-multiline" v-for="(items, index) in input.listVitalSignDetails" :key="index">
            <div class="column is-12 is-flex">
              <VButton color="primary" raised icon="fas fa-plus" class="mr-3" @click="addNewItemVitalSign()"
                :isLoading="isLoading"> Tambah </VButton>
              <VButton color="danger" v-if="items.no > 1" raised icon="fas fa-trash" class="mr-3"
                @click="removeItemVitalSign(index)" :isLoading="isLoading"> Hapus </VButton>
            </div>
            <div class="column is-3">
              <VField label="Tanggal">
                <VDatePicker v-model="items.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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

            <div class="column is-3">
              <VField label="Tekanan Darah"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="items.tekananDarah"
                    :tabindex="3" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mmHG</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Suhu"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Suhu" v-model="items.suhu" :tabindex="4" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>°C </VButton>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField label="RR"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="RR" v-model="items.pernapasan" :tabindex="6" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/menit</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="SpO2"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="SaO2" v-model="items.SPO2" :tabindex="7" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>%</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="MAP"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="MAP" v-model="items.MAP" :tabindex="8" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Cm</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="Nadi"></VField>
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Nadi" v-model="items.nadi" :tabindex="8" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/mnt</VButton>
                </VControl>
              </VField>
            </div>
          </div>
        </VCard>
      </div>
    </div> -->

    <div>
      <VButton color="primary" raised icon="fas fa-plus" class="mr-3" @click="inputVitalSign = true"
        :isLoading="isLoading">
        Input Vital Sign </VButton>
    </div>
    <Dialog v-model:visible="inputVitalSign" header="Input Vital Sign" :style="{ width: '70rem' }">
      <div class="columns is-multiline" v-for="(items, index) in input.listVitalSignDetails" :key="index">
        <div class="column is-12 is-flex">
          <VButton color="primary" raised icon="fas fa-plus" class="mr-3" @click="addNewItemVitalSign()"
            :isLoading="isLoading"> Tambah </VButton>
          <VButton color="danger" v-if="items.no > 1" raised icon="fas fa-trash" class="mr-3"
            @click="removeItemVitalSign(index)" :isLoading="isLoading"> Hapus </VButton>
        </div>
        <div class="column is-3">
          <VField label="Tanggal">
            <VDatePicker v-model="items.tanggal" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
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

        <div class="column is-3">
          <VField label="Tekanan Darah"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="items.tekananDarah"
                :tabindex="3" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>mmHG</VButton>
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Suhu"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="text" class="input" placeholder="Suhu" v-model="items.suhu" :tabindex="4" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>°C </VButton>
            </VControl>
          </VField>
        </div>

        <div class="column is-3">
          <VField label="RR"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="text" class="input" placeholder="RR" v-model="items.pernapasan" :tabindex="6" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>x/menit</VButton>
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="SpO2"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="text" class="input" placeholder="SaO2" v-model="items.SPO2" :tabindex="7" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>%</VButton>
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="MAP"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="text" class="input" placeholder="MAP" v-model="items.MAP" :tabindex="8" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>Cm</VButton>
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Nadi"></VField>
          <VField addons>
            <VControl expanded>
              <VInput type="text" class="input" placeholder="Nadi" v-model="items.nadi" :tabindex="8" />
            </VControl>
            <VControl class="field-addon-body">
              <VButton static>x/mnt</VButton>
            </VControl>
          </VField>
        </div>
      </div>
    </Dialog>
    <div class="column is-12">
      <div class="columns is-multiline mt-5" v-if="isLoading">
        <VPlaceloadText :lines="1" class="p-2" />
        <div class="column is-12" v-for="key in 2" :key="key">
          <VPlaceloadWrap>
            <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
            <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
            <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
            <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
          </VPlaceloadWrap>
        </div>
      </div>
      <div v-else>
        <div v-for="(items, index) in listVital" :key="index" style="height: 400px; overflow-y: auto;" class="mt-3">
          <div class="columns is-multiline column is-12">
            <div class="column is-6 columns is-multiline mr-3"
              v-for="(itemsVitalSign, indexVitalSign) in items.listVitalSignDetails" :key="indexVitalSign">
              <UIWidget class="calendar-widget">
                <template #body>
                  <div class="calendar-widget-inner">
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ itemsVitalSign.suhu ? itemsVitalSign.suhu : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            C</a></span>
                        <span> Suhu </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ itemsVitalSign.pernapasan ? itemsVitalSign.pernapasan : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            x/mnt</a></span>
                        <span> Pernafasan </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span style=" white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">{{
                          itemsVitalSign.tekananDarah ? itemsVitalSign.tekananDarah : '-' }}<a
                            style="font-size: 0.6rem; color: var(--dark-text);">
                            mmHg</a></span>
                        <span> Tekanan Darah </span>
                      </div>
                    </div>
                  </div>
                  <div class="calendar-widget-inner">
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ itemsVitalSign.SPO2 ? itemsVitalSign.SPO2 : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            %</a></span>
                        <span> SpO2 </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ itemsVitalSign.MAP ? itemsVitalSign.MAP : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            Cm</a></span>
                        <span> Map </span>
                      </div>
                    </div>
                    <div class="calendar-square" style="padding:5px">
                      <div class="date">
                        <span>{{ itemsVitalSign.nadi ? itemsVitalSign.nadi : '-' }}<a
                            style="font-size: 0.8rem; color: var(--dark-text);">
                            x/mnt</a></span>
                        <span> Nadi </span>
                      </div>
                    </div>
                  </div>
                  <VTag rounded :label="'IMT'" outlined color="orange" class="mr-2" />
                  <VTag rounded :label="(itemsVitalSign.IMT ? itemsVitalSign.IMT : '-')" color="orange" class="mr-2" />
                  <VTag rounded :label="H.formatDateIndoSimple(itemsVitalSign.tanggal)" outlined
                    class="is-pulled-right" />
                  <!-- <small class="is-tanggal">28 minutes ago</small> -->
                </template>
              </UIWidget>
            </div>
          </div>
          <div class="column is-12">
            <VCard style="border-radius: 16px;">
              <Chart type="line" :data="chartData" :options="chartOptions" :height="300" class="h-30rem" />
            </VCard>
          </div>
        </div>
      </div>
    </div>

    <span style="font-weight: bold; color: darkgray;">II. Sistem Saraf</span>
    <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0 pt-0">
    <div class="column columns is-multiline">
      <div class="column is-4 pl-0 pb-0" style="text-align: center;">
        <VButtons style="justify-content:space-around">
          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem2()" color="info"
            v-tooltip.bubble="'Tambah '">
          </VIconButton>
          <VIconButton class="mt-1" v-if="input.details2.length > 1" type="button" raised circle icon="feather:trash"
            @click="removeItem2(index)" color="danger">
          </VIconButton>
        </VButtons>
      </div>
      <div class="column is-8 pt-0 pb-0"></div>
      <div class="column is-2 pr-0">
        <table style="width: 100%;border-collapse: collapse;border:1px solid black;">
          <tr>
            <td
              style="height: 5rem;border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              Tanggal & Jam</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              E</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              V</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              M</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Reaksi Pupil</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Kesadaran</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Atas</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Bawah</td>
          </tr>
        </table>
      </div>
      <div class="column is-10 pl-0" style="overflow: auto;">
        <table class="table" style="width: auto !important; border-collapse: collapse;border:1px solid black;">
          <tr>
            <td v-for="(data, index) in input.details2" :key="index" class="p-0">
              <table class="table" style="width: 14rem !important;">
                <tr>
                  <td style="height: 5rem; background-color: #e8e7e6">
                    <VDatePicker v-model="data['TGL_SARAF']" mode="datetime" is24hr>
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <!-- <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>E</VButton>
                      </VControl>
                      <VControl expanded>
                        <Multiselect v-model="data['GCSE']" :attrs="{ value }" placeholder="E" label="label"
                          :options="d_gcse" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          style="border-radius:0px 4px 4px 0px;height:100%">
                        </Multiselect>
                      </VControl>
                    </VField> -->
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="data['GCSE']" />
                      </VControl>
                    </VField>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <!-- <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>V</VButton>
                      </VControl>
                      <VControl expanded>
                        <Multiselect v-model="data['GCSV']" :attrs="{ value }" placeholder="E" label="label"
                          :options="d_gcsv" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          style="border-radius:0px 4px 4px 0px;height:100%">
                        </Multiselect>
                      </VControl>
                    </VField> -->
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="data['GCSV']" />
                      </VControl>
                    </VField>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <!-- <VField addons>
                      <VControl class="field-addon-body">
                        <VButton static>M</VButton>
                      </VControl>
                      <VControl expanded>
                        <Multiselect v-model="data['GCSM']" :attrs="{ value }" placeholder="M" label="label"
                          :options="d_gcsm" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          style="border-radius:0px 4px 4px 0px;height:100%">
                        </Multiselect>
                      </VControl>
                    </VField> -->
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" v-model="data['GCSM']" />
                      </VControl>
                    </VField>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['PUPIL']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['KESADARAN']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['ATAS']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['BAWAH']" />
                    </VControl>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </div>



    <div class="column p-0" style="display: none !important">
      <div class="column">
        <div class="columns is-multiline" v-for="(items, index) in input.listSistemSaraf" :key="index">
          <div class="column is-12 is-flex">
            <VButton color="primary" raised icon="fas fa-plus" class="mr-3" @click="addNewSistemSaraf()"
              :isLoading="isLoading"> Tambah </VButton>
            <VButton color="danger" v-if="items.no > 1" raised icon="fas fa-trash" class="mr-3"
              @click="removeSistemSaraf(index)" :isLoading="isLoading"> Hapus </VButton>
          </div>
          <div class="column is-3 pt-0">
            <VField label="Tanggan & Waktu">
              <VDatePicker v-model="items.tanggalSistemSaraf" mode="dateTime" style="width: 100%" trim-weeks
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

          <div class="column is-3 pt-0">
            <VField label="E"></VField>
            <VField addons>
              <VControl class="field-addon-body">
                <VButton static>E</VButton>
              </VControl>
              <VControl expanded>
                <VInput type="text" class="input" v-model="items.GCSe" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 pt-0">
            <VField label="V"></VField>
            <VField addons>
              <VControl class="field-addon-body">
                <VButton static>V</VButton>
              </VControl>
              <VControl expanded>
                <VInput type="text" class="input" v-model="items.GCSv" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 pt-0">
            <VField label="M"></VField>
            <VField addons>
              <VControl class="field-addon-body">
                <VButton static>M</VButton>
              </VControl>
              <VControl expanded>
                <VInput type="text" class="input" v-model="items.GCSm" />
              </VControl>
            </VField>
          </div>

          <div class="column is-3 pt-0">
            <VField>
              <VControl expanded>
                <VInput type="text" placeholder="Reaksi Pupil" class="input" v-model="items.reaksiPupil" />
              </VControl>
            </VField>
          </div>

          <div class="column is-3 pt-0">
            <VField>
              <VControl expanded>
                <VInput type="text" placeholder="Kesadaran" class="input" v-model="items.kesadaran" />
              </VControl>
            </VField>
          </div>

          <div class="column is-3 pt-0">
            <VField>
              <VControl expanded>
                <VInput type="text" placeholder="Atas" class="input" v-model="items.atas" />
              </VControl>
            </VField>
          </div>

          <div class="column is-3 pt-0">
            <VField>
              <VControl expanded>
                <VInput type="text" placeholder="Bawah" class="input" v-model="items.bawah" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </div>

    <span style="font-weight: bold; color: darkgray;">III. Status Respirasi</span>
    <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0 pt-0">
    <div class="column columns is-multiline">
      <div class="column is-4 pl-0 pb-0" style="text-align: center;">
        <VButtons style="justify-content:space-around">
          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem3()" color="info"
            v-tooltip.bubble="'Tambah '">
          </VIconButton>
          <VIconButton class="mt-1" v-if="input.details3.length > 1" type="button" raised circle icon="feather:trash"
            @click="removeItem3(index)" color="danger">
          </VIconButton>
        </VButtons>
      </div>
      <div class="column is-8 pt-0 pb-0"></div>
      <div class="column is-2 pr-0">
        <table style="width: 100%;border-collapse: collapse;border:1px solid black;">
          <tr>
            <td
              style="height: 5rem;border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              Tanggal & Jam</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Alat Bantu</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              No. ETT</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              PEEP</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              RR</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              FiO2</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Flow</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              PIP</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              I:E</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              TV</td>
          </tr>
        </table>
      </div>
      <div class="column is-10 pl-0" style="overflow: auto;">
        <table class="table" style="width: auto !important; border-collapse: collapse;border:1px solid black;">
          <tr>
            <td v-for="(data, index) in input.details3" :key="index" class="p-0">
              <table class="table" style="width: 14rem !important;">
                <tr>
                  <td style="height: 5rem; background-color: #e8e7e6">
                    <VDatePicker v-model="data['TGL_RESPIRASI']" mode="datetime" is24hr>
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="data['ALAT_BANTU']" :suggestions="d_AlatBantu" @complete="fetchAlatBantu()"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['NO_ETT']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['PEEP']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['RR']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['FiO2']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['Flow']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['PIP']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['IE']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['TV']" />
                    </VControl>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </div>






    <div class="column pl-0 pr-0 pt-0" style="display: none !important">
      <div class="columns is-multiline p-3" v-for="(items, index) in input.listStatusRespirasi" :key="index">
        <div class="column is-12 is-flex">
          <VButton color="primary" raised icon="fas fa-plus" class="mr-3" @click="addNewStatusRespirasi()"
            :isLoading="isLoading"> Tambah </VButton>
          <VButton color="danger" v-if="items.no > 1" raised icon="fas fa-trash" class="mr-3"
            @click="removeStatusRespirasi(index)" :isLoading="isLoading"> Hapus </VButton>
        </div>
        <div class="column is-3" v-for="(data) in statusRespirasi">
          <span class="label-icu">{{ data.label }}</span>
          <VField class="pt-3" v-if="data.type == 'textBox'">
            <VControl>
              <VInput type="text" class="input" v-model="input[data.model]" />
            </VControl>
          </VField>
          <VField v-else>
            <VControl class="prime-auto">
              <AutoComplete v-model="input[data.model]" :suggestions="d_AlatBantu" @complete="fetchAlatBantu()"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                class="mt-2" />
            </VControl>
          </VField>
        </div>
      </div>
    </div>

    <span style="font-weight: bold; color: darkgray;">IV. Hemodinamik</span>
    <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0 pt-0">
    <div class="column columns is-multiline">
      <div class="column is-4 pl-0 pb-0" style="text-align: center;">
        <VButtons style="justify-content:space-around">
          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem4()" color="info"
            v-tooltip.bubble="'Tambah '">
          </VIconButton>
          <VIconButton class="mt-1" v-if="input.details4.length > 1" type="button" raised circle icon="feather:trash"
            @click="removeItem4(index)" color="danger">
          </VIconButton>
        </VButtons>
      </div>
      <div class="column is-8 pt-0 pb-0"></div>
      <div class="column is-2 pr-0">
        <table style="width: 100%;border-collapse: collapse;border:1px solid black;">
          <tr>
            <td
              style="height: 5rem;border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              Tanggal & Jam</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              CVP</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              CRT</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Sedasi Sore</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Gambaran EKG</td>
          </tr>
        </table>
      </div>
      <div class="column is-10 pl-0" style="overflow: auto;">
        <table class="table" style="width: auto !important; border-collapse: collapse;border:1px solid black;">
          <tr>
            <td v-for="(data, index) in input.details4" :key="index" class="p-0">
              <table class="table" style="width: 14rem !important;">
                <tr>
                  <td style="height: 5rem; background-color: #e8e7e6">
                    <VDatePicker v-model="data['TGL_HEMODINAMIK']" mode="datetime" is24hr>
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['CVP']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl class="prime-auto">
                      <AutoComplete v-model="data['CRT']" :suggestions="d_CRT" @complete="fetchCRT()"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" />
                    </VControl>
                  </td>
                </tr>

                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['SEDASI']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['EKG']" />
                    </VControl>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </div>




    <div class="column pl-0 pr-0 pt-0" style="display: none !important">
      <div class="columns is-multiline pt-0">
        <div class="column is-3" v-for="(data) in hemodinamik">
          <span class="label-icu">{{ data.label }}</span>
          <VField class="pt-0" v-if="data.type == 'textBox'">
            <VControl>
              <VInput type="text" class="input" v-model="input[data.model]" />
            </VControl>
          </VField>
          <VField v-else class="pt-0">
            <VControl class="prime-auto">
              <AutoComplete v-model="input[data.model]" :suggestions="d_CRT" @complete="fetchCRT()"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" />
            </VControl>
          </VField>
        </div>
      </div>
    </div>

    <span style="font-weight: bold; color: darkgray;">V. Medikasi</span>
    <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0 pt-0">






    <div class="column columns is-multiline">
      <!-- Left Table -->
      <div class="column is-7 pr-0">
        <table class="table" style="width: 100%; border-collapse: collapse; border:1px solid black;">
          <tbody>
            <tr>
              <th colspan="5" style="text-align:center; background-color: palegreen;">Intruksi Pengobatan</th>
            </tr>
            <tr style="text-align: center;">
              <th style="background-color: palegreen;">#</th>
              <th style="background-color: palegreen;">No</th>
              <th style="background-color: palegreen; width:30%">Hari, Tanggal</th>
              <th style="background-color: palegreen;">Nama Obat</th>
              <th style="background-color: palegreen;">Paraf Dokter</th>
            </tr>
            <tr v-for="(item, index) in input.detailsCPO" :key="'left-' + index" ref="leftRows"
              :id="'left-row-' + index" :style="{ height: rowHeights[index] + 'px' }">
              <td>
                <VButtons style="display: flex; justify-content: space-around;">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemCPO()" color="info">
                  </VIconButton>
                  <VIconButton v-if="index > 0" type="button" raised circle icon="feather:trash"
                    @click="removeItemCPO(index)" color="danger"></VIconButton>
                </VButtons>
              </td>
              <td style="text-align: center;">{{ getAlphabet(index) }}</td>
              <td>
                <VDatePicker v-model="item.DTanggal_IP" mode="date" trim-weeks attach="body">
                  <template #default="{ inputValue, inputEvents }">
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </template>
                </VDatePicker>
              </td>
              <td>
                <VField>
                  <VTextarea has-fixed-size rows="2" v-model="item.TBNamaObat"></VTextarea>
                </VField>
              </td>
              <td>
                <VControl class="prime-auto">
                  <AutoComplete v-model="item.DParafDokter" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" />
                </VControl>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Right Table -->
      <div class="column is-5 pl-0" style="overflow: auto;">
        <table class="table" style="width: auto !important; border-collapse: collapse; border:1px solid black;">
          <tbody>
            <tr>
              <th colspan="6" style="text-align:center; background-color: palegreen;">&nbsp;</th>
            </tr>
            <tr style="text-align: center;">
              <th style="background-color: palegreen;min-width:200px;">Dosis</th>
              <th style="background-color: palegreen;min-width:150px;">Frekuensi</th>
              <th style="background-color: palegreen;min-width:150px;">Rute</th>
              <th style="background-color: palegreen;min-width:300px;">Paraf Dokter</th>
              <th style="background-color: palegreen;min-width:300px;">Paraf Apoteker</th>
            </tr>
            <tr v-for="(item, index) in input.detailsCPO" :key="'right-' + index" ref="rightRows"
              :id="'right-row-' + index" :style="{ height: rowHeights[index] + 'px' }">
              <td>
                <VControl>
                  <VInput type="text" class="input" v-model="item.TBDosis" />
                </VControl>
              </td>
              <td>
                <Multiselect v-model="item.listInjeksi" placeholder="--Pilih--" label="label" :options="listInjeksi"
                  :searchable="true" track-by="label" mode="single"></Multiselect>
                <VControl v-if="item.listInjeksi == 'Lainnya'">
                  <VInput type="text" class="input" v-model="item.FrekuensiLainnya" placeholder="Lainnya..." />
                </VControl>
              </td>
              <td>
                <Multiselect v-model="item.TBRute" placeholder="--Pilih--" label="label" :options="lisRute"
                  :searchable="true" track-by="label" mode="single"></Multiselect>
                <VControl v-if="item.TBRute == 'Lainnya'">
                  <VInput type="text" class="input" v-model="item.RuteLainnya" placeholder="Lainnya..." />
                </VControl>
              </td>
              <td>
                <VControl class="prime-auto">
                  <AutoComplete v-model="item.DDParafDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    optionLabel="label" dropdown minLength="3" appendTo="body" />
                </VControl>
              </td>
              <td>
                <VControl class="prime-auto">
                  <AutoComplete v-model="item.DDParafApoteker" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    optionLabel="label" dropdown minLength="3" appendTo="body" />
                </VControl>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="column">
      <h1 style="color: red;">Untuk Keselamatan Pasien :</h1>
      <h2>DOKTER :</h2>
      <span>
        1. Tulisakan nama obat termasuk dosis, frekuensi dan rute<br>
        2. Tulisan harus jelas dan terbaca, serta tanda tangan untuk keabsahan instruksi/resep<br>
        3. Tanda tangan dokter dalam catatan pengobatan harus dilakukan dalam 24 jam<br>
        4. Semua obat yang diberikan selama dirawat harus dicatat dalam catatan pengobatan<br>
        5. Pembatalan/penghentian diberi tanda 2 garis miring (//) pada kolom pemberian terakhir dan ditulis
        "STOP"
      </span>
    </div>

    <div class="columns is-multiline" style="margin-bottom: -15px;">
      <div class="column is-8">

      </div>
      <div class="column is-1 mr-5">
        <VButton type="button" raised circle rounded icon="feather:plus" @click="addNewDetail3()" color="info"
          class="mt-2">
          Tambah Hari
        </VButton>
      </div>
      <div class="column is-1">
        <VButton v-if="input.details3.length > 0" type="button" raised circle rounded icon="feather:trash"
          color="danger" @click="removeDetail3()" class="mt-2">
          Hapus Hari Terakhir
        </VButton>
      </div>
    </div>

    <!-- Catatan Pemberian Obat -->

    <div class="column is-12" style="overflow:auto">
      <div class="columns is-multiline">
        <!-- Left Table -->
        <div class="column is-3 pr-0">
          <table class="table" style="width: 100%; border-collapse: collapse; border:1px solid black; overflow:scroll">
            <tr>
              <td style="text-align:center; background-color: skyblue;">Catatan Pemberian Obat</td>
            </tr>
            <tr>
              <th colspan="1" style="text-align:center; background-color: skyblue;height:73px;">Nama Obat</th>
            </tr>
            <tbody v-for="(item, index) in input.detailsCPO2" :key="'left2-' + index" :id="'left2-row-' + index"
              :style="{ height: rowHeights2[index] + 'px' }">
              <tr>
                <td rowspan="3" class="non-overflow-text" style="background-color: #FFFFFF;">
                  {{ input.detailsCPO[index]?.TBNamaObat ?? '-' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Right Table -->
        <div class="column is-9 pl-0" style="overflow: auto;">
          <table class="table" style="width: auto !important; border-collapse: collapse; border:1px solid black;">
            <tr style="text-align: left;">
              <th :colspan="totalColumns - 1" style="background-color: skyblue;">&nbsp;</th>
            </tr>
            <tr style="vertical-align:center;">
              <th style="background-color: skyblue;">Tanggal</th>
              <th style="background-color: skyblue;" colspan="7">
                <div class="columns">
                  <div class="column is-6" style="text-align: center;">
                    <h1>Tanggal Pengisian</h1>
                    <div class="is-flex" style="justify-content: center;">
                      <VDatePicker v-model="input.D_1_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <h1>Hari Ke</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TB_1_CPO" style="width: 50%;" />
                    </VControl>
                  </div>
                </div>
              </th>
              <th style="background-color: skyblue;" colspan="7">
                <div class="columns">
                  <div class="column is-6" style="text-align: center;">
                    <h1>Tanggal Pengisian</h1>
                    <div class="is-flex" style="justify-content: center;">
                      <VDatePicker v-model="input.D_2_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <h1>Hari Ke</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TB_2_CPO" style="width: 50%;" />
                    </VControl>
                  </div>
                </div>
              </th>
              <th style="background-color: skyblue;" colspan="7">
                <div class="columns">
                  <div class="column is-6" style="text-align: center;">
                    <h1>Tanggal Pengisian</h1>
                    <div class="is-flex" style="justify-content: center;">
                      <VDatePicker v-model="input.D_3_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <h1>Hari Ke</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TB_3_CPO" style="width: 50%;" />
                    </VControl>
                  </div>
                </div>
              </th>
              <th style="background-color: skyblue;" colspan="7">
                <div class="columns">
                  <div class="column is-6" style="text-align: center;">
                    <h1>Tanggal Pengisian</h1>
                    <div class="is-flex" style="justify-content: center;">
                      <VDatePicker v-model="input.D_4_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <h1>Hari Ke</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TB_4_CPO" style="width: 50%;" />
                    </VControl>
                  </div>
                </div>
              </th>
              <th style="background-color: skyblue;" colspan="7">
                <div class="columns">
                  <div class="column is-6" style="text-align: center;">
                    <h1>Tanggal Pengisian</h1>
                    <div class="is-flex" style="justify-content: center;">
                      <VDatePicker v-model="input.D_5_CPO" mode="date" trim-weeks is24hr style="width: 50%;">
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <h1>Hari Ke</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="input.TB_5_CPO" style="width: 50%;" />
                    </VControl>
                  </div>
                </div>
              </th>
              <th style="background-color: skyblue;" colspan="7" v-for="(item, index) in input.detailsCPO3"
                :key="index">
                <div class="columns">
                  <div class="column is-6" style="text-align: center;">
                    <h1>Tanggal Pengisian</h1>
                    <div class="is-flex" style="justify-content: center;">
                      <VDatePicker v-model="item.tanggalPengisian" mode="date" trim-weeks is24hr style="width: 50%;">
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </div>
                  </div>
                  <div class="column is-6" style="text-align: center;">
                    <h1>Hari Ke</h1>
                    <VControl>
                      <VInput type="text" class="input" v-model="item.hariKe" style="width: 50%;" />
                    </VControl>
                  </div>
                </div>
              </th>
            </tr>
            <tbody v-for="(item, index) in input.detailsCPO2" :key="'right2-' + index" :id="'right2-row-' + index"
              :style="{ height: rowHeights2[index] + 'px' }">
              <tr>
                <td>Jam</td>
                <td v-for="(data, key) in ArrayKu" :key="key" style="min-width: 150px;">
                  <VDatePicker v-model="item[`Time_${key}`]" mode="time" is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:clock" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </td>
              </tr>
              <tr>
                <td>Paraf 1</td>
                <td v-for="(data, key) in ArrayKu" :key="key">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item[`DDParaf1_${key}`]" :suggestions="d_Pegawai"
                      @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </td>
              </tr>
              <tr>
                <td>Paraf 2</td>
                <td v-for="(data, key) in ArrayKu" :key="key">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item[`DDParaf2_${key}`]" :suggestions="d_Pegawai"
                      @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="column">
      <span><i><b>(M)</b> Menolak &nbsp;&nbsp;&nbsp; <b>(P)</b> Puasa &nbsp;&nbsp;&nbsp; <b>(V)</b>
          Dimuntahkan</i></span>
    </div>
    <div class="column">
      <h2>PERAWAT :</h2>
      <span>
        1. Periksa semua obat sebelum diberikan sesuai SPO yang berlaku<br>
        2. Pencatatan dan double check pada setiap pemberian obat untuk menghindari kesalahan pemberian<br>
        3. Perhatikan instruksi dokter dengan cermat dan teliti untuk setiap obat<br>
        4. Secara berpasangan, periksa ulang obat narkotika dan obat konsentrat sebelum diberikan sesuai dengan
        instruksi<br>
        5. Paraf 1 dan Paraf 2 diisi oleh perawat yang melakukan pemberian obat dan perawat yang mengecek ulang
      </span>
    </div>






    <div class="column pl-0 pr-0 pt-0" style="display: none !important">
      <div class="column pl-0 pr-0 pt-0">
        <VButton color="primary" raised icon="fas fa-plus" @click="showFormMedikasi"> Medikasi</VButton>
        <DataTable :value="dataSourceMedikasi" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          :loading="isLoading" class="p-datatable-sm mt-4"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="no" header="No" class="font-bold" style="text-align: center"></Column>
          <Column field="namaObat" header="Nama Obat" class="font-bold"></Column>
          <Column header="Waktu" :sortable="true">
            <template #body="slotProps">
              <span class="label-icu">{{ H.formatDateIndoSimple(slotProps.data.waktu) }}</span>
            </template>
          </Column>
          <Column field="dosis" header="Dosis" :sortable="true" style="text-align: center;" class="font-bold" />
          <Column :exportable="false" header="Action" style="text-align: center">
            <template #body="slotProps">
              <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                v-tooltip.top="'Edit'" @click="editMedikasi(slotProps.data)">
              </VIconButton>
              <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                v-tooltip.top="'Hapus'" @click="deleteMedikasi(slotProps.index)">
              </VIconButton>
            </template>
          </Column>
        </DataTable>
      </div>
    </div>

    <span style="font-weight: bold; color: darkgray;">VI. Balans Cairan</span>
    <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0 pt-0">
    <div class="column columns is-multiline">
      <div class="column is-4 pl-0 pb-0" style="text-align: center;">
        <VButtons style="justify-content:space-around">
          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
            v-tooltip.bubble="'Tambah '">
          </VIconButton>
          <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" raised circle icon="feather:trash"
            @click="removeItem(index)" color="danger">
          </VIconButton>
        </VButtons>
      </div>
      <div class="column is-8 pt-0 pb-0"></div>
      <div class="column is-2 pr-0">
        <table style="width: 100%;border-collapse: collapse;border:1px solid black;">
          <tr>
            <td
              style="height: 5rem;border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              Tanggal Penginputan</td>
          </tr>
          <tr>
            <td
              style="height: 2rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #f5ef4c">
              INTAKE</td>
          </tr>
          <tr>
            <td
              style="height: 2rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #92e0a7">
              Makan</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              Oral</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              Enteral</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              Jumlah/jam</td>
          </tr>
          <tr>
            <td
              style="height: 2rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #92e0a7">
              Transfusi</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              I</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              II</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              III</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              IV</td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              V</td>
          </tr>
          <tr v-for="(data, index) in input.detailsTransfusi" :key="index">
            <td
              style="height: 4.9rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6;">
              {{ toRoman(index + 6) }}
            </td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
              Jumlah/jam
              <div class=" columns is-multiline is-12" style="text-align: center;  ">
                <div class="column is-6" style="text-align: center;">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemTransfusi()"
                    color="info" v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                </div>
                <div class="column is-6" style="text-align: center;">
                  <VIconButton v-if="input.detailsTransfusi.length >= 1" type="button" raised circle
                    icon="feather:trash" @click="removeItemTransfusi(index)" color="danger">
                  </VIconButton>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td
              style="height: 2rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #92e0a7">
              Parenteral</td>
          </tr>
          <tr>
            <td style="height: 5rem; background-color: #62b3f5; padding: 5px; padding-top: 8px;">
              <div v-for="(data, index) in input.detailss" :key="index" style="margin-top: 10px;">
                <VControl>
                  <VInput type="text" class="input" v-model="data['FREETEXT']" />
                </VControl>
              </div>
            </td>
          </tr>
          <tr>
            <td style="height: 5rem; background-color: #62b3f5; padding: 5px; padding-top: 8px;">
              <div v-for="(data, index) in input.detailss" :key="index" style="margin-top: 10px;">
                <VControl>
                  <div class="fake-input"></div>
                </VControl>
              </div>
            </td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Jumlah
              <div class=" columns is-multiline is-12" style="text-align: center;  ">
                <div class="column is-6" style="text-align: center;">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemPar()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                </div>
                <div class="column is-6" style="text-align: center;">
                  <VIconButton v-if="input.detailss.length > 1" type="button" raised circle icon="feather:trash"
                    @click="removeItemPar(index)" color="danger">
                  </VIconButton>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td
              style="height: 2rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #f5ef4c">
              OUTPUT</td>
          </tr>
          <tr>
            <td style="height: 5rem; background-color: #62b3f5; padding-top: 10px;">
              <div v-for="(data, index) in input.detailout" :key="index" style="margin-top: 10px;">
                <VControl>
                  <div class="fake-input"></div>
                </VControl>
              </div>
            </td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
              Jumlah
              <div class=" columns is-multiline is-12" style="text-align: center;  ">
                <div class="column is-6" style="text-align: center;">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItemOut()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                </div>
                <div class="column is-6" style="text-align: center;">
                  <VIconButton v-if="input.detailout.length > 1" type="button" raised circle icon="feather:trash"
                    @click="removeItemOut(index)" color="danger">
                  </VIconButton>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td
              style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #f5ef4c">
              BALANCE</td>
          </tr>
        </table>
      </div>
      <div class="column is-10 pl-0" style="overflow: auto;">
        <table class="table" style="width: auto !important; border-collapse: collapse;border:1px solid black;">
          <tr>
            <td v-for="(data, index) in input.details" :key="index" class="p-0">
              <table class="table" style="width: 14rem !important;">
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VDatePicker v-model="data['DT_INTAKE']" mode="datetime" is24hr>
                      <template #default="{ inputValue, inputEvents }">
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </template>
                    </VDatePicker>
                  </td>
                </tr>
                <tr>
                  <td style="height: 2rem; background-color: #f5ef4c"></td>
                </tr>
                <tr>
                  <td style="height: 2rem; background-color: #92e0a7"></td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['TB_ORAL']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['TB_ENTERAL']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['JUMLAH31']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 2rem; background-color: #92e0a7"></td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['I']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['II']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['III']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['IV']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['V']" />
                    </VControl>
                  </td>
                </tr>
                <tr v-for="(dataTransfusi, colIndex) in input.detailsTransfusi" :key="colIndex">
                  <td style="height: 4.9rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input"
                        v-model="dataTransfusi[`transfusi${index + 1}_${colIndex + 1}`]" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['JUMLAH32']" />
                    </VControl>
                  </td>
                </tr>
                <tr>
                  <td style="height: 2rem; background-color: #92e0a7"></td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <div v-for="(row, rowIndex) in input.detailss" :key="rowIndex" style="margin-top:10px">
                      <VControl>
                        <VInput type="text" class="input" v-model="row[`FREE${index + 1}_${rowIndex + 1}`]" />
                      </VControl>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <div v-for="(row, rowIndex) in input.detailss" :key="rowIndex" style="margin-top: 10px;">
                      <VControl>
                        <VInput type="text" class="input" v-model="row[`JUMLAH1${index + 1}_${rowIndex + 1}`]" />
                      </VControl>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td
                    style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
                    <div class=" columns is-multiline is-12" style="text-align: center;  ">
                      <div class="column is-6" style="text-align: center;">
                      </div>
                      <div class="column is-6" style="text-align: center;">
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td style="height: 2rem; background-color: #f5ef4c"></td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #62b3f5">
                    <div v-for="(rows, rowsIndex) in input.detailout" :key="rowsIndex" style="margin-top: 10px;">
                      <VControl>
                        <VInput type="text" class="input" v-model="rows[`JUMLAH2${index + 1}_${rowsIndex + 1}`]" />
                      </VControl>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td
                    style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
                    <div class=" columns is-multiline is-12" style="text-align: center;  ">
                      <div class="column is-6" style="text-align: center;">
                      </div>
                      <div class="column is-6" style="text-align: center;">
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td style="height: 5rem; background-color: #f5ef4c">
                    <VControl>
                      <VInput type="text" class="input" v-model="data['TB']" />
                    </VControl>
                  </td>
                </tr>
                <!-- <tr>
                                <td style="height: 5rem; background-color: #62b3f5">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="data['ML']"/>
                                    </VControl>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 5rem; background-color: #62b3f5">
                                    <VDatePicker v-model="data['DT_INTAKE']" mode="datetime" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 5rem; background-color: #62b3f5">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="data['TB_TOTAL']"/>
                                    </VControl>
                                </td>
                            </tr> -->
              </table>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div class="column pl-0 pr-0 pt-0" style="display: none !important">
      <div class="column pl-0 pr-0 pt-0">
        <VButton color="primary" class="mr-3" raised icon="fas fa-plus" @click="showFormIntake"
          style="display: none !important">Intake</VButton>
        <VButton color="info" raised icon="fas fa-plus" @click="showFormOutput" style="display: none !important">Output
        </VButton>
        <DataTable style="display: none !important" :value="dataSourceIntake" :paginator="true" :rows="5"
          :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" class="p-datatable-sm mt-4"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="no" header="No" class="font-bold" style="text-align: center"></Column>
          <Column field="namaIntake" header="Intake" class="font-bold"></Column>
          <Column field="jenisIntake" header="Jenis Intake" class="font-bold"></Column>
          <Column field="mlIntake" header="ML" class="font-bold"></Column>
          <Column header="Tanggal dan Waktu" :sortable="true">
            <template #body="slotProps">
              <span class="label-icu">{{ H.formatDateIndoSimple(slotProps.data.tanggalIntake) }}</span>
            </template>
          </Column>
          <Column field="totalIntake" header="Total Intake" class="font-bold"></Column>
          <Column :exportable="false" header="Action" style="text-align: center">
            <template #body="slotProps">
              <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                v-tooltip.top="'Edit'" @click="editIntake(slotProps.data)">
              </VIconButton>
              <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                v-tooltip.top="'Hapus'" @click="deleteIntake(slotProps.index)">
              </VIconButton>
            </template>
          </Column>
        </DataTable>
        <DataTable style="display: none !important" :value="dataSourceOutput" :paginator="true" :rows="5"
          :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" class="p-datatable-sm mt-4"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="no" header="No" class="font-bold" style="text-align: center"></Column>
          <Column field="namaOutput" header="Output" class="font-bold"></Column>
          <Column field="jenisOutput" header="Jenis Output" class="font-bold"></Column>
          <Column field="mlOutput" header="ML" class="font-bold"></Column>
          <Column header="Tanggal dan Waktu" :sortable="true">
            <template #body="slotProps">
              <span class="label-icu">{{ H.formatDateIndoSimple(slotProps.data.tanggalOutput) }}</span>
            </template>
          </Column>
          <Column field="totalOutput" header="Total Output" class="font-bold"></Column>
          <Column :exportable="false" header="Action" style="text-align: center">
            <template #body="slotProps">
              <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                v-tooltip.top="'Edit'" @click="editOutput(slotProps.data)">
              </VIconButton>
              <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                v-tooltip.top="'Hapus'" @click="deleteOutput(slotProps.index)">
              </VIconButton>
            </template>
          </Column>
        </DataTable>
        <VField label="Total Balance">
          <VControl>
            <VInput v-model="totalBalance" placeholder="Total Balance" />
          </VControl>
        </VField>
      </div>
    </div>



    <div class="column p-0 mb-5">
      <span style="font-weight: bold; color: darkgray;">VII. Data Laboratorium</span>
      <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0">
      <div class="column columns is-multiline">
        <div class="column is-4 pl-0 pb-0" style="text-align: center;">
          <VButtons style="justify-content:space-around">
            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem1()" color="info"
              v-tooltip.bubble="'Tambah '">
            </VIconButton>
            <VIconButton class="mt-1" v-if="input.details1.length > 1" type="button" raised circle icon="feather:trash"
              @click="removeItem1(index)" color="danger">
            </VIconButton>
          </VButtons>
        </div>
        <div class="column is-8 pt-0 pb-0"></div>
        <div class="column is-2 pr-0">
          <table style="width: 100%;border-collapse: collapse;border:1px solid black;">
            <tr>
              <td
                style="height: 5rem;border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #e8e7e6">
                Tanggal & Jam</td>
            </tr>
            <tr>
              <td
                style="height: 5rem; border-bottom: 1px solid black; font-weight: bold; font-size: 11pt; text-align: center; background-color: #62b3f5">
                Keterangan</td>
            </tr>
          </table>
        </div>
        <div class="column is-10 pl-0" style="overflow: auto;">
          <table class="table" style="width: auto !important; border-collapse: collapse;border:1px solid black;">
            <tr>
              <td v-for="(data, index) in input.details1" :key="index" class="p-0">
                <table class="table" style="width: 14rem !important;">
                  <tr>
                    <td style="height: 5rem; background-color: #e8e7e6">
                      <VDatePicker v-model="data['TGL']" mode="datetime" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" v-on="inputEvents" />
                          </VControl>
                        </template>
                      </VDatePicker>
                    </td>
                  </tr>
                  <tr>
                    <td style="height: 5rem; background-color: #62b3f5">
                      <VControl>
                        <VInput type="text" class="input" v-model="data['KET']" />
                      </VControl>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="column p-0 mb-5" v-for="(data) in titleMore">
      <span style="font-weight: bold; color: darkgray;">{{ data.title }}</span>
      <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0">
      <VField class="pt-3">
        <VControl>
          <VTextarea v-model="input[data.value]" rows="2">
          </VTextarea>
        </VControl>
      </VField>
    </div>

    <span style="font-weight: bold; color: darkgray;">IX. Alat Invasif</span>
    <hr style="border-top: 1px dashed red;background-color:white" class="mb-1 mt-0 pt-0">
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <h1 class="mb-3 emr">PROSEDUR INVASIF</h1>
            </div>
            <div class="column is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.intravena" true-value="Infus intra vena"
                    label="Infus intra vena" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">dipasang di :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.pasangintravena" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">tanggal :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker v-model="input.tglintravena" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-1">
              <h1 class="mb-3 emr">kalkulasi hari</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kalkulasi1" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.cvc" true-value="Central Line (CVC)"
                    label="Central Line (CVC)" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">dipasang di :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.pasangcvc" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">tanggal :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker v-model="input.tglcvc" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-1">
              <h1 class="mb-3 emr">kalkulasi hari</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kalkulasi2" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.cath" true-value="Dower Chateter"
                    label="Dower Chateter" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">dipasang di :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.pasangcath" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">tanggal :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker v-model="input.tglcath" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-1">
              <h1 class="mb-3 emr">kalkulasi hari</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kalkulasi3" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.selangngt" true-value="Selang NGT"
                    label="Selang NGT" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">dipasang di :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.pasangngt" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">tanggal :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker v-model="input.tglngt" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-1">
              <h1 class="mb-3 emr">kalkulasi hari</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kalkulasi4" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.trakeostomy" true-value="Trakeostomy"
                    label="Trakeostomy" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">dipasang di :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.pasangtrakeostomy" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">tanggal :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker v-model="input.tgltrakeostomy" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-1">
              <h1 class="mb-3 emr">kalkulasi hari</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kalkulasi5" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.ett" true-value="ETT/Ventilator"
                    label="ETT/Ventilator" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">dipasang di :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.pasangett" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">tanggal :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker v-model="input.tglett" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-1">
              <h1 class="mb-3 emr">kalkulasi hari</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kalkulasi6" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-1">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.lainlainbawahranap" true-value="Lain-lain"
                    label="Lain-lain" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.ketLainlainbawahranap" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">dipasang di :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.pasanglainlain" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <h1 class="mb-3 emr">tanggal :</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VDatePicker v-model="input.tgllainlain" mode="date" trim-weeks :max-date="new Date()">
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
            <div class="column is-1">
              <h1 class="mb-3 emr">kalkulasi hari</h1>
            </div>
            <div class="column is-2">
              <VField>
                <VControl>
                  <VTextarea v-model="input.kalkulasi7" placeholder="" rows="1">
                  </VTextarea>
                </VControl>
              </VField>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="column pl-0 pr-0 pt-0" style="display: none !important">
      <div class="column pl-0 pr-0 pt-0">
        <VButton color="primary" raised icon="fas fa-plus" @click="showFormPemasanganAlat">Alat Invasif</VButton>
        <DataTable :value="dataSourceAlatInvasif" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          :loading="isLoading" class="p-datatable-sm mt-4"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="no" header="No" class="font-bold" style="text-align: center"></Column>
          <Column field="alatInvasif" header="Pemasangan Alat Invasif" class="font-bold"></Column>
          <Column header="Pasang" :sortable="true">
            <template #body="slotProps">
              <span class="label-icu">{{ H.formatDate(slotProps.data.tglPasang, 'DD-MM-YYYY') }}</span>
            </template>
          </Column>
          <Column header="Cabut" :sortable="true">
            <template #body="slotProps">
              <span class="label-icu">{{ H.formatDate(slotProps.data.tglCabut, 'DD-MM-YYYY') }}</span>
            </template>
          </Column>
          <Column field="hari" header="Hari Ke" :sortable="true" style="text-align: center;" class="font-bold" />
          <Column :exportable="false" header="Action" style="text-align: center">
            <template #body="slotProps">
              <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                v-tooltip.top="'Edit'" @click="editItemAlat(slotProps.data)">
              </VIconButton>
              <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                v-tooltip.top="'Hapus'" @click="deleteItemAlat(slotProps.index)">
              </VIconButton>
            </template>
          </Column>
        </DataTable>
      </div>
    </div>

    <!-- <div class="column">
      <VTabs slider selected="data" :tabs="[
        { label: 'Data', value: 'data' },
        { label: 'Chart', value: 'chart' },
      ]">
        <template #tab="{ activeValue }">

          <p v-if="activeValue === 'data'">
            <DataTable :value="description" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
              editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
              tableStyle="min-width: 50rem" groupRowsBy="group" rowGroupMode="subheader">

              <ColumnGroup type="header">
                <Row>
                  <Column header="Deskripsi" style="min-width: 150px; text-align: center;" frozen :rowspan="2" />
                  <Column header="Waktu" style="min-width: 300px;" class="font-bold" :colspan="waktu.length" />
                </Row>
                <Row>
                  <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                    :rowEditor="true">
                  </Column>
                </Row>
              </ColumnGroup>

              <Column field="deskripsi" style="min-width: 150px; text-align: center;" frozen />
              <Column style="width: 25%" v-for="(data, i) in waktu" class="font-bold" :field="data">
                <template #body="slotProps">
                  {{ slotProps.data[data] }}
                </template>
                <template #editor="{ data, field }">
                  <InputText v-model="data[field]" autofocus />
                </template>
              </Column>
              <template #groupheader="slotProps">
                <div class="font-bold">
                  <span class="label-icu">{{ slotProps.data.group }}</span>
                </div>
              </template>
            </DataTable>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">III. Status Respirasi</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="columns is-multiline p-3 mt-1">
              <div class="column is-3" v-for="(data) in statusRespirasi">
                <span class="label-icu">{{ data.label }}</span>
                <VField class="pt-3" v-if="data.type == 'textBox'">
                  <VControl>
                    <VInput type="text" class="input" v-model="input[data.model]" />
                  </VControl>
                </VField>
                <VField v-else>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input[data.model]" :suggestions="d_AlatBantu" @complete="fetchAlatBantu()"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" class="mt-2" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">IV. Hemodinamik</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="columns is-multiline p-3 mt-1">
              <div class="column is-3" v-for="(data) in hemodinamik">
                <span class="label-icu">{{ data.label }}</span>
                <VField class="pt-3" v-if="data.type == 'textBox'">
                  <VControl>
                    <VInput type="text" class="input" v-model="input[data.model]" />
                  </VControl>
                </VField>
                <VField v-else>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input[data.model]" :suggestions="d_CRT" @complete="fetchCRT()"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" class="mt-2" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">V. Medikasi</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="addNewItem"> Button </VButton>

            <div class="column pl-0 pr-0">
              <DataTable :value="medikasi" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                tableStyle="min-width: 50rem">

                <ColumnGroup type="header">
                  <Row>
                    <Column header="Nama Obat" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                    <Column header="Dosis" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                    <Column header="Action" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                    <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                      :colspan="listDescripWaktu.ketWaktu.length" />
                  </Row>
                  <Row>
                    <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                      :colspan="2">
                    </Column>
                  </Row>
                  <Row>
                    <Column :header="data" style="min-width: 100px;" v-for="(data) in listDescripWaktu.ketWaktu"
                      class="font-bold">
                    </Column>
                  </Row>
                </ColumnGroup>

                <Column field="namaObat" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                  <template #body="slotProps">
                    {{ slotProps.data.namaObat }}
                  </template>
                  <template #editor="{ data, field }">
                    <InputText v-model="data[field]" autofocus />
                  </template>
                </Column>
                <Column field="dosis" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                  <template #body="slotProps">
                    {{ slotProps.data.dosis }}
                  </template>
                  <template #editor="{ data, field }">
                    <InputText v-model="data[field]" autofocus />
                  </template>
                </Column>
                <Column field="action" style="min-width: 150px; text-align: center;" frozen>
                  <template #body="slotProps">
                    <VIconButton class="mt-1" v-if="slotProps.data" type="button" raised circle icon="feather:trash"
                      @click="removeItem(slotProps.index)" color="danger">
                    </VIconButton>
                  </template>
                </Column>
                <Column style="width: 25%" v-for="(data, i) in listDescripWaktu.ketWaktu" class="font-bold"
                  :field="data + '_' + i">
                  <template #body="slotProps">
                    {{ slotProps.data[data + '_' + i] }}
                  </template>
                  <template #editor="{ data, field, }">
                    <InputText v-model="data[field]" autofocus />
                  </template>
                </Column>
              </DataTable>
            </div>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">VI. Balans Cairan</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="column pl-0 pr-0">
              <DataTable :value="tableBalansCairan" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                tableStyle="min-width: 50rem" groupRowsBy="group" rowGroupMode="subheader">

                <ColumnGroup type="header">
                  <Row>
                    <Column header="Deskripsi" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                    <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                      :colspan="listDescripWaktu.descWaktu.length" />
                  </Row>
                  <Row>
                    <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                      :colspan="2">
                    </Column>
                  </Row>
                  <Row>
                    <Column :header="data" style="min-width: 100px;" v-for="(data) in listDescripWaktu.descWaktu"
                      class="font-bold" :rowEditor="true">
                    </Column>
                  </Row>
                </ColumnGroup>

                <Column field="deskripsi" style="min-width: 150px; text-align: center;" frozen />

                <Column style="width: 25%" v-for="(data, i) in listDescripWaktu.descWaktu" class="font-bold"
                  :field="data + '_' + i">
                  <template #body="slotProps">
                    {{ slotProps.data[data + '_' + i] }}
                  </template>
                  <template #editor="{ data, field, }">
                    <InputText v-model="data[field]" autofocus />
                  </template>
                </Column>
                <template #groupheader="slotProps">
                  <div class="font-bold">
                    <span class="label-icu">{{ slotProps.data.group }}</span>
                  </div>
                </template>
              </DataTable>
            </div>
          </div>

          <div class="column p-0 mb-5" v-for="(data) in titleMore">
            <span class="label-icu">{{ data.title }}</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input[data.value]" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">VI. Balans Cairan</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="showFormPemasanganAlat"> Button
            </VButton>
            <DataTable :value="dataSourceAlatInvasif" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
              :loading="isLoading" class="p-datatable-sm mt-4"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
              <Column field="no" header="No" class="font-bold" style="text-align: center"></Column>
              <Column field="alatInvasif" header="Pemasangan Alat Invasif" class="font-bold"></Column>
              <Column header="Pasang" :sortable="true">
                <template #body="slotProps">
                  <span class="label-icu">{{ H.formatDate(slotProps.data.tglPasang, 'DD-MM-YYYY') }}</span>
                </template>
              </Column>
              <Column header="Cabut" :sortable="true">
                <template #body="slotProps">
                  <span class="label-icu">{{ H.formatDate(slotProps.data.tglCabut, 'DD-MM-YYYY') }}</span>
                </template>
              </Column>
              <Column field="hari" header="Hari Ke" :sortable="true" style="text-align: center;" class="font-bold" />
              <Column :exportable="false" header="Action" style="text-align: center">
                <template #body="slotProps">
                  <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                    v-tooltip.top="'Edit'" @click="editItemAlat(slotProps.data)">
                  </VIconButton>
                  <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                    v-tooltip.top="'Hapus'" @click="deleteItemAlat(slotProps.index)">
                  </VIconButton>
                </template>
              </Column>
            </DataTable>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="columns is-multiline pt-5">
              <div class="column is-4">
                <span class="label-icu">Paraf Petugas Pagi</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.petugasPagi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <span class="label-icu">Paraf Petugas Siang</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.petugasSiang" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <span class="label-icu">Paraf Petugas Malam</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.petugasMalam" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          </p>



          <p v-else-if="activeValue === 'chart'">


          </p>

        </template>
      </VTabs>
    </div> -->
  </VCard>

  <VModal is="form" :open="formTambahMedikasi" title="Form " size="middle" actions="right"
    @close="formTambahMedikasi = false">
    <template #content>
      <div class="modal-form" style="height: 600px;">
        <div class="field">
          <label>Nama Obat</label>
          <div class="control">
            <input type="text" class="input" placeholder="Nama Obat" v-model="item.namaObat" />
          </div>
        </div>
        <div class="field">
          <label>Dosis</label>
          <div class="control">
            <VField>
              <VControl>
                <VInput type="text" class="input" v-model="item.dosis" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="field">
          <label>Waktu</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.waktu" color="green" trim-weeks mode="datetime"
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
      </div>
    </template>
    <template #action>
      <VButton type="submit" color="primary" raised @click="addtoSourceMedikasi(item)">Simpan</VButton>
    </template>
  </VModal>

  <VModal is="form" :open="formTambahAlat" title="Form " size="small" actions="right" @close="formTambahAlat = false">
    <template #content>
      <div class="modal-form">
        <div class="field">
          <label>Nama Alat</label>
          <div class="control">
            <input type="text" class="input" placeholder="Nama Alat" v-model="item.namaAlat" />
          </div>
        </div>
        <div class="field">
          <label>Pasang</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.pasang" color="green" trim-weeks mode="date" :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="field">
          <label>Cabut</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.cabut" color="green" trim-weeks mode="date" :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="field">
          <label>Hari Ke</label>
          <div class="control">
            <input type="text" class="input" v-model="item.hariKe" />
          </div>
        </div>
      </div>
    </template>
    <template #action>
      <VButton type="submit" color="primary" raised @click="addtoSourceAlat(item)">Simpan</VButton>
    </template>
  </VModal>

  <VModal is="form" :open="formIntake" title="Form " size="small" actions="right" @close="formIntake = false">
    <template #content>
      <div class="modal-form" style="height: 600px;">
        <div class="field">
          <label>Nama Intake</label>
          <div class="control">
            <input type="text" class="input" placeholder="Jenis" v-model="item.namaIntake" />
          </div>
        </div>
        <div class="field">
          <label>Jenis</label>
          <div class="control">
            <input type="text" class="input" placeholder="Jenis" v-model="item.jenisIntake" />
          </div>
        </div>
        <div class="field">
          <label>ML</label>
          <div class="control">
            <input type="text" class="input" placeholder="Nama Alat" v-model="item.mlIntake" />
          </div>
        </div>
        <div class="field">
          <label>Tanggal & waktu</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.tanggalIntake" color="green" trim-weeks mode="datetime"
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="field">
          <label>Total Intake</label>
          <div class="control">
            <input type="text" class="input" placeholder="Total Intake" v-model="item.totalIntake" />
          </div>
        </div>
      </div>
    </template>
    <template #action>
      <VButton type="submit" color="primary" raised @click="addtoSourceIntake(item)">Simpan</VButton>
    </template>
  </VModal>

  <VModal is="form" :open="formOutput" title="Form " size="small" actions="right" @close="formOutput = false">
    <template #content>
      <div class="modal-form" style="height: 600px;">
        <div class="field">
          <label>Nama Output</label>
          <div class="control">
            <input type="text" class="input" placeholder="Nama" v-model="item.namaOutput" />
          </div>
        </div>
        <div class="field">
          <label>Jenis</label>
          <div class="control">
            <input type="text" class="input" placeholder="Jenis" v-model="item.jenisOutput" />
          </div>
        </div>
        <div class="field">
          <label>ML</label>
          <div class="control">
            <input type="text" class="input" placeholder="Nama Alat" v-model="item.mlOutput" />
          </div>
        </div>
        <div class="field">
          <label>Tanggal & waktu</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.tanggalOutput" color="green" trim-weeks mode="datetime"
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="field">
          <label>Total Output</label>
          <div class="control">
            <input type="text" class="input" placeholder="Total Output" v-model="item.totalOutput" />
          </div>
        </div>
      </div>
    </template>
    <template #action>
      <VButton type="submit" color="primary" raised @click="addDataSourceOutput(item)">Simpan</VButton>
    </template>
  </VModal>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, watchEffect, nextTick, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Fieldset from 'primevue/fieldset';
import * as ICU from '../page-emr-plugins/monitoring-icu'
import PreviewMonitoringIcu from '../page-emr/preview-monitoring-icu.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import InputText from 'primevue/inputtext';
import { alatBantu } from '../page-emr-plugins/asesmen-awal-keperawatan-rawat-inap'
import { followersList } from '/@src/data/widgets/ui/followers'
import { tagList1, tagList2 } from '/@src/data/widgets/ui/tagList'
import { tabs } from '/@src/data/widgets/ui/tabList'
import { iconList } from '/@src/data/widgets/ui/menuList'
// import { notifications } from '/@src/data/widgets/ui/notificationList'
import { trendWidgetChartOptions } from '/@src/data/widgets/charts/trendWidgetChart'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useConfirm } from 'primevue/useconfirm'
import Dialog from 'primevue/dialog';
import Chart from 'primevue/chart';
import moment from 'moment'
import { v4 as uuidv4 } from 'uuid';


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let waktu = ref(ICU.waktu())
let listDescripWaktu = ref(ICU.listDescripWaktu())
let description = ref(ICU.dataTableDescrip())
let statusRespirasi = ref(ICU.statusRespirasi())
let hemodinamik = ref(ICU.Hemodinamik())
let tableBalansCairan = ref(ICU.tableBalansCairan())
let titleMore = ref(ICU.titleMore())

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
    hideButtons?: boolean
    dataTertutup?: any
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
    hideButtons: false,
    dataTertutup: {},
  }
)
const previewTutupJadwal = ref(false);
const listTutupJadwal: any = ref([]);
const inputVitalSign = ref(false);
const listVital: any = ref([])
const modalInput: any = ref(false)
const chartData = ref();
const chartOptions = ref();
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const isLoadingHolder: any = ref(false)
const medikasi = ref([]);
const d_Obat: any = ref([])
const d_AlatBantu: any = ref([])
const d_CRT: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const formTambahAlat: any = ref(false)
const formIntake: any = ref(false)
const formOutput: any = ref(false)
const formTambahMedikasi: any = ref(false)
const showFormInput: any = ref(false)
// const showFormMedikasi: any = ref(false)
const dataSourceMedikasi: any = ref([])
const dataSourceAlatInvasif: any = ref([])
const dataSourceIntake: any = ref([]);
const dataSourceOutput: any = ref([]);
const item: any = reactive({})
const user = useUserSession().getUser().pegawai;
const filter: any = ref('')
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref(props.norecTertutup)
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const hours = new Date().setHours(0, 0, 0, 0);
const lisRute: any = ref([{ value: 'Oral', label: 'Oral' }, { value: 'Injeksi', label: 'Injeksi' }, { value: 'nebul', label: 'nebul' }, { value: 'Lainnya', label: 'Lainnya' }])
const listInjeksi: any = ref([{ value: '/24', label: '/24' }, { value: '/12', label: '/12' }, { value: '/8', label: '/8' }, { value: '/6', label: '/6' }, { value: 'Lainnya', label: 'Lainnya' }])
const input: any = ref({
  tanggal: new Date(),
  listVitalSignDetails: [{
    no: 1,
    id: uuidv4(),
  }],
  listSistemSaraf: [{
    no: 1,
    id: uuidv4(),
  }],
  listStatusRespirasi: [{
    no: 1,
    id: uuidv4(),
  }],
  balansCairanIntake: [{
    no: 1,
    id: uuidv4(),
  }],
  details: [{
    NO: 1,
    DT_INTAKE: new Date(),
  }],
  detailss: [{
    NO: 1,
  }],
  detailsTransfusi: [{
    NO: 1,
  }],
  detailout: [{
    NO: 1,
  }],
  details1: [{
    TGL: '',
    KET: '',
  }],
  details2: [{
    TGL_SARAF: new Date(),
  }],
  details3: [{
    TGL_RESPIRASI: new Date(),
  }],
  details4: [{
    TGL_HEMODINAMIK: new Date(),
  }],
  D_1_CPO: new Date(),
  detailsCPO: [{ no: 1, DTanggal_IP: new Date() }],
  detailsCPO2: [{
    no: 1,
    Time_0: hours, Time_1: hours, Time_2: hours, Time_3: hours, Time_4: hours, Time_5: hours, Time_6: hours, Time_7: hours, Time_8: hours, Time_9: hours,
    Time_10: hours, Time_11: hours, Time_12: hours, Time_13: hours, Time_14: hours, Time_15: hours, Time_16: hours, Time_17: hours, Time_18: hours, Time_19: hours,
    Time_20: hours, Time_21: hours, Time_22: hours, Time_23: hours, Time_24: hours, Time_25: hours, Time_26: hours, Time_27: hours, Time_28: hours, Time_29: hours,
    Time_30: hours, Time_31: hours, Time_32: hours, Time_33: hours, Time_34: hours, Time_35: hours, Time_36: hours, Time_37: hours, Time_38: hours, Time_39: hours, Time_40: hours, Time_41: hours
  }],
  detailsCPO3: [{ tanggalPengisian: "", hariKe: "" }],
})
const resetInput: any = ref({
  id: '',
  tanggal: new Date(),
  listVitalSignDetails: [{
    no: 1,
    id: uuidv4(),
  }],
  listSistemSaraf: [{
    no: 1,
    id: uuidv4(),
  }],
  listStatusRespirasi: [{
    no: 1,
    id: uuidv4(),
  }],
  balansCairanIntake: [{
    no: 1,
    id: uuidv4(),
  }],
  details: [{
    NO: 1,
    DT_INTAKE: new Date(),
  }],
  detailss: [{
    NO: 1,
  }],
  detailsTransfusi: [{
    NO: 1,
  }],
  detailout: [{
    NO: 1,
  }],
  details1: [{
    TGL: '',
    KET: '',
  }],
  details2: [{
    TGL_SARAF: new Date(),
  }],
  details3: [{
    TGL_RESPIRASI: new Date(),
  }],
  details4: [{
    TGL_HEMODINAMIK: new Date(),
  }],
  D_1_CPO: new Date(),
  detailsCPO: [{ no: 1, DTanggal_IP: new Date() }],
  detailsCPO2: [{
    no: 1,
    Time_0: hours, Time_1: hours, Time_2: hours, Time_3: hours, Time_4: hours, Time_5: hours, Time_6: hours, Time_7: hours, Time_8: hours, Time_9: hours,
    Time_10: hours, Time_11: hours, Time_12: hours, Time_13: hours, Time_14: hours, Time_15: hours, Time_16: hours, Time_17: hours, Time_18: hours, Time_19: hours,
    Time_20: hours, Time_21: hours, Time_22: hours, Time_23: hours, Time_24: hours, Time_25: hours, Time_26: hours, Time_27: hours, Time_28: hours, Time_29: hours,
    Time_30: hours, Time_31: hours, Time_32: hours, Time_33: hours, Time_34: hours, Time_35: hours, Time_36: hours, Time_37: hours, Time_38: hours, Time_39: hours, Time_40: hours, Time_41: hours
  }],
  detailsCPO3: [{ tanggalPengisian: "", hariKe: "" }],
})

const toRoman = (num: number): string => {
  const romanMap = [
    { value: 1000, numeral: 'M' },
    { value: 900, numeral: 'CM' },
    { value: 500, numeral: 'D' },
    { value: 400, numeral: 'CD' },
    { value: 100, numeral: 'C' },
    { value: 90, numeral: 'XC' },
    { value: 50, numeral: 'L' },
    { value: 40, numeral: 'XL' },
    { value: 10, numeral: 'X' },
    { value: 9, numeral: 'IX' },
    { value: 5, numeral: 'V' },
    { value: 4, numeral: 'IV' },
    { value: 1, numeral: 'I' },
  ];

  let result = '';
  for (const { value, numeral } of romanMap) {
    while (num >= value) {
      result += numeral;
      num -= value;
    }
  }
  return result;
};

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const addNewItemCPO = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.detailsCPO[input.value.detailsCPO.length - 1].no + 1,
    DTanggal_IP: new Date()
  }
  input.value.detailsCPO.push(newItem);

  if (!input.value.detailsCPO2.length) return; // Prevent errors if details2 is empty

  const lastItem = input.value.detailsCPO2[input.value.detailsCPO2.length - 1]; // Get the last item

  // Generate a new object dynamically
  let newItem2 = {
    no: lastItem.no + 1, // Increment no
  };

  // Dynamically add Time_x fields based on the last item's keys
  Object.keys(lastItem).forEach((key) => {
    if (key.startsWith("Time_")) {
      newItem2[key] = hours; // Assign default value
    }
  });

  // Push the new item to details2
  input.value.detailsCPO2.push(newItem2);
}

const removeItemCPO = (index: any) => {
  input.value.detailsCPO.splice(index, 1)
  input.value.detailsCPO2.splice(index, 1)
}

// const filteredList = computed(() => {
//   if (!filter.value) {
//     // return listVital.value
//   }

//   return listVital.value.filter((items: any) => {
//     return (
//       items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
//     )
//   })
// })

const add = () => {
  showFormInput.value = true
}

const addVitalSign = () => {
  input.value = {
    tanggal: new Date()
  }
  modalInput.value = true
}

const filteredListVitalSign = computed(() => {
  if (!filter.value) {
    return listVital.value
  }

  return listVital.value.filter((items: any) => {
    return (
      items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
    )
  })

})

// const loadRiwayat = () => {
//   isLoadingHolder.value = true
//   useApi().get(
//     `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=MonitoringICU&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
//       if (response.length) {
//         input.value = response[0] //set ke inputan
//         description.value = response[0].monitoringICU
//         medikasi.value = response[0].medikasi
//         dataSourceAlatInvasif.value = response[0].pemasanganAlat
//         tableBalansCairan.value = response[0].balansCairan
//         chartData.value = setChartData(listVital.value);
//         chartOptions.value = setChartOptions();
//         if (NOREC_EMRPASIEN.value == '') {
//           NOREC_EMRPASIEN.value = response[0].emrpasienfk
//         }
//       }
//     })
//   isLoadingHolder.value = false
// }
const loadRiwayat = async () => {
  isLoading.value = true;
  try {
    const response = await useApi().get(
      `/emr/get-emr-monitoring-icu-jadwal-terbuka?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=MonitoringICU&statusJadwal=Terbuka`
    );

    isLoading.value = false;

    if (response?.length) {
      input.value = {
        ...input.value,
        ...response[0],
        details: response[0].details || input.value.details,
        detailsCPO: response[0].detailsCPO || input.value.detailsCPO,
      };

      listVital.value = response;
      dataSourceAlatInvasif.value = response[0].pemasanganAlat || [];
      dataSourceMedikasi.value = response[0].medikasi || [];
      dataSourceIntake.value = response[0].balansCairanIntake || [];
      dataSourceOutput.value = response[0].balansCairanOutput || [];
      chartData.value = setChartData(listVital.value);
      chartOptions.value = setChartOptions();
      H.alert('success', 'Data berhasil diambil, Status Jadwal Terbuka');
    } else {
      H.alert('info', 'Tidak ada jadwal terbuka');
    }
  } catch (error) {
    isLoading.value = false;
    console.log(JSON.stringify(error));
    H.alert('error', 'Gagal memuat riwayat');
  }
};
const loadRiwayatTutupJadwal = async () => {
  isLoading.value = true;
  try {
    const response = await useApi().get(
      `/emr/get-emr-monitoring-icu-jadwal-tertutup?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=MonitoringICU&emrpasienfk=${NOREC_EMRPASIEN.value}&statusJadwal=Tertutup`
    );
    listTutupJadwal.value = response;
    isLoading.value = false;
  } catch (error) {
    isLoading.value = false;
    console.log(JSON.stringify(error));
    H.alert('error', 'Gagal memuat riwayat');
  }
};
const previewTutupJadwalClick = () => {
  previewTutupJadwal.value = true;
};

const onCellEditComplete = (event: any) => {
  let { data, newValue, field, newData } = event;
  console.log(event)
  if (newValue != undefined) {
    data[field] = newValue
    // console.log(event)
  }

}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  // object.monitoringICU = description.value
  // object.medikasi = medikasi.value
  // object.balansCairan = tableBalansCairan.value
  object.pemasanganAlat = dataSourceAlatInvasif.value
  object.medikasi = dataSourceMedikasi.value
  object.balansCairanIntake = dataSourceIntake.value
  object.balansCairanOutput = dataSourceOutput.value
  object.statusJadwal = "Terbuka"
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': "MonitoringICU",
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
      modalInput.value = false
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const tutupJadwal = async () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pemasanganAlat = dataSourceAlatInvasif.value
  object.medikasi = dataSourceMedikasi.value
  object.balansCairanIntake = dataSourceIntake.value
  object.balansCairanOutput = dataSourceOutput.value
  object.statusJadwal = "Tertutup"
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)

  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': "MonitoringICU",
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }

  isLoading.value = true
  try {
    const response = await useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
      modalInput.value = false
      // loadRiwayatTutupJadwal()
      loadRiwayat();
    });
    // isLoading.value = false;
    // NOREC_EMRPASIEN.value = response.norec_emr;
    // input.value.id = response.id;
    // modalInput.value = false;

    // resetInput();

    // await loadRiwayat();
  } catch (e) {
    isLoading.value = false;
  }
}


const addNewItem = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details[input.value.details.length - 1].no + 1
  }
  input.value.details.push(newItem);
}
const removeItem = (index: any) => {
  let urut = input.value.details.length - 1
  input.value.details.splice(urut, 1)
}
const addNewItemPar = () => {
  let newItem: any = {};

  let lastItem = input.value.detailss.length > 0 ? input.value.detailss[input.value.detailss.length - 1] : null;
  newItem.no = lastItem ? lastItem.no + 1 : 1;

  input.value.detailss.push(newItem);
};

const removeItemPar = (index: number) => {
  if (input.value.detailss.length > 0) {
    input.value.detailss.splice(index, 1);
  }
};

const addNewItemTransfusi = () => {
  const newRow: Record<string, string> = {};
  const rowIndex = input.value.detailsTransfusi.length + 1;
  const colCount = 5; // Adjust the number of columns as needed

  for (let colIndex = 1; colIndex <= colCount; colIndex++) {
    newRow[`transfusi${rowIndex}_${colIndex}`] = ""; // Unique key for each column
  }

  input.value.detailsTransfusi.push(newRow);
};


const removeItemTransfusi = (index: number) => {
  input.value.detailsTransfusi.splice(index, 1);
};

const addNewItemOut = () => {
  if (!input.value.detailout) {
    input.value.detailout = [];
  }

  let newItem: any = {};
  let lastItem = input.value.detailout.length > 0 ? input.value.detailout[input.value.detailout.length - 1] : null;
  newItem.no = lastItem ? lastItem.no + 1 : 1;

  input.value.detailout.push(newItem);
};


const removeItemOut = (index: number) => {
  if (!input.value.detailout || input.value.detailout.length === 0) return;

  input.value.detailout.splice(index, 1);
};


const addNewItem1 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details1[input.value.details1.length - 1].no + 1
  }
  input.value.details1.push(newItem);
}
const removeItem1 = (index: any) => {
  let urut = input.value.details1.length - 1
  input.value.details1.splice(urut, 1)
}

const addNewItem2 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details2[input.value.details2.length - 1].no + 1
  }
  input.value.details2.push(newItem);
}
const removeItem2 = (index: any) => {
  let urut = input.value.details2.length - 1
  input.value.details2.splice(urut, 1)
}

const addNewItem3 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details3[input.value.details3.length - 1].no + 1
  }
  input.value.details3.push(newItem);
}
const removeItem3 = (index: any) => {
  let urut = input.value.details3.length - 1
  input.value.details3.splice(urut, 1)
}

const addNewItem4 = () => {
  let newItem: any = {}
  newItem = {
    no: input.value.details4[input.value.details4.length - 1].no + 1
  }
  input.value.details4.push(newItem);
}
const removeItem4 = (index: any) => {
  let urut = input.value.details4.length - 1
  input.value.details4.splice(urut, 1)
}

const addNewItemVitalSign = () => {
  input.value.listVitalSignDetails.unshift({
    no: input.value.listVitalSignDetails[input.value.listVitalSignDetails.length - 1].no + 1,
    tgltindakan: new Date(),
  });
}
const removeItemVitalSign = (index: any) => {
  input.value.listVitalSignDetails.splice(index, 1)
}

const addNewSistemSaraf = () => {
  input.value.listSistemSaraf.unshift({
    no: input.value.listSistemSaraf[input.value.listSistemSaraf.length - 1].no + 1,
    id: uuidv4(),
  });
}
const removeSistemSaraf = (index: any) => {
  input.value.listSistemSaraf.splice(index, 1)
}

const addNewStatusRespirasi = () => {
  input.value.listStatusRespirasi.unshift({
    no: input.value.listStatusRespirasi[input.value.listStatusRespirasi.length - 1].no + 1,
    id: uuidv4(),
  });
}
const removeStatusRespirasi = (index: any) => {
  input.value.listStatusRespirasi.splice(index, 1)
}


const addtoSourceAlat = (e: any) => {
  if (!e.namaAlat) {
    return H.alert('error', 'Nama Alat Harus diisi')
  }
  if (!e.pasang) {
    return H.alert('error', 'Tanggal Pasang Harus diisi')
  }
  if (!e.cabut) {
    return H.alert('error', 'Tanggal Cabut Harus diisi')
  }
  if (!e.hariKe) {
    return H.alert('error', 'Hari Ke Harus diisi')
  }
  if (e.no) {
    dataSourceAlatInvasif.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.alatInvasif = e.namaAlat
        element.tglPasang = e.pasang
        element.tglCabut = e.cabut
        element.hari = e.hariKe
      }
    });
  } else {
    dataSourceAlatInvasif.value.push({
      no: dataSourceAlatInvasif.value.length + 1,
      alatInvasif: e.namaAlat,
      tglPasang: e.pasang,
      tglCabut: e.cabut,
      hari: e.hariKe
    })
  }
  formTambahAlat.value = false
  clear()
}

const addDataSourceOutput = (e: any) => {
  if (!Array.isArray(dataSourceOutput.value)) {
    dataSourceOutput.value = [];
  }
  if (e.no) {
    dataSourceOutput.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.namaOutput = e.namaOutput
        element.jenisOutput = e.jenisOutput
        element.mlOutput = e.mlOutput
        element.tanggalOutput = e.tanggalOutput
        element.totalOutput = e.totalOutput
      }
    });
  } else {
    dataSourceOutput.value.push({
      no: dataSourceOutput.value.length + 1,
      namaOutput: e.namaOutput,
      jenisOutput: e.jenisOutput,
      mlOutput: e.mlOutput,
      tanggalOutput: e.tanggalOutput,
      totalOutput: e.totalOutput
    })
  }
  formOutput.value = false
  clear()
}

const addtoSourceIntake = (e: any) => {
  if (!Array.isArray(dataSourceIntake.value)) {
    dataSourceIntake.value = [];
  }
  if (e.no) {
    dataSourceIntake.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.namaIntake = e.namaIntake
        element.jenisIntake = e.jenisIntake
        element.mlIntake = e.mlIntake
        element.tanggalIntake = e.tanggalIntake
        element.totalIntake = e.totalIntake
      }
    });
  } else {
    dataSourceIntake.value.push({
      no: dataSourceIntake.value.length + 1,
      namaIntake: e.namaIntake,
      jenisIntake: e.jenisIntake,
      mlIntake: e.mlIntake,
      tanggalIntake: e.tanggalIntake,
      totalIntake: e.totalIntake
    })
  }
  formIntake.value = false
  clear()
}

const addtoSourceMedikasi = (e: any) => {
  if (!e.namaObat) {
    return H.alert('error', 'Nama Obat Harus diisi')
  }
  if (!e.dosis) {
    return H.alert('error', 'Dosis Harus diisi')
  }
  if (!e.waktu) {
    return H.alert('error', 'Waktu Hari')
  }
  if (e.no) {
    dataSourceMedikasi.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.namaObat = e.namaObat
        element.dosis = e.dosis
        element.waktu = e.waktu
      }
    });
  } else {
    dataSourceMedikasi.value.push({
      no: dataSourceMedikasi.value.length + 1,
      namaObat: e.namaObat,
      dosis: e.dosis,
      waktu: e.waktu,
    })
  }
  formTambahMedikasi.value = false
  clear()
}

const editOutput = (e: any) => {
  showFormOutput(e)
  console.log(e)
}

const deleteOutput = (i: any) => {
  dataSourceOutput.value.splice(i, 1)
}

const editIntake = (e: any) => {
  showFormIntake(e)
  console.log(e)
}

const deleteIntake = (i: any) => {
  dataSourceIntake.value.splice(i, 1)
}

const editMedikasi = (e: any) => {
  showFormMedikasi(e)
  console.log(e)
}

const deleteMedikasi = (i: any) => {
  dataSourceMedikasi.value.splice(i, 1)
}

const editItemAlat = (e: any) => {
  showFormPemasanganAlat(e)
  console.log(e)
}

const deleteItemAlat = (i: any) => {
  dataSourceAlatInvasif.value.splice(i, 1)
}

const clear = () => {
  delete item.namaAlat
  delete item.tglPasang
  delete item.tglCabut
  delete item.hariKe
}

const fetchAlatBantu = () => {
  d_AlatBantu.value = [{ label: 'NC' }, { label: 'SM' }, { label: 'RM' }, { label: 'NRM' }, { label: 'HFNC' }, { label: 'Ventilator' }, { label: 'NGT' }]
}

const fetchCRT = () => {
  d_CRT.value = [{ label: "< 3" }, { label: "> 3" }]
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.norm = props.pasien.nocm
  input.value.namaPasien = props.pasien.namapasien
}

const showFormPemasanganAlat = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaAlat = e.alatInvasif ? e.alatInvasif : '',
    item.pasang = e.tglPasang ? e.tglPasang : '',
    item.cabut = e.tglCabut ? e.tglCabut : '',
    item.hariKe = e.hari ? e.hari : '',
    formTambahAlat.value = true
}

const showFormIntake = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaIntake = e.namaIntake ? e.namaIntake : '',
    item.jenisIntake = e.jenisIntake ? e.jenisIntake : '',
    item.mlIntake = e.mlIntake ? e.mlIntake : '',
    item.tanggalIntake = e.tanggalIntake ? e.tanggalIntake : '',
    item.totalIntake = e.totalIntake ? e.totalIntake : '',
    formIntake.value = true
}

const showFormOutput = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaOutput = e.namaOutput ? e.namaOutput : '',
    item.jenisOutput = e.jenisOutput ? e.jenisOutput : '',
    item.mlOutput = e.mlOutput ? e.mlOutput : '',
    item.tanggalOutput = e.tanggalOutput ? e.tanggalOutput : '',
    item.totalOutput = e.totalOutput ? e.totalOutput : '',
    formOutput.value = true
}

const showFormMedikasi = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaAlat = e.alatInvasif ? e.alatInvasif : '',
    item.pasang = e.tglPasang ? e.tglPasang : '',
    item.cabut = e.tglCabut ? e.tglCabut : '',
    item.hariKe = e.hari ? e.hari : '',
    formTambahMedikasi.value = true
}

const totalColumns = computed(() => {
  if (!input.value.detailsCPO2.length) return 3; // Default value if empty

  // Get count of "Time_x" fields from the first entry in details2
  const timeCount = input.value.detailsCPO2.reduce((max, item) => {
    const count = Object.keys(item).filter((key) => key.startsWith("Time_")).length;
    return Math.max(max, count); // Ensure it accounts for all items
  }, 0);

  return timeCount + 3; // Include static columns (e.g., "Jam", "Paraf 1", etc.)
});

const addNewDetail3 = () => {
  if (!input.value.detailsCPO2.length) return;

  // Find the latest Time_x index
  const latestIndex = Object.keys(input.value.detailsCPO2[0])
    .filter(key => key.startsWith("Time_"))
    .map(key => parseInt(key.split("_")[1]))
    .sort((a, b) => b - a)[0] || 0;

  // Add 7 new Time_x fields reactively
  input.value.detailsCPO2 = input.value.detailsCPO2.map(item => {
    let newItem = { ...item }; // Create a new reactive object
    for (let i = 1; i <= 7; i++) {
      newItem[`Time_${latestIndex + i}`] = hours; // Assign hours dynamically
    }
    return newItem;
  });

  // Update ArrayKu dynamically
  ArrayKu.value = Object.keys(input.value.detailsCPO2[0]).filter(k => k.startsWith("Time_"));

  console.log(input.value.detail2);
  // Push a new details3 entry
  input.value.detailsCPO3.push({ tanggalPengisian: "", hariKe: "" });
};

const ArrayKu = computed(() => {
  if (!input.value.detailsCPO2.length) return []; // If empty, return an empty array

  // Get the highest number of "Time_x" fields across all `details2` items
  const maxTimeCount = input.value.detailsCPO2.reduce((max, item) => {
    const count = Object.keys(item).filter((key) => key.startsWith("Time_")).length;
    return Math.max(max, count);
  }, 0);

  return Array(maxTimeCount).fill(null); // Return an array of that size
});


const removeDetail3 = () => {
  if (!input.value.details2.length || !input.value.details3.length) return;

  // Remove the latest entry from details3
  input.value.details3.pop();

  // Find the latest 7 Time_x indexes
  const timeKeys = Object.keys(input.value.details2[0])
    .filter(key => key.startsWith("Time_"))
    .map(key => parseInt(key.split("_")[1]))
    .sort((a, b) => b - a); // Sort descending

  if (timeKeys.length) {
    const latestIndexes = timeKeys.slice(0, 7); // Get the last 7 indexes

    // Remove the latest 7 Time_x fields reactively
    input.value.details2 = input.value.details2.map(item => {
      let newItem = { ...item };
      latestIndexes.forEach(index => delete newItem[`Time_${index}`]); // Remove each Time_x
      return newItem;
    });

    // Update ArrayKu dynamically
    ArrayKu.value = Object.keys(input.value.details2[0]).filter(k => k.startsWith("Time_"));
  }
};

const setChartData = (data: any) => {
  const documentStyle = getComputedStyle(document.documentElement);
  let labels = []
  let seriesNadi = []
  let seriesSuhu = []
  let seriesTekananDarah = []
  let seriesNafas = []
  let seriesSpO2 = []
  let seriesMap = []
  // console.log('QOI',data)

  for (var i = data.length - 1; i >= 0; i--) {
    const element = data[i]
    // console.log('QOI', element)
    for (var j = element.listVitalSignDetails.length - 1; j >= 0; j--) {
      const element2 = element.listVitalSignDetails[j]
      labels.push(H.formatDate(element2.tanggal, 'lll'))
      seriesNadi.push((element2.nadi ? parseFloat(element2.nadi) : 0))
      seriesSuhu.push((element2.suhu ? parseFloat(element2.suhu) : 0))
      seriesTekananDarah.push((element2.tekananDarah ? parseFloat(element2.tekananDarah) : 0))
      seriesNafas.push((element2.pernapasan ? parseFloat(element2.pernapasan) : 0))
      seriesSpO2.push((element2.SPO2 ? parseFloat(element2.SPO2) : 0))
      seriesMap.push((element2.MAP ? parseFloat(element2.MAP) : 0))
    }
  }

  return {
    labels: labels,
    datasets: [
      {
        label: 'Nadi',
        data: seriesNadi,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--blue-500')
      },
      {
        label: 'Suhu',
        data: seriesSuhu,
        fill: false,
        borderDash: [5, 5],
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--green-500')
      },
      {
        label: 'Tekanan Darah',
        data: seriesTekananDarah,
        fill: true,
        borderColor: documentStyle.getPropertyValue('--red-500'),
        tension: 0.4,
        backgroundColor: 'rgba(255,167,38,0.2)'
      },
      {
        label: 'Nafas',
        data: seriesNafas,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--black-500')
      },
      {
        label: 'SpO2',
        data: seriesSpO2,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--green-500')
      },
      {
        label: 'MAP',
        data: seriesMap,
        fill: false,
        tension: 0.4,
        borderColor: documentStyle.getPropertyValue('--red-100')
      }
    ]
  };
};

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue('--text-color');
  const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
  const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

  return {
    maintainAspectRatio: false,
    aspectRatio: 0.6,
    plugins: {
      legend: {
        labels: {
          color: textColor
        }
      }
    },
    scales: {
      x: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      },
      y: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      }
    }
  };
}

function getAlphabet(index) {
  return String.fromCharCode(65 + index);
}

watch(
  () => input.value.tglintravena,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglintravena, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi1 = calculateDays + 1
  }
)

watch(
  () => input.value.tglcvc,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglcvc, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi2 = calculateDays + 1
  }
)

watch(
  () => input.value.tglcath,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglcath, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi3 = calculateDays + 1
  }
)

watch(
  () => input.value.tglngt,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglngt, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi4 = calculateDays + 1
  }
)

watch(
  () => input.value.tgltrakeostomy,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tgltrakeostomy, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi5 = calculateDays + 1
  }
)

watch(
  () => input.value.tglett,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tglett, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi6 = calculateDays + 1
  }
)

watch(
  () => input.value.tgllainlain,
  (newValue, oldValue) => {
    var convertTgl = moment(input.value.tgllainlain, 'DD-MM-YYYY');
    var tgl_Sekarang = moment();
    const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
    input.value.kalkulasi7 = calculateDays + 1
  }
)

const rowHeights = ref<number[]>([]); // Store row heights
const rowHeights2 = ref<number[]>([]);
const observers: ResizeObserver[] = []; // Store ResizeObservers

const observeResize = () => {
  // Disconnect and clear previous observers
  observers.forEach(observer => observer.disconnect());
  observers.length = 0;

  nextTick(() => {
    input.value.detailsCPO.forEach((_, index) => {
      // First and Second Tables
      const leftRow = document.querySelector(`#left-row-${index}`);
      const rightRow = document.querySelector(`#right-row-${index}`);

      // Third and Fourth Tables
      const left2Row = document.querySelector(`#left2-row-${index}`);
      const right2Row = document.querySelector(`#right2-row-${index}`);

      if (leftRow && rightRow) {
        const observer = new ResizeObserver(() => syncRowHeights());
        observer.observe(leftRow);
        observer.observe(rightRow);
        observers.push(observer);
      }

      if (left2Row && right2Row) {
        const observer2 = new ResizeObserver(() => syncRowHeights2());
        observer2.observe(left2Row);
        observer2.observe(right2Row);
        observers.push(observer2);
      }
    });
  });
};

const syncRowHeights = () => {
  nextTick(() => {
    rowHeights.value = input.value.detailsCPO.map(() => 0); // Reset heights

    input.value.detailsCPO.forEach((_, index) => {
      const leftRow = document.querySelector(`#left-row-${index}`);
      const rightRow = document.querySelector(`#right-row-${index}`);

      if (leftRow && rightRow) {
        const maxHeight = Math.max(leftRow.clientHeight, rightRow.clientHeight);
        rowHeights.value[index] = maxHeight;
      }
    });
  });
};

const syncRowHeights2 = () => {
  nextTick(() => {
    if (!input.value?.detailsCPO?.length) {
      rowHeights2.value = [];
      return;
    }
    rowHeights2.value = input.value.detailsCPO.map(() => 0); // Reset heights

    input.value.detailsCPO.forEach((_, index) => {
      const left2Row = document.querySelector(`#left2-row-${index}`);
      const right2Row = document.querySelector(`#right2-row-${index}`);

      if (left2Row && right2Row) {
        const maxHeight = Math.max(left2Row.clientHeight, right2Row.clientHeight);
        rowHeights2.value[index] = maxHeight;
      }
    });
  });
};

// watch(
//   () => input.value.detailsCPO.length,
//   () => {
//     nextTick(() => {
//       observeResize();
//       syncRowHeights();
//     });
//   },
//   { deep: true }
// );

// watch(
//   () => input.value.detailsCPO3.length,
//   () => {
//     nextTick(() => {
//       observeResize();
//       syncRowHeights2();
//     });
//   },
//   { deep: true }
// );

onMounted(() => {
  observeResize();
  syncRowHeights();
  syncRowHeights2();
  loadRiwayat();
});

// Cleanup observers on unmount
onUnmounted(() => {
  observers.forEach(observer => observer.disconnect());
  observers.length = 0;
});

// watch(
//   () => input.value.details['TB_ORAL'],
//         input.value.details['TB_ENTERAL'],
//         input.value.details['JUMLAH31'],
//         input.value.details['I'],
//         input.value.details['II'],
//         input.value.details['III'],
//         input.value.details['IV'],
//         input.value.details['V'],
//         input.value.details['JUMLAH32'],
//         input.value.details['FREE'],
//   (newValue, oldValue) => {
//     data['JUMLAH2'] = parseFloat(input.value.details['TB_ORAL']) +
//         parseFloat(input.value.details['TB_ENTERAL']) +
//         parseFloat(input.value.details['JUMLAH31']) +
//         parseFloat(input.value.details['I']) +
//         parseFloat(input.value.details['II']) +
//         parseFloat(input.value.details['III']) +
//         parseFloat(input.value.details['IV']) +
//         parseFloat(input.value.details['V']) +
//         parseFloat(input.value.details['JUMLAH32']) +
//         parseFloat(input.value.details['FREE'])
//   })

watchEffect(() => {
  let total = 0;
  let totalintake = 0;
  let totalall = 0;
  let totalmakan = 0;
  let totaltransfusi = 0;
  if (!input.value?.details || !Array.isArray(input.value.details)) {
    return; // Exit early if details is not an array
  }
  input.value.details.forEach((a, index) => {
    let oral = parseFloat(a['TB_ORAL'] ?? 0)
    let enteral = parseFloat(a['TB_ENTERAL'] ?? 0)
    let jumlah31 = parseFloat(a['JUMLAH31'] ?? 0)
    let I = parseFloat(a['I'] ?? 0)
    let II = parseFloat(a['II'] ?? 0)
    let III = parseFloat(a['III'] ?? 0)
    let IV = parseFloat(a['IV'] ?? 0)
    let V = parseFloat(a['V'] ?? 0)
    let jumlah32 = parseFloat(a['JUMLAH32'] ?? 0)
    let free = parseFloat(a['FREE'] ?? 0)
    let jumlah2Total = 0
    if (input.value.detailout && Array.isArray(input.value.detailout)) {
      input.value.detailout.forEach((row, rowsIndex) => {
        const key = `JUMLAH2${index + 1}_${rowsIndex + 1}`
        const val = parseFloat(row[key] ?? 0)
        if (!isNaN(val)) {
          jumlah2Total += val
        }
      })
    }

    let parenteral1 = 0
    if (input.value.detailss && Array.isArray(input.value.detailss)) {
      input.value.detailss.forEach((ro, rowIndex) => {
        const key = `FREE${index + 1}_${rowIndex + 1}`
        const val = parseFloat(ro[key] ?? 0)
        if (!isNaN(val)) {
          parenteral1 += val
        }
      })
    }

    let parenteral2 = 0
    if (input.value.detailss && Array.isArray(input.value.detailss)) {
      input.value.detailss.forEach((ro, rowIndex) => {
        const key = `JUMLAH1${index + 1}_${rowIndex + 1}`
        const val = parseFloat(ro[key] ?? 0)
        if (!isNaN(val)) {
          parenteral2 += val
        }
      })
    }

    let totalTransfusiRow = 0
      if (input.value.detailsTransfusi && Array.isArray(input.value.detailsTransfusi)) {
        input.value.detailsTransfusi.forEach((col, colIndex) => {
          const key = `transfusi${index + 1}_${colIndex + 1}`
          const val = parseFloat(col[key] ?? 0)
          if (!isNaN(val)) {
            totalTransfusiRow += val
          }
        })
      }


    totalmakan = oral + enteral
    if (!isNaN(totalmakan)) {
        a['JUMLAH31'] = totalmakan
    } else {
        a['JUMLAH31'] = 0
    }

    totaltransfusi = I + II + III + IV + V + totalTransfusiRow
    if (!isNaN(totaltransfusi)) {
        a['JUMLAH32'] = totaltransfusi
    } else {
        a['JUMLAH32'] = 0
    }

    total = totalmakan + totaltransfusi
    if (!isNaN(total)) {
      a['JUMLAH1'] = total
      totalintake = total
    } else {
      a['JUMLAH1'] = 0
      totalintake = 0
    }

    totalall = (totalintake + parenteral1 + parenteral2) - jumlah2Total
    if (!isNaN(totalall)) {
      a['TB'] = totalall
    } else {
      a['TB'] = 0
    }



  });
});

setView()
setAutoFill()
if(props.dataTertutup?.length) {
  if (props.dataTertutup?.length) {
      input.value = {
        ...input.value,
        ...props.dataTertutup[0],
        details: props.dataTertutup[0].details || input.value.details,
        detailsCPO: props.dataTertutup[0].detailsCPO || input.value.detailsCPO,
      };

      listVital.value = props.dataTertutup;
      dataSourceAlatInvasif.value = props.dataTertutup[0].pemasanganAlat || [];
      dataSourceMedikasi.value = props.dataTertutup[0].medikasi || [];
      dataSourceIntake.value = props.dataTertutup[0].balansCairanIntake || [];
      dataSourceOutput.value = props.dataTertutup[0].balansCairanOutput || [];
      chartData.value = setChartData(listVital.value);
      chartOptions.value = setChartOptions();
      H.alert('success', 'Data berhasil diambil, Status Jadwal Tertutup');
    } else {
      H.alert('info', 'Data berhasil diambil, Status Jadwal Tertutup');
    }

} else {
  loadRiwayat()
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
// @import '/@src/scss/custom/timeline-css';
// @import '/@src/scss/custom/timeline-css';

// .p-column-header-content {
//   justify-content: space-evenly;
// }

// .p-fieldset-legend {
//   margin-left: 15px;
// }

// .p-rowgroup-header {
//   background: beige !important;
// }

.tablecpo {
  border-collapse: collapse;
}

.tablecpo td {
  border: 1px solid black !important;
  text-align: center !important;
  color: black !important;
}

.tablecpo th {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  color: black !important;
}

.table2 {
  border-collapse: collapse;
}

.table2 td {
  border: 1px solid black !important;
  text-align: center !important;
  color: black !important;
  vertical-align: middle !important;
}

.table2 th {
  text-align: center !important;
  vertical-align: middle !important;
  border: 1px solid black !important;
  color: black !important;
}

.label-icu {
  font-weight: 500;
}

.list-widget {
  @include vuero-l-card;

  &.is-straight {
    @include vuero-s-card;
  }

  ul {
    li {
      &:not(:last-child) {
        margin-bottom: 12px;
      }

      a {
        font-family: var(--font);
        display: flex;
        justify-content: space-between;
        color: var(--light-text);

        &:hover,
        &:focus {
          color: var(--primary);
        }
      }
    }
  }
}


.widget-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;

  .left {
    display: flex;
    align-items: center;
  }

  .center {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .right {
    display: flex;
    align-items: center;
    justify-content: flex-end;

    .tag {
      font-family: var(--font);
    }

    .right-icon {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 32px;
      width: 32px;
      min-width: 32px;
      border-radius: var(--radius-rounded);
      color: var(--light-text-light-12);
      transition: all 0.3s; // transition-all test

      &.has-indicator {
        &::after {
          content: '';
          position: absolute;
          top: 3px;
          right: 4px;
          height: 10px;
          width: 10px;
          border-radius: var(--radius-rounded);
          background: var(--secondary);
          border: 1.8px solid var(--white);
        }
      }

      svg {
        height: 18px;
        width: 18px;
        transition: stroke 0.3s;
      }
    }
  }

  h3 {
    font-family: var(--font-alt);
    font-size: 0.9rem;
    color: var(--dark-text);
    font-weight: 600;

    &.is-bigger {
      font-size: 1rem;
    }
  }

  .action-icon {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 32px;
    width: 32px;
    min-width: 32px;
    border-radius: var(--radius-rounded);
    color: var(--light-text-light-12);
    transition: all 0.3s; // transition-all test

    svg {
      height: 18px;
      width: 18px;
      transition: stroke 0.3s;
    }
  }
}

.is-dark {
  .widget-toolbar {
    h3 {
      color: var(--dark-dark-text);
    }

    .right {
      .right-icon {
        &.has-indicator {
          &::after {
            border-color: var(--dark-sidebar-light-6);
          }
        }
      }
    }
  }
}

small.is-tanggal {
  color: var(--light-text);
}

.is-dark-text {
  color: var(--light-text);
}

.fake-input {
  display: block;
  width: 100%;
  height: 2.5rem;
  border: 1px solid transparent;
  background: transparent;
}
</style>
