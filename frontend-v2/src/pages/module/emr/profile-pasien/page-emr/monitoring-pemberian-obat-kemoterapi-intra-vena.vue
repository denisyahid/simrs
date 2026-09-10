<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
              <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                :disabled="isDisabled" @click="print">
                Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()">
                Simpan
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpanTemplate()"> Simpan Template
              </VButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="column is-12">
          <div class="columns">
            <div class="column is-12">
              <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                  template</span></h1>
              <VField>
                <VControl>
                  <VInput v-model="input.namatemplate" type="text">
                  </VInput>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
          <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
            @click="pilihTemplateFix(index)"> Pilih Template
          </VButton>
        </div>
        <div class="columns is-multiline">
          <div class="column is-flex is-12">
            <div class="column is-2">
              <h1 style="font-weight: bold">NAMA PASIEN:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput v-model="input.namaPasien" rows="1" disabled />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-flex is-12">
            <div class="column is-2">
              <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="column is-flex is-12">
            <div class="column is-2">
              <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
            </div>
            <div class="column is-10" style="display: flex">
              <VField v-for="items in JenisKelamin" :key="items.value">
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                    :label="items.label" color="primary" circle disabled />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">No. Rekam Medis:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput disabled type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold">TANGGAL DAN JAM:</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VDatePicker v-model="input.tanggalDanJam" mode="dateTime" is24hr trim-weeks :max-date="new Date()">
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
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Ruangan</h1>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <AutoComplete v-model="input.ruanganSelanjutnya" :suggestions="d_Ruangan"
                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="ketik untuk mencari ruangan..." />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Seri</h1>
            </div>
            <div class="is-10 column">
              <VField>
                <VControl>
                  <VInput class="input" placeholder="Seri" v-model="input.Seri" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Hari Ke</h1>
            </div>
            <div class="is-10 column">
              <VField>
                <VControl>
                  <VInput class="input" placeholder="Hari Ke" v-model="input.hariKe" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-12 is-flex">
            <div class="column is-2">
              <h1 style="font-weight: bold;">Regimen :</h1>
            </div>
            <div class="is-10 column">
              <VField>
                <VControl>
                  <VInput class="input" placeholder="Regimen"
                    v-model="input.regimen" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="is-12 column">
            <div style="max-width: 100%">
              <table class="table-pri">
                <thead>
                  <tr style="">
                    <th class="th-pri" style="vertical-align: inherit; text-align: center" rowspan="2">
                      Hasil Observasi
                    </th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center" rowspan="2">Sebelum Kemoterapi</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center" colspan="2">Selama Kemoterapi</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center" colspan="1">Setelah Kemoterapi</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center" rowspan="2">Bila Terjadi Hipersensitifitas</th>
                  </tr>
                  <tr>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">15 Menit</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">30 Menit</th>
                    <th class="th-pri" style="vertical-align: inherit; text-align: center">1 Jam</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="items in Kebutuhan" :key="items">
                    <td class="td-pri">
                      {{ items.label }}
                    </td>
                    <template v-for="input in items.inputan">
                      <td class="td-pri" v-if="items.addons == false">
                        <VField>
                          <VControl>
                            <VInput class="input" :placeholder="input.label" v-model="input[input.model]" />
                          </VControl>
                        </VField>
                      </td>

                      <td v-if="items.label === 'Suhu'" class="td-pri">
                        <VField addons>
                          <VControl>
                            <VInput class="input" :placeholder="input.label" v-model="input[input.model]"/>
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>°C </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td v-else-if="items.label === 'Nadi'" class="td-pri">
                        <VField addons>
                          <VControl>
                            <VInput class="input" :placeholder="input.label" v-model="input[input.model]"/>
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Kali/mnt </VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td v-else-if="items.label === 'Tensi'" class="td-pri">
                        <VField addons>
                          <VControl>
                            <VInput class="input" :placeholder="input.label" v-model="input[input.model]"/>
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>mmHg</VButton>
                          </VControl>
                        </VField>
                      </td>

                      <td v-else-if="items.label === 'Respirasi'" class="td-pri">
                        <VField addons>
                          <VControl>
                            <VInput class="input" :placeholder="input.label" v-model="input[input.model]"/>
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>kali/mnt</VButton>
                          </VControl>
                        </VField>
                      </td>

                    </template>
                  </tr>
                  <tr>
                    <td class="td-pri">
                      Pemakaian PORT
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.pemakaianPort" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.pemakaianPort" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                  </tr>

                  <tr>
                    <td class="td-pri" colspan="6">
                      Kondisi Port
                    </td>
                  </tr>
                  <tr>
                    <td class="td-pri">
                      - Teraba
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPortTeraba" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPortTeraba" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                  </tr>
                  <tr>
                    <td class="td-pri">
                      - Tidak Teraba
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPortTidakTeraba" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPortTidakTeraba" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                    <td class="td-pri"></td>
                  </tr>

                  <tr>
                    <td class="td-pri" colspan="6">
                      Kondisi kulit dan jaringan sekitar area pemasangan infus/PORT/CVC/PICC
                    </td>
                  </tr>
                  <tr>
                    <td class="td-pri">
                      - Bengkak
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBengkakSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td-pri">
                      - Kemerahan
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.KemerahanSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      - Sakit
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.SakitSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri" colspan="6">
                      Kondisi jarum konektor pada PORT
                    </td>
                  </tr>
                  <tr>
                    <td class="td-pri">
                      - Fiksasi Baik
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiBaikSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>
                  <tr>
                    <td class="td-pri">
                      - Fiksasi Longgar / Lepas
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiFiksasiLonggarSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      Infus menetes lancar
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiInfusSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      Blood Return
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBloodReturnSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      Mual
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMualSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      Muntah
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiMuntahSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri" colspan="6">
                      Reaksi hipersensitifitas
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      - Kemerahan dan terasa panas di wajah
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiKemerahanSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      - Gatal
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiGatalSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      - Sesak Nafas
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiSesakNafasSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      - Pusing
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiPusingSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">
                      - Berkeringat
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSebelumKemo" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSebelumKemo" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>

                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSelamaKemo15menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSelamaKemo15menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSelamaKemo30menit" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSelamaKemo30menit" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSelamaKemo1jam" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSelamaKemo1jam" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-pri">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSelamaKemoHipersensitifitas" color="primary" true-value="ya" label="ya" />
                        </VControl>
                      </VField>

                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.kondisiBerkeringatSelamaKemoHipersensitifitas" color="primary" true-value="tidak" label="tidak" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">Catatan</td>
                    <td v-for="(item, index) in 5" :key="index" class="td-pri">
                      <VField>
                        <VControl>
                          <VTextarea v-model="input[`catatan-${item}`]" class="textarea" />
                        </VControl>
                      </VField>
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">Nama</td>
                    <td v-for="(item, index) in 5" :key="index" class="td-pri">
                        <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                          <VControl icon="feather:search">
                            <AutoComplete v-model="input[`Nama-${item}`]" :suggestions="d_Pegawai"
                              @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                          </VControl>
                        </VField>
                      <!-- <VField>
                        <VControl>
                          <VInput v-model="input[`Nama-${item}`]" class="input" />
                        </VControl>
                      </VField> -->
                    </td>
                  </tr>

                  <tr>
                    <td class="td-pri">Tanda Tangan</td>
                    <td v-for="(item, index) in 5" :key="index" class="td-pri">
                      <TandaTangan :elemenID="`TTDperawat-${item}`" :width="'150'" :height="'150'" />
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>

        </div>
      </VCard>
    </div>
  </div>

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
                  v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
                <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
                  color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
                </VIconButton>
                <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                  color="success" v-tooltip-prime.top="'Pilih'">
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
import { h, reactive, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-dan-observasi-pasien-hemodialisa'
import * as EMR2 from '../page-emr-plugins/monitoring-pemberian-obat-kemoterapi-intra-vena'
import Fieldset from 'primevue/fieldset'
import Calendar from 'primevue/calendar'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let JenisKelamin: any = ref(EMR.JenisKelamin())
let Kebutuhan: any = ref(EMR2.Kebutuhan())

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

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const d_Obat: any = ref([])
const d_dokter: any = ref([])
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const dataTTD: any = ref([])
const showModalTemplateFix: any = ref(false)
const listTemplateFix: any = ref([])
const isAlltemplate: any = ref(false)
const idTemplate: any = ref('');
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const COLLECTION: any = ref('MonitoringPemberianObatKemoterapiIntraVena')
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
  tanggalDanJam: new Date(),
  TTDPerawat: []
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    )
    .then((response: any) => {
      if (response.length > 0) {
        isDisabled.value = false
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        input.value.namaPasien = props.pasien.namapasien
        input.value.jeniskelamin = props.pasien.jeniskelamin
        input.value.norm = props.pasien.nocm
        input.value.tanggalLahirPasien = props.pasien.tgllahir
        for (let i = 1; i <= 5; i++) {
          const ttdPerawat1 = `TTDperawat-${i}`
          H.tandaTangan().set(ttdPerawat1, dataTTD.value[ttdPerawat1])
        }
      } else {
        isDisabled.value = true
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  for (let i = 1; i <= 5; i++) {
    const ttdPerawat1 = `TTDperawat-${i}`
    object[ttdPerawat1] = H.tandaTangan().get(ttdPerawat1)

    console.log(object[ttdPerawat1])
  }
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const print = async () => {
  H.printBlade(
    `emr/cetak-formulir-asuhan-keperawatan-dan-observasi-pasien-hemodialisa?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  )
}

const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.tglPembuatan = new Date()
  let ru = await fetchRuangan({query: props.registrasi?.namaruangan});
  if(d_Ruangan.value.length > 0) {
    input.value.ruanganSelanjutnya = d_Ruangan.value[0];
  }

  console.log('input value', input.value.ruanganSelanjutnya);
}

const fetchPerawat = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Perawat.value = response
    })
}

const fetchObat = async (filter: any) => {
  const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  response.map((element: any) => {
    element.label = element.productname,
      element.value = element.id
  })
  d_Obat.value = response
}


const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const jumlahCairanMasuk = computed(() => {
  return (
    Number(input.value.sisaPriming || 0) +
    Number(input.value.TransfusiAtauObat || 0) +
    Number(input.value.washOut || 0) +
    Number(input.value.minum || 0)
  )
})

const jumlahCairanKeluar = computed(() => {
  return (
    Number(input.value.ultrafiltrasi || 0) +
    Number(input.value.kencing || 0) +
    Number(input.value.muntah || 0) +
    Number(input.value.drain || 0)
  )
})

const jumlahlBalance = computed(() => {
  return Number(jumlahCairanMasuk.value) - Number(jumlahCairanKeluar.value)
})

const d_Pegawai: any = ref([])

const fetchPegawai = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const d_Ruangan: any = ref([])

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  );
  d_Ruangan.value = response;
};

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
  }).catch((e: any) => {
    isLoading.value = false
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
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  input.value = dt //set ke inputan
  isAlltemplate.value = false;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
}

const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate', 'namaPasien', 'norm', 'tanggalLahirPasien', 'jeniskelamin']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=true`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
        // responselast[x].id = ''
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

watch(isAlltemplate, (newValue) => {
  pilihTemplateFix()
})


setView()
loadRiwayat()
setAutoFill()
fetchPerawat({ query: '' })
fetchObat({ query: '' })
</script>
