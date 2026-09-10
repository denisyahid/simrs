<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <table class="tg">
        <thead>
        </thead>
        <tbody v-for="(item, index) in props.cppt" :key="index">
          <tr>
            <td class="tg-0lax" style="vertical-align: inherit; size:10%;">
              <span style="font-weight: bold;">Tanggal / Jam</span>
            </td>
            <td>
              <span style="font-weight: bold;">{{ H.formatDate(item.tgl, 'YYYY-MM-DD HH:mm') }}</span>

            </td>

          </tr>
          <tr>
            <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
              <span style="font-size:20pt;font-weight:bold">S</span>
            </td>
            <td class="tg-0lax">
              <span style="font-weight: bold;">{{ item.hasilSub }}</span>

            </td>
          </tr>
          <tr>
            <td class="tg-0lax" width="10%" style="text-align:center">
              <span style="font-size:20pt;font-weight:bold">O</span>
            </td>
            <td class="tg-0lax">
              <span style="font-weight: bold;">{{ item.hasilObj }}</span>
            </td>
          </tr>
          <tr>
            <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
              <span style="font-size:20pt;font-weight:bold">A</span>
            </td>
            <td class="tg-0lax">

              <div class="column">
                <span style="font-weight: bold;">{{ item.hasilA }}</span>

              </div>

              <div class="column">
                <span style="font-size:11pt;font-weight:bold">Diagnosis Keperawatan</span>
                <div class="mt-1">
                  <table width="100%">
                    <thead>
                      <tr>
                        <th class="td-fkprj" width="2%" style="vertical-align: inherit;text-align: center">NO</th>
                        <th class="td-fkprj" width="50%" style="vertical-align:inherit;text-align: center;">
                          Diagnosa
                          Keperawatan
                        </th>
                        <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;" width="7%">#
                        </th>
                      </tr>
                    </thead>
                    <tbody v-for="(item2, index2) in item.details" :key="index2">
                      <tr>
                        <td class="tg-0lax" style="vertical-align:inherit;text-align:center">{{ item2.no }}</td>
                        <td class="tg-0lax">
                          <div class="column p-1">
                            <span style="font-weight: bold;">{{ item2.diagnosaKeperawatan ?
                              item2.diagnosaKeperawatan.caption : '' }}</span>

                          </div>
                        </td>
                        <td class="tg-0lax" style="vertical-align: inherit;">

                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="column">
                <span style="font-size:11pt;font-weight:bold">Diagnosis Dokter</span>
                <div style="overflow-y:auto;" class="mt-1">
                  <table width="100%">
                    <thead>
                      <tr>
                        <th class="td-fkprj" width="2%" style="vertical-align: inherit;">NO</th>
                        <th class="td-fkprj" width="23%" style="vertical-align:inherit;text-align: center;">Jenis
                          Diagnosa
                        </th>
                        <th class="td-fkprj" width="25%" style="vertical-align:inherit;text-align: center;">
                          Diagnosa
                          Dokter
                        </th>
                        <th class="td-fkprj" style="vertical-align:inherit;text-align: center;">Diagnosa ICD 10
                        </th>
                        <th class="td-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;" width="12%">
                          #
                        </th>
                      </tr>
                    </thead>
                    <tbody v-for="(itemsss, index3) in item.diagnosaDokter" :key="index3">
                      <tr>
                        <td class="tg-0lax" style="vertical-align:inherit;text-align:center">{{ itemsss.no }}</td>
                        <td class="tg-0lax">
                          <div class="column p-1">
                            <span style="font-weight: bold;">{{ itemsss.jenisDiagnosa ? itemsss.jenisDiagnosa.label : ''
                            }}</span>

                          </div>
                        </td>
                        <td class="tg-0lax">
                          <div class="column pt-3 pb-0">
                            <span style="font-weight: bold;">{{ itemsss.keterangan }}</span>

                          </div>
                        </td>
                        <td class="tg-0lax">
                          <div class="column p-1">
                            <span style="font-weight: bold;">{{ itemsss.diagnosaa ? itemsss.diagnosaa.label : '' }}</span>

                          </div>
                        </td>
                        <td class="tg-0lax" style="vertical-align: inherit;">

                        </td>

                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

            </td>
          <tr>

          </tr>

          </tr>
          <tr>
            <td class="tg-0lax" width="10%" style="text-align:center;vertical-align: inherit;">
              <span style="font-size:20pt;font-weight:bold">P</span>
            </td>
            <td class="tg-0lax">
              <span style="font-weight: bold;">{{ item.hasilPe }}</span>

            </td>
          </tr>
          <tr>
            <td class="tg-0lax" colspan="2">
              <div class="columns is-multiline">
                <div class="column is-2" style="text-align: center;margin-top: 10px;">
                  <span>DPJP</span>
                </div>
                <div class="column"   style="margin-top: 10px;">
                  <span style="font-weight: bold;">{{ item.dokterDPJP ? item.dokterDPJP.label : '' }}</span>

                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-2" style="text-align: center;margin-top: 10px;">
                  <span>Perawat</span>
                </div>
                <div class="column"   style="margin-top: 10px;">
                  <span style="font-weight: bold;">{{ item.tenagaMedis ? item.tenagaMedis.label : '' }}</span>

                </div>
              </div>

            </td>

          </tr>
          <tr>
            <hr/>
          </tr>
        </tbody>

      </table>
    </div>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { h, reactive, ref, computed, defineComponent, watch, PropType } from 'vue'
const props = defineProps({
  cppt: {
    type: Array as PropType<any>,
  },

})
// props.cppt = props.cppt[0][props.cppt.length]
</script>
