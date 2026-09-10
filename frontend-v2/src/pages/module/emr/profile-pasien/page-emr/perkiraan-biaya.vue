<style lang="scss">
h1 {
  font-weight: bold !important;
}

label {
  color: black !important;
}
</style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px; padding-bottom: 15px;">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" @simpanTemplate="simpanTemplate"></ButtonEmr>
          </div>
        </div>
      </div>

      <div class="columns is-multiline column">
        <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
            <VButton type="button" rounded outlined color="warning" raised icon="feather:file-text"
                isLoading="false" @click="enableInput()"> Enable Input
            </VButton>
        </div>
        <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-9 pt-0">
          <h1>Nama Template&emsp;&emsp;<span style="color: rgb(230, 41, 100);">**Hanya diisi jika ingin
              membuat template</span></h1>
          <VField>
            <VControl>
              <VInput type="text" class="input" v-model="input.namatemplate" :disabled="isDisabled" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3 mt-auto">
          <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
            :loading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
          </VButton>
        </div>
        <div class="column is-12" style="font-weight: bold;">
            <VField horizontal>
              <VControl raw subcontrol class="mr-4">
                <VCheckbox class="p-0" style="color:green !important" square true-value="Disetujui"
                  label="Disetujui" v-model="input.Parameter_Estimasi" :disabled="isDisabled" />
              </VControl>
              <VControl raw subcontrol class="mr-4">
                <VCheckbox class="p-0" style="color:red !important" square true-value="Ditolak"
                  label="Ditolak" v-model="input.Parameter_Estimasi" />
              </VControl>
            </VField>
          </div>
        <div class="column is-4">
          <div class="columns is-12 is-multiline">
            <div class="is-3" style="margin-top: 10px;margin-left: 10px;">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" @click="DiagnosaAsmed()">
                Diagnosa ASMED
            </VButton>
            </div>
            <div class="is-3" style="margin-left: 10px;margin-top: 10px;">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" @click="DiagnosaCPPT()">
                Diagnosa CPPT
            </VButton>
            </div>
        </div>
          <h2>Diagnosis / <i>Diagnose</i></h2>
          <VField>
            <VTextarea rows="2" v-model="input.TADiagnosa" :disabled="isDisabled"></VTextarea>
          </VField>
        </div>
        <div class="column is-4 is-flex" style="align-items: center;">
          <VControl raw subcontrol>
            <VCheckbox class="p-0" color="primary" square true-value="Subsidi" label="Subsidi / Subsidy"
              v-model="input.CBSubsidi" circle :disabled="isDisabled"/>
          </VControl>
          <VControl raw subcontrol>
            <VCheckbox class="p-0" color="primary" square true-value="Non Subsidi" label="Non Subsidi / Non Subsidy "
              v-model="input.CBSubsidi" circle :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-4">
          <h2>Rencana Perawatan / <i>Care of Plan</i></h2>
          <VField>
            <VTextarea rows="2" v-model="input.TARencanaPerawatan" :disabled="isDisabled"></VTextarea>
          </VField>
        </div>
        <div class="column is-8 pt-0">
          <div class="columns is-multiline">
            <div class="column is-11 pt-5">
              <h2>Kode Tarif</h2>
            </div>
            <div class="column is-1 pt-4">
              <VIconButton type="button" raised circle icon="fas fa-file-medical-alt" @click="kodetarif()"
                color="success" v-tooltip-prime.top="'Kode Tarif'">
              </VIconButton>
            </div>
          </div>
          <VControl>
            <VTextarea rows="5" v-model="input.TBKodeTarif" :disabled="isDisabled"></VTextarea>
          </VControl>
          <h2>Total Tarif</h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TBTotalTarif" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-4 pt-0">
          <h2>Pekiraan Hari Rawat</h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_PHR" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-4">
          <h2>Nama Dokter Operator / Dokter yang merawat <br><i>Doctor's Name</i></h2>
          <VControl class="prime-auto">
            <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-4 pt-0">
          <h2>&nbsp;<br>Spesialisasi / Specialization</h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_Spesialisasi" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-4">
          <h2>Pilihan Ruang Perawatan<br><i>Patient Choice Of Ward Class</i></h2>
          <VControl class="prime-auto">
            <AutoComplete v-model="input.DDRuangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <h2>Biaya Harian / <i>Daily Changes</i></h2>
        </div>
        <div class="column is-6">
          <h2>Ruang Perawatan / <i>Ward Class</i></h2>
          <VControl class="prime-auto">
            <AutoComplete v-model="input.DDRuangPerawatan" :suggestions="d_RuangPerawatan" :optionLabel="'namakelas'"
              :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namakelas'"
              @complete="fetchRP($event)" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6">
          <h2>Tarif : <br>{{ input.tarifRuangPerawatan ? input.tarifRuangPerawatan : '-' }}</h2>
        </div>
        <div class="column is-6 pt-0">
          <h2>Biaya Perawatan / <i>Nursing Charges</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_BiayaPerawatan" :disabled="isDisabled" />
          </VControl>
        </div>
        <div class="column is-6 pt-0">
          <!-- <h2>Tarif : <br>{{ input.perkiraanBiaya ? input.perkiraanBiaya : '-' }}</h2> -->
          <h2>Tarif : </h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.perkiraanBiaya" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <h2>Perkiraan Biaya-Biaya / <i>Estimated Charges</i></h2>
        </div>
        <div class="column is-6">
          <h2>Visit Dokter /<br> <i>Doctor Visite</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_VisitDokter" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6">
          <h2>Penunjang Diagnosa, Obat-obatan & lainnya /<br> <i>Diagnostic Investigation, Medication, Other
              Treatment</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_PenunjanngDiagnosa" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6">
          <h2>Tindakan Bedah<br> <i>Surgical Procedure Charges</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_TindakanBedah" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6 pt-0">
          <h2>Alat Kesehatan /<br> <i>Consumables / Implants / Prosthetic Devices / Graft</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_AlatKesehatan" :disabled="isDisabled" />
          </VControl>
        </div>
        <div class="column is-6"></div>
        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-6 pt-0">
          <h2>Perkiraan Total Biaya /<br> <i>Estimated Total Cost</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_PTB" :disabled="isDisabled" />
          </VControl>
        </div>
        <div class="column is-6  pt-0">
          <h2>Dana Titipan /<br> <i>Deposite</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_Deposite" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="columns is-multiline column is-12">
          <div class="column is-4" style="text-align: center !important;">
            <p style="font-weight: bold;">Petugas Admission<br><i>Admission Staff</i></p>
            <TandaTangan :elemenID="'TTDAdmission'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                  <AutoComplete v-model="input.petugasAddmision" :suggestions="d_Petugas" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas..." :disabled="isDisabled"/>
              </VControl>
            <h1>Tgl (Dates)</h1>
            <div class="is-flex" style="justify-content: center;">
              <VDatePicker v-model="input.DTanggalAdmission" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled"/>
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
          <div class="column is-4" style="text-align: center !important;">
            <p style="font-weight: bold;">Dokter yang Meminta<br><i>Doctor</i></p>
            <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                  <AutoComplete v-model="input.DokterMeminta" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari DPJP..." :disabled="isDisabled"/>
              </VControl>
            <h1>Tgl (Dates)</h1>
            <div class="is-flex" style="justify-content: center;">
              <VDatePicker v-model="input.DTanggalDokter" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }" :disabled="isDisabled">
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled"/>
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
          <div class="column is-4" style="text-align: center !important;">
            <p style="font-weight: bold;">Pasien / Keluarga<br><i>Patient / Family</i></p>
            <TandaTangan :elemenID="'TTDPasien'" :width="'150'" :height="'150'" class="dek" />
            <VControl>
              <VInput type="text" class="input" v-model="input.TB_Namakeluarga" :disabled="isDisabled"/>
            </VControl>
            <h1>Tgl (Dates)</h1>
            <div class="is-flex" style="justify-content: center;">
              <VDatePicker v-model="input.DTanggalPasien" mode="date" trim-weeks :disabled="isDisabled">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled"/>
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
        </div>
        <div class="column is-12" style="padding-bottom: 30px;">
          <VField>
            <VControl>
              <VCheckbox color="primary" true-value="Ya" v-model="input.CBEstmasiLanjutan" label="Estimasi Lanjutan" :disabled="isDisabled"/>
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline column" v-if="input.CBEstmasiLanjutan == 'Ya'">
        <div class="column is-4">
          <h2>Diagnosis / <i>Diagnose</i></h2>
          <VField>
            <VTextarea rows="2" v-model="input.TADiagnosa2" :disabled="isDisabled"></VTextarea>
          </VField>
        </div>
        <div class="column is-4 is-flex" style="align-items: center;">
          <VControl raw subcontrol>
            <VCheckbox class="p-0" color="primary" square true-value="Subsidi" label="Subsidi / Subsidy"
              v-model="input.CBSubsidi2" circle :disabled="isDisabled"/>
          </VControl>
          <VControl raw subcontrol>
            <VCheckbox class="p-0" color="primary" square true-value="Non Subsidi" label="Non Subsidi / Non Subsidy "
              v-model="input.CBSubsidi2" circle :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-4">
          <h2>Rencana Perawatan / <i>Care of Plan</i></h2>
          <VField>
            <VTextarea rows="2" v-model="input.TARencanaPerawatan2" :disabled="isDisabled"></VTextarea>
          </VField>
        </div>
        <div class="column is-8 pt-0">
          <div class="columns is-multiline">
            <div class="column is-11 pt-5">
              <h2>Kode Tarif</h2>
            </div>
            <div class="column is-1 pt-4">
              <VIconButton type="button" raised circle icon="fas fa-file-medical-alt" @click="kodetarif2()"
                color="success" v-tooltip-prime.top="'Kode Tarif'" :disabled="isDisabled">
              </VIconButton>
            </div>
          </div>
          <VControl>
            <VTextarea rows="5" v-model="input.TBKodeTarif2" :disabled="isDisabled"></VTextarea>
          </VControl>
          <h2>Total Tarif</h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TBTotalTarif2" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-4 pt-0">
          <h2>Pekiraan Hari Rawat</h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_PHR2" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-4">
          <h2>Nama Dokter Operator / Dokter yang merawat <br><i>Doctor's Name</i></h2>
          <VControl class="prime-auto">
            <AutoComplete v-model="input.DDDokter2" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-4 pt-0">
          <h2>&nbsp;<br>Spesialisasi / Specialization</h2>
          <VControl>
            <VInput :disabled="isDisabled" type="text" class="input" v-model="input.TB_Spesialisasi2" />
          </VControl>
        </div>
        <div class="column is-4">
          <h2>Pilihan Ruang Perawatan<br><i>Patient Choice Of Ward Class</i></h2>
          <VControl class="prime-auto">
            <AutoComplete v-model="input.DDRuangan2" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <h2>Biaya Harian / <i>Daily Changes</i></h2>
        </div>
        <div class="column is-6">
          <h2>Ruang Perawatan / <i>Ward Class</i></h2>
          <VControl class="prime-auto">
            <AutoComplete v-model="input.DDRuangPerawatan2" :suggestions="d_RuangPerawatan" :optionLabel="'namakelas'"
              :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namakelas'"
              @complete="fetchRP($event)" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6"></div>
        <div class="column is-6 pt-0">
          <h2>Biaya Perawatan / <i>Nursing Charges</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_BiayaPerawatan2" :disabled="isDisabled" />
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <h2>Perkiraan Biaya-Biaya / <i>Estimated Charges</i></h2>
        </div>
        <div class="column is-6">
          <h2>Visit Dokter /<br> <i>Doctor Visite</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_VisitDokter2" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6">
          <h2>Penunjang Diagnosa, Obat-obatan & lainnya /<br> <i>Diagnostic Investigation, Medication, Other
              Treatment</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_PenunjanngDiagnosa2" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6">
          <h2>Tindakan Bedah<br> <i>Surgical Procedure Charges</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_TindakanBedah2" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6 pt-0">
          <h2>Alat Kesehatan /<br> <i>Consumables / Implants / Prosthetic Devices / Graft</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_AlatKesehatan2" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6"></div>
        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-6 pt-0">
          <h2>Perkiraan Total Biaya /<br> <i>Estimated Total Cost</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_PTB2" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-6  pt-0">
          <h2>Dana Titipan /<br> <i>Deposite</i></h2>
          <VControl>
            <VInput type="text" class="input" v-model="input.TB_Deposite2" :disabled="isDisabled"/>
          </VControl>
        </div>
        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="columns is-multiline column is-12">
          <div class="column is-4" style="text-align: center !important;">
            <p style="font-weight: bold;">Petugas Admission<br><i>Admission Staff</i></p>
            <TandaTangan :elemenID="'TTDAdmission2'" :width="'150'" :height="'150'" class="dek" />
            <VControl class="prime-auto">
                  <AutoComplete v-model="input.petugasAddmision2" :suggestions="d_Petugas" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas..." :disabled="isDisabled"/>
              </VControl>
            <h1>Tgl (Dates)</h1>
            <div class="is-flex" style="justify-content: center;">
              <VDatePicker v-model="input.DTanggalAdmission2" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled"/>
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
          <div class="column is-4" style="text-align: center !important;">
            <p style="font-weight: bold;">Dokter yang Meminta<br><i>Doctor</i></p>
            <TandaTangan :elemenID="'TTDDokter2'" :width="'150'" :height="'150'" class="dek" />
            <VControl class="prime-auto">
                  <AutoComplete v-model="input.DokterMeminta2" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari DPJP..." :disabled="isDisabled"/>
              </VControl>
            <h1>Tgl (Dates)</h1>
            <div class="is-flex" style="justify-content: center;">
              <VDatePicker v-model="input.DTanggalDokter2" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled"/>
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
          <div class="column is-4" style="text-align: center !important;">
            <p style="font-weight: bold;">Pasien / Keluarga<br><i>Patient / Family</i></p>
            <TandaTangan :elemenID="'TTDPasien2'" :width="'150'" :height="'150'" class="dek" />
            <VControl>
              <VInput type="text" class="input" v-model="input.TB_Namakeluarga2" :disabled="isDisabled"/>
            </VControl>
            <h1>Tgl (Dates)</h1>
            <div class="is-flex" style="justify-content: center;">
              <VDatePicker v-model="input.DTanggalPasien2" mode="date" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue" v-on="inputEvents" :disabled="isDisabled"/>
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <Dialog v-model:visible="modalInput" modal header="Tindakan" :style="{ width: '85rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <div class="columns is-multiline">
      <div class="column is-6">
        <VCard>
          <DataTable v-model:selection="selectedProduct" v-model:filters="filtersTindakan" :loading="isLoadingTindakan"
            :rows="10" paginator :value="products" selectionMode="multiple" :metaKeySelection="metaKey" dataKey="id"
            @rowSelect="onTindakanSelected" @rowUnselect="onTindakanUnselected"
            :globalFilterFields="['namaproduk', 'id']" tableStyle="min-width: 50rem">
            <template #header>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <InputText v-model="filtersTindakan['global'].value" placeholder="Search Data" />
                  </VField>
                </div>
                <div class="column is-6" style="display: none !important">
                  <VField>
                    <VControl>
                      <VSwitchBlock v-model="isAllTindakan" @change.stop="isAllTindakanChange(isAllTindakan)"
                        color="success" label="Semua Tindakan" />
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
            <Column field="id" header="ID"></Column>
            <Column field="namaproduk" header="Nama"></Column>
          </DataTable>
        </VCard>
      </div>
      <div class="column is-6">
        <span style="font-weight: bold">List Tindakan Dipilih</span>
        <div class="timeline-wrapper" v-if="dataSelectedTindakan.length > 0" style="margin-top: 20px;">
          <div class="timeline-header"></div>
          <div class="timeline-wrapper-inner pt-0">
            <div class="timeline-container">
              <div class="timeline-item is-unread">
                <VCard radius="rounded" class="p-3 mb-3">
                  <div v-for="(items, index) in dataSelectedTindakan" :key="items.id">
                    <div class="flex items-center justify-between mb-2">
                      <span class="ml-5">{{ items.namaproduk }}</span>
                      <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash" @click="hapussskii(items.id)"
                        color="danger" raised circle class="ml-auto" />
                    </div>
                  </div>

                  <VPlaceloadText v-if="isLoadingTindakan" :lines="1" width="75%" last-line-width="25%" />
                </VCard>
              </div>
            </div>
          </div>
        </div>

        <VCard radius="rounded" class="mt-2" v-else-if="dataSelectedTindakan.length == 0 && isLoadingTindakan">
          <VPlaceloadText :lines="5" width="75%" last-line-width="25%" />
        </VCard>

        <VCard radius="rounded" class="mt-2" v-else>
          <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" larger>

            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />

          </VPlaceholderPage>
        </VCard>
      </div>
    </div>
    <VButton type="button" rounded color="primary" raised icon="feather:save" class="is-pulled-right"
      :loading="isLoading" @click="newSimpan()"> Tambah
    </VButton>
  </Dialog>

  <Dialog v-model:visible="modalInput2" modal header="Tindakan Estimasi Lanjutan" :style="{ width: '85rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
    <div class="columns is-multiline">
      <div class="column is-6">
        <VCard>
          <DataTable v-model:selection="selectedProduct" v-model:filters="filtersTindakan" :loading="isLoadingTindakan"
            :rows="10" paginator :value="products" selectionMode="multiple" :metaKeySelection="metaKey" dataKey="id"
            @rowSelect="onTindakanSelected2" @rowUnselect="onTindakanUnselected"
            :globalFilterFields="['namaproduk', 'id']" tableStyle="min-width: 50rem">
            <template #header>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <InputText v-model="filtersTindakan['global'].value" placeholder="Search Data" />
                  </VField>
                </div>
                <div class="column is-6" style="display: none !important">
                  <VField>
                    <VControl>
                      <VSwitchBlock v-model="isAllTindakan" @change.stop="isAllTindakanChange(isAllTindakan)"
                        color="success" label="Semua Tindakan" />
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
            <Column field="id" header="ID"></Column>
            <Column field="namaproduk" header="Nama"></Column>
          </DataTable>
        </VCard>
      </div>
      <div class="column is-6">
        <span style="font-weight: bold">List Tindakan Dipilih</span>
        <div class="timeline-wrapper" v-if="dataSelectedTindakan2.length > 0" style="margin-top: 20px;">
          <div class="timeline-header"></div>
          <div class="timeline-wrapper-inner pt-0">
            <div class="timeline-container">
              <div class="timeline-item is-unread">
                <VCard radius="rounded" class="p-3 mb-3">
                  <div v-for="(items, index) in dataSelectedTindakan2" :key="items.id">
                    <div class="flex items-center justify-between mb-2">
                      <span class="ml-5">{{ items.namaproduk }}</span>
                      <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash" @click="hapussskii(items.id)"
                        color="danger" raised circle class="ml-auto" />
                    </div>
                  </div>

                  <VPlaceloadText v-if="isLoadingTindakan" :lines="1" width="75%" last-line-width="25%" />
                </VCard>
              </div>
            </div>
          </div>
        </div>

        <VCard radius="rounded" class="mt-2" v-else-if="dataSelectedTindakan2.length == 0 && isLoadingTindakan">
          <VPlaceloadText :lines="5" width="75%" last-line-width="25%" />
        </VCard>

        <VCard radius="rounded" class="mt-2" v-else>
          <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" larger>

            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />

          </VPlaceholderPage>
        </VCard>
      </div>
    </div>
    <VButton type="button" rounded color="primary" raised icon="feather:save" class="is-pulled-right"
      :loading="isLoading" @click="newSimpan2()"> Tambah
    </VButton>
  </Dialog>
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
                                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                      width="15%">Tanggal Input</td>
                                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                      width="15%">Tanggal Registrasi</td>
                                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                      width="15%">No Registrasi</td>
                                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                      width="15%">No RM</td>
                                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                      width="20%">Dokter</td>
                                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                      width="15%">Section</td>
                                  <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                      width="5%">#</td>
                              </tr>
                          </thead>
                          <tbody v-for="resep in listTemplate">
                              <tr>
                                  <td
                                      style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                      <span class="mb-2">{{ resep.created_at }}</span><br>
                                  </td>
                                  <td
                                      style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                      <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                  </td>
                                  <td
                                      style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                      <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                  </td>
                                  <td
                                      style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                      <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                  </td>
                                  <td
                                      style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                      <span class="mb-2">{{ resep.registrasi.dokter }}</span><br>
                                  </td>
                                  <td
                                      style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                  </td>
                                  <td
                                      style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                      <VIconButton type="button" raised circle icon="fas fa-plus"
                                          @click="addRiwayat(resep)" color="info"
                                          v-tooltip-prime.top="'Pilih'">
                                      </VIconButton>
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
        <Column headerStyle="width: 8rem">
          <template #body="slotProps">
            <VButtons>
              <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)"
                v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" :isLoading="isLoading"/>
              <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Pilih'">
              </VIconButton>
              <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
                color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
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
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const user = useUserSession().getUser().pegawai
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
const modalInput: any = ref(false)
const modalInput2: any = ref(false)
const d_Produk: any = ref([])
let dataSelectedTindakan: any = ref([]);
let dataSelectedTindakan2: any = ref([]);
const selectedProduct = ref();
const dataSourceTindakan: any = ref([])
const isLoadingTindakan: any = ref(false);
const isAlltemplate: any = ref(false);
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const idTemplate: any = ref('');
const products = ref();
const dataTTD: any = ref([]);
const ingredient = ref('');
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_Dokter: any = ref([])
const d_Petugas: any = ref([])
const d_Ruangan: any = ref([])
const d_RuangPerawatan: any = ref([])
const d_Komponen: any = ref([])
const filtersTindakan = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('PerkiraanBiaya') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPegawai = async(filter :any)=>{
     const response = await useApi().get(
      `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
      d_Petugas.value = response
  }
const fetchRP = async (filter: any) => {
  await useApi().get(`emr/dropdown/custom/get-kelas?kebangsaan=${props.pasien.objectkebangsaanfk}&query=${filter.query}`).then((response) => {
    d_RuangPerawatan.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    dataTTD.value = response[0]
    H.tandaTangan().set("TTDAdmission", dataTTD.value.TTDAdmission)
    H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
    H.tandaTangan().set("TTDPasien", dataTTD.value.TTDPasien)

    H.tandaTangan().set("TTDAdmission2", dataTTD.value.TTDAdmission2)
    H.tandaTangan().set("TTDDokter2", dataTTD.value.TTDDokter2)
    H.tandaTangan().set("TTDPasien2", dataTTD.value.TTDPasien2)
  } else {
    const rj = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalMedisGawatDarurat" + `&field=TADiagnosis`)
    if(rj != null) {
      input.value.TADiagnosa = rj.TADiagnosis ? rj.TADiagnosis : ''
      input.value.TADiagnosa2 = rj.TADiagnosis ? rj.TADiagnosis : ''
    }
  }
}

const kodetarif = () => {
  dataSelectedTindakan.value = []
  useApi().get(
    `/tindakan/list-tindakan`).then((response: any) => {
      d_Produk.value = response.data.map((e: any) => { return { label: e.namaproduk, value: e } })
      products.value = response.data;
      isLoadingTindakan.value = false;
      modalInput.value = true
    })
}

const kodetarif2 = () => {
  dataSelectedTindakan.value = []
  useApi().get(
    `/tindakan/list-tindakan`).then((response: any) => {
      d_Produk.value = response.data.map((e: any) => { return { label: e.namaproduk, value: e } })
      products.value = response.data;
      isLoadingTindakan.value = false;
      modalInput2.value = true
    })
}

const onTindakanSelected = async (event) => {
  await changeTindakan(event.data);

  let jsonPush = {
    id: event.data.id,
    namaproduk: event.data.namaproduk,
    objectruanganfk: event.data.objectruanganfk,
    hargasatuan: item.hargasatuan,
    jumlah: item.jumlah,
    komponenharga: d_Komponen,
    tanggal: moment(new Date()).format('DD-MM-YYYY')
  }

  dataSelectedTindakan.value.push(jsonPush);


};
const onTindakanSelected2 = async (event) => {
  await changeTindakan(event.data);

  let jsonPush = {
    id: event.data.id,
    namaproduk: event.data.namaproduk,
    objectruanganfk: event.data.objectruanganfk,
    hargasatuan: item.hargasatuan,
    jumlah: item.jumlah,
    komponenharga: d_Komponen,
    tanggal: moment(new Date()).format('DD-MM-YYYY')
  }

  dataSelectedTindakan2.value.push(jsonPush);


};
const onTindakanUnselected = async (event) => {
  await deleteItem(event.data.id);
}

const newSimpan = () => {
  if (input.value.TBKodeTarif === undefined) {
    input.value.TBKodeTarif = '';
  }
  if (input.value.TBTotalTarif === undefined) {
    input.value.TBTotalTarif = 0;
  } else {
    input.value.TBTotalTarif = Number(input.value.TBTotalTarif);
  }

  console.log(dataSelectedTindakan.value);

  for (let x = 0; x < dataSelectedTindakan.value.length; x++) {
    input.value.TBKodeTarif += `Kode: ${dataSelectedTindakan.value[x].id}, ` + `Nama Produk: ${dataSelectedTindakan.value[x].namaproduk}, ` + `Harga Satuan: ${dataSelectedTindakan.value[x].hargasatuan}\n`;

    let harga = Number(dataSelectedTindakan.value[x].hargasatuan);
    if (!isNaN(harga)) {
      input.value.TBTotalTarif += harga;
    }
  }

  modalInput.value = false;
};


const newSimpan2 = () => {
  if (input.value.TBKodeTarif2 === undefined) {
    input.value.TBKodeTarif2 = '';
  }
  if (input.value.TBTotalTarif2 === undefined) {
    input.value.TBTotalTarif2 = 0;
  } else {
    input.value.TBTotalTarif2 = Number(input.value.TBTotalTarif2);
  }

  console.log(dataSelectedTindakan2.value);

  for (let x = 0; x < dataSelectedTindakan2.value.length; x++) {
    input.value.TBKodeTarif2 += `Kode: ${dataSelectedTindakan2.value[x].id}, ` + `Nama Produk: ${dataSelectedTindakan2.value[x].namaproduk}, ` + `Harga Satuan: ${dataSelectedTindakan2.value[x].hargasatuan}\n`;
    let harga = Number(dataSelectedTindakan2.value[x].hargasatuan);
    if (!isNaN(harga)) {
      input.value.TBTotalTarif2 += harga;
    }
  }

  modalInput2.value = false;
};


async function changeTindakan(e: any) {
  isLoading.value = true
  isLoadingTindakan.value = true;
  d_Komponen.value = []
  item.hargasatuan = 0
  await useApi().get(
    '/tindakan/list-tindakan-komponen?idRuangan=' + props.registrasi.objectruanganfk
    + '&idKelas=' + props.registrasi.objectkelasfk
    + '&idProduk=' + e.id
    + '&idJenisPelayanan=' + props.registrasi.jenispelayananfk
    + '&idPenjamin=' + props.registrasi.objectrekananfk
    + '&objectkebangsaanfk=' + props.pasien.objectkebangsaanfk
  ).then((response: any) => {
    isLoading.value = false
    isLoadingTindakan.value = false;
    item.hargasatuan = response.harga.hargasatuan
    item.hargasatuanDef = response.harga.hargasatuan
    item.jumlah = 1
    d_Komponen.value = response.komponen
  })
}

let PilihdiagnosaAsmed = 0;
let PilihdiagnosaCPPT = 0;

const DiagnosaAsmed = async () => {
  PilihdiagnosaAsmed++;
  if  (PilihdiagnosaAsmed >= 2) {
    input.value.TADiagnosa = '';
    PilihdiagnosaAsmed = 0;
  } else {
    const response_Asmed = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + `&field=TADiagnosa`)
    if (response_Asmed != null) {
    input.value.TADiagnosa = response_Asmed.TADiagnosa
    }
  }
}

const DiagnosaCPPT = async () => {
  PilihdiagnosaCPPT++;
  if  (PilihdiagnosaCPPT >= 2) {
    input.value.TADiagnosa = '';
    PilihdiagnosaCPPT = 0;
  } else {
    const response_CPPT = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail" + `&field=A`)
    if (response_CPPT != null) {
    input.value.TADiagnosa = response_CPPT.A
    }
  }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDAdmission'] = H.tandaTangan().get("TTDAdmission");
  object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
  object['TTDPasien'] = H.tandaTangan().get("TTDPasien");

  object['TTDAdmission2'] = H.tandaTangan().get("TTDAdmission2");
  object['TTDDokter2'] = H.tandaTangan().get("TTDDokter2");
  object['TTDPasien2'] = H.tandaTangan().get("TTDPasien2");
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    input.value.id = response.id
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''

  let object: any = {}

  object = input.value
  object.nocm = props.pasien.nocm

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
    `/emr/simpan-emr-template`, json).then((response: any) => {
      isLoading.value = false
      input.value.namatemplate = null
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        if (responselast.length) {
            listTemplate.value = responselast //set ke inputan
            showModalTemplate.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
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
        // showModalTemplateFix.value = false;
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

const editTemplate = async (dt: any) => {
  if (!dt) return;
  console.log("DT EDIT", dt);
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  // await addTemplate(dt);
  // input.value.namatemplate = null
  input.value = dt //set ke inputan
  isAlltemplate.value = false;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
}

const addTemplate = (response) => {
  input.value = response;
  dataTTD.value = response;
  H.tandaTangan().set("TTDAdmission", dataTTD.value.TTDAdmission);
  H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter);
  H.tandaTangan().set("TTDPasien", dataTTD.value.TTDPasien);

  H.tandaTangan().set("TTDAdmission2", dataTTD.value.TTDAdmission2)
  H.tandaTangan().set("TTDDokter2", dataTTD.value.TTDDokter2)
  H.tandaTangan().set("TTDPasien2", dataTTD.value.TTDPasien2)
  delete input.value['_id'];
  delete input.value['id'];
  input.value.namatemplate = null;
  showModalTemplateFix.value = false;
  H.alert('success', 'Berhasil ditambahkan');
};

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}&isAll=${isAlltemplate.value}&nocmfk=${ID_PASIEN}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          // responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        listTemplateFix.value = [];
        showModalTemplateFix.value = true
        H.alert('warning', 'Data tidak ada')
      }
    })
}
const isDisabled = ref(false)
const enableInput = async () => {
  isDisabled.value = false
}


const addRiwayat = (response: any) => {
    input.value = response //set ke inputan

    dataTTD.value = response;
    H.tandaTangan().set("TTDAdmission", dataTTD.value.TTDAdmission);
    H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter);
    H.tandaTangan().set("TTDPasien", dataTTD.value.TTDPasien);

    H.tandaTangan().set("TTDAdmission2", dataTTD.value.TTDAdmission2)
    H.tandaTangan().set("TTDDokter2", dataTTD.value.TTDDokter2)
    H.tandaTangan().set("TTDPasien2", dataTTD.value.TTDPasien2)
    delete input.value.namatemplate;
    delete input.value['_id'];

    isDisabled.value = true

    showModalTemplate.value = false
}

watch(() => input.value.DDRuangPerawatan, (a) => {
  if (a.harga) {
    input.value.tarifRuangPerawatan = a.harga
  } else { return }
})

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})

onBeforeMount(async () => {
  try {
    await setView()
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
</script>
