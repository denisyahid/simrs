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
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton v-if="activeValue !== 3"  type="button" rounded outlined color="primary" raised icon="feather:save" :disabled="disabledSave"
                                    :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                                <VButton v-if="activeValue !== 1 && activeValue !== 2" type="button" rounded outlined color="primary" raised icon="feather:save" :disabled="disabledSave"
                                    :loading="isLoading" @click="simpanop()"> Simpan
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
                            <!-- <div class="column is-12" v-if="!isLoadingPasien">
                                <HeadPasien :pasien="pasien" class="m-3" />
                            </div> -->
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-12 ">
                            <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
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
                                                <VField horizontal label="Registrasi" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.pilihRegistrasi" :options="d_Registrasi"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @change="getRegistrasi(item.pilihRegistrasi)" disabled/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VButton type="button" color="info" raised rounded
                                                    icon="feather:plus-circle" class=" mr-3 mt-0 mb-0" @click="modalInput = true">
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
                                                <VField horizontal label="DPJP" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.dataDokterSelect" :options="d_pegawai3"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @complete="fetchAllDokter2($event)"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <div class="timeline-wrapper" v-if="dataResult.length > 0">
                                                    <div class="timeline-header text-center">
                                                        <VTag
                                                            color="danger"
                                                            label="DATA BELUM TERSIMPAN"
                                                            curved
                                                            class="mb-3"
                                                        />
                                                    </div>
                                                    <div class="timeline-wrapper-inner pt-0">
                                                        <DataTable :value="dataResult" editMode="cell" @cell-edit-complete="onCellEditComplete"
                                                            :pt="{
                                                                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                                                                column: {
                                                                    bodycell: ({ state }) => ({
                                                                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                                                                    })
                                                                }
                                                            }"
                                                            scrollHeight="600px"
                                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                                                            <Column header="#">
                                                                <template #body="slotProps">
                                                                    <VIconButton
                                                                        color="danger"
                                                                        light
                                                                        raised
                                                                        circle
                                                                        icon="lucide:x"
                                                                        @click="onTindakanUnselected(slotProps)"
                                                                    />
                                                                    <!-- <VButton type="button" icon="pi pi-trash" class="mr-2" color="info" circle
                                                                        outlined raised v-tooltip.top="'Hapus'" @click="onTindakanUnselected(slotProps)">
                                                                    </VButton> -->
                                                                </template>
                                                            </Column>
                                                            <Column field="id" header="ID Jasa"></Column>
                                                            <Column field="namaproduk" header="Deskripsi"></Column>
                                                           <Column field="jumlah" header="Qty">
                                                              <template #body="{ data, field }">
                                                                  <InputText
                                                                      v-model="data[field]"
                                                                      type="text" @keypress="onlyNumber($event)"/>
                                                              </template>
                                                          </Column>
                                                            <Column field="hargasatuan" header="Tarif">
                                                                <template #body="slotProps">
                                                                    {{ H.formatRp(slotProps.data.hargasatuan,'Rp. ')}}
                                                                </template>
                                                            </Column>
                                                            <!-- <Column field="dokter" header="DPJP" style="width: 20%">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="item.dataDokterSelect" :suggestions="d_pegawai3" @complete="fetchAllDokter($event)"
                                                                    :optionLabel="'label'" :dropdown="true" :appendTo="'body'" :minLength="3"
                                                                    :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                    item-text="label" item-value="value" return-object
                                                                    @item-select="data[field] = $event.value.value; data['dokter_display'] = $event.value.label"
                                                                    />
                                                                </template>
                                                                <template #empty>
                                                                    klik disini untuk merubah dokter
                                                                </template>
                                                            </Column> -->
                                                            <Column field="pelaksana" header="Pelaksana" style="width: 20%">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]" :suggestions="d_pegawai4" @complete="fetchPegawai($event)"
                                                                    :optionLabel="'label'" :dropdown="true" :appendTo="'body'" :minLength="3"
                                                                    :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                    item-text="label" item-value="value" return-object
                                                                    @item-select="onPerawatSelected($event, data, field )"
                                                                    />
                                                                </template>
                                                            </Column>
                                                            <!-- <Column field="dokter_display" header="Dokter" style="width: 20%">
                                                                <template #body="slotProps">
                                                                    {{ slotProps.data.dokter_display }}
                                                                </template>
                                                                <template #empty>
                                                                    klik disini untuk merubah dokter
                                                                </template>
                                                            </Column> -->
                                                            <!-- <Column field="display_dokter" header="Nama Dokter" style="width: 20%"></Column> -->
                                                            <Column field="tanggal" header="Tanggal" style="width: 15%"></Column>
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
                                                    dataSourcefiltered.length }} - (Rp. {{ H.formatRp(item.TOTAL_BILL, '')}})
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
                                                            v-for="(items, index)  in dataSourcefiltered" :key="index">
                                                            <tr>
                                                                <td colspan="5" class="koneng">
                                                                    {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                                                                </td>
                                                            </tr>
                                                            <tr v-for="(itemsDet, index2)  in items.details" :key="index2">
                                                                <td width="30%">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">{{
                                                                                itemsDet.namaruangan
                                                                            }}</div>
                                                                            <div class="title-layan">{{ itemsDet.namaproduk
                                                                            }} - {{itemsDet.dokterpemeriksa ? itemsDet.dokterpemeriksa : 'Dokter belum di input'}}</div>
                                                                            <div>
                                                                                <VTag
                                                                                    :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                                                                    :label="itemsDet.tglpelayanan" />
                                                                            </div>
                                                                            <div class="title-kelas">{{ itemsDet.namakelas
                                                                            }}
                                                                            </div>
                                                                             <div class="title-kelas">DPJP : {{ itemsDet.dokterpemeriksa
                                                                            }}
                                                                            </div>
                                                                             <div class="title-kelas">Pemeriksa : {{ itemsDet.pemeriksa
                                                                            }}
                                                                            </div>
                                                                             <div class="title-kelas" v-if="itemsDet.isasa1 != 0 && itemsDet.isasa1 != null">ASA : 1</div>
                                                                              <div class="title-kelas" v-if="itemsDet.isasa2 != 0 && itemsDet.isasa2 != null">ASA : 2</div>
                                                                               <div class="title-kelas" v-if="itemsDet.isasa3 != 0 && itemsDet.isasa3 != null">ASA : 3</div>
                                                                                <div class="title-kelas" v-if="itemsDet.isasa4 != 0 && itemsDet.isasa4 != null">ASA : 4</div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">Jasa : {{
                                                                                H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                                                            <div class="title-layan">{{
                                                                                H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}
                                                                            </div>

                                                                            <div class="title-kelas">Diskon : {{
                                                                                H.formatRp(itemsDet.hargadiscount, 'Rp. ')
                                                                            }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">{{ itemsDet.jumlah }}</td>
                                                                <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}
                                                                </td>
                                                                <td class="center" v-if="itemsDet.idruangannya == item.registrasi.objectruanganfk">

                                                                    <VIconButton color="danger" class="mr-2" light raised
                                                                        circle icon="feather:trash"
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
                                                                                    <span>view detail harga tindakan </span>
                                                                                </div>
                                                                            </a>
                                                                        </template>
                                                                    </VDropdown>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <div class="search-results-wrapper"
                                                            v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                                                            ti <div class="search-results-body ">
                                                                <div class="page-placeholder">
                                                                    <div class="placeholder-content">
                                                                        <img class="light-image" style=" max-width: 340px;"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <img class="dark-image" style=" max-width: 340px;"
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
                                    <div class="columns is-multiline p-1" v-if="activeValue == 3 && (item.registrasi.objectruanganfk == 363 || item.registrasi.objectruanganfk == 753)">
                                     <div class="column is-7">
                                                <VField horizontal label="Registrasi" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.pilihRegistrasi" :options="d_Registrasi"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @change="getRegistrasi(item.pilihRegistrasi)" disabled/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                    <div class="column is-7">
                                                <VField horizontal label="Dokter Anak" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.dataDokteroperator" :options="d_pegawai3"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @complete="fetchAllDokter2($event)"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3">
                                                <VButton type="button" color="info" raised rounded
                                                    icon="feather:plus-circle" class=" mr-3 mt-0 mb-0" @click="showModal()">
                                                    Tambah
                                                </VButton>
                                            </div>
                                            <div class="column is-7">
                                                <VField horizontal label="Dokter Anestesi" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.dataDokteroperator1" :options="d_pegawai3"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @complete="fetchAllDokter2($event)"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-7">
                                                <VField horizontal label="Dokter Operator 1" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.dataDokteroperator2" :options="d_pegawai3"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @complete="fetchAllDokter2($event)"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-7">
                                                <VField horizontal label="Dokter Operasi 2" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.dataDokteroperator3" :options="d_pegawai3"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @complete="fetchAllDokter2($event)"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-7">
                                                <VField horizontal label="Dokter Operator 3" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.dataDokteroperator4" :options="d_pegawai3"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @complete="fetchAllDokter2($event)"/>
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-7">
                                                <VField horizontal label="Perawat Anastesi" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.perawatanastesi" :options="d_pegawai3"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @complete="fetchPegawai($event)"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-7">
                                                <VField horizontal label="Penata Anastesi" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                    <Dropdown v-model="item.penataanastesi" :options="d_pegawai3"
                                                        :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                        placeholder="Pilih data" style="width: 100%;" showClear
                                                        :filter="true" @complete="fetchPegawai($event)"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-2">
                                                <VControl class="is-pulled-right">
                                                    <VSwitchBlock v-model="isPaket" label="Paket" color="danger"
                                                        class="is-pulled-right" />
                                                </VControl>
                                            </div>
                                            <div class="column is-12">
                                        <div class="columns">
                                        <div class="column">
                                            <VField>
                                            <VControl>
                                                <VSwitchBlock v-model="item.isasa1" label="ASA 1"
                                                color="danger" @update:modelValue="onSwitchChange('isasa1', $event)" />
                                            </VControl>
                                            </VField>
                                        </div>
                                        <div class="column">
                                            <VField>
                                            <VControl>
                                                <VSwitchBlock v-model="item.isasa2" label="ASA 2" color="warning"
                                                @update:modelValue="onSwitchChange('isasa2', $event)" />
                                            </VControl>
                                            </VField>
                                        </div>
                                        <div class="column">
                                            <VField>
                                            <VControl>
                                                <VSwitchBlock  v-model="item.isasa3"  label="ASA 3"  color="success"
                                                @update:modelValue="onSwitchChange('isasa3', $event)" />
                                            </VControl>
                                            </VField>
                                        </div>
                                        <div class="column">
                                            <VField>
                                            <VControl>
                                                <VSwitchBlock  v-model="item.isasa4"  label="ASA 4"  color="info"
                                                @update:modelValue="onSwitchChange('isasa4', $event)"  />
                                            </VControl>
                                            </VField>
                                        </div>
                                        </div>
                                    </div>
                                        <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                                                  <!-- <div class="columns is-gapless">
                                                      <div class="column is-3">
                                                          <VField label="Jenis Pelaksana" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                                              <VControl icon="feather:plus-circle" fullwidth>
                                                                  <Dropdown v-model="item.jenisPelaksana" :options="d_JenisPelaksana"
                                                                      :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                                      placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                                                                      @change="changeJenis(item)" />
                                                              </VControl>
                                                          </VField>
                                                      </div>
                                                      <div class="column is-6" style="margin-left: 25px">
                                                          <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                                              <VLabel>Pegawai</VLabel>
                                                              <VControl icon="feather:user" fullwidth :loading="isLoadChange" class="prime-auto-select">
                                                                  <MultiSelect v-model="item.pegawai" display="chip" class="w-100 is-rounded"
                                                                      :options="item.d_Pegawai" optionLabel="label" optionValue="value" filter
                                                                      placeholder="Pilih Data" :maxSelectedLabels="3" />
                                                              </VControl>
                                                          </VField>
                                                      </div>
                                                      <div class="column is-1 mt-3">
                                                          <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                                                              icon="feather:trash" @click="removeItem(index)" color="danger">
                                                          </VIconButton>
                                                      </div>

                                                      <div class="column is-1 is-flex mt-3" >
                                                          <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                                                              @click="addNewItem()"> Tambah Pelaksana
                                                          </VButton>
                                                      </div>
                                                  </div> -->
                                              </div>

                                            <div class="column is-12">
                                                <div class="timeline-wrapper" v-if="dataResult.length > 0">
                                                    <div class="timeline-header"></div>
                                                    <div class="timeline-wrapper-inner pt-0">
                                                        <DataTable :value="dataResult" editMode="cell" @cell-edit-complete="onCellEditComplete"
                                                            :pt="{
                                                                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                                                                column: {
                                                                    bodycell: ({ state }) => ({
                                                                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                                                                    })
                                                                }
                                                            }"
                                                            scrollHeight="600px"
                                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                                                            <Column header="#">
                                                                <template #body="slotProps">
                                                                    <VIconButton
                                                                        color="danger"
                                                                        light
                                                                        raised
                                                                        circle
                                                                        icon="lucide:x"
                                                                        @click="onTindakanUnselected(slotProps)"
                                                                    />
                                                                    <!-- <VButton type="button" icon="pi pi-trash" class="mr-2" color="info" circle
                                                                        outlined raised v-tooltip.top="'Hapus'" @click="onTindakanUnselected(slotProps)">
                                                                    </VButton> -->
                                                                </template>
                                                            </Column>
                                                            <Column field="id" header="ID Jasa"></Column>
                                                            <Column field="namaproduk" header="Deskripsi"></Column>
                                                            <Column field="jumlah" header="Qty">
                                                                <template #body="{ data, field }">
                                                                    <InputText v-model="data[field]" @keypress="onlyNumber($event)"/>
                                                                </template>
                                                            </Column>
                                                            <Column field="hargasatuan" header="Tarif">
                                                                <template #body="slotProps">
                                                                    {{ H.formatRp(slotProps.data.hargasatuan,'Rp. ')}}
                                                                </template>
                                                            </Column>
                                                            <!-- <Column field="dokter" header="DPJP" style="width: 20%">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="item.dataDokterSelect" :suggestions="d_pegawai3" @complete="fetchAllDokter($event)"
                                                                    :optionLabel="'label'" :dropdown="true" :appendTo="'body'" :minLength="3"
                                                                    :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                    item-text="label" item-value="value" return-object
                                                                    @item-select="data[field] = $event.value.value; data['dokter_display'] = $event.value.label"
                                                                    />
                                                                </template>
                                                                <template #empty>
                                                                    klik disini untuk merubah dokter
                                                                </template>
                                                            </Column> -->
                                                            <!-- <Column field="pelaksana" header="Pelaksana" style="width: 20%">
                                                                <template #body="{ data, field }">
                                                                    <AutoComplete v-model="data[field]" :suggestions="d_pegawai4" @complete="fetchPegawai($event)"
                                                                    :optionLabel="'label'" :dropdown="true" :appendTo="'body'" :minLength="3"
                                                                    :field="'label'" :loadingIcon="'pi pi-spinner'"
                                                                    item-text="label" item-value="label" return-object
                                                                    @item-select="onPerawatSelected($event, data, field )"
                                                                    />
                                                                </template>
                                                            </Column> -->
                                                            <!-- <Column field="dokter_display" header="Dokter" style="width: 20%">
                                                                <template #body="slotProps">
                                                                    {{ slotProps.data.dokter_display }}
                                                                </template>
                                                                <template #empty>
                                                                    klik disini untuk merubah dokter
                                                                </template>
                                                            </Column> -->
                                                            <!-- <Column field="display_dokter" header="Nama Dokter" style="width: 20%"></Column> -->
                                                            <Column field="tanggal" header="Tanggal" style="width: 15%"></Column>
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
                                                    dataSourcefiltered.length }} - (Rp. {{ H.formatRp(item.TOTAL_BILL, '')}})
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
                                                            v-for="(items, index)  in dataSourcefiltered" :key="index">
                                                            <tr>
                                                                <td colspan="5" class="koneng">
                                                                    {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                                                                </td>
                                                            </tr>
                                                            <tr v-for="(itemsDet, index2)  in items.details" :key="index2">
                                                                <td width="30%">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">{{
                                                                                itemsDet.namaruangan
                                                                            }}</div>
                                                                            <div class="title-layan">{{ itemsDet.namaproduk
                                                                            }} - {{itemsDet.dokterpemeriksa ? itemsDet.dokterpemeriksa : 'Dokter belum di input'}}</div>
                                                                            <div>
                                                                                <VTag
                                                                                    :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                                                                    :label="itemsDet.tglpelayanan" />
                                                                            </div>
                                                                            <div class="title-kelas">{{ itemsDet.namakelas
                                                                            }}
                                                                            </div>
                                                                             <div class="title-kelas">DPJP : {{ itemsDet.dokterpemeriksa
                                                                            }}
                                                                            </div>
                                                                             <div class="title-kelas">Pemeriksa : {{ itemsDet.pemeriksa
                                                                            }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">Jasa : {{
                                                                                H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                                                            <div class="title-layan">{{
                                                                                H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}
                                                                            </div>

                                                                            <div class="title-kelas">Diskon : {{
                                                                                H.formatRp(itemsDet.hargadiscount, 'Rp. ')
                                                                            }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">{{ itemsDet.jumlah }}</td>
                                                                <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}
                                                                </td>
                                                                <td class="center">

                                                                    <!-- <VIconButton color="danger" class="mr-2" light raised
                                                                        circle icon="feather:trash"
                                                                        @click="hapusTindakan(itemsDet)" /> -->
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
                                                                                    <span>view detail harga tindakan </span>
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
                                                                        <img class="light-image" style=" max-width: 340px;"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <img class="dark-image" style=" max-width: 340px;"
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

            <Dialog
                v-model:visible="modalInput"
                modal header="Tindakan"
                :style="{ width: '95rem' }"
                :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
                maximizable
            >

                    <div class="columns is-multiline">
                    <!-- <div class="column is-12">
                        <VButton icon="feather:plus" @click="tambah()" outlined :loading="isLoading" color="danger" class="is-pulled-right" raised>
                            Tambah
                        </VButton>
                        <VTag :color="'warning'" :label="dataSourceTindakan.length + ' tindakan sudah ditambah'"
                            v-if="dataSourceTindakan.length" class="is-pulled-right mr-2 mt-2" />
                    </div> -->
                    <div class="column is-6">
                        <VCard>
                            <DataTable v-model:selection="selectedProduct"
                            v-model:filters="filtersTindakan"
                            :loading="isLoadingTindakan"
                            :rows="10" paginator
                            :value="products"
                            selectionMode="multiple"
                            :metaKeySelection="metaKey"
                            dataKey="id"
                            @rowSelect="onTindakanSelected"
                            @rowUnselect="onTindakanUnselected"
                            :globalFilterFields="['namaproduk', 'id']"
                            tableStyle="min-width: 50rem">
                                <template #header>
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField>
                                                <InputText v-model="filtersTindakan['global'].value" placeholder="Search Data" />
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VSwitchBlock v-model="isAllTindakan" @change.stop="isAllTindakanChange(isAllTindakan)" color="success" label="Semua Tindakan" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </template>
                                <template #empty> No customers found. </template>
                                <template #loading>
                                    <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
                                    <p style="color:white">Loading data, please wait...</p>
                                </template>
                                <Column field="id" header="ID"></Column>
                                <Column field="namaproduk" header="Nama"></Column>
                                <!-- <Column field="" header="Category"></Column>
                                <Column field="quantity" header="Quantity"></Column> -->
                            </DataTable>
                        </VCard>
                    </div>
                    <div class="column is-6">
                        <span style="font-weight: bold">List Tindakan Dipilih</span>
                        <div class="timeline-wrapper" v-if="dataSelectedTindakan.length > 0" style="margin-top: 20px;">
                            <div class="timeline-header"></div>
                            <!-- <div class="timeline-wrapper-inner pt-0">
                                <div class="timeline-container">
                                    <div class="timeline-item is-unread" >
                                        <VCard radius="rounded">
                                            <ul style="list-style-type:disc" class="ml-5"
                                            v-for="(items, index) in dataSelectedTindakan" :key="items.id">
                                                <li >{{ items.namaproduk }}</li>
                                                <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash"   class="is-pulled-right"            //DEFAULT
                                                    @click="hapussskii(index)" color="danger" raised circle>
                                                </VIconButton>
                                            </ul>
                                            <VPlaceloadText v-if="isLoadingTindakan"
                                                :lines="1"
                                                width="75%"
                                                last-line-width="25%"
                                            />
                                        </VCard>
                                    </div>
                                </div>
                            </div> -->
                            <div class="timeline-wrapper-inner pt-0">
                                <div class="timeline-container">
                                  <div class="timeline-item is-unread">
                                    <VCard radius="rounded" class="p-3 mb-3">
                                      <div v-for="(items, index) in dataSelectedTindakan" :key="items.id">
                                        <div class="flex items-center justify-between mb-2">
                                          <span class="ml-5">{{ items.namaproduk }}</span>
                                          <VIconButton
                                            v-tooltip.bottom.right="'Hapus'"
                                            icon="feather:trash"
                                            @click="hapussskii(items.id)"
                                            color="danger"
                                            raised
                                            circle
                                            class="ml-auto"
                                          />
                                        </div>
                                      </div>

                                      <VPlaceloadText
                                        v-if="isLoadingTindakan"
                                        :lines="1"
                                        width="75%"
                                        last-line-width="25%"
                                      />
                                    </VCard>
                                  </div>
                                </div>
                            </div>
                        </div>

                        <VCard radius="rounded" class="mt-2" v-else-if="dataSelectedTindakan.length == 0 && isLoadingTindakan" >
                            <VPlaceloadText
                                :lines="5"
                                width="75%"
                                last-line-width="25%"
                            />
                        </VCard>

                        <VCard radius="rounded" class="mt-2" v-else>
                            <VPlaceholderPage :title="H.assets().notFound"
                                :subtitle="H.assets().notFoundSubtitle" larger>

                                    <img class="light-image" :src="H.assets().iconNotFound_rev"
                                        alt="" />
                                    <img class="dark-image"
                                        src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                        alt="" />

                            </VPlaceholderPage>
                        </VCard>



                    </div>
                </div>


                    <VButton type="button" rounded color="primary" raised icon="feather:save" class="is-pulled-right"
                        :loading="isLoading" @click="newSimpan()"> Tambah
                    </VButton>

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
                                                    <div class="column is-12 mb--15" v-else
                                                        v-for="(item, index) in dataSourcePetugas" :key="index">
                                                        <div class="columns is-multiline p-0">
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/icons/files/dokter.svg'" alt="" />
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
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/icons/files/dokter.svg'" alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.operator1 }} </span>
                                                                        <span>
                                                                            <b>Dokter Anak</b>
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
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/icons/files/dokter.svg'" alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.operator2 }} </span>
                                                                        <span>
                                                                            <b>Dokter Anestesi</b>
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
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/icons/files/dokter.svg'" alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.operator3 }} </span>
                                                                        <span>
                                                                            <b>Dokter Operator 1</b>
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
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/icons/files/dokter.svg'" alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.operator4 }} </span>
                                                                        <span>
                                                                            <b>Dokter Operator 2</b>
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
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/icons/files/dokter.svg'" alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.operator5 }} </span>
                                                                        <span>
                                                                            <b>Dokter Operator 3</b>
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
                                                                                    H.formatRp(item.hargasatuan, '') }}</td>
                                                                                <td class="tb-th text-center"> {{
                                                                                    item.jumlah }}</td>
                                                                                <td class="tb-th text-center"> {{
                                                                                    H.formatRp(item.hargadiscount, '') }}
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

                                                    <VInput type="number" v-model="item.persenDiskon" placeholder="Diskon"
                                                        class="is-rounded" />
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
                        <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:plus-circle" fullwidth>
                                <Multiselect mode="single" v-model="item2.jenisPelaksana" :options="d_JenisPelaksana"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    @select="changeJenis2(item2)" track-by="value" />

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
                    <!-- <div class="column is-6">
                        <VField>
                            <VControl icon="feather:search">
                                <input v-model="filterPaket.global.value" type="text" class="input is-rounded"
                                    placeholder="Filter " />
                            </VControl>
                        </VField>
                    </div> -->
                    <div class="column is-12">
                        <VCard>
                            <DataTable :value="dataSourcePaket" v-model:expandedRows="expandedRows" :paginator="true"
                                :rows="10" :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers"
                                filterDisplay="menu" v-model:filters="filterPaket"
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                :globalFilterFields="['namapaket']" :scrollable="true" :loading="dataSourcePaket.loading"
                                dataKey="id">
                                <template #header>
                                    <div class="flex justify-content-end">
                                        <span class="p-input-icon-left">
                                            <i class="pi pi-search" />
                                            <InputText v-model="filterPaket.global.value" placeholder="Keyword Search" />
                                        </span>
                                    </div>
                                </template>
                                <Column :expander="true" :style="{ width: '50px' }" />
                                <Column :exportable="false" header="#" :style="{ width: '50px' }">
                                    <template #body="slotProps">
                                        <VIconButton type="button" icon="pi pi-plus" class="mr-2" color="info" circle
                                            outlined raised v-tooltip.top="'Tambah'" @click="tambahPaket(slotProps.data)"
                                            :loading="slotProps.data.isLoading">
                                        </VIconButton>
                                    </template>
                                </Column>

                                <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
                                <Column field="namapaket" header="Nama Paket" style="width:250px" :sortable="true"></Column>
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
            <!-- <Dialog v-model:visible="modalRadiologiSendRIS" modal header="Form Verifikasi Radiologi" :style="{ width: '60vw' }">
                <div class="space-y-4">
                    <div
                    v-for="(items, index) in dataResult"
                      :key="items.norec"
                      class="flex items-center bg-white rounded-lg shadow-sm p-4 space-x-4"
                    >
                      <VIconBox size="large" :color="listColor[index + 1]" rounded>
                        <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                      </VIconBox>
                      <div class="flex-grow">
                        <p class="text-gray-800 font-medium text-lg">{{ items.namaproduk }}</p>
                      </div>
                      <div class="w-1/3 mr-4">
                        <VField class="is-rounded-select is-autocomplete-select">
                          <VLabel class="required-field">Dokter Verifikator</VLabel>
                          <VControl icon="feather:search" fullwidth class="prime-auto-select">
                            <AutoComplete
                              v-model="items.dokterVerifRad"
                              :suggestions="d_Dokter_Rad_Verif"
                              @complete="getDokterVerif($event)"
                              :optionLabel="'label'"
                              :dropdown="true"
                              :minLength="3"
                              :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'"
                              :field="'label'"
                              placeholder="Ketik Nama Dokter"
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                 <div class="column">
                  <VField>
                    <VLabel class="required-field">Catatan Klinis</VLabel>
                    <VControl class="mt-3">
                      <VTextarea rows="4" placeholder="Tulis Catatan Klinis..." v-model="item.catatanklinis">
                      </VTextarea>
                    </VControl>
                  </VField>
                </div>
                <template #footer>
                  <VButton color="primary" icon="pi pi-check" raised @click="hilangken()" > simpan
                  </VButton>
                </template>
              </Dialog> -->
              <Dialog
                    v-model:visible="modalRadiologiSendRIS"
                    modal
                    :header="modalLis ? 'Form Verifikasi Laboratorium' : 'Form Verifikasi Radiologi'"
                    :style="{ width: '60vw' }"
                    >
                    <div class="space-y-6" v-if="modalLis != true">
                        <div
                        v-for="(items, index) in dataResult"
                        :key="items.norec"
                        class="flex items-center bg-white rounded-lg shadow-sm p-4 space-x-6"
                        >
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
                                <AutoComplete
                                v-model="items.dokterVerifRad"
                                :suggestions="d_Dokter_Rad_Verif"
                                @complete="getDokterVerif($event)"
                                :optionLabel="'label'"
                                :dropdown="true"
                                :minLength="3"
                                :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'"
                                :field="'label'"
                                placeholder="Ketik Nama Dokter"
                                />
                            </VControl>
                            </VField>
                        </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4">
                        <VField>
                            <VLabel class="required-field">Catatan Klinis</VLabel>
                            <VControl class="mt-3">
                            <VTextarea
                                rows="4"
                                placeholder="Tulis Catatan Klinis..."
                                v-model="item.catatanklinis"
                            />
                            </VControl>
                        </VField>
                        </div>
                    </div>
                    <div v-else>
                        <div class="w-1/3" >
                            <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Dokter Verifikator</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <AutoComplete v-model="item.dokterVerifLab" :suggestions="d_Dokter_Lab_Verif" @complete="fetchPetugas($event)"
                                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                            </VControl>
                            </VField>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <VField>
                                <VLabel class="required-field">Catatan Klinis</VLabel>
                                <VControl class="mt-3">
                                    <VTextarea
                                        rows="4"
                                        placeholder="Tulis Catatan Klinis..."
                                        v-model="item.catatanklinisLab"
                                    />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <template #footer>
                        <div class="flex justify-end space-x-2">
                        <VButton
                            color="primary"
                            icon="pi pi-check"
                            raised
                            @click="hilangken()"
                        >
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
console.log('DARI_RADIOLOGI woi', DARI_RADIOLOGI)
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
    dataDokteroperator: [],
    dataDokteroperator1: [],
    dataDokteroperator2: [],
    dataDokteroperator3: [],
    dataDokteroperator4: [],
    dataPerawatSelected: [],
    registrasi: {},
     isasa1: false,
  isasa2: false,
  isasa3: false,
  isasa4: false,
})
if(NOREC_PD == undefined){
  NOREC_PD = props.registrasi.norec_pd
  console.log('GANTI WOI',NOREC_PD)
}
const item2: any = reactive({
    tglpelayanan: new Date(),
})
const d_Produk: any = ref([])
const d_Komponen: any = ref([])
const d_JenisPelaksana: any = ref([])
const d_Pegawai: any = ref([])
const d_Pegawai2: any = ref([])
const d_pegawai3: any = ref([])
const d_pegawai4: any = ref([])
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
const historySave: any =ref([])
const dataSelect: any = ref({})
const isPaket: any = ref(false)
const isPaketTambah: any = ref(false)
const forDokter: any = ref([]);
const modalPaket: any = ref(false)
const d_Dokter_Lab_Verif: any =ref([])
const modalLis: any = ref(false)
const modalRadiologiSendRIS: any = ref(false)
const d_Dokter_Rad_Verif: any =ref([])
const isSavedTindakan: any = ref(true);
let dataSelectedTindakan : any = ref([]);
const isStuck = computed(() => {
    return y.value > 30
})
const filtersTindakan = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const tabs: any = ref([
    { label: 'Tindakan', value: 1, icon: 'lnir lnir-medicine-alt' },
    { label: 'Tindakan Operasi ', value: 3, icon: 'fas fa-list',  },
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
const toggle = (value: string) => {
    activeValue.value = value

    if(value == 2) {
        fetchBill();
    }
}

const onSwitchChange = (switchName: keyof SwitchState, value: boolean) => {
  item[switchName] = !!value;
  console.log(`${switchName} status:`, item[switchName]);
};

const dataSourcefiltered = computed(() => {
  if (!filters.value ) {
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
    await changeTindakan(event.data);

    let jsonPush = {
        id: event.data.id,
        namaproduk: event.data.namaproduk,
        objectruanganfk: event.data.objectruanganfk,
        hargasatuan: item.hargasatuan,
        jumlah: item.jumlah,
        komponenharga: d_Komponen,
        tanggal: moment(new Date()).format('DD-MM-YYYY')
    }

    dataSelectedTindakan.value.push(jsonPush);


};
const onTindakanUnselected = async (event) => {
    await deleteItem(event.data.id);
}

async function deleteItem(datass) {
    dataSelectedTindakan.value.forEach((getTindak, index) => {
        if(getTindak.id == datass) {
            dataSelectedTindakan.value.splice(index, 1);
        }
    });
}

const deleteResultTindakan = (event) => {
    dataResult.value.forEach((getTindak, index) => {
        if(getTindak.id == datass) {
            dataResult.value.splice(index, 1);
        }
    });
}

async function pasienByID(id: any) {
    if (props.pasien != undefined) {
        pasien.value = props.pasien
        item.NOREC_APD = props.registrasi.norec_apd
        item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
        item.registrasi = props.registrasi
        dropdownTindakan(item.RUANGAN_LAST, props.registrasi.objectkelasfk)
    } else {
        isLoadingPasien.value = true
        await useApi().get(
            `/tindakan/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`).then((response: any) => {
                pasien.value = response.pasien
                item.NOREC_APD = response.last_registrasi.norec_apd
                item.RUANGAN_LAST = response.last_registrasi.objectruanganfk //response.last_registrasi.objectruanganlastfk
                item.registrasi = response.last_registrasi
                isLoadingPasien.value = false
                dropdownTindakan(item.RUANGAN_LAST, response.last_registrasi.objectkelasfk)
            })
    }

    console.log("REGIS NOW", item.registrasi);
}




function dropdownTindakan(idruang: any, idkelas: any) {
    isLoadingTindakan.value = true;
    let qRuangan = '';
    if(isAllTindakan.value == false) {
        qRuangan = `?idruangan=${idruang}&idkelas=${idkelas}`;
    }

    useApi().get(
        `/tindakan/list-dropdown-registrasi?norec_pd=${NOREC_PD}&rad=${DARI_RADIOLOGI}`).then((response: any) => {

            // d_Registrasi.value = response.registrasi.map((e: any) => { return { label: e.tglregistrasi + ' - (' + e.noregistrasi + ' - ' + e.namaruangan + ')', value: e, default: e } })
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
            if(DARI_RADIOLOGI === 'true'){
                // console.log('masuk ke if')
                item.pilihRegistrasi = d_Registrasi.value[0].value
                getRegistrasi(item.pilihRegistrasi)
            }
            // console.log('registrasi',d_Registrasi)
            else{
                for (let i =  0 ; i < response.registrasi.length; i++) {
                    let ftregis = response.registrasi[i];
                    if(item.registrasi.noregistrasi == ftregis.noregistrasi &&
                        item.registrasi.objectruanganfk == ftregis.objectruanganfk
                    ) {
                        item.pilihRegistrasi = d_Registrasi.value[i].value
                        getRegistrasi(item.pilihRegistrasi)

                    }
                    // if(response.registrasi[i].tglregistrasi == H.formatDate(new Date(), 'DD-MM-YYYY') && response.registrasi[i].objectdepartemenfk == 18){
                    //     item.pilihRegistrasi = d_Registrasi.value[i].value
                    // }

                    // if(response.registrasi[i].tglpulang == null && response.registrasi[i].objectdepartemenfk == 16){
                    //     item.pilihRegistrasi = d_Registrasi.value[i].value
                    //     getRegistrasi(item.pilihRegistrasi)
                    // }
                }
            }
            //item.ruangan = d_RuanganRJ.value[0].value
        })

    useApi().get(
    `/tindakan/list-tindakan${qRuangan}`).then((response: any) => {
        d_Produk.value = response.data.map((e: any) => { return { label: e.namaproduk, value: e } })
        products.value = response.data;
        isLoadingTindakan.value = false;
    })
}
function dropdownList() {
    useApi().get(`tindakan/list-jenis-petugas`).then((response: any) => {
        d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
        item.nilaiCito = response.cito != null ? parseFloat(response.cito) : 1
        // console.log(response.jenispetugaspelaksana)
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
        for (let x = 0; x < response.jenispetugaspelaksana.length; x++) {
            const element = response.jenispetugaspelaksana[x];

            if (element.jenispetugaspe.toLowerCase().indexOf('pemeriksa') > -1) {
                item2.jenisPelaksana = element.id
                changeJenis2(item2)
                break
            }
        }

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
    // await H.statusClosingPasien(NOREC_PD);
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
    item.hargasatuan = 0
    await useApi().get(
        '/tindakan/list-tindakan-komponen?idRuangan=' + item.registrasi.objectruanganfk
        + '&idKelas=' + item.registrasi.objectkelasfk
        + '&idProduk=' + e.id
        + '&idJenisPelayanan=' + item.registrasi.jenispelayananfk
        + '&idPenjamin=' + item.registrasi.objectrekananfk
        + '&objectkebangsaanfk=' + pasien.value.objectkebangsaanfk
    ).then((response: any) => {
        isLoading.value = false
        isLoadingTindakan.value = false;
        item.hargasatuan = response.harga.hargasatuan
        item.hargasatuanDef = response.harga.hargasatuan
        item.jumlah = 1
        d_Komponen.value = response.komponen
    })
}

const isAllTindakanChange = async(e: any) => {
    isAllTindakan.value = !e;

    isLoadingTindakan.value = true;
    console.log('PROPS REGIS', props.registrasi)
    let kelas = undefined;
    if(props.registrasi) {
        kelas = props.registrasi.objectkelasfk
    }else {
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

            e.d_Pegawai = response.map((e: any) => {
                return {
                    label: e.namalengkap, value: e.id
                }
            })
        } else {
            e.d_Pegawai = []
        }
    })
    isLoadChange.value = false
    //     // d_Pegawai.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
    // })

    // console.log(d_Pegawai.value)
}
const changeJenis2 = async (e: any) => {
    isLoadChange.value = true
    await useApi().get('/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e.jenisPelaksana).then((response: any) => {
        if (response != null) {
            d_Pegawai2.value = response.map((e: any) => {
                return {
                    label: e.namalengkap, value: e.id
                }
            })

        } else {
            d_Pegawai = []
        }
    })
    isLoadChange.value = false
    //     // d_Pegawai.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
    // })

    // console.log(d_Pegawai.value)
}


function tambah() {
    // if (!item.produk) {
    //     useToaster().error('Pelayanan harus di isi')
    //     return
    // }
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

    // for (let i = 0; i < listItem.value.length; i++) {
    //     const element = listItem.value[i];
    //     element.pegawai = []
    // }
    // modalInput.value = false
}
function kembaliKeun() {
    window.history.back()
}

function hapuskeunsemuaopna(){
    delete item.dataDokteroperator
    delete item.dataDokteroperator1
    delete item.dataDokteroperator2
    delete item.dataDokteroperator3
    delete item.dataDokteroperator4
    delete item.perawatanastesi
    delete item.penataanastesi

    delete item.Pegawai

    dataSourceTindakan.value = data2.value
    dataSelectedTindakan.value = [];
    data2.value = []

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
    isLoading.value = true;
    await H.statusClosingPasien(NOREC_PD);
    if(dataSelectedTindakan.length == 0) {
        H.alert('warning', 'Tindakan belum di pilih');
        return
    }
    if(item.NOREC_PD == '') {
      item.NOREC_PD = props.registrasi.norec_pd
    }

    dataSelectedTindakan.value.forEach((element, index) => {
        element.kelasfk = item.registrasi.objectkelasfk
        element.norec_apd = item.pilihRegistrasi.norec_apd
        element.norec_pd = item.pilihRegistrasi.norec_pd
        element.tglregistrasi = item.registrasi.tglregistrasi
        element.isPaketTambah = false
        if (element.komponenharga.length == 0) {
            H.alert('warning', 'Tindakan (' + element.namaproduk + ') ini belum ada komponen harganya, harap hubungi IT')
            return
        }
        // element.dokter = item.pilihRegistrasi.objectpegawaifk
        // element.pelaksana = []
        useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}`).then((response:any)=>{
            if(item.pilihRegistrasi.namadepartemen.toLowerCase() == 'Instalasi Radiologi'.toLowerCase()){
                modalInput.value = false;
                modalRadiologiSendRIS.value=true
            }
            if(item.pilihRegistrasi.namadepartemen.toLowerCase() == 'Instalasi Laboratorium'.toLowerCase() &&  response.ordelab === null && item.pilihRegistrasi.namaruangan.toLowerCase() != 'BANK DARAH'.toLowerCase() ){
                modalInput.value = false;
                modalRadiologiSendRIS.value=true
                modalLis.value = true
            }

        })

        let cpgw = d_pegawai3.value.filter((dt) => {
            return dt.value == item.dataDokterSelect;
        });
        if(cpgw.length > 0) {
            element.pelaksana = cpgw[0] || {}
        }

        dataResult.value.push(element);
    });
    dataSelectedTindakan.value = [];
    // console.log("Data Dokter DPJP", item.dataDokterSelect);
    // dataResult.value = dataSelectedTindakan.value;
    let saveTemp = {
        tanggal: H.formatDate(new Date(), 'YYYY-MM-DD'),
        norec_pd: item.pilihRegistrasi.norec_pd,
        issaved: false,
        data: dataResult.value,
        nocmfk: ID_PASIEN
    }
    await useApi().post(`tindakan/save-temp-tindakan`, saveTemp).then((svData) => {
        isSavedTindakan.value = false;
    }).catch((err) => {
        isLoading.value = false;
    })
    console.log("Data Result ditambahkan", dataResult.value);

    isLoading.value = false;
    modalInput.value = false;

}

async function simpan() {
    if(!item.dataDokterSelect){
        useToaster().error('Dokter DPJP belum di pilih')
        return
    }
    // tambah()
    await H.statusClosingPasien(NOREC_PD);
    if (dataResult.length == 0) { H.alert('warning', 'Tindakan belum di isi'); return }
    let iserr = false;
    // let getAllDokterField = dataResult.value.filter((gdt) => {
    //     return gdt.dokter !== null;
    // });
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

    dataResult.value.forEach((val, index) => {

        dataResult.value[index].kelasfk = item.registrasi.objectkelasfk
        dataResult.value[index].norec_apd = item.pilihRegistrasi.norec_apd
        dataResult.value[index].norec_pd = item.pilihRegistrasi.norec_pd
        dataResult.value[index].tglregistrasi = moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
        dataResult.value[index].diskon = 0
        dataResult.value[index].iscito = 0
        dataResult.value[index].isparamedis = 0
        dataResult.value[index].produkfk = val.id
        dataResult.value[index].tglpelayanan = moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
        dataResult.value[index].dokter = item.dataDokterSelect
        dataResult.value[index].jenisPelaksana = item.jenisPelaksana || null;
        dataResult.value[index].pegawai = item.pegawai || [];
        dataResult.value[index].pelayananpetugas = petugas;
        // dataResult.value[index].pelaksana = item.dataPerawatSelected[index];
        // console.log("SELECTED DOKTER", item.dataDokterSelect)
        // dataResult.value[index].pelaksana = item.dataDokterSelect || {}
        val.dokter = item.dataDokterSelect;
        // if(getAllDokterField.length > 0) {
        //     dataResult.value[index].dokter_display = getAllDokterField[0].dokter_display
        //     val.dokter_display = getAllDokterField[0].dokter_display;
        // }
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
    if(iserr) return;
    let json = {
        'pelayananpasien': dataResult.value,
        'noregistrasi': item.pilihRegistrasi.noregistrasi,
        'nocm': pasien.value.nocm,
        'isPaketTambah': isPaketTambah.value,
        'namapasien': pasien.value.namapasien,
        'namaruangan': item.registrasi.namaruangan,
        'departemen' : item.pilihRegistrasi.namadepartemen.toLowerCase(),
        'flag' : item.pilihRegistrasi.namaruangan.toLowerCase() == 'BANK DARAH'.toLowerCase() ? true : false
    }
    isLoading.value = true
    console.log("DATA RESULT :",dataResult.value)
    await useApi().post(
        `/tindakan/save-tindakan`, json).then((response: any) => {
            if(response.data != null) {
                let updTemp = useApi().get(`tindakan/update-status-temp?norec_pd=${item.pilihRegistrasi.norec_pd}&nocmfk=${ID_PASIEN}`);
                if(updTemp) {
                    isSavedTindakan.value = true;
                }
                if(item.pilihRegistrasi.namadepartemen.toLowerCase() == 'Instalasi Radiologi'.toLowerCase()){
                    // sendRISPACS(response)
                    isLoading.value = true
                    // clearList()
                    console.log('masuk regis isntalasi radiologi')
                    useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}&noorderrad=true`).then((res : any) => {
                        if(res.ordelab == null || res.ordelab === '' || (typeof res.ordelab === 'object' && Object.keys(res.ordelab).length === 0)){
                            // console.log('masuk if')
                            sendRISPACSLangsungRegis(response)
                            // clearList()
                        }
                        else{
                            // console.log('masuk else')
                            sendRISPACS(response)
                            // clearList()
                        }
                    }).catch((xxx :any)=>{
                        isLoading.value = false
                        console.error(xxx)
                    })
                }
                if(item.pilihRegistrasi.namadepartemen.toLowerCase() == 'Instalasi Laboratorium'.toLowerCase() && item.pilihRegistrasi.namaruangan.toLowerCase() != 'BANK DARAH'.toLowerCase()){

                    isLoading.value = true
                    useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}`).then((res : any) => {
                        if(res.ordelab == null || res.ordelab === '' || (typeof res.ordelab === 'object' && Object.keys(res.ordelab).length === 0)){
                            sendLIS(response)
                            // console.log('ini nggak ada noorder nya')
                            // clearList()
                        }
                        else{
                            editLIS(response.norec_pp)
                            // clearList()
                        }
                    }).catch((xxx :any)=>{
                        isLoading.value = false
                        console.error(xxx)
                    })
                    // clearList()
                }
                else{
                    dataRiwayat.value = dataResult.value
                    console.log(dataRiwayat)
                    isLoading.value = false
                    fetchBill()
                    // clearList()
                }
              // disabledSave.value = true
              this.activeValue = 2
            }
        }).catch((e: any) => {
            isLoading.value = false
        })
}
async function simpanop() {
    // tambah()
    await H.statusClosingPasien(NOREC_PD);
    if (dataResult.length == 0) { H.alert('warning', 'Tindakan belum di isi'); return }
    let iserr = false;
    // let getAllDokterField = dataResult.value.filter((gdt) => {
    //     return gdt.dokter !== null;
    // });
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

    dataResult.value.forEach((val, index) => {

        dataResult.value[index].kelasfk = item.registrasi.objectkelasfk
        dataResult.value[index].norec_apd = item.pilihRegistrasi.norec_apd
        dataResult.value[index].norec_pd = item.pilihRegistrasi.norec_pd
        dataResult.value[index].tglregistrasi = item.registrasi.tglregistrasi
        dataResult.value[index].diskon = 0
        dataResult.value[index].iscito = 0
        dataResult.value[index].isparamedis = 0
        dataResult.value[index].produkfk = val.id
        dataResult.value[index].tglpelayanan = moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
        dataResult.value[index].dokter = item.dataDokterSelect
        dataResult.value[index].jenisPelaksana = item.jenisPelaksana || null;
        dataResult.value[index].pegawai = item.pegawai || [];
        dataResult.value[index].pelayananpetugas = petugas;
        dataResult.value[index].operator = item.dataDokteroperator
        dataResult.value[index].operator1 = item.dataDokteroperator1
        dataResult.value[index].operator2 = item.dataDokteroperator2
        dataResult.value[index].operator3 = item.dataDokteroperator3
        dataResult.value[index].operator4 = item.dataDokteroperator4
        dataResult.value[index].perawatanastesi = item.perawatanastesi
        dataResult.value[index].penataanastesi = item.penataanastesi
        dataResult.value[index].asa1 = item.isasa1
        dataResult.value[index].asa2 = item.isasa2
        dataResult.value[index].asa3 = item.isasa3
        dataResult.value[index].asa4 = item.isasa4
        // dataResult.value[index].pelaksana = item.dataPerawatSelected[index];
        console.log("SELECTED DOKTER", item.dataDokterSelect)
        // dataResult.value[index].pelaksana = item.dataDokterSelect || {}
        val.dokter = item.dataDokterSelect;
        val.operator = item.dataDokteroperator;
        val.operator1 = item.dataDokteroperator1;
        val.operator2 = item.dataDokteroperator2;
        val.operator3 = item.dataDokteroperator3;
        val.operator4 = item.dataDokteroperator4;
        val.perawatanastesi = item.perawatanastesi;
        val.penataanastesi = item.penataanastesi;
        val.asa1 = item.isasa1;
        val.asa2 = item.isasa2;
        val.asa3 = item.isasa3;
        val.asa4 = item.isasa4;
        // if(getAllDokterField.length > 0) {
        //     dataResult.value[index].dokter_display = getAllDokterField[0].dokter_display
        //     val.dokter_display = getAllDokterField[0].dokter_display;
        // }
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
    if(iserr) return;
    let json = {
        'pelayananpasien': dataResult.value,
        'noregistrasi': item.pilihRegistrasi.noregistrasi,
        'nocm': pasien.value.nocm,
        'namapasien': pasien.value.namapasien,
        'namaruangan': item.registrasi.namaruangan,
        'departemen' : item.pilihRegistrasi.namadepartemen.toLowerCase()
    }
    isLoading.value = true
    await useApi().post(
        `/tindakan/save-tindakanop`, json).then((response: any) => {
            if(response.data != null){
                if(item.pilihRegistrasi.namadepartemen.toLowerCase() == 'Instalasi Radiologi'.toLowerCase()){
                    isLoading.value = true
                    clearList()
                     useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}&noorderrad=true`).then((response : any)=>{
                        if(res.ordelab == null || res.ordelab === '' || (typeof res.ordelab === 'object' && Object.keys(res.ordelab).length === 0)){
                            sendRISPACSLangsungRegis(response)
                        }
                        else{
                            sendRISPACS(response)
                        }
                    }).catch((xxx :any)=>{
                        isLoading.value = false
                        console.error(xxx)
                    })
                }
                if(item.pilihRegistrasi.namadepartemen.toLowerCase() == 'Instalasi Laboratorium'.toLowerCase()){
                    editLIS()
                    isLoading.value = true
                    // clearList()
                }
                else{
                    dataRiwayat.value = dataResult.value
                    console.log(dataRiwayat)
                    isLoading.value = false
                    fetchBill()
                    // clearList()
                }
              // disabledSave.value = true
              this.activeValue = 2
            }
        }).catch((e: any) => {
            isLoading.value = false
        })

        hapuskeunsemuaopna()

        // clearInput();
        // clearList();
}

