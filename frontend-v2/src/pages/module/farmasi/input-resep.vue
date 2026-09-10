
<template>
     <ConfirmDialog />
    <div class="columns">
        <div class="column is-12 form-layout is-stacked">
            <div class=" form-outer">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>{{ TITLE_PAGE }}</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined
                                    rounded>
                                    Cancel
                                </VButton>
                                <SplitButton v-if="item.noResep" label="Cetakan" icon="pi pi-info-circle"
                                    :model="listButton" rounded />
                                <VButton v-else icon="feather:save" type="submit" color="primary" raised @click="cekSimpan()"
                                    rounded :loading="isSimpan">
                                    Save
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body">

                    <div class="columns is-multiline">
                        <div class="column is-3" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-6" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Pasien</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-3" v-if="!item.header.nocm">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Pengkajian</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-12" v-if="!item.header.nocm">
                            <VCard>
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VPlaceload height="42px" />
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <div class="flex-table-item grid-item mb-4">
                                            <div class="flex-list-inner mb-4">
                                                <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                                                    <VFlexTableCell>
                                                        <VPlaceload width="10%" class="mx-1" height="20px" />
                                                        <VPlaceload width="10%" class="mx-1 is-pulled-right"
                                                            height="20px" />
                                                    </VFlexTableCell>
                                                    <VFlexTableCell>
                                                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                                                    </VFlexTableCell>
                                                    <VFlexTableCell>
                                                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                                                    </VFlexTableCell>
                                                    <VFlexTableCell>
                                                        <VPlaceload width="100%" height="30px" class="mx-1 mt-2" />
                                                    </VFlexTableCell>
                                                    <VFlexTableCell :column="{ align: 'end' }">
                                                        <VPlaceload width="10%" height="20px" class="mx-1 mt-2" />
                                                    </VFlexTableCell>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                    </div>
                    <div class="columns is-multiline" v-if="item.header.nocm">
                        <div class="column is-3">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Resep</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Ruang Rawat</VLabel>
                                            <VLabel>{{ item.header.ruangrawat }} </VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Nomor Resep</VLabel>
                                            <VLabel>{{ item.resep ? item.resep : '-' }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Jenis Pasien</VLabel>
                                            <VLabel>{{ item.header.kelompokpasien }} - {{ item.header.kebangsaan }}</VLabel>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-6">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Data Pasien</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>No RM</VLabel>
                                            <VLabel>{{ item.header.nocm }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>No BPJS</VLabel>
                                            <VLabel>{{ item.header.nobpjs }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Nama Pasien</VLabel>
                                            <VLabel>{{ item.header.namapasien }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Jenis Kelamin</VLabel>
                                            <VLabel>{{ item.header.jeniskelamin }} - </VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Umur</VLabel>
                                            <VLabel>{{ item.header.umur }}</VLabel>
                                        </VField>
                                    </div>
                                    
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Penjamin</VLabel>
                                            <VLabel>{{ item.header.namarekanan }}</VLabel>
                                        </VField>
                                    </div>

                                   
                                    <div class="column is-8">
                                        <VField>
                                            <VLabel>Alamat</VLabel>
                                            <font color="white" size="1px">{{ item.header.alamatlengkap }}</font>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Tgl Kontrol</VLabel>
                                            <font color="white" size="1px">{{ item.header.suratkontrol }}</font>
                                        </VField>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <div class="column is-3">
                            <VCard custom="card-green">
                                <h3 class="title is-5 mb-2">Pengkajian</h3>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Berat Badan</VLabel>
                                            <VLabel>{{ item.header.beratbadan }} </VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Tinggi Badan</VLabel>
                                            <VLabel>{{ item.header.tinggibadan }} </VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>No Hp</VLabel>
                                            <VLabel>{{ item.header.nohp }}</VLabel>
                                        </VField>
                                    </div>

                                </div>
                            </VCard>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <VCard>
                                        <h3 class="title is-5 mb-2">Data Resep</h3>
                                        <div class="columns is-multiline">
                                            <div class="column is-4">
                                                <VDatePicker v-model="item.tglAwal" color="green" trim-weeks
                                                    mode="dateTime">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VLabel class="item">Tgl Resep</VLabel>
                                                            <VControl icon="feather:calendar">
                                                                <VInput type="text" placeholder="Select a date"
                                                                    class="is-rounded" :value="inputValue"
                                                                    v-on="inputEvents" :disabled="disTanggal" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </div>
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel class="item">Penulis Resep</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select"
                                                        :loading="isLoadingSelect">
                                                        <Dropdown v-model="item.penulisResep" :options="d_penulisResep"
                                                            :optionLabel="'namalengkap'" class="is-rounded"
                                                            :disabled="!!item.penulisResep"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel class="item">Ruangan</VLabel>
                                                    <VControl icon="feather:search" class="prime-auto-select"
                                                        :loading="isLoadingSelect">
                                                        <Dropdown v-model="item.ruangan" :options="d_ruangan" optionLabel="label"
                                            :disabled="disabledRuangan" class="is-rounded" placeholder="Pilih data"
                                            style="width: 100%;" :filter="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 ">
                                            <!-- <div class="column is-6">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="item.isreseppulang" class="p-0" :label="'Resep Pulang'" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div> -->
                                                <div class="column is-4 mt-5-min">
                                                    <VField>
                                                        <VLabel>Jenis Resep</VLabel>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="item.cito" class="p-0" label="Cito" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="item.isrutin" class="p-0" label="Rutin" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="item.isbpl" class="p-0" label="BPL" color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column is-4 mt-5-min" v-if="item.header.kelompokpasien && item.header.kelompokpasien.includes('BPJS')">
                                               <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabel class="item">Jenis Obat Apotik Online</VLabel>
                                                    <VControl icon="feather:home" class="prime-auto-select"
                                                        :loading="isLoadingSelect">
                                                        <Dropdown v-model="item.jenisobat" :options="listJenisObatApotikOnline"
                                                            :optionLabel="'jenisobat'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 mt-5-min">
                                                    <VField>
                                                        <VLabel>Riwayat Alergi</VLabel>
                                                        <VControl>
                                                        <VTextarea v-model="item.alergiobat" rows="1" placeholder="Alergi">
                                                        </VTextarea>
                                                        </VControl>
                                                    </VField>
                                                </div>
                            
                                            <!-- <div class="column is-3 mt-5-min">
                                              <VField>
                                                  <VControl raw subcontrol>
                                                      <VCheckbox v-model="item.checkisKronis" class="p-0"
                                                          :label="'Obat Kronis'" color="primary" square />
                                                  </VControl>
                                              </VField>
                                          </div>
                                          <div class="column is-3 mt-5-min">
                                              <VField>
                                                  <VControl raw subcontrol>
                                                      <VCheckbox v-model="item.isresepcito" class="p-0" :label="'Cito'"
                                                          color="primary" square />
                                                  </VControl>
                                              </VField>
                                          </div> -->
                                        </div>
                                    </VCard>
                                </div>
                                <div class="column is-12">
                                    <VCard>
                                        <div class="column is-2">
                                            <VButton color="info" class="w-100 btn-slim" light rounded outlined
                                                v-tooltip-prime.right="'Riwayat Resep'" @click="isResep = true">Riwayat
                                                Resep </VButton>
                                        </div>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <div class="columns is-multiline">
                                                    <div class="column is-6">
                                                        <Toolbar class="mb-4">
                                                            <template #start>
                                                                <VButton icon="feather:plus" color="info" raised
                                                                    @click="addPopUp">
                                                                    Tambah
                                                                </VButton>
                                                                <div class="column is-5">
                                                                <VControl class="is-pulled-right">
                                                                <!-- <VSwitchBlock
                                                                    style="padding-top: 3px"
                                                                    v-model="iterasi1"
                                                                    label="Iterasi 1"
                                                                    color="danger"
                                                                />
                                                                </VControl>
                                                                </div>
                                                                <div class="column is-5">
                                                                <VControl class="is-pulled-right">
                                                                <VSwitchBlock
                                                                    style="padding-top: 3px"
                                                                    v-model="iterasi2"
                                                                    label="Iterasi 2"
                                                                    color="danger"
                                                                /> -->
                                                                </VControl>
                                                                </div>
                                                            </template>
                                                        </Toolbar>
                                                    </div>

                                                    <div class="column is-6">
                                                        <Toolbar class="mb-4" style="height: 70px;">
                                                            <template #start>
                                                                <VFlexTableCell :column="{ align: 'end' }">
                                                                    <span class="bold-text">TOTAL BAYAR : </span>
                                                                    <span
                                                                        class="ml-1 bold-text light-text is-pulled-right">
                                                                        {{ H.formatRp(item.totalbayar, 'Rp. ') }}
                                                                    </span>
                                                                </VFlexTableCell>
                                                            </template>
                                                        </Toolbar>
                                                    </div>
                                                </div>

                                                <TListResep title="" straight class="list-widget-v3" :items="dataSource"
                                                    @editItems="editRow" @hapusItems="hapusRow" :iskronis="false" :disabled="isDisabledList"
                                                    @btnLoading="" squared colored>
                                                </TListResep>

                                            </div>
                                            <div class="column is-12" v-if="dataGridKronis.length">
                                                <VCard>
                                                    <h3 class="title is-5 mb-2">Obat Kronis</h3>
                                                    <TListResep title="" straight class="list-widget-v3"
                                                        :items="dataGridKronis" @editItems="editRow"
                                                        @hapusItems="hapusRow" :iskronis="true" squared colored>
                                                    </TListResep>
                                                </VCard>

                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
                        </div>
<!-- Pengkajian Resep --------------------------------------->                              
                        <div class="column is-12">
                            <VCard class="p-4">
                                <h3 class="title is-5 mb-4" style="text-align: center;">Pengkajian Resep</h3>
                                    <div class="columns is-multiline">
                                    
                                        <div class="column is-6">
                                            <table class="p-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th style="text-align:center">Y</th>
                                                        <th style="text-align:center">T</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="aspek in aspekKiri" :key="aspek.key">
                                                        <th>{{ aspek.label }}</th>
                                                            <td class="pilihannya">
                                                                <label>
                                                                    <VCheckbox 
                                                                    :inputId="aspek.key + 'Y'"
                                                                    v-model="kajian[aspek.key + 'Y']"
                                                                    @change="onCheckboxChange(aspek.key, 'Y')"
                                                                    />
                                                                </label>
                                                            </td>

                                                            <td class="pilihannya">
                                                                <label>
                                                                    <VCheckbox
                                                                    :inputId="aspek.key + 'T'"
                                                                    v-model="kajian[aspek.key + 'T']"
                                                                    @change="onCheckboxChange(aspek.key, 'T')"
                                                                    />
                                                                </label>
                                                            </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="column is-6">
                                        <table class="p-table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="text-align:center; width:10px;">Y</th>
                                                <th style="text-align:center">T</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="aspek in aspekKanan" :key="aspek.key">
                                                <th>{{ aspek.label }}</th>
                                                <td class="pilihannya">
                                                <label>
                                                    <VCheckbox
                                                    :inputId="aspek.key + 'Y'"
                                                    v-model="kajian[aspek.key + 'Y']"
                                                    @change="onCheckboxChange(aspek.key, 'Y')"
                                                    />
                                                </label>
                                                </td>
                                                <td class="pilihannya">
                                                <label>
                                                    <VCheckbox
                                                    :inputId="aspek.key + 'T'"
                                                    v-model="kajian[aspek.key + 'T']"
                                                    @change="onCheckboxChange(aspek.key, 'T')"
                                                    />
                                                </label>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                            <table class="p-table">
                                                <tr>
                                                    <th class="pilihannya2">
                                                       <font style="color:blue">Select All</font>
                                                    </th>
                                                
                                                    <td class="pilihannya2">
                                                         <VCheckbox v-model="selectAllPengkajian" color:blue @change="onSelectAllChange" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                            </VCard>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
<!-- ------------------------------------------------------->
        <VModal :open="modalInput" title="Add Resep" size="big" actions="right" @close="modalInput = false" noclose>
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                         <div class="column is-3 mt-5-min">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="item.inacbgss"
                                        color="warning" label="INA-CBG" square style="color:red; font-weight:bold;" >
                                       </VCheckbox>
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-3 mt-5-min">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-if="isDisabledKronis" disabled v-model="item.checkisKronis" label="Non INA-CBG 23 Hari" 
                                    color="primary" square style="color:red; font-weight:bold;" >
                                    </VCheckbox>

                                    <VCheckbox v-else v-model="item.checkisKronis"
                                        color="primary" label="Non INA-CBG 23 Hari" square style="color:red; font-weight:bold;" >
                                    </VCheckbox>
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-3 mt-5-min">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="item.checkisKronisAll"
                                        color="warning" label="Non INA-CBG 30 Hari" square style="color:red; font-weight:bold;" >
                                       </VCheckbox>
                                </VControl>
                            </VField>
                        </div>
                      
                        <div class="column is-2 mt-5-min">
                            <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="item.issementara" color="warning" square>
                                <span class="font-bold text-black">Stok Kosong</span>
                                </VCheckbox>
                            </VControl>
                            </VField>
                        </div>

                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">R/Ke</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.rke" placeholder="R/Ke" class="is-rounded" :disabled="isQtyDisabled" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VLabel class="item">Jenis Kemasan</VLabel>
                                <VControl>
                                    <VRadio v-for="items in d_kemasan" :key="items.id" v-model="item.jenisKemasan"
                                        :value="items" :label="items.jeniskemasan" name="{{items.id}}"
                                        color="primary" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="showRacikanDose">
                            <VField>
                                <VLabel class="item">Jumlah </VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.jumlahxmakan" placeholder="Jumlah" :disabled="isQtyDisabled"
                                        class="is-rounded" />

                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="showRacikanDose">
                            <VField>
                                <VLabel class="item">Dosis </VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.dosis" placeholder="Dosis" class="is-rounded" :disabled="isQtyDisabled"/>
                                    <!-- <p class="help"> {{ (item.kekuatan ? 'Kekuatan : ' + item.kekuatan : '') + ' ' +
                                      (item.sediaan ?
                                          item.sediaan : '')
                                  }}</p> -->
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-show="Hide">
                            <VField>
                                <VLabel class="item">Kekuatan </VLabel>
                                <VControl icon="feather:bookmark">
                                    <!-- <VInput type="text" v-model="item.dosis" placeholder="Dosis" class="is-rounded" /> -->
                                    <VInput type="text" v-model="item.kekuatan" placeholder="Kekuatan"
                                        class="is-rounded">
                                    </VInput>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="showRacikanDose">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Jenis Racikan</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.jenisRacikan" :options="d_jenisRacikan"
                                        :optionLabel="'jenisracikan'" class="is-rounded" placeholder="Pilih data" :disabled="isQtyDisabled"
                                        style="width: 100%;" showClear :filter="true" />
                                </VControl>
                                <!-- <VControl icon="feather:search">
                                  <Multiselect mode="single" v-model="item.jenisRacikan" :options="d_jenisRacikan"
                                      placeholder="Pilih data" :searchable="true" />
                                          </VControl> -->
                            </VField>
                        </div>


                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Produk</VLabel>
                                <VControl icon="feather:search" class="prime-auto">
                                    <AutoComplete v-model="item.produk" :suggestions="d_produk"
                                        @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                        :minLength="3" class="is-rounded" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'kode_namaproduk'" :disabled="isQtyDisabled"
                                        placeholder="ketik untuk mencari..." @item-select="changeProduk(item.produk)">
                                        <template #option="slotProps">
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <span style="font-weight:bold">{{ slotProps.option.name }}</span>
                                                </div>
                                                <div class="column is-12 mt-5-min">
                                                    <table style="width:50%">
                                                        <tr>
                                                            <td><b>[{{ slotProps.option.kdproduk }}] {{ slotProps.option.namaproduk }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kategory : {{ slotProps.option.satuanstandar }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Stok : {{ slotProps.option.stok }}</b></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </template>
                                    </AutoComplete>
                                </VControl>
                                <small v-if="isLastObatByDate" style="color:red">Produk {{ item.namaproduk }}, terakhir
                                    order tanggal : {{ item.lastorder }}</small>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Satuan</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'satuanstandar'"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear :disabled="isQtyDisabled"
                                        :filter="true" @change="changeSatuan(item.satuan)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">Konversi</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.nilaiKonversi" placeholder="Konversi" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">Stok</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.stok" placeholder="Stok" class="is-rounded" 
                                        disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VLabel class="item">Qty</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.jumlah" placeholder="Qty" class="is-rounded" :disabled="isQtyDisabled"/>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="item.issementara">
                            <VField>
                            <VLabel class="item">Qty Sementara</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput
                                type="number"
                                v-model="item.jumlahsementara"
                                placeholder="Qty"
                                class="is-rounded"
                                />
                            </VControl>
                            </VField>
                        </div>


                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Route</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.route" :options="d_route" :optionLabel="'name'"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;" showClear
                                        :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Tgl Kadaluarsa</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.tglKadaluarsa" :options="d_tglKadaluarsa"
                                        :optionLabel="'tglkadaluarsa'" class="is-rounded" placeholder="Pilih data"
                                        style="width: 100%;" showClear :filter="true"
                                        @change="changeExpired(item.tglKadaluarsa)" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="item.isbud"
                                        color="warning" square >
                                        <span class = "font-bold text-black">BUD</span>
                                    </VCheckbox>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="item.isbud">
                                <VDatePicker v-model="item.tglpemakaian" color="green" trim-weeks
                                                    mode="dateTime">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VLabel class="item">Batas Pemakaian</VLabel>
                                                            <VControl icon="feather:calendar">
                                                                <VInput type="text" placeholder="Select a date"
                                                                    class="is-rounded" :value="inputValue"
                                                                    v-on="inputEvents" :disabled="disTanggal" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                        </div>
                        <div class="column is-2">
                            <div class="checkboxes">
                                <VField>
                                    <VLabel class="item">Aturan Pakai</VLabel>
                                    <VControl icon="feather:bookmark">
                                        <VInput type="text" v-model="item.aturanpakaitxt" placeholder="Aturan Pakai"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="columns is-multiline mt-4">
                                <div class="column is-2" v-for="(opsi) in listDataSigna">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="opsi.isChecked" class="p-0" :label="opsi.nama"
                                                @keydown.enter.prevent="addListAturanPakai(true, opsi)"
                                                @change="addListAturanPakai(opsi.isChecked, opsi)" color="primary"
                                                square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="item">Waktu</VLabel>
                                <VControl icon="feather:search" class="prime-auto-select">
                                    <Dropdown v-model="item.satuanresep" :options="d_satuanResep"
                                        :optionLabel="'satuanresep'" class="is-rounded" placeholder="Pilih data"
                                        style="width: 100%;" showClear :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VLabel class="item">Keterangan</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.KeteranganPakai" placeholder="Keterangan"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>



                        <div class="column is-3">
                            <VField>
                                <VLabel class="item">Harga</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.hargaSatuan" placeholder="Harga" disabled
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3" v-if="isDisabledDiscount">
                            <VField>
                                <VLabel class="item" >Harga Diskon</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.hargadiskon" placeholder="Harga Diskon"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2" v-if="isDisabledDiscount">
                            <VField>
                                <VLabel class="item" >Diskon (%)</VLabel>
                                <VControl icon="feather:bookmark">
                                    <VInput type="number" v-model="item.persenDiskon" placeholder="Diskon (%)"
                                        class="is-rounded" @input="changeDikson($event.target.value)" disabled/>
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-6" v-if="showRacikanDose">

                        </div>
                        <div class="column is-4 analytics-dashboard is-pulled-right">
                            <div class="dashboard-tile p-2">
                                <div class="tile-head">
                                    <h3 class="dark-inverted">Total</h3>
                                    <VIconBox color="danger" size="small" rounded>
                                        <i aria-hidden="true" class="fas fa-calculator"></i>
                                    </VIconBox>
                                </div>
                                <div class="tile-body">
                                    <span class="dark-inverted">{{ H.formatRp(item.total, 'Rp. ') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="content">
                            <div class="is-divider" data-content="Resep Dibuat" />
                        </div>
                    </div>

                    <TListResep title="" straight class="list-widget-v3" :items="dataSource" @editItems="editRow"
                        @hapusItems="hapusRow" @btnLoading="" squared colored>
                    </TListResep>

                    <div v-if="dataGridKronis.length">
                        <h3 class="title is-5 mb-2">Obat Kronis / inacbg</h3>
                        <TListResep title="" straight class="list-widget-v3" :items="dataGridKronis"
                            @editItems="editRow" @hapusItems="hapusRow" :iskronis="true" squared colored>
                        </TListResep>
                    </div>


                    <div class="column is-12">
                        <div class="content">
                            <div class="is-divider" :data-content="infoStok" />
                        </div>
                    </div>

                    <div class="column is-12">
                        <DataTable :value="dataSourceStokProduk" :paginator="true" :rows="5"
                            :rowsPerPageOptions="[5, 10, 25]"
                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                            <Column field="no" header="No"></Column>
                            <Column field="namaruangan" header="ruangan"></Column>
                            <Column field="stok" header="Stok"></Column>
                        </DataTable>

                    </div>

                </form>
            </template>
            <template #action>
                <!-- <VButton
                    @click="paketObat()"
                    :loading="isloadingPaketObat"
                    color="info"
                    raised
                    class="is-pulled-right mr-2"
                    >Paket Obat
                    </VButton> -->
                <VButton icon="feather:plus" @click="add()" :loading="isLoading" color="primary" raised>Simpan</VButton>
            </template>
        </VModal>
    </div>


    <VModal :open="modalResepVerify" size="big" noclose title="Resep Verifikasi" actions="center"
        @close="modalResepVerify = false, clear()" cancelLabel="Tutup">
        <template #content>
            <div class="column">
                <DataTable :value="sourceDetailResep" dataKey="no" v-model:selection="selectedResep"
                    class="p-datatable-sm" :loading="sourceDetailResep.loading" :paginator="true" :rows="10"
                    :rowsPerPageOptions="[5, 10, 25]" scrollable
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 200px">
                    </Column>
                    <Column field="jmldosis" header="Jumlah Dosis" :sortable="true" style="min-width: 100px"></Column>
                    <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column>
                    <Column field="satuanstandar" header="Satuan" :sortable="true" style="min-width: 50px"></Column>
                    <Column field="jmldosis" header="Dosis" :sortable="true" style="min-width: 50px"></Column>
                    <Column field="aturanpakai" header="Aturan pakai" :sortable="true" style="min-width: 50px"></Column>
                    <Column field="jeniskemasan" header="Kemasan" :sortable="true" style="min-width: 50px"></Column>
                </DataTable>
            </div>
        </template>
        <template #action>
            <VButton @click="cetakRekapLabel(selectedResep, true)" icon="feather:printer" :loading="isLoading"
                color="primary" raised>
                Label Injeksi</VButton>
            <VButton @click="cetakRekapLabel(selectedResep, false)" icon="feather:printer" :loading="isLoading"
                color="primary" raised>
                Rekap Label</VButton>
            <!-- <VButton @click="modalPilihApoteker = true" icon="feather:printer" :loading="isLoading" color="primary" raised>Cetak
                </VButton> -->
        </template>
    </VModal>
    <Dialog
    v-model:visible="modalDataPaketObat"
    modal
    header="Paket Obat"
    :style="{ width: '60vw' }"
    maximizable
  >
    <div class="columns">
      <div class="column is-12">
        <DataTable
          :value="dataSourcePaketObat"
          :rows="5"
          :rowsPerPageOptions="[5, 10, 15]"
          class="p-datatable-sm"
          responsiveLayout="stack"
          breakpoint="960px"
          selectionMode="single"
          sortMode="multiple"
          showGridlines
          v-model:expanded-rows="expandedRows"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          paginator
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
        >
          <Column expander style="width: 5rem" />
          <Column field="no" header="No"></Column>
          <Column field="paketId" header="Nomor Paket"></Column>
          <Column field="namapaket" header="Nama Paket"></Column>
          <Column :exportable="false" header="#" style="text-align: center">
            <template #body="slotProps">
              <VIconButton
                v-tooltip.bottom.left="'Pilih Paket'"
                label="Bottom Left"
                color="primary"
                circle
                icon="pi pi-check-circle"
                @click="pilihPaketObat(slotProps.data)"
                style="margin-right: 15px"
                :loading="isloadingTambahPaket"
              />
            </template>
          </Column>
          <template #expansion="slotProps">
            <div class="p-3">
              <DataTable
                :value="slotProps.data.details"
                :rows="10"
                showGridlines
                class="p-datatable-sm"
                responsiveLayout="stack"
                breakpoint="960px"
                sortMode="multiple"
              >
                <Column field="no" header="No" />
                <Column field="namaproduk" header="Nama Produk" />
                <Column field="satuanresep" header="Satuan" />
                <Column field="jumlah" header="Jumlah" style="text-align: center" />
                <Column field="aturanpakai" header="Aturan Pakai" />
              </DataTable>
            </div>
          </template>
        </DataTable>
      </div>
    </div>
    </Dialog>

    <Dialog v-model:visible="isResep" header="Riwayat Resep" :style="{ width: '80vw' }">
        <RiwayatResep :pasien="props.pasien" :registrasi="props.registrasi" />
        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="isResep = false">
                Tutup
            </VButton>
        </template>
    </Dialog>


</template>

<script setup lang="ts">
import Dialog from 'primevue/dialog'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed,
    defineComponent,
    watch,
    nextTick,
    onMounted,
    reactive,
    watchEffect
} from 'vue'
import RiwayatResep from './riwayat-resep.vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import Checkbox from 'primevue/checkbox';
import { formatRp } from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import { useToaster } from '/@src/composable/toaster'
import { useConfirm } from "primevue/useconfirm"
import { useViewWrapper } from '/@src/stores/viewWrapper'
import PrimeVue from 'primevue/config';
import { useToast } from "primevue/usetoast";
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import AutoComplete from 'primevue/autocomplete';
import SplitButton from 'primevue/splitbutton';
import { useCurrencyInput } from 'vue-currency-input'
import moment from 'moment'
import TListResep from './t-list-resep.vue'
import * as qzService from '/@src/utils/qzTrayService'
const TITLE_PAGE = 'Obat Alkes'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let NOREC_PD = useRoute().query.norec_pd as string
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_APD = useRoute().query.norec_apd as string
let IS_NEWRESEP = useRoute().query.isnewresep ? useRoute().query.isnewresep as string : false
let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_RESEP: any = useRoute().query.norec_resep as string
let item: any = reactive({
    header: {},
    inacbgss:true,
    totalAll: 0,
    inacbgss:true,
    jumlah: 0,
    jumlahsementara: 0,
    persenDiskon: 0,
    // aturanPakai: [],
    hargadiskon: 0,
    tglAwal: new Date(),
    rke: 1,
    resep: '-',
    // dataresep:null,
    chkp: 0,
    checkisKronis: null,
    checkisKronisAll : null,
    chks: 0,
    chksr: 0,
    alergiobat: 'Tidak Ada Alergi',
    chkm: 0,
    issementara: false,
    isbud: false,
    hargaSatuan: 0,
    fornas:null,
    objectdetailjenisprodukfk:null
})
const isResep: any = ref(false)
const isloadingLAMPAU: any = ref(false)
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const modalInput = ref(false)
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const dataSourceStokProduk: any = ref([])
const isDisabledList = ref(false);
const d_penulisResep: any = ref([])
const d_ruangan: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_aturanPakai: any = ref([])
const d_kemasan: any = ref([])
const d_jenisRacikan: any = ref([])
const isloadingPaketObat: any = ref(false)
const d_route: any = ref([])
const d_tglKadaluarsa: any = ref([])
const d_tglpemakaian: any = ref([])
const confirm = useConfirm();
const d_satuanResep: any = ref([])
const d_asalProduk: any = ref([])
const dataSource: any = ref([])
const sourceDetailResep: any = ref([])
const listRiwayat = ref([])
const data2: any = ref([])
const dataOK: any = ref([])
const selectAllPengkajian = ref(false)
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const norecResep: any = ref('')
const isLoading: any = ref(false)
const toast = useToast();
const isLoadingSelect: any = ref(false)
const modalResepVerify: any = ref(false)
const isLoadInput: any = ref(false)
const isSimpan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const disabledRuangan: any = ref(false)
const showGridKronis: any = ref(false)
const infoStok: any = ref('List Informasi Stok')
const dataGridKronis: any = ref([])
const dataSelected: any = ref({})
const isPemakaianObatAlkes: any = ref(false)
const disTanggal: any = ref(false)
const checkResepPulang: any = ref(false)
const isEdit: any = ref(false)
const buttonReady: any = ref(true)
const expandedRows = ref()
const isloadingTambahPaket: any = ref(false)
const showRacikanDose: any = ref(false)
const Hide: any = ref(false)
const isMerge: any = ref(false)
const selectedResep = ref();
const isLastObatByDate: any = ref(false)
const modalDataPaketObat: any = ref(false)
const dataSourcePaketObat = ref([])
const isDisabledKronis = ref(false)
const isDisabledDiscount = ref(false)
const listDataSigna = ref([
    { "id": 1, "nama": "P", "isChecked": false },
    { "id": 2, "nama": "S", "isChecked": false },
    { "id": 3, "nama": "Sr", "isChecked": false },
    { "id": 4, "nama": "M", "isChecked": false }
])
const jumlah = computed(() => item.issementara ? 0 : item.jumlah);
const isQtyDisabled = ref(false);
const hargaSatuan = computed(() => item.issementara ? 0 : item.hargaSatuan);
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

watch(() => item.inacbgss, (val) => {
  console.log('item.inacbgss berubah:', val)
})

const aspekKiri = [
  { key: 'tepatpasien', label: 'Tepat Pasien' },
  { key: 'tepatobat', label: 'Tepat Obat' },
  { key: 'campuranobat', label: 'Campuran Obat Stabil' },
  { key: 'tepatdosis', label: 'Tepat Dosis / Kekuatan / Frekuensi' },
  { key: 'tepatrute', label: 'Tepat Rute Pemberian' }
]

const aspekKanan = [
  { key: 'duplikasiobat', label: 'Duplikasi Obat' },
  { key: 'interaksiobat', label: 'Interaksi Obat' },
  { key: 'jenisobatl5', label: 'Jenis Obat >= 5' },
  { key: 'kontraindikasi', label: 'Kontraindikasi' }
]
const semuaAspek = [...aspekKiri, ...aspekKanan]

const kajian = ref({})

;[...aspekKiri, ...aspekKanan].forEach(aspek => {
  kajian.value[aspek.key + 'Y'] = false
  kajian.value[aspek.key + 'T'] = false
})

function onCheckboxChange(aspekKey, type) {
  const yKey = aspekKey + 'Y'
  const tKey = aspekKey + 'T'
  if (type === 'Y' && kajian.value[yKey]) {
    kajian.value[tKey] = false
  } else if (type === 'T' && kajian.value[tKey]) {
    kajian.value[yKey] = false
  }
  selectAllPengkajian.value = false
}

const listJenisObatApotikOnline = ref([
  {
    id: 1,
    jenisobat: "Obat PRB"
  },
  {
    id : 2,
    jenisobat :"Obat Kronis Blm Stabil"
  },
  {
    id : 3,
    jenisobat :"Obat Kemoterapi"
  }
])

function cekSimpan()
{
  let totalbayarr = 0;
  var ada = false;
  for (let x = 0; x < data2.value.length; x++) 
  {
      const element = data2.value[x];
      const totalbayarnya = element.total
      totalbayarr += totalbayarnya;
      if(element.iskronis == true)
      {
        ada = true;
      }
  }
  
  if (item.header.kpid == 2 && totalbayarr > 50000 && ada == false) 
  {
    confirm1()
  }
  else
  {
    save()
  }
}

const confirm1 = () => {
    confirm.require({
      message: 'Nilai Resep Lebih Dari Rp. 50.000? Apakah Anda Ingin Melanjutkan ?',
      header: 'Konfirmasi Nilai Resep untuk Pasien BPJS',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Cancel',
        severity: 'secondary',
        outlined: true
      },
      acceptProps: {
        label: 'Save'
      },
      accept: () => {
        save(); 
      },
      reject: () => {
        toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
        return;  
      }
    });
  };

const paketObat = async () => {
  isloadingPaketObat.value = true
  let namaPaket = ''
  if (item.namapaket) namaPaket = `namapaket=${item.namapaket}`

  await useApi()
    .get(`/farmasi/data-paket-obat?${namaPaket}`)
    .then((response: any) => {
      isloadingPaketObat.value = false
      response.data.forEach((element: any, i: any) => {
        expandedRows.value = element.details.forEach((data: any, i: any) => {
          data.no = i + 1
        })
        element.no = i + 1
      })
      dataSourcePaketObat.value = response.data
      modalDataPaketObat.value = true
    })
    .catch((err: any) => {
      isloadingPaketObat.value = false
    })
}

function pilihPaketObat(e: any) {
  if (e.details.length == 0) {
    H.alert('error', 'Obat pada paket ini tidak ada')
    return
  }

  delete item.NOREC_SO
  //   item.tglorder.start = new Date()
  //   item.tglorder.end = new Date()
  e.details.forEach((element: any, i: any) => {
    isloadingTambahPaket.value = true
    let data: any = {}
    if (element.produkfk != undefined) {
      useApi()
        .get(
          '/farmasi/get-produkdetail?produkfk=' +
            element.produkfk +
            '&ruanganfk=' +
            item.ruangan.id +
            '&kpid=' +
            item.header.objectkelompokpasienlastfk +
            '&norec_apd=' +
            NOREC_APD
        )
        .then(function (response: any) {
        if (response.detail.length > 0) {
            dataProdukDetail.value = response.detail
            item.stok = response.jmlstok / 1
            if (response.kekuatan == undefined || response.kekuatan == 0) {
              response.kekuatan = 1
            }
            item.kekuatan = response.kekuatan
            item.sediaan = response.sediaan
            item.value.fornas = response.fornas
            item.objectdetailjenisprodukfk.value = response.objectdetailjenisprodukfk
            item.tglKadaluarsa = response.detail[0]
            if (dataSelected.value.no != undefined) {
              item.jumlah = (parseFloat(dataSelected.value.jumlah))
              item.dosis = parseFloat(dataSelected.value.dosis)
              item.jumlahxmakan =
                (parseFloat(item.jumlah) / parseFloat(item.dosis)) *
                parseFloat(item.kekuatan)
              item.nilaiKonversi = dataSelected.value.nilaikonversi
              d_satuan.value.forEach((element: any) => {
                if (element.ssid == dataSelected.value.satuanviewfk) {
                  item.satuan = element
                }
              })
              item.hargaSatuan = dataSelected.value.hargasatuan
              item.hargadiskon = dataSelected.value.hargadiscount
              item.hargaNetto = dataSelected.value.harganetto
              item.total = dataSelected.value.total
            } else {
              if (!isMerge.value) {
                item.jumlah = 1
                item.jumlahsementara = 0
              }
            }

            setNorecSPD()
            isLoading.value = false
          } else {
            if (response.jmlstok == 0) {
              useToaster().warn(`Stok ${element.namaproduk} belum ada`)
            }
            item.hargaSatuan = 0
            item.hargadiskon = 0
            item.stok = response.jmlstok
            item.hargaNetto = 0
            item.total = 0
            isLoading.value = false
          }

          data = {
            no: i + 1,
            noregistrasifk: NOREC_APD,
            generik: null,
            hargajual: String(item.hargaSatuan),
            jenisobatfk: item.jenisRacikan ? item.jenisRacikan.id : 10,
            jenisobat: item.jenisRacikan ? item.jenisRacikan.jenisracikan : null,
            kelasfk: item.header.objectkelasfk,
            stock: String(item.stok),
            harganetto: String(item.hargaNetto),
            nostrukterimafk:
              response.nostrukterimafk != undefined ? response.nostrukterimafk : null,
            norec_spd: response.norec != undefined ? response.norec : null,
            ruanganfk: item.ruangan.id,
            rke: i + 1,
            jeniskemasanfk: item.jenisKemasan.id,
            jeniskemasan: item.jenisKemasan.jeniskemasan,
            aturanpakaifk: 0, //item.aturanPakai.id,
            aturanpakai: element.aturanpakai, //item.aturanPakai.aturanpakai,
            ispagi: element.ispagi != undefined ? element.ispagi : null,
            issiang: element.issiang != undefined ? element.issiang : null,
            issore: element.issore != undefined ? element.issore : null,
            ismalam: element.ismalam != undefined ? element.ismalam : null,
            issementara: element.issementara != undefined ? element.issementara : null,
            asalprodukfk: item.asal != undefined ? item.asal.id : null,
            asalproduk: item.asal != undefined ? item.asal.asalproduk : null,
            produkfk: element.produkfk,
            namaproduk: element.namaproduk,
            nilaikonversi: item.nilaiKonversi,
            satuanstandarfk: element.objectsatuanstandarfk,
            satuanstandar: element.satuanstandar,
            satuanviewfk: item.satuan != undefined ? item.satuan.ssid : null,
            satuanview: item.satuan != undefined ? item.satuan.satuanstandar : null,
            jmlstok: String(item.stok),
            jumlah: element.jumlah, //item.jumlahbulat,
            jumlahsementara: item.jumlahsementara,
            jumlahobat: element.jumlah, //item.jumlah,
            dosis: item.dosisNA != undefined ? item.dosisNA : null,
            hargasatuan: String(item.hargaSatuan),
            hargadiscount: String(item.hargadiskon),
            persendiscount: item.persenDiskon ? item.persenDiskon : 0,
            total: item.total,
            jmldosis: String(element.jumlah) + '//' + String(element.kekuatan),
            jasa: item.jasa != undefined ? item.jasa : null,
            keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
            satuanresepfk: item.satuanresep ? item.satuanresep.id : null,
            satuanresep: item.satuanresep ? item.satuanresep.satuanresep : null,
            tglkadaluarsa: item.tglKadaluarsa ? item.tglKadaluarsa : null,
            routefk: null,
            route: null,
            iskronis: null,
          }
          data2.value.push(data)
          for (let i = 0; i < data2.value.length; i++) {
            const element = data2.value[i]
            if (element.iskronis == true) {
              element.obtkronis = '✔'
            } else {
              element.obtkronis = ''
            }
          }
        })
      modalDataPaketObat.value = false
      isloadingTambahPaket.value = false
    }
  })
  setColor()

  dataSource.value = data2.value
  if (item.jenisKemasan.jeniskemasan != 'Racikan') {
    item.rke = parseFloat(item.rke) + 1
  }
  countTotal()
  clearInput()
}

const loadRiwayatOld = async () => {
    isloadingLAMPAU.value = true
    let paramsPD = ``
    // if(isCPPTOLD.value == true){
    //      paramsPD = ``
    // }
    // await sleep(1000)
    useApi().get(
        `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then(async (response: any) => {

            isloadingLAMPAU.value = false
            if (response.length) {
                let dataOLD = []
                for (let x = 0; x < response.length; x++) {
                    const element = response[x];
                    element.show = false
                    if (element.registrasi.norec_pd != props.registrasi.norec_pd) {
                        for (let y = 0; y < element.details.length; y++) {
                            const element2 = element.details[y];
                            element2.tgl = H.formatDate(new Date(element2.tgl), 'YYYY-MM-DD HH:mm')
                            element2.tglVerifikasi = H.formatDate(new Date(element2.tglVerifikasi), 'YYYY-MM-DD HH:mm')
                        }

                        dataOLD.push(element)
                    }
                }

                input2.value = dataOLD
            }
            // if (props.pasien.enabledEMRSimrsLama == 'true') {
            let lokal = false;
            if (window.location.host.indexOf('192.168') > -1) {
                lokal = true;
            }

            isloadingLAMPAU.value = true
            let responseX = await useApi().get(`/emr/history-sim-lama?prefix=catatan_dokter&nocm=${props.pasien.nocm}&local=${lokal}`)
            isloadingLAMPAU.value = false
            let no = input2.value.length + 1
            for (let x = 0; x < responseX.length; x++) {
                const element = responseX[x];
                let dokterDPJP = null
                if (element.DokterPemeriksa && element.DokterPemeriksa != '' && element.DokterPemeriksa != null) {
                    await fetchDokter({ query: element.DokterPemeriksa.split(' ')[0] })
                    if (d_Dokter.value.length) {
                        for (let y = 0; y < d_Dokter.value.length; y++) {
                            const element2 = d_Dokter.value[y];
                            if (element2.label.toLowerCase().indexOf(element.DokterPemeriksa.split(' ')[0].toLowerCase()) > -1) {
                                dokterDPJP = element2
                                break
                            }
                        }
                    }
                }

                let pushOld = {
                    'show': false,
                    'registrasi': {
                        'noregistrasi': element.Nopendaftaran,
                        'tglregistrasi': element.TglMasuk,
                        'namaruangan': '',
                        'dokter': element.DokterPemeriksa
                    },
                    'noemr': '',
                    'details': [{
                        'no': no++,
                        'tgl': element.TglMasuk,
                        'S': element.CatatanS,
                        'O': element.CatatanO,
                        'flag': 'dokter',
                        'diagnosaDokter': [{
                            'keterangan': element.DiagnosaDokter
                        }],
                        'diagnosaDokter9': [{
                            'no': 1
                        }],
                        'P': element.CatatanP,
                        'dokterDPJP': dokterDPJP
                    }]
                }
                input2.value.push(pushOld)
            }
            // }
            if (input2.value.length == 0) {
                H.alert('warning', 'Data Tidak Ada')
            }
        })
}

const listButton: any = ref([

    {
        label: 'Cetak Resep',
        icon: 'fas fa-print',
        command: () => {
            cetakResep(item.dataresep)
        }
    },
    // {
    //     label: 'Cetak Resep 23',
    //     icon: 'fas fa-print',
    //     command: () => {
    //         cetakResep23(item.dataresep)
    //     }
    // },
    {
        label: 'Cetak Label Obat',
        icon: 'fas fa-print',
        command: () => {
            cetakLabel(item.dataresep);
        }
    },
    {
        label: 'Rekap Label',
        icon: 'fas fa-print',
        command: () => {
            showModalDetail(item.dataresep);
        }
    },

    {
        label: 'Simpan Pdf',
        icon: 'fas fa-print',
        command: () => {
            cetakResep2(item.dataresep)
        }
    },

])

function onSelectAllChange() {
  semuaAspek.forEach(aspek => {
    const isKiri = aspekKiri.some(a => a.key === aspek.key)
    kajian.value[aspek.key + 'Y'] = isKiri ? true : false
    kajian.value[aspek.key + 'T'] = !isKiri
  })
}

const getProdukListStok = async (e:any)=>{

    let namaproduk
    let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''
    await useApi().get(`farmasi/get-stok-produk-by-ruangan?produkfk=${e}${isDonasi}`).then((response)=>{
        response.forEach((element:any,i:any) => {
            element.no = i + 1
            namaproduk = element.namaproduk
        });
        dataSourceStokProduk.value = response
        infoStok.value = `List Stok Produk ${namaproduk} Per Ruangan`
    })

}

const cetakRekapLabel = async (e: any, isInjeksi: any) => {
    if (!e) {
        H.alert('error', 'Obat tidak boleh kosong')
        return
    }

    let items: any = []
    e.forEach((element: any) => {
        items = [...new Set([...items, element.produkfk])]
    });

    let ketLabel = isInjeksi == true ? 'LABEL BIRU RESEP' : 'LABEL RESEP'
    qzService.printData(`report/farmasi/label-custom?pdf=true&norec_resep=${item.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`, ketLabel, 1)
    // H.printBlade(`report/farmasi/label-custom?pdf=true&norec_resep=${item.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`)
}


const fetchProduk = async (filter: any) => {
    let isDonasi = item.checkisDonasi || filter.query ? `&isdonasi=true` : '';
    const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&ruanganfk=${item.ruangan.id}${isDonasi}&limit=30`)
    d_produk.value = response
}
const onInit = async () => {
    item.loading = false
    const response = await useApi().get(`/farmasi/input-resep-header?norec_apd=${NOREC_APD}`)
    item.loading = true
    item.header = response.data
    loadDrop()
}

const loadDrop = async () => {

    const response = await useApi().get(`/farmasi/input-resep-cbo?isnewresep=${IS_NEWRESEP}&departemenfk=${item.header.objectdepartemenfk}`)
    d_aturanPakai.value = response.signa.map((e: any) => { return { aturanpakai: e.signa, id: e.id } })
    d_kemasan.value = response.jeniskemasan
    // d_produk.value = response.produk//.map((e: any) => { return { label: e.namaproduk, value: e } })
    const response2 = await useApi().get(`/logistik/kartu-stok-cbo`)
    d_ruangan.value = response2.ruangan.map((e: any) => ({ label: e.namaruangan, id: e.id }));
    // d_ruangan.value = response.ruanganFarmasi.filter((e: any) => e.id !== 343);//.map((e: any) => { return { label: e.namaruangan, value: e } })
    d_penulisResep.value = response.penulisresep//.map((e: any) => { return { label: e.namalengkap, value: e } })
    d_jenisRacikan.value = response.jenisracikan//.map((e: any) => { return { label: e.jenisracikan, value: e } })
    d_route.value = response.route//.map((e: any) => { return { label: e.name, value: e } })
    d_satuanResep.value = response.satuanresep//.map((e: any) => { return { label: e.satuanresep, value: e } })
    d_asalProduk.value = response.asalproduk//.map((e: any) => { return { label: e.asalproduk, value: e } })
    if (d_aturanPakai.value[0])
        item.aturanPakai = d_aturanPakai.value[0]
    if (d_kemasan.value[1])
        item.jenisKemasan = d_kemasan.value[1]
    item.tarifadminresep = response.tarifadminresep ? response.tarifadminresep : 0
    item.tarifWNI = response.tarifWNI ? response.tarifWNI : 0
    item.tarifKitas = response.tarifKitas ? response.tarifKitas : 0
    item.tarifNonKitas = response.tarifNonKitas ? response.tarifNonKitas : 0
    disabledRuangan.value = false;

    if (NOREC_RESEP != undefined) {
        NOREC_ORDER = 'EditResep'
        loadEdit()
    }

    if (NOREC_ORDER != undefined) {
        loadEdit()
    }

}

const loadEdit = async () => {
    delete item.ruangan
    if (NOREC_ORDER != '') {
        isEdit.value = true
        if (NOREC_ORDER == 'EditResep') {
            isSimpan.value = true;
            isLoading.value = true
            const response = await useApi().get(`/farmasi/input-resep-edit?norecResep=${NOREC_RESEP}`)

            isLoading.value = false
            isSimpan.value = false;
            isEdit.value = false
            isQtyDisabled.value = true;
            // disabledRuangan.value = true;
            let headerRESEP = response.detailresep
            let detailRESEP = response.pelayananPasien
            let pasienn = response.pelayananPasien[0]


            if (pasienn) {
                semuaAspek.forEach(aspek => {
                    const nilai = pasienn[aspek.key] 
                    kajian.value[aspek.key + 'Y'] = nilai === true
                    kajian.value[aspek.key + 'T'] = nilai === false
                })
            }

            item.penulisResep = [];
            item.resep = headerRESEP.noresep


            d_ruangan.value.forEach((element: any) => {
                if (headerRESEP.id == element.id) {
                    item.ruangan = element
                }
            });


            item.penulisResep = { id: headerRESEP.pgid, namalengkap: headerRESEP.namalengkap }
            item.tglAwal = new Date(headerRESEP.tglresep);
            var resep = headerRESEP.noresep.split("/");
            var bulanNow = moment(new Date()).format('MM');
            if (resep[1].substr(2) != bulanNow) {
                useToaster().warn('Tanggal Resep Tidak Dapat Diubah (Hanya dapat diubah dibulan yang sama)')
                disTanggal.value = true;
            }
            if (detailRESEP.isreseppulang == '1') { checkResepPulang.value = true } else { checkResepPulang.value = false }

            data2.value = detailRESEP
            for (var i = data2.value.length - 1; i >= 0; i--) {
                const element = data2.value[i]
                if (element.iskronis == true)
                    element.obtkronis = "✔"
                else
                    element.obtkronis = ""
                element.noregistrasifk = NOREC_APD
                element.tglregistrasi = item.header.tglregistrasi
                element.kelasfk = data2.value[0].kelasfk

            }
            dataSource.value = data2.value

        } else {
            isLoadingSelect.value = true
            isSimpan.value = true;
            isLoading.value = true
            const response = await useApi().get(`/farmasi/input-resep-order?norec=${NOREC_ORDER}&nocmfk=${ID_PASIEN}&norec_apd=${NOREC_APD}&norec_pd=${NOREC_PD}`)
            isSimpan.value = false;
            isLoading.value = false
            isEdit.value = false
            // disabledRuangan.value = true;
            if (response.strukorder == null) {
                H.alert('warning', 'Order sudah diverifikasi')
                return
            }



            let headerRESEP = response.strukorder
            let detailRESEP = response.orderpelayanan
            item.cito = headerRESEP.cito || false;
            item.isrutin = headerRESEP.isrutin || false;
            item.isbpl = headerRESEP.isbpl || false;
            item.issementara = headerRESEP.issementara || false;
            item.alergiobat = headerRESEP.alergiobat;
            item.penulisResep = [];
            d_ruangan.value.forEach((element: any) => {
                if (headerRESEP.id == element.id) {
                    item.ruangan = element
                }
            });
            item.penulisResep = { id: headerRESEP.pgid, namalengkap: headerRESEP.namalengkap }
            isLoadingSelect.value = false
            if (headerRESEP.isreseppulang == '1') { checkResepPulang.value = true } else { checkResepPulang.value = false }
            data2.value = detailRESEP
            for (var i = data2.value.length - 1; i >= 0; i--) {
                const element = data2.value[i]
                element.noregistrasifk = NOREC_APD
                
                element.tglregistrasi = item.header.tglregistrasi
                element.kelasfk = item.header.klsid
                if (element.nilaikonversi == 0)
                    element.nilaikonversi = 1
                if (element.iskronis == "1") {
                    var qtyOK: any = 0;
                    element.jumlahreal = parseFloat(element.jumlah);
                    qtyOK = (parseFloat(element.jumlah) * 7) / 30
                    element.jumlah = qtyOK;
                    element.jumlahobat = qtyOK;
                    element.jumlahcetak = parseFloat(element.jumlahreal) - qtyOK;
                    element.total = (parseFloat(qtyOK) * (parseFloat(element.hargasatuan) - parseFloat(element.hargadiscount)) * parseFloat(element.nilaikonversi)) + parseFloat(element.jasa);
                    dataOK.value.push(data2[i]);
                }
                if(element.iskronis30 == true || element.iskronis23 == true)
                {
                    if(element.iskronis30 == true)
                    {
                        element.kronis30 = true   
                        element.iskronis = true
                    }
                    else if(element.iskronis23 == true)
                    {
                        element.kronis30 = false
                        element.iskronis = true
                    }
                }
                else 
                {
                    element.iskronis = false
                }
            }
            for (let x = 0; x < dataOK.value.length; x++) {
                const element = dataOK.value[x];
                element.no = x + 1;
            }
            for (let j = 0; j < data2.value.length; j++) {
                const element = data2.value[j];
                if (element.iskronis == true) {
                    element.obtkronis = "✔"
                } else {
                    element.obtkronis = ""
                }
            }
            setColor()
            dataSource.value = data2.value
            item.totalbayar = response.totalbayar
            countTotal()

        }
    }

    isSimpan.value = false
}

const setColor = () => {
    let z = 0
    for (let zx = 0; zx < data2.value.length; zx++) {
        const element = data2.value[zx];
        element.icon = 'fa-inverse lnir lnir-medicine-alt'
        element.color = listColor2.value[z]
        if (z > 4) {
            z = 0
        }
        z++
    }
    z = 0

    for (let zx = 0; zx < dataGridKronis.value.length; zx++) {
        const element = dataGridKronis.value[zx];
        element.icon = 'fa-inverse lnir lnir-medicine-alt'
        element.color = listColor2.value[z]
        if (z > 4) {
            z = 0
        }
        z++
    }
}

const addPopUp = () => {
    if (!item.ruangan) {
        useToaster().error('Ruangan harus di pilih')
        return
    }

    if (NOREC_APD) {
        dataSource.value.forEach((element: any, i: any) => {
            if (dataSource.value.length - 1 == i) {
                d_kemasan.value.forEach((e: any) => {
                    if (e.id == element.jeniskemasanfk) {
                        item.jenisKemasan = e
                        return
                    }
                });
                if (element.jeniskemasanfk == 1) {
                    item.rke = element.rke
                } else {
                    item.rke = parseInt(element.rke) + 1
                }
            }
        });
    }

    clearInput()
    modalInput.value = true
    nextTick(() => {
    item.inacbgss = true
})
}
const add = () => {
    if (!item.produk) {
        useToaster().error('Produk harus di isi')
        return
    }

    if(item.jenisKemasan.id == 1 && !item.jenisRacikan)
    {
        useToaster().error(`Jenis Racikan Belum Dipilih`);
        return;
    }
    


    if (parseFloat(item.jumlah) % 1 !== 0) {
            useToaster().error(`TERDAPAT QTY DESIMAL PADA OBAT`);
            return;
        }
    
    if (!item.jumlah && (!item.jumlahsementara || item.jumlahsementara < 0)) {
        useToaster().error('Jumlah dan Jumlah Sementara tidak boleh keduanya 0');
        return;
    }


    if (item.checkisDonasi !== true && item.hargaSatuan === undefined) {    
        useToaster().error('Harga Satuan belum ada');
        return;
    }

    if (item.checkisDonasi !== true && (item.hargaSatuan === undefined || item.hargaSatuan < 0)) {
        useToaster().error('Harga Satuan belum ada atau tidak valid');
        return;
    }

    if (item.stok < 0) {
        useToaster().error('Stok tidak ada')
        return
    }

    if (!item.satuan) {
        useToaster().error('Satuan harus di isi')
        return
    }
    if (!item.satuan) {
        useToaster().error('Satuan harus di isi')
        return
    }
    if (!item.aturanpakaitxt) {
        useToaster().error('Aturan Pakai harus di isi')
        return
    }
    var dosis = 1;
    if (item.jenisKemasan.jeniskemasan == 'Racikan') {
        if (!item.dosis) {
            useToaster().error('Dosis harus di isi')
            return
        }
        dosis = item.dosis
        item.jumlahxmakan = item.jumlahxmakan//Math.floor((parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan))
    } else {
        item.jumlahxmakan = item.jumlah
    }
    let nomor = 0
    if (data2.value.length == 0) {
        nomor = 1
    } else {
        nomor = data2.value.length + 1
    }

    if (item.jumlah < 0 && item.jumlahsementara < 0) {
    } else if (item.jumlah < 0 && item.jumlahsementara < 0) {
    } else if (item.jumlah < 0 && item.jumlahsementara < 0) {
        useToaster().error('Jumlah dan Jumlah Sementara tidak boleh keduanya 0');
        return;
    }
    var qtyOK: any = 0;
    var qtyCetak = 0;
    var total = 0;
    var jumlahreal = 0;

    let jmlbulat = 0;
    let jml = 0;

    jmlbulat = item.jumlahbulat;
    jml = item.jumlah;
    
    const existingItem = dataGridKronis.value.find(existing => existing.no === item.no);
    
    if (item.checkisKronis == true) 
    {
        var datas: any = {};
        showGridKronis.value = true
        
        jumlahreal = parseFloat(item.jumlah);
        qtyOK = Math.ceil((parseFloat(item.jumlah) * 23) / 30)
        jml = Math.floor(parseFloat(item.jumlah) - qtyOK);
        jmlbulat = qtyOK;
        qtyCetak = jumlahreal - jml;

        total = (parseFloat(qtyOK) * (parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon)) * parseFloat(item.nilaiKonversi)) + parseFloat(tarifJasa.value);

        if (existingItem)
        {
            existingItem.iskronis = item.checkisKronis ? item.checkisKronis : null;
            existingItem.no = item.no
            existingItem.noregistrasifk = NOREC_APD
            existingItem.generik = null
            existingItem.hargajual = String(item.hargaSatuan)
            existingItem.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : null
            if (existingItem.jeniskemasanfk === 2) {
                    existingItem.jenisobatfk = 10;
                } else if (item.jeniskemasanfk === 1) {
                    existingItem.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : null
                } else {
                    existingItem.jenisobatfk = 10;
                }
            existingItem.jenisobat = item.jenisRacikan ? item.jenisRacikan.jenisracikan : null
            existingItem.kelasfk = item.header.klsid
            existingItem.stock = String(item.stok)
            existingItem.harganetto = String(item.hargaNetto)
            existingItem.nostrukterimafk = norecTerima.value
            existingItem.norec_spd = norecSPD.value
            existingItem.ruanganfk = item.ruangan.id
            existingItem.rke = item.rke
            existingItem.jeniskemasanfk = item.jenisKemasan.id
            existingItem.jeniskemasan = item.jenisKemasan.jeniskemasan
            existingItem.aturanpakai = item.aturanpakaitxt 
            existingItem.aturanpakaifk = 0
            existingItem.ispagi = item.chkp
            existingItem.issiang = item.chks
            existingItem.issore = item.chksr
            existingItem.ismalam = item.chkm
            existingItem.issementara = item.issementara
            existingItem.lastorder = item.lastorder != undefined ? item.lastorder : element.lastorder
            existingItem.jumlahlast = item.jumlahlast != undefined ? item.lastorder : element.jumlahlast
            existingItem.routefk = item.route ? item.route.id : null
            existingItem.route = item.route ? item.route.name : null
            existingItem.asalprodukfk = item.asal.id
            existingItem.asalproduk = item.asal.asalproduk
            existingItem.produkfk = item.produk.id
            existingItem.namaproduk = item.produk.namaproduk
            existingItem.kdproduk = item.produk.kdproduk
            existingItem.productname = item.produk.productname
            existingItem.nilaikonversi = item.nilaiKonversi
            existingItem.satuanstandarfk = item.satuan.ssid
            existingItem.satuanstandar = item.satuan.satuanstandar
            existingItem.satuanviewfk = item.satuan.ssid
            existingItem.satuanview = item.satuan.satuanstandar
            existingItem.jmlstok = String(item.stok)
            existingItem.jumlah = jml
            existingItem.isbud = item.isbud
            existingItem.tglpemakaian = item.tglpemakaian
            existingItem.jumlahobat = jml
            existingItem.dosis = dosis
            existingItem.hargasatuan = String(item.hargaSatuan)
            existingItem.hargadiscount = String(item.hargadiskon)
            existingItem.total = parseFloat(jml)*parseFloat(item.hargaSatuan)
            existingItem.jmldosis = String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan)
            existingItem.jasa = tarifJasa.value
            existingItem.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null
            if (existingItem.jeniskemasanfk.id === 2) {
                    existingItem.racikan = 1;
                } else if (item.jeniskemasanfk === 1) {
                    existingItem.racikan = existingItem.jumlahxmakan;
                } else {
                    existingItem.racikan = 1;
                }
            existingItem.racikan = item.jumlahxmakan ? item.jumlahxmakan : null 
        } 

        else 
        {
            datas = {
                no: item.no ? item.no : nomor,
                noregistrasifk: NOREC_APD,
                generik: null,
                hargajual: String(item.hargaSatuan),
                jenisobatfk: item.jenisRacikan ? item.jenisRacikan.id : 10,
                jenisobat: item.jenisRacikan ? item.jenisRacikan.jenisracikan : null,
                kelasfk: item.header.klsid,
                stock: String(item.stok),
                harganetto: String(item.hargaNetto),
                norec_spd: norecSPD.value,
                nostrukterimafk: norecTerima.value,
                ruanganfk: item.ruangan.id,
                rke: item.rke,
                jeniskemasanfk: item.jenisKemasan.id,
                jeniskemasan: item.jenisKemasan.jeniskemasan,
                aturanpakaifk: 0,
                aturanpakai: item.aturanpakaitxt,
                ispagi: item.chkp,
                issiang: item.chks,
                lastorder: item.lastorder != undefined ? item.lastorder : element.lastorder,
                jumlahlast: item.jumlahlast != undefined ? item.jumlahlast : element.jumlahlast,
                issore: item.chksr,
                ismalam: item.chkm,
                issementara: item.issementara,
                jumlahsementara: item.jumlahsementara,
                iskronis:item.checkisKronis ? item.checkisKronis : null,
                routefk: null,
                route: null,
                asalprodukfk: item.asal.id,
                asalproduk: item.asal.asalproduk,
                produkfk: item.produk.id,
                namaproduk: item.produk.namaproduk,
                kdproduk: item.produk.kdproduk,
                productname: item.produk.productname,
                nilaikonversi: item.nilaiKonversi,
                satuanstandarfk: item.satuan.ssid,
                satuanstandar: item.satuan.satuanstandar,
                satuanviewfk: item.satuan.ssid,
                satuanview: item.satuan.satuanstandar,
                jmlstok: String(item.stok),
                jumlah: Math.floor((parseFloat(item.jumlah) * 7) / 30),//qtyOK,
                jumlahobat: qtyOK,
                jumlahcetak: qtyOK,
                dosis: dosis,
                hargasatuan: String(item.hargaSatuan),
                hargadiscount: String(item.hargadiskon),
                total: parseFloat(Math.floor((parseFloat(item.jumlah) * 7) / 30))*parseFloat(item.hargaSatuan),
                jmldosis: String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan),
                jasa: tarifJasa.value,
                isbud: item.isbud,
                tglpemakaian: item.tglpemakaian ? item.tglpemakaian : null,
                keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
                racikan: (item.jenisKemasan.id === 2) ? 1 : (item.jenisKemasan.id === 1) ? item.jumlahxmakan : 1,
            };
            dataGridKronis.value.push(datas);
            dataOK.value.push(datas);
            
            // for (let i = 0; i < dataSource.value.length; i++) {
            //     const s = dataSource.value[i];
            //     for (let m = 0; m < dataSource.value.length; m++) {
            //         const xx = dataSource.value[m];
            //         if (s.rke === xx.rke && s.rke == item.rke ) 
            //         {
            //             s.jumlah = Math.floor((parseFloat(s.jumlah) * 7) / 30)
            //             s.hargasatuan = String(item.hargaSatuan)
            //             s.total = (parseFloat(s.jumlah) * (parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon)) * parseFloat(item.nilaiKonversi)) + parseFloat(tarifJasa.value);
            //             dataGridKronis.value.push(datas);
            //             dataOK.value.push(datas);
            //             break; 
            //         }
            //     }
            // }            
        }  
    }

    if(item.checkisKronisAll == true)
    {
        
    }

    disabledRuangan.value = true;
    var data: any = {};
    if (item.no != undefined) 
    {
        for (let x = 0; x < data2.value.length; x++) {
            const element = data2.value[x];
            if (element.no == item.no) {
                data.no = item.no
                data.noregistrasifk = NOREC_APD
                data.generik = null
                data.hargajual = item.hargaSatuan
                data.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : 10
                data.jenisobat = item.jenisRacikan ? item.jenisRacikan.jenisracikan : null
                data.kelasfk = item.header.klsid
                data.stock = item.stok
                data.harganetto = item.hargaNetto
                data.nostrukterimafk = norecTerima.value
                data.norec_spd = norecSPD.value
                data.ruanganfk = item.ruangan.id
                data.rke = item.rke
                data.kronis30 = item.checkisKronisAll ? item.checkisKronisAll : null
                data.jeniskemasanfk = item.jenisKemasan.id
                data.jeniskemasan = item.jenisKemasan.jeniskemasan
                data.aturanpakai = item.aturanpakaitxt 
                data.aturanpakaifk = 0
                data.ispagi = item.chkp
                data.issiang = item.chks
                data.issore = item.chksr
                data.ismalam = item.chkm
                data.issementara = item.issementara
                data.jumlahsementara = item.jumlahsementara
                data.lastorder = item.lastorder != undefined ? item.lastorder : element.lastorder
                data.jumlahlast = item.jumlahlast != undefined ? item.jumlahlast : element.jumlahlast
                data.iskronis = item.checkisKronis || item.checkisKronisAll || null
                data.isdonasi = item.checkisDonasi
                data.routefk = item.route ? item.route.id : null
                data.route = item.route ? item.route.name : null
                data.asalprodukfk = item.asal.id
                data.asalproduk = item.asal.asalproduk
                data.produkfk = item.produk.id
                data.namaproduk = item.produk.namaproduk
                data.kdproduk = item.produk.kdproduk
                data.productname = item.produk.productname
                data.nilaikonversi = item.nilaiKonversi
                data.satuanstandarfk = item.satuan.ssid
                data.satuanstandar = item.satuan.satuanstandar
                data.satuanviewfk = item.satuan.ssid
                data.satuanview = item.satuan.satuanstandar
                data.jmlstok = item.stok
                data.jumlah = jmlbulat
                data.jumlahobat = jmlbulat
                data.jumlah2 = jml ? jml : 0
                data.dosis = dosis
                data.hargasatuan = String(item.hargaSatuan)
                data.hargadiscount = String(item.hargadiskon)
                data.persendiscount = item.persenDiskon ? item.persenDiskon : 0
                data.total = parseFloat(jmlbulat)*parseFloat(item.hargaSatuan)//item.total
                data.jmldosis = String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan)
                data.kekuatan = item.kekuatan,
                data.isbud = item.isbud
                data.tglpemakaian = item.tglpemakaian ? item.tglpemakaian : null
                data.jasa = tarifJasa.value
                data.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null
                data.satuanresepfk = item.satuanresep ? item.satuanresep.id : null
                data.satuanresep = item.satuanresep ? item.satuanresep.satuanresep : null
                data.tglkadaluarsa = item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null
                if (item.jenisKemasan.id === 2) {
                    data.racikan = 1;
                } else if (item.jenisKemasan.id === 1) {
                    data.racikan = item.jumlahxmakan;
                } else {
                    data.racikan = 1;
                }

                data2.value.forEach((el) => {
                if (el.rke === item.rke) {
                    if (item.jenisKemasan.id === 2) {
                        el.racikan = 1;
                    } else if (item.jenisKemasan.id === 1) {
                        el.racikan = item.jumlahxmakan;
                    } else {
                        el.racikan = 1;
                    }
                    el.aturanpakai = item.aturanpakaitxt;
                    el.iskronis = item.checkisKronis || item.checkisKronisAll || null;
                    el.ispagi = item.chkp;
                    el.issiang = item.chks;
                    el.issore = item.chksr;
                    el.ismalam = item.chkm;
                    el.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null;
                    el.jeniskemasanfk = item.jenisKemasan.id;
                    el.jeniskemasan = item.jeniskemasan
                    el.aturanpakaifk = 0; 
                    el.routefk = item.route ? item.route.id : null;
                    el.route = item.route;
                    el.tglkadaluarsa = item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null;
                    el.satuanresep = item.satuanresep ? item.satuanresep.satuanresep : null;
                    el.satuanresepfk = item.satuanresep ? item.satuanresep.id : null;
                    el.isbud = item.isbud ? item.isbud : null;
                    el.tglpemakaian = item.tglpemakaian ? item.tglpemakaian : null;
                    el.jumlahcetak = item.qtyOK ? item.qtyOK : 0;
                    el.jenisobat = item.jenisobat;
                    el.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.id : 10
                    
                }
            });
            data2.value[x] = data;

            for (let i = 0; i < data2.value.length; i++) {
                const element = data2.value[i];
                if (element.iskronis === true) {
                    element.obtkronis = "✔";
                } else {
                    element.obtkronis = "";
                }
            }
        }
    }
}
  
    else {
        if (data2.length > 0) {
            var racikan = data2.value[data2.value.length - 1].jeniskemasan
            if (racikan == 'Non Racikan') {
                item.rke = data2.value[data2.value.length - 1].rke + 1
            }
        }
        data = {
            no: nomor,
            noregistrasifk: NOREC_APD,
            generik: null,
            hargajual: String(item.hargaSatuan),
            jenisobatfk: item.jenisRacikan ? item.jenisRacikan.id : 10,
            jenisobat: item.jenisRacikan ? item.jenisRacikan.jenisracikan : null,
            kelasfk: item.header.klsid,
            stock: String(item.stok),
            harganetto: String(item.hargaNetto),
            nostrukterimafk: norecTerima.value,
            norec_spd: norecSPD.value,
            ruanganfk: item.ruangan.id,
            rke: item.rke,
            jeniskemasanfk: item.jenisKemasan.id,
            jeniskemasan: item.jenisKemasan.jeniskemasan,
            aturanpakaifk: 0,
            aturanpakai: item.aturanpakaitxt,
            ispagi: item.chkp,
            issiang: item.chks,
            issore: item.chksr,
            ismalam: item.chkm,
            kronis30:item.checkisKronisAll ? item.checkisKronisAll : null,
            jumlah2:jml ? jml : 0,
            issementara: item.issementara,
            iskronis: item.checkisKronis || item.checkisKronisAll || null,
            isdonasi: item.checkisDonasi,
            jumlahsementara: item.jumlahsementara,
            lastorder: item.lastorder != undefined ? item.lastorder : '',
            jumlahlast: item.jumlahlast != undefined ? item.jumlahlast : '',
            routefk: item.route ? item.route.id : null,
            route: item.route ? item.route.name : null,
            asalprodukfk: item.asal.id,
            asalproduk: item.asal.asalproduk,
            produkfk: item.produk.id,
            namaproduk: item.produk.namaproduk,
            kdproduk: item.produk.kdproduk,
            productname: item.produk.productname,
            nilaikonversi: item.nilaiKonversi,
            satuanstandarfk: item.satuan.ssid,
            satuanstandar: item.satuan.satuanstandar,
            satuanviewfk: item.satuan.ssid,
            satuanview: item.satuan.satuanstandar,
            jmlstok: String(item.stok),
            jumlah: jmlbulat,//item.jumlahbulat,
            jumlahobat: jmlbulat,//item.jumlah,
            dosis: dosis,
            hargasatuan: String(item.hargaSatuan),
            hargadiscount: String(item.hargadiskon),
            persendiscount: item.persenDiskon ? item.persenDiskon : 0,
            total: item.total,
            jmldosis: String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan),
            kekuatan: item.kekuatan,
            jasa: tarifJasa.value,
            keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
            satuanresepfk: item.satuanresep ? item.satuanresep.id : null,
            satuanresep: item.satuanresep ? item.satuanresep.satuanresep : null,
            tglkadaluarsa: item.tglKadaluarsa ? item.tglKadaluarsa.tglkadaluarsa : null,
            isbud: item.isbud,
            tglpemakaian: item.tglpemakaian ? item.tglpemakaian : null,
            racikan: (item.jenisKemasan.id === 2) ? 1 : (item.jenisKemasan.id === 1) ? item.jumlahxmakan : 1,
        }
        data2.value.push(data)
        for (let i = 0; i < data2.value.length; i++) {
            const element = data2.value[i];
            if (element.iskronis == true) {
                element.obtkronis = "✔"
            } else {
                element.obtkronis = ""
            }
        }

    }
    setColor()
    dataSource.value = data2.value
    if (item.jenisKemasan.jeniskemasan != 'Racikan') {
        //item.rke = parseFloat(item.rke) + 1
        item.rke = Math.max(...data2.value.map(item => item.rke)) + 1;
    }

    countTotal()
    clearInput()
}

const countTotal = () => {
    let total = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        total = total + parseFloat(element.total)
    }
    TOTAL.value = formatRp(total, '')
}
const countTotalSub = () => {
    item.total = ((item.qty ? item.qty : 0) * (item.harga
        ? item.harga : 0)) - (item.diskon ? item.diskon : 0)
}

const showModalDetail = async (e: any) => {

    modalResepVerify.value = true
    sourceDetailResep.value = dataSource.value
}


const editRow = async (e: any) => {
  e.btnLoading = true
  item.no = e.no
  item.rke = e.rke
  item.namaproduk = e.namaproduk
  item.issementara = e.issementara
  item.isbud = e.isbud
  item.tglpemakaian = e.tglpemakaian
  item.jumlahsementara = e.jumlahsementara
  d_kemasan.value.forEach((element: any) => {
    if (element.id == e.jeniskemasanfk) {
      item.jenisKemasan = element
      return
    }
  });
  d_satuanResep.value.forEach((element: any) => {
    if (element.id == e.satuanresepfk) {
      item.satuanresep = element
      return
    }
  });
  d_aturanPakai.value.forEach((element: any) => {
    if (element.id == e.aturanpakaifk) {
      item.aturanPakai = element
      return
    }
  });
  d_jenisRacikan.value.forEach((element: any) => {
    if (element.id == e.jenisobatfk) {
      item.jenisRacikan = element
      return
    }
  });
  d_route.value.forEach((element: any) => {
    if (element.id == e.routefk) {
      item.route = element
      return
    }
  })
  item.aturanpakaitxt = e.aturanpakai
  item.chkp = 0
  item.chks = 0
  item.chksr = 0
  item.chkm = 0
  listDataSigna.value = []
  let sp = false
  if (e.ispagi != "0") {
    sp = true
    item.chkp = 1
  }
  let ss = false
  if (e.issiang != "0") {
    ss = true
    item.chks = 1
  }
  let sr = false
  if (e.issore != "0") {
    sr = true
    item.chksr = 1
  }
  let sm = false
  if (e.ismalam != "0") {
    sm = true
    item.chkm = 1
  }
  listDataSigna.value = [
    { "id": 1, "nama": "P", 'isChecked': sp },
    { "id": 2, "nama": "S", 'isChecked': ss },
    { "id": 3, "nama": "Sr", 'isChecked': sr },
    { "id": 4, "nama": "M", 'isChecked': sm }
  ]
  item.KeteranganPakai = e.keterangan
  item.persenDiskon = e.persendiscount

  if (e.issementara == true) {
    item.issementara = true
  } else {
    item.issementara = false
  }

  if (e.isbud == true) {
    item.isbud = true
  } else {
    item.isbud = false
  }

  if (e.iskronis == true) 
  {
    if(e.kronis30 == true)
    {
        item.checkisKronisAll = true
    }
    else
    {
        item.checkisKronis = true
    }
  }
  else 
  {
    item.inacbgss = true
    item.checkisKronis = false
    item.checkisKronisAll = false

  }

  if (e.asalprodukfk) {
    d_asalProduk.value.forEach((element: any) => {
      if (element.id == e.asalprodukfk) {
        item.asal = element
        return
      }
    })
  }
  await fetchProduk({ query: e.namaproduk })
  d_produk.value.forEach((element: any) => {
    if (element.id == e.produkfk) {
      item.produk = element
      return
    }
  })


  tarifJasa.value = e.jasa
  dataSelected.value = e
  if(item.checkisKronis == true) 
    {
        dataSelected.jumlah = Math.ceil(parseFloat(e.jumlah)) 
        // dataSelected.value.jumlah = Math.ceil(parseFloat(e.jumlahobat)+parseFloat(e.jumlah2))
        dataSelected.value.jumlahobat = parseFloat(e.jumlahobat) + parseFloat(e.jumlah2 ? e.jumlah2 : 0);
    }
   else
    {
        dataSelected.jumlah = Math.ceil(parseFloat(e.jumlahobat)) 
        dataSelected.jumlahobat = e.jumlahobat
    }
  

  GETKONVERSI()
  modalInput.value = true
  e.btnLoading = false
}

const hapusRow = (e: any) => {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].namaproduk == e.namaproduk) {
            data2.value.splice(i, 1);

        }
    }
    for (var i = dataOK.value.length - 1; i >= 0; i--) {
        if (dataOK.value[i].namaproduk == e.namaproduk) {
            dataOK.value.splice(i, 1);
        }
    }
    dataSource.value = data2.value
    dataGridKronis.value = dataOK.value
    countTotal()
    clearInput()
}

