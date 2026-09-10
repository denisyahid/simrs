<script setup lang="ts">
import Toolbar from 'primevue/toolbar';

const emit = defineEmits<{
    (e: 'editItems', value: any): void,
    (e: 'batalKirim', value: any): void,
    (e: 'cetakBuktiKirim', value: any): void,
    (e: 'verif', value: any): void,
    (e: 'gotoRetur', value: any): void,
    (e: 'unverif', value: any): void,
}>()
const props = withDefaults(
    defineProps<{
        item?: any[]
    }>(),
    {
        item: () => [],
    }
)
</script>

<template>
  <VIconButton v-if="props.item.status == 'Kirim Barang'" :disabled="props.item.statuskirim == 2 ? true : false" v-tooltip.bottom.center="'Edit Barang'" icon="feather:edit" color="warning" raised
    circle class="mr-2" @click="$emit('editItems', item)">
  </VIconButton>
  <VIconButton v-if="props.item.status == 'Kirim Barang'" :disabled="props.item.statuskirim == 2 ? true : false" v-tooltip.bottom.center="'Batal Kirim Barang'" icon="feather:trash" color="danger"
    raised circle class="mr-2" @click="$emit('batalKirim', item)"  :loading="props.item.loading">
  </VIconButton>
  <VIconButton v-if="props.item.status == 'Terima Barang'" :disabled="props.item.statuskirim != 2 ? true : false"   v-tooltip.bottom.center="'Batal Verif Terima Barang'" 
    icon="fas fa-times" color="danger" outlined circle class="mr-2" @click="$emit('unverif', item)"  :loading="props.item.loading">
  </VIconButton>

  <VIconButton v-tooltip.bottom.center="'Cetak Bukti'" icon="feather:printer" color="primary" raised
    circle class="mr-2" @click="$emit('cetakBuktiKirim', item)">
  </VIconButton>
  <!-- {{props}} -->
  <VIconButton v-if="props.item.status == 'Terima Barang'" :disabled="props.item.statuskirim == 2 ? true : false" v-tooltip.bottom.right="'Verifikasi'" 
    color="warning" outlined circle icon="fas fa-check" @click="$emit('verif', item)"/> 
  <!-- <VIconButton v-tooltip.bottom.center="'Retur Barang'" icon="fas fa-undo" color="success" raised @click="$emit('gotoRetur', item)"
    circle class="mr-2">
  </VIconButton> -->
</template>