const sendRISPACS = (resp:any) => {
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
        useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}&noorderrad=true`).then((response : any)=>{
            let itemsave = {
                "details": objBridg,
                "noorder": response.ordelab.noorder,
                "objectkelasfk": item.registrasi.objectkelasfk,
                "objectruangantujuanfk": item.pilihRegistrasi.objectruanganfk,
                "objectpegawaiorderfk": item.pilihRegistrasi.objectpegawaifk,
                // "iddokterverif": item.objectpegawaifk.value,
                // "namadokterverif": item.objectpegawaifk.label,
                "catatan_klinis": item.keterangan ? item.keterangan : null,
            }
            isLoading.value = true
            useApi().post('bridging/penunjang/save-bridging-zeta', itemsave).then((e: any) => {
                isLoading.value = false
                if(metaData.code == 200 || metaData.code == 201)   H.alert('success','Success sending RIS')
                clearList()
            }).catch((e: any) => {
                isLoading.value = false
                console.error(e)
            })
        }).catch((xxx :any)=>{
            isLoading.value = false
            console.error(xxx)
        })
    }

}

const sendLIS = (resp:any) => {

    let objSaveOrder: any = {}
    let objOrder: any = []

    console.log(dataResult.value)

    // if (!item.objectpegawaifk) {
    //     H.alert('error', 'Pilih Dokter Pengirim terlebih dahulu !')
    //     return
    // }

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
            noregistrasi : item.pilihRegistrasi.noregistrasi,
            tanggal : H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
            tgloperasi : null,
            norec_so : '',
            norec_apd : item.pilihRegistrasi.norec_apd ? item.pilihRegistrasi.norec_apd : null,
            norec_pd : item.pilihRegistrasi.norec_pd,
            qtyproduk : objOrder.length,
            objectruanganfk : item.pilihRegistrasi.objectruanganlastfk,
            pegawaiorderfk : item.pilihRegistrasi.objectpegawaifk,
            namalengkap : item.pilihRegistrasi.dokter,
            objectruangantujuanfk: item.pilihRegistrasi.objectruanganfk,
            departemenfk : item.pilihRegistrasi.objectdepartemenfk,
            keterangan : null,
            // noord : element.noorder,
            iscito: false,
            details: objOrder,
            langsungregis : true
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
                "objectruangantujuanfk": item.pilihRegistrasi.objectruanganfk,
                "objectpegawaiorderfk": item.pilihRegistrasi.objectpegawaifk,
                "iddokterverif": item.dokterVerifLab.value,
                "namadokterverif": item.dokterVerifLab.label,
                "noregistrasi" : item.pilihRegistrasi.noregistrasi,
                "catatan_klinis": item.catatanklinisLab ? item.catatanklinisLab : null,
            }
            useApi().post('bridging/penunjang/save-bridging-vans-lab', itemsave).then((e: any) => {
                if(e.data != null){
                    isLoading.value=false
                    H.alert('success','Success sending LIS')
                    clearList()
                }
                isLoading.value = false
            }, (error) => {
                console.error(error)
            })
        }, (error) => {
            console.error(error)
        })
    }


}


const sendRISPACSLangsungRegis = (resp:any) => {
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
            langsungregis : true
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
                "catatan_klinis": item.catatanklinis ? item.catatanklinis : null,
            }
            useApi().post('bridging/penunjang/save-bridging-zeta', itemsave).then((e: any) => {
                if(e.metaData.code == 200 || e.metaData.code == 201)  {
                    H.alert('success','Success sending RIS')
                    clearList()
                    isLoading.value = false
                }
                }).catch((e: any) => {
                    isLoading.value = false
                    console.error(e)
                })
            })
    }

}

const editLIS = (resp:any) => {

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
        useApi().get(`/dashboard/data-noorder?norec_pd=${item.NOREC_PD}`).then((response:any) => {

            let itemsave = {
                "bridging": objBridg,
                "noorder": response.ordelab.noorder,
                "objectkelasfk": item.registrasi.objectkelasfk,
                "objectruangantujuanfk": item.pilihRegistrasi.objectruanganfk,
                "objectpegawaiorderfk": item.pilihRegistrasi.objectpegawaifk,
                // "iddokterverif": item.dokterVerifLab.value,
                // "namadokterverif": item.dokterVerifLab.label,
                "objectruanganfk": item.registrasi.objectruanganfk,
                "noregistrasi" : item.pilihRegistrasi.noregistrasi,
                // "catatan_klinis": item.catatanklinisLab ? item.catatanklinisLab : null,
                "norec_pp" : resp
            }
            console.log(itemsave)
            if(response.ordelab.noorder != null){
                useApi().post('bridging/penunjang/save-edit-lab',itemsave).then((res:any)=>{
                    if(res.data != null){
                            useApi().post('bridging/penunjang/edit-bridging-vans-lab', itemsave).then((e: any) => {
                                if(e.data != null)  {
                                    isLoading.value = false
                                    H.alert('success','Success sending LIS')
                                    clearList()
                                }
                            }, (error) => {
                                isLoading.value = false
                                console.error(error)
                            })
                    }
                }).catch((e:any)=>{
                    isLoading.value = false
                    console.error(e)
                })
            }
        }).catch((e: any)=>{
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
    const response= useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response: any) =>{
            d_Dokter_Lab_Verif.value = response
    })
}

const hilangken = () => {
if (modalLis === true && (!item.catatanklinisLab || item.catatanklinisLab.trim() === '-' || item.catatanklinisLab.trim() === '')) {
  H.alert('warning', 'Catatan Klinis Harus di isi!');
  return;
}

if ( modalLis === false && (!item.catatanklinis || item.catatanklinis.trim() === '-' || item.catatanklinis.trim() === '')) {
  H.alert('warning', 'Catatan Klinis Harus di isi!');
  return;
}
// if(!item.dokterVerifRad) {
//     H.alert('warning','Dokter Harus di isi !')
//     return
// }

modalRadiologiSendRIS.value = false
// console.log(item.dokterVerifRad)
// console.log(item.catatanklinis)
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
    let noreg = '';
    if(props.registrasi && props.registrasi.noregistrasi) {
        noreg = props.registrasi.noregistrasi;
    }else {
        noreg = item.registrasi.noregistrasi;
    }
    if(item.NOREC_PD == ''){
        item.NOREC_PD = props.registrasi.norec_pd
    }
    // let noreg = props.registrasi.noregistrasi ?? item.registrasi.noregistrasi;

    // await useApi()
    // .post(`sysadmin/save-akomodasi`, {
    //     'noregistrasi': noreg
    // })
    // .then((response3: any) => {

    // })

    await useApi().get(
        `/kasir/billing?norec_pd=${item.NOREC_PD}&istindakan=true`).then(async (response: any) => {
            dataSource.value = response.detail

            // item.DIBAYAR = response.dibayar
            // item.SISA = response.sisa
            item.TOTAL_BILL = response.total
            item.listruangan= response.list_ruangan
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
    await H.statusClosingPasien(item.NOREC_PD);
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

const hapussskii = (idProduk: any) => {
    // console.log(idProduk)
    let indexToRemove=null
    dataSelectedTindakan.value.forEach((item, index) => {
        if (item.id === idProduk) {
        indexToRemove = index;
        }
    });
    if (indexToRemove !== null) {
        dataSelectedTindakan.value.splice(indexToRemove, 1);
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
    dataSelect.value = { ...e,
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
    let limit = '200'
    if (filter) {
        query = filter.query
        limit = '25';
    }
    await useApi().get(
        `/tindakan/list-map-jenis-petugas-all?q=${query}&limit=${limit}`).then((response: any) => {
            d_pegawai3.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
            // d_pegawai3.value = response;
        }).catch((e: any) => {

        })
}
const fetchAllDokter2 = async (filter: any) => {
    let query = ''
    let limit = '200'
    if (filter) {
        query = filter.query
        limit = '25';
    }
    await useApi().get(
        `/tindakan/list-map-jenis-petugas-all?q=${query}&limit=${limit}`).then((response: any) => {
            d_pegawai3.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
            // d_pegawai3.value = response;
        }).catch((e: any) => {

        })
}

const fetchPegawai = async (filter: any) => {
    let data = filter ? filter.query : filter
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${data}&limit=10`
    ).then((response) => {
        // d_pegawai4.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
        d_pegawai4.value = response
    })
}
// const tambahPaket = async (e: any) => {

