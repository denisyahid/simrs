<template>
    <section>
        <ConfirmDialog group="positionDialog"></ConfirmDialog>
        <div class="form-layout is-stacked">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Tindakan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton rounded icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton v-if="activeValue !== 2" type="button" rounded outlined color="primary" raised
                                    icon="feather:save" :disabled="disabledSave" :loading="isLoading"
                                    @click="activeValue === 3 ? simpanOperasi() : simpan()">
                                    Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard" v-if="!props.pasien">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoadingPasien">
                                <PlaceloadHeader class="m-3" />
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-12 ">
                            <div class="form-section pl-0 pl-3 pt-5 pr-3 pb-0 mb-0">
                                <div class="tabs-wrapper" :class="['tab-naver']">
                                    <div class="tabs-inner">
                                        <div class="tabs is-boxed">
                                            <ul>
                                                <li v-for="(tab, key) in tabs" :key="key"
                                                    :class="[activeValue === tab.value && 'is-active']">
                                                    <slot name="tab-link" :active-value="activeValue" :tab="tab"
                                                        :index="key" :toggle="toggle">
                                                        <a tabindex="0" @keydown.space.prevent="toggle(tab.value)"
                                                            @click="toggle(tab.value)">
                                                            <VIcon v-if="tab.icon" :icon="tab.icon" />
                                                            <span>
                                                                <slot name="tab-link-label" :active-value="activeValue"
                                                                    :tab="tab" :index="key">
                                                                    {{ tab.label }}
                                                                </slot>
                                                            </span>
                                                        </a>
                                                    </slot>
                                                </li>
                                                <li v-if="sliderClass" class="tab-naver"></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content is-active">
                                        <Transition :name="'fade-fast'" mode="out-in">
                                            <slot name="tab" :active-value="activeValue"></slot>
                                        </Transition>
                                    </div>
                                </div>
                                <div class="columns is-multiline">
                                    <div class="column is-12 ">
                                        <div class="columns is-multiline p-1" v-if="activeValue == 1">
                                            <div class="column is-7">
                                                <VField horizontal label="Registrasi"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                        <Dropdown v-model="item.pilihRegistrasi" :options="d_Registrasi"
                                                            :optionLabel="'label'" :optionValue="'value'"
                                                            class="is-rounded" placeholder="Pilih data"
                                                            style="width: 100%;" showClear :filter="true"
                                                            @change="getRegistrasi(item.pilihRegistrasi)" disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VButton type="button" color="info" raised rounded
                                                    icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                    @click="modalInput = true" :disabled="isClosedPasien">
                                                    Tambah
                                                </VButton>
                                            </div>
                                            <div class="column is-2">
                                                <VControl class="is-pulled-right">
                                                    <VSwitchBlock v-model="isPaket" label="Paket" color="primary"
                                                        class="is-pulled-right" />
                                                </VControl>
                                            </div>
                                            <div class="column is-7">
                                                <VField horizontal label="DPJP"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                        <Dropdown v-model="item.dataDokterSelect" :options="d_pegawai3"
                                                            :optionLabel="'label'" :optionValue="'value'"
                                                            class="is-rounded" placeholder="Pilih data"
                                                            style="width: 100%;" showClear :filter="true"
                                                            @complete="fetchAllDokter2($event)" />
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-12">
                                                <div class="timeline-wrapper">
                                                    <div class="timeline-header text-center">
                                                        <VTag color="danger" label="DATA BELUM TERSIMPAN" curved
                                                            class="mb-3" v-if="!isSavedTindakan" />
                                                    </div>
                                                    <div class="timeline-wrapper-inner pt-0">
                                                        <DataTable :value="dataResult" editMode="cell"
                                                            @cell-edit-complete="onCellEditComplete" :pt="{
                                                                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                                                                column: {
                                                                    bodycell: ({ state }) => ({
                                                                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                                                                    })
                                                                }
                                                            }" :loading="isLoading" scrollHeight="600px"
                                                            responsiveLayout="stack" breakpoint="960px"
                                                            sortMode="multiple"
                                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                                            showGridlines>
                                                            <template #empty>
                                                                <VPlaceholderPage :title="H.assets().notFound"
                                                                    :subtitle="H.assets().notFoundSubtitle" larger
                                                                    class="mt-2">
                                                                    <template #image>
                                                                        <img class="light-image"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <img class="dark-image"
                                                                            src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                                            alt="" />
                                                                    </template>
                                                                </VPlaceholderPage>
                                                            </template>
                                                            <template #loading>
                                                                <img src="/images/other/loadingspin.gif"
                                                                    alt="Loading..." width="100" />
                                                                <p style="color:white">Loading data, please wait...</p>
                                                            </template>
                                                            <Column header="#">
                                                                <template #body="slotProps">
                                                                    <VIconButton color="danger" light raised circle
                                                                        icon="lucide:x"
                                                                        @click="guessTindakan(slotProps, 'result')"
                                                                        :disabled="isClosedPasien" />
                                                                </template>
                                                            </Column>
                                                            <Column field="id" header="ID Jasa"></Column>
                                                            <Column field="namaproduk" header="Deskripsi"></Column>
                                                            <Column field="jumlah" header="Qty">
                                                                <template #body="{ data, field }">
                                                                    <InputText v-model="data[field]" type="text"
                                                                        @keypress="onlyNumber($event)"
                                                                        :disabled="isClosedPasien" />
                                                                </template>
                                                            </Column>
                                                            <!-- <Column field="iscito" header="Cito">
                                                                <template #body="slotProps">
                                                                    <VControl class="prime-auto">
                                                                        <VLabel raw class="remember-toggle">
                                                                            <VInput raw type="checkbox"
                                                                                v-model="slotProps.data.iscito"
                                                                                :checked="slotProps.data.iscito == 'true' || slotProps.data.iscito == true ? true : false"
                                                                                @change="citosss($event.target.checked, slotProps.data)"
                                                                                :disabled="isClosedPasien" />

                                                                            <span class="toggler">
                                                                                <span class="active">
                                                                                    <i aria-hidden="true"
                                                                                        class="iconify"
                                                                                        data-icon="feather:check"></i>
                                                                                </span>
                                                                                <span class="inactive">
                                                                                    <i aria-hidden="true"
                                                                                        class="iconify"
                                                                                        data-icon="feather:circle"></i>
                                                                                </span>
                                                                            </span>
                                                                        </VLabel>
                                                                    </VControl>
                                                                </template>
                                                            </Column> -->
                                                            <!-- <Column header="Cito">
                                                            <template #body="slotProps">
                                                                <Checkbox
                                                                v-model="slotProps.data.iscito"
                                                                :binary="true"
                                                                @change="handleChange(slotProps.data.namaproduk)"
                                                                :disabled="isClosedPasien"
                                                                />
                                                            </template>
                                                            </Column> -->
                                                            <Column field="hargasatuan" header="Tarif">
                                                                <template #body="slotProps">
                                                                    {{ H.formatRp(slotProps.data.hargasatuan, 'Rp. ') }}
                                                                </template>
                                                            </Column>
                                                            <Column field="pelaksana" header="Pelaksana"
                                                                style="width: 20%;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai4"
                                                                        @complete="fetchPegawai($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object
                                                                        @item-select="onPerawatSelected($event, data, field)" />
                                                                </template>
                                                            </Column>
                                                            <!-- <Column field="jenispelaksana" header="Jenis Pelaksana"
                                                                style="width: 20%;">
                                                                <template #body="{ data, field }">
                                                                    <VField label="" class="is-autocomplete-select">
                                                                        <VControl icon="feather:user">
                                                                            <Dropdown v-model="data[field]"
                                                                                :options="d_JenisPelaksana" filter
                                                                                optionLabel="label" style="width: 100%;"
                                                                                placeholder="Pilih Jenis Pelaksana">
                                                                            </Dropdown>
                                                                        </VControl>
                                                                    </VField>
                                                                </template>
                                                            </Column> -->
                                                            <Column field="tanggal" header="Tanggal" style="width: 15%">
                                                                <template #body="{ data, field }">
                                                                    <VControl class="prime-auto">
                                                                        <Calendar v-model="data[field]"
                                                                            selectionMode="single" dateFormat="dd-mm-yy"
                                                                            :manualInput="true" class="w-100"
                                                                            :showIcon="true"
                                                                            :disabled="isClosedPasien" />
                                                                    </VControl>
                                                                </template>
                                                            </Column>
                                                        </DataTable>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12 ">
                                        <div class="columns is-multiline p-1" v-if="activeValue == 2">
                                            <div class="column is-6">
                                                <VField>
                                                    <VControl icon="feather:search">
                                                        <input v-model="filters" type="text" class="input is-rounded"
                                                            placeholder="Filter " />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <h3 class="title is-5 mb-2 mr-1 is-pulled-right">TOTAL #{{
                                                    dataSourcefiltered.length }} - (Rp. {{ H.formatRp(item.TOTAL_BILL,
                                                        '') }})
                                                </h3>
                                            </div>
                                            <Divider type="dashed" />
                                            <div class="column is-12">
                                                <table class="tb-custom mt-3">
                                                    <thead>
                                                        <tr>

                                                            <th width="30%">URAIAN</th>
                                                            <th>HARGA SATUAN</th>
                                                            <th>JUMLAH</th>
                                                            <th>SUBTOTAL</th>
                                                            <th>OPSI</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="isLoadingBill">
                                                        <tr>
                                                            <td colspan="5">
                                                                <div class="list-view list-view-v1 is-fullwidth">
                                                                    <div class="list-view-inner">
                                                                        <div v-for="key in 6" :key="key"
                                                                            class="list-view-item mt-2">
                                                                            <VPlaceloadWrap>
                                                                                <VPlaceloadAvatar size="medium" />
                                                                                <VPlaceloadText last-line-width="60%"
                                                                                    class="mx-2" />
                                                                                <VPlaceload class="mx-2" disabled />
                                                                                <VPlaceload
                                                                                    class="mx-2 h-hidden-tablet-p" />
                                                                                <VPlaceload
                                                                                    class="mx-2 h-hidden-tablet-p" />
                                                                                <VPlaceload class="mx-2" />
                                                                            </VPlaceloadWrap>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <div
                                                        style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
                                                        <tbody v-if="!isLoadingBill"
                                                            v-for="(items, index) in dataSourcefiltered" :key="index">
                                                            <tr>
                                                                <td colspan="5" class="koneng">
                                                                    {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                                                                </td>
                                                            </tr>
                                                            <tr v-for="(itemsDet, index2) in items.details"
                                                                :key="index2">
                                                                <td width="30%">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">{{
                                                                                itemsDet.namaruangan
                                                                            }}</div>
                                                                            <div class="title-layan">
                                                                                {{ itemsDet.namaproduk }} -
                                                                                {{
                                                                                    itemsDet.dokterpemeriksa ?
                                                                                        itemsDet.dokterpemeriksa :
                                                                                        'Dokter belum di input'
                                                                                }}
                                                                            </div>
                                                                            <div>
                                                                                <VTag
                                                                                    :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                                                                    :label="itemsDet.tglpelayanan" />
                                                                            </div>
                                                                            <div class="title-kelas">{{
                                                                                itemsDet.namakelas
                                                                            }}
                                                                            </div>
                                                                            <div class="title-kelas">DPJP : {{
                                                                                itemsDet.dokterpemeriksa
                                                                            }}
                                                                            </div>
                                                                            <div class="title-kelas">Pemeriksa : {{
                                                                                itemsDet.pemeriksa
                                                                            }}
                                                                            </div>
                                                                            <div class="title-kelas"
                                                                                v-if="itemsDet.isasa1 != 0 && itemsDet.isasa1 != null">
                                                                                ASA : 1</div>
                                                                            <div class="title-kelas"
                                                                                v-if="itemsDet.isasa2 != 0 && itemsDet.isasa2 != null">
                                                                                ASA : 2</div>
                                                                            <div class="title-kelas"
                                                                                v-if="itemsDet.isasa3 != 0 && itemsDet.isasa3 != null">
                                                                                ASA : 3</div>
                                                                            <div class="title-kelas"
                                                                                v-if="itemsDet.isasa4 != 0 && itemsDet.isasa4 != null">
                                                                                ASA : 4</div>
                                                                            <div class="title-kelas"
                                                                                v-if="itemsDet.isasa0 != 0 && itemsDet.isasa4 != null">
                                                                                ASA : none</div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">Jasa : {{
                                                                                H.formatRp(itemsDet.jasa, 'Rp. ') }}
                                                                            </div>
                                                                            <div class="title-layan">{{
                                                                                H.formatRp(itemsDet.hargasatuan, 'Rp. ')
                                                                            }}
                                                                            </div>

                                                                            <div class="title-kelas">Diskon :
                                                                                {{
                                                                                    H.formatRp(itemsDet.hargadiscount,
                                                                                        'Rp.')
                                                                                }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">{{ itemsDet.jumlah }}</td>
                                                                <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ')
                                                                }}
                                                                </td>
                                                                <td class="center">

                                                                    <VIconButton color="danger" class="mr-2" light
                                                                        raised circle icon="feather:trash"
                                                                        @click="hapusTindakan(itemsDet)" />
                                                                    <VDropdown spaced dots right>
                                                                        <template #button="{ open, toggle }">
                                                                            <VIconButton icon="feather:more-vertical"
                                                                                class="is-trigger" @mouseenter="open"
                                                                                @focusin="open" light raised circle
                                                                                @click="toggle" color="info"
                                                                                v-bind:disabled="PASIEN_AKTIF == false ? true : false">
                                                                                Aksi
                                                                            </VIconButton>
                                                                        </template>
                                                                        <template #content>
                                                                            <a role="menuitem"
                                                                                class="dropdown-item is-media"
                                                                                @click="detailPetugas(itemsDet)">
                                                                                <div class="icon">
                                                                                    <i class="iconify lnir lnir-users"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Detail Pelaksana</span>
                                                                                    <span>View petugas tindakan </span>
                                                                                </div>
                                                                            </a>

                                                                            <a role="menuitem"
                                                                                class="dropdown-item is-media"
                                                                                @click="detailKomponen(itemsDet)">
                                                                                <div class="icon">
                                                                                    <i class="iconify fas fa-money-check"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Komponen Harga</span>
                                                                                    <span>view detail harga tindakan
                                                                                    </span>
                                                                                </div>
                                                                            </a>
                                                                        </template>
                                                                    </VDropdown>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <div class="search-results-wrapper"
                                                            v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                                                            <div class="search-results-body ">
                                                                <div class="page-placeholder">
                                                                    <div class="placeholder-content">
                                                                        <img class="light-image"
                                                                            style=" max-width: 340px;"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <img class="dark-image"
                                                                            style=" max-width: 340px;"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <h3>{{ H.assets().notFound }}</h3>
                                                                        <p class="is-larger">
                                                                            {{ H.assets().notFoundSubtitle }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12 ">
                                        <div class="columns is-multiline p-1" v-if="activeValue == 3">
                                            <div class="column is-7">
                                                <VField horizontal label="Registrasi"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                        <Dropdown v-model="item.pilihRegistrasi" :options="d_Registrasi"
                                                            :optionLabel="'label'" :optionValue="'value'"
                                                            class="is-rounded" placeholder="Pilih data"
                                                            style="width: 100%;" showClear :filter="true"
                                                            @change="getRegistrasi(item.pilihRegistrasi)" disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VButton type="button" color="info" raised rounded
                                                    icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                    @click="showModal()">
                                                    Tambah
                                                </VButton>
                                            </div>
                                            <div class="column is-2">
                                                <VControl class="is-pulled-left">
                                                    <VSwitchBlock v-model="isPaket" label="Paket" color="danger"
                                                        class="is-pulled-left" :disabled="isClosedPasien" />
                                                </VControl>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Dokter Anak"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:user" fullwidth>
                                                        <Dropdown v-model="item.dataDokteroperator"
                                                            :options="d_pegawai3" :optionLabel="'label'"
                                                            :optionValue="'value'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" @complete="fetchAllDokter2($event)" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Dokter Anestesi"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:user" fullwidth>
                                                        <Dropdown v-model="item.dataDokteroperator1"
                                                            :options="d_pegawai3" :optionLabel="'label'"
                                                            :optionValue="'value'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" @complete="fetchAllDokter2($event)" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Dokter Operator 1"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:user" fullwidth>
                                                        <Dropdown v-model="item.dataDokteroperator2"
                                                            :options="d_pegawai3" :optionLabel="'label'"
                                                            :optionValue="'value'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" @complete="fetchAllDokter2($event)" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Dokter Asisten"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:user" fullwidth>
                                                        <Dropdown v-model="item.dataDokteroperator3"
                                                            :options="d_pegawai3" :optionLabel="'label'"
                                                            :optionValue="'value'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" @complete="fetchAllDokter2($event)" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Asisten Bedah"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl class="prime-auto is-rounded" icon="feather:user"
                                                        style="width: 100%;">
                                                        <AutoComplete v-model="item.dataDokteroperator4"
                                                            :suggestions="d_pegawai4" @complete="fetchPegawai($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" placeholder="Pilih Data" />
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-4">
                                                <VField label="Perawat Instrumen"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl class="prime-auto is-rounded" icon="feather:user"
                                                        style="width: 100%;">
                                                        <AutoComplete v-model="item.perawatanastesi"
                                                            :suggestions="d_pegawai4" @complete="fetchPegawai($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" placeholder="Pilih Data" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Perawat Sirkuler"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl class="prime-auto is-rounded" icon="feather:user"
                                                        style="width: 100%;">
                                                        <AutoComplete v-model="item.penataanastesi"
                                                            :suggestions="d_pegawai4" @complete="fetchPegawai($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" placeholder="Pilih Data" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField label="Penata Anastesi - Perawat Anastesi"
                                                    class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl class="prime-auto is-rounded" icon="feather:user"
                                                        style="width: 100%;">
                                                        <AutoComplete v-model="item.penataanastesiperawat"
                                                            :suggestions="d_pegawai4" @complete="fetchPegawai($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" placeholder="Pilih Data" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <div class="columns">
                                                    <div class="column">
                                                        <VField>
                                                            <VControl>
                                                                <VSwitchBlock v-model="item.isasa1" label="ASA 1"
                                                                    color="danger"
                                                                    @update:modelValue="onSwitchChange('isasa1', $event)" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl>
                                                                <VSwitchBlock v-model="item.isasa2" label="ASA 2"
                                                                    color="warning"
                                                                    @update:modelValue="onSwitchChange('isasa2', $event)" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl>
                                                                <VSwitchBlock v-model="item.isasa3" label="ASA 3"
                                                                    color="success"
                                                                    @update:modelValue="onSwitchChange('isasa3', $event)" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl>
                                                                <VSwitchBlock v-model="item.isasa4" label="ASA 4"
                                                                    color="info"
                                                                    @update:modelValue="onSwitchChange('isasa4', $event)" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column">
                                                        <VField>
                                                            <VControl>
                                                                <VSwitchBlock v-model="item.isasa0" label="NONE ASA "
                                                                    color="pink"
                                                                    @update:modelValue="onSwitchChange('isasa0', $event)" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="column is-12">
                                                <div class="timeline-wrapper" v-if="dataResult.length > 0">
                                                    <div class="timeline-header"></div>
                                                    <div class="timeline-wrapper-inner pt-0"
                                                        style="width:100%;overflow-x: auto;">
                                                        <DataTable :value="dataResult" editMode="cell"
                                                            @cell-edit-complete="onCellEditComplete" :pt="{
                                                                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                                                                column: {
                                                                    bodycell: ({ state }) => ({
                                                                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                                                                    })
                                                                }
                                                            }" scrollHeight="600px" responsiveLayout="stack"
                                                            breakpoint="960px" sortMode="multiple"
                                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                                            showGridlines>
                                                            <Column header="#">
                                                                <template #body="slotProps">
                                                                    <VIconButton color="danger" light raised circle
                                                                        icon="lucide:x"
                                                                        @click="onTindakanUnselected(slotProps, 'result')" />
                                                                </template>
                                                            </Column>
                                                            <Column field="id" header="ID Jasa"></Column>
                                                            <Column field="namaproduk" header="Deskripsi"
                                                                style="min-width: 200px;"></Column>
                                                            <Column field="jumlah" header="Qty"
                                                                style="min-width: 50px;">
                                                                <template #body="{ data, field }">
                                                                    <InputText v-model="data[field]"
                                                                        @keypress="onlyNumber($event)" />
                                                                </template>
                                                            </Column>
                                                            <!-- <Column field="iscito" header="Cito">
                                                                <template #body="slotProps">
                                                                    <VControl class="prime-auto">
                                                                        <VLabel raw class="remember-toggle">
                                                                            <VInput raw type="checkbox"
                                                                                v-model="slotProps.data.iscito"
                                                                                :checked="slotProps.data.iscito == 'true' || slotProps.data.iscito == true ? true : false"
                                                                                @change="citosss($event.target.checked, slotProps.data)"
                                                                                :disabled="isClosedPasien" />

                                                                            <span class="toggler">
                                                                                <span class="active">
                                                                                    <i aria-hidden="true"
                                                                                        class="iconify"
                                                                                        data-icon="feather:check"></i>
                                                                                </span>
                                                                                <span class="inactive">
                                                                                    <i aria-hidden="true"
                                                                                        class="iconify"
                                                                                        data-icon="feather:circle"></i>
                                                                                </span>
                                                                            </span>
                                                                        </VLabel>
                                                                    </VControl>
                                                                </template>
                                                            </Column> -->
                                                            <!-- <Column header="Cito">
                                                            <template #body="slotProps">
                                                                <Checkbox
                                                                v-model="slotProps.data.iscito"
                                                                :binary="true"
                                                                @change="handleChange(slotProps.data.namaproduk)"
                                                                :disabled="isClosedPasien"
                                                                />
                                                            </template>
                                                            </Column> -->
                                                            <Column field="hargasatuan" header="Tarif">
                                                                <template #body="slotProps">
                                                                    {{ H.formatRp(slotProps.data.hargasatuan, 'Rp. ') }}
                                                                </template>
                                                            </Column>
                                                            <Column field="dataDokteroperator" header="Dokter Operator"
                                                                style="min-width: 200px;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai3"
                                                                        @complete="fetchAllDokter2($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object
                                                                        @item-select="onPerawatSelected($event, data, field)" />
                                                                </template>
                                                            </Column>
                                                            <Column field="dataDokteroperator1" header="Dokter Anastesi"
                                                                style="min-width: 200px;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai3"
                                                                        @complete="fetchAllDokter2($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object />
                                                                </template>
                                                            </Column>
                                                            <Column field="dataDokteroperator2"
                                                                header="Dokter Operator 1" style="min-width: 200px;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai3"
                                                                        @complete="fetchAllDokter2($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object />
                                                                </template>
                                                            </Column>
                                                            <Column field="dataDokteroperator3" header="Dokter Asisten"
                                                                style="min-width: 200px;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai3"
                                                                        @complete="fetchAllDokter2($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object />
                                                                </template>
                                                            </Column>
                                                            <Column field="dataDokteroperator4" header="Asisten Bedah"
                                                                style="min-width: 200px;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai4"
                                                                        @complete="fetchPegawai($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object />
                                                                </template>
                                                            </Column>
                                                            <Column field="dataPerawatanastesi"
                                                                header="Perawat Instrumen" style="min-width: 200px;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai4"
                                                                        @complete="fetchPegawai($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object />
                                                                </template>
                                                            </Column>
                                                            <Column field="dataPenataanastesi" header="Perawat Sirkuler"
                                                                style="min-width: 200px;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai4"
                                                                        @complete="fetchPegawai($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object />
                                                                </template>
                                                            </Column>
                                                            <Column field="dataPenataanastesiperawat"
                                                                header="Penata Anastesi - Perawat Anastesi"
                                                                style="min-width: 200px;">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]"
                                                                        :suggestions="d_pegawai4"
                                                                        @complete="fetchPegawai($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :appendTo="'body'" :minLength="3"
                                                                        :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                        item-text="label" item-value="value"
                                                                        return-object />
                                                                </template>
                                                            </Column>
                                                            <Column field="asa" header="ASA" style="min-width: 150px;">
                                                                <template #body="{ data, field }">
                                                                    <Multiselect v-model="data[field]"
                                                                        :attrs="{ value }" placeholder="--Pilih--"
                                                                        label="label" :options="d_asa"
                                                                        :searchable="true" track-by="label"
                                                                        mode="single" autocomplete="off">
                                                                    </Multiselect>
                                                                </template>
                                                            </Column>
                                                            <Column field="tanggal" header="Tanggal"
                                                                style="min-width: 150px;">
                                                                <template #body="{ data, field }">
                                                                    <VControl class="prime-auto">
                                                                        <Calendar v-model="data[field]"
                                                                            selectionMode="single" dateFormat="dd-mm-yy"
                                                                            :manualInput="true" class="w-100"
                                                                            :showIcon="true" />
                                                                    </VControl>
                                                                </template>
                                                            </Column>
                                                        </DataTable>
                                                    </div>
                                                </div>

                                                <VCard radius="rounded" class="mt-2" v-if="dataResult.length === 0">
                                                    <VPlaceholderPage :title="H.assets().notFound"
                                                        :subtitle="H.assets().notFoundSubtitle" larger>
                                                        <template #image>
                                                            <img class="light-image" :src="H.assets().iconNotFound_rev"
                                                                alt="" />
                                                            <img class="dark-image"
                                                                src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                                alt="" />
                                                        </template>
                                                    </VPlaceholderPage>
                                                </VCard>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12 ">
                                        <div class="columns is-multiline p-1" v-if="activeValue == 4">
                                            <div class="column is-6">
                                                <VField>
                                                    <VControl icon="feather:search">
                                                        <input v-model="filters" type="text" class="input is-rounded"
                                                            placeholder="Filter " />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <h3 class="title is-5 mb-2 mr-1 is-pulled-right">TOTAL #{{
                                                    dataSourcefiltered.length }} - (Rp. {{ H.formatRp(item.TOTAL_BILL,
                                                        '') }})
                                                </h3>
                                            </div>
                                            <Divider type="dashed" />
                                            <div class="column is-12">
                                                <table class="tb-custom mt-3">
                                                    <thead>
                                                        <tr>

                                                            <th width="30%">URAIAN</th>
                                                            <th>HARGA SATUAN</th>
                                                            <th>JUMLAH</th>
                                                            <th>SUBTOTAL</th>
                                                            <th>OPSI</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="isLoadingBill">
                                                        <tr>
                                                            <td colspan="5">
                                                                <div class="list-view list-view-v1 is-fullwidth">
                                                                    <div class="list-view-inner">
                                                                        <div v-for="key in 6" :key="key"
                                                                            class="list-view-item mt-2">
                                                                            <VPlaceloadWrap>
                                                                                <VPlaceloadAvatar size="medium" />
                                                                                <VPlaceloadText last-line-width="60%"
                                                                                    class="mx-2" />
                                                                                <VPlaceload class="mx-2" disabled />
                                                                                <VPlaceload
                                                                                    class="mx-2 h-hidden-tablet-p" />
                                                                                <VPlaceload
                                                                                    class="mx-2 h-hidden-tablet-p" />
                                                                                <VPlaceload class="mx-2" />
                                                                            </VPlaceloadWrap>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <div
                                                        style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
                                                        <tbody v-if="!isLoadingBill"
                                                            v-for="(items, index) in dataSourcefiltered" :key="index">
                                                            <tr>
                                                                <td colspan="5" class="koneng">
                                                                    {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                                                                </td>
                                                            </tr>
                                                            <tr v-for="(itemsDet, index2) in items.details"
                                                                :key="index2">
                                                                <td width="30%">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">{{
                                                                                itemsDet.namaruangan
                                                                            }}</div>
                                                                            <div class="title-layan">
                                                                                {{ itemsDet.namaproduk }} -
                                                                                {{ itemsDet.dokterpemeriksa ?
                                                                                    itemsDet.dokterpemeriksa
                                                                                    : 'Dokter belum di input' }}
                                                                            </div>
                                                                            <div>
                                                                                <VTag
                                                                                    :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                                                                    :label="itemsDet.tglpelayanan" />
                                                                            </div>
                                                                            <div class="title-kelas">{{
                                                                                itemsDet.namakelas
                                                                            }}
                                                                            </div>
                                                                            <div class="title-kelas">DPJP : {{
                                                                                itemsDet.dokterpemeriksa
                                                                            }}
                                                                            </div>
                                                                            <div class="title-kelas">Pemeriksa : {{
                                                                                itemsDet.pemeriksa
                                                                            }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">Jasa : {{
                                                                                H.formatRp(itemsDet.jasa, 'Rp. ') }}
                                                                            </div>
                                                                            <div class="title-layan">{{
                                                                                H.formatRp(itemsDet.hargasatuan, 'Rp. ')
                                                                            }}
                                                                            </div>

                                                                            <div class="title-kelas">Diskon :
                                                                                {{
                                                                                    H.formatRp(itemsDet.hargadiscount,
                                                                                        'Rp.')
                                                                                }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">{{ itemsDet.jumlah }}</td>
                                                                <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ')
                                                                }}
                                                                </td>
                                                                <td class="center">
                                                                    <VDropdown spaced dots right>
                                                                        <template #button="{ open, toggle }">
                                                                            <VIconButton icon="feather:more-vertical"
                                                                                class="is-trigger" @mouseenter="open"
                                                                                @focusin="open" light raised circle
                                                                                @click="toggle" color="info"
                                                                                v-bind:disabled="PASIEN_AKTIF == false ? true : false">
                                                                                Aksi
                                                                            </VIconButton>
                                                                        </template>
                                                                        <template #content>
                                                                            <a role="menuitem"
                                                                                class="dropdown-item is-media"
                                                                                @click="detailPetugas(itemsDet)">
                                                                                <div class="icon">
                                                                                    <i class="iconify lnir lnir-users"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Detail Pelaksana</span>
                                                                                    <span>View petugas tindakan </span>
                                                                                </div>
                                                                            </a>

                                                                            <a role="menuitem"
                                                                                class="dropdown-item is-media"
                                                                                @click="detailKomponen(itemsDet)">
                                                                                <div class="icon">
                                                                                    <i class="iconify fas fa-money-check"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Komponen Harga</span>
                                                                                    <span>view detail harga tindakan
                                                                                    </span>
                                                                                </div>
                                                                            </a>
                                                                        </template>
                                                                    </VDropdown>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <div class="search-results-wrapper"
                                                            v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                                                            <div class="search-results-body ">
                                                                <div class="page-placeholder">
                                                                    <div class="placeholder-content">
                                                                        <img class="light-image"
                                                                            style=" max-width: 340px;"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <img class="dark-image"
                                                                            style=" max-width: 340px;"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <h3>{{ H.assets().notFound }}</h3>
                                                                        <p class="is-larger">
                                                                            {{ H.assets().notFoundSubtitle }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Dialog v-model:visible="modalInput" modal header="Tindakan" :style="{ width: '85rem' }"
                :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <VCard>
                            <DataTable v-model:selection="selectedProduct" v-model:filters="filtersTindakan"
                                :loading="isLoadingTindakan" :rows="10" paginator :value="products"
                                selectionMode="multiple" :metaKeySelection="metaKey" dataKey="id"
                                @rowSelect="onTindakanSelected" @rowUnselect="onTindakanUnselected"
                                :globalFilterFields="['namaproduk', 'id']" tableStyle="min-width: 50rem">
                                <template #header>
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField>
                                                <InputText v-model="filtersTindakan['global'].value"
                                                    placeholder="Search Data" />
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VSwitchBlock v-model="isAllTindakan"
                                                        @change.stop="isAllTindakanChange(isAllTindakan)"
                                                        color="success" label="Semua Tindakan" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </template>
                                <template #empty> No customers found. </template>
                                <template #loading>
                                    <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                                    <p style="color:white">Loading data, please wait...</p>
                                </template>
                                <Column field="id" header="ID"></Column>
                                <Column field="namaproduk" header="Nama"></Column>
                            </DataTable>
                        </VCard>
                    </div>
                    <div class="column is-6">
                        <span style="font-weight: bold">List Tindakan Dipilih</span>
                        <VButton type="button" rounded color="primary" raised icon="feather:save"
                            class="is-pulled-right" :loading="isLoading" @click="newSimpan()"> Tambah
                        </VButton>
                        <div class="timeline-wrapper" v-if="dataSelectedTindakan.length > 0" style="margin-top: 20px;">
                            <div class="timeline-header"></div>
                            <div class="timeline-wrapper-inner pt-0">
                                <div class="timeline-container">
                                    <div class="timeline-item is-unread">
                                        <VCard radius="rounded" class="p-3">
                                            <div v-for="(items, index) in dataSelectedTindakan" :key="items.id">
                                                <div class="is-flex column p-1" style="vertical-align: center;">
                                                    <span class="ml-5">{{ items.namaproduk }}</span>
                                                    <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash"
                                                        @click="hapussskii(items.id)" color="danger" raised circle
                                                        class="ml-auto" />
                                                </div>
                                            </div>

                                            <VPlaceloadText v-if="isLoadingTindakan" :lines="1" width="75%"
                                                last-line-width="25%" />
                                        </VCard>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <VCard radius="rounded" class="mt-2"
                            v-else-if="dataSelectedTindakan.length == 0 && isLoadingTindakan">
                            <VPlaceloadText :lines="5" width="75%" last-line-width="25%" />
                        </VCard>

                        <VCard radius="rounded" class="mt-2" v-else>
                            <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                larger>
                                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                    alt="" />
                            </VPlaceholderPage>
                        </VCard>
                    </div>
                </div>
            </Dialog>

            <VModal :open="modalPetugas" title="Detail Petugas Tindakan" :noclose="false" size="medium" actions="right"
                @close="modalPetugas = false">
                <template #content>
                    <form class="modal-form custom-mod ">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VCard>
                                    <div class="columns is-multiline p-1">
                                        <div class="column is-6">
                                            <p class="block-text">Tanggal Pelayanan</p>
                                            <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>

                                        </div>
                                        <div class="column is-6">
                                            <p class="block-text">Nama Pelayanan</p>
                                            <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>

                                        </div>
                                        <div class="column is-12">
                                            <VCard class="is-grey">
                                                <div class="columns is-multiline p-1">
                                                    <div class="column is-12 mb--15 text-center"
                                                        v-if="dataSourcePetugas.length == 0">
                                                        <img class="light-image" style=" max-width: 180px;"
                                                            :src="H.assets().iconNotFoundCalendar" alt="" />
                                                        <h3>{{ H.assets().notFound }}</h3>
                                                    </div>
                                                    <div class="column is-12 mb--15" v-else v-for="(item, index) in dataSourcePetugas" :key="index">
                                                        <div class="columns is-multiline p-0">
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/icons/files/dokter.svg'"
                                                                        alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.namalengkap }} </span>
                                                                        <span>
                                                                            <b>{{ item.jenispetugaspe }}</b>
                                                                        </span>
                                                                    </div>
                                                                    <div
                                                                        class="is-right is-dots is-spaced dropdown end-action">
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-check-circle  is-pulled-right"
                                                                            style="color:var(--success)"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </div>
                                    </div>
                                </VCard>
                            </div>

                        </div>
                    </form>
                </template>

            </VModal>
            <VModal :open="modalKomponen" title="Komponen Harga" :noclose="false" size="medium" actions="right"
                @close="modalKomponen = false">
                <template #content>
                    <form class="modal-form custom-mod ">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VCard>
                                    <div class="columns is-multiline p-1">
                                        <div class="column is-6">
                                            <p class="block-text">Tgl Pelayanan</p>
                                            <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>
                                        </div>
                                        <div class="column is-6">
                                            <p class="block-text">Nama Pelayanan</p>
                                            <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>
                                        </div>
                                        <div class="column is-12">
                                            <VCard class="is-grey">
                                                <div class="columns is-multiline p-1">
                                                    <div class="column is-12 mb--15 text-center"
                                                        v-if="dataSourceKom.length == 0">
                                                        <img class="light-image" style=" max-width: 180px;"
                                                            :src="H.assets().iconNotFoundList" alt="" />
                                                        <h3>{{ H.assets().notFound }}</h3>
                                                    </div>
                                                    <div class="column is-12 mb--15" v-else
                                                        v-for="(item, index) in dataSourceKom" :key="index">
                                                        <div class="columns is-multiline p-0">
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/simrs/bill-list.png'" alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.komponenharga }} </span>
                                                                        <table style="width: 100%;">
                                                                            <tr>
                                                                                <th class="tb-th">Harga</th>
                                                                                <th class="tb-th">Jumlah</th>
                                                                                <th class="tb-th">Diskon</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="tb-th text-center"> {{
                                                                                    H.formatRp(item.hargasatuan, '') }}
                                                                                </td>
                                                                                <td class="tb-th text-center"> {{
                                                                                    item.jumlah }}</td>
                                                                                <td class="tb-th text-center"> {{
                                                                                    H.formatRp(item.hargadiscount, '')
                                                                                }}
                                                                                </td>
                                                                            </tr>
                                                                        </table>


                                                                    </div>
                                                                    <div
                                                                        class="is-right is-dots is-spaced dropdown end-action">
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-check-circle  is-pulled-right"
                                                                            style="color:var(--success)"></i>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 " v-if="dataSourceKom.length != 0">
                                                        <VField class="is-pulled-right">
                                                            <VLabel class="fs-total">{{
                                                                H.formatRp(item.totalKomponen,
                                                                    'Rp.')
                                                            }} </VLabel>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </div>
                                        <div class="column is-6" v-if="item.norecPPD">
                                            <h4 class="block-heading">Nama Komponen </h4>
                                            <p class="block-text"> {{ item.namaKomponen }}
                                            </p>
                                        </div>
                                        <div class="column is-6" v-if="item.norecPPD">
                                            <h4 class="block-heading">Harga </h4>
                                            <p class="block-text">{{
                                                H.formatRp(item.hargasatuankom,
                                                    'Rp.')
                                            }}
                                            </p>
                                        </div>
                                        <div class="column is-6" v-if="item.norecPPD">
                                            <VField>
                                                <VLabel style="color: black !important ;font-size: 100%;">Persen Diskon
                                                </VLabel>
                                                <VControl icon="fas fa-calculator">

                                                    <VInput type="number" v-model="item.persenDiskon"
                                                        placeholder="Diskon" class="is-rounded" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6" v-if="item.norecPPD">
                                            <h4 class="block-heading">Total Diskon</h4>
                                            <p class="block-text">{{
                                                H.formatRp(item.diskonKomponen,
                                                    'Rp.')
                                            }}
                                            </p>
                                        </div>
                                    </div>
                                </VCard>
                            </div>

                        </div>
                    </form>
                </template>

            </VModal>
            <Dialog v-model:visible="modalPaket" modal header="Paket" :style="{ width: '60vw' }">
                <div class="columns is-multiline">

                    <div class="column is-3">
                        <VField label="Tanggal">
                            <VDatePicker v-model="item2.tglpelayanan" mode="dateTime" style="width: 100%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" class="is-rounded"
                                                v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="feather:plus-circle" fullwidth>
                                <Multiselect mode="single" v-model="item2.jenisPelaksana" :options="d_JenisPelaksana"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    @select="changeJenis(item2)" track-by="value" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select" v-slot="{ id }">
                            <VLabel>Pegawai</VLabel>
                            <VControl fullwidth :loading="isLoadChange">
                                <Multiselect mode="single" laceholder="Pilih data" v-model="item2.pegawai"
                                    :options="d_Pegawai2" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    appendTo="body" track-by="value" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VCard>
                            <DataTable :value="dataSourcePaket" v-model:expandedRows="expandedRows" :paginator="true"
                                :rows="10" :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers"
                                filterDisplay="menu" v-model:filters="filterPaket"
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                :globalFilterFields="['namapaket']" :scrollable="true"
                                :loading="dataSourcePaket.loading" dataKey="id">
                                <template #header>
                                    <div class="flex justify-content-end">
                                        <span class="p-input-icon-left">
                                            <i class="pi pi-search" />
                                            <InputText v-model="filterPaket.global.value"
                                                placeholder="Keyword Search" />
                                        </span>
                                    </div>
                                </template>
                                <Column :expander="true" :style="{ width: '50px' }" />
                                <Column :exportable="false" header="#" :style="{ width: '50px' }">
                                    <template #body="slotProps">
                                        <VIconButton type="button" icon="pi pi-plus" class="mr-2" color="info" circle
                                            outlined raised v-tooltip.top="'Tambah'"
                                            @click="tambahPaket(slotProps.data)" :loading="slotProps.data.isLoading">
                                        </VIconButton>
                                    </template>
                                </Column>

                                <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
                                <Column field="namapaket" header="Nama Paket" style="width:250px" :sortable="true">
                                </Column>
                                <Column field="jml" header="Jumlah Pelayanan" style="width:100px"></Column>
                                <Column field="hargapaket" header="Harga Paket" style="width:100px">
                                    <template #body="slotProps">
                                        {{ H.formatRp(slotProps.data.hargapaket, '') }}
                                    </template>
                                </Column>
                                <template #expansion="slotProps">
                                    <div class="orders-subtable">
                                        <h5>Paket : {{ slotProps.data.namapaket }}</h5>
                                        <DataTable :value="slotProps.data.details" responsiveLayout="scroll">
                                            <Column field="namaproduk" header="Pelayanan" :sortable="true"> </Column>
                                        </DataTable>
                                    </div>
                                </template>
                            </DataTable>
                        </VCard>
                    </div>
                </div>
            </Dialog>
            <Dialog v-model:visible="modalRadiologiSendRIS" modal
                :header="modalLis ? 'Form Verifikasi Laboratorium' : 'Form Verifikasi Radiologi'"
                :style="{ width: '60vw' }">
                <div class="space-y-6" v-if="modalLis != true">
                    <div v-for="(items, index) in dataResult" :key="items.norec"
                        class="flex items-center bg-white rounded-lg shadow-sm p-4 space-x-6">
                        <VIconBox size="large" :color="listColor[index + 1]" rounded>
                            <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                        </VIconBox>
                        <div class="flex-grow" style="margin-left:5rem">
                            <p class="text-gray-800 font-medium text-lg">{{ items.namaproduk }}</p>
                        </div>
                        <div class="w-1/3" style="margin-left:10rem">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Dokter Verifikator</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <AutoComplete v-model="items.dokterVerifRad" :suggestions="d_DokterVerifkator"
                                        @complete="getListDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Ketik Nama Dokter" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Petugas Verifikator / Admin</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.petugasverify" :options="d_DokterVerif" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Radiografer</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.radiografer" :options="d_DokterVerif" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <VField>
                            <VLabel class="required-field">Catatan Klinis</VLabel>
                            <VControl class="mt-3">
                                <VTextarea rows="4" placeholder="Tulis Catatan Klinis..."
                                    v-model="item.catatanklinis" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div v-else>
                    <div class="w-1/3">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Dokter Verifikator</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <AutoComplete v-model="item.dokterVerifLab" :suggestions="d_Dokter_Lab_Verif"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <VField>
                            <VLabel class="required-field">Catatan Klinis</VLabel>
                            <VControl class="mt-3">
                                <VTextarea rows="4" placeholder="Tulis Catatan Klinis..."
                                    v-model="item.catatanklinisLab" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <template #footer>
                    <div class="flex justify-end space-x-2">
                        <VButton color="primary" icon="pi pi-check" raised @click="hilangken()">
                            Simpan
                        </VButton>
                    </div>
                </template>
            </Dialog>
        </div>
    </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import Dialog from 'primevue/dialog';
