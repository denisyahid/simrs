<template>
  <ConfirmDialog group="templating">
    <template #message="slotProps">
      <div style="width:500px;height:300px;">
        <table style="width:100%;height:100%;border-collapse: collapse">
          <tr>
            <td style="text-align:center;vertical-align:middle">
              <i :class="slotProps.message.icon" style="font-size:125px;text-align:center;color:#FDDA0D"></i>
            </td>
          </tr>
          <tr>
            <td style="padding:7px;text-align:center">
              <p style="font-size:large">{{ slotProps.message.message }}</p>
            </td>
          </tr>
        </table>
      </div>
    </template>
  </ConfirmDialog>

  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Asesmen Awal Rawat Jalan Kesehatan Tradisional</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12" style="margin: 10px;">
          <div class="columns is-mobile is-centered">
            <div class="column is-auto" style="display: flex; gap: 5px;"> <!-- Flexbox with gap -->
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
                @click="pilihTemplateFix(index)">
                Pilih Template
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" :loading="isLoading"
                @click="pilihTemplate(index)">
                Pilih Riwayat
              </VButton>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12 mb-0">
          <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin
              membuat
              template</span></h1>
          <VField>
            <VControl>
              <VInput type="text" v-model="input.namatemplate">
              </VInput>
            </VControl>
          </VField>
        </div>

        <hr>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <h1 style="font-weight: bold;">Tanggal Kedatangan</h1>
              <VField>
                <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Jam Kedatangan</h1>
              <VField>
                <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:clock">
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-4">
              <h1 style="font-weight: bold;">Jam Asesmen Awal</h1>
              <VField>
                <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:clock">
                        <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
        </div>

        <hr>

        <div class="column is-12">
          <h1 style="font-size:larger;font-weight:bold">ALLOANAMNESIS</h1>
          <div class="column is-4 pl-0 pt-2">
            <VField class="is-autocomplete-select" v-slot="{ id }">
              <VControl>
                <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="d_allo" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </VControl>
            </VField>
          </div>
          <div class="column is-12" v-if="input.kebpilihanallo == 'Lainnya'">
            <VField>
              <VControl>
                <VTextarea v-model="input.keballoanamnesis" placeholder="Ketik Alloanamnesis Lainnya" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <hr>


        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12 mt-auto">
              <h1 class="mb-1" style="font-size:larger;font-weight:bold">ANAMNESIS
              </h1>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h1>Keluhan Utama</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.keluhanutama" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1>Riwayat penyakit sekarang</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatpenyakit" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1>Riwayat penyakit terdahulu</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatpenyakitdahulu" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1>Riwayat pengobatan</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatpengobatan" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1>Riwayat penyakit keluarga</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="7">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" v-if="input.isalergi == 'YA'">
                  <h1>Jenis alergi</h1>
                  <VField>
                    <VControl>
                      <VTextarea v-model="input.riwayatalergi" placeholder="Jelaskan..." rows="3">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>


        <hr>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12 pb-0">
              <h1 class="bold" style="font-size:larger;">
                Tanda Vital
              </h1>
              <br>
            </div>
            <div class="column is-3">
              <h1>Keadaan Umum</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <Multiselect v-model="input.keadaanumumobgyn" :attrs="{ value }" placeholder="--Pilih--" label="label"
                    :options="d_keadaanumum" :searchable="true" track-by="label" mode="single" autocomplete="off">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Tekanan Darah</h1>
              <VField addons>
                <VControl expanded>
                  <VInput class="input" placeholder="Tekanan Darah" v-model="input.tekananDarahObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>mmHg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Nadi</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="input.nadiObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/menit</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <h1>Respirasi</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="input.nafasObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>x/menit</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>Suhu</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="input.celciusObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>°C </VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>SaO2</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="" v-model="input.sao2Obgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>%</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>Berat Badan</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="Berat Badan" v-model="input.beratbadanObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>kg</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <h1>Tinggi Badan</h1>
              <VField addons>
                <VControl expanded>
                  <VInput type="number" class="input" placeholder="Tinggi Badan" v-model="input.tinggibadanObgyn" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>cm</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 pb-0 pt-0">
              <h1>GCS : </h1>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>E</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label" :options="d_gcse"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>V</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label" :options="d_gcsv"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-0">
              <VField addons>
                <VControl class="field-addon-body">
                  <VButton static>M</VButton>
                </VControl>
                <VControl expanded>
                  <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label" :options="d_gcsm"
                    :searchable="true" track-by="label" mode="single" autocomplete="off"
                    style="border-radius:0px 4px 4px 0px;height:100%">
                  </Multiselect>
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12 pb-0">
              <h1 class="bold" style="font-size:larger;">
                Status Generalis
              </h1>
              <br>
            </div>
            <div class="column is-3 pt-0" v-for="(item, index) in statusGeneralis" :key="index">
              <h1>{{ item.title }}</h1>
              <VField class="is-autocomplete-select">
                <VControl>
                  <VInput type="text" class="input" :placeholder="item.title" v-model="input[item.model]" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <div class="column is-12" align="center">
            <ImgDraw elemenID="Gambar" height="600" width="550"
              imageSrc="/images/simrs/asesmen-awal-rajal-kestrad.png" />
          </div>
          <h1 style="font-weight:bold">Hasil Pemeriksaan Fisik</h1>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea v-model="input.hasilpemeriksaan" placeholder="" rows="5">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <hr>

        <div class="column is-12">
          <h1 style="font-weight:bold">Hasil Pemeriksaan Penunjang</h1>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea v-model="input.hasilpemeriksaanpenunjang" placeholder="" rows="5">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <hr>
        <div class="column is-12">
          <h1 style="font-weight:bold">DIAGNOSIS</h1>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea v-model="input.DIAGNOSIS" placeholder="" rows="5">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <hr>
        <div class="column is-12">
          <div class="column is-12">
            <div style="overflow-y:auto;" class="mt-1">
              <table class="tabels" border="1" style="width: 100%;">
                <thead>
                  <tr>
                    <th class="th-pri" width="25%" style="vertical-align: inherit;text-align:center">
                      Daftar
                      Masalah</th>
                    <th class="th-pri" width="40%" style="vertical-align: inherit;text-align:center">
                      Rencana
                      Intervensi</th>
                    <th class="th-pri" width="25%" style="vertical-align: inherit;text-align:center">
                      Target
                    </th>
                    <th class="th-pri" style="vertical-align: inherit;text-align:center;" width="10%">
                      #
                    </th>
                  </tr>
                </thead>
                <tbody v-for="(input, index) in input.details" :key="index">
                  <tr>
                    <td class="td-pri">
                      <div class="pb-0">
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="input.daftarMasalah" placeholder="Daftar Masalah" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-pri">
                      <div class="pb-0">
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="input.rencanaIntervensi" placeholder="Rencana Intervensi" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-pri">
                      <div class="pb-0">
                        <VField>
                          <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="input.target" placeholder="Target" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-pri" style="vertical-align: inherit">
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
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <hr>
        <div class="column is-12">
          <h1 style="font-weight:bold">Intruksi</h1>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea v-model="input.Intruksi" placeholder="" rows="5">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <hr>
        <div class="column is-12" style="text-align: center;">
          <div class="column is-4 is-flex">
            <VField>
              <h1 style="font-weight: bold;" class="mr-3">Garut</h1>
            </VField>
            <VField>
              <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-4">
            <TandaTangan elemenID="TTDDokter" :width="'150'" :height="'150'" class="dek" />
          </div>
          <div class="column is-4 pt-0">
            <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_pegawai" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" />
          </div>

        </div>
        <!-- form baru -->

      </div>
    </div>
  </div>
  <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
    @close="showModalTemplate = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12 pt-0 pb-0">
          <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
          <div style="overflow-y:auto;" class="mt-1">
            <table class="tg table-tg" style="border-collapse: collapse; width: 100%;" v-if="listTemplate.length > 0">
              <thead>
                <tr>
                  <td class="tg-0lax text-center" style="width: 5%; padding: 9px;">#</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Tanggal Input</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Tanggal Registrasi</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">No Registrasi</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">No EMR</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Dokter</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Penyakit</td>
                  <td class="tg-0lax text-center" style="width: 15%; padding: 9px;">Section</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="resep in listTemplate" :key="resep.id">
                  <td style="width: 5%; text-align: center; padding: 4px;">
                    <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(resep)" color="info"
                      v-tooltip-prime.top="'Pilih'">
                    </VIconButton>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.created_at }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.registrasi.tglregistrasi }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.registrasi.noregistrasi }}</span>
                  </td>
                  <td style="width: 20%; text-align: center; padding: 9px;">
                    <span>{{ resep.pasien.nocm }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.dpjpUtama }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.riwayatpenyakit }}</span>
                  </td>
                  <td style="width: 15%; text-align: center; padding: 9px;">
                    <span>{{ resep.registrasi.namaruangan }}</span>
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
      }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="8"
        :loading="isLoading" paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
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
        <Column headerStyle="width: 8rem">
          <template #body="slotProps">
            <VButtons>
              <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)"
                v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Pilih'">
              </VIconButton>
              <VIconButton type="button" raised circle icon="fas fa-eye" @click="showTemplate(slotProps.data)"
                color="success" v-tooltip-prime.top="'Lihat'">
              </VIconButton>
            </VButtons>
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

  <VModal :open="showModalObat" title="Riwayat Obat" :noclose="true" size="large" actions="right"
    @close="showModalObat = false">
    <template #content>
      <DataTable :pt="{
        table: { style: 'min-width: 50rem; min-height: 10rem;' },
        column: {
          bodycell: ({ state }) => ({
            class: [{ 'pt-0 pb-0': state['d_editing'] }]
          })
        }
      }" v-model:selection="ObatSelected" :value="listSIMRSLama" :metaKeySelection="metaKey" :rows="10" paginator
        tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listSIMRSLama.length" responsiveLayout="stack"
        breakpoint="960px">
        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
        <Column field="namaobat" header="Nama" :sortable="true">
          <template #body="slotProps">
            <span>{{ slotProps.data.namaobat + ' - ' + slotProps.data.jenisobat }}</span>
          </template>
        </Column>
        <Column field="noorder" header="No Resep" :sortable="true"></Column>
        <Column field="noregistrasi" header="No Registrasi" :sortable="true"></Column>
        <Column field="namalengkap" header="Dokter" :sortable="true" style="width: 150px;;"></Column>
        <Column field="tglorder" header="Tanggal" :sortable="true">
          <template #body="slotProps">
            <span>{{ H.formatDateToLocalString(slotProps.data.tglorder) }}</span>
          </template>
        </Column>
      </DataTable>
    </template>
    <template #action>
      <VButton type="button" color="primary" raised @click="addToInput()">
        Tambah
      </VButton>
    </template>
  </VModal>

  <VModal :open="showModalDetailTemplate" title="Detail Template" :noclose="true" size="large" actions="right"
    @close="selectedTemplate = null; showModalDetailTemplate = false">
    <template #content>
      <DetailTemplate v-if="selectedTemplate" :input="selectedTemplate" :items="item" :kelompokUser="kelompokUser" />
      <VPlaceloadWrap v-else v-for="key in 6" :key="key">
        <VPlaceload width="100%" height="50px" class="mx-1 mt-2" />
      </VPlaceloadWrap>
    </template>
  </VModal>


