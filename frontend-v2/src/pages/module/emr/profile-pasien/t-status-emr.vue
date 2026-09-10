<template>
  <div class="stock-dashboard">
    <div class="stock-card h-500">
      <div class="action-bar">
        <h3>Status </h3>
      </div>
      <StatusCheck :color="'github'" :icon="'fa-inverse fa fa-history'" :name="'Riwayat Registrasi'"
        class="is-clickable" :status="true" @click="() => { { emits('openEMR', { riwayat: true }) } }">
      </StatusCheck>
      <StatusCheck :color="'amazon'" :icon="'fas fa-inverse fa-stethoscope'" :name="'Vital Sign'" class="is-clickable"
        :status="props.riwayat.LIST_VITAL == true"
        @click="() => { emits('openEMR', { namaemr: 'Vital Sign', url_form: 'vital-sign' }) }"></StatusCheck>
      <StatusCheck :color="'snapchat'" :icon="'fa-inverse lnir lnir-diagnosis'" :name="'Diagnosis'" class="is-clickable"
        :status="props.riwayat.LIST_DIAGNOSIS.length > 0"
        @click="() => { emits('openEMR', { namaemr: 'Diagnosis', url_form: 'input-diagnosis' }) }"> </StatusCheck>
      <StatusCheck :color="'tindakan'" :icon="'fa-inverse fa fa-clipboard-list'" :name="'Tindakan'" class="is-clickable"
        :status="props.riwayat.LIST_TINDAKAN.length > 0"
        @click="() => { emits('openEMR', { namaemr: 'Tindakan', url_form: 'tindakan' }) }"></StatusCheck>
      <StatusCheck :color="'invision'" :icon="'fa-inverse fa fa-bong'" :name="'Laboratorium'" class="is-clickable"
        :status="props.riwayat.LIST_LAB.length > 0"
        @click="() => { emits('openEMR', { namaemr: 'Laboratorium', url_form: 'order-laboratorium' }) }"></StatusCheck>
      <StatusCheck :color="'facebook'" :icon="'fa-inverse fa fa-radiation'" :name="'Radiologi'" class="is-clickable"
        :status="props.riwayat.LIST_RAD.length > 0"
        @click="() => { emits('openEMR', { namaemr: 'Radiologi', url_form: 'order-radiologi' }) }"></StatusCheck>
      <StatusCheck :color="'pink'" :icon="'fa-inverse lnir lnir-hospital-bed-alt'" :name="'Bedah'" class="is-clickable"
        :status="props.riwayat.LIST_ORDERBEDAH == true"
        @click="() => { emits('openEMR', { namaemr: 'Bedah', url_form: 'order-bedah' }) }"></StatusCheck>
      <StatusCheck :color="'twitter'" :icon="'fa-inverse lnir lnir-medicine-alt'" :name="'Resep'" class="is-clickable"
        :status="props.riwayat.LIST_RESEP.length > 0"
        @click="() => { emits('openEMR', { namaemr: 'Resep Elektronik', url_form: 'order-resep' }) }"></StatusCheck>
      <StatusCheck :color="'invision'" :icon="'fa-inverse fas fa-laptop-medical'" :name="'EMR'" class="is-clickable"
        :status="props.riwayat.LIST_EMR.length > 0"
        @click="() => { emits('openEMR', { namaemr: 'EMR', url_form: 'module-emr-elektronik-medical-record' }) }">
      </StatusCheck>
      <div class="content">
        <div class="is-divider" data-content="Lainnya"></div>
        <div style="height: 300px;overflow: auto;padding: 5px;">
          <TStatusPojokKanan :title="'Alergi'" :subtitle="props.riwayat.LIST_STATUSPASIEN.alergi" class="inbox-widget-2"
            @click="() => { emits('openEMR', { alergi: true }) }" />
          <TStatusPojokKanan :title="'Status Pasien'" :subtitle="props.riwayat.LIST_STATUSPASIEN.statuspasien"
            class="inbox-widget-2" />
          <TStatusPojokKanan :title="'Lama Rawat'" :subtitle="props.riwayat.LIST_STATUSPASIEN.lamarawat"
            class="inbox-widget-2" />
          <TStatusPojokKanan :title="'Tgl Pulang'" :subtitle="props.riwayat.LIST_STATUSPASIEN.tglpulang"
            class="inbox-widget-2" />
          <TStatusPojokKanan :title="'Status Pulang'" :subtitle="props.riwayat.LIST_STATUSPASIEN.statuspulang"
            class="inbox-widget-2" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { h, reactive, ref, computed, defineComponent, watch, PropType } from 'vue'
import TStatusPojokKanan from './t-status-pojok-kanan.vue'
import StatusCheck from '/@src/pages/include/status-check.vue'
const props = defineProps({
  riwayat: {
    type: Object as PropType<any>,
  },
})
const emits = defineEmits<{
  (e: 'openEMR', value: any): void,
}>()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/profile-pasien';
</style>
