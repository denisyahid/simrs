<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <label class="title-page">Jasa Layanan Pagu</label>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline" v-if="activeTab == 0">
                        <div class="column is-3">
                            <VField label=" Tanggal">
                                <VDatePicker v-model="item.filterTgl" is-range color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="Status Verifikasi">
                                <Multiselect v-model="item.statusVerif" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_StatusVerifikasi" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </VField>
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-2">
                            <span>Nomor RM</span>
                            <VControl icon="feather:user">
                                <VInput type="text" class="input" v-model="item.nocm" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Nomor Registrasi</span>
                            <VControl icon="feather:airplay">
                                <VInput type="text" class="input" v-model="item.noregis"
                                    @input="inputFormat(item.noregis, 'noregis')" @keypress="onlyAllowDigits" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Nomor SEP</span>
                            <VControl icon="feather:credit-card">
                                <VInput type="text" class="input" v-model="item.nosep"
                                    @input="inputFormat(item.nosep, 'nosep')" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Kelompok Pasien</span>
                            <VControl icon="feather:book-open">
                                <Dropdown v-model="item.kelompokpasien" :options="d_KelompokPasien" filter
                                    optionLabel="kelompokpasien" style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Tipe Perawatan</span>
                            <VControl icon="feather:home">
                                <Dropdown v-model="item.departemen" :options="d_Departemen" filter
                                    optionLabel="namadepartemen" style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-1 btn-search ml-auto mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchPagu()"
                                :loading="loadSearch" />
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-2">
                            <span>Description</span>
                            <VControl icon="feather:bookmark">
                                <Dropdown v-model="item.carabayar" :options="d_CaraBayar" filter optionLabel="carabayar"
                                    style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                    </div>
                    <div class="columns is-multiline" v-if="activeTab == 1">
                        <div class="column is-3">
                            <VField label="Tanggal Pulang">
                                <VDatePicker v-model="item.filterTgl" is-range color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Tanggal Tindakan">
                                <VDatePicker v-model="item.filterTglTindakan" is-range color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="Status Verifikasi">
                                <Multiselect v-model="item.statusVerif" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_StatusVerifikasi" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </VField>
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-2">
                            <span>Nomor Registrasi</span>
                            <VControl icon="feather:airplay">
                                <VInput type="text" class="input" v-model="item.noregis"
                                    @input="inputFormat(item.noregis, 'noregis')" @keypress="onlyAllowDigits" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Nomor SEP</span>
                            <VControl icon="feather:credit-card">
                                <VInput type="text" class="input" v-model="item.nosep"
                                    @input="inputFormat(item.nosep, 'nosep')" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Section</span>
                            <VControl icon="feather:home">
                                <Dropdown v-model="item.ruangan" :options="d_Ruangan" filter optionLabel="namaruangan"
                                    style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Kebangsaan</span>
                            <VControl icon="feather:flag">
                                <Dropdown v-model="item.kebangsaan" :options="d_Kebangsaan" filter optionLabel="name"
                                    style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Kelompok Pasien</span>
                            <VControl icon="feather:book-open">
                                <Dropdown v-model="item.kelompokpasien" :options="d_KelompokPasien" filter
                                    optionLabel="kelompokpasien" style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-1 btn-search ml-auto mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchPagu()"
                                :loading="loadSearch" />
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-2">
                            <span>DPJP</span>
                            <VControl icon="feather:user">
                                <AutoComplete v-model="item.dpjp" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                    </div>
                    <div class="columns is-multiline" v-if="activeTab == 2">
                        <div class="column is-3">
                            <VField label="Tanggal Pulang">
                                <VDatePicker v-model="item.filterTgl" is-range color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Tanggal Tindakan">
                                <VDatePicker v-model="item.filterTglTindakan" is-range color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="Status Verifikasi">
                                <Multiselect v-model="item.statusVerif" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_StatusVerifikasi" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </VField>
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-2">
                            <span>Nomor RM</span>
                            <VControl icon="feather:user">
                                <VInput type="text" class="input" v-model="item.nocm" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Nomor Registrasi</span>
                            <VControl icon="feather:airplay">
                                <VInput type="text" class="input" v-model="item.noregis"
                                    @input="inputFormat(item.noregis, 'noregis')" @keypress="onlyAllowDigits" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Nomor SEP</span>
                            <VControl icon="feather:credit-card">
                                <VInput type="text" class="input" v-model="item.nosep"
                                    @input="inputFormat(item.nosep, 'nosep')" />
                            </VControl>
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-2">
                            <span>Section</span>
                            <VControl icon="feather:home">
                                <Dropdown v-model="item.ruangan" :options="d_Ruangan" filter optionLabel="namaruangan"
                                    style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Kebangsaan</span>
                            <VControl icon="feather:flag">
                                <Dropdown v-model="item.kebangsaan" :options="d_Kebangsaan" filter optionLabel="name"
                                    style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Kelompok Pasien</span>
                            <VControl icon="feather:book-open">
                                <Dropdown v-model="item.kelompokpasien" :options="d_KelompokPasien" filter
                                    optionLabel="kelompokpasien" style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>DPJP</span>
                            <VControl icon="feather:user">
                                <AutoComplete v-model="item.dpjp" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Tindakan</span>
                            <VControl class="prime-auto" icon="feather:box">
                                <AutoComplete v-model="item.produk" :suggestions="d_Produk"
                                    @complete="fetchProduk($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :optionLabel="'label'" :field="'label'" />
                            </VControl>
                        </div>
                        <div class="column is-1 btn-search ml-auto mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchPagu()"
                                :loading="loadSearch" />
                        </div>
                        <div class="column is-12 p-0"></div>
                    </div>
                    <div class="columns is-multiline" v-if="activeTab == 3">
                        <div class="column is-3">
                            <VField label="Tanggal Pulang">
                                <VDatePicker v-model="item.filterTgl" is-range color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-3">
                            <VField label="Tanggal Tindakan">
                                <VDatePicker v-model="item.filterTglTindakan" is-range color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField label="Status Verifikasi">
                                <Multiselect v-model="item.statusVerif" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_StatusVerifikasi" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </VField>
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-2">
                            <span>Nomor Registrasi</span>
                            <VControl icon="feather:airplay">
                                <VInput type="text" class="input" v-model="item.noregis"
                                    @input="inputFormat(item.noregis, 'noregis')" @keypress="onlyAllowDigits" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Nomor SEP</span>
                            <VControl icon="feather:credit-card">
                                <VInput type="text" class="input" v-model="item.nosep"
                                    @input="inputFormat(item.nosep, 'nosep')" />
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Section</span>
                            <VControl icon="feather:home">
                                <Dropdown v-model="item.ruangan" :options="d_Ruangan" filter optionLabel="namaruangan"
                                    style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Kebangsaan</span>
                            <VControl icon="feather:flag">
                                <Dropdown v-model="item.kebangsaan" :options="d_Kebangsaan" filter optionLabel="name"
                                    style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-2">
                            <span>Kelompok Pasien</span>
                            <VControl icon="feather:book-open">
                                <Dropdown v-model="item.kelompokpasien" :options="d_KelompokPasien" filter
                                    optionLabel="kelompokpasien" style="width: 100%;">
                                </Dropdown>
                            </VControl>
                        </div>
                        <div class="column is-1 btn-search ml-auto mt-3">
                            <VIconButton color="success" icon="fas fa-search" @click="fetchPagu()"
                                :loading="loadSearch" />
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-2">
                            <span>Dokter</span>
                            <VControl icon="feather:user">
                                <AutoComplete v-model="item.dpjp" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column is-flex" style="justify-content: center;align-items: center;">
                    <div style="text-align: center;">
                        <h1 style="font-size: large;">Total data selected: {{ selectData ? selectData.length : 0 }}</h1>
                        <h1 v-if="activeTab == 0">
                            <span style="color: green;">Sudah diverifikasi: {{selectData.filter(data => data.status1 ==
                                1 ||
                                data.status2 == 1).length }}</span>,
                            <span style="color: red;">Batal verifikasi: {{selectData.filter(data => data.status1 == 2
                                || data.status2
                                == 2).length }}</span>
                        </h1>
                        <h1 v-else>
                            <span style="color: green;">Sudah diverifikasi: {{selectData.filter(data => data.status ==
                                1).length
                                }}</span>,
                            <span style="color: red;">Batal verifikasi: {{selectData.filter(data => data.status ==
                                2).length }}</span>
                        </h1>

                        <VButton v-if="activeTab == 0" class="my-1 mx-2" icon="feather:check-circle" color="primary"
                            :disabled="(selectData && selectData.length == 0) || loadData == true || (selectData && selectData.filter(data => data.status1 != null || data.status2 != null).length > 0)"
                            @click="updateStatusAll(selectData, 'Verifikasi', '')">
                            Verifikasi All
                        </VButton>
                        <VButton v-else class="my-1 mx-2" icon="feather:check-circle" color="primary"
                            :disabled="selectData && selectData.length == 0 || loadData == true || (selectData && selectData.filter(data => data.status != null).length > 0)"
                            @click="updateStatusAll(selectData, 'Verifikasi', '')">
                            Verifikasi All
                        </VButton>
                        <!-- <VButton class="my-1 mx-2" icon="feather:x-circle"
                            :disabled="selectData && selectData.length == 0 || loadData == true" color="danger"
                            @click="updateStatusAll(selectData, 'Batal Verifikasi', '')">
                            Batal Verifikasi All
                        </VButton> -->
                    </div>
                </div>

                <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column is-12">
                    <h1 style="font-weight: normal;">
                        Catatan : 
                        Fitur verifikasi semua hanya tersedia untuk data yang belum diverifikasi atau belum
                        ditindaklanjuti.
                    </h1>
                </div>

                <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column py-0">
                    <TabView @tab-click="klikTab($event)">
                        <TabPanel>
                            <template #header>
                                <i class="fas fa-users mr-2" aria-hidden="true"></i>
                                <span>NOREG</span>
                            </template>
                        </TabPanel>
                        <TabPanel>
                            <template #header>
                                <i class="fas fa-shopping-cart mr-2" aria-hidden="true"></i>
                                <span>LAYANAN</span>
                            </template>
                        </TabPanel>
                        <TabPanel>
                            <template #header>
                                <i class="fas fa-bed mr-2" aria-hidden="true"></i>
                                <span>IBSA</span>
                            </template>
                        </TabPanel>
                        <TabPanel>
                            <template #header>
                                <i class="fas fa-plus-square mr-2" aria-hidden="true"></i>
                                <span>OBAT</span>
                            </template>
                        </TabPanel>
                    </TabView>
                </div>

                <div class="column py-0">
                    <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                    <div v-else>
                        <div v-if="activeTab == 0">
                            <DataTable :rows="10" :value="dataSource" :rowsPerPageOptions="[5, 10, 15, 50, 100, 1000]"
                                class="p-datatable-sm" v-model:selection="selectData" showGridlines
                                tableStyle="min-width: 30rem" paginator :rowClass="rowClassNoreg" :dataKey="metaKey">
                                <template #header>
                                    <div class="columns is-multiline column">
                                        <div class="column is-4 pt-0 pb-0 is-flex is-align-items-center">
                                            <VButtons style="justify-content: space-between;">
                                                <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                    icon="fas fa-file-excel">
                                                    Export To Excel
                                                </VButton>
                                            </VButtons>
                                        </div>
                                        <div class="column is-4 is-justify-content-center is-flex">
                                            <span style="font-weight: bold;">NOREG</span>
                                        </div>
                                        <div class="column is-4 is-justify-content-right is-flex">
                                            <span style="font-weight: bold;">Total : {{ dataSource.length ?
                                                dataSource.length : 0 }}</span>
                                        </div>
                                    </div>
                                </template>
                                <Column selectionMode="multiple"></Column>
                                <Column field="" header="#" class="mw">
                                    <template #body="slotProps">
                                        <div class="column is-flex"
                                            style="justify-content: center;align-items: center;">
                                            <!-- Pasien Umum -->
                                            <div style="text-align: center;" v-if="slotProps.data.norec_sbmc">
                                                <VButton v-if="!slotProps.data.status2 || slotProps.data.status2 == 2"
                                                    class="my-1" icon="feather:check-circle" color="primary"
                                                    @click="updateStatus(slotProps.data, 'Verifikasi', '')">
                                                    Verifikasi
                                                </VButton>
                                                <VButton v-else-if="slotProps.data.status2 == 1" class="my-1"
                                                    icon="feather:x-circle" color="danger"
                                                    @click="updateStatus(slotProps.data, 'Batal Verifikasi', '')">
                                                    Batal Verifikasi
                                                </VButton>
                                            </div>
                                            <!-- Pasien BPJS -->
                                            <div style="text-align: center;" v-if="!slotProps.data.norec_sbmc">
                                                <VButton v-if="!slotProps.data.status1 || slotProps.data.status1 == 2"
                                                    class="my-1" icon="feather:check-circle" color="primary"
                                                    @click="updateStatus(slotProps.data, 'Verifikasi', '')">
                                                    Verifikasi
                                                </VButton>
                                                <VButton v-else-if="slotProps.data.status1 == 1" class="my-1"
                                                    icon="feather:x-circle" color="danger"
                                                    @click="updateStatus(slotProps.data, 'Batal Verifikasi', '')">
                                                    Batal Verifikasi
                                                </VButton>
                                            </div>
                                        </div>
                                    </template>
                                </Column>
                                <Column header="Alasan Pembatalan" class="mw">
                                    <template #body="slotProps">
                                        {{ slotProps.data.norec_sbmc ? slotProps.data.alasan_pembatalan2 :
                                            slotProps.data.alasan_pembatalan1 }}
                                    </template>
                                </Column>
                                <Column header="Nomor Verifikasi" class="mw">
                                    <template #body="slotProps">
                                        {{ slotProps.data.norec_sbmc ? slotProps.data.noverifikasi2 :
                                            slotProps.data.noverifikasi1 }}
                                    </template>
                                </Column>
                                <Column field="ket" header="Ket" class="mw" />
                                <Column field="nobukti" header="No Bukti" class="mw" />
                                <Column field="tanggal" header="Tanggal" class="mw" />
                                <Column field="jam" header="Jam" class="mw" />
                                <Column field="jam_nobukti" header="Jam No Bukti" class="mw" />
                                <Column field="tglreg" header="Tanggal Registrasi" class="mw" />
                                <Column field="noreg" header="No Registrasi" class="mw" />
                                <Column field="nrm" header="Nomor RM" class="mw" />
                                <Column field="namapasien" header="Nama Pasien" class="mw" />
                                <Column field="jenispasien" header="Jenis Pasien" class="mw" />
                                <Column field="nokartu" header="Nomor Kartu" class="mw" />
                                <Column field="nosep" header="No SEP" class="mw" />
                                <Column field="nama_customer" header="Nama Costumer" class="mw" />
                                <Column field="alamat" header="Alamat" class="mw" />
                                <Column field="total_billing" header="Total Billing" style="text-align:right"
                                    class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.total_billing, '') }}
                                    </template>
                                </Column>
                                <Column field="nilaibayar" header="Nilai Bayar" style="text-align:right" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.nilaibayar, '') }}
                                    </template>
                                </Column>
                                <Column field="nama_asli" header="Nama Asli" class="mw" />
                                <Column field="tipeperawatan" header="Tipe Perawatan" class="mw" />
                                <Column field="pasienlost" header="Pasien Lost" class="mw" />
                                <Column field="description" header="Description" class="mw" />
                            </DataTable>
                        </div>
                        <div v-else-if="activeTab == 1">
                            <DataTable :rows="10" :value="dataSource" :rowsPerPageOptions="[5, 10, 15, 50, 100, 1000]"
                                class="p-datatable-sm" v-model:selection="selectData" showGridlines
                                tableStyle="min-width: 30rem" paginator :rowClass="rowClass" dataKey="norec_pp">
                                <template #header>
                                    <div class="columns is-multiline column">
                                        <div class="column is-4 pt-0 pb-0 is-flex is-align-items-center">
                                            <VButtons style="justify-content: space-between;">
                                                <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                    icon="fas fa-file-excel">
                                                    Export To Excel
                                                </VButton>
                                            </VButtons>
                                        </div>
                                        <div class="column is-4 is-justify-content-center is-flex">
                                            <span style="font-weight: bold;">LAYANAN</span>
                                        </div>
                                        <div class="column is-4 is-justify-content-right is-flex">
                                            <span style="font-weight: bold;">Total : {{ dataSource.length ?
                                                dataSource.length : 0 }}</span>
                                        </div>
                                    </div>
                                </template>
                                <Column selectionMode="multiple"></Column>
                                <Column field="" header="#" class="mw">
                                    <template #body="slotProps">
                                        <div class="column is-flex"
                                            style="justify-content: center;align-items: center;">
                                            <div style="text-align: center;">
                                                <VButton v-if="!slotProps.data.status || slotProps.data.status == 2"
                                                    class="my-1" icon="feather:check-circle" color="primary"
                                                    @click="updateStatus(slotProps.data, 'Verifikasi', '')">
                                                    Verifikasi
                                                </VButton>
                                                <VButton v-else-if="slotProps.data.status == 1" class="my-1"
                                                    icon="feather:x-circle" color="danger"
                                                    @click="updateStatus(slotProps.data, 'Batal Verifikasi', '')">
                                                    Batal Verifikasi
                                                </VButton>
                                            </div>
                                        </div>
                                    </template>
                                </Column>
                                <Column field="alasan_pembatalan" header="Alasan Pembatalan" class="mw" />
                                <Column field="noverifikasi" header="Nomor Verifikasi" class="mw" />
                                <Column field="noreg" header="No Registrasi" class="mw" />
                                <Column field="nobukti" header="No Bukti" class="mw" />
                                <Column field="nrm" header="Nomor RM" class="mw" />
                                <Column field="nosep" header="No SEP" class="mw" />
                                <Column field="iddokter" header="ID Dokter" class="mw" />
                                <Column field="dokterdpjp" header="Dokter DPJP" class="mw" />
                                <Column field="idpelaksana" header="ID Pelaksana" class="mw" />
                                <Column field="namapelaksana" header="Nama Pelaksana" class="mw" />
                                <Column field="namapasien" header="Nama Pasien" class="mw" />
                                <Column field="jasaname" header="Nama Jasa" class="mw" />
                                <Column field="sectionname" header="Section" class="mw" />
                                <Column field="jeniskerjasama" header="Jenis Kerja Sama" class="mw" />
                                <Column field="tgltindakan" header="Tanggal Tindakan" class="mw" />
                                <Column field="tglpulang" header="Tglpulang" class="mw" />
                                <Column field="jumlah" header="Jumlah" class="mw" style="text-align: center;" />
                                <Column field="tarif" header="Tarif" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.tarif, '') }}
                                    </template>
                                </Column>
                                <Column field="jasapelayanan" header="Jasa Pelayanan" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.jasapelayanan, '') }}
                                    </template>
                                </Column>
                                <Column field="jasasarana" header="Jasa Sarana" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.jasasarana, '') }}
                                    </template>
                                </Column>
                                <Column field="selisih" header="Selisih" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.selisih, '') }}
                                    </template>
                                </Column>
                                <Column field="luar" header="Luar" class="mw" />
                                <Column field="keterangan" header="Keterangan" class="mw" />
                                <Column field="total" header="Total" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.total, '') }}
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                        <div v-else-if="activeTab == 2">
                            <DataTable :rows="10" :value="dataSource" :rowsPerPageOptions="[5, 10, 15, 50, 100, 1000]"
                                class="p-datatable-sm" v-model:selection="selectData" showGridlines
                                tableStyle="min-width: 30rem" paginator :rowClass="rowClass" dataKey="norec_pp">
                                <template #header>
                                    <div class="columns is-multiline column">
                                        <div class="column is-4 pt-0 pb-0 is-flex is-align-items-center">
                                            <VButtons style="justify-content: space-between;">
                                                <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                    icon="fas fa-file-excel">
                                                    Export To Excel
                                                </VButton>
                                            </VButtons>
                                        </div>
                                        <div class="column is-4 is-justify-content-center is-flex">
                                            <span style="font-weight: bold;">IBSA</span>
                                        </div>
                                        <div class="column is-4 is-justify-content-right is-flex">
                                            <span style="font-weight: bold;">Total : {{ dataSource.length ?
                                                dataSource.length : 0 }}</span>
                                        </div>
                                    </div>
                                </template>
                                <Column selectionMode="multiple"></Column>
                                <Column field="" header="#" class="mw">
                                    <template #body="slotProps">
                                        <div class="column is-flex"
                                            style="justify-content: center;align-items: center;">
                                            <div style="text-align: center;">
                                                <VButton v-if="!slotProps.data.status || slotProps.data.status == 2"
                                                    class="my-1" icon="feather:check-circle" color="primary"
                                                    @click="updateStatus(slotProps.data, 'Verifikasi', '')">
                                                    Verifikasi
                                                </VButton>
                                                <VButton v-else-if="slotProps.data.status == 1" class="my-1"
                                                    icon="feather:x-circle" color="danger"
                                                    @click="updateStatus(slotProps.data, 'Batal Verifikasi', '')">
                                                    Batal Verifikasi
                                                </VButton>
                                            </div>
                                        </div>
                                    </template>
                                </Column>
                                <Column field="alasan_pembatalan" header="Alasan Pembatalan" class="mw" />
                                <Column field="noverifikasi" header="Nomor Verifikasi" class="mw" />
                                <Column field="noreg" header="No Reg" class="mw" />
                                <Column field="nobukti" header="No Bukti" class="mw" />
                                <Column field="nrm" header="NRM" class="mw" />
                                <Column field="nosep" header="No SEP" class="mw" />
                                <!-- <Column field="iddokter" header="ID Dokter" class="mw" /> -->
                                <!-- <Column field="dokterdpjp" header="Dokter DPJP" class="mw" /> -->
                                <!-- <Column field="idpelaksana" header="ID Pelaksana" class="mw" /> -->
                                <!-- <Column field="namapelaksana" header="Nama Pelaksana" class="mw" /> -->
                                <Column field="dokteroperator1" header="Dokter Operator 1" class="mw" />
                                <Column field="dokteroperator3" header="Dokter Operator 3" class="mw" />
                                <Column field="dokterasisten" header="Dokter Asisten" class="mw" />
                                <Column field="dokteranastesi" header="Dokter Anastesi" class="mw" />
                                <Column field="dokteranak" header="Dokter Anak" class="mw" />
                                <Column field="asa" header="ASA" class="mw" />
                                <Column field="namapasien" header="Nama Pasien" class="mw" />
                                <Column field="jasaname" header="Jasa Name" class="mw" />
                                <Column field="sectionname" header="Section" class="mw" />
                                <Column field="jeniskerjasama" header="Jenis Kerja Sama" class="mw" />
                                <Column field="tgltindakan" header="Tgl Tindakan" class="mw" />
                                <Column field="tglpulang" header="Tgl Pulang" class="mw" />
                                <Column field="jumlah" header="Jumlah" class="mw" style="text-align: center;" />
                                <Column field="hargasatuan" header="Harga Satuan" class="mw" />
                                <Column field="jasapelayanan" header="Jasa Pelayanan" class="mw" />
                                <Column field="jasasarana" header="Jasa Sarana" class="mw" />
                                <Column field="selisih" header="Selisih" class="mw" />
                                <Column field="luar" header="Luar" class="mw" />
                                <Column field="total" header="Total" class="mw" />
                                <Column field="keterangan" header="Keterangan" class="mw" />
                                <Column field="norec" header="NOREC" class="mw" />
                                <Column field="" header="-" class="mw" />
                            </DataTable>
                        </div>
                        <div v-else-if="activeTab == 3">
                            <DataTable :rows="10" :value="dataSource" :rowsPerPageOptions="[5, 10, 15, 50, 100, 1000]"
                                class="p-datatable-sm" v-model:selection="selectData" showGridlines
                                tableStyle="min-width: 30rem" paginator :rowClass="rowClass" dataKey="norec_pp">
                                <template #header>
                                    <div class="columns is-multiline column">
                                        <div class="column is-4 pt-0 pb-0 is-flex is-align-items-center">
                                            <VButtons style="justify-content: space-between;">
                                                <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                    icon="fas fa-file-excel">
                                                    Export To Excel
                                                </VButton>
                                            </VButtons>
                                        </div>
                                        <div class="column is-4 is-justify-content-center is-flex">
                                            <span style="font-weight: bold;">OBAT</span>
                                        </div>
                                        <div class="column is-4 is-justify-content-right is-flex">
                                            <span style="font-weight: bold;">Total : {{ dataSource.length ?
                                                dataSource.length : 0 }}</span>
                                        </div>
                                    </div>
                                </template>
                                <Column selectionMode="multiple"></Column>
                                <Column field="" header="#" class="mw">
                                    <template #body="slotProps">
                                        <div class="column is-flex"
                                            style="justify-content: center;align-items: center;">
                                            <div style="text-align: center;">
                                                <VButton v-if="!slotProps.data.status || slotProps.data.status == 2"
                                                    class="my-1" icon="feather:check-circle" color="primary"
                                                    @click="updateStatus(slotProps.data, 'Verifikasi', '')">
                                                    Verifikasi
                                                </VButton>
                                                <VButton v-else-if="slotProps.data.status == 1" class="my-1"
                                                    icon="feather:x-circle" color="danger"
                                                    @click="updateStatus(slotProps.data, 'Batal Verifikasi', '')">
                                                    Batal Verifikasi
                                                </VButton>
                                            </div>
                                        </div>
                                    </template>
                                </Column>
                                <Column field="alasan_pembatalan" header="Alasan Pembatalan" class="mw" />
                                <Column field="noverifikasi" header="Nomor Verifikasi" class="mw" />
                                <Column field="nobukti" header="No Bukti" class="mw" />
                                <Column field="noresep" header="No Resep" class="mw" />
                                <Column field="barang_id" header="Barang ID" class="mw" />
                                <Column field="ktp" header="KTP" class="mw" />
                                <Column field="jeniskerjasama" header="Jenis Kerja Sama" class="mw" />
                                <Column field="tanggal" header="Tanggal" class="mw" />
                                <Column field="kode_barang" header="Kode Barang" class="mw" />
                                <Column field="nama_barang" header="Nama Barang" class="mw" />
                                <Column field="qty" header="Qty" style="text-align:center" class="mw" />
                                <Column field="hargaorig" header="Harga Original" style="text-align:right" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.hargaorig, '') }}
                                    </template>
                                </Column>
                                <Column field="hargajual" header="Harga Jual" style="text-align:right" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.hargajual, '') }}
                                    </template>
                                </Column>
                                <Column field="noreg" header="No Reg" class="mw" />
                                <Column field="nosep" header="No SEP" class="mw" />
                                <Column field="jeniskemasan" header="Jenis Kemasan" class="mw"
                                    style="text-align: center;" />
                                <Column field="sectionname" header="Section" class="mw" />
                                <Column field="namadokter" header="Nama Dokter" class="mw" />
                                <Column field="iddokter" header="ID Dokter" class="mw" />
                                <Column field="tglpulang" header="Tgl Pulang" class="mw" />
                                <Column field="jasa" header="Jasa" style="text-align:right" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.jasa, '') }}
                                    </template>
                                </Column>
                                <Column field="discount" header="Discount" style="text-align:right" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.discount, '') }}
                                    </template>
                                </Column>
                                <Column field="total" header="Total" style="text-align:right" class="mw">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(slotProps.data.total, '') }}
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </VCard>
        </div>
    </section>

    <VModal :open="modalAlasanPembatalan" title="Alasan Pembatalan" :noclose="true" size="medium" actions="right"
        @close="modalAlasanPembatalan = false; dataDetail = {}; item.alasanPembatalan = null">
        <template #content>
            <div class="column">
                <VField>
                    <VTextarea rows="2" v-model="item.alasanPembatalan" placeholder="Alasan pembatalan..."></VTextarea>
                </VField>
            </div>
        </template>
        <template #action>
            <VButton class="my-1" icon="feather:x-circle" color="danger"
                @click="updateStatus(dataDetail, 'Batal Verifikasi', true)">
                Batal Verifikasi
            </VButton>
        </template>
    </VModal>

