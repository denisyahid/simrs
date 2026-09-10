

<template>

    <VTabs slider centered selected="kelompok" :tabs="[
        { label: 'Kelompok Pasien', value: 'kelompok' },
        { label: 'Rekanan', value: 'rekanan' },
        { label: 'Map Kelompok Pasien To Rekanan', value: 'tasks' },
    ]">

        <div></div>
        <template #tab="{ activeValue }">
            <p v-if="activeValue === 'kelompok'">
            <div>
                <div class="page-content-inner">
                    <div class="jobs-dashboard">
                        <div class="jobs-dashboard-wrapper">
                            <!--Search toolbar 
                            <div class="search-menu">
                                <div class="search-bar">
                                    <VField class="is-autocomplete-select is-curved-select">
                                        <VControl icon="feather:search">
                                            <Multiselect v-model="item.objectjenistariffk" mode="tags"
                                                :searchable="true" :create-tag="true" :options="tagsOptions"
                                                placeholder="Jenis Tarif" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="search-salary">
                                    <i class="iconify" data-icon="feather:user"></i>
                                    <input type="text" v-model="item.kelompokpasien" placeholder="Kelompok Pasien" />
                                </div>
                                <div class="search-job">
                                    <i class="iconify" data-icon="feather:clipboard"></i>
                                    <input type="text" v-model="item.jenistarif" placeholder="Report Display " />
                                </div>
                                <VButton color="primary" raised class="search-button" @click="save()"
                                    :loading="isLoading">Tambah Data</VButton>
                            </div>-->

                            <!--Dashboard content -->
                            <div class="main-container">
                                <!--Left Alert -->
                                <div class="search-type">
                                    <VField>
                                        <VControl icon="feather:search">
                                            <input v-model="filters" class="input custom-text-filter"
                                                placeholder="Search..." />
                                        </VControl>

                                    </VField>

                                    <!--Left filters block -->
                                    <div class="job-time">
                                        <div class="job-time-title">Jenis Pembiayaan</div>
                                        <div class="job-wrapper">
                                            <div class="type-container">
                                                <VCheckbox v-model="jobType" alue-true="job-type-1" label="Umum/Pribadi"
                                                    color="primary" paddingless />

                                            </div>
                                            <div class="type-container">
                                                <VCheckbox v-model="jobType" value-true="job-type-2" label="BPJS"
                                                    color="primary" paddingless />

                                            </div>
                                            <div class="type-container">
                                                <VCheckbox v-model="jobType" value-true="job-type-3"
                                                    label="Asuransi Lain" color="primary" paddingless />

                                            </div>
                                            <div class="type-container">
                                                <VCheckbox v-model="jobType" value-true="job-type-4" label="Perusahaan"
                                                    color="primary" paddingless />

                                            </div>

                                        </div>
                                    </div>

                                    <!--Left filters block -->
                                    <div class="job-time">
                                        <div class="job-time-title">Instalasi</div>
                                        <div class="job-wrapper">
                                            <div class="type-container">
                                                <VCheckbox v-model="jobSeniority" value-true="job-seniority-1"
                                                    label="Rawat Jalan" color="primary" paddingless />

                                            </div>
                                            <div class="type-container">
                                                <VCheckbox v-model="jobSeniority" value-true="job-seniority-2"
                                                    label="Rawat Inap" color="primary" paddingless />

                                            </div>
                                            <div class="type-container">
                                                <VCheckbox v-model="jobSeniority" value-true="job-seniority-3"
                                                    label="IGD" color="primary" paddingless />

                                            </div>
                                            <div class="type-container">
                                                <VCheckbox v-model="jobSeniority" value-true="job-seniority-4"
                                                    label="Radiologi" color="primary" paddingless />

                                            </div>
                                            <div class="type-container">
                                                <VCheckbox v-model="jobSeniority" value-true="job-seniority-5"
                                                    label="Laboratorium" color="primary" paddingless />

                                            </div>

                                        </div>
                                    </div>


                                </div>

                                <!--Results-->
                                <div class="searched-jobs">
                                    <!--Results toolbar-->
                                    <div class="searched-bar">

                                        <div class="searched-count">
                                            <VControl class="is-pulled-right">
                                                <VSwitchBlock v-model="item.aktif" label="Aktif" color="danger"
                                                    @change="changeSwitch(item.aktif)" />
                                            </VControl>
                                        </div>
                                        <div class="searched-link">
                                            <VButton color="primary" raised @click="add()">
                                                <span class="icon">
                                                    <i aria-hidden="true" class="fas fa-plus"></i>
                                                </span>
                                                <span> Tambah Data</span>
                                            </VButton>

                                        </div>


                                    </div>

                                    <!--Results content-->
                                    <div class="user-grid user-grid-v1" v-if="dataSource.loading">
                                        <div class="columns is-multiline">
                                            <!--Grid item-->
                                            <div v-for="key in 9" :key="key" class="column is-4">
                                                <div class="grid-item">
                                                    <VPlaceloadAvatar size="big" centered class="mb-2" />

                                                    <VPlaceloadText class="mb-4" width="80%" :lines="3"
                                                        last-line-width="60%" centered />

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
                                    </div>

                                    <div class="job-cards" v-else-if="!dataSource.loading">
                                        <div v-for="(job, index) in dataSourcefiltered" :key="index" class="job-card">
                                            <div style="float:right">
                                                <VTag :color="job.status_c" :label="job.status" />
                                            </div>
                                            <div class="job-card-header">
                                                <img class="job-card-logo"
                                                    :src="'/images/pasien/' + (index + 1) + '.svg'" alt="" />
                                            </div>
                                            <div class="job-card-title">{{ job.kelompokpasien }}</div>
                                            <div class="job-card-subtitle">
                                                <i class="iconify" data-icon="feather:activity" aria-hidden="true"></i>
                                                <span class="ml-1"> {{ job.jenistarif }}</span>
                                            </div>
                                            <div class="job-card-buttons">
                                                <VButtons>
                                                    <VButton color="primary" raised @click="edit(job)">Edit</VButton>
                                                    <VButton dark-outlined raised @click="deleterow(job)">Hapus </VButton>
                                                </VButtons>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <VModal :open="modalInput" title="Tambah Kelompok Pasien" actions="right"
                                    @close="modalInput = false">
                                    <template #content>
                                        <form class="modal-form">
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <VField label="Nama Kelompok Pasien">
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" v-model="item.kelompokpasien"
                                                                placeholder="Nama Kelompok Pasien" class="is-rounded" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-8">
                                                    <VField class="is-rounded-select is-autocomplete-select">
                                                        <VLabel>Jenis Tarif</VLabel>
                                                        <VControl icon="feather:search">
                                                            <Multiselect mode="single" v-model="item.objectjenistariffk"
                                                                :options="d_jenistarif" placeholder="Pilih data"
                                                                :searchable="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField class="is-rounded-select is-autocomplete-select">
                                                        <VLabel>Status</VLabel>
                                                        <VControl>
                                                            <VSwitchBlock v-model="item.statusenabled"
                                                                :options="d_status" label="Aktif" color="danger" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                            </div>
                                        </form>
                                    </template>
                                    <template #action>
                                        <VButton icon="feather:plus" @click="save()" :loading="isLoadingTT"
                                            color="primary" raised>Simpan</VButton>
                                    </template>
                                </VModal>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </p>
            <p v-else-if="activeValue === 'rekanan'">
                <MasterRekanan></MasterRekanan>
            </p>
            <p v-else-if="activeValue === 'tasks'">
                <MasterMapKelompok></MasterMapKelompok>
            </p>
        </template>

    </VTabs>
