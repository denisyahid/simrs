    <template>
      <div class="columns is-multiline">
        <div class="column " :class="[props.show_resep ? 'is-9' : 'is-12']">
          <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
            <h3 class="title is-5 head-sep mt-5 pt-4 ml-1">
              <span> CPPT</span>
            </h3>
            <div class="columns is-multiline">
              <div class="column is-12">
                <table class="tg">
                  <thead class="tg">
                    <tr>
                      <th class="tg-0lax text-center font-bold">Tanggal/Jam</th>
                      <th class="tg-0lax text-center">Catatan Perkembangan Pasien Terintegrasi</th>
                      <!-- <th class="tg-0lax text-center">Intruksi PPA</th> -->
                      <th class="tg-0lax text-center">Verifikasi DPJP</th>

                    </tr>
                  </thead>
                  <tbody v-for="(item, index2) in props.cppt" :key="index2">
                    <tr v-if="item.flag != 'gizi'"
                      :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color);' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color);' : '')]">

                      <td style="width:15%">
                        <span class="mb-2">{{ H.formatDate(item.tgl, 'YYYY-MM-DD HH:mm') }}</span><br>
                        <span style="font-weight: bold;">{{ item.tenagaMedis ? item.tenagaMedis.label : '-' }}</span>

                      </td>
                      <td>
                        <table class="tg">
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">S
                            </td>
                            <td>
                              {{ item.S ?? '' }}
                            </td>

                          </tr>
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">O
                            </td>
                            <td>
                              {{ item.O ?? '' }}
                            </td>

                          </tr>
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">A
                            </td>
                            <td v-if="item.flag == 'profesi lain'">
                              {{ item.A ?? '' }}
                            </td>
                            <td v-if="item.flag == 'dokter'">
                              {{ item.A ?? '' }}
                              <!-- <div class="columns is-multiline">
                                <div class="column is-12">
                                  <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 10</span>
                                  <div style="overflow-y:auto;" class="mt-1">
                                    <table class="tg" style="width:100% !important">
                                      <thead>
                                        <tr>

                                          <th class="td-fkprj" width="23%"
                                            style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                            Jenis
                                          </th>
                                          <th class="td-fkprj" width="25%"
                                            style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                            Diagnosa
                                            Dokter
                                          </th>
                                          <th class="td-fkprj"
                                            style="vertical-align:inherit;text-align: center;font-size:9pt;"> ICD
                                            10
                                          </th>
                                        </tr>
                                      </thead>
                                      <tbody v-for="(itemsss, index3) in item.diagnosaDokter" :key="index3">
                                        <tr >
                                          <td class="tg-0lax">
                                            <div class="column p-1">
                                              {{ itemsss.jenisDiagnosa ? itemsss.jenisDiagnosa.label : '' }}
                                            </div>
                                          </td>
                                          <td class="tg-0lax">
                                            <div class="column pt-3 pb-0">
                                              {{ itemsss.keterangan ?? '' }}

                                            </div>
                                          </td>
                                          <td class="tg-0lax">
                                            <div class="column p-1">
                                              {{ itemsss.diagnosaa ? itemsss.diagnosaa.label : '' }}

                                            </div>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <div class="column is-12">
                                  <span style="font-size:9pt;font-weight:bold">Diagnosis ICD 9</span>
                                  <div style="overflow-y:auto;" class="mt-1">
                                    <table class="tg" width="100%">
                                      <thead>
                                        <tr>


                                          <th class="td-fkprj" width="40%"
                                            style="vertical-align:inherit;text-align: center;font-size:9pt;">
                                            Keterangan
                                          </th>
                                          <th class="td-fkprj"
                                            style="vertical-align:inherit;text-align: center;font-size:9pt;"> ICD
                                            9
                                          </th>
                                        </tr>
                                      </thead>
                                      <tbody v-for="(itemsss, index3) in item.diagnosaDokter9" :key="index3">
                                        <tr>

                                          <td class="tg-0lax">
                                            <div class="column pt-3 pb-0">
                                              {{ itemsss.keterangan ?? '' }}

                                            </div>
                                          </td>
                                          <td class="tg-0lax">
                                            <div class="column p-1">
                                              {{ itemsss.diagnosaa ? itemsss.diagnosaa.label : '' }}

                                            </div>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div> -->
                            </td>
                            <td v-if="item.flag == 'perawat'">
                              {{ item.A ?? '' }}
                              <!-- <div class="column">
                                <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                                <div class="mt-1">
                                  {{ item2.diagnosaKeperawatan ? item2.diagnosaKeperawatan.label : '' }}
                                  <table class="tg">
                                    <thead>
                                      <tr>
                                        <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
                                          Diagnosa
                                          Keperawatan
                                        </th>
                                      </tr>
                                    </thead>
                                    <tbody v-for="(item2, index2) in item.diagnosaKep" :key="index2">
                                      <tr>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            {{ item.A ?? '' }}
                                            {{ item2.diagnosaKeperawatan ? item2.diagnosaKeperawatan.label : '' }}
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div> -->
                            </td>

                          </tr>
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">P
                            </td>
                            <td>
                              {{ item.P ?? '' }}

                              <!-- <div class="column" v-if="item.flag == 'perawat'">
                                <span style="font-size:11pt;font-weight:bold">Tujuan Kriteria (SLKI) </span>
                                <div class="mt-1">
                                  <table class="tg">
                                    <thead>
                                      <tr>

                                        <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
                                          Tujuan Keperawatan & Intervensi
                                        </th>

                                      </tr>
                                    </thead>
                                    <tbody v-for="(item2, index2) in item.tujuanKep" :key="index2">
                                      <tr>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            {{ item2.tujuanKeperawatan ? item2.tujuanKeperawatan.label : '-' }}
                                          </div>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="tg-0lax">
                                          <div class="column p-1">
                                            {{ item2.intervensiKeperawatan ? item2.intervensiKeperawatan.label : '-' }}
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div> -->
                            </td>

                          </tr>
                          <tr>
                            <td width="5%" style="width: 5%;vertical-align: middle;font-size:12px">Intruksi
                            </td>
                            <td>
                              <span style="font-weight: bold; font-size: 11px" v-if="item.intruksi == 'Intruksi DPJP'">
                                {{ item.dpjpUtama?.label ?? '' }}
                              </span>
                              <br>
                              {{ item.intruksiPPA ?? '' }}
                              <template v-if="item.dpjpRawatBersama.length > 0 && item.dpjpRawatBersama[0].id != null">
                                <hr>
                                <span>Rawat Bersama</span>
                                <div v-for="raber in item.dpjpRawatBersama">
                                  <span style="font-weight: bold; font-size: 11px">
                                    {{ raber.id?.label ?? '' }}
                                  </span>
                                  <br>
                                  {{ raber.intruksi ?? '' }}
                                  <br>
                                </div>
                              </template>
                            </td>

                          </tr>
                        </table>

                      </td>
                      <!-- <td style="width:15%">
                        {{ item.intruksiPPA ?? '' }}

                      </td> -->

                      <td style="width:15%" class="text-center">

                        <template v-if="item.dokterDPJP && item.isverif">
                          <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br>
                        </template>
                        <template v-else>
                          <span>-</span>
                        </template>
                        <span> {{ item.keteranganVerifikasiDPJP ?? '' }}</span> <br>
                        <span> {{ item.tglVerifikasi ? H.formatDate(item.tglVerifikasi, 'YYYY-MM-DD HH:mm') : '' }}</span>
                        <br>
                        <span style="font-weight: bold;"> {{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span> <br>
                        <VTag color="success" v-if="item.isverif">
                          Intruksi Sudah Diverifikasi
                        </VTag>
                        <VTag color="warning" v-if="!item.isverif">
                          Intruksi Belum Diverifikasi
                        </VTag>
                        <!-- <span> Sudah Diverifikasi</span> <br> -->

                      </td>

                    </tr>
                    <tr v-if="item.flag == 'gizi'"
                      :style="[item.flag == 'dokter' ? 'background-color: var(--danger--light-color);' : (item.flag == 'perawat' ? 'background-color: var(--info--light-color);' : '')]">

                      <td style="width:15%">
                        <span class="mb-2">{{ H.formatDate(item.tgl, 'YYYY-MM-DD HH:mm') }}</span><br>
                        <span>{{ item.tenagaMedis ? item.tenagaMedis.label : '-' }}</span>

                      </td>
                      <td>
                        <table class="tg">
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">A
                            </td>
                            <td>
                              {{ item.AGizi ?? '' }}
                            </td>

                          </tr>
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">D
                            </td>
                            <td>
                              {{ item.DGizi ?? '' }}
                            </td>

                          </tr>
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">I
                            </td>
                            <td>
                              {{ item.IGizi ?? '' }}
                            </td>

                          </tr>
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">M
                            </td>
                            <td>
                              {{ item.MGizi ?? '' }}
                            </td>

                          </tr>
                          <tr>
                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;font-size:18px">E
                            </td>
                            <td>
                              {{ item.EGizi ?? '' }}
                            </td>

                          </tr>
                        </table>

                      </td>
                      <td style="width:15%">
                        {{ item.intruksiPPA ?? '' }}

                      </td>

                      <td style="width:15%" class="text-center">

                        <img v-if="item.dokterDPJP"
                          :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item.dokterDPJP ? item.dokterDPJP.label : '-')"><br>

                        <span> {{ item.keteranganVerifikasiDPJP ?? '' }}</span> <br>
                        <span> {{ item.tglVerifikasi ? H.formatDate(item.tglVerifikasi, 'YYYY-MM-DD HH:mm') : '' }}</span>
                        <br>
                        <span> {{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span> <br>

                      </td>

                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-3" v-if="props.show_resep">
          <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--danger);">
            <h3 class="title is-5 head-sep mt-3-min">
              <span> Resep</span>
            </h3>
            <div class="columns is-multiline" v-if="riwayatResep.length" style="height:800px;overflow:auto">

              <div class="column is-12 mb-0 mt-3-min" v-for="itemZ  in riwayatResep">
                <TWidgetListResep :title="itemZ.namaproduk" :subtitle="itemZ.jumlah" :subtitle1="itemZ.aturanpakai"
                  :subtitle2="H.formatDateIndoSimpleNoDay(itemZ.tglorder)"
                  :subtitle3="itemZ.simslama == true ? 'SIMRS LAMA' : ''" :color_sub_3="'danger'"
                  class="inbox-widget-3 success mb-0" />
              </div>
            </div>
            <div class="columns is-multiline" v-else>
              <div class="column is-12">
                <div class="flex-list-inner  text-center">
                  <VPlaceholderSection :title="H.assets().notFound" class="my-6">
                    <template #image>
                      <img class="light-image" :src="'/@src/assets/illustrations/placeholders/search-4-dark.svg'" alt=""
                        style="width: 100px;" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                        style="width: 100px;" />
                    </template>
                  </VPlaceholderSection>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <script setup lang="ts">
    import * as H from '/@src/utils/appHelper'
    import { useApi } from '/@src/composable/useApi'
    import { h, reactive, ref, computed, defineComponent, watch, PropType } from 'vue'
    import TWidgetListResep from '../t-widget-list-resep.vue'
    const props = defineProps({
      cppt: {
        type: Array as PropType<any>,
      },
      show_resep: {
        type: Boolean as PropType<any>,
      },
      norec_pd: {
        type: String as PropType<any>,
      },
    })
    const riwayatResep: any = ref([])
    if (props.show_resep) {
      riwayatResep.value = []
      useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${props.norec_pd}`).then((responseXX: any) => {
        for (let x = 0; x < responseXX.length; x++) {
          const element = responseXX[x];
          for (let y = 0; y < element.details.length; y++) {
            const element2 = element.details[y];
            riwayatResep.value.push({
              'namaproduk': element2.namaproduk,
              'jumlah': element2.jumlah,
              'aturanpakai': element2.aturanpakai,
              'tglorder': element.tglorder,
              'simslama': false,
            })
          }
        }
      })
    }
    // props.cppt = props.cppt[0][props.cppt.length]
    </script>