</template>

<script setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import * as H from '/@src/utils/appHelper'
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

useHead({ title: 'Jasa Layanan Pagu - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    filterTgl: reactive({
        // start: new Date(new Date().setDate(new Date().getDate() - 150)), // Buat testing hehe
        start: new Date(),
        end: new Date(),
    }),
})

const dataDetail: any = ref({})
const activeTab = ref(0);
const metaKey = ref(false);
const dataSource: any = ref([])
const dataSourceNilaiPagu: any = ref([])
const selectData = ref([]);

// List Filter
let d_Dokter: any = ref([]);
let d_Produk: any = ref([]);
let d_Ruangan: any = ref([]);
let d_KelompokPasien: any = ref([]);
let d_Departemen: any = ref([]);
let d_CaraBayar: any = ref([]);
let d_Kebangsaan: any = ref([]);
let d_StatusVerifikasi: any = ref([{ label: 'Verifikasi', value: 1 }, { label: 'Batal Verifikasi', value: 2 }, { label: 'Belum Ditindaklanjuti', value: 3 }]);

let loadSearch: any = ref(false);
let loadSave: any = ref(false);
let loadData: any = ref(true);
let IsBayar: any = ref(false);
let modalAlasanPembatalan: any = ref(false);

const updateStatus = async (data: any, status: any, confirm: any) => {
    let d = item.value;
    let jenis = '';

    if (!status) {
        H.alert('error', 'Terjadi kesalahan');
        return;
    }

    switch (status) {
        case 'Verifikasi':
            status = 1;
            break;
        case 'Batal Verifikasi':
            status = 2;
            break;
        default:
            break;
    }

    if (status == 2 && !confirm) {
        dataDetail.value = data;
        modalAlasanPembatalan.value = true;
        return;
    }

    switch (activeTab.value) {
        case 0:
            jenis = 'NOREG'
            break;
        case 1:
            jenis = 'LAYANAN'
            break;
        case 2:
            jenis = 'IBSA'
            break;
        case 3:
            jenis = 'OBAT'
            break;
        default:
            break;
    }

    // Foreign Key JP
    let id_jp_fk = '';
    let noverifikasi = '';
    if (jenis == 'NOREG') {
        id_jp_fk = data.id_jp1 || data.id_jp2 ? data.id_jp1 ?? data.id_jp2 : null;
        noverifikasi = data.noverifikasi1 || data.noverifikasi2 ? data.noverifikasi1 ?? data.noverifikasi2 : null;
    } else {
        id_jp_fk = data.id_jp ? data.id_jp : null;
        noverifikasi = data.noverifikasi ? data.noverifikasi : null;
    }

    let json = {
        id_jp: id_jp_fk,
        norec_sp: data.norec_sp ? data.norec_sp : null,
        norec_pp: data.norec_pp ? data.norec_pp : null,
        norec_sbmc: data.norec_sbmc ? data.norec_sbmc : null,
        noverifikasi: noverifikasi,
        jenis: jenis,
        status: status,
        catatan: d.catatan ? d.catatan : null,
        alasan_pembatalan: d.alasanPembatalan ? d.alasanPembatalan : null
    }

    loadData.value = true
    await useApi().post('jasapelayanan/update-status-jaspel', json).then((response: any) => {
        fetchPagu();
    }).catch((error) => {
        console.log(error);
        H.alert('error', 'Terjadi kesalahan!');
    }).finally(() => {
        loadData.value = false;
        modalAlasanPembatalan.value = false;
        d.alasanPembatalan = null;
        d.catatan = null;
        selectData.value = [];
    });
}

