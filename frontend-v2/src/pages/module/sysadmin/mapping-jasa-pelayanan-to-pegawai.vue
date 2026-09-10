<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Mapping Jasa Layanan to Pegawai</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-sysadmin-master-map-ruangan-to-produk' }" light dark-outlined>
                                        Batal
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoadingSave" color="primary"
                                        raised @click="saveMapRuanganToProduk()"> Simpan
                                    </VButton>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Jenis Pagu</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.jenisPagu" :options="d_JenisPagu"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                @select="choiceJenisPagu(item.jenisPagu)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Detil Jenis Pagu</VLabel>
                                        <VControl icon="feather:search" :loading="isLoading">
                                            <Multiselect mode="single" v-model="item.detailJenisPagu"
                                                @select="getListPegawai(item)" :options="d_DetailJenisPagu"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                                <!-- <div class="column is-3 mt-4">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="item.cekAll" :value="true" :label="'Check All'" color="info"
                                                square :class="item.cekAll == true ? 'is-solid' : ''"
                                                @change="checkAll(item.cekAll)" />
                                        </VControl>
                                    </VField>
                                </div> -->

                                <Divider align="center">
                                    <span class="p-tag">Pegawai</span>
                                </Divider>
                                <div class="column is-12 mt-0 pt-0" v-if="listChecked.length > 0">
                                    <VCard>
                                        <div class="form-section pr-0 mt-0 pt-0">
                                            <div class="form-section-inner has-padding-bottom h-700-o ">
                                                <h3 class="has-text-centered">Data Terpilih ({{ listChecked.length }})
                                                    <VIconButton circle class="mr-2 is-pulled-right" icon="fas fa-trash"
                                                        v-tooltip.danger.bubble="'Hapus Terpilih'" color="danger" raised
                                                        bold @click="clearSelection()">
                                                    </VIconButton>
                                                </h3>

                                                <div class="columns is-multiline mt-2">
                                                    <div class="column is-4" v-for="items in listChecked"
                                                        :key="items.namalengkap">
                                                        <VTag color="purple" style="width: 100%; justify-content: start;"
                                                            :label="items.namalengkap" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                                <div class="column is-12">
                                    <VCard>

                                        <div class="columns is-multiline">
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <h3 class="title is-6 mb-2 mr-1"> Pilih Pegawai </h3>
                                                <VControl icon="feather:search">
                                                    <input v-model="filters" class="input custom-text-filter"
                                                        placeholder="Search..." />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <p> Data terpilih <b>{{ listChecked.length }}</b></p>
                                        <div class="columns is-multiline" style="max-height:500px;overflow: auto;">
                                            <div class="column is-6 mt-2" v-for="data in 18" v-if="isLoadingPegawai">
                                                <VPlaceload class="mx-2" />
                                            </div>
                                            <div class="column is-6" v-else :key="items.id"
                                                v-for="items in dataSourcefiltered">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="checkboxPegawai[items.id]" :value="items.id"
                                                            :label="items.namalengkap" color="info" square
                                                            :class="checkboxPegawai[items.id] == true ? 'is-solid' : ''"
                                                            @change="clickPegawai()" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                    </VCard>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import Divider from 'primevue/divider';
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'


useHead({
    title: 'Mapping Jasa Layanan to Pegawai - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PRODUK = useRoute().query.id as string
let ID_MODALITY = useRoute().query.modality as string
let ID_PRODUK_SET = ref()
const date = ref(new Date())
const item: any = ref({})
const checkboxPegawai: any = ref([])
const items: any = ref([])
const dataPegawai: any = ref([])
let d_JenisPagu: any = ref([])
let d_DetailJenisPagu: any = ref([])
let d_Produk: any = ref([])
const listChecked: any = ref([])
const d_ListDefault: any = ref([])
let d_KelompokProduk: any = ref([])
let isLoading = ref(false)
let isLoadingPegawai = ref(true)
let isLoadingSave = ref(false)
let jumlahCeklis: any = ref(0)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})
const filters = ref('')
const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataPegawai.value
    }
    return dataPegawai.value.filter((items: any) => {
        return (
            items.namalengkap.match(new RegExp(filters.value, 'i'))
        )
    })
})

const dataCombo = async () => {
    await useApi().get('remunerasi/get-combo-idx').then((response) => {
        d_JenisPagu.value = response.jenispagu.map((e: any) => {
            return { label: e.jenispagu, value: e.id }
        })
        dataPegawai.value = response.pegawai
    })
    isLoadingPegawai.value = false
}

const choiceJenisPagu = async (e: any) => {
    isLoading.value = true
    await useApi().get(`remunerasi/get-pegawai-by-jenis-pagu?jpid=${e}`).then((response: any) => {
        d_DetailJenisPagu.value = response.detailjenispagu.map((e: any) => {
            return { label: e.detailjenispagu, value: e.id }
        })
    })
    isLoading.value = false
}
dataCombo()



