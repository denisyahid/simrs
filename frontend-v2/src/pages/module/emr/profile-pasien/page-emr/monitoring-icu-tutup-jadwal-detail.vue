<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <MonitoringICU :dataTertutup="props.data" :hideButtons="true" />
    </div>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, PropType, onMounted } from 'vue'
import TWidgetListResep from '../t-widget-list-resep.vue'
import MonitoringICU from '../page-emr/monitoring-icu.vue'
import Dialog from 'primevue/dialog';
const previewTutupJadwalDetail = ref(false);
const listTutupJadwal = ref([]);
const isLoading = ref(false);
// const props = withDefaults(
//   defineProps<{
//     pasien?: any
//     registrasi?: any
//     FORM_NAME?: string
//     FORM_URL?: string
//     COLLECTION?: string
//     listTutupJadwal?: Array,
//     data?: any
//   }>(),
//   {
//     pasien: {},
//     registrasi: {},
//     FORM_NAME: '',
//     FORM_URL: '',
//     COLLECTION: '',
//     listTutupJadwal: [],
//     data: {},
//   }
// )
const props = defineProps({
  data: {
    type: Object,
    required: true,
    default: () => ({})
  }
});

console.log('Received data:', JSON.stringify(props.data, null, 2));
const emit = defineEmits(['load-riwayat']);

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

const COLLECTION = 'MonitoringICU';
const listTutupJadwalDetail: any = ref({});



</script>