const save = async () => {
    isSimpan.value = true
    // isSimpan.value = false
    isDisabledList.value = true
    if (!item.penulisResep) {
        useToaster().error('Penulis Resep harus di pilih')
        isSimpan.value = false
        return
    }

    if (NOREC_ORDER !== 'EditResep' && NOREC_ORDER !== undefined ) 
    {
        const response = await useApi().get(`/farmasi/input-resep-order?norec=${NOREC_ORDER}&nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`);
        if (response.strukorder == null) {
            await H.statusClosingPasien(item.header.noregistrasi);    
            H.alert('warning', 'Order sudah diverifikasi');
            isSimpan.value = false
            return;
        }
    }

    for (let i = 0; i < data2.value.length; i++) {
        if (parseFloat(data2.value[i].jumlah) % 1 !== 0) {
            useToaster().error(`TERDAPAT QTY DESIMAL PADA OBAT : ${data2.value[i].namaproduk}`);
            isSimpan.value = false
            return;
        }
    }

    if (!item.ruangan) {
        useToaster().error('Ruangan Harus di pilih ')
        isSimpan.value = false
        return
    }
    if (data2.value.length == 0) {
        useToaster().error('Produk belum di pilih')
        isSimpan.value = false
        return
    }
    for (var i = data2.value.length - 1; i >= 0; i--) {
    
    }

    if (NOREC_RESEP == undefined)
    {
        for (var i = data2.value.length - 1; i >= 0; i--) {
            if (parseFloat(data2.value[i].jmlstok) < parseFloat(data2.value[i].jumlah)) {
                useToaster().error("Terdapat obat dengan jumlah melebihi STOK !! " + data2.value[i].namaproduk)
                isDisabledList.value = false;
                isSimpan.value = false
                return
            }
        }
    }
    
    let objSave =
    {
        'strukresep': {
            'tglresep': moment(item.tglAwal).format('YYYY-MM-DD HH:mm:ss'),
            'tglregistrasi': item.header.tglregistrasi,
            'noregistrasi': item.header.noregistrasi,
            'idruanganorder': item.header.objectruanganfk,
            'pasienfk': NOREC_APD,
            'nocm': item.header.nocm,
            'namapasien': item.header.namapasien,
            'penulisresepfk': item.penulisResep.id,
            'ruanganfk': item.ruangan.id,
            'noorder': NOREC_ORDER ? NOREC_ORDER : '',
            'norecResep': NOREC_RESEP ? NOREC_RESEP : '',
            'noresep': item.resep,
            'cito' : item.cito ? true : false,
            'issementara' : item.issementara ? true : false,
            'isrutin' : item.isrutin ? true : false,
            'isbpl' : item.isbpl ? true : false,
            'retur': '-',
            'isobatalkes': isPemakaianObatAlkes.value,
            'isreseppulang': item.isreseppulang ? item.isreseppulang : null,
            'isresepcito': item.isresepcito ? item.isresepcito : null,
            'alergiobat' : item.alergiobat
        },
        'pelayananpasien': dataSource.value
    }


    const aspekKosong = semuaAspek.filter(aspek => {
    const y = kajian.value[aspek.key + 'Y']
    const t = kajian.value[aspek.key + 'T']
    return y !== true && t !== true
    })

    if (aspekKosong.length > 0) 
    {
        const aspekLabels = aspekKosong.map(a => `- ${a.label}`).join('\n')
        alert(`Silakan pilih Y atau T untuk semua aspek:\n\n${aspekLabels}`)
        isDisabledList.value = false;
        isSimpan.value = false
        return
    }
    semuaAspek.forEach(aspek => {
    objSave.strukresep[aspek.key] = kajian.value[aspek.key + 'Y']
    ? true
    : kajian.value[aspek.key + 'T']
    ? false
    : null
    })


    isSimpan.value = true
    await useApi().post(
        `/farmasi/input-resep-save`, objSave).then(async (response: any) => {

            //sendAntrol(NOREC_PD)
            isSimpan.value = false
            item.dataresep = response.noresep
            item.norec_resep = response.noresep.norec
            item.noResep = response.noresep.noresep;
            if (dataOK.value.length > 0) {
                var objSaveKronis = {
                    'strukresep': objSave.strukresep,
                    'norecresep': response.noresep.norec,
                    'noresep': response.noresep.noresep,
                    'noregistrasi': item.header.noregistrasi,
                    'pelayananpasienobatkronis': dataOK.value,
                }
                await useApi().post(`/farmasi/input-resep-kronis-save`, objSaveKronis)
            }
            if(item.header.kelompokpasien && item.header.kelompokpasien.includes('BPJS')){
              var apotikonline = {
                "TGLSJP": item.header.tglsep,
                "REFASALSJP": item.header.nosep,
                "POLIRSP": item.header.kodeapotikonline,
                "KDJNSOBAT": item.jenisobat.id,
                "NORESEP": response.noresep.noresep.slice(-5),
                "IDUSERSJP": item.header.nocm,
                "TGLRSP": moment(item.tglAwal).format('YYYY-MM-DD HH:mm:ss'),
                "TGLPELRSP": moment(item.tglAwal).format('YYYY-MM-DD HH:mm:ss'),
                "KdDokter": item.penulisResep.id,
                "iterasi": item.iterasi
              }

                var json = {
                  url: `/sjpresep/v3/insert`,
                  jenis: "apotik",
                  method: "POST",
                  data: apotikonline
                }
                try{
                  let x = 0
                  await useApi().postBPJS(`/bridging/bpjs/tools`, json).then((response:any)=>{
                    dataSource.value.forEach((element :any)=>{
                      // non racikan
                      if (element.jeniskemasanfk == 2) {
                          const nonRacikan =
                          {
                            NOSJP : item.header.nosep ,
                            NORESEP : response.data.noresep.noresep.slice(-5),
                            KDOBT : element.kdobatbpjs || item.header.kodeapotikonline ,
                            NMOBAT :element.namaproduk,
                            SIGNA1OBT :element.aturanpakai.substring(0, 1),
                            SIGNA2OBT :element.aturanpakai.slice(-1),
                            JMLOBT : element.jumlah,
                            JHO :element.jumlah / parseInt(element.aturanpakai.substring(0, 1)),
                            CatKhsObt : element.keterangan
                          }
                          const jsonNonRacikan = {
                              data : nonRacikan,
                              url: `/obatnonracikan/v3/insert`,
                              jenis: "apotik",
                              method: "POST"
                          }
                          useApi().postBPJS('/bridging/bpjs/tools',jsonNonRacikan)
                      }
                      // racikan
                      if (element.jeniskemasanfk == 1) {
                         x++;
                         const no = (x < 10) ? '0' + x : '' + x;
                         const racikan ={
                            NOSJP : item.header.nosep ,
                            NORESEP : response.data.noresep.noresep.slice(-5),
                            JNSROBT: "R." + no,
                            KDOBT : element.kdobatbpjs || item.header.kodeapotikonline,
                            NMOBAT :element.namaproduk,
                            SIGNA1OBT :element.aturanpakai.substring(0, 1),
                            SIGNA2OBT :element.aturanpakai.slice(-1),
                            PERMINTAAN : element.dosis,
                            JMLOBT : element.jumlah,
                            JHO : 23,
                            CatKhsObt : element.keterangan
                         }
                          const jsonRacikan = {
                              data : racikan,
                              url: `/obatracikan/v3/insert`,
                              jenis: "apotik",
                              method: "POST"
                          }
                          useApi().postBPJS('/bridging/bpjs/tools',jsonRacikan)
                      }
                    })
                  })
                }catch(e:any){
                    isDisabledList.value = true;
                }
            }
            isDisabledList.value = true;

        }, (error) => {
            isSimpan.value = false
            isDisabledList.value = false;

        })
}
const changeProduk = (e: any) => {
    
    if (e != null && e != undefined) {
        GETKONVERSI()
        checkOrderLast(ID_PASIEN ? ID_PASIEN : item.header.nocmfk, e.id)
        getProdukListStok(e.id)
    }
}

