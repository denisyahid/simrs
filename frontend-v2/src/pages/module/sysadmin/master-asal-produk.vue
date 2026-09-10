<template>
    <div>
        <div class="tile-grid-toolbar">
            <VControl icon="feather:search">
                <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
            </VControl>

            <div class="buttons">
                <VField>
                    <VControl>
                        <VSwitchBlock color="danger" v-model="input.qAktif" label="Aktif"
                            @change="changeAktif(input.qAktif)" />
                    </VControl>
                </VField>
                <VButton color="primary" raised @click="add()">
                    <span class="icon">
                        <i aria-hidden="true" class="fas fa-plus"></i>
                    </span>
                    <span>Tambah Asal Produk</span>
                </VButton>
            </div>
        </div>
        <div class="tile-grid tile-grid-v1">
            <!--List Empty Search Placeholder -->
            <VPlaceholderPage :class="[dataSourcefiltered.length !== 0 && 'is-hidden']"
                title="We couldn't find any matching results." subtitle="Too bad. Looks like we couldn't find any matching results for the
              search terms you've entered. Please try different search terms or
              criteria." larger>
                <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-6.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-6-dark.svg" alt="" />
                </template>
            </VPlaceholderPage>
            <!--Tile Grid v1-->
            <TransitionGroup name="list" tag="div" class="columns is-multiline">
                <!--Grid item-->
                <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-4">
                    <div class="tile-grid-item">
                        <div class="tile-grid-item-inner">
                            <VIconBox size="medium" color="danger" rounded>
                                <i class="lnir lnir-suitcase" aria-hidden="true"></i>
                            </VIconBox>
                            <div class="meta">
                                <span class="dark-inverted">{{ item.asalproduk }}</span>
                            </div>
                            <VDropdown icon="feather:more-vertical" spaced right>
                                <template #content>
                                    <a role="menuitem" class="dropdown-item is-media" @click="detail(item)">
                                        <div class="icon">
                                            <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Detail</span>
                                            <span>Untuk melihat data </span>
                                        </div>
                                    </a>
                                    <a role="menuitem" class="dropdown-item is-media" @click="edit(item)">
                                        <div class="icon">
                                            <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Edit</span>
                                            <span>Untuk merubah data </span>
                                        </div>
                                    </a>
                                    <a role="menuitem" class="dropdown-item is-media" @click="deleterow(item)">
                                        <div class="icon">
                                            <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Remove</span>
                                            <span>Hapus Data dari Daftar Ruangan</span>
                                        </div>
                                    </a>
                                </template>
                            </VDropdown>
                        </div>
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </div>
    <template>
        <VModal :open="modalDetail" title="Lihat Asal Produk" actions="right" @close="modalDetail = false">
            <template #content>
                <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VField label="Asal Produk">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.asalproduk" placeholder="Asal Produk"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Nama Eksternal">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.namaexternal" placeholder="Nama Eksternal"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Report Display">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.reportdisplay" placeholder="Report Display"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Departemen</VLabel>
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_departemen"
                                    placeholder="Pilih data" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Kelompok Produk</VLabel>
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.objectkelompokprodukfk" :options="d_kelompokproduk"
                                    placeholder="Pilih data" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </form>
            </template>
        </VModal>
    </template>
    <VModal :open="modalInput" title="Tambah Asal Produk" actions="right" @close="modalInput = false">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VField label="Asal Produk">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.asalproduk" placeholder="Asal Produk"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Nama Eksternal">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.namaexternal" placeholder="Nama Eksternal"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Report Display">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.reportdisplay" placeholder="Report Display"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Departemen</VLabel>
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.objectdepartemenfk" :options="d_departemen"
                                    placeholder="Pilih data" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Kelompok Produk</VLabel>
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.objectkelompokprodukfk" :options="d_kelompokproduk"
                                    placeholder="Pilih data" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton icon="feather:plus" @click="save()" :loading="isLoading" color="primary" raised>Simpan</VButton>
        </template>
    </VModal>
</template>
<script setup lang="ts">
import { computed, ref, reactive } from 'vue'
import { useApi } from '/@src/composable/useApi'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useHead } from '@vueuse/head'
useHead({
  title: 'Asal Produk - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true
})
const input: any = reactive({
  qAktif: true,
})
const modalInput = ref(false)
const modalDetail = ref(false)
const isLoading = ref(false)
const filters = ref('')
const d_departemen = ref([])
const d_kelompokproduk = ref([])
const dataSource: any = ref([])
const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.asalproduk.match(new RegExp(filters.value, 'i'))
            ||
            items.namadepartemen.match(new RegExp(filters.value, 'i'))
        )
    })
})

function loadData() {
    dataSource.value = []

    useApi().get(
        `/sysadmin/master-asal-produk?statusenabled=${input.qAktif}`).then((response: any) => {
            dataSource.value = response.data
        })
}
function loadDropdown() {
    d_departemen.value = []
    useApi().get(
        `/sysadmin/master-asal-produk-dropdown`).then((response: any) => {
            d_departemen.value = response.departemen.map((e: any) => { return { label: e.namadepartemen, value: e.id } })
            d_kelompokproduk.value = response.kelompokproduk.map((e: any) => { return { label: e.kelompokproduk, value: e.id } })
        })
}
function add() {
    clear()
    modalInput.value = true
}
function edit(e: any) {
    item.value.id = e.id
    item.value.asalproduk = e.asalproduk
    item.value.reportdisplay = e.asalproduk
    item.value.namaexternal = e.namaexternal
    item.value.departemen = e.objectdepartemenfk
    modalInput.value = true
}
function detail(e: any) {
    item.value.id = e.id
    item.value.asalproduk = e.asalproduk
    item.value.reportdisplay = e.asalproduk
    item.value.namaexternal = e.namaexternal
    item.value.departemen = e.objectdepartemenfk
    modalDetail.value = true
}
async function save() {
    if (!item.value.asalproduk) {
        useToaster().error('Nama Ruangan harus di isi')
        return
    }
    var objSave =
    {
        'asalproduk': {
            'id': item.value.id ? item.value.id : '',
            'asalproduk': item.value.asalproduk,
            'reportdisplay': item.value.reportdisplay ? item.value.reportdisplay : null,
            'namaexternal': item.value.namaexternal ? item.value.namaexternal : null,
            'objectdepartemenfk': item.value.objectdepartemenfk ? item.value.objectdepartemenfk : null,
            'objectkelompokprodukfk': item.value.objectkelompokprodukfk ? item.value.objectkelompokprodukfk : null,
        }
    }
    isLoading.value = true
    await useApi().post(
        `/sysadmin/save-asal-produk`, objSave).then((response: any) => {
            isLoading.value = false
            clear()
            loadData()
        }, (error) => {
            isLoading.value = false
            // console.log(error)
        })
}
async function deleterow(e: any) {
    await useApi().post(
        `/sysadmin/delete-asal-produk`, { 'id': e.id }).then((response: any) => {
            loadData()
        }, (error) => {
        })
}
function clear() {
    item.value = {}
    modalInput.value = false
}
function changeAktif(e: any) {
    loadData()
}
loadDropdown()
loadData()
</script>
    <style lang="scss">
    @import '/@src/scss/abstracts/all';
    @import '/@src/scss/custom/config';
    </style>
