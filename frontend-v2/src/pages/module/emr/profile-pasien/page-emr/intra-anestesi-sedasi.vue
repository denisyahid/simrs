<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="column">
    <VCard>
      <h1 style="font-weight: bold;">Intra Anestesi / Sedasi</h1>
      <div class="column" style="overflow: auto;">
        <table class="tg">
          <thead>
            <tr>
              <th width="10%" class="col-stuck">Waktu</th>


              <th v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['waktu_' + index]" type="time" placeholder="Pick an hour" />
                  </VControl>
                </VField>
                <!-- <Calendar id="calendar-timeonly" v-model="input['waktu_' + index]" timeOnly :date-format="'HH:mm'" /> -->
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="col-stuck bg-colatas">
                <span>O2 L/min</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['O2_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>N2O L/min</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['N2O_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Air L/min</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['air_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Isoflurane %</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['isoflurane_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Sevoflurane %</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['sevoflurane_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Fentanyl mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['fentanyl_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Propofol mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['propofol_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Midazolam mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['midazolam_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Rocuronium mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['rocuronium_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Atracurium mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['atracurium_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Ketamine mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ketamine_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Lidocaine mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['lidocaine_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Meperidine mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['meperidine_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Dexamethasone mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['dexamethasone_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Diphenhydramine mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['diphenhydramine _' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Ondansentron mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ondansentron_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Ondansentron mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ondansentron_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Granisetron mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['granisetron_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Ranitidine mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ranitidine_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Pantoprazole mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['pantoprazole_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Paracetamol mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['paracetamol_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Ketorolac mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ketorolac_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Tramadol mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['tramadol_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Dexketoprofen mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['dexketoprofen_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Metamizole mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['metamizole_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Oxytocin mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['oxytocin_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Methylergomertine mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['methylergomertine_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Ephedrine mcg</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ephedrine_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.ketIntraAnes_1" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['waktuKetAnes1_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.ketIntraAnes_2" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['waktuKetAnes2_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>REGIONAL</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['regional_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>ANESTESI</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['anestesi_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.ketIntraAnes_3" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['waktuKetAnes3_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.ketIntraAnes_4" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['waktuKetAnes4_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="column mt-5" style="overflow: auto;">
        <table class="tg">
          <thead>
            <tr>
              <th width="10%" class="col-stuck">Waktu</th>
              <th v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['time_' + index]" type="time" placeholder="Pick an hour" />
                  </VControl>
                </VField>
                <!-- <Calendar id="calendar-timeonly" v-model="input['waktu_' + index]" timeOnly :date-format="'HH:mm'" /> -->
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="col-stuck bg-colatas">
                <span>Respirasi</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['respirasi_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Saturasi</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['saturasi_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>EtCO2</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['EtCO2_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Temperatur</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['temperatur_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>CVP</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['CVP_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>INPUT</span>
              </td>
              <td :colspan="jumlahIndex"></td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.input_1" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ketInputI_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.input_2" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ketInput2_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.input_3" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ketInput3_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.input_4" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ketInput4_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <VField>
                  <VControl>
                    <VInput v-model="input.input_5" type="text" />
                  </VControl>
                </VField>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['ketInput5_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>OUTPUT</span>
              </td>
              <td :colspan="jumlahIndex"></td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Pendarahan</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['pendarahan_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>Urine</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['urine_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
            <tr>
              <td class="col-stuck">
                <span>KODE / POSISI</span>
              </td>
              <td v-for="index in jumlahIndex">
                <VField>
                  <VControl>
                    <VInput v-model="input['kodePosisi_' + index]" type="text" />
                  </VControl>
                </VField>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </VCard>
    <!-- <Fieldset :toggleable="true" legend="Tanda Vital">
      <div class="column">
      </div>
    </Fieldset> -->
  </div>

  <div class="column">
    <VCard>
      <h1 style="font-weight: bold;">Tanda Vital</h1>
      <div class="column">
        <div class="column is-12">
          <VCard style="border-radius: 16px;">
            <highcharts :options="chartOptions2"></highcharts>
            <!-- <Chart type="line" :data="chartData" :options="chartOptions" :height="100" class="h-30rem" /> -->
          </VCard>
        </div>
      </div>
      <div class="column">
        <span>Tanda Vital</span>
        <div class="column p-3" style="overflow: auto;">
          <table class="tg">
            <thead>
              <tr>
                <th width="10%" class="col-stuck">Waktu</th>
                <th v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['waktuPemerks_' + index]" type="time" placeholder="Pick an hour" />
                    </VControl>
                  </VField>

                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="col-stuck bg-colatas">
                  <span>Suhu</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['suhu_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Pernapasan</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['pernapasan_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Nadi</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['nadi_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Tekanan Darah</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['tekananDarah_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>SaturasiO2</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['saturasiO2_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="column">
        <span>Durante Anestesi Pemantauan</span>
        <div class="column p-3" style="overflow: auto;">
          <table class="tg">
            <thead>
              <tr>
                <th width="10%" class="col-stuck">Waktu</th>
                <th v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['waktuPemantauan_' + index]" type="time" placeholder="Pick an hour" />
                    </VControl>
                  </VField>

                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="col-stuck bg-colatas">
                  <span>Cairan Infus (ml)</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['cairanInfus' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Darah masuk (ml)</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['darahMasuk_' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Urin (ml)</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['pemUrin' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
              <tr>
                <td class="col-stuck">
                  <span>Pendarahan (ml)</span>
                </td>
                <td v-for="index in jumlahIndex">
                  <VField>
                    <VControl>
                      <VInput v-model="input['permPendarahan' + index]" type="text" />
                    </VControl>
                  </VField>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <div class="column pb-0">
        <span>Teknik Anestesia</span>
        <div class="columns is-multiline p-3">
          <div class="column" v-for="(data) in teknikAnestesi">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary" square
                  class="p-0" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <hr class="m-4" style="background-color:  var(--fade-grey-dark-2) ;">
      <div class="column">
        <span>A. Tata Laksana Jalan Nafas</span>
        <div class="column" v-for="(datas) in tataLaksana">
          <span>{{ datas.title }}</span>
          <div class="columns is-multiline p-3">
            <div class="column pb-0" v-for="(data) in datas.value" :class="data.type == 'checkBox' ? 'is-1' : 'is-6'">
              <span>{{ data.subTitle }}</span>
              <VField v-if="data.type == 'textBox'">
                <VControl>
                  <VInput v-model="input[data.model]" type="text" />
                </VControl>
              </VField>
              <VField v-if="data.type == 'checkBox'">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary"
                    square class="p-0" />
                </VControl>
              </VField>
            </div>

          </div>
        </div>
      </div>

      <div class="column">
        <span>B. Teknik Regional / Blok perifer</span>
        <div class="column" v-for="(datas) in teknikRegional">
          <span>{{ datas.title }}</span>
          <div class="columns is-multiline" :class="datas.detail ? 'pl-3 pr-3 pb-1' : 'pt-2'">
            <div class="column is-2 mt-3" v-for="(pilihan) in datas.detail">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" :label="pilihan.label"
                    color="primary" square class="p-0" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline pt-3 pb-3 pl-3 pr-3">
            <div class="column is-5 pb-0 pt-0" v-for="(data) in datas.optional">
              <span v-if="data.label">{{ data.label }}</span>
              <VField class="pt-3">
                <VControl>
                  <VInput v-model="input[data.model]" type="text" :placeholder="data.placeholder" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <hr class="m-4" style="background-color:  var(--fade-grey-dark-2) ;">
      <div class="column" v-for="(datas) in ventilator">
        <span>{{ datas.title }}</span>
        <div class="columns is-multiline p-3">
          <div class="column is-3 pb-0" v-for="(data) in datas.value">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary" square
                  class="p-0" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="columns is-multiline p-3">
        <div class="column" v-for="(data) in more" :class="data.label == 'Pinsp' ? 'is-5' : 'is-3'">
          <span>{{ data.label }}</span>
          <VField class="pt-3">
            <VControl>
              <VInput v-model="input[data.model]" type="text" />
            </VControl>
          </VField>
        </div>
      </div>

      <hr class="m-4" style="background-color:  var(--fade-grey-dark-2) ;">
      <div class="columns is-multiline p-3">
        <div class="column is-3">
          <span>Lama Pembiusan</span>
          <VField class="column is-5 p-0 mt-3">
            <VControl>
              <VInput v-model="input.lamaPembiusan" type="time" placeholder="Pick an hour" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <span>Lama Pembedahan</span>
          <VField class="column is-5 p-0 mt-3">
            <VControl>
              <VInput v-model="input.lamaPembedahan" type="time" placeholder="Pick an hour" />
            </VControl>
          </VField>
        </div>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Calendar from 'primevue/calendar';
import Chart from 'primevue/chart';
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/intra-anestesi-sedasi'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let teknikAnestesi = ref(EMR.teknikAnestesia())
let tataLaksana = ref(EMR.tataLaksana())
let teknikRegional = ref(EMR.teknikRegional())
let ventilator = ref(EMR.ventilator())
let more = ref(EMR.more())

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
let chartOptions2 = reactive({
  chart: {
    type: 'spline',
  },
  title: {
    text: ''
  },
  credits: {
    enabled: false
  },
  xAxis: {
    categories: []
  },
  yAxis: {
    title: {
      text: 'Jumlah'
    }
  },
  legend: { enabled: true },
  plotOptions: {
    line: {
      dataLabels: {
        enabled: true
      },
      enableMouseTracking: false
    },
    spline: {
      dataLabels: {
        enabled: true,
        // style: {
        //   fontSize: '20px'
        // },
      },
      enableMouseTracking: false
    }
  },
  series: []
});
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const jumlahIndex = ref(15)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const chartData = ref();
const chartOptions = ref();
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan

        // chartData.value = setChartData(response[0]);
        chartHigh(response[0])
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
const chartHigh = (e: any) => {

  let labels = []
  let seriesNadi = []
  let seriesSuhu = []
  let seriesPernafasan = []
  let seriesSaturasi:any = []
  for (let x = 0; x < jumlahIndex.value; x++) {
    if (e['suhu_' + x.toString()] != undefined) {
      seriesSuhu.push(parseFloat(e['suhu_' + x.toString()]))
    }
    if (e['nadi_' + x.toString()] != undefined) {
      seriesNadi.push(parseFloat(e['nadi_' + x.toString()]))
    }
    if (e['pernapasan_' + x.toString()] != undefined) {
      seriesPernafasan.push(parseFloat(e['pernapasan_' + x.toString()]))
    }
    if (e['saturasiO2_' + x.toString()] != undefined) {
      seriesSaturasi.push(parseFloat(e['saturasiO2_' + x.toString()]))
    }
    if (e['waktuPemerks_' + x.toString()] != undefined) {
      labels.push(e['waktuPemerks_' + x.toString()])
    }
    // if (e['waktuPemerks_' + x.toString()] != undefined) {
    //   labels.push(e['waktuPemerks_' + x.toString()])
    // }
    // if (e['waktuPemerks_' + x.toString()] != undefined) {
    //   labels.push(e['waktuPemerks_' + x.toString()])
    // }
  }
  chartOptions2.xAxis.categories = labels
  chartOptions2.series =
    [{
      name: 'Suhu',
      color: 'red',
      lineWidth: 4,
      marker: {
        radius: 4
      },
      data: seriesSuhu
    }, {
      name: 'Nadi',
      color: 'blue',
      data: seriesNadi
    }, {
      name: 'Pernafasan',
      data: seriesPernafasan
    },
    {
      name: 'SaturasiO2',
      data: seriesSaturasi
    }],
    console.log(chartOptions2)

}

// const setChartData = (e: any) => {

//   let labels = []
//   let seriesNadi:any = []
//   let seriesSuhu = []
//   let seriesPernafasan:any = []
//   for (let x = 0; x < jumlahIndex.value; x++) {
//     if (e['suhu_' + x.toString()] != undefined) {
//       seriesSuhu.push(parseFloat(e['suhu_' + x.toString()]))
//     }
//     if (e['waktuPemerks_' + x.toString()] != undefined) {
//       labels.push(e['waktuPemerks_' + x.toString()])
//     }
//   }

//   const documentStyle = getComputedStyle(document.documentElement);

//   // for (var i = data.length - 1; i >= 0; i--) {
//   //   const element = data[i]
//   //   labels.push(H.formatDate(element.tanggal, 'lll'))
//   //   seriesNadi.push((element.nadi ? parseFloat(element.nadi) : 0))
//   //   seriesSuhu.push((element.suhu ? parseFloat(element.suhu) : 0))
//   //   seriesPernafasan.push((element.pernapasan ? parseFloat(element.pernapasan) : 0))
//   //   seriesPernafasan.push((element.saturasi02 ? parseFloat(element.saturasi02) : 0))
//   // }
//   return {
//     labels: labels,
//     datasets: [
//       {
//         label: 'Nadi',
//         data: seriesNadi,
//         fill: false,
//         tension: 0.4,
//         borderColor: documentStyle.getPropertyValue('--blue-500')
//       },
//       {
//         label: 'Suhu',
//         data: seriesSuhu,
//         fill: false,
//         borderDash: [5, 5],
//         tension: 0.4,
//         borderColor: documentStyle.getPropertyValue('--teal-500')
//       },
//       {
//         label: 'Pernafasan',
//         data: seriesPernafasan,
//         fill: true,
//         borderColor: documentStyle.getPropertyValue('--orange-500'),
//         tension: 0.4,
//         backgroundColor: 'rgba(255,167,38,0.2)'
//       },
//       {
//         label: 'Saturasi O2',
//         data: seriesPernafasan,
//         fill: true,
//         borderColor: documentStyle.getPropertyValue('rgb(65, 171, 242)'),
//         tension: 0.4,
//         backgroundColor: 'rgba(255,167,38,0.2)'
//       }
//     ]
//   };
// };

// const setChartOptions = () => {
//   const documentStyle = getComputedStyle(document.documentElement);
//   const textColor = documentStyle.getPropertyValue('--text-color');
//   const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
//   const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

//   return {
//     maintainAspectRatio: false,
//     aspectRatio: 0.6,
//     plugins: {
//       legend: {
//         labels: {
//           color: textColor
//         }
//       }
//     },
//     scales: {
//       x: {
//         ticks: {
//           color: textColorSecondary
//         },
//         grid: {
//           color: surfaceBorder
//         }
//       },
//       y: {
//         ticks: {
//           color: textColorSecondary
//         },
//         grid: {
//           color: surfaceBorder
//         }
//       }
//     }
//   };
// }

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
  // width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;

  // font-size: 14px;
  overflow: hidden;
  padding: 7px;
  word-break: normal;
}

.tg tr {
  height: 20px;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  vertical-align: middle;
  // font-size: 14px;
  text-align: center !important;
  font-weight: bold;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.col-stuck {
  width: 150px;
  position: sticky;
  left: 0;
  z-index: 2;
  background-color: aliceblue;
  vertical-align: inherit;
}

.custom-fieldset {
  border: 1px solid;
  border-radius: 5px;
  border-color: var(--fade-grey-dark-2);
}

.custom-legenda {
  margin-left: 15px;
  font-weight: 500;
}
</style>
