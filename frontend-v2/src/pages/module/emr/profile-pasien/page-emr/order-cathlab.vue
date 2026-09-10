<template>
    <ConfirmDialog />
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3> Cathlab</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton> -->
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2" v-if="!props.pasien">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoading">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoading">
                                <HeadPasien :pasien="pasien" class="m-3" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form class="form-layout is-separate">
            <div class="form-outer">
                <div class="form-body">
                    <div class="columns is-multiline">
                        <div class="column is-12 ">
                            <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
                                <VCard>
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
                                    <!-- <VTabs type="boxed" selected="order" :tabs="[
                                  { label: 'Order', value: 'order' },
                                  { label: 'Riwayat', value: 'riw' },
                                ]">

                                </VTabs> -->
                                </VCard>
                                <!-- <TabView v-model:activeIndex="active">
                                <TabPanel>
                                    <template #header>
                                        <i class="pi pi-calendar mr-2"></i>
                                        <span> Order</span>
                                    </template>

                                </TabPanel>
                                <TabPanel>
                                    <template #header>
                                        <i class="pi pi-user mr-2"></i>
                                        <span> Riwayat</span>
                                    </template>

                                </TabPanel>
                            </TabView> -->
                            </div>
                        </div>
                        <div class="column is-12 mt-0 " v-if="activeValue == 1">
                            <div class="columns is-multiline">
                                <div class="column is-12 ">
                                    <div class="form-section  pt-0 pr-0">
                                        <div class="form-section-inner has-padding-bottom h-700-o">
                                            <h3 class="has-text-centered">Detail Order </h3>
                                            <div class="columns is-multiline">
                                               <div class="column is-12">
                                                <VField label="Tanggal">
                                                    <VDatePicker v-model="item.tglorder" mode="date" style="width: 100%;">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                        </VControl>
                                                        </VField>
                                                    </template>
                                                    </VDatePicker>
                                                </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Ruangan Asal">
                                                        <VControl icon="feather:map-pin">
                                                            <VInput type="text" placeholder="" autocomplete="off"
                                                                v-model="item.registrasi.namaruangan" disabled />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Ruangan Tujuan"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="feather:list" fullwidth>
                                                            <Multiselect mode="single" v-model="item.ruanganTujuan"
                                                                :options="d_Ruangan" placeholder="Pilih data"
                                                                :searchable="true" :attrs="{ id }" autocomplete="off"
                                                                @select="changeRuangan(item.ruanganTujuan)"/>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Pengorder "
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="fa:user-md" fullwidth>
                                                            <Multiselect mode="single" v-model="item.pegawaiOrder"
                                                                placeholder="Pilih data" :searchable="true"
                                                                :filter-results="false" :min-chars="0" :attrs="{ id }"
                                                                :resolve-on-load="true" :delay="0" :options="d_Pegawai"
                                                                autocomplete="off" />

                                                        </VControl>
                                                    </VField>
                                                </div>



                                                <div class="column is-12" style="margin-top: 50px;">
                                                    <VField>
                                                        <VLabel>Diagnosis</VLabel>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" v-model="item.diagnosis" placeholder="Diagnosis" class="is-rounded" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel>Perkiraan Durasi</VLabel>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" v-model="item.durasi" placeholder="Durasi" class="is-rounded" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Tanggal Rencana Operasi">
                                                        <VDatePicker v-model="item.tgloperasi" mode="dateTime"
                                                            style="width: 100%;">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Persiapan Khusus">
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="item.persiapan" rows="2"
                                                                placeholder="Persiapan khusus (optional) ..."
                                                                autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <!-- <div class="column is-12">
                                                    <VField label="Nama Tindakan Operasi"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="feather:home" fullwidth>
                                                            <Multiselect mode="single" v-model="item.jenisOperasi"
                                                                :options="d_JenisOperasi" placeholder="Pilih data"
                                                                :searchable="true" :attrs="{ id }" autocomplete="off"/>
                                                        </VControl>
                                                    </VField>
                                                </div> -->

                                                <div class="column is-12">
                                                    <VField label="Rencana Tindakan">
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="item.keterangan" rows="2"
                                                                placeholder="Rencana tindakan (optional) ..."
                                                                autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-12">
                                                    <VField label="Pasien Anastesi"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="feather:home" fullwidth>
                                                            <Multiselect mode="single" v-model="item.isanastesi"
                                                                :options="listAnastesi" placeholder="Pilih data"
                                                                :searchable="true" :attrs="{ id }" autocomplete="off"
                                                                @change="changePasienAnastesi($event)" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-12">
                                                    <VField label="Dokter Operator"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="fa:user-md" fullwidth>
                                                            <Multiselect mode="single" v-model="item.dokterOperator"
                                                                placeholder="Pilih data" :searchable="true"
                                                                :min-chars="3" :attrs="{ id }" :delay="0" :options="d_Pegawai"
                                                                autocomplete="off" @complete="fetchOnlyDokter($event)"/>

                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12 mt-2" >
                                                    <div class="columns is-multiline" v-for="(dokterTambahan, index) in item.dokterOperatorTambahan" :key="index">
                                                        <div class="column is-11">
                                                            <VField label="Dokter Operator Tambahan (optional)"
                                                                class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }">
                                                                <VControl icon="fa:user-md" fullwidth>
                                                                    <Multiselect mode="single" v-model="item.dokterOperatorTambahan[index].id"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :min-chars="3" :attrs="{ id }" :delay="0" :options="d_Pegawai"
                                                                        autocomplete="off" @complete="fetchDokter($event)"/>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-1">
                                                            <div class="button-wrap mt-5 pt-2">
                                                                <VButton type="button" @click="tambahDokter()" color="primary"
                                                                    bold raised size="small" icon="feather:plus">
                                                                </VButton>
                                                                <VButton type="button" @click="hapusDokter(index)" color="danger"
                                                                    bold raised size="small" icon="feather:trash-2" v-if="index > 0">
                                                                </VButton>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="column is-12 mt-2">
                                                    <VField label="Dokter Anastesi"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="fa:user-md" fullwidth>
                                                            <Multiselect mode="single" v-model="item.dokterAnastesi"
                                                                placeholder="Pilih data" :searchable="true"
                                                                :filter-results="false" :min-chars="0" :attrs="{ id }"
                                                                :resolve-on-load="true" :delay="0" :options="d_Pegawai"
                                                                autocomplete="off" @complete="fetchDokter($event)"/>

                                                        </VControl>
                                                    </VField>
                                                </div> -->
                                                <div class="column is-12 mt-2" v-if="item.isanastesi == 1">
                                                    <div class="columns is-multiline" v-for="(dokterAnastesi, index) in item.dokterAnastesi" :key="index">
                                                        <div class="column is-10">
                                                            <VField label="Dokter Anastesi Konsul Di poli"
                                                                class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }">
                                                                <VControl icon="fa:user-md" fullwidth>
                                                                    <Multiselect mode="single" v-model="item.dokterAnastesi[index].id"
                                                                        placeholder="Pilih data" :searchable="true"
                                                                        :min-chars="3" :attrs="{ id }"
                                                                        :delay="0" :options="d_Pegawai"
                                                                        autocomplete="off" @complete="fetchDokter($event)"/>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-2">
                                                            <div class="button-wrap mt-5 pt-2">
                                                                <VButton type="button" @click="tambahDokterAnastesi()" color="primary"
                                                                    bold raised size="small" icon="feather:plus">
                                                                </VButton>
                                                                <VButton type="button" @click="hapusDokterAnastesi(index)" color="danger"
                                                                    bold raised size="small" icon="feather:trash-2" v-if="index > 0">
                                                                </VButton>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-6 mt-2">
                                                    <VField>
                                                        <VLabel>TB</VLabel>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" v-model="item.tb" @keypress="onlyNumber($event)" placeholder="cm" class="is-rounded" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6 mt-2">
                                                    <VField>
                                                        <VLabel>BB</VLabel>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" v-model="item.bb" @keypress="onlyNumber($event)" placeholder="kg" class="is-rounded" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel>Jaminan</VLabel>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" v-model="item.jaminan" placeholder="Jaminan" class="is-rounded" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel>No HP Pasien</VLabel>
                                                        <VControl icon="feather:phone">
                                                            <VInput type="text" v-model="item.nohp" @keypress="onlyNumber($event)" placeholder="No HP Pasien" class="is-rounded" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel>No HP Keluarga</VLabel>
                                                        <VControl icon="feather:phone">
                                                            <VInput type="text" v-model="item.nohpkel" @keypress="onlyNumber($event)" placeholder="No HP Keluarga" class="is-rounded" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12" style="display: none !important">
                                                    <VField label="Jenis Operasi"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="feather:home" fullwidth>
                                                            <Multiselect mode="single" v-model="item.kamaroperasi"
                                                                :options="d_Kamar" placeholder="Pilih data"
                                                                :searchable="true" :attrs="{ id }" autocomplete="off"/>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Amprahan Alat">
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="item.alat" rows="2"
                                                                placeholder="Amprahan Alat (Opsional) ..."
                                                                autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Riwayat Swab">
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="item.riwayatSwab" rows="2"
                                                                placeholder="Riwayat Swab ..."
                                                                autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Riwayat Vaksin">
                                                        <VControl>
                                                            <VTextarea class="textarea" v-model="item.riwayatvaksin" rows="2"
                                                                placeholder="Riwayat Vaksin ..."
                                                                autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-12">
                                                    <p>Tingkat Urgensi</p>
                                                    <!-- <VLabel>Tingkat Urgensi</VLabel> -->
                                                    <div class="columns">
                                                        <div class="column">
                                                            <VField>
                                                                <VControl>
                                                                    <VSwitchBlock v-model="item.isurgent" label="Urgent"
                                                                        color="danger" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column">
                                                            <VField>
                                                                <VControl>
                                                                    <VSwitchBlock v-model="item.iscito" label="Cito"
                                                                        color="warning" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column">
                                                            <VField>
                                                                <VControl>
                                                                    <VSwitchBlock v-model="item.iselektif" label="Elektif"
                                                                        color="success" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 mt-0 pt-0" v-if="listChecked.length > 0">
                                    <div class="form-section pr-0 mt-0 pt-0">
                                        <div class="form-section-inner has-padding-bottom h-700-o  creative-list-widget ">
                                            <h3 class="has-text-centered">Pemeriksaan Terpilih ({{
                                                listChecked.length
                                            }})
                                            </h3>
                                            <div class="columns is-multiline  creative-list">
                                                <div v-for="item in listChecked" :key="item.id"
                                                    class="creative-list-item is-pink">
                                                    <i aria-hidden="true" :class="'lnir lnir-hospital-bed-alt'"></i>
                                                    <div class="meta" style="width:100%">
                                                        <p class="is-pilih-text">{{ item.namaproduk }}</p>
                                                    </div>
                                                    <VTag :color="'danger'" :label="'Hapus'"
                                                        @click="clearSelectionItem(item)"
                                                        class="mt-0 ml-5 is-pulled-right is-cursor" />
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-8 mt-0" v-if="activeValue == 1 && isShow">
                            <div class="form-section pt-0 pl-0">
                                <div class="form-section-inner">
                                    <h3 class="has-text-centered">Detail Pemeriksaan</h3>
                                    <div class="columns is-multiline">
                                        <div class="column is-12">
                                            <UIWidget class="search-widget">
                                                <template #body>
                                                    <div class="field">
                                                        <div class="control">
                                                            <input type="text" v-model="filterLayanan" class="input"
                                                                placeholder="Search..." />
                                                            <button class="searcv-button" type="button">
                                                                <i aria-hidden="true" class="iconify"
                                                                    data-icon="feather:search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </UIWidget>

                                        </div>
                                        <div class="column is-12 h-400-o">
                                            <div class="column is-12" v-for="items in filteredLayanan"
                                                :key="items.detailjenisproduk">
                                                <div class="group-header">
                                                    <VIconBox color="pink"
                                                        style="width:30px;height:30px; min-width: 30px; ">
                                                        <i aria-hidden="true" class="lnir lnir-hospital-bed-alt"
                                                            style="font-size: 1rem"></i>
                                                    </VIconBox>
                                                    <!-- <VIconWrap icon="feather:list" color="warning" /> -->
                                                    <h4 class="ml-1">{{ items.detailjenisproduk }}</h4>
                                                </div>

                                                <div class="columns is-multiline mb-3">
                                                    <div class="column is-4" v-for="itemProd in items.details"
                                                        :key="itemProd.id">
                                                        <VField grouped>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="item.produkCeklis[itemProd.id]"
                                                                    :label="itemProd.namaproduk" color="info"
                                                                    @change="getSelected()" />
                                                            </VControl>
                                                            <!-- <VAnimatedCheckbox v-model="item.produkCeklis[itemProd.id]"
                                                            :value="itemProd.namaproduk" color="danger" checked
                                                            @change="getSelected()" />
                                                        <VLabelText class="mt-2 ml-2">{{itemProd.namaproduk}}
                                                        </VLabelText> -->
                                                        </VField>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-section-outer">
                                    <!-- <div class="checkboxes">
                                    <VField>
                                        <VFlex column-gap="1rem">
                                            <VControl>
                                                <VCheckbox v-model="createAccount" label="Create an account"
                                                    color="primary" circle />
                                            </VControl>
                                            <VControl subcontrol>
                                                <VCheckbox v-model="subscribe" label="Subscribe to our Newsletter"
                                                    color="primary" circle />
                                            </VControl>
                                        </VFlex>
                                    </VField>
                                </div> -->
                                    <!-- <div class="button-wrap mt-2">
                                        <VButton type="button" @click="simpan()" :loading="isLoading" color="primary"
                                            bold raised fullwidth icon="feather:arrow-right">
                                            Simpan Order
                                        </VButton>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 mt-0 mr-4 pr-5" v-if="activeValue == 2">
                            <div class="columns is-multiline">
                                <div class="column is-12 ">
                                    <div class="form-section  pt-0 pr-0">
                                        <div class="form-section-inner has-padding-bottom h-700-o ">
                                            <h3 class="has-text-centered">Riwayat </h3>
                                            <div class="columns is-multiline">
                                                <div class="column is-12">

                                                    <TRiwayatOrderBedah v-if="listRiwayat" title="" straight class="list-widget-v3"
                                                        :items="listRiwayat" @editItems="editItems"
                                                        @hapusItems="DialogConfirm" @hasilItems="hasilItems" squared
                                                        colored :isLoading="isLoading">
                                                    </TRiwayatOrderBedah>

                                                </div>

                                                <div class="column is-12 mt-3">

                                                    <VButton icon="lnir lnir-arrow-left is-fullwidth" color="info"
                                                        dark-outlined @click="activeValue = 1">
                                                        Order Baru
                                                    </VButton>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                          </div>
                     </div>

                 </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import TRiwayatOrderBedah from '../t-riwayat-order-bedah.vue'