const updateStatusAll = async (data: any, status: any, confirm: any) => {
    /*
        Sementara hanya all verifikasi saja, belum batal verifikasi. 
        Karena jika batal verifikasi harus ngecek tablenya dulu.
        Kalo datanya banyak, pasti ngelag.
    */

    let d = item.value;
    let jenis = '';
    let json = { data: [] };

    if (!status || selectData.length == 0) {
        H.alert('error', 'Terjadi kesalahan');
        return;
    }

    switch (status) {
        case 'Verifikasi':
            status = 1;
            break;
        case 'Batal Verifikasi':
            status = 2;
            break;
        default:
            break;
    }

    if (status == 2 && !confirm) {
        dataDetail.value = data;
        modalAlasanPembatalan.value = true;
        return;
    }

    switch (activeTab.value) {
        case 0:
            jenis = 'NOREG'
            break;
        case 1:
            jenis = 'LAYANAN'
            break;
        case 2:
            jenis = 'IBSA'
            break;
        case 3:
            jenis = 'OBAT'
            break;
        default:
            break;
    }

    data.forEach(function (dt, index) {
        let arrayz = {
            // id_jp: dt.id_jp ? dt.id_jp : null,
            id_jp: null,
            norec_sp: dt.norec_sp ? dt.norec_sp : null,
            norec_pp: dt.norec_pp ? dt.norec_pp : null,
            norec_sbmc: dt.norec_sbmc ? dt.norec_sbmc : null,
            jenis: jenis,
            status: status,
            catatan: d.catatan ? d.catatan : null,
            alasan_pembatalan: d.alasanPembatalan ? d.alasanPembatalan : null
        }
        json.data.push(arrayz);
    });

    loadData.value = true
    await useApi().post('jasapelayanan/update-status-jaspel-all', json).then((response: any) => {
        fetchPagu();
    }).catch((error) => {
        console.log(error);
        H.alert('error', 'Terjadi kesalahan!');
    }).finally(() => {
        loadData.value = false;
        modalAlasanPembatalan.value = false;
        d.alasanPembatalan = null;
        d.catatan = null;
        selectData.value = [];
    });
}