</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import DetailTemplate from '../page-emr-plugins/showdetail/asesmen-keperawatan-rajal.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import moment from 'moment'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'


useHead({
  title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let isfromCPPT = useRoute().query.iscppt as boolean


let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let statusFungsional: any = ref(EMR.statusFungsional())
let statusGeneralis: any = ref(EMR.statusGeneralis())

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

const formName = ref(props.FORM_NAME);

const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const metaKey = ref(true);
const loadData: any = ref(true)
const listSIMRSLama: any = ref([])
const showModalObat: any = ref(false);
const ObatSelected: any = ref()
const isAlltemplate: any = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
    tanggal: new Date,
    jam: new Date
  },
  airway: [],
  disability: []

})

const COLLECTION: any = ref('AsesmenAwalRawatJalanKestrad') //table mongodb
const pegawaiId = useUserSession().getUser().pegawai.namaLengkap
const NOREC_EMRPASIEN: any = ref('')
const selectedTemplate: any = ref();
const input: any = ref({
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  kebtanggalKedatangan: new Date(),
  gcse: 4,
  gcsv: 5,
  gcsm: 6,
  kebrujukan: '',
  kebjamKedatangan: new Date(),
  kebjamAsesmenAwal: new Date(),
  kebtanggalKedatangan: new Date(),
  details: [{
    no: 1,
  }]
})
const addNewItem = () => {
  input.value.details.unshift({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])

const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const filterMenu: any = ref('')
const showModalDetailTemplate: any = ref(false);

const confirm = useConfirm();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])
const dataTTD: any = ref({})
// fungsi load riwayat
const loadRiwayat = async () => {
  isLoading.value = true;

  // Fetch fungsi emr data
  let responsex = await fetchEmrData();
  isLoading.value = false;

  // handle case ketika emr data ada
  if (responsex.length) {
    handleExistingEmr(responsex[0]);
  } else {
    await handleNoEmrFound();
  }

  // Load gambar
  await loadGambar("Gambar", input.value.Gambar);
};