const checkOrderLast = async (nocmfk: any, produkid: any) => {
    await useApi().get(`/farmasi/check-obat-periode?produkfk=${produkid}&nocmfk=${nocmfk}&norec_pd=${NOREC_PD}`).then((response) => {
        if (response.data) 
        {
            isLastObatByDate.value = true
            item.namaproduk = response.data.namaproduk 
            item.lastorder = response.data.tglterakhir 
            item.jumlahlast = response.data.total 
        } 
        else 
        {
            isLastObatByDate.value = false
            isLastObatByDate.value = false;
            item.namaproduk = '';
            item.lastorder = '';
            item.jumlahlast = '';
        }
    })
}

const changeExpired = (e: any) => {
    if (e != null && e != undefined) {
        setNorecSPD()
    }
}
const GETKONVERSI = () => {

    d_satuan.value = []
    if (item.produk == undefined) return


    if (item.produk.konversisatuan.length == 0) {
        d_satuan.value = [{
            satuanstandar: item.produk.satuanstandar,
            ssid: item.produk.ssid,
            nilaikonversi: 1
        }]
    } else {
        d_satuan.value = item.produk.konversisatuan
    }
    d_satuan.value.forEach((element: any) => {
        if (element.ssid == item.produk.ssid) {
            item.satuan = element
            return
        }
    });

    item.nilaiKonversi = 1
    isLoading.value = true
    dataProdukDetail.value = []
    d_tglKadaluarsa.value = []

    // let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''
    let isSementara = item.issementara ? `&issementara=${item.issementara}` : ''

    useApi().get(
        '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
        '&ruanganfk=' + item.ruangan.id + isSementara +
        "&kpid=" + item.header.kpid +
        "&norec_apd=" + NOREC_APD).then(function (response: any) {
            if (response.detail.length > 0) {
                response.detail.forEach((element: any) => 
                {

                    if (element.tglkadaluarsa != null)
                        d_tglKadaluarsa.value.push({ tglkadaluarsa: element.tglkadaluarsa })
                });

                // .map((e: any) => {
                //     return { label: e.tglkadaluarsa, value: e }
                // })
                dataProdukDetail.value = response.detail
                item.stok = response.jmlstok / item.nilaiKonversi
                if (response.kekuatan == undefined || response.kekuatan == 0) {
                    response.kekuatan = 1
                }
                
                item.fornas = response.fornas
                item.objectdetailjenisprodukfk = response.objectdetailjenisprodukfk
                item.kekuatan = response.kekuatan
                item.sediaan = response.sediaan
                
                item.tglKadaluarsa = d_tglKadaluarsa.value.length ? d_tglKadaluarsa.value[0] : undefined

                if (dataSelected.value.no != undefined) 
                {
                    item.jumlah = Math.ceil(parseFloat(dataSelected.value.jumlahobat))
                    item.jumlahbulat = dataSelected.value.jumlahobat
                    item.dosis = parseFloat(dataSelected.value.dosis)
                    isLastObatByDate.value = true
                    item.jumlahlast = dataSelected.value.jumlahlast
                    item.lastorder = dataSelected.value.lastorder
                    item.jumlahxmakan = Math.floor((parseFloat(dataSelected.value.jumlahobat) / parseFloat(item.dosis)) * parseFloat(item.kekuatan))
                    item.nilaiKonversi = dataSelected.value.nilaikonversi

                    d_satuan.value.forEach((element: any) => {
                        if (element.ssid == dataSelected.value.satuanviewfk) {
                            item.satuan = element
                            return
                        }
                    });
                    if (item.issementara === true) {

                    }
                    item.hargaSatuan = dataSelected.value.hargasatuan
                    item.hargadiskon = dataSelected.value.hargadiscount
                    item.hargaNetto = dataSelected.value.harganetto
                    item.total = dataSelected.value.total
                } else {
                    if (!isMerge.value) {
                        item.jumlah = 1
                    }
                }

                setNorecSPD()
                isLoading.value = false
            } else {              
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
                H.alert('error', 'Stok Produk Kosong')
                isLoading.value = false
            }

        });

}