async function fetchPagu() {
    loadData.value = true

    // Param
    let api;
    switch (activeTab.value) {
        case 0:
            api = 'jasapelayanan/get-pagu-noreg';
            break;
        case 1:
            api = 'jasapelayanan/get-pagu-layanan';
            break;
        case 2:
            api = 'jasapelayanan/get-pagu-ibsa';
            break;
        case 3:
            api = 'jasapelayanan/get-pagu-obat';
            break;
        default:
            break;
    }

    // Filter
    let tglAwal = item.value.filterTgl.start ? `${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}` : null;
    let tglAkhir = item.value.filterTgl.end ? `${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}` : null;
    let tglAwalTindakan = item.value.filterTglTindakan && item.value.filterTglTindakan.start ? `${H.formatDate(item.value.filterTglTindakan.start, 'YYYY-MM-DD')}` : null;
    let tglAkhirTindakan = item.value.filterTglTindakan && item.value.filterTglTindakan.end ? `${H.formatDate(item.value.filterTglTindakan.end, 'YYYY-MM-DD')}` : null;
    let nocm = item.value.nocm ? `${item.value.nocm.trim()}` : null;
    let noregis = item.value.noregis ? `${item.value.noregis.trim()}` : null;
    let nosep = item.value.nosep ? `${item.value.nosep.trim()}` : null;
    let ruangan = item.value.ruangan && item.value.ruangan.id ? `${item.value.ruangan.id}` : null;
    let departemen = item.value.departemen && item.value.departemen.id ? `${item.value.departemen.id}` : null;
    let carabayar = item.value.carabayar && item.value.carabayar.id ? `${item.value.carabayar.id}` : null;
    let kelompokpasien = item.value.kelompokpasien && item.value.kelompokpasien.id ? `${item.value.kelompokpasien.id}` : null;
    let kebangsaan = item.value.kebangsaan && item.value.kebangsaan.id ? `${item.value.kebangsaan.id}` : null;
    let dpjp = item.value.dpjp && item.value.dpjp.value ? `${item.value.dpjp.value}` : null;
    let produk = item.value.produk && item.value.produk.value ? `${item.value.produk.value}` : null;
    let statusVerif = item.value.statusVerif ? `${item.value.statusVerif}` : null;

    if (noregis || nosep) {
        tglAwal = null;
        tglAkhir = null;
    }

    let json = {
        tglAwal: tglAwal,
        tglAkhir: tglAkhir,
        tglAwalTindakan: tglAwalTindakan,
        tglAkhirTindakan: tglAkhirTindakan,
        nocm: nocm,
        noregis: noregis,
        nosep: nosep,
        ruangan: ruangan,
        departemen: departemen,
        carabayar: carabayar,
        kelompokpasien: kelompokpasien,
        kebangsaan: kebangsaan,
        dpjp: dpjp,
        produk: produk,
        statusverif: statusVerif
    };

    await useApi().post(api, json).then((response: any) => {
        if (response.length) {
            dataSource.value = response;
        } else {
            dataSource.value = [];
            H.alert('warning', 'Tidak ada data!');
        }
    }).catch((e: any) => {
        console.log(e);
        dataSource.value = [];
        H.alert('error', 'Terjadi kesalahan saat mengambil data');
    }).finally(() => {
        loadData.value = false;
        selectData.value = [];
    });
}