import moment from 'moment'
import Divider from 'primevue/divider';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
import DataTable from 'primevue/datatable';
import AutoComplete from 'primevue/autocomplete';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import Calendar from 'primevue/calendar';

import { FilterMatchMode } from 'primevue/api';
useHead({
    title: 'Tindakan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREC_APD = useRoute().query.norec_apd as string
let DARI_RADIOLOGI = useRoute().query.dariradiologi as string
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const props = defineProps({
    registrasi: {
        type: Object as PropType<any>,
    },
    pasien: {
        type: Object as PropType<any>,
    },
    selected: undefined,
    type: undefined,
    align: undefined,
    NOREC_PD: ''
})
useViewWrapper().setFullWidth(props.pasien ? true : false)
const route = useRoute()
const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
    hargasatuan: 0,
    tglpelayanan: new Date(),
    totalHarga: 0,
    dataDokterSelect: [],
    dataDokteroperator: null,
    dataDokteroperator1: null,
    dataDokteroperator2: null,
    dataDokteroperator3: null,
    dataDokteroperator4: null,
    perawatanastesi: null,
    penataanastesi: null,
    penataanastesiperawat: null,
    dataPerawatSelected: [],
    registrasi: {},
    isasa1: false,
    isasa2: false,
    isasa3: false,
    isasa4: false,
    isasa0: false,
})
if (NOREC_PD == undefined) {
    NOREC_PD = props.registrasi.norec_pd
    console.log('GANTI WOI', NOREC_PD)
}
const item2: any = reactive({
    tglpelayanan: new Date(),
})
const d_Produk: any = ref([])
const d_DokterVerif = ref([])
const d_DokterVerifkator = ref([])
const d_Komponen: any = ref([])
const d_JenisPelaksana: any = ref([])
const d_Pegawai: any = ref([])
const d_Pegawai2: any = ref([])
const d_pegawai: any = ref([])
const d_pegawai3: any = ref([])
const d_pegawai4: any = ref([])
const d_pegawai5: any = ref([])
const d_asa: any = ref([
    { label: 'ASA 1', value: '1' },
    { label: 'ASA 2', value: '2' },
    { label: 'ASA 3', value: '3' },
    { label: 'ASA 4', value: '4' },
    { label: 'NON ASA', value: '0' },
])
const list_PPP: any = ref([]);
const pasien: any = ref({})
const { y } = useWindowScroll()
const dataSourceTindakan: any = ref([])
const products = ref();
const selectedProduct = ref();
const metaKey = ref(false);
const filterPaket: any = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const dataSource: any = ref([])
const selectedTabs: any = ref()
const listChecked: any = ref([])
const modelCheck: any = ref([])
const isLoadingBill: any = ref(false)
const isLoadingPop: any = ref(false)
const modalPetugas: any = ref(false)
const dataSourcePetugas: any = ref([])
const dataSourceKom: any = ref([])
const dataSourcePaket: any = ref([])
const expandedRows: any = ref([]);
const modalKomponen: any = ref(false)
const listColor: any = ref(Object.keys(useThemeColors()))
const confirm = useConfirm();
const d_Registrasi: any = ref([])
const disabledSave = ref(false)
const historySave: any = ref([])
const dataSelect: any = ref({})
const isPaket: any = ref(false)
const isPaketTambah: any = ref(false)
const isClosedPasien: any = ref(false);
const forDokter: any = ref([]);
const modalPaket: any = ref(false)
const d_Dokter_Lab_Verif: any = ref([])
const modalLis: any = ref(false)
const modalRadiologiSendRIS: any = ref(false)
const d_Dokter_Rad_Verif: any = ref([])
const isSavedTindakan: any = ref(true);
let dataSelectedTindakan: any = ref([]);
const isStuck = computed(() => {
    return y.value > 30
})
const filtersTindakan = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const tabs: any = ref([
    { label: 'Tindakan', value: 1, icon: 'lnir lnir-medicine-alt' },
    { label: 'Tindakan Operasi ', value: 3, icon: 'lnir lnir-hospital-bed-alt-1', },
    { label: 'Riwayat', value: 2, icon: 'fas fa-list' },
])
const emit = defineEmits<{
    (e: 'update:selected', value: string): void
}>()
const filters: any = ref('')
const activeValue: any = ref(1)
const isAllTindakan = ref(false);
const columns = ref([
    { field: 'id', header: 'ID Jasa' },
    { field: 'namaproduk', header: 'Deskripsi' },
    { field: 'jumlah', header: 'Qty' },
    { field: 'iscito', header: 'Cito' },
    { field: 'hargasatuan', header: 'Tarif' },
    { field: 'dokter', header: 'Dokter' },
    { field: 'tanggal', header: 'Tanggal' },
]);
const sliderClass = computed(() => {
    if (!props.slider) {
        return ''
    }

    if (props.type === 'rounded') {
        if (props.tabs.length === 3) {
            return 'is-triple-slider'
        }
        if (props.tabs.length === 2) {
            return 'is-slider'
        }

        return ''
    }

    if (!props.type) {
        if (props.tabs.length === 3) {
            return 'is-squared is-triple-slider'
        }
        if (props.tabs.length === 2) {
            return 'is-squared is-slider'
        }
    }

    return ''
})
const toggle = async (value: string) => {
    activeValue.value = value
}