const GETKONVERSIEDIT = () => {

    d_satuan.value = []
    if (item.produk == undefined) return


    if (item.produk.konversisatuan.length == 0) {
        d_satuan.value = [{
            satuanstandar: item.produk.satuanstandar,
            ssid: item.produk.ssid,
            nilaikonversi: 1
        }]
    } else {
        d_satuan.value = item.produk.konversisatuan
    }
    d_satuan.value.forEach((element: any) => {
        if (element.ssid == item.produk.ssid) {
            item.satuan = element
            return
        }
    });

    item.nilaiKonversi = 1
    isLoading.value = true
    dataProdukDetail.value = []
    d_tglKadaluarsa.value = []

    useApi().get(
        '/farmasi/get-produkdetailedit?produkfk=' + item.produk.id +
        '&ruanganfk=' + item.ruangan.id +
        "&kpid=" + item.header.kpid +
        "&norec_apd=" + NOREC_APD).then(function (response: any) {
            if (response.detail.length > 0) {
                response.detail.forEach((element: any) => {

                    if (element.tglkadaluarsa != null)
                        d_tglKadaluarsa.value.push({ tglkadaluarsa: element.tglkadaluarsa })
                });

                // .map((e: any) => {
                //     return { label: e.tglkadaluarsa, value: e }
                // })
                dataProdukDetail.value = response.detail
                item.stok = response.jmlstok / item.nilaiKonversi
                if (response.kekuatan == undefined || response.kekuatan == 0) {
                    response.kekuatan = 1
                }
                item.kekuatan = response.kekuatan
                item.sediaan = response.sediaan
                item.value.fornas = response.fornas
                item.tglKadaluarsa = d_tglKadaluarsa.value.length ? d_tglKadaluarsa.value[0] : undefined
                if (dataSelected.value.no != undefined) {
                    let jmlx = 0
                    if (dataSelected.value.iskronis === true) {
                        jmlx = parseInt(dataSelected.value.jumlahobat) + parseInt(dataSelected.value.jumlah)
                    } else {
                        jmlx = dataSelected.value.jumlahobat
                    }
                    item.jumlah = parseFloat(jmlx)
                    item.jumlahbulat = jumlah
                    isLastObatByDate.value = true
                    item.dosis = parseFloat(dataSelected.value.dosis)
                    item.jumlahlast = dataSelected.value.jumlahlast
                    item.lastorder = dataSelected.value.lastorder
                    item.jumlahxmakan = Math.floor((parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan))
                    item.nilaiKonversi = dataSelected.value.nilaikonversi

                    d_satuan.value.forEach((element: any) => {
                        if (element.ssid == dataSelected.value.satuanviewfk) {
                            item.satuan = element
                            return
                        }
                    });

                    if (item.checkisDonasi === true || item.issementara === true) {
                        item.hargaSatuan = 0
                        item.hargaNetto = 0
                        item.hargadiskon = 0
                        item.total = 0

                    } else {
                        item.hargaSatuan = dataSelected.value.hargasatuan
                        item.hargadiskon = dataSelected.value.hargadiscount
                        item.hargaNetto = dataSelected.value.harganetto
                        item.total = dataSelected.value.total
                    }


                } else {
                    if (!isMerge.value) {
                        item.jumlah = 1
                    }
                }

                setNorecSPD()
                isLoading.value = false
            } else {
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
                H.alert('error', 'Stok Produk Kosong')
                isLoading.value = false
            }

        });

}
const clearInput = () => {
    delete item.produk
    delete item.asal
    delete item.satuan
    delete item.no
    delete item.KeteranganPakai
    delete dataSelected.value
    delete item.tglKadaluarsa
    delete item.tglpemakaian
    isLastObatByDate.value = false

    item.qty = 1
    item.nilaiKonversi = 0
    item.stok = 0
    item.jumlah = 0
    item.jumlahsementara = 0
    item.jumlahbulat = item.jumlah
    item.isbud = false
    item.issementara = false
    item.hargadiskon = 0
    item.total = 0
    item.hargaSatuan = 0
    item.hargaNetto = 0
    item.persenDiskon = 0
    item.checkisKronis = false
    item.inacbgss = true
    item.kronis23 = false
    item.kronis30 = false
    item.checkisKronisAll = false
    item.checkisDonasi = false

    if (item.jenisKemasan.jeniskemasan != 'Racikan') {
        delete item.satuanresep
        delete item.aturanpakaitxt
        delete item.dosis
        delete item.jenisRacikan
        item.jumlahxmakan = 1
        item.chkp = 0
        item.chks = 0
        item.chksr = 0
        item.chkm = 0

        listDataSigna.value.forEach((element: any) => {
            element.isChecked = false
        });
    }

    dataSelected.value = {}
}
const clearGrid = () => {
    data2.value = []
    dataSource.value = data2.value
    dataOK.value = []
    dataGridKronis.value = dataOK.value

}
const changeDikson = (e: any) => {
    if (e) {
        item.hargadiskon = 0;
        if (item.persenDiskon > 100) {
            item.persenDiskon = 100
        }
        if (item.persenDiskon < 0) {
            item.persenDiskon = 0
        }

        if (item.persenDiskon > 0) {
            var diskon = (parseFloat(item.hargaSatuan) * parseFloat(item.persenDiskon)) / 100
            if (!isNaN(diskon)) {
                item.hargadiskon = diskon;
                item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
            }
        }
    } else {
        item.hargadiskon = 0
        item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
    }
}