const exportExcel = (tab: any) => {
    const workbook = XLSX.utils.book_new();
    let noreg = [
        ['NOREG'],
        [],
        [
            'KET',
            'NO BUKTI',
            'TANGGAL',
            'JAM',
            'JAM NO BUKTI',
            'TANGGAL REGISTRASI',
            'NO REGISTRASI',
            'NOMOR RM',
            'NAMA PASIEN',
            'JENIS PASIEN',
            'NOMOR KARTU',
            'NO SEP',
            'NAMA COSTUMER',
            'ALAMAT',
            'TOTAL BILLING',
            'NILAI BAYAR',
            'NAMA ASLI',
            'TIPE PERAWATAN',
            'PASIEN LOST',
            'DESCRIPTION'
        ],
        ...dataSource.value.map((e: any) => [
            e.ket,
            e.nobukti,
            e.tanggal,
            e.jam,
            e.jam_nobukti,
            e.tglreg,
            e.noreg,
            e.nrm,
            e.namapasien,
            e.jenispasien,
            e.nokartu,
            e.nosep,
            e.nama_customer,
            e.alamat,
            e.total_billing,
            e.nilaibayar,
            e.nama_asli,
            e.tipeperawatan,
            e.pasienlost,
            e.description
        ])
    ];
    let layanan = [
        ['LAYANAN'],
        [],
        [
            'NO REGISTRASI',
            'NO BUKTI',
            'NOMOR RM',
            'NO SEP',
            'ID DOKTER',
            'DOKTER DPJP',
            'ID PELAKSANA',
            'NAMA PELAKSANA',
            'NAMA PASIEN',
            'NAMA JASA',
            'SECTION',
            'JENIS KERJA SAMA',
            'TANGGAL TINDAKAN',
            'TANGGAL PULANG',
            'JUMLAH',
            'TARIF',
            'JASA PELAYANAN',
            'JASA SARANA',
            'SELISIH',
            'LUAR',
            'KETERANGAN',
            'TOTAL'
        ],
        ...dataSource.value.map((e: any) => [
            e.noreg,
            e.nobukti,
            e.nrm,
            e.nosep,
            e.iddokter,
            e.dokterdpjp,
            e.idpelaksana,
            e.namapelaksana,
            e.namapasien,
            e.jasaname,
            e.sectionname,
            e.jeniskerjasama,
            e.tgltindakan,
            e.tglpulang,
            e.jumlah,
            e.tarif,
            e.jasapelayanan,
            e.jasasarana,
            e.selisih,
            e.luar,
            e.keterangan,
            e.total
        ])
    ];
    let ibsa = [
        ['IBSA'],
        [],
        [
            'NO REG', 'NO BUKTI', 'NRM', 'NO SEP',
            // 'ID DOKTER',
            // 'DOKTER DPJP',
            // 'ID PELAKSANA',
            // 'NAMA PELAKSANA',
            'DOKTER OPERATOR 1', 'DOKTER OPERATOR 3', 'DOKTER ASISTEN', 'DOKTER ANASTESI', 'DOKTER ANAK',
            'ASA', 'NAMA PASIEN', 'JASA NAME', 'SECTION', 'JENIS KERJA SAMA',
            'TGL TINDAKAN', 'TGL PULANG', 'JUMLAH', 'HARGA SATUAN',
            'JASA PELAYANAN', 'JASA SARANA', 'SELISIH', 'LUAR', 'TOTAL', 'KETERANGAN', 'NOREC', '-'
        ],
        ...dataSource.value.map((e) => [
            e.noreg,
            e.nobukti,
            e.nrm,
            e.nosep,
            // e.iddokter,
            // e.dokterdpjp,
            // e.idpelaksana,
            // e.namapelaksana,
            e.dokteroperator1,
            e.dokteroperator3,
            e.dokterasisten,
            e.dokteranastesi,
            e.dokteranak,
            e.asa,
            e.namapasien,
            e.jasaname,
            e.sectionname,
            e.jeniskerjasama,
            e.tgltindakan,
            e.tglpulang,
            e.jumlah,
            e.hargasatuan,
            e.jasapelayanan,
            e.jasasarana,
            e.selisih,
            e.luar,
            e.total,
            e.keterangan,
            e.norec,
            ' '
        ])
    ];

    let obat = [
        ['OBAT'],
        [],
        [
            'NO BUKTI', 'NO RESEP', 'BARANG ID', 'KTP', 'JENIS KERJA SAMA', 'TANGGAL', 'KODE BARANG', 'NAMA BARANG',
            'QTY', 'HARGA ORIGINAL', 'HARGA JUAL', 'NO REG', 'NO SEP', 'JENIS KEMASAN', 'SECTION',
            'NAMA DOKTER', 'ID DOKTER', 'TGL PULANG', 'JASA', 'DISCOUNT', 'TOTAL'
        ],
        ...dataSource.value.map((e: any) => [
            e.nobukti,
            e.noresep,
            e.barang_id,
            e.ktp,
            e.jeniskerjasama,
            e.tanggal,
            e.kode_barang,
            e.nama_barang,
            e.qty,
            e.hargaorig,
            e.hargajual,
            e.noreg,
            e.nosep,
            e.jeniskemasan,
            e.sectionname,
            e.namadokter,
            e.iddokter,
            e.tglpulang,
            e.jasa,
            e.discount,
            e.total
        ])
    ];

    let worksheet;
    let title;
    if (tab == 0) {
        worksheet = XLSX.utils.aoa_to_sheet(noreg);
        title = 'NOREG.xlsx'
    } else if (tab == 1) {
        worksheet = XLSX.utils.aoa_to_sheet(layanan);
        title = 'LAYANAN.xlsx'
    } else if (tab == 2) {
        worksheet = XLSX.utils.aoa_to_sheet(ibsa);
        title = 'IBSA.xlsx'
    } else if (tab == 3) {
        worksheet = XLSX.utils.aoa_to_sheet(obat);
        title = 'OBAT.xlsx'
    }

    // Mendefinisikan style untuk header(centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            color: { rgb: 'FFFFFF' }
        },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    // Mendefinisikan range header
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    // atur lebar column
    let columnWidths
    if (tab == 0) {
        columnWidths = [18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18];
    } else if (tab == 1) {
        columnWidths = [18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18];
    } else if (tab == 2) {
        columnWidths = [18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18];
    } else if (tab == 3) {
        columnWidths = [18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18];
    }

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            bold: true,
            sz: 18
        }
    };


    // Menggabungkan dua baris pertama
    let mergeTitle
    if (tab == 0) {
        mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 19 } };
    } else if (tab == 1) {
        mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 21 } };
    } else if (tab == 2) {
        mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 25 } };
    } else if (tab == 3) {
        mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 19 } };
    }

    worksheet['!merges'] = [mergeTitle];
    XLSX.utils.book_append_sheet(workbook, worksheet, 'PERGU', true);
    XLSXStyle.writeFile(workbook, title);
}

