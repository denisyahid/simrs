<template>
  <div>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>
                {{ props.FORM_NAME }}
              </h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun" isHideST>
              </ButtonEmr>
            </div>
          </div>
        </div>

        <!-- form baru -->

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-6">
              <VField label="DIAGNOSA">
                <VControl>
                    <VInput type="text" class="input" v-model="input.Diagnosa" />
                </VControl>
              </VField>
          </div>
          <div class="column is-4">
            <VField>

            </VField>
          </div>
          <div class="column is-6">
            <VField label="TINDAKAN KATETERISASI :">
              <VField>
                  <VTextarea rows="2" v-model="input.TindakanKateterisasi"></VTextarea>
              </VField>
            </VField>
          </div>
          </div>
        </div>
        <div class="column is-12">
          <table class="table is-bordered" style="border: 1px solid black;">
            <tr>
              <th colspan="6" class="lightgrey has-text-centered">PRE KATETERISASI</th>
            </tr>
            <tr class="has-text-centered">
              <th width="2%" rowspan="2">No</th>
              <th width="58%" rowspan="2" colspan="2">HAL-HAL YANG DISERAHKAN OLEH PETUGAS RUANGAN</th>
              <th width="20%" colspan="2">HAL YANG DITERIMA OLEH PETUGAS RUANG CATH-LAB</th>
              <th width="20%" rowspan="2">Ket</th>
            </tr>
            <tr class="has-text-centered">
                <th> Ya</th>
                <th>Tidak</th>
            </tr>
            <tr>
              <th rowspan="3">1</th>
              <th rowspan="3">INFORMED CONSENT</th>
              <th>Tindakan kedokteran dan therapi beresiko tinggi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.therapi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.therapi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetTherapi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Anesthesi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Anesthesi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Anesthesi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetAnesthesi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Tranfusi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Tranfusi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Tranfusi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetTranfusi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>2</th>
              <th colspan="2">HASILPEMERIKSAAN LABORATORIUM (DL, Ureum, Kreatinin, PT/APTT, BT/CT, Na, K, HbsAg)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Hasillab"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Hasillab"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHasillab" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th rowspan="7">3</th>
              <th rowspan="7">HASIL PEMERIKSAAN PENUNJANG</th>
              <th>Rontgen</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Rontgen"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Rontgen"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetRontgen" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Ekokardiografi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Ekokardiografi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Ekokardiografi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetEkokardiografi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Treadmill test</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Treadmilltest"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Treadmilltest"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetTreadmilltest" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>EKG</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.EKG"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.EKG"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetEKG" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Holter</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Holter"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Holter"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHolter" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Kateterisasi sebelumnya</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Kateterisasisebelumnya"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Kateterisasisebelumnya"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetKateterisasisebelumnya" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>
              <div class="columns is-multiline">
                <div class="column is-5">
                  Lain-lain:
                </div>
                <div class="column is-7">
                  <VControl>
                      <VInput type="text" class="input" v-model="input.TBlainlain" />
                  </VControl>
                </div>
              </div>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.PenunjangLain"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.PenunjangLain"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPenunjangLain" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th rowspan="3">4</th>
              <th rowspan="3">HASIL KONSUL</th>
              <th>Penyakit dalam</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Penyakitdalam"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Penyakitdalam"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPenyakitdalam" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Anestesi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Anestesi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Anestesi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetAnestesi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>
              <div class="columns is-multiline">
                <div class="column is-5">
                  Divisi lain:
                </div>
                <div class="column is-7">
                  <VControl>
                      <VInput type="text" class="input" v-model="input.TBDivisilain" />
                  </VControl>
                </div>
              </div>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.DivisiLain"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.DivisiLain"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetDivisiLain" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th rowspan="3">5</th>
              <th rowspan="3">PENCUKURAN AREAPUNKSI ARTERI DAN VENA</th>
              <th>Pubis dan inguinalis kanan dan kiri (untuk akses arteri/vena femoralis)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Pubis"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Pubis"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPubis" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Lengan kanan bawah (untuk akses arteri/vena radialis kanan)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Lengankananbawah"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Lengankananbawah"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetLengankananbawah" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>
              <div class="columns is-multiline">
                  Dada (untuk pemasangan PPM)
              </div>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Dada"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Dada"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetDada" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>6</th>
              <th colspan="2">HASILPEMERIKSAAN LABORATORIUM (DL, Ureum, Kreatinin, PT/APTT, BT/CT, Na, K, HbsAg)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Hasillab2"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Hasillab2"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHasillab2" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>7</th>
              <th colspan="2">PEMASANGAN KONDOM KATETER UNTUK LAKI-LAKI DAN DOUWER KATETER UNTUK
                PEREMPUAN (KECUALI PASIEN ANAK-ANAK)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.PemasanganKondom"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.PemasanganKondom"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPemasanganKondom" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>8</th>
              <th colspan="2">
                <div class="columns is-multiline">
                  <div class="column is-5">
                    PUASA MAKAN MULAI JAM:
                  </div>
                  <div class="column is-7">
                    <VDatePicker v-model="input.puasamakanJam" mode="time" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </div>
               </div>
                <div class="columns is-multiline">
                  <div class="column is-5">
                    PUASA MINUM MULAI JAM:
                  </div>
                  <div class="column is-7">
                    <VDatePicker v-model="input.puasaminumJam" mode="time" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </div>
               </div>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.PUASAMAKAN"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.PUASAMAKAN"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPUASAMAKAN" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>9</th>
              <th colspan="2">MENGGUNAKAN PAKAIAN KHUSUS OPERASI (BAJU DAN TOPI)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.PAKAIANKHUSUSOPERASI"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.PAKAIANKHUSUSOPERASI"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPAKAIANKHUSUSOPERASI" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>10</th>
              <th colspan="2">HAPUS MAKE UP, HAPUS CATKUKU, LEPAS PERHIASAN, PROTESA, GIGI PALSU, KACAMATA</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.HapusMakeUp"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.HapusMakeUp"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHapusMakeUp" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>11</th>
              <th colspan="2">
                <div class="columns is-multiline"> 
                  <div class="column is-12">
                    OBSERVASI VITALSIGN TERAKHIR JAM :
                  </div>
                  <div class="column is-1">TD:</div>
                  <div class="column is-3" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbTD" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>mmHg</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1">N:</div>
                  <div class="column is-3" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbN" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>X/mnt</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1">R:</div>
                  <div class="column is-3" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbR" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>X/mnt</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1">S:</div>
                  <div class="column is-3" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbS" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static><sup>o</sup>C</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1">BB:</div>
                  <div class="column is-3" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbBB" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>kg</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1">TB:</div>
                  <div class="column is-3" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbTB" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>cm</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-3">SKALA NYERI :</div>
                  <div class="column is-7">
                    <Multiselect v-model="input.SkalaNyeri" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_skalanyeri" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                  <div class="column is-5">CAIRAN MASUK 24 JAM :</div>
                  <div class="column is-7">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.cairanmasuk24jam" />
                    </VControl>
                  </div>
                  <div class="column is-5">CAIRAN KELUAR 24 JAM :</div>
                  <div class="column is-7">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.cairankeluar24jam" />
                    </VControl>
                  </div>
                </div>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.VitalSign"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.VitalSign"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetVitalSign" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>12</th>
              <th colspan="2">PERSIAPAN TRANSFUSI</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.PERSIAPANTRANSFUSI"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.PERSIAPANTRANSFUSI"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPERSIAPANTRANSFUSI" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>13</th>
              <th colspan="2">
                 <div class="columns is-multiline">
                 <div class="column is-12">
                  OBAT-OBATAN PREMIDIKASI DI RUANGAN :
                  </div>
                  <div class="column is-6 pt-0">
                       <VButtons style="justify-content: space-around">
                        <VIconButton type="button" raised circle 
                          icon="feather:plus" @click="addnewItem()" 
                          color="info" v-tooltip.bubble="'Tambah'">
                        </VIconButton>
                        </VButtons>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                          <div class="column is-4 pt-0" v-for="(item, index) in input.details" :key="NO">
                            <VButtons style="justify-content: space-around">
                              <VIconButton class="mt-1" v-if="input.details.length > 1" type="button" 
                                raised circle icon="feather:trash" 
                                @click="removeItem(index)" color="danger">
                              </VIconButton>
                            </VButtons>
                            <VControl>
                              <VInput type="text" class="input" v-model="item.TBObatPremidikasi" />
                            </VControl>
                          </div>
                      </div>
                    </div>
                 </div>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.ObatPremidikasi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.ObatPremidikasi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetObatPremidikasi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>14</th>
              <th colspan="2">
                 <div class="columns is-multiline">
                 <div class="column is-12">
                  OBATORAL& PARENTERAL(DOSIS & JAM) :
                  </div>
                  <div class="column is-6 pt-0">
                      <VButtons style="justify-content: space-around">
                        <VIconButton type="button" raised circle 
                          icon="feather:plus" @click="addnewItem2()" 
                          color="info" v-tooltip.bubble="'Tambah'">
                        </VIconButton>
                      </VButtons>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                          <div class="column is-4 pt-0" v-for="(item, index) in input.details2" :key="NO">
                          <VButtons style="justify-content: space-around">
                            <VIconButton class="mt-1" v-if="input.details2.length > 1" type="button" 
                              raised circle icon="feather:trash" 
                              @click="removeItem2(index)" color="danger">
                            </VIconButton>
                          </VButtons>
                            <VControl>
                              <VInput type="text" class="input" v-model="item.TBObatOral" />
                            </VControl>
                          </div>
                      </div>
                    </div>
                 </div>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.ObatOral"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.ObatOral"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetObatOral" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th rowspan="4">15</th>
              <th rowspan="4">ALLERGI</th>
              <th>Riwayat allergi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Riwayatallergi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Riwayatallergi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetRiwayatallergi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Terpasang gelang tanda allergi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Terpasanggelangtandaallergi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Terpasanggelangtandaallergi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetTerpasanggelangtandaallergi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Jenis allergi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Jenisallergi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Jenisallergi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetJenisallergi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Reaksi allergi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Reaksiallergi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Reaksiallergi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetReaksiallergi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>16</th>
              <th colspan="2">PULSASI ARTERI RADIALIS &DORSALIS PEDIS</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.PulsasiArteri"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.PulsasiArteri"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPulsasiArteri" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>17</th>
              <th colspan="2">HAEMATOM / PERDARAHAN</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Haematom"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Haematom"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHaematom" />
                </VControl>
              </th>
            </tr>
          </table>
          <div class="columns" style="padding-top: 40px; padding-bottom: 60px;">
            <div class="column is-4" style="text-align: center;">
                <h1>PETUGAS RUANGAN YANG MENYERAHKAN</h1>
                <TandaTangan :elemenID="'TTDPetugasMenyerahkan'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                    <AutoComplete v-model="input.DDPetugasMenyerahkan" :suggestions="d_Petugas"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketika nama petugas..." />
                </VControl>
            </div>
            <div class="column is-4">
              
            </div>
            <div class="column is-4" style="text-align: center;">
                <h1>PETUGAS CATH-LAB YANG MENERIMA</h1>
                <TandaTangan :elemenID="'TTDPetugasChatlab'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                    <AutoComplete v-model="input.DDPetugasChatlab" :suggestions="d_Petugas"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketika nama petugas..." />
                </VControl>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-1" style="margin-top: 10px;">Tanggal:</div>
            <div class="column is-4">
              <VDatePicker v-model="input.tanggaljamPre" mode="date" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-1" style="margin-top: 10px;">Jam:</div>
            <div class="column is-4">
              <VDatePicker v-model="input.tanggaljamPre" mode="time" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
        </div>
        <hr>
        <div class="column is-12">
          <table class="table is-bordered" style="border: 1px solid black;">
            <tr>
              <th colspan="6" class="lightgrey has-text-centered">POST KATETERISASI</th>
            </tr>
            <tr class="has-text-centered">
              <th width="2%" rowspan="2">No</th>
              <th width="58%" rowspan="2" colspan="2">HAL-HAL YANG DISERAHKAN OLEH PETUGAS CATH-LAB</th>
              <th width="20%" colspan="2">HAL YANG DITERIMA OLEH PETUGAS RUANG CATH-LAB</th>
              <th width="20%" rowspan="2">Ket</th>
            </tr>
            <tr class="has-text-centered">
                <th> Ya</th>
                <th>Tidak</th>
            </tr>
            <tr>
              <th rowspan="6">1</th>
              <th colspan="2">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    OBSERVASI VITALSIGN TERAKHIR JAM :
                  </div>
                  <div class="column is-1">TD:</div>
                  <div class="column is-5" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbTDterakhir" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>mmHg</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1">RR:</div>
                  <div class="column is-5" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbRRTerakhir" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>X/mnt</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1">NADI:</div>
                  <div class="column is-5" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbNadiTerakhir" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>X/mnt</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1">SUHU:</div>
                  <div class="column is-5" style="margin-top: -10px;">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbSuhuterakhir" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static><sup>o</sup>C</VButton>
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-3">SKALA NYERI :</div>
                  <div class="column is-7">
                    <Multiselect v-model="input.SkalanyeriAkhir" :attrs="{ value }" placeholder="--Pilih--" label="label"
                        :options="d_skalanyeri" :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </div>
                </div>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.VITALSIGNTERAKHIR"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.VITALSIGNTERAKHIR"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetVITALSIGNTERAKHIR" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th rowspan="2">CAIRAN MASUK INTRAKATETERISASI</th>
              <th>
                <div class="columns is-multiline">
                  <div class="column is-3" style="margin-top: 10px;">Kontras:</div>
                  <div class="column is-7">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbKontras" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>ml</VButton>
                          </VControl>
                      </VField>
                  </div>
              </div>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Kontras1"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Kontras1"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetKontras1" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>
                <div class="columns is-multiline">
                  <div class="column is-3" style="margin-top: 10px;">Kontras:</div>
                  <div class="column is-7">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbKontras2" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>ml</VButton>
                          </VControl>
                      </VField>
                  </div>
              </div>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Kontras2"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Kontras2"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetKontras2" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th rowspan="3">CAIRAN KELUAR INTRAKATETERISASI</th>
              <th>
                <div class="columns is-multiline">
                  <div class="column is-3" style="margin-top: 10px;">Urine:</div>
                  <div class="column is-7">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbUrine" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>ml</VButton>
                          </VControl>
                      </VField>
                  </div>
              </div>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Urine"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Urine"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetUrine" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>
                <div class="columns is-multiline">
                  <div class="column is-3" style="margin-top: 10px;">Muntah:</div>
                  <div class="column is-7">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbMuntah" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>ml</VButton>
                          </VControl>
                      </VField>
                  </div>
              </div>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Muntah"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Muntah"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetMuntah" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>
                <div class="columns is-multiline">
                  <div class="column is-3" style="margin-top: 10px;">Perdarahan:</div>
                  <div class="column is-7">
                      <VField addons style="padding: 5px;padding-top:0px"
                          >
                          <VControl>
                              <VInput type="text" class="input"
                                  v-model="input.TbPerdarahan" />
                          </VControl>
                          <VControl class="field-addon-body">
                              <VButton static>ml</VButton>
                          </VControl>
                      </VField>
                  </div>
              </div>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Perdarahan"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Perdarahan"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPerdarahan" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>2</th>
              <th colspan="2">HASIL PEMERIKSAAN LABORATORIUM (DL, Ureum, Kreatinin, PT/APTT, BT/CT, Na, K, HbsAg)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Hasillab3"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Hasillab3"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHasillab3" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>2</th>
              <th colspan="2">HASILPEMERIKSAAN LABORATORIUM (DL, Ureum, Kreatinin, PT/APTT, BT/CT, Na, K, HbsAg)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.Hasillab"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.Hasillab"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHasillab" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th rowspan="7">3</th>
              <th rowspan="7">HASIL PEMERIKSAAN PENUNJANG</th>
              <th>Rontgen</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.RontgenPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.RontgenPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetRontgenPost" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Ekokardiografi</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.EkokardiografiPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.EkokardiografiPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetEkokardiografiPost" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Treadmill test</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.TreadmilltestPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.TreadmilltestPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetTreadmilltestPost" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>EKG</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.EKGPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.EKGPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetEKGPost" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Holter</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.HolterPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.HolterPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHolterPost" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>Kateterisasi sebelumnya</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.KateterisasisebelumnyaPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.KateterisasisebelumnyaPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetKateterisasisebelumnyaPost" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>
              <div class="columns is-multiline">
                <div class="column is-5">
                  Lain-lain:
                </div>
                <div class="column is-7">
                  <VControl>
                      <VInput type="text" class="input" v-model="input.TBlainlainPost" />
                  </VControl>
                </div>
              </div>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.PenunjangLainPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.PenunjangLainPost"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetPenunjangLainPost" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>4</th>
              <th colspan="2">HASIL KATETERISASI (FOTO/CD)</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.HasilKateterisasi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.HasilKateterisasi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetHasilKateterisasi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>5</th>
              <th colspan="2">LAPORAN KATETERISASI</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.LaporanKateterisasi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.LaporanKateterisasi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetLaporanKateterisasi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>6</th>
              <th colspan="2">CATATAN ANESTESI</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.CatatanAnestesi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.CatatanAnestesi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetCatatanAnestesi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>7</th>
              <th colspan="2">KITIR TINDAKAN KATETERISASI</th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.TindakanKateterisasi"
                    />
                </VControl>
              </th>
              <th>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.TindakanKateterisasi"
                    />
                </VControl>
              </th>
              <th>
                <VControl>
                    <VInput type="text" class="input" v-model="input.KetTindakanKateterisasi" />
                </VControl>
              </th>
            </tr>
            <tr>
              <th>8</th>
              <th colspan="2">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    PERAWATAN POSTKATETERISASI
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Observasi kesadaran vital sign, reaksi alergi'"
                            label="Observasi kesadaran vital sign, reaksi alergi."
                            v-model="input.kesadaranvitalsign"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Monitor intake cairan dan pengeluaran urine'"
                            label="Monitor intake cairan dan pengeluaran urine."
                            v-model="input.Monitorintakecairan"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Evaluasi perfusi ke perifer'"
                            label="Evaluasi perfusi ke perifer dan denyut arteri dorsalis pedis kanan dan kiri kemudian bandingkan kekuatannya."
                            v-model="input.Evaluasiperfusikeperifer"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Observasi perdarahan/haematom di area post punksi'"
                            label="Observasi perdarahan/haematom di area post punksi (arteri radialis kanan/kiri / arteri femoralis kanan/kiri / vena femoralis kanan/kiri / daerah luka operasi PPM)."
                            v-model="input.Observasiperdarahan"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Haemostasis dengan bantal pasir'"
                            label="Haemostasis dengan bantal pasir 0,5-1 kg di atas daerah punksi selama 4 jam setelah pencabutan"
                            v-model="input.Haemostasis"
                        />
                    </VControl>
                  </div>
                  <div class="columns is-12 is-multiline">
                    <div class="column is-6">
                      sheath sampai dengan jam
                    </div>
                    <div class="column is-6" style="margin-top: -10px;">
                      <VDatePicker v-model="input.HaemostasisJam" mode="time" trim-weeks :max-date="new Date()">
                          <template #default="{ inputValue, inputEvents }">
                              <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                              </VControl>
                          </template>
                      </VDatePicker>
                    </div>
                  </div>
                <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Immobilisasi pergelangan tangan kanan'"
                            label="Immobilisasi pergelangan tangan kanan / khaki kanan/kiri selama 6 jam setelah pencabutan sheath"
                            v-model="input.Immobilisasipergelangan"
                        />
                    </VControl>
                  </div>
                  <div class="columns is-12 is-multiline">
                    <div class="column is-4">
                      sampai dengan jam
                    </div>
                    <div class="column is-3" style="margin-top: -10px;">
                      <VDatePicker v-model="input.ImmobilisasiJam" mode="time" trim-weeks :max-date="new Date()">
                          <template #default="{ inputValue, inputEvents }">
                              <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                              </VControl>
                          </template>
                      </VDatePicker>
                    </div>
                    <div class="column is-5">
                      selama terpasang sheath / TPM.
                    </div>
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Radial band boleh dikendorkan jam'"
                            label="Radial band boleh dikendorkan jam"
                            v-model="input.Radialband"
                        />
                    </VControl>
                  </div>
                  <div class="column is-3" style="margin-top: -10px;">
                    <VDatePicker v-model="input.RadialbandJam" mode="time" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-12">
                    atau bila tampak kebiruan pada telapak dan jari-jari tangan daerah punksi.
                  </div>
                  <div class="column is-6">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Radial band boleh dilepas jam'"
                            label="Radial band boleh dilepas jam"
                            v-model="input.RadialbandDilepas"
                        />
                    </VControl>
                  </div>
                  <div class="column is-3" style="margin-top: -10px;">
                    <VDatePicker v-model="input.RadialbandDilepasJam" mode="time" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-12">
                    atau bila sudah tidak terjadi perdarahan di daerah punksi (arteri radialis kanan/kiri).
                  </div>
                  <div class="column is-5">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Periksa lab PT, APTT, jam'"
                            label="Periksa lab PT, APTT, jam"
                            v-model="input.RadialbandDilepas"
                        />
                    </VControl>
                  </div>
                  <div class="column is-3" style="margin-top: -10px;">
                    <VDatePicker v-model="input.RadialbandDilepasJam" mode="time" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-12">
                    dan lapor dokter operator bila sudah ada hasil.
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Immobilisasi penuh ekstremitas atas kanan / kiri selama 1 X 24 jam post tindakan PPM.'"
                            label="Immobilisasi penuh ekstremitas atas kanan / kiri selama 1 X 24 jam post tindakan PPM."
                            v-model="input.Immobilisasipenuh"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Minggu ke-1 s/d ke-4 post PPM, pasien tidak boleh menggerakkan ekstremitas atas kanan/kiri melebihi bahu.'"
                            label="Minggu ke-1 s/d ke-4 post PPM, pasien tidak boleh menggerakkan ekstremitas atas kanan/kiri melebihi bahu."
                            v-model="input.tidakbolehmenggerakkanekstremitas"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Minggu ke-1 s/d ke-12 post PPM, pasien tidak boleh mengangkat beban berat + 5 kg.'"
                            label="Minggu ke-1 s/d ke-12 post PPM, pasien tidak boleh mengangkat beban berat + 5 kg."
                            v-model="input.tidakbolehmengangkatbeban"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Lain-lain'"
                            label="Lain-lain :"
                            v-model="input.PostKateterisasiLain"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                   <VField>
                       <VTextarea rows="3" v-model="input.TAPostKateterisasiLain"></VTextarea>
                   </VField>
                  </div>
                  <div class="column is-12">
                    PROGRAM THERAPI DAN RENCANA TINDAKAN POST KATETERISASI
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Therapi sebelumnya dilanjutkan'"
                            label="Therapi sebelumnya dilanjutkan"
                            v-model="input.Therapisebelumnyadilanjutkan"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="'Lain-lain'"
                            label="Lain-lain :"
                            v-model="input.ProgramTherapiLain"
                        />
                    </VControl>
                  </div>
                  <div class="column is-12">
                   <VField>
                       <VTextarea rows="3" v-model="input.TAProgramTherapiLain"></VTextarea>
                   </VField>
                  </div>
                </div>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Ya'"
                        label="Ya"
                        v-model="input.PerawatanPostKateterisasi"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        :true-value="'Tidak'"
                        label="Tidak"
                        v-model="input.PerawatanPostKateterisasi"
                    />
                </VControl>
              </th>
              <th style="vertical-align: middle;">
                <VField>
                    <VTextarea rows="2" v-model="input.TAPerawatanPostKateterisasi"></VTextarea>
                </VField>
              </th>
            </tr>
            </table>
            <div class="columns" style="padding-top: 40px; padding-bottom: 60px;">
            <div class="column is-4" style="text-align: center;">
                <h1>PETUGAS CATH-LAB YANG MENYERAHKAN</h1>
                <TandaTangan :elemenID="'TTDPetugasChatlabMenyerahkan'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                    <AutoComplete v-model="input.DDPetugasChatlabMenyerahkan" :suggestions="d_Petugas"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketika nama petugas..." />
                </VControl>
            </div>
            <div class="column is-4">
              
            </div>
            <div class="column is-4" style="text-align: center;">
                <h1>PETUGAS RUANGAN YANG MENERIMA</h1>
                <TandaTangan :elemenID="'TTDPetugasRuangMenerima'" :width="'150'" :height="'150'" class="dek" />
                <VControl class="prime-auto">
                    <AutoComplete v-model="input.DDPetugasRuangMenerima" :suggestions="d_Petugas"
                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                        placeholder="Ketika nama petugas..." />
                </VControl>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-1" style="margin-top: 10px;">Tanggal:</div>
            <div class="column is-4">
              <VDatePicker v-model="input.tanggaljamPost" mode="date" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-1" style="margin-top: 10px;">Jam:</div>
            <div class="column is-4">
              <VDatePicker v-model="input.tanggaljamPost" mode="time" trim-weeks :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </template>
              </VDatePicker>
            </div>
          </div>
          </div>
      </div>
    </div>
  </div>

                  
            
              