let mergeAttempted = false

const setNorecSPD = () => {
    mergeAttempted = false
    if (!item.jumlah && !item.jumlahsementara) return

    var value = item.jumlahsementara || item.jumlah

    if (item.jenisKemasan == undefined) {
        return
    }
  
    var qty20 = 0
    tarifJasa.value = parseFloat(item.tarifadminresep)
    if (item.jumlah == 0) {
        tarifJasa.value = 0;
    } else if (dataProdukDetail.value[0]?.objectasalprodukfk === 3) {
        tarifJasa.value = 0;
    } else if (parseFloat(tarifJasa.value) != 0) {
        if (item.jenisKemasan.id == 2) {
            if (item.header.objectkebangsaanfk == 1) {
                tarifJasa.value = parseFloat(item.tarifWNI);
            } else if (item.header.objectkebangsaanfk == 2) {
                tarifJasa.value = parseFloat(item.tarifKitas);
            } else if (item.header.objectkebangsaanfk == 3) {
                tarifJasa.value = parseFloat(item.tarifNonKitas);
            }
        }

    if (item.jenisKemasan.id == 1) {
        qty20 = Math.floor(parseFloat(item.jumlah) / 20);
        if (parseFloat(item.jumlah) % 20 == 0) {
            qty20 = qty20;
        } else {
            qty20 = qty20 + 1;
        }

        if (qty20 != 0) {
            tarifJasa.value = tarifJasa.value * qty20;
        }
    }
}
    if (item.no == undefined) {
        for (var i = data2.value.length - 1; i >= 0; i--) {
            if (data2.value[i].rke == item.rke) {
                tarifJasa.value = 0
            }
        }
    }
    item.jumlahbulat = item.jumlah

    var ada = false;
    for (var i = 0; i < dataProdukDetail.value.length; i++) {
        ada = false
        const element = dataProdukDetail.value[i]
        var tglExpiredPilih = element.tglkadaluarsa
        if (item.tglKadaluarsa != undefined) {
            tglExpiredPilih = moment(item.tglKadaluarsa.tglkadaluarsa).format('YYYY-MM-DD HH:mm:ss')
        }



        if (parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi)
            <= parseFloat(element.qtyproduk)
            && tglExpiredPilih == element.tglkadaluarsa
        ) {
            if (item.checkisDonasi === true || item.jumlahsementara === true) {
                hrg1.value = 0
                hrgsdk.value = 0
                item.hargaSatuan = 0
                item.hargaNetto = 0
                item.hargadiskon = 0
                item.total = 0

            } else {
                if (item.header.kpid === 1) {
                    hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi));
                } else {
                    hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi));
                }

                hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
                item.hargaSatuan = hrg1.value
                item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
                if (item.hargadiskon == 0) {
                    item.hargadiskon = hrgsdk.value
                } else {
                    hrgsdk.value = item.hargadiskon
                }
                item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
                

            }
            if(element.qtyproduk >= item.jumlahbulat)
            {
                norecTerima.value = element.norec
                norecSPD.value = element.norec_spd
                item.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
                
                ada = true;
                break;
            }
            else
            {
                ada = false;
            }
            
        }
    }
    if (ada == false) {
        if (NOREC_RESEP != undefined)
        {
            item.hargaSatuan = dataSelected.value.hargasatuan
            item.harganetto = dataSelected.value.harganetto
            item.hargajual = dataSelected.value.hargajual
            item.total = dataSelected.value.total
            norecSPD.value = dataSelected.value.norec_spd
            norecTerima.value = dataSelected.value.nostrukterimafk
        }
        else 
        {
            item.hargaSatuan = 0
            item.hargadiskon = 0
            item.hargaNetto = 0
            item.total = 0

            let isDonasi = item.checkisDonasi ? `?isdonasi=${item.checkisDonasi}` : ''

            norecSPD.value = ''
            norecTerima.value = ''
            isMerge.value = false
            if (item.stok !== 0 && dataProdukDetail.value.length > 1 && !mergeAttempted && item.stok >= item.jumlah) {
                var objSave = {
                    produkfk: item.produk.id,
                    ruanganfk: item.ruangan.id,
                    jumlah: item.jumlahbulat,
                }

                isSimpan.value = true
                useApi()
                    .postNoMessage('/farmasi/save-stock-merger', objSave)
                    .then(function (response) {
                    isMerge.value = true
                    GETKONVERSI()
                    isSimpan.value = false
                    mergeAttempted = true 
                    })
                }
                else
                {
                    if(item.stok < item.jumlah)
                    {
                        useToaster().error('Jumlah Permintaan Melebihi Stok')
                    }
                }
        }
    }
    
    if (item.jumlah == 0) {
        item.hargaSatuan = 0
        item.hargaNetto = 0
    }
}