</template>
<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { ref, computed, watch, reactive } from 'vue'
import { jobs } from '/@src/data/dashboards/jobs'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useRoute, useRouter } from 'vue-router'
import MasterRekanan from './master-rekanan.vue'
import MasterMapKelompok from './master-map-kelompok.vue'
export type Job = 'web-developer' | 'uiux-designer' | 'backend-developer'
useHead({
    title: 'Kelompok Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const isLoading = ref(false)
const isLoadingTT: any = ref(false)
const modalInput = ref(false)
const modalDetail = ref(false)
const d_jenistarif = ref([])
const route = useRoute()
const router = useRouter()
const tagsValue: Job[] = []
const tagsOptions = [
    { value: 't', label: 'True' },
    { value: 'uiux-designer', label: 'UI/UX' },
    { value: 'backend-developer', label: 'Backend' },
]
// const item: any = reactive({})
const item: any = ref({
    aktif: true
})
const d_status = [
    { value: 't', label: 'True' },
    { value: 'f', label: 'False' },
]
const jobType = ref(['job-type-2'])
const jobSeniority = ref(['job-seniority-3', 'job-seniority-4'])
const jobSalary = ref(['job-salary-5', 'job-salary-6'])
const dataSource: any = ref([])
const currentPage: any = ref({
    limit: 5,
    rows: 50
})
const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.kelompokpasien.match(new RegExp(filters.value, 'i'))
    )
  })
})

