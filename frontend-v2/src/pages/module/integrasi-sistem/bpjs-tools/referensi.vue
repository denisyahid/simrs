<template>
    <h1>Referensi</h1>
    <div v-for="(data, index) in items" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Diagnosa'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>Parameter Pencarian</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Parameter Pencarian" v-model="item.pencarian" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="cariDiagnosa()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonDiagnosa }}</code>
                    </pre>  
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Poli'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>Parameter Pencarian</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Parameter Pencarian" v-model="item.pencarian" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="cariPoli()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonPoli }}</code>
                    </pre>  
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Fasilitas Kesehatan'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>Jenis</VLabel>
                        <VControl>
                            <VRadio v-model="item.jenis" value="2" label="Faskes 2/RS" name="outlined_radio" color="primary" />
                            <VRadio v-model="item.jenis" value="1" label="Faskes 1" name="outlined_radio" color="primary" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel>Parameter Pencarian</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Parameter Pencarian" v-model="item.pencarian" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="cariFK()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonFK }}</code>
                    </pre>  
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Dokter DPJP'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>Jenis</VLabel>
                        <VControl>
                            <VRadio v-model="item.jenis" value="1" label="Rawat Inap" name="outlined_radio" color="primary" />
                            <VRadio v-model="item.jenis" value="2" label="Rawat Jalan" name="outlined_radio" color="primary" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel>Parameter Pencarian</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Parameter Pencarian" v-model="item.pencarian" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="cariDPJP()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonDPJP }}</code>
                    </pre>  
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Provinsi, Kota, Kecamatan'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.provinsi"
                            :attrs="{ id }"
                            :options="listProv"
                            :searchable="true"
                            placeholder="Pilih Provinsi"
                            @click="getKota(item.provinsi)"
                        />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.kabkota"
                            :attrs="{ id }"
                            :options="listKab"
                            :searchable="true"
                            placeholder="Pilih Kabupaten / Kota"
                            @click="getKecamatan(item.kabkota)"
                        />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.kec"
                            :attrs="{ id }"
                            :options="listKec"
                            :searchable="true"
                            placeholder="Pilih Kecamatan"
                        />
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Diagnosa Program PRB'">
            <div class="columns is-multiline">
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="cariDgPRB()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonDgPRB }}</code>
                    </pre>  
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Obat Generik Program PRB'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>Parameter Pencarian</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Parameter Pencarian" v-model="item.pencarian" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="cariObatPRB()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonObatPRB }}</code>
                    </pre>  
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Hanya Untuk Lembar Pengajuan Klaim'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>Procedur / Tindakan</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Ketik untuk mencari data" v-model="item.pencarianproc" @keyup="getProcedur(item.pencarianproc)"  />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-8">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VLabel>Hasil Pencarian</VLabel>
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.procedur"
                            :attrs="{ id }"
                            :options="listProcedur"
                            :searchable="true"
                            placeholder="Procedur / Tindakan"
                        />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VLabel>Dokter</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Ketik untuk mencari data" v-model="item.pencariandokter" @keyup="getDokter(item.pencariandokter)"  />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-8">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VLabel>Hasil Pencarian</VLabel>
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.doker"
                            :attrs="{ id }"
                            :options="listDJP"
                            :searchable="true"
                            placeholder="Dokter"
                        />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VLabel>Kelas Rawat</VLabel>
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.kelarawat"
                            :attrs="{ id }"
                            :options="listKelas"
                            :searchable="true"
                            placeholder="Kelas Rawat"
                        />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VLabel>Spesialistik</VLabel>
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.spesilaistik"
                            :attrs="{ id }"
                            :options="listSpesialis"
                            :searchable="true"
                            placeholder="Spesialistik"
                        />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VLabel>Ruang Rawat</VLabel>
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.ruangrawat"
                            :attrs="{ id }"
                            :options="listRuangn"
                            :searchable="true"
                            placeholder="Ruang Rawat"
                        />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VLabel>Cara Keluar</VLabel>
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.carakeluar"
                            :attrs="{ id }"
                            :options="listCaraKeluar"
                            :searchable="true"
                            placeholder="Cara Keluar"
                        />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField v-slot="{ id }" class="is-autocomplete-select">
                        <VLabel>Pasca Pulang</VLabel>
                        <VControl icon="feather:search">
                        <Multiselect
                            v-model="item.pascapulang"
                            :attrs="{ id }"
                            :options="listPasca"
                            :searchable="true"
                            placeholder="Pasca Pulang"
                        />
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'

