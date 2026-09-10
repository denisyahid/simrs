<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ lockedFormName }} {{ route.params.index_tabs }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :ID_EMR="ID_EMR"
                            :isLoading="isLoading" @simpanTemplate="simpanTemplate" @simpan="simpan"
                            @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Input</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Registrasi</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">No Registrasi</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">No EMR</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="20%">Halaman</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Section</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.index_tabs }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayat(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="showModalTemplateFix = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Template</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
                            <thead>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="5%">No</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Dibuat</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="20%">Nama Ruangan</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="25%">Nama Template</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td
                                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td
                                        style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td
                                        style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                    </td>
                                    <td
                                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
            <VCard v-else>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                                isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                            </VButton>
                            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                                isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                            </VButton>
                            <VButton rounded icon="feather:plus" raised bold @click="addUpload()" color="warning"
                                outlined :loading="isLoading" class="mr-2">Upload</VButton>
                            <VButton rounded icon="feather:eye" raised bold @click="previewBerkas()" color="purple"
                                outlined :loading="isLoading" class="mr-2">Lihat Foto</VButton>
                        </div>

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                        <div class="column is-12">
                            <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                                    template</span></h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.namatemplate" rows="1">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Ruangan Operasi </h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.kamaroperasi" :suggestions="d_Kamar"
                                        @complete="fetchKamarOperasi($event)" :optionLabel="'namakamarok'"
                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'namakamarok'"
                                        placeholder="ketik kamar operasi" class="mt-2" />
                                </VControl>
                                <!-- <VControl>
                                <AutoComplete v-model="input.namaruangans" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Ruangan" />
                            </VControl> -->
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal & Jam </h1>
                            <VField>
                                <VDatePicker v-model="input.tglOperasi" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
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
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Operator </h1>
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.dokterBedah" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Cari nama Pegawai" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Asisten </h1>
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.asistenBedahI" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Cari nama Pegawai" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Ahli Anestesi</h1>
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.dokterAnestesi" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Cari nama Pegawai" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Asisten</h1>
                            <VField>
                                <VControl>
                                    <AutoComplete v-model="input.asistenAnestesi" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="Cari nama Pegawai" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Jenis Anastesi </h1>
                        </div>
                        <div class="column is-1">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Umum" true-value="Umum"
                                        label="Umum" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Spinal" true-value="Spinal"
                                        label="Spinal" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Epidural"
                                        true-value="Epidural" label="Epidural" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.BSP" true-value="BSP"
                                        label="BSP" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.CSE" true-value="CSE"
                                        label="CSE" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Lokal" true-value="Lokal"
                                        label="Lokal" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-3"></div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Golongan Operasi </h1>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Khusus" true-value="Khusus"
                                        label="Khusus" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Mayor" true-value="Mayor"
                                        label="Mayor" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Medium" true-value="Medium"
                                        label="Medium" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4"></div>
                        <div class="column is-2"></div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Minor" true-value="Minor"
                                        label="Minor" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Emergensi"
                                        true-value="Emergensi" label="Emergensi" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.Elektif"
                                        true-value="Elektif" label="Elektif" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.odc" true-value="ODC"
                                        label="ODC" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4"></div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa Pra-Bedah </h1>
                            <VField>
                                <VInput type="text" class="input" v-model="input.DiagnosaPraBedah" />
                                <!-- <VControl> -->
                                <!-- <AutoComplete v-model="input.diagnosaPraBedah" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa" /> -->
                                <!-- </VControl> -->
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa Pasca-Bedah </h1>
                            <VField>
                                <VInput type="text" class="input" v-model="input.DiagnosaPascaBedah" />
                                <!-- <VControl>
                                <AutoComplete v-model="input.diagnosaPascaBedah" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa" />
                            </VControl> -->
                            </VField>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Indikasi Operasi </h1>
                            <VField>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Indikasi Operasi"
                                            v-model="input.indikasiOperasi" />
                                    </VControl>
                                </VField>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama Operasi </h1>
                            <VField class="is-flex" v-for="(item, key) in input.namaOperasi">
                                <VControl style="flex-grow: 4;">
                                    <VInput type="text" class="input" placeholder="Nama Operasi"
                                        v-model="input.namaOperasi[key]" />
                                </VControl>
                                <VIconButton class="ml-2" raised circle icon="lucide:plus" @click="tambahNmOperasi()"
                                    :loading="isLoading" color="info" v-tooltip-prime.top="'Tambah'" v-if="key == 0">
                                </VIconButton>
                                <VIconButton class="ml-2" raised circle icon="lucide:trash-2"
                                    @click="input.namaOperasi.splice(key, 1)" :loading="isLoading" color="danger"
                                    v-tooltip-prime.top="'Hapus'" v-if="key != 0">
                                </VIconButton>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Jaringan yang dieksisi </h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.jaringanEksisi" rows="3"
                                        placeholder="Jaringan yang dieksisi">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6 mt-5">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Pemeriksaan PA </h1>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.yaPA"
                                                true-value="Ya" label="Ya" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakPA"
                                                true-value="Tidak" label="Tidak" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3"></div>
                                <div class="column is-3">
                                    <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Kultur </h1>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.yaKultur"
                                                true-value="Ya" label="Ya" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tidakKultur"
                                                true-value="Tidak" label="Tidak" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Operasi</h1>
                                <VField>
                                    <VDatePicker v-model="input.tgloperasi" mode="date" trim-weeks
                                        :max-date="new Date()">
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
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam Operasi Mulai</h1>
                                <VDatePicker v-model="input.jamStartOperasi" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:clock">
                                                <VInput class="input form-timepicker" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam Operasi Berakhir</h1>
                                <VDatePicker v-model="input.jamEndOperasi" color="green" mode="time" is24hr>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:clock">
                                                <VInput class="input form-timepicker" :value="inputValue"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Lama Operasi</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Durasi Operasi"
                                            v-model="input.lamaOperasi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Komplikasi / Penyulit Operasi</h1>
                                <VControl>
                                    <VTextarea class="textarea" v-model="input.penyulitOperasi" rows="3"
                                        placeholder="Catatan" autocomplete="off" autocapitalize="off"
                                        spellcheck="true" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jumlah Perdarahan Keluar</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Jumlah Perdarahan"
                                            v-model="input.jumlahDarah" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jumlah Transfusi</h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Jumlah Transfusi"
                                            v-model="input.jumlahTransfusi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Perawatan Pasca Operasi </h1>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.RuanganPasca"
                                            true-value="Ruangan" label="Ruangan" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.PICUNICU"
                                            true-value="PICU/NICU" label="PICU/NICU" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.HCU" true-value="HCU"
                                            label="HCU" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.RTI" true-value="RTI"
                                            label="RTI" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Prosedur operasi yang dilakukan
                                    dan rincian temuan
                                </h1>
                                <VControl>
                                    <VTextarea class="textarea" v-model="input.laporan" rows="30" placeholder="Catatan"
                                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                                </VControl>
                            </div>
                            <div class="column is-12" align="center">
                                <TandaTangan :elemenID="'TTDLaporanOperasi'" :width="'200'" :height="'200'" />
                            </div>
                            <div class="column is-5" style="margin-top: 200px;">
                                <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Nomor pendaftaran dari alat yang
                                    dipasang </h1>
                                <VField>
                                    <VControl>
                                        <VInput type="text" class="input" placeholder=""
                                            v-model="input.nomorPendaftaran" width="" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4" style="margin-top: 200px;"></div>
                            <div class="column is-3" align="center" style="margin-top: 200px;">
                                <h1>Operator</h1>
                                <TandaTangan :elemenID="'TTDOperator'" :width="'150'" :height="'150'" />
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.CBBidan" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" class="mt-2" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </div>
            </VCard>
        </div>
    </div>

    <VModal :open="modalBerkasPreview" title="Lihat Foto" :noclose="true" size="large" actions="right"
        @close="modalBerkasPreview = false">
        <template #content>
            <BerkasPasienView :data="dataSource" @edit="edit" @hapus="hapus" @lihat="lihat" :hide="false">
            </BerkasPasienView>
        </template>
    </VModal>

    <VModal :open="modalInput" title="Upload Foto" :noclose="true" size="medium" actions="right"
        @close="modalInput = false">
        <template #content>
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">Author</VLabel>
                        <VControl>
                            <VInput type="text" class="input" v-model="item.author" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField class="is-rounded-select is-autocomplete-select mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">File</VLabel>
                        <VControl icon="fas fa-sticky-note" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.namafile" :options="d_Berkas" :optionLabel="'label'"
                                class="is-rounded" placeholder="File" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">Nama</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="item.nama" type="text" class="input is-rounded" placeholder="Nama " />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <FileUpload v-model="filePasien" mode="basic" name="demo" accept="image/jpeg,image/png"
                        @upload="onUpload" outlined
                        style=" background-color: transparent; color: var(--danger); border: 1px solid;"
                        :chooseLabel="filePasien ? filePasien.name : 'Unggah'" @select="onSelect($event)"
                        class="is-rounded w-100" />
                </div>
            </div>
        </template>
        <template #action>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpanFile()"> Simpan
            </VButton>
        </template>
    </VModal>

    <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Input</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Registrasi</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">No Registrasi</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">No EMR</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Section</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayat(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <!-- <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="isAlltemplate = false; showModalTemplateFix = false">
        <template #content>
            <DataTable :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10"
                paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
                :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
                breakpoint="960px">
                <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-8">
                            <VField>
                                <InputText v-model="filtersTemplate['global'].value"
                                    placeholder="Search Nama Template" />
                            </VField>
                        </div>
                        <div class="column is-4 is-flex" style="justify-content: center;">
                            <VControl>
                                <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                            </VControl>
                        </div>
                    </div>
                </template>
                <template #empty> No templates found. </template>
                <template #loading>
                    <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                    <p style="color:white">Loading data, please wait...</p>
                </template>
                <Column headerStyle="width: 8rem">
                    <template #body="slotProps">
                        <VButtons>
                            <VIconButton color="danger" light raised circle icon="lucide:x"
                                @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate"
                                v-tooltip-prime.top="'Hapus'" />
                            <VIconButton type="button" raised circle icon="fas fa-plus"
                                @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                            </VIconButton>
                            <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                                @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                                v-if="!isAlltemplate">
                            </VIconButton>
                        </VButtons>
                    </template>
                </Column>
                <Column field="namatemplate" header="Nama" :sortable="true"></Column>
                <Column field="created_at" header="Tanggal" :sortable="true">
                    <template #body="slotProps">
                        <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                    </template>
                </Column>
            </DataTable>
        </template>
    </VModal> -->

</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave, onBeforeRouteUpdate } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import BerkasPasienView from '../../berkas-pasien-preview.vue'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const ID_EMR: any = ref('')
const user = useUserSession().getUser().pegawai;
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        COLLECTION?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: '',
    }
)