const onSwitchChange = (switchName: keyof SwitchState, value: boolean) => {
    item[switchName] = !!value;
    console.log(`${switchName} status:`, item[switchName]);
};

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.map((group: any) => {
        return {
            tglpelayanan_group: group.tglpelayanan_group,
            details: group.details.filter((detail: any) =>
                detail.namaproduk.match(new RegExp(filters.value, 'i'))
                || detail.namaruangan.match(new RegExp(filters.value, 'i'))
                || detail.dokterpemeriksa.match(new RegExp(filters.value, 'i'))
                || detail.pemeriksa.match(new RegExp(filters.value, 'i'))
            )
        };
    }).filter((group: any) => group.details.length > 0);

});
const modalInput: any = ref(false)
const isLoading: any = ref(false)
const dataRiwayat: any = ref([])
const isLoadChange: any = ref(false)
const isLoadingTindakan: any = ref(false);
const listItem: any = ref([
    {
        pegawai: [],
        jenisPelaksana: null,
        d_Pegawai: []
    }
])
const data2: any = ref([])
const isDetail: any = ref([true])
let dataResult: any = ref([]);

const getDokter = async () => {
    const response = await useApi().get(`/farmasi/input-resep-cbo-ruang`)
    d_ruangan.value = response.ruanganFarmasi.map((e: any) => { return { label: e.namaruangan, value: e.id } })
}