function loadData() {
    fetchData()
}
function loadDropdown() {
    d_jenistarif.value = []
    useApi().get(
        `sysadmin/master-kelompok-pasien-dropdown`).then((response: any) => {
            d_jenistarif.value = response.jenistarif.map((e: any) => { return { label: e.jenistarif, value: e.id } })
        })
}
function add() {
    clear()
    modalInput.value = true
}
function edit(e: any) {
    item.value.id = e.id
    item.value.kelompokpasien = e.kelompokpasien
    item.value.objectjenistariffk = e.objectjenistariffk
    item.value.statusenabled = e.statusenabled
    modalInput.value = true
}
function detail(e: any) {
    item.value.id = e.id
    item.value.kelompokpasien = e.kelompokpasien
    item.value.objectjenistariffk = e.objectjenistariffk
    item.value.statusenabled = e.statusenabled
    modalDetail.value = true
}

async function fetchData() {
    isLoading.value = true
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = offset * limit - limit
    let rows: any = currentPage.value.rows
    let KelompokPasien = ''
    let JenisTarif = ''
    let StatusEnabled = ''

    if (item.kelompokpasien) KelompokPasien = '&kelompokpasien' + item.kelompokpasien
    if (item.objectjenistariffk) JenisTarif = '&objectjenistariffk=' + item.objectjenistariffk
    item.value.aktif ? StatusEnabled = '&statusenabled=' + item.value.aktif : StatusEnabled = '&statusenabled=false'
    const response = await useApi().get(
        '/sysadmin/master-kelompok-pasien?offset=' + offset +
        '&limit=' + limit +
        '&rows=' + rows +
        KelompokPasien + JenisTarif + StatusEnabled
    )
    isLoading.value = false
    for (let x = 0; x < response.data.length; x++) {
        const element = response.data[x];
        element.no = x + 1
    }

    dataSource.value = response.data
    //   dataSource.value.total = response.length
}
async function save() {
   
    if (!item.value.kelompokpasien) {
        useToaster().error('Nama Kelompok Pasien harus di isi')
        return
    }
    var objSave =
    {
        'kelompokpasien': {
            'id': item.value.id ? item.value.id : '',
            'kelompokpasien': item.value.kelompokpasien,
            'objectjenistariffk': item.value.objectjenistariffk ? item.value.objectjenistariffk : null,
        }
    }
    isLoadingTT.value = true
    await useApi().post(
        `/sysadmin/save-master-kelompok-pasien`, objSave).then((response: any) => {
            isLoadingTT.value = false
            clear()
            fetchData()
        }, (error) => {
            isLoadingTT.value = false
            // console.log(error)
        })
}
async function deleterow(e: any) {
  await useApi().post(
    `/sysadmin/delete-master-kelompok-pasien`, { 'id': e.id }).then((response: any) => {
      fetchData()
    }, (error) => {
    })
}
function clear() {
    item.value = {}
    modalInput.value = false
}
function changeSwitch(e: any) {
    fetchData()
}
// loadData()
loadDropdown()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.is-dark {
    .user-grid {
        .grid-item {
            @include vuero-card--dark;
        }
    }
}

