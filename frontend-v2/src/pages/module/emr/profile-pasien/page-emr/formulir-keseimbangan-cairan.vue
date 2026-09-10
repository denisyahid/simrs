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
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="column">
    <VCard>
      <div class="columns is-multiline column is-12 pb-0">
        <div class="column is-9">
          <VField label="Periode" style="margin-bottom: 6px;" />
          <VDatePicker v-model="item.qFilterTgl" is-range color="pink" locale="id" trim-weeks>
            <template #default="{ inputValue, inputEvents }">
              <VField addons>
                <VControl icon="feather:calendar">
                  <VInput :value="inputValue.start" v-on="inputEvents.start" />
                </VControl>
                <VControl>
                  <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                </VControl>
                <VControl icon="feather:calendar">
                  <VInput :value="inputValue.end" v-on="inputEvents.end" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>

        <div class="column is-3 is-flex is-align-items-center">
          <VButtons style="justify-content:space-around" v-if="filterData.length == 0">
            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem(input.details.length - 1)"
              color="info" v-tooltip.bubble="'Tambah '">
            </VIconButton>
          </VButtons>
        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column is-12">
          <h1 style="font-weight: bold;">Filter periode hanya berlaku pada baris pertama (dari baris 1–8 hingga baris terakhir).</h1>
        </div>
      </div>

      <div class="column" style="overflow-y: auto; max-height: 400px; border: 1px solid #ccc;">
        <table class="table-fkc" style="border-collapse: collapse; width: 100%;">
          <thead>
            <tr>
              <th class="th-fkc" rowspan="3" width="10%"
                style="position: sticky; top: 0; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Tanggal
              </th>
              <th class="th-fkc" rowspan="3" width="15%"
                style="position: sticky; top: 0; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Perawat
              </th>
              <th class="th-fkc" colspan="5"
                style="position: sticky; top: 0; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Intake
              </th>
              <th class="th-fkc" colspan="5" rowspan="2"
                style="position: sticky; top: 0; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Output
              </th>
              <th class="th-fkc" rowspan="3"
                style="position: sticky; top: 0; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">IWL</th>
              <th class="th-fkc" rowspan="3"
                style="position: sticky; top: 0; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Balans
              </th>
              <th class="th-fkc" rowspan="3"
                style="position: sticky; top: 0; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Produksi
                Urine</th>
              <th class="th-fkc" rowspan="3" width="5%"
                style="position: sticky; top: 0; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">#</th>
            </tr>
            <tr>
              <th class="th-fkc" colspan="2"
                style="position: sticky; top: 35px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Minum
              </th>
              <th class="th-fkc" colspan="3"
                style="position: sticky; top: 35px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">
                Parental</th>
            </tr>
            <tr>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Oral
              </th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">NGT
              </th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Infus
              </th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">
                Injeksi</th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">
                Transfusi</th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">BAK
              </th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">BAB
              </th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">NGT
              </th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Muntah
              </th>
              <th class="th-fkc"
                style="position: sticky; top: 71px; background: #fff; z-index: 2; border-bottom: 1px solid #ddd;">Drain
              </th>
            </tr>
          </thead>
          <tbody v-for="(item, index) in filterData" :key="index">
            <tr>
              <td class="td-fkc">
                <VDatePicker v-model="item['tglInput_1']" mode="dateTime" style="width: 100%" trim-weeks>
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="mb-0">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item['perawat_1']" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumOral_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumOral_1']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumNGT_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumNGT_1']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentakInfus_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentakInfus_1']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalInjek_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalInjek_1']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalTransf_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalTransf_1']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAK_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAK_1']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAB_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAB_1']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputNGT_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputNGT_1']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputMuntah_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputMuntah_1']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputDrain_1']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputDrain_1']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['iwl_1']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['balans_1']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['produksi_urine_1']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc" rowspan="9" style="vertical-align:inherit">
                <VButtons style="justify-content:space-around">
                  <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                    v-tooltip.bubble="'Tambah '">
                  </VIconButton>
                  <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                    @click="removeItem(index)" color="danger">
                  </VIconButton>
                </VButtons>
              </td>
            </tr>
            <tr>
              <td class="td-fkc">
                <VDatePicker v-model="item['tglInput_2']" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="mb-0">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item['perawat_2']" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumOral_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumOral_2']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumNGT_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumNGT_2']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentakInfus_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentakInfus_2']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalInjek_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalInjek_2']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalTransf_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalTransf_2']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAK_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAK_2']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAB_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAB_2']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputNGT_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputNGT_2']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputMuntah_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputMuntah_2']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputDrain_2']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputDrain_2']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['iwl_2']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['balans_2']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['produksi_urine_2']" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="td-fkc">
                <VDatePicker v-model="item['tglInput_3']" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="mb-0">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item['perawat_3']" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumOral_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumOral_3']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumNGT_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumNGT_3']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentakInfus_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentakInfus_3']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalInjek_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalInjek_3']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalTransf_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalTransf_3']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAK_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAK_3']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAB_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAB_3']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputNGT_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputNGT_3']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputMuntah_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputMuntah_3']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputDrain_3']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputDrain_3']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['iwl_3']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['balans_3']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['produksi_urine_3']" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="td-fkc">
                <VDatePicker v-model="item['tglInput_4']" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="mb-0">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item['perawat_4']" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumOral_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumOral_4']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumNGT_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumNGT_4']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentakInfus_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentakInfus_4']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalInjek_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalInjek_4']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalTransf_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalTransf_4']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAK_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAK_4']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAB_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAB_4']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputNGT_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputNGT_4']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputMuntah_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputMuntah_4']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputDrain_4']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputDrain_4']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['iwl_4']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['balans_4']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['produksi_urine_4']" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="td-fkc">
                <VDatePicker v-model="item['tglInput_5']" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="mb-0">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item['perawat_5']" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumOral_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumOral_5']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumNGT_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumNGT_5']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentakInfus_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentakInfus_5']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalInjek_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalInjek_5']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalTransf_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalTransf_5']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAK_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAK_5']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAB_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAB_5']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputNGT_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputNGT_5']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputMuntah_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputMuntah_5']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputDrain_5']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputDrain_5']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['iwl_5']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['balans_5']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['produksi_urine_5']" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="td-fkc">
                <VDatePicker v-model="item['tglInput_6']" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="mb-0">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item['perawat_6']" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumOral_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumOral_6']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumNGT_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumNGT_6']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentakInfus_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentakInfus_6']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalInjek_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalInjek_6']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalTransf_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalTransf_6']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAK_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAK_6']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAB_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAB_6']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputNGT_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputNGT_6']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputMuntah_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputMuntah_6']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputDrain_6']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputDrain_6']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['iwl_6']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['balans_6']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['produksi_urine_6']" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="td-fkc">
                <VDatePicker v-model="item['tglInput_7']" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="mb-0">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item['perawat_7']" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumOral_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumOral_7']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumNGT_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumNGT_7']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentakInfus_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentakInfus_7']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalInjek_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalInjek_7']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalTransf_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalTransf_7']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAK_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAK_7']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAB_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAB_7']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputNGT_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputNGT_7']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputMuntah_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputMuntah_7']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputDrain_7']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputDrain_7']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['iwl_7']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['balans_7']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['produksi_urine_7']" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="td-fkc">
                <VDatePicker v-model="item['tglInput_8']" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="mb-0">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item['perawat_8']" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumOral_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumOral_8']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['minumNGT_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_minumNGT_8']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentakInfus_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentakInfus_8']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalInjek_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalInjek_8']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['parentalTransf_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_parentalTransf_8']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAK_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAK_8']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputBAB_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputBAB_8']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputNGT_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputNGT_8']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputMuntah_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputMuntah_8']"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['outputDrain_8']" />
                  </VControl>
                  <VControl>
                    <VInput type="text" class="input" v-model="item['ket_outputDrain_8']" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['iwl_8']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['balans_8']" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item['produksi_urine_8']" />
                  </VControl>
                </VField>
              </td>
            </tr>

            <!-- column total -->
            <tr style="background-color: rgb(236, 233, 233);">
              <td class="td-fkc" style="text-align:center;vertical-align: inherit;">
                <span class="label-fkc">TOTAL</span>
              </td>
              <td class="td-fkc">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.perawatVerify" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari nama Perawat" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalMinumOral" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalMinumNGT" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalParentakInfus" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalParentalInjek" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalParentalTransf" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalOutputBAK" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalOutputBAB" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalOutputNGT" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalOutputMuntah" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalOutputDrain" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalIwl" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalBalans" />
                  </VControl>
                </VField>
              </td>
              <td class="td-fkc">
                <VField>
                  <VControl>
                    <VInput type="number" v-model="item.totalProduksiUrine" />
                  </VControl>
                </VField>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="column pb-0">
        <VCard class="p-2">
          <span class="label-fkc">BALANS / 24 jam = TOTAL INTAKE - TOTAL OUTPUT - TOTAL IWL</span>
        </VCard>
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';

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
const jumlahImdex = ref(8)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
  qFilterTgl: {
    start: new Date(new Date().setDate(new Date().getDate() - 3)),
    end: new Date()
  }
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{
    no: 1,
    tglInput: new Date
  }]
})
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const d_Diagnosa: any = ref([])
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const filterData = computed(() => {
  let start = new Date(item.qFilterTgl.start);
  start.setHours(0, 0, 0, 0);

  let end = new Date(item.qFilterTgl.end);
  end.setHours(23, 59, 59, 999);

  const filtered = input.value.details.filter((detail) => {
    const detailDate = new Date(detail.tglInput_1);
    return detailDate >= start && detailDate <= end;
  });

  return filtered;
});

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
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
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
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