const onTindakanSelected = async (event) => {
    console.log("EVENT TINDAKAN SELECTED", event);
    await changeTindakan(event.data);

    let jsonPush = {
        id: event.data.id,
        id_hnp: item.id_hnp,
        namaproduk: event.data.namaproduk,
        objectruanganfk: event.data.objectruanganfk,
        hargasatuan: item.hargasatuan,
        jumlah: item.jumlah,
        komponenharga: d_Komponen,
        tanggal: moment(new Date()).format('DD-MM-YYYY'),
        iscito: false
    }

    dataSelectedTindakan.value.push(jsonPush);

    // console.log("maman", dataSelectedTindakan.value)


};
const onTindakanUnselected = async (event, type = '') => {
    await deleteItem(event.data.id);
    if (type == 'result') {
        removeSelectedTindakan(event);
    }
}

async function deleteItem(datass) {
    dataSelectedTindakan.value.forEach((getTindak, index) => {
        if (getTindak.id == datass) {
            dataSelectedTindakan.value.splice(index, 1);
        }
    });
}

const deleteResultTindakan = (event) => {
    dataResult.value.forEach((getTindak, index) => {
        if (getTindak.id == datass) {
            dataResult.value.splice(index, 1);
        }
    });
}

async function pasienByID(id: any) {
    return new Promise(async (resolve) => {
        if (props.pasien != undefined) {
            pasien.value = props.pasien
            item.NOREC_APD = props.registrasi.norec_apd
            item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
            item.registrasi = props.registrasi
            await dropdownTindakan(item.RUANGAN_LAST, props.registrasi.objectkelasfk)
            resolve(true);
        } else {
            isLoadingPasien.value = true
            await useApi().get(`/tindakan/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`).then(async (response: any) => {
                pasien.value = response.pasien
                item.NOREC_APD = response.last_registrasi.norec_apd
                item.RUANGAN_LAST = response.last_registrasi.objectruanganfk
                item.registrasi = response.last_registrasi
                isLoadingPasien.value = false
                await dropdownTindakan(item.RUANGAN_LAST, response.last_registrasi.objectkelasfk)
            })
            resolve(true);
        }
        console.log("REGIS NOW", item.registrasi);
    })
}

function dropdownTindakan(idruang: any, idkelas: any) {
    return new Promise(async (resolve) => {
        isLoadingTindakan.value = true;
        let qRuangan = '';
        if (isAllTindakan.value == false) {
            qRuangan = `?idruangan=${idruang}&idkelas=${idkelas}`;
        }

        useApi().get(`/tindakan/list-dropdown-registrasi?norec_pd=${NOREC_PD}&rad=${DARI_RADIOLOGI}`).then((response: any) => {
            d_Registrasi.value = Array.isArray(response.registrasi)
                ? response.registrasi.map((e: any) => ({
                    label: `${e.tglregistrasi} - (${e.noregistrasi} - ${e.namaruangan})`,
                    value: e,
                    default: e
                }))
                : [{
                    label: `${response.registrasi.tglregistrasi} - (${response.registrasi.noregistrasi} - ${response.registrasi.namaruangan})`,
                    value: response.registrasi,
                    default: response.registrasi
                }];
            if (DARI_RADIOLOGI === 'true') {
                // console.log('masuk ke if')
                item.pilihRegistrasi = d_Registrasi.value[0].value
                getRegistrasi(item.pilihRegistrasi)
                resolve(true);
            } else {
                for (let i = 0; i < response.registrasi.length; i++) {
                    let ftregis = response.registrasi[i];
                    if (item.registrasi.noregistrasi == ftregis.noregistrasi &&
                        item.registrasi.objectruanganfk == ftregis.objectruanganfk
                    ) {
                        item.pilihRegistrasi = d_Registrasi.value[i].value
                        getRegistrasi(item.pilihRegistrasi)
                        resolve(true);
                    }
                }
            }
        })

        useApi().get(
            `/tindakan/list-tindakan${qRuangan}`).then((response: any) => {
                d_Produk.value = response.data.map((e: any) => { return { label: e.namaproduk, value: e } })
                products.value = response.data;
                isLoadingTindakan.value = false;
            })
    })
}
function dropdownList() {
    useApi().get(`tindakan/list-jenis-petugas`).then((response: any) => {
        d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id } })
        item.nilaiCito = 0;
        if (item.registrasi.objectruanganfk == 126) { // nilai cito bedah
            item.nilaiCito = response.cito_bed != null ? parseFloat(response.cito_bed) : 1;
        } else {
            item.nilaiCito = response.cito != null ? parseFloat(response.cito) : 1;
        }

        for (let z = 0; z < listItem.value.length; z++) {
            const elementz = listItem.value[z];
            for (let x = 0; x < response.jenispetugaspelaksana.length; x++) {
                const element = response.jenispetugaspelaksana[x];
                if (element.jenispetugaspe.toLowerCase().indexOf('pemeriksa') > -1) {
                    elementz.jenisPelaksana = element.id
                    changeJenis(elementz)
                    break
                }
            }
        }
        // for (let x = 0; x < response.jenispetugaspelaksana.length; x++) {
        //     const element = response.jenispetugaspelaksana[x];

        //     if (element.jenispetugaspe.toLowerCase().indexOf('pemeriksa') > -1) {
        //         item2.jenisPelaksana = element.id
        //         changeJenis(item2)
        //         break
        //     }
        // }

        fetchAllDokter2()
    })
}
function changeSwitch(e: any) {
    item.isparamedis = e
}
function addNewItem() {
    listItem.value.push({
        jenisPelaksana: null,
        pegawai: [],
        d_Pegawai: []
    });
}

const addNewItem1 = () => {
    listItem.value.push({
        jenisPelaksana: null,
        pegawai: [],
    });
};

function removeItem(index: any) {
    listItem.value.splice(index, 1)
}
async function showModal() {
    await H.statusClosingPasien(NOREC_PD);
    item.hargasatuan = 0

    modalInput.value = true
}

function handleChange(e: any) {
    item.iscito = e
}

const filterByLabel = (option: { label?: string, value?: string }, searchTerm: string) => {
    if (option && option.label) {
        return option.label.toLowerCase().includes(searchTerm.toLowerCase()); // Search by label (name)
    }
    return false;
}

async function changeTindakan(e: any) {
    isLoading.value = true
    isLoadingTindakan.value = true;
    d_Komponen.value = []
    item.id_hnp = null
    item.hargasatuan = 0
    let ygini = item.registrasi;
    let kelasdipake = ygini.objectkelasfk

    if (item.pilihRegistrasi != undefined) {
        ygini = item.pilihRegistrasi;
    }
    if (ygini.kelasrawatfk != null && ygini.iskelastitip != null) {
        kelasdipake = ygini.kelasrawatfk // Jika pasien kelastitip, ambil tindakan berdasarkan kelas rawat
    }

    await useApi().get(
        '/tindakan/list-tindakan-komponen?idRuangan=' + ygini.objectruanganfk
        + '&idKelas=' + kelasdipake
        + '&idProduk=' + e.id
        + '&idJenisPelayanan=' + ygini.jenispelayananfk
        + '&idPenjamin=' + ygini.objectrekananfk
        + '&objectkebangsaanfk=' + pasien.value.objectkebangsaanfk
    ).then((response: any) => {
        isLoading.value = false
        isLoadingTindakan.value = false;
        item.hargasatuan = response.harga.hargasatuan
        item.hargasatuanDef = response.harga.hargasatuan
        item.id_hnp = response.harga.id
        item.jumlah = 1
        d_Komponen.value = response.komponen
    }).catch((err) => {
        isLoading.value = false
        isLoadingTindakan.value = false;
    })
}

const isAllTindakanChange = async (e: any) => {
    isAllTindakan.value = !e;

    isLoadingTindakan.value = true;
    console.log('PROPS REGIS', props.registrasi)
    let kelas = undefined;
    if (props.registrasi) {
        kelas = props.registrasi.objectkelasfk
    } else {
        kelas = item.registrasi.objectkelasfk
    }
    dropdownTindakan(item.RUANGAN_LAST, kelas)
}

const changeJenis = async (e: any) => {
    if (!e.jenisPelaksana) {
        H.alert('warning', 'Jenis pelaksana wajib dipilih')
        return
    }
    isLoadChange.value = true
    await useApi().get('/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e.jenisPelaksana).then((response: any) => {
        if (response != null) {
            e.d_Pegawai = response.map((e: any) => { return { label: e.namalengkap, value: e.id } })
            d_Pegawai2.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id } })
        } else {
            e.d_Pegawai = []
        }
    }).finally(() => {
        isLoadChange.value = false
    });
}


async function tambah() {
    if (isClosedPasien.value == true) {
        useToaster().error('Pasien sudah di closing, tidak bisa menambahkan tindakan');
        return;
    }

    if (!item.pilihRegistrasi) {
        useToaster().error('Registrasi harus di isi')
        return
    }
    if (!item.jumlah) {
        useToaster().error('Jumlah harus di isi')
        return
    }
    if (item.hargasatuan == 0) {
        useToaster().error('Harga Satuan belum ada')
        return
    }


    let nomor = 0
    if (data2.value.length == 0) {
        nomor = 1
    } else {
        nomor = data2.value.length + 1
    }
    let petugas = []
    for (let i = 0; i < listItem.value.length; i++) {
        const element = listItem.value[i];
        var jenispet = ''
        for (let z = 0; z < d_JenisPelaksana.value.length; z++) {
            const element2 = d_JenisPelaksana.value[z];
            if (element.jenisPelaksana == element2.value) {
                jenispet = element2.label
                break
            }
        }
        var listPegawai: any = []
        for (let k = 0; k < element.pegawai.length; k++) {
            const elementxx = element.pegawai[k];
            var namaPegawai = ''
            for (let zz = 0; zz < element.d_Pegawai.length; zz++) {
                const elementPeg = element.d_Pegawai[zz];
                if (elementxx == elementPeg.value) {
                    namaPegawai = elementPeg.label
                    break
                }
            }
            listPegawai.push({
                'id': elementxx,
                'namalengkap': namaPegawai
            })
        }
        petugas.push({
            "objectjenispetugaspefk": element.jenisPelaksana,
            "jenispetugaspe": jenispet,
            "listpegawai": listPegawai
        })
    }
    var jasacito = 0
    if (item.iscito && item.iscito == true) {
        jasacito = parseFloat(item.hargasatuanDef) * item.nilaiCito;
    }

    var data: any = {};
    if (item.no != undefined) {
        for (let x = 0; x < data2.value.length; x++) {
            const element = data2.value[x];
            if (element.no == item.no) {
                data.no = item.no
                data.tglpelayanan = H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm')
                data.produkfk = item.produk.id
                data.namaproduk = item.produk.namaproduk
                data.hargasatuan = item.hargasatuanDef
                data.jumlah = item.jumlah
                data.pelayananpetugas = petugas
                data.komponenharga = d_Komponen.value
                data.iscito = item.iscito ? item.iscito : false
                data.jasacito = jasacito
                data.isparamedis = item.isparamedis ? item.isparamedis : false
                data.diskon = 0
                data.subtotal = parseFloat(item.hargasatuanDef) * parseFloat(item.jumlah) + jasacito
                data.icon = item.iscito == true ? "<i class='iconify' data-icon='feather:check-circle' aria-hidden='true'></i>" : "<i class='iconify' data-icon='feather:x-circle' aria-hidden='true'></i>"

                data2.value[x] = data;
            }
        }
    } else {

        data = {
            'no': nomor,
            'tglpelayanan': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm'),
            'produkfk': item.produk.id,
            'namaproduk': item.produk.namaproduk,
            'hargasatuan': item.hargasatuanDef,
            'jumlah': item.jumlah,
            'pelayananpetugas': petugas,
            'komponenharga': d_Komponen.value,
            'iscito': item.iscito ? item.iscito : false,
            'icon': item.iscito == true ? "<i class='iconify is-success' data-icon='feather:check-circle' aria-hidden='true'></i>" : "<i class='iconify is-danger' data-icon='feather:x-circle' aria-hidden='true'></i>",
            'jasacito': jasacito,
            'isparamedis': item.isparamedis ? item.isparamedis : false,
            'diskon': 0,
            'subtotal': parseFloat(item.hargasatuanDef) * parseFloat(item.jumlah) + jasacito
        }
        data2.value.push(data)
    }
    isDetail.value[data2.value.length] = true
    dataSourceTindakan.value = data2.value
    countTotal()
    clearInput()
}
function editItems(e: any) {
    item.no = e.no
    item.produk = { id: e.produkfk, namaproduk: e.namaproduk }
    item.tglpelayanan = new Date(e.tglpelayanan)
    item.hargasatuanDef = e.hargasatuan
    item.hargasatuan = e.hargasatuan
    item.jumlah = e.jumlah
    item.iscito = e.iscito
    changeSwitch((e.isparamedis ? true : false))
    d_Komponen.value = e.komponenharga
    listItem.value = []
    for (let x = 0; x < e.pelayananpetugas.length; x++) {
        const element = e.pelayananpetugas[x];
        var peg = []
        for (let z = 0; z < element.listpegawai.length; z++) {
            const elementz = element.listpegawai[z];
            peg.push(elementz.id)
        }
        listItem.value.push({
            'jenisPelaksana': element.objectjenispetugaspefk,
            'pegawai': peg,
        })
    }
    modalInput.value = true
}
function hapusItems(e: any) {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].no == e.no) {
            data2.value.splice(i, 1);
        }
    }
    dataSourceTindakan.value = data2.value
    countTotal()
    clearInput()
}
function multiSelectArrayToString(item: any) {
    return item.map(function (elem: any) {
        return elem.namalengkap
    }).join(", ");

}
function countTotal() {

    let total = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        total = total + parseFloat(element.subtotal)
    }
    item.totalHarga = total
}
function clearInput() {
    if (item.produk) {
        H.alert('info', item.produk.namaproduk + ' berhasil ditambahkan')
    }
    delete item.produk
    delete item.jumlah
    delete item.no
    delete item.iscito
    delete item.isparamedis
}
function kembaliKeun() {
    window.history.back()
}
function clearList() {
    data2.value = []
    dataResult.value = [];
    dataSourceTindakan.value = data2.value
    dataSelectedTindakan.value = [];
    // await deleteItem(event.data.id);
}

