<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Mapping Ruangan To Produk</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-sysadmin-master-map-ruangan-to-produk' }" light
                                        dark-outlined>
                                        Batal
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary"
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
                                <div class="column is-8">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel>Nama Ruangan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.namaruangan" :options="d_Ruangan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                @select="handleChangeProduk(item.namaruangan)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4 mt-4">
                                  <VField>
                                      <VControl raw subcontrol>
                                          <VCheckbox v-model="item.cekAll" :value="true"
                                              :label="'Check All'" color="info" square
                                              :class="item.cekAll == true ? 'is-solid' : ''"
                                              @change="checkAll(item.cekAll)" />
                                      </VControl>
                                  </VField>
                                </div>
                                <Divider align="center">
                                    <span class="p-tag">Produk</span>
                                </Divider>
                                <div class="column is-12 mt-0 pt-0" v-if="listChecked.length > 0">
                                    <VCard>
                                        <div class="form-section pr-0 mt-0 pt-0">
                                            <div class="form-section-inner has-padding-bottom h-700-o ">
                                                <h3 class="has-text-centered">Data Terpilih ({{ listChecked.length }})
                                                  
                                                </h3>
                                                <div class="columns is-multiline">
                                                    <VIconButton circle class="mr-2 is-pulled-right" icon="fas fa-trash"
                                                    v-tooltip.danger.bubble="'Hapus Terpilih'"
                                                    color="danger" raised bold @click="clearSelection()">
                                                    </VIconButton>
                                              </div>
                                              <div style="height: 300px;overflow: auto;">
                                                <div class="columns is-multiline mt-2" >
                                                    <div class="column is-4" v-for="items in listChecked"
                                                        :key="items.namaproduk">
                                                        <VTag color="purple"  style="width: 100%; justify-content: start;" :label="items.namaproduk" />
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                                <div class="column is-12">
                                    <VCard>

                                        <div class="columns is-multiline">
                                            <div class="column is-6">
                                                <VField>
                                                    <h3 class="title is-6 mb-2 mr-1"> Filter Produk </h3>
                                                    <VControl icon="feather:search">
                                                        <input v-model="filters" class="input custom-text-filter"
                                                            placeholder="Search..."   v-on:keyup.enter="listDropdownProd()"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3 mt-5">
                                            <VIconButton type="button" color="success" class="searcv-button" raised
                                            icon="fas fa-search" @click="listDropdownProd()" :loading="isLoadingBtn">
                                        </VIconButton>
                                        </div>
                                            <div class="column is-3">
                                                <VField>
                                                    <h3 class="title is-6 mb-2 mr-1"> Limit  </h3>
                                                    <VControl icon="fas fa-sort-amount-up-alt">
                                                        <input v-model="item.limit" class="input custom-text-filter"
                                                            placeholder="Limit..." />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <p> Data terpilih <b>{{ listChecked.length }}</b></p>
                                        <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                                            <div class="column is-6" :key="items.id"
                                                v-for="items in d_Produk">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="checkboxProduk[items.id]" :value="items.id"
                                                            :label="items.namaproduk" color="info" square
                                                            :class="checkboxProduk[items.id] == true ? 'is-solid' : ''"
                                                            @change="clickProduk()" />
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
import AutoComplete from 'primevue/autocomplete';
import { useToaster } from '/@src/composable/toaster'
import { elements } from '/@src/data/landing/components'

useHead({
    title: 'Map Ruangan To Produk - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PRODUK = useRoute().query.id as string
let ID_RUANGAN = useRoute().query.objectruanganfk as string
let ID_PRODUK_SET = ref()
const date = ref(new Date())
const item: any = ref({
    limit:100
})
const checkboxProduk: any = ref([])
const items: any = ref([])
const dataSource: any = ref([])
let d_Ruangan: any = ref([])
let d_Departemen: any = ref([])
let d_Produk: any = ref([])
const listChecked: any = ref([])
const d_ListDefault: any = ref([])
let d_KelompokProduk: any = ref([])
let isLoading = ref(false)
let jumlahCeklis: any = ref(0)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoadingBtn:any =ref(false)
const filters = ref('')
const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return d_Produk.value
    }
    return d_Produk.value.filter((items: any) => {
        return (
            items.namaproduk.match(new RegExp(filters.value, 'i'))
        )
    })
})

async function listDropdown() {
  
    const response = await useApi().get(
        `/sysadmin/master-map-ruangan-to-produk-dropdown`)
    d_Ruangan.value = response.namaruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    // d_KelompokProduk.value = response.kelompokproduk.map((e: any) => { return { label: e.kelompokproduk, value: e.id } })
 

    if (ID_PRODUK) {
        ID_PRODUK_SET.value = ID_PRODUK
        produkByID(ID_PRODUK)
    }
    if (ID_RUANGAN) {
        item.value.namaruangan = ID_RUANGAN
        handleChangeProduk(ID_RUANGAN)
    }
}

