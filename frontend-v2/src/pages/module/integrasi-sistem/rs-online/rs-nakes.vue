<template>
    <VCard>
        <h3>Rekap Harian Tenaga Kesehatan Terinfeksi Covid-19</h3>
        <div class="columns is-multiline mt-2">
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <span>Periode</span>
                        <VDatePicker v-model="item.filterDate" is-range color="pink" trim-weeks :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VField addons>
                                    <VControl icon="feather:calendar">
                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                    </VControl>

                                </VField>
                            </template>
                        </VDatePicker>
                    </div>

                    <div class="column is-2" style="margin-top: 30px">
                        <VIconButton circle icon="feather:refresh-cw" raised bold @click="SearchData()" :loading="isLoading"
                            v-tooltip.bubble="'Cari'" class="mt-2-min">
                        </VIconButton>
                    </div>
                </div>

                <div class="column is-12" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>

                    <DataTable :value="dataSource" dataKey="no" class="p-datatable-sm" :paginator="true" :rows="10"
                        :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <template #header>
                            <div class="flex justify-content-between">

                                <VButton color="success" class="mr-4 mb-3" icon="fas fa-plus" raised @click="tambahData()">
                                    Tambah Data </VButton>
                            </div>
                        </template>

                        <ColumnGroup type="header">
                            <Row>
                                <Column field="tanggal" header="Tanggal" :rowspan="3" style="min-width: 200px" />
                                <Column header="Dokter Umum" :colspan="4" />
                                <Column header="Dokter Spesialis" :colspan="4" />
                                <Column header="Dokter Gigi" :colspan="4" />
                                <Column header="Residen" :colspan="4" />
                                <Column header="Bidan" :colspan="4" />
                                <Column header="Perawat" :colspan="4" />
                                <Column header="Apoteker" :colspan="4" />
                                <Column header="Radiografer" :colspan="4" />
                                <Column header="Analisis Lab" :colspan="4" />
                                <Column header="COASS" :colspan="4" />
                                <Column header="Internship" :colspan="4" />
                                <Column header="Nakes Lainnya" :colspan="4" />
                            </Row>

                            <Row>
                                <Column field="dokter_umum" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="dokter_umum_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="dokter_umum_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="dokter_umum_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="dokter_spesialis" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="dokter_spesialis_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="dokter_spesialis_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="dokter_spesialis_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="dokter_gigi" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="dokter_gigi_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="dokter_gigi_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="dokter_gigi_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="residen" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="residen_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="residen_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="residen_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="perawat" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="perawat_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="perawat_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="perawat_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="bidan" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="bidan_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="bidan_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="bidan_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="apoteker" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="apoteker_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="apoteker_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="apoteker_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="radiografer" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="radiografer_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="radiografer_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="radiografer_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="analis_lab" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="analis_lab_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="analis_lab_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="analis_lab_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="co_ass" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="co_ass_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="co_ass_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="co_ass_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="internship" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="internship_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="internship_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="internship_sembuh" header="Sembuh" style="min-width: 100px" />

                                <Column field="nakes_lainnya" header="Terinfeksi" style="min-width: 100px" />
                                <Column field="nakes_lainnya_dirawat" header="Dirawat" style="min-width: 100px" />
                                <Column field="nakes_lainnya_isoman" header="Isoman" style="min-width: 100px" />
                                <Column field="nakes_lainnya_sembuh" header="Sembuh" style="min-width: 100px" />
                            </Row>

                        </ColumnGroup>

                    </DataTable>
                </div>
            </div>

        </div>

    </VCard>

    <Dialog v-model:visible="modalInput" modal header="Laporan Pemeriksaan SWAB PCR untuk Nakes" :style="{ width: '80vw' }">

        <div class="column is-4">
            <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tanggal" color="green" trim-weeks mode="dateTime"
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                    <VField>
                        <VLabel class="required-field">Tanggal</VLabel>
                        <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                class="is-rounded" />
                        </VControl>
                    </VField>
                </template>
            </VDatePicker>
        </div>
        <div class="columns is-multiline">

            <div class="column is-3">
                <Fieldset legend="Dokter Umum" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.umumterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.umumtermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.umumdirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.umumisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.umumsembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-3">
                <Fieldset legend="Dokter Spesialis" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.spesialisterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.spesialistermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.spesialisdirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.spesialisisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.spesialistersembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-3">
                <Fieldset legend="Dokter Gigi" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.gigiterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.gigitermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.gigidirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.gigiisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.gigitersembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-3">
                <Fieldset legend="Residen" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.residenterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.residentermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.residendirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.residenisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.residentersembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Perawat" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.perawatterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.perawattermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.perawatdirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.perawatisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.perawattersembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-3">
                <Fieldset legend="Bidan" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.bidanterinfeksi" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.bidantermeninggal" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.bidandirawat" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.bidanisoman" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.bidantersembuh" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Apoteker" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.apotekterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.apotektermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.apotekdirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.apotekdirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.apotekdirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Radiografer" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.radioterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.radiotermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.radiodirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.radiodirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.radioisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.radiotersembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Analisis Lab" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.analislabterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.analislabdirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.analislabisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.analislabisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.analislabtersembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="COAS" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.coasterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.coastermeninggal" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.coasdirawat" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.coasisoman" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.coastersembuh" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Internship" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.intershipterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.intershiptermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.intershipdirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.intershipisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.intershiptersembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Nakes Lainnya" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Terinfeksi</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.nakeslainterinfeksi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Meninggal</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.nakeslaintermeninggal" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Dirawat</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.nakeslaindirawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Isoman</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.nakeslainisoman" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Sembuh</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.nakeslaintersembuh" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>




        </div>
        <template #footer>
            <VButton color="danger" icon="pi pi-times" outlined raised @click="modalInput = false"> Batal </VButton>
            <VButton color="primary" icon="pi pi-check" raised @click="simpanKebutuhan()" :loading="isRouteLoading"> Simpan
            </VButton>
        </template>
    </Dialog>