//     if (!item2.jenisPelaksana) {
//         H.alert('error', 'Jenis Pelaksana harus di isi')
//         return
//     }
//     if (!item2.pegawai) {
//         H.alert('error', 'Pegawai harus di isi')
//         return
//     }
//     e.isLoading = true
//     let totalHargaDefault = 0
//     for (var i = 0; i < e.details.length; i++) {
//         const TINDAKAN = e.details[i];


//         let listKomponen = []
//         let response = await useApi().get(
//             '/tindakan/list-tindakan-komponen?idRuangan=' + item.registrasi.objectruanganfk
//             + '&idKelas=' + item.registrasi.objectkelasfk
//             + '&idProduk=' + TINDAKAN.objectprodukfk
//             + '&idJenisPelayanan=' + item.registrasi.jenispelayananfk
//             + '&idPenjamin=' + item.registrasi.objectrekananfk
//         )
//         e.isLoading = false

//         if (response.komponen.length == 0) {
//             H.alert('error', 'Komponen tindakan ( ' + TINDAKAN.namaproduk + ') ini tidak ada .')
//             break
//         }
//         totalHargaDefault = totalHargaDefault + response.harga.hargasatuan
//     }
//     e.isLoading = true
//     for (var i = 0; i < e.details.length; i++) {
//         const TINDAKAN = e.details[i];
//         e.isLoading = false

