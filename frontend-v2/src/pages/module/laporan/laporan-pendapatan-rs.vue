<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page"> Income Statement </label>
        <CardStat icon="/images/simrs/saldo-awal.png" :color="((arrGroup.jumlah - arrGroup2.jumlah) +
          arrGroup2.bebanamorti) > 0 ? 'primary' : 'red'" straight :total="H.formatRupiah(EBITDA, 'Rp.')"
          label="EBITDA ( Earning Before Interest, Taxes, Depreciation, and Amortization )" />
      </div>

      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar inputId="range" v-model="item.qBulan" selectionMode="range" :manualInput="false"
                  class="w-100 mb-4 is-rounded" :showIcon="true" date-format="yy-mm-dd" />
              </VControl>
            </VField>
          </div>
          <div class="column mt-5">
            <VButton type="button" icon="feather:search" color="primary" raised :loading="isLoading"
              @click="fetchData()">
              Cari
            </VButton>
            <VButton type="button" icon="feather:send" color="info" raised :loading="isLoading2" @click="kirimData()"
              class="ml-2">
              Kirim Data
            </VButton>
          </div>
          <!-- <div class="column is-12">
            <Fieldset legend="Query" :toggleable="true" :collapsed="true">
              <div class="column is-12">
                <VField>
                  <VLabel class="required-field">Query</VLabel>
                  <VControl>
                    <VTextarea v-model="item.query" rows="20" placeholder="...">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </Fieldset>
          </div> -->
          <div class="column is-12">
            <VCard class="mb-2">
              <div class="columns is-multiline">

                <div class="column is-3">
                  <Card class="mb-2">

                    <template #title>PENDAPATAN LAYANAN <b style="float: right;"> {{ H.formatRupiah(arrGroup.jumlah.toFixed(2),
          'Rp.') }}</b></template>

                    <template #content>
                      <div>
                        <p class="subtitle" style="font-size: 14px;"> <b>1. Rawat Jalan </b></p>
                        <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.1
                          Pendapatan
                          JKN </p>
                        <span style="margin-left: 30px; margin-bottom: 20px !important;"> - Pendapatan
                          Pasien
                          JKN Reguler : <span style="float: right;"> {{ H.formatRupiah(arrGroup.jkn_rajal, 'Rp.') }}
                          </span></span>
                        <br />
                        <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.2
                          Pendapatan
                          Non JKN Eksekutif </p>
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Asuransi : <span style="float: right;">{{
          H.formatRupiah(arrGroup.Eks_Asuransi_rajal, 'Rp.') }}</span>
                        </span>
                        <br />
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Perusahaan : <span
                            style="float: right;">{{
          H.formatRupiah(arrGroup.Eks_perusahaan_rajal, 'Rp.') }} </span></span>
                        <br />
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Umum : <span style="float: right;">{{
          H.formatRupiah(arrGroup.Eks_umum_rajal, 'Rp.') }}</span>
                        </span>
                        <br />
                        <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.3 Pasien
                          Non JKN Reguler </p>
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Asuransi : <span style="float: right;">{{
          H.formatRupiah(arrGroup.Reg_Asuransi_rajal, 'Rp.') }} </span></span>
                        <br />
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Perusahaan : <span
                            style="float: right;">{{
          H.formatRupiah(arrGroup.Reg_perusahaan_rajal, 'Rp.') }}</span>
                        </span>
                        <br />
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Umum : <span style="float: right;">{{
          H.formatRupiah(parseFloat(arrGroup.Reg_umum_rajal, 'Rp.').toFixed(2), '') }}</span>
                        </span>
                        <br />
                        <p class="subtitle" style="font-size: 14px;"> </p>
                        <p class="subtitle" style="font-size: 14px;"><b> 2. Rawat Inap </b> </p>
                        <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.1 Pasien
                          JKN </p>
                        <span style="margin-left: 30px; margin-bottom: 20px !important;"> - Pendapatan
                          Pasien
                          JKN : <span style="float: right;">{{ H.formatRupiah(arrGroup.jkn_ranap, 'Rp.') }}
                          </span></span>
                        <br />

                        <br />
                        <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.2 Pasien
                          Non JKN Eksekutif </p>
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Asuransi : <span style="float: right;">{{
          H.formatRupiah(arrGroup.Eks_Asuransi_ranap, 'Rp.') }}</span>
                        </span>
                        <br />
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Perusahaan : <span
                            style="float: right;">{{
          H.formatRupiah(arrGroup.Eks_perusahaan_ranap, 'Rp.') }}</span>
                        </span>
                        <br />
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Umum : <span style="float: right;">{{
          H.formatRupiah(arrGroup.Eks_umum_ranap, 'Rp.') }}</span>
                        </span>
                        <br />
                        <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.3 Pasien
                          Non JKN Reguler </p>
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Asuransi : <span style="float: right;">{{
          H.formatRupiah(arrGroup.Reg_Asuransi_ranap, 'Rp.') }} </span></span>
                        <br />
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Perusahaan :<span style="float: right;">
                            {{
          H.formatRupiah(arrGroup.Reg_perusahaan_ranap, 'Rp.') }}</span>
                        </span>
                        <br />
                        <span style="margin-left: 30px;"> - Pendapatan Pasien Umum :<span style="float: right;"> {{
          H.formatRupiah(arrGroup.Reg_umum_ranap, 'Rp.') }}</span>
                        </span>
                        <p class="subtitle" style="font-size: 14px;"> </p>
                        <p class="subtitle" style="font-size: 14px;"><b> 3. Pendapatan Layanan Kesehatan Lainnya </b>
                        </p>
                        <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> 1.1 Pendapatan Lainnya
                        </p>
                        <span style="margin-left: 30px; margin-bottom: 20px !important;"> - Pendapatan Lainnya : <span
                            style="float: right;">{{
          H.formatRupiah(arrGroup.pendapatan_lain, 'Rp.') }} </span></span>
                        <br />

                      </div>
                    </template>
                  </Card>

                  <Card>

                    <template #title>BEBAN USAHA <b style="float: right;"> {{ H.formatRupiah(arrGroup2.jumlah,
          'Rp.') }}</b></template>

                    <template #content>
                      <div>

                        <span class="subtitle" style="font-size: 14px;"> <b>Beban Pokok Pendapatan : </b> <span
                            style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanPegBLU
          + arrGroup2.bebanPersediaanF + arrGroup2.bebanPersediaanNonF, 'Rp.') }}</span></span>
                        <br />
                        <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> a. Beban Pegawai
                        </span>
                        <br />
                        <span style="margin-left: 30px; margin-bottom: 20px !important;">(1) Beban Pegawai (PNBP/BLU)
                          :<span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanPegBLU, 'Rp.') }}</span>
                        </span>
                        <br />

                        <p class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;"> b. Beban Persediaan (RM
                          &
                          PNBP/BLU) </p>
                        <span style="margin-left: 30px;"> (1) Beban persediaan farmasi<span style="float: right;"> {{
          H.formatRupiah(arrGroup2.bebanPersediaanF, 'Rp.') }} </span></span>
                        <br />
                        <span style="margin-left: 30px;"> (2) Beban persediaan non farmasi :<span style="float: right;">
                            {{
          H.formatRupiah(arrGroup2.bebanPersediaanNonF, 'Rp.') }}</span>
                        </span>

                        <p class="subtitle" style="font-size: 14px;"> </p>
                        <span class="subtitle" style="font-size: 14px;"> <b>Beban Administrasi & Umum (RM & PNBP/BLU)
                            :</b>
                          <span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanBarangJasa
          + arrGroup2.bebanPemeliharaan + arrGroup2.bebanPerdin + arrGroup2.bebanPenyisihanPiut,
          'Rp.')
                            }}</span></span>
                        <br />
                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">a. Beban Barang dan
                          Jasa
                          : <span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanBarangJasa, 'Rp.')
                            }}</span></span>
                        <br />
                        <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">b. Beban Pemeliharaan
                          :
                          <span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanPemeliharaan, 'Rp.')
                            }}</span></span>
                        <br />
                        <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">c. Beban Perjalanan
                          Dinas
                          :<span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanPerdin, 'Rp.') }}</span></span>
                        <br />
                        <span class="subtitle ml-3" style="font-size: 14px; margin-bottom: -1px;">d. Beban Penyisihan
                          Piutang Tak Tertagih :<span style="float: right;"> {{
          H.formatRupiah(arrGroup2.bebanPenyisihanPiut,
            'Rp.') }}</span></span>
                        <br />
                        <br />
                        <p class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> <b>Beban Penyusutan dan
                            Amortisasi</b> : <span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanamorti,
          'Rp.')
                            }}
                          </span></p>
                        <br />
                        <p class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> <b>Beban Pegawai (APBN/RM)

                          </b> : <span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanPegAPBN, 'Rp.') }}
                          </span></p>
                      </div>
                    </template>
                  </Card>

                </div>
                <div class="column is-3">

                  <Card class="mt-2 mb-2"
                    :style="((arrGroup.jumlah - arrGroup2.jumlah) + arrGroup2.bebanamorti) > 0 ? 'background: var(--green);' : 'background: hsl(345deg 94% 57% / 59%);'">

                    <template #title>EBITDA <b style="float: right;">
                        {{ H.formatRupiah(EBITDA, 'Rp.') }}</b>
                      <br />
                      <i class="mt-2-min" style="font-size: 12px;"> (Suplus/ Defisit Usaha + Depresiasi )</i>
                    </template>


                    <template #content>
                      <span class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> <b>Surplus/Defisit usaha
                        </b><b style="float: right;"> {{ H.formatRupiah(SURPLUS,
          'Rp.') }}
                        </b>
                        <br />
                        <i class="mt-2-min" style="font-size: 12px;"> (Pendapatan Layanan RS - Beban Usaha )</i>
                      </span>
                      <br />
                      <span class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> Depresiasi dan Amortisasi
                        <span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanamorti, 'Rp.')
                          }}
                        </span>
                      </span>
                      <br />
                      <span class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> <b>EBITDA
                        </b><b style="float: right;">{{ H.formatRupiah(EBITDA, 'Rp.')
                          }}
                        </b>
                      </span>
                      <br />
                      <span class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> Beban Pegawai (APBN/RM)
                        <span style="float: right;"> {{ H.formatRupiah(arrGroup2.bebanPegAPBN, 'Rp.') }}
                        </span>
                      </span>
                      <br />
                      <span class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> <b>EBITDA (Termasuk Beban
                          Pegawai APBN/RM)
                        </b><b style="float: right;"> {{ H.formatRupiah(EBITDA_BEBAN, 'Rp.') }}
                        </b>
                        <br />
                        <i class="mt-2-min" style="font-size: 12px;"> (EBITDA - Beban Pegawai (APBN/RM) )</i>
                      </span>
                    </template>
                  </Card>
                  <Card class="mt-4">

                    <template #title>PENDAPATAN NON LAYANAN <b style="float: right;"> {{
          H.formatRupiah(arrGroup3.jumlah,
            'Rp.') }}</b></template>

                    <template #content>
                      <div>

                        <span class="subtitle" style="font-size: 14px;"> <b>Pendapatan Keuangan
                          </b></span>
                        <br />
                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">a.
                          Pendapatan Bunga Bank
                          : <span style="float: right;"> {{ H.formatRupiah(arrGroup3.pendapatan_bunga_bank, 'Rp.')
                            }}</span></span>
                        <br />
                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">b.
                          Deposito
                          : <span style="float: right;"> {{ H.formatRupiah(arrGroup3.deposito, 'Rp.')
                            }}</span></span>
                        <br />
                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">b.
                          Pend Denda & Lain-Lain
                          : <span style="float: right;"> {{ H.formatRupiah(arrGroup3.pend_lainnya, 'Rp.')
                            }}</span></span>
                        <br />
                        <p class="subtitle" style="font-size: 14px;"> </p>
                        <span class="subtitle" style="font-size: 14px;"> <b>Jumlah Pendapatan Keuangan :</b>
                          <span style="float: right;"> {{ H.formatRupiah(arrGroup3.pendapatan_bunga_bank
          + arrGroup3.deposito + arrGroup3.pend_lainnya, 'Rp.')
                            }}</span></span>
                        <br />
                        <p class="subtitle" style="font-size: 14px;"> </p>
                        <span class="subtitle" style="font-size: 14px;"> <b> Biaya Keuangan
                          </b></span>
                        <br />
                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">a.
                          Biaya Bunga Bank
                          : <span style="float: right;"> {{ H.formatRupiah(arrGroup3.biaya_bunga_bank, 'Rp.')
                            }}</span></span>
                        <br />
                        <p class="subtitle" style="font-size: 14px;"> </p>
                        <span class="subtitle" style="font-size: 14px;"> <b>Jumlah Biaya Keuangan :</b>
                          <span style="float: right;"> {{ H.formatRupiah(arrGroup3.biaya_bunga_bank, 'Rp.')
                            }}</span></span>
                        <br />

                        <p class="subtitle" style="font-size: 14px;"> </p>
                        <span class="subtitle" style="font-size: 14px;"> <b> Pendapatan (Biaya) Lain-lain
                          </b></span>
                        <br />
                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">a.
                          Pendapatan APBN/RM diluar belanja pegawai dan belanja modal
                          : <span style="float: right;"> {{ H.formatRupiah(arrGroup3.pend_apbn_lainnya, 'Rp.')
                            }}</span></span>
                        <br />
                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">b.
                          Pendapatan Hibah
                          : <span style="float: right;"> {{ H.formatRupiah(arrGroup3.pendapatan_hibah, 'Rp.')
                            }}</span></span>
                        <br />
                        <span class="subtitle  ml-3" style="font-size: 14px; margin-bottom: -1px;">c.
                          Pendapatan BLU lainnya (contoh Sewa Gedung/Ruang/Lahan)
                          : <span style="float: right;"> {{ H.formatRupiah(arrGroup3.pendapatan_blu_lainnya, 'Rp.')
                            }}</span></span>
                        <br />

                        <p class="subtitle" style="font-size: 14px;"> </p>
                        <span class="subtitle" style="font-size: 14px;"> <b>Jumlah Pendapatan lain-lain :</b>
                          <span style="float: right;"> {{ H.formatRupiah(arrGroup3.pend_apbn_lainnya +
          arrGroup3.pendapatan_hibah + arrGroup3.pendapatan_blu_lainnya, 'Rp.') }}</span></span>
                        <br />
                      </div>

                    </template>
                  </Card>

                  <Card class="mt-2 mb-2"
                    :style="((arrGroup.jumlah - arrGroup2.jumlah) + arrGroup2.bebanamorti) > 0 ? 'background: var(--blue);' : 'background: hsl(220 149 168 / 59%);'">

                    <template #title>SURPLUS/DEFISIT USAHA SEBELUM PAJAK <b style="float: right;">{{
          H.formatRupiah(SURPLUS_SEBELUM_PAJAK,
            'Rp.') }}</b>
                      <br />
                      <i class="mt-2-min" style="font-size: 12px;"> (Suplus/ Defisit Usaha + Pend. Keuangan - Biaya
                        Keuangan + Pend. Lainnya )</i>
                    </template>

                    <template #content>
                      <span class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> Manfaat (Beban) Pajak
                        <span style="float: right;"> {{ H.formatRupiah(arrGroup3.manfaat_beban, 'Rp.')
                          }}
                        </span>
                      </span>
                      <br />
                      <p class="subtitle" style="font-size: 14px;"> </p>
                      <span class="subtitle" style="font-size: 14px; margin-bottom: -1px;"> <b> SURPLUS / DEFISIT BERSIH
                          (NET INCOME)
                        </b> <b style="float: right;"> {{ H.formatRupiah(SURPLUS_SEBELUM_PAJAK, 'Rp.') }}
                        </b>
                        <br />
                        <i class="mt-2-min" style="font-size: 12px;"> (Suplus/ Defisit Usaha Sebelum Pajak + Manfaat
                          (Beban) Pajak )</i>
                      </span>

                    </template>
                  </Card>

                </div>
                <div class="column is-6">
                  <VCard>
                    <TabView class="tabview-custom mt-3" :scrollable="true" @tab-click="klikTab3($event)">
                      <TabPanel>
                        <template #header>
                          <span>Pendapatan </span>
                        </template>
                        <div class="columns is-multiline">

                          <div class="column is-12">


                            <div class="column is-12">
                              <div class="columns is-multiline">

                                <div class="column is-12">
                                  <ToggleButton v-model="proporsiFrozen" onIcon="pi pi-lock" offIcon="pi pi-lock-open" onLabel="Pendapatan" offLabel="Pendapatan" class="mb-2"/>
                                  <VButton type="button" icon="pi pi-file-excel" class="mr-3 is-pulled-right"
                                    v-tooltip-prime="'Export'" @click="exportExcel(dataPasien, 'LapKunjungan')"
                                    color="primary">
                                    Export Excel
                                  </VButton>
                                  <DataTable v-model:filters="filters" :value="dataPasien" paginator :rows="20"
                                    dataKey="no" filterDisplay="row" scrollable :size="'small'"
                                    :globalFilterFields="['namapasien', 'noregistrasi', 'nocm', 'kelompokpasien', 'namaruangan','namaproduk']"
                                    showGridlines stripedRows :loading="isLoading"
                                    :rowsPerPageOptions="[5, 10, 20, 25, 50, 100, 1000]"
                                    :rowClass="rowClass" :rowStyle="rowStyle">

                                    <template #header>
                                      <div class="columns is-multiline">
                                        <div class="column is-3">
                                          <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                                        </div>
                                        <div class="column is-3">

                                        </div>

                                        <div class="column is-6" style="margin-top:-20px">
                                          <div class="content mt-2 mb-3 is-pulled-right">
                                              <VTag color="success" label="Diklaim" />
                                              <VTag color="danger" label="Belum Diklaim" class="ml-3 mt-3"  />
                                          </div>
                                       </div>
                                      </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found.
                                    </template>
                                    <Column field="no" header="No" style="width: 50px; text-align: center;" />
                                    <Column field="namapasien" header="Nama Pasien" style="min-width: 250px;" frozen class="font-bold"/>
                                    <Column field="namadepartemen" header="Departemen" style="min-width: 200px;" />
                                    <Column field="kelompokpasien" header="Jenis Pasien" style="min-width: 150px;" />
                                    <Column field="tgl" header="Tgl Pulang" style="min-width: 150px;" />
                                    <Column field="noregistrasi" header="No Registrasi" style="min-width: 150px;" />
                                    <Column field="nocm" header="No RM" style="min-width: 100px;" />
                                    <Column field="namaruangan" header="Ruangan" style="min-width: 200px;" />
                                    <Column field="namaproduk" header="Pelayanan" style="min-width: 300px;" />
                                    <Column field="total" header="Potensi Pendapatan" class="font-bold" style="min-width: 150px;text-align:right"  alignFrozen="right" :frozen="proporsiFrozen">
                                      <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.total, 'Rp.') }}
                                      </template>
                                    </Column>
                                    <Column field="total" header="Total Pengajuan"
                                      style="min-width: 150px;text-align:right">
                                      <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.pengajuan, 'Rp.') }}
                                      </template>
                                    </Column>

                                    <Column field="total" header="Total Verifikasi (BAHV)"
                                      style="min-width: 150px;text-align:right">
                                      <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.klaim, 'Rp.') }}
                                      </template>
                                    </Column>

                                    <Column field="total" header="Proporsi (Setelah BAHV)"    alignFrozen="right" :frozen="proporsiFrozen" class="font-bold" style="min-width: 150px;text-align:right">
                                      <template #body="slotProps">

                                      <VTag :color="slotProps.data.kelompokpasien =='BPJS' ? (slotProps.data.proporsi == 0 ? 'danger':'success'):'solid'"> {{ H.formatRupiah(slotProps.data.proporsi, 'Rp.') }}</VTag>
                                      </template>
                                    </Column>

                                  </DataTable>

                                  <div class="columns is-multiline mt-4" >
                                    <div class="column is-4">
                                        <VCardCustom :style="'padding:5px 25px'">
                                            <div class="label-status primary">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1">Total Disetujui JKN</span>
                                            </div>
                                            <small class="text-bold-custom h-100">{{ H.formatRupiah(totalDisetujui, "Rp.") }}</small>
                                        </VCardCustom>
                                    </div>
                                    <div class="column is-4">
                                        <VCardCustom :style="'padding:5px 25px'">
                                            <div class="label-status info">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1">Total Biaya RS JKN</span>
                                            </div>
                                            <small class="text-bold-custom h-100">{{ H.formatRupiah(totalTarifRs, "Rp.") }}</small>

                                        </VCardCustom>
                                    </div>
                                    <div class="column is-4">
                                        <VCardCustom :style="'padding:5px 25px'">
                                            <div class="label-status" :class="{'danger': surplusDefisit <= 0, 'success': surplusDefisit > 0}">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1">Surplus / Defisit</span>
                                            </div>
                                            <small class="text-bold-custom h-100">{{ H.formatRupiah(surplusDefisit, "Rp.") }}</small>

                                        </VCardCustom>
                                    </div>
                                    <div class="column is-12">
                                    <div class="content  mb-3" style="margin-top: -35px;">
                                          <div class="is-divider  mb-3" data-content="Total"></div>
                                     </div>
                                     </div>
                                    <div class="column is-6">
                                          <VCardCustom :style="'padding:5px 25px'">
                                              <div class="label-status">
                                              <i aria-hidden="true" class="fas fa-circle"></i>
                                              <span class="ml-1">Potensi Pendapatan JKN</span>
                                              </div>
                                              <small class="text-bold-custom h-100">{{ H.formatRupiah(potensiPendapatan, "Rp.") }}</small>
                                          </VCardCustom>

                                    </div>
                                    <div class="column is-6">
                                          <VCardCustom :style="'padding:5px 25px'">
                                              <div class="label-status">
                                              <i aria-hidden="true" class="fas fa-circle"></i>
                                              <span class="ml-1">Pendapatan NON JKN</span>
                                              </div>
                                              <small class="text-bold-custom h-100">{{ H.formatRupiah(potensiPendapatanNon, "Rp.") }}</small>
                                          </VCardCustom>

                                    </div>
                                    <div class="column is-12">
                                          <VCardCustom :style="'padding:5px 25px'">
                                              <div class="label-status">
                                              <i aria-hidden="true" class="fas fa-circle"></i>
                                              <span class="ml-1">TOTAL</span>
                                              </div>
                                              <small class="text-bold-custom h-100">{{ H.formatRupiah(potensiPendapatanNon +potensiPendapatan, "Rp.") }}</small>
                                          </VCardCustom>

                                    </div>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </TabPanel>
                      <TabPanel>
                        <template #header>
                          <span>Beban </span>
                        </template>
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <DataTable v-model:filters="filters" :value="dataPasien2" paginator :rows="20" dataKey="no"
                              filterDisplay="row"
                              :globalFilterFields="['notransaksi', 'namaproduk', 'ketlainya', 'jenis']"
                              :class="`p-datatable-small`" showGridlines stripedRows :loading="isLoading3"
                              :rowsPerPageOptions="[5, 10, 20, 25, 50, 100, 1000]">
                              <template #header>
                                <div class="flex justify-content-between">
                                  <span class="p-input-icon-left">
                                    <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                                  </span>
                                  <VButton type="button" icon="pi pi-file-excel" class="mr-3" v-tooltip-prime="'Export'"
                                    @click="exportExcel(dataPasien2, 'LapKunjungan')" color="primary">
                                    Export Excel
                                  </VButton>
                                </div>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                </div>
                              </template>

                              <template #empty style="text-align: center;"> No data found.
                              </template>
                              <Column field="no" header="No" style="width: 50px; text-align: center;" />
                              <Column field="tgltransaksi" header="Tgl Transaksi" />
                              <Column field="notransaksi" header="No. Transaksi" />
                              <Column field="namaproduk" header="Deskripsi" />
                              <Column field="ketlainya" header="Keterangan" />
                              <Column field="jenis" header="Jenis" />
                              <Column field="beban" header="Total Beban" style="text-align:right;width: 150px; ">
                                <template #body="slotProps">
                                  {{ H.formatRupiah(slotProps.data.beban, 'Rp.') }}
                                </template>
                              </Column>
                            </DataTable>
                          </div>
                        </div>
                      </TabPanel>
                      <TabPanel>
                        <template #header>
                          <span>Pendapatan Keuangan</span>
                        </template>
                        <div class="columns is-multiline">

                          <div class="column is-12">
                            <DataTable v-model:filters="filters" :value="dataPasien3" paginator :rows="10" dataKey="no"
                              filterDisplay="row"
                              :globalFilterFields="['notransaksi', 'namaproduk', 'ketlainya', 'jenis']"
                              :class="`p-datatable-small`" showGridlines stripedRows
                              :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]">

                              <template #header>
                                <div class="flex justify-content-between">
                                  <span class="p-input-icon-left">

                                    <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                                  </span>
                                  <VButton type="button" icon="pi pi-file-excel" class="mr-3" v-tooltip-prime="'Export'"
                                    @click="exportExcel(dataPasien, 'LapKunjungan')" color="primary">
                                    Export Excel
                                  </VButton>
                                </div>
                                <div class="flex flex-wrap align-items-center justify-content-between gap-2">

                                </div>
                              </template>

                              <template #empty style="text-align: center;"> No data found.
                              </template>
                              <Column field="no" header="No" style="width: 50px; text-align: center;" />
                              <Column field="tanggal" header="Tanggal" />
                              <Column field="transaksi" header="Jenis Pendapatan" />
                              <Column field="kettransaksi" header="Keterangan Pendapatan" />
                              <Column field="nonhistori" header="Nomor Histori" />

                              <Column field="total" header="Total Pendapatan" style="text-align:right">

                                <template #body="slotProps">
                                  {{ H.formatRupiah(slotProps.data.total, 'Rp.') }}
                                </template>
                              </Column>
                            </DataTable>
                          </div>

                        </div>


                      </TabPanel>
                    </TabView>
                  </VCard>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </VCard>
  </div>

