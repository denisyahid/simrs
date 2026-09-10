<route lang="yaml">
meta:
  requiresAuth: true
</route>

<script lang="ts">
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { VFlexTableWrapperDataResolver } from '/@src/components/base/table/VFlexTableWrapper.vue'
import { useHead } from '@vueuse/head'
export default defineComponent({
    props: {
        incomingCallerId: Number,
        fetchData: Object
    },
    data() {
        return {
            columns: {
                name: {
                    label: 'Username',
                    media: true,
                    grow: true,
                    sortable: true,
                },
                location: {
                    label: 'Location',
                    sortable: true,
                },
                position: {
                    label: 'Positions',
                    sortable: true,
                },
                actions: {
                    label: '',
                    align: 'end',
                },
            },
            fetchData: {} as VFlexTableWrapperDataResolver,
            total: 0,
            activeValue: 'tab1',
            queryParam: {},
            openedRowId: undefined,
            incomingCallerId: undefined
        }

    },
    methods: {

        onRowClick(row: any) {
            if (this.openedRowId === row.id) {
                this.openedRowId = undefined
            } else {
                this.openedRowId = row.id
            }
        },
        onCallClick(row: any) {
            if (this.incomingCallerId === row.id) {
                this.incomingCallerId = undefined
            } else {
                this.incomingCallerId = row.id
            }
        },
        async fetchPasien(searchTerm: any,
            start: any,
            limit: any,
            sort: any) {
            if (start == undefined) return
            // searchTerm will contains the value of the wrapperState.searchInput
            // the update will be debounced to avoid to much requests
            const searchQuery = searchTerm ? `&q=${searchTerm}` : ''
            let sortQuery = ''

            // sort will be a string like "name:asc"
            if (sort && sort.includes(':')) {
                let [sortField, sortOrder] = sort.split(':')
                sortQuery = `&_sort=${sortField}&_order=${sortOrder}`
            }

            // async fetch data to our server
            const { data } = await useApi().get(
                `/registrasi/pasien-lama?_start=${start}&_limit=${limit}${searchQuery}${sortQuery}`

            )
            this.total = data.length
            this.fetchData.limit = 100
        }


    },
    mounted() {
        useHead({
            title: 'Contoh Form',
        })
        this.fetchPasien('', 0, 10, 'asc')

    },
    created() {
        // this.fetchPasien()

    }
})

</script>

