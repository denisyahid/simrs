<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
        <h3 class="title is-5 head-sep mt-3-min" style="text-align: center;">
          <span> Catatan Edukasi Informasi</span>
        </h3>
        <div class="columns is-multiline">
          <div class="column is-12">
            <table class="table-pri">
              <thead class="tg">
                <tr>
                  <th class="th-pri text-center font-bold">Tanggal/Jam</th>
                  <th class="th-pri text-center">Materi Informasi dan Edukasi</th>
                  <th class="th-pri text-center">Pemberi Edukasi</th>
                  <th class="th-pri text-center">Tanda Tangan Penerima Edukasi</th>
                  <th class="th-pri text-center">Metode Edukasi / Durasi</th>
                  <th class="th-pri text-center">Response</th>
                </tr>
              </thead>
              <tbody v-for="(item, index) in props.edukasi" :key="index">

                <tr v-for="(item2, index2) in item.details" :key="index2">

                    <td class="td-pri text-center">{{ H.formatDateIndo(item2.tanggalJam) || '-' }}</td>
                    <td class="td-pri text-center">{{ item2.mie }}</td>
                    <td class="td-pri text-center">
                      <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (item2.tenagaMedis)"> <br>
                      <span> {{ item2.tenagaMedis }} - {{ item2.flag }}</span>
                    </td>
                    <td class="td-pri text-center">
                      <img :src="item2.ttd" alt="">
                    </td>
                    <td class="td-pri text-center">{{ item2.metodeedukasi }} / {{ item2.durasiedukasi }}</td>
                    <td class="td-pri text-center">{{ item2.respon }}</td>
                </tr>
              </tbody>
            </table>
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
  edukasi: {
    type: Array as PropType<any>,
  },
})
console.log(props.edukasi);


const formatDate = (date: any) => {
  const options: Intl.DateTimeFormatOptions = {
    timeZone: 'Asia/Jakarta',
    weekday: 'long',
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  };

  // Format tanggal dan waktu menggunakan Intl.DateTimeFormat
  return new Intl.DateTimeFormat('id-ID', options).format(new Date(date));
};
const getTTDPenerimaEdukasi = computed(() => {
  if (!props.edukasi || !Array.isArray(props.edukasi)) {
    return [];
  }

  // Filter kunci yang sesuai dengan pola "TTDPenerimaEdukasi-*"
  return props.edukasi
    .map((item: any) => {
      return Object.keys(item)
        .filter((key) => key.startsWith("TTDPenerimaEdukasi-"))
        .map((key) => ({ key, value: item[key] }));
    })
    .flat();
});

</script>
