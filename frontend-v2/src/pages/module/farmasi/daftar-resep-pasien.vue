
<template>
    <VCard>
        <div class="columns column">
            <h3 class="title is-5 mb-2 mr-1">Daftar Pasien </h3> <span> ( {{ ds_PASIEN.total }}
                Totals)</span>
        </div>
        <div class="columns  all-projects m-3 mt-0">
            <div class="columns is-multiline  projects-card-grid">
                <div class="column is-8">
                    <div class="flex-list-inner mb-4" v-if="ds_PASIEN.loading">
                        <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
                            <VFlexTableCell :column="{ grow: true, media: true }">
                                <VPlaceloadAvatar size="medium" />
                                <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                            </VFlexTableCell>
                            <VFlexTableCell>
                                <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                            </VFlexTableCell>
                            <VFlexTableCell>
                                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                            </VFlexTableCell>
                            <VFlexTableCell :column="{ align: 'end' }">
                                <VPlaceload width="10%" class="mx-1" />
                            </VFlexTableCell>
                        </div>
                    </div>
                    <div class="flex-list-inner" v-else-if="ds_PASIEN.total === 0">
                        <VCard>
                            <VPlaceholderSection title="Not found" subtitle="There is no data that match your query."
                                class="my-6">
                                <template #image>
                                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg"
                                        alt="" />
                                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                        alt="" />
                                </template>
                            </VPlaceholderSection>
                        </VCard>
                    </div>
                    <div v-else-if="ds_PASIEN.total > 0">
                        <div class="grid-item mb-4" v-for="(items, i) in dataSource" :key="items.id">
                            <div class="top-section">
                                <div class="head">
                                    <div class="title-wrap">
                                        <div class="columns">
                                            <div class="column is-3">
                                                <VAvatar size="small" :color="listColor[i]" :initials="items.initials" />
                                            </div>
                                            <div class="column is-12 mr-3">
                                                <h3>{{ items.namapasien }}</h3>
                                                <p>{{ items.nocm + (items.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)')
                                                }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="columns">
                                        <div class="column">
                                            <h4 class="heading">Info Resep</h4>
                                            <p class="fs-075">Nomor : {{ items.noresep }}</p>
                                            <p class="fs-075">Tanggal : {{ items.tglResep }}</p>
                                        </div>
                                    <!-- <div class="column">
                                            <h4 class="heading">Kelompok Pasien</h4>
                                            <p class="fs-075">{{ items.kelompokpasien }}</p>
                                            <p class="fs-075">Kelas : {{ items.namakelas }}</p>
                                                        </div> -->
                                        <div class="column">
                                            <h4 class="heading">Ruangan</h4>
                                            <p class="fs-075">{{ items.namaruangan }}</p>

                                        </div>
                                        <div class="column">
                                            <h4 class="heading">Dokter</h4>
                                            <p class="fs-075">{{ items.dokter }}</p>

                                        </div>
                                        <div class="column">
                                            <h4 class="heading">Apotik</h4>
                                            <p class="fs-075">{{ items.namaruanganapotik }}</p>
                                        </div>
                                        <div class="column">
                                            <h4 class="heading">Status</h4>
                                            <VTag color="info" :label="items.statusbayar" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bottom-section">
                                <div class="foot-block">
                                    <h4 class="heading">Action</h4>
                                    <div class="developers">
                                        <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3"
                                            color="warning" outlined raised :to="{
                                                name: 'module-farmasi-transaksi-pelayanan-farmasi',
                                                query: {
                                                    norec_pd: items.norec_pd,
                                                    norec_APD:items.norec_apd
                                                },
                                            }">
                                            Transaksi Pelayanan </VButton>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                        :total-items="ds_PASIEN.length > 5 ? currentPage.limit * 10 : 1" :max-links-displayed="5">
                        <template #before-pagination>
                        </template>
                        <template #before-navigation>
                            <VFlex class="mr-4 mt-1" column-gap="1rem">
                                <VField>

                                </VField>
                                <VField>
                                    <VControl>
                                        <div class="select is-rounded">
                                            <select v-model="currentPage.limit">
                                                <option :value="1">1 results per page</option>
                                                <option :value="5">5 results per page</option>
                                                <option :value="10">10 results per page</option>
                                                <option :value="15">15 results per page</option>
                                                <option :value="25">25 results per page</option>
                                                <option :value="50">50 results per page</option>
                                            </select>
                                        </div>
                                    </VControl>
                                </VField>
                            </VFlex>
                        </template>
                    </VFlexPagination>

                </div>
                <div class="column is-4">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h3 class="title is-5 mb-2 mr-1">Filters </h3>
                        </div>
                        <div class="column is-6">
                            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined
                                raised>
                                Clear
                                All </a>
                        </div>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="feather:search">
                                    <input v-model="item.qnama" v-on:keyup.enter="fetchOrder()" type="text" class="input is-rounded"
                                    placeholder="Filter Nama..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3 switch-filter">
                            <VControl>
                                <VSwitchBlock v-model="item.statusbayar" label="Lunas" color="danger" class="p-0" />
                            </VControl>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel>Periode</VLabel>
                                <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static icon="feather:arrow-right" />
                                            </VControl>
                                            <VControl subcontrol icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel>No RM</VLabel>
                                <VControl icon="feather:user">
                                    <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="fetchOrder()"
                                        placeholder="No RM" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel>No Regisrasi</VLabel>
                                <VControl icon="feather:book">
                                    <VInput type="text" v-model="item.qnoregistrasi" v-on:keyup.enter="fetchOrder()"
                                        placeholder="No Regisrasi" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VLabel>Apotik</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.apotik" :options="d_ruangan"
                                        placeholder="Pilih data" :searchable="true" @select="fetchOrder()" :attrs="{ id }" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VButton @click="fetchOrder()" :loading="ds_PASIEN.loading" type="button" icon="feather:search"
                                class="is-fullwidth mr-3" color="info" raised> Apply Filters
                            </VButton>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </VCard>