</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';

useHead({
  title: 'Edukasi Persiapan EGD - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
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

const route = useRoute()
const pasien: any = ref({})
const d_Petugas: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({})
const COLLECTION: any = ref('ChecklistSerahTerimaPasienPrePostKateterisasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  pukulGigiPalsu : new Date(),
  puasamakanJam : new Date(),
  puasaminumJam : new Date(),
  HaemostasisJam : new Date(),
  ImmobilisasiJam : new Date(),
  RadialbandJam : new Date(),
  RadialbandDilepasJam : new Date(),
  details: [{
    NO: 1,
  }],
  details2: [{
    NO: 1,
  }],
})
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Pasien' }, { value: 5, label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }, { value: 3, label: '1-5 kg' }, { value: 4, label: '6-10 kg' }, { value: 5, label: '11-15 kg' }, { value: 6, label: '>15 kg' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const d_skalanyeri: any = ref([{ value: 0, label: '0' }, { value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }, { value: 7, label: '7' }, { value: 8, label: '8' }, { value: 9, label: '9' }, { value: 10, label: '10' }])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const d_Ruangan: any = ref([]);

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  );
  d_Ruangan.value = response;
};

let dropdownAllo: any = ref([
  "Suami/Istri",
  "Orang tua",
  "Anak",
  "Lainnya"
])

