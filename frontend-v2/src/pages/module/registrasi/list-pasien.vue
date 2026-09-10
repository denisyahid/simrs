

<template>
    <VCard radius="rounded">
        <div class="columns column">
            <h3 class="title is-5 mb-2 mr-1">Pasien </h3>
            <span>{{ '(' + (ds_PASIEN.total != undefined ? ds_PASIEN.total : 0) + ' totals)' }}
            </span>
        </div>
        <div class="columns is-multiline">
            <div class="column is-9">
                <div class="user-grid-toolbar">
                    <div class="buttons">
                        <VField v-slot="{ id }" class="is-icon-select">
                            <VControl>
                                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                                    :options="d_View" :searchable="true" track-by="name" mode="single"
                                    @select="changeView(selectView)" autocomplete="off">
                                    <template #singlelabel="{ value }">
                                        <div class="multiselect-single-label">
                                            <div class="select-label-icon-wrap">
                                                <i :class="value.icon"></i>
                                            </div>
                                            <span class="select-label-text">
                                                {{ value.name }}
                                            </span>
                                        </div>
                                    </template>
                                    <template #option="{ option }">
                                        <div class="select-option-icon-wrap">
                                            <i :class="option.icon"></i>
                                        </div>
                                        <span class="select-option-text">
                                            {{ option.name }}
                                        </span>
                                    </template>
                                </Multiselect>
                            </VControl>
                        </VField>
                        <VField class="h-hidden-mobile">
                            <VControl>
                                <VSwitchBlock v-model="item.qAktif" label="Pasien Aktif" color="danger"
                                    @change="changeSwitch(item.qAktif)" />
                            </VControl>
                        </VField>
                    </div>
                </div>

                <div class="user-grid user-grid-v2" v-if="selectView == 'grid'">
                    <div class="columns is-multiline" v-if="ds_PASIEN.loading">
                        <!--Grid item-->
                        <div v-for="key in 8" :key="key" class="column is-4">
                            <div class="grid-item">
                                <VPlaceloadAvatar size="big" centered class="mb-2" />

                                <VPlaceloadText class="mb-4" width="80%" :lines="3" last-line-width="60%" centered />

                                <div class="people">
                                    <VPlaceloadAvatar size="small" class="mx-1" />
                                    <VPlaceloadAvatar size="small" class="mx-1" />
                                    <VPlaceloadAvatar size="small" class="mx-1" />
                                    <VPlaceloadAvatar size="small" class="mx-1" />
                                </div>

                                <VButtons>
                                    <VButton placeload="100%" dark-outlined>loading ...</VButton>
                                    <VButton placeload="100%" dark-outlined>loading ...</VButton>
                                </VButtons>
                            </div>
                        </div>
                    </div>
                    <!--List Empty Search Placeholder -->
                    <VPlaceholderPage v-else-if="ds_PASIEN.length === 0" title="We couldn't find any matching results."
                        subtitle="Too bad. Looks like we couldn't find any matching results for the
          search terms you've entered. Please try different search terms or
          criteria." larger>
                        <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg"
                                alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                alt="" />
                        </template>
                    </VPlaceholderPage>

                    <TransitionGroup name="list" tag="div" class="columns is-multiline"
                        v-else-if="ds_PASIEN.length > 0">
                        <!--Grid item-->
                        <div v-for="item in ds_PASIEN" :key="item.id" class="column is-4">
                            <div class="grid-item-wrap is-clickable" >
                                <!-- @click="clickCard(item)" -->
                                <div :class="'grid-item-head ' + (
                                    item.norec_pd != null ? 'is-registrasi' : ''
                                )">
                                    <div class="flex-head">
                                        <div class="meta">
                                            <span v-if="item.norec_pd != null" class="dark-inverted">
                                                {{ item.namaruangan }}
                                            </span>

                                            <span v-if="item.norec_pd == null" class="dark-inverted">
                                                Belum Registrasi
                                            </span>
                                            <span>
                                                {{
                                                item.norec_pd == null
                                                ? 'NIK ' + item.noidentitas
                                                : H.formatDateIndoSimple(item.tglregistrasi)
                                                }}
                                            </span>
                                        </div>
                                        <div v-if="item.norec_pd != null" class="status-icon is-success">
                                            <i aria-hidden="true" class="fas fa-check"></i>
                                        </div>
                                        <div v-if="item.norec_pd == null" class="status-icon is-danger">
                                            <i aria-hidden="true" class="fas fa-times"></i>
                                        </div>
                                    </div>

                                </div>
                                <div class="grid-item">
                                    <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')"
                                        :badge="(item.jeniskelamin == 'LAKI-LAKI' ? '/images/other/male.png'
                                        : '/images/other/female.png')" size="big" />
                                    <h3 class="dark-inverted">{{ item.namapasien }}</h3>
                                    <p>{{ item.nocm }}</p>
                                    <div class="people">
                                        <VSnack :title="(item.norec_pd != null ? item.dokter : item.tgllahir)"
                                            color="info"
                                            :icon="(item.norec_pd != null ? 'fa:user-md' : 'feather:calendar')">
                                            <i class="iconify" data-icon="feather:plus"></i>
                                        </VSnack>
                                    </div>
                                    <div class="buttons">
                                        <button class="button v-button is-success" @click="emr(item)"
                                        v-if="item.norec_pd != null" style="width:100%">
                                            <span class="icon" style="color:white" >
                                                <i aria-hidden="true" class="iconify" data-icon="feather:user"></i>
                                            </span>
                                            <span style="color:white" >Profile</span>
                                        </button>
                                        <button class="button v-button is-dark-outlined" @click="registrasi(item)"
                                            v-if="item.norec_pd == null">
                                            <span class="icon">
                                                <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                            </span>
                                            <span>Registrasi</span>
                                        </button>
                                        <button class="button v-button is-dark-outlined" @click="emr(item)"
                                            v-if="item.norec_pd == null">
                                            <span class="icon">
                                                <i aria-hidden="true" class="iconify"
                                                    data-icon="fa:stethoscope"></i>
                                            </span>
                                            <span>Riwayat EMR</span>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>
                <!--List-->
                <div class="list-view list-view-v1" v-else-if="selectView == 'list'">
                    <!--List Empty Search Placeholder -->
                    <VPlaceholderPage v-if="ds_PASIEN.length === 0" title="We couldn't find any matching results."
                        subtitle="Too bad. Looks like we couldn't find any matching results for the
        search terms you've entered. Please try different search terms or
        criteria." larger>
                        <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg"
                                alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg"
                                alt="" />
                        </template>
                    </VPlaceholderPage>

                    <div class="list-view-inner" v-else-if="ds_PASIEN.length > 0">
                        <!--Item-->
                        <TransitionGroup name="list-complete" tag="div">
                            <div v-for="(item, key) in ds_PASIEN" :key="key" class="list-view-item">
                                <div class="list-view-item-inner is-clickable" @click="clickCard(item)">
                                    <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')"
                                        :badge="(item.jeniskelamin == 'LAKI-LAKI' ? '/images/other/male.png'
                                        : '/images/other/female.png')" size="large" />
                                    <div class="meta-left">
                                        <h3>{{ item.namapasien }}</h3>
                                        <span>
                                            <i aria-hidden="true" class="iconify" data-icon="fa:address-card"></i>
                                            <span class="ml-1 mt-1">{{ item.noidentitas }}</span>
                                        </span>
                                        <!-- <br>
                                        <span>
                                            <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                            <span>{{ item.alamatlengkap }}</span>
                                        </span> -->
                                        <div class="icon-list">
                                            <span>
                                                <i aria-hidden="true" class="lnil lnil-cardiology fs-1"></i>
                                                <span class="fs-1">{{ item.tgllahir }}</span>
                                            </span>
                                            <span>
                                                <i aria-hidden="true" class="lnil
                                                 lnil-sort fs-1"></i>
                                                <span class="fs-1">{{ item.umur }}</span>
                                            </span>
                                            <span>
                                                <i aria-hidden="true" class="lnil lnil-map fs-1"></i>
                                                <span class="fs-1">{{ item.alamatlengkap }}</span>
                                            </span>

                                        </div>
                                    </div>
                                    <div class="meta-right">
                                        <div class="tags">
                                            <VTag :label="item.norec_pd != null ? item.namaruangan : 'Belum Registrasi'"
                                                :color="item.norec_pd != null ? 'success' : 'danger'" rounded elevated
                                                v-tooltip.left="item.norec_pd != null ? item.namaruangan : 'Belum Registrasi'" />
                                            <VTag v-if="item.norec_pd != null"
                                                :label="H.formatDate(item.tglregistrasi, 'YYYY-MM-DD')"
                                                :color="'success'" rounded elevated class="mt-1" />
                                        </div>

                                        <div class="stats">
                                            <div class="stat">
                                                <span>{{ item.nocm }}</span>
                                                <span>No RM</span>
                                            </div>
                                            <div class="separator"></div>
                                            <div class="stat">
                                                <span>{{ item.nohp ? item.nohp : '0000000000000' }}</span>
                                                <span>No HP</span>
                                            </div>
                                            <div class="separator"></div>
                                            <div class="stat">
                                                <span>{{ item.jeniskelamin }}</span>
                                                <span>JK</span>
                                            </div>
                                        </div>
                                        <!--Dropdown-->
                                        <VDropdown icon="feather:more-vertical" spaced right>
                                            <template #content>
                                                <a role="menuitem" href="#" class="dropdown-item is-media">
                                                    <div class="icon">
                                                        <i aria-hidden="true" class="lnil lnil-user-alt"></i>
                                                    </div>
                                                    <div class="meta">
                                                        <span>Profile</span>
                                                        <span>lihat profile</span>
                                                    </div>
                                                </a>
                                                <a v-if="item.norec_pd == null" role="menuitem" href="#"
                                                    class="dropdown-item is-media">
                                                    <div class="icon">
                                                        <i aria-hidden="true" class="lnil lnil-pointer"></i>
                                                    </div>
                                                    <div class="meta">
                                                        <span>Registrasi</span>
                                                        <span>Daftarkan pasien ke ruangan</span>
                                                    </div>
                                                </a>
                                                <a v-else-if="item.norec_pd != null" role="menuitem" href="#"
                                                    class="dropdown-item is-media">
                                                    <div class="icon">
                                                        <i aria-hidden="true" class="lnil lnil-medical-sign"></i>
                                                    </div>
                                                    <div class="meta">
                                                        <span>EMR</span>
                                                        <span>Lihat Rekam Medis pasien </span>
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


                <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                    :total-items="ds_PASIEN.total < 5 ? ds_PASIEN.total : 50" :max-links-displayed="5">
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
            <div class="column is-3">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VField>
                            <VControl icon="feather:search">
                                <input v-model="item.qnama" v-on:keyup.enter="filter()" type="text"
                                    class="input is-rounded" placeholder="Filter Nama..." />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <h3 class="title is-5 mb-2 mr-1">Filters </h3>
                    </div>
                    <div class="column is-6">
                        <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined
                            raised> Clear
                            All </a>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>No RM</VLabel>
                            <VControl icon="feather:user">
                                <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="filter()"
                                    placeholder="No RM" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>NIK</VLabel>
                            <VControl icon="feather:book">
                                <VInput type="text" v-model="item.qnik" v-on:keyup.enter="filter()" placeholder="NIK" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>No BPJS</VLabel>
                            <VControl icon="feather:book">
                                <VInput type="text" v-model="item.qbpjs" v-on:keyup.enter="filter()"
                                    placeholder="No BPJS" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Alamat</VLabel>
                            <VControl icon="feather:map">
                                <VInput type="text" v-model="item.qalamat" v-on:keyup.enter="filter()"
                                    placeholder="Alamat" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VButton @click="filter()" :loading="ds_PASIEN.loading" type="button" icon="feather:search"
                            class="is-fullwidth mr-3" color="purple" raised> Apply Filters
                        </VButton>
                    </div>
                </div>

            </div>
        </div>
    </VCard>
