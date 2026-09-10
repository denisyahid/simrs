
<script setup lang="ts">
import { h, computed, defineComponent, ref, reactive, onMounted, defineAsyncComponent } from 'vue'
const props = defineProps({
    nameform: String,
    pasien: Array,
    alamat: Array,
    registrasi: Array,
})
const AsyncRegistrasi = defineAsyncComponent(() =>
    import(`../../module/emr/profile-pasien/page-emr/${props.nameform.replace('module-emr-profile-pasien-page-emr-', '')}.vue`)
);
</script>
<template>
    <div class="page-content-inner">
      <AsyncRegistrasi :pasien="props.pasien" :alamat="props.alamat" :registrasi="props.registrasi" />
    </div>
</template>