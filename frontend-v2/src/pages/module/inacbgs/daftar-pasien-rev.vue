

<template>
    <div class="page-content-inner">
        <div class="jobs-dashboard">
            <div class="jobs-dashboard-wrapper">
                <!--Search toolbar -->
                <VCard class="p-0">
                    <div class="search-menu">
                        <div class="search-bar">
                            <VField class="mt-3">
                                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
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
                        <div class="search-location p-2">
                            <i class="iconify" data-icon="feather:package"></i>
                            <Multiselect mode="single" v-model="item.objectdepartement" placeholder="Instalasi"
                                :options="d_departemen" :searchable="true" style="border:none;padding-left: 0px;" />
                        </div>
                        <div class="search-job p-0">
                            <i class="iconify" data-icon="feather:user"></i>
                            <input type="text" v-model="item.namapasien" placeholder="Nama Pasien"/>
                        </div>
                        <VButton color="primary" style="margin-top: -1px" raised class="search-button" @click="fetchData()"
                            :loading="dataSource.loading">Search</VButton>
                    </div>
                    <div class="control">
                        <VRadio v-model="item.status" value="0" label="Klaim" name="outlined_radio" color="warning"/>
                        <VRadio v-model="item.status" value="1" label="Groping" name="outlined_radio" color="primary"/>
                        <VRadio v-model="item.status" value="2" label="Final Klaim" name="outlined_radio" color="info"/>
                    </div>
                </VCard>

                <!--Dashboard content -->
                <div class="main-container">
                    <!--Left Alert -->

                    <!--Results-->
                    <div class="searched-jobs">
                        <!--Results toolbar-->


                        <!--Results content-->
                        <div class="user-grid user-grid-v1 mt-5" v-if="dataSource.loading">
                            <div class="columns is-multiline">
                                <!--Grid item-->
                                <div v-for="key in 9" :key="key" class="column is-4">
                                    <div class="grid-item">
                                        <VPlaceloadAvatar size="big" centered class="mb-2" />

                                        <VPlaceloadText class="mb-4" width="80%" :lines="3" last-line-width="60%"
                                            centered />

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
                        <div class="flex-list-inner" v-else-if="dataSource.length === 0">
                            <VPlaceholderSection title="Not found" subtitle="There is no data that match your query."
                                class="my-6">
                                <template #image>
                                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg"
                                        alt="" />
                                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                        alt="" />
                                </template>
                            </VPlaceholderSection>
                        </div>
                        <div class="job-cards" v-else-if="dataSource.length > 0">
                            <div v-for="(item, index) in dataSource" :key="index" class="job-card">
                                <div style="float:right">
                                    <VDropdown icon="feather:more-vertical" spaced right>
                                        <template #content>
                                            <a href="#" role="menuitem" class="dropdown-item is-media">
                                                <div class="icon">
                                                    <i aria-hidden="true" class="lnil lnil-pencil-alt"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Edit</span>
                                                    <span>Edit registrasi</span>
                                                </div>
                                            </a>

                                            <a href="#" role="menuitem" class="dropdown-item is-media">
                                                <div class="icon">
                                                    <i aria-hidden="true" class="lnil lnil-eye"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Details</span>
                                                    <span>Detail registrasi</span>
                                                </div>
                                            </a>

                                            <a href="#" role="menuitem" class="dropdown-item is-media">
                                                <div class="icon">
                                                    <i aria-hidden="true" class="lnil lnil-share"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Tindakan</span>
                                                    <span>Input tindakan</span>
                                                </div>
                                            </a>

                                            <a href="#" role="menuitem" class="dropdown-item is-media">
                                                <div class="icon">
                                                    <i aria-hidden="true" class="lnil lnil-share"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Share</span>
                                                    <span>Share this profile</span>
                                                </div>
                                            </a>

                                            <hr class="dropdown-divider" />

                                            <a href="#" role="menuitem" class="dropdown-item is-media">
                                                <div class="icon">
                                                    <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Remove</span>
                                                    <span>Remove from grid</span>
                                                </div>
                                            </a>
                                        </template>
                                    </VDropdown>
                                </div>
                                <div class="job-card-header">
                                    <img class="job-card-logo"
                                        :src="item.icons != null ? item.icons : '/images/avatars/svg/pasien.svg'" alt="" />
                                </div>
                                <div class="job-card-title">{{ item.namapasien }}</div>
                                <div class="job-card-subtitle">
                                    <i class="iconify" data-icon="feather:user" aria-hidden="true"></i>
                                    <span class="ml-1"><b> {{ item.namaruangan }}</b></span>
                                </div>
                                <div class="job-card-subtitle">
                                    <i class="iconify" data-icon="feather:activity" aria-hidden="true"></i>
                                    <span class="ml-1"> {{ item.noregistrasi }}</span>
                                </div>
                                <div class="job-card-subtitle">
                                    <i class="iconify" data-icon="feather:calendar" aria-hidden="true"></i>
                                    <span class="ml-1"> {{ item.tglregistrasi }}</span>
                                </div>
                                <div class="job-card-subtitle">
                                    <i class="iconify" data-icon="feather:clipboard" aria-hidden="true"></i>
                                    <span class="ml-1"> {{ item.nocm }}</span>
                                </div>

                                <div class="job-card-subtitle">
                                    <!-- <i class="iconify" data-icon="feather:credit-card" aria-hidden="true"></i> -->
                                    <!-- <span class="ml-1"> {{ item.kelompokpasien }}</span> -->
                                    <VTag color="orange" class="mt-1" :label="item.status" />
                                </div>

                                <div class="job-detail-buttons">
                                    <VTags>
                                        <VTag v-for="(category, catIndex) in item.categories" :key="catIndex" color="solid"
                                            :label="category.name" curved />
                                    </VTags>
                                </div>
                                <div class="job-card-buttons">
                                    <VButtons>
                                        <VButton color="primary" icon="fas fa-stethoscope"> Verifikasi
                                        </VButton>
                                        <VButton dark-outlined raised>Detail</VButton>

                                    </VButtons>
                                </div>
                            </div>

                        <!-- <div v-for="(job, index) in jobs" :key="index" class="job-card">
                                <div class="job-card-header">
                                    <img class="job-card-logo" :src="'/images/ruang/' + (index + 1) + '.png'" alt="" />
                                </div>
                                <div class="job-card-title">{{ item.title }}</div>
                                <div class="job-card-subtitle">
                                    {{ item.description }}
                                </div>
                                <div class="job-detail-buttons">
                                    <VTags>
                                        <VTag v-for="(category, catIndex) in item.categories" :key="catIndex"
                                            color="solid" :label="category.name" curved />
                                    </VTags>
                                </div>
                                <div class="job-card-buttons">
                                    <VButtons>
                                        <VButton color="primary" raised>Apply Now</VButton>
                                        <VButton dark-outlined>Messages</VButton>
                                    </VButtons>
                                                                                                                                                                    </div>
                                                                                                                                                                </div> -->
                        </div>
                        <VFlexPagination v-if="dataSource.total > 0" v-model:current-page="currentPage.page"
                            :item-per-page="currentPage.limit" :total-items="dataSource.total < 10 ? dataSource.total : 200"
                            :max-links-displayed="10">
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
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { reactive, ref, computed, watch } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import moment from 'moment'
import { useRoute, } from 'vue-router'
export type Job = 'web-developer' | 'uiux-designer' | 'backend-developer'
useHead({
    title: 'INACBGs E-Klaim - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const route = useRoute()
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    })
})
const dataSource: any = ref([])
const currentPage: any = ref({
    limit: 10,
    rows: 50
})

