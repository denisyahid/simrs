<template>
    <section>
        <div class="column is-12">
            <VCard style="padding-bottom: 0px">
                <div class="column c-title pt-2 mb-0">
                    <label class="title-page">Laporan Remunerasi</label>
                </div>
                <div class="column p-0">
                    <TabView class="tabview-custom " @tab-click="klikTab($event)">
                        <TabPanel>
                            <template #header>
                                <span>DETAIL LAPORAN REMUNERASI</span>
                            </template>
                            <div v-if="activeTab == 0">
                                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                                <DataTable v-else :rows="5" :value="dataSourceDetail" :rowsPerPageOptions="[5, 10, 15]"
                                    :loading="loadSearch" class="p-datatable-sm" breakpoint="960px" selectionMode="single"
                                    sortMode="multiple" v-model:expanded-rows="expandedRows" showGridlines
                                    tableStyle="min-width: 30rem"
                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                    <template #header>
                                        <div class="columns is-multiline">
                                            <div class="column is-2 pb-0 mb-0" style="padding-top: 2rem;">
                                                <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                    icon="fas fa-file-excel">
                                                    Export To Excel
                                                </VButton>
                                            </div>

                                            <div class="column is-10 pb-0 mb-3">
                                                <div class="columns is-multiline" style="justify-content: right;">
                                                    <div class="column is-4">
                                                        <VField label="Periode" />
                                                        <VDatePicker class="mt-2" v-model="item.filterTglDetail" is-range color="pink"
                                                            trim-weeks>
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField addons>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput :value="inputValue.start"
                                                                            v-on="inputEvents.start" />
                                                                    </VControl>
                                                                    <VControl>
                                                                        <VButton static><i class="fas fa-arrow-right"
                                                                                aria-hidden="true"></i></VButton>
                                                                    </VControl>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput :value="inputValue.end"
                                                                            v-on="inputEvents.end" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VField class="is-rounded-select is-autocomplete-select"
                                                            label="Departemen">
                                                            <VControl icon="feather:search" class="prime-auto">
                                                                <Dropdown v-model="item.departemenDetail"
                                                                    :options="d_Departemen" :optionLabel="'label'"
                                                                    placeholder="Pilih Departemen" :optionValue="'value'"
                                                                    @change="choiceRuangan(item.departemenDetail)"
                                                                    style="width: 100%;" :filter="true" appendTo="body"
                                                                    showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VField class=" is-rounded-select is-autocomplete-select"
                                                            label="Ruangan">
                                                            <VControl icon="feather:search" class="prime-auto"
                                                                :loading="loadRuangan">
                                                                <Dropdown v-model="item.ruanganfkDetail"
                                                                    :options="d_Ruangan" :optionLabel="'label'"
                                                                    placeholder="Pilih Ruangan" :optionValue="'value'"
                                                                    style="width: 100%;" :filter="true" appendTo="body"
                                                                    showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-1" style="padding-top: 35px;text-align:center">
                                                        <VIconButton color="success" icon="fas fa-search"
                                                            @click="fetchDetailLaporan()" :loading="loadSearch" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <Column field="namaruangan" header="Nama Unit" />
                                    <Column field="direksi" header="Direksi" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.direksi), 2), '')
                                            }}
                                        </template>
                                    </Column>
                                    <Column field="struktural" header="Struktural" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.struktural), 2),
                                                '') }}
                                        </template>
                                    </Column>
                                    <Column field="administrasi" header="Administrasi" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.administrasi), 2),
                                                '') }}
                                        </template>
                                    </Column>
                                    <Column field="jpl" header="JPL" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.jpl), 2), '') }}
                                        </template>
                                    </Column>
                                    <Column field="jptl" header="JPTL" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.jptl), 2), '') }}
                                        </template>
                                    </Column>
                                    <Column field="gabungan" header="Gabungan" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.gabungan), 2), '')
                                            }}
                                        </template>
                                    </Column>
                                    <ColumnGroup type="footer">
                                        <Row>
                                            <Column footer="Total" />
                                            <Column :footer="item.F_Direksi"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem; text-align:right" />
                                            <Column :footer="item.F_Struktural"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem; text-align:right" />
                                            <Column :footer="item.F_Administrasi"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem; text-align:right" />
                                            <Column :footer="item.F_JPL"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem; text-align:right" />
                                            <Column :footer="item.F_JPTL"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem; text-align:right" />
                                            <Column :footer="item.F_Gabungan"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem; text-align:right" />
                                        </Row>
                                    </ColumnGroup>
                                </DataTable>
                            </div>
                        </TabPanel>
                        <TabPanel>
                            <template #header>
                                <!-- <i class="fas fa-user-check mr-2" aria-hidden="true"></i> -->
                                <span>REKAP LAPORAN REMUNERASI</span>
                            </template>
                            <div v-if="activeTab == 1">
                                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                                <DataTable :rows="5" :value="dataSourceRekap" :rowsPerPageOptions="[5, 10, 15]" v-else
                                    :loading="loadSearch" class="p-datatable-sm" breakpoint="960px" selectionMode="single"
                                    sortMode="multiple" v-model:expanded-rows="expandedRows" showGridlines
                                    tableStyle="min-width: 30rem"
                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                    <template #header>
                                        <div class="columns is-multiline">
                                            <div class="column is-2 pb-0 mb-0" style="padding-top: 2rem;">
                                                <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                    icon="fas fa-file-excel">
                                                    Export To Excel
                                                </VButton>
                                            </div>

                                            <div class="column is-10 pb-0 mb-3">
                                                <div class="columns is-multiline" style="justify-content: right;">
                                                    <div class="column is-4">
                                                        <VField label="Periode" />
                                                        <VDatePicker class="mt-2" v-model="item.filterTglRekap" is-range color="pink"
                                                            trim-weeks>
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField addons>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput :value="inputValue.start"
                                                                            v-on="inputEvents.start" />
                                                                    </VControl>
                                                                    <VControl>
                                                                        <VButton static><i class="fas fa-arrow-right"
                                                                                aria-hidden="true"></i></VButton>
                                                                    </VControl>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput :value="inputValue.end"
                                                                            v-on="inputEvents.end" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </div>
                                                    <div class="column is-4">
                                                        <VField class="is-rounded-select is-autocomplete-select"
                                                            label="Pegawai">
                                                            <VControl icon="feather:search" class="prime-auto-select">
                                                                <AutoComplete v-model="item.pegawaifkLaporan"
                                                                    :suggestions="d_Pegawai"
                                                                    @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                    placeholder="Pegawai..." />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-1" style="padding-top: 35px;text-align:center">
                                                        <VIconButton color="success" icon="fas fa-search"
                                                            @click="fetchLaporanRemun()" :loading="loadSearch" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <Column field="namalengkap" header="Nama Pegawai" />
                                    <Column field="direksi" header="Direksi" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.direksi), 2), '')
                                            }}
                                        </template>
                                    </Column>
                                    <Column field="struktural" header="Struktural" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.struktural), 2),
                                                '') }}
                                        </template>
                                    </Column>
                                    <Column field="administrasi" header="Administrasi" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.administrasi), 2),
                                                '') }}
                                        </template>
                                    </Column>
                                    <Column field="jpl" header="JPL" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.jpl), 2), '') }}
                                        </template>
                                    </Column>
                                    <Column field="jptl" header="JPTL" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.jptl), 2), '') }}
                                        </template>
                                    </Column>
                                    <Column field="gabungan" header="Gabungan" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.gabungan), 2), '')
                                            }}
                                        </template>
                                    </Column>
                                    <ColumnGroup type="footer">
                                        <Row>
                                            <Column footer="Total" tyle="min-width: 80px;" />
                                            <Column :footer="item.r_Direksi"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                            <Column :footer="item.r_Struktural"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                            <Column :footer="item.r_Administrasi"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                            <Column :footer="item.r_JPL"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                            <Column :footer="item.r_JPTL"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                            <Column :footer="item.r_Gabungan"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                        </Row>
                                    </ColumnGroup>
                                </DataTable>
                            </div>
                        </TabPanel>
                        <TabPanel>
                            <template #header>
                                <span>LAPORAN DETAIL REMUNERASI DOKTER</span>
                            </template>
                            <div v-if="activeTab == 2">
                                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                                <DataTable :rows="5" :rowsPerPageOptions="[5, 10, 15]" v-else :loading="loadSearch"
                                    class="p-datatable-sm" :value="dataSourceRemunDokter" breakpoint="960px"
                                    selectionMode="single" sortMode="multiple" v-model:expanded-rows="expandedRows"
                                    showGridlines tableStyle="min-width: 30rem"
                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                    <template #header>
                                        <div class="columns is-multiline">
                                            <div class="column is-2 pb-0 mb-0" style="padding-top: 2rem;">
                                                <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                    icon="fas fa-file-excel">
                                                    Export To Excel
                                                </VButton>
                                            </div>

                                            <div class="column is-10 pb-0 mb-3">
                                                <div class="columns is-multiline" style="justify-content: right;">
                                                    <div class="column is-4">
                                                        <VField label="Periode" />
                                                        <VDatePicker class="mt-2" v-model="item.filterTglDokter" is-range color="pink"
                                                            trim-weeks>
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField addons>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput :value="inputValue.start"
                                                                            v-on="inputEvents.start" />
                                                                    </VControl>
                                                                    <VControl>
                                                                        <VButton static><i class="fas fa-arrow-right"
                                                                                aria-hidden="true"></i></VButton>
                                                                    </VControl>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput :value="inputValue.end"
                                                                            v-on="inputEvents.end" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </div>

                                                    <div class="column is-3">
                                                        <VField class="is-rounded-select is-autocomplete-select"
                                                            label="Departemen">
                                                            <VControl icon="feather:search" class="prime-auto">
                                                                <Dropdown v-model="item.departemenDok"
                                                                    :options="d_Departemen" :optionLabel="'label'"
                                                                    placeholder="Pilih Departemen" :optionValue="'value'"
                                                                    @change="choiceRuangan(item.departemenDok)"
                                                                    style="width: 100%;" :filter="true" appendTo="body"
                                                                    showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VField class=" is-rounded-select is-autocomplete-select"
                                                            label="Ruangan">
                                                            <VControl icon="feather:search" class="prime-auto"
                                                                :loading="loadRuangan">
                                                                <Dropdown v-model="item.ruanganfkDok" :options="d_Ruangan"
                                                                    :optionLabel="'label'" placeholder="Pilih Ruangan"
                                                                    :optionValue="'value'" style="width: 100%;"
                                                                    :filter="true" appendTo="body" showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-1" style="padding-top: 35px;text-align:center">
                                                        <VIconButton color="success" icon="fas fa-search"
                                                            @click="fetchLaporanRemunDok()" :loading="loadSearch" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <Column field="tglpelayanan" header="Tanggal">
                                        <template #body="slotProps">
                                            {{ H.formatDate(slotProps.data.tglpelayanan, 'YYYY-MM-DD') }}
                                        </template>
                                    </Column>
                                    <Column field="nocm" header="No Rekam Medis" />
                                    <Column field="noregistrasi" header="Noregistrasi" />
                                    <Column field="namapasien" header="Nama Pasien" />
                                    <Column field="namaruangan" header="Ruang Layanan" />
                                    <Column field="namaproduk" header="Nama Layanan" />
                                    <Column field="qty" header="Jumlah" />
                                    <Column field="isparamedis" header="P" />
                                    <Column field="iscito" header="Cito" />
                                    <Column field="hargasatuan" header="Harga Satuan" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2),
                                                '') }}
                                        </template>
                                    </Column>
                                    <Column field="total" header="Jasa Pelayanan" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
                                        </template>
                                    </Column>
                                    <ColumnGroup type="footer">
                                        <Row>
                                            <Column footer="Total" tyle="min-width: 80px;" />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column :footer="item.dr_Satuan"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                            <Column :footer="item.dr_Total"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                        </Row>
                                    </ColumnGroup>
                                </DataTable>
                            </div>
                        </TabPanel>
                        <TabPanel>
                            <template #header>
                                <!-- <i class="fas fa-user-check mr-2" aria-hidden="true"></i> -->
                                <span>DETAIL LAPORAN REMUNERASI PARAMEDIS</span>
                            </template>
                            <div v-if="activeTab == 3">
                                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                                <DataTable :rows="5" :rowsPerPageOptions="[5, 10, 15]" v-else :loading="loadSearch"
                                    class="p-datatable-sm" :value="dataSourceParamedis" breakpoint="960px"
                                    selectionMode="single" sortMode="multiple" v-model:expanded-rows="expandedRows"
                                    showGridlines tableStyle="min-width: 30rem"
                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                    <template #header>
                                        <div class="columns is-multiline">
                                            <div class="column is-2 pb-0 mb-0" style="padding-top: 2rem;">
                                                <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                                    icon="fas fa-file-excel">
                                                    Export To Excel
                                                </VButton>
                                            </div>

                                            <div class="column is-10 pb-0 mb-3">
                                                <div class="columns is-multiline" style="justify-content: right;">
                                                    <div class="column is-4">
                                                        <VField label="Periode" />
                                                        <VDatePicker class="mt-2" v-model="item.filterTglParamedis" is-range color="pink"
                                                            trim-weeks>
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField addons>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput :value="inputValue.start"
                                                                            v-on="inputEvents.start" />
                                                                    </VControl>
                                                                    <VControl>
                                                                        <VButton static><i class="fas fa-arrow-right"
                                                                                aria-hidden="true"></i></VButton>
                                                                    </VControl>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput :value="inputValue.end"
                                                                            v-on="inputEvents.end" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VField class="is-rounded-select is-autocomplete-select"
                                                            label="Departemen">
                                                            <VControl icon="feather:search" class="prime-auto">
                                                                <Dropdown v-model="item.departemenParm"
                                                                    :options="d_Departemen" :optionLabel="'label'"
                                                                    placeholder="Pilih Departemen" :optionValue="'value'"
                                                                    @change="choiceRuangan(item.departemenParm)"
                                                                    style="width: 100%;" :filter="true" appendTo="body"
                                                                    showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VField class=" is-rounded-select is-autocomplete-select"
                                                            label="Ruangan">
                                                            <VControl icon="feather:search" class="prime-auto"
                                                                :loading="loadRuangan">
                                                                <Dropdown v-model="item.ruanganfkParm" :options="d_Ruangan"
                                                                    :optionLabel="'label'" placeholder="Pilih Ruangan"
                                                                    :optionValue="'value'" style="width: 100%;"
                                                                    :filter="true" appendTo="body" showClear />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-1" style="padding-top: 35px;text-align:center">
                                                        <VIconButton color="success" icon="fas fa-search"
                                                            @click="fetchLaporanRemunParamedis()" :loading="loadSearch" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <Column field="tglpelayanan" header="Tanggal">
                                        <template #body="slotProps">
                                            {{ H.formatDate(slotProps.data.tglpelayanan, 'YYYY-MM-DD') }}
                                        </template>
                                    </Column>
                                    <Column field="nocm" header="No Rekam Medis" />
                                    <Column field="noregistrasi" header="Noregistrasi" />
                                    <Column field="namapasien" header="Nama Pasien" />
                                    <Column field="namaruangan" header="Ruang Layanan" />
                                    <Column field="namaproduk" header="Nama Layanan" />
                                    <Column field="qty" header="Jumlah" />
                                    <Column field="isparamedis" header="P" />
                                    <Column field="iscito" header="Cito" />
                                    <Column field="hargasatuan" header="Harga Satuan" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2),
                                                '') }}
                                        </template>
                                    </Column>
                                    <Column field="total" header="Total" style="text-align:right">
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
                                        </template>
                                    </Column>
                                    <ColumnGroup type="footer">
                                        <Row>
                                            <Column footer="Total" />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column />
                                            <Column :footer="item.ms_Satuan"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                            <Column :footer="item.ms_Total"
                                                style=" padding: 0.3rem 0.3rem 0 0.3rem;text-align:right" />
                                        </Row>
                                    </ColumnGroup>
                                </DataTable>
                            </div>
                        </TabPanel>

                    </TabView>
                    <!-- <VPlaceload height="20rem" width="100%" class="mx-2" /> -->
                </div>
            </VCard>
        </div>
    </section>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Laporan Remunerasi - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    filterTglDetail: reactive({
        start: new Date(),
        end: new Date(),
    }),
    filterTglRekap: reactive({
        start: new Date(),
        end: new Date(),
    }),
    filterTglDokter: reactive({
        start: new Date(),
        end: new Date(),
    }),
    filterTglParamedis: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const activeTab = ref(0);