</template>


<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import ColumnGroup from 'primevue/columngroup';
import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar';
import Fieldset from 'primevue/fieldset';
import moment from 'moment'

useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)

const item: any = reactive({
    filterDate: {
        start: new Date(),
        end: new Date()
    },
    tanggal: new Date(),
    limit: 10,
    start: 1,
})
const dataSource = ref([])
const dataRS = ref([])

const isLoadingTT: any = ref(false)
const isLoadingSave: any = ref(false)
const btnLoadSimpan = ref(false)
const isRouteLoading = ref(false)
const listKebutuhan = ref([])
const modalInput = ref(false)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const fetchData = async () => {
    let json = {
        "url": "Pasien/harian_nakes_terinfeksi",
        "method": "GET",
        "jenis": "sirsonlinev3",
        "data": null
    }
    const res = await useApi().postNoMessage(
        `/bridging/kemenkes/tools`, json)
    if (res.response != null) {
        res.response.forEach((element: any) => {
            element.no = element + 1
        });
        dataSource.value = res.response
    } else {
        H.alert('error', res.metaData.message)
    }
}

const tambahData = () => {
    modalInput.value = true
}

const simpanKebutuhan = () => {

    let tgl = moment(item.tanggal).format("YYYY-MM-DD");
    let json = {
        "url": "Pasien/harian_nakes_terinfeksi",
        "method": "POST",
        "jenis": "sirsonlinev3",
        "head": "x-tanggal: " + tgl,
        "data": {
            "tanggal": tgl,
            "co_ass": item.coasterinfeksi === undefined ? 0 : item.coasterinfeksi,
            "residen": item.residenterinfeksi === undefined ? 0 : item.residenterinfeksi,
            "intership": item.intershipterinfeksi === undefined ? 0 : item.intershipterinfeksi,
            "dokter_spesialis": item.spesialisterinfeksi === undefined ? 0 : item.spesialisterinfeksi,
            "dokter_umum": item.umumterinfeksi === undefined ? 0 : item.umumterinfeksi,
            "dokter_gigi": item.gigiterinfeksi === undefined ? 0 : item.gigiterinfeksi,
            "perawat": item.perawatterinfeksi === undefined ? 0 : item.perawatterinfeksi,
            "bidan": item.bidanterinfeksi === undefined ? 0 : item.bidanterinfeksi,
            "apoteker": item.apotekterinfeksi === undefined ? 0 : item.apotekterinfeksi,
            "radiografer": item.radioterinfeksi === undefined ? 0 : item.radioterinfeksi,
            "analis_lab": item.analislabterinfeksi === undefined ? 0 : item.analislabterinfeksi,
            "nakes_lainnya": item.nakeslainterinfeksi === undefined ? 0 : item.nakeslainterinfeksi,
            "co_ass_meninggal": item.coastermeninggal === undefined ? 0 : item.coastermeninggal,
            "residen_meninggal": item.residentermeninggal === undefined ? 0 : item.residentermeninggal,
            "intership_meninggal": item.intershiptermeninggal === undefined ? 0 : item.intershiptermeninggal,
            "dokter_spesialis_meninggal": item.spesialistermeninggal === undefined ? 0 : item.spesialistermeninggal,
            "dokter_umum_meninggal": item.umumtermeninggal === undefined ? 0 : item.umumtermeninggal,
            "dokter_gigi_meninggal": item.gigitermeninggal === undefined ? 0 : item.gigitermeninggal,
            "perawat_meninggal": item.perawattermeninggal === undefined ? 0 : item.perawattermeninggal,
            "bidan_meninggal": item.bidantermeninggal === undefined ? 0 : item.bidantermeninggal,
            "apoteker_meninggal": item.apotektermeninggal === undefined ? 0 : item.apotektermeninggal,
            "radiografer_meninggal": item.radiotermeninggal === undefined ? 0 : item.radiotermeninggal,
            "analis_lab_meninggal": item.analislabtermeninggal === undefined ? 0 : item.analislabtermeninggal,
            "nakes_lainnya_meninggal": item.nakeslaintermeninggal === undefined ? 0 : item.nakeslaintermeninggal,
            "co_ass_dirawat": item.coasdirawat === undefined ? 0 : item.coasdirawat,
            "co_ass_isoman": item.coasisoman === undefined ? 0 : item.coasisoman,
            "co_ass_sembuh": item.coastersembuh === undefined ? 0 : item.coastersembuh,
            "residen_dirawat": item.residendirawat === undefined ? 0 : item.residendirawat,
            "residen_isoman": item.residenisoman === undefined ? 0 : item.residenisoman,
            "residen_sembuh": item.residentersembuh === undefined ? 0 : item.residentersembuh,
            "intership_dirawat": item.intershipdirawat === undefined ? 0 : item.intershipdirawat,
            "intership_isoman": item.intershipisoman === undefined ? 0 : item.intershipisoman,
            "intership_sembuh": item.intershiptersembuh === undefined ? 0 : item.intershiptersembuh,
            "dokter_spesialis_dirawat": item.spesialisdirawat === undefined ? 0 : item.spesialisdirawat,
            "dokter_spesialis_isoman": item.spesialisisoman === undefined ? 0 : item.spesialisisoman,
            "dokter_spesialis_sembuh": item.spesialistersembuh === undefined ? 0 : item.spesialistersembuh,
            "dokter_umum_dirawat": item.umumdirawat === undefined ? 0 : item.umumdirawat,
            "dokter_umum_isoman": item.umumisoman === undefined ? 0 : item.umumisoman,
            "dokter_umum_sembuh": item.umumtersembuh === undefined ? 0 : item.umumtersembuh,
            "dokter_gigi_dirawat": item.gigidirawat === undefined ? 0 : item.gigidirawat,
            "dokter_gigi_isoman": item.gigiisoman === undefined ? 0 : item.gigiisoman,
            "dokter_gigi_sembuh": item.gigitersembuh === undefined ? 0 : item.gigitersembuh,
            "perawat_dirawat": item.perawatdirawat === undefined ? 0 : item.perawatdirawat,
            "perawat_isoman": item.perawatisoman === undefined ? 0 : item.perawatisoman,
            "perawat_sembuh": item.perawattersembuh === undefined ? 0 : item.perawattersembuh,
            "bidan_dirawat": item.bidandirawat === undefined ? 0 : item.bidandirawat,
            "bidan_isoman": item.bidanisoman === undefined ? 0 : item.bidanisoman,
            "bidan_sembuh": item.bidantersembuh === undefined ? 0 : item.bidantersembuh,
            "apoteker_dirawat": item.apotekdirawat === undefined ? 0 : item.apotekdirawat,
            "apoteker_isoman": item.apotekisoman === undefined ? 0 : item.apotekisoman,
            "apoteker_sembuh": item.apotektersembuh === undefined ? 0 : item.apotektersembuh,
            "radiografer_dirawat": item.radiodirawat === undefined ? 0 : item.radiodirawat,
            "radiografer_isoman": item.radioisoman === undefined ? 0 : item.radioisoman,
            "radiografer_sembuh": item.radiotersembuh === undefined ? 0 : item.radiotersembuh,
            "analis_lab_dirawat": item.analislabdirawat === undefined ? 0 : item.analislabdirawat,
            "analis_lab_isoman": item.analislabisoman === undefined ? 0 : item.analislabisoman,
            "analis_lab_sembuh": item.analislabtersembuh === undefined ? 0 : item.analislabtersembuh,
            "nakes_lainnya_dirawat": item.nakeslaindirawat === undefined ? 0 : item.nakeslaindirawat,
            "nakes_lainnya_isoman": item.nakeslainisoman === undefined ? 0 : item.nakeslainisoman,
            "nakes_lainnya_sembuh": item.nakeslaintersembuh === undefined ? 0 : item.nakeslaintersembuh,
        }
    }
    isRouteLoading.value = true
    const data = useApi().postNoMessage('/bridging/kemenkes/tools', json)
        .then((data: any) => {
            if (data.metaData.code == 200) {
                H.alert('success', data.metaData.message);
            } else {
                H.alert('error', data.metaData.message)
            }
            isRouteLoading.value = false;
            modalInput.value = false
            fetchData()
            clear()
        })
}