</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import Card from 'primevue/card';
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext';
import Fieldset from 'primevue/fieldset';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import ToggleButton from 'primevue/togglebutton';
useHead({
  title: 'Rekap Pendapatan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const totalDisetujui:any = ref(0)
const totalTarifRs:any = ref(0)
const surplusDefisit:any = ref(0)
const potensiPendapatan:any = ref(0)
const potensiPendapatanNon:any = ref(0)
const arrGroup: any = ref({
  'bulan': new Date(),
  jkn_rajal: 0,
  Eks_Asuransi_rajal: 0,
  Eks_perusahaan_rajal: 0,
  Reg_Asuransi_rajal: 0,
  Reg_perusahaan_rajal: 0,
  Reg_umum_rajal: 0,
  jkn_ranap: 0,
  Eks_Asuransi_ranap: 0,
  Eks_perusahaan_ranap: 0,
  Eks_umum_ranap: 0,
  Reg_Asuransi_ranap: 0,
  Reg_perusahaan_ranap: 0,
  Reg_umum_ranap: 0,
  pendapatan_lain: 0,
  jumlah: 0
}
)
const arrGroup2: any = ref({
  'bulan': new Date(),
  'bebanPegBLU': 0,
  'bebanPersediaanNonF': 0,
  'bebanPersediaanF': 0,
  'bebanBarangJasa': 0,
  'bebanPemeliharaan': 0,
  'bebanPerdin': 0,
  'bebanPenyisihanPiut': 0,
  'bebanamorti': 0,
  'jumlah': 0,
  'bebanPegAPBN': 0
}
)
const arrGroup3: any = ref({
  'bulan': new Date(),
  'pendapatan_bunga_bank': 0,
  'deposito': 0,
  'pend_lainnya': 0,
  'pend_apbn_lainnya': 0,
  'pendapatan_hibah': 0,
  'pendapatan_blu_lainnya': 0,
  'biaya_bunga_bank': 0,
  'manfaat_beban': 0,
  'jumlah': 0,
})
const EBITDA: any = ref(0)
const SURPLUS: any = ref(0)
const EBITDA_BEBAN: any = ref(0)
const SURPLUS_SEBELUM_PAJAK: any = ref(0)
const item: any = ref({
  qBulan: [
    new Date(),
    new Date()
  ],

  query: `

SELECT
to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,
     kps.kelompokpasien,(((CASE WHEN pp.hargadijamin IS NULL THEN pp.hargajual WHEN pp.hargadijamin = 0 THEN pp.hargajual ELSE pp.hargadijamin END -	CASE WHEN pp.hargadiscount IS NULL THEN	0 ELSE pp.hargadiscount END) * pp.jumlah)
     + CASE WHEN pp.jasa IS NULL THEN	0 ELSE pp.jasa END) AS total,
     pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan
FROM pelayananpasien_t AS pp
JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
WHERE  pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'
AND pp.strukresepfk IS NULL AND pd.statusenabled = TRUE AND pp.statusenabled = TRUE and dpm.statusenabled = true
--AND pd.tglpulang IS NOT NULL

UNION ALL

SELECT to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
     (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
     CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS total,
                            pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan
FROM strukresep_t AS sr
JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
WHERE  pd.statusenabled = TRUE AND pp.statusenabled = TRUE AND sr.statusenabled = TRUE
AND pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
--AND pd.tglpulang IS NOT NULL
AND pp.jumlah > 0 and dpm.statusenabled = true

UNION ALL

SELECT to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
     (((CASE WHEN pps.hargajual IS NULL THEN 0 ELSE pps.hargajual END - CASE WHEN pps.hargadiscount IS NULL THEN 0 ELSE pps.hargadiscount END) * pp.jumlah) +
     CASE WHEN pps.jasa IS NULL THEN 0 ELSE pps.jasa END) AS total,
                            pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan
FROM strukresep_t AS sr
INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
INNER JOIN pelayananpasien_t AS pps ON pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
WHERE  pd.statusenabled = TRUE AND pps.statusenabled = TRUE AND sr.statusenabled = TRUE
AND pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
AND pp.jumlah > 0 and dpm.statusenabled = true
--AND pd.tglpulang IS NOT NULL

UNION ALL

SELECT to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dp.namadepartemen,'Umum/Pribadi' AS kelompokpasien,
(spd.qtyproduk * ( spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END ) +
CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
                      1 as objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,1 as jenispelayanan,		to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan
FROM strukpelayanan_t AS sp
JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
AND sp.nostruk LIKE'OB%' AND sp.statusenabled = true AND spd.qtyproduk > 0 and dp.statusenabled = true

UNION ALL

SELECT to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,'Non Layanan' AS namaruangan,'Instalasi Rawat Jalan dan Rehabilitasi Medik' AS namadepartemen,
     'Umum/Pribadi' AS kelompokpasien,(spd.qtyproduk * (spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END) +
     CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
                            1 as objectkelompokpasienlastfk,18 as kddepartemen, 1 as jenispelayanan,	to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan
FROM strukpelayanan_t AS sp
JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
AND substring(sp.nostruk,0,3) = 'NL' AND sp.statusenabled = true

    `
})
const isLoading2: any = ref(false)
const dataKunjungan: any = ref([])
const dataPasien: any = ref([])
const dataPasien2: any = ref([])
const dataPasien3: any = ref([])
const isLoading = ref(false);
const isLoading3 = ref(false);
const rowClass = (data) => {
    // return [{ 'bg-error': data.proporsi === 0 }];
    return [{ 'bg-s': data.proporsi === 0 }];
};
const rowStyle = (data) => {
    if (data.quantity === 0) {
        return { fontWeight: 'bold', fontStyle: 'italic' };
    }
};
const proporsiFrozen = ref(true);
const fetchData = async () => {
  EBITDA.value = 0
  SURPLUS.value = 0
  EBITDA_BEBAN.value = 0
  SURPLUS_SEBELUM_PAJAK.value = 0
  let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-DD")
  let tglAkhir = H.formatDate(item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0], "YYYY-MM-DD")

  item.value.tglAwal = new Date(tglAwal)
  item.value.tglAkhir = new Date(tglAkhir)

  let queryParam = item.value.query
  queryParam = queryParam.replaceAll('$START_DATE', tglAwal + ' 00:00:00')
  queryParam = queryParam.replaceAll('$END_DATE', tglAkhir + ' 23:59:59')
  isLoading.value = true

  arrGroup.value = {
    'bulan': new Date(),
    jkn_rajal: 0,
    Eks_Asuransi_rajal: 0,
    Eks_perusahaan_rajal: 0,
    Reg_Asuransi_rajal: 0,
    Reg_perusahaan_rajal: 0,
    Reg_umum_rajal: 0,
    jkn_ranap: 0,
    Eks_Asuransi_ranap: 0,
    Eks_perusahaan_ranap: 0,
    Eks_umum_ranap: 0,
    Reg_Asuransi_ranap: 0,
    Reg_perusahaan_ranap: 0,
    Reg_umum_ranap: 0,
    pendapatan_lain: 0,
    jumlah: 0
  }

  arrGroup2.value = {
    'bulan': new Date(),
    'bebanPegBLU': 0,
    'bebanPersediaanNonF': 0,
    'bebanPersediaanF': 0,
    'bebanBarangJasa': 0,
    'bebanPemeliharaan': 0,
    'bebanPerdin': 0,
    'bebanPenyisihanPiut': 0,
    'bebanamorti': 0,
    'jumlah': 0,
    'bebanPegAPBN': 0
  }
  arrGroup3.value = {
    'bulan': new Date(),
    'pendapatan_bunga_bank': 0,
    'deposito': 0,
    'pend_lainnya': 0,
    'pend_apbn_lainnya': 0,
    'pendapatan_hibah': 0,
    'pendapatan_blu_lainnya': 0,
    'biaya_bunga_bank': 0,
    'manfaat_beban': 0,
    'jumlah': 0,
  }
  totalDisetujui.value = 0
  totalTarifRs.value = 0
  surplusDefisit.value = 0
  potensiPendapatan.value = 0
  potensiPendapatanNon.value = 0
  // let response =  await useApi().post(`mkko/lap-jml-pendapatan`, {
  //   query: queryParam,
  //   dari:tglAwal  + ' 00:00:00',
  //   sampai:tglAkhir + ' 23:59:59'
  // })
  let dari = tglAwal + ' 00:00:00'
  let sampai = tglAkhir + ' 23:59:59'
  let response = await useApi().get(`mkko/lap-jml-pendapatan?dari=${dari}&sampai=${sampai}`)
  totalDisetujui.value = 0
  totalTarifRs.value = 0
  surplusDefisit.value = 0
  response.detail.forEach((element: any, i: any) => {
    element.no = i + 1
    element.Reg_umum_rajal = parseFloat(element.Reg_umum_rajal).toFixed(2)
    if(element.kelompokpasien == 'BPJS' && element.pengajuan !=0){
      totalDisetujui.value = totalDisetujui.value +  parseFloat(element.proporsi)
      totalTarifRs.value= totalTarifRs.value +  parseFloat(element.total)
    }
    if(element.kelompokpasien == 'BPJS' ){
      potensiPendapatan.value = potensiPendapatan.value +  parseFloat(element.total)
    }else{
      potensiPendapatanNon.value = potensiPendapatanNon.value +  parseFloat(element.total)
    }

  // surplusDefisit.value = totalDisetujui.value +  parseFloat(element.klaim)
  });
  surplusDefisit.value = totalDisetujui.value - totalTarifRs.value;


  isLoading.value = false
  dataKunjungan.value = response.data
  dataPasien.value = response.detail

  groupMonth(dataKunjungan.value)


  await fetchData2()
  await fetchData3()
}
const fetchData2 = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-DD 00:00:00")
  let tglAkhir = H.formatDate(item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0], "YYYY-MM-DD 23:59:59")
  dataPasien2.value = []
  isLoading3.value = true
  let response = await useApi().get(`mkko/lap-beban-usaha?dari=${tglAwal}&sampai=${tglAkhir}`)
  response.detail.forEach((element: any, i: any) => {
    element.no = i + 1
  });


  isLoading3.value = false
  dataPasien2.value = response.detail
  if (response.data.length)
    groupbbEBAN(response.data)

}
const fetchData3 = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], "YYYY-MM-DD 00:00:00")
  let tglAkhir = H.formatDate(item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0], "YYYY-MM-DD 23:59:59")

  isLoading.value = true
  let response = await useApi().get(`mkko/lap-pendapatan-keuangan?dari=${tglAwal}&sampai=${tglAkhir}`)
  response.detail.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  isLoading.value = false
  dataPasien3.value = response.detail
  if (response.data.length)
    groupPendapatan(response.data)//groupMonth(  dataKunjungan.value )



}
const groupbbEBAN = (result: any) => {
  for (let i = 0; i < result.length; i++) {
    arrGroup2.value.bebanPegBLU = arrGroup2.value.bebanPegBLU + result[i].bebanPegBLU
    arrGroup2.value.bebanPersediaanNonF = arrGroup2.value.bebanPersediaanNonF + result[i].bebanPersediaanNonF
    arrGroup2.value.bebanPersediaanF = arrGroup2.value.bebanPersediaanF + result[i].bebanPersediaanF
    arrGroup2.value.bebanBarangJasa = arrGroup2.value.bebanBarangJasa + result[i].bebanBarangJasa
    arrGroup2.value.bebanPemeliharaan = arrGroup2.value.bebanPemeliharaan + result[i].bebanPemeliharaan
    arrGroup2.value.bebanPerdin = arrGroup2.value.bebanPerdin + result[i].bebanPerdin
    arrGroup2.value.bebanPenyisihanPiut = arrGroup2.value.bebanPenyisihanPiut + result[i].bebanPenyisihanPiut
    arrGroup2.value.bebanamorti = arrGroup2.value.bebanamorti + result[i].bebanamorti
    arrGroup2.value.jumlah = arrGroup2.value.jumlah + result[i].jumlah
    arrGroup2.value.bebanPegAPBN = arrGroup2.value.bebanPegAPBN + result[i].bebanPegAPBN
  }

  EBITDA.value =( arrGroup.value.jumlah - arrGroup2.value.jumlah )+ arrGroup2.value.bebanamorti
  SURPLUS.value = arrGroup.value.jumlah - arrGroup2.value.jumlah
  EBITDA_BEBAN.value = ((arrGroup.value.jumlah - arrGroup2.value.jumlah) + arrGroup2.value.bebanamorti) - arrGroup2.value.bebanPegAPBN


  EBITDA.value = EBITDA.value.toFixed(2)
  SURPLUS.value = SURPLUS.value.toFixed(2)
  EBITDA_BEBAN.value = EBITDA_BEBAN.value.toFixed(2)
}
const groupMonth = (result: any) => {

  for (let i = 0; i < result.length; i++) {
    arrGroup.value.jkn_rajal = arrGroup.value.jkn_rajal + result[i].jkn_rajal
    arrGroup.value.Eks_Asuransi_rajal = arrGroup.value.Eks_Asuransi_rajal + result[i].Eks_Asuransi_rajal
    arrGroup.value.Eks_perusahaan_rajal = arrGroup.value.Eks_perusahaan_rajal + result[i].Eks_perusahaan_rajal
    arrGroup.value.Reg_Asuransi_rajal = arrGroup.value.Reg_Asuransi_rajal + result[i].Reg_Asuransi_rajal
    arrGroup.value.Reg_perusahaan_rajal = arrGroup.value.Reg_perusahaan_rajal + result[i].Reg_perusahaan_rajal
    arrGroup.value.Reg_umum_rajal = arrGroup.value.Reg_umum_rajal + result[i].Reg_umum_rajal
    arrGroup.value.jkn_ranap = arrGroup.value.jkn_ranap + result[i].jkn_ranap
    arrGroup.value.Eks_Asuransi_ranap = arrGroup.value.Eks_Asuransi_ranap + result[i].Eks_Asuransi_ranap
    arrGroup.value.Eks_perusahaan_ranap = arrGroup.value.Eks_perusahaan_ranap + result[i].Eks_perusahaan_ranap
    arrGroup.value.Eks_umum_ranap = arrGroup.value.Eks_umum_ranap + result[i].Eks_umum_ranap
    arrGroup.value.Reg_Asuransi_ranap = arrGroup.value.Reg_Asuransi_ranap + result[i].Reg_Asuransi_ranap
    arrGroup.value.Reg_perusahaan_ranap = arrGroup.value.Reg_perusahaan_ranap + result[i].Reg_perusahaan_ranap
    arrGroup.value.Reg_umum_ranap = arrGroup.value.Reg_umum_ranap + result[i].Reg_umum_ranap
    arrGroup.value.pendapatan_lain = arrGroup.value.pendapatan_lain + result[i].pendapatan_lain
    arrGroup.value.jumlah = arrGroup.value.jumlah + result[i].jumlah
  }
}
const groupPendapatan = (result: any) => {
  for (let i = 0; i < result.length; i++) {
    arrGroup3.value.pendapatan_bunga_bank = arrGroup3.value.pendapatan_bunga_bank + result[i].pendapatan_bunga_bank
    arrGroup3.value.deposito = arrGroup3.value.deposito + result[i].deposito
    arrGroup3.value.pend_lainnya = arrGroup3.value.pend_lainnya + result[i].pend_lainnya
    arrGroup3.value.pend_apbn_lainnya = arrGroup3.value.pend_apbn_lainnya + result[i].pend_apbn_lainnya
    arrGroup3.value.pendapatan_hibah = arrGroup3.value.pendapatan_hibah + result[i].pendapatan_hibah
    arrGroup3.value.pendapatan_blu_lainnya = arrGroup3.value.pendapatan_blu_lainnya + result[i].pendapatan_blu_lainnya
    arrGroup3.value.biaya_bunga_bank = arrGroup3.value.biaya_bunga_bank + result[i].biaya_bunga_bank
    arrGroup3.value.manfaat_beban = arrGroup3.value.manfaat_beban + result[i].manfaat_beban
    arrGroup3.value.jumlah = arrGroup3.value.jumlah + result[i].jumlah
  }
  SURPLUS_SEBELUM_PAJAK.value = (arrGroup.value.jumlah - arrGroup2.value.jumlah) - (arrGroup3.value.pend_apbn_lainnya +
    arrGroup3.value.pendapatan_hibah + arrGroup3.value.pendapatan_blu_lainnya + arrGroup3.value.biaya_bunga_bank)
  SURPLUS_SEBELUM_PAJAK.value = SURPLUS_SEBELUM_PAJAK.value.toFixed(2)
}
const kirimData = async () => {
  if (dataKunjungan.value.length == 0) {
    H.alert('error', 'Data belum ada')
    return
  }

  isLoading2.value = true
  for (let index = 0; index < dataKunjungan.value.length; index++) {
    const rekap = dataKunjungan.value[index];
    await useApi().post(`mkko/api-integrate`,
      {
        url: 'keuangan',
        method: 'POST',
        data: {
          "tanggal": rekap.bulan,
          "detail": {
            "pendapatan_rs": {
              "outpatient_revenue": {
                "pasien_jkn": {
                  "jkn_reguler": rekap.jkn_rajal,
                  "jkn_naikkelas": 0
                },
                "pasien_non_jkn_eksekutif": {
                  "asuransi": rekap.Eks_Asuransi_rajal,
                  "jaminan_perusahaan": rekap.Eks_perusahaan_rajal,
                  "pembayaran_mandiri": rekap.Eks_umum_rajal,
                },
                "pasien_non_jkn_reguler": {
                  "asuransi": rekap.Reg_Asuransi_rajal,
                  "jaminan_perusahaan": rekap.Reg_perusahaan_rajal,
                  "pembayaran_mandiri": rekap.Reg_umum_rajal,
                }
              },
              "inpatient_revenue": {
                "pasien_jkn": {
                  "jkn_reguler": rekap.jkn_ranap,
                  "jkn_naikkelas": 0
                },
                "pasien_non_jkn_eksekutif": {
                  "asuransi": rekap.Eks_Asuransi_ranap,
                  "jaminan_perusahaan": rekap.Eks_perusahaan_ranap,
                  "pembayaran_mandiri": rekap.Eks_umum_ranap,
                },
                "pasien_non_jkn_reguler": {
                  "asuransi": rekap.Reg_Asuransi_ranap,
                  "jaminan_perusahaan": rekap.Reg_perusahaan_ranap,
                  "pembayaran_mandiri": rekap.Reg_umum_ranap,
                }
              },
              "pendapatan_layanan_lain": rekap.pendapatan_lain,
            },
            "rba_pendapatan": "0",
            "beban_pokok_pendapatan": {
              "beban_pegawai": arrGroup2.value.bebanPegBLU
            },
            "beban_administrasi_umum": {
              "beban_barang_jasa": arrGroup2.value.bebanBarangJasa,
              "beban_pemeliharaan":  arrGroup2.value.bebanPemeliharaan,
              "beban_perjalanan_dinas":  arrGroup2.value.bebanPerdin,
              "beban_penyisihan_piutang_tak_tertagih":  arrGroup2.value.bebanPenyisihanPiut,
            },
            "beban_persediaan": {
              "beban_persediaan_farmasi":   arrGroup2.value.bebanPersediaanF,
              "beban_persediaan_non_farmasi":   arrGroup2.value.bebanPersediaanNonF,
            },
            "beban_penyusutan_dan_amortisasi": arrGroup2.value.bebanamorti,
            "surplus_defisit_usaha":SURPLUS.value,
            "depresiasi_amortisasi": arrGroup2.value.bebanamorti,
            "EBITDA":EBITDA.value,
            "beban_pegawai":arrGroup2.value.bebanPegAPBN,
            "EBITDA_plus_beban_pegawai": EBITDA_BEBAN.value,
            "pendapatan_keuangan": {
              "pendapatan_bunga_bank": arrGroup3.value.pendapatan_bunga_bank,
              "deposito": arrGroup3.value.deposito,
              "pend_lainnya": arrGroup3.value.pend_lainnya,
              "jumlah_pendapatan_keuangan": arrGroup3.value.pendapatan_bunga_bank+ arrGroup3.value.deposito+ arrGroup3.value.pend_lainnya,
            },
            "biaya_keuangan": "0",
            "pendapatan_biaya_lain_lain": {
              "pend_apbn_lainnya": arrGroup3.value.pend_apbn_lainnya,
              "pendapatan_hibah":arrGroup3.value.pendapatan_hibah,
              "pendapatan_blu_lainnya":arrGroup3.value.pendapatan_blu_lainnya,
              "jumlah_pendapatan_lain_lain": arrGroup3.value.pend_apbn_lainnya+arrGroup3.value.pendapatan_hibah+arrGroup3.value.pendapatan_blu_lainnya,
            },
            "surplus_usaha_sebelum_pajak": SURPLUS_SEBELUM_PAJAK.value,
            "manfaat_beban_pajak":arrGroup3.value.manfaat_beban,
            "surplus_bersih": SURPLUS_SEBELUM_PAJAK.value

          }
        }
      })
  }
  isLoading2.value = false
}
const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}
fetchData()
// fetchData2()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.span-css {
  width: 500px !important;
}

.p-card .p-card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

// .title-page {
//   font-weight: 600;
//   font-size: 18px;
// }

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
  vertical-align: middle;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
  text-align: center !important;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      >img {
        display: block;
        width: 200px;
        height: 200px;
        min-width: 200px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.8rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}
</style>