useHead({
    title: 'Order Cathlab - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
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
    NOREC_PD: {
      type: Object as PropType<any>,
    },
    nocmfk: {
      type: Object as PropType<any>,
    },
})
useViewWrapper().setFullWidth(props.pasien ? true : false)
const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    tglorder: new Date(),
    produkCeklis: [],
    dokterOperatorTambahan: [{ id: null }],
    dokterAnastesi: [{ id: null }],
    pegawaiOrder: useUserSession().getUser().id,
})
const tabs: any = ref([
    { label: 'Order', value: 1, icon: 'fas fa-file-medical-alt' },
    { label: 'Riwayat', value: 2, icon: 'fas fa-list' }
])
console.log('NOREC PC BEDAH',props.NOREC_PD);
if (NOREC_PD == undefined) {
  NOREC_PD = props.registrasi.norec_pd
}

console.log('NOCMF FK DARI ID PASIEN', ID_PASIEN)

function changePasienAnastesi(e: any) {
    console.log('chage', e)
    if(!e || e.value == 0) {
        item.dokterOperator = [{id: null}];
    }
}

const listChecked: any = ref([])
const selected_count = ref(0);
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
for (let i = 0; i < colors.value.length; i++) {
    const element = colors.value[i];
    if (i <= 9 && element != 'primary')
        listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const listAnastesi: any = ref([
    {
        label: "Anastesi",
        value: 1
    },
    {
        label: "Non Anastesi ( Anastesi Local )",
        value: 0
    },
])
const isShow:any =ref(false)
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const isLoading = ref(false)
const confirm = useConfirm();
const d_Produk: any = ref([])
const d_Pegawai: any = ref([])
const d_ProdukDef: any = ref([])
const d_JenisOperasi: any = ref([])
const d_Kamar: any = ref([])
const filterLayanan: any = ref('')
const selectedTabs: any = ref()
const listRiwayat = ref([])
const emit = defineEmits<{
    (e: 'update:selected', value: string): void
}>()

const activeValue: any = ref(1)
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
const filteredLayanan = computed(() => {

    if (!filterLayanan.value) {
        return d_Produk.value
    }
    var filtered: any = [];

    for (let i = 0; i < d_Produk.value.length; i++) {
        const element = d_Produk.value[i];
        filtered.push({
            'detailjenisproduk': element.detailjenisproduk,
            'id': element.id,
            'details': []
        })
        for (let ii = 0; ii < element.details.length; ii++) {
            const element2 = element.details[ii];
            if (element2.namaproduk.match(new RegExp(filterLayanan.value, 'i'))) {
                filtered[filtered.length - 1].details.push(element2)
            }
        }
    }
    return filtered;
})

function toggle(value: string) {
    activeValue.value = value
}

watch(
    () => selectedTabs,
    (value) => {
        activeValue.value = value
    }
)

watch(activeValue, (value: any) => {
    emit('update:selected', value)
})
watch(
    () => activeValue.value,
    (value) => {
        if (value == 2) {
            loadRiwayat()
        }
    }
)
function loadRiwayat() {
    listRiwayat.value = []
    isLoading.value = true
    useApi().get(
        `/bedah/riwayat-order?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
            let z = 0
            for (let x = 0; x < response.length; x++) {
                const element = response[x];
                element.icon = 'lnir lnir-hospital-bed-alt'
                element.color = listColor2.value[z]
                element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
                element.tgloperasi = H.formatDateIndoSimple(new Date(element.tgloperasi))
                // element.tgloperasi = H.formatDate(new Date(element.tgloperasi),'YYYY-MM-DD')
                if (z > 4) {
                    z = 0
                }
                z++
            }
            listRiwayat.value = response
            isLoading.value = false
        })
}

const loadRiwayat1 = async () => {
  const response_TPI = await useApi().get(
    'emr/auto-fill?nocmfk=' +
      ID_PASIEN +
      '&norec_pd=' +
      NOREC_PD +
      '&collection=AsesmenAwalKeperawatanPasienRawatJalan' +
      `&field=beratbadanObgyn,tinggibadanObgyn`
  )
  item.bb = response_TPI.beratbadanObgyn
  item.tb = response_TPI.tinggibadanObgyn
}
loadRiwayat1()


function pasienByID(id: any) {
    if (props.pasien != undefined) {
        pasien.value = props.pasien
        item.NOREC_APD = props.registrasi.norec_apd
        item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
        item.registrasi = props.registrasi
        item.jaminan = props.kelompokpasien
    } else {
        isLoadingPasien.value = true
        isLoading.value = true
        useApi().get(
            `/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
                pasien.value = response.pasien
                item.NOREC_APD = response.last_registrasi.norec_apd
                item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
                item.registrasi = response.registrasi
                item.jaminan = pasien.value.kelompokpasien
                isLoadingPasien.value = false
                isLoading.value = false
                // fetchTindakan(item.RUANGAN_LAST)
            })
    }

    getAutoFill(item.NOREC_PD);

}