const modalInput: any = ref(false)
const modalBerkasPreview: any = ref(false)
const d_Berkas: any = ref([])
const dataSource: any = ref([])
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Dokter = ref([])
const d_Pegawai = ref([])
const d_Diagnosa: any = ref([])
const d_Ruangan: any = ref([])
const dataTTD: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const filePasien: any = ref()
const input: any = ref({
    jamStartOperasi: new Date(),
    jamEndOperasi: new Date(),
    tglOperasi: new Date(),
    jamPenulisanLaporan: new Date(),
    namaOperasi: ['']
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
    loadData.value = true
    await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`).then(async (response: any) => {
        if (response.length) {
            if (response[0].namaOperasi && !Array.isArray(response[0].namaOperasi)) {
                response[0].namaOperasi = [response[0].namaOperasi];
            }
            input.value = response[0]

            if (NOREC_EMRPASIEN.value == '') {
                NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }
            if (ID_EMR.value == '') {
                ID_EMR.value = response[0].id
            }
            dataTTD.value = response[0]
            H.tandaTangan().set("TTDLaporanOperasi", dataTTD.value.TTDLaporanOperasi)
            H.tandaTangan().set("TTDOperator", dataTTD.value.TTDOperator)
        } else {
            await setAutoFill()
        }
    })
    loadData.value = false
}

const loadRiwayatBerkas = async () => {
    isLoading.value = true
    let param = `nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}&halaman=${parseInt(route.params.index_tabs)}`;
    await useApi().get(`/emr/berkas-pasien?${param}`).then((response: any) => {
        isLoading.value = false
        dataSource.value = response.data
    })
}


const d_Kamar: any = ref([])
const fetchKamarOperasi = async (filter: any) => {
    await useApi().get(
        `/dashboard/list-bedah`
    ).then((response) => {
        d_Kamar.value = response.kamaroperasi
    })
}

const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}

const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}

const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}

const fetchJenisFile = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/berkaspasien_m?select=id,nama,isklaim`)
    d_Berkas.value = response.filter(item => item.label.toLowerCase().includes('operasi')).map(item => ({
        value: item.value,
        label: item.label,
        isklaim: item.isklaim
    }));
}