const d_departemen: any = ref([])

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})
watch(currentPage.value, () => {
    fetchData()
})

const fetchData = async () => {
    dataSource.value.loading = true
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    let tglAwal = item.value.periode ? 'tglAwal=' + moment(item.value.periode.start).format('YYYY-MM-DD') : ''
    let tglAkhir =  item.value.periode ? '&tglAkhir=' + moment(item.value.periode.end).format('YYYY-MM-DD') : ''
    let idDepartemen = item.value.objectdepartement ? `&departement=${item.value.objectdepartement }` : '' 
    let namaPas = item.value.namapasien ? `&nmpas=${item.value.namapasien }` : '' 
    let status = item.value.status ? `&status=${item.value.status}` : ''

    await useApi().get(
        'bridging/inacbg/get-daftar-pasien-inacbg-rev?'+tglAwal+tglAkhir+idDepartemen+namaPas+status+'&offset='+offset+'&limit='+limit).then((response) => {
        response.forEach((element: any) => {
            element.status = element.statusklaim == null ? '-' : element.statusklaim
        })
        dataSource.value = response
        dataSource.value.loading = false
        dataSource.value.total = response.length
    })
}

const listDataCombo = async () => {
    await useApi().get('bridging/inacbg/get-list-combo').then((response) => {
        d_departemen.value = response.map((element: any) => {
            return { label: element.namadepartemen, value: element.id }
        })
        console.log(d_departemen)
    })
}

fetchData()
listDataCombo()

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
            padding-left: 2rem;
            padding-right: 2rem;
        }

        .searched-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            .searched-count {
                font-family: var(--font-alt);
                font-size: 1rem;
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

                max-height: 42px;
                overflow: hidden;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 1;
                display: -webkit-box;
                text-align: center;
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