async function getAutoFill(norecpd: string) {
    isLoading.value = true;
    await useApi().get(`bedah/get-data-autofill-bedah?norec_pd=${NOREC_PD}`).then((response: any) => {
        item.jaminan = response.namaexternal;
        item.nohpkel = response.telponpenanggungjawab
        item.nohp = response.nohp
        isLoading.value = false;
    });

}

function changeRuangan(e: any) {
    fetchTindakan(e)
}
function fetchDropdown() {
    useApi().get(
        `/cathlab/list-dropdown`).then((response: any) => {
            console.log(response)
            d_Ruangan.value = response.ruanganLab.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
            item.ruanganTujuan = d_Ruangan.value[0].value
            item.departemenfk = d_Ruangan.value[0].default.objectdepartemenfk
            fetchTindakan(item.ruanganTujuan)
        })
    useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
    ).then((response) => {
        d_Pegawai.value = response
        d_Pegawai.value.forEach(element => {
          if(props.registrasi.objectpegawaifk == element.value){
            item.pegawaiOrder = element.value
          }
        });
    })
}

function fetchOnlyDokter(e: any) {
    let params = e != undefined ? e.filter : '';
    useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=${params}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
    ).then((response) => {
        d_Pegawai.value = response
        d_Pegawai.value.forEach(element => {
            if (props.registrasi.objectpegawaifk == element.value) {
                item.pegawaiOrder = element.value
            }
        });
    })
}

