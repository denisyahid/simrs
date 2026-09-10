<template>
  <section>
    <div class="column is-12" v-if="props.laboratorium.length > 0">
      <div class="project-files">
        <div class="widget creative-list-widget">
          <div class="widget-toolbar">

          </div>

          <div class="creative-list panjang-250">
            <VTag class="mr-1 mb-1" :color="'success'" :label="'Normal'" />
            <VTag class="mr-1 mb-1" :color="'danger'" :label="'Tinggi/Rendah Kritis'" />
            <VTag class="mr-1 mb-1" :color="'warning'" :label="'Tinggi/Rendah'" />
            <div v-for="item in props.laboratorium" :key="item.id" class=" mb-2">
              <div :class="item.isdetail ? '' : ''">
                <div class="creative-list-item  is-clickable" :class="'is-' + item.color_status" style="margin-bottom:0;"
                  :style="item.isdetail ? 'height:60%;border-radius: 10px 10px 0 0;' : ''"
                  @click="item.isdetail = !item.isdetail">
                  <i aria-hidden="true" :class="item.icon"></i>
                  <div class="meta">
                    <p>{{ item.namaproduk }}</p>
                    <span>{{ item.tglorder }}</span>

                  </div>
                  <VTag :color="(item.status == 'verifikasi' ? 'info' : '')"
                    :label="item.hasil_lab.length == 0 ? item.status : 'selesai'" class="mt-0 ml-5 is-pulled-right" />
                </div>
                <div v-if="item.isdetail"
                  style="border-radius: 0 0 10px 10px; background-color: rgb(249 235 242); padding:  0 10px 10px 10px;"
                  class="f-table">
                  <table class="w-100">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Pemeriksaan</th>
                        <th>Hasil</th>
                        <th>Nilai Normal</th>
                      </tr>
                    </thead>
                    <tbody v-if="item.hasil_lab.length > 0" v-for="(items_d, rowIndex) in item.hasil_lab" :key="rowIndex">
                      <tr v-if="props.laboratorium_GROUP[items_d.treatment_name] != undefined &&
                        props.laboratorium_GROUP[items_d.treatment_name].index === rowIndex">
                        <td colspan="3">
                          <span class="f-bold f-italic">{{ items_d.treatment_name
                          }}</span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <VTag class="mb-1-min"
                            :color="(items_d.flag == '' ? 'success' : (items_d.flag == 'H' ? 'danger' : 'warning'))"
                            :label="''" />
                        </td>
                        <td>{{ items_d.examination_name }}</td>
                        <td><b>{{ items_d.result_value }}</b> {{
                          items_d.unit }}</td>
                        <td> {{ items_d.normal_value }}</td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr class="text-center">
                        <td colspan="4">Belum ada hasil</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <VPlaceholderPage v-else-if="props.laboratorium.length === 0" :title="H.assets().notFound"
      :subtitle="H.assets().notFoundSubtitle" larger>
      <template #image>
        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
      </template>
    </VPlaceholderPage>
  </section>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { h, reactive, ref, computed, defineComponent, watch, PropType } from 'vue'
const props = defineProps({
  laboratorium: {
    type: Array as PropType<any>,
  },
  laboratorium_GROUP: {
    type: Array as PropType<any>,
  },

})

</script>