import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'

const setView = () => {
    useHead({
        title: 'Referensi - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const isDianogsa = ref(false)
const isPoli = ref(false)
const isFK = ref(false)
const isLoadingCall = ref(false)
const listProv = ref([])
const listKab = ref([])
const listKec = ref([])
const listProcedur = ref([])
const listKelas = ref([])
const listDJP = ref([])
const listSpesialis = ref([])
const listRuangn = ref([])
const listCaraKeluar = ref([])
const listPasca = ref([])
let item: any = reactive({})
const items = reactive([
    { title: 'Diagnosa', isExpanded: false },
    { title: 'Poli', isExpanded: false },
    { title: 'Fasilitas Kesehatan', isExpanded: false },
    { title: 'Dokter DPJP', isExpanded: false },
    { title: 'Provinsi, Kota, Kecamatan', isExpanded: false },
    { title: 'Diagnosa Program PRB', isExpanded: false },
    { title: 'Obat Generik Program PRB', isExpanded: false },
    { title: 'Hanya Untuk Lembar Pengajuan Klaim', isExpanded: false },
])

const toggleCollapse = (index) => {
    for (let i = 0; i < items.length; i++) {
        if (i === index) {
            items[i].isExpanded = !items[i].isExpanded;
        } else {
            items[i].isExpanded = false;
        }
    }
}

const cariDiagnosa = async () => {
    if(!item.pencarian) {
        useToaster().error('Harap isi pencarian terlebih dahulu !')
        return
    }

    let json = {
        "url": `referensi/diagnosa/${item.pencarian}`,
        "method": "GET",
        "data": null
    }
    
    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
        isLoadingCall.value = false
        item.jsonDiagnosa = response
    })
}
const cariPoli = async () => {
    if(!item.pencarian) {
        useToaster().error('Harap isi pencarian terlebih dahulu !')
        return
    }

    let json = {
        "url": `referensi/poli/${item.pencarian}`,
        "method": "GET",
        "data": null
    }
    
    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
        isLoadingCall.value = false
        item.jsonPoli = response
    })
}
const cariFK = async () => {
    if(!item.jenis) {
        useToaster().error('Harap pilih jenis pencarian terlebih dahulu !')
        return
    }

    if(!item.pencarian) {
        useToaster().error('Harap isi pencarian terlebih dahulu !')
        return
    }

    let json = {
        "url": `referensi/faskes/${item.pencarian}/${item.jenis}`,
        "method": "GET",
        "data": null
    }
    
    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
        isLoadingCall.value = false
        item.jsonFK = response
    })
}
const cariDPJP = async () => {
    if(!item.jenis) {
        useToaster().error('Harap pilih jenis pencarian terlebih dahulu !')
        return
    }

    if(!item.pencarian) {
        useToaster().error('Harap isi pencarian terlebih dahulu !')
        return
    }

    let json = {
        "url": `referensi/dokter/pelayanan/${item.jenis}/tglPelayanan/${H.formatDate(new Date(),'YYYY-MM-DD')}/Spesialis/${item.pencarian}`,
        "method": "GET",
        "data": null
    }
    
    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
        isLoadingCall.value = false
        item.jsonDPJP = response
    })
}
const cariDgPRB = async () => {
    let json = {
        "url": `referensi/diagnosaprb`,
        "method": "GET",
        "data": null
    }
    
    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
        isLoadingCall.value = false
        item.jsonDgPRB = response
    })
}
const cariObatPRB = async () => {
    if(!item.pencarian) {
        useToaster().error('Harap isi pencarian terlebih dahulu !')
        return
    }

    let json = {
        "url": `referensi/obatprb/${item.pencarian}`,
        "method": "GET",
        "data": null
    }
    
    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
        isLoadingCall.value = false
        item.jsonObatPRB = response
    })
}
const getKota = (idProv) => {
    if(idProv == null) return
    let jsonKabKota = {
        "url": `referensi/kabupaten/propinsi/${idProv}`,
        "method": "GET",
        "data": null
    }
    useApi().post(`/bridging/bpjs/tools`, jsonKabKota).then((response: any) => {
        let data = []
        for (let o = 0; o < response.list.length; o++) {
            const element = response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listKab.value = data
    })

}
const getKecamatan = (idKabkota) => {
    if(idKabkota == null) return
    let jsonKec = {
        "url": `referensi/kecamatan/kabupaten/${idKabkota}`,
        "method": "GET",
        "data": null
    }
    useApi().post(`/bridging/bpjs/tools`, jsonKec).then((response: any) => {
        let data = []
        for (let o = 0; o < response.list.length; o++) {
            const element = response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listKec.value = data
    })
}
const getProcedur = (event) => {
    let jsonProc = {
        "url": `referensi/procedure/${event}`,
        "method": "GET",
        "data": null
    }
    useApi().post(`/bridging/bpjs/tools`, jsonProc).then((response: any) => {
        let data = []
        for (let o = 0; o < response.procedure.length; o++) {
            const element = response.procedure[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listProcedur.value = data
    })
}
const getDokter = (event) => {
    let jsonProc = {
        "url": `referensi/dokter/${event}`,
        "method": "GET",
        "data": null
    }
    useApi().post(`/bridging/bpjs/tools`, jsonProc).then((response: any) => {
        let data = []
        for (let o = 0; o < response.list.length; o++) {
            const element = response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listDJP.value = data
    })
}
const loadCombo = () => {
    let jsonProv = {
        "url": `referensi/propinsi`,
        "method": "GET",
        "data": null
    }
    useApi().postNoMessage(`/bridging/bpjs/tools`, jsonProv).then((response: any) => {
        let data = []
        for (let o = 0; o < response.response.list.length; o++) {
            const element = response.response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listProv.value = data
    })

    let jsonKelasRawat = {
        "url": `referensi/kelasrawat`,
        "method": "GET",
        "data": null
    }
    useApi().postNoMessage(`/bridging/bpjs/tools`, jsonKelasRawat).then((response: any) => {
        let data = []
        for (let o = 0; o < response.response.list.length; o++) {
            const element = response.response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listKelas.value = data
    })

    let jsonSpesialistik = {
        "url": `referensi/spesialistik`,
        "method": "GET",
        "data": null
    }
    useApi().postNoMessage(`/bridging/bpjs/tools`, jsonSpesialistik).then((response: any) => {
        let data = []
        for (let o = 0; o < response.response.list.length; o++) {
            const element = response.response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listSpesialis.value = data
    })

    let jsonRuangRawat = {
        "url": `referensi/ruangrawat`,
        "method": "GET",
        "data": null
    }
    useApi().postNoMessage(`/bridging/bpjs/tools`, jsonRuangRawat).then((response: any) => {
        let data = []
        for (let o = 0; o < response.response.list.length; o++) {
            const element = response.response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listRuangn.value = data
    })

    let jsonCaraKeluar = {
        "url": `referensi/carakeluar`,
        "method": "GET",
        "data": null
    }
    useApi().postNoMessage(`/bridging/bpjs/tools`, jsonCaraKeluar).then((response: any) => {
        let data = []
        for (let o = 0; o < response.response.list.length; o++) {
            const element = response.response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listCaraKeluar.value = data
    })

    let jsonPascaPulang = {
        "url": `referensi/pascapulang`,
        "method": "GET",
        "data": null
    }
    useApi().postNoMessage(`/bridging/bpjs/tools`, jsonPascaPulang).then((response: any) => {
        let data = []
        for (let o = 0; o < response.response.list.length; o++) {
            const element = response.response.list[o];
            data.push({
                value: element.kode,
                label: element.kode + ' - ' + element.nama,
            })
        }
        listPasca.value = data
    })

    
}

loadCombo()


</script>
<style lang="scss">
.button {
    border-width: 0px !important;
}

.s-card {
    padding: 5px !important;
    border-radius: 0px !important;
    cursor: pointer;
    margin-top: 5px;
}

.card-inner {
    padding: 12px !important;
    border-top: none;
    border-left: 1px solid var(--fade-grey-dark-3);
    border-right: 1px solid var(--fade-grey-dark-3);
    border-bottom: 1px solid var(--fade-grey-dark-3);
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