async function fetchDokter(filter: any) {
    let query = ''
    if (filter) {
        query = filter.query
    }
    const response = await useApi().get(`/general/dokter-paging?name= ${query}&limit=10`)
    // d_Dokters.value =
    d_Dokter.value = response.dokter.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
    })
    // return response.dokter.map((item: any) => {
    //     return { value: item.id, label: item.namalengkap, default: item }
    // })
}

const onSelect = async (filez: any) => {
    const file = filez.files[0];
    filePasien.value = file
}

const edit = async (e: any) => {
    item.author = e.author
    item.norec = e.norec
    item.keterangan = e.deskripsi
    item.nama = e.nama
    d_Berkas.value.forEach((element: any) => {
        if (e.objectberkaspasien == element.id) {
            item.namafile = element
        }
    });
    let path = 'berkaspasien/' + e.nocm + '/' + e.namafile
    let file = await H.getFileBE(path);

    filePasien.value = file
    filePasien.value.name = e.namafile
    modalInput.value = true
}
const hapus = async (e: any) => {
    e.loadingHapus = true
    await useApi().post(`/emr/hapus-berkas-pasien`, { 'norec': e.norec, }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayatBerkas()
    })
}

const lihat = async (e: any) => {
    H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile);
}

const simpanFile = async () => {
    if (!item.namafile) {
        H.alert('error', 'Jenis File harus di isi')
        return
    }
    if (!item.nama) {
        H.alert('error', 'Nama harus di isi')
        return
    }
    if (!filePasien.value) {
        H.alert('error', 'File harus di unggah')
        return
    }
    const formData = new FormData()
    formData.append('filePasien', filePasien.value)
    formData.append('norec', item.norec ? item.norec : '')
    formData.append('noregistrasi', props.registrasi.noregistrasi)
    formData.append('nocm', props.pasien.nocm)
    formData.append('norec_apd', props.registrasi.norec_apd)
    formData.append('namafile', item.namafile.label)
    formData.append('isklaim', item.namafile.isklaim)
    formData.append('keterangan', item.keterangan ? item.keterangan : null)
    formData.append('objectberkaspasien', item.namafile.value)
    formData.append('nama', item.nama)
    formData.append('author', item.author)
    formData.append('halaman', parseInt(route.params.index_tabs))
    isLoading.value = true
    // await useApi().post('/emr/simpan-berkas-pasien-old', formData).then((r) => {
    //    isLoading.value = false
    //    loadRiwayatBerkas()
    //    modalInput.value = false
    // }).catch((e: any) => {
    //    isLoading.value = false
    // })
    await useApi().post('/emr/simpan-berkas-pasien', formData).then((r) => {
        isLoading.value = false
        loadRiwayat()
        modalInput.value = false
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object['TTDLaporanOperasi'] = H.tandaTangan().get("TTDLaporanOperasi");
    object['TTDOperator'] = H.tandaTangan().get("TTDOperator");
    if (route.params.index_tabs) {
        object.index_tabs = parseInt(route.params.index_tabs)
    }
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': lockedFormUrl.value,
        'name_form': lockedFormName.value,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
            input.value.id = response.id
            sudahDisimpan.value = true
            ID_EMR.value = response.id;
            loadRiwayat()
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const kembaliKeun = () => {
    window.history.back()
}

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        console.log()
        return;
    }
    let ID = input.id ? input.id : ''
    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true

    useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
        isLoading.value = false
        input.value.namatemplate = null
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        if (responselast.length) {
            listTemplate.value = responselast //set ke inputan
            showModalTemplate.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
    })
}