const inputFormat = (val, jenis) => {
    if (!val) return;

    let length = 0;
    let chunks = [];
    let raw = val.replace(/[, \t\r\n]/g, '');

    switch (jenis) {
        case 'noregis':
            length = 10;
            break;
        case 'nosep':
            length = 19;
            break;
        default:
            return;
    }

    for (let i = 0; i < raw.length; i += length) {
        chunks.push(raw.substr(i, length));
    }

    item.value[jenis] = chunks.join(',');
};


const onlyAllowDigits = (event) => {
    const char = String.fromCharCode(event.which);
    if (!/^\d$/.test(char)) {
        H.alert('warning', 'Hanya dapat menginput angka!');
        event.preventDefault();
    }
};

const onPasteCheck = (event, jenis) => {
    let pasted = event.clipboardData.getData('text').trim();
    let isValidFormat = '';

    if (jenis == 'noregis') {
        isValidFormat = /^(\d{10})(,\d{10})*$/.test(pasted);
        if (!isValidFormat) {
            H.alert('warning', 'Format tidak valid! Gunakan angka atau angka dipisahkan dengan koma setiap 10 digit.');
            event.preventDefault();
        }
    } else if (jenis == 'nosep') {
        isValidFormat = /^([^\s,]{19})(,[^\s,]{19})*$/.test(pasted);
        if (!isValidFormat) {
            H.alert('warning', 'Format tidak valid! Setiap kode harus 19 karakter dan dipisahkan dengan koma.');
            event.preventDefault();
        }
    }
};