const dataSourceDetail: any = ref([])
const dataSourceRekap: any = ref([])
const dataSource: any = ref([])
const dataSourceNilaiPagu: any = ref([])
const expandedRows = ref();
let d_KelompokPasien: any = ref([])
let d_Dokter: any = ref([])
let d_Pegawai: any = ref([])
let d_Ruangan: any = ref([])
let d_Departemen: any = ref([])
let dataSourceRemunDokter: any = ref([])
let dataSourceParamedis: any = ref([])
let loadSearch: any = ref(false)
let loadSave: any = ref(false)
let loadData: any = ref(true)
let loadRuangan: any = ref(false)


const fetchDetailLaporan = async () => {

    let tglAwal = H.formatDate(item.value.filterTglDetail.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTglDetail.end, 'YYYY-MM-DD')
    let departemen = item.value.departemenDetail ? `&departemenfk=${item.value.departemenDetail}` : ''
    let ruangan = item.value.ruanganfkDetail ? `&ruanganfk=${item.value.ruanganfkDetail}` : ''

    item.value.tDireksi = 0
    item.value.tStruktural = 0
    item.value.tAdministrasi = 0
    item.value.tJPL = 0
    item.value.tJPTL = 0
    item.value.tGabungan = 0

    loadSearch.value = true
    await useApi().get(`remunerasi/get-detail-laporan-remun?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${departemen}${ruangan}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.tDireksi = parseFloat(element.direksi) + item.value.tDireksi
            item.value.tStruktural = parseFloat(element.struktural) + item.value.tStruktural
            item.value.tAdministrasi = parseFloat(element.administrasi) + item.value.tAdministrasi
            item.value.tJPL = parseFloat(element.jpl) + item.value.tJPL
            item.value.tJPTL = parseFloat(element.jptl) + item.value.tJPTL
            item.value.tGabungan = parseFloat(element.gabungan) + item.value.tGabungan
        });
        dataSourceDetail.value = response.data
        item.value.F_Direksi = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.tDireksi, 2), '')
        item.value.F_Struktural = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.tStruktural, 2), '')
        item.value.F_Administrasi = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.tAdministrasi, 2), '')
        item.value.F_JPL = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.tJPL, 2), '')
        item.value.F_JPTL = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.tJPTL, 2), '')
        item.value.F_Gabungan = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.tGabungan, 2), '')
    })
    loadSearch.value = false
    loadData.value = false
}

const fetchLaporanRemun = async () => {

    let tglAwal = H.formatDate(item.value.filterTglRekap.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTglRekap.end, 'YYYY-MM-DD')
    let pegawai = item.value.pegawaifkLaporan ? `&pegawaifk=${item.value.pegawaifkLaporan.value}` : ''

    item.value.rDireksi = 0
    item.value.rStruktural = 0
    item.value.rAdministrasi = 0
    item.value.rJPL = 0
    item.value.rJPTL = 0
    item.value.rGabungan = 0
    loadSearch.value = true
    await useApi().get(`remunerasi/get-rekap-laporan-remun?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${pegawai}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.rDireksi = parseFloat(element.direksi) + item.value.rDireksi
            item.value.rStruktural = parseFloat(element.struktural) + item.value.rStruktural
            item.value.rAdministrasi = parseFloat(element.administrasi) + item.value.rAdministrasi
            item.value.rJPL = parseFloat(element.jpl) + item.value.rJPL
            item.value.rJPTL = parseFloat(element.jptl) + item.value.rJPTL
            item.value.rGabungan = parseFloat(element.gabungan) + item.value.rGabungan
        })
        dataSourceRekap.value = response.data
        item.value.r_Direksi = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.rDireksi, 2), '')
        item.value.r_Struktural = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.rStruktural, 2), '')
        item.value.r_Administrasi = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.rAdministrasi, 2), '')
        item.value.r_JPL = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.rJPL, 2), '')
        item.value.r_JPTL = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.rJPTL, 2), '')
        item.value.r_Gabungan = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.rGabungan, 2), '')
    })
    loadSearch.value = false
    loadData.value = false
}