const jumlahkan = () => {
    if (item.stok > 0) {
        item.jumlah = Math.ceil((parseFloat(item.jumlahxmakan) * parseFloat(item.dosis)) / parseFloat(item.kekuatan))
        item.jumlahbulat = item.jumlah
    }
}
const changeSatuan = (e: any) => {
    item.nilaiKonversi = item.satuan.nilaikonversi
}
const back = () => {
    window.history.back()
}
const addListAturanPakai = (bool: boolean, data: any) => {
    let jml = 0
    if (bool == true) {
        if (data.id == 1) {
            item.chkp = 1
        }
        if (data.id == 2) {
            item.chks = 1
        }
        if (data.id == 3) {
            item.chksr = 1
        }
        if (data.id == 4) {
            item.chkm = 1
        }
    } else {
        if (data.id == 1) {
            item.chkp = 0
        }
        if (data.id == 2) {
            item.chks = 0
        }
        if (data.id == 3) {
            item.chksr = 0
        }
        if (data.id == 4) {
            item.chkm = 0
        }
    }
    if (item.chkp == 1) {
        jml = jml + 1
    }
    if (item.chks == 1) {
        jml = jml + 1
    }
    if (item.chksr == 1) {
        jml = jml + 1
    }
    if (item.chkm == 1) {
        jml = jml + 1
    }
    // item.aturanpakaitxt = jml + 'x1'
    // if (jml == 0) {
    //     item.aturanpakaitxt = ''
    // }
}
const cetakResep = async (item: any) => {
    qzService.printData(`report/farmasi/resep?pdf=true&norec=${item.norec}`, 'RESEP', 1);
    // H.printBlade(`report/farmasi/resep?pdf=true&norec=${item.norec}`)
}