</template>

    
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import InputSwitch from 'primevue/inputswitch';
import { useHead } from '@vueuse/head'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';

useHead({
    title: 'Daftar Resep Pasien - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const route = useRoute()
const item: any = ref({
    statusbayar: false,
    isKK: false,
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const currentPage: any = ref({
    limit: 5,
    rows: 50
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})

const dataSource: any = ref([])
let d_ruangan: any = ref([])
let ds_PASIEN: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))

const fetchOrder = async () => {
    console.log(item.value.apotik)
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let statusbayar = item.value.statusbayar == true ? '&statusbayar=Lunas' : ''
    let idRuangan = item.value.apotik ? `&ruanganfk=${item.value.apotik}` : ''
    let nama = item.value.qnama ? `&qnama=${item.value.qnama}` : ''
    let nocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : ''
    let noRegis = item.value.qnoregistrasi ? `&noregis=${item.value.qnoregistrasi}` : ''
    ds_PASIEN.value.loading = true
    await useApi().get(`/farmasi/daftar-resep-pasien?${tglAwal}${tglAkhir}${statusbayar}${idRuangan}${nama}${nocm}${noRegis}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}`).then((response: any) => {
        response.daftar.forEach((element: any, i: any) => {
            element.no = i + 1
            element.statusbayar = element.statusbayar ? element.statusbayar : 'Belum Dibayar'
            element.tglResep = H.formatDate(element.tglresep, 'DD-MMM-YYYY')
            let ini = element.namapasien.split(' ')
            let init = element.namapasien.substr(0, 1)
            if (ini.length > 1) {
                init = init + ini[1].substr(0, 1)
            }
            element.initials = init
        });
        dataSource.value = response.daftar
        ds_PASIEN.value.total = response.daftar.length
        ds_PASIEN.value.loading = false
    })
}

const getListCombo = async () => {
    const response  = await useApi().get(`/farmasi/input-resep-cbo`)
    d_ruangan.value = response.ruanganFarmasi.map((e: any) => { return { label: e.namaruangan, value: e.id } })
}

function clearFilter() {
    delete item.value.qnama
    delete item.value.qnocm
    delete item.value.qnik
    delete item.value.qbpjs
    delete item.value.qalamat
    fetchOrder()
}

getListCombo()
fetchOrder()

watch(
    () => item.value.statusbayar,
    () => {
        fetchOrder()
    }
)

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
    margin-top: 14px;
}
</style>