//         let listKomponen = []
//         let response = await useApi().get(
//             '/tindakan/list-tindakan-komponen?idRuangan=' + item.registrasi.objectruanganfk
//             + '&idKelas=' + item.registrasi.objectkelasfk
//             + '&idProduk=' + TINDAKAN.objectprodukfk
//             + '&idJenisPelayanan=' + item.registrasi.jenispelayananfk
//             + '&idPenjamin=' + item.registrasi.objectrekananfk
//         )

//         let hargasatuan = response.harga.hargasatuan


//         if (TINDAKAN.hargapaket != 0 && TINDAKAN.hargapaket < totalHargaDefault) {
//             hargasatuan = TINDAKAN.hargapaket / totalHargaDefault * hargasatuan
//             //** Kompoonen */
//             for (let j = 0; j < response.komponen.length; j++) {
//                 const elementXX = response.komponen[j];
//                 elementXX.hargasatuan = hargasatuan / parseFloat(hargasatuan) * parseFloat(elementXX.hargasatuan)

//                 elementXX.hargasatuan = parseFloat(elementXX.hargasatuan.toFixed(2))
//             }
//         }
//         listKomponen = response.komponen

//         let petugas = []
//         let nomor = 0
//         if (data2.value.length == 0) {
//             nomor = 1
//         } else {
//             nomor = data2.value.length + 1
//         }