async function produkByID(id: any) {
    const { data: detail } = await useApi().get(`/sysadmin/master-map-ruangan-to-produk?id=${id}`)
    item.value.namaruangan = detail[0].objectruanganfk
    item.value.arrProduk = detail[0].objectprodukfk
}

async function listDropdownProd() {
    let limit= item.value.limit ? item.value.limit :''
    let filter= filters.value? filters.value:''
    isLoadingBtn.value = true
    const response = await useApi().get(
        `/sysadmin/master-map-ruangan-to-produk-dropdown-produk?limit=${limit}&namaproduk=${filter}`)
        isLoadingBtn.value = false
    d_Produk.value = response.namaproduk
    d_ListDefault.value = response.namaproduk
    item.namaproduk = response.namaproduk[0].id
}


async function saveMapRuanganToProduk() {
    if (!item.value.namaruangan) { H.alert('warning', 'Nama Ruangan harus di pilih'); return }
    if (jumlahCeklis.value == 0) { H.alert('warning', 'Produk harus di pilih'); return }
    let arrProduk = []
    let objectK = Object.keys(checkboxProduk.value)
    for (let x = 0; x < objectK.length; x++) {
        const element = objectK[x];
        if (checkboxProduk.value[element] == true) {
            arrProduk.push({ 'produkfk': element })
        }

    }
    let json = {
        'objectruanganfk': item.value.namaruangan,
        'detail': arrProduk

    }
    // console.log(json)
    isLoading.value = true
    await useApi().post(
        `/sysadmin/save-map-ruangan-to-produk`, json).then((response: any) => {
            isLoading.value = false

        }).catch((e: any) => {
            isLoading.value = false

        })

}

function clearSelection() {
    var arrobj = Object.keys(checkboxProduk.value)
    for (let x = 0; x < arrobj.length; x++) {
        const element2 = arrobj[x];
        checkboxProduk.value[element2] = false
    }
    clickProduk()
}
function clickProduk() {
    jumlahCeklis.value = 0
  
    let objectK = Object.keys(checkboxProduk.value)
    let jumlah = 0
    for (let x = 0; x < objectK.length; x++) {
        const element = objectK[x];
        if (checkboxProduk.value[element] == true) {
            for (var i = 0; i < d_Produk.value.length; i++) {
                const element2 = d_Produk.value[i];
                if (element2.id == element) {
                    for (var z = 0; z < listChecked.value.length; z++) {
                        const element3 = listChecked.value[z];
                        if (element3.namaproduk == element2.namaproduk) {
                            listChecked.value.splice(z, 1)
                        }
                    }
                    listChecked.value.push({ namaproduk: element2.namaproduk })
                }
            }
            jumlah = jumlah + 1

        } else {
            for (var i = 0; i < d_Produk.value.length; i++) {
                const element2 = d_Produk.value[i];
                if (element2.id== element) {
                    for (var z = 0; z < listChecked.value.length; z++) {
                        const element3 = listChecked.value[z];
                        if (element3.namaproduk == element2.namaproduk) {
                            listChecked.value.splice(z, 1)
                        }
                    }
                }
            }
        }
    }
    jumlahCeklis.value = jumlah
}


function resetForm() {

}

async function handleChangeProduk(e: any) {
    checkboxProduk.value = []
    jumlahCeklis.value = 0
    isLoadingBtn.value =true
    listChecked.value = []
    await useApi().get(
        `/sysadmin/master-map-ruangan-to-produk?objectruanganfk=${e}`).then(async(response: any) => {
            isLoading.value = false
            isLoadingBtn.value =false
            
            jumlahCeklis.value = response.data.length
            if( response.data.length > 0){
                d_Produk.value = []
                for (let x = 0; x < response.data.length; x++) {
                    const element = response.data[x];
                    checkboxProduk.value[element.objectprodukfk] = true
                    d_Produk.value.push( {id:element.objectprodukfk,namaproduk:element.namaproduk})
                }
            }else{
                listDropdownProd()
            }
         
            
            await clickProduk()
        }).catch((e: any) => {
            isLoading.value = false
        })

    checkboxProduk.value.total = checkboxProduk.value.length
}
const checkAll = (e:any) =>{
  checkboxProduk.value =[]
  for (let x = 0; x < d_Produk.value.length; x++) {
    const element =  d_Produk.value[x];
    checkboxProduk.value[element.id] = e
  }
  clickProduk()
}
listDropdown()
listDropdownProd()

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