<template>
    <VCard radius="small">
        <div class="columns column">
            <h3 class="title is-5 mb-2">Example Form</h3>
        </div>
        <div class="columns">
            <div class="column is-12">
                <VTabs slider type="rounded" selected="tab1" :tabs="[
                    { label: 'Data Table', value: 'tab1' },
                    { label: 'Form Input', value: 'tab2' },
                    { label: 'Tasks', value: 'tab3' },
                ]">
                    <template #tab="{ activeValue }">
                        <div class="columns" v-if="activeValue === 'tab1'">
                            <div class="column is-12">
                                <VFlexTableWrapper v-model:page="queryParam.page" v-model:limit="queryParam.limit"
                                    v-model:searchTerm="queryParam.searchTerm" v-model:sort="queryParam.sort"
                                    :columns="columns" :data="fetchData" :total="total" class="mt-4">
                                    <template #default="wrapperState">
                                        <!--Table Pagination-->
                                        <VFlexPagination v-model:current-page="wrapperState.page"
                                            :item-per-page="wrapperState.limit" :total-items="wrapperState.total"
                                            :max-links-displayed="2" no-router>
                                            <!-- The controls can be updated anywhere in the slot -->
                                            <template #before-pagination>
                                                <VFlex class="mr-4">
                                                    <VField>
                                                        <VControl icon="feather:search">
                                                            <input v-model="wrapperState.searchInput" type="text"
                                                                class="input is-rounded" placeholder="Filter..." />
                                                        </VControl>
                                                    </VField>
                                                </VFlex>
                                            </template>

                                            <template #before-navigation>
                                                <VFlex class="mr-4">
                                                    <VField>
                                                        <VControl>
                                                            <div class="select is-rounded">
                                                                <select v-model="wrapperState.limit">
                                                                    <option :value="1">1 results per page</option>
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

                                        <VFlexTable rounded clickable @row-click="onRowClick">
                                            <template #body>
                                                <!--
            The wrapperState.loading will be update 
            when the fetchData function is running 
          -->
                                                <div v-if="wrapperState.loading" class="flex-list-inner">
                                                    <div v-for="key in wrapperState.limit" :key="key"
                                                        class="flex-table-item">
                                                        <VFlexTableCell :column="{ grow: true, media: true }">
                                                            <VPlaceloadAvatar size="medium" />

                                                            <VPlaceloadText :lines="2" width="60%" last-line-width="20%"
                                                                class="mx-2" />
                                                        </VFlexTableCell>
                                                        <VFlexTableCell>
                                                            <VPlaceload width="60%" class="mx-1" />
                                                        </VFlexTableCell>
                                                        <VFlexTableCell>
                                                            <VPlaceload width="60%" class="mx-1" />
                                                        </VFlexTableCell>
                                                        <VFlexTableCell :column="{ align: 'end' }">
                                                            <VPlaceload width="45%" class="mx-1" />
                                                        </VFlexTableCell>
                                                    </div>
                                                </div>

                                                <!-- This is the empty state -->
                                                <div v-else-if="wrapperState.data.length === 0" class="flex-list-inner">
                                                    <VPlaceholderSection title="No matches"
                                                        subtitle="There is no data that match your query." class="my-6">
                                                        <template #image>
                                                            <img class="light-image"
                                                                src="/@src/assets/illustrations/placeholders/search-4.svg"
                                                                alt="" />
                                                            <img class="dark-image"
                                                                src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                                alt="" />
                                                        </template>
                                                    </VPlaceholderSection>
                                                </div>
                                            </template>

                                            <!-- We can inject content before any rows -->
                                            <template #body-row-pre="{ row }">
                                                <template v-if="row.id === incomingCallerId">
                                                    <VProgress size="tiny" class="m-0 mb-1" />
                                                </template>
                                            </template>

                                            <!-- This is the body cell slot -->
                                            <template #body-cell="{ row, column }">
                                                <template v-if="column.key === 'name'">
                                                    <VAvatar size="medium" :picture="row.pic" :badge="row.badge"
                                                        :initials="row.initials" />
                                                    <div>
                                                        <span class="dark-text">{{ row.name }}</span>
                                                        <VTextEllipsis width="280px" class="light-text">
                                                            {{ row.bio }}
                                                        </VTextEllipsis>
                                                    </div>
                                                </template>

                                                <template v-if="column.key === 'actions'">
                                                    <VAction>
                                                        {{ row.id === openedRowId ? 'Hide details' : 'View details' }}
                                                    </VAction>
                                                </template>
                                            </template>

                                            <!-- We can also inject content after rows -->
                                            <template #body-row-post="{ row }">
                                                <template v-if="row.id === incomingCallerId">
                                                    <VTags class="mt-2 mb-0">
                                                        <VTag color="primary" outlined>
                                                            <i class="iconify is-inline mr-2"
                                                                data-icon="feather:send"></i>
                                                            Calling...
                                                        </VTag>
                                                    </VTags>
                                                </template>

                                                <template v-if="row.id === openedRowId">
                                                    <div class="is-block p-4 my-2 is-rounded">
                                                        <div class="dark-text mb-4 is-size-4">{{ row.name }}'s details
                                                        </div>
                                                        <VFlex justify-content="space-between">
                                                            <VFlexItem>
                                                                <VCard>
                                                                    <pre><code>{{ row }}</code></pre>
                                                                </VCard>
                                                            </VFlexItem>

                                                            <VFlexItem align-self="flex-end">
                                                                <VFlex flex-direction="column">
                                                                    <VButton v-if="row.id === incomingCallerId"
                                                                        class="mb-2" color="danger"
                                                                        @click="() => onCallClick(row)">
                                                                        <i class="iconify is-inline mr-2"
                                                                            data-icon="feather:phone-off"></i>
                                                                        Cancel call
                                                                    </VButton>
                                                                    <VButton color="primary" outlined
                                                                        :disabled="row.id === incomingCallerId"
                                                                        :loading="row.id === incomingCallerId"
                                                                        @click="() => onCallClick(row)">
                                                                        <i class="iconify is-inline mr-2"
                                                                            data-icon="feather:phone"></i>
                                                                        Call {{ row.name }}
                                                                    </VButton>
                                                                </VFlex>
                                                            </VFlexItem>
                                                        </VFlex>
                                                    </div>
                                                </template>
                                            </template>
                                        </VFlexTable>

                                        <!--Table Pagination-->
                                        <VFlexPagination v-model:current-page="wrapperState.page" class="mt-5"
                                            :item-per-page="wrapperState.limit" :total-items="wrapperState.total"
                                            :max-links-displayed="2" no-router />
                                    </template>
                                </VFlexTableWrapper>
                            </div>
                        </div>
                        <p v-else-if="activeValue === 'tab2'">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quid iudicant sensus?
                            Primum quid tu dicis breve? Etiam beatissimum? Ne discipulum abducam, times. Quae
                            diligentissime contra Aristonem dicuntur a Chryippo. Duo Reges: constructio
                            interrete.
                        </p>
                        <p v-else-if="activeValue === 'tab3'">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quid iudicant sensus?
                            Primum quid tu dicis breve? Etiam beatissimum? Ne discipulum abducam, times. Quae
                            diligentissime contra Aristonem dicuntur a Chryippo. Duo Reges: constructio
                            interrete.
                        </p>
                    </template>
                </VTabs>
            </div>

        </div>
    </VCard>

</template>

<style lang="scss">
</style>