const kembaliKeun = () => {
  window.history.back()
}

const addNewItem = () => {

  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
    tglInput_1: new Date(),
    // tglInput_2: new Date(),
    // tglInput_3: new Date(),
    // tglInput_4: new Date(),
    // tglInput_5: new Date(),
    // tglInput_6: new Date(),
    // tglInput_7: new Date(),
    // tglInput_8: new Date()
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const data = (e: any) => {
  let lenghtDay = [1, 2, 3, 4, 5, 6, 7, 8]

  lenghtDay.forEach((el: any, i) => {
    e.forEach((element: any) => {
      console.log(element)
    });

  });
  // console.log(jumlahImdex)
  // e.forEach((element:any,i:any) => {

  //   console.log(i)
  // });
}
const setSkor = (e: any, index: any) => {
  // if (input.value.totalSkor[index] < 0) {
  //   input.value.totalSkor[index] = 0
  // }
  input.value.details.forEach((element: any, i: any) => {
    debugger
    if (index == (index + i)) {
      element.totalMinumOral = parseFloat(e)
    } else {
      element.totalMinumOral = (element.totalMinumOral ? parseFloat(element.totalMinumOral) : 0) + parseFloat(e)
    }

    // console.log(element['minumOral_' +i.toString()])
  });

}

setView()
loadRiwayat()


watch(() => [
  input.value.details
], () => {

  input.value.details.forEach((element: any, e: any) => {

    let oral_1 = element.minumOral_1 ? parseInt(element.minumOral_1) : 0
    let oral_2 = element.minumOral_2 ? parseInt(element.minumOral_2) : 0
    let oral_3 = element.minumOral_3 ? parseInt(element.minumOral_3) : 0
    let oral_4 = element.minumOral_4 ? parseInt(element.minumOral_4) : 0
    let oral_5 = element.minumOral_5 ? parseInt(element.minumOral_5) : 0
    let oral_6 = element.minumOral_6 ? parseInt(element.minumOral_6) : 0
    let oral_7 = element.minumOral_7 ? parseInt(element.minumOral_7) : 0
    let oral_8 = element.minumOral_8 ? parseInt(element.minumOral_8) : 0

    let ngt_1 = element.minumNGT_1 ? parseInt(element.minumNGT_1) : 0
    let ngt_2 = element.minumNGT_2 ? parseInt(element.minumNGT_2) : 0
    let ngt_3 = element.minumNGT_3 ? parseInt(element.minumNGT_3) : 0
    let ngt_4 = element.minumNGT_4 ? parseInt(element.minumNGT_4) : 0
    let ngt_5 = element.minumNGT_5 ? parseInt(element.minumNGT_5) : 0
    let ngt_6 = element.minumNGT_6 ? parseInt(element.minumNGT_6) : 0
    let ngt_7 = element.minumNGT_7 ? parseInt(element.minumNGT_7) : 0
    let ngt_8 = element.minumNGT_8 ? parseInt(element.minumNGT_8) : 0

    let infus_1 = element.parentakInfus_1 ? parseInt(element.parentakInfus_1) : 0
    let infus_2 = element.parentakInfus_2 ? parseInt(element.parentakInfus_2) : 0
    let infus_3 = element.parentakInfus_3 ? parseInt(element.parentakInfus_3) : 0
    let infus_4 = element.parentakInfus_4 ? parseInt(element.parentakInfus_4) : 0
    let infus_5 = element.parentakInfus_5 ? parseInt(element.parentakInfus_5) : 0
    let infus_6 = element.parentakInfus_6 ? parseInt(element.parentakInfus_6) : 0
    let infus_7 = element.parentakInfus_7 ? parseInt(element.parentakInfus_7) : 0
    let infus_8 = element.parentakInfus_8 ? parseInt(element.parentakInfus_8) : 0

    let injek_1 = element.parentalInjek_1 ? parseInt(element.parentalInjek_1) : 0
    let injek_2 = element.parentalInjek_2 ? parseInt(element.parentalInjek_2) : 0
    let injek_3 = element.parentalInjek_3 ? parseInt(element.parentalInjek_3) : 0
    let injek_4 = element.parentalInjek_4 ? parseInt(element.parentalInjek_4) : 0
    let injek_5 = element.parentalInjek_5 ? parseInt(element.parentalInjek_5) : 0
    let injek_6 = element.parentalInjek_6 ? parseInt(element.parentalInjek_6) : 0
    let injek_7 = element.parentalInjek_7 ? parseInt(element.parentalInjek_7) : 0
    let injek_8 = element.parentalInjek_8 ? parseInt(element.parentalInjek_8) : 0

    let transf_1 = element.parentalTransf_1 ? parseInt(element.parentalTransf_1) : 0
    let transf_2 = element.parentalTransf_2 ? parseInt(element.parentalTransf_2) : 0
    let transf_3 = element.parentalTransf_3 ? parseInt(element.parentalTransf_3) : 0
    let transf_4 = element.parentalTransf_4 ? parseInt(element.parentalTransf_4) : 0
    let transf_5 = element.parentalTransf_5 ? parseInt(element.parentalTransf_5) : 0
    let transf_6 = element.parentalTransf_6 ? parseInt(element.parentalTransf_6) : 0
    let transf_7 = element.parentalTransf_7 ? parseInt(element.parentalTransf_7) : 0
    let transf_8 = element.parentalTransf_8 ? parseInt(element.parentalTransf_8) : 0

    let bak_1 = element.outputBAK_1 ? parseInt(element.outputBAK_1) : 0
    let bak_2 = element.outputBAK_2 ? parseInt(element.outputBAK_2) : 0
    let bak_3 = element.outputBAK_3 ? parseInt(element.outputBAK_3) : 0
    let bak_4 = element.outputBAK_4 ? parseInt(element.outputBAK_4) : 0
    let bak_5 = element.outputBAK_5 ? parseInt(element.outputBAK_5) : 0
    let bak_6 = element.outputBAK_6 ? parseInt(element.outputBAK_6) : 0
    let bak_7 = element.outputBAK_7 ? parseInt(element.outputBAK_7) : 0
    let bak_8 = element.outputBAK_8 ? parseInt(element.outputBAK_8) : 0

    let bab_1 = element.outputBAB_1 ? parseInt(element.outputBAB_1) : 0
    let bab_2 = element.outputBAB_2 ? parseInt(element.outputBAB_2) : 0
    let bab_3 = element.outputBAB_3 ? parseInt(element.outputBAB_3) : 0
    let bab_4 = element.outputBAB_4 ? parseInt(element.outputBAB_4) : 0
    let bab_5 = element.outputBAB_5 ? parseInt(element.outputBAB_5) : 0
    let bab_6 = element.outputBAB_6 ? parseInt(element.outputBAB_6) : 0
    let bab_7 = element.outputBAB_7 ? parseInt(element.outputBAB_7) : 0
    let bab_8 = element.outputBAB_8 ? parseInt(element.outputBAB_8) : 0

    let outNgt_1 = element.outputNGT_1 ? parseInt(element.outputNGT_1) : 0
    let outNgt_2 = element.outputNGT_2 ? parseInt(element.outputNGT_2) : 0
    let outNgt_3 = element.outputNGT_3 ? parseInt(element.outputNGT_3) : 0
    let outNgt_4 = element.outputNGT_4 ? parseInt(element.outputNGT_4) : 0
    let outNgt_5 = element.outputNGT_5 ? parseInt(element.outputNGT_5) : 0
    let outNgt_6 = element.outputNGT_6 ? parseInt(element.outputNGT_6) : 0
    let outNgt_7 = element.outputNGT_7 ? parseInt(element.outputNGT_7) : 0
    let outNgt_8 = element.outputNGT_8 ? parseInt(element.outputNGT_8) : 0

    let muntah_1 = element.outputMuntah_1 ? parseInt(element.outputMuntah_1) : 0
    let muntah_2 = element.outputMuntah_2 ? parseInt(element.outputMuntah_2) : 0
    let muntah_3 = element.outputMuntah_3 ? parseInt(element.outputMuntah_3) : 0
    let muntah_4 = element.outputMuntah_4 ? parseInt(element.outputMuntah_4) : 0
    let muntah_5 = element.outputMuntah_5 ? parseInt(element.outputMuntah_5) : 0
    let muntah_6 = element.outputMuntah_6 ? parseInt(element.outputMuntah_6) : 0
    let muntah_7 = element.outputMuntah_7 ? parseInt(element.outputMuntah_7) : 0
    let muntah_8 = element.outputMuntah_8 ? parseInt(element.outputMuntah_8) : 0

    let drain_1 = element.outputDrain_1 ? parseInt(element.outputDrain_1) : 0
    let drain_2 = element.outputDrain_2 ? parseInt(element.outputDrain_2) : 0
    let drain_3 = element.outputDrain_3 ? parseInt(element.outputDrain_3) : 0
    let drain_4 = element.outputDrain_4 ? parseInt(element.outputDrain_4) : 0
    let drain_5 = element.outputDrain_5 ? parseInt(element.outputDrain_5) : 0
    let drain_6 = element.outputDrain_6 ? parseInt(element.outputDrain_6) : 0
    let drain_7 = element.outputDrain_7 ? parseInt(element.outputDrain_7) : 0
    let drain_8 = element.outputDrain_8 ? parseInt(element.outputDrain_8) : 0

    let iwl_1 = element.iwl_1 ? parseInt(element.iwl_1) : 0
    let iwl_2 = element.iwl_2 ? parseInt(element.iwl_2) : 0
    let iwl_3 = element.iwl_3 ? parseInt(element.iwl_3) : 0
    let iwl_4 = element.iwl_4 ? parseInt(element.iwl_4) : 0
    let iwl_5 = element.iwl_5 ? parseInt(element.iwl_5) : 0
    let iwl_6 = element.iwl_6 ? parseInt(element.iwl_6) : 0
    let iwl_7 = element.iwl_7 ? parseInt(element.iwl_7) : 0
    let iwl_8 = element.iwl_8 ? parseInt(element.iwl_8) : 0

    let balans_1 = element.balans_1 ? parseInt(element.balans_1) : 0
    let balans_2 = element.balans_2 ? parseInt(element.balans_2) : 0
    let balans_3 = element.balans_3 ? parseInt(element.balans_3) : 0
    let balans_4 = element.balans_4 ? parseInt(element.balans_4) : 0
    let balans_5 = element.balans_5 ? parseInt(element.balans_5) : 0
    let balans_6 = element.balans_6 ? parseInt(element.balans_6) : 0
    let balans_7 = element.balans_7 ? parseInt(element.balans_7) : 0
    let balans_8 = element.balans_8 ? parseInt(element.balans_8) : 0


    let oraL = oral_1 + oral_2 + oral_3 + oral_4 + oral_5 + oral_6 + oral_7 + oral_8
    let ngT = ngt_1 + ngt_2 + ngt_3 + ngt_4 + ngt_5 + ngt_6 + ngt_7 + ngt_8
    let infuS = infus_1 + infus_2 + infus_3 + infus_4 + infus_5 + infus_6 + infus_7 + infus_8
    let injeK = injek_1 + injek_2 + injek_3 + injek_4 + injek_5 + injek_6 + injek_7 + injek_8
    let transF = transf_1 + transf_2 + transf_3 + transf_4 + transf_5 + transf_6 + transf_7 + transf_8
    let baK = bak_1 + bak_2 + bak_3 + bak_4 + bak_5 + bak_6 + bak_7 + bak_8
    let baB = bab_1 + bab_2 + bab_3 + bab_4 + bab_5 + bab_6 + bab_7 + bab_8
    let outNGT = outNgt_1 + outNgt_2 + outNgt_3 + outNgt_4 + outNgt_5 + outNgt_6 + outNgt_7 + outNgt_8
    let outMuntah = muntah_1 + muntah_2 + muntah_3 + muntah_4 + muntah_5 + muntah_6 + muntah_7 + muntah_8
    let draiN = drain_1 + drain_2 + drain_3 + drain_4 + drain_5 + drain_6 + drain_7 + drain_8
    let iWL = iwl_1 + iwl_2 + iwl_3 + iwl_4 + iwl_5 + iwl_6 + iwl_7 + iwl_8
    let balanS = balans_1 + balans_2 + balans_3 + balans_4 + balans_5 + balans_6 + balans_7 + balans_8

    let balans1 = (oral_1 + ngt_1 + infus_1 + injek_1 + transf_1) - (bak_1 + bab_1 + outNgt_1 + muntah_1 + drain_1) - iwl_1
    let balans2 = (oral_2 + ngt_2 + infus_2 + injek_2 + transf_2) - (bak_2 + bab_2 + outNgt_2 + muntah_2 + drain_2) - iwl_2
    let balans3 = (oral_3 + ngt_3 + infus_3 + injek_3 + transf_3) - (bak_3 + bab_3 + outNgt_3 + muntah_3 + drain_3) - iwl_3
    let balans4 = (oral_4 + ngt_4 + infus_4 + injek_4 + transf_4) - (bak_4 + bab_4 + outNgt_4 + muntah_4 + drain_4) - iwl_4
    let balans5 = (oral_5 + ngt_5 + infus_5 + injek_5 + transf_5) - (bak_5 + bab_5 + outNgt_5 + muntah_5 + drain_5) - iwl_5
    let balans6 = (oral_6 + ngt_6 + infus_6 + injek_6 + transf_6) - (bak_6 + bab_6 + outNgt_6 + muntah_6 + drain_6) - iwl_6
    let balans7 = (oral_7 + ngt_7 + infus_7 + injek_7 + transf_7) - (bak_7 + bab_7 + outNgt_7 + muntah_7 + drain_7) - iwl_7
    let balans8 = (oral_8 + ngt_8 + infus_8 + injek_8 + transf_8) - (bak_8 + bab_8 + outNgt_8 + muntah_8 + drain_8) - iwl_8

    // if (element['minumOral_' + i.toString()] != undefined) {
    //   let tambah = element['minumOral_' + i.toString()] ? parseInt(element['minumOral_' + i.toString()]) : 0
    // console.log(cek)
    element.totalMinumOral = oraL ? oraL : ''
    element.totalMinumNGT = ngT ? ngT : ''
    element.totalParentakInfus = infuS ? infuS : ''
    element.totalParentalInjek = injeK ? injeK : ''
    element.totalParentalTransf = transF ? transF : ''
    element.totalOutputBAK = baK ? baK : ''
    element.totalOutputBAB = baB ? baB : ''
    element.totalOutputNGT = outNGT ? outNGT : ''
    element.totalOutputMuntah = outMuntah ? outMuntah : ''
    element.totalOutputDrain = draiN ? draiN : ''
    element.totalIwl = iWL ? iWL : ''
    element.totalBalans = balanS ? balanS : ''
    element.balans_1 = balans1 ? balans1 : ''
    element.balans_2 = balans2 ? balans2 : ''
    element.balans_3 = balans3 ? balans3 : ''
    element.balans_4 = balans4 ? balans4 : ''
    element.balans_5 = balans5 ? balans5 : ''
    element.balans_6 = balans6 ? balans6 : ''
    element.balans_7 = balans7 ? balans7 : ''
    element.balans_8 = balans8 ? balans8 : ''
  })
  //   input.value.details.forEach((element:any,i:any,e:any) => {

  //     console.log(element['minumOral_' + e.toString()])
  // });
  // data(input.value.details.length)
},
  { deep: true }
)


</script>

<style lang="scss">
.label-fkc {
  font-weight: 500;
}

.table-fkc {
  border: 1px solid black;
  width: 210% !important
}

.th-fkc {
  text-align: center !important;
  vertical-align: inherit !important;
}

.th-fkc,
.td-fkc {
  border: 1px solid black;
  padding: 7px;
  vertical-align: middle !important;
}
</style>