const addTemplate = (response: any) => {
    input.value = response
    delete input.value['id']
    delete input.value['_id']
    input.value.namatemplate = null
    if (typeof input.value.namaOperasi === 'string') {
        input.value.namaOperasi = [input.value.namaOperasi]
    } else if (!Array.isArray(input.value.namaOperasi)) {
        input.value.namaOperasi = []
    }
    showModalTemplateFix.value = false
    H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
    input.value = response //set ke inputan
    delete input.value.namatemplate;
    delete input.value['_id'];
    showModalTemplate.value = false
    showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        if (responselast.length) {
            for (var x = 0; x < responselast.length; x++) {
                responselast[x].no = x + 1
                responselast[x].id = ''
            }
            listTemplateFix.value = responselast //set ke inputan
            showModalTemplateFix.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
    })
}
const setAutoFill = async () => {
    let d = input.value;

    await useApi().get(`/emr/get-order-bedah?nocmfk=${ID_PASIEN}`).then((res: any) => {
        if (res) {
            d.DiagnosaPascaBedah = res.diagnosis;
            d.tgloperasi = res.tgloperasi;
            d.jamStartOperasi = res.tgloperasi;
            d.lamaOperasi = res.durasi;
            d.namaOperasi = [res.keteranganlainnya];
        }
    })
}

