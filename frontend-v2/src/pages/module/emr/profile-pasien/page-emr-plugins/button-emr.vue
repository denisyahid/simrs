<template>
  <div class="buttons">
    <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="emit('kembaliKeun')">
      Kembali
    </VButton> -->
    <!-- <VButton type="button" rounded outlined color="info" raised icon="lnir lnir-printer"
      :disabled="!NOREC_EMRPASIEN || NOREC_EMRPASIEN.length == 0" @click="printVerWNA" v-if="!props.isHideCetakWNA"> Cetak (WNA)
    </VButton> -->
    <VButton type="button" rounded color="purple" raised icon="feather:file-plus" :loading="props.isLoading" outlined
      @click="$emit('simpanTemplate')" v-if="!isHideST" :disabled="isLockSimpan"> Simpan Template
    </VButton>
    <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
      :disabled="!NOREC_EMRPASIEN || NOREC_EMRPASIEN.length == 0" @click="print" v-if="!props.isHideCetak"> Cetak
    </VButton>
    <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="props.isLoading"
      @click="$emit('simpan')" :disabled="isLockSimpan"> Simpan
    </VButton>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
const emit = defineEmits<{
  (e: 'simpan'): void,
  (e: 'simpanTemplate'): void,
  // (e: 'print'): void,
  (e: 'kembaliKeun'): void,
}>()
const props = withDefaults(
  defineProps<{
    isLoading?: boolean
    NOREC_EMRPASIEN?: any
    ID_EMR?: any
    ID?: any
    RUANGAN?: any
    COLLECTION?: any
    SIZE?: any
    isHideCetak?: boolean
    isHideCetakWNA?: boolean
    isHideST?: boolean
    isLockSimpan?: boolean
    registrasi?: any
  }>(),
  {
    isLoading: false,
    NOREC_EMRPASIEN: null,
    ID_EMR: null,
    ID: null,
    RUANGAN: null,
    SIZE: null,
    isHideCetak: false,
    isHideCetakWNA: true,
    isHideST: false,
    isLockSimpan: false,
    registrasi: {}
  }
)
const print = async () => {
  // Main Parameter
  let collection = `${props.COLLECTION}`;
  let emrpasienfk = `&emrpasienfk=${props.NOREC_EMRPASIEN}`;

  // Additional Parameter
  let id = props.ID ? `&id=${props.ID}` : '';
  let emrid = props.ID ? `&emrid=${props.ID_EMR}` : '';
  let ruangan = props.ID ? `&ruangan=${props.RUANGAN}` : '';
  let SIZE = props.SIZE ? `&size=${props.SIZE}` : ''
  let noregis = props.registrasi.noregistrasi ? `&noregistrasi=${props.registrasi.noregistrasi}` : ''

  H.printBlade(`emr/cetak/${collection}?pdf=true${emrpasienfk}${id}${emrid}${ruangan}${noregis}`)

  // Trying to make it dynamic
  // H.printBlade(`emr/cetak-v2/${collection}?pdf=true${emrpasienfk}${SIZE}`)
}
const printVerWNA = async () => {
  H.printBlade(`emr/cetak/${props.COLLECTION}?pdf=true&emrpasienfk=${props.NOREC_EMRPASIEN}&wna=true&id=${props.ID}`)
}
</script>