const cetakResep2 = async (item: any) => {
    // qzService.printData(`report/farmasi/resep?pdf=true&norec=${item.norec}`, 'RESEP', 1);
    H.printBlade(`report/farmasi/resep?pdf=true&norec=${item.norec}`)
}

// const cetakResep23 = (item: any) => {
//     // H.printBlade(`report/farmasi/resep-obat-23?pdf=true&norec=${item.norec}`)
//     qzService.printData(`report/farmasi/resep-obat-23?pdf=true&norec=${item.norec}`, 'RESEP', 1);

// }

const cetakLabel = async (e: any) => {
    let norec_resp = e.norec ? `&norec=${e.norec}` : ''
    let norec_pd = NOREC_PD ? `&norecpd=${NOREC_PD}` : ''

    qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true${norec_pd}${norec_resp}`, 'LABEL RESEP', 1);
    // qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true${norec_pd}${norec_resp}`, 'LABEL RESEP', 1);

    // if (e.jenis == "R") {
    //     // H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norecpd=${NOREC_PD}`)
    //     qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norecpd=${NOREC_PD}`, 'LABEL RESEP', 1);
    // } else {
    //     // H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norec=${e.norec}`)
    //     qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norec=${e.norec}`, 'LABEL RESEP', 1);
    // }
}