</template>
<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue'
import type { VAvatarProps } from '/@src/components/base/avatar/VAvatar.vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import * as H from '/@src/utils/appHelper'
export interface UserData extends VAvatarProps {
    id: number
    username: string
    fullName: string
    location: string
    position: string
    bio: string
    tasks: {
        pending: number
    }
    status: string
    team: VAvatarProps[]
}
useHead({
    title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let QPARAMS_NAMA = useRoute().query.norm as string
// const users = gridData.users as UserData[]
// console.log(users)
// const filters = ref('')
// const filteredData = computed(() => {
//     if (!filters.value) {
//         return users
//     } else {
//         return users.filter((item) => {
//             return (
//                 item.username.match(new RegExp(filters.value, 'i')) ||
//                 item.location.match(new RegExp(filters.value, 'i')) ||
//                 item.position.match(new RegExp(filters.value, 'i')) ||
//                 item.badge?.match(new RegExp(filters.value, 'i'))
//             )
//         })
//     }
// })
const route = useRoute()
const router = useRouter()
const currentPage: any = ref({
    limit: 5,
    rows: 50
})

const d_View = [
    {
        name: 'Grid View',
        value: 'grid',
        icon: 'fas fa-id-card-alt',
    },
    {
        name: 'List View',
        value: 'list',
        icon: 'fas fa-list',
    },
]
const selectView: any = ref()
selectView.value = 'grid'
const columns = {
    username: {
        label: 'User',
        grow: true,
        media: true,
    },
    location: 'Location',
    industry: 'Industry',
    status: 'Status',
    contacts: 'Relations',
    actions: {
        label: 'Actions',
        align: 'end',
    },
} as const
let item: any = reactive({})
let ds_PASIEN: any = ref([])
if (QPARAMS_NAMA != undefined) {
    item.qnocm = QPARAMS_NAMA
}
async function fetchPasien() {
    ds_PASIEN.value = []
    ds_PASIEN.value.loading = true

    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    let namapasien = ''
    let nocm = ''
    let nik = ''
    let nobpjs = ''
    let alamat = ''
    let pasienAktif = ''
    if (item.qnama) namapasien = `&namapasien=${item.qnama}`
    if (item.qnocm) nocm = `&nocm=${item.qnocm}`
    if (item.qnik) nik = `&nik=${item.qnik}`
    if (item.qbpjs) nobpjs = `&bpjs=${item.qbpjs}`
    if (item.qalamat) alamat = `&alamat=${item.qalamat}`
    if (item.qAktif) pasienAktif = `&pasien_aktif=${item.qAktif}`
    const { data: pasien } = await useApi().get(`/registrasi/list-pasien-grid?offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${namapasien}${nocm}${nik}${nobpjs}${alamat}${pasienAktif}`)
    for (let x = 0; x < pasien.length; x++) {
        const element = pasien[x];
        let ini = element.namapasien.split(' ')
        let init = element.namapasien.substr(0, 1)
        if (ini.length > 1) {
            init = init + ini[1].substr(0, 1)
        }
        element.initials = init
    }
    ds_PASIEN.value.loading = false
    ds_PASIEN.value = pasien
    ds_PASIEN.value.total = pasien.length
}
function filter() {
    fetchPasien()
}
function clearFilter() {
    delete item.qnama
    delete item.qnocm
    delete item.qnik
    delete item.qbpjs
    delete item.qalamat
    item.qAktif = false
    fetchPasien()
}
function changeSwitch(e: any) {
    fetchPasien()
}
function registrasi(e: any) {
    router.push({
        name: 'module-registrasi-registrasi-ruangan',
        query: {
            nocmfk: e.id,
        },
    })
}
function emr(e: any) {
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.id,
            norec_pd: e.norec_pd,
        },
    })
}
function changeView(e: any) {
    selectView.value = e
}
function clickCard(e: any) {
    if (e.norec_pd == null) {
        router.push({
            name: 'module-registrasi-registrasi-ruangan',
            query: {
                nocmfk: e.id,
            },
        })
    } else {
        router.push({
            name: 'module-emr-profile-pasien',
            query: {
                nocmfk: e.id,
                norec_pd: e.norec_pd,
            },
        })
    }
}
currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})
watch(currentPage.value, () => {
    fetchPasien()
})
fetchPasien()
</script>
<style lang="scss">
@import '/@src/scss/module/registrasi/list-pasien';
</style>
