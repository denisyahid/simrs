<template>
  <div class="column is-12">
    <div v-if="data.length == 0">
      <VCard>
        <span style="color: var(--light-text)">Data tidak ditemukan...</span>
      </VCard>
    </div>
    <div v-else-if="data.length > 0">
      <div class="column is-12 pt-0">
        <VCard style="background-color: #baffcd;">
          <VControl>
            <VInput type="text" class="input" v-model="filters['global'].value" placeholder="Search..." />
          </VControl>
        </VCard>
      </div>
      <div v-for="(items, key) in filteredData" :key="key">
        <div class="column is-12 pb-0">
          <VCard class="is-clickable is-grey" @click="emit('openEMR', items)" style="padding:10px">
            <i aria-hidden="true" class="lnil lnil-file-name mr-2"></i>
            <span class="span-text-left-bar" style="font-size: 14pt;">{{ items.caption }}</span>
          </VCard>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { h, reactive, ref, computed, defineComponent, watch, PropType } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api';

const router = useRouter()
const filters = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
const emit = defineEmits<{
  (e: 'openEMR', value: any): void,
}>()
const props = defineProps({
  data: { type: Array as PropType<any> },
  hide: Boolean
})

// Filter
const filteredData = computed(() => {
  const keyword = filters.value.global.value?.toLowerCase() || ''
  if (!keyword) return props.data

  return props.data.filter((item: any) =>
    item.caption?.toLowerCase().includes(keyword)
  )
})
</script>