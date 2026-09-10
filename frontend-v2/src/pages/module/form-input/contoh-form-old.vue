
<template>
    <VCard radius="small">
        <div class="columns column">
            <h3 class="title is-5 mb-2 mr-1">Pasien </h3> <span> ( {{ ds_PASIEN.total }}
                Results)</span>
        </div>
        <div class="columns  all-projects m-3 mt-0">
            <div class="columns is-multiline  projects-card-grid">

                <div class="column is-9">
                    <a type="button" class="is-pulled-right mr-3" color="info" outlined raised><i
                            class="fa fa-plus"></i> Pasien
                        Baru </a>
                </div>
                <div class="column is-9">
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
                                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                            </VFlexTableCell>
                            <VFlexTableCell :column="{ align: 'end' }">
                                <VPlaceload width="10%" class="mx-1" />
                            </VFlexTableCell>
                        </div>
                    </div>
                    <div class="flex-list-inner" v-else-if="ds_PASIEN.length === 0">
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

                    <div class="grid-item mb-4" v-else-if="ds_PASIEN.length > 0" v-for="items in ds_PASIEN"
                        :key="items.id">

                        <div class="top-section">
                            <div class="head">
                                <div class="title-wrap">
                                    <div class="columns">
                                        <div class="column is-3">
                                            <VAvatar size="small" color="primary" :initials="items.initials" />
                                        </div>
                                        <div class="column is-12 mr-3">
                                            <h3>{{ items.namapasien }}</h3>
                                            <p>{{ items.nocm + (items.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <ProjectCardDropdown />
                            </div>
                            <div class="body">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="heading">Alamat</h4>
                                        <p class="fs-075">{{ items.alamatlengkap }}</p>
                                        <p class="fs-075">{{ items.nohp }}</p>
                                    </div>
                                    <div class="column">
                                        <h4 class="heading">No Identitas</h4>
                                        <p class="fs-075">NIK : {{ items.noidentitas }}</p>
                                        <p class="fs-075">No BPJS : {{ items.nobpjs }}</p>
                                    </div>
                                    <div class="column">
                                        <h4 class="heading">Lahir</h4>
                                        <p class="fs-075">Tempat : {{ items.tempatlahir }}</p>
                                        <p class="fs-075">Tgl : {{ items.tgllahir }}</p>
                                    </div>
                                    <div class="column">
                                        <h4 class="heading">Status</h4>
                                        <VTag :color="items.status_c" :label="items.status" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom-section">
                            <div class="foot-block">
                                <h4 class="heading">Action</h4>
                                <div class="developers">
                                    <VButton type="button" icon="feather:eye" class="is-fullwidth mr-3" color="success"
                                        outlined raised>
                                        Details </VButton>
                                    <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3" color="info"
                                        outlined raised>
                                        Edit </VButton>
                                    <VButton type="button" icon="feather:trash" class="is-fullwidth mr-3" color="danger"
                                        outlined raised>
                                        Delete </VButton>
                                    <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3"
                                        color="warning" outlined raised>
                                        Riwayat </VButton>
                                </div>
                            </div>
                        </div>

                    </div>
                    <VFlexPagination v-model:current-page="currentPageX" class="mt-6" :item-per-page="5"
                        :total-items="ds_PASIEN.total" :max-links-displayed="5" />

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
                                    <VInput type="text" v-model="item.qnik" v-on:keyup.enter="filter()"
                                        placeholder="NIK" />
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
                                class="is-fullwidth mr-3" color="info" raised> Apply Filters
                            </VButton>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="columns" style="display:none">

            <div class="column is-12">
                <VTabs slider type="rounded" selected="tab2" :tabs="[
                    { label: 'Data Table', value: 'tab1' },
                    { label: 'Form Input', value: 'tab2' },
                    { label: 'Tasks', value: 'tab3' },
                ]">
                    <template #tab="{ activeValue }">
                        <div v-if="activeValue === 'tab1'">
                            <div class="columns">
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

                                                                <VPlaceloadText :lines="2" width="60%"
                                                                    last-line-width="20%" class="mx-2" />
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
                                                    <div v-else-if="wrapperState.data.length === 0"
                                                        class="flex-list-inner">
                                                        <VPlaceholderSection title="No matches"
                                                            subtitle="There is no data that match your query."
                                                            class="my-6">
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
                                                    <template v-if="column.key === 'namapasien'">
                                                        <VAvatar size="medium" :picture="row.pic" :badge="row.badge"
                                                            :initials="row.initials" />
                                                        <div>
                                                            <span class="dark-text">{{ row.namapasien }}</span>
                                                            <VTextEllipsis width="280px" class="light-text">
                                                                {{ row.noidentitas }}
                                                            </VTextEllipsis>
                                                        </div>
                                                    </template>

                                                    <template v-if="column.key === 'actions'">
                                                        <VAction>
                                                            {{ row.id === openedRowId ? 'Hide details' : 'View details'
                                                            }}
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
                                                            <div class="dark-text mb-4 is-size-4">{{ row.namapasien }}'s
                                                                details</div>
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
                            <div class="columns">
                                <div class="column is-offset-8">
                                    <VButtons>
                                        <VButton icon="feather:eye"> View </VButton>
                                        <VButton icon="feather:edit-2"> View </VButton>
                                        <VButton color="primary" icon="fas fa-check" elevated> Approve </VButton>
                                    </VButtons>
                                </div>
                            </div>
                        </div>
                        <div class="columns" v-else-if="activeValue === 'tab2'">
                            <Transition name="translate-page-y" mode="in-out">
                                <div class="column is-12">
                                    <div class="form-layout is-separate">
                                        <div class="form-outer">
                                            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                                                <div class="form-header-inner">
                                                    <div class="left">
                                                        <h3>Pasien Baru</h3>
                                                    </div>
                                                    <div class="right">
                                                        <div class="buttons">
                                                            <!-- :to="{ name: 'sidebar-layouts-profile-view' }" -->
                                                            <VButton icon="lnir lnir-arrow-left rem-100"
                                                                @click="resetForm()" light dark-outlined>
                                                                Cancel
                                                            </VButton>
                                                            <VButton type="button" color="primary" raised
                                                                @click="savePasien()"> Save </VButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-body">

                                                <div class="form-section">
                                                    <div class="form-section-inner has-padding-bottom">
                                                        <h3 class="has-text-centered">Demografi</h3>
                                                        <div class="columns is-multiline">

                                                            <div class="column is-6">
                                                                <VField>
                                                                    <VLabel>NIK</VLabel>
                                                                    <VControl icon="feather:book">
                                                                        <VInput type="text" v-model="item.nik"
                                                                            placeholder="" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField>
                                                                    <VLabel>No BPJS</VLabel>
                                                                    <VControl icon="feather:book">
                                                                        <VInput type="text" v-model="item.nobpjs"
                                                                            placeholder="" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-12">
                                                                <VField>
                                                                    <VLabel>Nama Pasien</VLabel>
                                                                    <VControl icon="feather:user">
                                                                        <VInput type="text" v-model="item.namapasien"
                                                                            placeholder="" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField>
                                                                    <VLabel>Tempat Lahir</VLabel>
                                                                    <VControl icon="feather:map-pin">
                                                                        <VInput type="text" v-model="item.tempatlahir"
                                                                            placeholder="" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">

                                                                <VDatePicker v-model="item.tglahir" color="green"
                                                                    trim-weeks>
                                                                    <template #default="{ inputValue, inputEvents }">
                                                                        <VField>
                                                                            <VLabel>Tgl Lahir</VLabel>
                                                                            <VControl icon="feather:calendar">
                                                                                <VInput type="text"
                                                                                    placeholder="Select a date"
                                                                                    :value="inputValue"
                                                                                    v-on="inputEvents" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </template>
                                                                </VDatePicker>
                                                            </div>

                                                            <div class="column is-12">
                                                                <VField>
                                                                    <VLabel>Jenis Kelamin</VLabel>
                                                                    <VControl>
                                                                        <div class="columns">
                                                                            <div class="column is-12"
                                                                                v-if="listJK.length == 0">
                                                                                <VPlaceloadText :lines="1" />
                                                                            </div>
                                                                            <div class="column is-6"
                                                                                v-for="items in listJK" :key="items.id">
                                                                                <VRadio v-model="item.jenisKel"
                                                                                    :value="items.id"
                                                                                    :label="items.jeniskelamin"
                                                                                    name="{{items.id}}" square
                                                                                    color="primary" />
                                                                            </div>
                                                                        </div>

                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                                    <VLabel>Agama</VLabel>
                                                                    <VControl icon="feather:search">
                                                                        <Multiselect mode="single" v-model="item.agama"
                                                                            :options="listAgama"
                                                                            placeholder="Pilih data"
                                                                            :searchable="true" :attrs="{ id }" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                                    <VLabel>Status Perkawinan</VLabel>
                                                                    <VControl icon="feather:search">
                                                                        <Multiselect mode="single"
                                                                            v-model="item.statusPerkawinan"
                                                                            :options="listStatusPerkawinan"
                                                                            placeholder="Pilih data"
                                                                            :searchable="true" :attrs="{ id }" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                                    <VLabel>Golongan Darah</VLabel>
                                                                    <VControl icon="feather:search">
                                                                        <Multiselect mode="single" v-model="item.golDar"
                                                                            :options="listGolonganDarah"
                                                                            placeholder="Pilih data"
                                                                            :searchable="true" :attrs="{ id }" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                                    <VLabel>Pendidikan</VLabel>
                                                                    <VControl icon="feather:search">
                                                                        <Multiselect mode="single"
                                                                            v-model="item.pendidikan"
                                                                            :options="listPendidikan"
                                                                            placeholder="Pilih data"
                                                                            :searchable="true" :attrs="{ id }" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-section">
                                                    <div class="form-section-inner has-padding-bottom">
                                                        <h3 class="has-text-centered">Geografi</h3>
                                                        <div class="columns is-multiline">
                                                            <div class="column is-12">
                                                                <VField>
                                                                    <VLabel>Alamat Lengkap</VLabel>
                                                                    <VControl>
                                                                        <VTextarea v-model="item.alamat" rows="4"
                                                                            placeholder="Alamat Lengkap">
                                                                        </VTextarea>
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-12">
                                                                <VField>
                                                                    <VLabel>No HP</VLabel>
                                                                    <VControl icon="feather:phone">
                                                                        <VInput type="text" v-model="item.nohp"
                                                                            placeholder="" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-12">
                                                                <VField>
                                                                    <VLabel>Email </VLabel>
                                                                    <VControl icon="feather:mail">
                                                                        <VInput type="email" v-model="item.email"
                                                                            placeholder="" inputmode="email" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                                    <VLabel>Pekerjaan</VLabel>
                                                                    <VControl icon="feather:search">
                                                                        <Multiselect mode="single"
                                                                            v-model="item.pekerjaan"
                                                                            :options="listPekerjaan"
                                                                            placeholder="Pilih data"
                                                                            :searchable="true" :attrs="{ id }" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>

                                                            <div class="column is-6">
                                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                                    <VLabel>Etnis</VLabel>
                                                                    <VControl icon="feather:search">
                                                                        <Multiselect mode="single" v-model="item.suku"
                                                                            :options="listEtnis"
                                                                            placeholder="Pilih data"
                                                                            :searchable="true" :attrs="{ id }" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                                    <VLabel>Kebangsaan</VLabel>
                                                                    <VControl icon="feather:search">
                                                                        <Multiselect mode="single"
                                                                            v-model="item.kebangsaan"
                                                                            :options="listKebangsaan"
                                                                            placeholder="Pilih data"
                                                                            :searchable="true" :attrs="{ id }" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                                    <VLabel>Negara</VLabel>
                                                                    <VControl icon="feather:search">
                                                                        <Multiselect mode="single" v-model="item.negara"
                                                                            :options="listNegara"
                                                                            placeholder="Pilih data"
                                                                            :searchable="true" :attrs="{ id }" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="form-section">
                        <div class="form-section-inner">
                          <h3 class="has-text-centered">Delivery</h3>
                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <VField>
                                <VLabel>Delivery Fee</VLabel>
                                <VControl>
                                  <div class="radio-boxes">
                                    <VControl class="radio-box" subcontrol>
                                      <VInput type="radio" name="delivery_type" />
                                      <div class="radio-box-inner">
                                        <div class="fee">
                                          <span>0</span>
                                        </div>
                                        <p>3-4 weeks</p>
                                      </div>
                                    </VControl>
                                    <VControl class="radio-box">
                                      <VInput type="radio" name="delivery_type" checked />
                                      <div class="radio-box-inner">
                                        <div class="fee">
                                          <span>5</span>
                                        </div>
                                        <p>2-5 days</p>
                                      </div>
                                    </VControl>
                                  </div>

                                  <p>
                                    <span>Estimated delivery date: <span>Oct 23</span></span>
                                    <span>Each package has a tracking number</span>
                                  </p>
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>

                        <div class="form-section-outer">
                          <div class="checkboxes">
                            <VField>
                              <VFlex column-gap="1rem">
                                <VControl>
                                  <VCheckbox v-model="createAccount" label="Create an account" color="primary" circle />
                                </VControl>
                                <VControl subcontrol>
                                  <VCheckbox v-model="subscribe" label="Subscribe to our Newsletter" color="primary"
                                    circle />
                                </VControl>
                              </VFlex>
                            </VField>
                          </div>
                          <div class="button-wrap">
                            <VButton type="submit" color="primary" bold raised fullwidth>
                              Confirm My Order
                            </VButton>
                          </div>
                        </div>
                      </div> -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                        <div class="columns" v-else-if="activeValue === 'tab3'">
                            <div class="column is-12">

                            </div>
                        </div>
                    </template>
                </VTabs>
            </div>

        </div>
    </VCard>

</template>
<route lang="yaml">
meta:
  requiresAuth: true
</route>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { VFlexTableWrapperDataResolver } from '/@src/components/base/table/VFlexTableWrapper.vue'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
    title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const currentPageX = ref(1)
const total = ref(0)
const date = ref(new Date())
const item: any = {}
let listJK: any = ref([])
let listAgama: any = ref([])
let listGolonganDarah: any = ref([])
let listStatusPerkawinan: any = ref([])
let listPendidikan: any = ref([])
let listPekerjaan: any = ref([])
let listEtnis: any = ref([])
let listKebangsaan: any = ref([])
let listNegara: any = ref([])
let ds_PASIEN: any = ref([])

const route = useRoute()
const currentPage = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const columns = {
    namapasien: {
        label: 'Nama',
        media: true,
        grow: true,
        sortable: true,
    },
    alamatlengkap: {
        label: 'Alamat',
        sortable: true,
    },
    nohp: {
        label: 'No Telpon',
        sortable: true,
    },
    actions: {
        label: '',
        align: 'end',
    },
} as const

const queryParam = useQueryParam()
const openedRowId = ref<number>()
const incomingCallerId = ref<number>()

function useQueryParam() {
    const router = useRouter()
    const route = useRoute()

    // when the params match those value,
    // we don't set their value to the query params
    const defaultSearch = ''
    const defaultSort = ''
    const defaultLimit = 10
    const defaultPage = 1

    const searchTerm = computed({
        get: () => {
            let searchTermQuery: string

            // read "search" from the query params
            if (Array.isArray(route?.query?.search)) {
                searchTermQuery = route.query.search?.[0] ?? defaultSearch
            } else {
                searchTermQuery = route.query.search ?? defaultSearch
            }

            return searchTermQuery
        },
        set(value) {
            // update the route query params with our new "search" value.
            // we can use router.replace instead of router.push
            // to not write state to the browser history
            router.push({
                query: {
                    search: value === defaultSearch ? undefined : value,
                    sort: sort.value === defaultSort ? undefined : sort.value,
                    limit: limit.value === defaultLimit ? undefined : limit.value,
                    page: page.value === defaultPage ? undefined : page.value,
                },
            })
        },
    })

    const sort = computed({
        get: () => {
            let sortQuery: string

            // read "sort" from the query params
            if (Array.isArray(route?.query?.sort)) {
                sortQuery = route.query.sort?.[0] ?? defaultSort
            } else {
                sortQuery = route.query.sort ?? defaultSort
            }

            return sortQuery
        },
        set(value) {
            // update the route query params with our new "sort" value.
            // we can use router.replace instead of router.push
            // to not write state to the browser history
            router.push({
                query: {
                    search: searchTerm.value === defaultSearch ? undefined : searchTerm.value,
                    sort: value === defaultSort ? undefined : value,
                    limit: limit.value === defaultLimit ? undefined : limit.value,
                    page: page.value === defaultPage ? undefined : page.value,
                },
            })
        },
    })

    const limit = computed({
        get: () => {
            let limitQuery: number

            // read "limit" from the query params
            if (Array.isArray(route?.query?.limit)) {
                limitQuery = parseInt(route.query.limit[0] ?? `${defaultLimit}`)
            } else {
                limitQuery = parseInt(route.query.limit ?? `${defaultLimit}`)
            }

            if (limitQuery === NaN) {
                limitQuery = defaultLimit
            }

            return limitQuery
        },
        set(value) {
            // update the route query params with our new "limit" value.
            // we can use router.replace instead of router.push
            // to not write state to the browser history
            router.push({
                query: {
                    search: searchTerm.value === defaultSearch ? undefined : searchTerm.value,
                    sort: sort.value === defaultSort ? undefined : sort.value,
                    limit: value === defaultLimit ? undefined : value,
                    page: page.value === defaultPage ? undefined : page.value,
                },
            })
        },
    })

    const page = computed({
        get: () => {
            let pageQuery: number

            if (Array.isArray(route?.query?.page)) {
                pageQuery = parseInt(route.query.page[0] ?? `${defaultPage}`)
            } else {
                pageQuery = parseInt(route.query.page ?? `${defaultPage}`)
            }

            // read "page" from the query params
            if (pageQuery === NaN) {
                pageQuery = defaultPage
            }

            return pageQuery
        },
        set(value) {
            // update the route query params with our new "page" value.
            // we can use router.replace instead of router.push
            // to not write state to the browser history
            router.push({
                query: {
                    search: searchTerm.value === defaultSearch ? undefined : searchTerm.value,
                    sort: sort.value === defaultSort ? undefined : sort.value,
                    limit: limit.value === defaultLimit ? undefined : limit.value,
                    page: value === defaultPage ? undefined : value,
                },
            })
        },
    })

    return reactive({
        searchTerm,
        sort,
        limit,
        page,
    })
}
const fetchData: VFlexTableWrapperDataResolver = async ({
    searchTerm,
    start,
    limit,
    sort,
    controller,
}) => {
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
    const { data: pasien } = await useApi().get(
        `/registrasi/pasien-lama?_start=${start}&_limit=${limit}$&_total=true${searchQuery}${sortQuery}`)

    // wait more time
    // await sleep(1000)

    // our backend send us the count in the headers,
    // but we can also get it from another request

    total.value = pasien.total
    for (let x = 0; x < pasien.data.length; x++) {
        const element = pasien.data[x];
        element.initials = element.namapasien.substr(0, 1)
    }
    // the return of the function must be an array
    return pasien.data
}
async function fetchPasien() {
    ds_PASIEN.value.loading = true
    console.log(route.params)
    let searchQuery = `&q=`
    let start = 0
    let limit = 10
    let namapasien = ''
    let nocm = ''
    let nik = ''
    let nobpjs = ''
    let alamat = ''
    if (item.qnama) namapasien = `&namapasien=${item.qnama}`
    if (item.qnocm) nocm = `&nocm=${item.qnocm}`
    if (item.qnik) nik = `&nik=${item.qnik}`
    if (item.qbpjs) nobpjs = `&bpjs=${item.qbpjs}`
    if (item.qalamat) alamat = `&alamat=${item.qalamat}`
    const { data: pasien } = await useApi().get(`/registrasi/pasien-lama?_start=${start}&_limit=
  ${limit}&_total=true${searchQuery}${namapasien}${nocm}${nik}${nobpjs}${alamat}`)

    total.value = pasien.total
    for (let x = 0; x < pasien.data.length; x++) {
        const element = pasien.data[x];
        let ini = element.namapasien.split(' ')
        let init = element.namapasien.substr(0, 1)
        if (ini.length > 1) {
            init = init + ini[1].substr(0, 1)
        }
        element.initials = init
    }
    ds_PASIEN.value.loading = false
    ds_PASIEN.value = pasien.data
    ds_PASIEN.value.total = pasien.total
}
function onRowClick(row: any) {
    if (openedRowId.value === row.id) {
        openedRowId.value = undefined
    } else {
        openedRowId.value = row.id
    }
}
function onCallClick(row: any) {
    if (incomingCallerId.value === row.id) {
        incomingCallerId.value = undefined
    } else {
        incomingCallerId.value = row.id
    }
}

async function listDropdown() {
    const { data: response } = await useApi().get(
        `/registrasi/list-dropdown`)
    listJK.value = []
    for (let x = 0; x < response.jk.length; x++) {
        const element = response.jk[x];
        if (element.jeniskelamin != '-') {
            listJK.value.push(element)
        }
    }
    listAgama.value = response.agama.map((e): any => { return { label: e.agama, value: e.id } })
    listGolonganDarah.value = response.golongandarah.map((e): any => { return { label: e.golongandarah, value: e.id } })
    listStatusPerkawinan.value = response.statusperkawinan.map((e): any => { return { label: e.statusperkawinan, value: e.id } })
    listPendidikan.value = response.pendidikan.map((e): any => { return { label: e.pendidikan, value: e.id } })
    listPekerjaan.value = response.pekerjaan.map((e): any => { return { label: e.pekerjaan, value: e.id } })
    listEtnis.value = response.etnis.map((e): any => { return { label: e.suku, value: e.id } })
    listKebangsaan.value = response.kebangsaan.map((e): any => { return { label: e.name, value: e.id } })
    listNegara.value = response.negara.map((e): any => { return { label: e.namanegara, value: e.id } })

}
function savePasien() {
    if (!item.nik) {
        useToaster().error('NIK harus di isi')
        return
    }
    if (!item.nobpjs) {
        useToaster().error('No BPJS harus di isi')
        return
    }
    if (!item.namapasien) {
        useToaster().error('Nama harus di isi')
        return
    }
}
function resetForm() {

}
function clearFilter() {
    delete item.qnama
    delete item.qnocm
    delete item.qnik
    delete item.qbpjs
    delete item.qalamat
    fetchPasien()
}
function filter() {
    fetchPasien()
}
fetchPasien()
listDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.fs-075 {
    font-size: 0.9rem;
}

.page-content-wrapper {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
}

.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.form-layout {
    max-width: 740px;
    margin: 0 auto;

    &.is-separate {
        max-width: 1040px;

        .form-outer {
            background: none;
            border: none;

            .form-body {
                display: flex;

                .form-section {
                    flex-grow: 2;
                    padding: 10px;
                    width: 50%;

                    .form-section-inner {
                        @include vuero-s-card;

                        padding: 40px;

                        &.has-padding-bottom {
                            padding-bottom: 60px;
                            height: 100%;
                        }

                        >h3 {
                            font-family: var(--font-alt);
                            font-size: 1.2rem;
                            font-weight: 600;
                            color: var(--dark-text);
                            margin-bottom: 30px;
                        }

                        .columns {
                            .column {
                                padding-top: 0.25rem;
                                padding-bottom: 0.25rem;
                            }
                        }

                        .radio-boxes {
                            display: flex;
                            justify-content: space-between;
                            margin-left: -8px;
                            margin-right: -8px;

                            .radio-box {
                                position: relative;
                                width: calc(50% - 16px);
                                margin: 8px;

                                &:focus-within {
                                    border-radius: 3px;
                                    outline-offset: var(--accessibility-focus-outline-offset);
                                    outline-width: var(--accessibility-focus-outline-width);
                                    outline-style: var(--accessibility-focus-outline-style);
                                    outline-color: var(--primary);
                                }

                                input {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    height: 100%;
                                    width: 100%;
                                    opacity: 0;
                                    cursor: pointer;

                                    &:checked {
                                        +.radio-box-inner {
                                            background: var(--primary);
                                            border-color: var(--primary);
                                            box-shadow: var(--primary-box-shadow);

                                            .fee,
                                            p {
                                                color: var(--smoke-white);
                                            }
                                        }
                                    }
                                }

                                .radio-box-inner {
                                    background: var(--white);
                                    border: 1px solid var(--fade-grey-dark-3);
                                    text-align: center;
                                    border-radius: var(--radius);
                                    font-family: var(--font);
                                    font-weight: 600;
                                    font-size: 0.9rem;
                                    transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                        height 0.3s, width 0.3s;
                                    padding: 30px 20px;

                                    .fee {
                                        font-family: var(--font);
                                        font-weight: 700;
                                        color: var(--dark-text);
                                        font-size: 2.4rem;
                                        line-height: 1;

                                        span {
                                            &::after {
                                                content: '$';
                                                position: relative;
                                                top: -10px;
                                                font-size: 1.5rem;
                                            }
                                        }
                                    }

                                    p {
                                        font-family: var(--font-alt);
                                    }
                                }
                            }
                        }

                        .control {
                            >p {
                                padding-top: 12px;

                                >span {
                                    display: block;
                                    font-size: 0.9rem;

                                    span {
                                        font-weight: 500;
                                        color: var(--dark-text);
                                    }
                                }
                            }
                        }
                    }

                    .form-section-outer {
                        .checkboxes {
                            padding: 16px 0;

                            .checkbox {
                                padding: 0;
                                font-size: 0.9rem;
                            }
                        }

                        .button-wrap {
                            .button {
                                min-height: 60px;
                                font-size: 1.05rem;
                                font-weight: 600;
                                font-family: var(--font-alt);
                            }
                        }
                    }
                }
            }
        }
    }
}

.is-dark {
    .form-layout {
        &.is-separate {
            .form-outer {
                background: none !important;

                .form-body {
                    .form-section {
                        .form-section-inner {
                            @include vuero-card--dark;

                            >h3 {
                                color: var(--dark-dark-text);
                            }

                            .radio-boxes {
                                .radio-box {
                                    input:checked+.radio-box-inner {
                                        background: var(--primary);
                                        border-color: var(--primary);
                                        box-shadow: var(--primary-box-shadow);

                                        .fee,
                                        p {
                                            color: var(--smoke-white);
                                        }
                                    }

                                    .radio-box-inner {
                                        background: var(--dark-sidebar-light-2);
                                        border-color: var(--dark-sidebar-light-12);

                                        .fee {
                                            color: var(--dark-dark-text);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;
                    flex-direction: column;

                    .form-section {
                        width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;

                    // flex-direction: column;

                    .form-section {
                        // width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

.all-projects {
    .all-projects-header {
        display: flex;
        padding: 20px;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        margin-bottom: 1.5rem;

        .header-item {
            width: 25%;
            border-right: 1px solid var(--fade-grey-dark-3);

            &:last-child {
                border-right: none;
            }

            .item-inner {
                text-align: center;

                .lnil,
                .lnir {
                    font-size: 2.2rem;
                    margin-bottom: 6px;
                    color: var(--primary);
                }

                span {
                    display: block;
                    font-family: var(--font);
                    font-weight: 600;
                    font-size: 1.4rem;
                    color: var(--dark-text);
                }

                p {
                    font-family: var(--font-alt);
                }
            }
        }
    }

    .projects-card-grid {
        .grid-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
            padding: 20px;
            background: var(--white);
            border: 1px solid var(--fade-grey-dark-3);
            border-radius: var(--radius-large);

            .top-section {
                .head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;

                    h3 {
                        font-size: 1rem;
                        font-family: var(--font-alt);
                        color: var(--dark-text);
                        font-weight: 600;
                    }
                }

                .body {
                    p {
                        font-family: var(--font);
                        color: var(--light-text);
                    }
                }
            }

            .bottom-section {
                display: flex;

                .foot-block {
                    margin-right: 30px;

                    .heading {
                        font-family: var(--font-alt);
                        font-size: 0.75rem;
                        color: var(--light-text-dark-22);
                    }

                    >p {
                        padding-top: 5px;
                    }

                    .developers {
                        display: flex;

                        .v-avatar {
                            margin-right: 6px;
                        }
                    }
                }
            }
        }
    }
}

.heading {
    font-family: var(--font-alt);
    font-size: 0.75rem;
    color: var(--light-text-dark-22);
}

.is-dark {
    .all-projects {
        .all-projects-header {
            background: var(--dark-sidebar-light-6);
            border-color: var(--dark-sidebar-light-12);

            .header-item {
                border-color: var(--dark-sidebar-light-18);

                span {
                    color: var(--dark-dark-text);
                }

                i {
                    color: var(--primary) !important;
                }
            }
        }

        .projects-card-grid {
            .grid-item {
                background: var(--dark-sidebar-light-6);
                border-color: var(--dark-sidebar-light-12);

                .top-section {
                    .head {
                        h3 {
                            color: var(--dark-dark-text);
                        }
                    }
                }

                .bottom-section {
                    .foot-block {
                        .heading {
                            color: var(--light-text-dark-12);
                        }
                    }
                }
            }
        }
    }
}
</style>