const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => { d_Dokter.value = response })
}

const fetchProduk = async (filter: any) => {
    await useApi().get(`emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&query=${filter.query}&limit=10`).then((response) => { d_Produk.value = response })
}

const fetchDropdown = async () => {
    await useApi().get('jasapelayanan/get-combo-idx').then((res) => {
        d_Ruangan.value = res['ruangan'];
        d_Departemen.value = res['departemen'];
        d_CaraBayar.value = res['carabayar'];
        d_KelompokPasien.value = res['kelompokpasien'];
        d_Kebangsaan.value = res['kebangsaan'];
    }).catch((e: any) => {
        console.log(e);
        H.alert('error', 'Terjadi kesalahan saat mengambil data');
    })
}

const klikTab = async (e: any) => {
    activeTab.value = e.index
    clear()
    await fetchPagu()
}

const clear = () => {
    item.value = {
        filterTgl: reactive({
            start: item.value.filterTgl.start,
            end: item.value.filterTgl.end
        }),
    };
}

const rowClass = (data) => {
    return {
        'row-green': data.status == 1,
        'row-red': data.status == 2,
    };
}

const rowClassNoreg = (data) => {
    return {
        'row-green': data.status1 == 1 || data.status2 == 1,
        'row-red': data.status1 == 2 || data.status2 == 2,
    };
}