function fetchTindakan(e: any) {
    // isLoading.value = true
    // useApi().get(
    //     `/bedah/list-tindakan-for-order?ruanganfk=${e}`).then((response: any) => {
    //         isLoading.value = false
    //         let x = 0
    //         for (let x = 0; x < response.list_tindakan.length; x++) {
    //             const element = response.list_tindakan[x];
    //             element.color = listColor.value[x]
    //             if (x > 9) {
    //                 x = 0
    //             }
    //             x++
    //         }
    //         d_ProdukDef.value = response.datad_Pegawai
    //         d_Produk.value = response.list_tindakan
    //     })
}
console.log('WOI', props.registrasi.norec_pd);
async function simpan() {
    isLoading.value = true;
    let iserr = false;
    let msgErr = '';
    var anastesitambahan = [];

    if (item.ruanganTujuan == undefined || item.ruanganTujuan == "") {
        msgErr = 'Pilih ruangan tujuan';
        iserr = true;
    }
    if (item.pegawaiOrder == undefined || item.pegawaiOrder == "") {
        msgErr = 'Pilih Pengorder terlebih dahulu';
        iserr = true;
    }
    if (item.tglorder == undefined || item.tglorder == "") {
        msgErr = 'Pilih Tgl Order  terlebih dahulu';
        iserr = true;
    }
    if (item.tgloperasi == undefined || item.tgloperasi == "") {
        msgErr = 'Pilih Tgl Rencana Operasi  terlebih dahulu';
        iserr = true;
    }
    if (item.dokterOperator == undefined || item.dokterOperator == "") {
        msgErr = 'Pilih Dokter Operator';
        iserr = true;
    }
    if (item.isanastesi == 1) {
        item.dokterAnastesi.forEach((el, elindex) => {
            if (el.id == null) {
                msgErr = 'Terdapat Dokter Anastesi yang belum dipilih';
                iserr = true;
            } else {
                if (elindex !== 0) {
                    anastesitambahan.push(el.id);
                }
            }
        });
    }
    if (item.diagnosis == undefined || item.diagnosis == "") {
        msgErr = 'Diagnosis wajib diisi';
        iserr = true;
    }
    if (item.durasi == undefined || item.durasi == "") {
        msgErr = 'Durasi wajib diisi';
        iserr = true;
    }
    if (item.tb == undefined || item.tb == "") {
        msgErr = 'Tinggi Badan wajib diisi';
        iserr = true;
    }
    if (item.bb == undefined || item.bb == "") {
        msgErr = 'Berat Badan wajib diisi';
        iserr = true;
    }
    if (item.jaminan == undefined || item.jaminan == "") {
        msgErr = 'Jaminan wajib diisi';
        iserr = true;
    }
    if (iserr && msgErr != '') {
        H.alert('error', msgErr);
        isLoading.value = false;
        return;
    }

    await H.statusClosingPasien(NOREC_PD);

    var arrobj = Object.keys(item.produkCeklis);
    var data2 = [];
    var dataidTambahan = [];
    for (var i = arrobj.length - 1; i >= 0; i--) {
        if (item.produkCeklis[parseInt(arrobj[i])] == true) {
            var data = {
                no: i + 1,
                produkfk: arrobj[i],
                qtyproduk: 1,
                objectkelasfk: item.registrasi.objectkelasfk,
                nourut: null,
            };
            data2.push(data);
        }
    }
    item.dokterOperatorTambahan.forEach(dot => {
        dataidTambahan.push(dot.id);
    });
    let dokteranastesifk = 0;
    if (item.isanastesi == 1) {
        dokteranastesifk = item.dokterAnastesi[0].id;
    }

    isLoading.value = true;
    var objSave = {
        noregistrasi: item.registrasi.noregistrasi,
        tanggal: H.formatDate(item.tglorder, 'YYYY-MM-DD HH:mm:ss'),
        tgloperasi: H.formatDate(item.tgloperasi, 'YYYY-MM-DD HH:mm:ss'),
        norec_so: item.NOREC_SO ? item.NOREC_SO : '',
        norec_apd: item.NOREC_APD,
        norec_pd: props.registrasi.norec_pd,
        qtyproduk: data2.length,
        objectruanganfk: item.registrasi.objectruanganlastfk,
        pegawaiorderfk: item.pegawaiOrder,
        objectruangantujuanfk: item.ruanganTujuan,
        jenisoperasifk: item.jenisOperasi ? item.jenisOperasi : null,
        kamaroperasifk: item.kamaroperasi ? item.kamaroperasi : null,
        departemenfk: item.departemenfk,
        keterangan: item.keterangan != undefined ? item.keterangan : null,
        iscito: item.iscito != undefined && item.iscito == true ? item.iscito : false,
        dokteroperatorfk: item.dokterOperator,
        dokteroperatortambahan: dataidTambahan.length > 0 ? dataidTambahan : null,
        dokteranastesifk: dokteranastesifk,
        diagnosis: item.diagnosis ? item.diagnosis : null,
        persiapan: item.persiapan ? item.persiapan : null,
        durasi: item.durasi ? item.durasi : null,
        tb: item.tb ? item.tb : null,
        bb: item.bb ? item.bb : null,
        jaminan: item.jaminan ? item.jaminan : null,
        nohp: item.nohp ? item.nohp : null,
        nohpkel: item.nohpkel ? item.nohpkel : null,
        iselektif: item.iselektif != undefined && item.iselektif == true ? item.iselektif : false,
        isurgent: item.isurgent != undefined && item.isurgent == true ? item.isurgent : false,
        alat: item.alat != undefined ? item.alat : null,
        riwayatSwab: item.riwayatSwab != undefined ? item.riwayatSwab : null,
        riwayatvaksin: item.riwayatvaksin != undefined ? item.riwayatvaksin : null,
        details: data2,
        isanastesi: item.isanastesi,
        anastesitambahan: anastesitambahan,
    };

    useApi()
        .post(`/bedah/simpan-order`, objSave)
        .then((response: any) => {
            isLoading.value = false;
            sendNotification(response);

            delete item.NOREC_SO;
        })
        .catch((e: any) => {
            isLoading.value = false;
        });
}