// async function listDropdown() {
//     const response = await useApi().get(`/sysadmin/get-mapping-pagu-to-pegawai`)
//     d_Produk.value = response.data
//     d_ListDefault.value = response.data
//     item.namalengkap = response.data

//     if (ID_PRODUK) {
//         ID_PRODUK_SET.value = ID_PRODUK
//         produkByID(ID_PRODUK)
//     }
//     if (ID_MODALITY) {
//         item.value.modality = ID_MODALITY
//         handleChangeProduk(ID_MODALITY)
//     }
// }

// async function produkByID(id: any) {
//     const { data: detail } = await useApi().get(`/sysadmin/master-map-ruangan-to-produk?id=${id}`)
//     item.value.modality = detail[0].modality
//     item.value.arrPegawai = detail[0].id
// }



async function saveMapRuanganToProduk() {

    if (jumlahCeklis.value == 0) { H.alert('warning', 'Pegawai harus di pilih'); return }
    if (!item.value.jenisPagu) { H.alert('warning', 'Jenis Pagu harus di pilih'); return }
    if (!item.value.detailJenisPagu) { H.alert('warning', 'Detail Jenis Pagu harus di pilih'); return }
    let arrPegawai = []
    let objectK = Object.keys(checkboxPegawai.value)
    for (let x = 0; x < objectK.length; x++) {
        const element = objectK[x];
        if (checkboxPegawai.value[element] == true) {
            arrPegawai.push({ 'pegawai': element })
        }

    }
    let json = {
        'jenisPeagu': item.value.jenisPagu,
        'detailJenisPagu': item.value.detailJenisPagu,
        'detail': arrPegawai
    }
    isLoadingSave.value = true
    await useApi().post('sysadmin/save-map-pagu-to-pegawai', json).then((response: any) => {
        isLoadingSave.value = false

    }).catch((e: any) => {
        isLoadingSave.value = false

    })

}

function clearSelection() {
    var arrobj = Object.keys(checkboxPegawai.value)
    for (let x = 0; x < arrobj.length; x++) {
        const element2 = arrobj[x];
        checkboxPegawai.value[element2] = false
    }
    clickPegawai()
}

function clickPegawai() {
    jumlahCeklis.value = 0
    let objectK = Object.keys(checkboxPegawai.value)
    let jumlah = 0
    for (let x = 0; x < objectK.length; x++) {
        const element = objectK[x];
        if (checkboxPegawai.value[element] == true) {
            for (var i = 0; i < dataPegawai.value.length; i++) {
                const element2 = dataPegawai.value[i];
                if (element2.id == element) {
                    for (var z = 0; z < listChecked.value.length; z++) {
                        const element3 = listChecked.value[z];
                        if (element3.namalengkap == element2.namalengkap) {
                            listChecked.value.splice(z, 1)
                        }
                    }
                    listChecked.value.push({ namalengkap: element2.namalengkap })
                }
            }
            jumlah = jumlah + 1

        } else {
            for (var i = 0; i < dataPegawai.value.length; i++) {
                const element2 = dataPegawai.value[i];
                if (element2.id == element) {
                    for (var z = 0; z < listChecked.value.length; z++) {
                        const element3 = listChecked.value[z];
                        if (element3.namalengkap == element2.namalengkap) {
                            listChecked.value.splice(z, 1)
                        }
                    }
                }
            }
        }
    }
    jumlahCeklis.value = jumlah
}

const getListPegawai = async (e: any) => {
    checkboxPegawai.value = []
    jumlahCeklis.value = 0
    await useApi().get(`/sysadmin/get-mapping-pagu-to-pegawai?jenispagu=${e.jenisPagu}&detailjenispagu=${e.detailJenisPagu}`).then((response: any) => {
        isLoading.value = false
        console.log(response.data)
        jumlahCeklis.value = response.data.length
        for (let x = 0; x < response.data.length; x++) {
            const element = response.data[x];
            checkboxPegawai.value[element.pegawaifk] = true
        }
        checkboxPegawai.value.total = checkboxPegawai.value.length
        clickPegawai()
    }).catch((e: any) => {
        isLoading.value = false
    })
}

// const checkAll = (e: any) => {
//     checkboxPegawai.value = []
//     for (let x = 0; x < dataSourcefiltered.value.length; x++) {
//         const element = dataSourcefiltered.value[x];
//         checkboxPegawai.value[element.id] = e
//     }
//     clickPegawai()
// }
// const unCheckAll = (e: any) => {

// }
// listDropdown()


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 800px;
    margin: 0 auto;
}


.checkbox.is-outlined.is-info.is-solid input+span::after {
    color: var(--white);
}

.form-fieldset {
    padding: 20px 0;
    max-width: 740px;
    margin: 0 auto;
}
</style>
