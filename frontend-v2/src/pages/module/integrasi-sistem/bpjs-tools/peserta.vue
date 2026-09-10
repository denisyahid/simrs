<template>
<h1>Peserta</h1>

<div class="columns is-multiline">
    <div class="column is-3">
        <VField>
            <VLabel>Pencarian</VLabel>
            <VControl>
                <VRadio v-model="item.pencarian" value="nobpjs" label="No Kartu" name="outlined_radio" color="primary" />
                <VRadio v-model="item.pencarian" value="nik" label="NIK" name="outlined_radio" color="primary" />
            </VControl>
        </VField>
    </div>
    <div class="column is-7">
        <VField>
                <VLabel>Masukan No Kartu/NIK</VLabel>
                <VControl>
                <VInput type="text" placeholder="Masukan No Kartu/NIK" v-model="item.nomor" />
                </VControl>
            </VField>
    </div>
    <div class="column is-2">
        <br />
        <VButtons>
            <VIconButton color="success" @click="cariKepesertaan()" icon="feather:search" :loading="isLoadingCall" />
            <VIconButton color="danger" @click="resetKepesertaan()" icon="feather:refresh-cw" />
        </VButtons>
    </div>
</div>
<div data-v-dc329976 class="demo-code-wrapper">
<div data-v-dc329976 class="demo-code">
<div>
<pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.json }}</code>
</pre>
</div>
</div>
</div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'

import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
declare const grecaptcha: any;
const setView = () => {
    useHead({
        title: 'Peserta - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const isLoadingCall = ref(false)
let item: any = reactive({
    pencarian: 'nobpjs'
})

const cariKepesertaan = async () => {
    if(!item.pencarian) {
        useToaster().error('Harap pilih jenis pencarian terlebih dahulu !')
        return
    }

    if(!item.nomor) {
        useToaster().error('Harap masukan No Kartu/NIK terlebih dahulu !')
        return
    }

    let url;
    if(item.pencarian == "nobpjs") url = `Peserta/nokartu/${item.nomor}/tglSEP/${ H.formatDate(new Date(),'YYYY-MM-DD')}`
    if(item.pencarian == "nik") url = `Peserta/nik/${item.nomor}/tglSEP/${ H.formatDate(new Date(),'YYYY-MM-DD')}`
    let token = await grecaptcha.execute( import.meta.env.VITE_CAPTCHA_SITEKEY, { action: 'submit' })
    let json = {
        "url": url,
        "method": "GET",
        "data": null,
        "token":token
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
        isLoadingCall.value = false
        item.json = response
    })

}

const resetKepesertaan = () => {
    delete item.nomor
    delete item.json
}
</script>
<style lang="scss">
.demo-code-wrapper[data-v-dc329976] {
    display: flex;
    flex-direction: column-reverse;
    margin-top: 2rem;
    overflow-x: auto
}

.demo-code-wrapper .demo-code[data-v-dc329976] {
    flex-grow: 1
}

.demo-code-wrapper[data-v-dc329976] .shiki {
    border-radius: var(--radius-large)
}

.demo-code-wrapper[data-v-dc329976] .shiki code {
    counter-reset: step;
    counter-increment: step 0;
}

.demo-code-wrapper[data-v-dc329976] .shiki code .line:before {
    content: counter(step);
    counter-increment: step;
    width: 1rem;
    margin-inline-end:1.5rem;display: inline-block;
    text-align: inset-inline-end;
    color: #898d98
}

.demo-code-wrapper .demo-state[data-v-dc329976] {
    position: relative;
    margin-bottom: 1.5rem;
    max-width: 100%
}

.demo-code-wrapper .demo-state[data-v-dc329976]:before {
    position: absolute;
    top: .6em;
    inset-inline-end: 1em;
    z-index: 2;
    font-size: .8rem;
    color: #888;
    content: "state"
}

.is-dark .demo-state pre[data-v-dc329976] {
    background: #1a1a1f;
    border-radius: .75rem;
    color: #c0c0d1
}

.is-dark .content[data-v-dc329976] code {
    background: var(--background-gray)
}

pre.shiki {
    background: #f5f5f5!important;
    height:500px;
    overflow-y: auto;
}

.is-dark pre.shiki {
    background: #1a1a1f!important
}


</style>