fetchDropdown()
fetchPagu()

// FETCH DATA MENGGUNAKAN METHOD GET
// async function fetchPagu() {
//     loadData.value = true

//     // Param
//     let api;
//     switch (activeTab.value) {
//         case 0:
//             api = 'jasapelayanan/get-pagu-noreg?';
//             break;
//         case 1:
//             api = 'jasapelayanan/get-pagu-layanan?';
//             break;
//         case 2:
//             api = 'jasapelayanan/get-pagu-ibsa?';
//             break;
//         case 3:
//             api = 'jasapelayanan/get-pagu-obat?';
//             break;
//         default:
//             break;
//     }

//     // Filter
//     let tglAwal = item.value.filterTgl.start ? `tglawal=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}` : '';
//     let tglAkhir = item.value.filterTgl.end ? `&tglakhir=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}` : '';
//     let tglAwalTindakan = item.value.filterTglTindakan && item.value.filterTglTindakan.start ? `&tglawaltindakan=${H.formatDate(item.value.filterTglTindakan.start, 'YYYY-MM-DD')}` : '';
//     let tglAkhirTindakan = item.value.filterTglTindakan && item.value.filterTglTindakan.end ? `&tglakhirtindakan=${H.formatDate(item.value.filterTglTindakan.end, 'YYYY-MM-DD')}` : '';
//     let nocm = item.value.nocm ? `&nocm=${item.value.nocm.trim()}` : '';
//     let noregis = item.value.noregis ? `&noregis=${item.value.noregis.trim()}` : '';
//     let nosep = item.value.nosep ? `&nosep=${item.value.nosep.trim()}` : '';
//     let ruangan = item.value.ruangan && item.value.ruangan.id ? `&ruangan=${item.value.ruangan.id}` : '';
//     let departemen = item.value.departemen && item.value.departemen.id ? `&departemen=${item.value.departemen.id}` : '';
//     let carabayar = item.value.carabayar && item.value.carabayar.id ? `&carabayar=${item.value.carabayar.id}` : '';
//     let kelompokpasien = item.value.kelompokpasien && item.value.kelompokpasien.id ? `&kelompokpasien=${item.value.kelompokpasien.id}` : '';
//     let kebangsaan = item.value.kebangsaan && item.value.kebangsaan.id ? `&kebangsaan=${item.value.kebangsaan.id}` : '';
//     let dpjp = item.value.dpjp && item.value.dpjp.value ? `&dpjp=${item.value.dpjp.value}` : '';
//     let produk = item.value.produk && item.value.produk.value ? `&produk=${item.value.produk.value}` : '';
//     let statusVerif = item.value.statusVerif ? `&statusverif=${item.value.statusVerif}` : '';

//     if (noregis || nosep) {
//         tglAwal = '';
//         tglAkhir = '';
//     }

//     let final = `${api}${tglAwal}${tglAkhir}${tglAwalTindakan}${tglAkhirTindakan}${nocm}${noregis}${nosep}${ruangan}${departemen}${carabayar}${kelompokpasien}${kebangsaan}${dpjp}${produk}${statusVerif}`;

//     await useApi().get(final).then((response: any) => {
//         if (response.length) {
//             dataSource.value = response;
//         } else {
//             dataSource.value = [];
//             H.alert('warning', 'Tidak ada data!');
//         }
//     }).catch((e: any) => {
//         console.log(e);
//         dataSource.value = [];
//         H.alert('error', 'Terjadi kesalahan saat mengambil data');
//     }).finally(() => {
//         loadData.value = false;
//     });
// }
</script>
<style lang="scss">
h1 {
    font-weight: bold;
}

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}

.mw {
    min-width: 150px !important;
}

.row-green {
    background-color: #d4edda !important;
}

.row-red {
    background-color: #ffd2d2 !important;
}
</style>