const fetchLaporanRemunDok = async () => {

    let tglAwal = H.formatDate(item.value.filterTglDokter.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTglDokter.end, 'YYYY-MM-DD')
    let pegawai = item.value.pegawaifkDok ? `&dokterfk=${item.value.pegawaifkDok.value}` : ''
    let departemen = item.value.departemenDok ? `&departemenfk=${item.value.departemenDok}` : ''
    let ruangan = item.value.ruanganfkDok ? `&ruanganfk=${item.value.ruanganfkDok}` : ''

    item.value.drSatuan = 0
    item.value.drTotal = 0
    loadSearch.value = true
    await useApi().get(`remunerasi/get-detail-laporan-remun-dokter?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${pegawai}${departemen}${ruangan}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.drSatuan = parseFloat(element.hargasatuan) + item.value.drSatuan
            item.value.drTotal = parseFloat(element.total) + item.value.drTotal
        })
        dataSourceRemunDokter.value = response.data
        item.value.dr_Satuan = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.drSatuan, 2), '')
        item.value.dr_Total = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.drTotal, 2), '')
    })
    loadSearch.value = false
    loadData.value = false
}

const fetchLaporanRemunParamedis = async () => {

    let tglAwal = H.formatDate(item.value.filterTglParamedis.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterTglParamedis.end, 'YYYY-MM-DD')
    let departemen = item.value.departemenParm ? `&departemenfk=${item.value.departemenParm}` : ''
    let ruangan = item.value.ruanganfkParm ? `&ruanganfk=${item.value.ruanganfkParm}` : ''

    item.value.msSatuan = 0
    item.value.msTotal = 0
    loadSearch.value = true
    await useApi().get(`remunerasi/get-detail-laporan-remun-paramedis?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${departemen}${ruangan}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.msSatuan = parseFloat(element.hargasatuan) + item.value.msSatuan
            item.value.msTotal = parseFloat(element.total) + item.value.msTotal
        })
        dataSourceParamedis.value = response.data
        item.value.ms_Satuan = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.msSatuan, 2), '')
        item.value.ms_Total = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.msTotal, 2), '')
    })
    loadSearch.value = false
    loadData.value = false
}