// fungsi fetch emr berdasarkan collection
const fetchEmrData = async () => {
  return await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`);
};

// Handle ketika emr data ada
const handleExistingEmr = (emrData) => {
  input.value = emrData; // Set to input
  if (!NOREC_EMRPASIEN.value) {
    NOREC_EMRPASIEN.value = emrData.emrpasienfk;
  }
  dataTTD.value = emrData;
  H.tandaTangan().set("TTDDokter", dataTTD.value['TTDDokter']);
};

// Handle case ketika emr tidak ada
const handleNoEmrFound = async () => {
  isLoading.value = true;

  const responseTglRuangan = await fetchLastRoomData();
  const responseHistori = await fetchLastHistoryData();

  isLoading.value = false;

  if (responseTglRuangan.length && responseHistori.length) {
    await processRoomHistory(responseTglRuangan[0], responseHistori);
  } else {
    console.log('Data EMR sebelumnya tidak ada!');
  }

  console.log("Ruangan pasien sekarang : " + H.setObjectRegistrasi(pasien.value.registrasi).namaruangan);
};

// Fetch last room data emr
const fetchLastRoomData = async () => {
  return await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
};

// Fetch history data terakhir
const fetchLastHistoryData = async () => {
  return await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
};

// Proses room history
const processRoomHistory = async (lastRoomData, historyData) => {
  console.log("Ruangan dulu : " + lastRoomData.registrasi.namaruangan);

  const tgl_EMR_terakhir = moment(lastRoomData.created_at).format("DD-MM-YYYY");
  const convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
  const tgl_Sekarang = moment();
  const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');

  if (isSameRoomAndRecent(lastRoomData, calculateDays)) {
    await promptViewHistory(calculateDays, historyData);
  }
};

// cek ketikan ruangan sama dan record terakhir dari ruangan yang sama kurang dari 90 hari
const isSameRoomAndRecent = (lastRoomData, calculateDays) => {
  return (
    lastRoomData.registrasi.namaruangan.trim() === H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() &&
    calculateDays < 90
  );
};

// Prompt view history
const promptViewHistory = async (calculateDays, historyData) => {
  confirm.require({
    message: `Asesmen Keperawatan sudah pernah diinput ${calculateDays} hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya?`,
    group: 'templating',
    header: 'Informasi Asesmen Keperawatan',
    icon: 'pi pi-exclamation-circle',
    accept: () => {
      if (historyData.length) {
        handleExistingEmr(historyData[0]);
        input.value.namatemplate = '';
      } else {
        H.alert('warning', 'Data tidak ada');
      }
    },
    reject: () => {
      console.log('User rejected to view history.');
    }
  });
};


const loadGambar = async (element_id: string, value: string) => {
  let sigCanvas: any = document.getElementById('Gambar');
  console.log("SIG CANVAS", sigCanvas);
  console.log("SIG CANVAS", sigCanvas);
  if (sigCanvas) {
    let context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    let imagess = input.value.Gambar
    let background = new Image();
    background.src = imagess
    background.onload = function () {
      context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
    }
  }
}

const simpan = () => {

  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

  if (object.hasOwnProperty('namatemplate')) {
    delete object.namatemplate
  }
  object.pasien = H.setObjectPasien(pasien.value)
  object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
  object['Gambar'] = H.tandaTangan().get("Gambar");
  object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': formName.value,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  console.log(json)

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      // NOREC_EMRPASIEN.value = response.norec_emr
    }).catch((e: any) => {
      isLoading.value = false
    })

  // console.log(resultValue)
}


const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
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

const pilihTemplate = async (index: any) => {
  isLoading.value = true;
  try {
    const responselast = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`);
    if (responselast.length) {
      listTemplate.value = responselast; // Set ke inputan
      showModalTemplate.value = true;
    } else {
      H.alert('warning', 'Data tidak ada');
    }
  } catch (error) {
    console.error(error);
    H.alert('error', 'Terjadi kesalahan saat mengambil data');
  } finally {
    isLoading.value = false;
  }
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  try {
    const responselast = await useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}`);
    if (responselast.length) {
      responselast.forEach((item: any, index: number) => {
        item.no = index + 1;
      });
      listTemplateFix.value = responselast; // Set ke inputan
      showModalTemplateFix.value = true;
    } else {
      H.alert('warning', 'Data tidak ada');
    }
  } catch (error) {
    console.error(error);
    H.alert('error', 'Terjadi kesalahan saat mengambil data');
  } finally {
    isLoading.value = false;
  }
}

const showTemplate = async (d: any) => {
  selectedTemplate.value = d;
  console.log("FROM D", d);

  showModalDetailTemplate.value = true;
}

const kembaliKeun = () => {
  window.history.back()
}

const addToInput = (event) => {
  console.log("obat selected", ObatSelected)
  let inputss = input.value.riwayatobat == undefined ? '' : input.value.riwayatobat;
  if (ObatSelected.value.length > 0) {
    ObatSelected.value.forEach((obt, ind) => {
      inputss += ` # ${obt.namaobat} `
    })
  }
  input.value.riwayatobat = inputss
  showModalObat.value = false;
}