//         let jenispet = ''
//         for (let z = 0; z < d_JenisPelaksana.value.length; z++) {
//             const element2 = d_JenisPelaksana.value[z];
//             if (item2.jenisPelaksana == element2.value) {
//                 jenispet = element2.label
//                 break
//             }
//         }
//         let namaPegawai = null

//         for (let zz = 0; zz < d_Pegawai2.value.length; zz++) {
//             const elementPeg = d_Pegawai2.value[zz];
//             if (item2.pegawai == elementPeg.value) {
//                 namaPegawai = elementPeg.label
//                 break
//             }
//         }
//         petugas.push({
//             "objectjenispetugaspefk": item2.jenisPelaksana,
//             "jenispetugaspe": jenispet,
//             "listpegawai": [
//                 {
//                     'id': item2.pegawai,
//                     'namalengkap': namaPegawai
//                 }
//             ]
//         })

//         var jasacito = 0
//         let data: any = {
//             'no': nomor,
//             'tglpelayanan': H.formatDate(item2.tglpelayanan, 'YYYY-MM-DD HH:mm'),
//             'produkfk': TINDAKAN.objectprodukfk,
//             'namaproduk': TINDAKAN.namaproduk,
//             'hargasatuan': hargasatuan,
//             'jumlah': 1,
//             'pelayananpetugas': petugas,
//             'komponenharga': listKomponen,
//             'iscito': item.iscito ? item.iscito : false,
//             'icon': item.iscito == true ? "<i class='iconify is-success' data-icon='feather:check-circle' aria-hidden='true'></i>" : "<i class='iconify is-danger' data-icon='feather:x-circle' aria-hidden='true'></i>",
//             'jasacito': jasacito,
//             'isparamedis': item.isparamedis ? item.isparamedis : false,
//             'diskon': 0,
//             'subtotal': parseFloat(hargasatuan) * parseFloat(1) + jasacito
//         }
//         data2.value.push(data)

