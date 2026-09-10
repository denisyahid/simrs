<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>CHECK LIST PRA OPERASI DAN PASCA OPERASI</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

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
            :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
            breakpoint="960px">
            <template #header>
              <div class="columns is-multiline">
                <div class="column is-8">
                  <VField>
                    <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
                  </VField>
                </div>
                <div class="column is-4"></div>
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
                  <VIconButton color="danger" light raised circle icon="lucide:x"
                    @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
                  <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                    color="info" v-tooltip-prime.top="'Pilih'">
                  </VIconButton>
                  <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                    @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                    v-if="!isAlltemplate">
                  </VIconButton>
                </VButtons>
              </template>
            </Column>
            <Column field="namatemplate" header="Nama" :sortable="true"></Column>
            <!-- <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
              <template #body="slotProps">
                {{ slotProps.data.registrasi.namaruangan }}
              </template>
            </Column> -->
            <Column field="created_at" header="Tanggal" :sortable="true">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
              </template>
            </Column>
          </DataTable>
        </template>
      </VModal>

        <div class="column is-12" style="margin-top: 30px;">
          <div class="columns is-multiline">
            <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
                @click="pilihTemplateFix(index)"> Pilih Template
              </VButton>
            </div>
  
            <div class="column is-12 pt-0 pb-0">
              <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
            </div>
            <div class="column is-12">
              <h1 class="mb-3 emr">Nama Template&emsp;&emsp;**Hanya diisi jika ingin membuat template</h1>
              <VField>
                <VControl>
                  <VTextarea v-model="input.namatemplate" rows="2">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <h1 class="mb-3" style="font-weight: bold;">Tanggal Lahir</h1>
                  <VField>
                    <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks>
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
                  <h1 class="mb-3" style="font-weight: bold;">Jam </h1>
                  <VField>
                    <VDatePicker v-model="input.kebjamAsesmenAwal" mode="time" style="width: 100%" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput :value="inputValue" placeholder="Jam" v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
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

            </div>
          </div>
        </div>

        <br>
        <hr><br>
        <div class="container">
          <table class="table is-bordered is-fullwidth">
            <thead>
              <tr>
                <th style="background-color: #0000CD; color: #FFFFFF" colspan="7" rowspan="1">Pre Operasi
                </th>
              </tr>
              <tr style="text-align: center;">
                <th rowspan="2" colspan="2" style="width: 40%; text-align: left;">Hal-hal yang harus Dioperkan Oleh
                  Petugas
                  Ruangan</th>
                <th rowspan="1" colspan="2" style="width: 20%;">Perawat Ruangan</th>
                <th rowspan="1" colspan="2" style="width: 20%;">Perawat Kamar Operasi</th>
                <th rowspan="2" style="width: 20%;">Keterangan</th>
              </tr>
              <tr style="text-align: center;">
                <th>Ya</th>
                <th>Tidak</th>
                <th>Ya</th>
                <th>Tidak</th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="5">1. Identitas Pasien
                </th>
              </tr>
              <tr>
                <th>Benar Gelang Pasien</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.gelA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.gelA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.gelB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.gelB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.gelE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Benar Nama</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.namaA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.namaA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.namaB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.namaB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.namaE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Benar Nomor RM</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.RMA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.RMA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.RMB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.RMB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.RME" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Benar Tanggal Lahir</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.TLA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.TLA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.TLB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.TLB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.TLE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">2. Informed Consent
                </th>
              </tr>
              <tr>
                <th>Bedah</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.bedahA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.bedahA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.bedahB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.bedahB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.bedahE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Anestesi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.anesA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.anesA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.anesB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.anesB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.anesE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th colspan="2">3. Site Marking
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.SMA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.SMA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.SMB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.SMB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.SME" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">4. Evaluasi pre operasi
                </th>
              </tr>
              <tr>
                <th>Pra Anestesi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pranesA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pranesA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pranesB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pranesB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.pranesE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Pra Bedah</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.prabedA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.prabedA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.prabedB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.prabedB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.prabedE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">5. Hasil Pemeriksaan
                </th>
              </tr>
              <tr>
                <th>Laboratorium</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.labA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.labA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.labB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.labB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.labE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>PA</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.PAA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.PAA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.PAB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.PAB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.PAE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">6. Rontgen
                </th>
              </tr>
              <tr>
                <th>Thorax</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.thoA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.thoA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.thoB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.thoB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.thoE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Foto lain</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.fotA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.fotA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.fotB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.fotB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.fotE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="4">7. Operasi Khusus Jantung
                </th>
              </tr>
              <tr>
                <th>CT Scan Anglo</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.ctsA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.ctsA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.ctsB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.ctsB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.ctsE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Echo</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.echoA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.echoA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.echoB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.echoB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.echoE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Kateterisasi Jantung</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.kateA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.kateA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.kateB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.kateB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.kateE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="6">8. Hasil Konsul
                </th>
              </tr>
              <tr>
                <th>Penyakit Dalam</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pdA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pdA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pdB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pdB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.pdE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Anestesi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.aneA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.aneA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.aneB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.aneB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.aneE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th colspan="6">Bagian Lain :</th>
              </tr>

              <tr>
                <VField>
                  <VTextarea v-model="input.bag1" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.bagA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.bagA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.bagB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.bagB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.bagE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <VField>
                  <VTextarea v-model="input.bag2" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.balA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.balA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.balB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.balB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.balE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">9. Transfusi
                </th>
              </tr>
              <tr>
                <th>Persiapan Transfusi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.PTA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.PTA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.PTB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.PTB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.PTE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Persetujuan Transfusi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.setuA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.setuA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.setuB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.setuB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.setuE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="5">10. Prothesa
                </th>
              </tr>
              <tr>
                <th>Implant</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.impA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.impA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.impB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.impB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.impE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Pace Maker</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.PMA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.PMA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.PMB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.PMB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.PME" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Alat bantu dengar</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.alatA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.alatA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.alatB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.alatB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.alatE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Lensa Kontak/Kaca Mata</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.lensA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.lensA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.lensB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.lensB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.lensE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">11. Gigi
                </th>
              </tr>
              <tr>
                <th>Gigi Palsu</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.GPA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.GPA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.GPB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.GPB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.GPE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Gigi Goyang/Lepas </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.GGLA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.GGLA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.GGLB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.GGLB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.GGLE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="10">12. Persiapan Khusus
                </th>
              </tr>
              <tr>
                <th>Puasa</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pusA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pusA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pusB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pusB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.pusE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Pasang Infus</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pasinA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pasinA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pasinB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pasinB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.pasinE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Pasang Kateter</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.paskatA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.paskatA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.paskatB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.paskatB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.paskatE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Mandi Besar</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.manbesA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.manbesA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.manbesB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.manbesB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.manbesE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Cuci Rambut</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.curamA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.curamA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.curamB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.curamB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.curamE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Cukur Daerah Operasi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.cudaA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.cudaA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.cudaB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.cudaB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.cudaE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Potong Kuku</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pokuA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pokuA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pokuB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pokuB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.pokuE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Hapus Make Up, Cat Kuku</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.makeA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.makeA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.makeB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.makeB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.makeE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Pakaian Operasi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pakoA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pakoA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pakoB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pakoB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.pakoE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th rowspan="3">Huknah</th>
              </tr>
              <tr>
                <th>Tinggi, jam</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.tinggiA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.tinggiA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.tinggiB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.tinggiB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.tinggiE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
              <tr>
                <th>Rendah, jam</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.rendahA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.rendahA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.rendahB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.rendahB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.rendahE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th colspan="2">13. Barang-barang milik pasien/perhiasan
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.barmilA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.barmilA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.barmilB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.barmilB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.barmilE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th colspan="7" style="gap: 1rem">14. Pendamping selama Transfer :

                  <VCheckbox v-model="input.dokter1" true-value="Ya" color="primary" />
                  <span>Dokter</span>

                  <VCheckbox v-model="input.perawat1" true-value="Ya" color="primary" />
                  <span>Perawat/Bidan</span>

                  <VCheckbox v-model="input.pos1" true-value="Ya" color="primary" />
                  <span>POS</span>

                </th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">15. Profilaksis Antibiotik
                </th>
                <th colspan="6">
                  <span>Jenis</span>
                  <VField>
                    <VTextarea v-model="input.jenis1" placeholder="Sebutkan" color="primary" rows="2" />
                  </VField>
                </th>
              </tr>
              <tr>
                <th colspan="6">
                  <span>Dosis</span>
                  <VField>
                    <VTextarea v-model="input.donis1" placeholder="Sebutkan" color="primary" rows="2" />
                  </VField>
                </th>
              </tr>
              <tr>
                <th colspan="6">
                  <span>Waktu Pemberian</span>
                  <VField>
                    <VTextarea v-model="input.pemberian1" placeholder="Sebutkan" color="primary" rows="2" />
                  </VField>
                </th>
              </tr>
            </thead>
          </table>
          <div class="signature">
            <div>
              <p>Diserahkan</p>
              <br>
              <TandaTangan :elemenID="'TTDDiserahkan'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.Diserahkan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Diserahkan" />
              </VControl>
            </div>
            <div>
              <p>Diterima</p>
              <br>
              <TandaTangan :elemenID="'TTDDiterima'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.Diterima" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Diterima" />
              </VControl>
            </div>
          </div>


          <table class="table is-bordered is-fullwidth">
            <thead>
              <tr>
                <th style="background-color: #0000CD; color: #FFFFFF" colspan="7">Pasca Operasi
                </th>
              </tr>
              <tr style="text-align: center;">
                <th rowspan="2" colspan="2" style="width: 40%; text-align: left;">Hal-hal yang harus Dioperkan Oleh
                  Petugas
                  Ruangan</th>
                <th rowspan="1" colspan="2" style="width: 20%;">Perawat Kamar Operasi</th>
                <th rowspan="1" colspan="2" style="width: 20%;">Perawat Ruangan</th>
                <th rowspan="2" style="width: 20%;">Keterangan</th>
              </tr>
              <tr style="text-align: center;">
                <th style="width: 10%;">Ya</th>
                <th style="width: 10%;">Tidak</th>
                <th style="width: 10%;">Ya</th>
                <th style="width: 10%;">Tidak</th>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="10">1. Blangko
                </th>
              </tr>
              <tr>
                <th>Catatan keperawatan peri operatif</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.periA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.periA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.periB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.periB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.periE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Cek List kesiapan anestesi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.sipanA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.sipanA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.sipanB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.sipanB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.sipanE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Cek List Keselamatan pasien</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.sepA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.sepA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.sepB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.sepB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.sepE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>

              <tr>
                <th>Catatan Anestesi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.catanA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.catanA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.catanB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.catanB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.catanE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
              <tr>
                <th>Laporan Operasi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.LapopA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.LapopA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.LapopB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.LapopB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.LapopE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
              <tr>
                <th>Pemakaian Alat</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pemalA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pemalA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.pemalB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.pemalB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.pemalE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
              <tr>
                <th>Kitir Tindakan</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.kitirA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.kitirA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.kitirB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.kitirB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.kitirE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
              <tr>
                <th>Bilangko ILO</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.iloA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.iloA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.iloB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.iloB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.iloE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
              <tr>
                <th>Catatan Terintegrasi</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.terinA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.terinA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.terinB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.terinB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.terinE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">2. Bahan Pemeriksaan
                </th>
              </tr>
              <tr>
                <th>PA</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.labPAA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.labPAA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.labPAB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.labPAB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.labPAE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Kultur</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.kulturA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.kulturA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.kulturB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.kulturB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.kulturE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th rowspan="3">3. Rontgen
                </th>
              </tr>
              <tr>
                <th>Thorax</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.thorA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.thorA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.thorB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.thorB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.thorE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>

              </tr>
              <tr>
                <th>Foto lain</th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.folaA" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.folaA" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.folaB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.folaB" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.folaE" placeholder="Sebutkan" color="primary" rows="2" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th colspan="2">4. Barang-barang milik pasien
                  <VField>
                    <VTextarea v-model="input.barangpasienA" placeholder="Sebutkan" color="primary" rows="2" />
                  </VField>
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.barangpasienB" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.barangpasienB" true-value="Tidak" color="primary" />
                </th>
                <th class="yes-column" style="text-align: center;">
                  <VCheckbox v-model="input.barangpasienC" true-value="Ya" color="primary" />
                </th>
                <th class="no-column" style="text-align: center;">
                  <VCheckbox v-model="input.barangpasienC" true-value="Tidak" color="primary" />
                </th>
                <VField>
                  <VTextarea v-model="input.barangpasienF" placeholder="Sebutkan" color="primary" />
                </VField>
              </tr>
            </thead>

            <thead>
              <tr>
                <th colspan="7" style="gap: 1rem">5. Pendamping selama Transfer :

                  <VCheckbox v-model="input.dokter2" true-value="Ya" color="primary" />
                  <span>Dokter</span>

                  <VCheckbox v-model="input.perawat2" true-value="Ya" color="primary" />
                  <span>Perawat/Bidan</span>

                  <VCheckbox v-model="input.pos2" true-value="Ya" color="primary" />
                  <span>POS</span>

                </th>
              </tr>
            </thead>

          </table>
          <div class="signature">
            <div>
              <p>Disetujui</p>
              <br>
              <TandaTangan :elemenID="'TTDDisetujuidua'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.Disetujuidua" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Disetujui" />
              </VControl>
            </div>
            <div>
              <p>Diserahkan</p>
              <br>
              <TandaTangan :elemenID="'TTDDiserahkandua'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.Diserahkandua" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Diserahkan" />
              </VControl>
            </div>
            <div>
              <p>Diterima</p>
              <br>
              <TandaTangan :elemenID="'TTDDiterimadua'" :width="'150'" :height="'150'" class="dek" />
              <VControl class="prime-auto">
                <AutoComplete v-model="input.Diterimadua" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Diterima" />
              </VControl>
            </div>
          </div>
        </div>

        <br>
        <hr><br>
      </div>
    </div>
  </div>


  <!-- <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="20%">Section</td>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
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
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal> -->

  <!-- <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="showModalTemplateFix = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Template</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="15%">No</td>
                                    <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                                    <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                                    <td class="tg-0lax text-center" width="50%">Nama Template</td>
                                    <td class="tg-0lax text-center" width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:50%;text-align:center">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal> -->
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
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
const route = useRoute()
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
const idTemplate: any = ref('');
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const checkTemplate: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const COLLECTION: any = ref('ChecklistPraOperasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  kebjamAsesmenAwal: new Date(),
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const setAutoFill = async () => {
  input.value.kebtanggalKedatangan = props.pasien.tgllahir
  input.value.kebjamAsesmenAwal = props.pasien.tgllahir
  input.value.namaPasien = props.pasien.namapasien;
  input.value.norm = props.pasien.nocm;
}

const dataTTD: any = ref([]);
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0];
        H.tandaTangan().set("TTDDiserahkan", dataTTD.value.TTDDiserahkan);
        H.tandaTangan().set("TTDDiterima", dataTTD.value.TTDDiterima);
        H.tandaTangan().set("TTDDisetujuidua", dataTTD.value.TTDDisetujuidua);
        H.tandaTangan().set("TTDDiserahkandua", dataTTD.value.TTDDiserahkandua);
        H.tandaTangan().set("TTDDiterimadua", dataTTD.value.TTDDiterimadua);
      } else {
        setAutoFill();
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDDiserahkan'] = H.tandaTangan().get("TTDDiserahkan");
  object['TTDDiterima'] = H.tandaTangan().get("TTDDiterima");
  object['TTDDisetujuidua'] = H.tandaTangan().get("TTDDisetujuidua");
  object['TTDDiserahkandua'] = H.tandaTangan().get("TTDDiserahkandua");
  object['TTDDiterimadua'] = H.tandaTangan().get("TTDDiterimadua");
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': route.name,
    'name_form': 'Checklist Pra Operasi & Pasca Operasi',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr;
      loadRiwayat();
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
    delete object.namatemplate;
    delete object['_id'];
    delete object.pasien;
    delete object.registrasi;
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=true&nocmfk=${ID_PASIEN}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate','kebtanggalKedatangan','kebjamAsesmenAwal']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(`/emr/hapus-template`, json).then((response: any) => {
    if (response.status !== 500) {
      isLoading.value = false;
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
  showModalTemplateFix.value = false;
}
const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  input.value = dt;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  checkTemplate.value = true
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
setView()
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

.signature {
  display: flex;
  justify-content: center;
  gap: 100px;
}

.signature div {
  text-align: center;
}

.signature-line {
  border-bottom: 1px solid #000;
  width: 200px;
  margin: 20px auto;
}

.prime-auto{
  margin-top: 10px;
}
</style>