const SearchData = async () => {
    if (item.periode.start == undefined) {
        fetchData()
    } else {
        var tgl = moment(item.periode.start).format("YYYY-MM-DD");
        var json = {
            "url": "Pasien/harian_nakes_terinfeksi",
            "method": "GET",
            "jenis": "sirsonlinev3",
            "head": "x-tanggal: " + tgl,
            "data": null
        }
        isRouteLoading.value = true
        const res = await useApi().postNoMessage(
            `/bridging/kemenkes/tools`, json)
        if (res.metaData.code == 200) {
            res.response.forEach((element: any) => {
                element.no = element + 1
            });
            dataSource.value = res.response
        } else {
            H.alert('error', res.metaData.message)
        }
    }
}


const clear = () => {

    delete item.coasterinfeksi
    delete item.residenterinfeksi
    delete item.intershipterinfeksi
    delete item.spesialisterinfeksi
    delete item.umumterinfeksi
    delete item.gigiterinfeksi
    delete item.perawatterinfeksi
    delete item.bidanterinfeksi
    delete item.apotekterinfeksi
    delete item.radioterinfeksi
    delete item.analislabterinfeksi
    delete item.nakeslainterinfeksi
    delete item.coastermeninggal
    delete item.residentermeninggal
    delete item.intershiptermeninggal
    delete item.spesialistermeninggal
    delete item.umumtermeninggal
    delete item.gigitermeninggal
    delete item.perawattermeninggal
    delete item.bidantermeninggal
    delete item.apotektermeninggal
    delete item.radiotermeninggal
    delete item.analislabtermeninggal
    delete item.nakeslaintermeninggal
    delete item.coasdirawat
    delete item.coasisoman
    delete item.coastersembuh
    delete item.residendirawat
    delete item.residenisoman
    delete item.residentersembuh
    delete item.intershipdirawat
    delete item.intershipisoman
    delete item.intershiptersembuh
    delete item.spesialisdirawat
    delete item.spesialisisoman
    delete item.spesialistersembuh
    delete item.umumdirawat
    delete item.umumisoman
    delete item.umumtersembuh
    delete item.gigidirawat
    delete item.gigiisoman
    delete item.gigitersembuh
    delete item.perawatdirawat
    delete item.perawatisoman
    delete item.perawattersembuh
    delete item.bidandirawat
    delete item.bidanisoman
    delete item.bidantersembuh
    delete item.apotekdirawat
    delete item.apotekisoman
    delete item.apotektersembuh
    delete item.radiodirawat
    delete item.radioisoman
    delete item.radiotersembuh
    delete item.analislabdirawat
    delete item.analisislabtersembuh
    delete item.nakeslaindirawat
    delete item.nakeslainisoman
    delete item.nakeslaintersembuh

}


fetchData()


</script>
<style lang="scss">
.control.has-icon {
    position: relative;
    width: 100%;
}

.field:not(:last-child) {
    margin-bottom: 0px !important;
}
</style>
