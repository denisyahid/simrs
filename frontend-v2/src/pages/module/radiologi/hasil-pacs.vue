<template>
  <div class="iframe-container">
    <iframe :src="iframeURL" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
  </div>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useRoute, useRouter } from 'vue-router'
import axios, { AxiosInstance } from 'axios'
useHead({
  title: 'Hasil PACS - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let URL = useRoute().query.url as string
const iframeLoadError = ref(false)
const iframeURL: any = ref('')
if (window.location.host.indexOf('192.168') > -1) {
  iframeURL.value = URL
} else {
  iframeURL.value = URL
}

console.log(iframeURL.value)

// try {
//   const response = await axios.get(iframeURL.value);
// } catch (error) {
//   console.error('Error fetching content:', error.message);
//   window.history.back()
  window.open(iframeURL.value,'_blank')
// }

</script>

<style lang="scss">
.iframe-container {
  height: 100vh;
  overflow: hidden;
}
</style>