//         isDetail.value[data2.value.length] = true
//         dataSourceTindakan.value = data2.value
//         e.isLoading = false
//         H.alert('info', TINDAKAN.namaproduk + '  berhasil ditambahkan .')

//         countTotal()
//         clearInput()
//     }
//     isPaket.value = false
//     modalPaket.value = false
//     isLoading.value = false
// }
const tambahPaket = async (e: any) => {
    try {
        // isPaketTambah.value = true
        e.isLoading = true;
        let totalHargaDefault = 0;
        // if (!item2.jenisPelaksana) {
        //   H.alert('error', 'Jenis Pelaksana harus di isi')
        //   return
        // }
        // if (!item2.pegawai) {
        //     H.alert('error', 'Pegawai harus di isi')
        //     return
        // }
        // Iterasi pertama untuk menghitung total harga default
        for (const TINDAKAN of e.details) {
            const response = await useApi().get(
                '/tindakan/list-tindakan-komponen?idRuangan=' + item.registrasi.objectruanganfk
                + '&idKelas=' + item.registrasi.objectkelasfk
                + '&idProduk=' + TINDAKAN.objectprodukfk
                + '&idJenisPelayanan=' + item.registrasi.jenispelayananfk
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
                '/tindakan/list-tindakan-komponen?idRuangan=' + item.registrasi.objectruanganfk
                + '&idKelas=' + item.registrasi.objectkelasfk
                + '&idProduk=' + TINDAKAN.objectprodukfk
                + '&idJenisPelayanan=' + item.registrasi.jenispelayananfk
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
            let jenispet = d_JenisPelaksana.value.find(jp => jp.value === item2.jenisPelaksana)?.label || '';
            let namaPegawai = d_Pegawai2.value.find(pg => pg.value === item2.pegawai)?.label || '';
            petugas.push({
                "objectjenispetugaspefk": item2.jenisPelaksana,
                "jenispetugaspe": jenispet,
                "listpegawai": [
                    {
                        'id': item2.pegawai,
                        'namalengkap': namaPegawai
                    }
                ]
            });
            console.log("petugas woi", petugas.value);
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
                // 'pelaksana': petugas,
                'komponenharga': listKomponen,
                'iscito': item.iscito || false,
                'icon': item.iscito ? "<i class='iconify is-success' data-icon='feather:check-circle' aria-hidden='true'></i>" : "<i class='iconify is-danger' data-icon='feather:x-circle' aria-hidden='true'></i>",
                'jasacito': 0,
                'isparamedis': item.isparamedis || false,
                'diskon': 0,
                'subtotal': parseFloat(hargasatuan) * 1,
                'id': TINDAKAN.objectprodukfk
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
watch(() => dataResult.value.length,(newValue) => {
    if(newValue != dataRiwayat.value.length) {
        disabledSave.value = false
        dataRiwayat.value =[]
    }
    if (newValue == dataRiwayat.value.length) {
      disabledSave.value = false
    }
  })

watch(activeValue, (value: any) => {
    emit('update:selected', value)
})

watch(
    () => activeValue.value,
    () => dataResult.value,
    (value) => {
        if (value == 2) {
            fetchBill()
        }
        if(activeValue.value == 2) {
            fetchBill()
        }

    }
)

watch(() => isPaket.value, (newValue, oldValue) => {
    if (newValue == true) {
        fetchPaket()
    }
})
onBeforeMount(() => {
    try {
        const noregistrasi = props.registrasi ? props.registrasi.noregistrasi :''
        let cache =  H.cacheEMR().get(`TAB~${noregistrasi}~${route.name}`)
        if(cache){
            // item = cache.item
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
        // if(!isSavedTindakan.value) {
        //     confirm.require({
        //       message: `Data Tindakan belum di Simpan, apakah anda yakin ingin keluar ?`,
        //       header: 'Konfirmasi Data Tindakan',
        //       icon: 'pi pi-info-circle',
        //       acceptClass: 'p-button-danger',
        //       accept: async () => {
        //         // next();
        //         return true;
        //       },
        //       reject: () => {
        //         return false
        //       },
        //     })
        // }
        const noregistrasi = props.registrasi ? props.registrasi.noregistrasi :''
        let rouutename = from?.name
        if (noregistrasi) {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, {
                // 'item':item,
                'dataSource':dataSource.value,
                'data2':data2.value,
            })
        }
        next();
        // return false;
        
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
});

function routeLeaves(to, from, next) {

}

function getTempTindakan() {
    useApi().get(`tindakan/get-temp-tindakan?norec_pd=${props.registrasi.norec_pd}&nocmfk=${ID_PASIEN}`).then((dtTemp) => {
        console.log("DATA TEMP", dtTemp);
        if(dtTemp && dtTemp.data.length > 0) {
            isSavedTindakan.value = dtTemp.issaved
            dataResult.value = dtTemp.data
        }
    })
}

onMounted(async () => {
    await pasienByID(ID_PASIEN)
    await dropdownList()
    getTempTindakan()
    // fetchBill();
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

.is-rounded-select{
  .p-calendar{
    border-radius : 20px !important;
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

.field > label {
    font-family: var(--font);
    font-size: 0.9rem;
    color: var(--light-text) !important;
    font-weight: 400;
}
</style>