const getRegistrasi = async (e: any) => {
    item.tglpelayanan = e.tanggal
    item.dataDokterSelect = e.objectpegawaifk
    item.registrasi = e;
}

async function newSimpan() {
    if (isClosedPasien.value == true) {
        useToaster().error('Pasien sudah di closing, tidak bisa menambahkan tindakan');
        return;
    }

    if (dataSelectedTindakan.length == 0) {
        H.alert('warning', 'Tindakan belum di pilih');
        return
    }

    if (item.NOREC_PD == '') {
        item.NOREC_PD = props.registrasi.norec_pd
    }

    var listPetugas = []; // Khusus petugas-petugas bedah
    if (activeValue.value == 3) {
        isLoading.value = true
        listPetugas = await useApi().get(`/tindakan/list-petugas`);
        isLoading.value = false
    }

    try {
        isLoading.value = true;
        dataSelectedTindakan.value.forEach((element, index) => {
            let asaParam = ''
            if (item.isasa1) { asaParam = 1 }
            else if (item.isasa2) { asaParam = 2 }
            else if (item.isasa3) { asaParam = 3 }
            else if (item.isasa4) { asaParam = 4 }
            else { asaParam = 0 }

            let ygdiselect = item.registrasi;
            let kelasused = ygdiselect.objectkelasfk
            if (item.pilihRegistrasi != undefined) {
                ygdiselect = item.pilihRegistrasi;
            }
            if (ygdiselect.kelasrawatfk != null && ygdiselect.iskelastitip != null) {
                kelasused = ygdiselect.kelasrawatfk // Jika pasien kelastitip, ambil tindakan berdasarkan kelas rawat
            }

            element.norec_pp = ''
            element.kelasfk = kelasused
            element.norec_apd = item.pilihRegistrasi.norec_apd
            element.norec_pd = item.pilihRegistrasi.norec_pd
            element.tglregistrasi = item.registrasi.tglregistrasi
            element.isPaketTambah = false
            element.isNewAdded = true
            element.tanggal = new Date()
            element.tglpelayanan = H.formatDate(new Date(), 'DD-MM-YYYY')
            element.asa = asaParam

            // Validasi ketika tidak ada komponen harga
            if (element.komponenharga.length == 0) {
                H.alert('warning', 'Tindakan (' + element.namaproduk + ') ini belum ada komponen harganya, harap hubungi IT')
                return
            }

            // Khusus Bank Darah
            if (item.pilihRegistrasi.namadepartemen.toLowerCase() == 'Instalasi Laboratorium'.toLowerCase() && item.pilihRegistrasi.namaruangan.toLowerCase() != 'BANK DARAH'.toLowerCase()) {
                useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}`).then((response: any) => {
                    if (response.ordelab === null) {
                        modalInput.value = false;
                        modalRadiologiSendRIS.value = true
                        modalLis.value = true
                    }
                })
            }

            // Select pelaksana based on DPJP
            let cpgw = d_pegawai3.value.filter((dt) => {
                return dt.value == item.dataDokterSelect;
            });
            if (cpgw.length > 0) {
                element.pelaksana = cpgw[0] || {}
            }

            // Set jenis pelaksana
            // if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
            //     element.jenispelaksana = { value: 4, label: 'Dokter Pemeriksa' } // Dokter pemeriksa
            // } else {
            //     element.jenispelaksana = { value: 2, label: 'Petugas Pelaksana' } // Petugas Pelaksana
            // }

            // Khusus untuk tindakan operasi
            if (activeValue.value == 3) {
                // Validasi Petugas
                // if (!item.dataDokteroperator) {
                //     H.alert('warning', 'Dokter Anak perlu diisi!')
                //     return
                // }
                // if (!item.dataDokteroperator1) {
                //     H.alert('warning', 'Dokter Anestesi perlu diisi!')
                //     return
                // }
                // if (!item.dataDokteroperator2) {
                //     H.alert('warning', 'Dokter Operator 1 perlu diisi!')
                //     return
                // }
                // if (!item.dataDokteroperator3) {
                //     H.alert('warning', 'Dokter Asisten perlu diisi!')
                //     return
                // }
                // if (!item.dataDokteroperator4.length) {
                //     H.alert('warning', 'Asisten Bedah perlu diisi!')
                //     return
                // }
                // if (!item.perawatanastesi) {
                //     H.alert('warning', 'Perawat Instrumen perlu diisi!')
                //     return
                // }
                // if (!item.penataanastesi) {
                //     H.alert('warning', 'Perawat Sirkuler perlu diisi!')
                //     return
                // }
                // if (!item.penataanastesiperawat) {
                //     H.alert('warning', 'Penata Anastesi - Perawat Anastesi perlu diisi!')
                //     return
                // }

                let dokteroperator = null;
                let dokteroperator1 = null;
                let dokteroperator2 = null;
                let dokteroperator3 = null;
                let dokteroperator4 = null;
                let perawatanastesi = null;
                let penataanastesi = null;
                let penataanastesiperawat = null;

                dokteroperator = d_pegawai3.value.filter((dt) => { return dt.value == item.dataDokteroperator });
                dokteroperator1 = d_pegawai3.value.filter((dt) => { return dt.value == item.dataDokteroperator1 });
                dokteroperator2 = d_pegawai3.value.filter((dt) => { return dt.value == item.dataDokteroperator2 });
                dokteroperator3 = d_pegawai3.value.filter((dt) => { return dt.value == item.dataDokteroperator3 });

                // Ini dipakai kondisi karena 4 inputan ini menggunakan autocomplete
                if (item.dataDokteroperator4) {
                    dokteroperator4 = listPetugas.filter((dt) => { return dt.value == item.dataDokteroperator4.value });
                }
                if (item.perawatanastesi) {
                    perawatanastesi = listPetugas.filter((dt) => { return dt.value == item.perawatanastesi.value });
                }
                if (item.penataanastesi) {
                    penataanastesi = listPetugas.filter((dt) => { return dt.value == item.penataanastesi.value });
                }
                if (item.penataanastesiperawat) {
                    penataanastesiperawat = listPetugas.filter((dt) => { return dt.value == item.penataanastesiperawat.value });
                }

                if (dokteroperator.length > 0) { element.dataDokteroperator = dokteroperator[0] || null }
                if (dokteroperator1.length > 0) { element.dataDokteroperator1 = dokteroperator1[0] || null }
                if (dokteroperator2.length > 0) { element.dataDokteroperator2 = dokteroperator2[0] || null }
                if (dokteroperator3.length > 0) { element.dataDokteroperator3 = dokteroperator3[0] || null }
                if (dokteroperator4) { element.dataDokteroperator4 = dokteroperator4[0] || null }
                if (perawatanastesi) { element.dataPerawatanastesi = perawatanastesi[0] || null }
                if (penataanastesi) { element.dataPenataanastesi = penataanastesi[0] || null }
                if (penataanastesiperawat) { element.dataPenataanastesiperawat = penataanastesiperawat[0] || null }
            } else {
                // Default tindakan
                element.jenispelaksana = { value: 2, label: 'Petugas Pelaksana' }
            }

            dataResult.value.push(element);
            console.log("Data Result ditambahkan", dataResult.value);
        });
    } catch (error) {
        console.error(error);
    } finally {
        selectedProduct.value = [];
        dataSelectedTindakan.value = [];
        isLoading.value = false;
        modalInput.value = false;
    }
}

async function simpan() {
    // Validasi
    if (!item.dataDokterSelect) { useToaster().error('Dokter DPJP belum di pilih'); return }
    if (dataResult.length == 0) { H.alert('warning', 'Tindakan belum di isi'); return }
    if (dataResult.value.pelaksana == "") { H.alert('warning', 'Pelaksana belum di isi'); return }

    let iserr = false;
    dataResult.value.forEach((val, index) => {
        let ygini = item.registrasi;
        let kelasdipake = ygini.objectkelasfk
        let petugas = [{}]
        let d = dataResult.value[index]

        // Pelayanan Pasien Petugas
        petugas[0]['jenispelaksana'] = val.jenispelaksana['value']
        petugas[0]['listpegawai'] = [val.pelaksana]

        if (item.pilihRegistrasi != undefined) {
            ygini = item.pilihRegistrasi;
        }
        if (ygini.kelasrawatfk != null && ygini.iskelastitip != null) {
            kelasdipake = ygini.kelasrawatfk // Jika kelas titip mengambil kelas rawat
        }

        d.kelasfk = kelasdipake
        d.norec_apd = item.pilihRegistrasi.norec_apd
        d.norec_pd = item.pilihRegistrasi.norec_pd
        d.tglregistrasi = item.registrasi.tglregistrasi
        d.diskon = 0
        // d.iscito = 0
        d.isparamedis = 0
        d.produkfk = val.id
        d.tglpelayanan = val.tanggal
        d.dokter = item.dataDokterSelect
        d.pegawai = item.pegawai || [];
        d.pelayananpetugas = petugas;
        d.norec_pp = val.norec_pp;
        val.dokter = item.dataDokterSelect;

        // Validasi
        if (val.komponenharga.length == 0) {
            H.alert('warning', 'Tindakan (' + val.namaproduk + ') ini belum ada komponen harganya, harap hubungi IT')
            iserr = true;
            return
        }
        if (val.dokter == undefined || val.dokter == null || val.dokter == "") {
            H.alert('warning', 'Dokter tindakan (' + val.namaproduk + ') belum dipilih')
            iserr = true;
            return
        }
        if (val.pelaksana == undefined || val.pelaksana == null || val.pelaksana == "") {
            H.alert('warning', 'Pelaksana tindakan (' + val.namaproduk + ') belum dipilih')
            iserr = true;
            return
        }
        // if (val.jenispelaksana == undefined || val.jenispelaksana == null || val.jenispelaksana == "") {
        //     H.alert('warning', 'Jenis Pelaksana tindakan (' + val.namaproduk + ') belum dipilih')
        //     iserr = true;
        //     return
        // }
    });
    if (iserr) return;
    let json = {
        'pelayananpasien': dataResult.value,
        'noregistrasi': item.pilihRegistrasi.noregistrasi,
        'nocm': pasien.value.nocm,
        'isPaketTambah': isPaketTambah.value,
        'namapasien': pasien.value.namapasien,
        'namaruangan': item.registrasi.namaruangan,
        'departemen': item.pilihRegistrasi.namadepartemen.toLowerCase(),
        'flag': item.pilihRegistrasi.namaruangan.toLowerCase() == 'BANK DARAH'.toLowerCase() ? true : false
    }
    isLoading.value = true
    await useApi().post(`/tindakan/save-tindakan`, json).then((response: any) => {
        if (response != null) {
            if (item.pilihRegistrasi.namadepartemen.toUpperCase() == 'INSTALASI LABORATORIUM'.toUpperCase() && item.pilihRegistrasi.namaruangan.toLowerCase() != 'BANK DARAH'.toLowerCase()) {
                isLoading.value = true
                useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}`).then((res: any) => {
                    if (res.ordelab == null || res.ordelab === '' || (typeof res.ordelab === 'object' && Object.keys(res.ordelab).length === 0)) {
                        sendLIS(response)
                        return
                    } else {
                        editLIS(response.norec_pp)
                        return
                    }
                }).catch((xxx: any) => {
                    isLoading.value = false
                    console.error(xxx)
                })
            } else {
                dataRiwayat.value = dataResult.value
                isLoading.value = false
                fetchBill()
                getTempTindakan();
            }
            this.activeValue = 2
        }
    }).catch((e: any) => {
        isLoading.value = false
    })
}
async function simpanOperasi() {
    if (isClosedPasien.value == true) {
        useToaster().error('Pasien sudah di closing, tidak bisa menambahkan tindakan');
        return;
    }
    if (dataResult.length == 0) {
        H.alert('warning', 'Tindakan belum di isi');
        return;
    }

    let iserr = false;

    dataResult.value.forEach((val, index) => {
        let d = dataResult.value[index];
        let norec_pp = d.norec_pp ? d.norec_pp : null;
        let pg = {};

        // List norec_ppp
        let dokterAnak = null;
        let dokterAnestesi = null;
        let dokterOperator1 = null;
        let dokterAsisten = null;
        let asistenBedah = null;
        let perawatInstrumen = null;
        let perawatSirkuler = null;
        let penataAnastesi = null;

        dokterAnak = list_PPP.value.filter((dt) => { return dt.norec_pp == norec_pp && dt.idjenispelaksana == 27 });
        dokterAnestesi = list_PPP.value.filter((dt) => { return dt.norec_pp == norec_pp && dt.idjenispelaksana == 6 });
        dokterOperator1 = list_PPP.value.filter((dt) => { return dt.norec_pp == norec_pp && dt.idjenispelaksana == 5 });
        dokterAsisten = list_PPP.value.filter((dt) => { return dt.norec_pp == norec_pp && dt.idjenispelaksana == 28 });
        asistenBedah = list_PPP.value.filter((dt) => { return dt.norec_pp == norec_pp && dt.idjenispelaksana == 29 });
        perawatInstrumen = list_PPP.value.filter((dt) => { return dt.norec_pp == norec_pp && dt.idjenispelaksana == 30 });
        perawatSirkuler = list_PPP.value.filter((dt) => { return dt.norec_pp == norec_pp && dt.idjenispelaksana == 31 });
        penataAnastesi = list_PPP.value.filter((dt) => { return dt.norec_pp == norec_pp && dt.idjenispelaksana == 32 });

        // List Petugas
        pg.operator = d.dataDokteroperator && d.dataDokteroperator.value ? d.dataDokteroperator.value : (item.dataDokteroperator ? item.dataDokteroperator : null)
        pg.operator1 = d.dataDokteroperator1 && d.dataDokteroperator1.value ? d.dataDokteroperator1.value : (item.dataDokteroperator1 ? item.dataDokteroperator1 : null)
        pg.operator2 = d.dataDokteroperator2 && d.dataDokteroperator2.value ? d.dataDokteroperator2.value : (item.dataDokteroperator2 ? item.dataDokteroperator2 : null)
        pg.operator3 = d.dataDokteroperator3 && d.dataDokteroperator3.value ? d.dataDokteroperator3.value : (item.dataDokteroperator3 ? item.dataDokteroperator3 : null)
        pg.operator4 = d.dataDokteroperator4 && d.dataDokteroperator4.value ? d.dataDokteroperator4.value : (item.dataDokteroperator4 && item.dataDokteroperator4.value ? item.dataDokteroperator4.value : null)
        pg.perawatanastesi = d.dataPerawatanastesi && d.dataPerawatanastesi.value ? d.dataPerawatanastesi.value : (item.perawatanastesi && item.perawatanastesi.value ? item.perawatanastesi.value : null)
        pg.penataanastesi = d.dataPenataanastesi && d.dataPenataanastesi.value ? d.dataPenataanastesi.value : (item.penataanastesi && item.penataanastesi.value ? item.penataanastesi.value : null)
        pg.penataanastesiperawat = d.dataPenataanastesiperawat && d.dataPenataanastesiperawat.value ? d.dataPenataanastesiperawat.value : (item.penataanastesiperawat && item.penataanastesiperawat.value ? item.penataanastesiperawat.value : null)

        //? Validasi ketika pegawainya NULL
        const pegawaiList = [
            { jenispelaksana: 27, value: pg.operator, norec_ppp: dokterAnak[0]?.norec_ppp || null },
            { jenispelaksana: 6, value: pg.operator1, norec_ppp: dokterAnestesi[0]?.norec_ppp || null },
            { jenispelaksana: 5, value: pg.operator2, norec_ppp: dokterOperator1[0]?.norec_ppp || null },
            { jenispelaksana: 28, value: pg.operator3, norec_ppp: dokterAsisten[0]?.norec_ppp || null },
            { jenispelaksana: 29, value: pg.operator4, norec_ppp: asistenBedah[0]?.norec_ppp || null },
            { jenispelaksana: 30, value: pg.perawatanastesi, norec_ppp: perawatInstrumen[0]?.norec_ppp || null },
            { jenispelaksana: 31, value: pg.penataanastesi, norec_ppp: perawatSirkuler[0]?.norec_ppp || null },
            { jenispelaksana: 32, value: pg.penataanastesiperawat, norec_ppp: penataAnastesi[0]?.norec_ppp || null }
        ].filter(p => p.value !== null);

        // Pelayanan Pasien Petugas
        let petugas = [{listPegawai: pegawaiList}]

        d.kelasfk = item.registrasi.objectkelasfk
        d.norec_apd = item.pilihRegistrasi.norec_apd
        d.norec_pd = item.pilihRegistrasi.norec_pd
        d.tglregistrasi = moment(item.registrasi.tglregistrasi, 'DD-MM-YYYY').format('YYYY-MM-DD HH:mm:ss')
        d.diskon = 0
        d.iscito = 0
        d.isparamedis = 0
        d.produkfk = val.id
        d.tglpelayanan = d.tanggal ? moment(d.tanggal).format('YYYY-MM-DD HH:mm:ss') : moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
        d.dokter = item.dataDokterSelect
        d.jenisPelaksana = item.jenisPelaksana || null;
        d.pegawai = item.pegawai || [];
        d.pelayananpetugas = petugas;
        d.asa1 = d.asa == 1 ? true : item.isasa1
        d.asa2 = d.asa == 2 ? true : item.isasa2
        d.asa3 = d.asa == 3 ? true : item.isasa3
        d.asa4 = d.asa == 4 ? true : item.isasa4
        d.asa0 = d.asa == 0 ? true : item.isasa0

        if (val.komponenharga.length == 0) {
            H.alert('warning', 'Tindakan (' + val.namaproduk + ') ini belum ada komponen harganya, harap hubungi IT')
            iserr = true;
            return
        }
        if (typeof val.dokter == undefined || val.dokter == null || val.dokter == "") {
            H.alert('warning', 'Dokter tindakan (' + val.namaproduk + ') belum dipilih')
            iserr = true;
            return
        }
    });

    if (iserr) return;
    let json = {
        'pelayananpasien': dataResult.value,
        'noregistrasi': item.pilihRegistrasi.noregistrasi,
        'nocm': pasien.value.nocm,
        'namapasien': pasien.value.namapasien,
        'namaruangan': item.registrasi.namaruangan,
        'departemen': item.pilihRegistrasi.namadepartemen.toLowerCase()
    }

    isLoading.value = true
    await useApi().post(`/tindakan/save-tindakan-operasi`, json).then((response: any) => {
        if (response.data != null) {
            dataRiwayat.value = dataResult.value
            isLoading.value = false
            fetchBill()
            getTempTindakan();
            this.activeValue = 2
        }
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const sendRISPACS = (resp: any) => {
    let objBridg: any = []

    for (let x = 0; x < dataResult.value.length; x++) {
        const element = dataResult.value[x];
        objBridg.push({
            produkfk: element.produkfk,
            namaproduk: element.namaproduk,
            qtyproduk: 1,
            objectkelasfk: item.registrasi.objectkelasfk,
            iddokterverif: element.dokterVerifRad.value,
            namadokterverif: element.dokterVerifRad.label
        })
    }
    if (objBridg.length) {
        useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}&noorderrad=true`).then((response: any) => {
            let itemsave = {
                "details": objBridg,
                "noorder": response.ordelab.noorder,
                "objectkelasfk": item.registrasi.objectkelasfk,
                "objectruangantujuanfk": item.pilihRegistrasi.objectruanganfk,
                "objectpegawaiorderfk": item.pilihRegistrasi.objectpegawaifk,
                // "iddokterverif": item.objectpegawaifk.value,
                // "namadokterverif": item.objectpegawaifk.label,
                "idadmin": item.petugasverify.sanata,
                "namaadmin": item.petugasverify.label,
                "idradiografer": item.radiografer.sanata,     //DEFAULT
                "namaradiografer": item.radiografer.label,   //DEFAULT
                "catatan_klinis": item.keterangan ? item.keterangan : null,
            }
            console.log('data itemsave', itemsave);

            isLoading.value = true
            useApi().post('bridging/penunjang/save-bridging-zeta', itemsave).then((e: any) => {
                isLoading.value = false
                console.log("DATA RESPONSE", e)
                getTempTindakan()
                fetchBill()
                clearList()
            }).catch((e: any) => {
                isLoading.value = false
                console.error('Error di: ', e)
            })
        }).catch((xxx: any) => {
            isLoading.value = false
            console.error(xxx)
        })
    }
}

const sendLIS = (resp: any) => {
    let objSaveOrder: any = {}
    let objOrder: any = []

    console.log(dataResult.value)

    for (let x = 0; x < dataResult.value.length; x++) {
        const element = dataResult.value[x];
        let norec_pp_value = resp.norec_pp[x] || null;
        objOrder.push({
            no: x + 1,
            produkfk: element.produkfk,
            namaproduk: element.namaproduk,
            qtyproduk: 1,
            objectkelasfk: item.pilihRegistrasi.objectkelasfk,
            nourut: null,
            norec_pp: norec_pp_value,
        })


        objSaveOrder = {
            status: "bridinglangsung",
            noregistrasi: item.pilihRegistrasi.noregistrasi,
            tanggal: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
            tgloperasi: null,
            norec_so: '',
            norec_apd: item.pilihRegistrasi.norec_apd ? item.pilihRegistrasi.norec_apd : null,
            norec_pd: item.pilihRegistrasi.norec_pd,
            qtyproduk: objOrder.length,
            objectruanganfk: item.pilihRegistrasi.objectruanganlastfk,
            pegawaiorderfk: item.pilihRegistrasi.objectpegawaifk,
            namalengkap: item.pilihRegistrasi.dokter,
            objectruangantujuanfk: item.pilihRegistrasi.objectruanganfk,
            departemenfk: item.pilihRegistrasi.objectdepartemenfk,
            keterangan: null,
            // noord : element.noorder,
            iscito: false,
            details: objOrder,
            langsungregis: true
        }
    }
    if (objOrder.length) {
        console.log(objOrder)

        useApi().post(`/laboratorium/simpan-order`, objSaveOrder).then((response: any) => {
                let itemsave = {
                    "bridging": objOrder,
                    "noorder": response.data.noorder,
                    "objectkelasfk": item.pilihRegistrasi.objectkelasfk,
                    "objectruangantujuanfk": item.pilihRegistrasi.objectruanganfk,
                    "objectpegawaiorderfk": item.pilihRegistrasi.objectpegawaifk,
                    "iddokterverif": item.dokterVerifLab.value,
                    "namadokterverif": item.dokterVerifLab.label,
                    "noregistrasi": item.pilihRegistrasi.noregistrasi,
                    "catatan_klinis": item.catatanklinisLab ? item.catatanklinisLab : null,
                }
                useApi().post('bridging/penunjang/save-bridging-vans-lab', itemsave).then((e: any) => {
                    H.alert('success', 'Success sending LIS')
                    getTempTindakan()
                    fetchBill()
                    clearList()
                    isLoading.value = false
                }, (error) => {
                    console.error(error)
                })
            }, (error) => {
                console.error(error)
            })
    }


}


const sendLISMCU = (resp: any) => {

    let objSaveOrder: any = {}
    let objOrder: any = []
    let dokterveriflab = ''
    let iddokterveriflab = ''

    console.log(resp)

    for (let x = 0; x < resp.length; x++) {
        const element = resp[x];
        dokterveriflab = element.dokterVerifLab
        iddokterveriflab = element.iddokterVerifLab
        objOrder.push({
            no: x + 1,
            produkfk: element.produkfk,
            namaproduk: element.namaproduk,
            qtyproduk: 1,
            objectkelasfk: item.pilihRegistrasi.objectkelasfk,
            nourut: null,
            norec_pp: element.norec,
        })


        objSaveOrder = {
            status: "bridinglangsung",
            noregistrasi: item.pilihRegistrasi.noregistrasi,
            tanggal: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
            tgloperasi: null,
            norec_so: '',
            norec_apd: item.pilihRegistrasi.norec_apd ? item.pilihRegistrasi.norec_apd : null,
            norec_pd: item.pilihRegistrasi.norec_pd,
            qtyproduk: objOrder.length,
            objectruanganfk: item.pilihRegistrasi.objectruanganlastfk,
            pegawaiorderfk: item.pilihRegistrasi.objectpegawaifk,
            namalengkap: item.pilihRegistrasi.dokter,
            objectruangantujuanfk: 335,
            departemenfk: 3,
            keterangan: null,
            // noord : element.noorder,
            iscito: false,
            details: objOrder,
            langsungregis: true
        }
    }
    if (objOrder.length) {
        console.log(objOrder)

        useApi().post(
            `/laboratorium/simpan-order`, objSaveOrder).then((response: any) => {

                let itemsave = {
                    "bridging": objOrder,
                    "noorder": response.data.noorder,
                    "objectkelasfk": item.pilihRegistrasi.objectkelasfk,
                    "objectruangantujuanfk": 335,
                    "objectpegawaiorderfk": item.pilihRegistrasi.objectpegawaifk,
                    "iddokterverif": iddokterveriflab,
                    "namadokterverif": dokterveriflab,
                    "noregistrasi": item.pilihRegistrasi.noregistrasi,
                    "catatan_klinis": item.catatanklinisLab ? item.catatanklinisLab : null,
                }
                useApi().post('bridging/penunjang/save-bridging-vans-lab', itemsave).then((e: any) => {
                    isLoading.value = false
                    getTempTindakan()
                    fetchBill()
                    H.alert('success', 'Success sending LIS')
                    clearList()
                    isLoading.value = false
                }, (error) => {
                    console.error(error)
                })
            }, (error) => {
                console.error(error)
            })
    }


}


const sendRISPACSLangsungRegis = (resp: any) => {
    console.log(dataResult)
    let objSaveOrder: any = {}
    let objOrder: any = []

    for (let x = 0; x < dataResult.value.length; x++) {
        const element = dataResult.value[x];
        objOrder.push({
            no: x + 1,
            produkfk: element.produkfk,
            qtyproduk: 1,
            objectkelasfk: item.pilihRegistrasi.objectkelasfk,
            nourut: x + 1,
            norec_pp: resp.data.norec,
            iddokterverif: element.dokterVerifRad.value,
            namadokterverif: element.dokterVerifRad.label
        })
        objSaveOrder = {
            status: "bridinglangsung",
            noregistrasi: item.pilihRegistrasi.noregistrasi,
            tanggal: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
            tgloperasi: null,
            norec_so: '',
            norec_apd: item.pilihRegistrasi.norec_apd,
            norec_pd: item.pilihRegistrasi.norec_pd,
            qtyproduk: objOrder.length,
            objectruanganfk: item.pilihRegistrasi.objectruanganlastfk,
            pegawaiorderfk: item.pilihRegistrasi.objectpegawaifk,
            namalengkap: item.pilihRegistrasi.dokter,
            objectruangantujuanfk: item.pilihRegistrasi.objectruanganfk,
            departemenfk: item.pilihRegistrasi.objectdepartemenfk,
            keterangan: null,
            // noord: element.noorder,
            iscito: false,
            details: objOrder,
            langsungregis: true
        }
    }
    if (objOrder.length) {
        isLoading.value = true
        useApi().post(
            `/laboratorium/simpan-order`, objSaveOrder).then((response: any) => {

                let itemsave = {
                    "details": objOrder,
                    "noorder": response.data.noorder,
                    "objectkelasfk": item.pilihRegistrasi.objectkelasfk,
                    "objectruangantujuanfk": item.pilihRegistrasi.objectruanganfk,
                    "objectpegawaiorderfk": item.pilihRegistrasi.objectpegawaifk,
                    // "iddokterverif": item.dokterVerifRad.value,
                    // "namadokterverif": item.dokterVerifRad.label,
                    "idadmin": item.petugasverify.sanata,
                    "namaadmin": item.petugasverify.label,
                    "idradiografer": item.radiografer.sanata,     //DEFAULT
                    "namaradiografer": item.radiografer.label,   //DEFAULT
                    "catatan_klinis": item.catatanklinis ? item.catatanklinis : null,
                }
                console.log('data itemsaev', itemsave);

                useApi().post('bridging/penunjang/save-bridging-zeta', itemsave).then((e: any) => {
                    H.alert('success', 'Success sending RIS')
                    getTempTindakan()
                    fetchBill()
                    clearList()
                    isLoading.value = false
                    // if(e.metaData.code == 200 || e.metaData.code == 201)  {
                    //     H.alert('success','Success sending RIS')
                    //     getTempTindakan()
                    //     clearList()
                    //     isLoading.value = false
                    // }
                }).catch((e: any) => {
                    isLoading.value = false
                    console.error(e)
                })
            })
    }

}

const getListDokter = async () => {
    const response = await useApi().get(`/dashboard/radiologi/get-dokter`)
    //   d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    //   d_JenisKelamin.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id } })
    //   d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e.id } })
    d_DokterVerifkator.value = response.data.map((e: any) => {
        return { label: `${e.namalengkap}`, value: `${e.id}`, default: e }
    })
    d_DokterVerif.value = response.pegawaiRadiologi.map((e: any) => {
        return { label: e.namalengkap, value: e.id, sanata: e.sanata_id }
    })

}

const editLIS = (resp: any) => {

    let objBridg: any = []

    for (let x = 0; x < dataResult.value.length; x++) {
        const element = dataResult.value[x];
        objBridg.push({
            produkfk: element.id,
            namaproduk: element.namaproduk,
        })
    }
    console.log(objBridg)
    isLoading.value = true
    if (objBridg.length) {
        useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}`).then((response: any) => {

            let itemsave = {
                "bridging": objBridg,
                "noorder": response.ordelab.noorder,
                "objectkelasfk": item.registrasi.objectkelasfk,
                "objectruangantujuanfk": item.pilihRegistrasi.objectruanganfk,
                "objectpegawaiorderfk": item.pilihRegistrasi.objectpegawaifk,
                // "iddokterverif": item.dokterVerifLab.value,
                // "namadokterverif": item.dokterVerifLab.label,
                "objectruanganfk": item.registrasi.objectruanganfk,
                "noregistrasi": item.pilihRegistrasi.noregistrasi,
                // "catatan_klinis": item.catatanklinisLab ? item.catatanklinisLab : null,
                "norec_pp": resp
            }
            console.log(itemsave)
            if (response.ordelab.noorder != null) {
                useApi().post('bridging/penunjang/save-edit-lab', itemsave).then((res: any) => {
                    if (res.data != null) {
                        useApi().post('bridging/penunjang/edit-bridging-vans-lab', itemsave).then((e: any) => {
                            isLoading.value = false
                            H.alert('success', 'Success sending LIS')
                            clearList()
                            // if(e.data != null)  {
                            //     isLoading.value = false
                            //     H.alert('success','Success sending LIS')
                            //     clearList()
                            // }
                        }, (error) => {
                            isLoading.value = false
                            console.error(error)
                        })
                    }
                }).catch((e: any) => {
                    isLoading.value = false
                    console.error(e)
                })
            }
        }).catch((e: any) => {
            isLoading.value = false
            console.error(e)
        })
    }

}

const getDokterVerif = async () => {
    const response = await useApi().get(`/dashboard/radiologi/get-dokter`)
    d_Dokter_Rad_Verif.value = response.data.map((e: any) => {
        return { label: `${e.namalengkap}`, value: `${e.id}`, default: e }
    })
}
const fetchPetugas = async (filter: any) => {
    const query = filter?.query || ''
    const response = useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=id,idJenisPegawaiDokterLab&limit=10`).then((response: any) => {
        d_Dokter_Lab_Verif.value = response
    })
}

const hilangken = () => {
    modalRadiologiSendRIS.value = false
}

const onCellEditComplete = (event) => {
    const { data, column, newValue, oldValue } = event;
    const rowKey = data.id; // Assuming `id` is the unique identifier for each row
}

const onPerawatSelected = (event, rowData, field) => {
    const rowKey = rowData.id; // Access the unique key for the row
    rowData[field] = event.value;
    item.dataPerawatSelected.push(event.value.value);

    // rowData['dokter_display'] = event.value.label;
}

async function fetchTindakan(filter: any) {
    let query = ''
    if (filter) {
        query = filter.toLowerCase()
    }

    const response = await useApi().get(
        `/tindakan/list-tindakan?name= ${query}&limit=10&idruangan=${item.RUANGAN_LAST}`)

    return response.data.map((item: any) => {
        return { value: item.id, label: item.namaproduk, default: item }
    })
}
const fetchBill = async () => {
    isLoadingBill.value = true
    dataSource.value = []

    await useApi().get(`/kasir/billing?norec_pd=${item.pilihRegistrasi.norec_pd}&istindakan=true&ruanganid=${item.pilihRegistrasi?.objectruanganfk}`).then(async (response: any) => {
        dataSource.value = response.detail

        // item.DIBAYAR = response.dibayar
        // item.SISA = response.sisa
        item.TOTAL_BILL = response.total
        item.listruangan = response.list_ruangan
        console.log(item.listruangan)
        // item.DEPOSIT = response.deposit
        // item.DISKON = response.diskon
        // item.DIKLAIM = response.klaim
        // item.PENGEMBALIAN = response.pengembalian
        // item.IURBAYAR = response.iurbayar
        // item.length = response.length
        // item.tarif_inacbg = response.tarif_inacbg
        isLoadingBill.value = false
    })
}

const groupRuang = (result: any) => {
    let sama = false
    let arrGroup: any = [];
    for (let i = 0; i < result.length; i++) {
        sama = false
        for (let x = 0; x < arrGroup.length; x++) {
            if (arrGroup[x].namadepartemen == result[i].namadepartemen) {
                sama = true;
            }
        }
        if (sama == false) {
            let data = {
                'namadepartemen': result[i].namadepartemen,
                'details': [],
            }
            arrGroup.push(data)
        }
    }
    for (let x = 0; x < arrGroup.length; x++) {
        const element = arrGroup[x];
        for (let y = 0; y < result.length; y++) {
            const element2 = result[y];
            if (element.namadepartemen == element2.namadepartemen) {
                element.details.push(element2)
            }
        }

    }
    return arrGroup
}
const checkedAll = (e: any) => {
    modelCheck.value = []
    listChecked.value = []
    if (e) {
        dataSource.value.forEach((e: any) => {
            e.details.forEach((f: any) => {
                listChecked.value.push(f)
                modelCheck.value[f.norec] = true
            });
        });
    }
}
const checkedItems = () => {
    let objectK = Object.keys(modelCheck.value)
    for (let x = 0; x < objectK.length; x++) {
        const element = objectK[x];
        if (modelCheck.value[element] == true) {
            for (var i = 0; i < dataSource.value.length; i++) {
                const element2 = dataSource.value[i];
                for (let xx = 0; xx < element2.details.length; xx++) {
                    const element3 = element2.details[xx];
                    if (element3.norec == element) {
                        for (var z = 0; z < listChecked.value.length; z++) {
                            const element4 = listChecked.value[z];
                            if (element4.norec == element3.norec) {
                                listChecked.value.splice(z, 1)
                            }
                        }
                        listChecked.value.push(element3)
                    }
                }

            }
        } else {
            for (var i = 0; i < dataSource.value.length; i++) {
                const element2 = dataSource.value[i];
                for (let xx = 0; xx < element2.details.length; xx++) {
                    const element3 = element2.details[xx];
                    if (element3.norec == element) {
                        for (var z = 0; z < listChecked.value.length; z++) {
                            const element4 = listChecked.value[z];
                            if (element4.norec == element3.norec) {
                                listChecked.value.splice(z, 1)
                            }
                        }
                    }
                }
            }
        }
    }
}

const hapusTindakan = async (e: any) => {
    if (isClosedPasien.value == true) {
        useToaster().error('Pasien sudah di closing, tidak bisa menambahkan tindakan');
        return;
    }
    confirm.require({
        group: 'positionDialog',
        message: H.alertHapus(),
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: () => {
            if (e.strukfk != null) {
                H.alert('error', H.alertKasir())
                return
            }
            if (e.strukresepfk != null) {
                H.alert('error', 'Resep hanya bisa dihapus di Farmasi')
                return
            }
            var objSave = {
                'data':
                    [{
                        'norec_pp': e.norec,
                        'namaproduk': e.namaproduk,
                        'namaruangan': e.namaruangan,
                    }],
                'nocm': pasien.value.nocm,
                'namapasien': pasien.value.namapasien,
                'noregistrasi': pasien.value.noregistrasi,
            }
            nextHapus(objSave)
        },
        reject: () => {
        }
    });

}

function onlyNumber(evt: any) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();
    } else {
        return true;
    }
}

function citosss(cito: any, data: any) {
    if (data.norec_pp && data.norec_pp != '') {
        dataResult.value.forEach((toss) => {
            if (toss.norec_pp === data.norec_pp) {
                toss.iscito = cito;

                // for save harga awal
                if (!toss.hargasebelumcito) {
                    toss.hargasebelumcito = parseFloat(toss.hargasatuan);
                    toss.komponenharga.forEach((komp) => {
                        if (!komp.komphargasebelumcito) {
                            if (komp.komponenharga === 'JASA PELAYANAN') {
                                komp.komphargasebelumcito = parseFloat(komp.hargasatuan);
                            }
                        }
                    })
                }

                if (cito === true) {
                    let jasacito = Math.floor(parseFloat(toss.hargasebelumcito) * item.nilaiCito);
                    toss.hargasatuan = parseFloat(toss.hargasebelumcito) + jasacito;
                    toss.komponenharga.forEach((komp) => {
                        if (komp.komponenharga === 'JASA PELAYANAN') {
                            komp.hargasatuan = parseFloat(komp.komphargasebelumcito) + jasacito;
                        }
                    })
                } else {
                    toss.hargasatuan = toss.hargasebelumcito;
                    toss.komponenharga.forEach((komp) => {
                        if (komp.komponenharga === 'JASA PELAYANAN') {
                            komp.hargasatuan = komp.komphargasebelumcito;
                            delete komp.komphargasebelumcito
                        }
                    })
                    delete toss.hargasebelumcito
                }
            }
        });
    } else {
        dataResult.value.forEach((toss2) => {
            if (toss2.id === data.id) {
                toss2.iscito = cito;

                // for save harga awal
                if (!toss2.hargasebelumcito) {
                    toss2.hargasebelumcito = parseFloat(toss2.hargasatuan);
                    toss2.komponenharga.forEach((komp) => {
                        if (!komp.komphargasebelumcito) {
                            if (komp.komponenharga === 'JASA PELAYANAN') {
                                komp.komphargasebelumcito = parseFloat(komp.hargasatuan);
                            }
                        }
                    })
                }

                if (cito === true) {
                    let jasacito = Math.floor(parseFloat(toss2.hargasebelumcito) * item.nilaiCito);
                    toss2.hargasatuan = parseFloat(toss2.hargasebelumcito) + jasacito;
                    toss2.komponenharga.forEach((komp) => {
                        if (komp.komponenharga === 'JASA PELAYANAN') {
                            komp.hargasatuan = parseFloat(komp.komphargasebelumcito) + jasacito;
                        }
                    })
                } else {
                    toss2.hargasatuan = toss2.hargasebelumcito;
                    toss2.komponenharga.forEach((komp) => {
                        if (komp.komponenharga === 'JASA PELAYANAN') {
                            komp.hargasatuan = komp.komphargasebelumcito;
                            delete komp.komphargasebelumcito
                        }
                    })
                    delete toss2.hargasebelumcito
                }
            }
        });
    }
};


const hapussskii = (idProduk: any) => {
    // console.log(idProduk)
    let indexToRemove = null
    dataSelectedTindakan.value.forEach((item, index) => {
        if (item.id === idProduk) {
            indexToRemove = index;
        }
    });
    if (indexToRemove !== null) {
        dataSelectedTindakan.value.splice(indexToRemove, 1);
    }

}

const guessTindakan = (e) => {
    if (e.data.norec_pp) {
        var objSave = {
            'data': [{
                'norec_pp': e.data.norec_pp,
                'namaproduk': e.data.namaproduk,
                'namaruangan': ''
            }],
            'nocm': pasien.value.nocm,
            'namapasien': pasien.value.namapasien,
            'noregistrasi': pasien.value.noregistrasi
        }
        nextHapus(objSave)
        removeSelectedTindakan(e)
    } else {
        removeSelectedTindakan(e)

    }
}

const nextHapus = (objSave: any) => {
    isLoadingBill.value = true
    useApi().post(
        `/kasir/billing/hapus-tindakan`, objSave).then((response: any) => {
            isLoadingBill.value = false
            fetchBill()
        }).catch((e: any) => {
            isLoadingBill.value = false
        })
}
const detailPetugas = async (e: any) => {
    dataSelect.value = {
        ...e,
        jenisPelaksana: e.jenisPelaksana || null,
        pegawai: e.pegawai || [],
    };

    await loadPetugas(e);
    modalPetugas.value = true;
};
const loadPetugas = async (e: any) => {
    isLoadingPop.value = true
    dataSourcePetugas.value = []
    await useApi().get(
        `/kasir/billing/petugas-tindakan?norec=${e.norec}`).then((response: any) => {
            isLoadingPop.value = false
            dataSourcePetugas.value = response
        }).catch((e: any) => {
            isLoadingPop.value = false
        })

    console.log('oper', dataSourcePetugas.value);

}
const detailKomponen = (e: any) => {
    delete item.norecPPD
    dataSelect.value = {}
    dataSelect.value = e
    modalKomponen.value = true
    loadKomponen(e)
}
const loadKomponen = async (e: any) => {
    isLoadingPop.value = true
    dataSourceKom.value = []
    item.totalKomponen = 0
    await useApi().get(
        `/kasir/billing/detail-tindakan?norec=${e.norec}`).then((response: any) => {
            isLoadingPop.value = false
            item.totalKomponen = response.total
            dataSourceKom.value = response.data
        }).catch((e: any) => {
            isLoadingPop.value = false
        })
}
const fetchPaket = async () => {
    modalPaket.value = true
    await useApi().get(
        `/tindakan/list-paket?flag=tindakan`).then((response: any) => {
            dataSourcePaket.value = response
        }).catch((e: any) => {

        })
}

// const fetchAKun = async (filter: any) => {
//   let query = ''
//   if (filter) {
//     query = filter.query
//   }
//   const response = await useApi().get(`/akuntansi/get-data-combo-coa-part?name= ${query}&limit=10`)
//   d_Akun.value = response
// }

const onDoctorSelect = (event: any) => {
    selectedDoctor.value = event.value; // Update local state with the selected doctor object
};

const fetchAllDokter = async (filter: any) => {
    let query = ''
    let limit = '5000'
    if (filter) {
        query = filter.query
        limit = '25';
    }
    await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&query=${query}&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=${limit}`).then((response: any) => {
            // d_pegawai3.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
            d_pegawai3.value = response;
        }).catch((e: any) => {

        })
}
const fetchAllDokter2 = async (filter: any) => {
    let query = ''
    let limit = '5000'
    if (filter) {
        query = filter.query
        limit = '25';
    }
    await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&query=${query}&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=${limit}`).then((response: any) => {
            // d_pegawai3.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
            d_pegawai3.value = response;
        }).catch((e: any) => {

        })
}

const fetchPegawai = async (filter: any) => {
    let data = filter ? filter.query : ''
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${data}&limit=20`).then((response) => {
        // d_pegawai4.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
        d_pegawai4.value = response
    })
}
const tambahPaket = async (e: any) => {
    try {
        // isPaketTambah.value = true
        e.isLoading = true;
        let totalHargaDefault = 0;

        let ygini = item.registrasi;
        if (item.pilihRegistrasi != undefined) {
            ygini = item.pilihRegistrasi;
        }

        let kelasdipake = ygini.objectkelasfk
        if (ygini.kelasrawatfk != null && ygini.iskelastitip != null) {
            kelasdipake = ygini.kelasrawatfk
        }

        for (const TINDAKAN of e.details) {
            const response = await useApi().get(
                '/tindakan/list-tindakan-komponen?idRuangan=' + ygini.objectruanganfk
                + '&idKelas=' + kelasdipake
                + '&idProduk=' + TINDAKAN.objectprodukfk
                + '&idJenisPelayanan=' + ygini.jenispelayananfk
                + '&objectkebangsaanfk=' + props.pasien.objectkebangsaanfk
                // + '&idPenjamin=' + item.registrasi.objectrekananfk
            );
            console.log('Response API:', response);
            if (!response.harga || response.harga.hargasatuan == null) {
                H.alert('error', `Harga untuk tindakan ${TINDAKAN.namaproduk} tidak ditemukan.`);
                break;
            }
            if (!response.komponen || response.komponen.length === 0) {
                H.alert('error', `Komponen untuk tindakan ${TINDAKAN.namaproduk} tidak ditemukan.`);
                break;
            }
            totalHargaDefault += parseFloat(response.harga.hargasatuan);
        }
        // Iterasi kedua untuk memproses tindakan dan komponen
        for (const TINDAKAN of e.details) {
            const response = await useApi().get(
                '/tindakan/list-tindakan-komponen?idRuangan=' + ygini.objectruanganfk
                + '&idKelas=' + kelasdipake
                + '&idProduk=' + TINDAKAN.objectprodukfk
                + '&idJenisPelayanan=' + ygini.jenispelayananfk
                + '&objectkebangsaanfk=' + props.pasien.objectkebangsaanfk
                // + '&idPenjamin=' + item.registrasi.objectrekananfk
            );
            console.log('Response API:', response);
            if (!response.harga || response.harga.hargasatuan == null) {
                H.alert('error', `Harga untuk tindakan ${TINDAKAN.namaproduk} tidak ditemukan.`);
                break;
            }
            if (!response.komponen || response.komponen.length === 0) {
                H.alert('error', `Komponen untuk tindakan ${TINDAKAN.namaproduk} tidak ditemukan.`);
                break;
            }
            let hargasatuan = parseFloat(response.harga.hargasatuan);
            let listKomponen = [...response.komponen];
            if (TINDAKAN.hargapaket != 0 && TINDAKAN.hargapaket < totalHargaDefault) {
                hargasatuan = (TINDAKAN.hargapaket / totalHargaDefault) * hargasatuan;
                // Update harga satuan pada komponen
                for (const komponen of listKomponen) {
                    komponen.hargasatuan = (hargasatuan / totalHargaDefault) * parseFloat(komponen.hargasatuan);
                    komponen.hargasatuan = parseFloat(komponen.hargasatuan.toFixed(2));
                }
            }
            // Petugas
            let petugas = [];
            let pelaksana = {};
            let jenispet = d_JenisPelaksana.value.find(jp => jp.value === item2.jenisPelaksana)?.label || '';
            let namaPegawai = d_Pegawai2.value.find(pg => pg.value === item2.pegawai)?.label || '';
            if (TINDAKAN.objectprodukfk == 4168) {
                console.log('HALO 1')
                petugas.push({
                    "objectjenispetugaspefk": item2.jenisPelaksana,
                    "jenispetugaspe": jenispet,
                    "listpegawai": [
                        {
                            'id': item.pilihRegistrasi.objectpegawaifk,
                            'namalengkap': item.pilihRegistrasi.dokter
                        }
                    ]
                });

                pelaksana = {
                    'value': item.pilihRegistrasi.objectpegawaifk,
                    'label': item.pilihRegistrasi.dokter
                }
            }
            if (TINDAKAN.objectprodukfk == 443) {
                console.log('HALO 1.1')
                petugas.push({
                    "objectjenispetugaspefk": item2.jenisPelaksana,
                    "jenispetugaspe": jenispet,
                    "listpegawai": [
                        {
                            'id': item.pilihRegistrasi.objectpegawaifk,
                            'namalengkap': item.pilihRegistrasi.dokter
                        }
                    ]
                });

                pelaksana = {
                    'value': item.pilihRegistrasi.objectpegawaifk,
                    'label': item.pilihRegistrasi.dokter
                }
            }
            if (TINDAKAN.objectprodukfk == 910) {
                console.log('HALO 2')
                petugas.push({
                    "objectjenispetugaspefk": item2.jenisPelaksana,
                    "jenispetugaspe": jenispet,
                    "listpegawai": [
                        {
                            'id': 384,
                            'namalengkap': 'dr. IDA AYU PUTU ASTHI DAMAYANTI, SpKJ'
                        }
                    ]
                });

                pelaksana = {
                    'value': 384,
                    'label': 'dr. IDA AYU PUTU ASTHI DAMAYANTI, SpKJ'
                }
            }
            if (TINDAKAN.objectprodukfk == 914) {
                console.log('HALO 3')

                petugas.push({
                    "objectjenispetugaspefk": item2.jenisPelaksana,
                    "jenispetugaspe": jenispet,
                    "listpegawai": [
                        {
                            'id': 384,
                            'namalengkap': 'dr. IDA AYU PUTU ASTHI DAMAYANTI, SpKJ'
                        }
                    ]
                });

                pelaksana = {
                    'value': 384,
                    'label': 'dr. IDA AYU PUTU ASTHI DAMAYANTI, SpKJ'
                }
            }
            if (TINDAKAN.objectprodukfk == 3223) {
                console.log('HALO 4')

                petugas.push({
                    "objectjenispetugaspefk": item2.jenisPelaksana,
                    "jenispetugaspe": jenispet,
                    "listpegawai": [
                        {
                            'id': item.pilihRegistrasi.objectpegawaifk,
                            'namalengkap': item.pilihRegistrasi.dokter
                        }
                    ]
                });

                pelaksana = {
                    'value': item.pilihRegistrasi.objectpegawaifk,
                    'label': item.pilihRegistrasi.dokter
                }
            }
            if (TINDAKAN.objectprodukfk == 4167) {
                console.log('HALO 4')

                petugas.push({
                    "objectjenispetugaspefk": item2.jenisPelaksana,
                    "jenispetugaspe": jenispet,
                    "listpegawai": [
                        {
                            'id': item.pilihRegistrasi.objectpegawaifk,
                            'namalengkap': item.pilihRegistrasi.dokter
                        }
                    ]
                });

                pelaksana = {
                    'value': item.pilihRegistrasi.objectpegawaifk,
                    'label': item.pilihRegistrasi.dokter
                }
            }
            if (TINDAKAN.objectprodukfk == 3399 || TINDAKAN.objectprodukfk == 3400 || TINDAKAN.objectprodukfk == 3401 || TINDAKAN.objectprodukfk == 3403 || TINDAKAN.objectprodukfk == 4103 || TINDAKAN.objectprodukfk == 3510 || TINDAKAN.objectprodukfk == 10600) {
                console.log('HALO 5')

                petugas.push({
                    "objectjenispetugaspefk": item2.jenisPelaksana,
                    "jenispetugaspe": jenispet,
                    "listpegawai": [
                        {
                            'id': 344,
                            'namalengkap': 'dr. A.A. MADE SEDANA PUTRA, Sp.PK'
                        }
                    ]
                });

                pelaksana = {
                    'value': 344,
                    'label': 'dr. A.A. MADE SEDANA PUTRA, Sp.PK'
                }
            }

            console.log("petugas woi", petugas);
            console.log("pelaksana woi", pelaksana);
            let nomor = data2.value.length === 0 ? 1 : data2.value.length + 1;
            // Data yang akan ditambahkan
            let data: any = {
                'no': nomor,
                'tanggal': H.formatDate(item2.tglpelayanan, 'YYYY-MM-DD HH:mm'),
                'produkfk': TINDAKAN.objectprodukfk,
                'objectruanganfk': TINDAKAN.objectruanganfk,
                'isPaketTambah': true,
                'namaproduk': TINDAKAN.namaproduk,
                'hargasatuan': hargasatuan,
                'jumlah': 1,
                'pelaksana': pelaksana,
                'komponenharga': listKomponen,
                'iscito': item.iscito || false,
                'icon': item.iscito ? "<i class='iconify is-success' data-icon='feather:check-circle' aria-hidden='true'></i>" : "<i class='iconify is-danger' data-icon='feather:x-circle' aria-hidden='true'></i>",
                'jasacito': 0,
                'isparamedis': item.isparamedis || false,
                'diskon': 0,
                'subtotal': parseFloat(hargasatuan) * 1,
                'id': TINDAKAN.objectprodukfk,
                'pelayananpetugas': petugas
            };
            data2.value.push(data);
            isDetail.value[data2.value.length] = true;
            dataSourceTindakan.value = data2.value;
            dataResult.value.push(data);
            console.log('Data Result yang akan ditambahkan:', dataResult.value);
            H.alert('info', `${TINDAKAN.namaproduk} berhasil ditambahkan.`);
        }
        countTotal();
        clearInput();
    } catch (error) {
        console.error('Error saat memproses tindakan:', error);
        H.alert('error', 'Terjadi kesalahan saat memproses data.');
    } finally {
        e.isLoading = false;
        // isPaket.value = false;
        modalPaket.value = false;
    }
};
watch(
    () => selectedTabs,
    (value) => {
        activeValue.value = value
    },
    () => dataSelectedTindakan,
)
watch(() => dataResult.value.length, (newValue) => {
    if (newValue != dataRiwayat.value.length) {
        disabledSave.value = false
        dataRiwayat.value = []
    }
    if (newValue == dataRiwayat.value.length) {
        disabledSave.value = false
    }
})