const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
  console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=100`
  ).then((response) => {
    d_pegawai.value = response
  })
}

const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}

const setAutoFill2 = async () => {
  const fieldsVitalSign = "tinggiBadan,IMT,lingkarPerut,tekananDarah,keadaanumumobgyn,keadaanumum,pernapasan,suhu,nadi,beratBadan,SPO2";
  const fieldsAsesmen = "tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,isalergi,beratbadanObgyn,tinggibadanObgyn,kebrujukan,kebrujuklanjutan";

  const fetchData = async (collection, fields) => {
    return await useApi().get(`emr/auto-fill?norec_pd=${props.registrasi.norec_pd}&collection=${collection}&field=${fields}`);
  };

  const parseResponse = (response) => {
    if (!response) return "";

    const fieldsMap = {
      celciusObgyn: "Suhu : ",
      nadiObgyn: "Nadi : ",
      nafasObgyn: "Pernafasan : ",
      tekananDarahObgyn: "Tekanan Darah : ",
      tinggibadanObgyn: "Tinggi Badan : ",
      beratbadanObgyn: "Berat Badan : ",
      sao2Obgyn: "SPO2 : ",
      IMT: "IMT : ",
      kebpilihanallo: "Kebutuhan Pilihan Allo : ",
      keluhanutama: "Keluhan Utama : ",
      riwayatpenyakit: "Riwayat Penyakit : ",
      riwayatpenyakitdahulu: "Riwayat Penyakit Dahulu : ",
      riwayatpengobatan: "Riwayat Pengobatan : ",
      riwayatpenyakitkeluarga: "Riwayat Penyakit Keluarga : ",
      riwayatalergi: "Riwayat Alergi : "
    };

    let data = "";
    Object.entries(fieldsMap).forEach(([key, label]) => {
      if (response[key]) data += `     ${label}${response[key]}\n`;
    });

    return data;
  };

  const setValues = (response) => {
    if (!response) return;

    input.value = {
      ...input.value,
      perawat: pegawaiId,
      tekananDarahObgyn: response.tekananDarahObgyn || response.tekananDarah,
      nadiObgyn: response.nadiObgyn || response.nadi,
      nafasObgyn: response.nafasObgyn || response.pernapasan,
      celciusObgyn: response.celciusObgyn || response.suhu,
      sao2Obgyn: response.sao2Obgyn || response.SPO2,
      gcse: response.gcse,
      gcsv: response.gcsv,
      gcsm: response.gcsm,
      keadaanumumobgyn: response.kebpilihanallo,

      keadaanumumobgyn: response.keadaanumumobgyn || response.keadaanumum,
      keluhanutama: response.keluhanutama || response.keluhanUtama,
      riwayatpenyakit: response.riwayatpenyakit || response.riwayatPenyakit,
      riwayatpenyakitdahulu: response.riwayatpenyakitdahulu || response.riwayatPenyakitDahulu,
      riwayatpengobatan: response.riwayatpengobatan || response.riwayatPengobatan,
      riwayatpenyakitkeluarga: response.riwayatpenyakitkeluarga || response.riwayatPenyakitKeluarga,
      riwayatalergi: response.riwayatalergi || response.riwayatAlergi,
      isalergi: response.isalergi ?? null,
      kebrujukan: response.kebrujukan ?? null,
      kebrujuklanjutan: response.kebrujuklanjutan ?? null,
      TBKetRujukanDari: response.kebrujuklanjutan ?? null,

      beratbadanObgyn: response.beratbadanObgyn || response.beratBadan,
      tinggibadanObgyn: response.tinggibadanObgyn || response.tinggiBadan,
      anamnesis: parseResponse(response),
    };
  };

  let response = await fetchData("VitalSign", fieldsVitalSign);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKebidananRawatJalanNurse", fieldsAsesmen);
  if (!response) response = await fetchData("AsesmenAwalKeperawatanPasienRawatJalan", fieldsAsesmen);

  setValues(response);
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

const addTemplate = (response) => {
  console.log(response);
  input.value = response;
  input.value.namatemplate = null;
  isAlltemplate.value = false;
  showModalTemplateFix.value = false;
  H.alert('success', 'Berhasil ditambahkan');
};

const handlerRujukanChange = (val: any) => {
  console.log(val);
  if (val === "YA") {

  }
}

const print = async () => {
  H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});


onMounted(() => {
  if (isfromCPPT) {
    H.alert('error', 'Silahkan isi Asesmen Keperawatan Terlebih Dahulu !');
    formName.value = 'Asesmen Awal Keperawatan Pasien Rawat Jalan'
  }
  // getDataExist()
  setAutoFill2()
  fetchPasien()
})

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.center {
  text-align: center;
}

.vm {
  vertical-align: middle;
}

.bold {
  font-weight: bold;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 0px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}

.tg2 {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg2 td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg2 th {
  // border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.fontcheckbox {
  padding: 0px;
  padding-top: 5px;
  padding-left: 5px;
}

.fontcheckbox label {
  color: black;
}


.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
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
  vertical-align: middle
}

mark {
  background-color: yellow;
  /* Pastikan warna yang Anda inginkan ditulis di sini */
  color: black;
  /* Warna teks jika perlu */
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
  /* Firefox */
}

.table.is-borderless th,
tr,
td {
  border: auto !important;
  background-color: auto !important;
}

.tg td {
  border-color: var(--fade-grey-dark-2) !important;
  border-style: solid !important;
  border-width: 1px !important;
  font-family: Arial, sans-serif !important;
  font-size: 14px !important;
  overflow: hidden !important;
  padding: 10px 5px !important;
  word-break: normal !important;
}

.is-autocomplete-select .multiselect .multiselect-search {
  padding-left: 20px !important;
}
</style>