.user-grid-v1 {

    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }

    .grid-item {
        @include vuero-s-card;

        text-align: center;

        >.v-avatar {
            display: block;
            margin: 0 auto 4px;
        }

        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        p {
            font-size: 0.85rem;
        }

        .people {
            display: flex;
            justify-content: center;
            padding: 8px 0 30px;

            .v-avatar {
                margin: 0 4px;
            }
        }

        .buttons {
            display: flex;
            justify-content: space-between;

            .button {
                width: calc(50% - 4px);
                color: var(--light-text);

                &:hover,
                &:focus {
                    border-color: var(--fade-grey-dark-4);
                    color: var(--primary);
                    box-shadow: var(--light-box-shadow);
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .user-grid-v1 {
        .columns {
            display: flex;

            .column {
                min-width: 50% !important;
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
    .user-grid-v1 {
        .columns {
            .column {
                min-width: 33.3% !important;
            }
        }
    }
}

:root {
    --header-bg-color: #fff;
    --search-border-color: #efefef;
    --subtitle-color: #83838e;
    --button-color: var(--white);
    --input-color: var(--subtitle-color);
}

.is-dark {
    --header-bg-color: var(--dark-sidebar-light-2);
    --search-border-color: var(--dark-sidebar-light-8);
    --input-color: var(--white);
}

.jobs-dashboard {
    display: flex;
    flex-direction: column;
    margin: 0 auto;
    overflow: hidden;

    .jobs-dashboard-wrapper {
        width: 100%;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        scroll-behavior: smooth;
        overflow: auto;
    }

    .search-menu {
        height: 56px;
        white-space: nowrap;
        display: flex;
        flex-shrink: 0;
        align-items: center;
        background-color: var(--header-bg-color);
        border-radius: 8px;
        width: 100%;
        padding-left: 0.75rem;

        >div:not(:last-of-type) {
            border-right: 1px solid var(--search-border-color);
        }

        .search-bar {
            height: 55px;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            padding-right: 1.5rem;

            .field {
                width: 100%;
            }

            .multiselect-tags {
                padding-left: 2.5rem;
            }
        }

        .search-location,
        .search-job,
        .search-salary {
            display: flex;
            align-items: center;
            width: 50%;
            font-size: 14px;
            font-weight: 500;
            padding: 0 25px;
            height: 100%;
            font-family: var(--font);

            input {
                width: 100%;
                height: 100%;
                display: block;
                font-family: var(--font);
                color: var(--input-color);
                background-color: transparent;
                border: none;
            }

            svg {
                margin-right: 0.5rem;
                width: 18px;
                color: var(--primary);
                flex-shrink: 0;
            }
        }

        .search-button {
            background-color: var(--primary);
            min-width: 120px;
            height: 55px;
            border: none;
            font-weight: 500;
            font-family: var(--font);
            padding: 0 1rem;
            border-radius: 0 0.75rem 0.75rem 0;
            color: var(--button-color);
            cursor: pointer;
            margin-left: auto;
        }
    }

    .main-container {
        display: flex;
        flex-grow: 1;
        padding-top: 2rem;

        .search-type {
            width: 270px;
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-shrink: 0;
        }

        .alert {
            background-color: var(--widget-grey);
            padding: 1.75rem;
            border-radius: 8px;

            .alert-title {
                font-size: 1rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 0.75rem;
            }

            .alert-subtitle {
                font-size: 13px;
                font-family: var(--font);
                color: var(--subtitle-color);
                margin-bottom: 1.5rem;
            }

            input {
                border-radius: 6px;
            }
        }

        .job-time {
            padding-top: 1.75rem;

            .job-wrapper {
                padding-top: 1.75rem;
            }

            .job-time-title {
                font-size: 0.95rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
            }

            .type-container {
                display: flex;
                align-items: center;
                color: var(--subtitle-color);
                font-size: 13px;

                label {
                    margin-left: 2px;
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                }

                +.type-container {
                    margin-top: 10px;
                }

                .job-number {
                    margin-left: auto;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 25px;
                    min-width: 25px;
                    background-color: var(--white);
                    color: var(--subtitle-color);
                    font-size: 0.8rem;
                    font-family: var(--font);
                    font-weight: 500;
                    padding: 0 0.25rem;
                    border-radius: 50rem;
                }
            }
        }

        .searched-jobs {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding-left: 2.5rem;
        }

        .searched-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            .searched-count {
                font-family: var(--font-alt);
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--dark-text);
            }
        }

        .job-cards {
            padding-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-column-gap: 1.5rem;
            grid-row-gap: 1.5rem;

            @media screen and (max-width: 1212px) {
                grid-template-columns: repeat(2, 1fr);
            }

            @media screen and (max-width: 930px) {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        .job-card {
            @include vuero-l-card;

            cursor: pointer;
            transition: 0.2s;

            &:hover,
            &:focus {
                transform: translateY(-5px);
            }

            .job-card-header {
                // display: flex;
                // align-items: flex-start;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-logo {
                width: 80px;
                height: 80px;
                margin-right: -40px;
            }

            .job-card-title {
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 0.75rem;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-subtitle {
                color: var(--subtitle-color);
                font-family: var(--font);
                font-size: 0.95rem;
                line-height: 1.6em;
                // margin-bottom: 1rem;
                margin-top: -5px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-buttons {
                margin-top: 1rem;

                .buttons {
                    justify-content: space-between;

                    .v-button {
                        width: 48%;
                    }
                }
            }
        }
    }
}

.is-dark {
    .jobs-dashboard {
        .job-card {
            @include vuero-card--dark;
        }

        .main-container {
            .alert {
                @include vuero-card--dark;
            }

            .job-time {
                .job-number {
                    background: var(--dark-sidebar-light-2);
                }
            }
        }
    }
}

@media screen and (max-width: 620px) {
    .job-cards {
        grid-template-columns: repeat(1, 1fr);
    }
}

@media screen and (max-width: 730px) {
    .job-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (max-width: 767px) {
    .jobs-dashboard {
        .search-menu {
            flex-direction: column;
            height: auto;
            padding: 1rem;

            >div:not(:last-of-type) {
                border-right: none;
            }

            .search-bar {
                padding: 0;
            }

            .search-location,
            .search-job,
            .search-salary {
                width: 100%;
                height: 44px;
                padding: 0;
            }

            .search-button {
                width: 100%;
                border-radius: 0.75rem;
            }
        }

        .main-container {
            .search-type {
                display: none;
            }

            .searched-jobs {
                padding-left: 0;
            }
        }
    }
}
</style>