const loadRiwayat = async () => {
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
  if (response.length) {
    input.value = response[0] //set ke inputan
    if (NOREC_EMRPASIEN.value == '') {
      NOREC_EMRPASIEN.value = response[0].emrpasienfk
    }
    H.tandaTangan().set("TTDPetugasMenyerahkan", response[0]['TTDPetugasMenyerahkan'])
    H.tandaTangan().set("TTDPetugasChatlab", response[0]['TTDPetugasChatlab'])
    H.tandaTangan().set("TTDPetugasChatlabMenyerahkan", response[0]['TTDPetugasChatlabMenyerahkan'])
    H.tandaTangan().set("TTDPetugasRuangMenerima", response[0]['TTDPetugasRuangMenerima'])
  } else {
    getDataExist()
  }
}
const filterMenu: any = ref('')
const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}
  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDPetugasMenyerahkan'] = H.tandaTangan().get("TTDPetugasMenyerahkan");
  object['TTDPetugasChatlab'] = H.tandaTangan().get("TTDPetugasChatlab");
  object['TTDPetugasChatlabMenyerahkan'] = H.tandaTangan().get("TTDPetugasChatlabMenyerahkan");
  object['TTDPetugasRuangMenerima'] = H.tandaTangan().get("TTDPetugasRuangMenerima");
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
    loadRiwayat()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const getDataExist = () => {
  input.value.tanggal = new Date()
  input.value.kebjamKedatangan = new Date()
  input.value.kebjamAsesmenAwal = new Date()
}