const addUpload = () => {
    modalInput.value = true
    item.author = user.namaLengkap
}
const previewBerkas = async () => {
    isLoading.value = true
    await loadRiwayatBerkas()
    isLoading.value = false
    modalBerkasPreview.value = true;
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let rouutename = route.name + '-' + route.params.index_tabs
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) input.value = cache
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onMounted(() => {
    lockedFormName.value = props.FORM_NAME
    lockedFormUrl.value = props.FORM_URL
})

const lockedFormName = ref(props.FORM_NAME)
const lockedFormUrl = ref(props.FORM_URL)

let sudahDisimpan = ref(false)

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        if (!sudahDisimpan.value) {
            console.log("DISIMPAN", sudahDisimpan.value);

            const konfirmasi = H.alert('warning', 'Belum Disimpan!!!');
            if (!konfirmasi) {
                return next(false);
            }
        }

    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

// watch(input, () => {
//     sudahDisimpan.value = false
// }, { deep: true });
watch(
    () => route.params.index_tabs,
    (newValue, oldValue) => {
        input.value = {}
        input.value.jamStartOperasi = new Date()
        input.value.jamEndOperasi = new Date()
        input.value.tglOperasi = new Date()
        input.value.jamPenulisanLaporan = new Date()
        ID_EMR.value = ""
        loadRiwayat()
        let rouutename = route.name + '-' + route.params.index_tabs
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
        if (cache) {
            input.value = cache
        }

    })
watch(
    () => input.value,
    (newValue, oldValue) => {
        let rouutename = route.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        let timeout = null;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(() => {
            H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue);
        }, 500);
    }, { deep: true })
// watch(
//   () => route.params.index_tabs,
//   (newValue, oldValue) => {
//     console.log("watch 1");
//     try {
//       let rouutename = route.name + '-' + route.params.index_tabs
//       H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
//       console.log(`TAB~${props.registrasi.noregistrasi}~${rouutename}`);
//       input.value = {}
//       console.log(input.value);
//     } catch (error) {
//       console.error('Error leave cache TAB EMR:', error);
//     }
//   }
// );
// watch(
//   () => route.params.index_tabs,
//   async (newValue, oldValue) => {
//     console.log("watch 2");
//     input.value = {}
//       try {
//         await loadRiwayat()
//         let rouutename = route.name + '-' + route.params.index_tabs
//         let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
//         console.log(`TAB~${props.registrasi.noregistrasi}~${rouutename}` ,JSON.stringify(cache));
//         console.log(Object.keys(cache).length);
//         if (cache){
//           input.value = cache
//         }else{
//           input.value ={}
//         }
//       } catch (error) {
//         console.error('Error mount cache TAB EMR:', error);
//       }
//     }
// );

const tambahNmOperasi = () => {
    //input.value.namaOperasi.push(['']);
    input.value.namaOperasi.push('');
}

setView()
loadRiwayat()
fetchJenisFile()
</script>

<style lang="scss">
.table-fro {
    width: 100%;
    border: 1px solid black;
}

.th-fro,
.td-fro {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
}

.setFRO-center {
    text-align: center !important;
}

.p-fieldset-legend {
    margin-left: 15px;
}
</style>