watch(activeValue, (value: any) => {
    if (value == 2) {
        fetchBill()
    } else {
        getTempTindakan()
    }
    emit('update:selected', value)
})

watch(() => isPaket.value, (newValue, oldValue) => {
    if (newValue == true) {
        fetchPaket()
    }
})

onBeforeMount(() => {
    try {
        const noregistrasi = props.registrasi ? props.registrasi.noregistrasi : ''
        let cache = H.cacheEMR().get(`TAB~${noregistrasi}~${route.name}`)
        if (cache) {
            dataSource.value = cache.dataSource
            data2.value = cache.data2
            countTotal()
        }
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});
onBeforeRouteLeave((to, from) => {
    try {
        const noregistrasi = props.registrasi ? props.registrasi.noregistrasi : ''
        let rouutename = from?.name
        if (noregistrasi) {
            H.cacheEMR().set(`TAB~${noregistrasi}~${rouutename}`, {
                'dataSource': dataSource.value,
                'data2': data2.value,
            })
        }
        next();
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
});

async function getTempTindakan() {
    let arrTindakan = [];
    isLoading.value = true
    let noreg = '';
    let filterTindakanOperasi = activeValue.value == 3 ? `&tindakanOperasi=true` : ''
    let filterRiwayat = activeValue.value == 1 ? `&riwayat=true` : ''
    dataResult.value = [];
    let detailProduk = [];

    await useApi().get(`/kasir/billing?norec_pd=${item.pilihRegistrasi.norec_pd}&istindakan=true&ruanganid=${item.pilihRegistrasi?.objectruanganfk}${filterTindakanOperasi}${filterRiwayat}`).then(async (response: any) => {
        for (let groupkey = 0; groupkey < response.detail.length; groupkey++) {
            const groupdata = response.detail[groupkey];
            for (let keydata = 0; keydata < groupdata.details.length; keydata++) {
                const detail = groupdata.details[keydata];
                let komharga = [];
                await useApi().get(`/kasir/billing/detail-tindakan?norec=${detail.norec}`).then((resHarga: any) => { komharga = resHarga.data })

                if (activeValue.value == 3 && detail.listpetugas.length) {
                    const listWithNorec = detail.listpetugas.map((item: any) => ({ ...item, norec_pp: detail.norec }));
                    list_PPP.value.push(...listWithNorec);
                }

                let pemeriksa = {};

                // Khusus untuk bedah
                let dokteroperator = null;
                let dokteroperator1 = null;
                let dokteroperator2 = null;
                let dokteroperator3 = null;
                let dokteroperator4 = null;
                let dataPerawatanastesi = null;
                let dataPenataanastesi = null;
                let dataPenataanastesiperawat = null;

                if (detail.objectpemeriksa && detail.pemeriksa) {
                    pemeriksa = {
                        value: detail.objectpemeriksa,
                        label: detail.pemeriksa,
                    };
                }

                detail.listpetugas.forEach(function (val) {
                    //? Ga dipake (Dokter Anak)
                    if (val.idjenispelaksana == 27 && val.value) {
                        dokteroperator = {
                            value: val.value,
                            label: val.label
                        };
                    }
                    if (val.idjenispelaksana == 6 && val.value) {
                        dokteroperator1 = {
                            value: val.value,
                            label: val.label
                        };
                    }
                    if (val.idjenispelaksana == 5 && val.value) {
                        dokteroperator2 = {
                            value: val.value,
                            label: val.label
                        };
                    }
                    if (val.idjenispelaksana == 28 && val.value) {
                        dokteroperator3 = {
                            value: val.value,
                            label: val.label
                        };
                    }
                    if (val.idjenispelaksana == 29 && val.value) {
                        dokteroperator4 = {
                            value: val.value,
                            label: val.label
                        };
                    }
                    if (val.idjenispelaksana == 30 && val.value) {
                        dataPerawatanastesi = {
                            value: val.value,
                            label: val.label
                        };
                    }
                    if (val.idjenispelaksana == 31 && val.value) {
                        dataPenataanastesi = {
                            value: val.value,
                            label: val.label
                        };
                    }
                    if (val.idjenispelaksana == 32 && val.value) {
                        dataPenataanastesiperawat = {
                            value: val.value,
                            label: val.label
                        };
                    }
                });

                // 4167 && 8150
                if (detail.idjasaproduk == 4167) {
                    if (!detail.objectpemeriksa && !detail.pemeriksa) {
                        pemeriksa = {
                            value: 8150,
                            label: 'NONE'
                        }
                    }
                }

                detailProduk.push({
                    id: detail.idjasaproduk,
                    namaproduk: detail.namaproduk,
                    objectruanganfk: detail.idruangannya ?? null,
                    hargasatuan: detail.hargasatuan,
                    hargasebelumcito: detail.hargasebelumcito,
                    jumlah: detail.jumlah,
                    komponenharga: komharga,
                    tanggal: H.formatDate(detail.tglpelayanan, 'DD-MM-YYYY'),
                    norec_pp: detail.norec,
                    kelasfk: detail.objectkelasfk,
                    norec_apd: detail.norec_apd,
                    tglregistrasi: detail.tglregistrasi,
                    isPaketTambah: false,
                    isparamedis: 0,
                    isNewAdded: true,
                    jenispelaksana: { value: detail.idjenispelaksana, label: detail.jenispelaksana },
                    pelaksana: pemeriksa,
                    dataDokteroperator: dokteroperator,
                    dataDokteroperator1: dokteroperator1,
                    dataDokteroperator2: dokteroperator2,
                    dataDokteroperator3: dokteroperator3,
                    dataDokteroperator4: dokteroperator4,
                    dataPerawatanastesi: dataPerawatanastesi,
                    dataPenataanastesi: dataPenataanastesi,
                    dataPenataanastesiperawat: dataPenataanastesiperawat,
                    asa: detail.asa ?? 0,
                    diskon: detail.hargadiscount,
                    iscito: detail.iscito,
                    produkfk: detail.idjasaproduk,
                    tglpelayanan: H.formatDate(detail.tglpelayanan, 'DD-MM-YYYY'),
                    dokter: detail.iddokterpemeriksa,
                    id_hnp: detail.id_hnp
                })
            }
        }
        isLoading.value = false
        dataResult.value = detailProduk;
    })
}

async function removeSelectedTindakan(event) {
    isLoading.value = true;
    isLoadingBill.value = true

    try {
        dataResult.value.splice(event.index, 1);
    } catch (err) {
        console.error(err);
        isLoading.value = false;
        isLoadingBill.value = false
    } finally {
        isLoading.value = false;
        isLoadingBill.value = false
    }
}

async function statusClosingPasien(key: any) {
    const response = await useApi().get(`general/get-status-close?key=${key}`);
    console.log("SCPasien", response)
    let closingPelayanaData = {
        key: key,
        status: response.status,
    };
    H.cacheHelper().set('status_closing', closingPelayanaData);
    if (response.status == true) {
        // isClosedPasien.value = true;
        H.alert("warning", 'Pelayanan yang sudah di Closing tidak bisa di ubah !');
        return;
    }
}


onMounted(async () => {
    // await fetchPegawai()
    await pasienByID(ID_PASIEN)
    await statusClosingPasien(NOREC_PD);
    await dropdownList()
    getTempTindakan()
    getListDokter()
})

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';

.form-layout {
    max-width: 1200px;
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item .dot {

    margin: 0 10px;
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
    // content: "";
    content: none;
    position: absolute;
    top: 63px;
    left: 97px;
    height: 100%;
    width: 2px;
    background: var(--placeholder);
    z-index: 0;
}

.is-rounded-select {
    .p-calendar {
        border-radius: 20px !important;

        .p-inputtext {
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
        }
    }

    .p-calendar-w-btn .p-datepicker-trigger {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 15px;
        border-bottom-right-radius: 15px;
    }
}

.field>label {
    font-family: var(--font);
    font-size: 0.9rem;
    color: var(--light-text) !important;
    font-weight: 400;
}
</style>