<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="s-card mt-0 p-5" style=" border-top: 3px solid var(--green);">
        <h3 class="title is-5 head-sep mt-3-min" style="text-align: center;">
          <span> Monitoring ICU Jadwal Tertutup</span>
        </h3>
        <div class="columns is-multiline">
          <div class="column is-12">
            <table class="table-pri">
              <thead class="tg">
                <tr>
                  <th class="th-pri text-center font-bold">Tanggal & Jam Dibuat</th>
                  <th class="th-pri text-center">Status</th>
                  <th class="th-pri text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>

                <tr v-for="(item, index) in props.listTutupJadwal" :key="index">
                  <td class="td-pri">{{ item.created_at }}</td>
                  <td class="td-pri text-center">{{ item.statusJadwal }}</td>
                  <td class="td-pri text-center">
                    <VButton color="primary" raised @click="detailJadwal(item.id)">Detail</VButton>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Dialog v-model:visible="previewTutupJadwalDetail" modal header="Detail Jadwal Tertutup" :style="{ width: '100rem' }">
    <MonitoringIcuTutupJadwalDetail :data="listTutupJadwalDetail"></MonitoringIcuTutupJadwalDetail>
  </Dialog>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, PropType, onMounted } from 'vue'
import TWidgetListResep from '../t-widget-list-resep.vue'
import Dialog from 'primevue/dialog';
import MonitoringIcuTutupJadwalDetail from '../page-emr/monitoring-icu-tutup-jadwal-detail.vue'
const previewTutupJadwalDetail = ref(false);
const listTutupJadwal = ref([]);
const isLoading = ref(false);
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
    listTutupJadwal?: Array,
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
    listTutupJadwal: [],
  }
)
const emit = defineEmits(['load-riwayat']);

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

const COLLECTION = 'MonitoringICU';
const listTutupJadwalDetail: any = ref({});

const loadData = () => {
  emit('load-riwayat');
};

const detailJadwal = async (id: any) => {
  await loadRiwayatTutupJadwalDetail(id);
  previewTutupJadwalDetail.value = true;
}

const loadRiwayatTutupJadwalDetail = async (id: any) => {
  isLoading.value = true;
  try {
    const response = await useApi().get(
      `/emr/get-emr-monitoring-icu-jadwal-tertutup-detail?nocmfk=${ID_PASIEN}&collection=MonitoringICU&id=${id}`
    );
    listTutupJadwalDetail.value = response;
    isLoading.value = false;
  } catch (error) {
    isLoading.value = false;
    console.log(JSON.stringify(error));
    H.alert('error', 'Gagal memuat riwayat');
  }
};

onMounted(() => {
  loadData();
});

</script>