const dataDepartemen = async () => {
    await useApi().get('sysadmin/master-ruangan-dropdown').then((response) => {
        d_Departemen.value = response.namadepartemen.map((e: any) => {
            return { label: e.namadepartemen, value: e.id }
        })
    })
}

const choiceRuangan = async (e: any) => {
    loadRuangan.value = true
    await useApi().get(`remunerasi/get-ruangan?departemenfk=${e}`).then((response) => {
        d_Ruangan.value = response.map((e: any) => {
            return { label: e.namaruangan, value: e.id }
        })
    })
    loadRuangan.value = false
}

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const klikTab = (e: any) => {
    activeTab.value = e.index
    if (e.index == 0) {
        fetchDetailLaporan()
    }
    if (e.index == 1) {
        fetchLaporanRemun()
    }
    if (e.index == 2) {
        fetchLaporanRemunDok()
    }
    if (e.index == 3) {
        fetchLaporanRemunParamedis()
    }
}

const clear = () => {
    item.value.filterTgl.start = new Date()
    item.value.filterTgl.end = new Date()
    delete item.value.departemen
    delete item.value.ruanganfk
    delete item.value.pegawaifk
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    let detailLaporan = [
        ['Detail Remunerasi'],
        [],
        ['No', 'Nama Unit', 'DIREKSI', 'STRUKTURAL', 'ADMINISTRASI', 'JPL', 'JPTL', 'GABUNGAN'],
        ...dataSourceDetail.value.map((e: any) => [
            e.no,
            e.namaruangan,
            e.direksi ? H.roundToDecimal(parseFloat(e.direksi), 2) : 0,
            e.struktural ? H.roundToDecimal(parseFloat(e.struktural), 2) : 0,
            e.administrasi ? H.roundToDecimal(parseFloat(e.administrasi), 2) : 0,
            e.jpl ? H.roundToDecimal(parseFloat(e.jpl), 2) : 0,
            e.jptl ? H.roundToDecimal(parseFloat(e.jptl), 2) : 0,
            e.gabungan ? H.roundToDecimal(parseFloat(e.gabungan), 2) : 0,
        ]),
        ['', 'TOTAL',
            H.roundToDecimal(parseFloat(item.value.tDireksi), 2),
            H.roundToDecimal(parseFloat(item.value.tStruktural), 2),
            H.roundToDecimal(parseFloat(item.value.tAdministrasi), 2),
            H.roundToDecimal(parseFloat(item.value.tJPL), 2),
            H.roundToDecimal(parseFloat(item.value.tJPTL), 2),
            H.roundToDecimal(parseFloat(item.value.tGabungan), 2)
        ]

    ];

    let rekapLaporan = [
        ['Rekap Laporan Remunerasi'],
        [],
        ['No', 'Nama Unit', 'DIREKSI', 'STRUKTURAL', 'ADMINISTRASI', 'JPL', 'JPTL', 'GABUNGAN'],
        ...dataSourceRekap.value.map((e: any) => [
            e.no,
            e.namaruangan,
            e.direksi ? H.roundToDecimal(parseFloat(e.direksi), 2) : 0,
            e.struktural ? H.roundToDecimal(parseFloat(e.struktural), 2) : 0,
            e.administrasi ? H.roundToDecimal(parseFloat(e.administrasi), 2) : 0,
            e.jpl ? H.roundToDecimal(parseFloat(e.jpl), 2) : 0,
            e.jptl ? H.roundToDecimal(parseFloat(e.jptl), 2) : 0,
            e.gabungan ? H.roundToDecimal(parseFloat(e.gabungan), 2) : 0,
        ]),
        ['', 'TOTAL',
            H.roundToDecimal(parseFloat(item.value.rDireksi), 2),
            H.roundToDecimal(parseFloat(item.value.rStruktural), 2),
            H.roundToDecimal(parseFloat(item.value.rAdministrasi), 2),
            H.roundToDecimal(parseFloat(item.value.rJPL), 2),
            H.roundToDecimal(parseFloat(item.value.rJPTL), 2),
            H.roundToDecimal(parseFloat(item.value.rGabungan), 2)
        ]

    ];

    let remunDokter = [
        ['LAPORAN DETAIL REMUNERASI DOKTER'],
        [],
        ['No', 'Tanggal', 'NO CM', 'NOREGISTRASI', 'NAMA PASIEN', 'RUANGAN', 'LAYANAN', 'JUMLAH', 'P', 'CITO', 'HARGA SATUAN', 'TOTAL'],
        ...dataSourceRemunDokter.value.map((e: any) => [
            e.no,
            H.formatDate(e.tglpelayanan, 'YYYY-MM-DD'),
            e.nocm,
            e.noregistrasi,
            e.namapasien,
            e.namaruangan,
            e.namaproduk,
            e.qty ? parseFloat(e.qty) : 0,
            e.isparamedis,
            e.iscito,
            e.hargasatuan ? H.roundToDecimal(parseFloat(e.hargasatuan), 2) : 0,
            e.total ? H.roundToDecimal(parseFloat(e.total), 2) : 0,
        ]),
        ['', 'TOTAL', '', '', '', '', '', '', '', '',
            H.roundToDecimal(parseFloat(item.value.drSatuan), 2),
            H.roundToDecimal(parseFloat(item.value.drTotal), 2),
        ]

    ];

    let remunParamedis = [
        ['LAPORAN DETAIL REMUNERASI PARAMEDIS'],
        [],
        ['No', 'Tanggal', 'NO CM', 'NOREGISTRASI', 'NAMA PASIEN', 'RUANGAN', 'LAYANAN', 'JUMLAH', 'P', 'CITO', 'HARGA SATUAN', 'TOTAL'],
        ...dataSourceRemunDokter.value.map((e: any) => [
            e.no,
            H.formatDate(e.tglpelayanan, 'YYYY-MM-DD'),
            e.nocm,
            e.noregistrasi,
            e.namapasien,
            e.namaruangan,
            e.namaproduk,
            e.qty ? parseFloat(e.qty) : 0,
            e.isparamedis,
            e.iscito,
            e.hargasatuan ? H.roundToDecimal(parseFloat(e.hargasatuan), 2) : 0,
            e.total ? H.roundToDecimal(parseFloat(e.total), 2) : 0,
        ]),
        ['', 'TOTAL', '', '', '', '', '', '', '', '',
            H.roundToDecimal(parseFloat(item.value.msSatuan), 2),
            H.roundToDecimal(parseFloat(item.value.msTotal), 2)
        ]
    ];

    let worksheet
    let title
    if (e == 0) {
        worksheet = XLSX.utils.aoa_to_sheet(detailLaporan);
        title = 'Detail Laporan Remunerasi.xlsx'
    }
    if (e == 1) {
        worksheet = XLSX.utils.aoa_to_sheet(rekapLaporan);
        title = 'Rekap Laporan Remunerasi.xlsx'
    }
    if (e == 2) {
        worksheet = XLSX.utils.aoa_to_sheet(remunDokter);
        title = 'Laporan Remunerasi Dokter.xlsx'
    }
    if (e == 3) {
        worksheet = XLSX.utils.aoa_to_sheet(remunParamedis);
        title = 'Laporan Remunerasi Paramedis.xlsx'
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
    console.log(worksheet)
    // atur lebar column
    let columnWidths
    if (e == 0 || e == 1) {
        columnWidths = [5, 20, 15, 15, 15, 15, 15, 15, 15, 15];
    } else {
        columnWidths = [5, 20, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15];
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
    if (e == 0 || e == 1) {
        mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } };
    } else {
        mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 9 } };
    }
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'REMUNERASI', true);
    XLSXStyle.writeFile(workbook, title);
}

dataDepartemen()
fetchDetailLaporan()


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
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}
</style>