watch(
  () => item.issementara,
  (newVal) => {
    if (newVal) {
      // Jika issementara bernilai true, pindahkan jumlah ke jumlahsementara
      if (item.jumlah > 0) {
        item.jumlahsementara = item.jumlah;
        item.jumlah = 0;
      }
    } else {
      // Jika issementara bernilai false, pindahkan jumlahsementara ke jumlah
      if (item.jumlahsementara > 0) {
        item.jumlah = parseFloat(item.jumlahsementara);
        item.jumlahsementara = 0;
      }
    }
  }
);


watch(
    () => item.checkisKronisAll,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (newValue === true) {
                item.checkisKronis = false
                item.inacbgss = false  
            }
        }
    }
)

watch(
    () => item.checkisKronis,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (newValue === true) {
                item.checkisKronisAll = false
                item.inacbgss = false
            } 
        }
    }
)

watch(
    () => item.inacbgss,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (newValue === true) {
                item.checkisKronisAll = false
                item.checkisKronis = false
            } 
        }
    }
)


watch(
    () => item.checkisDiscount,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (newValue === true) {
                isDisabledDiscount.value = true


            } else {
                isDisabledDiscount.value = false
                // item.checkisKronis = false

            }
        }
    }
)

watch(
    () => item.jenisKemasan,
    (newValue, oldValue) => {
        if (newValue.jeniskemasan == 'Racikan') {
            showRacikanDose.value = true
        } else {
            showRacikanDose.value = false
            delete item.jenisRacikan
        }
    }
)

watch(
  () => [item.jumlah, item.jumlahsementara],
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      setNorecSPD()
    }
  }
)
watch(
    () => item.jumlahxmakan,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            jumlahkan()
        }
    }
)
watch(
    () => item.dosis,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            jumlahkan()
        }
    }
)
watch(
    () => item.hargadiskon,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            hrgsdk.value = item.hargadiskon
            item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
            if (isNaN(item.total)) {
                item.total = 0
            }
        }
    }
)
watch(
    () => item.nilaiKonversi,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (item.stok > 0) {
                item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
                item.jumlah = 0
                item.jumlahbulat = 0;
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
            }
        }
    }
)
// watch(() => item.rke, (newValue, oldValue) => {
//     if (newValue != oldValue) {
//         if (tarifJasa == 0) {
//             for (var i = data2.value.length - 1; i >= 0; i--) {
//                 tarifJasa.value = parseFloat(item.tarifadminresep)
//                 if (data2.value[i].rke == item.rke) {
//                     tarifJasa.value = 0
//                     break;
//                 }
//             }
//         }
//     }
// })

watch(() => [
    item.jenisKemasan,
], (newValue, oldValue) => {
    if (item.no) {
        item.rke = item.rke
    } else {
        dataSource.value.forEach((element: any, i: any) => {
            if (dataSource.value.length - 1 == i) {
                if (item.jenisKemasan.id == 1 && element.jeniskemasanfk == 1) {
                    item.rke = element.rke
                    return
                } if (item.jenisKemasan.id == 2 && element.jeniskemasanfk == 1) {
                    item.rke = parseFloat(element.rke) + 1
                    return
                }
            }
        });
    }
})


const sendAntrol = async (norec_pd: any) => {
    const jsont6 = {
        "noregistrasifk": norec_pd,
        "taskid": 6,
        "waktu": new Date().getTime(),
    }
    await useApi()
        .postNoMessage(`/bridging/antrol/sendTaskId`, jsont6)
        .then((response: any) => {
        })
}
const clear = () => {

}
onInit()
// loadRiwayat()
onMounted(() => {

})
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
// @import '/@src/scss/custom/config';

.form-layout .form-outer {
    border: 1px solid transparent;
    background-color: transparent;
}

.bold-text {
    font-weight: bold;
}

// .form-layout {
//     .form-outer {
//         padding: 20px 40px 40px;
//     }
// }

.p-table {
  width: 100%;
  border-collapse: collapse;
}
.p-table th,.p-table td {
  padding: 0.51rem;
  text-align: center;
  vertical-align: middle;
  border: 1px solid #ccc;
  }

.pilihannya .checkbox{  
  padding:1px;
  text-align: center;
  align-items: center;
  gap: 0.5rem;
  color: red !important;
  align-items: center;
}

td.pilihannya
{
    width:2px !important;
}

.pilihannya label.checkbox.is-outlined 
{
    width:20px;
}

.pilihannya2 .checkbox{  
  padding:1px;
  text-align: center;
  align-items: center;
  gap: 0.5rem;
  color: blue !important;
  align-items: center;
}

td.pilihannya2
{
    width:2px !important;
}

.pilihannya2 label.checkbox.is-outlined 
{
    width:20px;
}

</style>