const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const addnewItem = () => {
  input.value.details.push({
    no: input.value.details.length + 1,
  });
};
const removeItem = (index) => {
  input.value.details.splice(index, 1);
};

const addnewItem2 = () => {
  input.value.details2.push({
    no: input.value.details2.length + 1,
  });
};
const removeItem2 = (index) => {
  input.value.details2.splice(index, 1);
};

onBeforeMount(async () => {
  try {
    await loadRiwayat()
    await fetchPasien()
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


// const simpanTemplate = () => {
//   if (!input.value.namatemplate) {
//     H.alert('warning', "Nama Template wajib diisi")
//     return;
//   }
//   let ID = input.id ? input.id : ''

//   let object: any = {}

//   object = input.value
//   object.nocm = pasien.value.nocm

//   object.pasien = H.setObjectPasien(pasien.value)
//   object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
//   let json = {
//     'id': ID,
//     'norec_emr': NOREC_EMRPASIEN.value,
//     'collection': COLLECTION.value,
//     'url_form': props.FORM_URL,
//     'name_form': props.FORM_NAME,
//     'jenis_emr': 'asesmen_medis',
//     'data': object
//   }
//   isLoading.value = true

//   useApi().post(
//     `/emr/simpan-emr-template`, json).then((response: any) => {
//       isLoading.value = false
//       input.value.namatemplate = null
//     }).catch((e: any) => {
//       isLoading.value = false
//     })
// }

// const pilihTemplate = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       if (responselast.length) {
//         listTemplate.value = responselast //set ke inputan
//         showModalTemplate.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }

// const addTemplate = (response: any) => {
//   console.log(response)
//   input.value = response //set ke inputan
//   input.value.namatemplate = null
// }

// const pilihTemplateFix = async (index: any) => {
//   isLoading.value = true
//   useApi().get(
//     `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
//       isLoading.value = false
//       console.log(responselast)
//       if (responselast.length) {
//         for (var x = 0; x < responselast.length; x++) {
//           responselast[x].no = x + 1
//           responselast[x].id = ''
//         }
//         listTemplateFix.value = responselast //set ke inputan
//         showModalTemplateFix.value = true
//       } else {
//         H.alert('warning', 'Data tidak ada')
//       }
//     })
// }
</script>

<style lang="scss">
.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}

.tables tr td {
  border: 1px solid #b3b3b3;
}

.tables tr th {
  border: 1px solid #b3b3b3;
}

.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

.table.is-borderless {
  border: none !important;
  background-color: transparent;
}

.table.is-borderless th,
tr,
td {
  border: none !important;
  background-color: transparent !important;
}

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

.p-fieldset-legend {
  margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
  background: none;
}

.td-po {
  border: 0.5px solid black !important;
  white-space: pre-line;
}

table.assesment {
  border-collapse: collapse;
  width: 100%;
}

.grey-background {
  background-color: #d3d3d3;
  /* Grey color */
}

.assesment th {
  text-align: center !important;
  border-bottom: 1px solid black;
  // border: 1px solid black;
}

.assesment th,
.assesment td {
  padding: 8px;
  vertical-align: middle !important;
}

hr {
  background-color: hsl(0deg 6.81% 88.68%);
  border: none;
  display: block;
  height: 2px;
  margin: 1rem 0;
}

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
.lightgrey{
  background-color: #b3b3b3;
}
</style>