function clearSelection() {
    var arrobj = Object.keys(item.produkCeklis)
    for (let x = 0; x < arrobj.length; x++) {
        const element2 = arrobj[x];
        item.produkCeklis[element2] = false
    }
    getSelected()
}
function clearSelectionItem(select: any) {
    var arrobj = Object.keys(item.produkCeklis)
    for (let x = 0; x < arrobj.length; x++) {
        const element2 = arrobj[x];
        if (element2 == select.id) {
            item.produkCeklis[element2] = false
        }
    }
    getSelected()
}

function kembaliKeun() {
    window.history.back()
}

async function fetchDokter(filter: any) {
    let query = ''
    if (filter) {
        query = filter.toLowerCase()
    }
    const response = await useApi().get(
        `/general/dokter-paging?name= ${query}&limit=10`)

    return response.dokter.map((item: any) => {
        return { value: item.id, label: item.namalengkap, default: item }
    })
}

function
    getSelected() {
    if (item.produkCeklis.length > 0) {
        var arrobj = Object.keys(item.produkCeklis)
        for (var x = 0; x < arrobj.length; x++) {
            const element = arrobj[x];
            if (item.produkCeklis[parseInt(element)] == true) {
                for (var i = 0; i < d_ProdukDef.value.length; i++) {
                    const element2 = d_ProdukDef.value[i];
                    if (element2.id == element) {
                        for (var z = 0; z < listChecked.value.length; z++) {
                            const element3 = listChecked.value[z];
                            if (element3.namaproduk == element2.namaproduk) {
                                listChecked.value.splice(z, 1)
                            }
                        }
                        listChecked.value.push({ namaproduk: element2.namaproduk, id: element2.id })
                    }
                }
            } else {
                for (var i = 0; i < d_ProdukDef.value.length; i++) {
                    const element2 = d_ProdukDef.value[i];
                    if (element2.id == element) {
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

    }
}
async function editItems(e: any) {
    if (e.status != 'pending') {
        H.alert('error', 'Order sudah diverifikasi')
        return
    }
    isLoading.value = true;
    item.NOREC_SO = e.norec
    useApi().get(
        `/bedah/detail-order?norec=${e.norec}`).then((response: any) => {
            for (let x = 0; x < response.length; x++) {
                const element = response[x];
                item.pegawaiOrder = element.objectpegawaiorderfk
                console.log("DATA FROM EDIT", element);

                useApi().get(
                    `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
                ).then((response) => {
                    d_Pegawai.value = response
                    d_Pegawai.value.forEach(elementd => {
                        if (element.objectdokteroperatorfk == elementd.value) {
                            item.dokterOperator = elementd
                            console.log('Halo')
                        }

                        if (element.objectdokteranastesifk == elementd.value) {
                            item.dokterAnastesi = elementd
                        }
                    });
                })

                item.jaminan = element.jaminan
                item.diagnosis = element.diagnosis
                item.persiapan = element.persiapan
                item.durasi = element.durasi
                item.tb = element.tb
                item.bb = element.bb
                item.tgloperasi = new Date(element.tgloperasi)
                item.tglorder = new Date(element.tglorder)
                item.ruanganTujuan = element.objectruangantujuanfk
                item.keterangan = element.keteranganlainnya
                item.iscito = element.cito
                item.iselektif = element.iselektif
                item.isurgent = element.isurgent
                item.isanastesi = element.isanastesi && element.isanastesi == true ? 1 : 0
                item.riwayatSwab = element.riwayatswab
                item.riwayatvaksin = element.riwayatvaksin
                item.alat = element.alat
                let arrOp = [];
                let arrAn = [];

                JSON.parse(element.operatorhelperfk).forEach(o => {
                    arrOp.push({id: o});
                });
                JSON.parse(element.anastesitambahanfk).forEach(a => {
                    arrAn.push({id: a});
                });

                item.dokterOperator = element.dokteroperatorfk;
                item.dokterOperatorTambahan = arrOp.length > 0 ? arrOp : [{id: null}];
                item.dokterAnastesi = arrAn.length > 0 ? arrAn : [{id: null}];

                item.produkCeklis[parseInt(element.produkfk)] = true
                getSelected()
                activeValue.value = 1
                isLoading.value = false;
            }
        }).catch((e: any) => {
        })
}
const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            hapusItems(e)

        },
        reject: () => { },
    })
}
function hapusItems(e: any) {
    if (e.status != 'pending') {
        H.alert('error', 'Order sudah diverifikasi')
        return
    }
    useApi().post(
        `/bedah/delete-order-bedah`, { noorder: e.noorder }).then((response: any) => {
            isLoading.value = false
            loadRiwayat()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const tambahDokter = () => {
    item.dokterOperatorTambahan.push({ id: null });
};

const hapusDokter = (index: number) => {
    item.dokterOperatorTambahan.splice(index, 1);
};

const tambahDokterAnastesi = () => {
    item.dokterAnastesi.push({ id: null });
};

const hapusDokterAnastesi = (index: number) => {
    item.dokterAnastesi.splice(index, 1);
};

function onlyNumber(evt: any) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();
    } else {
        return true;
    }
}

const sendNotification = (e) => {
    let ruanganAsal = ''
    let ruanganTujuan = ''
    let namapengorder = ''
    d_Ruangan.value.forEach((element: any) => {
        if (element.value == e.data.objectruangantujuanfk) {
            ruanganTujuan = element.label
        }
        if (element.value == e.data.objectruanganfk) {
            ruanganAsal = element.label
        }
    });

    d_Pegawai.value.forEach((dtPegawai: any) => {
        if(item.pegawaiOrder == dtPegawai.value) {
            namapengorder = dtPegawai.label;
        }
    })

    let body = {
        norec: e.data.norec,
        judul: 'Order Bedah #' + e.data.noorder,
        jenis: e.data.keteranganorder,
        pesanNotifikasi: `Order Jadwal Bedah dari ${ruanganAsal} ke ${ruanganTujuan}`,
        idRuanganAsal: e.data.objectruanganfk,
        idRuanganTujuan: e.data.objectruangantujuanfk,
        ruanganAsal: ruanganAsal,
        ruanganTujuan: ruanganTujuan,
        kelompokUser: null,
        idKelompokUser: null,
        idPegawai: e.data.objectpegawaiorderfk,//H.pegawaiLogin().id,
        namapegawai: namapengorder,//H.pegawaiLogin().id,
        dataArray: [],
        urlForm: 'module-dashboard-bedah',
        params: null,
        group: 'mapping_login',
        namaFungsiFrontEnd: null,
        tgl: e.data.tglorder,
        tgl_string: H.formatDateIndoSimple(e.data.tglorder),
    }
    H.sendSocket("sendNotification", body);
}

function hasilItems(e: any) {

}

onMounted(async () => {
    await pasienByID(ID_PASIEN)
    fetchDropdown()
    fetchOnlyDokter();
})
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/order-laboratorium.scss';
</style>
